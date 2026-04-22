<style>
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
			<div class="row">
				<div class="col-md-12">
					<h4 style="font-weight: bold;">Delete role</h4>
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
											<td>
												<a href="javascript:void(0);" data-toggle="tooltip" title="Delete role" class="delete" data-role_id="<?php echo $role['role_id']; ?>" data-role_name="<?php echo $role['role_name']; ?>" data-role_description="<?php echo $role['role_description']; ?>" style="color: red;">
													<i class="fa fa-trash" aria-hidden="true"></i> Delete
												</a>
											</td>
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
	// Define global variable ajaxData
    var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
	
	$('.delete').on('click', function(){
		$elem = $(this);
		//get details of roles
		var role_id = $elem.data('role_id');
		//use sweetalert for confirmation
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this role",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			cancelButtonText: "No, cancel",
			confirmButtonText: "Yes, delete it",
			closeOnConfirm: false,
			closeOnCancel: true
		},
		function(isConfirm) {
			if (isConfirm) {
				ajaxData['role_id'] = role_id;
				$.ajax({
					url: '<?php echo base_url(); ?>users/remove_role',
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
						swal("Network Error!", "Could not establish connection to server. Please refresh the page and try again.", "error");
					},
					success: function(response){
						if(response.status == 1){
							$elem.closest('tr').remove();
							swal("Deleted!", "Your role has been deleted.", "success");
						} else if(response.status == 2){
							swal("Warning!", "This role could not be deleted, role is assigned to some user !", "warning");
						} else {
							swal("Warning!", "Something went wrong, please try again later!", "warning");
						}
					}
				});
			}
		});
	});
</script>