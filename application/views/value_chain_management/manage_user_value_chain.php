<style type="text/css">
  .modal-header .close {
    -ms-flex-order: 2;
    order: 2;
    margin-top: -10px;
  }
  .modal-body {
      position: relative;
      -ms-flex: 1 1 auto;
      flex: 1 1 auto;
      padding: 20px;
      margin-top: -20px;
  }

  @media (min-width: 576px){
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }
    }
    
  .vertical-layout{
    margin-top: 10px;
   }

</style>

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title title">Edit details</h4>
        </div>
        
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 ajax_message"></div>

            <div class="col-md-12 value_chains"></div>

            <div class="col-md-12 user_select"></div>

            <div class="col-md-12 submit_button"></div>            
          </div>
          
        </div>
        <div class="modal-load" style="text-align: center;"></div>
        <div class="modal-footer">
            
        </div>
      </div>
  </div>
</div>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-top: 10px;">
       <div class="row" >
          <div class="col-md-12" style="margin-bottom: 30px; margin-top: -30px;">
            <img src="<?php echo base_url(); ?>includeout/images/banner.jpg" style="width: 100%;">
          </div>
        </div>
  		<div class="row" style="margin-bottom: 40px;">
        <div class="col-md-12 ">
          <a href="<?php echo base_url();?>value_chain_manangement/assign_value_chain_user" class="btn btn-success pull-right">Assign valuechain to user</a>
          <h4 class="title">Value Chain</h4>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div class="row">
              <div class="col-md-4">
                <label class="bold">Select Valuechain</label>
                  <?php  if(count($value_chain_list)>0){ ?>
                    <select name="valuechain" class="form-control valuechain" id="valuechain">
                      <option value="">Select Value</option>
                      <?php foreach ($value_chain_list as $key => $value) {?>
                       <option value="<?php echo $value['value_chain_id']; ?>"><?php echo $value['value_chain_name']; ?></option>
                      <?php }  ?>
                    </select>
                    <p class="error" style="color: red;"></p>
                  <?php } ?>
              </div>
              <!-- <div class="col-md-4">
                <label class="bold">Select User</label>
                <?php  if(count($val_chain_users) > 0){ ?>
                  <select name="users_list" class="form-control users_list" id="users_list"> 
                    <?php foreach ($val_chain_users as $key => $value) {?>
                     <option value="<?php echo $value['user_id']; ?>">
                      <?php echo $value['first_name']." ".$value['last_name']; ?></option>
                    <?php }  ?>
                  </select>
                <?php } ?>
              </div> -->
            </div>
          </div>

          <div class="card p-10">            
            <div class="table-responsive ">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Value chain</th>
                    <th>User name</th>   
                    <th>Action</th>   
                  </tr>
                </thead>
                <tbody class="valuechain_users">
                  <?php if(count($val_chain_users) > 0){
                    foreach ($val_chain_users as $key => $value) { ?>
                      <tr>
                        <td scope="row"><?php echo $key+1; ?></td>
                        <td><?php echo $value['value_chain_name']; ?></td>
                        <td><?php echo $value['first_name']." ".$value['last_name']; ?></td>
                        <td>
                          <a href="<?php echo base_url(); ?>value_chain_manangement/manage_users_value_chain_location/<?php echo $value['value_chain_id']; ?>/<?php echo $value['user_id'];?>"><span class="btn btn-info btn-sm"><i class="fa-map-pin"></i>Location Info</span></a>
                        </td>
                        <!--  <td><a href="javascript:void(0);" class="btn btn-success btn-sm">Edit</a> <a href="<?php echo base_url(); ?>value_chain_manangement/manage_users_value_chain_location/<?php echo $value['value_chain_id']; ?>" class="btn btn-success btn-sm">Manage User Location</a></td> -->
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
            var HTML_DATA = '<div class="row mt-20">\
              <div class="col-md-12">\
                <label>Select Value Chain</label>\
                <select class="form-control" name="assign_valuechain">\
                  <option value="">Select value chain</option>';
                  response.get_uservaluechain.forEach(function(valuechain, index){
                    HTML_DATA += '<option value="'+valuechain.value_chain_id+'">'+valuechain.value_chain_name+'</option>';
                  });
                HTML_DATA += '</select>\
              </div>\
            </div>';

            $('.value_chains').html(HTML_DATA);
            $('#myModal').modal('show');
          }
        }
      });   
    });

    $('body').on('change', 'select[name="assign_valuechain"]', function(){
      $elem = $(this);
      var valuechain_id = $elem.val();
      if(valuechain_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/get_valuechain_users_toassign',
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

    $('body').on('change', 'select[name="valuechain"]', function(){
      $('.error').html('');
      $elem = $(this);
      var valuechain_id = $elem.val();
     console.log(valuechain_id);
        var query = {valuechain_id: valuechain_id};
        get_data(query);
      // }else{
      //   $('.user_select').html('');
      //   $('.submit_button').html('');
      //   $('.error').html('Please select value chain');
      // }
    });

    $('body').on('change', 'select[name="users_list"]', function(){
      $elem = $(this);
      var user_id = $elem.val();
      var valuechain_id = $('body').find('select[name="valuechain"]').val();
      if(valuechain_id != '' && user_id.length > 0){
        var query = {valuechain_id:valuechain_id, user_id:user_id};
        get_data(query);
      }else{
        $('.user_select').html('');
        $('.submit_button').html('');
      }
    });

    $('body').on('click', '.assign_valuechain_user', function(){
      var valuechain = $('body').find('select[name="assign_valuechain"]').val();
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

    function get_data(query) {
      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_valuechain_users',
        type: 'POST',
        dataType : 'json',
        data : query,
        error: function() {
          $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            var HTML_DATA = '';
            if(response.val_chain_users.length > 0){
              response.val_chain_users.forEach(function(valuechain, index){
                HTML_DATA += '<tr>\
                  <td>'+(index+1)+'</td>\
                  <td>'+valuechain.value_chain_name+'</td>\
                  <td>'+valuechain.first_name+' '+valuechain.last_name+'</td>\
                  <td><a href="<?php echo base_url(); ?>value_chain_manangement/manage_users_value_chain_location/'+valuechain.value_chain_id+'/'+valuechain.user_id+'"><span class="fa-map-pin btn btn-info btn-sm">Location Info</span></a></td>\
                </tr>';
              });

              $('.valuechain_users').html(HTML_DATA);
            }else{
              $('.valuechain_users').html('<tr><td colspan="4">No users found</td></tr>');
            }           
          }
        }
      });
    }
  });
</script>