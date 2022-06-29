<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Piutang extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('farmasi/piutang', $this->session->userdata('site_lang'));
		$this->load->model('farmasi/Piutang_model', 'piutang');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Piutang";
		$this->data["page_title"] = "Piutang";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "farmasi/piutang/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'farmasi/piutang/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->piutang->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$search = $this->piutang->_search(array("piutang_id" => $id));

		$total_dibayar['total_dibayar'] = $this->piutang->total_piutang_dibayar($id);

		$sisa['sisa'] = $this->piutang->sisa_piutang($id);

		$data = array("data" => $search[0], "total_dibayar" => $total_dibayar, "sisa" => $sisa);

		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode($data);
	}
	public function search_detail_bayar_piutang($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");
		$detail = $this->piutang->_search_detail_bayar_piutang($id);

		if (empty($detail)) sendError("Belum ada riwayat pembayaran");
		echo json_encode(array("detail" => $detail));
	}
	public function save__()
	{
		header('Content-Type: application/json');
		$method = $this->input->method(true);
		if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
		$posted = $this->input->post();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($biaya == "") return sendError("Tidak boleh kosong");
		$fk_piutang = htmlentities(trim($posted['_id']));
		$data_bayar = array(
			"biaya"             => $biaya,
			"fk_piutang"    => $fk_piutang,
			"clinic_id" => $clinic_id,
		);
		$this->piutang->_save($data_bayar);
		if (sisa_piutang($fk_piutang) <= 0) {
			$this->piutang->_update_status_piutang($fk_piutang);
		}
		echo json_encode(array("message" => "Pembayaran berhasil"));
	}

	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
