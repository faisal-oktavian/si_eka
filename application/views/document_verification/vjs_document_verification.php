<script>
	jQuery('body').on('click', '.btn-verifikasi-dokumen', function() {
		var id = jQuery(this).attr('data_id');
		var idverif = jQuery(this).attr('data_idverif');
		
		show_modal('document_verification');
		jQuery('#form_approval').find('#idbudget_realization').val(id);
		jQuery('#form_approval').find('#idverification').val(idverif);
	});

	jQuery('body').on('click', '.btn-action-save_approval', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'document_verification/approval',
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

	jQuery('body').on('click', '.btn-description-dokumen', function() {
		var id = jQuery(this).attr('data_id');
		var idverif = jQuery(this).attr('data_idverif');
		console.log(id);
		show_modal('description');
		jQuery('#form_description').find('#idbudget_realization').val(id);
		jQuery('#form_description').find('#idverification').val(idverif);
	});

	jQuery('body').on('click', '.btn-action-save_description', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'document_verification/save_description',
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