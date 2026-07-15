<script>
	jQuery('body').on('click', '.btn-add-pad_mutasi_kas', function() {
		location.href = app_url + 'pad_mutasi_kas/add';
	});

	jQuery('body').on('click', '.btn-edit-pad-mutasi-kas', function() {
		var id = jQuery(this).attr('data_id');
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

	jQuery('body').on('click', '.btn-view-only-purchase-plan', function() {
		var id = jQuery(this).attr('data_id');

        location.href = app_url + 'pad_mutasi_kas/edit/' + id + '/view_only';
	});

	jQuery('body').on('click', '.btn-excel', function() {
		var param = jQuery('.purchase-plan').serialize();
		window.open(app_url + 'pad_mutasi_kas/excel?'+param, '_blank');
	});