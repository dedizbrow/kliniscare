<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian extends CI_Controller {
	 public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		redirect('antrian/tv');
	}
	public function tv(){
		$this->load->view('antrian/index');
	}
}
