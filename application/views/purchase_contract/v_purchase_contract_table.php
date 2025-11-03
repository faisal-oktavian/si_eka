<?php
	foreach ((array) $arr_data as $key => $value) {
?>
		<tr>
			<td><?php echo $value['purchase_plan_code'];?></td>
            <td>    
                <table class='table table-bordered table-condensed' id='table_dokumen'>
                    <thead>
                        <tr>
                            <th width="320px">Nama Paket Belanja</th>
			                <th width="200px">Uraian</th>
			                <th width="80px">Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                    foreach ($value['arr_detail'] as $key => $dvalue) {
                ?>
                        <tr>
                            <td><?php echo $dvalue['nama_paket_belanja'];?></td>
                            <td><?php echo $dvalue['nama_sub_kategori'];?></td>
                            <td align="center"><?php echo az_thousand_separator($dvalue['volume']);?></td>
                        </tr>
                <?php
                    }
                ?>
                    </tbody>
                </table>
            </td>
			<td>
				<?php 
					// if (in_array($value['npd_status'], array('DRAFT', 'INPUT NPD') ) ) {
				?>
						<button class="btn btn-default btn-xs btn-edit-dokumen" type="button" data-id="<?php echo $value['idcontract_detail'];?>"><i class="fa fa-pencil-alt"></i> Edit</button>
						<button class="btn btn-danger btn-xs btn-delete-dokumen" type="button" data-id="<?php echo $value['idcontract_detail'];?>"><i class="fa fa-times"></i> Hapus</button>
				<?php
					// }
				?>
			</td>
		</tr>
<?php
	}
?>