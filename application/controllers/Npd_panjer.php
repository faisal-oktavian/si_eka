<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Npd_panjer extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('npd_panjer');
        $this->table = 'npd_panjer';
        $this->controller = 'npd_panjer';
        $this->load->helper('az_crud');
		$this->load->helper('az_config');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('role_name');

		$crud->set_column(array('#', 'Tanggal NPD', 'Nomor NPD', 'Paket Belanja', 'Bidang', 'Kegiatan', 'Total', 'Status', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		if (aznav('role_view_npd_panjer') && strlen($idrole) > 0) {
			$crud->set_btn_add(false);
		}

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		$date1->set_value('01-'.Date('m-Y'));
		// $date1->set_value('01-01-'.Date('Y'));
		$data['date1'] = $date1->render();

		$date2 = $azapp->add_datetime();
		$date2->set_id('date2');
		$date2->set_name('date2');
		$date2->set_format('DD-MM-YYYY');
		$date2->set_value(Date('t-m-Y'));
		$data['date2'] = $date2->render();

		$crud->add_aodata('date1', 'date1');
		$crud->add_aodata('date2', 'date2');
		$crud->add_aodata('npd_panjer_code', 'npd_panjer_code');
		// $crud->add_aodata('vf_npd_status', 'vf_npd_status');

		$vf = $this->load->view('npd_panjer/vf_npd_panjer', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'npd_panjer';
		$view = $this->load->view('npd_panjer/v_format_npd_panjer', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('npd_panjer/vjs_npd_panjer');
		$azapp->add_js($js);

		$data_header['title'] = 'NPD Panjar';
		$data_header['breadcrumb'] = array('npd_panjer');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$npd_panjer_code = $this->input->get('npd_panjer_code');
		// $npd_panjer_status = $this->input->get('vf_npd_panjer_status');

        $crud->set_select('npd_panjer.idnpd_panjer, date_format(npd_panjer_date, "%d-%m-%Y %H:%i:%s") as txt_npd_panjer_date, npd_panjer_number, paket_belanja.nama_paket_belanja,field_activity, activity, total_realisasi, npd_panjer_status, user.name as user_created');
        $crud->set_select_table('idnpd_panjer, txt_npd_panjer_date, npd_panjer_number, nama_paket_belanja,field_activity, activity, total_realisasi, npd_panjer_status, user_created');
        $crud->set_sorting('npd_panjer_date, npd_panjer_number, nama_paket_belanja,field_activity, activity, total_realisasi, npd_panjer_status');
        $crud->set_filter('npd_panjer_date, npd_panjer_number, nama_paket_belanja,field_activity, activity, total_realisasi, npd_panjer_status');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , , , right, center');

        $crud->add_join_manual('user', 'npd_panjer.iduser_created = user.iduser');
        $crud->add_join_manual('npd_panjer_detail', 'npd_panjer.idnpd_panjer = npd_panjer_detail.idnpd_panjer');
        $crud->add_join_manual('paket_belanja', 'npd_panjer_detail.idpaket_belanja = paket_belanja.idpaket_belanja');
		// $crud->set_group_by('transaction.idtransaction');
		$crud->set_group_by('npd_panjer.idnpd_panjer, npd_panjer.npd_panjer_date, npd_panjer_number, paket_belanja.nama_paket_belanja, total_realisasi, user.name');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(npd_panjer.npd_panjer_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(npd_panjer.npd_panjer_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($npd_panjer_code) > 0) {
			$crud->add_where('npd_panjer.npd_panjer_number = "' . $npd_panjer_code . '"');
		}
		// if (strlen($npd_panjer_status) > 0) {
		// 	$crud->add_where('npd_panjer.npd_panjer_status = "' . $npd_panjer_status . '"');
		// }

		$crud->add_where("npd_panjer.status = 1");
		$crud->add_where("npd_panjer_detail.status = 1");
		$crud->add_where("npd_panjer.npd_panjer_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('npd_panjer_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('role_name');
		
		if ($key == 'total_realisasi') {
			$total_realisasi = az_thousand_separator($value);

			return $total_realisasi;
		}

		if ($key == 'npd_panjer_status') {
			// OK 	-> Data valid;
			// DRAFT -> Data belum valid;

			$lbl = 'default';
			$tlbl = '-';
			if ($value == "OK") {
				$lbl = 'warning';
				$tlbl = 'Input Data';
			}

			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
            $idnpd_panjer = azarr($data, 'idnpd_panjer');
			$is_view_only = false;

            $btn = '<button class="btn btn-default btn-xs btn-edit-npd_panjer" data_id="'.$idnpd_panjer.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-realisasi-anggaran" data_id="'.$idnpd_panjer.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

            // $this->db->where('idnpd_panjer', $idnpd_panjer);
            // $panjer = $this->db->get('npd_panjer');

            // $panjer_status = $panjer->row()->npd_panjer_status;
            // // if (in_array($trx_status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
            // if (in_array($panjer_status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
			// 	$is_view_only = true;
            // }

			if (aznav('role_view_npd_panjer') && strlen($idrole) > 0) {
				$is_view_only = true;
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-npd-panjer" data_id="'.$idnpd_panjer.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
			}

			$this->db->where('npd_panjer.idnpd_panjer', $idnpd_panjer);
			$this->db->where('npd_panjer.status', 1);
			$this->db->where('npd_panjer_detail.status', 1);
			
			$this->db->join('npd_panjer_detail', 'npd_panjer_detail.idnpd_panjer = npd_panjer.idnpd_panjer');
			
			$this->db->group_by('npd_panjer_detail.idpaket_belanja');
			$this->db->select('npd_panjer_detail.idpaket_belanja');
			$npd_panjer = $this->db->get('npd_panjer');

			$idpaket_belanja = $npd_panjer->row()->idpaket_belanja;

			$btn .= '<a href="' . app_url() . 'npd_panjer/print_npd_panjer?idn=' . $idnpd_panjer . '&idp=' . $idpaket_belanja . '" class="btn btn-xs btn-default btn-print" target="_blank"><i class="fa fa-print"></i> Cetak</a> ';

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
        
		$view = $this->load->view('npd_panjer/v_npd_panjer', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('npd_panjer/v_npd_panjer_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add');
		$modal->set_modal_title('Tambah NPD Panjer');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('npd_panjer/vjs_npd_panjer_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'NPD Panjar';
		$data_header['breadcrumb'] = array('npd_panjer');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function edit($id) {
		$this->db->where('idnpd_panjer', $id);
		$check = $this->db->get('npd_panjer');
		if ($check->num_rows() == 0) {
			redirect(app_url().'npd_panjer');
		} 
		// else if($this->uri->segment(4) != "view_only") {
		// 	$status = $check->row()->npd_panjer_status;
		// 	if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 		redirect(app_url().'npd_panjer');
		// 	}
		// }
		$this->add($id);
	}

    function search_paket_belanja() {
		$keyword = $this->input->get("term");

		
		// data yang ditampilkan adalah data pada tahun berjalan
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan = "'.Date('Y').'" ');
		$this->db->where('paket_belanja.status', 1);
		
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		
		$this->db->order_by("paket_belanja.nama_paket_belanja");
		$this->db->like('paket_belanja.nama_paket_belanja', $keyword);
		$this->db->select("paket_belanja.idpaket_belanja as id, paket_belanja.nama_paket_belanja as text");

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
		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
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

					// get nama kategori
					$this->db->where('idpaket_belanja_detail_sub', $dss_value->is_idpaket_belanja_detail_sub);
					$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori');
					$this->db->select('nama_kategori');
					$pbds = $this->db->get('paket_belanja_detail_sub');

					$nama_kategori = "";
					if ($pbds->num_rows() > 0) {
						$nama_kategori = '[Kategori: '.$pbds->row()->nama_kategori.'] ';
					}

					$arr_data[] = array(
						'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
						'iduraian' => $dss_value->idsub_kategori,
						'nama_uraian' => $nama_kategori.$dss_value->nama_sub_kategori,
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


	 	$idnpd_panjer = $this->input->post('idnpd_panjer');
	 	$idnpd_panjer_detail = $this->input->post('idnpd_panjer_detail');
		$idpaket_belanja = $this->input->post('idpaket_belanja');
	 	$penyedia = $this->input->post('penyedia');
	 	$iduraian = $this->input->post('iduraian');
	 	$idruang = $this->input->post('idruang');
	 	$name_training = $this->input->post('name_training');
		$volume = az_crud_number($this->input->post('volume'));	
		$laki = az_crud_number($this->input->post('laki'));
		$perempuan = az_crud_number($this->input->post('perempuan'));
		$harga_satuan = az_crud_number($this->input->post('harga_satuan'));
		$ppn = az_crud_number($this->input->post('ppn'));
		$pph = az_crud_number($this->input->post('pph'));
		$remains_budget = az_crud_number($this->input->post('remains_budget'));
        $npd_detail_description = $this->input->post('npd_detail_description');
        $npd_panjer_date = az_crud_date($this->input->post('npd_panjer_date'));

		$total = (floatval($volume) * floatval($harga_satuan)) + floatval($ppn) - floatval($pph);


		$this->load->library('form_validation');
		$this->form_validation->set_rules('idpaket_belanja', 'Paket Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('penyedia', 'Penyedia', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('iduraian', 'Uraian', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('volume', 'Volume', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('remains_budget', 'Sisa Anggaran', 'required|trim|max_length[200]');

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
			$this->form_validation->set_rules('npd_detail_description', 'Keterangan', 'required');
		}
		if ($validate_room) {
			$this->form_validation->set_rules('idruang', 'Nama Ruang', 'required|trim|max_length[200]');
		}
		if ($validate_name_training) {
			$this->form_validation->set_rules('name_training', 'Nama Diklat', 'required|trim|max_length[200]');
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

		// validasi tanggal NPD Panjer tidak boleh melebihi tanggal hari ini
		if ($err_code == 0) {
			if (strtotime($npd_panjer_date) > strtotime(date('Y-m-d H:i:s'))) {
				$err_code++;
				$err_message = "Tanggal NPD Panjer tidak boleh melebihi tanggal hari ini.";
			}
		}

		// validasi
		if ($err_code == 0) {
			$the_filter = array(
				'iduraian' => $iduraian,
				'idpaket_belanja' => $idpaket_belanja,
				'npd_panjer_date' => $npd_panjer_date,
				'idnpd_panjer_detail' => $idnpd_panjer_detail,
			);
			// var_dump($the_filter);die;

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
			// if (!aznav('role_bypass')) {

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

			// }

		
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
					$err_message = "Total Biaya yang direalisasikan melebihi jumlah dari DPA.";
				}
				// var_dump($data_utama->row()->jumlah.' < ('.floatval($data_realisasi->row()->total_realisasi).' + '.floatval($total).')'); echo "<br><br>";

			}
		}
		// var_dump($err_message);die;

		// if ($err_code == 0) {
		// 	$this->db->where('idtransaction',$idtransaction);
		// 	$transaction = $this->db->get('transaction');

		// 	if ($transaction->num_rows() > 0) {
		// 		$status = $transaction->row()->transaction_status;
		// 		// if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 		if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 			$err_code++;
		// 			$err_message = "Data tidak bisa diedit atau dihapus.";
		// 		}
		// 	}	
		// }

		if ($err_code == 0) {

			if (strlen($idnpd_panjer) == 0) {
				$arr_npd_panjer = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'npd_panjer_date' => Date('Y-m-d H:i:s'),
					'npd_panjer_status' => 'DRAFT',
					'npd_panjer_code' => $this->generate_transaction_code(),
				);

				$save_npd_panjer = az_crud_save($idnpd_panjer, 'npd_panjer', $arr_npd_panjer);
				$idnpd_panjer = azarr($save_npd_panjer, 'insert_id');
			}
			else {
				// validasi apakah data paket belanja yang disimpan sama?
				// jika tidak maka data tidak perlu disimpan

				$this->db->where('status', 1);
				$this->db->where('idnpd_panjer', $idnpd_panjer);
				$this->db->where('idpaket_belanja', $idpaket_belanja);
				$trxd = $this->db->get('npd_panjer_detail');

				if ($trxd->num_rows() == 0) {
					$err_code++;
					$err_message = "Paket Belanja yang anda inputkan berbeda dengan paket belanja yang telah diinputkan sebelumnya. <br>";
					$err_message .= "Silahkan menginputkan data dengan paket belanja yang sama.";
				}
			}
            
			if ($err_code == 0) {
				//npd_panjer detail
				$arr_npd_panjer_detail = array(
					'idnpd_panjer' => $idnpd_panjer,
					'idpaket_belanja' => $idpaket_belanja,
					'penyedia' => $penyedia,
					'iduraian' => $iduraian,
					'idruang' => $idruang,
					'name_training' => $name_training,
					'volume' => $volume,
					'laki' => $laki,
					'perempuan' => $perempuan,
					'harga_satuan' => $harga_satuan,
					'ppn' => $ppn,
					'pph' => $pph,
					'total' => $total,
					'remains_budget' => $remains_budget,
					'npd_detail_description' => $npd_detail_description,
				);
				
				$td = az_crud_save($idnpd_panjer_detail, 'npd_panjer_detail', $arr_npd_panjer_detail);
				$idnpd_panjer_detail = azarr($td, 'insert_id');

				// hitung total transaksi
				$this->calculate_total_realisasi($idnpd_panjer);	
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idnpd_panjer' => $idnpd_panjer,
			'idnpd_panjer_detail' => $idnpd_panjer_detail,
		);
		echo json_encode($return);
	}

	function save_realisasi() {
		$err_code = 0;
		$err_message = '';

		
		$idnpd_panjer = $this->input->post("hd_idnpd_panjer");
		$npd_panjer_date = az_crud_date($this->input->post("npd_panjer_date"));
		$iduser_created = $this->input->post("iduser_created");
		$npd_panjer_number = $this->input->post("npd_panjer_number");
		$field_activity = $this->input->post("field_activity");
		$activity = $this->input->post("activity");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('npd_panjer_date', 'Tanggal NPD Panjer', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('npd_panjer_number', 'Nomor NPD Panjer', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('field_activity', 'Bidang', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('activity', 'Kegiatan', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idnpd_panjer) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		// if ($err_code == 0) {
		// 	$this->db->where('idtransaction',$idtransaction);
		// 	$transaction = $this->db->get('transaction');

		// 	if ($transaction->num_rows() > 0) {
		// 		$status = $transaction->row()->transaction_status;
		// 		// if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 		if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 			$err_code++;
		// 			$err_message = "Data tidak bisa diedit atau dihapus.";
		// 		}
		// 	}	
		// }

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'npd_panjer_date' => $npd_panjer_date,
	    		'npd_panjer_number' => $npd_panjer_number,
	    		'field_activity' => $field_activity,
	    		'activity' => $activity,
	    		'npd_panjer_status' => "OK",
	    		'iduser_created' => $iduser_created,
	    	);

	    	az_crud_save($idnpd_panjer, 'npd_panjer', $arr_data);

			// hitung total transaksi
			$this->calculate_total_realisasi($idnpd_panjer);
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

		// $this->db->where('idtransaction',$id);
		// $transaction = $this->db->get('transaction');

		// if ($transaction->num_rows() > 0) {
		// 	$status = $transaction->row()->transaction_status;
		// 	// if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 	if (in_array($status, array('SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 		$err_code++;
		// 		$err_message = "Data tidak bisa diedit atau dihapus.";
		// 	}
		// }

		// // cek apakah sudah ada data di tabel verification & detail
		// if ($err_code == 0) {
		// 	$this->db->where('verification_detail.idtransaction', $id);
		// 	$verif_detail = $this->db->get('verification_detail');

		// 	if ($verif_detail->num_rows() > 0) {
		// 		$idverification = $verif_detail->row()->idverification;
		// 		az_crud_delete('verification', $idverification);
		// 	}
		// }

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
		
		$this->db->where('idnpd_panjer_detail', $id);
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = npd_panjer_detail.iduraian');
		$this->db->select('npd_panjer_detail.idpaket_belanja, npd_panjer_detail.penyedia, npd_panjer_detail.iduraian, sub_kategori.nama_sub_kategori, npd_panjer_detail.volume, npd_panjer_detail.harga_satuan, npd_panjer_detail.ppn, npd_panjer_detail.pph, npd_panjer_detail.total, npd_panjer_detail.npd_detail_description, npd_panjer_detail.laki, npd_panjer_detail.perempuan, npd_panjer_detail.idruang, npd_panjer_detail.name_training, npd_panjer_detail.remains_budget');
		$trxd = $this->db->get('npd_panjer_detail')->result_array();

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
		$message = '';

		$this->db->where('idnpd_panjer_detail',$id);
		$this->db->join('npd_panjer', 'npd_panjer_detail.idnpd_panjer = npd_panjer.idnpd_panjer');
		$npd_panjer = $this->db->get('npd_panjer_detail');

		// $status = $npd_panjer->row()->npd_panjer_status;
		$idnpd_panjer = $npd_panjer->row()->idnpd_panjer;
		// // if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// if (in_array($status, array('SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 	$is_delete = false;
		// }

		if ($is_delete) {
			$delete = az_crud_delete('npd_panjer_detail', $id, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

		// 	if ($err_code == 0) {
		// 		// hitung total transaksi
		// 		$this->calculate_total_realisasi($idnpd_panjer);
		// 	}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada paket belanja/detail transaksi di realisasi anggaran ini?
		if ($err_code == 0) {
			$this->db->where('idnpd_panjer', $idnpd_panjer);
			$this->db->where('status', 1);
			$npd_panjer_detail = $this->db->get('npd_panjer_detail');

			if ($npd_panjer_detail->num_rows() == 0) {
				$arr_update = array(
					'npd_panjer_status' => 'DRAFT',
				);
				az_crud_save($idnpd_panjer, 'npd_panjer', $arr_update);

				$message = 'Paket Belanja berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan paket belanja baru, harap klik simpan transaksi realisasi anggaran, agar datanya tidak hilang.</span>';
			}
			else {
				$message = 'Paket Belanja berhasil dihapus.';
			}
		}	

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
		);

		echo json_encode($return);
	}

	public function print_npd_panjer()
	{	
		$idnpd_panjer = $this->input->get('idn');
		$idpaket_belanja = $this->input->get('idp');

		$err_code = 0;
		$npd_date = '';
		$npd_panjer_number = '';
		$field_activity = '';
		$activity = '';
		$npd_date = '';
		if (strlen($idnpd_panjer) == 0) {
			$err_code++;
		}
		if ($err_code == 0) {
			$this->db->where('npd_panjer.idnpd_panjer', $idnpd_panjer);
			$this->db->where('npd_panjer.status', 1);
			$this->db->select('*, date_format(npd_panjer.npd_panjer_date, "%d %M %Y") as txt_npd_date_created');
			$data = $this->db->get('npd_panjer');
			if ($data->num_rows() == 0) {
				$err_code++;
			}
			else {
				$npd_code = $data->row()->npd_panjer_code;
				$npd_date_created = $data->row()->npd_panjer_date;
				$npd_panjer_number = $data->row()->npd_panjer_number;
				$field_activity = $data->row()->field_activity;
				$activity = $data->row()->activity;
				$npd_date = $this->bulanIndo($data->row()->txt_npd_date_created);
			}
		}

		if ($err_code == 0) {
			$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
			$this->db->where('paket_belanja_detail.status', 1);
			
			$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = paket_belanja_detail.idpaket_belanja');
			$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
			$this->db->join('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
			$this->db->join('program', 'program.idprogram = paket_belanja.idprogram');
			// $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail = paket_belanja_detail.idpaket_belanja_detail');

			$this->db->select('
				paket_belanja_detail.idpaket_belanja_detail, 
				nama_akun_belanja, 
				status_paket_belanja, 
				akun_belanja.no_rekening_akunbelanja, 
				paket_belanja.idpaket_belanja, 
				paket_belanja.nilai_anggaran,
				akun_belanja.idakun_belanja, 
				concat( "(", program.no_rekening_program, ") ", program.nama_program) as nama_program, 
				concat( "(", program.no_rekening_program, ".", kegiatan.no_rekening_kegiatan, ") ", kegiatan.nama_kegiatan) as nama_kegiatan,
				concat( "(", program.no_rekening_program, ".", kegiatan.no_rekening_kegiatan, ".", sub_kegiatan.no_rekening_subkegiatan, ") ", sub_kegiatan.nama_subkegiatan) as nama_subkegiatan');
			$pb_detail = $this->db->get('paket_belanja_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;

			$nama_program = $pb_detail->row()->nama_program;
			$nama_kegiatan = $pb_detail->row()->nama_kegiatan;
			$nama_subkegiatan = $pb_detail->row()->nama_subkegiatan;

			$arr_data = array();
			$arr_detail_sub = array();
			$total_data = 0;
			$total_realisasi = 0;
			foreach ($pb_detail->result() as $key => $value) {
				$idpaket_belanja_detail = $value->idpaket_belanja_detail;
				$nilai_anggaran = $value->nilai_anggaran;
				// $idpaket_belanja_detail_sub = $value->idpaket_belanja_detail_sub;

				$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail = "'.$idpaket_belanja_detail.'" ');
				$this->db->where('paket_belanja_detail_sub.status', 1);
				$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
				$detail_sub = $this->db->get('paket_belanja_detail_sub');
				
				$nama_kategori = $detail_sub->row()->nama_kategori;

					$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub = "'.$detail_sub->row()->idpaket_belanja_detail_sub.'" ');
					$this->db->where('paket_belanja_detail_sub.status', 1);
					$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
					$detail_sub_parent = $this->db->get('paket_belanja_detail_sub');

				if ($detail_sub_parent->num_rows() > 0) {
					$detail_sub = $detail_sub_parent;
				}
				// echo "<pre>"; print_r($this->db->last_query());die;

				$arr_detail_sub = array();
				foreach ($detail_sub->result() as $ds_key => $ds_value) {
					$idsub_kategori = $ds_value->idsub_kategori;
					$idpaket_belanja_detail_sub = $ds_value->idpaket_belanja_detail_sub;


					$this->db->where('npd_panjer_detail.idnpd_panjer', $idnpd_panjer);
					$this->db->where('npd_panjer_detail.iduraian', $idsub_kategori);
					$this->db->where('npd_panjer_detail.status = "1" ');

					$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = npd_panjer_detail.iduraian');
					$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening');
				
					$npd_panjer_detail = $this->db->get('npd_panjer_detail');
					// echo "<pre>"; print_r($this->db->last_query());die;

					foreach ($npd_panjer_detail->result() as $n_key => $n_value) {

						$sisa_anggaran = $n_value->remains_budget;
						$sisa_akhir = $sisa_anggaran - $n_value->total;

						$arr_detail_sub[] = array(
							'idpaket_belanja' => $idpaket_belanja,
							'iduraian' => $n_value->iduraian,
							'idpaket_belanja_detail' => $idpaket_belanja_detail,
							'idpaket_belanja_detail_sub' => $idpaket_belanja_detail_sub,
							'nomor_kode_rekening' => $n_value->kode_rekening,
							'nama_sub_kategori' => $n_value->nama_sub_kategori,
							'total_anggaran' => $nilai_anggaran,
							// 'total_anggaran' => $n_value->total_anggaran,
							'sisa_anggaran' => $sisa_anggaran,
							'total_sekarang' => $n_value->total,
							'sisa_akhir' => $sisa_akhir,
							'realization_detail_description' => $n_value->npd_detail_description,
						);

						$total_realisasi += $n_value->total;

						$total_data++;
					}
				}
				
				$arr_detail = array();
				if ($detail_sub->num_rows() > 0) {
					if (strlen($nama_kategori) > 0) {
						$total_data++;
					}

					$arr_detail[] = array(
						'nama_kategori' => $nama_kategori,
						'arr_detail_sub' => $arr_detail_sub,
					);
				}

				if (count($arr_detail) > 0 && count($arr_detail_sub) > 0) {
					$total_data++;

					$arr_data[] = array(
						'no_rekening_akunbelanja' => $value->no_rekening_akunbelanja,
						'nama_akun_belanja' => $value->nama_akun_belanja,
						'total_data' => $total_data,
						'arr_detail' => $arr_detail,
					);

					$total_data = 0;
				}
			}

			// echo "<pre>"; print_r($arr_data);die;

			$the_data = array(
				// 'nomor_surat' => $npd_code,
				// 'npd_date' => $npd_date,
				'pptk' => az_get_config('PPTK', 'config'),
				'program' => $nama_program,
				'kegiatan' => $nama_kegiatan,
				'sub_kegiatan' => $nama_subkegiatan,
				'nomor_dpa' => az_get_config('nomor_DPA', 'config'),
				// 'tahun_anggaran' => az_get_config('tahun_anggaran', 'config'),
				'arr_data' => $arr_data,
				'npd_panjer_date' => $npd_date,
				'total_realisasi' => $total_realisasi,
				'npd_panjer_number' => $npd_panjer_number,
				'field_activity' => $field_activity,
				'activity' => $activity,
			);

			// echo "<pre>"; print_r($the_data);die;

			$this->load->view('npd_panjer/v_label_npd_panjer', $the_data);
		}
	}

	// mapping bulan Indonesia
	function bulanIndo($tanggal) {
		$bulan = [
			'January' => 'Januari',
			'February' => 'Februari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember'
		];
		
		// replace nama bulan Inggris ke Indonesia
		return strtr($tanggal, $bulan);
	}

	function get_data() {
		$id = $this->input->post('id');
		$this->db->where('npd_panjer.idnpd_panjer', $id);
		$this->db->join('user', 'user.iduser = npd_panjer.iduser_created');
		$this->db->select('date_format(npd_panjer_date, "%d-%m-%Y %H:%i:%s") as txt_npd_panjer_date, npd_panjer_code, user.name as user_created, npd_panjer.iduser_created, npd_panjer_number, field_activity, activity');
		$this->db->order_by('npd_panjer_date', 'desc');
		$npd_panjer = $this->db->get('npd_panjer')->result_array();

		$this->db->where('idnpd_panjer', $id);
		$npd_panjer_detail = $this->db->get('npd_panjer_detail')->result_array();

		$return = array(
			'npd_panjer' => azarr($npd_panjer, 0),
			'npd_panjer_detail' => $npd_panjer_detail
		);
		echo json_encode($return);
	}

    function get_list_order() {
		$idnpd_panjer = $this->input->post("idnpd_panjer");

        $this->db->where('npd_panjer_detail.status', 1);
        $this->db->where('npd_panjer_detail.idnpd_panjer', $idnpd_panjer);
		$this->db->join('npd_panjer', 'npd_panjer.idnpd_panjer = npd_panjer_detail.idnpd_panjer');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = npd_panjer_detail.idpaket_belanja');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = npd_panjer_detail.iduraian');
		$this->db->select('npd_panjer_detail.idnpd_panjer_detail, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori as nama_uraian, npd_panjer_detail.volume, npd_panjer_detail.harga_satuan, npd_panjer_detail.total, npd_panjer.npd_panjer_status, npd_panjer.total_realisasi');
        $panjer_detail = $this->db->get('npd_panjer_detail');

        $data['detail'] = $panjer_detail->result_array();
		$data['total_realisasi'] = $panjer_detail->row()->total_realisasi;

		$view = $this->load->view('npd_panjer/v_npd_panjer_table', $data, true);
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

	public function get_validate_description() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_description');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_description = $sub_kategori->row()->is_description;
		
		echo json_encode($is_description);
	}

	public function get_validate_room() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_room');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_room = $sub_kategori->row()->is_room;
		
		echo json_encode($is_room);
	}

	public function get_validate_training() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_name_training');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_name_training = $sub_kategori->row()->is_name_training;
		
		echo json_encode($is_name_training);
	}

	function get_data_utama($the_data) {
		$iduraian = azarr($the_data, 'iduraian', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$add_select = azarr($the_data, 'add_select', '');

		// menampilkan data utama dari paket belanja
		$this->db->where('pb.status', 1);
		$this->db->where('pb.status_paket_belanja = "OK" ');
		$this->db->where('pbd.status', 1);
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
		$npd_panjer_date = azarr($the_data, 'npd_panjer_date', '');

		$format_date = date("n", strtotime($npd_panjer_date));

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
			'npd_panjer_date' => $npd_panjer_date,
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
		$npd_panjer_date = azarr($the_data, 'npd_panjer_date', '');
		$idnpd_panjer_detail = azarr($the_data, 'idnpd_panjer_detail', '');

		$format_year = date("Y", strtotime($npd_panjer_date));
		$format_month = date("m", strtotime($npd_panjer_date));

		// menampilkan data realisasi yang sudah ada sampai dengan tanggal inputan
		$this->db->where('npd_panjer.status', 1);
		$this->db->where('npd_panjer_detail.status', 1);
		if (strlen($idnpd_panjer_detail) > 0) {
			$this->db->where('npd_panjer_detail.idnpd_panjer_detail != "'.$idnpd_panjer_detail.'" ');
		}
		$this->db->where('npd_panjer_detail.iduraian', $iduraian);
		$this->db->where('npd_panjer_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('DATE_FORMAT(npd_panjer.npd_panjer_date, "%Y-%m") >=', $format_year . '-01');
		$this->db->where('DATE_FORMAT(npd_panjer.npd_panjer_date, "%Y-%m") <=', $format_year . '-' . $format_month);
		$this->db->where('npd_panjer.npd_panjer_status !=', 'DRAFT');
		$this->db->join('npd_panjer_detail', 'npd_panjer_detail.idnpd_panjer = npd_panjer.idnpd_panjer');
		$this->db->select('count(npd_panjer.idnpd_panjer), sum(npd_panjer_detail.volume) as total_volume, sum(npd_panjer_detail.laki) as total_laki, sum(npd_panjer_detail.perempuan) as total_perempuan, sum(npd_panjer_detail.harga_satuan) as total_harga_satuan, sum(npd_panjer_detail.ppn) as total_ppn, sum(npd_panjer_detail.pph) as total_pph, sum(npd_panjer_detail.total) as total_realisasi');
		$trx = $this->db->get('npd_panjer');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $trx;
	}

    private function generate_transaction_code() {
		$this->db->where('day(npd_panjer_date)', Date('d'));
		$this->db->where('month(npd_panjer_date)', Date('m'));
		$this->db->where('year(npd_panjer_date)', Date('Y'));
		$this->db->where('npd_panjer_code IS NOT NULL ');
		$this->db->order_by('npd_panjer_code desc');
		$data = $this->db->get('npd_panjer', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$npd_panjer_code = 'NP'.Date('Ymd').$numb;

			$this->db->where('npd_panjer_code', $npd_panjer_code);
			$this->db->select('npd_panjer_code');
			$check = $this->db->get('npd_panjer');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($npd_panjer_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$npd_panjer_code = 'NP'.Date('Ymd').$numb;

				$this->db->where('npd_panjer_code', $npd_panjer_code);
				$this->db->select('npd_panjer_code');
				$check = $this->db->get('npd_panjer');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->npd_panjer_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$npd_panjer_code = 'NP'.Date('Ymd').$numb;

			$this->db->where('npd_panjer_code', $npd_panjer_code);
			$this->db->select('npd_panjer_code');
			$check = $this->db->get('npd_panjer');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($npd_panjer_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$npd_panjer_code = 'NP'.Date('Ymd').$numb;

				$this->db->where('npd_panjer_code', $npd_panjer_code);
				$this->db->select('npd_panjer_code');
				$check = $this->db->get('npd_panjer');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

		// NP => NPD Panjer

		return $npd_panjer_code;
	}

	function calculate_total_realisasi($idnpd_panjer) {

		$this->db->where('status', 1);
		$this->db->where('idnpd_panjer', $idnpd_panjer);
		$this->db->select('sum(total) as total_realisasi');
		$trxd = $this->db->get('npd_panjer_detail');

		$total_realisasi = azobj($trxd->row(), 'total_realisasi', 0);

		$arr_update = array(
			'total_realisasi' => $total_realisasi,
		);

		az_crud_save($idnpd_panjer, 'npd_panjer', $arr_update);
	}
}
