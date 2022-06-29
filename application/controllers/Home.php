<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('home', $this->session->userdata('site_lang'));
		//$this->load->helper('Authentication');
		$this->load->model("Home_model","home");
		//$this->data=isAuthorized();
		//isAllowed("c-invoice^update");
	}
	public function index(){
		$this->data["web_title"]=lang('app_name_short'). " | Home";
		$this->data["page_title"]="Homepage";
		//$this->data["page_title_small"]="";
		$this->data['js_control']="home.js";
		$this->data['datatable']=false;
		$this->data['chartjs']=false;
		$this->data['src_assets_template']= "assets/rtus-assets";
		redirect('admin');
		//$this->template->load(get_template(),'home/index',$this->data);
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
