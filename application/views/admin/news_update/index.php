<div class="row">
	<section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<div class="card-header border c-header">
				<div class="card-title">
					Update berita
				</div>

				<div class="pull-right">
					<button type="button" class="btn btn-success add-news btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-striped minimize-padding-all" id="dataNews" width="100%">
					<thead>
						<tr>
							<th>No.</th>
							<th>Judul</th>
							<th>Keterangan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>


<div id="modal-manage-news" class="modal">
	<div class="modal-dialog" role="document">
		<form method="POST" name="form-manage-news">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">Add/Update Data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label class="form-label">Judul</label>
								<input class="datepicker form-control no-space input-sm" placeholder="eq. Penambahan fitur " name="judul" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label class="form-label">Keterangan</label>
								<textarea class="form-control col-xs-12" rows="7" cols="100" name="keterangan" placeholder="keterangan"></textarea>
							</div>
						</div>
					</div>
					<input type="text" class="hide" name="_id" value="">
				</div>
				<div class="modal-footer">
					<button type="submit" id="save-news" class="btn btn-primary"><?= lang('label_save'); ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><?= lang('label_close'); ?></button>
				</div>
			</div>
		</form>
	</div><!-- modal-dialog -->
</div><!-- modal -->
