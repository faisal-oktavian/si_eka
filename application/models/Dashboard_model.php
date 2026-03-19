<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Ambil data dashboard untuk API.
     *
     * Struktur ini dibuat agar dapat langsung dipakai untuk pembuatan grafik di v_home.
     *
     * @param int|null $tahun Tahun anggaran (misal 2026). Jika null, pakai tahun sekarang.
     * @return array
     */
    public function get_dashboard_data($tahun = null) {
        $tahun = $tahun ?: date('Y');
        $tahun = intval($tahun);

        $grafik_potensi = $this->grafik_potensi_sisa_anggaran($tahun);
        $grafik_realisasi = $this->grafik_realisasi_anggaran($tahun, $grafik_potensi['total_anggaran_tahun_ini']);
        $grafik_sumber_dana = $this->grafik_sumber_dana($tahun);

        $target_per_bulan = $this->get_target_per_bulan($tahun);
        $realisasi_per_bulan = $this->get_realisasi_per_bulan($tahun, false);
        $capaian_target_realisasi = $this->grafik_capaian_target_realisasi($tahun, $grafik_potensi['total_anggaran_tahun_ini']);

        return [
            'tahun_ini' => $tahun,
            'total_anggaran_tahun_ini' => floatval($grafik_potensi['total_anggaran_tahun_ini']),
            'realisasi_anggaran_tahun_ini' => floatval($grafik_realisasi['sudah_dibayar']),

            // Grafik realisasi anggaran (pie chart)
            'sudah_dibayar' => floatval($grafik_realisasi['sudah_dibayar']),
            'menunggu_pembayaran' => floatval($grafik_realisasi['menunggu_pembayaran']),
            'npd' => floatval($grafik_realisasi['npd']),
            'sudah_diverifikasi' => floatval($grafik_realisasi['sudah_diverifikasi']),
            'menunggu_verifikasi' => floatval($grafik_realisasi['menunggu_verifikasi']),
            'kontrak_pengadaan' => floatval($grafik_realisasi['kontrak_pengadaan']),
            'proses_pengadaan' => floatval($grafik_realisasi['proses_pengadaan']),
            'belum_direalisasi' => floatval($grafik_realisasi['belum_direalisasi']),

            // Grafik sumber dana
            'dbh' => floatval($grafik_sumber_dana['dbh']),
            'blud' => floatval($grafik_sumber_dana['blud']),
            'target_dbh' => floatval($grafik_sumber_dana['target_dbh']),
            'target_blud' => floatval($grafik_sumber_dana['target_blud']),

            // Grafik per bulan
            'target_per_bulan' => $target_per_bulan,
            'realisasi_per_bulan' => $realisasi_per_bulan,
            'capaian_target_per_bulan' => $capaian_target_realisasi['arr_TargetPerBulan'],
            'capaian_realisasi_per_bulan' => $capaian_target_realisasi['arr_RealisasiPerBulan'],
        ];
    }

    /**
     * Target per bulan (total anggaran paket belanja) berdasarkan data di Home.php.
     *
     * @param int $tahun
     * @return float[]
     */
    public function get_target_per_bulan($tahun) {
        $out = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $this->db->reset_query();

            // Kondisi dasar yang sama seperti di Home::index
            $this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $tahun);
            $this->db->where('pb.status_paket_belanja = "OK" ');
            $this->db->where('pb.is_active', 1);
            $this->db->where('pb.status', 1);
            $this->db->where('pbd.status', 1);
            $this->db->where('pbds.status', 1);
            $this->db->where('pbds.volume IS NOT NULL', null, false);
            $this->db->where('pbds.idsatuan IS NOT NULL', null, false);
            $this->db->where('pbds.harga_satuan IS NOT NULL', null, false);
            $this->db->where('pbds.jumlah IS NOT NULL', null, false);

            $this->db->join('paket_belanja_detail pbd', 'pbd.idpaket_belanja = pb.idpaket_belanja');
            $this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = pbd.idakun_belanja');
            $this->db->join('paket_belanja_detail_sub pbds', 
                'pbds.idpaket_belanja_detail = pbd.idpaket_belanja_detail 
                    OR pbds.is_idpaket_belanja_detail_sub IN (
                        SELECT sub.idpaket_belanja_detail_sub 
                        FROM paket_belanja_detail_sub sub 
                        WHERE sub.idpaket_belanja_detail = pbd.idpaket_belanja_detail
                    )',
                'left', false
            );
            $this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = pb.idsub_kegiatan');
            $this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
            $this->db->join('program', 'program.idprogram = kegiatan.idprogram');
            $this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
            $this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');

            // Pilih bulan yang sesuai
            switch ($bulan) {
                case 1:  $this->db->select('sum(rak_jumlah_januari) as nilai_anggaran'); break;
                case 2:  $this->db->select('sum(rak_jumlah_februari) as nilai_anggaran'); break;
                case 3:  $this->db->select('sum(rak_jumlah_maret) as nilai_anggaran'); break;
                case 4:  $this->db->select('sum(rak_jumlah_april) as nilai_anggaran'); break;
                case 5:  $this->db->select('sum(rak_jumlah_mei) as nilai_anggaran'); break;
                case 6:  $this->db->select('sum(rak_jumlah_juni) as nilai_anggaran'); break;
                case 7:  $this->db->select('sum(rak_jumlah_juli) as nilai_anggaran'); break;
                case 8:  $this->db->select('sum(rak_jumlah_agustus) as nilai_anggaran'); break;
                case 9:  $this->db->select('sum(rak_jumlah_september) as nilai_anggaran'); break;
                case 10: $this->db->select('sum(rak_jumlah_oktober) as nilai_anggaran'); break;
                case 11: $this->db->select('sum(rak_jumlah_november) as nilai_anggaran'); break;
                case 12: $this->db->select('sum(rak_jumlah_desember) as nilai_anggaran'); break;
            }

            $pb = $this->db->get('paket_belanja pb');
            $nilai = 0;
            if ($pb->num_rows() > 0) {
                $nilai = floatval($pb->row()->nilai_anggaran);
            }

            $out[] = $nilai;
        }

        return $out;
    }

    /**
     * Realisasi per bulan (opsional kumulatif) berdasarkan purchase_plan.
     *
     * @param int $tahun
     * @param bool $cumulative Jika true, hasil adalah kumulatif sejak awal tahun.
     * @return float[]
     */
    public function get_realisasi_per_bulan($tahun, $cumulative = true) {
        $out = [];
        $running = 0;

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // $this->db->reset_query();
            // $this->db->where('purchase_plan.status', 1);
            // $this->db->where('purchase_plan_status != "DRAFT" ');
            // $this->db->where('YEAR(purchase_plan_date)', $tahun);
            // $this->db->where('MONTH(purchase_plan_date)', $bulan);
            // $this->db->group_by('YEAR(purchase_plan_date), MONTH(purchase_plan_date)');
            // $this->db->select('SUM(total_budget) AS total');
            // $query = $this->db->get('purchase_plan');

            $this->db->reset_query();
            $this->db->where('npd.status', 1);
            $this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
            $this->db->where('YEAR(npd.confirm_payment_date)', $tahun);
            $this->db->where('MONTH(npd.confirm_payment_date)', $bulan);
            $this->db->group_by('YEAR(npd.confirm_payment_date), MONTH(npd.confirm_payment_date)');
            $this->db->select('sum(total_pay) as total');
            $query = $this->db->get('npd');

            $total = 0;
            if ($query->num_rows() > 0) {
                $total = floatval($query->row()->total);
            }
            
            if ($cumulative) {
                $running += $total;
                $out[] = $running;
            } else {
                $out[] = $total;
            }
        }

        return $out;
    }

    /**
     * Hitung beberapa kategori realisasi anggaran (Pie chart di v_home)
     *
     * @param int $tahun_ini
     * @param float $total_anggaran
     * @return array
     */
    public function grafik_realisasi_anggaran($tahun_ini, $total_anggaran = 0) {
        $sudah_dibayar = 0;
        $menunggu_pembayaran = 0;
        $npd = 0;
        $sudah_diverifikasi = 0;
        $menunggu_verifikasi = 0;
        $kontrak_pengadaan = 0;
        $proses_pengadaan = 0;
        $belum_direalisasi = 0;

        // Sudah Dibayar
        $this->db->reset_query();
        $this->db->where('npd.status', 1);
        $this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
        $this->db->where('YEAR(npd.confirm_payment_date) = "'.$tahun_ini.'" ');
        $this->db->select('sum(total_pay) as total_yang_sudah_dibayar');
        $npd_pay = $this->db->get('npd');
        if ($npd_pay->num_rows() > 0) {
            $sudah_dibayar = $npd_pay->row()->total_yang_sudah_dibayar;
        }

        // Menunggu Pembayaran
        $this->db->reset_query();
        $this->db->where('npd.status', 1);
        $this->db->where('npd.npd_status = "MENUNGGU PEMBAYARAN" ');
        $this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');
        $this->db->select('sum(total_anggaran) as total_yang_menunggu_pembayaran');
        $npd_before_pay = $this->db->get('npd');
        if ($npd_before_pay->num_rows() > 0) {
            $menunggu_pembayaran = $npd_before_pay->row()->total_yang_menunggu_pembayaran;
        }

        // NPD
        $this->db->reset_query();
        $this->db->where('npd.status', 1);
        $this->db->where('npd.npd_status = "INPUT NPD" ');
        $this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');
        $this->db->select('sum(total_anggaran) as total_input_npd');
        $npd_input = $this->db->get('npd');
        if ($npd_input->num_rows() > 0) {
            $npd = $npd_input->row()->total_input_npd;
        }

        // Sudah Diverifikasi
        $this->db->reset_query();
        $this->db->where('verification.status', 1);
        $this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
        $this->db->where('YEAR(verification.confirm_verification_date) = "'.$tahun_ini.'" ');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
        $this->db->select('sum(total_realization) as total_verif');
        $verification = $this->db->get('verification');
        if ($verification->num_rows() > 0) {
            $sudah_diverifikasi = $verification->row()->total_verif;
        }

        // Menunggu Verifikasi
        $this->db->reset_query();
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization.realization_status = "MENUNGGU VERIFIKASI" ');
        $this->db->where('YEAR(budget_realization.realization_date) = "'.$tahun_ini.'" ');
        $this->db->select('sum(total_realization) as total_menunggu');
        $budget_realization = $this->db->get('budget_realization');
        if ($budget_realization->num_rows() > 0) {
            $menunggu_verifikasi = $budget_realization->row()->total_menunggu;
        }

        // Kontrak Pengadaan
        $this->db->reset_query();
        $this->db->where('contract.status', 1);
        $this->db->where('contract.contract_status = "KONTRAK PENGADAAN" ');
        $this->db->where('YEAR(contract.contract_date) = "'.$tahun_ini.'" ');
        $this->db->join('contract_detail', 'contract_detail.idcontract = contract.idcontract');
        $this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
        $this->db->select('sum(total_budget) as total_contract');
        $contract = $this->db->get('contract');
        if ($contract->num_rows() > 0) {
            $kontrak_pengadaan = $contract->row()->total_contract;
        }

        // Proses Pengadaan
        $this->db->reset_query();
        $this->db->where('purchase_plan.status', 1);
        $this->db->where('purchase_plan.purchase_plan_status = "PROSES PENGADAAN" ');
        $this->db->where('YEAR(purchase_plan.purchase_plan_date) = "'.$tahun_ini.'" ');
        $this->db->select('sum(total_budget) as total_pengadaan');
        $purchase_plan = $this->db->get('purchase_plan');
        if ($purchase_plan->num_rows() > 0) {
            $proses_pengadaan = $purchase_plan->row()->total_pengadaan;
        }

        // Belum Direalisasi
        $belum_direalisasi = $total_anggaran - (floatval($sudah_dibayar)
            + floatval($menunggu_pembayaran)
            + floatval($npd)
            + floatval($sudah_diverifikasi)
            + floatval($menunggu_verifikasi)
            + floatval($kontrak_pengadaan)
            + floatval($proses_pengadaan)
        );

        return [
            'sudah_dibayar' => floatval($sudah_dibayar),
            'menunggu_pembayaran' => floatval($menunggu_pembayaran),
            'npd' => floatval($npd),
            'sudah_diverifikasi' => floatval($sudah_diverifikasi),
            'menunggu_verifikasi' => floatval($menunggu_verifikasi),
            'kontrak_pengadaan' => floatval($kontrak_pengadaan),
            'proses_pengadaan' => floatval($proses_pengadaan),
            'belum_direalisasi' => floatval($belum_direalisasi),
        ];
    }

    /**
     * Total anggaran tahun ini.
     *
     * @param int $tahun_ini
     * @return array
     */
    public function grafik_potensi_sisa_anggaran($tahun_ini) {
        $total_anggaran = 0;

        $this->db->reset_query();
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
        $pb = $this->db->get('paket_belanja');
        if ($pb->num_rows() > 0) {
            $total_anggaran = $pb->row()->nilai_anggaran;
        }

        return [
            'total_anggaran_tahun_ini' => floatval($total_anggaran),
        ];
    }

    /**
     * Realisasi per sumber dana (grafik DBH & BLUD).
     *
     * @param int $tahun_ini
     * @return array
     */
    public function grafik_sumber_dana($tahun_ini) {
        $dbh = 0;
        $blud = 0;
        $target_dbh = 0;
        $target_blud = 0;

        $this->db->reset_query();
        $this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
        $this->db->where('npd.status', 1);
        $this->db->where('npd_detail.status', 1);
        $this->db->where('verification.status', 1);
        $this->db->where('verification.verification_status != "DRAFT" ');
        $this->db->where('budget_realization.status', 1);
        $this->db->where('budget_realization.realization_status != "DRAFT" ');
        $this->db->where('budget_realization_detail.status', 1);
        $this->db->where('sub_kategori.status', 1);
        $this->db->where('sumber_dana.status', 1);
        $this->db->where('YEAR(npd.npd_date_created) = "'.$tahun_ini.'" ');

        $this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
        $this->db->join('verification', 'verification.idverification = npd_detail.idverification');
        $this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
        $this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
        $this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = budget_realization_detail.idsub_kategori');
        $this->db->join('sumber_dana', 'sumber_dana.idsumber_dana = sub_kategori.idsumber_dana');

        $this->db->group_by('nama_sumber_dana, idsumber_dana');
        $this->db->select('SUM(budget_realization_detail.total_realization_detail) AS total_sumber_dana, sumber_dana.nama_sumber_dana, sumber_dana.idsumber_dana');
        $npd = $this->db->get('npd');

        foreach ($npd->result() as $value) {
            $idsumber_dana = $value->idsumber_dana;
            $sumber_dana = $value->nama_sumber_dana;
            $total_sumber_dana = $value->total_sumber_dana;

            // ambil data target per sumber dana
            $this->db->reset_query();
            $this->db->where('pb.status', 1);
            $this->db->where('pb.status_paket_belanja = "OK" ');
            $this->db->where('pbd.status', 1);
            $this->db->where('sk.idsumber_dana = "'.$idsumber_dana.'" ');
            $this->db->join('paket_belanja_detail pbd', 'paket_belanja_detail pbd ON pb.idpaket_belanja = pbd.idpaket_belanja');
            $this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
            $this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');
            $this->db->join('sub_kategori sk', 'sk.idsub_kategori = COALESCE(pbds_child.idsub_kategori, pbds_parent.idsub_kategori)');
            $this->db->join('sumber_dana', 'sumber_dana.idsumber_dana = sk.idsumber_dana');

            $this->db->select('sumber_dana.nama_sumber_dana, SUM(COALESCE(pbds_child.jumlah, pbds_parent.jumlah)) AS total_target');
            $pb = $this->db->get('paket_belanja pb');

            if ($sumber_dana == "DBH Cukai Hasil Tembakau (CHT)") {
                $dbh = $total_sumber_dana;
                if ($pb->num_rows() > 0) {
                    $target_dbh = $pb->row()->total_target;
                }
            } else if ($sumber_dana == "Pendapatan dari BLUD") {
                $blud = $total_sumber_dana;
                if ($pb->num_rows() > 0) {
                    $target_blud = $pb->row()->total_target;
                }
            }
        }

        return [
            'dbh' => floatval($dbh),
            'blud' => floatval($blud),
            'target_dbh' => floatval($target_dbh),
            'target_blud' => floatval($target_blud),
        ];
    }

    /**
     * Persentase capaian target & realisasi per bulan.
     *
     * @param int $tahun_ini
     * @param float $total_anggaran
     * @return array
     */
    public function grafik_capaian_target_realisasi($tahun_ini, $total_anggaran) {
        // ambil target per bulan
        $target_per_bulan = $this->get_target_per_bulan($tahun_ini);
        $realisasi_bulanan = $this->get_realisasi_per_bulan($tahun_ini, false);

        $arr_TargetPerBulan = [];
        $arr_RealisasiPerBulan = [];

        $cumulative_target = 0;
        $cumulative_realisasi = 0;

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $cumulative_target += isset($target_per_bulan[$bulan - 1]) ? $target_per_bulan[$bulan - 1] : 0;
            $cumulative_realisasi += isset($realisasi_bulanan[$bulan - 1]) ? $realisasi_bulanan[$bulan - 1] : 0;

            $arr_TargetPerBulan[] = $total_anggaran 
                ? round(($cumulative_target / $total_anggaran) * 100, 2) 
                : 0;

            $arr_RealisasiPerBulan[] = $total_anggaran 
                ? round(($cumulative_realisasi / $total_anggaran) * 100, 2) 
                : 0;
        }
        
        // echo "<pre>";
        // print_r($arr_TargetPerBulan);
        // echo "<br>";
        // print_r($arr_RealisasiPerBulan);
        // die;

        return [
            'arr_TargetPerBulan' => $arr_TargetPerBulan,
            'arr_RealisasiPerBulan' => $arr_RealisasiPerBulan,
        ];
    }
}
