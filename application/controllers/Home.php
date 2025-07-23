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
		$sudah_dibayar = 0;
		$belum_dibayar = 0;
		$belum_direalisasi = 0;
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

		// Ambil data target dan realisasi per bulan tahun 2025
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


		// sudah dibayar
		$this->db->where('verification.status', 1);
		$this->db->where('verification.verification_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');
		$this->db->where('YEAR(verification.confirm_payment_date) = "'.$tahun_ini.'" ');
		$this->db->select('sum(total_pay) as total_yang_sudah_dibayar');
		$verif = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		if ($verif->num_rows() > 0) {
			$sudah_dibayar = $verif->row()->total_yang_sudah_dibayar;
		}


		// belum dibayar
		$this->db->where('verification.status', 1);
		$this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');
		$this->db->where('YEAR(verification.confirm_verification_date) = "'.$tahun_ini.'" ');
		$this->db->select('sum(total_anggaran) as total_yang_belum_dibayar');
		$verif_before_pay = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		if ($verif_before_pay->num_rows() > 0) {
			$belum_dibayar = $verif_before_pay->row()->total_yang_belum_dibayar;
		}


		// belum direalisasi

		// ambil data paket belanja yang sudah di realisasi
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction.transaction_status != "DRAFT" ');
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('YEAR(transaction.transaction_date) = "'.$tahun_ini.'" ');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
		$this->db->group_by('idpaket_belanja');
		$this->db->select('idpaket_belanja');
		$trx = $this->db->get('transaction');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$arr_idpaket_belanja = array();
		foreach ($trx->result() as $key => $value) {
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
		
		$data = array(
			'tahun_ini' => $tahun_ini,
			'total_anggaran_tahun_ini' => floatval($total_anggaran),
			'sudah_dibayar' => floatval($sudah_dibayar),
			'belum_dibayar' => floatval($belum_dibayar),
			'belum_direalisasi' => floatval($belum_direalisasi),
			'target_per_bulan' => $target_per_bulan,
			'realisasi_per_bulan' => $realisasi_per_bulan,
		);

		$view = $this->load->view('home/v_home', $data, true);
		$app->add_content($view);

		// $js = az_add_js('home/vjs_home');
		// $app->add_js($js);

		echo $app->render();	
	}

	function calculate_nilai_anggaran($idpaket_belanja) {

		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->order_by('paket_belanja_detail.idpaket_belanja_detail ASC');
		$this->db->select('paket_belanja_detail.idpaket_belanja_detail, akun_belanja.idakun_belanja, akun_belanja.no_rekening_akunbelanja, akun_belanja.nama_akun_belanja');
		$akun_belanja = $this->db->get('paket_belanja_detail');
		// echo "<pre>"; print_r($this->db->last_query());

		$total_jumlah = 0;
		foreach ($akun_belanja->result() as $pbd_key => $pbd_value) {
			$idpaket_belanja_detail = $pbd_value->idpaket_belanja_detail;

			// Kategori / Sub Kategori
			$paket_belanja_detail = $this->query_paket_belanja_detail($idpaket_belanja_detail);
			// echo "<pre>"; print_r($this->db->last_query());

			foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {
				$total_jumlah += $ds_value->jumlah;

				// get sub sub detail
				$paket_belanja_detail_sub = $this->query_paket_belanja_detail_sub($ds_value->idpaket_belanja_detail_sub);
				// echo "<pre>"; print_r($this->db->last_query());die;

				foreach ($paket_belanja_detail_sub->result() as $dss_key => $dss_value) {
					$total_jumlah += $dss_value->jumlah;
				}
			}
		}

		$arr_update = array(
			'nilai_anggaran' => $total_jumlah,
		);

		az_crud_save($idpaket_belanja, 'paket_belanja', $arr_update);
	}

	function query_paket_belanja_detail($idpaket_belanja_detail) {
		$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail', $idpaket_belanja_detail);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
		$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, akun_belanja.no_rekening_akunbelanja, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
		$paket_belanja_detail = $this->db->get('paket_belanja_detail_sub');

		return $paket_belanja_detail;
	}

	function query_paket_belanja_detail_sub($idpaket_belanja_detail_sub) {
		$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
		$paket_belanja_detail_sub = $this->db->get('paket_belanja_detail_sub');

		return $paket_belanja_detail_sub;
	}
}