<?php
	$role_view_pad_sts = false; // hanya lihat data

    if (aznav('role_view_pad_sts')) {
        $role_view_pad_sts = true;
    }
?>

<form class="form-horizontal row" id="form_pad_sts" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idpad_sts" name="hd_idpad_sts" value="<?php echo $id;?>">
		
		<div class="form-group">
			<label class="control-label col-md-4">Nomor Bukti <red>*</red></label>
			<div class="col-md-8">
				<input type="text" class="form-control" placeholder="Nomor Bukti" id="proof_number" name="proof_number">
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
			<label class="control-label col-md-4">Tanggal Bukti <red>*</red></label>
			<div class="col-md-8">
				<div class="input-group az-datetime">
					<input type="text" class="form-control" id="proof_date" name="proof_date" />
					<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Masuk ke <red>*</red></label>
			<div class="col-md-8">
				<?php echo az_select_proof_in();?>
			</div>
		</div>

		<div class="form-group description">
            <label class="control-label col-md-4">Untuk <red>*</red></label>
            <div class="col-md-8">
                <textarea class="form-control" name="proof_for" id="proof_for" rows="5"></textarea>
            </div>
        </div>

	</div>
	
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<?php
				if (!$role_view_pad_sts) {
			?>
					<button class="btn btn-primary btn-xs" type="button" id="btn_add_sts"><i class="fa fa-plus"></i> Tambah Rincian</i></button>
			<?php
				}
			?>
		</div>
		<table class="table table-bordered table-condensed" id="table_sts_detail">
			<thead>
				<tr>
					<th>Sub Kegiatan</th>
					<th width="auto">Kode Rekening</th>
					<th width="auto">Nama Rekening</th>
					<th width="auto">Langsung</th>
					<th width="auto">Uang Muka</th>
					<th width="auto">Piutang</th>
					<th width="auto">Jumlah</th>
					<th width="auto">Keterangan</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>pad_sts"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<?php
				if (!$role_view_pad_sts) {
			?>
					<button class="btn btn-primary" type="button" id="btn_save_pad_sts"><i class="fa fa-save"></i> Simpan</i></button>
			<?php
				}
			?>
		</div>
	</div>
</form>