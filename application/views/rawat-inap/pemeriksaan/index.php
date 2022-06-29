<body onload="load_data_temp()"></body>
<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header-large">
				<div class="card-title">
					Pemeriksaan Pasien IGD
				</div>
				<div class="ml-3 mr-3 tx-center label-filter ">
					<label class="ftr align-middle">
						<select class="form-control input-sm inline-block" style="width: 150px; display: inline-block;" name="filter_status_rawat">
							<option value="">Status Rawat</option>
							<option value="2">Sedang Dirawat</option>
							<option value="3">Telah Checkout</option>
						</select>

					</label>
					<label class="ftr align-middle">
						<select class="form-control input-sm inline-block" style="width: 170px; display: inline-block;" name="filter_status_cout">
							<option value="">Status Checkout</option>
							<option value="Sembuh">Sembuh</option>
							<option value="Meninggal">Meninggal</option>
							<option value="Permintaan pasien">Permintaan Pasien</option>
							<option value="Dirujuk">Dirujuk</option>
						</select>

					</label>
					<label class="ftr align-middle">Tgl. Kunjungan
						<input type="text" name="start_date" class="form-control input-sm" autocomplete="off" placeholder="YYYY-MM-DD" style="width: 90px; display: inline-block">
						s/d
						<input type="text" name="end_date" class="form-control input-sm" autocomplete="off" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
					</label>

				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="pemeriksaanIGD" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th>No.</th>
							<th>Periksa</th>
							<th>Status</th>
							<th>Tindakan</th>
							<th>No. Invoice</th>
							<th>No. RM</th>
							<th>Nama Pasien</th>
							<th>Alamat</th>
							<th>Tgl. Kunjungan</th>
							<th>Penanggung Jawab</th>
							<th>No. HP Pen.Jawab</th>
							<th>Status Checkout</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="card-footer">
				<label>Keterangan:</label>
				<ul class="desc">
					<li><b class="text-success"> Button Resep </b> jika pasien boleh pulang dan / mendapatkan resep.</li>
					<li><b class="text-primary"> Button ruangan </b> untuk merubah status pasien ke Rawat Inap. <b class="text-danger"> Button Checkout </b> jike pasien dinyatakan keluar dari rawat inap dalam kondisi tertentu<br></li>

					<li>Print surat meninggal hanya jika pasien keluar dalam keadaan meninggal.</li>
				</ul>
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
								<div class="card-header bd-b-0-f pd-b-0 c-card">
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
														<input type="text" class="hide" name="id_pendaftaran" value="">
														<label class="form-label" for="nama_lengkap"><?= lang('label_fullname'); ?><span class="tx-danger"> *</span></label>
														<input type="text" class="form-control input-sm " id="nama_lengkap" name="nama_lengkap" readonly autocomplete="off" required="" placeholder="<?= lang('label_fullname'); ?>">
													</div>
												</div>
												<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
													<div class="form-group">
														<label class="form-label" for="nomor_rm"><?= lang('label_nomor_rm'); ?></label>
														<input type="text" class="form-control input-sm " id="nomor_rm" name="nomor_rm" readonly autocomplete="off" placeholder="<?= lang('label_nomor_rm'); ?>">
													</div>
												</div>
											</div>
											<div class=" row">
												<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label"><?= lang('Label_penanggung_jawab'); ?></label>
														<input type="text" class="form-control input-sm " name="namaDokter" autocomplete="off" readonly placeholder="<?= lang('Label_penanggung_jawab'); ?>">
													</div>
												</div>
												<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">

													<div class="form-group">
														<label class="form-label"><?= lang('label_layanan'); ?><span class="tx-danger"> *</span></label>
														<select name="poli" id="poli" class="form-control input-sm">
															<option value=''></option>
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
												<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
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
															<select name="fk_dokter" class="form-control input-sm" id="select_dokter">
																<option value=''></option>
															</select>
														</div>
													</div>
													<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
														<div class="form-group">
															<select name="fk_diagnosa[]" class="form-control" multiple id="select_diagnosa">
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
															<select name="fk_dokter_tindakan" class="form-control input-sm" id="select_dokter_tindakan">
																<option value=''></option>
															</select>
														</div>
													</div>
													<div class="col-lg-8 col-md-4 col-sm-12 col-xs-12">
														<div class="form-group">
															<select name="fk_tindakan[]" class="form-control" multiple id="select_tindakan">
															</select>
														</div>
													</div>
												</div>
											</section>
										</div>
										<div class="row">
											<div class="col">

												<button type="submit" id="save-pemeriksaan" class="btn btn-primary float-right" style="margin-left: 10px;"> <?= lang('label_save'); ?></button>
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

