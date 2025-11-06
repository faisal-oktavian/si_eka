<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_contract extends CI_Controller {
	public function __construct() {	
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('purchase_contract');
        $this->table = 'contract';
        $this->controller = 'purchase_contract';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tanggal Kontrak', 'Nomor Kontrak', 'Detail', 'Status', 'Admin', azlang('Action')));
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
		$crud->add_aodata('contract_code', 'contract_code');
		$crud->add_aodata('vf_contract_status', 'vf_contract_status');

		$vf = $this->load->view('purchase_contract/vf_purchase_contract', $data, true);
        $crud->set_top_filter($vf);

        $idrole = $this->session->userdata('idrole');

		if (!aznav('role_view_purchase_contract') || strlen($idrole) == 0) {
			$btn = "<button class='btn btn-primary az-btn-primary btn-add-contract' type='button'><span class='glyphicon glyphicon-plus'></span> Tambah</button>";
			$crud->set_btn_top_custom($btn);
		}

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'purchase_contract';
		$view = $this->load->view('purchase_contract/v_format_purchase_contract', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('purchase_contract/vjs_purchase_contract');
		$azapp->add_js($js);

		$data_header['title'] = 'Kontrak Pengadaan';
		$data_header['breadcrumb'] = array('purchase_contract');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$contract_code = $this->input->get('contract_code');
		$contract_status = $this->input->get('vf_contract_status');

        $crud->set_select('contract.idcontract, date_format(contract_date, "%d-%m-%Y %H:%i:%s") as txt_contract_date, contract_code, "" as detail, contract_status, user_created.name as user_input');
        $crud->set_select_table('idcontract, txt_contract_date, contract_code, detail, contract_status, user_input');
        $crud->set_sorting('contract_code, contract_status, user_input');
        $crud->set_filter('contract_code, contract_status, user_input');
		$crud->set_id($this->controller);
		// $crud->set_select_align(', , , center, center');

        $crud->add_join_manual('user user_created', 'contract.iduser_created = user_created.iduser', 'left');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(contract.contract_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(contract.contract_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($contract_code) > 0) {
			$crud->add_where('contract.contract_code = "' . $contract_code . '"');
		}
		if (strlen($contract_status) > 0) {
			$crud->add_where('contract.contract_status = "' . $contract_status . '"');
		}

		$crud->add_where("contract.status = 1");
		$crud->add_where("contract.contract_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('contract_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		
		$idcontract = azarr($data, 'idcontract');
		$read_more = false;

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

                $this->db->select('paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, purchase_plan_detail.volume');
                $contract_detail = $this->db->get('contract_detail');
                // echo "<pre>"; print_r($this->db->last_query());die;

				if ($contract_detail->num_rows() > 3) {
					$read_more = true;
				}
				$last_query = $this->db->last_query();
				$contract_detail_limit = $this->db->query('SELECT * FROM ('.$last_query.') as new_query limit 3 ');


                $arr_detail = array();
                foreach ($contract_detail_limit->result() as $key => $c_value) {
                    $arr_detail[] = array(
                        'purchase_plan_code' => $value->purchase_plan_code,
                        'nama_paket_belanja' => $c_value->nama_paket_belanja,
                        'nama_sub_kategori' => $c_value->nama_sub_kategori,
                        'volume' => $c_value->volume,
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
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";
			
			foreach ((array) $arr_data as $key => $value) {
                foreach ($value['arr_detail'] as $key => $dvalue) {
                    $table .= "<tr>";
                    $table .=       "<td>".$dvalue['purchase_plan_code']."</td>";
                    $table .=       "<td>".$dvalue['nama_paket_belanja']."</td>";
                    $table .=       "<td>".$dvalue['nama_sub_kategori']."</td>";
                    $table .=       "<td align='center'>".az_thousand_separator($dvalue['volume'])."</td>";
                    $table .= "</tr>";
                }
			}

			$table .=	"</tbody>";
			$table .= "</table>";

			if ($read_more) {
				$table .= '<a href="purchase_contract/edit/'.$idcontract.'/view_only">Selengkapnya...</a>';
			}

			return $table;
		}

		if ($key == 'contract_status') {
			$lbl = 'default';
			$tlbl = '-';
			if ($value == "KONTRAK PENGADAAN") {
				$lbl = 'warning';
				$tlbl = 'Kontrak Pengadaan';
			}
			// else if ($value == "MENUNGGU PEMBAYARAN") {
			// 	$lbl = 'info';
			// 	$tlbl = 'Menunggu Pembayaran';
			// }
			// else if ($value == "SUDAH DIBAYAR BENDAHARA") {
			// 	$lbl = 'success';
			// 	$tlbl = 'Sudah Dibayar Bendahara';
			// }
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
			$is_viewonly = false;

			$btn = '';
			$idrole = $this->session->userdata('idrole');

		    if (!aznav('role_view_purchase_contract') || strlen($idrole) == 0) {
				$btn .= '<button class="btn btn-default btn-xs btn-edit-contract" data_id="'.$idcontract.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
				$btn .= '<button class="btn btn-danger btn-xs btn-delete-contract" data_id="'.$idcontract.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

				// // INPUT DATA, MENUNGGU PEMBAYARAN, SUDAH DIBAYAR BENDAHARA
				// if (in_array($npd_status, array("MENUNGGU PEMBAYARAN", "SUDAH DIBAYAR BENDAHARA") ) ) {
				// 	$is_viewonly = true;
				// }
			}
			else {
				$is_viewonly = true;
			}

			if ($is_viewonly) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-contract" data_id="'.$idcontract.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';
			}

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
        
		$view = $this->load->view('purchase_contract/v_purchase_contract', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('purchase_contract/v_purchase_contract_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add_contract');
		$modal->set_modal_title('Tambah Kontrak Pengadaan');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('purchase_contract/vjs_purchase_contract_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Kontrak Pengadaan';
		$data_header['breadcrumb'] = array('purchase_contract');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

    function search_dokumen() {
		$keyword = $this->input->get("term");

		$this->db->group_start();
		$this->db->like('paket_belanja.nama_paket_belanja', $keyword);
		$this->db->or_like('purchase_plan.purchase_plan_code', $keyword);
		$this->db->group_end();

		$this->db->where('purchase_plan.purchase_plan_status = "PROSES PENGADAAN" ');
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('paket_belanja.status', 1);

		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');

		$this->db->group_by('purchase_plan.idpurchase_plan, purchase_plan.purchase_plan_code');
		
		$this->db->select('purchase_plan.idpurchase_plan as id, purchase_plan.purchase_plan_code as text');
		$data = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

    function select_dokumen() {
		$idpurchase_plan = $this->input->post('idpurchase_plan');

		$this->db->where('purchase_plan.idpurchase_plan', $idpurchase_plan);
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('paket_belanja.status', 1);

		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
        $this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

		$this->db->select('purchase_plan.idpurchase_plan, purchase_plan.purchase_plan_code, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, purchase_plan_detail.volume');
		$data['purchase_plan'] = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query());die;

        $view = $this->load->view('purchase_contract/v_select_document_table', $data, true);

		$arr = array(
			'data' => $view,
            'idpurchase_plan' => $data['purchase_plan']->row()->idpurchase_plan,
		);
		echo json_encode($arr);
	}

    function add_save() {
		$err_code = 0;
		$err_message = '';

	 	$idcontract = $this->input->post('idcontract');
	 	$idcontract_detail = $this->input->post('idcontract_detail');
		$idpurchase_plan = $this->input->post('idpurchase_plan');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idpurchase_plan', 'Rencana Pengadaan', 'required');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		// if ($err_code == 0) {
		// 	$this->db->where('idnpd',$idnpd);
		// 	$npd = $this->db->get('npd');

		// 	if ($npd->num_rows() > 0) {
		// 		$status = $npd->row()->npd_status;
		// 		if (in_array($status, array('MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 			$err_code++;
		// 			$err_message = "Data tidak bisa diedit atau dihapus.";
		// 		}
		// 	}	
		// }

		if ($err_code == 0) {

			if (strlen($idcontract) == 0) {
				$arr_npd = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'contract_date' => Date('Y-m-d H:i:s'),
					'contract_status' => 'DRAFT',
					'contract_code' => $this->generate_code(),
				);

				$save_contract = az_crud_save($idcontract, 'contract', $arr_npd);
				$idcontract = azarr($save_contract, 'insert_id');
			}
			else {
				// validasi apakah sudah ada dokumen yang diinputkan
				// dalam 1 transaksi hanya boleh ada 1 dokumen yang diinputkan
				// validasi ini tidak berlaku jika edit dokumen
				
				if ($idcontract_detail == '') {
					$this->db->where('idcontract', $idcontract);
					$this->db->where('status', 1);
					$contract_detail = $this->db->get('contract_detail');
					// echo "<pre>"; print_r($this->db->last_query()); die;
					
					if ($contract_detail->num_rows() == 1) {
						$err_code++;
						$err_message = "Transaksi ini hanya diperbolehkan memiliki 1 dokumen saja.";
					}
				}
			}
            
			if ($err_code == 0) {
				//transaction detail
				$arr_contract_detail = array(
					'idcontract' => $idcontract,
					'idpurchase_plan' => $idpurchase_plan,
				);
				
				$contract_detail = az_crud_save($idcontract_detail, 'contract_detail', $arr_contract_detail);
				$idcontract_detail = azarr($contract_detail, 'insert_id');
				

				// // cek apakah datanya baru diinput / edit data
				// $this->db->where('idnpd', $idnpd);
				// $check = $this->db->get('npd');

				// if ($check->row()->npd_status != "DRAFT") {
				// 	$the_filter = array(
				// 		'idnpd' => $idnpd,
				// 		'type' => 'INPUT NPD'
				// 	);
				// 	$update_status = update_status_verifikasi_dokumen($the_filter);
				// }
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idcontract' => $idcontract,
			'idcontract_detail' => $idcontract_detail,
		);
		echo json_encode($return);
	}

    function save_contract() {
		$err_code = 0;
		$err_message = '';

		
		$idcontract = $this->input->post("hd_idcontract");
		$contract_date = az_crud_date($this->input->post("contract_date"));
		$iduser_created = $this->input->post("iduser_created");

		$contract_spt = $this->input->post("contract_spt");
		$contract_invitation_number = $this->input->post("contract_invitation_number");
		$contract_sp = $this->input->post("contract_sp");
		$contract_spk = $this->input->post("contract_spk");
		$contract_honor = $this->input->post("contract_honor");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('contract_date', 'Tanggal Kontrak', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

        if ($err_code == 0) {

            if ($contract_honor == "TIDAK") {
                if (strlen($contract_spt) == 0 && strlen($contract_invitation_number) == 0 && strlen($contract_sp) == 0 && strlen($contract_spk) == 0) {
                    $err_code++;
                    $err_message = "Pilih salah satu inputan dari SPT / No. Undangan / SP / SPK.";
                }
            }
        }

        if ($err_code == 0) {
            $data_inputan = 0;
            if (strlen($contract_spt) > 0) {
                $data_inputan++;
            }
            if (strlen($contract_invitation_number) > 0) {
                $data_inputan++;
            }
            if (strlen($contract_sp) > 0) {
                $data_inputan++;
            }
            if (strlen($contract_spk) > 0) {
                $data_inputan++;
            }
            if ($contract_honor == "YA") {
                $data_inputan++;
            }

            if ($data_inputan > 1) {
                $err_code++;
                $err_message = "Hanya diisi dari salah satu inputan SPT / No. Undangan / SP / SPK / Gaji Honor.";  
            }
        }

		if ($err_code == 0) {
			if (strlen($idcontract) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		// if ($err_code == 0) {
		// 	$this->db->where('idnpd',$idnpd);
		// 	$npd = $this->db->get('npd');

		// 	if ($npd->num_rows() > 0) {
		// 		$status = $npd->row()->npd_status;
		// 		if (in_array($status, array('MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 			$err_code++;
		// 			$err_message = "Data tidak bisa diedit atau dihapus.";
		// 		}
		// 	}	
		// }

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'contract_date' => $contract_date,
	    		'contract_status' => "KONTRAK PENGADAAN",
	    		'iduser_created' => $iduser_created,
	    		'contract_spt' => $contract_spt ? $contract_spt : null,
                'contract_invitation_number' => $contract_invitation_number ? $contract_invitation_number : null,
                'contract_sp' => $contract_sp ? $contract_sp : null,
                'contract_spk' => $contract_spk ? $contract_spk : null,
                'contract_honor' => $contract_honor ? $contract_honor : null,
	    	);

	    	az_crud_save($idcontract, 'contract', $arr_data);


			// // update status verifikasi dokumen
			// $the_filter = array(
			// 	'idcontract' => $idcontract,
			// 	'type' => 'KONTRAK PENGADAAN'
			// );
			// $update_status = update_status_verifikasi_dokumen($the_filter);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

    function edit_data() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where('idcontract_detail', $id);
		
		$this->db->select('idcontract_detail, idcontract, idpurchase_plan');
		$contract_detail = $this->db->get('contract_detail')->result_array();

		$ret = array(
			'data' => azarr($contract_detail, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

    function delete_data() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = "";
		$message = "";
		$is_delete = true;

		$this->db->where('idcontract_detail',$id);
		$this->db->join('contract', 'contract_detail.idcontract = contract.idcontract');
		$contract = $this->db->get('contract_detail');

		$status = $contract->row()->contract_status;
		$idcontract = $contract->row()->idcontract;
		// if (in_array($status, array('MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 	$is_delete = false;
		// }

		if ($is_delete) {
			$delete = az_crud_delete('contract_detail', $id, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				
				// // update status verifikasi dokumen
				// $the_filter = array(
				// 	'idcontract' => $idcontract,
				// 	'idcontract_detail' => $id,
				// 	'type' => 'INPUT NPD'
				// );
				// $update_status = update_status_verifikasi_dokumen($the_filter);
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada dokumen/detail transaksi di kontrak pengadaan ini?
		if ($err_code == 0) {
			$this->db->where('idcontract', $idcontract);
			$this->db->where('status', 1);
			$contract_detail = $this->db->get('contract_detail');

			if ($contract_detail->num_rows() == 0) {
				$arr_update = array(
					'contract_status' => 'DRAFT',
				);
				az_crud_save($idcontract, 'contract', $arr_update);

				$message = 'Dokumen berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan dokumen baru, harap klik simpan transaksi kontrak pengadaan, agar datanya tidak hilang.</span>';
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
		);

		echo json_encode($return);
	}

    function edit($id) {
		$this->db->where('idcontract', $id);
		$check = $this->db->get('contract');
		if ($check->num_rows() == 0) {
			redirect(app_url().'contract');
		} 
		else if($this->uri->segment(4) != "view_only") {
			// $status = $check->row()->npd_status;
			// if (in_array($status, array('MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
			// 	redirect(app_url().'npd');
			// }
		}
		$this->add($id);
	}

    function get_data() {
		$id = $this->input->post('id');

		$this->db->where('contract.idcontract', $id);
		$this->db->join('user', 'user.iduser = contract.iduser_created');
		$this->db->select('date_format(contract_date, "%d-%m-%Y %H:%i:%s") as txt_contract_date, contract_code, user.name as user_created, contract.iduser_created, contract_spt, contract_invitation_number, contract_sp, contract_spk, contract_honor');
		$this->db->order_by('contract_date', 'desc');
		$contract = $this->db->get('contract')->result_array();

		$this->db->where('idcontract', $id);
		$contract_detail = $this->db->get('contract_detail')->result_array();

		$return = array(
			'contract' => azarr($contract, 0),
			'contract_detail' => $contract_detail
		);
		echo json_encode($return);
	}

    function delete_contract() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('idcontract',$id);
		$contract = $this->db->get('contract');

		// if ($contract->num_rows() > 0) {
		// 	$status = $contract->row()->contract_status;
		// 	if (in_array($status, array('MENUNGGU PEMBAYARAN', 'SUDAH DIBAYAR BENDAHARA') ) ) {
		// 		$err_code++;
		// 		$err_message = "Data tidak bisa diedit atau dihapus.";
		// 	}
		// }

		if($err_code == 0) {
			// // kembalikan status realisasi anggaran
			// $the_filter = array(
			// 	'idcontract' => $id,
			// 	'type' => 'SUDAH DIVERIFIKASI'
			// );
			// $update_status = update_status_verifikasi_dokumen($the_filter);

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

    

    function get_list_data() {
		$idcontract = $this->input->post("idcontract");

        $this->db->where('contract.idcontract', $idcontract);
		$this->db->where('contract.status', 1);
		$this->db->where('contract_detail.status', 1);
		$this->db->where('contract.contract_status != "DRAFT" ');
		
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

            $this->db->select('paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, purchase_plan_detail.volume');
            $contract_detail = $this->db->get('contract_detail');
            // echo "<pre>"; print_r($this->db->last_query());die;

            $arr_detail = array();
            foreach ($contract_detail->result() as $key => $c_value) {
                $arr_detail[] = array(
                    'nama_paket_belanja' => $c_value->nama_paket_belanja,
                    'nama_sub_kategori' => $c_value->nama_sub_kategori,
                    'volume' => $c_value->volume,
                );
            }

            $arr_data[] = array(
                'idcontract' => $value->idcontract,
                'idcontract_detail' => $value->idcontract_detail,
                'purchase_plan_code' => $value->purchase_plan_code,
                'arr_detail' => $arr_detail,
            );
        }
        // echo "<pre>"; print_r($arr_data);die;

        $data['arr_data'] = $arr_data;

		$view = $this->load->view('purchase_contract/v_purchase_contract_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

    private function generate_code() {
		$this->db->where('day(contract_date)', Date('d'));
		$this->db->where('month(contract_date)', Date('m'));
		$this->db->where('year(contract_date)', Date('Y'));
		$this->db->where('contract_code IS NOT NULL ');
		$this->db->order_by('contract_code desc');
		$data = $this->db->get('contract', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$contract_code = 'PC'.Date('Ymd').$numb;

			$this->db->where('contract_code', $contract_code);
			$this->db->select('contract_code');
			$check = $this->db->get('contract');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($contract_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$contract_code = 'PC'.Date('Ymd').$numb;

				$this->db->where('contract_code', $contract_code);
				$this->db->select('contract_code');
				$check = $this->db->get('contract');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->contract_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$contract_code = 'PC'.Date('Ymd').$numb;

			$this->db->where('contract_code', $contract_code);
			$this->db->select('contract_code');
			$check = $this->db->get('contract');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($contract_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$contract_code = 'PC'.Date('Ymd').$numb;

				$this->db->where('contract_code', $contract_code);
				$this->db->select('contract_code');
				$check = $this->db->get('contract');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

        // Purchase Contract => PC

		return $contract_code;
	}
}
