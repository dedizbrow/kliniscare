<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					Data Pasien Klinik
					<?php
					if (isset($import_id)) {
						echo "<input type='hidden' id='import_id' value='" . $import_id . "'><br>
							<div class='tx-primary txt-normal mt-2'>[Data yang ditampilkan adalah yang baru saja anda import] 
							<a class='text-normal tx-danger' href='" . base_url('rawat-jalan/data_pasien') . "' title='Click disini untuk kembali ke keseluruhan data pasien'><sup><i class=''></i> clear filter</sup></a>
							</div>
							
							";
					}
					?>
				</div>


				<div class="pull-right">
					<?php if (isAllowed('c-datapasien^import', true)) { ?>
						<button type="button" class="btn btn-import import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button>
					<?php } ?>
					<?php if (isAllowed('c-datapasien^export', true)) { ?>
						<a href="<?php echo site_url(); ?>rawat-jalan/data_pasien/export_"><button type="button" class="btn btn-warning btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
					<?php } ?>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataPasien" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th><?= lang('label_number'); ?></th>
							<th><?= lang('label_norm'); ?></th>
							<th><?= lang('label_fullname'); ?></th>
							<th><?= lang('label_history'); ?></th>
							<th><?= lang('label_identitynumb'); ?></th>
							<th><?= lang('label_tlp/hp'); ?></th>
							<th><?= lang('label_address'); ?></th>
							<th><?= lang('label_date'); ?></th>
							<th><?= lang('label_user'); ?></th>
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
	<div class="modal-dialog modal-xl modal-pasien" role="document">
		<!-- <div class="row"> -->
		<!-- <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
		<form method="POST" name="form-manage-pasien" class="col-12">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Update Data Pasien</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="card bd-0" style="border:1px solid #32a6ff">
						<!-- <div class="card-header bd-b-0-f pd-b-0 c-header">
						<nav class="nav nav-tabs" style="margin-bottom: -10px;">
							<a class="col-lg-1 col-md-3 col-sm-3 text-center nav-link active" data-toggle="tab" href="#tab_datapasien" id="datapasien">Data Pasien</a>
						</nav>
					</div> -->
						<div class="card-body bd bd-t-0 tab-content">
							<div id="tab_datapasien" class="tab-pane active show">
								<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<div class=" row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="nomor_rm"><?= lang('label_norm'); ?><span class="tx-danger"> *</span></label>
												<div class="d-grid gap-2 d-md-flex">
													<input type="text" class="hide" name="_id" value="">
													<input type="text" id="nomor_rm" name="nomor_rm" class="form-control input-sm" readonly autocomplete="off" placeholder="<?= lang('label_norm_'); ?>">
												</div>
											</div>
										</div>
									</div>
									<div class=" row">
										<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="nama_lengkap"><?= lang('label_fullname'); ?><span class="tx-danger"> *</span></label>
												<input type="text" class="form-control input-sm " id="nama_lengkap" name="nama_lengkap" autocomplete="off" required="" placeholder="<?= lang('label_fullname'); ?>">
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_gender'); ?></label>
												<select name="jenis_kelamin" id="jenis_kelamin" class="form-control input-sm ">
													<option value="" disabled>Pilih</option>
													<option value="Laki-laki">Laki-laki</option>
													<option value="Perempuan">Perempuan</option>
												</select>
											</div>
										</div>
									</div>
									<div class=" row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tempat_lahir"><?= lang('Label_dateplace'); ?></label>
												<input type="text" class="form-control input-sm " id="tempat_lahir" name="tempat_lahir" autocomplete="off" placeholder="<?= lang('Label_dateplace'); ?>">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tgl_lahir"><?= lang('label_birthdate'); ?></label>
												<input type="text" class="form-control input-sm clear" id="tgl_lahir" name="tgl_lahir" autocomplete="off" placeholder="<?= lang('label_birthdate'); ?>">
											</div>
										</div>
										<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="umur"><?= lang('label_age'); ?></label>
												<input type="number" class="form-control input-sm clear" id="umur" name="umur" autocomplete="off" placeholder="<?= lang('label_age'); ?>" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_maritalstatus'); ?></label>
												<select name="status_nikah" id="status_nikah" class="form-control input-sm clear">
													<option value=""></option>
												</select>
											</div>
										</div>

										<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('Label_relligion'); ?></label>
												<select name="agama" id="agama" class="form-control input-sm clear">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_bloodgroup'); ?></label>
												<select name="gol_darah" id="gol_darah" class="form-control input-sm ">
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="kewarganegaraan">Kewarganegaraan</label>
												<input type="text" class="form-control input-sm" id="kewarganegaraan" name="kewarganegaraan" autocomplete="off" placeholder="Kewarganegaraan">
											</div>
										</div>

									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_identity'); ?></label>
												<select name="identitas" id="identitas" class="form-control input-sm">
													<option value="KTP">KTP</option>
													<option value="SIM">SIM</option>
													<option value="Passport">Passport</option>
													<option value="Lainnya">Lainnya</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_identitas"><?= lang('label_identitynumb'); ?></label>
												<input type="number" class="form-control input-sm" id="no_identitas" name="no_identitas" autocomplete="off" placeholder="<?= lang('label_identitynumb'); ?>">
											</div>
										</div>
									</div>

								</section>
								<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12 border-left-dashed">

									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_provincy'); ?></label>
												<select name="provinsi" id="provinsi" class="form-control input-sm">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_city'); ?></label>
												<select name="kabupaten" id="kabupaten" class="form-control input-sm">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_district'); ?></label>
												<select name="kecamatan" id="kecamatan" class="form-control input-sm">
													<option value=''></option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="alamat"><?= lang('label_address'); ?></label>
												<textarea class="form-control" rows="2" cols="100" name="alamat" id="alamat" placeholder="<?= lang('label_address'); ?>"></textarea>

											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_hp"><?= lang('label_hpnum'); ?></label>
												<input class="form-control input-sm" id="no_hp" name="no_hp" autocomplete="off" placeholder="<?= lang('label_hpnum_'); ?>">
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_telp"><?= lang('label_telpnum'); ?></label>
												<input class="form-control input-sm" id="no_telp" name="no_telp" autocomplete="off" placeholder="<?= lang('label_telpnum_'); ?>">
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="email">Email</label>
												<input class="form-control input-sm" id="email" name="email" autocomplete="off" placeholder="contoh@gmail.com">
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="pekerjaan"><?= lang('label_job'); ?></label>
												<input class="form-control input-sm" id="pekerjaan" name="pekerjaan" autocomplete="off" placeholder="<?= lang('label_job'); ?>">
											</div>
										</div>
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="perusahaan"><?= lang('label_company'); ?></label>
												<input class="form-control input-sm " id="perusahaan" name="perusahaan" autocomplete="off" placeholder="<?= lang('label_company'); ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="ibu_kandung"><?= lang('label_mothername'); ?></label>
												<input class="form-control input-sm" id="ibu_kandung" name="ibu_kandung" autocomplete="off" placeholder="<?= lang('label_mothername'); ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_insurance'); ?></label>
												<select name="asuransi_utama" id="asuransi_utama" class="form-control input-sm ">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_asuransi"><?= lang('label_insurancenum'); ?></label>
												<input id="no_asuransi" class="form-control input-sm " name="no_asuransi" autocomplete="off" placeholder="<?= lang('label_insurancenum'); ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<button type="button" class="btn btn-danger float-right" style="margin-left: 10px;" data-dismiss="modal"><?= lang('label_close'); ?></button>
											<button type="submit" id="save-pasien" class="btn btn-indigo float-right"><?= lang('label_save'); ?></button>
										</div>
									</div>
								</section>
							</div><!-- tab-pane -->
						</div>
					</div><!-- card-body -->
				</div>
			</div>
		</form>
		<!-- </section> -->
		<!-- </div> -->
	</div>
