<script>
	jQuery('body').on('click', '.btn-add-realisasi_anggaran', function() {
		location.href = app_url + 'realisasi_anggaran/add';
	});

	jQuery('body').on('click', '.btn-edit-pos', function() {
		var id = jQuery(this).attr('data_id');
		location.href = app_url + 'pos/edit/' + id;
	});

	jQuery('body').on('click', '.btn-view-only-pos', function() {
		var id = jQuery(this).attr('data_id');
		var param = '/view_only';
		if (jQuery(this).hasClass('btn-return')) {
			param += '/return';
		}
		location.href = app_url + 'pos/edit/' + id + param;
	});