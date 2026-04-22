<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>
<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
	.switch {
		position: relative;
		display: inline-block;
		width: 60px;
		height: 34px;
		}

		.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
		}

		.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
		}

		.slider:before {
		position: absolute;
		content: "";
		height: 26px;
		width: 26px;
		left: 4px;
		bottom: 4px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		}

		input:checked + .slider {
		background-color: #2196F3;
		}

		input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(26px);
		}

		/* Rounded sliders */
		.slider.round {
		border-radius: 34px;
		}

		.slider.round:before {
		border-radius: 50%;
		}
</style>


<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">

		<div class="content-body" style="margin-bottom: 30px;">
			<div class="row">
				<div class="col-12 ajax_message"></div>
				<div class="col-12">			    	
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-header">
								<h4 class="card-title">Roles and Capabilities</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Capability</th>
												<?php foreach ($roles_list as $key => $role) { ?>
													<th><?php echo $role['role_name']; ?></th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
										<?php foreach ($capability_list as $key => $capability) { ?>
											<tr>
												<td colspan="7" style="text-align: left; font-weight: bold;">
													<?php echo $capability['module_name']; ?>
												</td>
											</tr>
											<?php foreach ($capability['permissions'] as $key => $permission) { ?>
											<tr>
												<td><?php echo $permission['name']; ?></td>
												<?php foreach ($roles_list as $key => $role) { ?>
												<td><?php if($permission[$role['role_name']] == 1){ ?>
													<label class="switch"><input type="checkbox" class="updatepermission" data-roleid="<?php echo $role['role_id']; ?>" data-moduleid="<?php echo $capability['module_id']; ?>" data-permissionid="<?php echo $permission['permission_id']; ?>" value="1" checked ><span class="slider round"></span></label>
												<?php }else{ ?>
													<label class="switch"><input type="checkbox" class="updatepermission" data-roleid="<?php echo $role['role_id']; ?>" data-moduleid="<?php echo $capability['module_id']; ?>" data-permissionid="<?php echo $permission['permission_id']; ?>" value="0"><span class="slider round"></span></label>
												<?php } ?></td>
												<?php } ?>
											</tr>
											<?php }
										} ?>
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
</div>

<script type="text/javascript">
	$(function(){
		// Define global variable ajaxData
		var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
		
		$('body').on('click', '.updatepermission', function(){
			$elem = $(this);
			$elem.prop('disabled', true);
			
			var roleid = $elem.data('roleid');
			var moduleid = $elem.data('moduleid');
			var permissionid = $elem.data('permissionid');
			var assigingstatus = $elem.val();
			
			ajaxData['roleid'] = roleid;
			ajaxData['moduleid'] = moduleid;
			ajaxData['permissionid'] = permissionid;
			if(assigingstatus == 1){
				ajaxData['assigingstatus'] = 0;
			}else if(assigingstatus == 0){
				ajaxData['assigingstatus'] = 1;
			}
			$.ajax({
				url: '<?php echo base_url("users/update_permissions"); ?>',
				type: 'POST',
				dataType : 'json',
				data: ajaxData,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function() {
					$elem.prop('disabled', false);
					$('.ajax_message').html('<div class="alert alert-danger">Could not establish connection to server. Please refresh the page and try again.</div>');
				},
				success: function(response){
					if(response.status == 0){
						$('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>');
					}else{
						$('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');

						 if(assigingstatus == 1){
						 	$elem.attr('class', 'updatepermission');
							$elem.val("0");
							assigingstatus = "";
						 }else if(assigingstatus == 0){
						 	$elem.attr('class', 'updatepermission');
							$elem.val("1");
							assigingstatus = "";
						 }              			
					}
					$elem.prop('disabled', false);
					
					$('html,body').animate({
						scrollTop: $(".ajax_message").offset().top - 300
					}, 50);
				}
			});
		});
	});
</script>