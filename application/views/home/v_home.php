<style>
	.centered {
		/* width: ; */
		float: none;
		margin: 20px auto;
	}

	.dropdown-menu {
		padding: 10px;
	}

	.setting-btn {
		background-color: #f2f2f2;
		padding: 5px 10px;
		border-radius: 6px;
		border: 2px #87ceeb solid;
		display: flex;
		align-items: center;
		/* justify-content: space-between; */
	}

	.setting-btn span {
		margin-left: 10px;
		color: gray;
	}

	/* .dropdown-child a:hover {
		color: #232323 !important;
		background: #f3f3f3 !important;
	} */

	.chart-box {
		border-bottom: 5px dashed #f5f5f5;
	}

	.title-chart {
		font-weight: bold;
		padding-bottom: 5px;
	}

	.h3-not-found {
		text-align: center;
		/* height: fit-content; */
		margin: 100px;
		color: #dadada;
		display: flex;
		/* flex-wrap: ; */
		justify-content: center;
	}

	.dropdown-label {
		margin-right: 10px;
	}

	/* table tr{
		margin-bottom: 2px;
	} */
	.progress {
        width: 100%;
        background-color: #f3f3f3;
        border-radius: 5px;
        overflow: hidden;
    }
    .progress-bar {
        height: 20px;
        background-color: #4caf50;
        text-align: center;
        color: white;
    }
