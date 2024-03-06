<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate_barcode extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		
        if($this->session->userdata('user_type') != 2)
        {
          redirect('login');exit();
        }
    }


	public function index()
	{
		
		$data['template']='user/product/barcode';
		$this->load->view('user/layout/template',$data);
		
	}
  public function generate()
  {

  $this->load->library('zend');
  $this->zend->load('Zend/Barcode');

  $id=$this->input->post('product_name');
  $no_of_barcode=$this->input->post('print_qty');
  $barcodeOptions = array('text' => $id);
  $rendererOptions = array();
  $imageResource = Zend_Barcode::draw('code39', 'image', $barcodeOptions, $rendererOptions);
  $code = time().$id;
  // saving image to folder
  $store_image = imagepng($imageResource,"./assets/barcode/{$code}.png");
  
  $barcode_image=$code.'.png';
        $data = array(
      'barcode_image'                   =>$barcode_image
        );
        $update=$this->admin->update('products',array('product_id'=>$id), $data);
       
       redirect("user/Generate_barcode/barcode_print/". $no_of_barcode."/".$id);

      
  }
	public function barcode_print()
  {
    
    $data['no']=$this->uri->segment(4);
    $pid=$this->uri->segment(5);
    //echo $no;die;
    
    $data['barcodeimg']=$this->admin->getVal('select barcode_image from products where product_id="'.$pid.'"');
    $data['template']='user/product/barcode_print';
    $this->load->view('user/layout/template',$data);
  }
  
	public function autocomplete(){
    $this->load->model('Welcome_model');
    $postData = $this->input->post();
    $data = $this->Welcome_model->getPname($postData);
    echo json_encode($data);
  }
}