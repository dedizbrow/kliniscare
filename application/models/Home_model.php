<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->tablePemeriksaan = "c_data_pemeriksaan";
		$this->tableJenisPemeriksaan = "c_jenis_pemeriksaan";
		$this->tableDetailPemeriksaan = "c_detail_pemeriksaan";
		$this->tableNotes = "c_data_notes";
		$this->tableJenisSampling = "c_jenis_sampling";
		$this->tableOpsiHasil = "c_opsi_hasil";
		$this->tableProvider = "c_data_provider";
		$this->load->helper('ctc');
	}
	function _load_dt_jumlah($posted, $provider_id = '')
	{
		$orders_cols = ["provider.nama", "nama_lengkap", "no_identitas", "nomor_rm", "jenis_kelamin", "tempat_lahir", "jenis_kelamin", "pasien.alamat", "no_hp", "email", "pasien.createdAt", "pasien.id_pasien"];
		$output = build_filter_table($posted, $orders_cols);
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere = "") ? " WHERE " : " AND ";
			$sWhere .= " (nama_lengkap LIKE '%" . $output->search . "%' OR no_identitas LIKE '%" . $output->search . "%')";
		}
		if ($provider_id != '' && $provider_id != 'pusat') {
			$sWhere .= ($sWhere = "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='$provider_id'";
		}
		$sLimit = $output->limit;
		$sGroup = " GROUP BY provider.nama";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT provider.nama as nama_provider,COUNT(pasien.id_pasien) as jumlah FROM tbl_pasien pasien INNER JOIN c_data_provider provider ON pasien.provider_id=provider.id $sWhere $sGroup $sOrder")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			return [
				$dt->nama_provider,
				$dt->jumlah
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function count_pasien_per_provider($provider_id = '')
	{
		$this->db->select('a.id,a.nama as provider,COALESCE(SUM(b.self_register=0),0) as jumlah,COALESCE(SUM(b.self_register=1 AND b.verified=0),0) as waiting');
		$this->db->from($this->tableProvider . " a");
		$this->db->join($this->tablePasien . " b", "a.id=b.provider_id");
		if ($provider_id != "") {
			$this->db->where("a.id", $provider_id);
		}
		$this->db->where("b.deleted", 0);
		$this->db->group_by("a.id");
		return $this->db->get()->result_object();
	}
	function count_pemeriksaan_per_provider($provider_id = '')
	{
		$this->db->select('provider.nama as provider,jp.jenis as jenis,COALESCE(SUM(periksa.status="SELESAI"),0) as jumlah_selesai,COALESCE(SUM(periksa.status=""),0) as waiting');
		$this->db->from($this->tableProvider . " provider");
		$this->db->join($this->tablePemeriksaan . " periksa", "periksa.id_provider=provider.id");
		$this->db->join($this->tableJenisPemeriksaan . " jp", "periksa.jenis_pemeriksaan=jp.id");
		if ($provider_id != "") {
			$this->db->where("periksa.id_provider", $provider_id);
		}
		$this->db->where("periksa.deleted", 0);
		if ($provider_id == '') $this->db->group_by("periksa.id_provider");
		$this->db->group_by("periksa.jenis_pemeriksaan");
		return $this->db->get()->result();
	}
}

/* End of file Home_model.php */
/* Location: ./application/models/Home_model.php */
