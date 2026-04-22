<style type="text/css">
	.vertical-layout { margin-top: 10px; }
</style>

<div class="app-content content ml-0">
	<div class="content-wrapper">
		<div class="content-body mt-10">
			<div class="row">
				<div class="col-md-12 mt-10">
					<h4 class="bold">All Projects</h4>
					<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
				</div>
				<div class="col-md-12 mt-10">
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th>#</th>
												<th>Project Name</th>
												<th>Project Description</th>
												<!-- <th>Total Agencies</th>
												<th>Total Users</th> -->
												<th>Added Date</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<td>
												<h1>Coming soon...</h1>
											</td>
											<!-- <?php if(count($all_projets) > 0){
												foreach ($all_projets as $key => $project) { ?>
													<tr>
														<td><?php echo $key+1; ?></td>
														<td><?php echo $project['proj_name']; ?></td>
														<td><?php echo $project['proj_description']; ?></td> -->
														<!-- <td><?php echo $project['partners']; ?></td>
														<td><?php echo $project['users']; ?></td> -->
														<!-- <td class="date"><?php echo $project['proj_reg_date']; ?></td>
														<td><a href="javascript:void(0);" class="btn btn-danger btn-sm delete" data-id="<?php echo $project['proj_id']; ?>">Delete</a></td>
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="6">No project found.</td>
												</tr>
											<?php } ?> -->
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

<!-- Page Script -->
<script type="text/javascript">
	$(function(){
		$('body').find('td.date').each(function(index) {
			var elem = $(this),
			serverDate = moment.utc(elem.html()),
			formattedDate = serverDate.local().format('MMM Do, YYYY hh:mmA');
			elem.html(formattedDate);
		});
	});
	
	var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

	// Handle project delete btn click
	$('body').on('click', '.delete', function(event) {
		var elem = $(this);

		swal({
			title: "Are you sure?",
			text: "All the partners and surveys linked to this project will be freed. You will not be able to revert this!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, delete it!"
		}, function() {
			elem.prop('disabled', true);
			elem.html('Please Wait.... Deleting Project.');
			deleteProject(elem);
		});
	});

	function deleteProject(elem) {
		var data = {
			project_id: elem.data('id')
		}
		data[csrfName] = csrfHash;
		$.ajax({
			url: '<?php echo base_url(); ?>projects/delete_project/',
			data: data,
			type: 'POST',
			dataType: 'json',
			complete: function(data) {
                var csrfData = JSON.parse(data.responseText);
                if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                    $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
                }
                csrfName = csrfData.csrfName;
                csrfHash = csrfData.csrfHash;
            },
			error: function() {
				$.toast({
					heading: 'Network Error!',
					text: 'Could not connect to server!',
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
</script>