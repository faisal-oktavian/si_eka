<form class="form-horizontal" id="form_add">
	<input type="hidden" id="idcontract" name="idcontract" class="x-hidden" value="<?php echo $id;?>">
	<input type="hidden" id="idcontract_detail" name="idcontract_detail" class="x-hidden">
    
	<div class="form-group">
		<label class="control-label col-sm-3">Cari Rencana Pengadaan</label>
		<div class="col-sm-8">
			<select class="form-control" name="search_dokumen" id="search_dokumen"></select>
		</div>
	</div>
	<hr>
    <input type="hidden" class="form-control" name="idpurchase_plan" id="idpurchase_plan">
    <div class="detail-dokumen hide">
    </div>
</form>