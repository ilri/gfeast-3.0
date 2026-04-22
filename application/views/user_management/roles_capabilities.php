<style type="text/css">
 
   .vertical-layout{
    margin-top: 10px;
   }

</style>


<div class="app-content content" style="margin-left: 0px;">
  	<div class="content-wrapper">
  		
    	<div class="content-body" style="margin-bottom: 30px;">
    		 	<div class="row" >
		          <div class="col-md-12" style="margin-bottom: 30px; margin-top: -30px;">
		            <img src="<?php echo base_url(); ?>includeout/images/banner.jpg" style="width: 100%;">
		          </div>
        		</div>
			<div class="row">
				<div class="col-12 ajax_message"></div>
			    <div class="col-12">			    	
			        <div class="card">
			            <div class="card-content collapse show">
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
				                <h4 class="card-title">Roles and capabilities</h4>
				            </div>
			                <div class="card-body">
			                    <div class="table-responsive">
			                        <table class="table table-striped">
			                            <thead>
			                                <tr>
			                                    <th>Capability</th>
			                                    <?php foreach ($roles_list as $key => $role) { ?>
			                                    	<th><?php echo $role['role_name']; ?></th>
			                                    <?php } ?>
			                                </tr>
			                            </thead>
			                            <tbody>
			                            	<?php foreach ($capability_list as $key => $capability) { ?>
			                            		<tr>
				                            		<td colspan="7" style="text-align: left; font-weight: bold;">
				                            			<?php echo $capability['module_name']; ?>
				                            		</td>
				                            	</tr>
				                            	<?php foreach ($capability['permissions'] as $key => $permission) { ?>
				                            		<tr>
				                            			<td><?php echo $permission['name']; ?></td>
				                            			<?php foreach ($roles_list as $key => $role) { ?>
					                                    	<td>
					                                    		<?php if($permission[$role['role_name']] == 1){ ?>
					                                    			<button class="btn btn-success btn-sm updatepermission" data-roleid="<?php echo $role['role_id']; ?>" data-moduleid="<?php echo $capability['module_id']; ?>" data-permissionid="<?php echo $permission['permission_id']; ?>" data-assigingstatus="1">Yes</button>
					                                    		<?php }else{ ?>
					                                    			<button class="btn btn-danger btn-sm updatepermission" data-roleid="<?php echo $role['role_id']; ?>" data-moduleid="<?php echo $capability['module_id']; ?>" data-permissionid="<?php echo $permission['permission_id']; ?>" data-assigingstatus="0">No</button>
					                                    		<?php } ?></td>
					                                    <?php } ?>
				                            		</tr>
				                            	<?php }
			                            	} ?>
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

<script type="text/javascript">
	$(function(){
		$('body').on('click', '.updatepermission', function(){
			$elem = $(this);

			var roleid = $elem.data('roleid');
			var moduleid = $elem.data('moduleid');
			var permissionid = $elem.data('permissionid');
			var assigingstatus = $elem.data('assigingstatus');

			if(assigingstatus == 1){
				var query_data = { roleid : roleid, moduleid : moduleid, permissionid : permissionid, assigingstatus : 0 };
			}else{
				var query_data = { roleid : roleid, moduleid : moduleid, permissionid : permissionid, assigingstatus : 1 };
			}

			$.ajax({
          		url: '<?php echo base_url(); ?>user_management/update_permissions',
          		type: 'POST',
         		dataType : 'json',
          		data: query_data,
          		error: function() {
            		$('.ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          		},
          		success: function(response){
            		if(response.status == 0){
              			$('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>');
            		}else{
              			$('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
              			
              			if(assigingstatus == 1){
              				$elem.removeAttr('data-assigingstatus');
              				$elem.attr('data-assigingstatus', '0');
              				$elem.html('No');
              				$elem.attr('class', 'btn btn-danger btn-sm updatepermission');
              			}

						if(assigingstatus == 0){
							$elem.removeAttr('data-assigingstatus');
              				$elem.attr('data-assigingstatus', '1');
              				$elem.html('Yes');
              				$elem.attr('class', 'btn btn-success btn-sm updatepermission');
              			}              			
            		}

            		$('html,body').animate({
              			scrollTop: $(".ajax_message").offset().top - 300
            		}, 500);

            		setTimeout(function() {
    					location.reload();
					}, 2000);
          		}
        	});
		});
	});
</script>