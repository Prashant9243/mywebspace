    <!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Generate Invoice</h3>
						</div>

						<div class="title_right">
							<div class="col-md-5 col-sm-5  form-group pull-right top_search">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search for...">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button">Go!</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				


            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create Invoice </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                          </div>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <div class="row">
                          <div class="col-sm-4">
                            <div class="card-box">          
                              <div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
                      <div class="panel">
                        <a class="panel-heading" role="tab" id="headingOne1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne">
                          <h4 class="panel-title">Add Product To Invoice</h4>
                        </a>
                        <div id="collapseOne1" class="panel-collapse collapse in collapse show" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <br>
                             <form class="form-horizontal form-label-left" id="add-form" method="POST">
                                <div class="form-group row">
                                  <div class="col-md-6">
                                    <input type="hidden" id="invoice_no" value="<?php echo $invoice_no;?>" name="invoice_no">
                                    <input type="text" id="productcode" required="required" class="form-control" placeholder="Product Code">
                                    <label class="text-danger" id="error"></label>
                                  </div>
                                   <div class="col-md-3">
                                    <input type="text" id="quantity" name="quantity" required="required" class="form-control " placeholder="Qty">
                                  </div>
                                  <div class="col-md-2">
                                    <button id="add"class="btn btn-success "><i class="fa fa-arrow-right"></i></button>
                                  </div>
                                </div>
                                
                              </form>
                          </div>
                        </div>
                      </div>
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingTwo1" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo">
                          <h4 class="panel-title">Remove Product from invoice</h4>
                        </a>
                        <div id="collapseTwo1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <br>
                             <form class="form-horizontal form-label-left">
                                <div class="form-group row">
                                  <div class="col-md-6">
                                    <input type="text" id="product_code" required="required" class="form-control" placeholder="Product Code">
                                    <label class="text-danger" id="rerror"></label>
                                  </div>
                                   <div class="col-md-3">
                                    <input type="text" id="rquantity" name="rquantity" required="required" class="form-control " placeholder="Qty">
                                  </div>
                                  <div class="col-md-2">
                                    <button id="remove"class="btn btn-danger "><i class="fa fa-arrow-right"></i></button>
                                  </div>
                                </div>
                                
                              </form>
                          </div>
                        </div>
                      </div>
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingThree1" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree1" aria-expanded="false" aria-controls="collapseThree">
                          <h4 class="panel-title">Customer Details</h4>
                        </a>
                        <div id="collapseThree1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                          <div class="panel-body">
                            <br>
                            <form class="form-horizontal form-label-left">
                              <div class="form-group row">
                                <label class="control-label col-md-3">Customer Name <span class="required">*</span>
                                </label>
                                <div class="col-md-7">
                                  <input type="text" id="custname" required="required" name="custname" class="form-control">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="control-label col-md-3" for="mobile">Mobile Number <span class="required">*</span>
                                </label>
                                <div class="col-md-7">
                                  <input type="text" id="mobile" name="mobile" required="required" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <button id="customer"class="btn btn-success "><i class="fa fa-arrow-right"></i></button>
                                  </div>
                              </div>
                              <div class="form-group row">
                                <div class="col-md-12">
                                  <label>Payment Method *:</label>
                                  <p>
                                    Cash:
                                    <input type="radio" class="flat" name="paymethod" id="cash" value="1" checked="" required /> Card:
                                    <input type="radio" class="flat" name="paymethod" id="card" value="2" />
                                  </p>
                              </div>
                            </div>
                              <div class="form-group row">
                                <div class="col-md-12">
                                  <label>Payment status *:</label>
                                  <p>
                                    Paid:
                                    <input type="radio" class="flat" name="paystatus" id="paid" value="1"  required /> Unpaid:
                                    <input type="radio" class="flat" name="paystatus" id="unpaid" value="2" checked="" />
                                  </p>
                              </div>
                              </div>

                             
                              <div class="form-group row">
                                <div class="col-md-12">
                                  <button id="save"class="btn btn-success ">Save</button>
                                </div>
                              </div>
                               <div class="form-group row">
                                <div class="col-md-12">
                                  <label>customer Paid :</label>
                                  <input type="text" id="cust-amt-paid" class="form-control">

                              </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                            
                            </div>
                          </div>
                          
                          <div class="col-md-8" id="toprint">
                            <div class="card-box">
                              <div class="row">
                                <div class="col-md-4">
                                  <?php if($user_details){
                                    
                                     echo "<h6><b>$user_details->company_name</b></h6>";
                                     echo $user_details->address;
                                     echo"</br>";
                                     echo "<b>Mobile</b>- $user_details->mobile";
                                     echo"</br>";
                                     echo "<b>Email</b>- $user_details->email";
                                   
                                  } ?>
                                  
                                </div>
                                <div class="col-md-4">
                                  <img src="<?php echo base_url();?>assets/images/<?php echo $user_details->company_logo;?>" width="100%">
                                </div>
                                <div class="col-md-4 text-right">
                                  <h2>INVOICE</h2>
                                  <b>Date - </b><?php echo date('d-m-Y');?>
                                  <br><b>Invoice No - <?php echo $invoice_no;?></b>
                                </div>
                              </div>

                               <div class="clearfix"></div>
                                <br><br>
                              <div class="row">
                                <div class="col-md-12">
                                <span><b>Bill To</b></span><br>
                                <span>Customer Name - </span><span id="custname-display"></span><br>
                                <span>Mobile - </span><span id="mobile-display"></span><br>
                                </div>
                              </div>
                              <br>
                              <div class="row">
                                <div class="col-md-12">
                                  <table class="table table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Product Code</th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>Price</th>
                          <th>Amount</th>
                        </tr>
                      </thead>


                      <tbody class="tbody">
                       
                        
                      </tbody>
                       <tfoot>
                          <tr>
                              <th colspan="5" style="text-align:right" >Total:</th>
                              <th id="total"></th>
                          </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                      <div class="col-md-6">
                      </div>
                      <div class="col-md-6 text-right">
                        Payble Amount:<span id="payble"></span><br>
                        Customer Paid:<span id="cust-paid"></span><br>
                        Change Returned:<span id="return"></span><br>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-center">
                      <button type="button" class="btn btn-warning" id="print">Print</button>
                    </div>
                    </div>


                                </div>
                              </div>

                            </div>
                          </div>
                      
                      </div>
                  </div>
                </div>
              </div>
				    </div>
			   </div>
			<!-- /page content -->
 <script type='text/javascript'>
  $(document).ready(function(){
  
     $( "#productcode" ).autocomplete({
        source: function( request, response ) 
        {
             $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/autocomplete",
                type: 'post',
                dataType: "json",
                data: {
                  search: request.term
                },
                success: function( data ) 
                {
                  response( data);
                  console.log(data);
                }
             });
        },
        select: function (values, ui) {

             $('#productcode').val(ui.item.value);
             return false;
             
        }
     });

     /* $( "#product_code" ).autocomplete({
        source: function( request, response ) 
        {
             $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/rautocomplete",
                type: 'post',
                dataType: "json",
                data: {
                  search: request.term
                },
                success: function( data ) 
                {
                  response( data);
                  console.log(data);
                }
             });
        },
        select: function (values, ui) {

             $('#product_code').val(ui.item.value);
             return false;
             
        }
     });
*/

  });
  </script>
    

