<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bpjs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('pendaftaran', $this->session->userdata('site_lang'));
        // $this->load->model('Pendaftaran_model', 'pendaftaran');
        $this->load->helper('Authentication');
        $this->data = isAuthorized();
    }
    public function index()
    {
        $this->data["web_title"] = lang('app_name_short') . " | Bpjs";
        $this->data["page_title"] = "Bpjs";
        $this->data['js_control'] = "bpjs/index.js";
        $this->data['datatable'] = true;
        $this->data['chartjs'] = false;

        $this->template->load(get_template(), 'bpjs/index', $this->data);
    }

    // public function searchcode()
    // {
    //     $this->data['kodeunik'] = $this->pendaftaran->searchcode();
    //     echo json_encode($this->data['kodeunik']);
    // }
    // public function save__()
    // {
    //     header('Content-Type: application/json');
    //     $method = $this->input->method(true);
    //     if ($method != "POST" && $method != "PUT") sendError(lang('msg_method_post_put_required'), [], 405);
    //     $posted = $this->input->post();
    //     foreach ($posted as $key => $value) {
    //         $$key = htmlentities(trim($value));
    //     }
    //     if ($nomor_rm == "") return sendError("Nomor Rekam Medis wajib diisi");
    //     if ($nama_lengkap == "") return sendError("Nama wajib diisi");
    //     if ($membership == "") return sendError("Membership wajib diisi");
    //     if ($jenis_kunjungan == "") return sendError("Jenis kunjungan wajib diisi");
    //     if ($poli == "") return sendError("Poliklinik wajib diisi");
    //     if ($dpjp == "") return sendError("Dpjp wajib diisi");
    //     if ($penanggung_jawab == "") return sendError("Penanggung jawab wajib diisi");
    //     if (!isset($posted['_id']) || $posted['_id'] == "") {
    //         $data_pasien = array(
    //             "nomor_rm"      => $nomor_rm,
    //             "nama_lengkap"  => $nama_lengkap,
    //             "jenis_kelamin" => $jenis_kelamin,
    //             "tempat_lahir"  => $tempat_lahir,
    //             "tgl_lahir"     => $tgl_lahir,
    //             "umur"          => $umur,
    //             "status_nikah"  => $status_nikah,
    //             "agama"         => $agama,
    //             "gol_darah"     => $gol_darah,
    //             "identitas"     => $identitas,
    //             "no_identitas"  => $no_identitas,
    //             "membership"    => $membership,
    //             "provinsi"      => $provinsi,
    //             "kabupaten"     => $kabupaten,
    //             "kecamatan"     => $kecamatan,
    //             "alamat"        => $alamat,
    //             "no_hp"         => $no_hp,
    //             "no_telp"       => $no_telp,
    //             "pekerjaan"     => $pekerjaan,
    //             "perusahaan"    => $perusahaan,
    //             "ibu_kandung"   => $ibu_kandung,
    //             "asuransi_utama" => $asuransi_utama,
    //             "no_asuransi"   => $no_asuransi,
    //         );
    //         $id_pasien = $this->pendaftaran->_save_pasien($data_pasien);
    //     } else {
    //         $id_pasien = htmlentities(trim($posted['_id']));
    //     }
    //     $data_antrian = array(
    //         "jenis_kunjungan"  => $jenis_kunjungan,
    //         "poli"          => $poli,
    //         "dpjp"          => $dpjp,
    //         "nomor_antrian"    => $nomor_antrian,
    //         "status" => 0,
    //     );
    //     $id_antrian = $this->pendaftaran->_save_antrian($data_antrian);
    //     $data_pendaftaran = array(
    //         "fk_pasien" => $id_pasien,
    //         "fk_antrian" => $id_antrian,
    //         "perujuk"       => $perujuk,
    //         "nama_tenaga_perujuk"   => $nama_tenaga_perujuk,
    //         "alasan_datang" => $alasan_datang,
    //         "keterangan"    => $keterangan,
    //         "nama_lengkap_pjw"      => $nama_lengkap_pjw,
    //         "penanggung_jawab"      => $penanggung_jawab,
    //         "jenis_kelamin_pjw"     => $jenis_kelamin_pjw,
    //         "tempat_lahir_pjw"      => $tempat_lahir_pjw,
    //         "tgl_lahir_pjw"         => $tgl_lahir_pjw,
    //         "gol_darah_pjw"         => $gol_darah_pjw,
    //         "identitas_pjw"         => $identitas_pjw,
    //         "no_identitas_pjw"      => $no_identitas_pjw,
    //         "no_hp_pjw"    => $no_hp_pjw,
    //         "no_telp_pjw"  => $no_telp_pjw,
    //         "alamat_pjw"   => $alamat_pjw,
    //     );
    //     $this->pendaftaran->_save($data_pendaftaran);
    //     echo json_encode(array("message" => "Penambahan berhasil"));
    // }
    // public function select2_perujuk()
    // {
    //     header('Content-Type: application/json');
    //     $gets = $this->input->get();
    //     $key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
    //     $search = $this->pendaftaran->_search_select_perujuk($key);
    //     echo json_encode($search);
    // }
    // public function select2_agama()
    // {
    //     header('Content-Type: application/json');
    //     $gets = $this->input->get();
    //     $key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
    //     $search = $this->pendaftaran->_search_select_agama($key);
    //     echo json_encode($search);
    // }
    // public function select2_asuransi()
    // {
    //     header('Content-Type: application/json');
    //     $gets = $this->input->get();
    //     $key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
    //     $search = $this->pendaftaran->_search_select_asuransi($key);
    //     echo json_encode($search);
    // }
    // public function select2_poli()
    // {
    //     header('Content-Type: application/json');
    //     $gets = $this->input->get();
    //     $key = (isset($gets['search']) && $gets['search'] != '') ? $gets['search'] : "";
    //     $search = $this->pendaftaran->_search_select_poli($key);
    //     echo json_encode($search);
    // }
    // public function get_active_lang()
    // {
    //     header('Content-Type: application/json');
    //     echo json_encode($this->lang->language);
    // }
}
