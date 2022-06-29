$modalManageJasa = "#modal-manage-jasa-apoteker";
$modalManageImport = "#modal-manage-import";
$tableData = $("#dataJasa").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('master-data/jasa_apoteker/load-dt'),
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
    columnDefs: [{ 
		targets: [0], width: '35px',className: 'text-center' },
		{ targets: [5], width: '65px',className: 'text-center' },
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
		getActiveLang('master-data/jasa_apoteker');
	})
	.on("click", ".add-jasa-apoteker", function () {
		$modal_id=$modalManageJasa;
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
	.on("submit","form[name='form-manage-jasa-apoteker']",function(e){
		e.preventDefault();
		$("#save-jasa-apoteker").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('master-data/jasa_apoteker/save__','POST',data)
		.done(function(res){
			$($modalManageJasa).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-jasa-apoteker").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-jasa-apoteker").removeAttr('disabled');
			})
			.always(function () {
			$("#save-jasa-apoteker").removeAttr('disabled');
		})
	})
	.on("click",".link-delete-jasa-apoteker",function(){
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
					http_request('master-data/jasa_apoteker/delete__/'+id,'DELETE',{})
					.done(function(res){
							Msg.success(res.message);
							$tableData.ajax.reload(null,false);
					})
				}
			}
		});
	})

	.on("click",".link-edit-jasa-apoteker",function(){
		http_request('master-data/jasa_apoteker/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageJasa;
			$modal_body = $($modal_id).find('.modal-body');

			$('#status').html('<option value="'+data.status+'">'+data.status+'</option>').trigger('change');

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

	$("#status").select2({
		minimumResultsForSearch: -1,
	})