<script type="text/javascript">
  $('#print').click(function(){
var prtContent = document.getElementById("toprint");
var WinPrint = window.open();
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
});
</script>
      <script type="text/javascript">
         $(document).ready(function() {
          $('#print').hide();

          // print button functionality

            $('#add').click(function(e){
               e.preventDefault();
              var productcode=$('#productcode').val();
              //alert(productcode);
              var quantity=$('#quantity').val();
              var invoiceno=$('#invoice_no').val();
              if(productcode=='')
          {
            //alert('red');
            $('#productcode').css("border","2px solid red");
          }
          else
          {
            $('#productcode').css("border","1px solid #ced4da");
          }

          if(quantity=='')
          {
           // alert('red');
            $('#quantity').css("border","2px solid red");
          }
          else
          {
            $('#quantity').css("border","1px solid #ced4da");
          }
              if((productcode!='')&&(quantity!=''))
              {
                //alert('here');
              $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/create_invoice",
                method: "POST",
                data: {productcode: productcode,quantity:quantity,invoiceno:invoiceno},
                //dataType:"json",
                success: function(data) {
                  
                 $('.tbody').html(data);
                 $('#quantity').val('');
                 $('#productcode').val('');
                 $('#error').text('');
                 string1 = data.split(':')[1];
                 //str=string1[string1.length - 1];
                  $('#total').html(string1);
                 // console.log(data);
                 //console.log(str);
                 //console.log(string1);
                    
                },
                error: function() {
                    alert("No Sub Category Found");
                }
            });
          }

    
    });
      $('#productcode').focusout(function() {
         if(productcode!='')
         {
          var productcode=$('#productcode').val();
          $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/check_product_code",
                method: "POST",
                data: {productcode: productcode},
                //dataType:"json",
                success: function(data) {
                  //console.log(data);
                  if(data==0)
                  {
                    $('#error').text('Invalid Product Code');
                    $('#productcode').val('');
                  }
                  else if(data==2)
                  {
                    $('#error').text('Product Out of Stock');
                    $('#productcode').val('');
                  }
                  else
                  {
                    $('#error').text('');
                  }
                    
                },
                error: function() {
                    alert("No Sub Category Found");
                }
            });
         }
  });

