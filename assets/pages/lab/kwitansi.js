$modalManagePemeriksaan = "#modal-manage-pasien";
$tableData = $("#dataKwitansi").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: 50,
    ajax: {
        url: base_url($module_path_lab+'kwitansi/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
					// d.filter_provider = $("[name='filter_provider'] option:selected").val();
					// d.filter_pemeriksaan = $("[name='filter_pemeriksaan'] option:selected").val();
					// d.filter_tgl_option = $("[name='filter_tgl_option'] option:selected").val();
					// d.filter_status = $("[name='filter_status'] option:selected").val();
					// d.start_date = $("[name='start_date']").val();
					// d.end_date = $("[name='end_date']").val();
        }
    },
    language: DataTableLanguage(),
    // responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: true,
    sorting: [[2,'DESC']],
    columnDefs: [
        {targets: [0,3],width: '80px',class: 'text-center'},
        {targets: [1],width: '100px',class: 'text-center'},
        {targets: [3],visible: false,width: '100px',class: 'text-center'},
        {targets: [4,5],width: '100px'},
        {targets: [6],width: '80px',class: 'text-center'},
        {targets: [-1],width: '50px',class: 'text-center'}
    ],
	rowCallback: function (row, data, iDisplayIndex) {
		switch (data[9]) {
			case 'CANCEL':
				$('td', row).addClass('tx-danger');
				break;
			case 'SELESAI':
				$('td', row).addClass('tx-primary');
				break;
			default:
				break;
		}
  }
})

$("[name='filter_status']").select2().on("change", function () {
	$tableData.ajax.reload();
});
$(document)
	.on("click", ".confirm-bayar", function () {
		var id = $(this).data('id'), tn = $(this).data('tn');
	bootbox.confirm({
        title: $lang.bootbox_title_confirmation,
        message: 'Apakah anda yakin untuk confirm bayar?',
        size: 'small',
        buttons: {
            confirm: {
                label: $lang.bootbox_btn_confirm,
                className: 'btn-success'
            },
            cancel: {
                label: $lang.bootbox_btn_no,
                className: 'btn-danger'
            }
        },
        callback: function (result) {
			if(result){
				http_request($module_path_lab+'kwitansi/confirm-bayar/','PUT',{id: id,tn: tn})
				.done(function(res){
					Msg.success(res.message);
					$tableData.ajax.reload(null, false);
					window.open(res.link,'_blank')
				})
			}
        }
    });
})
