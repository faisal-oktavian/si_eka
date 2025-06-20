<script>
	jQuery('body').on('click', '.btn-filter-neraca', function() {
		// var date1 = jQuery('#date1').val();
		var idoutlet = jQuery('#idoutlet').val();
		var idcompany = jQuery('#idcompany').val();
		var date2 = jQuery('#date2').val();

		location.href = app_url + 'acc_report_balance_sheet/?idoutlet='+idoutlet+'&idcompany='+idcompany+'&date2='+date2;
	});

	jQuery('body').on('click','.changeable-outlet-item', function() {
		var idoutlet = $(this).attr('data-id');
		var idcompany = jQuery('#idcompany').val();
		
		window.location.href = app_url + 'acc_report_balance_sheet/?idoutlet='+idoutlet+'&idcompany='+idcompany+'&date2='+date2;
	});

	jQuery('body').on('click', '.btn-print-neraca', function() {
		// var date1 = jQuery('#date1').val();
		var idoutlet = jQuery('#idoutlet').val();
		var idcompany = jQuery('#idcompany').val();
		var date2 = jQuery('#date2').val();

		jQuery('#form_report').attr('action', app_url + 'acc_report_balance_sheet/print_report_balance_sheet');
		jQuery('#form_report').submit();
	});

	jQuery('body').on('click', '.btn-excel-neraca', function(){
		var idoutlet = jQuery('#idoutlet').val();
		var idcompany = jQuery('#idcompany').val();
		var date2 = jQuery('#date2').val();
		
		// location.href = app_url + 'acc_report_balance_sheet/excel_neraca/?idoutlet='+idoutlet+'&date2='+date2;
		jQuery('#form_report').attr('action', app_url + 'acc_report_balance_sheet/excel_neraca');
		jQuery('#form_report').submit();
	});

	var date2 = "<?php echo $this->input->get('date2') ;?>";

	if (date2 != "") {
		jQuery('#date2').val(date2);
	}

	//set choosed outlet
	jQuery(document).ready(function() {
		var idoutlet = "<?php echo $this->input->get('idoutlet');?>";
		if (idoutlet != "" && idoutlet != 'null') {
			if ( typeof global_outlet !== 'undefined') {
				var outlet_name = global_outlet[(idoutlet - 1)];
				jQuery("#idoutlet").append(new Option(outlet_name, idoutlet, true, true)).trigger('change');
			}
		}

		var idcompany = "<?php echo $this->input->get('idcompany');?>";
		if (idcompany != "" && idcompany != 'null') {
			if ( typeof global_company !== 'undefined') {
				var company_name = global_company[(idcompany - 1)];
				jQuery("#idcompany").append(new Option(company_name, idcompany, true, true)).trigger('change');
			}
		}
	});

	// id dari setiap form terkunci ketika di filter
	var idoutlet = "<?php echo $this->input->get('idoutlet') ;?>";
	var idcompany = "<?php echo $this->input->get('idcompany') ;?>";

	if (idoutlet !== "null" && idoutlet !== null && idoutlet !== 0 && idoutlet !== "") {
		// jQuery('#idoutlet').val(idoutlet)
		jQuery("#idoutlet").append(new Option('<?php echo $outlet_name; ?>', idoutlet, true, true)).trigger("change.select2");
	}

	<?php
		if (az_get_config('is_company', 'config_app')) {
	?>
		if (idcompany !== "null" && idcompany !== null && idcompany !== 0 && idcompany !== "") {
			// jQuery('#idcompany').val(idcompany)
			jQuery("#idcompany").append(new Option('<?php echo $company_name; ?>', idcompany, true, true)).trigger("change.select2");
		}
	<?php
		}
	?>
