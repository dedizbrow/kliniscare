<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ruangan_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableRuangan = "tbruangan";
		$this->tableKelas = "tbruangan_kelas";
		$this->tableKategori = "tbruangan_kategori";
		$this->load->helper('ctc');
	}
	function _load_dt($posted)
	{
		$orders_cols = ["idRuangan", "namaRuangan", "namaKelas", "namaKategori", "nomor", "nomor_ranjang", "tarif", "status"];
		$output = build_filter_table($posted, $orders_cols, [], "tbruangan.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " namaRuangan LIKE '%" . $output->search . "%'";
		}
		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbruangan join tbruangan_kelas on tbruangan_kelas.idKelas=tbruangan.idKelasruangan join tbruangan_kategori on tbruangan_kategori.idKategori=tbruangan.idKategoriruangan $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$link = "";
			if (isAllowed('c-ruangan^update', true))
				$link .= '<a href="#" class="link-edit-ruangan" data-id="' . $dt->idRuangan . '"><i class="fa fa-edit"></i></a>  &nbsp;';
			if (isAllowed('c-ruangan^delete', true))
				$link .= '<a href="#" class="link-delete-ruangan" data-id="' . $dt->idRuangan . '"><i class="fa fa-trash text-danger"></i></a>';
			return [
				$dt->idRuangan,
				$dt->namaKategori,
				$dt->namaKelas,
				$dt->namaRuangan,
				$dt->nomor,
				$dt->nomor_ranjang,
				number_format($dt->tarif),
				$dt->status,
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
		$this->db->select('idRuangan as _id, namaRuangan, namaKelas, namaKategori,nomor,nomor_ranjang,tarif,status,idKelas,idKategori');
		$this->db->from($this->tableRuangan);
		$this->db->join($this->tableKelas, 'tbruangan_kelas.idKelas=tbruangan.idKelasruangan');
		$this->db->join($this->tableKategori, 'tbruangan_kategori.idKategori=tbruangan.idKategoriruangan');
		$this->db->where($where);
		return $this->db->get()->result();
	}
	function _search_select_kelas_ruangan($key = "", $clinic_id)
	{
		$this->db->select('idKelas as id,namaKelas as text');
		$this->db->from($this->tableKelas);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaKelas', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}

	function _search_select_kategori_ruangan($key = "", $clinic_id)
	{
		$this->db->select('idKategori as id,namaKategori as text');
		$this->db->from($this->tableKategori);
		$this->db->where("clinic_id", $clinic_id);
		if ($key != "") {
			$this->db->like('namaKategori', $key);
		}
		$this->db->limit(30);
		return $this->db->get()->result_array();
	}

	function _save($data, $where, $key, $keys, $no_ranjang, $clinic_id)
	{
		if (empty($where)) {
			$this->db->select($key)->from($this->tableRuangan);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where($keys, $data[$keys]);
			$this->db->where($no_ranjang, $data[$no_ranjang]);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->insert($this->tableRuangan, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->select('idRuangan')->from($this->tableRuangan);
			$this->db->where($key, $data[$key]);
			$this->db->where($clinic_id, $data[$clinic_id]);
			$this->db->where($keys, $data[$keys]);
			$this->db->where($no_ranjang, $data[$no_ranjang]);
			$this->db->where("idRuangan!=", $where['idRuangan']);
			$check = $this->db->get()->result();
			if (!empty($check)) return 'exist';
			$this->db->update($this->tableRuangan, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _delete($where)
	{
		$this->db->delete($this->tableRuangan, $where);
		return $this->db->affected_rows();
	}

	function _save_kelas($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableKelas)->where($key, $data[$key]);
		$this->db->where("clinic_id", $clinic_id);
		$kelas = $this->db->get()->result();
		if (!empty($kelas)) return 'exist';
		$this->db->insert($this->tableKelas, $data);
		return $this->db->affected_rows();
	}
	function _save_kategori($data, $where, $key, $clinic_id)
	{
		$this->db->select($key)->from($this->tableKategori)->where($key, $data[$key]);
		$this->db->where("clinic_id", $clinic_id);
		$kelas = $this->db->get()->result();
		if (!empty($kelas)) return 'exist';
		$this->db->insert($this->tableKategori, $data);
		return $this->db->affected_rows();
	}
	public function ruanganlist($clinic_id)
	{
		$this->db->select(array('namaRuangan', 'namaKelas', 'namaKategori', 'nomor', 'nomor_ranjang', 'tarif', 'status'));
		$this->db->from('tbruangan');
		$this->db->where("tbruangan.clinic_id", $clinic_id);
		$this->db->join($this->tableKelas, 'tbruangan_kelas.idKelas=tbruangan.idKelasruangan');
		$this->db->join($this->tableKategori, 'tbruangan_kategori.idKategori=tbruangan.idKategoriruangan');
		$query = $this->db->get();
		return $query->result();
	}

	function _list_kelas()
	{
		$this->db->select('idKelas,namaKelas')->from($this->tableKelas)->order_by("namaKelas");
		return $this->db->get()->result_object();
	}
	function _list_kategori()
	{
		$this->db->select('idKategori,namaKategori')->from($this->tableKategori)->order_by("namaKategori");
		return $this->db->get()->result_object();
	}
}
