<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_realisasi_anggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_realisasi_anggaran');
        $this->table = 'npd';
        $this->controller = 'report_realisasi_anggaran';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal', 'Penyedia', 'Ruang', 'Uraian', 'Keterangan', 'Volume', 'LK', 'PR', 'Harga Satuan', 'Total'));
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
		$crud->add_aodata('idsub_kategori', 'idsub_kategori');

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
		$idsub_kategori = $this->input->get('idsub_kategori');


		$crud->set_select('npd.idnpd, date_format(npd.confirm_payment_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_payment_date, budget_realization_detail.provider, ruang.nama_ruang, sub_kategori.nama_sub_kategori, budget_realization_detail.realization_detail_description, budget_realization_detail.volume, budget_realization_detail.male, budget_realization_detail.female, budget_realization_detail.unit_price, budget_realization_detail.total_realization_detail');
		$crud->set_select_table('idnpd, txt_confirm_payment_date, provider, nama_ruang, nama_sub_kategori, realization_detail_description, volume, male, female, unit_price, total_realization_detail');

		$crud->set_filter('txt_confirm_payment_date, provider, nama_ruang, nama_sub_kategori, realization_detail_description, volume, male, female, unit_price, total_realization_detail');
		$crud->set_sorting('txt_confirm_payment_date, provider, nama_ruang, nama_sub_kategori, realization_detail_description, volume, male, female, unit_price, total_realization_detail');

		$crud->set_select_align(', , , , , center, center, center, right, right');
		$crud->set_id($this->controller);

		$crud->add_join_manual('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$crud->add_join_manual('verification', 'verification.idverification = npd_detail.idverification');
		$crud->add_join_manual('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
		$crud->add_join_manual('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$crud->add_join_manual('sub_kategori', 'sub_kategori.idsub_kategori = budget_realization_detail.idsub_kategori');
        $crud->add_join_manual('ruang', 'ruang.idruang = budget_realization_detail.idruang', 'left');

		$crud->add_where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
		$crud->add_where('npd.status = "1" ');
		$crud->add_where('npd_detail.status = "1" ');
		$crud->add_where('verification.status = "1" ');
		$crud->add_where('budget_realization.status = "1" ');
		$crud->add_where('budget_realization_detail.status = "1" ');

		if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(npd.confirm_payment_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(npd.confirm_payment_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($idsub_kategori) > 0) {
			$crud->add_where('budget_realization_detail.idsub_kategori = "' . $idsub_kategori . '"');
		}

		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('npd.confirm_payment_date ASC, sub_kategori.nama_sub_kategori ASC');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {

		if ($key == 'unit_price') {
			$unit_price = az_thousand_separator($value);

			return $unit_price;
		}

        if ($key == 'total_realization_detail') {
			$total_realization_detail = az_thousand_separator($value);

			return $total_realization_detail;
		}

		return $value;
	}
}