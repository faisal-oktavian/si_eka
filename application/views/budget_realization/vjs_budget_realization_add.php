<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery(document).ready(function() {
		if (is_viewonly == true) {

			jQuery('#form_realization').find('input, select').prop('disabled', true);
            jQuery('#btn_add_contract, #btn_save_realization').hide();

			setTimeout(function() {
				jQuery('#table_realization').find('button').hide();
			}, 500);		
		}
	});

	jQuery('#realization_date').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});

	jQuery('body').on('click', '#btn_add_contract', function() {
		var realization_date = jQuery('#realization_date').val();

		if (realization_date == '' || realization_date == null) {
			bootbox.alert('Tanggal realisasi harus diisi.');
		}
		else {
			show_modal('add_realization');

			jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
			jQuery('#idbudget_realization_detail').val('');
			jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');	
		}
	});

	jQuery('body').on('click', '.btn-show-contract', function() {
		show_modal('select_contract');

		jQuery('#form_select_contract').find('.btn-action-save').hide();
	});

	jQuery('body').on('click', '.btn-select-contract', function() {
		hide_modal('select_contract');

		var idcontract = jQuery(this).attr('data_id');
		var contract_code = jQuery(this).attr('data_code');

		jQuery('#form_add').find("#idcontract").append(new Option(contract_code, idcontract, true, true)).trigger('change');
	});

	jQuery('body').on('change', '#idcontract', function() {
		jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
	});

    $('#idpaket_belanja_detail_sub').on('select2:select', function (e) {
        var data = e.params.data;
        console.log('Semua data terpilih:', data);

        // ambil semua data_ yang ada
        var extraData = {};
        $.each(data, function(key, val) {
            if (key.indexOf('data_') === 0) {
                extraData[key] = val;
            }
        });

        console.log('Data tambahan:', extraData);

        if (extraData.data_idcontract) {
            $('#data_idcontract').val(extraData.data_idcontract);
        }
        if (extraData.data_idcontract_detail) {
            $('#data_idcontract_detail').val(extraData.data_idcontract_detail);
        }
        if (extraData.data_idpurchase_plan) {
            $('#data_idpurchase_plan').val(extraData.data_idpurchase_plan);
        }
        if (extraData.data_idpurchase_plan_detail) {
            $('#data_idpurchase_plan_detail').val(extraData.data_idpurchase_plan_detail);
        }
        if (extraData.data_idpaket_belanja) {
            $('#data_idpaket_belanja').val(extraData.data_idpaket_belanja);
        }
        if (extraData.data_idpaket_belanja_detail_sub) {
            $('#data_idpaket_belanja_detail_sub').val(extraData.data_idpaket_belanja_detail_sub);
        }
        if (extraData.data_idsub_kategori) {
            $('#data_idsub_kategori').val(extraData.data_idsub_kategori);
        }

        jQuery('#form_add').find('.detail-paket-belanja').removeClass('hide');

        search_uraian(extraData.data_idpaket_belanja, extraData.data_idsub_kategori, extraData.data_idcontract_detail);
    });

    function search_uraian(idpaket_belanja, idsub_kategori, idcontract_detail) {
		jQuery.ajax({
			url: app_url + 'budget_realization/select_paket_belanja',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				idpaket_belanja: idpaket_belanja,
				idsub_kategori: idsub_kategori,
				idcontract_detail: idcontract_detail,
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-paket-belanja').removeClass('hide');
				
				jQuery('#nama_urusan').val(response.nama_urusan);
				jQuery('#nama_bidang_urusan').val(response.nama_bidang_urusan);
				jQuery('#nama_program').val(response.nama_program);
				jQuery('#nama_kegiatan').val(response.nama_kegiatan);
				jQuery('#nama_subkegiatan').val(response.nama_subkegiatan);
				jQuery('#nama_paket_belanja').val(response.nama_paket_belanja);
				jQuery('#nama_sub_kategori').val(response.nama_sub_kategori);

                get_validate_gender(response.idsub_kategori);
                get_validate_description(response.idsub_kategori);
                get_validate_room(response.idsub_kategori);
                get_validate_name_training(response.idsub_kategori);

				setTimeout(function() {
					jQuery('.volume').val(thousand_separator(response.volume));
					jQuery('.unit-price').val(thousand_separator(response.unit_price));
					jQuery('.total-realization-detail').val(thousand_separator(response.total_realization_detail));
				}, 500);

				jQuery('#search_paket_belanja').val('').trigger('change.select2');
				
				reset_form_modal();
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '.btn-action-save', function() {
		var realization_date = jQuery('#realization_date').val();

		if (realization_date == '' || realization_date == null) {
			bootbox.alert('Tanggal realisasi harus diisi.');
		}
		else {
			// show_loading();
			jQuery.ajax({
				url: app_url + 'budget_realization/add_realization',
				type: 'POST',
				dataType: 'JSON',
				data: jQuery('#form_add').serialize() + '&realization_date=' + realization_date,
				success: function(response) {
					hide_loading();
					if (response.err_code > 0) {
						bootbox.alert(response.err_message);
					}
					else {
						hide_modal('add_realization');

						jQuery('#idbudget_realization').val(response.idbudget_realization);
						jQuery('#hd_idbudget_realization').val(response.idbudget_realization);

						generate_transaction(response.idbudget_realization);
					}
				},
				error: function(response) {}
			});
		}
	});

	jQuery('body').on('click','.btn-edit-order', function() {
		var idrealization_detail = jQuery(this).attr('data-id');

		show_loading();
		jQuery.ajax({
			url: app_url + 'budget_realization/edit_order',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idrealization_detail: idrealization_detail
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add_realization');

				// reset form
				jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
				jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');

				// set value
				jQuery('#idbudget_realization_detail').val(idrealization_detail);
				jQuery('#idbudget_realization').val(response.data.idbudget_realization);
				jQuery("#idcontract").append(new Option(response.data.ajax_idcontract, response.data.idcontract, true, true)).trigger('change.select2');
				jQuery("#idpaket_belanja_detail_sub").append(new Option(response.data.ajax_idpaket_belanja_detail_sub, response.data.idpaket_belanja_detail_sub, true, true)).trigger('change.select2');

				search_uraian(response.data.idpaket_belanja, response.data.idsub_kategori, response.data.idcontract_detail);
				
				$('#data_idcontract').val(response.data.idcontract);
				$('#data_idcontract_detail').val(response.data.idcontract_detail);
				$('#data_idpurchase_plan').val(response.data.idpurchase_plan);
				$('#data_idpurchase_plan_detail').val(response.data.idpurchase_plan_detail);
				$('#data_idpaket_belanja').val(response.data.idpaket_belanja);
				$('#data_idpaket_belanja_detail_sub').val(response.data.idpaket_belanja_detail_sub);
				$('#data_idsub_kategori').val(response.data.idsub_kategori);

				jQuery('#provider').val(response.data.provider);
				jQuery("#idruang").append(new Option(response.data.ajax_idruang, response.data.idruang, true, true)).trigger('change.select2');
				jQuery('#training_name').val(response.data.training_name);

				get_validate_gender(response.data.idsub_kategori);
				get_validate_description(response.data.idsub_kategori);
				get_validate_room(response.data.idsub_kategori);
				get_validate_name_training(response.data.idsub_kategori);

				setTimeout(function() {
					jQuery('#male').val(response.data.male);
					jQuery('#female').val(response.data.female);
					jQuery('#realization_detail_description').val(response.data.realization_detail_description);
					jQuery('#idruang').val(response.data.idruang);
					jQuery('#name_training').val(response.data.name_training);

					jQuery('#volume').val(response.data.volume);
					jQuery('#unit_price').val(thousand_separator(response.data.unit_price));
					jQuery('#ppn').val(thousand_separator(response.data.ppn));
					jQuery('#pph').val(thousand_separator(response.data.pph));
					jQuery('#total_realization_detail').val(thousand_separator(response.data.total_realization_detail));
				}, 700);
			},
			error: function(response) {}
		});
	});

	jQuery('body').on('click','.btn-delete-order', function() {
		var idrealization_detail = jQuery(this).attr('data-id');
		
		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'budget_realization/delete_order',
					type: 'POST',
					dataType: 'JSON',
					data: {
						idrealization_detail: idrealization_detail
					},
					success: function(response) {
						hide_loading();
						if(response.err_code == 0) {
							bootbox.alert(response.message);
							generate_transaction(response.idbudget_realization);
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

	jQuery('body').on('click', '#btn_save_realization', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'budget_realization/save_realization',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_realization').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'budget_realization';
				}
				else {
					bootbox.alert(response.err_message);
				}
			},
			error: function(response){}
		});
	});



	var the_id =  "<?php echo $id;?>";
	if (the_id != "") {
		generate_transaction(the_id);

		jQuery.ajax({
			url: app_url + 'budget_realization/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idbudget_realization: the_id
			},
			success: function(response) {
				jQuery('#realization_code').val(response.data.realization_code);
				jQuery('#iduser_created').val(response.data.iduser_created);
				jQuery('#user_name').val(response.data.user_created);
				jQuery('#realization_date').val(response.data.txt_realization_date);
			},
			error: function(response) {}
		});
	}

	// generate_transaction(2);
	function generate_transaction(idbudget_realization) {
		jQuery.ajax({
			url: app_url+'budget_realization/get_list_data/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idbudget_realization: idbudget_realization
			},
			success: function(response) {
				jQuery('#table_realization tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	jQuery('#form_add').on('keyup', '.volume, .unit-price, .ppn, .pph', function() {
		calculate();
	});

	function calculate() {
		var volume			=  jQuery('#volume').val();
		var unit_price 		=  jQuery('#unit_price').val();
		var ppn 			=  jQuery('#ppn').val() || 0;
		var pph 			=  jQuery('#pph').val() || 0;

		volume 			= remove_separator(volume);
		unit_price 		= remove_separator(unit_price);
		ppn 			= remove_separator(ppn);
		pph 			= remove_separator(pph);

  		var total = (parseFloat(volume) * parseFloat(unit_price)) + parseFloat(ppn) - parseFloat(pph);

		console.log('total '+total);
		jQuery('.total-realization-detail').val(thousand_separator(total));
	}

	function reset_form_modal() {
		jQuery('.male').val('');
		jQuery('.female').val('');
		jQuery('.ppn').val('');
		jQuery('.pph').val('');
		jQuery('.total-realization-detail').val('');
		jQuery('.realization-detail-description').val('');
	}

	function get_validate_gender(iduraian) {
		jQuery.ajax({
			url : app_url + 'budget_realization/get_validate_gender?id='+iduraian,
			method : 'get',
			dataType : 'json',
			success : function(res){
				console.log(res);

				jQuery('#laki').val('');
				jQuery('#perempuan').val('');
				
				if (res == 1) {
					jQuery('.gender').removeClass('hide');
				}
				else {
					jQuery('.gender').addClass('hide');
				}
			}
		});
	}

	function get_validate_description(iduraian) {
		jQuery.ajax({
			url : app_url + 'budget_realization/get_validate_description?id='+iduraian,
			method : 'get',
			dataType : 'json',
			success : function(res){
				console.log(res);

				jQuery('#transaction_description').val('');
				
				// if (res == 1) {
				// 	jQuery('.description').removeClass('hide');
				// }
				// else {
				// 	jQuery('.description').addClass('hide');
				// }
			}
		});
	}

	function get_validate_room(iduraian) {
		jQuery.ajax({
			url : app_url + 'budget_realization/get_validate_room?id='+iduraian,
			method : 'get',
			dataType : 'json',
			success : function(res){
				console.log(res);

				jQuery('#idruang').val('').trigger('change.select2');
				
				if (res == 1) {
					jQuery('.room').removeClass('hide');
				}
				else {
					jQuery('.room').addClass('hide');
				}
			}
		});
	}

	function get_validate_name_training(iduraian) {
		jQuery.ajax({
			url : app_url + 'budget_realization/get_validate_training?id='+iduraian,
			method : 'get',
			dataType : 'json',
			success : function(res){
				console.log(res);

				jQuery('#name_training').val('');
				
				if (res == 1) {
					jQuery('.training').removeClass('hide');
				}
				else {
					jQuery('.training').addClass('hide');
				}
			}
		});
	}

    ////////////////////////////////////////

	jQuery("#search_contract_code").select2({
		placeholder: "~ Cari Nomor Kontrak Pengadaan ~",
		allowClear: true,
		minimumInputLength: 0,
		ajax: { 
		    url: app_url + 'budget_realization/search_contract_code',
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

    jQuery('body').on('change', '.search-contract-code', function() {
		var idcontract = jQuery(this).val();
		
        jQuery.ajax({
			url: app_url + 'budget_realization/select_contract_code',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				idcontract: idcontract
			},
			success: function(response) {
				jQuery('#form_add').find('.search-paket-belanja').removeClass('hide');
				
				jQuery('#idcontract').val(response.idcontract);
				jQuery('#contract_code').val(response.contract_code);
				
				jQuery('#search_contract_code').val('').trigger('change.select2');

                // 
                jQuery("#search_paket_belanja").select2({
                    placeholder: "~ Cari Paket Belanja ~",
                    allowClear: true,
                    minimumInputLength: 0,
                    ajax: { 
                        url: app_url + 'budget_realization/search_paket_belanja',
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
			},
			error: function(response) {}
		});
	});


	jQuery('body').on('change', '#search_paket_belanja', function() {
		var id = jQuery(this).val();
		console.log('asdasda '+id);
		
		seach_paket_belanja(id);
	});

	jQuery('body').on('change', '#iduraian', function() {
		var idpaket_belanja = jQuery('#idpaket_belanja').val();
		var iduraian = jQuery(this).val();

		reset_form_modal();
		
		if (idpaket_belanja != "") {
			jQuery.ajax({
				url: app_url + 'realisasi_anggaran/get_paket_belanja_detail_sub_parent',
				type: 'POST',
				dataType: 'JSON',
				data: {
					idpaket_belanja: idpaket_belanja
				},
				success: function(response) {
					var helper_iduraian = jQuery('#helper_iduraian').val();

					// Reset option sebelumnya
					jQuery('#iduraian').html('<option value="">--Pilih Uraian--</option>');

					jQuery(response.results).each(function(adata, bdata) {
						console.log(bdata);
						var opt = "<option value='"+bdata.iduraian+"' data-id='"+bdata.iduraian+"' class='input-gender'>"+bdata.nama_uraian+"</option>";

						jQuery('#iduraian').append(opt);
					});
					if (iduraian != undefined) {
						jQuery('#iduraian').val(iduraian)
					}
					else {
						console.log('jos');
					}

					if (iduraian != "") {
						get_validate_gender(iduraian);
						get_validate_description(iduraian);
						get_validate_room(iduraian);
						get_validate_name_training(iduraian);
					}
				},
				error: function(response) {}
			});			
		}
	});