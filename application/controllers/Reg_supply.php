<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_supply extends CI_Controller {

  
  public function index()
  {

    $this->load->view('supply_form');
  }

  public function mail()
  {

    $this->load->view('mail');
  }

  public function getcurrency()
  {
    $id = $this->input->post('country_id');
    $getcurrency = $this->admin->getVal('SELECT currencyCode FROM countries WHERE id='.$id.'');
    echo $getcurrency;exit;
    
  }

  
  public function supply_form_save()
  {
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required|is_unique[members.user_name]');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('organization_name', 'Organization Name', 'trim|required');
        //$this->form_validation->set_rules('mailing_country', 'Mailing Country', 'trim|required');
        //$this->form_validation->set_rules('zip_code', 'Mailing Zip Code', 'trim|required');
        //$this->form_validation->set_rules('org_bu_add', 'Organization Business Address', 'trim|required');
        if ($this->form_validation->run() == FALSE)
        {
          if(form_error('user_id'))
            {
            $this->messages->add(form_error('user_id'), "alert-danger");
            redirect(base_url().'Reg_supply');
            }
            elseif(form_error('country'))
            {
            $this->messages->add(form_error('country'), "alert-danger");
            redirect(base_url().'Reg_supply');
            }

            elseif(form_error('organization_name'))
            {
            $this->messages->add(form_error('organization_name'), "alert-danger");
            redirect(base_url().'Reg_supply');
            }

        }
        else
        { 
          $image = '';
          if($_FILES['files']['name'])
            {
              
                $ext = end((explode(".", $_FILES['files']['name'])));
                $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext;
                if(move_uploaded_file($_FILES["files"]["tmp_name"],"uploads/org/".$imgname))
                {
                    copy("uploads/org/".$imgname,"uploads/org/resize/".$imgname);
                    $image = $imgname;
                }
            }
           // certificate of insurance
           $cert_of_inc = '';
          if($_FILES['cert_of_inc']['name'])
            {
              
                $ext = end((explode(".", $_FILES['cert_of_inc']['name'])));
                $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext;
                if(move_uploaded_file($_FILES["cert_of_inc"]["tmp_name"],"uploads/org/".$imgname))
                {
                    copy("uploads/org/".$imgname,"uploads/org/resize/".$imgname);
                    $cert_of_inc = $imgname;
                }
            }

            // proof of business
             $proof_of_business = '';
          if($_FILES['proof_of_business']['name'])
            {
              
                $ext = end((explode(".", $_FILES['proof_of_business']['name'])));
                $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext;
                if(move_uploaded_file($_FILES["proof_of_business"]["tmp_name"],"uploads/org/".$imgname))
                {
                    copy("uploads/org/".$imgname,"uploads/org/resize/".$imgname);
                    $proof_of_business = $imgname;
                }
            }
           // Transportation License
             $trans_license = '';
          if($_FILES['trans_license']['name'])
            {
              
                $ext = end((explode(".", $_FILES['trans_license']['name'])));
                $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext;
                if(move_uploaded_file($_FILES["trans_license"]["tmp_name"],"uploads/org/".$imgname))
                {
                    copy("uploads/org/".$imgname,"uploads/org/resize/".$imgname);
                    $trans_license = $imgname;
                }
            }

            // proof of banking
             $proof_of_banking = '';
          if($_FILES['proof_of_banking']['name'])
            {
              
                $ext = end((explode(".", $_FILES['proof_of_banking']['name'])));
                $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext;
                if(move_uploaded_file($_FILES["proof_of_banking"]["tmp_name"],"uploads/org/".$imgname))
                {
                    copy("uploads/org/".$imgname,"uploads/org/resize/".$imgname);
                    $proof_of_banking = $imgname;
                }
            }
           

  $array_org = array(
                                  //'member_id'           =>$mem_id,
                                  'organization_name'   =>$this->input->post('organization_name'),
                                  'fare_type'           =>$this->input->post('fare_type'),
                                  'org_reg_no'          =>$this->input->post('reg_no'),
                                  'gst_sst_vat'         =>$this->input->post('gst_reg_no'),
                                  'country'             =>$this->input->post('mailing_country'),
                                  'address'             =>$this->input->post('org_bu_add'),
                                  'bank_name'           =>$this->input->post('bank_name'),
                                  'bank_address'        =>$this->input->post('bank_address'),
                                  'ac_name'             =>$this->input->post('account_name'),
                                  'ac_no'               =>$this->input->post('account_number'),
                                  'swift_code'          =>$this->input->post('swift_code'),
                                  'iban_number'         =>$this->input->post('iban_number'),
                                  'zip_code'            =>$this->input->post('zip_code'),
                                  'street'              =>$this->input->post('street'),
                                  'state'               =>$this->input->post('state'),
                                  'city'                =>$this->input->post('city'),
                                  'org_logo'            =>$image ,
                                  'cert_of_inc'         =>$cert_of_inc,
                                  'proof_of_business'   =>$proof_of_business,
                                  'trans_license'       =>$trans_license,
                                  'proof_of_banking'    =>$proof_of_banking,
                                  'fleet_fee'           =>$this->input->post('fleet_fee'),
                                  'fleet_tax'           =>$this->input->post('fleet_tax'),
                                  'total_drivers'       =>$this->input->post('total_drivers'),



              // 'gst_sst_vat'      =>$this->input->post('gst_sst'),

                   );
                    $org = $this->admin->insert('organization', $array_org);
                
                     $org_id = $this->db->insert_id();
                  
                  // echo $this->db->last_query();die;

                if($org)
                { 
                  if(($this->input->post('agreement'))==1)
                  {
                  date_default_timezone_set("Asia/kolkata");
                  $accept_date=date("Y-m-d");
                  $accept_time=date("h:i:sa");
                }
                else if(($this->input->post('agreement'))==0)
                {
                  $accept_date="";
                  $accept_time="";
                
                }
                  
           $array = array(
                    'org_id'             =>$org_id,
                    'country_id'         =>$this->input->post('country'),
                    'company_name'       =>$this->input->post('organization_name'),
                    'user_name'          =>$this->input->post('user_id'),
                    'password'           =>$this->input->post('password'),
                    'display_name'       =>$this->input->post('organization_name'),
                    'accept_date'        =>$accept_date,
                    'accept_time'        =>$accept_time,
                    'usertype_id'        =>3,
                    'join_date'          =>$accept_date
                );
                   
                  $insert = $this->admin->insert('members', $array);
                  $mem_id = $this->db->insert_id();
                    //echo"inserted";die;
                 
                }

                if($insert)
                {
                   $salutation=$this->input->post('dir_salutation');
                   $first_name=$this->input->post('dir_first_name');
                   $last_name=$this->input->post('dir_last_name');
                   $office_no=$this->input->post('dir_office_no');
                   $ext_no=$this->input->post('dir_ext_no');
                   $mobile_no=$this->input->post('dir_mobile_no');
                   $email=$this->input->post('dir_email');
                   $alternative_email=$this->input->post('dir_alternative_email');
                   $newsletter_option=$this->input->post('dir_newsletter_option');

             
                  $at=count($this->input->post('dir_salutation'));
                 for($j=0;$j<$at;$j++)
                {
                  //echo $at;die;
                              $dir_doc = '';
                      if($_FILES['dir_doc']['name'][$j])
            {
              
                $ext = end((explode(".", $_FILES['dir_doc']['name'][$j])));
                $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext;
                if(move_uploaded_file($_FILES["dir_doc"]["tmp_name"][$j],"uploads/org/".$imgname))
                {
                    copy("uploads/org/".$imgname,"uploads/org/resize/".$imgname);
                    $dir_doc = $imgname;
                }
            }
             
         
                   $dir_details = array(

                  'member_id'           =>$mem_id,
                  'salutation'          =>$salutation[$j],
                  'first_name'          =>$first_name[$j],
                  'last_name'           =>$last_name[$j],
                  'office_no'           =>$office_no[$j],
                  'ext_no'              =>$ext_no[$j],
                  'mobile_no'           =>$mobile_no[$j],
                  'email'               =>$email[$j],
                  'alternative_email'   =>$alternative_email[$j],
                  'newsletter_option'   =>$newsletter_option[$j],
                  'dir_doc'             =>$dir_doc
                );
                  $dir = $this->admin->insert('directors_details', $dir_details);
                  //echo $this->db->last_query();die;

                   }
                }

                  if($dir)
                  {
                   $first_name=$this->input->post('acc_first_name');
                   $last_name=$this->input->post('acc_last_name');
                   $office_no=$this->input->post('acc_contact_no');
                   $ext_no=$this->input->post('acc_ext_no');
                   $mobile_no=$this->input->post('acc_mobile');
                   $email_address=$this->input->post('acc_email');
                   $complaint_email_address=$this->input->post('acc_complaint_email');
                   
                  $at=count($this->input->post('acc_first_name'));
                 for($k=0;$k<$at;$k++)
                {
                  //echo $at;die;
                   $acc_details = array(

                  'member_id'           =>$mem_id,
                  'first_name'          =>$first_name[$k],
                  'last_name'           =>$last_name[$k],
                  'office_no'           =>$office_no[$k],
                  'ext_no'              =>$ext_no[$k],
                  'mobile_no'           =>$mobile_no[$k],
                  'email_address'       =>$email_address[$k],
                  'complaint_email_address' =>$complaint_email_address
                );
                  $acc = $this->admin->insert('accounts_department', $acc_details);
                  //echo $this->db->last_query();die;

                   }
                  /* $directors_details_total=$this->admin->getRows('select * from directors_details where member_id="'.$mem_id.'"');
                   foreach($directors_details_total as $ddt)
                   {
                    $emailid=$ddt->email;


                    $this->load->library('email');

            $config['mailtype'] ='html';

            $this->email->initialize($config);

            $this->email->from('info@thecandytech.com', 'The Candy Tech');
            $this->email->to($emailid);

            $this->email->subject('Registered With The Candy Tech');
            $data = array(
             'name'=> $this->input->post('user_id'),
             'email'=>$this->input->post('user_id'),
             'password'=>$this->input->post('password'),
                 );
             $body = $this->load->view('mailformat/mail',$data,TRUE);
            $this->email->message($body);   

            if($this->email->send())
            {
              redirect('Login');
            }
                   }*/
}
              $this->messages->add('You have successfully registered as fleet , after approval you can login ', "alert-success");
              redirect('Login');
            }
        }   
      
      
      
      
  //}



  
}
