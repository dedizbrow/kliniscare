<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kwitansi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePasien = "tbl_pasien";
		$this->tablePemeriksaan = "lab_data_pemeriksaan";
		$this->tableDetailPemeriksaan = "lab_detail_pemeriksaan";
		$this->tableNotes = "lab_data_notes";
		$this->tableJenisSampling = "lab_jenis_sampling";
		$this->tableOpsiHasil = "lab_opsi_hasil";
		$this->tableSetting = "lab_settings";
		$this->load->helper('ctc');
	}
	function _load_dt($posted, $provider_id = '')
	{
		$orders_cols = ["periksa.tgl_periksa", "periksa.tgl_sampling", "pasien.id_pasien", "pasien.no_identitas", "pasien.nama_lengkap", "pasien.jenis_kelamin", "pasien.tgl_lahir", "jenis.jenis", "periksa.status", "periksa.biaya", "update_hasil_at", "periksa.id"];
		$output = build_filter_table($posted, $orders_cols, ["pasien.nomor_rm"],"periksa.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
			$sWhere.= " (nama_lengkap LIKE '%" . $output->search . "%' OR no_identitas LIKE '%" . $output->search . "%' OR periksa.id_pasien LIKE '%" . $output->search . "%')";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " pasien.deleted=0 AND periksa.status='SELESAI' AND DATE(update_hasil_at)=CURDATE()";
		if ($provider_id != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='$provider_id'";
		}
		if (isset($posted['filter_provider']) && $posted['filter_provider'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " provider_id='" . htmlentities($posted['filter_provider']) . "'";
		}
		if (isset($posted['filter_pemeriksaan']) && $posted['filter_pemeriksaan'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " jenis_pemeriksaan='" . htmlentities($posted['filter_pemeriksaan']) . "'";
		}
		if (isset($posted['filter_status']) && $posted['filter_status'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$stts = htmlentities(trim($posted['filter_status']));
			if ($stts == "BELUM") $stts = "";
			$sWhere .= " status='" . $stts . "'";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = (isset($posted['filter_tgl_option']) && htmlentities(trim($posted['filter_tgl_option'])) == "tgl_sampling") ? 'tgl_sampling' : 'tgl_periksa';
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . htmlentities($posted['end_date']) . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}

		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		if ($posted['order']) {
			$ord = $posted['order'][0];
			$col = $ord['column'];
			$dir = $ord['dir'];
			if (isset($order_cols[(int) $col]) && $order_cols[(int) $col] == "pasien.nomor_rm") {
				$sOrder = " ORDER BY LENGTH(" . $order_cols[(int) $col] . ") ASC," . $order_cols[(int) $col] . " " . $dir;
			}
		}
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("
			SELECT SQL_CALC_FOUND_ROWS periksa.id,periksa.tgl_periksa,LPAD(periksa.no_test,5,'0') as no_test,periksa.keluhan,periksa.status,
			LPAD(pasien.nomor_rm,5,'0') as no_pasien,periksa.tgl_sampling,pasien.nomor_rm,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			periksa.update_hasil_at,periksa.cancel_remarks,periksa.deleted,periksa.biaya,periksa.bayar,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,
			provider.nama as provider,jenis.jenis as jenis_pemeriksaan
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			INNER JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
		$sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			$status = strtoupper($dt->status);
			$update_at = ($dt->update_hasil_at != "") ? $dt->update_hasil_at : "";
			if ($dt->bayar == 0) {
				$link = ' <a href="#" title="Confirm Bayar" class="btn btn-xs btn-primary confirm-bayar" data-id="' . $dt->id . '" data-tn="' . strtolower($dt->no_test) . '">Confirm Bayar</a>';
			} else {
				$link = ' <a href="' . base_url(conf('path_module_lab') . 'kwitansi/print/?viewid=' . $dt->id . '&tn=' . strtolower($dt->no_test)) . '&pdf=true" target="_blank" title="Print Kwitansi"><i class="fa fa-print text-danger"></i></a>';
			}

			return [
				$dt->no_test,
				$dt->tgl_periksa,
				$dt->tgl_sampling,
				$dt->no_pasien,
				$dt->no_identitas,
				$dt->nama_pasien,
				$dt->jenis_kelamin,
				$dt->usia,
				$dt->jenis_pemeriksaan,
				$status,
				format_number($dt->biaya),
				$update_at,
				$link,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;

		$output->data = $map_data;
		return (array) $output;
	}
	function _search($id, $tn)
	{
		$data = $this->db->query("
			SELECT periksa.id,periksa.id_provider,periksa.tgl_periksa,LPAD(periksa.no_test,5,'0') as no_test,periksa.keluhan,periksa.status,periksa.id_notes,
			pasien.nomor_rm as id_pasien,pasien.nomor_rm as id_pasien_int,periksa.hasil,periksa.status,DATE_FORMAT(periksa.update_hasil_at,'%Y-%m-%d %H:%i:%s') as update_hasil_at,periksa.id_dokter,periksa.jenis_sample,
			periksa.masa_berlaku,periksa.masa_berlaku_opt,DATE_FORMAT(periksa.tgl_sampling,'%Y-%m-%d') as tgl_sampling,DATE_FORMAT(periksa.tgl_sampling,'%H:%i') as jam_sampling,
			pasien.nama_lengkap as nama_pasien,pasien.no_identitas,pasien.nomor_rm,pasien.jenis_kelamin,pasien.tempat_lahir,pasien.tgl_lahir,pasien.no_hp,IF(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE())<1,CONCAT(TIMESTAMPDIFF(MONTH, pasien.tgl_lahir, CURDATE()),' bulan'),CONCAT(TIMESTAMPDIFF(YEAR, pasien.tgl_lahir, CURDATE()),' tahun')) as usia,pasien.alamat,IFNULL(pasien.kewarganegaraan,'') as kewarganegaraan,
			provider.nama as provider,
			dokter.namaDokter as dokter,
			jenis.jenis as jenis_pemeriksaan, periksa.jenis_pemeriksaan as id_jenis,
			sampling.nama_sampling as nama_sample,sampling.nama_sampling_en as nama_sample_en,sampling.id as id_sample
			FROM lab_data_pemeriksaan periksa
			INNER JOIN tbl_pasien pasien ON periksa.id_pasien=pasien.id_pasien
			INNER JOIN lab_data_provider provider ON periksa.id_provider=provider.id
			LEFT JOIN tbdaftardokter dokter ON periksa.id_dokter=dokter.idDokter
			LEFT JOIN lab_jenis_pemeriksaan jenis ON periksa.jenis_pemeriksaan=jenis.id
			LEFT JOIN lab_jenis_sampling sampling ON periksa.jenis_sample=sampling.id
			WHERE periksa.id='" . $id . "' AND periksa.no_test='" . $tn . "' AND periksa.status='SELESAI' AND DATE(update_hasil_at)=CURDATE() GROUP BY periksa.id")->result_object();
		return $data;
	}
	function confirm_bayar($id, $tn)
	{
		$this->db->set('bayar', 'biaya', FALSE);
		$this->db->where(array("id" => $id, "no_test" => $tn));
		$this->db->update($this->tablePemeriksaan);
		return $this->db->affected_rows();
	}
}

/* End of file Kwitansi_model.php */
