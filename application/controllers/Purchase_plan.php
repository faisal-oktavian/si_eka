<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_plan extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('purchase_plan');
        $this->table = 'purchase_plan';
        $this->controller = 'purchase_plan';
        $this->load->helper('az_crud');
		$this->load->helper('transaction_status_helper');
    }

	public function index() {		
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');
		$idrole = $this->session->userdata('idrole');

		$crud->set_column(array('#', 'Tanggal Rencana', 'Nomor Rencana', 'Detail', 'Status', 'Admin', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		if (aznav('role_view_purchase_plan') && strlen($idrole) > 0) {
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
		$crud->add_aodata('vf_purchase_plan_code', 'vf_purchase_plan_code');
		$crud->add_aodata('vf_purchase_plan_status', 'vf_purchase_plan_status');

		$vf = $this->load->view('purchase_plan/vf_purchase_plan', $data, true);
        $crud->set_top_filter($vf);

		$crud = $crud->render();
		$data['crud'] = $crud;
		$data['active'] = 'purchase_plan';
		$view = $this->load->view('purchase_plan/v_format_purchase_plan', $data, true);
		$azapp->add_content($view);

		$js = az_add_js('purchase_plan/vjs_purchase_plan');
		$azapp->add_js($js);

		$data_header['title'] = 'Rencana Pengadaan';
		$data_header['breadcrumb'] = array('purchase_plan');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$date1 = $this->input->get('date1');
		$date2 = $this->input->get('date2');
		$purchase_plan_code = $this->input->get('vf_purchase_plan_code');
		$purchase_plan_status = $this->input->get('vf_purchase_plan_status');

        $crud->set_select('purchase_plan.idpurchase_plan, date_format(purchase_plan_date, "%d-%m-%Y %H:%i:%s") as txt_purchase_plan_date, purchase_plan_code, "" as detail, purchase_plan_status, user.name as user_created');        
        $crud->set_select_table('idpurchase_plan, txt_purchase_plan_date, purchase_plan_code, detail, purchase_plan_status, user_created');
        $crud->set_sorting('purchase_plan_date, purchase_plan_code, purchase_plan_status');
        $crud->set_filter('purchase_plan_date, purchase_plan_code, purchase_plan_status');
		$crud->set_id($this->controller);
		$crud->set_select_align(' , , , center');

        $crud->add_join_manual('user', 'purchase_plan.iduser_created = user.iduser');
        
        if (strlen($date1) > 0 && strlen($date2) > 0) {
            $crud->add_where('date(purchase_plan.purchase_plan_date) >= "'.Date('Y-m-d', strtotime($date1)).'"');
            $crud->add_where('date(purchase_plan.purchase_plan_date) <= "'.Date('Y-m-d', strtotime($date2)).'"');
        }
        if (strlen($purchase_plan_code) > 0) {
			$crud->add_where('purchase_plan.purchase_plan_code = "' . $purchase_plan_code . '"');
		}
		if (strlen($purchase_plan_status) > 0) {
			$crud->add_where('purchase_plan.purchase_plan_status = "' . $purchase_plan_status . '"');
		}

		$crud->add_where("purchase_plan.status = 1");
		$crud->add_where("purchase_plan.purchase_plan_status != 'DRAFT' ");

		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		$crud->set_order_by('purchase_plan_date desc');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		$idrole = $this->session->userdata('idrole');
		$idpurchase_plan = azarr($data, 'idpurchase_plan');
		$purchase_plan_status = azarr($data, 'purchase_plan_status');
		$read_more = false;
		$is_view_only = false;
		
		if ($key == 'purchase_plan_status') {
			$status = label_status($value);
			
			return $status;
		}

		if ($key == "detail") {
			$this->db->where('purchase_plan_detail.idpurchase_plan = "'.$idpurchase_plan.'" ');
			$this->db->where('purchase_plan_detail.status', 1);
			$this->db->where('purchase_plan.status', 1);
			$this->db->where('purchase_plan.purchase_plan_status != "DRAFT" ');

			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
			$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
			$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
			$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

			$this->db->select('paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, purchase_plan_detail.volume');
			$purchase_plan = $this->db->get('purchase_plan');
			// echo "<pre>"; print_r($this->db->last_query());die;

			$last_query = $this->db->last_query();
			$purchase_plan_limit = $this->db->query('SELECT * FROM ('.$last_query.') as new_query limit 3 ');

			if ($purchase_plan->num_rows() > 3) {
				$read_more = true;
			}

			$html = '<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">';
			$html .= 	'<tr>';
			$html .= 		'<th width="320px">Nama Paket Belanja</th>';
			$html .= 		'<th width="200px">Uraian</th>';
			$html .= 		'<th width="80px">Volume</th>';
			$html .= 	'</tr>';

			foreach ($purchase_plan_limit->result() as $key => $value) {
				$html .= '<tr>';
				$html .= 	'<td>'.$value->nama_paket_belanja.'</td>';
				$html .= 	'<td>'.$value->nama_sub_kategori.'</td>';
				$html .= 	'<td align="center">'.$value->volume.'</td>';
				$html .= '</tr>';
			}

			$html .= '</table>';

			if ($read_more) {
				$html .= '<div>
							<a href="purchase_plan/edit/'.$idpurchase_plan.'/view_only">Selengkapnya...</a>
						</div>';
			}

			return $html;
		}

		if ($key == 'action') {
            $btn = '<button class="btn btn-default btn-xs btn-edit-purchase-plan" data_id="'.$idpurchase_plan.'"><span class="glyphicon glyphicon-pencil"></span> Edit</button>';
            $btn .= '<button class="btn btn-danger btn-xs btn-delete-purchase-plan" data_id="'.$idpurchase_plan.'"><span class="glyphicon glyphicon-remove"></span> Hapus</button>';
			
			$the_filter = array(
				'menu' => 'RENCANA PENGADAAN',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($purchase_plan_status, $arr_validation) ) {
				$is_view_only = true;
            }

			if (aznav('role_view_purchase_plan') && strlen($idrole) > 0) {
				$is_view_only = true;
			}

			// cek apakah yang login user ppk & pp
			$role_name = $this->session->userdata('role_name');
			if (in_array($role_name, array('ppk', 'pp') ) ) {

				$this->db->where('idpurchase_plan', $idpurchase_plan);
				$this->db->join('user', 'user.iduser = purchase_plan.iduser_created');
				$this->db->join('role', 'role.idrole = user.idrole', 'left');
				$this->db->select('role.name as role_name');
				$check = $this->db->get('purchase_plan');

				$data_role_name = $check->row()->role_name;
				
				if ($role_name == "ppk" && $data_role_name != "ppk") {
					$is_view_only = true;
				}
				else if ($role_name == "pp" && $data_role_name != "pp") {
					$is_view_only = true;
				}
			}

			if ($is_view_only) {
				$btn = '<button class="btn btn-info btn-xs btn-view-only-purchase-plan" data_id="'.$idpurchase_plan.'"><span class="glyphicon glyphicon-eye-open"></span> Lihat</button>';
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
        
		$view = $this->load->view('purchase_plan/v_purchase_plan', $data, true);
		$azapp->add_content($view);

		$v_modal = $this->load->view('purchase_plan/v_purchase_plan_modal', '', true);
		$modal = $azapp->add_modal();
		$modal->set_id('add_uraian');
		$modal->set_modal_title('Tambah Rencana Pengadaan');
		$modal->set_modal($v_modal);
		$modal->set_action_modal(array('save_uraian'=>'Simpan'));
		$azapp->add_content($modal->render());
		
		$js = az_add_js('purchase_plan/vjs_purchase_plan_add', $data);
		$azapp->add_js($js);
		
		$data_header['title'] = 'Rencana Pengadaan';
		$data_header['breadcrumb'] = array('purchase_plan');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

    function search_uraian() {
		$keyword = $this->input->get("term");
        $month_now = date('m');
        $query_where = "";
        $query_or = "";

		$role_name = $this->session->userdata('role_name');

        if ($month_now > 1) {
            $query_or = " OR ";
        }

		// kondisi ini tidak berlaku jika user memiliki akses bisa rencana pengadaan sebelum bulan RAK
		if (!aznav('role_bypass')) {
			for ($i=1; $i <= $month_now; $i++) { 
				if ($i == 1) {
					$query_where .= "pbds_parent.rak_volume_januari IS NOT NULL OR pbds_child.rak_volume_januari IS NOT NULL";
				}
				else if ($i == 2) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_februari IS NOT NULL OR pbds_child.rak_volume_februari IS NOT NULL";
				}
				else if ($i == 3) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_maret IS NOT NULL OR pbds_child.rak_volume_maret IS NOT NULL";
				}
				else if ($i == 4) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_april IS NOT NULL OR pbds_child.rak_volume_april IS NOT NULL";
				}
				else if ($i == 5) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_mei IS NOT NULL OR pbds_child.rak_volume_mei IS NOT NULL";
				}
				else if ($i == 6) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_juni IS NOT NULL OR pbds_child.rak_volume_juni IS NOT NULL";
				}
				else if ($i == 7) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_juli IS NOT NULL OR pbds_child.rak_volume_juli IS NOT NULL";
				}
				else if ($i == 8) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_agustus IS NOT NULL OR pbds_child.rak_volume_agustus IS NOT NULL";
				}
				else if ($i == 9) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_september IS NOT NULL OR pbds_child.rak_volume_september IS NOT NULL";
				}
				else if ($i == 10) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_oktober IS NOT NULL OR pbds_child.rak_volume_oktober IS NOT NULL";
				}
				else if ($i == 11) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_november IS NOT NULL OR pbds_child.rak_volume_november IS NOT NULL";
				}
				else if ($i == 12) {
					$query_where .= $query_or;
					$query_where .= "pbds_parent.rak_volume_desember IS NOT NULL OR pbds_child.rak_volume_desember IS NOT NULL";
				}
			}
		}

        // ambil data paket belanja dan uraian yang belum masuk ke step rencana pengadaan
		if ($role_name == "ppk") {
			$this->db->where('pb.select_ppkom_pptk = "PPK" ');
		}
		else if ($role_name == "pp") {
			$this->db->where('pb.select_ppkom_pptk = "PP" ');
		}

		if (strlen($query_where) > 0) {
			$this->db->where(' ('.$query_where.') ');
		}
		$this->db->where('pb.status', 1);
		$this->db->where('pb.status_paket_belanja != "DRAFT" ');
		$this->db->where('pbd.status', 1);
		
		// minta dobel where
		$this->db->group_start();
        $this->db->like('pb.nama_paket_belanja', $keyword);
        $this->db->or_like('sk_child.nama_sub_kategori', $keyword);
        $this->db->or_like('sk_parent.nama_sub_kategori', $keyword);
        $this->db->group_end();

		// data yang ditampilkan adalah data pada tahun berjalan
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan = "'.Date('Y').'" ');
		
		// $this->db->group_start();
        // $this->db->where('pbds_parent.volume > pbds_parent.volume_realization');
        // $this->db->or_where('pbds_child.volume > pbds_child.volume_realization');
		
		// jika nama satuannya LS maka tidak perlu pengecekan volume > volume realisasi
		$this->db->where('
			(
				s_parent.nama_satuan = "LS"
				OR (
					s_parent.nama_satuan != "LS"
					AND pbds_parent.volume > pbds_parent.volume_realization
				)
			)
			OR
			(
				s_child.nama_satuan = "LS"
				OR (
					s_child.nama_satuan != "LS"
					AND pbds_child.volume > pbds_child.volume_realization
				)
			)
		');
        // $this->db->group_end();

        $this->db->group_start();
        $this->db->like('pb.nama_paket_belanja', $keyword);
        $this->db->or_like('sk_child.nama_sub_kategori', $keyword);
        $this->db->or_like('sk_parent.nama_sub_kategori', $keyword);
        $this->db->group_end();
		$this->db->where('pb.nama_paket_belanja IS NOT NULL');
		
		$this->db->join('paket_belanja_detail pbd', 'pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail', 'left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');
		$this->db->join('sub_kategori sk_child', 'sk_child.idsub_kategori = pbds_child.idsub_kategori', 'left');
		$this->db->join('sub_kategori sk_parent', 'sk_parent.idsub_kategori = pbds_parent.idsub_kategori', 'left');
		$this->db->join('satuan s_child', 's_child.idsatuan = pbds_child.idsatuan', 'left');
		$this->db->join('satuan s_parent', 's_parent.idsatuan = pbds_parent.idsatuan', 'left');
		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = pb.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');

        $this->db->order_by("nama_paket_belanja");

		$this->db->select('
			COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS id, 
			CONCAT( "[", `pb`.`nama_paket_belanja`, "] ", COALESCE(sk_child.nama_sub_kategori, sk_parent.nama_sub_kategori), 
			" -> [", 
			COALESCE(pbds_parent.volume, pbds_child.volume), 
			COALESCE(s_parent.nama_satuan, s_child.nama_satuan),
			" @",
			FORMAT(
				COALESCE(pbds_parent.harga_satuan, pbds_child.harga_satuan),
				0,
				"id_ID"
			),
			"]" ) AS text
		', false);

		$data = $this->db->get('paket_belanja pb');
        // echo "<pre>"; print_r($this->db->last_query()); die;

		$results = array(
			"results" => $data->result_array(),
		);
		echo json_encode($results);
	}

    function select_uraian() {
		$idpaket_belanja_detail_sub = $this->input->post('idpaket_belanja_detail_sub');

		// cek dulu apakah uraian ini satuannya LS atau bukan
		$this->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
		$this->db->join('satuan', 'satuan.idsatuan = paket_belanja_detail_sub.idsatuan', 'left');
		$this->db->select('satuan.nama_satuan');
		$pbds_check = $this->db->get('paket_belanja_detail_sub');

		$satuan_uraian = $pbds_check->row()->nama_satuan;


        $this->db->where(' (pbds_parent.idpaket_belanja_detail_sub = "'.$idpaket_belanja_detail_sub.'" OR pbds_child.idpaket_belanja_detail_sub = "'.$idpaket_belanja_detail_sub.'") ');
        $this->db->where('pb.status', 1);
		// $this->db->where('pb.status_paket_belanja = "OK" ');
		$this->db->where('pbd.status', 1);

		
		if ($satuan_uraian != "LS") {
			$this->db->group_start();
			$this->db->where('pbds_parent.volume >= pbds_parent.volume_realization');
			$this->db->or_where('pbds_child.volume >= pbds_child.volume_realization');
			$this->db->group_end();
		}

		
        $this->db->join('paket_belanja_detail pbd', 'pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');
        $this->db->join('sub_kategori sk_child', 'sk_child.idsub_kategori = pbds_child.idsub_kategori', 'left');
        $this->db->join('sub_kategori sk_parent', 'sk_parent.idsub_kategori = pbds_parent.idsub_kategori', 'left');
        
        $this->db->join('satuan satuan_child', 'satuan_child.idsatuan = pbds_child.idsatuan', 'left');
        $this->db->join('satuan satuan_parent', 'satuan_parent.idsatuan = pbds_parent.idsatuan', 'left');

        $this->db->order_by("nama_paket_belanja");

		$this->db->select('
            pb.idpaket_belanja, 
            pb.nama_paket_belanja,
            COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS detail_sub_id, 
            COALESCE(sk_child.nama_sub_kategori, sk_parent.nama_sub_kategori) AS nama_sub_kategori, 
            COALESCE(pbds_child.volume, pbds_parent.volume) AS volume,
            COALESCE(satuan_child.nama_satuan, satuan_parent.nama_satuan) AS nama_satuan,
            COALESCE(pbds_child.harga_satuan, pbds_parent.harga_satuan) AS harga_satuan
        ');
		$pb = $this->db->get('paket_belanja pb');
        // echo "<pre>"; print_r($this->db->last_query()); die;

		$ret = array(
			'idpaket_belanja_detail_sub' => $idpaket_belanja_detail_sub,
			'idpaket_belanja' => $pb->row()->idpaket_belanja,
			'nama_paket_belanja' => $pb->row()->nama_paket_belanja,
			'detail_sub_id' => $pb->row()->detail_sub_id,
			'nama_sub_kategori' => $pb->row()->nama_sub_kategori,
			'volume' => $pb->row()->volume,
			'nama_satuan' => $pb->row()->nama_satuan,
			'harga_satuan' => $pb->row()->harga_satuan,
		);

		echo json_encode($ret);
	}

    function add_plan() {
		$err_code = 0;
		$err_message = '';

	 	$idpurchase_plan = $this->input->post('idpurchase_plan');
	 	$idpaket_belanja = $this->input->post('idpaket_belanja');
	 	$idpurchase_plan_detail = $this->input->post('idpurchase_plan_detail');
        $idpaket_belanja_detail_sub = $this->input->post('idpaket_belanja_detail_sub');
        $volume = az_crud_number($this->input->post('volume'));
        $purchase_plan_detail_total = $this->input->post('purchase_plan_detail_total');
        $purchase_plan_date = az_crud_date($this->input->post('purchase_plan_date'));

		$this->load->library('form_validation');
		$this->form_validation->set_rules('idpaket_belanja_detail_sub', 'Uraian', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('volume', 'Volume', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			$this->db->where('idpurchase_plan',$idpurchase_plan);
			$purchase_plan = $this->db->get('purchase_plan');

			if ($purchase_plan->num_rows() > 0) {
				$status = $purchase_plan->row()->purchase_plan_status;

				$the_filter = array(
					'menu' => 'RENCANA PENGADAAN',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		// validasi dari realisasi anggaran
		if ($err_code == 0) {
			$this->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
			$pbds = $this->db->get('paket_belanja_detail_sub');

			$idsub_kategori = $pbds->row()->idsub_kategori;

			$the_filter = array(
				'idsub_kategori' => $idsub_kategori,
				'idpaket_belanja' => $idpaket_belanja,
				'transaction_date' => $purchase_plan_date,
				'idpurchase_plan_detail' => $idpurchase_plan_detail,
			);
			// var_dump($the_filter);die;

			// ambil data DPA
			$data_utama = $this->get_data_utama($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			// ambil data Rencana Anggaran Kegiatan (RAK) sampai bulan yang dipilih
			$data_rak = $this->get_data_rak($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;

			// ambil data uraian belanja yang sudah masuk di rencana pengadaan
			$data_realisasi = $this->get_data_rencana($the_filter);
			// echo "<pre>"; print_r($this->db->last_query()); die;
			

			// jika dicentang maka pengecekannya inputannya langsung dibandingkan dengan total keseluruhan volume dan total anggaran
			if (!aznav('role_bypass')) {
				// validasi apakah volume inputan + volume yang sudah direalisasikan melebihi volume RAK
				if ( (floatval($volume) + floatval($data_realisasi->row()->total_volume)) > floatval($data_rak->row()->total_rak_volume) ) {
					$sisa_volume = floatval($data_rak->row()->total_rak_volume) - floatval($data_realisasi->row()->total_volume);

					$err_code++;
					$err_message = "Volume yang diinputkan melebihi volume RAK pada bulan yang dipilih. <br>";
					$err_message .= "Sisa Volume yang bisa diinput pada bulan yang dipilih yaitu : ".$sisa_volume;
				}
				// var_dump('('.floatval($volume).' + '.floatval($data_realisasi->row()->total_volume).') > '.floatval($data_rak->row()->total_rak_volume)); echo "<br><br>";
			}

		
			if ($err_code == 0) {
				// validasi apakah volume yang sudah direalisasikan melebihi volume yang sudah ditentukan
				if ($data_utama->row()->volume < (floatval($data_realisasi->row()->total_volume) + floatval($volume))) {
					$sisa_volume = $data_utama->row()->volume - floatval($data_realisasi->row()->total_volume);

					// jika satuannya LS maka volumenya diloloskan, hanya validasi di harga saja
					if ($data_utama->row()->satuan != "LS") {
						$err_code++;
						$err_message = "Volume yang direalisasikan melebihi volume dari DPA. <br>";
						$err_message .= "Sisa Volume yang bisa diinput yaitu : ".$sisa_volume;
					}
				}
				// var_dump($data_utama->row()->volume.' < ('.floatval($data_realisasi->row()->total_volume).' + '.floatval($volume).')'); echo "<br><br>";
			}
		}
		// var_dump($err_message);die;
		
		if ($err_code == 0) {

			if (strlen($idpurchase_plan) == 0) {
				$arr_plan = array(
					'iduser_created' => $this->session->userdata('iduser'),
					'purchase_plan_date' => Date('Y-m-d H:i:s'),
					'purchase_plan_status' => 'DRAFT',
					'purchase_plan_code' => $this->generate_transaction_code(),
				);

				$save_plan = az_crud_save($idpurchase_plan, 'purchase_plan', $arr_plan);
				$idpurchase_plan = azarr($save_plan, 'insert_id');
			}
			else {
				// validasi apakah data paket belanja yang disimpan sama?
				// jika tidak maka data tidak perlu disimpan

				$this->db->where('status', 1);
				$this->db->where('idpaket_belanja', $idpaket_belanja);
				$this->db->where('idpurchase_plan', $idpurchase_plan);
				$ppd = $this->db->get('purchase_plan_detail');
				// echo "<pre>"; print_r($this->db->last_query()); die;

				if ($ppd->num_rows() == 0) {

					// validasi lanjutan
					$this->db->where('status', 1);
					$this->db->where('idpurchase_plan', $idpurchase_plan);
					$ppd = $this->db->get('purchase_plan_detail');
					// echo "<pre>"; print_r($this->db->last_query()); die;

					if ($ppd->num_rows() > 0) {
						$err_code++;
						$err_message = "Paket Belanja yang anda inputkan berbeda dengan paket belanja yang telah diinputkan sebelumnya. <br>";
						$err_message .= "Silahkan menginputkan data dengan paket belanja yang sama.";
					}

				}

				// validasi uraian tidak boleh sama dalam 1 transaksi
				if ($err_code == 0) {
					$this->db->where('status', 1);
					$this->db->where('idpurchase_plan', $idpurchase_plan);
					$this->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
					$this->db->where('idpurchase_plan_detail != "'.$idpurchase_plan_detail.'" ');
					$ppd = $this->db->get('purchase_plan_detail');
					// echo "<pre>"; print_r($this->db->last_query()); die;

					if ($ppd->num_rows() > 0) {
						$err_code++;
						$err_message = "Uraian yang anda inputan sama dengan data sebelumnya<br>";
						$err_message .= "Anda bisa mengedit uraian yang telah diinput.";
					}
				}


				if ($err_code == 0) {
					$this->db->where('purchase_plan.idpurchase_plan',$idpurchase_plan);
					if (strlen($idpurchase_plan_detail) > 0) {
						$this->db->where('purchase_plan_detail.idpurchase_plan_detail', $idpurchase_plan_detail);
					}
					$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan', 'left');
					$purchase_plan = $this->db->get('purchase_plan');
					// echo "<pre>"; print_r($this->db->last_query()); die;

					if ($purchase_plan->num_rows() > 0) {
						$status = $purchase_plan->row()->purchase_plan_status;
						$idsub = $purchase_plan->row()->idpaket_belanja_detail_sub;
						if ($status != "DRAFT") {
							// hitung total volume yang sudah terealisasi
							calculate_realisasi_volume($idsub, null, $idpurchase_plan_detail);
						}
					}
				}
			}

			if ($err_code == 0) {
				//purchase plan detail
				$arr_plan_detail = array(
					'idpurchase_plan' => $idpurchase_plan,
					'idpaket_belanja' => $idpaket_belanja,
					'idpaket_belanja_detail_sub' => $idpaket_belanja_detail_sub,
					'volume' => $volume,
					'purchase_plan_detail_total' => $purchase_plan_detail_total,
					
				);
				
				$td = az_crud_save($idpurchase_plan_detail, 'purchase_plan_detail', $arr_plan_detail);
				$idpurchase_plan_detail = azarr($td, 'insert_id');

				// hitung total transaksi
				$this->calculate_total_budget($idpurchase_plan);
			}

			if ($err_code == 0) {
				$this->db->where('idpurchase_plan',$idpurchase_plan);
				$purchase_plan = $this->db->get('purchase_plan');
				
				$status = $purchase_plan->row()->purchase_plan_status;
				if ($status != "DRAFT") {
					// hitung total volume yang sudah terealisasi
					calculate_realisasi_volume($idpaket_belanja_detail_sub);
				}
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'idpurchase_plan' => $idpurchase_plan,
			'idpurchase_plan_detail' => $idpurchase_plan_detail,
		);
		echo json_encode($return);
	}

	function edit_order() {
		$id = $this->input->post("id");

		$err_code = 0;
		$err_message = "";
		
		$this->db->where(' (pd_parent.idpurchase_plan_detail = "'.$id.'" OR pd_child.idpurchase_plan_detail = "'.$id.'") ');

        $this->db->where('pb.status', 1);
		// $this->db->where('pb.status_paket_belanja = "OK" ');
		$this->db->where('pbd.status', 1);
		
        $this->db->join('paket_belanja_detail pbd', 'pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');
        $this->db->join('sub_kategori sk_child', 'sk_child.idsub_kategori = pbds_child.idsub_kategori', 'left');
        $this->db->join('sub_kategori sk_parent', 'sk_parent.idsub_kategori = pbds_parent.idsub_kategori', 'left');
        $this->db->join('satuan satuan_child', 'satuan_child.idsatuan = pbds_child.idsatuan', 'left');
        $this->db->join('satuan satuan_parent', 'satuan_parent.idsatuan = pbds_parent.idsatuan', 'left');

		$this->db->join('purchase_plan_detail pd_parent', 'pd_parent.idpaket_belanja_detail_sub = pbds_parent.idpaket_belanja_detail_sub','left');
		$this->db->join('purchase_plan_detail pd_child', 'pd_child.idpaket_belanja_detail_sub = pbds_child.idpaket_belanja_detail_sub', 'left');

        $this->db->order_by("nama_paket_belanja");

		$this->db->select('
            pb.idpaket_belanja, 
            pb.nama_paket_belanja,
            COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS detail_sub_id, 
            COALESCE(sk_child.nama_sub_kategori, sk_parent.nama_sub_kategori) AS nama_sub_kategori, 
            COALESCE(pbds_child.volume, pbds_parent.volume) AS volume_paket_belanja,
            COALESCE(satuan_child.nama_satuan, satuan_parent.nama_satuan) AS nama_satuan,
            COALESCE(pbds_child.harga_satuan, pbds_parent.harga_satuan) AS harga_satuan, 
			COALESCE(pd_child.volume, pd_parent.volume) AS volume, 
			COALESCE(pd_child.purchase_plan_detail_total, pd_parent.purchase_plan_detail_total) AS purchase_plan_detail_total,
			COALESCE(pd_child.idpurchase_plan, pd_parent.idpurchase_plan) AS idpurchase_plan
        ');
		$pb = $this->db->get('paket_belanja pb');
        // echo "<pre>"; print_r($this->db->last_query()); die;

		$pb = $pb->result_array();

		$ret = array(
			'data' => azarr($pb, 0),
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
		$message = '';
		$idpurchase_plan = '';

		$this->db->where('idpurchase_plan_detail',$id);
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = purchase_plan_detail.idpurchase_plan');
		$purchase_plan = $this->db->get('purchase_plan_detail');

		if ($purchase_plan->num_rows() == 0) {
			$err_code++;
			$err_message = "Invalid ID";

			$is_delete = false;
		}

		if ($err_code == 0) {
			$idpurchase_plan = $purchase_plan->row()->idpurchase_plan;
			$status = $purchase_plan->row()->purchase_plan_status;
			$idpaket_belanja_detail_sub = $purchase_plan->row()->idpaket_belanja_detail_sub;
			$idpaket_belanja = $purchase_plan->row()->idpaket_belanja;

			$the_filter = array(
				'menu' => 'RENCANA PENGADAAN',
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
			// hitung total transaksi
			$this->calculate_total_budget($idpurchase_plan);

			// hitung total volume yang sudah terealisasi
			calculate_realisasi_volume($idpaket_belanja_detail_sub, $idpurchase_plan, $id);

			if ($err_code == 0) {
				// hapus detail rencana pengadaan
				$delete = az_crud_delete('purchase_plan_detail', $id, true);
			}
		}
		else{
			$err_code = 1;
			$err_message = "Data tidak bisa diedit atau dihapus.";
		}

		// cek apakah masih ada paket belanja/detail transaksi di realisasi anggaran ini?
		if ($err_code == 0) {
			$this->db->where('idpurchase_plan', $idpurchase_plan);
			$this->db->where('status', 1);
			$plan_detail = $this->db->get('purchase_plan_detail');

			if ($plan_detail->num_rows() == 0) {
				$arr_update = array(
					'purchase_plan_status' => 'DRAFT',
				);
				az_crud_save($idpurchase_plan, 'purchase_plan', $arr_update);

				$message = 'Uraian berhasil dihapus,';
				$message .= '<br><span style="color:red; font_weight:bold;">jika anda ingin menambahkan uraian baru, harap klik simpan rencana pengadaan, agar datanya tidak hilang.</span>';
			}
		}	

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'message' => $message,
			'idpurchase_plan' => $idpurchase_plan,
		);

		echo json_encode($return);
	}

	function save_plan() {
		$err_code = 0;
		$err_message = '';

		
		$idpurchase_plan = $this->input->post("hd_idpurchase_plan");
		$purchase_plan_date = az_crud_date($this->input->post("purchase_plan_date"));
		$iduser_created = $this->input->post("iduser_created");

		$this->load->library('form_validation');
		$this->form_validation->set_rules('purchase_plan_date', 'Tanggal Rencana', 'required|trim|max_length[200]');

		if ($this->form_validation->run() == FALSE) {
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			if (strlen($idpurchase_plan) == 0) {
				$err_code++;
				$err_message = 'Invalid ID';
			}
		}

		// validasi status ketika edit data
		if ($err_code == 0) {
			$this->db->where('purchase_plan.idpurchase_plan',$idpurchase_plan);
			$this->db->where('purchase_plan.status', 1);
			$this->db->where('purchase_plan_detail.status', 1);
			$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
			$purchase_plan = $this->db->get('purchase_plan');
			// echo "<pre>"; print_r($this->db->last_query()); die;

			if ($purchase_plan->num_rows() > 0) {
				$status = $purchase_plan->row()->purchase_plan_status;

				$the_filter = array(
					'menu' => 'RENCANA PENGADAAN',
					'type' => '',
				);
				$arr_validation = validation_status($the_filter);

				if (in_array($status, $arr_validation) ) {
					$err_code++;
					$err_message = "Data tidak bisa diedit atau dihapus.";
				}
			}	
		}

		// validasi apakah ada detail rencana pengadaan
		if ($err_code == 0) {
			$this->db->where('idpurchase_plan', $idpurchase_plan);
			$this->db->where('status', 1);
			$purchase_plan_detail = $this->db->get('purchase_plan_detail');

			if ($purchase_plan_detail->num_rows() == 0) {
				$err_code++;
				$err_message = "Tidak ada detail rencana pengadaan.";
			}
		}

		if ($err_code == 0) {
	    	$arr_data = array(
	    		'purchase_plan_date' => $purchase_plan_date,
	    		'purchase_plan_status' => "PROSES PENGADAAN",
	    		'iduser_created' => $iduser_created,
	    	);

	    	az_crud_save($idpurchase_plan, 'purchase_plan', $arr_data);

			// hitung total transaksi
			$this->calculate_total_budget($idpurchase_plan);

			foreach ($purchase_plan->result() as $key => $value) {
				$idpaket_belanja_detail_sub = $value->idpaket_belanja_detail_sub;
				$idpurchase_plan_detail = $value->idpurchase_plan_detail;
				
				// hitung total volume yang sudah terealisasi
				calculate_realisasi_volume($idpaket_belanja_detail_sub);

				$the_filter = array(
					'idpurchase_plan_detail' => $idpurchase_plan_detail,
					'status' => "PROSES PENGADAAN",
				);
				
				// update status paket belanja
				update_status_detail_purchase_plan($the_filter);
			}
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);
		echo json_encode($return);
	}

	function edit($id) {
		$this->db->where('idpurchase_plan', $id);
		$check = $this->db->get('purchase_plan');
		if ($check->num_rows() == 0) {
			redirect(app_url().'purchase_plan');
		} 
		else if($this->uri->segment(4) != "view_only") {
			$status = $check->row()->purchase_plan_status;

			$the_filter = array(
				'menu' => 'RENCANA PENGADAAN',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($status, $arr_validation) ) {
				redirect(app_url().'purchase_plan');
			}
		}
		$this->add($id);
	}

	function get_data() {
		$id = $this->input->post('id');

		$this->db->where('purchase_plan.idpurchase_plan', $id);
		$this->db->join('user', 'user.iduser = purchase_plan.iduser_created');
		$this->db->select('date_format(purchase_plan_date, "%d-%m-%Y %H:%i:%s") as txt_purchase_plan_date, purchase_plan_code, user.name as user_created, purchase_plan.iduser_created');
		$this->db->order_by('purchase_plan_date', 'desc');
		$purchase_plan = $this->db->get('purchase_plan')->result_array();

		$this->db->where('idpurchase_plan', $id);
		$purchase_plan_detail = $this->db->get('purchase_plan_detail')->result_array();

		$return = array(
			'purchase_plan' => azarr($purchase_plan, 0),
			'purchase_plan_detail' => $purchase_plan_detail
		);
		echo json_encode($return);
	}

	function delete_plan() {
		$id = $this->input->post('id');

		$err_code = 0;
		$err_message = '';

		$this->db->where('purchase_plan.idpurchase_plan', $id);
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$purchase_plan = $this->db->get('purchase_plan');

		if ($purchase_plan->num_rows() > 0) {
			$status = $purchase_plan->row()->purchase_plan_status;
			
			$the_filter = array(
				'menu' => 'RENCANA PENGADAAN',
				'type' => '',
			);
			$arr_validation = validation_status($the_filter);

			if (in_array($status, $arr_validation) ) {
				$err_code++;
				$err_message = "Data tidak bisa diedit atau dihapus.";
			}
		}

		if($err_code == 0) {
			// update status uraian di paket belanja
			foreach ($purchase_plan->result() as $key => $value) {

				// hitung total volume yang sudah terealisasi
				calculate_realisasi_volume($value->idpaket_belanja_detail_sub, $id);
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

	function get_list_order() {
		$idpurchase_plan = $this->input->post("idpurchase_plan");

		$this->db->where('purchase_plan_detail.idpurchase_plan = "'.$idpurchase_plan.'" ');
		$this->db->where('purchase_plan_detail.status', 1);
		$this->db->where('purchase_plan.status', 1);

		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');

		$this->db->select('purchase_plan.idpurchase_plan, purchase_plan_detail.idpurchase_plan_detail, paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori, purchase_plan_detail.volume, purchase_plan.total_budget, purchase_plan.purchase_plan_status');
		$purchase_plan = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query());die;

		$data = array(
			'detail' => $purchase_plan->result_array(),
		);

		$view = $this->load->view('purchase_plan/v_purchase_plan_table', $data, true);
		$arr = array(
			'data' => $view
		);
		echo json_encode($arr);
	}

	function calculate_total_budget($idpurchase_plan) {

		$this->db->where('status', 1);
		$this->db->where('idpurchase_plan', $idpurchase_plan);
		$this->db->select('sum(purchase_plan_detail_total) as purchase_plan_detail_total');
		$plan_detail = $this->db->get('purchase_plan_detail');

		$total_budget = azobj($plan_detail->row(), 'purchase_plan_detail_total', 0);

		$arr_update = array(
			'total_budget' => $total_budget,
		);

		az_crud_save($idpurchase_plan, 'purchase_plan', $arr_update);
	}

    private function generate_transaction_code() {
		$this->db->where('day(purchase_plan_date)', Date('d'));
		$this->db->where('month(purchase_plan_date)', Date('m'));
		$this->db->where('year(purchase_plan_date)', Date('Y'));
		$this->db->where('purchase_plan_code IS NOT NULL ');
		$this->db->order_by('purchase_plan_code desc');
		$data = $this->db->get('purchase_plan', 1);
		if ($data->num_rows() == 0) {
			$numb = '0001';

			$purchase_plan_code = 'PP'.Date('Ymd').$numb;

			$this->db->where('purchase_plan_code', $purchase_plan_code);
			$this->db->select('purchase_plan_code');
			$check = $this->db->get('purchase_plan');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($purchase_plan_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$purchase_plan_code = 'PP'.Date('Ymd').$numb;

				$this->db->where('purchase_plan_code', $purchase_plan_code);
				$this->db->select('purchase_plan_code');
				$check = $this->db->get('purchase_plan');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}
		else {
			$last = $data->row()->purchase_plan_code;
			$last = substr($last, 10);
			$numb = $last + 1;
			$numb = sprintf("%04d", $numb);

			$purchase_plan_code = 'PP'.Date('Ymd').$numb;

			$this->db->where('purchase_plan_code', $purchase_plan_code);
			$this->db->select('purchase_plan_code');
			$check = $this->db->get('purchase_plan');
			$ok = 0;
			if($check->num_rows() == 0) {
				$ok = 1;
			}

			while($ok == 0) {
				$last = substr($purchase_plan_code, 10);
				$numb = $last + 1;
				$numb = sprintf("%04d", $numb);

				$purchase_plan_code = 'PP'.Date('Ymd').$numb;

				$this->db->where('purchase_plan_code', $purchase_plan_code);
				$this->db->select('purchase_plan_code');
				$check = $this->db->get('purchase_plan');
				$ok = 0;
				if($check->num_rows() == 0) {
					$ok = 1;
				}
			}
		}

        // PP -> Purchase Plan

		return $purchase_plan_code;
	}

	function get_data_utama($the_data) {
		$idsub_kategori = azarr($the_data, 'idsub_kategori', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$add_select = azarr($the_data, 'add_select', '');

		// menampilkan data utama dari paket belanja
		$this->db->where('pb.idpaket_belanja = "'.$idpaket_belanja.'" ');
		$this->db->where('(pbds_child.idsub_kategori = "'.$idsub_kategori.'" OR pbds_parent.idsub_kategori = "'.$idsub_kategori.'")');
		$this->db->join('paket_belanja_detail pbd', 'pb.idpaket_belanja = pbd.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub pbds_parent', 'pbd.idpaket_belanja_detail = pbds_parent.idpaket_belanja_detail','left');
		$this->db->join('paket_belanja_detail_sub pbds_child', 'pbds_parent.idpaket_belanja_detail_sub = pbds_child.is_idpaket_belanja_detail_sub', 'left');
		$this->db->join('satuan sp', 'sp.idsatuan = pbds_parent.idsatuan','left');
		$this->db->join('satuan sc', 'sc.idsatuan = pbds_child.idsatuan','left');

		if (strlen($add_select) > 0) {
			$this->db->group_by('pb.idpaket_belanja, pb.nama_paket_belanja, pbd.idpaket_belanja_detail, detail_sub_id, idsub_kategori, volume, idsatuan, harga_satuan, jumlah, satuan');
		}

		$this->db->select('pb.idpaket_belanja,
			pb.nama_paket_belanja,
			pbd.idpaket_belanja_detail,
			COALESCE(pbds_child.idpaket_belanja_detail_sub, pbds_parent.idpaket_belanja_detail_sub) AS detail_sub_id,
			COALESCE(pbds_child.idsub_kategori, pbds_parent.idsub_kategori) AS idsub_kategori,
			COALESCE(pbds_child.volume, pbds_parent.volume) AS volume,
			COALESCE(pbds_child.idsatuan, pbds_parent.idsatuan) AS idsatuan,
			COALESCE(pbds_child.harga_satuan, pbds_parent.harga_satuan) AS harga_satuan,
			COALESCE(pbds_child.jumlah, pbds_parent.jumlah) AS jumlah, 
			COALESCE(sp.nama_satuan, sc.nama_satuan) AS satuan'.$add_select);
		$pb = $this->db->get('paket_belanja pb');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $pb;
	}

	function get_data_rak($the_data) {
		$idsub_kategori = azarr($the_data, 'idsub_kategori', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$transaction_date = azarr($the_data, 'transaction_date', '');

		$format_date = date("n", strtotime($transaction_date));

		$add_query_volume = '';
		$add_query_jumlah = '';
		for ($i = 0; $i < $format_date; $i++) { 
			if ($i == 0) {
				// untuk bulan Januari
				$add_query_volume .= 'COALESCE(pbds_child.rak_volume_januari, pbds_parent.rak_volume_januari, 0)';
				$add_query_jumlah .= 'COALESCE(pbds_child.rak_jumlah_januari, pbds_parent.rak_jumlah_januari, 0)';
			}
			else if ($i == 1) {
				// untuk bulan Februari
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_februari, pbds_parent.rak_volume_februari, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_februari, pbds_parent.rak_jumlah_februari, 0)';
			}
			else if ($i == 2) {
				// untuk bulan Maret
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_maret, pbds_parent.rak_volume_maret, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_maret, pbds_parent.rak_jumlah_maret, 0)';
			}
			else if ($i == 3) {
				// untuk bulan April
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_april, pbds_parent.rak_volume_april, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_april, pbds_parent.rak_jumlah_april, 0)';
			}
			else if ($i == 4) {
				// untuk bulan Mei
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_mei, pbds_parent.rak_volume_mei, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_mei, pbds_parent.rak_jumlah_mei, 0)';
			}
			else if ($i == 5) {
				// untuk bulan Juni
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_juni, pbds_parent.rak_volume_juni, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_juni, pbds_parent.rak_jumlah_juni, 0)';
			}
			else if ($i == 6) {
				// untuk bulan Juli
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_juli, pbds_parent.rak_volume_juli, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_juli, pbds_parent.rak_jumlah_juli, 0)';
			}
			else if ($i == 7) {
				// untuk bulan Agustus
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_agustus, pbds_parent.rak_volume_agustus, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_agustus, pbds_parent.rak_jumlah_agustus, 0)';
			}
			else if ($i == 8) {
				// untuk bulan September
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_september, pbds_parent.rak_volume_september, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_september, pbds_parent.rak_jumlah_september, 0)';
			}
			else if ($i == 9) {
				// untuk bulan Oktober
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_oktober, pbds_parent.rak_volume_oktober, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_oktober, pbds_parent.rak_jumlah_oktober, 0)';
			}
			else if ($i == 10) {
				// untuk bulan November
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_november, pbds_parent.rak_volume_november, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_november, pbds_parent.rak_jumlah_november, 0)';
			}
			else if ($i == 11) {
				// untuk bulan Desember
				$add_query_volume .= ' + COALESCE(pbds_child.rak_volume_desember, pbds_parent.rak_volume_desember, 0)';
				$add_query_jumlah .= ' + COALESCE(pbds_child.rak_jumlah_desember, pbds_parent.rak_jumlah_desember, 0)';
			}
		}

		$add_query_volume = rtrim($add_query_volume, ', '); // Hilangkan koma dan spasi di akhir
		$add_query_jumlah = rtrim($add_query_jumlah, ', '); // Hilangkan koma dan spasi di akhir
		
		$add_query_volume = 'SUM(' . rtrim($add_query_volume, ', ') . ') AS total_rak_volume';
		$add_query_jumlah = 'SUM(' . rtrim($add_query_jumlah, ', ') . ') AS total_rak_jumlah';

		// var_dump($add_query_volume); echo "<br><br>";
		// var_dump($add_query_jumlah);
		// die;

		$the_filter = array(
			'idsub_kategori' => $idsub_kategori,
			'idpaket_belanja' => $idpaket_belanja,
			'transaction_date' => $transaction_date,
			'add_select' => ', ' .$add_query_volume . ', ' . $add_query_jumlah, // digunakan untuk menyisipkan query tambahan pada select di fungsi get_data_utama
		);

		// menampilkan data Rencana Anggaran Kegiatan (RAK) sampai dengan tanggal inputan
		$db = $this->get_data_utama($the_filter);
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $db;
	}

	function get_data_rencana($the_data) {
		$idsub_kategori = azarr($the_data, 'idsub_kategori', '');
		$idpaket_belanja = azarr($the_data, 'idpaket_belanja', '');
		$transaction_date = azarr($the_data, 'transaction_date', '');
		$idpurchase_plan_detail = azarr($the_data, 'idpurchase_plan_detail', '');

		$format_year = date("Y", strtotime($transaction_date));
		$format_month = date("m", strtotime($transaction_date));

		// menampilkan data rencana yang sudah ada sampai dengan tanggal inputan
		$this->db->where('purchase_plan.status', 1);
		$this->db->where('purchase_plan_detail.status', 1);
		if (strlen($idpurchase_plan_detail) > 0) {
			$this->db->where('purchase_plan_detail.idpurchase_plan_detail != "'.$idpurchase_plan_detail.'" ');
		}
		$this->db->where('paket_belanja_detail_sub.idsub_kategori', $idsub_kategori);
		$this->db->where('purchase_plan_detail.idpaket_belanja', $idpaket_belanja);
		$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") >=', $format_year . '-01');
		$this->db->where('DATE_FORMAT(purchase_plan.purchase_plan_date, "%Y-%m") <=', $format_year . '-' . $format_month);
		$this->db->where('purchase_plan.purchase_plan_status != "DRAFT" ');

		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');

		$this->db->select('
			count(purchase_plan.idpurchase_plan), 
			sum( COALESCE(purchase_plan_detail.volume_realization, purchase_plan_detail.volume) ) as total_volume
		');
		$data = $this->db->get('purchase_plan');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		return $data;
	}
}
