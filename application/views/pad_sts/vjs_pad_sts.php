<script>
	jQuery('body').on('click', '.btn-add-pad_sts', function() {
		location.href = app_url + 'pad_sts/add';
	});

	jQuery('body').on('click', '.btn-edit-pad-sts', function() {
		var id = jQuery(this).attr('data_id');

		var dtable = $("#pad_sts").dataTable({
			bRetrieve: true
		});

		var settings = dtable.fnSettings();

		var currentPage = Math.ceil(
			settings._iDisplayStart / settings._iDisplayLength
		) + 1;

		sessionStorage.setItem('sts_page', currentPage);

		location.href = app_url + 'pad_sts/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-pad-sts', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'pad_sts/delete_sts',
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

	jQuery('body').on('click', '.btn-view-only-pad-sts', function() {
		var id = jQuery(this).attr('data_id');

        location.href = app_url + 'pad_sts/edit/' + id + '/view_only';
	});

	jQuery('body').on('click', '.btn-excel', function() {
		var param = jQuery('.purchase-plan').serialize();
		window.open(app_url + 'pad_sts/excel?'+param, '_blank');
	});

	function reloadFilterSts() {
		var dtable = $("#pad_sts").dataTable({
			bRetrieve: true
		});

		dtable.fnDraw();
	}

	jQuery(window).on('pageshow', function () {
		if (sessionStorage.getItem('sts_back') === '1') {
			sessionStorage.removeItem('sts_back');
			var savedPage = sessionStorage.getItem('sts_page');
			setTimeout(function () {
				var dtable = $("#pad_sts").dataTable({
					bRetrieve: true
				});
				if (savedPage !== null) {
					var settings = dtable.fnSettings();
					settings._iDisplayStart =
						(parseInt(savedPage) - 1) *
						settings._iDisplayLength;
					dtable.fnDraw(false);
					sessionStorage.removeItem('sts_page');
				} 
				else {
					dtable.fnDraw();
				}
			}, 500);
		}
	});

	// jQuery(window).on('pageshow', function () {
	// 	if (sessionStorage.getItem('sts_back') === '1') {
	// 		sessionStorage.removeItem('sts_back');
	// 		var savedPage = sessionStorage.getItem('sts_page');
	// 		setTimeout(function () {
	// 			var dtable = $("#pad_sts").dataTable({
	// 				bRetrieve: true
	// 			});
	// 			dtable.fnDraw();
	// 			if (savedPage !== null) {
	// 				setTimeout(function () {
	// 					dtable.fnPageChange(
	// 						parseInt(savedPage) - 1,
	// 						true
	// 					);
	// 					sessionStorage.removeItem('sts_page');
	// 				}, 300);
	// 			}
	// 		}, 500);
	// 	}
	// });