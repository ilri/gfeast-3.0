<style>
	.vertical-layout{
    margin-top: 10px;
   }
</style>


<div class="modal fade text-left" id="success" tabindex="-1" role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
  	<div class="modal-dialog" role="document">
		<div class="modal-content">
	  		<div class="modal-header bg-success white">
				
	  		</div>
		  	<div class="modal-body">
				
		  	</div>
	  		<div class="modal-footer">
				
	  		</div>
		</div>
  	</div>
</div>

<div class="app-content content" style="margin-left: 0px;">
  	<div class="content-wrapper">
    	<div class="content-body" style="margin-bottom: 30px;">
    		<div class="row" >
          <div class="col-md-12" style="margin-bottom: 30px; margin-top: -30px;">
            <img src="<?php echo base_url(); ?>includeout/images/banner.jpg" style="width: 100%;">
          </div>
        </div>
			<div class="row">
				<div class="content-header-left col-md-5">
					<div class="row" style="margin-bottom: 20px;">
						<!-- <div class="col-md-7">
	                		<input type="text" placeholder="search..." class="form-control" name="">
	                	</div> -->
	                	<!-- <?php if($main_menu['permission_list'] != ''){ ?>
		                	<div class="col-md-2">
				                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">   
						          	<button class="btn btn-info round dropdown-toggle dropdown-menu-right box-shadow-2 px-2" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
						          	<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
						          		<?php foreach ($main_menu['permission_list'] as $key => $value) { ?>
						          			<a class="dropdown-item" href="<?php echo base_url(); ?><?php echo $this->uri->segment(1); ?>/<?php echo $value['module_key']; ?>"><?php echo $value['name']; ?></a>
						          		<?php } ?>
						            	
						          	</div>
						        </div>
						    </div>
						<?php } ?> -->
					</div>
	            </div>
	            <div class="content-header-right col-md-7">
                    <div class="form-group" style="float: right;">
                        <!-- Outline Buttons Glow -->
                        <!-- <button type="button" class="btn btn-outline-primary btn-min-width btn-glow mr-1 mb-1">All</button>
                        <?php foreach ($roles_list as $key => $value) { ?>
                        	<button type="button" class="btn btn-outline-success btn-min-width btn-glow mr-1 mb-1 get_userslist" data-roleid="<?php echo $value['role_id']; ?>"><?php echo $value['role_name']; ?></button>
                        <?php } ?>   -->                      
                    </div>
	            </div>
			    <div class="col-12">		    	
			        <div class="card">
			            <div class="card-content collapse show">
					  		<div class="card-header">
				                <h4 class="card-title">Users list</h4>
				            </div>
			                <div class="card-body">
			                    <div class="table-responsive">
			                        <table class="table table-striped">
			                            <thead>
			                                <tr>
			                                	<th>Sl.no</th>
			                                    <th>First name</th>
			                                    <th>Last name</th>
			                                    <th>Email id</th>
			                                    <th>Username</th>
			                                    <th>Role</th>
			                                </tr>
			                            </thead>
			                            <tbody>
			                            	<?php foreach ($user_list as $key => $value) { ?>
			                            		<tr>
				                            		<td><?php echo $key+1; ?></td>
				                            		<td><?php echo $value['first_name']; ?></td>
				                            		<td><?php echo $value['last_name']; ?></td>
				                            		<td><?php echo $value['email_id']; ?></td>
				                            		<td><?php echo $value['username']; ?></td>
				                            		<td><?php echo $value['role_name']; ?></td>
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
    	</div>
   	</div>
</div>

<script type="text/javascript">
	$(function(){
		$('.create_role').on('click', function(){
			var HTML_HEADER = '<h4 class="modal-title white" id="myModalLabel9"> Add role</h4>\
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
			  <span aria-hidden="true">&times;</span>\
			</button>';
			$('body').find('.modal-header').html(HTML_HEADER);

			var HTML_BODY = '<div class="row">\
                <div class="col-md-6">\
                  <div class="form-group">\
                    <label for="firstName1">Role Name</label>\
                    <input type="text" class="form-control" id="firstName1">\
                  </div>\
                </div>\
                <div class="col-md-6">\
                  <div class="form-group">\
                    <label for="lastName1">Display Name</label>\
                    <input type="text" class="form-control" id="lastName1" >\
                  </div>\
                </div>\
                <div class="col-md-12">\
                  <div class="form-group">\
                    <label>Copy capabilities from</label>\
                    <select class="form-control" id="DefaultSelect">\
                        <option selected="">Default select options</option>\
                        <option value="1">One</option>\
                        <option value="2">Two</option>\
                        <option value="3">Three</option>\
                    </select>\
                  </div>\
                </div>\
            </div>';
            $('body').find('.modal-body').html(HTML_BODY);

            var HTML_FOOTER = '<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>\
				<button type="button" class="btn btn-outline-success">Save changes</button>';
			$('body').find('.modal-footer').html(HTML_FOOTER);

			$('#success').modal('show');
		});

		$('.create_user').on('click', function(){
			var HTML_HEADER = '<h4 class="modal-title white" id="myModalLabel9"> Add user</h4>\
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">\
			  <span aria-hidden="true">&times;</span>\
			</button>';
			$('body').find('.modal-header').html(HTML_HEADER);

			var HTML_BODY = '<div class="row">\
                <div class="col-md-6">\
                  <div class="form-group">\
                    <label for="firstName1">Role Name</label>\
                    <input type="text" class="form-control" id="firstName1">\
                  </div>\
                </div>\
                <div class="col-md-6">\
                  <div class="form-group">\
                    <label for="lastName1">Display Name</label>\
                    <input type="text" class="form-control" id="lastName1" >\
                  </div>\
                </div>\
                <div class="col-md-12">\
                  <div class="form-group">\
                    <label>Copy capabilities from</label>\
                    <select class="form-control" id="DefaultSelect">\
                        <option selected="">Default select options</option>\
                        <option value="1">One</option>\
                        <option value="2">Two</option>\
                        <option value="3">Three</option>\
                    </select>\
                  </div>\
                </div>\
            </div>';
            $('body').find('.modal-body').html(HTML_BODY);

            var HTML_FOOTER = '<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>\
				<button type="button" class="btn btn-outline-success">Save changes</button>';
			$('body').find('.modal-footer').html(HTML_FOOTER);

			$('#success').modal('show');
		});
	});
</script>