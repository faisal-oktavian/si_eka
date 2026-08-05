<?php

function terbilang($angka)
{
    $angka = number_format($angka, 2, '.', '');

    $pecah = explode('.', $angka);

    $nilai = (int)$pecah[0];
    $decimal = (int)$pecah[1];

    $hasil = terbilang_integer($nilai);

    if ($decimal > 0) {
        $hasil .= ' koma ' . terbilang_integer($decimal);
    }

    return trim($hasil);
}


function terbilang_integer($angka)
{
    $angka = abs($angka);

    $huruf = [
        "",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan",
        "sepuluh",
        "sebelas"
    ];


    if ($angka < 12) {

        return $huruf[$angka];

    } elseif ($angka < 20) {

        return terbilang_integer($angka - 10) . " belas";

    } elseif ($angka < 100) {

        return terbilang_integer(floor($angka / 10))
            . " puluh "
            . terbilang_integer($angka % 10);

    } elseif ($angka < 200) {

        return "seratus "
            . terbilang_integer($angka - 100);

    } elseif ($angka < 1000) {

        return terbilang_integer(floor($angka / 100))
            . " ratus "
            . terbilang_integer($angka % 100);

    } elseif ($angka < 2000) {

        return "seribu "
            . terbilang_integer($angka - 1000);

    } elseif ($angka < 1000000) {

        return terbilang_integer(floor($angka / 1000))
            . " ribu "
            . terbilang_integer($angka % 1000);

    } elseif ($angka < 1000000000) {

        return terbilang_integer(floor($angka / 1000000))
            . " juta "
            . terbilang_integer($angka % 1000000);

    } elseif ($angka < 1000000000000) {

        return terbilang_integer(floor($angka / 1000000000))
            . " milyar "
            . terbilang_integer($angka % 1000000000);

    } elseif ($angka < 1000000000000000) {

        return terbilang_integer(floor($angka / 1000000000000))
            . " triliun "
            . terbilang_integer($angka % 1000000000000);

    }

    return "";
}

