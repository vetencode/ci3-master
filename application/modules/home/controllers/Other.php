<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Other extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->data['title'] = 'Other';
    }

    // how to call me? :http://localhost/projectname/home/other
    public function index()
    {
        echo 'iam in other controller, index method';
    }

    // How to call me? :http://localhost/projectname/home/other/othertest/myparam
    public function othertest($param)
    {
        echo $param;
    }
}
