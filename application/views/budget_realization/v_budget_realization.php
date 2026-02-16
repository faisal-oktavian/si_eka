<?php
	$role_view_budget_realization = false; // hanya lihat data

    if (aznav('role_view_budget_realization')) {
        $role_view_budget_realization = true;
    }
?>

<form class="form-horizontal row" id="form_realization" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idbudget_realization" name="hd_idbudget_realization" value="<?php echo $id;?>">
		
		<div class="form-group">
			<label class="control-label col-md-4">Nomor Realisasi</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Nomor Realisasi (Otomatis)" id="realization_code" disabled>
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
					<input type="text" class="form-control" id="realization_date" name="realization_date" />
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Catatan </label>
			<div class="col-md-8">
				<textarea class="form-control" name="notes" id="notes" rows="5"></textarea>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<?php
				if (!$role_view_budget_realization) {
			?>
					<button class="btn btn-primary btn-xs" type="button" id="btn_add_contract"><i class="fa fa-plus"></i> Tambah Kontrak</i></button>
			<?php
				}
			?>
		</div>
		<table class="table table-bordered table-condensed" id="table_realization">
			<thead>
				<tr>
					<th width="130px">Nomor Kontrak</th>
					<th width="130px">Nomor Rencana</th>
					<th width="180px">Paket Belanja</th>
					<th width="200px">Uraian</th>
					<th width="60px">Volume</th>
					<th width="100px">Harga Satuan</th>
					<th width="80px">PPN</th>
					<th width="80px">PPH</th>
					<th width="110px">Total Harga</th>
					<th width="50px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>budget_realization"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<?php
				if (!$role_view_budget_realization) {
			?>
					<button class="btn btn-primary" type="button" id="btn_save_realization"><i class="fa fa-save"></i> Simpan</i></button>
			<?php
				}
			?>
		</div>
	</div>
</form>