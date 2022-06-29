$modalManageBiaya		= "#modal-manage-biaya";
$modalManageKategori    = "#modal-manage-kategori";
$tableData				= $("#dataBiaya").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('keuangan/pengeluaran/load-dt'),
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
    // responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: true,
    order: [[1,'desc']],
    columnDefs: [
		{ targets: [0], width: '30px',className: 'text-center' },
		{ targets: [1,2], width: '80px',className: 'text-center' },
		{ targets: [3], width: '100px'},
		{ targets: [4], width: '80px',className: 'text-right'},
		{ targets: [5], width: '300px'},
		{ targets: [6], width: '50px',className: 'text-center'},
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
		getActiveLang('keuangan/pengeluaran');
	})

	.on("click", ".add-biaya", function () {
		$modal_id=$modalManageBiaya;
		$modal_body=$($modal_id).find('.modal-body');
		
		$modal_body.find("input:text").val("");
		$modal_body.find("textarea").val('');
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
		http_request('keuangan/pengeluaran/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageBiaya;
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
	.on("submit","form[name='form-manage-biaya']",function(e){
		e.preventDefault();
		$("#save-obat").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('keuangan/pengeluaran/save__','POST',data)
		.done(function(res){
			$($modalManageBiaya).modal('hide');
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
							http_request('keuangan/pengeluaran/delete__/'+id,'DELETE',{})
							.done(function(res){
									Msg.success(res.message);
									$tableData.ajax.reload(null,false);
							})
						}
			}
		});
		})
        
	.on("click", ".add-kategori", function () {
		$($modalManageBiaya).modal('hide');
		$modal_id=$modalManageKategori;
		$modal_body=$($modal_id).find('.modal-body');
		
		$modal_body.find("input:text").val("");
		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})

	.on("submit","form[name='form-manage-kategori']",function(t){
		t.preventDefault();
		$("#save-kategori").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('keuangan/pengeluaran/save_kategori','POST',data)
		.done(function(res){
			$($modalManageKategori).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-kategori").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-kategori").removeAttr('disabled');
			})
			.always(function () {
			$("#save-kategori").removeAttr('disabled');
			$($modalManageBiaya).modal('show');
		})
	})

	$("#kategori").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih Kategori",
		tags: true,
		ajax: { 
		url: base_url('keuangan/pengeluaran/select2_kategori'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term,
				clinic_id: getSelectedClinic(),
			};
		},
		processResults: function (search) {
			return {
				results: search
			};
		},
			cache: true
		}	
	})
	$("[name='bulan']").select2({
		minimumResultsForSearch: -1,
	}).on("change", function () {
		$tableData.ajax.reload();
	});
	$("[name='bulan']").select2({
		minimumResultsForSearch: -1,
	}).on("change", function () {
		$tableData.ajax.reload();
	});
	$("[name='tahun']").select2({
		minimumResultsForSearch: -1,
	}).on("change", function () {
		$tableData.ajax.reload();
	});
