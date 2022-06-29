<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemasukan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('ctc');
	}
	function _load_pemeriksaan_lab($posted)
	{
		$orders_cols = ["lab.id", "lab.bayar", "lab.tgl_periksa", "lab.nama_pasien", "lab_j.jenis"];
		$output = build_filter_table($posted, $orders_cols, [], "lab.clinic_id");
		$sWhere = $output->where;

		if (isset($posted['bulan']) && $posted['bulan'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			if (isset($posted['tahun']) && $posted['tahun'] != "") {
				$sWhere .= " YEAR(tgl_periksa) = '" . htmlentities($posted['tahun']) . "' and month(tgl_periksa) = '" . htmlentities($posted['bulan']) . "' ";
			}
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= "lab.bayar IS NOT NULL AND lab.bayar <> 0 AND lab.bayar <> '' ";
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM lab_data_pemeriksaan as lab join lab_jenis_pemeriksaan as lab_j ON lab.jenis_pemeriksaan=lab_j.id $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			return [
				$dt->id,
				dateIndo($dt->tgl_periksa),
				$dt->nama_pasien,
				$dt->jenis,
				number_format($dt->bayar),
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function check_tindakan($filter_date, $clinic_id)
	{
		// $query = "SELECT pend.status_bayar, sum(layanan.harga_layanan_poli) as total_tindakan from tbl_pendaftaran as pend join tbl_pemeriksaan as periksa on pend.id_pendaftaran=periksa.id_pendaftaran join tbl_pemeriksaan_tindakan as tindakan on periksa.id_pemeriksaan=tindakan.fk_pemeriksaan join tbl_layanan_poli as layanan on layanan.id_layanan_poli=tindakan.fk_tindakan join tbl_bayar_periksa as bayar on pend.id_pendaftaran=bayar.fk_pendaftaran where pend.clinic_id='$clinic_id' AND pend.status_bayar=1 and bayar.create_at LIKE '$filter_date%'";
		$this->db->select('pend.status_bayar, sum(layanan.harga_layanan_poli) as total_tindakan');
		$this->db->from('tbl_pendaftaran as pend');
		$this->db->join('tbl_pemeriksaan as periksa','pend.id_pendaftaran=periksa.id_pendaftaran');
		$this->db->join('tbl_pemeriksaan_tindakan as tindakan','periksa.id_pemeriksaan=tindakan.fk_pemeriksaan');
		$this->db->join('tbl_layanan_poli as layanan','layanan.id_layanan_poli=tindakan.fk_tindakan');
		$this->db->join('tbl_bayar_periksa as bayar','pend.id_pendaftaran=bayar.fk_pendaftaran');
		$this->db->where('pend.status_bayar',1);
		$this->db->where('pend.clinic_id',$clinic_id);
		$this->db->like('bayar.create_at',$filter_date,'after');
		$data=$this->db->get()->row_array();
		// echo $this->db->last_query();
		// print_r($data);
		// $total_tindakan = $this->db->query($query)->row_array();
		return $data['total_tindakan'];
	}
	function detail_tindakan($filter_date, $clinic_id)
	{
		// $data = $this->db->query("SELECT sum(layanan.harga_layanan_poli) as total_det_tindakan, pasien.nama_lengkap,pend.status_bayar, bayar.create_at,pend.no_invoice from tbl_pendaftaran as pend join tbl_pemeriksaan as periksa on pend.id_pendaftaran=periksa.id_pendaftaran join tbl_pemeriksaan_tindakan as tindakan on periksa.id_pemeriksaan=tindakan.fk_pemeriksaan join tbl_pasien as pasien on pasien.id_pasien=pend.fk_pasien join tbl_layanan_poli as layanan on layanan.id_layanan_poli=tindakan.fk_tindakan join tbl_bayar_periksa as bayar on pend.id_pendaftaran=bayar.fk_pendaftaran where pend.clinic_id='$clinic_id' AND pend.status_bayar=1 and YEAR(bayar.create_at) = '$tahun' and month(bayar.create_at)='$bulan' GROUP BY periksa.id_pemeriksaan ORDER BY pend.id_pendaftaran DESC")->result();
		$this->db->select('sum(layanan.harga_layanan_poli) as total_det_tindakan, pasien.nama_lengkap,pend.status_bayar, bayar.create_at,pend.no_invoice');
		$this->db->from('tbl_pendaftaran as pend');
		$this->db->join('tbl_pemeriksaan as periksa','pend.id_pendaftaran=periksa.id_pendaftaran');
		$this->db->join('tbl_pemeriksaan_tindakan as tindakan','periksa.id_pemeriksaan=tindakan.fk_pemeriksaan');
		$this->db->join('tbl_pasien as pasien','pasien.id_pasien=pend.fk_pasien');
		$this->db->join('tbl_layanan_poli as layanan','layanan.id_layanan_poli=tindakan.fk_tindakan');
		$this->db->join('tbl_bayar_periksa as bayar','pend.id_pendaftaran=bayar.fk_pendaftaran');
		$this->db->where('pend.status_bayar',1);
		$this->db->where('pend.clinic_id',$clinic_id);
		$this->db->like('bayar.create_at',$filter_date,'after');
		$this->db->group_by('periksa.id_pemeriksaan');
		$this->db->order_by('pend.id_pendaftaran','DESC');
		$data=$this->db->get()->result_array();
		// echo $this->db->last_query();
		return $data;
	}
	function check_ruangan($filter_date, $clinic_id)
	{
		$query = "SELECT pend.status_bayar, sum((ruangan.tarif)*(DATEDIFF(checkout_at,checkin_at)+1)) as total_kamar from tbl_pendaftaran as pend join tbruangan_transaksi as trans on pend.id_pendaftaran=trans.fk_pendaftaran join tbruangan as ruangan on ruangan.idRuangan=trans.fk_ruangan join tbl_bayar_periksa as bayar on pend.id_pendaftaran=bayar.fk_pendaftaran where pend.clinic_id='$clinic_id' AND pend.status_bayar=1 and bayar.create_at LIKE '$filter_date%'";
		$total_kamar = $this->db->query($query)->row_array();
		return $total_kamar['total_kamar'];
	}
	function detail_ruangan($filter_date, $clinic_id)
	{
		$data = $this->db->query("SELECT sum((ruangan.tarif)*(DATEDIFF(checkout_at,checkin_at)+1)) as total_det_kamar, pasien.nama_lengkap,pend.status_bayar, bayar.create_at,pend.no_invoice, ruangan.namaRuangan, ruangan.nomor, ruangan.nomor_ranjang from tbl_pendaftaran as pend join tbruangan_transaksi as trans on pend.id_pendaftaran=trans.fk_pendaftaran join tbruangan as ruangan on ruangan.idRuangan=trans.fk_ruangan join tbl_pasien as pasien on pasien.id_pasien=pend.fk_pasien join tbl_bayar_periksa as bayar on pend.id_pendaftaran=bayar.fk_pendaftaran where pend.clinic_id='$clinic_id' AND pend.status_bayar=1 and bayar.create_at LIKE '$filter_date%' GROUP BY pend.id_pendaftaran ORDER BY pend.id_pendaftaran DESC")->result();
		return $data;
	}
	function check_resep($filter_date, $clinic_id)
	{
		$query = "SELECT pend.status_bayar, sum(total) as total_resep from tbl_pendaftaran as pend join tbl_resep_detail as trans on pend.id_pendaftaran=trans.fk_pendaftaran join tbl_obat as obat on obat.idObat=trans.fk_obat join tbl_bayar_periksa as bayar on pend.id_pendaftaran=bayar.fk_pendaftaran where pend.clinic_id='$clinic_id' AND pend.status_bayar=1 and bayar.create_at LIKE '$filter_date%'";
		$total_resep = $this->db->query($query)->row_array();
		return $total_resep['total_resep'];
	}
	function detail_resep($filter_date, $clinic_id)
	{
		$data = $this->db->query("SELECT sum(total) as total_det_resep, pasien.nama_lengkap,pend.status_bayar, bayar.create_at,pend.no_invoice from tbl_pendaftaran as pend join tbl_pemeriksaan as periksa on pend.id_pendaftaran=periksa.id_pendaftaran join tbl_pasien as pasien on pasien.id_pasien=pend.fk_pasien join tbl_resep_detail as trans on pend.id_pendaftaran=trans.fk_pendaftaran join tbl_obat as obat on obat.idObat=trans.fk_obat join tbl_bayar_periksa as bayar on pend.id_pendaftaran=bayar.fk_pendaftaran where pend.clinic_id='$clinic_id' AND pend.status_bayar=1 and bayar.create_at LIKE '$filter_date%' GROUP BY periksa.id_pemeriksaan ORDER BY pend.id_pendaftaran DESC")->result();
		return $data;
	}
	function check_apotek_obat($filter_date, $clinic_id)
	{
		$query = "SELECT jual.tunai_kredit,sum(jual.grandtotal) as total_apotek_obat from tbl_transaksi_jual as jual left join tbl_piutang as piutang on jual.transaksijual_id=piutang.transaksijual_id where jual.clinic_id='$clinic_id' AND  (jual.tunai_kredit='tunai' or (jual.tunai_kredit='kredit' and piutang.status=1)) and jual.create_at LIKE '$filter_date%' ";
		$total_apotek_obat = $this->db->query($query)->row_array();
		return $total_apotek_obat['total_apotek_obat'];
	}
	function detail_apotek_obat($filter_date, $clinic_id)
	{
		$data = $this->db->query("SELECT jual.tanggal,piutang.status,jual.grandtotal as total_det_apotek_obat, jual.faktur from tbl_transaksi_jual as jual left join tbl_piutang as piutang on jual.transaksijual_id=piutang.transaksijual_id where jual.clinic_id='$clinic_id' AND  (jual.tunai_kredit='tunai' or (jual.tunai_kredit='kredit' and piutang.status=1)) and jual.create_at LIKE '$filter_date%' GROUP BY jual.transaksijual_id ORDER BY jual.transaksijual_id DESC")->result();
		return $data;
	}
	function check_pemeriksaan_lab($filter_date, $clinic_id)
	{
		$query = "SELECT sum(lab.bayar) as total_pemeriksaan_lab from lab_data_pemeriksaan as lab where lab.clinic_id='$clinic_id' AND lab.tgl_periksa LIKE '$filter_date%'";
		$total_pemeriksaan_lab = $this->db->query($query)->row_array();
		return $total_pemeriksaan_lab['total_pemeriksaan_lab'];
	}
	function check_pengeluaran($filter_date, $clinic_id)
	{
		$query = "SELECT nama, nama_kategori, sum(trans.total) as pengeluaran from tbl_biaya_farmasi as trans join tbl_kategori_biaya_farmasi as kategori ON trans.kategori_biaya=kategori.kategori_biaya_id where trans.clinic_id='$clinic_id' AND trans.tanggal LIKE '$filter_date%' GROUP BY kategori_biaya";
		$pengeluaran = $this->db->query($query)->result_object();
		return $pengeluaran;
	}
	function check_pengeluaran_apotek_obat($filter_date, $clinic_id)
	{
		$query = "SELECT beli.tunai_kredit,sum(beli.grandtotal) as pengeluaran_apotek_obat from tbl_transaksi_beli as beli left join tbl_hutang as hutang on beli.transaksibeli_id=hutang.transaksibeli_id where beli.clinic_id='$clinic_id' AND (beli.tunai_kredit='tunai' or (beli.tunai_kredit='kredit' and hutang.status=1)) and beli.create_at LIKE '$filter_date%'";
		$pengeluaran_apotek_obat = $this->db->query($query)->row_array();
		return $pengeluaran_apotek_obat['pengeluaran_apotek_obat'];
	}
}
