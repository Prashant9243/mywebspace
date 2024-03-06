
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Out of stock</h3>
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
                    <h2>Products List</h2>
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
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                    
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Product Id</th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>Cost Price</th>
                          <th>Last Purchase Date</th>
                          <th>Wholesaler Name</th>
                          
                        </tr>
                      </thead>


                      <tbody class="tbody">
                      	<?php $i=1;
                        if(!empty($products))
                        {
                      	foreach ($products as $product) { ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php if(!empty($product->product_id)){ echo ($product->product_id); } else { echo "-";} ?></td>
                           <td><?php if(!empty($product->product_name)){ echo ($product->product_name); } else { echo "-";} ?></td>
                           <td class="<?php if(empty($product->quantity)){echo"text-danger font-weight-bold";}?>"><?php if(!empty($product->quantity)){ echo ($product->quantity); } else { echo "Out Of Stock";} ?></td>
                           <td><?php if(!empty($product->cost_price)){ echo ($product->cost_price); } else { echo "-";} ?></td>
                           <td><?php if(!empty($product->product_purchase_date)){ echo date('d-m-Y',strtotime($product->product_purchase_date)); } else { echo "-";} ?></td>
                          <td><?php $Wholesaler=$this->admin->getVal('select name from wholesaler_details where id="'.$product->wholesaler_id.'"'); echo $Wholesaler;  ?></td>
                         
                        </tr>
                    <?php } } ?>
                        
                      </tbody>
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
