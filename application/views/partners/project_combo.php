<link href="<?php echo base_url() ?>include/vendors/select2/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
	.select2-container .select2-search--inline .select2-search__field { margin-top: 0; width: auto !important; }
	.select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #D4D9F8 !important; }
</style>

<!-- Partner Centre Combo Modal -->
<div class="modal fade" id="managePartner" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Manage partner-project details</h4>
			</div>
			
			<?php echo form_open('', array('id'=>'partnerForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="partner_name">Partner Name</label>
						<div class="form-control" id="partner_name"></div>
					</div>

					<div class="form-group">
						<label for="projects">Select Projects</label> <span class="text-danger">*</span>
						<select name="projects[]" id="projects" multiple="multiple"></select>
						<span class="projects error text-danger"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info">Update Partner-Project Details</button>
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
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
					<h4 class="bold">All Partners</h4>
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
												<th>Partner Name</th>
												<th>Total Projects</th>
												<th>Added Date</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($all_partners) > 0){
												foreach ($all_partners as $key => $partner) { ?>
													<tr>
														<td><?php echo $key+1; ?></td>
														<td><?php echo $partner['partner_name']; ?></td>
														<td><?php echo $partner['projects']; ?></td>
														<td class="date"><?php echo $partner['added_datetime']; ?></td>
														<td><a href="javascript:void(0);" class="btn btn-primary btn-sm manage" data-id="<?php echo $partner['partner_id']; ?>">Manage Projects</a></td>
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="5">No partner found.</td>
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

<!-- Select2 -->
<script src="<?php echo base_url() ?>include/vendors/select2/select2.full.min.js"></script>

<!-- Page Script -->
<script type="text/javascript">
	$(function() {
		$('#projects').select2({
			placeholder: 'Select Projects...'
		});

		$('body').find('td.date').each(function(index) {
			var elem = $(this),
			serverDate = moment.utc(elem.html()),
			formattedDate = serverDate.local().format('MMM Do, YYYY hh:mmA');
			elem.html(formattedDate);
		});
	});

	// Define global variable ajaxData
	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
	
	// Handle partner manage btn click
	$('body').on('click', '.manage', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#partnerForm')[0].reset();
		$('#managePartner').modal('show');
		$('#managePartner').find('#projects').val(null).empty();
		$('#managePartner').find('button').prop('disabled', true);
		$('#managePartner').find('button[type="submit"]').html('Getting Centre Details...');

		ajaxData['partner_id'] = elem.data('id');
		$.ajax({
			url: '<?php echo base_url(); ?>partners/partner_details/',
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
					icon: 'error',
					afterHidden: function () {
						$('#managePartner').modal('hide');
						$('#managePartner').find('button').prop('disabled', false);
						$('#managePartner').find('button[type="submit"]').html('Update Partner-Project Details');
					}
				});
			},
			success: function(data) {
				// If session error exists
				if(data.session_err == 1) {
					$.toast({
						heading: 'Session Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#managePartner').modal('hide');
							$('#managePartner').find('button').prop('disabled', false);
							$('#managePartner').find('button[type="submit"]').html('Update Partner-Project Details');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#managePartner').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#managePartner').modal('hide');
							$('#managePartner').find('button').prop('disabled', false);
							$('#managePartner').find('button[type="submit"]').html('Update Partner-Project Details');
						}
					});
					return false;
				}
				
				$('#managePartner').find('button').prop('disabled', false);
				$('#managePartner').find('button[type="submit"]').html('Update Partner-Project Details');
				
				$('#partnerForm').data('partner_id', elem.data('id'));
				$('#managePartner').find('#partner_name').html(data.details.partner_name);

				var HTML = ``;
				for(var project of data.projects) {
					HTML += `<option value="${project.project_id}">${project.project_name}</option>`;
				}
				$('#managePartner').find('#projects').html(HTML).val(null);
				if(data.details.projects.length > 0) {
					$('#managePartner').find('#projects').val(data.details.projects);
				}
			}
		});
	});

	// Handle partner form submit
	$('#partnerForm').on('submit', function(event) {
		event.preventDefault();
		var elem = $(this);
		$('.error').empty();
		$('button').prop('disabled', true);
		$('button[type="submit"]').html('Please wait...');
		
		var form = $(this),
		formData = new FormData($(this)[0]);
		formData.append('partner_id', elem.data('partner_id'));
		$.ajax({
			url: '<?php echo base_url(); ?>partners/manage_project_combo/',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			complete: function(data) {
				var csrfData = JSON.parse(data.responseText);
				ajaxData[csrfData.csrfName] = csrfData.csrfHash;
				if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
					$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
				}
			},
			error: function() {
				$('button').prop('disabled', false);
				$('button[type="submit"]').html('Update Partner-Project Details');
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
					$('button[type="submit"]').html('Update Partner-Project Details');
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
					$('button[type="submit"]').html('Update Partner-Project Details');
				}
				
				if(data.updatestatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>partners/project_combo';
						}
					});
				} else if(data.updatestatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Partner-Project Details');
				}
			}
		});
	});
</script>