</div>

<div id="modal-informasi-pasien" class="modal">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Informasi Pasien</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<table width="100%" class="display table-striped">
						<tr>
							<td>No RM</td>
							<td width="10px">:</td>
							<td class="nomor_rm"></td>
						</tr>
						<tr>
							<td>Nama Lengkap</td>
							<td width="10px">:</td>
							<td class="nama_lengkap"></td>
						</tr>
						<tr>
							<td>Jenis Kelamin</td>
							<td width="10px">:</td>
							<td class="jenis_kelamin"></td>
						</tr>
						<tr>
							<td>Tempat Lahir</td>
							<td width="10px">:</td>
							<td class="tempat_lahir"></td>
						</tr>
						<tr>
							<td>Tgl Lahir</td>
							<td width="10px">:</td>
							<td class="tgl_lahir"></td>
						</tr>
						<tr>
							<td>Umur</td>
							<td width="10px">:</td>
							<td class="umur"></td>
						</tr>
						<tr>
							<td>Status Pernikahan</td>
							<td width="10px">:</td>
							<td class="nama_status_pernikahan"></td>
						</tr>
						<tr>
							<td>Agama</td>
							<td width="10px">:</td>
							<td class="nama_agama"></td>
						</tr>
						<tr>
							<td>Gol. Darah</td>
							<td width="10px">:</td>
							<td class="nama_gol_darah"></td>
						</tr>
						<tr>
							<td>No. Identitas</td>
							<td width="10px">:</td>
							<td class="">[<span class="identitas"></span>] <span class="no_identitas"></span></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<table class="display table-striped" width="100%">
						<tr>
							<td>Alamat</td>
							<td width="10%">:</td>
							<td class="alamat"></td>
						</tr>
						<tr>
							<td>Kecamatan</td>
							<td width="10px">:</td>
							<td class="nama_kecamatan"></td>
						</tr>
						<tr>
							<td>Kota/Kabupaten</td>
							<td width="10px">:</td>
							<td class="nama_kabupaten"></td>
						</tr>
						<tr>
							<td>Provinsi</td>
							<td width="10px">:</td>
							<td class="nama_provinsi"></td>
						</tr>
						<tr>
							<td>No. HP / Telp</td>
							<td width="10px">:</td>
							<td><span class="no_hp"></span> / <span class="no_telp"></span></td>
						</tr>
						<tr>
							<td>Pekerjaan</td>
							<td width="10px">:</td>
							<td class="pekerjaan"></td>
						</tr>
						<tr>
							<td>Perusahaan</td>
							<td width="10px">:</td>
							<td class="perusahaan"></td>
						</tr>
						<tr>
							<td>Nama Ibu Kandung</td>
							<td width="10px">:</td>
							<td class="ibu_kandung"></td>
						</tr>
						<tr>
							<td>Asuransi</td>
							<td width="10px">:</td>
							<td class="nama_asuransi"></td>
						</tr>
						<tr>
							<td>No. Asuransi</td>
							<td width="10px">:</td>
							<td class="no_asuransi"></td>
						</tr>

					</table>
				</div>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>



