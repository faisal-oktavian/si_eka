<script>

    // Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan
        var bulanLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];
        var CapaianTargetPerBulan = <?php echo json_encode($capaian_target_per_bulan); ?>;
        var CapaianRealisasiPerBulan = <?php echo json_encode($capaian_realisasi_per_bulan); ?>;

        // data dummy
        // var CapaianTargetPerBulan = [
        // 	10, 15, 20, 30, 40, 50, 60, 70, 75, 80, 90, 100
        // ];
        // var CapaianRealisasiPerBulan = [
        // 	0, 15, 20, 20, 20, 50, 53, 58, 65, 70, 81, 94
        // ];

        var ctxLine = document.getElementById("lineCapaianPerBulan").getContext('2d');
        var lineCapaianPerBulan = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [
                    {
                        label: 'Target',
                        data: CapaianTargetPerBulan,
                        borderColor: 'rgba(220, 0, 48, 0.85)',
                        backgroundColor: 'rgba(220, 0, 48, 0.08)',
                        fill: false,
                        tension: 0.35,
                        borderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: 'rgba(220, 0, 48, 0.85)'
                    },
                    {
                        label: 'Realisasi',
                        data: CapaianRealisasiPerBulan,
                        borderColor: 'rgba(54, 163, 235, 0.87)',
                        backgroundColor: 'rgba(54, 163, 235, 0.08)',
                        fill: false,
                        tension: 0.35,
                        borderWidth: 3,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: 'rgba(54, 163, 235, 0.87)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 18
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + '%';
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(38, 55, 70, .08)'
                        },
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });


    // grafik realisasi anggaran
        // Contoh data, silakan ganti dengan data dari backend PHP
        // var nominal_sudah_dibayar = 50000000;
        // var nominal_belum_dibayar = 30000000;
        // var nominal_belum_direalisasi = 20000000;
        
        var nominal_sudah_dibayar = <?php echo isset($sudah_dibayar) ? $sudah_dibayar : 0; ?>;
        var nominal_menunggu_pembayaran = <?php echo isset($menunggu_pembayaran) ? $menunggu_pembayaran : 0; ?>;
        var nominal_npd = <?php echo isset($npd) ? $npd : 0; ?>;
        var nominal_sudah_diverifikasi = <?php echo isset($sudah_diverifikasi) ? $sudah_diverifikasi : 0; ?>;
        var nominal_menunggu_verifikasi = <?php echo isset($menunggu_verifikasi) ? $menunggu_verifikasi : 0; ?>;
        var nominal_kontrak_pengadaan = <?php echo isset($kontrak_pengadaan) ? $kontrak_pengadaan : 0; ?>;
        var nominal_proses_pengadaan = <?php echo isset($proses_pengadaan) ? $proses_pengadaan : 0; ?>;
        var nominal_belum_direalisasi = <?php echo isset($belum_direalisasi) ? $belum_direalisasi : 0; ?>;
        var total = <?php echo isset($total_anggaran_tahun_ini) ? $total_anggaran_tahun_ini : 0; ?>;

        function formatRupiah(angka) {
            return 'Rp. ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.round(angka));
        }

        function formatPersentase(nilai, totalAnggaran) {
            return (totalAnggaran ? Math.round(nilai / totalAnggaran * 10000) / 100 : 0).toFixed(2) + '%';
        }

        document.getElementById('label-sudah-dibayar').innerText = formatPersentase(nominal_sudah_dibayar, total) + ' (' + formatRupiah(nominal_sudah_dibayar) + ')';
        document.getElementById('label-menunggu-pembayaran').innerText = formatPersentase(nominal_menunggu_pembayaran, total) + ' (' + formatRupiah(nominal_menunggu_pembayaran) + ')';
        document.getElementById('label-npd').innerText = formatPersentase(nominal_npd, total) + ' (' + formatRupiah(nominal_npd) + ')';
        document.getElementById('label-sudah-diverifikasi').innerText = formatPersentase(nominal_sudah_diverifikasi, total) + ' (' + formatRupiah(nominal_sudah_diverifikasi) + ')';
        document.getElementById('label-menunggu-verifikasi').innerText = formatPersentase(nominal_menunggu_verifikasi, total) + ' (' + formatRupiah(nominal_menunggu_verifikasi) + ')';
        document.getElementById('label-belum-direalisasi').innerText = formatPersentase(nominal_belum_direalisasi, total) + ' (' + formatRupiah(nominal_belum_direalisasi) + ')';
        document.getElementById('label-kontrak-pengadaan').innerText = formatRupiah(nominal_kontrak_pengadaan);
        document.getElementById('label-proses-pengadaan').innerText = formatRupiah(nominal_proses_pengadaan);

        var chartCategories = [
            { label: 'Sudah Dibayar', value: nominal_sudah_dibayar, color: '#28A745', url: '<?= site_url("realisasi_anggaran_detail/sudah_dibayar") ?>' },
            { label: 'Menunggu Pembayaran', value: nominal_menunggu_pembayaran, color: '#FFCC66', url: '<?= site_url("realisasi_anggaran_detail/belum_dibayar") ?>' },
            { label: 'NPD', value: nominal_npd, color: '#999999', url: '<?= site_url("realisasi_anggaran_detail/npd") ?>' },
            { label: 'Sudah Diverifikasi', value: nominal_sudah_diverifikasi, color: '#0066FF', url: '<?= site_url("realisasi_anggaran_detail/sudah_diverifikasi") ?>' },
            { label: 'Menunggu Verifikasi', value: nominal_menunggu_verifikasi, color: '#FF6600', url: '<?= site_url("realisasi_anggaran_detail/belum_diverifikasi") ?>' },
            { label: 'Belum Direalisasi', value: nominal_belum_direalisasi, color: '#f44336', url: null }
        ].filter(function(category) {
            return category.value > 0;
        });

        var ctx = document.getElementById('pieAnggaranChart').getContext('2d');
        var pieAnggaranChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartCategories.map(function(category) { return category.label; }),
                datasets: [{
                    data: chartCategories.map(function(category) { return category.value; }),
                    backgroundColor: chartCategories.map(function(category) { return category.color; }),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                                return label + ': ' + formatPersentase(value, total) + ' (' + formatRupiah(value) + ')';
                            }
                        }
                    }
                }
            }
        });

        // Tambahkan event click pada chart Realisasi Anggaran
        document.getElementById('pieAnggaranChart').onclick = function(evt) {
            var activePoints = pieAnggaranChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (activePoints.length > 0) {
                var idx = activePoints[0].index;
                var category = chartCategories[idx];
                if (category && category.url) {
                    window.open(category.url, '_blank');
                }
            }
        };


    // grafik potensi sisa anggaran
        // Data dummy, silakan ganti dengan data backend jika perlu
        // var realisasi_anggaran_tahun_ini = <?php // echo isset($total_realisasi_tahun_ini) ? $total_realisasi_tahun_ini : 0; ?>;
        // var realisasi_anggaran_tahun_ini = 10000000000; // TODO: ganti dengan data realisasi dari backend jika perlu
        var total_anggaran_tahun_ini = <?php echo isset($total_anggaran_tahun_ini) ? $total_anggaran_tahun_ini : 0; ?>;
        var realisasi_anggaran_tahun_ini = <?php echo isset($realisasi_anggaran_tahun_ini) ? $realisasi_anggaran_tahun_ini : 0; ?>;
        
        var sisa_anggaran_tahun_ini = total_anggaran_tahun_ini - realisasi_anggaran_tahun_ini;

        var persen_realisasi = total_anggaran_tahun_ini ? Math.round(realisasi_anggaran_tahun_ini / total_anggaran_tahun_ini * 10000) / 100 : 0;
        var persen_sisa = total_anggaran_tahun_ini ? Math.round(sisa_anggaran_tahun_ini / total_anggaran_tahun_ini * 10000) / 100 : 0;

        // Label
        document.getElementById('label-total-anggaran-sisa').innerText = formatRupiah(total_anggaran_tahun_ini);
        document.getElementById('label-realisasi-anggaran-sisa').innerText = persen_realisasi.toFixed(2) + '% (' + formatRupiah(realisasi_anggaran_tahun_ini) + ')';
        document.getElementById('label-sisa-anggaran-sisa').innerText = persen_sisa.toFixed(2) + '% (' + formatRupiah(sisa_anggaran_tahun_ini) + ')';

        // Pie chart: hanya tampilkan realisasi dan sisa (total anggaran = 100%)
        var potensiCategories = [
            { label: 'Realisasi Anggaran', value: realisasi_anggaran_tahun_ini, color: '#2196f3' },
            { label: 'Sisa Anggaran', value: sisa_anggaran_tahun_ini, color: '#c3c3c3' }
        ].filter(function(category) {
            return category.value > 0;
        });

        var ctxPotensi = document.getElementById('piePotensiSisaChart').getContext('2d');
        var piePotensiSisaChart = new Chart(ctxPotensi, {
            type: 'doughnut',
            data: {
                labels: potensiCategories.map(function(category) { return category.label; }),
                datasets: [{
                    data: potensiCategories.map(function(category) { return category.value; }),
                    backgroundColor: potensiCategories.map(function(category) { return category.color; }),
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
                                return label + ': ' + formatPersentase(value, total_anggaran_tahun_ini) + ' (' + formatRupiah(value) + ')';
                            }
                        }
                    }
                }
            }
        });

        // Tambahkan event click pada chart Potensi Sisa Anggaran
        // document.getElementById('piePotensiSisaChart').onclick = function(evt) {
        // 	var activePoints = piePotensiSisaChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
        // 	if (activePoints.length > 0) {
        // 		var idx = activePoints[0].index;
        // 		// Tentukan link berdasarkan index
        // 		if (idx === 0) {
        // 			window.open('<?= site_url("realisasi_anggaran_detail/sudah_dibayar") ?>', '_blank');
        // 		} else if (idx === 1) {
        // 			window.open('<?= site_url("realisasi_anggaran_detail/potensi_sisa_anggaran") ?>', '_blank');
        // 		}
        // 	}
        // };


    // grafik Realisasi Anggaran per Sumber Dana
        // Dummy data realisasi per sumber dana (hanya DBH CHT & BLUD)
        // var realisasi_dbh = 21000000000;
        // var realisasi_blud = 9000000000;

        var realisasi_dau = <?php echo isset($realisasi_dau) ? $realisasi_dau : 0; ?>;
        var target_dau = <?php echo isset($target_dau) ? $target_dau : 0; ?>;
        var realisasi_blud = <?php echo isset($realisasi_blud) ? $realisasi_blud : 0; ?>;
        var target_blud = <?php echo isset($target_blud) ? $target_blud : 0; ?>;
        // var total_realisasi = realisasi_dbh + realisasi_blud;

        var persen_realisasi_dau = 0;
        var persen_realisasi_blud = 0;

        if (realisasi_dau != 0) {
            var persen_realisasi_dau = Math.round( (realisasi_dau / target_dau * 100) * 100) / 100;
        }
        if (realisasi_blud != 0) {
            var persen_realisasi_blud = Math.round( (realisasi_blud / target_blud * 100) * 100) / 100;
        }

        document.getElementById('label-realisasi-dau').innerText = persen_realisasi_dau + '% (' + formatRupiah(realisasi_dau) + ')';
        document.getElementById('label-realisasi-blud').innerText = persen_realisasi_blud + '% (' + formatRupiah(realisasi_blud) + ')';

        var sourceCategories = [
            {
                label: 'DAU yang Ditentukan Penggunaannya Bidang Kesehatan',
                value: realisasi_dau,
                color: '#2196f3',
                url: '<?= site_url("realisasi_anggaran_detail/dbh") ?>'
            },
            {
                label: 'Pendapatan dari BLUD',
                value: realisasi_blud,
                color: '#c500ff',
                url: '<?= site_url("realisasi_anggaran_detail/blud") ?>'
            }
        ].filter(function(category) {
            return category.value > 0;
        });
        var totalRealisasiSumberDana = sourceCategories.reduce(function(total, category) {
            return total + category.value;
        }, 0);

        var ctxRealisasiSumberDana = document.getElementById('pieRealisasiSumberDanaChart').getContext('2d');
        var pieRealisasiSumberDanaChart = new Chart(ctxRealisasiSumberDana, {
            type: 'doughnut',
            data: {
                labels: sourceCategories.map(function(category) { return category.label; }),
                datasets: [{
                    data: sourceCategories.map(function(category) { return category.value; }),
                    backgroundColor: sourceCategories.map(function(category) { return category.color; }),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                                return label + ': ' + formatPersentase(value, totalRealisasiSumberDana) + ' (' + formatRupiah(value) + ')';
                            }
                        }
                    }
                }
            }
        });

        // Event click pada chart Realisasi Anggaran per Sumber Dana
        document.getElementById('pieRealisasiSumberDanaChart').onclick = function(evt) {
            var activePoints = pieRealisasiSumberDanaChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (activePoints.length > 0) {
                var idx = activePoints[0].index;
                var category = sourceCategories[idx];
                if (category) {
                    window.open(category.url, '_blank');
                }
            }
        };


    // grafik Perbandingan Target & Realisasi Anggaran per Bulan
        var bulanLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];
        // Data dari backend PHP
        var ctx2 = document.getElementById("perbandingan").getContext('2d');
        var perbandingan = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [
                    {
                        label: 'Target',
                        backgroundColor: 'rgba(220, 0, 48, 0.72)',
                        borderRadius: 5,
                        data: <?= json_encode($target_per_bulan) ?>
                    },
                    {
                        label: 'Realisasi',
                        backgroundColor: 'rgba(54, 163, 235, 0.88)',
                        borderRadius: 5,
                        data: <?= json_encode($realisasi_per_bulan) ?>
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + formatRupiah(context.parsed.y);
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 18
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    x: {
                        ticks: {
                            font: {
                                weight: '500'
                            }
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(38, 55, 70, .08)'
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


    // Line Chart
        var ctx = document.getElementById("chartPenerimaan").getContext("2d");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: [ "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des" ],
                datasets: [{
                    label: "Realisasi Penerimaan",
                    data: <?= json_encode(array_values($chart_bulanan)); ?>,
                    borderColor: "#28a745",
                    backgroundColor: "rgba(40,167,69,.15)",
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: "#28a745",
                    fill: true,
                    lineTension: .3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage:70,
                legend: {
                    display: true
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return 'Rp ' + Number(value).toLocaleString('id-ID');
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Rp ' + Number(tooltipItem.yLabel).toLocaleString('id-ID');
                        }
                    }
                }
            }
        });

    // Donut Chart Target vs Realisasi
        var ctx2 = document.getElementById("chartTarget").getContext("2d");
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: [
                    'Realisasi',
                    'Sisa Target'
                ],
                datasets: [{
                    data: [
                        <?= $realisasi_tahun ?>,
                        <?= max($target_tahun - $realisasi_tahun, 0) ?>
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#e9ecef'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 70,
                legend: {
                    position: 'bottom'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var value = data.datasets[0].data[tooltipItem.index];
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                }
            }
        });