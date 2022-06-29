$modalManagePemeriksaan = "#modal-manage-pasien";
$tableData = $("#dataPemeriksaan").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: 50,
    ajax: {
        url: base_url($module_path_lab+'pemeriksaan/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
			data: function (d) {
					d.clinic_id=getSelectedClinic()
					d.filter_provider = $("[name='filter_provider'] option:selected").val();
					d.filter_pemeriksaan = $("[name='filter_pemeriksaan'] option:selected").val();
					d.filter_tgl_option = $("[name='filter_tgl_option'] option:selected").val();
					d.filter_status = $("[name='filter_status'] option:selected").val();
					d.start_date = $("[name='start_date']").val();
					d.end_date = $("[name='end_date']").val();
        }
    },
    language: DataTableLanguage(),
    // responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: true,
    sorting: [[2,'DESC']],
    columnDefs: [
        {targets: [0],width: '90px',class: 'text-left'},
        {targets: [13],width: '60px',class: 'text-center'},
        {targets: [5],width: '100px',class: 'text-left',visible: false}, // provider
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
		if(data[10]==data[11]) $('td',row).removeClass('tx-primary').addClass('tx-success')
  }
})

$(document)
	.ready(function () {
		getActiveLang($module_path_lab+'pemeriksaan');
		
	})

	.on("click",".link-cancel-pemeriksaan",function(){
    hideMsg();
    var id=$(this).data('id');
    $that=$(this);
    bootbox.confirm({
        title: $lang.bootbox_title_confirmation,
        message: $lang.bootbox_message_confirm_cancel,
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
						http_request($module_path_lab+'pemeriksaan/cancel__/'+id,'PUT',{})
						.done(function(res){
							Msg.success(res.message);
							$tableData.ajax.reload(null, false);
						})
					}
        }
    });
	})
		.on("click",".link-cancel-pemeriksaan-with-comment",function(){
    hideMsg();
    var id=$(this).data('id');
    $that=$(this);
    bootbox.prompt({
        title: "<span class='text-normal'>Remark pembatalan</span>",
        placeholder: 'Masukkan remark pembatalan',
        size: 'small',
				inputType: "textarea",
        callback: function (result) {
					if(result){
						http_request($module_path_lab+'pemeriksaan/cancel-with-remark/'+id,'PUT',{remark: result})
						.done(function(res){
							Msg.success(res.message);
							$tableData.ajax.reload(null, false);
						}).fail(function () {
							return false
						})
					} else
						if (result === "") {
							return false
						}
        }
    });
	})
	.on("click", ".print-pdf", function () {
		var link = $(this).data('link')
		var dialog = bootbox.dialog({
			title: '<i class="fa fa-file-pdf-o tx-danger"></i> Generate Hasil Pemeriksaan',
			message: "<li>Click \"<span class='tx-success'>Tanpa Header & Tanpa Tanda Tangan</span>\", jika anda akan print pada kertas yang sudah punya header dan perlu stamp basah</li>" +
				"<li>Click \"<span class='tx-warning'>Dengan Header & Tanda Tangan</span>\", jika anda akan print pada kertas kosong dan tidak perlu stamp basah</li>"+
				"<li>Click \"<span class='tx-primary'>Dengan Header & Tanpa Tanda Tangan</span>\", jika anda akan print pada kertas kosong dan perlu stamp basah</li>",
			size: 'large',
			buttons: {
					without_header: {
							label: "Tanpa Header dan<br> Tanpa Tanda Tangan",
							className: 'btn-success',
							callback: function(){
								window.open(link + '&with_header=false&with_sign=false', '_blank')
								// dialog.modal('hide')
							}
					},
					with_header: {
							label: "Dengan Header dan<br> Tanda Tangan",
							className: 'btn-warning',
							callback: function(){
								window.open(link + '&with_header=true&with_sign=true', '_blank')
								// dialog.modal('hide')
							}
					},
					with_header_no_sign: {
							label: "Dengan Header dan<br> Tanpa Tanda Tangan",
							className: 'btn-primary',
							callback: function(){
								window.open(link + '&with_header=true&with_sign=false', '_blank')
								// dialog.modal('hide')
							}
					}
			}
	});
})
$('[name*="_date"]').datepicker({
	changeMonth: true,
	clearBtn: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	yearRange: 'c-1:c',
	maxDate: 'now',
}).on("change", function () {
	$tableData.ajax.reload();
})

$('[name="tgl_lahir"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c',
	container: '#modal-manage-pasien'
});

$("[name='filter_provider']").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'Semua Provider',
	ajax: {
			url: base_url($module_path_lab+'provider/select2-/with-all'),
			headers: {
					'x-user-agent': 'ctc-webapi'
			},
			data: function(params) {
				return {
						clinic_id: getSelectedClinic(),
						search: params.term
					}
			},
			processResults: function(data){
					return {results: data};
			}
	},
})
	.on("change", function () {
		$tableData.ajax.reload();
	})
$("[name='filter_pemeriksaan']").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'Semua',
	ajax: {
			url: base_url($module_path_lab+'jenispemeriksaan/select2-'),
			headers: {
					'x-user-agent': 'ctc-webapi'
			},
			data: function(params) {
				return {
						clinic_id: getSelectedClinic(),
						search: params.term,
						current: []
					}
			},
			processResults: function(data){
					return {results: data};
			}
	},
})
	.on("change", function () {
		$tableData.ajax.reload();
	})
$("[name='filter_status']").select2().on("change", function () {
	$tableData.ajax.reload();
});
