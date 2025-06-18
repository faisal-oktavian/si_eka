<form class="form-horizontal row" id="form_onthespot">
	<div class="col-md-6">
		<input type="hidden" id="hd_idtransaction" name="hd_idtransaction" value="<?php echo $id;?>">
		<input type="hidden" id="is_edit_transaction">
		<input type="hidden" id="the_edit" value="<?php echo $id;?>" name="the_edit">
		<div class="form-group">
			<label class="control-label col-md-4">Nomor Handphone</label>
			<div class="col-md-8">
				<input type="hidden" name="idmember" id="idmember" value="">
				<input type="text" autocomplete="off" class="form-control" name="customer_handphone" id="customer_handphone" placeholder="Nomor Handphone Pelanggan">
				<div style="position: relative; width: 100%;" class="autocomplete-number">
					<div class="autocomplete-field">
						<ul style="list-style: none; padding:0px; margin-bottom: 0px; border-radius: 2px; font-size: 13px; overflow: hidden; border: 1px solid #0000001c">

						</ul>    		
					</div>
				</div>				
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Nama</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Nama Pelanggan">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Email</label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="customer_email" id="customer_email" placeholder="Email Pelanggan">
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_product"><i class="fa fa-plus"></i> Tambah Produk</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_onthespot">
			<thead>
				<tr>
					<th>Produk</th>
					<th width="150px">Berat</th>
					<th width="200px">Harga Produk</th>
					<th width="100px">Jumlah</th>
					<?php
						$app_accounting = az_get_config('app_accounting', 'config_app');
						if ($app_accounting) {
					?>
							<th width="100px">Pajak</th>
					<?php
						}
					?>
					<th width="200px">Total Harga</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>master_paket_belanja"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_onthespot"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>