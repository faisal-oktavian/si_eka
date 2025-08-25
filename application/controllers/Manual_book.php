<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manual_book extends CI_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('az_auth');
		az_check_auth('manual_book');
	}

	public function index()
	{
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$data_header['title'] = azlang("Buku Petunjuk");
		$data_header['breadcrumb'] = array('manual_book');
		$azapp->set_data_header($data_header);

		$content = $this->load->view("manual_book/v_manual_book", '', true);
		$azapp->add_content($content);

		echo $azapp->render();
	}
}
