<script>
jQuery(function($){
	// Refresh chart data from server and update Chart.js instance
	function refreshReportTwChart() {
		var tahun = $('#vf_tahun_anggaran').val() || window.tahunGrafik || new Date().getFullYear();
		var param = { vf_tahun_anggaran: tahun };

		$.ajax({
			url: app_url + 'report_tw/chart_data',
			method: 'GET',
			data: param,
			dataType: 'json',
			cache: false,
			success: function(resp){
				if (!resp) return;
				// expect resp: { target: [...], realisasi: [...], anggaran: number }
				window.targetPerBulan = resp.target;
				window.realisasiPerBulan = resp.realisasi;
				window.totalAnggaran = resp.anggaran;
				window.tahunGrafik = tahun;

				if (window.perbandingan && window.perbandingan.data && window.perbandingan.data.datasets) {
					window.perbandingan.data.datasets[0].data = window.targetPerBulan;
					window.perbandingan.data.datasets[1].data = window.realisasiPerBulan;
					if (window.perbandingan.options && window.perbandingan.options.plugins && window.perbandingan.options.plugins.title) {
						window.perbandingan.options.plugins.title.text = 'Target & Realisasi Anggaran per TW (Tahun ' + window.tahunGrafik + ')';
					}
					window.perbandingan.update();
				}
			}
		});
	}

	// // trigger when year picker changes
	// $('body').on('change', '#vf_tahun_anggaran', function(){
	// 	refreshReportTwChart();
	// });

	// // also when paket belanja filter changes (text input)
	// $('body').on('change', '#vf_nama_paket_belanja', function(){
	// 	refreshReportTwChart();
	// });

	// when the datatable redraws (table updated), keep chart in sync
	$('body').on('draw.dt', '#report_tw', function(){
		refreshReportTwChart();
	});

	// $("#btn_top_filter_report_tw").on("click", function() {
	// 	refreshReportTwChart();
	// });

	// optional: refresh once on page load to ensure chart and filters are consistent
	$(window).on('load', function(){
		// do not override server-provided chart if no changes, but ensure values reflect current filter
		var tahunFilter = $('#vf_tahun_anggaran').val();
		if (tahunFilter && tahunFilter != window.tahunGrafik) {
			refreshReportTwChart();
		}
	});
});
