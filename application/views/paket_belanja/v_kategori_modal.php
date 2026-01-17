<form class="form-horizontal" id="form_add_kategori">
	<input type="hidden" id="hd_idpb_detail_sub" name="hd_idpb_detail_sub" class="x-hidden">
	<input type="hidden" id="hd_idpaket_belanja" name="hd_idpaket_belanja" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="hd_idpaket_belanja_detail" name="hd_idpaket_belanja_detail" class="x-hidden">
	<input type="hidden" id="hd_idakun_belanja" name="hd_idakun_belanja" class="x-hidden">
	<input type="hidden" id="is_kategori" name="is_kategori" class="x-hidden">
	<input type="hidden" id="is_subkategori" name="is_subkategori" class="x-hidden">
	<input type="hidden" id="is_edit" name="is_edit" class="x-hidden" value="0">
	
    <div class="form-group">
		<label class="control-label col-md-3">Kategori</label>
		<div class="col-md-8">
			<table width="100%">
                <tbody>
					<tr>
                        <td class="sub-kategori">
							<?php echo az_select_nama_kategori();?>
						</td>
                        <td width="10px" style="padding-left:5px;">
							<button class="btn btn-default btn-new-kategori" type="button"><i class="fa fa-plus"></i></button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</form>