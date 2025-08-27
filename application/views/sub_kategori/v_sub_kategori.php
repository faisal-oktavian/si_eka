<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idsub_kategori" id="idsub_kategori">
	<input type="hidden" name="is_copy" id="is_copy">
	<div class="form-group">
        <label class="control-label col-md-4">Kode Rekening</label>
        <div class="col-md-5">
        	<?php echo az_select_kode_rekening('kode_rekening');?>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Nama Sub Ketegori <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="nama_sub_kategori" name="nama_sub_kategori"/>
		</div>
	</div>
	<div class="form-group">
        <label class="control-label col-md-4">Sumber Dana <red>*</red></label>
        <div class="col-md-5">
        	<?php echo az_select_sumber_dana('sumber_dana');?>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Wajib Isi Jenis Kelamin <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control" name="is_gender" id="is_gender">
				<option value="" disabled> ~Pilih Opsi~ </option>
				<option value="1">YA</option>
				<option value="0">TIDAK</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Wajib Isi Keterangan <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control" name="is_description" id="is_description">
				<option value="" disabled> ~Pilih Opsi~ </option>
				<option value="1">YA</option>
				<option value="0">TIDAK</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Wajib Isi Ruang <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control" name="is_room" id="is_room">
				<option value="" disabled> ~Pilih Opsi~ </option>
				<option value="1">YA</option>
				<option value="0">TIDAK</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4">Wajib Isi Nama Diklat yang Diikuti <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control" name="is_name_training" id="is_name_training">
				<option value="" disabled> ~Pilih Opsi~ </option>
				<option value="1">YA</option>
				<option value="0">TIDAK</option>
			</select>
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