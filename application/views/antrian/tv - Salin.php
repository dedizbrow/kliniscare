<!doctype html>


<?php
/*
if(file_exists("install/index.php")){
	//perform redirect if installer files exist
	//this if{} block may be deleted once installed
	header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';

if(isset($user) && $user->isLoggedIn()){
}
*/

//include 'template/headerTV.php';
$this->load->view('template/headerTV');
	
?>


<audio id="suarabel" src="<?php echo config_item('rekaman'); ?>Airport_Bell.mp3"></audio>
<audio id="suarabelopen" src="<?php echo config_item('rekaman'); ?>opening.mp3"></audio>
<audio id="suarabelend" src="<?php echo config_item('rekaman'); ?>ending.mp3"></audio>
<audio id="suarabelnomorurut" src="<?php echo config_item('rekaman'); ?>noAntrian.mp3"></audio>
<audio id="suarabelsuarabelloket" src="<?php echo config_item('rekaman'); ?>KeCounter.mp3"></audio>
<audio id="belas" src="<?php echo config_item('rekaman'); ?>belas.mp3"></audio>
<audio id="sebelas" src="<?php echo config_item('rekaman'); ?>sebelas.mp3"></audio>
<audio id="puluh" src="<?php echo config_item('rekaman'); ?>puluh.mp3"></audio>
<audio id="sepuluh" src="<?php echo config_item('rekaman'); ?>sepuluh.mp3"></audio>
<audio id="ratus" src="<?php echo config_item('rekaman'); ?>ratus.mp3"></audio>
<audio id="seratus" src="<?php echo config_item('rekaman'); ?>seratus.mp3"></audio>
<audio id="suarabelloket1" src="<?php echo config_item('rekaman'); ?>1.mp3"></audio>
<audio id="suarabelloket2" src="<?php echo config_item('rekaman'); ?>2.mp3"></audio>
<audio id="suarabelloket3" src="<?php echo config_item('rekaman'); ?>3.mp3"></audio>
<audio id="suarabelloket4" src="<?php echo config_item('rekaman'); ?>4.mp3"></audio>
<audio id="1" src="<?php echo config_item('rekaman'); ?>Satu.mp3"></audio>
<audio id="2" src="<?php echo config_item('rekaman'); ?>Dua.mp3"></audio>
<audio id="3" src="<?php echo config_item('rekaman'); ?>Tiga.mp3"></audio>
<audio id="4" src="<?php echo config_item('rekaman'); ?>Empat.mp3"></audio>
<audio id="5" src="<?php echo config_item('rekaman'); ?>Lima.mp3"></audio>
<audio id="6" src="<?php echo config_item('rekaman'); ?>Enam.mp3"></audio>
<audio id="7" src="<?php echo config_item('rekaman'); ?>Tujuh.mp3"></audio>
<audio id="8" src="<?php echo config_item('rekaman'); ?>Delapan.mp3"></audio>
<audio id="9" src="<?php echo config_item('rekaman'); ?>Sembilan.mp3"></audio>

