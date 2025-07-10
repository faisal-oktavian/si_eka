<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idkategori" id="idkategori">
	<input type="hidden" name="is_copy" id="is_copy">
	<div class="form-group">
		<label class="control-label col-md-4">Nama Kategori <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="nama_kategori" name="nama_kategori"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Aktif</label>
		<div class="col-md-5">
			<select class="form-control" name="is_active" id="is_active">
				<option value="1">AKTIF</option>
				<option value="0">TIDAK AKTIF</option>
			</select>
		</div>
	</div>
</form>