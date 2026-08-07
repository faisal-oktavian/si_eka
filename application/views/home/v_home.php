<style>
	.centered {
		/* width: ; */
		float: none;
		margin: 20px auto;
	}

	.dropdown-menu {
		padding: 10px;
	}

	.setting-btn {
		background-color: #f2f2f2;
		padding: 5px 10px;
		border-radius: 6px;
		border: 2px #87ceeb solid;
		display: flex;
		align-items: center;
		/* justify-content: space-between; */
	}

	.setting-btn span {
		margin-left: 10px;
		color: gray;
	}

	/* .dropdown-child a:hover {
		color: #232323 !important;
		background: #f3f3f3 !important;
	} */

	.chart-box {
		border-bottom: 5px dashed #f5f5f5;
	}

	.title-chart {
		font-weight: bold;
		padding-bottom: 5px;
	}

	.h3-not-found {
		text-align: center;
		/* height: fit-content; */
		margin: 100px;
		color: #dadada;
		display: flex;
		/* flex-wrap: ; */
		justify-content: center;
	}

	.dropdown-label {
		margin-right: 10px;
	}

	/* table tr{
		margin-bottom: 2px;
	} */
	.progress {
        width: 100%;
        background-color: #f3f3f3;
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-bar {
        height: 20px;
        background-color: #4caf50;
        text-align: center;
        color: #144e7c;
    }

	#idpaket_belanja_filter {
		display: none;
	}

    /*  ======================
          STYLE TAB CONTENT
        ====================== */
    /*  ======================
                  TAB
        ====================== */

            .tab-wrapper{
                display:flex;
                flex-wrap:wrap;
                gap:12px;
                margin-bottom:25px;
                margin-top:5px;
            }

            .tab-button{
                padding:14px 24px;
                border:none;
                border-radius:14px;
                background:white;
                color:#1d2340;
                font-size:15px;
                font-weight:600;
                cursor:pointer;

                border:2px solid #e5e5e5;

                transition:0.3s ease;
            }

            .tab-button:hover{
                transform:translateY(-4px);
                border-color:#d89a63;
                box-shadow:0 10px 20px rgba(216,154,99,0.18);
            }

            .tab-button.active{
                background:#144e7c;
                /* background:#0cb299; */
                color:white;
                border-color:#144e7c;
                /* border-color:#0cb299; */
            }


    /*  ======================
              CONTENT
        ====================== */
            .tab-content{
                display:none;
                animation:fadeIn 0.3s ease;
                border-top: 1px solid #e5e5e5;
            }

            .tab-content.active{
                display:block;
            }

            @keyframes fadeIn{
                from{
                    opacity:0;
                    transform:translateY(10px);
                }
                to{
                    opacity:1;
                    transform:translateY(0);
                }
            }


    /*  ======================
                CARD
        ====================== */
            .card{
                background:transparent;
                /* border-radius:20px; */
                padding:25px;
                /* box-shadow:0 10px 25px rgba(0,0,0,0.05); */
            }


    /*  ======================
              TABLE TITLE
        ====================== */
            .card-title{
                font-size:26px;
                font-weight:700;
                margin-bottom:20px;
                color:#2b5054;
            }


    /*  ======================
              RESPONSIVE
        ====================== */
            @media(max-width:768px){

                .tab-button{
                    width:100%;
                }

                .card-title{
                    font-size:22px;
                }

            }

    /*  ======================
           END STYLE TAB CONTENT
          ====================== */

    .panel {
        border-radius:12px !important;
    }
