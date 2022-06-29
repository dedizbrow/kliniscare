$tableData = $("#dataTelahperiksa").DataTable({

	serverSide: true,
	ordering: true,
	pageLength: "50",
	ajax: {
		url: base_url('kir/load-dt'),
		type: 'POST',
		headers: {
			'x-user-agent': 'ctc-webapi',
		},
		data: function (d) {
			d.clinic_id = getSelectedClinic()
		}

	},

	language: DataTableLanguage(),
	// responsive: true,
	// scrollY: '50vh',
	// scrollCollapse: true,
	bFilter: false,
	scrollX: false,
	order: [[1, 'desc']],
	columnDefs: [
		{ targets: [0], width: '35px', className: 'text-center' },
	],
	rowCallback: function (row, data, iDisplayIndex) {
		var info = this.fnPagingInfo();
		var page = info.iPage;
		var length = info.iLength;
		var index = page * length + (iDisplayIndex + 1);
		$('td:eq(0)', row).html(index);
	},
})
$(document)
	.ready(function () {
		getActiveLang('pendaftaran');
	})
	.on("keypress", "[name='lama_mc']", function (e) {
		if (e.which == 13) {
			var id = $(this).data('id');
			var val = $(this).val();
			bootbox.confirm({
				title: $lang.bootbox_title_confirmation,
				message: 'Apakah anda yakin update?',
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
					if (result) {
						http_request('kir/update-mc', 'PUT', { _id: id, lama_mc: val })
							.done(function (result) {
								Msg.success(result.message);
								$tableData.ajax.reload(null, false)
							})
					}
				}
			});

		}
	})
