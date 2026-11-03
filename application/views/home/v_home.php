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

    #pengeluaran .card-content {
        margin-top: 8px;
    }

    #pengeluaran .expense-chart-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 20px;
        margin: 20px 0 0;
    }

    #pengeluaran .expense-chart-row:before,
    #pengeluaran .expense-chart-row:after,
    #pengeluaran .expense-donut-layout:before,
    #pengeluaran .expense-donut-layout:after,
    #pengeluaran .expense-status-grid:before,
    #pengeluaran .expense-status-grid:after {
        display: none;
    }

    #pengeluaran .expense-chart-row.expense-chart-wide {
        grid-template-columns: minmax(0, 1fr);
    }

    #pengeluaran .expense-chart-row > [class*="col-"] {
        float: none;
        width: auto;
        padding: 0;
        min-width: 0;
    }

    #pengeluaran .expense-chart-card {
        height: 100%;
        margin: 0;
        padding: 22px !important;
        border: 1px solid #e6ebf0 !important;
        border-radius: 16px !important;
        background: #fff !important;
        box-shadow: 0 6px 20px rgba(31, 56, 76, .06) !important;
    }

    #pengeluaran .expense-chart-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin: 0 0 18px !important;
        text-align: left !important;
    }

    #pengeluaran .expense-chart-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #eef5fb;
        color: #144e7c;
        font-size: 18px !important;
        margin: 0 !important;
    }

    #pengeluaran .expense-chart-icon:before {
        display: inline-block;
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
    }

    #pengeluaran .expense-chart-heading {
        min-width: 0;
    }

    #pengeluaran .expense-chart-heading .title-chart {
        display: block;
        padding: 0;
        color: #263746;
        font-size: 17px !important;
        line-height: 1.4;
    }

    #pengeluaran .expense-chart-subtitle {
        display: block;
        margin-top: 4px;
        color: #778592;
        font-size: 13px;
        line-height: 1.5;
    }

    #pengeluaran .expense-chart-body {
        position: relative;
        min-width: 0;
        height: 300px;
    }

    #pengeluaran .expense-chart-body canvas {
        max-width: 100%;
    }

    #pengeluaran .expense-donut-layout {
        display: grid;
        /* Atur lebar kolom donut di sini; ukuran fisik ketiga donut diatur pada .expense-donut-visual di bawah. */
        grid-template-columns: minmax(0, 220px) minmax(0, 1fr);
        align-items: center;
        gap: 20px;
        margin: 0;
    }

    #pengeluaran .expense-donut-layout > [class*="col-"] {
        float: none;
        width: auto !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0;
        min-width: 0;
    }

    #pengeluaran .expense-donut-visual {
        position: relative;
        /* Ubah width dan height ini untuk mengatur ukuran semua grafik pie/donut Pengeluaran. */
        width: 220px;
        max-width: 100%;
        height: 220px !important;
    }

    #pengeluaran .expense-donut-visual canvas {
        width: 100% !important;
        height: 100% !important;
    }

    #pengeluaran .expense-status-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(240px, .85fr);
        align-items: start;
        gap: 16px;
        margin: 0;
    }

    #pengeluaran .expense-status-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 0;
    }

    #pengeluaran .expense-status-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        min-height: 62px;
        margin: 0 !important;
        padding: 12px !important;
        border: 1px solid #edf0f3;
        border-radius: 10px !important;
        background: #f9fafb !important;
    }

    #pengeluaran .expense-status-item > span {
        flex: 0 0 12px;
        width: 12px !important;
        height: 12px !important;
        margin: 4px 0 0 !important;
        border-radius: 4px !important;
    }

    #pengeluaran .expense-status-item > div {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    #pengeluaran .expense-status-item > div > div:first-child {
        color: #495966;
        font-size: 13px;
        line-height: 1.4;
    }

    #pengeluaran .expense-status-item [id^="label-"] {
        margin-top: 4px;
        color: #263746;
        font-size: 13px !important;
        line-height: 1.45;
    }

    #pengeluaran .expense-procurement-summary {
        min-width: 0;
        padding: 16px;
        border: 1px solid #e6ebf0;
        border-radius: 12px;
        background: #f8fafc;
    }

    #pengeluaran .expense-procurement-title {
        margin-bottom: 16px;
        color: #263746;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.45;
    }

    #pengeluaran .expense-procurement-title i {
        margin-right: 7px;
        color: #ff9900;
    }

    #pengeluaran .expense-procurement-metric + .expense-procurement-metric {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #e6ebf0;
    }

    #pengeluaran .expense-procurement-value {
        margin-top: 4px;
        color: #263746;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    #pengeluaran .expense-metric {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin: 0 0 10px !important;
        padding: 12px !important;
        border: 1px solid #edf0f3;
        border-radius: 10px !important;
        background: #f9fafb !important;
    }

    #pengeluaran .expense-metric > span {
        flex: 0 0 12px;
        width: 12px !important;
        height: 12px !important;
        margin: 5px 0 0 !important;
        border-radius: 4px !important;
    }

    #pengeluaran .expense-metric > div {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    #pengeluaran .expense-source-layout {
        grid-template-columns: minmax(0, 220px) minmax(0, 1fr);
    }

    #pengeluaran .expense-source-metrics {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (max-width: 1199px) {
        #pengeluaran .expense-chart-row {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 991px) {
        #pengeluaran .expense-status-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 600px) {
        #pengeluaran .expense-chart-card {
            padding: 16px !important;
        }

        #pengeluaran .expense-donut-layout,
        #pengeluaran .expense-source-layout {
            grid-template-columns: minmax(0, 1fr);
            gap: 12px;
        }

        #pengeluaran .expense-donut-visual {
            height: 220px !important;
            max-width: 100%;
            margin: auto !important;
        }

        #pengeluaran .expense-chart-body {
            height: 260px;
        }
    }
