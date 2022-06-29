<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provider_model extends CI_Model {
	function __construct(){
			parent::__construct();
			$this->load->database();
			$this->tableProvider="lab_data_provider";
			$this->load->helper('ctc');
	}
	function _load_dt($posted){
		$orders_cols=["nama","alamat","telp","penanggung_jawab","id"];
		$output=build_filter_table($posted,$orders_cols);
		$sWhere=$output->where;
		if(isset($output->search) && $output->search!=""){
				$sWhere=" WHERE nama LIKE '%".$output->search."%' OR alamat LIKE '%".$output->search."%'";
		}
		$sLimit=$output->limit;
		$sGroup="";
		$sOrder=$output->order;
		$limit = 0;
		$offset = 25;
		$data=$this->db->query("SELECT SQL_CALC_FOUND_ROWS id,nama,alamat,provider_group,telp,penanggung_jawab,created_by,created_at FROM lab_data_provider $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found=$this->db->query("SELECT FOUND_ROWS() as total FROM lab_data_provider")->result_object();
		$map_data=array_map(function($dt){
				$id=$dt->id;
				$nama_provider=$dt->nama;
				$delete_link='<a href="#" class="link-delete-provider" data-id="'.$dt->id.'"><i class="fa fa-trash text-danger"></i></a>';
				if($dt->provider_group=='pusat'){
					$nama_provider='<i class="fa fa-check-square-o tx-success"></i> '.$dt->nama;
					$delete_link='';
				} 
				return [
						$nama_provider,
						$dt->alamat,
						$dt->telp,
						$dt->penanggung_jawab,
						'<a href="#" class="link-edit-provider" data-id="'.$dt->id.'"><i class="fa fa-edit"></i></a>  &nbsp;'.$delete_link
				];
		},$data);
		$output->recordsTotal = (sizeof($found)==0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data=$map_data;
		// unset($output->limit);
		// unset($output->search);
		// unset($output->order);
		// unset($output->where);
		return (array) $output;	
  }
	function _list_provider($id=''){
		$this->db->select('id,nama')->from($this->tableProvider)->order_by("nama");
		if($id!='') $this->db->where(array("id"=>$id));
		$this->db->order_by("nama","asc");
		return $this->db->get()->result_object();
	}
	function _search($where){
		$this->db->select('id as _id,nama,alamat,telp,provider_group,penanggung_jawab');
		$this->db->from($this->tableProvider);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select2($key=""){
		$this->db->select('id,nama as text');
		$this->db->from($this->tableProvider);
		if($key!=""){
			$this->db->like('nama',$key);
		}
		$this->db->limit(50);
		return $this->db->get()->result_array();
	}
	function _save($data,$where){
        if(empty($where)){
            // check before insert
            $this->db->select('nama')->from($this->tableProvider)->where("nama",$data['nama']);
            $check=$this->db->get()->result();
            if(!empty($check)) return 'exist';
            $this->db->insert($this->tableProvider,$data);
            return $this->db->affected_rows();
        }else{
            $this->db->select('id')->from($this->tableProvider);
            $this->db->where("nama",$data['nama']);
            $this->db->where("id!=",$where['id']);
            $check=$this->db->get()->result();
            if(!empty($check)) return 'exist';
            $this->db->update($this->tableProvider,$data,$where);
            return $this->db->affected_rows();
        }
    }
		function check_pemeriksaan_selesai($provider_id){
			$this->db->select('id_pasien,nama_pasien,id_provider')->from('lab_data_pemeriksaan');
			$this->db->where(array("id_provider"=>$provider_id,"status"=>"SELESAI"));
			return $this->db->get()->result();
		}
		function delete__($where){
			$this->db->delete($this->tableProvider,$where);
        return $this->db->affected_rows();
		}
}

/* End of file provider_model.php */
