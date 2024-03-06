<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function __construct()
{
    //parent::__construct();

}

function has_permission($pageid,$type)
{
    $CI =& get_instance();
    $CI->db->where('id',$CI->session->userdata('admin_id'));
    $query = $CI->db->get('members');
    $result =  $query->row();
    if($result->types==1)
    {
        return true;
    }
    else
    {
        if($type=='add'){ $type2='add_access'; $val=1;}
        elseif($type=='edit'){ $type2='edit_access';$val=1;}
        elseif($type=='delete'){ $type2='delete_access';$val=1;}
        elseif($type=='view'){ $type2='view_access';$val=1;}
        elseif($type=='no'){ $type2='no_access';$val=0;}
        $per=array_key_exists($pageid, $CI->session->userdata('permission'));
        if(!empty($per))
        {
            $per=$CI->session->userdata('permission');
            if($per[$pageid][$type2]==$val)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}

function generatePIN($digits = 4){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

function msg()
{
    $CI =& get_instance();
    $msg='';
    $all = $CI->messages->get();
    if($all)
    {
        foreach ($all as $type => $messages)
        {
            foreach ($messages as $message)
            {
                if ($type == 'alert-danger'){ $icon='<span class="glyphicon glyphicon-exclamation-sign"></span>'; }
                if ($type == 'alert-success'){ $icon='<span class="glyphicon glyphicon-ok"></span>'; }
                if ($type == 'alert-info'){ $icon='<span class="glyphicon glyphicon-info-sign"></span>'; }
                if ($type == 'alert-warning'){ $icon='<span class="glyphicon glyphicon-alert"></span>'; }
                $msg='<div class="alert  alert-dismissable '.$type.'"><a href="" class="close" data-dismiss="alert" aria-label="close">×</a>'.$message.'</div>';
            }
        }
        return $msg;
    }
    else
    {
        return $msg;
    }
}

function has_subscription()
{
    $CI =& get_instance();
    //$CI->db->where('user_id',$CI->session->userdata('admin_id'));
    //$query = $CI->db->get('subscription');
    $query =  $CI->db->query('select * from subscription where user_id='.$CI->session->userdata('admin_id').' order by id desc limit 0,1');
    $result =  $query->row();
    if(!empty($result))
    {
        if($result->status==2)
        {
            return false;
        }
        else
        {
            return true;
        }

    }
    else
    {
        return false;
    }
}

function getcms($id)
{
    $CI =& get_instance();
    $CI->db->where('page_id',$id);
    $query = $CI->db->get('cms');
    $result =  $query->row();
    return $result->heading;
}
function get_country()
{
    $CI =& get_instance();
    $query = $CI->db->get('country');
    $result =  $query->result();
    return $result;
}

function admin_roll($id="")
{
    $CI =& get_instance();
    if($id)
    {
        $CI->db->where('id',$id);
    }
    $query = $CI->db->get('role');
    $result =  $query->result();
    return $result;
}

function getcountryname($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('country');
    $result =  $query->result();
    return $result;
}
function getclient()
{
    $CI =& get_instance();
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}
function getname($id='')
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}

function getcategory($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('id',$id);
    }
    $CI->db->where('status','1');
    $CI->db->order_by('name','asc');
    $query = $CI->db->get('category');
    $result =  $query->result();
    return $result;
}

function getportfolio($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('id',$id);
    }
    $CI->db->where('status','1');
    $CI->db->order_by('id','asc');
    $query = $CI->db->get('portfolio');
    $result =  $query->result();
    return $result;
}

function get_all_portfolio_images($id="")
{
    $CI =& get_instance();
    $CI->db->where('portfolio_id',$id);
    $CI->db->order_by('id','desc');
    $query = $CI->db->get('portfolio_images');
    $result =  $query->result();
    return $result;
}

