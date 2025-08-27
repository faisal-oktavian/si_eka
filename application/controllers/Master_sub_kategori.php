<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_sub_kategori extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_sub_kategori');
        $this->table = 'sub_kategori';
        $this->controller = 'master_sub_kategori';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Kode Rekening', 'Nama Sub Kategori', 'Sumber Dana', 'Wajib Isi Jenis Kelamin', 'Wajib Isi Keterangan', 'Wajib Isi Ruang', 'Wajib Isi Nama Diklat', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$crud->add_aodata('vf_nama_sub_kategori', 'vf_nama_sub_kategori');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('sub_kategori/vf_sub_kategori', '', true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('sub_kategori/v_sub_kategori', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Sub Kategori"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('sub_kategori/vjs_sub_kategori');
		$azapp->add_js($js);

		$crud->set_callback_edit('
			check_copy();
        ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Sub Kategori');
		$data_header['breadcrumb'] = array('master', 'master_sub_kategori');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$nama_sub_kategori = $this->input->get('vf_nama_sub_kategori');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('sub_kategori.idsub_kategori, kode_rekening, sub_kategori.nama_sub_kategori, sumber_dana.nama_sumber_dana, sub_kategori.is_gender, sub_kategori.is_description, sub_kategori.is_room, sub_kategori.is_name_training, sub_kategori.is_active');
		$crud->set_select_table('idsub_kategori, kode_rekening, nama_sub_kategori, nama_sumber_dana, is_gender, is_description, is_room, is_name_training, is_active');
		$crud->set_filter('kode_rekening, nama_sub_kategori, nama_sumber_dana, is_gender, is_description, is_room, is_name_training');
		$crud->set_sorting('kode_rekening, nama_sub_kategori, nama_sumber_dana, is_gender, is_description, is_room, is_name_training');
		$crud->set_select_align(' , , , center, center, center, center, center');
		$crud->set_id($this->controller);
		$crud->add_join_manual('sumber_dana', 'sumber_dana.idsumber_dana = sub_kategori.idsumber_dana', 'left');
		$crud->add_join_manual('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
		$crud->add_where('sub_kategori.status = "1" ');
		if (strlen($nama_sub_kategori) > 0) {
			$crud->add_where('sub_kategori.nama_sub_kategori = "' . $nama_sub_kategori . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('sub_kategori.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('sub_kategori.idsub_kategori DESC');
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

		if ($key == 'is_gender') {
			$lbl = 'info';
			$tlbl = '-';
			if ($value == "1") {
				$lbl = 'default';
				$tlbl = 'Ya';
			}
			else if ($value == "0") {
				$lbl = 'warning';
				$tlbl = 'Tidak';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'is_description') {
			$lbl = 'info';
			$tlbl = '-';
			if ($value == "1") {
				$lbl = 'default';
				$tlbl = 'Ya';
			}
			else if ($value == "0") {
				$lbl = 'warning';
				$tlbl = 'Tidak';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'is_room') {
			$lbl = 'info';
			$tlbl = '-';
			if ($value == "1") {
				$lbl = 'default';
				$tlbl = 'Ya';
			}
			else if ($value == "0") {
				$lbl = 'warning';
				$tlbl = 'Tidak';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'is_name_training') {
			$lbl = 'info';
			$tlbl = '-';
			if ($value == "1") {
				$lbl = 'default';
				$tlbl = 'Ya';
			}
			else if ($value == "0") {
				$lbl = 'warning';
				$tlbl = 'Tidak';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
			$idsub_kategori = azarr($data, 'idsub_kategori');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_sub_kategori" data_id="'.$idsub_kategori.'"><i class="fa fa-file"></i> Copy</button>';

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

		$this->form_validation->set_rules('nama_sub_kategori', 'Nama Sub Kategori', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idsumber_dana', 'Sumber Dana', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_gender', 'Jenis Kelamin', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_description', 'Keterangan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_room', 'Ruang', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_name_training', 'Nama Diklat', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'nama_sub_kategori' => azarr($data_post, 'nama_sub_kategori'),
				'idkode_rekening' => azarr($data_post, 'idkode_rekening'),
				'idsumber_dana' => azarr($data_post, 'idsumber_dana'),
				'is_active' => azarr($data_post, 'is_active'),
				'is_gender' => azarr($data_post, 'is_gender'),
				'is_description' => azarr($data_post, 'is_description'),
				'is_room' => azarr($data_post, 'is_room'),
				'is_name_training' => azarr($data_post, 'is_name_training'),
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
		$this->db->join('sumber_dana', 'sumber_dana.idsumber_dana = sub_kategori.idsumber_dana', 'left');
		$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
		az_crud_edit('sub_kategori.idsub_kategori, sub_kategori.idkode_rekening, kode_rekening as ajax_idkode_rekening, sub_kategori.nama_sub_kategori, sub_kategori.idsumber_dana, sumber_dana.nama_sumber_dana as ajax_idsumber_dana, sub_kategori.is_active, sub_kategori.is_gender, sub_kategori.is_description, sub_kategori.is_room, sub_kategori.is_name_training');
	}

	public function delete() {
		$id = $this->input->post('id');

		az_crud_delete($this->table, $id);
	}
}