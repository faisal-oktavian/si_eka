<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends AZ_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        az_check_auth('dashboard');
    }

	public function index(){
		$this->load->library('AZApp');
		$app = $this->azapp;
		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$app->set_data_header($data_header);
		$this->load->helper('az_config');
		$this->load->helper('az_core');

		$total_anggaran = 0;
		$total_realisasi = 0;
		$tahun_ini = date('Y');
		// $tahun_ini = "2024";

		// Hitung total anggaran pada tahun ini
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
        $this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->where('paket_belanja.status', 1);
		$this->db->where('paket_belanja.is_active', 1);
		$this->db->where('paket_belanja.status_paket_belanja = "OK" ');
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan = "'.$tahun_ini.'" ');
		$this->db->select_sum('paket_belanja.nilai_anggaran');
		$total_anggaran = $this->db->get('paket_belanja');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		if ($total_anggaran->num_rows() > 0) {
			$total_anggaran = $total_anggaran->row()->nilai_anggaran;
		}

		
		// // Hitung total anggaran yang sudah terealisasi pada tahun ini
		// $this->db->where('transaction.status', 1);
		// $this->db->where('transaction.transaction_status != "DRAFT" ');
		// $this->db->where('transaction_detail.status', 1);
		// $this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		// $this->db->select('sum(total_realisasi) as nilai_realisasi');
		// $total_realisasi = $this->db->get('transaction');
		// // echo "<pre>"; print_r($this->db->last_query()); die;

		// if ($total_realisasi->num_rows() > 0) {
		// 	$total_realisasi = $total_realisasi->row()->nilai_realisasi;
		// }
		
		$data = array(
			'tahun_ini' => $tahun_ini,
			'total_anggaran_tahun_ini' => floatval($total_anggaran),
			// 'total_realisasi_tahun_ini' => floatval($total_realisasi),
		);

		$view = $this->load->view('home/v_home', $data, true);
		$app->add_content($view);

		$js = az_add_js('home/vjs_home');
		$app->add_js($js);

		echo $app->render();	
	}
}