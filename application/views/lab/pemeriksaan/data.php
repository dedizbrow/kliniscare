<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header-large">
				<div class="card-title">
					Data Pemeriksaan
				</div>
				<div class="ml-3 mr-3 tx-center label-filter">
					<?php if (isset($C_PV_GROUP) && $C_PV_GROUP == 'pusat' && conf('lab_enable_select_provider') === TRUE) { ?>
						<label class="ftr">Filter: </label>
						<label class="ftr">Provider
							<select class="form-control input-sm inline-block" style="width: 200px" name="filter_provider"></select>
						</label>
					<?php } ?>
					<label class="ftr align-middle">Pemeriksaan
						<select class="form-control input-sm inline-block" style="width: 200px" name="filter_pemeriksaan"></select>
					</label>
					<label class="ftr align-middle"> Status
						<select class="form-control input-sm inline-block" name="filter_status" style="max-width: 100px">
							<option value="">Semua</option>
							<option value="BELUM">Belum</option>
							<option value="SELESAI">Selesai</option>
							<option value="CANCEL">Cancel</option>
						</select>
					</label>
					<label class="ftr align-middle">
						<select class="form-control input-sm inline-block" name="filter_tgl_option" style="max-width: 110px;display: inline-block">
							<option value="tgl_periksa">Tgl. Daftar</option>
							<option value="tgl_sampling">Tgl Sampling</option>
						</select>
						<input type="text" name="start_date" class="form-control input-sm" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
						s/d
						<input type="text" name="end_date" class="form-control input-sm" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
					</label>

				</div>
				<div class="pull-right align-middle">
					<a href="<?= base_url(conf('path_module_lab') . 'pemeriksaan/form'); ?>" class="btn btn-success add-pemeriksaan btn-xs"><i class="fa fa-plus"></i> Tambah Data</a>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataPemeriksaan" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th class="search" data-name="periksa.tgl_periksa">Tgl. Daftar</th>
							<th class="search" data-name="periksa.tgl_sampling">Tgl. Sampling</th>
							<th class="search" data-name="pasien.no_test">No. RM</th>
							<th class="search" data-name="pasien.no_identitas">NIK</th>
							<th class="search" data-name="pasien.nama">Nama Pasien</th>
							<th class="search" data-name="provider.nama">Provider</th>
							<th>Jenis Kelamin</th>
							<th>Usia</th>
							<th>Pemeriksaan</th>
							<th>Status</th>
							<th>Biaya</th>
							<th>Bayar</th>
							<th>User</th>
							<th>Update</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-klinik" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-klinik">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update Klinik</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Nama Klinik</label>
								<input type="text" name="nama" class="form-control input-sm" required="" placeholder="Masukkan Nama Klinik" autocomplete="off">
							</div>
							<div class="form-group">
								<label class="form-label">Alamat</label>
								<textarea name="alamat" class="form-control input-sm" required="" placeholder="Masukkan Alamat" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Telp.</label>
								<input type="text" name="telp" class="form-control input-sm" required="" placeholder="Masukkan No Telp." autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Penanggung Jawab</label>
								<input type="text" name="penanggung_jawab" class="form-control input-sm" required="" placeholder="Masukkan Penanggung Jawab" autocomplete="off">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-klinik" class="btn btn-indigo"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
