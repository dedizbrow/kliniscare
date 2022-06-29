<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Obat_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableObat = "tbl_obat";
		$this->tableObatdetail = "tbl_obat_detail";
		$this->tableSatuanbeli = "tbl_satuan_beli";
		$this->tableSatuanobat = "tbl_satuan_obat";
		$this->tableKategori = "tbl_kategori_obat";
		$this->tableSupplier = "tbsupplier";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["idObat", "kode", "nama", "namaKategoriobat", "namaSatuanbeli",  "namaSupplier", "stok", "stokmin", "expired"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_obat.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " nama LIKE '%" . $output->search . "%'";
		}

		// $sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		// $sWhere .= " tbl_obat_detail.status = 1";

		if (isset($posted['filter_supplier']) && $posted['filter_supplier'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " supplier='" . htmlentities($posted['filter_supplier']) . "'";
		}
		if (isset($posted['filter_kategori']) && $posted['filter_kategori'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " kategori='" . htmlentities($posted['filter_kategori']) . "'";
		}
		$sLimit = $output->limit;
		$sGroup = "GROUP BY idObat";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . ",IFNULL(usr.name,'---') as user ,
		GROUP_CONCAT(format(IFNULL(tbl_obat_detail.harga,0),0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as harga,
		GROUP_CONCAT(format(IFNULL(tbl_obat_detail.hargabeli,0),0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as hargabeli,
		GROUP_CONCAT(IFNULL(tbl_satuan_obat.namaSatuanobat,'') ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as namaSatuan,
		GROUP_CONCAT(IFNULL(tbl_obat_detail.isi,'') ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR '<br>') as isi
		FROM tbl_obat 
		LEFT JOIN c_users usr ON tbl_obat.creator_id=usr.uid 
		left join tbl_kategori_obat on tbl_kategori_obat.idKategoriobat=tbl_obat.kategori 
		left join tbl_obat_detail on tbl_obat_detail.fk_obat=tbl_obat.idObat 
		left join tbl_satuan_obat on tbl_satuan_obat.idSatuanobat=tbl_obat_detail.satuan 
		left join tbl_satuan_beli on tbl_satuan_beli.idSatuanbeli=tbl_obat.satuanbeli
		left join tbsupplier on tbsupplier.idSupplier=tbl_obat.supplier $sWhere $sGroup $sOrder $sLimit")->result_object();
		// echo $this->db->last_query();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-obat^update', true))
				$link .= '<a href="#" class="link-edit-obat" data-id="' . $dt->idObat . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-obat^delete', true))
				$link .= '<a href="#" class="link-delete-obat" data-id="' . $dt->idObat . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idObat,
				$dt->kode,
				$dt->nama,
				$dt->namaKategoriobat,
				// '' . number_format($dt->hargabeli) . " @" . $dt->namaSatuanbeli . '',
				// '' . number_format($dt->harga) . " @" . $dt->namaSatuanbeli . '',
				$dt->namaSatuan,
				$dt->isi,
				$dt->hargabeli,
				$dt->harga,
				'' . $dt->stok . " @" . $dt->namaSatuanbeli . '',
				$dt->namaSupplier,
				$dt->expired,
				$dt->user,
				$link
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}

	function _search($where)
	{
		$this->db->select('idObat as _id,kode, nama,namaKategoriobat,namaSatuanbeli,hargaBeli, namaSupplier, stok, stokmin, expired,kategori,satuanbeli,supplier');
		$this->db->from($this->tableObat);
		$this->db->join($this->tableKategori, 'tbl_kategori_obat.idKategoriobat=tbl_obat.kategori', 'left');
		$this->db->join($this->tableSatuanbeli, 'tbl_satuan_beli.idSatuanbeli=tbl_obat.satuanbeli', 'left');
		$this->db->join($this->tableSupplier, 'tbsupplier.idSupplier=tbl_obat.supplier', 'left');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function searchcode($clinic_id)
	{
		$this->db->select('RIGHT(tbl_obat.kode,4) as kode');
		$this->db->order_by('kode', 'DESC');
		$this->db->limit(1);
		$this->db->where("clinic_id", $clinic_id);
		$query = $this->db->get('tbl_obat');
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = intval($data->kode) + 1;
		} else {
			$kode = 1;
		}
		$kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
		$kodejadi = "OBT-" . $kodemax;
		return $kodejadi;
	}
	function isKodeExist($kode, $clinic_id)
	{
		$query = $this->db->get_where($this->tableObat, array("kode" => $kode, "clinic_id" => $clinic_id));
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function barcode_search($where)
	{
		$this->db->select('idObat as _id,kode, nama,hargaBeli, hargaJual,supplier');
		$this->db->from($this->tableObat);
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select_satuan_beli($key = "", $clinic_id)
	{
		$this->db->select('idSatuanbeli as id,namaSatuanbeli as text');
		$this->db->from($this->tableSatuanbeli);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaSatuanbeli', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_satuan_obat($key = "", $clinic_id)
	{
		$this->db->select('idSatuanobat as id,namaSatuanobat as text');
		$this->db->from($this->tableSatuanobat);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaSatuanobat', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_kategori($key = "", $clinic_id)
	{
		$this->db->select('idKategoriobat as id,namaKategoriobat as text');
		$this->db->from($this->tableKategori);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaKategoriobat', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _search_select_supplier($key = "", $clinic_id)
	{
		$this->db->select('idSupplier as id,namaSupplier as text');
		$this->db->from($this->tableSupplier);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaSupplier', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data, $where, $key, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableObat)->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableObat, $data);
			$id_obat = $this->db->insert_id();
			return $id_obat;
		} else {
			$this->db->select('idObat')->from($this->tableObat);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("idObat!=", $where['idObat']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableObat, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _savesatuan_beli($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableSatuanbeli)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$satuan = $this->db->get()->result();
		if (!empty($satuan)) return 'exist';
		$this->db->insert($this->tableSatuanbeli, $data);
		return $this->db->affected_rows();
	}

	function _savesatuan_obat($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableSatuanobat)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$satuan = $this->db->get()->result();
		if (!empty($satuan)) return 'exist';
		$this->db->insert($this->tableSatuanobat, $data);
		return $this->db->affected_rows();
	}
	function _savekategori($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableKategori)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$checkcategory = $this->db->get()->result();
		if (!empty($checkcategory)) return 'exist';
		$this->db->insert($this->tableKategori, $data);
		return $this->db->affected_rows();
	}

	function _delete($where)
	{
		$this->db->delete($this->tableObat, $where);
		return $this->db->affected_rows();
	}
	function _delete_detail($where)
	{
		$this->db->delete($this->tableObatdetail, $where);
		return $this->db->affected_rows();
	}

	public function obatlist($clinic_id)
	{
		$this->db->select(array('kode', 'nama', 'namaSatuanbeli', 'kategori', 'namaKategoriobat', 'namaSupplier', 'satuanbeli',  'supplier', 'stok', 'stokmin', 'expired', 'satuan', 'GROUP_CONCAT(format(tbl_obat_detail.harga,0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR "-") as harga', 'GROUP_CONCAT(tbl_obat_detail.isi ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR "-") as isi', 'GROUP_CONCAT(tbl_satuan_obat.namaSatuanobat ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR "-") as namaSatuan', 'GROUP_CONCAT(format(tbl_obat_detail.hargabeli,0) ORDER BY tbl_obat_detail.obat_detail_id ASC SEPARATOR "-") as hargabeli'));
		$this->db->from('tbl_obat');
		$this->db->join("tbl_kategori_obat", "tbl_kategori_obat.idKategoriobat=tbl_obat.kategori", "left");
		$this->db->join("tbl_obat_detail", "tbl_obat.idObat=tbl_obat_detail.fk_obat", 'left');
		$this->db->join("tbl_satuan_obat", "tbl_satuan_obat.idSatuanobat=tbl_obat_detail.satuan ", 'left');
		$this->db->join("tbl_satuan_beli", "tbl_satuan_beli.idSatuanbeli=tbl_obat.satuanbeli", "left");
		$this->db->join("tbsupplier", "tbsupplier.idSupplier=tbl_obat.supplier");

		$this->db->where("tbl_obat.clinic_id", $clinic_id);
		$this->db->group_by("tbl_obat.idObat");
		$query = $this->db->get();
		return $query->result();
	}

	public function insert_($data)
	{
		$this->db->insert_batch('tbl_obat', $data);
		return $this->db->affected_rows();
	}

	function _num_rows($clinic_id)
	{
		return $this->db->get_where($this->tableObat, array('tbl_obat.clinic_id' => $clinic_id))->num_rows();
	}
	function _num_rows_trans_($clinic_id)
	{
		$query_resep = "SELECT sum(resep.qty*resep.isi) as resep from tbl_resep_detail as resep WHERE resep.clinic_id='$clinic_id' AND (status=2 or status=1)";
		$query_apotek = "SELECT sum(apotek.qty*apotek.isi) as apotek from tbl_transaksi_jual_detail as apotek WHERE apotek.clinic_id='$clinic_id' AND status=1";
		$resep = $this->db->query($query_resep)->row_array();
		$apotek = $this->db->query($query_apotek)->row_array();
		$nums_row['num_trans_obat'] = $resep['resep'] + $apotek['apotek'];
		return $nums_row['num_trans_obat'];
	}
	function _save_detail_obat($data)
	{
		$this->db->insert_batch('tbl_obat_detail', $data);
	}
	function _update_detail_obat($id)
	{
		$this->db->query("UPDATE tbl_obat_detail SET tbl_obat_detail.status=0 WHERE tbl_obat_detail.fk_obat='$id' and tbl_obat_detail.status=1");
	}
	function _load_dt_obat_stok_min($posted)
	{
		$orders_cols = ["idObat", "kode", "nama", "namaKategoriobat", "namaSupplier", "stok", "stokmin", "expired"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_obat.clinic_id");
		$sWhere = $output->where;
		$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
		$sWhere .= " stok<=stokmin ";
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_obat
		left join tbl_kategori_obat on tbl_kategori_obat.idKategoriobat=tbl_obat.kategori
		left join tbsupplier on tbsupplier.idSupplier=tbl_obat.supplier $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$idObat = $dt->idObat;
			return [
				$dt->kode,
				$dt->nama,
				// $dt->namaKategoriobat,
				$dt->stok,
				$dt->stokmin,
				$dt->namaSupplier,
				// $dt->expired,
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	/* FOR SUPPORT IMPORT DATA OBAT */
	function _list_supplier($clinic_id){
		$this->db->select('idSupplier as id,namaSupplier as nama');
		$this->db->from($this->tableSupplier);
		$this->db->where('clinic_id',$clinic_id);
		return $this->db->get()->result_object();
	}
	function _list_kategori_obat($clinic_id){
		$this->db->select('idKategoriobat as id,namaKategoriobat as nama');
		$this->db->from($this->tableKategori);
		$this->db->where('clinic_id',$clinic_id);
		return $this->db->get()->result_object();
	}
	function _list_satuan_obat($clinic_id){
		$this->db->select('idSatuanobat as id,namaSatuanobat as nama');
		$this->db->from($this->tableSatuanobat);
		$this->db->where('clinic_id',$clinic_id);
		return $this->db->get()->result_object();
	}
	function _list_satuan_beli($clinic_id){
		$this->db->select('idSatuanbeli as id,namaSatuanbeli as nama');
		$this->db->from($this->tableSatuanbeli);
		$this->db->where('clinic_id',$clinic_id);
		return $this->db->get()->result_object();
	}
	function _save_satuan_beli($value,$clinic_id){
		$this->db->insert($this->tableSatuanbeli,array("namaSatuanbeli"=>$value,"clinic_id"=>$clinic_id));
		return $this->db->insert_id();
	}
	function _save_kategori_obat($value,$clinic_id){
		$this->db->insert($this->tableKategori,array("namaKategoriobat"=>$value,"clinic_id"=>$clinic_id));
		return $this->db->insert_id();
	}
	function _save_import($data, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableObat)->where($key, $data[$key]);
		$this->db->where($clinic_id, $data[$clinic_id]);
		$check = $this->db->get()->result();
		if (!empty($check)) return 'exist';
		$this->db->insert($this->tableObat, $data);
		return $this->db->affected_rows();
	}
	/* END */
}
