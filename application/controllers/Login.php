<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		
		$this->load->view('login');
		
	}
	public function check_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
                {
                	
                       $data['template']='login';
                       $this->load->view('admin/layout/template',$data);
                }
                else
                {
                     // ********************** login code here ********************************
                	
                	$username =	$this->input->post('username');
                	$password =	$this->input->post('password');

                	$user_details = $this->admin->getRow('select * from users where username="'.$username.'" and password="'.$password.'" '); 
                	//echo ;die;
                	if(!empty($user_details))
                	{
                		$todays_date = date("Y-m-d");
                		$current_date=(strtotime($todays_date));
                		//echo ($current_date);
                		//echo( "<br>");

                		$valid_upto = $user_details->valid_upto;
                		$expiry_date =(strtotime($valid_upto));
                		//echo $expiry_date; die;

                		if($current_date > $expiry_date)
                		{
                			//echo "current big";die;
			                $array = array(
			               	'active_status'    => 2,
			                );
			            	
			            	$update=$this->admin->update('users',array('username'=>$username,'password'=>$password), $array);	
                		}
                		/*else{
                			echo "expiry big";die;
                		}*/

                		$user_details = $this->admin->getRow('select * from users where username="'.$username.'" and password="'.$password.'" '); 
                        

                		// **************** saving details in session *****************
                		 $session = array(
                                 'userid'=>$user_details->id,
                                 'username'=>$user_details->username,
                                 'user_type'=>$user_details->user_type,
                                 'company_name'=>$user_details->company_name,
                                 'company_logo'=>$user_details->company_logo,
                                 'logged_in' => TRUE
                                );

                		   		$this->session->set_userdata($session);

                		// **************** saving details in session *****************
                              
                		
                		if((($user_details->user_type) == 1) && (($user_details->active_status) == 1))
                		{

                		redirect('admin/admin_dashboard');

                		}
                		else if((($user_details->user_type) == 2) && (($user_details->active_status) == 1))
                		{
                			redirect('user/user_dashboard');
                		}
                		else
                		{
                			$this->messages->add('Your Account is currently inactive', "alert-danger");
                			redirect('Login');
                		}
                	}
                	else
                	{
                		$this->messages->add('Invalid Username or Password', "alert-danger");
                		redirect('Login');
                		
                	}
                }

	}
	
}
