
<div  style="margin-top:10px;">
	<a href="<?php echo app_url();?>master_paket_belanja/add" class="btn-add_paket_belanja"><button class="btn btn-default" type="button"><i class="fa fa-plus"></i> Tambah Paket Belanja</i></button></a>
</div>

<hr>

<form class="form-horizontal row" id="form_paket_belanja">
	<div class="col-md-6">
		<input type="hidden" id="hd_idpaket_belanja" name="hd_idpaket_belanja" value="<?php echo $id;?>">

		<div class="form-group">
			<label class="control-label col-sm-4">Nama Program</label>
			<div class="col-md-8">
				<?php echo az_select_nama_program('program');?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Nama Kegiatan</label>
			<div class="col-md-8">
				<?php echo az_select_nama_kegiatan_parent('kegiatan', '', 'kegiatan', 'idprogram');?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Nama Sub Kegiatan</label>
			<div class="col-md-8">
				<?php echo az_select_nama_sub_kegiatan_parent('sub_kegiatan', '', 'sub_kegiatan', 'idkegiatan');?>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Paket Belanja</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="nama_paket_belanja" id="nama_paket_belanja" placeholder="Paket Belanja">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Jumlah Anggaran</label>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right" id="nilai_anggaran" name="nilai_anggaran" readonly/>
				</div>
			</div>
		</div>
		<?php
			if (aznav('role_select_ppkom_pptk')) {
		?>
				<div class="form-group">
					<label class="control-label col-md-4">PPK/PP</label>
					<div class="col-md-8">
						<select class="form-control" name="select_ppkom_pptk" id="select_ppkom_pptk">
							<option value="" disabled selected> ~ Pilih ~ </option>
							<option value="PPK">PPK</option>
							<option value="PP">PP</option>
						</select>
					</div>
				</div>
		<?php
			}
		?>
	</div>
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_akun_belanja"><i class="fa fa-plus"></i> Tambah Akun Belanja</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_onthespot">
			<thead>
				<tr>
					<th width="450px">Akun Belanja</th>
					<th width="80px">Volume</th>
					<th width="80px">Satuan</th>
					<th width="110px">Harga Satuan</th>
					<th width="150px">Jumlah</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>master_paket_belanja"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_paket_belanja"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>