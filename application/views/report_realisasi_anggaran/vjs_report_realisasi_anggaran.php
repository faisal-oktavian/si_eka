<script>
    jQuery('body').on('click', '.btn-excel', function() {
		var param = jQuery('.report-realisasi-anggaran').serialize();
		window.open(app_url + 'report_realisasi_anggaran/excel?'+param, '_blank');
	});
