<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        $this->load->helper('az_config');
        az_check_auth('user_user');

        $this->table = 'user';
        // $this->table_column = 'iduser, idrole, name, username, email, address';
        $this->table_column = 'iduser, idrole, name, username, user.is_active';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->controller = 'user';
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$arr_column = array(azlang('No'), azlang('Role Name'), azlang('Name'), azlang('Username'));
		$arr_column = array_merge($arr_column, array(azlang('Status'), azlang('Action')));
		$crud->set_column($arr_column);
		$crud->set_id($this->table);
		$crud->set_th_class("no-sort, , , ,no-sort");
		$crud->set_width('10px, , , , , 150px');
		$crud->set_default_url(true);

		$file = $azapp->add_file();
        $file->set_page_id($this->controller);
        $file->set_file_size('1 MB');
        $file->set_id('photo');
        $file->set_file_dir('photo');
        $file->set_path(base_url().AZAPP.'assets/');
        $data['photo'] = $file->render();

		$v_modal = $this->load->view('user/v_user', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("User"));
		$v_modal = $crud->generate_modal();


        $crud->set_callback_edit("
        	callback_photo(response);
        ");

		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('User');
		$data_header['breadcrumb'] = array('user', 'user_user');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$crud = $this->crud;
		$crud->set_select('iduser, role.title as role_name, user.name as user_name, username, user.is_active');
		$crud->set_select_table('iduser, role_name, user_name, username, is_active');
		$crud->add_join('role');
		$crud->set_filter('user.name');
		$crud->set_sorting('role_name, user_name, phone, username');
		$crud->set_id($this->table);
		$crud->add_where("user.status > 0");
		$crud->set_table($this->table);
		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		if($key == 'is_active') {
			$tlbl = 'TIDAK AKTIF';
			$lbl = 'warning';
			if($value) {
				$lbl = 'success';
				$tlbl = 'AKTIF';
			}

			return "<label class='label label-".$lbl."'>".$tlbl."</label>";
		}
		if ($key == 'photo') {
			if (strlen($value) > 0) {
				return "<div class='img-employee'><img src='".base_url().AZAPP."assets/photo/".$value."'></div>";
			}
			return "<div class='img-employee'><img src='".base_url().AZAPP."assets/images/no-image.jpg'></div>";
		}
		return $value;
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('name', azlang('Name'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('username', azlang('Username'), 'required|trim|max_length[30]');
		if (strlen($idpost) == 0) {
			$this->form_validation->set_rules('password', azlang('Password'), 'required|trim|max_length[300]');
		}

		$err_code = 0;
		$err_message = "";

		if($this->form_validation->run() == FALSE){
			$err_code++;
			$err_message = validation_errors();
		}

		if ($err_code == 0) {
			$username = $this->input->post('username');
			$this->db->where("username", $username);
			$this->db->where('status', 1);
			if (strlen($idpost) > 0) {
				$this->db->where('iduser !=', $idpost);
			}
			$check = $this->db->get('user');

			if ($check->num_rows() > 0) {
				$err_code++;
				$err_message = 'Username telah digunakan';
			}
		}

		if ($err_code == 0) {
			$data_save = array(
				'idrole' => azarr($data_post, 'idrole'),
				"name" => azarr($data_post, 'name'),
				"username" => azarr($data_post, 'username'),
				"is_active" => azarr($data_post, 'is_active'),
			);

			$password = azarr($data_post, 'password');
			$pw = true;
			if (strlen($idpost) > 0 && strlen($password) == 0) {
				$pw = false;
			}

			if ($pw) {
				$data_save['password'] = md5($password);
			}

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function edit() {
		$data_edit = az_crud_edit($this->table_column, TRUE);

		$return = $data_edit;
		echo json_encode($return);
	}

	public function delete() {
		$id = $this->input->post("id");
		az_crud_delete('user', $id);
	}
}