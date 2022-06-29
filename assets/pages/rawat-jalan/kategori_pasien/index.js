$modalManageKategori  = "#modal-manage-kategori-pasien";
$tableData				= $("#dataBiaya").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('rawat-jalan/kategori_pasien/load-dt'),
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
		getActiveLang('rawat-jalan/kategori_pasien');
	})

	.on("click", ".add-biaya", function () {
		$modal_id=$modalManageKategori;
		$modal_body=$($modal_id).find('.modal-body');
		
		$modal_body.find("input:text").val("");
		$modal_body.find("select").val('').trigger('change');

		$modal_body.find("#autocode").prop('disabled', false);

		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
    
	.on("click",".link-edit-biaya",function(){
		http_request('rawat-jalan/kategori_pasien/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageKategori;
			$modal_body = $($modal_id).find('.modal-body');

			$('#kategori').html('<option value="'+data.kategori_biaya+'">'+data.nama_kategori+'</option>').trigger('change');
			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
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
	.on("submit","form[name='form-manage-kategori-pasien']",function(e){
		e.preventDefault();
		$("#save-obat").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-jalan/kategori_pasien/save__','POST',data)
		.done(function(res){
			$($modalManageKategori).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-biaya").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-biaya").removeAttr('disabled');
			})
			.always(function () {
			$("#save-biaya").removeAttr('disabled');
		})
		
	})
	.on("click",".link-delete-biaya",function(){
		hideMsg();
		var id=$(this).data('id');
		$that=$(this);
		bootbox.confirm({
			title: $lang.bootbox_title_confirmation,
			message: $lang.bootbox_message_confirm_remove,
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
							http_request('rawat-jalan/kategori_pasien/delete__/'+id,'DELETE',{})
							.done(function(res){
									Msg.success(res.message);
									$tableData.ajax.reload(null,false);
							})
						}
			}
		});
		})
        

    $('[name="tanggal"]').datepicker({
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dateFormat: 'yy-mm-dd',
		reverseYearRange: true,
		yearRange: 'c:c+80',
		container: '#modal-manage-biaya',
		beforeShow: function(input, instance) { 
			$(input).datepicker('setDate', new Date());
		}
	})