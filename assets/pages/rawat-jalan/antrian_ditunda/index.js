$tableData				= $("#dataDitunda").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('rawat-jalan/antrian_ditunda/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
			d.clinic_id=getSelectedClinic();
        }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    order: [[0,'asc']],
    columnDefs: [
		{ targets: [0], width: '35px',className: 'text-center' },
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
		getActiveLang('farmasi/biaya');
	})

	//update status dari ditunda ke dafftar antrian
	.on("click",".link-ke_antrian",function(){
		http_request('rawat-jalan/antrian_ditunda/ke_antrian','GET',{id: $(this).data('id')})
		.done(function(res){
			Msg.success(res.message);
			$tableData.ajax.reload();
		})
	})
