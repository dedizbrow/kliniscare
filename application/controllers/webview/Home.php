<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    var $data;
	public function __construct()
	{
		parent::__construct();
		if(!isAuthorizedPasien()) redirect('webview/auth');
		$this->lang->load('home', $this->session->userdata('site_lang'));
		$this->load->model("Common_model","common");
		$this->load->model("webview/Account_model","account");
		$this->load->model(conf('path_module_lab')."Pemeriksaan_model","pemeriksaan");
		$this->data=isAuthorizedPasien();
	}
	
	public function index()
	{
		$data['company_logo']=base_url(conf('company_logo'));
		// $this->data['js_control']="home.js";
		// $acc=isAuthorizedPasien();
		// if(empty($acc)) redirect('webview/auth');
		// $account=$this->account->get_account($acc['PSID']);
		// if(empty($account)) redirect('webview/auth');
		// $this->data['usr']=$account[0];
		if(isAuthorizedPasien()){
			$acc=isAuthorizedPasien();
			$account=$this->account->get_account($acc['PSID']);
			$this->data['usr']=$account[0];
			$this->data['jadwal_pemeriksaan']=$this->pemeriksaan->_list_jadwal_pemeriksaan($this->data['PSID']);
			// print_r($this->data['jadwal_pemeriksaan']);
			// die();
		}else{
		}
		
		$this->template->load(get_template('webview'), 'webview/home', $this->data);
	}
}

/* End of file Home.php */
/* Location: ./application/controllers/webview/Home.php */
