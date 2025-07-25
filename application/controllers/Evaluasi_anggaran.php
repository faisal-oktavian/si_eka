<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluasi_anggaran extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('evaluasi_anggaran');
        $this->controller = 'evaluasi_anggaran';
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

		$v_modal = $this->load->view('evaluasi_anggaran/v_evaluasi_anggaran_modal', $data, true);
		$modal = $azapp->add_modal();
		$modal->set_id('detail_realisasi');
		$modal->set_modal_title('Detail Realisasi');
		$modal->set_modal($v_modal);
		$azapp->add_content($modal->render());

		$js = az_add_js('evaluasi_anggaran/vjs_evaluasi_anggaran', $data, true);
		$azapp->add_js($js);

		$view = $this->load->view('evaluasi_anggaran/v_evaluasi_anggaran', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = 'Evaluasi Anggaran';
		$data_header['breadcrumb'] = array('evaluasi_anggaran');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();
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

											$arr_pd_detail_sub_sub[] = array(
												'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
												'idpaket_belanja_detail' => $dss_value->idpaket_belanja_detail,
												'idsub_kategori' => $dss_value->idsub_kategori,
												'nama_subkategori' => $dss_value->nama_sub_kategori,
												'is_kategori' => $dss_value->is_kategori,
												'is_subkategori' => $dss_value->is_subkategori,
												'volume' => $dss_value->volume,
												'nama_satuan' => $dss_value->nama_satuan,
												'harga_satuan' => $dss_value->harga_satuan,
												'jumlah' => $dss_value->jumlah,
											);
										}

										// ambil data yang sudah terealisasi
										$this->db->where('transaction.status', 1);
										$this->db->where('transaction_detail.status', 1);
										$this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
										$this->db->where('transaction_detail.iduraian', $ds_value->idsub_kategori);
										$this->db->where('transaction.transaction_status != "DRAFT" ');
										$this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
										$this->db->select('sum(total) as total');
										$trxd = $this->db->get('transaction_detail');
										// var_dump($this->db->last_query()); echo "<pre>";
										
										$nominal_realisasi = $ds_value->jumlah;
										$persentase_realisasi = 0;

										if ($trxd->num_rows() > 0) {
											if ($trxd->row()->total != NULL) {
												$nominal_realisasi = $trxd->row()->total;
											}
											
											if (strlen($nominal_realisasi) > 0 && $nominal_realisasi != 0) {
												$persentase_realisasi = ($nominal_realisasi / $ds_value->jumlah) * 100;
											}
										}

										$total_realisasi += $nominal_realisasi;

										$arr_detail_sub[] = array(
											'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
											'idpaket_belanja_detail' => $ds_value->idpaket_belanja_detail,
											'idkategori' => $ds_value->idkategori,
											'nama_kategori' => $ds_value->nama_kategori,
											'idsub_kategori' => $ds_value->idsub_kategori,
											'nama_subkategori' => $ds_value->nama_sub_kategori,
											'is_kategori' => $ds_value->is_kategori,
											'is_subkategori' => $ds_value->is_subkategori,
											'no_rekening_akunbelanja' => $ds_value->no_rekening_akunbelanja,
											'volume' => $ds_value->volume,
											'nama_satuan' => $ds_value->nama_satuan,
											'harga_satuan' => $ds_value->harga_satuan,
											'jumlah' => $ds_value->jumlah,
											'nominal_realisasi' => $nominal_realisasi,
											'persentase_realisasi' => $persentase_realisasi,
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

	function get_detail_data() {
		$idpaket_belanja_detail = $this->input->post("idpaket_belanja_detail");
		$tw = $this->input->post("tw");
		$tahun_anggaran = $this->input->post("tahun_anggaran");

		if ($tw == 1) {
			$nama_bulan_ke_1 = 'Januari';
			$nama_bulan_ke_2 = 'Februari';
			$nama_bulan_ke_3 = 'Maret';
			$mulai_bulan = 1;
		}
		else if ($tw == 2) {
			$nama_bulan_ke_1 = 'April';
			$nama_bulan_ke_2 = 'Mei';
			$nama_bulan_ke_3 = 'Juni';
			$mulai_bulan = 4;
		}
		else if ($tw == 3) {
			$nama_bulan_ke_1 = 'Juli';
			$nama_bulan_ke_2 = 'Agustus';
			$nama_bulan_ke_3 = 'September';
			$mulai_bulan = 7;
		}
		else if ($tw == 4) {
			$nama_bulan_ke_1 = 'Oktober';
			$nama_bulan_ke_2 = 'November';
			$nama_bulan_ke_3 = 'Desember';
			$mulai_bulan = 10;
		}

		$tw_sebelumnya = $tw - 1;
		if ($tw_sebelumnya == 0) {
			$tw_sebelumnya = "";
		}

		$grand_realisasi_lk_sebelumnya = 0;
		$grand_realisasi_pr_sebelumnya = 0;
		$grand_realisasi_vol_sebelumnya = 0;
		$grand_realisasi_rp_sebelumnya = 0;
		$grand_bulan_ke_1 = 0;
		$grand_bulan_ke_2 = 0;
		$grand_bulan_ke_3 = 0;
		$grand_realisasi_lk = 0;
		$grand_realisasi_pr = 0;
		$grand_realisasi_vol = 0;
		$grand_realisasi_rp = 0;
		$grand_realisasi_lk_sampai = 0;
		$grand_realisasi_pr_sampai = 0;
		$grand_realisasi_vol_sampai = 0;
		$grand_realisasi_rp_sampai = 0;
		$grand_capaian_sampai = 0;
		$grand_sisa_vol = 0;
		$grand_sisa_rp = 0;
		$tanggal_bulan_ke_1 = 0;
		$penyedia_bulan_ke_1 = 0;
		$volume_bulan_ke_1 = 0;
		$laki_bulan_ke_1 = 0;
		$perempuan_bulan_ke_1 = 0;
		$harga_satuan_bulan_ke_1 = 0;
		$ppn_bulan_ke_1 = 0;
		$pph_bulan_ke_1 = 0;
		$total_bulan_ke_1 = 0;
		$tanggal_bulan_ke_2 = 0;
		$penyedia_bulan_ke_2 = 0;
		$volume_bulan_ke_2 = 0;
		$laki_bulan_ke_2 = 0;
		$perempuan_bulan_ke_2 = 0;
		$harga_satuan_bulan_ke_2 = 0;
		$ppn_bulan_ke_2 = 0;
		$pph_bulan_ke_2 = 0;
		$total_bulan_ke_2 = 0;
		$tanggal_bulan_ke_3 = 0;
		$penyedia_bulan_ke_3 = 0;
		$volume_bulan_ke_3 = 0;
		$laki_bulan_ke_3 = 0;
		$perempuan_bulan_ke_3 = 0;
		$harga_satuan_bulan_ke_3 = 0;
		$ppn_bulan_ke_3 = 0;
		$pph_bulan_ke_3 = 0;
		$total_bulan_ke_3 = 0;
		$realisasi_lk_sebelumnya = 0;
		$realisasi_pr_sebelumnya = 0;
		$realisasi_vol_sebelumnya = 0;
		$realisasi_rp_sebelumnya = 0;
		
		$realisasi_lk_sampai = 0;
		$realisasi_pr_sampai = 0;
		$realisasi_vol_sampai = 0;
		$realisasi_rp_sampai = 0;
		$capaian_sampai = 0;
		$sisa_vol = 0;
		$sisa_rp = 0;

		$grand_total_anggaran = 0;


		// paket belanja
		$this->db->where('paket_belanja_detail.idpaket_belanja_detail', $idpaket_belanja_detail);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->select('paket_belanja_detail.idpaket_belanja, akun_belanja.nama_akun_belanja');
		$pb_detail = $this->db->get('paket_belanja_detail');
		$idpaket_belanja = $pb_detail->row()->idpaket_belanja;
		$nama_akun_belanja = $pb_detail->row()->nama_akun_belanja;

		// Kategori / Sub Kategori
		$paket_belanja_detail = $this->query_paket_belanja_detail($idpaket_belanja_detail);
		// echo "<pre>"; print_r($this->db->last_query());

		$arr_detail = array();
		foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {

			// cek apakah ada turunannya
			// $this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $ds_value->idpaket_belanja_detail_sub);
			// $this->db->where('paket_belanja_detail_sub.status', 1);
			// $this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
			// $this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');
			// $this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, sub_kategori.no_rekening_subkategori, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
			// $paket_belanja_detail_sub = $this->db->get('paket_belanja_detail_sub');

			// if ($paket_belanja_detail_sub->num_rows() > 0) {
			// 	// jika ada turunannya, maka menampilkan datanya berdasarkan turunannya ini
			// }
			// else {
			// 	// jika tidak ada turunannya, maka langsung jalankan code dibawah 
			// }
			
			$realisasi_lk = 0;
			$realisasi_pr = 0;
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
				);

				$get_tw_sebelumnya = $this->get_tw_sebelumnya($arr_tw_sebelumnya);

				$realisasi_lk_sebelumnya = $get_tw_sebelumnya['realisasi_lk_sebelumnya'];
				$realisasi_pr_sebelumnya = $get_tw_sebelumnya['realisasi_pr_sebelumnya'];
				$realisasi_vol_sebelumnya = $get_tw_sebelumnya['realisasi_vol_sebelumnya'];
				$realisasi_rp_sebelumnya = $get_tw_sebelumnya['realisasi_rp_sebelumnya'];
			}
			
			for ($i=0; $i < 3; $i++) {
				$filter_bulan = $tahun_anggaran.'-'.$mulai_bulan;

				$this->db->where('transaction.status', 1);
				$this->db->where('transaction_detail.status', 1);
				$this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") = "'.Date('Y-m', strtotime($filter_bulan)).'"');
				$this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
				$this->db->where('transaction_detail.iduraian', $ds_value->idsub_kategori);
				$this->db->where('transaction.transaction_status != "DRAFT" ');
				$this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
				$this->db->select('DATE_FORMAT(MAX(transaction.transaction_date), "%d-%m-%Y") as transaction_date, 
        		MAX(penyedia) as penyedia, sum(volume) as volume, sum(laki) as laki, sum(perempuan) as perempuan, sum(harga_satuan) as harga_satuan, sum(ppn) as ppn, sum(pph) as pph, sum(total) as total');
				$trxd = $this->db->get('transaction_detail');
				// echo "<pre>"; print_r($this->db->last_query());

				if ($trxd->num_rows() > 0) {
					if ($i == 0) {
						$tanggal_bulan_ke_1 		= $trxd->row()->transaction_date;
						$penyedia_bulan_ke_1 		= $trxd->row()->penyedia;
						$volume_bulan_ke_1 			= $trxd->row()->volume;
						$laki_bulan_ke_1 			= $trxd->row()->laki;
						$perempuan_bulan_ke_1 		= $trxd->row()->perempuan;
						$harga_satuan_bulan_ke_1 	= $trxd->row()->harga_satuan;
						$ppn_bulan_ke_1 			= $trxd->row()->ppn;
						$pph_bulan_ke_1 			= $trxd->row()->pph;
						$total_bulan_ke_1 			= $trxd->row()->total;

						$grand_bulan_ke_1 += $total_bulan_ke_1;
						$realisasi_lk += $laki_bulan_ke_1;
						$realisasi_pr += $perempuan_bulan_ke_1;
						$realisasi_vol += $volume_bulan_ke_1;
						$realisasi_rp += $total_bulan_ke_1;
					}
					else if ($i == 1) {
						$tanggal_bulan_ke_2 		= $trxd->row()->transaction_date;
						$penyedia_bulan_ke_2 		= $trxd->row()->penyedia;
						$volume_bulan_ke_2 			= $trxd->row()->volume;
						$laki_bulan_ke_2 			= $trxd->row()->laki;
						$perempuan_bulan_ke_2 		= $trxd->row()->perempuan;
						$harga_satuan_bulan_ke_2 	= $trxd->row()->harga_satuan;
						$ppn_bulan_ke_2 			= $trxd->row()->ppn;
						$pph_bulan_ke_2 			= $trxd->row()->pph;
						$total_bulan_ke_2 			= $trxd->row()->total;

						$grand_bulan_ke_2 += $total_bulan_ke_2;
						$realisasi_lk += $laki_bulan_ke_2;
						$realisasi_pr += $perempuan_bulan_ke_2;
						$realisasi_vol += $volume_bulan_ke_2;
						$realisasi_rp += $total_bulan_ke_2;
					}
					else if ($i == 2) {
						$tanggal_bulan_ke_3 		= $trxd->row()->transaction_date;
						$penyedia_bulan_ke_3 		= $trxd->row()->penyedia;
						$volume_bulan_ke_3 			= $trxd->row()->volume;
						$laki_bulan_ke_3 			= $trxd->row()->laki;
						$perempuan_bulan_ke_3 		= $trxd->row()->perempuan;
						$harga_satuan_bulan_ke_3 	= $trxd->row()->harga_satuan;
						$ppn_bulan_ke_3 			= $trxd->row()->ppn;
						$pph_bulan_ke_3 			= $trxd->row()->pph;
						$total_bulan_ke_3 			= $trxd->row()->total;

						$grand_bulan_ke_3 += $total_bulan_ke_3;
						$realisasi_lk += $laki_bulan_ke_3;
						$realisasi_pr += $perempuan_bulan_ke_3;
						$realisasi_vol += $volume_bulan_ke_3;
						$realisasi_rp += $total_bulan_ke_3;
					}
				}

				$mulai_bulan++;
			}

			$jumlah_anggaran = $ds_value->jumlah;
			$volume_anggaran = $ds_value->volume;
			$grand_total_anggaran += $jumlah_anggaran;

			$grand_realisasi_lk += $realisasi_lk;
			$grand_realisasi_pr += $realisasi_pr;
			$grand_realisasi_vol += $realisasi_vol;
			$grand_realisasi_rp += $realisasi_rp;

			$grand_realisasi_lk_sebelumnya += $realisasi_lk_sebelumnya;
			$grand_realisasi_pr_sebelumnya += $realisasi_pr_sebelumnya;
			$grand_realisasi_vol_sebelumnya += $realisasi_vol_sebelumnya;
			$grand_realisasi_rp_sebelumnya += $realisasi_rp_sebelumnya;

			$realisasi_lk_sampai = $realisasi_lk + $realisasi_lk_sebelumnya;
			$realisasi_pr_sampai = $realisasi_pr + $realisasi_pr_sebelumnya;
			$realisasi_vol_sampai = $realisasi_vol + $realisasi_vol_sebelumnya;
			$realisasi_rp_sampai = $realisasi_rp + $realisasi_rp_sebelumnya;

			$grand_realisasi_lk_sampai += $realisasi_lk_sampai;
			$grand_realisasi_pr_sampai += $realisasi_pr_sampai;
			$grand_realisasi_vol_sampai += $realisasi_vol_sampai;
			$grand_realisasi_rp_sampai += $realisasi_rp_sampai;

			$capaian_sampai = ($realisasi_rp_sampai / $jumlah_anggaran) * 100;
			$capaian_sampai = round($capaian_sampai);

			$sisa_vol = $volume_anggaran - $realisasi_vol_sampai;
			$sisa_rp = $jumlah_anggaran - $realisasi_rp_sampai;

			$grand_sisa_vol += $sisa_vol;
			$grand_sisa_rp += $sisa_rp;

			$arr_detail[] = array(
				'idkategori' 				=> $ds_value->idkategori,
				'nama_kategori' 			=> $ds_value->nama_kategori,
				'idsub_kategori'	 		=> $ds_value->idsub_kategori,
				'nama_subkategori' 			=> $ds_value->nama_sub_kategori,

				// realisasi tw sebelumnya
				'realisasi_lk_sebelumnya'	=> $realisasi_lk_sebelumnya,
				'realisasi_pr_sebelumnya'	=> $realisasi_pr_sebelumnya,
				'realisasi_vol_sebelumnya'	=> $realisasi_vol_sebelumnya,
				'realisasi_rp_sebelumnya'	=> $realisasi_rp_sebelumnya,

				// Bulan ke 1
				'tanggal_bulan_ke_1'		=> $tanggal_bulan_ke_1,
				'penyedia_bulan_ke_1'		=> $penyedia_bulan_ke_1,
				'volume_bulan_ke_1'			=> $volume_bulan_ke_1,
				'laki_bulan_ke_1'			=> $laki_bulan_ke_1,
				'perempuan_bulan_ke_1'		=> $perempuan_bulan_ke_1,
				'harga_satuan_bulan_ke_1'	=> $harga_satuan_bulan_ke_1,
				'ppn_bulan_ke_1'			=> $ppn_bulan_ke_1,
				'pph_bulan_ke_1'			=> $pph_bulan_ke_1,
				'total_bulan_ke_1'			=> $total_bulan_ke_1,
				
				// Bulan ke 2
				'tanggal_bulan_ke_2'		=> $tanggal_bulan_ke_2,
				'penyedia_bulan_ke_2'		=> $penyedia_bulan_ke_2,
				'volume_bulan_ke_2'			=> $volume_bulan_ke_2,
				'laki_bulan_ke_2'			=> $laki_bulan_ke_2,
				'perempuan_bulan_ke_2'		=> $perempuan_bulan_ke_2,
				'harga_satuan_bulan_ke_2'	=> $harga_satuan_bulan_ke_2,
				'ppn_bulan_ke_2'			=> $ppn_bulan_ke_2,
				'pph_bulan_ke_2'			=> $pph_bulan_ke_2,
				'total_bulan_ke_2'			=> $total_bulan_ke_2,

				// Bulan ke 3
				'tanggal_bulan_ke_3'		=> $tanggal_bulan_ke_3,
				'penyedia_bulan_ke_3'		=> $penyedia_bulan_ke_3,
				'volume_bulan_ke_3'			=> $volume_bulan_ke_3,
				'laki_bulan_ke_3'			=> $laki_bulan_ke_3,
				'perempuan_bulan_ke_3'		=> $perempuan_bulan_ke_3,
				'harga_satuan_bulan_ke_3'	=> $harga_satuan_bulan_ke_3,
				'ppn_bulan_ke_3'			=> $ppn_bulan_ke_3,
				'pph_bulan_ke_3'			=> $pph_bulan_ke_3,
				'total_bulan_ke_3'			=> $total_bulan_ke_3,

				// realisasi tw saat ini
				'realisasi_lk'				=> $realisasi_lk,
				'realisasi_pr'				=> $realisasi_pr,
				'realisasi_vol'				=> $realisasi_vol,
				'realisasi_rp'				=> $realisasi_rp,

				// total realisasi sampai tw saat ini
				'realisasi_lk_sampai'		=> $realisasi_lk_sampai,
				'realisasi_pr_sampai'		=> $realisasi_pr_sampai,
				'realisasi_vol_sampai'		=> $realisasi_vol_sampai,
				'realisasi_rp_sampai'		=> $realisasi_rp_sampai,

				// sisa realisasi
				'capaian_sampai'			=> $capaian_sampai,
				'sisa_vol'					=> $sisa_vol,
				'sisa_rp'					=> $sisa_rp,
			);
		}
		 
		$grand_capaian_sampai = ($grand_realisasi_rp_sampai / $grand_total_anggaran) * 100;
		$grand_capaian_sampai = round($grand_capaian_sampai);

		$arr_data['data'] = array(
			'idpaket_belanja_detail' 		=> $idpaket_belanja_detail,
			'nama_bulan_ke_1'				=> $nama_bulan_ke_1,
			'nama_bulan_ke_2'				=> $nama_bulan_ke_2,
			'nama_bulan_ke_3'				=> $nama_bulan_ke_3,
			'tw'							=> $tw,
			
			'tw_sebelumnya'					=> $tw_sebelumnya,
			'nama_akun_belanja'				=> $nama_akun_belanja,
			'grand_realisasi_lk_sebelumnya'	=> $grand_realisasi_lk_sebelumnya,
			'grand_realisasi_pr_sebelumnya'	=> $grand_realisasi_pr_sebelumnya,
			'grand_realisasi_vol_sebelumnya'=> $grand_realisasi_vol_sebelumnya,
			'grand_realisasi_rp_sebelumnya'	=> $grand_realisasi_rp_sebelumnya,
			
			'grand_bulan_ke_1'				=> $grand_bulan_ke_1,
			'grand_bulan_ke_2'				=> $grand_bulan_ke_2,
			'grand_bulan_ke_3'				=> $grand_bulan_ke_3,
			
			'grand_realisasi_lk'			=> $grand_realisasi_lk,
			'grand_realisasi_pr'			=> $grand_realisasi_pr,
			'grand_realisasi_vol'			=> $grand_realisasi_vol,
			'grand_realisasi_rp'			=> $grand_realisasi_rp,

			'grand_realisasi_lk_sampai'		=> $grand_realisasi_lk_sampai,
			'grand_realisasi_pr_sampai'		=> $grand_realisasi_pr_sampai,
			'grand_realisasi_vol_sampai'	=> $grand_realisasi_vol_sampai,
			'grand_realisasi_rp_sampai'		=> $grand_realisasi_rp_sampai,

			'grand_capaian_sampai'			=> $grand_capaian_sampai,
			'grand_sisa_vol'				=> $grand_sisa_vol,
			'grand_sisa_rp'					=> $grand_sisa_rp,
			
			'arr_detail' 					=> $arr_detail,
		);
		// echo "<pre>"; print_r($arr_data);die;

		$view = $this->load->view('evaluasi_anggaran/v_evaluasi_anggaran_table', $arr_data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function get_tw_sebelumnya($the_data) {
		$tw_sebelumnya = $the_data['tw_sebelumnya'];
		$tahun_anggaran = $the_data['tahun_anggaran'];
		$idpaket_belanja = $the_data['idpaket_belanja'];
		$idsub_kategori = $the_data['idsub_kategori'];

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

		$this->db->where('transaction.status', 1);
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") >= "'.Date('Y-m', strtotime($awal_tahun)).'"');
		$this->db->where('DATE_FORMAT(transaction.transaction_date, "%Y-%m") <= "'.Date('Y-m', strtotime($filter_bulan)).'"');
		$this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('transaction_detail.iduraian', $idsub_kategori);
		$this->db->where('transaction.transaction_status != "DRAFT" ');
		$this->db->join('transaction', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->select('DATE_FORMAT(MAX(transaction.transaction_date), "%d-%m-%Y") as transaction_date, 
        MAX(penyedia) as penyedia, sum(volume) as volume, sum(laki) as laki, sum(perempuan) as perempuan, sum(harga_satuan) as harga_satuan, sum(ppn) as ppn, sum(pph) as pph, sum(total) as total');
		$trxd = $this->db->get('transaction_detail');
		// echo "<pre>"; print_r($this->db->last_query());

		if ($trxd->num_rows() > 0) {
			$realisasi_lk_sebelumnya 	+= $trxd->row()->laki;
			$realisasi_pr_sebelumnya 	+= $trxd->row()->perempuan;
			$realisasi_vol_sebelumnya 	+= $trxd->row()->volume;
			$realisasi_rp_sebelumnya 	+= $trxd->row()->total;
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