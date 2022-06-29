$(document)
	.on("change", "#select_month,#select_year", function () {
		var year = $("#select_year option:selected").val();
		month = $("#select_month option:selected").val();
		location.href = base_url($module_path_lab+'report/?clinic_id='+getSelectedClinic()+'&periode=' + year + '-' + month);
	})

$('[name*="_date"]').datepicker({
	changeMonth: true,
	changeYear: true,
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'dd-mm-yy',
	container: '#modal-manage-pasien'
})
