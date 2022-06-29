<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Jasa_apoteker_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableJasaApoteker = "tbjasa_apoteker";
        $this->tableUser = "c_users";
        $this->load->helper('ctc');
    }
    function _load_dt($posted)
    {
        $orders_cols = ["id", "nama", "tanggal", "nominal", "status", "name"];
        $output = build_filter_table($posted, $orders_cols);
        $sWhere = $output->where;
        // if (isset($output->search) && $output->search != "") {
        //     $sWhere = " WHERE namaDokter LIKE '%" . $output->search . "%' OR namaHari LIKE '%" . $output->search . "%'";
        // }
        $sLimit = $output->limit;
        $sGroup = "";
        $sOrder = $output->order;
        $limit = 0;
        $offset = 25;
        $data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS " . implode(",", $orders_cols) . " FROM tbjasa_apoteker join c_users on c_users.uid=tbjasa_apoteker.user $sWhere $sGroup $sOrder $sLimit")->result_object();
        $found = $this->db->query("SELECT FOUND_ROWS() as total")->result_object();
        $map_data = array_map(function ($dt) {
            $id = $dt->id;
            return [
                $dt->id,
                $dt->nama,
                $dt->nominal,
                $dt->tanggal,
                $dt->status,
                $dt->name,
                '<a href="#" class="link-edit-jasa-apoteker" data-id="' . $dt->id . '"><i class="fa fa-edit"></i></a>  &nbsp;
				<a href="#" class="link-delete-jasa-apoteker" data-id="' . $dt->id . '"><i class="fa fa-trash text-danger"></i></a>'
            ];
        }, $data);
        $output->recordsTotal = (sizeof($found) == 0) ? 0 : (int) $found[0]->total;
        $output->recordsFiltered = $output->recordsTotal;
        $output->data = $map_data;
        return (array) $output;
    }
    function _save($data, $where, $key)
    {
        if (empty($where)) {
            $this->db->select($key)->from($this->tableJasaApoteker)->where($key, $data[$key]);
            $check = $this->db->get()->result();
            if (!empty($check)) return 'exist';
            $this->db->insert($this->tableJasaApoteker, $data);
            return $this->db->affected_rows();
        } else {
            $this->db->select('id')->from($this->tableJasaApoteker);
            $this->db->where($key, $data[$key]);
            $this->db->where("id!=", $where['id']);
            $check = $this->db->get()->result();
            if (!empty($check)) return 'exist';
            $this->db->update($this->tableJasaApoteker, $data, $where);
            return $this->db->affected_rows();
        }
    }
    function _delete($where)
    {
        $this->db->delete($this->tableJasaApoteker, $where);
        return $this->db->affected_rows();
    }
    function _search($where)
    {
        $this->db->select('id as _id,nama,nominal,status');
        $this->db->from($this->tableJasaApoteker);
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
