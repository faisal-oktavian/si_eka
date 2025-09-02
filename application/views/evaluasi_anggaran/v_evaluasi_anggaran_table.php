<style>
    thead > tr {
        background-color: #144e7c;
    }
    thead > tr > th {
        color: #ffffff;
        text-align: center;
        font-size: 14px;
        vertical-align: middle !important;
    }  
    .modal-lg {
        width:auto !important;
    }
    .table-responsive{
        font-size: 12px; 
        margin-top: 0px;
        overflow-x: auto !important;
        position: relative;
        width: 100%;
        /* min-height: 400px; jika perlu */
    }
    table {
        border-collapse: separate !important;
        /* Agar sticky tidak bug di beberapa browser */
    }
    /* Tambahan: atur lebar kolom */
    th.col-uraian, td.col-uraian { width: 220px; min-width: 220px; }
    th.col-vol, td.col-vol { width: 60px; min-width: 60px; }
    th.col-vol-sampai-tw, td.col-vol-sampai-tw { width: 100px; min-width: 100px; }
    th.col-rp, td.col-rp { width: 120px; min-width: 120px; }
    th.col-tanggal, td.col-tanggal,
    th.col-penyedia, td.col-penyedia { width: 110px; min-width: 110px; }
    th.col-lk, td.col-lk,
    th.col-pr, td.col-pr {
        width: 45px !important;
        min-width: 45px !important;
        max-width: 45px !important;
    }
    th.col-harga, td.col-harga { width: 100px; min-width: 100px; }
    th.col-ppn, td.col-ppn,
    th.col-pph, td.col-pph { width: 100px; min-width: 100px; }
    th.col-total, td.col-total { width: 100px; min-width: 100px; }
    th.col-gender, td.col-gender {
        width: 110px !important;
        min-width: 110px !important;
        font-size: 12px;
    }
    th.col-persentase, td.col-persentase { width: 120px; min-width: 120px; }
    th.col-sisa-vol, td.col-sisa-vol { width: 80px; min-width: 80px; }
    th.col-sisa-rp, td.col-sisa-rp { width: 100px; min-width: 100px; }

    /* Border kanan untuk kolom terakhir di thead dan tbody */
    th:last-child, td:last-child {
        border-right: 1px solid #dee2e6 !important;
    }

    /* Freeze kolom uraian */
    th.col-uraian, td.col-uraian {
        position: sticky !important;
        left: 0;
        background: #fff;
        z-index: 2;
        box-shadow: 2px 0 2px -1px #ccc;
    }
    th.col-uraian {
        z-index: 3; /* Supaya header di atas cell */
    }

    th.col-uraian-title {
        background: #144e7c !important;
        color: #fff !important;
    }

    .bold {
        font-weight: bold;
    }

    .abu_abu{
        background-color: rgba(0, 0, 0, .075);
    }
</style>

<!-- cek jika TW 1 maka kolom realisasi TW sebelumnya tidak perlu ditampilkan -->
<?php
    $hide_col_realisasi_sebelumnya = '';
    $hide_col_realisasi_sampai_tw = '';
    if ($data['tw'] == 1) {
        $hide_col_realisasi_sebelumnya = 'hide';
        $hide_col_realisasi_sampai_tw = 'hide';
    }

    $hide_col_after = 'hide';
    $label_capaian = "TW ". $data['tw'] ."";
    if ($data['tw'] > 1) {
        $hide_col_after = '';
        $label_capaian = "Sampai dengan TW ". $data['tw'] ."";
    }
?>

