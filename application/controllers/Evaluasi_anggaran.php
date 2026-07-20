<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluasi_anggaran extends CI_Controller {

    private $master_select = [
        'urusan' => '
            idurusan_pemerintah,
            no_rekening_urusan,
            nama_urusan,
            tahun_anggaran_urusan
        ',

        'bidang_urusan' => '
            idbidang_urusan,
            no_rekening_bidang_urusan,
            nama_bidang_urusan
        ',

        'program' => '
            idprogram,
            no_rekening_program,
            nama_program
        ',

        'kegiatan' => '
            idkegiatan,
            no_rekening_kegiatan,
            nama_kegiatan
        ',

        'sub_kegiatan' => '
            idsub_kegiatan,
            no_rekening_subkegiatan,
            nama_subkegiatan
        ',

        'paket_belanja' => '
            idpaket_belanja,
            nama_paket_belanja,
            nilai_anggaran
        ',

        'akun_belanja' => '
            paket_belanja_detail.idpaket_belanja_detail,
            akun_belanja.idakun_belanja,
            akun_belanja.no_rekening_akunbelanja,
            akun_belanja.nama_akun_belanja
        '
    ];

	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('evaluasi_anggaran');
        $this->controller = 'evaluasi_anggaran';
		$this->load->helper('az_crud');
        $this->load->helper('az_config');
		$this->load->helper('transaction_status_helper');
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

		$nama_paket_belanja = $this->input->get('paket_belanja');
		$tahun_anggaran = $this->input->get('tahun_anggaran');
		if ($tahun_anggaran == null) {
			$tahun_anggaran = date("Y");
		}

		$data['tahun_anggaran'] = $tahun_anggaran;
		
		$the_filter = array();
		$the_filter = array(
			'tahun_anggaran' => $tahun_anggaran,
			'nama_paket_belanja' => $nama_paket_belanja,
		);

		$data['arr_data'] = [
			'urusan' => []
		];
		$data['filter'] = $the_filter;
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

	public function get_lazy_data()
	{
		$tahun_anggaran = $this->input->post('tahun_anggaran') ?: $this->input->get('tahun_anggaran');
		$nama_paket_belanja = $this->input->post('nama_paket_belanja') ?: $this->input->get('paket_belanja');
		$page = (int) ($this->input->post('page') ?: $this->input->get('page') ?: 1);
		$batch_size = (int) ($this->input->post('batch_size') ?: $this->input->get('batch_size') ?: 20);

		$page = max($page, 1);
		$batch_size = max($batch_size, 1);
		$offset = ($page - 1) * $batch_size;

		$the_filter = [
			'tahun_anggaran' => $tahun_anggaran,
			'nama_paket_belanja' => $nama_paket_belanja,
		];

		$paket_rows = $this->query_lazy_paket_belanja($the_filter, $offset, $batch_size)->result();
		$total_count = $this->count_lazy_paket_belanja($the_filter);
		$total_anggaran = $this->get_total_anggaran_for_filter($the_filter);

		$result_urusan = [];
		$urusan_map = [];
		$bidang_map = [];
		$program_map = [];
		$kegiatan_map = [];
		$sub_kegiatan_map = [];

		foreach ($paket_rows as $paket) {
			$arr_akun = [];
			$total_data = 0;
			$total_done = 0;
			$total_potensi_sisa = 0;
			$total_persentase_target = 0;
			$total_persentase_realisasi = 0;
			$total_realisasi_pb = 0;

			$akun_list = $this->query_akun_belanja($paket->idpaket_belanja)->result();

			foreach ($akun_list as $akun) {
				$detail_data = $this->build_detail_sub($akun, $paket, $tahun_anggaran);

				$total_realisasi_pb += $detail_data['total_realisasi'];
				$total_data += $detail_data['total_data'];
				$total_done += $detail_data['total_done'];

				$arr_akun[] = [
					'idpaket_belanja_detail'  => $akun->idpaket_belanja_detail,
					'idakun_belanja'          => $akun->idakun_belanja,
					'no_rekening_akunbelanja' => $akun->no_rekening_akunbelanja,
					'nama_akun_belanja'       => $akun->nama_akun_belanja,
					'total_jumlah'            => $detail_data['total_jumlah'],
					'total_sisa_anggaran'     => $detail_data['total_sisa_uang'],
					'total_realisasi'         => $detail_data['total_realisasi'],
					'total_persentase_sisa'   => $detail_data['total_persentase'],
					'arr_detail_sub'          => $detail_data['detail']
				];
			}

			if ($total_data == $total_done) {
				$total_potensi_sisa = az_thousand_separator_decimal($detail_data['total_sisa_uang'] ?? 0);
			} else {
				$total_potensi_sisa = '-';
			}

			if ($total_anggaran > 0 && isset($paket->nilai_anggaran)) {
				$total_persentase_target = ($paket->nilai_anggaran / $total_anggaran) * 100;
			}

			if ($total_realisasi_pb > 0 && isset($paket->nilai_anggaran)) {
				$total_persentase_realisasi = ($total_realisasi_pb / $paket->nilai_anggaran) * 100;
			}

			$paket_payload = [
				'idpaket_belanja'          => $paket->idpaket_belanja,
				'nama_paket_belanja'       => $paket->nama_paket_belanja,
				'nilai_anggaran'           => $paket->nilai_anggaran,
				'potensi_sisa'             => $total_potensi_sisa,
				'total_realisasi_pb'       => $total_realisasi_pb,
				'total_persentase_target'  => $total_persentase_target,
				'total_persentase_realisasi' => $total_persentase_realisasi,
				'akun_belanja'             => $arr_akun
			];

			$urusan_key = $paket->idurusan_pemerintah;
			if (!isset($urusan_map[$urusan_key])) {
				$urusan_map[$urusan_key] = [
					'idurusan' => $paket->idurusan_pemerintah,
					'nama_urusan' => $this->generate_nama_urusan((object)[
						'no_rekening_urusan' => $paket->no_rekening_urusan,
						'nama_urusan' => $paket->nama_urusan,
						'tahun_anggaran_urusan' => $paket->tahun_anggaran_urusan
					]),
					'bidang_urusan' => []
				];
			}

			$bidang_key = $paket->idbidang_urusan;
			if (!isset($urusan_map[$urusan_key]['bidang_urusan'][$bidang_key])) {
				$urusan_map[$urusan_key]['bidang_urusan'][$bidang_key] = [
					'idbidang_urusan' => $paket->idbidang_urusan,
					'nama_bidang_urusan' => $this->generate_nama_bidang(
						(object)['no_rekening_urusan' => $paket->no_rekening_urusan],
						(object)['no_rekening_bidang_urusan' => $paket->no_rekening_bidang_urusan, 'nama_bidang_urusan' => $paket->nama_bidang_urusan]
					),
					'program' => []
				];
			}

			$program_key = $paket->idprogram;
			if (!isset($urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key])) {
				$urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key] = [
					'idprogram' => $paket->idprogram,
					'nama_program' => $this->generate_nama_program(
						(object)['no_rekening_urusan' => $paket->no_rekening_urusan],
						(object)['no_rekening_bidang_urusan' => $paket->no_rekening_bidang_urusan],
						(object)['no_rekening_program' => $paket->no_rekening_program, 'nama_program' => $paket->nama_program]
					),
					'kegiatan' => []
				];
			}

			$kegiatan_key = $paket->idkegiatan;
			if (!isset($urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key]['kegiatan'][$kegiatan_key])) {
				$urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key]['kegiatan'][$kegiatan_key] = [
					'idkegiatan' => $paket->idkegiatan,
					'nama_kegiatan' => $this->generate_nama_kegiatan(
						(object)['no_rekening_urusan' => $paket->no_rekening_urusan],
						(object)['no_rekening_bidang_urusan' => $paket->no_rekening_bidang_urusan],
						(object)['no_rekening_program' => $paket->no_rekening_program],
						(object)['no_rekening_kegiatan' => $paket->no_rekening_kegiatan, 'nama_kegiatan' => $paket->nama_kegiatan]
					),
					'sub_kegiatan' => []
				];
			}

			$sub_kegiatan_key = $paket->idsub_kegiatan;
			if (!isset($urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key]['kegiatan'][$kegiatan_key]['sub_kegiatan'][$sub_kegiatan_key])) {
				$urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key]['kegiatan'][$kegiatan_key]['sub_kegiatan'][$sub_kegiatan_key] = [
					'idsub_kegiatan' => $paket->idsub_kegiatan,
					'nama_sub_kegiatan' => $this->generate_nama_sub_kegiatan(
						(object)['no_rekening_urusan' => $paket->no_rekening_urusan],
						(object)['no_rekening_bidang_urusan' => $paket->no_rekening_bidang_urusan],
						(object)['no_rekening_program' => $paket->no_rekening_program],
						(object)['no_rekening_kegiatan' => $paket->no_rekening_kegiatan],
						(object)['no_rekening_subkegiatan' => $paket->no_rekening_subkegiatan, 'nama_subkegiatan' => $paket->nama_subkegiatan]
					),
					'paket_belanja' => []
				];
			}

			$urusan_map[$urusan_key]['bidang_urusan'][$bidang_key]['program'][$program_key]['kegiatan'][$kegiatan_key]['sub_kegiatan'][$sub_kegiatan_key]['paket_belanja'][] = $paket_payload;
		}

		foreach ($urusan_map as &$urusan_entry) {
			$urusan_entry['bidang_urusan'] = array_values($urusan_entry['bidang_urusan']);
			foreach ($urusan_entry['bidang_urusan'] as &$bidang_entry) {
				$bidang_entry['program'] = array_values($bidang_entry['program']);
				foreach ($bidang_entry['program'] as &$program_entry) {
					$program_entry['kegiatan'] = array_values($program_entry['kegiatan']);
					foreach ($program_entry['kegiatan'] as &$kegiatan_entry) {
						$kegiatan_entry['sub_kegiatan'] = array_values($kegiatan_entry['sub_kegiatan']);
					}
				}
			}
		}

		$result_urusan = array_values($urusan_map);

		$response = [
			'status' => true,
			'data' => $this->load->view('evaluasi_anggaran/v_evaluasi_anggaran_rows', ['arr_data' => ['urusan' => $result_urusan]], true),
			'has_more' => ($offset + count($paket_rows)) < $total_count,
			'next_page' => ($offset + count($paket_rows)) < $total_count ? $page + 1 : null,
			'page' => $page,
			'loaded_count' => count($paket_rows)
		];

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
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
		$nama_paket_belanja = azarr($the_data, 'nama_paket_belanja');

		
		// Hitung total anggaran pada tahun ini
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
        $this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->where('paket_belanja.status', 1);
		$this->db->where('paket_belanja.is_active', 1);
		$this->db->where('paket_belanja.status_paket_belanja = "OK" ');
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan = "'.$tahun_anggaran.'" ');
		if (strlen($nama_paket_belanja) > 0) {
			$this->db->where('paket_belanja.nama_paket_belanja = "'.$nama_paket_belanja.'" ');
		}
		$this->db->select_sum('paket_belanja.nilai_anggaran');
		$pb = $this->db->get('paket_belanja');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$total_anggaran = 0;
		if ($pb->num_rows() > 0) {
			$total_anggaran = $pb->row()->nilai_anggaran;
		}


		$result_urusan = [];

        $urusan_list = $this->query_urusan_pemerintah($tahun_anggaran)->result();

		foreach ($urusan_list as $urusan) {

			$arr_bidang = [];

            $bidang_list = $this->query_bidang_urusan($urusan->idurusan_pemerintah)->result();

            foreach ($bidang_list as $bidang) {

				$arr_program = [];

                $program_list = $this->query_program($bidang->idbidang_urusan)->result();

                foreach ($program_list as $program) {

					$arr_kegiatan = [];

                    $kegiatan_list = $this->query_kegiatan($program->idprogram)->result();

                    foreach ($kegiatan_list as $kegiatan) {
					
						$arr_sub_kegiatan = [];

                        $sub_kegiatan_list = $this->query_sub_kegiatan($kegiatan->idkegiatan)->result();

                        foreach ($sub_kegiatan_list as $sub_kegiatan) {

							$arr_paket = [];

                            $paket_list = $this->query_paket_belanja($sub_kegiatan->idsub_kegiatan, $nama_paket_belanja)->result();
							// echo "<pre>"; print_r($this->db->last_query()); die;
                            
							foreach ($paket_list as $paket) {

								$arr_akun = [];
								$total_data = 0;
								$total_done = 0;
								$total_potensi_sisa = 0;
								$total_persentase_target = 0;
								$total_persentase_realisasi = 0;
								$total_realisasi_pb = 0;

                                $akun_list = $this->query_akun_belanja($paket->idpaket_belanja)->result();

                                foreach ($akun_list as $akun) {

									$detail_data = $this->build_detail_sub($akun, $paket, $tahun_anggaran);

									$total_realisasi_pb += $detail_data['total_realisasi'];
                                    $total_potensi_sisa += $detail_data['total_sisa_uang'];
                                    $total_data += $detail_data['total_data'];
                                    $total_done += $detail_data['total_done'];

                                    $arr_akun[] = array(
                                        'idpaket_belanja_detail'  => $akun->idpaket_belanja_detail,
                                        'idakun_belanja'          => $akun->idakun_belanja,
                                        'no_rekening_akunbelanja' => $akun->no_rekening_akunbelanja,
                                        'nama_akun_belanja'       => $akun->nama_akun_belanja,
                                        'total_jumlah'            => $detail_data['total_jumlah'],
                                        'total_sisa_anggaran'     => $detail_data['total_sisa_uang'],
                                        'total_realisasi'         => $detail_data['total_realisasi'],
                                        'total_persentase_sisa'   => $detail_data['total_persentase'],
                                        'arr_detail_sub'          => $detail_data['detail']
                                    );
                                    // echo "<pre>"; print_r($detail_data);die;
								}
								
								if ($total_data == $total_done) {
									$total_potensi_sisa = az_thousand_separator_decimal($detail_data['total_sisa_uang'] ?? 0);
								}
								else {
									$total_potensi_sisa = '-';
								}

								$total_persentase_target = ($paket->nilai_anggaran / $total_anggaran) * 100; // nilai anggaran per paket belanja dibandingkan total anggaran
								if ($total_realisasi_pb > 0) {
									$total_persentase_realisasi = ($total_realisasi_pb / $paket->nilai_anggaran) * 100; // realisasi per paket belanja dibandingkan nilai anggaran
								}

                                $arr_paket[] = array(
                                    'idpaket_belanja'    			=> $paket->idpaket_belanja,
                                    'nama_paket_belanja' 			=> $paket->nama_paket_belanja,
                                    'nilai_anggaran'     			=> $paket->nilai_anggaran,
									'potensi_sisa' 					=> $total_potensi_sisa,
									'total_realisasi_pb' 			=> $total_realisasi_pb,
									'total_persentase_target'		=> $total_persentase_target,
									'total_persentase_realisasi' 	=> $total_persentase_realisasi,
                                    'akun_belanja'       			=> $arr_akun
                                );
							}

                            $arr_sub_kegiatan[] = array(
                                'idsub_kegiatan' => $sub_kegiatan->idsub_kegiatan,
                                'nama_sub_kegiatan' => $this->generate_nama_sub_kegiatan(
                                    $urusan,
                                    $bidang,
                                    $program,
                                    $kegiatan,
                                    $sub_kegiatan
                                ),
                                'paket_belanja' => $arr_paket
                            );
						}

                        $arr_kegiatan[] = array(
                            'idkegiatan'    => $kegiatan->idkegiatan,
                            'nama_kegiatan' => $this->generate_nama_kegiatan(
                                $urusan,
                                $bidang,
                                $program,
                                $kegiatan
                            ),
                            'sub_kegiatan' => $arr_sub_kegiatan
                        );
					}

                    $arr_program[] = array(
                        'idprogram'    => $program->idprogram,
                        'nama_program' => $this->generate_nama_program(
                            $urusan,
                            $bidang,
                            $program
                        ),
                        'kegiatan' => $arr_kegiatan
                    );
				}

                $arr_bidang[] = array(
                    'idbidang_urusan' => $bidang->idbidang_urusan,
                    'nama_bidang_urusan' => $this->generate_nama_bidang(
                        $urusan,
                        $bidang
                    ),
                    'program' => $arr_program
                );
			}

			$result_urusan[] = array(
                'idurusan' => $urusan->idurusan_pemerintah,
                'nama_urusan' => $this->generate_nama_urusan($urusan),
                'bidang_urusan' => $arr_bidang
            );
		}

		// echo "<pre>"; print_r($result_urusan);die;

		return array(
            'tahun_anggaran' => $tahun_anggaran,
			'total_anggaran' => $total_anggaran,
            'urusan'         => $result_urusan
        );
	}



	/*
    |--------------------------------------------------------------------------
    | BUILD DETAIL
    |--------------------------------------------------------------------------
    */

    private function build_detail_sub($akun, $paket, $tahun_anggaran) {
        $details = $this->query_paket_belanja_detail($akun->idpaket_belanja_detail)->result();

        $result_detail     = [];
        $total_jumlah      = 0;
        $total_sisa_uang   = 0;
        $total_realisasi   = 0;
        $total_persentase  = 0;
        $total_data       = 0;
        $total_done       = 0;

        $detail_ids = array_map(function ($detail) {
            return $detail->idpaket_belanja_detail_sub;
        }, $details);

        $child_sub_rows = $this->query_paket_belanja_detail_sub_by_detail_ids($detail_ids)->result();
        $child_sub_by_parent = [];
        $realisasi_ids = [];
        $idsub_categories = [];

        foreach ($details as $detail) {
            $realisasi_ids[] = $detail->idpaket_belanja_detail_sub;
            $idsub_categories[] = $detail->idsub_kategori;
        }

        foreach ($child_sub_rows as $sub_sub) {
            $child_sub_by_parent[$sub_sub->is_idpaket_belanja_detail_sub][] = $sub_sub;
            $realisasi_ids[] = $sub_sub->idpaket_belanja_detail_sub;
            $idsub_categories[] = $sub_sub->idsub_kategori;
        }

        // echo "<pre>";
        // print_r($paket->idpaket_belanja);
        // print_r(array_unique($realisasi_ids));
        // print_r(array_unique($idsub_categories));
        // print_r($tahun_anggaran);
        // die;

        $realisasi_map = $this->build_realisasi_map(
            $paket->idpaket_belanja,
            array_unique($realisasi_ids),
            array_unique($idsub_categories),
            $tahun_anggaran
        );
        // echo "<pre>"; print_r($realisasi_map);die;

        foreach ($details as $detail) {
            $total_jumlah += $detail->jumlah;
            $arr_sub_sub = [];

            $child_sub = $child_sub_by_parent[$detail->idpaket_belanja_detail_sub] ?? [];

            foreach ($child_sub as $sub_sub) {
                $total_jumlah += $sub_sub->jumlah;

                $tw_data = $this->build_tw_data_for_subdetail(
                    $sub_sub->idpaket_belanja_detail_sub,
                    $sub_sub->jumlah,
                    $realisasi_map
                );

                $nominal_realisasi = $tw_data['realisasi_sampai_tw4'];
                $total_realisasi += $tw_data['realisasi_sampai_tw4'];

                $volume_realisasi = $realisasi_map[$sub_sub->idpaket_belanja_detail_sub]['volume_realisasi'] ?? 0;
                $sisa_volume = $sub_sub->volume - $volume_realisasi;

                $uang_realisasi = $realisasi_map[$sub_sub->idpaket_belanja_detail_sub]['uang_realisasi'] ?? 0;
                $sisa_uang = $sub_sub->jumlah - $uang_realisasi;

                $total_sisa_uang += $sisa_uang;

                if ($sub_sub->is_subkategori == 1) {
                    $total_data++;
                    if ($sisa_volume == 0) {
                        $total_done++;
                    }
                }

                $arr_sub_sub[] = array_merge([
                    'idpaket_belanja_detail_sub' => $sub_sub->idpaket_belanja_detail_sub,
                    'idpaket_belanja_detail'     => $sub_sub->idpaket_belanja_detail,
                    'idsub_kategori'             => $sub_sub->idsub_kategori,
                    'nama_subkategori'           => $sub_sub->nama_sub_kategori,
                    'kode_rekening'              => $sub_sub->kode_rekening,
                    'is_kategori'                => $sub_sub->is_kategori,
                    'is_subkategori'             => $sub_sub->is_subkategori,
                    'volume'                     => $sub_sub->volume,
                    'nama_satuan'                => $sub_sub->nama_satuan,
                    'harga_satuan'               => $sub_sub->harga_satuan,
                    'jumlah'                     => $sub_sub->jumlah,
                    'volume_realisasi'           => $volume_realisasi,
                    'sisa_volume'                => $sisa_volume,
                    'sisa_uang'                  => $sisa_uang,
                    'nominal_realisasi'          => $nominal_realisasi,
                    'harga_satuan_realisasi'     => $realisasi_map[$sub_sub->idpaket_belanja_detail_sub]['unit_prices'] ?? [],
                    'harga_satuan_rata'          => $realisasi_map[$sub_sub->idpaket_belanja_detail_sub]['unit_price_average'] ?? 0
                ], $tw_data);
            }

            $tw_data = $this->default_tw_data();

            if (empty($child_sub)) {
                $tw_data = $this->build_tw_data_for_subdetail(
                    $detail->idpaket_belanja_detail_sub,
                    $detail->jumlah,
                    $realisasi_map
                );

                $nominal_realisasi = $tw_data['realisasi_sampai_tw4'];
                $total_realisasi += $tw_data['realisasi_sampai_tw4'];
            }

            $volume_realisasi = $realisasi_map[$detail->idpaket_belanja_detail_sub]['volume_realisasi'] ?? 0;
            $sisa_volume = $detail->volume - $volume_realisasi;

			if ($detail->is_subkategori == 1) {
				$total_data++;

				if ($sisa_volume == 0) {
					$total_done++;
				}
			}

            $uang_realisasi = $realisasi_map[$detail->idpaket_belanja_detail_sub]['uang_realisasi'] ?? 0;
            $sisa_uang = $detail->jumlah - $uang_realisasi;

            $total_sisa_uang += $sisa_uang;

            $result_detail[] = array_merge([
                'idpaket_belanja_detail_sub' => $detail->idpaket_belanja_detail_sub,
                'idpaket_belanja_detail'     => $detail->idpaket_belanja_detail,
                'idkategori'                 => $detail->idkategori,
                'nama_kategori'              => $detail->nama_kategori,
                'idsub_kategori'             => $detail->idsub_kategori,
                'nama_subkategori'           => $detail->nama_sub_kategori,
                'kode_rekening'              => $detail->kode_rekening,
                'is_kategori'                => $detail->is_kategori,
                'is_subkategori'             => $detail->is_subkategori,
                'no_rekening_akunbelanja'    => $detail->no_rekening_akunbelanja,
                'volume'                     => $detail->volume,
                'nama_satuan'                => $detail->nama_satuan,
                'harga_satuan'               => $detail->harga_satuan,
                'jumlah'                     => $detail->jumlah,
                'volume_realisasi'           => $volume_realisasi,
                'sisa_volume'                => $sisa_volume,
                'sisa_uang'                  => $sisa_uang,
                'nominal_realisasi'			 => $nominal_realisasi,
                'harga_satuan_realisasi'     => $realisasi_map[$detail->idpaket_belanja_detail_sub]['unit_prices'] ?? [],
                'harga_satuan_rata'          => $realisasi_map[$detail->idpaket_belanja_detail_sub]['unit_price_average'] ?? 0,
                'arr_pd_detail_sub_sub'      => $arr_sub_sub
            ], $tw_data);
        }
		
        if ($total_jumlah > 0 && $total_sisa_uang > 0) {
            $total_persentase = ($total_sisa_uang / $total_jumlah) * 100;
        }

		// echo"<pre>"; print_r($result_detail);die;
		
        return [
            'detail'           => $result_detail,
            'total_jumlah'     => $total_jumlah,
            'total_sisa_uang'  => $total_sisa_uang,
            'total_realisasi'  => $total_realisasi,
            'total_persentase' => $total_persentase,
            'total_data'       => $total_data,
            'total_done'       => $total_done
        ];
    }



	/*
    |--------------------------------------------------------------------------
    | GENERATE TW
    |--------------------------------------------------------------------------
    */
    private function default_tw_data() {
        return array(
            'realisasi_sampai_tw1' => 0,
            'realisasi_sampai_tw2' => 0,
            'realisasi_sampai_tw3' => 0,
            'realisasi_sampai_tw4' => 0,

            'persen_realisasi_sampai_tw1' => 0,
            'persen_realisasi_sampai_tw2' => 0,
            'persen_realisasi_sampai_tw3' => 0,
            'persen_realisasi_sampai_tw4' => 0
        );
    }



	/*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    private function apply_status_date_filter($filter_bulan) {
        $range = $this->get_month_date_range($filter_bulan);

        $mapping = [
            [
                'status' => 'SUDAH DIBAYAR BENDAHARA',
                'field'  => 'npd.confirm_payment_date'
            ],
            [
                'status' => 'MENUNGGU PEMBAYARAN',
                'field'  => 'npd.npd_date_created'
            ],
            [
                'status' => 'INPUT NPD',
                'field'  => 'npd.npd_date_created'
            ],
            [
                'status' => 'DITOLAK VERIFIKATOR',
                'field'  => 'verification.confirm_verification_date'
            ],
            [
                'status' => 'SUDAH DIVERIFIKASI',
                'field'  => 'verification.confirm_verification_date'
            ],
            [
                'status' => 'MENUNGGU VERIFIKASI',
                'field'  => 'budget_realization.realization_date'
            ],
            [
                'status' => 'KONTRAK PENGADAAN',
                'field'  => 'contract.contract_date'
            ]
        ];

        $this->db->group_start();

        foreach ($mapping as $item) {
            $this->db->or_group_start()
                ->where('contract.contract_status', $item['status'])
                ->where($item['field'].' >=', $range['start'])
                ->where($item['field'].' <=', $range['end'])
            ->group_end();
        }

        $this->db->group_end();
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

        $npd_statuses = [
            'SUDAH DIBAYAR BENDAHARA',
            'MENUNGGU PEMBAYARAN',
            'INPUT NPD'
        ];

        $this->db
            ->where_in('purchase_plan_detail.purchase_plan_detail_status', $statuses)
            ->where('budget_realization.realization_status !=', 'DRAFT')
            ->group_start()
                ->group_start()
                    ->where_in('purchase_plan_detail.purchase_plan_detail_status', $npd_statuses)
                    ->where('npd.status', 1)
                    ->where('npd.npd_status !=', 'DRAFT')
                ->group_end()
                ->or_group_start()
                    ->where_not_in('purchase_plan_detail.purchase_plan_detail_status', $npd_statuses)
                ->group_end()
            ->group_end();
    }



	/*
    |--------------------------------------------------------------------------
    | QUERY MASTER
    |--------------------------------------------------------------------------
    */

    private function base_master_query($table, $where = [], $order_by = '', $select = '*') {
        $this->db->from($table);

        foreach ($where as $field => $value) {
			if (strlen($value) > 0) {
				$this->db->where($field, $value);
			}
        }

        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }

        $this->db->select($select);

        return $this->db->get();
    }

	public function query_urusan_pemerintah($tahun_anggaran) {
        return $this->base_master_query(
            'urusan_pemerintah',
            [
                'status'                 => 1,
                'is_active'              => 1,
                'tahun_anggaran_urusan'  => $tahun_anggaran
            ],
            'idurusan_pemerintah ASC',
            $this->master_select['urusan']
        );
    }

    public function query_bidang_urusan($idurusan_pemerintah) {
        return $this->base_master_query(
            'bidang_urusan',
            [
                'status'               => 1,
                'is_active'            => 1,
                'idurusan_pemerintah'  => $idurusan_pemerintah
            ],
            'idbidang_urusan ASC',
            $this->master_select['bidang_urusan']
        );
    }

    public function query_program($idbidang_urusan) {
        return $this->base_master_query(
            'program',
            [
                'status'            => 1,
                'is_active'         => 1,
                'idbidang_urusan'   => $idbidang_urusan
            ],
            'idprogram ASC',
            $this->master_select['program']
        );
    }

    public function query_kegiatan($idprogram) {
        return $this->base_master_query(
            'kegiatan',
            [
                'status'    => 1,
                'is_active' => 1,
                'idprogram' => $idprogram
            ],
            'idkegiatan ASC',
            $this->master_select['kegiatan']
        );
    }

    public function query_sub_kegiatan($idkegiatan) {
        return $this->base_master_query(
            'sub_kegiatan',
            [
                'status'     => 1,
                'is_active'  => 1,
                'idkegiatan' => $idkegiatan
            ],
            'idsub_kegiatan ASC',
            $this->master_select['sub_kegiatan']
        );
    }

    public function query_paket_belanja($idsub_kegiatan, $nama_paket_belanja = null) {
        return $this->base_master_query(
            'paket_belanja',
            [
                'status'                 => 1,
                'status_paket_belanja'   => 'OK',
                'idsub_kegiatan'         => $idsub_kegiatan,
                'nama_paket_belanja'     => $nama_paket_belanja,
                // 'nama_paket_belanja'     => "Fasilitasi Kunjungan Tamu" // testing
            ],
            'idpaket_belanja ASC',
            $this->master_select['paket_belanja']
        );
    }

    private function query_lazy_paket_belanja($the_data, $offset = 0, $batch_size = 20) {
        $tahun_anggaran = azarr($the_data, 'tahun_anggaran');
        $nama_paket_belanja = azarr($the_data, 'nama_paket_belanja');

        $this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
        $this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
        $this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
        $this->db->where('paket_belanja.status', 1);
        $this->db->where('paket_belanja.is_active', 1);
        $this->db->where('paket_belanja.status_paket_belanja', 'OK');
        $this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $tahun_anggaran);

        if (strlen($nama_paket_belanja) > 0) {
            $this->db->where('paket_belanja.nama_paket_belanja', $nama_paket_belanja);
        }

        $this->db->order_by('paket_belanja.idpaket_belanja', 'ASC');
        $this->db->limit($batch_size, $offset);
        $this->db->select('paket_belanja.idpaket_belanja,
            paket_belanja.nama_paket_belanja,
            paket_belanja.nilai_anggaran,
            urusan_pemerintah.idurusan_pemerintah,
            urusan_pemerintah.no_rekening_urusan,
            urusan_pemerintah.nama_urusan,
            urusan_pemerintah.tahun_anggaran_urusan,
            bidang_urusan.idbidang_urusan,
            bidang_urusan.no_rekening_bidang_urusan,
            bidang_urusan.nama_bidang_urusan,
            program.idprogram,
            program.no_rekening_program,
            program.nama_program,
            kegiatan.idkegiatan,
            kegiatan.no_rekening_kegiatan,
            kegiatan.nama_kegiatan,
            sub_kegiatan.idsub_kegiatan,
            sub_kegiatan.no_rekening_subkegiatan,
            sub_kegiatan.nama_subkegiatan');

        return $this->db->get('paket_belanja');
    }

    private function count_lazy_paket_belanja($the_data) {
        $tahun_anggaran = azarr($the_data, 'tahun_anggaran');
        $nama_paket_belanja = azarr($the_data, 'nama_paket_belanja');

        $this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
        $this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
        $this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
        $this->db->where('paket_belanja.status', 1);
        $this->db->where('paket_belanja.is_active', 1);
        $this->db->where('paket_belanja.status_paket_belanja', 'OK');
        $this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $tahun_anggaran);

        if (strlen($nama_paket_belanja) > 0) {
            $this->db->where('paket_belanja.nama_paket_belanja', $nama_paket_belanja);
        }

        $this->db->select('COUNT(*) as total', false);
        $row = $this->db->get('paket_belanja')->row();

        return (int) ($row->total ?? 0);
    }

    private function get_total_anggaran_for_filter($the_data) {
        $tahun_anggaran = azarr($the_data, 'tahun_anggaran');
        $nama_paket_belanja = azarr($the_data, 'nama_paket_belanja');

        $this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
        $this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
        $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
        $this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
        $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
        $this->db->where('paket_belanja.status', 1);
        $this->db->where('paket_belanja.is_active', 1);
        $this->db->where('paket_belanja.status_paket_belanja', 'OK');
        $this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $tahun_anggaran);

        if (strlen($nama_paket_belanja) > 0) {
            $this->db->where('paket_belanja.nama_paket_belanja', $nama_paket_belanja);
        }

        $this->db->select_sum('paket_belanja.nilai_anggaran');
        $row = $this->db->get('paket_belanja')->row();

        return (float) ($row->nilai_anggaran ?? 0);
    }

    public function query_akun_belanja($idpaket_belanja) {
        $this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
        $this->db->where('paket_belanja_detail.status', 1);
        $this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
        $this->db->order_by('paket_belanja_detail.idpaket_belanja_detail ASC');
        $this->db->select($this->master_select['akun_belanja']);
        $pbd = $this->db->get('paket_belanja_detail');
        // echo "<pre>"; print_r($this->db->last_query());die;

        return $pbd;
    }

    public function query_paket_belanja_detail($idpaket_belanja_detail, $idpaket_belanja_detail_sub = null, $is_sub_detail = false) {

        $query_akun_belanja = '';

        if (!$is_sub_detail) {
            $query_akun_belanja = ', akun_belanja.no_rekening_akunbelanja';
            $this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail', $idpaket_belanja_detail);
        }

        if (!empty($idpaket_belanja_detail_sub)) {
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

        $this->db->select('
            paket_belanja_detail_sub.idpaket_belanja_detail_sub,
            paket_belanja_detail_sub.idpaket_belanja_detail,
            paket_belanja_detail_sub.idkategori,
            kategori.nama_kategori,
            sub_kategori.idsub_kategori,
            sub_kategori.nama_sub_kategori,
            kode_rekening.kode_rekening,
            paket_belanja_detail_sub.is_kategori,
            paket_belanja_detail_sub.is_subkategori,
            paket_belanja_detail_sub.volume,
            satuan.nama_satuan,
            paket_belanja_detail_sub.harga_satuan,
            paket_belanja_detail_sub.jumlah,
            paket_belanja_detail_sub.is_kategori,
            paket_belanja_detail_sub.is_subkategori,
			paket_belanja_detail_sub.rak_volume_januari, 
			paket_belanja_detail_sub.rak_volume_februari, 
			paket_belanja_detail_sub.rak_volume_maret, 
			paket_belanja_detail_sub.rak_volume_april, 
			paket_belanja_detail_sub.rak_volume_mei, 
			paket_belanja_detail_sub.rak_volume_juni, 
			paket_belanja_detail_sub.rak_volume_juli, 
			paket_belanja_detail_sub.rak_volume_agustus, 
			paket_belanja_detail_sub.rak_volume_september, 
			paket_belanja_detail_sub.rak_volume_oktober, 
			paket_belanja_detail_sub.rak_volume_november, 
			paket_belanja_detail_sub.rak_volume_desember, 
			paket_belanja_detail_sub.rak_jumlah_januari, 
			paket_belanja_detail_sub.rak_jumlah_februari,
			paket_belanja_detail_sub.rak_jumlah_maret, 
			paket_belanja_detail_sub.rak_jumlah_april, 
			paket_belanja_detail_sub.rak_jumlah_mei, 
			paket_belanja_detail_sub.rak_jumlah_juni, 
			paket_belanja_detail_sub.rak_jumlah_juli, 
			paket_belanja_detail_sub.rak_jumlah_agustus, 
			paket_belanja_detail_sub.rak_jumlah_september,
			paket_belanja_detail_sub.rak_jumlah_oktober, 
			paket_belanja_detail_sub.rak_jumlah_november, 
			paket_belanja_detail_sub.rak_jumlah_desember
            '.$query_akun_belanja);

        $pbds = $this->db->get('paket_belanja_detail_sub');
        // echo "<pre>"; print_r($this->db->last_query());die;

        return $pbds;
    }

    public function query_paket_belanja_detail_sub($idpaket_belanja_detail_sub, $join_kategori = false) {

        $query_category = '';

        if ($join_kategori) {
            $query_category = ',
                "" as nama_kategori,
                "" as no_rekening_akunbelanja
            ';
        }

        $this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
        $this->db->where('paket_belanja_detail_sub.status', 1);
        $this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
        $this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
        $this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');

        $this->db->select('
            paket_belanja_detail_sub.idpaket_belanja_detail_sub,
            paket_belanja_detail_sub.is_idpaket_belanja_detail_sub,
            paket_belanja_detail_sub.idpaket_belanja_detail,
            paket_belanja_detail_sub.idpaket_belanja,
            paket_belanja_detail_sub.idkategori,
            sub_kategori.idsub_kategori,
            sub_kategori.nama_sub_kategori,
            kode_rekening.kode_rekening,
            paket_belanja_detail_sub.is_kategori,
            paket_belanja_detail_sub.is_subkategori,
            paket_belanja_detail_sub.volume,
            satuan.nama_satuan,
            paket_belanja_detail_sub.harga_satuan,
            paket_belanja_detail_sub.jumlah,
			paket_belanja_detail_sub.rak_volume_januari,
			paket_belanja_detail_sub.rak_volume_februari,
			paket_belanja_detail_sub.rak_volume_maret,
			paket_belanja_detail_sub.rak_volume_april,
			paket_belanja_detail_sub.rak_volume_mei,
			paket_belanja_detail_sub.rak_volume_juni,
			paket_belanja_detail_sub.rak_volume_juli,
			paket_belanja_detail_sub.rak_volume_agustus,
			paket_belanja_detail_sub.rak_volume_september,
			paket_belanja_detail_sub.rak_volume_oktober,
			paket_belanja_detail_sub.rak_volume_november,
			paket_belanja_detail_sub.rak_volume_desember,
			paket_belanja_detail_sub.rak_jumlah_januari,
			paket_belanja_detail_sub.rak_jumlah_februari,
			paket_belanja_detail_sub.rak_jumlah_maret,
			paket_belanja_detail_sub.rak_jumlah_april,
			paket_belanja_detail_sub.rak_jumlah_mei,
			paket_belanja_detail_sub.rak_jumlah_juni,
			paket_belanja_detail_sub.rak_jumlah_juli,
			paket_belanja_detail_sub.rak_jumlah_agustus,
			paket_belanja_detail_sub.rak_jumlah_september,
			paket_belanja_detail_sub.rak_jumlah_oktober,
			paket_belanja_detail_sub.rak_jumlah_november,
			paket_belanja_detail_sub.rak_jumlah_desember
            '.$query_category);
            
        $pbds = $this->db->get('paket_belanja_detail_sub');
        // echo "<pre>"; print_r($this->db->last_query());die;

        return $pbds;
    }

    public function query_paket_belanja_detail_sub_by_detail_ids(array $detail_ids) {
        if (empty($detail_ids)) {
            return $this->db->get_where('paket_belanja_detail_sub', ['idpaket_belanja_detail_sub' => 0]);
        }

        $this->db->where_in('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub', $detail_ids);
        $this->db->where('paket_belanja_detail_sub.status', 1);
        $this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
        $this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left' );
        $this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan');

        $this->db->select('
            paket_belanja_detail_sub.idpaket_belanja_detail_sub,
            paket_belanja_detail_sub.idpaket_belanja_detail,
            paket_belanja_detail_sub.is_idpaket_belanja_detail_sub,
            paket_belanja_detail_sub.idkategori,
            sub_kategori.idsub_kategori,
            sub_kategori.nama_sub_kategori,
            kode_rekening.kode_rekening,
            paket_belanja_detail_sub.is_kategori,
            paket_belanja_detail_sub.is_subkategori,
            paket_belanja_detail_sub.volume,
            satuan.nama_satuan,
            paket_belanja_detail_sub.harga_satuan,
            paket_belanja_detail_sub.jumlah,
            paket_belanja_detail_sub.is_kategori,
            paket_belanja_detail_sub.is_subkategori
        ');

        $pbds = $this->db->get('paket_belanja_detail_sub');
        // echo "<pre>"; print_r($this->db->last_query());die;

        return $pbds;
    }

    private function build_realisasi_map($idpaket_belanja, array $subdetail_ids, array $idsub_categories, $tahun_anggaran) {
        if (empty($subdetail_ids) || empty($idsub_categories)) {
            return [];
        }

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

        $year_start = $tahun_anggaran . '-01-01';
        $tw1_end = $tahun_anggaran . '-03-31';
        $tw2_end = $tahun_anggaran . '-06-30';
        $tw3_end = $tahun_anggaran . '-09-30';
        $tw4_end = $tahun_anggaran . '-12-31';

        $this->db->select("purchase_plan_detail.idpaket_belanja_detail_sub as id,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw1_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw1,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw2_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw2,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw3_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw3,
                        SUM(
                            CASE 
                                WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw4_end}' 
                                    AND budget_realization_detail.unit_price IS NOT NULL
                                    THEN budget_realization_detail.total_realization_detail 
                                ELSE 0 
                            END
                        ) as tw4,
                        SUM(
                            CASE
                                WHEN purchase_plan.purchase_plan_status != 'DRAFT'
                                    AND DATE_FORMAT(purchase_plan.purchase_plan_date, '%Y') = '{$tahun_anggaran}'
                                    AND budget_realization_detail.idsub_kategori = paket_belanja_detail_sub.idsub_kategori
                                THEN budget_realization_detail.volume
                                ELSE 0
                            END
                        ) as volume_realisasi,
                        SUM(
                            CASE
                                WHEN purchase_plan.purchase_plan_status != 'DRAFT'
                                THEN budget_realization_detail.total_realization_detail
                                ELSE 0
                            END
                        ) as uang_realisasi",
                    false);

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization_detail.status', 1);
        
        $this->db->where('contract.contract_status !=', "DRAFT");
        $this->db->where('budget_realization.realization_status !=', "DRAFT");
        // $this->db->where('verification.verification_status !=', "DRAFT");
        // $this->db->where('npd.npd_status !=', "DRAFT");

        $this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
        $this->db->where_in('purchase_plan_detail.idpaket_belanja_detail_sub', $subdetail_ids);
        $this->db->where_in('budget_realization_detail.idsub_kategori', $idsub_categories);
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

        $result = $plan->result();

        $price_map = [];
        $this->db->reset_query();

        $this->db->select('DISTINCT purchase_plan_detail.idpaket_belanja_detail_sub as id, budget_realization_detail.unit_price', false);
        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization_detail.status', 1);
        $this->db->where('budget_realization_detail.unit_price IS NOT NULL', null, false);
        $this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
        $this->db->where_in('purchase_plan_detail.idpaket_belanja_detail_sub', $subdetail_ids);
        $this->db->where_in('budget_realization_detail.idsub_kategori', $idsub_categories);
        $this->db->where('purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');

        $this->apply_status_validation_filter();

        $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
        $this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
        $this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');
        $this->db->join('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
        $this->db->join('npd_detail', 'npd_detail.idverification = verification.idverification', 'left');
        $this->db->join('npd', 'npd.idnpd = npd_detail.idnpd', 'left');
        $this->db->order_by('budget_realization_detail.unit_price', 'ASC');

        $price_rows = $this->db->get('purchase_plan')->result();

        foreach ($price_rows as $price_row) {
            if ($price_row->unit_price === null) {
                continue;
            }

            $price_map[$price_row->id][] = (float) $price_row->unit_price;
        }

        foreach ($price_map as $id => $prices) {
            $prices = array_values(array_unique($prices, SORT_NUMERIC));
            sort($prices, SORT_NUMERIC);
            $price_map[$id] = $prices;
        }

        $map = [];
        foreach ($result as $row) {
            $prices = $price_map[$row->id] ?? [];
            $average_price = 0;
            if (!empty($prices)) {
                $average_price = array_sum($prices) / count($prices);
            }

            $map[$row->id] = [
                'realisasi_sampai_tw1' => (float) $row->tw1,
                'realisasi_sampai_tw2' => (float) $row->tw2,
                'realisasi_sampai_tw3' => (float) $row->tw3,
                'realisasi_sampai_tw4' => (float) $row->tw4,
                'unit_prices'          => $prices,
                'unit_price_average'   => (float) $average_price,
                'volume_realisasi'     => (float) ($row->volume_realisasi ?? 0),
                'uang_realisasi'       => (float) ($row->uang_realisasi ?? 0)
            ];
        }
        // echo "<pre>"; print_r($map);die;

        return $map;
    }

    private function build_tw_data_for_subdetail($subdetail_id, $jumlah, array $realisasi_map) {
        $realisasi = $realisasi_map[$subdetail_id] ?? null;

        if (empty($realisasi)) {
            return $this->default_tw_data();
        }

        $jumlah = ($jumlah > 0) ? (float) $jumlah : 0;

        return [
            'realisasi_sampai_tw1' => $realisasi['realisasi_sampai_tw1'],
            'realisasi_sampai_tw2' => $realisasi['realisasi_sampai_tw2'],
            'realisasi_sampai_tw3' => $realisasi['realisasi_sampai_tw3'],
            'realisasi_sampai_tw4' => $realisasi['realisasi_sampai_tw4'],
            'persen_realisasi_sampai_tw1' => $jumlah > 0 ? ($realisasi['realisasi_sampai_tw1'] / $jumlah) * 100 : 0,
            'persen_realisasi_sampai_tw2' => $jumlah > 0 ? ($realisasi['realisasi_sampai_tw2'] / $jumlah) * 100 : 0,
            'persen_realisasi_sampai_tw3' => $jumlah > 0 ? ($realisasi['realisasi_sampai_tw3'] / $jumlah) * 100 : 0,
            'persen_realisasi_sampai_tw4' => $jumlah > 0 ? ($realisasi['realisasi_sampai_tw4'] / $jumlah) * 100 : 0,
        ];
    }

    private function build_realisasi_query(array $params, $select) {
        $this->db->reset_query();

        $tahun_anggaran = $params['tahun_anggaran'];
        $start_bulan = $params['start_bulan'];
        $end_bulan = $params['end_bulan'];
        $idpaket_belanja = $params['idpaket_belanja'];
        $idpaket_belanja_detail_sub = $params['idpaket_belanja_detail_sub'];
        $idsub_kategori = $params['idsub_kategori'];

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('contract.contract_status != "DRAFT" ');

        $this->apply_status_date_range_filter(
            $tahun_anggaran . '-' . $start_bulan,
            $tahun_anggaran . '-' . $end_bulan
        );

        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub = "'.$idpaket_belanja_detail_sub.'" ');
        $this->db->where('purchase_plan_detail.idpaket_belanja = "'.$idpaket_belanja.'" ');
        $this->db->where('budget_realization_detail.idsub_kategori = "'.$idsub_kategori.'" ');
        $this->db->where('purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
        $this->db->where('budget_realization_detail.status', 1);
        $this->db->where('budget_realization.status', 1);

        $this->apply_status_validation_filter();

        $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
        $this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
        $this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');
        $this->db->join('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
        $this->db->join('npd_detail', 'npd_detail.idverification = verification.idverification', 'left');
        $this->db->join('npd', 'npd.idnpd = npd_detail.idnpd', 'left');

        $this->db->order_by(" 
            CASE purchase_plan_detail.purchase_plan_detail_status
                WHEN 'PROSES PENGADAAN' THEN 1
                WHEN 'KONTRAK PENGADAAN' THEN 2
                WHEN 'MENUNGGU VERIFIKASI' THEN 3
                WHEN 'SUDAH DIVERIFIKASI' THEN 4
                WHEN 'DITOLAK VERIFIKATOR' THEN 5
                WHEN 'INPUT NPD' THEN 6
                WHEN 'MENUNGGU PEMBAYARAN' THEN 7
                WHEN 'SUDAH DIBAYAR BENDAHARA' THEN 8
                ELSE 99
            END
        ", "", FALSE);
        $this->db->select($select);
    }

    private function get_monthly_realisasi_summary(array $params) {
        $this->build_realisasi_query(
            $params,
            'DATE_FORMAT(MAX(purchase_plan.purchase_plan_date), "%d-%m-%Y") as purchase_plan_date,
            MAX(budget_realization_detail.provider) as provider, sum(budget_realization_detail.volume) as volume, sum(budget_realization_detail.male) as male, sum(budget_realization_detail.female) as female, sum(budget_realization_detail.unit_price) as unit_price, sum(budget_realization_detail.ppn) as ppn, sum(budget_realization_detail.pph) as pph, sum(budget_realization_detail.total_realization_detail) as total'
        );

        return $this->db->get('purchase_plan');
    }

    private function get_monthly_realisasi_summary_batch(array $params, array $months) {
        $this->db->reset_query();

        $tahun_anggaran = $params['tahun_anggaran'];
        $start_bulan = $params['start_bulan'];
        $end_bulan = $params['end_bulan'];
        $idpaket_belanja = $params['idpaket_belanja'];
        $idpaket_belanja_detail_sub = $params['idpaket_belanja_detail_sub'];
        $idsub_kategori = $params['idsub_kategori'];

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('contract.contract_status != "DRAFT" ');

        $this->apply_status_date_range_filter(
            $tahun_anggaran . '-' . $start_bulan,
            $tahun_anggaran . '-' . $end_bulan
        );

        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub = "'.$idpaket_belanja_detail_sub.'" ');
        $this->db->where('purchase_plan_detail.idpaket_belanja = "'.$idpaket_belanja.'" ');
        $this->db->where('budget_realization_detail.idsub_kategori = "'.$idsub_kategori.'" ');
        $this->db->where('purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
        $this->db->where('budget_realization_detail.status', 1);
        $this->db->where('budget_realization.status', 1);

        $this->apply_status_validation_filter();

        $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
        $this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
        $this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');
        $this->db->join('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
        $this->db->join('npd_detail', 'npd_detail.idverification = verification.idverification', 'left');
        $this->db->join('npd', 'npd.idnpd = npd_detail.idnpd', 'left');

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

        $select_parts = [];
        $month_numbers = array_values($months);

        foreach ($month_numbers as $index => $month_number) {
            $month_value = sprintf('%02d', (int) $month_number);
            $month_range = $tahun_anggaran . '-' . $month_value;
            $alias = 'month_' . ($index + 1);

            $select_parts[] = "MAX(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN purchase_plan.purchase_plan_date ELSE NULL END) as purchase_plan_date_{$alias}";
            $select_parts[] = "MAX(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.provider ELSE NULL END) as provider_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.volume ELSE 0 END) as volume_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.male ELSE 0 END) as male_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.female ELSE 0 END) as female_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.unit_price ELSE 0 END) as unit_price_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.ppn ELSE 0 END) as ppn_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.pph ELSE 0 END) as pph_{$alias}";
            $select_parts[] = "SUM(CASE WHEN DATE_FORMAT({$status_date}, '%Y-%m') = '{$month_range}' AND budget_realization_detail.unit_price IS NOT NULL THEN budget_realization_detail.total_realization_detail ELSE 0 END) as total_{$alias}";
        }

        $this->db->select(implode(', ', $select_parts), false);
        $row = $this->db->get('purchase_plan')->row();

        $result = [];
        foreach ($month_numbers as $index => $month_number) {
            $alias = 'month_' . ($index + 1);
            $result[$month_number] = [
                'purchase_plan_date' => $row->{'purchase_plan_date_' . $alias} ?? null,
                'provider' => $row->{'provider_' . $alias} ?? null,
                'volume' => (float) ($row->{'volume_' . $alias} ?? 0),
                'male' => (float) ($row->{'male_' . $alias} ?? 0),
                'female' => (float) ($row->{'female_' . $alias} ?? 0),
                'unit_price' => (float) ($row->{'unit_price_' . $alias} ?? 0),
                'ppn' => (float) ($row->{'ppn_' . $alias} ?? 0),
                'pph' => (float) ($row->{'pph_' . $alias} ?? 0),
                'total' => (float) ($row->{'total_' . $alias} ?? 0),
            ];
        }

        return $result;
    }

    private function get_monthly_realisasi_detail_rows(array $params) {
        $this->build_realisasi_query(
            $params,
            'budget_realization.realization_code, purchase_plan_detail.purchase_plan_detail_status, budget_realization_detail.total_realization_detail'
        );

        return $this->db->get('purchase_plan');
    }

    private function resolve_triwulan_context($tw) {
        $map = [
            1 => [
                'nama_bulan_ke_1' => 'Januari',
                'nama_bulan_ke_2' => 'Februari',
                'nama_bulan_ke_3' => 'Maret',
                'start_bulan' => 1,
            ],
            2 => [
                'nama_bulan_ke_1' => 'April',
                'nama_bulan_ke_2' => 'Mei',
                'nama_bulan_ke_3' => 'Juni',
                'start_bulan' => 4,
            ],
            3 => [
                'nama_bulan_ke_1' => 'Juli',
                'nama_bulan_ke_2' => 'Agustus',
                'nama_bulan_ke_3' => 'September',
                'start_bulan' => 7,
            ],
            4 => [
                'nama_bulan_ke_1' => 'Oktober',
                'nama_bulan_ke_2' => 'November',
                'nama_bulan_ke_3' => 'Desember',
                'start_bulan' => 10,
            ],
        ];

        return $map[$tw] ?? [
            'nama_bulan_ke_1' => '',
            'nama_bulan_ke_2' => '',
            'nama_bulan_ke_3' => '',
            'start_bulan' => 1,
        ];
    }

	function get_detail_data() {
		$idpaket_belanja_detail = $this->input->post("idpaket_belanja_detail");
		$tw = $this->input->post("tw");
		$tahun_anggaran = $this->input->post("tahun_anggaran");

		$triwulan_context = $this->resolve_triwulan_context($tw);
		$nama_bulan_ke_1 = $triwulan_context['nama_bulan_ke_1'];
		$nama_bulan_ke_2 = $triwulan_context['nama_bulan_ke_2'];
		$nama_bulan_ke_3 = $triwulan_context['nama_bulan_ke_3'];
		$mulai_bulan = $triwulan_context['start_bulan'];

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
		$tw_cache = array();
		foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {

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
				$tw_cache_key = $ds_value->idpaket_belanja_detail_sub . '|' . $ds_value->idsub_kategori;
				if (!isset($tw_cache[$tw_cache_key])) {
					$arr_tw_sebelumnya = array(
						'tw_sebelumnya' => $tw - 1,
						'tahun_anggaran' => $tahun_anggaran,
						'idpaket_belanja' => $idpaket_belanja,
						'idsub_kategori' => $ds_value->idsub_kategori,
						'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
					);

					$tw_cache[$tw_cache_key] = $this->get_tw_sebelumnya($arr_tw_sebelumnya);
				}

				$get_tw_sebelumnya = $tw_cache[$tw_cache_key];
				$realisasi_lk_sebelumnya = $get_tw_sebelumnya['realisasi_lk_sebelumnya'];
				$realisasi_pr_sebelumnya = $get_tw_sebelumnya['realisasi_pr_sebelumnya'];
				$realisasi_vol_sebelumnya = $get_tw_sebelumnya['realisasi_vol_sebelumnya'];
				$realisasi_rp_sebelumnya = $get_tw_sebelumnya['realisasi_rp_sebelumnya'];
			}

			$the_filter = array(
				'tahun_anggaran' => $tahun_anggaran,
				'start_bulan' => sprintf('%02d', $mulai_bulan),
				'end_bulan' => sprintf('%02d', $mulai_bulan + 2),
				'idpaket_belanja' => $idpaket_belanja,
				'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
				'idsub_kategori' => $ds_value->idsub_kategori,
			);

			$monthly_summary = $this->get_monthly_realisasi_summary_batch($the_filter, range($mulai_bulan, $mulai_bulan + 2));
			$month_summary_1 = $monthly_summary[$mulai_bulan] ?? array();
			$month_summary_2 = $monthly_summary[$mulai_bulan + 1] ?? array();
			$month_summary_3 = $monthly_summary[$mulai_bulan + 2] ?? array();

			$tanggal_bulan_ke_1 = $month_summary_1['purchase_plan_date'] ?? null;
			$penyedia_bulan_ke_1 = $month_summary_1['provider'] ?? null;
			$volume_bulan_ke_1 = $month_summary_1['volume'] ?? 0;
			$laki_bulan_ke_1 = $month_summary_1['male'] ?? 0;
			$perempuan_bulan_ke_1 = $month_summary_1['female'] ?? 0;
			$harga_satuan_bulan_ke_1 = $month_summary_1['unit_price'] ?? 0;
			$ppn_bulan_ke_1 = $month_summary_1['ppn'] ?? 0;
			$pph_bulan_ke_1 = $month_summary_1['pph'] ?? 0;
			$total_bulan_ke_1 = $month_summary_1['total'] ?? 0;

			$tanggal_bulan_ke_2 = $month_summary_2['purchase_plan_date'] ?? null;
			$penyedia_bulan_ke_2 = $month_summary_2['provider'] ?? null;
			$volume_bulan_ke_2 = $month_summary_2['volume'] ?? 0;
			$laki_bulan_ke_2 = $month_summary_2['male'] ?? 0;
			$perempuan_bulan_ke_2 = $month_summary_2['female'] ?? 0;
			$harga_satuan_bulan_ke_2 = $month_summary_2['unit_price'] ?? 0;
			$ppn_bulan_ke_2 = $month_summary_2['ppn'] ?? 0;
			$pph_bulan_ke_2 = $month_summary_2['pph'] ?? 0;
			$total_bulan_ke_2 = $month_summary_2['total'] ?? 0;

			$tanggal_bulan_ke_3 = $month_summary_3['purchase_plan_date'] ?? null;
			$penyedia_bulan_ke_3 = $month_summary_3['provider'] ?? null;
			$volume_bulan_ke_3 = $month_summary_3['volume'] ?? 0;
			$laki_bulan_ke_3 = $month_summary_3['male'] ?? 0;
			$perempuan_bulan_ke_3 = $month_summary_3['female'] ?? 0;
			$harga_satuan_bulan_ke_3 = $month_summary_3['unit_price'] ?? 0;
			$ppn_bulan_ke_3 = $month_summary_3['ppn'] ?? 0;
			$pph_bulan_ke_3 = $month_summary_3['pph'] ?? 0;
			$total_bulan_ke_3 = $month_summary_3['total'] ?? 0;

			$grand_bulan_ke_1 += $total_bulan_ke_1;
			$grand_bulan_ke_2 += $total_bulan_ke_2;
			$grand_bulan_ke_3 += $total_bulan_ke_3;
			$realisasi_lk += $laki_bulan_ke_1 + $laki_bulan_ke_2 + $laki_bulan_ke_3;
			$realisasi_pr += $perempuan_bulan_ke_1 + $perempuan_bulan_ke_2 + $perempuan_bulan_ke_3;
			$realisasi_vol += $volume_bulan_ke_1 + $volume_bulan_ke_2 + $volume_bulan_ke_3;
			$realisasi_rp += $total_bulan_ke_1 + $total_bulan_ke_2 + $total_bulan_ke_3;

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

			if (strlen($ds_value->idkategori) == 0 && $jumlah_anggaran != 0) {
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
				'is_sub'						=> 1,

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
					$tw_cache_key = $dss_value->idpaket_belanja_detail_sub . '|' . $dss_value->idsub_kategori;
					if (!isset($tw_cache[$tw_cache_key])) {
						$arr_tw_sebelumnya = array(
							'tw_sebelumnya' => $tw - 1,
							'tahun_anggaran' => $tahun_anggaran,
							'idpaket_belanja' => $idpaket_belanja,
							'idsub_kategori' => $dss_value->idsub_kategori,
							'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
						);

						$tw_cache[$tw_cache_key] = $this->get_tw_sebelumnya($arr_tw_sebelumnya);
					}

					$get_tw_sebelumnya = $tw_cache[$tw_cache_key];
					$realisasi_lk_sebelumnya = $get_tw_sebelumnya['realisasi_lk_sebelumnya'];
					$realisasi_pr_sebelumnya = $get_tw_sebelumnya['realisasi_pr_sebelumnya'];
					$realisasi_vol_sebelumnya = $get_tw_sebelumnya['realisasi_vol_sebelumnya'];
					$realisasi_rp_sebelumnya = $get_tw_sebelumnya['realisasi_rp_sebelumnya'];
				}

				$the_filter = array(
					'tahun_anggaran' => $tahun_anggaran,
					'start_bulan' => sprintf('%02d', $mulai_bulan),
					'end_bulan' => sprintf('%02d', $mulai_bulan + 2),
					'idpaket_belanja' => $idpaket_belanja,
					'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
					'idsub_kategori' => $dss_value->idsub_kategori,
				);

				$monthly_summary = $this->get_monthly_realisasi_summary_batch($the_filter, range($mulai_bulan, $mulai_bulan + 2));
				$month_summary_1 = $monthly_summary[$mulai_bulan] ?? array();
				$month_summary_2 = $monthly_summary[$mulai_bulan + 1] ?? array();
				$month_summary_3 = $monthly_summary[$mulai_bulan + 2] ?? array();

				$tanggal_bulan_ke_1 = $month_summary_1['purchase_plan_date'] ?? null;
				$penyedia_bulan_ke_1 = $month_summary_1['provider'] ?? null;
				$volume_bulan_ke_1 = $month_summary_1['volume'] ?? 0;
				$laki_bulan_ke_1 = $month_summary_1['male'] ?? 0;
				$perempuan_bulan_ke_1 = $month_summary_1['female'] ?? 0;
				$harga_satuan_bulan_ke_1 = $month_summary_1['unit_price'] ?? 0;
				$ppn_bulan_ke_1 = $month_summary_1['ppn'] ?? 0;
				$pph_bulan_ke_1 = $month_summary_1['pph'] ?? 0;
				$total_bulan_ke_1 = $month_summary_1['total'] ?? 0;

				$tanggal_bulan_ke_2 = $month_summary_2['purchase_plan_date'] ?? null;
				$penyedia_bulan_ke_2 = $month_summary_2['provider'] ?? null;
				$volume_bulan_ke_2 = $month_summary_2['volume'] ?? 0;
				$laki_bulan_ke_2 = $month_summary_2['male'] ?? 0;
				$perempuan_bulan_ke_2 = $month_summary_2['female'] ?? 0;
				$harga_satuan_bulan_ke_2 = $month_summary_2['unit_price'] ?? 0;
				$ppn_bulan_ke_2 = $month_summary_2['ppn'] ?? 0;
				$pph_bulan_ke_2 = $month_summary_2['pph'] ?? 0;
				$total_bulan_ke_2 = $month_summary_2['total'] ?? 0;

				$tanggal_bulan_ke_3 = $month_summary_3['purchase_plan_date'] ?? null;
				$penyedia_bulan_ke_3 = $month_summary_3['provider'] ?? null;
				$volume_bulan_ke_3 = $month_summary_3['volume'] ?? 0;
				$laki_bulan_ke_3 = $month_summary_3['male'] ?? 0;
				$perempuan_bulan_ke_3 = $month_summary_3['female'] ?? 0;
				$harga_satuan_bulan_ke_3 = $month_summary_3['unit_price'] ?? 0;
				$ppn_bulan_ke_3 = $month_summary_3['ppn'] ?? 0;
				$pph_bulan_ke_3 = $month_summary_3['pph'] ?? 0;
				$total_bulan_ke_3 = $month_summary_3['total'] ?? 0;

				$grand_bulan_ke_1 += $total_bulan_ke_1;
				$grand_bulan_ke_2 += $total_bulan_ke_2;
				$grand_bulan_ke_3 += $total_bulan_ke_3;
				$realisasi_lk += $laki_bulan_ke_1 + $laki_bulan_ke_2 + $laki_bulan_ke_3;
				$realisasi_pr += $perempuan_bulan_ke_1 + $perempuan_bulan_ke_2 + $perempuan_bulan_ke_3;
				$realisasi_vol += $volume_bulan_ke_1 + $volume_bulan_ke_2 + $volume_bulan_ke_3;
				$realisasi_rp += $total_bulan_ke_1 + $total_bulan_ke_2 + $total_bulan_ke_3;

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

				if (strlen($dss_value->idkategori) == 0 && $realisasi_rp_sampai != 0) {
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

				$is_sub = 1;
				if (strlen($dss_value->is_idpaket_belanja_detail_sub) > 0) {
					$is_sub = 2;
				}

				$arr_detail[] = array(
					'idkategori' 					=> '',
					'nama_kategori' 				=> '',
					'idsub_kategori'	 			=> $dss_value->idsub_kategori,
					'nama_subkategori' 				=> $dss_value->nama_sub_kategori,
					'is_sub'						=> $is_sub,

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
		$idpaket_belanja_detail_sub = $the_data['idpaket_belanja_detail_sub'];

		$realisasi_lk_sebelumnya = 0;
		$realisasi_pr_sebelumnya = 0;
		$realisasi_vol_sebelumnya = 0;
		$realisasi_rp_sebelumnya = 0;

		$mulai_bulan = 1;
		$sampai_bulan = 1;
		if ($tw_sebelumnya == 1) {
			$sampai_bulan = 3;
		}
		else if ($tw_sebelumnya == 2) {
			$sampai_bulan = 6;
		}
		else if ($tw_sebelumnya == 3) {
			$sampai_bulan = 9;
		}

		$p_plan_d = $this->get_monthly_realisasi_summary([
			'tahun_anggaran' => $tahun_anggaran,
			'start_bulan' => sprintf('%02d', $mulai_bulan),
			'end_bulan' => sprintf('%02d', $sampai_bulan),
			'idpaket_belanja' => $idpaket_belanja,
			'idpaket_belanja_detail_sub' => $idpaket_belanja_detail_sub,
			'idsub_kategori' => $idsub_kategori,
		]);

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

	private function collect_history_month_data(array $params) {
        $this->db->reset_query();

        $tahun_anggaran = $params['tahun_anggaran'];
        $idpaket_belanja = $params['idpaket_belanja'];
        $idpaket_belanja_detail_sub = $params['idpaket_belanja_detail_sub'];
        $idsub_kategori = $params['idsub_kategori'];

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('contract.contract_status != "DRAFT" ');

        $this->apply_status_date_range_filter(
            $tahun_anggaran . '-01',
            $tahun_anggaran . '-12'
        );

        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub = "'.$idpaket_belanja_detail_sub.'" ');
        $this->db->where('purchase_plan_detail.idpaket_belanja = "'.$idpaket_belanja.'" ');
        $this->db->where('budget_realization_detail.idsub_kategori = "'.$idsub_kategori.'" ');
        $this->db->where('purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
        $this->db->where('budget_realization_detail.status', 1);
        $this->db->where('budget_realization.status', 1);

        $this->apply_status_validation_filter();

        $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
        $this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
        $this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');
        $this->db->join('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
        $this->db->join('npd_detail', 'npd_detail.idverification = verification.idverification', 'left');
        $this->db->join('npd', 'npd.idnpd = npd_detail.idnpd', 'left');

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

        $this->db->select("MONTH({$status_date}) as month_number, purchase_plan_detail.purchase_plan_detail_status, budget_realization_detail.total_realization_detail", false);
        $this->db->order_by(" 
            CASE purchase_plan_detail.purchase_plan_detail_status
                WHEN 'PROSES PENGADAAN' THEN 1
                WHEN 'KONTRAK PENGADAAN' THEN 2
                WHEN 'MENUNGGU VERIFIKASI' THEN 3
                WHEN 'SUDAH DIVERIFIKASI' THEN 4
                WHEN 'DITOLAK VERIFIKATOR' THEN 5
                WHEN 'INPUT NPD' THEN 6
                WHEN 'MENUNGGU PEMBAYARAN' THEN 7
                WHEN 'SUDAH DIBAYAR BENDAHARA' THEN 8
                ELSE 99
            END
        ", "", FALSE);

        $rows = $this->db->get('purchase_plan')->result();

        $month_names = array(
            1 => 'januari',
            2 => 'februari',
            3 => 'maret',
            4 => 'april',
            5 => 'mei',
            6 => 'juni',
            7 => 'juli',
            8 => 'agustus',
            9 => 'september',
            10 => 'oktober',
            11 => 'november',
            12 => 'desember',
        );

        $buckets = [];
        foreach ($month_names as $month_name) {
            $buckets[$month_name] = array(
                'rows' => array(),
                'realisasi' => array(),
                'total' => 0,
            );
        }

        foreach ($rows as $row) {
            $month_number = (int) ($row->month_number ?? 0);
            $month_name = $month_names[$month_number] ?? null;

            if ($month_name === null) {
                continue;
            }

            $buckets[$month_name]['rows'][] = label_status($row->purchase_plan_detail_status);
            $buckets[$month_name]['realisasi'][] = (float) $row->total_realization_detail;
            $buckets[$month_name]['total'] += (float) $row->total_realization_detail;
        }

        return $buckets;
    }

	function get_history_rak() {
		$idpaket_belanja_detail = $this->input->post("idpaket_belanja_detail");
		$tahun_anggaran = $this->input->post("tahun_anggaran");

		$rak_januari 			= 0;
		$realisasi_januari 		= array();
		$total_realisasi_januari = 0;
		$januari 				= '';
		$rak_februari			= 0;
		$realisasi_februari		= array();
		$total_realisasi_februari = 0;
		$februari 				= '';
		$rak_maret 				= 0;
		$realisasi_maret 		= array();
		$total_realisasi_maret = 0;
		$maret 					= '';
		$rak_april 				= 0;
		$realisasi_april 		= array();
		$total_realisasi_april = 0;
		$april 					= '';
		$rak_mei 				= 0;
		$realisasi_mei 			= array();
		$total_realisasi_mei 	= 0;
		$mei 					= '';
		$rak_juni 				= 0;
		$realisasi_juni 		= array();
		$total_realisasi_juni 	= 0;
		$juni 					= '';
		$rak_juli 				= 0;
		$realisasi_juli 		= array();
		$total_realisasi_juli 	= 0;
		$juli 					= '';
		$rak_agustus 			= 0;
		$realisasi_agustus 		= array();
		$total_realisasi_agustus = 0;
		$agustus 				= '';
		$rak_september 			= 0;
		$realisasi_september 	= array();
		$total_realisasi_september = 0;
		$september 				= '';
		$rak_oktober 			= 0;
		$realisasi_oktober 		= array();
		$total_realisasi_oktober = 0;
		$oktober				= '';
		$rak_november 			= 0;
		$realisasi_november 	= array();
		$total_realisasi_november = 0;
		$november 				= '';
		$rak_desember 			= 0;
		$realisasi_desember 	= array();
		$total_realisasi_desember = 0;
		$desember 				= '';

		// paket belanja
		$this->db->where('paket_belanja_detail.idpaket_belanja_detail', $idpaket_belanja_detail);
		$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = paket_belanja_detail.idpaket_belanja');
		$this->db->select('paket_belanja_detail.idpaket_belanja, akun_belanja.nama_akun_belanja, paket_belanja.nama_paket_belanja');
		$pb_detail = $this->db->get('paket_belanja_detail');
		$idpaket_belanja = $pb_detail->row()->idpaket_belanja;
		$nama_akun_belanja = $pb_detail->row()->nama_akun_belanja;
		$nama_paket_belanja = $pb_detail->row()->nama_paket_belanja;

		// Kategori / Sub Kategori
		$paket_belanja_detail = $this->query_paket_belanja_detail($idpaket_belanja_detail);
		// echo "<pre>"; print_r($this->db->last_query());

		$arr_data = array();
		foreach ($paket_belanja_detail->result() as $pbds_key => $ds_value) {

			$is_bold = 0;
			$mulai_bulan = 1;

			$rak_januari 			= 0;
			$realisasi_januari 		= array();
			$total_realisasi_januari = 0;
			$januari 				= array();
			$rak_februari			= 0;
			$realisasi_februari		= array();
			$total_realisasi_februari = 0;
			$februari 				= array();
			$rak_maret 				= 0;
			$realisasi_maret 		= array();
			$total_realisasi_maret 	= 0;
			$maret 					= array();
			$rak_april 				= 0;
			$realisasi_april 		= array();
			$total_realisasi_april 	= 0;
			$april 					= array();
			$rak_mei 				= 0;
			$realisasi_mei 			= array();
			$total_realisasi_mei 	= 0;
			$mei 					= array();
			$rak_juni 				= 0;
			$realisasi_juni 		= array();
			$total_realisasi_juni 	= 0;
			$juni 					= array();
			$rak_juli 				= 0;
			$realisasi_juli 		= array();
			$total_realisasi_juli 	= 0;
			$juli 					= array();
			$rak_agustus 			= 0;
			$realisasi_agustus 		= array();
			$total_realisasi_agustus = 0;
			$agustus 				= array();
			$rak_september 			= 0;
			$realisasi_september 	= array();
			$total_realisasi_september = 0;
			$september 				= array();
			$rak_oktober 			= 0;
			$realisasi_oktober 		= array();
			$total_realisasi_oktober = 0;
			$oktober				= array();
			$rak_november 			= 0;
			$realisasi_november 	= array();
			$total_realisasi_november = 0;
			$november 				= array();
			$rak_desember 			= 0;
			$realisasi_desember 	= array();
			$total_realisasi_desember = 0;
			$desember 				= array();

			$arr_ = 0;

			// simpan data nama akun belanja pada array pertama
			if ($pbds_key == 0) {
				$arr_data[] = array(
					'idkategori' 			=> '',
					'nama_kategori' 		=> $nama_akun_belanja,
					'idsub_kategori'	 	=> '',
					'nama_subkategori' 		=> '',
					'is_nama_akun_belanja'	=> 1,
					'is_bold'				=> 1,
					'is_sub'				=> 0,

					'rak_januari' 			=> 0,
					'realisasi_januari'		=> array(),
					'total_realisasi_januari' => 0,
					'januari' 				=> array(),
					'rak_februari' 			=> 0,
					'realisasi_februari'	=> array(),
					'total_realisasi_februari' => 0,
					'februari' 				=> array(),
					'rak_maret' 			=> 0,
					'realisasi_maret'		=> array(),
					'total_realisasi_maret' => 0,
					'maret' 				=> array(),
					'rak_april' 			=> 0,
					'realisasi_april'		=> array(),
					'total_realisasi_april' => 0,
					'april' 				=> array(),
					'rak_mei' 				=> 0,
					'realisasi_mei'			=> array(),
					'total_realisasi_mei' 	=> 0,
					'mei' 					=> array(),
					'rak_juni' 				=> 0,
					'realisasi_juni'		=> array(),
					'total_realisasi_juni' 	=> 0,
					'juni' 					=> array(),
					'rak_juli' 				=> 0,
					'realisasi_juli'		=> array(),
					'total_realisasi_juli' 	=> 0,
					'juli' 					=> array(),
					'rak_agustus' 			=> 0,
					'realisasi_agustus'		=> array(),
					'total_realisasi_agustus' => 0,
					'agustus' 				=> array(),
					'rak_september' 		=> 0,
					'realisasi_september'	=> array(),
					'total_realisasi_september' => 0,
					'september' 			=> array(),
					'rak_oktober' 			=> 0,
					'realisasi_oktober'		=> array(),
					'total_realisasi_oktober' => 0,
					'oktober' 				=> array(),
					'rak_november' 			=> 0,
					'realisasi_november'	=> array(),
					'total_realisasi_november' => 0,
					'november' 				=> array(),
					'rak_desember' 			=> 0,
					'realisasi_desember'	=> array(),
					'total_realisasi_desember' => 0,
					'desember' 				=> array(),
				);
			}

			// jika valuenya termasuk kategori (bukan sub kategori), maka set nilai seperti didalam kondisi
			if (strlen($ds_value->idkategori) > 0) {
				$is_bold = 1;
			}
			
			// jika kegiatan termasuk kategori, maka nilai target dan realisasi 0 (nol)
			// jika kegiatan termasuk sub kategori, maka pakai logika dibawah ini
			if (strlen($ds_value->idsub_kategori) > 0) {

				$history_months = $this->collect_history_month_data(array(
					'tahun_anggaran' => $tahun_anggaran,
					'idpaket_belanja' => $idpaket_belanja,
					'idpaket_belanja_detail_sub' => $ds_value->idpaket_belanja_detail_sub,
					'idsub_kategori' => $ds_value->idsub_kategori,
				));

				$januari = $history_months['januari']['rows'];
				$realisasi_januari = $history_months['januari']['realisasi'];
				$total_realisasi_januari = $history_months['januari']['total'];
				$februari = $history_months['februari']['rows'];
				$realisasi_februari = $history_months['februari']['realisasi'];
				$total_realisasi_februari = $history_months['februari']['total'];
				$maret = $history_months['maret']['rows'];
				$realisasi_maret = $history_months['maret']['realisasi'];
				$total_realisasi_maret = $history_months['maret']['total'];
				$april = $history_months['april']['rows'];
				$realisasi_april = $history_months['april']['realisasi'];
				$total_realisasi_april = $history_months['april']['total'];
				$mei = $history_months['mei']['rows'];
				$realisasi_mei = $history_months['mei']['realisasi'];
				$total_realisasi_mei = $history_months['mei']['total'];
				$juni = $history_months['juni']['rows'];
				$realisasi_juni = $history_months['juni']['realisasi'];
				$total_realisasi_juni = $history_months['juni']['total'];
				$juli = $history_months['juli']['rows'];
				$realisasi_juli = $history_months['juli']['realisasi'];
				$total_realisasi_juli = $history_months['juli']['total'];
				$agustus = $history_months['agustus']['rows'];
				$realisasi_agustus = $history_months['agustus']['realisasi'];
				$total_realisasi_agustus = $history_months['agustus']['total'];
				$september = $history_months['september']['rows'];
				$realisasi_september = $history_months['september']['realisasi'];
				$total_realisasi_september = $history_months['september']['total'];
				$oktober = $history_months['oktober']['rows'];
				$realisasi_oktober = $history_months['oktober']['realisasi'];
				$total_realisasi_oktober = $history_months['oktober']['total'];
				$november = $history_months['november']['rows'];
				$realisasi_november = $history_months['november']['realisasi'];
				$total_realisasi_november = $history_months['november']['total'];
				$desember = $history_months['desember']['rows'];
				$realisasi_desember = $history_months['desember']['realisasi'];
				$total_realisasi_desember = $history_months['desember']['total'];

				// ambil data realisasi RAK yang sudah ditentukan di menu paket belanja
				if (strlen($ds_value->rak_volume_januari) > 0) {
					$rak_januari = $ds_value->rak_jumlah_januari;
				}
				if (strlen($ds_value->rak_volume_februari) > 0) {
					$rak_februari = $ds_value->rak_jumlah_februari;
				}
				if (strlen($ds_value->rak_volume_maret) > 0) {
					$rak_maret = $ds_value->rak_jumlah_maret;
				}
				if (strlen($ds_value->rak_volume_april) > 0) {
					$rak_april = $ds_value->rak_jumlah_april;
				}
				if (strlen($ds_value->rak_volume_mei) > 0) {
					$rak_mei = $ds_value->rak_jumlah_mei;
				}
				if (strlen($ds_value->rak_volume_juni) > 0) {
					$rak_juni = $ds_value->rak_jumlah_juni;
				}
				if (strlen($ds_value->rak_volume_juli) > 0) {
					$rak_juli = $ds_value->rak_jumlah_juli;
				}
				if (strlen($ds_value->rak_volume_agustus) > 0) {
					$rak_agustus = $ds_value->rak_jumlah_agustus;
				}
				if (strlen($ds_value->rak_volume_september) > 0) {
					$rak_september = $ds_value->rak_jumlah_september;
				}
				if (strlen($ds_value->rak_volume_oktober) > 0) {
					$rak_oktober = $ds_value->rak_jumlah_oktober;
				}
				if (strlen($ds_value->rak_volume_november) > 0) {
					$rak_november = $ds_value->rak_jumlah_november;
				}
				if (strlen($ds_value->rak_volume_desember) > 0) {
					$rak_desember = $ds_value->rak_jumlah_desember;
				}
			}

			// simpan data uraian ke dalam array
			$arr_data[] = array(
				'idkategori' 			=> $ds_value->idkategori,
				'nama_kategori' 		=> $ds_value->nama_kategori,
				'idsub_kategori'	 	=> $ds_value->idsub_kategori,
				'nama_subkategori' 		=> $ds_value->nama_sub_kategori,
				'is_nama_akun_belanja'	=> 0,
				'is_bold'				=> $is_bold,
				'is_sub' 				=> 1,

				'rak_januari' 			=> $rak_januari,
				'realisasi_januari'		=> $realisasi_januari,
				'total_realisasi_januari' => $total_realisasi_januari,
				'januari' 				=> $januari,
				'rak_februari' 			=> $rak_februari,
				'realisasi_februari'	=> $realisasi_februari,
				'total_realisasi_februari' => $total_realisasi_februari,
				'februari' 				=> $februari,
				'rak_maret' 			=> $rak_maret,
				'realisasi_maret'		=> $realisasi_maret,
				'total_realisasi_maret' => $total_realisasi_maret,
				'maret' 				=> $maret,
				'rak_april' 			=> $rak_april,
				'realisasi_april'		=> $realisasi_april,
				'total_realisasi_april' => $total_realisasi_april,
				'april' 				=> $april,
				'rak_mei' 				=> $rak_mei,
				'realisasi_mei'			=> $realisasi_mei,
				'total_realisasi_mei' 	=> $total_realisasi_mei,
				'mei' 					=> $mei,
				'rak_juni' 				=> $rak_juni,
				'realisasi_juni'		=> $realisasi_juni,
				'total_realisasi_juni' 	=> $total_realisasi_juni,
				'juni' 					=> $juni,
				'rak_juli' 				=> $rak_juli,
				'realisasi_juli'		=> $realisasi_juli,
				'total_realisasi_juli' 	=> $total_realisasi_juli,
				'juli' 					=> $juli,
				'rak_agustus' 			=> $rak_agustus,
				'realisasi_agustus'		=> $realisasi_agustus,
				'total_realisasi_agustus' => $total_realisasi_agustus,
				'agustus' 				=> $agustus,
				'rak_september' 		=> $rak_september,
				'realisasi_september'	=> $realisasi_september,
				'total_realisasi_september' => $total_realisasi_september,
				'september' 			=> $september,
				'rak_oktober' 			=> $rak_oktober,
				'realisasi_oktober'		=> $realisasi_oktober,
				'total_realisasi_oktober' => $total_realisasi_oktober,
				'oktober' 				=> $oktober,
				'rak_november' 			=> $rak_november,
				'realisasi_november'	=> $realisasi_november,
				'total_realisasi_november' => $total_realisasi_november,
				'november' 				=> $november,
				'rak_desember' 			=> $rak_desember,
				'realisasi_desember'	=> $realisasi_desember,
				'total_realisasi_desember' => $total_realisasi_desember,
				'desember' 				=> $desember,
			);


			/////////////////////////////////////////////////////////////////////////////////
			// jika kegiatan termasuk kategori, maka pasti punya sub kategori
			// logika untuk mengambil nominal target dan realisasi sub kategorinya ada dibawah ini
			if (strlen($ds_value->idkategori) > 0) {
				$paket_belanja_detail_sub = $this->query_paket_belanja_detail_sub($ds_value->idpaket_belanja_detail_sub);
				// echo "<pre>"; print_r($this->db->last_query());die;

				foreach ($paket_belanja_detail_sub->result() as $dss_key => $dss_value) {

					$is_bold = 0;
					$mulai_bulan = 1;

					$rak_januari 			= 0;
					$realisasi_januari 		= array();
					$total_realisasi_januari = 0;
					$januari 				= array();
					$rak_februari			= 0;
					$realisasi_februari		= array();
					$total_realisasi_februari = 0;
					$februari 				= array();
					$rak_maret 				= 0;
					$realisasi_maret 		= array();
					$total_realisasi_maret 	= 0;
					$maret 					= array();
					$rak_april 				= 0;
					$realisasi_april 		= array();
					$total_realisasi_april 	= 0;
					$april 					= array();
					$rak_mei 				= 0;
					$realisasi_mei 			= array();
					$total_realisasi_mei 	= 0;
					$mei 					= array();
					$rak_juni 				= 0;
					$realisasi_juni 		= array();
					$total_realisasi_juni 	= 0;
					$juni 					= array();
					$rak_juli 				= 0;
					$realisasi_juli 		= array();
					$total_realisasi_juli 	= 0;
					$juli 					= array();
					$rak_agustus 			= 0;
					$realisasi_agustus 		= array();
					$total_realisasi_agustus = 0;
					$agustus 				= array();
					$rak_september 			= 0;
					$realisasi_september 	= array();
					$total_realisasi_september = 0;
					$september 				= array();
					$rak_oktober 			= 0;
					$realisasi_oktober 		= array();
					$total_realisasi_oktober = 0;
					$oktober				= array();
					$rak_november 			= 0;
					$realisasi_november 	= array();
					$total_realisasi_november = 0;
					$november 				= array();
					$rak_desember 			= 0;
					$realisasi_desember 	= array();
					$total_realisasi_desember = 0;
					$desember 				= array();

					// jika valuenya termasuk kategori (bukan sub kategori), maka set nilai seperti didalam kondisi
					if (strlen($dss_value->idkategori) > 0) {
						$is_bold = 1;
					}
					
					if (strlen($dss_value->idsub_kategori) > 0) {

						$history_months = $this->collect_history_month_data(array(
							'tahun_anggaran' => $tahun_anggaran,
							'idpaket_belanja' => $dss_value->idpaket_belanja,
							'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
							'idsub_kategori' => $dss_value->idsub_kategori,
						));

						$januari = $history_months['januari']['rows'];
						$realisasi_januari = $history_months['januari']['realisasi'];
						$total_realisasi_januari = $history_months['januari']['total'];
						$februari = $history_months['februari']['rows'];
						$realisasi_februari = $history_months['februari']['realisasi'];
						$total_realisasi_februari = $history_months['februari']['total'];
						$maret = $history_months['maret']['rows'];
						$realisasi_maret = $history_months['maret']['realisasi'];
						$total_realisasi_maret = $history_months['maret']['total'];
						$april = $history_months['april']['rows'];
						$realisasi_april = $history_months['april']['realisasi'];
						$total_realisasi_april = $history_months['april']['total'];
						$mei = $history_months['mei']['rows'];
						$realisasi_mei = $history_months['mei']['realisasi'];
						$total_realisasi_mei = $history_months['mei']['total'];
						$juni = $history_months['juni']['rows'];
						$realisasi_juni = $history_months['juni']['realisasi'];
						$total_realisasi_juni = $history_months['juni']['total'];
						$juli = $history_months['juli']['rows'];
						$realisasi_juli = $history_months['juli']['realisasi'];
						$total_realisasi_juli = $history_months['juli']['total'];
						$agustus = $history_months['agustus']['rows'];
						$realisasi_agustus = $history_months['agustus']['realisasi'];
						$total_realisasi_agustus = $history_months['agustus']['total'];
						$september = $history_months['september']['rows'];
						$realisasi_september = $history_months['september']['realisasi'];
						$total_realisasi_september = $history_months['september']['total'];
						$oktober = $history_months['oktober']['rows'];
						$realisasi_oktober = $history_months['oktober']['realisasi'];
						$total_realisasi_oktober = $history_months['oktober']['total'];
						$november = $history_months['november']['rows'];
						$realisasi_november = $history_months['november']['realisasi'];
						$total_realisasi_november = $history_months['november']['total'];
						$desember = $history_months['desember']['rows'];
						$realisasi_desember = $history_months['desember']['realisasi'];
						$total_realisasi_desember = $history_months['desember']['total'];

						// ambil data realisasi RAK yang sudah ditentukan di menu paket belanja
						if (strlen($dss_value->rak_volume_januari) > 0) {
							$rak_januari = $dss_value->rak_jumlah_januari;
						}
						if (strlen($dss_value->rak_volume_februari) > 0) {
							$rak_februari = $dss_value->rak_jumlah_februari;
						}
						if (strlen($dss_value->rak_volume_maret) > 0) {
							$rak_maret = $dss_value->rak_jumlah_maret;
						}
						if (strlen($dss_value->rak_volume_april) > 0) {
							$rak_april = $dss_value->rak_jumlah_april;
						}
						if (strlen($dss_value->rak_volume_mei) > 0) {
							$rak_mei = $dss_value->rak_jumlah_mei;
						}
						if (strlen($dss_value->rak_volume_juni) > 0) {
							$rak_juni = $dss_value->rak_jumlah_juni;
						}
						if (strlen($dss_value->rak_volume_juli) > 0) {
							$rak_juli = $dss_value->rak_jumlah_juli;
						}
						if (strlen($dss_value->rak_volume_agustus) > 0) {
							$rak_agustus = $dss_value->rak_jumlah_agustus;
						}
						if (strlen($dss_value->rak_volume_september) > 0) {
							$rak_september = $dss_value->rak_jumlah_september;
						}
						if (strlen($dss_value->rak_volume_oktober) > 0) {
							$rak_oktober = $dss_value->rak_jumlah_oktober;
						}
						if (strlen($dss_value->rak_volume_november) > 0) {
							$rak_november = $dss_value->rak_jumlah_november;
						}
						if (strlen($dss_value->rak_volume_desember) > 0) {
							$rak_desember = $dss_value->rak_jumlah_desember;
						}
					}

					$is_sub = 1;
					if (strlen($dss_value->is_idpaket_belanja_detail_sub) > 0) {
						$is_sub = 2;
					}

					// simpan data uraian ke dalam array
					$arr_data[] = array(
						'idkategori' 			=> '',
						'nama_kategori' 		=> '',
						'idsub_kategori'	 	=> $dss_value->idsub_kategori,
						'nama_subkategori' 		=> $dss_value->nama_sub_kategori,
						'is_nama_akun_belanja'	=> 0,
						'is_bold'				=> $is_bold,
						'is_sub' 				=> $is_sub,

						'rak_januari' 			=> $rak_januari,
						'realisasi_januari'		=> $realisasi_januari,
						'total_realisasi_januari' => $total_realisasi_januari,
						'januari' 				=> $januari,
						'rak_februari' 			=> $rak_februari,
						'realisasi_februari'	=> $realisasi_februari,
						'total_realisasi_februari' => $total_realisasi_februari,
						'februari' 				=> $februari,
						'rak_maret' 			=> $rak_maret,
						'realisasi_maret'		=> $realisasi_maret,
						'total_realisasi_maret' => $total_realisasi_maret,
						'maret' 				=> $maret,
						'rak_april' 			=> $rak_april,
						'realisasi_april'		=> $realisasi_april,
						'total_realisasi_april' => $total_realisasi_april,
						'april' 				=> $april,
						'rak_mei' 				=> $rak_mei,
						'realisasi_mei'			=> $realisasi_mei,
						'total_realisasi_mei' 	=> $total_realisasi_mei,
						'mei' 					=> $mei,
						'rak_juni' 				=> $rak_juni,
						'realisasi_juni'		=> $realisasi_juni,
						'total_realisasi_juni' 	=> $total_realisasi_juni,
						'juni' 					=> $juni,
						'rak_juli' 				=> $rak_juli,
						'realisasi_juli'		=> $realisasi_juli,
						'total_realisasi_juli' 	=> $total_realisasi_juli,
						'juli' 					=> $juli,
						'rak_agustus' 			=> $rak_agustus,
						'realisasi_agustus'		=> $realisasi_agustus,
						'total_realisasi_agustus' => $total_realisasi_agustus,
						'agustus' 				=> $agustus,
						'rak_september' 		=> $rak_september,
						'realisasi_september'	=> $realisasi_september,
						'total_realisasi_september' => $total_realisasi_september,
						'september' 			=> $september,
						'rak_oktober' 			=> $rak_oktober,
						'realisasi_oktober'		=> $realisasi_oktober,
						'total_realisasi_oktober' => $total_realisasi_oktober,
						'oktober' 				=> $oktober,
						'rak_november' 			=> $rak_november,
						'realisasi_november'	=> $realisasi_november,
						'total_realisasi_november' => $total_realisasi_november,
						'november' 				=> $november,
						'rak_desember' 			=> $rak_desember,
						'realisasi_desember'	=> $realisasi_desember,
						'total_realisasi_desember' => $total_realisasi_desember,
						'desember' 				=> $desember,
					);
				}
			}

			// echo "<pre>"; print_r($arr_data);die();
		}

		$data['nama_paket_belanja'] = $nama_paket_belanja;
		$data['arr_data'] = $arr_data;
		// echo "<pre>"; print_r($data);die();

		$view = $this->load->view('evaluasi_anggaran/v_history_rak_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function query_get_total_realisasi($the_data) {
        $total_realisasi = 0;

		$idsub_kategori = azarr($the_data, 'idsub_kategori');
		$idpaket_belanja_detail_sub = azarr($the_data, 'idpaket_belanja_detail_sub');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja');
		$filter_tahun = azarr($the_data, 'filter_tahun');
		

		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan.purchase_plan_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y") = "'.$filter_tahun.'"');
		$this->db->where('budget_realization_detail.idsub_kategori = "'.$idsub_kategori.'" ');
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
		MAX(budget_realization_detail.provider) as provider, sum(budget_realization_detail.volume) as total_realisasi, sum(budget_realization_detail.male) as male, sum(budget_realization_detail.female) as female, sum(budget_realization_detail.unit_price) as unit_price, sum(ppn) as ppn, sum(pph) as pph, sum(budget_realization_detail.total_realization_detail) as total');
		$p_plan = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query());


		// $this->db->where('transaction_detail.iduraian', $iduraian);
		// $this->db->where('transaction_detail.idpaket_belanja', $idpaket_belanja);
		// $this->db->where('transaction_detail.status', 1);
		// $this->db->where('transaction.status', 1);
		// $this->db->where('transaction.transaction_status != "DRAFT" ');
		// $this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
        // $this->db->select('sum(volume) as total_realisasi');
		// $transaction = $this->db->get('transaction');

        if ($p_plan->num_rows() > 0) {
            $total_realisasi = $p_plan->row()->total_realisasi;
        }

		return $total_realisasi;
	}


	/*
    |--------------------------------------------------------------------------
    | GENERATE NAME
    |--------------------------------------------------------------------------
    */

    private function generate_nama_urusan($urusan) {
        return $urusan->no_rekening_urusan.' - '.$urusan->nama_urusan;
    }

    private function generate_nama_bidang($urusan, $bidang) {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.
            ' - '.
            $bidang->nama_bidang_urusan;
    }

    private function generate_nama_program($urusan, $bidang, $program) {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.'.'.
            $program->no_rekening_program.
            ' - '.
            $program->nama_program;
    }

    private function generate_nama_kegiatan($urusan, $bidang, $program, $kegiatan) {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.'.'.
            $program->no_rekening_program.'.'.
            $kegiatan->no_rekening_kegiatan.
            ' - '.
            $kegiatan->nama_kegiatan;
    }

    private function generate_nama_sub_kegiatan($urusan, $bidang, $program, $kegiatan, $sub_kegiatan) {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.'.'.
            $program->no_rekening_program.'.'.
            $kegiatan->no_rekening_kegiatan.'.'.
            $sub_kegiatan->no_rekening_subkegiatan.
            ' - '.
            $sub_kegiatan->nama_subkegiatan;
    }

	private function calculate_tw_realisasi($params) {
        $mulai_bulan    = $params['mulai_bulan'];
        $tahun_anggaran = $params['tahun_anggaran'];

        $result = $this->query_realisasi([
            'tahun_anggaran'             => $tahun_anggaran,
            'start_bulan'                => sprintf('%02d', $mulai_bulan),
            'end_bulan'                  => sprintf('%02d', $mulai_bulan + 2),
            'idpaket_belanja'            => $params['idpaket_belanja'],
            'idsub_kategori'             => $params['idsub_kategori'],
            'idpaket_belanja_detail_sub' => $params['idpaket_belanja_detail_sub'],
            'mode'                       => 'bulanan_range',
        ]);

        if ($result->num_rows() > 0) {
            $row = $result->row();

            return [
                'volume' => (float) $row->volume,
                'total'  => (float) $row->total,
            ];
        }

        return [
            'volume' => 0,
            'total'  => 0,
        ];
    }



	/**
     * QUERY REALISASI
     */
    private function query_realisasi($params) {
        $tahun_anggaran = $params['tahun_anggaran'];
        $mode           = $params['mode'];

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization_detail.status', 1);
        $this->db->where('purchase_plan_detail.idpaket_belanja', $params['idpaket_belanja']);
        $this->db->where('purchase_plan_detail.idpaket_belanja_detail_sub', $params['idpaket_belanja_detail_sub']);
        $this->db->where('budget_realization_detail.idsub_kategori', $params['idsub_kategori']);
        $this->db->where('purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');

        /**
         * FILTER STATUS VALIDASI
         */
        $this->apply_status_validation_filter();

        /**
         * FILTER TANGGAL
         */
        if ($mode === 'bulanan') {
            $this->apply_status_date_filter($tahun_anggaran . '-' . $params['bulan']);
        } 
        elseif ($mode === 'bulanan_range') {
            $this->apply_status_date_range_filter(
                $tahun_anggaran . '-' . $params['start_bulan'],
                $tahun_anggaran . '-' . $params['end_bulan']
            );
        } 
        else {
            $this->apply_status_date_range_filter(
                $tahun_anggaran . '-01',
                $tahun_anggaran . '-' . $params['bulan']
            );
        }

        /**
         * JOIN
         */
        $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
        $this->db->join('contract_detail', 'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
        $this->db->join('contract', 'contract.idcontract = contract_detail.idcontract', 'left');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail', 'left');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization', 'left');
        $this->db->join('verification', 'verification.idbudget_realization = budget_realization.idbudget_realization', 'left');
        $this->db->join('npd_detail', 'npd_detail.idverification = verification.idverification', 'left');
        $this->db->join('npd', 'npd.idnpd = npd_detail.idnpd', 'left');

        $this->db->select('
            DATE_FORMAT(MAX(purchase_plan.purchase_plan_date), "%d-%m-%Y") as purchase_plan_date, 
        	MAX(budget_realization_detail.provider) as provider, 
            sum(budget_realization_detail.volume) as volume, 
            sum(budget_realization_detail.male) as male, 
            sum(budget_realization_detail.female) as female, 
            sum(budget_realization_detail.unit_price) as unit_price, 
            sum(ppn) as ppn, 
            sum(pph) as pph, 
            sum(budget_realization_detail.total_realization_detail) as total
        ');
        
        $plan = $this->db->get('purchase_plan');
        // echo "<pre>"; print_r($this->db->last_query()); die;

        return $plan;
    }

    /**
     * FILTER RANGE TANGGAL
     */
    private function apply_status_date_range_filter($start, $end) {
        $range = $this->get_month_date_range($start);

        if ($start !== $end) {
            $end_range = $this->get_month_date_range($end);
            $range['end'] = $end_range['end'];
        }

        $this->db->group_start()

            ->or_group_start()
                ->where('contract.contract_status', 'SUDAH DIBAYAR BENDAHARA')
                ->where('npd.confirm_payment_date >=', $range['start'])
                ->where('npd.confirm_payment_date <=', $range['end'])
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'MENUNGGU PEMBAYARAN')
                ->where('npd.npd_date_created >=', $range['start'])
                ->where('npd.npd_date_created <=', $range['end'])
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'INPUT NPD')
                ->where('npd.npd_date_created >=', $range['start'])
                ->where('npd.npd_date_created <=', $range['end'])
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'DITOLAK VERIFIKATOR')
                ->where('verification.confirm_verification_date >=', $range['start'])
                ->where('verification.confirm_verification_date <=', $range['end'])
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'SUDAH DIVERIFIKASI')
                ->where('verification.confirm_verification_date >=', $range['start'])
                ->where('verification.confirm_verification_date <=', $range['end'])
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'MENUNGGU VERIFIKASI')
                ->where('budget_realization.realization_date >=', $range['start'])
                ->where('budget_realization.realization_date <=', $range['end'])
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'KONTRAK PENGADAAN')
                ->where('contract.contract_date >=', $range['start'])
                ->where('contract.contract_date <=', $range['end'])
            ->group_end()

        ->group_end();
    }

    private function get_month_date_range($yearMonth) {
        $date = date('Y-m-01', strtotime($yearMonth));

        return [
            'start' => $date,
            'end'   => date('Y-m-t', strtotime($date))
        ];
    }
}