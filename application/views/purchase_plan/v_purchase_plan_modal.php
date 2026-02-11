<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idpurchase_plan" name="idpurchase_plan" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idpurchase_plan_detail" name="idpurchase_plan_detail" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-md-3">Cari Uraian</label>
		<div class="col-md-8">
			<select class="form-control" name="search_uraian" id="search_uraian"></select>
		</div>
	</div>
	<hr>
    <div class="detail-paket-belanja hide">
        <div class="form-group">
            <label class="control-label col-md-3">Paket Belanja</label>
            <div class="col-md-8">
                <input type="hidden" class="form-control" name="idpaket_belanja" id="idpaket_belanja">
                <input type="text" class="form-control x-hidden" name="nama_paket_belanja" id="nama_paket_belanja" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Uraian</label>
            <div class="col-md-8">
                <input type="hidden" class="form-control" name="idpaket_belanja_detail_sub" id="idpaket_belanja_detail_sub">
                <input type="text" class="form-control x-hidden" name="nama_sub_kategori" id="nama_sub_kategori" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Volume Paket Belanja</label>
            <div class="col-md-8">
                <input type="hidden" class="form-control" name="harga_satuan" id="harga_satuan">
                <div class="input-group">
                    <input type="text" class="form-control x-hidden format-number txt-right volume-paket-belanja" id="volume_paket_belanja" name="volume_paket_belanja" readonly/>
                    <span class="input-group-addon satuan"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Volume <red>*</red></label>
            <div class="col-md-8">
                <input type="text" class="form-control format-number-decimal txt-right volume" id="volume" name="volume"/>
                <input type="hidden" class="form-control" name="purchase_plan_detail_total" id="purchase_plan_detail_total">
            </div>
        </div>
    </div>
</form>