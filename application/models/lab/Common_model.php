<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {
	function __construct(){
        parent::__construct();
        $this->load->database();
        $this->TableAttachment="c_attachments";
    }

    function inline_insert($table,$data){
    	if($this->isExist('id',$table,array("name"=>$data['name']))){
    		return 'exist';
		}else{
			$this->db->insert($table,$data);
			return $this->db->affected_rows();
		}
    }

    function isExist($select,$table,$where){
        $this->db->select($select);
        $query=$this->db->get_where($table,$where);
        if($query->num_rows()>0){
            return $query->result();
        }else{
            return null;
        }
    }
    function common_search_select2($table,$key){
        if($table=='c_users'){
            $this->db->select('uid as id,name as text');
            // show users but super admin developer
            $this->db->where('uname!=',$this->config->item('super_admin_id'));
            $this->db->limit(20);
        }else{
            $this->db->select('id,name as text');
            if($table=="c_work_type"){
                $this->db->where("category",'');
            }
            $this->db->limit(100);
        }
        $this->db->from($table);
        $this->db->like('name',$key,'both');
        return $this->db->get()->result_array();    
        
    }
    function get_base_menus($access_codes){
        $this->db->select('id as base_id,title,end_point,icon,has_child,access_code,actions_code');
        if($access_codes!="c-spadmin" && empty($access_codes)){
            $this->db->where(array("access_code"=>"public"));
        }else
        if($access_codes!='c-spadmin' && !empty($access_codes)){
            $this->db->where_in("access_code",$access_codes);
        }
        //$this->db->where(array("has_child"=>0));
        $this->db->from("c_base_menu");
        $this->db->order_by("order_no","asc");
        return $this->db->get()->result();
    }
    function get_menus($access_codes){
        $this->db->select('a.base_id,a.title,a.end_point,a.access_code,a.actions_code,1 as is_child');
        $this->db->from('c_menus as a');
        $this->db->join('c_base_menu as b','a.base_id=b.id','INNER');
        if($access_codes!="c-spadmin" && empty($access_codes)){
            $this->db->where(array("a.access_code"=>"public"));
        }else
        if($access_codes!='c-spadmin' && !empty($access_codes)){
            $this->db->where_in("a.access_code",$access_codes);
        }
        $this->db->where(array("enabled"=>1));
        $this->db->order_by("a.order_no ASC,a.id");
        return $this->db->get()->result();
    }
    
    function save_attachment($data){
        $this->db->insert($this->TableAttachment,$data);
        return $this->db->insert_id();
    }
    function remove_attachment($where){
        $get_path=$this->db->get_where($this->TableAttachment,$where);
        $result=$get_path->result();
        if(!empty($result)){
            $dt=$result[0];
            if(unlink($dt->path_file)){
                $this->db->where($where);
                $this->db->delete($this->TableAttachment);
                return $this->db->affected_rows();
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
}

/* End of file Common_model.php */
/* Location: ./application/models/Common_model.php */
