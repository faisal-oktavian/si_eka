<?php
	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['transaction_code'];?></td>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td>
				<?php 
					if (in_array($value['verification_status'], array('DRAFT','MENUNGGU VERIFIKASI') ) ) {
				?>
						<button class="btn btn-default btn-xs btn-edit-paket-belanja" type="button" data-id="<?php echo $value['idverification_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
						<button class="btn btn-danger btn-xs btn-delete-paket-belanja" type="button" data-id="<?php echo $value['idverification_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
					}
				?>
			</td>
		</tr>
<?php
	}
?>