<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Resep_dokter_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tableAturanPakai = "tbl_resep_aturan_pakai";
		$this->tableCaraPakai = "tbl_resep_cara_pakai";
		$this->tableObat = "tbl_obat";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["pendaftaran.id_pendaftaran", "pasien.id_pasien", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pasien.nama_lengkap", "asuransi.namaAsuransi", "pendaftaran.no_invoice", "resep.status", "dokter.namaDokter"];
		$output = build_filter_table($posted, $orders_cols, [], "pendaftaran.clinic_id");
		$sWhere = $output->where;

		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " not resep.status=0";

		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = 'pendaftaran.create_at';
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . htmlentities($posted['end_date']) . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}
		$sLimit = $output->limit;
		$sGroup = "GROUP BY no_invoice";
		$dateNow = date('Y-m-d');
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . "  FROM tbl_pendaftaran AS pendaftaran 
		LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien 
		LEFT JOIN tbl_pemeriksaan AS periksa ON pendaftaran.id_pendaftaran=periksa.id_pendaftaran  
		LEFT JOIN tbasuransi AS asuransi ON pasien.asuransi_utama = asuransi.idAsuransi 
		LEFT JOIN tbl_resep_detail AS resep ON resep.fk_pendaftaran=pendaftaran.id_pendaftaran 
		LEFT JOIN tbdaftardokter AS dokter ON pendaftaran.dpjp=dokter.idDokter $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id_pendaftaran = $dt->id_pendaftaran;
			return [
				$dt->id_pendaftaran,

				'' . ($dt->status == 1  ? "<a href='#' class='btn btn-success btn-xs link-detail-resep' data-id='" . $dt->id_pendaftaran . "'>Proses</a>" : "<div class='tooltip-inner' style='background-color: darkgray;'>Telah Diproses</div>") . '',


				$dt->no_invoice,
				$dt->namaAsuransi,
				$dt->no_asuransi,
				$dt->nomor_rm,
				$dt->nama_lengkap,
				$dt->namaDokter,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search_detail($where)
	{
		$this->db->select('pendaftaran.id_pendaftaran as _id,obt.kode,obt.nama as nama_obat,dtl.qty,format(dtl.total,0) as total,aturan.nama_aturan_pakai,cara.nama_cara_pakai,supplier.namaSupplier,s.namaSatuanobat,format(o_dtl.harga,0) as harga');
		$this->db->from($this->tablePendaftaran . " pendaftaran");
		$this->db->join('tbl_resep_detail dtl', 'pendaftaran.id_pendaftaran=dtl.fk_pendaftaran', 'RIGHT');
		$this->db->join('tbl_obat_detail o_dtl', 'dtl.satuan=o_dtl.obat_detail_id', 'left');
		$this->db->join('tbl_satuan_obat s', 'o_dtl.satuan=s.idSatuanobat', 'left');
		$this->db->join('tbl_resep_aturan_pakai aturan', 'dtl.aturan_pakai=aturan.id');
		$this->db->join('tbl_resep_cara_pakai cara', 'dtl.cara_pakai=cara.id');
		$this->db->join($this->tableObat . " as obt", "dtl.fk_obat=obt.idObat", "LEFT");
		$this->db->join('tbsupplier supplier', 'supplier.idSupplier=obt.supplier', 'LEFT');

		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search($where)
	{
		$this->db->select('pendaftaran.id_pendaftaran as _id ,pendaftaran.no_invoice,pendaftaran.create_at,pasien.nomor_rm,pasien.nama_lengkap');
		$this->db->from($this->tablePendaftaran . " pendaftaran");
		$this->db->join('tbl_pasien pasien', 'pendaftaran.fk_pasien=pasien.id_pasien', 'RIGHT');

		$this->db->where($where);
		return $this->db->get()->result();
	}
	function simpan_proses_resep_jual($id)
	{
		// update stok obat and detail
		$this->db->query("UPDATE tbl_obat obat INNER JOIN tbl_resep_detail dtl ON obat.idObat=dtl.fk_obat SET obat.stok=obat.stok-(dtl.qty*dtl.isi),dtl.fk_pendaftaran='" . $id . "',dtl.status='2' WHERE dtl.fk_pendaftaran='" . $id . "'");
	}
}
