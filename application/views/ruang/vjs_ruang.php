<script>

	jQuery('body').on('click', '.btn-copy', function() {
		setTimeout(function() {
			jQuery('.az-modal-master_ruang').find('#is_copy').val('1');
			check_copy();
		}, 1000);
	});

	function check_copy() {
		var is_copy = jQuery('#is_copy').val();
		if (is_copy == '1') {
			// setTimeout(function() {
			// 	console.log('oke');
			// }, 2000);
			jQuery('#idruang').val('');
		}
	}