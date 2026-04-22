<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>Message - OLM</title>
    <link rel="apple-touch-icon" href="<?php echo base_url('uploads/fav.png'); ?>">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/vendors.css'); ?>">
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
  <body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
    <!-- fixed-top-->
   <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
      <div class="navbar-wrapper">
        <div class="navbar-header">
          <ul class="nav navbar-nav flex-row">
            <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
            <li class="nav-item">
              <a class="navbar-brand" href="javascript:void(0);">
                <img class="brand-logo" src="<?php echo base_url('include/verd_logo.png'); ?>" style="width: 100%; margin-top: -10px;">
              </a>
            </li>
            <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
          </ul>
        </div>
        <div class="navbar-container content"></div>
      </div>
    </nav>


    <!-- Page -->
    <div class="app-content content" style="margin-left: 0px; margin-bottom: 50px;">
        <div class="content-wrapper">
            <div class="content-body">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-md-12">
                        <?php
                          $message = $this->session->flashdata('err');
                          if (isset($message)) {
                            echo '<div class="alert alert-danger">' . $message . '</div>';
                            $this->session->unset_userdata('err');
                          }
                          $message2 = $this->session->flashdata('succ');
                          if (isset($message2)) {
                            echo '<div class="alert alert-success">' . $message2 . '</div>';
                            $this->session->unset_userdata('succ');
                          }
                          ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page -->


   