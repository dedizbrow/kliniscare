<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableSatuanobat = "tbl_satuan_obat";
		$this->tableDokter = "tbdaftardokter";
		$this->tableObat = "tbl_obat";
		$this->tableObatdetail = "tbl_obat_detail";
		$this->tableTransaksidetail = "tbl_transaksi_jual_detail";
		$this->tableTransaksi = "tbl_transaksi_jual";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["transaksijual_id", "faktur", "tanggal", "tunai_kredit", "kredit_hari", "jatuh_tempo", "namaDokter", "subtotal", "diskonsub", "grandtotal", "bayar"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_transaksi_jual.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (faktur LIKE '%" . $output->search . "%' OR tanggal LIKE '%" . $output->search . "%')";
		}
		if (isset($posted['filter_jenis_bayar']) && $posted['filter_jenis_bayar'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " tunai_kredit='" . htmlentities($posted['filter_jenis_bayar']) . "'";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = 'tanggal';
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
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",IFNULL(usr.name,'---') as user FROM tbl_transaksi_jual LEFT JOIN c_users usr ON tbl_transaksi_jual.creator_id=usr.uid left join tbdaftardokter on tbdaftardokter.idDokter=tbl_transaksi_jual.dokter $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$transaksijual_id = $dt->transaksijual_id;
			return [
				$dt->transaksijual_id,
				$dt->faktur,
				$dt->tanggal,
				$dt->tunai_kredit,
				$dt->kredit_hari,
				$dt->jatuh_tempo,
				$dt->namaDokter,
				number_format($dt->grandtotal),
				$dt->user,
				'<a href="' . base_url('farmasi/penjualan/print-faktur?id=' . $dt->transaksijual_id) . '" target="popup" class="nota" data-id="' . $dt->transaksijual_id . '"><i class="fa fa-print text-danger"></i></a>'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	//ambil data untuk daftar obat
	function _load_dt_obat($posted)
	{
		$orders_cols = ["idObat", "kode", "nama", "namaKategoriobat", "namaSatuanbeli", "namaSupplier", "stok", "stokmin", "expired"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_obat.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " nama LIKE '%" . $output->search . "%'";
		}
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " tbl_obat_detail.status = 1";

		$sLimit = $output->limit;
		$sGroup = "GROUP BY idObat";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ", GROUP_CONCAT(format(tbl_obat_detail.harga,0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as harga,
		GROUP_CONCAT(format(tbl_obat_detail.hargabeli,0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as hargabeli,
		GROUP_CONCAT(tbl_satuan_obat.namaSatuanobat ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as namaSatuan 
		FROM tbl_obat 
		left join tbl_obat_detail on tbl_obat_detail.fk_obat=tbl_obat.idObat 
		left join tbl_kategori_obat on tbl_kategori_obat.idKategoriobat=tbl_obat.kategori 
		left join tbl_satuan_beli on tbl_satuan_beli.idSatuanbeli=tbl_obat.satuanbeli 
		left join tbl_satuan_obat on tbl_satuan_obat.idSatuanobat=tbl_obat_detail.satuan 
		left join tbsupplier on tbsupplier.idSupplier=tbl_obat.supplier $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$idObat = $dt->idObat;
			$link = '<a href="#" style="width: 70px;" class="btn btn-primary btn-block btn-xs link-tambah-daftar-obat" data-dismiss="modal" data-id="' . $dt->idObat . '"><i class="fa fa-plus"></i></a>';
			if ((int) $dt->stok == 0) $link = "Stok Kosong";
			return [
				$dt->kode,
				$dt->nama,
				$dt->namaKategoriobat,
				// '' . number_format($dt->hargabeli) . " @" . $dt->namaSatuanbeli . '',
				// '' . number_format($dt->harga) . " @" . $dt->namaSatuanbeli . '',
				$dt->namaSatuan,
				$dt->hargabeli,
				$dt->harga,
				'' . $dt->stok . " @" . $dt->namaSatuanbeli . '',
				$dt->namaSupplier,

				$link
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search_obat($where)
	{
		$this->db->select('idObat as id_obat,kode,nama');
		$this->db->from($this->tableObat);
		$this->db->where($where);

		return $this->db->get()->result_array();
	}
	function check_stock_obat($id_obat)
	{
		$this->db->select('idObat,stok')->from($this->tableObat);
		$this->db->where('idObat', $id_obat);
		return $this->db->get()->result_object();
	}
	//insert temporary ke list obat yg akan dibeli
	function insert_temp($data)
	{
		// status 1 sudah diproses, 0 belum diproses
		$this->db->insert('tbl_transaksi_jual_detail', $data);
		return $this->db->affected_rows();
	}
	function hapus_temp($id)
	{
		$this->db->where('jualdetail_id', $id);
		$this->db->delete('tbl_transaksi_jual_detail');
		return $this->db->affected_rows();
	}

	function simpan_transaksi_jual($data)
	{
		$this->db->insert('tbl_transaksi_jual', $data);
		$insert_id = $this->db->insert_id();
		$this->db->reset_query();
		// update stok obat and detail
		$this->db->query("UPDATE tbl_obat obt INNER JOIN tbl_transaksi_jual_detail dtl ON obt.idObat=dtl.obat SET obt.stok=obt.stok-(dtl.qty*dtl.isi),dtl.transaksijual_id='" . $insert_id . "',dtl.status='1' WHERE dtl.status=0");
		return $insert_id;
	}
	function kredit($id, $clinic_id)
	{
		$this->db->query("INSERT tbl_piutang SET transaksijual_id='$id' , clinic_id='$clinic_id'");
	}
	function tampilkan_temp($clinic_id)
	{
		$query = "SELECT o.kode,o.nama,de.harga,s.namaSatuanobat,td.*
                FROM tbl_transaksi_jual_detail as td,tbl_obat as o, tbl_satuan_obat as s, tbl_obat_detail as de
         WHERE o.idObat=td.obat and td.satuan=de.obat_detail_id and de.satuan=s.idSatuanobat and td.status=0 and td.clinic_id='$clinic_id'";
		return $this->db->query($query);
	}
	function searchcode($clinic_id)
	{
		$this->db->select('RIGHT(tbl_transaksi_jual.faktur,4) as faktur');
		$this->db->order_by('faktur', 'DESC');
		$this->db->limit(1);
		$this->db->where("clinic_id", $clinic_id);
		$query = $this->db->get('tbl_transaksi_jual');
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = intval($data->faktur) + 1;
		} else {
			$kode = 1;
		}
		$kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
		date_default_timezone_set('Asia/Jakarta');
		$kodejadi = "FTRJ-" . date('ym') . $kodemax;
		return $kodejadi;
	}
	function isFtrExist($faktur, $clinic_id)
	{
		$query = $this->db->get_where($this->tableTransaksi, array("faktur" => $faktur, "clinic_id" => $clinic_id));
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function _search_select_dokter($key = "", $clinic_id)
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
	function _search_select_satuan_obat($key = "", $keys, $clinic_id)
	{
		$this->db->select('tbl_obat_detail.obat_detail_id as id,tbl_satuan_obat.namaSatuanobat as text');
		$this->db->from('tbl_obat_detail');
		if ($key != "") {
			$this->db->like('satuan', $key);
		}
		$this->db->join('tbl_satuan_obat', 'tbl_satuan_obat.idSatuanobat=tbl_obat_detail.satuan');
		$this->db->where("clinic_id", $clinic_id);
		$this->db->WHERE('fk_obat', $keys);
		$this->db->WHERE('status', '1');
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _delete($where)
	{
		$this->db->delete($this->tableTransaksi, $where);
		return $this->db->affected_rows();
	}
	public function transaksilist($clinic_id)
	{
		$this->db->select(array('faktur', 'tanggal', 'tunai_kredit', 'kredit_hari', 'jatuh_tempo', 'namaDokter', 'grandtotal'));
		$this->db->from('tbl_transaksi_jual');
		$this->db->join($this->tableDokter . " as dokter", "tbl_transaksi_jual.dokter=dokter.idDokter", 'left');
		$this->db->where("tbl_transaksi_jual.clinic_id", $clinic_id);
		$query = $this->db->get();
		return $query->result();
	}
	function search_nota($ids)
	{
		$this->db->select('transaksijual_id,faktur,tanggal,tunai_kredit,kredit_hari,jatuh_tempo,dokter,subtotal,diskonsub,grandtotal,bayar,
		dokter.namaDokter as nama_dokter');
		$this->db->from($this->tableTransaksi . " as trans");
		$this->db->join($this->tableDokter . " as dokter", "trans.dokter=dokter.idDokter", 'left');
		$this->db->where_in("transaksijual_id", $ids);
		return $this->db->get()->result_object();
	}
	function search_nota_detail($ids)
	{
		$this->db->select('dtl.transaksijual_id,obt.kode,obt.nama as nama_obat,dtl.qty,dtl.diskon,dtl.total,dtl.harga, satuan.namaSatuanobat');
		$this->db->from($this->tableTransaksidetail . " as dtl");
		$this->db->join($this->tableObat . " as obt", "dtl.obat=obt.idObat");
		$this->db->join($this->tableObatdetail  . " as obt-dtl", "dtl.satuan=obt-dtl.obat_detail_id");
		$this->db->join($this->tableSatuanobat  . " as satuan", "obt-dtl.satuan=satuan.idSatuanobat");
		$this->db->where_in("transaksijual_id", $ids);
		$this->db->group_by("dtl.jualdetail_id");
		return $this->db->get()->result_object();
	}

	public function total_amount($clinic_id, $where)
	{
		$total = $this->db->query("SELECT SUM(grandtotal) AS total FROM tbl_transaksi_jual WHERE tbl_transaksi_jual.clinic_id='$clinic_id' AND create_at LIKE '$where%'");
		return $total->result_object();
	}
	function _search_obat_detail($id)
	{
		$data = $this->db->query('select fk_obat, isi, satuan, harga from tbl_obat_detail
		WHERE obat_detail_id=' . $id . '
		')->result();
		return $data;
	}
}
