<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

	// update status realisasi anggaran
	// untuk mengakomodir add_product (tambah produk); save_verification (simpan transaksi verifikasi); delete_order (hapus detail transaksi verifikasi); delete_verifikasi_dokumen (hapus transaksi verifikasi); approval (persetujuan verifikasi)
    function update_status_realisasi_anggaran($arr) {
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
	function update_status_verifikasi_dokumen($arr) {
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
	function update_status_npd($arr) {
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





