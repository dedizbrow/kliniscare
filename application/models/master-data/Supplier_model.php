<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableSupplier = "tbsupplier";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idSupplier", "kodeSupplier", "namaSupplier", "alamat", "notelp"];
		$output = build_filter_table($posted, $orders_cols, [], "tbsupplier.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaSupplier LIKE '%" . $output->search . "%' OR kodeSupplier LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbsupplier $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-supplier^update', true))
				$link .= '<a href="#" class="link-edit-supplier" data-id="' . $dt->idSupplier . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-supplier^delete', true))
				$link .= '<a href="#" class="link-delete-supplier" data-id="' . $dt->idSupplier . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idSupplier,
				$dt->kodeSupplier,
				$dt->namaSupplier,
				$dt->alamat,
				$dt->notelp,
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
		$this->db->select('idSupplier as _id, kodeSupplier,namaSupplier,alamat,notelp');
		$this->db->from($this->tableSupplier);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableSupplier)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableSupplier, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idSupplier')->from($this->tableSupplier);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idSupplier!=", $where['idSupplier']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableSupplier, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _delete($where)
	{
		$this->db->delete($this->tableSupplier, $where);
		return $this->db->affected_rows();
	}
	public function supplierlist($clinic_id)
	{
		$this->db->select(array('kodeSupplier', 'namaSupplier', 'alamat', 'notelp'));
		$this->db->from('tbsupplier');
		$this->db->where("tbsupplier.clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function insert_($data)
	{
		$this->db->insert_batch('tbsupplier', $data);
		return $this->db->affected_rows();
	}
}
