<style>
    .report-wrapper {
        margin: 0px 10px 15px 10px;
    }

    .report-card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        font-size: 12px;
    }

    .report-header-h3 {
        background: linear-gradient(135deg, #144e7c, #7db0d6b3);
        color: #fff;
        padding: 20px;
        text-align: center;
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }

    .report-header-p {
        background: linear-gradient(135deg, #a6d7ff, #e7e7e7);
        color:rgb(0, 0, 0);
        padding: 20px;
        text-align: center;
        margin: 5px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }
    table {
        margin-bottom: 0px !important;
    }
    .table-report td {
        border: 1px solid #dbe4ec;
    }

    .harga-satuan-realisasi-cell {
        max-width: 120px;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.3;
        vertical-align: top;
    }

    .harga-satuan-realisasi-cell details {
        cursor: pointer;
    }

    .harga-satuan-realisasi-cell summary {
        list-style: none;
        outline: none;
        font-weight: 600;
        color: #1a1a1a;
    }

    .harga-satuan-realisasi-cell summary::-webkit-details-marker {
        display: none;
    }

    .harga-satuan-realisasi-cell .realization-summary {
        color: #5a5a5a;
        font-size: 11px;
        margin-top: 3px;
    }

    .harga-satuan-realisasi-cell .realization-details {
        margin-top: 8px;
        padding-left: 0;
        font-size: 11px;
        color: #333;
    }

    .harga-satuan-realisasi-cell .realization-details div {
        margin-bottom: 4px;
    }

    .table-section {
        background: #eef6ff;
        font-weight: bold;
    }

    .akun-header {
        background: #f5f7fa;
        font-weight: bold;
    }

    .nominal {
        text-align: right;
        white-space: nowrap;
    }

    .center {
        text-align: center;
    }

    .subkategori {
        padding-left: 30px !important;
    }

    .subkategori-child {
        padding-left: 50px !important;
    }

    .separator td {
        background: linear-gradient(135deg, #144e7c, #7db0d6b3);
        height: 12px;
        padding: 0 !important;
    }
    .dev{
      display: none;
    }

    .loader-card {
        width: 180px;
        padding: 20px 10px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(15,53,83,.18);
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #dbeafe;
        animation: fadeInLoader .35s ease;
    }

    @keyframes fadeInLoader {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .spinner {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: conic-gradient(#0f3553, #1e5b8f, #1597d4, #9fd3f5, #0f3553);
        animation: spin 1s linear infinite;
        position: relative;
        box-shadow: 0 0 18px rgba(30,91,143,.35);
    }

    .spinner::before {
        content: "";
        position: absolute;
        inset: 9px;
        background: white;
        border-radius: 50%;
    }

    .spinner::after {
        content: "";
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        border: 3px solid rgba(30,91,143,.15);
        animation: pulseSpinner 1.5s infinite;
    }

    @keyframes pulseSpinner {
        0% { transform: scale(.9); opacity: .5; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(.9); opacity: .5; }
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .loading-text {
        margin-top: 14px;
        font-size: 16px;
        font-weight: 600;
        color: #1e5b8f;
        letter-spacing: .3px;
    }
    .dots span { animation: loadingDots 1.4s infinite; }
    .dots span:nth-child(2){ animation-delay: .2s; }
    .dots span:nth-child(3){ animation-delay: .4s; }
    @keyframes loadingDots {
        0%,80%,100% { opacity:0; transform:translateY(0); }
        40% { opacity:1; transform:translateY(-3px); }
    }

    .blur-row { filter: blur(3px); opacity: .7; transition: .4s ease; }
    .blur-row.blur-2 { filter: blur(5px); opacity: .5; }
    .blur-row.blur-3 { filter: blur(7px); opacity: .3; }

    .loader-overlay {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: none;
        justify-content: center;
        align-items: center;
    }
    .loader-overlay.active { display: flex; }
    .initial-loader { display: none; justify-content: center; align-items: center; margin-top: 30px; }
    .initial-loader.active { display: flex; }
    .table-card { position: relative; }
    #observer { height: 1px; }
</style>

<!-- filter -->
<?php require_once 'vf_evaluasi_anggaran.php';?>

<div class="report-wrapper">

    <div class="report-card">

        <!-- <div class="report-header">
            <h3>LAPORAN DETAIL EVALUASI ANGGARAN</h3>
            <p>Provinsi Jawa Timur Tahun Anggaran <?= isset($tahun_anggaran) ? $tahun_anggaran : ''; ?></p>
        </div> -->

        <div class="table-responsive table-card">

            <table class="table table-bordered table-hover table-report">

                <thead>
                    <tr>
                        <th class="report-header-h3" colspan="11">RENCANA ANGGARAN KAS <br> SATUAN KERJA PERANGKAT DAERAH</th>
                    </tr>
                    <tr>
                        <th class="report-header-p" colspan="11">Provinsi Jawa Timur Tahun Anggaran <?= isset($tahun_anggaran) ? $tahun_anggaran : ''; ?></th>
                    </tr>
                </thead>

                <tbody id="evaluasiTableBody">
                </tbody>

            </table>

            <div id="initialLoader" class="initial-loader"></div>
            <div id="lazyLoader" class="loader-overlay"></div>
            <div id="observer"></div>

            <div id="backToTop">
                <svg class="progress-ring" width="66" height="66">
                    <circle class="progress-ring-circle" stroke-width="4" fill="transparent" r="29" cx="33" cy="33"/>
                </svg>

                <button id="btnTop"><i class="fa fa-chevron-up"></i></button>
            </div>

        </div>

    </div>

</div>