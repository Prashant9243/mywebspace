
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Manage Category</h3>
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
									<h2>Product Main <small>category</small></h2>
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
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Category Name <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 ">
												<input type="text" id="categoryname" required="required" class="form-control ">
											</div>
										</div>
										
										<div class="item form-group">
											<label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Status <span class="required">*</span>
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
                    <h2>Product categories</h2>
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
                      Add New Product Category
                    </p>
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Category Name</th>
                          <th>Status</th>
                        </tr>
                      </thead>


                      <tbody class="tbody">
                      	<?php $i=1;
                      	foreach ($categories as $category) {
                      		# code...
                      ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $category->category_name; ?></td>
                          <td><?php if(($category->status)==1){?> <span class="badge badge-success">Active</span>
                      <?php }else{?><span class="badge badge-danger">Inactive</span><?php } ?></td>
                        </tr>
                    <?php } ?>
                        
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
            var status = $("#status").val();
            $.ajax({
                url: "<?php echo base_url(); ?>user/Manage_categories/maincategoryadd",
                method: "POST",
                data: {categoryname: categoryname,status:status},
                dataType:"json",
                success: function(data) {
                	console.log(data);
                	var html = '';
                	var i=1;
                    $.each(data, function (key, item) {
                    	var sta=item.status;
                	if(sta==1)
                	{
                		statusoutput='<span class="badge badge-success">Active</span>';
                	}
                	else if(sta==2)
                	{
                		statusoutput='<span class="badge badge-danger">Inactive</span>';
                	}
                        html += '<tr>';
                        html += '<td>' + i++ + '</td>';
                        html += ' <td>' + item.category_name + '</td>';
                        html += '<td>' + statusoutput + '</td>';
                        
                        html += '</tr>';
                    });
                    $('.tbody').html(html);
                    $("#demo-form2").trigger("reset");
                    console.log(html);
                },
                error: function() {
                    alert("New category not inserted");
                }
            });
        });
			  });
</script>