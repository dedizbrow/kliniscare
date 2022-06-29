<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header c-header">
				<div class="card-title">
					Antrian Pemeriksaan
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataAntrian" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th><?= lang('label_number'); ?></th>
							<th><?= lang('label_check'); ?></th>
							<th><?= lang('label_invoice'); ?></th>
							<th><?= lang('label_queue'); ?></th>
							<th><?= lang('label_type'); ?></th>
							<th><?= lang('label_insurance'); ?></th>
							<th><?= lang('label_insurance_number'); ?></th>
							<th><?= lang('label_rm_number'); ?></th>
							<th><?= lang('label_name'); ?></th>
							<th><?= lang('label_address'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>
<div id="modal-manage-periksa" class="modal">
	<div class="modal-dialog modal-xl modal-pasien" role="document">
		<div class="row">
			<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<form method="POST" name="form-manage-periksa" class="col-12">
					<div class="modal-content modal-content-demo">
						<div class="modal-header">
							<h6 class="modal-title">Pemeriksaan pasien</h6>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="card bd-0">
								<div class="card-header bd-b-0-f c-card pd-b-0">
									<nav class="nav nav-tabs" style="margin-bottom: -10px;">
										<a class="col-lg-1 col-md-3 col-sm-3 text-center nav-link active" data-toggle="tab" href="#tab_datapasien" id="datapasien">Pemeriksaan</a>
										<a class="col-lg-2 col-md-4 col-sm-4 text-center nav-link" data-toggle="tab" href="#tab_diagnosa" id="diagnosa">Diagnosis & Tindakan</a>
									</nav>
								</div>
								<div class="card-body bd bd-t-0 tab-content">
									<div id="tab_datapasien" class="tab-pane active show">
										<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
											<div class=" row">
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<input type="text" class="hide" name="_id" value="">
														<input type="text" class="hide" name="_id_antrian" value="">
														<label class="form-label" for="nama_lengkap"><?= lang('label_fullname'); ?><span class="tx-danger"> *</span></label>
														<input type="text" class="form-control input-sm " id="nama_lengkap" name="nama_lengkap" autocomplete="off" required="" placeholder="<?= lang('label_fullname'); ?>">
													</div>
												</div>
												<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="nomor_rm"><?= lang('label_nomor_rm'); ?></label>
														<input type="text" class="form-control input-sm " id="nomor_rm" name="nomor_rm" autocomplete="off" placeholder="<?= lang('label_nomor_rm'); ?>">
													</div>
												</div>
											</div>
											<div class=" row">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('Label_penanggung_jawab'); ?></label>
														<input type="text" class="form-control input-sm " name="namaDokter" autocomplete="off" readonly>
													</div>
												</div>
												<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_layanan'); ?></label>
														<input type="text" class="form-control input-sm " name="namaPoli" autocomplete="off" readonly>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_kesadaran'); ?></label>
														<select name="kesadaran" id="kesadaran" class="form-control input-sm ">
															<option value="compos entis">Compos Mentis</option>
															<option value="somnolence">Somnolence</option>
															<option value="sopor">Sopor</option>
															<option value="coma">Coma</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="anamnesa"><?= lang('label_anamnesis'); ?></label>
														<textarea class="form-control" rows="2" cols="100" name="anamnesa" id="anamnesa" placeholder="<?= lang('label_anamnesis'); ?>"></textarea>

													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="pemeriksaan_umum"><?= lang('label_pemeriksaan_umum'); ?></label>
														<textarea class="form-control" rows="2" cols="100" name="pemeriksaan_umum" id="pemeriksaan_umum" placeholder="<?= lang('label_pemeriksaan_umum'); ?>"></textarea>

													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="alergi"><?= lang('label_alergi'); ?></label>
														<textarea class="form-control" rows="2" cols="100" name="alergi" id="alergi" placeholder="<?= lang('label_alergi'); ?>"></textarea>
													</div>
												</div>
											</div>
										</section>
										<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12 border-left-dashed">
											<div class="row">
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_sistole'); ?></label>
														<div class="input-group">
															<input type="text" name="sistole" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">mm/Hg</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_diastole'); ?></label>
														<div class="input-group mb-3">
															<input type="text" name="diastole" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">mm/Hg</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_tensi'); ?></label>
														<div class="input-group mb-3">
															<input type="text" name="tensi" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">mm/Hg</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_pulse'); ?></label>
														<div class="input-group mb-3">
															<input type="text" name="derajat_nadi" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">ppm</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="nafas"><?= lang('label_breathing'); ?></label>
														<div class="input-group mb-3">
															<input type="text" name="nafas" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">bpm</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="suhu_tubuh"><?= lang('label_bodytemp'); ?></label>
														<div class="input-group mb-3">
															<input type="text" name="suhu_tubuh" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">⁰C</span>
														</div>
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="saturasi">Saturasi</label>
														<div class="input-group mb-3">
															<input type="text" name="saturasi" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">mmHg</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="bb"><?= lang('label_bb'); ?></label>
														<div class="input-group mb-3">
															<input type="text" id="bb" name="bb" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">Kg</span>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="tb"><?= lang('label_tb'); ?></label>
														<div class="input-group mb-3">
															<input type="text" id="tb" name="tb" class="form-control input-sm" placeholder="0" autocomplete="off">
															<span class="input-group-text">Cm</span>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="catatan_dokter"><?= lang('label_catatan'); ?></label>
														<textarea class="form-control" rows="2" cols="100" name="catatan_dokter" id="catatan_dokter" placeholder="<?= lang('label_catatan'); ?>"></textarea>

													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
													<img src="<?= base_url('assets/img/nyeri.jpg'); ?>" class="zoomImage" style="max-width: 80%;border: 1px solid #ff0000">
												</div>
												<div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('label_nyeri'); ?></label>
														<input type="text" class="form-control input-sm " name="nyeri" autocomplete="off" placeholder="1 sampai 10">
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col">
													<button type="button" class="btn btn-success float-right " onclick="$('#diagnosa').trigger('click')" style="margin-left: 10px;"><?= lang('label_next'); ?></button>
												</div>
											</div>

										</section>
									</div>
									<div id="tab_diagnosa" class="tab-pane">
										<div class="row">
											<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 30px;">
												<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
														<label class="form-label"><?= lang('label_doctor'); ?></label>
													</div>
													<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
														<label class="form-label"><?= lang('label_diagnosa'); ?></label>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
														<div class="form-group">
															<select name="fk_dokter" class="form-control input-sm" id="select_dokter" required>
																<option value=''></option>
															</select>
														</div>
													</div>
													<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
														<div class="form-group">
															<select name="fk_diagnosa[]" class="form-control" multiple id="select_diagnosa" required>
															</select>
														</div>
													</div>
												</div>
											</section>

											<section class="col-lg-6 col-md-12 col-sm-12 col-xs-12 border-left-dashed">
												<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
														<label class="form-label"><?= lang('label_doctor'); ?></label>
													</div>
													<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
														<label class="form-label"><?= lang('label_tindakan'); ?></label>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
														<div class="form-group">
															<select name="fk_dokter_tindakan" class="form-control input-sm" id="select_dokter_tindakan" required>
																<option value=''></option>
															</select>
														</div>
													</div>
													<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
														<div class="form-group">
															<select name="fk_tindakan[]" class="form-control" multiple id="select_tindakan" required>
															</select>
														</div>
													</div>
												</div>
											</section>
										</div>

										<div class="row">
											<div class="col">

												<button type="submit" id="save-pemeriksaan" class="btn btn-primary float-right" style="margin-left: 10px;"><?= lang('label_save'); ?></button>
												<button type="button" class="btn btn-warning float-right " onclick="$('#datapasien').trigger('click')"><?= lang('label_back'); ?></button>
											</div>
										</div>
									</div>
								</div><!-- card-body -->
							</div><!-- card -->
						</div>
				</form>
			</section>
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
