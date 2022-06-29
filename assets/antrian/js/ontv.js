$sc_id='cdisp-tv-antrian'
$(document)
	.ready(function () {
		var cid = getUrlParameter('cid')
		if(cid!=undefined) localStorage.setItem($sc_id,cid)
		$disp_clinic = localStorage.getItem($sc_id)	
		if ($disp_clinic === null || $disp_clinic == '') {
			var redirect = base_url('antrian/tv')
			location.href=base_url('admin/auth?md=tv&redirect='+redirect)
		}
	})
	.on("mouseover", ".page-header", function () {
		$("#clearLoggedClinic").removeClass('d-none')
	})
	.on("mouseout", ".page-header", function () {
		$("#clearLoggedClinic").addClass('d-none')
	})
	.on("click","#clearLoggedClinic", function () {
		localStorage.removeItem($sc_id)
		setTimeout(function () {
			location.href = base_url('admin/auth?mf=true&md=tv&redirect=' + base_url('antrian'))
		},200)
	})

$server_socket_address = $("[name='server_socket_antrian']").val()
console.log($server_socket_address);
var SocketAntrian = new WebSocket("ws://" + $server_socket_address);
SocketAntrian.onerror = function (e) {
	if(e.message==undefined)
		$(".conn-status").text('Tidak terhubung ke server')
}
SocketAntrian.onopen = function (e) {
	$(".conn-status").text('')
	console.log("Connection to SistemAntrian TV established!");
	getClinicInfo(localStorage.getItem($sc_id))
};
if (SocketAntrian) {
	SocketAntrian.onmessage = async function (msg) {
		var data = JSON.parse(msg.data)
		// console.log(data)
		// console.log(localStorage.getItem($sc_id))
		if (data.nomor !== null && data.sc_id!=undefined && data.sc_id.replace(/=/g, '') == localStorage.getItem($sc_id.replace(/=/g, ''))) {
			panggilAntrian(data.nomor,data.poli)
			$("#PANGGIL_ANTRIAN").text(data.nomor)
			$("#PANGGIL_KONTER").text(data.poli)
		}
	}
}
function getClinicInfo (clin_id) {
	$.get(base_url('common/get-clinic-info-/'+clin_id), [], function (result) {
		$("#clinic-name").text(result.clinic_name)
		if(result.logo!="")
			$("#img-logo-clinic").html('<img src="'+base_url(result.logo)+'" width="90px">')
	},'json')
}
function time() {
  var x = new Date()
	var dmy = x.getDate() + 1 + "-" + x.getMonth() + "-" + x.getFullYear(); 
	var hr = x.getHours()
	if(hr<10) hr='0'+hr
	var minute = x.getMinutes()
	if(minute<10) minute='0'+minute
	var ss = x.getSeconds()
	if (ss < 10) ss = '0' + ss
	var dt = hr+ ":" +  minute + ":" +  ss;
	$(".current_datetime").html(dt);
}

setInterval(time, 1000);
