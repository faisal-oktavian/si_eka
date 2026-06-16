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

    .harga-satuan-realisasi-cell {
        max-width: 120px;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.3;
        vertical-align: top;
    }

    .harga-satuan-realisasi-cell details {
        cursor: pointer;
    }

    .harga-satuan-realisasi-cell summary {
        list-style: none;
        outline: none;
        font-weight: 600;
        color: #1a1a1a;
    }

    .harga-satuan-realisasi-cell summary::-webkit-details-marker {
        display: none;
    }

    .harga-satuan-realisasi-cell .realization-summary {
        color: #5a5a5a;
        font-size: 11px;
        margin-top: 3px;
    }

    .harga-satuan-realisasi-cell .realization-details {
        margin-top: 8px;
        padding-left: 0;
        font-size: 11px;
        color: #333;
    }

    .harga-satuan-realisasi-cell .realization-details div {
        margin-bottom: 4px;
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
                        <th class="report-header-h3" colspan="16">RENCANA ANGGARAN KAS <br> SATUAN KERJA PERANGKAT DAERAH</th>
                    </tr>
                    <tr>
                        <th class="report-header-p" colspan="16">Provinsi Jawa Timur Tahun Anggaran <?= $tahun_anggaran; ?></th>
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
                                            <td colspan="14"><?= $urusan['nama_urusan']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Bidang Urusan</td>
                                            <td>:</td>
                                            <td colspan="14"><?= $bidang['nama_bidang_urusan']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Program</td>
                                            <td>:</td>
                                            <td colspan="14"><?= $program['nama_program']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Kegiatan</td>
                                            <td>:</td>
                                            <td colspan="14"><?= $kegiatan['nama_kegiatan']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Sub Kegiatan</td>
                                            <td>:</td>
                                            <td colspan="14"><?= $sub_kegiatan['nama_sub_kegiatan']; ?></td>
                                        </tr>

                                        <tr class="table-section">
                                            <td>Paket Belanja</td>
                                            <td>:</td>
                                            <td colspan="14"><?= $paket['nama_paket_belanja']; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Total Anggaran</td>
                                            <td>:</td>
                                            <td colspan="14">
                                                Rp. <?= az_thousand_separator_decimal($paket['nilai_anggaran']); ?>
                                            </td>
                                        </tr>

                                        <!-- Detail Akun Belanja -->
                                        <tr>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold;" rowspan="2" colspan="2">Kode Rekening</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:auto;" rowspan="2">Uraian</td>
                                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="3">Rincian Perhitungan</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;" rowspan="2">Jumlah</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:50px;" rowspan="2">Volume Realisasi</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:50px;" rowspan="2">Sisa Volume</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;" rowspan="2">Sisa Uang</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;" rowspan="2">Harga Satuan Realisasi</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;" rowspan="2">Harga Satuan Rata-rata Realisasi</td>
                                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:100px;" colspan="4">Realisasi Sampai</td>
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
                                                <td class="nominal">
                                                    Rp. <?= az_thousand_separator($akun['total_sisa_uang']); ?>
                                                </td>
                                                <td></td>
                                                <td></td>
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
                                                            <?= az_thousand_separator($detail['volume']); ?>
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

                                                        <td class="center">
                                                            <?= az_thousand_separator($detail['volume_realisasi']); ?>
                                                        </td>

                                                        <td class="center">
                                                            <?= az_thousand_separator($detail['sisa_volume']); ?>
                                                        </td>

                                                        <td class="nominal">
                                                            Rp. <?= az_thousand_separator($detail['sisa_uang']); ?>
                                                        </td>

                                                        <?php
                                                            $harga_satuan_realisasi = $detail['harga_satuan_realisasi'] ?? [];
                                                            $harga_satuan_realisasi_formatted = array_map(function ($value) {
                                                                return 'Rp. ' . az_thousand_separator($value);
                                                            }, $harga_satuan_realisasi);
                                                            $display_harga = array_slice($harga_satuan_realisasi_formatted, 0, 1);
                                                            $more_count = max(0, count($harga_satuan_realisasi_formatted) - count($display_harga));
                                                            $show_details = count($harga_satuan_realisasi_formatted) > 1;
                                                        ?>
                                                        <td class="nominal harga-satuan-realisasi-cell" <?= $show_details ? 'title="Klik untuk melihat detail harga satuan realisasi"' : ''; ?> >
                                                            <?php if (empty($harga_satuan_realisasi_formatted)): ?>
                                                                -
                                                            <?php elseif (!$show_details): ?>
                                                                <?= $harga_satuan_realisasi_formatted[0]; ?>
                                                            <?php else: ?>
                                                                <details>
                                                                    <summary>
                                                                        <?= $display_harga[0]; ?>
                                                                        <div class="realization-summary">(+<?= $more_count; ?> lainnya)</div>
                                                                    </summary>
                                                                    <div class="realization-details">
                                                                        <?php foreach ($harga_satuan_realisasi_formatted as $value): ?>
                                                                            <div><?= $value; ?></div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </details>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="nominal">
                                                            Rp. <?= az_thousand_separator($detail['harga_satuan_rata']); ?>
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
                                                        <td colspan="14" class="subkategori">
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
                                                                <?= az_thousand_separator($sub['volume']); ?>
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

                                                            <td class="center">
                                                                <?= az_thousand_separator($sub['volume_realisasi']); ?>
                                                            </td>

                                                            <td class="center">
                                                                <?= az_thousand_separator($sub['sisa_volume']); ?>
                                                            </td>

                                                            <td class="nominal">
                                                                Rp. <?= az_thousand_separator($sub['sisa_uang']); ?>
                                                            </td>

                                                            <?php
                                                                $harga_satuan_realisasi = $sub['harga_satuan_realisasi'] ?? [];
                                                                $harga_satuan_realisasi_formatted = array_map(function ($value) {
                                                                    return 'Rp. ' . az_thousand_separator($value);
                                                                }, $harga_satuan_realisasi);
                                                                $display_harga = array_slice($harga_satuan_realisasi_formatted, 0, 1);
                                                                $more_count = max(0, count($harga_satuan_realisasi_formatted) - count($display_harga));
                                                                $show_details = count($harga_satuan_realisasi_formatted) > 1;
                                                            ?>
                                                            <td class="nominal harga-satuan-realisasi-cell" <?= $show_details ? 'title="Klik untuk melihat detail harga satuan realisasi"' : ''; ?> >
                                                                <?php if (empty($harga_satuan_realisasi_formatted)): ?>
                                                                    -
                                                                <?php elseif (!$show_details): ?>
                                                                    <?= $harga_satuan_realisasi_formatted[0]; ?>
                                                                <?php else: ?>
                                                                    <details>
                                                                        <summary>
                                                                            <?= $display_harga[0]; ?>
                                                                            <div class="realization-summary">(+<?= $more_count; ?> lainnya)</div>
                                                                        </summary>
                                                                        <div class="realization-details">
                                                                            <?php foreach ($harga_satuan_realisasi_formatted as $value): ?>
                                                                                <div><?= $value; ?></div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </details>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="nominal">
                                                                Rp. <?= az_thousand_separator($sub['harga_satuan_rata']); ?>
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
                                                <td colspan="16"></td>
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