function get_all_users()
{
    $CI =& get_instance();
    $query = $CI->db->get('users');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getdatehoding($id="",$date)
{
    $CI =& get_instance();
    $CI->db->where('client_id',$id);
    $CI->db->where('DATE(added_date)',$date);
    $query = $CI->db->get('client_holding');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_all_holding($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('client_id',$id);
    }
    $query = $CI->db->get('client_holding');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_act_holding($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('client_id',$id);
    }
    $CI->db->where('status',1);
    $query = $CI->db->get('client_holding');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_iact_holding($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('client_id',$id);
    }
    $CI->db->where('status',0);
    $query = $CI->db->get('client_holding');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_all_country()
{
    $CI =& get_instance();
    $query = $CI->db->get('country');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_all_currency()
{
    $CI =& get_instance();
    $query = $CI->db->get('currency');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}

function get_all_contactus()
{
    $CI =& get_instance();
    $query = $CI->db->get('contact');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function admin_data()
{
    $CI =& get_instance();
    $id=$CI->session->userdata('admin_userid');
    $CI->db->where('id',$id);
    $query = $CI->db->get('admin_login');
    $result =  $query->result();
    return $result;
}
function user_data()
{
    $CI =& get_instance();
    $id=$CI->session->userdata('userid');
    $CI->db->where('id',$id);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}

















function send_sms($to,$message)
{
    if($_SERVER['HTTP_HOST'] == "192.168.0.5" || $_SERVER['HTTP_HOST'] == "103.36.121.52:82" )
    {
        $to='91'.$to;
        $url="http://104.156.253.108:8008/websmpp/websms?user=OMSoftD&pass=fQ4c5Gyz&sid=KWIKBL&mno=".trim($to)."&text=".urlencode($message)."&type=1";
    }
    elseif($_SERVER['HTTP_HOST'] == "www.omsoftware.us" || $_SERVER['HTTP_HOST'] == "omsoftware.us")
    {
        $to='91'.$to;
        $url="http://104.156.253.108:8008/websmpp/websms?user=OMSoftD&pass=fQ4c5Gyz&sid=KWIKBL&mno=".trim($to)."&text=".urlencode($message)."&type=1";
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    $body = $result;
    return $body;
}

function send_mail($to="",$type="",$vars="")
{
    $CI =& get_instance();
    //'smtp_host' => 'mail.omsoftware.us',
    //'smtp_port' => 25,
    //'smtp_user' => 'neeraj@omsoftware.us',
    //'smtp_pass' => 'p#T),4M[Jm1d',
    $query= $CI->db->get('setting');
    $getmaildetail =  $query->result();
    $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => $getmaildetail[0]->mail_host,
        'smtp_port' => $getmaildetail[0]->port,
        'smtp_user' => $getmaildetail[0]->mail_uname,
        'smtp_pass' => $getmaildetail[0]->mail_password,
        //'smtp_timeout' => '4',
        'mailtype'  => 'html',
        'charset'   => 'utf-8',
        'wordwrap'   => 'TRUE'
    );

    $this->load->library('email', $config);
    $this->email->set_newline("\r\n");
    $this->email->from('support@omsoftware.net', 'Mobile Engineer');
    $where = array('email_type'=> $type);
    $emailbody['body']=$this->home_m->getWhere('emails',$where);
    foreach($emailbody['body'] as $subject)
    {
        $sub = $subject->subject;
    }
    $body = $this->load->view('user_mail_format',$emailbody,TRUE);
    if($vars!="")
    {
        if(count($vars))
        {
            foreach($vars as $key => $val)
            {
                if($key=='url'){$val="<a href='".$val."'>Click Here</a></h1>";}
                $body=str_replace($key,$val,$body);
            }
            $body = str_replace("{","",$body);
            $body = str_replace("}","",$body);
        }
    }
    $this->email->to($to);  // replace it with receiver mail id
    $this->email->subject($sub); // replace it with relevant subject
    $this->email->message($body);
    $this->email->set_mailtype('html');
    $this->email->send();
}
function get_service($id="")
{
    $CI =& get_instance();
    if($id)
    {
      $CI->db->where('id',$id);
    }
    $CI->db->where('status',1);
    $query = $CI->db->get('service_type');
    $result =  $query->result();
    return $result;
}

function device_name($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('device_type');
    $result =  $query->result();
    return $result;
}


function get_request($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('request');
    $result =  $query->result();
    return $result;
}
function get__lestest_request($id="")
{
    $CI =& get_instance();
    $CI->db->limit(10);
    $CI->db->order_by('id','desc');
    $query = $CI->db->get('request');
    $result =  $query->result();
    return $result;
}
function get_request_one($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('request');
    $result =  $query->result();
    return $result;
}

function get_make_request($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('make_request');
    $result =  $query->result();
    return $result;
}

function getcat($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('id',$id);
    }
    $CI->db->where('status',1);
    $query = $CI->db->get('service_category');
    $result =  $query->result();
    return $result;
}
function getsubcat($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('id',$id);
    }
    $CI->db->where('status',1);
    $query = $CI->db->get('service_sub_category');
    $result =  $query->result();
    return $result;
}
function get_accept_request($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('make_request');
    $result =  $query->result();
    return $result;
}
function get_product_5($id="")
{
    $CI =& get_instance();
     $CI->db->limit(3);
     $CI->db->order_by('id','desc');
    $query = $CI->db->get('product');
    $result =  $query->result();
    return $result;
}
function get_product($id="")
{
    $CI =& get_instance();
    if(!empty($id))
    {
        $CI->db->where('user_id',$id);
    }
    $query = $CI->db->get('product');
    $result =  $query->result();
    return $result;
}
function get_visit_list($id="")
{
    $CI =& get_instance();
    $CI->db->where('seller_id',$id);
    $query = $CI->db->get('visit_list');
    $result =  $query->result();
    return $result;
}

function get_Users()
{
    $CI =& get_instance();
    $CI->db->where('user_type',1);
    $query = $CI->db->get('users');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_all_product()
{
    $CI =& get_instance();
    $query = $CI->db->get('product');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}

function get_commission($id="")
{
    $CI =& get_instance();
    $CI->db->select_avg('admin_commission');
    $query = $CI->db->get('transaction');
    $result =  $query->result();
    return $result;
}

function get_last_users($type="")
{
    $CI =& get_instance();
    $CI->db->where('user_type',$type);
    $CI->db->limit(8);
    $CI->db->order_by('id','desc');
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}

function get_contactus()
{
    $CI =& get_instance();
    $query = $CI->db->get('contact');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_sellers()
{
    $CI =& get_instance();
    $CI->db->where('user_type',3);
    $query = $CI->db->get('users');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_service_provider()
{
    $CI =& get_instance();
    $CI->db->where('user_type',2);
    $query = $CI->db->get('users');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_all_request()
{
    $CI =& get_instance();
    $query = $CI->db->get('request');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_service_category()
{
    $CI =& get_instance();
    $query = $CI->db->get('service_type');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_service_sub_category()
{
    $CI =& get_instance();
    $query = $CI->db->get('service_sub_category');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_devices()
{
    $CI =& get_instance();
    $query = $CI->db->get('device_type');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_colors()
{
    $CI =& get_instance();
    $query = $CI->db->get('color_type');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function timeAgo($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v)
    {
        if ($diff->$k)
        {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        }
        else
        {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function get_user_rate($id="")
{
    $CI =& get_instance();
    $CI->db->where('rate_from',$id);
    $query = $CI->db->get('rating');
    $result =  $query->result();
    return $result;
}
function get_sp_rate($id="")
{
    $CI =& get_instance();
    $CI->db->where('rate_to',$id);
    $query = $CI->db->get('rating');
    $result =  $query->result();
    return $result;
}
function get_user_payment_history($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('transaction');
    $result =  $query->result();
    return $result;
}


function getbrandlogo()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('brand_logo');
    $result =  $query->result();
    return $result;
}

function getBuyHow()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
     $CI->db->where('type','buy');
     $CI->db->limit(3);
    $query = $CI->db->get('how_it_works');
    $result =  $query->result();
    return $result;
}
function get_repair_type()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('repair_type');
    $result =  $query->result();
    return $result;
}
function get_repair_type_withid($id="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('id',$id);
    $query = $CI->db->get('repair_type');
    $result =  $query->result();
    return $result;
}
function get_repair_type_admin($vehicleid)
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('vehicleid',$vehicleid);
    $query = $CI->db->get('repair_type');
    $result =  $query->result();
    return $result;
}
function get_Query_Image($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$id);
    $query = $CI->db->get('query_image');
    $result =  $query->result();
    return $result;
}
function get_Query_Image_admin($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$id);
    $CI->db->where('status',0);
    $query = $CI->db->get('query_image');
    $result =  $query->result();
    return $result;
}
function get_Query_video_admin($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$id);
    $CI->db->where('status',1);
    $query = $CI->db->get('query_image');
    $result =  $query->result();
    return $result;
}
function getqueryimage($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$id);
     $CI->db->limit(1);
    $query = $CI->db->get('query_image');
    $result =  $query->result();
    return $result;
}
function getproviderdetail($id="",$vehicle="")
{
    $CI =& get_instance();
    $CI->db->where('vehicle_type',$vehicle);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}
function getServiceDetail($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);

    $query = $CI->db->get('vehicles');
    $result =  $query->result();
    return $result;
}

function getVehicleRange($id="")
{
    $CI =& get_instance();
    $CI->db->where('vehicle_id',$id);
    $CI->db->limit(1);
    $query = $CI->db->get('vehicle_range');
    $result =  $query->result();
    return $result;
}


function getQueryreview($queryid="",$userid="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$queryid);
    //$CI->db->where('rate_from',$userid);
    //$CI->db->where('rate_to',$id);
    $query = $CI->db->get('rating');
    $result =  $query->result();
    return $result;
}
function getreview($id="",$userid="")
{
    $CI =& get_instance();
    $CI->db->where('rate_from',$userid);
    $CI->db->where('rate_to',$id);
    $query = $CI->db->get('rating');
    $result =  $query->result();
    return $result;
}
function slider_image($id="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('slider');
    $result =  $query->result();
    return $result;
}
function getBranchDetail($id="")
{
    $CI =& get_instance();
    //$CI->db->where('status',1);
    //$CI->db->where('main_branch',0);
    $CI->db->order_by('id','desc');
    $CI->db->where('sp_team_id',$id);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}
function getBranchTiming($id="",$main)
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('branch_id',$id);
    $query = $CI->db->get('branch_timing');
    $result =  $query->result();
    return $result;
}
function fetch_cms($linkname="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('linkname',$linkname);
    $query = $CI->db->get('cms');
    $result =  $query->result();
    return $result;
}
function fetch_settings($feildname="")
{
    $CI =& get_instance();
    $query = $CI->db->get('setting');
    $result =  $query->result();
    return $result;
}
function get_slider_content()
{
    $CI =& get_instance();
    $query = $CI->db->get('setting');
    $result =  $query->result();
    return $result;
}
function getvehicle_service()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    //$CI->db->where_not_in('type_for', 'buy');
    $query = $CI->db->get('vehicle_type');
    $result =  $query->result();
    return $result;
}

function getquery($id="")
{
    $CI =& get_instance();
    $where=array('user_id'=>$id,'status'=> 1);
    $CI->db->where($where);
    $query = $CI->db->get('query');
    $result =  $query->result();
    return $result;
}
function getqueryforshop($id="")
{
    $CI =& get_instance();
    $CI->db->where('id', $id);

    $query = $CI->db->get('query');
    $result =  $query->result();
    return $result;
}
function getshop_query($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id', $id);
    $query = $CI->db->get('query_offer');
    $result =  $query->result();
    return $result;
}

function getServicehistory($id="")
{
    $CI =& get_instance();
    $aray=array('user_id'=> $id,'status'=> '2');
    $CI->db->where($aray);
    $query = $CI->db->get('query');
    $result =  $query->result();
    return $result;
}
function getvehicleDetail($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id', $id);
    $query = $CI->db->get('vehicles');
    $result =  $query->result();
    return $result;
}
function getvehicle1($id="")
{
    $CI =& get_instance();
    $CI->db->where_not_in('vehicleid', $id);
    $query = $CI->db->get('vehicle_type');
    $result =  $query->result();
    return $result;
}
function vehicle_type($id="")
{
    $CI =& get_instance();
    $CI->db->where('vehicleid', $id);
    $query = $CI->db->get('vehicle_type');
    $result =  $query->result();
    return $result;
}
function get_vehicle()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('vehicle_type');
    $result =  $query->result();
    return $result;
}
function getCounterDetail()
{
    $CI =& get_instance();
    $query = $CI->db->get('visiter_counter');
    $result =  $query->result();
    return $result;
}
function get_model()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('vehicle_model');
    $result =  $query->result();
    return $result;
}
function get_model_for_service($modelid="")
{
    $CI =& get_instance();
    $CI->db->where('modelid',$modelid);
    $CI->db->where('type_for','service');
    $query = $CI->db->get('vehicle_model');
    $result =  $query->result();
    return $result;
}
function get_model_for_buy($modelid="")
{
    $CI =& get_instance();
    $CI->db->where('modelid',$modelid);
    $CI->db->where('type_for','buy');
    $query = $CI->db->get('vehicle_model');
    $result =  $query->result();
    return $result;
}
function get_modelWithid($id="")
{
    $CI =& get_instance();
    $CI->db->where('modelid',$id);
    $query = $CI->db->get('vehicle_model');
    $result =  $query->result();
    return $result;
}
function get_response_data($id="")
{
    $CI =& get_instance();
    $CI->db->where('queryid',$id);
    $query = $CI->db->get('response');
    $result =  $query->result();
    return $result;
}
function get_no_of_response($id="")
{
    $CI =& get_instance();
    //$userid=$CI->session->userdata('userid');
    $CI->db->where('queryid',$id);
    //$CI->db->where('user_id',$userid);
    $query = $CI->db->get('response');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getproviderimage($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('user_images');
    $result =  $query->result();
    return $result;
}
function get_fuele()
{
    $CI =& get_instance();
     $CI->db->where('status',1);
    $query = $CI->db->get('vehicle_fuel');
    $result =  $query->result();
    return $result;
}
function get_fuele_with_vehicle($type="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('vehicle_type',$type);
    $query = $CI->db->get('vehicle_fuel');
    $result =  $query->result();
    return $result;
}
function get_city()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('city');
    $result =  $query->result();
    return $result;
}
function getplandata($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('users_plan');
    $result =  $query->result();
    return $result;
}

