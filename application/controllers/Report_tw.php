<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Report_tw extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_tw');
        $this->table = 'paket_belanja';
        $this->controller = 'report_tw';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
		$this->load->model('dashboard_model','dashboard');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Nama Paket Belanja', 'TW 1', 'TW 2', 'TW 3', 'TW 4'));
		$crud->set_width('auto, auto, 180px, 180px, 180px, 180px');
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
        $crud->set_btn_add(false);

		$tahun_anggaran = $azapp->add_datetime();
		$tahun_anggaran->set_id('vf_tahun_anggaran');
		$tahun_anggaran->set_name('vf_tahun_anggaran');
		$tahun_anggaran->set_value(Date('Y'));
		$tahun_anggaran->set_format('YYYY');
		$data['tahun_anggaran'] = $tahun_anggaran->render();

		$crud->add_aodata('vf_tahun_anggaran', 'vf_tahun_anggaran');
		$crud->add_aodata('vf_nama_paket_belanja', 'vf_nama_paket_belanja');

		// GRAFIK PERBANDINGAN TARGET & REALISASI PER BULAN
		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');

		if (strlen($tahun_anggaran) == 0) {
			$tahun_anggaran = date('Y');
		}

		$target_per_bulan = [];
		$total_anggaran = [];

		$get_target_realisasi = $this->get_target_realisasi($tahun_anggaran);
		$arr_target = $get_target_realisasi['target'];
		$arr_realisasi = $get_target_realisasi['realisasi'];
		$total_anggaran = $get_target_realisasi['anggaran'];

		$data['target_per_bulan'] = $arr_target;
		$data['realisasi_per_bulan'] = $arr_realisasi;
		$data['total_anggaran'] = $total_anggaran;

		$filter = $this->load->view('report_tw/vf_report_tw', $data, true);
		$crud->set_top_filter($filter);

		$js = az_add_js('report_tw/vjs_report_tw');
		$azapp->add_js($js);
		
		$crud = $crud->render();
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Laporan Realisasi Paket Belanja per TW');
		$data_header['breadcrumb'] = array('report');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');
		$nama_paket_belanja = $this->input->get('vf_nama_paket_belanja');

		$crud->set_select('paket_belanja.idpaket_belanja, paket_belanja.nama_paket_belanja, "" as tw1, "" as tw2, "" as tw3, "" as tw4, urusan_pemerintah.tahun_anggaran_urusan');
		$crud->set_select_table('idpaket_belanja, nama_paket_belanja, tw1, tw2, tw3, tw4');

		$crud->set_filter('nama_paket_belanja');
		$crud->set_sorting('nama_paket_belanja');
		$crud->set_id($this->controller);

		$crud->add_join_manual('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$crud->add_join_manual('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$crud->add_join_manual('program', 'program.idprogram = paket_belanja.idprogram');
		$crud->add_join_manual('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$crud->add_join_manual('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');

		$crud->add_where('paket_belanja.status_paket_belanja != "DRAFT" ');
		$crud->add_where('paket_belanja.status = "1" ');

		if (strlen($tahun_anggaran) > 0) {
			$crud->add_where('urusan_pemerintah.tahun_anggaran_urusan = "' . $tahun_anggaran . '"');
		}
		if (strlen($nama_paket_belanja) > 0) {
			$crud->add_where('paket_belanja.nama_paket_belanja = "' . $nama_paket_belanja . '"');
		}

		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('nama_paket_belanja ASC');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idpaket_belanja = azarr($data, 'idpaket_belanja');
		$tahun_anggaran_urusan = azarr($data, 'tahun_anggaran_urusan');

		// TARGET PER TAHUN
		$this->db->where('paket_belanja_detail_sub.idpaket_belanja = "' . $idpaket_belanja . '"');
		$this->db->where('paket_belanja_detail_sub.status = "1"');
		$this->db->select('sum(rak_jumlah_januari) as bln1, sum(rak_jumlah_februari) as bln2, sum(rak_jumlah_maret) as bln3, sum(rak_jumlah_april) as bln4, sum(rak_jumlah_mei) as bln5, sum(rak_jumlah_juni) as bln6, sum(rak_jumlah_juli) as bln7, sum(rak_jumlah_agustus) as bln8, sum(rak_jumlah_september) as bln9, sum(rak_jumlah_oktober) as bln10, sum(rak_jumlah_november) as bln11, sum(rak_jumlah_desember) as bln12');
		$query_target = $this->db->get('paket_belanja_detail_sub');
		// echo"<pre>"; print_r($this->db->last_query()); die;

		$target = $query_target->row();
		$bln1 = $target->bln1 ?? 0;
		$bln2 = $target->bln2 ?? 0;
		$bln3 = $target->bln3 ?? 0;
		$bln4 = $target->bln4 ?? 0;
		$bln5 = $target->bln5 ?? 0;
		$bln6 = $target->bln6 ?? 0;
		$bln7 = $target->bln7 ?? 0;
		$bln8 = $target->bln8 ?? 0;
		$bln9 = $target->bln9 ?? 0;
		$bln10 = $target->bln10 ?? 0;
		$bln11 = $target->bln11 ?? 0;
		$bln12 = $target->bln12 ?? 0;

		$total_target = $bln1 + $bln2 + $bln3 + $bln4 + $bln5 + $bln6 + $bln7 + $bln8 + $bln9 + $bln10 + $bln11 + $bln12;

		// REALISASI PER TAHUN
		$the_filter = array(
			'tahun_anggaran_urusan' => $tahun_anggaran_urusan,
			'idpaket_belanja' => $idpaket_belanja
		);

		$query_realisasi = $this->build_realization($the_filter);
		$realisasi = $query_realisasi->row();

		$rtw1 = $realisasi->tw1 ?? 0;
		$rtw2 = $realisasi->tw2 ?? 0;
		$rtw3 = $realisasi->tw3 ?? 0;
		$rtw4 = $realisasi->tw4 ?? 0;

		// class
		$ctarget = "style='width:55px;'";
		$ctitikdua = "style='width:10px; text-align: center;'";
		$crealisasi = "style='width:60px; text-align: right;'";

		if ($key == 'tw1') {
			// TARGET
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja = "' . $idpaket_belanja . '"');
			$this->db->where('paket_belanja_detail_sub.status = "1"');
			$this->db->select('sum(rak_jumlah_januari) as bln1, sum(rak_jumlah_februari) as bln2, sum(rak_jumlah_maret) as bln3');
			$query = $this->db->get('paket_belanja_detail_sub');
			// echo"<pre>"; print_r($this->db->last_query()); die;

			$row_tw1 = $query->row();
			$target_tw1 = $row_tw1->bln1 + $row_tw1->bln2 + $row_tw1->bln3;

			$persentase_tw1 = 0;
			if ($target_tw1 > 0) {
				$persentase_tw1 = ($target_tw1 / $total_target) * 100;
			}
			
			// REALISASI
			$persentase_realisasi_tw1 = 0;
			if ($rtw1 > 0) {
				$persentase_realisasi_tw1 = ($rtw1 / $total_target) * 100;
			}

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .= 		"<tr>";
			$table .= 			"<td ".$ctarget.">Target</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_tw1)."%</td>";
			$table .= 		"</tr>";
			$table .= 		"<tr>";
			$table .= 			"<td>Realisasi</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_realisasi_tw1)."%</td>";
			$table .= 		"</tr>";
			$table .= "</table>";

			return $table;
		}

		if ($key == 'tw2') {
			// TARGET
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja = "' . $idpaket_belanja . '"');
			$this->db->where('paket_belanja_detail_sub.status = "1"');
			$this->db->select('sum(rak_jumlah_april) as bln1, sum(rak_jumlah_mei) as bln2, sum(rak_jumlah_juni) as bln3');
			$query = $this->db->get('paket_belanja_detail_sub');
			// echo"<pre>"; print_r($this->db->last_query()); die;

			$row_tw2 = $query->row();
			$target_tw2 = $row_tw2->bln1 + $row_tw2->bln2 + $row_tw2->bln3;

			$persentase_tw2 = 0;
			if ($target_tw2 > 0) {
				$persentase_tw2 = ($target_tw2 / $total_target) * 100;
			}


			// REALISASI
			$persentase_realisasi_tw2 = 0;
			if ($rtw2 > 0) {
				$persentase_realisasi_tw2 = ($rtw2 / $total_target) * 100;
			}

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .= 		"<tr>";
			$table .= 			"<td ".$ctarget.">Target</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_tw2)."%</td>";
			$table .= 		"</tr>";
			$table .= 		"<tr>";
			$table .= 			"<td>Realisasi</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_realisasi_tw2)."%</td>";
			$table .= 		"</tr>";
			$table .= "</table>";

			return $table;
		}

		if ($key == 'tw3') {
			// TARGET
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja = "' . $idpaket_belanja . '"');
			$this->db->where('paket_belanja_detail_sub.status = "1"');
			$this->db->select('sum(rak_jumlah_juli) as bln1, sum(rak_jumlah_agustus) as bln2, sum(rak_jumlah_september) as bln3');
			$query = $this->db->get('paket_belanja_detail_sub');
			// echo"<pre>"; print_r($this->db->last_query()); die;

			$row_tw3 = $query->row();
			$target_tw3 = $row_tw3->bln1 + $row_tw3->bln2 + $row_tw3->bln3;

			$persentase_tw3 = 0;
			if ($target_tw3 > 0) {
				$persentase_tw3 = ($target_tw3 / $total_target) * 100;
			}


			// REALISASI
			$persentase_realisasi_tw3 = 0;
			if ($rtw3 > 0) {
				$persentase_realisasi_tw3 = ($rtw3 / $total_target) * 100;
			}

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .= 		"<tr>";
			$table .= 			"<td ".$ctarget.">Target</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_tw3)."%</td>";
			$table .= 		"</tr>";
			$table .= 		"<tr>";
			$table .= 			"<td>Realisasi</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_realisasi_tw3)."%</td>";
			$table .= 		"</tr>";
			$table .= "</table>";

			return $table;
		}

		if ($key == 'tw4') {
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja = "' . $idpaket_belanja . '"');
			$this->db->where('paket_belanja_detail_sub.status = "1"');
			$this->db->select('sum(rak_jumlah_oktober) as bln1, sum(rak_jumlah_november) as bln2, sum(rak_jumlah_desember) as bln3');
			$query = $this->db->get('paket_belanja_detail_sub');
			// echo"<pre>"; print_r($this->db->last_query()); die;

			$row_tw4 = $query->row();
			$target_tw4 = $row_tw4->bln1 + $row_tw4->bln2 + $row_tw4->bln3;

			$persentase_tw4 = 0;
			if ($target_tw4 > 0) {
				$persentase_tw4 = ($target_tw4 / $total_target) * 100;
			}


			// REALISASI
			$persentase_realisasi_tw4 = 0;
			if ($rtw4 > 0) {
				$persentase_realisasi_tw4 = ($rtw4 / $total_target) * 100;
			}

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .= 		"<tr>";
			$table .= 			"<td ".$ctarget.">Target</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_tw4)."%</td>";
			$table .= 		"</tr>";
			$table .= 		"<tr>";
			$table .= 			"<td>Realisasi</td>";
			$table .= 			"<td ".$ctitikdua.">:</td>";
			$table .= 			"<td ".$crealisasi.">".az_thousand_separator($persentase_realisasi_tw4)."%</td>";
			$table .= 		"</tr>";
			$table .= "</table>";

			return $table;
		}

		return $value;
	}

	private function build_realization ($the_data) {
		$tahun_anggaran = azarr($the_data, 'tahun_anggaran_urusan');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja');

		$status_date = "
        CASE
            WHEN contract.contract_status = 'SUDAH DIBAYAR BENDAHARA' 
                THEN npd.confirm_payment_date
            WHEN contract.contract_status IN ('MENUNGGU PEMBAYARAN', 'INPUT NPD') 
                THEN npd.npd_date_created
            WHEN contract.contract_status IN ('DITOLAK VERIFIKATOR', 'SUDAH DIVERIFIKASI') 
                THEN verification.confirm_verification_date
            WHEN contract.contract_status = 'MENUNGGU VERIFIKASI' 
                THEN budget_realization.realization_date
            WHEN contract.contract_status = 'KONTRAK PENGADAAN' 
                THEN contract.contract_date
            ELSE NULL
        END";

        $tw1_start = $tahun_anggaran . '-01-01';
        $tw1_end = $tahun_anggaran . '-03-31';

        $tw2_start = $tahun_anggaran . '-04-01';
        $tw2_end = $tahun_anggaran . '-06-30';
        
		$tw3_start = $tahun_anggaran . '-07-01';
		$tw3_end = $tahun_anggaran . '-09-30';

		$tw4_start = $tahun_anggaran . '-10-01';
        $tw4_end = $tahun_anggaran . '-12-31';

        $this->db->select("purchase_plan_detail.idpaket_belanja_detail_sub as id,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$tw1_start}' AND {$status_date} <= '{$tw1_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw1,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$tw2_start}' AND {$status_date} <= '{$tw2_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw2,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$tw3_start}' AND {$status_date} <= '{$tw3_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw3,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$tw4_start}' AND {$status_date} <= '{$tw4_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw4",
                    false);

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization_detail.status', 1);
        
        $this->db->where('contract.contract_status !=', "DRAFT");
        $this->db->where('budget_realization.realization_status !=', "DRAFT");
        $this->db->where('verification.verification_status !=', "DRAFT");
        $this->db->where('npd.npd_status !=', "DRAFT");

        $this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
        $this->db->where('purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');

        $this->apply_status_validation_filter();

        $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
        $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub', 'left');
        $this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
        $this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');
        $this->db->join('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
        $this->db->join('npd_detail', 'npd_detail.idverification = verification.idverification', 'left');
        $this->db->join('npd', 'npd.idnpd = npd_detail.idnpd', 'left');

        $this->db->group_by('purchase_plan_detail.idpaket_belanja_detail_sub');

        $plan = $this->db->get('purchase_plan');
        // echo "<pre>"; print_r($this->db->last_query());die;

		return $plan;
	}

	private function apply_status_validation_filter()
    {
        $statuses = [
            'PROSES PENGADAAN',
            'KONTRAK PENGADAAN',
            'MENUNGGU VERIFIKASI',
            'SUDAH DIVERIFIKASI',
            'DITOLAK VERIFIKATOR',
            'INPUT NPD',
            'MENUNGGU PEMBAYARAN',
            'SUDAH DIBAYAR BENDAHARA'
        ];

        $this->db
            ->where_in('purchase_plan_detail.purchase_plan_detail_status', $statuses)
            ->where('budget_realization.realization_status !=', 'DRAFT');
    }

	public function chart_data() {
		$tahun_anggaran = $this->input->get('vf_tahun_anggaran');
		if (strlen($tahun_anggaran) == 0) {
			$tahun_anggaran = date('Y');
		}

		$data = $this->get_target_realisasi($tahun_anggaran);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function get_target_realisasi($tahun_anggaran) {

		// target per tw
		$target_per_bulan = $this->dashboard->get_target_per_bulan($tahun_anggaran);

		$target_tw1 = $target_per_bulan[0] + $target_per_bulan[1] + $target_per_bulan[2];
		$target_tw2 = $target_per_bulan[3] + $target_per_bulan[4] + $target_per_bulan[5];
		$target_tw3 = $target_per_bulan[6] + $target_per_bulan[7] + $target_per_bulan[8];
		$target_tw4 = $target_per_bulan[9] + $target_per_bulan[10] + $target_per_bulan[11];

		$arr_target = array($target_tw1, $target_tw2, $target_tw3, $target_tw4);

		// realisasi per tw
		$realisasi_per_bulan = $this->dashboard->get_realisasi_per_bulan($tahun_anggaran, false);

		$realisasi_tw1 = $realisasi_per_bulan[0] + $realisasi_per_bulan[1] + $realisasi_per_bulan[2];
		$realisasi_tw2 = $realisasi_per_bulan[3] + $realisasi_per_bulan[4] + $realisasi_per_bulan[5];
		$realisasi_tw3 = $realisasi_per_bulan[6] + $realisasi_per_bulan[7] + $realisasi_per_bulan[8];
		$realisasi_tw4 = $realisasi_per_bulan[9] + $realisasi_per_bulan[10] + $realisasi_per_bulan[11];

		$arr_realisasi = array($realisasi_tw1, $realisasi_tw2, $realisasi_tw3, $realisasi_tw4);

		$grafik_potensi_sisa_anggaran = $this->dashboard->grafik_potensi_sisa_anggaran($tahun_anggaran);
		$total_anggaran = $grafik_potensi_sisa_anggaran['total_anggaran_tahun_ini'];
		// echo "<pre>"; print_r($arr_realisasi);die;

		$arr_return = array(
			'target' => $arr_target,
			'realisasi' => $arr_realisasi,
			'anggaran' => $total_anggaran,
		);

		return $arr_return;
	}

	// function excel() {
	// 	$date1 = $this->input->get('date1');
	// 	$date2 = $this->input->get('date2');
	// 	$idpaket_belanja_detail_sub = $this->input->get('idpaket_belanja_detail_sub');


	// 	$this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
	// 	$this->db->where('npd.status = "1" ');
	// 	$this->db->where('npd_detail.status = "1" ');
	// 	$this->db->where('verification.status = "1" ');
	// 	$this->db->where('budget_realization.status = "1" ');
	// 	$this->db->where('budget_realization_detail.status = "1" ');

	// 	if (strlen($date1) > 0 && strlen($date2) > 0) {
    //         $this->db->where('date(npd.confirm_payment_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
    //         $this->db->where('date(npd.confirm_payment_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
    //     }
    //     if (strlen($idpaket_belanja_detail_sub) > 0) {
	// 		$this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub = "' . $idpaket_belanja_detail_sub . '"');
	// 	}

	// 	$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
	// 	$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
	// 	$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
	// 	$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
	// 	$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
	// 	$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
	// 	$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
	// 	$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
	// 	$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = budget_realization_detail.idsub_kategori');
    //     $this->db->join('ruang', 'ruang.idruang = budget_realization_detail.idruang', 'left');
		
	// 	$this->db->order_by('npd.confirm_payment_date ASC, sub_kategori.nama_sub_kategori ASC');
	// 	$this->db->select('npd.idnpd, date_format(npd.confirm_payment_date, "%d-%m-%Y %H:%i:%s") as txt_confirm_payment_date, budget_realization_detail.provider, ruang.nama_ruang, sub_kategori.nama_sub_kategori, budget_realization_detail.realization_detail_description, budget_realization_detail.volume, budget_realization_detail.male, budget_realization_detail.female, budget_realization_detail.unit_price, budget_realization_detail.total_realization_detail');
	// 	$data = $this->db->get('npd');
	// 	// echo"<pre>"; print_r($this->db->last_query()); die;

	// 	$this->load->library('AZApp');
	// 	$azapp = $this->azapp;
	// 	$azapp->add_phpexcel();

	// 	$file_excel = APPPATH . "assets/excel/laporan_realisasi_anggaran.xlsx";
	// 	// echo "<pre>"; print_r($file_excel); die;

	// 	$spreadsheet = IOFactory::load($file_excel);
	// 	$sheet = $spreadsheet->getActiveSheet();

	// 	$i = 0;
	// 	$start_row = 6;

	// 	$styleArray11 = [
	// 		'borders' => [
	// 			'allBorders' => [
	// 				'style' => Border::BORDER_THIN
	// 			]
	// 		]
	// 	];

	// 	$sheet->setCellValue("A3", $date1 . ' s/d ' . $date2);

	// 	foreach ($data->result() as $key => $value) {
	// 		$sheet->setCellValue("A" . ($start_row + $i), ($i + 1));
	// 		$sheet->setCellValue("B" . ($start_row + $i), $value->txt_confirm_payment_date);
	// 		$sheet->setCellValue("C" . ($start_row + $i), $value->provider);
	// 		$sheet->setCellValue("D" . ($start_row + $i), $value->nama_ruang);
	// 		$sheet->setCellValue("E" . ($start_row + $i), $value->nama_sub_kategori);
	// 		$sheet->setCellValue("F" . ($start_row + $i), $value->realization_detail_description);
	// 		$sheet->setCellValue("G" . ($start_row + $i), $value->volume);
	// 		$sheet->setCellValue("H" . ($start_row + $i), $value->male);
	// 		$sheet->setCellValue("I" . ($start_row + $i), $value->female);
	// 		$sheet->setCellValue("J" . ($start_row + $i), $value->unit_price);
	// 		$sheet->setCellValue("K" . ($start_row + $i), $value->total_realization_detail);
	// 		$i++;
	// 	}

	// 	$sheet->getStyle("A{$start_row}:K" . ($start_row + $i - 1))
	// 		->applyFromArray($styleArray11);

	// 	// OUTPUT
	// 	$filename = 'Laporan Realisasi Anggaran ' . date('d-m-Y H-i-s') . '.xlsx';

	// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	header('Content-Disposition: attachment;filename="' . $filename . '"');
	// 	header('Cache-Control: max-age=0');

	// 	$writer = new Xlsx($spreadsheet);
	// 	$writer->save('php://output');
	// 	exit;
	// }
}