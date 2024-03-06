	<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
	require APPPATH . '/libraries/REST_Controller.php';

	
	class Riderapi extends REST_Controller {
	
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
	        
	//	signup rider //
	public function rider_signup_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);  
		$name = $getdetails->{'name'};
		$country_id = $getdetails->{'country_id'};
		$email = $getdetails->{'email'};
		$mobile = $getdetails->{'mobile'};
		$password = $getdetails->{'password'};
			
		if($name == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter name'], REST_Controller::HTTP_OK);
		}
		elseif($mobile == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter mobile'], REST_Controller::HTTP_OK);
		}
		elseif($country_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter country'], REST_Controller::HTTP_OK);
		}
		elseif($email == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter email'], REST_Controller::HTTP_OK);
		}
		elseif($password == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter password'], REST_Controller::HTTP_OK);
		}
		elseif($this->service_m->checkemail($email))
		{
			$this->response(['responsecode' => 0,'response' => 'Your email id is already registered'], REST_Controller::HTTP_OK);
		}
		elseif($this->service_m->checkmobile($mobile))
		{
			$this->response(['responsecode' => 0,'response' => 'Your mobile number already registered'], REST_Controller::HTTP_OK);
		}
		else
		{
		    $image = '';
            if($getdetails->{'user_image'})
            {
                $user_image = $getdetails->{'user_image'};
                $getimage = base64_decode($user_image);
                $getname = getImageSizeFromString($getimage);
                $ext = end(explode("/", $getname['mime']));
                $imgname = $ext[0].substr(bin2hex(random_bytes(16)),0,9).'.'.$ext;
                if(file_put_contents("uploads/rider/".$imgname, $getimage))
                {
                   	copy("uploads/rider/".$imgname,"uploads/rider/resize/".$imgname);
                   	$image = $imgname;
                }
            }
		    
		 	$array = array(
					 		'country_id'		=> $country_id,
					 		'display_name'		=> $name,
					 		'email'				=> $email,
					 		'user_name'			=> $email,
					 		'password'			=> $password,
					 		'mobile_no'			=> $mobile,
					 		'usertype_id'		=> 5
				     		);
				
			if(!empty($image))
          	{
          		$array['user_image'] = $image;
          	}
         		   
		  		//$where = array('id'=>$id);
	      		$insert = $this->service_m->insert('members', $array);  	
	
		  		if($insert)
				{
	      			$this->response(['responsecode' => 1,'response' => 'Your account has been created successfully.'], REST_Controller::HTTP_OK);
				}
				else
				{
		  			$this->response([ 'responsecode' => 0,'response' => 'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
				}	
			}
	} 

	// rider country get //
	public function rider_country_get()
    {
      $countries = $this->service_m->getRows('SELECT countryName,id FROM countries ORDER BY countryName ASC');

      if(!empty($countries))
      {
        $this->response(['responsecode' => 1, 'countries' => $countries], REST_Controller::HTTP_OK);
      }
      else
      {
        $this->response(['responsecode' => 0, 'countries' => $countries], REST_Controller::HTTP_OK);
      }
    }

    // rider login data get //
    public function rider_login_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$email = $getdetails->{'email'};
		$password = $getdetails->{'password'};
		$device_id = $getdetails->{'device_id'};
		$device_type = $getdetails->{'device_type'};
		$token_id = $getdetails->{'token_id'};
		
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
			$checkid = $this->service_m->getRow("select m.* from members m where  m.email='".$email."' and usertype_id = 5");
            
            if($checkid->email != $email)
            {
				$this->response(['responsecode' => 0,'response' => 'Email id is not valid'], REST_Controller::HTTP_OK);
            }
            else if($checkid->usertype_id != 5)
            {
				$this->response(['responsecode' => 0,'response' => 'This Crendential is invalid for mobile app'], REST_Controller::HTTP_OK);
            }
			else if($checkid->password != $password)
			{
			   	$this->response(['responsecode' => 0,'response' => 'Password does not match'], REST_Controller::HTTP_OK);
			}
            else
            {
            	if($checkid->status == 2)
                {
				   $this->response(['responsecode' => 0,'response' => 'You have been blocked for more details contact info@thecandy.com'], REST_Controller::HTTP_OK);
                }
			   	else
			   	{
			      $arr  = array('device_id' => $device_id,'device_type' => $device_type,'token_id' => $token_id);
				  $where = array('id' => $checkid->id);
				  $update = $this->service_m->update('members', $where, $arr);

				  $alldetails =  array(
				  						'riderid'	=> $checkid->id,
				  						'fullname'	=> $checkid->display_name, 
				  						'country'	=> $checkid->country_id,
				  						'email'		=> $checkid->email,
				  						'password'	=> $checkid->password,
				  						'user_image'=> base_url().'uploads/rider/'.$checkid->user_image,
				  						'mobile'    => $checkid->mobile_no	
				  					);   
				  $this->response(['responsecode' => 1,'response' => $alldetails,'userstatus'=>1], REST_Controller::HTTP_OK);
				   }                                           
                 }
		       }
	}

	//	rider profile updated //
	public function rider_updateprofile_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);  
		$rider_id = $getdetails->{'rider_id'};
		$name = $getdetails->{'name'};
		$country_id = $getdetails->{'country_id'};
		$mobile = $getdetails->{'mobile'};
		$email = $getdetails->{'email'};
		$password = $getdetails->{'password'};
		$device = $this->admin->getRow('SELECT device_id,device_type,token_id FROM members WHERE  usertype_id = 5 and  id = '.$rider_id.''); 

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
		
		$email = $getdetails->{'email'};
		if($rider_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
		}
		elseif($name == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter name'], REST_Controller::HTTP_OK);
		}
		elseif($email == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter email'], REST_Controller::HTTP_OK);
		}
		elseif($this->service_m->ucheckemail($email,$rider_id))
		{
			$this->response(['responsecode' => 0,'response' => 'Your email id is already registered'], REST_Controller::HTTP_OK);
		}
		elseif($this->service_m->ucheckmobile($mobile,$rider_id))
		{
			$this->response(['responsecode' => 0,'response' => 'Your mobile no is already registered'], REST_Controller::HTTP_OK);
		}
		else
		{
			$image = '';
            if($getdetails->{'user_image'})
            {
                $user_image = $getdetails->{'user_image'};
                $getimage = base64_decode($user_image);
                $getname = getImageSizeFromString($getimage);
                $ext = end(explode("/", $getname['mime']));
                $imgname = $ext[0].substr(bin2hex(random_bytes(128)),0,10).'.'.$ext;
                if(file_put_contents("uploads/rider/".$imgname, $getimage))
                {
                   	copy("uploads/rider/".$imgname,"uploads/rider/resize/".$imgname);
                   	$image = $imgname;
                }
            }


			$array = array(
					 		'display_name' 	=> $name,
					 		'email' 		=> $email,
					 		'country_id' 	=> $country_id,
					 		'mobile_no'		=> $mobile,
					 		'password'		=> $password
				       		);

			if(!empty($image))
          	{
          		$array['user_image'] = $image;
          	}

          				   
			$where = array('id'=>$rider_id);
	    	$update = $this->service_m->update('members', $where, $array);
	    	$sql = $this->db->last_query();  	
	
			if($update)
			{
				$checkid = $this->service_m->getRow("select m.* from members m where  m.id ='".$rider_id."' and usertype_id = 5");

				$alldetails =  array(
				  						'riderid'	=> $checkid->id,
				  						'fullname'	=> $checkid->display_name, 
				  						'country'	=> $checkid->country_id,
				  						'email'		=> $checkid->email,
				  						'password'	=> $checkid->password,
				  						'user_image'=> base_url().'uploads/rider/'.$checkid->user_image,
				  						'mobile'    => $checkid->mobile_no	
				  					); 

				$this->response([
							  'responsecode' => 1,
							  'alldetails' => $alldetails,
							  'response' => 'Your account has been updated successfully.'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->response([
							  'responsecode' => 0,
							  'response' => 'Some problems occurred, please try again.'], REST_Controller::HTTP_OK);
			}	
		}
	}

	// rider when forgot password get this api //
	public function rider_forgotpassword_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);  
		$email = $getdetails->{'email'};  
		
		if($email == '')
		{
		  $this->response(['responsecode' => 0,'errormsgemail'=> 'email','response' => 'Please enter Email'], REST_Controller::HTTP_OK);
		}
		else
		{
			$getdata = $this->service_m->getVal("select id from members where email ='".$email."' and status = 0 ");
			$sql = $this->db->last_query();
			if(!empty($getdata))
			{
				$random =  mt_rand(100000, 999999);
				$user_id = $getdata;
				$arr   = array('password'=>$random);
				$where = array('id'=>$user_id);
				$update = $this->service_m->update('members', $where, $arr); 
		  		if($update)
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
             						'password' => $random,
                 				 );
             		$body = $this->load->view('mailformat/forgotpassword',$data,TRUE);
            		$this->email->message($body);   
            		$this->email->send();

            		$this->response(['responsecode' => 1,'response' => "Email sent successfully and Password changed successfully"], REST_Controller::HTTP_OK);
				}
		    }
			else
		    {
			$this->response(['responsecode' => 0,'response' => "Couldn't find your email id "], REST_Controller::HTTP_OK);
			}
		}
	}

	// log out api for running the data //
	public function rider_logoutdata_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'}; 
		
		$array = array(
					 	'device_id' =>'',
					 	'token_id'  =>'',
					  );
		$where = array('id' => $rider_id);
	    $update = $this->service_m->update('members', $where, $array); 
	    if($update)
		{
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

    // getting the vehicle category //
    public function rider_vehicle_category_get()
    {
        $getdata = $this->service_m->getRows("SELECT * FROM car_category ORDER BY name ASC");
        foreach($getdata as $getdatai)
        {	
        $vehicle_details[] = array(
        							'id' 	=> $getdatai->id,
        							'name'	=> $getdatai->name,
        							'icon'  => base_url().'assets/icons/'.$getdatai->icon 
        						  );
    	}
        if(!empty($getdata))
        {
        	$this->response(['responsecode' => 1,'vehicle_details' => $vehicle_details], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(['responsecode' => 0,'vehicle_details' => "Data not found"], REST_Controller::HTTP_OK);
        }
    }

    // getting the vehicle sub category //
    public function rider_vehicle_subtype_post()
    {
        $id = file_get_contents('php://input');
       	$ids = json_decode($id);
        $getdata = $this->service_m->getRows("SELECT * FROM vehicle_category WHERE type_id = '".$ids->{'id'}."'");
          
        if(!empty($getdata))
        {
        	$this->response(['responsecode' => 1,'response' => $getdata], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
        }
    } 

    //showing driver list in rider api //
    public function rider_fleet_show_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$vehicle_category = $getdetails->{'vehicle_category'};
		$vehicle_subtype = $getdetails->{'vehicle_subtype'};
		$latitude = $getdetails->{'latitude'};
		$longitude = $getdetails->{'longitude'};
		$distance = $getdetails->{'distance'};
		$duration = $getdetails->{'duration'};
		$zone_id = $getdetails->{'zone_id'};
		$fleet_id = $getdetails->{'fleet_id'};
		$zonearea_id = $getdetails->{'zonearea_id'};
		$zonedistance = $getdetails->{'zonedistance'};
		$totalsurge = $getdetails->{'surge'};
		$book_type = $getdetails->{'book_type'};
		$temp = count($getdetails->{'zone_id'});
		$charter_time = $getdetails->{'charter_time'}; 
		$fleetids = join("','",$fleet_id);
		$country_id = $getdetails->{'country_id'};
		$prebook_time = date('H:i',strtotime($getdetails->{'prebook_time'}));
		$dist = 0;
		$sum = 0;
		            
 		if(!empty($country_id)) 
  		{
  			$gettimezone = $this->service_m->getVal('SELECT timezone FROM countries WHERE id='.$country_id.'');
    		date_default_timezone_set($gettimezone);
    		$book_date= date('d-m-Y H:i:s');
    		$book_time= date('H:i');
  		} 
  		for($i=0; $i<$temp; $i++)
    	{
    		$dist+= $distance - $zonedistance[$i];
    		$sum+= $zonedistance[$i] * $surge[$i];
    		$totalsurge+= $surge[$i];
    	}
    	
    	$show = array($dist,$sum,$totalsurge);
   		if($book_type == 1)
   		{
    		$getdata = $this->service_m->getRows("SELECT dd.dispatch_id as dd_id,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour,MIN( 6371 * acos( cos( radians('".$latitude."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$longitude."') ) + sin( radians('".$latitude."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$book_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category=  '".$vehicle_category."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 27 and m.org_id = dd.id and dd.carcategory = f.car_category  and f.zone_id = 0 and dd.vehicle_type = f.vechile_seater and dd.activestatus = 1 GROUP BY dd.dispatch_id HAVING distance < 8 ORDER BY distance");

    		$sql = $this->db->last_query();

    		if($getdata == 0)
    		{
    			$getdata = $this->service_m->getRows("SELECT dd.dispatch_id as dd_id,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour,MIN( 6371 * acos( cos( radians('".$latitude."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$longitude."') ) + sin( radians('".$latitude."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$book_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category=  '".$vehicle_category."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 26 and m.org_id = dd.id and dd.carcategory = f.car_category  and f.zone_id = 0 and dd.vehicle_type = f.vechile_seater and dd.activestatus = 1 GROUP BY dd.dispatch_id HAVING distance < 8 ORDER BY distance");
    		}

    		$sql = $this->db->last_query();
    	} 
    	if($book_type == 2)
    	{
    		$book_time = $prebook_time;
 			$getdata = $this->service_m->getRows("SELECT dd.dispatch_id as dd_id,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour,f.client_id,(f.base_fare +f.cost_per_km * '".$distance."' + f.time_per_m * '".$duration."' + f.waiting_time_p_hour * '".$charter_time."') as total_crocs, MIN( 6371 * acos( cos( radians('".$latitude."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$longitude."') ) + sin( radians('".$latitude."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$book_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category =  '".$vehicle_category."' and f.vechile_seater = '".$vehicle_subtype."' and f.client_id = 27  and m.org_id = dd.id and dd.carcategory = f.car_category  and f.zone_id = 0 and dd.vehicle_type = f.vechile_seater and dd.activestatus = 1 GROUP BY dd.dispatch_id ORDER BY MAX(total_crocs) DESC limit 1");

 			$sql = $this->db->last_query(); 

 			if($getdata == 0)
 			{
 				$getdata = $this->service_m->getRows("SELECT dd.dispatch_id as dd_id,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour,f.client_id,(f.base_fare +f.cost_per_km * '".$distance."' + f.time_per_m * '".$duration."' + f.waiting_time_p_hour * '".$charter_time."') as total_crocs, MIN( 6371 * acos( cos( radians('".$latitude."') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$longitude."') ) + sin( radians('".$latitude."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$book_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category =  '".$vehicle_category."' and f.vechile_seater = '".$vehicle_subtype."' and f.client_id = 26  and m.org_id = dd.id and dd.carcategory = f.car_category  and f.zone_id = 0 and dd.vehicle_type = f.vechile_seater and dd.activestatus = 1 GROUP BY dd.dispatch_id ORDER BY MAX(total_crocs) DESC limit 1");	
 			}	
       	}

      	if($zonearea_id != 0)
      	{
      		if($book_type == 1)
      		{
       			$zonegetdata = $this->service_m->getRows("SELECT *, SUM(base_fare) as totalfare FROM `fares` WHERE `zone_id` IN (".$zone_id.") AND '".$book_time."' BETWEEN time_from AND time_to and car_category=  '".$vehicle_category."' and vechile_seater= '".$vehicle_subtype."' GROUP by dispatch_id");
       		}
       		else
       		{
       			$zonegetdata = $this->service_m->getRows("SELECT *, SUM(base_fare) as totalfare FROM `fares` WHERE `zone_id` IN (".$zone_id.") AND '".$book_time."' BETWEEN time_from AND time_to and car_category=  '".$vehicle_category."' and vechile_seater= '".$vehicle_subtype."' GROUP by dispatch_id");
          	}
          	
       	}
       	else
       	{
       		$zonegetdata='';
       	}
		
		$response=[];
       	if($getdata != 0)
       	{
	   		foreach($getdata as $getdatai)
	   		{
	   			$agentcount = $this->service_m->getVal('SELECT count(id) FROM fares WHERE "'.$book_time.'" BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 27 and dispatch_id = "'.$getdatai->dd_id.'" and  zone_id=0 GROUP BY client_id,dispatch_id');

	   			if($agentcount == '')
	   			{
	   				$agenti = $this->service_m->getRow('SELECT * FROM fares  WHERE "'.$book_time.'"  BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 26 and dispatch_id = "'.$getdatai->dd_id.'" and zone_id=0 GROUP by dispatch_id,client_id');
	   			}
	   			else
	   			{
	   				$agenti = $this->service_m->getRow('SELECT * FROM fares  WHERE "'.$book_time.'"  BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 27 and dispatch_id = "'.$getdatai->dd_id.'" and zone_id=0 GROUP by dispatch_id,client_id');
	   			}
	   			
	   			$getfleet = $this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$getdatai->dd_id."' and m.org_id= o.id and m.usertype_id= 3 ");
	    		
	   			$supplypartnerfee = $this->admin->getVal('SELECT o.fleet_fee FROM members m, organization o WHERE m.org_id = o.id and  m.id = "'.$getdatai->dd_id.'" and m.usertype_id = 3 ');

	   			$supplypartnertax = $this->admin->getVal('SELECT o.fleet_tax FROM members m, organization o WHERE m.org_id = o.id and m.id = '.$getdatai->dd_id.' and m.usertype_id = 3');
	    		
       			if($vehicle_type == 4)
       			{
       				$taotal = $agenti->base_fare + $distance * 2 *  $agenti->cost_per_km  + $duration * 2 * $agenti->time_per_m;
       				$charter_time_charge = $charter_time * $agenti->waiting_time_p_hour;
       			}
       			else
       			{
       				$taotal = $agenti->base_fare + $distance *  $agenti->cost_per_km + $duration * $agenti->time_per_m;
       				$charter_time_charge = $charter_time * $agenti->waiting_time_p_hour;
            	}

    			if($book_type == 2)
    			{
       				$prebooking_fee  = 0.00;
      			}
      			else
      			{
      				$pre_surge_total = 0.00;
      			}

      			if($agentcount == '')
      			{
      				$totalcharge 			= $taotal + $charter_time_charge;
      				$candytechmarkup 		= $totalcharge * 20 / 100;
   					$totalcandytechmarkup	= $candytechmarkup + $totalcharge;
   					$candytechtax 			= $candytechmarkup * 6 / 100;
   					$totalcandytechtax      = $candytechtax + $totalcandytechmarkup;
   					$fleetfees              = $totalcharge * $supplypartnerfee / 100;
   					$totalfleetcharge 		= $fleetfees + $totalcandytechtax; 
   					$fleettax 				= $fleetfees * $supplypartnertax / 100;
   					$totalfare 				= $fleettax + $totalfleetcharge;
   					$driverfees 			= $totalcharge;
      			}
      			else
      			{
      				$totalcharge 			= $taotal + $charter_time_charge; 
		  			$fleetfees 				= $totalcharge * $supplypartnerfee / 100;
		  			$totalfleetcharge 		= $fleetfees + $totalcharge;
		  			$fleettax       		= $fleetfees * $supplypartnertax / 100;
		  			$totalfare 				= $fleettax + $totalfleetcharge;
		  			$driverfees      		= $totalcharge;
		  			$candytechmarkup		= 0.00;
   	 				$candytechtax           = 0.00;
		  		}  
       
      				$response[] = array(
      									"driver_id" => 0,
                						"vehicle_category" => $vehicle_category,
                						"vehicle_subtype" => $vehicle_subtype,
                						"country" =>   $country_id,
                						"adminfees" => 0.00,
                						"tax_fees"	=> 0.00,
                						"gateway_fee" => 0.00,
                						"fleetfees" => $fleetfees,
                						"fleettax"	=> $fleettax, 
                						"agentfees"   => 0.00,
                						"royality_fees" => 0.00,
                						"candytechmarkup" => $candytechmarkup,
                						"candytechtax" => $candytechtax,
                						"agent_id"	=>	0,
                						"driverfees" => $driverfees,
                						"fleet_id" => $getdatai->dd_id,
        	    						"fare"    =>  $totalfare,
        	    						"book_type" => $book_type,
        	    						"fleetnamne" =>  $getfleet->display_name,
                						"fleetlogo"  =>  $getfleet->org_logo,
                						"surge"  =>  0,
                						"charter_time_charge" => $charter_time_charge,
                						"charter_time" => $charter_time
            						);
          	}
      	}
       	
       	$zoneresponse=[];
       	if($zonegetdata != 0)
       	{
	   		foreach($zonegetdata as $getdatai)
	   		{
	   			$zoneagentcount = $this->service_m->getVal('SELECT count(id)  FROM fares WHERE "'.$book_time.'" BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 27 and dispatch_id = "'.$getdatai->dispatch_id.'"  and zone_id = "'.$getdatai->zone_id.'" GROUP BY client_id,dispatch_id');

	   			$getfleet1 = $this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$getdatai->dispatch_id."' and m.org_id= o.id and m.usertype_id = 3");
	   
	 			
     			if($vehicle_type == 4)
     			{
     				$taotal1 = $getdatai->totalfare * 2  + $totalsurge;
     				$charter_time_charge1 = $charter_time * $getdatai->waiting_time_p_hour;
     			}
     			else
     			{ 
     				$taotal1 = $getdatai->totalfare + $totalsurge; 
     				$charter_time_charge1 = $charter_time * $getdatai->waiting_time_p_hour;
     			}
     			if($book_type == 2)
     			{
     				$prebooking_fee1 = $taotal1 * $adminfee1 / 100;
     				$pre_surge_total1 = $taotal1 + $prebooking_fee1 + $totalsurge;
     			}
     			else
     			{
     				$pre_surge_total1 = $taotal1 + $totalsurge;
     			} 
  					$adminfees1 = $pre_surge_total1 * $adminfee1 / 100;
  					$dispatchfees1 = $pre_surge_total1 * $dispatchfee1 / 100;
  					$driverfees1   = $taotal1;
  					$totaladmin1   = $pre_surge_total1  + $dispatchfees1;            
  					$clientfees1   = $totaladmin1 * $royaltyclientfee1 / 100;
  					$cahrtertime1  = $charter_time_charge1 + $clientfees1;        
  					$tfees1        = $totaladmin1 + $waitingtime1;
  					$gateway_fee1  = $tfees1 * $adminfee1 / 100;
  					$taotalgatewayfees1  = $tfees1 + $gateway_fee1; 
  					$taotaltaxfees1      = $taotalgatewayfees1 * $adminfee1 / 100;      
  					$taotalfees1      	 = $taotal1;
  					$taotaladminfees1    = $adminfees1;

        			$zoneresponse[] = array(
        									"driver_id" => 0,
                							"vehicle_type" => $vehicle_category,
                							"vehicle_subtype" => $vehicle_subtype,
                							"country"   => $country_id,
                							"adminfees" => 0.00,
                							"gateway_fee" => 0.00,
                							"tax_fees"	  => 0.00,	
                							"fleetfees" => 0.00,
                							"agentfees"   => 0.00,
                							"royality_fees"   => 0.00,
                							"agent_id"		=> 0,
                							"driverfees"   => $taotalfees1,
                							"fleet_id"  => $getdatai->dispatch_id,
        	    							"fare"      =>  $taotalfees1,
        	    							"book_type" => $book_type,
        	    							"fleetnamne"   =>  $getdp1->display_name,
                							"fleetlogo"    =>  $getdp1->org_logo,
                							"surge"     =>  $totalsurge,
                							"charter_time_charge" => $charter_time_charge1
            							);
          	}
        }

        if(!empty($response))
        {
			$this->response(['responsecode' => 1,"baseurl" => base_url().'uploads/org/','response' => $response,'sql' => $sql], REST_Controller::HTTP_OK);
	    }
	    else if(!empty($zoneresponse))
	    {
	    	$this->response(['responsecode' => 1,"baseurl" => base_url().'uploads/org/','response' => $response ,'zoneresponse' => $zoneresponse], REST_Controller::HTTP_OK);	
	    }
		else
        {
        	$this->response(['responsecode' => 0,"baseurl" => base_url().'uploads/org/','response' => "Data not found",'sql' => $sql], REST_Controller::HTTP_OK);
        }
	}

	// getting the zonedata //
	public function rider_zoneride_post()
	{  
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$country_id = $getdetails->{'country_id'};
    	
    	$getdata = $this->service_m->getRows("SELECT * FROM zone WHERE type = 1 and country_id = '".$country_id."'");
	    
	    foreach($getdata as $getdatai)
	    {
	   		$getitemdata = $this->service_m->getRows("SELECT * FROM zonelatlng WHERE 
	   			zone_id = '".$getdatai->id."'");
	     	
	     	if($getitemdata != NULL)
            {
	   			$getdata = $getitemdata; 
		    }
		    else
		    {
		    	$getdata = array();
		    }
       		$response[] = array(
                				"id"		=> $getdatai->id,
                				"zonename"	=> $getdatai->zonename,
                				"address"	=> $getdatai->address,
        	    				"latlng" 	=> $getdata
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

	// cancelling the trip //
	public function rider_cancelride_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$ride_id = $getdetails->{'ride_id'};
		$reason = $getdetails->{'reason'};
		$status_type = $getdetails->{'status_type'};
		$country_id = $getdetails->{'country_id'};
		$cancel_date_time = $getdetails->{'cancel_date_time'};
		$book_type = $getdetails->{'book_type'};
			 
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
  				$gettimezone = $this->service_m->getVal('SELECT timezone FROM countries WHERE id='.$country_id.'');
    			date_default_timezone_set($gettimezone);
    			$book_date = date('d-m-Y H:i:s');
    
  			} 
  			
 			$adminfees='0.00';
  			$totalamount='0.00';
  			$cancellation_charge='0.00';
			
			$ride = $this->service_m->getRow('SELECT id,driver_id,client_id,book_date,book_datetime,paymentmode,totalfare,book_type,ride_status,adminfees,driverfees FROM ride WHERE  id ='.$ride_id.'');
			$adminfee = 0.00;
			$cancel_date_time = $book_date;

    	if($ride->ride_status == 0)
    	{
    		$array = array(
               				'ride_status'    => 2,
               				'refundamount'   => 0.00,
               				'end_time' => $cancel_date_time,
               				'cancelled_by' => 5
                		);
    		$update = $this->service_m->update('ride',array('id'=>$ride_id), $array);

    	}
    	else
    	{
    		$array = array(
               				'ride_status'    => 2,
               				'reason'         => $reason,
               				'cancelled_by'   => 5,
               				'refundamount'   => 0.00,
               				'cancelcharge'   => 0.00,
               				'cancel_service_fee' => $adminfees,
               				'end_time'       => $cancel_date_time,
               				'updated_driver_earning' => $ride->driverfees 
               				);
    		$update=$this->service_m->update('ride',array('id'=>$ride_id), $array);
        } 
    	if($update)
    	{
    		if($ride->ride_status == 0)
    		{
    			$array1 = array(
    							'ride_status'    => 2,
               					'refundamount'   => 0.00,
               					'end_time' 		 => $cancel_date_time,
               					'cancelled_by'   => 5			
    							);
    			$ridepayupdate = $this->service_m->update('ride_pay',array('ride_id'=>$ride_id), $array1);
    		}
    		else
    		{
    			$array1 = array(
               					'ride_status'    => 2,
               					'reason'         =>$reason,
               					'cancelled_by'   => 5,
               					'refundamount'   => 0.00,
               					'cancelcharge'   => 0.00,
               					'cancel_service_fee' => $adminfees,
               					'end_time'       => $cancel_date_time,
                            	);
    			$ridepayupdate = $this->service_m->update('ride_pay',array('ride_id'=>$ride_id), $array1);
    		}
    	}

 		$device = $this->admin->getRow('SELECT m.device_id,m.device_type,m.token_id FROM members m, ride r WHERE  m.usertype_id =  4 and  m.org_id = r.driver_id and r.id = '.$ride_id.'');
 		
 		$fcmMsg = array(
						'body' => 'Your booking cancelled',
						'title' => 'The Candy Booking',
						'sound' => "default",
    					'color' => "#203E78" 
    					);
   
		if($device->device_type == 1)
		{
			$fcmFields = array(
								'to'   => $device->device_id,
								'data' => $fcmMsg
    						);
		}
		else
		{
			$fcmFields = array(
								'to' => $device->device_id,
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

		$this->response(['responsecode' => 1,'ride_status'=>$status,'response' => 'Success!','sql' => $sql], REST_Controller::HTTP_OK);
	}
}
	// rider ride booking api //
	public function rider_ridebook_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		$fleet_id = $getdetails->{'fleet_id'};
		$driver_id = $getdetails->{'driver_id'};
		$fleetfees = $getdetails->{'fleetfees'};
		$fleettax = $getdetails->{'fleettax'};
		$candytechmarkup = $getdetails->{'candytechmarkup'};
		$candytechtax = $getdetails->{'candytechtax'};
		$adminfees = $getdetails->{'adminfees'};
		$book_type = $getdetails->{'book_type'};
		$prebook_time = $getdetails->{'prebook_time'};
		$country_id = $getdetails->{'country_id'};
		$agent_id = $getdetails->{'agent_id'};
		$pickupaddress = $getdetails->{'pickupaddress'};
		$pickuplat = $getdetails->{'pickuplat'};
		$pickuplong = $getdetails->{'pickuplong'};
		$dropaddress = $getdetails->{'dropaddress'};
		$droplat = $getdetails->{'droplat'};
		$droplong = $getdetails->{'droplong'};
		$vehicle_category = $getdetails->{'vehicle_category'};
		$vehicle_subtype = $getdetails->{'vehicle_subtype'};
		$distance = $getdetails->{'distance'};
		$duration = $getdetails->{'duration'};
		$note = $getdetails->{'note'};
		$driverfees = $getdetails->{'driverfees'};
		$totalfees = $getdetails->{'totalfees'};
		$paymentmode = $getdetails->{'paymentmode'};
		$charter_time_charge = $getdetails->{'charter_time_charge'};
		$charter_time = $getdetails->{'charter_time'};
		$zonedistance = $getdetails->{'zonedistance'};
		$surge = $getdetails->{'surge'};
		$zone_id = $getdetails->{'zone_id'};
		$currency = $getdetails->{'currency'};


		$userdetail = $this->admin->getRow('SELECT * FROM members WHERE 
			id = "'.$rider_id.'" and usertype_id = 5'); 


	    
	    if($userdetail->id != '')
	    {
			$dispatchfee = $this->admin->getVal('SELECT o.fees FROM members m, organization o WHERE m.org_id = o.id and m.id = "'.$fleet_id.'"');
			$royaltyclientfee = 0.00;
			
			$device = $this->admin->getRow('SELECT m.device_id,m.device_type FROM members m, driver_details dd WHERE m.org_id = dd.id and m.usertype_id =  4 and  dd.id = "'.$driver_id.'"');

        	$managerfee = 0.00; 
        	
        	$adminfee = 0.00;
        	$admin_id = $this->service_m->getVal('SELECT id FROM members WHERE usertype_id = 1');

       		if($fleetfees == '')
			{
			 	$this->response(['responsecode' => 0,'response' => 'Right now vechile not available'], REST_Controller::HTTP_OK);
			}
			elseif($adminfees == '')
			{
		    	$this->response(['responsecode' => 0,'response' => 'Right now vechile not available'], REST_Controller::HTTP_OK);
			}
			else
			{
  				if(!empty($country_id)) 
  				{
  					$gettimezone = $this->service_m->getVal('SELECT timezone FROM countries WHERE id='.$country_id.'');
    				date_default_timezone_set($gettimezone);
    				$book_date = date('d-m-Y H:i:s');
    				$book_time= date('H:i');
    				$book_date1= date('d-m-Y');
  				} 
           		
           		$driverarrival = mt_rand(5,10); 
           		$array = array(
                    			'dispatch_id'      => $fleet_id,
                  				'manager_id'       => 0,
                    			'admin_id'         => $admin_id,
                    			'client_id'		   => $agent_id,	
                    			'user_id'          => $rider_id,
                    			'country_id'       => $country_id,
                    			'pickupaddress'    => $pickupaddress ,
                    			'pickuplat'        => $pickuplat,
                    			'pickuplong'       => $pickuplong,
                    			'dropaddress'      => $dropaddress,
                    			'droplat'          => $droplat,
                    			'droplong'         => $droplong,
                    			'book_type'        => $book_type,
                    			'car_category'     => $vehicle_category,
                    			'vichle_type'      => $vehicle_subtype,
                    			'distance'         => $distance,
                    			'duration'         => $duration,
                    			'note'             => $note,
                    			'dispatchfees'     => $fleetfees,
                    			'supplier_partner_tax' => $fleettax,
                    			'candytechmarkup'  => $candytechmarkup,
                    			'candytechtax'	   => $candytechtax,	
                  				"surge"            => 0.00,
                    			'adminfees'        => 0.00,
                    			'gateway_fee'      => 0.00,	
                    			'driverfees'       => $driverfees,
                    			'totalfare'        => $totalfees,
                    			'fullname'         => $userdetail->display_name,
                    			'mobile'           => $userdetail->mobile_no,
                    			'passport'         => $userdetail->passport,
                    			'email'            => $userdetail->email,
                    			'driver_eta'       => $driverarrival,
                 				'paymentmode'      => $paymentmode,
                    			'waiting_time_charge' => $charter_time_charge,
                    			'waiting_time' 		=> $charter_time,
                    			'payment_token_id' 	=> 0,
                    			'currency'          => $currency  
                     			);	

            if($book_type == 1)
            {
              $array['book_date'] = $book_date;
              $array['book_datetime'] = $book_date;
            }
            if($book_type == 2)
            {
              $array['book_date'] = $prebook_time;
              $array['book_datetime'] = $book_date;
            }
            
           $insert = $this->service_m->insert('ride',$array);
           $sql = $this->db->last_query();
         
           if($book_type == 2)
           {

            	$zonedistance = $zonedistance;
            	$totalsurge = $surge;
            	$temp = count($zone_id);
            	for($i=0; $i<$temp; $i++)
			    {
			    $dist+=$distance - $zonedistance[$i];
			    $sum+= $zonedistance[$i] * $surge[$i];
			    $totalsurge+= $surge[$i];
			    }


            	$getdata = $this->service_m->getRows("SELECT dd.dispatch_id as dd_id,f.base_fare,f.cost_per_km,f.time_per_m,f.waiting_time_p_hour, (f.base_fare +f.cost_per_km * '".$distance."' + f.time_per_m * '".$duration."' + f.waiting_time_p_hour * '".$charter_time."') as total_crocs,( 6371 * acos( cos( radians('".$pickuplat."')) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('".$pickuplong."') ) + sin( radians('".$pickuplat."') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd,fares f  WHERE '".$prebook_time."' BETWEEN f.time_from AND f.time_to and f.dispatch_id = dd.dispatch_id   and f.car_category=  '".$vehicle_category."' and f.vechile_seater= '".$vehicle_subtype."' and f.client_id = 27 and m.org_id = dd.id and dd.carcategory = f.car_category  and f.zone_id = 0 and dd.vehicle_type = f.vechile_seater and dd.activestatus = 1 GROUP BY dd.dispatch_id ORDER BY distance");

            	if($zonearea_id != 0)
				{
      				if($book_type == 1)
					{
       					$zonegetdata = $this->service_m->getRows("SELECT *, SUM(base_fare) as totalfare FROM `fares` WHERE 
       						`zone_id` IN (".$zonearea_id.") AND '".$book_time."' BETWEEN time_from AND time_to and car_category=  '".$vehicle_category."' and vechile_seater= '".$vehicle_subtype."' GROUP by dispatch_id");
       				}
       				else
       				{
       					$zonegetdata = $this->service_m->getRows("SELECT *, SUM(base_fare) as totalfare FROM `fares` WHERE `zone_id` IN (".$zonearea_id.") AND '".$book_time."' BETWEEN time_from AND time_to and car_category=  '".$vehicle_category."' and 
       						vechile_seater= '".$vehicle_subtype."' GROUP by dispatch_id");
          			}
          		}
          		else
          		{
       				$zonegetdata='';
       			}

       			if($getdata != 0)
       			{
	   				foreach($getdata as $getdatai)
	   				{
	   					$agentcount = $this->service_m->getVal('SELECT count(id) FROM fares WHERE "'.$book_time.'" BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 27 and dispatch_id = "'.$getdatai->dd_id.'" and  zone_id=0 GROUP BY client_id,dispatch_id');

	   					if($agentcount == '')
	   					{
	   						$agenti = $this->service_m->getRow('SELECT * FROM fares  WHERE "'.$book_time.'"  BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 26 and dispatch_id = "'.$getdatai->dd_id.'" and zone_id=0 GROUP by dispatch_id,client_id');
	   					}
	   					else
	   					{
	   						$agenti = $this->service_m->getRow('SELECT * FROM fares  WHERE "'.$book_time.'"  BETWEEN time_from AND time_to and car_category = "'.$vehicle_category.'" and vechile_seater = "'.$vehicle_subtype.'" and client_id = 27 and dispatch_id = "'.$getdatai->dd_id.'" and zone_id=0 GROUP by dispatch_id,client_id');
	   					}


	   					$getfleet = $this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$getdatai->dd_id."' and m.org_id= o.id and m.usertype_id= 3 ");
	    		
	   					$supplypartnerfee = $this->admin->getVal('SELECT o.fleet_fee FROM members m, organization o WHERE m.org_id = o.id and  m.id = "'.$getdatai->dd_id.'" and m.usertype_id = 3 ');

	   					$supplypartnertax = $this->admin->getVal('SELECT o.fleet_tax FROM members m, organization o WHERE m.org_id = o.id and m.id = '.$getdatai->dd_id.' and m.usertype_id = 3');
	   					
	   					$fleet_id+= $getdatai->dispatch_id;
         				
       					if($vehicle_category == 4)
       					{
       						$taotal = $agenti->base_fare + $distance * 2 *  $agenti->cost_per_km  + $duration * $agenti->time_per_m;
       						$charter_time_charge = $charter_time * $agenti->waiting_time_p_hour;
       					}
       					else
       					{
       						$taotal = $agenti->base_fare + $distance *  $agenti->cost_per_km + $duration * $agenti->time_per_m;
       						$charter_time_charge = $charter_time * $agenti->waiting_time_p_hour;
            			}

            			if($book_type == 2)
    					{
       						$prebooking_fee  = 0.00;
      					}
						if($agentcount == '')
      					{
      						$totalcharge 			= $taotal + $charter_time_charge;
      						$candytechmarkup 		= $totalcharge * 20 / 100;
   							$totalcandytechmarkup	= $candytechmarkup + $totalcharge;
   							$candytechtax 			= $candytechmarkup * 6 / 100;
   							$totalcandytechtax      = $candytechtax + $totalcandytechmarkup;
   							$fleetfees              = $totalcharge * $supplypartnerfee / 100;
   							$totalfleetcharge 		= $fleetfees + $totalcandytechtax; 
   							$fleettax 				= $fleetfees * $supplypartnertax / 100;
   							$totalfare 				= $fleettax + $totalfleetcharge;
   							$driverfees 			= $totalcharge;
      					}
      					else
      					{
      						$totalcharge 			= $taotal + $charter_time_charge; 
		  					$fleetfees 				= $totalcharge * $supplypartnerfee / 100;
		  					$totalfleetcharge 		= $fleetfees + $totalcharge;
		  					$fleettax       		= $fleetfees * $supplypartnertax / 100;
		  					$totalfare 				= $fleettax + $totalfleetcharge;
		  					$driverfees      		= $totalcharge;
		  					$candytechmarkup		= 0.00;
   	 						$candytechtax           = 0.00;
		  				}


		  	 $driverarrival = mt_rand(1,20);				
             $array1 = array(
                    'dispatch_id'      => $getdatai->dd_id,
                    'ride_id'          => $insert,
                    'manager_id'       => 0,
                    'admin_id'         => $admin_id,
                    'client_id'		   => $agent_id,
                    'user_id'          => $rider_id,
                    'country_id'       => $country_id,
                    'pickupaddress'    => $pickupaddress,
                    'pickuplat'        => $pickuplat,
                    'pickuplong'       => $pickuplong,
                    'dropaddress'      => $dropaddress,
                    'droplat'          => $droplat ,
                    'droplong'         => $droplong,
                    'book_type'        => $book_type,
                    'car_category'     => $vehicle_category,
                    'vichle_type'      => $vehicle_subtype,
                    'distance'         => $distance,
                    'duration'         => $duration,
                    'note'             => $note,
                    'dispatchfees'     => $fleetfees,
                    'supplier_partner_tax' => $fleettax,
                    'candytechmarkup'  => $candytechmarkup,
                    'candytechtax'	   => $candytechtax,
                    "surge"            => 0.00,
                    'adminfees'        => 0.00,
                    'gateway_fee'      => 0.00,
                    'tax_fees'		   => 0.00,
                    'prebooking_fee'   => $prebooking_fee,
                    'driverfees'       => $driverfees,
                    'totalfare'        => $taotalfees,
                    'fullname'         => $userdetail->display_name,
                    'mobile'           => $userdetail->mobile_no,
                    'passport'         => $userdetail->passport,
                    'email'            => $userdetail->email,
                    'driver_eta'       => $driverarrival,
                    'paymentmode'      => $paymentmode,
                    'waiting_time_charge' => $charter_time_charge,
                    'waiting_time' => $charter_time,
                    'currency'     => $currency
                  );	

            if($book_type == 1)
            {
              $array1['book_date'] = $book_date;
              $array1['book_datetime'] = $book_date;
            }
            if($book_type == 2)
            {
              $array1['book_date'] = $prebook_time;
              $array1['book_datetime'] = $book_date;
            }
             $insert1 = $this->service_m->insert('ride_pay',$array1);
             $sql = $this->db->last_query();
            } 
         }

         if($zonegetdata != 0)
         {
         	foreach($zonegetdata as $zonegetdatai)
	   		{
	   			$getdp1 = $this->service_m->getRow("SELECT m.display_name, o.org_logo FROM members m, organization o  WHERE m.id = '".$zonegetdatai->dispatch_id."' and m.org_id= o.id and m.usertype_id= 3 ");

	   			$fleet_id+= $zonegetdatai->dispatch_id;

	   			$dispatchfee = 0.00;
	   			$royaltyclientfee = 0.00;
	    		$managerfee = 0.00; 
       			$adminfee = 0.00;

       			if($vehicle_category == 4)
       			{
       				$taotal = $zonegetdatai->base_fare + $distance * 2 *  $zonegetdatai->cost_per_km  + $duration * $zonegetdatai->time_per_m;
       				$charter_time_charge = $charter_time * $zonegetdatai->waiting_time_p_hour;
       			}
       			else
       			{
       				$taotal = $zonegetdatai->base_fare + $distance *  $zonegetdatai->cost_per_km + $duration * $zonegetdatai->time_per_m;
       				$charter_time_charge = $charter_time * $zonegetdatai->waiting_time_p_hour;
            	}

       			$prebooking_fee  = $taotal * $adminfee / 100;
       			$pre_surge_total = $taotal + $prebooking_fee + $totalsurge;
       			$adminfees    = $pre_surge_total * $adminfee / 100;
		  		$dispatchfees = $pre_surge_total * $dispatchfee / 100;
		  		$driverfees   = $taotal;
		  		$taotaladmin  = $pre_surge_total  + $dispatchfees;            
		  		$clientfees   = $taotaladmin * $royaltyclientfee / 100; 
		  		$chartertime = $charter_time_charge + $clientfees; 
		  		$tfees        = $taotaladmin + $chartertime;
		  		$gateway_fee  = $tfees * $adminfee / 100;       
		  		$taotalgatewayfees  = $tfees + $gateway_fee;
		  		$taotaltaxfees      = $taotalgatewayfees * $adminfee / 100;
		  		$taotalfees      = $taotal;
		  		$taotaladminfees = $adminfees; 

		  		$driverarrival = mt_rand(1,20);
		  		$array2 = array(
                    'dispatch_id'      => $zonegetdatai->dispatch_id,
                    'ride_id'          => $insert,
                    'manager_id'       => 0,
                    'admin_id'         => $admin_id,
                    'client_id'		   => $agent_id,
                    'user_id'          => $rider_id,
                    'country_id'       => $country_id,
                    'pickupaddress'    => $pickupaddress,
                    'pickuplat'        => $pickuplat,
                    'pickuplong'       => $pickuplong,
                    'dropaddress'      => $dropaddress,
                    'droplat'          => $droplat ,
                    'droplong'         => $droplong,
                    'book_type'        => $book_type,
                    'car_category'     => $vehicle_category,
                    'vichle_type'      => $vehicle_subtype,
                    'distance'         => $distance,
                    'duration'         => $duration,
                    'note'             => $note,
                    'dispatchfees'     => 0.00,
                    'royality_fees'    => 0.00,
                    "surge"            => 0.00,
                    'adminfees'        => 0.00,
                    'gateway_fee'      => 0.00,
                    'tax_fees'		   => 0.00,
                    'prebooking_fee'   => 0.00,
                    'driverfees'       => $driverfees,
                    'totalfare'        => $taotalfees,
                    'fullname'         => $userdetail->display_name,
                    'mobile'           => $userdetail->mobile_no,
                    'passport'         => $userdetail->passport,
                    'email'            => $userdetail->email,
                    'driver_eta'       => $driverarrival,
                    'paymentmode'      => $paymentmode,
                    'waiting_time_charge' => $charter_time_charge,
                    'waiting_time' => $charter_time,
                    'currency'     => $currency
                  );

		  		if($book_type == 1)
            	{
              		$array1['book_date'] = $book_date;
              		$array1['book_datetime'] = $book_date;
            	}
            	if($book_type == 2)
            	{
              		$array1['book_date'] = $prebook_date;
              		$array1['book_datetime'] = $book_date;
            	}
             	
             	$insert2 = $this->service_m->insert('ride_pay',$array2);
	   		}
         }	
     }

	if($book_type == 1 || $book_type == 2)
	{
		if($book_type == 1)
		{
  			$autoinstantbookings = $this->admin->getRows('SELECT m.device_id,m.device_type,m.token_id FROM members m, driver_details dd WHERE m.org_id = dd.id and dd.activestatus = 1 and  dd.dispatch_id = "'.$fleet_id.'" and m.usertype_id = 4 and dd.carcategory = '.$vehicle_category.' and dd.vehicle_type = '.$vehicle_subtype.'');           
  		}
  		else if($book_type == 2)
  		{
  			$autoinstantbookings = $this->admin->getRows('SELECT m.device_id,m.device_type,m.token_id FROM members m, driver_details dd WHERE m.org_id = dd.id and dd.activestatus = 1 and  dd.dispatch_id IN ("'.$fleet_id.'") and dd.carcategory ='.$vehicle_category.' and dd.vehicle_type = '.$vehicle_subtype.' and m.usertype_id = 4'); 
  		}
  		foreach($autoinstantbookings as $autoinstantbookingi)
  		{
  			$device_id = $autoinstantbookingi->device_id;
  			
   			$fcmMsg = array(
   							'body' => 'You have recieved auto booking_'.$insert.'_'.$book_type.'_'.$prebook_date.'_'.$pickupaddress.'_'.$userdetail->display_name,
   							'title' => 'The Candy Booking',
   							'sound' => "default",
   							'color' => "#203E78",
   							'category' => "oneCabbiDriver" 
     						);
   
			if($autoinstantbookingi->device_type == 1) 
			{
				$fcmFields = array(
  									'to'   => $autoinstantbookingi->token_id,
  									'data' => $fcmMsg
    								);
			}
			else
			{
  				$fcmFields = array(
  									'to'   => $autoinstantbookingi->token_id,
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
			$results = json_decode($result);
			$notiarr = array(
                          'ride_id' => $insert,
                          'status'  => $results->{'success'} 
                        );
        	$notiinsert = $this->service_m->insert('notification_check',$notiarr);
			curl_close( $ch );
      	}
  	}  
    
    $array3 = array(
                    'page_id'       =>'2',
                    'insert_id'     =>$insert,
                    'status'        =>'0',
                    'member_id'     =>$rider_id,
                    'user_type'     =>5
                   );
    
    $insert3 = $this->service_m->insert('admin_notification', $array3);
	if($insert)
    {
		$this->response(['responsecode' => 1,'id' => $insert,'response' => "Sccuess"], REST_Controller::HTTP_OK);
	}
	else
    {
    	$this->response(['responsecode' => 0,'response' => "Data not found",'sql' => $sql], REST_Controller::HTTP_OK);
    }
}
}
else
{
	$this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
}
}

	// complaint registered under this api //
	public function rider_complaint_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id =$getdetails->{'rider_id'};
		$driver_id = $getdetails->{'driver_id'};
		$complaint = $getdetails->{'complaint'};
		$ride_id = $getdetails->{'ride_id'};
		
		if($rider_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter rider id'], REST_Controller::HTTP_OK);
		}
		elseif($driver_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
		}
		else
		{
			$driver = $driver_id;
            $array = array(
                     		'user_id'  		=> $rider_id,
                     		'driver_id' 	=> $driver_id,
                     		'type'     		=> 1,
                     		'user_type_id'  => 5,
                     		'comment'  		=> $complaint,
                     		'booking_id'	=> $ride_id
                      		);
            $insert = $this->service_m->insert('driverrating',$array);
            if($insert!='')
			{
				$array1 = array(
                    			'page_id'      	 => '9',
                    			'insert_id'      => $insert,
                    			'status'         => '0',
                    			'member_id'      => $rider_id,
                    			'user_type'     =>5
                    			);
                    
              	$insert1 = $this->service_m->insert('admin_notification', $array1);
             }
				
              	if(!empty($insert))
              	{
					$this->response(['responsecode' => 1,'response' => 'Complaint registered sucessfully'], REST_Controller::HTTP_OK);
				}
				else
				{
					$this->response(['responsecode' => 0,'response' => 'fail'], REST_Controller::HTTP_OK);
				}
	       }
	    }

	// feedback trip for rider api //
	public function rider_feedbackride_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		$driver_id = $getdetails->{'driver_id'};
		$rating = $getdetails->{'rating'};
		$feedback = $getdetails->{'feedback'};
		$ride_id = $getdetails->{'ride_id'};
			 
		if($rider_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter rider id'], REST_Controller::HTTP_OK);
		}
		elseif($driver_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter driver id'], REST_Controller::HTTP_OK);
		}
		elseif($rating == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter rating'], REST_Controller::HTTP_OK);
		}
		else
		{
            $array = array(
                     		'user_id'     	=> $rider_id,
                     		'driver_id'   	=> $driver_id,
                     		'rate'        	=> $rating,
                     		'type'        	=> 2,
                     		'user_type_id'	=> 5,
                     		'comment'     	=> $feedback,
                     		'booking_id'  	=> $ride_id,
                     		'rating_status'	=> 1
                      );

            $where = array('booking_id' => $ride_id,'user_id' => $rider_id);    
            $update = $this->service_m->update('driverrating',$where,$array);
            if($update != '')
			{
				$array1 = array(
                    			'page_id'      => '13',
                    			'insert_id'    => $update,
                    			'status'       => '0',
                    			'member_id'    => $rider_id,
                    			'user_type'    => 5
                    			);
                    
              	$insert = $this->service_m->insert('admin_notification', $array1);
				$this->response(['responsecode' => 1,'response' => 'Thank you for feedback!'], REST_Controller::HTTP_OK);
			}
			else
			{
				$this->response(['responsecode' => 0,'response' => 'Fail!'], REST_Controller::HTTP_OK);
			}
		}
	}

	// getting the instant booking detail //
	public function rider_ride_instant_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		
		$myinstant = $this->service_m->getRows("SELECT r.*,dd.fname,dd.lname,dd.mobile,dd.carcategory FROM ride r, driver_details dd  WHERE dd.id = r.driver_id AND r.user_id = '".$rider_id."' AND r.book_type = 1  AND r.ride_status in (2,4) ORDER BY r.id DESC");
		$sql = $this->db->last_query();

		if($rider_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter rider id'], REST_Controller::HTTP_OK);
		}	
		else if($myinstant != NULL)
		{
			$this->response(['responsecode' => 1,'mytrip' => $myinstant,'response' => 'Success!'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->response(['responsecode' => 0,'mytrip' => [],'response' => 'Fail!'], REST_Controller::HTTP_OK);
		}
	}

	// getting the prebooking ride detail
	public function rider_ride_prebooking_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};

		$myprebookings = $this->service_m->getRows("SELECT r.*,dd.fname,dd.lname,dd.mobile,dd.carcategory FROM ride r, driver_details dd WHERE dd.id=r.driver_id AND r.user_id = '".$rider_id."' AND r.book_type = 2 ORDER BY r.id DESC");

		if($rider_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter ride id'], REST_Controller::HTTP_OK);
		}
		elseif($myprebookings != NULL)
		{
			$this->response(['responsecode' => 1,'myprebookings' => $myprebookings,'response' => 'Success!'], REST_Controller::HTTP_OK);
		}
		else
		{
			$this->response(['responsecode' => 0,'myprebookings' => [],'response' => 'Fail!'], REST_Controller::HTTP_OK);
		}
	}

	// getting the ride details //
	public function rider_ride_detail_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		$ride_id = $getdetails->{'ride_id'};
		
		$myridedetails = $this->service_m->getRow("SELECT r.*,dd.fname,dd.lname,dd.mobile as drivermobile,dd.plate_no,vc.category_name,dd.profile_photo FROM ride r,driver_details dd,vehicle_category vc WHERE r.driver_id = dd.id and r.user_id = '".$rider_id."'  AND r.id= '".$ride_id."' AND dd.vehicle_type = vc.id");

		$imagedetails = base_url().'uploads/driver/'.$myridedetails->dd.profile_photo;

		$driver_id = $this->service_m->getVal("SELECT dd.id FROM ride r,driver_details dd,vehicle_category vc WHERE r.driver_id = dd.id and r.user_id = '".$rider_id."'  AND r.id= '".$ride_id."' AND dd.vehicle_type = vc.id");

		$myratings = $this->service_m->getVal("SELECT AVG(rate) as driverratings FROM driverrating dr, members m WHERE dr.driver_id = '".$driver_id."' and m.org_id = dr.driver_id and m.usertype_id = 4"); 

		if(!empty($myratings))
		{
			$myrating = number_format($myratings,2);
		}
		else
		{
			$myrating = number_format(5.00,2);
		}

		if($rider_id == '')
		{
			$this->response(['responsecode' => 0,'response' => 'Please enter id'], REST_Controller::HTTP_OK);
		}
		else if($myridedetails != NULL)
		{
			$this->response(['responsecode' => 1,'myridedetails' => $myridedetails,'imagedetails' => $imagedetails,'driverrating' => $myrating,'response' => 'Success!'], REST_Controller::HTTP_OK); 
		}
		else
		{
			$this->response(['responsecode' => 0,'myridedetail' => $myridedetail,'response' => 'Fail!'], REST_Controller::HTTP_OK); 
		}
	}

	// rider profile data getting //
	public function rider_userprofile_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		$getdata = $this->service_m->getRow("SELECT * FROM members where id='".$rider_id."'");

		if(!empty($getdata))
		{	
			$alldetails =  array(
				  						'riderid'	=> $getdata->id,
				  						'fullname'	=> $getdata->display_name, 
				  						'country'	=> $getdata->country_id,
				  						'email'		=> $getdata->email,
				  						'password'	=> $getdata->password,
				  						'user_image'=> $getdata->user_image,
				  						'mobile'    => $getdata->mobile_no	
				  					);
		}  
	    if(!empty($alldetails))
            {
		       $this->response(['responsecode' => 1,"baseurl" => base_url().'uploads/rider/','response' => $alldetails], REST_Controller::HTTP_OK);
	        }
		 else
            {
               $this->response(['responsecode' => 0,'response' => "Data not found"], REST_Controller::HTTP_OK);
            }
	}

	// retrive ride of ride details //
	public function rider_retrieve_ride_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};

		$myridedetails = $this->service_m->getRow("SELECT r.*,dd.fname,dd.lname,dd.mobile as drivermobile,dd.plate_no,vc.category_name,dd.profile_photo FROM ride r,driver_details dd,vehicle_category vc WHERE r.driver_id = dd.id and 
			r.user_id = '".$rider_id."'  AND r.ride_status IN (1,3) AND dd.vehicle_type = vc.id ORDER BY r.id DESC LIMIT 1");

		$imagedetails = base_url().'uploads/driver/'.$myridedetails->profile_photo;

		$driver_id = $this->service_m->getVal("SELECT dd.id FROM ride r,driver_details dd,vehicle_category vc WHERE r.driver_id = dd.id and r.user_id = '".$rider_id."' AND dd.vehicle_type = vc.id");

		$myratings = $this->service_m->getVal("SELECT AVG(rate) as driverratings FROM driverrating dr, members m WHERE dr.driver_id = '".$driver_id."' and m.org_id = dr.driver_id and m.usertype_id = 4"); 

		if(!empty($myratings))
		{
			$myrating = number_format($myratings,2);
		}
		else
		{
			$myrating = number_format(5.00,2);
		}

		if(!empty($myridedetails))
		{
			$this->response(['responsecode' => 1,"getridedetails" => $myridedetails,'imagedetails' => $imagedetails,'ratings' => $myrating,'response' => 'Success!'], REST_Controller::HTTP_OK);
		}
		else
		{
			$this->response(['responsecode' => 0,"getridedetails" => [],'response' => 'Fail!'], REST_Controller::HTTP_OK);
		}
	}

	// get trip history details //
	public function rider_ride_history_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};

		$getridehistory = $this->service_m->getRows('SELECT * FROM ride WHERE user_id = '.$rider_id.' AND ride_status IN (2,4) ORDER BY id DESC');

		if(!empty($getridehistory))
		{
			$this->response(['responsecode' => 1,'getridehistory' => $getridehistory,'response' => 'Success!'], REST_Controller::HTTP_OK);
		}
		else
		{
			$this->response(['responsecode' => 0,'getridehistory' => [],'response' => 'Fail!'], REST_Controller::HTTP_OK);
		}  
	}

	// save rider rating details in db //
	public function rider_setfeedback_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		$driver_id = $getdetails->{'driver_id'};
		$rating = $getdetails->{'rating'};
		$feedback = $getdetails->{'feedback'};
		$ride_id = $getdetails->{'ride_id'};

		$array = array(
                     		'user_id'     	=> $rider_id,
                     		'driver_id'   	=> $driver_id,
                     		'rate'        	=> $rating,
                     		'type'        	=> 2,
                     		'user_type_id'	=> 5,
                     		'comment'     	=> $feedback,
                     		'booking_id'  	=> $ride_id,
                     		'rating_status'	=> 0
                      );

		$insert = $this->service_m->insert('driverrating',$array);

		if(!empty($insert))
		{
			$this->response(['responsecode' => 1,'response' => 'Success!'], REST_Controller::HTTP_OK);
		}
		else
		{
			$this->response(['responsecode' => 0,'response' => 'Fail!'], REST_Controller::HTTP_OK);
		}
	}

	// get rider rating details from db //
	public function rider_retrieve_feedback_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};


		$getratingdetails = $this->service_m->getRow('SELECT dr.* FROM driverrating dr,ride r WHERE r.user_id = '.$rider_id.' AND dr.rating_status = 0  AND r.user_id = dr.user_id AND  r.id = dr.booking_id AND r.ride_status = 4 ORDER BY r.id DESC LIMIT 1');

		if(!empty($getratingdetails))
		{
			$this->response(['responsecode' => 1,'ratingdetails' => $getratingdetails,'response' => 'Success!'], REST_Controller::HTTP_OK);
		}
		else
		{
			$this->response(['responsecode' => 0,'ratingdetails' => 'NULL','response' => 'Fail!'], REST_Controller::HTTP_OK);
		}
	}

	// fetch rider current ride //
	public function rider_currentride_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};

		$getridedetail = $this->service_m->getRow('SELECT * FROM ride WHERE user_id = '.$rider_id.' and book_type = 1 and ride_status = 0 ORDER BY id DESC');

		if($getridedetail != '')
		{
			$bookingdate = date('d m Y  H:i:s',strtotime($getridedetail->book_datetime));
			$this->response(['responsecode' => 1,'ridedetail' => $getridedetail,'bookingdate' => $bookingdate,'response' => 'Success!'], REST_Controller::HTTP_OK);	
		}
		else
		{
			$this->response(['responsecode' => 0,'ridedetail' => [],'response' => 'Fail!'], REST_Controller::HTTP_OK);
		} 
	}

	// rider rebook the ride //
	public function rider_ride_rebook_post()
	{
		$getdetail = file_get_contents("php://input");
		$getdetails = json_decode($getdetail);
		$rider_id = $getdetails->{'rider_id'};
		$ride_id = $getdetails->{'ride_id'};

		$getridedetail = $this->service_m->getRow('SELECT * FROM ride WHERE id = '.$ride_id.' and user_id = '.$rider_id.' and ride_status = 0 ORDER BY id DESC');

		if(!empty($getridedetail->country_id)) 
  		{
  			$gettimezone = $this->service_m->getVal('SELECT timezone FROM countries WHERE id='.$getridedetail->country_id.'');
    		date_default_timezone_set($gettimezone);
    		$book_date= date('d-m-Y H:i:s');
    		$book_time= date('H:i');
  		}

		$array = array(
						'book_datetime' => $book_date, 
					);

		$where = array('id' => $ride_id, 'user_id' => $rider_id);

		$update = $this->service_m->update('ride',$where,$array);  

		if($getridedetail != '')
		{
			
			$notification = $this->admin->getRows('SELECT m.device_id,m.device_type,m.token_id,MIN( 6371 * acos( cos( radians('.$getridedetail->pickuplat.') ) * cos( radians( dd.driver_lat ) ) * cos( radians( dd.driver_long ) - radians('.$getridedetail->pickuplong.') ) + sin( radians('.$getridedetail->pickuplat.') ) * sin(radians(dd.driver_lat)) ) ) AS distance FROM members m, driver_details dd WHERE m.org_id = dd.id and dd.activestatus = 1 and  dd.dispatch_id = "'.$getridedetail->dispatch_id.'" and m.usertype_id = 4 and dd.carcategory = '.$getridedetail->car_category.' and dd.vehicle_type = '.$getridedetail->vichle_type.' HAVING distance < 8 ORDER BY distance ASC');

			foreach($notification as $notifications)
  			{
  				$device_id = $notifications->device_id;
  			
   				$fcmMsg = array(
   							'body' => 'You have recieved auto booking_'.$getridedetail->id.'_'.$getridedetail->book_type.'_'.$getridedetail->book_datetime.'_'.$getridedetail->pickupaddress.'_'.$getridedetail->fullname,
   							'title' => 'The Candy Booking',
   							'sound' => "default",
   							'color' => "#203E78",
   							'category' => "oneCabbiDriver" 
     						);
   
			if($notifications->device_type == 1) 
			{
				$fcmFields = array(
  									'to'   => $notifications->token_id,
  									'data' => $fcmMsg
    								);
			}
			else
			{
  				$fcmFields = array(
  									'to'   => $notifications->token_id,
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
			$results = json_decode($result);
			$notiarr = array(
                          'ride_id' => $getridedetail->id,
                          'status'  => $results->{'success'} 
                        );
        	$notiinsert = $this->service_m->insert('notification_check',$notiarr);
			curl_close( $ch );

			$this->response(['responsecode' => 1,'response' => 'Your Ride Rebooked Successfully'], REST_Controller::HTTP_OK);
      	}

      	 $this->response(['responsecode' => 0,'response' => 'Your Ride Rebooked Fail!'], REST_Controller::HTTP_OK);
	}	

	}


}
	
