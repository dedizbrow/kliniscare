<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<form method="POST" name="form-manage-company-detail">
			<div class="card with-border">
				<div class="card-header c-header with-border">
					<div class="card-title">
						Pengaturan Umum
					</div>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label class="form-label">Nama Klinik</label>
						<input type="text" class="form-control input-sm" name="nama" required="" value="<?=(isset($company->nama)) ? $company->nama : ""; ?>">
					</div>
					<div class="form-group">
						<label class="form-label">Kota/Kabupaten</label>
						<input type="text" class="form-control input-sm" name="kabupaten" required="" value="<?=(isset($company->kabupaten)) ? $company->kabupaten : ""; ?>">
					</div>
					<div class="form-group">
						<label class="form-label">Provinsi</label>
						<input type="text" class="form-control input-sm" name="provinsi" required="" value="<?=(isset($company->provinsi)) ? $company->provinsi : ""; ?>">
					</div>
					<div class="form-group">
						<label class="form-label">Alamat</label>
						<textarea class="form-control col-xs-12" rows="7" cols="100" name="alamat"><?=(isset($company->alamat)) ? $company->alamat : ""; ?></textarea>
					</div>
					<div class="form-group">
						<label class="form-label">Nomor Izin Klinik</label>
						<input type="text" class="form-control input-sm" name="no_izin" value="<?=(isset($company->no_izin)) ? $company->no_izin : ""; ?>" required="">
					</div>
					<div class="form-group">
						<label class="form-label">Telp</label>
						<input type="text" class="form-control input-sm" name="telp" value="<?=(isset($company->telp)) ? $company->telp : ""; ?>" required="">
					</div>
					<div class="form-group">
						<label class="form-label">E-mail</label>
						<input type="text" class="form-control input-sm" name="email" value="<?=(isset($company->email)) ? $company->email : ""; ?>" required="">
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" id="save-company" class="btn btn-primary"> <?= lang('label_save'); ?></button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<div class="card with-border">
			<div class="card-header with-border bg-warning">
				<div class="card-title">Pengaturan Dokumen</div>
			</div>
			<div class="card-body">
				<div class="header-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_header)) ? $doc_setting->img_doc_header->title : "Gambar Header Dokumen - <i class='tx-danger'>Belum ditambahkan</i>"; ?> <i class="fa fa-level-down"></i><i style="font-size: 10px;"> Dimensions: 2400 x 330 | size 60KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_header" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?=(isset($doc_setting->img_doc_header)) ? base_url($doc_setting->img_doc_header->path) : ""; ?>" width="100%">
					</div>
				</div>
				
				<div class="body-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_background_body)) ? $doc_setting->img_doc_background->title : "Gambar Background Body Dokumen - <i class='tx-danger'>Belum ditambahkan</i>"; ?> <i style="font-size: 10px;"> Dimensions: 2400 x 330 | size 60KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_background_body" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?=(isset($doc_setting->img_doc_background_body)) ?  base_url($doc_setting->img_doc_background_body->path) : ""; ?>" width="100%"></div>
				</div>
				
				<div class="footer-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_footer)) ? $doc_setting->img_doc_footer->title : "Gambar Footer Dokumen - <i class='tx-danger'>Belum ditambahkan</i>"; ?> <i class="fa fa-level-down"></i><i style="font-size: 10px;"> Dimensions: 2400 x 1200 | size 100KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_footer" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?=(isset($doc_setting->img_doc_footer)) ?  base_url($doc_setting->img_doc_footer->path) : ""; ?>" width="100%"></div>
				</div>
				<div class="body-doc mb-3">
					<h5><?= (isset($doc_setting->img_ttd_validator)) ? $doc_setting->img_ttd_validator->title : 'Gambar TTD Validator - Belum ditambahkan'; ?> <i class="fa fa-level-down"></i> <i style="font-size: 10px;"> Dimensions: 850 x 800 | size 60KB | PNG</i></h5>
					<!-- <div class="input-group" style="width: 300px">
						<div class="input-group-prepend"><span class="input-group-text">Kota/Kabupaten:</span></div>
						<input type="text" class="form-control input-sm" name="text_city_of_klinik" id="text_city_of_klinik" value="<?= (isset($doc_setting->text_city_of_klinik)) ? $doc_setting->text_city_of_klinik->content : ""; ?>" placeholder="Kota/Kabupaten">
						<div class="input-group-append save-inline"><span class="input-group-text"><i class="fa fa-save"></i></span></div>
					</div> -->
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_ttd_validator" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?= (isset($doc_setting->img_ttd_validator)) ? base_url($doc_setting->img_ttd_validator->path) : ""; ?>" style="width: 200px;"></div>
				</div>

				<div class="body-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_logo)) ?  $doc_setting->img_doc_logo->title : "Gambar Logo Dokumen - <i class='tx-danger'>Belum ditambahkan</i>"; ?><i style="font-size: 10px;"> Dimensions: 850 x 800 | size 60KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_logo" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?= (isset($doc_setting->img_doc_logo)) ? base_url($doc_setting->img_doc_logo->path) : ""; ?>" style="width: 200px;"></div>
				</div>

			</div>
		</div>

		<div class="card with-border">
			<div class="card-header with-border bg-success text-white">
				<div class="card-title">Pengaturan Kwitansi</div>
			</div>
			<div class="card-body">
				
				<div class="body-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_header_kwitansi)) ? $doc_setting->img_doc_header_kwitansi->title : "Gambar Header Kwitansi - <i class='tx-danger'>Belum ditambahkan</i>"; ?> <i style="font-size: 10px;"> Dimensions: 2400 x 330 | size 60KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_header_kwitansi" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?=(isset($doc_setting->img_doc_header_kwitansi)) ?  base_url($doc_setting->img_doc_header_kwitansi->path) : ""; ?>" width="100%"></div>
				</div>
				<div class="body-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_background_kwitansi)) ? $doc_setting->img_doc_background_kwitansi->title : "Gambar Background Kwitansi - <i class='tx-danger'>Belum ditambahkan</i>"; ?> <i style="font-size: 10px;"> Dimensions: 2400 x 330 | size 60KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_background_kwitansi" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?=(isset($doc_setting->img_doc_background_kwitansi)) ?  base_url($doc_setting->img_doc_background_kwitansi->path) : ""; ?>" width="100%"></div>
				</div>
				<div class="body-doc mb-3">
					<h5><?=(isset($doc_setting->img_doc_footer_kwitansi)) ? $doc_setting->img_doc_footer_kwitansi->title : "Gambar Footer Kwitansi - <i class='tx-danger'>Belum ditambahkan</i>"; ?> <i style="font-size: 10px;"> Dimensions: 2400 x 330 | size 60KB | PNG</i></h5>
					<div class="btn btn-xs p-0 pull-right btn-change-image">
						<button type="button" class="btn btn-warning choose_file__ btn-xs"><i class="fa fa-folder-open-o"></i> <span><?= lang('label_change'); ?></span></button>
						<input type="file" name="file" class="hide" id="img_doc_footer_kwitansi" accept=".png,.jpg,.jpeg">
						<span class="filename"></span>
					</div>
					<div class="image-preview"><img src="<?=(isset($doc_setting->img_doc_footer_kwitansi)) ?  base_url($doc_setting->img_doc_footer_kwitansi->path) : ""; ?>" width="100%"></div>
				</div>
			</div>
		</div>
	</div>
	
</div>


<style>
	.image-preview {
		border: 1px solid #ccc;
		padding: 5px;
	}
</style>
