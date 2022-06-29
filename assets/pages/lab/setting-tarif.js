$modalManageTarif = "#modal-manage-tarif";
const groupColumn = 0;
$prev_row_id = "";
$visible_provider = false;
$tableData = $("#dataTarif").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
			url: base_url($module_path_lab+'setting-tarif/load-dt'),
			type: 'POST',
			headers: {
					'x-user-agent': 'ctc-webapi',
			},
			data: function (d) {
				d.clinic_id = getSelectedClinic();
				d.filter_provider = $("[name='filter_provider'] option:selected").val();
			}
    },
    language: DataTableLanguage(),
    // responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    // scrollX: true,
    order: [[2,'desc']],
		columnDefs: [
			//{ "visible": false, "targets": groupColumn },
			{ targets: [0], visible: $visible_provider},
			{ targets: [1], orderable: false },
			{ targets: [3], width: '100px',searchable: false,orderable: false },
			{ targets: [5,6], visible: false },
    ],
	rowCallback: function (row, data, iDisplayIndex) {
		if (data[5] == 1) {
			$('td', row).addClass('text-primary')
		} else {
			$('td', row).addClass('text-secondary tx-italic');
		}
		var eq_tarif = ($visible_provider) ? 3 : 2;
		$('td:eq(' + eq_tarif + ')', row).html('<span>Rp. </span><span class="pull-right">' + data[3] + '</span>');
		var saveid = data[6];
		if ($prev_row_id == saveid) {
			index=($visible_provider) ? 4 : 3
			$('td:eq('+index+')', row).html('');
		}
		$('td',row).closest('tr').attr('saveid',saveid)
		$prev_row_id=saveid	
	},
	drawCallback: function(setting) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;
        // api.column(groupColumn, { page: 'current' }).data().each(function(group, i) {
				// 	if (last !== group) {
				// 			$(rows).eq(i).before(
				// 					'<tr class="group"><td colspan="4" class="text-primary"><i class="fa "></i> Provider : <b>' + group + '</b></td></tr>'
				// 			);
				// 			last = group;
				// 	}
        // });

    },
})
$("[name='filter_provider']").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'Semua Provider',
	ajax: {
			url: base_url($module_path_lab+'provider/select2-/with-all'),
			headers: {
					'x-user-agent': 'ctc-webapi'
			},
			data: function(params) {
					return {
						search: params.term
					}
			},
			processResults: function(data){
					return {results: data};
			}
	},
})
.on("change", function () {
	$tableData.ajax.reload();
})

$("select[name='provider_id']").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'Pilih Provider',
	ajax: {
		url: base_url($module_path_lab+'provider/select2-'),
		headers: {
			'x-user-agent': 'ctc-webapi'
		},
		data: function(params) {
			return {
				search: params.term
			}
		},
		processResults: function(data){
			return {results: data};
		}
	},
})
$('[name="start_date"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-1:c+1',
	container: '#modal-manage-tarif'
})
$(document)
	.ready(function () {
		getActiveLang($module_path_lab+'setting-tarif');
		if(getUrlParameter('clinic_id')==null && getSelectedClinic()!='allclinic') location.href=base_url('lab/setting-tarif?clinic_id='+$("#source_clinic").val())
	})
	.on("change", "#source_clinic", function () {
		location.href = base_url('lab/setting-tarif?clinic_id=' + $(this).val());
	})
	.on("click", ".add-tarif", function () {
		$modal_id=$modalManageTarif;
		$modal_body=$($modal_id).find('.modal-body');
		// $modal_body.find('input[name="user_id"]').val('');		
		//$modal_body.find("input:text").val("");
		// $modal_body.find("[name*='password']").attr('required');
		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
	.on("click",".link-edit-tarif",function(){
		http_request($module_path_lab+'setting-tarif/search_saveid__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageTarif;
			$modal_body = $($modal_id).find('.modal-body');
			$.each(data, function (index, dt) {
				$modal_body.find("[name='tarif_["+dt.jenis_id+"]']").val(dt.nominal)
			})
			$modal_body.find("[name='provider_id']").html('').append('<option value="'+data[0].provider_id+'" selected="selected">'+data[0].provider+'</option>');
			$modal_body.find("[name='start_date']").val(data[0].start_date)
			$modal_body.find("[name='_id']").val(data[0]._id)
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("click",".link-delete-tarif",function(){
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
						http_request($module_path_lab+'setting-tarif/delete__/'+id,'DELETE',{})
						.done(function(res){
								Msg.success(res.message);
								$tableData.ajax.reload(null,false);
						})
					}
        }
    });
	})
	.on("submit","form[name='form-manage-tarif']",function(e){
		e.preventDefault();
		$("#save-tarif").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request($module_path_lab+'setting-tarif/save__','POST',data)
		.done(function(res){
			$($modalManageTarif).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-tarif").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-tarif").removeAttr('disabled');
			})
			.always(function () {
			$("#save-tarif").removeAttr('disabled');
		})
	})
	.on("mouseover", "tr td", function () {
		var rowid = $(this).closest('tr').attr('saveid')
		$("tr[saveid='"+rowid+"'] td").addClass('bg-yellow-light')
	})
	.on("mouseout", "tr td", function () {
		var rowid = $(this).closest('tr').attr('saveid')
		$("tr[saveid='"+rowid+"'] td").removeClass('bg-yellow-light')
	})
