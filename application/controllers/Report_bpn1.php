<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_bpn1 extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_bpn1');
        $this->table = 'pad_target';
        $this->controller = 'report_bpn1';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal', 'Nomor Bukti', 'Kode Rekening', 'Alat Bayar', 'Uraian', 'Penerimaan', 'Pengeluaran', 'Saldo'));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
        $crud->set_btn_add(false);

		$tahun = $azapp->add_datetime();
		$tahun->set_id('vf_tahun');
		$tahun->set_name('vf_tahun');
		$tahun->set_value(Date('m-Y'));
		$tahun->set_format('MM-YYYY');
		$data['tahun'] = $tahun->render();

		$crud->add_aodata('vf_tahun', 'vf_tahun');

		$filter = $this->load->view('report_bpn1/vf_report_bpn1', $data, true);
		$crud->set_top_filter($filter);

		$js = az_add_js('report_bpn1/vjs_report_bpn1');
		$azapp->add_js($js);
        
        $total_saldo_awal = 0;
        $crud->set_btn_top_custom("
			<table>
				<tr>
					<td><button class='btn btn-success btn-pdf' type='button' id='btn_export'><i class='fa fa-file-pdf'></i> Export PDF</button></td>
					<td style='padding-left:30px; font-weight:bold;'>Saldo Awal : <span id='all_total_debt'>".az_thousand_separator_decimal($total_saldo_awal)."</span></td>
				</tr>
			</table>");

		// $crud->set_callback_edit('
		// 	check_copy();
        // ');
		
		$crud = $crud->render();
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Laporan Buku Kas Umum Penerimaan (BPn - 1)');
		$data_header['breadcrumb'] = array('report_pad', 'role_report_bpn1');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun = $this->input->get('vf_tahun');

		$query1 = $this->get_pad_mutasi_kas($tahun);

		$query2 = $this->get_pad_sts($tahun);

        // echo "<pre>"; print_r($query1); die();
        // echo "<pre>"; print_r($query2); die();

        $crud->set_select($query1);
        $crud->set_select_union($query2);
		$crud->set_select_table('id, txt_proof_date, proof_number, kode_rekening, alat_bayar, uraian, penerimaan, pengeluaran, (SUM(penerimaan - pengeluaran) OVER (
			ORDER BY proof_date ASC
			ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
		)) AS saldo');
        // $crud->set_sorting('transaction_date, transaction_code, nama_paket_belanja, total_realisasi, transaction_status');
        // $crud->set_filter('txt_proof_date, proof_number, kode_rekening, alat_bayar, uraian');

        $crud->set_select_align(', , , , , right, right, right');
		$crud->set_id($this->controller);

		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
        $proof_for = azarr($data, 'proof_for');
        $uraian = azarr($data, 'uraian');
        
        if ($key == 'uraian') {
            $html = $uraian;
            $html .= '<br><span style="font-size: 11px;"> ' . $proof_for . '</span>';

            return $html;
        }
		if ($key == 'penerimaan') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
        if ($key == 'pengeluaran') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'saldo') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		return $value;
	}

	public function export_pdf($tahun) {
		$this->load->library('pdf');

		$sql_pad_mutasi = $this->get_pad_mutasi_kas($tahun);
		$sql_pad_sts    = $this->get_pad_sts($tahun);

		$this->db->select("
				id,
				txt_proof_date,
				proof_number,
				kode_rekening,
				alat_bayar,
				uraian,
				penerimaan,
				pengeluaran,
				SUM(penerimaan - pengeluaran) OVER (
					ORDER BY proof_date ASC
					ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
				) AS saldo
			", FALSE);

		$this->db->from("(
			{$sql_pad_mutasi}
			UNION
			{$sql_pad_sts}
		) AS new_query", NULL, FALSE);

		$bpn1 = $this->db->get();
		// echo "<pre>"; print_r($this->db->last_query()); die();

		$month = $this->reformat_month($tahun);
		
		$data = array(
			'bpn1' => $bpn1->result(),
			'month' => $month,
		);


		$html = $this->load->view('report_bpn1/v_report_bpn1_pdf', $data, TRUE);

		$this->pdf->loadHtml($html);

		$this->pdf->setPaper('A4', 'landscape');

		$this->pdf->render();

		// Footer nomor halaman
		$canvas = $this->pdf->getCanvas();

		$font = "Helvetica";

		$canvas->page_text(
			720,
			575,
			"Halaman {PAGE_NUM} / {PAGE_COUNT}",
			$font,
			8,
			array(0,0,0)
		);	

		$this->pdf->stream(
			'Laporan_BPn1.pdf',
			[
				'Attachment' => false
			]
		);
	}

	function get_pad_mutasi_kas($tahun) {
		$query1 = "SELECT pad_mutasi_kas.idpad_mutasi_kas as id, 
                        pad_mutasi_kas.proof_date, 
                        date_format(pad_mutasi_kas.proof_date, '%d-%m-%Y') as txt_proof_date, 
                        pad_mutasi_kas.proof_number, 
                        '' as kode_rekening, 
                        '' as alat_bayar, 
                        pad_kode_rekening.uraian, 
                        pad_mutasi_kas.proof_for, 
                        IF(
							pad_mutasi_kas.proof_type='PINDAH_REKENING',
							pad_mutasi_kas.total_mutasi_kas,
							0
						) AS penerimaan,
						IF(
							pad_mutasi_kas.proof_type='PINDAH_REKENING',
							pad_mutasi_kas.total_mutasi_kas,
							0
						) AS pengeluaran 
                    FROM pad_mutasi_kas
                    JOIN pad_kode_rekening ON pad_mutasi_kas.idproof_to = pad_kode_rekening.idpad_kode_rekening
                    WHERE pad_mutasi_kas_status = 'OK' 
                    AND pad_mutasi_kas.status = '1'
                    AND DATE_FORMAT(pad_mutasi_kas.proof_date,'%m-%Y') = '$tahun'
                    ";
		// echo "<pre>"; print_r($this->db->last_query());die;

		return $query1;
	}

	function get_pad_sts($tahun) {
		$query2 = "SELECT pad_sts.idpad_sts as id, 
                        pad_sts.proof_date, 
                        date_format(pad_sts.proof_date, '%d-%m-%Y') as txt_proof_date, 
                        pad_sts.proof_number, 
                        pad_kode_rekening.kode_rekening, 
                        '' as alat_bayar, 
                        pad_kode_rekening.uraian, 
                        pad_sts.proof_for, 
                        pad_sts_detail.total_detail as penerimaan, 
                        0 as pengeluaran
                    FROM pad_sts
                    JOIN pad_sts_detail ON pad_sts.idpad_sts = pad_sts_detail.idpad_sts
                    JOIN pad_kode_rekening ON pad_sts_detail.idpad_kode_rekening = pad_kode_rekening.idpad_kode_rekening
                    WHERE pad_sts.pad_sts_status = 'OK'
                    AND pad_sts.status = '1'
                    AND pad_sts_detail.status = '1'
                    AND DATE_FORMAT(pad_sts.proof_date,'%m-%Y') = '$tahun'
                    ";
		// echo "<pre>"; print_r($this->db->last_query());die;

		return $query2;
	}

	function reformat_month($input) {
		list($bulan, $tahun) = explode('-', $input);

		$nama_bulan = [
			1  => 'Januari',
			2  => 'Februari',
			3  => 'Maret',
			4  => 'April',
			5  => 'Mei',
			6  => 'Juni',
			7  => 'Juli',
			8  => 'Agustus',
			9  => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember'
		];

		$hasil = $nama_bulan[(int)$bulan] . ' ' . $tahun;

		return $hasil;
	}
}