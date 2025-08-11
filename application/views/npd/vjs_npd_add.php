<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery(document).ready(function() {
		if (is_viewonly == true) {
			jQuery('.form-control, .btn-primary').attr('readonly', true);
			// jQuery('#btn_preview').attr('disabled', false);
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

		jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
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
		
		search_dokumen(id);
	});

	function search_dokumen(idtransaction) {
		jQuery.ajax({
			url: app_url + 'verifikasi_dokumen/select_paket_belanja',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				id: idtransaction
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-paket-belanja').removeClass('hide');
				
				jQuery('#idtransaction').val(response.idtransaction);
				jQuery('#transaction_code').val(response.transaction_code);
				jQuery('#nama_paket_belanja').val(response.nama_paket_belanja);
				jQuery('#total_realisasi').val(thousand_separator(response.total_realisasi));

				jQuery('#search_realisasi_anggaran').val('').trigger('change.select2');
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '.btn-action-save', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'verifikasi_dokumen/add_product',
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

					jQuery('#idverification').val(response.idverification);
					jQuery('#hd_idverification').val(response.idverification);

					generate_transaction(response.idverification);
				}
			},
			error: function(response) {}
		});
	});
	
	// generate_transaction(17);
	function generate_transaction(idverification) {
		jQuery.ajax({
			url: app_url+'verifikasi_dokumen/get_list_order/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idverification: idverification
			},
			success: function(response) {
				jQuery('#table_realisasi tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '#btn_save_verifikasi', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'verifikasi_dokumen/save_verifikasi',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_verifikasi').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'verifikasi_dokumen';
				}
				else {
					bootbox.alert(response.err_message);
				}
			},
			error: function(response){}
		});
	});

	jQuery('body').on('click','.btn-edit-paket-belanja', function() {
		var id = jQuery(this).attr('data-id');

		show_loading();
		jQuery.ajax({
			url: app_url + 'verifikasi_dokumen/edit_order',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add');

				jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
				jQuery('#idverification_detail').val(id);
				jQuery('#idverification').val(response.data.idverification);
				jQuery('#form_add input, #form_add select').not('.x-hidden').val('').trigger('change.select2');

				search_realisasi_anggaran(response.data.idtransaction);
			},
			error: function(response) {}
		});
	});

	jQuery('body').on('click','.btn-delete-paket-belanja', function() {
		var id = jQuery(this).attr('data-id');
		
		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'verifikasi_dokumen/delete_order',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id: id
					},
					success: function(response) {
						hide_loading();
						if(response.err_code == 0) {
							generate_transaction(jQuery('#idtransaction').val(), true);
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
			url: app_url + 'verifikasi_dokumen/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#verification_code').val(response.verification.verification_code);
				jQuery('#iduser_created').val(response.verification.iduser_created);
				jQuery('#user_name').val(response.verification.user_created);
				jQuery('#verification_date_created').val(response.verification.txt_verification_date_created);
			},
			error: function(response) {}
		});
	}