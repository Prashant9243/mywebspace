  <?php
  if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
  require APPPATH . '/libraries/REST_Controller.php';

  
  class Driverapitest extends REST_Controller {
  
    public function __construct()
    {
      header('Access-Control-Allow-Origin: *');
      header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
      parent::__construct();
      $this->load->model('api/service_m');
      $this->load->library('form_validation');
      $this->load->helper("send_email_helper");
    }

    // login API //
    public function login_post()
    {
      $getdetail = file_get_contents('php://input');
      $getdetails = json_decode($getdetail);  
      $email = $getdetails->{'username'};
      $password = $getdetails->{'password'};
      $device_id = $getdetails->{'device_id'};
      $device_type = $getdetails->{'device_type'};
      $token_id = $getdetails->{'token_id'};
       
       // validation //
       if($email == '')
        {
       $this->response(['responsecode' => 0,'response' => 'Please enter Email'], REST_Controller::HTTP_OK);
        }
       
       elseif($password == '')
        {
      $this->response(['responsecode' => 0,'response' => 'Please enter Password'], REST_Controller::HTTP_OK);
        }
       
       else
        {
        $checkid = $this->service_m->getRow("select m.*,dd.dispatch_id,dd.fname,dd.lname,dd.address1,dd.postcode,dd.state,dd.country,dd.plate_no,dd.carcategory,dd.vehicle_type,dd.year_of_reg,dd.bank_name,dd.ac_name,dd.ac_type,dd.ac_no,dd.emg_person,dd.emg_contact,dd.profile_photo,dd.lc_photo,dd.insurance_photo,dd.vocational_license,dd.vocational_license_expiry,dd.vehicle_permit,dd.vehicle_permit_expiry,dd.vehicle_insurance_expiry,dd.driving_license_expiry,dd.activestatus,dd.alt_emg_contact,dd.varification,dd.status,dd.currency,dd.status dd_status from members m, driver_details dd  where dd.id=m.org_id and m.email='".$email."' and m.usertype_id=4");

        if($checkid->carcategory != '')
            {
        $carcategory = $this->service_m->getVal('SELECT name FROM car_category WHERE id = '.$checkid->carcategory.'');

        $vehicletype = $this->service_m->getVal('SELECT category_name FROM vehicle_category WHERE id = '.$checkid->vehicle_type.'');
        }
         
    
    if($checkid->email != $email)
            {
      $this->response(['responsecode' => 0,'response' => 'Email id is not valid'], REST_Controller::HTTP_OK);
            }
   else if($checkid->password != $password){
            $this->response(['responsecode' => 0,'response' => 'Password does not match'], REST_Controller::HTTP_OK);
            } 
         else
           {
        if($checkid->varification == 1)
           { 

            if($checkid->email != $email)
            {
              $this->response(['responsecode' => 0,'response' => 'Email id is not valid'], REST_Controller::HTTP_OK);
            }
        
        else if($checkid->password != $password){
                $this->response(['responsecode' => 0,'response' => 'Password does not match'], REST_Controller::HTTP_OK);
            } 
        
        else if($checkid->dd_status == 2)
           {
           $this->response(['response' => 0,'response' => 'You have been blocked for more details contact info@thecandytech.com'], REST_Controller::HTTP_OK);
           }else{
          $arr  = array('device_id'=>$device_id,'device_type'=>$device_type,'token_id'=>$token_id);
          $where = array('id'=>$checkid->id);
          $update = $this->service_m->update('members', $where, $arr);

          $country_name = $this->service_m->getVal('SELECT countryName FROM countries WHERE id='.$checkid->country.'');
          
          if($checkid->vocational_license_expiry == '0000-00-00')
          {
            $vcexpiry = $checkid->vocational_license_expiry;
          }
          else
          {
            $vcexpiry = date('d-m-Y',strtotime($checkid->vocational_license_expiry)); 
          }
          if($checkid->vehicle_insurance_expiry == '0000-00-00')
          {
            $vinsuranceexpiry = $checkid->vehicle_insurance_expiry;
          }
          else
          {
            $vinsuranceexpiry = date('d-m-Y',strtotime($checkid->vehicle_insurance_expiry));
          }
          if($checkid->vehicle_permit_expiry == '0000-00-00')
          {
            $vehiclepermitexpiry = $checkid->vehicle_permit_expiry;
          }
          else
          {
            $vehiclepermitexpiry = date('d-m-Y',strtotime($checkid->vehicle_permit_expiry));
          }
          if($checkid->driving_license_expiry == '0000-00-00')
          {
            $drivinglicense = $checkid->driving_license_expiry;
          }
          else
          {
            $drivinglicense = date('d-m-Y',strtotime($checkid->driving_license_expiry));
          }

          $alldetail[] = array(
                              'id'                => $checkid->org_id,  
                              'firstname'         => $checkid->fname,
                              'lastname'          => $checkid->lname,
                              'fleetid'           => $checkid->dispatch_id,
                              'address'           => $checkid->address1,
                              'postcode'          => $checkid->postcode,
                              'state'             => $checkid->state,
                              'country'           => $checkid->country,
                              'mobile'            => $checkid->mobile,
                              'email'             => $checkid->email,
                              'mobile'            => $checkid->mobile_no,
                              'plate_no'          => $checkid->plate_no,
                              'carcategory'       => $carcategory,
                              'vehicletype'       => $vehicletype,
                              'yearofreg'         => $checkid->year_of_reg,
                              'bankname'          => $checkid->bank_name,
                              'accountno'         => $checkid->ac_no,
                              'emergencypserson'  => $checkid->emg_person,
                              'emergencycontact'  => $checkid->emg_contact,
                              'profilephoto'      => $checkid->profile_photo,
                              'licensephoto'      => $checkid->lc_photo,
                              'insurancephoto'    => $checkid->insurance_photo,
                              'vocationallicense' => $checkid->vocational_license,
                              'vcexpiry'          => $vcexpiry,
                              'vinsuranceexpiry'  => $vinsuranceexpiry,
                              'vehiclepermitexpiry' => $vehiclepermitexpiry,
                              'drivinglicense'    => $drivinglicense,
                              'vehiclepermit'     => $checkid->vehicle_permit,
                              'activestatus'      => $checkid->activestatus,
                              'currency'          => $checkid->currency

          );

          $this->response(['responsecode' => 1,'email'=>$email,'email'=>$email,"baseurl" => base_url().'uploads/driver/','alldetail'=>$alldetail,'response' => 'Success!'], REST_Controller::HTTP_OK);
            }
          }else{
          $this->response(['responsecode' => 0,'response' => 'Your accout is not verified'], REST_Controller::HTTP_OK);
               }
            }
        }
       }

      // Log Out API // 
      public function logoutdata_post()
       {
        $getdetail = file_get_contents('php://input');
        $getdetails = json_decode($getdetail); 
        $id = $getdetails->{'id'};
        $array = array(
           'device_id' =>'',
           'token_id'  =>''
             );
        $where = array(
                'org_id'    => $id,
                'usertype_id' => 4
                );
        $update = $this->service_m->update('members', $where, $array);


        if($update)
          {
            $array1 = array(
                    'activestatus' => 0 
                  );
            $where1 = array('id'=> $id);
            $update1 = $this->service_m->update('driver_details', $where1, $array1);

          $this->response([
                      'responsecode' => 1,
                      'response' => 'Logout successfully.'], REST_Controller::HTTP_OK);
          }
         else
          {
          $this->response([
                'responsecode' => 0,
                'response' => 'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
          } 
       }


       // Forgot API When password forgot //
      public function forgot_password_post()
      {
          $getdetail = file_get_contents('php://input');
          $getdetails = json_decode($getdetail);

          $email = trim($getdetails->{'email'}, TRUE);
          $password = mt_rand(100000, 999999);
          $member = $this->service_m->getVal('SELECT id FROM members WHERE 
            email = "'.$email.'" AND usertype_id = 4'); 
          if($member!='')
          {
            //  Mail sending //
            $this->load->library('email');

            $config['mailtype'] ='html';

            $this->email->initialize($config);

            $this->email->from('info@thecandytech.com', 'The Candy Tech');
            $this->email->to($email);

            $this->email->subject('Forgot Password');
            $data = array(
             'email' => $email,
             'password' => $password,
                 );
             $body = $this->load->view('mailformat/forgotpassword',$data,TRUE);
            $this->email->message($body);   
            $this->email->send();

            $array = array(
                        'password' => $password
                            );
            $update = $this->admin->update('members',array('id'=>$member), $array);
            //echo $this->db->last_query(); exit;
            if($update)
              {
                $this->response([
                      'responsecode' => 1,
                      'response' => 'Password Send Successfully in your email !Please check your email! .'], REST_Controller::HTTP_OK);
                }
            else
            {
              $this->response(['responsecode' => 0,'response' => 'Email Id  is Invalid'], REST_Controller::HTTP_OK);
            }
          }
          else
          {
            $this->response(['responsecode' => 0,'response' =>'Email Id is Invalid'], REST_Controller::HTTP_OK);
          }
      }

    //  get all fleet from database API //
    public function get_fleet_get()
      {
        $fleet = $this->service_m->getRows('SELECT id,display_name FROM members WHERE usertype_id=3 ORDER BY display_name ASC ');

        if(!empty($fleet)){

          $this->response(['responsecode' => 1, 'fleet' => $fleet], REST_Controller::HTTP_OK);
        }

        else
        {
          $this->response(['responsecode' => 0, 'fleet' => []], REST_Controller::HTTP_OK);
        }
      }

    // get country from database API //
    public function country_get()
    {
      $countries = $this->service_m->getRows('SELECT countryName,id FROM countries ORDER BY countryName ASC');

      if(!empty($countries))
      {
        $this->response(['responsecode' => 1, 'countries' => $countries], REST_Controller::HTTP_OK);
      }
      else
      {
        $this->response(['responsecode' => 0, 'countries' => []], REST_Controller::HTTP_OK);
      }
    }

    public function currency_post()
    {
      $country_id   = file_get_contents('php://input');
      $country_ids  = json_decode($country_id);
      $currency   = $this->service_m->getVal('SELECT currencyCode FROM countries WHERE id = '.$country_ids->{'country_id'}.'');       

      if(!empty($currency))
      {
        $this->response(['responsecode' => 1, 'currency' => $currency], REST_Controller::HTTP_OK);
      }
      else
      {
        $this->response(['responsecode' => 0, 'currency' => []], REST_Controller::HTTP_OK);
      }
    }

    public function driver_profile_post()
    {

                //  all keys //  
       $fleet             =   $this->input->post('fleet_id');
       $fname             =   $this->input->post('fname');
       $lname             =   $this->input->post('lname');
       $country           =   $this->input->post('country');
       $currency          =   $this->input->post('currency');
       $state             =   $this->input->post('state');
       $postcode          =   $this->input->post('postcode');
       $address1          =   $this->input->post('address');
       $email             =   $this->input->post('email');
       $mobile_no         =   $this->input->post('mobile_no');
       $driving_license_expiry      = $this->input->post('driving_license_expiry');
       $vocational_license_expiry   = $this->input->post('vocational_license_expiry');
       $plate_no            =   $this->input->post('plate_no');
       $vehicle_type        = $this->input->post('vehicle_type');
       $vehicle_category    = $this->input->post('vehicle_category');
       $year_of_reg         = $this->input->post('year_of_reg');
       $vehicle_insurance_expiry    = $this->input->post('vehicle_insurance_expiry');
       $vehicle_permit_expiry       = $this->input->post('vehicle_permit_expiry');
       $bank_name           = $this->input->post('bank_name');
       $ac_name             = $this->input->post('ac_name');
       $ac_no               = $this->input->post('ac_no');
       $emg_person          = $this->input->post('emg_person');
       $emg_contact         = $this->input->post('emg_contact');
       $alternate_emg_num   = $this->input->post('alt_emg_contact');
       $device_id           = $this->input->post('device_id');
       $device_type         = $this->input->post('device_type');

    
          /*  Validation of keys */
        $getemail = $this->service_m->getVal('SELECT email FROM members WHERE email="'.$this->input->post('email').'"');

        $getnumber = $this->service_m->getVal('SELECT mobile_no FROM members WHERE 
          mobile_no = "'.$this->input->post('mobile_no').'"'); 

        if($fname == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter first name'], REST_Controller::HTTP_OK);
        }
        elseif($lname == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter last name'], REST_Controller::HTTP_OK);
        }
        elseif($country == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select country'], REST_Controller::HTTP_OK);
        }
        elseif($state == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select state'], REST_Controller::HTTP_OK);
        }
        elseif($postcode == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter postcode'], REST_Controller::HTTP_OK);
        }  
        elseif($address1 == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter address'], REST_Controller::HTTP_OK);
        } 
        elseif($email == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter email'], REST_Controller::HTTP_OK);
        } 
        elseif($mobile_no == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter mobile no'], REST_Controller::HTTP_OK);
        }  
        elseif($driving_license_expiry == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter driving license expiry'], REST_Controller::HTTP_OK);
        }
        elseif($vocational_license_expiry == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter vocational license expiry'], REST_Controller::HTTP_OK);
        }
        elseif($plate_no == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter plate no.'], REST_Controller::HTTP_OK);
        }
        elseif($vehicle_type == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select vehicle type'], REST_Controller::HTTP_OK);
        }
        elseif($vehicle_category == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select vehicle category'], REST_Controller::HTTP_OK);
        }
        elseif($year_of_reg == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter year of registration'], REST_Controller::HTTP_OK);
        }  
        elseif($vehicle_insurance_expiry == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter vehicle insurance expiry'], REST_Controller::HTTP_OK);
        } 
        elseif($vehicle_permit_expiry == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter vehicle permit expiry'], REST_Controller::HTTP_OK);
        }  
        elseif($bank_name == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter bank name'], REST_Controller::HTTP_OK);
        }
        elseif($ac_name == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter account name'], REST_Controller::HTTP_OK);
        }
        elseif($ac_no == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter account number'], REST_Controller::HTTP_OK);
        }
        elseif($emg_person == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please emergency person name'], REST_Controller::HTTP_OK);
        } 
        elseif($emg_contact == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter contact number'], REST_Controller::HTTP_OK);
        }
        elseif($alternate_emg_num == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter alternate emergency number'], REST_Controller::HTTP_OK);
        }
        elseif($email == $getemail)
        {
         $this->response(['responsecode' => 0,'response' => 'Email Already Exist'], REST_Controller::HTTP_OK);
        }
        elseif($mobile_no == $getnumber)
        {
         $this->response(['responsecode' => 0,'response' => 'Mobile Number Already Exist'], REST_Controller::HTTP_OK);
        }

          /*  Image Saving code */  
        else
        {
          $image = '';
          if($_FILES['user_image']['name'])
          {
            $ext = end(explode(".", $_FILES['user_image']['name']));
            $imgname = $ext[0].substr(md5(microtime()),0,8).'.'.$ext; 
            if(move_uploaded_file($_FILES["user_image"]["tmp_name"],"uploads/driver/".$imgname))
            {
              copy("uploads/driver/".$imgname,"uploads/driver/resize/".$imgname);
              $image = $imgname;
            }
          }

          $image1 = '';
          if($_FILES['driving_license']['name'])
          {   
            $ext1 = end(explode(".", $_FILES['driving_license']['name']));
            $imgname1 = $ext1[0].substr(md5(microtime()),0,8).'.'.$ext1;
            if(move_uploaded_file($_FILES["driving_license"]["tmp_name"],"uploads/driver/".$imgname1))
            {
                copy("uploads/driver/".$imgname1,"uploads/driver/resize/".$imgname1);
                $image1 = $imgname1;
            }
          }
            
          $image2 = '';
          if($_FILES['vocational_li']['name'])
          {
            $ext2 = end(explode(".", $_FILES['vocational_li']['name']));
            $imgname2 = $ext2[0].substr(md5(microtime()),0,8).'.'.$ext2;
            if(move_uploaded_file($_FILES["vocational_li"]["tmp_name"],"uploads/driver/".$imgname2))
            {
              copy("uploads/driver/".$imgname2,"uploads/driver/resize/".$imgname2);
              $image2 = $imgname2;
            }
          }   
          
          $image3 = '';
          if($_FILES['insurance']['name'])
          {
            $ext3 = end(explode(".", $_FILES['insurance']['name']));
            $imgname3 = $ext3[0].substr(md5(microtime()),0,8).'.'.$ext3;
            if(move_uploaded_file($_FILES["insurance"]["tmp_name"],"uploads/driver/".$imgname3))
            {
              copy("uploads/driver/".$imgname3,"uploads/driver/resize/".$imgname3);
              $image3 = $imgname3;
            }
          }
            
          $image4 = '';
          if($_FILES['permit']['name'])
          {
            $ext4 = end(explode(".", $_FILES['permit']['name']));
            $imgname4 = $ext4[0].substr(md5(microtime()),0,8).'.'.$ext4;
            if(move_uploaded_file($_FILES["permit"]["tmp_name"],"uploads/driver/".$imgname4))
            {
              copy("uploads/driver/".$imgname4,"uploads/driver/resize/".$imgname4);
              $image4 = $imgname4;
            }
          }

       $array1 = array(
              'fname'                     =>$fname,
              'lname'                     =>$lname,
              'dispatch_id'               =>$fleet,
              'country'                   =>$country,
              'currency'                  =>$currency,
              'state'                     =>$state,
              'postcode'                  =>$postcode,
              'address1'                  =>$address1,
              'email'                     =>$email,
              'mobile'                    =>$mobile_no,
              'driving_license_expiry'    =>$driving_license_expiry,
              'vocational_license_expiry' =>$vocational_license_expiry,
              'plate_no'                  =>$plate_no,
              'carcategory'               =>$vehicle_type,
              'vehicle_type'              =>$vehicle_category,
              'year_of_reg'               =>$year_of_reg,
              'vehicle_insurance_expiry'  =>$vehicle_insurance_expiry, 
              'vehicle_permit_expiry'     =>$vehicle_permit_expiry,
              'bank_name'                 =>$bank_name,
              'ac_name'                   =>$ac_name,
              'ac_no'                     =>$ac_no,
              'emg_person'          =>$emg_person,
              'emg_contact'         =>$emg_contact,
              'alt_emg_contact'       =>$alternate_emg_num,
              'varification'              =>0,
              'status'                    =>0,
              'activestatus'              =>0          
              );         
    if(!empty($image))
          {
           $array1['profile_photo']=$image;
          }
            if(!empty($image1))
            {
            $array1['lc_photo']=$image1;
            }
            if(!empty($image2))
            {
            $array1['vocational_license']=$image2;
            }  
             if(!empty($image3))
            {
                $array1['insurance_photo']=$image3;
            }
             if(!empty($image4))
            {
                $array1['vehicle_permit']=$image4;
            } 
      
      $insert1 = $this->service_m->insert('driver_details', $array1);
      $check = $this->db->last_query();

      if($insert1)
      {
        $display_name = $fname.' '.$lname; 
        $array = array(
                        'device_id'       => $device_id,
                        'device_type'     => $device_type,
                        'user_name'       => $email,
                        'password'        => $mobile_no,
                        'email'           => $email,
                        'display_name'    => $display_name,
                        'usertype_id'     => 4,
                        'org_id'          => $insert1,
                        'mobile_no'      => $mobile_no
        );

        $insert = $this->service_m->insert('members',$array);
      } 
  
          if(!empty($insert))
          {

            //  Mail sending //
            $this->load->library('email');

            $config['mailtype'] ='html';

            $this->email->initialize($config);

            $this->email->from('info@thecandytech.com', 'The Candy Tech');
            $this->email->to($email);

            $this->email->subject('Registered With The Candy Tech');
            $data = array(
             'name' => $display_name,
             'email' => $email,
             'password' => $mobile_no,
                 );
             $body = $this->load->view('mail',$data,TRUE);
            $this->email->message($body);   
            $this->email->send();
          
          $this->response([
                'responsecode' => 1,
                'response' => 'Your account has been created successfully.',
              ], REST_Controller::HTTP_OK);
          }
          else
          {
            $this->response([
                'responsecode' => 0,
                'response' => 'Some problems occurred, please try again.',
              ], REST_Controller::HTTP_OK);
          } 
       }
   }
      

      public function driver_rating_feedback_get($id)
         {
          $myearning=$this->service_m->getRows("SELECT dr.*,m.display_name FROM driverrating dr,members m WHERE dr.user_id =m.id AND  dr.driver_id = '".$id."' ");
          //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
          if($myearning != NULL){ $myearn = $myearning; }else{$myearn =[];}
        if($id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 1,'myfeedback'=>$myearn,'response' => 'Success!'], REST_Controller::HTTP_OK);
                }
        }
        
         public function complaint_post()
         {
           $user_id =$this->input->post('user_id');
           $driver_id = $this->input->post('driver_id');
  
         $feedback = $this->input->post('feedback');
         $rideid = $this->input->post('rideid');
       
           if($user_id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter user id'], REST_Controller::HTTP_OK);
        }
        elseif($driver_id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
        }
        else
        {
          //$checkid=$this->service_m->getRow("select m.id,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.carcategory from members m, driver_details dd  where dd.id=m.org_id and m.id='".$id."' and m.usertype_id=4 ");
          //print_r($checkid);
                $array = array(
                     'user_id'   =>$user_id,
                     'driver_id' =>$driver_id,
                     'type'      =>1,
                     'user_type_id' =>4,
                     'comment'   =>$feedback,
                     'booking_id'=>$rideid
                      );
                $insert = $this->service_m->insert('driverrating',$array);
       if($insert!='')
        {
        $array1 = array(
                    'page_id'      =>'9',
                    'insert_id'    =>$insert,
                    'status'       =>'0',
                    'member_id'    =>$this->input->post('user_id'),
                    'user_type'    =>5
                    );
                    
              $inserted = $this->service_m->insert('admin_notification', $array1);
        $this->response(['responsecode' => 1,'response' => 'Complaint registered sucessfully'], REST_Controller::HTTP_OK);
        }
          }
           }

        public function feedback_trip_post()
         {
         $user_id =$this->input->post('user_id');
         $driver_id = $this->input->post('driver_id');
         $rate = $this->input->post('rate');
         $feedback = $this->input->post('feedback');
         $rideid = $this->input->post('rideid');
       
        if($user_id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter user id'], REST_Controller::HTTP_OK);
        }
        elseif($driver_id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
        }
        elseif($rate=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter rate'], REST_Controller::HTTP_OK);
        }
        else
        {
          //$checkid=$this->service_m->getRow("select m.id,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.carcategory from members m, driver_details dd  where dd.id=m.org_id and m.id='".$id."' and m.usertype_id=4 ");
          //print_r($checkid);
          $array = array(
                     'user_id'     =>$user_id,
                     'driver_id'   =>$driver_id,
                     'rate'        =>$rate,
                     'type'        =>2,
                     'user_type_id'=>4,
                     'comment'     =>$feedback,
                     'booking_id'  =>$rideid
                     );
        $insert = $this->service_m->insert('driverrating',$array);
      if($insert!='')
        {
        $array1 = array(
                    'page_id'      =>'13',
                    'insert_id'    =>$insert,
                    'status'       =>'0',
                    'member_id'    =>$this->input->post('user_id'),
                    'user_type'    =>4
                    );
                    
        $inserted = $this->service_m->insert('admin_notification', $array1);
        $this->response(['responsecode' => 1,'response' => 'Thank you for feedback!'], REST_Controller::HTTP_OK);
        }
       }
      }
public function help_trip_post()
         {
         $user_id =$this->input->post('user_id');
         $driver_email =$this->input->post('email');
         $comment = $this->input->post('comment');
         $rideid = $this->input->post('booking_id');
         $topic_id = $this->input->post('topic_id');
       
      if($user_id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter user id'], REST_Controller::HTTP_OK);
        }
        elseif($rideid=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter valid booking id'], REST_Controller::HTTP_OK);
        }
          elseif($comment=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter comment'], REST_Controller::HTTP_OK);
        }
        else
        {
          //$checkid=$this->service_m->getRow("select m.id,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.carcategory from members m, driver_details dd  where dd.id=m.org_id and m.id='".$id."' and m.usertype_id=4 ");
          //print_r($checkid);
                $array = array(
                     'driver_id'  =>$user_id,
                     'driver_email'=>$driver_email,
                     'topic_id'   =>$topic_id,
                     'comment'    =>$comment,
                     'booking_id' =>$rideid
                      );
                $insert = $this->service_m->insert('help_store',$array);
                if($insert!='')
        {
      // $array1 = array(
    //                 'page_id'      =>'9',
    //                 'insert_id'      =>$insert,
    //                 'status'       =>'0',
    //                 'member_id'     =>$this->input->post('user_id'),
    //                 'user_type'     =>5
    //                 );
                    
    //       $insert = $this->service_m->insert('admin_notification', $array1);
        $this->response(['responsecode' => 1,'response' => 'Thank you for comment!'], REST_Controller::HTTP_OK);
        }
          }
           }
     
       // public function driverblock_get($id)
       // { 
       // $array = array(
       //     'status' =>'2'
       //       );
       //  $where = array('id'=>$id);
       //  $update = $this->service_m->update('driver_details', $where, $array); 
       //  if($update)
       //    {
       //  $this->response([
       //          'responsecode' => 1,
       //          'STATUS' => 2,
       //          'response' => 'Bolcked successfully.'], REST_Controller::HTTP_OK);
       //    }
       //   else
       //    {
       //    $this->response([
       //          'responsecode' => 0,
       //          'response' => $this->db->last_query().'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
       //    } 
       //    }

            public function driverquezone_get($id,$quezoneid)
       { 
       $array = array(
           'activestatus' =>'5',
           'quezone_id'   =>$quezoneid  
             );
        $where = array('id'=>$id);
        $update = $this->service_m->update('driver_details', $where, $array); 
        if($update)
          {
            $vcategory = $this->service_m->getRow('select carcategory,vehicle_type from driver_details where id='.$id.'');
            $quenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');
$sqlall = $quenumbers;
            if($quenumbers==0)
            {

            $getcount = $this->service_m->getVal('select count(*) from driver_details where activestatus = 5 and quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

            $updatequenumber = $this->db->query('update driver_details set que_number = '.$getcount.' where id = '.$id.'');
            // $updatequenumber = $this->db->query('UPDATE driver_details SET que_number =8 WHERE driver_details.id = 59');

            $getallquenumber = $this->service_m->getRows('SELECT que_number,id FROM driver_details where quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');
            $sqlall = $this->db->last_query();

            $singlequenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

        foreach($getallquenumber as $getallquenumberi)
        {
          if($getallquenumberi->que_number == $singlequenumbers)
          {
            if($getallquenumberi->id != $id)
            {
             $getcount = $this->service_m->getVal('select count(*) from driver_details where activestatus = 5 and quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

             $updatequenumber = $this->db->query('update driver_details set que_number = '.$getcount.' where id = '.$id.'');
            }
          }
        }
            
            $sql = $this->db->last_query();
            }
            else
            {
              $getallquenumber = $this->service_m->getRows('SELECT que_number,id FROM driver_details where quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');
            $sqlall = $this->db->last_query();

            $singlequenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

        foreach($getallquenumber as $getallquenumberi)
        {
          if($getallquenumberi->que_number == $singlequenumbers)
          {
            if($getallquenumberi->id != $id)
            {
             $getcount = $this->service_m->getVal('select count(*) from driver_details where activestatus = 5 and quezone_id='.$quezoneid.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

             $updatequenumber = $this->db->query('update driver_details set que_number = '.$getcount.' where id = '.$id.'');
            }
          }
        }
            }
        $this->response([
                'responsecode' => 1,
                'status' => 5,
                'response' => 'Bolcked successfully.','query'=>$sql,'sqlall'=>$sqlall], REST_Controller::HTTP_OK);
          }
         else
          {
          $this->response([
                'responsecode' => 0,
                'response' => $this->db->last_query().'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
          } 
          }

          public function updatequezone_get($id)
       { 

    $vcategory = $this->service_m->getRow('select carcategory,vehicle_type,quezone_id from driver_details where id='.$id.'');

        $quenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

       $getactivestatus = $this->service_m->getVal('select activestatus from driver_details where id='.$id.'');
       
        if($quenumbers)
          {

            if($getactivestatus==5)
            {

            $setquenumber = $this->db->query('update driver_details set que_number = 0 where id='.$id.'');

            $underquenumber = $this->service_m->getRows('select id,que_number from driver_details where activestatus= 5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.' and que_number>'.$quenumbers.'');

            $sql = $this->db->last_query();
            
        foreach($underquenumber as $underquenumberi)
        {
          if($underquenumberi->que_number!=1)
          {
          $getnewnumber  = $underquenumberi->que_number - 1;
          }
          else
          {
            $getnewnumber = $underquenumberi->que_number; 
          }
          $updatequenumber = $this->db->query('update driver_details set que_number ='.$getnewnumber.'  where id='.$underquenumberi->id.'');

        }

    }


         $array = array(
           'activestatus' =>'1'
             );
        $where = array('id'=>$id);
        $update = $this->service_m->update('driver_details', $where, $array);
        $this->response([
                'responsecode' => 1,
                'status' => 1,
                'response' => 'Bolcked successfully.','query' => $sql], REST_Controller::HTTP_OK);
          }
         else
          {
          $this->response([
                'responsecode' => 0,
                'response' => $this->db->last_query().'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
          } 
          }

          public function rejectquezoneride_get($id,$ride_id,$passenger_id)
       { 

        $vcategory = $this->service_m->getRow('select carcategory,vehicle_type,quezone_id from driver_details where id='.$id.'');

        $quenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');


            $underquenumber = $this->service_m->getRows('select id,que_number from driver_details where activestatus= 5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.' and que_number>'.$quenumbers.'');

            //$sql = $this->db->last_query();
            
        foreach($underquenumber as $underquenumberi)
        {
          if($underquenumberi->que_number!=1)
          {
          $getnewnumber  = $underquenumberi->que_number - 1;
          }
          else
          {
            $getnewnumber = $underquenumberi->que_number; 
          }
          $updatequenumber = $this->db->query('update driver_details set que_number ='.$getnewnumber.'  where id='.$underquenumberi->id.'');

        }
        
        $newcount = $this->service_m->getVal('select count(*) from driver_details where activestatus=5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');
        
        $setquenumber = $this->db->query('update driver_details set que_number ='.$newcount.'  where id='.$id.'');

         if($setquenumber)
          {
        


         $device_detail = $this->service_m->getRow('SELECT m.device_id,m.device_type FROM members m, driver_details dd WHERE m.org_id = dd.id and m.usertype_id=4 and dd.activestatus=5 and dd.quezone_id='.$vcategory->quezone_id.' and dd.carcategory='.$vcategory->carcategory.' and dd.vehicle_type='.$vcategory->vehicle_type.' and dd.que_number=1');

         $sql = $this->db->last_query();

        $device_id = $device_detail->device_id;
        $device_type = $device_detail->device_type;

        

        $ride_detail = $this->service_m->getRow('select book_type,book_date,pickupaddress,user_id,ride_status from ride where id='.$ride_id.'');

        if($ride_detail->user_id == 0)
        {
          $passenger_name = $this->admin->getVal("select fullname from ride where id='".$ride_id."'");
        }
        else
        {
          $userdetail=$this->service_m->getRow('SELECT * FROM members WHERE id = "'.$passenger_id.'" and usertype_id=5');
          $passenger_name = $userdetail->display_name;
        }

        $ride_status = $ride_detail->ride_status;

        if($ride_detail->ride_status == 0)
        {
        $fcmMsg = array(
   'body' => 'You have recieved que booking_'.$ride_id.'_'.$ride_detail->book_type.'_'.$ride_detail->book_date.'_'.$ride_detail->pickupaddress.'_'.$passenger_name,
   'title' => '1cabbi Booking',
   'sound' => "default",
   'color' => "#203E78",
   'category' => "oneCabbiDriver" 
     );
   
if($device_type == 1) 
{
$fcmFields = array(
  'to'   => $device_id,
  'data' => $fcmMsg
    );
}else{
  $fcmFields = array(
  'to'   => $device_id,
  'notification' => $fcmMsg,
  'priority'=>'high'
    );
}

$headers = array(
  'Authorization: key='.API_ACCESS_KEY,
  'Content-Type: application/json'
     );
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
$result = curl_exec($ch );
curl_close( $ch );
}
$this->response([
                'responsecode' => 1,
                'status' => 1,
                'ridestatus' => $ride_status,
                'response' => 'Bolcked successfully.','query' => $sql], REST_Controller::HTTP_OK);
          
        }
        else
          {
          $this->response([
                'responsecode' => 0,
                'ridestatus' => $ride_status,
                'response' => $this->db->last_query().'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
          } 
        }

        public function acceptquezoneride_get($id)
       { 

        $vcategory = $this->service_m->getRow('select carcategory,vehicle_type,quezone_id from driver_details where id='.$id.'');

        $quenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

          $underquenumber = $this->service_m->getRows('select id,que_number from driver_details where activestatus= 5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.' and que_number>'.$quenumbers.'');

            //$sql = $this->db->last_query();
            
        foreach($underquenumber as $underquenumberi)
        {
          if($underquenumberi->que_number!=1)
          {
          $getnewnumber  = $underquenumberi->que_number - 1;
          }
          else
          {
            $getnewnumber = $underquenumberi->que_number; 
          }
          $updatequenumber = $this->db->query('update driver_details set que_number ='.$getnewnumber.'  where id='.$underquenumberi->id.'');

        }
        
        $newcount = $this->service_m->getVal('select count(*) from driver_details where activestatus=5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');
        
        $setquenumber = $this->db->query('update driver_details set que_number ='.$newcount.'  where id='.$id.'');

         if($setquenumber)
          {
            $this->response([
                'responsecode' => 1,
                'status' => 1,
                'response' => 'Bolcked successfully.','query' => $sql], REST_Controller::HTTP_OK);
          
        }
        else
          {
          $this->response([
                'responsecode' => 0,
                'response' => $this->db->last_query().'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
          } 
        }

      public function driverprofile_get($id)
      {
      $getdata=$this->service_m->getRow("select m.*,dd.fname,dd.lname,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.nric_photo,dd.lc_photo,dd.vocational_license,dd.ecard,dd.insurance_photo,dd.vehicle_permit,dd.carcategory,dd.bank_name,dd.ac_type,dd.ac_no,dd.vehicle_insurance_expiry,dd.vehicle_permit_expiry,dd.driving_license_expiry,dd.vocational_license_expiry,dd.ecard_expiry_date from members m, driver_details dd where dd.id=m.org_id and m.org_id='".$id."' and m.usertype_id=4 "); 
      $rating=$this->service_m->getVal('select  CAST(AVG(rate) AS DECIMAL(10,2))  from driverrating where driver_id="'.$id.'" order by id desc');
      
     if(!empty($getdata))
        { 
        if($rating != NULL){$myearn = $rating; }else{$myearn =5.00;}
        $this->response(['responsecode' => 1,"BASEURL" => base_url().'uploads/driver/','response' => $getdata,'RATING' => $myearn], REST_Controller::HTTP_OK);
        }
     else
        {
        $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
        }
       } 
      
      public function emergency_number_get($id)
         {
      $getdata=$this->service_m->getRows("SELECT * FROM emergency_number WHERE country_id = '".$id."' and driver_publish_status = 1"); 
      
      if(!empty($getdata))
          {
          $this->response(['responsecode' => 1,'response' => $getdata], REST_Controller::HTTP_OK);
          }
      else{
          $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
          }
          }  
       
       // Change Status of Driver //
       public function change_status_post()
        {
          $getdetail = file_get_contents("php://input");
          $getdetails = json_decode($getdetail);
          $driver_id = $getdetails->{'driver_id'};
          $new_status = $getdetails->{'new_status'};
       
        if($driver_id=='')
          {
          $this->response(['responsecode' => 0,'response' => 'Please enter Driver id'], REST_Controller::HTTP_OK);
          }
          elseif($new_status== '')
          {
          $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
          }
          else
          {

          //    $vcategory = $this->service_m->getRow('select carcategory,vehicle_type,quezone_id from driver_details where id='.$id.'');

            // $quenumbers = $this->service_m->getVal('select que_number from driver_details where id='.$id.' and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');

              // $underquenumber = $this->service_m->getRows('select id,que_number from driver_details where activestatus= 5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.' and que_number>'.$quenumbers.'');

              //$sql = $this->db->last_query();
            
            // foreach($underquenumber as $underquenumberi)
            // {
            //   if($underquenumberi->que_number!=1)
            //   {
            //   $getnewnumber  = $underquenumberi->que_number - 1;
            //   }
            //   else
            //   {
            //     $getnewnumber = $underquenumberi->que_number; 
            //   }
            //   $updatequenumber = $this->db->query('update driver_details set que_number ='.$getnewnumber.'  where id='.$underquenumberi->id.'');

            // }
        
            // $newcount = $this->service_m->getVal('select count(*) from driver_details where activestatus=5 and quezone_id='.$vcategory->quezone_id.' and carcategory='.$vcategory->carcategory.' and vehicle_type='.$vcategory->vehicle_type.'');
        
            // $setquenumber = $this->db->query('update driver_details set que_number ='.$newcount.'  where id='.$id.'');
            //$checkid=$this->service_m->getRow("select m.id,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.carcategory from members m, driver_details dd  where dd.id=m.org_id and m.id='".$id."' and m.usertype_id=4 ");
            //print_r($checkid);
            $arr    = array('activestatus'=>$new_status);
            $where  = array('id'=>$driver_id);
            $update = $this->service_m->update('driver_details', $where, $arr);
            if($update)
            {    
              $this->response(['responsecode' => 1,'activestatus'=>$new_status,'response' => 'Success!'], REST_Controller::HTTP_OK);
            }
            else
            {
              $this->response(['responsecode' => 0,'activestatus'=>$new_status,'response' => 'fail'], REST_Controller::HTTP_OK);
            }
            }
          }
        /*
        public function my_trip_post()
         {
         $id =$this->input->post('user_id');
         $instantbooking=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 1 AND ride_status = 0");
         $prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");


        
         if($instantbooking != NULL){$instantbook = $instantbooking; }else{$instantbook =[];}
         
          if($prebook != NULL){$prebooked = $prebook; }else{$prebooked =[];}
         
        if($id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 1,'instantbooking'=>$instantbook,'prebook'=>$prebooked,'response' => 'Success!'], REST_Controller::HTTP_OK);
                }
        }*/
      // This API run when ride booked //
      public function get_trip_post()
      {
        $getdetail = file_get_contents("php://input");
        $getdetails = json_decode($getdetail);
        $driver_id = trim($getdetails->{'driver_id'});
        $fleet_id = $getdetails->{'fleet_id'};
        $reassignstatus = $this->admin->getRow('select reassign_status,ride_status from ride where dispatch_id='.$fleet_id.' and driver_id='.$driver_id.' ORDER BY id DESC LIMIT 1');

         if($reassignstatus->reassign_status == 1 && $reassignstatus->ride_status == 9)
         {

          ///////// REASSIGN RIDE FUNCTION /////////////

          $car_category = $this->admin->getRow('SELECT dd.carcategory,dd.vehicle_type FROM members m, driver_details dd WHERE m.org_id = dd.id and m.usertype_id =  4 and  dd.id = '.$driver_id.'');

          $instantbooking = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$driver_id."' AND book_type = 1  AND ride_status = 9 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."' AND reassign_status = 1");

          $queinstantbooking = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$driver_id."' AND book_type = 3  AND ride_status = 9 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."' AND reassign_status = 1");

          $prebook = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$driver_id."' AND book_type = 2 AND ride_status = 9 and pre_status = 2 and reassign_status = 1");

          $autoinstantbooking = $this->service_m->getRows("SELECT r.* FROM ride r, members m, driver_details dd WHERE r.driver_id = ".$driver_id." AND r.book_type = 1 AND r.ride_status = 9 and r.reassign_status = 1 and r.dispatch_id='".$fleet_id."' AND m.org_id = dd.id and dd.id='".$driver_id."' and dd.activestatus = 1 and dd.status=0 and  dd.dispatch_id = r.dispatch_id and  dd.carcategory = '".$car_category->carcategory."' and dd.vehicle_type = '".$car_category->vehicle_type."'");

          $autoprebook = $this->service_m->getRows("SELECT r.* FROM ride r WHERE r.driver_id =".$driver_id." AND r.book_type = 2 AND r.ride_status = 9 AND r.reassign_status = 1 and r.dispatch_id='".$fleet_id."' and pre_status = 2 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."' ORDER BY r.id DESC");
        
          
          if($prebook != NULL)
            {$prebooked = $prebook; }
          else{$prebooked =[];}
          if($queinstantbooking != NULL){$quebooked = $queinstantbooking; }else{$quebooked =[];}
          if($autoinstantbooking != NULL){$autoinstantbooked = $autoinstantbooking; }elseif($queauto != NULL){$autoinstantbooked = $queauto; }else{$autoinstantbooked =[];}
          if($autoprebook != NULL){$autoprebooked = $autoprebook; }else{$autoprebooked =[];}
          if($driver_id=='')
          {
            $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
          }
          else if($fleet_id=='')
          {
            $this->response(['responsecode' => 0,'response' => 'Please enter fleet id'], REST_Controller::HTTP_OK);
          }
          else
          {
            if(($autoinstantbooking != NULL) || ($autoprebook != NULL))
            {
            $this->response(['responsecode' => 1,'instantautobook'=>$autoinstantbooked,'preautobook'=>$autoprebooked,'response' => 'Success!'], REST_Controller::HTTP_OK);
            }
            else
            {
              $this->response(['responsecode' => 0,'instantautobook'=>$autoinstantbooked,'preautobook'=>$autoprebooked,'response' => 'fail'], REST_Controller::HTTP_OK);
            }


          }

         }
       else
       {

        //// NORMAL BOOKING FUNCTION ///

        $ride_id = $this->service_m->getVal("SELECT id FROM ride WHERE dispatch_id = ".$fleet_id." ORDER BY id DESC LIMIT 1");

        $car_category = $this->admin->getRow('SELECT r.car_category,r.vichle_type FROM ride r WHERE id='.$ride_id.'');

        $instantbooking = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$driver_id."' AND book_type = 1  AND ride_status = 0 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."'");

        $queinstantbooking = $this->service_m->getRows("SELECT * FROM ride_pay WHERE driver_id = '".$driver_id."' AND book_type = 3  AND ride_status = 0 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."'");

        $prebook = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$driver_id."' AND book_type = 2 AND ride_status = 0 and pre_status = 2");

        $autoinstantbooking = $this->service_m->getRows("SELECT r.* ,111.111 * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(r.pickuplat)) * COS(RADIANS(dd.driver_lat)) * COS(RADIANS(dd.driver_long - r.pickuplong)) + SIN(RADIANS(r.pickuplat)) * SIN(RADIANS(dd.driver_lat))))) AS distance FROM ride r,members m, driver_details dd WHERE r.driver_id = 0 AND r.book_type = 1 AND r.ride_status = 0 and r.dispatch_id='".$fleet_id."' AND m.org_id = dd.id and dd.id = '".$driver_id."' and dd.activestatus = 1 and dd.status=0 and  dd.dispatch_id = r.dispatch_id and  dd.carcategory = '".$car_category->car_category."' and dd.vehicle_type = '".$car_category->vichle_type."' GROUP BY r.id HAVING distance < 8 ORDER BY distance");

        // $getquezoneid = $this->service_m->getVal(' SELECT quezone_id from driver_details where id='.$driver_id.'');

        $queauto = $this->service_m->getRows("SELECT r.* , ( 3959 * acos( cos( radians(r.pickuplat) ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians(r.pickuplong) ) + sin( radians(r.pickuplat) ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM ride_pay r,members m, driver_details dd WHERE r.dispatch_id = '".$fleet_id."' AND r.book_type = 3 AND r.ride_status = 0 AND r.pre_status=0 AND r.ride_id != 0 AND m.org_id = dd.id and dd.id='".$driver_id."' and dd.activestatus=5 and dd.status=0 and dd.carcategory = '".$car_category->car_category."' and dd.vehicle_type = '".$car_category->vichle_type."' GROUP BY r.id HAVING distance < 8 ORDER BY distance");

        $autoprebook = $this->service_m->getRows("SELECT r.* FROM ride r WHERE r.driver_id = 0 AND r.book_type = 2 AND r.ride_status = 0 and r.dispatch_id='".$fleet_id."' and pre_status = 2 AND car_category='".$car_category->car_category."' AND vichle_type='".$car_category->vichle_type."' ORDER BY r.id DESC");
        
        if($instantbooking != NULL){$instantbook = $instantbooking; }
        else{$instantbook =[];}
        if($prebook != NULL){$prebooked = $prebook; }else{$prebooked =[];}
        if($queinstantbooking != NULL){$quebooked = $queinstantbooking; }else{$quebooked =[];}
        if($autoinstantbooking != NULL){$autoinstantbooked = $autoinstantbooking; }elseif($queauto != NULL){$autoinstantbooked = $queauto; }else{$autoinstantbooked =[];}
        if($autoprebook != NULL){$autoprebooked = $autoprebook; }else{$autoprebooked =[];}
        if($driver_id=='')
        {
          $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
        }
        else if($fleet_id=='')
        {
          $this->response(['responsecode' => 0,'response' => 'Please enter fleet id'], REST_Controller::HTTP_OK);
        }
        else
        {
          $this->response(['responsecode' => 1,'instantautobook'=>$autoinstantbooked,'preautobook'=>$autoprebooked,'response' => 'Success!'], REST_Controller::HTTP_OK);
        }
      }
    } 

public function my_trip_detail_post()
      {
      $id =$this->input->post('user_id');
      $rid =$this->input->post('ride_id');
      $instantbooking=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND id = '".$rid."' AND book_type = 1 AND ride_status  IN (1,2,3,4,9)");
         
      if($instantbooking != NULL){$instantbook = $instantbooking; }
      else{$instantbook =[];}
      if($id=='')
      {
      $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
      }
      else
      {
      $this->response(['responsecode' => 1,'instantbooking'=>$instantbook,'response' => 'Success!'], REST_Controller::HTTP_OK);
      }
      } 


      public function reassign_trip_detail_post()
      {
      
      $rid =$this->input->post('ride_id');
      $instantbooking=$this->service_m->getRows("SELECT * FROM ride WHERE id = '".$rid."' AND book_type = 1 AND ride_status  IN (1,2,3,4,9)");
         
      if($instantbooking != NULL){$instantbook = $instantbooking; }
      else{$instantbook =[];}
      if($rid=='')
      {
      $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
      }
      else
      {
      $this->response(['responsecode' => 1,'instantbooking'=>$instantbook,'response' => 'Success!'], REST_Controller::HTTP_OK);
      }
      } 

   // Prebooking Jobs coming API //
  public function prebooking_jobs_post()
    {
      $getdetail = file_get_contents("php://input");
      $getdetails = json_decode($getdetail);
      $id = $getdetails->{'id'};
        $dispatch_id = $getdetails->{'fleet_id'};
        
        $car_category = $this->admin->getRow('SELECT dd.carcategory,dd.vehicle_type FROM members m, driver_details dd WHERE m.org_id = dd.id and m.usertype_id =  4 and  dd.id = '.$id.'');  
      
      $prebook = $this->service_m->getRows("SELECT * FROM ride_pay WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."'  AND pre_status = 0"); 
    
        $autoprebook = $this->service_m->getRows("SELECT * FROM ride_pay WHERE driver_id = 0 AND book_type = 2 AND ride_status = 0 and pre_status = 0 AND car_category='".$car_category->carcategory."' AND vichle_type='".$car_category->vehicle_type."' and dispatch_id='".$dispatch_id."'  ORDER BY id DESC");
    
        if($prebook != NULL)
        {
          $prebooked = $prebook; 
        }
        else
        {
          $prebooked =[];
        }
        if($autoprebook != NULL)
        {
          foreach($autoprebook as $autoprebooki)
          {

            $booking_date = date('d m Y  H:i:s',strtotime($autoprebooki->book_datetime));
            $prebookingdate = date('d m Y  H:i:s',strtotime($autoprebooki->book_date));
            $prebookingdata[] = array(
                          'fareid' => $autoprebooki->fareid,
                          'totalfare' => $autoprebooki->totalfare,
                          'agentfees' => $autoprebooki->clientfees,
                          'fleetfees' => $autoprebooki->dispatchfees,
                          'adminfees' => $autoprebooki->adminfees,
                          'prebookingfees' => $autoprebooki->prebooking_fee,
                          'gatewayfees' => $autoprebooki->gateway_fee,
                          'driverfees' => $autoprebooki->driverfees,
                          'bookingdate' => $booking_date,
                          'prebookingdate' => $prebookingdate,
                          'bookingid' => $autoprebooki->ride_id,
                          'fullname' => $autoprebooki->fullname,
                          'mobile' => $autoprebooki->mobile,
                          'pickupaddress' => $autoprebooki->pickupaddress,
                          'dropaddress' => $autoprebooki->dropaddress,
                          'remark' => $autoprebooki->note,
                          'booktype' => $autoprebooki->book_type,
                          'carcategory' => $autoprebooki->car_category
                                );
          }
        }
        else
        {
          $prebookingdata = [];
        }
        if($id=='')
        {
          $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
        }
        else
        {
          $this->response(['responsecode' => 1,'prebook'=>$prebooked,'prebookingdata'=>$prebookingdata,'response' => 'Success!'], REST_Controller::HTTP_OK);
        }
      } 

   // Ride accept after trip getting //   
   public function ride_status_post()
       {
          $getdetail = file_get_contents("php://input");
          $getdetails = json_decode($getdetail);
          $ride_id = $getdetails->{'ride_id'};
          $latitude = $getdetails->{'latitude'};
          $longitude = $getdetails->{'longitude'};
          $status = $getdetails->{'status_type'};
          $book_type = $getdetails->{'book_type'};
          $driver_id = $getdetails->{'driver_id'};
          $start_time = $getdetails->{'start_time'};
          $driver_arrivaltime = $getdetails->{'driver_arrivaltime'};
          $auto_booking = $getdetails->{'auto_booking'};
          $country_id = $getdetails->{'country_id'};

          $ride_status = $this->admin->getVal('SELECT ride_status FROM ride  WHERE  id = '.$ride_id.'');
           
        if($ride_id == '')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status == '')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        elseif(($ride_status == 1) && ($status == 1))
        {
           $this->response(['responsecode' => 1,'ride_status' => $ride_status,'response' => 'This Ride Already Assigned Other Driver'], REST_Controller::HTTP_OK);
        }
        elseif(($ride_status == 2) && ($status == 1))
        {
           $this->response(['responsecode' => 1,'ride_status' => $ride_status,'response' => 'This Ride Already Assigned Other Driver'], REST_Controller::HTTP_OK);
        }
        elseif(($ride_status == 3) && ($status == 1))
        {
           $this->response(['responsecode' => 1,'ride_status' => $ride_status,'response' => 'This Ride Already Assigned Other Driver'], REST_Controller::HTTP_OK);
        }
        elseif(($ride_status == 4) && ($status == 1))
        {
           $this->response(['responsecode' => 1,'ride_status' => $ride_status,'response' => 'This Ride Already Assigned Other Driver'], REST_Controller::HTTP_OK);
        }
        else
        {
           if($status == 1)
         {
            $device_id = $this->service_m->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$ride_id.''); 
        $fcmMsg = array(
                    'body' => 'Your booking accepted',
                    'title' => 'The Candy Booking',
                    'sound' => "default",
                    'color' => "#203E78" 
                  );
        if($device_id->device_type == 1)
        { 
          $fcmFields = array(
                              'to'    => $device_id->token_id,
                              'data'  => $fcmMsg
                  );
        }
        else
        {
          $fcmFields = array(
                              'to'            => $device_id->token_id,
                              'notification'  => $fcmMsg,
                              'priority'      => 'high'  
          );
        }  

        $headers = array(
                    'Authorization: key='.API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
 
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ));
        $result = curl_exec($ch);
        $results = json_decode($result);
        $notiarr = array(
                          'ride_id' => $ride_id,
                          'status'  => $results->{'success'} 
                        );
        $notiinsert = $this->service_m->insert('notification_check',$notiarr);
        curl_close( $ch );


            if($auto_booking == 1) 
            {  
                  $arr = array(
                            'ride_status'=>$status,
                            'driver_id'=>$driver_id,
                            'driver_lat'=>$latitude,
                            'driver_long'=>$longitude,
                            'driver_eta'=>0,
                            'driver_arrivaltime'=>$driver_arrivaltime
                          );
              }
              else
              {
                $arr = array(
                            'ride_status'=>$status,
                            'driver_lat'=>$latitude,
                            'driver_long'=>$longitude,
                            'driver_arrivaltime'=>$driver_arrivaltime
                          );
                }
                $array1 = array(
                            'page_id'      => '15',
                            'insert_id'    => $ride_id,
                            'status'       => '1',
                            'member_id'    => $driver_id,
                            'user_type'    => 4
                            );
                    
            $insert = $this->service_m->insert('admin_notification', $array1);         
            }
            else if( $status == 3) 
            {
              $arr= array(
                          'ride_status'=>$status,
                          'driver_lat'=>$latitude,
                          'driver_long'=>$longitude,
                          'start_time'=>$start_time,
                          
                        );
              $array1 = array(
                            'page_id'      => '16',
                            'insert_id'    => $ride_id,
                            'status'       => '1',
                            'member_id'    => $driver_id,
                            'user_type'    => 4
                            );
                    
            $insert = $this->service_m->insert('admin_notification', $array1);     
            }
            else if( $status == 5) 
            {
              $arr= array(
                        'ride_status' =>  0,
                          'pre_status'  =>  1,
                          'driver_lat'  =>  $latitude,
                          'driver_long' =>  $longitude,
                          'start_time'  =>  $start_time,
                        );
          
            }
            else
            {
              $arr  = array(
                        'ride_status'=>$status,
                          'driver_lat'=>$latitude,
                          'driver_long'=>$longitude,
                          //'start_time'=>$start_time,
                        ); 
            }
            $where = array('id'=>$ride_id);
            $update = $this->service_m->update('ride', $where, $arr);

            /////  Mail For Driver Details start ///////
            $rides = $this->admin->getRow('select * from ride where id='.$ride_id.'');
            $driver_names = $this->admin->getRow('select fname,lname,plate_no from driver_details where id ='.$rides->driver_id.'');


           if($rides->mail_status == 0)
           {
 
            $totalgatewayfees = '0.00';
            $taxpercentage = 0;
  
             $sh = '';$cc = '';  
             if($rides->book_type == 1)
             {  
                $sh = 'IB';
             } 
             elseif($rides->book_type == 2) 
             { 
                $sh = 'PB'; 
             }
                
             if($rides->car_category == 1)
             { 
                $cc = 'T';
             }  
             elseif($rides->car_category == 2) 
             { 
                $cc = 'L';
             } 
             elseif($rides->car_category == 3) 
             { 
                $cc = 'V';
             } 
             elseif($rides->car_category == 4) 
             { 
                $cc = 'A';
             }
                
             $bookingidshow = $sh.$rides->id.$cc;
             if(!empty($country_id))
             { 
                $valueshow = $this->service_m->getVal('SELECT currencyCode FROM countries WHERE id='.$country_id.'');
             } 

              //  Mail sending //
              $this->load->library('email');
              $email = $rides->email;

              $config['mailtype'] ='html';

              $this->email->initialize($config);

              $this->email->from('info@thecandytech.com', 'The Candy Tech');
              $this->email->to($email);

              $this->email->subject('Booking Confirmation');
            $data = array(
             'bookingidshow' => $bookingidshow,
             'book_date' => $rides->book_date,
             'fullname' => $rides->fullname,
             'pickupaddress' => $rides->pickupaddress,
             'dropaddress' => $rides->dropaddress,
             'valueshow' => $valueshow,
             'totalgatewayfees' => $totalgatewayfees,
             'taxpercentage' => $taxpercentage,
             'tax_fees' => $rides->tax_fees,
             'totalfare' => $rides->totalfare,
             'fname' => $driver_names->fname,
             'lname' => $driver_names->lname,
             'plate_no' => $driver_names->plate_no
                 );
            $body = $this->load->view('mailformat/bookingconfirmationdd',$data,TRUE);
            $this->email->message($body);   
            $this->email->send();
            }

               $where = array('id' => $ride_id);
               $mailstatusdata = array(
                                      'mail_status' => 1
                                      );
               $updateride = $this->service_m->update('ride',$where,$mailstatusdata);
               ////////  Mail For Driver Details end //////
    
                $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);
            }
        }

       public function ride_status_arrived_post()
       {
        $getdetail = file_get_contents("php://input");
        $getdetails = json_decode($getdetail);
        $ride_id = $getdetails->{'ride_id'};
        $latitude = $getdetails->{'latitude'};
        $longitude = $getdetails->{'longitude'};
        $status = $getdetails->{'status_type'};
        $book_type = $getdetails->{'book_type'};
        $driver_id = $getdetails->{'driver_id'};
        $start_time = $getdetails->{'start_time'};
        $driver_arrivaltime = $getdetails->{'driver_arrivaltime'};
        $auto_booking = $getdetails->{'auto_booking'};
           
         if($ride_id == '')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status == '')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        else
        {
          if($status == 1)
          {
           $device_id = $this->admin->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$ride_id.''); 
           
            $fcmMsg = array(
                           'body' => 'Your Driver has Arrived - please onboard within 10mins',
                           'title' => 'The Candy Booking',
                           'sound' => "default",
                           'color' => "#203E78" 
                         );
   
          if($device_id->device_type == 1) 
          {
           $fcmFields = array(
                              'to' => $device_id->token_id,
                              'data' => $fcmMsg
                             );
          }
          else
          {
            $fcmFields = array(
                                'to'   => $device_id->token_id,
                                'notification' => $fcmMsg,
                                'priority'=>'high'
                              );
          }

           $headers = array(
                             'Authorization: key=' . API_ACCESS_KEY,
                             'Content-Type: application/json'
                           );
 
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
      $result = curl_exec($ch );
      curl_close( $ch );   

          if($auto_booking == 1) 
          {  
              $arr = array(
                        'ride_status'=>$status,
                        'driver_id'=>$driver_id,
                        'driver_lat'=>$latitude,
                        'driver_long'=>$longitude,
                        'driver_eta'=>0,
                        'driver_arrivaltime'=>$driver_arrivaltime
                        );
           }
           else
           {
              $arr = array(
                        'ride_status'=>$status,
                        'driver_lat'=>$latitude,
                        'driver_long'=>$longitude,
                        'driver_arrivaltime'=>$driver_arrivaltime
                        );
            }
            
            $array1 = array(
                        'page_id'      => '15',
                        'insert_id'    => $ride_id,
                        'status'       => '1',
                        'member_id'    => $driver_id,
                        'user_type'    => 4
                          );
                    
          $insert = $this->service_m->insert('admin_notification', $array1);         
           }
           else if( $status == 3) 
           {
              $arr= array(
                        'ride_status'=>$status,
                        'driver_lat'=>$latitude,
                        'driver_long'=>$longitude,
                        'start_time'=>$start_time,
                        );
              $array1 = array(
                          'page_id'      => '16',
                          'insert_id'    => $ride_id,
                          'status'       => '1',
                          'member_id'    => $driver_id,
                          'user_type'    => 4
                            );
                    
            $insert = $this->service_m->insert('admin_notification', $array1);     
            }
            else if( $status == 5) 
            {
              $arr= array(
                        'ride_status'=>0,
                        'pre_status'=>1,
                        'driver_lat'=>$latitude,
                        'driver_long'=>$longitude,
                        'start_time'=>$start_time,
                        );
          
          }
          else
          {
            $arr  = array(
                        'ride_status'=>$status,
                        'driver_lat'=>$latitude,
                        'driver_long'=>$longitude,
                      ); 
          }
         $where = array('id'=>$ride_id);
         $update = $this->service_m->update('ride', $where, $arr);    
         $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);
           }
       }

       public function preride_status_post()
         {
         $rideid =$this->input->post('rideid');
         $latitude =$this->input->post('latitude');
         $longitude =$this->input->post('longitude');
       $status = $this->input->post('status_type');
       $pre_status = $this->input->post('pre_status');
       $book_type = $this->input->post('book_type');
       $id = $this->input->post('user_id');
       $start_time = $this->input->post('start_time');
       $driver_arrivaltime = $this->input->post('driver_arrivaltime');
       $auto_booking = $this->input->post('auto_booking');
           
         if($rideid=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        else
        {
          //$checkid=$this->service_m->getRow("select m.id,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.carcategory from members m, driver_details dd  where dd.id=m.org_id and m.id='".$id."' and m.usertype_id=4 ");
          //print_r($checkid);
          if($status == 1) {
          if($auto_booking == 1) {  
                 $arr = array(
                     'ride_status'=>$status,
                      'pre_status'=>$pre_status,
                     
                     'driver_id'=>$id,
                     'driver_lat'=>$latitude,
                     'driver_long'=>$longitude,
                     'start_time'=>$start_time
                  );
                 }else{
             $arr = array(
                     'ride_status'=>$status,
                     'pre_status'=>$pre_status,
                     'driver_lat'=>$latitude,
                     'driver_long'=>$longitude,
                     'start_time'=>$start_time
                  );

                 }
          }else if( $status == 3) {
             $arr   = array(
                     'ride_status'=>$status,
                     'pre_status'=>$pre_status,
                     'driver_lat'=>$latitude,
                     'driver_long'=>$longitude,
                     'driver_arrivaltime'=>$driver_arrivaltime
                    );
          }
          else{
             $arr  = array(
                     'ride_status'=>$status,
                     'pre_status'=>$pre_status,
                     'driver_lat'=>$latitude,
                     'driver_long'=>$longitude,
                    ); 
          }
         $where = array('id'=>$rideid);
         $update = $this->service_m->update('ride', $where, $arr);    
         $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);
           }
       }

      public function prebcancel_trip_post()
         {
  $rideid =$this->input->post('rideid');
  $ride=$this->admin->getRow('SELECT user_id,driver_id,client_id,totalfare FROM ride r WHERE  id = '.$rideid.'');     
    if($rideid=='')
        {
      $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        else
        {
                $arr = array(
                     'ride_status'=>0,
                     'pre_status'=>0,
                     'driver_id'=>0,
                      );
        $where = array('id'=>$rideid);
        $update = $this->service_m->update('ride', $where, $arr); 
        /*$array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$ride->totalfare,
              'status'=>1 
              );
    $insert5 = $this->admin->insert('top_up_credit',$array5);*/
        $array1 = array(
                     'page_id'    => '17',
                     'insert_id'  =>  $rideid,
                     'status'     => '1',
                     'member_id'  =>  $driver_id,
                     'user_type'  =>  4
                      );
        $insert = $this->service_m->insert('admin_notification', $array1);    
        $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
        }
        }
      public function prebooking_status_trip_post()
        {
      $id =$this->input->post('id');
      $rideid =trim($this->input->post('rideid'));
      $dpid =$this->input->post('dpid');
      $fareid =$this->input->post('fareid'); 
      $driver_id =$this->input->post('driver_id');
      $totalfare =$this->input->post('totalfare');
      $clientfees =$this->input->post('clientfees');
      $dispatchfees =$this->input->post('dispatchfees');
      $adminfees =$this->input->post('adminfees');
      $prebooking_fee =$this->input->post('prebooking_fee');
      $gateway_fee =$this->input->post('gateway_fee');
      $managerfee =$this->input->post('managerfee');
      $driverfees =$this->input->post('driverfees');
      $pre_status =$this->input->post('pre_status');
      $ridefare=$this->service_m->getRow('SELECT totalfare,fareid,pre_status FROM ride r WHERE  id = '.$rideid.'');     
     // $last_query=$this->db->last_query();
       if($rideid=='')
        {
      $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        else
        {
            if($ridefare->fareid == $fareid){
            $totaladminfees =$adminfees;
            }else{
            $totaladminfees1 =$ridefare->totalfare - $totalfare;
            $totaladminfees =$totaladminfees1 + $adminfees;
            }
          
                $array = array(
                   'dispatch_id'=>$dpid,
                   'driver_id'=>$driver_id,
                   'clientfees'=>$clientfees,
                   'dispatchfees'=>$dispatchfees,
                   'adminfees'=>$totaladminfees,
                    'prebooking_fee'=>$prebooking_fee,
                   'gateway_fee'=>$gateway_fee,
                   'managerfee'=>$managerfee,
                   'driverfees'=>$driverfees,
                     'ride_status'=>0,
                     'pre_status'=>$pre_status,
                      );
       //$where = array('id'=>$rideid);
       //$update = $this->service_m->update('ride', $where, $arr); 
      if($ridefare->pre_status==1)
       {
       $this->response(['responsecode' => 0,'response' => 'Already assigned this ride!'], REST_Controller::HTTP_OK);
       }else{
       $update = $this->service_m->update('ride',array('id'=>$rideid), $array);
       }
    if($update){
  $array1 = array(
                     'pre_status'=>1,
                    );
       //$where = array('id'=>$rideid);
       //$update = $this->service_m->update('ride', $where, $arr); 
       $update1 = $this->service_m->update('ride_pay',array('ride_id'=>$rideid), $array1);
        //  $delete=$this->admin->deleteAll('ride_pay',array('ride_id'=>$rideid));
       $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
    }
        }
        }

          public function prebooking_status_change_post()
         {
      $rideid =trim($this->input->post('rideid'));
      $pre_status =$this->input->post('pre_status');
      
        $array = array(
                     'ride_status'=>0,
                     'pre_status'=>$pre_status,
                      );
       //$where = array('id'=>$rideid);
       //$update = $this->service_m->update('ride', $where, $arr); 
       $update = $this->service_m->update('ride',array('id'=>$rideid), $array);
         $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
         }
     
     // Driver Latitude And Longitude Update //
     public function driver_latlong_post()
         {
          $getdetail = file_get_contents("php://input");
          $getdetails = json_decode($getdetail);
          $driver_id = $getdetails->{'driver_id'};
          $latitude = $getdetails->{'latitude'};
          $longitude = $getdetails->{'longitude'};

          $arr = array(
                     'driver_lat' =>  $latitude,
                     'driver_long'  =>  $longitude,
                      ); 

          $where = array('id'=>$driver_id);
          $update = $this->service_m->update('driver_details', $where, $arr); 

          if($update)
          {   
            $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
            }
            else
            {
              $this->response(['responsecode' => 0,'response' => 'fail'], REST_Controller::HTTP_OK);
            }
        }

      // Token id update //
      public function tokenid_update_post()
         {
          $getdetail = file_get_contents("php://input");
          $getdetails = json_decode($getdetail);
          $driver_id = $getdetails->{'driver_id'};
          $token_id = $getdetails->{'token_id'};

          date_default_timezone_set("Asia/Kolkata");
          $arr = array(
                     'token_id' =>  $token_id,
                     'updated_at' => date('Y-m-d h:i:sa')
                      ); 

          $getdriver_id = $this->service_m->getVal('SELECT id FROM members WHERE org_id='.$driver_id.' AND usertype_id = 4');
          $where = array('id'=>$getdriver_id);
          $update = $this->service_m->update('members', $where, $arr); 

          if($update)
          {   
            $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
            }
            else
            {
              $this->response(['responsecode' => 0,'response' => 'fail'], REST_Controller::HTTP_OK);
            }
        }

      public function cancel111_trip_post()
         {
       $rideid =$this->input->post('rideid');
       $reason = $this->input->post('reason');
       $status = $this->input->post('status_type');
       $end_time = $this->input->post('end_time');

$ride=$this->admin->getRow('SELECT user_id,driver_id,totalfare FROM ride r WHERE  id = '.$rideid.'');

$device=$this->admin->getRow('SELECT m.device_id,m.device_type FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$rideid.'');   
$fcmMsg = array(
  'body'  => 'Your booking cancelled@2',
  'title' => '1cabbi Booking',
  'sound' => "default",
  'color' => "#203E78" 
);
   

if($device->device_type == 1)
{
$fcmFields = array(
  'to'   => $device->device_id,
  'data' => $fcmMsg
    );
}else{
  $fcmFields = array(
  'to'   => $device->device_id,
  'notification' => $fcmMsg,
  'priority'=>'high'
    );
}

$headers = array(
  'Authorization: key=' . API_ACCESS_KEY,
  'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
$result = curl_exec($ch );
curl_close( $ch );
       
         if($rideid=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        else
        {
          //$checkid=$this->service_m->getRow("select m.id,dd.profile_photo,dd.plate_no,dd.activestatus,dd.profile_photo,dd.carcategory from members m, driver_details dd  where dd.id=m.org_id and m.id='".$id."' and m.usertype_id=4 ");
          //print_r($checkid);
                $arr = array(
                     'ride_status'=>$status,
                     'cancelled_by'=>4,
                     'reason'=>$reason,
                    'refundamount'   => $ride->totalfare,
                     'driver_long'=>$longitude,
                      'end_time'=>$end_time  
                      );
        $where = array('id'=>$rideid);
        $update = $this->service_m->update('ride', $where, $arr); 

          $array1 = array(
                     'page_id'      => '17',
                     'insert_id'    => $rideid,
                     'status'       => '1',
                     'member_id'    => $driver_id,
                     'user_type'    => 4
                      );
                    
        $insert = $this->service_m->insert('admin_notification', $array1);    
        $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);
          }
           }

 public function cancel_trip_post()
         {
       $rideid =$this->input->post('rideid');
       $id =$this->input->post('rideid');
       $reason = $this->input->post('reason');
       $status = $this->input->post('status_type');
       $country_id = $this->input->post('country_id');
       $cancel_date_time = $this->input->post('cancel_date_time');
       if($rideid=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        else
        {

    if($this->input->post('country_id')==1) 
  {
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $end_time = date('Y-m-d h:i:sa');
  } 
  else if($this->input->post('country_id')==2) 
  { date_default_timezone_set('Asia/Singapore');
    $end_time  = date('Y-m-d h:i:sa');
  } 
  else 
  {
  date_default_timezone_set('Asia/Brunei');
  $end_time  = date('Y-m-d h:i:sa');
  }   
  $date1 = strtotime($ride->book_datetime);  
$date2 = strtotime($end_time);  
$diff = abs($date2 - $date1);  
$years = floor($diff / (365*60*60*24));  
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 
$hours = floor(($diff - $years * 365*60*60*24  
       - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
$minutes = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24  
                          - $hours*60*60)/ 60);    
  $adminfees='0.00';
  $totalamount='0.00';
  $cancellation_charge='0.00';
$ride=$this->admin->getRow('SELECT id,driver_id,user_id,client_id,book_date,book_datetime,paymentmode,totalfare,book_type,ride_status FROM ride WHERE  id ='.$id.'');
 $adminfee=$this->admin->getRow('SELECT admin_id,admin_fee_charge,cancellation_charge_instant,cancellation_charges_pre FROM admin_fee WHERE country_id = "'.$this->input->post('country_id').'" '); 

if($ride->ride_status == 0){
   $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
    $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$ride->totalfare, 
              'status'=>1
              );
    $insert5 = $this->admin->insert('top_up_credit',$array5);
  

    }else{
  if($days == '0'){
  if($hours == '0'){
  if($minutes <= '10'){
     $delete = $this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
     $taotal = $ride->totalfare;
      if($ride->paymentmode == '2'){
     /*if($ride->book_type == 1){
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }*/
      }
      if($ride->paymentmode == '3'){
      /*if($ride->book_type == 1){
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }*/
       $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
     if($ride->user_id == 0){
       $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$ride->totalfare, 
              'status'=>1
              );
       $insert5 = $this->admin->insert('top_up_credit',$array5);
      }else{
  $array3   = array(
              'm_id'=>$ride->user_id,
              'ride_id'=>$ride->id,
              'amount'=>$ride->totalfare, 
              'status'=>1,
              'user_type_id'=>5,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      }
      }
     }
     else{
     //echo $minutes;exit;
     $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
     //echo $this->db->last_query();
     $taotal =$ride->totalfare;
     if($ride->paymentmode == '2'){
     if($ride->book_type == 1){
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
       $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
        $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
   
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
       $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
        $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
       }
      }
    if($ride->paymentmode == '3'){
    if($ride->book_type == 1){
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount = $cancellation_charge - $adminfees;
      $totalamount  = $ride->totalfare - $cancellation_charge;
      }
       $array3   = array(
              'm_id'=>$ride->driver_id,
              'ride_id'=>$ride->id,
              'amount'=>$driveramount, 
              'status'=>1,
              'user_type_id'=>4,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      $array4 = array(
              'm_id'=>1,
              'ride_id'=>$ride->id,
              'amount'=>$adminfees, 
              'status'=>1,
              'user_type_id'=>1,
              );
     $insert4 = $this->admin->insert('e_wallet',$array4);
     /* $array5  = array(
              'm_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>2,
              );
      $insert5 = $this->admin->insert('e_wallet',$array5);*/
      if($ride->user_id == 0){
       $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$totalamount, 
              'status'=>1
              );
       $insert5 = $this->admin->insert('top_up_credit',$array5);
      }else{
  $array3  = array(
              'm_id'=>$ride->user_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>5,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      }
    
      }
     }
  }else{
      //echo $minutes;exit;
      $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
      //echo $this->db->last_query();
      $taotal =$ride->totalfare;
      if($ride->paymentmode == '2'){
      if($ride->book_type == 1){
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
       }
      }
      if($ride->paymentmode == '3'){
      if($ride->book_type == 1){
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
      $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }
      $array3 = array(
              'm_id'=>$ride->driver_id,
              'ride_id'=>$ride->id,
              'amount'=>$driveramount, 
              'status'=>1,
              'user_type_id'=>4,
                     );
       $insert3 = $this->admin->insert('e_wallet',$array3);
       $array4 = array(
                 'm_id'=>1,
                 'ride_id'=>$ride->id,
                 'amount'=>$adminfees, 
                 'status'=>1,
                 'user_type_id'=>1,
                      );
      $insert4 = $this->admin->insert('e_wallet',$array4);
      //echo $this->db->last_query();
      /*$array5  = array(
                  'm_id'=>$ride->client_id,
                  'ride_id'=>$ride->id,
                  'amount'=>$totalamount, 
                  'status'=>1,
                  'user_type_id'=>2,
                       );
      $insert5 = $this->admin->insert('e_wallet',$array5);*/
       if($ride->user_id == 0){
       $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$totalamount, 
              'status'=>1
              );
       $insert5 = $this->admin->insert('top_up_credit',$array5);
      }else{
    $array3 = array(
              'm_id'=>$ride->user_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>5,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      }
   
}
}
}else{
       //echo $minutes;exit;
     $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
       //echo $this->db->last_query();
    // $taotal =$ride->totalfare;
      if($ride->paymentmode == '2'){
           if($ride->book_type == 1){

      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
            $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
      $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{

      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
            $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
       $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
       }
       }
      if($ride->paymentmode == '3'){
      if($ride->book_type == 1){

      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charge_instant / 100;
            $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
       $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{

      $cancellation_charge  =  $ride->totalfare * $adminfee->cancellation_charges_pre / 100;
            $adminfees  =  $cancellation_charge * $adminfee->admin_fee_charge / 100;
       $driveramount =  $cancellation_charge - $adminfees;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }
     // echo $cancellation_charge;echo $adminfees;echo $driveramount;echo $totalamount;
      $array3 = array(
              'm_id'=>$ride->driver_id,
              'ride_id'=>$ride->id,
              'amount'=>$driveramount, 
              'status'=>1,
              'user_type_id'=>4,
               );
      $insert3 = $this->admin->insert('e_wallet',$array3);

      $array4 = array(
              'm_id'=>1,
              'ride_id'=>$ride->id,
              'amount'=>$adminfees, 
              'status'=>1,
              'user_type_id'=>1,
           );
      $insert4 = $this->admin->insert('e_wallet',$array4);

     /* $array5  = array(
              'm_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>2,
           );
      $insert5 = $this->admin->insert('e_wallet',$array5);*/
        if($ride->user_id == 0){
       $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$totalamount, 
              'status'=>1
              );
       $insert5 = $this->admin->insert('top_up_credit',$array5);
      }else{
  $array3   = array(
              'm_id'=>$ride->user_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>5,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      }
      }
}
}
    if($ride->ride_status == 0){
    $array = array(
               'ride_status'    => 2,
             //'reason'    => $this->input->post('reason'),
               'end_time' => $end_time,
               'cancelled_by' => $this->session->userdata('admin_type')
                );
    $update=$this->admin->update('ride',array('id'=>$id), $array);
    }else{
    $array = array(
               'ride_status'    => 2,
               'reason'        => $reason,
               'cancelled_by'   => 4,
               'refundamount'   => $totalamount,
               'cancelcharge'   => $cancellation_charge,
               'cancel_service_fee' => $adminfees,
               'end_time'       => $end_time,
               'cancelled_by'   => 4
               );
    $update=$this->admin->update('ride',array('id'=>$id), $array);
          }
//echo $this->db->last_query();
       $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);        
  //$device=$this->admin->getRow('SELECT m.device_id,m.device_type FROM members m, ride r WHERE  m.usertype_id =  4 and  m.id = r.driver_id and r.id = '.$rideid.'');  
  $device=$this->admin->getRow('SELECT m.device_id,m.device_type FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$rideid.'');    
$fcmMsg = array(
  'body'  => 'Your booking cancelled@2',
  'title' => '1cabbi Booking',
  'sound' => "default",
  'color' => "#203E78" 
  );
   
if($device->device_type == 1)
{
$fcmFields = array(
  'to'   => $device->device_id,
  'data' => $fcmMsg
    );
}else{
$fcmFields = array(
  'to'   => $device->device_id,
  'notification' => $fcmMsg,
  'priority'=>'high'
    );
}

$headers = array(
  'Authorization: key=' . API_ACCESS_KEY,
  'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
$result = curl_exec($ch );
curl_close( $ch );

    }
  }       
      public function noshowcancel_trip_post()
         {
       $rideid =$this->input->post('rideid');
       $id =$this->input->post('rideid');
       $reason = $this->input->post('reason');
       $status = $this->input->post('status_type');
       $country_id = $this->input->post('country_id');
       $cancel_date_time = $this->input->post('cancel_date_time');
       if($rideid=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        else
        {

    if($this->input->post('country_id')==1) 
  {
  date_default_timezone_set('Asia/Kuala_Lumpur');
  $end_time = date('Y-m-d h:i:sa');
  } 
  else if($this->input->post('country_id')==2) 
  { date_default_timezone_set('Asia/Singapore');
    $end_time  = date('Y-m-d h:i:sa');
  } 
  else 
  {
  date_default_timezone_set('Asia/Brunei');
  $end_time  = date('Y-m-d h:i:sa');
  }   
  $date1 = strtotime($ride->book_datetime);  
$date2 = strtotime($end_time);  
$diff = abs($date2 - $date1);  
$years = floor($diff / (365*60*60*24));  
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 
$hours = floor(($diff - $years * 365*60*60*24  
       - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
$minutes = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24  
                          - $hours*60*60)/ 60);    
  $adminfees='0.00';
  $totalamount='0.00';
  $cancellation_charge='0.00';
$ride=$this->admin->getRow('SELECT id,driver_id,user_id,client_id,book_date,book_datetime,paymentmode,totalfare,book_type,ride_status,adminfees,driverfees FROM ride WHERE  id ='.$id.'');
 $adminfee=$this->admin->getRow('SELECT admin_id,admin_fee_charge,cancellation_charge_instant,cancellation_charges_pre FROM admin_fee WHERE country_id = "'.$this->input->post('country_id').'" '); 

if($ride->ride_status == 0){
   $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
    $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$ride->totalfare, 
              'status'=>1
              );
    $insert5 = $this->admin->insert('top_up_credit',$array5);
  

    }else{
     $delete = $this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
     $taotal = $ride->totalfare;
   $driverearning = $ride->driverfees;
     $adminearning = $ride->adminfees;
      if($ride->paymentmode == '2'){

        if($ride->book_type == 1){
      $adminfees  =  $adminearning * $adminfee->cancellation_charge_instant / 100;
      $driveramount =  $driverearning * $adminfee->cancellation_charge_instant / 100;
      $cancellation_charge = $ride->totalfare * $adminfee->cancellation_charge_instant / 100 ;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
   
      $adminfees  =  $adminearning * $adminfee->cancellation_charges_pre / 100;
      $driveramount =  $driverearning * $adminfee->cancellation_charges_pre / 100;
      $cancellation_charge = $ride->totalfare * $adminfee->cancellation_charges_pre / 100 ;
      $totalamount = $ride->totalfare - $cancellation_charge;
       }
     
     $array3   = array(
              'm_id'=>$ride->driver_id,
              'ride_id'=>$ride->id,
              'amount'=>$driveramount, 
              'status'=>1,
              'user_type_id'=>4,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      $sql3 = $this->db->last_query();
      $array4 = array(
              'm_id'=>1,
              'ride_id'=>$ride->id,
              'amount'=>$adminfees, 
              'status'=>1,
              'user_type_id'=>1,
              );
     $insert4 = $this->admin->insert('e_wallet',$array4);
     $sql4 = $this->db->last_query();
     /* $array5  = array(
              'm_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>2,
              );
      $insert5 = $this->admin->insert('e_wallet',$array5);*/
       $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$totalamount, 
              'status'=>1
              );
    $insert5 = $this->admin->insert('top_up_credit',$array5);
    $sql5 = $this->db->last_query();
      }
      if($ride->paymentmode == '3'){
      if($ride->book_type == 1){
      $adminfees  =  $adminearning * $adminfee->cancellation_charge_instant / 100;
      $driveramount =  $driverearning * $adminfee->cancellation_charge_instant / 100;
      $cancellation_charge = $ride->totalfare * $adminfee->cancellation_charge_instant / 100 ;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }else{
      $adminfees  =  $adminearning * $adminfee->cancellation_charges_pre / 100;
      $driveramount =  $driverearning * $adminfee->cancellation_charges_pre / 100;
      $cancellation_charge = $ride->totalfare * $adminfee->cancellation_charges_pre / 100 ;
      $totalamount = $ride->totalfare - $cancellation_charge;
      }
       $array3   = array(
              'm_id'=>$ride->driver_id,
              'ride_id'=>$ride->id,
              'amount'=>$driveramount, 
              'status'=>1,
              'user_type_id'=>4,
              );
      $insert3 = $this->admin->insert('e_wallet',$array3);
      $sql3 = $this->db->last_query();
      $array4 = array(
              'm_id'=>1,
              'ride_id'=>$ride->id,
              'amount'=>$adminfees, 
              'status'=>1,
              'user_type_id'=>1,
              );
     $insert4 = $this->admin->insert('e_wallet',$array4);
     $sql4 = $this->db->last_query();
     /* $array5  = array(
              'm_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'amount'=>$totalamount, 
              'status'=>1,
              'user_type_id'=>2,
              );
      $insert5 = $this->admin->insert('e_wallet',$array5);*/
       $array5 = array(
              'client_id'=>$ride->client_id,
              'ride_id'=>$ride->id,
              'updated_balance'=>$totalamount, 
              'status'=>1
              );
    $insert5 = $this->admin->insert('top_up_credit',$array5);
    $sql5 = $this->db->last_query();
        
      
      }
}
    if($ride->ride_status == 0){
    $array = array(
               'ride_status'    => 2,
             //'reason'         => $this->input->post('reason'),
               'end_time'       => $end_time,
               'cancelled_by'   => $this->session->userdata('admin_type')
                );
    $update=$this->admin->update('ride',array('id'=>$id), $array);
    }else{
    $array = array(
               'ride_status'    => 2,
               'reason'         => $reason,
               'cancelled_by'   => 4,
               'refundamount'   => $totalamount,
               'cancelcharge'   => $cancellation_charge,
               'cancel_service_fee' => $adminfees,
               'end_time'       => $end_time,
               'cancelled_by'   => 4,
               'updated_driver_earning' => $driveramount
               );
    $update=$this->admin->update('ride',array('id'=>$id), $array);
          }

          if($update)
           {
           if($ride->book_type == 2){
            $deleteride=$this->admin->deleteAll('ride_pay',array('ride_id'=>$ride->id));
            }
            $cancelrides = $this->admin->getRow('select * from ride where id='.$id.'');
            $sh='';$cc='';  
                    if($cancelrides->book_type==1){  $sh = 'IB';} elseif($cancelrides->book_type==2) { $sh = 'PB'; }
                    if($cancelrides->car_category==1){ $cc = 'T';}  elseif($cancelrides->car_category==2) { $cc = 'L';} elseif($cancelrides->car_category==3) { $cc = 'V';} elseif($cancelrides->car_category==4) { $cc = 'A';}
                          $cancelbookingidshow = $sh.$cancelrides->id.$cc;
if($this->session->userdata('country_id')==1){ $valueshow = 'RM';} elseif($this->session->userdata('country_id')==2){ $valueshow='SGD';} elseif($this->session->userdata('country_id')==3){ $valueshow = 'B$';}



 $date = strtotime($cancelrides->book_date);
 $cancelbookingdate = date('d-m-Y H:i:s',$date);

  $detail = $this->admin->getRow('select * from ride where id='.$id.'');
  $passengeremail = $this->admin->getVal('select email from ride where id='.$id.'');
  $subject="No Show Booking"; 
  $message = '<html><head></head><body><center><img src="https://www.1cabbi.site/themes/admin/images/logo.png" width="220" height="56"></center>
</br>
<p><b>Date:</b> '.$cancelbookingdate.'<span style="float:right"><b>Attn To:</b>'.$detail->fullname.'</span></p>
<p><b>Booking ID:</b> '.$cancelbookingidshow.'</p><br><br>
<p><strong>Your Booking Details:</strong></p>
<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Pickup Location:</strong>'.$detail->pickupaddress.'</p>
<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Drop-Off:</strong> '.$detail->dropaddress.'</p><br><br>
<p><strong>Trip Fare:</strong> '.number_format($detail->totalfare,2).'</p>
<p><strong>Cancellation Charges:</strong> '.number_format($cancellation_charge,2).'</p>
<p><strong>Refund Recieved:</strong> '.number_format($totalamount,2).'</p>
<p><strong>Toll Price:</strong> '.number_format($detail->tolltaxamount,2).'</p>
<p><b>We wish to thank you for your support in changing the Eco-System of Limousine and Taxi serving the Nation
If there is any feedback, suggestion or complaint, please email us at support@1cabbi.com</b></p>
<p><center>HAVE A PLEASANT DAY</center></p>
</body></html>';
 //<b>You have been served by:(Driver Name)</b><br>
//<b>Vehicle number:</b>(Vehicle Number)<br>
  $email=$this->input->post('email'); 
  //cho $email; exit;
        $this->load->config('email');
        $this->load->library('email');
        
        $from = $this->config->item('smtp_user');
        $to = $passengeremail;  
        $subject = $subject;
        $message = $message;

        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
      }

$device=$this->admin->getRow('SELECT m.device_id,m.device_type FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$rideid.'');    
$fcmMsg = array(
  'body'  => 'Your booking cancelled@2',
  'title' => '1cabbi Booking',
  'sound' => "default",
  'color' => "#203E78" 
  );
   
if($device->device_type == 1)
{
$fcmFields = array(
  'to'   => $device->device_id,
  'data' => $fcmMsg
    );
}else{
$fcmFields = array(
  'to'   => $device->device_id,
  'notification' => $fcmMsg,
  'priority'=>'high'
    );
    }
$headers = array(
  'Authorization: key=' . API_ACCESS_KEY,
  'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
$result = curl_exec($ch );
curl_close( $ch );

       $this->response(['responsecode' => 1,'ride_status'=>$status,'sql'=>$sql,'response' => 'Success!'], REST_Controller::HTTP_OK);        
  //$device=$this->admin->getRow('SELECT m.device_id,m.device_type FROM members m, ride r WHERE  m.usertype_id =  4 and  m.id = r.driver_id and r.id = '.$rideid.'');  
    }
  }       

 public function send_email_get()
    {
        $this->load->config('email');
        $this->load->library('email');
        $from = $this->config->item('smtp_user');
        $to = "bjat1212@gmail.com";  
        $subject = 'Testing the email class.';
        $message = 'Testing the email class.';
        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if($this->email->send())
        {
        $this->response(['responsecode' => 1,'response' => 'SEND'], REST_Controller::HTTP_OK);
        }else{
        $this->response(['responsecode' => 0,'response' => show_error($this->email->print_debugger())], REST_Controller::HTTP_OK);  
        }

    }

           public function ride_tolltax_post()
         {
         $rideid =$this->input->post('rideid');
         $amount =$this->input->post('amount');
         $driver_payment_mode =$this->input->post('driver_payment_mode');
         
             $ride=$this->admin->getRow('SELECT user_id,driver_id FROM ride r WHERE  id = '.$rideid.'');
       
         if($rideid=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        else
        {
                $arr = array(
                     'tolltaxamount'=>$amount,
                     'driver_payment_mode'=>$driver_payment_mode
                      );
        $where = array('id'=>$rideid);
        $update = $this->service_m->update('ride', $where, $arr); 

        $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
          }
           }
  
   // Ride Completed API //
   public function completed_ride_post()
   {
      $getdetail = file_get_contents("php://input");
      $getdetails = json_decode($getdetail);   
        $ride_id = $getdetails->{'ride_id'};
        $latitude = $getdetails->{'latitude'};
        $longitude = $getdetails->{'longitude'};
        $status = $getdetails->{'status_type'};
        $book_type = $getdetails->{'book_type'};
        $end_time = $getdetails->{'end_time'};
        $driver_id = $getdetails->{'driver_id'};


        $getdriverpayment = $this->service_m->getRow('SELECT driver_payment_mode,tolltaxamount FROM ride WHERE id='.
          $ride_id.'');

        $array1 = array(
                     'page_id'      => '18',
                     'insert_id'    => $ride_id,
                     'status'       => '1',
                     'member_id'    => $driver_id,
                     'user_type'    => 4
                      );
                    
        $insert = $this->service_m->insert('admin_notification', $array1);    

      $device_id = $this->admin->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$ride_id.''); 
      
      $fcmMsg = array(
                      'body'  => 'Your booking completed',
                      'title' => 'The Candy Booking',
                      'sound' => "default",
                      'color' => "#203E78" 
                     );
      if($device_id->device_type == 1) 
      {
        $fcmFields = array(
                           'to'    => $device_id->token_id,
                           'data'  => $fcmMsg
                           );
      }
      else
      {
        $fcmFields = array(
                            'to'            => $device_id->token_id,
                            'notification'  => $fcmMsg,
                            'priority'      =>'high'
                          );
      }  
    
      $headers = array(
                        'Authorization: key=' . API_ACCESS_KEY,
                        'Content-Type: application/json'
                      );
 
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
       
        if($ride_id == '')
        {
          $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
        }
        elseif($status == '')
        {
          $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
        }
        else
        {
          
          $getdata = $this->service_m->getRow("SELECT * FROM ride where id='".$ride_id."'");

          // if($getdriverpayment->driver_payment_mode == 1)
          // {
            $totaldriverfees = $getdata->driverfees;
          // }
          // if($getdriverpayment->driver_payment_mode == 2)
          // {
          //$totaldriverfees = $getdriverpayment->tolltaxamount + $getdata->driverfees;
          //}
                 

                 $arr = array(
                     'ride_status'  => $status,
                     'driver_lat'   => $latitude,
                     'driver_long'  => $longitude,
                     'end_time'     => $end_time
                  );
                  
         $where = array('id'=>$ride_id);
         $update = $this->service_m->update('ride', $where, $arr);  
         if($getdata->dispatch_id != '')
         {
          $array1 = array(
                      'm_id'          => $getdata->dispatch_id,
                      'ride_id'       => $ride_id,
                      'amount'        => $getdata->dispatchfees, 
                      'status'        => 1,
                      'user_type_id'  => 3,
                      );
           
          $insert1 = $this->service_m->insert('e_wallet',$array1);
          }
          if($getdata->driver_id != 0)
          {
            $array2   = array(
                        'm_id'=>$getdata->driver_id,
                        'ride_id'=>$ride_id,
                        'amount'=>$totaldriverfees, 
                        'status'=>1,
                        'user_type_id'=>4,
                    );
          }
           $insert2 = $this->service_m->insert('e_wallet',$array2);
           if($getdata->manager_id != '')
           {
                   $array3  = array(
                          'm_id'=>$getdata->manager_id,
                          'ride_id'=>$ride_id,
                          'amount'=>$getdata->managerfee, 
                          'status'=>1,
                          'user_type_id'=>6,
                        );
                $insert3 = $this->service_m->insert('e_wallet',$array3);
          }
          if($getdata->admin_id != '')
          {

           $array4   = array(
                            'm_id'=>$getdata->admin_id,
                            'ride_id'=>$ride_id,
                            'amount'=>$getdata->adminfees, 
                            'status'=>1,
                            'user_type_id'=>1,
                        );
            $insert4 = $this->service_m->insert('e_wallet',$array4);
          }
          if($getdata->client_id != 0)
           {
              if($getdata->user_id != 0)
              {
                  $amountofclient = $getdata->royality_fees;
              }
              else
              {
                  $amountofclient = $getdata->clientfees;
              }

              $array5 = array(
                        'm_id'=>$getdata->client_id,
                        'ride_id'=>$ride_id,
                        'amount'=>$amountofclient, 
                        'status'=>1,
                        'user_type_id'=>2,
                      );
              $insert5 = $this->service_m->insert('e_wallet',$array5);
            }
                 
          $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);
        }
    }

             
       public function my_earning_trip_post()
         {
         $id =$this->input->post('user_id');
         $myearning=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."'  AND ride_status = 4");
         //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
         if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}
         
        if($id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 1,'myearning'=>$myearn,'response' => 'Success!'], REST_Controller::HTTP_OK);
                }
           }

            public function ewallet_post()
         {
          $id =$this->input->post('user_id');
          //  $myearning=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."'  AND ride_status = 4");
          $driverdetail=$this->service_m->getRow('SELECT b.name,dd.ac_name,dd.ac_no FROM members m, driver_details dd,banks b WHERE dd.bank_name= b.id and  m.usertype_id = 4 and m.org_id = dd.id and dd.id = '.$id.'');
           $amount=$this->service_m->getVal("SELECT SUM(amount) FROM e_wallet WHERE m_id = '".$id."' AND status = 1 AND cronstatus=0 AND user_type_id = 4 GROUP BY m_id");
           $query = $this->db->last_query();
           $cashoutamount=$this->service_m->getVal("SELECT SUM(cash_out_amount) FROM e_wallet WHERE m_id = '".$id."' AND status = 2 AND user_type_id = 4 GROUP BY m_id");
           $myearnew = $amount;
         if($myearnew > 0){$myearn = $myearnew; }else{$myearn ='0.00';}
         
        if($id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter user id'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 1,'amount'=>round($myearn,2),'driverdetail'=>$driverdetail,'response' => 'Success!'], REST_Controller::HTTP_OK);
                }
       }

           public function cashout_post()
         {
           $id =$this->input->post('user_id');
           $amount =$this->input->post('amount');
           $amount1=$this->service_m->getVal("SELECT SUM(amount) FROM e_wallet WHERE m_id = '".$id."' AND status = 1 AND user_type_id = 4 GROUP BY m_id");
           $cashoutamount=$this->service_m->getVal("SELECT SUM(cash_out_amount) FROM e_wallet WHERE m_id = '".$id."' AND status = 2 AND user_type_id = 4 GROUP BY m_id");
           $myearn = $amount1 - $cashoutamount;
           if($myearn > 0) { $myearnnew = $myearn; } else{ $myearnnew ='0.00';}
           $book_date = date('Y-m-d H:i:s');

         $array = array(
                     'm_id'         => $id,
                     'amount'       => $amount,
                     'walletamount' => '0',
                     'status'       => '2',
                     'user_type_id' => '4',
                     'auto_status'  => '1',
                     'cronstatus'   => '1',
                     'reedem_date'  => date('Y-m-d H:i:s')
                      );
               $insert = $this->service_m->insert('e_wallet', $array);

        $egetdata1=$this->admin->getRows("SELECT e.* FROM e_wallet e,members m where e.m_id='".$id."' and m.org_id=e.m_id and e.user_type_id=4 and e.status=1 and  e.cronstatus=0");

        foreach($egetdata1 as $egetdatai)
        {
    $array21  = array(
          'cronstatus' => 1,
          'reedem_date'=>$book_date,
            );
    $update1 = $this->admin->update('e_wallet',array('id'=>$egetdatai->id), $array21);
  }

               $array1 = array(
                     'page_id'      => '12',
                     'insert_id'    => $insert,
                     'status'       => '1',
                     'member_id'    => $id,
                     'user_type'    => '4'
                      );
                    
                $insert = $this->service_m->insert('admin_notification', $array1);    
         
        if($insert!='')
        { 
        $this->response(['responsecode' => 1,'response' => 'Send Successful Cash Out Request!'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
                }
       }

        public function cashout_history_get($id)
         {
         $myearning=$this->service_m->getRows("SELECT * FROM e_wallet WHERE m_id ='".$id."' AND status = 2 AND user_type_id = 4 GROUP BY id ORDER BY id DESC");
         $sql = $this->db->last_query();
         //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
         if($myearning != NULL)
         {
          $myearn = $myearning; 
        }
        else
          {
            $myearn =[];
          }
         
        if($myearning =='')
        {
        $this->response(['responsecode' => 0,'response' => 'empty'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 1,'mytrip'=>$myearn,'response' => 'Success!'], REST_Controller::HTTP_OK);
                }
       }

     public function trip_history_post()
        {
          $getdetail = file_get_contents("php://input");
          $getdetails = json_decode($getdetail);
          $id = $getdetails->{'id'};
          $myearning = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND ride_status in (2,4) ORDER BY id DESC");

          $totalwalletsums = $this->service_m->getVal("SELECT SUM(ew.amount) FROM e_wallet ew,ride r WHERE ew.m_id = ".$id." AND ew.status = 1 AND ew.cronstatus = 0 AND ew.user_type_id = 4 AND r.id = ew.ride_id AND r.ride_status IN (2,4) GROUP BY ew.m_id");

          foreach ($myearning as $myearnings) {
					
					$alltripdetail[] = array(
								'id'			   		=> $myearnings->id,
								'dispatch_id'      		=> $myearnings->dispatch_id,
                  				'manager_id'       		=> $myearnings->manager_id,
                    			'admin_id'         		=> $myearnings->admin_id,
                    			'client_id'		   		=> $myearnings->client_id,	
                    			'user_id'          		=> $myearnings->user_id,
                    			'country_id'       		=> $myearnings->country_id,
                    			'pickupaddress'    		=> $myearnings->pickupaddress ,
                    			'pickuplat'        		=> $myearnings->pickuplat,
                    			'pickuplong'       		=> $myearnings->pickuplong,
                    			'dropaddress'      		=> $myearnings->dropaddress,
                    			'droplat'          		=> $myearnings->droplat,
                    			'droplong'         		=> $myearnings->droplong,
                    			'book_type'        		=> $myearnings->book_type,
                    			'car_category'     		=> $myearnings->car_category,
                    			'vichle_type'      		=> $myearnings->vichle_type,
                    			'distance'         		=> $myearnings->distance,
                    			'duration'         		=> $myearnings->duration,
                    			'note'             		=> $myearnings->note,
                    			'dispatchfees'     		=> $myearnings->dispatchfees,
                    			'supplier_partner_tax' 	=> $myearnings->supplier_partner_tax,
                    			'candytechmarkup'  		=> $myearnings->candytechmarkup,
                    			'candytechtax'	   		=> $myearnings->candytechtax,	
                  				'surge'            		=> $myearnings->surge,
                    			'adminfees'        		=> $myearnings->adminfees,
                    			'gateway_fee'      		=> $myearnings->gateway_fee,	
                    			'driverfees'       		=> $myearnings->driverfees,
                    			'totalfare'        		=> $myearnings->totalfare,
                    			'fullname'         		=> $myearnings->fullname,
                    			'mobile'           		=> $myearnings->mobile,
                    			'passport'         		=> $myearnings->passport,
                    			'email'            		=> $myearnings->email,
                    			'driver_eta'       		=> $myearnings->driver_eta,
                 				'paymentmode'      		=> $myearnings->paymentmode,
                    			'waiting_time_charge' 	=> $myearnings->waiting_time_charge,
                    			'waiting_time' 			=> $myearnings->waiting_time,
                    			'payment_token_id' 		=> $myearnings->payment_token_id,
                    			'currency'          	=> $myearnings->currency,
                    			'book_date'				=> date('d m Y  H:i:s',strtotime($myearnings->book_date)),
                    			'book_datetime'			=> date('d m Y  H:i:s',strtotime($myearnings->book_datetime)),
                    			'ride_status'			=> $myearnings->ride_status,
                    			'end_time'				=> $myearnings->end_time,
                    			'driver_arrivaltime'	=> $myearnings->driver_arrivaltime
					);     	
          }

          if($id=='')
          {
            $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
          }
      
          else if($myearning != NULL)
          {
            $this->response(['responsecode' => 1,
            				 'mytrip'=>$alltripdetail,
            				 'totalwalletsums' => number_format(round($totalwalletsums,2),2),
            				 'response' => 'Success!'], REST_Controller::HTTP_OK); 
          }
          else
          {
            $myearning =[];
            $this->response(['responsecode' => 0,'mytrip'=>$myearning,'response' => 'Fail'], REST_Controller::HTTP_OK);
          }
        }
       
  public function driver_rating_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $driver_id = $getdetails->{'driver_id'};
          
    $myratings = $this->service_m->getVal("SELECT AVG(rate) as driverratings FROM driverrating dr, members m WHERE dr.driver_id = '".$driver_id."' and m.org_id = dr.driver_id and m.usertype_id = 4");

    $mycancellations = $this->service_m->getVal("SELECT count(id) FROM ride WHERE 
      driver_id = '".$driver_id."' and ride_status = 2");

    $myacceptances = $this->service_m->getVal("SELECT count(id) FROM ride WHERE 
      driver_id = '".$driver_id."' and ride_status = 4");

    $mytotal = $this->service_m->getVal("SELECT count(id) FROM ride WHERE 
      driver_id = '".$driver_id."'");

    $mycancellation = number_format(($mycancellations / $mytotal * 100),2);

    $myacceptance = number_format(($myacceptances / $mytotal * 100),2);

    if(!empty($myratings))
    {
      $myrating = number_format($myratings,2);
    }
    else if($myratings == NULL)
    {
      $myratingss = 5.00;
      $myrating = number_format($myratingss,2);
    }
    
    if($driver_id == '')
    {
      $this->response(['responsecode' => 0,'response' => 'Please enter id'], 
        REST_Controller::HTTP_OK);
    } 
    else if(($mycancellation != NULL) || ($myacceptance != NULL) || ($myrating != NULL))
      {
        $this->response(['responsecode' => 1,'myrating' => $myrating,'mycancellation' => $mycancellation ,'myacceptance' => $myacceptance,'response' => 'Success!'], REST_Controller::HTTP_OK);
      }
    else
    {
      $this->response(['responsecode' => 0,'response' => 'fail'], REST_Controller::HTTP_OK);
    }
  }

       public function aboutus_get($id)
         {
        if($id == 1)
          { $c_id = 5;}
        if($id == 2)
          { $c_id = 1;}
        if($id == 3)
          { $c_id = 15;}
        $myearning=$this->service_m->getRow("SELECT * FROM cms WHERE id = '".$c_id."' and driver_publish_status=1 AND country_id = '".$id."'");
        //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
          if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}
        
      $this->response(['responsecode' => 1,'response' => $myearn], REST_Controller::HTTP_OK);
              
           }
           
         public function faq_get($id)
         {
        $myearning=$this->service_m->getRows("SELECT * FROM faq WHERE driver_publish_status=1 AND country_id = '".$id."'");
       
        if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}  
      $this->response(['responsecode' => 1,'response' => $myearn], REST_Controller::HTTP_OK);
           }
                 public function help_get($id)
        {
        $myearning=$this->service_m->getRows("SELECT * FROM help WHERE driver_publish_status=1 AND country_id = '".$id."'");
        if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}  
       $this->response(['responsecode' => 1,'response' => $myearn], REST_Controller::HTTP_OK);
          }
     //       public function help_get($id)
      //    {
      //    $myearning=$this->service_m->getRow("SELECT * FROM cms WHERE id = 10 AND driver_publish_status=1 AND country_id = '".$id."'");
      //    //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
      //    if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}
       // $this->response(['responsecode' => 1,'response' => $myearn], REST_Controller::HTTP_OK);
     //       }

           public function termandcondition_get($id)
         {
           if($id == 1)
          { $c_id = 8;}
           if($id == 2)
          { $c_id = 13;}
           if($id == 3)
          { $c_id = 18;}
          $myearning=$this->service_m->getRow("SELECT * FROM cms WHERE id = '".$c_id."' and driver_publish_status=1 AND country_id = '".$id."'");
          //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
          if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}
        
      $this->response(['responsecode' => 1,'response' => $myearn], REST_Controller::HTTP_OK);
           }

            public function privacypolicy_get($id)
         {
           if($id == 1)
          { $c_id = 9;}
           if($id == 2)
          { $c_id = 11;}
           if($id == 3)
          { $c_id = 16;}
          $myearning=$this->service_m->getRow("SELECT * FROM cms WHERE  id = '".$c_id."' and driver_publish_status=1 AND country_id = '".$id."'");
          //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
          if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}
        $this->response(['responsecode' => 1,'response' => $myearn], REST_Controller::HTTP_OK);
           }
       
        public function my_trip_filter_post()
         {
         $id =$this->input->post('id');
         $startdate =$this->input->post('startdate');
         $enddate=$this->input->post('enddate');
         
      
         $myearning=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."'  AND ride_status in (2,4) AND book_date BETWEEN '".$startdate."' AND '".$enddate."'");
         //$prebook=$this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$id."' AND book_type = 2 AND ride_status = 0");
         if($myearning != NULL){$myearn = $myearning; }else{$myearn =[];}
        if($id=='')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
        }
               else
                {
        $this->response(['responsecode' => 1,'mytrip'=>$myearn,'response' => 'Success!'], REST_Controller::HTTP_OK);
                }
       }
       
       // ride details get after ride completed //
       public function driver_ride_detail_post()
        {
         $getdetail = file_get_contents("php://input");
         $getdetails = json_decode($getdetail);
         $driver_id = $getdetails->{'driver_id'};
         $ride_id = $getdetails->{'ride_id'};
         $myridedetail = $this->service_m->getRow("SELECT * FROM ride WHERE driver_id = '".$driver_id."'  AND id= '".$ride_id."' AND ride_status = 4");

        if($myridedetail != NULL)
        {
          $bookingdate = date('d m Y  H:i:s',strtotime($myridedetail->book_datetime));
          $this->response(['responsecode' => 1,
                           'myridedetail'=> $myridedetail,
                           'response' => 'Success',
                           'bookingdate' => $bookingdate], REST_Controller::HTTP_OK); 
        }
        else if($myridedetail == NULL)
        {
          $this->response(['responsecode' => 0,'myridedetail' => [],'response' => 'Fail'], REST_Controller::HTTP_OK);
        }
        else if($driver_id == '')
        {
          $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
        }
      }

        public function zonearea_get($id)
      {  
        $getdata=$this->service_m->getRows("SELECT * FROM zone WHERE type = 2 and country_id = '".$id."'");
       //$data=[];
     foreach($getdata as $getdatai){
       $getitemdata=$this->service_m->getRows("SELECT * FROM zonelatlng WHERE zone_id = '".$getdatai->id."'");
       if($getitemdata != NULL)
            {
      $getdata =$getitemdata; 
        }else{$getdata = array();}
       $response[] = array(
                "id"  =>$getdatai->id,
                "zonename"=>$getdatai->zonename,
                "address"=>$getdatai->address,
                "surge"=>$getdatai->surge,
              "latlng" =>$getdata
                 );
                }

      if($response != NULL)
            {
        $this->response(['responsecode' => 1,'response' => $response], REST_Controller::HTTP_OK);
        }
           else
            {
            $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
            }
      } 
 
    public function quezoneride_get($id,$dp)
     {  
       $getdata=$this->service_m->getRows("SELECT z.*,ad.dispatch_id as dispatch FROM zone z,assignque_dp ad WHERE z.type = 3 and z.country_id = '".$id. "' and ad.dispatch_id = '".$dp. "' and z.que_status= 0 ");
      foreach($getdata as $getdatai){
     $getitemdata=$this->service_m->getRows("SELECT * FROM zonelatlng WHERE zone_id = '".$getdatai->id."'");
       if($getitemdata != NULL)
            {
     $getdata =$getitemdata; 
        }else{$getdata = array();}
       $response[] = array(
                "id" =>$getdatai->id,
                "dispatch_id" =>$getdatai->dispatch,
                "zonename"=>$getdatai->zonename,
                "address"=>$getdatai->address,
                "latlng" =>$getdata
                );
          }

      if($response != NULL)
            {
        $this->response(['responsecode' => 1,'response' => $response], REST_Controller::HTTP_OK);
        }
           else
            {
            $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
            }
      } 

 public function driverquezonecount_get($driver_id)
        {

        $vcategory = $this->service_m->getRow('select carcategory,vehicle_type,quezone_id from driver_details where id='.$driver_id.'');  
          
      $getdata=$this->service_m->getVal("SELECT que_number FROM driver_details  WHERE id='".$driver_id."' and activestatus = 5 and quezone_id='".$vcategory->quezone_id."' and carcategory='".$vcategory->carcategory."' and vehicle_type='".$vcategory->vehicle_type."'"); 

      //$sql = $this->db->last_query();

      $totalcount = $this->service_m->getVal("SELECT count(*) from driver_details where activestatus=5 and quezone_id='".$vcategory->quezone_id."' and carcategory='".$vcategory->carcategory."' and vehicle_type='".$vcategory->vehicle_type."'");
      
     $sql = $totalcount;
                     
      
      if(!empty($getdata))
            {
      $this->response(['responsecode' => 1,'response' => $getdata,'totalcount' => $totalcount], REST_Controller::HTTP_OK);
          }
    else
           {
          $this->response(['responsecode' => 0,'response' => "Data not found",'query'=>$sql], REST_Controller::HTTP_OK);
           }
       }

       public function quezone_fare_update_post()
       {
          $dispatch_id = $this->input->post('dispatch_id');
          $book_type = $this->input->post('book_type');
          $driver_id = $this->input->post('driver_id');
          $ride_id = $this->input->post('ride_id');

          $car_category = $this->service_m->getRow('SELECT dd.carcategory,dd.vehicle_type FROM members m, driver_details dd WHERE m.org_id = dd.id and m.usertype_id =  4 and  dd.id = '.$driver_id.'');
          
          $getridepaydata = $this->service_m->getRow('SELECT * FROM ride_pay WHERE driver_id = 0 AND book_type = 3 AND ride_status = 0 and pre_status = 0 AND car_category='.$car_category->carcategory.' AND vichle_type='.$car_category->vehicle_type.' and dispatch_id='.$dispatch_id.'  ORDER BY id DESC');

          $ridedata = array(
                    'clientfees'   => $getridepaydata->clientfees,
                    'dispatchfees' => $getridepaydata->dispatchfees,
                    'adminfees'    => $getridepaydata->adminfees,
                    'gateway_fee'  => $getridepaydata->gateway_fee,
                    'tax_fees'     => $getridepaydata->tax_fees,
                    'prebooking_fee' => $getridepaydata->prebooking_fee,
                    'managerfee'     => $getridepaydata->managerfee,
                    'driverfees'   => $getridepaydata->driverfees,
                    'royality_fees'  => $getridepaydata->royality_fees,
                    'surge'      => $getridepaydata->surge
                        
          );

          $where = array('id'=>$ride_id);
          $updateride = $this->service_m->update('ride',$where,$ridedata);

          if($updateride)
          {
            $ridepaydata = array(
                      'pre_status'  => 1
            );
          $where = array('ride_id' => $ride_id);
          $updateridepay = $this->service_m->update('ride_pay',$where,$ridepaydata);
          }

          if(!empty($getridepaydata))
          {
          $this->response(['responsecode' => 1,'response' => $getridepaydata], REST_Controller::HTTP_OK);
          }
          else
          {
          $this->response(['responsecode' => 0,'response' => $getridepaydata], REST_Controller::HTTP_OK); 
          }
       } 

       public function already_assign_afteraccept_ride_get($id,$driverid)
         {
     $rideid =$id;
     $driverid = $driverid;
     $getdriverid = $this->service_m->getRow('SELECT driver_id,book_type FROM ride WHERE id='.$rideid.'');
     
     
         
          if($driverid == $getdriverid->driver_id){
            $this->response(['responsecode' => 1,'ride_status' => 1,'response' => 'This is Your Ride.'], REST_Controller::HTTP_OK);
            }
           else{

            if($getdriverid->book_type == 1 || $getdriverid->book_type == 2 )
            {
               
               $arraydata = array(
                                    'activestatus' => 1
                   );
                   
                   $where = array('id'=>$driverid);
                   $updatauser = $this->service_m->update('driver_details',$where,$arraydata);
               
            $this->response(['responsecode' => 0,'ride_status' => 0,'response' => 'This Ride Already Assigned Other Driver.','query' => $driverid], REST_Controller::HTTP_OK);
           }
           else if($getdriverid->book_type == 3)
            {
               
               $arraydata = array(
                                    'activestatus' => 5
                   );
                   
                   $where = array('id'=>$driverid);
                   $updatauser = $this->service_m->update('driver_details',$where,$arraydata);
               
            $this->response(['responsecode' => 0,'ride_status' => 0,'response' => 'This Ride Already Assigned Other Driver.'], REST_Controller::HTTP_OK);
           }
      } 
  }

      public function vehicle_category_get()
      {
            $getdata = $this->service_m->getRows("SELECT id,name FROM car_category ORDER BY name ASC");
            if(!empty($getdata))
                {
            $this->response(['responsecode' => 1,'response' => $getdata], REST_Controller::HTTP_OK);
              }
            else
                {
                   $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
                }
        } 

        public function vehicle_subtype_post()
        {
          $id = file_get_contents('php://input');
          $ids = json_decode($id);
          $getdata = $this->service_m->getRows("SELECT * FROM vehicle_category WHERE type_id = '".$ids->{'id'}."' order by category_name asc");
          
          if(!empty($getdata))
                {
        $this->response(['responsecode' => 1,'response' => $getdata], REST_Controller::HTTP_OK);
              }
         else
                {
                   $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
                }
        }

        public function get_farekarhoo_post()
      {
        $vehicle_type =$this->input->post('vehicle_type');
        $vehicle_subtype =$this->input->post('vehicle_subtype');
        $origin_latitude =$this->input->post('origin_latitude');
        $origin_longitude =$this->input->post('origin_longitude');
        $destination_latitude =$this->input->post('destination_latitude');
        $destination_longitude =$this->input->post('destination_longitude');
        $pickupaddress =$this->input->post('pickupaddress');
        $dropaddress =$this->input->post('dropaddress');
        $book_type =$this->input->post('book_type');
        $country_id =$this->input->post('country_id');
        $prebookdate_time = $this->input->post('prebookdate_time');


        $gURL = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".$origin_latitude.",".$origin_longitude."&destinations=".$destination_latitude.",".$destination_longitude."&key=AIzaSyBcJqLlwhz6gxHGFclIcVOkExUtxhbg2ek";

        $ch = curl_init($gURL);
        $options = array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => array('Content-type: application/json') 
    );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $response_a = json_decode($result, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    $realdist = explode(' ',$dist);
        $realtime = explode(' ',$time);
        $distance = floatval($realdist[0]);
        $duration = intval($realtime[0]);
        
        if($this->input->post('country_id')==1) 
      {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $book_date= date('d-m-Y H:i:s');
        $book_time= date('H:i');
      } 
      else if($this->input->post('country_id')==2) 
      { date_default_timezone_set('Asia/Singapore');
        $book_date = date('d-m-Y H:i:s');
        $book_time= date('H:i');
      } 
      else 
      {
        date_default_timezone_set('Asia/Brunei');
        $book_date = date('d-m-Y H:i:s');
        $book_time= date('H:i');
      }
    
        if($book_type == 1)
        {
        $getdata=$this->service_m->getRows("SELECT dd.*,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour,( 3959 * acos( cos( radians('".$origin_latitude."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$origin_longitude."') ) + sin( radians('".$origin_latitude."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$book_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category=  '".$vehicle_type."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 1 and m.org_id=dd.id and dd.carcategory =  f.car_category  and f.zone_id =0 and dd.vehicle_type= f.vechile_seater and dd.activestatus=1 GROUP BY dd.dispatch_id HAVING distance < 10  ORDER BY distance");
    }
    else
    {
      $getdata = $this->service_m->getRows("SELECT dd.*,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour, (f.base_fare +f.cost_per_km * '".$distance."' + f.time_per_m * '".$duration."' + f.waiting_time_p_hour * '".$waiting_time."') as total_crocs, ( 3959 * acos( cos( radians('".$origin_latitude."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$origin_longitude."') ) + sin( radians('".$origin_latitude."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$prebookdate_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category=  '".$vehicle_type."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 1 and m.org_id=dd.id and dd.carcategory =  f.car_category  and f.zone_id =0 and dd.vehicle_type= f.vechile_seater and dd.activestatus=1 GROUP BY dd.dispatch_id HAVING distance < 100 ORDER BY MAX(total_crocs) DESC limit 1");
      $sql= $this->db->last_query();
    }
          
     
           $response=[];
           if($getdata != 0){
         foreach($getdata as $getdatai){
         //$getitemdata=$this->service_m->getVal("SELECT ROUND(( ( ".$dist." * cost_per_km + base_fare +  time_per_m * ".$duration." ) + (base_fare + ".$sum." * cost_per_km +  time_per_m * ".$duration.") )) as fare FROM fares  WHERE dispatch_id = '".$getdatai->dispatch_id."' and car_category= '".$vehicle_type."'  and vechile_seater= '".$vehicle_subtype."'");
         $getdp=$this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$getdatai->dispatch_id."' and m.org_id= o.id and m.usertype_id= 3 ");
          //$sql= $this->db->last_query();
         $dispatchfee=$this->admin->getVal("SELECT o.fees FROM members m, organization o WHERE m.org_id = o.id and m.id = '".$getdatai->dispatch_id."'");
         
          $sql11= $this->db->last_query();  
           $adminfee=$this->admin->getRow("SELECT admin_id,admin_fee_charge,gateway_fee,prebooking_fee,other_charges FROM admin_fee WHERE country_id = '".$this->input->post('country_id')."' "); 

           if($vehicle_type == 4){
           $taotal = $getdatai->base_fare + $distance * 2 *  $getdatai->cost_per_km  + $duration * $getdatai->time_per_m;
           }else{
            $taotal = $getdatai->base_fare + $distance *  $getdatai->cost_per_km + $duration * $getdatai->time_per_m;
                }

         //$totalsurge
        if($book_type == 2){
           $prebooking_fee  = $taotal * $adminfee->prebooking_fee / 100;
           $pre_surge_total = $taotal + $prebooking_fee;
          }else{
          //$prebooking_fee = $taotal * $adminfee->prebooking_fee / 100;
          $pre_surge_total = $taotal ;
          }
          $adminfees    = $pre_surge_total * $adminfee->admin_fee_charge / 100;
          $dispatchfees = $pre_surge_total * $dispatchfee / 100;
          $driverfees   = $pre_surge_total - $adminfees;
          $taotaladmin  = $pre_surge_total  + $dispatchfees;              
          $tfees        = $taotaladmin;    
          $taotaltaxfees      = $tfees * $adminfee->other_charges / 100;
          $taotalfees      = $tfees + $taotaltaxfees;
          $taotaladminfees = $adminfees; 

          if($country_id == 1)
          {
            $currency = 'MYR';
          }
          else if($country_id == 2)
          {
            $currency = 'SGD';
          }
          else
          {
            $currency = 'BND';
          }
           
          $response[] = array(
                  "fare"            => $taotalfees,
                  "currency"        => $currency,
                  "dispatch_id"     => $getdatai->dispatch_id,
                  "vehicle_type"    => $vehicle_type,
                  "vehicle_subtype" => $vehicle_subtype,
                  "country_id"      => $country_id,
                  "prebookdate_time"=> $prebookdate_time,
                  "distance"        => $distance,
                  "duartion"        => $duration

                );
              }
          }
         
          if(!empty($response))
               {
         $this->response(['responsecode' => 1,'response' => $response], REST_Controller::HTTP_OK);
             }
        else
               {
             $this->response(['responsecode' => 0,'response' => "Data not found",'result' => $response, 'sql'=>$sql], REST_Controller::HTTP_OK);
               }
          }

          public function karhoo_ridebook_post()
          {
        
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $phone_number = $this->input->post('phone_number');
        $dispatch_id = $this->input->post('dispatch_id');
        $country_id = $this->input->post('country_id'); 
        $book_type = $this->input->post('book_type');
        $vehicle_type = $this->input->post('vehicle_type'); 
        $vehicle_subtype = $this->input->post('vehicle_subtype');
        $distance = $this->input->post('distance');
        $duration = $this->input->post('duration');
        $totalfare = $this->input->post('fare');
        $pickupaddress = $this->input->post('pickupaddress');
        $pickuplat = $this->input->post('pickuplat');
        $pickuplong = $this->input->post('pickuplong');
        $dropaddress = $this->input->post('dropaddress');
        $droplat     = $this->input->post('droplat');
        $droplong    = $this->input->post('droplong');
        $note        = $this->input->post('comment');
        $prebook_date = $this->input->post('prebook_date');
            
            $adminfee=$this->admin->getRow('SELECT admin_id,admin_fee_charge FROM admin_fee WHERE country_id = "'.$this->input->post('country_id').'" ');

      if($country_id == 1) 
      {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $book_date= date('d-m-Y H:i:s');
        $book_time= date('H:i');
        $book_date1= date('d-m-Y');
      } 
      else if($country_id == 2) 
      { 
        date_default_timezone_set('Asia/Singapore');
        $book_date = date('d-m-Y H:i:s');
        $book_time= date('H:i');
        $book_date1= date('d-m-Y');
      } 
      else 
      {
        date_default_timezone_set('Asia/Brunei');
        $book_date = date('d-m-Y H:i:s');
        $book_time = date('H:i');
        $book_date1= date('d-m-Y');
      }

      if($book_type == 1)
      {
        $prebook_date = $book_date;
      }
      
      $getdata=$this->service_m->getRow("SELECT dd.*,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour FROM members m, driver_details dd,fares f  WHERE '".$book_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = ".$dispatch_id."   and f.car_category=  '".$vehicle_type."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 1 and m.org_id=dd.id and dd.carcategory =  f.car_category  and f.zone_id =0 and dd.vehicle_type= f.vechile_seater and dd.activestatus=1");
      
      if($getdata != 0){
      
      $getdp=$this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$getdata->dispatch_id."' and m.org_id= o.id and m.usertype_id= 3 ");
      $dispatchfee=$this->admin->getVal("SELECT o.fees FROM members m, organization o WHERE m.org_id = o.id and m.id = '".$getdata->dispatch_id."'");
      $adminfee=$this->admin->getRow("SELECT admin_id,admin_fee_charge,gateway_fee,prebooking_fee,other_charges FROM admin_fee WHERE country_id = '".$country_id."' "); 
      
      if($vehicle_type == 4){
           $taotal = $getdata->base_fare + $distance * 2 *  $getdata->cost_per_km  + $this->input->post('duration') * $getdata->time_per_m;
           }else{
            $taotal = $getdata->base_fare + $distance *  $getdata->cost_per_km + $this->input->post('duration') * $getdata->time_per_m;
                }
               
            if($book_type == 2){
           $prebooking_fee  = $taotal * $adminfee->prebooking_fee / 100;
           $pre_surge_total = $taotal + $prebooking_fee;
          }else{
          //$prebooking_fee = $taotal * $adminfee->prebooking_fee / 100;
          $pre_surge_total = $taotal;
          }
          $adminfees       = $pre_surge_total * $adminfee->admin_fee_charge / 100;
          $dispatchfees    = $pre_surge_total * $dispatchfee / 100;
          $driverfees      = $pre_surge_total - $adminfees;
          $taotaladmin     = $pre_surge_total  + $dispatchfees;            
          $tfees           = $taotaladmin;       
          $taotaltaxfees   =  $tfees * $adminfee->other_charges / 100;
          $taotalfees      = $tfees + $taotaltaxfees;
          $taotaladminfees = $adminfees; 
           
           $driverarrival = mt_rand(5,10); 
               $array = array(
                        'dispatch_id'      => $this->input->post('dispatch_id'),
                        'manager_id'       => 0,
                        'admin_id'         => $adminfee->admin_id,
                        'client_id'        => 0,  
                        'user_id'          => 0,
                        'country_id'       => $this->input->post('country_id'),
                        'pickupaddress'    => $this->input->post('pickupaddress') ,
                        'pickuplat'        => $this->input->post('pickuplat'),
                        'pickuplong'       => $this->input->post('pickuplong'),
                        'dropaddress'      => $this->input->post('dropaddress'),
                        'droplat'          => $this->input->post('droplat') ,
                        'droplong'         => $this->input->post('droplong'),
                        'book_type'        => $this->input->post('book_type'),
                        'car_category'     => $this->input->post('vehicle_type'),
                        'vichle_type'      => $this->input->post('vehicle_subtype'),
                        'distance'         => $this->input->post('distance'),
                        'duration'         => $this->input->post('duration'),
                        'note'             => $this->input->post('comment'),
                        'dispatchfees'     => $dispatchfees,
                        'royality_fees'    => 0,
                        "surge"            => 0,
                        'adminfees'        => $adminfees,
                        'gateway_fee'      => 0,
                        'tax_fees'         => $taotaltaxfees, 
                        'driverfees'       => $driverfees,
                        'totalfare'        => $totalfare,
                        'fullname'         => $first_name.' '.$last_name,
                        'mobile'           => $phone_number,
                        'passport'         => 0,
                        'email'            => 'null',
                        'driver_eta'       => $driverarrival,
                        'paymentmode'      => 0,
                        'waiting_time_charge' => 0,
                        'waiting_time' => 0,
                        'payment_token_id' => 0
                         ); 

                if($this->input->post('book_type') == 1){
                  $array['book_date']=$book_date;
                  $array['book_datetime'] =$book_date;
                }
                
          if($this->input->post('book_type') == 2){
                  $array['book_date']=$this->input->post('prebook_date');
                  $array['book_datetime'] =$book_date;
                 }
                
               $insert = $this->service_m->insert('ride',$array);
           }

         if($book_type == 2)
         {

            $getdata=$this->service_m->getRows("SELECT dd.*,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour, (f.base_fare +f.cost_per_km * '".$distance."' + f.time_per_m * '".$duration."') as total_crocs, ( 3959 * acos( cos( radians('".$pickuplat."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$pickuplong."') ) + sin( radians('".$pickuplat."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$prebook_date."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category=  '".$vehicle_type."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 1 and m.org_id=dd.id and dd.carcategory =  f.car_category  and f.zone_id =0 and dd.vehicle_type= f.vechile_seater and dd.activestatus=1 GROUP BY dd.dispatch_id HAVING distance < 100 ORDER BY MAX(total_crocs)");


            if($getdata != 0){

              foreach($getdata as $getdatai){

                $getdp=$this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$getdatai->dispatch_id."' and m.org_id= o.id and m.usertype_id= 3 ");

                $dispatchfee=$this->admin->getVal("SELECT o.fees FROM members m, organization o WHERE m.org_id = o.id and m.id = '".$getdatai->dispatch_id."'");

                $adminfee=$this->admin->getRow("SELECT admin_id,admin_fee_charge,gateway_fee,prebooking_fee,other_charges FROM admin_fee WHERE country_id = '".$this->input->post('country_id')."' ");

                if($vehicle_type == 4){
                  
                  $taotal = $getdatai->base_fare + $distance * 2 *  $getdatai->cost_per_km  + $this->input->post('duration') * $getdatai->time_per_m;
                }
                else{
                  
                  $taotal = $getdatai->base_fare + $distance *  $getdatai->cost_per_km + $this->input->post('duration') * $getdatai->time_per_m;
                }

                $prebooking_fee  = $taotal * $adminfee->prebooking_fee / 100;
                $pre_surge_total = $taotal + $prebooking_fee;
                $adminfees    = $pre_surge_total * $adminfee->admin_fee_charge / 100;
                $dispatchfees  = $pre_surge_total * $dispatchfee / 100;
                $driverfees    = $pre_surge_total - $adminfees;
                $taotaladmin   = $pre_surge_total  + $dispatchfees;             
                $tfees         = $taotaladmin;      
                $taotaltaxfees = $tfees * $adminfee->other_charges / 100;
                $taotalfees    = $tfees + $taotaltaxfees;
                $taotaladminfees = $adminfees;

                $driverarrival = mt_rand(5,10); 
                $array1 = array(
                    'dispatch_id'      => $getdatai->dispatch_id,
                    'ride_id'          => $insert,
                    'manager_id'       => 0,
                    'admin_id'         => $adminfee->admin_id,
                    'client_id'        => 0,
                    'user_id'          => 0,
                    'country_id'       => $this->input->post('country_id'),
                    'pickupaddress'    => $this->input->post('pickupaddress') ,
                    'pickuplat'        => $this->input->post('pickuplat'),
                    'pickuplong'       => $this->input->post('pickuplong'),
                    'dropaddress'      => $this->input->post('dropaddress'),
                    'droplat'          => $this->input->post('droplat') ,
                    'droplong'         => $this->input->post('droplong'),
                    'book_type'        => $this->input->post('book_type'),
                    'car_category'     => $this->input->post('vehicle_type'),
                    'vichle_type'      => $this->input->post('vehicle_subtype'),
                    'distance'         => $this->input->post('distance'),
                    'duration'         => $this->input->post('duration'),
                    'note'             => $note,
                    'dispatchfees'     => $dispatchfees,
                    'royality_fees'    => 0,
                    "surge"            => 0,
                    'adminfees'        => $adminfees,
                    'gateway_fee'      => 0,
                    'tax_fees'       => $taotaltaxfees,
                    'driverfees'       => $driverfees,
                    'totalfare'        => $taotalfees,
                    'fullname'         => $first_name.' '.$last_name,
                    'mobile'           => $phone_number,
                    'passport'         => 0,
                    'email'            => 'null',
                    'driver_eta'       => $driverarrival,
                 // 'book_date'        => $this->input->post('prebook_date'),
                    'paymentmode'      => 0,
                    'waiting_time_charge' => 0,
                    'waiting_time' => 0,
                  //  'payment_token_id' => $this->input->post('payment_token_id') 
                 // 'book_datetime'    => date('d-m-Y h:i:sa');  
                     ); 

            if($this->input->post('book_type') == 1){
              $array1['book_date']=$book_date;
              $array1['book_datetime'] =$book_date;
            }
            if($this->input->post('book_type') == 2){
              $array1['book_date']=$this->input->post('prebook_date');
              $array1['book_datetime'] =$book_date;
             }

             $insert1 = $this->service_m->insert('ride_pay',$array1);
              }
            }

         }
           
           if($insert)
           {
            if($book_type == 1 || $book_type == 2)
            {
            

              $autoinstantbooking=$this->admin->getRows('SELECT m.device_id,m.device_type FROM members m, driver_details dd WHERE m.org_id = dd.id and dd.activestatus=1 and  dd.dispatch_id ='.$this->input->post('dispatch_id').''); 

              $sqlcheck = $this->db->last_query();            
              foreach($autoinstantbooking as $autoinstantbookingi){
              $device_id=$autoinstantbookingi->device_id;
              // $device_id= 'ens3hXa8Xo0:APA91bEcGxhbb5MxGSswTQNn7gSG3xAwyuDU9ErUsgpUiNK8U42gwyoeoz4iJRm3jaeh9ttO-LK_jG2oT8hQiqGvNeOzsA-JOTgpGtpXkLCZnGEgt8_8KAYqmf9DmyXQh8aLR6vhF4cV';
               $fcmMsg = array(
               'body' => 'You have recieved auto booking_'.$insert.'_'.$this->input->post('book_type').'_'.$prebook_date.'_'.$this->input->post('pickupaddress').'_'. $first_name.' '.$last_name,
               'title' => '1cabbi Booking',
               'sound' => "default",
               'color' => "#203E78",
               'category' => "oneCabbiDriver" 
               );
       
            if($autoinstantbookingi->device_type == 1) 
            {
            $fcmFields = array(
              'to'   => $autoinstantbookingi->device_id,
              'data' => $fcmMsg
              );
            }else{
              $fcmFields = array(
              'to'   => $autoinstantbookingi->device_id,
              'notification' => $fcmMsg,
              'priority'=>'high'
              );
            }

            $headers = array(
              'Authorization: key='.API_ACCESS_KEY,
              'Content-Type: application/json'
               );
             
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
                }
              }
           }
           
           if($insert)
                {
          $this->response(['responsecode' => 1,'id' => $insert,'response' => "Sccuess"], REST_Controller::HTTP_OK);
              }
          else
                {
              $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
                }
            }

  public function driver_cancel_ride_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $ride_id = $getdetails->{'ride_id'};
    $reason = $getdetails->{'reason'};
    $status_type = $getdetails->{'status_type'};
    $country_id = $getdetails->{'country_id'};
    $cancel_date_time = $getdetails->{'cancel_date_time'};
       
    if($ride_id == '')
    {
      $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
    }
    elseif($status_type == '')
    {
      $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
    }
    else
    {
      if(!empty($country_id)) 
      {
        $get_timezone = $this->service_m->getVal(' SELECT timezone FROM countries WHERE id='.$country_id.'');
        date_default_timezone_set($get_timezone); 
        $end_time = date('Y-m-d h:i:sa');
      } 
      $date1 = strtotime($ride->book_datetime);  
      $date2 = strtotime($end_time);  
      $diff = abs($date2 - $date1);  
      $years = floor($diff / (365*60*60*24));  
      $months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
      $days = floor(($diff - $years * 365*60*60*24 -  
      $months*30*60*60*24)/ (60*60*24)); 
      $hours = floor(($diff - $years * 365*60*60*24  
              - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
      $minutes = floor(($diff - $years * 365*60*60*24  
                  - $months*30*60*60*24 - $days*60*60*24  
                          - $hours*60*60)/ 60);    
      $adminfees = '0.00';
      $totalamount = '0.00';
      $cancellation_charge = '0.00';
      $ride = $this->admin->getRow('SELECT id,driver_id,user_id,client_id,book_date,book_datetime,paymentmode,totalfare,book_type,ride_status FROM ride WHERE  id ='.$ride_id.''); 

      if($ride->ride_status == 0)
      {
        $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
        $array5 = array(
                        'client_id'=>$ride->client_id,
                        'ride_id'=>$ride->id,
                        'updated_balance'=>$ride->totalfare, 
                        'status'=>1
                      );
        
        $insert5 = $this->admin->insert('top_up_credit',$array5);
      }
      else
      {
        if($days == '0')
        {
          if($hours == '0')
          {
            if($minutes <= '10')
            {
              $delete = $this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
              $taotal = $ride->totalfare;
              if($ride->paymentmode == '1')
              {
                $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
                if(!empty($ride->user_id))
                {
                  $array3   = array(
                                    'm_id'        => $ride->user_id,
                                    'ride_id'     => $ride->id,
                                    'amount'      => $ride->totalfare, 
                                    'status'      => 1,
                                    'user_type_id'=> 5,
                                  );
                  $insert3 = $this->admin->insert('e_wallet',$array3);
                }
              }
            }
            else
            {
              $delete = $this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
              $taotal = $ride->totalfare;
              if($ride->paymentmode == '1')
              {
                if($ride->book_type == 1)
                {
                  $cancellation_charge  = $ride->totalfare * $adminfee / 100;
                  $adminfees = $cancellation_charge * $adminfee / 100;
                  $driveramount = $cancellation_charge - $adminfees;
                  $totalamount = $ride->totalfare - $cancellation_charge;
                }
                else
                {
                  $cancellation_charge = $ride->totalfare * $adminfee / 100;
                  $adminfees = $cancellation_charge * $adminfee / 100;
                  $driveramount =  $cancellation_charge - $adminfees;
                  $totalamount = $ride->totalfare - $cancellation_charge;
                }
              }
              if($ride->paymentmode == '3')
              {
                if($ride->book_type == 1)
                {
                  $array3   = array(
                                    'm_id'=>$ride->driver_id,
                                    'ride_id'=>$ride->id,
                                    'amount'=>$driveramount, 
                                    'status'=>1,
                                    'user_type_id'=>4,
                                  );
                  $insert3 = $this->admin->insert('e_wallet',$array3);
                  $array4 = array(
                                  'm_id'=>1,
                                  'ride_id'=>$ride->id,
                                  'amount'=>$adminfees, 
                                  'status'=>1,
                                  'user_type_id'=>1,
                                );
                  $insert4 = $this->admin->insert('e_wallet',$array4);
                  if($ride->user_id == 0)
                  {
                    $array5 = array(
                                    'client_id'=>$ride->client_id,
                                    'ride_id'=>$ride->id,
                                    'updated_balance'=>$totalamount, 
                                    'status'=>1
                                  );
                    $insert5 = $this->admin->insert('top_up_credit',$array5);
                  }
                  else
                  {
                    $array3  = array(
                                    'm_id'=>$ride->user_id,
                                    'ride_id'=>$ride->id,
                                    'amount'=>$totalamount, 
                                    'status'=>1,
                                    'user_type_id'=>5,
                                  );
                    $insert3 = $this->admin->insert('e_wallet',$array3);
                  }
                }
              }
            }
          }
          else
          {
            $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
            $taotal =$ride->totalfare;
            if($ride->paymentmode == '1')
            {
              if($ride->book_type == 1)
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
              else
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
            }
            if($ride->paymentmode == '3')
            {
              if($ride->book_type == 1)
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
              else
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
              $array3 = array(
                              'm_id'=>$ride->driver_id,
                              'ride_id'=>$ride->id,
                              'amount'=>$driveramount, 
                              'status'=>1,
                              'user_type_id'=>4,
                            );
              $insert3 = $this->admin->insert('e_wallet',$array3);
              $array4 = array(
                              'm_id'=>1,
                              'ride_id'=>$ride->id,
                              'amount'=>$adminfees, 
                              'status'=>1,
                              'user_type_id'=>1,
                            );
              $insert4 = $this->admin->insert('e_wallet',$array4);
              if($ride->user_id == 0)
              {
                $array5 = array(
                                'client_id'=>$ride->client_id,
                                'ride_id'=>$ride->id,
                                'updated_balance'=>$totalamount, 
                                'status'=>1
                              );
                $insert5 = $this->admin->insert('top_up_credit',$array5);
              }
              else
              {
                $array3 = array(
                                'm_id'=>$ride->user_id,
                                'ride_id'=>$ride->id,
                                'amount'=>$totalamount, 
                                'status'=>1,
                                'user_type_id'=>5,
                              );
                $insert3 = $this->admin->insert('e_wallet',$array3);
              }
            }
          }
        }
        else
        {
          $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
          if($ride->paymentmode == '1')
          {
            if($ride->book_type == 1)
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
            else
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
          }
          if($ride->paymentmode == '3')
          {
            if($ride->book_type == 1)
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
            else
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
            $array3 = array(
                            'm_id'=>$ride->driver_id,
                            'ride_id'=>$ride->id,
                            'amount'=>$driveramount, 
                            'status'=>1,
                            'user_type_id'=>4,
                          );
            $insert3 = $this->admin->insert('e_wallet',$array3);
            $array4 = array(
                            'm_id'=>1,
                            'ride_id'=>$ride->id,
                            'amount'=>$adminfees, 
                            'status'=>1,
                            'user_type_id'=>1,
                            );
            $insert4 = $this->admin->insert('e_wallet',$array4);
            if($ride->user_id == 0)
            {
              $array5 = array(
                              'client_id'=>$ride->client_id,
                              'ride_id'=>$ride->id,
                              'updated_balance'=>$totalamount, 
                              'status'=>1
                            );
              $insert5 = $this->admin->insert('top_up_credit',$array5);
            }
            else
            {
              $array3   = array(
                                'm_id'=>$ride->user_id,
                                'ride_id'=>$ride->id,
                                'amount'=>$totalamount, 
                                'status'=>1,
                                'user_type_id'=>5,
                              );
              $insert3 = $this->admin->insert('e_wallet',$array3);
            }
          }
        }
      }
      if($ride->ride_status == 0)
      {
        $array = array(
                      'ride_status'    => 2,
                      'end_time' => $end_time,
                      'cancelled_by' => $this->session->userdata('admin_type')
                      );
        $update=$this->admin->update('ride',array('id'=>$ride_id), $array);
      }
      else
      {
        $array = array(
                        'ride_status'    => 2,
                        'reason'        => $reason,
                        'cancelled_by'   => 4,
                        'refundamount'   => $totalamount,
                        'cancelcharge'   => $cancellation_charge,
                        'cancel_service_fee' => $adminfees,
                        'end_time'       => $end_time,
                        'cancelled_by'   => 4
                      );
        $update=$this->admin->update('ride',array('id'=>$ride_id), $array);
      }
      
      $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);          
      
      $device = $this->admin->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$ride_id.'');    
    
      $fcmMsg = array(
                      'body'  => 'Your booking cancelled',
                      'title' => 'The Candy Booking',
                      'sound' => "default",
                      'color' => "#203E78" 
                    );
   
      if($device->device_type == 1)
      {
        $fcmFields = array(
                            'to'   => $device->token_id,
                            'data' => $fcmMsg
                          );
      }
      else
      {
        $fcmFields = array(
                            'to'   => $device->token_id,
                            'notification' => $fcmMsg,
                            'priority'=>'high'
                          );
      }

      $headers = array(
                        'Authorization: key=' . API_ACCESS_KEY,
                        'Content-Type: application/json'
                      );
 
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
    }
  }

  // prebooing status trip coming here //
  public function driver_prebookingstatus_trip_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $ride_id = trim($getdetails->{'ride_id'});
    $fleet_id = $getdetails->{'fleet_id'};
    $fare_id = $getdetails->{'fare_id'}; 
    $driver_id = $getdetails->{'driver_id'};
    $totalfare = $getdetails->{'totalfare'};
    $clientfees = $getdetails->{'agentfees'};
    $dispatchfees = $getdetails->{'fleetfees'};
    $adminfees = $getdetails->{'adminfees'};
    $prebooking_fee = $getdetails->{'prebooking_fee'};
    $gateway_fee = $getdetails->{'gateway_fee'};
    $driverfees = $getdetails->{'driverfees'};
    $pre_status = $getdetails->{'pre_status'};
        
    $ridefare = $this->service_m->getRow('SELECT totalfare,fareid,pre_status FROM ride r WHERE  id = '.$ride_id.'');     
        
    if($ride_id == '')
    {
      $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
    }
    else
    {
      if($ridefare->fareid == $fare_id)
      {
        $totaladminfees = $adminfees;
      }
      else
      {
        $totaladminfees1 = $ridefare->totalfare - $totalfare;
        $totaladminfees = $totaladminfees1 + $adminfees;
      }
      $array = array(
                      'dispatch_id'       => $fleet_id,
                      'driver_id'         => $driver_id,
                      'clientfees'        => $agentfees,
                      'dispatchfees'      => $fleetfees,
                      'adminfees'         => $totaladminfees,
                      'prebooking_fee'    => $prebooking_fee,
                      'gateway_fee'       => $gateway_fee,
                      'managerfee'        => 0.00,
                      'driverfees'        => $driverfees,
                      'ride_status'       => 0,
                      'pre_status'        => $pre_status,
                    ); 
      
    if($ridefare->pre_status == 1)
    {
      $this->response(['responsecode' => 0,'response' => 'Already assigned this ride!'], REST_Controller::HTTP_OK);
    }
    else
    {
      $update = $this->service_m->update('ride',array('id'=>$ride_id), $array);
    }
    if($update)
    {
      $array1 = array(
                      'pre_status'=>1,
                     ); 
      $update1 = $this->service_m->update('ride_pay',array('ride_id'=>$ride_id), $array1);
          
      $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
    }
  }
}

// // getting the upcoming booking //
public function driver_upcomingbooking_post()
{
  $getdetail = file_get_contents("php://input");
  $getdetails = json_decode($getdetail);
  $driver_id = $getdetails->{'driver_id'};
  $fleet_id = $getdetails->{'fleet_id'};
        
  $prebook = $this->service_m->getRows("SELECT * FROM ride WHERE driver_id = '".$driver_id."' AND book_type = 2 AND ride_status = 0 and pre_status = 1 ORDER BY id DESC");

  $autoprebook = $this->service_m->getRows("SELECT r.* FROM ride r WHERE r.driver_id = 0 AND r.book_type = 2 AND r.ride_status = 0  and pre_status = 1 and r.dispatch_id='".$dispatch_id."' ORDER BY r.id DESC");
         
  if(($prebook != NULL) && ($autoprebook != NULL))
  {
    $this->response(['responsecode' => 1,'prebook' => $prebook, 'autoprebook' => $autoprebook ,'response' => 'Success!'], REST_Controller::HTTP_OK); 
  }
  else if(($prebook == NULL) && ($autoprebook == NULL))
  {
    $this->response(['responsecode' => 0,'prebook' => [], 'autoprebook' => [],'response' => 'Fail!'], REST_Controller::HTTP_OK);
  }
  else if(($prebook == NULL) || ($autoprebook != NULL))
  {
    $this->response(['responsecode' => 1,'prebook' => [], 'autoprebook' => $autoprebook,'response' => 'Success!'], REST_Controller::HTTP_OK); 
  }
  else if(($prebook != NULL) || ($autoprebook == NULL))
  {
    $this->response(['responsecode' => 1,'prebook' => $prebook, 'autoprebook' => [],'response' => 'Success!'], REST_Controller::HTTP_OK);
  }
  else if($driver_id == '')
  {
    $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
  }
  else
        {
        $this->response(['responsecode' => 1,'prebook'=>$prebooked,'preautobook'=>$autoprebooked,'response' => 'Success!'], REST_Controller::HTTP_OK);
        }
        } 

  // getting the status change //
  public function driver_prebookingstatus_change_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $ride_id = trim($getdetails->{'ride_id'});
    $pre_status = $getdetails->{'pre_status'};
      
    $array = array(
                    'ride_status'=>0,
                    'pre_status'=>$pre_status,
                  );
    $update = $this->service_m->update('ride',array('id'=>$ride_id), $array);
    if($update)
    {
      $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
    }
    else
    {
      $this->response(['responsecode' => 0,'response' => 'Fail!'], REST_Controller::HTTP_OK);
    }
  }

  // cancel the booking so that its go back in prebooking jobs //
  public function driver_prebookingstatus_cancel_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $ride_id = trim($getdetails->{'ride_id'});
    $pre_status = $getdetails->{'pre_status'};

    $array = array(
                  'ride_status'=>0,
                  'pre_status'=>$pre_status,
                  );

    $update1 = $this->service_m->update('ride',array('id'=>$ride_id), $array);
    $update = $this->service_m->update('ride_pay',array('ride_id'=>$ride_id), $array);
    $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
  }

  // getting the driver blocked when admin block it //
  public function driver_blockcheck_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $driver_id  = $getdetails->{'driver_id '};
    $member = $this->service_m->getVal('SELECT status FROM driver_details WHERE id = "'.$driver_id.'"'); 
          if($member == 2)
          {
          $this->response([
                 'responsecode' => 1,
                 'response'     => $member
                         ], REST_Controller::HTTP_OK);
          }
         else
          {
          $this->response([
                 'responsecode' => 0,
                 'response'     => []
                          ], REST_Controller::HTTP_OK);
          } 
    }

    // checking the rating of driver below 3.5 //
    public function driver_rating_check_post()
    {
      $getdetail = file_get_contents("php://input");
      $getdetails = json_decode($getdetail);
      $driver_id = $getdetails->{'driver_id'};
      $status_type = $getdetails->{'status_type'};
      $reason = $getdetails->{'reason'};
       
      if($driver_id == '')
      {
        $this->response(['responsecode' => 0,'response' => 'Please enter Driver id'], REST_Controller::HTTP_OK);
      }
      else
      {
        $arr   = array('status'=>$status_type,
                       'reason'=>$reason
                       );
         $where = array('id'=>$driver_id);
         $update = $this->service_m->update('driver_details', $where, $arr);    
         $this->response(['responsecode' => 1,'status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);
          }
    }

    // already assign ride check //
    public function driver_alreadyassign_ride_post()
    {
      $getdetail = file_get_contents("php://input");
      $getdetails = json_decode($getdetail);
      $ride_id = $getdetails->{'ride_id'};
      
      $ride_status = $this->admin->getVal('SELECT ride_status FROM ride  WHERE  id = '.$ride_id.'');
      if($ride_status == 1 || $ride_status == 2 || $ride_status == 3 || $ride_status == 4)
      {
        if($ride_status == 2)
        {
          $this->response(['responsecode' => 1,'ride_status' => $ride_status,'response' => 'This Ride Cancelled.'], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(['responsecode' => 1,'ride_status' => $ride_status,'response' => 'This Ride Already Assigned Other Driver'], REST_Controller::HTTP_OK);
        }
      }
      else if($ride_id == '')
      {
        $this->response(['responsecode' => 0,'ride_status' => 0,'response' => 'Please enter ride id'], REST_Controller::HTTP_OK);
      }
    }

    // getting driver profile //
    public function driver_profileget_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail);
    $driver_id = $getdetails->{'driver_id'};
      
      $checkid = $this->service_m->getRow("select m.*,dd.dispatch_id,dd.fname,dd.lname,dd.address1,dd.postcode,dd.state,dd.country,dd.plate_no,dd.carcategory,dd.vehicle_type,dd.year_of_reg,dd.bank_name,dd.ac_name,dd.ac_type,dd.ac_no,dd.emg_person,dd.emg_contact,dd.profile_photo,dd.lc_photo,dd.insurance_photo,dd.vocational_license,dd.vocational_license_expiry,dd.vehicle_permit,dd.vehicle_permit_expiry,dd.vehicle_insurance_expiry,dd.driving_license_expiry,dd.activestatus,dd.alt_emg_contact,dd.varification,dd.status,dd.currency from members m, driver_details dd  where dd.id = m.org_id and m.usertype_id = 4 and dd.id = '".$driver_id."'");

      $rating = $this->service_m->getVal('select  CAST(AVG(rate) AS DECIMAL(10,2))  from driverrating where driver_id="'.$driver_id.'" order by id desc');
      
      if(!empty($checkid))
      { 
          $alldetail[] = array(
                                  'id'                => $checkid->org_id,  
                                  'firstname'         => $checkid->fname,
                                  'lastname'          => $checkid->lname,
                                  'fleetid'           => $checkid->dispatch_id,
                                  'address'           => $checkid->address1,
                                  'postcode'          => $checkid->postcode,
                                  'state'             => $checkid->state,
                                  'country'           => $checkid->country,
                                  'mobile'            => $checkid->mobile,
                                  'email'             => $checkid->email,
                                  'mobile'            => $checkid->mobile_no,
                                  'plate_no'          => $checkid->plate_no,
                                  'carcategory'       => $checkid->carcategory,
                                  'vehicletype'       => $checkid->vehicle_type,
                                  'yearofreg'         => $checkid->year_of_reg,
                                  'bankname'          => $checkid->bank_name,
                                  'accountno'         => $checkid->ac_no,
                                  'emergencypserson'  => $checkid->emg_person,
                                  'emergencycontact'  => $checkid->emg_contact,
                                  'profilephoto'      => $checkid->profile_photo,
                                  'licensephoto'      => $checkid->lc_photo,
                                  'insurancephoto'    => $checkid->insurance_photo,
                                  'vocationallicense' => $checkid->vocational_license,
                                  'vcexpiry'          => $checkid->vocational_license_expiry,
                                  'vehiclepermit'     => $checkid->vehicle_permit,
                                  'vinsuranceexpiry'  => $checkid->vehicle_insurance_expiry,
                                  'drivinglicense'    => $checkid->driving_license_expiry,
                                  'activestatus'      => $checkid->activestatus,
                                  'currency'          => $checkid->currency

          );
        }

     if(!empty($checkid))
        { 
        if($rating != NULL)
        { 
          $myearn = number_format($rating,2); 
        }
        else
        {
          $myearn =number_format(5.00,2);
        }
        $this->response(['responsecode' => 1,"baseurl" => base_url().'uploads/driver/','response' => $alldetail,'ratings' => $myearn], REST_Controller::HTTP_OK);
      }
    else
        {
          $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
        }
  }

  // driver get ewallet details of my wallet //
  public function driver_ewallet_post()
  {
      $getdetail = file_get_contents("php://input");
      $getdetails = json_decode($getdetail);
      $driver_id = $getdetails->{'driver_id'};
        
        $totalwalletsums = $this->service_m->getVal("SELECT SUM(ew.amount) FROM e_wallet ew,ride r WHERE ew.m_id = ".$driver_id." AND ew.status = 1 AND ew.cronstatus = 0 AND ew.user_type_id = 4 AND r.id = ew.ride_id AND r.ride_status = 4 GROUP BY ew.m_id");

        $getridedetails = $this->service_m->getRows("SELECT r.id r_id,r.book_type,r.car_category,r.driverfees,r.fullname,ew.* FROM ride r,e_wallet ew WHERE r.ride_status = 4 AND ew.ride_id = r.id AND ew.m_id = '".$driver_id."' ORDER BY r.id DESC");

        if(!empty($getridedetails))
        {
          foreach($getridedetails as $getridedetailsi)
          $ridedetails[] =  array(
                  'rideid'        => $getridedetailsi->r_id,
                  'booktype'      => $getridedetailsi->book_type,
                  'carcategory'   => $getridedetailsi->car_category,
                  'driverfees'    => number_format(round($getridedetailsi->driverfees,2),2),
                  'fullname'    => $getridedetailsi->fullname   
                   );  
        }

        if($totalwalletsums > 0)
        {
          $totalwalletsum = number_format(round($totalwalletsums,2),2); 
        }
        else
        {
          $totalwalletsum =number_format(0.00,2);
        }
        if($driver_id == '')
        {
        $this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
        }
        else
        {
          $this->response(['responsecode' => 1,'totalwalletsum' => $totalwalletsum,'ridedetails'=>$ridedetails,'response' => 'Success!'], REST_Controller::HTTP_OK);
        }
    }

    // updatting the driver profile //
    public function driver_updateprofile_post()
    {
      $getdetail                  = file_get_contents("php://input");
      $getdetails                 = json_decode($getdetail);
      $driver_id                  = $getdetails->{'driver_id'}; 
      $fleet                      = $getdetails->{'fleet_id'};
      $fname                      = $getdetails->{'fname'};
      $lname                      = $getdetails->{'lname'};
      $country                    = $getdetails->{'country_id'};
      $currency                   = $getdetails->{'currency'};
      $state                      = $getdetails->{'state'};
      $postcode                   = $getdetails->{'postcode'};
      $address1                   = $getdetails->{'address'};
      $email                      = $getdetails->{'email'};
      $mobile_no                  = $getdetails->{'mobile_no'};
      $plate_no                   = $getdetails->{'plate_no'};
      $vehicle_type               = $getdetails->{'vehicle_type'};
      $vehicle_category           = $getdetails->{'vehicle_category'};
      $year_of_reg                = $getdetails->{'year_of_reg'};
      $bank_name                  = $getdetails->{'bank_name'};
      $ac_name                    = $getdetails->{'ac_name'};
      $ac_no                      = $getdetails->{'ac_no'};
      $emg_person                 = $getdetails->{'emg_person'};
      $emg_contact                = $getdetails->{'emg_contact'};
      $alternate_emg_num          = $getdetails->{'alt_emg_contact'};
      $device_id                  = $getdetails->{'device_id'};
      $device_type                = $getdetails->{'device_type'};

      $device = $this->admin->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m,driver_details dd WHERE  m.usertype_id = 4 and dd.id = '.$driver_id.' AND m.org_id = dd.id'); 
      
      $fcmMsg = array(
                      'body' => 'Your profile updated successfully',
                      'title' => 'The Candy Booking',
                      'sound' => "default",
                      'color' => "#203E78" 
                      );
   
      if($device->device_type == 1)
      {
        $fcmFields = array(
                            'to'   => $device->token_id,
                            'data' => $fcmMsg
                          );
      }
      else
      {
        $fcmFields = array(
                            'to'   => $device->token_id,
                            'notification' => $fcmMsg,
                            'priority'=>'high'
                          );
      }

      $headers = array(
                        'Authorization: key=' . API_ACCESS_KEY,
                        'Content-Type: application/json'
                      );
 
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
    
       /*  Validation of keys */
        if($fname == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter first name'], REST_Controller::HTTP_OK);
        }
        elseif($lname == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter last name'], REST_Controller::HTTP_OK);
        }
        elseif($country == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select country'], REST_Controller::HTTP_OK);
        }
        elseif($state == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select state'], REST_Controller::HTTP_OK);
        }
        elseif($postcode == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter postcode'], REST_Controller::HTTP_OK);
        }  
        elseif($address1 == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter address'], REST_Controller::HTTP_OK);
        } 
        elseif($email == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter email'], REST_Controller::HTTP_OK);
        } 
        elseif($mobile_no == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter mobile no'], REST_Controller::HTTP_OK);
        }  
        elseif($plate_no == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter plate no.'], REST_Controller::HTTP_OK);
        }
        elseif($vehicle_type == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select vehicle type'], REST_Controller::HTTP_OK);
        }
        elseif($vehicle_category == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please select vehicle category'], REST_Controller::HTTP_OK);
        }
        elseif($year_of_reg == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter year of registration'], REST_Controller::HTTP_OK);
        }   
        elseif($bank_name == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter bank name'], REST_Controller::HTTP_OK);
        }
        elseif($ac_name == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter account name'], REST_Controller::HTTP_OK);
        }
        elseif($ac_no == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter account number'], REST_Controller::HTTP_OK);
        }
        elseif($emg_person == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please emergency person name'], REST_Controller::HTTP_OK);
        } 
        elseif($emg_contact == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter contact number'], REST_Controller::HTTP_OK);
        }
        elseif($alternate_emg_num == '')
        {
         $this->response(['responsecode' => 0,'response' => 'Please enter alternate emergency number'], REST_Controller::HTTP_OK);
        }
        
      else
      {
        $array1 = array(
              'fname'                     =>  $fname,
              'lname'                     =>  $lname,
              'dispatch_id'               =>  $fleet,
              'country'                   =>  $country,
              'currency'                  =>  $currency,
              'state'                     =>  $state,
              'postcode'                  =>  $postcode,
              'address1'                  =>  $address1,
              'email'                     =>  $email,
              'mobile'                    =>  $mobile_no,
              'plate_no'                  =>  $plate_no,
              'carcategory'               =>  $vehicle_type,
              'vehicle_type'              =>  $vehicle_category,
              'year_of_reg'               =>  $year_of_reg, 
              'bank_name'                 =>  $bank_name,
              'ac_name'                   =>  $ac_name,
              'ac_no'                     =>  $ac_no,
              'emg_person'                =>  $emg_person,
              'emg_contact'               =>  $emg_contact,
              'alt_emg_contact'           =>  $alternate_emg_num,
              'varification'              =>  0,
              'status'                    =>  2,
              'activestatus'              =>  0          
              );           

      $where1 = array('id'=>$driver_id);
      $update1 = $this->service_m->update('driver_details', $where1, $array1);
      $sql = $this->db->last_query();

      if(!empty($update1))
      {
         $display_name = $fname.' '.$lname; 
         $array = array(
                        'device_id'       => $device_id,
                        'device_type'     => $device_type,
                        'user_name'       => $email,
                        'password'        => $mobile_no,
                        'email'           => $email,
                        'display_name'    => $display_name,
                        'mobile_no'       => $mobile_no
                      );

        $data = array('org_id' => $driver_id,'usertype_id' => 4);
        $where = $data;
        $update = $this->service_m->update('members', $where, $array);
        
      }

      if($update)
      {
          $this->response([
                'responsecode' => 1,
                'response' => 'Your account has been updated successfully.'], REST_Controller::HTTP_OK);
      }
      else
      {
        $this->response([
                'responsecode' => 0,
                'response' => 'Some problems occurred, please try again.',
                'sql1' => $sql], 
                REST_Controller::HTTP_OK);
      } 
    }
  }

  // driver noshowride //
  public function driver_noshowride_post()
  {
      $getdetail = file_get_contents("php://input");
      $getdetails = json_decode($getdetail);
      $ride_id = $getdetails->{'ride_id'};
      $reason = $getdetails->{'reason'};
      $status_type = $getdetails->{'status_type'};
      $country_id = $getdetails->{'country_id'};
      $cancel_date_time = $getdetails->{'cancel_date_time'};
      
      if($ride_id == '')
      {
        $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
      }
      elseif($status_type == '')
      {
        $this->response(['responsecode' => 0,'response' => 'Please enter status'], REST_Controller::HTTP_OK);
      }
      else
      {
        if(!empty($country_id)) 
        {
          $get_timezone = $this->service_m->getVal(' SELECT timezone FROM countries WHERE id='.$country_id.''); 
          date_default_timezone_set($get_timezone);
          $end_time = date('Y-m-d h:i:sa');
        } 
           
      $date1 = strtotime($ride->book_datetime);  
      $date2 = strtotime($end_time);  
      $diff = abs($date2 - $date1);  
      $years = floor($diff / (365*60*60*24));  
      $months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
      $days = floor(($diff - $years * 365*60*60*24 -  
      $months*30*60*60*24)/ (60*60*24)); 
      $hours = floor(($diff - $years * 365*60*60*24  
              - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
      $minutes = floor(($diff - $years * 365*60*60*24  
                  - $months*30*60*60*24 - $days*60*60*24  
                          - $hours*60*60)/ 60);    
      $adminfees = '0.00';
      $totalamount = '0.00';
      $cancellation_charge = '0.00';
      $ride = $this->admin->getRow('SELECT id,driver_id,user_id,client_id,book_date,book_datetime,paymentmode,totalfare,book_type,ride_status FROM ride WHERE  id ='.$ride_id.''); 

      if($ride->ride_status == 0)
      {
        $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
        $array5 = array(
                        'client_id'=>$ride->client_id,
                        'ride_id'=>$ride->id,
                        'updated_balance'=>$ride->totalfare, 
                        'status'=>1
                      );
        
        $insert5 = $this->admin->insert('top_up_credit',$array5);
      }
      else
      {
        if($days == '0')
        {
          if($hours == '0')
          {
            if($minutes <= '10')
            {
              $delete = $this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
              $taotal = $ride->totalfare;
              if($ride->paymentmode == '1')
              {
                $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
                if(!empty($ride->user_id))
                {
                  $array3   = array(
                                    'm_id'        => $ride->user_id,
                                    'ride_id'     => $ride->id,
                                    'amount'      => $ride->totalfare, 
                                    'status'      => 1,
                                    'user_type_id'=> 5,
                                  );
                  $insert3 = $this->admin->insert('e_wallet',$array3);
                }
              }
            }
            else
            {
              $delete = $this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
              $taotal = $ride->totalfare;
              if($ride->paymentmode == '1')
              {
                if($ride->book_type == 1)
                {
                  $cancellation_charge  = $ride->totalfare * $adminfee / 100;
                  $adminfees = $cancellation_charge * $adminfee / 100;
                  $driveramount = $cancellation_charge - $adminfees;
                  $totalamount = $ride->totalfare - $cancellation_charge;
                }
                else
                {
                  $cancellation_charge = $ride->totalfare * $adminfee / 100;
                  $adminfees = $cancellation_charge * $adminfee / 100;
                  $driveramount =  $cancellation_charge - $adminfees;
                  $totalamount = $ride->totalfare - $cancellation_charge;
                }
              }
              if($ride->paymentmode == '3')
              {
                if($ride->book_type == 1)
                {
                  $array3   = array(
                                    'm_id'=>$ride->driver_id,
                                    'ride_id'=>$ride->id,
                                    'amount'=>$driveramount, 
                                    'status'=>1,
                                    'user_type_id'=>4,
                                  );
                  $insert3 = $this->admin->insert('e_wallet',$array3);
                  $array4 = array(
                                  'm_id'=>1,
                                  'ride_id'=>$ride->id,
                                  'amount'=>$adminfees, 
                                  'status'=>1,
                                  'user_type_id'=>1,
                                );
                  $insert4 = $this->admin->insert('e_wallet',$array4);
                  if($ride->user_id == 0)
                  {
                    $array5 = array(
                                    'client_id'=>$ride->client_id,
                                    'ride_id'=>$ride->id,
                                    'updated_balance'=>$totalamount, 
                                    'status'=>1
                                  );
                    $insert5 = $this->admin->insert('top_up_credit',$array5);
                  }
                  else
                  {
                    $array3  = array(
                                    'm_id'=>$ride->user_id,
                                    'ride_id'=>$ride->id,
                                    'amount'=>$totalamount, 
                                    'status'=>1,
                                    'user_type_id'=>5,
                                  );
                    $insert3 = $this->admin->insert('e_wallet',$array3);
                  }
                }
              }
            }
          }
          else
          {
            $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
            $taotal =$ride->totalfare;
            if($ride->paymentmode == '1')
            {
              if($ride->book_type == 1)
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
              else
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
            }
            if($ride->paymentmode == '3')
            {
              if($ride->book_type == 1)
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
              else
              {
                $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
                $adminfees  =  $cancellation_charge * $adminfee / 100;
                $driveramount =  $cancellation_charge - $adminfees;
                $totalamount = $ride->totalfare - $cancellation_charge;
              }
              $array3 = array(
                              'm_id'=>$ride->driver_id,
                              'ride_id'=>$ride->id,
                              'amount'=>$driveramount, 
                              'status'=>1,
                              'user_type_id'=>4,
                            );
              $insert3 = $this->admin->insert('e_wallet',$array3);
              $array4 = array(
                              'm_id'=>1,
                              'ride_id'=>$ride->id,
                              'amount'=>$adminfees, 
                              'status'=>1,
                              'user_type_id'=>1,
                            );
              $insert4 = $this->admin->insert('e_wallet',$array4);
              if($ride->user_id == 0)
              {
                $array5 = array(
                                'client_id'=>$ride->client_id,
                                'ride_id'=>$ride->id,
                                'updated_balance'=>$totalamount, 
                                'status'=>1
                              );
                $insert5 = $this->admin->insert('top_up_credit',$array5);
              }
              else
              {
                $array3 = array(
                                'm_id'=>$ride->user_id,
                                'ride_id'=>$ride->id,
                                'amount'=>$totalamount, 
                                'status'=>1,
                                'user_type_id'=>5,
                              );
                $insert3 = $this->admin->insert('e_wallet',$array3);
              }
            }
          }
        }
        else
        {
          $delete=$this->admin->deleteAll('e_wallet',array('ride_id'=>$ride->id));
          if($ride->paymentmode == '1')
          {
            if($ride->book_type == 1)
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
            else
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
          }
          if($ride->paymentmode == '3')
          {
            if($ride->book_type == 1)
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
            else
            {
              $cancellation_charge  =  $ride->totalfare * $adminfee / 100;
              $adminfees  =  $cancellation_charge * $adminfee / 100;
              $driveramount =  $cancellation_charge - $adminfees;
              $totalamount = $ride->totalfare - $cancellation_charge;
            }
            $array3 = array(
                            'm_id'=>$ride->driver_id,
                            'ride_id'=>$ride->id,
                            'amount'=>$driveramount, 
                            'status'=>1,
                            'user_type_id'=>4,
                          );
            $insert3 = $this->admin->insert('e_wallet',$array3);
            $array4 = array(
                            'm_id'=>1,
                            'ride_id'=>$ride->id,
                            'amount'=>$adminfees, 
                            'status'=>1,
                            'user_type_id'=>1,
                            );
            $insert4 = $this->admin->insert('e_wallet',$array4);
            if($ride->user_id == 0)
            {
              $array5 = array(
                              'client_id'=>$ride->client_id,
                              'ride_id'=>$ride->id,
                              'updated_balance'=>$totalamount, 
                              'status'=>1
                            );
              $insert5 = $this->admin->insert('top_up_credit',$array5);
            }
            else
            {
              $array3   = array(
                                'm_id'=>$ride->user_id,
                                'ride_id'=>$ride->id,
                                'amount'=>$totalamount, 
                                'status'=>1,
                                'user_type_id'=>5,
                              );
              $insert3 = $this->admin->insert('e_wallet',$array3);
            }
          }
        }
      }
      if($ride->ride_status == 0)
      {
        $array = array(
                      'ride_status'    => 5,
                      'end_time' => $end_time,
                      'cancelled_by' => 4
                      );
        $update=$this->admin->update('ride',array('id'=>$ride_id), $array);
      }
      else
      {
        $array = array(
                        'ride_status'    => 5,
                        'reason'        => $reason,
                        'cancelled_by'   => 4,
                        'refundamount'   => $totalamount,
                        'cancelcharge'   => $cancellation_charge,
                        'cancel_service_fee' => $adminfees,
                        'end_time'       => $end_time,
                      );
        $update=$this->admin->update('ride',array('id'=>$ride_id), $array);
      }
      
      $this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!'], REST_Controller::HTTP_OK);          
      
      $device = $this->admin->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m, ride r WHERE  m.usertype_id =  5 and  m.id = r.user_id and r.id = '.$ride_id.'');    
    
      $fcmMsg = array(
                      'body'  => 'Your booking cancelled',
                      'title' => 'The Candy Booking',
                      'sound' => "default",
                      'color' => "#203E78" 
                    );
   
      if($device->device_type == 1)
      {
        $fcmFields = array(
                            'to'   => $device->token_id,
                            'data' => $fcmMsg
                          );
      }
      else
      {
        $fcmFields = array(
                            'to'   => $device->token_id,
                            'notification' => $fcmMsg,
                            'priority'=>'high'
                          );
      }

      $headers = array(
                        'Authorization: key=' . API_ACCESS_KEY,
                        'Content-Type: application/json'
                      );
 
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
    }
  }
  
  public function driver_tolltax_post()
  {
    $getdetail = file_get_contents("php://input");
    $getdetails = json_decode($getdetail); 
    $ride_id = $getdetails->{'ride_id'};
    $amount = $getdetails->{'amount'};
    $driver_payment_mode = $getdetails->{'driver_payment_mode'};
         
    $ride = $this->admin->getRow('SELECT user_id,driver_id FROM ride r WHERE  id = '.$ride_id.'');
       
    if($ride_id == '')
    {
      $this->response(['responsecode' => 0,'response' => 'Please enter Ride id'], REST_Controller::HTTP_OK);
    }
    else
    {
      $arr = array(
                  'tolltaxamount'=>$amount,
                  'driver_payment_mode'=>$driver_payment_mode
                  );
      $where = array('id'=>$ride_id);
      $update = $this->service_m->update('ride', $where, $arr); 

      $this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
    }
  }
}
