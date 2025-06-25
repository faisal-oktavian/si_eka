<?php
	foreach ((array) $data['detail'] as $key => $value) {
?>
		<tr>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td align="right"><?php echo $value['nama_uraian'];?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['volume']);?></td>
			<td align="center"><?php echo $value['harga_satuan'];?></td>
			<td align="right">Rp <?php echo $is_comma($value['total']);?></td>
			<td>
				<?php 
					if (in_array(azarr($data,'transaction_status'), array('DRAFT','INPUT DATA') ) ) { 
				?>
						<button class="btn btn-default btn-xs btn-edit-order" type="button" data-id="<?php echo $value['idtransaction_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
						<button class="btn btn-danger btn-xs btn-delete-order" type="button" data-id="<?php echo $value['idtransaction_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
					}
				?>
			</td>
		</tr>
<?php
	}
?>

<tr>
	<td align="right" <?php echo $colspan; ?> >Subtotal</td>
	<td align="right" colspan="4">Rp <?php echo az_thousand_separator($data['total_price']);?></td>
	<td></td>
</tr>
<tr>
	<td align="right" <?php echo $colspan; ?> >PPN</td>
	<td align="right" colspan="4">Rp <?php echo az_thousand_separator($data['total_tax']);?></td>
	<td></td>
</tr>
<tr>
	<td align="right" <?php echo $colspan; ?> >PPH</td>
	<td align="right" colspan="4">( Rp <?php echo az_thousand_separator($data['total_tax_pph']) ;?> )</td>
	<td></td>
</tr>
<tr>
	<td align="right" <?php echo $colspan; ?> >Total</td>
	<td align="right" colspan="4">Rp <?php echo az_thousand_separator($data['grand_total_price']);?></td>
	<td></td>
</tr>