function getsubscription($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$id);
    $query = $CI->db->get('subscription');
    $result =  $query->result();
    return $result;
}
function providerdetail($id="")
{
    $CI =& get_instance();
    $CI->db->where('vehicle_type',$id);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}



function getuserprofile($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}

function getvarientimage($id="")
{
    $CI =& get_instance();
    $CI->db->where=array('varientid'=>$id);
    $query = $CI->db->get('vehicle_varient');
    $result =  $query->result();
    return $result;
}

function get_sp_typeid($id="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('id',$id);
    $query = $CI->db->get('serviceprovider_type');
    $result =  $query->result();
    return $result;
}
function getemailtype($type="")
{
    $CI =& get_instance();

    $CI->db->where('type',$type);
    $query = $CI->db->get('email_type');
    $result =  $query->result();
    return $result;
}
function get_sp_type()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('serviceprovider_type');
    $result =  $query->result();
    return $result;
}

function get_subscription($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('subscription');
    $result =  $query->result();
    return $result;
}
function getmtype($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('member_type');
    $result =  $query->result();
    return $result;
}

function get_userplan_name($id="")
{
    $CI =& get_instance();
    if($id>0)
    {
    $CI->db->where('id',$id);
    }
    $query = $CI->db->get('users_plan');
    $result =  $query->result();
    return $result;
}
function get_plan_nameforupgrade($id="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('rate_plan');
    $result =  $query->result();
    return $result;
}
function get_plan_name($id="")
{
    $CI =& get_instance();
    if($id>0)
    {
    $CI->db->where('id',$id);
    }
    $query = $CI->db->get('rate_plan');
    $result =  $query->result();
    return $result;
}
function get_subsby_service($id="")
{
    $CI =& get_instance();
    $CI->db->where('sp_id',$id);
    $CI->db->where('subscription_type',1);
    $type=array(1,3);
    $CI->db->where_in('status', $type);
    $query = $CI->db->get('subscription');
    $result =  $query->result();
    return $result;
}

