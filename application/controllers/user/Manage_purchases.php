<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_purchases extends CI_Controller {

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
    // Generating product id
    
		$data['maincategories']=$this->admin->getRows('select * from main_categories where status=1 ');
    $data['subcategories']=$this->admin->getRows('select * from sub_categories where status=1');
    $data['wholesaler_details']=$this->admin->getRows('select * from wholesaler_details');
    $data['template']='user/purchase/index';
		$this->load->view('user/layout/template',$data);
		
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
   public function subcategory()
  {
    
    $maincategory=$this->input->post('categoryname');
    $subcategory=$this->admin->getRows('select * from sub_categories where main_category_id="'.$maincategory.'"');
    foreach ($subcategory as $sub) {
      # code...
      echo "<option value=".$sub->id.">".$sub->sub_category_name."</option>";
    }
  }
  public function insertproduct()
  {
    //$data = $this->input->post();
    $wholesaler=$this->input->post('wholesaler_id');
    $name=$this->input->post('wholesaler_name');
    if(($wholesaler==NULL) && ($name!=NULL))
    {
      //echo "Finally Here";die;
      $w_details = array(
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
      'quantity'                  =>$this->input->post('quantity'),
      'unit_of_measurement'       =>$this->input->post('uquantity').' '.$this->input->post('uom'),
      'expiry_date'               =>$this->input->post('expiry_date'),
      'cost_price'                =>$this->input->post('cost_price'),
      'sale_price'                =>$this->input->post('sale_price'),
      'gst'                       =>$this->input->post('gst'),
      'cgst'                      =>$this->input->post('cgst'),
      'amt_paid'                  =>$this->input->post('amt_paid'),
      'wholesaler_id'             =>$insert,
      'status'                    =>1
      );
      
      $productinsert = $this->admin->insert('product_purchase', $data);
      if($productinsert)
      {
         $product_id=$this->input->post('product_id');
    $check_product_exists=$this->admin->getRow('select * from products where product_id="'.$product_id.'"');
    $old_quantity=$this->admin->getVal('select quantity from products where product_id="'.$product_id.'"');
    if(($check_product_exists)&&($old_quantity==0))
    {
      
      $input_quantity=$this->input->post('quantity');
      $new_quantity=$old_quantity + $input_quantity;
      $array = array(
                    
                    'product_purchase_date'     =>$this->input->post('product_purchase_date'),
                    'quantity'                  =>$new_quantity,
                    'expiry_date'               =>$this->input->post('expiry_date'),
                    'cost_price'                =>$this->input->post('cost_price'),
                    'sale_price'                =>$this->input->post('sale_price'),
                    'gst'                       =>$this->input->post('gst'),
                    'cgst'                      =>$this->input->post('cgst'),
                    'wholesaler_id'             =>$insert,
                    'status'                    =>1
                    );
       $update=$this->admin->update('products',array('product_id'=>$product_id), $array);

    }
    else{
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
      'wholeseler_id'             =>$insert,
      'status'                    =>1
      );
      
      $productinsert = $this->admin->insert('products', $data);
    }
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
      'amt_paid'                  =>$this->input->post('amt_paid'),
      'wholesaler_id'             =>$this->input->post('wholesaler_id'),
      'status'                    =>1
      );
      
      $insert = $this->admin->insert('product_purchase', $data);
      if($insert)
      {
        $product_id=$this->input->post('product_id');
    $check_product_exists=$this->admin->getRow('select * from products where product_id="'.$product_id.'"');
    if($check_product_exists)
    {
      $old_quantity=$this->admin->getVal('select quantity from products where product_id="'.$product_id.'"');
      $input_quantity=$this->input->post('quantity');
      $new_quantity=$old_quantity + $input_quantity;
      $array = array(
                    
                    'product_purchase_date'     =>$this->input->post('product_purchase_date'),
                    'quantity'                  =>$new_quantity,
                    'expiry_date'               =>$this->input->post('expiry_date'),
                    'cost_price'                =>$this->input->post('cost_price'),
                    'sale_price'                =>$this->input->post('sale_price'),
                    'gst'                       =>$this->input->post('gst'),
                    'cgst'                      =>$this->input->post('cgst'),
                    'wholesaler_id'             =>$this->input->post('wholesaler_id'),
                    'status'                    =>1
                    );
       $update=$this->admin->update('products',array('product_id'=>$product_id), $array);
    }
    else{
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
      
      $productinsert = $this->admin->insert('products', $data);
    }
      }
    }
    redirect('user/Manage_purchases/purchase_list');
  }

  public function wholesaler_details()
  {
    $w_id=$this->input->post('w_id');
    $wholesaler_details=$this->admin->getRows('select * from wholesaler_details where id="'.$w_id.'"');
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

  public function purchase_list()
  {
    $data['products']=$this->admin->getRows('select * from product_purchase ');
    $data['template']='user/purchase/list';
    $this->load->view('user/layout/template',$data);
  }
  

   public function search_product(){
    $this->load->model('Welcome_model');
    $postData = $this->input->post();
    $data = $this->Welcome_model->getUsers($postData);
    echo json_encode($data);
  }

  public function product_id()
  {
    $productname=$this->input->post('productname');
    $product_id=$this->admin->getVal('select product_id from products where product_name="'.$productname.'"');
    
     $myJSON = json_encode($product_id);
     echo($myJSON);

    }

  public function get_new_row()
  {
    $html='<tr>
            <td class="plus"><button class="btn btn-success add" type="button"><i class="fa fa-plus"></i></button></td>
            <td><input type="text" id="" name="product_code[]" class="form-control productcode" placeholder="Product Code/Name"></td>
            <td><input type="text" name="quantity[]" class="form-control" placeholder="Quantity"></td>
            <td><select name="uom" class="select2_single form-control js-example-basic-single col-md-10"  id="uom">
                                     <option value="Unit" selected hidden disabled>Unit</option>
                                   </select></td><td><input class="form-control date" type="date" name="expiry_date[]"></td>
                          <td><input type="text" name="cost_price[]" class="form-control cp" placeholder="Cost Price"></td>
                          <td><input type="text" name="sell_price[]" class="form-control" placeholder="selling Price"></td>
                        </tr>';
      echo $html;exit;
  }

  public function save_purchase()
  {

    $product_id = $this->input->post('product_code');
    
    $quantity = $this->input->post('quantity');
    $unit_of_measurement= $this->input->post('uom');
    $expiry_date = $this->input->post('expiry_date');
    $cost_price = $this->input->post('cost_price');
    $sale_price = $this->input->post('sell_price');
    $retailer_mobile = $this->input->post('retailer_mobile');
    $retailer_name = $this->input->post('wholesaler_id');
    $invoice_id = $this->input->post('invoice_id');
    
    $retailer = $this->admin->check_retailer($retailer_mobile);
    if(empty($retailer))
    {
        $ret_array = array(
            'user_id'       => $this->session->userdata('userid'),
            'name'          => $retailer_name,
            'contact_no'    => $retailer_mobile
            );
        $ret = $this->db->insert('wholesaler_details',$ret_array);
    }

    $pcount = count($product_id);

    for($i=0;$i<$pcount;$i++)
    {
      $pcode_name = explode('#',$product_id[$i]);
      $pcode  = $pcode_name[0];
      $pname = $pcode_name[1];
      $check_old_pro = $this->admin->check_old_pro($pcode);
      if($check_old_pro)
      {
        //old product
        $nproduct_id = $pcode;
        $product_name = $pname;
        
        // ************************************** update product quantity ****************************************
        
        $quantity = array(
            'quantity'  => $check_old_pro + $quantity[$i]
        );
        
        $where_quantity = array(
            'product_id' => $pcode
        );
        
        $update_quantity = $this->db->update('products',$quantity,$where_quantity);
      
          
      }
      else
      {
        // new product
        $product_name = $pname;
        $company_name=$this->session->userdata('company_name');
        $company= substr($company_name, 0, 3);
        $number=rand(pow(10, 4-1), pow(10, 4)-1);
        $maincategory=$this->input->post('categoryname');
        $category_code=$this->admin->getVal('select category_code from main_categories where id="'.$maincategory.'"');
        $productid=$company.$number.$category_code;
        $nproduct_id = $productid;
      }

        $array = array(
          'invoice_id'                =>  $invoice_id,
          'user_id'                   =>  $this->session->userdata('userid'),
          'product_id'                =>  $nproduct_id,
          'product_name'              =>  $product_name.' '.$quantity[$i].' '.$unit_of_measurement[$i],
          'product_purchase_date'     =>  $this->input->post('product_purchase_date'),
          'quantity'                  =>  $quantity[$i],
          'unit_of_measurement'       =>  $unit_of_measurement[$i],
          'expiry_date'               =>  $expiry_date[$i],
          'cost_price'                =>  $cost_price[$i],
          'sale_price'                =>  $sale_price[$i],
          /*'gst'                       =>  $this->input->post('gst'),
          'cgst'                      =>  $this->input->post('cgst'),*/
          'amt_paid'                  =>  $this->input->post('amt_paid'),
          'wholesaler_id'             =>  $this->input->post('wholesaler_id'),
          'status'                    =>  1
          );
          
          $insert = $this->admin->insert('product_purchase', $array);
          echo $this->db->last_query();
    }

    redirect(base_url().'user/Manage_purchases/print_invoice/'.$invoice_id);
  }

  public function autocomplete(){
    $this->load->model('Welcome_model');
    $postData = $this->input->post();
    $data = $this->Welcome_model->getfullcode($postData);
    echo json_encode($data);
  }
  

  public function retailerautocomplete(){
    $this->load->model('Welcome_model');
    $postData = $this->input->post();
    $data = $this->Welcome_model->getretailer($postData,$this->session->userdata('userid'));
    echo json_encode($data);
  }
  
  public function get_retailer_mobile()
  {
      $id = $this->input->post('id');
      $this->db->select('contact_no');
      $this->db->where('id',$id);
      $query = $this->db->get('wholesaler_details');
      $querys = $query->row();
      if($querys)
      {
          echo $querys->contact_no;
      }
      else
      {
          echo '';
      }
  }

  public function print_invoice($invoice_id)
  {
    $this->db->select('*');
    $this->db->where('invoice_id',$invoice_id);
    $query = $this->db->get('product_purchase');
      $querys = $query->result();
      if($querys)
      {
          $data['products'] = $querys;
      }

    $this->db->select('*');
    $this->db->where('invoice_id',$invoice_id);
    $query = $this->db->get('product_purchase');
      $querys = $query->row();
      if($querys)
      {
          $invoice = $querys;
          $data['invoice'] = $invoice;
      }

      $data['retailer'] = $this->admin->retailer_detail($invoice->wholesaler_id);
      
      $this->load->view('user/purchase/print_invoice',$data);
  }
  

  
}