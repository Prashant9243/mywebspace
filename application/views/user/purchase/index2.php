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

                  <form id="purchaseinvoice">
                  <div class="x_content">
                      <div class="row">
                          <div class="col-md-12" id="toprint">
                            <div class="card-box">
                              <div class="row">
                                <div class="col-md-6">
                                  <span><b>Purchase From</b></span><br>
                                  <span>Retailer Name - </span><span id="custname-display"></span><br>
                                  <span>Mobile - </span><span id="mobile-display"></span><br>
                                  
                                </div>
                                <div class="col-md-6 text-right">
                                  <h2>INVOICE</h2>
                                  <b>Date - </b><?php echo date('d-m-Y');?>
                                  <br><b>Invoice No - <?php echo $invoice_no;?></b>
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
                          <td><input type="text" id="productcode" class="form-control" placeholder="Product Code/Name" name="product_code[]"></td>
                          <td><input type="text" name="quantity[]" class="form-control" placeholder="Quantity"></td>
                          <td><select name="uom[]" class="select2_single form-control js-example-basic-single col-md-10"  id="uom">
                                     <option selected hidden disabled value="Unit">Unit</option>
                                   </select></td>
                          <td><input class="form-control date" type="date" name="expiry_date[]"></td>
                          <td><input type="text" name="cost_price[]" class="form-control" placeholder="Cost Price"></td>
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
                        Payble Amount:<span id="payble"></span><br>
                        Customer Paid:<span id="cust-paid"></span><br>
                        Change Returned:<span id="return"></span><br>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-md-12 text-center">
                      <button type="button" class="btn btn-primary" id="save_invoice">Save Invoice</button>
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
       </form>
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
             //return false;
             
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

$('#save_invoice').click(function(){
    var form = $('#purchaseinvoice').serialize();
    $.post(url+'user/manage_purchases/save_purchase',{data:form},function(response){
        console.log(response);
    });
});
  </script>
<!-- <script src="<?php echo base_url();?>assets/js/purchase.js"></script> -->