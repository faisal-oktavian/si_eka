<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasi_anggaran_detail extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('dashboard');
        $this->controller = 'realisasi_anggaran_detail';
        $this->load->helper('az_crud');
    }


	// BELUM DIREALISASI
	public function belum_direalisasi() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$tahun_ini = date('Y');

		$query_total = $this->get_query_belum_direalisasi($tahun_ini, true);
		$query_total = $this->db->query($query_total);
		$total_belum_terealisasi = $query_total->row()->total_nilai_anggaran;

		$crud->set_btn_top_custom("
					<div class='btn btn-default' style='background-color:#ff5722; color:#FFF;'>Total Anggaran : Rp. ".az_thousand_separator($total_belum_terealisasi)."</div>
			");

		$crud->set_column(array('#', "Program", "Paket Belanja", "Nilai Anggaran"));
		$crud->set_id($this->controller);
		// $crud->set_default_url(true);
		$crud->set_default_url(false);
		$crud->set_btn_add(false);
		$crud->set_url("app_url+'realisasi_anggaran_detail/get_belum_direalisasi'");
		$crud->set_url_edit("app_url+'realisasi_anggaran_detail/edit_belum_direalisasi'");
		$crud->set_url_delete("app_url+'realisasi_anggaran_detail/delete_belum_direalisasi'");
		$crud->set_url_save("app_url+'realisasi_anggaran_detail/save_belum_direalisasi'");
		$crud = $crud->render();

		$data['crud'] = $crud;
		$data['tahun_ini'] = $tahun_ini;
		
		$view = $this->load->view('realisasi_anggaran_detail/v_format_belum_direalisasi', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_belum_direalisasi() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_ini = date('Y');


		$query = $this->get_query_belum_direalisasi($tahun_ini);
		// echo "<pre>"; print_r($query); die;

		$crud->set_manual_query($query);

		$crud->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_filter('nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_sorting('nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_select_align(' , , right');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_id('paket_belanja');
		// $crud->set_custom_first_column(true);
		
		$crud->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_custom_style('custom_style_belum_direalisasi');
		$crud->set_table('paket_belanja');
		echo $crud->get_table();
	}

	function custom_style_belum_direalisasi($key, $value, $data) {
		
		if ($key == 'nilai_anggaran') {
			return az_thousand_separator($value);
		}

		return $value;
	}

	function get_query_belum_direalisasi($tahun_ini, $query_total = false) {
		// query utama
		$this->db->select('pb.idpaket_belanja, p.nama_program, pb.nama_paket_belanja, pb.nilai_anggaran');
		
		$this->db->join('sub_kegiatan sk', 'sk.idsub_kegiatan = pb.idsub_kegiatan');
		$this->db->join('kegiatan k', 'k.idkegiatan = sk.idkegiatan');
		$this->db->join('program p', 'p.idprogram = k.idprogram');
		$this->db->join('paket_belanja_detail pbd', 'pbd.idpaket_belanja = pb.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds', 'pbds.idpaket_belanja_detail = pbd.idpaket_belanja_detail');

		$this->db->where('YEAR(pb.created) = "'.$tahun_ini.'" ');
		$this->db->where('pb.status_paket_belanja', 'OK');
		$this->db->where('pb.is_active', 1);
		$this->db->where('pb.status', 1);
		$this->db->where('pbd.status', 1);
		$this->db->where('pbds.status', 1);
		$this->db->where('pbds.volume IS NOT NULL', null, false);
		$this->db->where('pbds.idsatuan IS NOT NULL', null, false);
		$this->db->where('pbds.harga_satuan IS NOT NULL', null, false);
		$this->db->where('pbds.jumlah IS NOT NULL', null, false);

		$this->db->group_by([
			'pb.idpaket_belanja',
			'p.nama_program',
			'pb.nama_paket_belanja',
			'pb.nilai_anggaran'
		]);

		$this->db->get('paket_belanja pb');
		$last_query1 = $this->db->last_query();
		// echo "<pre>"; print_r($last_query1); die;

		// query turunan
		$this->db->select('pb.idpaket_belanja, p.nama_program, pb.nama_paket_belanja, pb.nilai_anggaran');
		
		$this->db->join('sub_kegiatan sk', 'sk.idsub_kegiatan = pb.idsub_kegiatan');
		$this->db->join('kegiatan k', 'k.idkegiatan = sk.idkegiatan');
		$this->db->join('program p', 'p.idprogram = k.idprogram');
		$this->db->join('paket_belanja_detail pbd', 'pbd.idpaket_belanja = pb.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbds_parent.idpaket_belanja_detail = pbd.idpaket_belanja_detail');
		$this->db->join('paket_belanja_detail_sub pbds', 'pbds.is_idpaket_belanja_detail_sub = pbds_parent.idpaket_belanja_detail_sub');

		$this->db->where('YEAR(pb.created) = "'.$tahun_ini.'" ');
		$this->db->where('pb.status_paket_belanja', 'OK');
		$this->db->where('pb.is_active', 1);
		$this->db->where('pb.status', 1);
		$this->db->where('pbd.status', 1);
		$this->db->where('pbds.status', 1);
		$this->db->where('pbds.volume IS NOT NULL', null, false);
		$this->db->where('pbds.idsatuan IS NOT NULL', null, false);
		$this->db->where('pbds.harga_satuan IS NOT NULL', null, false);
		$this->db->where('pbds.jumlah IS NOT NULL', null, false);

		$this->db->group_by([
			'pb.idpaket_belanja',
			'p.nama_program',
			'pb.nama_paket_belanja',
			'pb.nilai_anggaran'
		]);

		$this->db->get('paket_belanja pb');
		$last_query2 = $this->db->last_query();
		// echo "<pre>"; print_r($last_query2); die;


		// query realisasi
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction.transaction_status != "DRAFT" ');
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('YEAR(transaction.transaction_date)', $tahun_ini);
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->group_by('idpaket_belanja');
		$this->db->select('idpaket_belanja');
		$this->db->get('transaction');
		$last_query_where = $this->db->last_query();
		// echo "<pre>"; print_r($last_query_where); die;
		

		// $query = array_merge($last_query1, $last_query2);
		$query = 'select * from (' . $last_query1 . ' UNION ' . $last_query2 . ') new_query WHERE idpaket_belanja NOT IN (' . $last_query_where . ')';
		
		if ($query_total) {
			$query = 'select sum(nilai_anggaran) as total_nilai_anggaran from (' . $last_query1 . ' UNION ' . $last_query2 . ') new_query WHERE idpaket_belanja NOT IN (' . $last_query_where . ')';
		}

		// echo "<pre>"; print_r($query);die;

		return $query;

	}


	// BELUM DIBAYAR
	public function belum_dibayar() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$tahun_ini = date('Y');

		$total_belum_dibayar = $this->get_query_belum_dibayar($tahun_ini, true);

		$crud->set_btn_top_custom("
					<div class='btn btn-default' style='background-color:#ff5722; color:#FFF;'>Total Anggaran : Rp. ".az_thousand_separator($total_belum_dibayar)."</div>
			");

		$crud->set_column(array('#', "Program", "Paket Belanja", "Nilai Anggaran"));
		$crud->set_id($this->controller);
		// $crud->set_default_url(true);
		$crud->set_default_url(false);
		$crud->set_btn_add(false);
		$crud->set_url("app_url+'realisasi_anggaran_detail/get_belum_dibayar'");
		$crud->set_url_edit("app_url+'realisasi_anggaran_detail/edit_belum_dibayar'");
		$crud->set_url_delete("app_url+'realisasi_anggaran_detail/delete_belum_dibayar'");
		$crud->set_url_save("app_url+'realisasi_anggaran_detail/save_belum_dibayar'");
		$crud = $crud->render();

		$data['crud'] = $crud;
		$data['tahun_ini'] = $tahun_ini;
		
		$view = $this->load->view('realisasi_anggaran_detail/v_format_belum_dibayar', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_belum_dibayar() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_ini = date('Y');


		$query = $this->get_query_belum_dibayar($tahun_ini);
		// echo "<pre>"; print_r($query); die;

		$crud->set_manual_query($query);

		$crud->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, total_anggaran');
		$crud->set_filter('nama_program, nama_paket_belanja, total_anggaran');
		$crud->set_sorting('nama_program, nama_paket_belanja, total_anggaran');
		$crud->set_select_align(' , , right');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_id('paket_belanja');
		// $crud->set_custom_first_column(true);
		
		$crud->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, total_anggaran');
		$crud->set_custom_style('custom_style_belum_dibayar');
		$crud->set_table('paket_belanja');
		echo $crud->get_table();
	}

	function custom_style_belum_dibayar($key, $value, $data) {
		
		if ($key == 'total_anggaran') {
			return az_thousand_separator($value);
		}

		return $value;
	}

	function get_query_belum_dibayar($tahun_ini, $query_total = false) {
		$total_anggaran = 0;

		$this->db->where('npd.status = 1');
		$this->db->where('npd.npd_status = "MENUNGGU PEMBAYARAN" ');
		$this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');
		$this->db->where('npd_detail.status = 1');
		$this->db->where('verification.status = 1');
		$this->db->where('verification.status_approve = "DISETUJUI" ');
		$this->db->where('verification_detail.status = 1');
		$this->db->where('transaction.status = 1');
		$this->db->where('transaction_detail.status = 1');
		$this->db->where('paket_belanja.status = 1');

		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$this->db->join('program', 'program.idprogram = paket_belanja.idprogram');

		$this->db->group_by('npd.idnpd, transaction_detail.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, npd.total_anggaran');
		$this->db->select('npd.idnpd, transaction_detail.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, npd.total_anggaran');
		$query = $this->db->get('npd');
		$last_query = $this->db->last_query();
		// echo "<pre>"; print_r($last_query); die;
		
		if ($query_total) {
			foreach ($query->result() as $key => $value) {
				$total_anggaran += $value->total_anggaran;
			}

			$query = $total_anggaran;
		}
		else {
			
			$query = 'select * from (' . $last_query . ') new_query';
		}

		// echo "<pre>"; print_r($query);die;

		return $query;

	}


	// SUDAH DIBAYAR
	public function sudah_dibayar() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$tahun_ini = date('Y');

		$total_sudah_dibayar = $this->get_query_sudah_dibayar($tahun_ini, true);

		$crud->set_btn_top_custom("
					<div class='btn btn-default' style='background-color:#ff5722; color:#FFF;'>Total Anggaran : Rp. ".az_thousand_separator($total_sudah_dibayar)."</div>
			");

		$crud->set_column(array('#', "Program", "Paket Belanja", "Nilai Anggaran"));
		$crud->set_id($this->controller);
		// $crud->set_default_url(true);
		$crud->set_default_url(false);
		$crud->set_btn_add(false);
		$crud->set_url("app_url+'realisasi_anggaran_detail/get_sudah_dibayar'");
		$crud->set_url_edit("app_url+'realisasi_anggaran_detail/edit_sudah_dibayar'");
		$crud->set_url_delete("app_url+'realisasi_anggaran_detail/delete_sudah_dibayar'");
		$crud->set_url_save("app_url+'realisasi_anggaran_detail/save_sudah_dibayar'");
		$crud = $crud->render();

		$data['crud'] = $crud;
		$data['tahun_ini'] = $tahun_ini;
		
		$view = $this->load->view('realisasi_anggaran_detail/v_format_sudah_dibayar', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_sudah_dibayar() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_ini = date('Y');


		$query = $this->get_query_sudah_dibayar($tahun_ini);
		// echo "<pre>"; print_r($query); die;

		$crud->set_manual_query($query);

		$crud->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, total_pay');
		$crud->set_filter('nama_program, nama_paket_belanja, total_pay');
		$crud->set_sorting('nama_program, nama_paket_belanja, total_pay');
		$crud->set_select_align(' , , right');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_id('paket_belanja');
		// $crud->set_custom_first_column(true);
		
		$crud->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, total_pay');
		$crud->set_custom_style('custom_style_sudah_dibayar');
		$crud->set_table('paket_belanja');
		echo $crud->get_table();
	}

	function custom_style_sudah_dibayar($key, $value, $data) {
		
		if ($key == 'total_pay') {
			return az_thousand_separator($value);
		}

		return $value;
	}

	function get_query_sudah_dibayar($tahun_ini, $query_total = false) {
		$total_pay = 0;

		$this->db->where('npd.status = 1');
		$this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('YEAR(npd.confirm_payment_date) = "'.$tahun_ini.'" ');
		$this->db->where('npd_detail.status = 1');
		$this->db->where('verification.status = 1');
		$this->db->where('verification.status_approve = "DISETUJUI" ');
		$this->db->where('verification_detail.status = 1');
		$this->db->where('transaction.status = 1');
		$this->db->where('transaction_detail.status = 1');
		$this->db->where('paket_belanja.status = 1');

		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$this->db->join('program', 'program.idprogram = paket_belanja.idprogram');

		$this->db->group_by('npd.idnpd, transaction_detail.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, npd.total_pay');
		$this->db->select('npd.idnpd, transaction_detail.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, npd.total_pay');
		$query = $this->db->get('npd');
		$last_query = $this->db->last_query();
		// echo "<pre>"; print_r($last_query); die;
		
		if ($query_total) {
			foreach ($query->result() as $key => $value) {
				$total_pay += $value->total_pay;
			}

			$query = $total_pay;
		}
		else {
			
			$query = 'select * from (' . $last_query . ') new_query';
		}

		// echo "<pre>"; print_r($query);die;

		return $query;

	}


	// POTENSI SISA ANGGARAN
	public function potensi_sisa_anggaran() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$tahun_ini = date('Y');

		$total_potensi_sisa_anggaran = $this->get_query_potensi_sisa_anggaran($tahun_ini, true);

		$crud->set_btn_top_custom("
					<div class='btn btn-default' style='background-color:#ff5722; color:#FFF;'>Total Anggaran : Rp. ".az_thousand_separator($total_potensi_sisa_anggaran)."</div>
			");

		$crud->set_column(array('#', "Program", "Paket Belanja", "Nilai Anggaran"));
		$crud->set_id($this->controller);
		// $crud->set_default_url(true);
		$crud->set_default_url(false);
		$crud->set_btn_add(false);
		$crud->set_url("app_url+'realisasi_anggaran_detail/get_potensi_sisa_anggaran'");
		$crud->set_url_edit("app_url+'realisasi_anggaran_detail/edit_potensi_sisa_anggaran'");
		$crud->set_url_delete("app_url+'realisasi_anggaran_detail/delete_potensi_sisa_anggaran'");
		$crud->set_url_save("app_url+'realisasi_anggaran_detail/save_potensi_sisa_anggaran'");
		$crud = $crud->render();

		$data['crud'] = $crud;
		$data['tahun_ini'] = $tahun_ini;
		
		$view = $this->load->view('realisasi_anggaran_detail/v_format_potensi_sisa_anggaran', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_potensi_sisa_anggaran() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_ini = date('Y');


		$query = $this->get_query_potensi_sisa_anggaran($tahun_ini);
		// echo "<pre>"; print_r($query); die;

		$crud->set_manual_query($query);

		$crud->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_filter('nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_sorting('nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_select_align(' , , right');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_id('paket_belanja');
		// $crud->set_custom_first_column(true);
		
		$crud->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, nilai_anggaran');
		$crud->set_custom_style('custom_style_potensi_sisa_anggaran');
		$crud->set_table('paket_belanja');
		echo $crud->get_table();
	}

	function custom_style_potensi_sisa_anggaran($key, $value, $data) {
		
		if ($key == 'nilai_anggaran') {
			return az_thousand_separator($value);
		}

		return $value;
	}

	function get_query_potensi_sisa_anggaran($tahun_ini, $query_total = false) {
		$total_anggaran = 0;

		$this->db->where('verification.status = 1');
		$this->db->where('verification.verification_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');
		$this->db->where('YEAR(verification.confirm_verification_date) = "'.$tahun_ini.'" ');

		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$this->db->join('program', 'program.idprogram = paket_belanja.idprogram');
		
		$this->db->group_by('verification.idverification, transaction_detail.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, nilai_anggaran');
		
		$this->db->select('verification.idverification, transaction_detail.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, verification.total_anggaran as nilai_anggaran');
		
		$query = $this->db->get('verification');
		$last_query = $this->db->last_query();
		
		if ($query_total) {
			foreach ($query->result() as $key => $value) {
				$total_anggaran += $value->nilai_anggaran;
			}

			$query = $total_anggaran;
		}
		else {
			
			$query = 'select * from (' . $last_query . ') new_query';
		}

		// echo "<pre>"; print_r($query);die;

		return $query;

	}
	

	// PENDAPATAN DARI BLUD
	public function blud() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$tahun_ini = date('Y');
		$sumber_dana = "Pendapatan dari BLUD";

		$total_pendapatan_blud = $this->get_query_sumber_dana($tahun_ini, $sumber_dana, true);

		$crud->set_btn_top_custom("
					<div class='btn btn-default' style='background-color:#ff5722; color:#FFF;'>Total Anggaran : Rp. ".az_thousand_separator($total_pendapatan_blud)."</div>
			");

		$crud->set_column(array('#', "Program", "Paket Belanja", "Nilai Realisasi"));
		$crud->set_id($this->controller);
		// $crud->set_default_url(true);
		$crud->set_default_url(false);
		$crud->set_btn_add(false);
		$crud->set_url("app_url+'realisasi_anggaran_detail/get_pendapatan_dari_blud'");
		$crud->set_url_edit("app_url+'realisasi_anggaran_detail/edit_pendapatan_dari_blud'");
		$crud->set_url_delete("app_url+'realisasi_anggaran_detail/delete_pendapatan_dari_blud'");
		$crud->set_url_save("app_url+'realisasi_anggaran_detail/save_pendapatan_dari_blud'");
		$crud = $crud->render();

		$data['crud'] = $crud;
		$data['tahun_ini'] = $tahun_ini;
		$data['sumber_dana'] = $sumber_dana;
		
		$view = $this->load->view('realisasi_anggaran_detail/v_format_sumber_dana', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_pendapatan_dari_blud() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_ini = date('Y');
		$sumber_dana = "Pendapatan dari BLUD";

		$query = $this->get_query_sumber_dana($tahun_ini, $sumber_dana, false);
		// echo "<pre>"; print_r($query); die;

		$crud->set_manual_query($query);

		$crud->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_filter('nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_sorting('nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_select_align(' , , right');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_id('paket_belanja');
		// $crud->set_custom_first_column(true);
		
		$crud->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_custom_style('custom_style_sumber_dana');
		$crud->set_table('paket_belanja');
		echo $crud->get_table();
	}

	public function dbh() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$tahun_ini = date('Y');
		$sumber_dana = "DBH Cukai Hasil Tembakau (CHT)";

		$total_pendapatan_blud = $this->get_query_sumber_dana($tahun_ini, $sumber_dana, true);

		$crud->set_btn_top_custom("
					<div class='btn btn-default' style='background-color:#ff5722; color:#FFF;'>Total Anggaran : Rp. ".az_thousand_separator($total_pendapatan_blud)."</div>
			");

		$crud->set_column(array('#', "Program", "Paket Belanja", "Nilai Realisasi"));
		$crud->set_id($this->controller);
		// $crud->set_default_url(true);
		$crud->set_default_url(false);
		$crud->set_btn_add(false);
		$crud->set_url("app_url+'realisasi_anggaran_detail/get_dbh'");
		$crud->set_url_edit("app_url+'realisasi_anggaran_detail/edit_dbh'");
		$crud->set_url_delete("app_url+'realisasi_anggaran_detail/delete_dbh'");
		$crud->set_url_save("app_url+'realisasi_anggaran_detail/save_dbh'");
		$crud = $crud->render();

		$data['crud'] = $crud;
		$data['tahun_ini'] = $tahun_ini;
		$data['sumber_dana'] = $sumber_dana;
		
		$view = $this->load->view('realisasi_anggaran_detail/v_format_sumber_dana', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_dbh() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_ini = date('Y');
		$sumber_dana = "DBH Cukai Hasil Tembakau (CHT)";

		$query = $this->get_query_sumber_dana($tahun_ini, $sumber_dana, false);
		// echo "<pre>"; print_r($query); die;

		$crud->set_manual_query($query);

		$crud->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_filter('nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_sorting('nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_select_align(' , , right');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_id('paket_belanja');
		// $crud->set_custom_first_column(true);
		
		$crud->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, total_realisasi');
		$crud->set_custom_style('custom_style_sumber_dana');
		$crud->set_table('paket_belanja');
		echo $crud->get_table();
	}

	function custom_style_sumber_dana($key, $value, $data) {
		
		if ($key == 'total_realisasi') {
			return az_thousand_separator($value);
		}

		return $value;
	}

	function get_query_sumber_dana($tahun_ini, $sumber_dana, $query_total = false) {
		$total_realisasi = 0;		

		$this->db->where('sumber_dana.nama_sumber_dana = "'.$sumber_dana.'" ');
		$this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('npd.status = 1 ');
		$this->db->where('npd_detail.status = 1 ');
		$this->db->where('verification.status = 1 ');
		$this->db->where('verification.verification_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');
		$this->db->where('verification_detail.status = 1 ');
		$this->db->where('transaction.status = 1 ');
		$this->db->where('transaction.transaction_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('transaction_detail.`status` = 1 ');
		$this->db->where('YEAR(transaction_detail.created) = "'.$tahun_ini.'" ');

		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');

		
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = transaction_detail.iduraian');
		$this->db->join('sumber_dana', 'sumber_dana.idsumber_dana = sub_kategori.idsumber_dana');

		// $this->db->group_by('paket_belanja.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja');
		$this->db->select('paket_belanja.idpaket_belanja, program.nama_program, paket_belanja.nama_paket_belanja, total AS total_realisasi');
		$query = $this->db->get('npd');
		$last_query = $this->db->last_query();
		// echo "<pre>"; print_r($last_query); die;
		
		if ($query_total) {
			foreach ($query->result() as $key => $value) {
				$total_realisasi += $value->total_realisasi;
			}

			$query = $total_realisasi;
		}
		else {
			
			$query = 'select * from (' . $last_query . ') new_query';
		}

		// echo "<pre>"; print_r($query);die;

		return $query;

	}
}
