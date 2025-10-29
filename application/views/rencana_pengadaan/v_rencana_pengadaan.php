<form class="form-horizontal row" id="form_rencana" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idpurchase_plan" name="hd_idpurchase_plan" value="<?php echo $id;?>">
		
		<div class="form-group">
			<label class="control-label col-md-4">Nomor Rencana</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Nomor Rencana (Otomatis)" id="purchase_plan_code" disabled>
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
			<label class="control-label col-md-4">Tanggal Rencana <red>*</red></label>
			<div class="col-md-8">
				<div class="input-group az-datetime">
					<input type="text" class="form-control" id="purchase_plan_date" name="purchase_plan_date" />
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
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_uraian"><i class="fa fa-plus"></i> Tambah Uraian</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_rencana">
			<thead>
				<tr>
					<th>Paket Belanja</th>
					<th width="auto">Uraian</th>
					<th width="auto">Volume</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>rencana_pengadaan"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_rencana_pengadaan"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>