<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jenispemeriksaan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableJenisPemeriksaan = "lab_jenis_pemeriksaan";
		$this->tableItemJenisPemeriksaan = "lab_item_jenis_pemeriksaan";
		$this->tableSubItemJenisPemeriksaan = "lab_subitem_jenis_pemeriksaan";
		$this->tableOpsiHasil = "lab_opsi_hasil";
		$this->tableJenisSampling = "lab_jenis_sampling";
		$this->tableTarif = "lab_tarif";
		$this->load->helper('ctc');
	}
	function _load_dt_jenis_pemeriksaan($posted)
	{
		$orders_cols = ["a.jenis", "b.nama_pemeriksaan", "b.hasil", "b.nilai_rujukan", "b.metode", "id"];
		$output = build_filter_table($posted, $orders_cols);
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere = " WHERE a.jenis LIKE '%" . $output->search . "%' OR b.nama_pemeriksaan LIKE '%" . $output->search . "%'";
		}
		$sLimit = $output->limit;
		$sGroup = " GROUP BY a.id";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS a.id,a.jenis,a.metode,GROUP_CONCAT(DISTINCT b.nama_pemeriksaan ORDER BY b.id ASC,b.is_main DESC SEPARATOR '<br>') as pemeriksaan,GROUP_CONCAT(b.hasil ORDER BY b.id ASC,b.is_main DESC SEPARATOR '<br>') as hasil,GROUP_CONCAT(b.nilai_rujukan ORDER BY b.id ASC,b.is_main DESC SEPARATOR '<br>') as nilai_rujukan,a.created_by,a.created_at FROM lab_jenis_pemeriksaan a LEFT JOIN lab_opsi_hasil b ON a.id=b.jenis_id $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			return [
				$dt->jenis,
				$dt->pemeriksaan,
				$dt->hasil,
				$dt->nilai_rujukan,
				$dt->metode,
				'<a href="#" class="link-edit-jenis-pemeriksaan" data-id="' . $dt->id . '"><i class="fa fa-edit"></i></a>  &nbsp;'
				// <a href="#" class="link-delete-jenis-pemeriksaan" data-id="'.$dt->id.'"><i class="fa fa-trash text-danger"></i></a>'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		// unset($output->limit);
		// unset($output->search);
		// unset($output->order);
		// unset($output->where);
		return (array) $output;
	}
	function _search($where)
	{
		$this->db->select('id as _id,jenis,metode,category');
		$this->db->from($this->tableJenisPemeriksaan);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _list_jenis_pemeriksaan($clinic_id)
	{
		$this->db->select('id,jenis')->from($this->tableJenisPemeriksaan)->where(array("clinic_id" => $clinic_id))->order_by("jenis");
		return $this->db->get()->result_object();
	}
	function _search_list_hasil($where = [])
	{
		$this->db->select('id,jenis_id,group_hasil,nama_pemeriksaan,hasil,nilai_rujukan,metode,is_main');
		$this->db->from($this->tableOpsiHasil);
		if (!empty($where)) {
			$this->db->where($where);
		}
		$this->db->order_by("id asc,is_main desc");
		return $this->db->get()->result_object();
	}
	function _search_select2($key = "", $not_in = [], $clinic_id)
	{
		$this->db->select('a.id as id,a.jenis as text');
		$this->db->from($this->tableJenisPemeriksaan . " a");
		$this->db->join($this->tableJenisPemeriksaan . " b", "a.jenis=b.jenis");
		$this->db->where("a.clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('a.nama_pemeriksaan', $key);
			$this->db->or_like('a.jenis', $key);
		}
		if (!empty($not_in)) {
			$this->db->where_not_in("a.id", $not_in);
		}
		$this->db->group_by("a.jenis");
		$this->db->order_by("a.id");
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select2_with_cost($key = "", $not_in = [])
	{
		$this->db->select('a.id as id,CONCAT(a.jenis, " (Rp. ",FORMAT(c.nominal,0),")") as text', FALSE);
		$this->db->from($this->tableJenisPemeriksaan . " a");
		$this->db->join($this->tableJenisPemeriksaan . " b", "a.jenis=b.jenis");
		$this->db->join($this->tableTarif . " c", "a.id=c.jenis_id");
		if ($key != "") {
			$this->db->like('a.nama_pemeriksaan', $key);
			$this->db->or_like('a.jenis', $key);
		}
		if (!empty($not_in)) {
			$this->db->where_not_in("a.id", $not_in);
		}
		$this->db->group_by("a.jenis");
		$this->db->order_by("a.id");
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_sampling_select2($key = "", $clinic_id)
	{
		$this->db->select('id ,nama_sampling as text');
		$this->db->from($this->tableJenisSampling);
		$this->db->where(array("clinic_id" => $clinic_id));
		if ($key != "") {
			$this->db->like('nama_sampling', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save_jenis($data, $where, $key)
	{
		if (empty($where)) {
			// check before insert
			$this->db->select($key)->from($this->tableJenisPemeriksaan)->where($key, $data[$key]);
			$check = $this->db->get();
			if($check->num_rows()>0) return 'exist';
			$this->db->insert($this->tableJenisPemeriksaan, $data);
			return $this->db->insert_id();
		} else {
			$this->db->select('id')->from($this->tableJenisPemeriksaan);
			$this->db->where($key, $data[$key]);
			$this->db->where("id!=", $where['id']);
			$check = $this->db->get();
			if($check->num_rows()>0) return 'exist';
			$this->db->update($this->tableJenisPemeriksaan, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _save_opsi_hasil($data, $where, $key)
	{
		if (empty($where)) {
			// check before insert
			$this->db->select($key)->from($this->tableOpsiHasil)->where($key, $data[0][$key]);
			$check = $this->db->get();
			if($check->num_rows()>0) return 'exist';
			$this->db->insert_batch($this->tableOpsiHasil, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('id')->from($this->tableOpsiHasil);
			$this->db->where($key, $data[$key]);
			$this->db->where("id!=", $where['id']);
			$check = $this->db->get();
			if($check->num_rows()>0) return 'exist';
			$this->db->update($this->tableOpsiHasil, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _update_opsi($data, $where, $key)
	{
		// $this->db->select('id')->from($this->tableOpsiHasil);
		// $this->db->where($key,$data[$key]);
		// $this->db->where("id!=",$where['id']);
		// $check=$this->db->get()->result();
		// if(!empty($check)) return 'exist';
		$this->db->update($this->tableOpsiHasil, $data, $where);
		return $this->db->affected_rows();
	}

	function _delete($id)
	{
		$this->db->delete($this->tableJenisPemeriksaan, array("id" => $id));
		return $this->db->affected_rows();
	}
	function _delete_opsi($id)
	{
		$this->db->where_in("id", $id);
		$this->db->delete($this->tableOpsiHasil);
		return $this->db->affected_rows();
	}

	/* new update for jenis pemeriksaan ** umum */
	function checkJenis($where)
	{
		$query = $this->db->get_where($this->tableJenisPemeriksaan, $where);
		if ($query->num_rows() > 0) {
			return 'exist';
		} else {
			return "";
		}
	}
	function saveJenisPemeriksaanNew($data)
	{
		$this->db->insert($this->tableJenisPemeriksaan, $data);
		return $this->db->insert_id();
	}
	function updateJenisPemeriksaanNew($data, $where)
	{
		$this->db->update($this->tableJenisPemeriksaan, $data, $where);
		return $this->db->affected_rows();
	}
	function saveItemJenisPemeriksaan($data)
	{
		$this->db->insert($this->tableItemJenisPemeriksaan, $data);
		return $this->db->insert_id();
	}
	function saveSubItemJenisPemeriksaan($data)
	{
		$this->db->insert($this->tableSubItemJenisPemeriksaan, $data);
		return $this->db->insert_id();
	}

	function _search_item_jenis_pemeriksaan($where)
	{
		$this->db->select("*")->from($this->tableItemJenisPemeriksaan);
		$this->db->where($where);
		$this->db->order_by("sub_id", "ASC");
		return $this->db->get()->result_array();
	}
	function _search_subitem_jenis_pemeriksaan($where)
	{
		$query = $this->db->get_where($this->tableSubItemJenisPemeriksaan, $where);
		return $query->result_array();
	}
	function _deleteItemJenisPemeriksaan($where)
	{
		$this->db->delete($this->tableItemJenisPemeriksaan, $where);
		return $this->db->affected_rows();
	}
	function _deleteSubItemJenisPemeriksaan($where)
	{
		$this->db->delete($this->tableSubItemJenisPemeriksaan, $where);
		return $this->db->affected_rows();
	}
}

/* End of file Pasien_model.php */
