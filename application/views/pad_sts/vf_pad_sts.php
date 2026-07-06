<form class="form-horizontal purchase-plan">
	<div class="form-group">
		<label class="control-label col-sm-2">Tanggal Bukti</label>
		<div class="col-md-4">
			<div class="container-date">
				<div class="cd-list">
					<?php echo $date1;?>
				</div>
				<div class="cd-list">s/d</div>
				<div class="cd-list">
					<?php echo $date2;?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Nomor Bukti</label>
		<div class="col-md-4 col-sm-6">
			<input type="text" class="form-control" name="vf_proof_number" id="vf_proof_number" placeholder="Nomor Bukti">
		</div>
	</div>
	<div class="form-group">
        <label class="control-label col-sm-2">Admin</label>
        <div class="col-md-4 col-sm-6">
        	<?php echo az_select_user_admin();?>
        </div>
	</div>
</form>