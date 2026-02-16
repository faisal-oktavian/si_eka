<?php
	$role_view_purchase_plan = false; // hanya lihat data

    if (aznav('role_view_purchase_plan')) {
        $role_view_purchase_plan = true;
    }

	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td><?php echo $value['nama_sub_kategori'];?></td>
			<td align="center"><?php echo az_thousand_separator_decimal($value['volume']);?></td>
			<td>
				<?php 
					if (in_array($value['purchase_plan_status'], array('DRAFT', 'PROSES PENGADAAN') ) ) { 
						if (!$role_view_purchase_plan) {
				?>
							<button class="btn btn-default btn-xs btn-edit-order" type="button" data-id="<?php echo $value['idpurchase_plan_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
							<button class="btn btn-danger btn-xs btn-delete-order" type="button" data-id="<?php echo $value['idpurchase_plan_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
						}
					}
				?>
			</td>
		</tr>
<?php
	}
?>