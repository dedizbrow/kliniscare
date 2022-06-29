<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					Cetak Kwitansi
				</div>
				<div class="pull-right">
					<!-- <a href="<?= base_url(conf('path_module_lab') . 'pemeriksaan/form'); ?>" class="btn btn-primary add-pemeriksaan btn-xs"><i class="fa fa-plus"></i> Baru</a> -->
				</div>
			</div>
			<div class="card-body">
				<table class="table table-bordered table-striped minimize-padding-all" id="dataKwitansi" width="100%" cellspacing="0">
					<thead>
						<tr role="row">
							<th class="search" data-name="periksa.no_test">No Test</th>
							<th class="search" data-name="periksa.tgl_periksa">Tgl. Daftar</th>
							<th class="search" data-name="periksa.tgl_sampling">Tgl. Sampling</th>
							<th class="search" data-name="pasien.no_test">ID Pasien</th>
							<th class="search" data-name="pasien.nik">NIK</th>
							<th class="search" data-name="pasien.nama">Nama Pasien</th>
							<th>JK</th>
							<th>Usia</th>
							<th>Pemeriksaan</th>
							<th>Status</th>
							<th>Biaya</th>
							<th>Update</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>