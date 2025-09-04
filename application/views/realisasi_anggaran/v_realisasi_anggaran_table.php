<?php
	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td><?php echo $value['nama_uraian'];?></td>
			<td align="center"><?php echo az_thousand_separator($value['volume']);?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['harga_satuan']);?></td>
			<td align="right">Rp <?php echo az_thousand_separator($value['total']);?></td>
			<td>
				<?php 
					// if (in_array($value['transaction_status'], array('DRAFT','INPUT DATA', 'DITOLAK VERIFIKATOR') ) ) { 
					if (in_array($value['transaction_status'], array('DRAFT', 'MENUNGGU VERIFIKASI', 'DITOLAK VERIFIKATOR') ) ) { 
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

<tr style="font-weight: bold;">
	<td align="right" colspan="4">Total</td>
	<td align="right">Rp <?php echo az_thousand_separator($total_realisasi);?></td>
	<td></td>
</tr>