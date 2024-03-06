<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_invoice extends CI_Controller {

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
        $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $invoicenumber = substr(str_shuffle($str), 0, 6);
        $data['user_details']=$this->admin->getRow('select * from users where username="'.$this->session->userdata('username').'"');
        $data['invoice_no']=$invoicenumber;
        $data['template']='user/invoice/invoice';
		$this->load->view('user/layout/template',$data);
		
	}

    public function create_invoice()
    {
        $todaydate=date("Y-m-d");
        $quantity=$this->input->post('quantity');
        $pid=$this->input->post('productcode');
        $invoicenumber=$this->input->post('invoiceno');
        $product_details=$this->admin->getRow('select * from products where product_id="'.$pid.'"');
        $price=$product_details->sale_price;
        //check if product already exists in invoice
        $exists_invoice=$this->admin->getVal('select qty from invoice where invoice_no="'.$invoicenumber.'" and product_id="'.$pid.'"');
        $new_qty=$exists_invoice+$quantity;

        if($exists_invoice)
        {
             $array = array(
                    
                    'qty'                  =>$new_qty,
                    
                    );
       $update=$this->admin->update('invoice',array('product_id'=>$pid,'invoice_no'=>$invoicenumber), $array);
       if($update)
            {
                $quantity=$this->input->post('quantity');

                    do {
                        $id=$this->admin->getVal('select id from products where product_id="'.$pid.'" and quantity!=0 order by id ASC limit 1');
                        $q=$this->admin->getVal('select quantity from products where product_id="'.$pid.'" and quantity!=0 order by id ASC limit 1');
                        if($q<$quantity)
                        {
                             $array = array(
                            
                            'quantity'                  =>0,
                            'expiry_date'               =>'',
                            
                            );
                            $update=$this->admin->update('products',array('id'=>$id), $array);
                            //echo $this->db->last_query();
                            $quantity=$qantity-$q;
                        }
                        else
                        {
                            $new_quantity=$q-$quantity;
                                $array = array(
                        
                                'quantity'                  =>$new_quantity,
                        
                                );
                            $update=$this->admin->update('products',array('id'=>$id), $array);
                            $quantity=0;   
                        }

                    }while ($quantity> 0);
                 
                $invoice=$this->admin->getRows('select * from invoice where invoice_no="'.$invoicenumber.'"');
                $total=$this->admin->getVal('select sum(amount) from invoice where invoice_no="'.$invoicenumber.'"');
                $i=1;
                foreach ($invoice as $inv) { 
                    if(!empty($inv->product_id)){ $product_name=$this->admin->getVal('select product_name from products where product_id="'.$inv->product_id.'"');
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . $inv->product_id . "</td>";
                    echo "<td>" . $product_name . "</td>";     
                    echo "<td>" . $inv->qty . "</td>";
                    echo "<td>" . $inv->price . "</td>";
                    echo "<td>" . $inv->amount . "</td>";
                    echo"</tr>";
                     }
                      }
                      echo":$total";
    }

        } 
        else
        {

        $data = array(
          'user_id'         =>$this->session->userdata('userid'),
          'invoice_no'      =>$this->input->post('invoiceno'),
          'invoice_date'    =>$todaydate,
          'user_id'         =>$this->session->userdata('userid'),
          'product_id'      =>$this->input->post('productcode'),
          'price'           =>$price,
          'qty'             =>$this->input->post('quantity'),
          'amount'          =>$price * $quantity 
        );
       
    $productinsert = $this->admin->insert('invoice', $data);
      if($productinsert)
      {
         $inputquantity=$this->input->post('quantity');

                    do {
                        $id=$this->admin->getVal('select id from products where product_id="'.$pid.'" and quantity!=0 order by id ASC limit 1');
                        $q=$this->admin->getVal('select quantity from products where product_id="'.$pid.'" and quantity!=0 order by id ASC limit 1');
                        
                        if($q<$inputquantity)
                        {
                     $array = array(
                    
                    'quantity'                  =>0,
                    'expiry_date'               =>'',
                    
                    );
                    $update=$this->admin->update('products',array('id'=>$id), $array);
                    $quantity=$inputquantity-$q;
                    }
                    else
                    {
                        $new_quantity=$q-$quantity;
                        $array = array(
                    
                        'quantity'                  =>$new_quantity,
                        );
                    $update=$this->admin->update('products',array('id'=>$id), $array);
                    $quantity=0;   
                    }


                    } while ($quantity> 0);
      }
      $invoice=$this->admin->getRows('select * from invoice where invoice_no="'.$invoicenumber.'"');
      $total=$this->admin->getVal('select sum(amount) from invoice where invoice_no="'.$invoicenumber.'"');

        if(!empty($invoice))
            {
                $i=1;
                foreach ($invoice as $inv) { 
                    if(!empty($inv->product_id)){ $product_name=$this->admin->getVal('select product_name from products where product_id="'.$inv->product_id.'"');
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . $inv->product_id . "</td>";
                    echo "<td>" . $product_name . "</td>";     
                    echo "<td>" . $inv->qty . "</td>";
                    echo "<td>" . $inv->price . "</td>";
                    echo "<td>" . $inv->amount . "</td>";
                    echo"</tr>";
                     }
                      }

                       echo":$total";
    }
}
}

    public function check_product_code()
    {
        $product_code=$this->input->post('productcode');
        $exists=$this->admin->getRow('select * from products where product_id="'.$product_code.'"');
        $qty=$this->admin->getVal('select sum(quantity) from products where product_id="'.$product_code.'"');

        if(!empty($exists))
        {
            // valid product exists
            if($qty<=0)
             {
             echo"2";   
             }
             else
            {
            echo "1";
            }
        }
        else
        {
            // Invalid no product Found
            echo "0";
        }
        //echo $this->db->last_query();


    }
    public function check_product_code_remove()
    {
        $product_code=$this->input->post('productcode');
        $invoiceno=$this->input->post('invoiceno');
        $exists=$this->admin->getRow('select * from invoice where product_id="'.$product_code.'" and invoice_no="'.$invoiceno.'"');
        
        if(!empty($exists))
        {
            // valid product exists
            echo "1";
        }
        else
        {
            // Invalid no product Found
            echo "0";
        }
        //echo $this->db->last_query();


    }
    public function check_quantity()
    {
        $quantity=$this->input->post('quantity');
        $product_code=$this->input->post('productcode');
        $check_quantity=$qty=$this->admin->getVal('select sum(quantity) from products where product_id="'.$product_code.'"');
        if($check_quantity<$quantity)
        {
            echo "$check_quantity";
        }
    }

    public function check_quantity_remove()
    {
        $quantity=$this->input->post('quantity');
        $product_code=$this->input->post('productcode');
        $invoiceno=$this->input->post('invoiceno');
        $check_quantity=$qty=$this->admin->getVal('select qty from invoice where product_id="'.$product_code.'" and invoice_no="'.$invoiceno.'"');
        if($check_quantity<$quantity)
        {
            echo "$check_quantity";
        }
    }

    public function remove_from_invoice()
    {
        $product_code=$this->input->post('productcode');
        $quantity=$this->input->post('quantity');
        $invoiceno=$this->input->post('invoiceno');
        
        $product_price=$this->admin->getVal('select sale_price from products where product_id="'.$product_code.'"');
        $check_quantity=$this->admin->getVal('select qty from invoice where product_id="'.$product_code.'" and invoice_no="'.$invoiceno.'" ');
        $new_quantity=$check_quantity-$quantity;
        $old_product_quantity=$this->admin->getVal('select quantity from products where product_id="'.$product_code.'"');
        $product_quantity=$old_product_quantity+$quantity;

        if($check_quantity==$quantity)
        {
            //delete
             $delete=$this->admin->delete('invoice',array('product_id'=>$product_code,'invoice_no'=>$invoiceno));

        /******* ------------------- Update Product table  ------------------ *******/
             $array = array(
                    
                    'quantity'                  =>$product_quantity,
                    
                    );
       $update=$this->admin->update('products',array('product_id'=>$product_code), $array);


        /******* ------------------- Update Product table  ------------------ *******/
            //generate new table
             $invoice=$this->admin->getRows('select * from invoice where invoice_no="'.$invoiceno.'"');
             $total=$this->admin->getVal('select sum(amount) from invoice where invoice_no="'.$invoiceno.'"');

        if(!empty($invoice))
            {
                $i=1;
                foreach ($invoice as $inv) { 
                    if(!empty($inv->product_id)){ $product_name=$this->admin->getVal('select product_name from products where product_id="'.$inv->product_id.'"');
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . $inv->product_id . "</td>";
                    echo "<td>" . $product_name . "</td>";     
                    echo "<td>" . $inv->qty . "</td>";
                    echo "<td>" . $inv->price . "</td>";
                    echo "<td>" . $inv->amount . "</td>";
                    echo"</tr>";
                     }
                      }
                       echo":$total";
    }
        }
        else
        {
            //update
            $array = array(
                    
                    'qty'                  =>$new_quantity,
                    'amount'               =>$product_price * $new_quantity,

                    
                    );
       $update=$this->admin->update('invoice',array('product_id'=>$product_code,'invoice_no'=>$invoiceno), $array);

        /******* ------------------- Update Product table  ------------------ *******/
             $array = array(
                    
                    'quantity'                  =>$product_quantity,

                    
                    );
       $update=$this->admin->update('products',array('product_id'=>$product_code), $array);


        /******* ------------------- Update Product table  ------------------ *******/

       //generate new table
             $invoice=$this->admin->getRows('select * from invoice where invoice_no="'.$invoiceno.'"');
             $total=$this->admin->getVal('select sum(amount) from invoice where invoice_no="'.$invoiceno.'"');

        if(!empty($invoice))
            {
                $i=1;
                foreach ($invoice as $inv) { 
                    if(!empty($inv->product_id)){ $product_name=$this->admin->getVal('select product_name from products where product_id="'.$inv->product_id.'"');
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . $inv->product_id . "</td>";
                    echo "<td>" . $product_name . "</td>";     
                    echo "<td>" . $inv->qty . "</td>";
                    echo "<td>" . $inv->price . "</td>";
                    echo "<td>" . $inv->amount . "</td>";
                    echo"</tr>";
                     }
                      }
                       echo":$total";
    }
        }

    }

    public function customer_details()
    {
        $invoiceno=$this->input->post('invoiceno');
        $custname=$this->input->post('custname');
        $mobile=$this->input->post('mobile');

        $invoice_details=$this->admin->getRow('select * from invoice_details where invoice_no="'.$invoiceno.'"');
        if($invoice_details)
        {
            // update
            $array = array(
                
                'customer_name'   =>$custname,
                'mobile'          =>$mobile
                    
                    );
       $update=$this->admin->update('invoice_details',array('invoice_no'=>$invoiceno), $array);
       //echo $this->db->last_query();
        }
        else
        {
            //insert
             $data = array(
                'user_id'         =>$this->session->userdata('userid'),
                'invoice_no'      =>$invoiceno,
                'customer_name'   =>$custname,
                'mobile'          =>$mobile
          
      );
       
    $productinsert = $this->admin->insert('invoice_details', $data);
   // echo $this->db->last_query();
    echo"$custname";echo"$mobile";
        }





    }

    public function invoice_list()
    {
         $data['invoice_list']=$this->admin->getRows('select * from invoice where user_id="'.$this->session->userdata('userid').'" group by invoice_no');
        $data['template']='user/invoice/invoice_list';
        $this->load->view('user/layout/template',$data);

    }

    public function save_bill()
    {
       $invoiceno=$this->input->post('invoiceno');
       $bill_amount=$this->admin->getVal('select sum(amount) from invoice where invoice_no="'.$invoiceno.'"');
        $array = array(
                
                'bill_amount'   =>$bill_amount,
                'bill_status'   =>$this->input->post('payment_status'),
                'payment_mode'  =>$this->input->post('payment_mode')
                    
                    );
       $update=$this->admin->update('invoice_details',array('invoice_no'=>$invoiceno), $array);
        echo"$bill_amount";
    }

    public function autocomplete(){
    $this->load->model('Welcome_model');
    $postData = $this->input->post();
    $data = $this->Welcome_model->getPcode($postData);
    echo json_encode($data);
  }

   public function rautocomplete(){
    $this->load->model('Welcome_model');
    $postData = $this->input->post();
    $data = $this->Welcome_model->getPcoder($postData);
    echo json_encode($data);
  }


 
}