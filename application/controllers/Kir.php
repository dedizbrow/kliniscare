<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kir extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('Kir', $this->session->userdata('site_lang'));
		$this->load->model('Kir_model', 'kir');
		$this->load->model("/admin/Other_setting_model", "other_set");
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		// isAllowed('c-kir');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | KIR";
		$this->data["page_title"] = "KIR";
		$this->data["page_title_small"] = "Surat keterangan sehat/sakit";
		$this->data['js_control'] = "kir/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;

		$this->template->load(get_template(), 'kir/index', $this->data);
	}

	public function load_dt()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->kir->_load_dt($posted);
		echo json_encode($data);
	}
	public function test()
	{
		$this->data["web_title"] = lang('app_name_short') . "Print";
		$this->data["page_title"] = lang('page_title');
		$this->data['js_control'] = "/kir/print.js";
		$this->data['datatable'] = true;
		$gets = $this->input->get();
		$this->data['status'] = "";
		if (isset($gets['status'])) $this->data['status'] = htmlentities($gets['status']);
		$this->template->load(get_template(), 'kir/print', $this->data);
	}
	public function print()
	{
		$this->data["web_title"] = lang('app_name_short') . "Print KIR";
		$this->data["page_title"] = lang('page_title');
		$gets = $this->input->get();
		$page_title = "KIR - MC ";
		$paper_size = (isset($gets['size'])) ? $gets['size'] : 'A4';
		$mode = ($paper_size == 'A4') ? 'P' : 'P';
		if (!isset($gets['antrian']) || !isset($gets['pasien'])) {
			die("No Document can be print");
		}
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];

		$doc_setting = $this->other_set->get_setting_doc_requirements(array("clinic_id" => $clinic_id));
		$this->data['doc_setting'] = array_group_by("code", $doc_setting, true);

		$vid = htmlentities($gets['antrian']);
		$periksa = $this->kir->_search((int) htmlentities($vid), htmlentities((int) trim($gets['pasien'])));
		if (empty($periksa)) die("Data Not exist");
		$dt_periksa = $periksa[0];
		$dt_periksa->tgl_mc = date("d-m-Y", strtotime($dt_periksa->tgl_periksa));
		$dt_periksa->tgl_mc_end = ($dt_periksa->lama_mc > 1) ? date("d-m-Y", strtotime($dt_periksa->tgl_periksa . ' +' . ($dt_periksa->lama_mc - 1) . ' days')) : $dt_periksa->tgl_mc;
		// echo '<pre>';
		// print_r($dt_periksa);
		// echo '</pre>';
		// die();
		$this->data['data_periksa'] = $dt_periksa;
		$dt_profile =  $this->other_set->get_com_profile(array("clinic_id" => $clinic_id));
		
		$data_profile = $dt_profile[0];
		$this->data['doc_setting_profile'] = $data_profile;

		// $this->data['text_city_of_klinik'] = (isset($this->data['doc_setting']->text_city_of_klinik)) ? $this->data['doc_setting']->text_city_of_klinik->content : conf('lab_kota_praktek');
		// $detail=$this->pemeriksaan->_search_detail($vid);
		// $this->data['detail_periksa']=$detail;
		$mpdf = new \Mpdf\Mpdf(['format' => $paper_size, 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

		$this->data['page_title'] = $page_title;
		$mpdf->SetAuthor(conf('lab_nama_klinik_id'));
		$mpdf->SetCreator(conf('lab_nama_klinik_id'));
		$mpdf->SetTitle($page_title);
		$mpdf->SetSubject($page_title);
		$mpdf->shrink_tables_to_fit = 1;
		//$mpdf->SetProtection(['print'],'','--YM^21..');
		$margin_left = 0;
		$margin_right = 0;
		$margin_top = 30;
		$margin_bottom = 0;
		$margin_header_top = 0;
		$margin_footer_bottom = 0;
		$mpdf->addPage($mode, '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);
		$mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url($this->data['doc_setting']->img_doc_header->path) . '"></div>', '', true);

		// $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/' . conf('path_module_lab') . 'img-header-kwitansi.png') . '"></div>', '', true);
		//<img src="'.base_url('/assets/img/ym-doc-footer.jpg').'">
		// $mpdf->SetHTMLFooter('<div class="page-footer-pdf"><div class="page-footer-text">'.$setting_doc->content.'</div></div>'); 
		// $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="'.base_url('/assets/img/'.conf('path_module_lab').'img-doc-footer.png').'"></div>','',false);
		$mpdf->defaultfooterline = 30;

		$html = $this->load->view('kir/print', $this->data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output($page_title . ".pdf", 'I');
	}
	public function update_mc()
	{
		header('Content-Type: application/json');
		requiredMethod('PUT');
		$posted = $this->input->input_stream();
		foreach ($posted as $key => $value) {
			$$key = htmlentities(trim($value));
		}
		if ($lama_mc == "") $lama_mc = 0;
		$data = array(
			"lama_mc" => $lama_mc,
			"mc_set_on" => date("Y-m-d H:i:s")
		);
		$save = $this->kir->update_mc($data, array("id_pendaftaran" => htmlentities(trim($posted['_id']))));
		if ($save > 0) sendSuccess("Lama MC berhasil di set");
		return sendError("Tidak ada perubahan data");
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
