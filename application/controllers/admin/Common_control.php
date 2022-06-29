<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_control extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Common_model","common");
		//$this->lang->load('', $this->session->userdata('site_lang'));
/*		$this->load->helper('Authentication');
		$this->url_restapi=$this->config->item('url_restapi');
		$data_user=isAuthorized();
		$this->options=$this->config->item('api_options');
		$this->data=array_merge($static_variable,$data_user);
		isAllowed("administrative::privileges");*/
		$this->data=array();
	}
	public function index()
	{
				
	}
	public function inline_insert(){
		$method=strtoupper($this->input->method());
		if($method!="POST") sendError("$method method not allowed!",405);
		$posted=$this->input->post();
		if(!isset($posted['keyid']) || !isset($posted['keyval'])) sendError("$method method not allowed!",405);
		if($posted['keyid']=="" || $posted['keyval']=="") sendError(lang('msg_error_required_common'));
		$table="";
		$user="test";
		$data=array();
		$data['name']=htmlentities($posted['keyval']);
		$data['add_by']=$user;
		switch ($posted['keyid']) {
			case 'work_type':
				$table="c_work_type";
				break;
			case 'pic':
				$table='c_pic_supports';
				$data['category']='pic';
				break;
			case 'supports':
				$table='c_pic_supports';
				$data['category']='support';
				break;
			case 'work_process':
				$table='c_work_process';
				break;
			case 'work_subprocess':
				$table='c_work_subprocess';
				break;
			default:
				sendError("keyid is not available!");
				break;
		}
		$save=$this->common->inline_insert($table,$data);
		if($save=='exist') sendError(lang('msg_record_exist'));
		if($save==0) sendError(lang('msg_insert_failed'));
		sendSuccess(lang('msg_insert_success'));
	}
	public function simple_search_select2($idkey=''){
		$gets=$this->input->get();
		$table="";$skey=(isset($gets['search'])) ? htmlentities($gets['search']) : "";
		switch ($idkey) {
			case 'work_type':
				$table='c_work_type'; 
				break;
			case 'work_process':
				$table='c_work_process'; 
				break;
			case 'pic_support': 
				$table='c_users';
				break;
			default:
				$table=$idkey;
				//sendJSON(array());
				break;
		}

		$result=$this->common->common_search_select2($table,$skey);
		sendJSON($result);
	}
	public function search_work_type($id=''){
        $gets=$this->input->get();
        $id=($id!='') ? $id : $gets['id'];
        header('Content-Type: application/json');
        $search=$this->common->search_work_type(array("id"=>$id));
        if(empty($search)) sendError(lang('msg_no_record'));
        $merge=array_unique(array_values(array_filter(array_merge(explode(",",$search[0]->int_process),explode(",",$search[0]->ext_process)))));
        if(!empty($merge)){
        	$data_process=$this->common->list_work_process("id IN (".implode(",",$merge).")");
        }else{
        	$data_process=array();
        }
        echo json_encode(array("work_type"=>$search[0],"work_process"=>$data_process));
    }   
    public function get_active_lang(){
        header('Content-Type: application/json');
        $this->load->helper('language');
        echo json_encode($this->lang->language);
    }
    public function remove_attachment($id=''){
    	header('Content-Type: application/json');
    	requiredMethod('DELETE');
    	$posted=$this->input->post();
    	$this->load->helper('Authentication');
		$this->data=isAuthorized();
    	if($id=='') sendError("msg_missing_parameter");
    	$remove=$this->common->remove_attachment(array("id"=>$id,"user_id"=>$this->data['C_UID']));
    	if($remove==0) sendError(lang("msg_remove_attachment_failed"));
    	sendSuccess(lang("msg_remove_attachment_success"));
    }

	public function search_clinic(){
		$gets=$this->input->get();
		$key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
		$search = $this->common->_search_clinic_select2($key);
		echo json_encode($search);
	}
	
	public function alter_table(){
		$sv=$this->common->alter_table();
		echo $sv; 
	}
}

/* End of file Global_save.php */
/* Location: ./application/controllers/Global_save.php */
