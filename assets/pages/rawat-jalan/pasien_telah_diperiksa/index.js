$modalHistoryRM			= "#modal-history-rm";
$modalManagePilihObat	= "#modal-manage-pilih-obat";
$modalManageResep		= "#modal-manage-resep-dokter";
$modalManageAturanPakai		= "#modal-manage-aturan-pakai";
$modalManageCaraPakai		= "#modal-manage-cara-pakai";
$modalManagePeriksa			= "#modal-manage-periksa";
$tableData				= $("#dataTelahperiksa").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('rawat-jalan/pasien_telah_diperiksa/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
			data: function (d) {
					d.clinic_id=getSelectedClinic();
        }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    order: [[0,'desc']],
    columnDefs: [
		{ targets: [0], width: '35px',className: 'text-center' },
		{ targets: [1], width: '135px',className: 'text-center' },
    ],
    rowCallback: function(row, data, iDisplayIndex){	
		var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
    },
})

$tableDataObat = $("#dataObat").DataTable({
	serverSide: true,
	ordering: true,
	pageLength: "50",
	ajax: {
		url: base_url('rawat-jalan/pasien_telah_diperiksa/load-dt-obat'),
		type: 'POST',
		headers: {
			'x-user-agent': 'ctc-webapi',
		},
		data: function (d) {
			d.clinic_id=getSelectedClinic()
		}
	},

	language: DataTableLanguage(),
	responsive: true,
	// scrollY: '50vh',
	scrollCollapse: true,
	scrollX: false,
	order: [[1, 'desc']],
	columnDefs: [
	],
	rowCallback: function (row, data, iDisplayIndex) {
		if ($("#dataDaftarObat table tbody tr td[data-code='" + data[0] + "']").length > 0) {
			$('td:eq(8)', row).html("Sudah ditambahkan")
			$(row).addClass('text-danger')
		}
		if(data[6]==0) $(row).addClass('text-danger')
  },
})
$(document)
	.ready(function () {
		getActiveLang('rawat-jalan/pasien-telah-diperiksa');
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
	.on("click",".link-resep",function(){
		http_request('rawat-jalan/pasien_telah_diperiksa/search_','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageResep;
			$modal_body = $($modal_id).find('.modal-body');
			
			$.each(data, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(data, function (key, val) {
				$modal_body.find("[name='"+ key +"']").val(val);
		})
			load_data_temp();
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("click",".link-edit-pemeriksaan",function(){
		http_request('rawat-jalan/pasien_telah_diperiksa/search_pemeriksaan', 'GET', { id: $(this).data('id') })
			.done(function (result) {
				var data = result.data;
				$modal_id = $modalManagePeriksa;
				$modal_body = $($modal_id).find('.modal-body');

				// $('#select_dokter').html('<option value="'+data.dokter_diagnosa+'">'+data.namaDokter+'</option>').trigger('change');
				// $('#select_dokter_tindakan').html('<option value="'+data.dokter_tindakan+'">'+data.namaDokter+'</option>').trigger('change');
				$('#kesadaran').val(data.kesadaran).trigger('change');
				$.each(data, function (key, val) {
					$modal_body.find("[name='" + key + "']").val(val);
				})

				$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
				})
				if (result.diagnosa_pemeriksaan) {
					var selectdiagnosa = $("#select_diagnosa");
					$.each(result.diagnosa_pemeriksaan, function (i, d) {
						var option = new Option(d.text,d.id, true, true);
						selectdiagnosa.append(option).trigger('change');
						selectdiagnosa.trigger({
								type: 'select2:select',
								params: {
										data: option
								}
						});
					})
					
				}
				if (result.tindakan_pemeriksaan) {
					var selecttindakan = $("#select_tindakan");
					$.each(result.tindakan_pemeriksaan, function (i, d) {
						var option = new Option(d.text,d.id, true, true);
						selecttindakan.append(option).trigger('change');
						selecttindakan.trigger({
								type: 'select2:select',
								params: {
										data: option
								}
						});
					})
					
				}
		})
	})
	.on("submit","form[name='form-manage-periksa']",function(e){
		e.preventDefault();
		$("#save-pemeriksaan").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-jalan/pasien_telah_diperiksa/save__','POST',data)
		.done(function(res){
			$($modalManagePeriksa).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-pemeriksaan").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-pemeriksaan").removeAttr('disabled');
			})
			.always(function () {
			$("#save-pemeriksaan").removeAttr('disabled');
		})
		
	})
	.on("click",".link-pindah",function(){
		hideMsg();
		var id=$(this).data('id');
		$that=$(this);
		bootbox.confirm({
			title: $lang.bootbox_title_confirmation,
			message: "Pasien dipindah ke Rawat Inap",
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
							http_request('rawat-jalan/pasien_telah_diperiksa/pindah_ranap/'+id,'GET',{})
							.done(function(res){
									Msg.success(res.message);
									$tableData.ajax.reload(null,false);
							})
						}
			}
		});
		})
	.on("click", ".cariobat", function () {
		// $($modalManagePembelian).modal('hide');
		$modal_id=$modalManagePilihObat;
		$modal_body=$($modal_id).find('.modal-body');

		// $modal_body.find("input:text").val("");
		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
	.on("click",".link-tambah-daftar-obat",function(){
		http_request('farmasi/penjualan/search_obat','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageResep;
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
	.on("click", ".add-aturan-pakai", function () {
		$($modalManageResep).modal('hide');
		$modal_id=$modalManageAturanPakai;
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

	.on("submit","form[name='form-manage-aturan-pakai']",function(t){
		t.preventDefault();
		$("#save-aturan-pakai").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-jalan/pasien_telah_diperiksa/save_aturan_pakai','POST',data)
		.done(function(res){
			$($modalManageAturanPakai).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-aturan-pakai").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-aturan-pakai").removeAttr('disabled');
			})
			.always(function () {
			$("#save-aturan-pakai").removeAttr('disabled');
			$($modalManageResep).modal('show');
		})
	})
	
	.on("click", ".add-cara-pakai", function () {
		$($modalManageResep).modal('hide');
		$modal_id=$modalManageCaraPakai;
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
	.on("submit","form[name='form-manage-cara-pakai']",function(t){
		t.preventDefault();
		$("#save-cara-pakai").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-jalan/pasien_telah_diperiksa/save_cara_pakai','POST',data)
		.done(function(res){
			$($modalManageCaraPakai).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-cara-pakai").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-cara-pakai").removeAttr('disabled');
			})
			.always(function () {
			$("#save-cara-pakai").removeAttr('disabled');
			$($modalManageResep).modal('show');
		})
	})

	
	.on("submit","form[name='form-manage-resep']",function(t){
		t.preventDefault();
		$("#save-resep").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-jalan/pasien_telah_diperiksa/save_resep','POST',data)
		.done(function(res){
			$($modalManageResep).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-resep").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-resep").removeAttr('disabled');
			})
			.always(function () {
			$("#save-resep").removeAttr('disabled');
		})
	})
	$("#satuanobat").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih satuan",
		ajax: {
			url: base_url('farmasi/penjualan/select2_satuan_obat'),
			headers: {
				'x-user-agent': 'ctc-webapi'
			},
			data: function (params) {
				return {
					search: params.term,
					id_obat: $('input[name="id_obat"]').val(),
					clinic_id: getSelectedClinic()
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
		}
	}).on('change', function () {
		var id = $("#satuanobat option:selected").val();
		if (id == undefined) return false;
		http_request('farmasi/penjualan/search_obat_detail','GET',{id: id})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageResep;
			$modal_body = $($modal_id).find('.modal-body');
			
			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
			})
		})
	});

	$("#aturan_pakai").select2({
		minimumResultsForSearch: -1,
		placeholder: "Aturan pakai",
		tags: true,
		ajax: { 
		url: base_url('rawat-jalan/pasien_telah_diperiksa/select2_aturan_pakai'),
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
$("#cara_pakai").select2({
	minimumResultsForSearch: -1,
	placeholder: "Cara pakai",
	tags: true,
	ajax: { 
	url: base_url('rawat-jalan/pasien_telah_diperiksa/select2_cara_pakai'),
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

$("#select_dokter").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih ",
	tags: true,
	ajax: {
		url: base_url('rawat-jalan/antrian_pemeriksaan/select2_dokter'),
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
$("#select_diagnosa").select2({
	// minimumResultsForSearch: -1,
	placeholder: "Pilih ",
	tags: true,
	ajax: {
		url: base_url('rawat-jalan/antrian_pemeriksaan/select2_diagnosa'),
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

$("#select_dokter_tindakan").select2({
	minimumResultsForSearch: -1,
	placeholder: "Pilih ",
	tags: true,
	ajax: {
		url: base_url('rawat-jalan/antrian_pemeriksaan/select2_dokter'),
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


$("#select_tindakan").select2({
	// minimumResultsForSearch: -1,
	placeholder: "Pilih ",
	tags: true,
	multiple:true,
	ajax: {
		url: base_url('rawat-jalan/antrian_pemeriksaan/select2_tindakan'),
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

$('#kesadaran').select2({
	minimumResultsForSearch: -1,
})

const hitungTotal = (hargaJual, jumlah) => {
	hasil=hargaJual*jumlah;
	return (hasil);
}
const	$jual	= $('input[name="harga"]'),
		$total	= $('input[name="total"]'), 
		$jumlah	= $('input[name="qty"]');

$jumlah.add($jual).on('input', () => {
	let total = $jual.val();
	if ( $jumlah.val().length ) {      
	total = hitungTotal($jual.val(), $jumlah.val());
	}
	$total.val( total );
});

function load_data_temp () {
	$.ajax({
		type:"GET",
		url:"pasien_telah_diperiksa/load_temp?clinic_id="+getSelectedClinic(),
		data:"",
		success:function(html){
			$("#dataDaftarObat").html(html);
			var TotalValue = 0;
		 	$("tr #totalitas").each(function(index,value){
				currentRow = parseFloat($(this).text());
				TotalValue += currentRow
			});
			
			const	$subtotal	= $('input[name="subtotal"]');

			set = $("tr #totalitas").length;
				if(typeof set !== null) {
					$subtotal.val(TotalValue);
				}
			
		}
	})  
	}

	function hapus (id) {
		http_request('rawat-jalan/pasien_telah_diperiksa/hapus-temp', 'POST', { id: id })
			.done(function (result) {
				$("#dataobat"+id).hide(200);
				load_data_temp();
				$tableData.ajax.reload();
			})
	}
function add_barang(){
	var id_obat	= $('[name="id_obat"]').val();
	var kode	= $('[name="kode"]').val();
	var obat	= $('[name="nama"]').val();
	var qty		= $('[name="qty"]').val();
	var isi		= $('[name="isi"]').val();
	var harga	= $('[name="harga"]').val();
	var total	= $('[name="total"]').val();
	var satuan	= $('#satuanobat').val();
	var cara_pakai = $('[name="cara_pakai"]').val();
	var aturan_pakai = $('[name="aturan_pakai"]').val();
	if ($("#satuanobat").val() == "" || $("#satuanobat").val() == null) {
		Msg.error("Satuan pembelian belum dipilih"); return false;
	}
	if (qty == "" || parseInt(qty) < 1) {
		Msg.error("Qty wajib diisi"); return false;
	}
	if (obat == "") {
		Msg.error("Nama obat wajib diisi"); return false;
	}
	if (aturan_pakai == "") {
		Msg.error("Aturan pakai belum dipilih"); return false;
	}
	if (cara_pakai == "") {
		Msg.error("Cara pakai belum dipilih"); return false;
	}

	http_request('rawat-jalan/pasien_telah_diperiksa/insert-temp', 'POST', { id_obat: id_obat,kode: kode,obat: obat, qty: qty, total: total,cara_pakai: cara_pakai, aturan_pakai:aturan_pakai,isi:isi,harga:harga,satuan:satuan })
		.done(function (result) {
			load_data_temp();
			$tableData.ajax.reload(null,false);
			$modal_body.find("input:text.clear").val(""); //default val kosong
			$modal_body.find('[name="qty"]').val("");
			$modal_body.find('[name="harga"]').val("");
			$modal_body.find('[name="total"]').val("");
			$modal_body.find("#aturan_pakai").val('').trigger('change');
			$modal_body.find("#cara_pakai").val('').trigger('change');
		})		
}
