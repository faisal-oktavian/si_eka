<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_verification extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('document_verification');
        $this->table = 'budget_realization';
        $this->controller = 'document_verification';
        $this->load->helper('az_crud');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal Realisasi', 'Tanggal Verifikasi Dokumen', 'Nomor Dokumen', 'Detail', 'Status', 'Status Verifikasi', 'Keterangan Verifikasi', 'User Realisasi', 'User Verifikasi', azlang('Action')));
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
		$crud->add_aodata('vf_realization_code', 'vf_realization_code');
		$crud->add_aodata('vf_realization_status', 'vf_realization_status');

		$vf = $this->load->view('document_verification/vf_document_verification', $data, true);
        $crud->set_top_filter($vf);

		$v_modal = $this->load->view('document_verification/v_document_verification_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('document_verification');
		$modal->set_modal_title('Verifikasi Dokumen');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_approval'=>'Simpan'));
		$azapp->add_content($modal->render());

		$v_modal = $this->load->view('document_verification/v_description_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('description');
		$modal->set_modal_title('Keterangan Dokumen');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_description'=>'Simpan'));
		$azapp->add_content($modal->render());

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'document_verification';
		$view = $this->load->view('document_verification/v_format_document_verification', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('document_verification/vjs_document_verification');
		$azapp->add_js($js);

		$data_header['title'] = 'Verifikasi Dokumen';
		$data_header['breadcrumb'] = array('document_verification');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$realization_code = $this->input->get('vf_realization_code');
		$realization_status = $this->input->get('vf_realization_status');

		$crud->set_select('budget_realization.idbudget_realization, verification.idverification, date_format(budget_realization.realization_date, "%d-%m-%Y %H:%i:%s") AS txt_realization_date, date_format(verification.confirm_verification_date, "%d-%m-%Y %H:%i:%s") AS txt_confirm_verification_date, budget_realization.realization_code, "" AS detail, budget_realization.realization_status, verification.status_approve, budget_realization.realization_description, verification.verification_description, user_realization.name AS user_realization, user_verification.name AS user_verification');
		
		$crud->set_select_table('idbudget_realization, txt_realization_date, txt_confirm_verification_date, realization_code, detail, realization_status, status_approve, realization_description, user_realization, user_verification');

        $crud->set_sorting('realization_code, realization_status, status_approve, realization_description, user_realization, user_verification');
        $crud->set_filter('realization_code, realization_status, status_approve, realization_description, user_realization, user_verification');
		$crud->set_id($this->controller);
		$crud->set_select_align('center, center, , , center, center');

        $crud->add_join_manual('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
		$crud->add_join_manual('user user_realization', 'user_realization.iduser = budget_realization.iduser_created');
        $crud->add_join_manual('user user_verification', 'user_verification.iduser = verification.iduser_verification', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(budget_realization.realization_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(budget_realization.realization_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($realization_code) > 0) {
			$crud->add_where('budget_realization.realization_code = "' . $realization_code . '"');
		}
		if (strlen($realization_status) > 0) {
			$crud->add_where('budget_realization.realization_status = "' . $realization_status . '"');
		}

		$crud->add_where("budget_realization.status = '1' ");
		$crud->add_where("budget_realization.realization_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idbudget_realization = azarr($data, 'idbudget_realization');

		if ($key == 'detail') {
            $this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization_detail.status', 1);

			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail, budget_realization_detail.realization_detail_description');
			$budget_realization = $this->db->get('budget_realization');
            // echo "<pre>"; print_r($this->db->last_query());die;

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th width='auto'>Nomor Kontrak</th>";
			// $table .=			"<th width='auto'>Nomor Rencana</th>";
			$table .=			"<th width='180px'>Paket Belanja</th>";
			$table .=			"<th width='200px'>Uraian</th>";
			$table .=			"<th width='60px'>Volume</th>";
			$table .=			"<th width='150px'>Keterangan</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";

            foreach ($budget_realization->result_array() as $key => $value) {
				$table .= "<tr>";
				$table .= 		"<td>".$value['contract_code']."</td>";
				// $table .= 		"<td>".$value['purchase_plan_code']."</td>";
				$table .= 		"<td>".$value['nama_paket_belanja']."</td>";
				$table .= 		"<td>".$value['nama_sub_kategori']."</td>";
				$table .= 		"<td align='center'>".az_thousand_separator($value['volume'])."</td>";
				$table .= 		"<td>".$value['realization_detail_description']."</td>";
				$table .= "</tr>";
            }
			
			$table .=	"</tbody>";
			$table .= "</table>";

			return $table;
		}

		if ($key == 'realization_status') {
			$status = label_status($value);
			
			return $status;
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

		if ($key == "realization_description") {
			$realization_description = azarr($data, 'realization_description');
			$verification_description = azarr($data, 'verification_description');
			$status_approve = azarr($data, 'status_approve');

			if (strlen($status_approve) == 0) {
				$desc = $realization_description;
			}
			else {
				$desc = $verification_description;
			}

			return $desc;
		}		

		if ($key == 'action') {
            $idbudget_realization = azarr($data, 'idbudget_realization');
            $idverification = azarr($data, 'idverification');
            $realization_status = azarr($data, 'realization_status');

			$btn = '';
			
			$the_filter = array(
				'menu' => 'VERIFIKASI DOKUMEN',
				'type' => 'view',
			);
			$arr_validation = validation_status($the_filter);
			// var_dump($arr_validation);

			if (in_array($realization_status, $arr_validation) ) {
				$btn .= '<button class="btn btn-success btn-xs btn-verifikasi-dokumen" data_id="'.$idbudget_realization.'" 
							data_idverif="'.$idverification.'">
							<span class="glyphicon glyphicon-check"></span> Verifikasi
						</button>';

				if ($realization_status == 'MENUNGGU VERIFIKASI') {
					$btn .= '<button class="btn btn-default btn-xs btn-description-dokumen" data_id="'.$idbudget_realization.'" 		
								data_idverif="'.$idverification.'">
								<span class="glyphicon glyphicon-file"></span> Keterangan
							</button>';
				}
            }
			else {
				$btn .= '<button class="btn btn-success btn-xs btn-verifikasi-dokumen" data_id="'.$idbudget_realization.'" 
							data_idverif="'.$idverification.'" disabled>
							<span class="glyphicon glyphicon-check"></span> Verifikasi
						</button>';
			}

			return $btn;
		}

		return $value;
	}

	function save_description() {
		$err_code = 0;
		$err_message = '';

		
		$idbudget_realization = $this->input->post("idbudget_realization");
		$idverification = $this->input->post("idverification");
		$realization_description = $this->input->post("realization_description");

		if (strlen($idbudget_realization) == 0) {
			$err_code++;
			$err_message = 'Invalid ID';
		}

		if ($err_code == 0) {
			$this->db->where('idbudget_realization',$idbudget_realization);
			$realization = $this->db->get('budget_realization');

			if ($realization->num_rows() > 0) {
				$status = $realization->row()->realization_status;

				$the_filter = array(
					'menu' => 'VERIFIKASI DOKUMEN',
					'type' => 'save',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {
			$arr_data = array(
				'realization_description' => $realization_description,
			);

			az_crud_save($idbudget_realization, 'budget_realization', $arr_data);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		echo json_encode($return);
	}

	function approval() {
		$err_code = 0;
		$err_message = '';

		
		$idbudget_realization = $this->input->post("idbudget_realization");
		$idverification = $this->input->post("idverification");
		$status_approve = $this->input->post("status_approve");
		$verification_description = $this->input->post("verification_description");
		$confirm_verification_date = Date('Y-m-d H:i:s');
		$iduser_verification = $this->session->userdata('iduser');
		$realization_status = '';

		if ($status_approve == "DISETUJUI") {
			$realization_status = "SUDAH DIVERIFIKASI";
			$type = 'SUDAH DIVERIFIKASI';
		}
		else if ($status_approve == "DITOLAK") {
			$realization_status = "DITOLAK VERIFIKATOR";
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
			if (strlen($idbudget_realization) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}
		
		if ($err_code == 0) {
			$this->db->where('idbudget_realization',$idbudget_realization);
			$realization = $this->db->get('budget_realization');

			if ($realization->num_rows() > 0) {
				$status = $realization->row()->realization_status;

				$the_filter = array(
					'menu' => 'VERIFIKASI DOKUMEN',
					'type' => 'save',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {

	    	$arr_data = array(
	    		'realization_status' => $realization_status,
				'updated_status' => date('Y-m-d H:i:s'),
	    	);

	    	az_crud_save($idbudget_realization, 'budget_realization', $arr_data);


			// cek apakah datanya sudah ada di tabel verification
			$this->db->where('verification.status', 1);
			$this->db->where('verification.idbudget_realization', $idbudget_realization);
			$this->db->where('verification.verification_status != "DRAFT" ');
			$verif = $this->db->get('verification');

			if ($verif->num_rows() > 0) {
				// jika sudah ada datanya maka update datanya
				$arr_data = array(
					'confirm_verification_date' => $confirm_verification_date,
					'verification_status' => $realization_status,
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
					'idbudget_realization' => $idbudget_realization,
					'verification_date_created' => $realization->row()->realization_date,
					'confirm_verification_date' => $confirm_verification_date,
					'verification_code' => $realization->row()->realization_code,
					'verification_status' => $realization_status,
					'updated_status' => date('Y-m-d H:i:s'),
					'status_approve' => $status_approve,
					'verification_description' => $verification_description,
					'iduser_created' => $realization->row()->iduser_created,
					'iduser_verification' => $iduser_verification
				);

				$save_verification = az_crud_save('', 'verification', $arr_data);
				$idverification = azarr($save_verification, 'insert_id');
			}

			if ($status_approve == "DITOLAK") {
				$arr_filter = array(
					'idbudget_realization' => $idbudget_realization,
					'idverification' => $idverification,
					'confirm_verification_date' => $confirm_verification_date,
					'status_approve' => $status_approve,
					'verification_description' => $verification_description,
					'iduser_verification' => $iduser_verification,
				);
				
				$this->insert_history($arr_filter);
			}

			// update status rencana pengadaan
			$the_filter = array(
				'idbudget_realization' => $idbudget_realization,
				'idverification' => $idverification,
				'status' => $realization_status,
			);
			$update_status = update_status_budget_realization($the_filter);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		echo json_encode($return);
	}

	function insert_history($the_data) {
		$idbudget_realization = azarr($the_data, 'idbudget_realization');
		$idverification = azarr($the_data, 'idverification');
		$confirm_verification_date = azarr($the_data, 'confirm_verification_date');
		$status_approve = azarr($the_data, 'status_approve');
		$verification_description = azarr($the_data, 'verification_description');
		$iduser_verification = azarr($the_data, 'iduser_verification');

		$arr_data = array(
			'idbudget_realization' => $idbudget_realization,
			'idverification' => $idverification,
			'confirm_verification_date' => $confirm_verification_date,
			'status_approve' => $status_approve,
			'verification_description' => $verification_description,
			'iduser_verification' => $iduser_verification,
		);

		az_crud_save('', 'verification_history', $arr_data);
	}
}
