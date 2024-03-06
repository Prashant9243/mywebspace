<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome_model extends CI_Model {

  public function getUsers($postData)
  {
      $response = array();

      $this->db->select('*');

      if($postData['search'] )
      {
          $this->db->where("product_name like '%".$postData['search']."%' ");
          $result = $this->db->get('products')->result();

          foreach($result as $row )
          {
              $response[] = array("label"=>$row->product_name);
          }
      }

      return $response;
  }

   public function getPcode($postData)
  {
      $response = array();

      $this->db->select('*');

      if($postData['search'] )
      {
          $this->db->where("product_id like '%".$postData['search']."%' OR product_name like '%".$postData['search']."%' ");
          $result = $this->db->get('products')->result();

          foreach($result as $row )
          {
              $response[] = array("label"=>$row->product_id.' '.$row->product_name,"value"=>$row->product_id);
          }
      }

      return $response;
  }

   public function getPcoder($postData)
  {
    $product_name=$this->admin->getVal('select product_name from products where product_id="'.$postData['search'].'"');
    //echo $this->db->last_query();
      $response = array();

      $this->db->select('*');

      if($postData['search'] )
      {
          $this->db->where("product_id like '%".$postData['search']."%' ");
          $result = $this->db->get('invoice')->result();

          foreach($result as $row )
          {
              $response[] = array("label"=>$row->product_id.' '.$product_name,"value"=>$row->product_id);
          }
      }

      return $response;
  }
public function getPname($postData)
  {
      $response = array();

      $this->db->select('*');

      if($postData['search'] )
      {
          $this->db->where("product_id like '%".$postData['search']."%' ");
          $result = $this->db->get('products')->result();

          foreach($result as $row )
          {
              $response[] = array("label"=>$row->product_id.' '.$row->product_name,"value"=>$row->product_id);
          }
      }

      return $response;
  }

  public function getfullcode($postData)
  {
      $response = array();

      $this->db->select('*');

      if($postData['search'] )
      {
          $this->db->where("product_id like '%".$postData['search']."%' OR product_name like '%".$postData['search']."%' ");
          $result = $this->db->get('products')->result();

          foreach($result as $row )
          {
              $response[] = array("label"=>$row->product_id.' '.$row->product_name,"value"=>$row->product_id.'#'.$row->product_name);
          }
      }

      return $response;
  }

  public function getretailer($postData,$user)
  {
      $response = array();

      $this->db->select('*');

      if($postData['search'] )
      {
          //$this->db->where('user_id',$user);
          
          $this->db->where("(name like '%".$postData['search']."%' OR email like '%".$postData['search']."%') AND user_id = $user");
          
          $result = $this->db->get('wholesaler_details')->result();

          foreach($result as $row )
          {
              $response[] = array("label"=>$row->name.' '.$row->email,"value"=>$row->name,'id'=> $row->id);
          }
      }

      return $response;
  }

 
}