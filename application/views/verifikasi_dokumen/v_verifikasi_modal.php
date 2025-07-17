<form class="form-horizontal" id="form_approval">
	<input type="text" id="idverification" name="idverification" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-md-3">Status Verifikasi <red>*</red></label>
		<div class="col-md-7">
			<select class="form-control" name="status_approve" id="status_approve">
				<option value="" disabled selected>~ Pilih Status ~</option>
				<option value="DISETUJUI">Disetujui</option>
				<option value="DITOLAK">Ditolak</option>
			</select>
		</div>
	</div>
    <div class="form-group">
		<label class="control-label col-md-3">Keterangan <red>*</red></label>
		<div class="col-md-7">
			<textarea class="form-control" name="verification_description" id="verification_description" rows="5"></textarea>
		</div>
	</div>
</form>