<div id="modal-history-rm" class="modal">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">History Rekam Medis</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<table>
							<tr>
								<td>Nama Pasien</td>
								<td>:</td>
								<td><b class="nama_pasien"></b></td>
							</tr>
							<tr>
								<td>No. RM</td>
								<td>:</td>
								<td><b class="no_rm"></b></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 display minimize-padding-all">
						<table width="100%" class="display rm" id="tableDetail" cellpadding="6">
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="modal-history-lab" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">History Laboratorium</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<table>
							<tr>
								<td>Nama Pasien</td>
								<td>:</td>
								<td><b class="nama_pasien"></b></td>
							</tr>
							<tr>
								<td>No. RM</td>
								<td>:</td>
								<td><b class="no_rm"></b></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table width="100%" class="display minimize-padding-all rm" id="tableDetailLab" cellpadding="6">
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



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
							<li>Format urutan kolom harus disesuaikan dengan sample yang ada. Jika belum ada silahkan download <a href="<?= base_url('files/docs/M_FormatImportDataPasienKlinik.xlsx'); ?>" class="tx-warning" target="_blank">disini</a></li>
						</ul>
						</p>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<label class="form-label">Format: </label>
							<img src="<?= base_url('assets/img/image-sample-import-pasien-klinik.png'); ?>" class="zoomImage" style="max-width: 100%;border: 1px solid #ff0000">
						</div>
					</div>
					<div class="row">
						<hr>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="card border-1">
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
					<button type="submit" id="submit-import" class="btn btn-indigo"><i class="fa fa-save"></i> Submit</button>
					<button type="button" class="btn btn-outline-light pull-right" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
