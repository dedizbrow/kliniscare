$modalManagePeriksa = "#modal-manage-periksa";
$modalHistoryRM			= "#modal-history-rm";
$tableData				= $("#dataAntrian").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('rawat-jalan/antrian_pemeriksaan/load-dt'),
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
    order: [[0,'asc']],
    columnDefs: [
		{ targets: [0], width: '35px',className: 'text-center' },
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
		getActiveLang('rawat-jalan/antrian_pemeriksaan');
	})

	.on("click",".link-periksa",function(){
		http_request('rawat-jalan/antrian_pemeriksaan/search_pendaftaran','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManagePeriksa;
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
	
	.on("click",".link-lewati",function(){
		http_request('rawat-jalan/antrian_pemeriksaan/lewati','GET',{id: $(this).data('id')})
		.done(function(res){
			Msg.success(res.message);
			$tableData.ajax.reload();
		})
	})
	.on("click",".link-panggil-antrian",function(){
		var nomor = $(this).data("nomor"), poli = $(this).data("poli")
		var cid = (getSelectedClinic() == 'default') ? $("#ref_default_clinic").val() : getSelectedClinic();
		var csc_id = Base64.encode(cid);
		SocketAntrian.send(JSON.stringify({nomor: nomor, poli: poli,sc_id: csc_id}))
		// panggilAntrian(nomor)
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
	.on("submit","form[name='form-manage-periksa']",function(e){
		e.preventDefault();
		$("#save-pemeriksaan").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('rawat-jalan/antrian_pemeriksaan/save__','POST',data)
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
	// 	var nextform = 0;
    //  	$("#btn-tambah-form").click(function(){
    //     nextform++;
    //     $("#insert-form").append(
    //       "<div class='row' id='row"+nextform+"'>" +
	// 	  "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>"+
	// 	  "<div class='form-group'>"+
    //       "<select name='fk_dokter_tindakan' class='form-control input-sm'>"+
    //       "<option value='1'>Dokter</option></select></div></div>"+

	// 	  "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>"+
	// 	  "<div class='form-group'>"+
    //       "<input type='text' name='fk_tindakan' class='form-control input-sm'> </div></div>"+

	// 	  "<div class='col-lg-3 col-md-3 col-sm-6 col-xs-6'>"+
	// 	  "<div class='form-group'>"+
    //       "<input type='text' name='biaya' class='form-control input-sm' autocomplete='off'> </div></div>" + 

	// 	  "<div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>"+
	// 	  "<button type='button' id='"+nextform+"' class='btn_remove btn btn-xs btn-outline-danger' > - </button></div>"
    //     );
    //     $("#jumlah-form").val(nextform);
    //   });
	  
    //   $(document).on('click', '.btn_remove', function(){
    //     var button_id = $(this).attr("id");
    //     $('#row'+button_id+'').remove();
    //   });

	const	$sistole	= $('input[name="sistole"]'),
			$diastole	= $('input[name="diastole"]'), 
			$tensi	= $('input[name="tensi"]');
	
	$diastole.add($sistole).on('input', () => {
		if ( $sistole.val().length || $diastole.val().length) {      
			$tensi.val( $sistole.val()+'/'+$diastole.val() );
		}
		$tensi.val( total );
	});
