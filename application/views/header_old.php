<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<title>MSoil</title>
	<link rel="apple-touch-icon" href="<?php echo base_url('include/app-assets/images/ico/apple-icon-120.png'); ?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('include/app-assets/images/ico/mfavicon.ico'); ?>">
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
	<link rel="stylesheet" href="<?php echo base_url(); ?>include/vendors/datepicker/css/datepicker.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/jquery-toast-plugin/src/jquery.toast.css"/>
	<!-- END Page Level CSS-->
	<!-- BEGIN Custom CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('include/assets/css/style.css'); ?>">
	<!-- END Custom CSS-->
	<link rel="stylesheet" href="<?php echo base_url();?>include/vendors/colorbox/colorbox.css">
	<!-- BEGIN VENDOR JS-->
	<script src="<?php echo base_url('include/app-assets/vendors/js/vendors.min.js'); ?>"></script>
	<script src="<?php echo base_url(); ?>include/vendors/moment/min/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>include/vendors/bootstrap-sweetalert-master/dist/sweetalert.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>include/vendors/bootstrap-sweetalert-master/dist/sweetalert.css">
	<link href="<?php echo base_url(); ?>includeout/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>includeout/jquery-toast-plugin/src/jquery.toast.js"></script>
	<script type="text/javascript">
	   var csrf_token = '<?php echo $this->security->get_csrf_hash(); ?>';
	</script>
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
	</style>

	<script type="text/javascript">
		var site_url = '<?php echo base_url(); ?>';
		function validateProperNumber(form) {
			var error = 0;
			$('input[type="tel"]', form).each(function(index) {
				var errorCode = 0,
				elem = $(this),
				errorCodes = [0, 1, 2, 3, 4],
				label = elem.closest('.form-group').find('label').html(),
				errorContainer = elem.closest('.form-group').find('.error');

				if((elem.intlTelInput('getNumber').length > 0) && (!elem.intlTelInput("isValidNumber"))) {
					error++;
					errorCode = elem.intlTelInput("getValidationError");
					if(errorCodes.includes(errorCode)) {
						errorContainer.html(`${label} is not valid.`);
					}
				}
			});
			return error;
		}
	</script>

</head>
<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
	<!-- fixed-top-->
	<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
		<div class="navbar-wrapper">
			<div class="navbar-header">
				<ul class="nav navbar-nav flex-row">
					<li class="nav-item mobile-menu d-md-none mr-auto">
						<a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a>
					</li>
					<li class="nav-item">
						<a class="navbar-brand" href="javascript:void(0);">
							<img class="brand-logo" src="<?php echo base_url('include/verd_logo.png'); ?>" style="width: 100%; margin-top: -10px;">
						</a>
					</li>
					<li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
				</ul>
			</div>
			<div class="navbar-container content p-0">
				<div class="collapse navbar-collapse" id="navbar-mobile">
					<ul class="nav navbar-nav mr-auto float-left">
						<?php foreach ($main_menu['get_user_modules'] as $key => $mainmenu) { ?>
							<li class="dropdown nav-item" data-menu="dropdown">
								<a class="dropdown-toggle nav-link <?php echo ($this->uri->segment(1) == $mainmenu['module_key']) ? 'active' : ''; ?>" href="#" data-toggle="dropdown" aria-expanded="false">
									<span data-i18n="nav.category.admin-panels"><?php echo $mainmenu['module_name']; ?></span>
								</a>
								<ul class="dropdown-menu">
									<?php foreach ($mainmenu['permission_list'] as $key => $submenu) { ?>
										<li data-menu="">
											<a class="dropdown-item" href="<?php echo base_url(); ?><?php echo $mainmenu['module_key']; ?>/<?php echo $submenu['module_key']; ?>">
												<?php echo $submenu['name']; ?>
											</a>
										</li>
									<?php } ?>                   
								</ul>
							</li>
						<?php } ?>
					</ul>
					<ul class="nav navbar-nav float-right">
						<li class="dropdown dropdown-user nav-item">
							<a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
								<span class="mr-1">Hello,<span class="user-name text-bold-700"><?php echo $this->session->userdata('name'); ?></span></span>
								<span class="avatar avatar-online">
									<img src="<?php echo base_url(); ?>uploads/user/<?php echo $this->session->userdata('image');?>" alt="avatar"><i></i>
								</span>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<!-- <a class="dropdown-item" href="<?php echo base_url(); ?>locationsetting"><i class="ft-globe"></i> Location Setting</a> -->
								<a class="dropdown-item" href="<?php echo base_url(); ?>login/profile"><i class="ft-user"></i> Edit Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo base_url(); ?>auth/logout"><i class="ft-power"></i> Logout</a>
							</div>
						</li>
						<li class="nav-item bg-white">
							<img src="<?php echo base_url(); ?>include/castrol_logo.png" style="height: 60px; margin: 4px auto;">
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>