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
$("#search_pasien").select2({
		minimumInputLength: 0,
		allowClear: true,
		placeholder: 'click untuk mencari',
		ajax: {
				url: base_url($module_path_lab+'pasien/select2-'),
				headers: {
						'x-user-agent': 'ctc-webapi'
				},
				data: function(params) {
					return {
							clinic_id: getSelectedClinic(),
							search: params.term,
							provider: ($("#search_provider").length==0) ? 'none' : $("#search_provider option:selected").val() 
						}
				},
				processResults: function(data){
						return {results: data};
				}
		},
}).on('change', function () {
	resetTablePasien();
	var id = $("#search_pasien option:selected").val();
	if (id == undefined) return false;
	http_request($module_path_lab+'pasien/search__','GET',{id: id})
	.done(function(result){
		var data=result.data;
		$.each(data, function (k, v) {
			$("[name='" + k + "'].autofill").val(v);
			if (k == '_id') $("[name='_id_pasien']").val(v);
		})
		
		if (data.reg_pemeriksaan && data.reg_pemeriksaan != "") {
			$("#search_jenis_pemeriksaan").append('<option value="' + data.reg_pemeriksaan + '">' + data.jenis_pemeriksaan + '</option>');
		}
	})
})
$("#search_dokter").select2({
	minimumInputLength: 0,
	tags: true,
	allowClear: true,
		multiple: false,
		placeholder: 'click untuk mencari',
		ajax: {
				url: base_url('master-data/dokter/search-dokter-select2-'),
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
$("#search_sampling").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'click untuk mencari',
	ajax: {
			url: base_url($module_path_lab+'jenispemeriksaan/sampling-select2-'),
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
$list_selected_periksa = [];
$("#search_jenis_pemeriksaan").select2({
	minimumInputLength: 0,
	allowClear: true,
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
					current: $list_selected_periksa.join(",")
				}
			},
			processResults: function(data){
					return {results: data};
			}
	},
})
.on('change', function () {
	// $tableListPeriksa = $("#tableJenisPeriksa tbody");
	// var id = $("#search_jenis_pemeriksaan option:selected").val();
	// var text = $("#search_jenis_pemeriksaan option:selected").text();
	// if (id == undefined) return false;
	// $(this).val('').trigger('change');
	// if ($list_selected_periksa.indexOf(id) > -1) return false;
	// $list_selected_periksa.push(id);
	// var tr = "<tr>" +
	// 	"<td width='30%'><input type='checkbox' name='id_periksa' class='hide' checked='' value='"+id+"'> " + text + "<span class='pull-right delete-row pointer' data-index='"+($list_selected_periksa.length-1)+"'><i class='fa fa-times text-danger'></i></span></td>" +
	// 	"<td><input type='text' class='form-control input-sm' name='hasil' style='width: 150px'></td>" +
	// 	"<td><input type='text' class='form-control input-sm' name='nilai_rujukan' style='width: 120px'></td>" +
	// 	"<td><input type='text' class='form-control input-sm' name='metode' style='width: 200px'></td>" +
	// 	"</tr > ";
	// $tableListPeriksa.append($(tr));
})
$("#select_hasil").on("change", function () {
	var val = $(this).val();
	var id_jenis = $(this).data('id_jenis')
	http_request($module_path_lab+'jenispemeriksaan/search_list_opsi', 'GET', { hasil: val, id_jenis: id_jenis })
		.done(function (result) {
			var tbl = '<table width="100%" class="table dataTable table-bordered minimize-padding-all"><thead>' +
				'<tr>' +
				'<th>Pemeriksaan</th>' +
				'<th>Hasil</th>' +
				'<th>Nilai Rujukan</th>' +
				'<th>Metode</th>' +
				'</tr > </thead><tbody>';
			$.each(result.data, function (i, item) {
				//var readonly = (i==0) ? "readonly=''": "";
				var readonly = "";
				tbl += '<tr>' +
					'<td><input type="text" class="form-control input-sm" readonly="" name="nama_pemeriksaan[]" value="'+item.nama_pemeriksaan+'"></td>'+
					'<td><input type="text" class="form-control input-sm" '+readonly+' name="hasil_periksa[]" value="'+item.hasil+'"></td>'+
					'<td><input type="text" class="form-control input-sm" '+readonly+' name="nilai_rujukan[]" value="'+item.nilai_rujukan+'"></td>'+
					'<td><input type="text" class="form-control input-sm" readonly="" name="metode[]" value="'+item.metode+'"></td>'+
					'</tr > ';
			})
			tbl += '</tbody></table>';
			$(".current_hasil").html('');
			$(".current_hasil").html(tbl);
		})
})
// $("#search_dokter").select2({
// 	minimumInputLength: 0,
// 	allowClear: true,
// 	multiple: false,
// 	placeholder: 'click untuk mencari',
// 	ajax: {
// 			url: base_url('master-data/dokter/select2-'),
// 			headers: {
// 					'x-user-agent': 'ctc-webapi'
// 			},
// 			data: function(params) {
// 					return {
// 						search: params.term
// 					}
// 			},
// 			processResults: function(data){
// 					return {results: data};
// 			}
// 	},
// })
$("#search_provider").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'click untuk mencari',
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
$selected_notes = [];
$("#search_notes").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'click untuk mencari',
	ajax: {
		url: base_url($module_path_lab+'pemeriksaan/search-notes-select2-'),
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
.on('change', function () {
	var id = $("#search_notes option:selected").val();
	var text = $("#search_notes option:selected").text();
	if (id == undefined) return false;
	$(this).val('').trigger('change');
	if ($("[name^='id_notes'][value='" + id + "']").length > 0) return false;
	$("ul.note").append($("<li>" +
		"<input type='checkbox' name='id_notes[]' class='hide' value='" + id + "' checked> " + text +
		"<i class='fa fa-times text-warning link-delete pointer ' style='margin-left: 20px' title='Hapus'></i>" +
		"</li>"
	))
	$("#note_updates").attr('checked', true);
})
// $("[data-mask='time']").mask('00:00')
$(document)
	.ready(function () {
		getActiveLang($module_path_lab+'pemeriksaan');
	})
	.on("change", "#source_clinic", function () {
		$("#search_pasien").val(null).trigger("change")
		$("#search_dokter").val(null).trigger("change")
		$("#search_jenis_pemeriksaan").val(null).trigger("change")
		$("#search_sampling").val(null).trigger("change")
	})
	.on("click", ".delete-row", function () {
		var index = $(this).data('index');
		delete $list_selected_periksa[index];
		$(this).closest('tr').remove();
	})
	.on("click", "#submit_pemeriksaan", function () {
		var form = $(this).closest('form').serialize();
		bootbox.confirm({
        title: $lang.bootbox_title_confirmation,
        message: $lang.bootbox_message_confirm_save,
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
						http_request($module_path_lab+'pemeriksaan/save__', 'POST', form)
						.done(function (result) {
							Msg.success(result.message);
							setTimeout(function () {
								location.href=base_url('lab/pemeriksaan')
							},1000)
						})
					}
        }
    });
	})
	.on("click", "ul.note li .link-delete", function () {
		$that = $(this);
		bootbox.confirm({
        title: 'Konfirmasi',
        message: 'Yakin menghapus?',
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
						$that.closest('li').remove();
						$("#note_updates").attr('checked', true);
					}
        }
    });
	})
	.on("click", ".reset-hasil", function () {
		$that = $(this);
		bootbox.confirm({
        title: 'Konfirmasi',
        message: 'Item pemeriksaan dan hasil akan di reset. System akan memanggil ulang list item pemeriksaan terbaru.<br>Apakah anda yakin melanjutkan?',
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
						http_request('lab/pemeriksaan/reset-hasil-pemeriksaan', 'DELETE', { pid: $that.data('id') })
							.done(function (resu) {
								Msg.success(resu.message);
								setTimeout(function () {
									location.reload()
								},1000)
							})
					}
        }
    });
	})

function resetTablePasien () {
	$("input:text.autofill,textarea.autofill").val('');
}

$('[name="tgl_sampling"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	yearRange: 'c-1:c',
	maxDate: 'now',
	minDate: 'c-14'
});
$('.time-format').mask('H0:M0', {
	translation: {
			'H': {
					pattern: /[0-2]/
			},
			'M': {
					pattern: /[0-5]/
			},
			// 'S': {
			//     pattern: /[0-5]/
			// }
	}
});
