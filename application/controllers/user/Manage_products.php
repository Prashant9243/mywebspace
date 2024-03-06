<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_products extends CI_Controller {

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
    $data['edit']=$this->admin->getRow('select * from products where product_id="'.$edit_id.'" ');
    }
		$data['maincategories']=$this->admin->getRows('select * from main_categories where status=1 and user_id="'.$user.'"');
     
    $data['wholesaler_details']=$this->admin->getRows('select * from wholesaler_details where user_id="'.$user.'"');
    $data['template']='user/product/index';
		$this->load->view('user/layout/template',$data);
		
	}

  public function subcategory()
  {
    
    $maincategory=$this->input->post('categoryname');
    $subcategory=$this->admin->getRows('select * from sub_categories where main_category_id="'.$maincategory.'"');
    foreach ($subcategory as $sub) {
      # code...
      echo "<option value=".$sub->id.">".$sub->sub_category_name."</option>";
    }
  }
  public function productid()
  {
    $company_name=$this->session->userdata('company_name');
    $company= substr($company_name, 0, 3);
    $number=rand(pow(10, 4-1), pow(10, 4)-1);
    $maincategory=$this->input->post('categoryname');
    $category_code=$this->admin->getVal('select category_code from main_categories where id="'.$maincategory.'"');
    $productid=$company.$number.$category_code;
    echo $productid;

  }
  public function insertproduct($edit_id = NULL)
  {
    $wholesaler=$this->input->post('wholesaler_id');
    $name=$this->input->post('wholesaler_name');
    $pid=$this->input->post('product_id');
    if(($wholesaler==NULL) && ($name!=NULL))
    {
      //echo "Finally Here";die;
      $w_details = array(
      'user_id'                   =>$this->session->userdata('userid'),
      'name'                      =>$this->input->post('wholesaler_name'),
      'email'                     =>$this->input->post('W_email'),
      'contact_no'                =>$this->input->post('contact_number'),
      'address'                   =>$this->input->post('address'),
      
      );
      
      $insert = $this->admin->insert('wholesaler_details', $w_details);
      if($insert)
      {
        $data = array(
      'user_id'                   =>$this->session->userdata('userid'),
      'product_id'                =>$this->input->post('product_id'),
      'main_category'             =>$this->input->post('main_category'),
      'sub_category'              =>$this->input->post('sub_category'),
      'product_name'              =>$this->input->post('product_name').' '.$this->input->post('uquantity').' '.$this->input->post('uom'),
      'product_purchase_date'     =>$this->input->post('product_purchase_date'),
      'unit_of_measurement'       =>$this->input->post('uquantity').' '.$this->input->post('uom'),
      'quantity'                  =>$this->input->post('quantity'),
      'expiry_date'               =>$this->input->post('expiry_date'),
      'cost_price'                =>$this->input->post('cost_price'),
      'sale_price'                =>$this->input->post('sale_price'),
      'gst'                       =>$this->input->post('gst'),
      'cgst'                      =>$this->input->post('cgst'),
      'wholesaler_id'             =>$insert,
      'status'                    =>1
      );
      if(empty($edit_id))
      {
      $productinsert = $this->admin->insert('products', $data); 
      
      $this->messages->add('New Product Added successfully', "alert-success");
    }
    else
    {

    $update=$this->admin->update('products',array('product_id'=>$pid), $data);
    $this->messages->add('Product Updated successfully', "alert-success");

    }
      
    }
    }
    else
    {
       $data = array(
        'user_id'                   =>$this->session->userdata('userid'),
        'product_id'                =>$this->input->post('product_id'),
        'main_category'             =>$this->input->post('main_category'),
        'sub_category'              =>$this->input->post('sub_category'),
        'product_name'              =>$this->input->post('product_name').' '.$this->input->post('uquantity').' '.$this->input->post('uom'),
        'product_purchase_date'     =>$this->input->post('product_purchase_date'),
        'quantity'                  =>$this->input->post('quantity'),
        'unit_of_measurement'       =>$this->input->post('uquantity').' '.$this->input->post('uom'),
        'expiry_date'               =>$this->input->post('expiry_date'),
        'cost_price'                =>$this->input->post('cost_price'),
        'sale_price'                =>$this->input->post('sale_price'),
        'gst'                       =>$this->input->post('gst'),
        'cgst'                      =>$this->input->post('cgst'),
        'wholesaler_id'             =>$this->input->post('wholesaler_id'),
        'status'                    =>1
        );
        if(empty($edit_id))
        {
          $insert = $this->admin->insert('products', $data);
          $this->messages->add('New Product Added successfully', "alert-success");
        }
        else
        {

          $update=$this->admin->update('products',array('product_id'=>$pid), $data);
          $this->messages->add('Product Updated successfully', "alert-success");
        }
      }
      redirect('user/Manage_products/product_list');
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

  public function product_list()
  {
    $data['products']=$this->admin->getRows('select * from products where user_id="'.$this->session->userdata('userid').'" ');
    $data['template']='user/product/list';
    $this->load->view('user/layout/template',$data);
  }

  public function delete()
  {
  
    $pid=$this->input->post('id');
    $delete=$this->admin->deleteAll('products',array('product_id'=>$pid));
    echo $this->db->last_query();

  }

  public function move_to_wishlist()
  {
    $product_id=$this->input->post('id');

    $find=$this->admin->getVal('select id from wishlist where product_id="'.$product_id.'"');
    if(!empty($find))
    {
      echo "Product Already Exsits in wishlist";
    }
    else
    {
    $data = array(
      'user_id'                   =>$this->session->userdata('userid'),
      'product_id'                =>$product_id
      );
      
      $insert = $this->admin->insert('wishlist', $data);
      echo "Product Added in wishlist";

      }
  }

  public function sold_products()
  {
    $product_id=$this->input->post('id');

    $data = array(
      'user_id'                   =>$this->session->userdata('userid'),
      'product_id'                =>$product_id
      );
      
      $insert = $this->admin->insert('sold_products', $data);
      if($insert)
      {
        $old_quantity=$this->admin->getVal('select quantity from products where product_id="'.$product_id.'" ');
        $data = array(
      'quantity'                   =>($old_quantity-1),
      
      );
         $update=$this->admin->update('products',array('product_id'=>$product_id), $data);
      
       if($update)
       { echo "Product Sold";}
    }
     // echo "Product Sold";

      
  }

   
}