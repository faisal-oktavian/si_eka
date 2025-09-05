<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi_dokumen extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('verifikasi_dokumen');
        // $this->table = 'verification';
        $this->table = 'transaction';
        $this->controller = 'verifikasi_dokumen';
        $this->load->helper('az_crud');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal Realisasi', 'Tanggal Verifikasi Dokumen', 'Nomor Dokumen', 'Paket Belanja', 'Status', 'Status Verifikasi', 'Keterangan Verifikasi', 'User Realisasi', 'User Verifikasi', azlang('Action')));
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
		$crud->add_aodata('transaction_code', 'transaction_code');
		$crud->add_aodata('vf_transaction_status', 'vf_transaction_status');

		$vf = $this->load->view('verifikasi_dokumen/vf_verifikasi_dokumen', $data, true);
        $crud->set_top_filter($vf);

		// if (aznav('role_crud')) {
		// 	$btn = "<button class='btn btn-primary az-btn-primary btn-add-verifikasi-dokumen' type='button'><span class='glyphicon glyphicon-plus'></span> Tambah</button>";
		// 	$crud->set_btn_top_custom($btn);
		// }

		$v_modal = $this->load->view('verifikasi_dokumen/v_verifikasi_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('verifikasi_dokumen');
		$modal->set_modal_title('Verifikasi Dokumen');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_approval'=>'Simpan'));
		$azapp->add_content($modal->render());

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'verifikasi_dokumen';
		$view = $this->load->view('verifikasi_dokumen/v_format_verifikasi_dokumen', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('verifikasi_dokumen/vjs_verifikasi_dokumen');
		$azapp->add_js($js);

		$data_header['title'] = 'Verifikasi Dokumen';
		$data_header['breadcrumb'] = array('verifikasi_dokumen');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$transaction_code = $this->input->get('transaction_code');
		$transaction_status = $this->input->get('vf_transaction_status');		

		$crud->set_select('transaction.idtransaction, verification.idverification, date_format(transaction_date, "%d-%m-%Y %H:%i:%s") as txt_date_input, date_format(confirm_verification_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_verification, transaction_code, nama_paket_belanja, transaction_status, status_approve, verification_description, user_created.name as user_input, user_confirm.name as user_verifikasi');
		$crud->set_select_table('idtransaction, txt_date_input, txt_confirm_verification, transaction_code, nama_paket_belanja, transaction_status, status_approve, verification_description, user_input, user_verifikasi');
        $crud->set_sorting('transaction_code, nama_paket_belanja, transaction_status, status_approve, verification_description');
        $crud->set_filter('transaction_code, nama_paket_belanja, transaction_status, status_approve, verification_description');
		$crud->set_id($this->controller);
		$crud->set_select_align(', , , , center, center');

        $crud->add_join_manual('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
        $crud->add_join_manual('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
        $crud->add_join_manual('verification_detail', 'verification_detail.idtransaction = transaction.idtransaction', 'left');
        $crud->add_join_manual('verification', 'verification.idverification = verification_detail.idverification', 'left');
		$crud->add_join_manual('user user_created', 'transaction.iduser_created = user_created.iduser', 'left');
        $crud->add_join_manual('user user_confirm', 'verification.iduser_verification = user_confirm.iduser', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(transaction.transaction_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(transaction.transaction_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($transaction_code) > 0) {
			$crud->add_where('transaction.transaction_code = "' . $transaction_code . '"');
		}
		if (strlen($transaction_status) > 0) {
			$crud->add_where('transaction.transaction_status = "' . $transaction_status . '"');
		}

		$crud->add_where("transaction.status = 1");
		$crud->add_where("transaction.transaction_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_group_by('transaction.idtransaction, verification.idverification, transaction_date, confirm_verification_date, transaction_code, nama_paket_belanja, transaction_status, status_approve, verification_description, user_created.name, user_confirm.name');
		$crud->set_order_by('transaction_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {

		if ($key == 'status_approve') {
			$lbl = 'default';
			$tlbl = '-';
			if ($value == "DISETUJUI") {
				$lbl = 'success';
				$tlbl = 'Disetujui';
			}
			else if ($value == "DITOLAK") {
				$lbl = 'danger';
				$tlbl = 'Ditolak';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'transaction_status') {
			// INPUT DATA 				-> data diinputkan dimenu realisasi anggaran (oleh user realisasi); 
			// MENUNGGU VERIFIKASI 		-> data diinputkan di menu verifikasi dokumen (oleh user realisasi); 
			// SUDAH DIVERIFIKASI 		-> data sudah diverifikasi (oleh user verifikator); 
			// DITOLAK VERIFIKATOR 		-> data ditolak verifikator (oleh user verifikator);
			// INPUT NPD				-> data diinputkan di menu npd (oleh user npd);
			// MENUNGGU PEMBAYARAN		-> data sudah dikirim ke bendahara (oleh user npd);
			// SUDAH DIBAYAR BENDAHARA 	-> data sudah dibayar bendahara (oleh user bendahara);

			$lbl = 'default';
			$tlbl = '-';
			if ($value == "INPUT DATA") {
				$lbl = 'warning';
				$tlbl = 'Input Data';
			}
			else if ($value == "MENUNGGU VERIFIKASI") {
				$lbl = 'info';
				$tlbl = 'Menunggu Verifikasi';
			}
			else if ($value == "SUDAH DIVERIFIKASI") {
				$lbl = 'default';
				$tlbl = 'Sudah Diverifikasi';
			}
			else if ($value == "DITOLAK VERIFIKATOR") {
				$lbl = 'danger';
				$tlbl = 'Ditolak Verifikator';
			}
			if ($value == "INPUT NPD") {
				$lbl = 'warning';
				$tlbl = 'Input NPD';
			}
			else if ($value == "MENUNGGU PEMBAYARAN") {
				$lbl = 'info';
				$tlbl = 'Menunggu Pembayaran';
			}
			else if ($value == "SUDAH DIBAYAR BENDAHARA") {
				$lbl = 'success';
				$tlbl = 'Sudah Dibayar Bendahara';
			}

			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
            $idtransaction = azarr($data, 'idtransaction');
            $idverification = azarr($data, 'idverification');
            $transaction_status = azarr($data, 'transaction_status');

			$btn = '';	
			if (in_array($transaction_status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR') ) ) {
				$btn .= '<button class="btn btn-success btn-xs btn-verifikasi-dokumen" data_id="'.$idtransaction.'" data_idverif="'.$idverification.'"><span class="glyphicon glyphicon-check"></span> Verifikasi</button>';
			}
			else {
				$btn .= '<button class="btn btn-success btn-xs btn-verifikasi-dokumen" data_id="'.$idtransaction.'" data_idverif="'.$idverification.'" disabled><span class="glyphicon glyphicon-check"></span> Verifikasi</button>';
			}

			return $btn;
		}

		return $value;
	}

	function approval() {
		$err_code = 0;
		$err_message = '';

		
		$idtransaction = $this->input->post("idtransaction");
		$idverification = $this->input->post("idverification");
		$status_approve = $this->input->post("status_approve");
		$verification_description = $this->input->post("verification_description");
		$confirm_verification_date = Date('Y-m-d H:i:s');
		$iduser_verification = $this->session->userdata('iduser');
		$transaction_status = '';

		if ($status_approve == "DISETUJUI") {
			$transaction_status = "SUDAH DIVERIFIKASI";
			$type = 'SUDAH DIVERIFIKASI';
		}
		else if ($status_approve == "DITOLAK") {
			$transaction_status = "DITOLAK VERIFIKATOR";
			$type = 'DITOLAK VERIFIKATOR';
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('status_approve', 'Status Verifikasi', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('verification_description', 'Keterangan', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			if (strlen($idtransaction) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$this->db->where('idtransaction',$idtransaction);
			$transaction = $this->db->get('transaction');

			if ($transaction->num_rows() > 0) {
				$status = $transaction->row()->transaction_status;
				if (in_array($status, array('INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {

	    	$arr_data = array(
	    		'transaction_status' => $transaction_status,
				'updated_status' => date('Y-m-d H:i:s'),
	    	);

	    	az_crud_save($idtransaction, 'transaction', $arr_data);


			// cek apakah datanya sudah ada di tabel verification
			$this->db->where('verification.status', 1);
			$this->db->where('verification_detail.status', 1);
			$this->db->where('verification_detail.idtransaction', $idtransaction);
			$this->db->where('verification.verification_status != "DRAFT" ');
			$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
			$verif = $this->db->get('verification');

			if ($verif->num_rows() > 0) {
				// jika sudah ada datanya maka update datanya
				$arr_data = array(
					'confirm_verification_date' => $confirm_verification_date,
					'verification_status' => $transaction_status,
					'updated_status' => date('Y-m-d H:i:s'),
					'status_approve' => $status_approve,
					'verification_description' => $verification_description,
					'iduser_verification' => $iduser_verification
				);

				az_crud_save($idverification, 'verification', $arr_data);
			}
			else {
				// jika belum ada datanya maka simpan datanya
				$arr_data = array(
					'verification_date_created' => $transaction->row()->transaction_date,
					'confirm_verification_date' => $confirm_verification_date,
					'verification_code' => $transaction->row()->transaction_code,
					'verification_status' => $transaction_status,
					'updated_status' => date('Y-m-d H:i:s'),
					'status_approve' => $status_approve,
					'verification_description' => $verification_description,
					'iduser_created' => $transaction->row()->iduser_created,
					'iduser_verification' => $iduser_verification
				);

				$save_verification = az_crud_save('', 'verification', $arr_data);
				$idverification = azarr($save_verification, 'insert_id');


				$arr_data_detail = array(
					'idverification' => $idverification,
					'idtransaction' => $idtransaction,
				);

				$save_verification_detail = az_crud_save('', 'verification_detail', $arr_data_detail);
				$idverification_detail = azarr($save_verification_detail, 'insert_id');
			}

			if ($status_approve == "DITOLAK") {
				$arr_filter = array(
					'idtransaction' => $idtransaction,
					'idverification' => $idverification,
					'confirm_verification_date' => $confirm_verification_date,
					'status_approve' => $status_approve,
					'verification_description' => $verification_description,
					'iduser_verification' => $iduser_verification,
				);
				
				$this->insert_history($arr_filter);
			}

			// // update status realisasi anggaran
			// $the_filter = array(
			// 	'idverification' => $idverification,
			// 	'type' => $type,
			// );
			// $update_status = update_status_realisasi_anggaran($the_filter);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idverification' => $idverification,
		);
		echo json_encode($return);
	}

	function insert_history($the_data) {
		$idtransaction = azarr($the_data, 'idtransaction');
		$idverification = azarr($the_data, 'idverification');
		$confirm_verification_date = azarr($the_data, 'confirm_verification_date');
		$status_approve = azarr($the_data, 'status_approve');
		$verification_description = azarr($the_data, 'verification_description');
		$iduser_verification = azarr($the_data, 'iduser_verification');

		$arr_data = array(
			'idtransaction' => $idtransaction,
			'idverification' => $idverification,
			'confirm_verification_date' => $confirm_verification_date,
			'status_approve' => $status_approve,
			'verification_description' => $verification_description,
			'iduser_verification' => $iduser_verification,
		);

		az_crud_save('', 'verification_history', $arr_data);
	}


	// fungsi dibawah sudah tidak digunakan
	//////////////////////////////////////////////////////
	//////////////////////////////////////////////////////

	public function index_xxx() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal Input Dokumen', 'Tanggal Verifikasi Dokumen', 'Nomor Dokumen', 'Detail', 'Status', 'Status Verifikasi', 'Keterangan Verifikasi', 'User Input', 'User Verifikasi', azlang('Action')));
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
		$crud->add_aodata('vf_verification_status', 'vf_verification_status');

		$vf = $this->load->view('verifikasi_dokumen/vf_verifikasi_dokumen', $data, true);
        $crud->set_top_filter($vf);

		if (aznav('role_crud')) {
			$btn = "<button class='btn btn-primary az-btn-primary btn-add-verifikasi-dokumen' type='button'><span class='glyphicon glyphicon-plus'></span> Tambah</button>";
			$crud->set_btn_top_custom($btn);
		}

		$v_modal = $this->load->view('verifikasi_dokumen/v_verifikasi_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('verifikasi_dokumen');
		$modal->set_modal_title('Verifikasi Dokumen');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_approval'=>'Simpan'));
		$azapp->add_content($modal->render());

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'verifikasi_dokumen';
		$view = $this->load->view('verifikasi_dokumen/v_format_verifikasi_dokumen', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('verifikasi_dokumen/vjs_verifikasi_dokumen');
		$azapp->add_js($js);

		$data_header['title'] = 'Verifikasi Dokumen';
		$data_header['breadcrumb'] = array('verifikasi_dokumen');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_xxx() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$verification_code = $this->input->get('verification_code');
		$verification_status = $this->input->get('vf_verification_status');

        $crud->set_select('verification.idverification, date_format(verification_date_created, "%d-%m-%Y %H:%i:%s") as txt_date_input, date_format(confirm_verification_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_verification, verification_code, "" as detail, verification_status, status_approve, verification_description, user_created.name as user_input, user_confirm.name as user_verifikasi');

        $crud->set_select_table('idverification, txt_date_input, txt_confirm_verification, verification_code, detail, verification_status, status_approve, verification_description, user_input, user_verifikasi');
        $crud->set_sorting('verification_code, status_approve, verification_description, verification_status, user_input, user_verifikasi');
        $crud->set_filter('verification_code, status_approve, verification_description, verification_status, user_input, user_verifikasi');
		$crud->set_id($this->controller);
		$crud->set_select_align(', , , , center, center');

        $crud->add_join_manual('user user_created', 'verification.iduser_created = user_created.iduser', 'left');
        $crud->add_join_manual('user user_confirm', 'verification.iduser_verification = user_confirm.iduser', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(verification.verification_date_created) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(verification.verification_date_created) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($verification_code) > 0) {
			$crud->add_where('verification.verification_code = "' . $verification_code . '"');
		}
		if (strlen($verification_status) > 0) {
			$crud->add_where('verification.verification_status = "' . $verification_status . '"');
		}

		$crud->add_where("verification.status = 1");
		$crud->add_where("verification.verification_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('verification_date_created desc');
		echo $crud->get_table();
	}

	function custom_style_xxx($key, $value, $data) {
		
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

		if ($key == 'status_approve') {
			$lbl = 'default';
			$tlbl = '-';
			if ($value == "DISETUJUI") {
				$lbl = 'success';
				$tlbl = 'Disetujui';
			}
			else if ($value == "DITOLAK") {
				$lbl = 'danger';
				$tlbl = 'Ditolak';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'verification_status') {
			// INPUT DATA 				-> data diinputkan dimenu realisasi anggaran (oleh user realisasi); 
			// MENUNGGU VERIFIKASI 		-> data diinputkan di menu verifikasi dokumen (oleh user realisasi); 
			// SUDAH DIVERIFIKASI 		-> data sudah diverifikasi (oleh user verifikator); 
			// DITOLAK VERIFIKATOR 		-> data ditolak verifikator (oleh user verifikator);
			// INPUT NPD				-> data diinputkan di menu npd (oleh user npd);
			// MENUNGGU PEMBAYARAN		-> data sudah dikirim ke bendahara (oleh user npd);
			// SUDAH DIBAYAR BENDAHARA 	-> data sudah dibayar bendahara (oleh user bendahara);

			$lbl = 'default';
			$tlbl = '-';
			if ($value == "INPUT DATA") {
				$lbl = 'warning';
				$tlbl = 'Input Data';
			}
			else if ($value == "MENUNGGU VERIFIKASI") {
				$lbl = 'info';
				$tlbl = 'Menunggu Verifikasi';
			}
			else if ($value == "SUDAH DIVERIFIKASI") {
				$lbl = 'default';
				$tlbl = 'Sudah Diverifikasi';
			}
			else if ($value == "DITOLAK VERIFIKATOR") {
				$lbl = 'danger';
				$tlbl = 'Ditolak Verifikator';
			}
			if ($value == "INPUT NPD") {
				$lbl = 'warning';
				$tlbl = 'Input NPD';
			}
			else if ($value == "MENUNGGU PEMBAYARAN") {
				$lbl = 'info';
				$tlbl = 'Menunggu Pembayaran';
			}
			else if ($value == "SUDAH DIBAYAR BENDAHARA") {
				$lbl = 'success';
				$tlbl = 'Sudah Dibayar Bendahara';
			}

			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
            $idverification = azarr($data, 'idverification');

			$btn = '';
			if (aznav('role_crud')) {
				$btn .= '<button class="btn btn-default btn-xs btn-edit-verifikasi-dokumen" data_id="'.$idverification.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
				$btn .= '<button class="btn btn-danger btn-xs btn-delete-verifikasi-dokumen" data_id="'.$idverification.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

				$this->db->where('idverification', $idverification);
				$verif = $this->db->get('verification');

				$verif_status = $verif->row()->verification_status;
				if (in_array($verif_status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
					$btn = '<button class="btn btn-info btn-xs btn-view-only-verifikasi-dokumen" data_id="'.$idverification.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';
				}
			}
			
			if (aznav('role_verificator')) {
				if (in_array($verif_status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR') ) ) {
					$btn .= '<button class="btn btn-success btn-xs btn-verifikasi-dokumen" data_id="'.$idverification.'"><span class="glyphicon glyphicon-check"></span> Verifikasi</button>';
				}
				else {
					$btn .= '<button class="btn btn-success btn-xs btn-verifikasi-dokumen" data_id="'.$idverification.'" disabled><span class="glyphicon glyphicon-check"></span> Verifikasi</button>';
				}
			}

			return $btn;
		}

		return $value;
	}
    
    function add($id = '') {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

        $data = array(
            'id' => $id,
            'iduser_created' => $this->session->userdata('iduser'),
            'user_name' => $this->session->userdata('name'),
        );
        
		$view = $this->load->view('verifikasi_dokumen/v_verifikasi_dokumen', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('verifikasi_dokumen/v_verifikasi_dokumen_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add');
		$modal->set_modal_title('Tambah Realisasi');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('verifikasi_dokumen/vjs_verifikasi_dokumen_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Verifikasi Dokumen';
		$data_header['breadcrumb'] = array('verifikasi_dokumen');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function edit($id) {
		$this->db->where('idverification', $id);
		$check = $this->db->get('verification');
		if ($check->num_rows() == 0) {
			redirect(app_url().'verifikasi_dokumen');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->verification_status;
			if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
				redirect(app_url().'verifikasi_dokumen');
			}
		}
		$this->add($id);
	}

    function search_realisasi_anggaran() {
		$keyword = $this->input->get("term");

		$this->db->group_start();
		$this->db->like('nama_paket_belanja', $keyword);
		$this->db->or_like('transaction.transaction_code', $keyword);
		$this->db->group_end();
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction.transaction_status = "INPUT DATA" ');
		$this->db->where('transaction_detail.status', 1);

		$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->join('paket_belanja', 'transaction_detail.idpaket_belanja = paket_belanja.idpaket_belanja');
		
		$this->db->group_by('transaction.idtransaction, transaction_detail.idpaket_belanja');
		$this->db->order_by("transaction.idtransaction");
		$this->db->select('transaction.idtransaction as id, transaction_detail.idpaket_belanja, concat(transaction.transaction_code, " - ", paket_belanja.nama_paket_belanja, " - (", date_format(transaction.transaction_date, "%d-%m-%Y %H:%i:%s"), ")") as text');
		$data = $this->db->get("transaction");
		// echo "<pre>"; print_r($this->db->last_query());die;

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

    function select_paket_belanja() {
		$id = $this->input->post('id');

		$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->join('paket_belanja', 'transaction_detail.idpaket_belanja = paket_belanja.idpaket_belanja');
		$this->db->where('transaction.idtransaction', $id);
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction.transaction_status = "INPUT DATA" ');
		$this->db->where('transaction_detail.status', 1);
		$this->db->group_by('transaction.idtransaction, transaction_detail.idpaket_belanja, paket_belanja.nama_paket_belanja, transaction.transaction_code');

		$this->db->select('transaction.idtransaction, date_format(transaction_date, "%d-%m-%Y %H:%i:%s") as txt_transaction_date, transaction_code, paket_belanja.nama_paket_belanja, total_realisasi');
		$trx = $this->db->get('transaction');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'idtransaction' => $trx->row()->idtransaction,
			'transaction_code' => $trx->row()->transaction_code,
			'nama_paket_belanja' => $trx->row()->nama_paket_belanja,
			'total_realisasi' => $trx->row()->total_realisasi,
		);

		echo json_encode($ret);
	}

    function add_product() {
		$err_code = 0;
		$err_message = '';

	 	$idverification = $this->input->post('idverification');
	 	$_idverification = $this->input->post('idverification');
	 	$idverification_detail = $this->input->post('idverification_detail');
		$idtransaction = $this->input->post('idtransaction');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idtransaction', 'Paket Belanja', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			$this->db->where('idverification',$idverification);
			$verification = $this->db->get('verification');

			if ($verification->num_rows() > 0) {
				$status = $verification->row()->verification_status;
				if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {

			if (strlen($idverification) == 0) {
				$arr_verification = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'verification_date_created' => Date('Y-m-d H:i:s'),
					'verification_status' => 'DRAFT',
					'verification_code' => $this->generate_transaction_code(),
				);

				$save_verification = az_crud_save($idverification, 'verification', $arr_verification);
				$idverification = azarr($save_verification, 'insert_id');
			}
			else {
				// validasi apakah paket belanja yang diinputkan sama?
				// jika dalam 1 dokumen paket belanja yang diinputkan tidak sama, maka ditolak

				// query untuk ambil paket belanja yang sudah tersimpan
				$this->db->where('verification_detail.idverification', $idverification);
				$this->db->where('verification_detail.status', 1);
				$this->db->where('transaction.status', 1);
				$this->db->where('transaction_detail.status', 1);
				$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
				$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
				$this->db->group_by('transaction_detail.idpaket_belanja');
				$this->db->select('transaction_detail.idpaket_belanja');
				$verif_detail = $this->db->get('verification_detail');

				// query untuk ambil paket belanja dari idtransaksi yang diinputkan
				$this->db->where('transaction_detail.idtransaction', $idtransaction);
				$this->db->where('transaction_detail.status', 1);
				$this->db->group_by('transaction_detail.idpaket_belanja');
				$this->db->select('transaction_detail.idpaket_belanja');
				$trxd = $this->db->get('transaction_detail');

				// cek apakah paket belaja dari menu realisasi lebih dari 1 data
				// jika lebih dari 1 data maka data realisasi anggarannya bermasalah
				if ($trxd->num_rows() != 1) {
					$err_code++;
					$err_message = "Data paket belanja yang anda pilih bermasalah.";
				}
				else {
					$idpaket_belanja_input = $trxd->row()->idpaket_belanja;
				}

				if ($err_code == 0) {
					foreach ($verif_detail->result() as $key => $value) {
						$idpaket_belanja = $value->idpaket_belanja;

						if ($idpaket_belanja != $idpaket_belanja_input) {
							$err_code++;
							$err_message = "Paket Belanja yang anda inputkan berbeda dengan paket belanja yang telah diinputkan sebelumnya. <br>";
							$err_message .= "Silahkan menginputkan data dengan paket belanja yang sama.";
							
							break;
						}
					}
				}
			}
            
			if ($err_code == 0) {
				//transaction detail
				$arr_verification_detail = array(
					'idverification' => $idverification,
					'idtransaction' => $idtransaction,
				);
				
				$td = az_crud_save($idverification_detail, 'verification_detail', $arr_verification_detail);
				$idverification_detail = azarr($td, 'insert_id');
				

				// cek apakah datanya baru diinput / edit data
				$this->db->where('idverification', $idverification);
				$check = $this->db->get('verification');

				if ($check->row()->verification_status != "DRAFT") {
					$the_filter = array(
						'idverification' => $idverification,
						'type' => 'MENUNGGU VERIFIKASI'
					);
					$update_status = update_status_realisasi_anggaran($the_filter);
				}
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idverification' => $idverification,
			'idverification_detail' => $idverification_detail,
		);
		echo json_encode($return);
	}

	function save_verifikasi() {
		$err_code = 0;
		$err_message = '';

		
		$idverification = $this->input->post("hd_idverification");
		$verification_date_created = az_crud_date($this->input->post("verification_date_created"));
		$iduser_created = $this->input->post("iduser_created");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('verification_date_created', 'Tanggal Input', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idverification) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$this->db->where('idverification',$idverification);
			$verification = $this->db->get('verification');

			if ($verification->num_rows() > 0) {
				$status = $verification->row()->verification_status;
				if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'verification_date_created' => $verification_date_created,
	    		'verification_status' => "MENUNGGU VERIFIKASI",
	    		'iduser_created' => $iduser_created,
	    	);

	    	az_crud_save($idverification, 'verification', $arr_data);

			// update status realisasi anggaran
			$the_filter = array(
				'idverification' => $idverification,
				'type' => 'MENUNGGU VERIFIKASI'
			);
			$update_status = update_status_realisasi_anggaran($the_filter);

		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function delete_verifikasi_dokumen() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('idverification',$id);
		$verification = $this->db->get('verification');

		if ($verification->num_rows() > 0) {
			$status = $verification->row()->verification_status;
			if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";
			}
		}

		if($err_code == 0) {
			// update status realisasi anggaran
			$the_filter = array(
				'idverification' => $id,
				'type' => 'INPUT DATA'
			);
			$update_status = update_status_realisasi_anggaran($the_filter);

			az_crud_delete($this->table, $id);
		} 
		else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}

	function edit_order() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where('idverification_detail', $id);
		$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
		$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->select('verification_detail.idverification_detail, verification_detail.idverification, verification_detail.idtransaction, transaction_detail.idpaket_belanja');
		$verif_detail = $this->db->get('verification_detail')->result_array();

		$ret = array(
			'data' => azarr($verif_detail, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function delete_order() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = "";
		$message = "";
		$is_delete = true;

		$this->db->where('idverification_detail',$id);
		$this->db->join('verification', 'verification_detail.idverification = verification.idverification');
		$verification = $this->db->get('verification_detail');

		$status = $verification->row()->verification_status;
		$idverification = $verification->row()->idverification;
		if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
			$is_delete = false;
		}

		if ($is_delete) {
			$delete = az_crud_delete('verification_detail', $id, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				// update status realisasi anggaran
				$the_filter = array(
					'idverification' => $idverification,
					'idverification_detail' => $id,
					'type' => 'INPUT DATA'
				);
				$update_status = update_status_realisasi_anggaran($the_filter);	
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada realisasi/detail transaksi di verif dokumen ini?
		if ($err_code == 0) {
			$this->db->where('idverification', $idverification);
			$this->db->where('status', 1);
			$verification_detail = $this->db->get('verification_detail');

			if ($verification_detail->num_rows() == 0) {
				$arr_update = array(
					'verification_status' => 'DRAFT',
				);
				az_crud_save($idverification, 'verification', $arr_update);

				$message = 'Realisasi anggaran berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan realisasi baru, harap klik simpan transaksi verifikasi dokumen, agar datanya tidak hilang.</span>';
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
		);

		echo json_encode($return);
	}

	function approval_xxx() {
		$err_code = 0;
		$err_message = '';

		
		$idverification = $this->input->post("idverification");
		$status_approve = $this->input->post("status_approve");
		$verification_description = $this->input->post("verification_description");
		$confirm_verification_date = Date('Y-m-d H:i:s');
		$iduser_verification = $this->session->userdata('iduser');
		$verification_status = '';

		if ($status_approve == "DISETUJUI") {
			$verification_status = "SUDAH DIVERIFIKASI";
			$type = 'SUDAH DIVERIFIKASI';
		}
		else if ($status_approve == "DITOLAK") {
			$verification_status = "DITOLAK VERIFIKATOR";
			$type = 'DITOLAK VERIFIKATOR';

			$arr_filter = array(
	    		'idverification' => $idverification,
	    		'confirm_verification_date' => $confirm_verification_date,
	    		'status_approve' => $status_approve,
	    		'verification_description' => $verification_description,
	    		'iduser_verification' => $iduser_verification,
	    	);
			
			$this->insert_history($arr_filter);
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('status_approve', 'Status Verifikasi', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('verification_description', 'Keterangan', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			if (strlen($idverification) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$this->db->where('idverification',$idverification);
			$verification = $this->db->get('verification');

			if ($verification->num_rows() > 0) {
				$status = $verification->row()->verification_status;
				if (in_array($status, array('INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {

	    	$arr_data = array(
	    		'confirm_verification_date' => $confirm_verification_date,
	    		'verification_status' => $verification_status,
	    		'status_approve' => $status_approve,
	    		'verification_description' => $verification_description,
	    		'iduser_verification' => $iduser_verification,
	    	);

	    	az_crud_save($idverification, 'verification', $arr_data);

			// update status realisasi anggaran
			$the_filter = array(
				'idverification' => $idverification,
				'type' => $type,
			);
			$update_status = update_status_realisasi_anggaran($the_filter);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function get_data() {
		$id = $this->input->post('id');

		$this->db->where('verification.idverification', $id);
		$this->db->join('user', 'user.iduser = verification.iduser_created');
		$this->db->select('date_format(verification_date_created, "%d-%m-%Y %H:%i:%s") as txt_verification_date_created, verification_code, user.name as user_created, verification.iduser_created');
		$this->db->order_by('verification_date_created', 'desc');
		$verification = $this->db->get('verification')->result_array();

		$this->db->where('idverification', $id);
		$verification_detail = $this->db->get('verification_detail')->result_array();

		$return = array(
			'verification' => azarr($verification, 0),
			'verification_detail' => $verification_detail
		);
		echo json_encode($return);
	}

    function get_list_order() {
		$idverification = $this->input->post("idverification");

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
		// echo "<pre>"; print_r($this->db->last_query()); die;

        $data['detail'] = $verif_detail->result_array();

		$view = $this->load->view('verifikasi_dokumen/v_verifikasi_dokumen_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

    private function generate_transaction_code() {
		$this->db->where('day(verification_date_created)', Date('d'));
		$this->db->where('month(verification_date_created)', Date('m'));
		$this->db->where('year(verification_date_created)', Date('Y'));
		$this->db->where('verification_status IS NOT NULL ');
		$this->db->order_by('verification_code desc');
		$data = $this->db->get('verification', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$verification_code = 'AP'.Date('Ymd').$numb;

			$this->db->where('verification_code', $verification_code);
			$this->db->select('verification_code');
			$check = $this->db->get('verification');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($verification_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$verification_code = 'AP'.Date('Ymd').$numb;

				$this->db->where('verification_code', $verification_code);
				$this->db->select('verification_code');
				$check = $this->db->get('verification');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->verification_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$verification_code = 'AP'.Date('Ymd').$numb;

			$this->db->where('verification_code', $verification_code);
			$this->db->select('verification_code');
			$check = $this->db->get('verification');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($verification_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$verification_code = 'AP'.Date('Ymd').$numb;

				$this->db->where('verification_code', $verification_code);
				$this->db->select('verification_code');
				$check = $this->db->get('verification');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

		return $verification_code;
	}

	function calculate_total_realisasi($idtransaction) {

		$this->db->where('status', 1);
		$this->db->where('idtransaction', $idtransaction);
		$this->db->select('sum(total) as total_realisasi');
		$trxd = $this->db->get('transaction_detail');

		$total_realisasi = azobj($trxd->row(), 'total_realisasi', 0);

		$arr_update = array(
			'total_realisasi' => $total_realisasi,
		);

		az_crud_save($idtransaction, 'transaction', $arr_update);
	}
}
