<style type="text/css">
	.vertical-layout { margin-top: 10px; }
</style>

<!-- location Modal -->
<div class="modal fade" id="locationModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Location Details</h4>
				<button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body table-responsive">
				<table class="table">
					<thead>
						<th>#</th>
						<th>Country</th>
						<th>State</th>
						<th>District</th>
						<th>Block</th>
						<th>Village</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Details Project Modal -->
<div class="modal fade" id="detailsProject" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Project Details</h4>
				<button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
			</div>

			<?php echo form_open('', array('id'=> 'projectForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="project_name">Project Name</label>
						<input type="text" id="project_name" placeholder="Name of project" class="form-control" name="project_name">
					</div>
					<div class="form-group">
						<label for="project_description">Project Description</label>
						<textarea id="project_description" placeholder="Description of project" class="form-control" name="project_description"></textarea>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

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
												<!-- <th>Total Agencies</th> -->
												<!-- <th>Total Users</th> -->
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
														<!-- <td><?php echo $project['partners']; ?></td> -->
														<!-- <td><?php echo $project['users']; ?></td> -->
														<!-- <td class="date"><?php echo $project['proj_reg_date']; ?></td>
														<td>
															<a href="javascript:void(0);" class="btn btn-info btn-sm details" data-id="<?php echo $project['proj_id']; ?>">View Details</a>
															<a href="javascript:void(0);" class="btn btn-info btn-sm locations" data-id="<?php echo $project['proj_id']; ?>">View Locations</a>
														</td>
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

<!-- TinyMCE -->
<script src="<?php echo base_url(); ?>include/vendors/tinymce/tinymce.min.js"></script>

<!-- Page Script -->
<script type="text/javascript">
	$(function() {
		var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
		var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
		tinymce.init({
			selector: 'textarea#project_description',
			toolbar: 'false',
			menubar: 'false'
		});
		
		$('body').find('td.date').each(function(index) {
			var elem = $(this),
			serverDate = moment.utc(elem.html()),
			formattedDate = serverDate.local().format('MMM Do, YYYY hh:mmA');
			elem.html(formattedDate);
		});

		// Handle project details btn click
		$('body').on('click', '.details', function(event) {
			var elem = $(this);
			$('.error').empty();
			$('#projectForm')[0].reset();
			$('#detailsProject').modal('show');
			var data = {
				project_id: elem.data('id')
			}
			data[csrfName] = csrfHash;
			$.ajax({
				url: '<?php echo base_url(); ?>projects/project_details/',
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
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error',
						afterHidden: function () {
							$('#detailsProject').modal('hide');
						}
					});
				},
				success: function(data) {
					if(data.status == 0) {
						$('#detailsProject').modal('hide');
						$.toast({
							heading: 'Error!',
							text: data.msg,
							icon: 'error',
							afterHidden: function () {
								$('#detailsProject').modal('hide');
							}
						});
						return false;
					}
					
					$('#projectForm').data('project_id', elem.data('id'));
					$('#detailsProject').find('#project_name').val(data.details.project_name);
					if(data.details.project_description) {
						tinymce.activeEditor.setContent(data.details.project_description);
					}
				}
			});
		});

		// Handle project locations btn click
		$('body').on('click', '.locations', function(event) {
			var elem = $(this);
			$('.error').empty();
			$('#locationModal').modal('show');
			$('#locationModal').find('tbody').html('<tr><td colspan="4">Please Wait... Getting Location Details.</td></tr>');
			var data = {
				project_id: elem.data('id')
			}
			data[csrfName] = csrfHash;
			$.ajax({
				url: '<?php echo base_url(); ?>projects/project_locations/',
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
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error',
						afterHidden: function () {
							$('#locationModal').modal('hide');
						}
					});
				},
				success: function(data) {
					if(data.status == 0) {
						$('#locationModal').modal('hide');
						$.toast({
							heading: 'Error!',
							text: data.msg,
							icon: 'error',
							afterHidden: function () {
								$('#locationModal').modal('hide');
							}
						});
						return false;
					}

					if(data.status == 2) {
						$('#locationModal').modal('hide');
						$.toast({
							heading: 'Info',
							text: data.msg,
							icon: 'info',
							afterHidden: function () {
								$('#locationModal').modal('hide');
							}
						});
						return false;
					}

					var HTML = ``;
					for(var key in data.locations) {
						var loc = data.locations[key];
						HTML += `<tr>
							<td>${parseInt(key)+1}</td>
							<td>${loc.country}</td>
							<td>${loc.state}</td>
							<td>${loc.dist}</td>
							<td>${loc.block}</td>
							<td>${loc.village}</td>
						</tr>`;
					}
					$('#locationModal').find('tbody').html(HTML);
				}
			});
		});
	});
</script>