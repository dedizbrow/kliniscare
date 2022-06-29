$manageKunjunganIGD = "#manage-kunjunganIGD";
$modalManageDataPasien = "#modal-manage-data-pasien";
$modalManageInstansiPerujuk = "#modal-manage-instansi-rujukan";
$modalManageTenagaPerujuk = "#modal-manage-tenaga-perujuk";

$tableDataPasien = $("#dataPasien").DataTable({

	serverSide: true,
	ordering: true,
	pageLength: "50",
	ajax: {
		url: base_url('kunjunganIGD/load-dt-pasien'),
		type: 'POST',
		headers: {
			'x-user-agent': 'ctc-webapi',
		},
		data: function (d) {
			d.clinic_id = getSelectedClinic();
    }
	},

	language: DataTableLanguage(),
	responsive: true,
	scrollCollapse: true,
	scrollX: false,
	order: [[1, 'desc']],
})

$(document)
	.ready(function () {
		getActiveLang('KunjunganIGD');
		$("#autocode").trigger("click")
	})
.on("change", "#source_clinic", function () {
		$("#autocode").trigger("click")
		$tableDataPasien.ajax.reload()
	})
	.on("click", "#caripasien", function () {
		$modal_id = $modalManageDataPasien;
		$modal_body = $($modal_id).find('.modal-body');

		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
	//ketika tambah pasien
	.on("click", ".link-tambah-daftar-pasien", function () {
		http_request('kunjunganIGD/search_pasien', 'GET', { id: $(this).data('id') })
			.done(function (result) {
				var data = result.data;
				$div_id = $manageKunjunganIGD;
				$div_body = $($div_id).find('.modal-body');
				
				$('#identitas').html('<option value="' + data.identitas + '">' + data.identitas + '</option>').trigger('change');
				$('#gol_darah').html('<option value="' + data.gol_darah + '">' + data.gol_darah + '</option>').trigger('change');
				$('#jenis_kelamin').html('<option value="' + data.jenis_kelamin + '">' + data.jenis_kelamin + '</option>').trigger('change');
				$('#agama').html('<option value="' + data.agama + '">' + data.agama + '</option>').trigger('change');
				$('#asuransi_utama').html('<option value="' + data.asuransi_utama + '">' + data.asuransi_utama + '</option>').trigger('change');
				$('#status_nikah').html('<option value="' + data.status_nikah + '">' + data.status_nikah + '</option>').trigger('change');
				
				$('#provinsi').html('<option value="'+data.provinsi+'">'+data.provinsi+'</option>').trigger('change');
				$('#kabupaten').html('<option value="'+data.kabupaten+'">'+data.kabupaten+'</option>').trigger('change');
				$('#kecamatan').html('<option value="'+data.kecamatan+'">'+data.kecamatan+'</option>').trigger('change');
				$.each(data, function (key, val) {
					$div_body.find("[name='" + key + "']").val(val);
				})
			})
	})
	.on("click", ".cari-pasien", function () {
		$modal_id = $modalManageDataPasien;
		$modal_body = $($modal_id).find('.modal-body');

		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})

	.on("submit", "form[name='form-manage-kunjunganIGD']", function (e) {
		e.preventDefault();
		$("#save-kunjunganigd").attr('disabled', 'disabled');
		$form = $(this).closest('form');
		var data = $form.serialize();
		http_request('kunjunganIGD/save__', 'POST', data)
			.done(function (res) {
				Msg.success(res.message);
				setTimeout(function () { // add timeout before redirect to show message in a second 
					window.location.replace(base_url('kunjunganIGD'));
					// this code below useless because already redirected
					$("#save-kunjunganigd").removeAttr('disabled');
					$div_id = $manageKunjunganIGD;
					$div_body = $($div_id).find('.modal-body');
					$div_body.find("input:text").val("");
				},1000)
			})
			.fail(function (error) {
				console.log(error)
				$("#save-kunjunganigd").removeAttr('disabled');
			})
			.always(function () {
				$("#save-kunjunganigd").removeAttr('disabled');
			})

	})
	$('#autocode').on("click", function () {
		http_request('pendaftaran/searchcode', 'GET', { id: $(this).data('id') })
			.done(function (result) {
				var data = result;
				$("#nomor_rm").val(data);
			})
	});

	$("#asuransi_utama").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih asuransi",
		tags: true,
		ajax: {
			url: base_url('pendaftaran/select2_asuransi'),
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
	});
$("#perujuk").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih perujuk",
	tags: true,
	ajax: {
		url: base_url('kunjunganIGD/select2_perujuk'),
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
});

$("#dpjp").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih Dokter Jaga",
	tags: true,
	ajax: {
		url: base_url('kunjunganIGD/select2_dpjp'),
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
});

