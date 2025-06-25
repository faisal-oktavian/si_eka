<form class="form-horizontal" id="form_add_subkategori">
	<input type="hidden" id="hds_idpb_detail_sub" name="hds_idpb_detail_sub" class="x-hidden">
	<input type="hidden" id="hds_idpaket_belanja_detail" name="hds_idpaket_belanja_detail" class="x-hidden">
	<input type="hidden" id="hds_is_kategori" name="hds_is_kategori" class="x-hidden">
	<input type="hidden" id="hds_is_subkategori" name="hds_is_subkategori" class="x-hidden">
	<input type="hidden" id="hds_idds_parent" name="hds_idds_parent" class="x-hidden">
	<input type="hidden" id="hds_is_edit" name="hds_is_edit" class="x-hidden" value="0">
	
    <div class="form-group">
		<label class="control-label col-md-3">Sub Kategori <red>*</red></label>
		<div class="col-md-8">
			<?php echo az_select_nama_subkategori();?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">Volume <red>*</red></label>
		<div class="col-md-8">
			<input type="text" class="form-control format-number volume" name="volume" id="volume" placeholder="Volume">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Satuan <red>*</red></label>
		<div class="col-md-8">
			<?php echo az_select_nama_satuan();?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Harga Satuan <red>*</red></label>
		<div class="col-md-8">
			<div class="input-group">
				<span class="input-group-addon">Rp. </span>
				<input type="text" class="form-control format-number txt-right harga-satuan" id="harga_satuan" name="harga_satuan"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Jumlah</label>
		<div class="col-md-8">
			<div class="input-group">
				<span class="input-group-addon">Rp. </span>
				<input type="text" class="form-control format-number txt-right" id="jumlah" name="jumlah" readonly/>
			</div>
		</div>
	</div>
</form>