<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrative_model extends CI_Model {
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->TableUser="c_users";
        $this->TableBaseMenu="c_base_menu";
        $this->TableMenu="c_menus";
        $this->TableProvider="lab_data_provider";
        $this->TableSetting="lab_settings";
    }
    function dt_users($where){
    	$this->datatables->select('uid,name,IFNULL(provider.nama,"") as nama_provider,uname as username,email,enabled');
    	$this->datatables->from($this->TableUser." user");
		$this->datatables->join("lab_data_provider provider","user.provider_id=provider.id","left");
        $this->datatables->where("uname!=",conf("super_admin_id"));
        $this->datatables->add_column('edit', '<a href="#" class="link-edit-user" data-id="$1"><i class="fa fa-edit"></i></a>', 'uid');
        return $this->datatables->generate();
    }
    function search_user($where){
        $this->db->select('uid as user_id,name,uname as username,email,accessibility,actions_code,level,provider_id,provider.nama as nama_provider');
        $this->db->from($this->TableUser." user");
		$this->db->join($this->TableProvider." provider","user.provider_id=provider.id","left");
        $this->db->where($where);
        return $this->db->get()->result();
    }
    function save_user($data,$where){
        if(empty($where)){
            // check before insert
            $this->db->select('uid')->from($this->TableUser)->where("uname",$data['uname']);
            $check=$this->db->get()->result();
            if(!empty($check)) return 'exist';
            $this->db->insert($this->TableUser,$data);
            return $this->db->affected_rows();
        }else{
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
    function enable_disable_user($data,$where){
        $this->db->update($this->TableUser,$data,$where);
        return $this->db->affected_rows();
    }
    function load_base_menu(){
        $this->db->select("title,icon,access_code,actions_code");
        $this->db->from($this->TableBaseMenu);
        $this->db->order_by("order_no");
        return $this->db->get()->result();
    }

	/* SETTINGS */
	function _save_update_setting($data,$where){
		$this->db->update($this->tableSetting,$data,$where);
		return $this->db->affected_rows();
	}
	function _get_setting($code=''){
		$this->db->select('id as _id,title,content,size,width,height')->from($this->TableSetting)->where(array("code"=>$code))->or_where(["id"=>$code]);
		$dt=$this->db->get()->result_object();
		if(empty($dt)){
			return "";
		}else{
			return $dt;
		}
	}
	function _load_dt_others_setting($posted){
		$orders_cols=["title","content","id"];
		$output=build_filter_table($posted,$orders_cols);
		$sWhere=$output->where;
		if(isset($output->search) && $output->search!=""){
			$sWhere=" WHERE title LIKE '%".$output->search."%' OR content LIKE '%".$output->search."%'";
		}
		$sLimit=$output->limit;
		$sGroup="";
		$sOrder=$output->order;
		$data=$this->db->query("SELECT SQL_CALC_FOUND_ROWS ".implode(",",$orders_cols)." FROM lab_settings $sWhere $sGroup $sOrder")->result_object();
		$found=$this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data=array_map(function($dt){
				$id=$dt->id;
				return [
						$dt->title,
						$dt->content,
						'<a href="#" class="link-edit-others-setting" data-id="'.$dt->id.'"><i class="fa fa-edit"></i></a>
						'
				];
		},$data);
		$output->recordsTotal = (sizeof($found)==0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data=$map_data;
		return (array) $output;	
  	}
	function _save_others_setting($data,$where,$key){
		if(empty($where)){
			$this->db->select($key)->from($this->TableSetting)->where($key,$data[$key]);
			$check=$this->db->get()->result();
			if(!empty($check)) return 'exist';
			$this->db->insert($this->TableSetting,$data);
			return $this->db->affected_rows();
		}else{
			$this->db->select('id')->from($this->TableSetting);
			$this->db->where($key,$data[$key]);
			$this->db->where("id!=",$where['id']);
			$check=$this->db->get()->result();
			if(!empty($check)) return 'exist';
			$this->db->update($this->TableSetting,$data,$where);
			return $this->db->affected_rows();
		}
	}
	function save_backup_db($data){
		$this->db->insert('c_backup_list',$data);
		return $this->db->insert_id();
	}
	function delete_old_backup_db(){
		$this->db->query('DELETE FROM c_backup_list WHERE created_at < NOW() - INTERVAL 2 DAY');
		return $this->db->affected_rows();
	}
}

/* End of file Administrative_model.php */
/* Location: ./application/models/Administrative_model.php */