function get_advertisement()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    //$CI->db->or_where('to_date', date('Y-m-d'));
   //$CI->db->where("DATE_FORMAT(to_date,'%d-%m-%Y')" , date('Y-m-d'));
    $CI->db->like('advertisement_for', 'Customer');
    $query = $CI->db->get('advertisement');
    $result =  $query->result();
    return $result;
}
function get_uplan()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->order_by("valid1","desc");
    $query = $CI->db->get('users_plan');
    $result =  $query->result();
    return $result;
}
function get_usersplan_name($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    // $CI->db->order_by('valid1','asc');
    $query = $CI->db->get('users_plan');
    $result =  $query->result();
    return $result;
}

function get_query($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('query');
    $result =  $query->result();
    return $result;
}
function getUserRatingandreview($id="")
{
    $CI =& get_instance();
    $CI->db->where('rate_to',$id);
    $query = $CI->db->get('rating');

    $result =  $query->result();
    return $result;
}
function getUserRating($id="")
{
    $CI =& get_instance();
    $CI->db->where('rate_to',$id);
    $query = $CI->db->get('rating');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getContactNumber($id="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('branch_id',$id);
    $query = $CI->db->get('branch_mobile');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_rols()
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $query = $CI->db->get('member_type');
    $result =  $query->result();
    return $result;
}


function get_emailtype()
{
    $CI =& get_instance();
    $query = $CI->db->get('email_type');
    $result =  $query->result();
    return $result;
}


function getdealdate($id)
{
    $CI =& get_instance();
    $CI->db->where('deal_id',$id);
    $query = $CI->db->get('deals');
    $result =  $query->result();
    return $result;
}
function getusername($id)
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('users');
    $result =  $query->result();
    return $result;
}

