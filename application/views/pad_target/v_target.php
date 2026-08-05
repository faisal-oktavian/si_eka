<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idpad_target" id="idpad_target">
	<input type="hidden" name="is_copy" id="is_copy">
	<div class="form-group">
		<label class="control-label col-md-3">Tahun <red>*</red></label>
		<div class="col-md-6">
			<input type="text" class="form-control" id="tahun" name="tahun"/>
		</div>
	</div>
	<div class="form-group">
        <label class="control-label col-md-3">Cari Kode Rekening <red>*</red></label>
        <div class="col-md-6">
            <?php echo az_select_pad_koderek();?>
        </div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3">Target per Tahun <red>*</red></label>
		<div class="col-md-6">
			<div class="input-group">
				<span class="input-group-addon">Rp. </span>
				<input type="text" class="form-control format-number-decimal txt-right target_per_tahun" id="target_per_tahun" name="target_per_tahun"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Target Bulan Laporan <red>*</red></label>
		<div class="col-md-6">
			<div class="input-group">
				<span class="input-group-addon">Rp. </span>
				<input type="text" class="form-control format-number-decimal txt-right target_bulan_laporan" id="target_bulan_laporan" name="target_bulan_laporan"/>	
			</div>
		</div>
	</div>
</form>