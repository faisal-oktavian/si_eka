<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

    function update_status_pake_belanja($arr) {
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


