<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal report-realisasi-anggaran">
			<div class="form-group">
				<label class="control-label col-sm-3">Tahun</label>
				<div class="col-md-6 col-sm-6">
					<div class="container-date">
						<div class="cd-list">
							<?php echo $tahun_anggaran;?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3">Paket Belanja</label>
				<div class="col-md-6 col-sm-6">
					<input type="text" class="form-control" name="vf_nama_paket_belanja" id="vf_nama_paket_belanja" placeholder="Paket Belanja">
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">
		<div class="row" style="margin-top:30px;">
			<div class="col-md-12 col-xs-12" style="margin:auto;">
				<div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
					<div class="row">
						<div class="col-xs-12" style="display:flex;align-items:center;justify-content:center;">
							<canvas id="perbandingan" height="120"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- CDN Chart.js -->
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script>
			var bulanLabels = [
				'TW 1', 'TW 2', 'TW 3', 'TW 4'
			];
			// Data dari backend PHP
			var targetPerBulan = <?php echo json_encode($target_per_bulan); ?>;
			var realisasiPerBulan = <?php echo json_encode($realisasi_per_bulan); ?>;
			var totalAnggaran = <?php echo json_encode($total_anggaran); ?>;
			var tahunGrafik = <?php echo isset($tahun_ini) ? $tahun_ini : date('Y'); ?>;

			var targetPersentase = targetPerBulan.map(function(value) {
				return totalAnggaran > 0 ? (value / totalAnggaran) * 100 : 0;
			});

			var realisasiPersentase = realisasiPerBulan.map(function(value) {
				return totalAnggaran > 0 ? (value / totalAnggaran) * 100 : 0;
			});

			var ctx2 = document.getElementById("perbandingan").getContext('2d');
			var perbandingan = new Chart(ctx2, {
				type: 'bar',
				data: {
					labels: bulanLabels,
					datasets: [
						{
							label: 'Target',
							backgroundColor: 'rgba(220, 0, 48, 0.85)',
							data: targetPerBulan
						},
						{
							label: 'Realisasi',
							backgroundColor: 'rgba(54, 163, 235, 0.87)',
							data: realisasiPerBulan
						}
					]
				},
				options: {
					responsive: true,
					plugins: {
						title: {
							display: true,
							text: 'Target & Realisasi Anggaran per TW (Tahun ' + tahunGrafik + ')',
							font: {
								size: 18
							}
						},
						tooltip: {
							mode: 'index',
							intersect: false,
							callbacks: {
								label: function(context) {
									var label = context.dataset.label || '';
									var value = context.parsed.y !== undefined ? context.parsed.y : context.parsed;
									var percent = 0;
									if (label === 'Target') {
										percent = targetPersentase[context.dataIndex];
									} else if (label === 'Realisasi') {
										percent = realisasiPersentase[context.dataIndex];
									}
									return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value) + ' (' + percent.toFixed(2) + '%)';
								}
							}
						},
						legend: {
							position: 'bottom'
						}
					},
					interaction: {
						mode: 'nearest',
						axis: 'x',
						intersect: false
					},
					scales: {
						x: {
							title: {
								display: true,
								text: 'TW'
							},
							ticks: {
								font: {
									weight: 'bold'
								}
							}
						},
						y: {
							title: {
								display: true,
								text: 'Nilai Anggaran'
							},
							beginAtZero: true,
							ticks: {
								callback: function(value) {
									return 'Rp ' + new Intl.NumberFormat().format(value);
								}
							}
						}
					}
				}
			});

		</script>
	</div>
</div>