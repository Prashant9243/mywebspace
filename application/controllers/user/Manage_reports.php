<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_reports extends CI_Controller {

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
    $todaydate=date("Y-m-d");
		$data['purchases']=$this->admin->getRows('select * from product_purchase where product_purchase_date="'.$todaydate.'"');
    //echo $this->db->last_query();die;
   
    $data['template']='user/reports/daily_purchase_report';
		$this->load->view('user/layout/template',$data);
		
	}
  public function daily_sales_report()
  {
    $todaydate=date("Y-m-d");
        $data['purchases']=$this->admin->getRows('select * from invoice where invoice_date="'.$todaydate.'"');
    //echo $this->db->last_query();die;
   
    $data['template']='user/reports/daily_sales_report';
        $this->load->view('user/layout/template',$data);
        
    }
     public function monthly_sales_report()
  {
    $date=$this->input->post('month');
    $dm=(explode("-",$date));
    $year=($dm[0]);
    $month=($dm[1]);

    if(!empty($this->input->post()))
    {
      
      $data['invoice_list']=$this->admin->getRows('select * from invoice WHERE MONTH(invoice_date) ="'.$month.'" AND YEAR(invoice_date) ="'.$year.'" group by invoice_no');  
     // echo $this->db->last_query(); die;
    }
    else{
        //echo"here";die;
        $data['invoice_list']=$this->admin->getRows('select * from invoice WHERE MONTH(invoice_date) = MONTH(CURRENT_DATE()) AND YEAR(invoice_date) = YEAR(CURRENT_DATE()) group by invoice_no');
    //echo $this->db->last_query();die;
   }
    $data['template']='user/reports/monthly_sales_report';
        $this->load->view('user/layout/template',$data);
        
    }

     public function yearly_sales_report()
    {
      $data['purchases']=$this->admin->getRows('select * from invoice WHERE YEAR(invoice_date) = YEAR(CURRENT_DATE()) group by invoice_no');
    //echo $this->db->last_query();die;
   
    $data['template']='user/reports/yearly_sales_report';
        $this->load->view('user/layout/template',$data);   
    }

    public function monthly_purchase_report()
    {
      $date=$this->input->post('month');
    $dm=(explode("-",$date));
    $year=($dm[0]);
    $month=($dm[1]);

    if(!empty($this->input->post()))
    {
      
      $data['purchases']=$this->admin->getRows('select * from product_purchase WHERE MONTH(product_purchase_date) ="'.$month.'" AND YEAR(product_purchase_date) ="'.$year.'"');  
     // echo $this->db->last_query(); die;
    }
    else
    {
    
        $data['purchases']=$this->admin->getRows('select * from product_purchase WHERE MONTH(product_purchase_date) = MONTH(CURRENT_DATE()) AND YEAR(product_purchase_date) = YEAR(CURRENT_DATE())');
    //echo $this->db->last_query();die;
   }
    $data['template']='user/reports/monthly_purchase_report';
        $this->load->view('user/layout/template',$data);
        
    }

    public function yearly_purchase_report()
    {
      $data['purchases']=$this->admin->getRows('select * from product_purchase WHERE YEAR(product_purchase_date) = YEAR(CURRENT_DATE()) group by invoice_no');
    //echo $this->db->last_query();die;
   
    $data['template']='user/reports/yearly_purchase_report';
        $this->load->view('user/layout/template',$data);   
    }
 
}