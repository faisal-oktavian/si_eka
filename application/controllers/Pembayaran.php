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

		$crud->set_column(array('#', 'Tanggal Input Dokumen', 'Tanggal Verifikasi Dokumen', 'Nomor Dokumen', 'Detail', 'Total Anggaran', 'User Input', 'User Verifikasi', azlang('Action')));
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

        $crud->set_select('verification.idverification, date_format(verification_date_created, "%d-%m-%Y %H:%i:%s") as txt_date_input, date_format(confirm_verification_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_verification, verification_code, "" as detail,  "" as total_anggaran, user_created.name as user_input, user_confirm.name as user_verifikasi');

        $crud->set_select_table('idverification, txt_date_input, txt_confirm_verification, verification_code, detail, total_anggaran, user_input, user_verifikasi');
        $crud->set_sorting('verification_code, user_input, user_verifikasi');
        $crud->set_filter('verification_code, user_input, user_verifikasi');
		$crud->set_id($this->controller);
		$crud->set_select_align(', , , , right');

        $crud->add_join_manual('user user_created', 'verification.iduser_created = user_created.iduser', 'left');
        $crud->add_join_manual('user user_confirm', 'verification.iduser_verification = user_confirm.iduser', 'left');
        
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
			$idverification = azarr($data, 'idverification');

			$total_anggaran = 0;
			$total_anggaran = $this->calculate_total_anggaran($idverification);

			$total_anggaran = az_thousand_separator($total_anggaran);

			return $total_anggaran;
		}

		if ($key == 'action') {
            $idverification = azarr($data, 'idverification');
			
			$btn = '<button class="btn btn-success btn-xs btn-payment" data_id="'.$idverification.'"><span class="fas fa-money-bill-wave"></span> Bayar</button>';
			$btn .= '<button type="button" class="btn btn-xs btn-primary btn-payment-log" data-id="' . $idverification . '">Riwayat Angsuran</button>';

			return $btn;
		}

		return $value;
	}
    
	public function edit()
	{
		$id = $this->input->get('id');

		$total_cicilan = 0;
		$total_lack = 0;

		// $this->db->select('transaction.idtransaction as helper_idtransaction, transaction.total_dp, transaction.total_lack,sum(debt_log.total_pay) as total_cicilan, grand_total_price, ifnull(total_return,0) as total_return,');
		// $this->db->where('transaction.idtransaction', $id);
		// $this->db->join('debt_log', 'debt_log.idtransaction = transaction.idtransaction', 'left');
		// $this->db->where('is_debt', 1);
		// $data = $this->db->get('transaction')->row();

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
}
