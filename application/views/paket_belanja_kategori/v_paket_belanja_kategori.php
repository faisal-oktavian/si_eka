<form class="form-horizontal row" id="form_paket_belanja" style="margin-top: 30px;;">
	<div class="col-md-6 akun-kategori">
		<input type="hidden" id="hd_idpaket_belanja" name="hd_idpaket_belanja" value="<?php echo $idpaket_belanja;?>">
		<input type="hidden" id="hd_idpaket_belanja_detail" name="hd_idpaket_belanja_detail" value="<?php echo $idpaket_belanja_detail;?>">

		<div class="form-group">
			<label class="control-label col-sm-4">Nama Program</label>
			<div class="col-md-8">
				<input type="hidden" id="idprogram" name="idprogram" value="<?php echo $idprogram;?>">
				<input type="text" class="form-control" name="nama_program" id="nama_program" placeholder="Program">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Nama Kegiatan</label>
			<div class="col-md-8">
				<input type="hidden" id="idkegiatan" name="idkegiatan" value="<?php echo $idkegiatan;?>">
				<input type="text" class="form-control" name="nama_kegiatan" id="nama_kegiatan" placeholder="Kegiatan">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Nama Sub Kegiatan</label>
			<div class="col-md-8">
				<input type="hidden" id="idsub_kegiatan" name="idsub_kegiatan" value="<?php echo $idsub_kegiatan;?>">
				<input type="text" class="form-control" name="nama_subkegiatan" id="nama_subkegiatan" placeholder="Sub Kegiatan">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Paket Belanja</label>
			<div class="col-md-8">
				<input type="hidden" id="idpaket_belanja" name="idpaket_belanja" value="<?php echo $idpaket_belanja;?>">
				<input type="text" class="form-control" name="nama_paket_belanja" id="nama_paket_belanja" placeholder="Paket Belanja">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-4">Jumlah Anggaran</label>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<input type="text" class="form-control format-number txt-right" id="nilai_anggaran" name="nilai_anggaran"/>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-4">Nama Akun Belanja</label>
			<div class="col-md-8">
				<input type="hidden" id="idpaket_belanja_detail" name="idpaket_belanja_detail" value="<?php echo $idpaket_belanja_detail;?>">
				<input type="hidden" id="idakun_belanja" name="idakun_belanja" value="<?php echo $idakun_belanja;?>">
				<input type="text" class="form-control" name="nama_akun_belanja" id="nama_akun_belanja" placeholder="Akun Belanja">
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<hr>
		<div style="margin-bottom:10px;">
			<button class="btn btn-primary btn-xs" type="button" id="btn_add_akun_belanja"><i class="fa fa-plus"></i> Tambah Kategori / Sub Kategori</i></button>
		</div>
		<table class="table table-bordered table-condensed" id="table_onthespot">
			<thead>
				<tr>
					<th width="350px">Kategori / Sub Kategori</th>
					<th width="130px">Aksi</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot></tfoot>
		</table>
		<hr>
		<div style="margin-bottom:10px;">
			<a href="<?php echo app_url();?>master_paket_belanja"><button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i> Kembali</i></button></a>
			<button class="btn btn-primary" type="button" id="btn_save_paket_belanja"><i class="fa fa-save"></i> Simpan</i></button>
		</div>
	</div>
</form>