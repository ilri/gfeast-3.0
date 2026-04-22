<style type="text/css">
	.error { color: red; }
	#editRole { top: 20%; }
	.vertical-layout { margin-top: 10px; }
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>
<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-top: 10px;">
			<!-- Modal for edit roles -->
			<div id="editRole" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Edit Role</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<?php echo form_open('', array('id' => 'editRoleForm', 'class' => 'row form-group')); ?>
								<div class="col-md-12">
									<div class="ajax_message"></div>
								</div>
								<div class="col-md-12">
									<label>Role name<span style="color:red;">*</span></label>
									<input type="text" name="role_name" class="form-control">
									<span class="error rolename_error"></span>
								</div>
								<div class="col-md-12 mt-20">
									<label>Role description<span style="color:red;">*</span></label>
									<textarea class="form-control" name="description" id="role_description"></textarea>
									<span class="error roledescription_error"></span>
								</div>

								<div class="col-md-12 mt-20">
									<button type="submit" class="btn btn-primary btn-primary pull-right">Update</button>
								</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<h4 style="font-weight: bold;">Edit role</h4>
				</div>

				<div class="col-md-12 mt-20">
					<div class="card p-20">
						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th>S.no</th>
									<th>Role name</th>
									<th>Role Description</th>
									<th>Added date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if(count($roles) > 0){
									foreach ($roles as $rkey => $role) { ?>
										<tr>
											<th><?php echo $rkey+1; ?></th>
											<td><?php echo $role['role_name']; ?></td>
											<td><?php echo $role['role_description']; ?></td>
											<td><?php echo $role['added_date']; ?></td>
											<td><a href="javascript:void(0);" data-toggle="tooltip" title="Edit role" class="edit" data-role_id="<?php echo $role['role_id']; ?>" data-role_name="<?php echo $role['role_name']; ?>" data-role_description="<?php echo $role['role_description']; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a></td>
										</tr>
									<?php }
								}else{ ?>
									<tr>
										<td colspan="5">No Roles have been found from your account.</td>
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

<script type="text/javascript">
	$('.edit').on('click', function(){
		$elem = $(this);
		//get details of roles
		var role_id = $elem.data('role_id');
		var role_name = $elem.data('role_name');
		var role_description = $elem.data('role_description');
		//set role id as data attribute in edit role form
		$('#editRoleForm').data('role_id', role_id);
		//set data to fields
		$('#editRole').find('[name="role_name"]').val(role_name);
		$('#editRole').find('#role_description').val(role_description);
		$('#editRole').modal('show');
	});

	// Define global variable ajaxData
    var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

	//on submit of edit role form
	$('#editRoleForm').on('submit', function(event){
		event.preventDefault();
		//remove error messages
		$('.error').empty();
		$('.ajax_response').css('display', 'block');
		$elem = $(this);
		var role_id = $elem.data('role_id');
		var role_name = $elem.find('[name="role_name"]').val();
		var role_description = $elem.find('#role_description').val();
		var error_count = 0;
		//regex to validate
		var textregex = new RegExp("^[a-zA-Z ]*$");
		//validate role name
		if($.trim(role_name).length == 0){
			$('.rolename_error').html('Role name is mandatory');
			error_count++;
		} else if($.trim(role_name).length < 2){
			$('.rolename_error').html('Minimum 2 characters required in role name');
			error_count++;
		} else if (!textregex.test($.trim(role_name))) {
			$('.rolename_error').html('Role name can contain only alphabets');
			error_count++;
		}
		//validate role
		if($.trim(role_description).length == 0){
			$('.roledescription_error').html('Role Description is mandatory');
			error_count++;
		} else if($.trim(role_description).length < 2){
			$('.roledescription_error').html('Minimum 2 characters required in role description');
			error_count++;
		} else if (!textregex.test($.trim(role_description))) {
			$('.roledescription_error').html('Role description can contain only alphabets');
			error_count++;
		}

		if(error_count == 0){
			ajaxData['role_id'] = role_id;
			ajaxData['role_name'] = role_name;
			ajaxData['role_description'] = role_description;
			$.ajax({
				url: '<?php echo base_url(); ?>users/update_role',
				type: 'POST',
				dataType:'json',
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
					if(response.status == 1){
						$('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
						window.setTimeout(function(){location.reload()},2000);
					}
				}
			});
		}
	})
	$(".close").on("click",function(){
		$('.roledescription_error').html('');
		$('.rolename_error').html('');
		$("form")[0].reset();
	});
</script>