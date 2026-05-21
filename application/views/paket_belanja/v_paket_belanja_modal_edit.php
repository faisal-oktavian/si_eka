<form class="form-horizontal" id="form_edit_akun_belanja">
	<input type="hidden" id="idpaket_belanja" name="idpaket_belanja" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idpaket_belanja_detail_sub" name="idpaket_belanja_detail_sub" class="x-hidden">
	
    <div class="form-group">
		<label class="control-label col-md-3">Akun Belanja</label>
		<div class="col-md-8">
			<table width="100%">
				<tbody>
					<tr>
						<td class="sub-kategori">
							<?php echo az_select_nama_akun_belanja('edit_idakun_belanja');?>
						</td>
                        <td width="10px" style="padding-left:5px;">
							<button class="btn btn-default btn-new-akunbelanja" type="button"><i class="fa fa-plus"></i></button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>
