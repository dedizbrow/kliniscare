<div class="row">
    <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card border border-secondary">
            <div class="card-body bg-blue-light">
                <div class="row">
                    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label" for="invoice"><?= lang('label_invoice'); ?></label>
                                    <input class="form-control input-sm clear" id="invoice" name="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label" for="visit"><?= lang('label_visit'); ?></label>
                                            <input class="form-control input-sm clear" id="visit" name="tanggal" autocomplete="off">
                                        </div>
                                    </div>
                                    <label style="margin-top:29px;">s.d</label>
                                    <div class="col">
                                        <div class="form-group">
                                            <input class="form-control input-sm clear" id="" name="tanggal" autocomplete="off" style="margin-top:25px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label" for="norm"><?= lang('label_norm'); ?></label>
                                    <input class="form-control input-sm clear" id="norm" name="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="form-label" for="patientname"><?= lang('label_patientname'); ?></label>
                                    <input class="form-control input-sm clear" id="patientname" name="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="row">
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <button type="button" name="" id="" class="btn btn-primary btn-block btn-xs" style="margin-top:25px;">Cari</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <button type="button" name="" id="" class="btn btn-success btn-block btn-xs" style="margin-top:25px;">Excel</button>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <button type="button" name="" id="" class="btn btn-warning btn-block btn-xs" style="margin-top:25px; border:1px solid #ff8d07;">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div class="row">
    <section class="col-lg-12">
        <div class="card border border-secondary">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-1 col-md-4 col-sm-4 col-xs-4">
                        <button type="button" name="autocode" id="autocode" class="btn btn-primary btn-block btn-xs cari-pasien" style="margin-bottom:11px;">Input Data</button>
                    </div>
                </div>
                <table class="table table-bordered dataTable table-striped" id="dataObat" role="grid" aria-describedby="dataTable_info" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= lang('label_norm'); ?></th>
                            <th><?= lang('label_category'); ?></th>
                            <th><?= lang('label_patientname'); ?></th>
                            <th><?= lang('label_hpnum'); ?></th>
                            <th><?= lang('label_address'); ?></th>
                            <th><?= lang('label_mothername'); ?></th>
                            <th><?= lang('label_job'); ?></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>