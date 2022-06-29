<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Poli_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePoli = "tbpoli";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idPoli", "namaPoli", "kodePoli", "label_antrian"];
		$output = build_filter_table($posted, $orders_cols, [], "tbpoli.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (namaPoli LIKE '%" . $output->search . "%' OR kodePoli LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbpoli $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-poli^update', true))
				$link .= '<a href="#" class="link-edit-poli" data-id="' . $dt->idPoli . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-poli^delete', true))
				$link .= '<a href="#" class="link-delete-poli" data-id="' . $dt->idPoli . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idPoli,
				$dt->namaPoli,
				$dt->kodePoli,
				$dt->label_antrian,
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
		$this->db->select('idPoli as _id,namaPoli,kodePoli,label_antrian');
		$this->db->from($this->tablePoli);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tablePoli)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tablePoli, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idPoli')->from($this->tablePoli);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idPoli!=", $where['idPoli']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tablePoli, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _delete($where)
	{
		$this->db->delete($this->tablePoli, $where);
		return $this->db->affected_rows();
	}

	public function polilist($clinic_id)
	{
		$this->db->select(array('namaPoli', 'kodePoli', 'label_antrian'));
		$this->db->from('tbpoli');
		$this->db->where("clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}
}
