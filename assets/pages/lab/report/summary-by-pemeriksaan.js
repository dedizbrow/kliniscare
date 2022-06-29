$(document)
	.ready(function () {
		updateListHasil()
	})
	.on("change", "#select_month,#select_year,#source_clinic", function () {
		var year = $("#select_year option:selected").val();
		month = $("#select_month option:selected").val();
		location.href = base_url($module_path_lab+'report/?clinic_id='+getSelectedClinic()+'&periode=' + year + '-' + month);
		updateListHasil()
	})
	.on("change", "[name='jenis'],[name='provider']", function () {
		updateListHasil()
	})

$('[name*="_date"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'dd-mm-yy',
	maxDate: 'now',
	container: '#modal-manage-pasien'
})
function updateListHasil () {
	http_request($module_path_lab+'/report/list-opsi-hasil', 'GET', { jenis: $('[name="jenis"]').val() })
			.done(function (result) {
				var sel = $("[name='hasil_pemeriksaan']").html('<option value="">Semua</option>')
				var current_selected = $("#selected_hasil_pemeriksaan").val()
				$.each(result, function (i, hsl) {
					var selected = (hsl.hasil == current_selected) ? 'selected' : '';
					sel.append($('<option value="'+hsl.hasil+'" '+selected+'>'+hsl.hasil+'</option>'))
				})
			})
}
