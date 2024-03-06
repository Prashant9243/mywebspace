<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /*
     * Fetch user data
     */
	function update($table, $data ,$where)
    {
		 $this->db->where($where);
		$this->db->update($table,$data);
    }	 
	 
    function insert($table, $data)
    {
        $this->db->insert($table,$data);
        $num = $this->db->insert_id();
        if($num)
        {
            return $num;
        }
        else
        {
            return FALSE;
        }
		//echo $this->db->last_query(); exit;
    }	 
	
	function get_wheres($table,$where)
    {			
        $this->db->where($where);	
        $getdata = $this->db->get($table);
        $num = $getdata->num_rows();
        if($num> 0)
        { 
            $arr=$getdata->row_array();
            return $arr;
        }
        else
        { 
            return false;
        }
    }
	
	function forgot($email)
	{
        if(!empty($email))
		{
			 $query = $this->db->get_where('user', array('email' => $email));
             $detail= $query->row_array();
			 return $detail['password'];
        }
    }
	function checkemail($email)
	{
		 $this->db->select('email');
		 $this->db->from("user_registration");
		 $this->db->where("email",$email);
		 $query = $this->db->get();
		 if($query->num_rows() > 0)
		 {
		  return true;
		 }
		 else
		 {
			 return false;
		 }
		
	
    }
        function checkusername($username)
    {
         $this->db->select('username');
         $this->db->from("user_registration");
         $this->db->where("username",$username);
         $query = $this->db->get();
         if($query->num_rows() > 0)
         {
          return true;
         }
         else
         {
             return false;
         }
        
    
    }
	function checkmobile($mobile)
	{
		$this->db->select('mobile');
		 $this->db->from("user_registration");
		 $this->db->where("mobile",$mobile);
		 $query = $this->db->get();
		 if($query->num_rows() > 0)
		 {
		  return true;
		 }
		 else
		 {
			 return false;
		 }
		 
		
	
    }
	
	function checkotp($mobile,$otp)
	{
		$this->db->select('mobile');
		 $this->db->from("user");
		 $this->db->where("mobile",$mobile);
		 $this->db->where("otp",$otp);
		 $query = $this->db->get();
		 if($query->num_rows() > 0)
		 {
		  return true;
		 }
		 else
		 {
			 return false;
		 }
		 
		
	
    }
	
	function myprofile($id)
	{
		 $query = $this->db->query("SELECT * FROM user  WHERE user.user_id='".trim($id)."'");
		return $query->result_array();
    }

    function getPoint($id = "")
	{
		 $query = $this->db->query("SELECT point as total,dates  FROM point WHERE user_id='".$id."' ");
		return $query->result_array();
    }
	function getPin($id = "")
	{
		 $query = $this->db->query("SELECT pin  FROM point WHERE user_id='".$id."' ");
		return $query->result_array();
    }

    public function orderinsert($data = array())
	 {
		
        $insert = $this->db->insert('orders', $data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }
	public function redeems($data = array())
	 {
		
        $insert = $this->db->insert('redeem', $data);
        if($insert){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }
	
	
    function get_where($table,$where)
    {			
        $this->db->where($where);	
        $getdata = $this->db->get($table);
        $num = $getdata->num_rows();
        if($num> 0)
        { 
            $arr=$getdata->result();
            foreach ($arr as $rows)
            {
                $data[] = $rows;
            }
            $getdata->free_result();
            return $data;
        }
        else
        { 
            return false;
        }
    }
  
    function getWhere($table,$where,$orderby,$ordertype)
    {			
        $this->db->where($where);	
        $this->db->order_by($orderby,$ordertype);
        $getdata = $this->db->get($table);

        $num = $getdata->num_rows();
        if($num> 0)
        { 
            $arr=$getdata->result();
            foreach ($arr as $rows)
            {
                    $data[] = $rows;
            }
            $getdata->free_result();
            return $data;
        }
        else
        { 
            return false;
        }
    }
    
	
	
	
	
	function getAll($table)
        {
            $data = $this->db->get($table);		
            $get = $data->result();
            $num = $data->num_rows();
            if($num)
            {
                return $get;
            }
            else
            {
                return false;
            }
        }
	
}
?>