<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Koreksi_tindakan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tablePemeriksaan = "tbl_pemeriksaan";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["pend.id_pendaftaran", "pend.no_invoice", "pend.create_at", "pend.nama_lengkap_pjw", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pend.dpjp", "pasien.nama_lengkap", "pasien.alamat", "checkin.fk_ruangan", "checkin.create_at as tgl_checkin", "ruangan.namaRuangan", "dokter.namaDokter"];
		$output = build_filter_table($posted, $orders_cols,[],"pend.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
			$sWhere.= "nama_lengkap LIKE '%" . $output->search . "%'";
		}
		$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
		$sWhere.=" status_rawat=2  ";
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pend.*," . implode(",", $orders_cols) . " FROM tbl_pendaftaran AS pend JOIN tbl_pasien AS pasien ON pend.fk_pasien = pasien.id_pasien JOIN tbruangan_transaksi as checkin ON pend.id_pendaftaran=checkin.fk_pendaftaran JOIN tbruangan as ruangan ON checkin.fk_ruangan=ruangan.idRuangan JOIN tbdaftardokter as dokter ON pend.dpjp=dokter.idDokter $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id_pendaftaran = $dt->id_pendaftaran;
			$link_resep=($dt->status_rawat == 2) ? " <a href='#' class='btn btn-success btn-xs link-resep' style='width: 50px;' data-id='" . $dt->id_pendaftaran . "'>Resep</a> " : "";
			return [
				$dt->id_pendaftaran,
				'<a href="#" class="btn btn-primary btn-xs link-periksa" data-dismiss="modal" data-id="' . $dt->id_pendaftaran . '"><i class="fa fa-stethoscope"></i></a> <button href="#" class="btn btn-primary btn-xs link-detail" data-dismiss="modal" data-id="' . $dt->id_pendaftaran . '">Detail</button> '.
				$link_resep,
				$dt->no_invoice,
				$dt->tgl_checkin,

				$dt->nomor_rm,
				$dt->nama_lengkap,
				$dt->namaRuangan,
				$dt->alamat,
				$dt->namaDokter,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search_pendaftaran($where)
	{
		$this->db->select('pend.id_pendaftaran as id_pendaftaran,pend.poli,pasien.nama_lengkap,pasien.nomor_rm,dokter.namaDokter,poliklinik.namaPoli');
		$this->db->from('tbl_pendaftaran as pend');
		$this->db->join('tbl_pasien as pasien', 'pend.fk_pasien=pasien.id_pasien');
		$this->db->join('tbdaftardokter as dokter', 'pend.dpjp=dokter.idDokter');
		$this->db->join('tbpoli as poliklinik', 'pend.poli=poliklinik.idPoli', 'LEFT');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _save($data)
	{
		$this->db->trans_start();
		$this->db->insert($this->tablePemeriksaan, $data);
		$id_pemeriksaan = $this->db->insert_id();
		$this->db->trans_complete();
		return $id_pemeriksaan;
	}
	function _save_diagnosa($data)
	{
		$this->db->insert_batch('tbl_pemeriksaan_diagnosa', $data);
	}
	function _save_tindakan($data)
	{
		$this->db->insert_batch('tbl_pemeriksaan_tindakan', $data);
	}

	function _search_pemeriksaan($id)
	{
		$data = $this->db->query('select id_pasien as _id,nomor_rm,nama_lengkap,pend.*,pem.create_at as tanggal_kunjungan,pem.*,IFNULL(GROUP_CONCAT(DISTINCT diag.namaDiagnosis SEPARATOR "|"),"") as diagnosa,IFNULL(GROUP_CONCAT(DISTINCT layanan_poli.nama_layanan_poli SEPARATOR "|"),"") as tindakan FROM tbl_pasien pasien INNER JOIN tbl_pendaftaran pend ON pasien.id_pasien=pend.fk_pasien 
		LEFT JOIN tbl_pemeriksaan pem ON pend.id_pendaftaran=pem.id_pendaftaran
		LEFT JOIN tbl_pemeriksaan_diagnosa p_diag ON pem.id_pemeriksaan=p_diag.fk_pemeriksaan
        LEFT JOIN tbdiagnosis diag ON p_diag.fk_diagnosa=diag.idDiagnosis
        LEFT JOIN tbl_pemeriksaan_tindakan p_tind ON pem.id_pemeriksaan=p_tind.fk_pemeriksaan
		LEFT JOIN tbl_layanan_poli layanan_poli ON p_tind.fk_tindakan=layanan_poli.id_layanan_poli
		WHERE pend.id_pendaftaran="' . $id . '"  GROUP BY pem.id_pemeriksaan ORDER BY pend.id_pendaftaran DESC')->result_object();
		return $data;
	}
}
