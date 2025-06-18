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

			jQuery('#is_edit').val('0');
			jQuery('#idtransaction_detail').val('');
			jQuery(".form-product").addClass("hide");
			jQuery('#form_add input, #form_add select, #form_add textarea').not('.x-hidden').val('').trigger('change');
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
		
        show_loading();
        jQuery.ajax({
            url: app_url + 'master_paket_belanja/save_paket_belanja',
            type: 'POST',
            dataType: 'JSON',
            data: jQuery('#form_paket_belanja').serialize(),
            success: function(response) {
                hide_loading();

                if (response.err_code == 0) {
                    location.reload();
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
				jQuery('#idtransaction_detail').val(id);
				
				jQuery("#idakun_belanja.select2-ajax").append(new Option(response.data.nama_akun_belanja, response.data.idakun_belanja, true, true)).trigger('change');
			},
			error: function(response) {}
		});
	});

    jQuery('body').on('click','.btn-delete-akun-belanja', function() {
		var id = jQuery(this).attr('data-id');
		show_loading();

        bootbox.confirm("Apakah anda yakin ingin menghapus data ini? <br> jika data tersebut mempunyai detail data maka akan terhapus juga", function(e) {
            
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

    jQuery('body').on('click', '.btn-add-kategori', function() {
		location.href = app_url + 'master_paket_belanja/add_kategori';
	});