// ****************check quantity**************
 $('#quantity').keyup(function() {
         if(quantity!='')
         {
          var productcode=$('#productcode').val();
          var quantity=$('#quantity').val();
          $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/check_quantity",
                method: "POST",
                data: {quantity: quantity,productcode:productcode},
                //dataType:"json",
                success: function(data) {
                  //console.log(data);
                  if(data)
                  {
                    $av_quantity=data;
                    $('#error').html("Only '" + $av_quantity + "' product available in stock");
                    $('#quantity').val(data);
                  }
                  
                  else
                  {
                    $('#error').text('');
                  }
                    
                },
                error: function() {
                    //alert("No Sub Category Found");
                }
            });
         }
  });
       });
      </script>
<!-- Add product to invoice script -->

<!-- Remove product from invoice script -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#remove').click(function(e){
               e.preventDefault();
              var productcode=$('#product_code').val();
              //alert(productcode);
              var quantity=$('#rquantity').val();
              var invoiceno=$('#invoice_no').val();
              if(productcode=='')
          {
            //alert('red');
            $('#product_code').css("border","2px solid red");
          }
          else
          {
            $('#product_code').css("border","1px solid #ced4da");
          }

          if(quantity=='')
          {
           // alert('red');
            $('#rquantity').css("border","2px solid red");
          }
          else
          {
            $('#rquantity').css("border","1px solid #ced4da");
          }
              if((productcode!='')&&(quantity!=''))
              {
                //alert('here');
              $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/remove_from_invoice",
                method: "POST",
                data: {productcode: productcode,quantity:quantity,invoiceno:invoiceno},
                //dataType:"json",
                success: function(data) {
                  
                 $('.tbody').html(data);
                 $('#rquantity').val('');
                 $('#product_code').val('');
                 $('#rerror').text('');
                 string1 = data.split(':')[1];
                 $('#total').html(string1);

                  //console.log(data);
                    
                },
                error: function() {
                    alert("No Sub Category Found");
                }
            });
          }

    
    });
      $('#product_code').focusout(function() {
         if(productcode!='')
         {
          var productcode=$('#product_code').val();
          var invoiceno=$('#invoice_no').val();
          $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/check_product_code_remove",
                method: "POST",
                data: {productcode: productcode,invoiceno:invoiceno},
                //dataType:"json",
                success: function(data) {
                  //console.log(data);
                  if(data==0)
                  {
                    $('#rerror').text('Invalid Product Code');
                    $('#product_code').val('');
                  }
                  
                  else
                  {
                    $('#rerror').text('');
                  }
                    
                },
                error: function() {
                    //alert("No Sub Category Found");
                }
            });
         }
  });

// ****************remove check quantity**************
 $('#rquantity').keyup(function() {
         if(quantity!='')
         {
          var productcode=$('#product_code').val();
          var quantity=$('#rquantity').val();
          var invoiceno=$('#invoice_no').val();
          $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/check_quantity_remove",
                method: "POST",
                data: {quantity: quantity,productcode:productcode,invoiceno:invoiceno},
                //dataType:"json",
                success: function(data) {
                  //console.log(data);
                  if(data)
                  {
                    $av_quantity=data;
                    $('#rerror').html("Only '" + $av_quantity + "' product can be removed from invoice ");
                    $('#rquantity').val(data);
                  }
                  
                  else
                  {
                    $('#rerror').text('');
                  }
                    
                },
                error: function() {
                    //alert("No Sub Category Found");
                }
            });
         }
  });
       });
      </script>

      <!-- Remove product from invoice script -->
      <script type="text/javascript">
        $(document).ready(function() {
          $('#customer').click(function(e) {
             e.preventDefault();
          var invoiceno=$('#invoice_no').val();
          var custname=$('#custname').val();
          var mobile=$('#mobile').val();
          $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/customer_details",
                method: "POST",
                data: {invoiceno: invoiceno,custname:custname,mobile:mobile},
                //dataType:"json",
                success: function(data) {
                  $('#custname-display').html(custname);
                   $('#mobile-display').html(mobile);
                  console.log(data);
                 
                },
                error: function() {
                    //alert("No Sub Category Found");
                }
            });

          });
      });
      </script>

       <script type="text/javascript">
        $(document).ready(function() {
          $('#save').click(function(e) {
             e.preventDefault();
          var invoiceno=$('#invoice_no').val();
          var payment_mode=1;
          var payment_status=1;

          $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_invoice/save_bill",
                method: "POST",
                data: {invoiceno: invoiceno,payment_mode:payment_mode,payment_status:payment_status},
                //dataType:"json",
                success: function(data) {
                  $('#payble').html(data);
                  $('#add').hide();
                  $('#customer').hide();
                  $('#remove').hide();
                  $('#save').hide();
                  $('#print').show();
                  console.log(data);
                 
                },
                error: function() {
                    //alert("No Sub Category Found");
                }
            });

          });
      });
      </script>
      <script type="text/javascript">
        $('#cust-amt-paid').focusout(function() {
       
          var customer_paid=$('#cust-amt-paid').val();
          var bill_amount=$('#payble').html();
          var return_amt=(customer_paid - bill_amount);
         // alert(return_amt);
          $('#return').html(return_amt);
          $('#cust-paid').html(customer_paid);
           });
      </script>