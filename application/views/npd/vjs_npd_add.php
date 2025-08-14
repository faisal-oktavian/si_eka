<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery(document).ready(function() {
		if (is_viewonly == true) {
			// jQuery('.form-horizontal, .btn-primary').attr('readonly', true);
			jQuery('#btn_add_npd, #btn_save_npd').hide();
			// jQuery('#btn_save_onthespot').attr('disabled', true);
			// jQuery('#btn_add_product').attr('disabled', true);
			// jQuery('#idoutlet').attr('disabled', true);
			// jQuery('#taken_sent').attr('disabled', true);
			// jQuery('#customer_type').attr('disabled', true);
			// jQuery('#transaction_type').attr('disabled', true);
			// jQuery('#cs_no').attr('disabled', true);
			// jQuery('#search_member').attr('disabled', true);
			// jQuery('#idacc_term_payment').prop('disabled', true);
			// jQuery('#idacc_tax_pph').prop('disabled', true);
		}
	});

	jQuery('#npd_date_created').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});

	jQuery('body').on('click', '#btn_add_npd', function() {
		
		show_modal('add');

		jQuery('#form_add').find('.detail-dokumen').addClass('hide');
		// jQuery('#idtransaction_detail').val('');
		jQuery('#form_add input, #form_add select').not('.x-hidden').val('').trigger('change.select2');	
	});

	jQuery("#search_dokumen").select2({
		placeholder: "~ Cari Dokumen ~",
		allowClear: true,
		minimumInputLength: 0,
		ajax: { 
		    url: app_url + 'npd/search_dokumen',
		    dataType: "json",
		    delay: 250,
		    data: function(params) {
		      	return {
					term: params.term,
				}
			},
			cache: true
		}
	});

	jQuery('body').on('change', '#search_dokumen', function() {
		var id = jQuery(this).val();
		console.log('asdasda '+id);
		
		select_dokumen(id);
	});

	function select_dokumen(idverification) {
		jQuery.ajax({
			url: app_url + 'npd/select_dokumen',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				id: idverification
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-dokumen').removeClass('hide');
				
				jQuery('#idverification').val(response.idverification);
				jQuery('#verification_code').val(response.verification_code);
				jQuery('#nama_paket_belanja').val(response.nama_paket_belanja);

				jQuery('#search_dokumen').val('').trigger('change.select2');
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '.btn-action-save', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'npd/add_product',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_add').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					hide_modal('add');

					jQuery('#idnpd').val(response.idnpd);
					jQuery('#hd_idnpd').val(response.idnpd);

					generate_transaction(response.idnpd);
				}
			},
			error: function(response) {}
		});
	});
	
	// generate_transaction(3);
	function generate_transaction(idnpd) {
		jQuery.ajax({
			url: app_url+'npd/get_list_order/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idnpd: idnpd
			},
			success: function(response) {
				jQuery('#table_dokumen tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '#btn_save_npd', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'npd/save_npd',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_npd').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'npd';
				}
				else {
					bootbox.alert(response.err_message);
				}
			},
			error: function(response){}
		});
	});

	jQuery('body').on('click','.btn-edit-dokumen', function() {
		var id = jQuery(this).attr('data-id');

		show_loading();
		jQuery.ajax({
			url: app_url + 'npd/edit_order',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add');

				jQuery('#form_add').find('.detail-dokumen').addClass('hide');
				jQuery('#idnpd_detail').val(id);
				jQuery('#idnpd').val(response.data.idnpd);
				jQuery('#form_add input, #form_add select').not('.x-hidden').val('').trigger('change.select2');

				select_dokumen(response.data.idverification);
			},
			error: function(response) {}
		});
	});

	jQuery('body').on('click','.btn-delete-dokumen', function() {
		var id = jQuery(this).attr('data-id');
		
		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'npd/delete_order',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id: id
					},
					success: function(response) {
						hide_loading();
						if(response.err_code == 0) {
							bootbox.alert(response.message);
							generate_transaction(jQuery('#idnpd').val(), true);
						} else {
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


	var the_id =  "<?php echo $id;?>";
	if (the_id != "") {
		generate_transaction(the_id);

		jQuery.ajax({
			url: app_url + 'npd/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#npd_code').val(response.npd.npd_code);
				jQuery('#iduser_created').val(response.npd.iduser_created);
				jQuery('#user_name').val(response.npd.user_created);
				jQuery('#npd_date_created').val(response.npd.txt_npd_date_created);
			},
			error: function(response) {}
		});
	}