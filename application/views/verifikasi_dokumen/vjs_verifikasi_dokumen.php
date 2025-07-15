<script>
	jQuery('body').on('click', '.btn-add-verifikasi_dokumen', function() {
		location.href = app_url + 'verifikasi_dokumen/add';
	});

	jQuery('body').on('click', '.btn-edit-verifikasi-dokumen', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'verifikasi_dokumen/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-realisasi-anggaran', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'verifikasi_dokumen/delete_realisasi',
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

	jQuery('body').on('click', '.btn-view-only-pos', function() {
		var id = jQuery(this).attr('data_id');
		var param = '/view_only';
		if (jQuery(this).hasClass('btn-return')) {
			param += '/return';
		}
		location.href = app_url + 'pos/edit/' + id + param;
	});