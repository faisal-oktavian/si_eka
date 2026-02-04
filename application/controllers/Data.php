<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
	public function __construct() {
        parent::__construct();

    }

	public function get_urusan_pemerintah(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, nama_urusan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("nama_urusan", $q);
			$this->db->or_like("no_rekening_urusan", $q);
			$this->db->group_end();
		}
		$this->db->where('is_active','1');
		$this->db->select("idurusan_pemerintah as id, concat(no_rekening_urusan, ' - ', nama_urusan, ' (Tahun Anggaran ', tahun_anggaran_urusan, ')') as text");
		$this->db->where('status', '1');

		$data = $this->db->get("urusan_pemerintah", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("nama_urusan", $q);
			$this->db->or_like("no_rekening_urusan", $q);
			$this->db->group_end();
		}
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("urusan_pemerintah");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_bidang_urusan(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, bidang_urusan.nama_bidang_urusan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("bidang_urusan.nama_bidang_urusan", $q);
			$this->db->or_like("bidang_urusan.no_rekening_bidang_urusan", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('bidang_urusan.is_active','1');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("bidang_urusan.idbidang_urusan as id, concat(bidang_urusan.no_rekening_bidang_urusan, ' - ', bidang_urusan.nama_bidang_urusan, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");
		$this->db->where('bidang_urusan.status', '1');

		$data = $this->db->get("bidang_urusan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("bidang_urusan.nama_bidang_urusan", $q);
			$this->db->or_like("bidang_urusan.no_rekening_bidang_urusan", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('bidang_urusan.is_active','1');
		$this->db->where('bidang_urusan.status', '1');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("bidang_urusan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_bidang_urusan_parent(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, bidang_urusan.nama_bidang_urusan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("bidang_urusan.nama_bidang_urusan", $q);
			$this->db->or_like("bidang_urusan.no_rekening_bidang_urusan", $q);
			$this->db->group_end();
		}
		$this->db->where('bidang_urusan.idurusan_pemerintah', $parent);
		$this->db->where('bidang_urusan.is_active','1');
		$this->db->where('bidang_urusan.status', '1');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("bidang_urusan.idbidang_urusan as id, concat(bidang_urusan.no_rekening_bidang_urusan, ' - ', bidang_urusan.nama_bidang_urusan, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");

		$data = $this->db->get("bidang_urusan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("bidang_urusan.nama_bidang_urusan", $q);
			$this->db->or_like("bidang_urusan.no_rekening_bidang_urusan", $q);
			$this->db->group_end();
		}
		$this->db->where('bidang_urusan.idurusan_pemerintah', $parent);
		$this->db->where('bidang_urusan.is_active','1');
		$this->db->where('bidang_urusan.status', '1');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("bidang_urusan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_program(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, program.nama_program");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("program.nama_program", $q);
			$this->db->or_like("program.no_rekening_program", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('program.is_active','1');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("program.idprogram as id, concat(program.no_rekening_program, ' - ', program.nama_program, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");
		$this->db->where('program.status', '1');

		$data = $this->db->get("program", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("program.nama_program", $q);
			$this->db->or_like("program.no_rekening_program", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('program.is_active','1');
		$this->db->where('program.status', '1');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("program");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_program_parent(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, program.nama_program");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("program.nama_program", $q);
			$this->db->or_like("program.no_rekening_program", $q);
			$this->db->group_end();
		}
		$this->db->where('program.idbidang_urusan', $parent);
		$this->db->where('program.is_active','1');
		$this->db->where('program.status', '1');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("program.idprogram as id, concat(program.no_rekening_program, ' - ', program.nama_program, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");

		$data = $this->db->get("program", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("program.nama_program", $q);
			$this->db->or_like("program.no_rekening_program", $q);
			$this->db->group_end();
		}
		$this->db->where('program.idbidang_urusan', $parent);
		$this->db->where('program.is_active','1');
		$this->db->where('program.status', '1');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("program");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_kegiatan(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, kegiatan.nama_kegiatan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("kegiatan.nama_kegiatan", $q);
			$this->db->or_like("kegiatan.no_rekening_kegiatan", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('kegiatan.is_active','1');
		$this->db->where('kegiatan.status', '1');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("kegiatan.idkegiatan as id, concat(kegiatan.no_rekening_kegiatan, ' - ', kegiatan.nama_kegiatan, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");

		$data = $this->db->get("kegiatan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("kegiatan.nama_kegiatan", $q);
			$this->db->or_like("kegiatan.no_rekening_kegiatan", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('kegiatan.is_active','1');
		$this->db->where('kegiatan.status', '1');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("kegiatan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_kegiatan_parent(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, kegiatan.nama_kegiatan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("kegiatan.nama_kegiatan", $q);
			$this->db->or_like("kegiatan.no_rekening_kegiatan", $q);
			$this->db->group_end();
		}
		$this->db->where('kegiatan.idprogram', $parent);
		$this->db->where('kegiatan.is_active','1');
		$this->db->where('kegiatan.status', '1');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("kegiatan.idkegiatan as id, concat(kegiatan.no_rekening_kegiatan, ' - ', kegiatan.nama_kegiatan, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");

		$data = $this->db->get("kegiatan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("kegiatan.nama_kegiatan", $q);
			$this->db->or_like("kegiatan.no_rekening_kegiatan", $q);
			$this->db->group_end();
		}
		$this->db->where('kegiatan.idprogram', $parent);
		$this->db->where('kegiatan.is_active','1');
		$this->db->where('kegiatan.status', '1');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("kegiatan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_sub_kegiatan(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, sub_kegiatan.nama_subkegiatan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("sub_kegiatan.nama_subkegiatan", $q);
			$this->db->or_like("sub_kegiatan.no_rekening_subkegiatan", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('sub_kegiatan.is_active','1');
		$this->db->where('sub_kegiatan.status', '1');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("sub_kegiatan.idsub_kegiatan id, concat(sub_kegiatan.no_rekening_subkegiatan, ' - ', sub_kegiatan.nama_subkegiatan, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");

		$data = $this->db->get("sub_kegiatan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("sub_kegiatan.nama_subkegiatan", $q);
			$this->db->or_like("sub_kegiatan.no_rekening_subkegiatan", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('sub_kegiatan.is_active','1');
		$this->db->where('sub_kegiatan.status', '1');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("sub_kegiatan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_sub_kegiatan_parent(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("urusan_pemerintah.tahun_anggaran_urusan DESC, sub_kegiatan.nama_subkegiatan");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("sub_kegiatan.nama_subkegiatan", $q);
			$this->db->or_like("sub_kegiatan.no_rekening_subkegiatan", $q);
			$this->db->group_end();
		}
		$this->db->where('sub_kegiatan.idkegiatan', $parent);
		$this->db->where('sub_kegiatan.is_active','1');
		$this->db->where('sub_kegiatan.status', '1');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$this->db->select("sub_kegiatan.idsub_kegiatan id, concat(sub_kegiatan.no_rekening_subkegiatan, ' - ', sub_kegiatan.nama_subkegiatan, ' (Tahun Anggaran ', urusan_pemerintah.tahun_anggaran_urusan, ')') as text");

		$data = $this->db->get("sub_kegiatan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("sub_kegiatan.nama_subkegiatan", $q);
			$this->db->or_like("sub_kegiatan.no_rekening_subkegiatan", $q);
			$this->db->group_end();
		}
		$this->db->where('sub_kegiatan.idkegiatan', $parent);
		$this->db->where('sub_kegiatan.is_active','1');
		$this->db->where('sub_kegiatan.status', '1');
		$this->db->join('kegiatan', 'kegiatan.idkegiatan = sub_kegiatan.idkegiatan');
		$this->db->join('program', 'program.idprogram = kegiatan.idprogram');
		$this->db->join('bidang_urusan', 'bidang_urusan.idbidang_urusan = program.idbidang_urusan');
		$this->db->join('urusan_pemerintah', 'urusan_pemerintah.idurusan_pemerintah = bidang_urusan.idurusan_pemerintah');
		$cdata = $this->db->get("sub_kegiatan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_akun_belanja(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("nama_akun_belanja");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("nama_akun_belanja", $q);
			$this->db->or_like("no_rekening_akunbelanja", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('is_active','1');
		$this->db->select("idakun_belanja id, concat(no_rekening_akunbelanja, ' - ', nama_akun_belanja) as text");
		$this->db->where('status', '1');

		$data = $this->db->get("akun_belanja", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("nama_akun_belanja", $q);
			$this->db->or_like("no_rekening_akunbelanja", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("akun_belanja");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_kategori(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("nama_kategori");
		if (strlen($q) > 0) {
			$this->db->like("nama_kategori", $q);
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('is_active','1');
		$this->db->select("idkategori id, nama_kategori as text");
		$this->db->where('status', '1');

		$data = $this->db->get("kategori", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->like("nama_kategori", $q);
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("kategori");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_subkategori(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("nama_sub_kategori");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("nama_sub_kategori", $q);
			$this->db->or_like("kode_rekening", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('sub_kategori.is_active','1');
		$this->db->select("sub_kategori.idsub_kategori id, 
		CONCAT(
        	sub_kategori.nama_sub_kategori, ' (',
        	IFNULL(SUBSTRING_INDEX(kode_rekening.kode_rekening, '-', 1), ''), ')'
    	) AS text");
		$this->db->where('sub_kategori.status', '1');
		$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');

		$data = $this->db->get("sub_kategori", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("nama_sub_kategori", $q);
			$this->db->or_like("kode_rekening", $q);
			$this->db->group_end();
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('sub_kategori.is_active','1');
		$this->db->where('sub_kategori.status', '1');
		$this->db->join('kode_rekening', 'kode_rekening.idkode_rekening = sub_kategori.idkode_rekening', 'left');
		$cdata = $this->db->get("sub_kategori");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_satuan(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		// $parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("nama_satuan");
		if (strlen($q) > 0) {
			$this->db->like("nama_satuan", $q);
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('is_active','1');
		$this->db->select("idsatuan id, nama_satuan as text");
		$this->db->where('status', '1');

		$data = $this->db->get("satuan", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->like("nama_satuan", $q);
		}
		// $this->db->where('idurusan_pemerintah', $parent);
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("satuan");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_room(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;
		
		$this->db->order_by("nama_ruang");
		if (strlen($q) > 0) {
			$this->db->like("nama_ruang", $q);
		}
		$this->db->where('is_active','1');
		$this->db->select("idruang as id, nama_ruang as text");
		$this->db->where('status', '1');

		$data = $this->db->get("ruang", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->like("nama_ruang", $q);
		}
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("ruang");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_sumber_dana(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("nama_sumber_dana");
		if (strlen($q) > 0) {
			$this->db->like("nama_sumber_dana", $q);
		}
		$this->db->where('is_active','1');
		$this->db->select("idsumber_dana as id, nama_sumber_dana as text");
		$this->db->where('status', '1');

		$data = $this->db->get("sumber_dana", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->like("nama_sumber_dana", $q);
		}
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("sumber_dana");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_kode_rekening(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("kode_rekening");
		if (strlen($q) > 0) {
			$this->db->like("kode_rekening", $q);
		}
		$this->db->where('is_active','1');
		$this->db->select("idkode_rekening as id, kode_rekening as text");
		$this->db->where('status', '1');

		$data = $this->db->get("kode_rekening", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->like("kode_rekening", $q);
		}
		$this->db->where('is_active','1');
		$this->db->where('status', '1');
		$cdata = $this->db->get("kode_rekening");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_contract_code(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("contract_code");
		if (strlen($q) > 0) {
			$this->db->like("contract_code", $q);
		}
		$this->db->select("idcontract as id, contract_code as text");
		$this->db->where('status', '1');
		$this->db->where("YEAR(contract.created) = '".Date('Y')."' ");
		$this->db->where('contract_status IN ("KONTRAK PENGADAAN", "DITOLAK VERIFIKATOR") ');

		$data = $this->db->get("contract", $limit, $offset);
		
		if (strlen($q) > 0) {
			$this->db->like("contract_code", $q);
		}
		$this->db->where('status', '1');
		$this->db->where("YEAR(contract.created) = '".Date('Y')."' ");
		$this->db->where('contract_status IN ("KONTRAK PENGADAAN", "DITOLAK VERIFIKATOR") ');
		$cdata = $this->db->get("contract");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}

	public function get_paket_belanja_detail_sub_parent(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;
		
		// var_dump($parent);die();
		$this->db->order_by("paket_belanja.nama_paket_belanja, sub_kategori.nama_sub_kategori");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("paket_belanja.nama_paket_belanja", $q);
			$this->db->or_like("sub_kategori.nama_sub_kategori", $q);
			$this->db->group_end();
		}
		$this->db->where('contract_detail.idcontract', $parent);
		$this->db->where('contract_detail.status', '1');
		$this->db->where('purchase_plan.status', '1');
		$this->db->where('purchase_plan_detail.status', '1');
		$this->db->where('paket_belanja.status', '1');
		$this->db->where('paket_belanja_detail_sub.status', '1');
		// $this->db->where('sub_kategori.status', '1');
		$this->db->where('contract.contract_status IN ("KONTRAK PENGADAAN", "DITOLAK VERIFIKATOR") ');
		$this->db->where("YEAR(contract.created) = '".Date('Y')."' ");
		$this->db->join('contract', 'contract.idcontract = contract_detail.idcontract');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		
		$this->db->select('
			paket_belanja_detail_sub.idpaket_belanja_detail_sub as id, 
			concat(purchase_plan.purchase_plan_code, " → ", paket_belanja.nama_paket_belanja, " → ", sub_kategori.nama_sub_kategori) as text, 
			contract_detail.idcontract as data_idcontract, 
			contract_detail.idcontract_detail as data_idcontract_detail, 
			purchase_plan.idpurchase_plan as data_idpurchase_plan, 
			purchase_plan_detail.idpurchase_plan_detail as data_idpurchase_plan_detail, 
			paket_belanja.idpaket_belanja as data_idpaket_belanja, 
			paket_belanja_detail_sub.idpaket_belanja_detail_sub as data_idpaket_belanja_detail_sub, 
			sub_kategori.idsub_kategori as data_idsub_kategori');
		$data = $this->db->get("contract_detail", $limit, $offset);
		// echo "<pre>"; print_r($this->db->last_query());die;
		
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("paket_belanja.nama_paket_belanja", $q);
			$this->db->or_like("sub_kategori.nama_sub_kategori", $q);
			$this->db->group_end();
		}
		$this->db->where('contract_detail.idcontract', $parent);
		$this->db->where('contract_detail.status', '1');
		$this->db->where('purchase_plan.status', '1');
		$this->db->where('purchase_plan_detail.status', '1');
		$this->db->where('paket_belanja.status', '1');
		$this->db->where('paket_belanja_detail_sub.status', '1');
		$this->db->where('sub_kategori.status', '1');
		$this->db->join('purchase_plan', 'purchase_plan.idpurchase_plan = contract_detail.idpurchase_plan');
		$this->db->join('purchase_plan_detail', 'purchase_plan_detail.idpurchase_plan = purchase_plan.idpurchase_plan');
		$this->db->join('paket_belanja', 'paket_belanja.idpaket_belanja = purchase_plan_detail.idpaket_belanja');
		$this->db->join('paket_belanja_detail_sub', 'paket_belanja_detail_sub.idpaket_belanja_detail_sub = purchase_plan_detail.idpaket_belanja_detail_sub');
		$this->db->join('sub_kategori', 'sub_kategori.idsub_kategori = paket_belanja_detail_sub.idsub_kategori');
		$cdata = $this->db->get("contract_detail");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);

		// echo "<pre>"; print_r($results);die;
		echo json_encode($results);
	}
}	
