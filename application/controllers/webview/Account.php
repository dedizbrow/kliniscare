<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
    var $data;
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('auth', $this->session->userdata('site_lang'));
		$this->load->model("webview/Account_model","account");
		$this->load->model("Common_model","common");
		$this->data=array();
		if(!isAuthorizedPasien()) redirect('webview/auth');
	}
	
	public function index()
	{
		$data['company_logo']=base_url(conf('company_logo'));
		$this->data['js_control']="update-account.js";
		$acc=isAuthorizedPasien();
		if(empty($acc)) redirect('webview/auth');
		$account=$this->account->get_account($acc['PSID']);
		if(empty($account)) redirect('webview/auth');
		$this->data['usr']=$account[0];
		$this->template->load(get_template('webview'), 'webview/account', $this->data);
	}
	public function update_account(){
		$posted=$this->input->post();
		foreach($posted as $key=>$value){
			$$key=htmlentities(trim($value));
		}
		$acc=isAuthorizedPasien();
		if(empty($acc)) sendError("Token tidak valid. silahkan login ulang");
		if(trim($name)=="") sendError("Nama wajib diisi");
		if(trim($email)=="") sendError("Email wajib diisi");
		$data=[
			"name"=>strtoupper($name),
			"email"=>strtolower($email),
			"hp"=>$hp
		];
		if($password!=""){
			if(trim($password)!=trim($repassword)) sendError("Password tidak cocok");
			$data['passwd']=md5(base64_encode($password));
		}
		$save=$this->account->update_account($data,array("id"=>$acc['PSID']));
		sendSuccess("Pembaharuan informasi akun berhasil");
	}
	public function signout(){
		clearToken();
	}

}

/* End of file Account.php */
/* Location: ./application/controllers/webview/account.php */
