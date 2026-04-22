<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-top: 10px;">
  		<div class="row" style="margin-bottom: 40px;">
        <!-- <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div class="content-body">
              <div class="row">
                <div class="col-md-12">
                  <label class="bold">Select Value Chain</label>
                  <select class="form-control" name="value_chain">
                    <option value="">All</option>
                    <?php foreach ($value_chain_list as $key => $value) { ?>
                      <option value="<?php echo $value['value_chain_id']; ?>"><?php echo $value['value_chain_name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <div class="col-md-12 add_data hidden">
          <h4 class="title">Assign Value chain to user</h4>
          <div class="card p-10">
            <div class="row">
              <div class="col-md-4 value_chains"></div>

              <div class="col-md-4 user_select"></div>

              <div class="col-md-2 submit_button"></div>

              <div class="col-md-12 ajax_message"></div>
            </div>
          </div>
        </div>

        <div class="col-md-12 mt-10">
          <h4 class="bold"></h4>
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
              <a href="javascript:void(0);" class="btn btn-success round float-md-right get_uservaluechain" style="margin-right: 20px;">Assign value to user</a>

              <h4 style="font-weight: bold;">Manage Value Chain User</h4>
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <!-- <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                  <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                  <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  <li><a data-action="close"><i class="ft-x"></i></a></li>
                </ul>
              </div> -->
            </div>

            <div class="card-content collapse show">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Value chain</th>
                        <th>User name</th>                        
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($valuechain_users) > 0){
                        foreach ($valuechain_users as $key => $value) { ?>
                          <tr>
                            <th scope="row"><?php echo $key+1; ?></th>
                            <td><?php echo $value['value_chain_name']; ?></td>
                            <td><?php echo $value['first_name']." ".$value['last_name']; ?></td>
                            
                            <td><a href="javascript:void(0);" class="btn btn-success btn-sm">Edit</a> <a href="<?php echo base_url(); ?>value_chain_manangement/manage_users_value_chain_location/<?php echo $value['value_chain_id']; ?>" class="btn btn-success btn-sm">Manage User Location</a></td>
                          </tr>
                        <?php }
                      }else{ ?>
                        <tr>
                          <td colspan="5">No surveys have been assigned.</td>
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
    $('body').on('click', '.get_uservaluechain', function(){
      $('.value_chains').html('');
      $('.user_select').html('');
      $('.ajax_message').html('');      

      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_uservaluechain',
        type: 'POST',
        dataType : 'json',
        error: function() {
          $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            var HTML_DATA = '<label>Select Value Chain</label>\
            <select class="form-control" name="valuechain">\
              <option value="">Select value chain</option>';
              response.get_uservaluechain.forEach(function(valuechain, index){
                HTML_DATA += '<option value="'+valuechain.value_chain_id+'">'+valuechain.value_chain_name+'</option>';
              });
            HTML_DATA += '</select>';

            $('.value_chains').html(HTML_DATA);
            $('.add_data').removeClass('hidden');
          }
        }
      });
    });

    $('body').on('change', 'select[name="valuechain"]', function(){
      $elem = $(this);
      var valuechain_id = $elem.val();
      if(valuechain_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/get_valuechain_users',
          type: 'POST',
          dataType : 'json',
          data : {
            valuechain_id : valuechain_id
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              if(response.users_list.length > 0){
                var HTML_DATA = '<label>Users list</label>\
                <select class="form-control" multiple name="users_list[]">';
                  response.users_list.forEach(function(user, index){
                    HTML_DATA += '<option value="'+user.user_id+'" '+(jQuery.inArray(user.user_id, response.get_valuechain_users) != '-1' ? 'selected' : '')+'>'+user.username+'</option>';
                  });
                HTML_DATA += '</select>';

                $('.user_select').html(HTML_DATA);

                $('.submit_button').html('<button type="button" style="margin-top: 35px;" class="btn btn-success assign_valuechain_user">Submit</button>');
              }else{
                $('.user_select').html('All the users are already to assigned to the selected value chain');
                $('.submit_button').html('');
              }           
            }
          }
        });
      }else{
        $('.user_select').html('');
        $('.submit_button').html('');
      }
    });

    $('body').on('click', '.assign_valuechain_user', function(){
      var valuechain = $('body').find('select[name="valuechain"]').val();
      var users_list = $('body').find('select[name="users_list[]"]').val();

      if(valuechain != '' && users_list.length > 0){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/assign_valuechain_user',
          type: 'POST',
          dataType : 'json',
          data: {
            valuechain : valuechain,
            users_list : users_list
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              $('.user_select').html('');
              $('.value_chains').html('');
              $('.submit_button').html('');              
              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');

              window.setTimeout(function(){
                window.location.href = "<?php echo base_url(); ?>value_chain_manangement/manage_users/";
              }, 3000);
            }
          }
        });
      }
    });
  });
</script>