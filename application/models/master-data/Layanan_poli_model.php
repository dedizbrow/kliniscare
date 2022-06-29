<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Layanan_poli_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableLayananPoli = "tbl_layanan_poli";
		$this->tablePoli = "tbpoli";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["id_layanan_poli", "namaPoli", "nama_layanan_poli", "kode_layanan_poli","harga_layanan_poli", "tarif_dokter"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_layanan_poli.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (nama_layanan_poli LIKE '%" . $output->search . "%' OR kode_layanan_poli LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_layanan_poli join tbpoli on tbpoli.idPoli=tbl_layanan_poli.id_poli $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-l-poli^update', true))
				$link .= '<a href="#" class="link-edit-layanan-poli" data-id="' . $dt->id_layanan_poli . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-l-poli^delete', true))
				$link .= '<a href="#" class="link-delete-layanan-poli" data-id="' . $dt->id_layanan_poli . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->id_layanan_poli,
				$dt->namaPoli,
				$dt->nama_layanan_poli,
				$dt->kode_layanan_poli,
				number_format($dt->harga_layanan_poli),
				number_format($dt->tarif_dokter),
				
				$link
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search($where)
	{
		$this->db->select('id_layanan_poli as _id,id_poli,namaPoli,nama_layanan_poli,harga_layanan_poli,kode_layanan_poli,tarif_dokter_percent,tarif_dokter');
		$this->db->from($this->tableLayananPoli);
		$this->db->join($this->tablePoli, 'tbpoli.idPoli=tbl_layanan_poli.id_poli');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableLayananPoli)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableLayananPoli, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('id_layanan_poli')->from($this->tableLayananPoli);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("id_layanan_poli!=", $where['id_layanan_poli']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableLayananPoli, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _delete($where)
	{
		$this->db->delete($this->tableLayananPoli, $where);
		return $this->db->affected_rows();
	}

	function _search_select2($key = "", $clinic_id)
	{
		$this->db->select('idPoli as id,namaPoli as text');
		$this->db->from($this->tablePoli);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaPoli', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}

	public function layanan_list($clinic_id)
	{
		$this->db->select(array('nama_layanan_poli', 'kode_layanan_poli', 'harga_layanan_poli', 'namaPoli'));
		$this->db->from('tbl_layanan_poli');
		$this->db->where("tbl_layanan_poli.clinic_id", $clinic_id);
		$this->db->join($this->tablePoli, 'tbpoli.idPoli=tbl_layanan_poli.id_poli');
		$query = $this->db->get();
		return $query->result();
	}
	function _list_poli()
	{
		$this->db->select('idPoli,namaPoli')->from($this->tablePoli)->order_by("namaPoli");
		return $this->db->get()->result_object();
	}
}
