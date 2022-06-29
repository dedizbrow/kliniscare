<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Klinik_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableRegisteredClinic = "c_registered_clinics";
		$this->TableBaseMenu = "c_base_menu";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["rc_id", "clinic_code", "clinic_name", "logo", "is_active", "reg_date","account_type","license_duration","license_type","phone","email","remarks","timestamp"];
		$output = build_filter_table($posted, $orders_cols);
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " clinic_name LIKE '%" . $output->search . "%'";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM c_registered_clinics $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$end_license=date("Y-m-d H:i:s",strtotime($dt->reg_date." +".$dt->license_duration." ".$dt->license_type));
			$status_license=($end_license<date("Y-m-d H:i:s")) ? "Expired":"Active";
			return [
				$dt->rc_id,
				$dt->clinic_code,
				$dt->clinic_name,
				$dt->logo,
				$dt->reg_date,
				$dt->is_active,
				$dt->account_type,
				$dt->license_duration." ".lang('label_'.$dt->license_type),
				$end_license,
				$dt->phone,
				$dt->email,
				$dt->remarks,
				$dt->timestamp,
				'<a href="#" class="link-edit-klinik" data-id="' . $dt->rc_id . '"><i class="fa fa-edit tx-danger"></i></a>',
				$status_license
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}

	function _search($where)
	{
		$this->db->select('*');
		$this->db->from($this->tableRegisteredClinic);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _save($data, $where, $key)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableRegisteredClinic)->where($key, $data[$key]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableRegisteredClinic, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('rc_id')->from($this->tableRegisteredClinic);
			$this->db->where($key, $data[$key]);
			$this->db->where("rc_id!=", $where['rc_id']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableRegisteredClinic, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function _save_get_clinic_id($data)
	{
		$this->db->trans_start();
		$this->db->insert($this->tableRegisteredClinic, $data);
		$clinic_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $clinic_id;
	}
	function enable_disable_clinic($data, $where)
	{
		$this->db->update($this->tableRegisteredClinic, $data, $where);
		return $this->db->affected_rows();
	}
	function load_base_menu()
	{
		$this->db->select("id,title,icon");
		$this->db->from($this->TableBaseMenu);
		$this->db->order_by("order_no");
		return $this->db->get()->result_object();
	}
}
