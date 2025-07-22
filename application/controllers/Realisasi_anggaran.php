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

		$crud->set_column(array('#', 'Tanggal Realisasi', 'Nomor Invoice', 'Paket Belanja', 'Total Realisasi', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		// $date1->set_value('01-'.Date('m-Y'));
		$date1->set_value('01-01-'.Date('Y'));
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

        $crud->set_select('transaction.idtransaction, date_format(transaction_date, "%d-%m-%Y %H:%i:%s") as txt_transaction_date, transaction_code, paket_belanja.nama_paket_belanja, total_realisasi, user.name as user_created');
        $crud->set_select_table('idtransaction, txt_transaction_date, transaction_code, nama_paket_belanja, total_realisasi, user_created');
        $crud->set_sorting('transaction_date, transaction_code, nama_paket_belanja, total_realisasi');
        $crud->set_filter('transaction_date, transaction_code, nama_paket_belanja, total_realisasi');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , right');

        $crud->add_join_manual('user', 'transaction.iduser_created = user.iduser');
        $crud->add_join_manual('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
        $crud->add_join_manual('paket_belanja', 'transaction_detail.idpaket_belanja = paket_belanja.idpaket_belanja');
		// $crud->set_group_by('transaction.idtransaction');
		$crud->set_group_by('transaction.idtransaction, transaction.transaction_date, transaction_code, paket_belanja.nama_paket_belanja, total_realisasi, user.name');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(transaction.transaction_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(transaction.transaction_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($transaction_code) > 0) {
			$crud->add_where('transaction.transaction_code = "' . $transaction_code . '"');
		}

		$crud->add_where("transaction.status = 1");
		$crud->add_where("transaction_detail.status = 1");
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
		$validate_description = false;
		$validate_room = false;
		$validate_name_training = false;


	 	$idtransaction = $this->input->post('idtransaction');
	 	$idtransaction_detail = $this->input->post('idtransaction_detail');
		$idpaket_belanja = $this->input->post('idpaket_belanja');
	 	$penyedia = $this->input->post('penyedia');
	 	$iduraian = $this->input->post('iduraian');
		$volume = az_crud_number($this->input->post('volume'));	
		$laki = az_crud_number($this->input->post('laki'));
		$perempuan = az_crud_number($this->input->post('perempuan'));
		$harga_satuan = az_crud_number($this->input->post('harga_satuan'));
		$ppn = az_crud_number($this->input->post('ppn'));
		$pph = az_crud_number($this->input->post('pph'));
        $transaction_description = $this->input->post('transaction_description');
        $transaction_date = az_crud_date($this->input->post('transaction_date'));

		$total = (floatval($volume) * floatval($harga_satuan)) + floatval($ppn) - floatval($pph);


		$this->load->library('form_validation');
		$this->form_validation->set_rules('idpaket_belanja', 'Paket Belanja', 'required');
		$this->form_validation->set_rules('iduraian', 'Uraian', 'required');
		$this->form_validation->set_rules('volume', 'Volume', 'required');
		$this->form_validation->set_rules('volume', 'Volume', 'required');


		$this->form_validation->set_rules('idpaket_belanja', 'Paket Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('penyedia', 'Penyedia', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('iduraian', 'Uraian', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('volume', 'Volume', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim|max_length[200]');

		// validasi apakah inputan laki-laki dan perempuan wajib diisi
		if (strlen($iduraian) > 0) {
			$this->db->where('status', 1);
			$this->db->where('idsub_kategori', $iduraian);
			$this->db->select('is_gender, is_description, is_room, is_name_training');
			$sub_kategori = $this->db->get('sub_kategori');

			if ($sub_kategori->num_rows() > 0) {
				// 0 : tidak wajib isi jenis kelamin
				// 1 : wajib isi jenis kelamin
				if ($sub_kategori->row()->is_gender == 1) {
					$validate_gender = true;
				}

				// 0 : tidak wajib isi keterangan
				// 1 : wajib isi keterangan
				if ($sub_kategori->row()->is_description == 1) {
					$validate_description = true;
				}

				// 0 : tidak wajib isi ruang
				// 1 : wajib isi ruang
				if ($sub_kategori->row()->is_room == 1) {
					$validate_room = true;
				}

				// 0 : tidak wajib isi nama diklat
				// 1 : wajib isi nama diklat
				if ($sub_kategori->row()->is_name_training == 1) {
					$validate_name_training = true;
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
		if ($validate_description) {
		}
		if ($validate_room) {
		}
		if ($validate_name_training) {
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

		// validasi tanggal realisasi tidak boleh melebihi tanggal hari ini
		if ($err_code == 0) {
			if (strtotime($transaction_date) > strtotime(date('Y-m-d H:i:s'))) {
				$err_code++;
				$err_message = "Tanggal realisasi tidak boleh melebihi tanggal hari ini.";
			}
		}

		// validasi
		if ($err_code == 0) {
			$the_filter = array(
				'iduraian' => $iduraian,
				'idpaket_belanja' => $idpaket_belanja,
				'transaction_date' => $transaction_date,
			);

			// ambil data DPA
			$data_utama = $this->get_data_utama($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			// ambil data Rencana Anggaran Kegiatan (RAK) sampai bulan yang dipilih
			$data_rak = $this->get_data_rak($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			// ambil data uraian belanja yang sudah direalisasikan
			$data_realisasi = $this->get_data_realisasi($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;
			

			// jika dicentang maka pengecekannya inputannya langsung dibandingkan dengan total keseluruhan volume dan total anggaran
			if (!aznav('role_bypass')) {

				// validasi apakah volume inputan + volume yang sudah direalisasikan melebihi volume RAK
				if ( (floatval($volume) + floatval($data_realisasi->row()->total_volume)) > floatval($data_rak->row()->total_rak_volume) ) {
					$err_code++;
					$err_message = "Volume yang diinputkan melebihi volume RAK pada bulan yang dipilih.";
				}
				// var_dump('('.floatval($volume).' + '.floatval($data_realisasi->row()->total_volume).') > '.floatval($data_rak->row()->total_rak_volume)); echo "<br><br>";
				

				// validasi apakah jumlah inputan + jumlah yang sudah direalisasikan melebihi jumlah RAK
				if ( (floatval($total) + floatval($data_realisasi->row()->total_realisasi)) > floatval($data_rak->row()->total_rak_jumlah) ) {
					$err_code++;
					$err_message = "Total yang diinputkan melebihi jumlah RAK pada bulan yang dipilih.";
				}
				// var_dump( '('.floatval($total).' + '.floatval($data_realisasi->row()->total_realisasi).') > '.floatval($data_rak->row()->total_rak_jumlah) ); echo "<br><br>";

			}

		
			if ($err_code == 0) {
				// validasi apakah volume yang sudah direalisasikan melebihi volume yang sudah ditentukan
				if ($data_utama->row()->volume < (floatval($data_realisasi->row()->total_volume) + floatval($volume))) {
					$err_code++;
					$err_message = "Volume yang direalisasikan melebihi volume dari DPA.";
				}
				// var_dump($data_utama->row()->volume.' < ('.floatval($data_realisasi->row()->total_volume).' + '.floatval($volume).')'); echo "<br><br>";


				// validasi apakah jumlah yang sudah direalisasikan melebihi jumlah yang sudah ditentukan
				if ($data_utama->row()->jumlah < (floatval($data_realisasi->row()->total_realisasi) + floatval($total))) {
					$err_code++;
					$err_message = "Jumlah yang direalisasikan melebihi jumlah dari DPA.";
				}
				// var_dump($data_utama->row()->jumlah.' < ('.floatval($data_realisasi->row()->total_realisasi).' + '.floatval($total).')'); echo "<br><br>";

			}
		}
		// var_dump($err_message);die;

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
            
			if ($err_code == 0) {
				//transaction detail
				$arr_transaction_detail = array(
					'idtransaction' => $idtransaction,
					'idpaket_belanja' => $idpaket_belanja,
					'penyedia' => $penyedia,
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
		$this->db->select('transaction_detail.idpaket_belanja, transaction_detail.penyedia, transaction_detail.iduraian, sub_kategori.nama_sub_kategori, transaction_detail.volume, transaction_detail.harga_satuan, transaction_detail.ppn, transaction_detail.pph, transaction_detail.total, transaction_detail.transaction_description, transaction_detail.laki, transaction_detail.perempuan');
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
		$this->db->order_by('transaction_date', 'desc');
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

	function get_data_utama($the_data) {
		$iduraian = azarr($the_data, 'iduraian', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$add_select = azarr($the_data, 'add_select', '');

		// menampilkan data utama dari paket belanja
		$this->db->where('pb.idpaket_belanja = "'.$idpaket_belanja.'" ');
		$this->db->where('(pbds_child.idsub_kategori = "'.$iduraian.'" OR pbds_parent.idsub_kategori = "'.$iduraian.'")');
		$this->db->join('paket_belanja_detail pbd', 'paket_belanja_detail pbd ON pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');

		if (strlen($add_select) > 0) {
			$this->db->group_by('pb.idpaket_belanja, pb.nama_paket_belanja, pbd.idpaket_belanja_detail, detail_sub_id, idsub_kategori, volume, idsatuan, harga_satuan, jumlah');
		}

		$this->db->select('pb.idpaket_belanja,
			pb.nama_paket_belanja,
			pbd.idpaket_belanja_detail,
			COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS detail_sub_id,
			COALESCE(pbds_child.idsub_kategori, pbds_parent.idsub_kategori) AS idsub_kategori,
			COALESCE(pbds_child.volume, pbds_parent.volume) AS volume,
			COALESCE(pbds_child.idsatuan, pbds_parent.idsatuan) AS idsatuan,
			COALESCE(pbds_child.harga_satuan, pbds_parent.harga_satuan) AS harga_satuan,
			COALESCE(pbds_child.jumlah, pbds_parent.jumlah) AS jumlah'.$add_select);
		$pb = $this->db->get('paket_belanja pb');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $pb;
	}

	function get_data_rak($the_data) {
		$iduraian = azarr($the_data, 'iduraian', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$transaction_date = azarr($the_data, 'transaction_date', '');

		$format_date = date("n", strtotime($transaction_date));

		$add_query_volume = '';
		$add_query_jumlah = '';
		for ($i = 0; $i < $format_date; $i++) { 
			if ($i == 0) {
				// untuk bulan Januari
				$add_query_volume .= 'COALESCE(pbds_child.rak_volume_januari, pbds_parent.rak_volume_januari, 0)';
				$add_query_jumlah .= 'COALESCE(pbds_child.rak_jumlah_januari, pbds_parent.rak_jumlah_januari, 0)';
			}
			else if ($i == 1) {
				// untuk bulan Februari
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_februari, pbds_parent.rak_volume_februari, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_februari, pbds_parent.rak_jumlah_februari, 0)';
			}
			else if ($i == 2) {
				// untuk bulan Maret
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_maret, pbds_parent.rak_volume_maret, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_maret, pbds_parent.rak_jumlah_maret, 0)';
			}
			else if ($i == 3) {
				// untuk bulan April
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_april, pbds_parent.rak_volume_april, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_april, pbds_parent.rak_jumlah_april, 0)';
			}
			else if ($i == 4) {
				// untuk bulan Mei
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_mei, pbds_parent.rak_volume_mei, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_mei, pbds_parent.rak_jumlah_mei, 0)';
			}
			else if ($i == 5) {
				// untuk bulan Juni
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_juni, pbds_parent.rak_volume_juni, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_juni, pbds_parent.rak_jumlah_juni, 0)';
			}
			else if ($i == 6) {
				// untuk bulan Juli
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_juli, pbds_parent.rak_volume_juli, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_juli, pbds_parent.rak_jumlah_juli, 0)';
			}
			else if ($i == 7) {
				// untuk bulan Agustus
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_agustus, pbds_parent.rak_volume_agustus, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_agustus, pbds_parent.rak_jumlah_agustus, 0)';
			}
			else if ($i == 8) {
				// untuk bulan September
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_september, pbds_parent.rak_volume_september, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_september, pbds_parent.rak_jumlah_september, 0)';
			}
			else if ($i == 9) {
				// untuk bulan Oktober
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_oktober, pbds_parent.rak_volume_oktober, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_oktober, pbds_parent.rak_jumlah_oktober, 0)';
			}
			else if ($i == 10) {
				// untuk bulan November
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_november, pbds_parent.rak_volume_november, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_november, pbds_parent.rak_jumlah_november, 0)';
			}
			else if ($i == 11) {
				// untuk bulan Desember
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_desember, pbds_parent.rak_volume_desember, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_desember, pbds_parent.rak_jumlah_desember, 0)';
			}
		}

		$add_query_volume = rtrim($add_query_volume, ', '); // Hilangkan koma dan spasi di akhir
		$add_query_jumlah = rtrim($add_query_jumlah, ', '); // Hilangkan koma dan spasi di akhir
		
		$add_query_volume = 'SUM(' . rtrim($add_query_volume, ', ') . ') AS total_rak_volume';
		$add_query_jumlah = 'SUM(' . rtrim($add_query_jumlah, ', ') . ') AS total_rak_jumlah';

		// var_dump($add_query_volume); echo "<br><br>";
		// var_dump($add_query_jumlah);
		// die;

		$the_filter = array(
			'iduraian' => $iduraian,
			'idpaket_belanja' => $idpaket_belanja,
			'transaction_date' => $transaction_date,
			'add_select' => ', ' .$add_query_volume . ', ' . $add_query_jumlah, // digunakan untuk menyisipkan query tambahan pada select di fungsi get_data_utama
		);

		// menampilkan data Rencana Anggaran Kegiatan (RAK) sampai dengan tanggal inputan
		$db = $this->get_data_utama($the_filter);
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $db;
	}

	function get_data_realisasi($the_data) {
		$iduraian = azarr($the_data, 'iduraian', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$transaction_date = azarr($the_data, 'transaction_date', '');

		$format_year = date("Y", strtotime($transaction_date));
		$format_month = date("m", strtotime($transaction_date));

		// menampilkan data realisasi yang sudah ada sampai dengan tanggal inputan
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('transaction_detail.iduraian', $iduraian);
		$this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") >=', $format_year . '-01');
		$this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") <=', $format_year . '-' . $format_month);
		$this->db->where('transaction.transaction_status !=', 'DRAFT');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->select('count(transaction.idtransaction), sum(transaction_detail.volume) as total_volume, sum(transaction_detail.laki) as total_laki, sum(transaction_detail.perempuan) as total_perempuan, sum(transaction_detail.harga_satuan) as total_harga_satuan, sum(transaction_detail.ppn) as total_ppn, sum(transaction_detail.pph) as total_pph, sum(transaction_detail.total) as total_realisasi');
		$trx = $this->db->get('transaction');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $trx;
	}

    private function generate_transaction_code() {
		$this->db->where('day(transaction_date)', Date('d'));
		$this->db->where('month(transaction_date)', Date('m'));
		$this->db->where('year(transaction_date)', Date('Y'));
		$this->db->where('transaction_code IS NOT NULL ');
		$this->db->order_by('transaction_code desc');
		$data = $this->db->get('transaction', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

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
}
