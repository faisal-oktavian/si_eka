<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Information extends CI_Controller
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('az_auth');
		$this->load->helper('az_crud');
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

		$anggaran_APBD = $this->input->post('anggaran_APBD');
		$type = $this->input->post('type');
		$year = date('Y');

		$this->form_validation->set_rules('PPTK', azlang('PPTK'), 'required|trim|max_length[200]');

		$data_post = $this->input->post();
		$err_code = 0;
		$err_message = '';

		if ($this->form_validation->run() === TRUE) {
			$this->db->trans_begin();
			
			$this->load->model('M_information');
			foreach ($data_post as $key => $value) {
				$this->M_information->save_information($key, array('value' => $value));
			}

			if ($anggaran_APBD !== null) {
				if ($anggaran_APBD == '2' && $type == "lock") {
					// kunci anggaran APBD PERUBAHAN

					$check_locked = 1;
					$pb_data = $this->get_active_paket_belanja($year, $check_locked);

					if ($pb_data->num_rows() === 0) {
						$err_message = azlang('Tidak ada paket belanja yang bisa diproses.');
						$err_code = 1;
					} 
					else {
						$type = "APBD PERUBAHAN";
						$duplicate_result = $this->duplicate_paket_belanja_to_apbd($pb_data->result_array(), $type);
						
						if ($duplicate_result['success'] === true) {
							$this->lock_paket_belanja($duplicate_result['ids'], $anggaran_APBD);
						} 
						else {
							$err_message = $duplicate_result['message'];
							$err_code = 1;
						}
					}
				}
				else if ($anggaran_APBD == '1' && $type == "lock") {
					// kunci anggaran APBD

					$check_locked = 0;
					$pb_data = $this->get_active_paket_belanja($year, $check_locked);

					if ($pb_data->num_rows() === 0) {
						$err_message = azlang('Tidak ada paket belanja yang bisa diproses.');
						$err_code = 1;
					} 
					else {
						$type = "APBD";
						$duplicate_result = $this->duplicate_paket_belanja_to_apbd($pb_data->result_array(), $type);
						
						if ($duplicate_result['success'] === true) {
							$this->lock_paket_belanja($duplicate_result['ids'], $anggaran_APBD);
						} 
						else {
							$err_message = $duplicate_result['message'];
							$err_code = 1;
						}
					}
				}
				else if ($anggaran_APBD == '1' && $type == "unlock") {
					// buka kunci anggaran APBD PERUBAHAN

					$check_locked = 2;
					$type = "APBD PERUBAHAN";
					$pb_data = $this->get_active_paket_belanja($year, $check_locked);
					
					if ($pb_data->num_rows() === 0) {
						$err_message = azlang('Tidak ada paket belanja yang bisa dibuka.');
						$err_code = 1;
					} 
					else {
						$ids_to_unlock = array_column($pb_data->result_array(), 'idpaket_belanja');
						// $delete_result = $this->delete_apbd_data($ids_to_unlock);
						$delete_result = $this->return_paket_belanja_to_apbd($ids_to_unlock, $type);
						
						if ($delete_result['success'] === true) {
							$this->unlock_paket_belanja($ids_to_unlock, $anggaran_APBD);
						} 
						else {
							$err_message = $delete_result['message'];
							$err_code = 1;
						}
					}
				}
				else if ($anggaran_APBD == '0' && $type == "unlock") {
					// buka kunci anggaran APBD

					$check_locked = 1;
					$type = "APBD";
					$pb_data = $this->get_active_paket_belanja($year, $check_locked);
					
					if ($pb_data->num_rows() === 0) {
						$err_message = azlang('Tidak ada paket belanja yang bisa dibuka.');
						$err_code = 1;
					} 
					else {
						$ids_to_unlock = array_column($pb_data->result_array(), 'idpaket_belanja');
						// $delete_result = $this->delete_apbd_data($ids_to_unlock);
						$delete_result = $this->return_paket_belanja_to_apbd($ids_to_unlock, $type);
						
						if ($delete_result['success'] === true) {
							$this->unlock_paket_belanja($ids_to_unlock, $anggaran_APBD);
						} 
						else {
							$err_message = $delete_result['message'];
							$err_code = 1;
						}
					}
				}
			}

			if ($err_code !== 0 || $this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				if ($err_message === '') {
					$err_message = azlang('Proses gagal, Data gagal disimpan.');
				}
			} else {
				$this->db->trans_commit();
			}
		} 
		else {
			$err_message = validation_errors();
			$err_code = 1;
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	private function get_active_paket_belanja($year, $check_lock)
	{
		// $this->db->where('idpaket_belanja', '76'); // testing
		
		$this->db->where('urusan_pemerintah.tahun_anggaran_urusan', $year);
		$this->db->where('urusan_pemerintah.is_active', 1);
		$this->db->where('urusan_pemerintah.status', 1);
		$this->db->where('bidang_urusan.status', 1);
		$this->db->where('program.status', 1);
		$this->db->where('kegiatan.status', 1);
		$this->db->where('sub_kegiatan.status', 1);
		$this->db->where('paket_belanja.status', 1);
		$this->db->where('paket_belanja.status_paket_belanja !=', 'DRAFT');

		if ($check_lock == '2') {
			$this->db->where('paket_belanja.is_locked', 2);
		} 
		else if ($check_lock == '1') {
			$this->db->where('paket_belanja.is_locked', 1);
		} 
		else if ($check_lock == '0') {
			$this->db->where('paket_belanja.is_locked', 0);
		}

		$this->db->join('sub_kegiatan', 'sub_kegiatan.idsub_kegiatan = paket_belanja.idsub_kegiatan');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = paket_belanja.idkegiatan');
		$this->db->join('program', 'program.idprogram = paket_belanja.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');

		$this->db->select('paket_belanja.*');
		$pb = $this->db->get('paket_belanja');
		// echo "<pre>"; print_r($this->db->last_query()); die;
		
		return $pb;
	}

	private function duplicate_paket_belanja_to_apbd(array $pb_rows, $type) {
		$inserted_ids = array();

		$this->db->select_max('sequence');
		$pb_apbd = $this->db->get('paket_belanja_apbd');
		$sequence = $pb_apbd->row()->sequence;
		$sequence = $sequence ? $sequence + 1 : 1;

		foreach ($pb_rows as $pb) {
			$idpaket_belanja = $pb['idpaket_belanja'];
			$inserted_ids[] = $idpaket_belanja;

			$pb_copy = $pb;
			unset($pb_copy['created'], $pb_copy['createdby'], $pb_copy['updated'], $pb_copy['updatedby'], $pb_copy['status'], $pb_copy['is_locked'], $pb_copy['lockedby'], $pb_copy['locked_date'], $pb_copy['apbd_lockedby'], $pb_copy['apbd_locked_date'], $pb_copy['apbd_changes_lockedby'], $pb_copy['apbd_changes_locked_date']);
			$pb_copy['jenis'] = $type;
			$pb_copy['sequence'] = $sequence;
			
			// echo "<pre>"; print_r($pb_copy); die;

			$save_apbd = az_crud_save('', 'paket_belanja_apbd', $pb_copy);
			$idpaket_belanja_apbd = azarr($save_apbd, 'insert_id');

			if (!$idpaket_belanja_apbd) {
				return array('success' => false, 'message' => azlang('Duplikasi data gagal[pbp].'));
			}

			$this->db->where('idpaket_belanja', $idpaket_belanja);
			$this->db->where('status', 1);
			$pb_detail_data = $this->db->get('paket_belanja_detail');
			// echo "<pre>"; print_r($this->db->last_query());die;

			foreach ($pb_detail_data->result_array() as $pbd) {
				$idpaket_belanja_detail = $pbd['idpaket_belanja_detail'];
				$pbd_copy = $pbd;

				unset($pbd_copy['idpaket_belanja'], $pbd_copy['created'], $pbd_copy['createdby'], $pbd_copy['updated'], $pbd_copy['updatedby'], $pbd_copy['status']);
				$pbd_copy['idpaket_belanja_apbd'] = $idpaket_belanja_apbd;
				
				// echo "<pre>"; print_r($pbd_copy); die;

				$save_apbd_detail = az_crud_save('', 'paket_belanja_apbd_detail', $pbd_copy);
				$idpaket_belanja_apbd_detail = azarr($save_apbd_detail, 'insert_id');
				
				if (!$idpaket_belanja_apbd_detail) {
					return array('success' => false, 'message' => azlang('Duplikasi data gagal[pbpd].'));
				}

				// ambil data paket belanja detail sub yang tidak ada turunannya (is_idpaket_belanja_detail_sub)
				$this->db->where('idpaket_belanja_detail', $idpaket_belanja_detail);
				$this->db->where('status', 1);
				$pb_detail_sub_data = $this->db->get('paket_belanja_detail_sub');
				// echo "<pre>"; print_r($this->db->last_query());die;

				foreach ($pb_detail_sub_data->result_array() as $pbd_sub) {
					$idpaket_belanja_detail_sub = $pbd_sub['idpaket_belanja_detail_sub'];
					$pbd_sub_copy = $pbd_sub;
					
					unset($pbd_sub_copy['idpaket_belanja'], $pbd_sub_copy['idpaket_belanja_detail'], $pbd_sub_copy['is_idpaket_belanja_detail_sub'], $pbd_sub_copy['created'], $pbd_sub_copy['createdby'], $pbd_sub_copy['updated'], $pbd_sub_copy['updatedby'], $pbd_sub_copy['status']);
					$pbd_sub_copy['idpaket_belanja_apbd'] = $idpaket_belanja_apbd;
					$pbd_sub_copy['idpaket_belanja_apbd_detail'] = $idpaket_belanja_apbd_detail;
					$pbd_sub_copy['is_idpaket_belanja_apbd_detail_sub'] = null;

					// echo "<pre>"; print_r($pbd_sub_copy); die;

					$save_apbd_detail_sub = az_crud_save('', 'paket_belanja_apbd_detail_sub', $pbd_sub_copy);
					$idpaket_belanja_apbd_detail_sub = azarr($save_apbd_detail_sub, 'insert_id');
					
					if (!$idpaket_belanja_apbd_detail_sub) {
						return array('success' => false, 'message' => azlang('Duplikasi data gagal[pbpds].'));
					}


					// cek idpaket_belanja_detail_sub apakah punya turunannya (is_idpaket_belanja_detail_sub)
					$this->db->where('is_idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
					$this->db->where('status', 1);
					$pb_detail_sub_child_data = $this->db->get('paket_belanja_detail_sub');
					// echo "<pre>"; print_r($this->db->last_query());die;

					if ($pb_detail_sub_child_data->num_rows() > 0) {
						foreach ($pb_detail_sub_child_data->result_array() as $pbd_sub_child) {
							$idpaket_belanja_detail_sub_child = $pbd_sub_child['idpaket_belanja_detail_sub'];
							$pbd_sub_child_copy = $pbd_sub_child;
							
							unset($pbd_sub_child_copy['idpaket_belanja'], $pbd_sub_child_copy['idpaket_belanja_detail'], $pbd_sub_child_copy['is_idpaket_belanja_detail_sub'], $pbd_sub_child_copy['created'], $pbd_sub_child_copy['createdby'], $pbd_sub_child_copy['updated'], $pbd_sub_child_copy['updatedby'], $pbd_sub_child_copy['status']);
							$pbd_sub_child_copy['idpaket_belanja_apbd'] = $idpaket_belanja_apbd;
							$pbd_sub_child_copy['idpaket_belanja_apbd_detail'] = null;
							$pbd_sub_child_copy['is_idpaket_belanja_apbd_detail_sub'] = $idpaket_belanja_apbd_detail_sub;

							$save_apbd_detail_sub_child = az_crud_save('', 'paket_belanja_apbd_detail_sub', $pbd_sub_child_copy);
							$idpaket_belanja_apbd_detail_sub_child = azarr($save_apbd_detail_sub_child, 'insert_id');
							
							if (!$idpaket_belanja_apbd_detail_sub_child) {
								return array('success' => false, 'message' => azlang('Duplikasi data gagal[pbpdsp].'));
							}
						}
					}
				}
			}
		}

		return array('success' => true, 'ids' => $inserted_ids);
	}

	private function return_paket_belanja_to_apbd($ids_to_unlock, $type) {
		// kembalikan data paket belanja apbd ke paket belanja berdasarkan idpaket_belanja
		$this->db->select_max('sequence');
		$pb_apbd = $this->db->get('paket_belanja_apbd');
		$sequence = $pb_apbd->row()->sequence;
		$sequence = $sequence ? $sequence : 1;

		$this->db->where_in('idpaket_belanja', $ids_to_unlock);
		$this->db->where('jenis', $type);
		$this->db->where('sequence', $sequence);
		$apbd = $this->db->get('paket_belanja_apbd');
		// echo "<pre>"; print_r($this->db->last_query()); die;

		foreach ($apbd->result_array() as $pb_apbd) {
			$idpaket_belanja_apbd = $pb_apbd['idpaket_belanja_apbd'];
			$idpaket_belanja = $pb_apbd['idpaket_belanja'];
			$pb = $pb_apbd;

			unset($pb['idpaket_belanja'], $pb['jenis'], $pb['sequence'], $pb['idpaket_belanja_apbd'], $pb['created'], $pb['createdby'], $pb['updated'], $pb['updatedby']);

			if ($type == 'APBD') {
				// turun dari APBD PERUBAHAN ke APBD
				$pb['is_locked'] = 0;
				$pb['apbd_lockedby'] = null;
				$pb['apbd_locked_date'] = null;
				$pb['apbd_changes_lockedby'] = null;
				$pb['apbd_changes_locked_date'] = null;
			}
			else if ($type == 'APBD PERUBAHAN') {
				// turun dari PAPBD ke APBD PERUBAHAN
				$pb['is_locked'] = 1;
				$pb['apbd_changes_lockedby'] = null;
				$pb['apbd_changes_locked_date'] = null;
			}
			// echo "<pre>"; print_r($pb); die;
			$this->db->where('idpaket_belanja', $idpaket_belanja);
			$return_pb = $this->db->update('paket_belanja', $pb);
			if (!$return_pb) {
				return array(
					'success' => false,
					'message' => 'Gagal update[pb].'
				);
			}


			// kembalikan data paket belanja apbd detail ke paket belanja detail berdasarkan idpaket_belanja_detail
			$this->db->where('idpaket_belanja_apbd', $idpaket_belanja_apbd);
			$apbd_detail = $this->db->get('paket_belanja_apbd_detail');
			// echo "<pre>"; print_r($this->db->last_query()); die;
			
			foreach ($apbd_detail->result_array() as $detail) {
				$idpaket_belanja_apbd_detail = $detail['idpaket_belanja_apbd_detail'];
				$idpaket_belanja_detail = $detail['idpaket_belanja_detail'];

				$pb_detail = $detail;
				unset($pb_detail['idpaket_belanja_apbd_detail'], $pb_detail['idpaket_belanja_detail'], $pb_detail['idpaket_belanja_apbd'], $pb_detail['created'], $pb_detail['createdby'], $pb_detail['updated'], $pb_detail['updatedby']);
				$pb_detail['idpaket_belanja'] = $idpaket_belanja;
				// echo "<pre>"; print_r($pb_detail); die;
				$this->db->where('idpaket_belanja_detail', $idpaket_belanja_detail);
				$return_pbd = $this->db->update('paket_belanja_detail', $pb_detail);
				
				if (!$return_pbd) {
					return array(
						'success' => false,
						'message' => 'Gagal update[pbd].'
					);
				}


				// kembalikan data paket belanja apbd detail sub ke paket belanja detail sub berdasarkan idpaket_belanja_detail_sub
				$this->db->where('idpaket_belanja_apbd_detail', $idpaket_belanja_apbd_detail);
				$this->db->where('idpaket_belanja_apbd_detail IS NOT NULL');
				$apbd_detail_sub = $this->db->get('paket_belanja_apbd_detail_sub');
				// echo "<pre>"; print_r($this->db->last_query()); die;

				foreach ($apbd_detail_sub->result_array() as $detail_sub) {
					$idpaket_belanja_apbd_detail_sub = $detail_sub['idpaket_belanja_apbd_detail_sub'];
					$idpaket_belanja_detail_sub = $detail_sub['idpaket_belanja_detail_sub'];

					$pb_detail_sub = $detail_sub;

					unset($pb_detail_sub['idpaket_belanja_apbd_detail_sub'], $pb_detail_sub['idpaket_belanja_detail_sub'], $pb_detail_sub['idpaket_belanja_apbd'], $pb_detail_sub['idpaket_belanja_apbd_detail'], $pb_detail_sub['is_idpaket_belanja_apbd_detail_sub'], $pb_detail_sub['created'], $pb_detail_sub['createdby'], $pb_detail_sub['updated'], $pb_detail_sub['updatedby']);

					$pb_detail_sub['idpaket_belanja'] = $idpaket_belanja;
					
					$pb_detail_sub['idpaket_belanja_detail'] = $idpaket_belanja_detail;
					$pb_detail_sub['is_idpaket_belanja_detail_sub'] = null;

					// echo "<pre>"; print_r($pb_detail_sub); die;
					$this->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub);
					$return_pbds = $this->db->update('paket_belanja_detail_sub', $pb_detail_sub);
					
					if (!$return_pbds) {
						return array(
							'success' => false,
							'message' => 'Gagal update[pbds].'
						);
					}

					// cek apakah ada child data dari APBD yang punya parent is_idpaket_belanja_apbd_detail_sub
					$this->db->where('is_idpaket_belanja_apbd_detail_sub', $idpaket_belanja_apbd_detail_sub);
					$this->db->where('status', 1);
					$apbd_detail_sub_child_data = $this->db->get('paket_belanja_apbd_detail_sub');
					// echo "<pre>"; print_r($this->db->last_query());die;

					if ($apbd_detail_sub_child_data->num_rows() > 0) {
						foreach ($apbd_detail_sub_child_data->result_array() as $detail_sub_child) {
							$idpaket_belanja_apbd_detail_sub_child = $detail_sub_child['idpaket_belanja_apbd_detail_sub'];
							$idpaket_belanja_detail_sub_child = $detail_sub_child['idpaket_belanja_detail_sub'];

							$pb_detail_sub_child = $detail_sub_child;

							unset($pb_detail_sub_child['idpaket_belanja_apbd_detail_sub'], $pb_detail_sub_child['idpaket_belanja_detail_sub'], $pb_detail_sub_child['idpaket_belanja_apbd'], $pb_detail_sub_child['idpaket_belanja_apbd_detail'], $pb_detail_sub_child['is_idpaket_belanja_apbd_detail_sub'], $pb_detail_sub_child['created'], $pb_detail_sub_child['createdby'], $pb_detail_sub_child['updated'], $pb_detail_sub_child['updatedby'], $pb_detail_sub_child['status']);

							$pb_detail_sub_child['idpaket_belanja'] = $idpaket_belanja;
							$pb_detail_sub_child['idpaket_belanja_detail'] = null;
							
							/*data yang ditampilkan masih ada yang salah*/ $pb_detail_sub_child['is_idpaket_belanja_detail_sub'] = $idpaket_belanja_detail_sub;

							// var_dump($idpaket_belanja_detail_sub_child);
							// echo "<pre>"; print_r($pb_detail_sub_child); die;
							$this->db->where('idpaket_belanja_detail_sub', $idpaket_belanja_detail_sub_child);
							$return_pbdsc = $this->db->update('paket_belanja_detail_sub', $pb_detail_sub_child);
							// echo "<pre>"; print_r($this->db->last_query()); echo "<br><br>";

							if (!$return_pbdsc) {
								return array(
									'success' => false,
									'message' => 'Gagal update[pbdsc].'
								);
							}
						}
					}
				}
			}
		}

		// jangan hapus data APBD jika sebelumnya ada error
		if ($this->db->trans_status() === FALSE) {
			return array(
				'success' => false,
				'message' => 'Terjadi kesalahan saat proses restore data APBD.'
			);
		}

		// jika tidak ada eror sama sekali, maka hapus data paket belanja apbd nya
		$delete_result = $this->delete_apbd_data($ids_to_unlock, $type, $sequence);

		if ($this->db->trans_status() === FALSE) {
			return array(
				'success' => false,
				'message' => 'Gagal menghapus data APBD.'
			);
		}

		return array(
			'success' => true
		);
	}

	private function lock_paket_belanja(array $ids, $anggaran_APBD)
	{
		$update_lock = array(
			'is_locked' => $anggaran_APBD,
		);

		if ($anggaran_APBD == '1') {
			$update_lock['apbd_lockedby'] = $this->session->userdata('username');
			$update_lock['apbd_locked_date'] = date('Y-m-d H:i:s');
		} 
		else if ($anggaran_APBD == '2') {
			$update_lock['apbd_changes_lockedby'] = $this->session->userdata('username');
			$update_lock['apbd_changes_locked_date'] = date('Y-m-d H:i:s');

		}

		$this->db->where_in('idpaket_belanja', $ids);
		$this->db->update('paket_belanja', $update_lock);
	}

	private function unlock_paket_belanja(array $ids, $anggaran_APBD)
	{
		$update_unlock = array(
			'is_locked' => $anggaran_APBD,
		);

		if ($anggaran_APBD == '1') {
			$update_lock['apbd_lockedby'] = null;
			$update_lock['apbd_locked_date'] = null;
		} 
		else if ($anggaran_APBD == '2') {
			$update_lock['apbd_changes_lockedby'] = null;
			$update_lock['apbd_changes_locked_date'] = null;

		}

		$this->db->where_in('idpaket_belanja', $ids);
		$this->db->update('paket_belanja', $update_unlock);
	}

	private function delete_apbd_data(array $ids, $type, $sequence)
	{
		$this->db->where_in('idpaket_belanja', $ids);
		$this->db->where('jenis', $type);
		$this->db->where('sequence', $sequence);
		$apbd_rows = $this->db->get('paket_belanja_apbd')->result_array();
		// echo "<pre>"; print_r($this->db->last_query());die;

		if (!empty($apbd_rows)) {
			$apbd_ids = array_column($apbd_rows, 'idpaket_belanja_apbd');

			$this->db->where_in('idpaket_belanja_apbd', $apbd_ids);
			if ($this->db->delete('paket_belanja_apbd_detail_sub') === false) {
				return array('success' => false, 'message' => azlang('Gagal menghapus data[pbpds].'));
			}

			$this->db->where_in('idpaket_belanja_apbd', $apbd_ids);
			if ($this->db->delete('paket_belanja_apbd_detail') === false) {
				return array('success' => false, 'message' => azlang('Gagal menghapus data[pbpd].'));
			}

			$this->db->where_in('idpaket_belanja', $ids);
			$this->db->where('jenis', $type);
			$this->db->where('sequence', $sequence);
			if ($this->db->delete('paket_belanja_apbd') === false) {
				return array('success' => false, 'message' => azlang('Gagal menghapus data[pbp].'));
			}
		}

		return array('success' => true);
	}
}
