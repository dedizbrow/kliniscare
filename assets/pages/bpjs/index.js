$managePendaftaran			= "#manage-pendaftaran";
$modalManagePendaftaran		= "#modal-manage-pendaftaran";
$modalManageDataPasien		= "#modal-manage-data-pasien";

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
    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollCollapse: true,
    scrollX: false,
    order: [[1,'desc']],
})

$(document)
.ready(function () {
	getActiveLang('pendaftaran');
})

.on("submit","form[name='form-manage-pendaftaran']",function(e){
	e.preventDefault();
	$("#save-pendaftaran").attr('disabled', 'disabled');
	$form=$(this).closest('form');
	var data = $form.serialize();
	http_request('pendaftaran/save__','POST',data)
	.done(function(res){
		Msg.success(res.message);
		$("#save-pendaftaran").removeAttr('disabled');
	})
		.fail(function () {
			$("#save-pendaftaran").removeAttr('disabled');
		})
		.always(function () {
		$("#save-pendaftaran").removeAttr('disabled');
	})
	
})

.on("click", ".cari-pasien", function () {
	$modal_id=$modalManageDataPasien;
	$modal_body=$($modal_id).find('.modal-body');

	$modal_body.find(".show-on-update").addClass('hide');
	$($modal_id).modal({
		effect: 'effect-slide-in-right',
		backdrop: 'static',
		keyboard: false,
		show: true
	})
})
//ketika tambah pasien
.on("click",".link-tambah-daftar-pasien",function(){
	http_request('kunjunganIGD/search_pasien','GET',{id: $(this).data('id')})
	.done(function(result){
		var data=result.data;
		$div_id=$managePendaftaran;
		$div_body = $($div_id).find('.modal-body');
		
		$('#jenis_kelamin').html('<option value="'+data.jenis_kelamin+'">'+data.jenis_kelamin+'</option>').trigger('change');
		$('#status_nikah').html('<option value="'+data.status_nikah+'">'+data.status_nikah+'</option>').trigger('change');
		$('#agama').html('<option value="'+data.agama+'">'+data.agama+'</option>').trigger('change');
		$('#gol_darah').html('<option value="'+data.gol_darah+'">'+data.gol_darah+'</option>').trigger('change');
		$('#identitas').html('<option value="'+data.identitas+'">'+data.identitas+'</option>').trigger('change');
		$('#asuransi_utama').html('<option value="'+data.asuransi_utama+'">'+data.asuransi_utama+'</option>').trigger('change');

		$.each(data, function (key, val) {
				$div_body.find("[name='"+ key +"']").val(val);
		})
	})
})

//auto kode nomor_rekam medis
$('#autocode').on("click",function(){
	http_request('pendaftaran/searchcode','GET',{id: $(this).data('id')})
	.done(function(result){
		var data=result;
			$( "#nomor_rm" ).val(data);
	})
});


$("#perujuk").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih perujuk",
	tags: true,
	ajax: { 
	url: base_url('pendaftaran/select2_perujuk'),
	type: "post",
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


$("#agama").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih agama",
	tags: true,
	ajax: { 
		url: base_url('pendaftaran/select2_agama'),
		type: "post",
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

$("#asuransi_utama").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih asuransi",
	tags: true,
	ajax: { 
	url: base_url('pendaftaran/select2_asuransi'),
	type: "post",
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

$("#poli").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih poliklinik",
	tags: true,
	ajax: { 
	url: base_url('pendaftaran/select2_poli'),
	type: "post",
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
$('#dpjp').select2({
	minimumResultsForSearch: -1,
})
$('#status_nikah').select2({
	minimumResultsForSearch: -1,
})
$('#membership').select2({
	minimumResultsForSearch: -1,
})
$('#gol_darah').select2({
	minimumResultsForSearch: -1,
})
$('#gol_darah_pjw').select2({
	minimumResultsForSearch: -1,
})
$('#jenis_kelamin').select2({
	minimumResultsForSearch: -1,
})

$('#jenis_kelamin_pjw').select2({
	minimumResultsForSearch: -1,
})
$('#identitas').select2({
	minimumResultsForSearch: -1,
})

$('#jenis_kunjungan').select2({
	minimumResultsForSearch: -1,
})
$('#identitas_pjw').select2({
	minimumResultsForSearch: -1,
})


$('#penanggung_jawab').change(function () {
	if (!this.checked){//ketika tidak
		
	} else {//ketika di klik
		const	$nama	= $('input[name="nama_lengkap"]').val(),
		$tempat_lahir	= $('input[name="tempat_lahir"]').val(),
		$tgl_lahir	= $('input[name="tgl_lahir"]').val(),
		$no_identitas	= $('input[name="no_identitas"]').val(),
		$no_hp	= $('input[name="no_hp"]').val(),
		$no_telp	= $('input[name="no_telp"]').val(),
		$alamat	= $('input[name="alamat"]').val();
		$('[name="nama_lengkap_pjw"]').val($nama);
		$('[name="tempat_lahir_pjw"]').val($tempat_lahir);
		$('[name="tgl_lahir_pjw"]').val($tgl_lahir);
		$('[name="no_identitas_pjw"]').val($no_identitas);
		$('[name="no_hp_pjw"]').val($no_hp);
		$('[name="no_telp_pjw"]').val($no_telp);
		$('[name="alamat_pjw"]').val($alamat);
	}
});


$('[name="tgl_lahir"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c',
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