<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Billing_model extends CI_Model
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
	function _load_dt($posted)
	{
		$orders_cols = ["pemeriksaan.id_pemeriksaan", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pasien.nama_lengkap", "pasien.alamat", "pendaftaran.id_pendaftaran", "pendaftaran.create_at as tgl_daftar", "pendaftaran.no_invoice", "pendaftaran.status_rawat", "pendaftaran.status_bayar", "bayar_periksa.create_at as tgl_bayar"];
		$output = build_filter_table($posted, $orders_cols, [], "pendaftaran.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (pasien.nomor_rm LIKE '%" . $output->search . "%' OR pendaftaran.no_invoice LIKE '%" . $output->search . "%')";
		}
		if (isset($posted['filter_pemeriksaan']) && $posted['filter_pemeriksaan'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$fil_pend = htmlentities(trim($posted['filter_pemeriksaan']));
			$fil_pend = explode('|', $fil_pend);
			$fil_pend1 = $fil_pend[0];
			$fil_pend2 = $fil_pend[1];
			$fil_pend3 = $fil_pend[2];
			$sWhere .= " status_rawat NOT IN ('" . $fil_pend1 . "','" . $fil_pend2 . "','" . $fil_pend3 . "') ";
			// $sWhere .= " status_rawat='" . $fil_pend1 . "' or status_rawat='" . $fil_pend2 . "' or status_rawat='" . $fil_pend3 . "' ";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = (isset($posted['filter_tgl_option']) && htmlentities(trim($posted['filter_tgl_option'])) == "1" ? 'pendaftaran.create_at' : 'bayar_periksa.create_at');

			// $end_date = strtotime("1 day", strtotime(htmlentities($posted['end_date'])));
			// $new_date = date("Y-m-d", $end_date);
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . htmlentities($posted['end_date']) . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}
		$sLimit = $output->limit;
		$sGroup = "GROUP BY no_invoice";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_pendaftaran AS pendaftaran 
		JOIN tbl_pemeriksaan AS pemeriksaan ON pemeriksaan.id_pendaftaran = pendaftaran.id_pendaftaran 
		INNER JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien 
		LEFT JOIN tbl_bayar_periksa as bayar_periksa ON pendaftaran.id_pendaftaran=bayar_periksa.fk_pendaftaran $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			// $id_pemeriksaan = $dt->id_pemeriksaan;
			$id_pendaftaran = $dt->id_pendaftaran;
			$biaya_pemeriksaan = biaya_pemeriksaan($id_pendaftaran);
			$biaya_resep = biaya_resep($id_pendaftaran);
			$total_biaya = total_biaya($id_pendaftaran);
			$sisa = sisa_pemeriksaan($id_pendaftaran);
			$biaya_kamar = biaya_kamar($id_pendaftaran);
			$tanggal_terakhir_bayar = terakhir_dibayar_pemeriksaan($id_pendaftaran);
			return [
				$dt->id_pendaftaran,
				// '' . ($sisa == 0 ? "<a href='" . base_url('billing/print?pendaftaran=' . $id_pendaftaran) . "' target='_blank'><i class='fa fa-print text-danger'></i></a>" :  "<a href='#'  class='btn btn-indigo btn-xs link-bayar' data-id='" . $dt->id_pendaftaran . "'>Bayar</a>") . '',
				'' . ($dt->status_rawat == 2  ? "<div class='tooltip-inner' style='background-color: cornflowerblue;'>INAP</div>" : ($sisa == 0  ? "<a href='" . base_url('billing/print?pendaftaran=' . $id_pendaftaran) . "' target='_blank'><i class='fa fa-print text-danger'></i></a>" : "<a href='#'  class='btn btn-success btn-xs link-bayar' data-id='" . $dt->id_pendaftaran . "'>Bayar</a>")) . '',
				$dt->no_invoice,
				$dt->nomor_rm,
				$dt->nama_lengkap,
				$dt->tgl_daftar,
				number_format($biaya_resep),
				number_format($biaya_pemeriksaan),
				number_format($biaya_kamar),
				'<b>' . number_format($total_biaya) . '</b>',
				'' . ($sisa == 0 ? "<p style='color: blue;'>LUNAS</p>" :  number_format($sisa)) . '',
				// number_format($sisa),
				$dt->tgl_bayar,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}

	function _search($where)
	{
		$this->db->select('tbl_pendaftaran.id_pendaftaran as _id,tbl_pendaftaran.id_pendaftaran as id_pend,tbl_pasien.asuransi_utama, tbl_pasien.nomor_rm, tbl_pasien.nama_lengkap, tbl_pendaftaran.create_at,tbl_pendaftaran.no_invoice');
		$this->db->from('tbl_pendaftaran');

		$this->db->join($this->tablePemeriksaan, 'tbl_pendaftaran.id_pendaftaran = tbl_pemeriksaan.id_pendaftaran');
		$this->db->join($this->tablePasien, 'tbl_pasien.id_pasien = tbl_pendaftaran.fk_pasien');

		$this->db->where($where);
		return $this->db->get()->result();
	}
	function total_biaya_tindakan($where)
	{
		$this->db->select('GROUP_CONCAT(tbl_layanan_poli.nama_layanan_poli) as layanan,sum(tbl_layanan_poli.harga_layanan_poli) as total_biaya_tindakan,SUM(tarif_dokter) as tarif_dokter');
		$this->db->from('tbl_pendaftaran as pend');

		$this->db->join($this->tablePemeriksaan, 'tbl_pemeriksaan.id_pendaftaran = pend.id_pendaftaran');
		$this->db->join($this->tablePemTindakan, 'tbl_pemeriksaan_tindakan.fk_pemeriksaan = tbl_pemeriksaan.id_pemeriksaan');
		$this->db->join($this->tableLayananPoli, 'tbl_layanan_poli.id_layanan_poli = tbl_pemeriksaan_tindakan.fk_tindakan');
		$this->db->where($where);
		$q=$this->db->get()->result();
		// echo $this->db->last_query();
		return $q;
	}
	function total_biaya_resep($where)
	{
		$this->db->select('sum(tbl_resep_detail.total) as total_biaya_resep');
		$this->db->from('tbl_pendaftaran');

		$this->db->join($this->tableResepDetail, 'tbl_resep_detail.fk_pendaftaran=tbl_pendaftaran.id_pendaftaran');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function total_biaya_kamar($id)
	{
		return $total_biaya_kamar['total_biaya_kamar'] = biaya_kamar($id);
	}
	function total_dibayar($where)
	{
		$this->db->select('sum(tbl_bayar_periksa.biaya) as total_dibayar');
		$this->db->from('tbl_pemeriksaan as periksa');

		$this->db->join($this->tableBayarPeriksa, 'tbl_bayar_periksa.fk_pendaftaran = periksa.id_pendaftaran');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function total_biaya($id)
	{
		return $total_biaya['total_biaya'] = total_biaya($id);
	}
	function biaya($id)
	{
		return $biaya['biaya'] = sisa_pemeriksaan($id);
	}
	function sisa($id)
	{
		return $sisa['sisa'] = sisa_pemeriksaan($id);
	}
	function _save($data)
	{
		$this->db->insert($this->tableBayarPeriksa, $data);
		return $this->db->affected_rows();
	}
	function update_status_bayar($id_pendaftaran)
	{
		$this->db->query("UPDATE tbl_pendaftaran SET status_bayar=1  where id_pendaftaran='$id_pendaftaran'"); //1 = lunas
	}
	function tindakan_detail($where)
	{
		$this->db->select('tindakan.fk_dokter,tindakan.fk_tindakan, layanan.nama_layanan_poli, dokter.namaDokter,layanan.harga_layanan_poli,periksa.create_at');
		$this->db->from('tbl_pemeriksaan as periksa');
		$this->db->join('tbl_pemeriksaan_tindakan tindakan', 'periksa.id_pemeriksaan=tindakan.fk_pemeriksaan');
		$this->db->join('tbl_layanan_poli layanan', 'tindakan.fk_tindakan=layanan.id_layanan_poli');
		$this->db->join('tbdaftardokter dokter', 'tindakan.fk_dokter=dokter.idDokter');
		$this->db->join('tbl_pendaftaran pend', 'periksa.id_pendaftaran=pend.id_pendaftaran');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function resep_detail($where)
	{
		$this->db->select('resep.aturan_pakai, resep.cara_pakai, resep.fk_obat, resep.qty,resep.total, obat.nama, aturan.nama_aturan_pakai,cara.nama_cara_pakai,supplier.namaSupplier');
		$this->db->from('tbl_resep_detail as resep');
		$this->db->join('tbl_obat obat', 'resep.fk_obat=obat.idObat');
		$this->db->join('tbl_resep_aturan_pakai aturan', 'resep.aturan_pakai=aturan.id');
		$this->db->join('tbl_resep_cara_pakai cara', 'resep.cara_pakai=cara.id');
		$this->db->join('tbsupplier supplier', 'supplier.idSupplier=obat.supplier', 'LEFT');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function kamar_detail($where)
	{
		$this->db->select('fk_ruangan,namaRuangan,checkin_at,checkout_at,nomor,tarif');
		$this->db->from('tbruangan_transaksi');
		$this->db->join('tbruangan', 'tbruangan_transaksi.fk_ruangan=tbruangan.idRuangan');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function detail_pendaftar($pendaftaran)
	{
		$data = $this->db->query("select tbl_pendaftaran.id_pendaftaran as _id,tbl_pasien.no_identitas,tbl_pasien.identitas,tbl_pendaftaran.id_pendaftaran as id_pend,tbl_pasien.asuransi_utama, tbl_pasien.nomor_rm, tbl_pasien.nama_lengkap, tbl_pendaftaran.create_at,tbl_pendaftaran.no_invoice from tbl_pendaftaran JOIN tbl_pemeriksaan ON tbl_pendaftaran.id_pendaftaran = tbl_pemeriksaan.id_pendaftaran JOIN tbl_pasien ON tbl_pasien.id_pasien = tbl_pendaftaran.fk_pasien where tbl_pendaftaran.id_pendaftaran='" . $pendaftaran . "'")->result_object();
		return $data;
	}
	function detail_tindakan_print($pendaftaran)
	{
		$data = $this->db->query("select tindakan.fk_dokter,tindakan.fk_tindakan, layanan.nama_layanan_poli, dokter.namaDokter,layanan.harga_layanan_poli,periksa.create_at from tbl_pemeriksaan as periksa join tbl_pemeriksaan_tindakan AS tindakan ON periksa.id_pemeriksaan=tindakan.fk_pemeriksaan JOIN tbl_layanan_poli AS layanan ON tindakan.fk_tindakan=layanan.id_layanan_poli JOIN tbdaftardokter AS dokter ON tindakan.fk_dokter=dokter.idDokter JOIN tbl_pendaftaran AS pend ON periksa.id_pendaftaran=pend.id_pendaftaran where pend.id_pendaftaran='" . $pendaftaran . "'")->result();
		return $data;
	}
	function detail_resep_print($pendaftaran)
	{
		$data = $this->db->query("select resep.aturan_pakai, resep.cara_pakai, resep.fk_obat, resep.qty,resep.total, obat.nama, aturan.nama_aturan_pakai,cara.nama_cara_pakai from tbl_resep_detail as resep JOIN tbl_obat as obat ON resep.fk_obat=obat.idObat JOIN tbl_resep_aturan_pakai as aturan ON resep.aturan_pakai=aturan.id JOIN tbl_resep_cara_pakai as cara on resep.cara_pakai=cara.id where resep.fk_pendaftaran='" . $pendaftaran . "'")->result();
		return $data;
	}
	function detail_kamar_print($pendaftaran)
	{
		$data = $this->db->query("select fk_ruangan,namaRuangan,checkin_at,checkout_at,nomor,tarif from tbruangan_transaksi JOIN tbruangan ON tbruangan_transaksi.fk_ruangan=tbruangan.idRuangan where fk_pendaftaran='" . $pendaftaran . "'")->result_object();
		return $data;
	}
}
