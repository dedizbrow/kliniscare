<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableKaryawan = "tbkaryawan";
		$this->tableBidang = "tbkaryawan_bidang";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idKaryawan", "kodeKaryawan", "namaKaryawan", "alamat", "notelp", "email", "nama"];
		$output = build_filter_table($posted, $orders_cols, [], "tbkaryawan.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (kodeKaryawan LIKE '%" . $output->search . "%' OR namaKaryawan LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbkaryawan join tbkaryawan_bidang on tbkaryawan_bidang.id=tbkaryawan.bidang $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-karyawan^update', true))
				$link .= '<a href="#" class="link-edit-karyawan" data-id="' . $dt->idKaryawan . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-karyawan^delete', true))
				$link .= '<a href="#" class="link-delete-karyawan" data-id="' . $dt->idKaryawan . '"><i class="fa fa-trash text-danger"></i></a>';
			return [

				$dt->idKaryawan,
				$dt->kodeKaryawan,
				$dt->namaKaryawan,
				$dt->alamat,
				$dt->notelp,
				$dt->email,
				$dt->nama,
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
		$this->db->select('idKaryawan as _id,kodeKaryawan,namaKaryawan,alamat,notelp,email,bidang,id,nama');
		$this->db->from($this->tableKaryawan);
		$this->db->join($this->tableBidang, 'tbkaryawan_bidang.id=tbkaryawan.bidang');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select_bidang($key = "", $clinic_id)
	{
		$this->db->select('id ,nama as text');
		$this->db->from($this->tableBidang);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('nama', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableKaryawan)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableKaryawan, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idKaryawan')->from($this->tableKaryawan);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idKaryawan!=", $where['idKaryawan']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableKaryawan, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _save_bidang($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableBidang)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$bidang = $this->db->get()->result();
		if (!empty($bidang)) return 'exist';
		$this->db->insert($this->tableBidang, $data);
		return $this->db->affected_rows();
	}
	function _delete($where)
	{
		$this->db->delete($this->tableKaryawan, $where);
		return $this->db->affected_rows();
	}
	public function karyawanlist($clinic_id)
	{
		$this->db->select(array('kodeKaryawan', 'namaKaryawan', 'alamat', 'notelp', 'email', 'nama'));
		$this->db->from('tbkaryawan');
		$this->db->where("tbkaryawan.clinic_id", $clinic_id);
		$this->db->join($this->tableBidang, 'tbkaryawan_bidang.id=tbkaryawan.bidang');
		$query = $this->db->get();
		return $query->result();
	}
	function _list_bidang()
	{
		$this->db->select('id,nama')->from($this->tableBidang)->order_by("nama");
		return $this->db->get()->result_object();
	}
}
