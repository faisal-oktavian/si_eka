<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_role extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('user_user_role');

        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$this->config->load('menu');
		$this->load->helper('array');
		$menu = $this->config->item('menu');

		$role = $this->generate_role($menu);
		$data['role'] = $role;

		$view = $this->load->view('user_role/v_user_role', $data, true);
		$azapp->add_content($view);
			
		$vjs = $this->load->view('user_role/vjs_user_role', '', true);
		$vjs = str_replace('<script>', '', $vjs);
		$azapp->add_js($vjs);

		$data_header['title'] = azlang('User Role');
		$data_header['breadcrumb'] = array('user', 'user_user_role');
		$azapp->set_data_header($data_header);

		echo $azapp->render();	
	}

	function save() {
		$data_post = $this->input->post();
		$err_code = 0;
		$err_message = '';

		if ($this->config->item('demo')) {
			$err_code++;
			$err_message = azlang('Demo version');
		}
		
		if ($err_code == 0) {
			$this->load->config('menu');
	        $menu = $this->config->item('menu');

	        $arr_menu_role = array();
	        foreach ($menu as $key => $value) {
	            $name = azarr($value, 'name');
	            $menu_role = azarr($value, 'role', array());
	            if (count($menu_role) > 0) {
	            	$arr_menu_role[$name] = $menu_role;
	            }
	            $submenu_role = azarr($value, 'submenu');
	            foreach ($submenu_role as $srkey => $srvalue) {
	            	$sub_role_name = azarr($srvalue, 'name');
	            	$sub_role = azarr($srvalue, 'role', array());
	            	if (count($sub_role) > 0) {
	            		$arr_menu_role[$sub_role_name] = $sub_role;
	            	}
	            	$subonsubmenu_role = azarr($srvalue, 'submenu');
		            foreach ($subonsubmenu_role as $sosrkey => $sosrvalue) {
		            	$subonsub_role_name = azarr($sosrvalue, 'name');
		            	$subonsub_role = azarr($sosrvalue, 'role', array());
		            	if (count($subonsub_role) > 0) {
		            		$arr_menu_role[$subonsub_role_name] = $subonsub_role;
		            	}
		            }
	            }
	        }

			if (count($data_post) == 0) {
				$err_code++;
				$err_message = azlang('Data kosong');
			}
		}


		if ($err_code == 0) {
			$role_name = azarr($data_post, 'role_name', array());
			$idrole = azarr($data_post, 'idrole');
			$access = azarr($data_post, 'access', array());
			
			foreach ($role_name as $key => $value) {
				$db_access = azarr($access, $value, 0);

				$role_in_menu = azarr($arr_menu_role, $value, array());
				$role_access = azarr($data_post, 'access_role_'.$value, array());
				
				foreach ($role_in_menu as $role_key => $role_value) {
					$rim_role_name = azarr($role_value, 'role_name');
					$db_access_role = azarr($role_access, $rim_role_name, 0);
					$this->process_role($idrole, $rim_role_name, $db_access_role);
				}
				$this->process_role($idrole, $value, $db_access);
			}
		}

		if ($err_code == 0) {
			$this->session->set_flashdata('msg', azlang('Save success'));
		}
		else {
			$this->session->set_flashdata('msg', $err_message);
		}
		redirect(app_url().'user_role');

	}

	function process_role($idrole, $value, $access) {
		$this->db->where('idrole', $idrole);
		$this->db->where('menu_name', $value);
		$check = $this->db->get('user_role');

		$data['access'] = $access;
		$data['idrole'] = $idrole;
		if ($check->num_rows() > 0) {
			$data['updated'] = Date('Y-m-d H:i:s');
			$data['updatedby'] = $this->session->userdata('username');
			$this->db->where('idrole', $idrole);
			$this->db->where('menu_name', $value);
			$this->db->update('user_role', $data);
		}
		else {
			$data['menu_name'] = $value;
			$data['created'] = Date('Y-m-d H:i:s');
			$data['createdby'] = $this->session->userdata('username');
			$this->db->insert('user_role', $data);
		}
	}

	function generate_role($data_role = array(), $sub_count = 0) {
		$ret = '';
		$sub_count++;

		foreach ($data_role as $key => $value) {    
		    $sub = '';
		    if (isset($value['submenu'])) {
			    if (count($value['submenu']) > 0) {
			        $sub .= $this->generate_role($value['submenu'], $sub_count);
			    }
		    }

		    $role = azarr($value, 'role', array());
		    $txt_role = '';
		    foreach ($role as $role_key => $role_value) {
		    	$txt_role .= "<label class='input-label'><input type='checkbox' class='access access-".azarr($role_value, 'role_name')."' value='1' name='access_role_".azarr($value, 'name')."[".azarr($role_value, 'role_name')."]'>&nbsp;<span>".azarr($role_value, 'role_title')."</span></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		    }

		    $separate = '';
		    for ($i=1; $i < $sub_count; $i++) { 
		        $separate .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		    }

		    $ret .= '<tr>';
		    $ret .= '<input type="hidden" name="role_name[]" value="'.azarr($value, 'name').'">';
		    $ret .= '	<td>'.$separate.azarr($value, 'title').'</td>';
		    $ret .= '	<td align="center"><input type="checkbox" class="access access-'.azarr($value, 'name').'" value="1" name="access['.azarr($value, 'name').']"></td>';
		    $ret .= '	<td>'.$txt_role.'</td>';
		    $ret .= '</tr>';
		    $ret .= $sub;
		}

		return $ret;
	}

	function generate_access() {
		$id = $this->input->post('idrole');
		$this->db->where('idrole', $id);
		$data = $this->db->get('user_role');
		$return = array();
		foreach ($data->result() as $key => $value) {
			$ret = array(
				'menu_name' => $value->menu_name,
				'access' => $value->access
			);
			$return[] = $ret;
		}

		echo json_encode($return);
	}
}