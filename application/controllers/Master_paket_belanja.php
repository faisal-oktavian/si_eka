<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_paket_belanja extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_paket_belanja');
        $this->table = 'paket_belanja';
        $this->controller = 'master_paket_belanja';

        // $this->load->library('encrypt');

        $this->load->helper('az_crud');
        // $this->load->helper('az_config');
        // $this->load->helper('az_security');
        // $this->is_sip = az_get_config('app_sip', 'config_app');
        // $this->is_siplite = az_get_config('app_siplite', 'config_app');
        // $this->is_offline = az_get_config('is_offline', 'config_app');
        // $this->app_sipplus = az_get_config('app_sipplus','config_app') == 1 ? true : false;
        // if ($this->app_sipplus) {
        // 	$this->load->helper('liteprint_notification');
        // }
        // $this->app_accounting = az_get_config('app_accounting', 'config_app');
		// if ($this->app_accounting) {
		// 	$this->load->helper('az_accounting');
		// }
		// $this->is_comma_price = az_get_config('is_comma_price', 'config_app');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Program', 'Kegiatan', 'Sub Kegiatan', 'Paket Belanja', 'Anggaran', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		// if (!$this->is_offline) {
		// 	$sess_idoutlet = $this->session->userdata('idoutlet');
		// 	if (strlen($sess_idoutlet) == 0) {
		// 		$v = $this->load->view('onthespot/v_onthespot_filter', '', true);
		// 		$crud->set_top_filter($v);
		// 	}
		// }

		// $date1 = $azapp->add_datetime();
		// $date1->set_id('date1');
		// $date1->set_name('date1');
		// $date1->set_format('DD-MM-YYYY');
		// $date1->set_value('01-'.Date('m-Y'));
		// $data['date1'] = $date1->render();

		// $date2 = $azapp->add_datetime();
		// $date2->set_id('date2');
		// $date2->set_name('date2');
		// $date2->set_format('DD-MM-YYYY');
		// $date2->set_value(Date('t-m-Y'));
		// $data['date2'] = $date2->render();

		// $crud->add_aodata('date1', 'date1');
		// $crud->add_aodata('date2', 'date2');
		// $crud->add_aodata('custom', 'custom');

		$vf = $this->load->view('paket_belanja/vf_paket_belanja', '', true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'pos';
		$view = $this->load->view('paket_belanja/v_format_paket_belanja', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('paket_belanja/vjs_paket_belanja');
		$azapp->add_js($js);

		$data_header['title'] = 'Paket Belanja';
		$data_header['breadcrumb'] = array('master', 'master_paket_belanja');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$custom = $this->input->get('custom');

		$idoutlet = $this->input->get('idoutlet');
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$idoutlet = $sess_idoutlet;
		}

		$crud->set_select('idpaket_belanja, nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran');
		$crud->set_select_table('idpaket_belanja, nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran');
		$crud->set_sorting('nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran');
		$crud->set_filter('nama_program, nama_kegiatan, nama_subkegiatan, nama_paket_belanja, nilai_anggaran');

		// if (strlen($custom) > 0) {
		// 	$crud->add_where('transaction_code like "'.$custom.'" or customer_name like "'.$custom.'" or customer_handphone like "'.$custom.'"');
		// }
		$crud->add_where("paket_belanja.status", 1);
		
		$crud->add_join_manual('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$crud->add_join_manual('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$crud->add_join_manual('program', 'program.idprogram = paket_belanja.idprogram');
		
		$crud->set_id($this->controller);
		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('idpaket_belanja desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		
		if ($this->is_comma_price) {
			$is_comma = 'az_thousand_separator_decimal';
		}
		else {
			$is_comma = 'az_thousand_separator';
		}
		
		if ($key == 'status_accounting') {
			$total_lack = azarr($data, 'total_lack');
			$total_pay = azarr($data, 'total_pay');
			$grand_total_price = azarr($data, 'grand_total_price');
			$transaction_status = azarr($data, 'transaction_status');

			$lbl = 'warning';
			$tlbl = 'Open';

			$not_transaction_status = !in_array($transaction_status, array("DRAFT", "BATAL ORDER") );

			if (in_array($total_lack, array(NULL, 0) ) && ($total_pay != $grand_total_price) && $not_transaction_status ) {
				$lbl = 'warning';
				$tlbl = 'Open';
			}
			else if ( ($total_pay != null) && ($total_pay != $grand_total_price) && $not_transaction_status && ($total_pay < $grand_total_price) ) {

				$lbl = 'warning';
				$tlbl = 'Partial';
			}
			else if ( ($total_pay >= $grand_total_price) && $not_transaction_status ) {
				
				$lbl = 'success';
				$tlbl = 'Paid';
			}
			else if ($transaction_status == "BATAL ORDER" ) {
				$lbl = 'danger';
				$tlbl = 'Batal Order';
			}
			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}

		if ($key == 'total_lack') {
			$grand_total_price = azarr($data, 'grand_total_price');

			if ($value < 0) {
				$value = $value * -1;
			}
			else if ($value == NULL) {
				$value = $grand_total_price;
			}
			else {
				$value = 0;
			}
			$total_lack = $is_comma($value);

			return $total_lack;
		}

		if ($key == 'grand_total_price') {
			$grand_total_price = $is_comma($value);

			return $grand_total_price;
		}

		if ($key == 'action') {
			
		}

		if ($key == 'action') {

			if ($this->app_accounting) {
				$idtransaction = azarr($data, 'idtransaction');
				$status = azarr($data, 'transaction_status');
				$is_bid = azarr($data, 'is_bid');
				$is_debt = azarr($data, 'is_debt');
				$debt = azarr($data, 'debt');
				$transaction_date = azarr($data, 'transaction_date');

				$roleedit = aznav('role_pos_edit');
				$roledelete = aznav('role_pos_delete');

				$this->db->where('status > 0');
				$this->db->order_by('idacc_clossing_book desc');
				$this->db->limit(1);
				$clossing = $this->db->get('acc_clossing_book');
				$status_clossing = false;

				$btn = $value;

				// cek sudah tutup buku atau belum
				if ($clossing->num_rows() > 0) {
					if ($clossing->row()->clossing_book_status == "CLOSED" && $clossing->row()->clossing_book_date_start <= $transaction_date && $clossing->row()->clossing_book_date_end >= $transaction_date) {
						$status_clossing = true;
					}
				}

				// cek sudah di rekonsiliasi atau belum
				$this->db->where('idtransaction', $idtransaction);
				$this->db->where('transaction_type = "PENJUALAN" ');
				$accounting = $this->db->get('acc_accounting');

				if ($accounting->num_rows() > 0) {
					if ($accounting->row()->status_reconciliation == "Terekonsiliasi") {
						$status_clossing = true;
					}
				}			

				if ($status_clossing == true) {
					$btn = '<button class="btn btn-info btn-xs btn-view-only-pos" data_id="'.$idtransaction.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';	
				}
				else {
					$status = azarr($data, 'transaction_status');
					if (in_array($status, array('PEMBAYARAN DIVERIFIKASI', 'BATAL ORDER', 'PESANAN SUDAH DIVERIFIKASI', 'SELESAI DIKERJAKAN', 'PESANAN DALAM PENGIRIMAN', 'PESANAN SUDAH DITERIMA'))) {

						$btn = '<button class="btn btn-info btn-xs btn-view-only-pos" data_id="'.$idtransaction.'"><span class="fa fa-external-link-alt"></span> Lihat</button>';
					}
				}

				return $btn;
			}
			else {
				$status = azarr($data, 'transaction_status');
				if ($status != 'MENUNGGU PEMBAYARAN') {
					return '';
				}
			}
		}

		return $value;
	}

	function add($id = '') {
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$data['id'] = $id;

		$view = $this->load->view('paket_belanja/v_paket_belanja', $data, true);
		$azapp->add_content($view);

		// $v_modal = $this->load->view('onthespot/v_onthespot_modal', $data, true);
		// $modal = $azapp->add_modal();
		// $modal->set_id('add');
		// $modal->set_modal_title('Tambah Produk');
		// $modal->set_modal($v_modal);
		// $modal->set_action_modal(array('save'=>'Simpan'));
		// $azapp->add_content($modal->render());
		
		// $js = az_add_js('pos/vjs_pos_add', $data);
		// $azapp->add_js($js);
		
		$data_header['title'] = 'Paket Belanja';
		$data_header['breadcrumb'] = array('master', 'master_paket_belanja');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}


	///////////////////////////////////
	//// Cek
	///////////////////////////////////
	function validation_data_customer()
	{
		// dump($this->input->post());die;
		$c_name = $this->input->post('customer_name');
		$c_email = $this->input->post('customer_email');
		$c_handphone = $this->input->post('customer_handphone');

		$number_phone = $c_handphone;
		$number_hp = str_replace('-','',$number_phone);
		$number_hp = str_replace('+62','0',$number_hp);
		$number_hp = str_replace(' ','',$number_hp);
		//Cek apakah nomor telepon sesuai sama nama orang nya
		$this->db->where(['handphone' => $number_hp]);
		$check_telp = $this->db->get('member');

		if($check_telp->num_rows() > 0 && strtolower($check_telp->row_array()['name']) != strtolower($c_name)){
			$return = [
				'success' => false,
				'data' => [
					'type' => 'confirm',
					'message' => 'Nama member berbeda! <br> Pelanggan dengan nomor telepon "<b>'.$number_hp.'</b>" telah terdata dengan nama <b>'.$check_telp->row_array()['name'].'</b>. Nama yang akan disimpan adalah <b>'.$check_telp->row_array()['name'].'</b>'
				]
			];
		}else{
			$return = [
				'success' => true,
				'data' => []
			];
		}
		echo json_encode($return) ;
	}

	function edit($id) {
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$this->db->where('idoutlet', $sess_idoutlet);
		}

		$this->db->where('idtransaction', $id);
		$check = $this->db->get('transaction');
		if ($check->num_rows() == 0) {
			redirect(app_url().'pos');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->transaction_status;
			if (in_array($status, array('PEMBAYARAN DIVERIFIKASI', 'BATAL ORDER', 'PESANAN SUDAH DIVERIFIKASI', 'SELESAI DIKERJAKAN', 'PESANAN DALAM PENGIRIMAN', 'PESANAN SUDAH DITERIMA'))) {
				redirect(app_url().'pos');
			}
		}
		$this->add($id);
	}

	public function get_member_data()
	{

		$q = $this->input->get('q');

		$this->db->where('status >',0);
		$this->db->group_start();
		$this->db->like('handphone',$q);
		$this->db->or_like('name',$q);
		$this->db->group_end();
		$this->db->limit(5);
		$this->db->order_by('handphone');
		$data['member'] = $this->db->get('member')->result();

		echo json_encode($data);
	}

	public function get_single_member_data() {

		$id = $this->input->get('id');
		$number = $this->input->get('number');

		$this->db->where('status >', 0);
		$this->db->where('idmember', $id);
		$data['member'] = $this->db->get('member')->row();

		$number = str_replace(' ', '-', $number); // Replaces all spaces with hyphens.

		$number = preg_replace('/[^A-Za-z0-9]/', '', $number); // Removes special chars.

		$data_number = str_replace(' ', '-', azobj($data['member'], 'handphone')); // Replaces all spaces with hyphens.

		$data_number = preg_replace('/[^A-Za-z0-9]/', '', $data_number); // Removes special chars.

		if ($number != $data_number) {
			$data['member'] = '';
		}

		echo json_encode($data);
	}

	function get_detail_product() {
		$idproduct = $this->input->post('idproduct');
		$is_edit = $this->input->post('is_edit');
		$product_type = "";

		$sip_product_finishing = az_get_config('sip_product_finishing', 'config_app');

		$this->db->where('product.idproduct', $idproduct);
		$this->db->where('product.status', 1);
		$this->db->join('product_unit', 'product.idproduct_unit = product_unit.idproduct_unit');
		$this->db->where('product.status', 1);
		$data = $this->db->get('product');
		$return = '';
		if ($data->num_rows() > 0) {
			$product_type = azobj($data->row(),'product_type');
			$this->db->where('status', 1);
			$this->db->where('idproduct', $data->row()->idproduct);
			$product_finishing = $this->db->get('product_finishing_group');
			$arr_finishing = array();
			foreach ($product_finishing->result() as $key => $value) {
				$this->db->select('product_finishing_group_detail.idproduct_finishing, finishing_name');
				$this->db->where('idproduct_finishing_group', $value->idproduct_finishing_group);
				$this->db->where('product_finishing_group_detail.status', 1);
				$this->db->join('product_finishing', 'product_finishing_group_detail.idproduct_finishing = product_finishing.idproduct_finishing', 'left');
				$product_finishing_group = $this->db->get('product_finishing_group_detail');

				$arr_detail = array();
				foreach ($product_finishing_group->result() as $group_key => $group_value) {
					$arr_ = array(
						'idproduct_finishing' => $group_value->idproduct_finishing,
						'product_finishing_name' => $group_value->finishing_name,
					);
					$arr_detail[] = $arr_;
				}
				$arr_finishing[] = array(
					'is_required' => $value->is_required,
					'product_finishing_group_name' => $value->finishing_group_name,
					'finishing' => $arr_detail
				);
			}
			$rdata['finishing'] = $arr_finishing;
			$this->db->where('idproduct', $data->row()->idproduct);
			$material_width_list = $this->db->get('product_material_width');
			$arr_material_width = array();
			foreach ($material_width_list->result() as $key => $value) {
				$arr_material_width[] = array(
					'idproduct_material_width' => $value->idproduct_material_width,
					'width' => $value->width,
				);
			}
			$rdata['material_width'] = $arr_material_width;
			$rdata['data'] = $data->row();

			$this->db->where('status', 1);
			$this->db->order_by('calendar_content');
			$rdata['product_calendar_content'] = $this->db->get('product_calendar_content');

			$rlength = '';
			$rwidth = '';
			$ris_two_side = '0';
			$rproduct_calendar_content = '1';
			$rqty = '';
			$rqty_group = '';
			$rfinishing = array();
			$rmaterial_length = '';
			$rmaterial_width = '';
			$ris_finishing = 0;
			if ($is_edit != '0' && $is_edit != 'x') {
				$is_edit = az_decode_url($is_edit);
				$this->db->where('idtransaction_detail', $is_edit);
				$detail = $this->db->get('transaction_detail')->row();
				$rlength = az_thousand_separator_decimal($detail->length);
				$rwidth = az_thousand_separator_decimal($detail->width);
				$ris_two_side = $detail->is_two_side;
				$rproduct_calendar_content = $detail->product_calendar_content;
				$rqty = $detail->qty;
				$rmaterial_length = $detail->material_length;
				$rmaterial_width = $detail->material_width;
				if($sip_product_finishing) {
					$rqty_group = $detail->qty_group;

					if(strlen($detail->idtransaction_detail_main) > 0) {
						$ris_finishing = 1;
					}
				}

				$this->db->where('idtransaction_detail', $is_edit);
				$db_finishing = $this->db->get('transaction_finishing');
				foreach ($db_finishing->result() as $fkey => $fvalue) {
					$rfinishing[] = $fvalue->idproduct_finishing;
				}
			}
			$this->load->helper('az_core');
			$rdata['rlength'] = $rlength;
			$rdata['rwidth'] = $rwidth;
			$rdata['ris_two_side'] = $ris_two_side;
			$rdata['rproduct_calendar_content'] = $rproduct_calendar_content;
			$rdata['rqty'] = $rqty;
			$rdata['rqty_group'] = $rqty_group;
			$rdata['rfinishing'] = $rfinishing;
			$rdata['rmaterial_length'] = $rmaterial_length;
			$rdata['rmaterial_width'] = $rmaterial_width;
			$rdata['ris_finishing'] = $ris_finishing;

			$return = $this->load->view('onthespot/v_container_product', $rdata, true);
		}

		$arr = array(
			'data' => $return,
			'product_type' => $product_type,
		);
		echo json_encode($arr);
	}

	function add_product() {
		$err_code = 0;
		$err_message = '';

		$sip_product_finishing = az_get_config('sip_product_finishing', 'config_app');

	 	$idtransaction = $this->input->post('idtransaction');
	 	$idtransaction_detail = $this->input->post('idtransaction_detail');
	 	if (strlen($idtransaction_detail) > 0) {
	 		$idtransaction_detail = az_decode_url($idtransaction_detail);
	 	}

	 	if($sip_product_finishing) {
	 		$idtransaction_detail_main = $this->input->post('idtransaction_detail_main');
			if (strlen($idtransaction_detail_main) > 0) {
				$idtransaction_detail_main = az_decode_url($idtransaction_detail_main);
			}

			$is_finishing = $this->input->post('is_finishing');
	 	}

	 	$idproduct = $this->input->post('idproduct');
	 	$qty = az_crud_number($this->input->post('qty'));
	 	$transaction_description = $this->input->post('transaction_description');
	 	$length = az_crud_number($this->input->post('length'));
		$width = az_crud_number($this->input->post('width'));
		$material_length = az_crud_number($this->input->post('material_length'));
		$material_width = az_crud_number($this->input->post('material_width'));
		$idproduct_finishing = $this->input->post('idproduct_finishing');
		$is_two_side = $this->input->post('is_two_side');
		$idproduct_finishing = $this->input->post('idproduct_finishing');
		$product_calendar_content = $this->input->post('product_calendar_content');
		$deadline = $this->input->post('deadline');

		if ($this->app_accounting) {
			$idacc_tax = $this->input->post('idacc_tax');
		}

		$this->db->where('idproduct', $idproduct);
		$product = $this->db->get('product');
		$product_type = azobj($product->row(),'product_type');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idproduct', 'Produk', 'required');
		if($product_type != "DESAIN"){
			$this->form_validation->set_rules('qty', 'Jumlah Produk', 'required');
		}
		if ($product->row()->product_size != 'TANPA KOMA') {
			$this->form_validation->set_rules('length', 'Panjang', 'required');
			$this->form_validation->set_rules('width', 'Lebar', 'required');
		}
		if ($this->is_sip || $this->is_siplite) {
			if($product_type != "DESAIN"){
				if($sip_product_finishing) {
					if($is_finishing != '1') {
						$this->form_validation->set_rules('deadline', 'Deadline', 'required');
					}
				} else {
					$this->form_validation->set_rules('deadline', 'Deadline', 'required');
				}
			}
		}
		if ($product_type == "DESAIN") {
			if (strlen($idtransaction) > 0 && strlen($idtransaction_detail) == 0) {
				$this->db->where('transaction.idtransaction',$idtransaction);
				$this->db->where('product_type',"DESAIN");
				$this->db->where('transaction_detail.status',1);
				$this->db->where('desain_end is null');
				$this->db->join('transaction_detail','transaction_detail.idtransaction = transaction.idtransaction','left');
				$this->db->join('product','product.idproduct = transaction_detail.idproduct','left');
				$check = $this->db->get('transaction');

				if ($check->num_rows() > 0) {
					$err_code++;
					$err_message = "Layanan desain sebelumnya belum selesai";
				}
			}

			if(strlen($idtransaction_detail) > 0){
				$this->db->where('idtransaction_detail',$idtransaction_detail);
				$this->db->where('desain_start is not null');
				$check = $this->db->get('transaction_detail');

				if ($check->num_rows() > 0) {
					$err_code++;
					$err_message = "Layanan desain tidak dapat diedit";
				}
			}
		}

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			if(strlen($material_length) > 0) {
				if($material_length < $length) {
					$err_code++;
					$err_message = 'Lebar bahan tidak boleh lebih kecil dari ukuran aslinya.';
				}
			}
			if(strlen($material_width) > 0) {
				if($material_width < $width) {
					$err_code++;
					$err_message = 'Lebar bahan tidak boleh lebih kecil dari ukuran aslinya.';
				}
			}
		}

		$this->db->where('idtransaction',$idtransaction);
		$transaction = $this->db->get('transaction');

		if ($transaction->num_rows() > 0) {
			$status = $transaction->row()->transaction_status;
			// echo $status;
			if (in_array($status, array('PEMBAYARAN DIVERIFIKASI', 'BATAL ORDER', 'PESANAN SUDAH DIVERIFIKASI', 'SELESAI DIKERJAKAN', 'PESANAN DALAM PENGIRIMAN', 'PESANAN SUDAH DITERIMA'))) {
				$err_code++;
				$err_message = "Transaksi tidak bisa diedit atau dihapus.";
			}
		}
		if ($err_code == 0) {
			// iduser_onthespot, transaction_date_start, transaction_date, transaction_code, total_weight, total_delivery, total_delivery_weight, total_price, unique_code, grand_total_price, transaction_status, transaction_state, is_onthespot
			if (strlen($idtransaction) == 0) {
				$arr_transaction = array(
					'iduser_onthespot' => $this->session->userdata('iduser'),
					'transaction_date_start' => Date('Y-m-d H:i:s'),
					'transaction_date' => Date('Y-m-d H:i:s'),
					'transaction_status' => 'DRAFT',
					'transaction_state' => 'PEMBAYARAN',
					'is_onthespot' => 1,
					'transaction_code' => $this->generate_transaction_code(),
					// 'unique_code' => rand(10, 99)
				);

				if ($this->is_sip || $this->is_siplite) {
					// $arr_transaction['unique_code'] = NULL;
				}

				$save_transaction = az_crud_save($idtransaction, 'transaction', $arr_transaction);
				$idtransaction = azarr($save_transaction, 'insert_id');
			}

			//transaction detail
			$this->load->library('lite');
			$length = str_replace(',', '.', $length);
			$width = str_replace(',', '.', $width);
			if (strlen($length) == 0) {
				$length = 1;
			}
			if (strlen($width) == 0) {
				$width = 1;
			}

			$is_min_order = 0;
			$qty = str_replace('.', '', $qty);

			$this->db->where('idproduct', $idproduct);
			$rproduct = $this->db->get('product')->row();

			$min_order = $rproduct->min_order;
			$min_order_type = $rproduct->min_order_type;
			$product_size = $rproduct->product_size;

			$qty_main = 1;
			if($sip_product_finishing) {
				$qty_group = $qty;
				if($is_finishing == 1) {
					$this->db->where('idtransaction_detail', $idtransaction_detail_main);
					$this->db->select('idtransaction_detail, qty');
					$td_main = $this->db->get('transaction_detail');
					$qty_main = azobj($td_main->row(), 'qty');
				}
			}

			$arr_calculate = array(
				'idproduct' => $idproduct,
				'length' => $length,
				'width' => $width,
				'is_two_side' => $is_two_side,
				'qty' => $qty * $qty_main,
				'idproduct_finishing' => $idproduct_finishing,
				'product_calendar_content' => $product_calendar_content
			);

			if($product_size == 'MENGIKUTI LEBAR BAHAN') {
				if(strlen($material_length) > 0 && $material_length != 0) {
					$arr_calculate['material_length'] = $material_length;
				}
				if(strlen($material_width) > 0 && $material_width != 0) {
					$arr_calculate['material_width'] = $material_width;
				}
			}

			$the_product = $this->lite->calculate_product($arr_calculate);

			$qty = azarr($the_product, 'qty');
			$qty_original = azarr($the_product, 'qty_original');
			$is_min_order = azarr($the_product, 'is_min_order');
			$is_min_order_price = azarr($the_product, 'is_min_order_price');

			$qty_total = azarr($the_product, 'qty_total');
			$qty_subtotal = azarr($the_product, 'qty_subtotal');
			$work_duration = azarr($the_product, 'work_duration');
			$work_duration_type = azarr($the_product, 'work_duration_type');
			$price = azarr($the_product, 'product_price');
			$total_price = azarr($the_product, 'total_price');
			$finishing_list = azarr($the_product, 'finishing_list', array());

			$sub_price = $price * $qty_subtotal;
			$grand_price = $price * $qty_total;
			$qty_original = $qty;

			$arr_transaction_detail = array(
				'idtransaction' => $idtransaction,
				'idproduct' => $idproduct,
				'weight' => $rproduct->product_weight * $qty * $length * $width,
				'price' => $price,
				'sub_price' => $sub_price,
				'work_duration' => $work_duration,
				'work_duration_type' => $work_duration_type,
				'qty' => $qty,
				'qty_subtotal' => $qty_subtotal,
				'qty_total' => $qty_total,
				'transaction_description' => $transaction_description,
				'length' => $length,
				'width' => $width,
				'is_min_order' => $is_min_order,
				'is_two_side' => $is_two_side,
				'qty_original' => $qty_original,
				'is_min_order_price' => $is_min_order_price,
				'product_name' => $rproduct->product_name,
				'grand_price' => $grand_price,
				'created' => Date('Y-m-d H:i:s'),
				'product_calendar_content' => $product_calendar_content
			);
			if ($this->app_accounting) {
				$arr_transaction_detail['idacc_tax'] = $idacc_tax;
			}
			if(strlen($material_length) > 0 && !empty($material_length)) {
				$arr_transaction_detail['material_length'] = $material_length;
			} else {
				$arr_transaction_detail['material_length'] = null;
			}
			if(strlen($material_width) > 0 && !empty($material_width)) {
				$arr_transaction_detail['material_width'] = $material_width;
			} else {
				$arr_transaction_detail['material_width'] = null;
			}
			if ($this->is_sip || $this->is_siplite) {
				$this->load->helper('az_crud');
				// var_dump(azarr($the_product, 'product_price_bottom'));die;
				$arr_transaction_detail['price_bid'] = null;
				$arr_transaction_detail['price_bottom'] = azarr($the_product, 'product_price_bottom');
				$arr_transaction_detail['deadline'] = az_crud_date($deadline);
			}
			if($sip_product_finishing) {
				$arr_transaction_detail['qty_group'] = $qty_group;
				if ($is_finishing == 1) {
					$arr_transaction_detail['idtransaction_detail_main'] = $idtransaction_detail_main;
				}
			}
			if ($rproduct->product_type == "DESAIN") {
				if (strlen($idtransaction_detail) == 0) {
					$arr_transaction_detail['desain_start'] = date('Y-m-d H:i:s');
				}
				else{
					$desain_start = $this->input->post('desain_start');
					$desain_end = $this->input->post('desain_end');
					if (strlen($desain_start) > 0) {
						$arr_transaction_detail['desain_start'] = $desain_start;
					}
					if (strlen($desain_end) > 0) {
						$arr_transaction_detail['desain_end'] = $desain_end;
					}
				}
			}
			$td = az_crud_save($idtransaction_detail, 'transaction_detail', $arr_transaction_detail);
			$idtransaction_detail = azarr($td, 'insert_id');

			// check child td
			if($sip_product_finishing) {
				if(strlen($idtransaction_detail_main) == 0) {
					if ($err_code == 0) {
						$this->db->where('idtransaction_detail_main', $idtransaction_detail);
						$this->db->where('status > ', 0);
						$tdc = $this->db->get('transaction_detail');
						foreach($tdc->result() as $tdc_key => $tdc_value) {
							if ($err_code == 0) {
								$idp = azobj($tdc_value, 'idproduct');
								$idtd = azobj($tdc_value, 'idtransaction_detail');
								$param = array(
									"idproduct" => $idp,
									"idtransaction_detail" => $idtd,
									"qty" => $qty,
								);

								if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
									$price_bid = azobj($tdc_value, 'price_bid');
									$param['price_bid'] = $price_bid;
								}

								$calculated_product = $this->calculate_product_finishing($param);
								$calculated_product['idtransaction_detail'] = $idtd;
								$calculated_product['idproduct'] = $idp;

								if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
									$calculated_product['price_bid'] = $price_bid;
								}
								
								$response = $this->save_product_finishing($calculated_product);
								$err_code = azarr($response, 'err_code');
								$err_message = azarr($response, 'err_message');
							}
						}
					}
				}
			}

			$grand_finishing_price = 0;
			$grand_finishing_sub_price = 0;

			$this->db->where('idtransaction_detail', $idtransaction_detail);
			$this->db->delete('transaction_finishing');

			foreach ((array) $finishing_list as $key => $value) {
				$finishing_price = azarr($value, 'price');
				$finishing_weight = azarr($value, 'weight');
				$finishing_name = azarr($value, 'name');
				$idfinishing_list = azarr($value, 'id');
				$finishing_sub_price = $finishing_price * $qty_subtotal;
				$finishing_total_price = $finishing_price * $qty_total;

				$grand_finishing_price += $finishing_sub_price;
				$grand_finishing_sub_price += $finishing_total_price;

				$arr_finishing = array(
					'idtransaction_detail' => $idtransaction_detail,
					'idproduct_finishing' => $idfinishing_list,
					'finishing_name' => $finishing_name,
					'weight' => $finishing_weight * $qty * $length * $width,
					'price' => $finishing_price,
					'sub_price' => $finishing_sub_price,
					'total_price' => $finishing_total_price
				);
				$this->db->insert('transaction_finishing', $arr_finishing);
			}

			//update grand finishing
			$arr_new_finishing = array(
				'grand_finishing_price' => $grand_finishing_price,
				'grand_finishing_sub_price' => $grand_finishing_sub_price,
				'grand_product_finishing_price' => $sub_price + $grand_finishing_price,
				// 'grand_product_finishing_sub_price' => $grand_price + $grand_finishing_sub_price
				'grand_product_finishing_sub_price' => az_crud_number($total_price)
			);

			if ($this->app_accounting) {
				if (strlen($idacc_tax) > 0) {
					$this->db->where('idacc_tax', $idacc_tax);
					$acc_tax = $this->db->get('acc_tax');

					$tax_percentase = $acc_tax->row()->tax_percentase;

					$price_tax = ( ( ($sub_price + $grand_finishing_price) * $qty ) * $tax_percentase ) / 100;

					if ($this->is_comma_price) {
						$price_tax = round($price_tax, 2);
					}
					else {
						$price_tax = ceil($price_tax);
					}
				
					$arr_new_finishing['price_tax'] = $price_tax;
				}

			}

			$this->db->where('idtransaction_detail', $idtransaction_detail);
			$this->db->update('transaction_detail', $arr_new_finishing);

			$this->null_delivery($idtransaction);

			$this->lite->update_weight_price($idtransaction);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idtransaction' => $idtransaction,
			'product_type' => $product_type,
			'idtransaction_detail' => az_encode_url($idtransaction_detail),
		);
		echo json_encode($return);
	}

	private function null_delivery($idtransaction) {
		$arr_delivery = array(
			'delivery_type' => NULL,
			'delivery_code' => NULL,
			'delivery_name' => NULL,
			'delivery_time' => NULL,
		);
		$this->db->where('idtransaction', $idtransaction);
		$this->db->update('transaction_delivery', $arr_delivery);

		$arr_transaction = array(
			'total_delivery' => NULL,
			'total_delivery_weight' => NULL
		);
		$this->db->where('idtransaction', $idtransaction);
		$this->db->update('transaction', $arr_transaction);
	}

	private function generate_transaction_code() {
		$this->db->where('day(transaction_date_start)', Date('d'));
		$this->db->where('month(transaction_date_start)', Date('m'));
		$this->db->where('year(transaction_date_start)', Date('Y'));
		$this->db->order_by('idtransaction desc');
		$data = $this->db->get('transaction', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';
		}
		else {
			$last = $data->row()->transaction_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);
		}

		return 'ON'.Date('Ymd').$numb;
	}

	function get_list_order() {
		$idtransaction = $this->input->post("idtransaction");

		$this->load->library('Lite');
		$data['data'] = $this->lite->cart($idtransaction);

		$view = $this->load->view('onthespot/v_onthespot_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function save_onthespot() {
		$app_sipplus = az_get_config('app_sipplus','config_app');
		$err_code = 0;
		$err_message = '';

		$idprovince = $this->input->post('idprovince');
		$idcity = $this->input->post('idcity');
		$iddistrict = $this->input->post('iddistrict');
		$iddelivery = $this->input->post('delivery');
		$taken_sent = $this->input->post("taken_sent");
		$idoutlet = $this->input->post("idoutlet");
		$idmember = $this->input->post('idmember');
		$sess_idoutlet = $this->session->userdata('idoutlet');
		$transaction_type = $this->input->post("transaction_type");
		$onthespot_type = $this->input->post("onthespot_type");
		$idmarketplace = $this->input->post("idmarketplace");
		$transaction_number_marketplace = $this->input->post("transaction_number_marketplace");

		$idtransaction = $this->input->post('hd_idtransaction');
		$c_name = $this->input->post('customer_name');
		$c_email = $this->input->post('customer_email');
		$c_handphone = $this->input->post('customer_handphone');

		if ($this->app_accounting) {
			$transaction_due_date = az_crud_date($this->input->post('transaction_due_date'));
			$idacc_term_payment = $this->input->post('idacc_term_payment');			
		}

		$this->load->library('form_validation');
		if (strlen($sess_idoutlet) > 0) {
			$idoutlet = $sess_idoutlet;
		}
		else {
			$this->form_validation->set_rules('idoutlet', 'Cabang', 'required|trim|max_length[200]');
		}
		$this->form_validation->set_rules('customer_name', 'Nama Pelanggan', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('customer_handphone', 'Nomor Handphone Pelanggan', 'required|trim|max_length[200]');
		if ($taken_sent == 'DIKIRIM') {
			$this->form_validation->set_rules('iddistrict', 'Kecamatan', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('address', 'Alamat', 'required|trim|max_length[200]');
			$this->form_validation->set_rules('delivery', 'Pengiriman', 'required|trim|max_length[200]');
		}
		if (!$this->is_sip && !$this->is_siplite) {
			$this->form_validation->set_rules('payment', 'Pembayaran', 'required|trim|max_length[200]');
		}
		else {
			if ($transaction_type == 'MARKETPLACE') {
				if ($transaction_type == 'MARKETPLACE') {
					$this->form_validation->set_rules('idmarketplace', 'Jenis Marketplace', 'required|trim|max_length[200]');		
					$this->form_validation->set_rules('transaction_number_marketplace', 'No Transaksi', 'required|trim|max_length[200]');		
				}
				$this->form_validation->set_rules('transaction_number_marketplace', 'No Transaksi', 'required|trim|max_length[200]');
			}
			else if ($transaction_type == 'WHATSAPP') {
				$this->form_validation->set_rules('payment', 'Pembayaran', 'required|trim|max_length[200]');
			}

			if ($app_sipplus == 1) {
				if ($transaction_type == 'ONTHESPOT') {
					$this->form_validation->set_rules('onthespot_type', 'Jenis Transaksi', 'required|trim|max_length[200]');
				}
			}
		}

		if ($this->app_accounting) {
			$this->form_validation->set_rules('idacc_term_payment', 'Syarat Pembayaran', 'required|trim|max_length[200]');
		}

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}
		if ($err_code == 0) {
			if (strlen($idtransaction) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		$this->db->where('idtransaction',$idtransaction);
		$check = $this->db->get('transaction');

		if ($check->num_rows() > 0) {
			$status = $check->row()->transaction_status;
			if (in_array($status, array('PEMBAYARAN DIVERIFIKASI', 'BATAL ORDER', 'PESANAN SUDAH DIVERIFIKASI', 'SELESAI DIKERJAKAN', 'PESANAN DALAM PENGIRIMAN', 'PESANAN SUDAH DITERIMA'))) {
				$err_code++;
				$err_message = "Transaksi tidak bisa diedit atau dihapus.";
			}
		}
		if($err_code == 0){
			if (strlen($idtransaction) > 0 && az_get_config('is_timer_design','config_app') == 1) {
				$this->db->where('transaction.idtransaction',$idtransaction);
				$this->db->where('product_type',"DESAIN");
				$this->db->where('transaction_detail.status',1);
				$this->db->where('desain_end is null');
				$this->db->join('transaction_detail','transaction_detail.idtransaction = transaction.idtransaction','left');
				$this->db->join('product','product.idproduct = transaction_detail.idproduct','left');
				$check_design = $this->db->get('transaction');
	
				if ($check_design->num_rows() > 0) {
					foreach($check_design->result() as $key => $value){
						$this->stop_desain($value->idtransaction_detail);
					}
				}
			}
		}
		//register new member
		if($err_code == 0){
			if ($idmember == '') {
				$this->db->where('status',1);
				$this->db->where('handphone',$c_handphone);
				$t_member = $this->db->get('member');
				if ($t_member->num_rows() > 0) {
					$idmember = $t_member->row()->idmember;
				}
				else{
					$data_save = array(
						'idoutlet_register' => $idoutlet,
						'name' => $c_name,
						'email' => $c_email,
						'handphone' => $c_handphone
					);

					$save_member = az_crud_save('', 'member', $data_save);
					$idmember = azarr($save_member,'insert_id');
				}
			}
		}

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'taken_sent' => $this->input->post('taken_sent'),
	    		'transaction_status' => 'MENUNGGU PEMBAYARAN',
	    		'idproduct_payment' => $this->input->post('payment'),
	    		'idoutlet' => $idoutlet,
	    		'idmember' => $idmember
	    	);
	    	if ($this->app_accounting) {
				$arr_data['transaction_due_date'] = $transaction_due_date;
				$arr_data['idacc_term_payment'] = $idacc_term_payment;
			}
	    	if ($this->is_sip || $this->is_siplite) {
	    		$arr_data['transaction_type'] = $transaction_type;
	    		$arr_data['idmarketplace'] = NULL;
	    		$arr_data['transaction_number_marketplace'] = NULL;
	    		if ($transaction_type == 'MARKETPLACE') {
	    			$arr_data['idmarketplace'] = $idmarketplace;
	    			$arr_data['transaction_number_marketplace'] = $transaction_number_marketplace;
	    		}

	    		if ($transaction_type == "WHATSAPP") {
	    			$arr_data['unique_code'] = rand(10,99);
	    		}
	    		else {
	    			$arr_data['unique_code'] = null;
	    		}

	    		if ($app_sipplus == 1) {
	    			$arr_data['onthespot_type'] = $onthespot_type;
	    		}
	    	}

	    	az_crud_save($idtransaction, 'transaction', $arr_data);

	    	$this->db->where('idtransaction', $idtransaction);
	    	$delivery = $this->db->get('transaction_delivery');
	    	$idtransaction_delivery = '';
	    	if ($delivery->num_rows() > 0) {
	    		$idtransaction_delivery = $delivery->row()->idtransaction_delivery;
	    	}

	    	$arr_delivery = array(
	    		'idtransaction' => $idtransaction,
	    		'customer_name' => $this->input->post('customer_name'),
	    		'customer_handphone' => $this->input->post('customer_handphone'),
	    		'customer_email' => $this->input->post('customer_email'),
	    	);
	    	$save_delivery = az_crud_save($idtransaction_delivery, 'transaction_delivery', $arr_delivery);
	    	$idtransaction_delivery = azarr($save_delivery, 'insert_id');

	    	// hitung ulang total transaksinya
	    	$this->load->library('Lite');
			$this->lite->update_weight_price($idtransaction);
		}

		if ($err_code == 0) {
			if ($taken_sent == 'DIKIRIM') {
		    	$this->load->helper('location');
				$province_name = get_province($idprovince);
				$city_name = get_city($idcity);
				$district_name = get_district($iddistrict);

				$arr_update_delivery = array(
					'address' => $this->input->post('address'),
					'idprovince' => $idprovince,
					'idcity' => $idcity,
					'iddistrict' => $iddistrict,
					'province_name' => $province_name,
					'city_name' => $city_name,
					'district_name' => $district_name,
					'updated' => Date('Y-m-d H:i:s')
				);

				$this->db->where('idtransaction_delivery', $idtransaction_delivery);
				$this->db->update('transaction_delivery', $arr_update_delivery);
			}
		}
		if ($app_sipplus == 1) {
			if ($err_code == 0) {
				if ($onthespot_type == "DITUNGGU") {
					$this->db->where('transaction.idtransaction',$idtransaction);
					$this->db->join('transaction_delivery','transaction_delivery.idtransaction = transaction.idtransaction','left');
					$trx = $this->db->get('transaction')->row();

					$this->db->where('code',$trx->transaction_code);
					$check_ots = $this->db->get('tv_ots');

					if ($check_ots->num_rows() == 0) {
						$arr_ots = array(
							'idoutlet' => $trx->idoutlet,
							'code' => $trx->transaction_code,
							'name' => $trx->customer_name,
							'status' => 'PROSES',
							'type' => 'add',
						);
						send_ots($arr_ots);
					}		

				}
			}
		}
		if ($err_code == 0) {
			// if ($this->is_sip || $this->is_siplite) {
			if ($this->is_sip) {
				if ($transaction_type == 'WHATSAPP') {
					$this->load->library('Lite');
					$this->lite->send_email($idtransaction);
					if (function_exists('send_wa')) {
						send_wa($idtransaction, 'wa_pay');
					}
				}
			}
		}


		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function get_data() {
		$id = $this->input->post('id');
		$this->db->where('transaction.idtransaction', $id);
		$this->db->join('transaction_delivery', 'transaction.idtransaction = transaction_delivery.idtransaction');
		$this->db->join('outlet', 'transaction.idoutlet = outlet.idoutlet', 'left');
		if ($this->app_accounting) {
			$this->db->join('acc_term_payment', 'transaction.idacc_term_payment = acc_term_payment.idacc_term_payment', 'left');
			$this->db->select('*, date_format(transaction_due_date, "%d-%m-%Y") as txt_transaction_due_date');
		}
		$transaction = $this->db->get('transaction')->result_array();

		$this->db->where('idtransaction', $id);
		$transaction_detail = $this->db->get('transaction_detail')->result_array();

		$return = array(
			'transaction' => azarr($transaction, 0),
			'transaction_detail' => $transaction_detail
		);
		echo json_encode($return);
	}

	function delete_order() {
		$id = $this->input->post('id');
		$id = az_decode_url($id);
		$the_edit = $this->input->post('the_edit');
		$sip_product_finishing = az_get_config('sip_product_finishing', 'config_app');

		$this->db->where('idtransaction_detail', $id);
		$td = $this->db->get('transaction_detail')->row();
		$idtransaction = $td->idtransaction;
		$grand_product_finishing_sub_price = $td->grand_product_finishing_sub_price;
		$this->db->where('idtransaction', $idtransaction);
		$transaction = $this->db->get('transaction')->row();
		$status = $transaction->transaction_status;
		$is_delete = true;
		if (in_array($status, array('PEMBAYARAN DIVERIFIKASI', 'BATAL ORDER', 'PESANAN SUDAH DIVERIFIKASI', 'SELESAI DIKERJAKAN', 'PESANAN DALAM PENGIRIMAN', 'PESANAN SUDAH DITERIMA'))) {
			$is_delete = false;
		}

		$ret = array();

		if ($is_delete) {
			$grand_product_finishing_sub_price_finishing = 0;

			if($sip_product_finishing) {
				$idtd_main = azobj($td, 'idtransaction_detail_main');

				if (is_null($idtd_main)) {
					$this->db->where('idtransaction_detail_main', $id);
					$this->db->where('status > ', 0);
					$product_finishing = $this->db->get('transaction_detail');

					if ($product_finishing->num_rows() > 0) {
						foreach ($product_finishing->result() as $key => $value) {
							$grand_product_finishing_sub_price_finishing += azobj($value, 'grand_product_finishing_sub_price', 0);
							$res = az_crud_delete('transaction_detail', azobj($value, 'idtransaction_detail'), true, true);
							$err_code = azarr($res, 'err_code');
							$err_message = azarr($res, 'err_message');
						}
					}
				}
			}

			if(strlen($the_edit) > 0) {
				$total_lack = $transaction->total_lack;
				$arr_del = array(
					'total_lack' => $total_lack + $grand_product_finishing_sub_price + $grand_product_finishing_sub_price_finishing
				);
				$this->db->where('idtransaction', $idtransaction);
				$this->db->update('transaction', $arr_del);
			}

			$this->db->where("idtransaction_detail", $id);
			$this->db->delete('transaction_detail');

			// [DELETE] Ketika hapus transaction_detail dihapus juga product jadi finishingnya
			if (az_get_config('sip_product_finishing','config_app') == 1) {
				$this->db->where('idtransaction_detail_main', $id)
						->delete('transaction_detail');
			}

			$this->null_delivery($idtransaction);

			$this->load->library('lite');
			$this->lite->update_weight_price($idtransaction);

			$ret['err_code'] = 0;
			$ret['err_message'] = "";
		}
		else{
			$ret['err_code'] = 1;
			$ret['err_message'] = "Transaksi tidak dapat diedit atau dihapus.";
		}
		echo json_encode($ret);
	}

	function edit_order() {
		$id = $this->input->post("id");
		$id = az_decode_url($id);

		$err_code = 0;
		$err_message = "";
		$accounting = '';
		if ($this->app_accounting) {
			$accounting = ', transaction_detail.idacc_tax, acc_tax.tax_name';
		}
		$this->db->select('transaction_detail.idproduct, product.product_name, product.idproduct_subcategory, product_subcategory_name, product_subcategory.idproduct_category, product_category_name, transaction_description, deadline'.$accounting);
		$this->db->where('idtransaction_detail', $id);
		$this->db->join('product', 'transaction_detail.idproduct = product.idproduct');
		$this->db->join('product_subcategory', 'product.idproduct_subcategory = product_subcategory.idproduct_subcategory');
		$this->db->join('product_category', 'product_subcategory.idproduct_category = product_category.idproduct_category');
		if ($this->app_accounting) {
			$this->db->join('acc_tax', 'transaction_detail.idacc_tax = acc_tax.idacc_tax', 'left');
		}
		$product = $this->db->get('transaction_detail')->result_array();

		if (azarr(azarr($product,0,array()),'product_type') == "DESAIN") {
			$err_code++;
			$err_message = "Produk desain tidak bisa diedit";
		}
		$ret = array(
			'data' => azarr($product, 0),
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($ret);
	}

	function generate_delivery($iddistrict = '', $iddelivery = '', $idoutlet = '') {
		if (strlen($idoutlet) == 0) {
			$idoutlet = $this->input->post('idoutlet');
		}
		if (strlen($idoutlet) == 0) {
			$ret = array(
				'data' => ''
			);
			echo json_encode($ret);
			return;
		}

		if (strlen($iddistrict) == 0) {
			$iddistrict = $this->input->post('iddistrict');
		}
		$idtransaction = $this->input->post('idtransaction');
		$is_edit_transaction = $this->input->post('is_edit_transaction');
		$this->load->library('Rajaongkir');
		$this->db->where('status', 1);
		$this->db->where('is_active', 1);
		$product_delivery = $this->db->get('product_delivery');
		$db_delivery = array();
		foreach ($product_delivery->result() as $key => $value) {
			$db_delivery[] = $value->product_delivery_name;
		}

		$total_weight = 1000;
		$arr_delivery_cost = array();

		$city_origin = az_get_config('kota');

		$jne_delivery = array();
		$pos_delivery = array();
		$wahana_delivery = array();
		$jnt_delivery = array();
		$tiki_delivery = array();
		$sicepat_delivery = array();

		if (in_array('JNE', $db_delivery)) {
			$jne_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "jne"), true);
			$jne_delivery = element('rajaongkir', $jne_delivery, array());
			$jne_delivery = element('results', $jne_delivery, array());
		}

		if (in_array('POS', $db_delivery)) {
			$pos_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "pos"), true);
			$pos_delivery = element('rajaongkir', $pos_delivery, array());
			$pos_delivery = element('results', $pos_delivery, array());
		}

		if (in_array('WAHANA', $db_delivery)) {
			$wahana_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "wahana"), true);
			$wahana_delivery = element('rajaongkir', $wahana_delivery, array());
			$wahana_delivery = element('results', $wahana_delivery, array());
		}

		if (in_array('JNT', $db_delivery)) {
			$jnt_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "jnt"), true);
			$jnt_delivery = element('rajaongkir', $jnt_delivery, array());
			$jnt_delivery = element('results', $jnt_delivery, array());
		}

		if (in_array('TIKI', $db_delivery)) {
			$tiki_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "tiki"), true);
			$tiki_delivery = element('rajaongkir', $tiki_delivery, array());
			$tiki_delivery = element('results', $tiki_delivery, array());
		}

		if (in_array('SICEPAT', $db_delivery)) {
			$sicepat_delivery = json_decode($this->rajaongkir->cost($city_origin, "city", $iddistrict, "subdistrict", $total_weight, "sicepat"), true);
			$sicepat_delivery = element('rajaongkir', $sicepat_delivery, array());
			$sicepat_delivery = element('results', $sicepat_delivery, array());
		}

		$arr_delivery = array();

		$this->load->library('Rajaongkir');
		$city_data = json_decode($this->rajaongkir->get_subdistrict($iddistrict), true);
		$city_data = azarr($city_data, 'rajaongkir');
		$city_data = azarr($city_data, 'results');
		$city_destination = azarr($city_data, 'city_id');

		if ($city_destination == $city_origin && in_array('GOJEK', $db_delivery)) {
			$arr_delivery[] = array(
				'tipe' => 'gojek',
				'code' => 'kirim_gojek',
				'name' => 'Biaya kirim dibayar di pembeli',
				'etd' => '',
				'value' => '0',
			);
		}
		$data_gojek = $arr_delivery;

		$arr_delivery = array();
		if ($city_destination == $city_origin && in_array('GRAB', $db_delivery)) {
			$arr_delivery[] = array(
				'tipe' => 'grab',
				'code' => 'kirim_grab',
				'name' => 'Biaya kirim dibayar di pembeli',
				'etd' => '',
				'value' => '0',
			);
		}
		$data_grab = $arr_delivery;

		$arr_delivery = array();

		$data_jne = array();
		$data_pos = array();
		$data_wahana = array();
		$order_by['gojek'] = 0;
		$order_by['grab'] = 0;
		$order_by['jne'] = 0;
		$order_by['pos'] = 0;
		$order_by['wahana'] = 0;
		$order_by['jnt'] = 0;
		$order_by['tiki'] = 0;
		$order_by['sicepat'] = 0;
		foreach ($jne_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'jne') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$add = true;
					if (strpos($service, 'JTR') !== false) {
						$add = false;
					}

					if ($add) {
						$arr_delivery[] = array(
							'tipe' => 'jne',
							'code' => 'jne_'.$txt_service,
							'name' => 'JNE '.$service,
							'etd' => element('etd', $cost_value, ''),
							'value' => element('value', $cost_value, '')
						);
					}
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['jne'] = element('value', $arr_delivery[0], '');
		}
		$data_jne = $arr_delivery;

		$arr_delivery = array();
		foreach ($pos_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'pos') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'pos',
						'code' => 'pos_'.$txt_service,
						'name' => 'POS '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['pos'] = element('value', $arr_delivery[0], '');
		}
		$data_pos = $arr_delivery;

		$arr_delivery = array();
		foreach ($jnt_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'J&T') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'jnt',
						'code' => 'jnt_'.$txt_service,
						'name' => 'JNT '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['jnt'] = element('value', $arr_delivery[0], '');
		}
		$data_jnt = $arr_delivery;

		$arr_delivery = array();
		foreach ($tiki_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'tiki') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'tiki',
						'code' => 'tiki_'.$txt_service,
						'name' => 'TIKI '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['tiki'] = element('value', $arr_delivery[0], '');
		}
		$data_tiki = $arr_delivery;

		$arr_delivery = array();
		foreach ($sicepat_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'sicepat') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'sicepat',
						'code' => 'sicepat_'.$txt_service,
						'name' => 'SI CEPAT '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['sicepat'] = element('value', $arr_delivery[0], '');
		}
		$data_sicepat = $arr_delivery;

		$arr_delivery = array();
		foreach ($wahana_delivery as $key => $value) {
			$code = element('code', $value, '');
			$costs = element('costs', $value, array());
			if ($code == 'wahana') {
				foreach ($costs as $ckey => $cvalue) {
					$service = element('service', $cvalue, '');
					$cost = element('cost', $cvalue, array());
					$cost_value = element('0', $cost, array());
					$txt_service = str_replace(' ', '_', strtolower($service));

					$arr_delivery[] = array(
						'tipe' => 'wahana',
						'code' => 'wahana_'.$txt_service,
						'name' => 'WAHANA '.$service,
						'etd' => element('etd', $cost_value, ''),
						'value' => element('value', $cost_value, '')
					);
				}
			}
		}

		if (count($arr_delivery) > 0) {
			usort($arr_delivery, function($a, $b) {
			    return $a['value'] - $b['value'];
			});
			$order_by['wahana'] = element('value', $arr_delivery[0], '');
		}
		$data_wahana = $arr_delivery;
		asort($order_by);

		$arr_delivery = array();
		foreach ($order_by as $key => $value) {
			$data_key = 'data_'.$key;
			$arr_delivery = array_merge($arr_delivery, $$data_key);
		}

		usort($arr_delivery, function($a, $b) {
			return $a['value'] - $b['value'];
		});


		$data_delivery = array();
		$data_delivery_id = array();
		foreach ($arr_delivery as $key => $value) {
			$duration = $value['etd'];
			$duration = str_replace('HARI', '', $duration);
			$duration = str_replace(' ', '', $duration).' HARI';
			// if ($value['code'] == 'kirim_gojek') {
			if (in_array($value['code'], array('kirim_gojek', 'kirim_grab'))) {
				$duration = '';
			}
			$data_delivery[] = array(
				'image' => base_url().AZAPP_FRONT.'assets/images/delivery/d_'.$value['tipe'].'.jpg',
				'name' => $value['name'],
				'duration' => $duration,
				'price' => $value['value'],
				'code' => $value['code']
			);
			if (strlen($iddelivery) > 0) {
				if ($value['code'] == $iddelivery) {
					$data_delivery_id = array(
						'name' => $value['name'],
						'duration' => $duration,
						'price' => $value['value'],
						'code' => $value['code'],
						'type' => $value['tipe']
					);
				}
			}
		}

		if (strlen($iddelivery) > 0) {
			return $data_delivery_id;
		}

		$this->db->where('idtransaction', $idtransaction);
		$dtransaction = $this->db->get('transaction');
		if ($dtransaction->num_rows() == 0) {
			$weight = 0;
		}
		else {
			$weight = $dtransaction->row()->total_weight;
		}

		if (!$is_edit_transaction) {
			if (strlen($idtransaction) > 0) {
				$this->null_delivery($idtransaction);
				$this->load->library('Lite');
				$this->lite->update_weight_price($idtransaction);
			}
		}

		$rdata['delivery'] = $data_delivery;
		$rdata['total_weight'] = $weight;
		$view = $this->load->view('onthespot/v_onthespot_delivery', $rdata, true);
		$ret = array(
			'data' => $view
		);
		echo json_encode($ret);
	}

	function update_delivery() {
		$idtransaction = $this->input->post('idtransaction');
		$price = $this->input->post('price');
		$weight = $this->input->post('weight');
		$delivery_code = $this->input->post('delivery_code');
		$iddistrict = $this->input->post('iddistrict');
		$idcity = $this->input->post('idcity');
		$idprovince = $this->input->post('idprovince');
		$idoutlet = $this->input->post('idoutlet');

		$total_delivery_weight = ceil($weight/1000) * az_crud_number($price);

		$arr = array(
			'total_delivery' => az_crud_number($price),
			'total_delivery_weight' => $total_delivery_weight
		);
		az_crud_save($idtransaction, 'transaction', $arr);

		$this->db->where('idtransaction', $idtransaction);
		// $transaction_delivery = $this->db->get('transaction_delivery')->row();
		$transaction_delivery = $this->db->get('transaction_delivery');
    	$data_delivery = $this->generate_delivery($iddistrict, $delivery_code, $idoutlet);

    	$this->load->helper('location');
		$province_name = get_province($idprovince);
		$city_name = get_city($idcity);
		$district_name = get_district($iddistrict);

		$arr_update_delivery = array(
			'delivery_type' => azarr($data_delivery, 'type'),
			'delivery_code' => azarr($data_delivery, 'code'),
			'delivery_name' => azarr($data_delivery, 'name'),
			'delivery_time' => azarr($data_delivery, 'duration'),
			'address' => $this->input->post('address'),
			'idprovince' => $idprovince,
			'idcity' => $idcity,
			'iddistrict' => $iddistrict,
			'province_name' => $province_name,
			'city_name' => $city_name,
			'district_name' => $district_name,
			'updated' => Date('Y-m-d H:i:s')
		);

		if ($transaction_delivery->num_rows() == 0) {
			$arr_update_delivery['idtransaction'] = $idtransaction;
			$this->db->insert('transaction_delivery', $arr_update_delivery);
		}
		else {
			$this->db->where('idtransaction', $idtransaction);
			$this->db->update('transaction_delivery', $arr_update_delivery);
		}

		$this->load->library('Lite');
		$this->lite->update_weight_price($idtransaction);

		$ret = array(
			'err_code' => 0
		);
		echo json_encode($ret);
	}

	function search_product() {
		$keyword = $this->input->get("term");

		$sip_api_tokopedia = az_get_config('sip_api_tokopedia', 'config_app');
		$sip_api_shopee = az_get_config('sip_api_shopee', 'config_app');

		$this->db->order_by("product_name");
		$this->db->where('status', 1);
		if($sip_api_tokopedia) {
			$this->db->where('is_tokopedia', 0);
		}
		if($sip_api_shopee) {
			$this->db->where('is_shopee', 0);
		}
		// $this->db->where("is_active", 1);
		$this->db->like('product_name', $keyword);
		$this->db->select("idproduct as id, product_name as text");

		$data = $this->db->get("product");

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

	function select_product() {
		$id = $this->input->post('id');
		$type = az_get_config('show_product', 'config_app');

		$this->db->where('idproduct', $id);
		$this->db->join('product_subcategory', 'product.idproduct_subcategory = product_subcategory.idproduct_subcategory', 'left');
		if ($type == '1') {
			$this->db->join('product_category', 'product.idproduct_category = product_category.idproduct_category', 'left');
		}
		else {
			$this->db->join('product_category', 'product_subcategory.idproduct_category = product_category.idproduct_category', 'left');
		}
		$product = $this->db->get('product');

		$product_category_name = '';
		$product_subcategory_name = '';
		if (strlen($product->row()->product_category_name) > 0) {
			$product_category_name = $product->row()->product_category_name;
		}
		if (strlen($product->row()->product_subcategory_name) > 0) {
			$product_subcategory_name = $product->row()->product_subcategory_name;
		}

		$ret = array(
			'idproduct_category' => $product->row()->idproduct_category,
			'idproduct_subcategory' => $product->row()->idproduct_subcategory,
			'idproduct' => $product->row()->idproduct,
			'product_category_name' => $product_category_name,
			'product_subcategory_name' => $product_subcategory_name,
			'product_name' => $product->row()->product_name
		);
		echo json_encode($ret);
	}

	public function delete() {
		$id = $this->input->post('id');
		// print_r($id);

		$err_code = 0;
		$err_message = '';

		if($err_code == 0) {
			$this->db->where_in('idtransaction', $id);
			$this->db->select('idtransaction, transaction_code, transaction_status');
			$check = $this->db->get('transaction');
			foreach($check->result() as $ckey => $cvalue) {
				if(!in_array($cvalue->transaction_status, array('DRAFT', 'MENUNGGU PEMBAYARAN'))) {
					$err_code++;
					$err_message = 'Transaksi sudah diverifikasi, tidak dapat dihapus.';
				}
			}
		}
		// var_dump($err_code);
		// var_dump($err_message);

		if($err_code == 0) {
			az_crud_delete($this->table, $id);

		} else{
			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message
			);
			echo json_encode($ret);
		}
	}

	function save_search() {
		$app_sipplus = az_get_config('app_sipplus', 'config_app');

		if($app_sipplus) {
			$text = $this->input->post('text');
			$keyword = str_replace('<strong>', '', $text);
			$keyword = str_replace('</strong>', '', $keyword);
			$keywords = explode(" ",$keyword);

			$err_code = 0;
			$err_message = '';
			if(strlen($keyword) > 0) {
				$this->db->select('product.idproduct, product.url_key, product.product_name, product.image1 as product_image, (SELECT price from product_price where product.idproduct = idproduct and range1 = -1) as product_price');
				$this->db->group_start();
				foreach ($keywords as $key => $value) {
					$this->db->like('product_name',$value);
				}
				$this->db->group_end();
				// $this->db->limit($limit,$page);
				$this->db->where('product.status >',0);
				$this->db->where('product.is_active >',0);
				$data_product = $this->db->get('product');
				$total_page = $data_product->num_rows();

				$is_found = 0;
				if ($data_product->num_rows() > 0) {
					$is_found = 1;
				}

				$data_smart_search = array(
					'keyword' => $keyword,
					'is_found' => $is_found,
					'created' => Date('Y-m-d H:i:s'),
					'updated' => Date('Y-m-d H:i:s'),
				);
				$this->db->insert('smart_search_log', $data_smart_search);
			}

			$ret = array(
				'err_code' => $err_code,
				'err_message' => $err_message,
			);
			echo json_encode($ret);
		}
	}

	function get_term_payment() {
		$err_code = 0;
		$err_message = '';
		
		$data = array();
		$transaction_due_date = '';
		$term_payment_name = '';

		$idacc_term_payment = $this->input->post('idacc_term_payment');
		$date = $this->input->post('date');

		if ($date == null) {
			$date = date('d-m-Y');
		}

		if(strlen($idacc_term_payment) > 0 && strlen($date) > 0) {
			$this->db->select("idacc_term_payment, term_payment_name, time_period");
			$this->db->where('status', '1');
			$this->db->where('idacc_term_payment="'.$idacc_term_payment.'"');
			$term_payment = $this->db->get('acc_term_payment')->result_array();

			$term_payment_name = $term_payment[0]['term_payment_name'];

			if ($term_payment_name != "Custom") {
				$date=date_create($date);
				date_add($date,date_interval_create_from_date_string($term_payment[0]['time_period'].' days'));
				$transaction_due_date = date_format($date,"d-m-Y");
			}
		}else{
			$err_code++;
			$err_message = "Invalid ID"; 
		}

		$data["err_code"] = $err_code;
		$data["err_message"] = $err_message;
		$data["transaction_due_date"] = $transaction_due_date;
		$data["term_payment"] = $term_payment_name;
		echo json_encode($data);
	}

	public function check_desain_time()
	{
		$idtransaction = $this->input->get('idtransaction');
		$this->db->select('desain_start, desain_end');
		$this->db->where('transaction.idtransaction',$idtransaction);
		$this->db->where('product_type',"DESAIN");
		$this->db->where('transaction_detail.status',1);
		$this->db->where('desain_end is null');
		$this->db->join('transaction_detail','transaction_detail.idtransaction = transaction.idtransaction','left');
		$this->db->join('product','product.idproduct = transaction_detail.idproduct','left');
		$check = $this->db->get('transaction');

		$hours = 0;
		$minute = 0;
		$second = 0;
		$desain_start = "";

		if ($check->num_rows() > 0) {
			$desain_start = new DateTime($check->row()->desain_start);
			$desain_end = new DateTime(date('Y-m-d H:i:s'));
			$interval = $desain_start->diff($desain_end);

			$hours = $interval->format('%h');
			$minute = $interval->format('%i');
			$second = $interval->format('%s');
		}

		$return = array(
			'desain_start' => $desain_start,
			'hours' => $hours,
			'minute' => $minute,
			'second' => $second,
		);

		echo json_encode($return);
	}

	public function stop_desain($idtrx_detail = "")
	{
		$idtransaction_detail = az_decode_url($this->input->get('idtransaction_detail'));
		if (strlen($idtrx_detail) > 0) {
			$idtransaction_detail = $idtrx_detail;
		}

		$this->db->where('idtransaction_detail',$idtransaction_detail);
		$trx_detail = $this->db->get('transaction_detail');

		$is_stop = true;
		if ($trx_detail->num_rows() > 0) {
			if (strlen($trx_detail->row()->desain_end) > 0) {
				$is_stop = false;
			}
		}

		$error = false;
		$message = "";

		if ($trx_detail->num_rows() > 0 && $is_stop) {
			$idproduct = $trx_detail->row()->idproduct;
			$idtransaction = $trx_detail->row()->idtransaction;
			$desain_start = $trx_detail->row()->desain_start;


			$diff = strtotime(date('Y-m-d H:i:s')) - strtotime($desain_start) ;
			$diff = ceil($diff/60);
			$this->load->library('Lite');
			$data_price = $this->lite->get_product_price($idproduct,$diff);
			$product_price = azarr($data_price,'price');

			$this->db->where('idproduct',$idproduct);
			$product = $this->db->get('product');

			if ($product->num_rows() > 0) {
				$product = $product->row();

				if ($product->min_order_type = "SATUAN") {
					if ($diff < $product->min_order) {
						$diff = $product->min_order;
					}
				}
			}
			$grand_price = $product_price*$diff;

			$arr_update = array(
				'qty' => $diff,
				'price'=> $product_price,
				'grand_price' => $grand_price,
				'sub_price' => $product_price,
				'grand_product_finishing_price' => $product_price,
				'grand_product_finishing_sub_price' => $grand_price,
				'desain_end' => date('Y-m-d H:i:s'),
				'production_status' => 1,
				'is_taken' => 1,
				'is_updated' => 1
			);


			$arr_selective_taking = array(
				'idtransaction_detail' => $idtransaction_detail,
				'iduser_cashier' => $this->session->userdata('iduser'),
				'total_taken' => $diff,
				'taking_date' => date('Y-m-d H:i:s'),
				'created' => date('Y-m-d H:i:s'),
				'updated' => date('Y-m-d H:i:s')
			);


			$this->db->where('idtransaction_detail',$idtransaction_detail);
			$update  = $this->db->update('transaction_detail',$arr_update);

			$this->db->insert('transaction_selective_taking',$arr_selective_taking);

			if ($update) {
				$message = "Success";
			}

			$this->null_delivery($idtransaction);

			$this->lite->update_weight_price($idtransaction);			
		}
		else{
			$error = true;
			$message = "Stop Gagal!";
		}

		$return = array(
			'error' => $error,
			'message' => $message,
		);

		if (strlen($idtrx_detail) > 0) {
			return $return;
		}
		else{
			echo json_encode($return);			
		}

	}

	function calculate_product_finishing($param) {
		$idproduct = azarr($param, 'idproduct');
		$idtransaction_detail = azarr($param, 'idtransaction_detail');
		$qty = azarr($param, 'qty');

		$this->db->where('idproduct', $idproduct);
		$rproduct = $this->db->get('product')->row();

		$min_order = $rproduct->min_order;
		$min_order_type = $rproduct->min_order_type;
		$product_size = $rproduct->product_size;

		$this->db->where('idtransaction_detail', $idtransaction_detail);
		$this->db->where('status > ', 0);
		$td_main = $this->db->get('transaction_detail');
		$qty_group = azobj($td_main->row(), 'qty_group');
		$length = azobj($td_main->row(), 'length');
		$width = azobj($td_main->row(), 'width');
		$is_two_side = azobj($td_main->row(), 'is_two_side');
		$product_calendar_content = azobj($td_main->row(), 'product_calender_content');
		$material_length = azobj($td_main->row(), 'material_length');
		$material_width = azobj($td_main->row(), 'material_width');

		$this->db->where('idtransaction_detail', $idtransaction_detail);
		$this->db->where('status >', 0);
		$finishing_list = $this->db->get('transaction_finishing')->result_array();
		$idproduct_finishing = array_column($finishing_list, 'idproduct_finishing');

		$arr_calculate = array(
			'idproduct' => $idproduct,
			'length' => $length,
			'width' => $width,
			'is_two_side' => $is_two_side,
			'qty' => $qty * $qty_group,
			'idproduct_finishing' => $idproduct_finishing,
			'product_calendar_content' => $product_calendar_content
		);

		if($product_size == 'MENGIKUTI LEBAR BAHAN') {
			if(strlen($material_length) > 0 && $material_length != 0) {
				$arr_calculate['material_length'] = $material_length;
			}
			if(strlen($material_width) > 0 && $material_width != 0) {
				$arr_calculate['material_width'] = $material_width;
			}
		}

		$the_product = $this->lite->calculate_product($arr_calculate);

		return $the_product;
	}
	
	function save_product_finishing($data) {
		$id = azarr($data, 'idtransaction_detail');
		$idproduct = azarr($data, 'idproduct');

		// nego
		// if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
		// 	$price_bid = azarr($data, 'price_bid');

		// 	if (strlen($price_bid) > 0) {
		// 		$qty_total = azarr($data, 'qty');
		// 	} else {
		// 		$qty_total = azarr($data, 'qty_total');
		// 	}
		// } else {
			$price_bid = null;
			$qty_total = azarr($data, 'qty_total');
		// }

		$qty_subtotal = azarr($data, 'qty_subtotal');
		$work_duration = azarr($data, 'work_duration');
		$work_duration_type = azarr($data, 'work_duration_type');
		$price_original = azarr($data, 'product_price');
		$price = azarr($data, 'product_price');

		if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
			if (strlen($price_bid) > 0) {
				$price = $price_bid;
			}
		}

		$qty = azarr($data, 'qty');
		$subtotal_price = azarr($data, 'subtotal_price');
		$total_price = azarr($data, 'total_price');
		$finishing_list = azarr($data, 'finishing_list', array());

		$sub_price = floatval($price_original) * floatval($qty_subtotal);
		$grand_price = floatval($price) * floatval($qty_total);

		$this->db->where('idproduct', $idproduct);
		$this->db->where('status > ', 0);
		$product = $this->db->get('product');

		$weight = azobj($product->row(), 'product_weight', 1) * $qty;
		$arr_transaction_detail = array(
			'weight' => $weight,
			'price' => $price_original,
			'sub_price' => $sub_price,
			'work_duration' => $work_duration,
			'work_duration_type' => $work_duration_type,
			'qty' => $qty,
			'qty_subtotal' => $qty_subtotal,
			'qty_total' => $qty_total,
			'grand_price' => $grand_price,
		);

		if (az_get_config('is_voucher', 'config_app')) {
			$discount_name = azarr($data, 'discount_name');
			$discount_total = az_remove_separator(azarr($data, 'discount_total', 0));
			$before_discount = 0;

			if (strlen($discount_name) > 0) {
				$before_discount = az_crud_number($total_price);
				$total_price = azarr($data, 'after_discount', 0);
			}

			$arr_transaction_detail['discount_name'] = $discount_name;
			$arr_transaction_detail['discount_total'] = $discount_total;
			$arr_transaction_detail['before_discount'] = $before_discount;
		}
		if (az_get_config('app_sip', 'config_app') || az_get_config('app_siplite', 'config_app')) {
			$arr_transaction_detail['price_bid'] = $price_bid;
		}

		$res = az_crud_save($id, 'transaction_detail', $arr_transaction_detail);
		$err_code = azarr($res, 'err_code');
		$err_message = azarr($res, 'err_message');

		$grand_finishing_price = 0;
		$grand_finishing_sub_price = 0;

		if ($err_code == 0) {
			foreach ((array) $finishing_list as $key => $value) {
				if ($err_code == 0) {
					$finishing_price = azarr($value, 'price');
					$finishing_weight = azarr($value, 'weight');
					$finishing_name = azarr($value, 'name');
					$idfinishing_list = azarr($value, 'id');
					$finishing_sub_price = $finishing_price * $qty_subtotal;
					$finishing_total_price = $finishing_price * $qty_total;
		
					$grand_finishing_price += floatval($finishing_sub_price);
					$grand_finishing_sub_price += $finishing_total_price;
		
					$arr_finishing = array(
						'weight' => $finishing_weight * $qty,
						'price' => $finishing_price,
						'sub_price' => $finishing_sub_price,
						'total_price' => $finishing_total_price
					);
	
					$res = az_crud_update('', 'transaction_finishing', $arr_finishing, "idtransaction_detail = '$id' and idproduct_finishing = '$idfinishing_list'");
					$err_code = azarr($res, 'err_code');
					$err_message = azarr($res, 'err_message');
				}
			}
		}

		if ($err_code == 0) {			
			//update grand finishing
			if (strlen($price_bid) > 0) {
				$grand_product_finishing_sub_price = $price_bid * $qty;
			} else {
				$grand_product_finishing_sub_price = $grand_price + $grand_finishing_sub_price;
			}
	
			$grand_product_finishing_price = $sub_price + $grand_finishing_price;

			$arr_new_finishing = array(
				'grand_finishing_price' => $grand_finishing_price,
				'grand_finishing_sub_price' => $grand_finishing_sub_price,
				'grand_product_finishing_price' => $sub_price + $grand_finishing_price,
				'grand_product_finishing_sub_price' => $grand_product_finishing_sub_price
				// 'grand_product_finishing_sub_price' => az_crud_number($total_price)
			);
	
			// if ($this->app_accounting || $this->app_accounting_old) {
			// 	if (strlen($idacc_tax) > 0) {
			// 		$this->db->where('idacc_tax', $idacc_tax);
			// 		$acc_tax = $this->db->get('acc_tax');
	
			// 		$tax_percentase = $acc_tax->row()->tax_percentase;
	
			// 		$arr_new_finishing['price_tax'] = ( $grand_product_finishing_sub_price * $tax_percentase ) / 100;
	
			// 		$total_tax = $arr_new_finishing['price_tax'];
			// 	}
	
			// }

			$res = az_crud_save($id, 'transaction_detail', $arr_new_finishing);
			$err_code = azarr($res, 'err_code');
			$err_message = azarr($res, 'err_message');
		}

		$response = array(
			"err_code" => $err_code,
			"err_message" => $err_message,
		);

		return $response;
	}
}
