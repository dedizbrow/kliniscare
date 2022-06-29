<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {	
	function __construct(){
        parent::__construct();
        $this->load->database();
				$this->tableAccountPasien='c_userpasien';
  }
	function check_account_exist($email){
		$this->db->select('email')->from($this->tableAccountPasien);
		$this->db->where('email',$email);
		$result=$this->db->get()->result_array();
		if(!empty($result)){
			return 'exist';
		}else{
			return 'not exist';
		}
	}
	function register_account_pasien($data){
		$this->db->insert($this->tableAccountPasien,$data);
		return $this->db->insert_id();
	}
	function signin($username,$password){
		$this->db->select('id,email,name,enabled')->from($this->tableAccountPasien);
		$this->db->where(array("email"=>$username,"passwd"=>$password));
		return $this->db->get()->result_object();
	}
}

/* End of file Auth_model.php */
/* Location: ./application/models/webview/Auth_model.php */
