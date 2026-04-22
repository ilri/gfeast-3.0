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
        <div class="col-md-12">        
          <a href="<?php echo base_url();?>value_chain_manangement/manage_value_chain" class="btn btn-success round float-md-right">Back
          </a>
          <h4 style="font-weight: bold;">Assign Value chain locations</h4>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div class="content-body">
              <div class="row">
                <div class="col-md-12 ajax_message">
                </div>
                <div class="col-md-3">
                  <label class="bold">Select Value Chain</label>
                  <select class="form-control valuechain" name="value_chain">
                    <option value="">Select valuechain</option>
                     <?php if(count($user_valuechain)>0){ foreach($user_valuechain['value_chain_list'] as $valuechain){?>
                    <option value="<?php echo $valuechain['value_chain_id'];?>"><?php echo $valuechain['value_chain_name'];?></option>
                  <?php } } ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="bold">Select County</label>
                  <select class="form-control county" name="county">
                    <option value="">Select county</option>
                     <?php if(count($counties)>0){ foreach($counties as $county){?>
                    <option value="<?php echo $county['county_id'];?>"><?php echo $county['name'];?></option>
                  <?php } } ?>
                  </select>
                </div>
                <div class="col-md-3 subcounty_select"></div>

                <div class="col-md-12 ward_select"></div>

                <div class="col-md-1 submit_button"></div>
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
    $('body').on('change', 'select[name="value_chain"]', function(){
      $elem = $(this);
      var value_chain = $elem.val();
      if(value_chain == ''){
        var value_chain_val = 'all';
      }else{
        var value_chain_val = value_chain;
      }

      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/manage_surveys_byvaluechain',
        dataType : 'json',
        type : 'post',
        data : {
          value_chain_val : value_chain_val
        },
        error:function(){
          $('.ajax_error').html('<p style="padding:10px;" class="red-800">Please check your internet connection and try again.</p>');
        },
        success : function(response){
          var HTML_DATA = '';
          if(response.status == 0){
            $('.ajax_error').html('<p style="padding:10px;" class="red-800">'+response.msg+'</p>');
          }else{
            response.value_chain_location.forEach(function(location, index){
              HTML_DATA += '<tr>\
                <td>'+(index+1)+'</td>\
                <td>'+location.value_chain_name+'</td>\
                <td>'+location.name+'</td>\
                <td>'+location.sub_county_name+'</td>\
                <td>'+location.ward_name+'</td>\
                <td><a href="javascript:void(0);">Edit</a></td>\
              </tr>';
            });

            $('.survey_list').html(HTML_DATA);
          }
        }
      });
    });

    $('body').on('click', '.assign_location', function(){
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

            $('.survey_select').html(HTML_DATA);

            var HTML_DATA_county = '<label>Select County</label>\
            <select class="county form-control" name="county">\
              <option value="">Select County</option>';
              response.get_county_list.forEach(function(county, index){
                HTML_DATA_county += '<option value="'+county.county_id+'">'+county.name+'</option>';
              });
            HTML_DATA_county += '</select>';
            HTML_DATA_county += '<p class="county_error error"></p>';

            $('.county_select').html(HTML_DATA_county);
            $('.add_data').removeClass('hidden');
          }
        }
      });
    });

    $('body').on('change', '.county', function(){
      $('body').find('.subcounty_field').remove();
      $('body').find('.ward_field').remove();

      $('.modal-footer').html('');

      $elem = $(this);
      var county_id = $elem.val();
      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_subcounties',
        type: 'POST',
        dataType : 'json',
        data: {
          county_id : county_id
        },
        error: function() {
          $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            var HTML_DATA = '';

            HTML_DATA += '<label>Select Subcounty</label>\
            <select class="subcounty form-control" name="subcounty">\
              <option value="">Select Subcounty</option>';
              response.get_subcounty_list.forEach(function(subcounty, index){
                HTML_DATA += '<option value="'+subcounty.sub_county_id+'">'+subcounty.sub_county_name+'</option>';
              });
            HTML_DATA += '</select>';
            HTML_DATA += '<p class="subcounty_error error"></p>';

            $('.subcounty_select').html(HTML_DATA);
            $('.ward_select').html("");
            $('.submit_button').html("");
          }
        }
      });
  
    });

    $('body').on('change', '.subcounty', function(){
      $('body').find('.ward_field').remove();

      // $('body').find('.ward_select').remove();

      $('.modal-footer').html('');

      $elem = $(this);

      var county_id = $('body').find('select[name="county"]').val();

      var subcounty_id = $elem.val();

      var valuechain_id=$('body').find('.valuechain').val();
      
      if(county_id!="" && subcounty_id!=""){
      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_ward_byvaluechain',
        type: 'POST',
        dataType : 'json',
        data: {
          subcounty_id : subcounty_id,
          county_id : county_id,
          valuechain_id:valuechain_id,
        },
        error: function() {
          $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            var HTML_DATA = '';

            HTML_DATA += '<label>Select Ward<span style="color:red;">*</span></label>\
                <input type="checkbox" name="checkAll" id="checkAll">Check/Uncheck all\
                <div class="form-check">\
                            <div class="row">';
              if(response.get_ward_list.length>0){              
              response.get_ward_list.forEach(function(ward, index){
                HTML_DATA += '<div class="col-md-4">';
                                  HTML_DATA += '<label class="radio-inline" >\
                                    <input type="checkbox" class="ward wardsel" name="ward[]" value="'+ward.ward_id+'">'+ward.ward_name+'\
                                  </label>\
                                </div>';
                          HTML_button='<button type="button" style="margin-top: 35px;" class="btn btn-success assign_valuechain_locations">Submit</button>';
              });
            }else{
              HTML_DATA += '<div class="col-md-4">';
                                  HTML_DATA += '<p style="color:red">All wards are assigned</p></div>';
                                HTML_button='';
            }
            HTML_DATA += '</div></div>\
                <p class="ward_error error"></p>';

            $('.ward_select').html(HTML_DATA);

            $('.submit_button').html(HTML_button);
          }
        }
      });
    }
    else{
      $('body').find('.ward_select').html("");
      $('.submit_button').html("");
    }
    });

    $('body').on('click','#checkAll',function () {
        if ($("#checkAll").is(':checked')) {
            $(".wardsel").prop("checked", true);
        } else {
            $(".wardsel").prop("checked", false);
        }
    });

    $('body').on('click', '.assign_valuechain_locations', function(){
      var valuechains = [];
      
      var valuechain_id = $('body').find('select[name="value_chain"]').val();
      var county_id = $('body').find('select[name="county"]').val();
      var subcounty_id = $('body').find('select[name="subcounty"]').val();
      var ward_id=[];
      $.each($("input[name='ward[]']:checked"),function(){
          ward_id.push($(this).val());
      });

      if(county_id == ''){
        $('.county_error').html('Please select county.');
      }

      if(subcounty_id == ''){
        $('.subcounty_error').html('Please select subcounty.');
      }

      if(ward_id == ''){
        $('.ward_error').html('Please select atleast one ward.');
      }

      if(valuechain_id != '' && county_id != '' && subcounty_id != '' && ward_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/assign_valuechain_location',
          type: 'POST',
          dataType : 'json',
          data: {
            valuechain_id : valuechain_id,
            county_id : county_id,
            subcounty_id : subcounty_id,
            ward_id : ward_id
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              $('.survey_select').html('');
              $('.county_select').html('');
               $('.subcounty').val('');
              $('.ward_select').html('');
              $('.submit_button').html('');              
              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
            }
          }
        });
      }
    });
  });
</script>