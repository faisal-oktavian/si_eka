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
			<input type="text" class="form-control" name="vf_realization_code" id="vf_realization_code" placeholder="Nomor Dokumen">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Status</label>
		<div class="col-md-4 col-sm-6">
			<select class="form-control" name="vf_realization_status" id="vf_realization_status">
				<option value="">Semua</option>
				<option value="PROSES PENGADAAN">Proses Pengadaan</option>
				<option value="KONTRAK PENGADAAN">Kontrak Pengadaan</option>
				<option value="MENUNGGU VERIFIKASI">Menunggu Verifikasi</option>
				<option value="SUDAH DIVERIFIKASI">Sudah Diverifikasi</option>
				<option value="DITOLAK VERIFIKATOR">Revisi Dokumen</option>
				<option value="INPUT NPD">Input NPD</option>
				<option value="MENUNGGU PEMBAYARAN">Menunggu Pembayaran</option>
				<option value="SUDAH DIBAYAR BENDAHARA">Sudah Dibayar Bendahara</option>
			</select>
		</div>
	</div>
</form>