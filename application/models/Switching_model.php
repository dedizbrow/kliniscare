<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Switching_model extends CI_Model {
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->TableUser="c_users";
    }
	function switch_template($data,$where){
		$this->db->where($where);
		$this->db->update($this->TableUser,$data);
		return $this->db->affected_rows();
	}

}

/* End of file Switching_model.php */
/* Location: ./application/models/Switching_model.php */