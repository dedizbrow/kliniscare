<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Antrian_ditunda_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('ctc');
    }

    function _load_dt($posted)
    {
        $orders_cols = ["antrian.id_antrian", "antrian.nomor_antrian", "pasien.asuransi_utama", "pasien.no_asuransi", "pasien.nomor_rm", "pasien.nama_lengkap", "pasien.alamat", "pendaftaran.create_at", "poliklinik.namaPoli", "pendaftaran.no_invoice"];
        $output = build_filter_table($posted, $orders_cols,[],"antrian.clinic_id");
        $sWhere = $output->where;
        if (isset($output->search) && $output->search != "") {
			$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
            $sWhere.= " nama_lengkap LIKE '%" . $output->search . "%'";
        }
		$sWhere.=($sWhere=="") ? " WHERE ": " AND ";
		$sWhere.=" status=1 and DATE(antrian.create_at)=CURDATE()";
        $sLimit = $output->limit;
        $sGroup = "";
        $dateNow = date('Y-m-d');
        $sOrder = $output->order;
        $limit = 0;
        $offset = 25;
        $data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_antrian AS antrian LEFT JOIN tbl_pendaftaran AS pendaftaran ON antrian.id_antrian = pendaftaran.fk_antrian LEFT JOIN tbl_pasien AS pasien ON pendaftaran.fk_pasien = pasien.id_pasien LEFT JOIN tbpoli as poliklinik ON pendaftaran.poli = poliklinik.idPoli $sWhere $sGroup $sOrder $sLimit")->result_object();
        $found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

        $map_data = array_map(function ($dt) {
            $id_antrian = $dt->id_antrian;
            return [
                $dt->id_antrian,
                $dt->nomor_antrian . '<a href="#"  class="btn btn-success btn-xs link-ke_antrian float-right" data-id="' . $dt->id_antrian . '">Kembalikan</a>',
                $dt->no_invoice,
                $dt->create_at,
                $dt->namaPoli,
                $dt->nomor_rm,
                $dt->nama_lengkap,
                $dt->alamat,
            ];
        }, $data);
        $output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
        $output->recordsFiltered = $output->recordsTotal;
        $output->data = $map_data;
        return (array) $output;
    }

    function ubah_status($id)
    {
        $this->db->query("UPDATE tbl_antrian SET status='0' where id_antrian='$id'");
    }
}
