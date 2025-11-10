<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_paket_belanja extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_paket_belanja');
        $this->table = 'paket_belanja';
        $this->controller = 'master_paket_belanja';
		$this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('idrole');

		$crud->set_column(array('#', 'Program', 'Kegiatan', 'Sub Kegiatan', 'Paket Belanja', 'Anggaran', 'PPKom/PPTK', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		if ( ( aznav('role_view_paket_belanja') || aznav('role_select_ppkom_pptk') || aznav('role_specification') ) 
			&& strlen($idrole) > 0 ) {

			$crud->set_btn_add(false);
		}

		$tahun_anggaran = $azapp->add_datetime();
		$tahun_anggaran->set_id('vf_tahun_anggaran');
		$tahun_anggaran->set_name('vf_tahun_anggaran');
		$tahun_anggaran->set_value(Date('Y'));
		$tahun_anggaran->set_format('YYYY');
		$data['tahun_anggaran'] = $tahun_anggaran->render();

		$crud->add_aodata('vf_tahun_anggaran', 'vf_tahun_anggaran');
		$crud->add_aodata('idf_nama_program', 'idf_nama_program');
		$crud->add_aodata('idf_nama_kegiatan', 'idf_nama_kegiatan');
		$crud->add_aodata('idf_nama_subkegiatan', 'idf_nama_subkegiatan');
		$crud->add_aodata('vf_nama_paket_belanja', 'vf_nama_paket_belanja');

		$vf = $this->load->view('paket_belanja/vf_paket_belanja', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'pos';
		$view = $this->load->view('paket_belanja/v_format_paket_belanja', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('paket_belanja/vjs_paket_belanja');
		$azapp->add_js($js);

		$data_header['title'] = 'Paket Belanja';
		$data_header['breadcrumb'] = array('master', 'master_paket_belanja');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');
		$idprogram = $this->input->get('idf_nama_program');
		$idkegiatan = $this->input->get('idf_nama_kegiatan');
		$idsub_kegiatan = $this->input->get('idf_nama_subkegiatan');
		$nama_paket_belanja = $this->input->get('vf_nama_paket_belanja');

		$crud->set_select('idpaket_belanja, nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran, select_ppkom_pptk');
		$crud->set_select_table('idpaket_belanja, nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran, select_ppkom_pptk');
		$crud->set_sorting('nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran, select_ppkom_pptk');
		$crud->set_filter('nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran, select_ppkom_pptk');
		$crud->set_select_align(', , , ,right');
		
		$crud->add_join_manual('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$crud->add_join_manual('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$crud->add_join_manual('program', 'program.idprogram = paket_belanja.idprogram');
		$crud->add_join_manual('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$crud->add_join_manual('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		
		$crud->add_where("paket_belanja.status = '1' ");
		$crud->add_where("paket_belanja.status_paket_belanja = 'OK' ");
		if (strlen($tahun_anggaran) > 0) {
			$crud->add_where('urusan_pemerintah.tahun_anggaran_urusan = "' . $tahun_anggaran . '"');
		}
		if (strlen($idprogram) > 0) {
			$crud->add_where('program.idprogram = "' . $idprogram . '"');
		}
		if (strlen($idkegiatan) > 0) {
			$crud->add_where('kegiatan.idkegiatan = "' . $idkegiatan . '"');
		}
		if (strlen($idsub_kegiatan) > 0) {
			$crud->add_where('sub_kegiatan.idsub_kegiatan = "' . $idsub_kegiatan . '"');
		}
		if (strlen($nama_paket_belanja) > 0) {
			$crud->add_where('paket_belanja.nama_paket_belanja = "' . $nama_paket_belanja . '"');
		}
		
		$crud->set_id($this->controller);
		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('idpaket_belanja desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('idrole');
		$is_view_only = false;

		if ($key == 'nilai_anggaran') {
			return az_thousand_separator($value);
		}

		if ($key == 'action') {
			$idpaket_belanja = azarr($data, 'idpaket_belanja');

			$this->db->where('idpaket_belanja', $idpaket_belanja);
			$pb = $this->db->get('paket_belanja');

			$status = $pb->row()->status_paket_belanja;

			$btn = '<button class="btn btn-default btn-xs btn-edit-master_paket_belanja" data_id="'.$idpaket_belanja.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
			$btn .= '<button class="btn btn-danger btn-xs btn-delete-master-paket-belanja" data_id="'.$idpaket_belanja.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

			if ($status != "OK" ) {
				$is_view_only = true;
            }

			if ( ( aznav('role_view_paket_belanja') || aznav('role_select_ppkom_pptk') || aznav('role_specification') ) 
			&& strlen($idrole) > 0 ) {
				$is_view_only = true;
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-paket_belanja" data_id="'.$idpaket_belanja.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
			}

			return $btn;
		}

		return $value;
	}

	function add($id = '') {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$data['id'] = $id;

		$view = $this->load->view('paket_belanja/v_paket_belanja', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('paket_belanja/v_paket_belanja_modal', $data, true);
		$modal = $azapp->add_modal();
		$modal->set_id('add');
		$modal->set_modal_title('Tambah Akun Belanja');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_akun_belanja'=>'Simpan'));
		$azapp->add_content($modal->render());

		$v_modal2 = $this->load->view('paket_belanja/v_kategori_modal', $data, true);
		$modal2 = $azapp->add_modal();
		$modal2->set_id('add_kategori');
		$modal2->set_modal_title('Tambah Kategori');
		$modal2->set_modal($v_modal2);
		$modal2->set_action_modal(array('save_kategori'=>'Simpan'));
		$azapp->add_content($modal2->render());

		$v_modal3 = $this->load->view('paket_belanja/v_subkategori_modal', $data, true);
		$modal3 = $azapp->add_modal();
		$modal3->set_id('add_subkategori');
		$modal3->set_modal_title('Tambah Sub Kategori');
		$modal3->set_modal($v_modal3);
		$modal3->set_action_modal(array('save_subkategori'=>'Simpan'));
		$azapp->add_content($modal3->render());

		$v_modal2 = $this->load->view('paket_belanja/v_spesifikasi_modal', $data, true);
		$modal2 = $azapp->add_modal();
		$modal2->set_id('add_spesifikasi');
		$modal2->set_modal_title('Tambah Spesifikasi');
		$modal2->set_modal($v_modal2);
		$modal2->set_action_modal(array('save_spesifikasi'=>'Simpan'));
		$azapp->add_content($modal2->render());
		
		$js = az_add_js('paket_belanja/vjs_paket_belanja_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Paket Belanja';
		$data_header['breadcrumb'] = array('master', 'master_paket_belanja');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function edit($id) {

		$this->db->where('idpaket_belanja', $id);
		$check = $this->db->get('paket_belanja');
		if ($check->num_rows() == 0) {
			redirect(app_url().'master_paket_belanja');
		}

		$this->add($id);
	}

	function get_data() {
		$id = $this->input->post('id');

		$this->db->where('paket_belanja.idpaket_belanja', $id);
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->select('kegiatan.idprogram, concat(program.no_rekening_program, " - ", program.nama_program) as nama_program, sub_kegiatan.idkegiatan, concat(kegiatan.no_rekening_kegiatan, " - ", kegiatan.nama_kegiatan) as nama_kegiatan, paket_belanja.idsub_kegiatan, concat(sub_kegiatan.no_rekening_subkegiatan, " - ", sub_kegiatan.nama_subkegiatan) as nama_subkegiatan, paket_belanja.nama_paket_belanja, paket_belanja.nilai_anggaran, paket_belanja.select_ppkom_pptk');
		$paket_belanja = $this->db->get('paket_belanja')->result_array();

		$this->db->where('idpaket_belanja', $id);
		$paket_belanja_detail = $this->db->get('paket_belanja_detail')->result_array();

		$return = array(
			'paket_belanja' => azarr($paket_belanja, 0),
			'paket_belanja_detail' => $paket_belanja_detail
		);
		echo json_encode($return);
	}

	function edit_paket_belanja() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where('idpaket_belanja_detail', $id);
		$this->db->join('akun_belanja', 'paket_belanja_detail.idakun_belanja = akun_belanja.idakun_belanja');
		$this->db->select('paket_belanja_detail.idpaket_belanja_detail, paket_belanja_detail.idakun_belanja, concat(akun_belanja.no_rekening_akunbelanja, " - ", akun_belanja.nama_akun_belanja) as nama_akun_belanja');
		$pb_detail = $this->db->get('paket_belanja_detail')->result_array();
		// echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'data' => azarr($pb_detail, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function edit_paket_belanja_detail() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where('idpaket_belanja_detail_sub', $id);
		$this->db->join('kategori', 'paket_belanja_detail_sub.idkategori = kategori.idkategori', 'left');
		$this->db->join('sub_kategori', 'paket_belanja_detail_sub.idsub_kategori = sub_kategori.idsub_kategori', 'left');
		$this->db->join('kode_rekening', 'sub_kategori.idkode_rekening = kode_rekening.idkode_rekening', 'left');
		$this->db->join('paket_belanja_detail', 'paket_belanja_detail_sub.idpaket_belanja_detail = paket_belanja_detail.idpaket_belanja_detail', 'left');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori as nama_kategori, paket_belanja_detail.idakun_belanja, paket_belanja_detail_sub.idsub_kategori, sub_kategori.nama_sub_kategori as nama_sub_kategori, kode_rekening.kode_rekening, paket_belanja_detail_sub.is_idpaket_belanja_detail_sub, paket_belanja_detail_sub.volume, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah, paket_belanja_detail_sub.idsatuan, satuan.nama_satuan, paket_belanja_detail_sub.rak_volume_januari, paket_belanja_detail_sub.rak_jumlah_januari, paket_belanja_detail_sub.rak_volume_februari, paket_belanja_detail_sub.rak_jumlah_februari, paket_belanja_detail_sub.rak_volume_maret, paket_belanja_detail_sub.rak_jumlah_maret, paket_belanja_detail_sub.rak_volume_april, paket_belanja_detail_sub.rak_jumlah_april, paket_belanja_detail_sub.rak_volume_mei, paket_belanja_detail_sub.rak_jumlah_mei, paket_belanja_detail_sub.rak_volume_juni, paket_belanja_detail_sub.rak_jumlah_juni, paket_belanja_detail_sub.rak_volume_juli, paket_belanja_detail_sub.rak_jumlah_juli, paket_belanja_detail_sub.rak_volume_agustus, paket_belanja_detail_sub.rak_jumlah_agustus, paket_belanja_detail_sub.rak_volume_september, paket_belanja_detail_sub.rak_jumlah_september, paket_belanja_detail_sub.rak_volume_oktober, paket_belanja_detail_sub.rak_jumlah_oktober, paket_belanja_detail_sub.rak_volume_november, paket_belanja_detail_sub.rak_jumlah_november, paket_belanja_detail_sub.rak_volume_desember, paket_belanja_detail_sub.rak_jumlah_desember');
		$pb_detail = $this->db->get('paket_belanja_detail_sub')->result_array();
		// echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'data' => azarr($pb_detail, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function add_akun_belanja() {
		$err_code = 0;
		$err_message = '';
		$idpaket_belanja_detail = '';

	 	$idpaket_belanja = $this->input->post('idpaket_belanja');
	 	$idpb_akun_belanja = $this->input->post('idpb_akun_belanja');

	 	$idakun_belanja = $this->input->post('idakun_belanja');
		$idprogram = $this->input->post('idprogram');
	 	$idkegiatan = $this->input->post('idkegiatan');
	 	$idsub_kegiatan = $this->input->post('idsub_kegiatan');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idakun_belanja', 'Akun Belanja', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			if (strlen($idpaket_belanja) == 0) {
				$arr_pb = array(
					'idprogram' => $idprogram,
					'idkegiatan' => $idkegiatan,
					'idsub_kegiatan' => $idsub_kegiatan,
				);

				$save_paket_belanja = az_crud_save($idpaket_belanja, 'paket_belanja', $arr_pb);
				$idpaket_belanja = azarr($save_paket_belanja, 'insert_id');
			}

			//transaction detail
			$arr_pb_detail = array(
				'idpaket_belanja' => $idpaket_belanja,
				'idakun_belanja' => $idakun_belanja,
			);

			$save_pb_detail = az_crud_save($idpb_akun_belanja, 'paket_belanja_detail', $arr_pb_detail);
			$idpaket_belanja_detail = azarr($save_pb_detail, 'insert_id');
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpaket_belanja' => $idpaket_belanja,
			'idpaket_belanja_detail' => $idpaket_belanja_detail,
		);
		echo json_encode($return);
	}

	function add_kategori() {
		$err_code = 0;
		$err_message = '';

	 	$idpb_detail_sub = $this->input->post('hd_idpb_detail_sub');
	 	$idpaket_belanja = $this->input->post('hd_idpaket_belanja');
	 	$idpaket_belanja_detail = $this->input->post('hd_idpaket_belanja_detail');
	 	$idakun_belanja = $this->input->post('hd_idakun_belanja');
	 	$idkategori = $this->input->post('idkategori');
	 	$is_kategori = $this->input->post('is_kategori');
	 	$is_subkategori = $this->input->post('is_subkategori');

		$this->db->where('idpaket_belanja_detail', $idpaket_belanja_detail);
		$this->db->select('idpaket_belanja');
		$paket_belanja = $this->db->get('paket_belanja_detail');
		$idpaket_belanja = $paket_belanja->row()->idpaket_belanja;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('idkategori', 'Kategori', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			//detail
			$arr_pb_detail_sub = array(
				'idpaket_belanja' => $idpaket_belanja,
				// 'idpaket_belanja_detail' => $idpaket_belanja_detail,
				'idkategori' => $idkategori,
				'idpaket_belanja_detail' => $idpaket_belanja_detail,
				'is_kategori' => $is_kategori,
				'is_subkategori' => $is_subkategori,
			);
			// echo "<pre>"; print_r($arr_pb_detail_sub);die;

			$save_pb_detail_sub = az_crud_save($idpb_detail_sub, 'paket_belanja_detail_sub', $arr_pb_detail_sub);
			$idpb_detail_sub = azarr($save_pb_detail_sub, 'insert_id');
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpaket_belanja' => $idpaket_belanja,
		);
		echo json_encode($return);
	}

	function add_subkategori() {
		$err_code = 0;
		$err_message = '';

	 	$idpb_detail_sub = $this->input->post('hds_idpb_detail_sub');
	 	$idpaket_belanja_detail = $this->input->post('hds_idpaket_belanja_detail');
	 	$idsub_kategori = $this->input->post('idsub_kategori');
	 	$is_kategori = $this->input->post('hds_is_kategori');
	 	$is_subkategori = $this->input->post('hds_is_subkategori');
	 	$is_idpaket_belanja_detail_sub = $this->input->post('hds_idds_parent');
	 	$volume = az_crud_number($this->input->post('volume'));
	 	$idsatuan = $this->input->post('idsatuan');
	 	$harga_satuan = az_crud_number($this->input->post('harga_satuan'));
	 	$jumlah = az_crud_number($this->input->post('jumlah'));

		// Rencana Aksi Kegiatan
		$rak_volume_januari = az_crud_number($this->input->post('rak_volume_januari'));
	 	$rak_jumlah_januari = az_crud_number($this->input->post('rak_jumlah_januari'));
		$rak_volume_februari = az_crud_number($this->input->post('rak_volume_februari'));
	 	$rak_jumlah_februari = az_crud_number($this->input->post('rak_jumlah_februari'));
		$rak_volume_maret = az_crud_number($this->input->post('rak_volume_maret'));
	 	$rak_jumlah_maret = az_crud_number($this->input->post('rak_jumlah_maret'));
		$rak_volume_april = az_crud_number($this->input->post('rak_volume_april'));
	 	$rak_jumlah_april = az_crud_number($this->input->post('rak_jumlah_april'));
		$rak_volume_mei = az_crud_number($this->input->post('rak_volume_mei'));
	 	$rak_jumlah_mei = az_crud_number($this->input->post('rak_jumlah_mei'));
		$rak_volume_juni = az_crud_number($this->input->post('rak_volume_juni'));
	 	$rak_jumlah_juni = az_crud_number($this->input->post('rak_jumlah_juni'));
		$rak_volume_juli = az_crud_number($this->input->post('rak_volume_juli'));
	 	$rak_jumlah_juli = az_crud_number($this->input->post('rak_jumlah_juli'));
		$rak_volume_agustus = az_crud_number($this->input->post('rak_volume_agustus'));
	 	$rak_jumlah_agustus = az_crud_number($this->input->post('rak_jumlah_agustus'));
		$rak_volume_september = az_crud_number($this->input->post('rak_volume_september'));
	 	$rak_jumlah_september = az_crud_number($this->input->post('rak_jumlah_september'));
		$rak_volume_oktober = az_crud_number($this->input->post('rak_volume_oktober'));
	 	$rak_jumlah_oktober = az_crud_number($this->input->post('rak_jumlah_oktober'));
		$rak_volume_november = az_crud_number($this->input->post('rak_volume_november'));
	 	$rak_jumlah_november = az_crud_number($this->input->post('rak_jumlah_november'));
		$rak_volume_desember = az_crud_number($this->input->post('rak_volume_desember'));
	 	$rak_jumlah_desember = az_crud_number($this->input->post('rak_jumlah_desember'));

		if (strlen($rak_volume_januari) == 0) {
			$rak_jumlah_januari = null;
		}
		if (strlen($rak_volume_februari) == 0) {
			$rak_jumlah_februari = null;
		}
		if (strlen($rak_volume_maret) == 0) {
			$rak_jumlah_maret = null;
		}
		if (strlen($rak_volume_april) == 0) {
			$rak_jumlah_april = null;
		}
		if (strlen($rak_volume_mei) == 0) {
			$rak_jumlah_mei = null;
		}
		if (strlen($rak_volume_juni) == 0) {
			$rak_jumlah_juni = null;
		}
		if (strlen($rak_volume_juli) == 0) {
			$rak_jumlah_juli = null;
		}
		if (strlen($rak_volume_agustus) == 0) {
			$rak_jumlah_agustus = null;
		}
		if (strlen($rak_volume_september) == 0) {
			$rak_jumlah_september = null;
		}
		if (strlen($rak_volume_oktober) == 0) {
			$rak_jumlah_oktober = null;
		}
		if (strlen($rak_volume_november) == 0) {
			$rak_jumlah_november = null;
		}
		if (strlen($rak_volume_desember) == 0) {
			$rak_jumlah_desember = null;
		}
		

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idsub_kategori', 'Sub Kategori', 'required');
		$this->form_validation->set_rules('volume', 'Volume', 'required');
		$this->form_validation->set_rules('idsatuan', 'Satuan', 'required');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		// cek apakah inputan volume rak per bulannya melebihi volume
		if ($err_code == 0) {
			$total_rak_volume = floatval($rak_volume_januari) + floatval($rak_volume_februari) + floatval($rak_volume_maret) + floatval($rak_volume_april) + floatval($rak_volume_mei) + floatval($rak_volume_juni) + floatval($rak_volume_juli) + floatval($rak_volume_agustus) + floatval($rak_volume_september) + floatval($rak_volume_oktober) + floatval($rak_volume_november) + floatval($rak_volume_desember);
			
			if ($total_rak_volume > $volume) {
				$err_code++;
				$err_message = 'Total volume rak per bulan tidak boleh melebihi volume';
			}
		}

		// validasi volume yang diinput tidak boleh kurang dari total volume yang sudah masuk di rencana pengadaan
		if ($err_code == 0) {
			if (strlen($idpb_detail_sub) > 0) {
				$this->db->where('idpaket_belanja_detail_sub', $idpb_detail_sub);
				$this->db->where('status', 1);
				$pp_detail = $this->db->get('purchase_plan_detail');
	
				if ($pp_detail->num_rows() > 0) {
					$existing_volume = $pp_detail->row()->volume;
	
					if ($volume < $existing_volume) {
						$err_code++;
						$err_message = 'Volume tidak boleh kurang dari volume yang sudah masuk di rencana pengadaan';
					}
				}
			}
		}

		if ($err_code == 0) {

			// jika variabel ini terisi maka tidak perlu simpan id paket belanja detail
			if (strlen($is_idpaket_belanja_detail_sub) > 0 || strlen($idpaket_belanja_detail) == 0) {
				$idpaket_belanja_detail = null;
			}

			if (strlen($is_idpaket_belanja_detail_sub) == 0 || $is_idpaket_belanja_detail_sub == 0) {
				$is_idpaket_belanja_detail_sub = null;
			}

			//detail
			$arr_pb_detail_sub = array(
				// 'idpaket_belanja' => $idpaket_belanja,
				// 'idpaket_belanja_detail' => $idpaket_belanja_detail,
				'idsub_kategori' 				=> $idsub_kategori,
				'idpaket_belanja_detail' 		=> $idpaket_belanja_detail,
				'is_idpaket_belanja_detail_sub' => $is_idpaket_belanja_detail_sub,
				'is_kategori' 					=> $is_kategori,
				'is_subkategori' 				=> $is_subkategori,
				'volume' 						=> $volume,
				'idsatuan' 						=> $idsatuan,
				'harga_satuan' 					=> $harga_satuan,
				'jumlah' 						=> $jumlah,

				'rak_volume_januari' 	=> ($rak_volume_januari == 0 || $rak_volume_januari === "" ? null : $rak_volume_januari),
				'rak_jumlah_januari' 	=> ($rak_jumlah_januari == 0 || $rak_jumlah_januari === "" ? null : $rak_jumlah_januari),
				'rak_volume_februari' 	=> ($rak_volume_februari == 0 || $rak_volume_februari === "" ? null : $rak_volume_februari),
				'rak_jumlah_februari' 	=> ($rak_jumlah_februari == 0 || $rak_jumlah_februari === "" ? null : $rak_jumlah_februari),
				'rak_volume_maret' 		=> ($rak_volume_maret == 0 || $rak_volume_maret === "" ? null : $rak_volume_maret),
				'rak_jumlah_maret' 		=> ($rak_jumlah_maret == 0 || $rak_jumlah_maret === "" ? null : $rak_jumlah_maret),
				'rak_volume_april' 		=> ($rak_volume_april == 0 || $rak_volume_april === "" ? null : $rak_volume_april),
				'rak_jumlah_april' 		=> ($rak_jumlah_april == 0 || $rak_jumlah_april === "" ? null : $rak_jumlah_april),
				'rak_volume_mei' 		=> ($rak_volume_mei == 0 || $rak_volume_mei === "" ? null : $rak_volume_mei),
				'rak_jumlah_mei' 		=> ($rak_jumlah_mei == 0 || $rak_jumlah_mei === "" ? null : $rak_jumlah_mei),
				'rak_volume_juni' 		=> ($rak_volume_juni == 0 || $rak_volume_juni === "" ? null : $rak_volume_juni),
				'rak_jumlah_juni' 		=> ($rak_jumlah_juni == 0 || $rak_jumlah_juni === "" ? null : $rak_jumlah_juni),
				'rak_volume_juli' 		=> ($rak_volume_juli == 0 || $rak_volume_juli === "" ? null : $rak_volume_juli),
				'rak_jumlah_juli' 		=> ($rak_jumlah_juli == 0 || $rak_jumlah_juli === "" ? null : $rak_jumlah_juli),
				'rak_volume_agustus' 	=> ($rak_volume_agustus == 0 || $rak_volume_agustus === "" ? null : $rak_volume_agustus),
				'rak_jumlah_agustus' 	=> ($rak_jumlah_agustus == 0 || $rak_jumlah_agustus === "" ? null : $rak_jumlah_agustus),
				'rak_volume_september' 	=> ($rak_volume_september == 0 || $rak_volume_september === "" ? null : $rak_volume_september),
				'rak_jumlah_september' 	=> ($rak_jumlah_september == 0 || $rak_jumlah_september === "" ? null : $rak_jumlah_september),
				'rak_volume_oktober' 	=> ($rak_volume_oktober == 0 || $rak_volume_oktober === "" ? null : $rak_volume_oktober),
				'rak_jumlah_oktober' 	=> ($rak_jumlah_oktober == 0 || $rak_jumlah_oktober === "" ? null : $rak_jumlah_oktober),
				'rak_volume_november' 	=> ($rak_volume_november == 0 || $rak_volume_november === "" ? null : $rak_volume_november),
				'rak_jumlah_november' 	=> ($rak_jumlah_november == 0 || $rak_jumlah_november === "" ? null : $rak_jumlah_november),
				'rak_volume_desember' 	=> ($rak_volume_desember == 0 || $rak_volume_desember === "" ? null : $rak_volume_desember),
				'rak_jumlah_desember' 	=> ($rak_jumlah_desember == 0 || $rak_jumlah_desember === "" ? null : $rak_jumlah_desember),
			);
			// echo "<pre>"; print_r($arr_pb_detail_sub);die;

			$save_pb_detail_sub = az_crud_save($idpb_detail_sub, 'paket_belanja_detail_sub', $arr_pb_detail_sub);
			$idpb_detail_sub = azarr($save_pb_detail_sub, 'insert_id');

			// get idpaket_belanja
			$this->db->where('idpaket_belanja_detail_sub', $idpb_detail_sub);
			$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail', 'left');
			$this->db->select('paket_belanja_detail.idpaket_belanja, paket_belanja_detail_sub.is_idpaket_belanja_detail_sub');
			$paket_belanja = $this->db->get('paket_belanja_detail_sub');

			if (strlen($paket_belanja->row()->idpaket_belanja) == 0) {
				$this->db->where('idpaket_belanja_detail_sub', $paket_belanja->row()->is_idpaket_belanja_detail_sub);
				$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
				$this->db->select('paket_belanja_detail.idpaket_belanja, paket_belanja_detail_sub.is_idpaket_belanja_detail_sub');
				$paket_belanja = $this->db->get('paket_belanja_detail_sub');
			}

			$idpaket_belanja = $paket_belanja->row()->idpaket_belanja;

			$arr_update = array(
				'idpaket_belanja' => $idpaket_belanja,
			);

			az_crud_save($idpb_detail_sub, 'paket_belanja_detail_sub', $arr_update);

			$total_jumlah = $this->calculate_nilai_anggaran($idpaket_belanja);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpaket_belanja' => $idpaket_belanja,
			'total_jumlah' => $total_jumlah,
		);
		echo json_encode($return);
	}

	function add_spesifikasi() {
		$idpaket_belanja_detail_sub = $this->input->post('id');
		$spesifikasi = "";
		$link_url = "";

		$this->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$this->db->select('spesifikasi, link_url, idpaket_belanja');
		$detail_sub = $this->db->get('paket_belanja_detail_sub');

		if ($detail_sub->num_rows() > 0) {
			$spesifikasi = $detail_sub->row()->spesifikasi;
			$link_url = $detail_sub->row()->link_url;
		}

		$ret = array(
			'specification' => $spesifikasi,
			'link_url' => $link_url,
		);

		echo json_encode($ret);
	}

	function save_spesifikasi() {
		$err_code = 0;
		$err_message = '';
		$idpaket_belanja = '';

		$idpb_detail_sub = $this->input->post('hd_idpb_detail_sub');
		$specification = $this->input->post("specification");
		$link_url = $this->input->post("link_url");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('specification', 'Spesifikasi', 'required|trim');
		
		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		
		if ($err_code == 0) {
			if (strlen($idpb_detail_sub) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$arr_data = array(
				'spesifikasi' => $specification,
				'link_url' => $link_url,
			);

	    	az_crud_save($idpb_detail_sub, 'paket_belanja_detail_sub', $arr_data);

			$this->db->where('idpaket_belanja_detail_sub', $idpb_detail_sub);
			$this->db->select('spesifikasi, link_url, idpaket_belanja');
			$detail_sub = $this->db->get('paket_belanja_detail_sub');

			$idpaket_belanja = $detail_sub->row()->idpaket_belanja;
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpaket_belanja' => $idpaket_belanja,
		);
		echo json_encode($return);
	}

	function select_kode_rekening() {
		$id = $this->input->post('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->join('kode_rekening', 'sub_kategori.idkode_rekening = kode_rekening.idkode_rekening');
		$this->db->select('kode_rekening.kode_rekening');
		$kode_rek = $this->db->get('sub_kategori');
        // echo "<pre>"; print_r($this->db->last_query());die;

		$kode_rekening = '';
		if ($kode_rek->num_rows() > 0) {
			$kode_rekening = $kode_rek->row()->kode_rekening;
		}

		$ret = array(
			'kode_rekening' => $kode_rekening,
		);

		echo json_encode($ret);
	}

	function calculate_nilai_anggaran($idpaket_belanja) {

		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->order_by('paket_belanja_detail.idpaket_belanja_detail ASC');
		$this->db->select('paket_belanja_detail.idpaket_belanja_detail, akun_belanja.idakun_belanja, akun_belanja.no_rekening_akunbelanja, akun_belanja.nama_akun_belanja');
		$akun_belanja = $this->db->get('paket_belanja_detail');
		// echo "<pre>"; print_r($this->db->last_query());

		$total_jumlah = 0;
		foreach ($akun_belanja->result() as $pbd_key => $pbd_value) {
			$idpaket_belanja_detail = $pbd_value->idpaket_belanja_detail;

			// Kategori / Sub Kategori
			$paket_belanja_detail = $this->query_paket_belanja_detail($idpaket_belanja_detail);
			// echo "<pre>"; print_r($this->db->last_query());

			foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {
				$total_jumlah += $ds_value->jumlah;

				// get sub sub detail
				$paket_belanja_detail_sub = $this->query_paket_belanja_detail_sub($ds_value->idpaket_belanja_detail_sub);
				// echo "<pre>"; print_r($this->db->last_query());die;

				foreach ($paket_belanja_detail_sub->result() as $dss_key => $dss_value) {
					$total_jumlah += $dss_value->jumlah;
				}
			}
		}

		$arr_update = array(
			'nilai_anggaran' => $total_jumlah,
		);

		az_crud_save($idpaket_belanja, 'paket_belanja', $arr_update);

		return $total_jumlah;
	}

	function query_paket_belanja_detail($idpaket_belanja_detail) {
		$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail', $idpaket_belanja_detail);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
		$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, akun_belanja.no_rekening_akunbelanja, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
		$paket_belanja_detail = $this->db->get('paket_belanja_detail_sub');

		return $paket_belanja_detail;
	}

	function query_paket_belanja_detail_sub($idpaket_belanja_detail_sub) {
		$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
		$paket_belanja_detail_sub = $this->db->get('paket_belanja_detail_sub');

		return $paket_belanja_detail_sub;
	}

	function get_list_akun_belanja() {
		$idpaket_belanja = $this->input->post("idpaket_belanja");

		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = paket_belanja_detail.idpaket_belanja');
		$this->db->select('idpaket_belanja_detail, nama_akun_belanja, status_paket_belanja, akun_belanja.no_rekening_akunbelanja, paket_belanja.idpaket_belanja, akun_belanja.idakun_belanja');
		$pb_detail = $this->db->get('paket_belanja_detail');

		$arr_pb_detail = array();
		foreach ($pb_detail->result() as $key => $value) {
			$idpaket_belanja_detail = $value->idpaket_belanja_detail;

			// get sub detail
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail', $idpaket_belanja_detail);
			$this->db->where('paket_belanja_detail_sub.status', 1);
			$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
			$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
			$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
			$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
			$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
			$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, kode_rekening.kode_rekening,
			 paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, akun_belanja.no_rekening_akunbelanja, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah, paket_belanja_detail_sub.spesifikasi');
			$pb_detail_sub = $this->db->get('paket_belanja_detail_sub');
			// echo "<pre>"; print_r($this->db->last_query());die;

			$arr_pd_detail_sub = array();
			foreach ($pb_detail_sub->result() as $ds_key => $ds_value) {

				// get sub sub detail
				$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $ds_value->idpaket_belanja_detail_sub);
				$this->db->where('paket_belanja_detail_sub.status', 1);
				$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
				$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
				$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');
				$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, kode_rekening.kode_rekening, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah, paket_belanja_detail_sub.spesifikasi');
				$pd_detail_sub_sub = $this->db->get('paket_belanja_detail_sub');
				// echo "<pre>"; print_r($this->db->last_query());die;

				$arr_pd_detail_sub_sub = array();
				foreach ($pd_detail_sub_sub->result() as $dss_key => $dss_value) {
					$arr_pd_detail_sub_sub[] = array(
						'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
						'idpaket_belanja_detail' => $dss_value->idpaket_belanja_detail,
						'idsub_kategori' => $dss_value->idsub_kategori,
						'nama_subkategori' => $dss_value->nama_sub_kategori,
						'kode_rekening' => $dss_value->kode_rekening,
						'is_kategori' => $dss_value->is_kategori,
						'is_subkategori' => $dss_value->is_subkategori,
						'volume' => $dss_value->volume,
						'nama_satuan' => $dss_value->nama_satuan,
						'harga_satuan' => $dss_value->harga_satuan,
						'jumlah' => $dss_value->jumlah,
						'spesifikasi' => $dss_value->spesifikasi,
					);
				}


				$arr_pd_detail_sub[] = array(
					'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
					'idpaket_belanja_detail' => $ds_value->idpaket_belanja_detail,
					'idkategori' => $ds_value->idkategori,
					'nama_kategori' => $ds_value->nama_kategori,
					'idsub_kategori' => $ds_value->idsub_kategori,
					'nama_subkategori' => $ds_value->nama_sub_kategori,
					'kode_rekening' => $ds_value->kode_rekening,
					'is_kategori' => $ds_value->is_kategori,
					'is_subkategori' => $ds_value->is_subkategori,
					'no_rekening_akunbelanja' => $ds_value->no_rekening_akunbelanja,
					'volume' => $ds_value->volume,
					'nama_satuan' => $ds_value->nama_satuan,
					'harga_satuan' => $ds_value->harga_satuan,
					'jumlah' => $ds_value->jumlah,
					'spesifikasi' => $ds_value->spesifikasi,
					'arr_pd_detail_sub_sub' => $arr_pd_detail_sub_sub,
				);
			}

			$arr_pb_detail[] = array(
				'idpaket_belanja_detail' => $value->idpaket_belanja_detail,
				'nama_akun_belanja' => $value->no_rekening_akunbelanja." - ".$value->nama_akun_belanja,
				'status_paket_belanja' => $value->status_paket_belanja,
				'idpaket_belanja' => $value->idpaket_belanja,
				'idakun_belanja' => $value->idakun_belanja,
				'arr_pb_detail_sub' => $arr_pd_detail_sub,
			);
		}

		$data['arr_pb_detail'] = $arr_pb_detail;
		// echo "<pre>"; print_r($data); die;

		$view = $this->load->view('paket_belanja/v_paket_belanja_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function save_paket_belanja() {
		$err_code = 0;
		$err_message = '';

		$idpaket_belanja = $this->input->post('hd_idpaket_belanja');
		$idprogram = $this->input->post("idprogram");
		$idkegiatan = $this->input->post("idkegiatan");
		$idsub_kegiatan = $this->input->post("idsub_kegiatan");
		$nama_paket_belanja = $this->input->post("nama_paket_belanja");
		$nilai_anggaran = az_crud_number($this->input->post("nilai_anggaran"));
		$select_ppkom_pptk = $this->input->post('select_ppkom_pptk');

		$this->load->library('form_validation');

		if (aznav('role_select_ppkom_pptk')) {
			$this->form_validation->set_rules('select_ppkom_pptk', 'Opsi PPKom/PPTK', 'required|trim|max_length[200]');
		}
		else {
			$this->form_validation->set_rules('idprogram', 'Nama Program', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('idkegiatan', 'Nomor Kegiatan', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('idsub_kegiatan', 'Nomor Sub Kegiatan', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('nama_paket_belanja', 'Nomor Paket Belanja', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('nilai_anggaran', 'Jumlah Anggaran', 'required|trim|max_length[200]');	
		}
		
		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idpaket_belanja) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			if (aznav('role_select_ppkom_pptk')) {
				$arr_data = array(
					'select_ppkom_pptk' => $select_ppkom_pptk,
				);
			}
			else {
				$arr_data = array(
					'idprogram' => $idprogram,
					'idkegiatan' => $idkegiatan,
					'idsub_kegiatan' => $idsub_kegiatan,
					'nama_paket_belanja' => $nama_paket_belanja,
					'nilai_anggaran' => $nilai_anggaran,
					'status_paket_belanja' => "OK",
				);
			}

	    	az_crud_save($idpaket_belanja, 'paket_belanja', $arr_data);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function delete_paket_belanja() {
		$err_code = 0;
		$err_message = '';

		$id = $this->input->post('id');

		$data_validasi = $this->validasi_realisasi($id); 

		$err_code = $data_validasi['err_code'];
		$err_message = $data_validasi['err_message'];
		
		if ($err_code == 0) {
			// cek apakah ada detailnya?
			$this->db->where('idpaket_belanja', $id);
			$this->db->where('status', 1);
			$ds = $this->db->get('paket_belanja_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;
			
			foreach ($ds->result() as $key => $value) {
				// kategori / sub kategori
				$idpaket_belanja_detail = $value->idpaket_belanja_detail;

				$data_delete = az_crud_delete('paket_belanja_detail', $idpaket_belanja_detail, true);
				$err_code = $data_delete['err_code'];
				$err_message = $data_delete['err_message'];

				// detail
				$this->db->where('idpaket_belanja_detail', $idpaket_belanja_detail);
				$this->db->where('status', 1);
				$dss = $this->db->get('paket_belanja_detail_sub');
				// echo "<pre>"; print_r($this->db->last_query());die;

				foreach ($dss->result() as $dss_key => $dss_value) {
					$idpaket_belanja_detail_sub = $dss_value->idpaket_belanja_detail_sub;

					$data_delete = az_crud_delete('paket_belanja_detail_sub', $idpaket_belanja_detail_sub, true);
					$err_code = $data_delete['err_code'];
					$err_message = $data_delete['err_message'];

					$this->db->where('is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
					$this->db->where('status', 1);
					$dsss = $this->db->get('paket_belanja_detail_sub');
					// echo "<pre>"; print_r($this->db->last_query());die;
					
					foreach ($dsss->result() as $dsss_key => $dsss_value) {

						$data_delete_sub = az_crud_delete('paket_belanja_detail_sub', $dsss_value->idpaket_belanja_detail_sub, true);
						$err_code = $data_delete_sub['err_code'];
						$err_message = $data_delete_sub['err_message'];
					}
				}
			}

			$data_delete = az_crud_delete('paket_belanja', $id, true);
			$err_code = $data_delete['err_code'];
			$err_message = $data_delete['err_message'];
		}
		
		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		echo json_encode($return);
	}

	function delete_akun_belanja() {
		$err_code = 0;
		$err_message = '';
		$message = '';

		$id = $this->input->post('id');

		$this->db->where('idpaket_belanja_detail', $id);
		$this->db->select('idpaket_belanja');
		$pb = $this->db->get('paket_belanja_detail');
		
		$idpaket_belanja = $pb->row()->idpaket_belanja;


		$data_validasi = $this->validasi_realisasi($idpaket_belanja); 

		$err_code = $data_validasi['err_code'];
		$err_message = $data_validasi['err_message'];

		if ($err_code == 0) {
			// cek apakah ada detail dari akun belanja ini?
			$this->db->where('idpaket_belanja_detail', $id);
			$this->db->where('status', 1);
			$ds = $this->db->get('paket_belanja_detail_sub');
			
			foreach ($ds->result() as $key => $value) {
				// kategori / sub kategori
				$idpaket_belanja_detail_sub = $value->idpaket_belanja_detail_sub;

				$data_delete = az_crud_delete('paket_belanja_detail_sub', $idpaket_belanja_detail_sub, true);
				$err_code = $data_delete['err_code'];
				$err_message = $data_delete['err_message'];

				// sub detail
				$this->db->where('status', 1);
				$this->db->where('is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
				$dss = $this->db->get('paket_belanja_detail_sub');

				foreach ($dss->result() as $dss_key => $dss_value) {

					$data_delete = az_crud_delete('paket_belanja_detail_sub', $dss_value->idpaket_belanja_detail_sub, true);
					$err_code = $data_delete['err_code'];
					$err_message = $data_delete['err_message'];
				}
			}

			$data_delete = az_crud_delete('paket_belanja_detail', $id, true);
			$err_code = $data_delete['err_code'];
			$err_message = $data_delete['err_message'];
		}

		// cek apakah masih ada akun belanja/detail transaksi di paket belanja ini?		
		if ($err_code == 0) {
			$this->db->where('idpaket_belanja', $idpaket_belanja);
			$this->db->where('status', 1);
			$paket_belanja_detail = $this->db->get('paket_belanja_detail');

			if ($paket_belanja_detail->num_rows() == 0) {
				$arr_update = array(
					'status_paket_belanja' => 'DRAFT',
				);
				az_crud_save($idpaket_belanja, 'paket_belanja', $arr_update);

				$message = 'Akun belanja berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan akun belanja baru, harap klik simpan transaksi paket belanja, agar datanya tidak hilang.</span>';
			}
		}
		
		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
			'idpaket_belanja' => $idpaket_belanja,
		);
		echo json_encode($return);
	}

	function delete_detail() {
		$err_code = 0;
		$err_message = '';

		$id = $this->input->post('id');

		$this->db->where('idpaket_belanja_detail_sub', $id);
		$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail', 'left');
		$this->db->select('idpaket_belanja, is_idpaket_belanja_detail_sub');
		$pb = $this->db->get('paket_belanja_detail_sub');
		
		// jika ada turunan
		if (strlen($pb->row()->is_idpaket_belanja_detail_sub) > 0) {
			$this->db->where('idpaket_belanja_detail_sub', $pb->row()->is_idpaket_belanja_detail_sub);
			$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
			$this->db->select('idpaket_belanja, is_idpaket_belanja_detail_sub');
			$pb = $this->db->get('paket_belanja_detail_sub');
		}
		// echo "<pre>"; print_r($this->db->last_query());die;
		
		$idpaket_belanja = $pb->row()->idpaket_belanja;

		$data_validasi = $this->validasi_realisasi($idpaket_belanja); 

		$err_code = $data_validasi['err_code'];
		$err_message = $data_validasi['err_message'];
		
		if ($err_code == 0) {
			// cek apakah ada detail dari akun belanja ini?
			$this->db->where('is_idpaket_belanja_detail_sub', $id);
			$this->db->where('status', 1);
			$ds = $this->db->get('paket_belanja_detail_sub');
			
			foreach ($ds->result() as $key => $value) {
				// kategori / sub kategori
				$idpaket_belanja_detail_sub = $value->idpaket_belanja_detail_sub;

				$data_delete = az_crud_delete('paket_belanja_detail_sub', $idpaket_belanja_detail_sub, true);
				$err_code = $data_delete['err_code'];
				$err_message = $data_delete['err_message'];
			}

			$data_delete = az_crud_delete('paket_belanja_detail_sub', $id, true);
			$err_code = $data_delete['err_code'];
			$err_message = $data_delete['err_message'];

			$this->calculate_nilai_anggaran($idpaket_belanja);	
		}
		
		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpaket_belanja' => $idpaket_belanja,
		);
		echo json_encode($return);
	}

	function validasi_realisasi($idpaket_belanja) {
		$err_code = 0;
		$err_message = '';

		// cek apakah paket belanja ini sudah ada realisasinya
		$this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction.transaction_status != "DRAFT" ');
		$this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
		$trx = $this->db->get('transaction_detail');

		if ($trx->num_rows() > 0) {
			$err_code++;
			$err_message = 'Paket belanja ini tidak bisa dihapus karena sudah ada realisasinya';
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);

		return $return;
	}

	function recalculate_total_anggaran() {

		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->where('paket_belanja.status_paket_belanja = "OK" ');
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = paket_belanja_detail.idpaket_belanja');
		$this->db->select('idpaket_belanja_detail, nama_akun_belanja, status_paket_belanja, akun_belanja.no_rekening_akunbelanja, paket_belanja.idpaket_belanja, akun_belanja.idakun_belanja');
		$pb_detail = $this->db->get('paket_belanja_detail');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$arr_pb_detail = array();
		foreach ($pb_detail->result() as $key => $value) {
			$idpaket_belanja_detail = $value->idpaket_belanja_detail;

			// get sub detail
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail', $idpaket_belanja_detail);
			$this->db->where('paket_belanja_detail_sub.status', 1);
			$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
			$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
			$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
			$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
			$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori,
			 paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, akun_belanja.no_rekening_akunbelanja, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
			$pb_detail_sub = $this->db->get('paket_belanja_detail_sub');
			// echo "<pre>"; print_r($this->db->last_query());die;

			$arr_pd_detail_sub = array();
			foreach ($pb_detail_sub->result() as $ds_key => $ds_value) {

				// get sub sub detail
				$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $ds_value->idpaket_belanja_detail_sub);
				$this->db->where('paket_belanja_detail_sub.status', 1);
				$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
				$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');
				$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
				$pd_detail_sub_sub = $this->db->get('paket_belanja_detail_sub');
				// echo "<pre>"; print_r($this->db->last_query());die;

				$arr_pd_detail_sub_sub = array();
				foreach ($pd_detail_sub_sub->result() as $dss_key => $dss_value) {
					$arr_pd_detail_sub_sub[] = array(
						'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
						'idpaket_belanja_detail' => $dss_value->idpaket_belanja_detail,
						'idsub_kategori' => $dss_value->idsub_kategori,
						'nama_subkategori' => $dss_value->nama_sub_kategori,
						'is_kategori' => $dss_value->is_kategori,
						'is_subkategori' => $dss_value->is_subkategori,
						'volume' => $dss_value->volume,
						'nama_satuan' => $dss_value->nama_satuan,
						'harga_satuan' => $dss_value->harga_satuan,
						'jumlah' => $dss_value->jumlah,
					);
				}


				$arr_pd_detail_sub[] = array(
					'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
					'idpaket_belanja_detail' => $ds_value->idpaket_belanja_detail,
					'idkategori' => $ds_value->idkategori,
					'nama_kategori' => $ds_value->nama_kategori,
					'idsub_kategori' => $ds_value->idsub_kategori,
					'nama_subkategori' => $ds_value->nama_sub_kategori,
					'is_kategori' => $ds_value->is_kategori,
					'is_subkategori' => $ds_value->is_subkategori,
					'no_rekening_akunbelanja' => $ds_value->no_rekening_akunbelanja,
					'volume' => $ds_value->volume,
					'nama_satuan' => $ds_value->nama_satuan,
					'harga_satuan' => $ds_value->harga_satuan,
					'jumlah' => $ds_value->jumlah,
					'arr_pd_detail_sub_sub' => $arr_pd_detail_sub_sub,
				);
			}

			$arr_pb_detail[] = array(
				'idpaket_belanja_detail' => $value->idpaket_belanja_detail,
				'nama_akun_belanja' => $value->no_rekening_akunbelanja." - ".$value->nama_akun_belanja,
				'status_paket_belanja' => $value->status_paket_belanja,
				'idpaket_belanja' => $value->idpaket_belanja,
				'idakun_belanja' => $value->idakun_belanja,
				'arr_pb_detail_sub' => $arr_pd_detail_sub,
			);
		}

		$data['arr_pb_detail'] = $arr_pb_detail;
		// echo "<pre>"; print_r($data); die;

		$view = $this->load->view('paket_belanja/v_paket_belanja_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}


	// untuk mengisi idpaket belanja di table paket belanja detail sub
	function set_idpaket_belanja() {
		$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
		$this->db->select('idpaket_belanja_detail_sub, paket_belanja_detail.idpaket_belanja as txt_idpaket_belanja, paket_belanja_detail_sub.idpaket_belanja');
		$sub = $this->db->get('paket_belanja_detail_sub');

		$total_data = $sub->num_rows();

		foreach ($sub->result() as $key => $value) {
			$arr_update = array(
				'idpaket_belanja' => $value->txt_idpaket_belanja,
			);

			$this->db->where('idpaket_belanja_detail_sub', $value->idpaket_belanja_detail_sub);
			$this->db->update('paket_belanja_detail_sub', $arr_update);
		}

		echo "Done<br>";
		echo $total_data." Data.";
	}

	function testing_helper() {
		$this->load->helper('transaction_status_helper');

		
	}
}
