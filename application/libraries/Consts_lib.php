<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Consts_lib
{
    private $CI;
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->setConstants();

    }
    private function setConstants()
    {
        $array=array('site_title','EMAIL');
        $this->CI->db->where_in('field',$array);
        $getdata = $this->CI->db->get('settings');
        $data=$getdata->result();
        if(is_array($data) && count($data)>0)
        {
            foreach($data as $datai)
            {
                $arraydata[$datai->field]=$datai->value;
            }
        }
        if($arraydata)
        {
            define('TITLE',$arraydata['site_title']);
            define('EMAIL',$arraydata['email']);
            //define('DESCRIPTION',$row->site_description);
            //define('CONTACTDES',$row->contact_description);
            //define('CONTACTEMAIL',$row->email);
            //define('CONTACTNUMBER',$row->telephone);
            //define('CONTACTADDRESS',$row->address);
            //define('CONTACTFAX',$row->fax);
            //define('CONTACTDESCRIPTION',$row->address);
            //define('FACEBOOK',$row->fb_url);
            //define('TWITTER',$row->twitter_url);
           // define('INSTAGRAM',$row->inta_url);
            //define('GOOGLEPLUS',$row->gplus_url);
            //define('PINTEREST',$row->pin_url);
            //define('FOOTERCONTENT',$row->footer_content);
            //define('TWITTERNAME',$row->twitter_name);
            //define('NEWS1',$row->newsletter1);
            //define('NEWS2',$row->newsletter2);
        }

    }
}