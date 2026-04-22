<style>
  .vertical-layout{
    margin-top: 10px;
  }
  .error {
    color: red;
    font-size: 13px;
  }
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>
<!-- user personal edit modal -->
<div id="UserEditModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit User Personal Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo form_open('', array('id' => 'EditUserForm', 'class' => 'form-group')); ?>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>First Name</label> <span class="text-danger">*</span>
              <input type="text" name="fname" class="form-control" placeholder="Enter First name">
              <span id="fname_error" class="error"></span>
            </div>
            <div class="col-md-6 form-group">
              <label>Last Name</label> <span class="text-danger">*</span>
              <input type="text" name="lname" class="form-control" placeholder="Enter Last name">
              <span id="lname_error" class="error"></span>
            </div>
            <div class="col-md-6 form-group">
              <label>Email Id</label> <span class="text-danger">*</span>
              <input type="email" name="email" class="form-control" placeholder="Enter Email id">
              <span id="email_error" class="error"></span>
            </div>
            <div class="col-md-6 form-group">
              <label>Username</label> <span class="text-danger">*</span>
              <input type="text" name="username" class="form-control" placeholder="Enter Username">
              <span id="username_error" class="error"></span>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success float-right mr-1">Update</button>
            </div>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
<!-- user personal edit modal ends -->

<!-- Main content -->
<div class="main-content">
  <div class="p-4">
    <div class="card">
      <div class="card-header">
        <h3>All Users</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Sl.no</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Email id</th>
                <th>Username</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(count($users) > 0){
                foreach ($users as $ukey => $user) { ?>
                  <tr data-user_id="<?php echo $user['user_id']; ?>">
                    <td><?php echo ($ukey+1); ?></td>
                    <td><?php echo $user['first_name']; ?></td>
                    <td><?php echo $user['last_name']; ?></td>
                    <td><?php echo $user['email_id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td>
                    <?php if($user['status'] == 1) { ?>
                      <span class="text-success">Active</span>
                    <?php } else { ?>
                      <span class="text-danger">Inactive</span>
                    <?php } ?>
                    </td>
                    <td>
                      <a href="javascript:void(0);" class="edit">
                        <i class="fa fa-pencil-square" aria-hidden="true"></i> Edit
                      </a>
                      <span class="mx-1">|</span>
                      <a href="javascript:void(0);" class="activation" data-status="<?php echo $user['status'] == 1 ? 0 : 1; ?>">
                        <?php if($user['status'] == 1) { ?>
                        <i class="fa fa-ban text-danger" aria-hidden="true"></i> Deactivate
                        <?php } else { ?>
                        <i class="fa fa-check text-success" aria-hidden="true"></i> Activate
                        <?php } ?>
                      </a>
                      <span class="mx-1">|</span>
                      <a href="javascript:void(0);" class="resetpass">
                        <i class="fa password" aria-hidden="true"></i> Reset Password
                      </a>
                    </td>
                  </tr>
                <?php }
              }else{ ?>
                <tr>
                  <td colspan="7">No users found</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function(){ });

// Define global variable ajaxData
var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

// Handle edit and editProf button click
$('body').on('click', '.edit', function(event){
  $elem = $(this);
  var user_id = $elem.closest('tr').data('user_id');
  $('#UserEditModal').find('.error').empty();
  $('#UserEditModal').modal('show');
  $('#EditUserForm').data('user_id', user_id);
  $('#EditUserForm').find('button').prop('disabled', true);
  $('#EditUserForm').find('button[type="submit"]').html('Please Wait... Getting User Details');
  
  //send ajax request to get user data to edit
  ajaxData['user_id'] = user_id;
  $.ajax({
    url: '<?php echo base_url(); ?>Users/get_user_details',
    type: 'POST',
    dataType : 'json',
    data: ajaxData,
    complete: function(data) {
      $('#EditUserForm').find('button[type="submit"]').html('Update');
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
          $('#EditUserForm').find('button').prop('disabled', false);
          $('#UserEditModal').modal('hide');
        }
      });
    },
    success: function(response){
      $('#EditUserForm').find('button').prop('disabled', false);
      $('[name="fname"]').val(response.user_details.first_name);
      $('[name="lname"]').val(response.user_details.last_name);
      $('[name="email"]').val(response.user_details.email_id);
      $('[name="username"]').val(response.user_details.username);
    }
  });
});

//Handle user edit form submit
$('body').on('submit', '#EditUserForm', function(event) {
  event.preventDefault();
  $('.error').empty();
  var fname = $('[name="fname"]').val();
  var lname = $('[name="lname"]').val();
  var email = $('[name="email"]').val();
  var username = $('[name="username"]').val();
  var user_id = $(this).data('user_id');
  var error = false;

  var form = $(this);
  $('input[type="text"]', form).each(function(index) {
    var elem = $(this);
    elem.val($.trim(elem.val()));
  });
  $('input[type="email"]', form).each(function(index) {
    var elem = $(this);
    elem.val($.trim(elem.val()));
  });

  if($.trim(fname).length == 0){
    error = true;
    $('#fname_error').html('First name is mandatory');
  }

  if($.trim(lname).length == 0){
    error = true;
    $('#lname_error').html('Last name is mandatory');
  }

  if($.trim(email).length == 0){
    error = true;
    $('#email_error').html('Email Id is mandatory');
  }

  if($.trim(username).length == 0){
    error = true;
    $('#username_error').html('Username is mandatory');
  }

  if(!error){
    ajaxData['user_id'] = user_id;
    ajaxData['fname'] = fname;
    ajaxData['lname'] = lname;
    ajaxData['email'] = email;
    ajaxData['username'] = username;
    $.ajax({
      url: '<?php echo base_url(); ?>Users/update_user_details',
      type: 'POST',
      dataType : 'json',
      data: ajaxData,
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
          icon: 'error'
        });
      },
      success: function(response){
        $.toast({
          heading: 'Success!',
          text: response.msg,
          icon: 'success',
          afterHidden: function () {
            window.location.reload();
          }
        });
      }
    });
  }
});

// Activating and Deactivating User
$('body').on('click', '.activation', function(){
  var elem = $(this);
  elem.addClass('disabled');
  if(elem.data('status') == 0) {
    elem.html('Please Wait.... Deactivating User.');
  } else {
    elem.html('Please Wait.... Activating User.');
  }
  deleteUser(elem);
});
function deleteUser(elem){
  ajaxData['status'] = elem.data('status');
  ajaxData['user_id'] = elem.closest('tr').data('user_id');
  
  $.ajax({
    url: '<?php echo base_url(); ?>users/delete_user/',
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
        icon: 'error'
      });
      elem.removeClass('disabled');
      if(elem.data('ststus') == 0) elem.html('<i class="fa fa-ban text-danger" aria-hidden="true"></i> Deactivate');
      else elem.html('<i class="fa fa-check text-success" aria-hidden="true"></i> Activate');
    },
    success: function(data) {
      if(data.status == 0) {
        $.toast({
          heading: 'Error!',
          text: data.msg,
          icon: 'error'
        });
        elem.removeClass('disabled');
        if(elem.data('ststus') == 0) elem.html('<i class="fa fa-ban text-danger" aria-hidden="true"></i> Deactivate');
        else elem.html('<i class="fa fa-check text-success" aria-hidden="true"></i> Activate');
        return false;
      }
      
      $.toast({
        heading: 'Success!',
        text: data.msg,
        icon: 'success',
        afterHidden: function () {
          window.location.reload();
        }
      });
    }
  });
}

// Resseting user's password to Default
$('body').on('click', '.resetpass', function(event) {
  var elem = $(this);
  swal({
    title: "Are you sure?",
    text: "The User's password will be reset to 'Mpro@123'",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes, reset it!"
  }, function() {
    elem.addClass('disabled');
    elem.html('Please Wait.... Resetting User Password.');
    resetPassword(elem);
  });
});
function resetPassword(elem){
  ajaxData['user_id'] = elem.closest('tr').data('user_id');
  
  $.ajax({
    url: '<?php echo base_url(); ?>users/reset_user_password/',
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
        icon: 'error'
      });
      elem.removeClass('disabled');
      elem.html('<i class="fa password" aria-hidden="true"></i> Reset Password');
    },
    success: function(data) {
      elem.removeClass('disabled');
      elem.html('<i class="fa password" aria-hidden="true"></i> Reset Password');
      
      if(data.status == 0) {
        $.toast({
          heading: 'Error!',
          text: data.msg,
          icon: 'error'
        });
        return false;
      }

      $.toast({
        heading: 'Success!',
        text: data.msg,
        icon: 'success'
      });
    }
  });
}
</script>
