<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header c-header-large border">
				<div class="card-title">
					Resep Dokter
				</div>
				<div class="ml-3 mr-3 tx-center label-filter">

					<label class="ftr align-middle">Tgl. Kunjungan
						<input type="text" name="start_date" class="form-control input-sm" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
						s/d
						<input type="text" name="end_date" class="form-control input-sm" placeholder="YYYY-MM-DD" value="<?= date('Y-m-d'); ?>" style="width: 90px; display: inline-block">
					</label>

				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataTelahperiksa" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th>No.</th>
							<th>Keterangan</th>
							<th>No Invoice</th>
							<th>Asuransi</th>
							<th>No Asuransi</th>
							<th>No RM</th>
							<th>Nama Pasien</th>
							<th>Dokter</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="card-footer">
				<ul class="desc">
					<li class="tx-primary">Pengurangan stok obat dilakukan setelah tertera keterangan <b>Telah Diproses</b></li>
				</ul>
			</div>
		</div>
	</section>
</div>

<div id="modal-manage-detail-resep" class="modal">
	<div class="modal-dialog modal-billing" role="document">
		<form method="POST" name="form-manage-detail-resep">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Resep dokter</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table width="100%" class="display" id="pasien" style="margin-bottom: 20px;">
						<input type="text" class="hide" name="_id" value="">
						<tr>
							<td>Nama Pasien</td>
							<td>:</td>
							<td class="nama_lengkap"></td>
						</tr>
						<tr>
							<td>No Rekam Medis</td>
							<td>:</td>
							<td class="nomor_rm"></td>
						</tr>
						<tr>
							<td>Nomor Invoice</td>
							<td>:</td>
							<td class="no_invoice"></td>
						</tr>
						<tr>
							<td>Tanggal Kunjungan</td>
							<td>:</td>
							<td class="create_at"></td>
						</tr>
					</table>
					<table width="100%" class="display rm" border="1" id="tableDetail" cellpadding="4">
					</table>


				</div>
				<div class="modal-footer">
					<button type="submit" id="save-resep" class="btn btn-success">Proses</button>
					<!-- <button type="button" onclick="selesai()" class="btn btn-success">Proses</button> -->
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
