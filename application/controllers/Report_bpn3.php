<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_bpn3 extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('role_report_bpn3');
        $this->table = 'pad_kode_rekening';
        $this->controller = 'report_bpn3';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Kode Rekening', 'Uraian', 'Target Pendapatan 12 bln', 'Target Pendapatan Bulan Laporan', 'Realisasi Bulan ini', 'Realisasi jumlah s/d bulan lalu', 'Realisasi jumlah s/d bulan ini', 'Pencapaian target tahunan', 'Pencapaian target bulanan'));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
        $crud->set_btn_add(false);

		$date1 = $azapp->add_datetime();
		$date1->set_id('date1');
		$date1->set_name('date1');
		$date1->set_format('DD-MM-YYYY');
		$date1->set_value('01-'.Date('m-Y'));
		// $date1->set_value('01-01-'.Date('Y'));
		$data['date1'] = $date1->render();

		$date2 = $azapp->add_datetime();
		$date2->set_id('date2');
		$date2->set_name('date2');
		$date2->set_format('DD-MM-YYYY');
		$date2->set_value(Date('t-m-Y'));
		$data['date2'] = $date2->render();

		$crud->add_aodata('date1', 'date1');
		$crud->add_aodata('date2', 'date2');

		$filter = $this->load->view('report_bpn3/vf_report_bpn3', $data, true);
		$crud->set_top_filter($filter);

		$js = az_add_js('report_bpn3/vjs_report_bpn3');
		$azapp->add_js($js);
        
        $total_saldo_awal = 0;
        $crud->set_btn_top_custom("
			<table>
				<tr>
					<td><button class='btn btn-success btn-pdf' type='button' id='btn_export'><i class='fa fa-file-pdf'></i> Export PDF</button></td>
				</tr>
			</table>");

		// $crud->set_callback_edit('
		// 	check_copy();
        // ');
		
		$crud = $crud->render();
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Laporan Realisasi Pendapatan (BPn - 3)');
		$data_header['breadcrumb'] = array('report_pad', 'role_report_bpn3');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');

		$date1_format = az_crud_date($date1, 'Y-m-d');
		$date2_format = az_crud_date($date2, 'Y-m-d');

		$bulan_ini = $this->get_bulan_ini($date1_format, $date2_format); 
		$bulan_lalu = $this->get_bulan_lalu($date1_format, $date2_format);

		$sd_bulan_ini = $bulan_ini . " + " . $bulan_lalu;
		$capaian_tahunan = $sd_bulan_ini;
		$capaian_bulanan = $bulan_ini;

		$this->db->select("pad_kode_rekening.idpad_kode_rekening as id, 
			pad_kode_rekening.kode_rekening, 
			pad_kode_rekening.uraian, 
			pad_target.target_per_tahun, 
			pad_target.target_bulan_laporan as bulan_laporan, 
			{$bulan_ini} as bulan_ini, 
			{$bulan_lalu} as sd_bulan_lalu, 
			{$sd_bulan_ini} as sd_bulan_ini, 
			( ( ( {$capaian_tahunan} ) / pad_target.target_per_tahun) * 100) as capaian_tahunan, 
			( ( {$capaian_bulanan} / pad_target.target_bulan_laporan) * 100) as capaian_bulanan", FALSE);
		$this->db->from($this->table);
		$this->db->join('pad_target', 'pad_kode_rekening.idpad_kode_rekening = pad_target.idpad_kode_rekening');
		if (strlen($date1) > 0 && strlen($date2) > 0) {
            $this->db->where('pad_target.tahun >=', date('Y', strtotime($date1)), FALSE);
			$this->db->where('pad_target.tahun <=', date('Y', strtotime($date2)), FALSE);
        }
        
		$this->db->where("pad_kode_rekening.status = 1");
		$this->db->where("pad_target.status = 1");
		$this->db->order_by('pad_kode_rekening.idpad_kode_rekening ASC');
		$sts = $this->db->get();
		$last_query = $this->db->last_query();
		// echo "<pre>"; print_r($last_query); die();

		$crud->set_manual_query($last_query);
        $crud->set_select_table('id, kode_rekening, uraian, target_per_tahun, bulan_laporan, bulan_ini, sd_bulan_lalu, sd_bulan_ini, capaian_tahunan, capaian_bulanan');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , right, right, right, right, right, right, right');

		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
        
		if ($key == 'target_per_tahun') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'bulan_laporan') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'bulan_ini') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'sd_bulan_lalu') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'sd_bulan_ini') {
			return 'Rp. '.az_thousand_separator_decimal($value);
		}
		if ($key == 'capaian_tahunan') {
			return az_thousand_separator_decimal($value).' %';
		}
		if ($key == 'capaian_bulanan') {
			return az_thousand_separator_decimal($value).' %';
		}
        
		return $value;
	}

	public function export_pdf() {
		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');

		$this->load->library('pdf');

		$date1_format = az_crud_date($date1, 'Y-m-d');
		$date2_format = az_crud_date($date2, 'Y-m-d');

		$bulan_ini = $this->get_bulan_ini($date1_format, $date2_format); 
		$bulan_lalu = $this->get_bulan_lalu($date1_format, $date2_format);

		$sd_bulan_ini = $bulan_ini . " + " . $bulan_lalu;
		$capaian_tahunan = $sd_bulan_ini;
		$capaian_bulanan = $bulan_ini;

		$this->db->select("pad_kode_rekening.idpad_kode_rekening as id, 
			pad_kode_rekening.kode_rekening, 
			pad_kode_rekening.uraian, 
			pad_target.target_per_tahun, 
			pad_target.target_bulan_laporan as bulan_laporan, 
			{$bulan_ini} as bulan_ini, 
			{$bulan_lalu} as sd_bulan_lalu, 
			{$sd_bulan_ini} as sd_bulan_ini, 
			( ( ( {$capaian_tahunan} ) / pad_target.target_per_tahun) * 100) as capaian_tahunan, 
			( ( {$capaian_bulanan} / pad_target.target_bulan_laporan) * 100) as capaian_bulanan", FALSE);
		$this->db->from($this->table);
		$this->db->join('pad_target', 'pad_kode_rekening.idpad_kode_rekening = pad_target.idpad_kode_rekening');
		if (strlen($date1) > 0 && strlen($date2) > 0) {
            $this->db->where('pad_target.tahun >=', date('Y', strtotime($date1)), FALSE);
			$this->db->where('pad_target.tahun <=', date('Y', strtotime($date2)), FALSE);
        }
        
		$this->db->where("pad_kode_rekening.status = 1");
		$this->db->where("pad_target.status = 1");
		$this->db->order_by('pad_kode_rekening.idpad_kode_rekening ASC');
		$sts = $this->db->get();
		$last_query = $this->db->last_query();
		// echo "<pre>"; print_r($last_query); die();
		
		$data = array(
			'sts' => $sts->result(),
			'date1' => $date1,
			'date2' => $date2
		);


		$html = $this->load->view('report_bpn3/v_report_bpn3_pdf', $data, TRUE);

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
			'Laporan_BPn3.pdf',
			[
				'Attachment' => false
			]
		);
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

	function get_bulan_ini($date1_format, $date2_format) {
		$bulan_ini = "(
				SELECT COALESCE(SUM(pad_sts_detail.total_detail), 0)
				FROM pad_sts
				JOIN pad_sts_detail
					ON pad_sts.idpad_sts = pad_sts_detail.idpad_sts
				WHERE pad_sts_detail.idpad_kode_rekening = pad_kode_rekening.idpad_kode_rekening
					AND pad_sts.status = 1
					AND pad_sts_detail.status = 1
					AND pad_sts.pad_sts_status != 'DRAFT'
					AND pad_sts.proof_date >= '{$date1_format}' 
					AND pad_sts.proof_date <= '{$date2_format}'
			)";

		return $bulan_ini;
	}

	function get_bulan_lalu($date1_format, $date2_format) {
		$bulan_lalu = "(
				SELECT COALESCE(SUM(pad_sts_detail.total_detail), 0)
				FROM pad_sts
				JOIN pad_sts_detail
					ON pad_sts.idpad_sts = pad_sts_detail.idpad_sts
				WHERE pad_sts_detail.idpad_kode_rekening = pad_kode_rekening.idpad_kode_rekening
					AND pad_sts.status = 1
					AND pad_sts_detail.status = 1
					AND pad_sts.pad_sts_status != 'DRAFT'
					AND pad_sts.proof_date < '{$date1_format}'
			)";

		return $bulan_lalu;
	}
}