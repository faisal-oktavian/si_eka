<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('pembayaran');
        $this->table = 'verification';
        $this->controller = 'pembayaran';
        $this->load->helper('az_crud');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal Input Dokumen', 'Tanggal Verifikasi Dokumen', 'Tanggal Pembayaran', 'Nomor Dokumen', 'Detail', 'Total Anggaran', 'Total Bayar', 'User Bendahara', azlang('Action')));
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
		$crud->add_aodata('verification_code', 'verification_code');

		$vf = $this->load->view('pembayaran/vf_pembayaran', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'pembayaran';
		$view = $this->load->view('pembayaran/v_format_pembayaran', $data, true);
		$azapp->add_content($view);

		$debt_payment_date = $azapp->add_datetime();
		$debt_payment_date->set_id('debt_payment_date');
		$debt_payment_date->set_name('debt_payment_date');
		$debt_payment_date->set_value(Date('d-m-Y H:i:s'));
		$data_modal['debt_payment_date'] = $debt_payment_date->render();

		$v_modal = $this->load->view('pembayaran/v_modal_pembayaran', $data_modal, true);
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

		$js = az_add_js('pembayaran/vjs_pembayaran');
		$azapp->add_js($js);

		$data_header['title'] = 'Pembayaran';
		$data_header['breadcrumb'] = array('pembayaran');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$verification_code = $this->input->get('verification_code');

        $crud->set_select('verification.idverification, date_format(verification_date_created, "%d-%m-%Y %H:%i:%s") as txt_date_input, date_format(confirm_verification_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_verification, date_format(verification.confirm_payment_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_payment_date, verification_code, "" as detail,  "" as total_anggaran, total_pay, user_bendahara.name as user_bendahara');

        $crud->set_select_table('idverification, txt_date_input, txt_confirm_verification, txt_confirm_payment_date, verification_code, detail, total_anggaran, total_pay, user_bendahara');
        $crud->set_sorting('verification_code, user_bendahara');
        $crud->set_filter('verification_code, user_bendahara');
		$crud->set_id($this->controller);
		$crud->set_select_align(', , , , , right, right');

        // $crud->add_join_manual('user user_created', 'verification.iduser_created = user_created.iduser', 'left');
        // $crud->add_join_manual('user user_confirm', 'verification.iduser_verification = user_confirm.iduser', 'left');
        $crud->add_join_manual('user user_bendahara', 'verification.iduser_payment = user_bendahara.iduser', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(verification.verification_date_created) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(verification.verification_date_created) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($verification_code) > 0) {
			$crud->add_where('verification.verification_code = "' . $verification_code . '"');
		}

		$crud->add_where("verification.status = 1");
		$crud->add_where("verification.verification_status != 'DRAFT' ");
		$crud->add_where("verification.status_approve = 'DISETUJUI' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('verification_date_created desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idverification = azarr($data, 'idverification');
		$total_anggaran = $this->calculate_total_anggaran($idverification);
		
		if ($key == 'detail') {
			$idverification = azarr($data, 'idverification');
			
			$this->db->where('verification.idverification', $idverification);
			$this->db->where('verification_detail.status', 1);
			$this->db->where('transaction_detail.status', 1);
			
			$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
			$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
			$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = transaction_detail.iduraian');
			$this->db->select('transaction.idtransaction, transaction.transaction_code, paket_belanja.nama_paket_belanja, verification.verification_status, verification.idverification, verification_detail.idverification_detail');
			$this->db->group_by('transaction.idtransaction, transaction.transaction_code, paket_belanja.nama_paket_belanja, verification.verification_status, verification.idverification, verification_detail.idverification_detail');
			$verif_detail = $this->db->get('verification');

			$table = "<table class='table table-bordered table-condensed' id='table_realisasi'>";
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th>Nomor Invoice</th>";
			$table .=			"<th width='auto'>Nama Paket Belanja</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";
			
			foreach ((array) $verif_detail->result_array() as $key => $value) {
				$table .=	"<tr>";
				$table .=		"<td>".$value['transaction_code']."</td>";
				$table .=		"<td>".$value['nama_paket_belanja']."</td>";
				$table .=	"</tr>";
			}

			$table .=	"</tbody>";
			$table .= "</table>";

			return $table;
		}

		if ($key == 'total_anggaran') {
			// $total_anggaran = 0;
			$total_anggaran = az_thousand_separator($total_anggaran);

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
            $idverification = azarr($data, 'idverification');
            $total_pay = azarr($data, 'total_pay');
			$btn = '';
			
			if ( ($total_anggaran != $total_pay) OR (strlen($total_pay) == 0 || $total_pay == 0) ) {
				$btn .= '<button class="btn btn-success btn-xs btn-payment" data_id="'.$idverification.'"><span class="fas fa-money-bill-wave"></span> Bayar</button>';
			}
			$btn .= '<button type="button" class="btn btn-xs btn-primary btn-payment-log" data_id="' . $idverification . '">Riwayat Pembayaran</button>';

			return $btn;
		}

		return $value;
	}
    
	public function edit()
	{
		$id = $this->input->get('id');

		$total_cicilan = 0;
		$total_lack = 0;

		$total_anggaran = $this->calculate_total_anggaran($id);
		$total_lack = floatval($total_anggaran) - floatval($total_cicilan);
		$total_lack = $total_lack * -1;

		$data = array(
			'total_anggaran' => $total_anggaran,
			'total_cicilan' => $total_cicilan,
			'total_lack' => $total_lack,
		);

		echo json_encode($data);
	}

	function calculate_total_anggaran($idverification) {
		$this->db->where('verification.idverification', $idverification);
		$this->db->where('verification.status', 1);
		$this->db->where('transaction.transaction_status != "DRAFT" ');
		$this->db->where('verification_detail.status', 1);

		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
		$this->db->select('sum(total_realisasi) as total_anggaran');
		$verif = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$total_anggaran = azobj($verif->row(), 'total_anggaran', 0);

		return $total_anggaran;
	}

	public function save()
	{
		$err_code = 0;
		$err_message = '';

		$this->db->trans_begin();

		// input payment
		$idverification = $this->input->post('idverification');
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
		$this->db->where('idverification', $idverification);
		$this->db->where('status', 1);
		$verification_data = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		if ($verification_data->num_rows() == 0) {
			$err_code++;
			$err_message = "Transaksi tidak ditemukan";
		} 
		else {

			if ($verification_data->row()->verification_status == 'SUDAH DIBAYAR BENDAHARA') {
				$err_code++;
				$err_message = "Transaksi sudah dibayar";
			}

			if ($err_code == 0) {
				$total_anggaran = $this->calculate_total_anggaran($idverification);

				$verification_data = $verification_data->row();
				$total_pay = $verification_data->total_pay + $total_pay;
				$lack = $total_pay - $total_anggaran;

				if ($lack > 0) {
					$total_cash = $lack;
				}
			}
		}


		// validasi pembayaran non tunai tidak boleh melebihi total anggaran
		if ($err_code == 0) {
			$data_payment = $verification_data->total_pay;
			if (strlen($data_payment) > 0 || $data_payment > 0) {
				$err_code++;
				$err_message = "Dokumen <b>" . $verification_data->row()->verification_code . "</b> sudah dibayar";
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
				'verification_status' => 'SUDAH DIBAYAR BENDAHARA',
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
			
			az_crud_save($idverification, 'verification', $data_pembayaran);
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

		$this->db->where('idverification', $id);
		$this->db->where('status', 1);
		$this->db->where('verification_status != "DRAFT" ');
		$this->db->where('status_approve = "DISETUJUI" ');
		$this->db->select('date_format(confirm_payment_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_payment_date, verification_code, total_cash, total_debet, total_credit, total_transfer, total_pay');
		$payment = $this->db->get('verification');

		if ($payment->num_rows() > 0) {
			$data['payment'] = $payment;

			$view = $this->load->view('pembayaran/v_debt_log', $data, true);
			$ret = array('success' => true, 'view' => $view);
		} else {
			$ret = array('success' => false, 'message' => "Data Transaksi tidak valid");
		}

		echo json_encode($ret);
	}
}
