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

<!-- Details Partner Modal -->
<div class="modal fade" id="detailsPartner" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Partner Details</h4>
				<button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
			</div>

			<?php echo form_open('', array('id'=>'partnerForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="partner_name">Partner Name</label>
						<input type="text" id="partner_name" placeholder="Name of partner" class="form-control" name="partner_name">
					</div>
					<div class="form-group">
						<label for="partner_email">Partner Email</label>
						<input type="email" id="partner_email" placeholder="Email of partner" class="form-control" name="partner_email">
					</div>
					<div class="form-group">
						<label for="partner_business">Nature of business</label>
						<input type="text" id="partner_business" placeholder="Nature of business" class="form-control" name="partner_business">
					</div>
					<div class="form-group">
						<label for="partner_address">Partner Address</label>
						<textarea id="partner_address" placeholder="Address" class="form-control" name="partner_address" rows="2" style="resize:vertical;"></textarea>
					</div>
					<div class="row">
						<div class="col-sm-6 form-group">
							<label for="partner_country">Country</label>
							<select id="partner_country" class="form-control" name="partner_country">
								<option value="">-- Select Country --</option>
								<?php foreach ($all_countries as $key => $country) { ?>
									<option value="<?php echo $country['country_id'] ?>">
										<?php echo $country['name']; echo !is_null($country['code']) ? ' ('.$country['code'].')' : ''; ?>
									</option>
								<?php } ?>
							</select>
						</div>
						<div class="col-sm-6 form-group">
							<label for="partner_zip">Postal Code</label>
							<input type="text" id="partner_zip" placeholder="Postal Code" class="form-control" name="partner_zip">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 form-group">
							<label for="partner_fax">Fax</label>
							<input type="text" id="partner_fax" placeholder="Fax" class="form-control" name="partner_fax">
						</div>
						<div class="col-sm-6 form-group">
							<label for="partner_phone">Telephone</label>
							<input type="tel" id="partner_phone" placeholder="Telephone" class="form-control" name="partner_phone">
						</div>
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
														<td>
															<a href="javascript:void(0);" class="btn btn-info btn-sm details" data-id="<?php echo $partner['partner_id']; ?>">View Details</a>
															<a href="javascript:void(0);" class="btn btn-info btn-sm locations" data-id="<?php echo $partner['partner_id']; ?>">View Locations</a>
														</td>
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

	// Define global variable ajaxData
	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

	// Handle partner details btn click
	$('body').on('click', '.details', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#partnerForm')[0].reset();
		$('#detailsPartner').modal('show');

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
						$('#detailsPartner').modal('hide');
					}
				});
			},
			success: function(data) {
				if(data.status == 0) {
					$('#detailsPartner').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#detailsPartner').modal('hide');
						}
					});
					return false;
				}
				
				$('#partnerForm').data('partner_id', elem.data('id'));
				$('#detailsPartner').find('#partner_name').val(data.details.partner_name);
				$('#detailsPartner').find('#partner_email').val(data.details.partner_email);
				$('#detailsPartner').find('#partner_business').val(data.details.nature_of_business);
				$('#detailsPartner').find('#partner_address').val(data.details.address);
				$('#detailsPartner').find('#partner_zip').val(data.details.postcode);
				$('#detailsPartner').find('#partner_country').val(data.details.country).trigger('change');
				$('#detailsPartner').find('#partner_fax').val(data.details.fax);
				$('#detailsPartner').find('#partner_phone').val(data.details.telephone);
			}
		});
	});

	// Handle partner locations btn click
	$('body').on('click', '.locations', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#locationModal').modal('show');
		$('#locationModal').find('tbody').html('<tr><td colspan="4">Please Wait... Getting Location Details.</td></tr>');

		ajaxData['partner_id'] = elem.data('id');
		$.ajax({
			url: '<?php echo base_url(); ?>partners/partner_locations/',
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