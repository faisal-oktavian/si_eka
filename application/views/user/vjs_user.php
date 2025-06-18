<script>
	jQuery('body').on('click', '.btn-new-material-width', function() {
	    add_material_width();
	});

	function add_material_width() {
	    var content = jQuery('#ft_helper_material_width').text();
	    jQuery('.table-material-width > tbody').append(content);

	    var the_pos = jQuery('.table-material-width > tbody > tr').last().index();
	    // var material_width = jQuery('.material-width').val();
	    // jQuery('.table-material-width > tbody > tr').last().find('.material-width-list').val(material_width);
	    // jQuery('.material-width').val('');
	}

	jQuery('body').on('click', '.btn-remove-material-width', function() {
	    jQuery(this).parent().parent().remove();
	});