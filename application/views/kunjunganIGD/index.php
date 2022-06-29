<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<form method="POST" name="form-manage-kunjunganIGD" class="col-12" id="manage-kunjunganIGD">
			<div class="modal-content modal-content-demo">
				<div class="modal-body" style="padding: 10px;">
					<div class="card bd-0">
						<div class="card-header bd-b-0-f c-card pd-b-0">
							<nav class="nav nav-tabs" style="margin-bottom: -10px;">
								<a class="col-lg-3 col-md-3 col-sm-3 text-center nav-link active" data-toggle="tab" href="#tab_datapasien" id="datapasien">Data Pasien</a>
								<a class="col-lg-3 col-md-3 col-sm-3 text-center nav-link" data-toggle="tab" href="#tab_rujukan" id="rujukan">Rujukan</a>
								<a class="col-lg-3 col-md-3 col-sm-3 text-center nav-link" data-toggle="tab" href="#tab_penanggungjawab" id="penanggungjawab">Penanggung Jawab</a>
								<a class="col-lg-2 col-md-2 col-sm-2 text-center nav-link" data-toggle="tab" href="#tab_triase" id="triase">Triase</a>
							</nav>
						</div><!-- card-header -->
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
									<div class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="dpjp"><?= lang('label_dpjp'); ?><span class="tx-danger"> *</span></label>
												<select name="dpjp" id="dpjp" class="form-control input-sm">
												</select>
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
										<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
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
											<button type="button" class="btn btn-success float-right " onclick="$('#penanggungjawab').trigger('click')" style="margin-left: 10px;"><?= lang('label_next'); ?></button>
											<button type="button" class="btn btn-warning float-right " onclick="$('#datapasien').trigger('click')"><?= lang('label_back'); ?></button>
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
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 ">
											<div class="form-group">
												<label class="form-label"><?= lang('label_gender'); ?></label>
												<select name="jenis_kelamin_pjw" class="form-control input-sm" id="jenis_kelamin_pjw">
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
										<div class="col-lg-5 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="tgl_lahir_pjw"><?= lang('label_birthdate'); ?></label>
												<input class="form-control input-sm " name="tgl_lahir_pjw" autocomplete="off" placeholder="<?= lang('label_birthdate'); ?>">
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
										<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="no_identitas_pjw"><?= lang('label_identitynumb'); ?></label>
												<input class="form-control input-sm " name="no_identitas_pjw" autocomplete="off" placeholder="<?= lang('label_identitynumb'); ?>">
											</div>
										</div>
									</div>

									<div class="row">
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
											<button type="button" class="btn btn-success float-right " onclick="$('#triase').trigger('click')" style="margin-left: 10px;"><?= lang('label_next'); ?></button>
											<button type="button" class="btn btn-warning float-right " onclick="$('#rujukan').trigger('click')"><?= lang('label_back'); ?></button>
										</div>
									</div>
								</section>
							</div>

							<div id="tab_triase" class="tab-pane">
								<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="allergy"><?= lang('label_allergy'); ?></label>
												<textarea class="form-control" rows="2" cols="100" name="alergi" placeholder="<?= lang('label_allergy'); ?>"></textarea>
											</div>
										</div>


										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_triase'); ?></label>
												<div class="btn-group">
													<label class=" btn btn-xs btn-danger triase">
														<input type="radio" name="triase" value="gawat darurat"> Gawat Darurat
													</label>
													<label class="btn btn-xs btn-warning triase">
														<input type="radio" name="triase" value="darurat"> Darurat
													</label>
													<label class="btn btn-xs btn-success">
														<input type="radio" name="triase" value="tidak gawat & darurat"> Tdk Gawat & Tdk Darurat
													</label>
													<label class="btn btn-xs btn-dark triase">
														<input type="radio" name="triase" value="meninggal"> Meninggal
													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label">E</label>
												<div class="input-group mb-3">
													<input type="text" name="e" class="form-control input-sm" autocomplete="off">
												</div>
											</div>
										</div>
										<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label">M</label>
												<div class="input-group mb-3">
													<input type="text" name="m" class="form-control input-sm" autocomplete="off">
												</div>
											</div>
										</div>

										<div class="col-lg-2 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label">V</label>
												<div class="input-group mb-3">
													<input type="text" name="v" class="form-control input-sm" autocomplete="off">
												</div>
											</div>
										</div>
									</div>
								</section>
								<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_pulse'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="nadi" value="teraba"> Teraba
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="nadi" value="tidak teraba"> Tidak Teraba
													</label>
													<input type="radio" checked="checked" name="nadi" value="" style="display:none" />
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_sianosis'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="sianosis" value="ya"> Ya
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="sianosis" value="tidak"> Tidak
													</label>
													<input type="radio" checked="checked" name="sianosis" value="" style="display:none" />
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_crt'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="crt" value="kurang dari 2 detik">
														< 2 Detik </label>
															<label class="btn btn-xs btn-outline-secondary triase">
																<input type="radio" name="crt" value="lebih dari 2 detik"> > 2 Detik
															</label>

															<input type="radio" checked="checked" name="crt" value="" style="display:none" />
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_bleeding'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="pendarahan" value="ya"> Ya
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="pendarahan" value="tidak"> Tidak
													</label>
													<input type="radio" checked="checked" name="pendarahan" value="" style="display:none" />
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_airway'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="jalan_nafas" value="normal"> Normal
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="jalan_nafas" value="tidak"> Tidak Normal
													</label>

													<input type="radio" checked="checked" name="jalan_nafas" value="" style="display:none" />
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_rhythm'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary " style="width: 100px;">
														<input type="radio" name="irama_nafas" value="normal"> Normal
													</label>
													<label class="btn btn-xs btn-outline-secondary" style="width: 100px;">
														<input type="radio" name="irama_nafas" value="cepat"> Cepat
													</label>
													<label class="btn btn-xs btn-outline-secondary " style="width: 100px;">
														<input type="radio" name="irama_nafas" value="dangkal"> Dangkal
													</label>
													<input type="radio" checked="checked" name="irama_nafas" value="" style="display:none" />
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_obstruction'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="obstruksi" value="lidah"> Lidah
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="obstruksi" value="cairan"> Cairan
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="obstruksi" value="benda asing"> Benda Asing
													</label>
													<input type="radio" checked="checked" name="obstruksi" value="" style="display:none" />
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_trauma'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="trauma" value="ya"> Ya
													</label>
													<label class="btn btn-xs btn-outline-secondary triase">
														<input type="radio" name="trauma" value="tidak"> Tidak
													</label>
													<input type="radio" checked="checked" name="trauma" value="" style="display:none" />
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_nontrauma'); ?></label>
												<div class="btn-group">
													<label class="btn btn-xs btn-outline-secondary " style="width: 100px;">
														<input type="radio" name="non_trauma" value="intoksikasi"> Intoksikasi
													</label>
													<label class="btn btn-xs btn-outline-secondary " style="width: 100px;">
														<input type="radio" name="non_trauma" value="gigitan ular"> Gigitan Ular
													</label>
													<label class="btn btn-xs btn-outline-secondary " style="width: 100px;">
														<input type="radio" name="non_trauma" value="Lain-lain"> Lain-lain
													</label>
													<input type="radio" checked="checked" name="non_trauma" value="" style="display:none" />
												</div>
											</div>
										</div>
									</div>
								</section>

								<section class="col-lg-6 col-md-8 col-sm-12 col-xs-12 border-left-dashed border-top-dashed  ">
									<div class="row" style="margin-bottom: 25px;">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<img src="<?= base_url('assets/img/nyeri.jpg'); ?>" class="zoomImage" style="max-width: 80%;border: 1px solid #ff0000">
										</div>
										<div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label">Pengkajian Nyeri</label>
												<input type="text" class="form-control input-sm " name="nyeri" autocomplete="off" placeholder="1 sampai 10">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_sistole'); ?></label>
												<div class="input-group mb-3">
													<input type="text" name="sistole" class="form-control input-sm" placeholder="0" autocomplete="off" aria-label="Recipient's username" aria-describedby="basic-addon2">
													<span class="input-group-text">mm/Hg</span>
												</div>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_diastole'); ?></label>
												<div class="input-group mb-3">
													<input type="text" name="diastole" class="form-control input-sm" placeholder="0" autocomplete="off" aria-label="Recipient's username" aria-describedby="basic-addon2">
													<span class="input-group-text">mm/Hg</span>
												</div>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_pulse'); ?></label>
												<div class="input-group mb-3">
													<input type="text" name="derajat_nadi" class="form-control input-sm" placeholder="0" autocomplete="off" aria-label="Recipient's username" aria-describedby="basic-addon2">
													<span class="input-group-text">x/i</span>
												</div>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="suhu_tubuh"><?= lang('label_bodytemp'); ?></label>
												<div class="input-group mb-3">
													<input type="text" name="suhu_tubuh" class="form-control input-sm" placeholder="0" autocomplete="off" aria-label="Recipient's username" aria-describedby="basic-addon2">
													<span class="input-group-text">⁰C</span>
												</div>
											</div>
										</div>
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="pernafasan"><?= lang('label_breathing'); ?></label>
												<div class="input-group mb-3">
													<input type="text" id="pernafasan" name="pernafasan" class="form-control input-sm" placeholder="0" autocomplete="off" aria-label="Recipient's username" aria-describedby="basic-addon2">
													<span class="input-group-text">x/i</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label" for="anamnesa"><?= lang('label_anamnesa'); ?></label>
												<textarea class="form-control" rows="2" cols="100" name="anamnesa" placeholder="<?= lang('label_anamnesa'); ?>"></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="form-label"><?= lang('label_diagnosa'); ?></label>
												<select name="fk_diagnosa[]" class="form-control" multiple id="select_diagnosa">
												</select>
											</div>
										</div>
									</div>


									<div class="row">
										<div class="col">
											<button type="submit" id="save-kunjunganigd" class="btn btn-primary float-right" style="margin-left: 10px;">Simpan</button>
											<button type="button" class="btn btn-warning float-right " onclick="$('#penanggungjawab').trigger('click')">Kembali</button>
										</div>
									</div>
								</section>
							</div>
						</div><!-- card-body -->
					</div><!-- card -->
				</div>
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

<div id="modal-manage-data-ruangan" class="modal">
	<!-- modal pilih ruangan-->
	<div class="modal-dialog modal-xl" role="document">
		<form method="POST">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pilih ruangan</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<section class="col-lg-12 ">
						<div class="card card-dashboard-one">
							<div class="card-header border" style="background: #32a6ff">
							</div>
							<div class="card-body">
								<table class="table table-bordered dataTable table-striped minimize-padding-all" id="dataRuangan" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%;" cellspacing="0" style="height:40px;">
									<thead>
										<tr>
											<th><?= lang('label_name'); ?></th>
											<th><?= lang('label_class'); ?></th>
											<th><?= lang('label_category'); ?></th>
											<th><?= lang('label_room_number'); ?></th>
											<th><?= lang('label_price'); ?></th>
											<th><?= lang('label_status'); ?></th>
											<th><?= lang('label_action'); ?></th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</section>
				</div>
			</div>
		</form>
	</div>
</div>
