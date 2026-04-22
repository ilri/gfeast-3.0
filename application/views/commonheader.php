<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<title>FEAST 3.0</title>
	<link rel="apple-touch-icon" href="<?php echo base_url('uploads/fav.png'); ?>">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

	<!-- BEGIN: Vendor CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/vendors.css'); ?>">
	<!-- END: Vendor CSS-->

	<!-- BEGIN MODERN CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/app.css'); ?>">
	<!-- END MODERN CSS-->
	<!-- BEGIN Page Level CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/core/menu/menu-types/vertical-menu.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/css/core/colors/palette-gradient.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/app-assets/vendors/css/cryptocoins/cryptocoins.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url();?>include/vendors/datepicker/css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css"/>
	<!-- END Page Level CSS-->
	<!-- BEGIN Custom CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/assets/css/style.css'); ?>">

	<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/bootstrap-select/bootstrap-select.css">
	<!-- END Custom CSS-->
	<link rel="stylesheet" href="<?php echo base_url();?>include/vendors/colorbox/colorbox.css">
	<link rel="stylesheet" href="<?php echo base_url();?>includeout/intlTelInput/build/css/intlTelInput.css">

	<!-- BEGIN VENDOR JS-->
	<script src="<?php echo base_url('include/app-assets/vendors/js/vendors.min.js'); ?>"></script>
	<script src="<?php echo base_url(); ?>include/vendors/moment/min/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>include/vendors/bootstrap-sweetalert-master/dist/sweetalert.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>include/vendors/bootstrap-sweetalert-master/dist/sweetalert.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js"></script>
	<!-- BEGIN VENDOR JS-->

	<script src="<?php echo base_url(); ?>include/vendors/datepicker/js/bootstrap-datepicker.min.js"></script>

	<script src="<?php echo base_url(); ?>includeout/amcharts4/core.js"></script>
    <script src="<?php echo base_url(); ?>includeout/amcharts4/charts.js"></script>
    <script src="<?php echo base_url(); ?>includeout/amcharts4/maps.js"></script>
    <script src="<?php echo base_url(); ?>includeout/amcharts4/themes/kelly.js"></script>
    <script src="<?php echo base_url(); ?>includeout/amcharts4/themes/animated.js"></script>
    <script src="<?php echo base_url(); ?>includeout/amcharts4/geodata/worldLow.js"></script>
	<script src="<?php echo base_url(); ?>includeout/amcharts4/themes/animated.js"></script>
	<script src="<?php echo base_url(); ?>includeout/amcharts4/geodata/usaLow.js"></script>
	<script src="<?php echo base_url(); ?>includeout/amcharts4/geodata/worldHigh.js"></script>
	<script src="<?php echo base_url(); ?>includeout/amcharts4/geodata/worldIndiaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
  <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
  <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
  <script src="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.js"></script>

	<style type="text/css">
		html body .content .content-wrapper {
		    padding: 1.2rem;
		}
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
		.red-800{
			color:red;
		}
		/*style for scrollbar everywhere */
		/* width */
		::-webkit-scrollbar {
			width: 8px;
			height: 8px;
		}

		/* Track */
		::-webkit-scrollbar-track {
			background: #f1f1f1; 
		}

		/* Handle */
		::-webkit-scrollbar-thumb {
			background: #1e9ff2;
		}

		/* Handle on hover */
		::-webkit-scrollbar-thumb:hover {
			background: #1e9ff2; 
		}

		/*Select2 User Defined CSS*/
		.select2-container--default .select2-selection--multiple .select2-selection__choice {
			color: #555555 !important;
		}
		.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove, .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
			color: #c85252 !important;
		}

		.content-body{
			margin-bottom: 30px;
		}

		h1, h2, h3, h4, h5, h6,
		.h1, .h2, .h3, .h4, .h5, .h6 {
		    font-family: 'Open Sans', sans-serif;
		    font-weight: 400;
		    line-height: 1.2;
		    margin-bottom: .5rem;
		    color: #464855;
		}
		.header-navbar { font-family: 'Open Sans', sans-serif; }
		.navbar-semi-light .navbar-nav .nav-link { color: #07612C; font-weight: 500; }
		.navbar-brand h1 {
			display: inline-block;
			padding-top: .3125rem;
			padding-bottom: .3125rem;
			padding-left: 5px;
			margin-top: 5px;
			font-size: 40px !important;
			line-height: inherit;
			white-space: nowrap;
			color: #007bff !important;
			font-weight: 600;
		}
		.dropdown .dropdown-menu .dropdown-item {
			width: 100%;
			padding: 7px 20px;
		}
		label {
			font-weight: bold;
			color: #800000 !important;
		}
		.btn-primary {
			color: #fff;
			border-color: #800000 !important;
			background-color: #800000 !important;
		}
		.btn-primary:hover {
			color: #fff;
			border-color: #800000 !important;
			background-color: #800000 !important;
		}
	</style>

</head>
<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
	<!-- fixed-top-->
	<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
		<div class="navbar-wrapper">
			<div class="navbar-header" style="width: auto;padding: 5px 10px;">
				<ul class="nav navbar-nav flex-row" style="height: 100%;">
					<li class="nav-item mobile-menu d-md-none mr-auto">
						<a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a>
					</li>
					<li class="nav-item" style="height: 100%;">
						<a class="navbar-brand pl-1" href="javascript:void(0);" style="height: 100%; padding:0;">
							<!-- <h1>FEAST 3.0</h1> -->
							<img src="<?php echo base_url(); ?>include/dist/img/ilri_logo.png" height="60px" alt="Logo icrisat">
							<!-- <img class="brand-logo" src="<?php echo base_url('uploads/logo.png'); ?>" style="height: 100%; width: auto;"> -->
						</a>
					</li>
					<li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
				</ul>
			</div>
			
		</div>
	</nav>
