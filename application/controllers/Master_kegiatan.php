<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_kegiatan extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_kegiatan');
        $this->table = 'kegiatan';
        $this->controller = 'master_kegiatan';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Urusan', 'Nama Bidang Urusan', 'Nama Program', 'Nama Kegiatan', 'Tahun Anggaran', 'Status', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$tahun_anggaran = $azapp->add_datetime();
		$tahun_anggaran->set_id('vf_tahun_anggaran');
		$tahun_anggaran->set_name('vf_tahun_anggaran');
		$tahun_anggaran->set_value(Date('Y'));
		$tahun_anggaran->set_format('YYYY');
		$data['tahun_anggaran'] = $tahun_anggaran->render();

		$crud->add_aodata('vf_tahun_anggaran', 'vf_tahun_anggaran');
		$crud->add_aodata('vf_no_rekening_kegiatan', 'vf_no_rekening_kegiatan');
		$crud->add_aodata('idf_nama_urusan', 'idf_nama_urusan');
		$crud->add_aodata('idf_nama_bidang_urusan', 'idf_nama_bidang_urusan');
		$crud->add_aodata('idf_nama_program', 'idf_nama_program');
		$crud->add_aodata('vf_nama_kegiatan', 'vf_nama_kegiatan');
		$crud->add_aodata('vf_is_active', 'vf_is_active');

		$filter = $this->load->view('kegiatan/vf_kegiatan', $data, true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('kegiatan/v_kegiatan', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Kegiatan"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('kegiatan/vjs_kegiatan');
		$azapp->add_js($js);

		$crud->set_callback_edit('
			check_copy();
        ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Kegiatan');
		$data_header['breadcrumb'] = array('master', 'master_kegiatan');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');
		$vf_no_rekening_kegiatan = $this->input->get('vf_no_rekening_kegiatan');
		$idf_nama_urusan = $this->input->get('idf_nama_urusan');
		$idf_nama_bidang_urusan = $this->input->get('idf_nama_bidang_urusan');
		$idf_nama_program = $this->input->get('idf_nama_program');
		$vf_nama_kegiatan = $this->input->get('vf_nama_kegiatan');
		$is_active = $this->input->get('vf_is_active');

		$crud->set_select('idkegiatan, no_rekening_urusan, no_rekening_bidang_urusan, no_rekening_program, no_rekening_kegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, tahun_anggaran_urusan, kegiatan.is_active');
		$crud->set_select_table('idkegiatan, no_rekening_kegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, tahun_anggaran_urusan, is_active');
		$crud->set_filter('no_rekening_kegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, tahun_anggaran_urusan');
		$crud->set_sorting('no_rekening_kegiatan, nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, tahun_anggaran_urusan');
		$crud->set_select_align('center, , , , , center, center');
		$crud->set_id($this->controller);
        $crud->add_join_manual('program', 'program.idprogram = kegiatan.idprogram');
        $crud->add_join_manual('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $crud->add_join_manual('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		
        $crud->add_where('kegiatan.status = "1" ');
		if (strlen($tahun_anggaran) > 0) {
			$crud->add_where('urusan_pemerintah.tahun_anggaran_urusan = "' . $tahun_anggaran . '"');
		}
		if (strlen($vf_no_rekening_kegiatan) > 0) {
			$crud->add_where('kegiatan.no_rekening_kegiatan = "' . $vf_no_rekening_kegiatan . '"');
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
		if (strlen($vf_nama_kegiatan) > 0) {
			$crud->add_where('kegiatan.nama_kegiatan = "' . $vf_nama_kegiatan . '"');
		}
		if (strlen($is_active) > 0) {
			$crud->add_where('kegiatan.is_active = "' . $is_active . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('kegiatan.idkegiatan DESC');
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

        if ($key == 'no_rekening_kegiatan') {
            $no_rekening_urusan = azarr($data, 'no_rekening_urusan');
            $no_rekening_bidang_urusan = azarr($data, 'no_rekening_bidang_urusan');
            $no_rekening_program = azarr($data, 'no_rekening_program');
			
			return $no_rekening_urusan.'.'.$no_rekening_bidang_urusan.'.'.$no_rekening_program.'.'.$value;
		}

		if ($key == 'action') {
			$idkegiatan = azarr($data, 'idkegiatan');
			$btn = $value;

			$btn .= '<button class="btn btn-info btn-xs btn-copy btn-edit-master_kegiatan" data_id="'.$idkegiatan.'"><i class="fa fa-file"></i> Copy</button>';

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

		$this->form_validation->set_rules('no_rekening_kegiatan', 'No Rekening', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idprogram', 'Nama Program', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_kegiatan' => azarr($data_post, 'no_rekening_kegiatan'), 
				'idprogram' => azarr($data_post, 'idprogram'), 
				'nama_kegiatan' => azarr($data_post, 'nama_kegiatan'), 
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
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		az_crud_edit('kegiatan.idkegiatan, kegiatan.no_rekening_kegiatan, kegiatan.idprogram, concat(program.no_rekening_program, " - ", program.nama_program) as ajax_idprogram, kegiatan.nama_kegiatan, kegiatan.is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		// tambah validasi ketika Kegiatan sudah digunakan untuk Sub Kegiatan
		$this->db->where('status', 1);
		$this->db->where('idkegiatan', $id);
		$sk = $this->db->get('sub_kegiatan');

		if ($sk->num_rows() > 0) {
			$data_return = array(
                'err_code' => 1,
                'err_message' => "Data tidak bisa dihapus karena sudah digunakan pada menu Sub Kegiatan."
            );

			echo json_encode($data_return);
		}
		else {
			az_crud_delete($this->table, $id);
		}
	}
}