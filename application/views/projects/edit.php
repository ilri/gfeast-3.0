<style type="text/css">
	.vertical-layout { margin-top: 10px; }
</style>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProject" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update project details</h4>
			</div>

			<?php echo form_open('', array('id'=> 'projectForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="project_name">Project Name</label> <span class="text-danger">*</span>
						<input type="text" id="project_name" placeholder="Name of project" class="form-control" name="project_name">
						<span class="project_name error text-danger"></span>
					</div>
					<div class="form-group">
						<label for="project_description">Project Description</label> <span class="text-danger">*</span>
						<textarea id="project_description" placeholder="Description of project" class="form-control" name="project_description"></textarea>
						<span class="project_description error text-danger"></span>
					</div>
				</div>
				<div class="modal-footer">
					<span class="form_msg error text-danger"></span>
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-info">Update Project</button>
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
														<!-- <td><?php echo $project['partners']; ?></td> -->
														<!-- <td><?php echo $project['users']; ?></td> -->
														<!-- <td class="date"><?php echo $project['proj_reg_date']; ?></td>
														<td><a href="javascript:void(0);" class="btn btn-success btn-sm edit" data-id="<?php echo $project['proj_id']; ?>">Edit</a></td>
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
	$(function(){
		tinymce.init({
			selector: 'textarea#project_description',
			toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent'
		});

		$('body').find('td.date').each(function(index) {
			var elem = $(this),
			serverDate = moment.utc(elem.html()),
			formattedDate = serverDate.local().format('MMM Do, YYYY hh:mmA');
			elem.html(formattedDate);
		});
	});
	var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
	var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
	// Handle project edit btn click
	$('body').on('click', '.edit', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#projectForm')[0].reset();
		$('#editProject').modal('show');
		$('#editProject').find('button').prop('disabled', true);
		$('#editProject').find('button[type="submit"]').html('Getting Project Details...');
		var data = {
			project_id: elem.data('id')
		}
		data[csrfName] = csrfHash;
		$.ajax({
			url: '<?php echo base_url(); ?>projects/project_details/',
			data: data,
			type: 'POST',
			dataType: 'json',
			error: function() {
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error',
					afterHidden: function () {
						$('#editProject').modal('hide');
						$('#editProject').find('button').prop('disabled', false);
						$('#editProject').find('button[type="submit"]').html('Update Project');
					}
				});
			},
			complete: function(data) {
                var csrfData = JSON.parse(data.responseText);
                if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                    $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
                }
                csrfName = csrfData.csrfName;
                csrfHash = csrfData.csrfHash;
            },
			success: function(data) {
				// If session error exists
				if(data.session_err == 1) {
					$.toast({
						heading: 'Session Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#editProject').modal('hide');
							$('#editProject').find('button').prop('disabled', false);
							$('#editProject').find('button[type="submit"]').html('Update Project');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#editProject').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#editProject').modal('hide');
							$('#editProject').find('button').prop('disabled', false);
							$('#editProject').find('button[type="submit"]').html('Update Project');
						}
					});
					return false;
				}
				
				$('#editProject').find('button').prop('disabled', false);
				$('#editProject').find('button[type="submit"]').html('Update Project');
				
				$('#projectForm').data('project_id', elem.data('id'));
				$('#editProject').find('#project_name').val(data.details.project_name);
				if(data.details.project_description) {
					tinymce.activeEditor.setContent(data.details.project_description);
				}
			}
		});
	});

	// Handle project form submit
	$('#projectForm').on('submit', function(event) {
		event.preventDefault();
		var elem = $(this);
		$('.error').empty();
		$('button').prop('disabled', true);
		$('button[type="submit"]').html('Please wait...');

		var form = $(this),
		project_description = tinymce.activeEditor.getContent();
		$('input[type="text"]', form).each(function(index) {
			var elem = $(this);
			elem.val($.trim(elem.val()));
		});

		var formData = new FormData($(this)[0]);
		formData.append('project_id', elem.data('project_id'));
		formData.append('description', $.trim(project_description));
		$.ajax({
			url: '<?php echo base_url(); ?>projects/edit_project/',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			complete: function(data) {
            var csrfData = JSON.parse(data.responseText);
                if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                    $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
                }
                csrfName = csrfData.csrfName;
                csrfHash = csrfData.csrfHash;
            },
			error: function() {
				$('button').prop('disabled', false);
				$('button[type="submit"]').html('Update Project');
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success: function(data) {
				var data = JSON.parse(data);

				// If session error exists
				if(data.session_err == 1) {
					$.toast({
						heading: 'Session Error!',
						text: data.msg,
						icon: 'error'
					});

					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Project');
				}

				// If validation error exists
				if(data.status > 0) {
					for(var key in data) {
						var errorContainer = form.find(`.${key}.error`);
						if(errorContainer.length !== 0) {
							errorContainer.html(data[key]);
						}
					}
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Project');
				}

				if(data.updatestatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>projects/edit';
						}
					});
				} else if(data.updatestatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Project');
				}
			}
		});
	});
</script>