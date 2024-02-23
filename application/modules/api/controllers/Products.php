<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Products extends RestController
{
	public $user_id;

	function __construct()
	{
		parent::__construct();

		protectByKey();

		$this->load->library('jwtauth');

		// Validasi Token
		$this->jwtauth->validate_token();

		// user_id didapat dari hasil validasi token (Di validate itu di set data ke session)
		$this->user_id = $this->session->userdata('user_id');

		$this->load->model('mproducts', 'product');
	}

	public function index_get()
	{
		$uid = $this->user_id;

		responseJSON([
			'success' => true,
			'data' => [
				'uid' => $uid,
			],
		]);
	}

	public function index_post()
	{
		$uid = $this->user_id;
	}

	// A Bonus, add this lines to your method for simply upload photo to assets folder
	//
	// $filename = time() . '.jpg';
	// $filepath = FCPATH . 'assets/images/folder/' . $filename;
	// $result = move_uploaded_file($_FILES['photo']['tmp_name'], $filepath); // 'photo' adalah key yang dikirim dari request (input[name='photo'])
	//
	// Punya cara lain? Silahkan sesuaikan
}
