<?php
	foreach ((array) $arr_pb_detail as $key => $value) {
?>
        <tr>
            <td>
                <div style="font-weight: bold;">
                    <?php echo $value['nama_akun_belanja'];?>
                </div>

                <button class="btn btn-default btn-xs btn-add-kategori" type="button" data-idpaket-belanja="<?php echo $value['idpaket_belanja'];?>" data-idpb-detail="<?php echo $value['idpaket_belanja_detail'];?>" data-idakun-belanja="<?php echo $value['idakun_belanja'];?>" data-is-kategori="1" data-is-subkategori="0"><i class="fa fa-plus"></i> Tambah Kategori</button>

                <button class="btn btn-default btn-xs btn-add-sub-kategori" type="button" data-idpaket-belanja="<?php echo $value['idpaket_belanja'];?>" data-idpb-detail="<?php echo $value['idpaket_belanja_detail'];?>" data-idakun-belanja="<?php echo $value['idakun_belanja'];?>" data-is-kategori="0" data-is-subkategori="1"><i class="fa fa-plus"></i> Tambah Sub Kategori</button>
            </td>
            <td style="text-align: center;">
                <button class="btn btn-default btn-xs btn-edit-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                <button class="btn btn-danger btn-xs btn-delete-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
                
                <?php
                    if ($value['status_paket_belanja'] == "OK") {
                ?>
                        <!-- <button class="btn btn-default btn-xs btn-add-kategori" type="button" data-id="<?php // echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-file"></i> Detail</button> -->
                <?php
                    }
                ?>
            </td>
        </tr>

        <?php
            foreach ($value['arr_pb_detail_sub'] as $sub_key => $sub_value) {
        ?>
                <tr>
                    <td>
                        <div style="padding-left: 50px;">
                            <?php echo $sub_value['no_rekening_akunbelanja'].'.'.$sub_value['no_rekening_subkategori'].' - '.$sub_value['nama_subkategori'];?>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-default btn-xs btn-edit-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                        <button class="btn btn-danger btn-xs btn-delete-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
                    </td>
                </tr>
        <?php
            }
        ?>
<?php
    }
?>