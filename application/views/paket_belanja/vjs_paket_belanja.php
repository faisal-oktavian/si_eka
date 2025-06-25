<script>
	jQuery('body').on('click', '.btn-add-master_paket_belanja', function() {
		location.href = app_url + 'master_paket_belanja/add';
	});

	jQuery('body').on('click', '.btn-edit-master_paket_belanja', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'master_paket_belanja/edit/' + id;
	});

	jQuery('body').on('click','.btn-delete-master-paket-belanja', function() {
		var id = jQuery(this).attr('data_id');

        bootbox.confirm("Apakah anda yakin ingin menghapus data ini? <br> jika data tersebut mempunyai detail data maka akan terhapus juga", function(e) {

			show_loading();
            if (e) {
                jQuery.ajax({
                    url: app_url + 'master_paket_belanja/delete_paket_belanja',
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
