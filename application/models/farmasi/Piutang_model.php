<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Piutang_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->tablePiutang      = "tbl_piutang";
		$this->tableBayar      = "tbl_bayar_piutang";
		$this->tableTransaksi   = "tbl_transaksi_jual";
		$this->tableDokter   = "tbdaftardokter";
		$this->load->helper('ctc');
	}

	function _load_dt($posted)
	{
		$orders_cols = ["piutang_id", "faktur", "tunai_kredit", "jatuh_tempo", "namaDokter", "grandtotal"];
		$output = build_filter_table($posted, $orders_cols, [], "tbl_piutang.clinic_id");
		$sWhere = $output->where;
		if (isset($output->search) && $output->search != "") {
			$sWhere .= ($sWhere == "") ? " WHERE " : " AND ";
			$sWhere .= " faktur LIKE '%" . $output->search . "%'";
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
		$data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_piutang join tbl_transaksi_jual on tbl_transaksi_jual.transaksijual_id=tbl_piutang.transaksijual_id left join tbdaftardokter on tbdaftardokter.idDokter=tbl_transaksi_jual.dokter $sWhere $sGroup $sOrder $sLimit")->result_object();
		$found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
		$map_data = array_map(function ($dt) {
			$piutang_id = $dt->piutang_id;
			$total_telah_dibayar = total_dibayar_piutang($piutang_id);
			$sisa = sisa_piutang($piutang_id) <= 0 ? 0 : number_format(sisa_piutang($piutang_id));
			return [
				$dt->piutang_id,
				$dt->faktur,
				'' . $dt->tunai_kredit . ' <a href="#" class="btn btn-xs link-detail-piutang" data-dismiss="modal" data-id="' . $dt->piutang_id . '"><i class="fa fa-info-circle tx-primary"></i></a>',
				$dt->jatuh_tempo,
				$dt->namaDokter,
				number_format($dt->grandtotal),
				'' . ($total_telah_dibayar >= $dt->grandtotal ? number_format($dt->grandtotal) : number_format($total_telah_dibayar)) . '',
				$sisa,
				'' . ($sisa == 0 ? "<b class='tx-primary'>LUNAS</b>" : "<a href='#' style='width: 70px;' class='btn btn-primary btn-block btn-xs link-bayar-piutang' data-dismiss='modal' data-id='" . $dt->piutang_id . "'>bayar</i></a>") . '',
			];
		}, $data);
		$output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
		$output->recordsFiltered = $output->recordsTotal;
		$output->data = $map_data;
		return (array) $output;
	}

	function _search($where)
	{
		$this->db->select('piutang_id _id,faktur,namaDokter,grandtotal');
		$this->db->from($this->tablePiutang);
		$this->db->join($this->tableTransaksi, 'tbl_transaksi_jual.transaksijual_id=tbl_piutang.transaksijual_id');
		$this->db->join($this->tableDokter, 'tbdaftardokter.idDokter=tbl_transaksi_jual.dokter', 'left');
		$this->db->where($where);

		return $this->db->get()->result();
	}
	function _search_detail_bayar_piutang($id)
	{
		$data = $this->db->query('select format(biaya,0) as biaya,DATE_FORMAT(create_at, "%d %M %Y, %k:%i:%s") as create_at FROM tbl_bayar_piutang WHERE fk_piutang="' . $id . '"')->result_object();
		return $data;
	}
	function _save($data)
	{
		$this->db->insert($this->tableBayar, $data);
		return $this->db->affected_rows();
	}
	function total_piutang_dibayar($id)
	{
		return $total_dibayar['total_dibayar'] = total_dibayar_piutang($id);
	}
	function sisa_piutang($id)
	{
		return $sisa['sisa'] = sisa_piutang($id);
	}
	function _update_status_piutang($id)
	{
		$this->db->query("UPDATE tbl_piutang SET status=1 where piutang_id='$id'");
	}
}
