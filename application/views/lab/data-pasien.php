<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					<?= lang('label_list_patient'); ?>
					<?php
					if (isset($import_id)) {
						echo "<input type='hidden' id='import_id' value='" . $import_id . "'><br>
							<div class='tx-primary txt-normal mt-2'>[Data yang ditampilkan adalah yang baru saja anda import] 
							<a class='text-normal tx-danger' href='" . base_url('pasien') . "' title='Click disini untuk kembali ke keseluruhan data pasien'><sup><i class=''></i> clear filter</sup></a>
							</div>
							
							";
					}
					?>
				</div>
				<div class="ml-3 mr-3 tx-center label-filter">
					<?php if (isset($C_PV_GROUP) && $C_PV_GROUP == 'pusat' && conf('lab_enable_select_provider') === TRUE) { ?>
						<label class="">Filter: </label>
						<label class="bg-black-1">Provider
							<select class="form-control input-sm inline-block" style="width: 200px" name="filter_provider"></select>
						</label>
					<?php } ?>
				</div>
				<div class="pull-right">
					<button type="button" class="btn btn-success add-pasien btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
					<!-- <button type="button" class="btn btn-import import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button> -->
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataPasien" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th class="search" data-name="provider">Provider</th>
							<th class="search" data-name="nomor_rm">No. RM</th>
							<th class="search" data-name="nama"><?= lang('label_patient_name'); ?></th>
							<th class="search" data-name="no_identitas"><?= lang('label_identity_no'); ?></th>
							<th class="search" data-name="kewarganegaraan"><?= lang('label_nationality'); ?></th>
							<th><?= lang('label_date_of_birth'); ?></th>
							<th><?= lang('label_gender'); ?></th>
							<th><?= lang('label_address'); ?></th>
							<th><?= lang('label_telp'); ?></th>
							<th>Email</th>
							<th><?= lang('label_register'); ?></th>
							<th><?= lang('label_action'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-pasien" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-pasien">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title"><?= lang('label_add_update_patient'); ?></h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<?php
						if (strtolower($C_PV_GROUP) == 'pusat' && conf('lab_enable_select_provider') === TRUE) {
						?>
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="form-group">
									<label class="form-label"><?= lang('label_select_provider'); ?></label>
									<select class="form-control input-sm" name="provider_id"></select>
								</div>
							</div>
						<?php } ?>
						<div class="col-lg-8 col-md-8 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_select_checkup_type'); ?></label>
								<input type="hidden" name="current_jenis_pemeriksaan" value="">
								<select class="form-control input-sm" name="jenis_pemeriksaan" id="search_jenis_pemeriksaan"></select>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_sampling_date'); ?></label>
								<input type="text" class="form-control input-sm" name="tgl_sampling" id="tgl_sampling" placeholder="YYYY-MM-DD">
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_identity_patient'); ?>* (NIP/SIM/Passport)</label>
								<input type="text" name="no_identitas" class="form-control input-sm number" required="" placeholder="<?= lang('label_identity_patient'); ?>" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_nationality'); ?>*</label>
								<input type="text" name="kewarganegaraan" class="form-control input-sm" required="" placeholder="<?= lang('label_nationality'); ?>" value="INDONESIA">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_patient_name'); ?> *</label>
								<input type="text" name="nama_lengkap" class="form-control input-sm" required="" placeholder="<?= lang('label_input_patient_name'); ?>" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_gender'); ?> *</label>
								<div class="btn-group">
									<label class="btn btn-xs btn-primary">
										<input type="radio" name="jenis_kelamin" value="Laki-laki"> <?= lang('label_gender_male'); ?>
									</label>
									<label class="btn btn-xs btn-warning">
										<input type="radio" name="jenis_kelamin" value="Perempuan"> Perempuan
									</label>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12"></div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_place_of_birth'); ?> *</label>
								<input type="text" name="tempat_lahir" class="form-control input-sm" required="" placeholder="<?= lang('label_place_of_birth'); ?>" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_date_of_birth'); ?> *</label>
								<input type="text" name="tgl_lahir" class="form-control input-sm" required="" placeholder="YYYY-MM-DD" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label"><?= lang('label_address'); ?> *</label>
								<textarea name="alamat" class="form-control input-sm" required="" placeholder="<?= lang('label_input_address'); ?>" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Hp *</label>
								<input type="text" name="no_hp" class="form-control input-sm" required="" placeholder="No Hp" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Email</label>
								<input type="text" name="email" class="form-control input-sm" placeholder="Email" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Kelompok Pasien/Asuransi</label>
								<select  name="asuransi" id="asuransi" class="form-control input-sm" placeholder="Asuransi" autocomplete="off"></select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">No. Asuransi</label>
								<input type="text" name="no_asuransi" id="no_asuransi" class="form-control input-sm" placeholder="No Asuransi" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 row-perujuk">
							<div class="form-group">
								<label class="form-label">Pengirim/Perujuk</label>
								<select  name="perujuk" id="perujuk" class="form-control input-sm" placeholder="Perujuk" autocomplete="off"></select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 row-perujuk">
							<div class="form-group">
								<label class="form-label">Nama Perujuk</label>
								<input type="text" name="nama_tenaga_perujuk" id="nama_tenaga_perujuk" class="form-control input-sm" placeholder="Nama Perujuk" autocomplete="off">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-pasien" class="btn btn-indigo"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modal-manage-import" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST" name="form-manage-import">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title"><?= lang('label_title_import'); ?></h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="card card-body bg-primary tx-white bd-0">
						<h5 class="card-title tx-white tx-medium mg-b-5">CATATAN:</h5>
						<p class="card-text">
						<ul class="m-0 pl-3">
							<li>Hanya file excel yang dapat diproses untuk import</li>
							<li>Format urutan kolom harus disesuaikan dengan sample yang ada. Jika belum ada silahkan download <a href="<?= base_url('files/docs/Lab-FormatImportDataPasien.xlsx'); ?>" class="tx-warning" target="_blank">disini</a></li>
						</ul>
						</p>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label class="form-label">Format: </label>
							<img src="<?= base_url('assets/img/image-sample-import-pasien.png'); ?>" class="zoomImage" style="max-width: 100%;border: 1px solid #ff0000">
						</div>
					</div>
					<div class="row">
						<hr>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="card border-1">
								<div class="rows">
									<?php
									if (strtolower($C_PV_GROUP) == 'pusat' && conf('lab_enable_select_provider') === TRUE) {
									?>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_select_provider'); ?></label>
												<select class="form-control input-sm" name="provider_id"></select>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="rows">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label"><?= lang('label_choose_file'); ?></label>
										</div>
										<div class="btn btn-xs p-0 btn-import">
											<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_browse'); ?></span></button>
											<input type="file" name="file" class="hide" id="file" accept=".xls,.xlsx">
											<span class="filename"></span>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
										<div class="form-group">
											<label class="form-label">Index Row Data</label>
											<input type="number" class="form-control input-sm" name="start_row" value="7">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 row-error">
							<h5 class="title m-0 pb-2 tx-bold tx-danger"></h5>
							<div class="content" style="height: 170px; overflow-y: auto;"></div>
						</div>
					</div>

				</div>
				<div class="modal-footer inline-block">
					<button type="submit" id="submit-import" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light pull-right" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modal-reg-success" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-regsuccess">
			<div class="modal-content modal-content-demo">
				<div class="modal-header bg-success">
					<h6 class="modal-title tx-white"><?= lang('label_info_register_success'); ?></h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="printarea center-position label-data-pasien" style="border: 1px solid #000;">
						<table width="100%" style="font-size: 14px;padding: 10px;font-weight: bold;width: 400px;">
							<tr class="text-bold" style="font-weight: bold;font-size: 18px">
								<td width="100px">ID</td>
								<td width="10px">:</td>
								<td class="id_pasien"></td>
							</tr>
							<tr class="text-bold">
								<td><?= lang('label_patient_name'); ?></td>
								<td>:</td>
								<td class="nama_lengkap"></td>
							</tr>
							<tr class="text-bold">
								<td><?= lang('label_gender'); ?></td>
								<td>:</td>
								<td class="jenis_kelamin"></td>
							</tr>
							<tr>
								<td><?= lang('label_checkup_date'); ?></td>
								<td>:</td>
								<td class="tgl_periksa"></td>
							</tr>
							<tr>
								<td><?= lang('label_sampling_date'); ?></td>
								<td>:</td>
								<td class="tgl_sampling"></td>
							</tr>
							<tr>
								<td>Provider</td>
								<td>:</td>
								<td class="provider"></td>
							</tr>
							<tr>
								<td><?= lang('label_checkup_type'); ?></td>
								<td>:</td>
								<td class="jenis_pemeriksaan"></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="modal-footer tx-left" style="justify-content: flex-start">
					<button type="button" class="btn btn-print btn-xs btn-default" data-title_print="Print Kode Registrasi" data-printarea=".printarea.label-data-pasien"><i class="fa fa-print"></i> Print</button>
				</div>
			</div>
		</form>
	</div>
</div>

<style>
	table.table tbody tr td {
		vertical-align: top !important;
	}
</style>
