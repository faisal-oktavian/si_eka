<form class="form-horizontal row" id="form_onthespot">
	<div class="col-md-6">
		<input type="" id="hd_idtransaction" name="hd_idtransaction" value="<?php echo $id;?>">
		
		<div class="form-group">
			<label class="control-label col-md-4">Kode Order</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Kode Order (Otomatis)" id="transaction_code" disabled>
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
			<label class="control-label col-md-4">Tanggal Realisasi <red>*</red></label>
			<div class="col-md-8">
				<div class="input-group az-datetime">
					<input type="text" class="form-control" id="transaction_date" name="transaction_date" />
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
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_paket_belanja"><i class="fa fa-plus"></i> Tambah Paket Belanja</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_onthespot">
			<thead>
				<tr>
					<th>Paket Belanja</th>
					<th width="auto">Uraian</th>
					<th width="auto">Volume</th>
					<th width="auto">Harga Satuan</th>
					<th width="auto">Total Harga</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>realisasi_anggaran"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_onthespot"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>