<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_akun_belanja extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_akun_belanja');
        $this->table = 'akun_belanja';
        $this->controller = 'master_akun_belanja';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Akun Belanja', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$crud->add_aodata('vf_no_rekening_akunbelanja', 'vf_no_rekening_akunbelanja');
		$crud->add_aodata('vf_nama_akun_belanja', 'vf_nama_akun_belanja');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('akun_belanja/vf_akun_belanja', '', true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('akun_belanja/v_akun_belanja', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Akun Belanja"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('akun_belanja/vjs_akun_belanja');
		$azapp->add_js($js);

		$crud->set_callback_edit('
			check_copy();
        ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Akun Belanja');
		$data_header['breadcrumb'] = array('master', 'master_akun_belanja');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$no_rekening_akunbelanja = $this->input->get('vf_no_rekening_akunbelanja');
		$nama_akun_belanja = $this->input->get('vf_nama_akun_belanja');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('idakun_belanja, no_rekening_akunbelanja, nama_akun_belanja, is_active');
		$crud->set_select_table('idakun_belanja, no_rekening_akunbelanja, nama_akun_belanja, is_active');
		$crud->set_filter('no_rekening_akunbelanja, nama_akun_belanja');
		$crud->set_sorting('no_rekening_akunbelanja, nama_akun_belanja');
		$crud->set_select_align('center, ,center');
		$crud->set_id($this->controller);
		$crud->add_where('status = "1" ');
		if (strlen($no_rekening_akunbelanja) > 0) {
			$crud->add_where('akun_belanja.no_rekening_akunbelanja = "' . $no_rekening_akunbelanja . '"');
		}
		if (strlen($nama_akun_belanja) > 0) {
			$crud->add_where('akun_belanja.nama_akun_belanja = "' . $nama_akun_belanja . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('akun_belanja.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('idakun_belanja DESC');
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
			$idakun_belanja = azarr($data, 'idakun_belanja');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_akun_belanja" data_id="'.$idakun_belanja.'"><i class="fa fa-file"></i> Copy</button>';

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

		$this->form_validation->set_rules('no_rekening_akunbelanja', 'No Rekening ', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_akun_belanja', 'Nama Akun Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_akunbelanja' => azarr($data_post, 'no_rekening_akunbelanja'), 
				'nama_akun_belanja' => azarr($data_post, 'nama_akun_belanja'), 
				'is_active' => azarr($data_post, 'is_active'),
			);

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
		az_crud_edit('idakun_belanja, no_rekening_akunbelanja, nama_akun_belanja, is_active');
	}

	public function delete() {
		$id = $this->input->post('id');
		
		az_crud_delete($this->table, $id);
	}
}