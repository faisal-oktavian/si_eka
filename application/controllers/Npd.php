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

		$crud->set_column(array('#', 'Tanggal NPD', 'Nomor NPD', 'Detail', 'Status NPD', 'User Input', azlang('Action')));
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

		$vf = $this->load->view('npd/vf_npd', $data, true);
        $crud->set_top_filter($vf);

		if (aznav('role_crud')) {
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

        $crud->set_select('npd.idnpd, date_format(npd_date_created, "%d-%m-%Y %H:%i:%s") as txt_date_input, npd_code, "" as detail, npd_status, user_created.name as user_input');

        $crud->set_select_table('idnpd, txt_date_input, npd_code, detail, npd_status, user_input');
        $crud->set_sorting('npd_code, detail, npd_status, user_input');
        $crud->set_filter('npd_code, detail, npd_status, user_input');
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

		$crud->add_where("npd.status = 1");
		$crud->add_where("npd.npd_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('npd_date_created desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		
		if ($key == 'detail') {
			$idnpd = azarr($data, 'idnpd');
			
			$this->db->where('npd_detail.idnpd', $idnpd);	
			$this->db->where('npd_detail.status', 1);
			
			$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
			$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
			$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
			$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
			$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');

			$this->db->group_by('npd.npd_status, verification.verification_code, paket_belanja.nama_paket_belanja');

			$this->db->select('npd.npd_status, verification.verification_code, paket_belanja.nama_paket_belanja');
			$npd_detail = $this->db->get('npd');

			$table = "<table class='table table-bordered table-condensed' id='table_dokumen'>";
			$table .=	"<thead>";
			$table .=		"<tr>";
			$table .=			"<th width='120px;'>Nomor Dokumen</th>";
			$table .=			"<th width='auto'>Nama Paket Belanja</th>";
			$table .=		"</tr>";
			$table .=	"</thead>";
			$table .=	"<tbody>";
			
			foreach ((array) $npd_detail->result_array() as $key => $value) {
				$table .=	"<tr>";
				$table .=		"<td>".$value['verification_code']."</td>";
				$table .=		"<td>".$value['nama_paket_belanja']."</td>";
				$table .=	"</tr>";
			}

			$table .=	"</tbody>";
			$table .= "</table>";

			return $table;
		}

		if ($key == 'npd_status') {
			$lbl = 'default';
			$tlbl = '-';
			if ($value == "INPUT DATA") {
				$lbl = 'warning';
				$tlbl = 'Input Data';
			}
			else if ($value == "MENUNGGU PEMBAYARAN") {
				$lbl = 'info';
				$tlbl = 'Menunggu Pembayaran';
			}
			else if ($value == "SUDAH DIBAYAR BENDAHARA") {
				$lbl = 'success';
				$tlbl = 'Sudah Dibayar Bendahara';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'action') {
            $idnpd = azarr($data, 'idnpd');
			$is_viewonly = false;

			$btn = '';
			if (aznav('role_crud')) {
				$btn .= '<button class="btn btn-default btn-xs btn-edit_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
				$btn .= '<button class="btn btn-danger btn-xs btn-delete_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';

				$this->db->where('idnpd', $idnpd);
				$npd = $this->db->get('npd');

				$npd_status = $npd->row()->npd_status;
				// INPUT DATA, MENUNGGU PEMBAYARAN, SUDAH DIBAYAR BENDAHARA
				if (in_array($npd_status, array("MENUNGGU PEMBAYARAN", "SUDAH DIBAYAR BENDAHARA") ) ) {
					$is_viewonly = true;
				}

				if ($npd_status == "INPUT DATA") {
					$btn .= '<button class="btn btn-info btn-xs btn-send_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-send"></span> Kirim ke bendahara</button>';
				}
			}
			else {
				$is_viewonly = true;	
			}

			if ($is_viewonly) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-npd" data_id="'.$idnpd.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';
			}

			$btn .= '<button class="btn btn-success btn-xs btn-print_npd" data_id="'.$idnpd.'"><span class="glyphicon glyphicon-print"></span> Cetak</button>';

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

		$v_modal = $this->load->view('npd/v_npd_modal', '', true);
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

	function edit($id) {
		$this->db->where('idnpd', $id);
		$check = $this->db->get('npd');
		if ($check->num_rows() == 0) {
			redirect(app_url().'npd');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->npd_status;
			if ($status == "SUDAH DIBAYAR BENDAHARA") {
				redirect(app_url().'npd');
			}
		}
		$this->add($id);
	}

    function search_dokumen() {
		$keyword = $this->input->get("term");

		$this->db->group_start();
		$this->db->like('nama_paket_belanja', $keyword);
		$this->db->or_like('verification.verification_code', $keyword);
		$this->db->group_end();

		$this->db->where('verification.status', 1);
		$this->db->where('verification_detail.status', 1);
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');

		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
		$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');

		$this->db->group_by('verification.idverification, verification.verification_code, paket_belanja.nama_paket_belanja');
		
		$this->db->select('verification.idverification as id, concat(verification.verification_code, " - ", paket_belanja.nama_paket_belanja) as text');
		$data = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

    function select_dokumen() {
		$id = $this->input->post('id');

		$this->db->where('verification.idverification', $id);
		$this->db->where('verification.status', 1);
		$this->db->where('verification_detail.status', 1);
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction_detail.status', 1);
		$this->db->where('verification.verification_status = "SUDAH DIVERIFIKASI" ');
		$this->db->where('verification.status_approve = "DISETUJUI" ');

		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
		$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');

		$this->db->group_by('verification.idverification, verification.verification_code, paket_belanja.nama_paket_belanja');
		
		$this->db->select('verification.idverification as idverification, verification.verification_code, paket_belanja.nama_paket_belanja');
		$data = $this->db->get('verification');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$ret = array(
			'idverification' => $data->row()->idverification,
			'verification_code' => $data->row()->verification_code,
			'nama_paket_belanja' => $data->row()->nama_paket_belanja,
		);

		echo json_encode($ret);
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
				if ($status == "SUDAH DIBAYAR BENDAHARA") {
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
				//transaction detail
				$arr_npd_detail = array(
					'idnpd' => $idnpd,
					'idverification' => $idverification,
				);
				
				$td = az_crud_save($idnpd_detail, 'npd_detail', $arr_npd_detail);
				$idnpd_detail = azarr($td, 'insert_id');
				

				// // cek apakah datanya baru diinput / edit data
				// $this->db->where('idverification', $idverification);
				// $check = $this->db->get('verification');

				// if ($check->row()->verification_status != "DRAFT") {
				// 	$the_filter = array(
				// 		'idverification' => $idverification,
				// 		'type' => 'MENUNGGU VERIFIKASI'
				// 	);
				// 	$update_status = update_status_pake_belanja($the_filter);
				// }
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
			$this->db->where('idnpd',$idnpd);
			$npd = $this->db->get('npd');

			if ($npd->num_rows() > 0) {
				$status = $npd->row()->npd_status;
				if ($status == "SUDAH DIBAYAR BENDAHARA") {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'npd_date_created' => $npd_date_created,
	    		'npd_status' => "INPUT DATA",
	    		'iduser_created' => $iduser_created,
	    	);

	    	az_crud_save($idnpd, 'npd', $arr_data);

			// // update status realisasi anggaran
			// $the_filter = array(
			// 	'idverification' => $idverification,
			// 	'type' => 'MENUNGGU VERIFIKASI'
			// );
			// $update_status = update_status_pake_belanja($the_filter);

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

		$this->db->where('idnpd',$id);
		$npd = $this->db->get('npd');

		if ($npd->num_rows() > 0) {
			$status = $npd->row()->npd_status;
			if ($status == "SUDAH DIBAYAR BENDAHARA") {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";
			}
		}

		if($err_code == 0) {
			// kembalikan status realisasi anggaran
			$this->db->where('idverification', $id);
			$verif_detail = $this->db->get('verification_detail');

			// foreach ($verif_detail->result() as $key => $value) {
			// 	$idtransaction = $value->idtransaction;

			// 	$update_data = array(
			// 		'transaction_status' => 'INPUT DATA',
			// 		'updated_status' => date('Y-m-d H:i:s'),
			// 	);
				
			// 	$this->db->where('idtransaction', $idtransaction);
			// 	$this->db->update('transaction', $update_data);
			// }

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
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('idnpd',$id);
		$npd = $this->db->get('npd');

		if ($npd->num_rows() > 0) {
			$status = $npd->row()->npd_status;
			if ($status == "SUDAH DIBAYAR BENDAHARA") {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";
			}
		}

		if($err_code == 0) {
			// hitung total anggaran
			$total_anggaran = $this->calculate_total_anggaran($id);

			
			// update status npd
			$arr_data = array(
				'npd_status' => 'MENUNGGU PEMBAYARAN',
				'updated' => Date('Y-m-d H:i:s'),
				'updatedby' => $this->session->userdata('username'),
				'total_anggaran' => $total_anggaran,
			);
			
			$this->db->where('idnpd', $id);
			$this->db->update('npd', $arr_data);

			// update status realisasi anggaran
			// $the_filter = array(
			// 	'idverification' => $idverification,
			// 	'type' => 'MENUNGGU PEMBAYARAN'
			// );
			// $update_status = update_status_pake_belanja($the_filter);

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
		$is_delete = true;

		$this->db->where('idnpd_detail',$id);
		$this->db->join('npd', 'npd_detail.idnpd = npd.idnpd');
		$npd = $this->db->get('npd_detail');

		$status = $npd->row()->npd_status;
		$idnpd = $npd->row()->idnpd;
		if ($status == "SUDAH DIBAYAR BENDAHARA") {
			$is_delete = false;
		}

		if ($is_delete) {
			$delete = az_crud_delete('npd_detail', $id, true);

			$err_code = $delete['err_code'];
			$err_message = $delete['err_message'];

			if ($err_code == 0) {
				// update status realisasi anggaran
				// $the_filter = array(
				// 	'idverification' => $idverification,
				// 	'idverification_detail' => $id,
				// 	'type' => 'INPUT DATA'
				// );
				// $update_status = update_status_pake_belanja($the_filter);	
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
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

		$this->db->where('npd_detail.idnpd', $idnpd);	
		$this->db->where('npd_detail.status', 1);
		
		$this->db->join('npd_detail', 'npd_detail.idnpd = npd.idnpd');
		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'verification_detail.idtransaction = transaction.idtransaction');
		$this->db->join('transaction_detail', 'transaction.idtransaction = transaction_detail.idtransaction');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = transaction_detail.idpaket_belanja');

		$this->db->group_by('npd.npd_status, npd_detail.idnpd_detail, verification_detail.idverification, verification.verification_code, paket_belanja.nama_paket_belanja');

		$this->db->select('npd.npd_status, npd_detail.idnpd_detail, verification_detail.idverification, verification.verification_code, paket_belanja.nama_paket_belanja');
		$npd_detail = $this->db->get('npd');
		// echo "<pre>"; print_r($this->db->last_query()); die;

        $data['detail'] = $npd_detail->result_array();

		$view = $this->load->view('npd/v_npd_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
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
		$this->db->where('verification_detail.status', 1);
		$this->db->where('transaction.status', 1);
		$this->db->where('transaction.transaction_status != "DRAFT" ');
		$this->db->where('verification.verification_status != "DRAFT" ');

		$this->db->join('verification', 'verification.idverification = npd_detail.idverification');
		$this->db->join('verification_detail', 'verification_detail.idverification = verification.idverification');
		$this->db->join('transaction', 'transaction.idtransaction = verification_detail.idtransaction');

		$this->db->select('sum(transaction.total_realisasi) as total_anggaran');
		$npd_detail = $this->db->get('npd_detail');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		$total_anggaran = azobj($npd_detail->row(), 'total_anggaran', 0);

		return $total_anggaran;
	}
}
