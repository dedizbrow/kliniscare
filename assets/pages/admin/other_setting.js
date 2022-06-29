$modalManageCompany    = "#modal-manage-company-detail";
$(document)
	.ready(function () {
		getActiveLang('admin/other_setting');
	})
	.on("change", "#source_clinic", function () {
		var value = $(this).val()
		location.href=base_url('admin/other-setting?clinic_id='+value)
	})
    
	.on("click",".link-edit-company-detail",function(){
		http_request('admin/other_setting/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageCompany;
			$modal_body = $($modal_id).find('.modal-body');
			
			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
			})
			
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("submit","form[name='form-manage-company-detail']",function(e){
		e.preventDefault();
		$("#save-company").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('admin/other_setting/save__','POST',data)
		.done(function(res){
			Msg.success(res.message);
			setTimeout(function () {
				location.href=base_url('admin/other_setting')
			},1000)
			$("#save-company").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-company").removeAttr('disabled');
			})
			.always(function () {
			$("#save-company").removeAttr('disabled');
		})
		
	})
	.on("click", ".choose_file__", function () {
		$(this).closest('div').find('input:file').trigger('click');
	})
	.on("change", "input:file[name^='file']", function () {
		$that = $(this);
		var id=$(this).attr('id')
		var btn_control = $(this).closest('div').find('.btn');
		btn_control.removeClass('btn-warning').addClass('btn-primary');
		//btn_control.find('i.fa').addClass('fa-spin');
		//btn_control.find('span').html('Uploading.. <i class="fa fa-spinner fa-spin"></i>');
		let file = document.getElementById(id).files[0];
		if (file.name == "") return false;
		$that.closest('div').find('.filename').text(file.name);
		// process upload with create self form
		bootbox.confirm({
			title: 'Ganti gambar?',
			message: $lang.bootbox_message_confirm_upload+"<br>"+file.name,
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
					var formData = new FormData();
					formData.append('file', $('#'+id)[0].files[0]); 
					formData.append('clinic_id',getSelectedClinic())
					$.ajax({
						url: base_url('admin/other-setting/update-img/'+id),
						type: 'POST',
						headers: {
                'x-user-agent': 'ctc-webapi',
                'x-access-token': getToken(),
                'x-domain': base_url(''),
            },
						data: formData,
						async: false,
						cache: false,
						contentType: false,
						enctype: 'multipart/form-data',
						processData: false,
						success: function (response) {
							Msg.success(response.message);
							// $tableData.ajax.reload();
							setTimeout(function () {
								location.reload();
							},500)
						},
						error: function (err) {
							console.log(err)
							var res = err.responseJSON
							if(res==undefined) res=JSON.parse(err.responseText)
							var message = res.error || res.message
							console.log(res)
							Msg.error(message);
						},
						complete: function (response) {
							btn_control.removeClass('btn-purple').addClass('btn-warning');
							btn_control.find('i.fa').removeClass('fa-spin');
							btn_control.find('span').text('Import');
						}
					})
				}else{
					btn_control.removeClass('btn-purple').addClass('btn-warning');
					btn_control.find('i.fa').removeClass('fa-spin');
					btn_control.find('span').text('Import');
					$that.closest('form').get(0).reset();
				}
			}
		});
	})
	.on("click", ".save-inline", function () {
		$(this).closest('div').find('input:text').trigger({type: 'keypress',which: 13})
	})
	.on("keypress", "#text_city_of_klinik", function (e) {
		var val = $(this).val();
		if (e.which === 13) {
			bootbox.confirm({
				title: 'Konfirmasi',
				message: 'Apakah anda yakin untuk update kota/kabupaten?',
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
						http_request('admin/other-setting/update-city', 'POST', { city: val})
							.done(function (response) {
								Msg.success(response.message);
								// $tableData.ajax.reload();
								location.reload();
							})
					}
				}
			})
		}
	})
