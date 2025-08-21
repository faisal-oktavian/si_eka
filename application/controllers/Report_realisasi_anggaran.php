<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_realisasi_anggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_realisasi_anggaran');
        $this->table = 'transaction_detail';
        $this->controller = 'report_realisasi_anggaran';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal', 'Penyedia', 'Ruang', 'Uraian', 'Keterangan', 'Volume', 'LK', 'PR', 'Harga', 'Total'));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
        $crud->set_btn_add(false);

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		// $date1->set_value('01-'.Date('m-Y'));
		// $date1->set_value('01-01-'.Date('Y'));
		$data['date1'] = $date1->render();

		$date2 = $azapp->add_datetime();
		$date2->set_id('date2');
		$date2->set_name('date2');
		$date2->set_format('DD-MM-YYYY');
		// $date2->set_value(Date('t-m-Y'));
		$data['date2'] = $date2->render();

		$crud->add_aodata('date1', 'date1');
		$crud->add_aodata('date2', 'date2');
		$crud->add_aodata('iduraian', 'iduraian');

		$filter = $this->load->view('report_realisasi_anggaran/vf_report_realisasi_anggaran', $data, true);
		$crud->set_top_filter($filter);

		// $js = az_add_js('urusan_pemerintah/vjs_urusan_pemerintah');
		// $azapp->add_js($js);
		
		$crud = $crud->render();
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Laporan Realisasi Anggaran');
		$data_header['breadcrumb'] = array('report');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$iduraian = $this->input->get('iduraian');

		$crud->set_select('idtransaction_detail, date_format(transaction_date, "%d-%m-%Y %H:%i:%s") as txt_transaction_date, penyedia, nama_ruang, nama_sub_kategori, transaction_description, volume, laki, perempuan, harga_satuan, total');
		$crud->set_select_table('idtransaction_detail, txt_transaction_date, penyedia, nama_ruang, nama_sub_kategori, transaction_description, volume, laki, perempuan, harga_satuan, total');
		$crud->set_filter('txt_transaction_date, penyedia, nama_ruang, nama_sub_kategori, transaction_description, volume, laki, perempuan, harga_satuan, total');
		$crud->set_sorting('txt_transaction_date, penyedia, nama_ruang, nama_sub_kategori, transaction_description, volume, laki, perempuan, harga_satuan, total');
		$crud->set_select_align(', , , , , center, center, center, right, right');
		$crud->set_id($this->controller);

        $crud->add_join_manual('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
        $crud->add_join_manual('sub_kategori', 'sub_kategori.idsub_kategori = transaction_detail.iduraian');
        $crud->add_join_manual('ruang', 'ruang.idruang = transaction_detail.idruang', 'left');

		$crud->add_where('transaction.status = "1" ');
		$crud->add_where('transaction.transaction_status != "DRAFT" ');
		$crud->add_where('transaction_detail.status = "1" ');

		if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(transaction.transaction_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(transaction.transaction_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($iduraian) > 0) {
			$crud->add_where('transaction_detail.iduraian = "' . $iduraian . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('sub_kategori.nama_sub_kategori ASC, transaction_detail.idpaket_belanja ASC, transaction.transaction_date ASC');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {

		if ($key == 'harga_satuan') {
			$harga_satuan = az_thousand_separator($value);

			return $harga_satuan;
		}

        if ($key == 'total') {
			$total = az_thousand_separator($value);

			return $total;
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

		$this->form_validation->set_rules('no_rekening_urusan', 'No Rekening ', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('nama_urusan', 'Nama Urusan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('tahun_anggaran_urusan', 'Tahun Anggaran', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('is_active', 'Status', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'no_rekening_urusan' => azarr($data_post, 'no_rekening_urusan'), 
				'nama_urusan' => azarr($data_post, 'nama_urusan'), 
				'tahun_anggaran_urusan' => az_crud_number(azarr($data_post, 'tahun_anggaran_urusan')),
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
		az_crud_edit('idurusan_pemerintah, no_rekening_urusan, nama_urusan, tahun_anggaran_urusan, is_active');
	}

	public function delete() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		// tambah validasi ketika urusan pemerintah sudah digunakan untuk bidang urusan
		$this->db->where('status', 1);
		$this->db->where('idurusan_pemerintah', $id);
		$bu = $this->db->get('bidang_urusan');

		if ($bu->num_rows() > 0) {
			$data_return = array(
                'err_code' => 1,
                'err_message' => "Data tidak bisa dihapus karena sudah digunakan pada menu Bidang Urusan."
            );

			echo json_encode($data_return);
		}
		else {
			az_crud_delete($this->table, $id);
		}

	}
}