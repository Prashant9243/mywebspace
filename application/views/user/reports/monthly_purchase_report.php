
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Monthly / Yearly Purchase Report</h3>
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
                    <h2>This Month Purchase</h2>
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
                  <div class="row">
                    <form class="form-inline" method="POST" action="<?php echo base_url();?>user/Manage_reports/monthly_purchase_report">
                      <label for="month" class="mr-sm-2">Choose Month:</label>
                      <input type="month" class="form-control mb-2 mr-sm-2" id="month" name="month">
                      
                      <button type="submit" class="btn btn-warning mb-2" name="search">Search</button>
                    </br>
                    </br>
                    <hr>
                    </form>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                    
                    <table id="exe" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Product Id</th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>Purchase Price</th>
                          <th>Total Purchse Price</th>
                          <th>Wholesaler Name</th>
                          
                        </tr>
                      </thead>


                      <tbody class="tbody">
                      	<?php $i=1;
                        if(!empty($purchases))
                        {
                      	foreach ($purchases as $purchase) { ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php if(!empty($purchase->product_id)){ echo ($purchase->product_id); } else { echo "-";} ?></td>
                           <td><?php if(!empty($purchase->product_name)){ echo ($purchase->product_name); } else { echo "-";} ?></td>
                           <td><?php if(!empty($purchase->quantity)){ echo ($purchase->quantity); } else { echo "-";} ?></td>
                           <td><?php if(!empty($purchase->cost_price)){ echo ($purchase->cost_price); } else { echo "-";} ?></td>
                           <td><?php if(!empty($purchase->cost_price)){ $total_price=($purchase->cost_price) *($purchase->quantity); echo ($total_price); } else { echo "-";} ?></td>
                           
                          <td><?php $Wholesaler=$this->admin->getVal('select name from wholesaler_details where id="'.$purchase->wholesaler_id.'"'); echo $Wholesaler;  ?></td>
                          
                        </tr>
                    <?php } } ?>
                        
                      </tbody>
                       <tfoot>
                          <tr>
                              <th colspan="5" style="text-align:right">Total:</th>
                              <th></th>
                          </tr>
                        </tfoot>
                    </table>
                  </div>
                  </div>
              </div>
            </div>
                </div>
              </div>



				</div>
			</div>
			<!-- /page content -->
      <script>
        $(document).ready(function() {
    $('#exe').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\INR,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                'INR '+pageTotal +' ( INR '+ total +' total)'
            );
        }
    } );
} );
      </script>

		