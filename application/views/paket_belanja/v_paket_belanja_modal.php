<form class="form-horizontal" id="form_add_akun_belanja">
	<input type="hidden" id="idpaket_belanja" name="idpaket_belanja" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="is_edit" name="is_edit" class="x-hidden" value="0">
	<input type="hidden" id="idpb_akun_belanja" name="idpb_akun_belanja" class="x-hidden">
	
    <div class="form-group">
		<label class="control-label col-md-3">Akun Belanja</label>
		<div class="col-md-8">
			<?php echo az_select_nama_akun_belanja();?>
		</div>
	</div>
</form>
