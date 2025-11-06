<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_realization extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('budget_realization');
        $this->table = 'budget_realization';
        $this->controller = 'budget_realization';
        $this->load->helper('az_crud');
        $this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('idrole');

		$crud->set_column(array('#', 'Tanggal Realisasi', 'Nomor Realisasi', 'Detail', 'Total Realisasi', 'Status', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		if (aznav('role_view_budget_realization') && strlen($idrole) > 0) {
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
		$crud->add_aodata('vf_realization_code', 'vf_realization_code');
		$crud->add_aodata('vf_realization_status', 'vf_realization_status');

		$vf = $this->load->view('budget_realization/vf_budget_realization', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'budget_realization';
		$view = $this->load->view('budget_realization/v_format_budget_realization', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('budget_realization/vjs_budget_realization');
		$azapp->add_js($js);

		$data_header['title'] = 'Realisasi Anggaran';
		$data_header['breadcrumb'] = array('budget_realization');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$transaction_code = $this->input->get('transaction_code');
		$transaction_status = $this->input->get('vf_transaction_status');

        $crud->set_select('budget_realization.idbudget_realization, date_format(realization_date, "%d-%m-%Y %H:%i:%s") as txt_realization_date, realization_code, "" as detail, total_realization, realization_status, user.name as user_created');
		$crud->set_select_table('idbudget_realization, txt_realization_date, realization_code, detail, total_realization, realization_status, user_created');

        $crud->set_sorting('realization_date, realization_code, total_realization, realization_status');
        $crud->set_filter('realization_date, realization_code, total_realization, realization_status');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , right, center');

        $crud->add_join_manual('user', 'budget_realization.iduser_created = user.iduser');
        $crud->add_join_manual('budget_realization_detail', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization');
		
        // $crud->set_group_by('transaction.idtransaction, transaction.transaction_date, transaction_code, paket_belanja.nama_paket_belanja, total_realisasi, user.name');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(budget_realization.realization_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(budget_realization.realization_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($transaction_code) > 0) {
			$crud->add_where('budget_realization.realization_code = "' . $transaction_code . '"');
		}
		if (strlen($transaction_status) > 0) {
			$crud->add_where('budget_realization.realization_status = "' . $transaction_status . '"');
		}

		$crud->add_where("budget_realization.status = 1");
		$crud->add_where("budget_realization_detail.status = 1");
		$crud->add_where("budget_realization.realization_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('realization_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('idrole');
		$idbudget_realization = azarr($data, 'idbudget_realization');
		$read_more = false;
		
		if ($key == 'total_realization') {
			$total_realization = az_thousand_separator($value);

			return $total_realization;
		}

		if ($key == "realization_status") {
			$status = label_status($value);
			
			return $status;
		}

		if ($key == 'detail') {
            $this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization_detail.status', 1);

			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail');
			$budget_realization = $this->db->get('budget_realization');
            // echo "<pre>"; print_r($this->db->last_query());die;

			$last_query = $this->db->last_query();
			$budget_realization_limit = $this->db->query('SELECT * FROM ('.$last_query.') as new_query limit 3 ');

			if ($budget_realization->num_rows() > 3) {
				$read_more = true;
			}
			
			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th width='auto'>Nomor Kontrak</th>";
			// $table .=			"<th width='auto'>Nomor Rencana</th>";
			$table .=			"<th width='180px'>Paket Belanja</th>";
			$table .=			"<th width='200px'>Uraian</th>";
			$table .=			"<th width='60px'>Volume</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";

            foreach ($budget_realization_limit->result_array() as $key => $value) {
				$table .= "<tr>";
				$table .= 		"<td>".$value['contract_code']."</td>";
				// $table .= 		"<td>".$value['purchase_plan_code']."</td>";
				$table .= 		"<td>".$value['nama_paket_belanja']."</td>";
				$table .= 		"<td>".$value['nama_sub_kategori']."</td>";
				$table .= 		"<td align='center'>".az_thousand_separator($value['volume'])."</td>";
				$table .= "</tr>";
            }
			
			$table .=	"</tbody>";
			$table .= "</table>";

			if ($read_more) {
				$table .= '<a href="budget_realization/edit/'.$idbudget_realization.'/view_only">Selengkapnya...</a>';
			}

			return $table;
		}

		if ($key == 'action') {
			$is_view_only = false;

            $btn = '<button class="btn btn-default btn-xs btn-edit-budget-realization" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-budget-realization" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

            // $this->db->where('idtransaction', $idtransaction);
            // $trx = $this->db->get('transaction');

            // $trx_status = $trx->row()->transaction_status;
            // // if (in_array($trx_status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
            // if (in_array($trx_status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
			// 	$is_view_only = true;
            // }

			if (aznav('role_view_budget_realization') && strlen($idrole) > 0) {
				$is_view_only = true;
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-budget-realization" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
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
        
		$view = $this->load->view('budget_realization/v_budget_realization', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('budget_realization/v_budget_realization_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add_realization');
		$modal->set_modal_title('Tambah Realisasi Anggaran');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('budget_realization/vjs_budget_realization_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Realisasi Anggaran';
		$data_header['breadcrumb'] = array('budget_realization');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	function select_paket_belanja() {
		$idpaket_belanja = $this->input->post('idpaket_belanja');
		$idsub_kategori = $this->input->post('idsub_kategori');
		$idcontract_detail = $this->input->post('idcontract_detail');

		$this->db->where('paket_belanja.idpaket_belanja', $idpaket_belanja);
		$this->db->where('paket_belanja_detail_sub.idsub_kategori', $idsub_kategori);
		$this->db->where('contract_detail.idcontract_detail', $idcontract_detail);
		$this->db->where('contract_detail.status', 1);
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('paket_belanja_detail_sub.status', 1);

		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');

		$this->db->select('urusan_pemerintah.idurusan_pemerintah, urusan_pemerintah.nama_urusan, bidang_urusan.idbidang_urusan, bidang_urusan.nama_bidang_urusan, program.idprogram, program.nama_program, kegiatan.idkegiatan, kegiatan.nama_kegiatan, sub_kegiatan.idsub_kegiatan, sub_kegiatan.nama_subkegiatan, paket_belanja.idpaket_belanja, paket_belanja.nama_paket_belanja, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, purchase_plan_detail.idpurchase_plan_detail, purchase_plan_detail.volume, paket_belanja_detail_sub.harga_satuan, purchase_plan_detail.purchase_plan_detail_total');
		$contract_detail = $this->db->get('contract_detail');
        // echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'nama_urusan' => $contract_detail->row()->nama_urusan,
			'nama_bidang_urusan' => $contract_detail->row()->nama_bidang_urusan,
			'nama_program' => $contract_detail->row()->nama_program,
			'nama_kegiatan' => $contract_detail->row()->nama_kegiatan,
			'nama_subkegiatan' => $contract_detail->row()->nama_subkegiatan,
			'nama_paket_belanja' => $contract_detail->row()->nama_paket_belanja,
			'nama_sub_kategori' => $contract_detail->row()->nama_sub_kategori,
			'idsub_kategori' => $contract_detail->row()->idsub_kategori,
			'volume' => $contract_detail->row()->volume,
			'unit_price' => $contract_detail->row()->harga_satuan,
			'total_realization_detail' => $contract_detail->row()->purchase_plan_detail_total,
			'idpaket_belanja' => $idpaket_belanja,
		);

		echo json_encode($ret);
	}

	function add_realization() {
		$err_code = 0;
		$err_message = '';
		$validate_gender = false;
		$validate_description = false;
		$validate_room = false;
		$validate_name_training = false;
		
		
		$idbudget_realization = $this->input->post('idbudget_realization');
		$idbudget_realization_detail = $this->input->post('idbudget_realization_detail');
		$realization_date = az_crud_date($this->input->post('realization_date'));
		
		$idcontract = $this->input->post('idcontract');
		$idpaket_belanja_detail_sub = $this->input->post('idpaket_belanja_detail_sub');
		$data_idcontract = $this->input->post('data_idcontract');
		$data_idcontract_detail = $this->input->post('data_idcontract_detail');
		$data_idpurchase_plan = $this->input->post('data_idpurchase_plan');
		$data_idpurchase_plan_detail = $this->input->post('data_idpurchase_plan_detail');
		$data_idpaket_belanja = $this->input->post('data_idpaket_belanja');
		$data_idpaket_belanja_detail_sub = $this->input->post('data_idpaket_belanja_detail_sub');
		$data_idsub_kategori = $this->input->post('data_idsub_kategori');

		$provider = $this->input->post('provider');
		$idruang = $this->input->post('idruang');
		$training_name = $this->input->post('training_name');
		$volume = az_crud_number($this->input->post('volume'));
		$male = az_crud_number($this->input->post('male'));
		$female = az_crud_number($this->input->post('female'));
		$unit_price = az_crud_number($this->input->post('unit_price'));
		$ppn = az_crud_number($this->input->post('ppn'));
		$pph = az_crud_number($this->input->post('pph'));
		$realization_detail_description = $this->input->post('realization_detail_description');

		$total_realization_detail = (floatval($volume) * floatval($unit_price)) + floatval($ppn) - floatval($pph);


		$this->load->library('form_validation');
		$this->form_validation->set_rules('idcontract', 'Nomor Kontrak', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('data_idsub_kategori', 'Uraian Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('provider', 'Penyedia', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('volume', 'Volume', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('unit_price', 'Harga Satuan', 'required|trim|max_length[200]');

		// validasi apakah inputan laki-laki dan perempuan wajib diisi
		if (strlen($data_idsub_kategori) > 0) {
			$this->db->where('status', 1);
			$this->db->where('idsub_kategori', $data_idsub_kategori);
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
			$this->form_validation->set_rules('male', 'Laki-laki', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('female', 'Perempuan', 'required|trim|max_length[200]');
		}
		if ($validate_description) {
			$this->form_validation->set_rules('realization_detail_description', 'Keterangan', 'required');
		}
		if ($validate_room) {
			$this->form_validation->set_rules('idruang', 'Nama Ruang', 'required|trim|max_length[200]');
		}
		if ($validate_name_training) {
			$this->form_validation->set_rules('training_name', 'Nama Diklat', 'required|trim|max_length[200]');
		}

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		// validasi jumlah laki-laki & perempuan tidak boleh lebih dari volume
		if ($err_code == 0) {
			if ($validate_gender) {
				if (floatval($volume) != (floatval($male) + floatval($female)) ) {
					$err_code++;
					$err_message = "Jumlah inputan total laki-laki dan perempuan tidak sama dengan inputan volume.";
				}
			}
		}

		// validasi tanggal realisasi tidak boleh melebihi tanggal hari ini
		if ($err_code == 0) {
			if (strtotime($realization_date) > strtotime(date('Y-m-d H:i:s'))) {
				$err_code++;
				$err_message = "Tanggal realisasi tidak boleh melebihi tanggal hari ini.";
			}
		}

		// // validasi
		// if ($err_code == 0) {
		// 	$the_filter = array(
		// 		'iduraian' => $data_idsub_kategori,
		// 		'idpaket_belanja' => $idpaket_belanja,
		// 		'transaction_date' => $realization_date,
		// 		'idtransaction_detail' => $idtransaction_detail,
		// 	);
		// 	// var_dump($the_filter);die;

		// 	// ambil data DPA
		// 	$data_utama = $this->get_data_utama($the_filter);
		// 	// echo "<pre>"; print_r($this->db->last_query()); die;

		// 	// ambil data Rencana Anggaran Kegiatan (RAK) sampai bulan yang dipilih
		// 	$data_rak = $this->get_data_rak($the_filter);
		// 	// echo "<pre>"; print_r($this->db->last_query()); die;

		// 	// ambil data uraian belanja yang sudah direalisasikan
		// 	$data_realisasi = $this->get_data_realisasi($the_filter);
		// 	// echo "<pre>"; print_r($this->db->last_query()); die;
			

		// 	// jika dicentang maka pengecekannya inputannya langsung dibandingkan dengan total keseluruhan volume dan total anggaran
		// 	if (!aznav('role_bypass')) {

		// 		// validasi apakah volume inputan + volume yang sudah direalisasikan melebihi volume RAK
		// 		if ( (floatval($volume) + floatval($data_realisasi->row()->total_volume)) > floatval($data_rak->row()->total_rak_volume) ) {
		// 			$err_code++;
		// 			$err_message = "Volume yang diinputkan melebihi volume RAK pada bulan yang dipilih.";
		// 		}
		// 		// var_dump('('.floatval($volume).' + '.floatval($data_realisasi->row()->total_volume).') > '.floatval($data_rak->row()->total_rak_volume)); echo "<br><br>";
				

		// 		// validasi apakah jumlah inputan + jumlah yang sudah direalisasikan melebihi jumlah RAK
		// 		if ( (floatval($total) + floatval($data_realisasi->row()->total_realisasi)) > floatval($data_rak->row()->total_rak_jumlah) ) {
		// 			$err_code++;
		// 			$err_message = "Total yang diinputkan melebihi jumlah RAK pada bulan yang dipilih.";
		// 		}
		// 		// var_dump( '('.floatval($total).' + '.floatval($data_realisasi->row()->total_realisasi).') > '.floatval($data_rak->row()->total_rak_jumlah) ); echo "<br><br>";

		// 	}

		
		// 	if ($err_code == 0) {
		// 		// validasi apakah volume yang sudah direalisasikan melebihi volume yang sudah ditentukan
		// 		if ($data_utama->row()->volume < (floatval($data_realisasi->row()->total_volume) + floatval($volume))) {
		// 			$err_code++;
		// 			$err_message = "Volume yang direalisasikan melebihi volume dari DPA.";
		// 		}
		// 		// var_dump($data_utama->row()->volume.' < ('.floatval($data_realisasi->row()->total_volume).' + '.floatval($volume).')'); echo "<br><br>";


		// 		// validasi apakah jumlah yang sudah direalisasikan melebihi jumlah yang sudah ditentukan
		// 		if ($data_utama->row()->jumlah < (floatval($data_realisasi->row()->total_realisasi) + floatval($total))) {
		// 			$err_code++;
		// 			$err_message = "Total Biaya yang direalisasikan melebihi jumlah dari DPA.";
		// 		}
		// 		// var_dump($data_utama->row()->jumlah.' < ('.floatval($data_realisasi->row()->total_realisasi).' + '.floatval($total).')'); echo "<br><br>";

		// 	}
		// }
		// // var_dump($err_message);die;

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

			if (strlen($idbudget_realization) == 0) {
				$arr_realization = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'realization_date' => Date('Y-m-d H:i:s'),
					'realization_status' => 'DRAFT',
					'realization_code' => $this->generate_code(),
				);

				$save_realization = az_crud_save($idbudget_realization, 'budget_realization', $arr_realization);
				$idbudget_realization = azarr($save_realization, 'insert_id');
			}
			else {
				// validasi apakah data paket belanja yang disimpan sama?
				// jika tidak maka data tidak perlu disimpan

				$this->db->where('status', 1);
				$this->db->where('idbudget_realization', $idbudget_realization);
				$this->db->where('idcontract_detail', $data_idcontract_detail);
				$rd = $this->db->get('budget_realization_detail');

				if ($rd->num_rows() == 0) {
					$err_code++;
					$err_message = "Paket Belanja yang anda inputkan berbeda dengan paket belanja yang telah diinputkan sebelumnya. <br>";
					$err_message .= "Silahkan menginputkan data dengan paket belanja yang sama.";
				}
			}
            
			if ($err_code == 0) {
				//detail
				$arr_realization_detail = array(
					'idbudget_realization' => $idbudget_realization,
					'idcontract_detail' => $data_idcontract_detail,
					'idpurchase_plan_detail' => $data_idpurchase_plan_detail,
					'provider' => $provider,
					'idsub_kategori' => $data_idsub_kategori,
					'idruang' => $idruang,
					'training_name' => $training_name,
					'volume' => $volume,
					'male' => $male,
					'female' => $female,
					'unit_price' => $unit_price,
					'ppn' => $ppn,
					'pph' => $pph,
					'total_realization_detail' => $total_realization_detail,
					'realization_detail_description' => $realization_detail_description,
				);
				
				$save_rd = az_crud_save($idbudget_realization_detail, 'budget_realization_detail', $arr_realization_detail);
				$idbudget_realization_detail = azarr($save_rd, 'insert_id');

				// hitung total transaksi
				$this->calculate_total_realization($idbudget_realization);
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idbudget_realization' => $idbudget_realization,
			'idbudget_realization_detail' => $idbudget_realization_detail,
		);
		echo json_encode($return);
	}

	function edit_order() {
		$idrealization_detail = $this->input->post("idrealization_detail");

		$err_code = 0;
		$err_message = "";

		$this->db->where('budget_realization_detail.idbudget_realization_detail', $idrealization_detail);

		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract_detail');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = purchase_plan_detail.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('ruang', 'ruang.idruang = budget_realization_detail.idruang', 'left');
		
		$this->db->select('
			budget_realization_detail.idbudget_realization,
			budget_realization_detail.provider,
			budget_realization_detail.idruang,
			budget_realization_detail.training_name,
			budget_realization_detail.volume,
			budget_realization_detail.male,
			budget_realization_detail.female,
			budget_realization_detail.unit_price,
			budget_realization_detail.ppn,
			budget_realization_detail.pph,
			budget_realization_detail.total_realization_detail,
			budget_realization_detail.realization_detail_description,
			
			ruang.nama_ruang as ajax_idruang,

			contract.idcontract, 
			contract.contract_code as ajax_idcontract, 
			contract_detail.idcontract_detail, 
			
			purchase_plan_detail.idpaket_belanja_detail_sub, 
			concat(purchase_plan.purchase_plan_code, " → ", paket_belanja.nama_paket_belanja, " → ", sub_kategori.nama_sub_kategori) as ajax_idpaket_belanja_detail_sub, 
			
			paket_belanja.idpaket_belanja, 
			
			sub_kategori.idsub_kategori, 
			
			purchase_plan.idpurchase_plan, 
			purchase_plan_detail.idpurchase_plan_detail');
		$rd = $this->db->get('budget_realization_detail')->result_array();

		$ret = array(
			'data' => azarr($rd, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function delete_order() {
		$idrealization_detail = $this->input->post('idrealization_detail');

		$err_code = 0;
		$err_message = "";
		$is_delete = true;
		$message = '';

		$this->db->where('idrealization_detail', $idrealization_detail);
		$this->db->join('realization', 'realization_detail.idrealization = realization.idrealization');
		$budget_realization = $this->db->get('budget_realization');

		$status = $budget_realization->row()->realization_status;
		$idbudget_realization = $budget_realization->row()->idbudget_realization;
		// // if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// if (in_array($status, array('SUDAH DIVERIFIKASI', 'DITOLAK VERIFIKATOR', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 	$is_delete = false;
		// }

		if ($is_delete) {
			$delete = az_crud_delete('budget_realization_detail', $idrealization_detail, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				// hitung total transaksi
				$this->calculate_total_realization($idbudget_realization);
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada paket belanja/detail transaksi di realisasi anggaran ini?
		if ($err_code == 0) {
			$this->db->where('idrealization_detail', $idrealization_detail);
			$this->db->where('status', 1);
			$budget_realization_detail = $this->db->get('budget_realization_detail');

			if ($budget_realization_detail->num_rows() == 0) {
				$arr_update = array(
					'realization_status' => 'DRAFT',
				);
				az_crud_save($idbudget_realization, 'budget_realization', $arr_update);

				$message = 'Paket Belanja berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan paket belanja baru, harap klik simpan transaksi realisasi anggaran, agar datanya tidak hilang.</span>';
			}
		}	

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
			'idbudget_realization' => $idbudget_realization,
		);

		echo json_encode($return);
	}

	function save_realization() {
		$err_code = 0;
		$err_message = '';

		
		$idbudget_realization = $this->input->post("hd_idbudget_realization");
		$realization_date = az_crud_date($this->input->post("realization_date"));
		$iduser_created = $this->input->post("iduser_created");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('realization_date', 'Tanggal Realisasi', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idbudget_realization) == 0) {
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
	    		'realization_date' => $realization_date,
	    		'realization_status' => "MENUNGGU VERIFIKASI",
	    		'iduser_created' => $iduser_created,
	    	);

	    	az_crud_save($idbudget_realization, 'budget_realization', $arr_data);

			// hitung total transaksi
			$this->calculate_total_realization($idbudget_realization);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function edit($id) {
		$this->db->where('idbudget_realization', $id);
		$check = $this->db->get('budget_realization');
		if ($check->num_rows() == 0) {
			redirect(app_url().'budget_realization');
		} 
		else if($this->uri->segment(4) != "view_only") {
			// $status = $check->row()->transaction_status;
			// // if (in_array($status, array('MENUNGGU VERIFIKASI', 'SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
			// if (in_array($status, array('SUDAH DIVERIFIKASI', 'INPUT NPD', 'MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
			// 	redirect(app_url().'budget_realization');
			// }
		}
		$this->add($id);
	}

	function get_data() {
		$idbudget_realization = $this->input->post('idbudget_realization');
		
		$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$this->db->join('user', 'user.iduser = budget_realization.iduser_created');
		$this->db->select('date_format(realization_date, "%d-%m-%Y %H:%i:%s") as txt_realization_date, realization_code, user.name as user_created, budget_realization.iduser_created');
		$this->db->order_by('realization_date', 'desc');
		$budget_realization = $this->db->get('budget_realization')->result_array();

		$return = array(
			'data' => azarr($budget_realization, 0),
		);
		echo json_encode($return);
	}

	function delete_realization() {
		$idbudget_realization = $this->input->post('idbudget_realization');

		$err_code = 0;
		$err_message = '';

		$this->db->where('idbudget_realization',$idbudget_realization);
		$budget_realization = $this->db->get('budget_realization');

		// if ($budget_realization->num_rows() > 0) {
		// 	$status = $budget_realization->row()->transaction_status;
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
			az_crud_delete($this->table, $idbudget_realization);

		} 
		else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}





	
    function get_list_data() {
		$idbudget_realization = $this->input->post("idbudget_realization");

		$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization_detail.status', 1);

		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

		$this->db->select('budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail');
		$budget_realization = $this->db->get('budget_realization');

        $data['detail'] = $budget_realization->result_array();
		$data['total_realization'] = $budget_realization->row()->total_realization;

		$view = $this->load->view('budget_realization/v_budget_realization_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function calculate_total_realization($idbudget_realization) {

		$this->db->where('status', 1);
		$this->db->where('idbudget_realization', $idbudget_realization);
		$this->db->select('sum(total_realization_detail) as total_realization');
		$rd = $this->db->get('budget_realization_detail');

		$total_realization = azobj($rd->row(), 'total_realization', 0);

		$arr_update = array(
			'total_realization' => $total_realization,
		);

		az_crud_save($idbudget_realization, 'budget_realization', $arr_update);
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

	private function generate_code() {
		$this->db->where('day(realization_date)', Date('d'));
		$this->db->where('month(realization_date)', Date('m'));
		$this->db->where('year(realization_date)', Date('Y'));
		$this->db->where('realization_code IS NOT NULL ');
		$this->db->order_by('realization_code desc');
		$data = $this->db->get('budget_realization', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$realization_code = 'AP'.Date('Ymd').$numb;

			$this->db->where('realization_code', $realization_code);
			$this->db->select('realization_code');
			$check = $this->db->get('budget_realization');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($realization_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$realization_code = 'AP'.Date('Ymd').$numb;

				$this->db->where('realization_code', $realization_code);
				$this->db->select('realization_code');
				$check = $this->db->get('budget_realization');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->realization_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$realization_code = 'AP'.Date('Ymd').$numb;

			$this->db->where('realization_code', $realization_code);
			$this->db->select('realization_code');
			$check = $this->db->get('budget_realization');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($realization_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$realization_code = 'AP'.Date('Ymd').$numb;

				$this->db->where('realization_code', $realization_code);
				$this->db->select('realization_code');
				$check = $this->db->get('budget_realization');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

		return $realization_code;
	}

	//////////////////////////////

    

    

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
		$idtransaction_detail = azarr($the_data, 'idtransaction_detail', '');

		$format_year = date("Y", strtotime($transaction_date));
		$format_month = date("m", strtotime($transaction_date));

		// menampilkan data realisasi yang sudah ada sampai dengan tanggal inputan
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction_detail.status', 1);
		if (strlen($idtransaction_detail) > 0) {
			$this->db->where('transaction_detail.idtransaction_detail != "'.$idtransaction_detail.'" ');
		}
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
}
