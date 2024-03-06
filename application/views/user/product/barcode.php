
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Generate Barcode</h3>
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
									<h2>Product <small>Barcode</small></h2>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown">
											<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench"></i></a>
											<ul class="dropdown-menu" role="menu">
												<li><a class="dropdown-item" href="#">Settings 1</a>
												</li>
												<li><a class="dropdown-item" href="#">Settings 2</a>
												</li>
											</ul>
										</li>
										<li><a class="close-link"><i class="fa fa-close"></i></a>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<br />
									<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="<?php echo base_url();?>user/Generate_barcode/generate">
                     


										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Product Name / Code <span class="required text-danger" >*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input type="text" id="productcode" required="required" class="form-control " name="product_name">
											</div>
										</div>
										
										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Barcode Quantity <span class="required text-danger" >*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input type="number" id="print_qty" required="required" class="form-control " name="print_qty">
											</div>
										</div>
										
							
										<div class="ln_solid"></div>
										<div class="item form-group">
											<div class="col-md-6 col-sm-6 offset-md-3">
												<button class="btn btn-primary" type="reset">Reset</button>
												<button type="submit" class="btn btn-success" id="submit">Submit</button>
											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>

					 <div class="clearfix"></div>

			</div>
			<!-- /page content -->

			<script type='text/javascript'>
  $(document).ready(function(){
  
     $( "#productcode" ).autocomplete({
        source: function( request, response ) 
        {
             $.ajax({
                url: "<?php echo base_url(); ?>user/Generate_barcode/autocomplete",
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
   });
</script>