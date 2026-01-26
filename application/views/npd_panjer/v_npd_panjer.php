<form class="form-horizontal row" id="form_realisasi" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idnpd_panjer" name="hd_idnpd_panjer" value="<?php echo $id;?>">
		
		<!-- <div class="form-group">
			<label class="control-label col-md-4">Nomor NPD</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Nomor NPD (Otomatis)" id="npd_panjer_code" disabled>
			</div>
		</div> -->
		
		<div class="form-group">
			<label class="control-label col-md-4">Nama</label>
			<div class="col-md-8">
				<input type="hidden" class="form-control" name="iduser_created" id="iduser_created" value="<?php echo $iduser_created;?>">
				<input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo $user_name;?>" readonly>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Tanggal NPD Panjer <red>*</red></label>
			<div class="col-md-8">
				<div class="input-group az-datetime">
					<input type="text" class="form-control" id="npd_panjer_date" name="npd_panjer_date" />
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Nomor NPD Panjar <red>*</red></label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="npd_panjer_number" id="npd_panjer_number">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Bidang <red>*</red></label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="field_activity" id="field_activity">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Kegiatan <red>*</red></label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="activity" id="activity">
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_paket_belanja"><i class="fa fa-plus"></i> Tambah Paket Belanja</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_realisasi">
			<thead>
				<tr>
					<th>Paket Belanja</th>
					<th width="auto">Uraian</th>
					<th width="auto">Volume</th>
					<th width="130px">Harga Satuan</th>
					<th width="130px">Total Harga</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>npd_panjer"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_realisasi"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>