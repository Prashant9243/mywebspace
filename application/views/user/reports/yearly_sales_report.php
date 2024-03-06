
      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Yearly Sales Report</h3>
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
                    <h2>Yearly Sale</h2>
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
                          <th>Invoice number</th>
                          <th>Invoice Date</th>
                          <th>Customer Name</th>
                          <th>Customer Mobile</th>
                          <th>Product Code</th>
                          <th>Product Name</th>
                          <th>Bill Amount</th>
                          <th>Bill Status</th>
                        </tr>
                      </thead>


                      <tbody class="tbody">
                        <?php $i=1;
                        if(!empty($invoice_list))
                        {
                        foreach ($invoice_list as $invoice) {
                        $customername=$this->admin->getVal('select customer_name from invoice_details where invoice_no="'.$invoice->invoice_no.'"');
                        $customermobile=$this->admin->getVal('select mobile from invoice_details where invoice_no="'.$invoice->invoice_no.'"');
                        $bill_amount=$this->admin->getVal('select bill_amount from invoice_details where invoice_no="'.$invoice->invoice_no.'"');
                        $bill_status=$this->admin->getVal('select bill_status from invoice_details where invoice_no="'.$invoice->invoice_no.'"'); ?>
                        <tr>
                          <td class="text-center"><?php echo $i++; ?></td>
                          <td class="text-center"><?php if(!empty($invoice->invoice_no)){ echo ($invoice->invoice_no); } else { echo "-";} ?></td>
                           <td><?php if(!empty($invoice->invoice_date)){ echo ($invoice->invoice_date); } else { echo "-";} ?></td>
                           <td><?php if(!empty($customername)){ echo $customername;}else{ echo"-";} ?></td>
                           <td><?php if(!empty($customermobile)){ echo $customermobile;}else{ echo"-";} ?></td>
                           <td><?php if(!empty($invoice->invoice_no))
                           { 
                            $product_list=$this->admin->getRows('select * from invoice where invoice_no="'.$invoice->invoice_no.'" ');
                            foreach ($product_list as $products) {
                              # code...
                           
                            echo ($products->product_id);echo"</br><br>";
                            }
                             } else { echo "-";} ?></td>
                           <td><?php if(!empty($invoice->invoice_no))
                           { 
                            $product_list=$this->admin->getRows('select * from invoice where invoice_no="'.$invoice->invoice_no.'" ');
                            foreach ($product_list as $products) {
                              # code...
                            $product_name=$this->admin->getVal('select product_name from products where product_id="'.$products->product_id.'"');
                            echo ($product_name);echo"</br><br>";
                            }
                             } else { echo "-";} ?></td>
                           <td><?php if(!empty($bill_amount)){ echo $bill_amount;}else{ echo"-";} ?></td> 
                           <td><?php if(!empty($bill_status)){ if($bill_status==1){echo"Paid";}else{ echo"Unpaid";}}else{ echo"Unpaid";} ?></td>
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
