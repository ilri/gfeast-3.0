<!-- Sidebar content -->
<div class="app-sidebar colored" id="zSidebar" style="z-index: 1040;">
	<div class="sidebar-header">
		<a class="header-brand" href="<?php echo base_url(); ?>">
			<div class="sidebar-img-c text-center">
				<!-- <img src="<?php echo base_url(); ?>include/dist/img/munichRe.svg" class="sidebar-img" alt="Brand Logo"> -->
				<h1 style="margin-bottom:0;">FEAST 3.0</h1>
			</div>
		</a>
	</div>

	<div class="sidebar-content" id="sidebar-c">
		<div class="nav-container" id="nav-c">
			<nav id="main-menu-navigation" class="navigation-main">
				<!-- <div class="nav-item has-sub mb-5 active <?php echo $this->uri->segment(1) == 'projects' ? 'open' : ''; ?>">
					<a href="javascript:void(0)">
						<i class="ik ik-bar-chart-2"></i><span class="nav-clr">Projects</span>
					</a>
					<div class="submenu-content">
						<a href="javascript:void(0)" class="menu-item text-light">Manage Location</a>
						<a href="javascript:void(0)" class="menu-item text-light">Manage Activity</a>
					</div>
				</div> -->
				<div class="nav-item has-sub mb-5 active <?php echo $this->uri->segment(1) == 'users' ? 'open' : ''; ?>">
					<a href="javascript:void(0)">
						<i class="ik ik-user"></i><span class="nav-clr">Users</span>
					</a>
					<div class="submenu-content">
						<a href="<?php echo base_url(); ?>users/create" class="menu-item text-light <?php echo $this->uri->segment(2) == 'create' ? 'active' : ''; ?>">Create User</a>
						<a href="<?php echo base_url(); ?>users/manage" class="menu-item text-light <?php echo $this->uri->segment(2) == 'manage' ? 'active' : ''; ?>">Manage Users</a>
						<a href="<?php echo base_url(); ?>users/map" class="menu-item text-light <?php echo $this->uri->segment(2) == 'map' ? 'active' : ''; ?>">Map Users</a>
						<a href="<?php echo base_url(); ?>users/track" class="menu-item text-light <?php echo $this->uri->segment(2) == 'track' ? 'active' : ''; ?>">Track REs</a>
					</div>
				</div>
				<div class="nav-item has-sub mb-5 active <?php echo $this->uri->segment(1) == 'reports' ? 'open' : ''; ?>">
					<a href="javascript:void(0)">
						<i class="ik ik-database"></i><span class="nav-clr">Data</span>
					</a>
					<div class="submenu-content">
						<a href="<?php echo base_url(); ?>reports/registration" class="menu-item text-light <?php echo $this->uri->segment(2) == 'registration' ? 'active' : ''; ?>">Focus Group Discussions (FGD)</a>
					</div>
					<div class="submenu-content">
						<a href="<?php echo base_url(); ?>reports/mappoints" class="menu-item text-light <?php echo $this->uri->segment(2) == 'mappoints' ? 'active' : ''; ?>">Individual Farmer Interviews (IFI)</a>
					</div>
				</div>
				<!-- <div class="nav-item has-sub mb-5 active <?php echo $this->uri->segment(1) == 'kml' ? 'open' : ''; ?>">
					<a href="javascript:void(0)">
						<i class="ik ik-user"></i><span class="nav-clr">KML</span>
					</a>
					<div class="submenu-content">
						<a href="<?php echo base_url(); ?>kml/view" class="menu-item text-light <?php echo $this->uri->segment(2) == 'view' ? 'active' : ''; ?>">View KML</a>
					</div>
				</div> -->
				<!-- <div class="nav-item mb-5">
					<a href="javascript:void(0)">
						<i class="ik ik-file-text"></i><span class="nav-clr">Reports</span>
					</a>
				</div>
				<div class="nav-item">
					<a href="javascript:void(0)">
						<i class="ik ik-globe"></i><span class="nav-clr">Organization</span>
					</a>
				</div>
				<div class="nav-item mb-5">
					<a href="javascript:void(0)">
						<i class="ik ik-layout"></i><span class="nav-clr">Form</span>
					</a>
				</div> -->

				<hr />
				<!-- <div class="nav-item" id="expandSidebar" style="position: relative; left: 85px;">
					<a href="javascript:void(0)" class="nav-toggle">
						<button class="round-button">
							<i data-toggle="expanded"
							class="ixx text-light ik ik-align-left toggle-icon"></i>
						</button>
					</a>
				</div> -->
				<div class="nav-item logout">
					<a href="<?php echo base_url(); ?>auth/logout">
						<i class="ik ik-log-out"></i><span class="nav-clr">Logout</span>
					</a>
				</div>
			</nav>
		</div>
	</div>
</div>
<!-- /Sidebar content end-->