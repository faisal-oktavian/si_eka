<form class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-2">Tanggal</label>
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
		<label class="control-label col-sm-2">Nomor Dokumen</label>
		<div class="col-md-4 col-sm-6">
			<input type="text" class="form-control" name="verification_code" id="verification_code" placeholder="Nomor Dokumen">
		</div>
	</div>
</form>