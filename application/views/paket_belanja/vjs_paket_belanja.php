<script>
	jQuery('body').on('click', '.btn-add-master_paket_belanja', function() {
		location.href = app_url + 'master_paket_belanja/add';
	});

	jQuery('body').on('click', '.btn-edit-master_paket_belanja', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'master_paket_belanja/edit/' + id;
	});
