<style>
    h4 {
        font-weight: bold;
    }
    .heading {
        font-size: 16px;
    }
    .container-report {
        padding: 20px 10px;
    }
    .content-report {
        margin-left:10px; 
        margin-right:10px;
    }
    .report {
        padding: 20px 10px;
    }
</style>

<div class="container-fluid container-report">
    <div class="row">
        <div class="col-md-12">
            <p class="heading">Berikut adalah daftar laporan yang tersedia. Silakan pilih laporan yang ingin Anda lihat.</p>
        </div>
    </div>

    <hr>
    
    <div class="row content-report">
        <?php
            if (aznav('role_report_realisasi_anggaran')) {
        ?>
                <div class="col-md-6 report">
                    <h4>Laporan Realisasi Anggaran</h4>
                    <p>Menampilan data uraian yang telah di realisasi yang dikelompokkan per bulan dan per uraian.</p>
                    <a href="<?php echo app_url().'report_realisasi_anggaran' ?>"><button class="btn btn-primary"> Lihat Laporan</button></a>
                </div>
        <?php
            }
            if (aznav('role_report_sisa_realisasi_anggaran')) {
        ?>
                <div class="col-md-6 report">
                    <h4>Laporan Sisa Anggaran</h4>
                    <p>Menampilkan semua uraian yang masih mempunyai sisa anggaran pada masing-masing paket belanja.</p>
                    <a href="<?php echo app_url().'report_sisa_anggaran' ?>"><button class="btn btn-primary"> Lihat Laporan</button></a>
                </div>
        <?php
            }
        ?>
    </div>
</div>