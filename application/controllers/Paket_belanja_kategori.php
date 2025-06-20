<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paket_belanja_kategori extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('paket_belanja_kategori');
        $this->table = 'paket_belanja';
        $this->controller = 'paket_belanja_kategori';

        $this->load->helper('az_crud');
    }

	function add_kategori() {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

        $idpaket_belanja = $this->input->get('id');
        $idpaket_belanja_detail = $this->input->get('idd');

        // get data
        $this->db->where('paket_belanja.idpaket_belanja', $idpaket_belanja);
        $this->db->where('paket_belanja_detail.idpaket_belanja_detail', $idpaket_belanja_detail);
        $this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
        $this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
        $this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja = paket_belanja.idpaket_belanja');
        $this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
        $this->db->select('paket_belanja.nama_paket_belanja, kegiatan.idprogram, concat(program.no_rekening_program, " - ", program.nama_program) as nama_program, sub_kegiatan.idkegiatan, concat(kegiatan.no_rekening_kegiatan, " - ", kegiatan.nama_kegiatan) as nama_kegiatan, paket_belanja.idsub_kegiatan, concat(sub_kegiatan.no_rekening_subkegiatan, " - ", sub_kegiatan.nama_subkegiatan) as nama_subkegiatan, paket_belanja.idpaket_belanja, paket_belanja.nama_paket_belanja, paket_belanja.nilai_anggaran, paket_belanja_detail.idpaket_belanja_detail, akun_belanja.idakun_belanja, concat(akun_belanja.no_rekening_akunbelanja, " - ", akun_belanja.nama_akun_belanja) as nama_akun_belanja');
        $paket_belanja = $this->db->get('paket_belanja');
        // echo "<pre>"; print_r($this->db->last_query());die;

        $data = array(
            'idpaket_belanja' => $idpaket_belanja,
            'idpaket_belanja_detail' => $idpaket_belanja_detail,
            'nama_paket_belanja' => $paket_belanja->row()->nama_paket_belanja,
            'idprogram' => $paket_belanja->row()->idprogram,
            'nama_program' => $paket_belanja->row()->nama_program,
            'idkegiatan' => $paket_belanja->row()->idkegiatan,
            'nama_kegiatan' => $paket_belanja->row()->nama_kegiatan,
            'idsub_kegiatan' => $paket_belanja->row()->idsub_kegiatan,
            'nama_subkegiatan' => $paket_belanja->row()->nama_subkegiatan,
            'idakun_belanja' => $paket_belanja->row()->idakun_belanja,
            'nama_akun_belanja' => $paket_belanja->row()->nama_akun_belanja,
            'nilai_anggaran' => $paket_belanja->row()->nilai_anggaran,
        );

		$view = $this->load->view('paket_belanja_kategori/v_paket_belanja_kategori', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('paket_belanja_kategori/v_paket_belanja_kategori_modal', $data, true);
		$modal = $azapp->add_modal();
		$modal->set_id('add');
		$modal->set_modal_title('Tambah Akun Belanja');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_akun_belanja'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('paket_belanja_kategori/vjs_paket_belanja_kategori_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Paket Belanja -> Akun Belanja';
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
		$this->db->select('kegiatan.idprogram, concat(program.no_rekening_program, " - ", program.nama_program) as nama_program, sub_kegiatan.idkegiatan, concat(kegiatan.no_rekening_kegiatan, " - ", kegiatan.nama_kegiatan) as nama_kegiatan, paket_belanja.idsub_kegiatan, concat(sub_kegiatan.no_rekening_subkegiatan, " - ", sub_kegiatan.nama_subkegiatan) as nama_subkegiatan, paket_belanja.nama_paket_belanja, paket_belanja.nilai_anggaran');
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

	function add_akun_belanja() {
		$err_code = 0;
		$err_message = '';

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
			// iduser_onthespot, transaction_date_start, transaction_date, transaction_code, total_weight, total_delivery, total_delivery_weight, total_price, unique_code, grand_total_price, transaction_status, transaction_state, is_onthespot
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
			'idpaket_belanja_detail' => az_encode_url($idpaket_belanja_detail),
		);
		echo json_encode($return);
	}

	function get_list_akun_belanja() {
		$idpaket_belanja = $this->input->post("idpaket_belanja");

		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = paket_belanja_detail.idpaket_belanja');
		$this->db->select('idpaket_belanja_detail, nama_akun_belanja, status_paket_belanja');
		$pb_detail = $this->db->get('paket_belanja_detail');

		$arr_pb_detail = array();
		foreach ($pb_detail->result() as $key => $value) {
			$arr_pb_detail[] = array(
				'idpaket_belanja_detail' => $value->idpaket_belanja_detail,
				'nama_akun_belanja' => $value->nama_akun_belanja,
				'status_paket_belanja' => $value->status_paket_belanja,
			);
		}

		$data['arr_pb_detail'] = $arr_pb_detail;

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

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idprogram', 'Nama Program', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idkegiatan', 'Nomor Kegiatan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idsub_kegiatan', 'Nomor Sub Kegiatan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_paket_belanja', 'Nomor Paket Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nilai_anggaran', 'Jumlah Anggaran', 'required|trim|max_length[200]');
		
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
	    	$arr_data = array(
	    		'idprogram' => $idprogram,
	    		'idkegiatan' => $idkegiatan,
	    		'idsub_kegiatan' => $idsub_kegiatan,
	    		'nama_paket_belanja' => $nama_paket_belanja,
	    		'nilai_anggaran' => $nilai_anggaran,
				'status_paket_belanja' => "OK",
	    	);

	    	az_crud_save($idpaket_belanja, 'paket_belanja', $arr_data);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function delete_akun_belanja() {
		$err_code = 0;
		$err_message = '';

		$id = $this->input->post('id');

		$this->db->where('idpaket_belanja_detail', $id);
		$this->db->select('idpaket_belanja');
		$pb = $this->db->get('paket_belanja_detail');
		
		$idpaket_belanja = $pb->row()->idpaket_belanja;
		
		// cek apakah ada detail dari akun belanja ini?

		$data_delete = az_crud_delete('paket_belanja_detail', $id, true);
		$err_code = $data_delete['err_code'];
		$err_message = $data_delete['err_message'];
		
		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpaket_belanja' => $idpaket_belanja,
		);
		echo json_encode($return);
	}
}
