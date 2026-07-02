<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_pad_target extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('master_pad_target');
        $this->table = 'pad_target';
        $this->controller = 'master_pad_target';
        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();
		$this->load->helper('az_role');

		$crud->set_column(array('#', 'Tahun', 'Target Per Tahun', azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$tahun = $azapp->add_datetime();
		$tahun->set_id('vf_tahun');
		$tahun->set_name('vf_tahun');
		$tahun->set_value(Date('Y'));
		$tahun->set_format('YYYY');
		$data['tahun'] = $tahun->render();

		$crud->add_aodata('vf_tahun', 'vf_tahun');

		$filter = $this->load->view('pad_target/vf_target', $data, true);
		$crud->set_top_filter($filter);

		$v_modal = $this->load->view('pad_target/v_target', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Target"));
		$v_modal = $crud->generate_modal();

		$js = az_add_js('pad_target/vjs_target');
		$azapp->add_js($js);

		// $crud->set_callback_edit('
		// 	check_copy();
        // ');
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('PAD Target');
		$data_header['breadcrumb'] = array('master_pad_online', 'master_pad_target');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$tahun = $this->input->get('vf_tahun');

		$crud->set_select_table('idpad_target, tahun, target_per_tahun');
		$crud->set_filter('tahun, target_per_tahun');
		$crud->set_sorting('tahun, target_per_tahun');
		$crud->set_select_align('center, center');
		$crud->set_id($this->controller);
		$crud->add_where('status = "1" ');
		if (strlen($tahun) > 0) {
			$crud->add_where('tahun = "' . $tahun . '"');
		}
		$crud->set_custom_style('custom_style');
		$crud->set_table($this->table);
		$crud->set_order_by('idpad_target DESC');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {

		if ($key == 'target_per_tahun') {
			return 'Rp. '.az_thousand_separator($value);
		}
		return $value;
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('tahun', 'Tahun', 'required|trim|max_length[200]');
		$this->form_validation->set_rules('target_per_tahun', 'Target per Tahun', 'required|trim|max_length[200]');
		
		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){

			$data_save = array(
				'tahun' => az_crud_number(azarr($data_post, 'tahun')),
				'target_per_tahun' => az_crud_number(azarr($data_post, 'target_per_tahun')),
			);
            // echo "<pre>"; print_r($data_save); die();

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_crud_edit('idpad_target, tahun, target_per_tahun');
	}

	public function delete() {
		$id = $this->input->post('id');

		az_crud_delete($this->table, $id);
	}
}