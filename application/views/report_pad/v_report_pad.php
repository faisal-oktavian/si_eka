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
            if (aznav('role_report_bpn1')) {
        ?>
                <div class="col-md-6 report">
                    <h4>Laporan Buku Kas Umum Penerimaan (BPn - 1)</h4>
                    <p>Menampilkan data seluruh transaksi penerimaan kas yang telah dicatat, disusun secara kronologis sebagai Buku Kas Umum Penerimaan sesuai periode yang dipilih.</p>
                    <a href="<?php echo app_url().'report_bpn1' ?>"><button class="btn btn-primary"> Lihat Laporan</button></a>
                </div>
        <?php
            }
            if (aznav('role_report_bpn3')) {
        ?>
                <div class="col-md-6 report">
                    <h4>Laporan Realisasi Pendapatan (BPn - 3)</h4>
                    <p>Menampilkan data realisasi pendapatan yang dikelompokkan berdasarkan akun pendapatan beserta nilai anggaran, realisasi, dan sisa anggaran sesuai periode yang dipilih.</p>
                    <a href="<?php echo app_url().'report_bpn3' ?>"><button class="btn btn-primary"> Lihat Laporan</button></a>
                </div>
        <?php
            }
        ?>
    </div>
</div>