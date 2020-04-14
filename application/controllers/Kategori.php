<?php

class Kategori extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		// $status_login = $this->session->userdata('status_login');
		// if (!$status_login) {
		// 	redirect('auth');
		// }
	}

	function index(){
		$data['page'] = 'kategori/kategori_add';
		view('template', $data);
	}

	public function json() {
        header('Content-Type: application/json');
        echo $this->akun_model->json();
    }
}