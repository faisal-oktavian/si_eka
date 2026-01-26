<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Surat Pengajuan Panjar Kegiatan</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                /* margin: 20px 60px; */
                margin: 20px 40px;
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
                /* text-decoration: underline; */
                font-size: 20px; 
                margin-top: 10px;   
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

            .ttd-row {
                display: flex;
                justify-content: center; /* posisi tengah halaman */
                gap: 180px;              /* jarak kiri-kanan */
                margin-top: 10px;
            }

            .ttd {
                text-align: left;
                /* border-left: 4px solid red; */
                padding-left: 12px;
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
                <!-- Laman: <a href="https://rssumberglagah.jatimprov.go.id/" target="_blank">www.rssumberglagah.jatimprov.go.id</a> Pos-el:rsudsumberglagah@jatimprov.go.id</p> -->
                Laman: www.rssumberglagah.jatimprov.go.id &nbsp;&nbsp; Pos-el:rsudsumberglagah@jatimprov.go.id</p>
            </div>
            <div class="header-line"></div>
        </div>

        <div class="title">SURAT PENGAJUAN PANJAR KEGIATAN</div>
        <div class="title-number">
            <div>Nomor: <?php echo $npd_panjer_number; ?></div>
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
            <table class="no-border">
                <tr>
                    <td width="100">Bidang</td>
                    <td>: <?php echo $field_activity; ?></td>
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td>: <?php echo $activity; ?></td>
                </tr>
            </table>


            <!-- table uraian -->
            <table>
                <thead>
                    <tr>
                        <th>Kode rekening (No)</th>
                        <th>Uraian</th>
                        <th>Total Anggaran</th>
                        <th>Sisa Anggaran</th>
                        <th>Total Sekarang</th>
                        <th>Sisa Akhir</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                        foreach ($arr_data as $key => $value) {
                    ?>
                            <tr style="font-weight: bold;">
                                <td rowspan="<?php echo $value['total_data']; ?>" style="vertical-align:top;"><?php echo $value['no_rekening_akunbelanja'];?></td>
                                <td colspan="6"><?php echo $value['nama_akun_belanja'];?></td>
                            </tr>
                    <?php   
                            $space = "padding-left:15px;";
                            $space_detail = "padding-left:15px;";
                            foreach ($value['arr_detail'] as $key_sub => $value_sub) {
                                if ($value_sub['nama_kategori'] != "") {

                                    $space_detail = "padding-left:30px;";
                    ?>
                                    <tr>
                                        <td colspan="6" style="font-weight:bold; <?php echo $space; ?>">
                                            <?php echo $value_sub['nama_kategori'];?></td>
                                    </tr>
                    <?php
                                }
                                foreach ($value_sub['arr_detail_sub'] as $key_ds => $value_ds) {
                    ?>
                                    <tr>
                                        <td style="<?php echo $space_detail; ?>">
                                            <?php echo $value_ds['nama_sub_kategori'];?>
                                            <br>
                                            <?php echo $value_ds['nomor_kode_rekening'];?>
                                        </td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['total_anggaran']);?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['sisa_anggaran']);?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['total_sekarang']);?></td>
                                        <td style="text-align: right;"><?php echo az_thousand_separator($value_ds['sisa_akhir']);?></td>
                                        <td><?php echo $value_ds['realization_detail_description'];?></td>
                                    </tr>
                    <?php
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
            <p>&emsp;&emsp;&emsp;&emsp;&emsp; Panjar tersebut akan segera di pertanggungjawabkan selambat - lambatnya 5 (lima) hari setelah kegiatan dilaksanakan. Demikian surat pengajuan panjar ini di buat.</p>
        </div>
        
        <div style="margin-top: 30px; text-align: right; padding-right:5px">Mojokerto, <?php echo $npd_panjer_date; ?><br></div>
        <div class="ttd-row">
            <div class="ttd">
                Bendahara Pengeluaran Pembantu
                <br><br><br><br><br><br>
                Iktia Dewi Shinta, A.Md<br>
                NIP 199406282022042001
            </div>

            <div class="ttd">
                Pelaksana Kegiatan
                <br><br><br><br><br><br>
                Dwi Lina Nur W., SE, MM.<br>
                NIP 197109142009012001
            </div>
        </div>
        <div class="ttd-row">
            <div class="ttd">
                Persetujuan, <br>
                Kuasa Pengguna Anggaran
                <br><br><br><br><br><br>
                dr. Edy Cahyono<br>
                Pembina Tingkat I (IV/b)<br>
                NIP 197301052010011007
            </div>
        </div>
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
