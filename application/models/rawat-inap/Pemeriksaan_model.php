<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemeriksaan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePendaftaran = "tbl_pendaftaran";
		$this->tablePemeriksaan = "tbl_pemeriksaan";
		$this->tableObat = "tbl_obat";
		$this->tableRuangan = "tbruangan";
		$this->tableTransRuangan = "tbruangan_transaksi";
		$this->tableCheckout = "tbruangan_checkout";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["pend.id_pendaftaran", "pend.status_rawat", "pend.create_at", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pasien.no_hp", "pasien.nama_lengkap", "pasien.alamat", "trans.status_checkout"];
		$output = build_filter_table($posted, $orders_cols, [], "pend.clinic_id");
		$sWhere = $output->where;

		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " not status_rawat=0";

		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " nama_lengkap LIKE '%" . $output->search . "%'";
		}
		if (isset($posted['filter_status_cout']) && $posted['filter_status_cout'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " status_checkout='" . htmlentities($posted['filter_status_cout']) . "'";
		}
		if (isset($posted['filter_status_rawat']) && $posted['filter_status_rawat'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " status_rawat='" . htmlentities($posted['filter_status_rawat']) . "'";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = "pend.create_at";
			if (isset($posted['end_date']) && $posted['end_date'] != "") {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND '" . htmlentities($posted['end_date']) . "'";
			} else {
				$sWhere .= "$tgl_col IS NOT NULL AND $tgl_col BETWEEN '" . htmlentities($posted['start_date']) . "' AND CURDATE()";
			}
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS pend.*," . implode(",", $orders_cols) . " FROM tbl_pendaftaran AS pend LEFT JOIN tbl_pasien AS pasien ON pend.fk_pasien = pasien.id_pasien LEFT JOIN tbruangan_transaksi trans ON pend.id_pendaftaran=trans.fk_pendaftaran $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		// 0 rawat jalan, 1 daftar igd,2 rawat inap,3 checkout, 4 telah diperiksa
		$map_data = array_map(function ($dt) {
			$id_pendaftaran = $dt->id_pendaftaran;
			return [
				$dt->id_pendaftaran,
				'' . ($dt->status_rawat == 1  ? "<a href='#' class='btn btn-primary btn-xs link-periksa' data-dismiss='modal' data-id='" . $dt->id_pendaftaran . "'><i class='fa fa-stethoscope'></i></a>" : "<button class='btn btn-primary btn-xs' disabled><i class='fa fa-stethoscope'></i></button>") . '',

				'' . ($dt->status_rawat == 2 ? "<div class='tooltip-inner' style='background-color: cornflowerblue; width: 100px;'>Rawat Inap</div>" : ($dt->status_rawat == 3  ? "<div class='tooltip-inner' style='background-color: cornflowerblue;width: 100px;'>Rawat Inap</div>" :  "<div class='tooltip-inner' style='background-color: peru; width: 100px;'>Rawat Jalan</div>")) . '',

				'' . ($dt->status_rawat == 4  ? "<a href='#' class='btn btn-primary btn-xs link-checkin-ruangan' style='width: 65px;' data-dismiss='modal' data-id='" . $dt->id_pendaftaran . "'>Ruangan</a> <a href='#' class='btn btn-success btn-xs link-resep' style='width: 50px;' data-id='" . $dt->id_pendaftaran . "'>Resep</a> " : ($dt->status_rawat == 1  ? "" : ($dt->status_rawat == 2  ? "<a href='#' class='btn btn-danger btn-xs link-checkout-ruangan' style='width: 120px;' data-dismiss='modal' data-id='" . $dt->id_pendaftaran . "'>Checkout</a>" : "<div class='tooltip-inner' style='background-color: green;width: 120px;'>Telah Checkout</div>"))) . '',

				$dt->no_invoice,
				$dt->nomor_rm,
				$dt->nama_lengkap,
				$dt->alamat,
				$dt->create_at,
				$dt->nama_lengkap_pjw,
				$dt->no_hp_pjw,
				'' . ($dt->status_checkout == 'Meninggal'  ? "Meninggal <a href='" . base_url('rawat-inap/pemeriksaan/print?pendaftaran=' . $id_pendaftaran) . "' target='_blank'><i class='fa fa-print text-danger'></i></a>" : $dt->status_checkout) . '',
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _load_dt_ruangan($posted)
	{
		$orders_cols = ["idRuangan", "namaRuangan", "namaKelas", "namaKategori", "nomor", "nomor_ranjang", "tarif", "status"];
		$output = build_filter_table($posted, $orders_cols, [], "tbruangan.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= "(namaRuangan LIKE '%" . $output->search . "%')";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " status='tersedia'";

		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbruangan join tbruangan_kelas on tbruangan_kelas.idKelas=tbruangan.idKelasruangan join tbruangan_kategori on tbruangan_kategori.idKategori=tbruangan.idKategoriruangan $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$idRuangan = $dt->idRuangan;
			return [
				$dt->namaKategori,
				$dt->namaKelas,
				$dt->namaRuangan,
				$dt->nomor,
				$dt->nomor_ranjang,
				number_format($dt->tarif),
				'' . ($dt->status == 'tersedia'  ? "<a href='#' class='btn btn-primary btn-xs link-tambah-ruangan' data-dismiss='modal' data-id='" . $dt->idRuangan . "'><i class='fa fa-plus'></i></a>" : "<button class='btn btn-primary btn-xs' disabled><i class='fa fa-plus'></i></button>") . ''
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search_pendaftaran($where)
	{
		$this->db->select('pend.id_pendaftaran as id_pendaftaran,pasien.nama_lengkap,pasien.nomor_rm,dokter.namaDokter');
		$this->db->from('tbl_pendaftaran as pend');
		$this->db->join('tbl_pasien as pasien', 'pend.fk_pasien=pasien.id_pasien');
		$this->db->join('tbdaftardokter as dokter', 'pend.dpjp=dokter.idDokter');
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
	function update_poli_pendaftaran($dtpoli, $id_pendaftaran)
	{
		$this->db->query("UPDATE tbl_pendaftaran SET poli='$dtpoli'  where id_pendaftaran='$id_pendaftaran'");
	}
	function _search($where)
	{
		$this->db->select('pendaftaran.id_pendaftaran as id_pendaftaran ,pendaftaran.no_invoice,pendaftaran.create_at,pasien.nomor_rm,pasien.nama_lengkap');
		$this->db->from($this->tablePendaftaran . " pendaftaran");
		$this->db->join('tbl_pasien pasien', 'pendaftaran.fk_pasien=pasien.id_pasien', 'RIGHT');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_checkout($where)
	{
		$this->db->select('pendaftaran.id_pendaftaran as id_pendaftaran ,pendaftaran.no_invoice,pendaftaran.create_at as tgl_daftar,pasien.nomor_rm,pasien.nama_lengkap, checkin.checkin_at as tgl_masuk_ruangan ,ruangan.idRuangan as idRuangan, ruangan.namaRuangan, kelas.namaKelas, ruangan.tarif');
		$this->db->from($this->tablePendaftaran . " pendaftaran");
		$this->db->join('tbl_pasien pasien', 'pendaftaran.fk_pasien=pasien.id_pasien', 'RIGHT');
		$this->db->join('tbruangan_transaksi checkin', 'pendaftaran.id_pendaftaran=checkin.fk_pendaftaran');
		$this->db->join('tbruangan as ruangan', 'checkin.fk_ruangan=ruangan.idRuangan');
		$this->db->join('tbruangan_kelas as kelas', 'ruangan.idKelasruangan=kelas.idKelas');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function tambah_ruangan($where)
	{
		$this->db->select('idRuangan as _idRuangan, namaRuangan,tarif,namaKelas');
		$this->db->from($this->tableRuangan);
		$this->db->join('tbruangan_kelas', 'tbruangan.idKelasruangan=tbruangan_kelas.idKelas');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _save_checkin($data)
	{
		$this->db->trans_start();
		$this->db->insert($this->tableTransRuangan, $data);
		$id_checkin = $this->db->insert_id();
		$this->db->trans_complete();
		return $id_checkin;
	}
	function _save_checkout($status_checkout, $id_pendaftaran)
	{
		$this->db->query("UPDATE tbruangan_transaksi SET checkout_at=CURDATE() , status_checkout='$status_checkout' where fk_pendaftaran='$id_pendaftaran'");
	}
	function _save_status_checkin($id_pendaftaran, $id_checkin)
	{
		$this->db->query("UPDATE tbl_pendaftaran SET status_rawat=2, fk_trans_ruangan='$id_checkin' where id_pendaftaran='$id_pendaftaran'"); //2 rawat inap, 0 rawat jalan, 1 daftar igd,3 checkout, 4 telah diperiksa
	}
	function _update_status_kamar($id_ruangan)
	{
		$this->db->query("UPDATE tbruangan SET status='penuh' where idRuangan='$id_ruangan'");
	}
	function _update_status_kamar_checkout($id_ruangan)
	{
		$this->db->query("UPDATE tbruangan SET status='tersedia' where idRuangan='$id_ruangan'");
	}
	function _save_status_checkout($id_pendaftaran)
	{
		$this->db->query("UPDATE tbl_pendaftaran SET status_rawat=3 where id_pendaftaran='$id_pendaftaran'");
	}
	function update_status_telah_diperiksa($id_pendaftaran)
	{
		$this->db->query("UPDATE tbl_pendaftaran SET status_rawat=4 where id_pendaftaran='$id_pendaftaran'");
	}
	function tampilkan_temp($clinic_id,$id_daftar,$status_flag)
	{
		$query = "SELECT o.nama,o.kode,o.hargaJual,s.namaSatuanobat,td.*,aturan.nama_aturan_pakai,cara.nama_cara_pakai
                FROM tbl_resep_detail as td,tbl_obat as o, tbl_satuan_obat as s,tbl_resep_aturan_pakai as aturan,tbl_resep_cara_pakai as cara,tbl_obat_detail as de
         WHERE td.clinic_id='$clinic_id' AND td.fk_pendaftaran='$id_daftar' AND o.idObat=td.fk_obat and td.satuan=de.obat_detail_id and de.satuan=s.idSatuanobat and aturan.id=td.aturan_pakai and cara.id=td.cara_pakai and td.status=$status_flag";
		$data=$this->db->query($query);
		// echo $this->db->last_query();
		return $data;
	}
	function check_stock_obat($id_obat)
	{
		$this->db->select('idObat,stok')->from($this->tableObat);
		$this->db->where('idObat', $id_obat);
		return $this->db->get()->result_object();
	}
	function insert_temp($data)
	{
		$this->db->insert('tbl_resep_detail', $data);
		return $this->db->affected_rows();
	}
	function hapus_temp($id)
	{
		$this->db->where('resep_detail_id', $id);
		$this->db->delete('tbl_resep_detail');
		return $this->db->affected_rows();
	}
	function ubah_status($id)
	{
		$this->db->query("UPDATE tbl_resep_detail SET fk_pendaftaran='$id' ,status='1' where fk_pendaftaran=0 ");
	}

	function _num_rows($clinic_id)
	{
		$this->db->where("tbl_pendaftaran.clinic_id", $clinic_id);
		$this->db->where('status_rawat', 2);
		return $this->db->get('tbl_pendaftaran')->num_rows();
	}
	function _search_print($id_pendaftaran)
	{
		$data = $this->db->query("
			SELECT pasien.id_pasien,pasien.perusahaan,pasien.pekerjaan,pasien.nomor_rm,pasien.nama_lengkap,pasien.alamat,pasien.tgl_lahir,pendaftaran.no_invoice, pendaftaran.create_at as tgl_masuk,DATEDIFF(trans.checkout_at,trans.checkin_at)+1 as lama_inap,
			dokter.namaDokter,dokter.nip,dokter.position,spesial.namaSpesialisasi,pendaftaran.lama_mc,pendaftaran.create_at as tgl_periksa, trans.create_at as tgl_kematian, kec.nama as nama_kecamatan, kab.nama as nama_kabupaten, prov.nama as nama_provinsi
			FROM 
			tbl_pendaftaran AS pendaftaran
			LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien  
			LEFT JOIN tbdaftardokter as dokter ON pendaftaran.dpjp=dokter.idDokter
			LEFT JOIN tbspesialisasi as spesial ON dokter.spesialisasi=spesial.idSpesialisasi
			LEFT JOIN tbruangan_transaksi AS trans ON pendaftaran.id_pendaftaran = trans.fk_pendaftaran 
			
			LEFT JOIN wilayah_kecamatan AS kec ON pasien.kecamatan=kec.id
			LEFT JOIN wilayah_kabupaten AS kab ON pasien.kabupaten=kab.id
			LEFT JOIN wilayah_provinsi AS prov ON pasien.provinsi=prov.id
			WHERE pendaftaran.id_pendaftaran='" . $id_pendaftaran . "'")->result_object();
		return $data;
	}
}
