<script>
	jQuery('#tahun_anggaran').datetimepicker({
		format: 'YYYY'
	});

	var tahun_anggaran = "<?php echo $this->input->get('tahun_anggaran') ;?>";
	var nama_paket_belanja = "<?php echo $this->input->get('paket_belanja') ;?>";

	if (tahun_anggaran != "") {
		jQuery('#tahun_anggaran').val(tahun_anggaran);
	}

	if (nama_paket_belanja != "") {
		jQuery('#vf_nama_paket_belanja').val(nama_paket_belanja);
	}

	jQuery('body').on('click', '.btn-print-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		// location.href = app_url + 'report_detail_papbd/print_report/'+tahun_anggaran;
		window.open(
			app_url + 'report_detail_papbd/print_report/' + tahun_anggaran,
			'_blank'
		);
	});

	var lazyState = {
		page: 1,
		loading: false,
		hasMore: true,
		batchSize: 20,
		observer: null,
		loadedRows: 0
	};

	function loaderTemplate() {
		return '<div class="loader-card"><div class="spinner"></div><div class="loading-text">Loading<span class="dots"><span>.</span><span>.</span><span>.</span></span></div></div>';
	}

	function showInitialLoader() {
		var initialLoader = jQuery('#initialLoader');
		initialLoader.html(loaderTemplate()).addClass('active');
	}

	function hideInitialLoader() {
		jQuery('#initialLoader').removeClass('active').html('');
	}

	function showLazyLoader() {
		var loader = jQuery('#lazyLoader');
		loader.html(loaderTemplate()).addClass('active');
	}

	function hideLazyLoader() {
		jQuery('#lazyLoader').removeClass('active').html('');
	}

	function removeBlur() {
		jQuery('#evaluasiTableBody tr').removeClass('blur-row blur-2 blur-3');
	}

	function applyBlur() {
		removeBlur();
		var rows = jQuery('#evaluasiTableBody tr');
		var total = rows.length;
		if (total < 4) {
			return;
		}
		rows.eq(total - 4).addClass('blur-row');
		rows.eq(total - 3).addClass('blur-row blur-2');
		rows.eq(total - 2).addClass('blur-row blur-3');
		rows.eq(total - 1).addClass('blur-row blur-3');
	}

	function loadLazyData() {
		if (lazyState.loading || !lazyState.hasMore) {
			return;
		}

		lazyState.loading = true;
		if (lazyState.loadedRows === 0) {
			showInitialLoader();
		} else {
			showLazyLoader();
		}

		jQuery.ajax({
			url: app_url + 'report_detail_papbd/get_lazy_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				tahun_anggaran: jQuery('#tahun_anggaran').val(),
				nama_paket_belanja: jQuery('#vf_nama_paket_belanja').val(),
				is_papbd: jQuery('#is_papbd').val(),
				page: lazyState.page,
				batch_size: lazyState.batchSize
			},
			success: function(response) {
				hideInitialLoader();
				if (response.status && response.data) {
					jQuery('#evaluasiTableBody').append(response.data);
					lazyState.loadedRows += response.loaded_count || 0;
					lazyState.page += 1;
					lazyState.hasMore = response.has_more;
					if (lazyState.hasMore) {
						applyBlur();
					} else {
						removeBlur();
					}
				}
				hideLazyLoader();
				lazyState.loading = false;
				if (!lazyState.hasMore) {
					if (lazyState.observer) {
						lazyState.observer.disconnect();
					}
				}
			},
			error: function() {
				hideInitialLoader();
				hideLazyLoader();
				lazyState.loading = false;
			}
		});
	}

	jQuery(function() {
		var observerTarget = document.getElementById('observer');
		if (observerTarget) {
			lazyState.observer = new IntersectionObserver(function(entries) {
				if (entries[0] && entries[0].isIntersecting) {
					loadLazyData();
				}
			}, {
				rootMargin: '200px'
			});
			lazyState.observer.observe(observerTarget);
		}
		loadLazyData();
	});

	jQuery('body').on('click', '.btn-filter-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();
		var nama_paket_belanja = jQuery('#vf_nama_paket_belanja').val();

		location.href = app_url + 'report_detail_papbd/?tahun_anggaran='+tahun_anggaran+'&paket_belanja='+nama_paket_belanja;
	});
