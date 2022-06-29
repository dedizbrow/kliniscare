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
							<a class='text-normal tx-danger' href='" . base_url('jasa_apoteker') . "' title='Click disini untuk kembali ke keseluruhan data perujuk'><sup><i class=''></i> clear filter</sup></a>
							</div>
							";
                    }
                    ?>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-primary add-jasa-apoteker btn-xs"><i class="fa fa-plus"></i>Add</button>
                    <button type="button" class="btn btn-warning import__ btn-xs"><i class="fa fa-cloud-upload"></i> <span>Import</span> </button>
                    <a href="<?php echo site_url(); ?>master-data/jasa_apoteker/export_"><button type="button" class="btn btn-secondary btn-xs"><i class="fa fa-cloud-upload"></i> <span>Export</span> </button></a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered dataTable table-striped" id="dataJasa" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
                    <thead>
                        <tr role="row">
                            <th><?= lang('label_Number'); ?></th>
                            <th><?= lang('label_service'); ?></th>
                            <th><?= lang('label_nominalrp'); ?></th>
                            <th><?= lang('label_date'); ?></th>
                            <th><?= lang('label_status'); ?></th>
                            <th><?= lang('label_input_by'); ?></th>
                            <th><?= lang('label_action'); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div id="modal-manage-jasa-apoteker" class="modal">
    <div class="modal-dialog" role="document">
        <form method="POST" name="form-manage-jasa-apoteker">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add Data</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="form-label" for="nama"><?= lang('label_service'); ?></label>
                                <input type="text" class="form-control input-sm" id="nama" name="nama">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><?= lang('label_status'); ?></label>
                                <div class="d-grid gap-2 d-md-flex">
                                    <select id="status" name="status" class="form-control input-sm">
                                        <option value="1">Aktif</option>
                                        <option value="2">Draft</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="form-label" for="nominal"><?= lang('label_nominal'); ?></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="text" class="form-control input-sm" id="nominal" name="nominal" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="hide" name="_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" id="save-jasa-apoteker" class="btn btn-indigo"><i class="fa fa-save"></i> <?= lang('label_save'); ?></button>
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal"><?= lang('label_close'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>