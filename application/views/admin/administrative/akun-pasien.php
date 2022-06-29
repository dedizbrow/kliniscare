<div class="row">
	<?php if (isset($manage_akun_pasien) && $manage_akun_pasien != false) { ?>
		<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card bd-0 with-border">
				<div class="card-header bd-0 c-header">
					<div class="card-title">
						<i class="fa fa-users"></i> Data Akun Pasien <span class="pull-right pointer add-user"><i class="fa fa-plus"></i></span>
					</div>

				</div><!-- card-header -->
				<div class="card-body">
					<table id="tableUsers" class="display responsive cell-border table-border minimize-padding-all" width="100%">
						<thead>
							<tr>
								<th>No.</th>
								<th>Nama</th>
								<th>Email</th>
								<th>HP</th>
								<th>Tgl. Daftar</th>
								<th>Pasien Terkait</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<p></p>
		</section>
	<?php
	}
	?>

</div>
