<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('mhome', 'home');

		$this->data['title'] = 'Home';
		$this->data['dbHost'] = $_ENV['DB_HOST'];
	}

	// how to call me? :http://localhost/projectname/home
	public function index()
	{
		$this->load->view('home/index', $this->data);
	}

	// How to call me? :http://localhost/projectname/home/test/myparam
	public function test($param)
	{
		echo $param;
	}
}
