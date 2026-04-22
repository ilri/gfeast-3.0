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
  				<h4 style="font-weight: bold;">Delete role</h4>
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
                      <td>
                        <a href="javascript:void(0);" data-toggle="tooltip" title="Delete role" class="delete" data-role_id="<?php echo $role['role_id']; ?>" data-role_name="<?php echo $role['role_name']; ?>" data-role_description="<?php echo $role['role_description']; ?>" style="color: red;">
                          <i class="fa fa-trash" aria-hidden="true"></i> Delete
                        </a>
                      </td>
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
  $('.delete').on('click', function(){
    $elem = $(this);
    //get details of roles
    var role_id = $elem.data('role_id');
    //use sweetalert for confirmation
    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this role",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      cancelButtonText: "No, cancel",
      confirmButtonText: "Yes, delete it",
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {
        $.ajax({
          url: '<?php echo base_url(); ?>user_management/delete_role',
          type: 'POST',
          dataType:'json',
          data: {
            role_id:role_id
          },
          error: function() {
            console.log('No response...');
          },
          success: function(response){
            console.log(response);
            if(response.status == 1){
              $elem.closest('tr').remove();
              swal("Deleted!", "Your role has been deleted.", "success");
            } else if(response.status == 2){
              swal("Warning!", "This role could not be deleted, role is assigned to some user !", "warning");
            } else {
              swal("Warning!", "Something went wrong, please try again later!", "warning");
            }
          }
        });
      }
    });
  })
</script>