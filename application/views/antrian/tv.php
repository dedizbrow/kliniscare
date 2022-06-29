<!doctype html>
    <html class="no-js h-100" lang="en">


    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Antrian Pelayanan</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" id="main-stylesheet" data-version="1.1.0" href="styles/shards-dashboards.1.1.0.min.css">
        <link rel="stylesheet" href="styles/extras.1.1.0.min.css">
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <style>
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

    <body class="h-100">


    
	<div class="page-header row no-gutters py-4" style="background-color:#022066 !important">
        <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
            <span class="text-uppercase page-subtitle" style="color:white;font-style: bold;padding-left: 10px; font-size:20px"></span>
            <h3 class="page-title"></h3>
        </div>
    </div>

        <div class="container-fluid" >
            <div class="row">

                <INPUT type="hidden" id="nyala"></INPUT>
                <INPUT type="hidden" id="antrian_bicara" value="0"></INPUT>

                <main class="main-content col-lg-12 col-md-12 col-sm-12">
                    
                    <div class="main-content-container container-fluid px-4">
                        <!-- Page Header -->


                        <div class="row">
                            <!-- Users By Device Stats -->
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4" style="margin-top: 7px;">
                                <div class="card card-small h-100">
                                    <div class="card-header">
                                        <p class="stats-small__value text-center" style="font-size: 70px; color: royalblue;">No. Antrian</p>
                                    </div>
                                    <div id="PANGGIL_ANTRIAN" class="blink" class="card-body" style="padding-top:50px;font-weight: bold;font-size: 90px;text-align: center;color: blue"></div>
                                    <div id="PANGGIL_KONTER" class="card-footer" style="margin-top: 65px;font-size: 50px;text-align: center;color: green"></div>
                                </div>
                            </div>
                            <!-- End Users By Device Stats -->
                            <!-- Users Stats -->
                            <div class="col-lg-8 col-md-12 col-sm-12 mb-4" style="margin-top: 7px;">
                                <div class="card card-small" style="">
                                        <video width="100%" height="100%" autoplay loop muted>
                                            <source src="<?=base_url('assets/antrian/videos/video.mp4');?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Users Stats -->

                            </div>
                        </div>

                        <!-- Small Stats Blocks -->
                        <div class="row">
                            <div class="col-lg col-md-6 col-sm-6 mb-4">
                                <div class="stats-small stats-small--1 card card-small" style="background-color: #00a3a3;">
                                    <div class="card-body p-0 d-flex">
                                        <div class="stats-small__data text-left">
                                            <span class="text-uppercase" style="margin-left: 20px; font-size: 30px; color: lavender;">COUNTER<br>
                                                <p style="font-size: 50px; margin: 0px;">*1</p>
                                            </span>
                                        </div>
                                        <div class="stats-small__data" id="Counter-1">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg col-md-6 col-sm-6 mb-4">
                                <div class="stats-small stats-small--1 card card-small" style="background-color: #00a3a3;">
                                    <div class="card-body p-0 d-flex">
                                        <div class="stats-small__data text-left">
                                            <span class="text-uppercase" style="margin-left: 20px; font-size: 30px; color: lavender;">COUNTER<br>
                                                <p style="font-size: 50px; margin: 0px;">*2</p>
                                            </span>
                                        </div>
                                        <div class="stats-small__data" id="Counter-2">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg col-md-6 col-sm-6 mb-4">
                                <div class="stats-small stats-small--1 card card-small" style="background-color: #00a3a3;">
                                    <div class="card-body p-0 d-flex">
                                        <div class="stats-small__data text-left">
                                            <span class="text-uppercase" style="margin-left: 20px; font-size: 30px; color: lavender;">COUNTER<br>
                                                <p style="font-size: 50px; margin: 0px;">*3</p>
                                            </span>
                                        </div>
                                        <div class="stats-small__data" id="Counter-3">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg col-md-6 col-sm-6 mb-4">
                                <div class="stats-small stats-small--1 card card-small" style="background-color: #00a3a3;">
                                    <div class="card-body p-0 d-flex">
                                        <div class="stats-small__data text-left">
                                            <span class="text-uppercase" style="margin-left: 20px; font-size: 30px; color: lavender;">COUNTER<br>
                                                <p style="font-size: 50px; margin: 0px;">*4</p>
                                            </span>
                                        </div>
                                        <div class="stats-small__data" id="Counter-4">

                                        </div>
                                    </div>
                                </div>
                            </div> -->
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
            </div>
        </div>

        
    </main>
</div>
</div>
<footer class="main-footer bg-white border-top" style="padding-top: 13px;color: white; background-color:#022066 !important"">
            <span class="copyright ml-auto my-auto mr-12"><marquee>Copyright © 2022 Version</marquee>
            </span>
        </footer>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script src="https://unpkg.com/shards-ui@latest/dist/js/shards.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sharrre/2.0.1/jquery.sharrre.min.js"></script>
<script src="<?php echo config_item('scripts'); ?>extras.1.1.0.min.js"></script>
<script src="<?php echo config_item('scripts'); ?>shards-dashboards.1.1.0.min.js"></script>
<script src="<?php echo config_item('scripts'); ?>app/app-blog-overview.1.1.0.js"></script>

<input type="hidden" id="base_url" value="<?= base_url(); ?>">
<input type="hidden" name="server_socket_antrian" value="<?=conf('SERVER_SOCKET_ANTRIAN');?>">

<script src="<?=base_url('assets/pages/ctc.js');?>"></script>
<script src="<?=base_url('assets/antrian/js/ontv.js');?>"></script>

</body>

</html>
