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


  			<!-- <div class="col-md-12">  				
          <span>
            <a href="<?php echo base_url(); ?>value_chain_manangement/manage_users" class="btn btn-success btn-sm round float-md-right">Back</a>
          </span>
          <h4 style="font-weight: bold;">Manage User</h4>
  			</div> -->

        <div class="col-md-12 add_data ">
          <h4 class="title">Assign Value chain locations to user</h4>
          <div class="card p-10">
            <div class="row">
               <div class="col-md-12 ajax_message"></div>
              <div class="col-md-2 valuechain_select">
                <label>Valuechain<span style="color:red;">*</span></label>
                <select name="valuechain" class="form-control">
                  <option value="">Select valuechain</option>
                  <?php if(count($user_valuechain)>0){ foreach($user_valuechain['value_chain_list'] as $valuechain){?>
                    <option value="<?php echo $valuechain['value_chain_id'];?>"><?php echo $valuechain['value_chain_name'];?></option>
                  <?php } } ?>
                </select>
              </div>

              <div class="col-md-2 user_select"></div>

              <div class="col-md-2 county_select"></div>

              <div class="col-md-2 subcounty_select"></div>

              <div class="col-md-12 ward_select" ></div>

              <div class="col-md-12 submit_button"></div>
            </div>
          </div>
        </div>
         </div>
        </div>
  		</div>
  	</div>
	</div>
</div>

