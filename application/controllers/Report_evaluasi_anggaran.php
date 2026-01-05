<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_evaluasi_anggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_evaluasi_anggaran');
        $this->controller = 'report_evaluasi_anggaran';
		$this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_single_filter(false);
		$crud->set_btn_add(false);

		$tahun_anggaran = $azapp->add_datetime();
		$tahun_anggaran->set_id('tahun_anggaran');
		$tahun_anggaran->set_name('tahun_anggaran');
		$tahun_anggaran->set_value(Date('Y'));
		$tahun_anggaran->set_format('YYYY');
		$data['tahun_anggaran'] = $tahun_anggaran->render();

		$tahun_anggaran = $this->input->get('tahun_anggaran');
		if ($tahun_anggaran == null) {
			$tahun_anggaran = date("Y");
		}

		$data['tahun_anggaran'] = $tahun_anggaran;
		
		$the_filter = array();
		$the_filter = array(
			'tahun_anggaran' => $tahun_anggaran,
		);

		$get_data = $this->get_data($the_filter);

		$data['arr_data'] = $get_data;
		// echo "<pre>"; print_r($data);die;

		$js = az_add_js('report_evaluasi_anggaran/vjs_report_evaluasi_anggaran', $data, true);
		$azapp->add_js($js);

		$view = $this->load->view('report_evaluasi_anggaran/v_report_evaluasi_anggaran', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = 'Laporan Evaluasi Anggaran';
		$data_header['breadcrumb'] = array('report');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();
	}

	function print_report()
	{
		$tahun_anggaran = $this->uri->segment(3);

		$the_filter = array();
		$the_filter = array(
			'tahun_anggaran' => $tahun_anggaran,
		);

		$get_data = $this->get_data($the_filter);

		$data['tahun_anggaran'] = $tahun_anggaran;
		$data['arr_data'] = $get_data;
		// echo "<pre>"; print_r($data);die;

		$this->load->view("report_evaluasi_anggaran/v_report_evaluasi_anggaran_print", $data);
	}

	function get_data($the_data) {

		$tahun_anggaran = azarr($the_data, 'tahun_anggaran');

		$urusan_pemerintah = $this->query_urusan_pemerintah($tahun_anggaran);
		// echo "<pre>"; print_r($this->db->last_query());

		$arr_urusan = array();
		foreach ($urusan_pemerintah->result() as $key => $value) {
			$idurusan_pemerintah = $value->idurusan_pemerintah;
			$no_rek_urusan = $value->no_rekening_urusan;
			$nama_urusan = $value->nama_urusan;

			// bidang urusan
			$bidang_urusan = $this->query_bidang_urusan($idurusan_pemerintah);
			// echo "<pre>"; print_r($this->db->last_query());

			$arr_bidang_urusan = array();
			foreach ($bidang_urusan->result() as $bu_key => $bu_value) {
				$idbidang_urusan = $bu_value->idbidang_urusan;
				$no_rek_bidang_urusan = $bu_value->no_rekening_bidang_urusan;
				$nama_bidang_urusan = $bu_value->nama_bidang_urusan;
				$rekening_bidang = $no_rek_urusan.'.'.$no_rek_bidang_urusan;

				// program
				$program = $this->query_program($idbidang_urusan);
				// echo "<pre>"; print_r($this->db->last_query());

				$arr_program = array();
				foreach ($program->result() as $p_key => $p_value) {
					$idprogram = $p_value->idprogram;
					$no_rekening_program = $p_value->no_rekening_program;
					$nama_program = $p_value->nama_program;
					$rekening_program = $no_rek_urusan.'.'.$no_rek_bidang_urusan.'.'.$no_rekening_program;

					// kegiatan
					$kegiatan = $this->query_kegiatan($idprogram);
					// echo "<pre>"; print_r($this->db->last_query());

					$arr_kegiatan = array();
					foreach ($kegiatan->result() as $k_key => $k_value) {
						$idkegiatan = $k_value->idkegiatan;
						$no_rekening_kegiatan = $k_value->no_rekening_kegiatan;
						$nama_kegiatan = $k_value->nama_kegiatan;
						$rekening_kegiatan = $no_rek_urusan.'.'.$no_rek_bidang_urusan.'.'.$no_rekening_program.'.'.$no_rekening_kegiatan;

						// Sub Kegiatan
						$sub_kegiatan = $this->query_sub_kegiatan($idkegiatan);
						// echo "<pre>"; print_r($this->db->last_query());

						$arr_sub_kegiatan = array();
						foreach ($sub_kegiatan->result() as $sk_key => $sk_value) {
							$idsub_kegiatan = $sk_value->idsub_kegiatan;
							$no_rekening_subkegiatan = $sk_value->no_rekening_subkegiatan;
							$nama_subkegiatan = $sk_value->nama_subkegiatan;
							$rekening_subkegiatan = $no_rek_urusan.'.'.$no_rek_bidang_urusan.'.'.$no_rekening_program.'.'.$no_rekening_kegiatan.'.'.$no_rekening_subkegiatan;

							// Paket Belanja + anggaran
							$paket_belanja = $this->query_paket_belanja($idsub_kegiatan);
							// echo "<pre>"; print_r($this->db->last_query());

							$arr_paket_belanja = array();
							foreach ($paket_belanja->result() as $pb_key => $pb_value) {
								$idpaket_belanja = $pb_value->idpaket_belanja;
								$nama_paket_belanja = $pb_value->nama_paket_belanja;
								$nilai_anggaran = $pb_value->nilai_anggaran;

								// Akun Belanja
								$akun_belanja = $this->query_akun_belanja($idpaket_belanja);
								// echo "<pre>"; print_r($this->db->last_query());

								$arr_akun_belanja = array();
								foreach ($akun_belanja->result() as $pbd_key => $pbd_value) {
									$idpaket_belanja_detail = $pbd_value->idpaket_belanja_detail;
									$idakun_belanja = $pbd_value->idakun_belanja;
									$no_rekening_akunbelanja = $pbd_value->no_rekening_akunbelanja;
									$nama_akun_belanja = $pbd_value->nama_akun_belanja;

									// Kategori / Sub Kategori
									$paket_belanja_detail = $this->query_paket_belanja_detail($idpaket_belanja_detail);
									// echo "<pre>"; print_r($this->db->last_query());

									$arr_detail_sub = array();
									$total_jumlah = 0;
									$total_realisasi = 0;
									$total_persentase = 0;
									foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {
										$total_jumlah += $ds_value->jumlah;

										// get sub sub detail
										$paket_belanja_detail_sub = $this->query_paket_belanja_detail_sub($ds_value->idpaket_belanja_detail_sub);
										// echo "<pre>"; print_r($this->db->last_query());die;

										$arr_pd_detail_sub_sub = array();
										foreach ($paket_belanja_detail_sub->result() as $dss_key => $dss_value) {
											$total_jumlah += $dss_value->jumlah;

											$the_filter = array(
												'idpaket_belanja_detail' => $idpaket_belanja_detail,
												'idpaket_belanja' => $idpaket_belanja,
												'tahun_anggaran' => $tahun_anggaran,
												'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
												'is_sub_detail' => true,
											);

											$tw1 = array_merge($the_filter, array('tw' => 1) );
											$tw2 = array_merge($the_filter, array('tw' => 2) );
											$tw3 = array_merge($the_filter, array('tw' => 3) );
											$tw4 = array_merge($the_filter, array('tw' => 4) );

											// echo "<pre>"; print_r($the_filter); echo "<br>";

											$realisasi_sampai_tw1 = 0;
											$realisasi_sampai_tw2 = 0;
											$realisasi_sampai_tw3 = 0;
											$realisasi_sampai_tw4 = 0;

											$detail_tw1 = $this->get_detail_data($tw1);
											if (!empty($detail_tw1)) {
												$realisasi_sampai_tw1 = $detail_tw1['realisasi_rp_sampai'];
											}

											$detail_tw2 = $this->get_detail_data($tw2);
											if (!empty($detail_tw2)) {
												$realisasi_sampai_tw2 = $detail_tw2['realisasi_rp_sampai'];
											}

											$detail_tw3 = $this->get_detail_data($tw3);
											if (!empty($detail_tw3)) {
												$realisasi_sampai_tw3 = $detail_tw3['realisasi_rp_sampai'];
											}

											$detail_tw4 = $this->get_detail_data($tw4);
											if (!empty($detail_tw4)) {
												$realisasi_sampai_tw4 = $detail_tw4['realisasi_rp_sampai'];
											}

											$arr_pd_detail_sub_sub[] = array(
												'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
												'idpaket_belanja_detail' => $dss_value->idpaket_belanja_detail,
												'idsub_kategori' => $dss_value->idsub_kategori,
												'nama_subkategori' => $dss_value->nama_sub_kategori,
												'kode_rekening' => $dss_value->kode_rekening,
												'is_kategori' => $dss_value->is_kategori,
												'is_subkategori' => $dss_value->is_subkategori,
												'volume' => $dss_value->volume,
												'nama_satuan' => $dss_value->nama_satuan,
												'harga_satuan' => $dss_value->harga_satuan,
												'jumlah' => $dss_value->jumlah,
												'realisasi_sampai_tw1' => $realisasi_sampai_tw1,
												'realisasi_sampai_tw2' => $realisasi_sampai_tw2,
												'realisasi_sampai_tw3' => $realisasi_sampai_tw3,
												'realisasi_sampai_tw4' => $realisasi_sampai_tw4,
											);
										}

										// ambil data yang sudah terealisasi
										$this->db->where('purchase_plan.status', 1);
										$this->db->where('purchase_plan.purchase_plan_status = "SUDAH DIBAYAR BENDAHARA" ');
										$this->db->where('purchase_plan_detail.status', 1);
										$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
										$this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub', $ds_value->idpaket_belanja_detail_sub);

										$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');

										$this->db->select('sum(purchase_plan_detail.purchase_plan_detail_total) as total, sum(purchase_plan_detail.volume) as volume');
										$p_plan_s = $this->db->get('purchase_plan');
										// var_dump($this->db->last_query()); echo "<pre>";
										
										
										// $this->db->where('transaction.status', 1);
										// $this->db->where('transaction_detail.status', 1);
										// $this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
										// $this->db->where('transaction_detail.iduraian', $ds_value->idsub_kategori);
										// $this->db->where('transaction.transaction_status != "DRAFT" ');
										// $this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
										// $this->db->select('sum(total) as total');
										// $trxd = $this->db->get('transaction_detail');
										
										$nominal_realisasi = $ds_value->jumlah;
										$persentase_realisasi = 0;

										if ($p_plan_s->num_rows() > 0) {
											if ($p_plan_s->row()->total != NULL) {
												$nominal_realisasi = $p_plan_s->row()->total;
											}
											
											if (strlen($nominal_realisasi) > 0 && $nominal_realisasi != 0) {
												$persentase_realisasi = ($nominal_realisasi / $ds_value->jumlah) * 100;
											}
										}

										$total_realisasi += $nominal_realisasi;

										$the_filter = array(
											'idpaket_belanja_detail' => $idpaket_belanja_detail,
											'idpaket_belanja' => $idpaket_belanja,
											'tahun_anggaran' => $tahun_anggaran,
											'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
										);

										$tw1 = array_merge($the_filter, array('tw' => 1) );
										$tw2 = array_merge($the_filter, array('tw' => 2) );
										$tw3 = array_merge($the_filter, array('tw' => 3) );
										$tw4 = array_merge($the_filter, array('tw' => 4) );

										$realisasi_sampai_tw1 = 0;
										$realisasi_sampai_tw2 = 0;
										$realisasi_sampai_tw3 = 0;
										$realisasi_sampai_tw4 = 0;
										
										// jika ada sub detailnya, maka data detailnya tidak perlu ambil realisasi
										// contoh:
										// Belanja Barang dan Jasa BLUD => tidak perlu ambil data realiasi
										// 		Honorarium Dewan Pengawas BLUD : => tidak perlu ambil data realiasi
										// 			Ketua (1 org x 8 bulan) => perlu ambil data realiasi

										// Belanja Barang dan Jasa BLUD => tidak perlu ambil data realiasi
										// 		Pejabat Pengadaan (2 orang x 12 bulan) => perlu ambil data realiasi
										if ($paket_belanja_detail_sub->num_rows() == 0) {
											$detail_tw1 = $this->get_detail_data($tw1);
											if (!empty($detail_tw1)) {
												$realisasi_sampai_tw1 = $detail_tw1['realisasi_rp_sampai'];
											}

											$detail_tw2 = $this->get_detail_data($tw2);
											if (!empty($detail_tw2)) {
												$realisasi_sampai_tw2 = $detail_tw2['realisasi_rp_sampai'];
											}

											$detail_tw3 = $this->get_detail_data($tw3);
											if (!empty($detail_tw3)) {
												$realisasi_sampai_tw3 = $detail_tw3['realisasi_rp_sampai'];
											}

											$detail_tw4 = $this->get_detail_data($tw4);
											if (!empty($detail_tw4)) {
												$realisasi_sampai_tw4 = $detail_tw4['realisasi_rp_sampai'];
											}	
										}

										$arr_detail_sub[] = array(
											'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
											'idpaket_belanja_detail' => $ds_value->idpaket_belanja_detail,
											'idkategori' => $ds_value->idkategori,
											'nama_kategori' => $ds_value->nama_kategori,
											'idsub_kategori' => $ds_value->idsub_kategori,
											'nama_subkategori' => $ds_value->nama_sub_kategori,
											'kode_rekening' => $ds_value->kode_rekening,
											'is_kategori' => $ds_value->is_kategori,
											'is_subkategori' => $ds_value->is_subkategori,
											'no_rekening_akunbelanja' => $ds_value->no_rekening_akunbelanja,
											'volume' => $ds_value->volume,
											'nama_satuan' => $ds_value->nama_satuan,
											'harga_satuan' => $ds_value->harga_satuan,
											'jumlah' => $ds_value->jumlah,
											'realisasi_sampai_tw1' => $realisasi_sampai_tw1,
											'realisasi_sampai_tw2' => $realisasi_sampai_tw2,
											'realisasi_sampai_tw3' => $realisasi_sampai_tw3,
											'realisasi_sampai_tw4' => $realisasi_sampai_tw4,
											// 'nominal_realisasi' => $nominal_realisasi,
											// 'persentase_realisasi' => $persentase_realisasi,
											'arr_pd_detail_sub_sub' => $arr_pd_detail_sub_sub,
										);
									}

									if ( (strlen($total_realisasi) > 0 && $total_realisasi != 0) && (strlen($total_jumlah) > 0 && $total_jumlah != 0) ) {
										$total_persentase = ($total_realisasi / $total_jumlah) * 100;
									}

									$arr_akun_belanja[] = array(
										'idpaket_belanja_detail' => $idpaket_belanja_detail,
										'idakun_belanja' => $idakun_belanja,
										'no_rekening_akunbelanja' => $no_rekening_akunbelanja,
										'nama_akun_belanja' => $nama_akun_belanja,
										'total_jumlah' => $total_jumlah,
										'total_realisasi' => $total_realisasi,
										'total_persentase' => $total_persentase,
										'arr_detail_sub' => $arr_detail_sub,
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

	function query_urusan_pemerintah($tahun_anggaran) {
		$this->db->where('urusan_pemerintah.status', 1);
		$this->db->where('urusan_pemerintah.is_active', 1);
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $tahun_anggaran);
		$this->db->order_by('urusan_pemerintah.idurusan_pemerintah ASC');
		$this->db->select('idurusan_pemerintah, no_rekening_urusan, nama_urusan, tahun_anggaran_urusan');
		$urusan_pemerintah = $this->db->get('urusan_pemerintah');

		return $urusan_pemerintah;
	}

	function query_bidang_urusan($idurusan_pemerintah) {
		$this->db->where('bidang_urusan.status', 1);
			$this->db->where('bidang_urusan.is_active', 1);
			$this->db->where('bidang_urusan.idurusan_pemerintah', $idurusan_pemerintah);
			$this->db->order_by('bidang_urusan.idbidang_urusan ASC');
			$this->db->select('idbidang_urusan, no_rekening_bidang_urusan, nama_bidang_urusan');
			$bidang_urusan = $this->db->get('bidang_urusan');

		return $bidang_urusan;
	}

	function query_program($idbidang_urusan) {
		$this->db->where('program.status', 1);
		$this->db->where('program.is_active', 1);
		$this->db->where('program.idbidang_urusan', $idbidang_urusan);
		$this->db->order_by('program.idprogram ASC');
		$this->db->select('idprogram, no_rekening_program, nama_program');
		$program = $this->db->get('program');

		return $program;
	}

	function query_kegiatan($idprogram) {
		$this->db->where('kegiatan.status', 1);
		$this->db->where('kegiatan.is_active', 1);
		$this->db->where('kegiatan.idprogram', $idprogram);
		$this->db->order_by('kegiatan.idkegiatan ASC');
		$this->db->select('idkegiatan, no_rekening_kegiatan, nama_kegiatan');
		$kegiatan = $this->db->get('kegiatan');

		return $kegiatan;
	}

	function query_sub_kegiatan($idkegiatan) {
		$this->db->where('sub_kegiatan.status', 1);
		$this->db->where('sub_kegiatan.is_active', 1);
		$this->db->where('sub_kegiatan.idkegiatan', $idkegiatan);
		$this->db->order_by('sub_kegiatan.idsub_kegiatan ASC');
		$this->db->select('idsub_kegiatan, no_rekening_subkegiatan, nama_subkegiatan');
		$sub_kegiatan = $this->db->get('sub_kegiatan');

		return $sub_kegiatan;
	}

	function query_paket_belanja($idsub_kegiatan) {

		// testing
		$this->db->where('nama_paket_belanja = "Pengadaan Pakaian Dinas beserta Atribut Kelengkapannya" ');


		$this->db->where('paket_belanja.status', 1);
		$this->db->where('paket_belanja.status_paket_belanja = "OK" ');
		$this->db->where('paket_belanja.idsub_kegiatan', $idsub_kegiatan);
		$this->db->order_by('paket_belanja.idpaket_belanja ASC');
		$this->db->select('idpaket_belanja, nama_paket_belanja, nilai_anggaran');
		$paket_belanja = $this->db->get('paket_belanja');

		return $paket_belanja;
	}

	function query_akun_belanja($idpaket_belanja) {
		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->order_by('paket_belanja_detail.idpaket_belanja_detail ASC');
		$this->db->select('paket_belanja_detail.idpaket_belanja_detail, akun_belanja.idakun_belanja, akun_belanja.no_rekening_akunbelanja, akun_belanja.nama_akun_belanja');
		$akun_belanja = $this->db->get('paket_belanja_detail');

		return $akun_belanja;
	}

	function query_paket_belanja_detail($idpaket_belanja_detail, $idpaket_belanja_detail_sub = null, $is_sub_detail = false) {
		$query_akun_belanja = '';
		if (!$is_sub_detail) {
			$query_akun_belanja = ', akun_belanja.no_rekening_akunbelanja';
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail', $idpaket_belanja_detail);
		}
		if (strlen($idpaket_belanja_detail_sub) > 0 ) {
			$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		}
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
		$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
		if (!$is_sub_detail) {
			$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
			$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		}
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, kode_rekening.kode_rekening, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah'.$query_akun_belanja);
		$paket_belanja_detail = $this->db->get('paket_belanja_detail_sub');

		// if ($paket_belanja_detail->num_rows() > 0) {
		// 	$idkategori = $paket_belanja_detail->row()->idkategori;
		// 	$idpaket_belanja_detail_sub = $paket_belanja_detail->row()->idpaket_belanja_detail_sub;
		// 	if (strlen($idkategori) > 0) {
		// 		$paket_belanja_detail = $this->query_paket_belanja_detail_sub($idpaket_belanja_detail_sub, true);
		// 	}
		// }

		return $paket_belanja_detail;
	}

	function query_paket_belanja_detail_sub($idpaket_belanja_detail_sub, $join_kategori = false) {
		$query_category = '';
		if ($join_kategori) {
			$query_category = ', "" as nama_kategori, "" as no_rekening_akunbelanja';
		}

		$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, kode_rekening.kode_rekening, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah'.$query_category);
		$paket_belanja_detail_sub = $this->db->get('paket_belanja_detail_sub');

		return $paket_belanja_detail_sub;
	}

	function get_detail_data($the_data) {
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja');
		$idpaket_belanja_detail = azarr($the_data, 'idpaket_belanja_detail');
		$idpaket_belanja_detail_sub = azarr($the_data, 'idpaket_belanja_detail_sub');
		$tw = azarr($the_data, 'tw');
		$tahun_anggaran = azarr($the_data, 'tahun_anggaran');
		$is_sub_detail = azarr($the_data, 'is_sub_detail', false);

		// echo "<pre>"; print_r($the_data);die;

		if ($tw == 1) {
			$mulai_bulan = 1;
		}
		else if ($tw == 2) {
			$mulai_bulan = 4;
		}
		else if ($tw == 3) {
			$mulai_bulan = 7;
		}
		else if ($tw == 4) {
			$mulai_bulan = 10;
		}

		$tw_sebelumnya = $tw - 1;
		if ($tw_sebelumnya == 0) {
			$tw_sebelumnya = "";
		}

		$volume_bulan_ke_1 = 0;
		$total_bulan_ke_1 = 0;
		$volume_bulan_ke_2 = 0;
		$total_bulan_ke_2 = 0;
		$volume_bulan_ke_3 = 0;
		$total_bulan_ke_3 = 0;
		$realisasi_vol_sebelumnya = 0;
		$realisasi_rp_sebelumnya = 0;
		
		$realisasi_vol_sampai = 0;
		$realisasi_rp_sampai = 0;

		// Kategori / Sub Kategori
		$paket_belanja_detail = $this->query_paket_belanja_detail($idpaket_belanja_detail, $idpaket_belanja_detail_sub, $is_sub_detail);
		// echo "<pre>"; print_r($this->db->last_query());

		$arr_detail = array();
		foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {

			$realisasi_vol = 0;
			$realisasi_rp = 0;

			if ($tw == 1) {
				$mulai_bulan = 1;
			}
			else if ($tw == 2) {
				$mulai_bulan = 4;
			}
			else if ($tw == 3) {
				$mulai_bulan = 7;
			}
			else if ($tw == 4) {
				$mulai_bulan = 10;
			}

			if ($tw > 1) {
				$arr_tw_sebelumnya = array(
					'tw_sebelumnya' => $tw - 1,
					'tahun_anggaran' => $tahun_anggaran,
					'idpaket_belanja' => $idpaket_belanja,
					'idsub_kategori' => $ds_value->idsub_kategori,
					'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
				);

				$get_tw_sebelumnya = $this->get_tw_sebelumnya($arr_tw_sebelumnya);

				$realisasi_vol_sebelumnya = $get_tw_sebelumnya['realisasi_vol_sebelumnya'];
				$realisasi_rp_sebelumnya = $get_tw_sebelumnya['realisasi_rp_sebelumnya'];
			}
			
			for ($i=0; $i < 3; $i++) {
				$filter_bulan = $tahun_anggaran.'-'.$mulai_bulan;

				$this->db->where('purchase_plan.status', 1);
				$this->db->where('purchase_plan.purchase_plan_status = "SUDAH DIBAYAR BENDAHARA" ');
				$this->db->where('purchase_plan_detail.status', 1);
				$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
				$this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub', $ds_value->idpaket_belanja_detail_sub);
				$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") = "'.Date('Y-m', strtotime($filter_bulan)).'"');
				$this->db->where('budget_realization_detail.idsub_kategori = "'.$ds_value->idsub_kategori.'" ');
				$this->db->where('contract_detail.status', 1);
				$this->db->where('contract.status', 1);
				$this->db->where('budget_realization.status', 1);
				$this->db->where('budget_realization_detail.status', 1);
				$this->db->where('budget_realization_detail.idpurchase_plan_detail = purchase_plan_detail.idpurchase_plan_detail');

				$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
				$this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
				$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
				$this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
				$this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');

				$this->db->select('DATE_FORMAT(MAX(purchase_plan.purchase_plan_date), "%d-%m-%Y") as purchase_plan_date, 
        		MAX(budget_realization_detail.provider) as provider, sum(budget_realization_detail.volume) as volume, sum(budget_realization_detail.male) as male, sum(budget_realization_detail.female) as female, sum(budget_realization_detail.unit_price) as unit_price, sum(ppn) as ppn, sum(pph) as pph, sum(budget_realization_detail.total_realization_detail) as total');
				$p_plan = $this->db->get('purchase_plan');
				// echo "<pre>"; print_r($this->db->last_query());

				// $this->db->where('transaction.status', 1);
				// $this->db->where('transaction_detail.status', 1);
				// $this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") = "'.Date('Y-m', strtotime($filter_bulan)).'"');
				// $this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
				// $this->db->where('transaction_detail.iduraian', $ds_value->idsub_kategori);
				// $this->db->where('transaction.transaction_status != "DRAFT" ');
				// $this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
				// $this->db->select('DATE_FORMAT(MAX(transaction.transaction_date), "%d-%m-%Y") as transaction_date, 
        		// MAX(penyedia) as penyedia, sum(volume) as volume, sum(laki) as laki, sum(perempuan) as perempuan, sum(harga_satuan) as harga_satuan, sum(ppn) as ppn, sum(pph) as pph, sum(total) as total');
				// $trxd = $this->db->get('transaction_detail');

				if ($p_plan->num_rows() > 0) {
					if ($i == 0) {
						$volume_bulan_ke_1 			= $p_plan->row()->volume;
						$total_bulan_ke_1 			= $p_plan->row()->total;

						$realisasi_vol += $volume_bulan_ke_1;
						$realisasi_rp += $total_bulan_ke_1;
					}
					else if ($i == 1) {
						$volume_bulan_ke_2 			= $p_plan->row()->volume;
						$total_bulan_ke_2 			= $p_plan->row()->total;

						$realisasi_vol += $volume_bulan_ke_2;
						$realisasi_rp += $total_bulan_ke_2;
					}
					else if ($i == 2) {
						$volume_bulan_ke_3 			= $p_plan->row()->volume;
						$total_bulan_ke_3 			= $p_plan->row()->total;

						$realisasi_vol += $volume_bulan_ke_3;
						$realisasi_rp += $total_bulan_ke_3;
					}
				}

				$mulai_bulan++;
			}

			$realisasi_vol_sampai = $realisasi_vol + $realisasi_vol_sebelumnya;
			$realisasi_rp_sampai = $realisasi_rp + $realisasi_rp_sebelumnya;

			$arr_detail = array(
				'idpaket_belanja_detail_sub' 	=> $ds_value->idpaket_belanja_detail_sub,
				'idkategori' 					=> $ds_value->idkategori,
				'nama_kategori' 				=> $ds_value->nama_kategori,
				'idsub_kategori'	 			=> $ds_value->idsub_kategori,
				'nama_subkategori' 				=> $ds_value->nama_sub_kategori,

				// total realisasi sampai tw saat ini
				'realisasi_vol_sampai'			=> $realisasi_vol_sampai,
				'realisasi_rp_sampai'			=> $realisasi_rp_sampai,
			);

			// jika ada sub kategorinya
			$paket_belanja_detail_sub = $this->query_paket_belanja_detail_sub($ds_value->idpaket_belanja_detail_sub);
			// echo "<pre>"; print_r($this->db->last_query());die;

			foreach ($paket_belanja_detail_sub->result() as $dss_key => $dss_value) {
				$realisasi_vol = 0;
				$realisasi_rp = 0;

				if ($tw == 1) {
					$mulai_bulan = 1;
				}
				else if ($tw == 2) {
					$mulai_bulan = 4;
				}
				else if ($tw == 3) {
					$mulai_bulan = 7;
				}
				else if ($tw == 4) {
					$mulai_bulan = 10;
				}

				if ($tw > 1) {
					$arr_tw_sebelumnya = array(
						'tw_sebelumnya' => $tw - 1,
						'tahun_anggaran' => $tahun_anggaran,
						'idpaket_belanja' => $idpaket_belanja,
						'idsub_kategori' => $dss_value->idsub_kategori,
						'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
					);

					$get_tw_sebelumnya = $this->get_tw_sebelumnya($arr_tw_sebelumnya);

					$realisasi_vol_sebelumnya = $get_tw_sebelumnya['realisasi_vol_sebelumnya'];
					$realisasi_rp_sebelumnya = $get_tw_sebelumnya['realisasi_rp_sebelumnya'];
				}
				
				for ($i=0; $i < 3; $i++) {
					$filter_bulan = $tahun_anggaran.'-'.$mulai_bulan;

					$this->db->where('purchase_plan.status', 1);
					$this->db->where('purchase_plan.purchase_plan_status = "SUDAH DIBAYAR BENDAHARA" ');
					$this->db->where('purchase_plan_detail.status', 1);
					$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
					$this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub', $ds_value->idpaket_belanja_detail_sub);
					$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") = "'.Date('Y-m', strtotime($filter_bulan)).'"');
					$this->db->where('budget_realization_detail.idsub_kategori = "'.$ds_value->idsub_kategori.'" ');
					$this->db->where('contract_detail.status', 1);
					$this->db->where('contract.status', 1);
					$this->db->where('budget_realization.status', 1);
					$this->db->where('budget_realization_detail.status', 1);
					$this->db->where('budget_realization_detail.idpurchase_plan_detail = purchase_plan_detail.idpurchase_plan_detail');

					$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
					$this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
					$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
					$this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
					$this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');

					$this->db->select('DATE_FORMAT(MAX(purchase_plan.purchase_plan_date), "%d-%m-%Y") as purchase_plan_date, 
					MAX(budget_realization_detail.provider) as provider, sum(budget_realization_detail.volume) as volume, sum(budget_realization_detail.male) as male, sum(budget_realization_detail.female) as female, sum(budget_realization_detail.unit_price) as unit_price, sum(ppn) as ppn, sum(pph) as pph, sum(budget_realization_detail.total_realization_detail) as total');
					$p_plan = $this->db->get('purchase_plan');
					// echo "<pre>"; print_r($this->db->last_query());
					
					// $this->db->where('transaction.status', 1);
					// $this->db->where('transaction_detail.status', 1);
					// $this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") = "'.Date('Y-m', strtotime($filter_bulan)).'"');
					// $this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
					// $this->db->where('transaction_detail.iduraian', $dss_value->idsub_kategori);
					// $this->db->where('transaction.transaction_status != "DRAFT" ');
					// $this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
					// $this->db->select('DATE_FORMAT(MAX(transaction.transaction_date), "%d-%m-%Y") as transaction_date, 
					// MAX(penyedia) as penyedia, sum(volume) as volume, sum(laki) as laki, sum(perempuan) as perempuan, sum(harga_satuan) as harga_satuan, sum(ppn) as ppn, sum(pph) as pph, sum(total) as total');
					// $trxd = $this->db->get('transaction_detail');

					if ($p_plan->num_rows() > 0) {
						if ($i == 0) {
							$volume_bulan_ke_1 			= $p_plan->row()->volume;
							$total_bulan_ke_1 			= $p_plan->row()->total;

							$realisasi_vol += $volume_bulan_ke_1;
							$realisasi_rp += $total_bulan_ke_1;
						}
						else if ($i == 1) {
							$volume_bulan_ke_2 			= $p_plan->row()->volume;
							$total_bulan_ke_2 			= $p_plan->row()->total;

							$realisasi_vol += $volume_bulan_ke_2;
							$realisasi_rp += $total_bulan_ke_2;
						}
						else if ($i == 2) {
							$volume_bulan_ke_3 			= $p_plan->row()->volume;
							$total_bulan_ke_3 			= $p_plan->row()->total;

							$realisasi_vol += $volume_bulan_ke_3;
							$realisasi_rp += $total_bulan_ke_3;
						}
					}

					$mulai_bulan++;
				}

				$realisasi_vol_sampai = $realisasi_vol + $realisasi_vol_sebelumnya;
				$realisasi_rp_sampai = $realisasi_rp + $realisasi_rp_sebelumnya;

				$arr_detail = array(
					'idpaket_belanja_detail_sub' 	=> $dss_value->idpaket_belanja_detail_sub,
					'idkategori' 					=> '',
					'nama_kategori' 				=> '',
					'idsub_kategori'	 			=> $dss_value->idsub_kategori,
					'nama_subkategori' 				=> $dss_value->nama_sub_kategori,

					// total realisasi sampai tw saat ini
					'realisasi_vol_sampai'			=> $realisasi_vol_sampai,
					'realisasi_rp_sampai'			=> $realisasi_rp_sampai,
				);
			}
		}
	
		// echo "<pre>"; print_r($arr_detail);die();

		return $arr_detail;

		// $view = $this->load->view('evaluasi_anggaran/v_evaluasi_anggaran_table', $arr_data, true);
		// $arr = array(
		// 	'data' => $view
		// );
		// echo json_encode($arr);
	}

	function get_tw_sebelumnya($the_data) {
		$tw_sebelumnya = $the_data['tw_sebelumnya'];
		$tahun_anggaran = $the_data['tahun_anggaran'];
		$idpaket_belanja = $the_data['idpaket_belanja'];
		$idsub_kategori = $the_data['idsub_kategori'];
		$idpaket_belanja_detail_sub = $the_data['idpaket_belanja_detail_sub'];

		$realisasi_lk_sebelumnya = 0;
		$realisasi_pr_sebelumnya = 0;
		$realisasi_vol_sebelumnya = 0;
		$realisasi_rp_sebelumnya = 0;

		if ($tw_sebelumnya == 1) {
			$sampai_bulan = 3;
		}
		else if ($tw_sebelumnya == 2) {
			$sampai_bulan = 6;
		}
		else if ($tw_sebelumnya == 3) {
			$sampai_bulan = 9;
		}

		$filter_bulan = $tahun_anggaran.'-'.$sampai_bulan;
		$awal_tahun = $tahun_anggaran.'-01-01';


		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan.purchase_plan_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") >= "'.Date('Y-m', strtotime($awal_tahun)).'"');
		$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") <= "'.Date('Y-m', strtotime($filter_bulan)).'"');
		$this->db->where('budget_realization_detail.idsub_kategori = "'.$idsub_kategori.'" ');
		$this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);

		$this->db->where('contract_detail.status', 1);
		$this->db->where('contract.status', 1);
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization_detail.status', 1);
		$this->db->where('budget_realization_detail.idpurchase_plan_detail = purchase_plan_detail.idpurchase_plan_detail');

		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
		$this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
		$this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
		$this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');

		$this->db->select('DATE_FORMAT(MAX(purchase_plan.purchase_plan_date), "%d-%m-%Y") as purchase_plan_date, 
		MAX(budget_realization_detail.provider) as provider, sum(budget_realization_detail.volume) as volume, sum(budget_realization_detail.male) as male, sum(budget_realization_detail.female) as female, sum(budget_realization_detail.unit_price) as unit_price, sum(ppn) as ppn, sum(pph) as pph, sum(budget_realization_detail.total_realization_detail) as total');
		$p_plan_d = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query());

		// $this->db->where('transaction.status', 1);
		// $this->db->where('transaction_detail.status', 1);
		// $this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") >= "'.Date('Y-m', strtotime($awal_tahun)).'"');
		// $this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") <= "'.Date('Y-m', strtotime($filter_bulan)).'"');
		// $this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
		// $this->db->where('transaction_detail.iduraian', $idsub_kategori);
		// $this->db->where('transaction.transaction_status != "DRAFT" ');
		// $this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
		// $this->db->select('DATE_FORMAT(MAX(transaction.transaction_date), "%d-%m-%Y") as transaction_date, 
        // MAX(penyedia) as penyedia, sum(volume) as volume, sum(laki) as laki, sum(perempuan) as perempuan, sum(harga_satuan) as harga_satuan, sum(ppn) as ppn, sum(pph) as pph, sum(total) as total');
		// $trxd = $this->db->get('transaction_detail');

		if ($p_plan_d->num_rows() > 0) {
			$realisasi_lk_sebelumnya 	+= $p_plan_d->row()->male;
			$realisasi_pr_sebelumnya 	+= $p_plan_d->row()->female;
			$realisasi_vol_sebelumnya 	+= $p_plan_d->row()->volume;
			$realisasi_rp_sebelumnya 	+= $p_plan_d->row()->total;
		}

		$return = array(
			'realisasi_lk_sebelumnya' => $realisasi_lk_sebelumnya,
			'realisasi_pr_sebelumnya' => $realisasi_pr_sebelumnya,
			'realisasi_vol_sebelumnya' => $realisasi_vol_sebelumnya,
			'realisasi_rp_sebelumnya' => $realisasi_rp_sebelumnya,
		);
		// echo "<pre>"; print_r($return);die;

		return $return;
	}
}