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
                        backgroundColor: 'rgba(220, 0, 48, 0.15)',
                        fill: false,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(220, 0, 48, 0.85)'
                    },
                    {
                        label: 'Realisasi',
                        data: CapaianRealisasiPerBulan,
                        borderColor: 'rgba(54, 163, 235, 0.87)',
                        backgroundColor: 'rgba(54, 163, 235, 0.15)',
                        fill: false,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(54, 163, 235, 0.87)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        // text: 'Persentase Capaian Target & Realisasi Anggaran per Bulan',
                        // font: { size: 18 }
                    },
                    legend: {
                        position: 'bottom'
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
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Persentase (%)'
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

        // opsi proses pengadaan dilebur menjadi 1 di belum direalisasi
        var new_nominal_belum_direalisasi = nominal_belum_direalisasi + nominal_proses_pengadaan;

        // var total = nominal_sudah_dibayar + nominal_menunggu_pembayaran + nominal_npd + nominal_sudah_diverifikasi + nominal_menunggu_verifikasi + nominal_kontrak_pengadaan + nominal_proses_pengadaan + nominal_belum_direalisasi;
        var total = nominal_sudah_dibayar + nominal_menunggu_pembayaran + nominal_npd + nominal_sudah_diverifikasi + nominal_menunggu_verifikasi + nominal_kontrak_pengadaan + new_nominal_belum_direalisasi;

        // var total = <?php echo isset($total_anggaran_tahun_ini) ? $total_anggaran_tahun_ini : 0; ?>;

        var persen_sudah_dibayar = total ? Math.round( (nominal_sudah_dibayar / total * 100) * 100) / 100 : 0;
        var persen_menunggu_pembayaran = total ? Math.round( (nominal_menunggu_pembayaran / total * 100) * 100) / 100 : 0;
        var persen_npd = total ? Math.round( (nominal_npd / total * 100) * 100) / 100 : 0;
        var persen_sudah_diverifikasi = total ? Math.round( (nominal_sudah_diverifikasi / total * 100) * 100) / 100 : 0;
        var persen_menunggu_verifikasi = total ? Math.round( (nominal_menunggu_verifikasi / total * 100) * 100) / 100 : 0;
        var persen_kontrak_pengadaan = total ? Math.round( (nominal_kontrak_pengadaan / total * 100) * 100) / 100 : 0;
        var persen_proses_pengadaan = total ? Math.round( (nominal_proses_pengadaan / total * 100) * 100) / 100 : 0;

        // var persen_belum_direalisasi = total ? Math.round((100 - (persen_sudah_dibayar + persen_npd + persen_sudah_diverifikasi + persen_menunggu_verifikasi + persen_kontrak_pengadaan + persen_proses_pengadaan)) * 100) / 100 : 0;
        var persen_belum_direalisasi = total ? Math.round((100 - (persen_sudah_dibayar + persen_npd + persen_sudah_diverifikasi + persen_menunggu_verifikasi + persen_kontrak_pengadaan)) * 100) / 100 : 0;

        function formatRupiah(angka) {
            return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.getElementById('label-sudah-dibayar').innerText = persen_sudah_dibayar + '% (' + formatRupiah(nominal_sudah_dibayar) + ')';
        document.getElementById('label-menunggu-pembayaran').innerText = persen_menunggu_pembayaran + '% (' + formatRupiah(nominal_menunggu_pembayaran) + ')';
        document.getElementById('label-npd').innerText = persen_npd + '% (' + formatRupiah(nominal_npd) + ')';
        document.getElementById('label-sudah-diverifikasi').innerText = persen_sudah_diverifikasi + '% (' + formatRupiah(nominal_sudah_diverifikasi) + ')';
        document.getElementById('label-menunggu-verifikasi').innerText = persen_menunggu_verifikasi + '% (' + formatRupiah(nominal_menunggu_verifikasi) + ')';
        document.getElementById('label-kontrak-pengadaan').innerText = persen_kontrak_pengadaan + '% (' + formatRupiah(nominal_kontrak_pengadaan) + ')';
        // document.getElementById('label-proses-pengadaan').innerText = persen_proses_pengadaan + '% (' + formatRupiah(nominal_proses_pengadaan) + ')';
        
        
        // document.getElementById('label-belum-dibayar').innerText = persen_belum_dibayar + '% (' + formatRupiah(nominal_belum_dibayar) + ')';
        // document.getElementById('label-belum-direalisasi').innerText = persen_belum_direalisasi + '% (' + formatRupiah(nominal_belum_direalisasi) + ')';
        document.getElementById('label-belum-direalisasi').innerText = persen_belum_direalisasi + '% (' + formatRupiah(new_nominal_belum_direalisasi) + ')';

        var ctx = document.getElementById('pieAnggaranChart').getContext('2d');
        var pieAnggaranChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Sudah Dibayar',
                    'Menunggu Pembayaran',
                    'NPD',
                    'Sudah Diverifikasi',
                    'Menunggu Diverifikasi',
                    'Kontrak Pengadaan',
                    'Proses Pengadaan',
                    'Belum Direalisasi'
                ],
                datasets: [{
                    data: [
                        nominal_sudah_dibayar,
                        nominal_menunggu_pembayaran,
                        nominal_npd,
                        nominal_sudah_diverifikasi,
                        nominal_menunggu_verifikasi,
                        nominal_kontrak_pengadaan,
                        nominal_proses_pengadaan,
                        new_nominal_belum_direalisasi
                    ],
                    backgroundColor: [
                        '#28A745',
                        '#FFCC66',
                        '#999999',
                        '#0066FF',
                        '#FF6600',
                        '#FF9900',
                        '#FFCC00',
                        '#f44336',
                    ],
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
                                var percent = total ? Math.round(value / total * 100) : 0;
                                return label + ': ' + percent + '% (' + formatRupiah(value) + ')';
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

                console.log(idx);
                // Tentukan link berdasarkan index
                if (idx === 0) {
                    window.open('<?= site_url("realisasi_anggaran_detail/sudah_dibayar") ?>', '_blank');
                } else if (idx === 1) {
                    window.open('<?= site_url("realisasi_anggaran_detail/belum_dibayar") ?>', '_blank');
                } else if (idx === 2) {
                    window.open('<?= site_url("realisasi_anggaran_detail/npd") ?>', '_blank');
                } else if (idx === 3) {
                    window.open('<?= site_url("realisasi_anggaran_detail/sudah_diverifikasi") ?>', '_blank');
                } else if (idx === 4) {
                    window.open('<?= site_url("realisasi_anggaran_detail/belum_diverifikasi") ?>', '_blank');
                } else if (idx === 5) {
                    window.open('<?= site_url("realisasi_anggaran_detail/kontrak_pengadaan") ?>', '_blank');
                } else if (idx === 6) {
                    window.open('<?= site_url("realisasi_anggaran_detail/proses_pengadaan") ?>', '_blank');
                } else if (idx === 7) {
                    // window.open('<?= site_url("realisasi_anggaran_detail/belum_direalisasi") ?>', '_blank');
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

        var persen_realisasi = total_anggaran_tahun_ini ? Math.round( (realisasi_anggaran_tahun_ini / total_anggaran_tahun_ini * 100) * 100) / 100 : 0;
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
                        '#c3c3c3'  // Abu-abu untuk Sisa Anggaran
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

        var realisasi_dbh = <?php echo isset($dbh) ? $dbh : 0; ?>;
        var target_dbh = <?php echo isset($target_dbh) ? $target_dbh : 0; ?>;
        var realisasi_blud = <?php echo isset($blud) ? $blud : 0; ?>;
        var target_blud = <?php echo isset($target_blud) ? $target_blud : 0; ?>;
        // var total_realisasi = realisasi_dbh + realisasi_blud;

        var persen_realisasi_dbh = 0;
        var persen_realisasi_blud = 0;

        if (realisasi_dbh != 0) {
            var persen_realisasi_dbh = Math.round( (realisasi_dbh / target_dbh * 100) * 100) / 100;
        }
        if (realisasi_blud != 0) {
            var persen_realisasi_blud = Math.round( (realisasi_blud / target_blud * 100) * 100) / 100;
        }

        document.getElementById('label-realisasi-dbh').innerText = persen_realisasi_dbh + '% (' + formatRupiah(realisasi_dbh) + ')';
        document.getElementById('label-realisasi-blud').innerText = persen_realisasi_blud + '% (' + formatRupiah(realisasi_blud) + ')';

        var ctxRealisasiSumberDana = document.getElementById('pieRealisasiSumberDanaChart').getContext('2d');
        var pieRealisasiSumberDanaChart = new Chart(ctxRealisasiSumberDana, {
            type: 'doughnut',
            data: {
                labels: ['DAU yang Ditentukan Penggunaannya Bidang Kesehatan', 'Pendapatan dari BLUD'],
                datasets: [{
                    data: [realisasi_dbh, realisasi_blud],
                    backgroundColor: [
                        '#2196f3', // DAU yang Ditentukan Penggunaannya Bidang Kesehatan
                        '#c500ff'  // BLUD
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
                                var percent = total_realisasi ? Math.round(value / total_realisasi * 100) : 0;
                                return label + ': ' + percent + '% (' + formatRupiah(value) + ')';
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
                // 0: DBH, 1: BLUD
                if (idx === 0) {
                    window.open('<?= site_url("realisasi_anggaran_detail/dbh") ?>', '_blank');
                } else if (idx === 1) {
                    window.open('<?= site_url("realisasi_anggaran_detail/blud") ?>', '_blank');
                }
            }
        };


    // grafik Perbandingan Target & Realisasi Anggaran per Bulan
        var bulanLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];
        // Data dari backend PHP
        var targetPerBulan = <?php echo json_encode($target_per_bulan); ?>;
        var realisasiPerBulan = <?php echo json_encode($realisasi_per_bulan); ?>;
        var tahunGrafik = <?php echo isset($tahun_ini) ? $tahun_ini : date('Y'); ?>;

        var ctx2 = document.getElementById("perbandingan").getContext('2d');
        var perbandingan = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [
                    {
                        label: 'Target',
                        backgroundColor: 'rgba(220, 0, 48, 0.85)',
                        data: <?= json_encode($target_per_bulan) ?>
                    },
                    {
                        label: 'Realisasi',
                        backgroundColor: 'rgba(54, 163, 235, 0.87)',
                        data: <?= json_encode($realisasi_per_bulan) ?>
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Perbandingan Target & Realisasi Anggaran per Bulan (Tahun ' + tahunGrafik + ')',
                        font: {
                            size: 18
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
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
                            text: 'Bulan'
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