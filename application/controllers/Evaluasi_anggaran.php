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

		$this->load->view("evaluasi_anggaran/v_evaluasi_anggaran_print", $data);
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
												'kode_rekening' => $dss_value->kode_rekening,
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
											'kode_rekening' => $ds_value->kode_rekening,
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
		$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
		$this->db->join('paket_belanja_detail', 'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail');
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		$this->db->select('paket_belanja_detail_sub.idpaket_belanja_detail_sub, paket_belanja_detail_sub.idpaket_belanja_detail, paket_belanja_detail_sub.idkategori, kategori.nama_kategori, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, kode_rekening.kode_rekening, paket_belanja_detail_sub.is_kategori, paket_belanja_detail_sub.is_subkategori, akun_belanja.no_rekening_akunbelanja, paket_belanja_detail_sub.volume, satuan.nama_satuan, paket_belanja_detail_sub.harga_satuan, paket_belanja_detail_sub.jumlah');
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
		// $text_decoration_bulan_ke_1 = '';
		$tanggal_bulan_ke_1 = 0;
		$penyedia_bulan_ke_1 = 0;
		$volume_bulan_ke_1 = 0;
		$laki_bulan_ke_1 = 0;
		$perempuan_bulan_ke_1 = 0;
		$harga_satuan_bulan_ke_1 = 0;
		$ppn_bulan_ke_1 = 0;
		$pph_bulan_ke_1 = 0;
		$total_bulan_ke_1 = 0;
		// $text_decoration_bulan_ke_2 = '';
		$tanggal_bulan_ke_2 = 0;
		$penyedia_bulan_ke_2 = 0;
		$volume_bulan_ke_2 = 0;
		$laki_bulan_ke_2 = 0;
		$perempuan_bulan_ke_2 = 0;
		$harga_satuan_bulan_ke_2 = 0;
		$ppn_bulan_ke_2 = 0;
		$pph_bulan_ke_2 = 0;
		$total_bulan_ke_2 = 0;
		// $text_decoration_bulan_ke_3 = '';
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
			$text_decoration_bulan_ke_1 = '';
			$text_decoration_bulan_ke_2 = '';
			$text_decoration_bulan_ke_3 = '';

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

			if (strlen($ds_value->idkategori) == 0) {
				$capaian_sampai = ($realisasi_rp_sampai / $jumlah_anggaran) * 100;
				$capaian_sampai = round($capaian_sampai);
			}
			else {
				$capaian_sampai = 0;
			}

			$sisa_vol = $volume_anggaran - $realisasi_vol_sampai;
			$sisa_rp = $jumlah_anggaran - $realisasi_rp_sampai;

			$grand_sisa_vol += $sisa_vol;
			$grand_sisa_rp += $sisa_rp;

			if ($total_bulan_ke_1 == 0 || $total_bulan_ke_1 == '') {
				$text_decoration_bulan_ke_1 = 'color: red;';
			}
			if ($total_bulan_ke_2 == 0 || $total_bulan_ke_2 == '') {
				$text_decoration_bulan_ke_2 = 'color: red;';
			}
			if ($total_bulan_ke_3 == 0 || $total_bulan_ke_3 == '') {
				$text_decoration_bulan_ke_3 = 'color: red;';
			}

			$arr_detail[] = array(
				'idkategori' 					=> $ds_value->idkategori,
				'nama_kategori' 				=> $ds_value->nama_kategori,
				'idsub_kategori'	 			=> $ds_value->idsub_kategori,
				'nama_subkategori' 				=> $ds_value->nama_sub_kategori,

				// realisasi tw sebelumnya
				'realisasi_lk_sebelumnya'		=> $realisasi_lk_sebelumnya,
				'realisasi_pr_sebelumnya'		=> $realisasi_pr_sebelumnya,
				'realisasi_vol_sebelumnya'		=> $realisasi_vol_sebelumnya,
				'realisasi_rp_sebelumnya'		=> $realisasi_rp_sebelumnya,

				// Bulan ke 1
				'tanggal_bulan_ke_1'			=> $tanggal_bulan_ke_1,
				'penyedia_bulan_ke_1'			=> $penyedia_bulan_ke_1,
				'volume_bulan_ke_1'				=> $volume_bulan_ke_1,
				'laki_bulan_ke_1'				=> $laki_bulan_ke_1,
				'perempuan_bulan_ke_1'			=> $perempuan_bulan_ke_1,
				'harga_satuan_bulan_ke_1'		=> $harga_satuan_bulan_ke_1,
				'ppn_bulan_ke_1'				=> $ppn_bulan_ke_1,
				'pph_bulan_ke_1'				=> $pph_bulan_ke_1,
				'total_bulan_ke_1'				=> $total_bulan_ke_1,
				'text_decoration_bulan_ke_1'	=> $text_decoration_bulan_ke_1,
				
				// Bulan ke 2
				'tanggal_bulan_ke_2'			=> $tanggal_bulan_ke_2,
				'penyedia_bulan_ke_2'			=> $penyedia_bulan_ke_2,
				'volume_bulan_ke_2'				=> $volume_bulan_ke_2,
				'laki_bulan_ke_2'				=> $laki_bulan_ke_2,
				'perempuan_bulan_ke_2'			=> $perempuan_bulan_ke_2,
				'harga_satuan_bulan_ke_2'		=> $harga_satuan_bulan_ke_2,
				'ppn_bulan_ke_2'				=> $ppn_bulan_ke_2,
				'pph_bulan_ke_2'				=> $pph_bulan_ke_2,
				'total_bulan_ke_2'				=> $total_bulan_ke_2,
				'text_decoration_bulan_ke_2'	=> $text_decoration_bulan_ke_2,

				// Bulan ke 3
				'tanggal_bulan_ke_3'			=> $tanggal_bulan_ke_3,
				'penyedia_bulan_ke_3'			=> $penyedia_bulan_ke_3,
				'volume_bulan_ke_3'				=> $volume_bulan_ke_3,
				'laki_bulan_ke_3'				=> $laki_bulan_ke_3,
				'perempuan_bulan_ke_3'			=> $perempuan_bulan_ke_3,
				'harga_satuan_bulan_ke_3'		=> $harga_satuan_bulan_ke_3,
				'ppn_bulan_ke_3'				=> $ppn_bulan_ke_3,
				'pph_bulan_ke_3'				=> $pph_bulan_ke_3,
				'total_bulan_ke_3'				=> $total_bulan_ke_3,
				'text_decoration_bulan_ke_3'	=> $text_decoration_bulan_ke_3,

				// realisasi tw saat ini
				'realisasi_lk'					=> $realisasi_lk,
				'realisasi_pr'					=> $realisasi_pr,
				'realisasi_vol'					=> $realisasi_vol,
				'realisasi_rp'					=> $realisasi_rp,

				// total realisasi sampai tw saat ini
				'realisasi_lk_sampai'			=> $realisasi_lk_sampai,
				'realisasi_pr_sampai'			=> $realisasi_pr_sampai,
				'realisasi_vol_sampai'			=> $realisasi_vol_sampai,
				'realisasi_rp_sampai'			=> $realisasi_rp_sampai,

				// sisa realisasi
				'capaian_sampai'				=> $capaian_sampai,
				'sisa_vol'						=> $sisa_vol,
				'sisa_rp'						=> $sisa_rp,
			);



			// jika ada sub kategorinya
			$paket_belanja_detail_sub = $this->query_paket_belanja_detail_sub($ds_value->idpaket_belanja_detail_sub);
			// echo "<pre>"; print_r($this->db->last_query());die;

			foreach ($paket_belanja_detail_sub->result() as $dss_key => $dss_value) {
				$realisasi_lk = 0;
				$realisasi_pr = 0;
				$realisasi_vol = 0;
				$realisasi_rp = 0;
				$text_decoration_bulan_ke_1 = '';
				$text_decoration_bulan_ke_2 = '';
				$text_decoration_bulan_ke_3 = '';

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
					$this->db->where('transaction_detail.iduraian', $dss_value->idsub_kategori);
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

				$jumlah_anggaran = $dss_value->jumlah;
				$volume_anggaran = $dss_value->volume;
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

				if (strlen($dss_value->idkategori) == 0) {
					$capaian_sampai = ($realisasi_rp_sampai / $jumlah_anggaran) * 100;
					$capaian_sampai = round($capaian_sampai);
				}
				else {
					$capaian_sampai = 0;
				}

				$sisa_vol = $volume_anggaran - $realisasi_vol_sampai;
				$sisa_rp = $jumlah_anggaran - $realisasi_rp_sampai;

				$grand_sisa_vol += $sisa_vol;
				$grand_sisa_rp += $sisa_rp;

				if ($total_bulan_ke_1 == 0 || $total_bulan_ke_1 == '') {
					$text_decoration_bulan_ke_1 = 'color: red;';
				}
				if ($total_bulan_ke_2 == 0 || $total_bulan_ke_2 == '') {
					$text_decoration_bulan_ke_2 = 'color: red;';
				}
				if ($total_bulan_ke_3 == 0 || $total_bulan_ke_3 == '') {
					$text_decoration_bulan_ke_3 = 'color: red;';
				}

				$arr_detail[] = array(
					'idkategori' 					=> '',
					'nama_kategori' 				=> '',
					'idsub_kategori'	 			=> $dss_value->idsub_kategori,
					'nama_subkategori' 				=> $dss_value->nama_sub_kategori,

					// realisasi tw sebelumnya
					'realisasi_lk_sebelumnya'		=> $realisasi_lk_sebelumnya,
					'realisasi_pr_sebelumnya'		=> $realisasi_pr_sebelumnya,
					'realisasi_vol_sebelumnya'		=> $realisasi_vol_sebelumnya,
					'realisasi_rp_sebelumnya'		=> $realisasi_rp_sebelumnya,

					// Bulan ke 1
					'tanggal_bulan_ke_1'			=> $tanggal_bulan_ke_1,
					'penyedia_bulan_ke_1'			=> $penyedia_bulan_ke_1,
					'volume_bulan_ke_1'				=> $volume_bulan_ke_1,
					'laki_bulan_ke_1'				=> $laki_bulan_ke_1,
					'perempuan_bulan_ke_1'			=> $perempuan_bulan_ke_1,
					'harga_satuan_bulan_ke_1'		=> $harga_satuan_bulan_ke_1,
					'ppn_bulan_ke_1'				=> $ppn_bulan_ke_1,
					'pph_bulan_ke_1'				=> $pph_bulan_ke_1,
					'total_bulan_ke_1'				=> $total_bulan_ke_1,
					'text_decoration_bulan_ke_1'	=> $text_decoration_bulan_ke_1,
					
					// Bulan ke 2
					'tanggal_bulan_ke_2'			=> $tanggal_bulan_ke_2,
					'penyedia_bulan_ke_2'			=> $penyedia_bulan_ke_2,
					'volume_bulan_ke_2'				=> $volume_bulan_ke_2,
					'laki_bulan_ke_2'				=> $laki_bulan_ke_2,
					'perempuan_bulan_ke_2'			=> $perempuan_bulan_ke_2,
					'harga_satuan_bulan_ke_2'		=> $harga_satuan_bulan_ke_2,
					'ppn_bulan_ke_2'				=> $ppn_bulan_ke_2,
					'pph_bulan_ke_2'				=> $pph_bulan_ke_2,
					'total_bulan_ke_2'				=> $total_bulan_ke_2,
					'text_decoration_bulan_ke_2'	=> $text_decoration_bulan_ke_2,

					// Bulan ke 3
					'tanggal_bulan_ke_3'			=> $tanggal_bulan_ke_3,
					'penyedia_bulan_ke_3'			=> $penyedia_bulan_ke_3,
					'volume_bulan_ke_3'				=> $volume_bulan_ke_3,
					'laki_bulan_ke_3'				=> $laki_bulan_ke_3,
					'perempuan_bulan_ke_3'			=> $perempuan_bulan_ke_3,
					'harga_satuan_bulan_ke_3'		=> $harga_satuan_bulan_ke_3,
					'ppn_bulan_ke_3'				=> $ppn_bulan_ke_3,
					'pph_bulan_ke_3'				=> $pph_bulan_ke_3,
					'total_bulan_ke_3'				=> $total_bulan_ke_3,
					'text_decoration_bulan_ke_3'	=> $text_decoration_bulan_ke_3,

					// realisasi tw saat ini
					'realisasi_lk'					=> $realisasi_lk,
					'realisasi_pr'					=> $realisasi_pr,
					'realisasi_vol'					=> $realisasi_vol,
					'realisasi_rp'					=> $realisasi_rp,

					// total realisasi sampai tw saat ini
					'realisasi_lk_sampai'			=> $realisasi_lk_sampai,
					'realisasi_pr_sampai'			=> $realisasi_pr_sampai,
					'realisasi_vol_sampai'			=> $realisasi_vol_sampai,
					'realisasi_rp_sampai'			=> $realisasi_rp_sampai,

					// sisa realisasi
					'capaian_sampai'				=> $capaian_sampai,
					'sisa_vol'						=> $sisa_vol,
					'sisa_rp'						=> $sisa_rp,
				);
			}
		}
	
		if ($paket_belanja_detail->num_rows() > 0) {
			$grand_capaian_sampai = ($grand_realisasi_rp_sampai / $grand_total_anggaran) * 100;
			$grand_capaian_sampai = round($grand_capaian_sampai);	
		}

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
		// echo "<pre>"; print_r($arr_data);die();

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
}