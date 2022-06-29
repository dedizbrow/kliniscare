<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pendaftaran_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tablePendaftaran = "tbl_pendaftaran";
        $this->tablePasien = "tbl_pasien";
        $this->tableAntrian = "tbl_antrian";
        $this->tablePerujuk = "tbperujuk";
        $this->tableAgama = "agama";
        $this->tableAsuransi = "tbasuransi";
        $this->tablePoli = "tbpoli";
        $this->load->helper('ctc');
    }

    function searchcode()
    {
        $this->db->select('RIGHT(tbl_pasien.nomor_rm,4) as kode');
        $this->db->order_by('kode', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('tbl_pasien');
        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }
        $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
        $kodejadi = "RM-" . $kodemax;
        return $kodejadi;
    }
    function _save_pasien($data_pasien)
    {
        $this->db->trans_start();
        $this->db->insert($this->tablePasien, $data_pasien);
        $pasien_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $pasien_id;
    }

    function _save_antrian($data_antrian)
    {
        $this->db->trans_start();
        $this->db->insert($this->tableAntrian, $data_antrian);
        $antrian_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $antrian_id;
    }
    function _save($data_pendaftaran)
    {
        $this->db->trans_start();
        $this->db->insert($this->tablePendaftaran, $data_pendaftaran);
        $user_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $user_id;
    }

    function _search_select_perujuk($key = "")
    {
        $this->db->select('idPerujuk as id,namaPerujuk as text');
        $this->db->from($this->tablePerujuk);
        if ($key != "") {
            $this->db->like('namaPerujuk', $key);
        }
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }
    function _search_select_agama($key = "")
    {
        $this->db->select('id_agama as id,nama_agama as text');
        $this->db->from($this->tableAgama);
        if ($key != "") {
            $this->db->like('nama_agama', $key);
        }
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }
    function _search_select_asuransi($key = "")
    {
        $this->db->select('idAsuransi as id,namaAsuransi as text');
        $this->db->from($this->tableAsuransi);
        if ($key != "") {
            $this->db->like('namaAsuransi', $key);
        }
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }
    function _search_select_poli($key = "")
    {
        $this->db->select('idPoli as id,namaPoli as text');
        $this->db->from($this->tablePoli);
        if ($key != "") {
            $this->db->like('namaPoli', $key);
        }
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }
}
