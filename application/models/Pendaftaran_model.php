<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tablePasien = "tbl_pasien";
		$this->tableAntrian = "tbl_antrian";
		$this->tablePerujuk = "tbperujuk";
		$this->tableAgama = "fix_agama";
		$this->tableGolDarah = "fix_gol_darah";
		$this->tableStatusPernikahan = "fix_status_pernikahan";
		$this->tableAsuransi = "tbasuransi";
		$this->tablePoli = "tbpoli";
		$this->tableKaryawan = "tbkaryawan";
		$this->tableDokter = "tbdaftardokter";
		$this->tableProvinsi = "wilayah_provinsi";
		$this->tableKabupaten = "wilayah_kabupaten";
		$this->tableKecamatan = "wilayah_kecamatan";
		$this->load->helper('ctc');
	}

	function searchcode($clinic_id)
	{
		$query=$this->db->query('SELECT IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)+1 as new_no FROM tbl_pasien WHERE clinic_id="'.$clinic_id.'" ORDER BY LENGTH(IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)) DESC, IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0) DESC LIMIT 1');
		// $this->db->select('IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)+1 as new_no', false);
		// $this->db->where("clinic_id", $clinic_id);
		// // $this->db->order_by('LENGTH(IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0))', 'DESC');
		// $this->db->order_by('IFNULL(CAST(REPLACE(nomor_rm,"RM-","") as UNSIGNED),0)', 'DESC');
		// $this->db->limit(1);
		// $dta = $this->db->get('tbl_pasien')->row();
		$dta=$query->row();
		$no = 1;
		if ($dta != null) $no = $dta->new_no;
		$no = str_pad($no, 5, '0', STR_PAD_LEFT);
		return "RM-$no";
	}
	function searchcode_antrian($clinic_id)
	{
		$this->db->select('RIGHT(tbl_antrian.nomor_antrian,3) as nomor_antrian');
		$this->db->order_by('nomor_antrian', 'DESC');
		$this->db->where("clinic_id", $clinic_id);
		$this->db->where('date_format(create_at,"%Y-%m-%d")', 'CURDATE()', FALSE);
		$this->db->limit(1);
		$query = $this->db->get('tbl_antrian');
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = intval($data->nomor_antrian) + 1;
		} else {
			$kode = 1;
		}
		return str_pad($kode, 3, "0", STR_PAD_LEFT);
	}
	function isNomorRMExist($nomor_rm, $clinic_id)
	{
		$query = $this->db->get_where('tbl_pasien', array("nomor_rm" => $nomor_rm, "clinic_id" => $clinic_id));
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function _save_pasien($data_pasien)
	{
		$this->db->trans_start();
		$this->db->insert($this->tablePasien, $data_pasien);
		$pasien_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $pasien_id;
	}

	function _save_antrian($data_antrian)
	{
		$this->db->trans_start();
		$this->db->insert($this->tableAntrian, $data_antrian);
		$antrian_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $antrian_id;
	}
	function _save($data_pendaftaran)
	{
		$this->db->trans_start();
		$this->db->insert($this->tablePendaftaran, $data_pendaftaran);
		$user_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $user_id;
	}

	function _search_select_perujuk($key = "", $clinic_id)
	{
		$this->db->select('idPerujuk as id,namaPerujuk as text');
		$this->db->from($this->tablePerujuk);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaPerujuk', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_agama($key = "")
	{
		$this->db->select('id_agama as id,nama_agama as text');
		$this->db->from($this->tableAgama);
		if ($key != "") {
			$this->db->like('nama_agama', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_gol_darah($key = "")
	{
		$this->db->select('id_gol_darah as id,nama_gol_darah as text');
		$this->db->from($this->tableGolDarah);
		if ($key != "") {
			$this->db->like('nama_gol_darah', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_status_nikah($key = "")
	{
		$this->db->select('id_status_pernikahan as id,nama_status_pernikahan as text');
		$this->db->from($this->tableStatusPernikahan);
		if ($key != "") {
			$this->db->like('nama_status_pernikahan', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_asuransi($key = "", $clinic_id)
	{
		$this->db->select('idAsuransi as id,namaAsuransi as text');
		$this->db->from($this->tableAsuransi);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaAsuransi', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_poli($key = "", $clinic_id)
	{
		$this->db->select('idPoli as id,namaPoli as text, label_antrian as label');
		$this->db->from($this->tablePoli);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaPoli', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_dpjp($key = "", $clinic_id)
	{
		$this->db->select('idDokter as id,namaDokter as text');
		$this->db->from($this->tableDokter);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaDokter', $key);
		}
		// $this->db->WHERE('bidang', 2);
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_provinsi($key = "")
	{
		$this->db->select('id as id,nama as text');
		$this->db->from($this->tableProvinsi);
		if ($key != "") {
			$this->db->like('nama', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_kabupaten($key = '', $keys)
	{
		$this->db->select('id as id,nama as text');
		$this->db->from($this->tableKabupaten);
		if ($key != "") {
			$this->db->like('nama', $key);
		}
		$this->db->WHERE('provinsi_id', $keys);
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_kecamatan($key = '', $keys)
	{
		$this->db->select('id as id,nama as text');
		$this->db->from($this->tableKecamatan);
		if ($key != "") {
			$this->db->like('nama', $key);
		}
		$this->db->WHERE('kabupaten_id', $keys);
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function get_no_invoice($clinic_id)
	{
		$this->db->select('RIGHT(tbl_pendaftaran.no_invoice,3) as no_invoice');
		$this->db->order_by('no_invoice', 'DESC');
		$this->db->limit(1);
		$this->db->where("clinic_id", $clinic_id);
		$query = $this->db->get('tbl_pendaftaran');
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = intval($data->no_invoice) + 1;
		} else {
			$kode = 1;
		}
		$kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
		date_default_timezone_set('Asia/Jakarta');
		$kodejadi = "INV" . date('ymd') . $kodemax;
		return $kodejadi;
	}
	function isInvoiceExist($no_invoice, $clinic_id)
	{
		$query = $this->db->get_where($this->tablePendaftaran, array("no_invoice" => $no_invoice, "clinic_id" => $clinic_id));
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// function check_antrian($id)
	// {
	//     $q = $this->db->query("SELECT count(nomor_antrian) as nomor_antrian from tbl_antrian WHERE poli='$id' and STR_TO_DATE('create_at', '%Y-%m-%d') = CURDATE();");

	//     return $q->num_rows();
	// }
	function cari_antrian($where)
	{
		$this->db->select('IFNULL(MAX(CAST(REGEXP_SUBSTR(nomor_antrian,"[0-9]+$") AS UNSIGNED)),0)+1 as new_no', FALSE);
		$this->db->where($where);
		$this->db->where('date_format(create_at,"%Y-%m-%d")', 'CURDATE()', FALSE);
		// $this->db->order_by("id_antrian", "desc");
		// $this->db->limit(1);
		$dta = $this->db->get($this->tableAntrian)->row();
		$no = 1;
		if (!$dta == null && !empty($dta)) $no = $dta->new_no;
		return str_pad($no, 3, "0", STR_PAD_LEFT);
	}
	function isAntrianExist($no_antrian, $clinic_id)
	{
		$this->db->select("*")->from($this->tableAntrian);
		$this->db->where(array("nomor_antrian" => $no_antrian, "clinic_id" => $clinic_id));
		$this->db->where('date_format(create_at,"%Y-%m-%d")', 'CURDATE()', FALSE);
		$dt=$this->db->get()->result_object();
		if (!empty($dt)) {
			return true;
		} else {
			return false;
		}
	}
	function get_no_antrian($id)
	{
		$this->db->select('pend.id_pendaftaran,a.id_antrian,a.nomor_antrian, b.namaPoli');
		$this->db->from('tbl_pendaftaran as pend');
		$this->db->join('tbl_antrian as a', 'pend.fk_antrian=a.id_antrian');
		$this->db->join('tbpoli as b', 'a.poli=b.idPoli');
		$this->db->WHERE('id_pendaftaran', $id);

		return $this->db->get()->result();
	}
}
