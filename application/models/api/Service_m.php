<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_m extends CI_Model
{
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
    }

    function update($table,$where,$data)
    {
        $this->db->where($where );
        $update = $this->db->update($table,$data);

        if($update)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function delete($table,$where)
    {
        $this->db->where($where);
        $this->db->limit('1');
        $del = $this->db->delete($table);
        if($del)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function deleteAll($table,$where)
    {
        $this->db->where($where);
        $del = $this->db->delete($table);
        if($del){
                return true;
        }else{
                return false;
        }
    }

    function getRows($str_query)
    {
        $result = $this->db->query($str_query);
        $numofrecords = $result->num_rows();
        if($numofrecords> 0)
        {
            return $result->result();
        }
        else
        {
            return false;
        }
    }

    function getRow($str_query)
    {
        $result = $this->db->query($str_query);
        $numofrecords = $result->num_rows();
        if($numofrecords> 0)
        {
            return $result->row();
        }
        else
        {
            return false;
        }
    }

    function getVal($str_query)
    {
        $result = $this->db->query($str_query);
        $numofrecords = $result->num_rows();
        if($numofrecords> 0)
        {
            foreach ($result->row() as $onefield)
            {
                return $onefield;
            }
        }
        else
        {
            return false;
        }
    }

    function getRowCount($str_query)
    {
        $result = $this->db->query($str_query);
        $numofrecords = $result->num_rows();
        if($numofrecords> 0)
        {
            return $numofrecords;
        }
        else
        {
            return 0;
        }
    }

    function fetchSetting($table,$array)
    {
        $arraydata=array();
        $this->db->where_in('field',$array);
        $getdata = $this->db->get($table);
        $data=$getdata->result();
        if(is_array($data) && count($data)>0)
        {
            foreach($data as $datai)
            {
                $arraydata[$datai->field]=$datai->value;
            }
        }
        return $arraydata;
    }
	    function getCustom($str_query)
    {
        $getdata = $this->db->query($str_query);
        $num = $getdata->num_rows();
        if($num> 0)
        {
            $arr=$getdata->result();
            foreach ($arr as $rows)
            {
            $data[] = $rows;
            }
            $getdata->free_result();
            return $data;}else{ return false;
        }
    }
    function checkemail($email)
	{
		 $this->db->select('email');
		 $this->db->from("members");
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
         $this->db->from("members");
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
		$this->db->select('mobile_no');
		 $this->db->from("members");
		 $this->db->where("mobile_no",$mobile);
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

    function ucheckemail($email,$rider_id)
    {
         $this->db->select('email');
         $this->db->from("members");
         $this->db->where("email",$email);
         $this->db->where("id !=",$rider_id);
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
    
    function ucheckusername($username,$id)
    {
         $this->db->select('username');
         $this->db->from("members");
         $this->db->where("user_name",$username);
         $this->db->where("id !=",$id);
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
    function ucheckmobile($mobile,$rider_id)
    {
         $this->db->select('mobile_no');
         $this->db->from("members");
         $this->db->where("mobile_no",$mobile);
         $this->db->where("id !=",$rider_id);
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
 }
