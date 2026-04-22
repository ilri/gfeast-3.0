<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>include/vendors/intlTelInput/build/css/intlTelInput.css">
<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
	/*Intl-tel-input CSS*/
	.iti { width: 100%; }
	.iti__flag {background-image: url("<?php echo base_url(); ?>include/vendors/intlTelInput/build/img/flags.png");}

	@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
		.iti__flag {background-image: url("<?php echo base_url(); ?>include/vendors/intlTelInput/build/img/flags@2x.png");}
	}
</style>

<!-- Edit Partner Modal -->
<div class="modal fade" id="editPartner" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Update partner details</h4>
			</div>
			
			<?php echo form_open('', array('id'=>'partnerForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="partner_name">Partner Name</label> <span class="text-danger">*</span>
						<input type="text" id="partner_name" placeholder="Name of partner" class="form-control" name="partner_name">
						<span class="partner_name error text-danger"></span>
					</div>
					<div class="form-group">
						<label for="partner_email">Partner Email</label> <span class="text-danger">*</span>
						<input type="text" id="partner_email" placeholder="Email of partner" class="form-control" name="partner_email">
						<span class="partner_email error text-danger"></span>
					</div>
					<div class="form-group">
						<label for="partner_business">Nature of business</label>
						<input type="text" id="partner_business" placeholder="Nature of business" class="form-control" name="partner_business">
						<span class="partner_business error text-danger"></span>
					</div>
					<div class="form-group">
						<label for="partner_address">Partner Address</label>
						<textarea id="partner_address" placeholder="Address" class="form-control" name="partner_address" rows="2" style="resize:vertical;"></textarea>
						<span class="partner_address error text-danger"></span>
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
							<span class="partner_country error text-danger"></span>
						</div>
						<div class="col-sm-6 form-group">
							<label for="partner_zip">Postal Code</label>
							<input type="text" id="partner_zip" placeholder="Postal Code" class="form-control" name="partner_zip">
							<span class="partner_zip error text-danger"></span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 form-group">
							<label for="partner_fax">Fax</label>
							<input type="text" id="partner_fax" placeholder="Fax" class="form-control" name="partner_fax">
							<span class="partner_fax error text-danger"></span>
						</div>
						<div class="col-sm-6 form-group">
							<label for="partner_phone">Telephone</label>
							<input type="tel" id="partner_phone" placeholder="Telephone" class="form-control" name="partner_phone">
							<span class="partner_phone error text-danger"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-info">Update Partner</button>
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
														<td><a href="javascript:void(0);" class="btn btn-success btn-sm edit" data-id="<?php echo $partner['partner_id']; ?>">Edit</a></td>
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

<!-- intlTelInput -->
<script src="<?php echo base_url(); ?>include/vendors/intlTelInput/build/js/intlTelInput.min.js"></script>

<!-- Page Script -->
<script type="text/javascript">
	$(function(){
		var countryIds = [],
		countries = <?php echo json_encode($all_countries); ?>;
		for(var country of countries) { countryIds.push(country.code); }
		
		// Initialize IntlTelInput
		// $('[type="tel"]').intlTelInput({
		// 	utilsScript: "<?php echo base_url(); ?>include/vendors/intlTelInput/build/js/utils.js",
		// 	initialCountry: 'IN',
		// 	onlyCountries: countryIds
		// });

		$('body').find('td.date').each(function(index) {
			var elem = $(this),
			serverDate = moment.utc(elem.html()),
			formattedDate = serverDate.local().format('MMM Do, YYYY hh:mmA');
			elem.html(formattedDate);
		});
	});

	// Define global variable ajaxData
	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

	// Handle partner edit btn click
	$('body').on('click', '.edit', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#partnerForm')[0].reset();
		$('#editPartner').modal('show');
		$('#editPartner').find('button').prop('disabled', true);
		$('#editPartner').find('button[type="submit"]').html('Getting Partner Details...');

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
						$('#editPartner').modal('hide');
						$('#editPartner').find('button').prop('disabled', false);
						$('#editPartner').find('button[type="submit"]').html('Update Partner');
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
							$('#editPartner').modal('hide');
							$('#editPartner').find('button').prop('disabled', false);
							$('#editPartner').find('button[type="submit"]').html('Update Partner');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#editPartner').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#editPartner').modal('hide');
							$('#editPartner').find('button').prop('disabled', false);
							$('#editPartner').find('button[type="submit"]').html('Update Partner');
						}
					});
					return false;
				}
				
				$('#editPartner').find('button').prop('disabled', false);
				$('#editPartner').find('button[type="submit"]').html('Update Partner');
				
				$('#partnerForm').data('partner_id', elem.data('id'));
				$('#editPartner').find('#partner_name').val(data.details.partner_name);
				$('#editPartner').find('#partner_email').val(data.details.partner_email);
				$('#editPartner').find('#partner_business').val(data.details.nature_of_business);
				$('#editPartner').find('#partner_address').val(data.details.address);
				$('#editPartner').find('#partner_zip').val(data.details.postcode);
				$('#editPartner').find('#partner_country').val(data.details.country).trigger('change');
				$('#editPartner').find('#partner_fax').val(data.details.fax);
				$('#editPartner').find('#partner_phone').val(data.details.telephone);
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

		var form = $(this);
		$('input[type="text"], input[type="tel"], textarea', form).each(function(index) {
			var elem = $(this);
			elem.val($.trim(elem.val()));
		});
		
		var formData = new FormData($(this)[0]);
		formData.append('partner_id', elem.data('partner_id'));
		$.ajax({
			url: '<?php echo base_url(); ?>partners/edit_partner/',
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
				$('button[type="submit"]').html('Update Partner');
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
					$('button[type="submit"]').html('Update Partner');
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
					$('button[type="submit"]').html('Update Partner');
				}

				if(data.updatestatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>partners/edit';
						}
					});
				} else if(data.updatestatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Update Partner');
				}
			}
		});
	});
</script>