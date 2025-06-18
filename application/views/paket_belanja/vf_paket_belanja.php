<form class="form-horizontal">
	<div class="form-group">
        <label class="control-label col-sm-2">Nama Program</label>
        <div class="col-md-4 col-sm-6">
        	<?php echo az_select_nama_program('f_nama_program');?>
        </div>
	</div>
	<div class="form-group">
        <label class="control-label col-sm-2">Nama Kegiatan</label>
        <div class="col-md-4 col-sm-6">
        	<?php echo az_select_nama_kegiatan_parent('f_nama_kegiatan', '', 'kegiatan', 'idf_nama_program');?>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Nama Sub Kegiatan</label>
		<div class="col-md-4 col-sm-6">
			<?php echo az_select_nama_sub_kegiatan_parent('f_nama_subkegiatan', '', 'sub_kegiatan', 'idf_nama_kegiatan');?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Paket Belanja</label>
		<div class="col-md-4 col-sm-6">
			<input type="text" class="form-control" name="vf_nama_paket_belanja" id="vf_nama_paket_belanja" placeholder="Paket Belanja">
		</div>
	</div>
</form>