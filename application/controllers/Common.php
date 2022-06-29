<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Common_model","common");
		$this->data=array();
	}
	public function index()
	{
				
	}
	
	public function search_poli_select2($idkey=''){
		$gets=$this->input->get();
		$skey=(isset($gets['search'])) ? htmlentities($gets['search']) : "";
		if(!isset($gets['clinic_id']) || $gets['clinic_id']=='') die("Silahkan Pilih Klinik");
		$result=$this->common->_search_poli_select2($skey,$gets['clinic_id']);
		sendJSON($result);
	}
	public function search_dokter_select2($idkey=''){
		$gets=$this->input->get();
		$skey=(isset($gets['search'])) ? htmlentities($gets['search']) : "";
		if(!isset($gets['clinic_id']) || $gets['clinic_id']=='') die("Silahkan Pilih Klinik");
		$result=$this->common->_search_dokter_select2($skey,$gets['clinic_id']);
		sendJSON($result);
	}
	public function get_clinic_info_($clinic_id){
		$clinic=base64_decode($clinic_id);
		$result=$this->common->_get_clinic_info(array("rc_id"=>$clinic,"is_active"=>1));
			if(sizeof($result)>0) return sendJSON($result[0]);
		sendError("Tidak ditemukan");
	}
}

/* End of file Global_save.php */
/* Location: ./application/controllers/Global_save.php */
