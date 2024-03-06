
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>User Profile</h3>
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
				


     <div class="container">
    <div class="row my-2">
        <div class="col-lg-8 order-lg-2">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Profile</a>
                </li>
                <!-- <li class="nav-item">
                    <a href="" data-target="#messages" data-toggle="tab" class="nav-link">Messages</a>
                </li> -->
                <li class="nav-item">
                    <a href="" data-target="#edit" data-toggle="tab" class="nav-link">Edit</a>
                </li>
            </ul>
            <div class="tab-content py-4">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3">User Profile</h5>
                    <div class="row">
                        <div class="col-md-6" style="color: black;">
                            <h6>Company Details</h6>
                                <span><b>Company Name:</b> <?php echo $user_details->company_name; ?></span>
                                <br><span><b>Address:</b> <?php echo $user_details->address; ?></span>
                                <br><span><b>Email:</b> <?php echo $user_details->email; ?></span>
                                <br><span><b>Mobile:</b> <?php echo $user_details->mobile; ?></span>
                            <br><br>
                            <h6>Package Details</h6>
                           
                                <span><b>Registration Date:</b> <?php echo $user_details->register_date; ?></span>
                                <br><span><b>Valid Upto:</b> <?php echo $user_details->valid_upto; ?></span>
                                <br><span><b>Username:</b> <?php echo $user_details->username; ?></span>
                                <br><span><b>Password:</b> <?php echo $user_details->password; ?></span>
                                <br><span><b>Current Status:</b> <?php  $status=$user_details->active_status; if($status==1){ echo "Active"; }elseif($status==2){ echo "Inactive"; } ?></span>
                                <br><br>
                            
                        </div>
                        <div class="col-md-6">
                            <h6>Recent Categories</h6>
                            <?php if(!empty($categories))
                            {
                              foreach ($categories as $category) {
                                # code...
                             ?>
                            <a href="#" class="badge badge-dark badge-pill"><?php echo $category->category_name;?></a>
                             <?php 
                           }
                            } else
                            {
                              echo "Yet no category available";
                            }?>
                            
                            <hr>
                            <span class="badge badge-primary" style="font-size: 12px;"><i class="fa fa-user"></i> <?php echo "$product_count"?> Products</span>
                            <span class="badge badge-success" style="font-size: 12px;"><i class="fa fa-cog"></i> <?php echo "$invoice_count"?> Invoice Generated</span>
                            <span class="badge badge-danger" style="font-size: 12px;"><i class="fa fa-eye"></i> <?php echo "$purchase_count"?> Purchases</span>
                        </div>
                        <!-- <div class="col-md-12">
                            <h5 class="mt-2"><span class="fa fa-clock-o ion-clock float-right"></span> Recent Activity</h5>
                            <table class="table table-sm table-hover table-striped">
                                <tbody>                                    
                                    <tr>
                                        <td>
                                            <strong>Abby</strong> joined ACME Project Team in <strong>`Collaboration`</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Gary</strong> deleted My Board1 in <strong>`Discussions`</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Kensington</strong> deleted MyBoard3 in <strong>`Discussions`</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>John</strong> deleted My Board1 in <strong>`Discussions`</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Skell</strong> deleted his post Look at Why this is.. in <strong>`Discussions`</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> -->
                    </div>
                    <!--/row-->
                </div>
               
                <div class="tab-pane" id="edit">
                    <form action="<?php echo base_url();?>user/user_profile/update_profile" method="POST">
                      <?= msg();?>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Company Name</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" readonly value="<?php echo $user_details->company_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Address</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" value="<?php echo $user_details->address; ?>" placeholder="Street" name="address">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Email</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="email" value="<?php echo $user_details->email; ?>" name="email">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Mobile</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" name="mobile" value="<?php echo $user_details->mobile; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Registration Date</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" readonly value="<?php echo $user_details->register_date; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Valid Upto</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" readonly value="<?php echo $user_details->valid_upto; ?>">
                            </div>
                          </div>
                       
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Username</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" value="<?php echo $user_details->username; ?>" name="username">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Password</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="password" value="<?php echo $user_details->password; ?>" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Confirm password</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="password" value="<?php echo $user_details->password; ?>" id="cpassword" required>
                                <span id="error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <input type="reset" class="btn btn-secondary" value="Cancel">
                                <input type="submit" class="btn btn-primary" value="Save Changes">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 order-lg-1 text-center">
            <img src="//placehold.it/150" class="mx-auto img-fluid img-circle d-block" alt="MIT">
            <h6 class="mt-2">Upload a different photo</h6>
            <label class="custom-file">
                <input type="file" id="file" class="custom-file-input">
                <span class="custom-file-control">Choose file</span>
            </label>
        </div>
    </div>
</div>
			   </div>
			<!-- /page content -->

<script>
  $('form').on('submit', function() {
  var password = $('#password').val();
  var cpassword = $('#cpassword').val();
  if (password != cpassword ) {
    $('#error').html("Password and Confirm Password does not match");
    $('#password').val('');
    $('#cpassword').val('');
    return false;
  }
});

</script>
