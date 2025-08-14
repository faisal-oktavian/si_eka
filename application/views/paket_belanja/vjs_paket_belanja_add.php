<script>
	var is_viewonly = <?php if ($this->uri->segment(4)) {
							echo "true";
						} else {
							echo "false";
						} ?>;

	var is_add = <?php if ($this->uri->segment(2) == 'add') {
						echo "true";
					} else {
						echo "false";
					} ?>;					

	jQuery(document).ready(function() {
		if (is_viewonly == true) {

			jQuery('#form_paket_belanja').find('input, select').prop('disabled', true);
            jQuery('.btn-add_paket_belanja, #btn_add_akun_belanja, #btn_save_paket_belanja').hide();

			setTimeout(function() {
				jQuery('#table_onthespot').find('button').hide();
			}, 500);		
		}
	});

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
							bootbox.alert(response.message);
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


	// edit
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

					generate_detail_paket_belanja(response.idpaket_belanja);
					
					// location.reload();
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

		jQuery('#form_add_subkategori input, #form_add_subkategori select').not('.x-hidden').val('').trigger('change.select2');
		
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
					
					generate_detail_paket_belanja(response.idpaket_belanja);
				}
			},
			error: function(response) {}
		});
	});


	// kategori & sub kategori
	jQuery('body').on('click','.btn-edit-detail', function() {
		var id = jQuery(this).attr('data-id');

        show_loading();
		jQuery.ajax({
			url: app_url + 'master_paket_belanja/edit_paket_belanja_detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function(response) {
				hide_loading();

				if (response.data.idkategori !== null) {
					show_modal('add_kategori');

					jQuery('#is_edit').val(id);
					jQuery('#hd_idpb_detail_sub').val(id);
					jQuery('#hd_idpaket_belanja_detail').val(response.data.idpaket_belanja_detail);
					jQuery('#hd_idakun_belanja').val(response.data.idakun_belanja);
					jQuery('#is_kategori').val(1);
					jQuery('#is_subkategori').val(0);
					
					jQuery("#idkategori").select2("val", "");
					jQuery("#idkategori.select2-ajax").append(new Option(response.data.nama_kategori, response.data.idkategori, true, true)).trigger('change');
				}
				else if (response.data.idsub_kategori !== null) {
					show_modal('add_subkategori');

					jQuery('#is_edit').val(id);
					jQuery('#hds_idpb_detail_sub').val(id);
					jQuery('#hds_idpaket_belanja_detail').val(response.data.idpaket_belanja_detail);
					jQuery('#hds_is_kategori').val(0);
					jQuery('#hds_is_subkategori').val(1);
					jQuery('#hds_idds_parent').val(response.data.is_idpaket_belanja_detail_sub);
					
					jQuery("#idsub_kategori").select2("val", "");
					jQuery("#idsub_kategori.select2-ajax").append(new Option(response.data.nama_sub_kategori, response.data.idsub_kategori, true, true)).trigger('change');
					jQuery('#volume').val(thousand_separator(response.data.volume));
					jQuery("#idsatuan.select2-ajax").append(new Option(response.data.nama_satuan, response.data.idsatuan, true, true)).trigger('change');
					jQuery('#harga_satuan').val(thousand_separator(response.data.harga_satuan));
					jQuery('#jumlah').val(thousand_separator(response.data.jumlah));

					jQuery('#rak_volume_januari').val(thousand_separator(response.data.rak_volume_januari));
					jQuery('#rak_jumlah_januari').val(thousand_separator(response.data.rak_jumlah_januari));
					jQuery('#rak_volume_februari').val(thousand_separator(response.data.rak_volume_februari));
					jQuery('#rak_jumlah_februari').val(thousand_separator(response.data.rak_jumlah_februari));
					jQuery('#rak_volume_maret').val(thousand_separator(response.data.rak_volume_maret));
					jQuery('#rak_jumlah_maret').val(thousand_separator(response.data.rak_jumlah_maret));
					jQuery('#rak_volume_april').val(thousand_separator(response.data.rak_volume_april));
					jQuery('#rak_jumlah_april').val(thousand_separator(response.data.rak_jumlah_april));
					jQuery('#rak_volume_mei').val(thousand_separator(response.data.rak_volume_mei));
					jQuery('#rak_jumlah_mei').val(thousand_separator(response.data.rak_jumlah_mei));
					jQuery('#rak_volume_juni').val(thousand_separator(response.data.rak_volume_juni));
					jQuery('#rak_jumlah_juni').val(thousand_separator(response.data.rak_jumlah_juni));
					jQuery('#rak_volume_juli').val(thousand_separator(response.data.rak_volume_juli));
					jQuery('#rak_jumlah_juli').val(thousand_separator(response.data.rak_jumlah_juli));
					jQuery('#rak_volume_agustus').val(thousand_separator(response.data.rak_volume_agustus));
					jQuery('#rak_jumlah_agustus').val(thousand_separator(response.data.rak_jumlah_agustus));
					jQuery('#rak_volume_september').val(thousand_separator(response.data.rak_volume_september));
					jQuery('#rak_jumlah_september').val(thousand_separator(response.data.rak_jumlah_september));
					jQuery('#rak_volume_oktober').val(thousand_separator(response.data.rak_volume_oktober));
					jQuery('#rak_jumlah_oktober').val(thousand_separator(response.data.rak_jumlah_oktober));
					jQuery('#rak_volume_november').val(thousand_separator(response.data.rak_volume_november));
					jQuery('#rak_jumlah_november').val(thousand_separator(response.data.rak_jumlah_november));
					jQuery('#rak_volume_desember').val(thousand_separator(response.data.rak_volume_desember));
					jQuery('#rak_jumlah_desember').val(thousand_separator(response.data.rak_jumlah_desember));
				} 
			},
			error: function(response) {}
		});
	});

	jQuery('body').on('click','.btn-delete-detail', function() {
		var id = jQuery(this).attr('data-id');

        bootbox.confirm("Apakah anda yakin ingin menghapus data ini? <br> jika data tersebut mempunyai detail data maka akan terhapus juga", function(e) {

			show_loading();
            if (e) {
                jQuery.ajax({
                    url: app_url + 'master_paket_belanja/delete_detail',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        hide_loading();

                        if (response.err_code == 0) {
							location.reload();
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

	jQuery('#form_add_subkategori').on('keyup', '.volume, .harga-satuan', function() {
		var volume			=  jQuery('#volume').val();
		var harga_satuan 	=  jQuery('#harga_satuan').val();

		var rak_volume_januari		=  jQuery('#rak_volume_januari').val();
		var rak_volume_februari		=  jQuery('#rak_volume_februari').val();
		var rak_volume_maret		=  jQuery('#rak_volume_maret').val();
		var rak_volume_april		=  jQuery('#rak_volume_april').val();
		var rak_volume_mei			=  jQuery('#rak_volume_mei').val();
		var rak_volume_juni			=  jQuery('#rak_volume_juni').val();
		var rak_volume_juli			=  jQuery('#rak_volume_juli').val();
		var rak_volume_agustus		=  jQuery('#rak_volume_agustus').val();
		var rak_volume_september	=  jQuery('#rak_volume_september').val();
		var rak_volume_oktober		=  jQuery('#rak_volume_oktober').val();
		var rak_volume_november		=  jQuery('#rak_volume_november').val();
		var rak_volume_desember		=  jQuery('#rak_volume_desember').val();

		volume 					= remove_separator(volume);
		harga_satuan 			= remove_separator(harga_satuan);
		rak_volume_januari		= remove_separator(rak_volume_januari);
		rak_volume_februari		= remove_separator(rak_volume_februari);
		rak_volume_maret		= remove_separator(rak_volume_maret);
		rak_volume_april		= remove_separator(rak_volume_april);
		rak_volume_mei			= remove_separator(rak_volume_mei);
		rak_volume_juni			= remove_separator(rak_volume_juni);
		rak_volume_juli			= remove_separator(rak_volume_juli);
		rak_volume_agustus		= remove_separator(rak_volume_agustus);
		rak_volume_september	= remove_separator(rak_volume_september);
		rak_volume_oktober		= remove_separator(rak_volume_oktober);
		rak_volume_november		= remove_separator(rak_volume_november);
		rak_volume_desember		= remove_separator(rak_volume_desember);

  		var jumlah 					= volume * harga_satuan;
		var rak_jumlah_januari		= rak_volume_januari * harga_satuan;
		var rak_jumlah_februari		= rak_volume_februari * harga_satuan;
		var rak_jumlah_maret		= rak_volume_maret * harga_satuan;
		var rak_jumlah_april		= rak_volume_april * harga_satuan;
		var rak_jumlah_mei			= rak_volume_mei * harga_satuan;
		var rak_jumlah_juni			= rak_volume_juni * harga_satuan;
		var rak_jumlah_juli			= rak_volume_juli * harga_satuan;
		var rak_jumlah_agustus		= rak_volume_agustus * harga_satuan;
		var rak_jumlah_september	= rak_volume_september * harga_satuan;
		var rak_jumlah_oktober		= rak_volume_oktober * harga_satuan;
		var rak_jumlah_november		= rak_volume_november * harga_satuan;
		var rak_jumlah_desember		= rak_volume_desember * harga_satuan;

		console.log('jumlah '+jumlah);
		jQuery('#jumlah').val(thousand_separator(jumlah));
		
		if (rak_volume_januari != '' && rak_volume_januari != null) {
			jQuery('#rak_jumlah_januari').val(thousand_separator(rak_jumlah_januari));
		}
		if (rak_volume_februari != '' && rak_volume_februari != null) {
			jQuery('#rak_jumlah_februari').val(thousand_separator(rak_jumlah_februari));
		}
		if (rak_volume_maret != '' && rak_volume_maret != null) {
			jQuery('#rak_jumlah_maret').val(thousand_separator(rak_jumlah_maret));
		}
		if (rak_volume_april != '' && rak_volume_april != null) {
			jQuery('#rak_jumlah_april').val(thousand_separator(rak_jumlah_april));
		}
		if (rak_volume_mei != '' && rak_volume_mei != null) {
			jQuery('#rak_jumlah_mei').val(thousand_separator(rak_jumlah_mei));
		}
		if (rak_volume_juni != '' && rak_volume_juni != null) {
			jQuery('#rak_jumlah_juni').val(thousand_separator(rak_jumlah_juni));
		}
		if (rak_volume_juli != '' && rak_volume_juli != null) {
			jQuery('#rak_jumlah_juli').val(thousand_separator(rak_jumlah_juli));
		}
		if (rak_volume_agustus != '' && rak_volume_agustus != null) {
			jQuery('#rak_jumlah_agustus').val(thousand_separator(rak_jumlah_agustus));
		}
		if (rak_volume_september != '' && rak_volume_september != null) {
			jQuery('#rak_jumlah_september').val(thousand_separator(rak_jumlah_september));
		}
		if (rak_volume_oktober != '' && rak_volume_oktober != null) {
			jQuery('#rak_jumlah_oktober').val(thousand_separator(rak_jumlah_oktober));
		}
		if (rak_volume_november != '' && rak_volume_november != null) {
			jQuery('#rak_jumlah_november').val(thousand_separator(rak_jumlah_november));
		}
		if (rak_volume_desember != '' && rak_volume_desember != null) {
			jQuery('#rak_jumlah_desember').val(thousand_separator(rak_jumlah_desember));
		}
	});