<div id="modal-manage-pilih-ruangan" class="modal">
	<!-- modal pilih ruangan-->
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pilih ruangan</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card-body">
					<table class="table-bordered table-striped minimize-padding-all" id="dataRuangan" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%;" cellspacing="0" style="height:40px;">
						<thead>
							<tr>
								<th><?= lang('label_category'); ?></th>
								<th><?= lang('label_class'); ?></th>
								<th><?= lang('label_name'); ?></th>
								<th>No. Kamar</th>
								<th>No. Ranjang</th>
								<th><?= lang('label_price'); ?></th>
								<th><?= lang('label_action'); ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>


<div id="modal-manage-resep-dokter" class="modal">
	<div class="modal-dialog modal-pasien" role="document">
		<form method="POST" name="form-manage-resep">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Resep dokter </h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<section class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<table width="100%" class="display">
								<tr>
									<td>No Invoice</td>
									<td>:</td>
									<td class="no_invoice"></td>
								</tr>
								<tr>
									<td>Tgl Registrasi</td>
									<td>:</td>
									<td class="create_at"></td>
								</tr>
							</table>
						</section>
						<section class="col-lg-6 col-md-6 col-sm-12 col-xs-12 border-left-dashed">
							<table width="100%" class="display">

								<tr>
									<td>No Rekam Medis</td>
									<td>:</td>
									<td class="nomor_rm"></td>
								</tr>
								<tr>
									<td>Nama Pasien</td>
									<td>:</td>
									<td class="nama_lengkap"></td>
								</tr>
								<tr>
									<td>Dokter</td>
									<td>:</td>
									<td class="namaDokter"></td>
								</tr>
							</table>
						</section>
					</div>

					<div class="row">
						<section class="col-md-12 border-top-dashed">
							<div class="row">

								<div class="col-sm-12">
									<div class="row">

										<div class="col-sm-12">
											<button type="button" id="cariobat" class="btn btn-secondary btn-block btn-xs cariobat" style="width: 80px;">Cari Obat <i class="fa fa-search"></i></button>

											<div class="row">
												<div class="col-lg-2 col-md-1 col-sm-12 col-xs-12">
													<label class="form-label" style="font-size: 9px;">Nama</label>
													<input type="text" name="nama" class="form-control input-sm clear" autocomplete="off">
													<input type="hidden" name="id_obat" class="form-control input-sm clear" autocomplete="off">
												</div>


												<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
													<label class="form-label" style="font-size: 9px;">Satuan</label>
													<div class="d-grid d-md-flex ">
														<select id="satuanobat" class="form-control" name="satuanobat" style="width:100%;">
															<option value=""></option>
														</select>
													</div>
												</div>
												<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
													<label class="form-label" style="font-size: 9px;">QTY</label>
													<input type="number" step="1" name="qty" class="form-control input-sm clear" autocomplete="off">
												</div>
												<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
													<label class="form-label" style="font-size: 9px;">Harga</label>
													<input type="hidden" name="isi" class="form-control input-sm clear" autocomplete="off">
													<input type="text" name="harga" class="form-control input-sm" autocomplete="off" readonly>
												</div>
												<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
													<label class="form-label" style="font-size: 9px;">Total</label>
													<input type="number" name="total" class="form-control input-sm" readonly>
												</div>

												<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" style="font-size: 9px;">Aturan Pakai</label>
														<div class="d-grid gap-2 d-md-flex ">
															<select id="aturan_pakai" class="form-control select2-no-search" name="aturan_pakai" style="width:100%;">
																<option value=''></option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
													<div class="form-group">
														<label class="form-label" style="font-size: 9px;">Cara Pakai</label>
														<div class="d-grid gap-2 d-md-flex ">
															<select id="cara_pakai" class="form-control select2-no-search" name="cara_pakai" style="width:100%;">
																<option value=''></option>
															</select>
														</div>
													</div>
												</div>
												<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
													<div class="input-group" style="margin-top: 18px;">
														<button type="button" name="add" id="addBarang" onclick="add_barang()" class="btn btn-secondary btn-block btn-xs">Add</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>

					<div class="row">
						<section class="col-lg-12">
							<div class="card card-dashboard-one">
								<!-- <div class="card-header border c-header">
								</div> -->
								<div class="card-body" id="dataDaftarBarang">
								</div>
							</div>
						</section>
					</div>

					<div class="row">
						<section class="col-md-12 border-top-dashed">
							<div class="row">
								<div class="col-sm-2">
									<div class="form-group">
										<label class="form-label">Total</label>
										<input type="text" name="subtotal" class="form-control input-xl" autocomplete="off" readonly>
									</div>
								</div>
							</div>

							<input type="text" class="hide" name="id_pendaftaran" value="">
						</section>
					</div>


				</div>
				<div class="modal-footer">
					<button type="submit" id="save-resep" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-manage-pilih-obat" class="modal">
	<!-- modal pilih obat-->
	<div class="modal-dialog modal-xl" role="document">
		<form method="POST" name="form-manage-pilih-obat">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pilih data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card card-dashboard-one">
					<div class="card-body">
						<table class="table table-bordered dataTable table-striped minimize-padding-all" id="dataObat" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%;" cellspacing="0" style="height:40px;">
							<thead>
								<tr>
									<th>Kode Obat</th>
									<th>Nama Obat</th>
									<th>Kategori</th>
									<th>Satuan</th>
									<th>Harga Beli</th>
									<th>Harga Jual</th>
									<th>Stok</th>
									<th>Supplier</th>
									<th>Action</th>
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
<div id="modal-manage-checkin-ruangan" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-checkin-ruangan">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pasien rawat inap</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal-body">
						<div class="row">
							<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label"><?= lang('label_fullname'); ?><span class="tx-danger"> *</span></label>
											<input type="text" class="form-control input-sm " name="nama_lengkap" readonly autocomplete="off" required="" placeholder="<?= lang('label_fullname'); ?>">
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label"><?= lang('label_nomor_rm'); ?></label>
											<input type="text" class="form-control input-sm " name="nomor_rm" readonly autocomplete="off" placeholder="<?= lang('label_nomor_rm'); ?>">
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label">No Invoice<span class="tx-danger"> *</span></label>
											<input type="text" class="form-control input-sm " name="no_invoice" readonly>
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label">Tanggal Pendaftaran</label>
											<input type="text" class="form-control input-sm " name="create_at" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label">Ruangan</label>
											<div class="d-grid gap-2 d-md-flex ">
												<input type="text" class="hide" name="id_pendaftaran" value="">
												<input type="text" class="hide" name="_idRuangan" value="">
												<input type="text" id="namaRuangan" name="namaRuangan" class="form-control input-sm" autocomplete="off" readonly="readonly">
												<button type="button" id="cariruangan" class="btn btn-primary btn-block btn-xs" style="width: 110px;">Cari Ruangan</button>
											</div>
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label">Kelas Ruangan</label>
											<input type="text" class="form-control input-sm " name="namaKelas" readonly>
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label">Tarif Ruangan</label>
											<input type="text" class="form-control input-sm " name="tarif" readonly>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="save-checkin" class="btn btn-primary"> <?= lang('label_save'); ?></button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
					</div>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->



