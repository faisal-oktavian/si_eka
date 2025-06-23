<script>
    jQuery('body').on('click', '#btn_add_akun_belanja', function() {
		var idprogram = jQuery('#idprogram').val();
		var idkegiatan = jQuery('#idkegiatan').val();
		var idsub_kegiatan = jQuery('#idsub_kegiatan').val();

		if(idprogram == '' || idprogram == null) {
			bootbox.alert('Pilih program terlebih dahulu');
		}
        else if(idkegiatan == '' || idkegiatan == null) {
			bootbox.alert('Pilih kegiatan terlebih dahulu');
		}
        else if(idsub_kegiatan == '' || idsub_kegiatan == null) {
			bootbox.alert('Pilih sub kegiatan terlebih dahulu');
		}
        else {
			show_modal('add');

			jQuery("#idakun_belanja").select2("val", "");
			jQuery('#is_edit').val('0');
		}
	});

    jQuery('body').on('click', '.btn-action-save_akun_belanja', function() {
        var idprogram = jQuery('#idprogram').val();
		var idkegiatan = jQuery('#idkegiatan').val();
		var idsub_kegiatan = jQuery('#idsub_kegiatan').val();
		
		show_loading();
        jQuery.ajax({
			url: app_url + 'master_paket_belanja/add_akun_belanja',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_add_akun_belanja').serialize() + '&idprogram=' + idprogram + '&idkegiatan=' + idkegiatan + '&idsub_kegiatan=' + idsub_kegiatan,
			success: function(response) {
				
                hide_loading();

				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					hide_modal('add');
					
                    jQuery('#idpaket_belanja').val(response.idpaket_belanja);
                    jQuery('#hd_idpaket_belanja').val(response.idpaket_belanja);
					generate_detail_paket_belanja(response.idpaket_belanja);
				}
			},
			error: function(response) {}
		});
	});

    function generate_detail_paket_belanja(idpaket_belanja) {
		jQuery.ajax({
			url: app_url+'master_paket_belanja/get_list_akun_belanja/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idpaket_belanja: idpaket_belanja
			},
			success: function(response) {
				jQuery('#table_onthespot tbody').html(response.data);
			},
			error: function(response) {}
		});
	}

    jQuery('body').on('click', '#btn_save_paket_belanja', function() {
		var idpaket_belanja = jQuery('#hd_idpaket_belanja').val();
		
        show_loading();
        jQuery.ajax({
            url: app_url + 'master_paket_belanja/save_paket_belanja',
            type: 'POST',
            dataType: 'JSON',
            data: jQuery('#form_paket_belanja').serialize(),
            success: function(response) {
                hide_loading();

                if (response.err_code == 0) {
					bootbox.alert("Data Berhasil Disimpan.");

					setTimeout(function() {
						location.href = app_url + 'master_paket_belanja/edit/' + idpaket_belanja;
					}, 2000);					
                }
                else {
                    bootbox.alert(response.err_message);
                }
            },
            error: function(response){}
        });
	});

    jQuery('body').on('click','.btn-edit-akun-belanja', function() {
		var id = jQuery(this).attr('data-id');

        show_loading();
		jQuery.ajax({
			url: app_url + 'master_paket_belanja/edit_paket_belanja',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				hide_loading();
				show_modal('add');
				jQuery('#is_edit').val(id);
				jQuery('#idpb_akun_belanja').val(id);
				
				jQuery("#idakun_belanja").select2("val", "");
				jQuery("#idakun_belanja.select2-ajax").append(new Option(response.data.nama_akun_belanja, response.data.idakun_belanja, true, true)).trigger('change');
			},
			error: function(response) {}
		});
	});

    jQuery('body').on('click','.btn-delete-akun-belanja', function() {
		var id = jQuery(this).attr('data-id');

        bootbox.confirm("Apakah anda yakin ingin menghapus data ini? <br> jika data tersebut mempunyai detail data maka akan terhapus juga", function(e) {

			show_loading();
            if (e) {
                jQuery.ajax({
                    url: app_url + 'master_paket_belanja/delete_akun_belanja',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        hide_loading();

                        if (response.err_code == 0) {
                            generate_detail_paket_belanja(response.idpaket_belanja);
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

    // jQuery('body').on('click', '.btn-add-kategori', function() {
	// 	var idpaket_belanja = jQuery('#hd_idpaket_belanja').val();
	// 	var idpaket_belanja_detail = jQuery(this).attr('data-id');

	// 	location.href = app_url + 'paket_belanja_kategori/add_kategori?id=' + idpaket_belanja + '&idd=' + idpaket_belanja_detail;
	// });

	var the_id =  "<?php echo $id;?>";
	if (the_id != "") {
		generate_detail_paket_belanja(the_id);

		jQuery.ajax({
			url: app_url + 'master_paket_belanja/get_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: the_id
			},
			success: function(response) {
				jQuery("#idprogram").append(new Option(response.paket_belanja.nama_program, response.paket_belanja.idprogram, true, true)).trigger('change');
				jQuery("#idkegiatan").append(new Option(response.paket_belanja.nama_kegiatan, response.paket_belanja.idkegiatan, true, true)).trigger('change');
				jQuery("#idsub_kegiatan").append(new Option(response.paket_belanja.nama_subkegiatan, response.paket_belanja.idsub_kegiatan, true, true)).trigger('change');
				
				jQuery('#nama_paket_belanja').val(response.paket_belanja.nama_paket_belanja);
				jQuery('#nilai_anggaran').val(thousand_separator(response.paket_belanja.nilai_anggaran));
			},
			error: function(response) {}
		});
	}


	// kategori
	jQuery('body').on('click', '.btn-add-kategori', function() {
		show_modal('add_kategori');

		var idpaket_belanja = jQuery(this).attr('data-idpaket-belanja');
		var idpaket_belanja_detail = jQuery(this).attr('data-idpb-detail');
		var idakun_belanja = jQuery(this).attr('data-idakun-belanja');

		jQuery("#idkategori").select2("val", "");
		jQuery('#is_edit').val('0');
		
		jQuery('#hd_idpaket_belanja').val(idpaket_belanja);
		jQuery('#hd_idpaket_belanja_detail').val(idpaket_belanja_detail);
		jQuery('#hd_idakun_belanja').val(idakun_belanja);
		jQuery('#is_kategori').val('1');
		jQuery('#is_subkategori').val('0');
	});

	jQuery('body').on('click', '.btn-action-save_kategori', function() {
		
		show_loading();
        jQuery.ajax({
			url: app_url + 'master_paket_belanja/add_kategori',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_add_kategori').serialize(),
			success: function(response) {
				
                hide_loading();

				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					hide_modal('add_kategori');
					
					location.reload();
                    // jQuery('#idpaket_belanja').val(response.idpaket_belanja);
                    // jQuery('#hd_idpaket_belanja').val(response.idpaket_belanja);
					// generate_detail_paket_belanja(response.idpaket_belanja);
				}
			},
			error: function(response) {}
		});
	});


	// sub kategori
	jQuery('body').on('click', '.btn-add-sub-kategori', function() {
		show_modal('add_subkategori');

		var idpaket_belanja = jQuery(this).attr('data-idpaket-belanja');
		var idpaket_belanja_detail = jQuery(this).attr('data-idpb-detail');
		var idakun_belanja = jQuery(this).attr('data-idakun-belanja');
		var idds_parent = jQuery(this).attr('data-idds_parent'); // terisi jika mempunyai kategori

		jQuery("#idsub_kategori").select2("val", "");
		jQuery('#hds_is_edit').val('0');
		
		jQuery('#hds_idpaket_belanja').val(idpaket_belanja);
		jQuery('#hds_idpaket_belanja_detail').val(idpaket_belanja_detail);
		jQuery('#hds_idakun_belanja').val(idakun_belanja);
		jQuery('#hds_idds_parent').val(idds_parent); // idpaket_belanja_detail_sub_parent
		jQuery('#hds_is_kategori').val('0');
		jQuery('#hds_is_subkategori').val('1');
	});

	jQuery('body').on('click', '.btn-action-save_subkategori', function() {
		
		show_loading();
        jQuery.ajax({
			url: app_url + 'master_paket_belanja/add_subkategori',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_add_subkategori').serialize(),
			success: function(response) {
				
                hide_loading();

				if (response.err_code > 0) {
					bootbox.alert(response.err_message);
				}
				else {
					hide_modal('add_subkategori');
					
					location.reload();
                    // jQuery('#idpaket_belanja').val(response.idpaket_belanja);
                    // jQuery('#hd_idpaket_belanja').val(response.idpaket_belanja);
					// generate_detail_paket_belanja(response.idpaket_belanja);
				}
			},
			error: function(response) {}
		});
	});

	jQuery('#form_add_subkategori').on('keyup', '.volume, .harga-satuan', function() {
		var volume			=  jQuery('#volume').val();
		var harga_satuan 	=  jQuery('#harga_satuan').val();

		volume = remove_separator(volume);
		harga_satuan 	= remove_separator(harga_satuan);

  		var jumlah = volume * harga_satuan;

		console.log('jumlah '+jumlah);
		jQuery('#jumlah').val(thousand_separator(jumlah));
	});