<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter_model extends CI_Model {
	function __construct(){
			parent::__construct();
			$this->load->database();
			$this->tableDokter="c_data_dokter";
			$this->load->helper('ctc');
	}
	function _load_dt($posted){
		$orders_cols=["kategori","id_dokter","nama_dokter","created_at","id"];
		$output=build_filter_table($posted,$orders_cols);
		$sWhere=$output->where;
		if(isset($output->search) && $output->search!=""){
				$sWhere=" WHERE id_dokter LIKE '%".$output->search."%' OR nama_dokter LIKE '%".$output->search."%'";
		}
		$sLimit=$output->limit;
		$sGroup="";
		$sOrder=$output->order;
		$limit = 0;
		$offset = 25;
		$data=$this->db->query("SELECT SQL_CALC_FOUND_ROWS ".implode(",",$orders_cols).",created_by FROM c_data_dokter $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found=$this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data=array_map(function($dt){
				$id=$dt->id;
				return [
						$dt->kategori,
						$dt->id_dokter,
						$dt->nama_dokter,
						'<a href="#" class="link-edit-dokter" data-id="'.$dt->id.'"><i class="fa fa-edit"></i></a>  &nbsp;
						<a href="#" class="link-delete-dokter" data-id="'.$dt->id.'"><i class="fa fa-trash text-danger"></i></a>
						'
				];
		},$data);
		$output->recordsTotal = (sizeof($found)==0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data=$map_data;
		return (array) $output;	
  }
	function _search($where){
		$this->db->select('id as _id,kategori,id_dokter,nama_dokter');
		$this->db->from($this->tableDokter);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select2($key=""){
		$this->db->select('id,nama_dokter as text');
		$this->db->from($this->tableDokter);
		if($key!=""){
			$this->db->like('nama_dokter',$key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data,$where,$key){
			if(empty($where)){
					// check before insert
					$this->db->select($key)->from($this->tableDokter)->where($key,$data[$key]);
					$check=$this->db->get()->result();
					if(!empty($check)) return 'exist';
					$this->db->insert($this->tableDokter,$data);
					return $this->db->affected_rows();
			}else{
					$this->db->select('id')->from($this->tableDokter);
					$this->db->where($key,$data[$key]);
					$this->db->where("id!=",$where['id']);
					$check=$this->db->get()->result();
					if(!empty($check)) return 'exist';
					$this->db->update($this->tableDokter,$data,$where);
					return $this->db->affected_rows();
			}
	}
	function _delete($where){
		$this->db->delete($this->tableDokter,$where);
			return $this->db->affected_rows();
	}
}

/* End of file Dokter_model.php */
