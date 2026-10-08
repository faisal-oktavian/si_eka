<?php foreach ($arr_data['urusan'] as $urusan): ?>
    <?php foreach ($urusan['bidang_urusan'] as $bidang): ?>
        <?php foreach ($bidang['program'] as $program): ?>
            <?php foreach ($program['kegiatan'] as $kegiatan): ?>
                <?php foreach ($kegiatan['sub_kegiatan'] as $sub_kegiatan): ?>

                    <?php $last_index = count($sub_kegiatan['paket_belanja']) - 1; ?>

                    <?php foreach ($sub_kegiatan['paket_belanja'] as $key_paket => $paket): ?>

                        <tr>
                            <td width="140">Urusan</td>
                            <td width="10">:</td>
                            <td colspan="10"><?= $urusan['nama_urusan']; ?></td>
                        </tr>

                        <tr>
                            <td>Bidang Urusan</td>
                            <td>:</td>
                            <td colspan="10"><?= $bidang['nama_bidang_urusan']; ?></td>
                        </tr>

                        <tr>
                            <td>Program</td>
                            <td>:</td>
                            <td colspan="10"><?= $program['nama_program']; ?></td>
                        </tr>

                        <tr>
                            <td>Kegiatan</td>
                            <td>:</td>
                            <td colspan="10"><?= $kegiatan['nama_kegiatan']; ?></td>
                        </tr>

                        <tr>
                            <td>Sub Kegiatan</td>
                            <td>:</td>
                            <td colspan="10"><?= $sub_kegiatan['nama_sub_kegiatan']; ?></td>
                        </tr>

                        <tr class="table-section">
                            <td>Paket Belanja</td>
                            <td>:</td>
                            <td colspan="10"><?= $paket['nama_paket_belanja']; ?></td>
                        </tr>

                        <tr>
                            <td>Total Anggaran APBD</td>
                            <td>:</td>
                            <td colspan="10">Rp. <?= az_thousand_separator_decimal($paket['nilai_anggaran_apbd']); ?></td>
                        </tr>
                        <tr>
                            <td>Total Anggaran PAPBD</td>
                            <td>:</td>
                            <td colspan="10">Rp. <?= az_thousand_separator_decimal($paket['nilai_anggaran_papbd']); ?></td>
                        </tr>
                        <tr>
                            <td>Selisih Anggaran</td>
                            <td>:</td>
                            <td colspan="10">
                                <?php if ($paket['selisih_nilai_anggaran'] < 0): ?>
                                    <span style="color: red; font-weight:bold;">
                                <?php endif; ?>
                                <?php if ($paket['selisih_nilai_anggaran'] > 0): ?>
                                    <span style="color: green; font-weight:bold;">
                                <?php endif; ?>
                                <?php if ($paket['selisih_nilai_anggaran'] == 0): ?>
                                    <span style="">
                                <?php endif; ?>
                                        Rp. <?= az_thousand_separator_decimal($paket['selisih_nilai_anggaran']); ?>
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Potensi Sisa</td>
                            <td>:</td>
                            <td colspan="10">Rp. <?= $paket['potensi_sisa']; ?></td>
                        </tr>
                        <tr>
                            <td>Persentase Target</td>
                            <td>:</td>
                            <td colspan="10"><?= az_thousand_separator_decimal($paket['total_persentase_target']); ?> %</td>
                        </tr>
                        <tr>
                            <td>Persentase Realisasi</td>
                            <td>:</td>
                            <td colspan="10"><?= az_thousand_separator_decimal($paket['total_persentase_realisasi']); ?> %</td>
                        </tr>
                        <tr>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold;" rowspan="3" colspan="2">Kode Rekening</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:auto;" rowspan="3">Uraian</td>
                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="4">APBD</td>
                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="4">PAPBD</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold;" rowspan="3">Bertambah / Berkurang</td>
                        </tr>
                        <tr>
                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="3">Rincian Perhitungan</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:130px;" rowspan="2">Jumlah</td>
                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="3">Rincian Perhitungan</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:130px;" rowspan="2">Jumlah</td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold; text-align:center; width:60;">Volume</td>
                            <td style="font-weight:bold; text-align:center; width:60px;">Satuan</td>
                            <td style="font-weight:bold; text-align:center; width:100px;">Harga Satuan</td>

                            <td style="font-weight:bold; text-align:center; width:60;">Volume</td>
                            <td style="font-weight:bold; text-align:center; width:60px;">Satuan</td>
                            <td style="font-weight:bold; text-align:center; width:100px;">Harga Satuan</td>
                        </tr>

                        <?php foreach ($paket['akun_belanja'] as $akun): ?>

                            <tr class="akun-header">
                                <td colspan="2">
                                    <?= $akun['no_rekening_akunbelanja']; ?>
                                </td>
                                <td colspan="4">
                                    <?= $akun['nama_akun_belanja']; ?>
                                </td>

                                <!-- APBD -->
                                <td class="nominal">
                                    Rp. <?= az_thousand_separator($akun['total_jumlah_apbd']); ?>
                                </td>
                                <td colspan="3"></td>

                                <!-- PAPBD -->
                                <td class="nominal">
                                    Rp. <?= az_thousand_separator($akun['total_jumlah_papbd']); ?>
                                </td>

                                <!-- SELISIH -->
                                <td class="nominal">
                                    <?php if ($akun['selisih_jumlah_akun_belanja'] < 0): ?>
                                        <span style="color: red; font-weight:bold;">
                                    <?php endif; ?>
                                    <?php if ($akun['selisih_jumlah_akun_belanja'] > 0): ?>
                                        <span style="color: green; font-weight:bold;">
                                    <?php endif; ?>
                                    <?php if ($akun['selisih_jumlah_akun_belanja'] == 0): ?>
                                        <span style="">
                                    <?php endif; ?>
                                            Rp. <?= az_thousand_separator_decimal($akun['selisih_jumlah_akun_belanja']); ?>
                                        </span>
                                </td>
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

                                        <!-- APBD -->
                                        <td class="center">
                                            <?= az_thousand_separator($detail['apbd_volume']); ?>
                                        </td>
                                        <td class="center">
                                            <?= $detail['apbd_nama_satuan']; ?>
                                        </td>
                                        <td class="nominal">
                                            Rp. <?= az_thousand_separator($detail['apbd_harga_satuan']); ?>
                                        </td>
                                        <td class="nominal">
                                            Rp. <?= az_thousand_separator($detail['apbd_jumlah']); ?>
                                        </td>

                                        <!-- PAPBD -->
                                        <td class="center">
                                            <?= az_thousand_separator($detail['papbd_volume']); ?>
                                        </td>
                                        <td class="center">
                                            <?= $detail['papbd_nama_satuan']; ?>
                                        </td>
                                        <td class="nominal">
                                            Rp. <?= az_thousand_separator($detail['papbd_harga_satuan']); ?>
                                        </td>
                                        <td class="nominal">
                                            Rp. <?= az_thousand_separator($detail['papbd_jumlah']); ?>
                                        </td>

                                        <!-- SELISIH -->
                                        <td class="nominal">
                                            <?php if ($detail['selisih_jumlah_sub'] < 0): ?>
                                                <span style="color: red; font-weight:bold;">
                                            <?php endif; ?>
                                            <?php if ($detail['selisih_jumlah_sub'] > 0): ?>
                                                <span style="color: green; font-weight:bold;">
                                            <?php endif; ?>
                                            <?php if ($detail['selisih_jumlah_sub'] == 0): ?>
                                                <span style="">
                                            <?php endif; ?>
                                                    Rp. <?= az_thousand_separator_decimal($detail['selisih_jumlah_sub']); ?>
                                                </span>
                                        </td>
                                    </tr>

                                <?php endif; ?>

                                <?php if ($detail['is_kategori'] == 1): ?>

                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="10" class="subkategori">
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

                                            <!-- APBD -->
                                            <td class="center">
                                                <?= az_thousand_separator($sub['apbd_volume']); ?>
                                            </td>
                                            <td class="center">
                                                <?= $sub['apbd_nama_satuan']; ?>
                                            </td>
                                            <td class="nominal">
                                                Rp. <?= az_thousand_separator($sub['apbd_harga_satuan']); ?>
                                            </td>
                                            <td class="nominal">
                                                Rp. <?= az_thousand_separator($sub['apbd_jumlah']); ?>
                                            </td>

                                            <!-- PAPBD -->
                                            <td class="center">
                                                <?= az_thousand_separator($sub['papbd_volume']); ?>
                                            </td>
                                            <td class="center">
                                                <?= $sub['papbd_nama_satuan']; ?>
                                            </td>
                                            <td class="nominal">
                                                Rp. <?= az_thousand_separator($sub['papbd_harga_satuan']); ?>
                                            </td>
                                            <td class="nominal">
                                                Rp. <?= az_thousand_separator($sub['papbd_jumlah']); ?>
                                            </td>

                                            <!-- SELISIH -->
                                            <td class="nominal">
                                                <?php if ($sub['selisih_jumlah_sub'] < 0): ?>
                                                    <span style="color: red; font-weight:bold;">
                                                <?php endif; ?>
                                                <?php if ($sub['selisih_jumlah_sub'] > 0): ?>
                                                    <span style="color: green; font-weight:bold;">
                                                <?php endif; ?>
                                                <?php if ($sub['selisih_jumlah_sub'] == 0): ?>
                                                    <span style="">
                                                <?php endif; ?>
                                                        Rp. <?= az_thousand_separator_decimal($sub['selisih_jumlah_sub']); ?>
                                                    </span>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        <?php endforeach; ?>

                        <?php if (($key_paket != $last_index) || $key_paket == 0): ?>
                            <tr class="separator">
                                <td colspan="12"></td>
                            </tr>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endforeach; ?>