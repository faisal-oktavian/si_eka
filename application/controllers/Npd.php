<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Npd extends CI_Controller {
	public function __construct() {	
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('npd');
        $this->table = 'npd';
        $this->controller = 'npd';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('idrole');

		$crud->set_column(array('#', 'Tanggal NPD', 'Nomor NPD', 'Keterangan', 'Detail', 'Status NPD', 'User Input', azlang('Action')));
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
		$crud->add_aodata('npd_code', 'npd_code');
		$crud->add_aodata('vf_npd_status', 'vf_npd_status');

		$vf = $this->load->view('npd/vf_npd', $data, true);
        $crud->set_top_filter($vf);

		if (!aznav('role_view_npd') || strlen($idrole) == 0) {
			$btn = "<button class='btn btn-primary az-btn-primary btn-add-npd' type='button'><span class='glyphicon glyphicon-plus'></span> Tambah</button>";
			$crud->set_btn_top_custom($btn);
		}

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'npd';
		$view = $this->load->view('npd/v_format_npd', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('npd/vjs_npd');
		$azapp->add_js($js);

		$data_header['title'] = 'Nota Pencairan Dana (NPD)';
		$data_header['breadcrumb'] = array('npd');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$npd_code = $this->input->get('npd_code');
		$npd_status = $this->input->get('vf_npd_status');

        $crud->set_select('npd.idnpd, date_format(npd_date_created, "%d-%m-%Y %H:%i:%s") as txt_date_input, npd_code, "" as type_code, "" as detail, npd_status, user_created.name as user_input');

        $crud->set_select_table('idnpd, txt_date_input, npd_code, type_code, detail, npd_status, user_input');
        $crud->set_sorting('npd_code, npd_status, user_input');
        $crud->set_filter('npd_code, npd_status, user_input');
		$crud->set_id($this->controller);
		$crud->set_select_align(', , , center, center');

        $crud->add_join_manual('user user_created', 'npd.iduser_created = user_created.iduser', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(npd.npd_date_created) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(npd.npd_date_created) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($npd_code) > 0) {
			$crud->add_where('npd.npd_code = "' . $npd_code . '"');
		}
		if (strlen($npd_status) > 0) {
			$crud->add_where('npd.npd_status = "' . $npd_status . '"');
		}

		$crud->add_where("npd.status = 1");
		$crud->add_where("npd.npd_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('npd_date_created desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		
		$idnpd = azarr($data, 'idnpd');
		$idrole = $this->session->userdata('idrole');
		$read_more = false;
		$is_view_only = false;

		$this->db->where('npd.idnpd', $idnpd);
		$this->db->where('npd.status', 1);
		$this->db->where('npd_detail.status', 1);
		
		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		
		$this->db->select('npd.idnpd, npd_detail.idnpd_detail, npd_detail.idverification, verification.verification_code, npd.npd_status');
		$npd = $this->db->get('npd');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$table = "<table class='table table-bordered table-condensed' id='table_dokumen'>";
		$table .=	"<thead>";
		$table .=		"<tr>";
		$table .=			"<th width='100px;'>Nomor Verifikasi Dokumen</th>";
		$table .=			"<th width='100px;'>Nomor Kontrak</th>";
		$table .=			"<th width='300px'>Nama Paket Belanja</th>";
		$table .=			"<th width='200px'>Uraian</th>";
		$table .=			"<th width='50px'>Volume</th>";
		$table .=			"<th width='150px'>Keterangan</th>";
		$table .=		"</tr>";
		$table .=	"</thead>";
		$table .=	"<tbody>";

		foreach ($npd->result() as $npd_key => $npd_value) {

			$this->db->where('verification.idverification', $npd_value->idverification);
			$this->db->where('verification.status', 1);
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization_detail.status', 1);
			// $this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
			$this->db->where('verification.status_approve = "DISETUJUI" ');

			$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('verification.idverification, verification.verification_code, budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail, budget_realization_detail.realization_detail_description, contract_spt, contract_invitation_number, contract_sp, contract_spk, contract_honor');
			$verification = $this->db->get('verification');
			// echo "<pre>"; print_r($this->db->last_query());die;

			$last_query = $this->db->last_query();
			$verification_limit = $this->db->query('SELECT * FROM ('.$last_query.') as new_query limit 3 ');

			foreach ($verification_limit->result() as $verification_key => $c_value) {
				$table .=	"<tr>";
				$table .=		"<td>".$c_value->verification_code."</td>";
				$table .=		"<td>".$c_value->contract_code."</td>";
				$table .=		"<td>".$c_value->nama_paket_belanja."</td>";
				$table .=		"<td>".$c_value->nama_sub_kategori."</td>";
				$table .=		"<td>".az_thousand_separator($c_value->volume)."</td>";
				$table .= 		"<td>".$c_value->realization_detail_description."</td>";
				$table .=	"</tr>";
			}
		}
		
		$table .=	"</tbody>";
		$table .= "</table>";

		if ($key == 'detail') {

			if ($verification->num_rows() > 3) {
				$read_more = true;
			}

			if ($read_more) {
				$table .= '<a href="npd/edit/'.$idnpd.'/view_only">Selengkapnya...</a>';
			}

			return $table;
		}

		if ($key == "type_code") {
			$contract_spt = $verification->row()->contract_spt;
			$contract_invitation_number = $verification->row()->contract_invitation_number;
			$contract_sp = $verification->row()->contract_sp;
			$contract_spk = $verification->row()->contract_spk;
			$contract_honor = $verification->row()->contract_honor;
			
			if (strlen($contract_spt) > 0) {
				$text = "No. SPT : ".$contract_spt." ";
			}
			else if (strlen($contract_invitation_number) > 0) {
				$text = "No. Undangan : ".$contract_invitation_number." ";
			}
			else if (strlen($contract_sp) > 0) {
				$text = "No. SP : ".$contract_sp." ";
			}
			else if (strlen($contract_spk) > 0) {
				$text = "No. SPK : ".$contract_spk." ";
			}
			else if (strlen($contract_honor) > 0) {
				$text = "Gaji/Honor : ".$contract_honor." ";
			}

			return $text;
		}

		if ($key == 'npd_status') {
			$status = label_status($value);
			
			return $status;
		}

		if ($key == 'action') {
            $idnpd = azarr($data, 'idnpd');
            // $idpaket_belanja = $npd_detail->row()->idpaket_belanja;
			$is_viewonly = false;

			$btn = '';
			if (!aznav('role_view_npd') || strlen($idrole) == 0) {
				$btn .= '<button class="btn btn-default btn-xs btn-edit_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
				$btn .= '<button class="btn btn-danger btn-xs btn-delete_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

				$this->db->where('idnpd', $idnpd);
				$npd = $this->db->get('npd');

				$npd_status = $npd->row()->npd_status;
				$is_print = $npd->row()->is_print;
				// INPUT DATA, MENUNGGU PEMBAYARAN, SUDAH DIBAYAR BENDAHARA
				if (in_array($npd_status, array("MENUNGGU PEMBAYARAN", "SUDAH DIBAYAR BENDAHARA") ) ) {
					$is_viewonly = true;
				}

				if ($npd_status == "INPUT NPD" && $is_print == 1) {
					$btn .= '<button class="btn btn-info btn-xs btn-send_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-send"></span> Kirim ke bendahara</button>';
				}
			}
			else {
				$is_viewonly = true;	
			}

			if ($is_viewonly) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-npd" data_id="'.$idnpd.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';
			}


			$this->db->where('npd.idnpd', $idnpd);
			$this->db->where('npd.status', 1);
			$this->db->where('npd_detail.status', 1);
			
			$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
			$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
			$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			
			$this->db->select('purchase_plan_detail.idpaket_belanja');
			$npd = $this->db->get('npd');

			$idpaket_belanja = $npd->row()->idpaket_belanja;

			// $btn .= '<button class="btn btn-xs btn-default btn-print" data_idnpd="'.$idnpd.'" data_idpaket_belanja="'.$idpaket_belanja.'"><i class="fa fa-print"></i> Cetak</button>';
			$btn .= '<a href="' . app_url() . 'npd/print_npd?idn=' . $idnpd . '&idp=' . $idpaket_belanja . '" class="btn btn-xs btn-default btn-print" target="_blank"><i class="fa fa-print"></i> Cetak</a> ';

			return $btn;
		}

		return $value;
	}
    
    function add($id = '') {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

        $data = array(
            'id' => $id,
            'iduser_created' => $this->session->userdata('iduser'),
            'user_name' => $this->session->userdata('name'),
        );
        
		$view = $this->load->view('npd/v_npd', $data, true);
		$azapp->add_content($view);

		// get data document
		$document = $azapp->add_crud();
		$document->set_column(array('#', "Tanggal Verifikasi", "Nomor Dokumen", "Detail"));
		$document->set_th_class(array('', '', '', '', '', '', '', '', ''));
		$document->set_width('auto, 100px, 100px, auto');
		$document->set_id($this->controller . '2');
		$document->set_default_url(false);
		$document->set_btn_add(false);

		$document->set_url("app_url+'npd/get_document?idnpd=".$id."'");
		$document->set_url_edit("app_url+'npd/edit_document'");
		$document->set_url_delete("app_url+'npd/delete_document'");
		$document->set_url_save("app_url+'npd/save_document'");
		// $document->set_callback_table_complete('callback_check_plan_table();');
		$data_add['document'] = $document->render();

		$data_add['id'] = '';

		$v_modal = $this->load->view('npd/v_npd_modal', $data_add, true);
		$modal = $azapp->add_modal();
		$modal->set_id('add');
		$modal->set_modal_title('Tambah NPD');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('npd/vjs_npd_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Nota Pencairan Dana (NPD)';
		$data_header['breadcrumb'] = array('npd');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_document()
	{
		$this->load->library('AZApp');
		$crud_document = $this->azapp->add_crud();
		$idnpd = $this->input->get('idnpd');


		$crud_document->set_select('verification.idverification, verification.idverification as txt_idverification, date_format(verification.confirm_verification_date, "%d-%m-%Y %H:%i:%s") AS txt_confirm_verification_date, verification.verification_code, "" AS detail');
		
		$crud_document->set_select_table('idverification, txt_idverification, txt_confirm_verification_date, verification_code, detail');

        $crud_document->set_sorting('confirm_verification_date, verification_code');
        $crud_document->set_filter('confirm_verification_date, verification_code');
		$crud_document->set_id($this->controller . '2');
		$crud_document->set_custom_first_column(true);
		// $crud_document->set_select_align('center, center, , , center, center');

		$crud_document->add_join_manual('user user_realization', 'user_realization.iduser = verification.iduser_created');
        $crud_document->add_join_manual('user user_verification', 'user_verification.iduser = verification.iduser_verification', 'left');
        
		$crud_document->add_where("verification.status = '1' ");
		if (strlen($idnpd) == 0) {
			$crud_document->add_where("verification.verification_status = 'SUDAH DIVERIFIKASI' ");
		}
		else {
			$crud_document->add_where("verification.verification_status = 'SUDAH DIVERIFIKASI' OR verification.idverification IN (SELECT npd_detail.idverification FROM npd_detail WHERE npd_detail.idnpd = '".$idnpd."' AND npd_detail.status = 1) ");
		}

		$crud_document->set_table('verification');
		$crud_document->set_custom_style('custom_style_document');
		echo $crud_document->get_table();
	}

	function custom_style_document($key, $value, $data) {
		$idverification = azarr($data, 'idverification');

		if ($key == 'txt_idverification') {
			$id = '<button class="btn btn-success btn-xs btn-select-document" type="button" data_id="'.$idverification.'"> Pilih</button>';

			return $id;
		}

		if ($key == 'detail') {
            $this->db->where('verification.idverification', $idverification);
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization_detail.status', 1);

			$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail');
			$budget_realization = $this->db->get('verification');
            // echo "<pre>"; print_r($this->db->last_query());die;

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th width='auto'>Nomor Kontrak</th>";
			// $table .=			"<th width='auto'>Nomor Rencana</th>";
			$table .=			"<th width='180px'>Paket Belanja</th>";
			$table .=			"<th width='200px'>Uraian</th>";
			$table .=			"<th width='60px'>Volume</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";

            foreach ($budget_realization->result_array() as $key => $value) {
				$table .= "<tr>";
				$table .= 		"<td>".$value['contract_code']."</td>";
				// $table .= 		"<td>".$value['purchase_plan_code']."</td>";
				$table .= 		"<td>".$value['nama_paket_belanja']."</td>";
				$table .= 		"<td>".$value['nama_sub_kategori']."</td>";
				$table .= 		"<td align='center'>".az_thousand_separator($value['volume'])."</td>";
				$table .= "</tr>";
            }
			
			$table .=	"</tbody>";
			$table .= "</table>";

			return $table;
		}

		return $value;
	}

	function edit($id) {
		$this->db->where('idnpd', $id);
		$check = $this->db->get('npd');
		if ($check->num_rows() == 0) {
			redirect(app_url().'npd');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->npd_status;

			$the_filter = array(
				'menu' => 'NPD',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($status, $arr_validation) ) {
				redirect(app_url().'npd');
			}
		}
		$this->add($id);
	}

    function search_dokumen() {
		$keyword = $this->input->get("term");

		$this->db->like('verification.verification_code', $keyword);

		$this->db->where('budget_realization.status', 1);
		$this->db->where('verification.status', 1);
		$this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');

		$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');

		$this->db->select('verification.idverification as id, verification.verification_code as text');
		$data = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

    function select_dokumen() {
		$idverification = $this->input->post('idverification');
		$idnpd_detail = $this->input->post('idnpd_detail');

		$this->db->where('verification.idverification', $idverification);
		$this->db->where('verification.status', 1);
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization_detail.status', 1);
		if (strlen($idnpd_detail) == 0) {
			$this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
		}
		$this->db->where('verification.status_approve = "DISETUJUI" ');

		$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

		$this->db->select('verification.idverification, verification.verification_code, budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail');
		$data['verification'] = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$view = $this->load->view('npd/v_select_document_table', $data, true);
		
		$arr = array(
			'data' => $view,
            'idverification' => $data['verification']->row()->idverification,
            'verification_code' => $data['verification']->row()->verification_code,
		);

		echo json_encode($arr);
	}

    function add_product() {
		$err_code = 0;
		$err_message = '';

	 	$idnpd = $this->input->post('idnpd');
	 	$idnpd_detail = $this->input->post('idnpd_detail');
		$idverification = $this->input->post('idverification');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idverification', 'Dokumen', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			$this->db->where('idnpd',$idnpd);
			$npd = $this->db->get('npd');

			if ($npd->num_rows() > 0) {
				$status = $npd->row()->npd_status;

				$the_filter = array(
					'menu' => 'NPD',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {

			if (strlen($idnpd) == 0) {
				$arr_npd = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'npd_date_created' => Date('Y-m-d H:i:s'),
					'npd_status' => 'DRAFT',
					'no_urut' => $this->generate_urutan(),
					'npd_code' => $this->generate_transaction_code(),
				);

				$save_npd = az_crud_save($idnpd, 'npd', $arr_npd);
				$idnpd = azarr($save_npd, 'insert_id');
			}
			else {
				// validasi apakah sudah ada dokumen yang diinputkan
				// dalam 1 transaksi hanya boleh ada 1 dokumen yang diinputkan
				// validasi ini tidak berlaku jika edit dokumen
				
				if ($idnpd_detail == '') {
					$this->db->where('idnpd', $idnpd);
					$this->db->where('status', 1);
					$npd_detail = $this->db->get('npd_detail');
					// echo "<pre>"; print_r($this->db->last_query()); die;
					
					if ($npd_detail->num_rows() == 1) {
						$err_code++;
						$err_message = "Transaksi ini hanya diperbolehkan memiliki 1 dokumen saja.";
					}
				}
			}
            
			if ($err_code == 0) {
				// cek apakah datanya baru diinput / edit data
				$this->db->where('npd.idnpd', $idnpd);
				$check = $this->db->get('npd');

				if (strlen($idnpd_detail) > 0) {
					if ($check->row()->npd_status != "DRAFT") {
						
						// kembalikan dulu status dari rencana pengadaan yang sudah dipilih sebelumnya
						$this->db->where('npd_detail.idnpd_detail', $idnpd_detail);
						$this->db->where('verification.status', 1);
						$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
						$npd_detail = $this->db->get('npd_detail');

						// update status verifikasi dokumen
						$the_filter = array(
							'idverification' => $idverification,
							'idbudget_realization' => $npd_detail->row()->idbudget_realization,
							'status' => 'SUDAH DIVERIFIKASI'
						);
						$update_status = update_status_document_verification($the_filter);
					}
				}

				//transaction detail
				$arr_npd_detail = array(
					'idnpd' => $idnpd,
					'idverification' => $idverification,
				);
				
				$td = az_crud_save($idnpd_detail, 'npd_detail', $arr_npd_detail);
				$idnpd_detail = azarr($td, 'insert_id');
				

				if ($check->row()->npd_status != "DRAFT") {

					// hitung total anggaran
					$total_anggaran = $this->calculate_total_anggaran($idnpd);
					
					$arr_npd = array(
						'total_anggaran' => $total_anggaran,
					);

					$save_npd = az_crud_save($idnpd, 'npd', $arr_npd);


					$this->db->where('npd_detail.idnpd_detail', $idnpd_detail);
					$this->db->where('verification.status', 1);
					$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
					$npd_detail = $this->db->get('npd_detail');

					// update status verifikasi dokumen
					$the_filter = array(
						'idverification' => $idverification,
						'idbudget_realization' => $npd_detail->row()->idbudget_realization,
						'status' => 'INPUT NPD'
					);
					$update_status = update_status_document_verification($the_filter);
				}
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idnpd' => $idnpd,
			'idnpd_detail' => $idnpd_detail,
		);
		echo json_encode($return);
	}

	function save_npd() {
		$err_code = 0;
		$err_message = '';

		
		$idnpd = $this->input->post("hd_idnpd");
		$npd_date_created = az_crud_date($this->input->post("npd_date_created"));
		$iduser_created = $this->input->post("iduser_created");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('npd_date_created', 'Tanggal NPD', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idnpd) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$this->db->where('npd.idnpd',$idnpd);
			$this->db->where('npd.status', 1);
			$this->db->where('npd_detail.status', 1);
			$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
			$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
			$npd = $this->db->get('npd');

			if ($npd->num_rows() > 0) {
				$status = $npd->row()->npd_status;

				$the_filter = array(
					'menu' => 'NPD',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}
		
		if ($err_code == 0) {

			// hitung total anggaran
			$total_anggaran = $this->calculate_total_anggaran($idnpd);

	    	$arr_data = array(
	    		'npd_date_created' => $npd_date_created,
	    		'npd_status' => "INPUT NPD",
	    		'iduser_created' => $iduser_created,
	    		'total_anggaran' => $total_anggaran,
	    	);

	    	az_crud_save($idnpd, 'npd', $arr_data);


			foreach ($npd->result() as $key => $value) {
				// update status verifikasi dokumen
				$the_filter = array(
					'idverification' => $value->idverification,
					'idbudget_realization' => $value->idbudget_realization,
					'status' => 'INPUT NPD'
				);
				$update_status = update_status_document_verification($the_filter);
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function delete_npd() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		if (strlen($id) == 0) {
			$err_code++;
			$err_message = 'Invalid ID';
		}

		if ($err_code == 0) {
			$this->db->where('npd.idnpd',$id);
			$this->db->where('npd.status', 1);
			$this->db->where('npd_detail.status', 1);
			$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
			$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
			$npd = $this->db->get('npd');

			if ($npd->num_rows() > 0) {
				$status = $npd->row()->npd_status;

				$the_filter = array(
					'menu' => 'NPD',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if($err_code == 0) {
			foreach ($npd->result() as $key => $value) {
				// update status verifikasi dokumen
				$the_filter = array(
					'idverification' => $value->idverification,
					'idbudget_realization' => $value->idbudget_realization,
					'status' => 'SUDAH DIVERIFIKASI'
				);
				$update_status = update_status_document_verification($the_filter);
			}
			
			az_crud_delete($this->table, $id);
		} 
		else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}

	function send_npd() {
		$idnpd = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		if (strlen($idnpd) == 0) {
			$err_code++;
			$err_message = 'Invalid ID';
		}

		if ($err_code == 0) {
			$this->db->where('npd.idnpd',$idnpd);
			$this->db->where('npd.status', 1);
			$this->db->where('npd_detail.status', 1);
			$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
			$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
			$npd = $this->db->get('npd');

			if ($npd->num_rows() > 0) {
				$status = $npd->row()->npd_status;

				$the_filter = array(
					'menu' => 'NPD',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if($err_code == 0) {
			// // hitung total anggaran
			$total_anggaran = $this->calculate_total_anggaran($idnpd);

			
			// update status npd
			$arr_data = array(
				'npd_status' => 'MENUNGGU PEMBAYARAN',
				'updated' => Date('Y-m-d H:i:s'),
				'updatedby' => $this->session->userdata('username'),
				'total_anggaran' => $total_anggaran,
			);
			
			$this->db->where('idnpd', $idnpd);
			$this->db->update('npd', $arr_data);


			foreach ($npd->result() as $key => $value) {
				// update status verifikasi dokumen
				$the_filter = array(
					'idverification' => $value->idverification,
					'idbudget_realization' => $value->idbudget_realization,
					'status' => 'MENUNGGU PEMBAYARAN'
				);
				$update_status = update_status_document_verification($the_filter);
			}

			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		} 
		else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}

	function edit_order() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where('idnpd_detail', $id);
		
		$this->db->select('npd_detail.idnpd_detail, npd_detail.idnpd, npd_detail.idverification');
		$npd_detail = $this->db->get('npd_detail')->result_array();

		$ret = array(
			'data' => azarr($npd_detail, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function delete_order() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = "";
		$message = "";
		$is_delete = true;

		$this->db->where('idnpd_detail',$id);
		$this->db->join('npd', 'npd_detail.idnpd = npd.idnpd');
		$npd = $this->db->get('npd_detail');

		if ($npd->num_rows() == 0) {
			$err_code++;
			$err_message = "Invalid ID";

			$is_delete = false;
		}

		if ($err_code == 0) {
			$status = $npd->row()->npd_status;
			$idnpd = $npd->row()->idnpd;
			$idverification = $npd->row()->idverification;

			$the_filter = array(
				'menu' => 'NPD',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($status, $arr_validation) ) {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";

				$is_delete = false;
			}
		}

		if ($is_delete) {
			$this->db->where('npd_detail.idnpd_detail', $id);
			$this->db->where('verification.status', 1);
			$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
			$npd_detail = $this->db->get('npd_detail');

			// update status verifikasi dokumen
			$the_filter = array(
				'idverification' => $idverification,
				'idbudget_realization' => $npd_detail->row()->idbudget_realization,
				'status' => 'SUDAH DIVERIFIKASI'
			);
			$update_status = update_status_document_verification($the_filter);

			
			$delete = az_crud_delete('npd_detail', $id, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				// hitung total anggaran
				$total_anggaran = $this->calculate_total_anggaran($id);
				
				$arr_npd = array(
					'total_anggaran' => $total_anggaran,
				);

				$save_npd = az_crud_save($id, 'npd', $arr_npd);
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada dokumen/detail transaksi di npd ini?
		if ($err_code == 0) {
			$this->db->where('idnpd', $idnpd);
			$this->db->where('status', 1);
			$npd_detail = $this->db->get('npd_detail');

			if ($npd_detail->num_rows() == 0) {
				$arr_update = array(
					'npd_status' => 'DRAFT',
				);
				az_crud_save($idnpd, 'npd', $arr_update);

				$message = 'Dokumen berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan dokumen baru, harap klik simpan transaksi NPD, agar datanya tidak hilang.</span>';
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
		);

		echo json_encode($return);
	}

	function get_data() {
		$id = $this->input->post('id');

		$this->db->where('npd.idnpd', $id);
		$this->db->join('user', 'user.iduser = npd.iduser_created');
		$this->db->select('date_format(npd_date_created, "%d-%m-%Y %H:%i:%s") as txt_npd_date_created, npd_code, user.name as user_created, npd.iduser_created');
		$this->db->order_by('npd_date_created', 'desc');
		$npd = $this->db->get('npd')->result_array();

		$this->db->where('idnpd', $id);
		$npd_detail = $this->db->get('npd_detail')->result_array();

		$return = array(
			'npd' => azarr($npd, 0),
			'npd_detail' => $npd_detail
		);
		echo json_encode($return);
	}

    function get_list_order() {
		$idnpd = $this->input->post("idnpd");

		$this->db->where('npd.idnpd', $idnpd);
		$this->db->where('npd.status', 1);
		$this->db->where('npd_detail.status', 1);
		
        $this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		
        $this->db->select('npd.idnpd, npd_detail.idnpd_detail, npd_detail.idverification, verification.verification_code, npd.npd_status');
        $npd = $this->db->get('npd');
        // echo "<pre>"; print_r($this->db->last_query());die;

        $arr_data = array();
        foreach ($npd->result() as $key => $value) {

			$this->db->where('verification.idverification', $value->idverification);
			$this->db->where('verification.status', 1);
			$this->db->where('budget_realization.status', 1);
			$this->db->where('budget_realization_detail.status', 1);
			// $this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
			$this->db->where('verification.status_approve = "DISETUJUI" ');

			$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('verification.idverification, verification.verification_code, budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail');
			$verification = $this->db->get('verification');
            // echo "<pre>"; print_r($this->db->last_query());die;

            $arr_detail = array();
            foreach ($verification->result() as $key => $c_value) {
                $arr_detail[] = array(
                    'contract_code' => $c_value->contract_code,
                    'nama_paket_belanja' => $c_value->nama_paket_belanja,
                    'nama_sub_kategori' => $c_value->nama_sub_kategori,
                    'volume' => $c_value->volume,
                );
            }

            $arr_data[] = array(
                'idnpd' => $value->idnpd,
                'idnpd_detail' => $value->idnpd_detail,
                'verification_code' => $value->verification_code,
                'npd_status' => $value->npd_status,
                'arr_detail' => $arr_detail,
            );
        }
        // echo "<pre>"; print_r($arr_data);die;

        $data['arr_data'] = $arr_data;

		$view = $this->load->view('npd/v_npd_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	public function print_npd()
	{	
		$idnpd = $this->input->get('idn');
		$idpaket_belanja = $this->input->get('idp');

		$err_code = 0;
		if (strlen($idnpd) == 0) {
			$err_code++;
		}
		if ($err_code == 0) {
			$this->db->where('npd.idnpd', $idnpd);
			$this->db->where('npd.status', 1);
			$this->db->select('*, date_format(npd.npd_date_created, "%d %M %Y") as txt_npd_date_created');
			$data = $this->db->get('npd');
			if ($data->num_rows() == 0) {
				$err_code++;
			}
			else {
				$npd_code = $data->row()->npd_code;
				$npd_date_created = $data->row()->npd_date_created;
				$npd_date = $this->bulanIndo($data->row()->txt_npd_date_created);

				$arr_update = array(
					'is_print' => 1,
				);

				$this->db->where('npd.idnpd', $idnpd);
				$this->db->update('npd', $arr_update);
			}
		}

		if ($err_code == 0) {
			$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
			$this->db->where('paket_belanja_detail.status', 1);
			
			$this->db->join('akun_belanja', 'akun_belanja.idakun_belanja = paket_belanja_detail.idakun_belanja');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = paket_belanja_detail.idpaket_belanja');
			$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
			$this->db->join('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
			$this->db->join('program', 'program.idprogram = paket_belanja.idprogram');
			// $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail = paket_belanja_detail.idpaket_belanja_detail');

			$this->db->select('
				paket_belanja_detail.idpaket_belanja_detail, 
				nama_akun_belanja, 
				status_paket_belanja, 
				akun_belanja.no_rekening_akunbelanja, 
				paket_belanja.idpaket_belanja, 
				akun_belanja.idakun_belanja, 
				concat( "(", program.no_rekening_program, ") ", program.nama_program) as nama_program, 
				concat( "(", program.no_rekening_program, ".", kegiatan.no_rekening_kegiatan, ") ", kegiatan.nama_kegiatan) as nama_kegiatan,
				concat( "(", program.no_rekening_program, ".", kegiatan.no_rekening_kegiatan, ".", sub_kegiatan.no_rekening_subkegiatan, ") ", sub_kegiatan.nama_subkegiatan) as nama_subkegiatan');
			$pb_detail = $this->db->get('paket_belanja_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;

			$nama_program = $pb_detail->row()->nama_program;
			$nama_kegiatan = $pb_detail->row()->nama_kegiatan;
			$nama_subkegiatan = $pb_detail->row()->nama_subkegiatan;

			$arr_data = array();
			$arr_detail_sub = array();
			$total_data = 0;
			foreach ($pb_detail->result() as $key => $value) {
				$idpaket_belanja_detail = $value->idpaket_belanja_detail;
				// $idpaket_belanja_detail_sub = $value->idpaket_belanja_detail_sub;

				$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail = "'.$idpaket_belanja_detail.'" ');
				$this->db->where('paket_belanja_detail_sub.status', 1);
				$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
				$detail_sub = $this->db->get('paket_belanja_detail_sub');
				
				$nama_kategori = $detail_sub->row()->nama_kategori;

					$this->db->where('paket_belanja_detail_sub.is_idpaket_belanja_detail_sub = "'.$detail_sub->row()->idpaket_belanja_detail_sub.'" ');
					$this->db->where('paket_belanja_detail_sub.status', 1);
					$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori', 'left');
					$detail_sub_parent = $this->db->get('paket_belanja_detail_sub');

				if ($detail_sub_parent->num_rows() > 0) {
					$detail_sub = $detail_sub_parent;
				}
				// echo "<pre>"; print_r($this->db->last_query());die;

				$arr_detail_sub = array();
				foreach ($detail_sub->result() as $ds_key => $ds_value) {
					$idsub_kategori = $ds_value->idsub_kategori;
					$idpaket_belanja_detail_sub = $ds_value->idpaket_belanja_detail_sub;


					$this->db->where('npd.idnpd', $idnpd);
					$this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
					$this->db->where('npd.status = "1" ');
					$this->db->where('npd_detail.status = "1" ');
					$this->db->where('verification.status = "1" ');
					$this->db->where('budget_realization.status = "1" ');
					$this->db->where('budget_realization_detail.status = "1" ');
					$this->db->where('purchase_plan_detail.status = "1" ');
					$this->db->where('paket_belanja_detail_sub.status = "1" ');
					$this->db->where('sub_kategori.status = "1" ');

					$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
					$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
					$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
					$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
					$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
					$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
					$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
					
					$this->db->select('npd.idnpd, paket_belanja_detail_sub.idpaket_belanja_detail_sub, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.jumlah AS total_anggaran, "" AS sisa_anggaran, budget_realization_detail.total_realization_detail AS total_sekarang, "" AS sisa_akhir, budget_realization_detail.realization_detail_description AS realization_detail_description');
					$npd = $this->db->get('npd');
					// echo "<pre>"; print_r($this->db->last_query());die;




					// $this->db->where('transaction_detail.iduraian', $idsub_kategori);
					// $this->db->where('paket_belanja_detail_sub.idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
					// $this->db->where('npd.idnpd', $idnpd);
					// $this->db->where('npd.status = "1" ');
					// $this->db->where('npd_detail.status = "1" ');
					// $this->db->where('verification.status = "1" ');
					// $this->db->where('verification_detail.status = "1" ');
					// $this->db->where('transaction.status = "1" ');
					// $this->db->where('transaction_detail.status = "1" ');
					// $this->db->where('sub_kategori.status = "1" ');

					// $this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
					// $this->db->join('verification', 'verification.idverification = npd_detail.idverification');
					// $this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
					// $this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
					// $this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');
					// $this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = transaction_detail.iduraian');
					// $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idsub_kategori = transaction_detail.iduraian');

					// $this->db->select('npd.idnpd, paket_belanja_detail_sub.idpaket_belanja_detail_sub, transaction_detail.iduraian, sub_kategori.nama_sub_kategori, paket_belanja_detail_sub.jumlah AS total_anggaran, "" AS sisa_anggaran, transaction_detail.total AS total_sekarang, "" AS sisa_akhir');
					// $npd = $this->db->get('npd');
					// // echo "<pre>"; print_r($this->db->last_query());die;

					
					foreach ($npd->result() as $n_key => $n_value) {

						// Total anggaran => Total pagu anggaran yang sudah ditentukan di menu paket belanja
						// Sisa Anggaran => total anggaran - sisa anggaran sebelum ada pencairan dana ini
						// Total Sekarang => total pencairan dana pada npd ini
						// Sisa Akhir => sisa anggaran - total sekarang

						$the_filter = array(
							'npd_date_created' => $npd_date_created,
							'idpaket_belanja' => $idpaket_belanja,
							'iduraian' => $n_value->idsub_kategori,
							'idnpd' => $n_value->idnpd,
						);

						$sisa_anggaran = $this->calculate_sisa_anggaran($the_filter);

						// $sisa_anggaran = 0;
						$sisa_akhir = $n_value->total_anggaran - ($sisa_anggaran + $n_value->total_sekarang);

						$arr_detail_sub[] = array(
							'idpaket_belanja' => $idpaket_belanja,
							'iduraian' => $n_value->idsub_kategori,
							'idpaket_belanja_detail' => $idpaket_belanja_detail,
							'idpaket_belanja_detail_sub' => $n_value->idpaket_belanja_detail_sub,
							'nama_sub_kategori' => $n_value->nama_sub_kategori,
							'total_anggaran' => $n_value->total_anggaran,
							'sisa_anggaran' => $sisa_anggaran,
							'total_sekarang' => $n_value->total_sekarang,
							'sisa_akhir' => $sisa_akhir,
							'realization_detail_description' => $n_value->realization_detail_description,
						);

						$total_data++;
					}
				}
				
				$arr_detail = array();
				if ($detail_sub->num_rows() > 0) {
					if (strlen($nama_kategori) > 0) {
						$total_data++;
					}

					$arr_detail[] = array(
						'nama_kategori' => $nama_kategori,
						'arr_detail_sub' => $arr_detail_sub,
					);
				}

				if (count($arr_detail) > 0 && count($arr_detail_sub) > 0) {
					$total_data++;

					$arr_data[] = array(
						'no_rekening_akunbelanja' => $value->no_rekening_akunbelanja,
						'nama_akun_belanja' => $value->nama_akun_belanja,
						'total_data' => $total_data,
						'arr_detail' => $arr_detail,
					);

					$total_data = 0;
				}
			}
			// echo "<pre>"; print_r($arr_data);die;

			$the_data = array(
				'nomor_surat' => $npd_code,
				'npd_date' => $npd_date,
				'pptk' => az_get_config('PPTK', 'config'),
				'program' => $nama_program,
				'kegiatan' => $nama_kegiatan,
				'sub_kegiatan' => $nama_subkegiatan,
				'nomor_dpa' => az_get_config('nomor_DPA', 'config'),
				'tahun_anggaran' => az_get_config('tahun_anggaran', 'config'),
				'arr_data' => $arr_data,
			);

			// echo "<pre>"; print_r($the_data);die;
			$this->load->view('npd/v_label', $the_data);
		}
	}

	// mapping bulan Indonesia
	function bulanIndo($tanggal) {
		$bulan = [
			'January' => 'Januari',
			'February' => 'Februari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember'
		];
		
		// replace nama bulan Inggris ke Indonesia
		return strtr($tanggal, $bulan);
	}

    private function generate_transaction_code() {

		$kode_surat_NPD = az_get_config('kode_surat_NPD', 'config'); 
		$tahun_anggaran = az_get_config('tahun_anggaran', 'config'); 

		$this->db->where('month(npd_date_created)', Date('m'));
		$this->db->where('year(npd_date_created)', Date('Y'));
		$this->db->where('npd_status IS NOT NULL ');
		$this->db->order_by('no_urut desc');	
		$data = $this->db->get('npd', 1);
		if ($data->num_rows() == 0) {
			$numb = 1;

			// Format jadi 2 digit
			$npd_code = $kode_surat_NPD .'/'. Date('m'). '.' . str_pad($numb, 2, '0', STR_PAD_LEFT) .'/PPTK/'. $tahun_anggaran;

			$this->db->where('npd_code', $npd_code);
			$this->db->select('npd_code');
			$check = $this->db->get('npd');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$numb += 1;

				$npd_code = $kode_surat_NPD .'/'. Date('m'). '.' . str_pad($numb, 2, '0', STR_PAD_LEFT) .'/PPTK/'. $tahun_anggaran;

				$this->db->where('npd_code', $npd_code);
				$this->db->select('npd_code');
				$check = $this->db->get('npd');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->no_urut;
			$numb = $last + 1;

			$npd_code = $kode_surat_NPD .'/'. Date('m'). '.' . str_pad($numb, 2, '0', STR_PAD_LEFT) .'/PPTK/'. $tahun_anggaran;

			$this->db->where('npd_code', $npd_code);
			$this->db->select('npd_code');
			$check = $this->db->get('npd');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$numb += 1;

				$npd_code = $kode_surat_NPD .'/'. Date('m'). '.' . str_pad($numb, 2, '0', STR_PAD_LEFT) .'/PPTK/'. $tahun_anggaran;

				$this->db->where('npd_code', $npd_code);
				$this->db->select('npd_code');
				$check = $this->db->get('npd');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		
		return $npd_code;
	}

	private function generate_urutan() {

		$this->db->where('month(npd_date_created)', Date('m'));
		$this->db->where('year(npd_date_created)', Date('Y'));
		$this->db->where('npd_status IS NOT NULL ');
		$this->db->order_by('no_urut desc');	
		$data = $this->db->get('npd', 1);
		if ($data->num_rows() == 0) {
			$numb = 1;
		}
		else {
			$last = $data->row()->no_urut;
			$numb = $last + 1;
		}
		
		return $numb;
	}

	function calculate_total_anggaran($idnpd) {

		$this->db->where('npd_detail.idnpd', $idnpd);
		$this->db->where('npd_detail.status', 1);
		$this->db->where('verification.status', 1);
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization.realization_status != "DRAFT" ');
		$this->db->where('verification.verification_status != "DRAFT" ');

		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('budget_realization', 'budget_realization.idbudget_realization = verification.idbudget_realization');
		
		$this->db->select('sum(budget_realization.total_realization) as total_realization');
		$npd_detail = $this->db->get('npd_detail');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$total_anggaran = azobj($npd_detail->row(), 'total_realization', 0);

		return $total_anggaran;
	}

	function calculate_sisa_anggaran($the_data) {
		$npd_date_created = azarr($the_data, 'npd_date_created');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja');
		$iduraian = azarr($the_data, 'iduraian');
		$idnpd = azarr($the_data, 'idnpd');

		$this->db->where('date(npd.npd_date_created) < "'.Date('Y-m-d H:i:s', strtotime($npd_date_created)).'"');
		$this->db->where('transaction_detail.idpaket_belanja = "'.$idpaket_belanja.'" ');
		$this->db->where('transaction_detail.iduraian = "'.$iduraian.'" ');
		$this->db->where('npd.idnpd != "'.$idnpd.'" ');

		$this->db->where('npd.npd_status = "SUDAH DIBAYAR BENDAHARA" ');
		$this->db->where('npd.status = 1 ');
		$this->db->where('npd_detail.status = 1 ');
		$this->db->where('verification.status = 1 ');
		$this->db->where('verification_detail.status = 1 ');
		$this->db->where('transaction.status = 1 ');
		$this->db->where('transaction_detail.status = 1 ');

		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');
		$this->db->join('transaction_detail', 'transaction_detail.idtransaction = transaction.idtransaction');

		$this->db->group_by('transaction_detail.iduraian');
		$this->db->order_by('iduraian');
		
		$this->db->select('SUM(transaction_detail.total) AS total_per_uraian');
		$npd = $this->db->get('npd');
		
		$sisa_anggaran = azobj($npd->row(), 'total_per_uraian');

		if ($sisa_anggaran == null) {
			$sisa_anggaran = 0;
		}

		// echo "<pre>"; print_r($sisa_anggaran);die;

		return $sisa_anggaran;
	}
}
