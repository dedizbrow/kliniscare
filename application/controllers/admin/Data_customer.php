<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_customer extends CI_Controller {
    var $data;
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('games', $this->session->userdata('site_lang'));
        $this->load->helper('Authentication');
        $this->data=isAuthorized();
        $this->load->model("admin/Data_customer_model","data_customer_model");
        isAllowed("c-privilege");
    }
    public function index()
    {}

    public function insert_data(){
        header('Content-Type: application/json');
        isAllowed("c-privilege^create-update-games");
        $method=$this->input->method(true);
        if($method!="POST" && $method!="PUT") sendError(lang('msg_method_post_put_required'), [],405);
        $posted=$this->input->post();
        $save = $this->data_customer_model->insert_data($posted);
        if($save == 0){
            sendError(lang("msg_insert_option_data_customer_failed"));
        }else{
            sendSuccess(lang("msg_insert_option_data_customer_success"));
        }
    }
}

/* End of file Data_customer.php */
/* Location: ./application/controllers/Data_customer.php */
