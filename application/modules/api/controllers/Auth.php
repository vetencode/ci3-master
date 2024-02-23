<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Auth extends RestController
{
	function __construct()
	{
		parent::__construct();

		protectByKey();

		$this->load->model('mapi', 'api');
		$this->load->library('jwtauth');
	}

	// Example To Create Login Request

	public function login_post()
	{
		$phone = getRequest('phone');
		$password = getRequest('password');
		if ($password) {
			$password = md5($password);
		}

		if (!$phone || !$password) {
			responseJSON([
				'success' => false,
				'message' => 'Nomor ponsel dan password diperlukan',
				'data' => null,
			], 400);
		}
		$user_data 	= ['active' => 1, 'phone' => $phone, 'password' => $password];
		$users = $this->api->get_user($user_data);

		if (!$users->num_rows()) {
			responseJSON([
				'success' => false,
				'message' => 'Nomor ponsel atau kata sandi salah',
				'data' => null,
			], 401);
		} else {
			$user = $users->row();

			$payload['user_id'] = $user->id;
			$jwt = $this->jwtauth->generateToken($payload);

			responseJSON([
				'success' => true,
				'message' => 'Login berhasil',
				'data' => (object)[
					'token' => $jwt,
					// Add some additional data for login request here..
				],
			]);
		}
	}
}
