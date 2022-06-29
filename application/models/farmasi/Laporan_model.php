<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableTransaksiBeli = "tbl_transaksi_beli";
        $this->tableTransaksiJual = "tbl_transaksi_jual";
        $this->load->helper('ctc');
    }
    function gettahun()
    {
        $query = $this->db->query("SELECT YEAR(tanggal) AS tahun FROM tbl_transaksi_beli GROUP BY YEAR(tanggal) ORDER BY YEAR(tanggal) ASC");
        return $query->result();
    }

    function filterbytanggal($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbybulan($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and YEAR(tanggal) = '$tahun1' and MONTH(tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbytahun($tahun2)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and YEAR(tanggal) = '$tahun2'  ORDER BY tanggal ASC ");
        return $query->result();
    }
    //pembelian kredit

    function filterbytanggal_beli_kredit($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tunai_kredit='kredit' and tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_beli_kredit($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tunai_kredit='kredit' and YEAR(tanggal) = '$tahun1' and MONTH(tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_beli_kredit($tahun2)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tunai_kredit='kredit' and YEAR(tanggal) = '$tahun2'  ORDER BY tanggal ASC ");
        return $query->result();
    }
    //pembelian Tunai

    function filterbytanggal_beli_tunai($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tunai_kredit='tunai' and tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_beli_tunai($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tunai_kredit='tunai' and YEAR(tanggal) = '$tahun1' and MONTH(tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_beli_tunai($tahun2)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.* from tbl_transaksi_beli as beli,tbsupplier as supplier where supplier.idSupplier=beli.supplier and tunai_kredit='tunai' and YEAR(tanggal) = '$tahun2'  ORDER BY tanggal ASC ");
        return $query->result();
    }
    //penjualan
    function filterbytanggal_penjualan($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_penjualan($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and YEAR(tanggal) = '$tahun1' and MONTH(tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_penjualan($tahun2)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and YEAR(tanggal) = '$tahun2'  ORDER BY tanggal ASC ");
        return $query->result();
    }
    //penjualan kredit
    function filterbytanggal_jual_kredit($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tunai_kredit='kredit' and tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_jual_kredit($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tunai_kredit='kredit' and YEAR(tanggal) = '$tahun1' and MONTH(tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_jual_kredit($tahun2)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tunai_kredit='kredit' and YEAR(tanggal) = '$tahun2'  ORDER BY tanggal ASC ");
        return $query->result();
    }
    //penjualan tunai
    function filterbytanggal_jual_tunai($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tunai_kredit='tunai' and tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_jual_tunai($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tunai_kredit='tunai' and YEAR(tanggal) = '$tahun1' and MONTH(tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_jual_tunai($tahun2)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.* from tbl_transaksi_jual as jual,tbdaftardokter as dokter where dokter.idDokter=jual.dokter and tunai_kredit='tunai' and YEAR(tanggal) = '$tahun2'  ORDER BY tanggal ASC ");
        return $query->result();
    }

    //penjualan Obat Expired
    function filterbytanggal_obat_expired($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT kategoriobat.namaKategoriobat, satuanobat.namaSatuanobat, satuanbeli.namaSatuanbeli, supplier.namaSupplier, obat.* from tbl_obat as obat, tbl_kategori_obat as kategoriobat, tbl_satuan_obat as satuanobat, tbl_satuan_beli as satuanbeli, tbsupplier as supplier where kategoriobat.idKategoriobat=obat.kategori and satuanobat.idSatuanobat=obat.satuanobat and satuanbeli.idSatuanbeli=obat.satuanbeli and supplier.idSupplier=obat.supplier and expired <= now() and create_at BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY create_at ASC ");
        return $query->result();
    }

    function filterbybulan_obat_expired($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT kategoriobat.namaKategoriobat, satuanobat.namaSatuanobat, satuanbeli.namaSatuanbeli, supplier.namaSupplier, obat.* from tbl_obat as obat, tbl_kategori_obat as kategoriobat, tbl_satuan_obat as satuanobat, tbl_satuan_beli as satuanbeli, tbsupplier as supplier where kategoriobat.idKategoriobat=obat.kategori and satuanobat.idSatuanobat=obat.satuanobat and satuanbeli.idSatuanbeli=obat.satuanbeli and supplier.idSupplier=obat.supplier and expired <= now() and YEAR(create_at) = '$tahun1' and MONTH(create_at) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY create_at ASC ");
        return $query->result();
    }

    function filterbytahun_obat_expired($tahun2)
    {
        $query = $this->db->query("SELECT kategoriobat.namaKategoriobat, satuanobat.namaSatuanobat, satuanbeli.namaSatuanbeli, supplier.namaSupplier, obat.* from tbl_obat as obat, tbl_kategori_obat as kategoriobat, tbl_satuan_obat as satuanobat, tbl_satuan_beli as satuanbeli, tbsupplier as supplier where kategoriobat.idKategoriobat=obat.kategori and satuanobat.idSatuanobat=obat.satuanobat and satuanbeli.idSatuanbeli=obat.satuanbeli and supplier.idSupplier=obat.supplier and expired <= now() and YEAR(create_at) = '$tahun2'  ORDER BY create_at ASC ");
        return $query->result();
    }

    //penjualan Obat Masuk //berdasarkan tgl transaksi beli
    function filterbytanggal_obat_masuk($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT obat.kode,obat.nama,tb_beli.faktur,tb_beli.tanggal,satuanbeli.namaSatuanbeli,tb_detail.* FROM tbl_transaksi_beli_detail as tb_detail,tbl_obat as obat, tbl_satuan_beli as satuanbeli,tbl_transaksi_beli as tb_beli WHERE obat.idObat=tb_detail.obat and satuanbeli.idSatuanbeli=obat.satuanbeli and tb_beli.transaksibeli_id=tb_detail.transaksibeli_id and tb_beli.tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tb_beli.tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_obat_masuk($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT obat.kode,obat.nama,tb_beli.faktur,tb_beli.tanggal,satuanbeli.namaSatuanbeli,tb_detail.* FROM tbl_transaksi_beli_detail as tb_detail,tbl_obat as obat, tbl_satuan_beli as satuanbeli,tbl_transaksi_beli as tb_beli WHERE obat.idObat=tb_detail.obat and satuanbeli.idSatuanbeli=obat.satuanbeli and tb_beli.transaksibeli_id=tb_detail.transaksibeli_id and YEAR(tb_beli.tanggal) = '$tahun1' and MONTH(tb_beli.tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tb_beli.tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_obat_masuk($tahun2)
    {
        $query = $this->db->query("SELECT obat.kode,obat.nama,tb_beli.faktur,tb_beli.tanggal,satuanbeli.namaSatuanbeli,tb_detail.* FROM tbl_transaksi_beli_detail as tb_detail,tbl_obat as obat, tbl_satuan_beli as satuanbeli,tbl_transaksi_beli as tb_beli WHERE obat.idObat=tb_detail.obat and satuanbeli.idSatuanbeli=obat.satuanbeli and tb_beli.transaksibeli_id=tb_detail.transaksibeli_id and YEAR(tb_beli.tanggal) = '$tahun2'  ORDER BY tb_beli.tanggal ASC ");
        return $query->result();
    }

    //penjualan Obat Keluar //berdasarkan tgl transaksi jual
    function filterbytanggal_obat_keluar($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT obat.kode,obat.nama,tb_jual.faktur,tb_jual.tanggal,satuanbeli.namaSatuanbeli,tb_detail.* FROM tbl_transaksi_jual_detail as tb_detail,tbl_obat as obat, tbl_satuan_beli as satuanbeli,tbl_transaksi_jual as tb_jual WHERE obat.idObat=tb_detail.obat and satuanbeli.idSatuanbeli=obat.satuanbeli and tb_jual.transaksijual_id=tb_detail.transaksijual_id and tb_jual.tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY tb_jual.tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_obat_keluar($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT obat.kode,obat.nama,tb_jual.faktur,tb_jual.tanggal,satuanbeli.namaSatuanbeli,tb_detail.* FROM tbl_transaksi_jual_detail as tb_detail,tbl_obat as obat, tbl_satuan_beli as satuanbeli,tbl_transaksi_jual as tb_jual WHERE obat.idObat=tb_detail.obat and satuanbeli.idSatuanbeli=obat.satuanbeli and tb_jual.transaksijual_id=tb_detail.transaksijual_id and YEAR(tb_jual.tanggal) = '$tahun1' and MONTH(tb_jual.tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY tb_jual.tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_obat_keluar($tahun2)
    {
        $query = $this->db->query("SELECT obat.kode,obat.nama,tb_jual.faktur,tb_jual.tanggal,satuanbeli.namaSatuanbeli,tb_detail.* FROM tbl_transaksi_jual_detail as tb_detail,tbl_obat as obat, tbl_satuan_beli as satuanbeli,tbl_transaksi_jual as tb_jual WHERE obat.idObat=tb_detail.obat and satuanbeli.idSatuanbeli=obat.satuanbeli and tb_jual.transaksijual_id=tb_detail.transaksijual_id and YEAR(tb_jual.tanggal) = '$tahun2'  ORDER BY tb_jual.tanggal ASC ");
        return $query->result();
    }

    //penjualan Obat Stok Habis 
    function filterbytanggal_obat_stok($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT kategoriobat.namaKategoriobat, satuanobat.namaSatuanobat, satuanbeli.namaSatuanbeli, supplier.namaSupplier, obat.* from tbl_obat as obat, tbl_kategori_obat as kategoriobat, tbl_satuan_obat as satuanobat, tbl_satuan_beli as satuanbeli, tbsupplier as supplier where kategoriobat.idKategoriobat=obat.kategori and satuanobat.idSatuanobat=obat.satuanobat and satuanbeli.idSatuanbeli=obat.satuanbeli and supplier.idSupplier=obat.supplier and stok <= 0 and create_at BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY create_at ASC ");
        return $query->result();
    }

    function filterbybulan_obat_stok($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT kategoriobat.namaKategoriobat, satuanobat.namaSatuanobat, satuanbeli.namaSatuanbeli, supplier.namaSupplier, obat.* from tbl_obat as obat, tbl_kategori_obat as kategoriobat, tbl_satuan_obat as satuanobat, tbl_satuan_beli as satuanbeli, tbsupplier as supplier where kategoriobat.idKategoriobat=obat.kategori and satuanobat.idSatuanobat=obat.satuanobat and satuanbeli.idSatuanbeli=obat.satuanbeli and supplier.idSupplier=obat.supplier and stok <= 0 and YEAR(create_at) = '$tahun1' and MONTH(create_at) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY create_at ASC ");
        return $query->result();
    }

    function filterbytahun_obat_stok($tahun2)
    {
        $query = $this->db->query("SELECT kategoriobat.namaKategoriobat, satuanobat.namaSatuanobat, satuanbeli.namaSatuanbeli, supplier.namaSupplier, obat.* from tbl_obat as obat, tbl_kategori_obat as kategoriobat, tbl_satuan_obat as satuanobat, tbl_satuan_beli as satuanbeli, tbsupplier as supplier where kategoriobat.idKategoriobat=obat.kategori and satuanobat.idSatuanobat=obat.satuanobat and satuanbeli.idSatuanbeli=obat.satuanbeli and supplier.idSupplier=obat.supplier and stok <= 0 and YEAR(create_at) = '$tahun2'  ORDER BY create_at ASC ");
        return $query->result();
    }

    //Hutang

    function filterbytanggal_hutang($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.faktur, beli.tanggal,beli.jatuh_tempo, beli.grandtotal,hutang.* from tbl_hutang as hutang, tbl_transaksi_beli as beli,tbsupplier as supplier  where supplier.idSupplier=beli.supplier and hutang.transaksibeli_id=beli.transaksibeli_id and beli.tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY beli.tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_hutang($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.faktur, beli.tanggal,beli.jatuh_tempo, beli.grandtotal,hutang.* from tbl_hutang as hutang, tbl_transaksi_beli as beli,tbsupplier as supplier  where supplier.idSupplier=beli.supplier and hutang.transaksibeli_id=beli.transaksibeli_id and YEAR(beli.tanggal) = '$tahun1' and MONTH(beli.tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY beli.tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_hutang($tahun2)
    {
        $query = $this->db->query("SELECT supplier.namaSupplier, beli.faktur, beli.tanggal,beli.jatuh_tempo, beli.grandtotal,hutang.* from tbl_hutang as hutang, tbl_transaksi_beli as beli,tbsupplier as supplier  where supplier.idSupplier=beli.supplier and hutang.transaksibeli_id=beli.transaksibeli_id and YEAR(beli.tanggal) = '$tahun2'  ORDER BY beli.tanggal ASC ");
        return $query->result();
    }

    //Piutang

    function filterbytanggal_piutang($tanggalawal, $tanggalakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.faktur, jual.tanggal,jual.jatuh_tempo, jual.grandtotal,piutang.* from tbl_piutang as piutang, tbl_transaksi_jual as jual,tbdaftardokter as dokter  where dokter.idDokter=jual.dokter and piutang.transaksijual_id=jual.transaksijual_id and jual.tanggal BETWEEN '$tanggalawal' and '$tanggalakhir' ORDER BY jual.tanggal ASC ");
        return $query->result();
    }

    function filterbybulan_piutang($tahun1, $bulanawal, $bulanakhir)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.faktur, jual.tanggal,jual.jatuh_tempo, jual.grandtotal,piutang.* from tbl_piutang as piutang, tbl_transaksi_jual as jual,tbdaftardokter as dokter  where dokter.idDokter=jual.dokter and piutang.transaksijual_id=jual.transaksijual_id and YEAR(jual.tanggal) = '$tahun1' and MONTH(jual.tanggal) BETWEEN '$bulanawal' and '$bulanakhir' ORDER BY jual.tanggal ASC ");
        return $query->result();
    }

    function filterbytahun_piutang($tahun2)
    {
        $query = $this->db->query("SELECT dokter.namaDokter, jual.faktur, jual.tanggal,jual.jatuh_tempo, jual.grandtotal,piutang.* from tbl_piutang as piutang, tbl_transaksi_jual as jual,tbdaftardokter as dokter  where dokter.idDokter=jual.dokter and piutang.transaksijual_id=jual.transaksijual_id and YEAR(jual.tanggal) = '$tahun2'  ORDER BY jual.tanggal ASC ");
        return $query->result();
    }
}
