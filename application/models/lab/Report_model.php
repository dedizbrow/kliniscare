<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->tablePemeriksaan = "lab_data_pemeriksaan";
		$this->tableJenisPemeriksaan = "lab_jenis_pemeriksaan";
		$this->tableDetailPemeriksaan = "c_detail_pemeriksaan";
		$this->tableNotes = "lab_data_notes";
		$this->tableJenisSampling = "lab_jenis_sampling";
		$this->tableOpsiHasil = "lab_opsi_hasil";
		$this->tableProvider = "lab_data_provider";
		$this->load->helper('ctc');
	}
	function count_pemeriksaan_per_provider($provider_id = '', $date_like = '',$clinic_id)
	{
		$this->db->select('provider.nama as provider,jp.jenis as jenis,COALESCE(SUM(periksa.status="SELESAI"),0) as jumlah_selesai,COALESCE(SUM(periksa.status=""),0) as waiting');
		$this->db->from($this->tableProvider . " provider");
		$this->db->join($this->tablePemeriksaan . " periksa", "periksa.id_provider=provider.id");
		$this->db->join($this->tableJenisPemeriksaan . " jp", "periksa.jenis_pemeriksaan=jp.id");
		if ($provider_id != "") {
			$this->db->where("periksa.id_provider", $provider_id);
		}
		if ($date_like != '') {
			$this->db->like("periksa.tgl_periksa", $date_like, "after");
		}
		$this->db->where(array("periksa.deleted"=>0,"periksa.clinic_id"=>$clinic_id));
		if ($provider_id == '') $this->db->group_by("periksa.id_provider");
		$this->db->group_by("periksa.id_provider,periksa.jenis_pemeriksaan");
		return $this->db->get()->result();
	}
	function load_report_pemeriksaan($provider, $jenis, $start_date, $end_date, $hasil_pemeriksaan = '',$clinic_id)
	{
		$and_hasil_pemeriksaan = ($hasil_pemeriksaan != "") ? ' AND a.hasil="' . $hasil_pemeriksaan . '"' : "";
		$data = $this->db->query('SELECT a.id_pasien,b.nama_lengkap as nama_pasien,a.tgl_periksa,a.hasil FROM lab_data_pemeriksaan a INNER JOIN tbl_pasien b ON a.id_pasien=b.id_pasien WHERE a.id_provider="' . $provider . '" AND a.jenis_pemeriksaan="' . $jenis . '" AND a.status="SELESAI" AND a.tgl_periksa BETWEEN "' . $start_date . '" AND "' . $end_date . '" ' . $and_hasil_pemeriksaan . ' AND a.clinic_id="'.$clinic_id.'" GROUP BY a.id ORDER BY a.id ASC')->result_object();
		return $data;
	}
	function count_daily_checkup($provider, $jenis, $start_date, $end_date)
	{
		$data = $this->db->query('SELECT tgl_periksa,update_hasil_at as tgl_hasil,COUNT(id) as qty FROM lab_data_pemeriksaan WHERE id_provider="' . $provider . '" AND jenis_pemeriksaan="' . $jenis . '" AND status="SELESAI" AND tgl_periksa BETWEEN "' . $start_date . '" AND "' . $end_date . '" GROUP BY tgl_periksa')->result_object();
		return $data;
	}
	function load_last_tarif($provider, $jenis, $start_date, $end_date)
	{
		$data = $this->db->query("select start_date,nominal FROM lab_tarif WHERE provider_id='" . $provider . "' AND jenis_id='" . $jenis . "' AND start_date<='" . $start_date . "' ORDER BY start_date DESC LIMIT 1")->result_object();
		return $data;
	}
	function load_tarif($provider, $jenis, $start_date, $end_date, $last_date = '')
	{
		$andWhere = ($last_date != "") ? " AND start_date>='" . $last_date . "'" : "";
		$data = $this->db->query("select start_date,nominal FROM lab_tarif WHERE provider_id='" . $provider . "' AND jenis_id='" . $jenis . "' AND start_date<'" . $end_date . "' " . $andWhere . " ORDER BY start_date ASC")->result_object();
		return $data;
	}
	function get_list_opsi_hasil($jenis_id)
	{
		$this->db->select('hasil')->from('lab_opsi_hasil');
		$this->db->where(array("jenis_id" => $jenis_id, "is_main" => 1));
		$this->db->group_by('hasil');
		return $this->db->get()->result_object();
	}
}

/* End of file Report_model.php */
/* Location: ./application/models/Report_model.php */