<audio id="A" src="<?php echo config_item('rekaman'); ?>a.mp3"></audio>
<audio id="B" src="<?php echo config_item('rekaman'); ?>b.mp3"></audio>
<audio id="C" src="<?php echo config_item('rekaman'); ?>c.mp3"></audio>
<audio id="D" src="<?php echo config_item('rekaman'); ?>d.mp3"></audio>

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<body class="h-100" style="background-color: #29434e;">
    <div class="container-fluid" style="background-color: #29434e;">
        <div class="row">
            <!-- Main Sidebar -->
			<INPUT  type = "hidden" id="nyala"></INPUT>
			<INPUT type = "hidden" id="antrian_bicara" value="0"></INPUT>
			
            <!-- End Main Sidebar -->
            <main class="main-content col-lg-12 col-md-12 col-sm-12 p-0">

                <!-- / .main-navbar -->
                <div class="main-content-container container-fluid px-4">
                    <!-- Page Header -->
                    <div class="page-header row no-gutters py-4">
                      
                        <div class="col-8 col-sm-8 text-center text-sm-left mb-0">
                           
                            <h5 class="page-title" style="color: white;">Antrian Counter</h5>
                        </div>
                       
                    </div>
                    <!-- End Page Header -->
                    <div class="row">
                        <!-- Users By Device Stats -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card card-small h-100">
                                <div class="card-header border-bottom" style="background: linear-gradient(#c1d5e0,#90a4ae);">
                                    <p class="m-0 text-uppercase" style="text-align: center; font-size: 80px; color: #29434e;">Antrian</p>
                                </div>
                                <div class="card-body" id="PANGGIL_ANTRIAN">
                 
                                </div>
                                <div class="card-footer border-top" style="background: linear-gradient(#c1d5e0,#90a4ae);" id="PANGGIL_KONTER">
                                   
                                </div>
                            </div>
                        </div>
                        <!-- End Users By Device Stats -->
                        <!-- Users Stats -->
                        <div class="col-lg-8 col-md-12 col-sm-12 mb-4">
                            <div class="card card-small">
                                <div class="card-body">
                                    <video width="100%" height="100%" autoplay loop muted>
                                        <source src="<?php echo config_item('images'); ?>video.mp4" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>
                        </div>
                        <!-- End Users Stats -->
                    </div>
                    <!-- Small Stats Blocks -->
                    <div class="row">
                        <div class="col-lg col-md-6 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#ffc1e3,#f48fb1);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 1</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-1" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-md-6 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#ffff81,#ffd54f);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 2</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-2" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-md-4 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#b2fab4,#81c784);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 3</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-3" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-md-4 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#8bf6ff,#4fc3f7);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 4</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-4" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <!-- <div class="col-lg col-md-4 col-sm-12 mb-4">
              <div class="stats-small stats-small--1 card card-small">
                <div class="card-body p-0 d-flex">
                  <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                      <span class="stats-small__label text-uppercase">Subscribers</span>
                      <h6 class="stats-small__value count my-3">17,281</h6>
                    </div>
                    <div class="stats-small__data">
                      <span class="stats-small__percentage stats-small__percentage--decrease">2.4%</span>
                    </div>
                  </div>
                  <canvas height="120" class="blog-overview-stats-small-5"></canvas>
                </div>
              </div>
            </div> -->
                    </div>
					
					
					<!-- Small Stats Blocks -->
                    <div class="row">
                        <div class="col-lg col-md-6 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#ffc1e3,#f48fb1);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 5</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-5" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-md-6 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#ffff81,#ffd54f);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 6</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-6" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-md-4 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#b2fab4,#81c784);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 7</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-7" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg col-md-4 col-sm-6 mb-4">
                            <div class="stats-small stats-small--1 card card-small" style="background: linear-gradient(#8bf6ff,#4fc3f7);">
                                <div class="card-body d-flex">
                                    <div class="d-flex flex-column m-auto">
                                        <div class="stats-small__data text-center">
                                            <span class="stats-small__label text-uppercase" style="font-size: 30px;">Counter 8</span>
                                            <h6 class="stats-small__value count my-3" style="font-size: 50px;"><table id="Counter-8" class="table table-striped"></table></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
                        <!-- <div class="col-lg col-md-4 col-sm-12 mb-4">
              <div class="stats-small stats-small--1 card card-small">
                <div class="card-body p-0 d-flex">
                  <div class="d-flex flex-column m-auto">
                    <div class="stats-small__data text-center">
                      <span class="stats-small__label text-uppercase">Subscribers</span>
                      <h6 class="stats-small__value count my-3">17,281</h6>
                    </div>
                    <div class="stats-small__data">
                      <span class="stats-small__percentage stats-small__percentage--decrease">2.4%</span>
                    </div>
                  </div>
                  <canvas height="120" class="blog-overview-stats-small-5"></canvas>
                </div>
              </div>
            </div> -->
                    </div>
					
					
                    <!-- End Small Stats Blocks -->
                </div>
                <footer class="main-footer d-flex p-2 px-3" style="background-color: 29434e;">
                    <span class="copyright ml-auto my-auto mr-2">Copyright © 2020
                        <a href="#" rel="nofollow">-</a>
                    </span>
                </footer>
            </main>
        </div>
    </div>
   
	
		<script>

				var conn = new WebSocket('ws://192.168.15.222:5999');
				conn.onopen = function(e) {
					console.log("Connection established!");
				};
				
				function sleep(ms) {
				  return new Promise(resolve => setTimeout(resolve, ms));
				}
				
			

				conn.onmessage = async function(e) {
					console.log(e);
					var a = JSON.parse(e.data);
					console.log(e.data);
					if(a.hasOwnProperty("konter")){
								
								var lagi_nyala = document.getElementById("nyala").value;
								var antrian_bicara = document.getElementById("antrian_bicara").value;
								var waktu_tunggu = 15000;
								
								
								if (lagi_nyala == '1'){
									var tunggu_bicara = parseInt(antrian_bicara);
									tunggu_bicara += 1;
									document.getElementById("antrian_bicara").value = tunggu_bicara;
									await sleep(waktu_tunggu * tunggu_bicara);
								}
								
							
								var nomerkonter = a.konter.substr(1,2);
								$('.'+a.konter).remove();
								$('#antri').remove();
								$('#konternya').remove();
								panggilLoket(a.jenis_antrian,a.nomer_antrian,a.konter)
								$("#"+a.konter).append(`<tr class="${a.konter}"><td><h1 align="center">${a.jenis_antrian}${a.nomer_antrian}</td></tr>`);
								$("#PANGGIL_ANTRIAN").append(`<p class="m-0 mt-5" id="antri" style="text-align: center; font-size: 165px; color: #29434e;"><b>${a.jenis_antrian}${a.nomer_antrian}</b></p>`);
								$("#PANGGIL_KONTER").append(`<p class="m-0  text-uppercase" id="konternya" style="text-align: center; font-size: 80px; color: #29434e;"><b> ${a.konter}</b></p>`);
								
							
					}
				};

	   </script>
	   
	   
	<script type="text/javascript">
	function panggilLoket(jenis_antrian,nomer_antrian,konter){
		
		var antrian_bicara = document.getElementById("antrian_bicara").value;
		document.getElementById("nyala").value = "1";
		
		var angka_00 = nomer_antrian.substr(0,2);
		
		
		if (angka_00 == '00'){
			
			nomer_antrian = nomer_antrian.substr(2,1);
		}
		
		var angka_0 = nomer_antrian.substr(0,1);
		if (angka_0 == '0'){
			
			nomer_antrian = nomer_antrian.substr(1,2);
		}
		
		
		
		//MAINKAN SUARA BEL PADA SAAT AWAL
		document.getElementById('suarabelopen').pause();
		document.getElementById('suarabelopen').currentTime=0;
		document.getElementById('suarabelopen').play();
		
			//SET DELAY UNTUK MEMAINKAN REKAMAN NOMOR URUT		
		totalwaktu=document.getElementById('suarabelopen').duration*1000;	
		
		//MAINKAN SUARA NOMOR URUT		
		setTimeout(function() {
				document.getElementById('suarabelnomorurut').pause();
				document.getElementById('suarabelnomorurut').currentTime=0;
				document.getElementById('suarabelnomorurut').play();
		}, totalwaktu);
		totalwaktu=totalwaktu+2000;
		
		
		//MAINKAN JENIS ANTRIAN
		setTimeout(function() {
				document.getElementById(''+jenis_antrian+'').pause();
				document.getElementById(''+jenis_antrian+'').currentTime=0;
				document.getElementById(''+jenis_antrian+'').play();
		}, totalwaktu);
		totalwaktu=totalwaktu+2000;
		
		if (nomer_antrian < 10) {
			
			//panggl antrian 1 sampai 9
			//MAINKAN JENIS ANTRIAN
				setTimeout(function() {
						document.getElementById(''+nomer_antrian+'').pause();
						document.getElementById(''+nomer_antrian+'').currentTime=0;
						document.getElementById(''+nomer_antrian+'').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+2000;
				
			
		} else if (nomer_antrian == 10){
			
				//panggl antrian 10
			
				setTimeout(function() {
						document.getElementById('sepuluh').pause();
						document.getElementById('sepuluh').currentTime=0;
						document.getElementById('sepuluh').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+2000;
			
		}else if (nomer_antrian == 11){
			
				//panggl antrian 11
			
				setTimeout(function() {
						document.getElementById('sebelas').pause();
						document.getElementById('sebelas').currentTime=0;
						document.getElementById('sebelas').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+2000;
			
		} else if (nomer_antrian > 11 && nomer_antrian < 20){
			
			var angka_awal = nomer_antrian.substr(0,1);
			var angka_akhir = nomer_antrian.substr(1,2);
			
			
				setTimeout(function() {
						document.getElementById(''+angka_akhir+'').pause();
						document.getElementById(''+angka_akhir+'').currentTime=0;
						document.getElementById(''+angka_akhir+'').play();
				}, totalwaktu);
				
				if (angka_akhir > 6){
						totalwaktu=totalwaktu+600;
				}else{
						totalwaktu=totalwaktu+450;
				}
				
				
				setTimeout(function() {
						document.getElementById('belas').pause();
						document.getElementById('belas').currentTime=0;
						document.getElementById('belas').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+2000;
				
			
				
		} else if (nomer_antrian > 19 && nomer_antrian < 100){
			
				var angka_awal = nomer_antrian.substr(0,1);
				var angka_akhir = nomer_antrian.substr(1,2);
				
					setTimeout(function() {
						document.getElementById(''+angka_awal+'').pause();
						document.getElementById(''+angka_awal+'').currentTime=0;
						document.getElementById(''+angka_awal+'').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+500;
				
				setTimeout(function() {
						document.getElementById('puluh').pause();
						document.getElementById('puluh').currentTime=0;
						document.getElementById('puluh').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+2000;
				
				
				if (angka_akhir > 0) {
						
							setTimeout(function() {
							document.getElementById(''+angka_akhir+'').pause();
							document.getElementById(''+angka_akhir+'').currentTime=0;
							document.getElementById(''+angka_akhir+'').play();
						}, totalwaktu);
						totalwaktu=totalwaktu+2000;
						
				}		
		}else if (nomer_antrian == 100){
			//panggl antrian 100
			
				setTimeout(function() {
						document.getElementById('seratus').pause();
						document.getElementById('seratus').currentTime=0;
						document.getElementById('seratus').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+2000;	
			
		} else if (nomer_antrian > 100 && nomer_antrian < 1000){
			
				
				var angka_awal = nomer_antrian.substr(0,1);
				var angka_tengah = nomer_antrian.substr(1,1);
				var angka_akhir = nomer_antrian.substr(2,1);
				
				
				
				//bilang angka ratus nya
				if (angka_awal == 1){
					
							setTimeout(function() {
								document.getElementById('seratus').pause();
								document.getElementById('seratus').currentTime=0;
								document.getElementById('seratus').play();
						}, totalwaktu);
						totalwaktu=totalwaktu+1000;	
						
						
					
				}else{
					
						setTimeout(function() {
								document.getElementById(''+angka_awal+'').pause();
								document.getElementById(''+angka_awal+'').currentTime=0;
								document.getElementById(''+angka_awal+'').play();
						}, totalwaktu);
						totalwaktu=totalwaktu+750;	
						
						setTimeout(function() {
						document.getElementById('ratus').pause();
						document.getElementById('ratus').currentTime=0;
						document.getElementById('ratus').play();
				}, totalwaktu);
				totalwaktu=totalwaktu+750;
							
				}
				
				
				if (angka_tengah == 0){
					//jika satuan
						setTimeout(function() {
								document.getElementById(''+angka_akhir+'').pause();
								document.getElementById(''+angka_akhir+'').currentTime=0;
								document.getElementById(''+angka_akhir+'').play();
						}, totalwaktu);
						totalwaktu=totalwaktu+1000;	
						
				} else if (angka_tengah == 1){
					//jika belasan
							if (angka_akhir == 1) {
								
								setTimeout(function() {
										document.getElementById('sebelas').pause();
										document.getElementById('sebelas').currentTime=0;
										document.getElementById('sebelas').play();
								}, totalwaktu);
								totalwaktu=totalwaktu+2000;
								
							}else if (angka_tengah == 1 && angka_akhir == 0){
						
								setTimeout(function() {
										document.getElementById('sepuluh').pause();
										document.getElementById('sepuluh').currentTime=0;
										document.getElementById('sepuluh').play();
								}, totalwaktu);
								totalwaktu=totalwaktu+2000;
							}
							else {
								
								setTimeout(function() {
										document.getElementById(''+angka_akhir+'').pause();
										document.getElementById(''+angka_akhir+'').currentTime=0;
										document.getElementById(''+angka_akhir+'').play();
								}, totalwaktu);
								totalwaktu=totalwaktu+750;	
								
								setTimeout(function() {
										document.getElementById('belas').pause();
										document.getElementById('belas').currentTime=0;
										document.getElementById('belas').play();
								}, totalwaktu);
								totalwaktu=totalwaktu+1000;
								
								
								
							} 
					
				} else {
					
							setTimeout(function() {
										document.getElementById(''+angka_tengah+'').pause();
										document.getElementById(''+angka_tengah+'').currentTime=0;
										document.getElementById(''+angka_tengah+'').play();
								}, totalwaktu);
								totalwaktu=totalwaktu+750;	
							
							setTimeout(function() {
									document.getElementById('puluh').pause();
									document.getElementById('puluh').currentTime=0;
									document.getElementById('puluh').play();
							}, totalwaktu);
							totalwaktu=totalwaktu+1000;
							
							
							if (angka_akhir > 0) {
				
									setTimeout(function() {
											document.getElementById(''+angka_akhir+'').pause();
											document.getElementById(''+angka_akhir+'').currentTime=0;
											document.getElementById(''+angka_akhir+'').play();
									}, totalwaktu);
									totalwaktu=totalwaktu+750;	
							}
							
					
					
				}
				
		
			
			
		} 
		
		//MAINKAN SUARA NOMOR URUT		
		setTimeout(function() {
				document.getElementById('suarabelsuarabelloket').pause();
				document.getElementById('suarabelsuarabelloket').currentTime=0;
				document.getElementById('suarabelsuarabelloket').play();
		}, totalwaktu);
		totalwaktu=totalwaktu+2000;
		
		//nomer konter
		
		if (konter == 'Counter-1'){
			
			var KonterTemp = '1';
			
		}else if (konter == 'Counter-2'){
			
			var KonterTemp = '2';
			
		}else if (konter == 'Counter-3'){
			
			var KonterTemp = '3';
			
		}else if (konter == 'Counter-4'){
			
			var KonterTemp = '4';
			
		}else if (konter == 'Counter-5'){
			
			var KonterTemp = '5';
			
		}else if (konter == 'Counter-6'){
			
			var KonterTemp = '6';
			
		}else if (konter == 'Counter-7'){
			
			var KonterTemp = '7';
			
		}else if (konter == 'Counter-8'){
			
			var KonterTemp = '8';
			
		}
		
		
		//MAINKAN konter	
		setTimeout(function() {
				document.getElementById(''+KonterTemp+'').pause();
				document.getElementById(''+KonterTemp+'').currentTime=0;
				document.getElementById(''+KonterTemp+'').play();
				
				
		}, totalwaktu);
		totalwaktu=totalwaktu+1000;
		
		//MAINKAN konter	
		setTimeout(function() {
				
				
				
				
				if (antrian_bicara > 0){
						document.getElementById("antrian_bicara").value = document.getElementById("antrian_bicara").value - 1;
				}
				
				if (document.getElementById("antrian_bicara").value < 1) {
						document.getElementById("nyala").value = totalwaktu;
				}
				
		}, totalwaktu);
		totalwaktu=totalwaktu+2000;
		
		
		
	}
</script>
	
	
	
<!-- footers -->


<!-- Place any per-page javascript here -->



	
	
	
	
