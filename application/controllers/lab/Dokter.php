<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('dokter', $this->session->userdata('site_lang'));
		$this->load->helper('Authentication');
		$this->load->library("datatables");
		$this->load->model("Dokter_model","dokter");
		$this->load->model("Common_model","common");
		$this->data=isAuthorized();
        isAllowed("c::setting");
	}
	public function select2_(){
		header('Content-Type: application/json');
		$gets=$this->input->get();
		$key=(isset($gets['search']) && $gets['search']!='') ? $gets['search'] : "";
		$search=$this->dokter->_search_select2($key);
		echo json_encode($search);
	}
	public function search__($id=''){
			$gets=$this->input->get();
			$id=($id!='') ? $id : $gets['id'];
			header('Content-Type: application/json');
			$search=$this->dokter->_search(array("id"=>$id));
			if(empty($search)) sendError(lang('msg_no_record'));
			echo json_encode(array("data"=>$search[0]));
	}
	public function save__(){
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method=$this->input->method(true);
		if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		foreach($posted as $key=>$value){
			$$key=htmlentities(trim($value));
		}
		if($id_dokter=="") return sendError("ID Dokter");
		if($nama_dokter=="") return sendError("Nama Dokter Wajib diisi");
		if(!isset($posted['_id']) || $posted['_id']==""){
				// add new user
				$save=$this->dokter->_save(array(
						"kategori"=>$kategori,
						"id_dokter"=>$id_dokter,
						"nama_dokter"=>$nama_dokter
				),array(),"id_dokter");
				if($save=='exist'){
						sendError(lang('msg_record_exist'));  
				}else{
						if($save==0) sendError(lang('msg_insert_failed'));
						sendSuccess(lang('msg_insert_success'));
				}
		}else{
				// update existing user
				$data=array(
						"kategori"=>$kategori,
						"id_dokter"=>$id_dokter,
						"nama_dokter"=>$nama_dokter
				);
				$save=$this->dokter->_save($data,array("id"=>htmlentities(trim($posted['_id']))),"id_dokter");
				if($save==="exist"){
						sendError(lang('msg_record_exist'));  
				}else{
						if($save>0)
								sendSuccess(lang('msg_update_success'));
						sendError(lang('msg_update_failed'));
				}
		}
	}
	public function delete__($id=''){
			header('Content-Type: application/json');
			//isAllowed("c-privilege^delete work type");
			$method=$this->input->method(true);
			if($method!="DELETE") sendError(lang('msg_method_delete_required'),[],405);
			$result=$this->dokter->_delete(array('id'=>htmlentities(trim($id))));
			if($result==1){
					sendSuccess(lang('msg_delete_success'),[]);    
			}else{
					sendError(lang('msg_delete_failed'));
			}
			
	}
	public function get_active_lang(){
        header('Content-Type: application/json');
        echo json_encode($this->lang->language);
    }
}

/* End of file Pasien.php */
