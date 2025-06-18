<?php
	foreach ((array) $arr_pb_detail as $key => $value) {
?>
        <tr>
            <td><?php echo $value['nama_akun_belanja'];?></td>
            <td style="text-align: center;">
                <button class="btn btn-default btn-xs btn-edit-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
                <button class="btn btn-danger btn-xs btn-delete-akun-belanja" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
                
                <?php
                    if ($value['status_paket_belanja'] == "OK") {
                ?>
                        <button class="btn btn-default btn-xs btn-add-kategori" type="button" data-id="<?php echo $value['idpaket_belanja_detail'];?>"><i class="fa fa-pencil-alt"></i> Detail</button>
                <?php
                    }
                ?>
            </td>
        </tr>
<?php
    }
?>