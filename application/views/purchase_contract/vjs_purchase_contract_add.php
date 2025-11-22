<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery(document).ready(function() {
		if (is_viewonly == true) {
			jQuery('#form_contract').find('input, select').prop('disabled', true);
			jQuery('#btn_add_contract, #btn_save_contract').hide();

			setTimeout(function() {
				jQuery('#table_dokumen').find('button').hide();
			}, 500);
		}
	});

	jQuery('#contract_date').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});

	jQuery('body').on('click', '#btn_add_contract', function() {
		
		show_modal('add_contract');

		// jQuery('#form_add').find('#idcontract').val('');
		jQuery('#form_add').find('#idcontract_detail').val('');
		jQuery('.btn-action-save').hide();

		// jQuery('#form_add').find('.detail-dokumen').addClass('hide');
		// jQuery('#form_add input, #form_add select').not('.x-hidden').val('').trigger('change.select2');	
	});

	jQuery("#search_dokumen").select2({
		placeholder: "~ Cari Dokumen ~",
		allowClear: true,
		minimumInputLength: 0,
		ajax: { 
		    url: app_url + 'purchase_contract/search_dokumen',
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
		// console.log('asdasda '+id);
		
		select_dokumen(id);
	});

	function select_dokumen(idpurchase_plan) {
		jQuery.ajax({
			url: app_url + 'purchase_contract/select_dokumen',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				idpurchase_plan: idpurchase_plan
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-dokumen').removeClass('hide');
				
				jQuery('#idpurchase_plan').val(response.idpurchase_plan);
                jQuery('#form_add').find('.detail-dokumen').html(response.data);

				jQuery('#search_dokumen').val('').trigger('change.select2');
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '.btn-select-plan', function() {

		var idpurchase_plan = jQuery(this).attr('data_id');
		console.log(idpurchase_plan);
		// show_loading();
		jQuery.ajax({
			url: app_url + 'purchase_contract/add_save',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_add').serialize() + '&idpurchase_plan=' + idpurchase_plan,
			success: function(response) {
				hide_loading();
				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					hide_modal('add_contract');

					jQuery('#idcontract').val(response.idcontract);
					jQuery('#hd_idcontract').val(response.idcontract);

					generate_transaction(response.idcontract);
				}
			},
			error: function(response) {}
		});
	});

	// jQuery('body').on('click', '.btn-action-save', function() {
	// 	// show_loading();
	// 	jQuery.ajax({
	// 		url: app_url + 'purchase_contract/add_save',
	// 		type: 'POST',
	// 		dataType: 'JSON',
	// 		data: jQuery('#form_add').serialize(),
	// 		success: function(response) {
	// 			hide_loading();
	// 			if (response.err_code > 0) {
	// 				bootbox.alert(response.err_message);
	// 			}
	// 			else {
	// 				hide_modal('add_contract');

	// 				jQuery('#idcontract').val(response.idcontract);
	// 				jQuery('#hd_idcontract').val(response.idcontract);

	// 				generate_transaction(response.idcontract);
	// 			}
	// 		},
	// 		error: function(response) {}
	// 	});
	// });
	
	// generate_transaction(2);
	function generate_transaction(idcontract) {
		jQuery.ajax({
			url: app_url+'purchase_contract/get_list_data/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idcontract: idcontract
			},
			success: function(response) {
				jQuery('#table_dokumen tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '#btn_save_contract', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'purchase_contract/save_contract',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_contract').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'purchase_contract';
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
			url: app_url + 'purchase_contract/edit_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add_contract');

				// jQuery('#form_add').find('.detail-dokumen').addClass('hide');
				jQuery('#idcontract_detail').val(id);
				jQuery('#idcontract').val(response.data.idcontract);
				jQuery('.btn-action-save').hide();
				// jQuery('#form_add input, #form_add select').not('.x-hidden').val('').trigger('change.select2');

				select_dokumen(response.data.idpurchase_plan);
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
					url: app_url + 'purchase_contract/delete_data',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id: id
					},
					success: function(response) {
						hide_loading();
						if(response.err_code == 0) {
							bootbox.alert(response.message);
							generate_transaction(id);
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
			url: app_url + 'purchase_contract/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#contract_code').val(response.contract.contract_code);
				jQuery('#iduser_created').val(response.contract.iduser_created);
				jQuery('#user_name').val(response.contract.user_created);
				jQuery('#contract_date').val(response.contract.txt_contract_date);

                if (response.contract.contract_spt != "") {
                    jQuery('#contract_spt').val(response.contract.contract_spt);
                }
                if (response.contract.contract_invitation_number != "") {
                    jQuery('#contract_invitation_number').val(response.contract.contract_invitation_number);
                }
                if (response.contract.contract_sp != "") {
                    jQuery('#contract_sp').val(response.contract.contract_sp);
                }
                if (response.contract.contract_spk != "") {
                    jQuery('#contract_spk').val(response.contract.contract_spk);
                }
                jQuery('#contract_honor').val(response.contract.contract_honor);
			},
			error: function(response) {}
		});
	}