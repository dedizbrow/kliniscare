<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    var $data;
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('auth', $this->session->userdata('site_lang'));
		$this->load->model("webview/Auth_model","auth");
		$this->load->model("Common_model","common");
		$this->data=array();
	}
	public function genPassword($password=''){
		if($password!='') echo "password: ".md5(base64_encode($password));
	}
	public function index()
	{
		if(isAuthorizedPasien()) redirect('webview/lab/pemeriksaan');
		$data['company_logo']=base_url(conf('company_logo'));
		$this->data['js_control']="auth/login.js";
		$this->load->view('webview/auth/login', $this->data);
	}
	/* methods for registering account */
	public function register()
	{
		$data['company_logo']=base_url(conf('company_logo'));
		$this->data['js_control']="auth/register.js";
		// $this->load->view('webview/auth/login', $this->data);
		$this->load->view('webview/auth/register', $this->data);
	}
	public function process_register(){
		header('Content-Type: application/json');
		$method=$this->input->method(true);
		if($method!="POST") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		foreach($posted as $key=>$value){
			$$key=htmlentities(trim($value));
		}
		if(!isset($name) || $name=="") return sendError("Nama Wajib diisi");
		if(!isset($email) || $password=="") return sendError("Email Wajib diisi");
		if(!isset($password) || $password=="") return sendError("Password Wajib diisi");
		if($password!=$repassword) return sendError("Ulang password tidak cocok");
		// check account
		if($this->auth->check_account_exist($email)==='exist') sendError("Email sudah terdaftar");
		$save=$this->auth->register_account_pasien(array(
			"name"=>strtoupper($name),
			"email"=>strtolower($email),
			"passwd"=>hashPasswd($password)
		));
		sendSuccess("Registrasi berhasil");
	}
	/* end registering account */
	public function process_signin()
	{
		header('Content-Type: application/json');
		$method=$this->input->method(true);
		if($method!="POST") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		if(empty($posted)) sendError("Masukkan username dan password");
		if($posted['username']=="" || $posted['password']=="")
			sendError("Masukkan username dan password");
		$res=$this->auth->signin($posted['username'],hashPasswd($posted['password']));
		if(empty($res)){
			sendError("Username/password salah");
		}else{
			if($res[0]->enabled===0) sendError("Akun belum diverifikasi");
			generateTokenLoginPasien($res[0]);
			sendSuccess("Selamat datang, ".$res[0]->name);
		}
	}
	public function signout(){
		clearTokenLoginPasien();
	}
	public function forget_password(){
		$posted=$this->input->post();
		if(!isset($posted['email']) || $posted['email']=="") sendError("Masukkan Email/Username");
		$check=$this->auth->check_account_exist($posted['email']);
		if($check!=='exist') sendError("Email tidak terdaftar");
		sendSuccess("Fitur forget password belum tersedia");
	}
}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */
