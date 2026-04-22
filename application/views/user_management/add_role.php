<style>
  .vertical-layout{
    margin-top: 10px;
   }
</style>


<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: : 30px;"><!-- Form wizard with number tabs section start -->
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
            <div class="card">
              <div class="card-header">
                <!-- <?php if($main_menu['permission_list'] != ''){ ?>
                  <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">   
                    <button class="btn btn-info round dropdown-toggle dropdown-menu-right box-shadow-2 px-2" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <?php foreach ($main_menu['permission_list'] as $key => $value) { ?>
                        <a class="dropdown-item" href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $value['module_key']; ?>">
                          <?php echo $value['name']; ?>
                        </a>
                      <?php } ?>
                    </div>
                  </div>
                <?php } ?> -->
                <h4 class="card-title">Role creation</h4>
              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                  <form class="number-tab-steps wizard-circle" id="add_role">
                    <fieldset>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="firstName1">Role Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="role_name">
                            <p class="rolename_error error" style="color: red;"></p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="lastName1">Display Name<span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="role_description" >
                            <p class="roledescription_error error" style="color: red;"></p>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Copy capabilities from<span style="color:red;">*</span></label>
                            <select class="form-control" name="copy_role">
                              <option value="">Select role</option>
                              <?php foreach ($roles_list as $key => $value) { ?>
                                <option value="<?php echo $value['role_id'] ?>"><?php echo $value['role_name']; ?></option>
                              <?php } ?>
                            </select>
                            <p class="copy_role_error error" style="color: red;"></p>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-success add_role" style="float: right;">Add role</button>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $('.add_role').on('click', function(){

       $('.ajax_message').removeAttr('style');

      $('.add_role').prop('disabled',true);

      $('.error').html('');
      var error_count = 0;

      var textregex = new RegExp("^[a-zA-Z ]*$");

      var copy_role = $('select[name="copy_role"]').val();
      var role_name = $('input[name="role_name"]').val();
      var role_description = $('input[name="role_description"]').val();

      if($.trim(copy_role).length == 0){
        $('.copy_role_error').html('Copy capabilities field is mandatory');
        error_count++;
      }

      if($.trim(role_name).length == 0){
        $('.rolename_error').html('Role name is mandatory');
        error_count++;
      }else if($.trim(role_name).length < 2){
        $('.rolename_error').html('Minimum 2 characters required in role name');
        error_count++;
      }else if (!textregex.test($.trim(role_name))) {
        $('.rolename_error').html('Role name can contain only alphabets');
        error_count++;
      }

      if($.trim(role_description).length == 0){
        $('.roledescription_error').html('Display Name is mandatory');
        error_count++;
      }else if($.trim(role_description).length < 2){
        $('.roledescription_error').html('Minimum 2 characters required in display name');
        error_count++;
      }else if (!textregex.test($.trim(role_description))) {
        $('.roledescription_error').html('display name can contain only alphabets');
        error_count++;
      }
      if(error_count == 0){
        $.ajax({
          url: '<?php echo base_url(); ?>user_management/insert_role',
          type: 'POST',
          dataType : 'json',
          data: {
            role_name : role_name,
            role_description : role_description,
            copy_role : copy_role
          },
          error: function() {
            $('.ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success: function(response){
            if(response.status == 0){
              $('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>').delay(2000).fadeOut();
              $('.add_role').prop('disabled',false);

            }else{
              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>').delay(2000).fadeOut();
              $("#add_role").trigger("reset");
              $('.add_role').prop('disabled',false);
            }

            $('html,body').animate({
              scrollTop: $(".ajax_message").offset().top - 300
            }, 500);
          }
        });
      }
      else
      {
        $('.add_role').prop('disabled',false);

      }
    });
  });
</script>

