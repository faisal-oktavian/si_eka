<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Surat Pengajuan Panjar Kegiatan</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 40px 60px;
            }
            .kop-container {
                position: relative;
                width: 100%;
                /* margin-bottom: 20px; */
                min-height: 120px;
            }
            .kop-logo {
                position: absolute;
                left: 20px;
                top: 0;
                /* transform: translateX(-50%); */
                width: 50px;
                height: auto;
                z-index: 2;
            }
            .kop-surat {
                text-align: center;
                margin-top: 70px; /* beri ruang untuk logo di atas */
                z-index: 1;
                position: relative;
            }
            .kop-surat h2, .kop-surat h3 {
                margin: 0;
            }
            .kop-surat p {
                margin: 5px 0 0 0;
                font-size: 9px;
            }
            .header-line {
                border-bottom: 2px solid #000;
                margin-top: 10px;
            }
            .title {
                text-align: center;
                font-weight: bold;
                /* margin: 20px 0; */
                text-decoration: underline;
                font-size: 20px;    
            }
            .title-number {
                font-weight: bold;
                text-align: center;
                font-size: 14px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }
            table th, table td {
                border: 1px solid #000;
                padding: 5px;
                text-align: left;
            }
            table > thead > tr > th {
                text-align: center;
                background-color: #7cf8f7f0;
            }
            .no-border {
                margin-top: 20px;;
            }
            .no-border td {
                border: none;
                padding: 3px 5px;
            }
            .ttd-direktur {
                margin-top: 80px;
            }
            .ttd-pptk {
                margin-top: 80px;
            }
        </style>
    </head>

    <body   onload="window.print();">
    <!-- <body> -->
        <div class="kop-container">
            <img src="<?php echo base_url().AZAPP_FRONT.'assets/logo/logo jer basuki mawa beya.png';?>" alt="Logo" class="kop-logo">
            <div class="kop-surat">
                <h3>PEMERINTAH PROVINSI JAWA TIMUR</h3>
                <h3>DINAS KESEHATAN</h3>
                <h2>RUMAH SAKIT UMUM DAERAH SUMBERGLAGAH</h2>
                <p>Dusun Sumberglagah, Desa Tanjungkenongo, Kecamatan Pacet, Kabupaten Mojokerto, Jawa Timur 61374<br>
                Telepon (0321) 690441, Faksimile: (0321) 690137<br>
                Laman: <a href="https://rssumberglagah.jatimprov.go.id/" target="_blank">www.rssumberglagah.jatimprov.go.id</a> Pos-el:rsudsumberglagah@jatimprov.go.id</p>
            </div>
            <div class="header-line"></div>
        </div>

        <div class="title">SURAT PENGAJUAN PANJAR KEGIATAN</div>
        <div class="title-number">
            <p>Nomor: </p>
        </div>

        <table class="no-border">
            <tr>
                <td width="100">PPTK</td>
                <td>:</td>
            </tr>
            <tr>
                <td>Program</td>
                <td>:</td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td>:</td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td>:</td>
            </tr>
            <tr>
                <td>Nomor DPA</td>
                <td>:</td>
            </tr>
        </table>

        <div class="table-container">
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Sehubungan dengan kegiatan yang akan segera dilaksanakan, kami mengajukan dan panjar kegiatan sebesar Rp. </p>
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Dengan ini pula menyatakan dengan sebenarnya bahwa uang sejumlah tersebut digunakan untuk keperluan sebagai berikut :</p>

            <!-- table bidang -->
            <table class="no-border">
                <tr>
                    <td width="100">Bidang</td>
                    <td width="10">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>


            <!-- table uraian -->
            <table>
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Kode Rekening</th>
                        <th>Uraian</th>
                        <th>Jumlah (Rp)</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                        // foreach ($arr_data as $key => $value) {
                    ?>
                            <!-- <tr style="font-weight: bold;">
                                <td rowspan="<?php echo $value['total_data']; ?>" style="vertical-align:top;"><?php echo $value['no_rekening_akunbelanja'];?></td>
                                <td colspan="6"><?php echo $value['nama_akun_belanja'];?></td>
                            </tr> -->
                    <?php   
                            // $space = "padding-left:15px;";
                            // $space_detail = "padding-left:15px;";
                            // foreach ($value['arr_detail'] as $key_sub => $value_sub) {
                            //     if ($value_sub['nama_kategori'] != "") {

                            //         $space_detail = "padding-left:30px;";
                    ?>
                                    <!-- <tr>
                                        <td colspan="6" style="font-weight:bold; <?php echo $space; ?>"><?php echo $value_sub['nama_kategori'];?></td>
                                    </tr> -->
                    <?php
                                // }
                                // foreach ($value_sub['arr_detail_sub'] as $key_ds => $value_ds) {
                    ?>
                                    <!-- <tr>
                                        <td style="<?php echo $space_detail; ?>"><?php echo $value_ds['nama_sub_kategori'];?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['total_anggaran']);?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['sisa_anggaran']);?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['total_sekarang']);?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['sisa_akhir']);?></td>
                                        <td></td>
                                    </tr> -->
                    <?php
                        //         }
                        //     }
                        // }
                    ?>
                </tbody>
            </table>

            <br>
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Panjar tersebut akan segera di pertanggungjawabkan selambat - lambatnya 5 (lima) hari setelah kegiatan dilaksanakan. Demikian surat pengajuan panjar ini di buat.</p>
        </div>

        <table style="width:100%; margin-top:30px; border:none; vertical-align:top;">
            <tr>
                <td style="width:50%; text-align:center; border:none;">
                    Bendahara Pengeluaran Pembantu
                </td>
                <td style="width:50%; text-align:center; border:none;">
                    Pelaksana Kegiatan
                </td>
            </tr>
            <tr style="height: 70px;"> 
            </tr>
            <tr>
                <td style="width:50%; text-align:center; border:none;">
                    <b><u>IKTIA DEWI SHINTA, A.Md</u></b><br>
                    NIP. 19940628 202204 2 001
                </td>
                <td style="width:50%; text-align:center; border:none;">
                    <b><u>DWI LINA NUR W., SE, MM.</u></b><br>
                    NIP. 19710914 200901 2 001
                </td>
            </tr>

            <tr style="height: 50px;">
                <td style="width:50%; text-align:center; border:none;" colspan=2;>
                    Persetujuan,
                    <br>
                    Kuasa Pengguna Anggaran
                </td>
            </tr>
            <tr style="height: 70px;"> 
            </tr>
            <tr>
                <td style="width:50%; text-align:center; border:none;" colspan=2;>
                    <b><u>dr. NINIS HERLINA KIRANASARI</u></b><br>
                    NIP. 19690108 200003 2 003
                </td>
            </tr>
        </table>
    </body>
</html>
