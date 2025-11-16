<form class="form-horizontal row" id="form_npd" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idnpd" name="hd_idnpd" value="<?php echo $id;?>">
		
		<div class="form-group">
			<label class="control-label col-md-4">Nomor NPD</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Nomor NPD (Otomatis)" id="npd_code" disabled>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-4">Nama</label>
			<div class="col-md-8">
				<input type="hidden" class="form-control" name="iduser_created" id="iduser_created" value="<?php echo $iduser_created;?>">
				<input type="text" class="form-control" name="user_name" id="user_name" value="<?php echo $user_name;?>" readonly>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Tanggal NPD <red>*</red></label>
			<div class="col-md-8">
				<div class="input-group az-datetime">
					<input type="text" class="form-control" id="npd_date_created" name="npd_date_created" />
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_npd"><i class="fa fa-plus"></i> Tambah Dokumen</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_dokumen">
			<thead>
				<tr>
					<th>Nomor Verifikasi Dokumen</th>
					<th width="auto">Detail</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>npd"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_npd"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>