<script>
    $(function(){

  $('body').on('change', 'select[name="valuechain"]', function(){
      $('body').find('.user_field').html('');
      $('body').find('.county_select').html('');
      $('body').find('.subcounty_select').html('');
      $('body').find('.ward_select').html('');

      $('.modal-footer').html('');

      $elem = $(this);
      var valuechain_id = $elem.val();

      if(valuechain_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/get_valuechain_user_county',
          type: 'POST',
          dataType : 'json',
          data: {
            valuechain_id : valuechain_id
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              var HTML_DATA_user = '<label>Select User<span style="color:red;">*</span></label>\
              <select class="user form-control" name="user">\
                <option value="">Select User</option>';
                response.get_valuechain_user.forEach(function(user, index){
                  HTML_DATA_user += '<option value="'+user.user_id+'">'+user.username+'</option>';
                });
              HTML_DATA_user += '</select>';
              HTML_DATA_user += '<p class="user_error error"></p>';

              $('.user_select').html(HTML_DATA_user);

              var HTML_DATA_county = '<label>Select County<span style="color:red;">*</span></label>\
              <select class="county form-control" name="county">\
                <option value="">Select County</option>';
                response.get_valuechain_county.forEach(function(county, index){
                  HTML_DATA_county += '<option value="'+county.county_id+'">'+county.name+'</option>';
                });
              HTML_DATA_county += '</select>';
              HTML_DATA_county += '<p class="county_error error"></p>';

              $('.county_select').html(HTML_DATA_county);
            }
          }
        });
      }else{
        $('.user_select').html('');
        $('.county_select').html('');
        $('.subcounty_select').html('');
        $('.ward_select').html('');
        $('.submit_button').html('');
      }
    }); 

    $('body').on('change', '.county', function(){
      $('body').find('.subcounty_select').html('');
      $('body').find('.ward_select').html('');
      $('.submit_button').html('');

      var valuechain_id = $('body').find('select[name="valuechain"]').val();

      $('.modal-footer').html('');

      $elem = $(this);
      var county_id = $elem.val();

      if(county_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/get_valuechain_subcounties',
          type: 'POST',
          dataType : 'json',
          data: {
            county_id : county_id,
            valuechain_id : valuechain_id
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              var HTML_DATA = '';

              HTML_DATA += '<label>Select Subcounty<span style="color:red;">*</span></label>\
              <select class="subcounty form-control" name="subcounty">\
                <option value="">Select Subcounty</option>';
                response.get_valuechain_subcounties_list.forEach(function(subcounty, index){
                  HTML_DATA += '<option value="'+subcounty.sub_county_id+'">'+subcounty.sub_county_name+'</option>';
                });
              HTML_DATA += '</select>';
              HTML_DATA += '<p class="subcounty_error error"></p>';

              $('.subcounty_select').html(HTML_DATA);
            }
          }
        });
      }else{
        $('.subcounty_select').html('');
        $('.ward_select').html('');
        $('.submit_button').html('');
      }
    });

    $('body').on('change', '.user', function(){
      var county_id = $('body').find('select[name="county"]').val('');
      $('body').find('.subcounty_select').html('');
      $('body').find('.ward_select').html('');
      $('.submit_button').html('');
    });

    $('body').on('change', '.subcounty', function(){
      $('body').find('.ward_select').html('');
      $('.submit_button').html('');

      $('.modal-footer').html('');

      $elem = $(this);

      var valuechain_id = $('body').find('select[name="valuechain"]').val();
      var county_id = $('body').find('select[name="county"]').val();
      var user_id = $('body').find('select[name="user"]').val();

      var subcounty_id = $elem.val();

      if(subcounty_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/get_valuechain_wards',
          type: 'POST',
          dataType : 'json',
          data: {
            subcounty_id : subcounty_id,
            county_id : county_id,
            valuechain_id : valuechain_id,
            user_id : user_id
          },
          error: function() {
            //$('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              //$('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              var HTML_DATA = '';

              if(response.get_valuechain_wards.length == 0){
                HTML_DATA += '<p class="mt-20" style="color:red;">All ward have been already assigned to the user</p>';
              }else{
                HTML_DATA += '<label>Select Ward<span style="color:red;">*</span></label>\
                <input type="checkbox" name="checkAll" id="checkAll">Check/Uncheck all\
                <div class="form-check">\
                            <div class="row">';
               response.get_valuechain_wards.forEach(function(ward, index){
                                                HTML_DATA += '<div class="col-md-4">';
                                  HTML_DATA += '<label class="radio-inline" >\
                                    <input type="checkbox" class="ward wardsel" name="ward[]" value="'+ward.ward_id+'">'+ward.ward_name+'\
                                  </label>\
                                </div>';
                  });
                HTML_DATA += '</div></div>\
                <p class="ward_error error"></p>';

                $('.submit_button').html('<button type="button" class="btn btn-success assign_user_valuechain_locations float-md-right" style="margin-top:30px;">Submit</button>');
              }             

              $('.ward_select').html(HTML_DATA);             
            }
          }
        });
      }else{
        $('.ward_select').html('');
        $('.submit_button').html('');
      }
    });

    $('body').on('click','#checkAll',function () {
        if ($("#checkAll").is(':checked')) {
            $(".wardsel").prop("checked", true);
        } else {
            $(".wardsel").prop("checked", false);
        }
    });

    $('body').on('click', '.assign_user_valuechain_locations', function(){
      var valuechains = [];
      
      var valuechain_id = $('body').find('select[name="valuechain"]').val();
      var user_id = $('body').find('select[name="user"]').val();
      var county_id = $('body').find('select[name="county"]').val();
      var subcounty_id = $('body').find('select[name="subcounty"]').val();
      var ward_id=[];
      $.each($("input[name='ward[]']:checked"),function(){
          ward_id.push($(this).val());
      });

      if(user_id == ''){
        $('.user_error').html('Please select user.');
      }

      if(county_id == ''){
        $('.county_error').html('Please select county.');
      }

      if(subcounty_id == ''){
        $('.subcounty_error').html('Please select subcounty.');
      }

      if(ward_id == ''){
        $('.ward_error').html('Please select atleast one ward.');
      }

      if(valuechain_id != '' && county_id != '' && subcounty_id != '' && ward_id != '' && user_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/assign_user_valuechain_locations',
          type: 'POST',
          dataType : 'json',
          data: {
            valuechain_id : valuechain_id,
            county_id : county_id,
            subcounty_id : subcounty_id,
            ward_id : ward_id,
            user_id : user_id
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
               $('.subcounty').val('');
              $('.ward_select').html('');
              

              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');

            }
          }
        });

        $('body').on('click', '.edit', function(){
          var valuechains = [];
      
          var valuechain_id = $('body').find('select[name="valuechain"]').val("1");
          var user_id = $('body').find('select[name="user"]').val();
          var county_id = $('body').find('select[name="county"]').val();
          var subcounty_id = $('body').find('select[name="subcounty"]').val();
          var ward_id = $('body').find('select[name="ward[]"]').val();
        });
      }
    });
  });
</script>