<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('billing', $this->session->userdata('site_lang'));
		$this->load->model('Billing_model', 'billing');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Billing";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data["page_title"] = "Billing";
		$this->data['js_control'] = "billing/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'billing/index', $this->data);
	}
	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->billing->_load_dt($posted);
		echo json_encode($data);
	}

	public function search__($id = '')
	{
		header('Content-Type: application/json');
		$gets = $this->input->get();
		$id = ($id != '') ? $id : $gets['id'];
		$id = htmlentities(trim($id));
		if ($id == '' || $id == null) sendError("Missing ID");

		$search = $this->billing->_search(array("tbl_pemeriksaan.id_pendaftaran" => $id));

		// $id_pendaftaran = $search[0]->id_pend;

		$total_biaya_tindakan = $this->billing->total_biaya_tindakan(array("tbl_pemeriksaan.id_pendaftaran" => $id));
		$total_biaya_resep = $this->billing->total_biaya_resep(array("id_pendaftaran" => $id));
		$total_biaya_kamar['total_biaya_kamar'] = $this->billing->total_biaya_kamar($id);

		$total_dibayar = $this->billing->total_dibayar(array("id_pendaftaran" => $id));

		$detail_tindakan = $this->billing->tindakan_detail(array("periksa.id_pendaftaran" => $id));
		$detail_resep = $this->billing->resep_detail(array("fk_pendaftaran" => $id));

		$detail_kamar = $this->billing->kamar_detail(array("fk_pendaftaran" => $id));

		$total_biaya['total_biaya'] = $this->billing->total_biaya($id);
		$biaya['biaya'] = $this->billing->biaya($id);
		$sisa['sisa'] = $this->billing->sisa($id);

		$data = array("data" => $search[0], "data_tot_biaya_tindakan" => $total_biaya_tindakan[0], "data_tot_biaya_resep" => $total_biaya_resep[0], "data_tot_dibayar" => $total_dibayar[0], "total_biaya" => $total_biaya, "total_biaya_kamar" => $total_biaya_kamar, "biaya" => $biaya, "sisa" => $sisa, "detail_tindakan" => $detail_tindakan, "detail_resep" => $detail_resep, "detail_kamar" => $detail_kamar);

		if (empty($search)) sendError(lang('msg_no_record'));
		echo json_encode($data);
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
		$clinic_id = (!isset($clinic_id) || $clinic_id == 'default') ? getClinic()->id : $clinic_id;
		if ($clinic_id == 'allclinic') return sendError("Klinik Belum di pilih");

		$fk_pendaftaran = htmlentities(trim($posted['_id']));
		$data_bayar = array(
			"biaya"             => $biaya,
			"tarif_dokter"             => $tarif_dokter,
			"create_at" 		=> date("Y-m-d"),
			"fk_pendaftaran"    => $fk_pendaftaran,
			"clinic_id" 		=> $clinic_id,
			'creator_id'   		=> $this->data['C_UID']
		);
		$this->billing->_save($data_bayar);
		$this->billing->update_status_bayar($fk_pendaftaran);
		echo json_encode(array("message" => "Pembayaran berhasil"));
	}

	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}

	public function print()
	{
		$this->data["web_title"] = lang('app_name_short') . "Print Billing";
		$this->data["page_title"] = lang('page_title');
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];

		$page_title = "Billing ";
		$paper_size = (isset($gets['size'])) ? $gets['size'] : 'A4';
		$mode = ($paper_size == 'A4') ? 'P' : 'P';
		if (!isset($gets['pendaftaran'])) {
			die("No Document can be print");
		}
		// update 2021-08-17
		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $clinic_id));
		$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);

		$vid = htmlentities($gets['pendaftaran']);
		$databilling = $this->billing->detail_pendaftar((int) htmlentities($vid));

		$detailtindakan = $this->billing->detail_tindakan_print((int) htmlentities($vid));
		$detailresep = $this->billing->detail_resep_print((int) htmlentities($vid));
		$detailkamar = $this->billing->detail_kamar_print((int) htmlentities($vid));
		$this->data['tot_biaya_kamar'] =  biaya_kamar((int) htmlentities($vid));

		if (empty($databilling)) die("Data Not exist");
		$dt_periksa = $databilling[0];
		$this->data['data_periksa'] = $dt_periksa;
		$dt_kamar = (sizeof($detailkamar)==0) ? "" : $detailkamar[0];
		$this->data['data_tindakan'] = $detailtindakan;
		$this->data['data_resep'] = $detailresep;
		$this->data['data_kamar'] = $dt_kamar;
		// $this->data['text_city_of_klinik'] = (isset($this->data['doc_setting']->text_city_of_klinik)) ? $this->data['doc_setting']->text_city_of_klinik->content : conf('lab_kota_praktek');
		$dt_profile =  $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));
		
		$data_profile = (sizeof($dt_profile)==0) ? "" : $dt_profile[0];
		$this->data['doc_setting_profile'] = $data_profile;

		$this->data['ttd_hasil'] = $this->data['doc_setting']->img_ttd_validator->path;
		$this->data['logo_ttd_suket'] = $this->data['doc_setting']->img_ttd_validator->path;

		$mpdf = new \Mpdf\Mpdf(['format' => $paper_size, 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);
		$this->data['ttd_kwitansi'] = './assets/img/' . conf('path_module_lab') . 'ttd-suket.png';
		$this->data['page_title'] = $page_title;
		$mpdf->SetAuthor(conf('lab_nama_klinik_id'));
		$mpdf->SetCreator(conf('lab_nama_klinik_id'));
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 30;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->addPage($mode, '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
		// $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/' . conf('path_module_lab') . 'img-header-kwitansi.png') . '"></div>', '', true);
		$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url($this->data['doc_setting']->img_doc_header->path) . '"></div>', '', true);

		$mpdf->defaultfooterline = 30;

		$html = $this->load->view('billing/print', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
}
