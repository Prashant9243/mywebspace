
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>Manage Retailer</h3>
              <span id="message"><?php echo msg();?></span>
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
                    <h2>Retailer List</h2>
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
                      Retailer
                    </p>
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Contact Number</th>
                          <th>Address</th>
                          <th>Action</th>
                        </tr>
                      </thead>


                      <tbody class="tbody">
                      	<?php $i=1;
                        if(!empty($retailer))
                        {
                      	foreach ($retailer as $retailers) { ?>
                        <tr id="<?php echo ($retailers->id);?>">
                          <td><?php echo $i++; ?></td>
                          <td><?php if(!empty($retailers->name)){ echo ($retailers->name); } else { echo "-";} ?></td>
                          <td><?php if(!empty($retailers->email)){ echo ($retailers->email); } else { echo "-";} ?></td>
                          <td><?php if(!empty($retailers->contact_no)){ echo ($retailers->contact_no); } else { echo "-";} ?></td>
                          <td><?php if(!empty($retailers->address)){ echo ($retailers->address); } else { echo "-";} ?></td>

                        <td style="white-space: nowrap;"> 
                            <a href="<?php echo base_url();?>user/Manage_retailers/index/<?php echo $retailers->id;?>" data-toggle="tooltip" title="Edit"><span class="badge badge-success" style="font-size: 14px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span></a>

                            <a data-toggle="tooltip" title="Delete" ><span class="badge badge-danger remove" style="font-size: 14px;"><i class="fa fa-trash" aria-hidden="true"></i></span></a>


                        </td>


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
<script type="text/javascript">
    $(".remove").click(function(){
        var id = $(this).parents("tr").attr("id");
        //alert(id);

        if(confirm('Are you sure to remove this record ?'))
        {
            $.ajax({
               url: "<?php echo base_url(); ?>user/Manage_retailers/delete",
               type: 'POST',
               data:{id: id},
               error: function() {
                  alert('Something is wrong');
                  console.log(data);
               },
               success: function(data) {
                    //$("#"+id).remove();
                    //console.log(data);
                    alert("Record removed successfully"); 
                    location.reload();
 
               }
            });
        }
    });


</script>
<script type="text/javascript">
    $("#wish").click(function(){
        var id = $(this).parents("tr").attr("id");
        //alert(id);

            $.ajax({
               url: "<?php echo base_url(); ?>user/Manage_products/move_to_wishlist",
               type: 'POST',
               data:{id: id},
               error: function() {
                  alert('Something is wrong');
                  //console.log(data);
               },
               success: function(data) {
                $('#message').addClass("alert-success").html(data);
                
 
               }
            });
        
    });


</script>
<script type="text/javascript">
    $("#sold").click(function(){
        var id = $(this).parents("tr").attr("id");
        //alert(id);

            $.ajax({
               url: "<?php echo base_url(); ?>user/Manage_products/sold_products",
               type: 'POST',
               data:{id: id},
               error: function() {
                  alert('Something is wrong');
                  //console.log(data);
               },
               success: function(data) {
                $('#message').addClass("alert-success").html(data);
                //console.log(data);
                var old=$('#tquantity').html();
                var newq=old-1;
                $('#tquantity').html(newq);
                //console.log(data);
                //alert(newq);
 
               }
            });
        
    });


</script>