<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pad_mutasi_kas extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('pad_mutasi_kas');
        $this->table = 'pad_mutasi_kas';
        $this->controller = 'pad_mutasi_kas';
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

		if (aznav('role_view_pad_mutasi_kas') && strlen($idrole) > 0) {
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

		$vf = $this->load->view('pad_mutasi_kas/vf_pad_mutasi_kas', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'pad_mutasi_kas';
		$view = $this->load->view('pad_mutasi_kas/v_format_pad_mutasi_kas', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('pad_mutasi_kas/vjs_pad_mutasi_kas');
		$azapp->add_js($js);

		$data_header['title'] = 'Mutasi Kas';
		$data_header['breadcrumb'] = array('pad_mutasi_kas');
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

        $crud->set_select('pad_mutasi_kas.idpad_mutasi_kas, date_format(pad_mutasi_kas.proof_date, "%d-%m-%Y %H:%i:%s") as txt_proof_date, pad_mutasi_kas.proof_number, pad_mutasi_kas.proof_for, pad_mutasi_kas.total_mutasi_kas, pad_mutasi_kas.iduser_created, user.name as user_name');        
        $crud->set_select_table('idpad_mutasi_kas, txt_proof_date, proof_number, proof_for, total_mutasi_kas, user_name');
        $crud->set_sorting('txt_proof_date, proof_number, proof_for, total_mutasi_kas');
        $crud->set_filter('txt_proof_date, proof_number, proof_for, total_mutasi_kas');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , right');
 
        $crud->add_join_manual('user', 'user.iduser = pad_mutasi_kas.iduser_created', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(pad_mutasi_kas.proof_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(pad_mutasi_kas.proof_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($proof_number) > 0) {
			$crud->add_where('pad_mutasi_kas.proof_number = "' . $proof_number . '"');
		}
        if (strlen($iduser_created) > 0) {
			$crud->add_where('pad_mutasi_kas.iduser_created = "' . $iduser_created . '"');
		}

		$crud->add_where("pad_mutasi_kas.status = 1");
		$crud->add_where("pad_mutasi_kas.pad_mutasi_kas_status != 'DRAFT' ");

		$crud->set_group_by('pad_mutasi_kas.idpad_mutasi_kas');
		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('pad_mutasi_kas.proof_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('idrole');
		$idpad_mutasi_kas = azarr($data, 'idpad_mutasi_kas');
		$is_view_only = false;
		
		if ($key == 'total_mutasi_kas') {
			$total_mutasi_kas = az_thousand_separator_decimal($value);
			
			return $total_mutasi_kas;
		}

		if ($key == 'action') {
            $btn = '<button class="btn btn-default btn-xs btn-edit-pad-mutasi-kas" data_id="'.$idpad_mutasi_kas.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-pad-mutasi-kas" data_id="'.$idpad_mutasi_kas.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

			if (aznav('role_view_pad_mutasi-kas') && strlen($idrole) > 0) {
				$is_view_only = true;
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-pad-mutasi-kas" data_id="'.$idpad_mutasi_kas.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
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
        
		$view = $this->load->view('pad_mutasi_kas/v_pad_mutasi_kas', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('pad_mutasi_kas/vjs_pad_mutasi_kas_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Mutasi Kas';
		$data_header['breadcrumb'] = array('pad_mutasi_kas');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function save_mutasi_kas() {
		$err_code = 0;
		$err_message = '';

		$this->db->trans_begin();
		
		$idpad_mutasi_kas = $this->input->post("hd_idpad_mutasi_kas");
		$proof_number = $this->input->post("proof_number");
		$iduser_created = $this->input->post("iduser_created");
		$proof_date = az_crud_date($this->input->post("proof_date"));
		$proof_type = $this->input->post("proof_type");
		$idproof_from = $this->input->post("idproof_from");
		$idproof_to = $this->input->post("idproof_to");
		$total_mutasi_kas = az_crud_number($this->input->post("total_mutasi_kas"));
		$proof_for = $this->input->post("proof_for");


		$this->load->library('form_validation');
		$this->form_validation->set_rules('proof_number', 'Nomor Bukti', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('proof_date', 'Tanggal Bukti', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idproof_from', 'Dari', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idproof_to', 'Ke', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('proof_for', 'Untuk', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
	    	$arr_data = array(
				'proof_number' => $proof_number,
				'pad_mutasi_kas_status' => 'OK',
				'iduser_created' => $iduser_created,
				'proof_date' => $proof_date,
				'proof_type' => $proof_type,
				'idproof_from' => $idproof_from,
				'idproof_to' => $idproof_to,
				'proof_for' => $proof_for,
				'total_mutasi_kas' => $total_mutasi_kas
	    	);

	    	$save_mutasi_kas = az_crud_save($idpad_mutasi_kas, 'pad_mutasi_kas', $arr_data);
			$err_code = azarr($save_mutasi_kas, 'err_code');
			$err_message = azarr($save_mutasi_kas, 'err_message');
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

		$this->db->where('idpad_mutasi_kas', $id);
		$check = $this->db->get('pad_mutasi_kas');
		if ($check->num_rows() == 0) {
			redirect(app_url().'pad_mutasi_kas');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->pad_mutasi_kas_status;

			if ($status == "DRAFT") {
				redirect(app_url().'pad_mutasi_kas');
			}
		}
		$this->add($id);
	}

	function get_data() {
		$id = $this->input->post('id');

		$this->db->where('pad_mutasi_kas.idpad_mutasi_kas', $id);
		$this->db->join('user', 'user.iduser = pad_mutasi_kas.iduser_created');
		$this->db->join('pad_rekening rek_from', 'rek_from.idpad_rekening = pad_mutasi_kas.idproof_from');
		$this->db->join('pad_rekening rek_to', 'rek_to.idpad_rekening = pad_mutasi_kas.idproof_to');
		$this->db->select('date_format(proof_date, "%d-%m-%Y %H:%i:%s") as txt_proof_date, proof_number, idproof_from, rek_from.uraian as uraian_from, idproof_to, rek_to.uraian as uraian_to, proof_for, user.name as user_created, pad_mutasi_kas.iduser_created, pad_mutasi_kas.idpad_mutasi_kas,pad_mutasi_kas.proof_type, pad_mutasi_kas.total_mutasi_kas');
		$pad_mutasi_kas = $this->db->get('pad_mutasi_kas')->result_array();

		$return = array(
			'pad_mutasi_kas' => azarr($pad_mutasi_kas, 0),
		);
		echo json_encode($return);
	}

	function delete_mutasi() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('pad_mutasi_kas.idpad_mutasi_kas', $id);
		$this->db->where('pad_mutasi_kas.status', 1);
		$pad_mutasi_kas = $this->db->get('pad_mutasi_kas');

		if ($pad_mutasi_kas->num_rows() > 0) {
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
}