function tanggal_sekarang() {
    $bulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $tanggal = date('d') . ' ' . $bulan[(int)date('m')] . ' ' . date('Y');

    return $tanggal;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            @page {
                margin: 20px 20px 20px 20px;
            }
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 10px;
                color: #000;
                margin: 0;
                padding: 0;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }

            /* ===========================
            HEADER
            =========================== */
            .header {
                width: 100%;
                margin-bottom: 15px;
            }
            .header td {
                border: none;
                vertical-align: middle;
            }
            .logo {
                width: 70px;
            }
            .logo img {
                width: 60px;
            }
            .title {
                text-align: center;
            }
            .title h2 {
                margin: 0;
                font-size: 18px;
            }
            .title h3 {
                margin: 3px 0;
                font-size: 16px;
            }
            .title h4 {
                margin: 3px 0;
                font-size: 13px;
            }
            .periode {
                margin-top: 10px;
                margin-bottom: 15px;
                font-size: 11px;
            }
            hr {
                border: none;
                border-top: 2px solid #000;
                margin: 5px 0;
            }

            /* ===========================
            TABLE
            =========================== */
            .report {
                width: 100%;
                border-collapse: collapse;
            }
            .report thead {
                display: table-header-group;
            }
            .report th {

                border: 1px solid #000;
                background: #EFEFEF;
                padding: 5px;
                text-align: center;
                font-weight: bold;
                vertical-align: middle;
            }
            .report td {

                border: 1px solid #000;
                padding: 4px;
                vertical-align: top;

            }
            .center {
                text-align: center;
            }
            .left {
                text-align: left;
            }
            .right {
                text-align: right;
            }

            /* ===========================
            TOTAL
            =========================== */
            .summary {

                margin-top: 10px;
                width: 45%;
                float: right;

            }
            .summary td {

                border: none;
                padding: 3px;

            }

            /* ===========================
            SIGNATURE
            =========================== */
            .signature {

                width: 100%;
                margin-top: 20px;

            }
            .signature td {

                border: none;
                text-align: center;

            }
            .ttd-space {

                height: 80px;

            }

            /* ===========================
            FOOTER
            =========================== */
            .footer {

                position: fixed;
                bottom: -45px;
                left: 0;
                right: 0;

                font-size: 9px;

            }
            .footer table td {

                border: none;

            }
        </style>
    </head>

    <body>
        <div class="footer">
            <table>
                <tr>
                    <td>
                        Dicetak :
                        <?= date('d/m/Y H:i:s'); ?>
                    </td>
                </tr>
            </table>
        </div>
        <table class="header">
            <tr>
                <td class="logo" width="70">
                    <!--
                    <img src="<?= FCPATH ?>assets/images/logo.png">
                    -->
                </td>
                <td class="title">
                    <h2>PEMERINTAH PROVINSI JAWA TIMUR</h2>
                    <h3>RSUD SUMBERGLAGAH</h3>
                    <h4>LAPORAN REALISASI PENDAPATAN (BPn-3)</h4>
                </td>
                <td width="70"></td>
            </tr>
        </table>

        <hr>

        <div class="periode">
            <table>
                <tr>
                    <td style="width: 200px;"><strong>URUSAN PEMERINTAH</strong></td>
                    <td style="width: 10px;">:</td>
                    <td>( 102 ) URUSAN PEMERINTAH BIDANG KESEHATAN</td>
                </tr>
                <tr>
                    <td><strong>ORGANISASI</strong></td>
                    <td>:</td>
                    <td>( 000000010010 ) Rumah Sakit Umum Daerah Sumberglagah</td>
                </tr>
                <tr>
                    <td><strong>BENDAHARA PENERIMAAN</strong></td>
                    <td>:</td>
                    <td>DWI MASTUTIK</td>
                </tr>
                <tr>
                    <td><strong>PERIODE</strong></td>
                    <td>:</td>
                    <td><?= $date1; ?> s.d <?= $date2; ?></td>
                </tr>
            </table>
        </div>

        <table class="report">
            <thead>
                <tr>
                    <th width="3%"  rowspan="2">No</th>
                    <th width="10%" rowspan="2">Kode Rekening</th>
                    <th width="auto" rowspan="2">Uraian</th>
                    <th width="auto"  colspan="2">Target Pendapatan</th>
                    <th width="auto"  colspan="3">Realisasi (Rp.)</th>
                    <th width="auto"  colspan="2">% Pencapaian Target</th>
                </tr>
                <tr>
                    <th width="11%">12 Bulan</th>
                    <th width="11%">Bulan Laporan</th>
                    <th width="11%">Bulan Ini</th>
                    <th width="11%">Jumlah s/d Bulan Lalu</th>
                    <th width="11%">Jumlah s/d Bulan Ini</th>
                    <th width="5%">Tahunan</th>
                    <th width="5%">Bulanan</th>  
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;

                $grand_target_per_tahun = 0;
                $grand_bulan_laporan    = 0;
                $grand_bulan_ini        = 0;
                $grand_sd_bulan_lalu    = 0;
                $grand_sd_bulan_ini     = 0;
                $grand_capaian_tahunan  = 0;
                $grand_capaian_bulanan  = 0;

                foreach ($sts as $key => $row):

                    $grand_target_per_tahun += $row->target_per_tahun;
                    $grand_bulan_laporan += $row->bulan_laporan;
                    $grand_bulan_ini += $row->bulan_ini;
                    $grand_sd_bulan_lalu += $row->sd_bulan_lalu;
                    $grand_sd_bulan_ini += $row->sd_bulan_ini;
                    $grand_capaian_tahunan += $row->capaian_tahunan;
                    $grand_capaian_bulanan += $row->capaian_bulanan;
                    
                ?>
                    <tr>
                        <td class="center">
                            <?= $no++; ?>
                        </td>
                        <td class="center">
                            <?= $row->kode_rekening ?>
                        </td>
                        <td class="left">
                            <?= $row->uraian ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->target_per_tahun) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->bulan_laporan) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->bulan_ini) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->sd_bulan_lalu) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->sd_bulan_ini) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->capaian_tahunan) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->capaian_bulanan) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background-color: #EFEFEF;">
                    <td class="center" colspan="3">
                        Jumlah Total
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_target_per_tahun) ?>
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_bulan_laporan) ?>
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_bulan_ini) ?>
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_sd_bulan_lalu) ?>
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_sd_bulan_ini) ?>
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_capaian_tahunan) ?>
                    </td>
                    <td class="right">
                        <?= az_thousand_separator_decimal($grand_capaian_bulanan) ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="signature">
            <tr>
                <td width="50%">
                    Mengetahui
                    <br>
                    Kuasa Pengguna Anggaran
                </td>
                <td width="50%">
                    Mojokerto, <?= tanggal_sekarang(); ?>
                    <br>
                    Bendahara Penerimaan
                </td>
            </tr>
            <tr>
                <td class="ttd-space"></td>
                <td class="ttd-space"></td>
            </tr>
            <tr>
                <td>
                    <strong>
                        dr. EDY CAHYONO
                        <br>
                        NIP. 197301052010011007
                    </strong>
                </td>
                <td>
                    <strong>
                        DWI MASTUTIK
                        <br>
                        NIP. 198109152008012013
                    </strong>
                </td>
            </tr>
        </table>
    </body>
</html>