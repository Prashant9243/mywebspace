
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Manage Sub Category</h3>
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
									<h2>Product Sub <small>category</small></h2>
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
									<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                      <div class="item form-group">
                      <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Select Main Category <span class="required text-danger">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 ">
                        <select class="select2_single form-control js-example-basic-single" tabindex="-1" id="categoryname" required="">
                          <option selected="" disabled="" hidden="">Select Main Category First</option>
                          <?php foreach ($maincategories as $maincategory) { ?>
                           
                          <option value="<?php echo($maincategory->id); ?>"><?php echo($maincategory->category_name); ?></option>
                           <?php # code ... ... ..
                          } ?>
                        </select>
                    </div>
                    </div>


										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Sub Category Name <span class="required text-danger" >*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input type="text" id="subcategory" required="required" class="form-control ">
											</div>
										</div>
										
										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Status <span class="required text-danger">*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
													<div class="">
													<label>
														<input type="checkbox" class="js-switch" id="status" checked /> 
													</label>
												</div>
												
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

            <div class="row">
              <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Product Sub categories</h2>
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
                    <p class="text-muted font-13 m-b-30">
                      Add New Product Sub Category
                    </p>
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Main Category Name</th>
                          <th>Sub Category Name</th>
                          <th>Status</th>
                        </tr>
                      </thead>


                      <tbody class="tbody">
                      	<?php $i=1;
                        if(!empty($subcategories))
                        {
                      	foreach ($subcategories as $subcategory) {
                      		# code...
                      ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php $maincategoryname=$this->admin->getVal('select category_name from main_categories where id="'.$subcategory->main_category_id.'"'); echo $maincategoryname;  ?></td>
                          <td><?php echo $subcategory->sub_category_name; ?></td>
                          <td><?php if(($subcategory->status)==1){?> <span class="badge badge-success">Active</span>
                      <?php }else{?><span class="badge badge-danger">Inactive</span><?php } ?></td>
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

			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

			<script type="text/javascript">
      
      		$(document).ready(function () {

				if ($('#status').prop('checked')) {
   					$('#status').val(1);
				}
				else{
					$('#status').val(2);
				}
				$('#status').change(function() {
					if ($('#status').prop('checked')) {
   					$('#status').val(1);
				}
				else{
					$('#status').val(2);
				}
				 });	


			 $("#submit").click(function(e) {
            		e.preventDefault();
            var categoryname = $("#categoryname").val();
            var subcategory=$("#subcategory").val();
            var status = $("#status").val();
            $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_categories/subcategoryadd",
                method: "POST",
                data: {categoryname: categoryname,status:status,subcategory:subcategory},
                //dataType:"json",
                success: function(data) {
                	console.log(data);
                	  $('.tbody').html(data);
                    $("#demo-form2").trigger("reset");
                    console.log(html);
                },
                error: function() {
                    alert("New sub category not inserted");
                }
            });
        });
			  });
</script>