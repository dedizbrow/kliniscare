$modalManagePasien		= "#modal-manage-pasien";
$modalHistoryRM			= "#modal-history-rm";
$modalHistoryLab		= "#modal-history-lab";
$modalInfoPasien		= "#modal-informasi-pasien";
$modalManageImport 		= "#modal-manage-import";
$tableData				= $("#dataPasien").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('rawat-jalan/data-pasien/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
			data: function (d) {
				d.clinic_id = getSelectedClinic();
					if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
      }

    },
	
    language: DataTableLanguage(),
    // responsive: true,
    // scrollY: '50vh',
    // scrollCollapse: true,
    // scrollX: true,
    order: [[1,'desc']],
    columnDefs: [
		{ targets: [0], width: '35px',className: 'text-center' },
		{ targets: [3], width: '85px'},
		{ targets: [9], width: '60px'},
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
		getActiveLang('rawat-jalan/data-pasien');
	})

    
	.on("click", ".link-edit-pasien", function () {
		$($modalInfoPasien).modal('hide')
		http_request('rawat-jalan/data-pasien/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManagePasien;
			$modal_body = $($modal_id).find('.modal-body');
			
			// $('#jenis_kelamin').html('<option value="'+data.jenis_kelamin+'">'+data.jenis_kelamin+'</option>').trigger('change');
			$('#jenis_kelamin').val(data.jenis_kelamin).trigger('change');
			$('#status_nikah').html('<option value="' + data.status_nikah + '">' + data.nama_status_pernikahan + '</option>').trigger('change');
			$('#gol_darah').html('<option value="' + data.gol_darah + '">' + data.nama_gol_darah + '</option>').trigger('change');
			$('#identitas').val(data.identitas).trigger('change');
			
			$('#asuransi_utama').html('<option value="' + data.asuransi_utama + '">' + data.namaAsuransi + '</option>').trigger('change');
			$('#agama').html('<option value="' + data.agama + '">' + data.nama_agama + '</option>').trigger('change');
			$('#provinsi').html('<option value="'+data.provinsi+'">'+data.nama_provinsi+'</option>').trigger('change');
			$('#kabupaten').html('<option value="'+data.kabupaten+'">'+data.nama_kabupaten+'</option>').trigger('change');
			$('#kecamatan').html('<option value="'+data.kecamatan+'">'+data.nama_kecamatan+'</option>').trigger('change');

			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
			})
			
			// $("#autocode").prop('disabled', true);

			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("click",".link-info-pasien",function(){
		http_request('rawat-jalan/data-pasien/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalInfoPasien;
			$modal_body = $($modal_id).find('.modal-body');
			$.each(data, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: true,
					show: true
			})
		})
	})
	.on("click",".link-history",function(){
		http_request('rawat-jalan/data-pasien/search_rm','GET',{id: $(this).data('id')})
		.done(function (result) {
			
			var pem='<tr style="background-color:lightgrey;">'+
			'<th width="15%">Kunjungan</th>'+
			'<th width="50%">Pemeriksaan</th>'+
			'<th width="15%">Diagnosis</th>'+
			'<th width="20%">Tindakan</th>'+
		'</tr>'
			$.each(result.pemeriksaan, function (index, item) {
				if (index == 0) {
					$(".nama_pasien").text(item.nama_lengkap)
					$(".no_rm").text(item.nomor_rm)
				}
				var anamnesa = (item.anamnesa !== null && item.anamnesa != '') ? item.anamnesa : '-';
				var pemeriksaan_umum = (item.pemeriksaan_umum !== null && item.pemeriksaan_umum != '') ? item.pemeriksaan_umum : '-';
				var alergi = (item.alergi !== null && item.alergi != '') ? item.alergi : '-';
				var sistole = (item.sistole !== null && item.sistole != '') ? item.sistole : '-';
				var diastole = (item.diastole !== null && item.diastole != '') ? item.diastole : '-';
				var tensi = (item.tensi !== null && item.tensi != '') ? item.tensi : '-';
				var derajat_nadi = (item.derajat_nadi !== null && item.derajat_nadi != '') ? item.derajat_nadi : '-';
				var nafas = (item.nafas !== null && item.nafas != '') ? item.nafas : '-';
				var suhu_tubuh = (item.suhu_tubuh !== null && item.suhu_tubuh != '') ? item.suhu_tubuh : '-';
				var saturasi = (item.saturasi !== null && item.saturasi != '') ? item.saturasi : '-';
				var bb = (item.bb !== null && item.bb != '') ? item.bb : '-';
				var tb = (item.tb !== null && item.tb != '') ? item.tb : '-';
				var catatan_dokter = (item.catatan_dokter !== null && item.catatan_dokter != '') ? item.catatan_dokter : '-';
				var nyeri = (item.nyeri !== null && item.nyeri != '') ? item.nyeri : '-';
				var diagnosa = (item.diagnosa !== null && item.diagnosa != '') ? item.diagnosa : '';
				var tindakan = (item.tindakan !== null && item.tindakan != '') ? item.tindakan : '';
				pem += '<tr style="vertical-align: top;">' +
				'<td>'+(item.status_rawat == 1 || item.status_rawat == 0 ? "<div class='tooltip-inner' style='background-color: peru;'>Rawat Jalan</div>" : "<div class='tooltip-inner' style='background-color: cornflowerblue;'>Rawat Inap</div>")+item.tanggal_kunjungan+'<br>'+ '</td>'+
				'<td><table width="100%">'+
						// '<tr><td>Anamnesa </td><td> : &nbsp;</td><td>'+anamnesa+'</td></tr>'+
						// '<tr><td>Pemeriksaan umum</td><td> : </td><td>'+pemeriksaan_umum+'</td></tr>'+
						// '<tr><td>Alergi </td><td> : </td><td>'+alergi+'</td></tr>'+
						// '<tr><td>Sistole</td><td> : </td><td>'+sistole+' mm/Hg</td></tr>'+
						// '<tr><td>Diastole</td><td> : </td><td>'+diastole+' mm/Hg</td></tr>'+
						// '<tr><td>Tensi</td><td> : </td><td>'+tensi+' mm/Hg</td></tr>'+
						// '<tr><td>Derajat nadi</td><td> : </td><td>'+derajat_nadi+' ppm</td></tr>'+
						// '<tr><td>Nafas</td><td> : </td><td>'+nafas+' bpm</td></tr>'+
						// '<tr><td>Suhu tubuh</td><td> : </td><td>'+suhu_tubuh+' ⁰C</td></tr>'+
						// '<tr><td>Saturasi</td><td> : </td><td>'+saturasi+' mmHg</td></tr>'+
						// '<tr><td>Berat bada</td><td> : </td><td>'+bb+' Kg</td></tr>'+
						// '<tr><td>Tinggi badan</td><td> : </td><td>'+tb+' Cm</td></tr>'+
						// '<tr><td>Catatan dokter</td><td> : </td><td>'+catatan_dokter+'</td></tr>'+
						// '<tr><td>Nyeri</td><td> : </td><td>'+nyeri+'</td></tr>'+
						'<tr><td width="15%">Sistole</td><td width="5%"> : </td><td width="30%">'+sistole+' mm/Hg</td><td width="15%">Suhu tubuh</td><td width="5%"> : </td><td width="30%">'+suhu_tubuh+' ⁰C</td></tr>'+
						'<tr><td>Diastole</td><td> : </td><td>'+diastole+' mm/Hg</td><td>Saturasi</td><td> : </td><td>'+saturasi+' mmHg</td></tr>'+
						'<tr><td>Tensi</td><td> : </td><td>'+tensi+' mm/Hg</td><td>Nyeri</td><td> : </td><td>'+nyeri+'</td></tr>'+
						'<tr><td>Derajat nadi</td><td> : </td><td>'+derajat_nadi+' ppm</td><td>Berat bada</td><td> : </td><td>'+bb+' Kg</td></tr>'+
						'<tr><td>Nafas</td><td> : </td><td>'+nafas+' bpm</td><td>Tinggi badan</td><td> : </td><td>'+tb+' Cm</td></tr>'+
						'<tr style="vertical-align: top;"><td  width="25%">Anamnesa </td><td  width="5%"> : &nbsp;</td><td colspan="4"  width="70%">'+anamnesa+'</td></tr>'+
						'<tr style="vertical-align: top;"><td>Pemeriksaan umum</td><td> : </td><td colspan="4">'+pemeriksaan_umum+'</td></tr>'+
						'<tr style="vertical-align: top;"><td>Alergi </td><td> : </td><td colspan="4">'+alergi+'</td></tr>'+
						'<tr style="vertical-align: top;"><td>Catatan dokter</td><td> : </td><td colspan="4">'+catatan_dokter+'</td></tr>'+
				'</table></td>'+
				'<td>- '+diagnosa.split('|').join(",<br>- ")+'</td>'+
				'<td>- '+tindakan.split('|').join(",<br>- ")+'</td>'+
				'</tr > ';
			})
			
			// '' . ($dt->status_rawat == 2 ? "<div class='tooltip-inner' style='background-color: cornflowerblue;'>Rawat Inap</div>" : "<div class='tooltip-inner' style='background-color: peru;'>Rawat Jalan</div>") . ''



			$("#tableDetail").html(pem)
	
			$($modalHistoryRM).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
			})
		})
	})
	.on("click",".link-history-lab",function(){
		http_request('rawat-jalan/data-pasien/search_lab','GET',{id: $(this).data('id')})
		.done(function (result) {
			
			var pem='<tr style="background-color:lightgrey;">'+
			'<th width="15%">Kunjungan</th>'+
			'<th width="40%">Pemeriksaan</th>'+
			'<th width="30%">Sampling</th>'+
			'<th width="15%">Hasil</th>'+
		'</tr>'
			$.each(result.pemeriksaan, function (index, item) {
				if (index == 0) {
					$(".nama_pasien").text(item.nama_lengkap)
					$(".no_rm").text(item.nomor_rm)
				}
				pem += '<tr style="vertical-align: top;">' +
				'<td>'+item.tgl_periksa+'</td>'+
				'<td>'+item.jenis+'</td>'+
				'<td>'+item.nama_sampling+'</td>'+
				'<td>'+item.hasil+'</td>'+
				'</tr > ';
			})
			
			$("#tableDetailLab").html(pem)
	
			$($modalHistoryLab).modal({
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
		http_request('rawat-jalan/data_pasien/save__','POST',data)
		.done(function(res){
			$($modalManagePasien).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-pasien").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-pasien").removeAttr('disabled');
			})
			.always(function () {
			$("#save-pasien").removeAttr('disabled');
		})
		
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
							http_request('rawat-jalan/data-pasien/delete__/'+id,'DELETE',{})
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
	let file = document.getElementById("file").files[0];
	if (file.name == "") return false;
	$that.closest('div').find('.filename').text(file.name);
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
					url: base_url('rawat-jalan/data_pasien/import_'),
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
							$(".row-error .title").html('Pasien sudah terdaftar (Duplicate):');
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

$('#identitas').select2({
	minimumResultsForSearch: -1,
})

$('#jenis_kunjungan').select2({
	minimumResultsForSearch: -1,
})
$('[name="tgl_lahir"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'yy-mm-dd',
	reverseYearRange: true,
	yearRange: 'c-80:c',
}).on("change", function () {
	var age = calculate_age($(this).val())
	$("#umur").val(age)
})
