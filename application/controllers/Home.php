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
		$this->load->model('dashboard_model','dashboard');
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
		
		$arr_pengeluaran 	= $this->pengeluaran_anggaran($tahun_ini);
		$arr_pemasukan 		= $this->pemasukan_anggaran($tahun_ini);

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
		$belum_terealisasi = $crud_table->render();
		
		
		$data = array(
			'tahun_ini' 					=> $tahun_ini,
			
			// PENGELUARAN ANGGARAN
			'total_anggaran_tahun_ini' 		=> $arr_pengeluaran['total_anggaran'],
			'realisasi_anggaran_tahun_ini' 	=> $arr_pengeluaran['realisasi_anggaran'],
			'sudah_dibayar' 				=> $arr_pengeluaran['sudah_dibayar'],
			'menunggu_pembayaran' 			=> $arr_pengeluaran['menunggu_pembayaran'],
			'npd' 							=> $arr_pengeluaran['npd'],
			'sudah_diverifikasi' 			=> $arr_pengeluaran['sudah_diverifikasi'],
			'menunggu_verifikasi' 			=> $arr_pengeluaran['menunggu_verifikasi'],
			'kontrak_pengadaan' 			=> $arr_pengeluaran['kontrak_pengadaan'],
			'proses_pengadaan' 				=> $arr_pengeluaran['proses_pengadaan'],
			'belum_direalisasi' 			=> $arr_pengeluaran['belum_direalisasi'],
			'dbh' 							=> $arr_pengeluaran['dbh'],
			'blud' 							=> $arr_pengeluaran['blud'],
			'target_dbh' 					=> $arr_pengeluaran['target_dbh'],
			'target_blud' 					=> $arr_pengeluaran['target_blud'],
			'target_per_bulan' 				=> $arr_pengeluaran['target_per_bulan'],
			'realisasi_per_bulan' 			=> $arr_pengeluaran['realisasi_per_bulan'],
			'belum_terealisasi' 			=> $belum_terealisasi,
			'capaian_target_per_bulan' 		=> $arr_pengeluaran['arr_TargetPerBulan'],
			'capaian_realisasi_per_bulan' 	=> $arr_pengeluaran['arr_RealisasiPerBulan'],

			// PEMASUKAN ANGGARAN
			'target_tahun' 					=> $arr_pemasukan['target_tahun'],
			'target_bulan' 					=> $arr_pemasukan['target_bulan'],
			'realisasi_tahun' 				=> $arr_pemasukan['realisasi_tahun'],
			'realisasi_bulan' 				=> $arr_pemasukan['realisasi_bulan'],
			'realisasi_hari' 				=> $arr_pemasukan['realisasi_hari'],
			'persen_tahun' 					=> $arr_pemasukan['persen_tahun'],
			'persen_bulan' 					=> $arr_pemasukan['persen_bulan'],
			'persen_hari' 					=> $arr_pemasukan['persen_hari'],
			'jumlah_sts_tahun' 				=> $arr_pemasukan['jumlah_sts_tahun'],
			'jumlah_sts_bulan' 				=> $arr_pemasukan['jumlah_sts_bulan'],
			'jumlah_sts_hari' 				=> $arr_pemasukan['jumlah_sts_hari'],
			'rata_harian' 					=> $arr_pemasukan['rata_harian'],
			'mutasi_masuk' 					=> $arr_pemasukan['mutasi_masuk'],
			'mutasi_keluar' 				=> $arr_pemasukan['mutasi_keluar'],
			'mutasi_pindah' 				=> $arr_pemasukan['mutasi_pindah'],
			'saldo_kas' 					=> $arr_pemasukan['saldo_kas'],
			'chart_bulanan' 				=> $arr_pemasukan['chart_bulanan'],
		);
		// echo "<pre>"; print_r($data); die;

		$view = $this->load->view('home/v_home', $data, true);
		$app->add_content($view);

		$js = az_add_js('home/vjs_home');
		$app->add_js($js);

		echo $app->render();	
	}

	function pengeluaran_anggaran($tahun_ini) {
		
		$grafik_potensi_sisa_anggaran = $this->dashboard->grafik_potensi_sisa_anggaran($tahun_ini);
		$total_anggaran = $grafik_potensi_sisa_anggaran['total_anggaran_tahun_ini'];

		
		// GRAFIK REALISASI ANGGARAN
		$grafik_realisasi_anggaran = $this->dashboard->grafik_realisasi_anggaran($tahun_ini, $total_anggaran);
		$sudah_dibayar = $grafik_realisasi_anggaran['sudah_dibayar'];
		$menunggu_pembayaran = $grafik_realisasi_anggaran['menunggu_pembayaran'];
		$npd = $grafik_realisasi_anggaran['npd'];
		$sudah_diverifikasi = $grafik_realisasi_anggaran['sudah_diverifikasi'];
		$menunggu_verifikasi = $grafik_realisasi_anggaran['menunggu_verifikasi'];
		$kontrak_pengadaan = $grafik_realisasi_anggaran['kontrak_pengadaan'];
		$proses_pengadaan = $grafik_realisasi_anggaran['proses_pengadaan'];
		$belum_direalisasi = $grafik_realisasi_anggaran['belum_direalisasi'];

		
		// GRAFIK POTENSI SISA ANGGARAN
		// $grafik_potensi_sisa_anggaran = $this->grafik_potensi_sisa_anggaran($tahun_ini);
		$total_anggaran = $grafik_potensi_sisa_anggaran['total_anggaran_tahun_ini'];
		$realisasi_anggaran = $sudah_dibayar;


		// GRAFIK REALISASI ANGGARAN PER SUMBER DANA
		$grafik_sumber_dana = $this->dashboard->grafik_sumber_dana($tahun_ini);
		$dbh = $grafik_sumber_dana['dbh'];
		$target_dbh = $grafik_sumber_dana['target_dbh'];
		$blud = $grafik_sumber_dana['blud'];
		$target_blud = $grafik_sumber_dana['target_blud'];


		// GRAFIK PERBANDINGAN TARGET & REALISASI PER BULAN
		$target_per_bulan = [];
		$realisasi_per_bulan = [];

		$target_per_bulan = $this->dashboard->get_target_per_bulan($tahun_ini);
		$realisasi_per_bulan = $this->dashboard->get_realisasi_per_bulan($tahun_ini, false);


		// Grafik Line Persentase Capaian Target & Realisasi Anggaran per Bulan
		$arr_TargetPerBulan = array();
		$arr_RealisasiPerBulan = array();

		if (strlen($total_anggaran) > 0 && $total_anggaran != 0) {
			$grafik_capaian_target_realisasi = $this->dashboard->grafik_capaian_target_realisasi($tahun_ini, $total_anggaran);
			$arr_TargetPerBulan = $grafik_capaian_target_realisasi['arr_TargetPerBulan'];
			$arr_RealisasiPerBulan = $grafik_capaian_target_realisasi['arr_RealisasiPerBulan'];
		}

		$arr_pengeluaran = array(
			'total_anggaran'			=> floatval($total_anggaran),
			'realisasi_anggaran' 		=> floatval($realisasi_anggaran),
			'sudah_dibayar' 			=> floatval($sudah_dibayar),
			'menunggu_pembayaran' 		=> floatval($menunggu_pembayaran),
			'npd' 						=> floatval($npd),
			'sudah_diverifikasi' 		=> floatval($sudah_diverifikasi),
			'menunggu_verifikasi' 		=> floatval($menunggu_verifikasi),
			'kontrak_pengadaan'			=> floatval($kontrak_pengadaan),
			'proses_pengadaan' 			=> floatval($proses_pengadaan),
			'belum_direalisasi' 		=> floatval($belum_direalisasi),
			'dbh' 						=> floatval($dbh),
			'blud' 						=> floatval($blud),
			'target_dbh' 				=> floatval($target_dbh),
			'target_blud' 				=> floatval($target_blud),
			'target_per_bulan' 			=> $target_per_bulan,
			'realisasi_per_bulan' 		=> $realisasi_per_bulan,
			'arr_TargetPerBulan' 		=> $arr_TargetPerBulan,
			'arr_RealisasiPerBulan' 	=> $arr_RealisasiPerBulan,
		);

		return $arr_pengeluaran;
	}

	function get_paket_belanja($tahun_ini) {

		$this->load->library('AZApp');
		$crud_table = $this->azapp->add_crud();

		// query utama
		$this->db->select('pb.idpaket_belanja, p.nama_program, pb.nama_paket_belanja, pb.nilai_anggaran');
		
		$this->db->join('sub_kegiatan sk', 'sk.idsub_kegiatan = pb.idsub_kegiatan');
		$this->db->join('kegiatan k', 'k.idkegiatan = sk.idkegiatan');
		$this->db->join('program p', 'p.idprogram = k.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = p.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->join('paket_belanja_detail pbd', 'pbd.idpaket_belanja = pb.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds', 'pbds.idpaket_belanja_detail = pbd.idpaket_belanja_detail');

		// $this->db->where('YEAR(pb.created) = "'.$tahun_ini.'" ');
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan = "'.$tahun_ini.'" ');
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
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = p.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->join('paket_belanja_detail pbd', 'pbd.idpaket_belanja = pb.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbds_parent.idpaket_belanja_detail = pbd.idpaket_belanja_detail');
		$this->db->join('paket_belanja_detail_sub pbds', 'pbds.is_idpaket_belanja_detail_sub = pbds_parent.idpaket_belanja_detail_sub');

		// $this->db->where('YEAR(pb.created) = "'.$tahun_ini.'" ');
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan = "'.$tahun_ini.'" ');
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
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan.purchase_plan_status != "DRAFT" ');
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('YEAR(purchase_plan.purchase_plan_date)', $tahun_ini);
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->group_by('idpaket_belanja');
		$this->db->select('idpaket_belanja');
		$this->db->get('purchase_plan');
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
	}

	function custom_style($key, $value, $data) {
		
		if ($key == 'nilai_anggaran') {
			return az_thousand_separator($value);
		}

		return $value;
	}

	function pemasukan_anggaran($tahun_ini) {
		$bulan_ini = date('m');
		$hari_ini  = date('Y-m-d');

		// target tahun berjalan
			$target = $this->target_tahun($tahun_ini);
			$total_target_tahun = $target->row()->target_per_tahun;


		// target bulan berjalan
			$target = $this->target_tahun($tahun_ini);
			$total_target_bulan = $target->row()->target_bulan_laporan;


		// realisasi tahun berjalan
			$this->db->join('pad_sts_detail', 'pad_sts.idpad_sts = pad_sts_detail.idpad_sts');
			$this->db->where('YEAR(pad_sts.proof_date)', $tahun_ini);
			$this->db->where('pad_sts.pad_sts_status','OK');
			$this->db->where('pad_sts.status', 1);
			$this->db->where('pad_sts_detail.status', 1);
			$this->db->select_sum('pad_sts_detail.total_detail','total');
			$realisasi_tahun = $this->db->get('pad_sts');

			$total_realisasi_tahun = $realisasi_tahun->row()->total;


		// realisasi bulan berjalan
			$this->db->join('pad_sts_detail', 'pad_sts.idpad_sts = pad_sts_detail.idpad_sts');
			$this->db->where('YEAR(pad_sts.proof_date)', $tahun_ini);
			$this->db->where('MONTH(pad_sts.proof_date)', $bulan_ini);
			$this->db->where('pad_sts.pad_sts_status','OK');
			$this->db->where('pad_sts.status', 1);
			$this->db->where('pad_sts_detail.status', 1);
			$this->db->select_sum('pad_sts_detail.total_detail','total');
			$realisasi_bulan = $this->db->get('pad_sts');

			$total_realisasi_bulan = $realisasi_bulan->row()->total;


		// realisasi hari ini
			$this->db->join('pad_sts_detail', 'pad_sts.idpad_sts = pad_sts_detail.idpad_sts');
			$this->db->where('DATE(pad_sts.proof_date)', $hari_ini);
			$this->db->where('pad_sts.pad_sts_status','OK');
			$this->db->where('pad_sts.status',1);
			$this->db->where('pad_sts_detail.status',1);
			$this->db->select_sum('pad_sts_detail.total_detail','total');
			$realisasi_hari = $this->db->get('pad_sts');

			$total_realisasi_hari = $realisasi_hari->row()->total;


		// persentase
			$persen_tahun = 0;
			$persen_bulan = 0;
			$persen_hari  = 0;

			if ($total_target_tahun > 0){
				$persen_tahun = ($total_realisasi_tahun / $total_target_tahun) * 100;
			}
			if ($total_target_bulan > 0){
				$persen_bulan = ($total_realisasi_bulan / $total_target_bulan) * 100;
				$persen_hari = ($total_realisasi_hari / $total_target_bulan) * 100;
			}


		// total sts tahun ini
			$this->db->from('pad_sts');
			$this->db->where('YEAR(proof_date)', $tahun_ini);
			$this->db->where('pad_sts_status', 'OK');
			$this->db->where('status', 1);

			$jumlah_sts_tahun = $this->db->count_all_results();


		// total sts bulan ini
			$this->db->from('pad_sts');
			$this->db->where('YEAR(proof_date)', $tahun_ini);
			$this->db->where('MONTH(proof_date)', $bulan_ini);
			$this->db->where('pad_sts_status', 'OK');
			$this->db->where('status', 1);

			$jumlah_sts_bulan = $this->db->count_all_results();


		// total sts hari ini
			$this->db->from('pad_sts');
			$this->db->where('DATE(proof_date)', $hari_ini);
			$this->db->where('pad_sts_status', 'OK');
			$this->db->where('status', 1);

			$jumlah_sts_hari = $this->db->count_all_results();


		// rata rata penerimaan harian
			$this->db->join('pad_sts_detail', 'pad_sts.idpad_sts = pad_sts_detail.idpad_sts');
			$this->db->where('YEAR(proof_date)', $tahun_ini);
			$this->db->where('MONTH(proof_date)', $bulan_ini);
			$this->db->where('pad_sts_status', 'OK');
			$this->db->where('pad_sts.status', 1);
			$this->db->where('pad_sts_detail.status', 1);
			$this->db->group_by('DATE(proof_date)');
			
			$this->db->select('DATE(proof_date) AS tanggal, SUM(pad_sts_detail.total_detail) AS total');
			$result = $this->db->get('pad_sts')->result();

			$rata_harian = 0;
			if(count($result) > 0){
				$total = 0;
				foreach($result as $row){
					$total += $row->total;
				}
				$rata_harian = $total / count($result);
			}


		// total kas masuk
			$query_mutasi_masuk = $this->mutasi_kas($tahun_ini, $bulan_ini, $type = "PENERIMAAN");
			$mutasi_masuk = $query_mutasi_masuk->row()->total_mutasi_kas;


		// total kas keluar
			$query_mutasi_keluar = $this->mutasi_kas($tahun_ini, $bulan_ini, $type = "PENGELUARAN");
			$mutasi_keluar = $query_mutasi_keluar->row()->total_mutasi_kas;


		// total kas keluar
			$query_mutasi_pindah = $this->mutasi_kas($tahun_ini, $bulan_ini, $type = "PINDAH_REKENING");
			$mutasi_pindah = $query_mutasi_pindah->row()->total_mutasi_kas;

		
		// Saldo Kas Bendahara
			$saldo_kas = ($mutasi_masuk + $mutasi_pindah) - $mutasi_keluar;


		// trend penerimaan bulanan
			$this->db->join("pad_sts_detail", "pad_sts.idpad_sts = pad_sts_detail.idpad_sts");
			$this->db->where("YEAR(pad_sts.proof_date)", $tahun_ini);
			$this->db->where("pad_sts.pad_sts_status", "OK");
			$this->db->where("pad_sts.status", 1);
			$this->db->where("pad_sts_detail.status", 1);

			$this->db->group_by("MONTH(pad_sts.proof_date)");
			$this->db->order_by("MONTH(pad_sts.proof_date)", "ASC");
			
			$this->db->select("MONTH(pad_sts.proof_date) AS bulan, SUM(pad_sts_detail.total_detail) AS total");
			$result = $this->db->get("pad_sts")->result();

			$chart_bulanan = array_fill(0, 12, 0);
			foreach ($result as $row) {
				$chart_bulanan[$row->bulan - 1] = (float)$row->total;
			}


		$data = array(
			'target_tahun' 		=> $total_target_tahun,
			'target_bulan' 		=> $total_target_bulan,
			'realisasi_tahun' 	=> $total_realisasi_tahun,
			'realisasi_bulan' 	=> $total_realisasi_bulan,
			'realisasi_hari' 	=> $total_realisasi_hari,
			'persen_tahun' 		=> $persen_tahun,
			'persen_bulan' 		=> $persen_bulan,
			'persen_hari' 		=> $persen_hari,
			'jumlah_sts_tahun' 	=> $jumlah_sts_tahun,
			'jumlah_sts_bulan' 	=> $jumlah_sts_bulan,
			'jumlah_sts_hari' 	=> $jumlah_sts_hari,
			'rata_harian' 		=> $rata_harian,
			'mutasi_masuk' 		=> $mutasi_masuk,
			'mutasi_keluar' 	=> $mutasi_keluar,
			'mutasi_pindah' 	=> $mutasi_pindah,
			'saldo_kas' 		=> $saldo_kas,
			'chart_bulanan' 	=> $chart_bulanan,
		);

		return $data;
	}

	function mutasi_kas($tahun_ini, $bulan_ini, $type) {
		$this->db->where('proof_type', $type);
		$this->db->where('YEAR(proof_date)', $tahun_ini);
		$this->db->where('MONTH(proof_date)', $bulan_ini);
		$this->db->where('pad_mutasi_kas_status','OK');
		$this->db->where('status',1);
		$this->db->select_sum('total_mutasi_kas');
		$query_mutasi = $this->db->get('pad_mutasi_kas');
		
		return $query_mutasi;
	}

	function target_tahun($tahun_ini) {
		$this->db->select('sum(target_per_tahun) as target_per_tahun, sum(target_bulan_laporan) as target_bulan_laporan');
		$this->db->where('tahun', $tahun_ini);
		$this->db->where('status', 1);
		$target_tahun = $this->db->get('pad_target');

		return $target_tahun;
	}
}