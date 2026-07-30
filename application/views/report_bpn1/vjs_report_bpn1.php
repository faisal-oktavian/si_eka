<script>
	jQuery('body').on('click', '.btn-pdf', function() {
		var vf_tahun = jQuery('#vf_tahun').val();

		window.open(
			app_url + 'report_bpn1/export_pdf/' + vf_tahun,
			'_blank'
		);
	});
