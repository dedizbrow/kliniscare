<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Summarylab_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->tablePemeriksaan = "lab_data_pemeriksaan";
		$this->tableJenisPemeriksaan = "lab_jenis_pemeriksaan";
		$this->tableDetailPemeriksaan = "lab_detail_pemeriksaan";
		$this->tableNotes = "lab_data_notes";
		$this->tableJenisSampling = "lab_jenis_sampling";
		$this->tableOpsiHasil = "lab_opsi_hasil";
		$this->tableProvider = "lab_data_provider";
		$this->load->helper('ctc');
	}
	// summary pemeriksaan
	function count_pemeriksaan($provider_id = '', $clinic_id)
	{
		$this->db->select('provider.nama as provider,jp.jenis as jenis,COALESCE(SUM(periksa.status="SELESAI" AND periksa.deleted=0),0) as jumlah_selesai,COALESCE(SUM(periksa.status="" AND periksa.deleted=0),0) as waiting,COALESCE(SUM(periksa.status="CANCEL" AND periksa.deleted=0),0) as cancel');
		$this->db->from($this->tableProvider . " provider");
		$this->db->join($this->tablePemeriksaan . " periksa", "periksa.id_provider=provider.id");
		$this->db->join($this->tableJenisPemeriksaan . " jp", "periksa.jenis_pemeriksaan=jp.id");
		$this->db->where("periksa.clinic_id", $clinic_id);
		if ($provider_id != "") {
			$this->db->where("periksa.id_provider", $provider_id);
		}
		if ($provider_id == '') $this->db->group_by("periksa.id_provider");
		$this->db->group_by("periksa.jenis_pemeriksaan");
		return $this->db->get()->result();
	}
	function count_pasien($provider_id = '')
	{
		$this->db->select('a.id,a.nama as provider,COALESCE(SUM(b.verified=1),0) as jumlah,COALESCE(SUM(b.self_register=1 AND b.verified=0),0) as waiting');
		$this->db->from($this->tableProvider . " a");
		$this->db->join($this->tablePasien . " b", "a.id=b.provider_id");
		if ($provider_id != "") {
			$this->db->where("a.id", $provider_id);
		}
		$this->db->where("b.deleted", 0);
		$this->db->group_by("a.id");
		return $this->db->get()->result_object();
	}
}

/* End of file Summarylab_model.php */
