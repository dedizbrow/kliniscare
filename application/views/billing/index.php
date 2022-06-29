<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="card card-dashboard-one">
			<div class="card-header c-header-large">
				<div class="card-title">
					Billing
				</div>
				<div class="ml-3 mr-3 tx-center label-filter ">
					<!-- <label class="ftr align-middle">Kategori
						<div class="btn-group">
							<label class="btn btn-xs btn-warning"><input type="radio" name="category" value="lab"> Lab</label>
							<label class="btn btn-xs btn-info"><input type="radio" name="category" value="non-lab" checked> Non Lab</label>
						</div>
					</label> -->
					<label class="ftr align-middle">Pendaftaran
						<select class="form-control input-sm inline-block" style="width: 120px; display: inline-block;" name="filter_pemeriksaan">
							<option value="">Semua</option>
							<option value=2|3|5>Rawat Jalan</option>
							<option value=0|1|4>Rawat Inap</option>
						</select>

					</label>
					<label class="ftr align-middle">
						<select class="form-control input-sm inline-block" name="filter_tgl_option" style="max-width: 170px;display: inline-block">
							<option value="1" selected>Tgl. Kunjungan</option>
							<option value="2">Tgl. Pembayaran</option>
						</select>
						<input type="text" name="start_date" class="form-control input-sm" autocomplete="off" value="<?= date('Y-m-01'); ?>" placeholder="YYYY-MM-DD" style="width: 90px; display: inline-block">
						s/d
						<input type="text" name="end_date" class="form-control input-sm" autocomplete="off" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
					</label>

				</div>
			</div>
			<div class="card-body">
				<table class="table-bordered table-striped minimize-padding-all" id="dataPasiendiperiksa" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>No.</th>
							<th>Aksi</th>
							<th><?= lang('label_invoice'); ?></th>
							<th><?= lang('label_rmnum'); ?></th>
							<th><?= lang('label_patientname'); ?></th>
							<th><?= lang('label_visit'); ?></th>
							<th><?= lang('label_biaya_resep'); ?></th>
							<th><?= lang('label_biaya_pemeriksaan'); ?></th>
							<th>Biaya Kamar</th>
							<th><b><?= lang('label_total_biaya'); ?></b></th>
							<th><?= lang('label_sisa_tagihan'); ?></th>

							<th>Tanggal Pembayaran</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>


<div id="modal-manage-pembayaran" class="modal" style="right:40%; ">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-pembayaran">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pembayaran</h6>
					<button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dt class="col-sm-5">No. Invoice</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-6 no_invoice"></dd>
								</dl>
								<dl class="row mb-0">
									<dt class="col-sm-5">No. RM</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-6 nomor_rm"></dd>
								</dl>
								<dl class="row mb-0">
									<dt class="col-sm-5">Nama Pasien</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-6 nama_lengkap"></dd>
								</dl>
								<dl class="row mb-0">
									<dt class="col-sm-5">Tgl. Kunjungan</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-6 create_at"></dd>
								</dl>
							</div>
						</section>
						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
							<div class="col-sm-12">
								<dl class="row mb-0">
									<dt class="col-sm-5">Total Biaya Tindakan</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-4 total_biaya_tindakan">
									</dd>
									<dd class="col-sm-2">
										<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-manage-detail-tindakan" id="detail_tindakan" style="width: 100%;">Detail</button>
									</dd>
								</dl>
								<dl></dl>
								<dl class="row mb-0">
									<dt class="col-sm-5">Total Biaya Resep </dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-4 total_biaya_resep">
									</dd>
									<dd class="col-sm-2">
										<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-manage-detail-resep" id="detail_resep" style="width: 100%;">Detail</button>
									</dd>
								</dl>
								<dl></dl>
								<dl class="row mb-0">
									<dt class="col-sm-5">Total Biaya Kamar </dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-4 total_biaya_kamar">
									</dd>
									<dd class="col-sm-2">
										<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-manage-detail-kamar" id="detail_kamar" style="width: 100%;">Detail</button>
									</dd>
								</dl>
							</div>
						</section>

						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
							<div class="col-sm-12">
								<dl class="row mb-0">
									<dt class="col-sm-5 ">Total Semua Biaya</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-6 total_biaya">
								</dl>
								<!-- <dl class="row mb-0 ">
                                    <dt class="col-sm-3">Total Telah Bayar </dt>
                                    <dd class="col-sm-1">:</dd>
                                    <dd class="col-sm-8 total_dibayar">
                                </dl> -->
								<dl class="row mb-0 ">
									<dt class="col-sm-5">Sisa Tagihan </dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-6 sisa">
								</dl>
							</div>
						</section>

						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
							<div class="col-lg-4 mg-t-20 mg-lg-t-0">
				              <label class="rdiobox">
				                <input name="rdio" type="radio" checked>
				                <span>Tunai</span>
				              </label>
				            </div>
				            <div class="col-lg-4 mg-t-20 mg-lg-t-0">
				              <label class="rdiobox">
				                <input name="rdio" type="radio" >
				                <span>Transfer</span>
				              </label>
				            </div>
				            <div class="col-lg-4 mg-t-20 mg-lg-t-0">
				              <label class="rdiobox">
				                <<input name="rdio" type="radio" >
				                <span>Kartu Kredit</span>
				              </label>
				            </div>
						</section>

						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
							<div class="col-sm-12">
								<dl class="row mb-0 ">
									<dt class="col-sm-5">Dibayar </dt>
									<dd class="col-sm-2">: Rp</dd>
									<dd class="col-sm-5"><input class="form-control input-sm " id="biaya" type="text" autocomplete="off" required>
										<input type="text" class="hide" name="biaya" value="">
									</dd>
								</dl>
								<dl class="row mb-0 ">
									<dt class="col-sm-5">Kembalian </dt>
									<dd class="col-sm-2">: Rp</dd>
									<dd class="col-sm-5"><input class="form-control input-sm " id="kembalian" type="text" autocomplete="off">
									</dd>
								</dl>

								<input type="text" class="hide" name="_id" value="">
								<input type="hidden" class="hide" name="tarif_dokter" value="">
							</div>
						</section>
					</div>
					<div class="modal-footer">
						<button type="submit" id="save-pembayaran" class="btn btn-primary"><?= lang('label_save'); ?></button>
						<button type="button" class="btn btn-outline-light close_modal" data-dismiss="modal"><?= lang('label_close'); ?></button>
					</div>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->


<div id="modal-manage-detail-tindakan" class="modal fade" style="top:0%; left:55%; margin-right:30px ">
	<div class="modal-dialog modal-billing" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail Tindakan</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table width="100%" class="table-bordered table-striped minimize-padding-all display" id="tableDetailTindakan" cellpadding="1">
						</table>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-manage-detail-resep" class="modal fade" style="top:30%; left:55%; margin-right:30px">
	<div class="modal-dialog modal-billing" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail Resep</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">

					<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table width="100%" class="table-bordered table-striped minimize-padding-all display" id="tableDetailResep" cellpadding="1">
						</table>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal-manage-detail-kamar" class="modal fade" style="top:60%; left:55%; margin-right:30px">
	<div class="modal-dialog modal-billing" role="document">
		<div class="modal-content modal-content-demo">
			<div class="modal-header">
				<h6 class="modal-title">Detail Kamar</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table width="100%" class="table-bordered table-striped minimize-padding-all display" id="tableDetailKamar" cellpadding="1">
						</table>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
