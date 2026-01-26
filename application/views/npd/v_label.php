<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Nota Pencairan Dana (NPD)</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 40px;
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


            .ttd-row {
                display: flex;
                justify-content: center; /* posisi tengah halaman */
                gap: 180px;              /* jarak kiri-kanan */
                margin-top: 50px;
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

        <div class="title">NOTA PENCAIRAN DANA (NPD)</div>
        <div class="title-number">
            <!-- <p>Nomor: <?php echo $nomor_surat;?></p>
            <p>Tanggal: <?php echo $npd_date;?></p> -->

            <div>Nomor: <?php echo $nomor_surat;?></div>
            <div>Tanggal: <?php echo $npd_date;?></div>
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
                <td>Nomor DPPA</td>
                <td>: <?php echo $nomor_dpa; ?></td>
            </tr>
            <tr>
                <td>Tahun Anggaran</td>
                <td>: <?php echo $tahun_anggaran; ?></td>
            </tr>
            <tr>
                <td>Rincian Belanja</td>
                <td>:</td>
            </tr>
        </table>

        <div class="table-container">
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
        </div>

        <div class="ttd-row">
            <div class="ttd">
                Pengguna Anggaran<br>
                Kuasa Pengguna Anggaran
                <br><br><br><br><br><br>
                dr. Edy Cahyono<br>
                Pembina Tingkat I (IV/b)<br>
                NIP 197301052010011007
            </div>

            <div class="ttd">
                Pejabat Pelaksana Teknis Kegiatan (PPTK)
                <br><br><br><br><br><br><br>
                Hari Purnomo, S.Kep.Ns., M.Kes.<br>
                Pembina (IV/a)<br>
                NIP 197103061993031004
            </div>
        </div>
    </body>
</html>
