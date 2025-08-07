<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery(document).ready(function() {
		if (is_viewonly == true) {

			jQuery('#form_realisasi').find('input, select').prop('disabled', true);
            jQuery('#btn_add_paket_belanja, #btn_save_realisasi').hide();

			setTimeout(function() {
				jQuery('#table_realisasi').find('button').hide();
			}, 500);		
		}
	});

	jQuery('#transaction_date').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});

	jQuery('body').on('click', '#btn_add_paket_belanja', function() {
		var transaction_date = jQuery('#transaction_date').val();

		if (transaction_date == '' || transaction_date == null) {
			bootbox.alert('Tanggal realisasi harus diisi.');
		}
		else {
			show_modal('add');

			jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
			jQuery('#idtransaction_detail').val('');
			jQuery('#iduraian').html('').val('');
			jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');	
		}
	});

	jQuery("#search_paket_belanja").select2({
		placeholder: "~ Cari Paket Belanja ~",
		allowClear: true,
		minimumInputLength: 0,
		ajax: { 
		    url: app_url + 'realisasi_anggaran/search_paket_belanja',
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

	jQuery('body').on('change', '#search_paket_belanja', function() {
		var id = jQuery(this).val();
		console.log('asdasda '+id);
		
		seach_paket_belanja(id);
	});

	function seach_paket_belanja(idpaket_belanja) {
		jQuery.ajax({
			url: app_url + 'realisasi_anggaran/select_paket_belanja',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				id: idpaket_belanja
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-paket-belanja').removeClass('hide');
				
				jQuery('#nama_urusan').val(response.nama_urusan);
				jQuery('#nama_bidang_urusan').val(response.nama_bidang_urusan);
				jQuery('#nama_program').val(response.nama_program);
				jQuery('#nama_kegiatan').val(response.nama_kegiatan);
				jQuery('#nama_subkegiatan').val(response.nama_subkegiatan);
				jQuery('#nama_paket_belanja').val(response.nama_paket_belanja);
				jQuery('#idpaket_belanja').val(response.idpaket_belanja);

				jQuery('#search_paket_belanja').val('').trigger('change.select2');

				jQuery('#iduraian').html("<option value=''>~ Cari Uraian ~</option>");
				jQuery('#iduraian').val('').trigger('change');
				
				reset_form_modal();
			},
			error: function(response) {}
		});
	}

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

	function reset_form_modal() {
		jQuery('.volume').val('');
		jQuery('#laki').val('');
		jQuery('#perempuan').val('');
		jQuery('.harga-satuan').val('');
		jQuery('.ppn').val('');
		jQuery('.pph').val('');
		jQuery('#total').val('');
		jQuery('#transaction_description').val('');
	}

	function get_validate_gender(iduraian) {
		jQuery.ajax({
			url : app_url + 'realisasi_anggaran/get_validate_gender?id='+iduraian,
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
			url : app_url + 'realisasi_anggaran/get_validate_description?id='+iduraian,
			method : 'get',
			dataType : 'json',
			success : function(res){
				console.log(res);

				jQuery('#transaction_description').val('');
				
				if (res == 1) {
					jQuery('.description').removeClass('hide');
				}
				else {
					jQuery('.description').addClass('hide');
				}
			}
		});
	}

	function get_validate_room(iduraian) {
		jQuery.ajax({
			url : app_url + 'realisasi_anggaran/get_validate_room?id='+iduraian,
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
			url : app_url + 'realisasi_anggaran/get_validate_training?id='+iduraian,
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

	jQuery('#form_add').on('keyup', '.volume, .harga-satuan, .ppn, .pph', function() {
		var volume			=  jQuery('#volume').val();
		var harga_satuan 	=  jQuery('#harga_satuan').val();
		var ppn 			=  jQuery('#ppn').val() || 0;
		var pph 			=  jQuery('#pph').val() || 0;

		volume 			= remove_separator(volume);
		harga_satuan 	= remove_separator(harga_satuan);
		ppn 			= remove_separator(ppn);
		pph 			= remove_separator(pph);

  		var total = (parseFloat(volume) * parseFloat(harga_satuan)) + parseFloat(ppn) - parseFloat(pph);

		console.log('total '+total);
		jQuery('#total').val(thousand_separator(total));
	});

	jQuery('body').on('click', '.btn-action-save', function() {
		var transaction_date = jQuery('#transaction_date').val();

		if (transaction_date == '' || transaction_date == null) {
			bootbox.alert('Tanggal realisasi harus diisi.');
		}
		else {
			// show_loading();
			jQuery.ajax({
				url: app_url + 'realisasi_anggaran/add_product',
				type: 'POST',
				dataType: 'JSON',
				data: jQuery('#form_add').serialize() + '&transaction_date=' + transaction_date,
				success: function(response) {
					hide_loading();
					if (response.err_code > 0) {
						bootbox.alert(response.err_message);
					}
					else {
						hide_modal('add');

						jQuery('#idtransaction').val(response.idtransaction);
						jQuery('#hd_idtransaction').val(response.idtransaction);

						generate_transaction(response.idtransaction);
					}
				},
				error: function(response) {}
			});
		}
	});
	
	// generate_transaction(6);
	function generate_transaction(idtransaction) {
		jQuery.ajax({
			url: app_url+'realisasi_anggaran/get_list_order/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idtransaction: idtransaction
			},
			success: function(response) {
				jQuery('#table_realisasi tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	jQuery('body').on('click', '#btn_save_realisasi', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'realisasi_anggaran/save_realisasi',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_realisasi').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'realisasi_anggaran';
				}
				else {
					bootbox.alert(response.err_message);
				}
			},
			error: function(response){}
		});
	});

	jQuery('body').on('click','.btn-edit-order', function() {
		var id = jQuery(this).attr('data-id');

		show_loading();
		jQuery.ajax({
			url: app_url + 'realisasi_anggaran/edit_order',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add');

				jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
				jQuery('#idtransaction_detail').val(id);
				jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');

				seach_paket_belanja(response.data.idpaket_belanja);
				
				setTimeout(function() {
					jQuery('#penyedia').val(response.data.penyedia);
					jQuery('#iduraian').val(response.data.iduraian);
					jQuery('#volume').val(response.data.volume);
					jQuery('#harga_satuan').val(thousand_separator(response.data.harga_satuan));
					jQuery('#ppn').val(thousand_separator(response.data.ppn));
					jQuery('#pph').val(thousand_separator(response.data.pph));
					jQuery('#total').val(thousand_separator(response.data.total));
					
					get_validate_gender(response.data.iduraian);
					get_validate_description(response.data.iduraian);
					get_validate_room(response.data.iduraian);
					get_validate_name_training(response.data.iduraian);

					setTimeout(function() {
						jQuery('#laki').val(response.data.laki);
						jQuery('#perempuan').val(response.data.perempuan);
						jQuery('#transaction_description').val(response.data.transaction_description);
						jQuery('#idruang').val(response.data.idruang);
						jQuery('#name_training').val(response.data.name_training);
					}, 500);
				}, 1000);
			},
			error: function(response) {}
		});
	});

	jQuery('body').on('click','.btn-delete-order', function() {
		var id = jQuery(this).attr('data-id');
		
		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'realisasi_anggaran/delete_order',
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
			url: app_url + 'realisasi_anggaran/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#transaction_code').val(response.transaction.transaction_code);
				jQuery('#iduser_created').val(response.transaction.iduser_created);
				jQuery('#user_name').val(response.transaction.user_created);
				jQuery('#transaction_date').val(response.transaction.txt_transaction_date);
			},
			error: function(response) {}
		});
	}