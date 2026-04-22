<style type="text/css">
  .error{
    color: red;
  }
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
        <div class="col-md-12 add_data ">
          <h4 class="title">Assign Value chain locations to user</h4>
          <div class="card p-10">
            <div class="row">
               <div class="col-md-12 ajax_message"></div>
              <div class="col-md-2 valuechain_select ">
                <label>Valuechain<span style="color:red;">*</span></label>
                <select name="assign_valuechain" class="form-control value_chains">
                  <option value="">Select valuechain</option>
                  <?php if(count($get_uservaluechain)>0){ foreach($get_uservaluechain as $valuechain){ ?>
                    <option value="<?php echo $valuechain['value_chain_id'];?>"><?php echo $valuechain['value_chain_name'];?></option>
                  <?php } } ?>
                </select>
              </div>
              <div class="col-md-12 user_select"></div>
              <div class="col-md-12 submit_button"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

  $(function(){

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
                var HTML_DATA = '<label>Select Users<span style="color:red;">*</span></label>\
                <input type="checkbox" name="checkAll" id="checkAll">Check/Uncheck all\
                <div class="form-check">\
                            <div class="row">';
                  response.users_list.forEach(function(user, index){
                    HTML_DATA += '<div class="col-md-4">\
                    <label class="radio-inline" >\
                    <input type="checkbox" name="users_list[]" class="usersel" value="'+user.user_id+'" '+(jQuery.inArray(user.user_id, response.get_valuechain_users) != '-1' ? 'checked' : '')+'>'+user.username+'\
                      </label>\
                      </div>';
                  });
                  HTML_DATA += '</div></div>\
                <p class="ward_error error"></p>';
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

  $('body').on('click','#checkAll',function () {
      console.log('1');
        if ($("#checkAll").is(':checked')) {
            $(".usersel").prop("checked", true);
        } else {
            $(".usersel").prop("checked", false);
        }
    });


    $('body').on('change', 'select[name="valuechain"]', function(){
      $('.error').html('');
      $elem = $(this);
      var valuechain_id = $elem.val();
      if(valuechain_id != ''){
        var query = {valuechain_id: valuechain_id};
        get_data(query);
      }else{
        $('.user_select').html('');
        $('.submit_button').html('');
        $('.error').html('Please select value chain');
      }
    });

    $('body').on('change', 'select[name="users_list[]"]', function(){
      $elem = $(this);
      var users_list=[];
      $.each($("input[name='users_list[]']:checked"),function(){
          user_list.push($elem.val());
      });
      var valuechain_id = $('body').find('select[name="assign_valuechain"]').val();
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
      var users_list=[];
      $.each($("input[name='users_list[]']:checked"),function(){
          users_list.push( $(this).val());
      });
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
              $('.value_chains').val('');
              $('.submit_button').html('');              
              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>').delay(500).fadeOut();
            }
          }
        });
      }
    });
  });
</script>
