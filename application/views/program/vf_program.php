<form class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-2">Tahun</label>
		<div class="col-md-4 col-sm-6">
			<div class="container-date">
				<div class="cd-list">
					<?php echo $tahun_anggaran;?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">No. Rekening</label>
		<div class="col-md-4 col-sm-6">
			<input type="text" class="form-control" name="vf_no_rekening_program" id="vf_no_rekening_program" placeholder="No. Rekening">
		</div>
	</div>
	<div class="form-group">
        <label class="control-label col-sm-2">Nama Urusan</label>
        <div class="col-md-4 col-sm-6">
        	<?php echo az_select_nama_urusan('f_nama_urusan');?>
        </div>
	</div>
	<div class="form-group">
        <label class="control-label col-sm-2">Nama Bidang Urusan</label>
        <div class="col-md-4 col-sm-6">
        	<?php echo az_select_nama_bidang_urusan_parent('f_nama_bidang_urusan', '', 'bidang_urusan', 'idf_nama_urusan');?>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Nama Program</label>
		<div class="col-md-4 col-sm-6">
			<input type="text" class="form-control" name="vf_nama_program" id="vf_nama_program" placeholder="Nama Program">
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