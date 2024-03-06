    <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Generate Purchase Invoice</h3>
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
                    <h2>Create Purchase Invoice </h2>
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

                  <form method="POST" action="<?php echo base_url();?>user/manage_purchases/save_purchase">
                    <?php $invoice_id = random_string('alnum',6);?> 
                    <input type="hidden" name="categoryname" value="8">
                    <input type="hidden" name="amt_paid" id="amt_paid">
                    <input type="hidden" value="<?php echo $invoice_id;?>" name="invoice_id">
                  <div class="x_content">
                      <div class="row">
                          <div class="col-md-12" id="toprint">
                            <div class="card-box">
                              <div class="row">
                                <div class="col-md-6">
                                  <span class="mb-2"><b>Purchase From</b></span><br><br>
                                  <span>Retailer Name - </span><input type="text" class="mb-2" id="retailer" name="wholesaler_id"><br>
                                  <span>Mobile - </span><input type="text" class="mb-2" id="mobile" name="retailer_mobile"><br>
                                  
                                </div>
                                <div class="col-md-6 text-right">
                                  <h2>INVOICE</h2>
                                  <b>Date - </b><input type="date" class="mb-2 w-20" name="product_purchase_date">
                                  <br><b>Invoice No - <?php echo $invoice_id;?></b>
                                </div>
                              </div>

                               <div class="clearfix"></div>
                              <div class="row">
                                <div class="col-md-12">
                                  <table class="table table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Product Code / Name</th>
                          <th>Quantity</th>
                          <th>Unit of Measurement</th>
                          <th>Expiry Date</th>
                          <th>Cost Price <br>(Per piece)</th>
                          <th>Selling Price <br>(Per piece)</th>
                        </tr>
                      </thead>


                      <tbody class="tbody">
                        <tr>
                          <td class="plus"><button class="btn btn-success add" type="button"><i class="fa fa-plus"></i></button></td>
                          <td><input type="text" id="productcode" class="form-control productcode" placeholder="Product Code/Name" name="product_code[]"></td>
                          <td><input type="text" name="quantity[]" class="form-control" placeholder="Quantity"></td>
                          <td><select name="uom[]" class="select2_single form-control js-example-basic-single col-md-10"  id="uom">
                                     <option selected hidden value="Unit">Unit</option>
                                   </select></td>
                          <td><input class="form-control date" type="date" name="expiry_date[]"></td>
                          <td><input type="text" name="cost_price[]" class="form-control cp" placeholder="Cost Price"></td>
                          <td><input type="text" name="sell_price[]" class="form-control" placeholder="selling Price"></td>
                        </tr>
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
                        Payable Amount:<span id="payble"></span><br>
                        Customer Paid:<span id="cust-paid"></span><br>
                        Change Returned:<span id="return"></span><br>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-center">
                      <button type="submit" class="btn btn-primary" id="save_invoice">Save Invoice</button>
                      
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
       </form>
      <!-- /page content -->
 <script type='text/javascript'>
  $(document).ready(function(){
  $(document).on('keyup','.productcode',function(){
     $(this).autocomplete({
        source: function( request, response ) 
        {
             $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/autocomplete",
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

             $(this).val(ui.item.value);
             //return false;
             
        }
     });
  });

     $("#retailer").autocomplete({
        source: function( request, response ) 
        {
             $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/retailerautocomplete",
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
             
             $('#retailer').val(ui.item.value);
             var ids = ui.item.id;
             $.post('<?php echo base_url();?>user/Manage_purchases/get_retailer_mobile',{id:ids},function(response){
                 $('#mobile').val(response);
             });
             
        }
     });

    

  });
  </script>
  <script>
    $(document).on('click','.add',function(){
              
              $.get('<?php echo base_url();?>user/Manage_purchases/get_new_row',function(response){

                $('.plus').html('<button class="btn btn-danger remove" type="button"><i class="fa fa-minus"></i></button>');
                $('tbody:last').append(response);
            });
        });


  </script>
  <script>
      $(document).on('keyup','.cp',function(){
          var amount = 0;
         $('.cp').each(function(){
             amount += Number($(this).val());
         });
         $('#total').text(amount);
         $('#amt_paid').val(amount);
         $('#payble').text(amount);
      });
  </script>
<!-- <script src="<?php echo base_url();?>assets/js/purchase.js"></script> -->