<style>
  .vertical-layout{
    margin-top: 10px;
   }
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>

<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 40px;"><!-- Form wizard with number tabs section start -->
      <section id="number-tabs">
        <div class="row" >
          <div class="col-md-12" style="margin-bottom: 30px; margin-top: -30px;">
            <img src="<?php echo base_url(); ?>includeout/images/banner.jpg" style="width: 100%;">
          </div>
        </div>
        <div class="row">
          <div class="col-12 ajax_message">
            
          </div>
          <div class="col-12">
            <h4 class="title">User creation</h4>
            <div class="card">
              <div class="card-content collapse show">
                <div class="card-body">
                  <form class="number-tab-steps wizard-circle" id="add_user">
                    <fieldset>
                      <div class="row">
                        <div class="form-group col-md-12">
                          <label for="eventType1">Select Role<span style="color:red;">*</span></label>
                          <select class="form-control" name="user_role">
                            <option value="">Select role</option>
                            <?php foreach ($roles_list as $key => $value) { ?>
                              <option value="<?php echo $value['role_id'] ?>"><?php echo $value['role_name']; ?></option>
                            <?php } ?>
                          </select>
                          <p class="user_role_error error" style="color: red;"></p>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="firstName1">First Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="first_name">
                            <p class="first_name_error error" style="color: red;"></p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="lastName1">Last Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="last_name" >
                            <p class="last_name_error error" style="color: red;"></p>
                          </div>
                        </div>
                     
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="emailAddress1">Email Address<span style="color:red;">*</span></label>
                              <input type="email" class="form-control" name="email" >
                              <p class="email_error error" style="color: red;"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="eventName1">User Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="username" >
                            <p class="username_error error" style="color: red;"></p>
                          </div>
                        </div>                                             
                        <div class="col-md-6">
                          <div class="form-group">
                              <label for="eventName1">Password<span style="color:red;">*</span></label>
                              <input type="password" class="form-control" name="password" >
                              <p class="password_error error" style="color: red;"></p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                              <label for="eventName1">Confirm Password<span style="color:red;">*</span></label>
                              <input type="password" class="form-control" name="cpassword" >
                              <p class="cpassword_error error" style="color: red;"></p>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-success add_user" style="float: right;">Add user</button>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>


<script type="text/javascript">
  
  $(function() {
    $('.add_user').on('click', function(){

      $('.ajax_message').removeAttr('style');


      $('.add_user').prop('disabled',true);


      $('.error').html('');
      var error_count = 0;

      var textregex = new RegExp("^[a-zA-Z ]*$");
      var passwordregex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@$!%*?&]){8,}");

      var user_role = $('select[name="user_role"]').val();
      var first_name = $('input[name="first_name"]').val();
      var last_name = $('input[name="last_name"]').val();
      var email = $('input[name="email"]').val();
      var username = $('input[name="username"]').val();
      var password = $('input[name="password"]').val();
      var cpassword = $('input[name="cpassword"]').val();

      if($.trim(user_role).length == 0){
        $('.user_role_error').html('Role is mandatory');
        error_count++;
      }

      if($.trim(first_name).length == 0){
        $('.first_name_error').html('First name is mandatory');
        error_count++;
      }else if($.trim(first_name).length < 2){
        $('.first_name_error').html('Minimum 2 characters required in first name');
        error_count++;
      }else if (!textregex.test($.trim(first_name))) {
        $('.first_name_error').html('First name can contain only alphabets');
        error_count++;
      }

      if($.trim(last_name).length == 0){
        console.log(last_name);
        $('.last_name_error').html('Last name is mandatory');
        error_count++;
      }else if($.trim(last_name).length < 2){
        $('.last_name_error').html('Minimum 2 characters required in last name');
        error_count++;
      }else if (!textregex.test($.trim(last_name))) {
        $('.last_name_error').html('Last name can contain only alphabets');
        error_count++;
      }

      if($.trim(email).length == 0){
        $('.email_error').html('Email id name is mandatory');
        error_count++;
      }else if(!isValidEmailAddress(email)) {
        $('.email_error').html('Please provide a valid emailid.');
        error_count++;
      }

      if($.trim(username).length == 0){
        $('.username_error').html('Username is mandatory');
        error_count++;
      }else if($.trim(username).length < 2){
        $('.username_error').html('Minimum 2 characters required in username');
        error_count++;
      }
      if($.trim(password).length == 0){
        $('.password_error').html('Password is mandatory');
        error_count++;
      }else if (!passwordregex.test($.trim(password))) {
        $('.password_error').html('Password should contain at least one digit, at least one lower case, at least one upper case,at least one special character, at least 8 from the mentioned characters');
        error_count++;
      }

      if($.trim(cpassword).length == 0){
        $('.cpassword_error').html('Confirm password is mandatory');
        error_count++;
      }else if (!passwordregex.test($.trim(cpassword))) {
        $('.cpassword_error').html('Password should contain at least one digit, at least one lower case, at least one upper case,at least one special character, at least 8 from the mentioned characters');
        error_count++;
      }

      if(password != cpassword){
        $('.cpassword_error').html('Both password and confirm password should be same');
        error_count++;
      }
      if(error_count == 0){
        $.ajax({
          url: '<?php echo base_url(); ?>user_management/insert_user',
          type: 'POST',
          dataType : 'json',
          data: {
            user_role : user_role,
            first_name : first_name,
            last_name : last_name,
            email : email,
            username : username,
            password : password,
            cpassword : cpassword
          },
          error: function() {
            $('.ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success: function(response){
            if(response.status == 0){
              $('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>').delay(1000).fadeOut();
              $('.add_user').prop('disabled',false);

            }else{
              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>').delay(1000).fadeOut();
              $("#add_user").trigger("reset");
              $('.add_user').prop('disabled',false);

            }

            $('html,body').animate({
              scrollTop: $(".ajax_message").offset().top - 300
            }, 500);
          }
        });
      }else{
            $('.add_user').prop('disabled',false);}
    });
  });

  function isValidEmailAddress(emailAddress) {
        var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test(emailAddress);
    }
</script>
