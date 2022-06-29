<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header-large">
				<div class="card-title">
					Pengeluaran
				</div>
				<div class="ml-3 mr-3 tx-center label-filter ">
					<label class="ftr align-middle">Periode
						<select class="form-control input-sm inline-block" style="width: 110px; display: inline-block;" name="bulan">
							<!-- <option value="">Bulan</option> -->
							<?php
							$selected_month = date("n");
							$arr_month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
							foreach ($arr_month as $k => $v) {
								$m = (($k + 1) < 10) ? '0' . ($k + 1) : ($k + 1);
								$selected = ($m == $selected_month) ? 'selected="selected"' : '';
								echo '<option value="' . $m . '" ' . $selected . '>' . $v . '</option>';
							}
							?>
						</select>
						<select class="form-control input-sm inline-block" style="width: 80px; display: inline-block;" name="tahun">
							<?php
							$syear = 2020;
							$cyear = date("Y");
							$selected_year = date("Y");
							$f = false;
							for ($y = $syear; $y <= $cyear; $y++) {
								$selected = ($y == $selected_year) ? 'selected=""' : '';
								if ($y == $selected_year) $f = true;
								echo '<option value="' . $y . '" ' . $selected . '>' . $y . '</option>';
							}
							if (!$f && $selected_year != $cyear) echo '<option value="' . $selected_year . '" selected>' . $selected_year . '</option>';
							?>
						</select>
					</label>
				</div>

				<div class="pull-right">
					<?php if (isAllowed('c-pengeluaran^create', true)) { ?>
						<button type="button" class="btn btn-success add-biaya btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
					<?php } ?>
					<a href="<?php echo site_url(); ?>keuangan/pengeluaran/export_"><button type="button" class="btn btn-warning btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-striped minimize-padding-all" id="dataBiaya" width="100%">
					<thead>
						<tr>
							<th>No.</th>
							<th><?= lang('label_name'); ?></th>
							<th><?= lang('label_date'); ?></th>
							<th><?= lang('label_category'); ?></th>
							<th><?= lang('label_tot'); ?></th>
							<th><?= lang('label_ket'); ?></th>
							<th><?= lang('label_action'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>


<div id="modal-manage-biaya" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-biaya">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Add/Update Data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="form-label">Nama Pengeluaran</label>
								<input class="datepicker form-control no-space input-sm" placeholder="eq. Bayar listrik " name="nama" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<label class="form-label"><?= lang('label_category'); ?></label>
							<div class="d-grid d-md-flex ">
								<select id="kategori" class="form-control" name="kategori_biaya" style="width:100%;">
									<option value=""></option>
								</select>
								<button type="button" class="btn btn-secondary btn-block btn-xs btn-add add-kategori"><i class="fa fa-plus"></i></button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Biaya</label>
								<input type="text" class="form-control input-sm" name="total" id="total" autocomplete="off" required="" placeholder="biaya">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_ket'); ?></label>
								<textarea class="form-control col-xs-12" rows="7" cols="100" name="keterangan" id="keterangan" placeholder="<?= lang('label_ket'); ?>"></textarea>
							</div>
						</div>

					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-biaya" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modal-manage-kategori" class="modal">
	<!-- modal kategori obat-->
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-kategori">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Add data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_category'); ?></label>
								<input type="text" class="form-control input-sm" name="nama_kategori" id="nama_kategori" autocomplete="off" placeholder="eq. umum, apotek, sewa">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_idkategori" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-kategori" class="btn btn-indigo"> <?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
