<?php

defined('BASEPATH') or exit('No direct script access allowed');

class KunjunganIGD_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableTriase = "tbl_triase";

		$this->tablePasien = "tbl_pasien";
		$this->tablePerujuk = "tbperujuk";
		$this->tableRuangan = "tbruangan";
		$this->tableKelas = "tbruangan_kelas";
		$this->tableKategori = "tbruangan_kategori";
		$this->tableAsuransi = "tbasuransi";
		$this->tableKaryawan = "tbkaryawan";
		$this->tableDokter = "tbdaftardokter";
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tableAgama = "fix_agama";
		$this->tableGolDarah = "fix_gol_darah";
		$this->tableStatusPernikahan = "fix_status_pernikahan";
		$this->tableDiagnosa = "tbdiagnosis";
		$this->tableProvinsi = "wilayah_provinsi";
		$this->tableKabupaten = "wilayah_kabupaten";
		$this->tableKecamatan = "wilayah_kecamatan";
		$this->load->helper('ctc');
	}

	function _load_dt_pasien($posted)
	{
		$orders_cols = ["id_pasien", "nomor_rm", "nama_lengkap", "no_hp", "no_telp", "alamat", "no_identitas"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_pasien.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (no_identitas LIKE '%" . $output->search . "%' OR nama_lengkap LIKE '%" . $output->search . "%')";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_pasien $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id_pasien = $dt->id_pasien;
			return [
				$dt->nomor_rm,
				$dt->nama_lengkap,
				$dt->no_identitas,
				$dt->no_hp,
				$dt->alamat,
				'<a href="#" class="btn btn-primary btn-xs link-tambah-daftar-pasien" data-dismiss="modal" data-id="' . $dt->id_pasien . '"><i class="fa fa-plus"></i></a>'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search_pasien($where)
	{
		$this->db->select('pasien.id_pasien as _id,pasien.*, fix_agama.nama_agama as agama, tbasuransi.namaAsuransi as asuransi_utama,fix_status_pernikahan.nama_status_pernikahan as status_nikah,fix_gol_darah.nama_gol_darah as gol_darah,wilayah_provinsi.nama as provinsi,wilayah_kabupaten.nama as kabupaten,wilayah_kecamatan.nama as kecamatan');
		$this->db->from('tbl_pasien as pasien');
		$this->db->join($this->tableAgama, 'fix_agama.id_agama=pasien.agama', 'LEFT');
		$this->db->join($this->tableStatusPernikahan, 'fix_status_pernikahan.id_status_pernikahan=pasien.status_nikah', 'LEFT');
		$this->db->join($this->tableGolDarah, 'fix_gol_darah.id_gol_darah=pasien.gol_darah', 'LEFT');
		$this->db->join($this->tableAsuransi, 'tbasuransi.idAsuransi = pasien.asuransi_utama ', 'LEFT');
		$this->db->join($this->tableProvinsi, 'wilayah_provinsi.id = pasien.provinsi ', 'LEFT');
		$this->db->join($this->tableKabupaten, 'wilayah_kabupaten.id = pasien.kabupaten ', 'LEFT');
		$this->db->join($this->tableKecamatan, 'wilayah_kecamatan.id = pasien.kecamatan ', 'LEFT');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _save_triase($data_triase)
	{
		$this->db->trans_start();
		$this->db->insert($this->tableTriase, $data_triase);
		$triase_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $triase_id;
	}
	function _save_diagnosa($data)
	{
		$this->db->insert_batch('tbl_triase_diagnosa', $data);
		// return $this->db->affected_rows();
	}
	function _save_pendaftar_igd($data_pendaftar_igd)
	{
		$this->db->trans_start();
		$this->db->insert($this->tablePendaftaran, $data_pendaftar_igd);
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
	function _save_pasien($data_pasien)
	{
		$this->db->trans_start();
		$this->db->insert($this->tablePasien, $data_pasien);
		$pasien_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $pasien_id;
	}
	function _search_select_diagnosa($key = "", $clinic_id)
	{
		$this->db->select('idDiagnosis as id,namaDiagnosis as text');
		$this->db->from($this->tableDiagnosa);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaDiagnosis', $key);
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
		$kodejadi = "INVIGD" . date('ymd') . $kodemax;
		return $kodejadi;
	}
}
