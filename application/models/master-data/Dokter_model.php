<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dokter_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableDokter = "tbdaftardokter";
		$this->tableSpesialisasi = "tbspesialisasi";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idDokter", "namaDokter", "namaSpesialisasi", "nip", "position", "notelp", "idBpjs", "status"];
		$output = build_filter_table($posted, $orders_cols, [], "tbdaftardokter.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaDokter LIKE '%" . $output->search . "%' OR idBpjs LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbdaftardokter left join tbspesialisasi on tbspesialisasi.idSpesialisasi=tbdaftardokter.spesialisasi $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-dokter^update', true))
				$link .= '<a href="#" class="link-edit-dokter" data-id="' . $dt->idDokter . '"><i class="fa fa-edit" ></i></a>  &nbsp;';
			if (isAllowed('c-dokter^delete', true))
				$link .= '<a href="#" class="link-delete-dokter" data-id="' . $dt->idDokter . '"><i class="fa fa-trash text-danger"></i></a>';
			return [

				$dt->idDokter,
				$dt->namaDokter,
				$dt->namaSpesialisasi,
				$dt->nip,
				$dt->position,
				$dt->notelp,
				$dt->idBpjs,
				$dt->status,
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
		$this->db->select('idDokter as _id,namaDokter,idSpesialisasi,nip,position,namaSpesialisasi,notelp,idBpjs,status');
		$this->db->from($this->tableDokter);
		$this->db->join($this->tableSpesialisasi, 'tbspesialisasi.idSpesialisasi=tbdaftardokter.spesialisasi');
		$this->db->where($where);
		return $this->db->get()->result();
	}


	function _search_select2($key = "", $clinic_id)
	{
		$this->db->select('idSpesialisasi as id, namaSpesialisasi as text');
		$this->db->from($this->tableSpesialisasi);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaSpesialisasi', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _list_spesialisasi()
	{
		$this->db->select('idSpesialisasi,namaSpesialisasi')->from($this->tableSpesialisasi)->order_by("namaSpesialisasi");
		return $this->db->get()->result_object();
	}
	function _search_dokter_select2($key = "", $clinic_id)
	{
		$this->db->select('idDokter as id, namaDokter as text');
		$this->db->from($this->tableDokter);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaDokter', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableDokter)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableDokter, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idDokter')->from($this->tableDokter);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idDokter!=", $where['idDokter']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableDokter, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _savespesialisasi($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableSpesialisasi)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$checkspesialisasi = $this->db->get()->result();
		if (!empty($checkspesialisasi)) return 'exist';
		$this->db->insert($this->tableSpesialisasi, $data);
		return $this->db->affected_rows();
	}

	function _delete($where)
	{
		$this->db->delete($this->tableDokter, $where);
		return $this->db->affected_rows();
	}
	public function dokterlist($clinic_id)
	{
		$this->db->select(array('namaDokter', 'namaSpesialisasi', 'notelp', 'idBpjs', 'status', 'nip', 'position'));
		$this->db->from('tbdaftardokter');
		$this->db->where("tbdaftardokter.clinic_id", $clinic_id);
		$this->db->join($this->tableSpesialisasi, 'tbspesialisasi.idSpesialisasi=tbdaftardokter.spesialisasi');
		$query = $this->db->get();
		return $query->result();
	}
}
