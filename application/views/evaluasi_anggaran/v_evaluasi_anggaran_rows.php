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
                            <td colspan="9">Rp. <?= az_thousand_separator_decimal($paket['nilai_anggaran']); ?></td>
                        </tr>
                        <tr>
                            <td>Potensi Sisa</td>
                            <td>:</td>
                            <td colspan="9">Rp. <?= $paket['potensi_sisa']; ?></td>
                        </tr>
                        <tr>
                            <td>Persentase Target</td>
                            <td>:</td>
                            <td colspan="9"><?= az_thousand_separator_decimal($paket['total_persentase_target']); ?> %</td>
                        </tr>
                        <tr>
                            <td>Persentase Realisasi</td>
                            <td>:</td>
                            <td colspan="9"><?= az_thousand_separator_decimal($paket['total_persentase_realisasi']); ?> %</td>
                        </tr>

                        <tr>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold;" rowspan="2" colspan="2">Kode Rekening</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:auto;" rowspan="2">Uraian</td>
                            <td style="text-align:center; font-weight:bold; width:auto;" colspan="3">Rincian Perhitungan</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:130px;" rowspan="2">Jumlah</td>
                            <td style="text-align:center; vertical-align: middle; font-weight:bold; width:130px;" colspan="3">Sisa</td>
                            <td style="font-weight:bold; text-align:center; width:250px;" rowspan="2"></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold; text-align:center; width:60;">Volume</td>
                            <td style="font-weight:bold; text-align:center; width:60px;">Satuan</td>
                            <td style="font-weight:bold; text-align:center; width:100px;">Harga Satuan</td>

                            <td style="font-weight:bold; text-align:center; width:60;">Volume</td>
                            <td style="font-weight:bold; text-align:center; width:130px;">Anggaran (Rp)</td>
                            <td style="font-weight:bold; text-align:center; width:100px;">Anggaran (%)</td>
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
                                <td class="nominal">
                                    Rp. <?= az_thousand_separator($akun['total_sisa_anggaran']); ?>
                                </td>
                                <td class="nominal">
                                    <?= az_thousand_separator_decimal($akun['total_persentase_sisa']); ?>%
                                </td>
                                <td style="text-align: center;">
                                    <button class="btn btn-default btn-xs btn-view" data_idpaket_belanja_detail="<?= $akun['idpaket_belanja_detail']; ?>" data_tw="1"><span class="glyphicon glyphicon-eye-open"></span> TW1</button>
                                    <button class="btn btn-default btn-xs btn-view" data_idpaket_belanja_detail="<?= $akun['idpaket_belanja_detail']; ?>" data_tw="2"><span class="glyphicon glyphicon-eye-open"></span> TW2</button>
                                    <button class="btn btn-default btn-xs btn-view" data_idpaket_belanja_detail="<?= $akun['idpaket_belanja_detail']; ?>" data_tw="3"><span class="glyphicon glyphicon-eye-open"></span> TW3</button>
                                    <button class="btn btn-default btn-xs btn-view" data_idpaket_belanja_detail="<?= $akun['idpaket_belanja_detail']; ?>" data_tw="4"><span class="glyphicon glyphicon-eye-open"></span> TW4</button>
                                    <button class="btn btn-success btn-xs btn-history-rak" data_idpaket_belanja_detail="<?= $akun['idpaket_belanja_detail']; ?>"><span class="glyphicon glyphicon-eye-open"></span> History RAK</button>
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
                                            <?= az_thousand_separator($detail['sisa_volume']); ?>
                                        </td>

                                        <td colspan="3"></td>
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
                                                <?= az_thousand_separator($sub['sisa_volume']); ?>
                                            </td>

                                            <td colspan="3"></td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        <?php endforeach; ?>

                        <?php if (($key_paket != $last_index) || $key_paket == 0): ?>
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