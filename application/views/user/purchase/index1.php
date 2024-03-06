       <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Manage Purchases</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5  form-group row pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                              <button class="btn btn-secondary" type="button">Go!</button>
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
                    <h2> Add New Product<small>Purchased</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="container">
                      <div class="row">
      <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step col-xs-3"> 
                <a href="#step-1" type="button" class="btn btn-success btn-circle">1</a>
                <p><small>Product Details</small></p>
            </div>
            <div class="stepwizard-step col-xs-3"> 
                <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                <p><small>WholeSaler Details</small></p>
            </div>
            
        </div>
    </div>
  </div>
    <div class="row">
    <form  style="width:100%;" action="<?php echo base_url();?>user/Manage_purchases/insertproduct" method="POST">
        <div class="card card-primary setup-content" id="step-1">
            <div class="card-heading">
                 <h3 class="card-title">Product Details</h3>
            </div>
            <div class="card-body">
       <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="category">Select Category <span class="required text-danger">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <select class="select2_single form-control js-example-basic-single" tabindex="-1" id="categoryname" required="" name="main_category">
                                  <option selected="" disabled="" hidden="">Select Main Category </option>
                                  <?php foreach ($maincategories as $maincategory) { ?>
                           
                                  <option value="<?php echo($maincategory->id); ?>"><?php echo($maincategory->category_name); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Select Sub Category
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <select class="select2_single form-control js-example-basic-single" tabindex="-1" id="subcategoryname" name="sub_category">
                                  <option selected="" disabled="" hidden="">Select Sub Category </option>
                                  <?php foreach ($subcategories as $subcategory) { ?>
                           
                                  <option value="<?php echo($subcategory->id); ?>"><?php echo($subcategory->sub_category_name); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Product Id <span class="required text-danger">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                              <input type="text" id="product-id" required="required" class="form-control" readonly="" name="product_id">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="product-name">Product Name <span class="required text-danger">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                              <input type="text" id="fullname" class="form-control" placeholder="Enter Name" name="product_name">
                             
                            </div>
                          </div>

                           <div class="field item form-group">
                              <label class="col-form-label col-md-3 col-sm-3  label-align">Product Purchase Date<span class="required text-danger">*</span></label>
                              <div class="col-md-6 col-sm-6">
                                <input class="form-control date" type="date" name="product_purchase_date" required>
                              </div>
                            </div>
                          <div class="form-group row">
                            <label class="col-form-label col-md-3 col-sm-3  label-align">Product Quantity<span class="required text-danger">*</span></label>
                              
                             <div class="col-lg-2">
                                        <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="">
                                          <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" id="quantity" name="quantity" class="form-control input-number" value="10" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div>
                        </div>
                          </div>
                          <div class="field item form-group">
                              <label class="col-form-label col-md-3 col-sm-3  label-align">Product Expiry Date<span class="required text-danger">*</span></label>
                              <div class="col-md-6 col-sm-6">
                                <input class="form-control date" type="date" name="expiry_date" required='required'>
                              </div>
                            </div>

                          <div class="form-group row">
                            <label for="cost-price" class="col-form-label col-md-3 col-sm-3 label-align">Product Cost Price<span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="cost_price" class="form-control col " type="text" name="cost_price">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="sale-price" class="col-form-label col-md-3 col-sm-3 label-align">Product Selling Price<span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="sale-price" class="form-control col " type="text" name="sale_price">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="gst" class="col-form-label col-md-3 col-sm-3 label-align">GST (%)<span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="gst" class="form-control col" type="text" name="gst">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="cgst" class="col-form-label col-md-3 col-sm-3 label-align">CGST (%)<span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="cgst" class="form-control col" type="text" name="cgst">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="cgst" class="col-form-label col-md-3 col-sm-3 label-align">Amount Paid<span class="required text-danger">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="amt_paid" class="form-control col" type="text" name="amt_paid">
                            </div>
                          </div>
                          

                        
                <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
            </div>
        </div>
        
        <div class="card card-primary setup-content" id="step-2">
            <div class="card-heading">
                 <h3 class="card-title">WholeSaler Details</h3>
            </div>
            <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6" id="W_row">
                  <label for="wholesaler_name" class="col-form-label col-md-6 col-sm-6 label-align">Select Wholesaler </label>
                            <div class="col-md-6 col-sm-6 ">
                            <select class="select2_single form-control col js-example-basic-single" tabindex="-1" id="wholesaler_id" required="" name="wholesaler_id">
                                  <option selected="" disabled="" hidden="">Select WholeSaler</option>
                                  <?php if($wholesaler_details){
                                  foreach ($wholesaler_details as $w_details) { ?>
                           
                                  <option value="<?php echo($w_details->id); ?>"><?php echo($w_details->name); ?></option>
                                    <?php } } else {?>
                                       <option value="" selected="" hidden="">No Wholesaler Found</option>
                                     <?php } ?>

                                </select>
                            </div>
                          </div>
                          <div class="col-md-6">

                             <button type="button" id="new_w"> Or Add New  </button>
                           </div>
                           
                </div>
              <div class="form-group row">
                            <label for="wholesaler_name" class="col-form-label col-md-3 col-sm-3 label-align">WholeSaler Name</label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="wholesaler_name" class="form-control col" type="text" name="wholesaler_name">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="cgst" class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="W_email" class="form-control col" type="email" name="W_email">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="contact_number" class="col-form-label col-md-3 col-sm-3 label-align">Contact Number</label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="contact_number" class="form-control col" type="text" name="contact_number">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="address" class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
                            <div class="col-md-6 col-sm-6 ">
                              <textarea name="address"id="address"></textarea> 
                            </div>
                          </div>
                <button class="btn btn-success pull-right" type="submit">Finish!</button>
            </div>
        </div>
        
        
        
      
    </form>
  </div>
</div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
          $(document).ready(function(){
            var quantitiy=0;
            $('.quantity-right-plus').click(function(e){
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
                var quantity = parseInt($('#quantity').val());
                // If is not undefined
                $('#quantity').val(quantity + 1);

                // Increment
            });
            
            $('.quantity-left-minus').click(function(e){
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var quantity = parseInt($('#quantity').val());
            // If is not undefined
            // Increment
              if(quantity>0){
                $('#quantity').val(quantity - 1);
              }
            });

             $('#categoryname').change(function(e){
              // Get the field name
            var maincategory = $('#categoryname').val();
           
              $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/subcategory",
                method: "POST",
                data: {categoryname: maincategory},
                //dataType:"json",
                success: function(data) {
                  $('#subcategoryname').html(data);
                  //console.log(data);
                    
                },
                error: function() {
                    alert("No Sub Category Found");
                }
            });

              $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/productid",
                method: "POST",
                data: {categoryname: maincategory},
                //dataType:"json",
                success: function(data) {
                  $('#product-id').val(data);
                  //console.log(data);
                    
                },
                error: function() {
                    alert("Product id not generated");
                }
            });

            });


          

          });
        </script>
       <script type='text/javascript'>
  $(document).ready(function(){
  
     $( "#fullname" ).autocomplete({
        source: function( request, response ) 
        {
             $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/search_product",
                type: 'post',
                dataType: "json",
                data: {
                  search: request.term
                },
                success: function( data ) 
                {
                  response( data );
                }
             });
        },
        select: function (values, ui) {

             $('#fullname').val(ui.item.label);
             return false;
             
        }
     });

  });
  </script>
        <script type="text/javascript">
  $(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-success').addClass('btn-default');
            $item.addClass('btn-success');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-success').trigger('click');

     $('#wholesaler_id').change(function(e){
      var w_id=$('#wholesaler_id').val();
       $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/wholesaler_details",
                method: "POST",
                data: {w_id: w_id},
                dataType:"json",
                success: function(data) {
                  $('#wholesaler_name').val(data.name).prop('readonly', true);
                  $('#W_email').val(data.email).prop('readonly', true);
                  $('#contact_number').val(data.contact).prop('readonly', true);
                  $('#address').val(data.address).prop('readonly', true);
                  //console.log(data.address);
                    
                },
                error: function() {
                    alert("No wholesaler found");
                }
            });

});
     $('#new_w').click(function(e){
        $('#wholesaler_name').removeAttr('readonly').val('');
        $('#W_email').removeAttr('readonly').val('');
        $('#contact_number').removeAttr('readonly').val('');
        $('#address').removeAttr('readonly').val('');
        //$('#wholesaler_id').val('');
        $("#wholesaler_id").removeAttr("selected");
        $("#W_row").hide();
        $("#new_w").html('Add New');
        $("#W_row").show();
     });

     $('#fullname').change(function(e){
      //alert('change works');
      var productname=$('#fullname').val();
       $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_purchases/product_id",
                method: "POST",
                data: {productname: productname},
                dataType:"json",
                success: function(data) {
                  //$('#wholesaler_name').val(data.name).prop('readonly', true);
                  //$('#W_email').val(data.email).prop('readonly', true);
                 // $('#contact_number').val(data.contact).prop('readonly', true);
                 if(data)
                 {
                  $('#product-id').val(data);
                 }
                 // console.log(data);
                    
                },
                error: function() {
                    alert("New Product");
                }
            });

});
});
</script>
<script>
$(document).ready(function(){
  $("#cost_price").blur(function(){

     var cost_price=$('#cost_price').val();
    var quantity=$('#quantity').val();
    var amt_paid=cost_price*quantity;
    $('#amt_paid').val(amt_paid);
  });
});
</script>

	  </body>
</html>