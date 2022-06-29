<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perujuk_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePerujuk = "tbperujuk";
		$this->tablePerujukTipe = "tbperujuk_tipe";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idPerujuk", "namaPerujuk", "namaTipe"];
		$output = build_filter_table($posted, $orders_cols, [], "tbperujuk.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaPerujuk LIKE '%" . $output->search . "%' OR namaTipe LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbperujuk left join tbperujuk_tipe on tbperujuk_tipe.idTipe=tbperujuk.tipe $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-perujuk^update', true))
				$link .= '<a href="#" class="link-edit-perujuk" data-id="' . $dt->idPerujuk . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-perujuk^delete', true))
				$link .= '<a href="#" class="link-delete-perujuk" data-id="' . $dt->idPerujuk . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idPerujuk,
				$dt->namaPerujuk,
				$dt->namaTipe,
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
		$this->db->select('idPerujuk as _id,namaPerujuk,tipe,namaTipe');
		$this->db->from($this->tablePerujuk);
		$this->db->join($this->tablePerujukTipe, 'tbperujuk_tipe.idTipe=tbperujuk.tipe');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select2($key = "", $clinic_id)
	{
		$this->db->select('idTipe as id,namaTipe as text');
		$this->db->from($this->tablePerujukTipe);
		$this->db->WHERE('clinic_id', $clinic_id);
		if ($key != "") {
			$this->db->like('namaTipe', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tablePerujuk)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tablePerujuk, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idPerujuk')->from($this->tablePerujuk);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idPerujuk!=", $where['idPerujuk']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tablePerujuk, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete($where)
	{
		$this->db->delete($this->tablePerujuk, $where);
		return $this->db->affected_rows();
	}
	function _savetipe($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tablePerujukTipe)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$checktipe = $this->db->get()->result();
		if (!empty($checktipe)) return 'exist';
		$this->db->insert($this->tablePerujukTipe, $data);
		return $this->db->affected_rows();
	}

	public function perujuklist($clinic_id)
	{
		$this->db->select(array('namaPerujuk', 'namaTipe'));
		$this->db->from('tbperujuk');
		$this->db->where("tbperujuk.clinic_id", $clinic_id);
		$this->db->join('tbperujuk_tipe', 'tbperujuk_tipe.idTipe=tbperujuk.tipe');
		$query = $this->db->get();
		return $query->result();
	}

	function _list_kategori()
	{
		$this->db->select('idTipe,namaTipe')->from($this->tablePerujukTipe)->order_by("namaTipe");
		return $this->db->get()->result_object();
	}
}
