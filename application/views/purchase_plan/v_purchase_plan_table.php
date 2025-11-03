<?php
	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td><?php echo $value['nama_sub_kategori'];?></td>
			<td align="center"><?php echo az_thousand_separator($value['volume']);?></td>
			<td>
				<?php 
					if (in_array($value['purchase_plan_status'], array('DRAFT', 'PROSES PENGADAAN') ) ) { 
				?>
						<button class="btn btn-default btn-xs btn-edit-order" type="button" data-id="<?php echo $value['idpurchase_plan_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
						<button class="btn btn-danger btn-xs btn-delete-order" type="button" data-id="<?php echo $value['idpurchase_plan_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
					}
				?>
			</td>
		</tr>
<?php
	}
?>