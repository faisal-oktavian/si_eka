<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idbudget_realization" name="idbudget_realization" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idbudget_realization_detail" name="idbudget_realization_detail" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-sm-3">Nomor Kontrak</label>
		<div class="col-sm-8">
            <?php echo az_select_contract_code();?>
		</div>
	</div>
    <div class="form-group ">
        <label class="control-label col-sm-3">Cari Uraian Belanja</label>
        <div class="col-sm-8">
            <?php echo az_select_sub_kategori_parent();?>
        </div>
    </div>
	<hr>
    <div class="detail-paket-belanja hide">
        <input type="" id="data_idcontract" name="data_idcontract" class="x-hidden" placeholder="id kontrak">
        <input type="" id="data_idcontract_detail" name="data_idcontract_detail" class="x-hidden" placeholder="id kontrak detail">
        <input type="" id="data_idpurchase_plan" name="data_idpurchase_plan" class="x-hidden" placeholder="id rencana">
        <input type="" id="data_idpurchase_plan_detail" name="data_idpurchase_plan_detail" class="x-hidden" placeholder="id rencana detail">
        <input type="" id="data_idpaket_belanja" name="data_idpaket_belanja" class="x-hidden" placeholder="id paket belanja">
        <input type="" id="data_idpaket_belanja_detail_sub" name="data_idpaket_belanja_detail_sub" class="x-hidden" placeholder="id paket belanja detail sub">
        <input type="" id="data_idsub_kategori" name="data_idsub_kategori" class="x-hidden" placeholder="id sub kategori">

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
                <input type="text" class="form-control" name="nama_paket_belanja" id="nama_paket_belanja" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">Uraian</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_sub_kategori" id="nama_sub_kategori" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Penyedia <red>*</red></label>
            <div class="col-md-8">
                <input type="text" class="form-control" id="provider" name="provider"/>
            </div>
        </div>
        <div class="form-group room">
            <label class="control-label col-md-3">Nama Ruang <red>*</red></label>
            <div class="col-md-8">
                <?php echo az_select_nama_ruang('ruang');?>
            </div>
        </div>
        <div class="form-group training">
            <label class="control-label col-md-3">Nama Diklat <red>*</red></label>
            <div class="col-md-8">
                <input type="text" class="form-control training-name" id="training_name" name="training_name"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Volume <red>*</red></label>
            <div class="col-md-8">
                <input type="text" class="form-control format-number txt-right volume" id="volume" name="volume"/>
            </div>
        </div>
        <div class="gender hide">
            <div class="form-group">
                <label class="control-label col-md-3">Laki-laki</label>
                <div class="col-md-8">
                    <input type="text" class="form-control format-number txt-right male" id="male" name="male"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">Perempuan</label>
                <div class="col-md-8">
                    <input type="text" class="form-control format-number txt-right female" id="female" name="female"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Harga Satuan <red>*</red></label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">Rp. </span>
                    <input type="text" class="form-control format-number txt-right unit-price" id="unit_price" name="unit_price"/>
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
                    <input type="text" class="form-control format-number txt-right total-realization-detail" id="total_realization_detail" name="total_realization_detail" readonly/>
                </div>
            </div>
        </div>
        <div class="form-group description">
            <label class="control-label col-md-3">Keterangan</label>
            <div class="col-md-8">
                <textarea class="form-control" name="realization_detail_description" id="realization_detail_description" rows="5"></textarea>
            </div>
        </div>
    </div>
</form>