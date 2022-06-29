<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Other_setting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableCompany = "tb_company_detail";
		$this->tableDocRequirement = "c_doc_requirements";
		$this->load->helper('ctc');
	}
	function get_com_profile($where=[])
	{
		$this->db->select("*");
		$this->db->from($this->tableCompany);
		if(!empty($where)) $this->db->where($where);
		$this->db->limit(1);
		// $this->db->where("id", 1);
		return $this->db->get()->result();
	}
	function save_setting_doc_requirements($data, $where, $key)
	{
		if (empty($where)) {
			// $this->db->select($key)->from($this->tableDocRequirement)->where($key, $data[$key]);
			// $check = $this->db->get()->result();
			// if (!empty($check)) {
			// 	$this->db->update($this->tableDocRequirement, $data, array("code" => $data[$key]));
			// 	return $this->db->affected_rows();
			// } else {
				$this->db->insert($this->tableDocRequirement, $data);
				return $this->db->affected_rows();
			// }
		} else {
			// $this->db->select('code')->from($this->tableDocRequirement);
			// $this->db->where($where);
			// $this->db->where("code!=", $where['code']);
			// $check = $this->db->get()->result();
			// if (!empty($check)) return 'exist';
			$this->db->update($this->tableDocRequirement, $data, $where);
			return $this->db->affected_rows();
		}
	}
	function get_setting_doc_requirements($where = [])
	{
		$this->db->select('*')->from($this->tableDocRequirement);
		if (!empty($where)) $this->db->where($where);
		return $this->db->get()->result_object();
	}
	function _save_company($data,$where=[])
	{
		if(empty($where)){
			$this->db->insert($this->tableCompany,$data);
		}else{
			$this->db->update($this->tableCompany, $data,$where);
		}
		return $this->db->affected_rows();
	}
	function check_clinic($clinic_id){
		$query=$this->db->get_where($this->tableCompany,array("clinic_id"=>$clinic_id));
		if($query->num_rows()>0){
				return $query->row();
		}else{
				return null;
		}
	}
}
