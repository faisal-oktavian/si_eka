<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_pad extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('report_pad');
        $this->controller = 'report_pad';

        $this->load->helper('az_crud');
        $this->load->helper('az_config');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$this->load->helper('az_role');

		$view = $this->load->view('report_pad/v_report_pad', '', true);
		$azapp->add_content($view);

		$data_header['title'] = "Laporan PAD";
		$data_header['breadcrumb'] = array('report_pad');
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}
}