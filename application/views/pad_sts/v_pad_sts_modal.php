<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idpad_sts" name="idpad_sts" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idpad_sts_detail" name="idpad_sts_detail" class="x-hidden">
    
    <div class="form-group">
        <label class="control-label col-md-3">Cari Kode Rekening <red>*</red></label>
        <div class="col-md-8">
            <input type="hidden" class="form-control" name="hd_idpad_kode_rekening" id="hd_idpad_kode_rekening">
            <?php echo az_select_pad_koderek();?>
        </div>
    </div>
    
    <hr>

    <div class="detail-koderek">
        <div class="form-group">
            <label class="control-label col-md-3">Sub Kegiatan</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="sub_kegiatan" id="sub_kegiatan" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Kode Rekening</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="kode_rekening" id="kode_rekening" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Nama Rekening</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="nama_rekening" id="nama_rekening" readonly>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-md-3">Langsung</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon">RP. </span>
                <input type="text" class="form-control format-number-decimal txt-right direct-receipt" id="direct_receipt" name="direct_receipt"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Uang Muka</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon">RP. </span>
                <input type="text" class="form-control format-number-decimal txt-right down-payment" id="down_payment" name="down_payment"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Piutang</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon">RP. </span>
                <input type="text" class="form-control format-number-decimal txt-right debt" id="debt" name="debt"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Jumlah</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon">RP. </span>
                <input type="text" class="form-control format-number-decimal txt-right total_detail" id="total_detail" name="total_detail" readonly/>
            </div>
        </div>
    </div>
    <div class="form-group description">
        <label class="control-label col-md-3">Keterangan</label>
        <div class="col-md-8">
            <textarea class="form-control" name="description_detail" id="description_detail" rows="5"></textarea>
        </div>
    </div>
</form>