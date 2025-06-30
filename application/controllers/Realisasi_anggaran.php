<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasi_anggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('realisasi_anggaran');
        $this->table = 'transaction';
        $this->controller = 'realisasi_anggaran';
        $this->load->helper('az_crud');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal Realisasi', 'Nomor Invoice', 'Total Realisasi', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		$date1->set_value('01-'.Date('m-Y'));
		$data['date1'] = $date1->render();

		$date2 = $azapp->add_datetime();
		$date2->set_id('date2');
		$date2->set_name('date2');
		$date2->set_format('DD-MM-YYYY');
		$date2->set_value(Date('t-m-Y'));
		$data['date2'] = $date2->render();

		$crud->add_aodata('date1', 'date1');
		$crud->add_aodata('date2', 'date2');
		$crud->add_aodata('transaction_code', 'transaction_code');

		$vf = $this->load->view('realisasi_anggaran/vf_realisasi_anggaran', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'realisasi_anggaran';
		$view = $this->load->view('realisasi_anggaran/v_format_realisasi_anggaran', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('realisasi_anggaran/vjs_realisasi_anggaran');
		$azapp->add_js($js);

		$data_header['title'] = 'Realisasi Anggaran';
		$data_header['breadcrumb'] = array('realisasi_anggaran');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$transaction_code = $this->input->get('transaction_code');

        $crud->set_select('idtransaction, date_format(transaction_date, "%d-%m-%Y %H:%i:%s") as txt_transaction_date, transaction_code, total_realisasi, user.name as user_created');
        $crud->set_select_table('idtransaction, txt_transaction_date, transaction_code, total_realisasi, user_created');
        $crud->set_sorting('transaction_date, transaction_code, total_realisasi');
        $crud->set_filter('transaction_date, transaction_code, total_realisasi');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , right');

        $crud->add_join_manual('user', 'transaction.iduser_created = user.iduser');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(transaction.transaction_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(transaction.transaction_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($transaction_code) > 0) {
			$crud->add_where('transaction.transaction_code = "' . $transaction_code . '"');
		}

		$crud->add_where("transaction.status = 1");
		$crud->add_where("transaction.transaction_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('transaction_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		
		if ($key == 'total_realisasi') {
			$total_realisasi = az_thousand_separator($value);

			return $total_realisasi;
		}

		if ($key == 'action') {
            $idtransaction = azarr($data, 'idtransaction');

            $btn = '<button class="btn btn-default btn-xs btn-edit-realisasi-anggaran" data_id="'.$idtransaction.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-realisasi-anggaran" data_id="'.$idtransaction.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

            $this->db->where('idtransaction', $idtransaction);
            $trx = $this->db->get('transaction');

            $trx_status = $trx->row()->transaction_status;
            // USER INPUT => statusnya INPUT DATA
            // USER VERIFIKASI => statusnya MENUNGGU VERIFIKASI, SUDAH DIVERIFIKASI
            // USER BENDAHARA => SUDAH DIVERIFIKASI, SUDAH DIBAYAR BENDAHARA
            if (in_array($trx_status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'SUDAH DIVERIFIKASI BENDAHARA'))) {
                $btn = '<button class="btn btn-info btn-xs btn-view-only-realisasi-anggaran" data_id="'.$idtransaction.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';
            }

			return $btn;
		}

		return $value;
	}
    
    function add($id = '') {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

        $data = array(
            'id' => $id,
            'iduser_created' => $this->session->userdata('iduser'),
            'user_name' => $this->session->userdata('name'),
        );
        
		$view = $this->load->view('realisasi_anggaran/v_realisasi_anggaran', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('realisasi_anggaran/v_realisasi_anggaran_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add');
		$modal->set_modal_title('Tambah Realisasi');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('realisasi_anggaran/vjs_realisasi_anggaran_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Realisasi Anggaran';
		$data_header['breadcrumb'] = array('realisasi_anggaran');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function edit($id) {
		$this->db->where('idtransaction', $id);
		$check = $this->db->get('transaction');
		if ($check->num_rows() == 0) {
			redirect(app_url().'realisasi_anggaran');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->transaction_status;
			if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'SUDAH DIBAYAR BENDAHARA'))) {
				redirect(app_url().'realisasi_anggaran');
			}
		}
		$this->add($id);
	}

    function search_paket_belanja() {
		$keyword = $this->input->get("term");

		$this->db->order_by("nama_paket_belanja");
		$this->db->where('status', 1);
		$this->db->like('nama_paket_belanja', $keyword);
		$this->db->select("idpaket_belanja as id, nama_paket_belanja as text");

		$data = $this->db->get("paket_belanja");

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

    function select_paket_belanja() {
		$id = $this->input->post('id');

		$this->db->where('idpaket_belanja', $id);
		$this->db->join('sub_kegiatan', 'paket_belanja.idsub_kegiatan = sub_kegiatan.idsub_kegiatan');
		$this->db->join('kegiatan', 'sub_kegiatan.idkegiatan = kegiatan.idkegiatan');
		$this->db->join('program', 'kegiatan.idprogram = program.idprogram');
		$this->db->join('bidang_urusan', 'program.idbidang_urusan = bidang_urusan.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'bidang_urusan.idurusan_pemerintah = urusan_pemerintah.idurusan_pemerintah');
        $this->db->select('nama_urusan, nama_bidang_urusan, nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja');
		$paket_belanja = $this->db->get('paket_belanja');
        // echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'nama_urusan' => $paket_belanja->row()->nama_urusan,
			'nama_bidang_urusan' => $paket_belanja->row()->nama_bidang_urusan,
			'nama_program' => $paket_belanja->row()->nama_program,
			'nama_kegiatan' => $paket_belanja->row()->nama_kegiatan,
			'nama_subkegiatan' => $paket_belanja->row()->nama_subkegiatan,
			'nama_paket_belanja' => $paket_belanja->row()->nama_paket_belanja,
			'idpaket_belanja' => $id,
		);

		echo json_encode($ret);
	}

    public function get_paket_belanja_detail_sub_parent(){
		$idpaket_belanja = $this->input->post("idpaket_belanja");
		
		// var_dump($parent);die();
		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->where('idpaket_belanja', $idpaket_belanja);
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail = paket_belanja_detail.idpaket_belanja_detail');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
		$pb_detail = $this->db->get('paket_belanja_detail');
        // echo "<pre>"; print_r($this->db->last_query()); die;

		$arr_data = array();
		foreach ($pb_detail->result() as $key => $value) {
			
			// cek apakah ada detailnya
			$this->db->where('paket_belanja_detail_sub.status', 1);
			$this->db->where('is_idpaket_belanja_detail_sub', $value->idpaket_belanja_detail_sub);
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
			$dss = $this->db->get('paket_belanja_detail_sub');

			if ($dss->num_rows() == 0) {
				$arr_data[] = array(
					'idpaket_belanja_detail_sub' => $value->idpaket_belanja_detail_sub,
					'iduraian' => $value->idsub_kategori,
					'nama_uraian' => $value->nama_sub_kategori,
					'is_gender' =>$value->is_gender,
				);
			}
			else {
				foreach ($dss->result() as $dss_key => $dss_value) {
					$arr_data[] = array(
						'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
						'iduraian' => $dss_value->idsub_kategori,
						'nama_uraian' => $dss_value->nama_sub_kategori,
						'is_gender' =>$dss_value->is_gender,
					);
				}
			}
		}

		$results = array(
		  "results" => $arr_data,
		);
        // echo "<pre>"; print_r($results);die;

		echo json_encode($results);
	}

    function add_product() {
		$err_code = 0;
		$err_message = '';
		$validate_gender = false;


	 	$idtransaction = $this->input->post('idtransaction');
	 	$idtransaction_detail = $this->input->post('idtransaction_detail');
		$idpaket_belanja = $this->input->post('idpaket_belanja');
	 	$iduraian = $this->input->post('iduraian');
		$volume = az_crud_number($this->input->post('volume'));	
		$laki = az_crud_number($this->input->post('laki'));
		$perempuan = az_crud_number($this->input->post('perempuan'));
		$harga_satuan = az_crud_number($this->input->post('harga_satuan'));
		$ppn = az_crud_number($this->input->post('ppn'));
		$pph = az_crud_number($this->input->post('pph'));
        $transaction_description = $this->input->post('transaction_description');

		$total = (floatval($volume) * floatval($harga_satuan)) + floatval($ppn) - floatval($pph);


		$this->load->library('form_validation');
		$this->form_validation->set_rules('idpaket_belanja', 'Paket Belanja', 'required');
		$this->form_validation->set_rules('iduraian', 'Uraian', 'required');
		$this->form_validation->set_rules('volume', 'Volume', 'required');
		$this->form_validation->set_rules('volume', 'Volume', 'required');


		$this->form_validation->set_rules('idpaket_belanja', 'Paket Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('iduraian', 'Uraian', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('volume', 'Volume', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim|max_length[200]');

		// validasi apakah inputan laki-laki dan perempuan wajib diisi
		if (strlen($iduraian) > 0) {
			$this->db->where('status', 1);
			$this->db->where('idsub_kategori', $iduraian);
			$this->db->select('is_gender');
			$sub_kategori = $this->db->get('sub_kategori');

			if ($sub_kategori->num_rows() > 0) {
				// 0 : tidak wajib isi jenis kelamin
				// 1 : wajib isi jenis kelamin
				if ($sub_kategori->row()->is_gender == 1) {
					$validate_gender = true;
				}
			}
			else {
				$err_code++;
				$err_message = "Uraian tidak ditemukan.";
			}
		}

		if ($validate_gender) {
			$this->form_validation->set_rules('laki', 'Laki-laki', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('perempuan', 'Perempuan', 'required|trim|max_length[200]');
		}

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		// validasi jumlah laki-laki & perempuan tidak boleh lebih dari volume
		if ($err_code == 0) {
			if ($validate_gender) {
				if (floatval($volume) != (floatval($laki) + floatval($perempuan)) ) {
					$err_code++;
					$err_message = "Jumlah inputan total laki-laki dan perempuan tidak sama dengan inputan volume.";
				}
			}
		}

		// validasi volume total volume yang sudah terealisasi tidak boleh lebih dari volume yang sudah ditentukan
		if ($err_code == 0) {
			// code
		}

		if ($err_code == 0) {
			$this->db->where('idtransaction',$idtransaction);
			$transaction = $this->db->get('transaction');

			if ($transaction->num_rows() > 0) {
				$status = $transaction->row()->transaction_status;
				if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'SUDAH DIBAYAR BENDAHARA'))) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {

			if (strlen($idtransaction) == 0) {
				$arr_transaction = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'transaction_date' => Date('Y-m-d H:i:s'),
					'transaction_status' => 'DRAFT',
					'transaction_code' => $this->generate_transaction_code(),
				);

				$save_transaction = az_crud_save($idtransaction, 'transaction', $arr_transaction);
				$idtransaction = azarr($save_transaction, 'insert_id');
			}
			else {
				// validasi apakah data paket belanja yang disimpan sama?
				// jika tidak maka data tidak perlu disimpan

				$this->db->where('status', 1);
				$this->db->where('idtransaction', $idtransaction);
				$this->db->where('idpaket_belanja', $idpaket_belanja);
				$trxd = $this->db->get('transaction_detail');

				if ($trxd->num_rows() == 0) {
					$err_code++;
					$err_message = "Paket Belanja yang anda inputkan berbeda dengan paket belanja yang telah diinputkan sebelumnya. <br>";
					$err_message .= "Silahkan menginputkan data dengan paket belanja yang sama.";
				}
			}
            
			//transaction detail
			$arr_transaction_detail = array(
				'idtransaction' => $idtransaction,
				'idpaket_belanja' => $idpaket_belanja,
				'iduraian' => $iduraian,
				'volume' => $volume,
				'laki' => $laki,
				'perempuan' => $perempuan,
				'harga_satuan' => $harga_satuan,
				'ppn' => $ppn,
				'pph' => $pph,
				'total' => $total,
				'transaction_description' => $transaction_description,
			);
			
			$td = az_crud_save($idtransaction_detail, 'transaction_detail', $arr_transaction_detail);
			$idtransaction_detail = azarr($td, 'insert_id');

			// hitung total transaksi
			$this->calculate_total_realisasi($idtransaction);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idtransaction' => $idtransaction,
			'idtransaction_detail' => $idtransaction_detail,
		);
		echo json_encode($return);
	}

	function save_realisasi() {
		$err_code = 0;
		$err_message = '';

		
		$idtransaction = $this->input->post("hd_idtransaction");
		$transaction_date = az_crud_date($this->input->post("transaction_date"));
		$iduser_created = $this->input->post("iduser_created");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('transaction_date', 'Tanggal Realisasi', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idtransaction) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$this->db->where('idtransaction',$idtransaction);
			$transaction = $this->db->get('transaction');

			if ($transaction->num_rows() > 0) {
				$status = $transaction->row()->transaction_status;
				if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'SUDAH DIBAYAR BENDAHARA'))) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'transaction_date' => $transaction_date,
	    		'transaction_status' => "INPUT DATA",
	    		'iduser_created' => $iduser_created,
	    	);

	    	az_crud_save($idtransaction, 'transaction', $arr_data);

			// hitung total transaksi
			$this->calculate_total_realisasi($idtransaction);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function delete_realisasi() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('idtransaction',$id);
		$transaction = $this->db->get('transaction');

		if ($transaction->num_rows() > 0) {
			$status = $transaction->row()->transaction_status;
			if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'SUDAH DIBAYAR BENDAHARA'))) {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";
			}
		}

		if($err_code == 0) {
			az_crud_delete($this->table, $id);

		} 
		else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}

	function edit_order() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where('idtransaction_detail', $id);
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = transaction_detail.iduraian');
		$this->db->select('transaction_detail.idpaket_belanja, transaction_detail.iduraian, sub_kategori.nama_sub_kategori, transaction_detail.volume, transaction_detail.harga_satuan, transaction_detail.ppn, transaction_detail.pph, transaction_detail.total, transaction_detail.transaction_description, transaction_detail.laki, transaction_detail.perempuan');
		$trxd = $this->db->get('transaction_detail')->result_array();

		$ret = array(
			'data' => azarr($trxd, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function delete_order() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = "";
		$is_delete = true;

		$this->db->where('idtransaction_detail',$id);
		$this->db->join('transaction', 'transaction_detail.idtransaction = transaction.idtransaction');
		$transaction = $this->db->get('transaction_detail');

		$status = $transaction->row()->transaction_status;
		$idtransaction = $transaction->row()->idtransaction;
		if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'SUDAH DIBAYAR BENDAHARA'))) {
			$is_delete = false;
		}

		if ($is_delete) {
			$delete = az_crud_delete('transaction_detail', $id, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				// hitung total transaksi
				$this->calculate_total_realisasi($idtransaction);
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);

		echo json_encode($return);
	}

	function get_data() {
		$id = $this->input->post('id');
		$this->db->where('transaction.idtransaction', $id);
		$this->db->join('user', 'user.iduser = transaction.iduser_created');
		$this->db->select('date_format(transaction_date, "%d-%m-%Y %H:%i:%s") as txt_transaction_date, transaction_code, user.name as user_created, transaction.iduser_created');
		$transaction = $this->db->get('transaction')->result_array();

		$this->db->where('idtransaction', $id);
		$transaction_detail = $this->db->get('transaction_detail')->result_array();

		$return = array(
			'transaction' => azarr($transaction, 0),
			'transaction_detail' => $transaction_detail
		);
		echo json_encode($return);
	}

    function get_list_order() {
		$idtransaction = $this->input->post("idtransaction");

        $this->db->where('transaction_detail.status', 1);
        $this->db->where('transaction_detail.idtransaction', $idtransaction);
		$this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = transaction_detail.iduraian');
		$this->db->select('transaction_detail.idtransaction_detail, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori as nama_uraian, transaction_detail.volume, transaction_detail.harga_satuan, transaction_detail.total, transaction.transaction_status, transaction.total_realisasi');
        $trx_detail = $this->db->get('transaction_detail');

        $data['detail'] = $trx_detail->result_array();
		$data['total_realisasi'] = $trx_detail->row()->total_realisasi;

		$view = $this->load->view('realisasi_anggaran/v_realisasi_anggaran_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	public function get_validate_gender() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_gender');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_gender = $sub_kategori->row()->is_gender;
		
		echo json_encode($is_gender);
	}

    private function generate_transaction_code() {
		$this->db->where('day(transaction_date)', Date('d'));
		$this->db->where('month(transaction_date)', Date('m'));
		$this->db->where('year(transaction_date)', Date('Y'));
		$this->db->where('transaction_code != "NULL" ');
		$this->db->order_by('transaction_code desc');
		$data = $this->db->get('transaction', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$transaction_code = 'ON'.Date('Ymd').$numb;
		}
		else {
			$last = $data->row()->transaction_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$transaction_code = 'ON'.Date('Ymd').$numb;

			$this->db->where('transaction_code', $transaction_code);
			$this->db->select('transaction_code');
			$check = $this->db->get('transaction');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($transaction_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$transaction_code = 'ON'.Date('Ymd').$numb;

				$this->db->where('transaction_code', $transaction_code);
				$this->db->select('transaction_code');
				$check = $this->db->get('transaction');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

		return $transaction_code;
	}

	function calculate_total_realisasi($idtransaction) {

		$this->db->where('status', 1);
		$this->db->where('idtransaction', $idtransaction);
		$this->db->select('sum(total) as total_realisasi');
		$trxd = $this->db->get('transaction_detail');

		$total_realisasi = azobj($trxd->row(), 'total_realisasi', 0);

		$arr_update = array(
			'total_realisasi' => $total_realisasi,
		);

		az_crud_save($idtransaction, 'transaction', $arr_update);
	}

    ///////////////////////////////////
	//// Cek
	///////////////////////////////////
    function validation_data_customer()
	{
		// dump($this->input->post());die;
		$c_name = $this->input->post('customer_name');
		$c_email = $this->input->post('customer_email');
		$c_handphone = $this->input->post('customer_handphone');

		$number_phone = $c_handphone;
		$number_hp = str_replace('-','',$number_phone);
		$number_hp = str_replace('+62','0',$number_hp);
		$number_hp = str_replace(' ','',$number_hp);
		//Cek apakah nomor telepon sesuai sama nama orang nya
		$this->db->where(['handphone' => $number_hp]);
		$check_telp = $this->db->get('member');

		if($check_telp->num_rows() > 0 && strtolower($check_telp->row_array()['name']) != strtolower($c_name)){
			$return = [
				'success' => false,
				'data' => [
					'type' => 'confirm',
					'message' => 'Nama member berbeda! <br> Pelanggan dengan nomor telepon "<b>'.$number_hp.'</b>" telah terdata dengan nama <b>'.$check_telp->row_array()['name'].'</b>. Nama yang akan disimpan adalah <b>'.$check_telp->row_array()['name'].'</b>'
				]
			];
		}else{
			$return = [
				'success' => true,
				'data' => []
			];
		}
		echo json_encode($return) ;
	}

	public function get_member_data()
	{

		$q = $this->input->get('q');

		$this->db->where('status >',0);
		$this->db->group_start();
		$this->db->like('handphone',$q);
		$this->db->or_like('name',$q);
		$this->db->group_end();
		$this->db->limit(5);
		$this->db->order_by('handphone');
		$data['member'] = $this->db->get('member')->result();

		echo json_encode($data);
	}

	public function get_single_member_data() {

		$id = $this->input->get('id');
		$number = $this->input->get('number');

		$this->db->where('status >', 0);
		$this->db->where('idmember', $id);
		$data['member'] = $this->db->get('member')->row();

		$number = str_replace(' ', '-', $number); // Replaces all spaces with hyphens.

		$number = preg_replace('/[^A-Za-z0-9]/', '', $number); // Removes special chars.

		$data_number = str_replace(' ', '-', azobj($data['member'], 'handphone')); // Replaces all spaces with hyphens.

		$data_number = preg_replace('/[^A-Za-z0-9]/', '', $data_number); // Removes special chars.

		if ($number != $data_number) {
			$data['member'] = '';
		}

		echo json_encode($data);
	}

	function get_detail_product() {
		$idproduct = $this->input->post('idproduct');
		$is_edit = $this->input->post('is_edit');
		$product_type = "";

		$sip_product_finishing = az_get_config('sip_product_finishing', 'config_app');

		$this->db->where('product.idproduct', $idproduct);
		$this->db->where('product.status', 1);
		$this->db->join('product_unit', 'product.idproduct_unit = product_unit.idproduct_unit');
		$this->db->where('product.status', 1);
		$data = $this->db->get('product');
		$return = '';
		if ($data->num_rows() > 0) {
			$product_type = azobj($data->row(),'product_type');
			$this->db->where('status', 1);
			$this->db->where('idproduct', $data->row()->idproduct);
			$product_finishing = $this->db->get('product_finishing_group');
			$arr_finishing = array();
			foreach ($product_finishing->result() as $key => $value) {
				$this->db->select('product_finishing_group_detail.idproduct_finishing, finishing_name');
				$this->db->where('idproduct_finishing_group', $value->idproduct_finishing_group);
				$this->db->where('product_finishing_group_detail.status', 1);
				$this->db->join('product_finishing', 'product_finishing_group_detail.idproduct_finishing = product_finishing.idproduct_finishing', 'left');
				$product_finishing_group = $this->db->get('product_finishing_group_detail');

				$arr_detail = array();
				foreach ($product_finishing_group->result() as $group_key => $group_value) {
					$arr_ = array(
						'idproduct_finishing' => $group_value->idproduct_finishing,
						'product_finishing_name' => $group_value->finishing_name,
					);
					$arr_detail[] = $arr_;
				}
				$arr_finishing[] = array(
					'is_required' => $value->is_required,
					'product_finishing_group_name' => $value->finishing_group_name,
					'finishing' => $arr_detail
				);
			}
			$rdata['finishing'] = $arr_finishing;
			$this->db->where('idproduct', $data->row()->idproduct);
			$material_width_list = $this->db->get('product_material_width');
			$arr_material_width = array();
			foreach ($material_width_list->result() as $key => $value) {
				$arr_material_width[] = array(
					'idproduct_material_width' => $value->idproduct_material_width,
					'width' => $value->width,
				);
			}
			$rdata['material_width'] = $arr_material_width;
			$rdata['data'] = $data->row();

			$this->db->where('status', 1);
			$this->db->order_by('calendar_content');
			$rdata['product_calendar_content'] = $this->db->get('product_calendar_content');

			$rlength = '';
			$rwidth = '';
			$ris_two_side = '0';
			$rproduct_calendar_content = '1';
			$rqty = '';
			$rqty_group = '';
			$rfinishing = array();
			$rmaterial_length = '';
			$rmaterial_width = '';
			$ris_finishing = 0;
			if ($is_edit != '0' && $is_edit != 'x') {
				$is_edit = az_decode_url($is_edit);
				$this->db->where('idtransaction_detail', $is_edit);
				$detail = $this->db->get('transaction_detail')->row();
				$rlength = az_thousand_separator_decimal($detail->length);
				$rwidth = az_thousand_separator_decimal($detail->width);
				$ris_two_side = $detail->is_two_side;
				$rproduct_calendar_content = $detail->product_calendar_content;
				$rqty = $detail->qty;
				$rmaterial_length = $detail->material_length;
				$rmaterial_width = $detail->material_width;
				if($sip_product_finishing) {
					$rqty_group = $detail->qty_group;

					if(strlen($detail->idtransaction_detail_main) > 0) {
						$ris_finishing = 1;
					}
				}

				$this->db->where('idtransaction_detail', $is_edit);
				$db_finishing = $this->db->get('transaction_finishing');
				foreach ($db_finishing->result() as $fkey => $fvalue) {
					$rfinishing[] = $fvalue->idproduct_finishing;
				}
			}
			$this->load->helper('az_core');
			$rdata['rlength'] = $rlength;
			$rdata['rwidth'] = $rwidth;
			$rdata['ris_two_side'] = $ris_two_side;
			$rdata['rproduct_calendar_content'] = $rproduct_calendar_content;
			$rdata['rqty'] = $rqty;
			$rdata['rqty_group'] = $rqty_group;
			$rdata['rfinishing'] = $rfinishing;
			$rdata['rmaterial_length'] = $rmaterial_length;
			$rdata['rmaterial_width'] = $rmaterial_width;
			$rdata['ris_finishing'] = $ris_finishing;

			$return = $this->load->view('onthespot/v_container_product', $rdata, true);
		}

		$arr = array(
			'data' => $return,
			'product_type' => $product_type,
		);
		echo json_encode($arr);
	}

	private function null_delivery($idtransaction) {
		$arr_delivery = array(
			'delivery_type' => NULL,
			'delivery_code' => NULL,
			'delivery_name' => NULL,
			'delivery_time' => NULL,
		);
		$this->db->where('idtransaction', $idtransaction);
		$this->db->update('transaction_delivery', $arr_delivery);

		$arr_transaction = array(
			'total_delivery' => NULL,
			'total_delivery_weight' => NULL
		);
		$this->db->where('idtransaction', $idtransaction);
		$this->db->update('transaction', $arr_transaction);
	}

	function generate_delivery($iddistrict = '', $iddelivery = '', $idoutlet = '') {
		if (strlen($idoutlet) == 0) {
			$idoutlet = $this->input->post('idoutlet');
		}
		if (strlen($idoutlet) == 0) {
			$ret = array(
				'data' => ''
			);
			echo json_encode($ret);
			return;
		}

		if (strlen($iddistrict) == 0) {
			$iddistrict = $this->input->post('iddistrict');
		}
		$idtransaction = $this->input->post('idtransaction');
		$is_edit_transaction = $this->input->post('is_edit_transaction');
		$this->load->library('Rajaongkir');
		$this->db->where('status', 1);
		$this->db->where('is_active', 1);
		$product_delivery = $this->db->get('product_delivery');
		$db_delivery = array();
		foreach ($product_delivery->result() as $key => $value) {
			$db_delivery[] = $value->product_delivery_name;
		}

		$total_weight = 1000;
		$arr_delivery_cost = array();

		$city_origin = az_get_config('kota');

		$jne_delivery = array();
		$pos_delivery = array();
		$wahana_delivery = array();
		$jnt_delivery = array();
		$tiki_delivery = array();
		$sicepat_delivery = array();

		if (in_array('JNE', $db_delivery)) {
			$jne_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "jne"), true);
			$jne_delivery = element('rajaongkir', $jne_delivery, array());
			$jne_delivery = element('results', $jne_delivery, array());
		}

		if (in_array('POS', $db_delivery)) {
			$pos_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "pos"), true);
			$pos_delivery = element('rajaongkir', $pos_delivery, array());
			$pos_delivery = element('results', $pos_delivery, array());
		}

		if (in_array('WAHANA', $db_delivery)) {
			$wahana_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "wahana"), true);
			$wahana_delivery = element('rajaongkir', $wahana_delivery, array());
			$wahana_delivery = element('results', $wahana_delivery, array());
		}

		if (in_array('JNT', $db_delivery)) {
			$jnt_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "jnt"), true);
			$jnt_delivery = element('rajaongkir', $jnt_delivery, array());
			$jnt_delivery = element('results', $jnt_delivery, array());
		}

		if (in_array('TIKI', $db_delivery)) {
			$tiki_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "tiki"), true);
			$tiki_delivery = element('rajaongkir', $tiki_delivery, array());
			$tiki_delivery = element('results', $tiki_delivery, array());
		}

		if (in_array('SICEPAT', $db_delivery)) {
			$sicepat_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "sicepat"), true);
			$sicepat_delivery = element('rajaongkir', $sicepat_delivery, array());
			$sicepat_delivery = element('results', $sicepat_delivery, array());
		}

		$arr_delivery = array();

		$this->load->library('Rajaongkir');
		$city_data = json_decode($this->rajaongkir->get_subdistrict($iddistrict), true);
		$city_data = azarr($city_data, 'rajaongkir');
		$city_data = azarr($city_data, 'results');
		$city_destination = azarr($city_data, 'city_id');

		if ($city_destination == $city_origin && in_array('GOJEK', $db_delivery)) {
			$arr_delivery[] = array(
				'tipe' => 'gojek',
				'code' => 'kirim_gojek',
				'name' => 'Biaya kirim dibayar di pembeli',
				'etd' => '',
				'value' => '0',
			);
		}
		$data_gojek = $arr_delivery;

		$arr_delivery = array();
		if ($city_destination == $city_origin && in_array('GRAB', $db_delivery)) {
			$arr_delivery[] = array(
				'tipe' => 'grab',
				'code' => 'kirim_grab',
				'name' => 'Biaya kirim dibayar di pembeli',
				'etd' => '',
				'value' => '0',
			);
		}
		$data_grab = $arr_delivery;

		$arr_delivery = array();

		$data_jne = array();
		$data_pos = array();
		$data_wahana = array();
		$order_by['gojek'] = 0;
		$order_by['grab'] = 0;
		$order_by['jne'] = 0;
		$order_by['pos'] = 0;
		$order_by['wahana'] = 0;
		$order_by['jnt'] = 0;
		$order_by['tiki'] = 0;
		$order_by['sicepat'] = 0;
		foreach ($jne_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'jne') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$add = true;
					if (strpos($service, 'JTR') !== false) {
						$add = false;
					}

					if ($add) {
						$arr_delivery[] = array(
							'tipe' => 'jne',
							'code' => 'jne_'.$txt_service,
							'name' => 'JNE '.$service,
							'etd' => element('etd', $cost_value, ''),
							'value' => element('value', $cost_value, '')
						);
					}
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['jne'] = element('value', $arr_delivery[0], '');
		}
		$data_jne = $arr_delivery;

		$arr_delivery = array();
		foreach ($pos_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'pos') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'pos',
						'code' => 'pos_'.$txt_service,
						'name' => 'POS '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['pos'] = element('value', $arr_delivery[0], '');
		}
		$data_pos = $arr_delivery;

		$arr_delivery = array();
		foreach ($jnt_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'J&T') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'jnt',
						'code' => 'jnt_'.$txt_service,
						'name' => 'JNT '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['jnt'] = element('value', $arr_delivery[0], '');
		}
		$data_jnt = $arr_delivery;

		$arr_delivery = array();
		foreach ($tiki_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'tiki') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'tiki',
						'code' => 'tiki_'.$txt_service,
						'name' => 'TIKI '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['tiki'] = element('value', $arr_delivery[0], '');
		}
		$data_tiki = $arr_delivery;

		$arr_delivery = array();
		foreach ($sicepat_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'sicepat') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'sicepat',
						'code' => 'sicepat_'.$txt_service,
						'name' => 'SI CEPAT '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['sicepat'] = element('value', $arr_delivery[0], '');
		}
		$data_sicepat = $arr_delivery;

		$arr_delivery = array();
		foreach ($wahana_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'wahana') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'wahana',
						'code' => 'wahana_'.$txt_service,
						'name' => 'WAHANA '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['wahana'] = element('value', $arr_delivery[0], '');
		}
		$data_wahana = $arr_delivery;
		asort($order_by);

		$arr_delivery = array();
		foreach ($order_by as $key => $value) {
			$data_key = 'data_'.$key;
			$arr_delivery = array_merge($arr_delivery, $$data_key);
		}

		usort($arr_delivery, function($a, $b) {
			return $a['value'] - $b['value'];
		});


		$data_delivery = array();
		$data_delivery_id = array();
		foreach ($arr_delivery as $key => $value) {
			$duration = $value['etd'];
			$duration = str_replace('HARI', '', $duration);
			$duration = str_replace(' ', '', $duration).' HARI';
			// if ($value['code'] == 'kirim_gojek') {
			if (in_array($value['code'], array('kirim_gojek', 'kirim_grab'))) {
				$duration = '';
			}
			$data_delivery[] = array(
				'image' => base_url().AZAPP_FRONT.'assets/images/delivery/d_'.$value['tipe'].'.jpg',
				'name' => $value['name'],
				'duration' => $duration,
				'price' => $value['value'],
				'code' => $value['code']
			);
			if (strlen($iddelivery) > 0) {
				if ($value['code'] == $iddelivery) {
					$data_delivery_id = array(
						'name' => $value['name'],
						'duration' => $duration,
						'price' => $value['value'],
						'code' => $value['code'],
						'type' => $value['tipe']
					);
				}
			}
		}

		if (strlen($iddelivery) > 0) {
			return $data_delivery_id;
		}

		$this->db->where('idtransaction', $idtransaction);
		$dtransaction = $this->db->get('transaction');
		if ($dtransaction->num_rows() == 0) {
			$weight = 0;
		}
		else {
			$weight = $dtransaction->row()->total_weight;
		}

		if (!$is_edit_transaction) {
			if (strlen($idtransaction) > 0) {
				$this->null_delivery($idtransaction);
				$this->load->library('Lite');
				$this->lite->update_weight_price($idtransaction);
			}
		}

		$rdata['delivery'] = $data_delivery;
		$rdata['total_weight'] = $weight;
		$view = $this->load->view('onthespot/v_onthespot_delivery', $rdata, true);
		$ret = array(
			'data' => $view
		);
		echo json_encode($ret);
	}

	function update_delivery() {
		$idtransaction = $this->input->post('idtransaction');
		$price = $this->input->post('price');
		$weight = $this->input->post('weight');
		$delivery_code = $this->input->post('delivery_code');
		$iddistrict = $this->input->post('iddistrict');
		$idcity = $this->input->post('idcity');
		$idprovince = $this->input->post('idprovince');
		$idoutlet = $this->input->post('idoutlet');

		$total_delivery_weight = ceil($weight/1000) * az_crud_number($price);

		$arr = array(
			'total_delivery' => az_crud_number($price),
			'total_delivery_weight' => $total_delivery_weight
		);
		az_crud_save($idtransaction, 'transaction', $arr);

		$this->db->where('idtransaction', $idtransaction);
		// $transaction_delivery = $this->db->get('transaction_delivery')->row();
		$transaction_delivery = $this->db->get('transaction_delivery');
    	$data_delivery = $this->generate_delivery($iddistrict, $delivery_code, $idoutlet);

    	$this->load->helper('location');
		$province_name = get_province($idprovince);
		$city_name = get_city($idcity);
		$district_name = get_district($iddistrict);

		$arr_update_delivery = array(
			'delivery_type' => azarr($data_delivery, 'type'),
			'delivery_code' => azarr($data_delivery, 'code'),
			'delivery_name' => azarr($data_delivery, 'name'),
			'delivery_time' => azarr($data_delivery, 'duration'),
			'address' => $this->input->post('address'),
			'idprovince' => $idprovince,
			'idcity' => $idcity,
			'iddistrict' => $iddistrict,
			'province_name' => $province_name,
			'city_name' => $city_name,
			'district_name' => $district_name,
			'updated' => Date('Y-m-d H:i:s')
		);

		if ($transaction_delivery->num_rows() == 0) {
			$arr_update_delivery['idtransaction'] = $idtransaction;
			$this->db->insert('transaction_delivery', $arr_update_delivery);
		}
		else {
			$this->db->where('idtransaction', $idtransaction);
			$this->db->update('transaction_delivery', $arr_update_delivery);
		}

		$this->load->library('Lite');
		$this->lite->update_weight_price($idtransaction);

		$ret = array(
			'err_code' => 0
		);
		echo json_encode($ret);
	}

	

	function save_search() {
		$app_sipplus = az_get_config('app_sipplus', 'config_app');

		if($app_sipplus) {
			$text = $this->input->post('text');
			$keyword = str_replace('<strong>', '', $text);
			$keyword = str_replace('</strong>', '', $keyword);
			$keywords = explode(" ",$keyword);

			$err_code = 0;
			$err_message = '';
			if(strlen($keyword) > 0) {
				$this->db->select('product.idproduct, product.url_key, product.product_name, product.image1 as product_image, (SELECT price from product_price where product.idproduct = idproduct and range1 = -1) as product_price');
				$this->db->group_start();
				foreach ($keywords as $key => $value) {
					$this->db->like('product_name',$value);
				}
				$this->db->group_end();
				// $this->db->limit($limit,$page);
				$this->db->where('product.status >',0);
				$this->db->where('product.is_active >',0);
				$data_product = $this->db->get('product');
				$total_page = $data_product->num_rows();

				$is_found = 0;
				if ($data_product->num_rows() > 0) {
					$is_found = 1;
				}

				$data_smart_search = array(
					'keyword' => $keyword,
					'is_found' => $is_found,
					'created' => Date('Y-m-d H:i:s'),
					'updated' => Date('Y-m-d H:i:s'),
				);
				$this->db->insert('smart_search_log', $data_smart_search);
			}

			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message,
			);
			echo json_encode($ret);
		}
	}

	function get_term_payment() {
		$err_code = 0;
		$err_message = '';
		
		$data = array();
		$transaction_due_date = '';
		$term_payment_name = '';

		$idacc_term_payment = $this->input->post('idacc_term_payment');
		$date = $this->input->post('date');

		if ($date == null) {
			$date = date('d-m-Y');
		}

		if(strlen($idacc_term_payment) > 0 && strlen($date) > 0) {
			$this->db->select("idacc_term_payment, term_payment_name, time_period");
			$this->db->where('status', '1');
			$this->db->where('idacc_term_payment="'.$idacc_term_payment.'"');
			$term_payment = $this->db->get('acc_term_payment')->result_array();

			$term_payment_name = $term_payment[0]['term_payment_name'];

			if ($term_payment_name != "Custom") {
				$date=date_create($date);
				date_add($date,date_interval_create_from_date_string($term_payment[0]['time_period'].' days'));
				$transaction_due_date = date_format($date,"d-m-Y");
			}
		}else{
			$err_code++;
			$err_message = "Invalid ID"; 
		}

		$data["err_code"] = $err_code;
		$data["err_message"] = $err_message;
		$data["transaction_due_date"] = $transaction_due_date;
		$data["term_payment"] = $term_payment_name;
		echo json_encode($data);
	}

	public function check_desain_time()
	{
		$idtransaction = $this->input->get('idtransaction');
		$this->db->select('desain_start, desain_end');
		$this->db->where('transaction.idtransaction',$idtransaction);
		$this->db->where('product_type',"DESAIN");
		$this->db->where('transaction_detail.status',1);
		$this->db->where('desain_end is null');
		$this->db->join('transaction_detail','transaction_detail.idtransaction = transaction.idtransaction','left');
		$this->db->join('product','product.idproduct = transaction_detail.idproduct','left');
		$check = $this->db->get('transaction');

		$hours = 0;
		$minute = 0;
		$second = 0;
		$desain_start = "";

		if ($check->num_rows() > 0) {
			$desain_start = new DateTime($check->row()->desain_start);
			$desain_end = new DateTime(date('Y-m-d H:i:s'));
			$interval = $desain_start->diff($desain_end);

			$hours = $interval->format('%h');
			$minute = $interval->format('%i');
			$second = $interval->format('%s');
		}

		$return = array(
			'desain_start' => $desain_start,
			'hours' => $hours,
			'minute' => $minute,
			'second' => $second,
		);

		echo json_encode($return);
	}

	public function stop_desain($idtrx_detail = "")
	{
		$idtransaction_detail = az_decode_url($this->input->get('idtransaction_detail'));
		if (strlen($idtrx_detail) > 0) {
			$idtransaction_detail = $idtrx_detail;
		}

		$this->db->where('idtransaction_detail',$idtransaction_detail);
		$trx_detail = $this->db->get('transaction_detail');

		$is_stop = true;
		if ($trx_detail->num_rows() > 0) {
			if (strlen($trx_detail->row()->desain_end) > 0) {
				$is_stop = false;
			}
		}

		$error = false;
		$message = "";

		if ($trx_detail->num_rows() > 0 && $is_stop) {
			$idproduct = $trx_detail->row()->idproduct;
			$idtransaction = $trx_detail->row()->idtransaction;
			$desain_start = $trx_detail->row()->desain_start;


			$diff = strtotime(date('Y-m-d H:i:s')) - strtotime($desain_start) ;
			$diff = ceil($diff/60);
			$this->load->library('Lite');
			$data_price = $this->lite->get_product_price($idproduct,$diff);
			$product_price = azarr($data_price,'price');

			$this->db->where('idproduct',$idproduct);
			$product = $this->db->get('product');

			if ($product->num_rows() > 0) {
				$product = $product->row();

				if ($product->min_order_type = "SATUAN") {
					if ($diff < $product->min_order) {
						$diff = $product->min_order;
					}
				}
			}
			$grand_price = $product_price*$diff;

			$arr_update = array(
				'qty' => $diff,
				'price'=> $product_price,
				'grand_price' => $grand_price,
				'sub_price' => $product_price,
				'grand_product_finishing_price' => $product_price,
				'grand_product_finishing_sub_price' => $grand_price,
				'desain_end' => date('Y-m-d H:i:s'),
				'production_status' => 1,
				'is_taken' => 1,
				'is_updated' => 1
			);


			$arr_selective_taking = array(
				'idtransaction_detail' => $idtransaction_detail,
				'iduser_cashier' => $this->session->userdata('iduser'),
				'total_taken' => $diff,
				'taking_date' => date('Y-m-d H:i:s'),
				'created' => date('Y-m-d H:i:s'),
				'updated' => date('Y-m-d H:i:s')
			);


			$this->db->where('idtransaction_detail',$idtransaction_detail);
			$update  = $this->db->update('transaction_detail',$arr_update);

			$this->db->insert('transaction_selective_taking',$arr_selective_taking);

			if ($update) {
				$message = "Success";
			}

			$this->null_delivery($idtransaction);

			$this->lite->update_weight_price($idtransaction);			
		}
		else{
			$error = true;
			$message = "Stop Gagal!";
		}

		$return = array(
			'error' => $error,
			'message' => $message,
		);

		if (strlen($idtrx_detail) > 0) {
			return $return;
		}
		else{
			echo json_encode($return);			
		}

	}

	function calculate_product_finishing($param) {
		$idproduct = azarr($param, 'idproduct');
		$idtransaction_detail = azarr($param, 'idtransaction_detail');
		$qty = azarr($param, 'qty');

		$this->db->where('idproduct', $idproduct);
		$rproduct = $this->db->get('product')->row();

		$min_order = $rproduct->min_order;
		$min_order_type = $rproduct->min_order_type;
		$product_size = $rproduct->product_size;

		$this->db->where('idtransaction_detail', $idtransaction_detail);
		$this->db->where('status > ', 0);
		$td_main = $this->db->get('transaction_detail');
		$qty_group = azobj($td_main->row(), 'qty_group');
		$length = azobj($td_main->row(), 'length');
		$width = azobj($td_main->row(), 'width');
		$is_two_side = azobj($td_main->row(), 'is_two_side');
		$product_calendar_content = azobj($td_main->row(), 'product_calender_content');
		$material_length = azobj($td_main->row(), 'material_length');
		$material_width = azobj($td_main->row(), 'material_width');

		$this->db->where('idtransaction_detail', $idtransaction_detail);
		$this->db->where('status >', 0);
		$finishing_list = $this->db->get('transaction_finishing')->result_array();
		$idproduct_finishing = array_column($finishing_list, 'idproduct_finishing');

		$arr_calculate = array(
			'idproduct' => $idproduct,
			'length' => $length,
			'width' => $width,
			'is_two_side' => $is_two_side,
			'qty' => $qty * $qty_group,
			'idproduct_finishing' => $idproduct_finishing,
			'product_calendar_content' => $product_calendar_content
		);

		if($product_size == 'MENGIKUTI LEBAR BAHAN') {
			if(strlen($material_length) > 0 && $material_length != 0) {
				$arr_calculate['material_length'] = $material_length;
			}
			if(strlen($material_width) > 0 && $material_width != 0) {
				$arr_calculate['material_width'] = $material_width;
			}
		}

		$the_product = $this->lite->calculate_product($arr_calculate);

		return $the_product;
	}
	
	function save_product_finishing($data) {
		$id = azarr($data, 'idtransaction_detail');
		$idproduct = azarr($data, 'idproduct');

		// nego
		// if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
		// 	$price_bid = azarr($data, 'price_bid');

		// 	if (strlen($price_bid) > 0) {
		// 		$qty_total = azarr($data, 'qty');
		// 	} else {
		// 		$qty_total = azarr($data, 'qty_total');
		// 	}
		// } else {
			$price_bid = null;
			$qty_total = azarr($data, 'qty_total');
		// }

		$qty_subtotal = azarr($data, 'qty_subtotal');
		$work_duration = azarr($data, 'work_duration');
		$work_duration_type = azarr($data, 'work_duration_type');
		$price_original = azarr($data, 'product_price');
		$price = azarr($data, 'product_price');

		if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
			if (strlen($price_bid) > 0) {
				$price = $price_bid;
			}
		}

		$qty = azarr($data, 'qty');
		$subtotal_price = azarr($data, 'subtotal_price');
		$total_price = azarr($data, 'total_price');
		$finishing_list = azarr($data, 'finishing_list', array());

		$sub_price = floatval($price_original) * floatval($qty_subtotal);
		$grand_price = floatval($price) * floatval($qty_total);

		$this->db->where('idproduct', $idproduct);
		$this->db->where('status > ', 0);
		$product = $this->db->get('product');

		$weight = azobj($product->row(), 'product_weight', 1) * $qty;
		$arr_transaction_detail = array(
			'weight' => $weight,
			'price' => $price_original,
			'sub_price' => $sub_price,
			'work_duration' => $work_duration,
			'work_duration_type' => $work_duration_type,
			'qty' => $qty,
			'qty_subtotal' => $qty_subtotal,
			'qty_total' => $qty_total,
			'grand_price' => $grand_price,
		);

		if (az_get_config('is_voucher', 'config_app')) {
			$discount_name = azarr($data, 'discount_name');
			$discount_total = az_remove_separator(azarr($data, 'discount_total', 0));
			$before_discount = 0;

			if (strlen($discount_name) > 0) {
				$before_discount = az_crud_number($total_price);
				$total_price = azarr($data, 'after_discount', 0);
			}

			$arr_transaction_detail['discount_name'] = $discount_name;
			$arr_transaction_detail['discount_total'] = $discount_total;
			$arr_transaction_detail['before_discount'] = $before_discount;
		}
		if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
			$arr_transaction_detail['price_bid'] = $price_bid;
		}

		$res = az_crud_save($id, 'transaction_detail', $arr_transaction_detail);
		$err_code = azarr($res, 'err_code');
		$err_message = azarr($res, 'err_message');

		$grand_finishing_price = 0;
		$grand_finishing_sub_price = 0;

		if ($err_code == 0) {
			foreach ((array) $finishing_list as $key => $value) {
				if ($err_code == 0) {
					$finishing_price = azarr($value, 'price');
					$finishing_weight = azarr($value, 'weight');
					$finishing_name = azarr($value, 'name');
					$idfinishing_list = azarr($value, 'id');
					$finishing_sub_price = $finishing_price * $qty_subtotal;
					$finishing_total_price = $finishing_price * $qty_total;
		
					$grand_finishing_price += floatval($finishing_sub_price);
					$grand_finishing_sub_price += $finishing_total_price;
		
					$arr_finishing = array(
						'weight' => $finishing_weight * $qty,
						'price' => $finishing_price,
						'sub_price' => $finishing_sub_price,
						'total_price' => $finishing_total_price
					);
	
					$res = az_crud_update('', 'transaction_finishing', $arr_finishing, "idtransaction_detail = '$id' and idproduct_finishing = '$idfinishing_list'");
					$err_code = azarr($res, 'err_code');
					$err_message = azarr($res, 'err_message');
				}
			}
		}

		if ($err_code == 0) {			
			//update grand finishing
			if (strlen($price_bid) > 0) {
				$grand_product_finishing_sub_price = $price_bid * $qty;
			} else {
				$grand_product_finishing_sub_price = $grand_price + $grand_finishing_sub_price;
			}
	
			$grand_product_finishing_price = $sub_price + $grand_finishing_price;

			$arr_new_finishing = array(
				'grand_finishing_price' => $grand_finishing_price,
				'grand_finishing_sub_price' => $grand_finishing_sub_price,
				'grand_product_finishing_price' => $sub_price + $grand_finishing_price,
				'grand_product_finishing_sub_price' => $grand_product_finishing_sub_price
				// 'grand_product_finishing_sub_price' => az_crud_number($total_price)
			);
	
			// if ($this->app_accounting || $this->app_accounting_old) {
			// 	if (strlen($idacc_tax) > 0) {
			// 		$this->db->where('idacc_tax', $idacc_tax);
			// 		$acc_tax = $this->db->get('acc_tax');
	
			// 		$tax_percentase = $acc_tax->row()->tax_percentase;
	
			// 		$arr_new_finishing['price_tax'] = ( $grand_product_finishing_sub_price * $tax_percentase ) / 100;
	
			// 		$total_tax = $arr_new_finishing['price_tax'];
			// 	}
	
			// }

			$res = az_crud_save($id, 'transaction_detail', $arr_new_finishing);
			$err_code = azarr($res, 'err_code');
			$err_message = azarr($res, 'err_message');
		}

		$response = array(
			"err_code" => $err_code,
			"err_message" => $err_message,
		);

		return $response;
	}
}
