<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_pasien_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableKategori = "tbl_kategori_pasien";
        $this->load->helper('ctc');
    }

    function _load_dt($posted)
    {
        $orders_cols = ["kategori_pasien_id", "nama_kategori", "no_init", "	panjang_digit", "status"];
        $output = build_filter_table($posted, $orders_cols);
        $sWhere = $output->where;

        if (isset($output->search) && $output->search != "") {
            $sWhere = " WHERE kategori_pasien_id LIKE '%" . $output->search . "%' OR nama_kategori LIKE '%" . $output->search . "%'";
        }
        $sLimit = $output->limit;
        $sGroup = "";
        $sOrder = $output->order;
        $limit = 0;
        $offset = 25;
        $data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbl_kategori_pasien $sWhere $sGroup $sOrder $sLimit")->result_object();
        $found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();

        $map_data = array_map(function ($dt) {
            $biaya_id = $dt->kategori_pasien_id;
            return [
                $dt->kategori_pasien_id,
                $dt->nama_kategori,
                $dt->no_init,
                $dt->panjang_digit,
                $dt->status,

                '<a href="#" class="link-edit-biaya" data-id="' . $dt->kategori_pasien_id . '"><i class="fa fa-edit"></i></a>  &nbsp;
						<a href="#" class="link-delete-biaya" data-id="' . $dt->kategori_pasien_id . '"><i class="fa fa-trash text-danger"></i></a>
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
        $this->db->select('kategori_pasien_id  as _id,nama_kategori,no_init,panjang_digit,status');
        $this->db->from($this->tableKategori);
        $this->db->where($where);

        return $this->db->get()->result();
    }
    function _save($data, $where, $key)
    {
        if (empty($where)) {
            $this->db->select($key)->from($this->tableKategori)->where($key, $data[$key]);
            $check = $this->db->get()->result();
            if (!empty($check)) return 'exist';
            $this->db->insert($this->tableKategori, $data);
            return $this->db->affected_rows();
        } else {
            $this->db->select('kategori_pasien_id ')->from($this->tableKategori);
            $this->db->where($key, $data[$key]);
            $this->db->where("kategori_pasien_id !=", $where['kategori_pasien_id ']);
            $check = $this->db->get()->result();
            if (!empty($check)) return 'exist';
            $this->db->update($this->tableKategori, $data, $where);
            return $this->db->affected_rows();
        }
    }
    function _delete($where)
    {
        $this->db->delete($this->tableKategori, $where);
        return $this->db->affected_rows();
    }
}
