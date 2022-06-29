<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    var $data;
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('auth', $this->session->userdata('site_lang'));
		$this->load->model("admin/Auth_model","auth");
		$this->load->model("Common_model","common");
		$this->data=array();
	}
	public function genPassword($password=''){
		if($password!='') echo "password: ".md5(base64_encode($password));
	}
	public function index()
	{
		$gets=$this->input->get();
		$data['company_logo']=base_url(conf('company_logo'));
		if(isset($gets['redirect'])){ 
			$data['redirect']=$gets['redirect'];
			$app_code=conf('app_code');
			if(!isset($gets['mf']) && $this->session->userdata($app_code."CTC-X-KEY")){
				$dt=json_decode(base64_decode($this->session->userdata($app_code."CTC-X-KEY")));
				$clinic_id=$dt->clinic_id;
				if($clinic_id=="allclinic"){
					$data['error']="Login untuk TV Display menggunakan salah satu akun sesuai dengan klinik tersebut";
				}else{
					// echo "<script>localStorage.setItem('cdisp-tv-antrian',$clinic_id)</script>";
					// sleep(5);
					// redirect($data['redirect']);
				}
			}
		}
		$this->load->view('admin/auth/signin',$data);
	}
	public function signin()
	{
		$posted=$this->input->post();
		if(empty($posted)) redirect("admin/auth");
		if($posted['username']=="" || $posted['password']==""){
			$this->session->set_flashdata('message', lang('msg_username_password_required'));
			redirect("admin/auth","refresh");
		}
		$res=$this->auth->signin($posted['username'],hashPasswd($posted['password']));
		if(empty($res)){
			$this->session->set_flashdata('message', lang('msg_invalid_username'));
			redirect("admin/auth","refresh");
		}else{
			$clinic_id = $res[0]->clinic_id;
			$enabled_menus=false;
			if ($clinic_id != 'allclinic'){
				$search_enabled_menus=$this->auth->search_enabled_menus($clinic_id);
				$enabled_menus=explode(",",$search_enabled_menus[0]->enabled_menus);
			}
			if($res[0]->level==$this->config->item('super_admin_code')){
				$base_menu=$this->common->get_base_menus($res[0]->level);
				$menus=$this->common->get_menus($res[0]->level);
			}else{
				$base_menu=$this->common->get_base_menus(explode(",",$res[0]->accessibility_base));
				$menus=$this->common->get_menus(explode(",",$res[0]->accessibility));
			}
			if($enabled_menus){
				$new_based=array();
				foreach($base_menu as $base){
					if(in_array($base->base_id,$enabled_menus)) array_push($new_based,$base);
				}
				$base_menu=$new_based;
			}
			$dt=$res[0];
			if($dt->usrname!=conf('super_admin_id')){
					// check license
				if($dt->reg_date!=null){
					$new_date=date("Y-m-d H:i:s",strtotime($dt->reg_date." +".$dt->license_duration." ".$dt->license_type));
					if($new_date<date("Y-m-d H:i:s")){
						// license expired
						$this->session->set_flashdata('message', 'Licensi klinik anda berakhir pada '.$new_date.'. Silahkan hubungi kami<br>');
						redirect("admin/auth","refresh");
					}else{
						generateMenu($base_menu,$menus,$res[0]);
						generateToken($res[0]);
					}
				}
						
			}else{
				generateMenu($base_menu,$menus,$res[0]);
				generateToken($res[0]);
			}
		}
	}
	// public function signup(){
	// 	$data['company_logo']=base_url(conf('company_logo'));
	// 	$this->load->view('auth/signup',$this->data);
	// }
	public function signout(){
		$gets=$this->input->get();
		$redirect=(isset($gets['redirect']) && isset($gets['md']) && $gets['md']=="tv") ? $gets['redirect']: '';
		clearToken($redirect);
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */
