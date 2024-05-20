<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		// Remove or use this
		// $this->load->model('mexample', 'example');
	}

	public function index()
	{
		$data['title'] = 'Testing Templating';

		$this->template->render('example/index', $data);
	}
}
