<?php
    $config['menu'] = array(
        array(
            "name" => 'dashboard',
            'title' => azlang('Dashboard'),
            'icon' => 'tachometer-alt',
            'url' => 'home',
            'role' => array(
                array(
                    'role_name' => 'role_table',
                    'role_title' => 'Data'
                ),
            ),
            'submenu' => array(),
        ), 
        array(
            "name" => "master",
            "title" => "Master",
            "icon" => "database",
            "url" => "",
            "submenu" => array(
                array(
                    "name" => "master_urusan_pemerintah",
                    "title" => "Urusan Pemerintah",
                    "url" => "master_urusan_pemerintah",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_bidang_urusan",
                    "title" => "Bidang Urusan",
                    "url" => "master_bidang_urusan",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_program",
                    "title" => "Program",
                    "url" => "master_program",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_kegiatan",
                    "title" => "Kegiatan",
                    "url" => "master_kegiatan",
                    "submenu" => array()
                ),  
                array(
                    "name" => "master_sub_kegiatan",
                    "title" => "Sub Kegiatan",
                    "url" => "master_sub_kegiatan",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_paket_belanja",
                    "title" => "Paket Belanja",
                    "url" => "master_paket_belanja",
                    "submenu" => array(),
                    'role' => array(
                        array(
                            'role_name' => 'role_view_paket_belanja',
                            'role_title' => 'Hanya lihat data'
                        ),
                        array(
                            'role_name' => 'role_select_ppkom_pptk',
                            'role_title' => 'Pilih PPK / PP'
                        ),
                        array(
                            'role_name' => 'role_specification',
                            'role_title' => 'Bisa Isi Spesifikasi'
                        ),
                        array(
                            'role_name' => 'role_special_paket_belanja',
                            'role_title' => 'Bisa Pilih PPK / PP, Isi Spesifikasi'
                        ),
                    ),
                ),
                array(
                    "name" => "master_akun_belanja",
                    "title" => "Akun Belanja",
                    "url" => "master_akun_belanja",
                    "submenu" => array(),
                ),
                array(
                    "name" => "master_kategori",
                    "title" => "Kategori",
                    "url" => "master_kategori",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_sub_kategori",
                    "title" => "Sub Kategori",
                    "url" => "master_sub_kategori",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_kendaraan",
                    "title" => "Kendaraan",
                    "url" => "master_kendaraan",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_ruang",
                    "title" => "Ruang",
                    "url" => "master_ruang",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_satuan",
                    "title" => "Satuan",
                    "url" => "master_satuan",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_sumber_dana",
                    "title" => "Sumber Dana",
                    "url" => "master_sumber_dana",
                    "submenu" => array()
                ),
                array(
                    "name" => "master_kode_rekening",
                    "title" => "Kode Rekening",
                    "url" => "master_kode_rekening",
                    "submenu" => array()
                ),
            ),
        ),
        array(
            "name" => "purchase_plan",
            "title" => "Rencana Pengadaan",
            "icon" => "clipboard",
            "url" => "purchase_plan",
            'submenu' => array(),
            'role' => array(
                array(
                    'role_name' => 'role_view_purchase_plan',
                    'role_title' => 'Hanya lihat data'
                ),
                array(
                    'role_name' => 'role_bypass',
                    'role_title' => 'Bisa rencana pengadaan sebelum bulan RAK'
                ),
            ),
        ),
        array(
            "name" => "purchase_contract",
            "title" => "Kontrak Pengadaan",
            "icon" => "handshake",
            "url" => "purchase_contract",
            'submenu' => array(),
            'role' => array(
                array(
                    'role_name' => 'role_view_purchase_contract',
                    'role_title' => 'Hanya lihat data'
                ),
            ),
        ),
        array(
            "name" => "budget_realization",
            "title" => "Realisasi Anggaran",
            "icon" => "shopping-cart",
            "url" => "budget_realization",
            'submenu' => array(),
            'role' => array(
                array(
                    'role_name' => 'role_view_budget_realization',
                    'role_title' => 'Hanya lihat data'
                ),
            ),
        ),
        array(
            "name" => "document_verification",
            "title" => "Verifikasi Dokumen",
            "icon" => "user",
            "url" => "document_verification",
            'submenu' => array(),
            'role' => array(),
        ),
        array(
            "name" => "npd",
            "title" => "Nota Pencairan Dana",
            "icon" => "file",
            "url" => "npd",
            'submenu' => array(),
            'role' => array(
                array(
                    'role_name' => 'role_view_npd',
                    'role_title' => 'Hanya lihat data'
                ),
            ),
        ),
        array(
            "name" => "payment",
            "title" => "Pembayaran",
            "icon" => "credit-card",
            "url" => "payment",
            'submenu' => array(),
        ),
        array(
            "name" => "evaluasi_anggaran",
            "title" => "Evaluasi Anggaran",
            "icon" => "check-square",
            "url" => "evaluasi_anggaran",
            'submenu' => array(),
        ),
        array(
            "name" => "npd_panjer",
            "title" => "NPD Panjar",
            "icon" => "file",
            "url" => "npd_panjer",
            'submenu' => array(),
            'role' => array(
                array(
                    'role_name' => 'role_view_npd_panjer',
                    'role_title' => 'Hanya lihat data'
                ),
            ),
        ),
        array(
            "name" => "report",
            "title" => "Laporan",
            "url" => "report",
            "icon" => "file",
            "submenu" => array(),
            'role' => array(
                array(
                    'role_name' => 'role_report_realisasi_anggaran',
                    'role_title' => 'Laporan Realisasi Anggaran',
                ),
                array(
                    'role_name' => 'role_report_sisa_realisasi_anggaran',
                    'role_title' => 'Laporan Sisa Realisasi Anggaran',
                ),
                array(
                    'role_name' => 'role_report_evaluasi_anggaran',
                    'role_title' => 'Laporan Evaluasi Anggaran',
                ),
            ),
        ),
        array(
            "name" => "information",
            "title" => "Informasi",
            "icon" => "bullhorn",
            "url" => "information",
            'submenu' => array(),
        ),
        array(
            "name" => "manual_book",
            "title" => "Buku Petunjuk",
            "icon" => "file-pdf",
            "url" => "manual_book",
            'submenu' => array(),
        ),
        array(
            "name" => "user",
            "title" => azlang("User"),
            "icon" => "user",
            "url" => "",
            "submenu" => array(
                array(
                    "name" => "user_user",
                    "title" => azlang("Tambah/Edit Pengguna"),
                    "url" => "user",
                    "submenu" => array()
                ),
                array(
                    "name" => "user_user_role",
                    "title" => azlang("Hak Akses"),
                    "url" => "user_role",
                    "submenu" => array()
                ),
            )
        ),
    );

