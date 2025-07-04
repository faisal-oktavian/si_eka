<script>
	jQuery('#tahun_anggaran').datetimepicker({
		format: 'YYYY'
	});

	jQuery('body').on('click', '.btn-filter-evaluasi', function() {
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		location.href = app_url + 'evaluasi_anggaran/?tahun_anggaran='+tahun_anggaran;
	});

	var tahun_anggaran = "<?php echo $this->input->get('tahun_anggaran') ;?>";

	if (tahun_anggaran != "") {
		jQuery('#tahun_anggaran').val(tahun_anggaran);
	}

	jQuery('body').on('click', '.btn-view', function() {
		var idpaket_belanja_detail = jQuery(this).attr('data_idpaket_belanja_detail');
		var tw = jQuery(this).attr('data_tw');
		var tahun_anggaran = jQuery('#tahun_anggaran').val();

		jQuery('.detail-table').html('');

		jQuery.ajax({
			url: app_url + 'evaluasi_anggaran/get_detail_data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				idpaket_belanja_detail: idpaket_belanja_detail,
				tw: tw,
				tahun_anggaran: tahun_anggaran
			},
			success: function(response) {
				show_modal('detail_realisasi');

				jQuery('.detail-table').html(response.data);
			},
			error: function(response) {}
		});
	});

	////////////////////////////////////
	/////////// CEK
	////////////////////////////////////

	jQuery('body').on('click', '.btn-add-sub-kategori', function() {
		show_modal('detail_paket_belanja');

		// var idpaket_belanja = jQuery(this).attr('data-idpaket-belanja');
		// var idpaket_belanja_detail = jQuery(this).attr('data-idpb-detail');
		// var idakun_belanja = jQuery(this).attr('data-idakun-belanja');
		// var idds_parent = jQuery(this).attr('data-idds_parent'); // terisi jika mempunyai kategori

		// jQuery("#idsub_kategori").select2("val", "");
		// jQuery('#hds_is_edit').val('0');
		
		// jQuery('#hds_idpaket_belanja').val(idpaket_belanja);
		// jQuery('#hds_idpaket_belanja_detail').val(idpaket_belanja_detail);
		// jQuery('#hds_idakun_belanja').val(idakun_belanja);
		// jQuery('#hds_idds_parent').val(idds_parent); // idpaket_belanja_detail_sub_parent
		// jQuery('#hds_is_kategori').val('0');
		// jQuery('#hds_is_subkategori').val('1');
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
