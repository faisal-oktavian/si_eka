<script>
	jQuery('#tahun_anggaran').datetimepicker({
		format: 'YYYY'
	});

	jQuery('body').on('click', '.btn-filter-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		location.href = app_url + 'evaluasi_anggaran/?tahun_anggaran='+tahun_anggaran;
	});

	var tahun_anggaran = "<?php echo $this->input->get('tahun_anggaran') ;?>";

	if (tahun_anggaran != "") {
		jQuery('#tahun_anggaran').val(tahun_anggaran);
	}

	jQuery('body').on('click', '.btn-view', function() {
		var idpaket_belanja_detail = jQuery(this).attr('data_idpaket_belanja_detail');
		var tw = jQuery(this).attr('data_tw');
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		jQuery('.detail-table').html('');

		jQuery.ajax({
			url: app_url + 'evaluasi_anggaran/get_detail_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idpaket_belanja_detail: idpaket_belanja_detail,
				tw: tw,
				tahun_anggaran: tahun_anggaran
			},
			success: function(response) {
				show_modal('detail_realisasi');

				jQuery('.detail-table').html(response.data);
			},
			error: function(response) {}
		});
	});

	jQuery('body').on('click', '.btn-print-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		// location.href = app_url + 'evaluasi_anggaran/print_report/'+tahun_anggaran;
		window.open(
			app_url + 'evaluasi_anggaran/print_report/' + tahun_anggaran,
			'_blank'
		);
	});

	jQuery('body').on('click', '.btn-history-rak', function() {
		var idpaket_belanja_detail = jQuery(this).attr('data_idpaket_belanja_detail');
		var tw = jQuery(this).attr('data_tw');
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		jQuery('.detail-table').html('');

		jQuery.ajax({
			url: app_url + 'evaluasi_anggaran/get_history_rak',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idpaket_belanja_detail: idpaket_belanja_detail,
				tw: tw,
				tahun_anggaran: tahun_anggaran
			},
			success: function(response) {
				show_modal('detail_realisasi');

				jQuery('.detail-table').html(response.data);
			},
			error: function(response) {}
		});
	});
