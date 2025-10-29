<script>
	jQuery('body').on('click', '.btn-add-rencana_pengadaan', function() {
		location.href = app_url + 'rencana_pengadaan/add';
	});

	jQuery('body').on('click', '.btn-edit-rencana-pengadaan', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'rencana_pengadaan/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-rencana-pengadaan', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'rencana_pengadaan/delete_rencana',
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

	jQuery('body').on('click', '.btn-view-only-rencana-pengadaan', function() {
		var id = jQuery(this).attr('data_id');

        location.href = app_url + 'rencana_pengadaan/edit/' + id + '/view_only';
	});