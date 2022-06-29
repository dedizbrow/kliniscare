<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('farmasi/laporan', $this->session->userdata('site_lang'));
        $this->load->model('farmasi/Laporan_model', 'laporan');
        $this->load->helper('Authentication');
        $this->data = isAuthorized();
    }
    public function index()
    {
        $this->data["web_title"] = lang('app_name_short') . " | Laporan Farmasi";
        $this->data["page_title"] = "Laporan Farmasi";
        $this->data['js_control'] = "farmasi/laporan/index.js";
        $this->data['datatable'] = true;
        $this->data['chartjs'] = false;

        $this->data['tahun'] = $this->laporan->gettahun();

        $this->template->load(get_template(), 'farmasi/laporan/index', $this->data);
    }
    public function print_laporan()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        // $mpdf = new \Mpdf\Mpdf();
        $page_title = "Laporan Pembelian ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Pembelian";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal($tanggalawal, $tanggalakhir);



            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $file_name = 'yourFileName.pdf';
            $mpdf->Output($file_name, 'I');
        }
    }

    public function print_laporan_beli_kredit()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Pembelian Kredit ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Pembelian Kredit";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_beli_kredit($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_beli_kredit($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_beli_kredit($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function print_laporan_beli_tunai()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Pembelian Tunai ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Pembelian Tunai";

        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_beli_tunai($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_beli_tunai($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_beli_tunai($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_pembelian', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }
    public function print_laporan_penjualan()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Penjualan ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Penjualan";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_penjualan($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_penjualan($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_penjualan($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function print_laporan_jual_kredit()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Penjualan Kredit ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;


        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Penjualan Kredit";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_jual_kredit($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_jual_kredit($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_jual_kredit($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }
    public function print_laporan_jual_tunai()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Penjualan Tunai ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Penjualan Tunai";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_jual_tunai($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_jual_tunai($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_jual_tunai($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_penjualan', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }
    public function print_laporan_obat_expired()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Obat Expired ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Obat Expired";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_obat_expired($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_obat', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_obat_expired($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_obat', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_obat_expired($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_obat', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }
    public function print_laporan_obat_masuk()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Obat Masuk ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Obat Masuk";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_obat_masuk($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_obat_masuk', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_obat_masuk($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_obat_masuk', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_obat_masuk($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_obat_masuk', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function print_laporan_obat_keluar()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Obat Keluar ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Obat Keluar";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_obat_keluar($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_obat_keluar', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_obat_keluar($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_obat_keluar', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_obat_keluar($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_obat_keluar', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function print_laporan_obat_stok()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Stok Obat Habis ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Stok Obat Habis";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_obat_stok($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_obat', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_obat_stok($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_obat', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_obat_stok($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_obat', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }

    public function print_laporan_hutang()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Hutang ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Hutang";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_hutang($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_hutang', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_hutang($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_hutang', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_hutang($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_hutang', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }
    public function print_laporan_piutang()
    {
        require_once __DIR__ . '../../../../vendor/autoload.php';
        $page_title = "Laporan Piutang ";
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_footer' => 0, 'setAutoBottomMargin' => 'stretch']);

        $this->data['page_title'] = $page_title;
        $mpdf->SetTitle($page_title);
        $mpdf->SetSubject($page_title);
        $mpdf->shrink_tables_to_fit = 1;
        $margin_left = 0;
        $margin_right = 0;
        $margin_top = 10;
        $margin_bottom = 0;
        $margin_header_top = 0;
        $margin_footer_bottom = 0;
        $mpdf->addPage('P', '', '', '', '', $margin_left, $margin_right, $margin_top, $margin_header_top, $margin_footer_bottom);

        $mpdf->SetHTMLHeader('<div class="page-header-pdf"><img src="' . base_url('/assets/img/ym-doc-header.png') . '"></div>', '', true);
        $mpdf->SetHTMLFooter('<div class="page-header-pdfs"><img src="' . base_url('/assets/img/ym-doc-footer.png') . '"></div>', '', false);
        $mpdf->defaultfooterline = 30;

        $tanggalawal = $this->input->post('tanggalawal');
        $tanggalakhir = $this->input->post('tanggalakhir');
        $tahun1 = $this->input->post('tahun1');
        $bulanawal = $this->input->post('bulanawal');
        $bulanakhir = $this->input->post('bulanakhir');
        $tahun2 = $this->input->post('tahun2');
        $nilaifilter = $this->input->post('nilaifilter');

        $this->data['jenis_laporan'] = "Laporan Piutang";
        if ($nilaifilter == 1) {

            $this->data['periode'] = "Dari tanggal " . $tanggalawal . ' Sampai tanggal ' . $tanggalakhir;

            $where = array(
                'tanggal >=' => $tanggalawal,
                'tanggal <=' => $tanggalakhir,
            );
            $this->data['datafilter'] = $this->laporan->filterbytanggal_Piutang($tanggalawal, $tanggalakhir);

            $html = $this->load->view('farmasi/laporan/lap_piutang', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 2) {

            $this->data['periode'] = "Dari bulan " . $bulanawal . ' Sampai bulan ' . $bulanakhir . ' Tahun ' . $tahun1;


            $this->data['datafilter'] = $this->laporan->filterbybulan_piutang($tahun1, $bulanawal, $bulanakhir);
            $html = $this->load->view('farmasi/laporan/lap_piutang', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } elseif ($nilaifilter == 3) {

            $this->data['periode'] = ' Tahun ' . $tahun2;

            $this->data['datafilter'] = $this->laporan->filterbytahun_piutang($tahun2);
            $html = $this->load->view('farmasi/laporan/lap_piutang', $this->data, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }
    }
    public function get_active_lang()
    {
        header('Content-Type: application/json');
        echo json_encode($this->lang->language);
    }
}
