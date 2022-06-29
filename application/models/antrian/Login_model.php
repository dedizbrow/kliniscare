<?php
class Login_model extends CI_Model 
{
	
	
	function cari_user($IdUser)
		{
		$sql ="SELECT * FROM login WHERE UserName ='$IdUser'";
		$query = $this->db->query($sql);
        return $result = $query->result();
		}
}
