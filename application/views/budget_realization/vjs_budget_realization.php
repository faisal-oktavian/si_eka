<script>
	jQuery('body').on('click', '.btn-add-budget_realization', function() {
		location.href = app_url + 'budget_realization/add';
	});

	jQuery('body').on('click', '.btn-edit-budget-realization', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'budget_realization/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-budget-realization', function() {
		var idbudget_realization = jQuery(this).attr('data_id');

		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'budget_realization/delete_realization',
					type: 'POST',
					dataType: 'JSON',
					data: {
						idbudget_realization: idbudget_realization
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

	jQuery('body').on('click', '.btn-view-only-budget-realization', function() {
		var id = jQuery(this).attr('data_id');

        location.href = app_url + 'budget_realization/edit/' + id + '/view_only';
	});

	jQuery('body').on('click', '.btn-edit-description', function() {
		var id = jQuery(this).attr('data_id');
		console.log(id);
		show_modal('description');
		jQuery('#form_description').find('#idbudget_realization').val(id);
	});

	jQuery('body').on('click', '.btn-action-save_description', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'budget_realization/save_description',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_description').serialize(),
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