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
                margin: 50px 20px 50px 20px;
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
                bottom: -25px;
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
                    <h4>LAPORAN BUKU KAS UMUM PENERIMAAN (BPn-1)</h4>
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
                    <td><?= $month ?></td>
                </tr>
            </table>
        </div>

        <table class="report">
            <thead>
                <tr>
                    <th width="20px">No</th>
                    <th width="80px">Tanggal</th>
                    <th width="190px">Nomor Bukti</th>
                    <th width="100px">Kode Rekening</th>
                    <th width="80px">Alat Bayar</th>
                    <th width="280px">Uraian</th>
                    <th width="80px">Penerimaan</th>
                    <th width="80px">Pengeluaran</th>
                    <th width="80px">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;

                $total_penerimaan = 0;
                $total_pengeluaran = 0;

                foreach ($bpn1 as $key => $row):

                    $total_penerimaan += $row->penerimaan;
                    $total_pengeluaran += $row->pengeluaran;
                    
                    if ($key == 0) {
                ?>
                        <tr>
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                            <td class="center">
                            </td>
                            <td>
                                SALDO AWAL
                            </td>
                            <td class="right">
                            </td>
                            <td class="right">
                            </td>
                            <td class="right">
                                <?= az_thousand_separator_decimal($saldo_awal) ?>
                            </td>
                        </tr>
                <?php
                    }
                ?>
                    <tr>
                        <td class="center">
                            <?= $no++; ?>
                        </td>
                        <td class="center">
                            <?= $row->txt_proof_date ?>
                        </td>
                        <td class="left">
                            <?= $row->proof_number ?>
                        </td>
                        <td class="center">
                            <?= $row->kode_rekening ?>
                        </td>
                        <td class="center">
                            <?= $row->alat_bayar ?>
                        </td>
                        <td>
                            <div>
                                <?= $row->uraian ?>
                            </div>
                            <span style="font-size: 10px">
                                <?= $row->proof_for ?>
                            </span>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->penerimaan) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->pengeluaran) ?>
                        </td>
                        <td class="right">
                            <?= az_thousand_separator_decimal($row->saldo) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div>
            <p>
                Pada hari ini tanggal <?= tanggal_sekarang(); ?> terdapat oleh kami kas sebesar Rp. <?= az_thousand_separator_decimal(end($bpn1)->saldo) ?> ( <?= ucfirst(terbilang(end($bpn1)->saldo)); ?> rupiah )
                <br><br>
                <b>Terdiri Atas :</b>
                <table>
                    <tr>
                        <td style="width: 80px;">Tunai</td>
                        <td style="width: 10px;">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Bank</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Lainnya</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                </table>
            </p>
        </div>

        <!-- <table class="summary">
            <tr>
                <td width="60%">
                    <strong>Total Penerimaan</strong>
                </td>
                <td class="right">
                    <strong>
                        <?= az_thousand_separator_decimal($total_penerimaan) ?>
                    </strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Total Pengeluaran</strong>
                </td>
                <td class="right">
                    <strong>
                        <?= az_thousand_separator_decimal($total_pengeluaran) ?>
                    </strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Saldo Akhir</strong>
                </td>
                <td class="right">
                    <strong>
                        <?= az_thousand_separator_decimal(end($bpn1)->saldo) ?>
                    </strong>
                </td>
            </tr>
        </table> -->

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