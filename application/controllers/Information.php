<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Information extends CI_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('az_auth');
		az_check_auth('information');
	}

	public function index()
	{
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$data_header['title'] = azlang("Information");
		$data_header['breadcrumb'] = array('information');
		$azapp->set_data_header($data_header);

		$this->load->model('M_information');
		$data['information'] = $this->M_information->get_information();

		$content = $this->load->view("information/v_information", $data, true);
		$azapp->add_content($content);

		$js = $this->load->view('information/vjs_information', '', true);
		$js = str_replace('<script>', '', $js);
		$azapp->add_js($js);
		echo $azapp->render();
	}


	public function save()
	{
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('PPTK', azlang('PPTK'), 'required|trim|max_length[200]');

		$data_post = $this->input->post();
		$err_code = 0;
		$err_message = '';

		if ($this->form_validation->run() == TRUE) {
			$this->load->model('M_information');
			foreach ($data_post as $key => $value) {
				$this->M_information->save_information($key, array('value' => $value));
			}
		}
		$data["sMessage"] = validation_errors() . $err_message;
		echo json_encode($data);
	}
}
