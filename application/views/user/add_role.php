<style>
	.vertical-layout{ margin-top: 10px; }
	label {
    font-weight: bold;
    color: #800000 !important;
}
</style>


<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: : 30px;"><!-- Form wizard with number tabs section start -->
			<div class="row">
				<div class="col-12 ajax_message"></div>
				<div class="col-md-12">
					<h4 style="font-weight: bold;">Create role</h4>
				</div>
				<div class="col-12">
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<?php echo form_open('', array('id' => 'add_role', 'class' => 'number-tab-steps wizard-circle')); ?>
									<fieldset>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="firstName1">Role Name<span style="color:red;">*</span></label>
													<input type="text" class="form-control" name="role_name">
													<p class="rolename_error error" style="color: red;"></p>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="lastName1">Display Name<span style="color:red;">*</span></label>
													<input type="text" class="form-control" name="role_description" >
													<p class="roledescription_error error" style="color: red;"></p>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label>Copy capabilities from<span style="color:red;">*</span></label>
													<select class="form-control" name="copy_role">
														<option value="">Select role</option>
														<?php foreach ($roles_list as $key => $value) { ?>
															<option value="<?php echo $value['role_id'] ?>"><?php echo $value['role_name']; ?></option>
														<?php } ?>
													</select>
													<p class="copy_role_error error" style="color: red;"></p>
												</div>
											</div>
										</div>
									</fieldset>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
					<button type="button" class="btn btn-success add_role" style="float: right;">Add role</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		// Define global variable ajaxData
		var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
		
		$('.add_role').on('click', function(){

			$('.ajax_message').removeAttr('style');

			$('.add_role').prop('disabled',true);

			$('.error').html('');
			var error_count = 0;

			var textregex = new RegExp("^[a-zA-Z ]*$");

			var copy_role = $('select[name="copy_role"]').val();
			var role_name = $('input[name="role_name"]').val();
			var role_description = $('input[name="role_description"]').val();

			if($.trim(copy_role).length == 0){
				$('.copy_role_error').html('Copy capabilities field is mandatory');
				error_count++;
			}

			if($.trim(role_name).length == 0){
				$('.rolename_error').html('Role name is mandatory');
				error_count++;
			}else if($.trim(role_name).length < 2){
				$('.rolename_error').html('Minimum 2 characters required in role name');
				error_count++;
			}else if (!textregex.test($.trim(role_name))) {
				$('.rolename_error').html('Role name can contain only alphabets');
				error_count++;
			}

			if($.trim(role_description).length == 0){
				$('.roledescription_error').html('Display Name is mandatory');
				error_count++;
			}else if($.trim(role_description).length < 2){
				$('.roledescription_error').html('Minimum 2 characters required in display name');
				error_count++;
			}else if (!textregex.test($.trim(role_description))) {
				$('.roledescription_error').html('display name can contain only alphabets');
				error_count++;
			}
			if(error_count == 0){
				ajaxData['role_name'] = role_name;
				ajaxData['role_description'] = role_description;
				ajaxData['copy_role'] = copy_role;
				$.ajax({
					url: '<?php echo base_url(); ?>users/insert_role',
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
						$('.ajax_message').html('<div class="alert alert-danger">Could not establish connection to server. Please refresh the page and try again.</div>');
					},
					success: function(response){
						if(response.status == 0){
							$('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>').delay(2000).fadeOut();
							$('.add_role').prop('disabled',false);

						}else{
							$('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>').delay(2000).fadeOut();
							$("#add_role").trigger("reset");
							$('.add_role').prop('disabled',false);
						}

						$('html,body').animate({
							scrollTop: $(".ajax_message").offset().top - 300
						}, 500);
					}
				});
			} else {
				$('.add_role').prop('disabled',false);
			}
		});
	});
</script>