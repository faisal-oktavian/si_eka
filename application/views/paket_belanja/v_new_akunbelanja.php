<form class="form-horizontal az-form" id="form_new_akunbelanja" name="form_new_akunbelanja" method="POST">
	<!-- <input type="hidden" name="idakun_belanja" id="idakun_belanja">
	<input type="hidden" name="is_copy" id="is_copy"> -->
	<div class="form-group">
		<label class="control-label col-md-4">No. Rekening <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="no_rekening_akunbelanja" name="no_rekening_akunbelanja"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Nama Akun Belanja <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="nama_akun_belanja" name="nama_akun_belanja"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Aktif <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control" name="is_active" id="is_active">
				<option value="1">AKTIF</option>
				<option value="0">TIDAK AKTIF</option>
			</select>
		</div>
	</div>
</form>