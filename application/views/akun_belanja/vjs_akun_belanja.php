<script>

	jQuery('body').on('click', '.btn-copy', function() {
		setTimeout(function() {
			jQuery('.az-modal-master_urusan_pemerintah').find('#is_copy').val('1');
			check_copy();
		}, 1000);
	});

	function check_copy() {
		var is_copy = jQuery('#is_copy').val();
		if (is_copy == '1') {
			// setTimeout(function() {
			// 	console.log('oke');
			// }, 2000);
			jQuery('#idurusan_pemerintah').val('');
		}
	}

	jQuery('#btn_save').click(function() {
		show_loading();
		jQuery.ajax({
			url: app_url + 'information/save',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('#form_information').serialize(),
			success: function(response) {
				hide_loading();
				if (response.sMessage != "") {
	                var err_response = response.sMessage;
	                err_response = err_response.replace(/\n/g, "<br>");
	                bootbox.alert({
	                    title: "<?php echo azlang('Error');?>",
	                    message: err_response
	                });
	            }
	            else {
	                bootbox.alert({
	                    title: "<?php echo azlang('Success');?>",
	                    message: "<?php echo azlang('Save data success');?>",
	                    callback: function() {
	                    	location.reload();
	                    }
	                });
	            }
			},
			error: function(response) {
				hide_loading();
			}
		});
	});
