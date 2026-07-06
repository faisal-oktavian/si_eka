<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	// jQuery(document).ready(function() {
	// 	if (is_viewonly == true) {

	// 		jQuery('#form_plan').find('input, select').prop('disabled', true);
    //         jQuery('#btn_add_uraian, #btn_save_purchase_plan').hide();

	// 		setTimeout(function() {
	// 			jQuery('#table_plan').find('button').hide();
	// 		}, 500);		
	// 	}
	// });

    // function reset_form_modal() {
	// 	jQuery('.volume').val('');
	// 	jQuery('#purchase_plan_detail_total').val('');
	// }

	jQuery('#proof_date').datetimepicker({
		format: 'DD-MM-YYYY HH:mm:ss'
	});


    // tambah uraian
    jQuery('body').on('click', '#btn_add_sts', function() {
		show_modal('add_uraian');

		jQuery('#form_add').find('.detail-koderek').addClass('hide');
		jQuery('#idpad_sts_detail').val('');
		jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');	

		// reset_form_modal();
	});

	// Cari Kode Rekening
	$('#idpad_kode_rekening').on('select2:select', function (e) {
		var id = $(this).val();
		console.log('id '+id);

        jQuery('#form_add').find('.detail-koderek').removeClass('hide');

        select_koderek(id);
    });

    function select_koderek(idpad_kode_rekening) {
		jQuery.ajax({
			url: app_url + 'pad_sts/select_kode_rekening',
			type: 'POST', 
			dataType: 'JSON',
			data: {
				idpad_kode_rekening: idpad_kode_rekening,
			},
			success: function(response) {
				jQuery('#form_add').find('.detail-koderek').removeClass('hide');
				
				jQuery('#hd_idpad_kode_rekening').val(response.idpad_kode_rekening);
				jQuery('#sub_kegiatan').val(response.sub_kegiatan);
				jQuery('#kode_rekening').val(response.kode_rekening);
				jQuery('#nama_rekening').val(response.uraian);

				// setTimeout(function() {
				// 	jQuery('.volume').val(thousand_separator_decimal(response.volume));
				// 	jQuery('.unit-price').val(thousand_separator(response.unit_price));
				// 	jQuery('.total-realization-detail').val(thousand_separator(response.total_realization_detail));
				// }, 500);

				jQuery('#idpad_kode_rekening').val('').trigger('change.select2');
				
				// reset_form_modal();
			},
			error: function(response) {}
		});
	}

    // // modal cari uraian
    // jQuery("#search_uraian").select2({
	// 	placeholder: "~ Cari Uraian ~",
	// 	allowClear: true,
	// 	minimumInputLength: 0,
	// 	ajax: { 
	// 	    url: app_url + 'purchase_plan/search_uraian',
	// 	    dataType: "json",
	// 	    delay: 250,
	// 	    data: function(params) {
	// 	      	return {
	// 				term: params.term,
	// 			}
	// 		},
	// 		cache: true
	// 	}
	// });

    // // pilih uraian paket belanja
    // jQuery('body').on('change', '#search_uraian', function() {
	// 	var id = jQuery(this).val();
	// 	console.log('idpaket belanja detail sub '+id);
		
	// 	search_uraian(id);
	// });

    // function search_uraian(idpaket_belanja_detail_sub) {
	// 	jQuery.ajax({
	// 		url: app_url + 'purchase_plan/select_uraian',
	// 		type: 'POST', 
	// 		dataType: 'JSON',
	// 		data: {
	// 			idpaket_belanja_detail_sub: idpaket_belanja_detail_sub
	// 		},
	// 		success: function(response) {
	// 			jQuery('#form_add').find('.detail-paket-belanja').removeClass('hide');
				
	// 			jQuery('#nama_paket_belanja').val(response.nama_paket_belanja);
	// 			jQuery('#idpaket_belanja').val(response.idpaket_belanja);
	// 			jQuery('#idpaket_belanja_detail_sub').val(response.detail_sub_id);
	// 			jQuery('#nama_sub_kategori').val(response.nama_sub_kategori);
	// 			jQuery('#volume_paket_belanja').val(response.volume);
    //             jQuery('.satuan').text(response.nama_satuan);
	// 			jQuery('#harga_satuan').val(response.harga_satuan);
				
    //             jQuery('#purchase_plan_detail_total').val();

    //             jQuery('#search_uraian').val('').trigger('change.select2');
				
	// 			reset_form_modal();
	// 		},
	// 		error: function(response) {}
	// 	});
	// }

	// perhitungan
    jQuery('#form_add').on('keyup', '.direct-receipt, .down-payment, .debt', function() {
		calculate();
	});

	function calculate() {
		var direct_receipt			=  jQuery('#direct_receipt').val() || 0;
		var down_payment			=  jQuery('#down_payment').val() || 0;
		var debt					=  jQuery('#debt').val() || 0;

		direct_receipt 				= parseFloat(remove_separator(direct_receipt)) || 0;
		down_payment  			 	= parseFloat(remove_separator(down_payment)) || 0;
		debt           				= parseFloat(remove_separator(debt)) || 0;
console.log('direct_receipt '+direct_receipt);
		var total_detail = direct_receipt + down_payment + debt;
		console.log('total_detail '+total_detail);
		jQuery('#total_detail').val(thousand_separator_decimal(total_detail));
	}

	function new_remove_separator(value) {
		return String(value)
			.replace(/\./g, '') // hapus pemisah ribuan
			.replace(',', '.'); // ubah koma desimal menjadi titik
	}

	// modal simpan uraian
    jQuery('body').on('click', '.btn-action-save_uraian', function() {
		// show_loading();
		jQuery.ajax({
			url: app_url + 'pad_sts/add_sts',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_add').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					hide_modal('add_uraian');

					jQuery('#idpad_sts').val(response.idpad_sts);
					jQuery('#hd_idpad_sts').val(response.idpad_sts);

					generate_transaction(response.idpad_sts);
				}
			},
			error: function(response) {}
		});
	});

	// generate_transaction(8);
    function generate_transaction(idpad_sts) {
		jQuery.ajax({
			url: app_url+'pad_sts/get_list_detail/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idpad_sts: idpad_sts
			},
			success: function(response) {
				jQuery('#table_sts_detail tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

	// tabel tombol edit rincian
	jQuery('body').on('click','.btn-edit-detail', function() {
		var id = jQuery(this).attr('data-id');

		show_loading();
		jQuery.ajax({
			url: app_url + 'pad_sts/edit_detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				
				hide_loading();

				show_modal('add_uraian');

				jQuery('#form_add').find('.detail-koderek').addClass('hide');
				
				jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change.select2');
				jQuery('#idpad_sts_detail').val(id);
				jQuery('#idpad_sts').val(response.data.idpad_sts);

				select_koderek(response.data.idpad_kode_rekening);
		
				setTimeout(function() {
					if (response.data.direct_receipt != 0) {
						jQuery('#direct_receipt').val(thousand_separator_decimal(response.data.direct_receipt));
					}
					if (response.data.down_payment != 0) {
						jQuery('#down_payment').val(thousand_separator_decimal(response.data.down_payment));
					}
					if (response.data.debt != 0) {
						jQuery('#debt').val(thousand_separator_decimal(response.data.debt));
					}
					if (response.data.total_detail != 0) {
						jQuery('#total_detail').val(thousand_separator_decimal(response.data.total_detail));
					}
					jQuery('#description_detail').val(response.data.description_detail);
				}, 500);
			},
			error: function(response) {}
		});
	});

	// tabel tombol hapus rincian
	jQuery('body').on('click','.btn-delete-detail', function() {
		var id = jQuery(this).attr('data-id');
		
		bootbox.confirm('Apakah anda yakin ingin menghapus data ini?', function(e) {
			show_loading();
			if (e) {
				jQuery.ajax({
					url: app_url + 'pad_sts/delete_detail',
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

							generate_transaction(response.idpad_sts);
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

	// simpan sts
	jQuery('body').on('click', '#btn_save_pad_sts', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'pad_sts/save_sts',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_pad_sts').serialize(),
			success: function(response) {
				hide_loading();
				if (response.err_code == 0) {
					location.href = app_url + 'pad_sts';
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
			url: app_url + 'pad_sts/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery('#hd_idpad_sts').val(response.pad_sts.idpad_sts);
				jQuery('#proof_number').val(response.pad_sts.proof_number);
				jQuery('#iduser_created').val(response.pad_sts.iduser_created);
				jQuery('#user_name').val(response.pad_sts.user_created);
				jQuery('#proof_date').val(response.pad_sts.txt_proof_date);
				jQuery("#idproof_in.select2-ajax").append(new Option(response.pad_sts.uraian, response.pad_sts.idproof_in, true, true)).trigger('change');
				jQuery('#proof_for').val(response.pad_sts.proof_for);
			},
			error: function(response) {}
		});
	}