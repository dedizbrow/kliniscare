<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Asuransi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableAsuransi = "tbasuransi";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["idAsuransi", "namaAsuransi",  "telp", "alamat", "email"];
		$output = build_filter_table($posted, $orders_cols, [], "tbasuransi.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaAsuransi LIKE '%" . $output->search . "%' OR email LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbasuransi $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-asuransi^update', true))
				$link .= '<a href="#" class="link-edit-asuransi" data-id="' . $dt->idAsuransi . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-asuransi^delete', true))
				$link .= '<a href="#" class="link-delete-asuransi" data-id="' . $dt->idAsuransi . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idAsuransi,
				$dt->namaAsuransi,
				$dt->telp,
				$dt->alamat,
				$dt->email,
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
		$this->db->select('idAsuransi as _id, namaAsuransi, telp, alamat,email');
		$this->db->from($this->tableAsuransi);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableAsuransi)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableAsuransi, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idAsuransi')->from($this->tableAsuransi);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idAsuransi!=", $where['idAsuransi']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableAsuransi, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete($where)
	{
		$this->db->delete($this->tableAsuransi, $where);
		return $this->db->affected_rows();
	}
	public function asuransilist($clinic_id)
	{
		$this->db->select(array('namaAsuransi', 'telp', 'alamat', 'email'));
		$this->db->from('tbasuransi');
		$this->db->where("clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}
}
