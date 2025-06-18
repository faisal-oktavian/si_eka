<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends AZ_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        az_check_auth('dashboard');
    }

	public function index(){
		$this->load->library('AZApp');
		$app = $this->azapp;
		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$app->set_data_header($data_header);
		$this->load->helper('az_config');
		$this->load->helper('az_core');

		// $this->db->where('status', 1);
		// $this->db->where('is_active', 1);
		// $rak = $this->db->get('rak');

		// $arr_data = array();
		// foreach ($rak->result() as $key => $value) {

		// 	$out = $this->get_total_out($value->idrak);
		// 	$remains = $value->capacity - $out;

		// 	$arr_data[] = array(
		// 		'rak_name' => $value->rak_name,
		// 		'capacity' => $value->capacity,
		// 		'remains' => $remains,
		// 	);
		// }

		// $data['data_rak'] = $arr_data;

		$view = $this->load->view('home/v_home', '', true);
		$app->add_content($view);

		$js = az_add_js('home/vjs_home');
		$app->add_js($js);

		echo $app->render();	
	}
}