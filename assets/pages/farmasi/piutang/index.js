$modalManagePembayaranPiutang	= "#modal-manage-pembayaran-piutang";
$modalDetail					= "#modal-detail"
$tableData		= $("#dataPiutang").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('farmasi/piutang/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id = getSelectedClinic();
			d.start_date = $("[name='start_date']").val();
			d.end_date = $("[name='end_date']").val();
        }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    order: [[1,'desc']],
    columnDefs: [
		{ targets: [0], width: '35px',className: 'text-center' },
		{ targets: [2,3], width: '90px' },
		{ targets: [5,6,7], width: '90px',className: 'text-right'},
		{ targets: [8], width: '50px' },
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
		getActiveLang('farmasi/obat');
	})
    
	.on("click",".link-bayar-piutang",function(){
		http_request('farmasi/piutang/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			var sisa=result.sisa;
			var total_dibayar=result.total_dibayar;
			$modal_id=$modalManagePembayaranPiutang;
			$modal_body = $($modal_id).find('.modal-body');
			$modal_body.find("[name='biaya']").val("");
			$modal_body.find("#kembalian").val("");
			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
			})
			
			$.each(data, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(sisa, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(total_dibayar, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$("#autocode").prop('disabled', true);

			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("submit","form[name='form-manage-pembayaran-piutang']",function(e){
		e.preventDefault();
		$("#save-pembayaran-piutang").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('farmasi/piutang/save__','POST',data)
		.done(function(res){
			$($modalManagePembayaranPiutang).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-pembayaran-piutang").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-pembayaran-piutang").removeAttr('disabled');
			})
			.always(function () {
			$("#save-pembayaran-piutang").removeAttr('disabled');
		})
		
	})
	.on("click",".link-detail-piutang",function(){
		http_request('farmasi/piutang/search_detail_bayar_piutang','GET',{id: $(this).data('id')})
		.done(function (result) {
			
			var pem='<tr style="background-color:lightgrey;">'+
			'<th width="70%">Tanggal Pembayaran</th>'+
			'<th width="30%" align="right">Total dibayarkan</th>'+
		'</tr>'
			$.each(result.detail, function (index, item) {
				
				pem += '<tr style="vertical-align: top;">' +
				'<td >'+item.create_at+ '</td>'+
				'<td align="right">'+item.biaya+ '</td>'+
				'</tr > ';
			})
			$("#tableDetail").html(pem)
	
			$($modalDetail).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
			})
		})
	})
	const hitungTotal = (total, dibayar) => {
		hasil=dibayar-total;
		return (hasil); 
	}
	const	$total	= $('.sisa'),
			$kembalian	= $('#kembalian'),
			$dibayar	= $('input[name="biaya"]');
	
	$dibayar.add($total).on('input', () => {
		if ( $dibayar.val().length) {      
		total = hitungTotal($total.text(),$dibayar.val());
		}
		$kembalian.val( total );
	});
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
