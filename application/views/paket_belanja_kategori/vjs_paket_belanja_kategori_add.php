<script>

	var idpaket_belanja =  "<?php echo $idpaket_belanja;?>";
	var nama_paket_belanja = "<?php echo $nama_paket_belanja;?>";
	var idpaket_belanja_detail = "<?php echo $idpaket_belanja_detail;?>";
	var idprogram = "<?php echo $idprogram;?>";
	var nama_program = "<?php echo $nama_program;?>";
	var idkegiatan = "<?php echo $idkegiatan;?>";
	var nama_kegiatan = "<?php echo $nama_kegiatan;?>";
	var idsub_kegiatan = "<?php echo $idsub_kegiatan;?>";
	var nama_subkegiatan = "<?php echo $nama_subkegiatan;?>";
	var nilai_anggaran = "<?php echo $nilai_anggaran;?>";
	var idakun_belanja = "<?php echo $idakun_belanja;?>";
	var nama_akun_belanja = "<?php echo $nama_akun_belanja;?>";

	if (idpaket_belanja != null) {
		jQuery('#hd_idpaket_belanja').val(idpaket_belanja);
		jQuery('#hd_idpaket_belanja_detail').val(idpaket_belanja_detail);
		jQuery('#idprogram').val(idprogram);
		jQuery('#nama_program').val(nama_program);
		jQuery('#idkegiatan').val(idkegiatan);
		jQuery('#nama_kegiatan').val(nama_kegiatan);
		jQuery('#idsub_kegiatan').val(idsub_kegiatan);
		jQuery('#nama_subkegiatan').val(nama_subkegiatan);
		jQuery('#idpaket_belanja').val(idpaket_belanja);
		jQuery('#nama_paket_belanja').val(nama_paket_belanja);
		jQuery('#nilai_anggaran').val(thousand_separator(nilai_anggaran));
		jQuery('#idpaket_belanja_detail').val(idpaket_belanja_detail);
		jQuery('#idakun_belanja').val(idakun_belanja);
		jQuery('#nama_akun_belanja').val(nama_akun_belanja);

		// jQuery('#form_paket_belanja').find('.akun-kategori').prop('readonly', true);
		
		jQuery('#form_paket_belanja > .akun-kategori input').prop('readonly', true);
	}

	if (the_id != "") {
		generate_detail_paket_belanja(the_id);

		jQuery.ajax({
			url: app_url + 'master_paket_belanja/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery("#idprogram").append(new Option(response.paket_belanja.nama_program, response.paket_belanja.idprogram, true, true)).trigger('change');
				jQuery("#idkegiatan").append(new Option(response.paket_belanja.nama_kegiatan, response.paket_belanja.idkegiatan, true, true)).trigger('change');
				jQuery("#idsub_kegiatan").append(new Option(response.paket_belanja.nama_subkegiatan, response.paket_belanja.idsub_kegiatan, true, true)).trigger('change');
				
				jQuery('#nama_paket_belanja').val(response.paket_belanja.nama_paket_belanja);
				jQuery('#nilai_anggaran').val(thousand_separator(response.paket_belanja.nilai_anggaran));
			},
			error: function(response) {}
		});
	}