<div class="row">
	<?php if (isset($manage_user) && $manage_user != false) { ?>
		<div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
			<div class="card bd-0 with-border">
				<div class="card-header bd-0 c-header">
					<i class="fa fa-users"></i> <?= lang('label_list_user'); ?> <span class="pull-right pointer add-user"><i class="fa fa-plus"></i></span>
				</div><!-- card-header -->
				<div class="card-body">
					<table id="tableUsers" class="table cell-border table-border minimize-padding-all" width="100%">
						<thead>
							<tr>
								<th>No.</th>
								<th>Name</th>
								<th>Provider</th>
								<th>Username</th>
								<th>Email</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<div class="card-footer tx-indigo"></div>
			</div>
		</div>
	<?php } ?>
	<?php if (isset($manage_provider) && $manage_provider != false) { ?>
		<div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
			<div class="card bg-0 with-border">
				<div class="card-header bd-0 tx-white bg-primary">
					<i class="fa fa-users"></i> Data Provider <span class="pull-right pointer add-provider"><i class="fa fa-plus"></i></span>
				</div>
				<div class="card-body">
					<table class="table table-bordered table-striped" id="dataProvider" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
						<thead>
							<tr role="row">
								<th class="search" data-name="kode">Nama Provider</th>
								<th class="search" data-name="jenis">Alamat</th>
								<th>Telp</th>
								<th>Penanggung Jawab</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="card-footer tx-primary">
					Note: *<br>
					<ul>
						<li>Icon <i class="fa fa-check-square-o tx-success"></i> pada nama provider menandakan sebagai YM Pusat</li>
						<li>Sedangkan lainnya dianggap sebagai cabang/marketing</li>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if (isset($manage_dokter) && $manage_dokter != false) { ?>
		<div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
			<div class="card bd-0 with-border">
				<div class="card-header bd-0 tx-white bg-success"><i class="fa fa-users"></i> Data Dokter
					<span class="pull-right add-dokter pointer"><i class="fa fa-plus"></i></span>
				</div><!-- card-header -->
				<div class="card-body">
					<table id="tableDokter" class="table cell-border table-border minimize-padding-all" width="100%">
						<thead>
							<tr>
								<th>Kategori</th>
								<th>ID Dokter</th>
								<th>Nama Dokter</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card bd-0 with-border ">
			<div class="card-header bd-0 tx-white c-header">
				<div class="card-title">
					<i class="fa fa-users"></i> Data Jenis Pemeriksaan
					<span class="pull-right add-jenis-pemeriksaan pointer"><i class="fa fa-plus"></i></span>
				</div>
				
			</div><!-- card-header -->
			<div class="card-body">
				<table id="tableJenisPemeriksaan" class="table cell-border table-border minimize-padding-all" width="100%">
					<thead>
						<tr>
							<th>Jenis</th>
							<th>Pemeriksaan</th>
							<th>Hasil</th>
							<th>Nilai Rujukan</th>
							<th>Metode</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="card-footer">
				Note: *
				<ul class="desc">
					<li>Perubahan pada item pemeriksaan tidak akan mengubah hasil yang sudah pernah di update pada pemeriksaan</li>
					<li>Jika ada perlu melakukan perubahan hasil (adanya penambahan/pengurangan item pemeriksaan), silahkan click reset hasil ketika update pemeriksaan</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
		<div class="card bd-0 with-border">
			<div class="card-header bd-0 tx-white bg-warning"><i class="fa fa-users"></i> List Opsi Sampling
				<span class="pull-right add-jenis-sample pointer"><i class="fa fa-plus"></i></span>
			</div><!-- card-header -->
			<div class="card-body">
				<table id="table-jenis-sample" class="table cell-border table-border minimize-padding-all" width="100%">
					<thead>
						<tr>
							<th>Nama Sampling</th>
							<th>Nama Sampling (English)</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
	<?php if (isset($manage_other_setting) && $manage_other_setting != false) { ?>
		<div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
			<div class="card bd-0 with-border">
				<div class="card-header bd-0 tx-white bg-danger"><i class="fa fa-users"></i> Others Settings
				</div><!-- card-header -->
				<div class="card-body">
					<table id="table-others-setting" class="table cell-border table-bordered table-striped minimize-padding-all" width="100%">
						<thead>
							<tr>
								<th>Title</th>
								<th>Content</th>
								<th>Act</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="col-lg-6 col-md-10 col-sm-12 col-xs-12">
		<div class="card bd-0 with-border">
			<div class="card-header bd-0 tx-white bg-primary"><i class="fa fa-users"></i> List Notes
				<span class="pull-right add-notes pointer"><i class="fa fa-plus"></i></span>
			</div><!-- card-header -->
			<div class="card-body">
				<table id="table-notes" class="table cell-border table-border minimize-padding-all" width="100%">
					<thead>
						<tr>
							<th>Indonesia</th>
							<th>English</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>

</div>


<div id="modal-manage-user" class="modal">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<form method="POST" name="form-manage-user">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update User</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label class="form-label">Select Provider</label>
										<select class="form-control input-sm" name="provider_id" id="search_provider"></select>
									</div>
								</div>
								<div class="col-sm-8">
									<div class="form-group">
										<label class="form-label"><?= lang('label_enter_name'); ?></label>
										<input type="text" class="hide" name="user_id" value="">
										<input type="text" name="name" class="form-control input-sm" required="" placeholder="<?= lang('label_enter_name'); ?>" autocomplete="off">
									</div>
								</div>
								<div class="col-sm-12"></div>
								<div class="col-sm-6">
									<div class="form-group">
										<label class="form-label"><?= lang('label_enter_username'); ?></label>
										<input type="text" name="username" class="form-control no-space input-sm" required="" placeholder="<?= lang('label_enter_username'); ?>" autocomplete="off">
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label class="form-label"><?= lang('label_enter_email'); ?></label>
										<input type="text" name="email" class="form-control input-sm" required="" placeholder="<?= lang('label_enter_email'); ?>" autocomplete="off">
									</div>
								</div>

								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="form-label"><?= lang('label_enter_password'); ?></label>
												<input type="password" name="password" class="form-control input-sm" required="" placeholder="<?= lang('label_enter_password'); ?>" autocomplete="off">
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="form-label"><?= lang('label_enter_repassword'); ?></label>
												<input type="password" name="repassword" class="form-control input-sm" required="" placeholder="<?= lang('label_enter_repassword'); ?>" autocomplete="off">
											</div>
										</div>
										<div class="col-sm-12">
											<sup class="show-on-update hide text-danger"><?= lang('label_is_change_password'); ?></sup>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">

									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 border-left-dashed">
							<h5><?= lang('label_user_privilege'); ?></h5>
							<?php
							if (isset($base_menu)) {
								$row = "<ul class='setting_menu'>";
								foreach ($base_menu as $k => $bm) {
									$row .= "<li><label class='ckbox'>
								<input type='checkbox' name='accessibility[]' value='" . $bm->access_code . "' class='accessibility' > <span> " . strtoupper($bm->title) . "</span></label>";
									$split_actions_code = explode(",", $bm->actions_code);
									if ($bm->actions_code == "") $split_actions_code = array();
									if (sizeof($split_actions_code) > 0) $row .= "<ul>";
									foreach ($split_actions_code as $ac) {
										$row .= "<li class='inline'><label class='ckbox'><input type='checkbox' class='accessibility' name='actions_code[]' value='" . $bm->access_code . "^" . $ac . "'><span>" . strtoupper($ac) . "<span></label> </li>";
									}
									if (sizeof($split_actions_code) > 0) $row .= "</ul>";
									$row .= "</li>";
								}
								$row .= "</ul>";
								echo $row;
							}
							?>
							<p>
								<hr>
								<label class="ckbox text-primary">
									<input type="checkbox" class="accessibility" name="level" value="<?= $this->config->item('super_admitem_code'); ?>"> <span>Super Admin (<?= lang('label_allow_access_all'); ?>)</span>
								</label>
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-user" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modal-manage-provider" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-provider">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update Provider</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Nama Provider</label>
								<input type="text" name="nama" class="form-control input-sm" required="" placeholder="Masukkan Nama Provider" autocomplete="off">
							</div>
							<div class="form-group">
								<label class="form-label">Alamat</label>
								<textarea name="alamat" class="form-control input-sm" required="" placeholder="Masukkan Alamat" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Telp.</label>
								<input type="text" name="telp" class="form-control input-sm" required="" placeholder="Masukkan No Telp." autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Penanggung Jawab</label>
								<input type="text" name="penanggung_jawab" class="form-control input-sm" required="" placeholder="Masukkan Penanggung Jawab" autocomplete="off">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-provider" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->


<div id="modal-manage-jenis-pemeriksaan" class="modal">
	<div class="modal-dialog modal-lg" role="document">
		<form method="POST" name="form-manage-jenis-pemeriksaan">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update Jenis Pemeriksaan</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-8 col-md-10 col-sm-12">
							<div class="form-group">
								<label class="form-label">Kategori</label>
								<div class="btn-groups">
									<label class="btn btn-flat btn-xs btn-success">
										<input type="radio" name="kategori" value="umum"> Umum
									</label>
									<label class="btn btn-flat btn-xs btn-danger">
										<input type="radio" name="kategori" value="covid" checked> Covid 19
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-8 col-md-10 col-sm-12">
							<div class="form-group">
								<label class="form-label">Nama Pemeriksaan</label>
								<input type="text" name="jenis_pemeriksaan" class="form-control input-sm" required="" placeholder="Jenis Pemeriksaan" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row list_hasil with-border show-kategori-umum hide">
						<div class="col-lg-12 col-md-12 col-sm-12 append-row-pemeriksaan">
							<!-- ROW HEADER -->
							<div class="row mb-1 row-header">
								<div class="col-1 bg-warning pt-2 pb-2">
									<b>No</b>
								</div>
								<div class="col-6 bg-warning pt-2 pb-2">
									<b>Item Pemeriksaan</b>
								</div>
								<div class="col-3 bg-warning pt-2 pb-2">
									<b>Nilai Rujukan</b>
								</div>
								<div class="col-2 bg-warning pt-2 pb-2">
									<b>Satuan</b>
								</div>
							</div>
							<!-- END ROW HEADER -->
							<!-- ROW ITEM PERIKSA -->
							<div class="row row-item mb-1 main default" data-index="1">
								<div class="col-1 border">
									<b class="no">1</b>
								</div>
								<div class="col-6">
									<input type="text" name="item_periksa_umum[1][name]" class="form-control input-sm" placeholder="Item Periksa">
									<button type="button" class="btn btn-xs add-sub-item p-0" data-toggle="tooltip" title="Tambah Sub Item Pemeriksaan" index="4"><i class="fa fa-plus-circle tx-success"></i></button>
								</div>
								<div class="col-3">
									<input type="text" name="item_periksa_umum[1][rujukan]" class="form-control input-sm" placeholder="Nilai Rujukan">
								</div>
								<div class="col-2">
									<input type="text" name="item_periksa_umum[1][satuan]" class="form-control input-sm" placeholder="Satuan Nilai">
								</div>
							</div>
						
							<!-- <div class="row row-item main mb-1" data-index="2">
								<div class="col-1 border">
									<b class="no">2</b>
								</div>
								<div class="col-6">
									<input type="text" name="item_periksa_umum[2][name]" class="form-control input-sm" placeholder="Item Periksa">
									<button type="button" class="btn btn-xs add-sub-item p-0" data-toggle="tooltip" title="Tambah Sub Item Pemeriksaan" index="4"><i class="fa fa-plus-circle tx-success"></i></button>
								</div>
								<div class="col-3">
									<input type="text" name="item_periksa_umum[2][rujukan]" class="form-control input-sm" placeholder="Nilai Rujukan">
								</div>
								<div class="col-2">
									<input type="text" name="item_periksa_umum[2][satuan]" class="form-control input-sm" placeholder="Satuan Nilai">
								</div>
							</div> -->
							
							<!-- SUB ROW ITEM PERIKSA -->
								<!-- <div class="row row-sub-item mb-1 sub" data-index="2">
									<div class="col-2 border tx-right pr-1">
										<div class="btn-group-sm">
											<button type="button" class="btn btn-xs delete-sub-item"><i class="fa fa-minus-circle tx-danger"></i></button>
										</div>
									</div>
									<div class="col-5 pl-1">
										<input type="text" name="item_periksa_umum[2][sub][0][name]" class="form-control input-sm" placeholder="Sub Item Periksa">
									</div>
									<div class="col-3">
										<input type="text" name="item_periksa_umum[2][sub][0][rujukan]" class="form-control input-sm" placeholder="Nilai Rujukan">
									</div>
									<div class="col-2">
										<input type="text" name="item_periksa_umum[2][sub][0][satuan]" class="form-control input-sm" placeholder="Satuan Nilai">
									</div>
								</div>

								<div class="row row-sub-item sub mb-1" data-index="2">
									<div class="col-2 border tx-right pr-1">
										<div class="btn-group-sm">
											<button type="button" class="btn btn-xs delete-sub-item"><i class="fa fa-minus-circle tx-danger"></i></button>
										</div>
									</div>
									<div class="col-5 pl-1">
										<input type="text" name="item_periksa_umum[2][sub][1][name]" class="form-control input-sm" placeholder="Sub Item Periksa">
									</div>
									<div class="col-3">
										<input type="text" name="item_periksa_umum[2][sub][1][rujukan]" class="form-control input-sm" placeholder="Nilai Rujukan">
									</div>
									<div class="col-2">
										<input type="text" name="item_periksa_umum[2][sub][1][satuan]" class="form-control input-sm" placeholder="Satuan Nilai">
									</div>
								</div> -->
								<!-- END SUB ROW ITEM PERIKSA -->
						
						</div>	
						<div class="col-lg-12 col-md-12 col-sm-12 mt-2">
							<button type="button" class="btn btn-xs btn-flat btn-success add-row-item-pemeriksaan"><i class="fa fa-plus-square"></i>  Tambah Item Pemeriksaan</button>
						</div>
					</div>
					<div class="row show-kategori-covid">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group"><label class="form-label">Setting Hasil:</label></div>
							<div class="row rows-hasil">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<div class="form-group">
										<label class="form-label">Hasil 1: </label>
										<label class="btn btn-xs btn-warning">
											<input type="radio" name="group_hasil1" data-group="gh_radio1" value="Positif"> Positif
										</label>
										<label class="btn btn-xs btn-purple">
											<input type="radio" name="group_hasil1" data-group="gh_radio2" value="Reaktif"> Reaktif
										</label>
									</div>
									<div>
										<table width="100%" class="table-hasil1 table-striped table-bordered minimize-padding-all" style="border-left: 2px solid #ff00ff">
											<thead>
												<tr>
													<th width="100px">Pemeriksaan</th>
													<th width="40px">Hasil</th>
													<th width="50px">Nilai Rujukan</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="p-1">
														<input type="hidden" class="form-control input-sm" name="is_main[]" value="1">
														<input type="hidden" class="form-control input-sm" name="hasil_id1[]">
														<input type="text" class="form-control input-sm" name="pemeriksaan[]">
													</td>
													<td class="p-1"><input type="text" class="form-control input-sm" readonly name="hasil1[]" data-groupv="group_hasil1"></td>
													<td class="p-1"><input type="text" class="form-control input-sm" name="nilai_rujukan1[]" data-groupv="group_hasil1"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6">
									<div class="form-group">
										<label class="form-label">Hasil 2: </label>
										<label class="btn btn-xs btn-warning">
											<input type="radio" name="group_hasil2" value="Negatif" data-group="gh_radio1"> Negatif
										</label>
										<label class="btn btn-xs btn-purple">
											<input type="radio" name="group_hasil2" value="Non Reaktif" data-group="gh_radio2"> Non-Reaktif
										</label>
									</div>
									<div>
										<table width="100%" class="table-hasil2  table-striped table-bordered minimize-padding-all">
											<thead>
												<tr>
													<th width="40px">Hasil</th>
													<th width="50px">Nilai Rujukan</th>
													<th width="10px">--</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="p-1">
														<input type="hidden" class="form-control input-sm" name="hasil_id2[]">
														<input type="text" class="form-control input-sm" name="hasil2[]" readonly data-groupv="group_hasil2">
													</td>
													<td class="p-1"><input type="text" class="form-control input-sm" name="nilai_rujukan2[]" data-groupv="group_hasil2"></td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<p>&nbsp;</p>

							</div>
							<i class="fa fa-plus-square add-row-opsi-pemeriksaan pointer tx-success"></i>
							<p>&nbsp;</p>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Metode</label>
								<input type="text" name="metode" class="form-control input-sm" required="" placeholder="Metode" autocomplete="off">
							</div>
						</div>
						<input type="text" class="hide" name="_id" value="">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-jenis-pemeriksaan" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-manage-dokter" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-dokter">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update Dokter</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="form-group">
								<label class="form-label">Kategori</label>
								<input type="text" name="kategori" class="form-control input-sm" required="" placeholder="Umum/Spesialis/dll" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">

						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">ID Dokter</label>
								<input type="text" name="id_dokter" class="form-control input-sm" required="" placeholder="ID Dokter" autocomplete="off">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Nama Dokter</label>
								<input type="text" name="nama_dokter" class="form-control input-sm" required="" placeholder="Nama Dokter" autocomplete="off">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-dokter" class="btn btn-indigo"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-manage-jenis-sample" class="modal">
	<div class="modal-dialog modal-sm" role="document">
		<form method="POST" name="form-manage-jenis-sample">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update Jenis Sample</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Jenis Sample</label>
								<input type="text" name="nama_sampling" class="form-control input-sm" required="" placeholder="Nama Sampling" autocomplete="off">
							</div>
							<div class="form-group">
								<label class="form-label">Jenis Sample (English)</label>
								<input type="text" name="nama_sampling_en" class="form-control input-sm" required="" placeholder="Nama Sampling (Inggris)" autocomplete="off">
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-jenis-sample" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-manage-notes" class="modal">
	<div class="modal-dialog modal-md" role="document">
		<form method="POST" name="form-manage-notes">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Tambah/Update Notes</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">

						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Indonesia</label>
								<textarea name="notes" class="form-control input-sm" required="" placeholder="Note" autocomplete="off" rows="3"></textarea>
							</div>
							<div class="form-group">
								<label class="form-label">English</label>
								<textarea name="english" class="form-control input-sm" required="" placeholder="Note in English" autocomplete="off" rows="3"></textarea>
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-notes" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modal-manage-others-setting" class="modal">
	<div class="modal-dialog modal-md" role="document">
		<form method="POST" name="form-manage-others-setting">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Update Others Setting</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Title</label>
								<input type="text" class="form-control input-sm" name="title" placeholder="title">
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Content</label>
								<textarea name="content" class="form-control input-sm" rows="3" placeholder="content"></textarea>
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-setting" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->


<style>
.main div[class^='col']{
	position: relative;
}
.main div[class^='col'] .add-sub-item{
	position: absolute;
	right: 0;
	bottom: 1px;
	z-index: 100;
}
</style>
