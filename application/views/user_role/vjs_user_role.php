<script>
jQuery('.az-save-user-role').click(function() {
	jQuery('#form').submit();
});

var msg = "<?php echo $this->session->flashdata('msg');?>";
if (msg != '') {
	bootbox.alert(msg);
}

jQuery('#idrole').change(function() {
	access_user_role();
});

jQuery(document).ready(function() {
	access_user_role();
});

function access_user_role() {
	jQuery('.access').prop('checked', false);
	jQuery.ajax({
		url: app_url+'user_role/generate_access',
		type: 'POST',
		dataType: 'JSON',
		data: {
			idrole: jQuery('#idrole').val()
		},
		success: function(response) {
			jQuery(response).each(function(reskey, resvalue) {
				menu_name = resvalue.menu_name;
				access = resvalue.access;

				if (access == 1) {
					jQuery('.access-'+menu_name).prop('checked', true);
				}
				else {
					jQuery('.access-'+menu_name).prop('checked', false);	
				}
			});
		},
		error: function(response) {
			console.log('error');
		}
	});
}