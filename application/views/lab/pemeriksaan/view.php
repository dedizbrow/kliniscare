<div class="row">
	<section class="col-lg-12  col-md-12  col-sm-12 col-xs-12">
		<div class="card card-dashboard-one">
			<form method="POST" name="form-pemeriksaan">
				<input type="hidden" name="ref_id" value="<?= $data_periksa->id; ?>">
				<div class="card-header border">
					<div class="card-title"><?= (isset($editable)) ? "Update " : ""; ?> Pemeriksaan</div>
					<div class="pull-right">
						<?php
						if ($data_periksa->hasil != "") { ?>
							<a href="<?= base_url(conf('path_module_lab') . 'pemeriksaan/form/view?viewid=' . $data_periksa->id . '&tn=' . $data_periksa->no_test); ?>&pdf=true" target="_blank" class="download pointer" data-id="<?= (isset($data_periksa->id)) ? $data_periksa->id : ''; ?>" data-tn="<?= (isset($data_periksa->no_test)) ? $data_periksa->no_test : ''; ?>">
								<i class="fa fa-file-pdf-o text-danger"></i> Download Pdf
							</a>
						<?php } ?>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
							<table class="dataTable minimize-padding-all v-center table-striped tablePasien" id="tablePasien" width="100%">

								<tr>
									<td style="width: 100px">ID Pemeriksaan</td>
									<td style="width: 5px">:</td>
									<td class="nama_pasien">
										<?= (isset($data_periksa->no_test)) ? $data_periksa->no_test : ''; ?>
									</td>
								</tr>
								<tr>
									<td style="width: 100px">Nama Pasien</td>
									<td style="width: 5px">:</td>
									<td class="nama_pasien"><?= (isset($data_periksa->nama_pasien)) ? $data_periksa->nama_pasien : ''; ?>

									</td>
								</tr>
								<tr>
									<td>Tgl. Lahir</td>
									<td>:</td>
									<td class="tgl_lahir"><?= (isset($data_periksa->tgl_lahir)) ? $data_periksa->tgl_lahir : ''; ?></td>
								</tr>
								<tr>
									<td>Jenis Kelamin</td>
									<td>:</td>
									<td class="jenis_kelamin">
										<?= (isset($data_periksa->jenis_kelamin)) ? ucwords($data_periksa->jenis_kelamin) : ''; ?>
									</td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>:</td>
									<td class="alamat">
										<?= (isset($data_periksa->alamat)) ? $data_periksa->alamat : ''; ?>
									</td>
								</tr>
								<tr>
									<td>Kewarganegaraan</td>
									<td>:</td>
									<td class="alamat">
										<?= (isset($data_periksa->kewarganegaraan)) ? $data_periksa->kewarganegaraan : ''; ?>
									</td>
								</tr>
								<tr>
									<td colspan="4">&nbsp;</td>
								</tr>
								<tr>
									<td><b>Pemeriksaan</b></td>
									<td>:</td>
									<td><b>
											<input type="hidden" name="id_jenis_pemeriksaan" value="<?= $data_periksa->id_jenis; ?>">
											<?= (isset($data_periksa->jenis_pemeriksaan)) ? $data_periksa->jenis_pemeriksaan : ''; ?>
										</b>
									</td>
								</tr>
								<tr>
									<td><b>Hasil</b></td>
									<td>:</td>
									<td>
										<?php
										if($category=='covid'){
											$hasil = (isset($data_periksa->hasil) && $data_periksa->hasil != "") ? $data_periksa->hasil : "";
											if (isset($editable)) {
												echo '<select class="form-control input-sm" name="hasil" data-id_jenis="' . $data_periksa->id_jenis . '" id="select_hasil" style="width: 200px">';
												echo '<option value="">Pilih Hasil</option>';
												foreach ($list_hasil as $hsl) {
													$selected = ($hsl->group_hasil == $hasil) ? 'selected=""' : '';
													echo '<option value="' . $hsl->group_hasil . '" ' . $selected . '>' . ucwords($hsl->group_hasil) . '</option>';
												}
												echo '</select>';
											}
										}
										?>
										<span class="pull-right pointer tx-danger tx-italic reset-hasil" data-id="<?=$data_periksa->id;?>" data-toggle="tooltip" title="Item pada hasil pemeriksaan akan di reset dan mengikuti item pemeriksaan terbaru">Reset Hasil </span>
									</td>
								</tr>
							</table>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
							<table class="dataTable minimize-padding-all v-center table-striped tablePasien" width="100%">
								<?php
								if (conf('lab_enable_select_provider') === TRUE) { ?>
									<tr>
										<td style="width: 150px">Provider</td>
										<td style="width: 5px">:</td>
										<td class="">
											<?= (isset($data_periksa->provider)) ? $data_periksa->provider : ''; ?>
										</td>
									</tr>
								<?php } ?>
								<tr>
									<td>Dokter</td>
									<td>:</td>
									<td class="dokter">
										<?php
										if (isset($editable)) {
											$sel = '<select class="form-control input-sm" style="width: 250px;" id="search_dokter" name="dokter">';
											if (isset($data_periksa->id_dokter) && $data_periksa->id_dokter != 0 && $data_periksa->id_dokter != "") {
												$sel .= '<option value="' . $data_periksa->id_dokter . '">' . $data_periksa->dokter . '</option>';
											}
											$sel .= '
												</select>';
											echo $sel;
										} else {
											if (isset($data_periksa->dokter)) {
												echo $data_periksa->dokter;
											}
										}
										?>
									</td>
								</tr>
								<tr>
									<td>Tgl Sampling</td>
									<td>:</td>
									<td>
										<div class="input-group">
											<span class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
											<input type="text" class="form-control input-sm" value="<?= (isset($data_periksa->tgl_sampling)) ? $data_periksa->tgl_sampling : ""; ?>" name="tgl_sampling">
											<span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
											<input type="text" class="form-control input-sm time-format" value="<?= (isset($data_periksa->jam_sampling) && $data_periksa->jam_sampling != "00:00") ? $data_periksa->jam_sampling : ""; ?>" placeholder="00:00" name="jam_sampling">
										</div>
									</td>
								</tr>
								<tr>
									<td>Jenis Sample</td>
									<td>:</td>
									<td>
										<?php
										if (isset($editable)) {
											$sel = '<select class="form-control input-sm" style="width: 250px;" id="search_sampling" name="jenis_sample">';
											if (isset($data_periksa->jenis_sample) && $data_periksa->jenis_sample != "") $sel .= '
												<option value="' . $data_periksa->id_sample . '">' . $data_periksa->nama_sample . '</option>';
											$sel .= '
												</select>';
											echo $sel;
										} else {
											if (isset($data_periksa->jenis_sample)) {
												echo $data_periksa->nama_sample;
											}
										}
										?>
									</td>
								</tr>
								<tr>
									<td>NIK</td>
									<td>:</td>
									<td>
										<?= (isset($data_periksa->no_identitas)) ? $data_periksa->no_identitas : ''; ?>
									</td>
								</tr>
								<tr>
									<td>Masa Berlaku</td>
									<td>:</td>
									<td>
										<?php
										$day_berlaku = (isset($data_periksa->masa_berlaku)) ? $data_periksa->masa_berlaku : 1;
										$day_berlaku_opt = (isset($data_periksa->masa_berlaku_opt)) ? $data_periksa->masa_berlaku_opt : 'day';
										if (isset($editable)) { ?>
											<div class="form-group" style="max-width: 150px">
												<div class="input-group">
													<input type="number" class="form-control input-sm number" name="masa_berlaku" value="<?= $day_berlaku; ?>">
													<select class="form-control input-sm" name="masa_berlaku_opt">
														<option value="day" <?= ($day_berlaku_opt == 'day') ? 'selected' : ''; ?>>Hari</option>
														<option value="month" <?= ($day_berlaku_opt == 'month') ? 'selected' : ''; ?>>Bulan</option>
													</select>
												</div>
											</div>
										<?php
										} else {
											$alias_m = ($day_berlaku == 'day') ? 'Hari' : 'Bulan';
											echo $day_berlaku . " " . $alias_m;
											if ($day_berlaku > 0) {
												$date_start = $data_periksa->update_hasil_at;
												$end_date = dateIndo(date("Y-m-d", strtotime("$date_start +$day_berlaku $day_berlaku_opt")));
												echo " ($end_date)";
											}
										}
										?>
									</td>
								</tr>
								<tr>
									<td>Biaya Pemeriksaan</td>
									<td>:</td>
									<td>
										<b><?= (isset($biaya_pemeriksaan)) ? "Rp. " . format_number($biaya_pemeriksaan) : 'Belum ditentukan'; ?></b>
										<input type="hidden" name="biaya" value="<?= $biaya_pemeriksaan; ?>">
									</td>
								</tr>
								<tr>
									<td>Asuransi</td>
									<td>:</td>
									<td>
										<div class="form-group">
											<select  name="asuransi" id="asuransi" class="form-control input-sm" placeholder="Asuransi" autocomplete="off">
											<option value="<?=(isset($data_periksa->asuransi)) ? $data_periksa->asuransi : "";?>"><?=(isset($data_periksa->nama_asuransi)) ? $data_periksa->nama_asuransi : "";?></option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td>No Asuransi</td>
									<td>:</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control input-sm autofill" name="no_asuransi" value="<?=(isset($data_periksa->no_asuransi)) ? $data_periksa->no_asuransi : "";?>">
										</div>
									</td>
								</tr>
								<tr>
									<td>Perujuk</td>
									<td>:</td>
									<td>
										<div class="form-group">
											<select  name="perujuk" id="perujuk" class="form-control input-sm" placeholder="Perujuk" autocomplete="off">
												<option value="<?=(isset($data_periksa->perujuk)) ? $data_periksa->perujuk: "";?>"><?=(isset($data_periksa->nama_perujuk)) ? $data_periksa->nama_perujuk : "";?></option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td>Nama Perujuk</td>
									<td>:</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control input-sm autofill" name="nama_tenaga_perujuk" value="<?=(isset($data_periksa->nama_tenaga_perujuk)) ? $data_periksa->nama_tenaga_perujuk : "";?>">
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<input type="hidden" name="category" value="<?=$category;?>">
					<?php if($category=='covid'){ ?>
						<div class="row">
							<div class="current_hasil col-lg-8 col-md-8 col-sm-12 pl-5">
								<?php
								
								 if ($data_periksa->hasil!="" && isset($detail_periksa) && !empty($detail_periksa)) { ?>
								 	<b>Hasil Pemeriksaan</b>
									<table class="table dataTable table-bordered minimize-padding-all">
										<thead>
											<tr>
												<th>Pemeriksaan</th>
												<th>Hasil</th>
												<th>Nilai Rujukan</th>
												<th>Metode</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($detail_periksa as $item) { ?>
												<tr>
													<td><?= $item->nama_pemeriksaan; ?></td>
													<td><?= $item->hasil; ?></td>
													<td><?= $item->nilai_rujukan; ?></td>
													<td><?= $item->metode; ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php }
								?>  
							</div>
						</div>
					<?php 
					}else{ // end covid, next umum
					
					 ?>
					<div class="row">
						<div class="col-10 offset-1 mt-2 bg-info-light2">
							<!-- ROW HEADER -->
							<div class="row mb-1">
								<div class="col-1 bg-warning pt-2 pb-2">
									<b>No</b>
								</div>
								<div class="col-4 bg-warning pt-2 pb-2 pl-0">
									<b>Item Pemeriksaan</b>
								</div>
								<div class="col-3 bg-warning pt-2 pb-2">
									<b>Hasil</b>
								</div>
								<div class="col-3 bg-warning pt-2 pb-2">
									<b>Nilai Rujukan</b>
								</div>
								<div class="col-1 bg-warning pt-2 pb-2">
									<b>Satuan</b>
								</div>
							</div>
							<?php 
							// echo "<pre>";
							// print_r($detail_periksa);
							// echo "</pre>";
							$no=1;
							foreach($detail_periksa as $k=>$item){
								$dt=(Object) $item;
							?>
							<!-- ROW ITEM PERIKSA -->
							<div class="row row-item mb-1 main" data-index="1">
								<div class="col-1 border">
									<b class="no"><?=$no;?></b>
								</div>
								<div class="col-4 pl-0">
									<input type="text" name="item_periksa_umum[<?=$k;?>][name]" readonly value="<?=$dt->item;?>" class="form-control input-sm" placeholder="Item Periksa">
								</div>
								<div class="col-3">
									<input type="text" name="item_periksa_umum[<?=$k;?>][hasil]" value="<?=(isset($dt->hasil)) ? $dt->hasil : "";?>" class="form-control input-sm" placeholder="Hasil">
								</div>
								<div class="col-3">
									<textarea name="item_periksa_umum[<?=$k;?>][rujukan]" rows="1" class="form-control input-sm" placeholder="Nilai Rujukan"><?=$dt->nilai_rujukan;?></textarea>
								</div>
								<div class="col-1">
									<input type="text" name="item_periksa_umum[<?=$k;?>][satuan]" value="<?=$dt->satuan;?>" class="form-control input-sm" placeholder="Satuan Nilai">
								</div>
							</div>
							<?php
							$no++;
								if(isset($dt->sub)){
									foreach($dt->sub as $s=>$subitem){
										$sub=(Object) $subitem;
							?>
								<!-- SUB ROW ITEM PERIKSA -->
								<div class="row row-sub-item mb-1 sub" data-index="<?=$k;?>">
									<div class="col-1">
										
									</div>
									<div class="col-4 pl-2">
										<input type="text" readonly name="item_periksa_umum[<?=$k;?>][sub][<?=$s;?>][name]" value="<?=$sub->item;?>" class="form-control input-sm " placeholder="Sub Item Periksa">
									</div>
									
									<div class="col-3">
										<input type="text" name="item_periksa_umum[<?=$k;?>][sub][<?=$s;?>][hasil]" value="<?=(isset($sub->hasil)) ? $sub->hasil : "";?>" class="form-control input-sm" placeholder="Hasil">
									</div>
									<div class="col-3">
										<textarea rows="1" name="item_periksa_umum[<?=$k;?>][sub][<?=$s;?>][rujukan]" class="form-control input-sm" placeholder="Nilai Rujukan"><?=$sub->nilai_rujukan;?></textarea>
									</div>
									<div class="col-1">
										<input type="text" name="item_periksa_umum[<?=$k;?>][sub][<?=$s;?>][satuan]" value="<?=$sub->satuan;?>" class="form-control input-sm" placeholder="Satuan Nilai">
									</div>
								</div>

								<!-- END SUB ROW ITEM PERIKSA -->
							<?php
									} // end for each subitem
								} // end for each iteme
								} ?>
							<!-- END ROW HEADER -->
							
						</div>
					</div> 
					<!-- end row -->
					
					<?php } //end if umum ?>
					<div class="row">
						<div class="col-lg-12">
							<?php
							if (isset($data_notes)) {
								echo '<b>Notes: </b> *';
								echo '
										<ul class="note">';
								foreach ($data_notes as $dt) {
									$link = (isset($editable)) ? '<i class="fa fa-times text-warning link-delete pointer " style="margin-left: 20px" title="Hapus"></i>' : '';
									echo "<li>
													<input type='checkbox' name='id_notes[]' class='hide' value='" . $dt->id . "' checked>" . $dt->notes . " " . $link . "
													</li>";
								}
								echo '</ul>';
							}
							if (isset($editable)) {
								echo '<div class="" style="width: 500px">
										<div class="input-group">
											<div class="input-group-prepend"><span class="input-group-text">Tambah Note:</span></div> 
											<select class="form-control input-sm" style="width: 320px;" id="search_notes"></select>
										</div>
										<input type="checkbox" name="note_updates" class="hide" id="note_updates" value="true">
									</div>';
							}
							?>
						</div>
					</div>
					<p></p>
				</div>
				<?php if (isset($editable)) { ?>
					<div class="card-footer">
						<button type="button" class="btn btn-primary" id="submit_pemeriksaan"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
						<a href="<?= base_url(conf('path_module_lab') . 'pemeriksaan'); ?>" class="btn btn-warning pull-right"><i class="fa fa-chevron-left"></i> <?= lang('label_back'); ?></a>
					</div>
				<?php } ?>
			</form>
		</div>
	</section>
</div>

<style>
	table.v-center {
		display: table;
	}

	table.v-center tr td {
		display: table-cell;
		vertical-align: middle;
	}

	.inline-block {
		display: inline-block;
	}

	ul.note li .link-delete {
		display: none;
	}

	ul.note li:hover {
		color: #ff0000;
	}

	ul.note li:hover>.link-delete {
		display: inline-block;
	}
</style>
