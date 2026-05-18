<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_detail_evaluasi_anggaran extends CI_Controller {

    private $cache_realisasi      = [];
    private $cache_tw_sebelumnya  = [];

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
        $this->load->helper('az_auth');
        az_check_auth('role_report_detail_evaluasi_anggaran');
        $this->controller = 'report_detail_evaluasi_anggaran_backup';
		$this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index() {
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

        $tahun_anggaran = $this->input->get('tahun_anggaran') ?: date('Y');

        $data = [
            'tahun_anggaran' => $tahun_anggaran,
            'arr_data'       => $this->get_data($tahun_anggaran)
        ];

        // echo "<pre>"; print_r($data['arr_data']['urusan']);die;
        // echo "<pre>"; print_r($data);die;   

        $js = az_add_js('report_detail_evaluasi_anggaran/vjs_report_detail_evaluasi_anggaran', $data, true);
		$azapp->add_js($js);

		$view = $this->load->view('report_detail_evaluasi_anggaran/v_report_detail_evaluasi_anggaran_backup', $data, true);
		$azapp->add_content($view);

		$data_header['title'] = 'Laporan Detail Evaluasi Anggaran';
		$data_header['breadcrumb'] = array('report');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();
    }

    public function print_report() {
        $tahun_anggaran = $this->uri->segment(3);

        $data = [
            'tahun_anggaran' => $tahun_anggaran,
            'arr_data'       => $this->get_data($tahun_anggaran)
        ];

        $this->load->view("report_detail_evaluasi_anggaran/v_report_detail_evaluasi_anggaran_print", $data);
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD DATA
    |--------------------------------------------------------------------------
    */

    private function get_data($tahun_anggaran) {
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

                            $paket_list = $this->query_paket_belanja($sub_kegiatan->idsub_kegiatan)->result();

                            foreach ($paket_list as $paket) {

                                $arr_akun = [];

                                $akun_list = $this->query_akun_belanja($paket->idpaket_belanja)->result();

                                foreach ($akun_list as $akun) {

                                    $detail_data = $this->build_detail_sub($akun, $paket, $tahun_anggaran);

                                    $arr_akun[] = array(
                                        'idpaket_belanja_detail'  => $akun->idpaket_belanja_detail,
                                        'idakun_belanja'          => $akun->idakun_belanja,
                                        'no_rekening_akunbelanja' => $akun->no_rekening_akunbelanja,
                                        'nama_akun_belanja'       => $akun->nama_akun_belanja,
                                        'total_jumlah'            => $detail_data['total_jumlah'],
                                        'total_realisasi'         => $detail_data['total_realisasi'],
                                        'total_persentase'        => $detail_data['total_persentase'],
                                        'arr_detail_sub'          => $detail_data['detail']
                                    );
                                }

                                $arr_paket[] = array(
                                    'idpaket_belanja'    => $paket->idpaket_belanja,
                                    'nama_paket_belanja' => $paket->nama_paket_belanja,
                                    'nilai_anggaran'     => $paket->nilai_anggaran,
                                    'akun_belanja'       => $arr_akun
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

        return array(
            'tahun_anggaran' => $tahun_anggaran,
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
        $total_realisasi   = 0;
        $total_persentase  = 0;

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

        $realisasi_map = $this->build_realisasi_map(
            $paket->idpaket_belanja,
            array_unique($realisasi_ids),
            array_unique($idsub_categories),
            $tahun_anggaran
        );

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

                $total_realisasi += $tw_data['realisasi_sampai_tw4'];

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
                    'jumlah'                     => $sub_sub->jumlah
                ], $tw_data);
            }

            $tw_data = $this->default_tw_data();

            if (empty($child_sub)) {
                $tw_data = $this->build_tw_data_for_subdetail(
                    $detail->idpaket_belanja_detail_sub,
                    $detail->jumlah,
                    $realisasi_map
                );

                $total_realisasi += $tw_data['realisasi_sampai_tw4'];
            }

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
                'arr_pd_detail_sub_sub'      => $arr_sub_sub
            ], $tw_data);
        }

        if ($total_jumlah > 0 && $total_realisasi > 0) {
            $total_persentase = ($total_realisasi / $total_jumlah) * 100;
        }

        return [
            'detail'           => $result_detail,
            'total_jumlah'     => $total_jumlah,
            'total_realisasi'  => $total_realisasi,
            'total_persentase' => $total_persentase
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE TW
    |--------------------------------------------------------------------------
    */

    private function generate_tw_realisasi($params)
    {
        $result = [];
        $jumlah = isset($params['jumlah']) ? (float) $params['jumlah'] : 0;

        for ($tw = 1; $tw <= 4; $tw++) {
            $cache_key = implode('|', [
                'tw_realisasi',
                $params['idpaket_belanja'],
                $params['idpaket_belanja_detail'],
                $params['idpaket_belanja_detail_sub'],
                $params['idsub_kategori'],
                $params['tahun_anggaran'],
                $tw,
                $params['is_sub_detail'] ? '1' : '0'
            ]);

            if (!isset($this->cache_realisasi[$cache_key])) {
                $previous = $this->get_previous_tw_realisasi([
                    'tw'                             => $tw,
                    'tahun_anggaran'                 => $params['tahun_anggaran'],
                    'idpaket_belanja'               => $params['idpaket_belanja'],
                    'idsub_kategori'                => $params['idsub_kategori'],
                    'idpaket_belanja_detail_sub'    => $params['idpaket_belanja_detail_sub']
                ]);

                $current = $this->calculate_tw_realisasi([
                    'mulai_bulan'                   => $this->get_start_month_by_tw($tw),
                    'tahun_anggaran'                => $params['tahun_anggaran'],
                    'idpaket_belanja'               => $params['idpaket_belanja'],
                    'idsub_kategori'                => $params['idsub_kategori'],
                    'idpaket_belanja_detail_sub'    => $params['idpaket_belanja_detail_sub']
                ]);

                $this->cache_realisasi[$cache_key] = [
                    'realisasi_rp_sampai'  => $current['total'] + $previous['realisasi_rp_sebelumnya'],
                    'realisasi_vol_sampai' => $current['volume'] + $previous['realisasi_vol_sebelumnya']
                ];
            }

            $nominal = $this->cache_realisasi[$cache_key]['realisasi_rp_sampai'];
            $persentase = $jumlah > 0 ? ($nominal / $jumlah) * 100 : 0;

            $result['realisasi_sampai_tw'.$tw] = $nominal;
            $result['persen_realisasi_sampai_tw'.$tw] = $persentase;
        }

        return $result;
    }

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

        $this->db
            ->where_in('purchase_plan_detail.purchase_plan_detail_status', $statuses)
            ->where('budget_realization.realization_status !=', 'DRAFT');
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY MASTER
    |--------------------------------------------------------------------------
    */

    private function base_master_query($table, $where = [], $order_by = '', $select = '*') {
        $this->db->from($table);

        foreach ($where as $field => $value) {
            $this->db->where($field, $value);
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

    public function query_paket_belanja($idsub_kegiatan) {
        return $this->base_master_query(
            'paket_belanja',
            [
                'status'                 => 1,
                'status_paket_belanja'   => 'OK',
                'idsub_kegiatan'         => $idsub_kegiatan
            ],
            'idpaket_belanja ASC',
            $this->master_select['paket_belanja']
        );
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
            paket_belanja_detail_sub.jumlah
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
            paket_belanja_detail_sub.idpaket_belanja_detail,
            paket_belanja_detail_sub.idkategori,
            sub_kategori.idsub_kategori,
            sub_kategori.nama_sub_kategori,
            kode_rekening.kode_rekening,
            paket_belanja_detail_sub.is_kategori,
            paket_belanja_detail_sub.is_subkategori,
            paket_belanja_detail_sub.volume,
            satuan.nama_satuan,
            paket_belanja_detail_sub.harga_satuan,
            paket_belanja_detail_sub.jumlah
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
            paket_belanja_detail_sub.jumlah
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

        $this->db->select('purchase_plan_detail.idpaket_belanja_detail_sub as id', false);
        $this->db->select("SUM(CASE WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw1_end}' THEN budget_realization_detail.total_realization_detail ELSE 0 END) as tw1", false);
        $this->db->select("SUM(CASE WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw2_end}' THEN budget_realization_detail.total_realization_detail ELSE 0 END) as tw2", false);
        $this->db->select("SUM(CASE WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw3_end}' THEN budget_realization_detail.total_realization_detail ELSE 0 END) as tw3", false);
        $this->db->select("SUM(CASE WHEN {$status_date} >= '{$year_start}' AND {$status_date} <= '{$tw4_end}' THEN budget_realization_detail.total_realization_detail ELSE 0 END) as tw4", false);

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization_detail.status', 1);
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

        $this->db->group_by('purchase_plan_detail.idpaket_belanja_detail_sub');

        $result = $this->db->get('purchase_plan')->result();

        $map = [];
        foreach ($result as $row) {
            $map[$row->id] = [
                'realisasi_sampai_tw1' => (float) $row->tw1,
                'realisasi_sampai_tw2' => (float) $row->tw2,
                'realisasi_sampai_tw3' => (float) $row->tw3,
                'realisasi_sampai_tw4' => (float) $row->tw4,
            ];
        }

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
     * REALISASI TW SEBELUMNYA
     */
    private function get_previous_tw_realisasi($params) {
        $tw = (int) $params['tw'];

        if ($tw <= 1) {
            return [
                'realisasi_lk_sebelumnya'  => 0,
                'realisasi_pr_sebelumnya'  => 0,
                'realisasi_vol_sebelumnya' => 0,
                'realisasi_rp_sebelumnya'  => 0,
            ];
        }

        $cache_key = implode('|', [
            'previous_tw',
            $params['idpaket_belanja'],
            $params['idsub_kategori'],
            $params['idpaket_belanja_detail_sub'],
            $params['tahun_anggaran'],
            $tw
        ]);

        if (isset($this->cache_tw_sebelumnya[$cache_key])) {
            return $this->cache_tw_sebelumnya[$cache_key];
        }

        $sampai_bulan = $this->get_end_month_by_tw($tw - 1);

        $result = $this->query_realisasi([
            'tahun_anggaran'             => $params['tahun_anggaran'],
            'bulan'                      => sprintf('%02d', $sampai_bulan),
            'idpaket_belanja'            => $params['idpaket_belanja'],
            'idsub_kategori'             => $params['idsub_kategori'],
            'idpaket_belanja_detail_sub' => $params['idpaket_belanja_detail_sub'],
            'mode'                       => 'kumulatif',
        ]);

        $data = $result->row();

        return $this->cache_tw_sebelumnya[$cache_key] = [
            'realisasi_lk_sebelumnya'  => (float) ($data->male ?? 0),
            'realisasi_pr_sebelumnya'  => (float) ($data->female ?? 0),
            'realisasi_vol_sebelumnya' => (float) ($data->volume ?? 0),
            'realisasi_rp_sebelumnya'  => (float) ($data->total ?? 0),
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

    /**
     * GET BULAN AWAL TW
     */
    private function get_start_month_by_tw($tw) {
        $mapping = [
            1 => 1,
            2 => 4,
            3 => 7,
            4 => 10,
        ];

        return $mapping[$tw] ?? 1;
    }

    /**
     * GET BULAN AKHIR TW
     */
    private function get_end_month_by_tw($tw) {
        $mapping = [
            1 => 3,
            2 => 6,
            3 => 9,
        ];

        return $mapping[$tw] ?? 3;
    }
}