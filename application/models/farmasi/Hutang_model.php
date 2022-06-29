<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hutang_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tableHutang      = "tbl_hutang";
		$this->tableBayar      = "tbl_bayar_hutang";
		$this->tableTransaksi   = "tbl_transaksi_beli";
		$this->tableSupplier   = "tbsupplier";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["hutang_id", "faktur", "tunai_kredit", "jatuh_tempo", "namaSupplier", "grandtotal"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_hutang.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " (faktur LIKE '%" . $output->search . "%' OR namaSupplier LIKE '%" . $output->search . "%')";
		}
		if (isset($posted['start_date']) && $posted['start_date'] != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$tgl_col = 'jatuh_tempo';
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
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_hutang join tbl_transaksi_beli on tbl_transaksi_beli.transaksibeli_id=tbl_hutang.transaksibeli_id join tbsupplier on tbsupplier.idSupplier=tbl_transaksi_beli.supplier $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$hutang_id = $dt->hutang_id;
			$total_telah_dibayar = total_dibayar_hutang($hutang_id);
			$sisa = sisa_hutang($hutang_id) <= 0 ? 0 : number_format(sisa_hutang($hutang_id));
			return [
				$dt->hutang_id,
				$dt->faktur,
				'' . $dt->tunai_kredit . ' <a href="#" class="btn btn-xs link-detail-hutang" data-dismiss="modal" data-id="' . $dt->hutang_id . '"><i class="fa fa-info-circle tx-primary"></i></a>',
				$dt->jatuh_tempo,
				$dt->namaSupplier,
				number_format($dt->grandtotal),
				'' . ($total_telah_dibayar >= $dt->grandtotal ? number_format($dt->grandtotal) : number_format($total_telah_dibayar)) . '',
				$sisa,
				'' . ($sisa == 0 ? "<b class='tx-primary'>LUNAS</b>" : "<a href='#' style='width: 70px;' class='btn btn-primary btn-block btn-xs link-bayar-hutang' data-dismiss='modal' data-id='" . $dt->hutang_id . "'>bayar</i></a>") . '',
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}
	function _search($where)
	{
		$this->db->select('hutang_id _id,faktur,namaSupplier,grandtotal');
		$this->db->from($this->tableHutang);
		$this->db->join($this->tableTransaksi, 'tbl_transaksi_beli.transaksibeli_id=tbl_hutang.transaksibeli_id');
		$this->db->join($this->tableSupplier, 'tbsupplier.idSupplier=tbl_transaksi_beli.supplier');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _search_detail_bayar_hutang($id)
	{
		$data = $this->db->query('select format(biaya,0) as biaya,DATE_FORMAT(create_at, "%d %M %Y, %k:%i:%s") as create_at FROM tbl_bayar_hutang WHERE fk_hutang="' . $id . '"')->result_object();
		return $data;
	}

	function _save($data)
	{
		$this->db->insert($this->tableBayar, $data);
		return $this->db->affected_rows();
	}
	function total_hutang_dibayar($id)
	{
		return $total_dibayar['total_dibayar'] = total_dibayar_hutang($id);
	}
	function sisa_hutang($id)
	{
		return $sisa['sisa'] = sisa_hutang($id);
	}
	function _update_status_hutang($id)
	{
		$this->db->query("UPDATE tbl_hutang SET status=1 where hutang_id='$id'");
	}
}
