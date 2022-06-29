$modalManageKunjunganIGD	= "#modal-manage-kunjunganIGD";
$tableData					= $("#dataHomeVisit").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('homeVisit/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
					if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
        }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    order: [[1,'desc']],
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
		getActiveLang('HomeVisit');
	})

.on("click", ".add-kunjunganIGD", function () {
	$modal_id=$modalManageKunjunganIGD;
	$modal_body=$($modal_id).find('.modal-body');
	
	$modal_body.find("input:text").val("");
	$modal_body.find("select").val('').trigger('change');


	$modal_body.find(".show-on-update").addClass('hide');
	$($modal_id).modal({
		effect: 'effect-slide-in-right',
		backdrop: 'static',
		keyboard: false,
		show: true
	})
})

$(function(){
	$("#tabs").tabs();
});

$('[name="tanggal"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c:c+80',
	container: '#modal-manage-pembelian',
	beforeShow: function(input, instance) { 
		$(input).datepicker('setDate', new Date());
	}
})