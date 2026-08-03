<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pad_sts extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('pad_sts');
        $this->table = 'pad_sts';
        $this->controller = 'pad_sts';
        $this->load->helper('az_crud');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('idrole');

		$crud->set_column(array('#', 'Tanggal Bukti', 'Nomor Bukti', 'Untuk', 'Jumlah', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		if (aznav('role_view_pad_sts') && strlen($idrole) > 0) {
			$crud->set_btn_add(false);
		}

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		$date1->set_value('01-'.Date('m-Y'));
		$data['date1'] = $date1->render();

		$date2 = $azapp->add_datetime();
		$date2->set_id('date2');
		$date2->set_name('date2');
		$date2->set_format('DD-MM-YYYY');
		$date2->set_value(Date('t-m-Y'));
		$data['date2'] = $date2->render();

		$crud->add_aodata('date1', 'date1');
		$crud->add_aodata('date2', 'date2');
		$crud->add_aodata('vf_proof_number', 'vf_proof_number');
		$crud->add_aodata('iduser_created', 'iduser_created');

		$vf = $this->load->view('pad_sts/vf_pad_sts', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'pad_sts';
		$view = $this->load->view('pad_sts/v_format_pad_sts', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('pad_sts/vjs_pad_sts');
		$azapp->add_js($js);

		$data_header['title'] = 'STS';
		$data_header['breadcrumb'] = array('pad_sts');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$proof_number = $this->input->get('vf_proof_number');
		$iduser_created = $this->input->get('iduser_created');

        $crud->set_select('pad_sts.idpad_sts, date_format(pad_sts.proof_date, "%d-%m-%Y %H:%i:%s") as txt_proof_date, pad_sts.proof_number, pad_sts.proof_for, pad_sts.total_sts, pad_sts.iduser_created, user.name as user_name');        
        $crud->set_select_table('idpad_sts, txt_proof_date, proof_number, proof_for, total_sts, user_name');
        $crud->set_sorting('proof_date, proof_number, proof_for, total_sts');
        $crud->set_filter('proof_date, proof_number, proof_for, total_sts');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , right');
 
        $crud->add_join_manual('user', 'user.iduser = pad_sts.iduser_created', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(pad_sts.proof_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(pad_sts.proof_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($proof_number) > 0) {
			$crud->add_where('pad_sts.proof_number = "' . $proof_number . '"');
		}
        if (strlen($iduser_created) > 0) {
			$crud->add_where('pad_sts.iduser_created = "' . $iduser_created . '"');
		}

		$crud->add_where("pad_sts.status = 1");
		$crud->add_where("pad_sts.pad_sts_status != 'DRAFT' ");

		$crud->set_group_by('pad_sts.idpad_sts');
		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('pad_sts.proof_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('idrole');
		$idpad_sts = azarr($data, 'idpad_sts');
		$is_view_only = false;
		
		if ($key == 'total_sts') {
			$total_sts = az_thousand_separator_decimal($value);
			
			return $total_sts;
		}

		if ($key == 'action') {
            $btn = '<button class="btn btn-default btn-xs btn-edit-pad-sts" data_id="'.$idpad_sts.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-pad-sts" data_id="'.$idpad_sts.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

			if (aznav('role_view_pad_sts') && strlen($idrole) > 0) {
				$is_view_only = true;
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-pad-sts" data_id="'.$idpad_sts.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
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
        
		$view = $this->load->view('pad_sts/v_pad_sts', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('pad_sts/v_pad_sts_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add_uraian');
		$modal->set_modal_title('Tambah Rincian');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_uraian'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('pad_sts/vjs_pad_sts_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'STS';
		$data_header['breadcrumb'] = array('pad_sts');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function select_kode_rekening() {
		$idpad_kode_rekening = $this->input->post('idpad_kode_rekening');

		$this->db->where('idpad_kode_rekening', $idpad_kode_rekening);
		$check = $this->db->get('pad_kode_rekening');

		if ($check->num_rows() > 0) {
			$data = array(
				'idpad_kode_rekening' => $check->row()->idpad_kode_rekening,
				'sub_kegiatan' => $check->row()->sub_kegiatan,
				'kode_rekening' => $check->row()->kode_rekening,
				'uraian' => $check->row()->uraian,
			);

			echo json_encode($data);
		}
	}

    function add_sts() {
		$err_code = 0;
		$err_message = '';

		$this->db->trans_begin();

	 	$idpad_sts = $this->input->post('idpad_sts');
	 	$idpad_sts_detail = $this->input->post('idpad_sts_detail');

		$idpad_kode_rekening = $this->input->post('hd_idpad_kode_rekening');
        $direct_receipt = az_crud_number($this->input->post('direct_receipt'));
        $down_payment = az_crud_number($this->input->post('down_payment'));
        $debt = az_crud_number($this->input->post('debt'));
        $description_detail = $this->input->post('description_detail');

		$total_detail = floatval($direct_receipt) + floatval($down_payment) + floatval($debt);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('hd_idpad_kode_rekening', 'Kode Rekening', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		
		if ($err_code == 0) {
			
			if (strlen($idpad_sts) == 0) {
				$arr_sts = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'pad_sts_status' => 'DRAFT',
					'proof_date' => Date('Y-m-d H:i:s'),
				);

				$save_plan = az_crud_save($idpad_sts, 'pad_sts', $arr_sts);
				$idpad_sts = azarr($save_plan, 'insert_id');
				$err_code = azarr($save_plan, 'err_code');
				$err_message = azarr($save_plan, 'err_message');
			}

			if ($err_code == 0) {
				//sts detail
				$arr_pad_sts_detail = array(
					'idpad_sts' => $idpad_sts,
					'idpad_kode_rekening' => $idpad_kode_rekening,
					'direct_receipt' => $direct_receipt,
					'down_payment' => $down_payment,
					'debt' => $debt,
					'total_detail' => $total_detail,
					'description_detail' => $description_detail,
				);
				// echo "<pre>"; print_r($arr_pad_sts_detail); die;
				
				$td = az_crud_save($idpad_sts_detail, 'pad_sts_detail', $arr_pad_sts_detail);
				$idpad_sts_detail = azarr($td, 'insert_id');

				// hitung total transaksi
				$this->calculate_total($idpad_sts);
			}
		}

		if ($this->db->trans_status() === FALSE || $err_code > 0) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpad_sts' => $idpad_sts,
			'idpad_sts_detail' => $idpad_sts_detail,
		);
		echo json_encode($return);
	}

	function edit_detail() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";

		$this->db->where('pad_sts_detail.idpad_sts_detail', $id);
		$this->db->where('pad_sts.status', 1);
		$this->db->where('pad_sts_detail.status', 1);
		$this->db->where('pad_kode_rekening.status', 1);

		$this->db->join('pad_sts_detail', 'pad_sts_detail.idpad_sts = pad_sts.idpad_sts');
		$this->db->join('pad_kode_rekening', 'pad_kode_rekening.idpad_kode_rekening = pad_sts_detail.idpad_kode_rekening');

		$this->db->select('pad_sts.idpad_sts, pad_sts_detail.idpad_sts_detail, pad_kode_rekening.idpad_kode_rekening, pad_kode_rekening.sub_kegiatan, pad_kode_rekening.kode_rekening, pad_kode_rekening.uraian, pad_sts_detail.direct_receipt, pad_sts_detail.down_payment, pad_sts_detail.debt, pad_sts_detail.total_detail, pad_sts_detail.description_detail, pad_sts.pad_sts_status');
		$sts = $this->db->get('pad_sts');
        // echo "<pre>"; print_r($this->db->last_query()); die;

		$sts = $sts->result_array();

		$ret = array(
			'data' => azarr($sts, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function delete_detail() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = "";
		$is_delete = true;
		$message = '';
		$idpad_sts = '';

		$this->db->where('idpad_sts_detail',$id);
		$this->db->join('pad_sts', 'pad_sts.idpad_sts = pad_sts_detail.idpad_sts');
		$pad_sts = $this->db->get('pad_sts_detail');

		if ($pad_sts->num_rows() == 0) {
			$err_code++;
			$err_message = "Invalid ID";

			$is_delete = false;
		}

		if ($is_delete) {
			$idpad_sts = $pad_sts->row()->idpad_sts;

			// hapus detail sts
			$delete = az_crud_delete('pad_sts_detail', $id, true);

			// hitung total transaksi
			$this->calculate_total($idpad_sts);
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada paket belanja/detail transaksi di realisasi anggaran ini?
		if ($err_code == 0) {
			$this->db->where('idpad_sts', $idpad_sts);
			$this->db->where('status', 1);
			$pad_sts_detail = $this->db->get('pad_sts_detail');

			if ($pad_sts_detail->num_rows() == 0) {
				$arr_update = array(
					'pad_sts_status' => 'DRAFT',
				);
				az_crud_save($idpad_sts, 'pad_sts', $arr_update);

				$message = 'Rincian berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan rincian baru, harap klik simpan, agar datanya tidak hilang.</span>';
			}
		}	

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
			'idpad_sts' => $idpad_sts,
		);

		echo json_encode($return);
	}

	function save_sts() {
		$err_code = 0;
		$err_message = '';

		$this->db->trans_begin();
		
		$idpad_sts = $this->input->post("hd_idpad_sts");
		$proof_date = az_crud_date($this->input->post("proof_date"));
		$iduser_created = $this->input->post("iduser_created");
		$proof_number = $this->input->post("proof_number");
		$idproof_in = $this->input->post("idproof_in");
		$proof_for = $this->input->post("proof_for");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('proof_number', 'Nomor Bukti', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('proof_date', 'Tanggal Bukti', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idproof_in', 'Masuk Ke', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('proof_for', 'Untuk', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			if (strlen($idpad_sts) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'pad_sts_status' => "OK",
	    		'proof_number' => $proof_number,
	    		'iduser_created' => $iduser_created,
	    		'proof_date' => $proof_date,
	    		'idproof_in' => $idproof_in,
	    		'proof_for' => $proof_for,
	    	);

	    	$save_sts = az_crud_save($idpad_sts, 'pad_sts', $arr_data);
			$err_code = azarr($save_sts, 'err_code');
			$err_message = azarr($save_sts, 'err_message');


			// hitung total transaksi
			$this->calculate_total($idpad_sts);
		}

		if ($this->db->trans_status() === FALSE || $err_code > 0) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function edit($id) {

		$this->db->where('idpad_sts', $id);
		$check = $this->db->get('pad_sts');
		if ($check->num_rows() == 0) {
			redirect(app_url().'pad_sts');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->pad_sts_status;

			if ($status == "DRAFT") {
				redirect(app_url().'pad_sts');
			}
		}
		$this->add($id);
	}

	function get_data() {
		$id = $this->input->post('id');

		$this->db->where('pad_sts.idpad_sts', $id);
		$this->db->join('user', 'user.iduser = pad_sts.iduser_created');
		$this->db->join('pad_rekening', 'pad_rekening.idpad_rekening = pad_sts.idproof_in');
		$this->db->select('date_format(proof_date, "%d-%m-%Y %H:%i:%s") as txt_proof_date, proof_number, idproof_in, pad_rekening.uraian, proof_for, user.name as user_created, pad_sts.iduser_created,pad_sts.idpad_sts');
		$this->db->order_by('proof_date', 'desc');
		$pad_sts = $this->db->get('pad_sts')->result_array();

		$this->db->where('idpad_sts', $id);
		$pad_sts_detail = $this->db->get('pad_sts_detail')->result_array();

		$return = array(
			'pad_sts' => azarr($pad_sts, 0),
			'pad_sts_detail' => $pad_sts_detail
		);
		echo json_encode($return);
	}

	function delete_sts() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('pad_sts.idpad_sts', $id);
		$this->db->where('pad_sts.status', 1);
		$this->db->where('pad_sts_detail.status', 1);
		$this->db->join('pad_sts_detail', 'pad_sts_detail.idpad_sts = pad_sts.idpad_sts');
		$pad_sts = $this->db->get('pad_sts');

		if ($pad_sts->num_rows() > 0) {
			$delete = az_crud_delete($this->table, $id);
		}
		else {
			$ret = array(
				'err_code' => 1,
				'err_message' => 'Data tidak ditemukan'
			);

			echo json_encode($ret);
		}
	}

	function get_list_detail() {
		$idpad_sts = $this->input->post("idpad_sts");

		$this->db->where('pad_sts_detail.idpad_sts = "'.$idpad_sts.'" ');
		$this->db->where('pad_sts_detail.status', 1);
		$this->db->where('pad_sts.status', 1);

		$this->db->join('pad_sts_detail', 'pad_sts_detail.idpad_sts = pad_sts.idpad_sts');	
		$this->db->join('pad_kode_rekening', 'pad_kode_rekening.idpad_kode_rekening = pad_sts_detail.idpad_kode_rekening');

		$this->db->select('pad_sts_detail.idpad_sts_detail, pad_kode_rekening.sub_kegiatan, pad_kode_rekening.kode_rekening, pad_kode_rekening.uraian, pad_sts_detail.direct_receipt, pad_sts_detail.down_payment, pad_sts_detail.debt, pad_sts_detail.total_detail, pad_sts_detail.description_detail, pad_sts.pad_sts_status');
		$purchase_plan = $this->db->get('pad_sts');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$data = array(
			'detail' => $purchase_plan->result_array(),
		);

		$view = $this->load->view('pad_sts/v_pad_sts_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function calculate_total($idpad_sts) {

		$this->db->where('status', 1);
		$this->db->where('idpad_sts', $idpad_sts);
		$this->db->select('sum(total_detail) as total_detail');
		$sts_detail = $this->db->get('pad_sts_detail');

		$total_sts = azobj($sts_detail->row(), 'total_detail', 0);

		$arr_update = array(
			'total_sts' => $total_sts,
		);

		az_crud_save($idpad_sts, 'pad_sts', $arr_update);
	}
}
