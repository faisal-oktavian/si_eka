<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_pad_kode_rekening extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_pad_kode_rekening');
        $this->table = 'pad_kode_rekening';
        $this->controller = 'master_pad_kode_rekening';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Sub Kegiatan', 'Kode Rekening', 'Uraian', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$crud->add_aodata('vf_kode_rekening', 'vf_kode_rekening');
		$crud->add_aodata('vf_uraian', 'vf_uraian');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('pad_kode_rekening/vf_kode_rekening', '', true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('pad_kode_rekening/v_kode_rekening', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Kode Rekening"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('pad_kode_rekening/vjs_kode_rekening');
		$azapp->add_js($js);

		$crud->set_callback_edit('
			check_copy();
        ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('PAD Kode Rekening');
		$data_header['breadcrumb'] = array('master_pad_online', 'master_pad_kode_rekening');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$kode_rekening = $this->input->get('vf_kode_rekening');
		$uraian = $this->input->get('vf_uraian');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select_table('idpad_kode_rekening, sub_kegiatan, kode_rekening, uraian, is_active');
		$crud->set_filter('sub_kegiatan, kode_rekening, uraian');
		$crud->set_sorting('sub_kegiatan, kode_rekening, uraian');
		$crud->set_select_align(' , , , center');
		$crud->set_id($this->controller);
		$crud->add_where('status = "1" ');
		if (strlen($kode_rekening) > 0) {
			$crud->add_where('kode_rekening = "' . $kode_rekening . '"');
		}
		if (strlen($uraian) > 0) {
			$crud->add_where('uraian = "' . $uraian . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('idpad_kode_rekening DESC');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {

		if ($key == 'is_active') {
			$lbl = 'danger';
			$tlbl = 'TIDAK AKTIF';
			if ($value) {
				$lbl = 'success';
				$tlbl = 'AKTIF';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
			$idpad_kode_rekening = azarr($data, 'idpad_kode_rekening');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_pad_kode_rekening" data_id="'.$idpad_kode_rekening.'"><i class="fa fa-file"></i> Copy</button>';

			return $btn;
		}
		return $value;
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('sub_kegiatan', 'Sub Kegiatan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('kode_rekening', 'Kode Rekening', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('uraian', 'Uraian', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'sub_kegiatan' => azarr($data_post, 'sub_kegiatan'),
				'kode_rekening' => azarr($data_post, 'kode_rekening'),
				'uraian' => azarr($data_post, 'uraian'),
				'is_active' => azarr($data_post, 'is_active'),
			);
            // echo "<pre>"; print_r($data_save); die();

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_crud_edit('idpad_kode_rekening, sub_kegiatan, kode_rekening, uraian, is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		az_crud_delete($this->table, $id);
	}
}