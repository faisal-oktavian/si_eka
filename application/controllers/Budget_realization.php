<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_realization extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('budget_realization');
        $this->table = 'budget_realization';
        $this->controller = 'budget_realization';
        $this->load->helper('az_crud');
        $this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('idrole');

		$crud->set_column(array('#', 'Tanggal Realisasi', 'Nomor Realisasi', 'Keterangan', 'Detail', 'Total Realisasi', 'Keterangan Uraian', 'Status', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		if (aznav('role_view_budget_realization') && strlen($idrole) > 0) {
			$crud->set_btn_add(false);
		}

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
		$crud->add_aodata('vf_realization_code', 'vf_realization_code');
		$crud->add_aodata('vf_realization_status', 'vf_realization_status');
		$crud->add_aodata('iduser_created', 'iduser_created');

		$vf = $this->load->view('budget_realization/vf_budget_realization', $data, true);
        $crud->set_top_filter($vf);

		$v_modal = $this->load->view('budget_realization/v_description_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('description');
		$modal->set_modal_title('Keterangan');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_description'=>'Simpan'));
		$azapp->add_content($modal->render());

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'budget_realization';
		$view = $this->load->view('budget_realization/v_format_budget_realization', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('budget_realization/vjs_budget_realization');
		$azapp->add_js($js);

		$data_header['title'] = 'Realisasi Anggaran';
		$data_header['breadcrumb'] = array('budget_realization');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$realization_code = $this->input->get('vf_realization_code');
		$realization_status = $this->input->get('vf_realization_status');
		$iduser_created = $this->input->get('iduser_created');

        $crud->set_select('budget_realization.idbudget_realization, date_format(realization_date, "%d-%m-%Y %H:%i:%s") as txt_realization_date, realization_code, "" as type_code, "" as detail, total_realization, "" as description, realization_status, user.name as user_created, budget_realization.iduser_created, budget_realization.notes');
		$crud->set_select_table('idbudget_realization, txt_realization_date, realization_code, type_code, detail, total_realization, description, realization_status, user_created');

        $crud->set_sorting('realization_date, realization_code, total_realization, realization_status');
        $crud->set_filter('realization_date, realization_code, total_realization, realization_status');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , , right, , center');

        $crud->add_join_manual('user', 'budget_realization.iduser_created = user.iduser');
        // $crud->add_join_manual('budget_realization_detail', 'budget_realization.idbudget_realization = budget_realization_detail.idbudget_realization');
		
        // $crud->set_group_by('transaction.idtransaction, transaction.transaction_date, realization_code, paket_belanja.nama_paket_belanja, total_realisasi, user.name');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(budget_realization.realization_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(budget_realization.realization_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($realization_code) > 0) {
			$crud->add_where('budget_realization.realization_code = "' . $realization_code . '"');
		}
		if (strlen($realization_status) > 0) {
			$crud->add_where('budget_realization.realization_status = "' . $realization_status . '"');
		}
		if (strlen($iduser_created) > 0) {
			$crud->add_where('budget_realization.iduser_created = "' . $iduser_created . '"');
		}

		$crud->add_where("budget_realization.status = 1");
		// $crud->add_where("budget_realization_detail.status = 1");
		$crud->add_where("budget_realization.realization_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('realization_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('idrole');
		$idbudget_realization = azarr($data, 'idbudget_realization');
		$realization_status = azarr($data, 'realization_status');
		$read_more = false;
		$is_view_only = false;
		$is_btn_description = false;

		$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization_detail.status', 1);

		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

		$this->db->select('budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail, budget_realization_detail.realization_detail_description, contract_spt, contract_invitation_number, contract_sp, contract_spk, contract_honor');
		$budget_realization = $this->db->get('budget_realization');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$last_query = $this->db->last_query();
		$budget_realization_limit = $this->db->query('SELECT * FROM ('.$last_query.') as new_query limit 3 ');

		
		if ($key == 'total_realization') {
			$total_realization = az_thousand_separator($value);

			return $total_realization;
		}

		if ($key == "realization_status") {
			$status = label_status($value);
			
			return $status;
		}

		if ($key == "description") {
			return $budget_realization_limit->row()->realization_detail_description;
		}

		if ($key == "type_code") {
			$contract_spt = $budget_realization->row()->contract_spt;
			$contract_invitation_number = $budget_realization->row()->contract_invitation_number;
			$contract_sp = $budget_realization->row()->contract_sp;
			$contract_spk = $budget_realization->row()->contract_spk;
			$contract_honor = $budget_realization->row()->contract_honor;
			
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

			
			$notes = azarr($data, 'notes');
			if (strlen($notes) > 0) {
				$text .= "<hr style='border-top: 2px solid #000000'>";
				$text .= "Catatan : ".$notes." ";
			}


			return $text;
		}

		if ($key == 'detail') {
			if ($budget_realization->num_rows() > 3) {
				$read_more = true;
			}
			
			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th width='auto'>Nomor Kontrak</th>";
			// $table .=			"<th width='auto'>Nomor Rencana</th>";
			$table .=			"<th width='180px'>Paket Belanja</th>";
			$table .=			"<th width='200px'>Uraian</th>";
			$table .=			"<th width='60px'>Volume</th>";
			// $table .=			"<th width='150px'>Keterangan</th>";
			$table .=			"<th width='150px'>Total Detail</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";

            foreach ($budget_realization_limit->result_array() as $key => $value) {
				$table .= "<tr>";
				$table .= 		"<td align='left'>".$value['contract_code']."</td>";
				// $table .= 		"<td>".$value['purchase_plan_code']."</td>";
				$table .= 		"<td align='left'>".$value['nama_paket_belanja']."</td>";
				$table .= 		"<td align='left'>".$value['nama_sub_kategori']."</td>";
				$table .= 		"<td align='center'>".az_thousand_separator_decimal($value['volume'])."</td>";
				// $table .= 		"<td align='left'>".$value['realization_detail_description']."</td>";
				$table .= 		"<td align='right'>".az_thousand_separator($value['total_realization_detail'])."</td>";
				$table .= "</tr>";
            }
			
			$table .=	"</tbody>";
			$table .= "</table>";

			if ($read_more) {
				$table .= '<a href="budget_realization/edit/'.$idbudget_realization.'/view_only">Selengkapnya...</a>';
			}

			return $table;
		}

		if ($key == 'action') {
			$btn = '<button class="btn btn-default btn-xs btn-edit-budget-realization" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-budget-realization" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

			$the_filter = array(
				'menu' => 'REALISASI ANGGARAN',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($realization_status, $arr_validation) ) {
				$is_view_only = true;
				$is_btn_description = true;
            }

			if (aznav('role_view_budget_realization') && strlen($idrole) > 0) {
				$is_view_only = true;
				$is_btn_description = false;
			}

			// cek apakah data yg ditampilkan adalah data milik user itu sendiri
			$iduser_created = azarr($data, 'iduser_created');
			if ( ($this->session->userdata('iduser') != $iduser_created) && ($this->session->userdata('username') != 'superadmin' || strlen($idrole) > 1) ) {
				$is_view_only = true;
				$is_btn_description = false;
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-budget-realization" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
			}
			
			$btn_description = '';
			if ($is_btn_description) {
				$btn_description = '<button class="btn btn-success btn-xs btn-edit-description" data_id="'.$idbudget_realization.'"><span class="glyphicon glyphicon-pencil"></span> Edit Keterangan</button>';
			}

			return $btn.$btn_description;
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
        
		$view = $this->load->view('budget_realization/v_budget_realization', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('budget_realization/v_budget_realization_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add_realization');
		$modal->set_modal_title('Tambah Realisasi Anggaran');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());

		// get data purchase_contract
		$purchase_contract = $azapp->add_crud();
		$purchase_contract->set_column(array('#', "Tanggal Kontrak", "Nomor Kontrak", "Detail"));
		$purchase_contract->set_th_class(array('', '', '', '', '', '', '', '', ''));
		$purchase_contract->set_width('auto, 100px, 100px, auto');
		$purchase_contract->set_id($this->controller . '2');
		$purchase_contract->set_default_url(false);
		$purchase_contract->set_btn_add(false);

		$purchase_contract->set_url("app_url+'budget_realization/get_contract'");
		$purchase_contract->set_url_edit("app_url+'budget_realization/edit_contract'");
		$purchase_contract->set_url_delete("app_url+'budget_realization/delete_contract'");
		$purchase_contract->set_url_save("app_url+'budget_realization/save_contract'");
		// $purchase_contract->set_callback_table_complete('callback_check_contract_table();');
		$data_add['contract'] = $purchase_contract->render();

		$v_modal_2 = $this->load->view('budget_realization/v_select_contract_modal', $data_add, true);
		$modal = $azapp->add_modal();
		$modal->set_id('select_contract');
		$modal->set_modal_title('Cari Kontrak Pengadaan');
		$modal->set_modal($v_modal_2);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('budget_realization/vjs_budget_realization_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Realisasi Anggaran';
		$data_header['breadcrumb'] = array('budget_realization');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get_contract()
	{
		$this->load->library('AZApp');
		$crud_contract = $this->azapp->add_crud();

		$crud_contract->set_select('contract.idcontract, contract.idcontract as txt_idcontract, date_format(contract_date, "%d-%m-%Y %H:%i:%s") as txt_contract_date, contract_code, "" as detail');
        $crud_contract->set_select_table('idcontract, txt_idcontract, txt_contract_date, contract_code, detail');
        $crud_contract->set_sorting('contract_code');
        $crud_contract->set_filter('contract_code');
		$crud_contract->set_id($this->controller . '2');
		$crud_contract->set_select_align(', , , right, center');
		$crud_contract->set_custom_first_column(true);

        $crud_contract->add_join_manual('user user_created', 'contract.iduser_created = user_created.iduser', 'left');
        
		$crud_contract->add_where("contract.status = 1");
		$crud_contract->add_where("contract.contract_status IN ('KONTRAK PENGADAAN', 'DITOLAK VERIFIKATOR') ");
		$crud_contract->add_where("YEAR(contract.created) = '".Date('Y')."' ");

		$crud_contract->set_table('contract');
		$crud_contract->set_custom_style('custom_style_contract');
		$crud_contract->set_order_by('contract_date desc');
		echo $crud_contract->get_table();
	}

	function custom_style_contract($key, $value, $data) {
		$idcontract = azarr($data, 'idcontract');
		$contract_code = azarr($data, 'contract_code');

		if ($key == 'txt_idcontract') {
			$id = '<button class="btn btn-success btn-xs btn-select-contract" type="button" data_id="'.$idcontract.'" data_code="'.$contract_code.'"> Pilih</button>';

			return $id;
		}

		if ($key == 'detail') {
            $this->db->where('contract.idcontract', $idcontract);
            $this->db->where('contract.status', 1);
            $this->db->where('contract.contract_status != "DRAFT"');
            $this->db->where('contract_detail.status', 1);
            
            $this->db->join('contract_detail', 'contract_detail.idcontract = contract.idcontract');
            $this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
            
            $this->db->group_by('contract.idcontract, contract_detail.idcontract_detail, purchase_plan.purchase_plan_code');
            $this->db->select('contract.idcontract, contract_detail.idcontract_detail, purchase_plan.purchase_plan_code');
            $contract = $this->db->get('contract');
            // echo "<pre>"; print_r($this->db->last_query());die;

            $arr_data = array();
            foreach ($contract->result() as $key => $value) {

                $this->db->where('contract_detail.idcontract_detail', $value->idcontract_detail);
                $this->db->where('contract_detail.status', 1);
                $this->db->where('purchase_plan.status', 1);
                $this->db->where('purchase_plan_detail.status', 1);
                $this->db->where('paket_belanja.status', 1);

                $this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
                $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
                $this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
                $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
                $this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

                $this->db->select('paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, purchase_plan_detail.volume,  purchase_plan_detail.purchase_plan_detail_total');
                $contract_detail = $this->db->get('contract_detail');
                // echo "<pre>"; print_r($this->db->last_query());die;

                $arr_detail = array();
                foreach ($contract_detail->result() as $key => $c_value) {
                    $arr_detail[] = array(
                        'purchase_plan_code' => $value->purchase_plan_code,
                        'nama_paket_belanja' => $c_value->nama_paket_belanja,
                        'nama_sub_kategori' => $c_value->nama_sub_kategori,
                        'volume' => $c_value->volume,
                        'total' => $c_value->purchase_plan_detail_total,
                    );
                }

                $arr_data[] = array(
                    'arr_detail' => $arr_detail,
                );
            }
            // echo "<pre>"; print_r($arr_data);die;

			$table = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th width='100px;'>Nomor Rencana</th>";
			$table .=			"<th width='300px;'>Nama Paket Belanja</th>";
			$table .=			"<th width='200px'>Uraian</th>";
			$table .=			"<th width='50px'>Volume</th>";
			$table .=			"<th width='50px'>Total Detail</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";
			
			foreach ((array) $arr_data as $key => $value) {
                foreach ($value['arr_detail'] as $key => $dvalue) {
                    $table .= "<tr>";
                    $table .=       "<td>".$dvalue['purchase_plan_code']."</td>";
                    $table .=       "<td>".$dvalue['nama_paket_belanja']."</td>";
                    $table .=       "<td>".$dvalue['nama_sub_kategori']."</td>";
                    $table .=       "<td align='center'>".az_thousand_separator_decimal($dvalue['volume'])."</td>";
                    $table .=       "<td align='right'>".az_thousand_separator($dvalue['total'])."</td>";
                    $table .= "</tr>";
                }
			}

			$table .=	"</tbody>";
			$table .= "</table>";

			return $table;
		}

		return $value;
	}

	function select_paket_belanja() {
		$idpaket_belanja = $this->input->post('idpaket_belanja');
		$idsub_kategori = $this->input->post('idsub_kategori');
		$idcontract_detail = $this->input->post('idcontract_detail');

		$this->db->where('paket_belanja.idpaket_belanja', $idpaket_belanja);
		$this->db->where('paket_belanja_detail_sub.idsub_kategori', $idsub_kategori);
		$this->db->where('contract_detail.idcontract_detail', $idcontract_detail);
		$this->db->where('contract_detail.status', 1);
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('paket_belanja_detail_sub.status', 1);

		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');

		$this->db->select('urusan_pemerintah.idurusan_pemerintah, urusan_pemerintah.nama_urusan, bidang_urusan.idbidang_urusan, bidang_urusan.nama_bidang_urusan, program.idprogram, program.nama_program, kegiatan.idkegiatan, kegiatan.nama_kegiatan, sub_kegiatan.idsub_kegiatan, sub_kegiatan.nama_subkegiatan, paket_belanja.idpaket_belanja, paket_belanja.nama_paket_belanja, sub_kategori.idsub_kategori, sub_kategori.nama_sub_kategori, purchase_plan_detail.idpurchase_plan_detail, purchase_plan_detail.volume, paket_belanja_detail_sub.harga_satuan, purchase_plan_detail.purchase_plan_detail_total');
		$contract_detail = $this->db->get('contract_detail');
        // echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'nama_urusan' => $contract_detail->row()->nama_urusan,
			'nama_bidang_urusan' => $contract_detail->row()->nama_bidang_urusan,
			'nama_program' => $contract_detail->row()->nama_program,
			'nama_kegiatan' => $contract_detail->row()->nama_kegiatan,
			'nama_subkegiatan' => $contract_detail->row()->nama_subkegiatan,
			'nama_paket_belanja' => $contract_detail->row()->nama_paket_belanja,
			'nama_sub_kategori' => $contract_detail->row()->nama_sub_kategori,
			'idsub_kategori' => $contract_detail->row()->idsub_kategori,
			'volume' => $contract_detail->row()->volume,
			'unit_price' => $contract_detail->row()->harga_satuan,
			'total_realization_detail' => $contract_detail->row()->purchase_plan_detail_total,
			'idpaket_belanja' => $idpaket_belanja,
		);

		echo json_encode($ret);
	}

	function add_realization() {
		$err_code = 0;
		$err_message = '';
		$validate_gender = false;
		$validate_description = false;
		$validate_room = false;
		$validate_name_training = false;
		
		
		$idbudget_realization = $this->input->post('idbudget_realization');
		$idbudget_realization_detail = $this->input->post('idbudget_realization_detail');
		$realization_date = az_crud_date($this->input->post('realization_date'));
		
		$idcontract = $this->input->post('idcontract');
		$idpaket_belanja_detail_sub = $this->input->post('idpaket_belanja_detail_sub');
		$data_idcontract = $this->input->post('data_idcontract');
		$data_idcontract_detail = $this->input->post('data_idcontract_detail');
		$data_idpurchase_plan = $this->input->post('data_idpurchase_plan');
		$data_idpurchase_plan_detail = $this->input->post('data_idpurchase_plan_detail');
		$data_idpaket_belanja = $this->input->post('data_idpaket_belanja');
		$data_idpaket_belanja_detail_sub = $this->input->post('data_idpaket_belanja_detail_sub');
		$data_idsub_kategori = $this->input->post('data_idsub_kategori');

		$provider = $this->input->post('provider');
		$idruang = $this->input->post('idruang');
		$training_name = $this->input->post('training_name');
		$volume = az_crud_number($this->input->post('volume'));
		$male = az_crud_number($this->input->post('male'));
		$female = az_crud_number($this->input->post('female'));
		$unit_price = az_crud_number($this->input->post('unit_price'));
		$ppn = az_crud_number($this->input->post('ppn'));
		$pph = az_crud_number($this->input->post('pph'));
		$realization_detail_description = $this->input->post('realization_detail_description');

		// $total_realization_detail = (floatval($volume) * floatval($unit_price)) + floatval($ppn) - floatval($pph);
		$total_realization_detail = (floatval($volume) * floatval($unit_price));


		$this->load->library('form_validation');
		$this->form_validation->set_rules('idcontract', 'Nomor Kontrak', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('data_idsub_kategori', 'Uraian Belanja', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('provider', 'Penyedia', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('volume', 'Volume', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('unit_price', 'Harga Satuan', 'required|trim|max_length[200]');

		// validasi apakah inputan laki-laki dan perempuan wajib diisi
		if (strlen($data_idsub_kategori) > 0) {
			// $this->db->where('status', 1);
			$this->db->where('idsub_kategori', $data_idsub_kategori);
			$this->db->select('is_gender, is_description, is_room, is_name_training');
			$sub_kategori = $this->db->get('sub_kategori');

			if ($sub_kategori->num_rows() > 0) {
				// 0 : tidak wajib isi jenis kelamin
				// 1 : wajib isi jenis kelamin
				if ($sub_kategori->row()->is_gender == 1) {
					$validate_gender = true;
				}

				// 0 : tidak wajib isi keterangan
				// 1 : wajib isi keterangan
				if ($sub_kategori->row()->is_description == 1) {
					$validate_description = true;
				}

				// 0 : tidak wajib isi ruang
				// 1 : wajib isi ruang
				if ($sub_kategori->row()->is_room == 1) {
					$validate_room = true;
				}

				// 0 : tidak wajib isi nama diklat
				// 1 : wajib isi nama diklat
				if ($sub_kategori->row()->is_name_training == 1) {
					$validate_name_training = true;
				}
			}
			else {
				$err_code++;
				$err_message = "Uraian tidak ditemukan.";
			}
		}

		if ($validate_gender) {
			$this->form_validation->set_rules('male', 'Laki-laki', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('female', 'Perempuan', 'required|trim|max_length[200]');
		}
		if ($validate_description) {
			$this->form_validation->set_rules('realization_detail_description', 'Keterangan', 'required');
		}
		if ($validate_room) {
			$this->form_validation->set_rules('idruang', 'Nama Ruang', 'required|trim|max_length[200]');
		}
		if ($validate_name_training) {
			$this->form_validation->set_rules('training_name', 'Nama Diklat', 'required|trim|max_length[200]');
		}

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		// // validasi jumlah laki-laki & perempuan tidak boleh lebih dari volume
		// if ($err_code == 0) {
		// 	if ($validate_gender) {
		// 		if (floatval($volume) != (floatval($male) + floatval($female)) ) {
		// 			$err_code++;
		// 			$err_message = "Jumlah inputan total laki-laki dan perempuan tidak sama dengan inputan volume.";
		// 		}
		// 	}
		// }

		if ($err_code == 0) {			
			$the_filter = array(
				'idsub_kategori' => $data_idsub_kategori,
				'idpaket_belanja' => $data_idpaket_belanja,
				'transaction_date' => $realization_date,
				'idpurchase_plan_detail' => $data_idpurchase_plan_detail,
				'idbudget_realization_detail' => $idbudget_realization_detail,
			);
			// var_dump($the_filter);die;

			// ambil data DPA
			$data_utama = $this->get_data_utama($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			// ambil data uraian belanja yang sudah direalisasikan
			$data_realisasi = $this->get_data_rencana($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			// validasi apakah jumlah yang sudah direalisasikan melebihi jumlah yang sudah ditentukan
			if ($data_utama->row()->jumlah < (floatval($data_realisasi->row()->total_realization_detail) + floatval($total_realization_detail))) {
				$err_code++;
				$err_message = "Total Biaya yang direalisasikan melebihi jumlah dari DPA.";
			}
			// var_dump($data_utama->row()->jumlah.' < ('.floatval($data_realisasi->row()->total_realization_detail).' + '.floatval($total_realization_detail).')'); echo "<br><br>";
		}
// var_dump($err_message);die;


		// validasi tanggal realisasi tidak boleh melebihi tanggal hari ini
		if ($err_code == 0) {
			if (strtotime($realization_date) > strtotime(date('Y-m-d H:i:s'))) {
				$err_code++;
				$err_message = "Tanggal realisasi tidak boleh melebihi tanggal hari ini.";
			}
		}

		if ($err_code == 0) {
			$this->db->where('idbudget_realization',$idbudget_realization);
			$realization = $this->db->get('budget_realization');

			if ($realization->num_rows() > 0) {
				$status = $realization->row()->realization_status;

				$the_filter = array(
					'menu' => 'REALISASI ANGGARAN',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		// validasi volume realisasi tidak boleh lebih dari kontrak pengadaan
		if ($err_code == 0) {
			$this->db->where('contract_detail.idcontract_detail', $data_idcontract_detail);
			$this->db->where('paket_belanja_detail_sub.idsub_kategori', $data_idsub_kategori);

			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			
			$this->db->select('purchase_plan_detail.volume, contract_detail.idcontract, purchase_plan.idpurchase_plan');
			$contract_detail = $this->db->get('contract_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;
			
			if ($volume > $contract_detail->row()->volume) {
				$err_code++;
				$err_message = "Volume realisasi tidak boleh lebih dari volume pada kontrak pengadaan.";
			}
		}
		
		if ($err_code == 0) {

			if (strlen($idbudget_realization) == 0) {
				$arr_realization = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'realization_date' => Date('Y-m-d H:i:s'),
					'realization_status' => 'DRAFT',
					'realization_code' => $this->generate_code(),
				);

				$save_realization = az_crud_save($idbudget_realization, 'budget_realization', $arr_realization);
				$idbudget_realization = azarr($save_realization, 'insert_id');
			}
			else {
				// validasi apakah data paket belanja yang disimpan sama?
				// jika tidak maka data tidak perlu disimpan

				// $this->db->where('status', 1);
				$this->db->where('idbudget_realization', $idbudget_realization);
				$this->db->where('idcontract_detail', $data_idcontract_detail);
				$rd = $this->db->get('budget_realization_detail');
				// echo "<pre>"; print_r($this->db->last_query());die;

				if ($rd->num_rows() == 0) {
					$err_code++;
					$err_message = "Paket Belanja yang anda inputkan berbeda dengan paket belanja yang telah diinputkan sebelumnya. <br>";
					$err_message .= "Silahkan menginputkan data dengan paket belanja yang sama.";
				}
			}
            
			if ($err_code == 0) {
				// cek apakah datanya baru diinput / edit data
				$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
				$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
				$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
				$check = $this->db->get('budget_realization');

				if (strlen($idbudget_realization_detail) > 0) {
					if ($check->row()->realization_status != "DRAFT") {
						
						// kembalikan dulu status dari realisasi anggaran yang sudah dipilih sebelumnya
						$the_filter = array(
							'idcontract' => $check->row()->idcontract,
							'idpurchase_plan_detail' => $check->row()->idpurchase_plan_detail,
							'idpurchase_plan' => $check->row()->idpurchase_plan,
							'status' => 'KONTRAK PENGADAAN'
						);
						$update_status = update_status_detail_purchase_contract($the_filter);


						// update status kontrak pengadaan
						update_status_purchase_contract($the_filter);
					}
				}

				//detail
				$arr_realization_detail = array(
					'idbudget_realization' => $idbudget_realization,
					'idcontract_detail' => $data_idcontract_detail,
					'idpurchase_plan_detail' => $data_idpurchase_plan_detail,
					'provider' => $provider,
					'idsub_kategori' => $data_idsub_kategori,
					'idruang' => $idruang ? $idruang : null,
					'training_name' => $training_name ? $training_name : null,
					'volume' => $volume,
					'male' => $male ? $male : null,
					'female' => $female ? $female : null,
					'unit_price' => $unit_price,
					'ppn' => $ppn ? $ppn : null,
					'pph' => $pph ? $pph : null,
					'total_realization_detail' => $total_realization_detail,
					'realization_detail_description' => $realization_detail_description ? $realization_detail_description : null,
				);
				
				$save_rd = az_crud_save($idbudget_realization_detail, 'budget_realization_detail', $arr_realization_detail);
				$idbudget_realization_detail = azarr($save_rd, 'insert_id');

				// hitung total transaksi
				$this->calculate_total_realization($idbudget_realization);

				// cek apakah datanya baru diinput / edit data
				$this->db->where('idbudget_realization', $idbudget_realization);
				$check = $this->db->get('budget_realization');

				if ($check->row()->realization_status != "DRAFT") {
					
					$the_filter = array(
						'idcontract' => $contract_detail->row()->idcontract,
						'idpurchase_plan_detail' => $data_idpurchase_plan_detail,
						'idpurchase_plan' => $contract_detail->row()->idpurchase_plan,
						'status' => 'MENUNGGU VERIFIKASI'
					);
					$update_status = update_status_detail_purchase_contract($the_filter);


					// update status kontrak pengadaan
					update_status_purchase_contract($the_filter);
					
				}
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idbudget_realization' => $idbudget_realization,
			'idbudget_realization_detail' => $idbudget_realization_detail,
		);
		echo json_encode($return);
	}

	function edit_order() {
		$idrealization_detail = $this->input->post("idrealization_detail");

		$err_code = 0;
		$err_message = "";

		$this->db->where('budget_realization_detail.idbudget_realization_detail', $idrealization_detail);

		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = purchase_plan_detail.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$this->db->join('ruang', 'ruang.idruang = budget_realization_detail.idruang', 'left');
		
		$this->db->select('
			budget_realization_detail.idbudget_realization,
			budget_realization_detail.provider,
			budget_realization_detail.idruang,
			budget_realization_detail.training_name,
			budget_realization_detail.volume,
			budget_realization_detail.male,
			budget_realization_detail.female,
			budget_realization_detail.unit_price,
			budget_realization_detail.ppn,
			budget_realization_detail.pph,
			budget_realization_detail.total_realization_detail,
			budget_realization_detail.realization_detail_description,
			
			ruang.nama_ruang as ajax_idruang,

			contract.idcontract, 
			contract.contract_code as ajax_idcontract, 
			contract_detail.idcontract_detail, 
			
			purchase_plan_detail.idpaket_belanja_detail_sub, 
			concat(purchase_plan.purchase_plan_code, " → ", paket_belanja.nama_paket_belanja, " → ", sub_kategori.nama_sub_kategori) as ajax_idpaket_belanja_detail_sub, 
			
			paket_belanja.idpaket_belanja, 
			
			sub_kategori.idsub_kategori, 
			
			purchase_plan.idpurchase_plan, 
			purchase_plan_detail.idpurchase_plan_detail');
		$rd = $this->db->get('budget_realization_detail')->result_array();
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$ret = array(
			'data' => azarr($rd, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		// echo "<pre>"; print_r($ret); die;

		echo json_encode($ret);
	}

	function delete_order() {
		$idrealization_detail = $this->input->post('idrealization_detail');

		$err_code = 0;
		$err_message = "";
		$is_delete = true;
		$message = '';

		$this->db->where('idbudget_realization_detail', $idrealization_detail);
		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$budget_realization = $this->db->get('budget_realization');

		if ($budget_realization->num_rows() == 0) {
			$err_code++;
			$err_message = "Invalid ID";

			$is_delete = false;
		}

		if ($err_code == 0) {
			$status = $budget_realization->row()->realization_status;
			$idbudget_realization = $budget_realization->row()->idbudget_realization;
			$idcontract_detail = $budget_realization->row()->idcontract_detail;
			$idpurchase_plan_detail = $budget_realization->row()->idpurchase_plan_detail;
			$idbudget_realization_detail = $budget_realization->row()->idbudget_realization_detail;
			$idcontract = $budget_realization->row()->idcontract;
			$idpurchase_plan_detail = $budget_realization->row()->idpurchase_plan_detail;
			$idpurchase_plan = $budget_realization->row()->idpurchase_plan;

			$the_filter = array(
				'menu' => 'REALISASI ANGGARAN',
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
			$the_filter = array(
				'idcontract' => $idcontract,
				'idpurchase_plan_detail' => $idpurchase_plan_detail,
				'idpurchase_plan' => $idpurchase_plan,
				'status' => 'KONTRAK PENGADAAN'
			);
			$update_status = update_status_detail_purchase_contract($the_filter);


			// update status kontrak pengadaan
			// update_status_purchase_contract($the_filter);
			$this->db->where('contract_detail.idcontract', $idcontract);
			$this->db->where('purchase_plan_detail.idpurchase_plan', $idpurchase_plan);
			$this->db->where('purchase_plan_detail.purchase_plan_detail_status = "KONTRAK PENGADAAN" ');

			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');

			$data_contract = $this->db->get('contract_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;
			
			if ($data_contract->num_rows() > 0) {
				$update_status = "KONTRAK PENGADAAN";

				$arr_update = array(
					'contract_status' => $update_status,
				);

				$this->db->where('idcontract', $idcontract);
				$this->db->update('contract', $arr_update);

				$the_filter = array(
					'idpurchase_plan' => $idpurchase_plan,
					'status' => $update_status,
				);

				update_status_purchase_plan($the_filter);
			}

			$delete = az_crud_delete('budget_realization_detail', $idbudget_realization_detail, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				// hitung total transaksi
				$this->calculate_total_realization($idbudget_realization);
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada paket belanja/detail transaksi di realisasi anggaran ini?
		if ($err_code == 0) {
			$this->db->where('idbudget_realization', $idbudget_realization);
			$this->db->where('status', 1);
			$budget_realization_detail = $this->db->get('budget_realization_detail');

			if ($budget_realization_detail->num_rows() == 0) {
				$arr_update = array(
					'realization_status' => 'DRAFT',
				);
				az_crud_save($idbudget_realization, 'budget_realization', $arr_update);

				$message = 'Paket Belanja berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan paket belanja baru, harap klik simpan transaksi realisasi anggaran, agar datanya tidak hilang.</span>';
			}
		}	

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
			'idbudget_realization' => $idbudget_realization,
		);

		echo json_encode($return);
	}

	function save_realization() {
		$err_code = 0;
		$err_message = '';

		
		$idbudget_realization = $this->input->post("hd_idbudget_realization");
		$realization_date = az_crud_date($this->input->post("realization_date"));
		$iduser_created = $this->input->post("iduser_created");
		$notes = $this->input->post("notes");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('realization_date', 'Tanggal Realisasi', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idbudget_realization) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		if ($err_code == 0) {
			$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
			$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
			$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
			$realization = $this->db->get('budget_realization');

			if ($realization->num_rows() > 0) {
				$status = $realization->row()->realization_status;

				$the_filter = array(
					'menu' => 'REALISASI ANGGARAN',
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
	    	$arr_data = array(
	    		'realization_date' => $realization_date,
	    		'realization_status' => "MENUNGGU VERIFIKASI",
	    		'iduser_created' => $iduser_created,
	    		'notes' => $notes,
	    	);

	    	az_crud_save($idbudget_realization, 'budget_realization', $arr_data);

			// hitung total transaksi
			$this->calculate_total_realization($idbudget_realization);

			// update status detail kontrak pengadaan
			// update statusnya di table detail rencana pengadaan
			foreach ($realization->result() as $key => $value) {
				$the_filter = array(
					'idcontract' => $value->idcontract,
					'idpurchase_plan_detail' => $value->idpurchase_plan_detail,
					'idpurchase_plan' => $value->idpurchase_plan,
					'status' => 'MENUNGGU VERIFIKASI'
				);
				$update_status = update_status_detail_purchase_contract($the_filter);
			}

			// update status kontrak pengadaan
			update_status_purchase_contract($the_filter);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function save_description() {
		$err_code = 0;
		$err_message = '';

		
		$idbudget_realization = $this->input->post("idbudget_realization");
		$realization_detail_description = $this->input->post("realization_detail_description");

		if (strlen($idbudget_realization) == 0) {
			$err_code++;
			$err_message = 'Invalid ID';
		}

		if ($err_code == 0) {
			$arr_data = array(
				'realization_detail_description' => $realization_detail_description,
			);

			$this->db->where('status', 1);
			$this->db->where('idbudget_realization', $idbudget_realization);
			$this->db->update('budget_realization_detail', $arr_data);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
		);
		echo json_encode($return);
	}

	function edit($id) {
		$this->db->where('idbudget_realization', $id);
		$check = $this->db->get('budget_realization');
		if ($check->num_rows() == 0) {
			redirect(app_url().'budget_realization');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->realization_status;

			$the_filter = array(
				'menu' => 'REALISASI ANGGARAN',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($status, $arr_validation) ) {
				redirect(app_url().'budget_realization');
			}
		}
		$this->add($id);
	}

	function get_data() {
		$idbudget_realization = $this->input->post('idbudget_realization');
		
		$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$this->db->join('user', 'user.iduser = budget_realization.iduser_created');
		$this->db->select('date_format(realization_date, "%d-%m-%Y %H:%i:%s") as txt_realization_date, realization_code, user.name as user_created, budget_realization.iduser_created');
		$this->db->order_by('realization_date', 'desc');
		$budget_realization = $this->db->get('budget_realization')->result_array();

		$return = array(
			'data' => azarr($budget_realization, 0),
		);
		echo json_encode($return);
	}

	function delete_realization() {
		$idbudget_realization = $this->input->post('idbudget_realization');

		$err_code = 0;
		$err_message = '';

		$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$budget_realization = $this->db->get('budget_realization');
		
		if ($budget_realization->num_rows() > 0) {
			$status = $budget_realization->row()->realization_status;

			$the_filter = array(
				'menu' => 'REALISASI ANGGARAN',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($status, $arr_validation) ) {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";
			}
		}

		// // cek apakah sudah ada data di tabel verification & detail
		// if ($err_code == 0) {
		// 	$this->db->where('verification_detail.idtransaction', $id);
		// 	$verif_detail = $this->db->get('verification_detail');

		// 	if ($verif_detail->num_rows() > 0) {
		// 		$idverification = $verif_detail->row()->idverification;
		// 		az_crud_delete('verification', $idverification);
		// 	}
		// }

		if($err_code == 0) {
			// update status detail kontrak pengadaan
			// update statusnya di table detail rencana pengadaan
			foreach ($budget_realization->result() as $key => $value) {
				$idcontract = $value->idcontract;
				$idpurchase_plan_detail = $value->idpurchase_plan_detail;
				$idpurchase_plan = $value->idpurchase_plan;
				$update_status = 'KONTRAK PENGADAAN';

				$the_filter = array(
					'idcontract' => $idcontract,
					'idpurchase_plan_detail' => $idpurchase_plan_detail,
					'idpurchase_plan' => $idpurchase_plan,
					'status' => $update_status
				);
				update_status_detail_purchase_contract($the_filter);
			}

			// update status kontrak pengadaan
			// update_status_purchase_contract($the_filter);
			$this->db->where('contract_detail.idcontract', $idcontract);
			$this->db->where('purchase_plan_detail.idpurchase_plan', $idpurchase_plan);
			$this->db->where('purchase_plan_detail.purchase_plan_detail_status = "KONTRAK PENGADAAN" ');

			$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');

			$data_contract = $this->db->get('contract_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;
			
			if ($data_contract->num_rows() > 0) {
				$arr_update = array(
					'contract_status' => $update_status,
				);

				$this->db->where('idcontract', $idcontract);
				$this->db->update('contract', $arr_update);

				$the_filter = array(
					'idpurchase_plan' => $idpurchase_plan,
					'status' => $update_status,
				);

				update_status_purchase_plan($the_filter);
			}

			// delete data realisasi
			az_crud_delete($this->table, $idbudget_realization);

		} 
		else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}





	
    function get_list_data() {
		$idbudget_realization = $this->input->post("idbudget_realization");

		$this->db->where('budget_realization.idbudget_realization', $idbudget_realization);
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization_detail.status', 1);

		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

		$this->db->select('budget_realization.idbudget_realization, budget_realization.total_realization, budget_realization_detail.idbudget_realization_detail, contract.contract_code, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, budget_realization_detail.volume, budget_realization_detail.unit_price, budget_realization_detail.ppn, budget_realization_detail.pph, budget_realization_detail.total_realization_detail');
		$budget_realization = $this->db->get('budget_realization');
		// echo "<pre>"; print_r($this->db->last_query());die;

        $data['detail'] = $budget_realization->result_array();

		$total_realization = 0;
		if ($budget_realization->num_rows() > 0) {
			$total_realization = $budget_realization->row()->total_realization;
		}
		$data['total_realization'] = $total_realization;

		$view = $this->load->view('budget_realization/v_budget_realization_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function calculate_total_realization($idbudget_realization) {

		$this->db->where('status', 1);
		$this->db->where('idbudget_realization', $idbudget_realization);
		$this->db->select('sum(total_realization_detail) as total_realization');
		$rd = $this->db->get('budget_realization_detail');

		$total_realization = azobj($rd->row(), 'total_realization', 0);

		$arr_update = array(
			'total_realization' => $total_realization,
		);

		az_crud_save($idbudget_realization, 'budget_realization', $arr_update);
	}

	public function get_validate_gender() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_gender');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_gender = $sub_kategori->row()->is_gender;
		
		echo json_encode($is_gender);
	}

	public function get_validate_description() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_description');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_description = $sub_kategori->row()->is_description;
		
		echo json_encode($is_description);
	}

	public function get_validate_room() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_room');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_room = $sub_kategori->row()->is_room;
		
		echo json_encode($is_room);
	}

	public function get_validate_training() {

		$id = $this->input->get('id');

		$this->db->where('idsub_kategori', $id);
		$this->db->select('is_name_training');
		$sub_kategori = $this->db->get('sub_kategori');

		$is_name_training = $sub_kategori->row()->is_name_training;
		
		echo json_encode($is_name_training);
	}

	private function generate_code() {
		$this->db->where('day(realization_date)', Date('d'));
		$this->db->where('month(realization_date)', Date('m'));
		$this->db->where('year(realization_date)', Date('Y'));
		$this->db->where('realization_code IS NOT NULL ');
		$this->db->order_by('realization_code desc');
		$data = $this->db->get('budget_realization', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$realization_code = 'AP'.Date('Ymd').$numb;

			$this->db->where('realization_code', $realization_code);
			$this->db->select('realization_code');
			$check = $this->db->get('budget_realization');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($realization_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$realization_code = 'AP'.Date('Ymd').$numb;

				$this->db->where('realization_code', $realization_code);
				$this->db->select('realization_code');
				$check = $this->db->get('budget_realization');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->realization_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$realization_code = 'AP'.Date('Ymd').$numb;

			$this->db->where('realization_code', $realization_code);
			$this->db->select('realization_code');
			$check = $this->db->get('budget_realization');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($realization_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$realization_code = 'AP'.Date('Ymd').$numb;

				$this->db->where('realization_code', $realization_code);
				$this->db->select('realization_code');
				$check = $this->db->get('budget_realization');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

		return $realization_code;
	}   

    public function get_paket_belanja_detail_sub_parent(){
		$idpaket_belanja = $this->input->post("idpaket_belanja");
		
		// var_dump($parent);die();
		$this->db->where('paket_belanja_detail.status', 1);
		$this->db->where('paket_belanja_detail_sub.status', 1);
		$this->db->where('paket_belanja_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail = paket_belanja_detail.idpaket_belanja_detail');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
		$pb_detail = $this->db->get('paket_belanja_detail');
        // echo "<pre>"; print_r($this->db->last_query()); die;

		$arr_data = array();
		foreach ($pb_detail->result() as $key => $value) {
			
			// cek apakah ada detailnya
			$this->db->where('paket_belanja_detail_sub.status', 1);
			$this->db->where('is_idpaket_belanja_detail_sub', $value->idpaket_belanja_detail_sub);
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori', 'left');
			$dss = $this->db->get('paket_belanja_detail_sub');

			if ($dss->num_rows() == 0) {
				$arr_data[] = array(
					'idpaket_belanja_detail_sub' => $value->idpaket_belanja_detail_sub,
					'iduraian' => $value->idsub_kategori,
					'nama_uraian' => $value->nama_sub_kategori,
					'is_gender' =>$value->is_gender,
				);
			}
			else {
				foreach ($dss->result() as $dss_key => $dss_value) {

					// get nama kategori
					$this->db->where('idpaket_belanja_detail_sub', $dss_value->is_idpaket_belanja_detail_sub);
					$this->db->join('kategori', 'kategori.idkategori = paket_belanja_detail_sub.idkategori');
					$this->db->select('nama_kategori');
					$pbds = $this->db->get('paket_belanja_detail_sub');

					$nama_kategori = "";
					if ($pbds->num_rows() > 0) {
						$nama_kategori = '[Kategori: '.$pbds->row()->nama_kategori.'] ';
					}

					$arr_data[] = array(
						'idpaket_belanja_detail_sub' => $dss_value->idpaket_belanja_detail_sub,
						'iduraian' => $dss_value->idsub_kategori,
						'nama_uraian' => $nama_kategori.$dss_value->nama_sub_kategori,
						'is_gender' =>$dss_value->is_gender,
					);
				}
			}
		}

		$results = array(
		  "results" => $arr_data,
		);
        // echo "<pre>"; print_r($results);die;

		echo json_encode($results);
	}

	function get_data_utama($the_data) {
		$idsub_kategori = azarr($the_data, 'idsub_kategori', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$add_select = azarr($the_data, 'add_select', '');

		// menampilkan data utama dari paket belanja
		$this->db->where('pb.idpaket_belanja = "'.$idpaket_belanja.'" ');
		$this->db->where('(pbds_child.idsub_kategori = "'.$idsub_kategori.'" OR pbds_parent.idsub_kategori = "'.$idsub_kategori.'")');
		$this->db->join('paket_belanja_detail pbd', 'paket_belanja_detail pbd ON pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');

		if (strlen($add_select) > 0) {
			$this->db->group_by('pb.idpaket_belanja, pb.nama_paket_belanja, pbd.idpaket_belanja_detail, detail_sub_id, idsub_kategori, volume, idsatuan, harga_satuan, jumlah');
		}

		$this->db->select('pb.idpaket_belanja,
			pb.nama_paket_belanja,
			pbd.idpaket_belanja_detail,
			COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS detail_sub_id,
			COALESCE(pbds_child.idsub_kategori, pbds_parent.idsub_kategori) AS idsub_kategori,
			COALESCE(pbds_child.volume, pbds_parent.volume) AS volume,
			COALESCE(pbds_child.idsatuan, pbds_parent.idsatuan) AS idsatuan,
			COALESCE(pbds_child.harga_satuan, pbds_parent.harga_satuan) AS harga_satuan,
			COALESCE(pbds_child.jumlah, pbds_parent.jumlah) AS jumlah'.$add_select);
		$pb = $this->db->get('paket_belanja pb');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $pb;
	}

	function get_data_rencana($the_data) {
		$idsub_kategori = azarr($the_data, 'idsub_kategori', '');
		$transaction_date = azarr($the_data, 'transaction_date', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$idbudget_realization_detail = azarr($the_data, 'idbudget_realization_detail', '');

		$format_year = date("Y", strtotime($transaction_date));
		$format_month = date("m", strtotime($transaction_date));

		
		// baca data yang sudah direalisasikan karena efek satuan LS bisa ke konversi
		$this->db->where('budget_realization.status', 1);
		$this->db->where('budget_realization.realization_status != "DRAFT" ');
		$this->db->where('budget_realization_detail.status', 1);
		$this->db->where('DATE_FORMAT(budget_realization.realization_date, "%Y-%m") >=', $format_year . '-01');
		$this->db->where('DATE_FORMAT(budget_realization.realization_date, "%Y-%m") <=', $format_year . '-' . $format_month);
		$this->db->where('budget_realization_detail.idsub_kategori', $idsub_kategori);
		// $this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
		if (strlen($idbudget_realization_detail) > 0) {
			$this->db->where('budget_realization_detail.idbudget_realization_detail != "'.$idbudget_realization_detail.'" ');
		}
		/*
		|--------------------------------------------------------------------------
		| LOGIKA KHUSUS SATUAN LS
		|--------------------------------------------------------------------------
		| (satuan.nama_satuan = 'LS')
		| OR
		| (satuan.nama_satuan != 'LS' AND purchase_plan_detail.idpaket_belanja = ?)
		*/
		$this->db->group_start();
			$this->db->where('satuan.nama_satuan', 'LS');
			$this->db->or_group_start();
				$this->db->where('satuan.nama_satuan !=', 'LS');
				$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
			$this->db->group_end();
		$this->db->group_end();

		$this->db->join('budget_realization_detail', 'budget_realization_detail.idbudget_realization = budget_realization.idbudget_realization');
		$this->db->join('contract_detail', 'contract_detail.idcontract_detail = budget_realization_detail.idcontract_detail');
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan_detail = budget_realization_detail.idpurchase_plan_detail');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = purchase_plan_detail.idpurchase_plan');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		

		$this->db->select('
			count(budget_realization_detail.idbudget_realization_detail), 
			sum(budget_realization_detail.volume) as total_volume,
			sum(budget_realization_detail.total_realization_detail) as total_realization_detail,
			max(satuan.nama_satuan) as nama_satuan
		');

		$data = $this->db->get('budget_realization');
		// echo "<pre>"; print_r($this->db->last_query()); die;



		// // menampilkan data rencana yang sudah ada sampai dengan tanggal inputan
		// $this->db->where('purchase_plan.status', 1);
		// $this->db->where('purchase_plan_detail.status', 1);
		// if (strlen($idpurchase_plan_detail) > 0) {
		// 	$this->db->where('purchase_plan_detail.idpurchase_plan_detail != "'.$idpurchase_plan_detail.'" ');
		// }
		// $this->db->where('paket_belanja_detail_sub.idsub_kategori', $idsub_kategori);
		// $this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
		// $this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") >=', $format_year . '-01');
		// $this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") <=', $format_year . '-' . $format_month);
		// $this->db->where('purchase_plan.purchase_plan_status != "DRAFT" ');

		// $this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		// $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');

		// $this->db->select('
		// 	count(purchase_plan.idpurchase_plan), 
		// 	sum( COALESCE(purchase_plan_detail.volume_realization, purchase_plan_detail.volume) ) as total_volume,
		// 	sum(purchase_plan_detail.purchase_plan_detail_total) as purchase_plan_detail_total
		// ');
		// $data = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $data;
	}
}
