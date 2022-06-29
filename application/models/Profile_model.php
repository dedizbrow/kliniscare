<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {
	function __construct(){
        parent::__construct();
        $this->load->database();
        $this->TableUser="c_users";
        
    }
    function my_profile($uid){
    	$this->db->select("uname as username,email,name,accessibility,actions_code,level,template,lang,profile,enabled");
    	$this->db->from($this->TableUser);
    	$this->db->where("uid",$uid);
    	return $this->db->get()->result();
    }
    function save_user($data,$where){
    	$this->db->select('uid')->from($this->TableUser);
	    $this->db->where("uname",$data['uname']);
	    $this->db->where("uid!=",$where['uid']);
	    $check=$this->db->get()->result();
	    
	    if(!empty($check)){
	        return 'exist';  
	    } 
	    $this->db->update($this->TableUser,$data,$where);
	    return $this->db->affected_rows();
    }
	

}

/* End of file Profile_model.php */
/* Location: ./application/models/Profile_model.php */