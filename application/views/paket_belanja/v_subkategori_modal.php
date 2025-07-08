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

	<hr>

	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Januari</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_januari" id="rak_volume_januari" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_januari" name="rak_jumlah_januari" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Februari</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_februari" id="rak_volume_februari" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_februari" name="rak_jumlah_februari" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Maret</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_maret" id="rak_volume_maret" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_maret" name="rak_jumlah_maret" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan April</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_april" id="rak_volume_april" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_april" name="rak_jumlah_april" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Mei</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_mei" id="rak_volume_mei" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_mei" name="rak_jumlah_mei" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Juni</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_juni" id="rak_volume_juni" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_juni" name="rak_jumlah_juni" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Juli</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_juli" id="rak_volume_juli" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_juli" name="rak_jumlah_juli" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Agustus</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_agustus" id="rak_volume_agustus" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_agustus" name="rak_jumlah_agustus" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan September</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_september" id="rak_volume_september" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_september" name="rak_jumlah_september" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Oktober</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_oktober" id="rak_volume_oktober" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_oktober" name="rak_jumlah_oktober" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan November</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_november" id="rak_volume_november" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_november" name="rak_jumlah_november" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">RAK Bulan Desember</label>
		<div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
			<div class="col-md-4">
				<input type="text" class="form-control format-number volume" name="rak_volume_desember" id="rak_volume_desember" placeholder="Volume">
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right jumlah" id="rak_jumlah_desember" name="rak_jumlah_desember" placeholder="Jumlah" readonly/>
				</div>
			</div>
		</div>
	</div>
</form>