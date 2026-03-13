<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API Controller untuk mengekspor data grafik dari dashboard.
 *
 * URL contoh:
 *  - http://domain.com/api/dashboard/get_dashboard_data
 *  - http://domain.com/api/dashboard/get_dashboard_data?tahun=2026
 */
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

    /**
     * Mengembalikan data dashboard dalam format JSON.
     */
    public function get_dashboard_data() {
        header('Content-Type: application/json');

        try {
            $tahun = $this->input->get('tahun');
            $data = $this->Dashboard_model->get_dashboard_data($tahun);

            $response = [
                'status' => true,
                'message' => 'success',
                'data' => $data,
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'failed to load dashboard data: ' . $e->getMessage(),
                'data' => new stdClass(),
            ];
            echo json_encode($response);
        }
    }
}
