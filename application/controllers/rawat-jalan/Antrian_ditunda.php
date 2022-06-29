<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_ditunda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rawat-jalan/antrian_ditunda', $this->session->userdata('site_lang'));
        $this->load->model('rawat-jalan/Antrian_ditunda_model', 'antrian_ditunda');
        $this->load->helper('Authentication');
        $this->data = isAuthorized();
		isAllowed('c-antrianperiksa');
    }
    public function index()
    {
        $this->data["web_title"] = lang('app_name_short') . " | Antrian telah dibatalkan";
        $this->data["page_title"] = "Pasien ditunda";
        $this->data["page_title_small"] = "Data pasien yang belum diperiksa";
        $this->data['js_control'] = "rawat-jalan/antrian_ditunda/index.js";
        $this->data['datatable'] = true;
        $this->data['chartjs'] = false;

        $this->template->load(get_template(), 'rawat-jalan/antrian_ditunda/index', $this->data);
    }
    public function load_dt()
    {
        header('Content-Type: application/json');
        requiredMethod('POST');
        $posted = $this->input->input_stream();
		$posted=modify_post($posted);
        $data = $this->antrian_ditunda->_load_dt($posted);
        echo json_encode($data);
    }

    public function ke_antrian($id = '')
    {
        $gets = $this->input->get();
        $id = ($id != '') ? $id : $gets['id'];
        $id = htmlentities(trim($id));
        $this->antrian_ditunda->ubah_status($id);
        echo json_encode(array("message" => "Ditambah ke <a href='antrian_pemeriksaan')'>Antrian</a>"));
    }
    public function get_active_lang()
    {
        header('Content-Type: application/json');
        echo json_encode($this->lang->language);
    }
}
