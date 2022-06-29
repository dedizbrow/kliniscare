$tableData = $("#obatStokmin").DataTable({

    serverSide: true,
    ordering: true,
    pageLength: "5",
    ajax: {
        url: base_url('admin/home/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id=getSelectedClinic();
        }

    },
	
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '17vh',
	"bFilter": false,
	"bLengthChange": false,
    scrollX: false,
    order: [[1,'desc']],
    columnDefs: [
			{ targets: [2,3],className: 'text-center' },
    ],
    rowCallback: function(row, data, iDisplayIndex){
		$('td:eq(2)', row).addClass('text-danger')
		$('td:eq(3)', row).addClass('text-primary')
    },
})
$(document)
	.ready(function () {
		if($(".module-lab-enable").length>0)
		generateChartPemeriksaan();
	})
	.on("change", "#source_clinic", function () {
		var value = $(this).val()
		location.href=base_url('admin/home?clinic_id='+value)
	})

function generateChartPemeriksaan () {
	http_request($module_path_lab + 'summarylab/count-pemeriksaan', 'GET', {})
		.done(function (result) {
			console.log(result)
			var rand_id = Math.floor(Math.random() * 10000)
			$("#chartLabArea").html('');
			var total_jenis = result.length
			console.log(total_jenis)
			$.each(result, function (i, obj) {
				var elmid = 'chatLab_' + i + '_' + rand_id;
				if (total_jenis > 1) {
					$("#chartLabArea").append('<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12"><canvas id="' + elmid + '" width="500"></canvas></div>')
				} else {
					$("#chartLabArea").append('<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><canvas id="' + elmid+ '" width="500"></canvas></div>')
				}
				
				new Chart(document.getElementById(elmid), {
				  	type: 'pie',
						data: {
							labels: ["Selesai ("+obj.jumlah_selesai+")","Menunggu ("+obj.waiting+")","Cancel ("+obj.cancel+")"],
							datasets: [{
								label: "Proses",
								backgroundColor: ["#007bff", "#ffc107","#dc3545"],
								data: [obj.jumlah_selesai,obj.waiting,obj.cancel]
							}]
						},
					options: {
							legend: {
								position: 'left',
							},
							title: {
								display: true,
								text: obj.jenis
							}
						}
				});
				
			})
		})
	

}
