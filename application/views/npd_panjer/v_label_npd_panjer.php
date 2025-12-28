<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Surat Pengajuan Panjar Kegiatan</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 20px 60px;
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
                margin-top: 10px;
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
                <td>: <?php echo $pptk; ?></td>
            </tr>
            <tr>
                <td>Program</td>
                <td>: <?php echo $program; ?></td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td>: <?php echo $kegiatan; ?></td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td>: <?php echo $sub_kegiatan; ?></td>
            </tr>
            <tr>
                <td>Nomor DPA</td>
                <td>: <?php echo $nomor_dpa; ?></td>
            </tr>
        </table>

        <div class="table-container">
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Sehubungan dengan kegiatan yang akan segera dilaksanakan, kami mengajukan dan panjar kegiatan sebesar Rp. <?php echo az_thousand_separator($total_realisasi); ?>,- (<?php echo terbilang($total_realisasi); ?>)</p>
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Dengan ini pula menyatakan dengan sebenarnya bahwa uang sejumlah tersebut digunakan untuk keperluan sebagai berikut :</p>

            <!-- table bidang -->
            <table class="no-border" style="margin-top: 0 !important;">
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
                        <th width="30">No. </th>
                        <th width="150">Kode Rekening</th>
                        <th width="200">Uraian</th>
                        <th width="70">Jumlah (Rp)</th>
                        <th width=auto>Keterangan</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                        $no = 0;
                        foreach ($arr_data as $key => $value) {
                            $no++;
                    ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $no; ?></td>
                                <td><?php echo $value['kode_rekening'];?></td>
                                <td><?php echo $value['nama_uraian'];?></td>
                                <td style="text-align: right;"><?php echo az_thousand_separator($value['total']);?></td>
                                <td><?php echo $value['description_detail'];?></td>
                            </tr>
                    <?php
                        }
                    ?>

                    <tr style="font-weight: bold;">
                        <td colspan="3" style="text-align: center;">Total</td>
                        <td style="text-align: right;"><?php echo az_thousand_separator($total_realisasi);?></td>
                        <td></td>
                </tbody>
            </table>
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Panjar tersebut akan segera di pertanggungjawabkan selambat - lambatnya 5 (lima) hari setelah kegiatan dilaksanakan. Demikian surat pengajuan panjar ini di buat.</p>
        </div>

        <table style="width:100%; margin-top:0px; border:none; vertical-align:top;">
            <tr>
                <td style="width:50%; text-align:center; border:none;">
                </td>
                <td style="width:50%; text-align:center; border:none;">
                    Mojokerto, <?php echo $npd_panjer_date; ?>
                </td>
            </tr>
            <tr>
                <td style="width:50%; text-align:center; border:none;">
                    Bendahara Pengeluaran Pembantu
                </td>
                <td style="width:50%; text-align:center; border:none;">
                    Pelaksana Kegiatan
                </td>
            </tr>
            <tr style="height: 50px;"> 
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
                <td style="width:50%; text-align:center; border:none;" colspan=2>
                    Persetujuan,
                    <br>
                    Kuasa Pengguna Anggaran
                </td>
            </tr>
            <tr style="height: 50px;"> 
            </tr>
            <tr>
                <td style="width:50%; text-align:center; border:none;" colspan=2>
                    <b><u>dr. NINIS HERLINA KIRANASARI</u></b><br>
                    NIP. 19690108 200003 2 003
                </td>
            </tr>
        </table>
    </body>
</html>


<?php 
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = penyebut($nilai - 10). " Belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000009999999) {
            $temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }

    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }     		
        return $hasil;
    }
?>
