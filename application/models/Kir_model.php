<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kir_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["antrian.id_antrian", "pendaftaran.lama_mc", "pendaftaran.no_invoice,pasien.nama_lengkap", "pasien.tgl_lahir", "pasien.pekerjaan", "dokter.namaDokter", "antrian.create_at"];
		$output = build_filter_table($posted, $orders_cols, [], "antrian.clinic_id");
		$dateNow = date('Y-m-d');
		$sWhere = $output->where;

		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " antrian.status=2 and antrian.create_at LIKE '" . $dateNow . "%'";

		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",pendaftaran.id_pendaftaran,pasien.id_pasien,antrian.jenis_kunjungan,antrian.status 
		FROM tbl_antrian AS antrian LEFT JOIN tbl_pendaftaran AS pendaftaran ON antrian.id_antrian = pendaftaran.fk_antrian 
		LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien  
		LEFT JOIN tbdaftardokter as dokter ON pendaftaran.dpjp=dokter.idDokter  $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$clinic_id=$posted['clinic_id'];

		$map_data = array_map(function ($dt) use ($clinic_id) {
			$id_antrian = $dt->id_antrian;
			$link_print = ($dt->lama_mc > 0) ? '<a href="' . base_url('kir/print?antrian=' . $id_antrian . '&pasien=' . $dt->id_pasien.'&clinic_id='.$clinic_id) . '" target="_blank" class="print-pdf"><i class="fa fa-print tx-danger"></i></a>' : '<a href="' . base_url('kir/print?antrian=' . $id_antrian . '&pasien=' . $dt->id_pasien.'&clinic_id='.$clinic_id) . '" target="_blank" class="print-pdf"><i class="fa fa-print tx-success"></i></a>';
			return [
				$dt->id_antrian,
				$link_print,
				// '<a href="#" class="link-edit-diagnosis" data-id="' . $dt->id_pasien . '"><i class="fa fa-edit"></i></a>',
				'<input type="number" name="lama_mc" data-id="' . $dt->id_pendaftaran . '" value="' . $dt->lama_mc . '" class="form-control input-sm" style="width: 90px">',
				$dt->no_invoice,
				$dt->nama_lengkap,
				$dt->tgl_lahir,
				$dt->pekerjaan,
				$dt->namaDokter,
				$dt->create_at,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function update_mc($data, $where)
	{
		$this->db->update('tbl_pendaftaran', $data, $where);
		return $this->db->affected_rows();
	}
	function _search($antrian, $pasien)
	{
		$data = $this->db->query("
			SELECT antrian.*,pasien.id_pasien,pasien.perusahaan,pasien.pekerjaan,pasien.nomor_rm,pasien.nama_lengkap,pasien.alamat,pasien.tgl_lahir,pendaftaran.no_invoice,
			dokter.namaDokter,dokter.nip,dokter.position,spesial.namaSpesialisasi,pendaftaran.lama_mc,pendaftaran.create_at as tgl_periksa,
			periksa.kesadaran,periksa.anamnesa,periksa.pemeriksaan_umum,periksa.alergi,periksa.sistole,periksa.diastole,periksa.tensi,periksa.derajat_nadi,
			periksa.nafas,periksa.suhu_tubuh,periksa.saturasi,periksa.bb,periksa.tb,periksa.catatan_dokter,periksa.nyeri
			FROM 
			tbl_antrian AS antrian LEFT JOIN tbl_pendaftaran AS pendaftaran ON antrian.id_antrian = pendaftaran.fk_antrian 
			LEFT JOIN tbl_pemeriksaan periksa ON periksa.id_pendaftaran=pendaftaran.id_pendaftaran
			LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien  
			LEFT JOIN tbdaftardokter as dokter ON pendaftaran.dpjp=dokter.idDokter
			LEFT JOIN tbspesialisasi as spesial ON dokter.spesialisasi=spesial.idSpesialisasi
			WHERE antrian.status=2 and DATE(antrian.create_at)=CURDATE() AND antrian.id_antrian='" . $antrian . "' AND pasien.id_pasien='" . $pasien . "'")->result_object();
		return $data;
	}
}
