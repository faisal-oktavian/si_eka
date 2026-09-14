<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_papbd_akun_belanja extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_papbd_akun_belanja');
        $this->controller = 'report_papbd_akun_belanja';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'No. Rekening', 'Nama Akun Belanja', 'APBD', 'PAPBD', 'Selisih'));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
        $crud->set_btn_add(false);

		$tahun = $azapp->add_datetime();
		$tahun->set_id('vf_tahun');
		$tahun->set_name('vf_tahun');
		$tahun->set_value(Date('Y'));
		$tahun->set_format('YYYY');
		$data['tahun'] = $tahun->render();

		$crud->add_aodata('vf_tahun', 'vf_tahun');

		$filter = $this->load->view('report_papbd_akun_belanja/vf_report_papbd_akun_belanja', $data, true);
		$crud->set_top_filter($filter);

		$js = az_add_js('report_papbd_akun_belanja/vjs_report_papbd_akun_belanja');
		$azapp->add_js($js);
        
        // $total_saldo_awal = 0;
		
		// $tahun = Date('m-Y'); // default filter
		// $total_saldo_awal = $this->get_saldo_awal($tahun);

        // $crud->set_btn_top_custom("
		// 	<table>
		// 		<tr>
		// 			<td><button class='btn btn-success btn-pdf' type='button' id='btn_export'><i class='fa fa-file-pdf'></i> Export PDF</button></td>
		// 			<td style='padding-left:30px; font-weight:bold;'>Saldo Awal : <span id='all_total_debt'>".az_thousand_separator_decimal($total_saldo_awal)."</span></td>
		// 		</tr>
		// 	</table>");

		// $crud->set_callback_edit('
		// 	check_copy();
        // ');
		
		$crud = $crud->render();
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Laporan Perubahan APBD per Akun Belanja');
		$data_header['breadcrumb'] = array('report', 'role_report_papbd_akun_belanja');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun = $this->input->get('vf_tahun');

		$this->db->select("
				akun_belanja.idakun_belanja as id,
				akun_belanja.no_rekening_akunbelanja,
				akun_belanja.nama_akun_belanja,
				(
					CASE
						WHEN EXISTS (
							/* ====================================================================
								CHECK APAKAH ADA DATA APBD ATAU TIDAK DI TABEL PAKET BELANJA APBD
							======================================================================= */
							" . $this->query_check_apbd("akun_belanja.idakun_belanja", $tahun) . "
						)
						THEN (
							/* =========================================
								BACA DATA DI TABEL PAKET BELANJA APBD
							============================================ */
							" . $this->query_total_apbd("akun_belanja.idakun_belanja", $tahun) . "
						)
						ELSE (
							/* ===================================
								BACA DATA DI TABEL PAKET BELANJA
							====================================== */
							" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
						)
					END
				) AS apbd,

				(
					CASE
						WHEN EXISTS (
							/* ====================================================================
								CHECK APAKAH ADA DATA APBD ATAU TIDAK DI TABEL PAKET BELANJA APBD
							======================================================================= */
							" . $this->query_check_apbd("akun_belanja.idakun_belanja", $tahun) . "
						)
						THEN (
							/* ===================================
								BACA DATA DI TABEL PAKET BELANJA
							====================================== */
							" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
						)
						ELSE (
							0
						)
					END
				) AS papbd,

				(
					(
						CASE
							WHEN EXISTS (
								" . $this->query_check_apbd("akun_belanja.idakun_belanja", $tahun) . "
							)
							THEN (
								" . $this->query_total_apbd("akun_belanja.idakun_belanja", $tahun) . "
							)
							ELSE (
								" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
							)
						END
					)
					-
					(
						" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
					)
				) AS selisih
			", FALSE);


		$this->db->from("akun_belanja");

		$this->db->where("akun_belanja.status", 1);
		$this->db->where("akun_belanja.is_active", 1);
		$this->db->order_by("akun_belanja.idakun_belanja ASC");

		$anggaran = $this->db->get();
		$last_query = $this->db->last_query();
		// echo "<pre>"; print_r($this->db->last_query()); die();

		$crud->set_manual_query($last_query);

        // $crud->set_select($query1);
        // $crud->set_select_union($query2);

		$crud->set_select_table('id, no_rekening_akunbelanja, nama_akun_belanja, apbd, papbd, selisih');
        // $crud->set_sorting('transaction_date, transaction_code, nama_paket_belanja, total_realisasi, transaction_status');
        // $crud->set_filter('txt_proof_date, proof_number, kode_rekening, alat_bayar, uraian');

        $crud->set_select_align(', , right, right, right');
		$crud->set_id($this->controller);

		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		if ($key == 'apbd') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
        if ($key == 'papbd') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'selisih') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		return $value;
	}

	// public function export_pdf($tahun) {
	// 	$this->load->library('pdf');

	// 	$saldo_awal = $this->get_saldo_awal($tahun);
	// 	$sql_pad_mutasi = $this->get_pad_mutasi_kas($tahun);
	// 	$sql_pad_sts    = $this->get_pad_sts($tahun);

	// 	$this->db->select("
	// 		id,
	// 		txt_proof_date,
	// 		proof_date,
	// 		proof_number,
	// 		kode_rekening,
	// 		alat_bayar,
	// 		uraian,
	// 		proof_for,
	// 		penerimaan,
	// 		pengeluaran,
	// 		(
	// 			{$saldo_awal} +
	// 			SUM(penerimaan-pengeluaran) OVER(
	// 				ORDER BY proof_date ASC, id ASC
	// 				ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
	// 			)
	// 		) AS saldo
	// 	", FALSE);

	// 	$this->db->from("(
	// 		{$sql_pad_mutasi}
	// 		UNION ALL
	// 		{$sql_pad_sts}
	// 	) AS new_query", NULL, FALSE);

	// 	$bpn1 = $this->db->get();
	// 	// echo "<pre>"; print_r($this->db->last_query()); die();

	// 	$month = $this->reformat_month($tahun);
		
	// 	$data = array(
	// 		'bpn1' => $bpn1->result(),
	// 		'month' => $month,
	// 		'saldo_awal' => $saldo_awal
	// 	);


	// 	$html = $this->load->view('report_bpn1/v_report_bpn1_pdf', $data, TRUE);

	// 	$this->pdf->loadHtml($html);

	// 	$this->pdf->setPaper('A4', 'landscape');

	// 	$this->pdf->render();

	// 	// Footer nomor halaman
	// 	$canvas = $this->pdf->getCanvas();

	// 	$font = "Helvetica";

	// 	$canvas->page_text(
	// 		720,
	// 		575,
	// 		"Halaman {PAGE_NUM} / {PAGE_COUNT}",
	// 		$font,
	// 		8,
	// 		array(0,0,0)
	// 	);	

	// 	$this->pdf->stream(
	// 		'Laporan_BPn1.pdf',
	// 		[
	// 			'Attachment' => false
	// 		]
	// 	);
	// }

	function get_data_apbd_murni($tahun) {
		 $query1 = "
				SELECT
					akun_belanja.idakun_belanja,
					akun_belanja.no_rekening_akunbelanja,
					akun_belanja.nama_akun_belanja,

					(
						CASE
							WHEN EXISTS (
								/* ====================================================================
									CHECK APAKAH ADA DATA APBD ATAU TIDAK DI TABEL PAKET BELANJA APBD
								======================================================================= */
								" . $this->query_check_apbd("akun_belanja.idakun_belanja", $tahun) . "
							)
							THEN (
								/* =========================================
									BACA DATA DI TABEL PAKET BELANJA APBD
								============================================ */
								" . $this->query_total_apbd("akun_belanja.idakun_belanja", $tahun) . "
							)
							ELSE (
								/* ===================================
									BACA DATA DI TABEL PAKET BELANJA
								====================================== */
								" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
							)
						END
					) AS apbd,

					(
						/* ===================================
							BACA DATA DI TABEL PAKET BELANJA
						====================================== */
						" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
					) AS papbd,

					(
						(
							CASE
								WHEN EXISTS (
									" . $this->query_check_apbd("akun_belanja.idakun_belanja", $tahun) . "
								)
								THEN (
									" . $this->query_total_apbd("akun_belanja.idakun_belanja", $tahun) . "
								)
								ELSE (
									" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
								)
							END
						)
						-
						(
							" . $this->query_total_murni("akun_belanja.idakun_belanja", $tahun) . "
						)
					) AS selisih

				FROM akun_belanja

				WHERE akun_belanja.status = 1
				AND akun_belanja.is_active = 1

				ORDER BY akun_belanja.idakun_belanja ASC
			";
		// echo "<pre>"; print_r($query1);die;
		// echo "<pre>"; print_r($this->db->last_query());die;

		return $query1;
	}

	private function query_check_apbd($idakun_belanja, $tahun) {
		$query = "
				SELECT 1
				FROM paket_belanja_apbd_detail pbad_exists
				JOIN paket_belanja_apbd pba_exists
					ON pba_exists.idpaket_belanja_apbd =
					pbad_exists.idpaket_belanja_apbd

				JOIN sub_kegiatan sk_exists
					ON sk_exists.idsub_kegiatan =
					pba_exists.idsub_kegiatan

				JOIN kegiatan k_exists
					ON k_exists.idkegiatan =
					sk_exists.idkegiatan

				JOIN program pr_exists
					ON pr_exists.idprogram =
					k_exists.idprogram

				JOIN bidang_urusan bu_exists
					ON bu_exists.idbidang_urusan =
					pr_exists.idbidang_urusan

				JOIN urusan_pemerintah up_exists
					ON up_exists.idurusan_pemerintah =
					bu_exists.idurusan_pemerintah

				WHERE pbad_exists.status = 1
				AND pba_exists.status = 1
				AND pba_exists.status_paket_belanja = 'OK'
				AND pba_exists.jenis = 'APBD'

				AND pbad_exists.idakun_belanja =
					akun_belanja.idakun_belanja

				AND up_exists.tahun_anggaran_urusan = '$tahun'
		";

		return $query;
	}

	private function query_total_apbd($idakun_belanja, $tahun) {
		$query = "
				SELECT
					COALESCE(SUM(data_apbd.jumlah), 0)

				FROM
				(
					/* ====================================================
					PARENT APBD
					==================================================== */
					SELECT
						parent_sub.jumlah

					FROM paket_belanja_apbd_detail pbad
					JOIN paket_belanja_apbd pba
						ON pba.idpaket_belanja_apbd =
						pbad.idpaket_belanja_apbd

					JOIN akun_belanja ab_apbd
						ON ab_apbd.idakun_belanja =
						pbad.idakun_belanja

					JOIN sub_kegiatan sk_apbd
						ON sk_apbd.idsub_kegiatan =
						pba.idsub_kegiatan

					JOIN kegiatan k_apbd
						ON k_apbd.idkegiatan =
						sk_apbd.idkegiatan

					JOIN program pr_apbd
						ON pr_apbd.idprogram =
						k_apbd.idprogram

					JOIN bidang_urusan bu_apbd
						ON bu_apbd.idbidang_urusan =
						pr_apbd.idbidang_urusan

					JOIN urusan_pemerintah up_apbd
						ON up_apbd.idurusan_pemerintah =
						bu_apbd.idurusan_pemerintah

					JOIN paket_belanja_apbd_detail_sub parent_sub
						ON parent_sub.idpaket_belanja_apbd_detail =
						pbad.idpaket_belanja_apbd_detail
					AND parent_sub.status = 1

					WHERE pbad.status = 1
					AND pba.status = 1
					AND pba.status_paket_belanja = 'OK'
					AND pba.jenis = 'APBD'

					AND ab_apbd.status = 1
					AND ab_apbd.is_active = 1

					AND pbad.idakun_belanja =
						akun_belanja.idakun_belanja

					AND up_apbd.tahun_anggaran_urusan = '$tahun'


					UNION ALL


					/* ====================================================
					CHILD APBD
					==================================================== */
					SELECT
						child_sub.jumlah

					FROM paket_belanja_apbd_detail pbad
					JOIN paket_belanja_apbd pba
						ON pba.idpaket_belanja_apbd =
						pbad.idpaket_belanja_apbd

					JOIN akun_belanja ab_apbd
						ON ab_apbd.idakun_belanja =
						pbad.idakun_belanja

					JOIN sub_kegiatan sk_apbd
						ON sk_apbd.idsub_kegiatan =
						pba.idsub_kegiatan

					JOIN kegiatan k_apbd
						ON k_apbd.idkegiatan =
						sk_apbd.idkegiatan

					JOIN program pr_apbd
						ON pr_apbd.idprogram =
						k_apbd.idprogram

					JOIN bidang_urusan bu_apbd
						ON bu_apbd.idbidang_urusan =
						pr_apbd.idbidang_urusan

					JOIN urusan_pemerintah up_apbd
						ON up_apbd.idurusan_pemerintah =
						bu_apbd.idurusan_pemerintah

					JOIN paket_belanja_apbd_detail_sub parent_sub
						ON parent_sub.idpaket_belanja_apbd_detail =
						pbad.idpaket_belanja_apbd_detail
					AND parent_sub.status = 1

					JOIN paket_belanja_apbd_detail_sub child_sub
						ON child_sub.is_idpaket_belanja_apbd_detail_sub =
						parent_sub.idpaket_belanja_apbd_detail_sub
					AND child_sub.status = 1

					WHERE pbad.status = 1
					AND pba.status = 1
					AND pba.status_paket_belanja = 'OK'
					AND pba.jenis = 'APBD'

					AND ab_apbd.status = 1
					AND ab_apbd.is_active = 1

					AND pbad.idakun_belanja =
						akun_belanja.idakun_belanja

					AND up_apbd.tahun_anggaran_urusan = '$tahun'
				) AS data_apbd
			";

		return $query;
	}

	private function query_total_murni($idakun_belanja, $tahun) {
		$query = "
				SELECT
					COALESCE(SUM(data_murni.jumlah), 0)

				FROM
				(
					/* ====================================================
					PARENT NORMAL
					==================================================== */
					SELECT
						parent_sub.jumlah

					FROM paket_belanja_detail pbd
					JOIN paket_belanja pb
						ON pb.idpaket_belanja =
						pbd.idpaket_belanja

					JOIN akun_belanja ab_normal
						ON ab_normal.idakun_belanja =
						pbd.idakun_belanja

					JOIN sub_kegiatan sk_normal
						ON sk_normal.idsub_kegiatan =
						pb.idsub_kegiatan

					JOIN kegiatan k_normal
						ON k_normal.idkegiatan =
						sk_normal.idkegiatan

					JOIN program pr_normal
						ON pr_normal.idprogram =
						k_normal.idprogram

					JOIN bidang_urusan bu_normal
						ON bu_normal.idbidang_urusan =
						pr_normal.idbidang_urusan

					JOIN urusan_pemerintah up_normal
						ON up_normal.idurusan_pemerintah =
						bu_normal.idurusan_pemerintah

					JOIN paket_belanja_detail_sub parent_sub
						ON parent_sub.idpaket_belanja_detail =
						pbd.idpaket_belanja_detail
					AND parent_sub.status = 1

					WHERE pbd.status = 1
					AND pb.status = 1
					AND pb.status_paket_belanja = 'OK'

					AND ab_normal.status = 1
					AND ab_normal.is_active = 1

					AND pbd.idakun_belanja =
						akun_belanja.idakun_belanja

					AND up_normal.tahun_anggaran_urusan = '$tahun'


					UNION ALL


					/* ====================================================
					CHILD NORMAL
					==================================================== */
					SELECT
						child_sub.jumlah

					FROM paket_belanja_detail pbd
					JOIN paket_belanja pb
						ON pb.idpaket_belanja =
						pbd.idpaket_belanja

					JOIN akun_belanja ab_normal
						ON ab_normal.idakun_belanja =
						pbd.idakun_belanja

					JOIN sub_kegiatan sk_normal
						ON sk_normal.idsub_kegiatan =
						pb.idsub_kegiatan

					JOIN kegiatan k_normal
						ON k_normal.idkegiatan =
						sk_normal.idkegiatan

					JOIN program pr_normal
						ON pr_normal.idprogram =
						k_normal.idprogram

					JOIN bidang_urusan bu_normal
						ON bu_normal.idbidang_urusan =
						pr_normal.idbidang_urusan

					JOIN urusan_pemerintah up_normal
						ON up_normal.idurusan_pemerintah =
						bu_normal.idurusan_pemerintah

					JOIN paket_belanja_detail_sub parent_sub
						ON parent_sub.idpaket_belanja_detail =
						pbd.idpaket_belanja_detail
					AND parent_sub.status = 1

					JOIN paket_belanja_detail_sub child_sub
						ON child_sub.is_idpaket_belanja_detail_sub =
						parent_sub.idpaket_belanja_detail_sub
					AND child_sub.status = 1

					WHERE pbd.status = 1
					AND pb.status = 1
					AND pb.status_paket_belanja = 'OK'

					AND ab_normal.status = 1
					AND ab_normal.is_active = 1

					AND pbd.idakun_belanja =
						akun_belanja.idakun_belanja

					AND up_normal.tahun_anggaran_urusan = '$tahun'
				) AS data_murni
			";
		
		return $query;
	}
}