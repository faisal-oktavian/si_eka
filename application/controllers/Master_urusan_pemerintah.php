<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_urusan_pemerintah extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_urusan_pemerintah');
        $this->table = 'urusan_pemerintah';
        $this->controller = 'master_urusan_pemerintah';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Urusan', 'Tahun Anggaran', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$tahun_anggaran = $azapp->add_datetime();
		$tahun_anggaran->set_id('vf_tahun_anggaran');
		$tahun_anggaran->set_name('vf_tahun_anggaran');
		$tahun_anggaran->set_value(Date('Y'));
		$tahun_anggaran->set_format('YYYY');
		$data['tahun_anggaran'] = $tahun_anggaran->render();

		$crud->add_aodata('vf_tahun_anggaran', 'vf_tahun_anggaran');
		$crud->add_aodata('vf_no_rekening_urusan', 'vf_no_rekening_urusan');
		$crud->add_aodata('vf_nama_urusan', 'vf_nama_urusan');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('urusan_pemerintah/vf_urusan_pemerintah', $data, true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('urusan_pemerintah/v_urusan_pemerintah', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Urusan Pemerintah"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('urusan_pemerintah/vjs_urusan_pemerintah');
		$azapp->add_js($js);

		$crud->set_callback_edit('
			check_copy();
        ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Urusan Pemerintah');
		$data_header['breadcrumb'] = array('master', 'master_urusan_pemerintah');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');
		$no_rekening_urusan = $this->input->get('vf_no_rekening_urusan');
		$nama_urusan = $this->input->get('vf_nama_urusan');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('idurusan_pemerintah, no_rekening_urusan, nama_urusan, tahun_anggaran_urusan, is_active');
		$crud->set_select_table('idurusan_pemerintah, no_rekening_urusan, nama_urusan, tahun_anggaran_urusan, is_active');
		$crud->set_filter('no_rekening_urusan, nama_urusan, tahun_anggaran_urusan');
		$crud->set_sorting('no_rekening_urusan, nama_urusan, tahun_anggaran_urusan');
		$crud->set_select_align('center, , center, center');
		$crud->set_id($this->controller);
		$crud->add_where('status = "1" ');
		if (strlen($tahun_anggaran) > 0) {
			$crud->add_where('urusan_pemerintah.tahun_anggaran_urusan = "' . $tahun_anggaran . '"');
		}
		if (strlen($no_rekening_urusan) > 0) {
			$crud->add_where('urusan_pemerintah.no_rekening_urusan = "' . $no_rekening_urusan . '"');
		}
		if (strlen($nama_urusan) > 0) {
			$crud->add_where('urusan_pemerintah.nama_urusan = "' . $nama_urusan . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('urusan_pemerintah.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('idurusan_pemerintah DESC');
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
			$idurusan_pemerintah = azarr($data, 'idurusan_pemerintah');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_urusan_pemerintah" data_id="'.$idurusan_pemerintah.'"><i class="fa fa-file"></i> Copy</button>';

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

		$this->form_validation->set_rules('no_rekening_urusan', 'No Rekening ', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_urusan', 'Nama Urusan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('tahun_anggaran_urusan', 'Tahun Anggaran', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_urusan' => azarr($data_post, 'no_rekening_urusan'), 
				'nama_urusan' => azarr($data_post, 'nama_urusan'), 
				'tahun_anggaran_urusan' => az_crud_number(azarr($data_post, 'tahun_anggaran_urusan')),
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
		az_crud_edit('idurusan_pemerintah, no_rekening_urusan, nama_urusan, tahun_anggaran_urusan, is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		// tambah validasi ketika urusan pemerintah sudah digunakan untuk bidang urusan
		$this->db->where('status', 1);
		$this->db->where('idurusan_pemerintah', $id);
		$bu = $this->db->get('bidang_urusan');

		if ($bu->num_rows() > 0) {
			$data_return = array(
                'err_code' => 1,
                'err_message' => "Nama Urusan tidak bisa dihapus karena sudah digunakan pada Bidang Urusan."
            );

			echo json_encode($data_return);
		}
		else {
			az_crud_delete($this->table, $id);
		}

	}
}