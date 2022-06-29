<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		$this->data=isAuthorized();
		date_default_timezone_set("Asia/Bangkok");
	}
	public function index()
	{
		
	}
	public function Error403(){
		$this->data["page_title"]="Error 403!";
		$this->template->load(get_template(),'/errors/error_403',$this->data);
		//$this->load->view('errors/error_403');
	}

}

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */