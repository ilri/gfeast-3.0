<link href="<?php echo base_url() ?>include/vendors/select2/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
	.select2-container .select2-search--inline .select2-search__field { margin-top: 0; width: auto !important; }
	.select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #D4D9F8 !important; }
</style>

<!-- New Batch Modal -->
<div class="modal fade" id="newBatch" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Create a new batch</h4>
			</div>
			
			<?php echo form_open('', array('id' => 'batchForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="batch_name">Batch Name</label> <span class="text-danger">*</span>
						<input type="text" id="batch_name" placeholder="Name of batch" class="form-control" name="batch_name">
						<span class="batch_name error text-danger"></span>
					</div>

					<div class="form-group">
						<label for="centre">Select A Centre</label> <span class="text-danger">*</span>
						<select name="centre" id="centre"></select>
						<span class="centre error text-danger"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info">Register New Batch</button>
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
					<button type="button" class="btn btn-success round float-md-right" data-toggle="modal" data-target="#newBatch"><i class="ft-plus"></i> New Batch</button>
					<h4 class="bold">All Batches</h4>
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
												<th>Batch Name</th>
												<th>Centre Name</th>
												<th>Total Partners</th>
												<th>Total Users</th>
												<th>Added Date</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($all_batch) > 0){
												foreach ($all_batch as $key => $batch) { ?>
													<tr>
														<td><?php echo $key+1; ?></td>
														<td><?php echo $batch['batch_name']; ?></td>
														<td><?php echo $batch['centre_name']; ?></td>
														<td><?php echo $batch['partners']; ?></td>
														<td><?php echo $batch['users']; ?></td>
														<td class="date"><?php echo $batch['added_datetime']; ?></td>
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="6">No batch found.</td>
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
	$(function(){
		$('#centre').select2({
			placeholder: 'Select A Centre...'
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
	$('#newBatch').on('show.bs.modal', function(e) {
		var elem = $(this);
		$('.error').empty();
		$('#batchForm')[0].reset();
		$('#newBatch').find('#centre').val(null).empty();
		$('#newBatch').find('button').prop('disabled', true);
		$('#newBatch').find('button[type="submit"]').html('Getting Centres...');

		$.ajax({
			url: '<?php echo base_url(); ?>centre/all_centre/',
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
						$('#newBatch').modal('hide');
						$('#newBatch').find('button').prop('disabled', false);
						$('#newBatch').find('button[type="submit"]').html('Register New Batch');
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
							$('#newBatch').modal('hide');
							$('#newBatch').find('button').prop('disabled', false);
							$('#newBatch').find('button[type="submit"]').html('Register New Batch');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#newBatch').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#newBatch').modal('hide');
							$('#newBatch').find('button').prop('disabled', false);
							$('#newBatch').find('button[type="submit"]').html('Register New Batch');
						}
					});
					return false;
				}
				
				$('#newBatch').find('button').prop('disabled', false);
				$('#newBatch').find('button[type="submit"]').html('Register New Batch');

				var HTML = ``;
				for(var centre of data.all_centre) {
					HTML += `<option value="${centre.centre_id}">${centre.centre_name}</option>`;
				}
				$('#newBatch').find('#centre').html(HTML).val(null);
			}
		});
	});

	// Handle batch form submit
	$('#batchForm').on('submit', function(event) {
		event.preventDefault();
		$('.error').empty();
		$('button').prop('disabled', true);
		$('button[type="submit"]').html('Please wait...');

		var form = $(this);
		$('input[type="text"]', form).each(function(index) {
			var elem = $(this);
			elem.val($.trim(elem.val()));
		});
		
		var formData = new FormData($(this)[0]);
		$.ajax({
			url: '<?php echo base_url(); ?>centre/add_batch/',
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
				$('button[type="submit"]').html('Register New Batch');
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
					$('button[type="submit"]').html('Register New Batch');
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
					$('button[type="submit"]').html('Register New Batch');
				}
				
				if(data.insertstatus == 1) {
					// If insert completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>centre/batch';
						}
					});
				} else if(data.insertstatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Register New Batch');
				}
			}
		});
	});
</script>