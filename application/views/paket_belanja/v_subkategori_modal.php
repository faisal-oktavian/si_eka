<form class="form-horizontal" id="form_add_subkategori">
	<input type="" id="hd_idpb_detail_sub" name="hd_idpb_detail_sub" class="x-hidden">
	<input type="" id="hds_idpaket_belanja" name="hds_idpaket_belanja" class="x-hidden" value="<?php echo $id;?>">
	<input type="" id="hds_idpaket_belanja_detail" name="hds_idpaket_belanja_detail" class="x-hidden">
	<input type="" id="hds_idakun_belanja" name="hds_idakun_belanja" class="x-hidden">
	<input type="" id="hds_is_kategori" name="hds_is_kategori" class="x-hidden">
	<input type="" id="hds_is_subkategori" name="hds_is_subkategori" class="x-hidden">
	<input type="" id="hds_is_edit" name="hds_is_edit" class="x-hidden" value="0">
	
    <div class="form-group">
		<label class="control-label col-md-3">Sub Kategori</label>
		<div class="col-md-8">
			<?php echo az_select_nama_subkategori();?>
		</div>
	</div>
</form>