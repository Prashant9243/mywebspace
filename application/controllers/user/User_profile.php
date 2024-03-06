<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_profile extends CI_Controller {

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
		$user=$this->session->userdata('userid');
		$data['user_details']=$this->admin->getRow('select * from users where id="'.$user.'"');
		$data['categories']=$this->admin->getRows('select * from main_categories where user_id="'.$user.'" and status=1');
		$data['product_count']=$this->admin->getVal('select count(*) from products where user_id="'.$user.'" and status=1');
		$data['invoice_count']=$this->admin->getVal('select count(*) from invoice where user_id="'.$user.'"');
		$data['purchase_count']=$this->admin->getVal('select count(*) from product_purchase where user_id="'.$user.'" ');
		$data['template']='user/dashboard/User_profile';
		$this->load->view('user/layout/template',$data);
		
	}

	public function update_profile()
	{
		$user=$this->session->userdata('userid');

		 $array = array(
                    
                    'address'     =>$this->input->post('address'),
                    'email'       =>$this->input->post('email'),
                    'mobile'      =>$this->input->post('mobile'),
                    'username'    =>$this->input->post('username'),
                    'password'    =>$this->input->post('password')
                    
                    );
       $update=$this->admin->update('users',array('id'=>$user), $array);

       $this->messages->add('Profile Updated', "alert-success");

       redirect('user/user_profile');
	}
	
	
}
