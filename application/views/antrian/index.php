<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta property="og:image" content="http://themepixels.me/azia/img/azia-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/azia/img/azia-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Antrian PelayananAntrian Pelayanan">
    <meta name="author" content="Antrian Pelayanan">

    <title>Antrian Pelayanan</title>

    <!-- vendor css -->
    <link href="../assets/azia-assets/libfontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../assets/azia-assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../assets/azia-assets/lib/typicons.font/typicons.css" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="../assets/azia-assets/css/azia.css">

    <style>
      .card-dashboard-nineteen .card-body {
        padding-top: 5px;
        padding-left: 5px;
      }

      .huruf {
        color: khaki;
      }

      .blink {
        animation: blink-animation 1s steps(5, start) infinite;
        -webkit-animation: blink-animation 1s steps(5, start) infinite;
      }

      @keyframes blink-animation {
        to {
          visibility: hidden;
        }
      }

      @-webkit-keyframes blink-animation {
        to {
          visibility: hidden;
        }
      }

    </style>

  </head>
  <body class="az-body az-dashboard-eight">

    <div class="az-header az-header-primary">
      <div class="container">
        <div class="az-header-left">
          <a href="#" class="az-logo"><span id="img-logo-clinic"></span></a>
					<span class="conn-status text-danger"></span>
          <a href="#" id="azNavShow" class="az-header-menu-icon d-lg-none"><span></span></a>
        </div><!-- az-header-left -->

        <div class="az-header-right">
          <div class="dropdown az-profile-menu" >
            <a href="#" class="az-img-user" style="color:white; font-size: 25px;">
							<b class="current_datetime"></b>
              <?php
              // $jam=date("H:i:s");
              // echo "<b>". $jam." "."</b>";
              ?>
            </a>
            <div class="dropdown-menu">

              <a type="button" href="#" class="dropdown-item" id="clearLoggedClinic"><i class="typcn typcn-power-outline"></i> Sign Out</a>
            </div><!-- dropdown-menu -->
          </div>
        </div><!-- az-header-right -->
      </div><!-- container -->
    </div><!-- az-header -->

    

    <div class="az-content az-content-dashboard-eight">
      <div class="container d-block">
        <div class="row row-sm mg-b-20">
          <input type="hidden" id="nyala">
          <input type="hidden" id="antrian_bicara" value="0">
          <div class="col-lg-4 mg-t-40 mg-lg-t-0">
            <div class="card card-dashboard-eighteen">
              <h6 class="card-title mg-b-80" style="text-align: center; font-size:32px;">No. Antrian</h6>
              <div id="PANGGIL_ANTRIAN" class="blink" class="card-body row row-xs" style="text-align: center; font-size:87px; font-weight: bold; color: #0062cc;">
              
              </div><!-- card-body -->
              <h6 id="PANGGIL_KONTER" class="card-title mg-t-90" style="text-align: center; font-size:30px;"></h6>
            </div><!-- card -->
          </div><!-- col -->

          <div class="col-lg-8">
            <div class="row row-xs row-sm--sm">
              
              <div class="col-12 mg-t-0">
                <div class="card card-dashboard-nineteen">
                  <div class="card-body">
                    <video width="99%" height="" autoplay loop  >
                    <!-- <source src="<?=base_url('assets/antrian/videos/video.mp4');?>" type="video/mp4"> -->
                      Your browser does not support the video tag.
                    </video>
                  </div>
                  </div><!-- card-body -->
                </div><!-- card -->
              </div><!-- col -->
            </div><!-- row -->
          </div><!-- col -->

          
          <div class="col-lg-12 mg-t-20">
            <div class="row row-sm">

              <div class="col-sm-4">
                <div class="card card-dashboard-twenty">
                  <div class="card-body">
                    <strong>POLI ANAK</strong>
                    <p>&nbsp;</p>
                    <div class="expansion-value">
                      <label class="az-content-label tx-13 tx-danger" >Sedang Tutup</label>
                    </div>
                  </div>
                </div><!-- card -->
              </div><!-- col -->

              <div class="col-sm-4 mg-t-20 mg-sm-t-0">
                <div class="card card-dashboard-twenty ht-md-100p">
                  <div class="card-body">
                    <strong>POLI KULIT</strong>
                    <p>&nbsp;</p>
                    <div class="expansion-value">
                      <label class="az-content-label tx-13 tx-danger" >Sedang Tutup</label>
                    </div>
                  </div>
                </div><!-- card -->
              </div><!-- col -->

              <div class="col-sm-4 mg-t-20 mg-sm-t-0">
                <div class="card card-dashboard-twenty ht-md-100p">
                  <div class="card-body">
                    <strong>POLI GIGI</strong>
                    <p>&nbsp;</p>
                    <div class="expansion-value" >
                      <label class="az-content-label tx-13 tx-danger" >Sedang Tutup</label>
                    </div>
                  </div>
                </div><!-- card -->
              </div><!-- col -->

            </div><!-- row -->
          </div><!-- col -->
        </div><!-- row -->
      </div><!-- container -->
    </div><!-- az-content -->

    <div class="az-footer" style=" background: #0062cc; color: white;">
      <div class="container" style="color: white; font-size: 1.3em;">
        <marquee>Selamat datang di klinik Mubarak, Demi mendukung mengatasi COVID-19 mohon menjaga jarak aman dan menggunakan masker.  Apabila ada keluhan atas pelayanan kami dapat menghubungi call center kita di 081272071825</marquee>
      </div><!-- container -->
    </div><!-- az-footer -->

    <script src="../assets/azia-assets/lib/jquery/jquery.min.js"></script>
    <script src="../assets/azia-assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/azia-assets/lib/ionicons/ionicons.js"></script>
    <script src="../assets/azia-assets/lib/jquery.flot/jquery.flot.js"></script>
    <script src="../assets/azia-assets/lib/jquery.flot/jquery.flot.resize.js"></script>
    <script src="../assets/azia-assets/lib/chart.js/Chart.bundle.min.js"></script>

    <script src="../assets/azia-assets/js/azia.js"></script>
    <script src="../assets/azia-assets/js/chart.flot.sampledata.js"></script>
    <input type="hidden" id="base_url" value="<?= base_url(); ?>">
    <input type="hidden" name="server_socket_antrian" value="<?=conf('SERVER_SOCKET_ANTRIAN');?>">
    <script src="<?=base_url('assets/pages/ctc.js');?>"></script>
    <script src="<?=base_url('assets/antrian/js/ontv.js');?>"></script>

    
  </body>

<!-- dashboard-eight.html  14:08:12 GMT -->
</html>