$("#select_diagnosa").select2({
	// minimumResultsForSearch: -1,
	placeholder: "Pilih ",
	tags: true,
	ajax: {
		url: base_url('kunjunganIGD/select2_diagnosa'),
		type: "GET",
		dataType: 'json',
		delay: 500,
		data: function (params) {
			return {
				search: params.term,
				clinic_id: getSelectedClinic()
			};
		},
		processResults: function (data) {
			return {
				results: data
			};
		},
	}
});

$("#agama").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih agama",
	tags: true,
	ajax: {
		url: base_url('pendaftaran/select2_agama'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term
			};
		},
		processResults: function (search) {
			return {
				results: search
			};
		},
		cache: true
	}
});
$("#provinsi").select2({
	placeholder: "Pilih provinsi",
	tags: true,
	ajax: {
		url: base_url('pendaftaran/select2_provinsi'),
		type: "GET",
		dataType: 'json',
		delay: 500,
		data: function (params) {
			return {
				search: params.term
			};
		},
		processResults: function (data) {
			return {
				results: data
			};
		},
	}
});

$("#kabupaten").select2({
	placeholder: "Pilih Kabupaten",
	tags: true,
	ajax: {
		url: base_url('pendaftaran/select2_kabupaten'),
		type: "GET",
		dataType: 'json',
		delay: 500,
		data: function (params) {
			return {
				search: params.term,
				id_provinsi: $("#provinsi").val()
			};
		},
		processResults: function (data) {
			return {
				results: data
			};
		},
	}
});

$("#kecamatan").select2({
	// minimumResultsForSearch: -1,
	placeholder: "Pilih Kecamatan",
	tags: true,
	ajax: {
		url: base_url('pendaftaran/select2_kecamatan'),
		type: "GET",
		dataType: 'json',
		delay: 500,
		data: function (params) {
			return {
				search: params.term,
				id_kabupaten: $("#kabupaten").val()
			};
		},
		processResults: function (data) {
			return {
				results: data
			};
		},
	}
});
$("#status_nikah").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih status pernikahan",
	tags: true,
	ajax: {
		url: base_url('pendaftaran/select2_status_nikah'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term
			};
		},
		processResults: function (search) {
			return {
				results: search
			};
		},
		cache: true
	}
});
$("#gol_darah").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih Gol. darah",
	tags: true,
	ajax: {
		url: base_url('pendaftaran/select2_gol_darah'),
		type: "GET",
		dataType: 'json',
		delay: 250,
		data: function (params) {
			return {
				key: params.term
			};
		},
		processResults: function (search) {
			return {
				results: search
			};
		},
		cache: true
	}
});
$('#jenis_kelamin').select2({
	minimumResultsForSearch: -1,
})

$('#gol_darah_pjw').select2({
	minimumResultsForSearch: -1,
})
$('#jenis_kelamin_pjw').select2({
	minimumResultsForSearch: -1,
})
$('#identitas').select2({
	minimumResultsForSearch: -1,
})
$('#identitas_pjw').select2({
	minimumResultsForSearch: -1,
})
$('#kondisi').select2({
	minimumResultsForSearch: -1,
})


$('#penanggung_jawab').change(function () {
	if (!this.checked) {//ketika tidak
		$('[name="nama_lengkap_pjw"]').val(null);
		$('[name="tempat_lahir_pjw"]').val(null);
		$('[name="tgl_lahir_pjw"]').val(null);
		$('[name="no_identitas_pjw"]').val(null);
		$('[name="no_hp_pjw"]').val(null);
		$('[name="no_telp_pjw"]').val(null);
		$('[name="alamat_pjw"]').val(null);
	} else {//ketika di klik
		const $nama = $('input[name="nama_lengkap"]').val(),
			$tempat_lahir = $('input[name="tempat_lahir"]').val(),
			$tgl_lahir = $('input[name="tgl_lahir"]').val(),
			$no_identitas = $('input[name="no_identitas"]').val(),
			$no_hp = $('input[name="no_hp"]').val(),
			$no_telp = $('input[name="no_telp"]').val(),
			$alamat = $('#alamat').val();
			$jk = $('#jenis_kelamin').val();
			$card_id = $('#identitas').val();
		$('[name="nama_lengkap_pjw"]').val($nama);
		$('[name="tempat_lahir_pjw"]').val($tempat_lahir);
		$('[name="tgl_lahir_pjw"]').val($tgl_lahir);
		$('[name="no_identitas_pjw"]').val($no_identitas);
		$('[name="no_hp_pjw"]').val($no_hp);
		$('[name="no_telp_pjw"]').val($no_telp);
		$('[name="alamat_pjw"]').val($alamat);
		$('#jenis_kelamin_pjw').val($jk).trigger('change');
		$('#gol_darah_pjw').val($gol_darah).trigger('change');
		$('#identitas_pjw').val($card_id).trigger('change');
	}
});


$('[name="tgl_lahir"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c'
}).on("change", function () {
	var age = calculate_age($(this).val())
	$("#umur").val(age)
})
$('[name="tgl_lahir_pjw"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c'
})

$('[name="tanggal"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true
})
