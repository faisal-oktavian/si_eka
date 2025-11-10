<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	jQuery(document).ready(function() {
		if (is_viewonly == true) {

			jQuery('#form_plan').find('input, select').prop('disabled', true);
            jQuery('#btn_add_uraian, #btn_save_purchase_plan').hide();

			setTimeout(function() {
				jQuery('#table_plan').find('button').hide();
			}, 500);		
		}
	});

    function reset_form_modal() {
		jQuery('.volume').val('');
		jQuery('#purchase_plan_detail_total').val('');
	}

	jQuery('#purchase_plan_date').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});


    // tambah uraian
    jQuery('body').on('click', '#btn_add_uraian', function() {
		var purchase_plan_date = jQuery('#purchase_plan_date').val();

		if (purchase_plan_date == '' || purchase_plan_date == null) {
			bootbox.alert('Tanggal Rencana harus diisi.');
		}
		else {
			show_modal('add_uraian');

			jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
			jQuery('#idtransaction_detail').val('');
			jQuery('#iduraian').html('').val('');
			jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');	
		}
	});

    // modal cari uraian
    jQuery("#search_uraian").select2({
		placeholder: "~ Cari Uraian ~",
		allowClear: true,
		minimumInputLength: 0,
		ajax: { 
		    url: app_url + 'purchase_plan/search_uraian',
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

    // pilih uraian paket belanja
    jQuery('body').on('change', '#search_uraian', function() {
		var id = jQuery(this).val();
		console.log('idpaket belanja detail sub '+id);
		
		search_uraian(id);
	});

    function search_uraian(idpaket_belanja_detail_sub) {
		jQuery.ajax({
			url: app_url + 'purchase_plan/select_uraian',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				idpaket_belanja_detail_sub: idpaket_belanja_detail_sub
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-paket-belanja').removeClass('hide');
				
				jQuery('#nama_paket_belanja').val(response.nama_paket_belanja);
				jQuery('#idpaket_belanja').val(response.idpaket_belanja);
				jQuery('#idpaket_belanja_detail_sub').val(response.detail_sub_id);
				jQuery('#nama_sub_kategori').val(response.nama_sub_kategori);
				jQuery('#volume_paket_belanja').val(response.volume);
                jQuery('.satuan').text(response.nama_satuan);
				jQuery('#harga_satuan').val(response.harga_satuan);
				
                jQuery('#purchase_plan_detail_total').val();

                jQuery('#search_uraian').val('').trigger('change.select2');
				
				reset_form_modal();
			},
			error: function(response) {}
		});
	}

	// perhitungan total anggaran per uraian
    jQuery('#form_add').on('keyup', '.volume', function() {
		var volume			=  jQuery('#volume').val();
		var harga_satuan 	=  jQuery('#harga_satuan').val();

		volume 			    = remove_separator(volume);
		harga_satuan 		= remove_separator(harga_satuan);

  		var jumlah 			= volume * harga_satuan;

		console.log('jumlah '+jumlah);
		jQuery('#purchase_plan_detail_total').val(jumlah);
	});

	// modal simpan uraian
    jQuery('body').on('click', '.btn-action-save_uraian', function() {
		var purchase_plan_date = jQuery('#purchase_plan_date').val();

		if (purchase_plan_date == '' || purchase_plan_date == null) {
			bootbox.alert('Tanggal Rencana harus diisi.');
		}
		else {
			// show_loading();
			jQuery.ajax({
				url: app_url + 'purchase_plan/add_plan',
				type: 'POST',
				dataType: 'JSON',
				data: jQuery('#form_add').serialize() + '&purchase_plan_date=' + purchase_plan_date,
				success: function(response) {
					hide_loading();
					if (response.err_code > 0) {
						bootbox.alert(response.err_message);
					}
					else {
						hide_modal('add_uraian');

						jQuery('#idpurchase_plan').val(response.idpurchase_plan);
						jQuery('#hd_idpurchase_plan').val(response.idpurchase_plan);

						generate_transaction(response.idpurchase_plan);
					}
				},
				error: function(response) {}
			});
		}
	});

	// generate_transaction(2);
    function generate_transaction(idpurchase_plan) {
		jQuery.ajax({
			url: app_url+'purchase_plan/get_list_order/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idpurchase_plan: idpurchase_plan
			},
			success: function(response) {
				jQuery('#table_plan tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	// tabel tombol edit uraian
	jQuery('body').on('click','.btn-edit-order', function() {
		var id = jQuery(this).attr('data-id');

		show_loading();
		jQuery.ajax({
			url: app_url + 'purchase_plan/edit_order',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add_uraian');

				jQuery('#form_add').find('.detail-paket-belanja').addClass('hide');
				
				jQuery('#idpurchase_plan_detail').val(id);
				jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');

				search_uraian(response.data.detail_sub_id);
		
				setTimeout(function() {
					jQuery('#volume').val(response.data.volume);
					jQuery('#purchase_plan_detail_total').val(response.data.purchase_plan_detail_total);
					jQuery('#idpurchase_plan').val(response.data.idpurchase_plan);
				}, 500);
			},
			error: function(response) {}
		});
	});

	// tabel tombol hapus uraian
	jQuery('body').on('click','.btn-delete-order', function() {
		var id = jQuery(this).attr('data-id');
		
		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'purchase_plan/delete_order',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id: id
					},
					success: function(response) {
						hide_loading();
						if (response.err_code == 0) {
							if (response.message != "") {
								bootbox.alert(response.message);
							}

							generate_transaction(response.idpurchase_plan);
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

	// simpan rencana pengadaan
	jQuery('body').on('click', '#btn_save_purchase_plan', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'purchase_plan/save_plan',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_plan').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'purchase_plan';
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
		generate_transaction(the_id);

		jQuery.ajax({
			url: app_url + 'purchase_plan/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#purchase_plan_code').val(response.purchase_plan.purchase_plan_code);
				jQuery('#iduser_created').val(response.purchase_plan.iduser_created);
				jQuery('#user_name').val(response.purchase_plan.user_created);
				jQuery('#purchase_plan_date').val(response.purchase_plan.txt_purchase_plan_date);
			},
			error: function(response) {}
		});
	}