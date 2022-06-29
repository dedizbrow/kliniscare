$modalManagePenjualan	= "#modal-manage-penjualan";
$modalManagePilihObat	= "#modal-manage-pilih-obat";
$tableDataPenjualan		= $("#dataPenjualan").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('farmasi/penjualan/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id=getSelectedClinic();
			d.filter_jenis_bayar = $("[name='filter_jenis_bayar'] option:selected").val();
			d.start_date = $("[name='start_date']").val();
			d.end_date = $("[name='end_date']").val();
        }

    },
	
    language: DataTableLanguage(),
    responsive: false,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: true,
    order: [[1,'desc']],
    columnDefs: [
			{ targets: [0], width: '20px',className: 'text-center' },
			{ targets: [2,3,4,5], width: '80px',className: 'text-center' },
			{ targets: [7], width: '90px',className: 'text-right'},
			{ targets: [8], width: '80px',className: 'text-center'},
			{ targets: [9], width: '40px',className: 'text-center'},
    ],
    rowCallback: function(row, data, iDisplayIndex){	
		var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
    },
})
$tableData = $("#dataObat").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('farmasi/penjualan/load-dt-obat'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },

    },
	
    language: DataTableLanguage(),
    responsive: true,
    // scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    order: [[1,'desc']],
		columnDefs: [
			{ targets: [6], className: 'text-center' },
			{ targets: [8], width: '3px', className: 'text-center' },
			{ targets: [3,4,5], width: '90px',className: 'text-right'},
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
		getActiveLang('farmasi/penjualan');
	})

	.on("click", ".add-penjualan", function () {
		$modal_id=$modalManagePenjualan;
	   
		$modal_body=$($modal_id).find('.modal-body');
		$modal_body.find("input:text").not('[name="tanggal"]').val("");
		$modal_body.find("input[type=number]").val("");
		$modal_body.find("[name='qty']").val("");
		$modal_body.find("[name='total']").val("");
		$modal_body.find("[name='diskonsub']").val(0);
		$modal_body.find("#dokter").val('').trigger('change');
		$modal_body.find("#autocode").trigger('click');
		$modal_body.find("#autocode").prop('disabled', false);

		$modal_body.find(".show-on-update").addClass('hide');
		
		load_data_temp();
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
		$("#tunai_kredit").val('tunai').trigger('change');
	})
	
	.on("click",".link-delete-penjualan",function(){
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
							http_request('farmasi/penjualan/delete__/'+id,'DELETE',{})
							.done(function(res){
									Msg.success(res.message);
									$tableDataPenjualan.ajax.reload(null,false);
							})
						}
			}
		});
		})
    //ketika tambah obat ke daftar pembelian
	.on("click",".link-tambah-daftar-obat",function(){
		http_request('farmasi/penjualan/search_obat','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManagePenjualan;
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
		$('input[name="diskon"]').val('0').trigger('change');
	})
	
	.on("click", ".cariobat", function () {
		// $($modalManagePenjualan).modal('hide');
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
			$modal_id=$modalManagePenjualan;
			$modal_body = $($modal_id).find('.modal-body');
			
			$.each(data, function (key, val) {
					$modal_body.find("[name='"+ key +"']").val(val);
			})
		})
	});
	
	$("#idDokter").select2({
		minimumResultsForSearch: -1,
		placeholder: "Pilih Dokter",
		tags: true,
		ajax: { 
		url: base_url('farmasi/penjualan/select2_dokter'),
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
	})
	$('#tunai_kredit').select2({
		minimumResultsForSearch: -1,
	})
	

	$('#tunai_kredit').on('change', function() {
		$("#kredit_h").css('display', (this.value == 'kredit')? 'block' : 'none');
		$("#jatuh_t").css('display', (this.value == 'kredit')? 'block' : 'none');


		$("#ifkreditthen_kembali_gone").css('display', (this.value == 'tunai')? 'block' : 'none');
		$("#ifkreditthen_bayar_gone").css('display', (this.value == 'tunai')? 'block' : 'none');
		
	});
		
	$('#autocode').on("click",function(){
		http_request('farmasi/penjualan/searchcode','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result;
				$( "#faktur" ).val(data);
		})
	});
	
	$('[name="tanggal"]').datepicker({
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dateFormat: 'yy-mm-dd',
		reverseYearRange: true,
		yearRange: 'c:c+80',
		container: '#modal-manage-penjualan',
		beforeShow: function(input, instance) { 
			$(input).datepicker('setDate', new Date());
		}
	})
	$('[name="jatuh_tempo"]').datepicker({
		changeMonth: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dateFormat: 'yy-mm-dd',
		reverseYearRange: true
	})
	
	const hitungTotal = (hargaJual, diskon, jumlah) => {
		hitungDiskon = (diskon/100);
		hasilDiskon = (hargaJual*hitungDiskon); 
		hasil=(hargaJual-hasilDiskon)*jumlah;
		return (hasil); // jual price
	}
	const	$jual	= $('input[name="harga"]'),
			$diskon	= $('input[name="diskon"]'), 
			$total	= $('input[name="total"]'), 
			$jumlah	= $('input[name="qty"]');
	
	$jumlah.add($jual).add($diskon).on('input', () => {
		let total = $jual.val();
		if ( $jumlah.val().length || $diskon.val().length) {      
		total = hitungTotal($jual.val(), $diskon.val(), $jumlah.val());
		}
		$total.val( total );
	});
	//simpan transaksi
