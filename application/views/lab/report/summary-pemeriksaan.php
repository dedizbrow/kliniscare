<div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
	<div class="mb-2">
		<div class="btn-group">
			<label>Report: </label>
			<a href="<?= base_url(conf('path_module_lab') . 'report/by-periode'); ?>" class="btn btn-flat btn-success btn-xs">By Periode</a>
			<a href="<?= base_url(conf('path_module_lab') . 'report/by-pemeriksaan'); ?>" class="btn btn-flat btn-warning btn-xs">By Pemeriksaan</a>
			<a href="<?= base_url(conf('path_module_lab') . 'report/biaya-pemeriksaan'); ?>" class="btn btn-flat btn-primary btn-xs">Biaya Pemeriksaan</a>
		</div>

	</div>
	<form method="get" action="">
		<div class="card card-dashboard-one">
			<div class="card-header bg-purple border-bottom bg-warning">
				<div class="card-title">
					<h4 class="tx-white">Laporan Pemeriksaan </h4>
				</div>
				<?php if (!empty($list_pemeriksaan)) { ?>
					<div class="btn-group pull-right">
						<a href="<?= base_url(conf('path_module_lab') . 'report/by-pemeriksaan/export/excel?start_date=' . $start_date . '&end_date=' . $end_date . '&provider=' . $provider . '&jenis=' . $jenis . '&hasil_pemeriksaan=' . $hasil_pemeriksaan . '&pdf=true'); ?>" target="_blank" download class="btn btn-xs bg-white"><i class="fa fa-file-excel-o tx-success"></i> Download Excel</a>
						<a href="<?= base_url(conf('path_module_lab') . 'report/by-pemeriksaan/export?start_date=' . $start_date . '&end_date=' . $end_date . '&provider=' . $provider . '&jenis=' . $jenis . '&hasil_pemeriksaan=' . $hasil_pemeriksaan . '&pdf=true'); ?>" target="_blank" download class="btn btn-xs bg-white"><i class="fa fa-file-pdf-o tx-danger"></i> Download PDF</a>
					</div>
				<?php } ?>
			</div>
			<div class="card-body  bg-bd-white-3">
				<table class="" width="auto" cellpadding="5" cellspacing="0">
					<tr>
						<td width="150px">Periode</td>
						<td width="5%">:</td>
						<td>
							<input name="start_date" value="<?= $start_date; ?>"> s/d
							<input name="end_date" value="<?= $end_date; ?>">
						</td>
					</tr>
					<tr <?php if (isset($C_PV_GROUP) && $C_PV_GROUP != 'pusat' || conf('lab_enable_select_provider') === FALSE) echo "class='hide'"; ?>>
						<td>Provider</td>
						<td>:</td>
						<td><select class="" name="provider">
								<?php
								foreach ($list_provider as $item) {
									$selected = ($provider == $item->id) ? "selected=''" : "";
									echo '<option value="' . $item->id . '" ' . $selected . '>' . $item->nama . '</option>';
								}
								?>
							</select></td>
					</tr>
					<tr>
						<td>Jenis Pemeriksaan</td>
						<td>:</td>
						<td><select class="" name="jenis">
								<?php
								foreach ($list_jenis as $jns) {
									$selected = ($jenis == $jns->id) ? "selected='selected'" : "";
									echo '<option value="' . $jns->id . '" ' . $selected . '>' . $jns->jenis . '</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Hasil Pemeriksaan</td>
						<td>:</td>
						<td><select class="" name="hasil_pemeriksaan">
								<option value="">Semua</option>
							</select>
							<input type="hidden" value="<?= (isset($hasil_pemeriksaan)) ? $hasil_pemeriksaan : ''; ?>" id="selected_hasil_pemeriksaan">
							<button type="submit" class="btn btn-xs btn-primary" style="margin-left: 20px">Submit</button>
						</td>
					</tr>
				</table>
				<p></p>
				<table width="100%" id="table table-bordered dataTable table-striped" border="1" cellpadding="5">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nama Pasien</th>
							<th>Hasil</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (empty($list_pemeriksaan)) {
							echo '<tr><td colspan=4>Tidak ada data</td></tr>';
						}
						$i = 1;
						foreach ($list_pemeriksaan as $item) {
						?>
							<tr>
								<td><?= $i; ?></td>
								<td><?= date("d-m-Y", strtotime($item->tgl_periksa)); ?></td>
								<td><?= $item->nama_pasien; ?></td>
								<td><?= $item->hasil; ?></td>
							</tr>
						<?php
							$i++;
						}
						?>
					</tbody>
				</table>
				<p><i>* Laporan ini hanya menampilkan nama pasien dengan status "selesai" dari data pemeriksaan</i></p>
			</div>
		</div>
	</form>
</div>