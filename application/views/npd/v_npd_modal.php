<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idnpd" name="idnpd" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idnpd_detail" name="idnpd_detail" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-sm-3">Cari Dokumen</label>
		<div class="col-sm-8">
			<select class="form-control" name="search_dokumen" id="search_dokumen"></select>
		</div>
	</div>
	<hr>
    <div class="detail-dokumen hide">
        <div class="form-group">
            <label class="control-label col-sm-3">Nomor Dokumen</label>
            <div class="col-sm-8">
                <input type="hidden" class="form-control" name="idverification" id="idverification" readonly>
                <input type="text" class="form-control" name="verification_code" id="verification_code" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Nama Paket Belanja</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_paket_belanja" id="nama_paket_belanja" readonly>
            </div>
        </div>
    </div>
</form>