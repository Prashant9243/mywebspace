<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_dashboard extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		
        if($this->session->userdata('user_type') != 2)
        {
          redirect('login');exit();
        }
    }


	public function index()
	{
		$data['template']='user/dashboard/index';
		$this->load->view('user/layout/template',$data);
		
	}
	
	
}
