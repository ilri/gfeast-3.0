<link href="<?php echo base_url() ?>include/vendors/select2/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
	.select2-container .select2-search--inline .select2-search__field { margin-top: 0; width: auto !important; }
	.select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #D4D9F8 !important; }
</style>

<!-- Partner Centre Combo Modal -->
<div class="modal fade" id="manageCentre" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Manage centre-partner details</h4>
			</div>
			
			<?php echo form_open('', array('id' => 'centreForm')) ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="centre_name">Centre Name</label>
						<div class="form-control" id="centre_name"></div>
					</div>

					<div class="form-group">
						<label for="partners">Select Partners</label> <span class="text-danger">*</span>
						<select name="partners[]" id="partners" multiple="multiple"></select>
						<span class="partners error text-danger"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info">Update Centre-Partner Details</button>
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
														<td class="date"><?php echo $centre['added_datetime']; ?></td>
														<td><a href="javascript:void(0);" class="btn btn-primary btn-sm manage" data-id="<?php echo $centre['centre_id']; ?>">Manage Partners</a></td>
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

<!-- Select2 -->
<script src="<?php echo base_url() ?>include/vendors/select2/select2.full.min.js"></script>

<!-- Page Script -->
<script type="text/javascript">
	$(function() {
		$('#partners').select2({
			placeholder: 'Select Partners...'
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
	
	// Handle centre manage btn click
	$('body').on('click', '.manage', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#centreForm')[0].reset();
		$('#manageCentre').modal('show');
		$('#manageCentre').find('#partners').val(null).empty();
		$('#manageCentre').find('button').prop('disabled', true);
		$('#manageCentre').find('button[type="submit"]').html('Getting Centre Details...');

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
						$('#manageCentre').modal('hide');
						$('#manageCentre').find('button').prop('disabled', false);
						$('#manageCentre').find('button[type="submit"]').html('Update Centre-Partner Details');
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
							$('#manageCentre').modal('hide');
							$('#manageCentre').find('button').prop('disabled', false);
							$('#manageCentre').find('button[type="submit"]').html('Update Centre-Partner Details');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#manageCentre').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#manageCentre').modal('hide');
							$('#manageCentre').find('button').prop('disabled', false);
							$('#manageCentre').find('button[type="submit"]').html('Update Centre-Partner Details');
						}
					});
					return false;
				}
				
				$('#manageCentre').find('button').prop('disabled', false);
				$('#manageCentre').find('button[type="submit"]').html('Update Centre-Partner Details');
				
				$('#centreForm').data('centre_id', elem.data('id'));
				$('#manageCentre').find('#centre_name').html(data.details.centre_name);

				var HTML = ``;
				for(var partner of data.partners) {
					HTML += `<option value="${partner.partner_id}">${partner.partner_name}</option>`;
				}
				$('#manageCentre').find('#partners').html(HTML).val(null);
				if(data.details.partners.length > 0) {
					$('#manageCentre').find('#partners').val(data.details.partners);
				}
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
		formData = new FormData($(this)[0]);
		formData.append('centre_id', elem.data('centre_id'));
		$.ajax({
			url: '<?php echo base_url(); ?>centre/manage_partner_combo/',
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
				$('button[type="submit"]').html('Update Centre-Partner Details');
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
					$('button[type="submit"]').html('Update Centre-Partner Details');
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
					$('button[type="submit"]').html('Update Centre-Partner Details');
				}
				
				if(data.updatestatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>centre/partner_combo';
						}
					});
				} else if(data.updatestatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Centre-Partner Details');
				}
			}
		});
	});
</script>