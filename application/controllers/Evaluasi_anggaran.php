<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluasi_anggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('evaluasi_anggaran');
        $this->controller = 'evaluasi_anggaran';
        $this->load->helper('az_crud');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_single_filter(false);
		$crud->set_btn_add(false);

		$crud->add_aodata('date2', 'date2');

		$tahun_anggaran_urusan = $azapp->add_datetime();
		$tahun_anggaran_urusan->set_id('tahun_anggaran_urusan');
		$tahun_anggaran_urusan->set_name('tahun_anggaran_urusan');
		$tahun_anggaran_urusan->set_value(Date('Y'));
		$tahun_anggaran_urusan->set_format('YYYY');
		$data['tahun_anggaran_urusan'] = $tahun_anggaran_urusan->render();

		$tahun_anggaran_urusan = $this->input->get('tahun_anggaran_urusan');
		if ($tahun_anggaran_urusan == null) {
			$tahun_anggaran_urusan = date("Y");
		}
		else {
			$tahun_anggaran_urusan = az_crud_date($tahun_anggaran_urusan, 'Y');
		}

		$data['tahun_anggaran'] = $tahun_anggaran_urusan;
		
		$the_filter = array();
		$the_filter = array(
			'tahun_anggaran' => $tahun_anggaran_urusan,
		);

		$get_data = $this->get_data($the_filter);

		$data['arr_data'] = $get_data;
		// echo "<pre>"; print_r($data);die;

		// $js = az_add_js('evaluasi_anggaran/vjs_evaluasi_anggaran', $data, true);
		// $azapp->add_js($js);

		$view = $this->load->view('evaluasi_anggaran/v_evaluasi_anggaran', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = 'Evaluasi Anggaran';
		$data_header['breadcrumb'] = array('evaluasi_anggaran');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();
	}

	function get_data($the_data) {

		$tahun_anggaran = azarr($the_data, 'tahun_anggaran');
		
		$this->db->where('urusan_pemerintah.status', 1);
		$this->db->where('urusan_pemerintah.is_active', 1);
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $tahun_anggaran);
		$this->db->order_by('urusan_pemerintah.idurusan_pemerintah ASC');
		$this->db->select('idurusan_pemerintah, no_rekening_urusan, nama_urusan, tahun_anggaran_urusan');
		$urusan_pemerintah = $this->db->get('urusan_pemerintah');
		// echo "<pre>"; print_r($this->db->last_query());

		$arr_urusan = array();
		foreach ($urusan_pemerintah->result() as $key => $value) {
			$idurusan_pemerintah = $value->idurusan_pemerintah;
			$no_rek_urusan = $value->no_rekening_urusan;
			$nama_urusan = $value->nama_urusan;

			// bidang urusan
			$this->db->where('bidang_urusan.status', 1);
			$this->db->where('bidang_urusan.is_active', 1);
			$this->db->where('bidang_urusan.idurusan_pemerintah', $idurusan_pemerintah);
			$this->db->order_by('bidang_urusan.idbidang_urusan ASC');
			$this->db->select('idbidang_urusan, no_rekening_bidang_urusan, nama_bidang_urusan');
			$bidang_urusan = $this->db->get('bidang_urusan');
			// echo "<pre>"; print_r($this->db->last_query());

			$arr_bidang_urusan = array();
			foreach ($bidang_urusan->result() as $bu_key => $bu_value) {
				$idbidang_urusan = $bu_value->idbidang_urusan;
				$no_rek_bidang_urusan = $bu_value->no_rekening_bidang_urusan;
				$nama_bidang_urusan = $bu_value->nama_bidang_urusan;
				$rekening_bidang = $no_rek_urusan.'.'.$no_rek_bidang_urusan;

				// program
				$this->db->where('program.status', 1);
				$this->db->where('program.is_active', 1);
				$this->db->where('program.idbidang_urusan', $idbidang_urusan);
				$this->db->order_by('program.idprogram ASC');
				$this->db->select('idprogram, no_rekening_program, nama_program');
				$program = $this->db->get('program');
				// echo "<pre>"; print_r($this->db->last_query());

				$arr_program = array();
				foreach ($program->result() as $p_key => $p_value) {
					$idprogram = $p_value->idprogram;
					$no_rekening_program = $p_value->no_rekening_program;
					$nama_program = $p_value->nama_program;
					$rekening_program = $no_rek_urusan.'.'.$no_rek_bidang_urusan.'.'.$no_rekening_program;

					// kegiatan
					$this->db->where('kegiatan.status', 1);
					$this->db->where('kegiatan.is_active', 1);
					$this->db->where('kegiatan.idprogram', $idprogram);
					$this->db->order_by('kegiatan.idkegiatan ASC');
					$this->db->select('idkegiatan, no_rekening_kegiatan, nama_kegiatan');
					$kegiatan = $this->db->get('kegiatan');
					// echo "<pre>"; print_r($this->db->last_query());

					$arr_kegiatan = array();
					foreach ($kegiatan->result() as $k_key => $k_value) {
						$idkegiatan = $k_value->idkegiatan;
						$no_rekening_kegiatan = $k_value->no_rekening_kegiatan;
						$nama_kegiatan = $k_value->nama_kegiatan;
						$rekening_kegiatan = $no_rek_urusan.'.'.$no_rek_bidang_urusan.'.'.$no_rekening_program.'.'.$no_rekening_kegiatan;

						// Sub Kegiatan
						$this->db->where('sub_kegiatan.status', 1);
						$this->db->where('sub_kegiatan.is_active', 1);
						$this->db->where('sub_kegiatan.idkegiatan', $idkegiatan);
						$this->db->order_by('sub_kegiatan.idsub_kegiatan ASC');
						$this->db->select('idsub_kegiatan, no_rekening_subkegiatan, nama_subkegiatan');
						$sub_kegiatan = $this->db->get('sub_kegiatan');
						// echo "<pre>"; print_r($this->db->last_query());

						$arr_sub_kegiatan = array();
						foreach ($sub_kegiatan->result() as $sk_key => $sk_value) {
							$idsub_kegiatan = $sk_value->idsub_kegiatan;
							$no_rekening_subkegiatan = $sk_value->no_rekening_subkegiatan;
							$nama_subkegiatan = $sk_value->nama_subkegiatan;
							$rekening_subkegiatan = $no_rek_urusan.'.'.$no_rek_bidang_urusan.'.'.$no_rekening_program.'.'.$no_rekening_kegiatan.'.'.$no_rekening_subkegiatan;

							// Paket Belanja + anggaran
							$this->db->where('paket_belanja.status', 1);
							$this->db->where('paket_belanja.status_paket_belanja = "OK" ');
							$this->db->where('paket_belanja.idsub_kegiatan', $idsub_kegiatan);
							$this->db->order_by('paket_belanja.idpaket_belanja ASC');
							$this->db->select('idpaket_belanja, nama_paket_belanja, nilai_anggaran');
							$paket_belanja = $this->db->get('paket_belanja');
							// echo "<pre>"; print_r($this->db->last_query());

							$arr_paket_belanja = array();
							foreach ($paket_belanja->result() as $pb_key => $pb_value) {
								$idpaket_belanja = $pb_value->idpaket_belanja;
								$nama_paket_belanja = $pb_value->nama_paket_belanja;
								$nilai_anggaran = $pb_value->nilai_anggaran;

								// Akun Belanja
								$this->db->where('paket_belanja_detail.status', 1);
								// $this->db->where('akun_belanja.is_active', 1);
								$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
								$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
								$this->db->order_by('paket_belanja_detail.idpaket_belanja_detail ASC');
								$this->db->select('paket_belanja_detail.idpaket_belanja_detail, akun_belanja.idakun_belanja, akun_belanja.no_rekening_akunbelanja, akun_belanja.nama_akun_belanja');
								$paket_belanja_detail = $this->db->get('paket_belanja_detail');
								// echo "<pre>"; print_r($this->db->last_query());

								$arr_akun_belanja = array();
								foreach ($paket_belanja_detail->result() as $pbd_key => $pbd_value) {
									$idpaket_belanja_detail = $pbd_value->idpaket_belanja_detail;
									$idakun_belanja = $pbd_value->idakun_belanja;
									$no_rekening_akunbelanja = $pbd_value->no_rekening_akunbelanja;
									$nama_akun_belanja = $pbd_value->nama_akun_belanja;

									$arr_akun_belanja[] = array(
										'idpaket_belanja_detail' => $idpaket_belanja_detail,
										'idakun_belanja' => $idakun_belanja,
										'no_rekening_akunbelanja' => $no_rekening_akunbelanja,
										'nama_akun_belanja' => $nama_akun_belanja,
									);
								}

								$arr_paket_belanja[] = array(
									'idpaket_belanja' => $idpaket_belanja,
									'nama_paket_belanja' => $nama_paket_belanja,
									'nilai_anggaran' => $nilai_anggaran,
									'akun_belanja' => $arr_akun_belanja,
								);
							}							
							
							$arr_sub_kegiatan[] = array(
								'idsub_kegiatan' => $idsub_kegiatan,
								'nama_sub_kegiatan' => $rekening_subkegiatan.' - '.$nama_subkegiatan,
								'paket_belanja' => $arr_paket_belanja,
							);
						}

						$arr_kegiatan[] = array(
							'idkegiatan' => $idkegiatan,
							'nama_kegiatan' => $rekening_kegiatan.' - '.$nama_kegiatan,
							'sub_kegiatan' => $arr_sub_kegiatan,
						);
					}

					$arr_program[] = array(
						'idprogram' => $idprogram,
						'nama_program' => $rekening_program.' - '.$nama_program,
						'kegiatan' => $arr_kegiatan,
					);
				}

				$arr_bidang_urusan[] = array(
					'idbidang_urusan' => $idbidang_urusan,
					'nama_bidang_urusan' => $rekening_bidang.' - '.$nama_bidang_urusan,
					'program' => $arr_program,
				);
			}

			$arr_urusan[] = array(
				'idurusan' => $idurusan_pemerintah,
				'nama_urusan' => $no_rek_urusan.' - '.$nama_urusan,
				'bidang_urusan' => $arr_bidang_urusan,
			);
		}

		$return = array(
			'tahun_anggaran' => $tahun_anggaran,
			'urusan' => $arr_urusan,
		);
		// echo "<pre>"; print_r($return);die;
		
		return $return;
	}


	//////////////////////////////////////////////
	///// cek
	/////////////////////////////////////////////
	function print_report_balance_sheet() {
		// $date1 = $this->input->post('date1');
		$idoutlet = $this->input->post('idoutlet');
		if ($idoutlet == "null") {
			$idoutlet = null;
		}
		$idcompany = $this->input->post('idcompany');
		if ($idcompany == "null" || $idcompany == "undefined") {
			$idcompany = '';
		}
		$date2 = $this->input->post('date2');
		
		if ($date2 == null) {
			$date2 = date("Y-m-d");
		}
		
		$arr_data = array();
		$data_laporan['account_name'] = '';

		for ($i=0; $i < 2; $i++) { 
			$data_laporan['arr_detail'] = array();

			$data_laporan = $this->get_data($i, $date2, $idoutlet, $idcompany);

			$arr_data[] = array(
				'account_name' => $data_laporan['account_name'],
				'arr_detail' => $data_laporan['arr_detail'],
			);
		}
				
		// echo "<pre>";print_r($arr_data);die;
		$data['arr_data'] = $arr_data;

		// $data['date1'] = $date1;
		$data['date2'] = $date2;
		$data['idoutlet'] = $idoutlet;
		$data['idcompany'] = $idcompany;

		$this->load->view('acc_report/report_balance_sheet/v_balance_sheet_print', $data);
	}

	function get_total_balance_sheet($date2, $idoutlet, $idcompany, $is_export_excel = false) {
		// $date1 = '2023-08-01 00:00:00';
		$date1 = '1970-01-01 00:00:00';

		// $date1 = date(date('Y-m').'-01', strtotime($date2)).' 00:00:00';
		// $date1 = date('Y-m-d', strtotime('01-' . substr($date2, 3))).' 00:00:00';

		// $date1 = $date2;
		// var_dump($date1); echo "<br>";

		if ($this->is_comma_price) {
			$is_comma = 'az_thousand_separator_decimal';
		}
		else {
			$is_comma = 'az_thousand_separator';
		}

		// cek apakah ada data tutup buku
		$this->db->where('acc_conversion_balance_group.status', 1);
		$this->db->where('acc_conversion_balance_group.is_publish', 1);
		$this->db->where('acc_conversion_balance_group.status_conversion_balance = "CLOSED" ');
		if ($date1 != null && $date2 != null) {
			$this->db->where('acc_conversion_balance_group.conversion_balance_date_start >= "'.$date1.'"');
		}
		else {
			$this->db->where('acc_conversion_balance_group.conversion_balance_date_start >= "'.Date('Y-m-d H:i:s').' "');
		}
		if (strlen($idoutlet) > 0) {
			$this->db->where('acc_conversion_balance_group.idoutlet = '.$idoutlet.'');
		}
		if ($this->is_company) {
			if (strlen($idcompany) > 0) {
				$this->db->where('acc_conversion_balance_group.idcompany = '.$idcompany.'');
			}
		}
		$this->db->select('conversion_balance_date_end');
		$this->db->order_by('conversion_balance_date_end DESC');
		$acc_conversion_balance_group = $this->db->get('acc_conversion_balance_group');
		if ($acc_conversion_balance_group->num_rows() > 0) {
			if (strtotime($acc_conversion_balance_group->row()->conversion_balance_date_end) > strtotime($date1)) {
				$date1 = $acc_conversion_balance_group->row()->conversion_balance_date_end;
			}
		}



		// cek apakah dari data filternya itu ada data SALDO_AWAL
		$this->db->where('acc_conversion_balance_group.status', 1);
		$this->db->where('acc_conversion_balance_group.is_publish', 1);
		$this->db->where('acc_conversion_balance_group.status_conversion_balance = "OPEN" ');
		if ($date1 != null && $date2 != null) {
			$this->db->where('acc_conversion_balance_group.conversion_balance_date_start >= "'.$date1.'"');
		}
		else {
			$this->db->where('acc_conversion_balance_group.conversion_balance_date_start >= "'.Date('Y-m-d H:i:s').' "');
		}
		if (strlen($idoutlet) > 0) {
			$this->db->where('acc_conversion_balance_group.idoutlet = '.$idoutlet.'');
		}
		if ($this->is_company) {
			if (strlen($idcompany) > 0) {
				$this->db->where('acc_conversion_balance_group.idcompany = '.$idcompany.'');
			}
		}

		$this->db->select('conversion_balance_date_start');

		$acc_conversion_balance_group = $this->db->get('acc_conversion_balance_group');

		if ($acc_conversion_balance_group->num_rows() > 0) {
			if (strtotime($acc_conversion_balance_group->row()->conversion_balance_date_start) > strtotime($date1)) {
				$date1 = $acc_conversion_balance_group->row()->conversion_balance_date_start;
			}
		}
		// echo "<pre>"; print_r($this->db->last_query());die;

		// ambil id yang punya sub akun
		$this->db->where('status', 1);
		$this->db->where('idaccount_parent IS NOT NULL');
		$this->db->select('idaccount_parent');
		$this->db->group_by('idaccount_parent');
		$account_parent = $this->db->get('acc_account');

		$arr_parent = array();
		foreach ($account_parent->result() as $key => $value) {
			$arr_parent[] = $value->idaccount_parent;
		}

		$data_parent = '"'.implode('", "', $arr_parent).'"';


		$arr_detail = array();

		$saldo_aset = 0;
		$saldo_liabilitas = 0;
		$saldo_net_income = 0;


		// Total Debet
			$account_category_name = ' "Kas & Bank", "Akun Piutang", "Persediaan", "Aktiva Lancar Lainnya", "Aktiva Tetap", "Depresiasi & Amortisasi", "Aktiva Lainnya" ';
			
			// ambil total saldo
			$the_filter = array(
				'idoutlet' 				=> $idoutlet,
				'idcompany' 			=> $idcompany,
				'date1' 				=> $date1,
				'date2' 				=> $date2,
				'account_category_name' => $account_category_name,
				'data_parent' 			=> $data_parent,
				'type' 					=> "ASET",
			);

			$get_balance = $this->lreport_accounting->get_balance($the_filter);

			$saldo_aset = $get_balance['balance'];
			if ($this->is_comma_price) {
				$saldo_aset = round($saldo_aset, 2);
			}
			else {
				$saldo_aset = round($saldo_aset);
			}
			// var_dump($saldo_aset);die;

		
		// Total Kredit
			$account_category_name = ' "Akun Hutang", "Kewajiban Lancar Lainnya", "Kewajiban Jangka Panjang", "Ekuitas" ';

			// ambil total saldo
			$the_filter = array(
				'idoutlet' 				=> $idoutlet,
				'idcompany' 			=> $idcompany,
				'date1' 				=> $date1,
				'date2' 				=> $date2,
				'account_category_name' => $account_category_name,
				'data_parent' 			=> $data_parent,
				'type' 					=> "LIABILITAS",
			);

			$get_balance = $this->lreport_accounting->get_balance($the_filter);

			$saldo_liabilitas = $get_balance['balance'];
			if ($this->is_comma_price) {
				$saldo_liabilitas = round($saldo_liabilitas, 2);
			}
			else {
				$saldo_liabilitas = round($saldo_liabilitas);
			}

			// var_dump($saldo_liabilitas);die;

		
		// Pendapatan periode ini
			$arr_data = array();
			$saldo_net_income = $saldo_liabilitas;
			// $saldo_net_income = 0;

			$the_profit_loss = array(
				'idoutlet' 			=> $idoutlet,
				'idcompany' 		=> $idcompany,
				'date1' 			=> $date1,
				'date2' 			=> $date2,
			);

			$arr_data = $this->lreport_accounting->report_profit_loss($the_profit_loss);
			// echo "<pre>"; print_r($arr_data);die;
			
			foreach ((array)$arr_data as $key => $value) {
				foreach ((array)$value['arr_detail'] as $detail_key => $value2) {
					if ($value2['is_parent'] == 0) {

						if ($this->is_comma_price) {
							$value2['saldo_aset'] = round($value2['saldo_aset'], 2);
						}
						else {
							$value2['saldo_aset'] = round($value2['saldo_aset']);
						}

						// uji coba tanpa kondisi is_parent = 0
						// case nya ketika ada jurnal yang tampilnya hanya parentnya saja (cth. bensin, tol dan parkir - penjualan => kategori beban)
						$saldo_net_income += $value2['saldo_aset'];
					}
				}
			}

			if ($this->is_comma_price) {
				$saldo_net_income = round($saldo_net_income, 2);
			}
			else {
				$saldo_net_income = round($saldo_net_income);
			}


		$return = array(
			// 'account_name' => $account_name,
			'total_aset' => $saldo_aset,
			'total_modal' => $saldo_liabilitas,
			'total_income' => $saldo_net_income,
			'date1' => Date('d-m-Y', strtotime($date1)),
			'date2' => Date('d-m-Y', strtotime($date2)),
		);
		// echo "<pre>"; print_r($return);die;
		
		return $return;
	}

	function get_grand_total($date1, $date2, $idoutlet, $idcompany, $account_category_name) {
		// hitung total saldo per akun
		if (strlen($idoutlet) > 0) {
			$this->db->where('acc_accounting.idoutlet = '.$idoutlet.'');
		}
		if ($this->is_company) {
			if (strlen($idcompany) > 0) {
				$this->db->where('acc_accounting.idcompany = '.$idcompany.'');
			}
		}
		$this->db->where('DATE_FORMAT(acc_accounting.transaction_date, "%Y-%m-%d") >= "'.Date('Y-m-d', strtotime($date1)).'"');
		$this->db->where('DATE_FORMAT(acc_accounting.transaction_date, "%Y-%m-%d") <= "'.Date('Y-m-d', strtotime($date2)).'"');

		$this->db->where('acc_accounting.status', 1);
		$this->db->where('acc_account.status', 1);
		$this->db->where('acc_account_category.account_category_name IN ('.$account_category_name.') ');
		$this->db->join('acc_account', 'acc_account.idacc_account = acc_accounting.idacc_account');
		$this->db->join('acc_account_category', 'acc_account_category.idacc_account_category = acc_account.idacc_account_category');
		$this->db->select('
			ROUND(SUM(acc_accounting.debet)) as sum_debet, 
			ROUND(SUM(acc_accounting.kredit)) as sum_kredit, 
			(
				ROUND(SUM(acc_accounting.debet)) - 
				ROUND(SUM(acc_accounting.kredit))
			) as total_debet_kredit, 
			(
				ROUND(SUM(acc_accounting.kredit)) - 
				ROUND(SUM(acc_accounting.debet))
			) as total_kredit_debet, 
			type_addition_account, 
			acc_account.account_name, 
			account_category_name');
		$acc_accounting = $this->db->get('acc_accounting');
		// echo "<pre>"; print_r($this->db->last_query());die;
		
		$balance = 0;

		// Cek saldo normalnya di debet atau kredit
		if ($acc_accounting->row()->type_addition_account == "DEBET") {
			// $balance = $acc_accounting->row()->sum_debet - $acc_accounting->row()->sum_kredit;
			$balance = $acc_accounting->row()->total_debet_kredit;
		}
		else if ($acc_accounting->row()->type_addition_account == "KREDIT") {

			// jika akumulasi penyusutan maka saldonya ditaruh di minus
			if ($acc_accounting->row()->account_category_name == "Depresiasi & Amortisasi") {
				// $balance = (double)$acc_accounting->row()->sum_debet - (double)$acc_accounting->row()->sum_kredit;
				$balance = $acc_accounting->row()->total_debet_kredit;
			}
			else {
				// $balance = (double)$acc_accounting->row()->sum_kredit - (double)$acc_accounting->row()->sum_debet;
				$balance = $acc_accounting->row()->total_kredit_debet;
			}
		}

		if ($this->is_comma_price) {
			$balance = round($balance, 2);
		}
		else {
			$balance = round($balance);
		}

		return $balance;
	}

	function get_query($idoutlet, $idcompany, $account_category_name) {

		if (strlen($idoutlet) > 0) {
			$this->db->where('acc_account.idoutlet = "'.$idoutlet.'"');
		}
		if (strlen($idcompany) > 0) {
			$this->db->where('acc_account.idcompany = "'.$idcompany.'"');
		}
		$this->db->where('acc_account.status', 1);
		// $this->db->where_in('account_category_name', array("Kas & Bank", "Akun Piutang", "Persediaan", "Aktiva Lancar Lainnya"));
		$this->db->where_in('account_category_name', $account_category_name);
		
		$this->db->join('acc_account_category', 'acc_account_category.idacc_account_category = acc_account.idacc_account_category', 'left');
		$this->db->join('acc_account account_parent', 'acc_account.idaccount_parent = account_parent.idacc_account', 'left');
		$this->db->join('outlet', 'outlet.idoutlet = acc_account.idoutlet', 'left');

		$this->db->order_by('the_sort, acc_account.account_code');
		$this->db->select('acc_account.idacc_account, outlet_name, acc_account.account_code, acc_account.account_name, account_category_name, acc_account.balance, acc_account.is_delete, acc_account.idaccount_parent, CASE WHEN acc_account.idaccount_parent is null THEN acc_account.account_code ELSE concat(account_parent.account_code, "-") END AS the_sort');
		$query = $this->db->get('acc_account');

		return $query;
	}

	function excel_neraca(){

		if ($this->is_comma_price) {
			$is_comma = 'az_thousand_separator_decimal';
		}
		else {
			$is_comma = 'az_thousand_separator';
		}

		$idoutlet = $this->input->post('idoutlet');
		if ($idoutlet == "null") {
			$idoutlet = null;
		}
		$idcompany = $this->input->post('idcompany');
		if ($idcompany == "null" || $idcompany == "undefined") {
			$idcompany = '';
		}
		$date2 = $this->input->post('date2');

		$type_date = 'Y-m-d';

		if ($date2 == null){
			$date2 = date("Y-m-d");
		}
		else {
			$date2 = az_crud_date($this->input->post('date2'), $type_date);
		}

		$arr_data = array();
		$data_laporan['account_name'] = '';
		$is_export_excel = true;

		for ($i=0; $i < 2; $i++){
			$arr_laporan['arr_detail'] = array();

			$data_laporan = $this->get_data($i, $date2, $idoutlet, $idcompany, $is_export_excel);
			// dump($data_laporan);
			// die();

			$arr_data[] = array(
				'account_name' 	=> $data_laporan['account_name'],
				'arr_detail'	=> $data_laporan['arr_detail'],
			);

		}

		// echo "<pre>"; print_r($arr_data);die;
		$data['arr_data'] = $arr_data;

		$data['date2'] = $date2;
		
		$app_name = az_get_config('app_name');
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$azapp->add_phpexcel();

		$file_excel = APPPATH . "assets/excel/laporan_neraca.xlsx";
		if (!file_exists($file_excel)) {
			$file_excel = DEFPATH . "assets/excel/laporan_neraca.xlsx";
		}
		$phpexcel = PHPExcel_IOFactory::load($file_excel);
		$sheet0 = $phpexcel->setActiveSheetIndex(0);

		$i = 0;
		$start_row_data = 10;
		$start_row_detail = 11;
		$start_row_sub = 12;

		$styleArray11 = array(
			'borders' => array(
				'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);
	
		// echo"<pre>"; print_r($arr_data);die;
		$the_sub_total = 0;
		$the_sub_total_modal = 0;
		$the_grand_total = 0;
        $the_grand_total_modal = 0;

		$sheet0->setCellValue("A3", $app_name);
		$sheet0->setCellValue("A6",'Per Tanggal  ' .Date('d-m-Y', strtotime($date2)));
		foreach ((array)$data['arr_data'] as $data_key => $data_value){
			$sheet0->setCellValue("A".($start_row_data + $i),  $data_value['account_name'])->mergeCells("A".($start_row_data + $i).":"."D".($start_row_data + $i));
			$sheet0->getStyle('A'.($start_row_data + $i))->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'a5d7ff')
					)
				)
			);
			// $sheet0->setCellValue("A".($start_row_data + $i), $data_value['account_name']);
			$i++;		
			foreach ((array)$data_value['arr_detail'] as $detail_key => $detail_value) {
				$sheet0->setCellValue("A".($start_row_data + $i),  $detail_value['account_name_sub'])->mergeCells("A".($start_row_data + $i).":"."D".($start_row_data + $i));
				// $sheet0->setCellValue("A".($start_row_data + $i), $detail_value['account_name_sub']);
				$i++;

				foreach ((array)$detail_value['arr_detail_sub'] as $sub_key => $sub_value) {
					// dump($sub_value);
					// die();
					
					if ($sub_value['saldo_aset'] != 0 || $sub_value['saldo_modal'] != 0 || $sub_value['account_name'] == "Pendapatan Periode Ini") {
						$saldo_aset = az_remove_separator($sub_value['saldo_aset']);
						$saldo_modal = az_remove_separator($sub_value['saldo_modal']);

						$the_sub_total += floatval($saldo_aset);
						$the_sub_total_modal += floatval($saldo_modal);
						$the_grand_total += floatval($saldo_aset);
						$the_grand_total_modal += floatval($saldo_modal);

						$sheet0->setCellValue("A".($start_row_data + $i), $sub_value['account_code']);
						$sheet0->setCellValue("B".($start_row_data + $i), $sub_value['account_name']);
						if ($saldo_aset != '' && $saldo_aset > 0) {
							$sheet0->setCellValue("C".($start_row_data + $i), $saldo_aset);
						} else if ($saldo_aset < 0) {
							$sheet0->setCellValue("C".($start_row_data + $i), abs($saldo_aset));
						}
						if ( ($saldo_modal != '' && $saldo_modal > 0) || ($sub_value['account_name'] == "Pendapatan Periode Ini" && $saldo_modal == 0)  ) {
							$sheet0->setCellValue("D".($start_row_data + $i), $saldo_modal);
						} else if ($saldo_modal < 0) {
							$sheet0->setCellValue("D".($start_row_data + $i), abs($saldo_modal));
						}
						
						$i++;
					}
					else {
						continue;
					}

				}
				$sheet0->setCellValue("A".($start_row_data + $i),'Total '.$detail_value['account_name_sub'])->mergeCells("A".($start_row_data + $i).":"."B".($start_row_data + $i));
				if ($data_key == 0) {
					if ($the_sub_total >= 0) {
						$sheet0->setCellValue("C".($start_row_data + $i), $the_sub_total);
						// echo $the_sub_total);
					} else if ($the_sub_total < 0) {
						$sheet0->setCellValue("C".($start_row_data + $i), abs($the_sub_total));
						// echo '('.abs($the_sub_total)).')';
					}
				}
				if($data_key == 1){
					if ($the_sub_total_modal >= 0) {
						$sheet0->setCellValue("D".($start_row_data + $i), $the_sub_total_modal);
					} else if ($the_sub_total_modal < 0) {
						$sheet0->setCellValue("D".($start_row_data + $i), abs($the_sub_total_modal));
					}
				}
				// $i++;

				$i = $i + 2;
			}
			$sheet0->setCellValue("A".($start_row_data + $i),'Total')->mergeCells("A".($start_row_data + $i).":"."B".($start_row_data + $i));
			if ($the_grand_total >= 0) {
				$sheet0->setCellValue("C".($start_row_data + $i), $the_grand_total);
            } else if ($the_grand_total < 0) {
				$sheet0->setCellValue("C".($start_row_data + $i), abs($the_grand_total));
            }
			if ($the_grand_total_modal >= 0) {
				$sheet0->setCellValue("D".($start_row_data + $i), $the_grand_total_modal);
			} else if ($the_grand_total_modal < 0) {
				$sheet0->setCellValue("D".($start_row_data + $i), abs($the_grand_total_modal));
			}
		}
		
		$sheet0->getStyle("A".$start_row_data.":D".($start_row_data + $i - 1))->applyFromArray($styleArray11);
		//write file and download
		$filename='Laporan Neraca '.Date('d-m-Y H:i:s').'.xls'; 
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename="'.$filename.'"'); 
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');  
		$objWriter->save('php://output');
	}
}