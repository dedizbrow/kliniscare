<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {
	function __construct(){
        parent::__construct();
        $this->load->database();
        $this->TableWorkType="c_work_type";
        $this->TableWorkProcess="c_work_process";
        $this->TableAttachment="c_attachments";
    }
	function alter_table(){
		// 2022-01-14
		$q=$this->db->query("ALTER table tbl_layanan_poli 
		ADD tarif_dokter_percent DOUBLE,
		ADD tarif_dokter DOUBLE
		");
		$q=$this->db->query("ALTER table tbl_bayar_periksa 
		ADD tarif_dokter DOUBLE
		");
		$q=$this->db->query("ALTER TABLE tbl_bayar_periksa ADD is_kwitansi_lab INT(1) NOT NULL DEFAULT '0' AFTER clinic_id; ");
		$q=$this->db->query("ALTER TABLE `tbl_obat` ADD import_id VARCHAR(50) NOT NULL AFTER creator_id; ");
		$q=$this->db->query("ALTER TABLE `c_registered_clinics` ADD `enabled_menus` TEXT NOT NULL COMMENT 'added 2022-01-21' AFTER `remarks`");
		$q=$this->db->query("ALTER TABLE `lab_data_pemeriksaan` ADD `asuransi` VARCHAR(40) NOT NULL AFTER `kode_sales`, ADD `no_asuransi` VARCHAR(40) NOT NULL AFTER `asuransi`, ADD `perujuk` VARCHAR(40) NOT NULL AFTER `no_asuransi`, ADD `nama_tenaga_perujuk` VARCHAR(40) NOT NULL AFTER `perujuk`");
		return "OK";
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
    function get_base_menus($access_codes,$enabled_menus=[]){
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
    function search_work_type($where){
        $this->db->select('id,name,int_process,ext_process');
        $this->db->from($this->TableWorkType);
        $this->db->where($where);
        return $this->db->get()->result();
    }
    function list_work_process($where=''){
        $this->db->select('id,name,category');
        $this->db->from($this->TableWorkProcess);
        if($where!='')
            $this->db->where($where,null,false);
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
	function get_company_info(){
		return $this->db->get('tb_company_detail')->result();
	}
	function _search_clinic_select2($key=''){
		$this->db->select('rc_id as id,clinic_name as text');
		$this->db->from('c_registered_clinics');
		if($key!=""){
			$this->db->like('clinic_name',$key);
		}
		$this->db->order_by("clinic_name");
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_poli_select2($key='',$clinic_id){
		$this->db->select('idPoli as id,namaPoli as text');
		$this->db->from('tbpoli');
		$this->db->where('clinic_id',$clinic_id);
		if($key!=""){
			$this->db->like('namaPoli',$key);
		}
		$this->db->order_by("namaPoli");
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_dokter_select2($key='',$clinic_id){
		$this->db->select('idDokter as id,namaDokter as text');
		$this->db->from('tbdaftardokter');
		$this->db->where('clinic_id',$clinic_id);
		if($key!=""){
			$this->db->like('namaDokter',$key);
		}
		$this->db->order_by("namaDokter");
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _get_clinic_info($where){
		$this->db->select('clinic_name,logo,CONCAT(license_duration," ",license_type) as license');
		$this->db->from('c_registered_clinics');
		$this->db->where($where);
		return $this->db->get()->result_object();
	}
}

/* End of file Common_model.php */
/* Location: ./application/models/Common_model.php */
