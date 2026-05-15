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
        background: linear-gradient(135deg, #144e7c, #1d6ca7b3);
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
    .table-report {
        /* font-size: 12px;
        margin-bottom: 0; */
    }

    .table-report thead th {
        /* background: #144e7c;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dbe4ec; */
    }

    .table-report td {
        /* vertical-align: top; */
        border: 1px solid #dbe4ec; */
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
        background: linear-gradient(135deg, #144e7c, #1d6ca7b3);
        height: 12px;
        padding: 0 !important;
    }
</style>

<?php require_once 'vf_report_detail_evaluasi_anggaran.php'; ?>

<div class="report-wrapper">

    <div class="report-card">

        <!-- <div class="report-header">
            <h3>LAPORAN DETAIL EVALUASI ANGGARAN</h3>
            <p>Provinsi Jawa Timur Tahun Anggaran <?= $tahun_anggaran; ?></p>
        </div> -->

        <div class="table-responsive">

            <table class="table table-bordered table-hover table-report">

                <thead>
                    <tr>
                        <th class="report-header-h3" colspan="11">RENCANA ANGGARAN KAS <br> SATUAN KERJA PERANGKAT DAERAH</th>
                    </tr>
                    <tr>
                        <th class="report-header-p" colspan="11">Provinsi Jawa Timur Tahun Anggaran <?= $tahun_anggaran; ?></th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($arr_data['urusan'] as $urusan): ?>
                    <?php foreach ($urusan['bidang_urusan'] as $bidang): ?>
                        <?php foreach ($bidang['program'] as $program): ?>
                            <?php foreach ($program['kegiatan'] as $kegiatan): ?>
                                <?php foreach ($kegiatan['sub_kegiatan'] as $sub_kegiatan): ?>

                                    <?php
                                        // hitung ada berapa data paket belanja
                                        $last_index = count($sub_kegiatan['paket_belanja']) - 1;
                                    ?>

                                    <?php foreach ($sub_kegiatan['paket_belanja'] as $key_paket => $paket): ?>

                                        <tr>
                                            <td width="140">Urusan</td>
                                            <td width="10">:</td>
                                            <td colspan="9"><?= $urusan['nama_urusan']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Bidang Urusan</td>
                                            <td>:</td>
                                            <td colspan="9"><?= $bidang['nama_bidang_urusan']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Program</td>
                                            <td>:</td>
                                            <td colspan="9"><?= $program['nama_program']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Kegiatan</td>
                                            <td>:</td>
                                            <td colspan="9"><?= $kegiatan['nama_kegiatan']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Sub Kegiatan</td>
                                            <td>:</td>
                                            <td colspan="9"><?= $sub_kegiatan['nama_sub_kegiatan']; ?></td>
                                        </tr>

                                        <tr class="table-section">
                                            <td>Paket Belanja</td>
                                            <td>:</td>
                                            <td colspan="9"><?= $paket['nama_paket_belanja']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Total Anggaran</td>
                                            <td>:</td>
                                            <td colspan="9">
                                                Rp. <?= az_thousand_separator_decimal($paket['nilai_anggaran']); ?>
                                            </td>
                                        </tr>

                                        <!-- Detail Akun Belanja -->
                                        <tr>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold;" rowspan="2" colspan="2">Kode Rekening</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:auto;" rowspan="2">Uraian</td>
                                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="3">Rincian Perhitungan</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;" rowspan="2">Jumlah</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:130px;" colspan="4">Realisasi Sampai</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold; text-align:center; width:50px;">Volume</td>
                                            <td style="font-weight:bold; text-align:center; width:60px;">Satuan</td>
                                            <td style="font-weight:bold; text-align:center; width:100px;">Harga Satuan</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;">TW 1</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;">TW 2</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;">TW 3</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;">TW 4</td>
                                        </tr>

                                        <?php foreach ($paket['akun_belanja'] as $akun): ?>

                                            <tr class="akun-header">
                                                <td colspan="2">
                                                    <?= $akun['no_rekening_akunbelanja']; ?>
                                                </td>
                                                <td colspan="4">
                                                    <?= $akun['nama_akun_belanja']; ?>
                                                </td>
                                                <td class="nominal">
                                                    Rp. <?= az_thousand_separator($akun['total_jumlah']); ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                            <?php foreach ($akun['arr_detail_sub'] as $detail): ?>

                                                <?php if ($detail['is_subkategori'] == 1): ?>

                                                    <tr>
                                                        <td colspan="2"></td>

                                                        <td class="subkategori">
                                                            <?= $detail['nama_subkategori']; ?>
                                                            <br>
                                                            <small><?= $detail['kode_rekening']; ?></small>
                                                        </td>

                                                        <td class="center">
                                                            <?= $detail['volume']; ?>
                                                        </td>

                                                        <td class="center">
                                                            <?= $detail['nama_satuan']; ?>
                                                        </td>

                                                        <td class="nominal">
                                                            Rp. <?= az_thousand_separator($detail['harga_satuan']); ?>
                                                        </td>

                                                        <td class="nominal">
                                                            Rp. <?= az_thousand_separator($detail['jumlah']); ?>
                                                        </td>

                                                        <?php for ($tw = 1; $tw <= 4; $tw++): ?>
                                                            <td class="nominal">
                                                                Rp.
                                                                <?= az_thousand_separator($detail['realisasi_sampai_tw'.$tw]); ?>
                                                                <br>
                                                                (
                                                                <?= az_thousand_separator($detail['persen_realisasi_sampai_tw'.$tw]); ?>%
                                                                )
                                                            </td>
                                                        <?php endfor; ?>
                                                    </tr>

                                                <?php endif; ?>

                                                <?php if ($detail['is_kategori'] == 1): ?>

                                                    <tr>
                                                        <td colspan="2"></td>
                                                        <td colspan="9" class="subkategori">
                                                            <strong><?= $detail['nama_kategori']; ?></strong>
                                                        </td>
                                                    </tr>

                                                    <?php foreach ($detail['arr_pd_detail_sub_sub'] as $sub): ?>

                                                        <tr>
                                                            <td colspan="2"></td>

                                                            <td class="subkategori-child">
                                                                <?= $sub['nama_subkategori']; ?>
                                                                <br>
                                                                <small><?= $sub['kode_rekening']; ?></small>
                                                            </td>

                                                            <td class="center">
                                                                <?= $sub['volume']; ?>
                                                            </td>

                                                            <td class="center">
                                                                <?= $sub['nama_satuan']; ?>
                                                            </td>

                                                            <td class="nominal">
                                                                Rp. <?= az_thousand_separator($sub['harga_satuan']); ?>
                                                            </td>

                                                            <td class="nominal">
                                                                Rp. <?= az_thousand_separator($sub['jumlah']); ?>
                                                            </td>

                                                            <?php for ($tw = 1; $tw <= 4; $tw++): ?>
                                                                <td class="nominal">
                                                                    Rp.
                                                                    <?= az_thousand_separator($sub['realisasi_sampai_tw'.$tw]); ?>
                                                                    <br>
                                                                    (
                                                                    <?= az_thousand_separator($sub['persen_realisasi_sampai_tw'.$tw]); ?>%
                                                                    )
                                                                </td>
                                                            <?php endfor; ?>
                                                        </tr>

                                                    <?php endforeach; ?>

                                                <?php endif; ?>

                                            <?php endforeach; ?>

                                        <?php endforeach; ?>
                                        
                                        <!-- separator -->
                                        <?php if ( ($key_paket != $last_index) || $key_paket == 0 ): ?>
                                            <tr class="separator">
                                                <td colspan="11"></td>
                                            </tr>
                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>