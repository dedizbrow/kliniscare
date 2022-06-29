<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('home', $this->session->userdata('site_lang'));
		$this->load->helper('Authentication');
		$this->load->model("Home_model","home");
		$this->data=isAuthorized();
		//isAllowed("c-invoice^update");
	}
	public function index(){
		$this->data["web_title"]=lang('app_name_short'). " | Home";
		$this->data["page_title"]="Homepage";
		//$this->data["page_title_small"]="";
		$this->data['js_control']="home.js";
		$this->data['datatable']=true;
		$this->data['chartjs']=true;
		$provider_id=(isset($this->data['C_PV_GROUP']) && $this->data['C_PV_GROUP']!='pusat') ? $this->data['C_PV_ID'] : '';
		$this->data['count_pasien_provider']=$this->home->count_pasien_per_provider($provider_id);
		$this->template->load(get_template(),'home/index',$this->data);
	}
	public function load_dt_jumlah_pasien(){
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		$provider_id=(isset($this->data['C_PV_GROUP']) && $this->data['C_PV_GROUP']!='pusat') ? $this->data['C_PV_ID'] : '';
		$provider_id=($this->data['C_PV_GROUP']=='pusat') ? '' : $this->data['C_PV_ID'];
		$data=$this->pasien->_load_dt_jumlah($posted,$provider_id);
		echo json_encode($data);
	}
	public function count_pemeriksaan(){
		header('Content-Type: application/json');
		$provider_id=(isset($this->data['C_PV_GROUP']) && $this->data['C_PV_GROUP']!='pusat') ? $this->data['C_PV_ID'] : '';
		$count_pemeriksaan=$this->home->count_pemeriksaan_per_provider($provider_id);
		$dta=["data"=>$count_pemeriksaan];
		if($provider_id!="") $dta["limited"]=true;
		echo json_encode($dta);
	}
	
	function group_by($key, $data) {
			$result = array();

			foreach($data as $val) {
					if(array_key_exists($key, $val)){
							$result[$val[$key]][] = $val;
					}else{
							$result[""][] = $val;
					}
			}

			return $result;
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
