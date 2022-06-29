$modalManageObat		= "#modal-manage-obat";
$modalManageSatuanbeli	= "#modal-manage-satuan-beli";
$modalManageSatuanobat	= "#modal-manage-satuan-obat";
$modalManageKategori	= "#modal-manage-kategori";
$modalManageImport		= "#modal-manage-import";
$modalManageBarcode		= "#modal-manage-barcode";
$tableData				= $("#dataObat").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('farmasi/obat/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id=getSelectedClinic();
			if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
			d.filter_supplier = $("[name='filter_supplier'] option:selected").val();
			d.filter_kategori = $("[name='filter_kategori'] option:selected").val();
        }

    },
	
    language: DataTableLanguage(),
    // responsive: true,
    // scrollY: '50vh',
    // scrollCollapse: true,
    // scrollX: true,
    order: [[1,'desc']],
    columnDefs: [
			{ targets: [0], width: '20px',className: 'text-center' },
			{ targets: [1], width: '60px',className: 'text-center' },
			{ targets: [4], width: '70px',className: 'text-right'},
			{ targets: [6,7], width: '80px',className: 'text-right'},
			{ targets: [8], className: 'text-center'},
			{ targets: [9], width: '120px',className: 'text-left'},
			{ targets: [10,11,12], width: '70px',className: 'text-center'},
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
		getActiveLang('farmasi/obat');
	})

	.on("click", ".add-obat", function () {
		$modal_id=$modalManageObat;
		$modal_body=$($modal_id).find('.modal-body');
		
		$modal_body.find("input:text").val("");
		$modal_body.find("input[type=number]").val("");
		$modal_body.find("select").val('').trigger('change');
		$modal_body.find("input[name=stok]").prop('readonly', false);

		$modal_body.find("#autocode").trigger('click');
		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
    
	.on("click",".link-edit-obat",function(){
		http_request('farmasi/obat/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageObat;
			$modal_body = $($modal_id).find('.modal-body');
			$modal_body.find("select").val('').trigger('change');
			$modal_body.find("input[type=number]").val("");
			$('#satuanbeli').html('<option value="'+data.satuanbeli+'">'+data.namaSatuanbeli+'</option>').trigger('change');
			$('#satuanobat').html('<option value="'+data.satuanobat+'">'+data.namaSatuanobat+'</option>').trigger('change');
			$('#kategori').html('<option value="'+data.kategori+'">'+data.namaKategoriobat+'</option>').trigger('change');
			$('#supplier').html('<option value="'+data.supplier+'">'+data.namaSupplier+'</option>').trigger('change');
			
			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
			})

			$modal_body.find("input[name=stok]").prop('readonly', true);
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("submit","form[name='form-manage-obat']",function(e){
		e.preventDefault();
		$("#save-obat").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('farmasi/obat/save__','POST',data)
		.done(function(res){
			$($modalManageObat).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-obat").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-obat").removeAttr('disabled');
			})
			.always(function () {
			$("#save-obat").removeAttr('disabled');
		})
		
	})
	.on("click",".link-delete-obat",function(){
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
							http_request('farmasi/obat/delete__/'+id,'DELETE',{})
							.done(function(res){
									Msg.success(res.message);
									$tableData.ajax.reload(null,false);
							})
						}
			}
		});
		})


	.on("click",".link-barcode-obat",function(){
		http_request('farmasi/obat/barcode','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageBarcode;
			$modal_body = $($modal_id).find('.modal-body');
			$($modal_body).find('.printBarcode-cloned').remove();
			
			var code = data.kode
				$("#barcode").JsBarcode(code, {
				width: 1.6,
				displayValue: false,
				height: 50,
				marginTop: 1,
				marginBottom: 1,
			});

			$('#satuanbeli').html('<option value="'+data.satuanbeli+'">'+data.namaSatuanbeli+'</option>').trigger('change');
			$('b#harga').html('Rp'+data.hargaJual+',00');
			$('b#namaobat').html(data.nama);
			$('b#kode').html(data.kode);
			

			$('b#namaobat').each(function (f) {

				var newstr = $(this).text().substring(0,16);
				$(this).text(newstr);
		  
			});


			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})


	.on("click", ".add-satuan-beli", function () {
			$($modalManageObat).modal('hide');
			$modal_id=$modalManageSatuanbeli;
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

	.on("submit","form[name='form-manage-satuan-beli']",function(t){
		t.preventDefault();
		$("#save-satuan-bali").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('farmasi/obat/save_satuan_beli','POST',data)
		.done(function(res){
			$($modalManageSatuanbeli).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-satuan-beli").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-satuan-beli").removeAttr('disabled');
			})
			.always(function () {
			$("#save-satuan-beli").removeAttr('disabled');
			$($modalManageObat).modal('show');
		})
	})
		
	
	.on("click", ".add-satuan-obat", function () {
			$($modalManageObat).modal('hide');
			$modal_id=$modalManageSatuanobat;
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

	.on("submit","form[name='form-manage-satuan-obat']",function(t){
		t.preventDefault();
		$("#save-satuan-obat").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('farmasi/obat/save_satuan_obat','POST',data)
		.done(function(res){
			$($modalManageSatuanobat).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-satuan-obat").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-satuan-obat").removeAttr('disabled');
			})
			.always(function () {
			$("#save-satuan-obat").removeAttr('disabled');
			$($modalManageObat).modal('show');
		})
	})
		
	
	
	.on("click", ".add-kategori", function () {
		$($modalManageObat).modal('hide');
		$modal_id=$modalManageKategori;
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

	.on("submit","form[name='form-manage-kategori']",function(t){
		t.preventDefault();
		$("#save-kategori").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('farmasi/obat/save_kategori','POST',data)
		.done(function(res){
			$($modalManageKategori).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-kategori").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-kategori").removeAttr('disabled');
			})
			.always(function () {
			$("#save-kategori").removeAttr('disabled');
			$($modalManageObat).modal('show');
		})
	})


	
		// control import
		.on("click", ".import__", function () {
			var modal_id=$modalManageImport;
			var modal_body=$(modal_id).find('.modal-body');
			
			modal_body.find("input:text").val("");
			$(".row-error .title").html('');
			$(".row-error .content").html('');
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
			// btn_control.find('i.fa').addClass('fa-spin');
			// btn_control.find('span').html('Uploading.. <i class="fa fa-spinner fa-spin"></i>');
			let file = document.getElementById("file").files[0];
			if (file.name == "") return false;
			$that.closest('div').find('.filename').text(file.name);
			
		})
		.on("submit", "form[name='form-manage-import']", function (e) {
			$that = $(this);
			var btn_control = $(this).find('.btn.choose_file__');
			btn_control.removeClass('btn-warning').addClass('btn-primary');
			e.preventDefault();
			$("#submit-import").prop('disabled', true);
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
						formData.append('clinic_id',getSelectedClinic())
						$.ajax({
							url: base_url('farmasi/obat/import_'),
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
								$($modalManageImport).modal('hide');
								
								$("#submit-import").prop('disabled', false);
								if (response.duplicate && response.duplicate.length>0) {
									$(".row-error .content").html('');
									$(".row-error .title").html('sudah terdaftar (Duplicate):');
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
									$("#submit-import").prop('disabled', false);
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
								$("#submit-import").prop('disabled', false);
								btn_control.removeClass('btn-primary').addClass('btn-warning');
								$("#file").val('');
								$(".filename").text('');
								// loading_hide();
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
	$("#satuanbeli").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih Satuan",
		tags: true,
		ajax: { 
		url: base_url('farmasi/obat/select2_satuan_beli'),
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
		
	$(".satuan").select2({
		minimumResultsForSearch: -1,
		placeholder: "Satuan",
		tags: true,
		ajax: { 
		url: base_url('farmasi/obat/select2_satuan_obat'),
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
		
	

	$(".kategori").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih Kategori",
		tags: true,
		ajax: { 
		url: base_url('farmasi/obat/select2_kategori'),
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
	}).on("change", function () {
		$tableData.ajax.reload();
	});
	
	$(".supplier").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih Supplier",
		tags: true,
		ajax: { 
		url: base_url('farmasi/obat/select2_supplier'),
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
}).on("change", function () {
	$tableData.ajax.reload();
});

//event change satuan
// $('select#satuan1').on('change', function() {
// 	var singleValues = $( "#satuan1" ).find(":selected").text();
// 	$( "#lb1" ).html( singleValues );
//   });
//   $('select#satuan2').on('change', function() {
// 	var singleValues = $( "#satuan2" ).find(":selected").text();
// 	$( "#lb2" ).html( singleValues );
//   });
//   $('select#satuan3').on('change', function() {
// 	var singleValues = $( "#satuan3" ).find(":selected").text();
// 	$( "#lb3" ).html( singleValues );
//   });
$('select#satuanbeli').on('change', function() {
	var singleValues = $( "#satuanbeli" ).find(":selected").text();
	$( ".label2" ).html( singleValues );
  });

//auto kode obat
$('#autocode').on("click",function(){
	http_request('farmasi/obat/searchcode','GET',{id: $(this).data('id')})
	.done(function(result){
		var data=result;
			$( "#kode" ).val(data);
	})
});

	$('[name="expired"]').datepicker({
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dateFormat: 'yy-mm-dd',
		reverseYearRange: true,
		yearRange: 'c:c+80',
		container: '#modal-manage-obat'
	})

const hitungJual = (hargaBeli, laba, isi) => {
	hargaBeli = parseFloat(hargaBeli);
	laba  = parseFloat(laba);
		
	hargaBeliisi = hargaBeli * isi;
	hasilLaba = (laba/100)*hargaBeliisi;
	hasilJual = hargaBeliisi + hasilLaba; 
	return (hasilJual); // jual price
}
const hitungBeli = (hargaBeli, isi) => {
	hasil = hargaBeli * isi;
	return (hasil); 
}

const hitungLaba = (hargaBeli, hargaJual, isi) => {
	hargaBeli = parseFloat(hargaBeli);
	hargaJual = parseFloat(hargaJual);
	hargaBeliisi = hargaBeli *isi;
	hasil = ((hargaJual - hargaBeliisi )/hargaBeliisi)*100;
	return (hasil); // laba percentage
}
const	$beli		= $('input[name="hargaBeli"]'),
		$laba1		= $('#laba1'), 
		$isi1		= $('#isi1'), 
		$jual1		= $('#harga1'),
		$skip		= $('input[name="_onedit_skip"]'),
		$belitotal1	= $('#hargabeli1');
	
$jual1.add($isi1).on('input', () => {  
	let laba = 0;      
	if ( $isi1.val().length ) { 
		if($jual1.val().length){
			laba = hitungLaba($beli.val(), $jual1.val(),$isi1.val());
		}
	beli = hitungBeli($beli.val(), $isi1.val());
	}
	
	$laba1.val( Math.round(laba) );
	$belitotal1.val( Math.round(beli) );

});
($laba1).on('input', () => {  
	let jual = 0; 
	if ( $laba1.val().length ) { 
			jual = hitungJual($beli.val(), $laba1.val(),$isi1.val()); 
	}
	$jual1.val( Math.round(jual) );
});
//edit obat when satuan jual nothing change
($isi1).on('input', () => {  
	if ( $isi1.val().length ) { 
		skip = 1; 
	}
	$skip.val( skip );
});
// ===========================================
const	$laba2		= $('#laba2'), 
		$isi2		= $('#isi2'), 
		$jual2		= $('#harga2'),
		$belitotal2	= $('#hargabeli2');
$jual2.add($isi2).on('input', () => {  
	let laba = 0;      
	if ( $isi2.val().length ) { 
		if($jual2.val().length){
			laba = hitungLaba($beli.val(), $jual2.val(),$isi2.val());
		}
	beli = hitungBeli($beli.val(), $isi2.val());
	}
	
	$laba2.val( Math.round(laba) );
	$belitotal2.val( Math.round(beli) );

});
($laba2).on('input', () => {  
	let jual = 0; 
	if ( $laba2.val().length ) { 
			jual = hitungJual($beli.val(), $laba2.val(),$isi2.val()); 
	}
	$jual2.val( Math.round(jual) );
});

// ===========================================
const	$laba3		= $('#laba3'), 
		$isi3		= $('#isi3'), 
		$jual3		= $('#harga3'),
		$belitotal3	= $('#hargabeli3');
	
$jual3.add($isi3).on('input', () => {  
	let laba = 0;      
	if ( $isi3.val().length ) { 
		if($jual3.val().length){
			laba = hitungLaba($beli.val(), $jual3.val(),$isi3.val());
		}
	beli = hitungBeli($beli.val(), $isi3.val());
	}
	
	$laba3.val( Math.round(laba) );
	$belitotal3.val( Math.round(beli) );

});
($laba3).on('input', () => {  
	let jual = 0; 
	if ( $laba3.val().length ) { 
			jual = hitungJual($beli.val(), $laba3.val(),$isi3.val()); 
	}
	$jual3.val( Math.round(jual) );
});
// ===========================================
$(function(){
	$("#tambah").on("click",function(){
		var $img = $(".printBarcode").clone();
		$("#modal-manage-barcode").find('.modal-body').append($img.removeClass('printBarcode').addClass('printBarcode-cloned'));
	});
	$('#barcode_download').on('click', function() {
		$("#modal-manage-barcode").find('.modal-body').print({
			globalStyles : true,
			iframe : false
		})
	})
})
$('#printobat').on('click', function() {
	$("#dataObat").print({
		globalStyles : true,
		iframe : false
	})
})

$('#refresh').click(function() {
    location.reload();
});
