$modalManageKlinik		= "#modal-manage-klinik";
$tableData				= $("#dataKlinik").DataTable({
	serverSide: true,
	ordering: true,
	pageLength: "50",
	ajax: {
			url: base_url('admin/klinik/load-dt'),
			type: 'POST',
			headers: {
					'x-user-agent': 'ctc-webapi',
			},
			data: function(d) {
			}

	},

	language: DataTableLanguage(),
	order: [[1,'desc']],
	columnDefs: [
	{ targets: [0], width: '30px',className: 'text-center' },
	// { targets: [6], width: '50px',className: 'text-center' },
		{targets: [-1],visible: false}
	],
	rowCallback: function(row, data, iDisplayIndex){	
		var info = this.fnPagingInfo();
		var page = info.iPage;
		var length = info.iLength;
		var index = page * length + (iDisplayIndex + 1);
		$('td:eq(0)', row).html(index);
		var image = '<div class="az-img-user"> <img src="'+base_url(''+data[3])+'" alt=""> </div>';
		$("td:eq(3)",row).html(image);
		var selected = "", set_status = "Inactive", btnclass = "btn-danger";
		if(parseInt(data[5])==1){
				selected="checked='checked'"; set_status="Active";
				btnclass="btn-success";
		}else{
				$(row).addClass("text-danger");
		}
		$("td:eq(5)", row).html($('<label class="ckbox"><input type="checkbox" class="set_status_clinic" data-id="' + data[0] + '" name="is_enabled" value="1" ' + selected + '> <span>' + set_status + '</span></label>'));
		if(data[14]=='Expired') $(row).addClass('bg-warning tx-danger')
	}
})
$(document)
	.ready(function () {
		getActiveLang('admin/klinik');
	})

	.on("click", ".add-klinik", function () {
		$modal_id=$modalManageKlinik;
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
    
	.on("click",".link-edit-klinik",function(){
		http_request('admin/klinik/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageKlinik;
			$modal_body = $($modal_id).find('.modal-body');
			
			$.each(data, function (key, val) {
				if (key == 'rc_id') key = '_id'
				if (key == 'account_type') {
					$modal_body.find("[name='" + key + "'][value='"+val+"']").prop("checked",true);
					$modal_body.find("[name='" + key + "'][value!='"+val+"']").prop("checked",false);
				} else {
					$modal_body.find("[name='" + key + "']").val(val);
				}
			})
			$(".accessibility").prop("checked",false);
			if (data.enabled_menus) {
					var enabled_menus=data.enabled_menus.split(',')
					$.each(enabled_menus,function(i,dt){
							$("[name^='enabled_menus'][value='"+dt+"']").prop("checked",true);
					})
			}
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("submit","form[name='form-manage-klinik']",function(e){
		e.preventDefault();
		$("#save-klinik").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('admin/klinik/save__','POST',data)
		.done(function(res){
			$($modalManageKlinik).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-klinik").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-klinik").removeAttr('disabled');
			})
			.always(function () {
			$("#save-klinik").removeAttr('disabled');
		})
		
	})
	.on("click",".set_status_clinic",function(){
		var $that=$(this);
		var status=0;
		var endisUser=" disabled this clinic";
		var id=$that.data('id');
		if($that.is(":checked")){
			   status=1;  
			   var endisUser=" enabled this clinic";
		}
		bootbox.confirm({
			title: $lang.bootbox_title_confirmation,
			message: $lang.bootbox_message_confirm_+endisUser+"?",
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
					http_request('admin/klinik/enable-disable-clinic/','POST',{id: id,status: status})
					.done(function(res){
						Msg.success(res.message);
						$tableData.ajax.reload(null,false);
					})
					.fail(function(err){
						if($that.is(":checked")){
							$that.prop("checked",false);
						}else{
							$that.prop("checked",true);
						}
					})
				}else{
					if($that.is(":checked")){
						$that.prop("checked",false);
					}else{
						$that.prop("checked",true);
					}
				}
			}
		});
	})
