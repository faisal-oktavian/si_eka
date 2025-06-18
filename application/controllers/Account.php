<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $iduser = $this->session->userdata("iduser");
        if (strlen($iduser) == 0) {
            redirect(app_url());
        }
    }

    public function change_password() {
        $this->load->library("AZApp");
        $azapp = $this->azapp;

        $view = $this->load->view("account/v_change_password", "", true);
        $azapp->add_content($view);

        $js = $this->load->view('account/vjs_change_password', '', true);
        $js = str_replace('<script>', '', $js);
        $azapp->add_js($js);

        $data["title"] = azlang('Change Password');
        $azapp->set_data_header($data);
        echo $azapp->render();
    }

    public function change_password_process() {
        $iduser = $this->session->userdata("iduser");
        $post_data = $this->input->post();

        $old_password = azarr($post_data, "old_password");
        $new_password = azarr($post_data, "new_password");
        $confirm_password = azarr($post_data, "confirm_password");

        $err_code = 0;
        $err_message = "";

        if ($this->config->item('demo')) {
            $err_code++;
            $err_message = azlang('Demo version');
        }

        $this->load->helper('az_config');
        $password_sha = az_get_config('password_sha', 'config_app');

        if ($err_code == 0) {
            if (strlen($old_password) == 0) {
                $err_code++;
                $err_message = azlang('Old password required');
            }
            else if (strlen($new_password) == 0){
                $err_code++;
                $err_message = azlang('New password required');
            }
            else if (strlen($confirm_password) == 0) {
                $err_code++;
                $err_message = azlang('Confirm password required');
            }
            else if ($new_password != $confirm_password) {
                $err_code++;
                $err_message = azlang('Confirm password not valid');
            }
            else {
                if($password_sha) {
                    // new password hash
                    $secure_old_password = password_hash($old_password, PASSWORD_DEFAULT);

                    $this->db->where("iduser", $iduser);
                    $rdata = $this->db->get("user");
                    if ($rdata->num_rows() > 0) {
                        $db_password = $rdata->row()->password;

                        if(password_verify($old_password, $db_password)) {
                            // ok
                        } else {
                            $err_code++;
                            $err_message = azlang('Wrong old password');
                        }
                    }

                } else {
                    $this->db->where("iduser", $iduser);
                    $this->db->group_start();
                    $this->db->where("password", md5($old_password));
                    $this->db->or_where("password", sha1($old_password));
                    $this->db->group_end();
                    $rdata = $this->db->get("user");
                    if ($rdata->num_rows() == 0) {
                        $err_code++;
                        $err_message = azlang('Wrong old password');
                    }
                }
            }
        }

        if ($err_code == 0) {
            $this->db->where("iduser", $iduser);
            if($password_sha) {
                // new password hash
                $secure_password = password_hash($new_password, PASSWORD_DEFAULT);

                $this->db->set("password", $secure_password);
            } else {
                $this->db->set("password", md5($new_password));
            }
            $this->db->update("user");
        }

        $data = array();
        $data["sMessage"] = $err_message;
        echo json_encode($data);
    }
}