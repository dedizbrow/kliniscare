<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tablePasien = "tbl_pasien";
		$this->tableAntrian = "tbl_antrian";
		$this->tablePemeriksaan = "tbl_pemeriksaan";
		$this->tablePemTindakan = "tbl_pemeriksaan_tindakan";
		$this->tableBayarPeriksa = "tbl_bayar_periksa";
		$this->tableLayananPoli = "tbl_layanan_poli";
		$this->tableResepDetail = "tbl_resep_detail";
		$this->load->helper('ctc');
	}
	function _load_dt_fee_dokter($posted,$is_export)
	{
		$orders_cols = ["pemeriksaan.id_pemeriksaan", "pemeriksaan.create_at","poli.namaPoli","dokter.namaDokter","lay_poli.nama_layanan_poli","bayar_periksa.tarif_dokter","bayar_periksa.biaya","bayar_periksa.biaya"];
		$output = build_filter_table($posted, $orders_cols, [], "pendaftaran.clinic_id");
		$sWhere = $output->where;
		$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
		$sWhere.=" pendaftaran.status_bayar=1 AND bayar_periksa.tarif_dokter>0 ";
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (pasien.nomor_rm LIKE '%" . $output->search . "%' OR pendaftaran.no_invoice LIKE '%" . $output->search . "%')";
		}
		if(isset($posted['poli']) && $posted['poli']!="" && $posted['poli']!=null){
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere.=" lay_poli.id_poli='".htmlentities($posted['poli'])."'";
		}
		if(isset($posted['dokter']) && $posted['dokter']!="" && $posted['dokter']!=null){
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere.=" tindakan.fk_dokter='".htmlentities($posted['dokter'])."'";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col="pemeriksaan.create_at";
			// $end_date = strtotime("1 day", strtotime(htmlentities($posted['end_date'])));
			// $new_date = date("Y-m-d", $end_date);
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . htmlentities($posted['end_date']) . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}
		$sLimit = $output->limit;
		if($is_export) $sLimit="";
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",DATE_FORMAT(pemeriksaan.create_at,'%Y-%m-%d') as tgl_periksa,pasien.nomor_rm,pendaftaran.clinic_id,tindakan.fk_dokter,diagnosis.namaDiagnosis FROM tbl_pendaftaran AS pendaftaran 
		JOIN tbl_pemeriksaan AS pemeriksaan ON pemeriksaan.id_pendaftaran = pendaftaran.id_pendaftaran 
		INNER JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien 
		INNER JOIN tbl_bayar_periksa as bayar_periksa ON pendaftaran.id_pendaftaran=bayar_periksa.fk_pendaftaran
        INNER JOIN tbl_pemeriksaan_tindakan tindakan ON pemeriksaan.id_pemeriksaan=tindakan.fk_pemeriksaan
        INNER JOIN tbdaftardokter dokter ON tindakan.fk_dokter=idDokter AND pendaftaran.clinic_id=dokter.clinic_id
        INNER JOIN tbl_pemeriksaan_diagnosa diagnosa ON pemeriksaan.id_pemeriksaan=diagnosa.fk_pemeriksaan
        INNER JOIN tbdiagnosis diagnosis ON diagnosa.fk_diagnosa=diagnosis.idDiagnosis
        INNER JOIN tbl_layanan_poli lay_poli ON tindakan.fk_tindakan=lay_poli.id_layanan_poli
				INNER JOIN tbpoli poli ON lay_poli.id_poli=idPoli
    $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		if($is_export) return $data;
		$map_data = array_map(function ($dt) {
			// $id_pemeriksaan = $dt->id_pemeriksaan;
			$tarif_dokter=$dt->tarif_dokter;
			$total_biaya=$dt->biaya;
			$tarif_klinik=$total_biaya-$tarif_dokter;
			return [
				$dt->id_pemeriksaan,
				$dt->tgl_periksa,
				$dt->namaPoli,
				$dt->namaDokter,
				$dt->nama_layanan_poli,
				number_format($tarif_dokter),
				number_format($tarif_klinik),
				number_format($total_biaya)
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _load_dt_kunjungan_pasien($posted,$is_export)
	{
		$orders_cols = ["pemeriksaan.id_pemeriksaan", "pemeriksaan.create_at","poli.namaPoli","pasien.nama_lengkap","pasien.nomor_rm","pendaftaran.alasan_datang","dokter.namaDokter","lay_poli.nama_layanan_poli"];
		$output = build_filter_table($posted, $orders_cols, [], "pendaftaran.clinic_id");
		$sWhere = $output->where;
		// $sWhere.=($sWhere=="") ? " WHERE ": " AND ";
		// $sWhere.=" pendaftaran.status_bayar=1 AND bayar_periksa.tarif_dokter>0 ";
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (pasien.nomor_rm LIKE '%" . $output->search . "%' OR pendaftaran.no_invoice LIKE '%" . $output->search . "%')";
		}
		if(isset($posted['poli']) && $posted['poli']!="" && $posted['poli']!=null){
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere.=" lay_poli.id_poli='".htmlentities($posted['poli'])."'";
		}
		if(isset($posted['dokter']) && $posted['dokter']!="" && $posted['dokter']!=null){
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere.=" tindakan.fk_dokter='".htmlentities($posted['dokter'])."'";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere.=" DATE(pemeriksaan.create_at)='".htmlentities($posted['start_date'])."'";
		}
		$sLimit = $output->limit;
		$sGroup = " GROUP BY pemeriksaan.id_pemeriksaan";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",DATE_FORMAT(pemeriksaan.create_at,'%Y-%m-%d') as tgl_periksa,pasien.nomor_rm,pendaftaran.clinic_id,tindakan.fk_dokter,diagnosis.namaDiagnosis,
		pendaftaran.status_bayar,dokter.namaDokter
		 FROM tbl_pendaftaran AS pendaftaran 
		JOIN tbl_pemeriksaan AS pemeriksaan ON pemeriksaan.id_pendaftaran = pendaftaran.id_pendaftaran 
		INNER JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien 
		LEFT JOIN tbl_bayar_periksa as bayar_periksa ON pendaftaran.id_pendaftaran=bayar_periksa.fk_pendaftaran
        LEFT JOIN tbl_pemeriksaan_tindakan tindakan ON pemeriksaan.id_pemeriksaan=tindakan.fk_pemeriksaan
        LEFT JOIN tbdaftardokter dokter ON tindakan.fk_dokter=idDokter AND pendaftaran.clinic_id=dokter.clinic_id
        LEFT JOIN tbl_pemeriksaan_diagnosa diagnosa ON pemeriksaan.id_pemeriksaan=diagnosa.fk_pemeriksaan
        LEFT JOIN tbdiagnosis diagnosis ON diagnosa.fk_diagnosa=diagnosis.idDiagnosis
        LEFT JOIN tbl_layanan_poli lay_poli ON tindakan.fk_tindakan=lay_poli.id_layanan_poli
				LEFT JOIN tbpoli poli ON lay_poli.id_poli=idPoli
    $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		if($is_export) return $data;
		$map_data = array_map(function ($dt) {
			$dt->status="";
			if($dt->status_bayar==1) $dt->status="SELESAI";
			return [
				$dt->id_pemeriksaan,
				$dt->tgl_periksa,
				$dt->namaPoli,
				$dt->nama_lengkap,
				$dt->nomor_rm,
				$dt->alasan_datang,
				$dt->namaDokter,
				$dt->status
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _load_dt_summary_diagnosa($posted,$is_export)
	{
		$orders_cols = ["diagnosis.namaDiagnosis","pemeriksaan.id_pemeriksaan"];
		$output = build_filter_table($posted, $orders_cols, [], "pendaftaran.clinic_id");
		$sWhere = $output->where;
		// $sWhere.=($sWhere=="") ? " WHERE ": " AND ";
		// $sWhere.=" pendaftaran.status_bayar=1 AND bayar_periksa.tarif_dokter>0 ";
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (diagnosis.namaDiagnosis LIKE '%" . $output->search . "%')";
		}
		
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col="pemeriksaan.create_at";
			// $end_date = strtotime("1 day", strtotime(htmlentities($posted['end_date'])));
			// $new_date = date("Y-m-d", $end_date);
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$end_date=date("Y-m-d",strtotime($posted['end_date']." +1 days"));
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . $end_date . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}
		$sLimit = $output->limit;
		$sGroup = " GROUP BY diagnosis.idDiagnosis";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",diagnosis.namaDiagnosis,COUNT(pemeriksaan.id_pemeriksaan) as total 
		FROM tbl_pemeriksaan pemeriksaan
		INNER JOIN tbl_pendaftaran pendaftaran 
			ON pemeriksaan.id_pendaftaran = pendaftaran.id_pendaftaran 
		 INNER JOIN tbl_pemeriksaan_diagnosa diagnosa ON pemeriksaan.id_pemeriksaan=diagnosa.fk_pemeriksaan INNER JOIN tbdiagnosis diagnosis ON diagnosa.fk_diagnosa=diagnosis.idDiagnosis  
    $sWhere $sGroup ")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		if($is_export) return $data;
		$map_data = array_map(function ($dt) {
			// $id_pemeriksaan = $dt->id_pemeriksaan;
			return [
				$dt->namaDiagnosis,
				$dt->total
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
}
