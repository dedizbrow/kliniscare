<?php
function isAuthorized($public=""){
	$ci = &get_instance(); 
	$ci->load->library('session');
	$app_code=$ci->config->item('app_code');
	if($ci->session->userdata($app_code."CTC-X-KEY")){
		$data=json_decode(base64_decode($ci->session->userdata($app_code.'CTC-X-KEY')));
		$user_profile=($data->profile!="") ? base_url('files/imgs/'.$data->profile) : base_url('assets/user-img/img-user-default.png');
		$data=array(
				'app_code'=>$app_code,
				'C_UID'=>$data->uid,
				'C_NAME'=>$data->name,
				'C_USRNAME'=> $data->usrname,
				'C_EMAIL'=> $data->email,
				'C_USER_PROFILE'=> $user_profile,
				'C_PV_ID'=> (isset($data->provider_id)) ? $data->provider_id : '',
				'C_PV_GROUP'=> (isset($data->pv_group)) ? $data->pv_group: '',
				'C_PV_NAME'=> (isset($data->pv_name)) ? $data->pv_name : '',
				'DEF_CLINIC_ID'=>$ci->session->userdata($app_code.'CTC-CL-ID'),
				'DEF_CLINIC_NAME'=>$ci->session->userdata($app_code.'CTC-CL-NAME'),
				'DEF_CLINIC_LOGO'=>($data->clinic_logo!="") ? $data->clinic_logo : "assets/img/clinics/default.png",
				'add_js'=>'assets/pages/admin/common.admin.js'
				);
		return $data;
	}else
	if($public!=""){
		//$this->load->library('PHPRequests');
		$options=array("x-user-agent"=>"ptte-api");
		$data=array(
			'C_NAME'=>"Guest",
			'C_EMAIL'=> "---"
			);
		return $data;
	}
	return false;
}
if(!isAuthorized()){
	$ci = &get_instance();
	$current_url=current_url();
	$params   = $_SERVER['QUERY_STRING'];
	$redirect_url = "$current_url?$params";
	$is_ajax=false;
	$headers=getallheaders();
	if(isset($headers['x-user-agent']) && $headers['x-user-agent']=="ctc-webapi" && strpos($headers['Content-Type'],"application/x-www-form-urlencoded")!==false){
		$is_ajax=true;
	}else
	if(isset($headers['x-user-agent']) && $headers['x-user-agent']=="ctc-webapi" && strpos($headers['Content-Type'],"application/json")!==false){
		$is_ajax=true;
	}
	if($is_ajax) sendError("Session timeout, Anda perlu refresh dan login ulang");

	if($ci->uri->segment(1)!=="auth"){
		$last_char=substr($redirect_url, -1);
		$first_char=substr($redirect_url, 3);
		if($last_char==="?") $redirect_url=str_replace("?","",$redirect_url);
		if($last_char==="?" && $first_char==="www") $redirect_url="";
		redirect("admin/auth/signin?redirect=$redirect_url","refresh");
	}
}
function isAllowed($access_code,$cond=false,$mode=''){
	$ci = &get_instance(); 
	$app_code=$ci->config->item('app_code');
	//$accessibility=explode(",",$ci->session->userdata("CTC-ACT-CODE"));
	$accessibility=json_decode($ci->session->userdata($app_code."CTC-ACT-CODE"));
	$data=json_decode(base64_decode($ci->session->userdata($app_code.'CTC-X-KEY')));
	if($data->level==$ci->config->item('super_admin_code') && $mode==''){
		return true;
	}else
	if($data->level==$ci->config->item('super_admin_code') && $mode=='strict' && $data->usrname==conf('super_admin_id')){
		return true;
	}else
	{
		//echo $access_code;
		//echo gettype($accessibility);
		//print_r($accessibility);
		if(gettype($accessibility)=="object") $accessibility=(array) $accessibility;
		if(!in_array($access_code,$accessibility)){
			if($cond==true){
				return false;
			}else{
				$headers = apache_request_headers();
				$is_ajax = (isset($headers['x-user-agent']) && $headers['x-user-agent']=="ctc-webapi") ? true : false;
				if($is_ajax){  
					if(!$ci->input->post('draw'))
				  		http_response_code(403);
				  $output = [];
			        $output['sEcho'] = 0;
			        $output['draw'] = 1;
			        $output['iTotalRecords'] = 0;
			        $output['iTotalDisplayRecords'] = 0;
			        $output['data']=[];
				  die(json_encode(array("error"=>lang('msg_error_403'),"data"=>$output)));
				}else{

					$ci->session->set_userdata('url_403', base_url(uri_string()));
					
					redirect("error/403","refresh");
				}
			}
		}else{
			return true;
		}
	}
}
function isAllowedSection($access_code,$cond=false){
	$ci = &get_instance(); 
	$ci->load->library('session');
	$app_code=$ci->config->item('app_code');
	//$accessibility=explode(",",$ci->session->userdata("CTC-ACT-CODE"));
	$accessibility=json_decode($ci->session->userdata($app_code."CTC-ACT-CODE"));
	$data=json_decode(base64_decode($ci->session->userdata($app_code.'CTC-X-KEY')));
	if($data->level==$ci->config->item('super_admin_code')){
		return true;
	}else{
		//echo $access_code;
		//print_r(json_decode($accessibility));
		//die();
		if(gettype($accessibility)=="object") $accessibility=(array) $accessibility;
		if(!in_array($access_code,$accessibility)){
			if($cond==true){
				return false;
			}else{
				$headers = apache_request_headers();
				$is_ajax = (isset($headers['x-user-agent']) && $headers['x-user-agent']=="ctc-webapi") ? true : false;
				if($is_ajax){  
					if(!$ci->input->post('draw'))
				  		http_response_code(403);
				  $output = [];
			        $output['sEcho'] = 0;
			        $output['draw'] = 1;
			        $output['iTotalRecords'] = 0;
			        $output['iTotalDisplayRecords'] = 0;
			        $output['data']=[];
				  die(json_encode(array("error"=>lang('msg_error_403'),"data"=>$output)));
				}else{
					return false;
				}
			}
		}else{
			return true;
		}
	}
}
