<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends AZ_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        az_check_auth('dashboard');
		$this->load->helper('az_crud');
		$this->load->helper('az_config');
		$this->load->helper('az_core');
    }

	public function index(){
		$this->load->library('AZApp');
		$app = $this->azapp;
		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$app->set_data_header($data_header);

		$total_anggaran = 0;
		$total_realisasi = 0;
		$sudah_dibayar = 0;
		$menunggu_pembayaran = 0;
		$npd = 0;
		$sudah_diverifikasi = 0;
		$menunggu_verifikasi = 0;
		$kontrak_pengadaan = 0;
		$proses_pengadaan = 0;
		$belum_direalisasi = 0;
		
		$tahun_ini = date('Y');
		// $tahun_ini = "2024";


		// GRAFIK REALISASI ANGGARAN
		$grafik_realisasi_anggaran = $this->grafik_realisasi_anggaran($tahun_ini);
		$sudah_dibayar = $grafik_realisasi_anggaran['sudah_dibayar'];
		$menunggu_pembayaran = $grafik_realisasi_anggaran['menunggu_pembayaran'];
		$npd = $grafik_realisasi_anggaran['npd'];
		$sudah_diverifikasi = $grafik_realisasi_anggaran['sudah_diverifikasi'];
		$menunggu_verifikasi = $grafik_realisasi_anggaran['menunggu_verifikasi'];
		$kontrak_pengadaan = $grafik_realisasi_anggaran['kontrak_pengadaan'];
		$proses_pengadaan = $grafik_realisasi_anggaran['proses_pengadaan'];
		$belum_direalisasi = $grafik_realisasi_anggaran['belum_direalisasi'];



		// GRAFIK POTENSI SISA ANGGARAN
		$grafik_potensi_sisa_anggaran = $this->grafik_potensi_sisa_anggaran($tahun_ini);
		$total_anggaran = $grafik_potensi_sisa_anggaran['total_anggaran_tahun_ini'];
		$realisasi_anggaran = $sudah_dibayar;


		// GRAFIK REALISASI ANGGARAN PER SUMBER DANA
		$grafik_sumber_dana = $this->grafik_sumber_dana($tahun_ini);
		$dbh = $grafik_sumber_dana['dbh'];
		$blud = $grafik_sumber_dana['blud'];


		// GRAFIK PERBANDINGAN TARGET & REALISASI PER BULAN
		$target_per_bulan = [];
		$realisasi_per_bulan = [];

		for ($bulan = 1; $bulan <= 12; $bulan++) {

		    // Target: total anggaran paket belanja yang aktif dan OK
			$this->db->where('YEAR(pb.created)', $tahun_ini);
			$this->db->where('pb.status_paket_belanja = "OK" ');
			$this->db->where('pb.is_active = 1');
			$this->db->where('pb.status = 1');
			$this->db->where('pbd.status = 1');
			$this->db->where('pbds.status = 1');
			$this->db->where('pbds.volume IS NOT NULL');
			$this->db->where('pbds.idsatuan IS NOT NULL');
			$this->db->where('pbds.harga_satuan IS NOT NULL');
			$this->db->where('pbds.jumlah IS NOT NULL');					
					
			$this->db->join('paket_belanja_detail pbd', 'pbd.idpaket_belanja = pb.idpaket_belanja');
			$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = pbd.idakun_belanja');
			$this->db->join('paket_belanja_detail_sub pbds', 
					'pbds.idpaket_belanja_detail = pbd.idpaket_belanja_detail 
				OR pbds.is_idpaket_belanja_detail_sub IN (
					SELECT sub.idpaket_belanja_detail_sub 
					FROM paket_belanja_detail_sub sub 
					WHERE sub.idpaket_belanja_detail = pbd.idpaket_belanja_detail
				)', 
				'left', false // false = tidak di-escape (untuk subquery)
			);

			if ($bulan == 1) {
				$this->db->select('sum(rak_jumlah_januari) as nilai_anggaran');	
			}
			else if ($bulan == 2) {
				$this->db->select('sum(rak_jumlah_februari) as nilai_anggaran');	
			}
			else if ($bulan == 3) {
				$this->db->select('sum(rak_jumlah_maret) as nilai_anggaran');	
			}
			else if ($bulan == 4) {
				$this->db->select('sum(rak_jumlah_april) as nilai_anggaran');	
			}
			else if ($bulan == 5) {
				$this->db->select('sum(rak_jumlah_mei) as nilai_anggaran');	
			}
			else if ($bulan == 6) {
				$this->db->select('sum(rak_jumlah_juni) as nilai_anggaran');	
			}
			else if ($bulan == 7) {
				$this->db->select('sum(rak_jumlah_juli) as nilai_anggaran');	
			}
			else if ($bulan == 8) {
				$this->db->select('sum(rak_jumlah_agustus) as nilai_anggaran');	
			}
			else if ($bulan == 9) {
				$this->db->select('sum(rak_jumlah_september) as nilai_anggaran');	
			}
			else if ($bulan == 10) {
				$this->db->select('sum(rak_jumlah_oktober) as nilai_anggaran');	
			}
			else if ($bulan == 11) {
				$this->db->select('sum(rak_jumlah_november) as nilai_anggaran');	
			}
			else if ($bulan == 12) {
				$this->db->select('sum(rak_jumlah_desember) as nilai_anggaran');	
			}
			$pb = $this->db->get('paket_belanja pb');
			$target = $pb->row()->nilai_anggaran;
			$target_per_bulan[] = floatval($target);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			
		    // Realisasi: total realisasi transaksi pada bulan tsb
		    $this->db->where('status', 1);
		    $this->db->where('transaction_status !=', 'DRAFT');
		    $this->db->where('YEAR(transaction_date)', $tahun_ini);
		    $this->db->where('MONTH(transaction_date)', $bulan);
		    $this->db->select_sum('total_realisasi');
		    $trx = $this->db->get('transaction');
		    $realisasi = $trx->row()->total_realisasi;
			// echo "<pre>"; print_r($this->db->last_query()); die;
			
		    $realisasi_per_bulan[] = floatval($realisasi);
		}

		// Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan
		$arr_TargetPerBulan = array();
		$arr_RealisasiPerBulan = array();

		if (strlen($total_anggaran) > 0 && $total_anggaran != 0) {
			$grafik_capaian_target_realisasi = $this->grafik_capaian_target_realisasi($tahun_ini, $total_anggaran);
			$arr_TargetPerBulan = $grafik_capaian_target_realisasi['arr_TargetPerBulan'];
			$arr_RealisasiPerBulan = $grafik_capaian_target_realisasi['arr_RealisasiPerBulan'];
		}

		// echo "<pre>"; print_r($arr_TargetPerBulan); echo "<br>";
		// echo "<pre>"; print_r($arr_RealisasiPerBulan); die();


		// TABLE PAKET BELANJA YANG BELUM TEREALISASI
		$crud_table = $app->add_crud();
		$crud_table->set_column(array('#', "Program", "Paket Belanja", "Nilai Anggaran"));
		$crud_table->set_th_class(array('', '', '', ''));
		$crud_table->set_id('idpaket_belanja');
		$crud_table->set_default_url(false);
		$crud_table->set_btn_add(false);

		$crud_table->set_url("app_url+'home/get_paket_belanja/".$tahun_ini."'");
		$crud_table->set_url_edit("app_url+'home/edit_paket_belanja'");
		$crud_table->set_url_delete("app_url+'home/delete_paket_belanja'");
		$crud_table->set_url_save("app_url+'home/save_paket_belanja'");
		// $crud_table->set_callback_table_complete('callback_check_request_table();');
		$belum_terealisasi = $crud_table->render();
		
		$data = array(
			'tahun_ini' => $tahun_ini,
			'total_anggaran_tahun_ini' => floatval($total_anggaran),
			'realisasi_anggaran_tahun_ini' => floatval($realisasi_anggaran),
			'sudah_dibayar' => floatval($sudah_dibayar),
			'menunggu_pembayaran' => floatval($menunggu_pembayaran),
			'npd' => floatval($npd),
			'sudah_diverifikasi' => floatval($sudah_diverifikasi),
			'menunggu_verifikasi' => floatval($menunggu_verifikasi),
			'kontrak_pengadaan' => floatval($kontrak_pengadaan),
			'proses_pengadaan' => floatval($proses_pengadaan),
			'belum_direalisasi' => floatval($belum_direalisasi),
			'dbh' => floatval($dbh),
			'blud' => floatval($blud),
			'target_per_bulan' => $target_per_bulan,
			'realisasi_per_bulan' => $realisasi_per_bulan,
			'belum_terealisasi' => $belum_terealisasi,
			'capaian_target_per_bulan' => $arr_TargetPerBulan,
			'capaian_realisasi_per_bulan' => $arr_RealisasiPerBulan,
		);
		// echo "<pre>"; print_r($data); die;

		$view = $this->load->view('home/v_home', $data, true);
		$app->add_content($view);

		// $js = az_add_js('home/vjs_home');
		// $app->add_js($js);

		echo $app->render();	
	}

	function grafik_realisasi_anggaran($tahun_ini) {
		$sudah_dibayar = 0;
		$menunggu_pembayaran = 0;
		$npd = 0;
		$sudah_diverifikasi = 0;
		$menunggu_verifikasi = 0;
		$kontrak_pengadaan = 0;
		$proses_pengadaan = 0;
		$belum_direalisasi = 0;


		// Sudah Dibayar
			$this->db->where('npd.status', 1);
			$this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
			$this->db->where('YEAR(npd.confirm_payment_date) = "'.$tahun_ini.'" ');
			$this->db->select('sum(total_pay) as total_yang_sudah_dibayar');
			$npd_pay = $this->db->get('npd');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($npd_pay->num_rows() > 0) {
				$sudah_dibayar = $npd_pay->row()->total_yang_sudah_dibayar;
			}


		// Menunggu Pembayaran
			$this->db->where('npd.status', 1);
			$this->db->where('npd.npd_status = "MENUNGGU PEMBAYARAN" ');
			$this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');
			$this->db->select('sum(total_anggaran) as total_yang_menunggu_pembayaran');
			$npd_before_pay = $this->db->get('npd');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($npd_before_pay->num_rows() > 0) {
				$menunggu_pembayaran = $npd_before_pay->row()->total_yang_menunggu_pembayaran;
			}


		// NPD
			$this->db->where('npd.status', 1);
			$this->db->where('npd.npd_status = "INPUT NPD" ');
			$this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');
			$this->db->select('sum(total_anggaran) as total_input_npd');
			$npd_input = $this->db->get('npd');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($npd_input->num_rows() > 0) {
				$npd = $npd_input->row()->total_input_npd;
			}
		
		
		// Sudah Diverifikasi
			$this->db->where('verification.status', 1);
			$this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
			$this->db->where('YEAR(verification.confirm_verification_date) = "'.$tahun_ini.'" ');
			$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
			$this->db->select('sum(total_realization) as total_verif');
			$verification = $this->db->get('verification');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($verification->num_rows() > 0) {
				$sudah_diverifikasi = $verification->row()->total_verif;
			}


		// Menunggu Verifikasi
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization.realization_status = "MENUNGGU VERIFIKASI" ');
			$this->db->where('YEAR(budget_realization.realization_date) = "'.$tahun_ini.'" ');
			$this->db->select('sum(total_realization) as total_menunggu');
			$budget_realization = $this->db->get('budget_realization');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($budget_realization->num_rows() > 0) {
				$menunggu_verifikasi = $budget_realization->row()->total_menunggu;
			}
		

		// Kontrak Pengadaan
			$this->db->where('contract.status', 1);
			$this->db->where('contract.contract_status = "KONTRAK PENGADAAN" ');
			$this->db->where('YEAR(contract.contract_date) = "'.$tahun_ini.'" ');
			$this->db->join('contract_detail', 'contract_detail.idcontract = contract.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->select('sum(total_budget) as total_contract');
			$contract = $this->db->get('contract');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($contract->num_rows() > 0) {
				$kontrak_pengadaan = $contract->row()->total_contract;
			}
		

		// Proses Pengadaan
			$this->db->where('purchase_plan.status', 1);
			$this->db->where('purchase_plan.purchase_plan_status = "PROSES PENGADAAN" ');
			$this->db->where('YEAR(purchase_plan.purchase_plan_date) = "'.$tahun_ini.'" ');
			$this->db->select('sum(total_budget) as total_pengadaan');
			$purchase_plan = $this->db->get('purchase_plan');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($purchase_plan->num_rows() > 0) {
				$proses_pengadaan = $purchase_plan->row()->total_pengadaan;
			}


		// Belum Direalisasi
			// ambil data paket belanja yang sudah masuk di rencana pengadaan
			$this->db->where('purchase_plan.status', 1);
			$this->db->where('purchase_plan.purchase_plan_status != "DRAFT" ');
			$this->db->where('purchase_plan_detail.status', 1);
			$this->db->where('YEAR(purchase_plan.purchase_plan_date) = "'.$tahun_ini.'" ');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
			$this->db->group_by('purchase_plan_detail.idpaket_belanja');
			$this->db->select('purchase_plan_detail.idpaket_belanja');
			$pp = $this->db->get('purchase_plan');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			$arr_idpaket_belanja = array();
			foreach ($pp->result() as $key => $value) {
				$arr_idpaket_belanja[] = $value->idpaket_belanja;
			}
			$data_idpaket_belanja = '"'.implode(' ", " ', $arr_idpaket_belanja).'"';

			// hitung total nilai anggaran di paket belanja
			$this->db->where('paket_belanja.status', 1);
			$this->db->where('paket_belanja.status_paket_belanja = "OK" ');
			$this->db->where('paket_belanja.is_active', 1);
			$this->db->where('YEAR(paket_belanja.created) = "'.$tahun_ini.'" ');
			$this->db->where('paket_belanja.idpaket_belanja NOT IN ('.$data_idpaket_belanja.') ');
			$this->db->where('paket_belanja.nilai_anggaran > 0');
			$this->db->select('sum(nilai_anggaran) as total_yang_belum_direalisasi');
			$paket_belanja = $this->db->get('paket_belanja');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($paket_belanja->num_rows() > 0) {
				$belum_direalisasi = $paket_belanja->row()->total_yang_belum_direalisasi;
			}


		$return = array(
			'sudah_dibayar' => floatval($sudah_dibayar),
			'menunggu_pembayaran' => floatval($menunggu_pembayaran),
			'npd' => floatval($npd),
			'sudah_diverifikasi' => floatval($sudah_diverifikasi),
			'menunggu_verifikasi' => floatval($menunggu_verifikasi),
			'kontrak_pengadaan' => floatval($kontrak_pengadaan),
			'proses_pengadaan' => floatval($proses_pengadaan),
			'belum_direalisasi' => floatval($belum_direalisasi),
		);

		return $return;
	}

	function grafik_potensi_sisa_anggaran($tahun_ini) {
		$total_anggaran = 0;

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
		$pb = $this->db->get('paket_belanja');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		if ($pb->num_rows() > 0) {
			$total_anggaran = $pb->row()->nilai_anggaran;
		}

		$return = array(
			'total_anggaran_tahun_ini' => floatval($total_anggaran),
		);

		return $return;
	}

	function grafik_sumber_dana($tahun_ini) {
		$dbh = 0;
		$blud = 0;


		$this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('npd.status', 1);
		$this->db->where('npd_detail.status', 1);
		$this->db->where('verification.status', 1);
		$this->db->where('verification.verification_status != "DRAFT" ');
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization.realization_status != "DRAFT" ');
		$this->db->where('budget_realization_detail.status', 1);
		$this->db->where('sub_kategori.status', 1);
		$this->db->where('sumber_dana.status', 1);
		$this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');

		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification'	);
		$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization'	);
		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization'	);
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = budget_realization_detail.idsub_kategori');
		$this->db->join('sumber_dana', 'sumber_dana.idsumber_dana = sub_kategori.idsumber_dana');

		$this->db->group_by('nama_sumber_dana');
		$this->db->select('SUM(budget_realization_detail.total_realization_detail) AS total_sumber_dana, nama_sumber_dana');
		$npd = $this->db->get('npd');
		// echo "<pre>"; print_r($this->db->last_query());die;

		foreach ($npd->result() as $key => $value) {
			$sumber_dana = $value->nama_sumber_dana;
			$total_sumber_dana = $value->total_sumber_dana;

			if ($sumber_dana == "DBH Cukai Hasil Tembakau (CHT)") {
				$dbh = $total_sumber_dana;
			}
			else if ($sumber_dana == "Pendapatan dari BLUD") {
				$blud = $total_sumber_dana;
			}
		}

		$return = array(
			'dbh' => floatval($dbh),
			'blud' => floatval($blud),
		);

		// echo "<pre>"; print_r($return);die;

		return $return;
	}

	function grafik_capaian_target_realisasi($tahun_ini, $total_anggaran) {
		$is_develop = false;
		$januari 	= 0;
		$februari 	= 0;
		$maret 		= 0;
		$april 		= 0;
		$mei 		= 0;
		$juni 		= 0;
		$juli 		= 0;
		$agustus 	= 0;
		$september 	= 0;
		$oktober 	= 0;
		$november 	= 0;
		$desember 	= 0;
		$cr_januari 	= 0;
		$cr_februari 	= 0;
		$cr_maret 		= 0;
		$cr_april 		= 0;
		$cr_mei 		= 0;
		$cr_juni 		= 0;
		$cr_juli 		= 0;
		$cr_agustus 	= 0;
		$cr_september 	= 0;
		$cr_oktober 	= 0;
		$cr_november 	= 0;
		$cr_desember 	= 0;

		// ambil capaian target per bulan
		$query_develop = "";
		if ($is_develop) {
			$query_develop = ",
				pbd.idpaket_belanja_detail,
				COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS detail_sub_id,
				COALESCE(pbds_child.idsub_kategori, pbds_parent.idsub_kategori) AS idsub_kategori,
				COALESCE(pbds_child.volume, pbds_parent.volume) AS volume,
				COALESCE(pbds_child.idsatuan, pbds_parent.idsatuan) AS idsatuan,
				COALESCE(pbds_child.harga_satuan, pbds_parent.harga_satuan) AS harga_satuan,
				COALESCE(pbds_child.jumlah, pbds_parent.jumlah) AS jumlah";
		}
		
		$this->db->where('YEAR(pb.created) = "'.$tahun_ini.'" ');
		$this->db->where('pb.status', 1);
		$this->db->where('pb.status_paket_belanja = "OK" ');
		$this->db->where('pbd.status', 1);
		$this->db->join('paket_belanja_detail pbd', 'paket_belanja_detail pbd ON pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');

		if (!$is_develop) {
			$this->db->group_by('pb.idpaket_belanja, pb.nama_paket_belanja, idpaket_belanja_detail_sub, rak_jumlah_januari, rak_jumlah_februari, rak_jumlah_maret, rak_jumlah_april, rak_jumlah_mei, rak_jumlah_juni, rak_jumlah_juli, rak_jumlah_agustus, rak_jumlah_september, rak_jumlah_oktober, rak_jumlah_november, rak_jumlah_desember');
		}

		$this->db->select('pb.idpaket_belanja,
			pb.nama_paket_belanja,
			COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS idpaket_belanja_detail_sub,
			COALESCE(pbds_child.rak_jumlah_januari, pbds_parent.rak_jumlah_januari) AS rak_jumlah_januari,
			COALESCE(pbds_child.rak_jumlah_februari, pbds_parent.rak_jumlah_februari) AS rak_jumlah_februari,
			COALESCE(pbds_child.rak_jumlah_maret, pbds_parent.rak_jumlah_maret) AS rak_jumlah_maret,
			COALESCE(pbds_child.rak_jumlah_april, pbds_parent.rak_jumlah_april) AS rak_jumlah_april,
			COALESCE(pbds_child.rak_jumlah_mei, pbds_parent.rak_jumlah_mei) AS rak_jumlah_mei,
			COALESCE(pbds_child.rak_jumlah_juni, pbds_parent.rak_jumlah_juni) AS rak_jumlah_juni,
			COALESCE(pbds_child.rak_jumlah_juli, pbds_parent.rak_jumlah_juli) AS rak_jumlah_juli,
			COALESCE(pbds_child.rak_jumlah_agustus, pbds_parent.rak_jumlah_agustus) AS rak_jumlah_agustus,
			COALESCE(pbds_child.rak_jumlah_september, pbds_parent.rak_jumlah_september) AS rak_jumlah_september,
			COALESCE(pbds_child.rak_jumlah_oktober, pbds_parent.rak_jumlah_oktober) AS rak_jumlah_oktober,
			COALESCE(pbds_child.rak_jumlah_november, pbds_parent.rak_jumlah_november) AS rak_jumlah_november,
			COALESCE(pbds_child.rak_jumlah_desember, pbds_parent.rak_jumlah_desember) AS rak_jumlah_desember'.$query_develop);
		$capaian_target = $this->db->get('paket_belanja pb');
		// echo "<pre>"; print_r($this->db->last_query()); die;		

		// akumulasi semua data per bulan
		foreach ($capaian_target->result() as $key => $value) {
			$januari 	+= $value->rak_jumlah_januari;
			$februari 	+= $value->rak_jumlah_februari;
			$maret 		+= $value->rak_jumlah_maret;
			$april 		+= $value->rak_jumlah_april;
			$mei 		+= $value->rak_jumlah_mei;
			$juni 		+= $value->rak_jumlah_juni;
			$juli 		+= $value->rak_jumlah_juli;
			$agustus 	+= $value->rak_jumlah_agustus;
			$september 	+= $value->rak_jumlah_september;
			$oktober 	+= $value->rak_jumlah_oktober;
			$november 	+= $value->rak_jumlah_november;
			$desember 	+= $value->rak_jumlah_desember;

			// var_dump($value->rak_jumlah_januari); echo "<br>";
		}

		// echo "<pre>"; print_r($januari.' <br> '.$februari.' <br> '.$maret.' <br> '.$april.' <br> '.$mei.' <br> '.$juni.' <br> '.$juli.' <br> '.$agustus.' <br> '.$september.' <br> '.$oktober.' <br> '.$november.' <br> '.$desember); die;


		// ambil capaian realisasi per bulan
		$this->db->where('STATUS', 1);
		$this->db->where('transaction_status != "DRAFT" ');
		$this->db->where('YEAR(transaction_date) = "2025" ');
		$this->db->group_by('YEAR(transaction_date), MONTH(transaction_date)');
		$this->db->order_by('YEAR(transaction_date), MONTH(transaction_date)');
		$this->db->select('YEAR(transaction_date), MONTH(transaction_date) AS bulan_realisasi, SUM(total_realisasi) AS total');
		$capaian_realisasi = $this->db->get('transaction');

		foreach ($capaian_realisasi->result() as $key => $value) {
			if ($value->bulan_realisasi == 1) {
				$cr_januari = $value->total;
			}
			else if ($value->bulan_realisasi == 2) {
				$cr_februari = $value->total;
			}
			else if ($value->bulan_realisasi == 3) {
				$cr_maret = $value->total;
			}
			else if ($value->bulan_realisasi == 4) {
				$cr_april = $value->total;
			}
			else if ($value->bulan_realisasi == 5) {
				$cr_mei = $value->total;
			}
			else if ($value->bulan_realisasi == 6) {
				$cr_juni = $value->total;
			}
			else if ($value->bulan_realisasi == 7) {
				$cr_juli = $value->total;
			}
			else if ($value->bulan_realisasi == 8) {
				$cr_agustus = $value->total;
			}
			else if ($value->bulan_realisasi == 9) {
				$cr_september = $value->total;
			}
			else if ($value->bulan_realisasi == 10) {
				$cr_oktober = $value->total;
			}
			else if ($value->bulan_realisasi == 11) {
				$cr_november = $value->total;
			}
			else if ($value->bulan_realisasi == 12) {
				$cr_desember = $value->total;
			}
		}


		// set persentase capaian target per bulan
		$arr_TargetPerBulan = array();
		$arr_RealisasiPerBulan = array();
		for ($bulan = 1; $bulan <= 12; $bulan++) {
			if ($bulan == 1) {
				// target
				// var_dump($januari); echo "<br>";
				$januari = $januari;
				$persen_januari = round( ($januari / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_januari);

				// realisasi
				$cr_januari = $cr_januari;
				$persen_cr_januari = round( ($cr_januari / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_januari);
			}
			else if ($bulan == 2) {
				// target
				// var_dump($januari.' + '.$februari); echo "<br>";
				$februari = $januari + $februari;
				$persen_februari = round( ($februari / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_februari);

				// resalisasi
				$cr_februari = $cr_januari + $cr_februari;
				$persen_cr_februari = round( ($cr_februari / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_februari);
			}
			else if ($bulan == 3) {
				// target
				// var_dump($februari.' + '.$maret); echo "<br>";
				$maret = $februari + $maret;
				$persen_maret = round( ($maret / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_maret);

				// realisasi
				$cr_maret = $cr_februari + $cr_maret;
				$persen_cr_maret = round( ($cr_maret / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_maret);
			}
			else if ($bulan == 4) {
				// target
				// var_dump($maret.' + '.$april); echo "<br>";
				$april = $maret + $april;
				$persen_april = round( ($april / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_april);

				// realisasi
				$cr_april = $cr_maret + $cr_april;
				$persen_cr_april = round( ($cr_april / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_april);
			}
			else if ($bulan == 5) {
				// target
				// var_dump($april.' + '.$mei); echo "<br>";
				$mei = $april + $mei;
				$persen_mei = round( ($mei / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_mei);

				// realisasi
				$cr_mei = $cr_april + $cr_mei;
				$persen_cr_mei = round( ($cr_mei / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_mei);
			}
			else if ($bulan == 6) {
				// target
				// var_dump($mei.' + '.$juni); echo "<br>";
				$juni = $mei + $juni;
				$persen_juni = round( ($juni / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_juni);

				// realisasi
				$cr_juni = $cr_mei + $cr_juni;
				$persen_cr_juni = round( ($cr_juni / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_juni);
			}
			else if ($bulan == 7) {
				// target
				// var_dump($juni.' + '.$juli); echo "<br>";
				$juli = $juni + $juli;
				$persen_juli = round( ($juli / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_juli);

				// realisasi
				$cr_juli = $cr_juni + $cr_juli;
				$persen_cr_juli = round( ($cr_juli / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_juli);
			}
			else if ($bulan == 8) {
				// target
				// var_dump($juli.' + '.$agustus); echo "<br>";
				$agustus = $juli + $agustus;
				$persen_agustus = round( ($agustus / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_agustus);

				// realisasi
				$cr_agustus = $cr_juli + $cr_agustus;
				$persen_cr_agustus = round( ($cr_agustus / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_agustus);
			}
			else if ($bulan == 9) {
				// target
				// var_dump($agustus.' + '.$september); echo "<br>";
				$september = $agustus + $september;
				$persen_september = round( ($september / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_september);

				// realisasi
				$cr_september = $cr_agustus + $cr_september;
				$persen_cr_september = round( ($cr_september / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_september);
			}
			else if ($bulan == 10) {
				// target
				// var_dump($september.' + '.$oktober); echo "<br>";
				$oktober = $september + $oktober;
				$persen_oktober = round( ($oktober / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_oktober);

				// realisasi
				$cr_oktober = $cr_september + $cr_oktober;
				$persen_cr_oktober = round( ($cr_oktober / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_oktober);
			}
			else if ($bulan == 11) {
				// target
				// var_dump($oktober.' + '.$november); echo "<br>";
				$november = $oktober + $november;
				$persen_november = round( ($november / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_november);

				// realisasi
				$cr_november = $cr_oktober + $cr_november;
				$persen_cr_november = round( ($cr_november / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_november);
			}
			else if ($bulan == 12) {
				// target
				// var_dump($november.' + '.$desember); die();
				$desember = $november + $desember;
				$persen_desember = round( ($desember / $total_anggaran) * 100);
				$arr_TargetPerBulan[] = floatval($persen_desember);

				// realisasi
				$cr_desember = $cr_november + $cr_desember;
				$persen_cr_desember = round( ($cr_desember / $total_anggaran) * 100);
				$arr_RealisasiPerBulan[] = floatval($persen_cr_desember);
			}
		}

		$ret = array(
			'arr_TargetPerBulan' => $arr_TargetPerBulan,
			'arr_RealisasiPerBulan' => $arr_RealisasiPerBulan,
		);

		return $ret;
	}

	function get_paket_belanja($tahun_ini) {

		$this->load->library('AZApp');
		$crud_table = $this->azapp->add_crud();

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
		// echo "<pre>"; print_r($query); die;

		$crud_table->set_manual_query($query);

		$crud_table->set_select_table('idpaket_belanja, nama_program, nama_paket_belanja, nilai_anggaran');
		$crud_table->set_filter('nama_program, nama_paket_belanja, nilai_anggaran');
		$crud_table->set_sorting('nama_program, nama_paket_belanja, nilai_anggaran');
		$crud_table->set_select_align(' , , right');
		$crud_table->set_edit(false);
		$crud_table->set_delete(false);
		$crud_table->set_id('paket_belanja');
		// $crud_table->set_custom_first_column(true);
		
		$crud_table->set_order_by('idpaket_belanja, nama_program, nama_paket_belanja, nilai_anggaran');
		$crud_table->set_custom_style('custom_style');
		$crud_table->set_table('paket_belanja');
		echo $crud_table->get_table();

		// SELECT 
		// pb.idpaket_belanja,
		// p.nama_program,
		// pb.nama_paket_belanja,
		// pb.nilai_anggaran

		// FROM paket_belanja pb
		// JOIN sub_kegiatan sk ON sk.idsub_kegiatan = pb.idsub_kegiatan
		// JOIN kegiatan k ON k.idkegiatan = sk.idkegiatan
		// JOIN program p ON p.idprogram = k.idprogram
		// JOIN paket_belanja_detail pbd ON pb.idpaket_belanja = pbd.idpaket_belanja
		// JOIN paket_belanja_detail_sub pbds
		// ON (
		// 	-- Ambil yang langsung dari detail (bukan anak dari kategori)
		// 	pbds.idpaket_belanja_detail = pbd.idpaket_belanja_detail
		// 	OR pbds.is_idpaket_belanja_detail_sub IN (
		// 		-- Ambil anak-anak dari sub-detail lain yang berelasi dengan detail
		// 		SELECT sub.idpaket_belanja_detail_sub
		// 		FROM paket_belanja_detail_sub sub
		// 		WHERE sub.idpaket_belanja_detail = pbd.idpaket_belanja_detail
		// 	)
		// )

		// -- WHERE: Semua kondisi penyaringan
		// WHERE 
		// YEAR(pb.created) = 2025
		// AND pb.status_paket_belanja = 'OK'
		// AND pb.is_active = 1
		// AND pb.status = 1
		// AND pbd.status = 1
		// AND `pbds`.`status` = 1
		// AND pbds.volume IS NOT NULL
		// AND pbds.idsatuan IS NOT NULL
		// AND pbds.harga_satuan IS NOT NULL
		// AND pbds.jumlah IS NOT NULL
		// GROUP BY pb.idpaket_belanja,
		// p.nama_program,
		// pb.nama_paket_belanja,
		// pb.nilai_anggaran

		// return $paket_belanja;
	}

	function custom_style($key, $value, $data) {
		
		if ($key == 'nilai_anggaran') {
			return az_thousand_separator($value);
		}

		return $value;
	}
}