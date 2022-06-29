<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<form method="POST" name="form-manage-pendaftaran" class="col-12" id="manage-pendaftaran">
			<div class="modal-content modal-content-demo">
				<div class="modal-body" style="padding: 10px;">
					<div class="card bd-0 ">
						<div class="card-header bd-b-0-f c-card pd-b-0">
							<nav class="nav nav-tabs" style="margin-bottom: -10px;">
								<a class="col-lg-3 col-md-3 col-sm-3 text-center nav-link active" data-toggle="tab" href="#tab_datapasien" id="datapasien">Data Pasien</a>
								<a class="col-lg-3 col-md-3 col-sm-3 text-center nav-link" data-toggle="tab" href="#tab_rujukan" id="rujukan">Rujukan</a>
								<a class="col-lg-2 col-md-2 col-sm-2 text-center nav-link" data-toggle="tab" href="#tab_antrian" id="antrian">Antrian</a>
								<a class="col-lg-3 col-md-3 col-sm-3 text-center nav-link" data-toggle="tab" href="#tab_penanggungjawab" id="penanggungjawab">Penanggung Jawab</a>
							</nav>
						</div>
						<div class="card-body bd bd-t-0 tab-content">
							<div id="tab_datapasien" class="tab-pane active show">
								<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<div class=" row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="nomor_rm"><?= lang('label_norm'); ?><span class="tx-danger"> *</span></label>
												<div class="d-grid gap-2 d-md-flex">
													<input type="text" class="hide" name="_id" value="">
													<input type="text" id="nomor_rm" name="nomor_rm" class="form-control input-sm" autocomplete="off" placeholder="<?= lang('label_norm_'); ?>" readonly>
													<button type="button" name="autocode" id="autocode" class="btn btn-primary btn-block btn-xs" style="width: 70px;">Auto</button>
												</div>
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<button type="button" class="btn btn-primary btn-block btn-xs cari-pasien" style="margin-top:23px; height:31px; width: 170px;">Ambil data pasien</button>
											</div>
										</div>
									</div>
									<div class=" row">
										<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="nama_lengkap"><?= lang('label_fullname'); ?><span class="tx-danger"> *</span></label>
												<input type="text" class="form-control input-sm " id="nama_lengkap" name="nama_lengkap" autocomplete="off" placeholder="<?= lang('label_fullname'); ?>">
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
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
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tempat_lahir"><?= lang('Label_dateplace'); ?></label>
												<input type="text" class="form-control input-sm " id="tempat_lahir" name="tempat_lahir" autocomplete="off" placeholder="<?= lang('Label_dateplace'); ?>">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tgl_lahir"><?= lang('label_birthdate'); ?></label>
												<input type="text" class="form-control input-sm " id="tgl_lahir" name="tgl_lahir" autocomplete="off" placeholder="<?= lang('label_birthdate'); ?>">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="umur"><?= lang('label_age'); ?></label>
												<input type="number" class="form-control input-sm " id="umur" name="umur" autocomplete="off" readonly placeholder="<?= lang('label_age'); ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_maritalstatus'); ?></label>
												<select name="status_nikah" id="status_nikah" class="form-control input-sm ">
													<option value=""></option>
												</select>
											</div>
										</div>

										<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('Label_relligion'); ?></label>
												<select name="agama" id="agama" class="form-control input-sm ">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_bloodgroup'); ?></label>
												<select name="gol_darah" id="gol_darah" class="form-control input-sm ">
													<option value=""></option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_identity'); ?></label>
												<select name="identitas" id="identitas" class="form-control input-sm">
													<option value="" disabled>Pilih</option>
													<option value="KTP">KTP</option>
													<option value="SIM">SIM</option>
													<option value="Passport">Passport</option>
													<option value="Lainnya">Lainnya</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-7 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_identitas"><?= lang('label_identitynumb'); ?><span class="tx-danger"> *</span></label>
												<input type="text" class="form-control input-sm" id="no_identitas" name="no_identitas" autocomplete="off" placeholder="<?= lang('label_identitynumb'); ?>">
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
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_hp"><?= lang('label_hpnum'); ?></label>
												<input class="form-control input-sm" id="no_hp" name="no_hp" autocomplete="off" placeholder="<?= lang('label_hpnum_'); ?>">
											</div>
										</div>
										<div class="col-lg-7 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_telp"><?= lang('label_telpnum'); ?></label>
												<input class="form-control input-sm" id="no_telp" name="no_telp" autocomplete="off" placeholder="<?= lang('label_telpnum_'); ?>">
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="pekerjaan"><?= lang('label_job'); ?></label>
												<input class="form-control input-sm" id="pekerjaan" name="pekerjaan" autocomplete="off" placeholder="<?= lang('label_job'); ?>">
											</div>
										</div>
										<div class="col-lg-7 col-md-4 col-sm-12 col-xs-12">
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
											<button type="button" class="btn btn-success float-right " onclick="$('#rujukan').trigger('click')" style="margin-left: 10px;"><?= lang('label_next'); ?></button>
										</div>
									</div>
								</section>
							</div><!-- tab-pane -->
							<div id="tab_rujukan" class="tab-pane">
								<section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_RefAgen'); ?></label>
												<select name="perujuk" id="perujuk" class="form-control input-sm ">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="nama_tenaga_perujuk"><?= lang('label_refname'); ?></label>
												<input id="nama_tenaga_perujuk" class="form-control input-sm " name="nama_tenaga_perujuk" autocomplete="off" placeholder="<?= lang('label_refname'); ?>">
											</div>
										</div>
									</div>
								</section>
								<section class="col-lg-6 col-md-6 col-sm-12 col-xs-12 border-left-dashed">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="alasan_datang"><?= lang('label_reason'); ?></label>
												<textarea class="form-control" rows="2" cols="100" name="alasan_datang" id="alasan_datang" placeholder="<?= lang('label_reason_'); ?>"></textarea>
											</div>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="keterangan"><?= lang('label_info'); ?></label>
												<textarea class="form-control" rows="3" cols="100" name="keterangan" id="keterangan" placeholder="<?= lang('label_info'); ?>"></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<button type="button" class="btn btn-success float-right " onclick="$('#antrian').trigger('click')" style="margin-left: 10px;"><?= lang('label_next'); ?></button>
											<button type="button" class="btn btn-warning float-right " onclick="$('#datapasien').trigger('click')"><?= lang('label_back'); ?></button>
										</div>
									</div>
								</section>
							</div>
							<div id="tab_antrian" class="tab-pane">
								<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_visittype'); ?><span class="tx-danger"> *</span></label>
												<select name="jenis_kunjungan" id="jenis_kunjungan" class="form-control input-sm">
													<option value="Kunjungan Sakit">Kunjungan Sakit</option>
													<option value="Kunjungan Sehat">Kunjungan Sehat</option>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="dpjp"><?= lang('label_dpjp'); ?><span class="tx-danger"> *</span></label>
												<select name="dpjp" id="dpjp" class="form-control input-sm">
													<option value=''></option>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_clinic'); ?><span class="tx-danger"> *</span></label>
												<select name="poli" id="poli" class="form-control input-sm">
													<option value=''></option>
												</select>
												<input type="hidden" name="poli_label" id="poli_label">
											</div>
										</div>
										<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_queuenum'); ?><span class="tx-danger"> *</span></label>
												<input type="text" name="nomor_antrian" class="form-control input-sm" required="" autocomplete="off" readonly>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">

											<button type="button" class="btn btn-success float-right " onclick="$('#penanggungjawab').trigger('click')" style="margin-left: 10px;"><?= lang('label_next'); ?></button>
											<button type="button" class="btn btn-warning float-right " onclick="$('#rujukan').trigger('click')"><?= lang('label_back'); ?></button>
										</div>
									</div>
								</section>
							</div>
							<div id="tab_penanggungjawab" class="tab-pane">
								<section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="row ">

										<div class="parsley-checkbox col-lg-6 col-md-4 col-sm-12 col-xs-12">
											<label class="ckbox mg-b-5">
												<input type="checkbox" id="penanggung_jawab" name="penanggung_jawab" value="sendiri"><span class="tx-danger">Ceklist Jika Datang Sendiri</span>
											</label>
										</div>
									</div>
									<div class="row " style="margin-top: 10px;">
										<div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="nama_lengkap_pjw"><?= lang('label_penanggungjawab'); ?><span class="tx-danger"> *</span></label>
												<input class="form-control input-sm" name="nama_lengkap_pjw" autocomplete="off" placeholder="<?= lang('label_penanggungjawab'); ?>">
											</div>
										</div>
									</div>
									<div class="row ">

										<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 ">
											<div class="form-group">
												<label class="form-label"><?= lang('label_gender'); ?></label>
												<select name="jenis_kelamin_pjw" id="jenis_kelamin_pjw" class="form-control input-sm">
													<option value="" disabled>Pilih</option>
													<option value="Laki-laki">Laki-laki</option>
													<option value="Perempuan">Perempuan</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row ">
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tempat_lahir_pjw"><?= lang('Label_dateplace'); ?></label>
												<input class="form-control input-sm " name="tempat_lahir_pjw" autocomplete="off" placeholder="<?= lang('Label_dateplace'); ?>">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tgl_lahir_pjw"><?= lang('label_birthdate'); ?></label>
												<input class="form-control input-sm " id="tgl_lahir_pjw" name="tgl_lahir_pjw" autocomplete="off" placeholder="<?= lang('label_birthdate'); ?>">
											</div>
										</div>
									</div>
								</section>
								<section class="col-lg-6 col-md-6 col-sm-12 col-xs-12 border-left-dashed">
									<div class="row ">
										<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_identity'); ?></label>
												<select name="identitas_pjw" id="identitas_pjw" class="form-control input-sm ">
													<option value="" disabled>Pilih</option>
													<option value="KTP">KTP</option>
													<option value="SIM">SIM</option>
													<option value="Passport">Passport</option>
													<option value="Lainnya">Lainnya</option>
												</select>
											</div>
										</div>
										<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_identitas_pjw"><?= lang('label_identitynumb'); ?></label>
												<input class="form-control input-sm " name="no_identitas_pjw" autocomplete="off" placeholder="<?= lang('label_identitynumb'); ?>">
											</div>
										</div>
									</div>

									<div class="row ">
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_hp_pjw"><?= lang('label_hpnum'); ?></label>
												<input class="form-control input-sm" name="no_hp_pjw" autocomplete="off" placeholder="<?= lang('label_hpnum_'); ?>">
											</div>
										</div>
										<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_telp_pjw"><?= lang('label_telpnum'); ?></label>
												<input class="form-control input-sm" name="no_telp_pjw" autocomplete="off" placeholder="<?= lang('label_telpnum_'); ?>">
											</div>
										</div>
									</div>
									<div class="row ">
										<div class="col-lg-10 col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="alamat_pjw"><?= lang('label_address'); ?></label>
												<textarea class="form-control" rows="2" cols="100" name="alamat_pjw" placeholder="<?= lang('label_address'); ?>"></textarea>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col">
											<button type="submit" id="save-pendaftaran" class="btn btn-primary float-right" style="margin-left: 10px;"><?= lang('label_save'); ?></button>
											<button type="button" class="btn btn-warning float-right " onclick="$('#antrian').trigger('click')"><?= lang('label_back'); ?></button>
										</div>
									</div>
								</section>
							</div>
						</div>
					</div><!-- card-body -->
				</div><!-- card -->
			</div>
		</form>
	</section>
</div>
<div id="modal-manage-data-pasien" class="modal">
	<!-- modal pilih pasien-->
	<div class="modal-dialog modal-xl" role="document">
		<form method="POST">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pilih data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card card-dashboard-one">
					<div class="card-body">
						<table class="table table-bordered dataTable table-striped minimize-padding-all" id="dataPasien" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%;" cellspacing="0" style="height:40px;">
							<thead>
								<tr>
									<th><?= lang('label_norm'); ?></th>
									<th><?= lang('label_patientname'); ?></th>
									<th><?= lang('label_identitynumb'); ?></th>
									<th><?= lang('label_hpnum'); ?></th>
									<th><?= lang('label_address'); ?></th>
									<th><?= lang('label_action'); ?></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
