<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
</style>

<!-- location Modal -->
<div class="modal fade" id="locationModal" role="dialog">
	<div class="modal-dialog modal-md">
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
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Details Centre Modal -->
<div class="modal fade" id="detailsCentre" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Centre Details</h4>
				<button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
			</div>
			
			<?php echo form_open('', array('id' => 'centreForm')) ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="centre_name">Centre Name</label>
						<input type="text" id="centre_name" placeholder="Name of centre" class="form-control" name="centre_name">
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
														<td>
															<a href="javascript:void(0);" class="btn btn-info btn-sm details" data-id="<?php echo $centre['centre_id']; ?>">View Details</a>
															<a href="javascript:void(0);" class="btn btn-info btn-sm locations" data-id="<?php echo $centre['centre_id']; ?>">View Locations</a>
														</td>
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

	// Handle centre details btn click
	$('body').on('click', '.details', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#centreForm')[0].reset();
		$('#detailsCentre').modal('show');

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
						$('#detailsCentre').modal('hide');
					}
				});
			},
			success: function(data) {
				if(data.status == 0) {
					$('#detailsCentre').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#detailsCentre').modal('hide');
						}
					});
					return false;
				}
				
				$('#centreForm').data('centre_id', elem.data('id'));
				$('#detailsCentre').find('#centre_name').val(data.details.centre_name);
			}
		});
	});

	// Handle centre locations btn click
	$('body').on('click', '.locations', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#locationModal').modal('show');
		$('#locationModal').find('tbody').html('<tr><td colspan="4">Please Wait... Getting Location Details.</td></tr>');

		ajaxData['centre_id'] = elem.data('id');
		$.ajax({
			url: '<?php echo base_url(); ?>centre/centre_locations/',
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
					</tr>`;
				}
				$('#locationModal').find('tbody').html(HTML);
			}
		});
	});
</script>