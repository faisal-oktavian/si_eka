<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

	// OK => data berada di menu paket belanja
	// PROSES PENGADAAN => data berada di menu rencana pengadaan
	// KONTRAK PENGADAAN => data berada di menu kontrak pengadaan
	// MENUNGGU VERIFIKASI => data berada di menu realisasi anggaran
	// SUDAH DIVERIFIKASI => data berada di menu verifikasi dokumen dan statusnya diverif
	// DITOLAK VERIFIKATOR => data berada di menu verifikasi dokumen dan statusnya ditolak
	// INPUT NPD => data berada di menu nota pencairan dana
	// MENUNGGU PEMBAYARAN => data berada di menu pembayaran dan statusnya belum dibayar
	// SUDAH DIBAYAR BENDAHARA => data berada di menu pembayaran dan statusnya sudah dibayar

	function validation_status($the_data) {
		$menu = azarr($the_data, 'menu');
		$type = azarr($the_data, 'type');
		$arr_validation = array('PROSES PENGADAAN', 'KONTRAK PENGADAAN', 'MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA');

		if ($menu == "RENCANA PENGADAAN") {
			$key = array_search("PROSES PENGADAAN", $arr_validation);

			if ($key !== false) {
				unset($arr_validation[$key]);
			}
		}
		else if ($menu == "KONTRAK PENGADAAN") {

			if ($type == "validation_crud") {
				$arr_validation = '("PROSES PENGADAAN", "MENUNGGU VERIFIKASI", "SUDAH DIVERIFIKASI", "DITOLAK VERIFIKATOR", "INPUT NPD", "MENUNGGU PEMBAYARAN", "SUDAH DIBAYAR BENDAHARA")';
			}
			else {
				$key = array_search("KONTRAK PENGADAAN", $arr_validation);
	
				if ($key !== false) {
					unset($arr_validation[$key]);
				}
			}
		}
		else if ($menu == "REALISASI ANGGARAN") {
			$remove = array('MENUNGGU VERIFIKASI', 'DITOLAK VERIFIKATOR');

			$arr_validation = array_values(array_diff($arr_validation, $remove));
			
			// $key = array_search("MENUNGGU VERIFIKASI", $arr_validation);

			// if ($key !== false) {
			// 	unset($arr_validation[$key]);
			// }
		}
		else if ($menu == "VERIFIKASI DOKUMEN") {
			if ($type == "save") {
				$remove = array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR');
			}
			else {
				$remove = array('SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR');
			}

			$arr_validation = array_values(array_diff($arr_validation, $remove));

			if ($type == "view") {
				$arr_validation = array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR');
			}
		}
		else if ($menu == "NPD") {
			$key = array_search("INPUT NPD", $arr_validation);

			if ($key !== false) {
				unset($arr_validation[$key]);
			}
		}
		else if ($menu == "PEMBAYARAN") {
			$key = array_search("SUDAH DIBAYAR BENDAHARA", $arr_validation);

			if ($key !== false) {
				unset($arr_validation[$key]);
			}
		}

		return $arr_validation;
	}

	function label_status($status) {
		$label_status = "<label class='label label-default'>-</label>";

		if ($status == "PROSES PENGADAAN") {
			$label_status = "<label class='label' style='text-align: center; background-color: #FFCC00; color: black;'>
								Proses Pengadaan</label>";
		}
		else if ($status == "KONTRAK PENGADAAN") {
			$label_status = "<label class='label' style='text-align: center; background-color: #FF9900; color: white;'>
								Kontrak Pengadaan</label>";
		}
		else if ($status == "MENUNGGU VERIFIKASI") {
			$label_status = "<label class='label' style='text-align: center; background-color: #FF6600; color: white;'>
								Menunggu Verifikasi</label>";
		}
		else if ($status == "SUDAH DIVERIFIKASI") {
			$label_status = "<label class='label' style='text-align: center; background-color: #0066FF; color: white;'>
								Sudah Diverifikasi</label>";
		}
		else if ($status == "DITOLAK VERIFIKATOR") {
			$label_status = "<label class='label' style='text-align: center; background-color: #FF3333; color: white;'>
								Revisi Dokumen</label>";
		}
		else if ($status == "INPUT NPD") {
			$label_status = "<label class='label' style='text-align: center; background-color: #999999; color: white;'>
								Input NPD</label>";
		}
		else if ($status == "MENUNGGU PEMBAYARAN") {
			$label_status = "<label class='label' style='text-align: center; background-color: #FFCC66; color: black;'>
								Menunggu Pembayaran</label>";
		}
		else if ($status == "SUDAH DIBAYAR BENDAHARA") {
			$label_status = "<label class='label' style='text-align: center; background-color: #28A745; color: white;'>
								Sudah Dibayar Bendahara</label>";
		}
		
		return $label_status;
	}
	
	// hitung volume yang sudah terealisasi
	function calculate_realisasi_volume($idpaket_belanja_detail_sub) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$ci->db->where('purchase_plan_detail.status', 1);
		$ci->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$ci->db->where('purchase_plan.purchase_plan_status != "DRAFT" ');
		$ci->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = purchase_plan_detail.idpurchase_plan');
		$ci->db->select_sum('volume');
		$ppd = $ci->db->get('purchase_plan_detail');
        // echo "<pre>"; print_r($ci->db->last_query());die;
		
        $volume_realization = 0;
		if ($ppd->num_rows() > 0) {
			$volume_realization = $ppd->row()->volume;
		}
		
		if ($volume_realization == null) {
			$volume_realization = 0;
		}

		$arr_update = array(
			'volume_realization' => $volume_realization,
		);

		$ci->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$ci->db->update('paket_belanja_detail_sub', $arr_update);

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}


    // update status detail rencana pengadaan
	function update_status_detail_purchase_plan($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idpurchase_plan_detail = azarr($the_data, 'idpurchase_plan_detail');
		$status = azarr($the_data, 'status');

		$arr_update_plan = array(
			'purchase_plan_detail_status' => $status,
		);

		$ci->db->where('idpurchase_plan_detail', $idpurchase_plan_detail);
		$ci->db->update('purchase_plan_detail', $arr_update_plan);

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

    // update status rencana pengadaan
	function update_status_purchase_plan($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idpurchase_plan = azarr($the_data, 'idpurchase_plan');
		$status = azarr($the_data, 'status');

		$arr_update = array(
			'purchase_plan_status' => $status,
		);

		$ci->db->where('idpurchase_plan', $idpurchase_plan);
		$ci->db->update('purchase_plan', $arr_update);


		// update status detail paket belanja
		$ci->db->where('status', 1);
		$ci->db->where('idpurchase_plan', $idpurchase_plan);
		$pd = $ci->db->get('purchase_plan_detail');

		foreach ($pd->result() as $key => $value) {
			$the_filter = array(
				'idpurchase_plan_detail' => $value->idpurchase_plan_detail,
				'status' => $status,
			);

			update_status_detail_purchase_plan($the_filter);	
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

    // update status detail kontrak pengadaan
	function update_status_detail_purchase_contract($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idpurchase_plan_detail = azarr($the_data, 'idpurchase_plan_detail');
		$status = azarr($the_data, 'status');

		$arr_update_plan = array(
			'purchase_plan_detail_status' => $status,
		);

		$ci->db->where('idpurchase_plan_detail', $idpurchase_plan_detail);
		$ci->db->update('purchase_plan_detail', $arr_update_plan);

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

    // update status kontrak pengadaan
	function update_status_purchase_contract($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idcontract = azarr($the_data, 'idcontract');
		$idpurchase_plan_detail = azarr($the_data, 'idpurchase_plan_detail');
		$idpurchase_plan = azarr($the_data, 'idpurchase_plan');
		$status = azarr($the_data, 'status');

		$ci->db->where('contract_detail.idcontract', $idcontract);
		$ci->db->where('purchase_plan_detail.idpurchase_plan', $idpurchase_plan);
		$ci->db->where('purchase_plan_detail.purchase_plan_detail_status = "KONTRAK PENGADAAN" ');

		$ci->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$ci->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');

		$contract = $ci->db->get('contract_detail');
		// echo "<pre>"; print_r($ci->db->last_query());die;

		if ($contract->num_rows() == 0) {
			$arr_update = array(
				'contract_status' => $status,
			);

			$ci->db->where('idcontract', $idcontract);
			$ci->db->update('contract', $arr_update);

			$the_filter = array(
				'idpurchase_plan' => $idpurchase_plan,
				'status' => $status,
			);

			update_status_purchase_plan($the_filter);
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

    // update status realisasi anggaran
	function update_status_budget_realization($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idbudget_realization = azarr($the_data, 'idbudget_realization');
		$idverification = azarr($the_data, 'idverification');
		$status = azarr($the_data, 'status');

		$ci->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$ci->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$ci->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$realization = $ci->db->get('budget_realization');
		
		// update status detail kontrak pengadaan
		// update statusnya di table detail rencana pengadaan
		foreach ($realization->result() as $key => $value) {
			$the_filter = array(
				'idcontract' => $value->idcontract,
				'idpurchase_plan_detail' => $value->idpurchase_plan_detail,
				'idpurchase_plan' => $value->idpurchase_plan,
				'status' => $status
			);
			$update_status = update_status_detail_purchase_contract($the_filter);
		}

		// update status kontrak pengadaan
		update_status_purchase_contract($the_filter);

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

    // update status verifikasi dokumen
	function update_status_document_verification($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idverification = azarr($the_data, 'idverification');
		$idbudget_realization = azarr($the_data, 'idbudget_realization');
		$status = azarr($the_data, 'status');

		$arr_update_npd = array(
			'verification_status' => $status,
		);

		$ci->db->where('idverification', $idverification);
		$ci->db->update('verification', $arr_update_npd);


		$arr_update_realization = array(
			'realization_status' => $status,
		);

		$ci->db->where('idbudget_realization', $idbudget_realization);
		$ci->db->update('budget_realization', $arr_update_realization);


		// update status rencana pengadaan
		$the_filter = array(
			'idbudget_realization' => $idbudget_realization,
			'idverification' => $idverification,
			'status' => $status,
		);
		// echo "<pre>"; print_r($the_filter); die;
		$update_status = update_status_budget_realization($the_filter);

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

    // update status nota pencairan dana
	function update_status_npd($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idnpd = azarr($the_data, 'idnpd');
		$status = azarr($the_data, 'status');

		$ci->db->where('npd.idnpd',$idnpd);
		$ci->db->where('npd.status', 1);
		$ci->db->where('npd_detail.status', 1);
		$ci->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$ci->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$npd = $ci->db->get('npd');
		
		foreach ($npd->result() as $key => $value) {
			// update status verifikasi dokumen
			$the_filter = array(
				'idverification' => $value->idverification,
				'idbudget_realization' => $value->idbudget_realization,
				'status' => $status
			);
			$update_status = update_status_document_verification($the_filter);
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}