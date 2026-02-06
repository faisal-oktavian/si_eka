<?php
    $role_view_paket_belanja = false; // hanya lihat data
    $role_select_ppkom_pptk = false; // pilih ppk / pp
    $role_specification = false; // Bisa Isi Spesifikasi
    $role_special_paket_belanja = false; // Bisa Pilih PPK / PP, Isi Spesifikasi

    if (aznav('role_view_paket_belanja')) {
        $role_view_paket_belanja = true;
    }
    if (aznav('role_select_ppkom_pptk')) {
        $role_select_ppkom_pptk = true;
    }
    if (aznav('role_specification')) {
        $role_specification = true;
    }
    if (aznav('role_special_paket_belanja')) {
        $role_special_paket_belanja = true;
    }

	foreach ((array) $arr_pb_detail as $key => $value) {
?>
        <tr>
            <td>
                <div style="font-weight: bold;">
                    <?php echo $value['nama_akun_belanja'];?>
                </div>

                <?php 
                    $btn_add_kat_subkat = false;

                    if ($role_view_paket_belanja) {
                        // jika hanya lihat data saja
                        $btn_add_kat_subkat = false;
                    }
                    else if ($role_select_ppkom_pptk) {
                        // jika bisa pilih ppk / pp
                        $btn_add_kat_subkat = false;
                    }
                    else if ($role_specification) {
                        // jika hanya bisa isi spesifikasi
                        $btn_add_kat_subkat = false;
                    }
                    else if ($role_special_paket_belanja) {
                        // jika bisa pilih ppk / pp dan isi spesifikasi
                        $btn_add_kat_subkat = true;
                    }
                    else {
                        // jika bisa buka akses semuanya
                        $btn_add_kat_subkat = true;
                    }

                    if ($btn_add_kat_subkat) {
                ?>
                        <button class="btn btn-default btn-xs btn-add-kategori" type="button" data-idpaket-belanja="<?php echo $value['idpaket_belanja'];?>" data-idpb-detail="<?php echo $value['idpaket_belanja_detail'];?>" data-idakun-belanja="<?php echo $value['idakun_belanja'];?>" data-is-kategori="1" data-is-subkategori="0"><i class="fa fa-plus"></i> Tambah Kategori</button>
                        <button class="btn btn-default btn-xs btn-add-sub-kategori" type="button" data-idpaket-belanja="<?php echo $value['idpaket_belanja'];?>" data-idpb-detail="<?php echo $value['idpaket_belanja_detail'];?>" data-idakun-belanja="<?php echo $value['idakun_belanja'];?>" data-is-kategori="0" data-is-subkategori="1"><i class="fa fa-plus"></i> Tambah Sub Kategori</button>
                <?php
                    }
                ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center;">
                <?php 
                    $btn_edit_delete_akun = false;

                    if ($role_view_paket_belanja) {
                        // jika hanya lihat data saja
                        $btn_edit_delete_akun = false;
                    }
                    else if ($role_select_ppkom_pptk) {
                        // jika bisa pilih ppk / pp
                        $btn_edit_delete_akun = false;
                    }
                    else if ($role_specification) {
                        // jika hanya bisa isi spesifikasi
                        $btn_edit_delete_akun = false;
                    }
                    else if ($role_special_paket_belanja) {
                        // jika bisa pilih ppk / pp dan isi spesifikasi
                        $btn_edit_delete_akun = true;
                    }
                    else {
                        // jika bisa buka akses semuanya
                        $btn_edit_delete_akun = true;
                    }

                    if ($btn_edit_delete_akun) {
                ?>
                        <button class="btn btn-default btn-xs btn-edit-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                        <button class="btn btn-danger btn-xs btn-delete-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
                <?php
                    }
                ?>
            </td>
        </tr>

        <?php
            foreach ($value['arr_pb_detail_sub'] as $sub_key => $sub_value) {
                if ($sub_value['is_subkategori'] == 1) {
        ?>
                    <tr>
                        <td>
                            <div style="padding-left: 50px;">
                                <?php echo $sub_value['nama_subkategori'];?>
                                <br>
                                <?php echo $sub_value['kode_rekening'];?>
                                <?php 
                                    if (!empty($sub_value['spesifikasi'])) { 
                                ?>
                                        <div style="font-style: italic; color: #555; padding: 2px 0; line-height: 1.2; margin-top: 10px;">
                                            <?php echo "<b>Spesifikasi:</b>"; ?>
                                            <div style="padding-left: 20px;">
                                                <?php echo ($sub_value['spesifikasi']); ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </td>
                        <td align="center"><?php echo $sub_value['volume'];?></td>
                        <td align="center"><?php echo $sub_value['nama_satuan'];?></td>
                        <td align="right">Rp. <?php echo az_thousand_separator($sub_value['harga_satuan']);?></td>
                        <td align="right">Rp. <?php echo az_thousand_separator($sub_value['jumlah']);?></td>
                        <td style="text-align: center;">
                            <?php 
                                $btn_subkategori = false;
                                $btn_add_spec = false;

                                if ($role_view_paket_belanja) {
                                    // jika hanya lihat data saja
                                    $btn_subkategori = false;
                                    $btn_add_spec = false;
                                }
                                else if ($role_select_ppkom_pptk) {
                                    // jika bisa pilih ppk / pp
                                    $btn_subkategori = false;
                                    $btn_add_spec = false;
                                }
                                else if ($role_specification) {
                                    // jika hanya bisa isi spesifikasi
                                    $btn_subkategori = false;
                                    $btn_add_spec = true;
                                }
                                else if ($role_special_paket_belanja) {
                                    // jika bisa pilih ppk / pp dan isi spesifikasi
                                    $btn_subkategori = true;
                                    $btn_add_spec = true;
                                }
                                else {
                                    // jika bisa buka akses semuanya
                                    $btn_subkategori = true;
                                    $btn_add_spec = false;
                                }

                                if ($btn_subkategori) {
                            ?>
                                    <button class="btn btn-default btn-xs btn-edit-detail" type="button" data-id="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                                    <button class="btn btn-danger btn-xs btn-delete-detail" type="button" data-id="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-times"></i> Hapus</button>
                                    <button class="btn btn-info btn-xs copy-detail btn-edit-detail" type="button" data-id="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-file"></i> Copy</button>
                            <?php
                                }
                                if ($btn_add_spec) {
                            ?>
                                    <button class="btn btn-success btn-xs btn-specification" type="button" data-id="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-pencil-alt"></i> Isi Spesifikasi</button>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
        <?php
                }
                else if ($sub_value['is_kategori'] == 1) {
        ?>
                    <tr>
                        <td>
                            <div style="padding-left: 50px; font-weight:bold;">
                                <div>
                                    <?php echo $sub_value['nama_kategori'];?>
                                </div>

                                <?php 
                                    $btn_add_subkat = false;

                                    if ($role_view_paket_belanja) {
                                        // jika hanya lihat data saja
                                        $btn_add_subkat = false;
                                    }
                                    else if ($role_select_ppkom_pptk) {
                                        // jika bisa pilih ppk / pp
                                        $btn_add_subkat = false;
                                    }
                                    else if ($role_specification) {
                                        // jika hanya bisa isi spesifikasi
                                        $btn_add_subkat = false;
                                    }
                                    else if ($role_special_paket_belanja) {
                                        // jika bisa pilih ppk / pp dan isi spesifikasi
                                        $btn_add_subkat = true;
                                    }
                                    else {
                                        // jika bisa buka akses semuanya
                                        $btn_add_subkat = true;
                                    }

                                    if ($btn_add_subkat) {
                                ?>
                                        <button class="btn btn-default btn-xs btn-add-sub-kategori" type="button" 
                                        data-idpaket-belanja="<?php echo $value['idpaket_belanja'];?>" 
                                        data-idpb-detail="<?php echo $value['idpaket_belanja_detail'];?>" 
                                        data-idakun-belanja="<?php echo $value['idakun_belanja'];?>" 
                                        data-idds_parent="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>" 
                                        data-is-kategori="0" data-is-subkategori="1">
                                        <i class="fa fa-plus"></i> Tambah Sub Kategori</button>
                                <?php
                                    }
                                ?>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center;">
                            <?php
                                $btn_edit_delet_subkat = false;

                                if ($role_view_paket_belanja) {
                                    // jika hanya lihat data saja
                                    $btn_edit_delet_subkat = false;
                                }
                                else if ($role_select_ppkom_pptk) {
                                    // jika bisa pilih ppk / pp
                                    $btn_edit_delet_subkat = false;
                                }
                                else if ($role_specification) {
                                    // jika hanya bisa isi spesifikasi
                                    $btn_edit_delet_subkat = false;
                                }
                                else if ($role_special_paket_belanja) {
                                    // jika bisa pilih ppk / pp dan isi spesifikasi
                                    $btn_edit_delet_subkat = true;
                                }
                                else {
                                    // jika bisa buka akses semuanya
                                    $btn_edit_delet_subkat = true;
                                }

                                if ($btn_edit_delet_subkat) {
                            ?>
                                    <button class="btn btn-default btn-xs btn-edit-detail" type="button" data-id="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                                    <button class="btn btn-danger btn-xs btn-delete-detail" type="button" data-id="<?php echo $sub_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-times"></i> Hapus</button>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
        <?php
                    foreach ($sub_value['arr_pd_detail_sub_sub'] as $ss_key => $ss_value) {
        ?>
                        <tr>
                            <td>
                                <div style="padding-left: 80px;">
                                    <?php echo $ss_value['nama_subkategori'];?>
                                    <br>
                                    <?php echo $ss_value['kode_rekening'];?>
                                    <?php 
                                        if (!empty($ss_value['spesifikasi'])) {
                                    ?>
                                            <div style="font-style: italic; color: #555; padding: 2px 0; line-height: 1.2; margin-top: 10px;">
                                                <?php echo "<b>Spesifikasi:</b>"; ?>
                                                <div style="padding-left: 20px;">
                                                    <?php echo nl2br($ss_value['spesifikasi']); ?>
                                                </div>
                                            </div>
                                    <?php
                                        } 
                                    ?>
                                </div>
                            </td>
                            <td align="center"><?php echo $ss_value['volume'];?></td>
                            <td align="center"><?php echo $ss_value['nama_satuan'];?></td>
                            <td align="right">Rp. <?php echo az_thousand_separator($ss_value['harga_satuan']);?></td>
                            <td align="right">Rp. <?php echo az_thousand_separator($ss_value['jumlah']);?></td>
                            <td style="text-align: center;">
                                <?php 
                                    $btn_subkategori = false;
                                    $btn_add_spec = false;

                                    if ($role_view_paket_belanja) {
                                        // jika hanya lihat data saja
                                        $btn_subkategori = false;
                                        $btn_add_spec = false;
                                    }
                                    else if ($role_select_ppkom_pptk) {
                                        // jika bisa pilih ppk / pp
                                        $btn_subkategori = false;
                                        $btn_add_spec = false;
                                    }
                                    else if ($role_specification) {
                                        // jika hanya bisa isi spesifikasi
                                        $btn_subkategori = false;
                                        $btn_add_spec = true;
                                    }
                                    else if ($role_special_paket_belanja) {
                                        // jika bisa pilih ppk / pp dan isi spesifikasi
                                        $btn_subkategori = true;
                                        $btn_add_spec = true;
                                    }
                                    else {
                                        // jika bisa buka akses semuanya
                                        $btn_subkategori = true;
                                        $btn_add_spec = false;
                                    }

                                    if ($btn_subkategori) {
                                ?>
                                        <button class="btn btn-default btn-xs btn-edit-detail" type="button" data-id="<?php echo $ss_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                                        <button class="btn btn-danger btn-xs btn-delete-detail" type="button" data-id="<?php echo $ss_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-times"></i> Hapus</button>
                                        <button class="btn btn-info btn-xs copy-detail btn-edit-detail" type="button" data-id="<?php echo $ss_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-file"></i> Copy</button>
                                <?php
                                    }
                                    if ($btn_add_spec) {
                                ?>
                                        <button class="btn btn-success btn-xs btn-specification" type="button" data-id="<?php echo $ss_value['idpaket_belanja_detail_sub'];?>"><i class="fa fa-pencil-alt"></i> Isi Spesifikasi</button>
                                <?php
                                    }
                                ?>
                            </td>
                        </tr>
        <?php
                    }
                }
            }
        ?>
<?php
    }
?>