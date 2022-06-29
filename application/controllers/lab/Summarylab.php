<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Summarylab extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		$this->load->model(conf('path_module_lab') . "Summarylab_model", "summary_lab");
	}
	public function count_pemeriksaan()
	{
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];
		$data = $this->summary_lab->count_pemeriksaan($this->data['C_PV_ID'], $clinic_id);
		sendJSON($data);
	}
}

/* End of file Pemeriksaan.php */
