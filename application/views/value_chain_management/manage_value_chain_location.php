<style type="text/css">
  .error{
    color: red;
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
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title title">Delete Record</h4>
               
            </div>
            
            <div class="modal-body">
                
            </div>
            <div class="modal-load" style="text-align: center;"></div>
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
          <a href="<?php echo base_url();?>value_chain_manangement/assign_valuechain_loc" class="btn btn-success round float-md-right">
            <i class="ft-plus"></i> Assign location
          </a>
  				<h4 style="font-weight: bold;">Value chain locations</h4>
  			</div>

        <!-- <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div class="content-body">
              <div class="row">
                <div class="col-md-12">
                  <label class="bold">Select Value Chain</label>
                  <select class="form-control" name="value_chain">
                    <option value="">All</option>
                    <?php foreach ($get_value_chain_locations['value_chain_list'] as $key => $value) { ?>
                      <option value="<?php echo $value['value_chain_id']; ?>"><?php echo $value['value_chain_name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div> -->
        <!-- <div class="col-md-12 mt-10 ajax_error">
          
        </div> -->

        <div class="col-md-12 add_data hidden">
          <h4 class="title">Assign location to value chain</h4>
          
          <div class="card p-10">
            <div class="row">
              <div class="col-md-3 survey_select"></div>

              <div class="col-md-3 county_select"></div>

              <div class="col-md-3 subcounty_select"></div>

              <div class="col-md-2 ward_select"></div>

              <div class="col-md-1 submit_button"></div>

              <div class="col-md-12 ajax_message">
                
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 mt-10">
          <h4 class="bold"></h4>
          <div class="card">
            <div class="card-header">
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
                        <th>County</th>
                        <th>Sub county</th>
                        <th>Ward</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody class="survey_list">
                      <?php foreach ($get_value_chain_locations['value_chain_location'] as $key => $value) { ?>
                        <tr class="del_<?php echo $value['value_chain_loc_id'];?>">
                          <td><?php echo $key+1; ?></td>
                          <td><?php echo $value['value_chain_name']; ?></td>
                          <td class="county<?php echo $value['value_chain_loc_id'];?>"><?php echo $value['name'] ?></td>
                          <td class="subcounty<?php echo $value['value_chain_loc_id'];?>"><?php echo $value['sub_county_name']; ?></td>
                          <td class="ward<?php echo $value['value_chain_loc_id'];?>"><?php echo $value['ward_name']; ?></td>
                          <th><a href="javascript:void(0);"  class="edit" id="<?php echo $value['value_chain_loc_id'];?>">Edit</a>
                            <a href="javascript:void(0);" class="delete" id="<?php echo $value['value_chain_loc_id'];?>">Delete</a></th>
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
          console.log(response.status);
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
          }
        }
      });
    });

    $('body').on('change', '.subcounty', function(){
      $('body').find('.ward_field').remove();

      $('.modal-footer').html('');

      $elem = $(this);

      var county_id = $('body').find('select[name="county"]').val();

      var subcounty_id = $elem.val();

      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_ward',
        type: 'POST',
        dataType : 'json',
        data: {
          subcounty_id : subcounty_id,
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

            HTML_DATA += '<label>Select Ward</label>\
            <select class="ward form-control" multiple name="ward[]">';
              response.get_ward_list.forEach(function(ward, index){
                HTML_DATA += '<option value="'+ward.ward_id+'">'+ward.ward_name+'</option>';
              });
            HTML_DATA += '</select>';
            HTML_DATA += '<p class="ward_error error"></p>';

            $('.ward_select').html(HTML_DATA);

            $('.submit_button').html('<button type="button" style="margin-top: 35px;" class="btn btn-success assign_valuechain_locations">Submit</button>');
          }
        }
      });
    });

    $('body').on('click', '.assign_valuechain_locations', function(){
      var valuechains = [];
      
      var valuechain_id = $('body').find('select[name="valuechain"]').val();
      var county_id = $('body').find('select[name="county"]').val();
      var subcounty_id = $('body').find('select[name="subcounty"]').val();
      var ward_id = $('body').find('select[name="ward[]"]').val();

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
              $('.subcounty_select').html('');
              $('.ward_select').html('');
              $('.submit_button').html('');              
              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
            }
          }
        });
      }
    });

    $('body').on('click', '.delete', function(){
          var value_chain_loc_id=$(this).attr('id');
             $.ajax({
          url : '<?php echo base_url(); ?>value_chain_manangement/check_valuechain_location',
          type: 'POST',
          dataType : 'json',
          data: {
            value_chain_loc_id : value_chain_loc_id,
          },
          error: function() {
            $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              if(response.count>0){
                $('.modal-body').html('<div class="alert alert-danger">We are unable to delete location.This location already assign to user.</div>');
                $('.modal-header').html(" <h4 class='modal-title title'>Delete Location</h4>");
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
                 $('#myModal').modal('show');
               }
              else{
              var HTML_DATA = '<form name="editsurveydetails" id="editsurveydetails" class="mt-10">\
                <div class="row">\
                  <div class="col-md-12">\
                  <h4>Are you sure do you want to delete this Location?</h4>\
                    <div class="form-group">\
                      <label>Reason to delete<span style="color:red;">*</span></label>\
                      <input type="text" name="fromdate" class="form-control reason" data-required="required" data-maxlength="250">\
                      <p class="reasonerror"></p>\
                      <p class="lenerror"></p>\
                    </div>\
                  </div>\
                </div>\
              </form>';
                $('.modal-body').html(HTML_DATA);
                $('.modal-header').html(" <h4 class='modal-title title'>Delete Location</h4>");
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger btn-sm saveediteddetails" data-record_id="'+value_chain_loc_id+'">Delete</button>');
                 $('#myModal').modal('show');
               }
            }
          }
        });
         

          });


    $('body').on('click', '.saveediteddetails', function(){ 
      var record_id=$(this).data('record_id');
      var reason=$('body').find('.reason').val();
      if(reason==""){
        $('body').find('.reasonerror').html('Reason field is mandatory.').css('color','red');
      }
      var maxlength =$('body').find('.reason').data("maxlength");
      var len=reason.length;
      if(len>maxlength || len==0)
      {
         $('body').find('.lenerror').html('length mustbe less than 250 characters.').css('color','red');
      }
      else
      {
          $.ajax({
          url: '<?php echo base_url(); ?><?php echo $this->uri->segment(1);?>/delete_value_chain_location',
          type: 'POST',
          dataType : 'json',
          data: {
          record_id:record_id,
          reason:reason,
        },
          error: function() {
            $('.modal-body').html('<div class="alert alert-danger">Please check your internet connection and try again</div>');
            $('.modal-load').html('');
              $('.modal-footer').html('');
          },
          success : function(response){
            if(response.status == 1){
                var rec='del_'+record_id;
                $('body').find('.'+rec+'').remove();
                $('.modal-body').html('<div class="alert alert-success">'+response.msg+'</div>');
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>');
              }else{
                $('.modal-body').html('<div class="alert alert-danger">'+response.msg+'</div>');
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>');
              }
          }
        });
      }
      
    });

    $('body').on('click', '.edit', function(){ 
      var record_id=$(this).attr('id');
      var valuechain_id="<?php echo $this->uri->segment('3');?>";
        $.ajax({
          url: '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/getvalue_chain_location',
          type: 'POST',
          dataType : 'json',
          data: {
          record_id:record_id,
          valuechain_id:valuechain_id,
        },
          error: function() {
            $('.modal-body').html('<div class="alert alert-danger">Please check your internet connection and try again</div>');
            $('.modal-load').html('');
              $('.modal-footer').html('');
          },
          success : function(response){
            if(response.status == 1){
              var data=response.userdata;
                var HTML_DATA = '<form name="editsurveydetails" id="editsurveydetails" class="mt-20">\
                  <div class="row">\
                    <div class="col-md-12">\
                      <div class="form-group">\
                        <label>County<span style="color:red;">*</span></label>\
                        <select name="county" id="county" class="form-control">';
                             HTML_DATA += '<option value="'+data.seldata.county_id +'" selected>'+data.seldata.countyname +'</option>';
                         HTML_DATA += '</select>\
                        <p class="error red-800"></p>\
                      </div>\
                    </div>\
                    <div class="col-md-12">\
                      <div class="form-group">\
                        <label>Sub County<span style="color:red;">*</span></label>\
                        <select  name="subcounty" id="subcounty" class="form-control">';
                           HTML_DATA += '<option value="'+data.seldata.subcounty_id +'" selected >'+data.seldata.subcountyname +'</option>';
                         HTML_DATA += '</select>\
                        <p class="error red-800"></p>\
                      </div>\
                    </div>\
                    <div class="col-md-12">\
                      <div class="form-group">\
                        <label>Ward<span style="color:red;">*</span></label>\
                        <select name="ward" id="ward" class="form-control">';
                        HTML_DATA += '<option value="'+data.seldata.ward_id +'" selected >'+data.seldata.wardname +'</option>';
                          data.unsdata.forEach(function(ward,index){
                            HTML_DATA += '<option value="'+ward.ward_id +'" >'+ward.ward_name +'</option>';
                          });
                        HTML_DATA += '</select>\
                        <p class="error red-800"></p>\
                      </div>\
                    </div>\
                      <div class="col-md-12">\
                    <div class="form-group">\
                      <label>Reason to Edit<span style="color:red;">*</span></label>\
                      <input type="text" name="fromdate" class="form-control reason" data-required="required" data-maxlength="250">\
                      <p class="reasonerror"></p>\
                      <p class="lenerror"></p>\
                    </div>\
                </div>\
                  </div>\
                </form>';
                $('.modal-header').html('<h4 class="modal-title title">Edit Location</h4>');
                $('.modal-body').html(HTML_DATA);
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-success btn-sm saveeditdetails" data-recordid="'+record_id+'">Save</button>');
                $('#myModal').modal('show');
                
              }else{
                $('.modal-body').html('<div class="alert alert-danger">'+response.msg+'</div>');
                $('.modal-footer').html('');
              }
          }
        });

     });

     $('body').on('click', '.saveeditdetails' ,function(){
       var metaForm = new FormData($('#editsurveydetails')[0]);
       var recordid=$(this).data("recordid");
      metaForm.append('recordid',recordid);
      var reason=$('body').find('.reason').val();
      if(reason==""){
        $('body').find('.reasonerror').html('Reason field is mandatory.').css('color','red');
      }

      var maxlength =$('body').find('.reason').data("maxlength");
      var len=reason.length;
      if(len>maxlength || len==0)
      {
         $('body').find('.lenerror').html('length mustbe less than 250 characters.').css('color','red');
      }
      else
      {
      $.ajax({

          url: '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/updatevaluechain_loc',
          type: 'POST',
          dataType : 'json',
          data: metaForm,
          processData: false,
          contentType: false,
          error: function() {
            $('.modal-body').html('<div class="alert alert-danger">Please check your internet connection and try again</div>');
            $('.modal-load').html('');
            $('.modal-footer').html('');
          },
          success : function(data){
            if(data.status == 1){
              $('.modal-header').html('');
              $('.county'+recordid).html("");
              $('.county'+recordid).html(data.countyname);
              $('.subcounty'+recordid).html("");
              $('.subcounty'+recordid).html(data.subcountyname);
              $('.ward'+recordid).html("");
              $('.ward'+recordid).html(data.wardname);
              $('.modal-body').html('<div class="alert alert-success">'+data.msg+'</div>');
              $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');

            }              
            else{

              $('.modal-body').html('<div class="alert alert-danger">Unable to update Data.</div>');
              $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
            }
          }
        });
    }
     });

  });
</script>