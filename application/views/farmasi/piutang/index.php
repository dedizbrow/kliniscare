<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header c-header-large border">
				<div class="card-title">
					Piutang
				</div>
				<div class="tx-center label-filter ">
					<label class="ftr align-middle">Jatuh Tempo
						<input type="text" name="start_date" class="form-control input-sm" autocomplete="off" placeholder="YYYY-MM-DD" style="width: 90px; display: inline-block">
						s/d
						<input type="text" name="end_date" class="form-control input-sm" autocomplete="off" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
					</label>
				</div>
				<div class="pull-right">
					<!-- <a href="<?php echo site_url(); ?>farmasi/piutang/export_"><button type="button" class="btn btn-secondary btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a> -->
				</div>
			</div>
			<div class="card-body">
				<table class="table-bordered table-striped minimize-padding-all" id="dataPiutang" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th>No.</th>
							<th><?= lang('label_invoice'); ?></th>
							<th><?= lang('label_payment'); ?></th>
							<th><?= lang('label_jatuhtempo'); ?></th>
							<th><?= lang('label_docter'); ?></th>
							<th><?= lang('label_earlybill'); ?></th>
							<th><?= lang('label_paid'); ?></th>
							<th>Sisa tagihan</th>
							<!-- <th><?= lang('label_operator'); ?></th>
                            <th><?= lang('label_status'); ?></th> -->
							<th><?= lang('label_action'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-pembayaran-piutang" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-pembayaran-piutang">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Pembayaran piutang</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-md-12">
								<dl class="row mb-0">
									<dt class="col-sm-3">Faktur</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-8 faktur"></dd>
								</dl>
								<dl class="row mb-0">
									<dt class="col-sm-3">Dokter</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-8 namaDokter"></dd>
								</dl>
							</div>
						</section>
					</div>
					<div class="row">
						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
							<div class="col-sm-12">
								<dl class="row mb-0">
									<dt class="col-sm-3">Total Piutang</dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-8 grandtotal">
									</dd>
								</dl>

								<dl class="row mb-0 ">
									<dt class="col-sm-3">Telah Bayar </dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-8 total_dibayar">
								</dl>
								<dl class="row mb-0 ">
									<dt class="col-sm-3">Sisa Tagihan </dt>
									<dd class="col-sm-1">:</dd>
									<dd class="col-sm-8 sisa">
								</dl>
							</div>
						</section>
						<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 border-top-dashed">
							<div class="col-sm-12">
								<dl class="row mb-0 ">
									<dt class="col-sm-3">Dibayar Tunai </dt>
									<dd class="col-sm-2">: Rp</dd>
									<dd class="col-sm-7"><input class="form-control input-sm " id="biaya" name="biaya" type="number" autocomplete="off">
									</dd>
								</dl>
								<dl class="row mb-0 ">
									<dt class="col-sm-3">Kembalian </dt>
									<dd class="col-sm-2">: Rp</dd>
									<dd class="col-sm-7"><input class="form-control input-sm " id="kembalian" type="number" autocomplete="off">
									</dd>
								</dl>

								<input type="text" class="hide" name="_id" value="">
							</div>
						</section>
						<input type="text" class="hide" name="_id" value="">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-pembayaran-piutang" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-detail" class="modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Detail Pembayaran Piutang</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<table width="100%" class="display minimize-padding-all rm" id="tableDetail" cellpadding="6">
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
