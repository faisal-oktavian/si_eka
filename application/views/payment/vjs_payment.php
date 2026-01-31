<script>
	jQuery('body').on('click','.btn-payment', function() {
		var id = jQuery(this).attr('data_id');
		jQuery('.form-payment').find(':input:text').val('');
		jQuery('.form-payment').find('textarea').val('');
		show_loading();

		var dtp = $("#debt_payment_date").data("DateTimePicker");
		if (dtp) {
		    dtp.destroy();
		}
		$("#debt_payment_date").datetimepicker({
		    format: "DD-MM-YYYY HH:mm:ss"
		});

		jQuery.ajax({
			url : app_url + 'payment/edit?id='+id,
			method : 'get',
			dataType : 'JSON',
			success : function(res){
				hide_loading();
				show_modal('payment');
				
				jQuery('#idnpd').val(id);
				// jQuery('.utang-component').show();
				
				jQuery('#total_transaction').val(thousand_separator(res.total_anggaran));
				// jQuery('#total_cicilan').val(thousand_separator(res.total_cicilan));
				// jQuery('#total_debt').val(thousand_separator(res.total_lack));
				jQuery('#lack').val(thousand_separator(res.total_lack));
				// jQuery('#total_return').val(thousand_separator(res.total_return));

				jQuery('#payment_description').val(res.description);
			}
		});
	});

	$('#debt_payment_date').on('click', function() {

		if ($(this).val() == '') {
			// Mendapatkan tanggal dan waktu saat ini
	        var now = new Date();
	        var year = now.getFullYear();
	        var month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
	        var day = String(now.getDate()).padStart(2, '0');
	        var hours = String(now.getHours()).padStart(2, '0');
	        var minutes = String(now.getMinutes()).padStart(2, '0');
	        var seconds = String(now.getSeconds()).padStart(2, '0');

	        // Mengatur nilai input dengan format YYYY-MM-DDTHH:MM:SS
	        var datetime = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ':' + seconds;
	        $(this).val(datetime);
		}
    });

	jQuery('body').on('keyup','.calc',function(){
		calculate();
	});

	function calculate() {
		var total_cash =  jQuery('#total_cash').val() || 0;
		var total_debet =  jQuery('#total_debet').val() || 0;
		var total_credit =  jQuery('#total_credit').val() || 0;
		var total_transfer = jQuery('#total_transfer').val() || 0;
		var total_transaction = jQuery('#total_transaction').val() || 0;
		var total_dp = jQuery('#total_dp').val() || 0;
		var total_cicilan = jQuery('#total_cicilan').val() || 0;
		var total_return = jQuery('#total_return').val() || 0;

		total_cash = remove_separator(total_cash); 
		total_debet = remove_separator(total_debet);
		total_credit = remove_separator(total_credit);
		total_transfer = remove_separator(total_transfer);
		total_transaction = remove_separator(total_transaction);
		total_dp = remove_separator(total_dp);
		total_cicilan = remove_separator(total_cicilan);
		total_return = remove_separator(total_return);

		var total_pay = parseInt(total_cash) + parseInt(total_debet) + parseInt(total_credit) + parseInt(total_transfer);
		total_transaction = parseInt(total_transaction) - (parseInt(total_dp) + parseInt(total_cicilan));
		console.log(total_transaction);
		var lack = total_pay - total_transaction;

		jQuery('#total_pay').val(thousand_separator(total_pay));
		jQuery('#lack').val(thousand_separator(lack));

		if (lack <= 0) {
			jQuery('#txt_lack').text('KEKURANGAN');
			jQuery('#lack').removeClass('pas');
		}
		else {
			jQuery('#txt_lack').text('KEMBALI');
			jQuery('#lack').addClass('pas');
		}
	}

	jQuery('body').on('click', '.btn-action-save-payment', function() {
		show_loading();
		jQuery.ajax({
			url: app_url + 'payment/save',
			type: 'POST',
			dataType: 'JSON',
			data: jQuery('.form-payment').serialize(),
			success: function(response) {
				hide_loading();
				
				// var debt = jQuery('#workflow_debt').dataTable({bRetrieve:true});
				// debt.fnDraw(false);	

				// if (response.err_code == 0) {
				// 	hide_modal('payment');
				// }
				// bootbox.alert(response.err_message);

				if (response.err_code == 0) {
					location.href = app_url + 'payment';
				}
				else {
					bootbox.alert(response.err_message);
				}

			},
			errors: function(response) {}
		});
	});

	jQuery('body').on('click','.btn-payment-log', function(){
		var id = jQuery(this).attr('data_id');

		jQuery.ajax({
			url : app_url + 'payment/debt_log?id='+id,
			method : 'get',
			dataType : 'json',
			success : function(res) {
				if (res.success) {
					show_modal('debt-log-payment');
					jQuery('.container-debt-log').html(res.view);
				}
				else{
					bootbox.alert(res.message);
				}
			}
		})
	});