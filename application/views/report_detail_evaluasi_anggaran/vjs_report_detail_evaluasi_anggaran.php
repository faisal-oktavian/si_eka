<script>
	jQuery('#tahun_anggaran').datetimepicker({
		format: 'YYYY'
	});

	jQuery('body').on('click', '.btn-filter-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();
		var nama_paket_belanja = jQuery('#vf_nama_paket_belanja').val();

		location.href = app_url + 'report_detail_evaluasi_anggaran/?tahun_anggaran='+tahun_anggaran+'&paket_belanja='+nama_paket_belanja;
	});

	var tahun_anggaran = "<?php echo $this->input->get('tahun_anggaran') ;?>";
	var nama_paket_belanja = "<?php echo $this->input->get('paket_belanja') ;?>";

	if (tahun_anggaran != "") {
		jQuery('#tahun_anggaran').val(tahun_anggaran);
	}

	if (nama_paket_belanja != "") {
		jQuery('#vf_nama_paket_belanja').val(nama_paket_belanja);
	}

	jQuery('body').on('click', '.btn-print-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		// location.href = app_url + 'evaluasi_anggaran/print_report/'+tahun_anggaran;
		window.open(
			app_url + 'report_detail_evaluasi_anggaran/print_report/' + tahun_anggaran,
			'_blank'
		);
	});
