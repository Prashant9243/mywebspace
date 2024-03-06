<?php
$user=$this->session->userdata('userid'); 
$category=$this->admin->getVal('select count(*) from main_categories where user_id="'.$user.'" and status=1');
$sales=$this->admin->getVal('select sum(bill_amount) from invoice_details where user_id="'.$user.'"');
$products=$this->admin->getVal('select count(*) from products where user_id="'.$user.'" and status=1');
$products_out=$this->admin->getVal('select count(*) from products where user_id="'.$user.'" and status=1 and quantity=0');
$today=date('Y-m-d'); $expired=$this->admin->getVal('select count(*) from products where expiry_date<"'.$today.'" and user_id="'.$user.'" and status=1'); 
$purchase=$this->admin->getVal('select sum(amt_paid) from product_purchase where user_id="'.$user.'"');
?>    


          
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="container">
  <div class="row">
    <div class="col-md-2" style="margin-top: 20px">
      <div class="card border-primary">
        <div class="card-body bg-primary text-white">
          <div class="row">
            <div class="col-3">
              <i class="fa fa-random fa-2x"></i>
            </div>
            <div class="col-9 text-right">
              <h4><?php echo $category;?></h4>
              <h6>Category</h6>
            </div>
          </div>
        </div>
        <a href="https://www.linkedin.com/in/younes-elmorabit" target="_blank">
          <div class="card-footer bg-light text-primary">
            <span class="float-left">More details</span>
            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-md-2" style="margin-top: 20px">
      <div class="card border-secondary">
        <div class="card-body bg-secondary text-white">
          <div class="row">
            <div class="col-3">
              <i class="fa fa-line-chart fa-2x"></i>
            </div>
            <div class="col-9 text-right">
              <h4>Rs. <?php echo $sales;?></h4>
              <h6>Sales</h6>
            </div>
          </div>
        </div>
        <a href="https://www.linkedin.com/in/younes-elmorabit" target="_blank">
          <div class="card-footer bg-light text-secondary">
            <span class="float-left">More details</span>
            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-md-2" style="margin-top: 20px">
      <div class="card border-success">
        <div class="card-body bg-success text-white">
          <div class="row">
            <div class="col-3">
              <i class="fa fa-product-hunt fa-2x"></i>
            </div>
            <div class="col-9 text-right">
              <h4><?php echo $products;?></h4>
              <h6>Products</h6>
            </div>
          </div>
        </div>
        <a href="https://www.linkedin.com/in/younes-elmorabit" target="_blank">
          <div class="card-footer bg-light text-success">
            <span class="float-left">More details</span>
            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-md-2" style="margin-top: 20px">
      <div class="card border-danger">
        <div class="card-body bg-danger text-white">
          <div class="row">
            <div class="col-3">
              <i class="fa fa-frown-o fa-2x"></i>
            </div>
            <div class="col-9 text-right">
              <h4><?php echo $products_out;?></h4>
              <h6>Out of Stock</h6>
            </div>
          </div>
        </div>
        <a href="https://www.linkedin.com/in/younes-elmorabit" target="_blank">
          <div class="card-footer bg-light text-danger">
            <span class="float-left">More details</span>
            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-md-2" style="margin-top: 20px">
      <div class="card border-warning">
        <div class="card-body bg-warning text-white">
          <div class="row">
            <div class="col-3">
              <i class="fa fa-shopping-basket fa-2x"></i>
            </div>
            <div class="col-9 text-right">
              <h4>Rs. <?php echo $purchase; ?></h4>
              <h6>Purchacse</h6>
            </div>
          </div>
        </div>
        <a href="https://www.linkedin.com/in/younes-elmorabit" target="_blank">
          <div class="card-footer bg-light text-warning">
            <span class="float-left">More details</span>
            <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    
  </div>
</div>

            <div class="row mt-5">
              <div class="col-md-6">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Products </h2>
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
                    <!--  Top 10 Best Sailing Product -->
                   <table class="table table-bordered">
                     <tr>
                       <th>Product Id</th>
                       <th>Product Name</th>
                     </tr>
                     <?php
                      $user=$this->session->userdata('userid');
                      $top_product=$this->admin->getRows('select products.product_id FROM products INNER JOIN invoice ON products.product_id=invoice.product_id and invoice.user_id="'.$user.'" group by product_id order by sum(amount) desc limit 10');

                      foreach ($top_product as $best_product ) {
                        # code...
                      
                     ?>
                     <tr>
                       <td><?php echo $best_product->product_id;?></td>
                       <td><?php if(!empty($best_product->product_id)){ $product_name=$this->admin->getVal('select product_name from products where product_id="'.$best_product->product_id.'"'); echo $product_name;} ?></td>
                     </tr>
                   <?php } ?>
                   </table>
                                    
                   
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Product Expiring Soon </h2>
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
                    <table class="table table-stripped table-responsive">
                      <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>Expiry Date</th>
                        <th>Wholesaler Name</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $user=$this->session->userdata('userid');
                    $expiring_soon=$this->admin->getRows('select * from products where expiry_date between "'.$today.'" AND "'.$days_ahed.'"  and user_id="'.$user.'" and status=1 limit 10');
                    
                    if(!empty($expiring_soon))
                    {
                      $i=1;
                    foreach ($expiring_soon as $expiring) {
                      # code...
                     ?>
                   <tr>
                    <td><?php echo $i++;?> </td>
                    <td><?php echo $expiring->product_id;?></td>
                    <td><?php echo $expiring->product_name;?></td>
                    <td><?php echo $expiring->expiry_date;?></td>
                   </tr>
                    <?php } } else{ ?>
                      <tr>
                        <td colspan="5" class="text-center">No Record Found</td>
                      </tr>
                    <?php } ?>
                    </tbody>
                 </table>
                  </div>
                </div>
              </div>

        
            </div>

          <div class="row">

         

            <div class="col-md-4 col-sm-4 ">
              <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                  <h2>Category Wise Purchase</h2>
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
                  <table class="" style="width:100%">
                    <tr>
                      <th style="width:37%;">
                        <p>Top 5</p>
                      </th>
                      <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 ">
                          <p class="">Category</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 ">
                          <p class="">Purchase</p>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <td>
                        <canvas class="canvasDoughnut" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                      </td>
                      <td>
                        <table class="tile_info">
                          <?php $category=$this->admin->getRows('select category_name from main_categories where status=1 and user_id="'.$this->session->userdata('userid').'" limit 5');
                          $i=0;
                          foreach ($category as $cat) {
                            # code...
                            $i++;
                        
                          ?>
                          <tr>
                            <td>
                              <p><i class="fa fa-square <?php if($i==1){echo "blue"; } else if($i==2){echo "green";} else if($i==3){echo "red";}else if($i==4){echo "grey";}else if($i==5){echo "purple";} ?>"></i><?php echo ucfirst($cat->category_name);?> </p>
                            </td>
                            <td>30%</td>
                          </tr>
                          <?php
                        } ?>
                         
                        
                         
                         
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>


           



          </div>



        
        </div>
        <!-- /page content -->

      