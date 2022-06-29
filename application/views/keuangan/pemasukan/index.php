<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header-large">
				<div class="card-title">
					Pemasukan & pengeluaran, periode : 
					<?php $arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
					echo "$hari ".$arr_month[$bulan - 1] . " $tahun";
					?>
				</div>
				<form id="formbulan" action="<?php echo base_url(); ?>keuangan/pemasukan/index" method="POST">

					<div class="ml-3 mr-3 tx-center label-filter ">
						<label class="ftr align-middle">Periode
							<input type="hidden" name="clinic_id" value="<?=$clinic_id;?>">
							<select class="form-control input-sm inline-block" style="width: 80px; display: inline-block;" name="tahun">
								<?php
								$syear = 2020;
								$cyear = date("Y");
								if (!isset($tahun)) {
									$tahun = date("Y");
								}
								$f = false;
								for ($y = $syear; $y <= $cyear; $y++) {
									$selected = ($y == $tahun) ? 'selected=""' : '';
									if ($y == $tahun) $f = true;
									echo '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
								} ?>
							</select>
							<select class="form-control input-sm inline-block" style="width: 110px; display: inline-block;" name="bulan">
								<?php
								if (!isset($bulan)) {
									$bulan = date("n");
								}
								$arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
								foreach ($arr_month as $k => $v) {
									$m = (($k + 1) < 10) ? '0' . ($k + 1) : ($k + 1);
									$selected = ($m == $bulan) ? 'selected="selected"' : '';
									echo '<option value="' . $m . '" ' . $selected . '>' . $v . '</option>';
								}
								?>
							</select>

							<select class="form-control input-sm inline-block" style="width: 110px; display: inline-block;" name="hari">
								<option value="">---</option>
								<?php
								if (!isset($hari)) $hari="";
								for ($k=0;$k<31;$k++) {
									$m = (($k + 1) < 10) ? '0' . ($k + 1) : ($k + 1);
									$selected = ($m == $hari) ? 'selected="selected"' : '';
									echo '<option value="' . $m . '" ' . $selected . '>' . $m . '</option>';
								}
								?>
							</select>
							<button type="submit" class="btn btn-sm btn-primary btn-xs" style="height: 30px;"><i class="fa fa-search"></i></button>
						</label>
					</div>
				</form>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" role="grid" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="1%">No.</th>
							<th width="79%">Jenis Pemasukan</th>
							<th align="right" width="20%">Total</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="1%">1</td>
							<td width="79%"><a href="#" class="tx-primary" data-toggle="modal" data-target="#modal-detail-tindakan">Klinik : Pemeriksaan / Tindakan </a></td>
							<td align="right" width="20%"><?= format_number($total_tindakan); ?></td>
						</tr>
						<tr>
							<td width="1%">2</td>
							<td width="79%"><a href="#" class="tx-primary" data-toggle="modal" data-target="#modal-detail-kamar">Klinik : Sewa Kamar / Rawat Inap </a></td>
							<td align="right" width="20%"><?= format_number($total_kamar); ?></td>
						</tr>
						<tr>
							<td width="1%">3</td>
							<td width="79%"><a href="#" class="tx-primary" data-toggle="modal" data-target="#modal-detail-resep">Klinik : Resep Dokter : Penjualan Obat</a></td>
							<td align="right" width="20%"><?= format_number($total_resep); ?></td>
						</tr>
						<tr>
							<td width="1%">4</td>
							<td width="79%"><a href="#" class="tx-primary" data-toggle="modal" data-target="#modal-detail-obat">Apotek : Penjualan Obat</a></td>
							<td align="right" width="20%"><?= format_number($total_apotek_obat); ?></td>
						</tr>
						<tr>
							<td width="1%">5</td>
							<td width="79%"><a href="#" class="tx-primary" data-toggle="modal" id="detail_lab" data-target="#modal-detail-lab">Laboratorium : Pemeriksaan</a></td>
							<td align="right" width="20%"><?= format_number($total_pemeriksaan_lab); ?></td>
						</tr>
						<?php $total_pemasukan = $total_tindakan + $total_kamar + $total_resep + $total_apotek_obat + $total_pemeriksaan_lab ?>
						<tr style="background-color: lightgray;">
							<td width="20%" colspan="2"><b>Total Pemasukan</b></td>
							<td align="right" width="20%"><b><?= format_number($total_pemasukan); ?></b></td>
						</tr>
						<tr>
							<th width="1%">NO.</th>
							<th width="79%">JENIS PENGELUARAN</th>
							<th align="right" width="20%">TOTAL</th>
						</tr>
						<tr>
							<td width="1%">1</td>
							<td width="79%">Apotek : Pembelian Stok Obat</td>
							<td align="right" width="20%"><?= format_number($pengeluaran_apotek_obat); ?></td>
						</tr>
						<?php
						$no = 2;
						$subtotal_plg = 0;
						foreach ($pengeluaran as $dt) {
							echo '<tr>
									<td   width="1%">' . $no . '</td>
									<td  width="79%">' . $dt->nama_kategori . '</td>
									<td align="right" width="20%">' . number_format($dt->pengeluaran) . '</td>
								</tr>';
							$subtotal_plg += $dt->pengeluaran;
							$no++;
						}
						?>
					</tbody>
					<?php $total_pengeluaran = $pengeluaran_apotek_obat + $subtotal_plg ?>
					<tfoot>
						<tr style="background-color: lightgray;">
							<td width="20%" colspan="2"><b>Total Pengeluaran</b></td>
							<td align="right" width="20%"><b><?= format_number($total_pengeluaran); ?></b></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="footer">
				<table class="table table-striped minimize-padding-all" role="grid" width="100%" cellspacing="0">
					<tr style="font-size: 23px;">
						<td><b>Total pendapatan bersih</b></td>
						<td align="right"><b>Rp<?php $tot_ = $total_pemasukan - $total_pengeluaran;
												echo format_number($tot_); ?>,-</b></td>
					</tr>
				</table>
			</div>

		</div>
	</section>
