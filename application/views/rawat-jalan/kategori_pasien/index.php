<div class="row">
    <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card card-dashboard-one">
            <div class="card-header border" style="background: #E3F2FD">
                <div class="card-title">
                    <?= lang('label_list_patient'); ?>
                    <?php
                    if (isset($import_id)) {
                        echo "<input type='hidden' id='import_id' value='" . $import_id . "'><br>
							<div class='tx-primary txt-normal mt-2'>[Data yang ditampilkan adalah yang baru saja anda import] 
							<a class='text-normal tx-danger' href='" . base_url('obat') . "' title='Click disini untuk kembali ke keseluruhan data obat'><sup><i class=''></i> clear filter</sup></a>
							</div>
							";
                    }
                    ?>
                </div>

                <div class="pull-right">
                    <button type="button" class="btn btn-primary add-biaya btn-xs"><i class="fa fa-plus"></i> Tambah Data</button>
                    <!-- <button type="button" class="btn btn-warning import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button> -->
                    <a href="<?php echo site_url(); ?>farmasi/biaya/export_"><button type="button" class="btn btn-secondary btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered dataTable table-striped" id="dataBiaya" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
                    <thead>
                        <tr role="row">
                            <th>No.</th>
                            <th>Jumlah pasien</th>
                            <th>Nama</th>
                            <th>Nomor inisial</th>
                            <th>Panjang Digit </th>
                            <th>No. RM Terakhir</th>
                            <th>No. RM Berikutnya</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div id="modal-manage-kategori-pasien" class="modal">
    <div class="modal-dialog" role="document">
        <form method="POST" name="form-manage-kategori-pasien">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add/Update Data</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?= lang('label_name'); ?></label>
                                <input class="form-control no-space input-sm" id="nama_kategori" name="nama_kategori" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?= lang('label_init'); ?></label>
                                <input class="form-control no-space input-sm" id="no_init" name="no_init" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label"><?= lang('label_lenght'); ?></label>
                                <input class="form-control no-space input-sm" id="panjang_digit" name="panjang_digit" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" type="checkbox"><?= lang('label_action'); ?></label>
                                <select name="status" id="status" class="form-control input-sm clear">
                                    <option value="aktif">Aktif</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                        <input type="text" class="hide" name="_id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="save-biaya" class="btn btn-success"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('label_close'); ?></button>
                </div>
            </div>
        </form>
    </div><!-- modal-dialog -->
</div><!-- modal -->
<div id="modal-manage-kategori" class="modal">
    <!-- modal kategori obat-->
    <div class="modal-dialog" role="document">
        <form method="POST" name="form-manage-kategori">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add data</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="form-label"><?= lang('label_category'); ?></label>
                                <input type="text" class="form-control input-sm" name="nama_kategori" id="nama_kategori" autocomplete="off" placeholder="<?= lang('label_category'); ?>">
                            </div>
                        </div>
                    </div>
                    <input type="text" class="hide" name="_idkategori" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" id="save-kategori" class="btn btn-indigo"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>