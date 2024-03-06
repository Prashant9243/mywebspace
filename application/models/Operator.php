<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operator extends CI_Model
{
    #Start query for ajax data table================================================================
    private function _get_datatables_query($table,$column_order="",$column_search="",$order="")
    {

        $this->db->from($table);

        $i = 0;
        foreach ($column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {

                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($column_search) - 1 == $i) //last loop
                $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($order))
        {
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($table,$column_order,$column_search,$order,$where_col="",$where_in_array="",$where="")
    {

        $this->_get_datatables_query($table,$column_order,$column_search,$order);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        if($where_col && $where_in_array)
        {
            $this->db->where_in($where_col, $where_in_array);
        }
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($table,$column_order,$column_search,$order,$where_col="",$where_in_array="",$where="")
    {
        $this->_get_datatables_query($table,$column_order,$column_search,$order);
        if($where_col && $where_in_array)
        {
            $this->db->where_in($where_col, $where_in_array);
        }
        if($where)
        {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($table,$where_col="",$where_in_array="",$where="")
    {
        if($where_col && $where_in_array)
        {
            $this->db->where_in($where_col, $where_in_array);
        }
        if($where)
        {
            $this->db->where($where);
        }
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    #End query for ajax data table====================================================================



    public function insert_json_in_db($table,$json_data)
    {

        $array = json_decode($json_data);
        $this->db->insert($table, $array);
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function count_row($table,$where)
    {
        $this->db->where($where);
        $data = $this->db->get($table);
        $num = $data->num_rows();

        if($num)
        {
            return $num;
        }
        else
        {
            return false;
        }
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
    }

    function insert_multi_joson($table, $data)
    {
        $array = json_decode($data);
        $this->db->insert_batch($table,$array);
        $num = $this->db->insert_id();
        if($num)
        {
            return true;
        }
        else
        {
            return FALSE;
        }
    }
    function insert_multi($table, $data)
    {
        $this->db->insert_batch($table,$data);
        $num = $this->db->insert_id();
        if($num)
        {
            return true;
        }
        else
        {
            return FALSE;
        }
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
    function  sociallink_insert($data)
    {
        // Inserting in Table(students) of Database(college)
        $this->db->insert('bf_social_link', $data);
    }



    function getSpecific($tablename, $cond1,$field,$cond2)
    {
        $this->db->where($cond1);
        $where=$field.'LIKE "%'.$cond2.'%"';
        $this->db->where($where);
        $getdata = $this->db->get($tablename);
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

    function getNotIn($table,$array,$col)
    {
        $this->db->where_not_in($col, $array);
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

    function getWhereNotIn($table,$array,$col,$where)
    {
        $this->db->where($where);
        $this->db->where_not_in($col, $array);
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
    function getWhereIn($table,$array)
    {
        //$names = array('admin_uname', 'admin_pswd');
        $this->db->where_in('field', $array);
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

    function getWhereLogin($table)
    {
        $names = array('admin_uname', 'admin_pswd');
        $this->db->where_in('field', $names);
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

    function getWhere($table,$where)
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
    function getAllWithNotEqual($table)
    {
        $this->db->where('role!=',1);
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

    function getWhereWithOrderbyLimit($table,$where,$orderby,$ordertype,$limit)
    {
        $this->db->where($where);
        $this->db->order_by($orderby,$ordertype);
        $this->db->limit($limit);
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
    function getAllWithOrderbyLimit($table,$orderby,$limit="")
    {
        $getdata = $this->db->get($table);
        $this->db->order_by($orderby,'desc');
        $this->db->limit(10);
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
    function getWhereWithOrderby($table,$where,$orderby)
    {
        $this->db->where($where);
        $getdata = $this->db->get($table);
        $this->db->order_by($orderby,'desc');
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
    //chart model
    function get_data()
    {
         $this->db->select('your_name');
        $this->db->from('bf_members');
        $query = $this->db->get();
       	return $query->result();
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

    /*get all with limit star*/
    function getAllWithL($table,$limit,$offset)
    {
        $this->db->limit($limit, $offset);
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
    /*get all with limit end*/




    /*get data with where and limit*/
    function getWhereAndL($table,$where,$limit,$offset)
    {
        $this->db->where($where);
        $this->db->limit($limit, $offset);
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
    /**/



    /*Get all in desc/asc order start*/
    function getAllWithOrder($table,$order_field,$order_type)
    {
        $data = $this->db->get($table);
        $this->db->order_by($order_field,$order_type);
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
    /*Get all in desc/asc order end*/

    /**/
    function getJoinTwoWithOL($tbl1,$tbl2 ,$field1,$field2,$order_field,$order_type,$limit,$offset)
    {
        $this->db->select($tbl1.'.*,'.$tbl2.'.user_name');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
        $this->db->order_by($tbl1.'.'.$order_field,$order_type);
        $this->db->limit($limit, $offset);
        $getdata  = $this->db->get();
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
/**/



    /*Get all with join Three table and with order start*/
    function getJoinTwoWithOrder($tbl1,$tbl2,$field1,$field2,$order_field,$order_type)
    {
        $this->db->select($tbl1.'.*,'.$tbl2.'.user_name');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
        //$this->db->join($tbl3, $tbl1.'.'.$field3.'='.$tbl3.'.'.$field4);
        $this->db->order_by($tbl1.'.'.$order_field,$order_type);
        $getdata  = $this->db->get();
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
    /*Get all with join two table and with order end*/

    /*get all with join two and order and where start*/
    function getJTWithOW($tbl1,$tbl2,$field1,$field2,$order_field,$order_type,$where)
    {
        $this->db->select($tbl1.'.*,'.$tbl2.'.f_name');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
        $this->db->order_by($tbl1.'.'.$order_field,$order_type);
        $this->db->where($where);
        $getdata  = $this->db->get();
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
    /*get all with join two and order and where end*/

    /*get all with join two and order and where and limit start*/

    function getJTWithOLW($tbl1,$tbl2 ,$field1,$field2,$order_field,$order_type,$limit,$offset,$where)
    {
        $this->db->select($tbl1.'.*,'.$tbl2.'.f_name');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
         $this->db->order_by($tbl1.'.'.$order_field,$order_type);
        $this->db->limit($limit, $offset);
        $this->db->where($where);
        $getdata  = $this->db->get();
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
    /*get all with join two and order and where and limit end*/

    /*get all with join two and order and like start*/
    function getJTWithOLi($tbl1,$tbl2,$field1,$field2,$order_field,$order_type,$like_f,$like_txt)
    {
        $this->db->select($tbl1.'.*,'.$tbl2.'.f_name');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
        $this->db->order_by($tbl1.'.'.$order_field,$order_type);
        $this->db->like($like_f, $like_txt);
        //$this->db->where($where);
        $getdata  = $this->db->get();
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
    /*get all with join two and order and like end*/

    /*get all with join two and order and like and where start*/
    function getJTWithOLiW($tbl1,$tbl2,$field1,$field2,$order_field,$order_type,$like_f,$like_txt,$where)
    {
        $this->db->select($tbl1.'.*,'.$tbl2.'.f_name');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1.'.'.$field1.'='.$tbl2.'.'.$field2);
        $this->db->order_by($tbl1.'.'.$order_field,$order_type);
        $this->db->like($like_f, $like_txt);
        $this->db->where($where);
        $getdata  = $this->db->get();
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
    /*get all with join two and order and like and where end*/


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

}