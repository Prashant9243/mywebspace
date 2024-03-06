<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_retailers extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		
        if($this->session->userdata('user_type') != 2)
        {
          redirect('login');exit();
        }
    }


	public function index($edit_id = NULL)
	{
    $user=$this->session->userdata('userid');
    if($edit_id)
    {
      $data['edit']=$this->admin->getRow('select * from wholesaler_details where id="'.$edit_id.'" ');
    }
		
    $data['template']='user/retailer/index';
		$this->load->view('user/layout/template',$data);
		
	}

 
  public function insertretailer($edit_id = NULL)
  {
    $wholesaler=$this->input->post('wholesaler_id');
    $name=$this->input->post('wholesaler_name');
    $pid=$this->input->post('product_id');
    if(!empty($wholesaler))
    {
      $w_details = array(
      'user_id'                   =>  $this->session->userdata('userid'),
      'name'                      =>  $this->input->post('wholesaler_name'),
      'email'                     =>  $this->input->post('W_email'),
      'contact_no'                =>  $this->input->post('contact_number'),
      'address'                   =>  $this->input->post('address'),
      
      );

      $where = array(
        'id'  => $wholesaler
      );
      
      $update = $this->db->update('wholesaler_details', $w_details, $where);
    }
    else
    {
      
      $w_details = array(
      'user_id'                   =>  $this->session->userdata('userid'),
      'name'                      =>  $this->input->post('wholesaler_name'),
      'email'                     =>  $this->input->post('W_email'),
      'contact_no'                =>  $this->input->post('contact_number'),
      'address'                   =>  $this->input->post('address'),
      
      );
      
      $insert = $this->admin->insert('wholesaler_details', $w_details);
    }
      redirect('user/Manage_retailers/retailer_list');
  }

  public function wholesaler_details()
  {
    $user=$this->session->userdata('userid');
    $w_id=$this->input->post('w_id');
    $wholesaler_details=$this->admin->getRows('select * from wholesaler_details where id="'.$w_id.'" and user_id="'.$user.'"');
    foreach ($wholesaler_details as $details) {
      $data = array(
      'name'=>$details->name,
      'email'=>$details->email,
      'contact'=>$details->contact_no,
      'address'=>$details->address
    );
     $myJSON = json_encode($data);
     echo($myJSON);

    }

  }

  public function retailer_list()
  {
    $data['retailer']=$this->admin->getRows('select * from wholesaler_details where user_id="'.$this->session->userdata('userid').'" ');
    $data['template']='user/retailer/list';
    $this->load->view('user/layout/template',$data);
  }

  public function delete()
  {
  
    $pid=$this->input->post('id');
    $delete=$this->admin->deleteAll('wholesaler_details',array('id'=>$pid));
    echo $this->db->last_query();

  }

  

   
}