  <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="<?php echo base_url(); ?>user/user_dashboard"><i class="fa fa-home"></i> Dashboard </a></li>
                  <li><a href="<?php echo base_url(); ?>user/user_profile"><i class="fa fa-check-square-o"></i> Profile </a></li>

                  <li><a><i class="fa fa-desktop"></i> Retailer Management <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url(); ?>user/Manage_retailers">Add Retailer</a></li>
                      <li><a href="<?php echo base_url(); ?>user/Manage_retailers/retailers_list">List Retailers</a></li>
                      
                    </ul>
                  </li>

                  <li><a><i class="fa fa-cubes"></i> Product Management <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url(); ?>user/Manage_products">Add Product</a></li>
                      <li><a href="<?php echo base_url(); ?>user/Manage_products/product_list">Product List</a></li>
                      <li><a href="<?php echo base_url(); ?>user/Generate_barcode">Generate Barcode</a></li>
                      
                    </ul>
                  </li>

                  <li><a><i class="fa fa-diamond"></i> Category Management <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url(); ?>user/Manage_categories">Main Category</a></li>
                      <li><a href="<?php echo base_url(); ?>user/Manage_categories/subcategories">Sub Category</a></li>               
                    </ul>
                  </li>
                  <li><a><i class="fa fa-cart-plus"></i> Purchase Management <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url(); ?>user/Manage_purchases">Add Purchases</a></li>
                      <li><a href="<?php echo base_url(); ?>user/Manage_purchases/purchase_list">List Purchases</a></li>
                      
                    </ul>
                  </li>
                 
                   <li><a><i class="fa fa-bar-chart-o"></i> Manage Invoices <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url(); ?>user/Manage_invoice">Create Invoice</a></li>
                      <li><a href="<?php echo base_url(); ?>user/Manage_invoice/invoice_list">Invoice List</a></li>
                    </ul>
                  </li>
                  
                </ul>
              </div>
              <div class="menu_section">
                <h3>Reports</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-line-chart"></i> Sales Report <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                       <li><a href="<?php echo base_url(); ?>user/Manage_reports/daily_sales_report">Todays Sales</a></li>
                     <li><a href="<?php echo base_url(); ?>user/Manage_reports/monthly_sales_report">Monthly / Yearly Sales Report</a></li>
                      <!-- <li><a href="<?php echo base_url(); ?>user/Manage_reports/yearly_sales_report">Yearly Sales Report</a></li> -->
                    </ul>
                  </li>
                  <li><a><i class="fa fa-qrcode"></i> Purchase Report <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url(); ?>user/Manage_reports">Todays Purchase </a></li>
                      <li><a href="<?php echo base_url(); ?>user/Manage_reports/monthly_purchase_report">Monthly / Yearly Purchase Report</a></li>
                      <!-- <li><a href="<?php echo base_url(); ?>user/Manage_reports/yearly_purchase_report">Yearly Purchase Report</a></li> -->
                    </ul>
                  </li>
                        
                <!--   <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li> -->
                </ul>
              </div>


                <div class="menu_section">
                <h3>Alerts</h3>
                <ul class="nav side-menu">
                  <li><a href="<?php echo base_url(); ?>user/Manage_alerts"><i class="fa fa-clock-o"></i> Expiry Alert </a></li>
                  <li><a href="<?php echo base_url(); ?>user/Manage_alerts/out_of_stock"><i class="fa fa-exclamation-triangle"></i> Out of Stock Alert</a></li>
                  <li><a href="<?php echo base_url(); ?>user/Manage_alerts/wishlist"><i class="fa fa-shopping-bag"></i> Products Required</a></li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url();?>Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo base_url();?>assets/images/img.jpg" alt=""><?php echo ucfirst($this->session->userdata('username')); ?>
                  </a>
                  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="javascript:;"> Profile</a>
                      <a class="dropdown-item"  href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                  <a class="dropdown-item"  href="javascript:;">Help</a>
                    <a class="dropdown-item"  href="<?php echo base_url();?>Logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </div>
                </li>

                <li role="presentation" class="nav-item dropdown open">
                  <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bullhorn" style="font-size:25px;"></i>
                    <span class="badge bg-green">
                      <?php $user=$this->session->userdata('userid');
                       $today=date('Y-m-d'); 
                       $days_ahed = date('Y-m-d', strtotime('+15 days', strtotime($today)));
                       $expiring_soon_count=$this->admin->getVal('select count(*) from products where expiry_date between "'.$today.'" AND "'.$days_ahed.'"  and user_id="'.$user.'" and status=1');
                      // echo $this->db->last_query();
                      $expired_count=$this->admin->getVal('select count(*) from products where expiry_date<"'.$today.'" and user_id="'.$user.'" and status=1 ');

                      $total_count=$expiring_soon_count+$expired_count;
                      echo $total_count;
                      ?></span>
                  </a>
                  <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                    <h4 class="text-center" style="font-size: 21px;">Expired</h4>
                    <?php
                    
                    $expired=$this->admin->getRows('select * from products where expiry_date<"'.$today.'" and user_id="'.$user.'" and status=1 limit 2'); 
                    //echo $this->db->last_query();
                    foreach ($expired as $expire) {
                      # code...
                   
                     ?>
                    <li class="nav-item">
                      <a class="dropdown-item">
                        <span>
                          <span><b>Product Name:</b> </span><?php echo $expire->product_name;?>
                          <span class="time">0 Days</span>
                        </span>
                        <span class="message">
                          <b>Product Code - </b><?php echo $expire->product_id;?><br><b>Wholesaler Name - </b> 
                          <?php 
                          $w_id=$expire->wholesaler_id;
                          $wholesaler_name=$this->admin->getVal('select name from wholesaler_details where id="'.$w_id.'"');
                          if(!empty($wholesaler_name))
                          {
                            echo ucfirst($wholesaler_name);
                          }
                          else{
                            echo "-";
                          }
                          ?>
                        </span>
                      </a>
                    </li>     
                    <?php } ?>
                       <h4 class="text-center" style="font-size: 21px;">Expiring Soon</h4>
                    <?php
                    
                    $expiring_soon=$this->admin->getRows('select * from products where expiry_date between "'.$today.'" AND "'.$days_ahed.'"  and user_id="'.$user.'" and status=1 limit 2');
                    
                    if(!empty($expiring_soon))
                    {
                    foreach ($expiring_soon as $expiring) {
                      # code...
                     ?>
                     <li class="nav-item">
                      <a class="dropdown-item">
                        <span>
                          <span><b>Product Name:</b> </span><?php echo $expiring->product_name;?>
                          <span class="time"><?php $now = time(); $date=$expiring->expiry_date; $expdate=strtotime($date);$datediff = $now - $expdate; echo round($datediff / (60 * 60 * 24)); ?> Days</span>
                        </span>
                        <span class="message">
                          <b>Product Code - </b><?php echo $expiring->product_id;?><br><b>Wholesaler Name - </b> 
                          <?php 
                          $w_id=$expiring->wholesaler_id;
                          $wholesaler_name=$this->admin->getVal('select name from wholesaler_details where id="'.$w_id.'"');
                          if(!empty($wholesaler_name))
                          {
                            echo ucfirst($wholesaler_name);
                          }
                          else{
                            echo "-";
                          }
                        }
                          ?>
                        </span>
                      </a>
                    </li>     
                    <?php }?>
                   
                   
                    <li class="nav-item">
                      <div class="text-center">
                        <a class="dropdown-item">
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

