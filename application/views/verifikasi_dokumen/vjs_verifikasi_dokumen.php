<script>
	jQuery('body').on('click', '.btn-add-verifikasi-dokumen', function() {
		location.href = app_url + 'verifikasi_dokumen/add';
	});

	jQuery('body').on('click', '.btn-edit-verifikasi-dokumen', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'verifikasi_dokumen/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-verifikasi-dokumen', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'verifikasi_dokumen/delete_verifikasi_dokumen',
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

	jQuery('body').on('click', '.btn-view-only-verifikasi-dokumen', function() {
		var id = jQuery(this).attr('data_id');

		location.href = app_url + 'verifikasi_dokumen/edit/' + id + '/view_only';
	});

	jQuery('body').on('click', '.btn-verifikasi-dokumen', function() {
		var id = jQuery(this).attr('data_id');
		
		show_modal('verifikasi_dokumen');
		jQuery('#idverification').val(id);
	});

	jQuery('body').on('click', '.btn-action-save_approval', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'verifikasi_dokumen/approval',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_approval').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					location.reload();
				}
			},
			error: function(response) {}
		});
	});