<div id="modal-manage-checkout-ruangan" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-checkout-ruangan">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pasien rawat inap</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="modal-body">
						<div class="row">
							<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label"><?= lang('label_fullname'); ?><span class="tx-danger"> *</span></label>
											<input type="text" class="form-control input-sm " name="nama_lengkap" readonly autocomplete="off" required="" placeholder="<?= lang('label_fullname'); ?>">
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label"><?= lang('label_nomor_rm'); ?></label>
											<input type="text" class="form-control input-sm " name="nomor_rm" readonly autocomplete="off" placeholder="<?= lang('label_nomor_rm'); ?>">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label">No Invoice<span class="tx-danger"> *</span></label>
											<input type="text" class="form-control input-sm " name="no_invoice" readonly>
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label">Tanggal Pendaftaran</label>
											<input type="text" class="form-control input-sm " name="tgl_daftar" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label">Ruangan</label>
											<div class="d-grid gap-2 d-md-flex ">
												<input type="text" class="hide" name="id_pendaftaran" value="">
												<input type="text" class="hide" name="idRuangan" value="">
												<input type="text" name="namaRuangan" class="form-control input-sm" autocomplete="off" readonly="readonly">

											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label">Kelas Ruangan</label>
											<input type="text" class="form-control input-sm " name="namaKelas" readonly>
										</div>
									</div>
								</div>
								<div class=" row">
									<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
										<div class="form-group">
											<label class="form-label">Tanggal Masuk Ruangan</label>
											<input type="text" class="form-control input-sm " name="tgl_masuk_ruangan" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="form-label">Kondisi Keluar</label>
											<div class="btn-group">
												<label class=" btn btn-xs btn-success">
													<input type="radio" name="status_checkout" value="Sembuh"> Sembuh
												</label>
												<label class="btn btn-xs btn-dark ">
													<input type="radio" name="status_checkout" value="Meninggal"> Meninggal
												</label>
											</div>
											<div class="btn-group">
												<label class="btn btn-xs btn-primary">
													<input type="radio" name="status_checkout" value="Permintaan pasien"> Permintaan Pasien
												</label>
												<label class="btn btn-xs btn-warning ">
													<input type="radio" name="status_checkout" value="Dirujuk"> Dirujuk Ke Klinik Lain
												</label>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="save-checkout" class="btn btn-primary"><?= lang('label_save'); ?></button>
						<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
					</div>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
