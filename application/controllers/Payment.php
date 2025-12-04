<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('payment');
        $this->table = 'npd';
        $this->controller = 'payment';
        $this->load->helper('az_crud');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal NPD', 'Tanggal Pembayaran', 'Nomor NPD', 'Keterangan', 'Detail', 'Status', 'Total Anggaran', 'Total Bayar', 'User Bendahara', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
		$crud->set_btn_add(false);

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		$date1->set_value('01-'.Date('m-Y'));
		// $date1->set_value('01-01-'.Date('Y'));
		$data['date1'] = $date1->render();

		$date2 = $azapp->add_datetime();
		$date2->set_id('date2');
		$date2->set_name('date2');
		$date2->set_format('DD-MM-YYYY');
		$date2->set_value(Date('t-m-Y'));
		$data['date2'] = $date2->render();

		$crud->add_aodata('date1', 'date1');
		$crud->add_aodata('date2', 'date2');
		$crud->add_aodata('npd_code', 'npd_code');
		$crud->add_aodata('vf_npd_status', 'vf_npd_status');

		$vf = $this->load->view('payment/vf_payment', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'payment';
		$view = $this->load->view('payment/v_format_payment', $data, true);
		$azapp->add_content($view);

		$debt_payment_date = $azapp->add_datetime();
		$debt_payment_date->set_id('debt_payment_date');
		$debt_payment_date->set_name('debt_payment_date');
		$debt_payment_date->set_value(Date('d-m-Y H:i:s'));
		$data_modal['debt_payment_date'] = $debt_payment_date->render();

		$v_modal = $this->load->view('payment/v_modal_payment', $data_modal, true);
		$modal = $azapp->add_modal();
		$modal->set_id('payment');
		$modal->set_modal_title('Pembayaran');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save-payment' => "Simpan Pembayaran"));
		$azapp->add_content($modal->render());

		$modal2 = $azapp->add_modal();
		$modal2->set_id('debt-log-payment');
		$modal2->set_modal_title('Log Pembayaran');
		$modal2->set_modal('<div class="container-debt-log"></div>');
		$azapp->add_content($modal2->render());

		$js = az_add_js('payment/vjs_payment');
		$azapp->add_js($js);

		$data_header['title'] = 'Pembayaran';
		$data_header['breadcrumb'] = array('payment');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$npd_code = $this->input->get('npd_code');
		$npd_status = $this->input->get('vf_npd_status');

		$crud->set_select('npd.idnpd, date_format(npd_date_created, "%d-%m-%Y %H:%i:%s") as txt_npd_date_created, date_format(npd.confirm_payment_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_payment_date, npd.npd_code, "" as type_code, "" as detail, npd.npd_status, npd.total_anggaran, npd.total_pay, user_bendahara.name as user_bendahara');

        $crud->set_select_table('idnpd, txt_npd_date_created, txt_confirm_payment_date, npd_code, type_code, detail, npd_status, total_anggaran, total_pay, user_bendahara');
        $crud->set_sorting('npd_code, npd_status, total_anggaran, total_pay, user_bendahara');
        $crud->set_filter('npd_code, npd_status, total_anggaran, total_pay, user_bendahara');
		$crud->set_id($this->controller);
		$crud->set_select_align(', , , , center, right, right');

        $crud->add_join_manual('user user_bendahara', 'npd.iduser_payment = user_bendahara.iduser', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(npd.npd_date_created) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(npd.npd_date_created) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($npd_code) > 0) {
			$crud->add_where('npd.npd_code = "' . $npd_code . '"');
		}
		if (strlen($npd_status) > 0) {
			$crud->add_where('npd.npd_status = "' . $npd_status . '"');
		}

		$crud->add_where("npd.status = 1");
		$crud->add_where("npd.npd_status IN ('MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('npd_date_created desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {

		$idnpd = azarr($data, 'idnpd');
		
		$this->db->where('npd.idnpd', $idnpd);
		$this->db->where('npd.status', 1);
		$this->db->where('npd_detail.status', 1);
		
		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		
		$this->db->select('npd.idnpd, npd_detail.idnpd_detail, npd_detail.idverification, verification.verification_code, npd.npd_status');
		$npd = $this->db->get('npd');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$table = "<table class='table table-bordered table-condensed' id='table_dokumen'>";
		$table .=	"<thead>";
		$table .=		"<tr>";
		$table .=			"<th width='300px'>Nama Paket Belanja</th>";
		$table .=			"<th width='200px'>Uraian</th>";
		$table .=			"<th width='50px'>Volume</th>";
		$table .=			"<th width='150px'>Keterangan</th>";
		$table .=		"</tr>";
		$table .=	"</thead>";
		$table .=	"<tbody>";

		foreach ($npd->result() as $npd_key => $npd_value) {

			$this->db->where('verification.idverification', $npd_value->idverification);
			$this->db->where('verification.status', 1);
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization_detail.status', 1);
			// $this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
			$this->db->where('verification.status_approve = "DISETUJUI" ');

			$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('verification.idverification, verification.verification_code, budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail, budget_realization_detail.realization_detail_description, contract_spt, contract_invitation_number, contract_sp, contract_spk, contract_honor');
			$verification = $this->db->get('verification');
			// echo "<pre>"; print_r($this->db->last_query());die;

			foreach ($verification->result() as $verification_key => $c_value) {
				$table .=	"<tr>";
				$table .=		"<td>".$c_value->nama_paket_belanja."</td>";
				$table .=		"<td>".$c_value->nama_sub_kategori."</td>";
				$table .=		"<td>".az_thousand_separator($c_value->volume)."</td>";
				$table .= 		"<td>".$c_value->realization_detail_description."</td>";
				$table .=	"</tr>";
			}
		}
		
		$table .=	"</tbody>";
		$table .= "</table>";
		

		if ($key == 'detail') {

			return $table;
		}

		if ($key == "type_code") {
			$contract_spt = $verification->row()->contract_spt;
			$contract_invitation_number = $verification->row()->contract_invitation_number;
			$contract_sp = $verification->row()->contract_sp;
			$contract_spk = $verification->row()->contract_spk;
			$contract_honor = $verification->row()->contract_honor;
			
			if (strlen($contract_spt) > 0) {
				$text = "No. SPT : ".$contract_spt." ";
			}
			else if (strlen($contract_invitation_number) > 0) {
				$text = "No. Undangan : ".$contract_invitation_number." ";
			}
			else if (strlen($contract_sp) > 0) {
				$text = "No. SP : ".$contract_sp." ";
			}
			else if (strlen($contract_spk) > 0) {
				$text = "No. SPK : ".$contract_spk." ";
			}
			else if (strlen($contract_honor) > 0) {
				$text = "Gaji/Honor : ".$contract_honor." ";
			}

			return $text;
		}

		if ($key == 'npd_status') {
			$lbl = 'default';
			$tlbl = '-';
			if ($value == "MENUNGGU PEMBAYARAN") {
				$lbl = 'info';
				$tlbl = 'Menunggu Pembayaran';
			}
			else if ($value == "SUDAH DIBAYAR BENDAHARA") {
				$lbl = 'success';
				$tlbl = 'Sudah Dibayar Bendahara';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'total_anggaran') {
			// $total_anggaran = 0;
			$total_anggaran = az_thousand_separator($value);

			return $total_anggaran;
		}

		if ($key == 'total_pay') {

			if (strlen($value) == 0 || $value == 0) {
				$total_pay = 0;
			}
			else {
				$total_pay = az_thousand_separator($value);
			}

			return $total_pay;
		}

		if ($key == 'action') {
            $idnpd = azarr($data, 'idnpd');
            $total_pay = azarr($data, 'total_pay');
			$total_anggaran = azarr($data, 'total_anggaran');
			
			$btn = '';
			
			if ( ($total_anggaran != $total_pay) OR (strlen($total_pay) == 0 || $total_pay == 0) ) {
				$btn .= '<button class="btn btn-success btn-xs btn-payment" data_id="'.$idnpd.'"><span class="fas fa-money-bill-wave"></span> Bayar</button>';
			}
			$btn .= '<button type="button" class="btn btn-xs btn-primary btn-payment-log" data_id="' . $idnpd . '">Riwayat Pembayaran</button>';

			return $btn;
		}

		return $value;
	}
    
	public function edit()
	{
		$id = $this->input->get('id');

		$total_cicilan = 0;
		$total_lack = 0;
		$total_anggaran = 0;

		$this->db->where('idnpd', $id);
		$this->db->select('total_anggaran');
		$npd = $this->db->get('npd');
		
		if ($npd->num_rows() > 0) {
			$total_anggaran = $npd->row()->total_anggaran;
		} 
		else {
			$total_anggaran = 0;
		}

		$total_lack = floatval($total_anggaran) - floatval($total_cicilan);
		$total_lack = $total_lack * -1;

		$data = array(
			'total_anggaran' => $total_anggaran,
			'total_cicilan' => $total_cicilan,
			'total_lack' => $total_lack,
		);

		echo json_encode($data);
	}

	public function save()
	{
		$err_code = 0;
		$err_message = '';

		$this->db->trans_begin();

		// input payment
		$idnpd = $this->input->post('idnpd');
		$payment_description = $this->input->post('payment_description');
		$total_cash = doubleval(az_crud_number($this->input->post('total_cash')));
		$total_transfer = doubleval(az_crud_number($this->input->post('total_transfer')));
		$total_credit = doubleval(az_crud_number($this->input->post('total_credit')));
		$total_debet = doubleval(az_crud_number($this->input->post('total_debet')));
		$confirm_payment_date = $this->input->post('debt_payment_date');
		$date_now = Date('Y-m-d H:i:s');

		if (strlen($confirm_payment_date) > 0) {
			$confirm_payment_date = az_crud_date($confirm_payment_date);
		}
		else {
			$confirm_payment_date = $date_now;
		}

		$debet_number = $this->input->post('debet_number');
		$credit_number = $this->input->post('credit_number');
		$transfer_number = $this->input->post('transfer_number');

		$total_card = doubleval($total_debet) + doubleval($total_credit) + doubleval($total_transfer);
		$total_pay = doubleval($total_card) + doubleval($total_cash);
		$total_pay_debt = doubleval($total_card) + doubleval($total_cash);


		if (strlen($total_pay_debt) == 0) {
			$total_pay_debt = 0;
		}

		if ($total_pay_debt == 0) {
			$err_code++;
			$err_message = "Pembayaran tidak boleh kosong.";
		}

		// validasi
		$this->db->where('idnpd', $idnpd);
		$this->db->where('status', 1);
		$npd_data = $this->db->get('npd');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		if ($npd_data->num_rows() == 0) {
			$err_code++;
			$err_message = "Transaksi tidak ditemukan";
		} 
		else {

			if ($npd_data->row()->npd_status == 'SUDAH DIBAYAR BENDAHARA') {
				$err_code++;
				$err_message = "Transaksi sudah dibayar";
			}

			if ($err_code == 0) {
				$total_anggaran = $npd_data->row()->total_anggaran;

				$npd_data = $npd_data->row();
				$total_pay = $npd_data->total_pay + $total_pay;
				$lack = $total_pay - $total_anggaran;

				if ($lack > 0) {
					$total_cash = $lack;
				}
			}
		}


		// validasi pembayaran non tunai tidak boleh melebihi total anggaran
		if ($err_code == 0) {
			$data_payment = $npd_data->total_pay;
			if (strlen($data_payment) > 0 || $data_payment > 0) {
				$err_code++;
				$err_message = "Dokumen <b>" . $npd_data->row()->verification_code . "</b> sudah dibayar";
			}
			else if ($total_transfer > $total_anggaran) {
				$err_code++;
				$err_message = 'Total Transfer tidak boleh melebihi total anggaran';
			} 
			else if ($total_debet > $total_anggaran) {
				$err_code++;
				$err_message = 'Total EDC DEBET tidak boleh melebihi total anggaran';
			} 
			else if ($total_credit > $total_anggaran) {
				$err_code++;
				$err_message = 'Total EDC KREDIT tidak boleh melebihi total anggaran';
			}
			else if ($total_pay_debt < $total_anggaran) {
				$err_code++;
				$err_message = 'Total Pembayaran tidak boleh kurang dari total anggaran';
			}
			else if ($total_pay_debt > $total_anggaran) {
				$err_code++;
				$err_message = 'Total Pembayaran tidak boleh melebihi dari total anggaran';
			}
		}

		if ($err_code == 0) {
			$data_pembayaran  = array(
				'iduser_payment' => $this->session->userdata('iduser'),
				'total_anggaran' => $total_anggaran,
				'total_cash' => $total_cash,
				'total_debet' => $total_debet,
				'total_credit' => $total_credit,
				'total_transfer' => $total_transfer,
				'debet_number' => $debet_number,
				'credit_number' => $credit_number,
				'transfer_number' => $transfer_number,
				'npd_status' => 'SUDAH DIBAYAR BENDAHARA',
				'confirm_payment_date' => $confirm_payment_date,
				'payment_description' => $payment_description,
			);

			$data_pembayaran['total_pay'] = $total_pay_debt;
			$data_pembayaran['total_debt'] = $lack;
			if ($lack >= 0) {
				$data_pembayaran['total_pay'] = $total_pay_debt - $lack;
				$data_pembayaran['total_debt'] = 0;
			}
			// echo "<pre>"; print_r($data_pembayaran); die;
			
			az_crud_save($idnpd, 'npd', $data_pembayaran);



			// update status npd
			$the_filter = array(
				'idnpd' => $idnpd,
				'status' => 'SUDAH DIBAYAR BENDAHARA'
			);
			$update_status = update_status_npd($the_filter);
		}

		if ($err_code == 0) {
			$err_message = "Pembayaran berhasil.";
		}

		if ($this->db->trans_status() === FALSE || $err_code > 0) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$ret = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);

		echo json_encode($ret);
	}

	public function debt_log()
	{
		$id = $this->input->get('id');

		$this->db->where('idnpd', $id);
		$this->db->where('status', 1);
		$this->db->where('npd_status != "DRAFT" ');
		$this->db->select('date_format(confirm_payment_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_payment_date, npd_code, total_cash, total_debet, total_credit, total_transfer, total_pay');
		$payment = $this->db->get('npd');

		if ($payment->num_rows() > 0) {
			$data['payment'] = $payment;

			$view = $this->load->view('payment/v_debt_log', $data, true);
			$ret = array('success' => true, 'view' => $view);
		} else {
			$ret = array('success' => false, 'message' => "Data Transaksi tidak valid");
		}

		echo json_encode($ret);
	}
}