function selectMyVehicle()
{
    $CI =& get_instance();
    $CI->db->select('vehicle.id,vehicle.brand,vehicle_brand.vehicle_brand');
    $CI->db->from('vehicle');
    $CI->db->join('vehicle_brand','vehicle.brand=vehicle_brand.brandid');
    //$CI->db->where('tbl_usercategory','admin');
    $query=$this->db->get();
    $data= $query->result_array();
}
function getmanagevehicle($brandid)
{
    $CI =& get_instance();
    $CI->db->where('brandid',$brandid);
     $CI->db->where('type_for','service');
    $query = $CI->db->get('vehicle_brand');
    $result =  $query->result();
    return $result;
}

function getbrand_for_service($brandid)
{
    $CI =& get_instance();
    $CI->db->where('brandid',$brandid);
    $CI->db->where('type_for','service');
    $query = $CI->db->get('vehicle_brand');
    $result =  $query->result();
    return $result;
}
function getbrand_for_buy($brandid)
{
    $CI =& get_instance();
    $CI->db->where('brandid',$brandid);
    $CI->db->where('type_for','buy');
    $query = $CI->db->get('vehicle_brand');
    $result =  $query->result();
    return $result;
}
function get_user_permissions($id)
{
    $CI =& get_instance();
    $CI->db->where('admin_id',$id);
    $query = $CI->db->get('permissions');
    $result =  $query->result();
    return $result;
}
function get_user_permissions2($userid,$pageid)
{
    $CI =& get_instance();
    $CI->db->where('admin_id',$userid);
    $CI->db->where('page_id',$pageid);
    $query = $CI->db->get('permissions');
    $result =  $query->result();
    return $result;
}
function gettransactiondetail($id)
{
    $CI =& get_instance();
    $CI->db->where('deal_id',$id);
    $query = $CI->db->get('transaction');
    $result =  $query->result();
    return $result;
}

