<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Provider extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		// $this->data=isAuthorized();
        // isAllowed("c::setting");
		$this->data['path_module']="lab/";
		$this->lang->load($this->data['path_module'].'provider', $this->session->userdata('site_lang'));
		
		
		$this->load->library("datatables");
		$this->load->model($this->data['path_module']."Provider_model","provider");
		$this->load->model($this->data['path_module']."Common_model","common");
		
	}
	public function index()
	{
		$this->data["web_title"]=lang('app_name_short'). "Data provider";
		$this->data["page_title"]=lang('page_title');
		$this->data['js_control']=$this->data['path_module']."data-provider.js";
		$this->data['datatable']=true;
		$gets=$this->input->get();
		$this->data['status']="";
		if(isset($gets['status'])) $this->data['status']=htmlentities($gets['status']);
		$this->template->load(get_template(),$this->data['path_module'].'data-provider',$this->data);
	}

	public function load_dt(){
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		$data=$this->provider->_load_dt($posted);
		echo json_encode($data);
	}
	public function search__($id=''){
			$gets=$this->input->get();
			$id=($id!='') ? $id : $gets['id'];
			header('Content-Type: application/json');
			$search=$this->provider->_search(array("id"=>$id));
			if(empty($search)) sendError(lang('msg_no_record'));
			echo json_encode(array("data"=>$search[0]));
	}
	public function select2_($k=''){
		header('Content-Type: application/json');
		$gets=$this->input->get();
		$key=(isset($gets['search']) && $gets['search']!='') ? $gets['search'] : "";
		$search=$this->provider->_search_select2($key);
		echo json_encode($search);
	}
	public function save__(){
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method=$this->input->method(true);
		if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		$nama_provider=htmlentities(trim($posted['nama']));
		$alamat=htmlentities(trim($posted['alamat']));
		$telp=htmlentities(trim($posted['telp']));
		$penanggung_jawab=htmlentities(trim($posted['penanggung_jawab']));
		if(!isset($posted['_id']) || $posted['_id']==""){
				// add new user
				$save=$this->provider->_save(array(
						"nama"=>$nama_provider,
						"alamat"=>$alamat,
						"telp"=>$telp,
						"penanggung_jawab"=>$penanggung_jawab,
				),array());
				if($save=='exist'){
						sendError(lang('msg_record_exist'));  
				}else{
						if($save==0) sendError(lang('msg_insert_failed'));
						sendSuccess(lang('msg_insert_success'));
				}
		}else{
				// update existing user
				$data=array(
						"nama"=>$nama_provider,
						"alamat"=>$alamat,
						"telp"=>$telp,
						"penanggung_jawab"=>$penanggung_jawab
				);
				$save=$this->provider->_save($data,array("id"=>htmlentities(trim($posted['_id']))));
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
				// check first
				$check=$this->provider->_search(array('id'=>htmlentities(trim($id))));
				if(!empty($check)){
					if($check[0]->provider_group=='pusat') return sendError("Provider ini ditandai sebagai Pusat. Tidak dapat di hapus");
				}
				$check_pemeriksaan=$this->provider->check_pemeriksaan_selesai($id);
				if(!empty($check_pemeriksaan))  return sendError("Terdapat record pemeriksaan pada provider ini sehingga tidak dapat dihapus");
        $result=$this->provider->delete__(array('id'=>htmlentities(trim($id))));
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

/* End of file Controllername.php */
