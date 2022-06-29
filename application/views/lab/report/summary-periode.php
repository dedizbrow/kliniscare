<div class="col-lg-10 col-md-8 col-sm-12 col-xs-12">
	<div class="mb-2">
		<div class="btn-group">
			<label>Report: </label>
			<a href="<?= base_url(conf('path_module_lab') . 'report/by-periode'); ?>" class="btn btn-flat btn-success btn-xs">By Periode</a>
			<a href="<?= base_url(conf('path_module_lab') . 'report/by-pemeriksaan'); ?>" class="btn btn-flat btn-warning btn-xs">By Pemeriksaan</a>
			<a href="<?= base_url(conf('path_module_lab') . 'report/biaya-pemeriksaan'); ?>" class="btn btn-flat btn-primary btn-xs">Biaya Pemeriksaan</a>
		</div>

	</div>
	<div class="card card-dashboard-one">
		<div class="card-header border-bottom bg-success">
			<div class="card-title">
				<h4 class="tx-white">Laporan Pemeriksaan </h4>
			</div>
			<?php if (!empty($list_by_provider)) { ?>
				<div class="btn-group pull-right">
					<a href="<?= base_url(conf('path_module_lab') . 'report/by-periode/export/excel?periode=' . $selected_year . '-' . $selected_month); ?>" target="_blank" class="btn btn-xs bg-white pull-right"><i class="fa fa-file-excel-o tx-success"></i> Download Excel</a>
					<a href="<?= base_url(conf('path_module_lab') . 'report/by-periode/export?periode=' . $selected_year . '-' . $selected_month) . "&pdf=true"; ?>" target="_blank" class="btn btn-xs pull-right bg-white"><i class="fa fa-file-pdf-o tx-danger"></i> Download PDF</a>
				</div>
			<?php } ?>
		</div>
		<div class="card-body bg-bd-white-3">
			<table>
				<tr>
					<td>Periode</td>
					<td>:</td>
					<td>
						<select class="" id="select_month">
							<?php
							$arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
							foreach ($arr_month as $k => $v) {
								$m = (($k + 1) < 10) ? '0' . ($k + 1) : ($k + 1);
								$selected = ($m == $selected_month) ? 'selected="selected"' : '';
								echo '<option value="' . $m . '" ' . $selected . '>' . $v . '</option>';
							}
							?>
						</select>
						<select class="year" id="select_year">
							<?php
							$syear = 2021;
							$cyear = date("Y");
							$f = false;
							for ($y = $syear; $y <= $cyear; $y++) {
								$selected = ($y == $selected_year) ? 'selected=""' : '';
								if ($y == $selected_year) $f = true;
								echo '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
							}
							if (!$f && $selected_year != $cyear) echo '<option value="' . $selected_year . '" selected>' . $selected_year . '</option>';
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Jumlah Total</td>
					<td>:</td>
					<td><?= $grand_total; ?></td>
				</tr>
				<!-- LOOP JENIS -->
				<?php
				$arr_jenis = [];
				foreach ($list_jenis as $item) {
					echo '
					<tr>
						<td>' . $item->jenis . '</td>
						<td>:</td>
						<td class="total_sum_jenis data-id="' . $item->id . '">' . $sum_total[$item->jenis] . '</td>
					</tr>
					';
					array_push($arr_jenis, $item->jenis);
				}
				?>

			</table>
			<p></p>
			<table width="100%" id="table table-bordered dataTable table-striped" border="1" cellpadding="5">
				<thead>
					<tr>
						<th>No</th>
						<!-- <th>Nama Provider</th> -->
						<th>Jenis Pemeriksaan</th>
						<th>Jumlah</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$n = 1;
					$totals = 0;
					if (empty($list_by_provider)) echo '<tr><td colspan="3">Tidak ada data</td></tr>';
					foreach ($list_by_provider as $provider => $item) { ?>
						<tr>
							<td><b><?= $n++; ?></b></td>
							<!-- <td><b><?= $provider; ?></b></td> -->
							<td><?= $arr_jenis[0]; ?></td>
							<td>
								<?php
								$found = false;
								foreach ($item as $j => $v) {
									$totals += (int) $v->jumlah_selesai;
									if ($v->jenis == $arr_jenis[0]) {
										$found = true;
										echo $v->jumlah_selesai;
									}
								}
								if (!$found) echo 0;
								?>
							</td>
						</tr>
						<?php
						$alias_jenis = $arr_jenis;
						array_shift($alias_jenis);
						foreach ($alias_jenis as $jns) { ?>
							<tr>
								<!-- <td></td> -->
								<td></td>
								<td><?= $jns; ?></td>
								<td>
									<?php
									$found = false;
									foreach ($item as $j => $v) {
										if ($v->jenis == $jns) {
											$found = true;
											echo $v->jumlah_selesai;
										}
									}
									if (!$found) echo 0;
									?>
								</td>
							</tr>

					<?php
						}
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2">TOTAL</td>
						<td><?= $totals; ?></td>
					</tr>
				</tfoot>
			</table>
			<p><i>* Laporan ini hanya menampilkan jumlah status "selesai" dari data pemeriksaan</i></p>
		</div>
	</div>
</div>