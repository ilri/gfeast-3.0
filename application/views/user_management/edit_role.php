<style type="text/css">
  #editRole{
    top: 20%;
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
      <div id="editRole" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Role</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="row form-group" id="editRoleForm">
                <div class="col-md-12">
                  <div class="ajax_message"></div>
                </div>
                <!-- role name -->
                <div class="col-md-12">
                  <label>Role name<span style="color:red;">*</span></label>
                  <input type="text" name="role_name" class="form-control">
                  <span class="error rolename_error"></span>
                </div>

                <!-- role description -->
                <div class="col-md-12 mt-20">
                  <label>Role description<span style="color:red;">*</span></label>
                  <textarea class="form-control" name="description" id="role_description"></textarea>
                  <span class="error roledescription_error"></span>
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
  				<h4 style="font-weight: bold;">Edit role</h4>
  			</div>

        <div class="col-md-12 mt-20">
          <div class="card p-20">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>S.no</th>
                  <th>Role name</th>
                  <th>Role Description</th>
                  <th>Added date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($roles) > 0){
                  foreach ($roles as $rkey => $role) { ?>
                    <tr>
                      <th><?php echo $rkey+1; ?></th>
                      <td><?php echo $role['role_name']; ?></td>
                      <td><?php echo $role['role_description']; ?></td>
                      <td><?php echo $role['added_date']; ?></td>
                      <td><a href="javascript:void(0);" data-toggle="tooltip" title="Edit role" class="edit" data-role_id="<?php echo $role['role_id']; ?>" data-role_name="<?php echo $role['role_name']; ?>" data-role_description="<?php echo $role['role_description']; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> Edit</a></td>
                    </tr>
                  <?php }
                }else{ ?>
                  <tr>
                    <td colspan="5">No Roles have been found from your account.</td>
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
  $('.edit').on('click', function(){
    $elem = $(this);
    //get details of roles
    var role_id = $elem.data('role_id');
    var role_name = $elem.data('role_name');
    var role_description = $elem.data('role_description');
    //set role id as data attribute in edit role form
    $('#editRoleForm').data('role_id', role_id);
    //set data to fields
    $('#editRole').find('[name="role_name"]').val(role_name);
    $('#editRole').find('#role_description').val(role_description);
    $('#editRole').modal('show');
  });

  //on submit of edit role form
  $('#editRoleForm').on('submit', function(event){
    event.preventDefault();
    //remove error messages
    $('.error').empty();
    $('.ajax_response').css('display', 'block');
    $elem = $(this);
    var role_id = $elem.data('role_id');
    var role_name = $elem.find('[name="role_name"]').val();
    var role_description = $elem.find('#role_description').val();
    var error_count = 0;
    //regex to validate
    var textregex = new RegExp("^[a-zA-Z ]*$");
    //validate role name
    if($.trim(role_name).length == 0){
      $('.rolename_error').html('Role name is mandatory');
      error_count++;
    } else if($.trim(role_name).length < 2){
      $('.rolename_error').html('Minimum 2 characters required in role name');
      error_count++;
    } else if (!textregex.test($.trim(role_name))) {
      $('.rolename_error').html('Role name can contain only alphabets');
      error_count++;
    }
    //validate role
    if($.trim(role_description).length == 0){
      $('.roledescription_error').html('Role Description is mandatory');
      error_count++;
    } else if($.trim(role_description).length < 2){
      $('.roledescription_error').html('Minimum 2 characters required in role description');
      error_count++;
    } else if (!textregex.test($.trim(role_description))) {
      $('.roledescription_error').html('Role description can contain only alphabets');
      error_count++;
    }

    if(error_count == 0){
      $.ajax({
        url: '<?php echo base_url(); ?>user_management/update_role',
        type: 'POST',
        dataType:'json',
        data: {
          role_id:role_id,
          role_name : role_name,
          role_description : role_description
        },
        error: function() {
          $('.ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success: function(response){
          console.log(response);
          if(response.status == 1){
            $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
            window.setTimeout(function(){location.reload()},2000);
          }
        }
      });
    }
  })
  $(".close").on("click",function(){
    console.log('1');
  $('.roledescription_error').html('');
  $('.rolename_error').html('');
  $("form")[0].reset();
});

</script>