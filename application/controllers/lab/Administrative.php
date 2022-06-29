<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrative extends CI_Controller {
    var $data;
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('administrative', $this->session->userdata('site_lang'));
		$this->load->helper('Authentication');
		$this->load->library("datatables");
        $this->data=isAuthorized();
		$this->load->model(conf('path_module_lab')."Administrative_model","admin");
		$this->load->model(conf('path_module_lab')."Jenispemeriksaan_model","jenis_pemeriksaan");
		$this->load->model(conf('path_module_lab')."Dokter_model","dokter");
		isAllowed("lab::admin");
	}
	public function index()
	{
		$this->data["web_title"]=lang('app_name_short'). " | Administrative";
		$this->data["page_title"]="Administrator Area";
		$this->data['js_control']=conf('path_module_lab')."administrative.js";
		$this->data['datatable']=true;
        $this->data['base_menu']=$this->admin->load_base_menu();
        $this->data['manage_user']=false;
        $this->data['manage_work_type']=false;
        // if(isAllowedSection("c-privilege^create-update-user")) 
        $this->data['manage_user']=false;
        $this->data['manage_provider']=false;
        $this->data['manage_dokter']=false;
		$this->data['manage_other_setting']=false;
		$this->template->load(get_template(),conf('path_module_lab').'administrative/index',$this->data);
    }
    /* USERS */
    public function load_dt_users(){
    	header('Content-Type: application/json');
    	$posted=$this->input->post();
    	$data=$this->admin->dt_users($posted);
    	echo $data;
    }
    public function search_user($id=''){
        $gets=$this->input->get();
        $id=($id!='') ? $id : $gets['id'];
        header('Content-Type: application/json');
        $search=$this->admin->search_user(array("uid"=>$id));
        if(empty($search)) sendError(lang('msg_no_record'));
        //$data_user=$this->admin->list_work_process();
        $is_superAdmin=false;
        if($search[0]->level==conf('super_admin_code')){
            $is_superAdmin=true;
        }
        $privilege=array(
            "accessibility"=>explode(",",$search[0]->accessibility),
            "actions_code"=>explode(",",$search[0]->actions_code),
            "superAdmin"=>$is_superAdmin
        );

        unset($search[0]->accessibility);
        unset($search[0]->actions_code);
        unset($search[0]->level);
        echo json_encode(array("data"=>$search[0],"privilege"=>$privilege));
    }
    public function save_user(){
        header('Content-Type: application/json');
        isAllowed("c-privilege^create-update-user");
        $method=$this->input->method(true);
        if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
        $posted=$this->input->post();
        $email=htmlentities(trim($posted['email']));
        $password=htmlentities(trim($posted['password']));
        $accessibility=(isset($posted['accessibility'])) ? implode(",",array_unique($posted['accessibility'])) : "";
        $actions_code=(isset($posted['actions_code'])) ? implode(",",array_unique($posted['actions_code'])): "";
        $level=(isset($posted['level'])) ? $posted['level'] : "";
        if(!isEmailValid($email)) 
            sendError(lang('msg_invalid_email'));
		if(!isset($posted['provider_id']) || $posted['provider_id']=="") return sendError("Provider wajib di pilih");
        if(!isset($posted['user_id']) || $posted['user_id']==""){
            // add new user
            if(!isMatch($password, htmlentities(trim($posted['repassword'])))) 
                sendError(lang('msg_password_not_match'));

            $save=$this->admin->save_user(array(
                "uid"=>mt_rand(10100000, 99999999),
                "name"=>htmlentities(trim($posted['name'])),
                "uname"=>htmlentities(trim($posted['username'])),
                "email"=>htmlentities(trim($posted['email'])),
                "passwd"=>hashPasswd($posted['password']),
                "accessibility"=>$accessibility,
                "actions_code"=>$actions_code,
                "level"=>$level,
                "provider_id"=>htmlentities($posted['provider_id']),
                "template"=>conf('ctc_default_template')
            ),array());
            if($save=='exist'){
                sendError(lang('msg_username_used'));  
            }else{
                if($save==0) sendError(lang('msg_insert_user_failed'));
                sendSuccess(lang('msg_insert_user_success'));
            }
        }else{
            // update existing user
            $data=array(
                "name"=>htmlentities(trim($posted['name'])),
                "uname"=>htmlentities(trim($posted['username'])),
                "email"=>htmlentities(trim($posted['email'])),
                "accessibility"=>$accessibility,
                "actions_code"=>$actions_code,
                "level"=>$level,
				"provider_id"=>htmlentities($posted['provider_id'])
            );
            if($password!=""){
                if(!isMatch($password, htmlentities(trim($posted['repassword'])))) 
                    sendError(lang('msg_password_not_match'));
                $data["passwd"]=hashPasswd($password);
            }
            $save=$this->admin->save_user($data,array("uid"=>htmlentities(trim($posted['user_id']))));
            if($save==="exist"){
                sendError(lang('msg_username_used'));  
            }else{
                if($save>0)
                    sendSuccess(lang('msg_update_user_success'));
                sendError(lang('msg_no_changes'));
            }
        }
    }
    public function enable_disable_user(){
        requiredMethod('POST');
        isAllowed("c-privilege^create-update-user");
        $posted=$this->input->post();
        $save=$this->admin->enable_disable_user(array("enabled"=>$posted['status']),array("uid"=>$posted['id']));
        if($save>0) sendSuccess(lang("msg_update_success"));
        sendError(lang("msg_update_failed"));
    }
    function load_menus(){
        $base=$this->admin->load_base_menu();
        print_r($base);
    }
    /* USERS END */
    /* JENIS PEMERIKSAAN */
	public function load_jenis_pemeriksaan(){
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		$posted=modify_post($posted);
		$data=$this->jenis_pemeriksaan->_load_dt_jenis_pemeriksaan($posted);
		echo json_encode($data);
	}
	/* JENIS PEMERIKSAAN END */
	/* DOKTER */
	public function load_dokter(){
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		$posted=modify_post($posted);
		$data=$this->dokter->_load_dt($posted);
		echo json_encode($data);
	}
	/* DOKTER END */
	/* OTHERS SETTING */
	public function load_others_setting(){
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted=$this->input->input_stream();
		$posted=modify_post($posted);
		$data=$this->admin->_load_dt_others_setting($posted);
		echo json_encode($data);
	}
	public function get_setting($id){
		header('Content-Type: application/json');
		$data=$this->admin->_get_setting($id);
		echo json_encode($data);
	}
	public function save_others_setting__(){
		header('Content-Type: application/json');
		//isAllowed("c-privilege^create-update-user");
		$method=$this->input->method(true);
		if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
		$posted=$this->input->post();
		foreach($posted as $key=>$value){
			$$key=htmlentities(trim($value));
		}
		if($title=="") return sendError("Title Wajib diisi");
		if($content=="") return sendError("Content Wajib diisi");
				// update existing user
		$data=array(
			"title"=>$title,
			"content"=>$content
		);
		$save=$this->admin->_save_others_setting($data,array("id"=>htmlentities(trim($posted['_id']))),"title");
		if($save==="exist"){
			sendError(lang('msg_record_exist'));  
		}else{
			if($save>0)
				sendSuccess(lang('msg_update_success'));
			sendError(lang('msg_update_failed'));
		}
	}
	/* OTHERS SETTING END */
    public function backup_db(){
		//isAllowed("c::privilege");
		// Load the DB utility class
		$this->load->dbutil();
		// Backup your entire database and assign it to a variable
		$backup = $this->dbutil->backup();
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		$file_loc='files/bupsysdb/klinikymdb_'.date("Ymdhis").'.sql.gz';

		write_file("./".$file_loc, $backup);
		$save_list=$this->admin->save_backup_db(array("filename"=>$file_loc));
		$del_old=$this->admin->delete_old_backup_db();
		echo "Done";
		//redirect(base_url());
		/*
		$configMail = [
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'protocol'  => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'bupsysctc@gmail.com',  // Email gmail
            'smtp_pass'   => 'CTC#2021..Backup',  // Password gmail
            'smtp_crypto' => 'ssl',
            'smtp_port'   => 465,
            'crlf'    => "\r\n",
            'newline' => "\r\n"
        ];
		$this->load->library('email',$configMail);
		$this->email->from('admin.bup@codewell.co.id', 'CTC Admin');
		$this->email->to('yans.start@gmail.com');
		$this->email->attach(base_url($file_loc));
		$this->email->subject(conf('company_name')." | Backup Database");
		$this->email->message('Backup database');
		if ($this->email->send()) {
            echo 'Sukses! email berhasil dikirim.';
        } else {
            echo 'Error! email tidak dapat dikirim.';
        }
		*/
	}

    public function get_active_lang(){
        header('Content-Type: application/json');
        echo json_encode($this->lang->language);
    }
    

}

/* End of file Administrative.php */
/* Location: ./application/controllers/Administrative.php */
