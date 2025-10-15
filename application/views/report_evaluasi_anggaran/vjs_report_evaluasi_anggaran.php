<script>
	jQuery('#tahun_anggaran').datetimepicker({
		format: 'YYYY'
	});

	jQuery('body').on('click', '.btn-filter-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		location.href = app_url + 'report_evaluasi_anggaran/?tahun_anggaran='+tahun_anggaran;
	});

	var tahun_anggaran = "<?php echo $this->input->get('tahun_anggaran') ;?>";

	if (tahun_anggaran != "") {
		jQuery('#tahun_anggaran').val(tahun_anggaran);
	}

	jQuery('body').on('click', '.btn-print-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		// location.href = app_url + 'evaluasi_anggaran/print_report/'+tahun_anggaran;
		window.open(
			app_url + 'report_evaluasi_anggaran/print_report/' + tahun_anggaran,
			'_blank'
		);
	});
