<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_bidang_urusan extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_bidang_urusan');
        $this->table = 'bidang_urusan';
        $this->controller = 'master_bidang_urusan';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Urusan', 'Nama Bidang Urusan', 'Tahun Anggaran', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$crud->add_aodata('vf_no_rekening_bidang_urusan', 'vf_no_rekening_bidang_urusan');
		$crud->add_aodata('idf_nama_urusan', 'idf_nama_urusan');
		$crud->add_aodata('vf_nama_bidang_urusan', 'vf_nama_bidang_urusan');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('bidang_urusan/vf_bidang_urusan', '', true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('bidang_urusan/v_bidang_urusan', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Bidang Urusan"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('bidang_urusan/vjs_bidang_urusan');
		$azapp->add_js($js);
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Bidang Urusan');
		$data_header['breadcrumb'] = array('master', 'master_bidang_urusan');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$no_rekening_bidang_urusan = $this->input->get('vf_no_rekening_bidang_urusan');
		$nama_bidang_urusan = $this->input->get('vf_nama_bidang_urusan');
		$idurusan_pemerintah = $this->input->get('idf_nama_urusan');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('idbidang_urusan, no_rekening_urusan, no_rekening_bidang_urusan, nama_urusan, nama_bidang_urusan, tahun_anggaran_urusan, bidang_urusan.is_active');
		$crud->set_select_table('idbidang_urusan, no_rekening_bidang_urusan, nama_urusan, nama_bidang_urusan, tahun_anggaran_urusan, is_active');
		$crud->set_filter('no_rekening_bidang_urusan, nama_urusan, nama_bidang_urusan, tahun_anggaran_urusan');
		$crud->set_sorting('no_rekening_bidang_urusan, nama_urusan, nama_bidang_urusan, tahun_anggaran_urusan');
		$crud->set_select_align('center, , , center, center');
		$crud->set_id($this->controller);
        $crud->add_join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$crud->add_where('bidang_urusan.status = "1" ');
		if (strlen($no_rekening_bidang_urusan) > 0) {
			$crud->add_where('bidang_urusan.no_rekening_bidang_urusan = "' . $no_rekening_bidang_urusan . '"');
		}
		if (strlen($nama_bidang_urusan) > 0) {
			$crud->add_where('bidang_urusan.nama_bidang_urusan = "' . $nama_bidang_urusan . '"');
		}
        if (strlen($idurusan_pemerintah) > 0) {
			$crud->add_where('bidang_urusan.idurusan_pemerintah = "' . $idurusan_pemerintah . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('bidang_urusan.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('idbidang_urusan DESC');
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

        if ($key == 'no_rekening_bidang_urusan') {
            $no_rekening_urusan = azarr($data, 'no_rekening_urusan');
			
			return $no_rekening_urusan.'.'.$value;
		}

		if ($key == 'action') {
			$idbidang_urusan = azarr($data, 'idbidang_urusan');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_bidang_urusan" data_id="'.$idbidang_urusan.'"><i class="fa fa-file"></i> Copy</button>';

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

		$this->form_validation->set_rules('no_rekening_bidang_urusan', 'No Rekening', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_bidang_urusan', 'Nama Bidang Urusan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_bidang_urusan' => azarr($data_post, 'no_rekening_bidang_urusan'), 
				'idurusan_pemerintah' => azarr($data_post, 'idurusan_pemerintah'), 
				'nama_bidang_urusan' => azarr($data_post, 'nama_bidang_urusan'), 
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
        $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		az_crud_edit('idbidang_urusan, no_rekening_bidang_urusan, bidang_urusan.idurusan_pemerintah, concat(no_rekening_urusan, " - ", urusan_pemerintah.nama_urusan) as ajax_idurusan_pemerintah, nama_bidang_urusan, tahun_anggaran_urusan, bidang_urusan.is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		// tambah validasi ketika urusan pemerintah sudah digunakan untuk bidang urusan

		az_crud_delete($this->table, $id);
	}
}