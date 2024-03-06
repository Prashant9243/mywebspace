<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_alerts extends CI_Controller {

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
   
	
   
    $data['template']='user/reports/expiry_alert';
		$this->load->view('user/layout/template',$data);
		
	}
  
  public function out_of_stock()
  {
   
     $user=$this->session->userdata('userid');
    $data['products']=$this->admin->getRows('select * from products where user_id="'.$user.'" and status=1 and quantity=0');
    $data['template']='user/reports/out_of_stock';
    $this->load->view('user/layout/template',$data);
    
  } 

    public function wishlist()
  {
   
     $user=$this->session->userdata('userid');
    $data['products']=$this->admin->getRows('select * from wishlist where user_id="'.$user.'" ');
    $data['template']='user/reports/wishlist';
    $this->load->view('user/layout/template',$data);
    
  }  
 
}