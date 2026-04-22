<div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
  <div class="page-content vertical-align-middle">
    <div class="row" style="margin-top: 120px;">
      <div class="col-md-2"></div>
      <div class="col-md-3"></div>
      <div class="col-md-3" style="margin-left: -50px;">
        <h2 style="font-weight: bold;">Forgot Your Password?</h2>

        <!-- Step 1: Email verification -->
        <div id="step-email">
          <p>Enter your registered email to receive a password reset link.</p>
          <?php echo form_open('', array('class' => 'form-horizontal', 'id' => 'emailForm')); ?>
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="text" class="form-control empty" id="inputEmail" name="email" placeholder="Your Email Id">
              <span class="error email text-danger"></span>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block" id="verifyBtn">Send Reset Link</button>
            </div>
            <p style="margin-top: 10px;">Remember your password? <a href="<?php echo base_url(); ?>">Login here</a></p>
          <?php echo form_close(); ?>
        </div>

        <!-- Success message (hidden until email sent) -->
        <div id="step-success" style="display:none;">
          <div class="alert alert-success" id="success-msg"></div>
          <p style="margin-top: 10px;"><a href="<?php echo base_url(); ?>">Back to Login</a></p>
        </div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  // Submit email to send reset link
  $('#emailForm').on('submit', function(e) {
    e.preventDefault();
    $('.error').empty();
    $('#verifyBtn').prop('disabled', true).html('Please wait...');

    $.ajax({
      url: '<?php echo base_url(); ?>userregistration/verify_email/',
      type: 'POST',
      data: { email: $('#inputEmail').val(), '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
      dataType: 'json',
      success: function(data) {
        if (data.csrfHash) {
          $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(data.csrfHash);
        }

        if (data.status === 'error') {
          $('.error.email').html(data.msg);
          $('#verifyBtn').prop('disabled', false).html('Send Reset Link');
        } else {
          // Show success message
          $('#step-email').hide();
          $('#success-msg').html(data.msg);
          $('#step-success').show();
        }
      },
      error: function() {
        $('#verifyBtn').prop('disabled', false).html('Send Reset Link');
		$.toast({
			heading: 'Error!',
			text: 'Network error. Please try again.',
			icon: 'error'
		});
      }
    });
  });
</script>
