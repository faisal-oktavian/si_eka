<script>
	jQuery('body').on('click', '.btn-pdf', function() {
		var vf_tahun = jQuery('#vf_tahun').val();

		window.open(
			app_url + 'report_bpn1/export_pdf/' + vf_tahun,
			'_blank'
		);
	});

	jQuery('body').on('click', '#btn_top_filter_report_bpn1', function() {
		var vf_tahun = jQuery('#vf_tahun').val();

		jQuery.ajax({
			url: app_url + 'report_bpn1/get_saldo_awal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				tahun: vf_tahun
			},
			success: function(response) {
				hide_loading();
				if(response.err_code == 0) {
					
					jQuery('#all_total_debt').html(thousand_separator_decimal(response.total_saldo_awal));
				} 
				else {
					bootbox.alert(response.err_message);
				}
			},
			error: function(response) {}
		});
	});
