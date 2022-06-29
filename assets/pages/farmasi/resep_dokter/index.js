$modalManageResep			= "#modal-manage-detail-resep";
$tableData				= $("#dataTelahperiksa").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('farmasi/resep_dokter/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id=getSelectedClinic()
			d.start_date = $("[name='start_date']").val();
			d.end_date = $("[name='end_date']").val();
        }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    // scrollY: '50vh',
	"bFilter": false,
    // scrollCollapse: true,
    scrollX: false,
    order: [[0,'desc']],
    columnDefs: [
		{ targets: [0], width: '35px',className: 'text-center' },
		{ targets: [1], width: '120px',className: 'text-center' },
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
		getActiveLang('farmasi/resep_dokter');
	})
    
.on("click",".link-detail-resep",function(){
    http_request('farmasi/resep_dokter/search_','GET',{id: $(this).data('id')})
    .done(function (result) {
        var data=result.data;
        $modal_id=$modalManageResep;
			$modal_body = $($modal_id).find('.modal-body');

        var total_item=0, total_price=0
        var htm='<tr style="background-color:lightgrey;">'+
        '<th>Nama Obat</th>'+
		'<th>Supplier</th>'+
        '<th>Harga</th>'+
        '<th>Qty</th>'+
        '<th>Aturan</th>'+
        '<th>Cara Pakai</th>'+
        '<th>Total</th>'+
    '</tr>'
        $.each(result.detail, function (index, item) {
            total_item++
            total_price+=parseFloat(item.total)
            htm += '<tr style="vertical-align: top;">' +
            '<td>'+item.nama_obat+'</td>'+
			'<td>'+item.namaSupplier+'</td>'+
            '<td align="right">'+item.harga+'</td>'+
            '<td>'+item.qty+' @'+item.namaSatuanobat+'</td>'+
            '<td>'+item.nama_aturan_pakai+'</td>'+
            '<td>'+item.nama_cara_pakai+'</td>'+
            '<td align="right">'+item.total+'</td>'+
            '</tr > ';
        })
        $("#tableDetail").html(htm)

        // var summ = $("#tableSummary")
        // summ.find('.total_item').text(total_item)
        // summ.find('.total_price').text(total_price)
       

        var pasien = $("#pasien")
        pasien.find('.nama_lengkap').text(result.data.nama_lengkap)
        pasien.find('.nomor_rm').text(result.data.nomor_rm)
        pasien.find('.create_at').text(result.data.create_at)
        pasien.find('.no_invoice').text(result.data.no_invoice)

        $.each(data, function (key, val) {
				$modal_body.find("[name='"+ key +"']").val(val);
			})
        $($modalManageResep).modal({
            effect: 'effect-slide-in-right',
            backdrop: 'static',
            keyboard: false,
            show: true
        })
    })
})

    .on("submit","form[name='form-manage-detail-resep']",function(e){
		e.preventDefault();
		$("#save-dokter").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('farmasi/resep_dokter/simpan','POST',data)
		.done(function(res){
			$($modalManageResep).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-resep").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-resep").removeAttr('disabled');
			})
			.always(function () {
			$("#save-resep").removeAttr('disabled');
		})
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
