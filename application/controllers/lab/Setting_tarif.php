<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_tarif extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load(conf('path_module_lab').'tarif', $this->session->userdata('site_lang'));
		$this->load->helper('Authentication');
		$this->load->library("datatables");
		$this->load->model(conf('path_module_lab')."Settingtarif_model","tarif");
		$this->load->model(conf('path_module_lab')."Provider_model","provider");
		$this->load->model(conf('path_module_lab')."Jenispemeriksaan_model","jenis");
		$this->load->model(conf('path_module_lab')."Common_model","common");
		$this->data=isAuthorized();
    $this->data['access_code_tarif']="lab::tarif";
	}
	public function index()
	{
		$this->data["web_title"]=lang('app_name_short'). "Setting Tarif";
		$this->data["page_title"]=lang('page_title');
		$this->data['js_control']=conf('path_module_lab')."setting-tarif.js";
		$this->data['datatable']=true;
		$gets=$this->input->get();
		$gets=modify_post($gets);
		
		$this->data['status']="";
		$list_jenis=$this->jenis->_list_jenis_pemeriksaan($gets['clinic_id']);
		$this->data['list_jenis']=$list_jenis;
		if(isset($gets['status'])) $this->data['status']=htmlentities($gets['status']);
		$this->template->load(get_template(),conf('path_module_lab').'setting-tarif',$this->data);
	}

	public function load_dt(){
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		$posted=modify_post($posted);
		$data=$this->tarif->_load_dt_tarif($posted);
		echo json_encode($data);
	}
	public function search_saveid__($id=''){
		$gets=$this->input->get();
		$id=($id!='') ? $id : $gets['id'];
		header('Content-Type: application/json');
		$id=htmlentities(trim($id));
		$search=$this->tarif->_search_saveid($id);
		if(empty($search)) sendError(lang('msg_no_record'));
		echo json_encode(array("data"=>$search));
	}
	public function save__(){
		header('Content-Type: application/json');
		isAllowed($this->data['access_code_tarif']);
		$method=$this->input->method(true);
		if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->input_stream();
		$posted=modify_post($posted);
		// if(!isset($posted['provider_id'])) return sendError('Provider belum dipilih');
		$provider_id=(isset($posted['provider_id'])) ? htmlentities(trim($posted['provider_id'])) : $this->data['C_PV_ID'];
		$start_date=htmlentities(trim($posted['start_date']));
		if($start_date=="") return sendError('Tgl Berlaku belum diset');
		$start_date=date("Y-m-d",strtotime($start_date));
		// echo "<pre>";
		// print_r($posted);
		// echo gettype($posted['tarif_']);
		// echo "</pre>";
		// die();
		if(!isset($posted['_id']) || $posted['_id']==""){
			$save_id=rand();
				$check=$this->tarif->checkIsExist(array("clinic_id"=>$posted["clinic_id"],"provider_id"=>$provider_id,"start_date"=>$start_date));
				if($check=='exist') return sendError("Setting untuk tanggal tersebut sudah tersedia");
				$data=array();
				foreach($posted['tarif_'] as $jenis_id=>$nominal){
					array_push($data,array(
						"clinic_id"=>$posted['clinic_id'],
						"provider_id"=>$provider_id,
						"start_date"=>$start_date,
						"jenis_id"=>$jenis_id,
						"nominal"=>(double) $nominal,
						"save_id"=>$save_id,
						"created_by"=>$this->data['C_UID']
					));
				}
				$save=$this->tarif->_save_batch($data);
				if($save==0) return sendError(lang('msg_insert_failed'));
				$this->tarif->_set_active($provider_id);
				sendSuccess(lang('msg_insert_success'));
		}else{
				$save_id=$posted['_id'];
				$check=$this->tarif->checkIsExist(array("clinic_id"=>$posted['clinic_id'],"provider_id"=>$provider_id,"start_date"=>$start_date,"save_id!="=>$save_id));
				if($check=='exist') return sendError("Setting untuk tanggal tersebut sudah tersedia");
				$upd=0;
				foreach($posted['tarif_'] as $jenis_id=>$nominal){
					$data=array(
						"provider_id"=>$provider_id,
						"start_date"=>$start_date,
						"jenis_id"=>$jenis_id,
						"nominal"=>(double) $nominal
					);
					$where=array("save_id"=>$save_id,"jenis_id"=>$jenis_id);
					$upd+=$this->tarif->_save_update($data,$where);
				}
				if($upd>0) return sendSuccess(lang('msg_update_success'));
				sendError(lang('msg_update_failed'));
		}
	}
	function delete__($id){
		$del=$this->tarif->_delete(array("save_id"=>$id));
		isAllowed($this->data['access_code_tarif']);
		if($del>0) return sendSuccess(lang('msg_delete_success'));
		sendError(lang('msg_delete_failed'));
	}
	public function get_active_lang(){
			header('Content-Type: application/json');
			echo json_encode($this->lang->language);
	}
}

/* End of file Setting_tarif.php */
