<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home_Visit_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('ctc');
    }

    function _load_dt($posted)
    {
    }
}
