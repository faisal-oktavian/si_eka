<script>
	jQuery('body').on('click', '.btn-add-purchase_plan', function() {
		location.href = app_url + 'purchase_plan/add';
	});

	jQuery('body').on('click', '.btn-edit-purchase-plan', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'purchase_plan/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-purchase-plan', function() {
		var id = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'purchase_plan/delete_plan',
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

        location.href = app_url + 'purchase_plan/edit/' + id + '/view_only';
	});