<script>
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

	jQuery('.time').datetimepicker({
		format:'HH:mm:ss',
	});
