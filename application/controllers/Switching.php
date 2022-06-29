<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Switching extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	function lang($language="") {
		
		$language = ($language != "") ? $language : "indonesia";
		$this->session->set_userdata('site_lang', $language);
		$referred_from = $this->session->userdata('referred_from');
		redirect($referred_from, 'refresh');
	}
	function template($tpl=''){
		if($tpl=="") $tpl=conf('ctc_default_template');
		$this->load->helper('Authentication');
		$this->load->model("Switching_model","switching");
		$this->data=isAuthorized();
		$update=$this->switching->switch_template(array("template"=>$tpl),array("uid"=>$this->data['C_UID']));
		$this->session->set_userdata(conf('app_code').'CTC-TPL', $tpl);
		sendJSON(array("message"=>"success"));
	}
}

/* End of file LanguageSwitcher.php */
/* Location: ./application/controllers/LanguageSwitcher.php */
