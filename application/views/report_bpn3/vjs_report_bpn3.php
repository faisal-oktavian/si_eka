<script>
	jQuery('body').on('click', '.btn-pdf', function() {
		var date1 = jQuery('#date1').val();
		var date2 = jQuery('#date2').val();

		window.open(
			app_url + 'report_bpn3/export_pdf?date1=' + date1 + '&date2=' + date2,
			'_blank'
		);
	});