function selesai(){
	var tanggal		= $('[name="tanggal"]').val();
	var faktur		= $('[name="faktur"]').val();
	var tunai_kredit= $('[name="tunai_kredit"]').val();
	var subtotal	= $('[name="subtotal"]').val();
	var grandotal	= $('[name="grandtotal"]').val();
	var bayar		= $('[name="bayar"]').val();
	var kredit_hari	= $('[name="kredit_hari"]').val();
	var jatuh_tempo	= $('[name="jatuh_tempo"]').val();
    if(tanggal==''||faktur==''||tunai_kredit==''||subtotal==''||grandotal==''){
        Msg.error('Lengkapi data');
        return false;
    }
    else{
		if(tunai_kredit=='tunai'){
			if (bayar == "" || bayar == null || bayar < grandotal || grandotal == 0) {
				Msg.error("Masukan total bayar yang sesuai"); return false;
			}
		}
		if(tunai_kredit=='kredit'){
			
			if (kredit_hari == "" || kredit_hari == null || jatuh_tempo == "" || jatuh_tempo == null) {
				Msg.error("Lengkapi data"); return false;
			}
		}
		var data = $("form[name='form-manage-penjualan']").serialize()
			http_request('farmasi/penjualan/simpan', 'POST', data)
				.done(function (result) {
					load_data_temp();
					$($modalManagePenjualan).modal('hide');
					$tableDataPenjualan.ajax.reload(null,false);
					Msg.success(result.message)
					$tableData.ajax.reload();
				})
    }
    
}

function load_data_temp(){
	$.ajax({
		type:"GET",
		url:base_url("farmasi/penjualan/load_temp?clinic_id="+getSelectedClinic()),
		data: {},
		success:function(html){
			$("#dataDaftarBarang").html(html);
		
			var TotalValue = 0;
		 
			$("tr #totalitas").each(function(index,value){
				currentRow = parseFloat($(this).text());
				TotalValue += currentRow
			});
		 	
			
			const	$subtotal	= $('input[name="subtotal"]'),
					$diskonsub	= $('input[name="diskonsub"]'), 
					$grandtotal	= $('input[name="grandtotal"]'), 
					$kembali	= $('input[name="kembali"]'), 
					$bayar		= $('input[name="bayar"]');

											
			const hitungGrandTotal = (subtotal, diskonsub) => {
					var hitungDiskon = diskonsub/100;
					var hasilDiskon=subtotal*hitungDiskon;
					var hasil=subtotal-hasilDiskon;
					return hasil;
				}
			const hitungKembalian = (grandotal, bayar) => {
					var hitung = bayar-grandotal;
					return hitung;
				}
				//auto set subtotal.val(TotalValue)
			set = $("tr #totalitas").length;
				if(typeof set !== null) {
					$subtotal.val(TotalValue);
				}

			let total = $subtotal.val(TotalValue);  
			total = hitungGrandTotal($subtotal.val(), $diskonsub.val());
			$grandtotal.val( total );
			$diskonsub.on('input', () => {
					let total = $subtotal.val(TotalValue);   
					total = hitungGrandTotal($subtotal.val(), $diskonsub.val());
					$grandtotal.val( total );
				});
			$bayar.add($grandtotal).on('input', () => {
					let diskons = 0;
					if ( $bayar.val().length || $grandtotal.val().length) {      
					totalkembali = hitungKembalian($grandtotal.val(), $bayar.val());
					}
					$kembali.val( totalkembali );
					$diskonsub.val( diskons);
				});

		}
	})  
	}
function hapus (id) {
	http_request('farmasi/penjualan/hapus-temp', 'POST', { id: id })
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
	var dis		= $('[name="diskon"]').val();
	var isi		= $('[name="isi"]').val();
	var harga	= $('[name="harga"]').val();
	var total	= $('[name="total"]').val();
	var satuan	= $('#satuanobat').val();
	if ($("#satuanobat").val() == "" || $("#satuanobat").val() == null) {
		Msg.error("Satuan pembelian belum dipilih"); return false;
	}
	if (qty == "" || parseInt(qty) < 1) {
		Msg.error("Qty wajib diisi"); return false;
	}
	http_request('farmasi/penjualan/insert-temp', 'POST', { id_obat: id_obat,kode: kode,obat: obat, qty: qty, dis: dis, total: total,isi:isi,harga:harga,satuan:satuan })
		.done(function (result) {
			load_data_temp();
			$tableData.ajax.reload(null,false);
			$modal_body.find("input:text.clear").val(""); //default val kosong
			$modal_body.find('[name="qty"]').val("");
			$modal_body.find('[name="harga"]').val("");
			$modal_body.find('[name="total"]').val("");
		})	
}
$("[name='kredit_hari']").on('input', function(){
	var days = +$(this).val();
	var actualDate = new Date();
	  actualDate.setDate(actualDate.getDate()+days)
	$('[name="jatuh_tempo"]').datepicker("setDate",actualDate);
});
$("[name='filter_jenis_bayar']").select2({
	minimumResultsForSearch: -1,
}).on("change", function () {
	$tableDataPenjualan.ajax.reload();
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
	$tableDataPenjualan.ajax.reload();
})
