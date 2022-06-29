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
		$this->load->model("/admin/Administrative_model","admin");
		isAllowed("c-privilege");
	}
	function array_recursive_search_key_map($needle, $haystack) {
		foreach($haystack as $first_level_key=>$value) {
			if ($needle === $value) {
				return array($first_level_key);
			} elseif (is_array($value)) {
				$callback = $this->array_recursive_search_key_map($needle, $value);
				if ($callback) {
					return array_merge(array($first_level_key), $callback);
				}
			}
		}
		return false;
	}
	public function index()
	{
		$this->data["web_title"]=lang('app_name_short'). " | Administrative";
		$this->data["page_title"]="Administrator Area";
		$this->data['js_control']="admin/administrative.js";
		$this->data['datatable']=true;
		$gets=modify_post($this->input->get());
		$clinic_id = (!isset($gets['clinic_id']) || $gets['clinic_id'] == 'default') ? getClinic()->id : $gets['clinic_id'];
		if ($clinic_id == 'allclinic'){
			$base_menus=$this->admin->load_base_menu();
		}else{
			$search_enabled_menus=$this->admin->search_enabled_menus($clinic_id);
			if(sizeof($search_enabled_menus)==0 || $search_enabled_menus[0]->enabled_menus==""){
				$base_menus=[];
				$this->data['error_menu']="Tidak ada menu yang tersedia untuk akun anda. Silahkan hubungi kami";
			}else{
				$enabled_menus=explode(",",$search_enabled_menus[0]->enabled_menus);
				$base_menus=$this->admin->load_base_menu($enabled_menus);
			} 
			
		}
		
		$menus=$this->admin->load_menus();
		$nested_menu = array();
		foreach ($menus as $element) {
			if(strpos($element->access_code,"ctc::")!==false && $this->data['C_USRNAME']!=conf('super_admin_id')){
			}else{
				$nested_menu[$element->base_id][] = $element;
			}
			
		}
		$nested_base=array();
		foreach($base_menus as $base){
			if(strpos($base->access_code,"ctc::")!==false && $this->data['C_USRNAME']!=conf('super_admin_id')){
			}else{
				$nested_base[]=(Object) ["base"=>$base,"main"=>($base->has_child==1 && isset($nested_menu[$base->id])) ? $nested_menu[$base->id] : []];
			}
		}
		// echo "<pre>";
		// print_r($nested_menu);
		// echo "</pre>";
		// die();
        // $this->data['base_menu']=$base_menus;
		// $this->data['menus']=$menus;
		$this->data['nested_base_menu']=$nested_base;
        $this->data['manage_user']=false;
        $this->data['manage_work_type']=false;
        if(isAllowedSection("c-privilege")) 
            $this->data['manage_user']=true;
			// die();
		$this->template->load(get_template(),'admin/administrative/index',$this->data);
    }
    /* USERS */
    public function load_dt_users(){
    	header('Content-Type: application/json');
    	$posted=$this->input->post();
		$posted=modify_post($posted);
    	$data=$this->admin->dt_users($posted);
    	echo json_encode($data);
    }
    public function search_user($id=''){
        $gets=$this->input->get();
		$gets=modify_post($gets);
		$clinic_id = (!isset($gets['clinic_id']) || $gets['clinic_id'] == 'default') ? getClinic()->id : $gets['clinic_id'];
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");
        $id=($id!='') ? $id : $gets['id'];
        header('Content-Type: application/json');
        $search=$this->admin->search_user(array("uid"=>$id));
		$search_enabled_menus=$this->admin->search_enabled_menus($clinic_id);
		if(sizeof($search_enabled_menus)==0) return sendError("Belum ada menu yang dienable untuk clinic ini");
		$enabled_menus=explode(",",$search_enabled_menus[0]->enabled_menus);
        if(empty($search)) sendError(lang('msg_no_record'));
        //$data_user=$this->admin->list_work_process();
        $is_superAdmin=false;
        if($search[0]->level==conf('super_admin_code')){
            $is_superAdmin=true;
        }
        $privilege=array(
			"accessibility_base"=>explode(",",$search[0]->accessibility_base),
            "actions_code_base"=>explode(",",$search[0]->actions_code_base),
            "accessibility"=>explode(",",$search[0]->accessibility),
            "actions_code"=>explode(",",$search[0]->actions_code),
            "superAdmin"=>$is_superAdmin
        );

        unset($search[0]->accessibility);
        unset($search[0]->actions_code);
        unset($search[0]->level);
        echo json_encode(array("data"=>$search[0],"privilege"=>$privilege,"enabled_menus"=>$enabled_menus));
    }
    public function save_user(){
        header('Content-Type: application/json');
        
        $method=$this->input->method(true);
        if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
        $posted=$this->input->post();
		$posted=modify_post($posted); // update 2021-09-27
        $email=htmlentities(trim($posted['email']));
        $password=htmlentities(trim($posted['password']));
        $accessibility_base=(isset($posted['accessibility_base'])) ? implode(",",array_unique($posted['accessibility_base'])) : "";
        $accessibility_menu=(isset($posted['accessibility_menu'])) ? implode(",",array_unique($posted['accessibility_menu'])) : "";
        $actions_code_base=(isset($posted['actions_code_base'])) ? implode(",",array_unique($posted['actions_code_base'])): "";
        $actions_code_menu=(isset($posted['actions_code_menu'])) ? implode(",",array_unique($posted['actions_code_menu'])): "";
        $level=(isset($posted['level'])) ? $posted['level'] : "";
        if(!isEmailValid($email)) 
            sendError(lang('msg_invalid_email'));
        if(!isset($posted['user_id']) || $posted['user_id']==""){
			isAllowed("c-privilege^create");
            // add new user
            if(!isMatch($password, htmlentities(trim($posted['repassword'])))) 
                sendError(lang('msg_password_not_match'));

            $save=$this->admin->save_user(array(
                "uid"=>mt_rand(10100000, 99999999),
                "name"=>htmlentities(trim($posted['name'])),
                "uname"=>htmlentities(trim($posted['username'])),
                "email"=>htmlentities(trim($posted['email'])),
                "passwd"=>hashPasswd($posted['password']),
				"accessibility_base"=>$accessibility_base,
                "actions_code_base"=>$actions_code_base,
                "accessibility"=>$accessibility_menu,
                "actions_code"=>$actions_code_menu,
                "level"=>$level,
				"clinic_id"=>$posted['clinic_id'],
				"created_by"=>$this->data['C_NAME'],
                "template"=>conf('ctc_default_template')
            ),array());
            if($save=='exist'){
                sendError(lang('msg_username_used'));  
            }else{
                if($save==0) sendError(lang('msg_insert_user_failed'));
                sendSuccess(lang('msg_insert_user_success'));
            }
        }else{
			isAllowed("c-privilege^update");
            // update existing user
            $data=array(
                "name"=>htmlentities(trim($posted['name'])),
                "uname"=>htmlentities(trim($posted['username'])),
                "email"=>htmlentities(trim($posted['email'])),
                "accessibility_base"=>$accessibility_base,
                "actions_code_base"=>$actions_code_base,
                "accessibility"=>$accessibility_menu,
                "actions_code"=>$actions_code_menu,
				"update_by"=>$this->data['C_NAME'],
                "level"=>$level
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
        isAllowed("c-privilege^activate-user");
        $posted=$this->input->post();
        $save=$this->admin->enable_disable_user(array("enabled"=>$posted['status']),array("uid"=>$posted['id']));
        if($save>0) sendSuccess(lang("msg_update_success"));
        sendError(lang("msg_update_failed"));
    }
    function load_menus(){
        $base=$this->admin->load_base_menu();
        print_r($base);
    }
    
    public function get_active_lang(){
        header('Content-Type: application/json');
        echo json_encode($this->lang->language);
    }
	// added 2021-09-21 :: list user pasien
	public function akun_pasien()
	{
		$this->data["web_title"]=lang('app_name_short'). " | List Akun Pasien";
		$this->data["page_title"]="Administrator Area";
		$this->data['js_control']="admin/akun-pasien.js";
		$this->data['datatable']=true;
		
        if(isAllowedSection("c-privilege^create-pasien")) 
            $this->data['manage_akun_pasien']=true;
		$this->template->load(get_template(),'admin/administrative/akun-pasien',$this->data);
    }
    /* USERS */
    public function load_dt_akun_pasien(){
    	header('Content-Type: application/json');
    	$posted=$this->input->post();
    	$data=$this->admin->dt_akun_pasien($posted);
    	echo $data;
    }
    

}

/* End of file Administrative.php */
/* Location: ./application/controllers/Administrative.php */