function getmobile_no($id="")
{
    $CI =& get_instance();
    $CI->db->where('branch_id',$id);
    $query = $CI->db->get('branch_mobile');
    $result =  $query->result();
    return $result;
}
function notification($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('notification');
    $CI->db->order_by('id','desc');
    $result =  $query->result();
    return $result;
}
function getnotificationtype($type="")
{
    $CI =& get_instance();
    $CI->db->where('type',$type);
    $query = $CI->db->get('notification_message');
    $result =  $query->result();
    return $result;
}

function getcurrency($id="")
{
    $CI =& get_instance();
    $CI->db->where('currency_id',$id);
    $query = $CI->db->get('currency');
    $result =  $query->result();
    return $result;
}

function getvehicleimage($id="")
{
    $CI =& get_instance();
    $CI->db->where('id',$id);
    $query = $CI->db->get('vehicles');
    $result =  $query->result();
    return $result;
}

function getQuerycount($id="")
{
    $CI =& get_instance();
    $CI->db->where('status',1);
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('query');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getServiceRemindrcount($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $query = $CI->db->get('service_reminder');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}




function getCustomerReviews()
{
    $CI =& get_instance();
    $CI->db->where('field', 'customer_count');
    $query = $CI->db->get('settings');
    $result =  $query->result();
    return $result[0]->value;
}
function getDealers()
{
    $CI =& get_instance();
    $CI->db->where('field', 'service_station_count');
    $query = $CI->db->get('settings');
    $result =  $query->result();
    return $result[0]->value;
}
function get_deals()
{
    $CI =& get_instance();
    $query = $CI->db->get('deals');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}


function get_transactions()
{
    $CI =& get_instance();
    $query = $CI->db->get('transaction');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}


function get_disputetransactions()
{
    $CI =& get_instance();
    $CI->db->where('status',4);
    $query = $CI->db->get('transaction');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}


function get_bitcoinaccount()
{
    $CI =& get_instance();
    $query = $CI->db->get('bitcoin_account');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_unreadnotification($id="")
{
    $CI =& get_instance();
    $CI->db->where('user_id',$id);
    $CI->db->where('unread',1);
    $query = $CI->db->get('notification');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getShowroom()
{
    $CI =& get_instance();
    $CI->db->where('type',4);
    $query = $CI->db->get('users');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}

function getQuerys()
{
    $CI =& get_instance();
    $query = $CI->db->get('query');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getAdvertisements()
{
    $CI =& get_instance();
    //$CI->db->where('type',3);
    $query = $CI->db->get('advertisement');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function get_responce($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id', $id);
    $query = $CI->db->get('query_offer');
    $result =  $query->result();
    return $result;
}

function getResponce($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id', $id);
    $query = $CI->db->get('query_offer');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}

function uselimit($sp_id="",$subscription_id="")
{
    $where=array('user_id'=>$sp_id,'subscription_id'=>$subscription_id,);
    $CI =& get_instance();
    $CI->db->where($where);
    $query = $CI->db->get('subscriptioncount');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getadvertviews($advert_id="")
{
    $where=array('advert_id'=>$advert_id);
    $CI =& get_instance();
    $CI->db->where($where);
    $query = $CI->db->get('advertisement_view_count');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}
function getSparePartsShopOwner()
{
    $CI =& get_instance();
    $CI->db->where('type',3);
    $query = $CI->db->get('users');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}


function get_bank_account()
{
    $CI =& get_instance();
    $query = $CI->db->get('bank_account');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}

function get_responcecount($id="")
{
    $CI =& get_instance();
    $CI->db->where('query_id',$id);
    $query = $CI->db->get('response');
    if($query->num_rows() > 0)
    {
        return $query->num_rows();
    }
    else
    {
        return 0 ;
    }
}

if (!function_exists('get_TeamMembers'))
{
    function get_TeamMembers()
    {
        $CI =& get_instance();
        $array = array('admin');
        $CI->db->where_not_in('user_type', $array);
        $query = $CI->db->get('admin_login');
        if($query->num_rows() > 0)
        {
            return $query->num_rows();
        }
        else
        {
            return 0 ;
        }
    }
}

function form_value($field = '', $default = '')
{
    $CI =& get_instance();
    $value = $CI->input->post($field);
    if($CI->input->post($field)===FALSE)
    {
        return $default;
    }

    // If the data is an array output them one at a time.
    //     E.g: form_input('name[]', set_value('name[]');
    if (is_array($value))
    {
        return array_shift($value);
    }

    return $value;
}

if (!function_exists('get_geolocation'))
{
    function get_geolocation($ip="")
    {
        $d = file_get_contents("http://www.ipinfodb.com/ip_query.php?ip=$ip&output=xml");
        //Use backup server if cannot make a connection
        if (!$d)
        {
            $backup = file_get_contents("http://backup.ipinfodb.com/ip_query.php?ip=$ip&output=xml");
            $result = new SimpleXMLElement($backup);
            if(!$backup)
            {
                return false; // Failed to open connection
            }
        }
        else
        {
            $result = new SimpleXMLElement($d);
        }
        //Return the data as an array
        return array(
                'ip'=>$ip,
                'country_code'=>$result->CountryCode,
                'country_name'=>$result->CountryName,
                'region_name'=>$result->RegionName,
                'city'=>$result->City,
                'zip_postal_code'=>$result->ZipPostalCode,
                'latitude'=>$result->Latitude,
                'longitude'=>$result->Longitude,
                'timezone'=>$result->Timezone,
                'gmtoffset'=>$result->Gmtoffset,
                'dstoffset'=>$result->Dstoffset
            );
    }
}



function getTheDay($date)
{
    $curr_date=strtotime(date("Y-m-d H:i:s"));
    $the_date=strtotime($date);
    $diff=floor(($curr_date-$the_date)/(60*60*24));
    switch($diff)
    {
        case 0:
            return "Today";
            break;
        case 1:
            return "Yesterday";
            break;
        default:
            //return $diff." Days ago";
            return date('d M Y', strtotime($date));
    }
}

