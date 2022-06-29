<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settingtarif_model extends CI_Model {
	function __construct(){
			parent::__construct();
			$this->load->database();
			$this->tableProvider="lab_data_provider";
			$this->tableJenisPemeriksaan="lab_jenis_pemeriksaan";
			$this->tableTarif="lab_tarif";
			$this->load->helper('ctc');
	}
	function _load_dt_tarif($posted){
		$orders_cols=["provider.nama","jenis.jenis","tarif.start_date","tarif.nominal","tarif.id"];
		$output=build_filter_table($posted,$orders_cols,[],"tarif.clinic_id");
		$sWhere=$output->where;
		if(isset($output->search) && $output->search!=""){
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
			$sWhere=" (provider.nama LIKE '%".$output->search."%' OR jenis.jenis LIKE '%".$output->search."%')";
		}
		if(isset($posted['filter_provider']) && $posted['filter_provider']!=''){
			$sWhere.=($sWhere="") ? " WHERE ": " AND ";
			$sWhere.=" provider_id='".$posted['filter_provider']."'";
		}
		$sLimit=$output->limit;
		$sGroup=" GROUP BY tarif.id";
		$sOrder=$output->order;
		if($sOrder!="") $sOrder.=" ,tarif.provider_id ASC, tarif.jenis_id ASC";
		$limit = 0;
		$offset = 25;
		$data=$this->db->query("SELECT SQL_CALC_FOUND_ROWS ".implode(",",$orders_cols).",tarif.provider_id,tarif.save_id,tarif.enabled,tarif.created_by,tarif.created_at 
			FROM lab_tarif as tarif
			INNER JOIN lab_data_provider as provider ON tarif.provider_id=provider.id 
			INNER JOIN lab_jenis_pemeriksaan as jenis ON tarif.jenis_id=jenis.id 
			$sWhere $sGroup $sOrder $sLimit")->result_object();
		$found=$this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		
		$map_data=array_map(function($dt){
				$id=$dt->id;
				$link_delete='<a href="#" class="link-delete-tarif" data-id="'.$dt->save_id.'" data-provider="'.$dt->provider_id.'"><i class="fa fa-trash text-danger"></i></a>';
				$edit='<a href="#" class="link-edit-tarif" data-id="'.$dt->save_id.'" data-provider="'.$dt->provider_id.'"><i class="fa fa-edit"></i></a>  &nbsp;'.$link_delete;
				return [
						$dt->nama,
						$dt->jenis,
						$dt->start_date,
						number_format($dt->nominal,0),
						$edit,
						$dt->enabled,
						$dt->save_id
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
	function _search_saveid($id){
		$data=$this->db->query('select tarif.save_id as _id,provider.nama as provider,tarif.provider_id,tarif.jenis_id,jenis.jenis,tarif.start_date,nominal
		FROM lab_tarif tarif 
		INNER JOIN lab_data_provider provider ON tarif.provider_id=provider.id
		INNER JOIN lab_jenis_pemeriksaan jenis ON tarif.jenis_id=jenis.id
		WHERE tarif.save_id='.$id.' GROUP BY tarif.id
		')->result();
		return $data;
	}
	function checkIsExist($where){
		$this->db->select('id,start_date')->from($this->tableTarif);
		$this->db->where($where);
		$check=$this->db->get()->result();
		if(!empty($check)) return 'exist';
		return 'Not Exist';
	}
	
	function _save_batch($data){
		$this->db->insert_batch($this->tableTarif, $data); 
		return $this->db->affected_rows();
	}
	function _save_update($data,$where){
		$this->db->update($this->tableTarif,$data,$where);
		return $this->db->affected_rows();
	}
	function _list_provider_id(){
		$this->db->select('id')->from($this->tableProvider);
		return $this->db->get()->result();
	}
	function _delete($where){
		$this->db->delete($this->tableTarif,$where);
		return $this->db->affected_rows();
	}
	function _set_active($provider_id){
		$this->db->query('UPDATE lab_tarif SET enabled=0 WHERE provider_id="'.$provider_id.'"');
		$this->db->reset_query();
		$this->db->query('UPDATE lab_tarif SET enabled=1 WHERE provider_id="'.$provider_id.'" AND start_date>=(SELECT MAX(start_date) FROM lab_tarif WHERE start_date<=CURDATE() AND provider_id="'.$provider_id.'") ORDER BY start_date LIMIT 3');
		return $this->db->affected_rows();
	}
	function load_last_tarif($provider=1,$jenis,$start_date,$end_date){
		$this->db->select("start_date,nominal")->from('lab_tarif');
		$this->db->where(array(
			"provider_id"=>$provider,
			"jenis_id"=>$jenis,
			"start_date<="=>$start_date
			));
		$this->db->order_by('start_date DESC');
		$this->db->limit(1);
		// $data=$this->db->query("select start_date,nominal FROM lab_tarif WHERE provider_id='".$provider."' AND jenis_id='".$jenis."' AND start_date<='".$start_date."' ORDER BY start_date DESC LIMIT 1")->result_object();
		return $this->db->get()->result_object();
	}
}

/* End of file Settingtarif_model.php */
