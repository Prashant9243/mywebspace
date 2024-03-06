<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_categories extends CI_Controller {

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
		$user_id = $this->session->userdata('userid');
		$data['categories']=$this->admin->getRows('select * from main_categories where user_id="'.$user_id.'" ');
		$data['template']='user/form';
		$this->load->view('user/layout/template',$data);
		
	}
	
	public function maincategoryadd()
	{
		$category_name=$this->input->post('categoryname');
		$category_code=$category_name[0];

		 $data = array(
           
           // 'client_id' =>$this->session->userdata('admin_id'),
                'user_id'             =>$this->session->userdata('userid'),
                'category_name'        =>$this->input->post('categoryname'),
                'category_code'		   =>$category_code,
                'status'  			   =>$this->input->post('status')
                
                    );
                   // print_r($data);die;
       
             $insert = $this->admin->insert('main_categories', $data);
             if($insert)
             {
             	$categories=$this->admin->getRows('select * from main_categories ');
		 		echo json_encode($categories);
		 	}
	}

	public function categorylist()
	{
		$categories=$this->admin->getRows('select * from main_categories ');
		 echo json_encode($categories);
	}
	

	public function subcategories()
	{
		$user_id = $this->session->userdata('userid');
		$data['maincategories']=$this->admin->getRows('select * from main_categories where status=1 and user_id="'.$user_id.'"');
		$data['subcategories']=$this->admin->getRows('select * from sub_categories where user_id="'.$user_id.'"');
		$data['template']='user/sub_categories';
		$this->load->view('user/layout/template',$data);
		
	}

	public function subcategoryadd()
	{
		$user_id = $this->session->userdata('userid');

		 $data = array(
           
           // 'client_id' =>$this->session->userdata('admin_id'),
                'user_id'             =>$this->session->userdata('userid'),
               	'main_category_id'	   =>$this->input->post('categoryname'),		
                'sub_category_name'    =>$this->input->post('subcategory'),
                'status'  			   =>$this->input->post('status')
                
                    );
                   // print_r($data);die;
       
             $insert = $this->admin->insert('sub_categories', $data);
             if($insert)
             {
             	$subcategories=$this->admin->getRows('select * from sub_categories where user_id="'.$user_id.'"');
             	$i=1;
             	foreach ($subcategories as $subcategory ) {
             		# code...
             		$maincategoryname=$this->admin->getVal('select category_name from main_categories where id="'.$subcategory->main_category_id.'"');
             		$sta=$subcategory->status;
                	if($sta==1)
                	{
                		$statusoutput='<span class="badge badge-success">Active</span>';
                	}
                	else if($sta==2)
                	{
                		$statusoutput='<span class="badge badge-danger">Inactive</span>';
                	}
             		 echo "<tr>";
  					 echo "<td>" . $i++ . "</td>";
  					 echo "<td>" . $maincategoryname . "</td>";
  					 echo "<td>" . $subcategory->sub_category_name . "</td>";
  					 echo "<td>" . $statusoutput . "</td>";
  					 echo "</tr>";

             	}
		 		
		 	}
	}

  
}