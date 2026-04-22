<style>
	.vertical-layout{
		margin-top: 10px;
	}
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 30px;">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-header">
								<h2 class="card-title">Delete User</h2>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Sl.no</th>
												<th>First name</th>
												<th>Last name</th>
												<th>Email id</th>
												<th>Username</th>
												<th>Role</th>
												<th>Created</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($users) > 0){
												foreach ($users as $ukey => $user) { ?>
													<tr data-user_id="<?php echo $user['user_id']; ?>">
														<td><?php echo ($ukey+1); ?></td>
														<td><?php echo $user['first_name']; ?></td>
														<td><?php echo $user['last_name']; ?></td>
														<td><?php echo $user['email_id']; ?></td>
														<td><?php echo $user['username']; ?></td>
														<td><?php echo $user['role_name']; ?></td>
														<td><?php echo $user['added_datetime']; ?></td>
														<td>
															<?php if ($user['role_id'] != 1): ?>
																<a href="javascript:void(0);" class="delete" style="color:red;">
																	<i class="fa fa-trash" aria-hidden="true"></i> Delete
																</a>
															<?php endif; ?>
														</td>
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="7">No users found</td>
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
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('body').on('click', '.delete', function(){
			var elem = $(this);
			swal({
				title: "Are you sure?",
				text: "The User will be deleted !",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, delete it!"
			}, function() {
				elem.prop('disabled', true);
				elem.html('Please Wait.... Deleting Project.');
				deleteUser(elem);
			});
		});

		// Define global variable ajaxData
    	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

		function deleteUser(elem){
			ajaxData['user_id'] = elem.closest('tr').data('user_id');
			ajaxData['status'] = 0;
			$.ajax({
				url: '<?php echo base_url(); ?>users/delete_user/',
				data: ajaxData,
				type: 'POST',
				dataType: 'json',
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function() {
					$.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error'
					});
					elem.prop('disabled', true);
					elem.html('Delete');
				},
				success: function(data) {
					if(data.status == 0) {
						$.toast({
							heading: 'Error!',
							text: data.msg,
							icon: 'error'
						});
						elem.prop('disabled', true);
						elem.html('Delete');
						return false;
					}
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success'
					});
					elem.closest('tr').remove();
					if($('.table').find('tbody').html().trim().length === 0) {
						$('.table').find('tbody').html(`<tr>
							<td colspan="7">No project found. It seems you have deleted all the projects.</td>
							</tr>`);
					}
				}
			});
		}
	})
</script>
