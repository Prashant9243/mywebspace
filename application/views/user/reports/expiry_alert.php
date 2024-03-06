
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Expiry Updates</h3>
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
                    <h2>Expired Products</h2>
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
                    
                    <table id="example1" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Product Id</th>
                          <th>Product Name</th>
                          <th>Remaining Quantity</th>
                          <th>Expired On</th>
                          <th>Wholesaler Name</th>
                          
                        </tr>
                      </thead>


                      <tbody class="tbody">
                       <?php
                       $i=1;
                       $user=$this->session->userdata('userid');
                       $today=date('Y-m-d'); 
                       $days_ahed = date('Y-m-d', strtotime('+15 days', strtotime($today)));
                        $expired=$this->admin->getRows('select * from products where expiry_date<"'.$today.'" and user_id="'.$user.'" and status=1 limit 2'); 
                    //echo $this->db->last_query();
                    foreach ($expired as $expire) {?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php if(!empty($expire->product_id)){ echo ($expire->product_id); } else { echo "-";} ?></td>
                           <td><?php if(!empty($expire->product_name)){ echo ($expire->product_name); } else { echo "-";} ?></td>
                           <td><?php if(!empty($expire->quantity)){ echo ($expire->quantity); } else { echo "-";} ?></td>
                           
                            <td><?php if(!empty($expire->expiry_date)){$date=$expire->expiry_date; echo date('d-m-Y',strtotime($date)); } else { echo "-";} ?></td>
                          <td><?php $Wholesaler=$this->admin->getVal('select name from wholesaler_details where id="'.$expire->wholesaler_id.'"'); echo $Wholesaler;  ?></td>
                          
                        </tr>
                    <?php }  ?>
                        
                      </tbody>
                      
                    </table>
                  </div>
                  </div>
              </div>
            </div>
                </div>
              </div>



				</div>

        <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Expiring Soon</h2>
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
                          <th>Remaining Quantity</th>
                          <th>Expiry Date</th>
                          <th>Days Remaining</th>
                          <th>Wholesaler Name</th>
                          
                        </tr>
                      </thead>


                      <tbody class="tbody">
                       <?php
                       $i=1;
                       $user=$this->session->userdata('userid');
                       $today=date('Y-m-d'); 
                       $days_ahed = date('Y-m-d', strtotime('+15 days', strtotime($today)));
                         $expiring_soon=$this->admin->getRows('select * from products where expiry_date between "'.$today.'" AND "'.$days_ahed.'"  and user_id="'.$user.'" and status=1 limit 2');
                         
                       
                    if(!empty($expiring_soon))
                    {
                    foreach ($expiring_soon as $expiring) {?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php if(!empty($expiring->product_id)){ echo ($expiring->product_id); } else { echo "-";} ?></td>
                           <td><?php if(!empty($expiring->product_name)){ echo ($expiring->product_name); } else { echo "-";} ?></td>
                           <td><?php if(!empty($expiring->quantity)){ echo ($expiring->quantity); } else { echo "-";} ?></td>
                           <td><?php if(!empty($expiring->expiry_date)){$date=$expiring->expiry_date; echo date('d-m-Y',strtotime($date)); } else { echo "-";} ?></td>

                           <td><?php if(!empty($expiring->expiry_date)){ $now = time(); $date=$expiring->expiry_date; $expdate=strtotime($date);$datediff = $now - $expdate; echo round($datediff / (60 * 60 * 24)); } else { echo "-";} ?></td>
                          <td><?php $Wholesaler=$this->admin->getVal('select name from wholesaler_details where id="'.$expiring->wholesaler_id.'"'); echo $Wholesaler;  ?></td>
                          
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
      <script>
$(document).ready(function(){
   $('#example1').DataTable({
  "ordering" : false
 });
  });
</script>
			<!-- /page content -->
     

		