<script>
	jQuery('body').on('click', '.btn-add-realisasi_anggaran', function() {
		location.href = app_url + 'realisasi_anggaran/add';
	});

	jQuery('body').on('click', '.btn-edit-realisasi-anggaran', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'realisasi_anggaran/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-realisasi-anggaran', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'realisasi_anggaran/delete_realisasi',
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

	jQuery('body').on('click', '.btn-view-only-realisasi-anggaran', function() {
		var id = jQuery(this).attr('data_id');

        location.href = app_url + 'realisasi_anggaran/edit/' + id + '/view_only';
	});