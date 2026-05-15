<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_detail_evaluasi_anggaran extends CI_Controller {

    private $cache_realisasi      = [];
    private $cache_tw_sebelumnya  = [];

    private $controller = 'report_detail_evaluasi_anggaran';

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

    public function __construct()
    {
        parent::__construct();

        $this->load->helper([
            'az_auth',
            'az_crud',
            'az_config',
            'az_role'
        ]);

        az_check_auth('role_report_detail_evaluasi_anggaran');
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->load->library('AZApp');

        $tahun_anggaran = $this->input->get('tahun_anggaran') ?: date('Y');

        $data = [
            'tahun_anggaran' => $tahun_anggaran,
            'arr_data'       => $this->get_data($tahun_anggaran)
        ];

        // echo "<pre>"; print_r($data['arr_data']['urusan']);die;
        // echo "<pre>"; print_r($data);die;   

        $this->load_assets($data);

        $view = $this->load->view(
            'report_detail_evaluasi_anggaran/v_report_detail_evaluasi_anggaran',
            $data,
            true
        );

        $this->azapp->add_content($view);

        $this->azapp->set_data_header([
            'title'      => 'Laporan Detail Evaluasi Anggaran',
            'breadcrumb' => ['report']
        ]);

        echo $this->azapp->render();
    }

    public function print_report()
    {
        $tahun_anggaran = $this->uri->segment(3);

        $data = [
            'tahun_anggaran' => $tahun_anggaran,
            'arr_data'       => $this->get_data($tahun_anggaran)
        ];

        $this->load->view(
            'report_detail_evaluasi_anggaran/v_report_detail_evaluasi_anggaran_print',
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD ASSET
    |--------------------------------------------------------------------------
    */

    private function load_assets($data)
    {
        $this->azapp = $this->azapp ?? new stdClass();

        $this->load->library('AZApp');

        $js = az_add_js(
            'report_detail_evaluasi_anggaran/vjs_report_detail_evaluasi_anggaran',
            $data,
            true
        );

        $this->azapp->add_js($js);
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD DATA
    |--------------------------------------------------------------------------
    */

    private function get_data($tahun_anggaran)
    {
        $result_urusan = [];

        $urusan_list = $this->query_urusan_pemerintah($tahun_anggaran)->result();

        foreach ($urusan_list as $urusan) {

            $arr_bidang = [];

            $bidang_list = $this->query_bidang_urusan(
                $urusan->idurusan_pemerintah
            )->result();

            foreach ($bidang_list as $bidang) {

                $arr_program = [];

                $program_list = $this->query_program(
                    $bidang->idbidang_urusan
                )->result();

                foreach ($program_list as $program) {

                    $arr_kegiatan = [];

                    $kegiatan_list = $this->query_kegiatan(
                        $program->idprogram
                    )->result();

                    foreach ($kegiatan_list as $kegiatan) {

                        $arr_sub_kegiatan = [];

                        $sub_kegiatan_list = $this->query_sub_kegiatan(
                            $kegiatan->idkegiatan
                        )->result();

                        foreach ($sub_kegiatan_list as $sub_kegiatan) {

                            $arr_paket = [];

                            $paket_list = $this->query_paket_belanja(
                                $sub_kegiatan->idsub_kegiatan
                            )->result();

                            foreach ($paket_list as $paket) {

                                $arr_akun = [];

                                $akun_list = $this->query_akun_belanja(
                                    $paket->idpaket_belanja
                                )->result();

                                foreach ($akun_list as $akun) {

                                    $detail_data = $this->build_detail_sub(
                                        $akun,
                                        $paket,
                                        $tahun_anggaran
                                    );

                                    $arr_akun[] = [
                                        'idpaket_belanja_detail'  => $akun->idpaket_belanja_detail,
                                        'idakun_belanja'          => $akun->idakun_belanja,
                                        'no_rekening_akunbelanja' => $akun->no_rekening_akunbelanja,
                                        'nama_akun_belanja'       => $akun->nama_akun_belanja,
                                        'total_jumlah'            => $detail_data['total_jumlah'],
                                        'total_realisasi'         => $detail_data['total_realisasi'],
                                        'total_persentase'        => $detail_data['total_persentase'],
                                        'arr_detail_sub'          => $detail_data['detail']
                                    ];
                                }

                                $arr_paket[] = [
                                    'idpaket_belanja'    => $paket->idpaket_belanja,
                                    'nama_paket_belanja' => $paket->nama_paket_belanja,
                                    'nilai_anggaran'     => $paket->nilai_anggaran,
                                    'akun_belanja'       => $arr_akun
                                ];
                            }

                            $arr_sub_kegiatan[] = [
                                'idsub_kegiatan' => $sub_kegiatan->idsub_kegiatan,
                                'nama_sub_kegiatan' => $this->generate_nama_sub_kegiatan(
                                    $urusan,
                                    $bidang,
                                    $program,
                                    $kegiatan,
                                    $sub_kegiatan
                                ),
                                'paket_belanja' => $arr_paket
                            ];
                        }

                        $arr_kegiatan[] = [
                            'idkegiatan'    => $kegiatan->idkegiatan,
                            'nama_kegiatan' => $this->generate_nama_kegiatan(
                                $urusan,
                                $bidang,
                                $program,
                                $kegiatan
                            ),
                            'sub_kegiatan' => $arr_sub_kegiatan
                        ];
                    }

                    $arr_program[] = [
                        'idprogram'    => $program->idprogram,
                        'nama_program' => $this->generate_nama_program(
                            $urusan,
                            $bidang,
                            $program
                        ),
                        'kegiatan' => $arr_kegiatan
                    ];
                }

                $arr_bidang[] = [
                    'idbidang_urusan' => $bidang->idbidang_urusan,
                    'nama_bidang_urusan' => $this->generate_nama_bidang(
                        $urusan,
                        $bidang
                    ),
                    'program' => $arr_program
                ];
            }

            $result_urusan[] = [
                'idurusan' => $urusan->idurusan_pemerintah,
                'nama_urusan' => $this->generate_nama_urusan($urusan),
                'bidang_urusan' => $arr_bidang
            ];
        }

        return [
            'tahun_anggaran' => $tahun_anggaran,
            'urusan'         => $result_urusan
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD DETAIL
    |--------------------------------------------------------------------------
    */

    private function build_detail_sub($akun, $paket, $tahun_anggaran)
    {
        $details = $this->query_paket_belanja_detail(
            $akun->idpaket_belanja_detail
        )->result();

        $result_detail     = [];
        $total_jumlah      = 0;
        $total_realisasi   = 0;
        $total_persentase  = 0;

        foreach ($details as $detail) {

            $total_jumlah += $detail->jumlah;

            $arr_sub_sub = [];

            $child_sub = $this->query_paket_belanja_detail_sub(
                $detail->idpaket_belanja_detail_sub
            )->result();

            foreach ($child_sub as $sub_sub) {

                $total_jumlah += $sub_sub->jumlah;

                $tw_data = $this->generate_tw_realisasi([
                    'idpaket_belanja'            => $paket->idpaket_belanja,
                    'idpaket_belanja_detail'     => $akun->idpaket_belanja_detail,
                    'idpaket_belanja_detail_sub' => $sub_sub->idpaket_belanja_detail_sub,
                    'tahun_anggaran'             => $tahun_anggaran,
                    'jumlah'                     => $sub_sub->jumlah,
                    'is_sub_detail'              => true
                ]);

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

                $tw_data = $this->generate_tw_realisasi([
                    'idpaket_belanja'            => $paket->idpaket_belanja,
                    'idpaket_belanja_detail'     => $akun->idpaket_belanja_detail,
                    'idpaket_belanja_detail_sub' => $detail->idpaket_belanja_detail_sub,
                    'tahun_anggaran'             => $tahun_anggaran,
                    'jumlah'                     => $detail->jumlah,
                    'is_sub_detail'              => false
                ]);
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

        for ($tw = 1; $tw <= 4; $tw++) {

            $detail = $this->get_detail_data([
                'idpaket_belanja'            => $params['idpaket_belanja'],
                'idpaket_belanja_detail'     => $params['idpaket_belanja_detail'],
                'idpaket_belanja_detail_sub' => $params['idpaket_belanja_detail_sub'],
                'tahun_anggaran'             => $params['tahun_anggaran'],
                'tw'                         => $tw,
                'is_sub_detail'              => $params['is_sub_detail']
            ]);

            $nominal = !empty($detail)
                ? $detail['realisasi_rp_sampai']
                : 0;

            $persentase = $params['jumlah'] > 0
                ? ($nominal / $params['jumlah']) * 100
                : 0;

            $result['realisasi_sampai_tw'.$tw] = $nominal;
            $result['persen_realisasi_sampai_tw'.$tw] = $persentase;
        }

        return $result;
    }

    private function default_tw_data()
    {
        return [
            'realisasi_sampai_tw1' => 0,
            'realisasi_sampai_tw2' => 0,
            'realisasi_sampai_tw3' => 0,
            'realisasi_sampai_tw4' => 0,

            'persen_realisasi_sampai_tw1' => 0,
            'persen_realisasi_sampai_tw2' => 0,
            'persen_realisasi_sampai_tw3' => 0,
            'persen_realisasi_sampai_tw4' => 0
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    private function apply_status_date_filter($filter_bulan)
    {
        $bulan = date('Y-m', strtotime($filter_bulan));

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
                ->where(
                    'DATE_FORMAT('.$item['field'].', "%Y-%m") =',
                    $bulan
                )
            ->group_end();
        }

        $this->db->group_end();
    }

    private function apply_status_validation_filter()
    {
        $mapping = [
            [
                'status'       => 'PROSES PENGADAAN',
                'table_status' => 'purchase_plan.purchase_plan_status'
            ],
            [
                'status'       => 'KONTRAK PENGADAAN',
                'table_status' => 'contract.contract_status'
            ],
            [
                'status'       => 'MENUNGGU VERIFIKASI',
                'table_status' => 'budget_realization.realization_status'
            ],
            [
                'status'       => 'SUDAH DIVERIFIKASI',
                'table_status' => 'budget_realization.realization_status'
            ],
            [
                'status'       => 'DITOLAK VERIFIKATOR',
                'table_status' => 'budget_realization.realization_status'
            ],
            [
                'status'       => 'INPUT NPD',
                'table_status' => 'budget_realization.realization_status'
            ],
            [
                'status'       => 'MENUNGGU PEMBAYARAN',
                'table_status' => 'budget_realization.realization_status'
            ],
            [
                'status'       => 'SUDAH DIBAYAR BENDAHARA',
                'table_status' => 'budget_realization.realization_status'
            ]
        ];

        $this->db->group_start();

        foreach ($mapping as $index => $item) {

            if ($index == 0) {
                $this->db->group_start();
            } else {
                $this->db->or_group_start();
            }

            $this->db
                ->where(
                    'purchase_plan_detail.purchase_plan_detail_status',
                    $item['status']
                )
                ->where($item['table_status'].' !=', 'DRAFT')
            ->group_end();
        }

        $this->db->group_end();
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY MASTER
    |--------------------------------------------------------------------------
    */

    private function base_master_query(
        $table,
        $where = [],
        $order_by = '',
        $select = '*'
    ) {
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

    public function query_urusan_pemerintah($tahun_anggaran)
    {
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

    public function query_bidang_urusan($idurusan_pemerintah)
    {
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

    public function query_program($idbidang_urusan)
    {
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

    public function query_kegiatan($idprogram)
    {
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

    public function query_sub_kegiatan($idkegiatan)
    {
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

    public function query_paket_belanja($idsub_kegiatan)
    {
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

    public function query_akun_belanja($idpaket_belanja)
    {
        $this->db->from('paket_belanja_detail');

        $this->db->join(
            'akun_belanja',
            'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja'
        );

        $this->db->where('paket_belanja_detail.status', 1);
        $this->db->where(
            'paket_belanja_detail.idpaket_belanja',
            $idpaket_belanja
        );

        $this->db->order_by(
            'paket_belanja_detail.idpaket_belanja_detail ASC'
        );

        $this->db->select($this->master_select['akun_belanja']);

        return $this->db->get();
    }

    public function query_paket_belanja_detail(
        $idpaket_belanja_detail,
        $idpaket_belanja_detail_sub = null,
        $is_sub_detail = false
    ) {

        $query_akun_belanja = '';

        if (!$is_sub_detail) {

            $query_akun_belanja = ',
                akun_belanja.no_rekening_akunbelanja
            ';

            $this->db->where(
                'paket_belanja_detail_sub.idpaket_belanja_detail',
                $idpaket_belanja_detail
            );
        }

        if (!empty($idpaket_belanja_detail_sub)) {

            $this->db->where(
                'paket_belanja_detail_sub.idpaket_belanja_detail_sub',
                $idpaket_belanja_detail_sub
            );
        }

        $this->db->where('paket_belanja_detail_sub.status', 1);

        $this->db->join(
            'kategori',
            'kategori.idkategori = paket_belanja_detail_sub.idkategori',
            'left'
        );

        $this->db->join(
            'sub_kategori',
            'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori',
            'left'
        );

        $this->db->join(
            'kode_rekening',
            'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening',
            'left'
        );

        if (!$is_sub_detail) {

            $this->db->join(
                'paket_belanja_detail',
                'paket_belanja_detail.idpaket_belanja_detail = paket_belanja_detail_sub.idpaket_belanja_detail'
            );

            $this->db->join(
                'akun_belanja',
                'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja'
            );
        }

        $this->db->join(
            'satuan',
            'satuan.idsatuan = paket_belanja_detail_sub.idsatuan',
            'left'
        );

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

        return $this->db->get('paket_belanja_detail_sub');
    }

    public function query_paket_belanja_detail_sub(
        $idpaket_belanja_detail_sub,
        $join_kategori = false
    ) {

        $query_category = '';

        if ($join_kategori) {
            $query_category = ',
                "" as nama_kategori,
                "" as no_rekening_akunbelanja
            ';
        }

        $this->db->where(
            'paket_belanja_detail_sub.is_idpaket_belanja_detail_sub',
            $idpaket_belanja_detail_sub
        );

        $this->db->where(
            'paket_belanja_detail_sub.status',
            1
        );

        $this->db->join(
            'sub_kategori',
            'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori'
        );

        $this->db->join(
            'kode_rekening',
            'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening',
            'left'
        );

        $this->db->join(
            'satuan',
            'satuan.idsatuan = paket_belanja_detail_sub.idsatuan'
        );

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

        return $this->db->get('paket_belanja_detail_sub');
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE NAME
    |--------------------------------------------------------------------------
    */

    private function generate_nama_urusan($urusan)
    {
        return $urusan->no_rekening_urusan.' - '.$urusan->nama_urusan;
    }

    private function generate_nama_bidang($urusan, $bidang)
    {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.
            ' - '.
            $bidang->nama_bidang_urusan;
    }

    private function generate_nama_program($urusan, $bidang, $program)
    {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.'.'.
            $program->no_rekening_program.
            ' - '.
            $program->nama_program;
    }

    private function generate_nama_kegiatan(
        $urusan,
        $bidang,
        $program,
        $kegiatan
    ) {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.'.'.
            $program->no_rekening_program.'.'.
            $kegiatan->no_rekening_kegiatan.
            ' - '.
            $kegiatan->nama_kegiatan;
    }

    private function generate_nama_sub_kegiatan(
        $urusan,
        $bidang,
        $program,
        $kegiatan,
        $sub_kegiatan
    ) {
        return
            $urusan->no_rekening_urusan.'.'.
            $bidang->no_rekening_bidang_urusan.'.'.
            $program->no_rekening_program.'.'.
            $kegiatan->no_rekening_kegiatan.'.'.
            $sub_kegiatan->no_rekening_subkegiatan.
            ' - '.
            $sub_kegiatan->nama_subkegiatan;
    }

    private function get_detail_data($params)
    {
        $idpaket_belanja            = azarr($params, 'idpaket_belanja');
        $idpaket_belanja_detail     = azarr($params, 'idpaket_belanja_detail');
        $idpaket_belanja_detail_sub = azarr($params, 'idpaket_belanja_detail_sub');
        $tw                         = azarr($params, 'tw');
        $tahun_anggaran             = azarr($params, 'tahun_anggaran');
        $is_sub_detail              = azarr($params, 'is_sub_detail', false);

        $mulai_bulan = $this->get_start_month_by_tw($tw);

        $paket_belanja_detail = $this->query_paket_belanja_detail(
            $idpaket_belanja_detail,
            $idpaket_belanja_detail_sub,
            $is_sub_detail
        );

        $arr_detail = [];

        foreach ($paket_belanja_detail->result() as $detail) {

            $realisasi_sebelumnya = $this->get_previous_tw_realisasi([
                'tw'                             => $tw,
                'tahun_anggaran'                => $tahun_anggaran,
                'idpaket_belanja'               => $idpaket_belanja,
                'idsub_kategori'                => $detail->idsub_kategori,
                'idpaket_belanja_detail_sub'    => $detail->idpaket_belanja_detail_sub,
            ]);

            $current_realisasi = $this->calculate_tw_realisasi([
                'mulai_bulan'                   => $mulai_bulan,
                'tahun_anggaran'                => $tahun_anggaran,
                'idpaket_belanja'               => $idpaket_belanja,
                'idsub_kategori'                => $detail->idsub_kategori,
                'idpaket_belanja_detail_sub'    => $detail->idpaket_belanja_detail_sub,
            ]);

            $arr_detail = [
                'idpaket_belanja_detail_sub' => $detail->idpaket_belanja_detail_sub,
                'idkategori'                 => $detail->idkategori,
                'nama_kategori'              => $detail->nama_kategori,
                'idsub_kategori'             => $detail->idsub_kategori,
                'nama_subkategori'           => $detail->nama_sub_kategori,

                'realisasi_vol_sampai' =>
                    $current_realisasi['volume'] +
                    $realisasi_sebelumnya['realisasi_vol_sebelumnya'],

                'realisasi_rp_sampai' =>
                    $current_realisasi['total'] +
                    $realisasi_sebelumnya['realisasi_rp_sebelumnya'],
            ];

            /**
             * SUB DETAIL
             */
            $sub_details = $this->query_paket_belanja_detail_sub(
                $detail->idpaket_belanja_detail_sub
            );

            foreach ($sub_details->result() as $sub_detail) {

                $realisasi_sebelumnya = $this->get_previous_tw_realisasi([
                    'tw'                             => $tw,
                    'tahun_anggaran'                => $tahun_anggaran,
                    'idpaket_belanja'               => $idpaket_belanja,
                    'idsub_kategori'                => $sub_detail->idsub_kategori,
                    'idpaket_belanja_detail_sub'    => $sub_detail->idpaket_belanja_detail_sub,
                ]);

                $current_realisasi = $this->calculate_tw_realisasi([
                    'mulai_bulan'                   => $mulai_bulan,
                    'tahun_anggaran'                => $tahun_anggaran,
                    'idpaket_belanja'               => $idpaket_belanja,
                    'idsub_kategori'                => $sub_detail->idsub_kategori,
                    'idpaket_belanja_detail_sub'    => $sub_detail->idpaket_belanja_detail_sub,
                ]);

                $arr_detail = [
                    'idpaket_belanja_detail_sub' => $sub_detail->idpaket_belanja_detail_sub,
                    'idkategori'                 => '',
                    'nama_kategori'              => '',
                    'idsub_kategori'             => $sub_detail->idsub_kategori,
                    'nama_subkategori'           => $sub_detail->nama_sub_kategori,

                    'realisasi_vol_sampai' =>
                        $current_realisasi['volume'] +
                        $realisasi_sebelumnya['realisasi_vol_sebelumnya'],

                    'realisasi_rp_sampai' =>
                        $current_realisasi['total'] +
                        $realisasi_sebelumnya['realisasi_rp_sebelumnya'],
                ];
            }
        }

        return $arr_detail;
    }

    /**
     * HITUNG REALISASI PER TW
     */
    private function calculate_tw_realisasi($params)
    {
        $total_volume = 0;
        $total_rp     = 0;

        $mulai_bulan    = $params['mulai_bulan'];
        $tahun_anggaran = $params['tahun_anggaran'];

        for ($i = 0; $i < 3; $i++) {

            $bulan = sprintf('%02d', $mulai_bulan + $i);

            $result = $this->query_realisasi([
                'tahun_anggaran'             => $tahun_anggaran,
                'bulan'                      => $bulan,
                'idpaket_belanja'            => $params['idpaket_belanja'],
                'idsub_kategori'             => $params['idsub_kategori'],
                'idpaket_belanja_detail_sub' => $params['idpaket_belanja_detail_sub'],
                'mode'                       => 'bulanan',
            ]);

            if ($result->num_rows() > 0) {
                $total_volume += (float) $result->row()->volume;
                $total_rp     += (float) $result->row()->total;
            }
        }

        return [
            'volume' => $total_volume,
            'total'  => $total_rp,
        ];
    }

    /**
     * REALISASI TW SEBELUMNYA
     */
    private function get_previous_tw_realisasi($params)
    {
        $tw = (int) $params['tw'];

        if ($tw <= 1) {
            return [
                'realisasi_lk_sebelumnya'  => 0,
                'realisasi_pr_sebelumnya'  => 0,
                'realisasi_vol_sebelumnya' => 0,
                'realisasi_rp_sebelumnya'  => 0,
            ];
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

        return [
            'realisasi_lk_sebelumnya'  => (float) ($data->male ?? 0),
            'realisasi_pr_sebelumnya'  => (float) ($data->female ?? 0),
            'realisasi_vol_sebelumnya' => (float) ($data->volume ?? 0),
            'realisasi_rp_sebelumnya'  => (float) ($data->total ?? 0),
        ];
    }

    /**
     * QUERY REALISASI
     */
    private function query_realisasi($params)
    {
        $tahun_anggaran = $params['tahun_anggaran'];
        $bulan          = $params['bulan'];
        $mode           = $params['mode'];

        $filter_bulan = $tahun_anggaran . '-' . $bulan;
        $awal_tahun   = $tahun_anggaran . '-01';

        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan_detail.status', 1);
        $this->db->where('contract.status', 1);
        $this->db->where('contract_detail.status', 1);
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization_detail.status', 1);

        $this->db->where(
            'purchase_plan_detail.idpaket_belanja',
            $params['idpaket_belanja']
        );

        $this->db->where(
            'purchase_plan_detail.idpaket_belanja_detail_sub',
            $params['idpaket_belanja_detail_sub']
        );

        $this->db->where(
            'budget_realization_detail.idsub_kategori',
            $params['idsub_kategori']
        );

        $this->db->where(
            'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail'
        );

        /**
         * FILTER STATUS VALIDASI
         */
        $this->apply_status_validation_filter();

        /**
         * FILTER TANGGAL
         */
        if ($mode == 'bulanan') {
            $this->apply_status_date_filter($filter_bulan);
        } else {

            $this->apply_status_date_range_filter(
                $awal_tahun,
                $filter_bulan
            );
        }

        /**
         * JOIN
         */
        $this->db->join(
            'purchase_plan_detail',
            'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan'
        );

        $this->db->join(
            'contract_detail',
            'contract_detail.idpurchase_plan = purchase_plan.idpurchase_plan',
            'left'
        );

        $this->db->join(
            'contract',
            'contract.idcontract = contract_detail.idcontract',
            'left'
        );

        $this->db->join(
            'budget_realization_detail',
            'budget_realization_detail.idcontract_detail = contract_detail.idcontract_detail',
            'left'
        );

        $this->db->join(
            'budget_realization',
            'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization',
            'left'
        );

        $this->db->join(
            'verification',
            'verification.idbudget_realization = budget_realization.idbudget_realization',
            'left'
        );

        $this->db->join(
            'npd_detail',
            'npd_detail.idverification = verification.idverification',
            'left'
        );

        $this->db->join(
            'npd',
            'npd.idnpd = npd_detail.idnpd',
            'left'
        );

        $this->db->select('
            DATE_FORMAT(MAX(purchase_plan.purchase_plan_date), "%d-%m-%Y") as purchase_plan_date,
            MAX(budget_realization_detail.provider) as provider,
            SUM(budget_realization_detail.volume) as volume,
            SUM(budget_realization_detail.male) as male,
            SUM(budget_realization_detail.female) as female,
            SUM(budget_realization_detail.unit_price) as unit_price,
            SUM(ppn) as ppn,
            SUM(pph) as pph,
            SUM(budget_realization_detail.total_realization_detail) as total
        ');

        return $this->db->get('purchase_plan');
    }

    /**
     * FILTER RANGE TANGGAL
     */
    private function apply_status_date_range_filter($start, $end)
    {
        $start = date('Y-m', strtotime($start));
        $end   = date('Y-m', strtotime($end));

        $this->db->group_start()

            ->or_group_start()
                ->where('contract.contract_status', 'SUDAH DIBAYAR BENDAHARA')
                ->where('DATE_FORMAT(npd.confirm_payment_date, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(npd.confirm_payment_date, "%Y-%m") <=', $end)
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'MENUNGGU PEMBAYARAN')
                ->where('DATE_FORMAT(npd.npd_date_created, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(npd.npd_date_created, "%Y-%m") <=', $end)
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'INPUT NPD')
                ->where('DATE_FORMAT(npd.npd_date_created, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(npd.npd_date_created, "%Y-%m") <=', $end)
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'DITOLAK VERIFIKATOR')
                ->where('DATE_FORMAT(verification.confirm_verification_date, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(verification.confirm_verification_date, "%Y-%m") <=', $end)
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'SUDAH DIVERIFIKASI')
                ->where('DATE_FORMAT(verification.confirm_verification_date, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(verification.confirm_verification_date, "%Y-%m") <=', $end)
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'MENUNGGU VERIFIKASI')
                ->where('DATE_FORMAT(budget_realization.realization_date, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(budget_realization.realization_date, "%Y-%m") <=', $end)
            ->group_end()

            ->or_group_start()
                ->where('contract.contract_status', 'KONTRAK PENGADAAN')
                ->where('DATE_FORMAT(contract.contract_date, "%Y-%m") >=', $start)
                ->where('DATE_FORMAT(contract.contract_date, "%Y-%m") <=', $end)
            ->group_end()

        ->group_end();
    }

    /**
     * GET BULAN AWAL TW
     */
    private function get_start_month_by_tw($tw)
    {
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
    private function get_end_month_by_tw($tw)
    {
        $mapping = [
            1 => 3,
            2 => 6,
            3 => 9,
        ];

        return $mapping[$tw] ?? 3;
    }
}