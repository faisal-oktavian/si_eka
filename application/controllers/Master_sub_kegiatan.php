<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_sub_kegiatan extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_sub_kegiatan');
        $this->table = 'sub_kegiatan';
        $this->controller = 'master_sub_kegiatan';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Urusan', 'Nama Bidang Urusan', 'Nama Program', 'Nama Kegiatan', 'Nama Sub Kegiatan', 'Tahun Anggaran', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$tahun_anggaran = $azapp->add_datetime();
		$tahun_anggaran->set_id('vf_tahun_anggaran');
		$tahun_anggaran->set_name('vf_tahun_anggaran');
		$tahun_anggaran->set_value(Date('Y'));
		$tahun_anggaran->set_format('YYYY');
		$data['tahun_anggaran'] = $tahun_anggaran->render();

		$crud->add_aodata('vf_tahun_anggaran', 'vf_tahun_anggaran');
		$crud->add_aodata('vf_no_rekening_subkegiatan', 'vf_no_rekening_subkegiatan');
		$crud->add_aodata('idf_nama_urusan', 'idf_nama_urusan');
		$crud->add_aodata('idf_nama_bidang_urusan', 'idf_nama_bidang_urusan');
		$crud->add_aodata('idf_nama_program', 'idf_nama_program');
		$crud->add_aodata('idf_nama_kegiatan', 'idf_nama_kegiatan');
		$crud->add_aodata('vf_nama_subkegiatan', 'vf_nama_subkegiatan');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('sub_kegiatan/vf_sub_kegiatan', $data, true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('sub_kegiatan/v_sub_kegiatan', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Sub Kegiatan"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('sub_kegiatan/vjs_sub_kegiatan');
		$azapp->add_js($js);

		$crud->set_callback_edit('
			check_copy();
        ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Sub Kegiatan');
		$data_header['breadcrumb'] = array('master', 'master_sub_kegiatan');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');
		$vf_no_rekening_subkegiatan = $this->input->get('vf_no_rekening_subkegiatan');
		$idf_nama_urusan = $this->input->get('idf_nama_urusan');
		$idf_nama_bidang_urusan = $this->input->get('idf_nama_bidang_urusan');
		$idf_nama_program = $this->input->get('idf_nama_program');
		$idf_nama_kegiatan = $this->input->get('idf_nama_kegiatan');
		$vf_nama_subkegiatan = $this->input->get('vf_nama_subkegiatan');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('idsub_kegiatan, no_rekening_urusan, no_rekening_bidang_urusan, no_rekening_program, no_rekening_kegiatan, no_rekening_subkegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, nama_subkegiatan, tahun_anggaran_urusan, kegiatan.is_active');
		$crud->set_select_table('idsub_kegiatan, no_rekening_subkegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, nama_subkegiatan, tahun_anggaran_urusan, is_active');
		$crud->set_filter('no_rekening_subkegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, nama_subkegiatan, tahun_anggaran_urusan');
		$crud->set_sorting('no_rekening_subkegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, nama_subkegiatan, tahun_anggaran_urusan');
		$crud->set_select_align('center, , , , , , center, center, center');
		$crud->set_id($this->controller);
        $crud->add_join_manual('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $crud->add_join_manual('program', 'program.idprogram = kegiatan.idprogram');
        $crud->add_join_manual('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $crud->add_join_manual('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		
        $crud->add_where('sub_kegiatan.status = "1" ');
		if (strlen($tahun_anggaran) > 0) {
			$crud->add_where('urusan_pemerintah.tahun_anggaran_urusan = "' . $tahun_anggaran . '"');
		}
		if (strlen($vf_no_rekening_subkegiatan) > 0) {
			$crud->add_where('sub_kegiatan.no_rekening_subkegiatan = "' . $vf_no_rekening_subkegiatan . '"');
		}
		if (strlen($idf_nama_urusan) > 0) {
			$crud->add_where('urusan_pemerintah.idurusan_pemerintah = "' . $idf_nama_urusan . '"');
		}
		if (strlen($idf_nama_bidang_urusan) > 0) {
			$crud->add_where('bidang_urusan.idbidang_urusan = "' . $idf_nama_bidang_urusan . '"');
		}
        if (strlen($idf_nama_program) > 0) {
			$crud->add_where('program.idprogram = "' . $idf_nama_program . '"');
		}
		if (strlen($idf_nama_kegiatan) > 0) {
			$crud->add_where('kegiatan.idkegiatan = "' . $idf_nama_kegiatan . '"');
		}
		if (strlen($vf_nama_subkegiatan) > 0) {
			$crud->add_where('sub_kegiatan.nama_subkegiatan = "' . $vf_nama_subkegiatan . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('sub_kegiatan.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('sub_kegiatan.idsub_kegiatan DESC');
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

        if ($key == 'no_rekening_subkegiatan') {
            $no_rekening_urusan = azarr($data, 'no_rekening_urusan');
            $no_rekening_bidang_urusan = azarr($data, 'no_rekening_bidang_urusan');
            $no_rekening_program = azarr($data, 'no_rekening_program');
            $no_rekening_kegiatan = azarr($data, 'no_rekening_kegiatan');
			
			return $no_rekening_urusan.'.'.$no_rekening_bidang_urusan.'.'.$no_rekening_program.'.'.$no_rekening_kegiatan.'.'.$value;
		}

		if ($key == 'action') {
			$idsub_kegiatan = azarr($data, 'idsub_kegiatan');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_sub_kegiatan" data_id="'.$idsub_kegiatan.'"><i class="fa fa-file"></i> Copy</button>';

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

		$this->form_validation->set_rules('no_rekening_subkegiatan', 'No Rekening', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idprogram', 'Nama Program', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idkegiatan', 'Nama Kegiatan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_subkegiatan', 'Nama Sub Kegiatan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_subkegiatan' => azarr($data_post, 'no_rekening_subkegiatan'), 
				'idkegiatan' => azarr($data_post, 'idkegiatan'), 
				'nama_subkegiatan' => azarr($data_post, 'nama_subkegiatan'), 
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
        $this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		az_crud_edit('sub_kegiatan.idsub_kegiatan, sub_kegiatan.no_rekening_subkegiatan, kegiatan.idprogram, concat(program.no_rekening_program, " - ", program.nama_program) as ajax_idprogram, sub_kegiatan.idkegiatan, concat(kegiatan.no_rekening_kegiatan, " - ", kegiatan.nama_kegiatan) as ajax_idkegiatan, sub_kegiatan.nama_subkegiatan, sub_kegiatan.is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		// tambah validasi ketika Sub Kegiatan sudah digunakan untuk Paket Belanja
		$this->db->where('status', 1);
		$this->db->where('idsub_kegiatan', $id);
		$pb = $this->db->get('paket_belanja');

		if ($pb->num_rows() > 0) {
			$data_return = array(
                'err_code' => 1,
                'err_message' => "Data tidak bisa dihapus karena sudah digunakan pada menu Paket Belanja."
            );

			echo json_encode($data_return);
		}
		else {
			az_crud_delete($this->table, $id);
		}
	}
}