</style>
<?php
	if (aznav('role_table') || aznav('role_income_graphic')) {

        //jika mendapat hak akses grafik pengeluaran anggaran
        $tab_pengeluaran = '';
        $tab_pemasukan = '';
        if (aznav('role_table')) {
            $tab_pengeluaran = 'active';
        }
        else if (aznav('role_income_graphic')) {
            $tab_pemasukan = 'active';
        }
?>		
	
        <!-- ======================
                TAB BUTTON
            ====================== -->

            <div class="tab-wrapper">
                <?php
                    if (aznav('role_table')) {
                ?>
                        <button class="tab-button <?= $tab_pengeluaran; ?>" onclick="openTab(event, 'pengeluaran')">
                            Pengeluaran
                        </button>
                <?php
                    }
                    if (aznav('role_income_graphic')) {
                ?>
                        <button class="tab-button <?= $tab_pemasukan; ?>" onclick="openTab(event, 'pemasukan')">
                            Pemasukan
                        </button>
                <?php
                    }
                ?>
            </div>

        <!-- ======================
                TAB PENGELUARAN
            ====================== -->

            <div id="pengeluaran" class="tab-content <?= $tab_pengeluaran; ?>">
                <div class="card">
                    <div class="card-title">
                        Pengeluaran
                    </div>

                    <div class="card-content">
                        <div class="rsedu-wrapper">

                            <!-- Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan -->
                                <div class="row expense-chart-row expense-chart-wide">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="card shadow expense-chart-card">
                                            <div class="expense-chart-header">
                                                <i class="fa fa-chart-line expense-chart-icon"></i>
                                                <div class="expense-chart-heading">
                                                    <span class="title-chart">Capaian Target & Realisasi per Bulan</span>
                                                    <span class="expense-chart-subtitle">Persentase kumulatif tahun <?php echo $tahun_ini; ?></span>
                                                </div>
                                            </div>
                                            <div class="expense-chart-body">
                                                <canvas id="lineCapaianPerBulan"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan -->


                            <!-- grafik realisasi anggaran -->
                                <div class="row expense-chart-row expense-chart-wide">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="card shadow expense-chart-card">
                                            <div class="expense-chart-header">
                                                <i class="fa fa-chart-pie expense-chart-icon"></i>
                                                <div class="expense-chart-heading">
                                                    <span class="title-chart">Realisasi Anggaran Tahun <?php echo $tahun_ini; ?></span>
                                                    <span class="expense-chart-subtitle">Komposisi posisi anggaran berdasarkan pagu dan nilai realisasi</span>
                                                </div>
                                            </div>
                                            <div class="row expense-donut-layout">
                                                <div class="col-xs-12 col-md-6 expense-donut-visual">
                                                    <canvas id="pieAnggaranChart"></canvas>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="row expense-status-grid">
                                                        <div class="expense-status-list">
                                                            <div class="mb-3 expense-status-item" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#28A745;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Sudah Dibayar</div>
                                                                    <div id="label-sudah-dibayar" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 expense-status-item" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#FFCC66;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Menunggu Pembayaran</div>
                                                                    <div id="label-menunggu-pembayaran" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 expense-status-item" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#999999;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">NPD</div>
                                                                    <div id="label-npd" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="expense-status-list">
                                                            <div class="mb-3 expense-status-item" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#0066FF;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Sudah Diverifikasi</div>
                                                                    <div id="label-sudah-diverifikasi" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 expense-status-item" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#FF6600;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Menunggu Verifikasi</div>
                                                                    <div id="label-menunggu-verifikasi" style="font-size:15px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 expense-status-item" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;display:flex;align-items:center;">
                                                                <span style="display:inline-block;width:18px;height:18px;background:#f44336;margin-right:10px;border-radius:4px;"></span>
                                                                <div>
                                                                    <div style="font-weight:600;">Belum Direalisasi</div>
                                                                    <div id="label-belum-direalisasi" style="font-size:15px;"></div>
                                                                    <small style="display:block;margin-top:5px;color:#778592;line-height:1.45;">Sisa anggaran yang belum direalisasikan, termasuk pagu kontrak pengadaan yang belum mencerminkan nilai realisasi aktual.</small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- ===============================
                                                             ===============================
                                                                HIDE PROGRESS PENGADAAN
                                                             ===============================
                                                             =============================== -->
                                                            <div class="expense-procurement-summary" style="display:none;">
                                                                <div class="expense-procurement-title">
                                                                    <i class="fa fa-briefcase"></i>
                                                                    Progres Pengadaan Tahun <?php echo $tahun_ini; ?>
                                                                </div>
                                                                <div class="expense-procurement-metric">
                                                                    <div style="color:#607d8b;font-size:13px;">Kontrak Pengadaan (nilai pagu)</div>
                                                                    <div id="label-kontrak-pengadaan" class="expense-procurement-value"></div>
                                                                </div>
                                                                <div class="expense-procurement-metric">
                                                                    <div style="color:#607d8b;font-size:13px;">Proses Pengadaan (nilai pagu)</div>
                                                                    <div id="label-proses-pengadaan" class="expense-procurement-value"></div>
                                                                </div>
                                                            </div>
                                                        <!-- ===============================
                                                             ===============================
                                                                HIDE PROGRESS PENGADAAN
                                                             ===============================
                                                             =============================== -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END grafik realisasi anggaran -->


                            <!-- grafik potensi sisa anggaran & Realisasi Anggaran per Sumber Dana -->
                                <div class="row expense-chart-row">

                                    <!-- grafik potensi sisa anggaran-->
                                    <div class="col-md-6 col-xs-12">
                                        <div class="card shadow expense-chart-card">
                                            <div class="expense-chart-header">
                                                <i class="fa fa-chart-pie expense-chart-icon"></i>
                                                <div class="expense-chart-heading">
                                                    <span class="title-chart">Potensi Sisa Anggaran</span>
                                                    <span class="expense-chart-subtitle">Realisasi dibayar dan saldo anggaran tahun <?php echo $tahun_ini; ?></span>
                                                </div>
                                            </div>
                                            <div class="row expense-donut-layout">
                                                <div class="col-xs-12 col-md-6 expense-donut-visual">
                                                    <canvas id="piePotensiSisaChart"></canvas>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="mb-3 expense-metric">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#c500ff;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">Total Anggaran</div>
                                                            <div id="label-total-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 expense-metric">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#2196f3;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">Realisasi Anggaran</div>
                                                            <div id="label-realisasi-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 expense-metric">
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
                                    <div class="col-md-6 col-xs-12">
                                        <div class="card shadow expense-chart-card">
                                            <div class="expense-chart-header">
                                                <i class="fa fa-chart-pie expense-chart-icon"></i>
                                                <div class="expense-chart-heading">
                                                    <span class="title-chart">Realisasi per Sumber Dana</span>
                                                    <span class="expense-chart-subtitle">Komposisi realisasi menurut sumber pada tahun <?php echo $tahun_ini; ?></span>
                                                </div>
                                            </div>
                                            <div class="row expense-donut-layout expense-source-layout">
                                                <div class="col-xs-12 col-md-6 expense-donut-visual">
                                                    <canvas id="pieRealisasiSumberDanaChart"></canvas>
                                                </div>
                                                <div class="col-xs-12 col-md-6 expense-source-metrics">
                                                    <div class="mb-3 expense-metric">
                                                        <span style="display:inline-block;width:22px;height:22px;background:#2196f3;margin-right:14px;border-radius:5px;"></span>
                                                        <div>
                                                            <div style="font-weight:700;color:#263238;">DAU yang Ditentukan Penggunaannya Bidang Kesehatan</div>
                                                            <div id="label-realisasi-dau" style="font-size:17px;color:#263238;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 expense-metric">
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
                                <div class="row expense-chart-row expense-chart-wide">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="card shadow expense-chart-card">
                                            <div class="expense-chart-header">
                                                <i class="fa fa-chart-bar expense-chart-icon"></i>
                                                <div class="expense-chart-heading">
                                                    <span class="title-chart">Target & Realisasi per Bulan</span>
                                                    <span class="expense-chart-subtitle">Perbandingan nilai anggaran tahun <?php echo $tahun_ini; ?></span>
                                                </div>
                                            </div>
                                            <div class="expense-chart-body">
                                                <canvas id="perbandingan"></canvas>
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

            <div id="pemasukan" class="tab-content <?= $tab_pemasukan; ?>">
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