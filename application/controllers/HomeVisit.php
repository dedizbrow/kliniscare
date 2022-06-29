<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HomeVisit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('homeVisit', $this->session->userdata('site_lang'));
        $this->load->model('Home_Visit_model', 'home');
        $this->load->helper('Authentication');
        $this->data = isAuthorized();
    }
    public function index()
    {
        $this->data["web_title"] = lang('app_name_short') . " | Home Visit";
        // $this->data["page_title_small"] = "text small dibawah page title";
        $this->data['js_control'] = "home-visit/index.js";
        $this->data['datatable'] = true;
        $this->data['chartjs'] = false;

        $this->template->load(get_template(), 'home-visit/index', $this->data);
    }
    public function load_dt()
    {
        header('Content-Type: application/json');
        requiredMethod('POST');
        $posted = $this->input->input_stream();
        $data = $this->KunjunganIGD->_load_dt($posted);
        echo json_encode($data);
    }
    public function get_active_lang()
    {
        header('Content-Type: application/json');
        echo json_encode($this->lang->language);
    }
}