<table id="selectedColumn" class="table table-hover table-bordered table-sm table-condensed" cellspacing="0" width="100%" data-ordering="false">
    <thead>
        <tr>
            <th rowspan="2" class="col-uraian col-uraian-title">Uraian</th>
            
            <!-- realisasi tw sebelumnya -->
            <th colspan="2" class="col-gender <?php echo $hide_col_realisasi_sebelumnya; ?>">Realisasi Gender TW Sebelumnya</th>
            <th colspan="2" class="<?php echo $hide_col_realisasi_sebelumnya; ?>">Realisasi TW Sebelumnya</th>
            
            <!-- realisasi tw saat ini -->
            <th colspan="9" class="bulan1"><?php echo $data['nama_bulan_ke_1']; ?></th>
            <th colspan="9" class="bulan2"><?php echo $data['nama_bulan_ke_2']; ?></th>
            <th colspan="9" class="bulan3"><?php echo $data['nama_bulan_ke_3']; ?></th>
            <th colspan="2" class="col-gender">Realisasi Gender TW <?php echo $data['tw']; ?></th>
            <th colspan="2" class="">Realisasi TW <?php echo $data['tw']; ?></th>

            <!-- total realisasi sampai tw saat ini -->
            <th colspan="2" class="col-gender <?php echo $hide_col_realisasi_sampai_tw; ?>">Realisasi Gender Sampai dengan TW <?php echo $data['tw']; ?></th>
            <th colspan="2" class="<?php echo $hide_col_realisasi_sampai_tw; ?>">Realisasi Sampai dengan TW <?php echo $data['tw']; ?></th>
            <!-- <th rowspan="2" class="col-rp <?php echo $hide_col_realisasi_sampai_tw; ?>">Realisasi Sampai dengan TW <?php echo $data['tw']; ?> (Rp)</th> -->

            <!-- sisa realisasi -->
            <th rowspan="2" class="col-persentase">Capaian <?php echo $label_capaian; ?> (%)</th>
            <th colspan="2" class="">Sisa TW <?php echo $data['tw']; ?></th>
            <!-- <th rowspan="2" class="col-sisa-rp">Sisa TW <?php echo $data['tw']; ?> (Rp)</th> -->
        </tr>
        <tr>
            <!-- realisasi tw sebelumnya -->
            <th class="col-lk <?php echo $hide_col_realisasi_sebelumnya; ?>">LK</th>
            <th class="col-pr <?php echo $hide_col_realisasi_sebelumnya; ?>">PR</th>
            <th class="col-vol <?php echo $hide_col_realisasi_sebelumnya; ?>">Volume</th>
            <th class="col-rp <?php echo $hide_col_realisasi_sebelumnya; ?>">Rp</th>

            <!-- Bulan ke 1 -->
            <th class="bulan1 col-tanggal">Tanggal</th>
            <th class="bulan1 col-penyedia">Penyedia</th>
            <th class="bulan1 col-vol">Volume</th>
            <th class="bulan1 col-lk">LK</th>
            <th class="bulan1 col-pr">PR</th>
            <th class="bulan1 col-harga">Harga Satuan</th>
            <th class="bulan1 col-ppn">PPN</th>
            <th class="bulan1 col-pph">PPH</th>
            <th class="bulan1 col-total">Total</th>

            <!-- Bulan ke 2 -->
            <th class="bulan2 col-tanggal">Tanggal</th>
            <th class="bulan2 col-penyedia">Penyedia</th>
            <th class="bulan2 col-vol">Volume</th>
            <th class="bulan2 col-lk">LK</th>
            <th class="bulan2 col-pr">PR</th>
            <th class="bulan2 col-harga">Harga Satuan</th>
            <th class="bulan2 col-ppn">PPN</th>
            <th class="bulan2 col-pph">PPH</th>
            <th class="bulan2 col-total">Total</th>

            <!-- Bulan ke 3 -->
            <th class="bulan3 col-tanggal">Tanggal</th>
            <th class="bulan3 col-penyedia">Penyedia</th>
            <th class="bulan3 col-vol">Volume</th>
            <th class="bulan3 col-lk">LK</th>
            <th class="bulan3 col-pr">PR</th>
            <th class="bulan3 col-harga">Harga Satuan</th>
            <th class="bulan3 col-ppn">PPN</th>
            <th class="bulan3 col-pph">PPH</th>
            <th class="bulan3 col-total">Total</th>

            <!-- realisasi tw saat ini -->
            <th class="col-lk">LK</th>
            <th class="col-pr">PR</th>
            <th class="col-vol">Volume</th>
            <th class="col-rp">Rp</th>

            <!-- total realisasi sampai tw saat ini -->
            <th class="col-lk <?php echo $hide_col_realisasi_sampai_tw; ?>">LK</th>
            <th class="col-pr <?php echo $hide_col_realisasi_sampai_tw; ?>">PR</th>
            <th class="col-vol-sampai-tw <?php echo $hide_col_realisasi_sampai_tw; ?>">Volume</th>
            <th class="col-rp <?php echo $hide_col_realisasi_sampai_tw; ?>">Rp</th>

            <!-- sisa realisasi -->
            <th class="col-sisa-vol">Volume</th>
            <th class="col-sisa-rp">Rp</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-uraian"><b><?php echo $data['nama_akun_belanja']; ?></b></td>
            <td class="col-lk bold <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"><?php echo az_thousand_separator($data['grand_realisasi_lk_sebelumnya']); ?></td>
            <td class="col-pr bold <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"><?php echo az_thousand_separator($data['grand_realisasi_pr_sebelumnya']); ?></td>
            <td class="col-vol bold <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"><?php echo az_thousand_separator($data['grand_realisasi_vol_sebelumnya']); ?></td>
            <td class="col-rp bold <?php echo $hide_col_realisasi_sebelumnya; ?>" align="right">Rp. <?php echo az_thousand_separator($data['grand_realisasi_rp_sebelumnya']); ?></td>
            
            <!-- Bulan ke 1 -->
            <td class="bulan1 col-tanggal" align="left"></td>
            <td class="bulan1 col-penyedia" align="left"></td>
            <td class="bulan1 col-vol" align="center"></td>
            <td class="bulan1 col-lk" align="center"></td>
            <td class="bulan1 col-pr" align="center"></td>
            <td class="bulan1 col-harga" align="right"></td>
            <td class="bulan1 col-ppn" align="right"></td>
            <td class="bulan1 col-pph" align="right"></td>
            <td class="bulan1 col-total bold" align="right">Rp. <?php echo az_thousand_separator($data['grand_bulan_ke_1']); ?></td>
            
            <!-- Bulan ke 2 -->
            <td class="bulan1 col-tanggal" align="left"></td>
            <td class="bulan1 col-penyedia" align="left"></td>
            <td class="bulan1 col-vol" align="center"></td>
            <td class="bulan1 col-lk" align="center"></td>
            <td class="bulan1 col-pr" align="center"></td>
            <td class="bulan1 col-harga" align="right"></td>
            <td class="bulan1 col-ppn" align="right"></td>
            <td class="bulan1 col-pph" align="right"></td>
            <td class="bulan1 col-total bold" align="right">Rp. <?php echo az_thousand_separator($data['grand_bulan_ke_2']); ?></td>

            <!-- Bulan ke 3 -->
            <td class="bulan1 col-tanggal" align="left"></td>
            <td class="bulan1 col-penyedia" align="left"></td>
            <td class="bulan1 col-vol" align="center"></td>
            <td class="bulan1 col-lk" align="center"></td>
            <td class="bulan1 col-pr" align="center"></td>
            <td class="bulan1 col-harga" align="right"></td>
            <td class="bulan1 col-ppn" align="right"></td>
            <td class="bulan1 col-pph" align="right"></td>
            <td class="bulan1 col-total bold" align="right">Rp. <?php echo az_thousand_separator($data['grand_bulan_ke_3']); ?></td>

            <!-- realisasi tw saat ini -->
            <td class="col-lk bold abu_abu" align="center"><?php echo az_thousand_separator($data['grand_realisasi_lk']); ?></td>
            <td class="col-pr bold abu_abu" align="center"><?php echo az_thousand_separator($data['grand_realisasi_pr']); ?></td>
            <td class="col-vol bold" align="center"><?php echo az_thousand_separator($data['grand_realisasi_vol']); ?></td>
            <td class="col-rp bold" align="right">Rp. <?php echo az_thousand_separator($data['grand_realisasi_rp']); ?></td>

            <!-- total realisasi sampai tw saat ini -->
            <td class="col-lk bold abu_abu <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"><?php echo az_thousand_separator($data['grand_realisasi_lk_sampai']); ?></td>
            <td class="col-pr bold abu_abu <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"><?php echo az_thousand_separator($data['grand_realisasi_pr_sampai']); ?></td>
            <td class="col-vol bold <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"><?php echo az_thousand_separator($data['grand_realisasi_vol_sampai']); ?></td>
            <td class="col-rp bold <?php echo $hide_col_realisasi_sampai_tw; ?>" align="right">Rp. <?php echo az_thousand_separator($data['grand_realisasi_rp_sampai']); ?></td>
            
            <?php
                $bg_color_rp = '';

                if ($data['tw'] == 4) {
                    if ($data['grand_sisa_vol'] == 0 && $data['grand_sisa_rp'] != 0) {
                        $bg_color_rp = '#f0ed21';
                    }
                }
            ?>
            
            <!-- sisa realisasi -->
            <td class="col-persentase bold abu_abu" align="right"><?php echo az_thousand_separator($data['grand_capaian_sampai']); ?> %</td>
            <td class="col-sisa-vol bold" align="center"><?php echo az_thousand_separator($data['grand_sisa_vol']); ?></td>
            <td class="col-sisa-rp bold" align="right" style="background-color: <?php echo $bg_color_rp;?>;">Rp. <?php echo az_thousand_separator($data['grand_sisa_rp']); ?></td>
        </tr>

        <?php
            foreach ($data['arr_detail'] as $key => $value) {
                $bulan1 = '';
                $bulan2 = '';
                $bulan3 = '';

                if ($value['text_decoration_bulan_ke_1'] != "") {
                    $bulan1 = 'style="'.$value['text_decoration_bulan_ke_1'].'" ';
                }
                if ($value['text_decoration_bulan_ke_2'] != "") {
                    $bulan2 = 'style="'.$value['text_decoration_bulan_ke_2'].'" ';
                }
                if ($value['text_decoration_bulan_ke_3'] != "") {
                    $bulan3 = 'style="'.$value['text_decoration_bulan_ke_3'].'" ';
                }

                if (strlen($value['idkategori']) > 0) {
        ?>
                    <tr>
                        <td class="col-uraian"><b><?php echo $value['nama_kategori']; ?></b></td>
                        
                        <!-- realisasi tw sebelumnya -->
                        <td class="col-lk <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"></td>
                        <td class="col-pr <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"></td>
                        <td class="col-vol <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"></td>
                        <td class="col-rp <?php echo $hide_col_realisasi_sebelumnya; ?>" align="right"></td>
                        
                        <!-- Bulan ke 1 -->
                        <td <?php echo $bulan1; ?> class="bulan1 col-tanggal" align="left"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-penyedia" align="left"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-vol" align="center"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-lk" align="center"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-pr" align="center"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-harga" align="right"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-ppn" align="right"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-pph" align="right"></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-total" align="right"></td>
                        
                        <!-- Bulan ke 2 -->
                        <td <?php echo $bulan2; ?> class="bulan2 col-tanggal" align="left"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-penyedia" align="left"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-vol" align="center"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-lk" align="center"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-pr" align="center"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-harga" align="right"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-ppn" align="right"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-pph" align="right"></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-total" align="right"></td>

                        <!-- Bulan ke 3 -->
                        <td <?php echo $bulan3; ?> class="bulan3 col-tanggal" align="left"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-penyedia" align="left"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-vol" align="center"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-lk" align="center"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-pr" align="center"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-harga" align="right"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-ppn" align="right"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-pph" align="right"></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-total" align="right"></td>

                        <!-- realisasi tw saat ini -->
                        <td class="col-lk abu_abu" align="center"></td>
                        <td class="col-pr abu_abu" align="center"></td>
                        <td class="col-vol" align="center"></td>
                        <td class="col-rp" align="right"></td>

                        <!-- total realisasi sampai tw saat ini -->
                        <td class="col-lk abu_abu <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"></td>
                        <td class="col-pr abu_abu <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"></td>
                        <td class="col-vol <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"></td>
                        <td class="col-rp <?php echo $hide_col_realisasi_sampai_tw; ?>" align="right"></td>

                        <!-- sisa realisasi -->
                        <td class="col-persentase abu_abu" align="right"></td>
                        <td class="col-sisa-vol" align="center"></td>
                        <td class="col-sisa-rp" align="right"></td>
                    </tr>
        <?php
                }
                else {
        ?>  
                    <tr>
                        <td class="col-uraian"><?php echo $value['nama_subkategori']; ?></td>
                        
                        <!-- realisasi tw sebelumnya -->
                        <td class="col-lk <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"><?php echo az_thousand_separator($value['realisasi_lk_sebelumnya']); ?></td>
                        <td class="col-pr <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"><?php echo az_thousand_separator($value['realisasi_pr_sebelumnya']); ?></td>
                        <td class="col-vol <?php echo $hide_col_realisasi_sebelumnya; ?>" align="center"><?php echo az_thousand_separator($value['realisasi_vol_sebelumnya']); ?></td>
                        <td class="col-rp <?php echo $hide_col_realisasi_sebelumnya; ?>" align="right">Rp. <?php echo az_thousand_separator($value['realisasi_rp_sebelumnya']); ?></td>
                        
                        <!-- Bulan ke 1 -->
                        <td <?php echo $bulan1; ?> class="bulan1 col-tanggal" align="left"><?php echo $value['tanggal_bulan_ke_1']; ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-penyedia" align="left"><?php echo $value['penyedia_bulan_ke_1']; ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-vol" align="center"><?php echo az_thousand_separator($value['volume_bulan_ke_1']); ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-lk" align="center"><?php echo az_thousand_separator($value['laki_bulan_ke_1']); ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-pr" align="center"><?php echo az_thousand_separator($value['perempuan_bulan_ke_1']); ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-harga" align="right">Rp. <?php echo az_thousand_separator($value['harga_satuan_bulan_ke_1']); ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-ppn" align="right">Rp. <?php echo az_thousand_separator($value['ppn_bulan_ke_1']); ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-pph" align="right">Rp. <?php echo az_thousand_separator($value['pph_bulan_ke_1']); ?></td>
                        <td <?php echo $bulan1; ?> class="bulan1 col-total" align="right">Rp. <?php echo az_thousand_separator($value['total_bulan_ke_1']); ?></td>
                        
                        <!-- Bulan ke 2 -->
                        <td <?php echo $bulan2; ?> class="bulan2 col-tanggal" align="left"><?php echo $value['tanggal_bulan_ke_2']; ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-penyedia" align="left"><?php echo $value['penyedia_bulan_ke_2']; ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-vol" align="center"><?php echo az_thousand_separator($value['volume_bulan_ke_2']); ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-lk" align="center"><?php echo az_thousand_separator($value['laki_bulan_ke_2']); ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-pr" align="center"><?php echo az_thousand_separator($value['perempuan_bulan_ke_2']); ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-harga" align="right">Rp. <?php echo az_thousand_separator($value['harga_satuan_bulan_ke_2']); ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-ppn" align="right">Rp. <?php echo az_thousand_separator($value['ppn_bulan_ke_2']); ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-pph" align="right">Rp. <?php echo az_thousand_separator($value['pph_bulan_ke_2']); ?></td>
                        <td <?php echo $bulan2; ?> class="bulan2 col-total" align="right">Rp. <?php echo az_thousand_separator($value['total_bulan_ke_2']); ?></td>

                        <!-- Bulan ke 3 -->
                        <td <?php echo $bulan3; ?> class="bulan3 col-tanggal" align="left"><?php echo $value['tanggal_bulan_ke_3']; ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-penyedia" align="left"><?php echo $value['penyedia_bulan_ke_3']; ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-vol" align="center"><?php echo az_thousand_separator($value['volume_bulan_ke_3']); ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-lk" align="center"><?php echo az_thousand_separator($value['laki_bulan_ke_3']); ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-pr" align="center"><?php echo az_thousand_separator($value['perempuan_bulan_ke_3']); ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-harga" align="right">Rp. <?php echo az_thousand_separator($value['harga_satuan_bulan_ke_3']); ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-ppn" align="right">Rp. <?php echo az_thousand_separator($value['ppn_bulan_ke_3']); ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-pph" align="right">Rp. <?php echo az_thousand_separator($value['pph_bulan_ke_3']); ?></td>
                        <td <?php echo $bulan3; ?> class="bulan3 col-total" align="right">Rp. <?php echo az_thousand_separator($value['total_bulan_ke_3']); ?></td>

                        <!-- realisasi tw saat ini -->
                        <td class="col-lk abu_abu" align="center"><?php echo az_thousand_separator($value['realisasi_lk']); ?></td>
                        <td class="col-pr abu_abu" align="center"><?php echo az_thousand_separator($value['realisasi_pr']); ?></td>
                        <td class="col-vol" align="center"><?php echo az_thousand_separator($value['realisasi_vol']); ?></td>
                        <td class="col-rp" align="right">Rp. <?php echo az_thousand_separator($value['realisasi_rp']); ?></td>

                        <!-- total realisasi sampai tw saat ini -->
                        <td class="col-lk abu_abu <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"><?php echo az_thousand_separator($value['realisasi_lk_sampai']); ?></td>
                        <td class="col-pr abu_abu <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"><?php echo az_thousand_separator($value['realisasi_pr_sampai']); ?></td>
                        <td class="col-vol <?php echo $hide_col_realisasi_sampai_tw; ?>" align="center"><?php echo az_thousand_separator($value['realisasi_vol_sampai']); ?></td>
                        <td class="col-rp <?php echo $hide_col_realisasi_sampai_tw; ?>" align="right">Rp. <?php echo az_thousand_separator($value['realisasi_rp_sampai']); ?></td>


                        <?php
                            $bg_color_rp = '';

                            if ($data['tw'] == 4) {
                                if ($value['sisa_vol'] == 0 && $value['sisa_rp'] != 0) {
                                    $bg_color_rp = '#f0ed21';
                                }
                            }
                        ?>

                        <!-- sisa realisasi -->
                        <td class="col-persentase abu_abu" align="right"><?php echo az_thousand_separator($value['capaian_sampai']); ?> %</td>
                        <td class="col-sisa-vol" align="center"><?php echo az_thousand_separator($value['sisa_vol']); ?></td>
                        <td class="col-sisa-rp" align="right" style="background-color: <?php echo $bg_color_rp;?>;">Rp. <?php echo az_thousand_separator($value['sisa_rp']); ?></td>
                    </tr>
        <?php
                }
        ?>  
        <?php
            }
        ?>
    </tbody>
</table>