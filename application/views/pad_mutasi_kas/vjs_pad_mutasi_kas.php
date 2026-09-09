<script>
	jQuery('body').on('click', '.btn-add-pad_mutasi_kas', function() {
		location.href = app_url + 'pad_mutasi_kas/add';
	});

	jQuery('body').on('click', '.btn-edit-pad-mutasi-kas', function() {
		var id = jQuery(this).attr('data_id');

		var dtable = $("#pad_mutasi_kas").dataTable({
			bRetrieve: true
		});

		var settings = dtable.fnSettings();

		var currentPage = Math.ceil(
			settings._iDisplayStart / settings._iDisplayLength
		) + 1;

		sessionStorage.setItem('mutasi_kas_page', currentPage);
		
		location.href = app_url + 'pad_mutasi_kas/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-pad-mutasi-kas', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'pad_mutasi_kas/delete_mutasi',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id: id
					},
					success: function(response) {
						hide_loading();
						if(response.err_code == 0) {
							location.reload();
						} 
						else {
							bootbox.alert(response.err_message);
						}
					},
					error: function(response) {}
				});
			}
			else{
				hide_loading();
			}
		})
	});

	jQuery('body').on('click', '.btn-view-only-pad-mutasi-kas', function() {
		var id = jQuery(this).attr('data_id');

        location.href = app_url + 'pad_mutasi_kas/edit/' + id + '/view_only';
	});

	jQuery('body').on('click', '.btn-excel', function() {
		var param = jQuery('.purchase-plan').serialize();
		window.open(app_url + 'pad_mutasi_kas/excel?'+param, '_blank');
	});

	function reloadFilterMutasiKas() {
		var dtable = $("#pad_mutasi_kas").dataTable({
			bRetrieve: true
		});

		dtable.fnDraw();
	}

	jQuery(window).on('pageshow', function () {
		if (sessionStorage.getItem('mutasi_kas_back') === '1') {
			sessionStorage.removeItem('mutasi_kas_back');
			var savedPage = sessionStorage.getItem('mutasi_kas_page');
			setTimeout(function () {
				var dtable = $("#pad_mutasi_kas").dataTable({
					bRetrieve: true
				});
				if (savedPage !== null) {
					var settings = dtable.fnSettings();
					settings._iDisplayStart =
						(parseInt(savedPage) - 1) *
						settings._iDisplayLength;
					dtable.fnDraw(false);
					sessionStorage.removeItem('mutasi_kas_page');
				} 
				else {
					dtable.fnDraw();
				}
			}, 500);
		}
	});