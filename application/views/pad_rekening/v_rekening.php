<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idpad_rekening" id="idpad_rekening">
	<input type="hidden" name="is_copy" id="is_copy">
	<div class="form-group">
		<label class="control-label col-md-3">Kode Rekening <red>*</red></label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="kode_rekening" name="kode_rekening"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Uraian <red>*</red></label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="uraian" name="uraian"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Menu <red>*</red></label>
		<div class="col-md-6">
			<select id="menu" class=" menu" name="menu">
				<option value="" selected>-- Pilih Menu --</option>
				<option value="STS">STS</option>
				<option value="MUTASI_KAS">Mutasi Kas</option>
				<option value="STS_MUTASI_KAS">STS & Mutasi Kas</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Aktif</label>
		<div class="col-md-6">
			<select class="form-control" name="is_active" id="is_active">
				<option value="1">AKTIF</option>
				<option value="0">TIDAK AKTIF</option>
			</select>
		</div>
	</div>
</form>