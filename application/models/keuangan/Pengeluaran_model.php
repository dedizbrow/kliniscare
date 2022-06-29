<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluaran_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePengeluaran = "tbl_biaya_farmasi";
		$this->tableKategori = "tbl_kategori_biaya_farmasi";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["biaya_id", "nama", "tanggal", "nama_kategori", "total", "keterangan"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_biaya_farmasi.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (nama LIKE '%" . $output->search . "%' OR total LIKE '%" . $output->search . "%')";
		}
		if (isset($posted['bulan']) && $posted['bulan'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			if (isset($posted['tahun']) && $posted['tahun'] != "") {
				$sWhere .= " YEAR(tanggal) = '" . htmlentities($posted['tahun']) . "' and month(tanggal) = '" . htmlentities($posted['bulan']) . "' ";
			}
		}

		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_biaya_farmasi join tbl_kategori_biaya_farmasi on tbl_kategori_biaya_farmasi.kategori_biaya_id=tbl_biaya_farmasi.kategori_biaya $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-pengeluaran^update', true))
				$link .= '<a href="#" class="link-edit-biaya" data-id="' . $dt->biaya_id . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-pengeluaran^delete', true))
				$link .= '<a href="#" class="link-delete-biaya" data-id="' . $dt->biaya_id . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->biaya_id,
				$dt->nama,
				$dt->tanggal,
				$dt->nama_kategori,
				format_number($dt->total),
				$dt->keterangan,
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
		$this->db->select('biaya_id as _id,nama,kategori_biaya,total,keterangan,nama_kategori');
		$this->db->from($this->tablePengeluaran);
		$this->db->join($this->tableKategori, 'tbl_kategori_biaya_farmasi.kategori_biaya_id=tbl_biaya_farmasi.kategori_biaya');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _search_select_kategori($key = "", $clinic_id)
	{
		$this->db->select('kategori_biaya_id as id,nama_kategori as text');
		$this->db->from($this->tableKategori);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('nama_kategori', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}
	function _save($data, $where, $key, $key2, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tablePengeluaran)->where($key, $data[$key]);
			$this->db->where($key2, $data[$key2]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tablePengeluaran, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('biaya_id')->from($this->tablePengeluaran);
			$this->db->where($key, $data[$key]);
			$this->db->where($key2, $data[$key2]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where("biaya_id!=", $where['biaya_id']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tablePengeluaran, $data, $where);
			return $this->db->affected_rows();
		}
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
		$this->db->delete($this->tablePengeluaran, $where);
		return $this->db->affected_rows();
	}
	public function pengeluaranlist($clinic_id)
	{
		$this->db->select(array('nama', 'tanggal', 'nama_kategori', 'total', 'keterangan'));
		$this->db->from('tbl_biaya_farmasi');
		$this->db->where("tbl_biaya_farmasi.clinic_id", $clinic_id);
		$this->db->join('tbl_kategori_biaya_farmasi', 'tbl_biaya_farmasi.kategori_biaya=tbl_kategori_biaya_farmasi.kategori_biaya_id', 'left');
		$query = $this->db->get();
		return $query->result();
	}
}
