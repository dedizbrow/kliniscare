$modalManagePeriksa    = "#modal-manage-periksa";
$modalManagePilihObat	= "#modal-manage-pilih-obat";
$modalManageCheckinRuangan = "#modal-manage-checkin-ruangan";
$modalManageCheckoutRuangan = "#modal-manage-checkout-ruangan";
$modalManagePilihRuangan = "#modal-manage-pilih-ruangan";
$modalManageResep		= "#modal-manage-resep-dokter";
$tableData				= $("#pemeriksaanIGD").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('rawat-inap/pemeriksaan/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
			data: function (d) {
				d.clinic_id=getSelectedClinic()
				d.filter_status_cout = $("[name='filter_status_cout'] option:selected").val();
				d.filter_status_rawat = $("[name='filter_status_rawat'] option:selected").val();
				d.start_date = $("[name='start_date']").val();
				d.end_date = $("[name='end_date']").val();
				if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
      }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    order: [[0,'desc']],
    columnDefs: [
		{ targets: [0], width: '15px',className: 'text-center' },
		{ targets: [3], width: '125px' },
		// { targets: [2], width: '100px' },
		{ targets: [5], width: '40px'},
		{ targets: [11], width: '70px' },
    ],
    rowCallback: function(row, data, iDisplayIndex){	
		var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
    },
})
$tableDataRuangan = $("#dataRuangan").DataTable({

	serverSide: true,
	ordering: true,
	pageLength: "50",
	ajax: {
		url: base_url('rawat-inap/pemeriksaan/load-dt-ruangan'),
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
	scrollCollapse: true,
	scrollX: false,
	order: [[1, 'desc']],
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
		if ($("#dataDaftarBarang table tbody tr td[data-code='" + data[0] + "']").length > 0) {
			$('td:eq(8)', row).html("Sudah ditambahkan")
			$(row).addClass('text-danger')
		}
		if(data[6]==0) $(row).addClass('text-danger')
  },
})
$(document)
	.ready(function () {
		getActiveLang('rawat-inap/pemeriksaan');
	})
	.on("change", "#source_clinic", function () {
		$tableData.ajax.reload()
		$tableDataObat.ajax.reload()
		$tableDataRuangan.ajax.reload()
	})
	.on("click",".link-periksa",function(){
		http_request('rawat-inap/pemeriksaan/search_pendaftaran','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManagePeriksa;
			$modal_body = $($modal_id).find('.modal-body');

			$modal_body.find("input:text").val("");
			$modal_body.find("select").val('').trigger('change');
			$modal_body.find("textarea").val('');

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
	.on("submit","form[name='form-manage-periksa']",function(e){
		e.preventDefault();
		$("#save-pemeriksaan").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-inap/pemeriksaan/save_pemeriksaan','POST',data)
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
	.on("click", "#cariruangan", function () {
		$modal_id = $modalManagePilihRuangan;
		$modal_body = $($modal_id).find('.modal-body');

		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
		$($modalManageCheckinRuangan).modal('hide');
	})
	
	.on("click",".link-checkin-ruangan",function(){
		http_request('rawat-inap/pemeriksaan/search_','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageCheckinRuangan;
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
	.on("click",".link-tambah-ruangan",function(){
		http_request('rawat-inap/pemeriksaan/tambah_ruangan','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageCheckinRuangan;
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
	.on("submit","form[name='form-manage-checkin-ruangan']",function(e){
		e.preventDefault();
		$("#save-checkin").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-inap/pemeriksaan/save_checkin','POST',data)
		.done(function(res){
			$($modalManageCheckinRuangan).modal('hide');
			$tableData.ajax.reload();
			$tableDataRuangan.ajax.reload();
			Msg.success(res.message);
			$("#save-checkin").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-checkin").removeAttr('disabled');
			})
			.always(function () {
			$("#save-checkin").removeAttr('disabled');
		})
		
	})
	.on("click",".link-checkout-ruangan",function(){
		http_request('rawat-inap/pemeriksaan/search_checkout','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageCheckoutRuangan;
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
	.on("submit","form[name='form-manage-checkout-ruangan']",function(e){
		e.preventDefault();
		$("#save-checkout").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-inap/pemeriksaan/save_checkout','POST',data)
		.done(function(res){
			$($modalManageCheckoutRuangan).modal('hide');
			$tableData.ajax.reload();
			$tableDataRuangan.ajax.reload();
			Msg.success(res.message);
			$("#save-checkout").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-checkout").removeAttr('disabled');
			})
			.always(function () {
			$("#save-checkout").removeAttr('disabled');
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
	.on("click", ".cariobat", function () {
		$modal_id=$modalManagePilihObat;
		$modal_body=$($modal_id).find('.modal-body');

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

	.on("submit","form[name='form-manage-resep']",function(t){
		t.preventDefault();
		$("#save-resep").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-inap/pemeriksaan/save_resep','POST',data)
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
					id_obat: $('input[name="id_obat"]').val()
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
	ajax: {
		url: base_url('rawat-jalan/antrian_pemeriksaan/select2_tindakan'),
		type: "GET",
		dataType: 'json',
		delay: 500,
		data: function (params) {
			return {
				search: params.term,
			};
		},
		processResults: function (data) {
			return {
				results: data
			};
		},
	}
});


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
		url:"pemeriksaan/load_temp?clinic_id="+getSelectedClinic(),
		data:"",
		success:function(html){
			$("#dataDaftarBarang").html(html);
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
		http_request('rawat-inap/pemeriksaan/hapus-temp', 'POST', { id: id })
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

	http_request('rawat-inap/pemeriksaan/insert-temp', 'POST', { id_obat: id_obat,kode: kode,obat: obat, qty: qty, total: total,cara_pakai: cara_pakai, aturan_pakai:aturan_pakai,isi:isi,harga:harga,satuan:satuan })
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
	$("#poli").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih poliklinik",
		tags: true,
		ajax: {
			url: base_url('pendaftaran/select2_poli'),
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
	const	$sistole	= $('input[name="sistole"]'),
			$diastole	= $('input[name="diastole"]'), 
			$tensi	= $('input[name="tensi"]');
	
	$diastole.add($sistole).on('input', () => {
		if ( $sistole.val().length || $diastole.val().length) {      
			$tensi.val( $sistole.val()+'/'+$diastole.val() );
		}
		$tensi.val( total );
	});
	$('[name*="_date"]').datepicker({
		changeMonth: true,
		clearBtn: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dateFormat: 'yy-mm-dd',
		yearRange: 'c-1:c',
		maxDate: 'now',
	}).on("change", function () {
		$tableData.ajax.reload();
	})
	$("[name='filter_status_cout']").select2({
		minimumResultsForSearch: -1,
	}).on("change", function () {
		$tableData.ajax.reload();
	});
	$("[name='filter_status_rawat']").select2({
		minimumResultsForSearch: -1,
	}).on("change", function () {
		$tableData.ajax.reload();
	});
