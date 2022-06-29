<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model {	
	function __construct(){
        parent::__construct();
        $this->load->database();
				$this->tableAccountPasien='c_userpasien';
  }
	function get_account($id){
		$this->db->select('id,email,name,hp')->from($this->tableAccountPasien);
		$this->db->where('id',$id);
		$result=$this->db->get()->result_object();
		return $result;
	}
	function update_account($data,$where){
		$this->db->update($this->tableAccountPasien,$data,$where);
		return $this->db->affected_rows();
	}
}

/* End of file Account_model.php */
/* Location: ./application/models/webview/Account_model.php */
