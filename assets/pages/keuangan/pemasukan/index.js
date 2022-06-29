$tableData = $("#dataPemeriksaanLab").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "25",
    ajax: {
        url: base_url('keuangan/pemasukan/load-pemeriksaan-lab'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id=getSelectedClinic();
			d.bulan = $("[name='bulan'] option:selected").val();
			d.tahun = $("[name='tahun'] option:selected").val();
        }
    },
	language: DataTableLanguage(),
	scrollY: '60vh',
	scrollCollapse: true,
	"bFilter": false,
	scrollX: true,
	order: [[0,'desc']],
	columnDefs: 
	[
			{ targets: [0], width: '25px',className: 'text-center' },
			{ targets: [1], width: '120px',className: 'text-left' },
			{ targets: [4], width: '120px',className: 'text-right' }
	],
	rowCallback: function(row, data, iDisplayIndex){
			var info = this.fnPagingInfo();
			var page = info.iPage;
			var length = info.iLength;
			var index = page * length + (iDisplayIndex + 1);
			$('td:eq(0)', row).html(index);
	},
})
$(document)
	.ready(function () {
		getActiveLang('keuangan/pemasukan');
	})
	.on("change", "#source_clinic", function () {
		var value = $(this).val()
		location.href=base_url('keuangan/pemasukan?clinic_id='+value)
	})
	$("[name='tahun']").select2({
		minimumResultsForSearch: -1,
	});
	$("[name='bulan']").select2({
		minimumResultsForSearch: -1,
	});
