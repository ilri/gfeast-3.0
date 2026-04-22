<style>
  .vertical-layout{
    margin-top: 10px;
   }
</style>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-bottom: 40px;">
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
          <a href="javascript:void(0);" class="btn btn-success round float-md-right assign_survey">Assign survey</a>
  				<h4 style="font-weight: bold;">Manage Survey</h4>
  			</div>

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

        <div class="col-md-12 mt-10 add_data hidden">
          <h4 class="title">Assign surveys to value chain</h4>
          <div class="card p-10">
            <div class="row">
              <div class="col-md-3 survey">
                
              </div>

              <div class="col-md-8 valuechain_list">
                
              </div>

              <div class="col-md-1 submit_button">
                
              </div>

              <div class="col-md-12 ajax_message">
                
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12 mt-10">
          <h4 class="bold"></h4>
          <div class="card">
            <div class="card-header">              
              <!-- <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
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
                        <th>Survey name</th>                        
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($form) > 0){
                        foreach ($form as $key => $value) { ?>
                          <tr>
                            <th scope="row"><?php echo $key+1; ?></th>
                            <td><?php echo $value['value_chain_name']; ?></td>
                            <td><?php echo $value['title']; ?></td>                            
                            <td><a href="javascript:void(0);" class="manage_surveys" data-surveyid = "<?php echo $value['id']; ?>">Edit</a></td>
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
    $('body').on('click', '.assign_survey', function(){
      $('.ajax_message').html('');
      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_allsurvey',
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

            HTML_DATA += '<label>Select Survey</label>\
            <select class="survey form-control" name="survey">\
              <option value="">Select Survey</option>';
              response.form_list.forEach(function(form, index){
                HTML_DATA += '<option value="'+form.id+'">'+form.title+'</option>';
              });
            HTML_DATA += '</select>';

            $('.add_data').removeClass('hidden');
            $('.survey').html(HTML_DATA);
          }
        }
      });
    });

    $('body').on('change', 'select[name="survey"]', function(){
      $elem = $(this);
      var survey_id = $elem.val();

      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/get_surveydetails',
        type: 'POST',
        dataType : 'json',
        data: {
          survey_id : survey_id
        },
        error: function() {
          $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            var HTML_DATA = '';
            HTML_DATA += '<div class="col-md-12 mb-10">\
              <label>List of value chains</label>\
            </div>';

            HTML_DATA += '<div class="col-md-12">\
              <div class="row">';
                response.value_chain_list.forEach(function(valuechain, index){
                  HTML_DATA += '<div class="col-md-4">\
                    <div class="checkbox">\
                      <label><input type="checkbox" name="value_chains" value="'+valuechain.value_chain_id+'" '+(jQuery.inArray(valuechain.value_chain_id, response.assigned_valuechains_array) != '-1' ? 'checked' : '')+'  > '+valuechain.value_chain_name+'</label>\
                    </div>\
                  </div>';
                });
              HTML_DATA += '</div>\
            </div>';

            $('.valuechain_list').html(HTML_DATA);

            $('.submit_button').html('<button type="button" style="margin-top: 35px;" class="btn btn-success  assign_valuechain_survey float-md-right">Submit</button>');
          }
        }
      });
    });    

    $('body').on('click', '.assign_valuechain_survey', function(){
      var valuechains = [];
      var survey_id = $('body').find('select[name="survey"]').val();

      $("input:checkbox[name=value_chains]:checked").each(function(){
        valuechains.push($(this).val());
      });

      $.ajax({
        url : '<?php echo base_url(); ?>value_chain_manangement/assign_valuechain_survey',
        type: 'POST',
        dataType : 'json',
        data: {
          survey_id : survey_id,
          valuechains : valuechains
        },
        error: function() {
          $('.add_data').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.add_data').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            $('.valuechain_list').html('');
            $('.survey').html('');
            $('.submit_button').html('');
            $('.ajax_message').html('<div class="alert alert-success">'+response.msg+'</div>');
          }
        }
      });
    });
  });
</script>