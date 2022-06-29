<?php

defined('BASEPATH') or exit('No direct script access allowed');

class News_update_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableNews = "tbl_berita_terbaru";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["id", "judul", "keterangan"];
		$output = build_filter_table($posted, $orders_cols);
		$sWhere = $output->where;

		$sLimit = $output->limit;
		$sGroup = "";
		$sOrder = $output->order;
		$limit = 0;
		$offset = 25;
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_berita_terbaru $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

		$map_data = array_map(function ($dt) {
			$id = $dt->id;
			return [
				$dt->id,
				$dt->judul,
				$dt->keterangan,

				'<a href="#" class="link-edit-news" data-id="' . $dt->id . '"><i class="fa fa-edit"></i></a>  &nbsp;
						<a href="#" class="link-delete-news" data-id="' . $dt->id . '"><i class="fa fa-trash text-danger"></i></a>
						'
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}

	function _search($where)
	{
		$this->db->select('id as _id, judul, keterangan');
		$this->db->from($this->tableNews);
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function detail_news()
	{
		$data = $this->db->query("SELECT * from tbl_berita_terbaru")->result();
		return $data;
	}
	function _save($data, $where)
	{
		if (empty($where)) {
			$this->db->insert($this->tableNews, $data);
			return $this->db->affected_rows();
		} else {
			$this->db->update($this->tableNews, $data, $where);
			return $this->db->affected_rows();
		}
	}

	function _delete($where)
	{
		$this->db->delete($this->tableNews, $where);
		return $this->db->affected_rows();
	}
}
