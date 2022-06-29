<div class="row">
	<section class="col-lg-12  col-md-12  col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<form method="POST" name="form-pemeriksaan">
				<input type="hidden" name="ref_id" value="<?= (isset($data_periksa->id)) ? $data_periksa->id : ''; ?>">
				<div class="card-header border">
					<div class="card-title">Registrasi Pemeriksaan</div>
					<div class="pull-right"></div>
				</div>
				<div class="card-body">
					<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
						<table class="dataTable minimize-padding-all v-center tablePasien" id="tablePasien" width="100%">
							<!-- <tr>
								<td style="width: 100px">No. Test</td>
								<td style="width: 5px">:</td>
								<td class="no_test">
									<div class="form-group">
										<input type="text" class="form-control input-sm" name="no_test">
									</div>
								</td>
							</tr> -->
							<?php
							if (isset($C_PV_GROUP) && $C_PV_GROUP == 'pusat' && conf('lab_enable_select_provider') === TRUE) { ?>
								<tr>
									<td style="width: 100px">Provider</td>
									<td style="width: 5px">:</td>
									<td class="dokter">
										<div class="form-group">
											<select class="form-control input-sm" name="provider" id="search_provider"></select>
										</div>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td colspan="3" class="text-center">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-prepend"><span class="input-group-text text-italic">Cari Data Pasien &raquo;</span></div>
											<select class="form-control input-sm" name="search_pasien" id="search_pasien"></select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td style="width: 100px">Nama Pasien</td>
								<td style="width: 5px">:</td>
								<td class="nama_pasien">
									<div class="form-group">
										<input type="hidden" class="form-control input-sm autofill" readonly="" value="<?= (isset($data_periksa->id_pasien_int)) ? $data_periksa->id_pasien_int : ''; ?>" name="_id_pasien">
										<input type="text" class="form-control input-sm autofill" readonly="" name="nama_lengkap" value="<?= (isset($data_periksa->nama_pasien)) ? $data_periksa->nama_pasien : ''; ?>">
									</div>
								</td>
							</tr>
							<tr>
								<td>Tgl. Lahir</td>
								<td>:</td>
								<td class="tgl_lahir">
									<div class="form-group">
										<input type="text" class="form-control input-sm autofill" readonly="" name="tgl_lahir" value="<?= (isset($data_periksa->tgl_lahir)) ? $data_periksa->tgl_lahir : ''; ?>">
									</div>
								</td>
							</tr>
							<tr>
								<td>Jenis Kelamin</td>
								<td>:</td>
								<td class="jenis_kelamin">
									<div class="form-group">
										<input type="text" class="form-control input-sm autofill" readonly="" name="jenis_kelamin" value="<?= (isset($data_periksa->jenis_kelamin)) ? $data_periksa->jenis_kelamin : ''; ?>">
									</div>
								</td>
							</tr>
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td class="alamat">
									<div class="form-group">
										<textarea class="form-control input-sm autofill" rows="3" readonly="" name="alamat"><?= (isset($data_periksa->alamat)) ? $data_periksa->alamat : ''; ?></textarea>
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
						<table class="dataTable minimize-padding-all v-center tablePasien" width="100%">

							<tr>
								<td style="width: 120px">Dokter</td>
								<td style="width: 5px">:</td>
								<td class="dokter">
									<div class="form-group">
										<select class="form-control input-sm" name="dokter" id="search_dokter"></select>
									</div>
								</td>
							</tr>
							<tr>
								<td>Tgl/Jam Sampling</td>
								<td>:</td>
								<td>
									<div class="input-group">
										<span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
										<input type="text" class="form-control input-sm" value="<?= (isset($data_periksa->tgl_sampling) && $data_periska->tgl_sampling != '0000-00-00 00:00:00') ? date("Y-m-d", strtotime($data_periksa->tgl_sampling)) : ""; ?>" name="tgl_sampling">
										<span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
										<input type="text" class="form-control input-sm time-format" value="" placeholder="00:00" name="jam_sampling">
									</div>
								</td>
							</tr>
							<tr>
								<td>Jenis Sample</td>
								<td>:</td>
								<td>
									<div class="form-group">
										<select class="form-control input-sm" id="search_sampling" name="jenis_sample"></select>
									</div>
								</td>
							</tr>
							<tr>
								<td>NIK</td>
								<td>:</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control input-sm autofill" name="no_identitas" readonly="">
									</div>
								</td>
							</tr>
							<tr>
								<td>Asuransi</td>
								<td>:</td>
								<td>
									<div class="form-group">
										<select  name="asuransi" id="asuransi" class="form-control input-sm" placeholder="Asuransi" autocomplete="off"></select>
									</div>
								</td>
							</tr>
							<tr>
								<td>No Asuransi</td>
								<td>:</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control input-sm autofill" name="no_asuransi">
									</div>
								</td>
							</tr>
							<tr>
								<td>Perujuk</td>
								<td>:</td>
								<td>
									<div class="form-group">
										<select  name="perujuk" id="perujuk" class="form-control input-sm" placeholder="Perujuk" autocomplete="off"></select>
									</div>
								</td>
							</tr>
							<tr>
								<td>Nama Perujuk</td>
								<td>:</td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control input-sm autofill" name="nama_tenaga_perujuk">
									</div>
								</td>
							</tr>
					<!-- <tr>
								<td>Keluhan</td>
								<td>:</td>
								<td class="keluhan">
									<div class="form-group">
										<textarea class="form-control input-sm autofill" rows="3"  name="keluhan"></textarea>
									</div>
								</td>
							</tr> -->
					</table>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text">Pilih Jenis Pemeriksaan &raquo;</span></div>
							<select class="form-control input-sm" id="search_jenis_pemeriksaan" name="jenis_pemeriksaan" style="max-width: 270px"></select>
						</div>
					</div>
					<!-- <table id="tableJenisPeriksa" class="display dataTable minimize-padding-all v-center table-bordered table-striped with-border" width="100%">
							<thead>
								<tr>
									<th>Pemeriksaan</th>
									<th>Hasil</th>
									<th>Nilai Rujukan</th>
									<th>Metode</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table> -->
				</div>
				<div class="col-lg-12">
					<p></p>
				</div>

		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-primary" id="submit_pemeriksaan"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
			<a href="<?= base_url(conf('path_module_lab') . 'pemeriksaan'); ?>" class="btn btn-info pull-right"><i class="fa fa-chevron-left"></i> <?= lang('label_back'); ?></a>
		</div>
		</form>
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


<style>
	table.v-center {
		display: table;
	}

	table.v-center tr td {
		display: table-cell;
		vertical-align: middle;
	}
</style>
