<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('profile', $this->session->userdata('site_lang'));
		
		$this->load->helper('Authentication');
		$this->data=isAuthorized();
		$this->load->model("Profile_model","profile");
	}
	public function index()
	{
		$this->data["web_title"]="Profile";
		$this->data["page_title"]=lang('label_my_account');
		$this->data['js_control']="profile.js";
		$dtuser=$this->profile->my_profile($this->data['C_UID']);
		$this->data['user']=$dtuser[0];
		$this->template->load(get_template(),'profile',$this->data);
	}
	public function save_profile(){
		header('Content-Type: application/json');
    	requiredMethod('POST');
    	$posted=$this->input->post();
        $email=htmlentities(trim($posted['email']));
        $password=htmlentities(trim($posted['password']));
        if(!isEmailValid($email)) 
            sendError(lang('msg_invalid_email'));
            $data=array(
                "name"=>htmlentities(trim($posted['name'])),
                "uname"=>htmlentities(trim($posted['username'])),
                "email"=>htmlentities(trim($posted['email']))
            );
            if($password!=""){
                if(!isMatch($password, htmlentities(trim($posted['repassword'])))) 
                    sendError(lang('msg_password_not_match'));
                $data["passwd"]=hashPasswd($password);
            }
            $save=$this->profile->save_user($data,array("uid"=>$this->data['C_UID']));
            if($save==="exist"){
                sendError(lang('msg_username_used'));  
            }else{
                if($save>0 && $password=="")
                    sendSuccess(lang('msg_update_user_success'));
               	if($save>0 && $password!="")
               		sendSuccess(lang('msg_change_password_success'));
                sendError(lang('msg_no_changes'));
            }
	}
}

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */