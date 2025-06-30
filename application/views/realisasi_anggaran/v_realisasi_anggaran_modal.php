<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idtransaction" name="idtransaction" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idtransaction_detail" name="idtransaction_detail" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-sm-3">Cari Paket Belanja</label>
		<div class="col-sm-8">
			<select class="form-control" name="search_paket_belanja" id="search_paket_belanja"></select>
		</div>
	</div>
	<hr>
    <div class="detail-paket-belanja hide">
        <div class="form-group">
            <label class="control-label col-sm-3">Urusan</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_urusan" id="nama_urusan" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Bidang Urusan</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_bidang_urusan" id="nama_bidang_urusan" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Program</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_program" id="nama_program" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Kegiatan</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_kegiatan" id="nama_kegiatan" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Sub Kegiatan</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_subkegiatan" id="nama_subkegiatan" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Paket Belanja</label>
            <div class="col-sm-8">
                <input type="hidden" class="form-control" name="idpaket_belanja" id="idpaket_belanja">
                <input type="text" class="form-control" name="nama_paket_belanja" id="nama_paket_belanja" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Uraian <red>*</red></label>
            <div class="col-md-8">
                <input type="hidden" id="helper_iduraian">
                <select class="form-control" name="iduraian" id="iduraian"></select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Volume</label>
            <div class="col-md-8">
                <input type="text" class="form-control format-number txt-right volume" id="volume" name="volume"/>
            </div>
        </div>
        <div class="gender hide">
            <div class="form-group">
                <label class="control-label col-md-3">Laki-laki</label>
                <div class="col-md-8">
                    <input type="text" class="form-control format-number txt-right" id="laki" name="laki"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">Perempuan</label>
                <div class="col-md-8">
                    <input type="text" class="form-control format-number txt-right" id="perempuan" name="perempuan"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Harga Satuan <red>*</red></label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">Rp. </span>
                    <input type="text" class="form-control format-number txt-right harga-satuan" id="harga_satuan" name="harga_satuan"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">PPN </label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">Rp. </span>
                    <input type="text" class="form-control format-number txt-right ppn" id="ppn" name="ppn"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">PPH </label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">Rp. </span>
                    <input type="text" class="form-control format-number txt-right pph" id="pph" name="pph"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Total</label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">Rp. </span>
                    <input type="text" class="form-control format-number txt-right" id="total" name="total" readonly/>
                </div>
            </div>
        </div>
    </div>
	<div class="form-group non-desain">
		<label class="control-label col-md-3">Keterangan</label>
		<div class="col-md-8">
			<textarea class="form-control" name="transaction_description" id="transaction_description" rows="5"></textarea>
		</div>
	</div>
</form>