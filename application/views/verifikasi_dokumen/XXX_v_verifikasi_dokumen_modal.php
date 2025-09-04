<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idverification" name="idverification" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idverification_detail" name="idverification_detail" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-sm-3">Cari Realisasi Anggaran</label>
		<div class="col-sm-8">
			<select class="form-control" name="search_realisasi_anggaran" id="search_realisasi_anggaran"></select>
		</div>
	</div>
	<hr>
    <div class="detail-paket-belanja hide">
        <div class="form-group">
            <label class="control-label col-sm-3">Nomor Invoice</label>
            <div class="col-sm-8">
                <input type="hidden" class="form-control" name="idtransaction" id="idtransaction" readonly>
                <input type="text" class="form-control" name="transaction_code" id="transaction_code" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Nama Paket Belanja</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_paket_belanja" id="nama_paket_belanja" readonly>
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="control-label col-md-3">Total Realisasi</label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">Rp. </span>
                    <input type="text" class="form-control format-number txt-right" id="total_realisasi" name="total_realisasi" readonly/>
                </div>
            </div>
        </div> -->
    </div>
</form>