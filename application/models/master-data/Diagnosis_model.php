<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Diagnosis_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableDiagnosis = "tbdiagnosis";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idDiagnosis", "kodeDiagnosis", "namaDiagnosis", "deskripsi"];
		$output = build_filter_table($posted, $orders_cols, [], "tbdiagnosis.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaDiagnosis LIKE '%" . $output->search . "%' OR kodeDiagnosis LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbdiagnosis $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-diagnose^update', true))
				$link .= '<a href="#" class="link-edit-diagnosis" data-id="' . $dt->idDiagnosis . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-diagnose^delete', true))
				$link .= '<a href="#" class="link-delete-diagnosis" data-id="' . $dt->idDiagnosis . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idDiagnosis,
				$dt->kodeDiagnosis,
				$dt->namaDiagnosis,
				$dt->deskripsi,
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
		$this->db->select('idDiagnosis as _id,kodeDiagnosis,namaDiagnosis,deskripsi');
		$this->db->from($this->tableDiagnosis);
		$this->db->where($where);
		return $this->db->get()->result();
	}

	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableDiagnosis)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableDiagnosis, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idDiagnosis')->from($this->tableDiagnosis);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idDiagnosis!=", $where['idDiagnosis']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableDiagnosis, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _delete($where)
	{
		$this->db->delete($this->tableDiagnosis, $where);
		return $this->db->affected_rows();
	}
	public function diagnosislist($clinic_id)
	{
		$this->db->select(array('kodeDiagnosis', 'namaDiagnosis', 'deskripsi'));
		$this->db->from('tbdiagnosis');
		$this->db->where("clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}
}
