<div class="row">
    <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card card-dashboard-one">
            <div class="card-header border c-header">
                <div class="card-title">
                    Daftar Antrian Ditunda
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped minimize-padding-all" id="dataDitunda" role="grid" aria-describedby="dataTable_info" style="width: 100%;" width="100%" cellspacing="0">
                    <thead>
                        <tr role="row">
                            <th><?= lang('label_number'); ?></th>
                            <th><?= lang('label_queue'); ?></th>
                            <th><?= lang('label_invoice'); ?></th>
                            <th><?= lang('label_visit'); ?></th>
                            <th><?= lang('label_type'); ?></th>
                            <th><?= lang('label_rm_number'); ?></th>
                            <th><?= lang('label_name'); ?></th>
                            <th><?= lang('label_address'); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>