</div>


<div id="modal-detail-tindakan" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail pemeriksaan / tindakan, periode :
					<?php $arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
					echo $arr_month[$bulan - 1] . " $tahun";
					?>
				</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped minimize-padding-all" width="100%" role="grid" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th width="1%">No</th>
							<th>Tgl Pembayaran</th>
							<th>Nama Pasien</th>
							<th>No Invoice</th>
							<th align="right">Biaya</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($detail_tindakan as $dt) {
						?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= dateIndo($dt->create_at); ?></td>
								<td><?= $dt->nama_lengkap; ?></td>
								<td><?= $dt->no_invoice; ?></td>
								<td align="right"><?= format_number($dt->total_det_tindakan) ?></td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>

			</div>
		</div>
	</div><!-- modal-dialog -->
</div><!-- modal -->


<div id="modal-detail-kamar" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail sewa kamar / rawat inap, periode :
					<?php $arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
					echo $arr_month[$bulan - 1] . " $tahun";
					?>
				</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped minimize-padding-all" width="100%" role="grid" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th width="1%">No</th>
							<th>Tgl Pembayaran</th>
							<th>Nama Pasien</th>
							<th>No Invoice</th>
							<th>No kamar/ranjang</th>
							<th align="right">Biaya</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($detail_kamar as $dt) {
						?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= dateIndo($dt->create_at); ?></td>
								<td><?= $dt->nama_lengkap; ?></td>
								<td><?= $dt->no_invoice; ?></td>
								<td><?= $dt->namaRuangan . '/' . $dt->nomor . '/' . $dt->nomor_ranjang ?></td>
								<td align="right"><?= format_number($dt->total_det_kamar) ?></td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>

			</div>
		</div>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-detail-resep" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail klinik resep dokter / penjualan obat, periode :
					<?php $arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
					echo $arr_month[$bulan - 1] . " $tahun";
					?>
				</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped minimize-padding-all" width="100%" role="grid" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th width="1%">No</th>
							<th>Tgl Pembayaran</th>
							<th>Nama Pasien</th>
							<th>No Invoice</th>
							<th align="right">Biaya</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($detail_resep as $dt) {
						?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= dateIndo($dt->create_at); ?></td>
								<td><?= $dt->nama_lengkap; ?></td>
								<td><?= $dt->no_invoice; ?></td>
								<td align="right"><?= format_number($dt->total_det_resep) ?></td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>

			</div>
		</div>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-detail-obat" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail Apotek / penjualan obat, periode :
					<?php $arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
					echo $arr_month[$bulan - 1] . " $tahun";
					?>
				</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped minimize-padding-all" width="100%" role="grid" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th width="1%">No</th>
							<th>Tgl Pembayaran</th>
							<th>No Faktur</th>
							<th align="right">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($detail_apotek_obat as $dt) {
						?>
							<tr>
								<td><?= $no; ?></td>
								<td><?= dateIndo($dt->tanggal); ?></td>
								<td><?= $dt->faktur; ?></td>
								<td align="right"><?= format_number($dt->total_det_apotek_obat) ?></td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>

			</div>
		</div>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-detail-lab" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail pemeriksaan laboratorium, periode :
					<?php $arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
					echo $arr_month[$bulan - 1] . " $tahun";
					?>
				</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped minimize-padding-all d-md-table" id="dataPemeriksaanLab" width="1000%" style="width: 100%;" cellspacing="0" cellpadding="0">
					<thead>
						<tr role="row">
							<th>No</th>
							<th class="search" data-name="tgl_periksa">Tgl Pembayaran</th>
							<th>Nama Pasien</th>
							<th>Jenis Pemeriksaan</th>
							<th>Biaya</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div><!-- modal-dialog -->
</div><!-- modal -->
