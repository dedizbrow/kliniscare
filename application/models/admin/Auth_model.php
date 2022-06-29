<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {	
	function __construct(){
        parent::__construct();
        $this->load->database();
  }
  function signin($username,$password){
		$this->db->select('uid,user.email,name,uname as usrname,accessibility_base,actions_code_base,accessibility,actions_code,level,last_page,template,lang,profile,IFNULL(provider_id,"") as provider_id,IFNULL(provider.nama,"") as pv_name,IFNULL(provider.provider_group,"") as pv_group,IFNULL(clinic.rc_id,"allclinic") as clinic_id,IFNULL(clinic.clinic_name,"SEMUA KLINIK") as clinic_name,IFNULL(clinic.logo,"") as clinic_logo,IFNULL(clinic.is_active,"0") as is_clinic_active,
		clinic.reg_date,clinic.license_duration,clinic.license_type
		');
		$this->db->from('c_users user');
		$this->db->join('lab_data_provider provider','user.provider_id=provider.id','LEFT');
		$this->db->join('c_registered_clinics clinic','user.clinic_id=clinic.rc_id','LEFT');
		$this->db->where(array(
			"uname"=>$username,
			"passwd"=>$password,
			"enabled"=>1
		));
		$res_auth=$this->db->get()->result();
	    return $res_auth;
  }
	function search_enabled_menus($clinic_id)
	{
		$this->db->select('enabled_menus');
		$this->db->from('c_registered_clinics');
		$this->db->where('rc_id',$clinic_id);
		return $this->db->get()->result_object();
	}
}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */
