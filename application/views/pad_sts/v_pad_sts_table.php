<?php
	$role_view_pad_sts = false; // hanya lihat data

    if (aznav('role_view_pad_sts')) {
        $role_view_pad_sts = true;
    }

	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['sub_kegiatan'];?></td>
			<td><?php echo $value['kode_rekening'];?></td>
			<td><?php echo $value['uraian'];?></td>
			<td align="right"><?php echo az_thousand_separator_decimal($value['direct_receipt']);?></td>
			<td align="right"><?php echo az_thousand_separator_decimal($value['down_payment']);?></td>
			<td align="right"><?php echo az_thousand_separator_decimal($value['debt']);?></td>
			<td align="right"><?php echo az_thousand_separator_decimal($value['total_detail']);?></td>
			<td><?php echo $value['description_detail'];?></td>
			<td>
				<?php 
					if (in_array($value['pad_sts_status'], array('DRAFT', 'OK')) ) { 
						if (!$role_view_pad_sts) {
				?>
							<button class="btn btn-default btn-xs btn-edit-detail" type="button" data-id="<?php echo $value['idpad_sts_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
							<button class="btn btn-danger btn-xs btn-delete-detail" type="button" data-id="<?php echo $value['idpad_sts_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
						}
					}
				?>
			</td>
		</tr>
<?php
	}
?>