       <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Manage Retailer</h3>
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
                    <h2> New Product<small>Add</small></h2>
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
                                  <p><small>Retailer Details</small></p>
                              </div>
                              
                              
                          </div>
                      </div>
                    </div>
    <div class="row">
    <form  style="width:100%;" action="<?php echo base_url();?>user/Manage_retailers/insertretailer" method="POST">
        <div class="card card-primary setup-content" id="step-1">
            <div class="card-heading">
                 <h3 class="card-title">Retailer Details</h3>
            </div>
            <div class="card-body">
              <div class="form-group row">
                            <input type="hidden" value="<?php if($edit->id){ echo $edit->id; } ?>" name="wholesaler_id">
                            <label for="wholesaler_name" class="col-form-label col-md-3 col-sm-3 label-align">Retailer Name</label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="wholesaler_name" class="form-control col" type="text" name="wholesaler_name" value="<?php if($edit->name){ echo $edit->name; } ?>" >
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="cgst" class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="W_email" class="form-control col" type="email" name="W_email" value="<?php if($edit->email){ echo $edit->email; }?>">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="contact_number" class="col-form-label col-md-3 col-sm-3 label-align">Contact Number</label>
                            <div class="col-md-6 col-sm-6 ">
                              <input id="contact_number" class="form-control col" type="text" name="contact_number" value="<?php if($edit->name){ echo $edit->contact_no; } ?>" >
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="address" class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
                            <div class="col-md-6 col-sm-6 ">
                              <textarea name="address"id="address" class="form-control"><?php if($edit->name){ echo $edit->address; } ?></textarea> 
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
        

	  </body>
</html>