</style>
<?php
	if (aznav('role_table')) {
?>		
	
        <!-- ======================
                TAB BUTTON
            ====================== -->

            <div class="tab-wrapper">
                <button class="tab-button active" onclick="openTab(event, 'pengeluaran')">
                    Pengeluaran
                </button>
                <button class="tab-button" onclick="openTab(event, 'pemasukan')">
                    Pemasukan
                </button>
            </div>

        <!-- ======================
                TAB PENGELUARAN
            ====================== -->

            <div id="pengeluaran" class="tab-content active">
                <div class="card">
                    <div class="card-title">
                        Pengeluaran
                    </div>

                    <div class="card-content">
                        <div class="rsedu-wrapper">

                            <!-- Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan -->
                                <div class="row" style="margin-top:30px;">
                                    <div class="col-md-12 col-xs-12" style="margin:auto;">
                                        <div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
                                            <div class="d-flex align-items-center" style="margin-bottom:18px; text-align:center;">
                                                <i class="fa fa-line-chart" style="font-size:26px;color:#2196f3;margin-right:10px;"></i>
                                                <span class="title-chart" style="font-size:20px;">Persentase Capaian Target & Realisasi Anggaran per Bulan (Tahun <?php echo $tahun_ini; ?>)</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12" style="display:flex;align-items:center;justify-content:center;">
                                                    <canvas id="lineCapaianPerBulan" height="120"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan -->


                            <!-- grafik realisasi anggaran -->
                                <div class="row" style="margin-top:30px;">
                                    <div class="col-md-12 col-xs-12" style="margin:auto;">
                                        <div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
                                            <div class="d-flex align-items-center" style="margin-bottom:18px;">
                                                <i class="fa fa-pie-chart" style="font-size:26px;color:#4caf50;margin-right:10px;"></i>
                                                <span class="title-chart" style="font-size:20px;">Grafik Realisasi Anggaran Tahun <?php echo $tahun_ini; ?></span>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6" style="display:flex;align-items:center;justify-content:center;width: 300px; height: 300px; margin: auto;">
                                                    <canvas id="pieAnggaranChart" width="120" height="120"></canvas>
                                                </div>
                                                <div class="col-xs-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center; margin-left:5%">
                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#28A745;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Sudah Dibayar</div>
                                                                    <div id="label-sudah-dibayar" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#FFCC66;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Menunggu Pembayaran</div>
                                                                    <div id="label-menunggu-pembayaran" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#999999;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">NPD</div>
                                                                    <div id="label-npd" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#0066FF;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Sudah Diverifikasi</div>
                                                                    <div id="label-sudah-diverifikasi" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">

                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#FF6600;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Menunggu Verifikasi</div>
                                                                    <div id="label-menunggu-verifikasi" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#FF9900;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Kontrak Pengadaan</div>
                                                                    <div id="label-kontrak-pengadaan" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#FFCC00;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Proses Pengadaan</div>
                                                                    <div id="label-proses-pengadaan" style="font-size:15px;"></div>
                                                                </div>
                                                            </div> -->
                                                            <div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#f44336;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Belum Direalisasi</div>
                                                                    <div id="label-belum-direalisasi" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>	
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END grafik realisasi anggaran -->


                            <!-- grafik potensi sisa anggaran & Realisasi Anggaran per Sumber Dana -->
                                <div class="row" style="margin-top:30px;">

                                    <!-- grafik potensi sisa anggaran-->
                                    <div class="col-md-6 col-xs-12" style="margin:auto;">
                                        <div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
                                            <div class="d-flex align-items-center" style="margin-bottom:18px;">
                                                <span class="title-chart" style="font-size:22px;font-weight:700;color:#263238;">Potensi Sisa Anggaran Tahun <?php echo $tahun_ini; ?></span>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6" style="display:flex;align-items:center;justify-content:center;">
                                                    <canvas id="piePotensiSisaChart" width="180" height="180"></canvas>
                                                </div>
                                                <div class="col-xs-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center;">
                                                    <div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;margin-bottom:12px;display:flex;align-items:center;">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#c500ff;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">Total Anggaran</div>
                                                            <div id="label-total-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;margin-bottom:12px;display:flex;align-items:center;">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#2196f3;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">Realisasi Anggaran</div>
                                                            <div id="label-realisasi-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;display:flex;align-items:center;">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#c3c3c3;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">Sisa Anggaran</div>
                                                            <div id="label-sisa-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Realisasi Anggaran per Sumber Dana -->
                                    <div class="col-md-6 col-xs-12" style="margin:auto;">
                                        <div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
                                            <div class="d-flex align-items-center" style="margin-bottom:18px;">
                                                <span class="title-chart" style="font-size:22px;font-weight:700;color:#263238;">Realisasi Anggaran per Sumber Dana Tahun <?php echo $tahun_ini; ?></span>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6" style="display:flex;align-items:center;justify-content:center;">
                                                    <canvas id="pieRealisasiSumberDanaChart" width="180" height="180"></canvas>
                                                </div>
                                                <div class="col-xs-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center;">
                                                    <div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;margin-bottom:12px;display:flex;align-items:center;">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#2196f3;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">DAU yang Ditentukan Penggunaannya Bidang Kesehatan</div>
                                                            <div id="label-realisasi-dbh" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;display:flex;align-items:center;">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#c500ff;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">Pendapatan dari BLUD</div>
                                                            <div id="label-realisasi-blud" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END grafik potensi sisa anggaran & Realisasi Anggaran per Sumber Dana -->


                            <!-- grafik Perbandingan Target & Realisasi Anggaran per Bulan -->
                                <div class="row" style="margin-top:30px;">
                                    <div class="col-md-12 col-xs-12" style="margin:auto;">
                                        <div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
                                            <!-- <div class="d-flex align-items-center" style="margin-bottom:18px;">
                                                <i class="fa fa-bar-chart" style="font-size:26px;color:#2196f3;margin-right:10px;"></i>
                                                <span class="title-chart" style="font-size:20px;">Perbandingan Target & Realisasi Anggaran per Bulan (Tahun <?php echo $tahun_ini; ?>)</span>
                                            </div> -->
                                            <div class="row">
                                                <div class="col-xs-12" style="display:flex;align-items:center;justify-content:center;">
                                                    <canvas id="perbandingan" height="120"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END grafik Perbandingan Target & Realisasi Anggaran per Bulan -->


                            <!-- Data Paket Belanja Yang Belum Terealisasi -->
                                <div class="row" style="margin-top:24px;">
                                    <div class="col-md-12">
                                        <div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:18px 0 18px 0; background:#fff;">
                                            <div class="d-flex align-items-center" style="margin-bottom:10px; text-align:center;">
                                                <i class="fa fa-bar-chart" style="font-size:26px;color:#2196f3;margin-right:10px;"></i>
                                                <span class="title-chart" style="font-size:20px;">Data Paket Belanja Yang Belum Terealisasi Pada Tahun <?php echo $tahun_ini; ?></span>
                                            </div>
                                            <div class="table-responsive" style="padding:0 18px;">
                                                <?php echo $belum_terealisasi;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END Data Paket Belanja Yang Belum Terealisasi -->

                        </div>
                    </div>
                </div>
            </div>

        <!-- ======================
                TAB PEMASUKAN
            ====================== -->

            <div id="pemasukan" class="tab-content">
                <div class="card">
                    <div class="card-title">
                        Pemasukan
                    </div>
                    <div class="card-content">
                        <div class="rsedu-wrapper">

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <i class="fa fa-money"></i>
                                            <b>Total Penerimaan s.d Bulan Berjalan</b>
                                        </div>
                                        <div class="panel-body">
                                            <h2 style="margin-top:5px;">
                                                Rp <?= az_thousand_separator_decimal($realisasi_tahun) ?>
                                            </h2>
                                            <small>
                                                Target Tahun
                                                Rp <?= az_thousand_separator_decimal($target_tahun) ?>
                                            </small>
                                            <div class="progress" style="margin-top:15px;">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?= $persen_tahun ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= min($persen_tahun,100) ?>%;">
                                                    <?= az_thousand_separator_decimal($persen_tahun,2) ?>%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <i class="fa fa-calendar"></i>
                                            <b>Penerimaan Bulan Berjalan</b>
                                        </div>
                                        <div class="panel-body">
                                            <h2 style="margin-top:5px;">
                                                Rp <?= az_thousand_separator_decimal($realisasi_bulan) ?>
                                            </h2>
                                            <small>
                                                Target Bulan
                                                Rp <?= az_thousand_separator_decimal($target_bulan) ?>
                                            </small>
                                            <div class="progress" style="margin-top:15px;">
                                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?= $persen_bulan ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= min($persen_bulan,100) ?>%;">
                                                    <?= az_thousand_separator_decimal($persen_bulan,2) ?>%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            <i class="fa fa-line-chart"></i>
                                            <b>Penerimaan Hari Ini</b>
                                        </div>
                                        <div class="panel-body">
                                            <h2 style="margin-top:5px;">
                                                Rp <?= az_thousand_separator_decimal($realisasi_hari) ?>
                                            </h2>
                                            <small>
                                                Persentase terhadap Target Bulan
                                            </small>
                                            <div class="progress" style="margin-top:15px;">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?= $persen_hari ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= min($persen_hari,100) ?>%;">
                                                    <?= az_thousand_separator_decimal($persen_hari,2) ?>%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="panel panel-default">
                                        <div class="panel-body text-center">
                                            <h4>Total STS Tahun Ini</h4>
                                            <h2><?= az_thousand_separator($jumlah_sts_tahun) ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="panel panel-default">
                                        <div class="panel-body text-center">
                                            <h4>Total STS Bulan Ini</h4>
                                            <h2><?= az_thousand_separator($jumlah_sts_bulan) ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="panel panel-default">
                                        <div class="panel-body text-center">
                                            <h4>Total STS Hari Ini</h4>
                                            <h2><?= az_thousand_separator($jumlah_sts_hari) ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="panel panel-default">
                                        <div class="panel-body text-center">
                                            <h4>Rata-rata / Hari</h4>
                                            <h2>
                                                Rp <?= az_thousand_separator_decimal($rata_harian) ?>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <div class="row">
                                <!-- Mutasi Kas Masuk -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading text-center">
                                            <strong>Kas Masuk</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <i class="fa fa-arrow-circle-down fa-3x text-success"></i>
                                            <h3 style="margin-top:15px;">
                                                Rp <?= az_thousand_separator_decimal($mutasi_masuk) ?>
                                            </h3>
                                            <small>Total Mutasi Penerimaan</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mutasi Kas Keluar -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading text-center">
                                            <strong>Kas Keluar</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <i class="fa fa-arrow-circle-up fa-3x text-danger"></i>
                                            <h3 style="margin-top:15px;">
                                                Rp <?= az_thousand_separator_decimal($mutasi_keluar) ?>
                                            </h3>
                                            <small>Total Mutasi Pengeluaran</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pindah Rekening -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-info">
                                        <div class="panel-heading text-center">
                                            <strong>Pindah Rekening</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <i class="fa fa-random fa-3x text-info"></i>
                                            <h3 style="margin-top:15px;">
                                                Rp <?= az_thousand_separator_decimal($mutasi_pindah) ?>
                                            </h3>
                                            <small>Total Transfer Rekening</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Saldo Kas Bendahara -->
                                <div class="col-md-3 col-sm-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading text-center">
                                            <strong>Saldo Kas</strong>
                                        </div>
                                        <div class="panel-body text-center">
                                            <i class="fa fa-credit-card fa-3x text-primary"></i>
                                            <h3>
                                                Rp <?= az_thousand_separator_decimal($saldo_kas) ?>
                                            </h3>
                                            <small>Posisi Saldo Saat Ini</small>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <i class="fa fa-line-chart"></i>
                                            <strong>Penerimaan Bulanan</strong>
                                        </div>
                                        <!-- <div class="panel-body"> -->
                                        <div class="panel-body" style="height:300px;">
                                            <!-- <canvas id="chartPenerimaan" height="120"></canvas> -->
                                            <canvas id="chartPenerimaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <i class="fa fa-pie-chart"></i>
                                            <strong>Target vs Realisasi</strong>
                                        </div>
                                        <!-- <div class="panel-body"> -->
                                        <div class="panel-body" style="height:300px;">
                                            <!-- <canvas id="chartTarget" height="220"></canvas> -->
                                            <canvas id="chartTarget"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end row -->

                        </div>
                    </div>
                </div>
            </div>


        <script>
            /*  =========================
                        TAB
                ========================= */
                function openTab(evt, tabId){

                    let tabContent = document.getElementsByClassName("tab-content");

                    for(let i = 0; i < tabContent.length; i++){
                        tabContent[i].classList.remove("active");
                    }

                    let tabButton = document.getElementsByClassName("tab-button");

                    for(let i = 0; i < tabButton.length; i++){
                        tabButton[i].classList.remove("active");
                    }

                    document.getElementById(tabId).classList.add("active");

                    evt.currentTarget.classList.add("active");
                }
            /*  =========================
                        END TAB
                ========================= */
        </script>

<?php
	} 
?>