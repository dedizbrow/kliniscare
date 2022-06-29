<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Rekanan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableRekanan = "tbrekanan";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idRekanan", "namaRekanan",  "telp", "alamat", "email"];
		$output = build_filter_table($posted, $orders_cols, [], "tbrekanan.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaRekanan LIKE '%" . $output->search . "%' OR email LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbrekanan $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-rekanan^update', true))
				$link .= '<a href="#" class="link-edit-rekanan" data-id="' . $dt->idRekanan . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-rekanan^delete', true))
				$link .= '<a href="#" class="link-delete-rekanan" data-id="' . $dt->idRekanan . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idRekanan,
				$dt->namaRekanan,
				$dt->alamat,
				$dt->telp,
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
		$this->db->select('idRekanan as _id, namaRekanan, telp, alamat,email');
		$this->db->from($this->tableRekanan);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableRekanan)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableRekanan, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idRekanan')->from($this->tableRekanan);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idRekanan!=", $where['idRekanan']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableRekanan, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete($where)
	{
		$this->db->delete($this->tableRekanan, $where);
		return $this->db->affected_rows();
	}
	public function rekananlist($clinic_id)
	{
		$this->db->select(array('namaRekanan', 'telp', 'alamat', 'email'));
		$this->db->from('tbrekanan');
		$this->db->where("tbrekanan.clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}
}
