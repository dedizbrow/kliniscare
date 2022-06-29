<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adms_model extends CI_Model {	
	function __construct(){
        parent::__construct();
        $this->load->database();
  }
  function load_account_backup(){
		$this->db->select('*');
		$this->db->from('c_backup_ref');
		$this->db->limit(1);
		return $this->db->get()->result_object();
  }
	function save_backup_db($data){
		$this->db->insert('c_backup_list',$data);
		return $this->db->insert_id();
	}
	function delete_old_backup_db($duration){
		$this->db->query('DELETE FROM c_backup_list WHERE created_at < NOW() - INTERVAL '.$duration.' DAY');
		return $this->db->affected_rows();
	}
}
