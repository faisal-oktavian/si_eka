<?php
	$role_view_purchase_contract = false; // hanya lihat data

    if (aznav('role_view_purchase_contract')) {
        $role_view_purchase_contract = true;
    }
?>

<form class="form-horizontal row" id="form_contract" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idcontract" name="hd_idcontract" value="<?php echo $id;?>">
		
		<div class="form-group">
			<label class="control-label col-md-4">Nomor Kontrak</label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Nomor Kontrak (Otomatis)" id="contract_code" disabled>
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
			<label class="control-label col-md-4">Tanggal Kontrak <red>*</red></label>
			<div class="col-md-8">
				<div class="input-group az-datetime">
					<input type="text" class="form-control" id="contract_date" name="contract_date" />
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">SPT</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="contract_spt" id="contract_spt" placeholder="Masukkan SPT">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">No. Undangan</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="contract_invitation_number" id="contract_invitation_number" placeholder="Masukkan No. Undangan">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">SP</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="contract_sp" id="contract_sp" placeholder="Masukkan SP">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">SPK</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="contract_spk" id="contract_spk" placeholder="Masukkan SPK">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Gaji/Honor</label>
			<div class="col-md-8">
				<select class="form-control" name="contract_honor" id="contract_honor">
					<option value="TIDAK">Tidak</option>
					<option value="YA">Ya</option>
				</select>
			</div>
		</div>

	</div>
	
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<?php
				if (!$role_view_purchase_contract) {
			?>
					<button class="btn btn-primary btn-xs" type="button" id="btn_add_contract"><i class="fa fa-plus"></i> Tambah Rencana Pengadaan</i></button>
			<?php
				}
			?>
		</div>
		<table class="table table-bordered table-condensed" id="table_dokumen">
			<thead>
				<tr>
					<th width="200px">Nomor Rencana Pengadaan</th>
					<th width="auto">Detail</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>purchase_contract"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<?php
				if (!$role_view_purchase_contract) {
			?>
					<button class="btn btn-primary" type="button" id="btn_save_contract"><i class="fa fa-save"></i> Simpan</i></button>
			<?php
				}
			?>
		</div>
	</div>
</form>