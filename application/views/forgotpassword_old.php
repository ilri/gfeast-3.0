<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>OLM</title>
    <link rel="apple-touch-icon" href="<?php echo base_url('uploads/fav.png'); ?>">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/vendors.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url(); ?>includeout/jquery-toast-plugin/src/jquery.toast.css"/>
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/app.css'); ?>">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/core/menu/menu-types/vertical-menu.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/core/colors/palette-gradient.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/vendors/css/cryptocoins/cryptocoins.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url();?>include/vendors/datepicker/css/datepicker.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/assets/css/style.css'); ?>">
    <!-- END Custom CSS-->

    <!-- BEGIN VENDOR JS-->
    <script src="<?php echo base_url('include/app-assets/vendors/js/vendors.min.js'); ?>"></script>
    <script src="<?php echo base_url(); ?>includeout/jquery-toast-plugin/src/jquery.toast.js"></script>
    <!-- BEGIN VENDOR JS-->

    <style type="text/css">
      .bold{
        font-weight: bold;
      }
      .mt-10{
        margin-top: 10px;
      }
      .mt-20{
        margin-top: 20px;
      }
      .mt-30{
        margin-top: 30px;
      }
      .mt-40{
        margin-top: 40px;
      }
      .p-10{
        padding: 10px;
      }
      .p-20{
        padding: 20px;
      }
      .p-30{
        padding: 30px;
      }
      label{
        font-weight: bold;
        color: #1e9ff2 !important;
      }
      .title{
        font-weight: bold;
      }
      .mb-10{
        margin-bottom: 10px;
      }
      .mb-20{
        margin-bottom: 20px;
      }
      .heading{
        font-weight: 500;
      }
      /*style for scrollbar everywhere */
    /* width */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #1e9ff2;
      border-radius:20px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #1e9ff2; 
    }
    </style>

  </head>
  <body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns" style="overflow:hidden;">
    <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
      <div class="navbar-wrapper">
        <div class="navbar-header" style="width: auto;padding: 5px 10px;">
          <ul class="nav navbar-nav flex-row" style="height: 100%;">
            <li class="nav-item mobile-menu d-md-none mr-auto">
              <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a>
            </li>
            <li class="nav-item" style="height: 100%;">
              <a class="navbar-brand" href="javascript:void(0);" style="height: 100%; padding:0;">
                <img class="brand-logo" src="<?php echo base_url('uploads/logo.png'); ?>" style="height: 100%; width: auto;">
              </a>
            </li>
            <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
          </ul>
        </div>
        <div class="navbar-container content"></div>
      </div>
    </nav>

    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
      <div class="page-content vertical-align-middle">
        <div class="row" style="margin-top: 120px;">
          <div class="col-md-2"></div>
          <div class="col-md-3"></div>
          <div class="col-md-3" style="margin-left: -50px;">
            <h2 style="font-weight: bold;">Forgot Your Password ?</h2>
            <p>Input your registered email to reset your password</p>

            <?php echo form_open('',array('class' => 'form-horizontal', 'id' => 'lostPassword')); ?>
              <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control empty" id="inputEmail" name="email" placeholder="Your Email Id">
                <span class="error email text-danger"></span>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Reset Your Password</button>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Page Script -->
    <script type="text/javascript">
      // Handle lostPassword form submit
      $('#lostPassword').on('submit', function(event) {
        event.preventDefault();
        var form = $(this);
        $('.error').empty();
        $('button').prop('disabled', true);
        $('button[type="submit"]').html('Please wait...');

        var formData = new FormData($(this)[0]);
        $.ajax({
          url: '<?php echo base_url(); ?>password/resetpassword/',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          complete: function(data) {
            var csrfData = JSON.parse(data.responseText);
            if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
              $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
            }
          },
          error: function() {
            $('button').prop('disabled', false);
            $('button[type="submit"]').html('Reset Your Password');
            $.toast({
              heading: 'Network Error!',
              text: 'Could not establish connection to server. Please refresh the page and try again.',
              icon: 'error'
            });
          },
          success: function(data) {
            var data = JSON.parse(data);
            $('button').prop('disabled', false);
            $('button[type="submit"]').html('Reset Your Password');
            
            // If validation error exists
            if(data.status > 0) {
              for(var key in data) {
                var errorContainer = form.find(`.${key}.error`);
                if(errorContainer.length !== 0) {
                  errorContainer.html(data[key]);
                }
              }
            }

            if(data.sentstatus == 1) {
              // If email sent completed
              form.trigger('reset');
              $.toast({
                heading: 'Success!',
                text: data.msg,
                icon: 'success'
              });
            } else if(data.sentstatus == 0) {
              $.toast({
                heading: 'Error!',
                text: data.msg,
                icon: 'error'
              });
            }
          }
        });
      });
    </script>