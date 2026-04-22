<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
</style>

<!-- New Centre Modal -->
<div class="modal fade" id="newCentre" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Create a new centre</h4>
			</div>
			
			<?php echo form_open('', array('id' => 'centreForm')) ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="centre_name">Centre Name</label> <span class="text-danger">*</span>
						<input type="text" id="centre_name" placeholder="Name of centre" class="form-control" name="centre_name">
						<span class="centre_name error text-danger"></span>
					</div>

					<div class="row locationDiv">
						<div class="col-sm-4 form-group">
							<label>Country</label> <span class="text-danger">*</span>
							<select class="form-control" name="country[]">
								<option value="">-- Select Country --</option>
								<?php foreach ($all_countries as $key => $country) { ?>
								<option value="<?php echo $country['country_id'] ?>">
									<?php echo $country['name']; echo !is_null($country['code']) ? ' ('.$country['code'].')' : ''; ?>
								</option>
								<?php } ?>
							</select>
							<span class="country error text-danger"></span>
						</div>
						<div class="col-sm-4 form-group">
							<label>State</label> <span class="text-danger">*</span>
							<select class="form-control" name="state[]">
								<option value="">-- Select State --</option>
							</select>
							<span class="state error text-danger"></span>
						</div>
						<div class="col-sm-4 form-group">
							<label>District</label> <span class="text-danger">*</span>
							<select class="form-control" name="dist[]">
								<option value="">-- Select District --</option>
							</select>
							<span class="dist error text-danger"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer" style="display:block;">
					<button type="button" class="btn btn-primary addMoreLocation">Add More Location</button>
					<button type="submit" class="btn btn-info pull-right">Register New Centre</button>
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
					<button type="button" class="btn btn-success round float-md-right" data-toggle="modal" data-target="#newCentre"><i class="ft-plus"></i> New Centre</button>
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
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="5">No centre found.</td>
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

<!-- intlTelInput -->
<script src="<?php echo base_url(); ?>include/vendors/intlTelInput/build/js/intlTelInput.min.js"></script>

<!-- Page Script -->
<script type="text/javascript">
	$(function(){
		var countryIds = [],
		countries = <?php echo json_encode($all_countries); ?>;
		for(var country of countries) { countryIds.push(country.code); }
		
		// Initialize IntlTelInput
		$('[type="tel"]').intlTelInput({
			utilsScript: "<?php echo base_url(); ?>include/vendors/intlTelInput/build/js/utils.js",
			initialCountry: 'IN',
			onlyCountries: countryIds
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

	// Handle modal shown
	$('#newCentre').on('show.bs.modal', function(e) {
		$('#newCentre').find('.row:not(.locationDiv)').remove();
		$('#newCentre').find('[name="state[]"]').html('<option value="">-- Select State --</option>');
		$('#newCentre').find('[name="dist[]"]').html('<option value="">-- Select District --</option>');
		$('#newCentre').find('form')[0].reset();
		$('#newCentre').find('.error').empty();
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
		$.ajax({
			url: '<?php echo base_url(); ?>centre/add_centre/',
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
				$('button[type="submit"]').html('Register New Centre');
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
					$('button[type="submit"]').html('Register New Centre');
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
					$('button[type="submit"]').html('Register New Centre');
				}
				
				// If insert completed
				if(data.insertstatus == 1) {
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>centre/create';
						}
					});
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