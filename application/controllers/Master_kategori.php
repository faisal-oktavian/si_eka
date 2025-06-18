<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_kategori extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_kategori');
        $this->table = 'kategori';
        $this->controller = 'master_kategori';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Akun Belanja', 'Nama Kategori', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$crud->add_aodata('vf_no_rekening_kategori', 'vf_no_rekening_kategori');
		$crud->add_aodata('idf_nama_akunbelanja', 'idf_nama_akunbelanja');
		$crud->add_aodata('vf_nama_kategori', 'vf_nama_kategori');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('kategori/vf_kategori', '', true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('kategori/v_kategori', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Kategori"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('kategori/vjs_kategori');
		$azapp->add_js($js);
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Kategori');
		$data_header['breadcrumb'] = array('master', 'master_kategori');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$no_rekening_kategori = $this->input->get('vf_no_rekening_kategori');
		$idakun_belanja = $this->input->get('idf_nama_akunbelanja');
		$nama_kategori = $this->input->get('vf_nama_kategori');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('idkategori, no_rekening_akunbelanja, no_rekening_kategori, nama_akun_belanja, nama_kategori, kategori.is_active');
		$crud->set_select_table('idkategori, no_rekening_kategori, nama_akun_belanja, nama_kategori, is_active');
		$crud->set_filter('no_rekening_kategori, nama_akun_belanja, nama_kategori');
		$crud->set_sorting('no_rekening_kategori, nama_akun_belanja, nama_kategori');
		$crud->set_select_align('center');
		$crud->set_id($this->controller);
        $crud->add_join('akun_belanja', 'akun_belanja.idakun_belanja = kategori.idakun_belanja');
		$crud->add_where('kategori.status = "1" ');
		if (strlen($no_rekening_kategori) > 0) {
			$crud->add_where('kategori.no_rekening_kategori = "' . $no_rekening_kategori . '"');
		}
		if (strlen($idakun_belanja) > 0) {
			$crud->add_where('akun_belanja.idakun_belanja = "' . $idakun_belanja . '"');
		}
		if (strlen($nama_kategori) > 0) {
			$crud->add_where('kategori.nama_kategori = "' . $nama_kategori . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('kategori.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('idkategori DESC');
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

        if ($key == 'no_rekening_kategori') {
            $no_rekening_akunbelanja = azarr($data, 'no_rekening_akunbelanja');
			
			return $no_rekening_akunbelanja.'.'.$value;
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

		$this->form_validation->set_rules('no_rekening_kategori', 'No Rekening', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idakun_belanja', 'Nama Akun Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_kategori' => azarr($data_post, 'no_rekening_kategori'), 
				'idakun_belanja' => azarr($data_post, 'idakun_belanja'), 
				'nama_kategori' => azarr($data_post, 'nama_kategori'), 
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
        $this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = kategori.idakun_belanja');
		az_crud_edit('idkategori, no_rekening_kategori, kategori.idakun_belanja, concat(no_rekening_akunbelanja, " - ", akun_belanja.nama_akun_belanja) as ajax_idakun_belanja, nama_kategori, kategori.is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		// tambah validasi ketika urusan pemerintah sudah digunakan untuk bidang urusan

		az_crud_delete($this->table, $id);
	}
}