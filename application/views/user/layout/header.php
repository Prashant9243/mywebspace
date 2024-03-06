<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>MIT Inventory Management System</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url();?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url();?>vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url();?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url();?>vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo base_url();?>vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url();?>vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!--**************************************table**********************************-->
    <link href="<?php echo base_url();?>vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/jquery.dataTables.min" rel="stylesheet">
    <!-- Switchery -->
    <link href="<?php echo base_url();?>vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url();?>assets/css/custom.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/jquery-ui.css" rel="stylesheet">
    <!-- select option-->
    <link href="<?php echo base_url();?>assets/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo base_url();?>assets/js/select2.min.js"></script>
    <!-- select option-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-uis.css">
    <!--- Jquery min ----->
    <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    <!--- Jquery validate min js ----->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>
    <style>
      .error
      {
        color:red!important;
      }
    </style>
    <script>
      var url = '<?php echo base_url();?>';
    </script>

 </head>
  <!-- Default body class nav-md for medium normal sidebar -->
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><img src="<?php echo base_url();?>assets/images/<?php echo $this->session->userdata('company_logo');?>" width="210px" height="100px"></a>
            </div>
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title"><span class="small"><?php echo $this->session->userdata('company_name');?></span></a>
            </div>
            <div class="clearfix"></div>
            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo base_url();?>assets/images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo ucfirst($this->session->userdata('username')); ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->
            <br />
