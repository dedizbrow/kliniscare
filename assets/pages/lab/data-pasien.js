$modalManagePasien = "#modal-manage-pasien";
$modalManageImport = "#modal-manage-import";
$tableData = $("#dataPasien").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url($module_path_lab+'pasien/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
			data: function (d) {
				d.clinic_id = getSelectedClinic();
				d.filter_provider = $("[name='filter_provider'] option:selected").val();
				if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
			}
    },
    language: DataTableLanguage(),
    // responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: true,
    order: [[1,'desc']],
    columnDefs: [
        { targets: [0], width: '35px',className: 'text-center', visible: false },
        { targets: [1], width: '70'},
        { targets: [2], width: '150px'},
        { targets: [3], width: '150px'},
        { targets: [5], width: '150px'},
        { targets: [6], width: '70px',className: 'text-center' },
        { targets: [10], width: '75'},
        { targets: [9], width: '80px',className: 'text-center' },
        // { targets: [4], width: '79px',className: 'text-center' },
        { targets: [-1], width: '50px',className: 'text-center',searchable: false,orderable: false },
    ],
    rowCallback: function(row, data, iDisplayIndex){
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        // var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);
        // var selected="", set_status="Inactive", btnclass="btn-danger";
        // if(parseInt(data[4])==1){
        //     selected="checked='checked'"; set_status="Active";
        //     btnclass="btn-success";
        // }else{
        //     $(row).addClass("text-danger");
        // }
        // $("td:eq(4)",row).html($('<label class="ckbox"><input type="checkbox" class="set_status_user" data-id="'+data[0]+'" name="is_enabled" value="1" '+selected+'> <span>'+set_status+'</span></label>'));
			if (data[12] == 1) {
				$('td',row).addClass('tx-primary');
			}
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
						clinic_id: getSelectedClinic(),
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
	placeholder: 'Default berdasarkan akun provider masing-masing',
	ajax: {
		url: base_url($module_path_lab+'provider/select2-'),
		headers: {
			'x-user-agent': 'ctc-webapi'
		},
		data: function(params) {
			return {
				clinic_id: getSelectedClinic(),
				search: params.term
			}
		},
		processResults: function(data){
			return {results: data};
		}
	},
})
$("#search_jenis_pemeriksaan").select2({
	minimumInputLength: 0,
	allowClear: false,
	multiple: false,
	placeholder: 'click untuk mencari',
	ajax: {
			url: base_url($module_path_lab+'jenispemeriksaan/select2-'),
			headers: {
					'x-user-agent': 'ctc-webapi'
			},
			data: function(params) {
				return {
						clinic_id: getSelectedClinic(),
						search: params.term,
						current: []
					}
			},
			processResults: function(data){
					return {results: data};
			}
	},
})
$("#perujuk").select2({
	minimumInputLength: 0,
	placeholder: "Pilih perujuk",
	// tags: true,
	ajax: {
		url: base_url('pendaftaran/select2-perujuk'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term,
				clinic_id: getSelectedClinic()
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
$("#asuransi").select2({
	minimumInputLength: 0,
	placeholder: "Pilih asuransi",
	// tags: true,
	ajax: {
		url: base_url('pendaftaran/select2-asuransi'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term,
				clinic_id: getSelectedClinic()
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
$(document)
	.ready(function () {
		getActiveLang($module_path_lab+'pasien');
		
	})
	.on("click", ".add-pasien", function () {
		$modal_id=$modalManagePasien;
		$modal_body=$($modal_id).find('.modal-body');
		// $modal_body.find('input[name="user_id"]').val('');
		$(".row-perujuk").removeClass('hide')
		$modal_body.find("input:text").val("");
		$modal_body.find("textarea[name='alamat']").val("");
		// $modal_body.find("[name*='password']").attr('required');
		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
	.on("click", ".link-edit-pasien", function () {
		$(".row-perujuk").addClass('hide')
		http_request($module_path_lab+'pasien/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManagePasien;
			$modal_body = $($modal_id).find('.modal-body');

			$.each(data, function (key, val) {
				if (key == 'jenis_kelamin') {
					$modal_body.find("[name='" + key + "']").removeAttr("checked");
					$modal_body.find("[name='" + key + "'][value='" + val + "']").attr("checked", "checked");
				} else{
					$modal_body.find("[name='"+key+"']").val(val);
				}
			})
			if (data.reg_pemeriksaan != "") {
				$("[name='current_jenis_pemeriksaan']").val(data.reg_pemeriksaan)
				$("#search_jenis_pemeriksaan").append('<option value="' + data.reg_pemeriksaan + '" selected="">' + data.jenis_pemeriksaan + '</option>');
			}
			if (result.periksa.length > 0) {
				var periksa = result.periksa[0];
				$("#search_jenis_pemeriksaan").append('<option value="' + periksa.id_jenis + '" selected="">' + periksa.jenis_pemeriksaan + '</option>');
				$("[name='tgl_sampling']").val(periksa.tgl_sampling);
			}
			$modal_body.find("[name='provider_id']").html('').append('<option value="'+data.provider_id+'" selected="selected">'+data.provider+'</option>');
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("submit","form[name='form-manage-pasien']",function(e){
		e.preventDefault();
		$("#save-pasien").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request($module_path_lab+'pasien/save__','POST',data)
		.done(function(res){
			$($modalManagePasien).modal('hide');
			$tableData.ajax.reload();
			if (res.action && res.action == "call-print") {
				var modalRegSuccessID = "#modal-reg-success";
				$(modalRegSuccessID).find('.modal-title').html(res.message);
				var _body = $(modalRegSuccessID).find('.modal-body');
				$.each(res.data, function (key, val) {
					$(_body).find("." + key).text(val)
				})
				$(modalRegSuccessID).modal({
						effect: 'effect-slide-in-right',
						backdrop: 'static',
						keyboard: false,
						show: true
				})
			} else {
				Msg.success(res.message);
			}
			$("#save-pasien").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-pasien").removeAttr('disabled');
			})
			.always(function () {
			$("#save-pasien").removeAttr('disabled');
		})
	})
	.on("click", ".btn-print", function () {
		var printarea = $(this).data("printarea");
		$(printarea).print({
				globalStyles: true,
				mediaPrint: true,
				stylesheet: null,
				noPrintSelector: ".no-print",
				iframe: true,
				append: null,
				prepend: null,
				manuallyCopyFormValues: true,
				deferred: $.Deferred(),
				timeout: 750,
				title: $(this).data("title-print"),
				doctype: '<!doctype html>'
		});
	})
	.on("click",".link-delete-pasien",function(){
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
						http_request($module_path_lab+'pasien/delete__/'+id,'DELETE',{})
						.done(function(res){
								Msg.success(res.message);
								$tableData.ajax.reload(null,false);
						})
					}
        }
    });
	})
	.on("click", ".accept-registrasi", function () {
		var id = $(this).data("id");
		bootbox.confirm({
        title: 'Konfirmasi Pendaftaran',
        message: 'Apakah anda yakin untuk konfirmasi pendaftaran?',
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
						http_request($module_path_lab+'pasien/confirm-register/'+id,'PUT',{})
						.done(function(res){
								Msg.success(res.message);
								$tableData.ajax.reload(null,false);
						})
					}
        }
    });
	})
	// control import
	.on("click", ".import__", function () {
		var modal_id=$modalManageImport;
		var modal_body=$(modal_id).find('.modal-body');
		// $modal_body.find('input[name="user_id"]').val('');
		
		modal_body.find("input:text").val("");
		modal_body.find("textarea[name='alamat']").val("");
		$(".row-error .title").html('');
		$(".row-error .content").html('');
		// $modal_body.find("[name*='password']").attr('required');
		modal_body.find(".show-on-update").addClass('hide');
		$(modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
	.on("click", ".choose_file__", function () {
		$(this).closest('div').find('input:file').trigger('click');
	})
	.on("change", "input:file[name='file']", function () {
		$that = $(this);
		var btn_control = $(this).closest('div').find('.btn');
		btn_control.removeClass('btn-warning').addClass('btn-primary');
		//btn_control.find('i.fa').addClass('fa-spin');
		//btn_control.find('span').html('Uploading.. <i class="fa fa-spinner fa-spin"></i>');
		let file = document.getElementById("file").files[0];
		if (file.name == "") return false;
		$that.closest('div').find('.filename').text(file.name);
		// process upload with create self form
		/*
		bootbox.confirm({
			title: $lang.bootbox_title_confirmation,
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
					var formData = new FormData($that.closest('form')[0]);
					$.ajax({
						url: base_url('suppliers/import'),
						type: 'POST',
						data: formData,
						async: false,
						cache: false,
						contentType: false,
						enctype: 'multipart/form-data',
						processData: false,
						success: function (response) {
							Msg.success(response.message);
							$tableData.ajax.reload();
						},
						error: function (err) {
							Msg.error(err.responseJSON.error);
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
		*/
	})
	.on("submit", "form[name='form-manage-import']", function (e) {
		$that = $(this);
		var btn_control = $(this).find('.btn.choose_file__');
		btn_control.removeClass('btn-warning').addClass('btn-primary');
		e.preventDefault();
		//$("#submit-import").attr('disabled', 'disabled');
		let file = document.getElementById("file").files[0];
		if (file === undefined) return Msg.error("Pastikan telah memilih file");
		$(".row-error .title").html('');
		$(".row-error .content").html('');
		bootbox.confirm({
			title: $lang.bootbox_title_confirmation,
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
				if (result) {
					var spanLoader = '<span class="spanloader text-primary" style="position: absolute; top: 45%;left: 45%;z-index: 1000;font-size: 14px"><i class="fa fa-spin fa-5x text-primary fa-spinner"></i><br>Processing.... </span>';
					$("#modal-manage-import .modal-body").append($(spanLoader));
					var formData = new FormData($that[0]);
					$.ajax({
						url: base_url($module_path_lab+'pasien/import'),
						type: 'POST',
						data: formData,
						async: false,
						cache: false,
						contentType: false,
						enctype: 'multipart/form-data',
						processData: false,
						success: function (response) {
							Msg.success(response.message);
							$tableData.ajax.reload();
							if (response.duplicate && response.duplicate.length>0) {
								$(".row-error .content").html('');
								$(".row-error .title").html('NIK sudah terdaftar (Duplicate):');
								var htm = '<ul class="striped-list ml-0 pl-3" > ';
								$.each(response.duplicate, function (i,item) {
									htm+='<li>'+item+'</li>'
								})
								htm += '</ >';
								$('.row-error .content').html(htm);
							} else {
								if (response.link) {
									location.href = response.link;
								}
							}
							
						},
						error: function (err) {
							var json = err.responseJSON;
							Msg.error(json.error);
							if (json.data && json.data.length>0) {
								$(".row-error .content").html('');
								$(".row-error .title").html('Error Data sheet tidak sesuai:');
								var htm = '<ul class="striped-list ml-0 pl-3" > ';
								$.each(json.data, function (i,item) {
									htm+='<li>'+item+'</li>'
								})
								htm += '</ >';
								$('.row-error .content').html(htm);
							}

						},
						complete: function (response) {
							btn_control.removeClass('btn-primary').addClass('btn-warning');
							$("#file").val('');
							$(".filename").text('');
							//loading_hide();
							$(".spanloader").remove();
						}
					})
				} else {
					alert("closed");
					btn_control.removeClass('btn-primary').addClass('btn-warning');
					$("#file").val('');
					$(".filename").text('');
				}
			}
		});
	})
$('[name="tgl_lahir"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c',
	maxDate: 'now',
	container: '#modal-manage-pasien'
})
$('[name="tgl_sampling"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c',
	maxDate: 'now',
	minDate: 'c-14',
	container: '#modal-manage-pasien'
});

