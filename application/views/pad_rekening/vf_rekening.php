<form class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-2">Uraian</label>
		<div class="col-md-4 col-sm-6">
			<input type="text" class="form-control" name="vf_uraian" id="vf_uraian" placeholder="Uraian">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Menu</label>
		<div class="col-md-4 col-sm-6">
			<select class="form-control" name="vf_menu" id="vf_menu">
				<option value="">Semua</option>
				<option value="STS">STS</option>
				<option value="MUTASI_KAS">Mutasi Kas</option>
				<option value="STS_MUTASI_KAS">STS & Mutasi Kas</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Status</label>
		<div class="col-md-4 col-sm-6">
			<select class="form-control" name="vf_is_active" id="vf_is_active">
				<option value="">Semua</option>
				<option value="1">Aktif</option>
				<option value="0">Non Aktif</option>
			</select>
		</div>
	</div>
</form>