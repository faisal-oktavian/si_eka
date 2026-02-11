<?php
	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['contract_code'];?></td>
			<td><?php echo $value['purchase_plan_code'];?></td>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td><?php echo $value['nama_sub_kategori'];?></td>
			<td align="center"><?php echo az_thousand_separator_decimal($value['volume']);?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['unit_price']);?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['ppn']);?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['pph']);?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['total_realization_detail']);?></td>
			<td>
				<?php 
					// if (in_array($value['transaction_status'], array('DRAFT', 'MENUNGGU VERIFIKASI', 'DITOLAK VERIFIKATOR') ) ) { 
				?>
						<button class="btn btn-default btn-xs btn-edit-order" type="button" data-id="<?php echo $value['idbudget_realization_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
						<button class="btn btn-danger btn-xs btn-delete-order" type="button" data-id="<?php echo $value['idbudget_realization_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
					// }
				?>
			</td>
		</tr>
<?php
	}
?>

<tr style="font-weight: bold;">
	<td align="right" colspan="8">Total</td>
	<td align="right">Rp <?php echo az_thousand_separator($total_realization);?></td>
	<td></td>
</tr>