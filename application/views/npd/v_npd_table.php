<?php
	foreach ((array) $detail as $key => $value) {
?>
		<tr>
			<td><?php echo $value['verification_code'];?></td>
			<td><?php echo $value['nama_paket_belanja'];?></td>
			<td>
				<?php 
					if (in_array($value['npd_status'], array('DRAFT', 'INPUT DATA') ) ) {
				?>
						<button class="btn btn-default btn-xs btn-edit-dokumen" type="button" data-id="<?php echo $value['idnpd_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
						<button class="btn btn-danger btn-xs btn-delete-dokumen" type="button" data-id="<?php echo $value['idnpd_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
					}
				?>
			</td>
		</tr>
<?php
	}
?>