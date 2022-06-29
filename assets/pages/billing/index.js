$modalManagePembayaran	= "#modal-manage-pembayaran";
$modalManageDetailTindakan	= "#modal-manage-detail-tindakan";
$modalManageDetailResep	= "#modal-manage-detail-resep";
$modalManageDetailKamar = "#modal-manage-detail-kamar";
$tableData = $("#dataPasiendiperiksa").DataTable({
	processing: true,
	serverSide: true,
	ordering: true,
	pageLength: 50,
	ajax: {
			url: base_url('billing/load-dt'),
			type: 'POST',
			headers: {
					'x-user-agent': 'ctc-webapi',
			},
			data: function(d) {
				d.clinic_id = getSelectedClinic();
				d.category=$("[name='category']:checked").val()
				d.filter_pemeriksaan = $("[name='filter_pemeriksaan'] option:selected").val();
				d.filter_tgl_option = $("[name='filter_tgl_option'] option:selected").val();
				d.start_date = $("[name='start_date']").val();
				d.end_date = $("[name='end_date']").val();
				if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
			}
	},
	language: DataTableLanguage(),
	scrollY: '60vh',
	// scrollCollapse: true,
	scrollX: true,
	order: [[0,'desc']],
	columnDefs: [
			{ targets: [0], width: '25px',className: 'text-center' },
			{ targets: [1], width: '40px',className: 'text-center' },
			{ targets: [3], width: '60px',className: 'text-left' },
			{ targets: [4], width: '90px',className: 'text-left' },
			{ targets: [6,7,8,9], width: '90px',className: 'text-right'},
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
		getActiveLang('Billing');
	})

	.on("click",".link-bayar",function(){
		http_request('billing/search--','GET',{id: $(this).data('id')})
		.done(function(result){
			var detail_tin='<tr style="background-color:lightgrey;">'+
			'<th>Dokter</th>'+
			'<th>Waktu Tindakan</th>'+
			'<th>Tindakan</th>'+
			'<th>Biaya Tindakan</th>'+
		'</tr>'
		var detail_res='<tr style="background-color:lightgrey;">'+
			'<th>Nama Obat</th>'+
			'<th>Supplier</th>'+
			'<th>Aturan Pakai</th>'+
			'<th>Cara Pakai</th>'+
			'<th>Jumlah</th>'+
			'<th>Total</th>'+
			
		'</tr>'
		var detail_kam='<tr style="background-color:lightgrey;">'+
			'<th>Kamar/Nomor</th>'+
			'<th>Tgl Masuk</th>'+
			'<th>Tgl Keluar</th>'+
			'<th>Biaya /hari</th>'+
			
		'</tr>'
			var data=result.data;
			var data_tot_biaya_tindakan=result.data_tot_biaya_tindakan;
			var data_tot_biaya_resep=result.data_tot_biaya_resep;
			var data_tot_dibayar=result.data_tot_dibayar;
			var total_biaya_kamar=result.total_biaya_kamar;
			var total_biaya=result.total_biaya;
			var biaya=result.biaya;
			var sisa=result.sisa;

			$modal_id=$modalManagePembayaran;
			$modal_body = $($modal_id).find('.modal-body');
			
			$modal_body.find("input:text").val("");

			$.each(data, function (key, val) {
				$modal_body.find("[name='"+ key +"']").val(val);
			})
			$.each(data, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(data_tot_biaya_tindakan, function (key, val) {
				$($modal_body).find('.'+key).text(val)
				$($modal_body).find('[name='+key+']').val(val)
			})
			$.each(data_tot_biaya_resep, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(total_biaya_kamar, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(data_tot_dibayar, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			
			$.each(total_biaya, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(biaya, function (key, val) {
				$modal_body.find("[name='"+ key +"']").val(val);
			})
			$.each(sisa, function (key, val) {
				$($modal_body).find('.'+key).text(val)
			})
			$.each(result.detail_tindakan, function (index, item) {
				detail_tin += '<tr style="vertical-align: top;">' +
				'<td>'+item.namaDokter+'</td>'+
				'<td>'+item.create_at+'</td>'+
				'<td>'+item.nama_layanan_poli+'</td>'+
				'<td align="right">'+item.harga_layanan_poli+'</td>'+
				'</tr > ';
			})
			$.each(result.detail_resep, function (index, item) {
				detail_res += '<tr style="vertical-align: top;">' +
				'<td>'+item.nama+'</td>'+
				'<td>'+item.namaSupplier+'</td>'+
				'<td>'+item.nama_aturan_pakai+'</td>'+
				'<td>'+item.nama_cara_pakai+'</td>'+
				'<td>'+item.qty+'</td>'+
				'<td align="right">'+item.total+'</td>'+
				'</tr > ';
			})
			$.each(result.detail_kamar, function (index, item) {
				detail_kam += '<tr style="vertical-align: top;">' +
				'<td>'+item.namaRuangan+'/'+item.nomor+'</td>'+
				'<td>'+item.checkin_at+'</td>'+
				'<td>'+item.checkout_at+'</td>'+
				'<td align="right">'+item.tarif+'</td>'+
				'</tr > ';
			})
			$("#tableDetailTindakan").html(detail_tin)
			
			$("#tableDetailResep").html(detail_res)
			
			$("#tableDetailKamar").html(detail_kam)
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})

	.on("submit", "form[name='form-manage-pembayaran']", function (e) {
		e.preventDefault();
		$("#save-pembayaran").attr('disabled', 'disabled');
		$form = $(this).closest('form');
		var data = $form.serialize();
		http_request('billing/save__', 'POST', data)
			.done(function (res) {
				$($modalManagePembayaran).modal('hide');
				$($modalManageDetailResep).modal('hide');
				$($modalManageDetailKamar).modal('hide');
				$($modalManageDetailTindakan).modal('hide');
				$tableData.ajax.reload();
				Msg.success(res.message);
				// window.location.replace(base_url('rawat-jalan/antrian_pemeriksaan'));
				$("#save-pembayaran").removeAttr('disabled');
			})
			.fail(function () {
				$("#save-pembayaran").removeAttr('disabled');
			})
			.always(function () {
				$("#save-pembayaran").removeAttr('disabled');
			})

	})
	.on("click", "#detail_tindakan", function () {
		$modal_id=$modalManageDetailTindakan;
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
	.on("click", "#detail_resep", function () {
		$modal_id=$modalManageDetailResep;
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
	.on("click", "#detail_kamar", function () {
		$modal_id=$modalManageDetailKamar;
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

	
const hitungTotal = (total, dibayar) => {
	hasil=dibayar-total;
	return (hasil); 
}
const	$total	= $('.sisa'),
		$kembalian	= $('#kembalian'),
		$dibayar	= $('#biaya');

$dibayar.add($total).on('input', () => {
	if ( $dibayar.val().length) {      
	total = hitungTotal($total.text(),$dibayar.val());
	}
	$kembalian.val( total );
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
$("[name='filter_pemeriksaan']").select2({
	minimumResultsForSearch: -1,
}).on("change", function () {
	$tableData.ajax.reload();
});
$("[name='filter_tgl_option']").select2({
	minimumResultsForSearch: -1,
}).on("change", function () {
	$tableData.ajax.reload();
});
$('.close_modal').on("click", function () {
	$($modalManageDetailResep).modal('hide');
	$($modalManageDetailKamar).modal('hide');
	$($modalManageDetailTindakan).modal('hide');
})
