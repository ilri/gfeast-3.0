
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
  .iti { width: 100%; }
</style>

<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			<div class="card-header">
				<a style="float: right;" href="<?php echo base_url(); ?>">Back to Login</a>
				<h3>User Registration</h3>
			</div>
			<div class="card-body">
				<?php echo form_open('', array('id' => 'userForm', 'autocomplete' => 'off')); ?>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="first_name">First Name<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="first_name" id="first_name">
							<p class="first_name error" style="color: red;"></p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="last_name">Last Name<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="last_name" id="last_name">
							<p class="last_name error" style="color: red;"></p>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="email">Email Address<span style="color:red;">*</span></label>
							<input type="email" class="form-control" name="email" id="email">
							<p class="email error" style="color: red;"></p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="username">Username<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="username" id="username">
							<p class="username error" style="color: red;"></p>
						</div>
					</div>
					<!-- <div class="col-md-4">
						<div class="form-group">
							<label for="phone">Phone</label>
							<input type="tel" class="form-control" id="phone">
							<input type="hidden" name="phone" id="phone_full">
							<p class="phone error" style="color: red;"></p>
						</div>
					</div> -->
					<div class="col-md-6">
						<div class="form-group">
							<label for="password">Password<span style="color:red;">*</span></label>
							<input type="password" class="form-control" name="password" id="password" autocomplete="off">
							<p class="password error" style="color: red;"></p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="cpassword">Confirm Password<span style="color:red;">*</span></label>
							<input type="password" class="form-control" name="cpassword" id="cpassword" autocomplete="off">
							<p class="cpassword error" style="color: red;"></p>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-success float-right mt-30">Register</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<!-- /Main content -->

<!-- Page Script -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script type="text/javascript">
	// Init intl-tel-input
	// var phoneInput = document.querySelector('#phone');
	// var iti = window.intlTelInput(phoneInput, {
	// 	initialCountry: 'in',
	// 	separateDialCode: true,
	// 	utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js'
	// });

	// Handle field value change
	$('.form-control').on('change', function(event) {
		var elem = $(this);
		if(elem.val().length > 0) elem.removeClass('form-control-danger');
	});

	// Handle user form submit
	$('#userForm').on('submit', function(event) {
		event.preventDefault();
		var elem = $(this);
		$('.error').empty();
		$('button').prop('disabled', true);
		$('button[type="submit"]').html('Please wait...');

		// Validate phone with intl-tel-input
		// var phoneVal = phoneInput.value.trim();
		// if(phoneVal != '' && !iti.isValidNumber()) {
		// 	$('.phone.error').html('Please enter a valid phone number for the selected country.');
		// 	$('#phone').addClass('form-control-danger');
		// 	$('button').prop('disabled', false);
		// 	$('button[type="submit"]').html('Register');
		// 	return;
		// }

		// Populate hidden fields with full number and country code
		//$('#phone_full').val(iti.getNumber()); // e.g. +919876543210

		var form = $(this)
		formData = new FormData($(this)[0]);
		$.ajax({
			url: '<?php echo base_url(); ?>userregistration/insert_user/',
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
				$('button[type="submit"]').html('Register');
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
					$('button[type="submit"]').html('Register');
				}

				// If validation error exists
				if(data.status > 0) {
					for(var key in data) {
						var errorContainer = form.find(`.${key}.error`),
						errorInput = form.find(`[name="${key}"].form-control`);
						
						if(errorContainer.length !== 0) {
							errorContainer.html(data[key]);
						}
						if(errorInput.length !== 0) {
							errorInput.addClass('form-control-danger');
						}
					}
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Register');
				}

				if(data.insertstatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>users/create';
						}
					});
				} else if(data.insertstatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Register');
				}
			}
		});
	});
</script>