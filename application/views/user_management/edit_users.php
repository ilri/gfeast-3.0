<style type="text/css">
  #editUser{
    top: 10%;
  }

  .error {
    color: red;
  }
   .vertical-layout{
    margin-top: 10px;
   }
</style>
<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-top: 10px;">

       <div class="row" >
          <div class="col-md-12" style="margin-bottom: 30px; margin-top: -30px;">
            <img src="<?php echo base_url(); ?>includeout/images/banner.jpg" style="width: 100%;">
          </div>
        </div>
      <!-- Modal for edit roles -->
      <div id="editUser" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit User</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="row form-group" id="editUserForm">
                <div class="col-md-12">
                  <div class="ajax_message"></div>
                </div>
                <!--fname -->
                <div class="col-md-12">
                  <label>First name<span style="color:red;">*</span></label>
                  <input type="text" name="fname" class="form-control">
                  <span class="error firstname_error"></span>
                </div>

                <!-- lname -->
                <div class="col-md-12 mt-20">
                  <label>Last name<span style="color:red;">*</span></label>
                  <input type="text" name="lname" class="form-control">
                  <span class="error lastname_error"></span>
                </div>

                <!-- username -->
                <div class="col-md-12 mt-20">
                  <label>Username<span style="color:red;">*</span></label>
                  <input type="text" name="username" class="form-control">
                  <span class="error username_error"></span>
                </div>

                <!-- username -->
                <div class="col-md-12 mt-20">
                  <label>Email<span style="color:red;">*</span></label>
                  <input type="text" name="email" class="form-control">
                  <span class="error email_error"></span>
                </div>

                <!-- role -->
                <div class="col-md-12 mt-20">
                  <label>Role<span style="color:red;">*</span></label>
                  <select class="form-control" name="role"></select>
                  <span class="error role_error"></span>
                </div>

                <div class="col-md-12 mt-20">
                  <button type="submit" class="btn btn-primary btn-primary pull-right">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

  		<div class="row">
  			<div class="col-md-12">
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
  				<h4 style="font-weight: bold;">Edit users</h4>
          <div class="ajax_response mt-20"></div>
  			</div>

        <div class="col-md-12">
          <div class="card p-20">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>S.no</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Added date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($all_users) > 0){
                  foreach ($all_users as $ukey => $user) { ?>
                    <tr>
                      <th><?php echo $ukey+1; ?></th>
                      <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
                      <td><?php echo $user['username']; ?></td>
                      <td><?php echo $user['email_id']; ?></td>
                      <td><?php echo $user['role_name']; ?></td>
                      <td><?php echo $user['added_datetime']; ?></td>
                      <td><a href="javascript:void(0);" data-toggle="tooltip" title="Edit User" class="edit" data-user_id="<?php echo $user['user_id']; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a></td>
                    </tr>
                  <?php }
                }else{ ?>
                   <tr>
                    <td colspan="7">No Users have been found from your account.</td>
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

<script type="text/javascript">
  function isValidEmailAddress(emailAddress) {
      var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return pattern.test(emailAddress);
  }

  $('.edit').on('click', function(){
    $('.error').html('');
    $(".error").removeAttr("style")

    var user_id = $(this).data('user_id');
    //get all data of user
    $.ajax({
      url: '<?php echo base_url(); ?>user_management/get_user_data',
      type: 'POST',
      dataType:'json',
      data: {
        user_id:user_id
      },
      error: function() {
        console.log('No response...');
      },
      success: function(response){
        $('#editUser').data('user_id', user_id);
        $('#editUser').find('[name="fname"]').val(response.userData.first_name);
        $('#editUser').find('[name="lname"]').val(response.userData.last_name);
        $('#editUser').find('[name="username"]').val(response.userData.username);
        $('#editUser').find('[name="email"]').val(response.userData.email_id);

        //set role options
        var roles = '';
        response.all_roles.forEach(function(val,ind){
          roles += '<option value="'+val.role_id+'" ';
          if(val.role_id == response.userData.role_id){
            roles += 'selected';
          }
          roles +='>'+val.role_name+'</option>'
        })
        $('#editUser').find('[name="role"]').html(roles);
        $('#editUser').modal('show');
      }
    });
  });

  /*On submit of user edit form*/
  $('#editUserForm').on('submit', function(event){
    event.preventDefault();
    $('.error').empty();
    $('.ajax_response').css('display', 'block');

    var first_name = $('[name="fname"]').val();
    var last_name = $('[name="lname"]').val();
    var username = $('[name="username"]').val();
    var email = $('[name="email"]').val();
    var role = $('[name="role"]').val();
    var error_count = 0;

    var textregex = new RegExp("^[a-zA-Z ]*$");

    if($.trim(first_name).length == 0){
      $('.firstname_error').html('First name is mandatory');
      error_count++;
    }else if($.trim(first_name).length < 2){
      $('.firstname_error').html('Minimum 2 characters required in first name');
      error_count++;
    }else if (!textregex.test($.trim(first_name))) {
      $('.firstname_error').html('First name can contain only alphabets');
      error_count++;
    }

     if($.trim(last_name).length == 0){
      $('.lastname_error').html('Last name is mandatory');
      error_count++;
    }else if($.trim(last_name).length < 2){
      $('.lastname_error').html('Minimum 2 characters required in Last name');
      error_count++;
    }else if (!textregex.test($.trim(last_name))) {
      $('.lastname_error').html('Last name can contain only alphabets');
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

    if(error_count == 0){
      $.ajax({
        url: '<?php echo base_url(); ?>user_management/update_user',
        type: 'POST',
        dataType : 'json',
        data: {
          user_id: $('#editUser').data('user_id'),
          role : role,
          first_name : first_name,
          last_name : last_name,
          email : email,
          username : username
        },
        error: function() {
          $('.ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success: function(response){
          if(response.status == 0){
            $('#editRole').modal('hide');
            $('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>').delay(3000).fadeOut();
          }else{
            $('#editRole').modal('hide');
            $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
            window.setTimeout(function(){location.reload()},2000);
          }
        }
      });
    }
  });
</script>