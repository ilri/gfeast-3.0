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
          <span>
            <a href="<?php echo base_url(); ?>value_chain_manangement/manage_users" class="btn btn-success btn-sm round float-md-right">Back</a>
          </span>
          <h4 style="font-weight: bold;">Manage User</h4>
  			</div>
        <div class="col-md-12 add_data hidden">
          <h4 class="title">Assign Value chain locations to user</h4>
          <div class="card p-10">
            <div class="row">
              <div class="col-md-2 valuechain_select"></div>

              <div class="col-md-2 user_select"></div>

              <div class="col-md-2 county_select"></div>

              <div class="col-md-2 subcounty_select"></div>

              <div class="col-md-2 ward_select"></div>

              <div class="col-md-2 submit_button"></div>

              <div class="col-md-12 ajax_message"></div>
            </div>
          </div>
        </div>

        <div class="col-md-12 mt-10">
          <h4 class="bold"></h4>
          <div class="card">
            <div class="card-header">
              <a href="<?php echo base_url();?>value_chain_manangement/assign_valuechain_userlocation" class="btn btn-success btn-sm round float-md-right ">Assign user location</a>
              <h4 class="bold">User Value chain locations</h4>
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
               <button class="btn btn-sm btn-success alldelete">Delete for selected Locations</button>
                <div class="table-responsive mt-10">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th><input type="checkbox" name="checkAll" id="checkAll">Select For Deletion</th>
                        <th>Value chain</th>
                        <th>Name</th>                        
                        <th>County</th>
                        <th>Sub county</th>
                        <th>Ward</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($value_chain_location_user) > 0){
                        foreach ($value_chain_location_user as $key => $value) { ?>
                          <tr class="del_<?php echo $value['value_chain_user_loc_id'];?>">
                            <td><?php echo $key+1; ?></td>
                            <td><input type="checkbox" name="users_list[]" class="usersel" value="<?php echo $value['value_chain_user_loc_id'];?>" > </td>
                            <td><?php echo $value['value_chain_name']; ?></td>
                            <td><?php echo $value['username'] ?></td>
                            <td class="county<?php echo $value['value_chain_user_loc_id'];?>"><?php echo $value['name'] ?></td>
                            <td class="sub<?php echo $value['value_chain_user_loc_id'];?>"><?php echo $value['sub_county_name']; ?></td>
                            <td class="ward<?php echo $value['value_chain_user_loc_id'];?>"><?php echo $value['ward_name']; ?></td>
                            <th><a href="javascript:void(0);"  class="edit" id="<?php echo $value['value_chain_user_loc_id'];?>">Edit</a>
                            <a href="javascript:void(0);" class="delete" id="<?php echo $value['value_chain_user_loc_id'];?>">Delete</a></th>
                          </tr>
                        <?php }
                      }else{ ?>
                        <tr>
                          <td colspan="7">No records found</td>
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
    $('body').on('click', '.assign_user_location', function(){
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
            var HTML_DATA = '';

            HTML_DATA += '<label>Select Value Chain</label>\
            <select class="form-control" name="valuechain">\
              <option value="">Select value chain</option>';
              response.get_uservaluechain.forEach(function(valuechain, index){
                HTML_DATA += '<option value="'+valuechain.value_chain_id+'">'+valuechain.value_chain_name+'</option>';
              });
            HTML_DATA += '</select>';

            $('.valuechain_select').html(HTML_DATA);

            $('.add_data').removeClass('hidden');
          }   
        }
      });
    });

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
              var HTML_DATA_user = '<label>Select User</label>\
              <select class="user form-control" name="user">\
                <option value="">Select User</option>';
                response.get_valuechain_user.forEach(function(user, index){
                  HTML_DATA_user += '<option value="'+user.user_id+'">'+user.username+'</option>';
                });
              HTML_DATA_user += '</select>';
              HTML_DATA_user += '<p class="user_error error"></p>';

              $('.user_select').html(HTML_DATA_user);

              var HTML_DATA_county = '<label>Select County</label>\
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

              HTML_DATA += '<label>Select Subcounty</label>\
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
                HTML_DATA += '<label>Select Ward</label>\
                <select class="ward form-control" multiple name="ward[]">';
                  response.get_valuechain_wards.forEach(function(ward, index){
                    HTML_DATA += '<option value="'+ward.ward_id+'">'+ward.ward_name+'</option>';
                  });
                HTML_DATA += '</select>';
                HTML_DATA += '<p class="ward_error error"></p>';

                $('.submit_button').html('<button type="button" class="btn btn-success assign_user_valuechain_locations" style="margin-top:30px;">Submit</button>');
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

    $('body').on('click', '.assign_user_valuechain_locations', function(){
      var valuechains = [];
      
      var valuechain_id = $('body').find('select[name="valuechain"]').val();
      var user_id = $('body').find('select[name="user"]').val();
      var county_id = $('body').find('select[name="county"]').val();
      var subcounty_id = $('body').find('select[name="subcounty"]').val();
      var ward_id = $('body').find('select[name="ward[]"]').val();

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
              $('.valuechain_select').html('');
              $('.county_select').html(''); 
              $('.user_select').html(''); 
              $('.subcounty_select').html('');
              $('.ward_select').html('');
              $('.submit_button').html(''); 

              $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');

              // window.setTimeout(function(){
              //   window.location.href = "<?php echo base_url(); ?>value_chain_manangement/manage_users_value_chain_location/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>";
              // }, 3000);
            }
          }
        });

      }
    });


    $('body').on('click', '.delete', function(){
          var value_chain_user_loc_id=$(this).attr('id');
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
                $('.modal-header').html(" <h4 class='modal-title title'>Delete Location</h4>");
                $('.modal-body').html(HTML_DATA);
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger btn-sm saveediteddetails" data-record_id="'+value_chain_user_loc_id+'" >Delete</button>');
                 $('#myModal').modal('show');

          });


    $('body').on('click', '.saveediteddetails', function(){ 
      var record_id=$(this).data('record_id');
      var reason=$('body').find('.reason').val();
      if(reason==''){
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
          url: '<?php echo base_url(); ?><?php echo $this->uri->segment(1);?>/delete_users_value_chain_location',
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
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
              }else{
                $('.modal-body').html('<div class="alert alert-danger">'+response.msg+'</div>');
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
              }
          }
        });
      }
    });
    $('body').on('click', '.edit', function(){ 
      var record_id=$(this).attr('id');
      var valuechain_id="<?php echo $this->uri->segment('3');?>";
      var user_id="<?php echo $this->uri->segment('4');?>";
        $.ajax({
          url: '<?php echo base_url(); ?><?php echo $this->uri->segment(1);?>/get_users_value_chain_location',
          type: 'POST',
          dataType : 'json',
          data: {
          record_id:record_id,
          valuechain_id:valuechain_id,
          user_id:user_id,
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
                $('.modal-header').html(" <h4 class='modal-title title'>Edit Location</h4>");
                $('.modal-body').html(HTML_DATA);
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-success btn-sm saveeditdetails" data-recordid="'+record_id+'">Save</button>');
                $('#myModal').modal('show');
                
              }else{
                $('.modal-body').html('<div class="alert alert-danger">'+response.msg+'</div>');
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
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
          url: '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/updatevaluechain_details',
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
 $('body').on('click','#checkAll',function () {
      console.log('1');
        if ($("#checkAll").is(':checked')) {
            $(".usersel").prop("checked", true);

        } else {
            $(".usersel").prop("checked", false);
        }
    });
 $('body').on('click','.alldelete',function(){

  var delval = new Array();
  $('.usersel:checked').each(function() {
        delval.push($(this).val());
    });
   //console.log(delval.length);
   if(delval.length>0 && delval.length != 0){
    var HTML_DATA = '<form name="editsurveydetails" id="editsurveydetails" class="mt-10">\
                <div class="row">\
                  <div class="col-md-12">\
                  <h4>Are you sure do you want to delete this record?</h4>\
                    <div class="form-group">\
                      <label>Reason to delete<span style="color:red;">*</span></label>\
                      <input type="text" name="fromdate" class="form-control reason" data-required="required" data-maxlength="250">\
                      <p class="lenerror"></p>\
                    </div>\
                  </div>\
                </div>\
              </form>';
                $('.modal-header').html(" <h4 class='modal-title title'>Delete Location</h4>");
                $('.modal-body').html(HTML_DATA);
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button><button type="button" class="btn btn-danger btn-sm savedeldetails" data-record_id="'+delval+'" >Delete</button>');
                 $('#myModal').modal('show');
              }
              else{
                $('.modal-header').html(" <h4 class='modal-title title'>Delete Location</h4>");
                $('.modal-body').html("<div style='color:red;'>Please select atleast one location to Delete</div>");
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
                 $('#myModal').modal('show');

              }

   
 });
 $('body').on('click', '.savedeldetails', function(){ 
  
      var record_ids=$(this).data('record_id');
      var reason=$('body').find('.reason').val();
      var maxlength =$('body').find('.reason').data("maxlength");
      var len=reason.length;
      if(len>maxlength || len==0)
      {
         $('body').find('.lenerror').html('length mustbe less than 250 characters.').css('color','red');
      }
      else
      {
          $.ajax({
          url: '<?php echo base_url(); ?><?php echo $this->uri->segment(1);?>/alldelete_users_value_chain_location',
          type: 'POST',
          dataType : 'json',
          data: {
          record_ids:record_ids,
          reason:reason,
        },
          error: function() {
            $('.modal-body').html('<div class="alert alert-danger">Please check your internet connection and try again</div>');
            $('.modal-load').html('');
              $('.modal-footer').html('');
          },
          success : function(response){
            if(response.status == 1){
              var check=$.inArray(',',record_ids);

              if(check != -1 ){
               var valNew=record_ids.split(',');
                for(i=0;i<valNew.length;i++){
                    var record_id=valNew[i];
                    var rec='del_'+record_id;
                $('body').find('.'+rec+'').remove();
                }
              }
              else{
                var rec='del_'+record_ids;
                 $('body').find('.'+rec+'').remove();
              }
                $('.modal-body').html('<div class="alert alert-success">'+response.msg+'</div>');
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
              }else{
                $('.modal-body').html('<div class="alert alert-danger">'+response.msg+'</div>');
                $('.modal-footer').html('<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>');
              }
          }
        });
      }
    });

</script>