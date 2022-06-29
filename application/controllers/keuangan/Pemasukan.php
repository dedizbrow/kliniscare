<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemasukan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('keuangan/pemasukan', $this->session->userdata('site_lang'));
		$this->load->model('keuangan/Pemasukan_model', 'pemasukan');
		$this->load->helper('Authentication');
		$this->data = isAuthorized();
		isAllowed('c-pemasukan');
	}
	public function index()
	{
		$this->data["web_title"] = lang('app_name_short') . " | Pemasukan";
		$this->data["page_title"] = "Pemasukan";
		// $this->data["page_title_small"] = "text small dibawah page title";
		$this->data['js_control'] = "keuangan/pemasukan/index.js";
		$this->data['datatable'] = true;
		$this->data['chartjs'] = false;
		$clinic_id = getClinic()->id;
		$gets = $this->input->get();
		$post_clinic=$this->input->post('clinic_id');
		if($clinic_id=='allclinic' && !isset($gets['clinic_id']) && isset($post_clinic)) $clinic_id=$post_clinic;
		if ($clinic_id == 'allclinic' && isset($gets['clinic_id'])) $clinic_id = $gets['clinic_id'];
		$this->data['clinic_id']=$clinic_id;
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		if (!isset($bulan)) {
			$bulan = date("m");
		}
		if (!isset($tahun)) {
			$tahun = date("Y");
		}
		$filter_date=$tahun."-".$bulan;
		$hari=$this->input->post('hari');
		if(isset($hari) && $hari!="") $filter_date.="-".$hari;
		// echo $filter_date;
		// die();
		$this->data['total_tindakan'] = $this->pemasukan->check_tindakan($filter_date, $clinic_id);
		// print_r($this->data['total_tindakan']);
		// die();
		$this->data['detail_tindakan'] = $this->pemasukan->detail_tindakan($filter_date, $clinic_id);

		$this->data['total_kamar'] = $this->pemasukan->check_ruangan($filter_date, $clinic_id);
		$this->data['detail_kamar'] = $this->pemasukan->detail_ruangan($filter_date, $clinic_id);

		$this->data['total_resep'] = $this->pemasukan->check_resep($filter_date, $clinic_id);
		$this->data['detail_resep'] = $this->pemasukan->detail_resep($filter_date, $clinic_id);

		$this->data['total_apotek_obat'] = $this->pemasukan->check_apotek_obat($filter_date, $clinic_id);
		$this->data['detail_apotek_obat'] = $this->pemasukan->detail_apotek_obat($filter_date, $clinic_id);

		$this->data['total_pemeriksaan_lab'] = $this->pemasukan->check_pemeriksaan_lab($filter_date, $clinic_id);

		$this->data['pengeluaran'] = $this->pemasukan->check_pengeluaran($filter_date, $clinic_id);
		$this->data['pengeluaran_apotek_obat'] = $this->pemasukan->check_pengeluaran_apotek_obat($filter_date, $clinic_id);

		$this->data['tahun'] = $tahun;
		$this->data['bulan'] = $bulan;
		$this->data['hari'] = $hari;

		$this->template->load(get_template(), 'keuangan/pemasukan/index', $this->data);
	}
	public function load_pemeriksaan_lab()
	{
		header('Content-Type: application/json');
		requiredMethod('POST');
		$posted = $this->input->input_stream();
		$posted = modify_post($posted);
		$data = $this->pemasukan->_load_pemeriksaan_lab($posted);
		echo json_encode($data);
	}
	public function get_active_lang()
	{
		header('Content-Type: application/json');
		echo json_encode($this->lang->language);
	}
}
