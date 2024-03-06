<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_model extends CI_Model
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

    function fetchSetting($array)
    {
        $this->db->where_in('field',$array);
        $getdata = $this->db->get('settings');
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

}