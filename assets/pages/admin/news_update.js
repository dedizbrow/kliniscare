$modalManagenews		= "#modal-manage-news";
$tableData				= $("#dataNews").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('admin/news_update/load-dt'),
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
		{ targets: [3], width: '50px',className: 'text-center' },
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
		getActiveLang('admin/news_update');
	})

	.on("click", ".add-news", function () {
		$modal_id=$modalManagenews;
		$modal_body=$($modal_id).find('.modal-body');
		
		$modal_body.find("input:text").val("");
		$modal_body.find("textarea").val('');

		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
    
	.on("click",".link-edit-news",function(){
		http_request('admin/news_update/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManagenews;
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
	.on("submit","form[name='form-manage-news']",function(e){
		e.preventDefault();
		$("#save-obat").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('admin/news_update/save__','POST',data)
		.done(function(res){
			$($modalManagenews).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-news").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-news").removeAttr('disabled');
			})
			.always(function () {
			$("#save-news").removeAttr('disabled');
		})
		
	})
	.on("click",".link-delete-news",function(){
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
							http_request('admin/news_update/delete__/'+id,'DELETE',{})
							.done(function(res){
									Msg.success(res.message);
									$tableData.ajax.reload(null,false);
							})
						}
			}
		});
		})
