<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
</style>

<!-- Edit Centre Modal -->
<div class="modal fade" id="editCentre" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update centre details</h4>
			</div>
			
			<?php echo form_open('', array('id' => 'centreForm')) ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="centre_name">Centre Name</label> <span class="text-danger">*</span>
						<input type="text" id="centre_name" placeholder="Name of centre" class="form-control" name="centre_name">
						<span class="centre_name error text-danger"></span>
					</div>

					<div class="row locationDiv"></div>
				</div>
				<div class="modal-footer" style="display:block;">
					<button type="button" class="btn btn-primary addMoreLocation">Add More Location</button>
					<button type="submit" class="btn btn-info pull-right">Update Centre</button>
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
					<h4 class="bold">All Centre</h4>
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
												<th>Centre Name</th>
												<th>Total Partners</th>
												<th>Total Users</th>
												<th>Added Date</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($all_centre) > 0){
												foreach ($all_centre as $key => $centre) { ?>
													<tr>
														<td><?php echo $key+1; ?></td>
														<td><?php echo $centre['centre_name']; ?></td>
														<td><?php echo $centre['partners']; ?></td>
														<td><?php echo $centre['users']; ?></td>
														<td class="date"><?php echo $centre['added_datetime']; ?></td>
														<td><a href="javascript:void(0);" class="btn btn-success btn-sm edit" data-id="<?php echo $centre['centre_id']; ?>">Edit</a></td>
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="6">No centre found.</td>
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

<!-- Page Script -->
<script type="text/javascript">
	$(function() {
		$('body').find('td.date').each(function(index) {
			var elem = $(this),
			serverDate = moment.utc(elem.html()),
			formattedDate = serverDate.local().format('MMM Do, YYYY hh:mmA');
			elem.html(formattedDate);
		});
	});

	// Define global variable ajaxData
	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
	
	// Handle centre edit btn click
	$('body').on('click', '.edit', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#centreForm')[0].reset();
		$('#editCentre').modal('show');
		$('#editCentre').find('button').prop('disabled', true);
		$('#editCentre').find('button[type="submit"]').html('Getting Centre Details...');

		ajaxData['centre_id'] = elem.data('id');
		$.ajax({
			url: '<?php echo base_url(); ?>centre/centre_details/',
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
						$('#editCentre').modal('hide');
						$('#editCentre').find('button').prop('disabled', false);
						$('#editCentre').find('button[type="submit"]').html('Update Centre');
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
							$('#editCentre').modal('hide');
							$('#editCentre').find('button').prop('disabled', false);
							$('#editCentre').find('button[type="submit"]').html('Update Centre');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#editCentre').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#editCentre').modal('hide');
							$('#editCentre').find('button').prop('disabled', false);
							$('#editCentre').find('button[type="submit"]').html('Update Centre');
						}
					});
					return false;
				}
				
				$('#editCentre').find('button').prop('disabled', false);
				$('#editCentre').find('button[type="submit"]').html('Update Centre');
				
				$('#centreForm').data('centre_id', elem.data('id'));
				$('#editCentre').find('#centre_name').val(data.details.centre_name);

				var locationHTML = ``;
				for(var key in data.details.locations) {
					var selected = '',
					loc = data.details.locations[key],
					baseClass = (key == 0) ? 'row locationDiv' : 'row';

					locationHTML += `<div class="${baseClass}">
						<div class="col-sm-4 form-group">
							<label>Country</label> <span class="text-danger">*</span>
							<select class="form-control" name="country[]">
								<option value="">-- Select Country --</option>`;
								for(var country of loc.countries) {
								selected = (country.country_id == loc.country) ? 'selected' : '';
								locationHTML += `<option value="${country.country_id}" ${selected}>
									${country.name} ${(country.code ? '('+country.code+')' : '')}
								</option>`;
								}
							locationHTML += `</select>
							<span class="country error text-danger"></span>
						</div>
						<div class="col-sm-4 form-group">
							<label>State</label> <span class="text-danger">*</span>
							<select class="form-control" name="state[]">
								<option value="">-- Select State --</option>`;
								for(var state of loc.states) {
								selected = (state.state_id == loc.state) ? 'selected' : '';
								locationHTML += `<option value="${state.state_id}" ${selected}>
									${state.state_name}
								</option>`;
								}
							locationHTML += `</select>
							<span class="state error text-danger"></span>
						</div>
						<div class="col-sm-4 form-group">
							<label>District</label> <span class="text-danger">*</span>
							<select class="form-control" name="dist[]">
								<option value="">-- Select District --</option>`;
								for(var dist of loc.dists) {
								selected = (dist.district_id == loc.dist) ? 'selected' : '';
								locationHTML += `<option value="${dist.district_id}" ${selected}>
									${dist.district_name}
								</option>`;
								}
							locationHTML += `</select>
							<span class="dist error text-danger"></span>
						</div>`;
						if(key > 0) {
						locationHTML += `<div class="col-sm-12 form-group">
							<button type="button" class="btn btn-sm btn-danger pull-right removeLocation">Remove Location</button>
						</div>`;
						}
					locationHTML += `</div>`;
				}
				$('#editCentre').find('.locationDiv').empty();
				$('#editCentre').find('.row:not(.locationDiv)').remove();
				$('#editCentre').find('.locationDiv').replaceWith(locationHTML);
			}
		});
	});

	// Handle addMoreLocation and removeLocation btn click
	$('body').on('click', '.addMoreLocation', function(event) {
		var elem = $(this),
		clonedDiv = elem.parent().prev('.modal-body').find('.locationDiv').clone();

		$('.modal-body').append(clonedDiv);
		clonedDiv.removeClass('locationDiv');
		clonedDiv.append(`<div class="col-sm-12 form-group">
			<button type="button" class="btn btn-sm btn-danger pull-right removeLocation">Remove Location</button>
		</div>`);
		clonedDiv.find('select[name="country[]"]').val('').trigger('change');
	}).on('click', '.removeLocation', function(event) {
		var elem = $(this);
		elem.closest('.row').remove();
	});

	// Handle country and state change
	$('body').on('change', 'select[name="country[]"]', function(event) {
		var elem = $(this),
		HTML = `<option value="">-- Select State --</option>`;
		
		row = elem.closest('.row');
		row.find('.error').empty();
		row.find('[name="state[]"]').html(HTML);
		row.find('[name="state[]"]').val('').trigger('change');
		if(elem.val().length === 0) return false;

		ajaxData['country'] = elem.val();
		$.ajax({
			url: '<?php echo base_url(); ?>helper/all_states/',
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
			error: function() {},
			success: function(data) {
				for(var state of data.states) {
					HTML += `<option value="${state.state_id}">${state.state_name}</option>`;
				}
				row.find('[name="state[]"]').html(HTML);
			}
		});
	}).on('change', 'select[name="state[]"]', function(event) {
		var elem = $(this),
		HTML = `<option value="">-- Select District --</option>`;
		
		row = elem.closest('.row');
		row.find('.error').empty();
		row.find('[name="dist[]"]').html(HTML);
		row.find('[name="dist[]"]').val('');
		if(elem.val().length === 0) return false;

		ajaxData['state'] = elem.val();
		$.ajax({
			url: '<?php echo base_url(); ?>helper/all_dists/',
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
			error: function() {},
			success: function(data) {
				for(var dist of data.dists) {
					HTML += `<option value="${dist.district_id}">${dist.district_name}</option>`;
				}
				row.find('[name="dist[]"]').html(HTML);
			}
		});
	});

	// Handle centre form submit
	$('#centreForm').on('submit', function(event) {
		event.preventDefault();
		var elem = $(this);
		$('.error').empty();
		$('button').prop('disabled', true);
		$('button[type="submit"]').html('Please wait...');

		var form = $(this),
		error = validateLocation(form);
		if(error > 0) {
			$('button').prop('disabled', false);
			$('button[type="submit"]').html('Register New Centre');
			return false;
		}
		$('input[type="text"]', form).each(function(index) {
			var elem = $(this);
			elem.val($.trim(elem.val()));
		});
		
		var formData = new FormData($(this)[0]);
		formData.append('centre_id', elem.data('centre_id'));
		$.ajax({
			url: '<?php echo base_url(); ?>centre/edit_centre/',
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
				$('button[type="submit"]').html('Update Centre');
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
					$('button[type="submit"]').html('Update Centre');
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
					$('button[type="submit"]').html('Update Centre');
				}
				
				if(data.updatestatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>centre/edit';
						}
					});
				} else if(data.updatestatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Centre');
				}
			}
		});
	});

	function validateLocation(form) {
		var error = 0,
		allSelectedDists = [];
		// Country Validation
		$('select[name="country[]"]', form).each(function(index) {
			var elem = $(this),
			label = elem.closest('.form-group').find('label').html(),
			errorContainer = elem.closest('.form-group').find('.error');

			if(elem.val().length === 0) {
				error++;
				errorContainer.html(`${label} is mandatory.`);
			}
		});

		// State Validation
		$('select[name="state[]"]', form).each(function(index) {
			var elem = $(this),
			label = elem.closest('.form-group').find('label').html(),
			errorContainer = elem.closest('.form-group').find('.error');

			if(elem.val().length === 0) {
				error++;
				errorContainer.html(`${label} is mandatory.`);
			}
		});

		// District Validation
		$('select[name="dist[]"]', form).each(function(index) {
			var elem = $(this),
			label = elem.closest('.form-group').find('label').html(),
			errorContainer = elem.closest('.form-group').find('.error');

			if(elem.val().length === 0) {
				error++;
				errorContainer.html(`${label} is mandatory.`);
			} else {
				if(allSelectedDists.includes(elem.val())) {
					error++;
					errorContainer.html(`${label} should not be repeated.`);
				} else allSelectedDists.push(elem.val());
			}
		});
		return error;
	}
</script>