</style>
<?php
	if (aznav('role_table')) {
?>
		<div class="row" style="margin-top:30px;">
			<div class="col-md-6 col-xs-12" style="margin:auto;">
				<div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
					<div class="d-flex align-items-center" style="margin-bottom:18px;">
						<i class="fa fa-pie-chart" style="font-size:26px;color:#4caf50;margin-right:10px;"></i>
						<span class="title-chart" style="font-size:20px;">Grafik Realisasi Anggaran Tahun <?php echo date('Y'); ?></span>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6" style="display:flex;align-items:center;justify-content:center;">
							<canvas id="pieAnggaranChart" width="180" height="180"></canvas>
						</div>
						<div class="col-xs-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center;">
							<div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
								<span style="display:inline-block;width:18px;height:18px;background:#4caf50;margin-right:10px;border-radius:4px;"></span>
								<div>
									<div style="font-weight:600;">Sudah Dibayar</div>
									<div id="label-sudah-dibayar" style="font-size:15px;"></div>
								</div>
							</div>
							<div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;margin-bottom:10px;display:flex;align-items:center;">
								<span style="display:inline-block;width:18px;height:18px;background:#ff9800;margin-right:10px;border-radius:4px;"></span>
								<div>
									<div style="font-weight:600;">Belum Dibayar</div>
									<div id="label-belum-dibayar" style="font-size:15px;"></div>
								</div>
							</div>
							<div class="mb-3" style="background:#f6f6f6;border-radius:8px;padding:12px 14px;display:flex;align-items:center;">
								<span style="display:inline-block;width:18px;height:18px;background:#f44336;margin-right:10px;border-radius:4px;"></span>
								<div>
									<div style="font-weight:600;">Belum Direalisasi</div>
									<div id="label-belum-direalisasi" style="font-size:15px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12" style="margin:auto;">
				<div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
					<div class="d-flex align-items-center" style="margin-bottom:18px;">
						<span class="title-chart" style="font-size:22px;font-weight:700;color:#263238;">Potensi Sisa Anggaran Tahun <?php echo date('Y'); ?></span>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6" style="display:flex;align-items:center;justify-content:center;">
							<canvas id="piePotensiSisaChart" width="180" height="180"></canvas>
						</div>
						<div class="col-xs-12 col-md-6" style="display:flex;flex-direction:column;justify-content:center;">
							<div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;margin-bottom:12px;display:flex;align-items:center;">
								<span style="display:inline-block;width:22px;height:22px;background:#c500ff;margin-right:14px;border-radius:5px;"></span>
								<div>
									<div style="font-weight:700;color:#263238;">Total Anggaran</div>
									<div id="label-total-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
								</div>
							</div>
							<div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;margin-bottom:12px;display:flex;align-items:center;">
								<span style="display:inline-block;width:22px;height:22px;background:#2196f3;margin-right:14px;border-radius:5px;"></span>
								<div>
									<div style="font-weight:700;color:#263238;">Realisasi Anggaran</div>
									<div id="label-realisasi-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
								</div>
							</div>
							<div class="mb-3" style="background:#f6f6f6;border-radius:10px;padding:14px 16px;display:flex;align-items:center;">
								<span style="display:inline-block;width:22px;height:22px;background:#c3c3c3;margin-right:14px;border-radius:5px;"></span>
								<div>
									<div style="font-weight:700;color:#263238;">Sisa Anggaran</div>
									<div id="label-sisa-anggaran-sisa" style="font-size:17px;color:#263238;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- CDN Chart.js -->
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<!-- grafik realisasi anggaran -->
		<script>
			// Contoh data, silakan ganti dengan data dari backend PHP
			var nominal_sudah_dibayar = 50000000;
			var nominal_belum_dibayar = 30000000;
			var nominal_belum_direalisasi = 20000000;
			var total = nominal_sudah_dibayar + nominal_belum_dibayar + nominal_belum_direalisasi;

			var persen_sudah_dibayar = total ? Math.round(nominal_sudah_dibayar / total * 100) : 0;
			var persen_belum_dibayar = total ? Math.round(nominal_belum_dibayar / total * 100) : 0;
			var persen_belum_direalisasi = total ? 100 - persen_sudah_dibayar - persen_belum_dibayar : 0;

			function formatRupiah(angka) {
				return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			}

			document.getElementById('label-sudah-dibayar').innerText = persen_sudah_dibayar + '% (' + formatRupiah(nominal_sudah_dibayar) + ')';
			document.getElementById('label-belum-dibayar').innerText = persen_belum_dibayar + '% (' + formatRupiah(nominal_belum_dibayar) + ')';
			document.getElementById('label-belum-direalisasi').innerText = persen_belum_direalisasi + '% (' + formatRupiah(nominal_belum_direalisasi) + ')';

			var ctx = document.getElementById('pieAnggaranChart').getContext('2d');
			var pieAnggaranChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: [
						'Sudah Dibayar',
						'Belum Dibayar',
						'Belum Direalisasi'
					],
					datasets: [{
						data: [
							nominal_sudah_dibayar,
							nominal_belum_dibayar,
							nominal_belum_direalisasi
						],
						backgroundColor: [
							'#4caf50',
							'#ff9800',
							'#f44336'
						],
						borderWidth: 2,
						borderColor: '#fff',
						hoverOffset: 8
					}]
				},
				options: {
					cutout: '65%',
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							callbacks: {
								label: function(context) {
									var label = context.label || '';
									var value = context.raw || 0;
									var percent = total ? Math.round(value / total * 100) : 0;
									return label + ': ' + percent + '% (' + formatRupiah(value) + ')';
								}
							}
						}
					}
				}
			});
		</script>

		<!-- grafik potensi sisa anggaran -->
		<script>
			// Data dummy, silakan ganti dengan data backend jika perlu
			var total_anggaran_tahun_ini = 100000000;
			var realisasi_anggaran_tahun_ini = 70000000;
			var sisa_anggaran_tahun_ini = total_anggaran_tahun_ini - realisasi_anggaran_tahun_ini;

			var persen_realisasi = total_anggaran_tahun_ini ? Math.round(realisasi_anggaran_tahun_ini / total_anggaran_tahun_ini * 100) : 0;
			var persen_sisa = total_anggaran_tahun_ini ? 100 - persen_realisasi : 0;

			function formatRupiah(angka) {
				return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			}

			// Label
			document.getElementById('label-total-anggaran-sisa').innerText = formatRupiah(total_anggaran_tahun_ini);
			document.getElementById('label-realisasi-anggaran-sisa').innerText = persen_realisasi + '% (' + formatRupiah(realisasi_anggaran_tahun_ini) + ')';
			document.getElementById('label-sisa-anggaran-sisa').innerText = persen_sisa + '% (' + formatRupiah(sisa_anggaran_tahun_ini) + ')';

			// Pie chart: hanya tampilkan realisasi dan sisa (total anggaran = 100%)
			var ctxPotensi = document.getElementById('piePotensiSisaChart').getContext('2d');
			var piePotensiSisaChart = new Chart(ctxPotensi, {
				type: 'doughnut',
				data: {
					labels: [
						'Realisasi Anggaran',
						'Sisa Anggaran'
					],
					datasets: [{
						data: [
							realisasi_anggaran_tahun_ini,
							sisa_anggaran_tahun_ini
						],
						backgroundColor: [
							'#2196f3', // Biru untuk Realisasi Anggaran
							'#c3c3c3'  // Oranye untuk Sisa Anggaran
						],
						borderWidth: 2,
						borderColor: '#fff',
						hoverOffset: 8
					}]
				},
				options: {
					cutout: '65%',
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							callbacks: {
								label: function(context) {
									var label = context.label || '';
									var value = context.raw || 0;
									var percent = total_anggaran_tahun_ini ? Math.round(value / total_anggaran_tahun_ini * 100) : 0;
									return label + ': ' + percent + '% (' + formatRupiah(value) + ')';
								}
							}
						}
					}
				}
			});
		</script>

		<div class="row" style="margin-top:30px;">
			<div class="col-md-12 col-xs-12" style="margin:auto;">
				<div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:24px 18px 18px 18px; background:#fff;">
					<div class="d-flex align-items-center" style="margin-bottom:18px;">
						<i class="fa fa-bar-chart" style="font-size:26px;color:#2196f3;margin-right:10px;"></i>
						<span class="title-chart" style="font-size:20px;">Perbandingan Target & Realisasi Anggaran per Bulan (<?php echo date('Y'); ?>)</span>
					</div>
					<div class="row">
						<div class="col-xs-12" style="display:flex;align-items:center;justify-content:center;">
							<canvas id="barAnggaranChart" height="180"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- grafik Perbandingan Target & Realisasi Anggaran per Bulan -->
		<script>
			// Contoh data, silakan ganti dengan data dari backend PHP
			var bulanLabels = [
				'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
				'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
			];
			// Data dummy, ganti dengan data asli
			var targetPerBulan = [10000000, 12000000, 9000000, 11000000, 13000000, 10000000, 12000000, 9000000, 11000000, 13000000, 10000000, 12000000];
			var realisasiPerBulan = [8000000, 10000000, 7000000, 9000000, 12000000, 9000000, 11000000, 8000000, 10000000, 12000000, 9000000, 11000000];

			var ctxBar = document.getElementById('barAnggaranChart').getContext('2d');
			var barAnggaranChart = new Chart(ctxBar, {
				type: 'bar',
				data: {
					labels: bulanLabels,
					datasets: [
						{
							label: 'Target',
							data: targetPerBulan,
							backgroundColor: 'rgba(33, 150, 243, 0.6)',
							borderColor: '#2196f3',
							borderWidth: 2
						},
						{
							label: 'Realisasi',
							data: realisasiPerBulan,
							backgroundColor: 'rgba(76, 175, 80, 0.6)',
							borderColor: '#4caf50',
							borderWidth: 2
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						x: {
							stacked: true
						},
						y: {
							stacked: true,
							beginAtZero: true
						}
					},
					plugins: {
						legend: {
							position: 'top',
							labels: {
								boxWidth: 12
							}
						},
						tooltip: {
							callbacks: {
								label: function(context) {
									var label = context.dataset.label || '';
									var value = context.raw || 0;
									return label + ': ' + formatRupiah(value);
								}
							}
						}
					}
				}
			});
		</script>

		<!-- Tabel Paket Belanja Belum Terealisasi -->
		<div class="row" style="margin-top:24px;">
			<div class="col-md-12">
				<div class="card shadow" style="border-radius:16px; border:1px solid #e0e0e0; padding:18px 0 18px 0; background:#fff;">
					<div class="d-flex align-items-center" style="margin-bottom:18px;">
						<i class="fa fa-bar-chart" style="font-size:26px;color:#2196f3;margin-right:10px;"></i>
						<span class="title-chart" style="font-size:20px;">Data Paket Belanja Yang Belum Terealisasi Pada Tahun (<?php echo date('Y'); ?>)</span>
					</div>
					<div class="table-responsive" style="padding:0 18px;">
						<table id="tabel-belanja-belum-realisasi" class="table table-bordered table-striped" style="width:100%;margin-bottom:0;">
							<thead>
								<tr>
									<th style="width:40px;">#</th>
									<th>Program</th>
									<th>Paket Belanja</th>
									<th style="width:140px;">Anggaran</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Pemeliharaan arsitektur gedung dan bangunan pelayanan</td>
									<td>449.500.000</td>
								</tr>
								<tr>
									<td>2</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Pemeliharaan Sipil, Arsitektur Gedung dan Bangunan Pelayanan</td>
									<td>237.800.000</td>
								</tr>
								<tr>
									<td>3</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Rehap Gedung Rawat Inap Anggrek Tahap II</td>
									<td>1.732.405.000</td>
								</tr>
								<tr>
									<td>4</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Pelatihan Manajemen SPI Rumah Sakit</td>
									<td>10.000.000</td>
								</tr>
								<tr>
									<td>5</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Pembuatan Pondasi Penahan Tanah Depan Gedung UKM</td>
									<td>119.129.000</td>
								</tr>
								<tr>
									<td>6</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Rehab Ruang Rapat Poli Lantai II Menjadi Ruang Komite</td>
									<td>103.299.000</td>
								</tr>
								<tr>
									<td>7</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Sosialisasi Perpajakan (SPT Pajak Tahunan)</td>
									<td>13.250.000</td>
								</tr>
								<tr>
									<td>8</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Audit Umum atas Laporan Keuangan RSUD Sumberglagah Tahun 2024</td>
									<td>85.000.000</td>
								</tr>
								<tr>
									<td>9</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Kegiatan RS Pendidikan</td>
									<td>55.000.000</td>
								</tr>
								<tr>
									<td>10</td>
									<td>PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI</td>
									<td>Perubahan Dinding Partisi Ruang Fisioterapi</td>
									<td>34.172.000</td>
								</tr>
								<!-- Tambahkan data dummy jika diperlukan -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- DataTables CDN -->
		<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> -->
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<!-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> -->
		<script>
			$(document).ready(function() {
				$('#tabel-belanja-belum-realisasi').DataTable({
					"language": {
						"search": "Search:",
						"lengthMenu": "Show _MENU_ entries",
						"info": "Showing _START_ to _END_ of _TOTAL_ entries",
						"paginate": {
							"first": "First",
							"last": "Last",
							"next": "Next",
							"previous": "Previous"
						},
						"zeroRecords": "No data found"
					}
				});
			});
		</script>
<?php
	} 
?>