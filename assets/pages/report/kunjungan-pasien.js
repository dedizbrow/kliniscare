
$currentParams = {}
$tableID = "#tableData"
$url_data=base_url('report/load-dt-kunjungan-pasien')
$tableData = $($tableID).DataTable({
	processing: true,
	serverSide: true,
	ordering: true,
	pageLength: 50,
	ajax: {
			url: $url_data,
			type: 'POST',
			headers: {
					'x-user-agent': 'ctc-webapi',
			},
			data: function(d) {
				d.clinic_id = getSelectedClinic();
				d.poli=$("[name='select_poli'] :selected").val()
				d.dokter=$("[name='select_dokter'] :selected").val()
				d.start_date = $("[name='start_date']").val();
				d.end_date = $("[name='end_date']").val();
			}
	},
	language: DataTableLanguage(),
	scrollY: '60vh',
	// scrollCollapse: true,
	scrollX: true,
	order: [[0,'desc']],
	columnDefs: [
			// { targets: [0], width: '25px',className: 'text-center' },
			// { targets: [1], width: '40px',className: 'text-center' },
			// { targets: [3], width: '60px',className: 'text-left' },
			// { targets: [4], width: '90px',className: 'text-left' },
			// { targets: [6,7,8,9], width: '90px',className: 'text-right'},
	],
	rowCallback: function(row, data, iDisplayIndex){
			var info = this.fnPagingInfo();
			var page = info.iPage;
			var length = info.iLength;
			var index = page * length + (iDisplayIndex + 1);
			$('td:eq(0)', row).html(index);
	},
	drawCallback: function() {
		var api = this.api();
		$currentParams=api.ajax.params();
	}
})
$(document)
	.ready(function () {
		getActiveLang('report');
	})
	.on("click", "#export", function () {
		console.log($currentParams)
		var newparams = $currentParams;
		newparams['export'] = true
		var params = $.param(newparams)
		window.location.href=$url_data+'?'+params
  })
$("[name='select_poli']").select2({
	minimumInputLength: 0,
	placeholder: "Pilih Poli",
	width: "200px",
	tags: true,
	ajax: { 
		url: base_url('common/search-poli-select2'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term,
				clinic_id: getSelectedClinic()
			};
		},
		processResults: function (search) {
			return {
				results: search
			};
		},
		cache: true
	}
}).on("change", function () {
	$tableData.ajax.reload()
})
$("[name='select_dokter']").select2({
	minimumInputLength: 0,
	placeholder: "Pilih Dokter",
	width: "200px",
	ajax: { 
		url: base_url('common/search-dokter-select2'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term,
				clinic_id: getSelectedClinic()
			};
		},
		processResults: function (search) {
			return {
				results: search
			};
		},
		cache: true
	}
}).on("change", function () {
	$tableData.ajax.reload()
})
$("[name='start_date']").datepicker({
	dateFormat: 'yy-mm-dd'
}).on("change", function () {
	var date = $(this).val()
	if ($("[name='end_date']").val() == '') $("[name='end_date']").val(date)
	$tableData.ajax.reload()
})
$("[name='end_date']").datepicker({
	dateFormat: 'yy-mm-dd'
}).on("change", function () {
	$tableData.ajax.reload()
})
