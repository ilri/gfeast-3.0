<style>
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
  				<h4 style="font-weight: bold;">Delete users</h4>
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
                      <td><a href="javascript:void(0);" data-toggle="tooltip" title="Edit User" class="delete" data-user_id="<?php echo $user['user_id']; ?>" style="color:red;"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a></td>
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
  $('.delete').on('click', function(){
    $elem = $(this);
    //get details of roles
    var user_id = $elem.data('user_id');
    //use sweetalert for confirmation
    swal({
      title: "Are you sure?",
      text: "You want to delete this user",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      cancelButtonText: "No, cancel",
      confirmButtonText: "Yes, delete",
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        $.ajax({
          url: '<?php echo base_url(); ?>user_management/delete_users',
          type: 'POST',
          dataType:'json',
          data: {
            user_id:user_id
          },
          error: function() {
            console.log('No response...');
          },
          success: function(response){
            console.log(response);
            if(response.status == 1){
              $elem.closest('tr').remove();
              swal("Deleted!", "User has been deleted.", "success");
            }
          }
        });
      }
    });
  })
</script>