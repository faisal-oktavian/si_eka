<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery('#proof_date').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});

	// simpan sts
	jQuery('body').on('click', '#btn_save_pad_mutasi_kas', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'pad_mutasi_kas/save_mutasi_kas',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_pad_mutasi_kas').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'pad_mutasi_kas';
				}
				else {
					bootbox.alert(response.err_message);
				}
			},
			error: function(response){}
		});
	});

	// edit data
	var the_id =  "<?php echo $id;?>";
	if (the_id != "") {

		jQuery.ajax({
			url: app_url + 'pad_mutasi_kas/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#hd_idpad_mutasi_kas').val(response.pad_mutasi_kas.idpad_mutasi_kas);
				jQuery('#proof_number').val(response.pad_mutasi_kas.proof_number);
				jQuery('#iduser_created').val(response.pad_mutasi_kas.iduser_created);
				// jQuery('#user_name').val(response.pad_mutasi_kas.user_name);
				jQuery('#proof_date').val(response.pad_mutasi_kas.txt_proof_date);
				jQuery('#proof_type').val(response.pad_mutasi_kas.proof_type);
				jQuery("#idproof_from.select2-ajax").append(new Option(response.pad_mutasi_kas.uraian_from, response.pad_mutasi_kas.idproof_from, true, true)).trigger('change');
				jQuery("#idproof_to.select2-ajax").append(new Option(response.pad_mutasi_kas.uraian_to, response.pad_mutasi_kas.idproof_to, true, true)).trigger('change');
				jQuery('#total_mutasi_kas').val(thousand_separator_decimal(response.pad_mutasi_kas.total_mutasi_kas));
				jQuery('#proof_for').val(response.pad_mutasi_kas.proof_for);
			},
			error: function(response) {}
		});
	}