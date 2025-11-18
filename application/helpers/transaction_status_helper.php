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
			$key = array_search("KONTRAK PENGADAAN", $arr_validation);

			if ($key !== false) {
				unset($arr_validation[$key]);
			}
		}
		else if ($menu == "REALISASI ANGGARAN") {
			$key = array_search("MENUNGGU VERIFIKASI", $arr_validation);

			if ($key !== false) {
				unset($arr_validation[$key]);
			}
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
								Ditolak Verifikator</label>";
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


	// update status di setiap detail paket belanja
	function update_status_detail_pb($the_data) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idpaket_belanja_detail_sub = azarr($the_data, 'idpaket_belanja_detail_sub');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja');
		$status = azarr($the_data, 'status');

		$arr_update = array(
			'status_detail_step' => $status,
		);

		$ci->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$ci->db->where('idpaket_belanja', $idpaket_belanja);
		$ci->db->update('paket_belanja_detail_sub', $arr_update);

		update_status_paket_belanja($idpaket_belanja);

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}
	
	// update status paket belanja
	function update_status_paket_belanja($idpaket_belanja) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$ci->db->where('status', 1);
		$ci->db->where('idpaket_belanja', $idpaket_belanja);
		$ci->db->where('status_detail_step = "INPUT PAKET BELANJA" ');
		$detail_sub = $ci->db->get('paket_belanja_detail_sub');
		// echo "<pre>"; print_r($ci->db->last_query());die;

		if ($detail_sub->num_rows() == 0) {
			$status_paket_belanja = "PROSES REALISASI";
		}
		else {
			$status_paket_belanja = "OK";
		}

		$arr_update = array(
			'status_paket_belanja' => $status_paket_belanja,
		);

		$ci->db->where('idpaket_belanja', $idpaket_belanja);
		$ci->db->update('paket_belanja', $arr_update);


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
				'idpaket_belanja_detail_sub' => $value->idpaket_belanja_detail_sub,
				'idpaket_belanja' => $value->idpaket_belanja,
				'status' => $status,
			);

			update_status_detail_pb($the_filter);	
		}

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

		$idcontract_detail = azarr($the_data, 'idcontract_detail');
		$idpurchase_plan_detail = azarr($the_data, 'idpurchase_plan_detail');
		$idbudget_realization = azarr($the_data, 'idbudget_realization');
		$status = azarr($the_data, 'status');

		$ci->db->where('idcontract_detail', $idcontract_detail);
		$contract = $ci->db->get('contract_detail');

		$idcontract = $contract->row()->idcontract;
		$idpurchase_plan = $contract->row()->idpurchase_plan;

		$arr_update = array(
			'contract_status' => $status,
		);

		$ci->db->where('idcontract', $idcontract);
		$ci->db->update('contract', $arr_update);


		$arr_update_plan = array(
			'purchase_plan_status' => $status,
		);

		$ci->db->where('idpurchase_plan', $idpurchase_plan);
		$ci->db->update('purchase_plan', $arr_update_plan);


		// update status detail paket belanja
		$ci->db->where('budget_realization_detail.status', 1);
		$ci->db->where('budget_realization_detail.idbudget_realization', $idbudget_realization);
		$ci->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$ci->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$rd = $ci->db->get('budget_realization_detail');

		foreach ($rd->result() as $key => $value) {
			$the_filter = array(
				'idpaket_belanja_detail_sub' => $value->idpaket_belanja_detail_sub,
				'idpaket_belanja' => $value->idpaket_belanja,
				'status' => $status,
			);

			update_status_detail_pb($the_filter);	
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
		
		
		// update status detail paket belanja
		$ci->db->where('budget_realization_detail.status', 1);
		$ci->db->where('budget_realization_detail.idbudget_realization', $idbudget_realization);
		$ci->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$ci->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$rd = $ci->db->get('budget_realization_detail');

		foreach ($rd->result() as $key => $value) {
			$the_filter = array(
				'idpaket_belanja_detail_sub' => $value->idpaket_belanja_detail_sub,
				'idpaket_belanja' => $value->idpaket_belanja,
				'status' => $status,
			);

			update_status_detail_pb($the_filter);	
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}

	// update status verifikasi dokumen
	function update_status_document_verification() {

	}

	// update status nota pencairan dana
	function update_status_npd() {

	}

	
	// hitung volume yang sudah terealisasi
	function calculate_realisasi_volume($idpaket_belanja_detail_sub) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$ci->db->where('status', 1);
		$ci->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$ci->db->select_sum('volume');
		$ppd = $ci->db->get('purchase_plan_detail');

		$volume_realization = 0;
		if ($ppd->num_rows() > 0) {
			$volume_realization = $ppd->row()->volume;
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




	// update status realisasi anggaran
	// untuk mengakomodir add_product (tambah produk); save_verification (simpan transaksi verifikasi); delete_order (hapus detail transaksi verifikasi); delete_verifikasi_dokumen (hapus transaksi verifikasi); approval (persetujuan verifikasi)
    function xx_update_status_realisasi_anggaran($arr) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idverification = azarr($arr, 'idverification');
		$idverification_detail = azarr($arr, 'idverification_detail');
		$type = azarr($arr, 'type');

		$ci->db->where('verification.idverification', $idverification);
		if (strlen($idverification_detail) > 0) {
			$ci->db->where('verification_detail.idverification_detail', $idverification_detail);
		}
		$ci->db->where('verification.verification_status != "DRAFT" ');
		$ci->db->where('verification.status', 1);
		$ci->db->where('verification_detail.status', 1);
		$ci->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$trx = $ci->db->get('verification');

		foreach ($trx->result() as $key => $value) {
			$idtransaction = $value->idtransaction;

			$update_data = array(
				'transaction_status' => $type,
				'updated_status' => date('Y-m-d H:i:s'),
			);
			
			$ci->db->where('idtransaction', $idtransaction);
			$ci->db->update('transaction', $update_data);
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}



	// update status verifikasi dokumen
	function xx_update_status_verifikasi_dokumen($arr) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idnpd = azarr($arr, 'idnpd');
		$idnpd_detail = azarr($arr, 'idnpd_detail');
		$type = azarr($arr, 'type');

		$ci->db->where('npd.idnpd', $idnpd);
		if (strlen($idnpd_detail) > 0) {
			$ci->db->where('npd_detail.idnpd_detail', $idnpd_detail);
		}
		$ci->db->where('npd.npd_status != "DRAFT" ');	
		$ci->db->where('npd.status', 1);
		$ci->db->where('npd_detail.status', 1);
		$ci->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$trx = $ci->db->get('npd');
		// echo"<pre>"; print_r($ci->db->last_query()); die;

		foreach ($trx->result() as $key => $value) {
			$idverification = $value->idverification;

			$update_data = array(
				'verification_status' => $type,
				'updated_status' => date('Y-m-d H:i:s'),
			);
			
			$ci->db->where('idverification', $idverification);
			$ci->db->update('verification', $update_data);


			// update status realisasi anggaran 
			$the_filter = array(
				'idverification' => $idverification,
				'type' => $type
			);
			update_status_realisasi_anggaran($the_filter);
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}


	// update status nota pencairan dana (NPD)
	function xx_update_status_npd($arr) {
		$ci =& get_instance();

		$err_code = 0;
		$err_message = '';

		$idnpd = azarr($arr, 'idnpd');
		$idnpd_detail = azarr($arr, 'idnpd_detail');
		$type = azarr($arr, 'type');

		$ci->db->where('npd.idnpd', $idnpd);
		if (strlen($idnpd_detail) > 0) {
			$ci->db->where('npd_detail.idnpd_detail', $idnpd_detail);
		}
		$ci->db->where('npd.npd_status != "DRAFT" ');	
		$ci->db->where('npd.status', 1);
		$ci->db->where('npd_detail.status', 1);
		$ci->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$trx = $ci->db->get('npd');
		// echo"<pre>"; print_r($ci->db->last_query()); die;

		foreach ($trx->result() as $key => $value) {
			$idverification = $value->idverification;

			$update_data = array(
				'verification_status' => $type,
				'updated_status' => date('Y-m-d H:i:s'),
			);
			
			$ci->db->where('idverification', $idverification);
			$ci->db->update('verification', $update_data);


			// update status realisasi anggaran 
			$the_filter = array(
				'idverification' => $idverification,
				'type' => $type
			);
			update_status_realisasi_anggaran($the_filter);
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		return $ret;
	}





