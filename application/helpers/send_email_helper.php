<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function send_mail_temp($to="",$type="",$vars="")
{ 
    //if($_SERVER['HTTP_HOST'] == "www.omsoftware.us" || $_SERVER['HTTP_HOST'] == "omsoftware.us"  || $_SERVER['HTTP_HOST'] == "https://omsoftware.us")
 //   {
        $CI =& get_instance();
        $arraydata=array();
        $CI->db->where_in('field',array('smtp_host','smtp_port','smtp_username','smtp_password'));
        $getdata = $CI->db->get('settings');
        $data=$getdata->result();
        if(is_array($data) && count($data)>0)
        {
            foreach($data as $datai)
            {
                $arraydata[$datai->field]=$datai->value;
            }
        }
       $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => $arraydata['smtp_host'],
            'smtp_port' => $arraydata['smtp_port'],
            'smtp_user' => $arraydata['smtp_username'],
            'smtp_pass' => $arraydata['smtp_password'],
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'wordwrap'   => TRUE
         );
        $CI->load->library('email', $config);
        $CI->email->set_newline("\r\n");
        $CI->db->where(array('type'=> $type));
        $equery= $CI->db->get('email');
        $getmaildetail1['body'] =  $equery->result();
        $CI->email->from($getmaildetail1['body'][0]->from_email, $getmaildetail1['body'][0]->from_name);
        $sub = $getmaildetail1['body'][0]->subject;
        $body = $CI->load->view('user_mail_format',$getmaildetail1,TRUE);
        
        if($vars!="")
        {
            if(count($vars))
            {
                foreach($vars as $key => $val)
                {
                    if($key=='url')
                    {
                        $val="<a href='".$val."'>Click Here</a></h1>";
                    }
                    $body=str_replace($key,$val,$body);
                }
                $body = str_replace("{","",$body);
                $body = str_replace("}","",$body);
            }
        }
        
        $CI->email->to($to);
		//echo $to; exit;
		if($to=='info@thenextidea.net')
		{ 
	    	$CI->email->cc('robert@thenextidea.net');
	    	$CI->email->cc('r.rajkamal@gmail.com');
	    	$CI->email->cc('bjat1212@gmail.com');
	     }
        $CI->email->subject($sub);
        $CI->email->message($body);
        $CI->email->set_mailtype('html');

        $CI->email->send();
		//if($to=='info@thenextidea.net')
		//{echo $to; exit;
		//$CI->email->cc('robert@thenextidea.net');
		//$CI->email->cc('r.rajkamal@gmail.com');
		//$CI->email->cc('bjat1212@gmail.com');
		//}
		return ;
  //  }
}

function send_mail_temp2($to="",$type="",$vars="")
{
    if($_SERVER['HTTP_HOST'] == "www.omsoftware.us" || $_SERVER['HTTP_HOST'] == "omsoftware.us"  || $_SERVER['HTTP_HOST'] == "https://omsoftware.us")
    {
        $CI =& get_instance();
        $query= $CI->db->get('setting');
        $getmaildetail =  $query->result();
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => $getmaildetail[0]->mail_host,
            'smtp_port' => $getmaildetail[0]->mail_port,
            'smtp_user' => $getmaildetail[0]->mail_uname,
            'smtp_pass' => $getmaildetail[0]->mail_password,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1',
            'wordwrap'   => TRUE
        );
        $CI->load->library('email', $config);
        $CI->email->set_newline("\r\n");
        $CI->email->from('helpdesk@omsoftware.net', 'FCSPLMobile');
        $where = array('email_type'=> $type);
        $CI->db->where($where);
        $equery= $CI->db->get('emails');
        $getmaildetail1['body'] =  $equery->result();
        $sub = $getmaildetail1['body'][0]->subject;
        $body = $CI->load->view('user_mail_format',$getmaildetail1,TRUE);
        if($vars!="")
        {
            if(count($vars))
            {
                foreach($vars as $key => $val)
                {
                    if($key=='url')
                    {
                        $val="<a href='".$val."'>Click Here</a></h1>";
                    }
                    $body=str_replace($key,$val,$body);
                }
                $body = str_replace("{","",$body);
                $body = str_replace("}","",$body);
            }
        }

        $CI->email->to($to);
        $CI->email->subject($sub);
        $CI->email->message($body);
		if($to=='info@thenextidea.net'){
			$CI->email->cc('robert@thenextidea.net');
			$CI->email->cc('r.rajkamal@gmail.com');
			$CI->email->cc('bjat1212@gmail.com');
			}
        $CI->email->set_mailtype('html');

        $CI->email->send();
        return ;
    }
}