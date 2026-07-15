<?php
	$role_view_pad_mutasi_kas = false; // hanya lihat data

    if (aznav('role_view_pad_mutasi_kas')) {
        $role_view_pad_mutasi_kas = true;
    }
?>

<form class="form-horizontal row" id="form_pad_mutasi_kas" style="margin-top: 20px;">
	<div class="col-md-6">
		<input type="hidden" id="hd_idpad_mutasi_kas" name="hd_idpad_mutasi_kas" value="<?php echo $id;?>">
		
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
			<label class="control-label col-md-4">Jenis</label>
			<div class="col-md-8">
				<select class="form-control" name="proof_type" id="proof_type">
					<option value="PENERIMAAN">Penerimaan</option>
					<option value="PENGELUARAN">Pengeluaran</option>
					<option value="PINDAH_REKENING">Pindah Rekening</option>
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label col-md-4">Dari <red>*</red></label>
			<div class="col-md-6">
				<?php echo az_select_pad_rekening('proof_from');?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Ke <red>*</red></label>
			<div class="col-md-6">
				<?php echo az_select_pad_rekening('proof_to');?>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-4">Jumlah</label>
			<div class="col-md-6">
				<div class="input-group">
					<span class="input-group-addon">RP. </span>
					<input type="text" class="form-control format-number-decimal txt-right total_mutasi_kas" id="total_mutasi_kas" name="total_mutasi_kas"/>
				</div>
			</div>
		</div>

		<div class="form-group description">
            <label class="control-label col-md-4">Untuk <red>*</red></label>
            <div class="col-md-6">
                <textarea class="form-control" name="proof_for" id="proof_for" rows="5"></textarea>
            </div>
        </div>

	</div>
	
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>pad_mutasi_kas"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<?php
				if (!$role_view_pad_mutasi_kas) {
			?>
					<button class="btn btn-primary" type="button" id="btn_save_pad_mutasi_kas"><i class="fa fa-save"></i> Simpan</i></button>
			<?php
				}
			?>
		</div>
	</div>
</form>