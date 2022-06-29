<div class="row">
    <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card card-dashboard-one">

            <div class="card-header border c-header">
                <div class="card-title">
                    Laporan Farmasi
                </div>
            </div>
            <div class="card-body">
                <div class="row">


                    <div class="col-lg-5" id="formfilter">

                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter
                            </div>
                            <!--id formfilter adalah nama form untuk filter-->
                            <div class="card-body card-block">

                                <input name="jenis_laporan" type="hidden">
                                <div class="row form-group">
                                    <div id="form-tanggal" class="col col-md-2"><label class=" form-control-label">Pilih Laporan</label></div>

                                    <div class="d-grid gap-2 d-md-flex col col-md-3">
                                        <select name="select1" id="select1" class="form-control form-control-user">
                                            <option value="1">Pembelian</option>
                                            <option value="2">Penjualan</option>
                                            <option value="3">Hutang</option>
                                            <option value="4">Piutang</option>
                                            <option value="5">Obat</option>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex col col-md-7">
                                        <select name="pilih_laporan" id="pilih_laporan" class="form-control form-control-user">
                                            <option value="1">Pembelian</option>
                                            <option value="1">Pembelian Kredit</option>
                                            <option value="1">Pembelian Tunai</option>

                                            <option value="2">Penjualan</option>
                                            <option value="2">Penjualan Kredit</option>
                                            <option value="2">Penjualan Tunai</option>

                                            <option value="5">Obat Expired</option>
                                            <option value="5">Obat Stok Habis</option>
                                            <option value="5">Obat Masuk</option>
                                            <option value="5">Obat Keluar</option>

                                            <option value="3">Hutang</option>
                                            <option value="3">Hutang Lunas</option>
                                            <option value="3">Hutang Belum Lunas</option>

                                            <option value="4">Piutang</option>
                                            <option value="4">Piutang Lunas</option>
                                            <option value="4">Piutang Belum Lunas</option>
                                            <option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- select periode laporan   -->
                    <div class="col-lg-3" id="formfilter">

                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter
                            </div>
                            <!--id formfilter adalah nama form untuk filter-->
                            <form>
                                <div class="card-body card-block">
                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-3"><label for="select" class=" form-control-label">Pilih Periode</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="periode" id="periode" class="form-control form-control-user">
                                                <option value="tanggal">Tanggal</option>
                                                <option value="bulan">Bulan</option>
                                                <option value="tahun">Tahun</option>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">

                                    <!--ketika di klik tombol Proses, maka akan mengeksekusi fungsi javascript prosesPeriode() , untuk menampilkan form-->

                                    <button id="btnproses" type="button" onclick="prosesPeriode()" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Proses</button>

                                    <!--ketika di klik tombol Reset, maka akan mengeksekusi fungsi javascript prosesReset() , untuk menyembunyikan form-->
                                    <button onclick="prosesReset()" type="button" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> Reset</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ================Pembelian kredit================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_beli_kredit">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_beli_kredit" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_beli_kredit">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_beli_kredit" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_beli_kredit">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_beli_kredit" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Pembelian Tunai================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_beli_tunai">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_beli_tunai" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_beli_tunai">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_beli_tunai" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_beli_tunai">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_beli_tunai" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Penjualan================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_penjualan">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_penjualan" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_penjualan">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_penjualan" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_penjualan">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_penjualan" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Penjualan Kredit================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_jual_kredit">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_jual_kredit" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_jual_kredit">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_jual_kredit" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_jual_kredit">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_jual_kredit" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Penjualan Tunai================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_jual_tunai">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_jual_tunai" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_jual_tunai">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_jual_tunai" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_jual_tunai">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_jual_tunai" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Obat Expired================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_obat_expired">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_expired" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_obat_expired">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_expired" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_obat_expired">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_expired" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Obat Masuk================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_obat_masuk">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_masuk" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_obat_masuk">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_masuk" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_obat_masuk">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_masuk" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ================Obat Keluar================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_obat_keluar">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_keluar" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_obat_keluar">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_keluar" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_obat_keluar">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_keluar" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ================Obat Stok Habis================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_obat_stok">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_stok" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_obat_stok">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_stok" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_obat_stok">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_obat_stok" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ================Hutang================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_hutang">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_hutang" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_hutang">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_hutang" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_hutang">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_hutang" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ================Piutang================= -->

                    <!-- if date selected  -->
                    <div class="col-lg-4" id="tanggalfilter_piutang">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tanggal
                            </div>
                            <form action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_piutang" method="POST" target="_blank">
                                <input type="hidden" name="nilaifilter" value="1">

                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalawal" autocomplete="off">
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai tanggal</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <input class="datepicker form-control no-space input-sm" data-date-format="mm/dd/yyyy" name="tanggalakhir" autocomplete="off">

                                        </div>
                                        <small class="help-block form-text"></small>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if month selected  -->
                    <div class="col-lg-4" id="bulanfilter_piutang">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Bulan
                            </div>
                            <form id="formbulan" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_piutang" method="POST" target="_blank">
                                <div class="card-body card-block">
                                    <input type="hidden" name="nilaifilter" value="2">

                                    <input name="valnilai" type="hidden">
                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun1" class="form-control  select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Dari Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanawal" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <div class="col col-md-2">
                                            <label for="select" class=" form-control-label">Sampai Bulan</label>
                                        </div>
                                        <div class="col col-md-4">
                                            <select name="bulanakhir" class="form-control select_in_periode" title="Pilih Bulan">
                                                <option value="1">Januari</option>
                                                <option value="2">Februari</option>
                                                <option value="3">Maret</option>
                                                <option value="4">April</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Juni</option>
                                                <option value="7">Juli</option>
                                                <option value="8">Agustus</option>
                                                <option value="9">September</option>
                                                <option value="10">Oktober</option>
                                                <option value="11">November</option>
                                                <option value="12">Desember</option>
                                            </select>
                                        </div>
                                        <small class="help-block form-text"></small>

                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- if year selected  -->
                    <div class="col-lg-4" id="tahunfilter_piutang">
                        <div class="card">
                            <div class="card-header">
                                <strong>Form</strong> Filter by Tahun
                            </div>
                            <form id="formtahun" action="<?php echo base_url(); ?>farmasi/laporan/print_laporan_piutang" method="POST" target="_blank">
                                <input name="valnilai" type="hidden">
                                <div class="card-body card-block">

                                    <input type="hidden" name="nilaifilter" value="3">

                                    <div class="row form-group">
                                        <div id="form-tanggal" class="col col-md-2"><label for="select" class=" form-control-label">Pilih Tahun</label></div>
                                        <div class="col-12 col-md-10">
                                            <select name="tahun2" class="form-control select_in_periode" title="Pilih Tahun">
                                                <option value="">Pilih</option>
                                                <?php foreach ($tahun as $thn) : ?>
                                                    <option value="<?php echo $thn->tahun; ?>"><?php echo $thn->tahun; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block form-text"></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>