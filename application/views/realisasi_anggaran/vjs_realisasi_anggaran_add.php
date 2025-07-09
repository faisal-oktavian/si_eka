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

					jQuery(response.results).each(function(adata, bdata) {
						console.log(bdata);
						var opt = "<option value='"+bdata.iduraian+"' data-id='"+bdata.iduraian+"' class='input-gender'>"+bdata.nama_uraian+"</option>";

						jQuery('#iduraian').append(opt);
					});
					if (helper_iduraian != undefined) {
						// jQuery('#iduraian').val(helper_iduraian).trigger('change');
					}
					else {
						console.log('jos');
					}

					if (iduraian != "") {
						get_validate_gender(iduraian);
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
					jQuery('#transaction_description').val(response.data.transaction_description);

					get_validate_gender(response.data.iduraian);

					setTimeout(function() {
						jQuery('#laki').val(response.data.laki);
						jQuery('#perempuan').val(response.data.perempuan);
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


	///////////////////////////////////
	//// Cek
	///////////////////////////////////
	jQuery('body').on('change', '#idproduct', function() {
		jQuery('.container-detail').hide();
		var is_edit = jQuery('#is_edit').val();
		var check = true;
		
		if (is_edit != 0 && jQuery('#idproduct').val() == null) {
			check = false;
		}
		if (check) {
			jQuery.ajax({
				url: app_url + 'pos/get_detail_product',
				type: 'POST',
				dataType: 'JSON',
				data: {
					idproduct: jQuery("#idproduct").val(),
					is_edit: jQuery('#is_edit').val(),
					idtransaction_detail: jQuery('#idtransaction_detail').val()
				},
				success: function(response) {
					jQuery('.container-detail').show();
					jQuery('.container-detail').html(response.data);

					if (response.product_type == "DESAIN") {
						$('.non-desain').hide();
						$('.desain').show();
					}
					else{
						$('.non-desain').show();
						$('.desain').hide();
					}
				},
				error: function(response) {}
			});			
		}
	});

	jQuery('body').on('change', '#length', function() {
		var value = remove_separator(jQuery(this).val());
		var length = remove_separator(jQuery('#material_length').val());

		if(parseFloat(value) > parseFloat(length)) {
			if(length.length > 0) {
				bootbox.alert('Lebar bahan tidak boleh lebih kecil dari ukuran aslinya.');
				jQuery('#material_length').val('');
			}
		}
	});

	jQuery('body').on('change', '#width', function() {
		var value = remove_separator(jQuery(this).val());
		var width = remove_separator(jQuery('#material_width').val());

		if(parseFloat(value) > parseFloat(width)) {
			if(width.length > 0) {
				bootbox.alert('Lebar bahan tidak boleh lebih kecil dari ukuran aslinya.');
				jQuery('#material_width').val('');
			}
		}
	});

	jQuery('body').on('change', '#material_length', function() {
		var value = remove_separator(jQuery(this).val());
		var length = remove_separator(jQuery('#length').val());

		if(parseFloat(value) < parseFloat(length)) {
			if(jQuery('#material_length').val().length > 0) {
				bootbox.alert('Lebar bahan tidak boleh lebih kecil dari ukuran aslinya.');
				jQuery(this).val('');
			}
		}
	});

	jQuery('body').on('change', '#material_width', function() {
		var value = remove_separator(jQuery(this).val());
		var width = remove_separator(jQuery('#width').val());

		if(parseFloat(value) < parseFloat(width)) {
			if(jQuery('#material_width').val().length > 0) {
				bootbox.alert('Lebar bahan tidak boleh lebih kecil dari ukuran aslinya.');
				jQuery(this).val('');
			}
		}
	});

	jQuery('body').on('click', '#btn_save_onthespot', function() {
		// show_loading();

		jQuery.ajax({
			url: app_url + 'pos/validation_data_customer',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_onthespot').serialize(),
			success: function(response) {
				// hide_loading();
				
				if (!response.success && response.data.type == 'confirm') {
					bootbox.confirm(response.data.message, function(e) {
						show_loading();
						if (e) {
							jQuery.ajax({
								url: app_url + 'pos/save_onthespot',
								type: 'POST',
								dataType: 'JSON',
								data: jQuery('#form_onthespot').serialize(),
								success: function(response) {
									hide_loading();
									if (response.err_code == 0) {
										location.href = app_url + 'pos';
									}
									else {
										bootbox.alert(response.err_message);
									}
								},
								error: function(response){}
							});
						}else{
							hide_loading();
						}
					})
				}
				else {
					jQuery.ajax({
						url: app_url + 'pos/save_onthespot',
						type: 'POST',
						dataType: 'JSON',
						data: jQuery('#form_onthespot').serialize(),
						success: function(response) {
							hide_loading();
							if (response.err_code == 0) {
								location.href = app_url + 'pos';
							}
							else {
								bootbox.alert(response.err_message);
							}
						},
						error: function(response){}
					});
				}
			},
				error: function(response){}
		});
	});



	jQuery('body').on('change', '#idprovince', function() {
		need_refresh();
		generate_city();
	});
	function generate_city() {
		jQuery('#idcity').html("<option value=''>Pilih Kota / Kabupaten</option>");
		jQuery('#idcity').val('').trigger('change');
		jQuery('#iddistrict').html("<option value=''>Pilih Kecamatan</option>");
		jQuery('#iddistrict').val('').trigger('change');
		if (jQuery('#idprovince').val() != "") {
			jQuery.ajax({
				url: app_url + 'data/get_city',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id: jQuery('#idprovince').val()
				},
				success: function(response) {
					var helper_city = jQuery('#helper_idcity').val();
					jQuery(response).each(function(adata, bdata) {
						// var opt = "<option value='"+bdata.city_id+"'>"+bdata.type+" "+bdata.city_name+"</option>";
						var opt = "<option value='"+bdata.city_id+"'>"+bdata.city_name+"</option>";
						jQuery('#idcity').append(opt)
					});
					if (helper_city != undefined) {
						jQuery('#idcity').val(helper_city).trigger('change');
					}
				},
				error: function(response) {}
			});
		}		
	}

	jQuery('body').on('change', '#idcity', function() {
		need_refresh();
		generate_district();
	});

	function generate_district() {
		jQuery('#iddistrict').html("<option value=''>Pilih Kecamatan</option>");
		jQuery('#iddistrict').val('').trigger('change');
		if (jQuery('#idcity').val() != "") {
			jQuery.ajax({
				url: app_url + 'data/get_district',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id: jQuery('#idcity').val()
				},
				success: function(response) {
					var helper_district = jQuery('#helper_iddistrict').val();
					jQuery(response).each(function(adata, bdata) {
						var opt = "<option value='"+bdata.subdistrict_id+"'>"+bdata.subdistrict_name+"</option>";
						jQuery('#iddistrict').append(opt)
					});
					if (helper_district != undefined) {
						jQuery('#iddistrict').val(helper_district).trigger('change');
					}
					else {
						console.log('jos');
					}
				},
				error: function(response) {}
			});			
		}		
	}

	jQuery('body').on('change', '#iddistrict', function() {
		var iddistrict = $(this).val();
		if (iddistrict > 0) {
			console.log('district => '+iddistrict);
			generate_delivery();
			generate_transaction(jQuery('#idtransaction').val());			
		}
	});

	function need_refresh() {
		var nr_idprovince = $('#idprovince').val();
		var nr_idcity = $('#idcity').val();
		var nr_iddistrict = $('#iddistrict').val();

		//memperbaharui data ketika ganti provinsi dalam keadaan kota & kecamatan terisi
		if (nr_idcity > 0 && nr_iddistrict > 0) {
			$('#iddistrict').val('');
			generate_delivery();
			generate_transaction(jQuery('#idtransaction').val());			
		}

		//memperbaharui data ketika ganti kota dalam keadaan provinsi & kecamatan terisi
		if (nr_idprovince > 0 && nr_iddistrict > 0) {
			$('#iddistrict').val('');
			generate_delivery();
			generate_transaction(jQuery('#idtransaction').val());			
		}
	}

	function generate_delivery() {
		show_loading();
		jQuery.ajax({
			url: app_url + 'pos/generate_delivery',
			type: 'POST',
			dataType: 'JSON',
			data: {
				iddistrict: jQuery('#iddistrict').val(),
				idtransaction: jQuery("#idtransaction").val(),
				idoutlet: jQuery('#idoutlet').val(),
				is_edit_transaction: is_edit_transaction
			},
			success: function(response) {
				hide_loading();
				jQuery('.container-delivery').html(response.data);
				var delivery = jQuery('#helper_delivery').val();
				jQuery('input[value="'+delivery+'"]').prop('checked', true);
				if (jQuery('#helper_iddistrict').val() != "" && jQuery('#iddistrict').val() != "") {
					is_edit_transaction = 0;
					jQuery('#helper_delivery').val('');
				}

				generate_transaction(jQuery('#idtransaction').val(), false);
			},
			error: function(response) {
				hide_loading();
				bootbox.alert('Ada kesalahan saat mendapatkan data pengiriman');
			}
		});
	}

	jQuery('body').on('change', '#taken_sent', function() {
		taken_sent();
	});
	taken_sent();
	function taken_sent() {
		var the = jQuery('#taken_sent').val();
		if (the == 'DIAMBIL') {
			var ts_idprovince = $('#idprovince').val();
			jQuery('.container-sent').hide();
			if (ts_idprovince > 0) {
				jQuery('#idprovince').val('').trigger('change');				
			}
		}
		else {
			jQuery('.container-sent').show();
			if (jQuery('#idprovince').find('option').length == 1) {
				call_province();
			}
		}
	}

	function call_province() {
		jQuery.ajax({
			url: app_url + 'data/get_province',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: 'ok'
			},
			success: function(response) {
				jQuery('#idprovince').html("<option value=''>Pilih Provinsi</option>");
				jQuery(response).each(function(adata, bdata) {
					jQuery("#idprovince").append("<option value='"+bdata.province_id+"'>"+bdata.province+"</option>");
				});
			},
			errors: function(response) {}
		});
	}

	jQuery('body').on('click', '.radio-delivery', function() {
		show_loading();
		var price = jQuery(this).attr('data-price');
		var weight = jQuery(this).attr('data-weight');
		var idtransaction = jQuery('#idtransaction').val();
		var delivery_code = jQuery('.radio-delivery:checked').val();
		var iddistrict = jQuery('#iddistrict').val();
		var idcity = jQuery('#idcity').val();
		var idprovince = jQuery('#idprovince').val();
		var address = jQuery('#address').val();

		jQuery.ajax({
			url: app_url + 'pos/update_delivery',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idtransaction: idtransaction,
				price: price,
				weight: weight,
				delivery_code: delivery_code,
				iddistrict: iddistrict,
				idcity: idcity,
				idprovince: idprovince,
				address: address,
				idoutlet: jQuery('#idoutlet').val()
			},
			success: function(response) {
				hide_loading();
				generate_transaction(idtransaction, false);
			},
			error: function(response) {
				hide_loading();
				bootbox.alert('Ada kesalahan saat memilih pengiriman');
			}
		});
	});

	function search_product() {
		jQuery.ajax({
			url: app_url + 'pos/search_product',
			type: 'POST',
			dataType: 'JSON',
			data: {
				app_name: jQuery('#app_name').val()
			},
			success: function(response) {
				var resp = '';
				jQuery(response.data).each(function(adata, bdata) {
					resp += "<option data-idkategori='"+bdata.idkategori+"' data-idsubkategori='"+bdata.idsubkategori+"' value='"+bdata.idproduk+"'>"+bdata.nama_produk+"</option>";
				});
				jQuery('#search_product').html(resp);
			},
			error: function(response) {

			}
		});
	}

	// $('body').on('change','#customer_handphone', function(){
	// 	var q = $(this).val();
	// 	get_member_data(q);
	// });

	jQuery('#customer_handphone').on('keyup focus', function() {
        var keyword = jQuery('#customer_handphone').val();
		var q = $(this).val();
		$('#customer_name').prop('readonly',false);
		// $('#customer_email').prop('readonly',false);
        get_member_data(q);
    });

    // tambah
	let isInMember;
	jQuery('.autocomplete-number').on('mouseover', function(){
		isInMember = true;
	});

	jQuery('.autocomplete-number').on('mouseleave', function(){
		isInMember = false;
	});

	jQuery('#customer_handphone').on('blur', function(){
		if(!isInMember){
			$('.autocomplete-number ul').html('');
			$('.autocomplete-number').hide();
			// get_member_data($(this).val());
		}
	});

	// jQuery('#customer_handphone').on('keydown', function(e){
	// 	if(e.which == 9){
	// 		$('.autocomplete-number ul').html('');
	// 		$('.autocomplete-number').hide();
	// 		get_member_data($(this).val());
	// 	}
	// });

	let getMemberProcess = null;
	function get_member_data(q) {
		if(q == ''){
				$('.autocomplete-number ul').html('');
				$('.autocomplete-number').hide();
				$('#idmember').val('');
		}else{
			if(getMemberProcess != null){
				getMemberProcess.abort();
			}
			getMemberProcess = $.ajax({
				url : app_url + 'pos/get_member_data?q='+q,
				method : 'get',
				dataType : 'json',
				success : function(res){
					console.log(res);
					if (res.member.length != 0) {
						$('#customer_name').val('');
						$('#customer_email').val('');						
					}
					$('.autocomplete-number ul').html('');
					add_autocomplete_dismiss();
					$('#idmember').val('');

					$('.autocomplete-number').show();
					$.each(res.member,function(key,val){
						$('.autocomplete-number ul').append('<li class="autocomplete-item"><a class="i-am-member" data-id="'+val.idmember+'" data-number="'+val.handphone+'"><b style="color: #09152a;">'+val.name+'</b> &nbsp; <span style="color: #456dff">('+val.handphone+') </span></a></li>');
					});
				}
			});
		}
	}

	function add_autocomplete_dismiss(){
		$('.autocomplete-number ul').append('<li style="display: flex; padding: 8px; background: #232f45;color: #FFF;"><span>Daftar Pelanggan</span><span id="close-autocomplete" style="cursor: pointer; margin-left: auto; color: red;"><i class="fa fa-times"></i></span></li>');
	}

	$('body').on('click','#close-autocomplete', function(){
		$('.autocomplete-number').hide();
	});

	function get_single_member_data(id,number) {
			$.ajax({
				url : app_url + 'pos/get_single_member_data?id='+id+'&number='+number,
				method : 'get',
				dataType : 'json',
				success : function(res){
					console.log(res);
					$('#customer_name').val(res.member.name);
					$('#customer_email').val(res.member.email);
					$('#customer_name').prop('readonly',true);
					// $('#customer_email').prop('readonly',true);
				}
			});
		}

	jQuery('body').on('click', '.i-am-member', function() {
		var number = $(this).attr('data-number');
		var id = $(this).attr('data-id');
		$('#customer_handphone').val(number);	
		console.log('id =>'+id);
		console.log('number =>'+number);
		$('.autocomplete-number ul').html('');
		$('.autocomplete-number').hide();
		$('#idmember').val(id);
		get_single_member_data(id,number);
	});

	check_marketplace();
	function check_marketplace() {
		var type = jQuery('#transaction_type').val();
		if (type == 'MARKETPLACE') {
			jQuery('.container-marketplace').show();
		}
		else {
			jQuery('.container-marketplace').hide();
		}
		change_name();

		jQuery('.container-payment').hide();
		if (type == 'WHATSAPP') {
			jQuery('.container-payment').show();
		}
	}

	jQuery('body').on('change', '#transaction_type', function() {
		check_marketplace();
	});

	function change_name() {
		var name = jQuery('#idmarketplace option:selected').text();
		jQuery('.txt-title-marketplace').text(name);
	}

	jQuery('body').on('change', '#idmarketplace', function(){
		change_name();
	});

	jQuery('body').on('click', '.btn-add-product-finishing', function() {
		var id = jQuery(this).attr('data-id');

		show_modal('add');
		jQuery('#is_edit').val('0');
		jQuery('#is_finishing').val('1');
		jQuery('#idtransaction_detail').val('');
		jQuery('#idtransaction_detail_main').val(id);
		jQuery(".form-product").addClass("hide");
		jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change');
		jQuery('.form-group-deadline').hide();
	});

	jQuery('body').on('click', '#btn_add_product, .btn-edit-order', function() {
		jQuery('.form-group-deadline').show();
	});