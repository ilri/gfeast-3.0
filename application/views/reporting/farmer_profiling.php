<style type="text/css">
  p {
      margin-top: 0;
      margin-bottom: 0;
  }
  .panel-title {
      display: block;
      padding: 8px 12px;
      margin-top: 0;
      margin-bottom: 0;
      font-size: 15px;
      color: #424242;
      font-weight: bold;
  }
  .panel-body {
      position: relative;
      padding: 15px;
  }
  .red-800{
    color: red;
  }
</style>
<link rel="stylesheet" href="<?php echo base_url();?>include/vendors/intlTelInput/build/css/intlTelInput.css">
<script type="text/javascript">  
  function getchild_field(field_id, field_value, calltype, class_name) {
    if(typeof class_name !== 'undefined'){
      var classname = class_name; 
    }else{
      var classname = 'childof'+field_id;
    }
    $.ajax({
      url: "<?php echo base_url(); ?>reporting/get_childfields",
      type: "POST",
      dataType: "json",
      data : {
         field_id : field_id,
         field_value : field_value,
         survey_id : 1,
         calltype : calltype
      },
      error : function(){
        $('html,body').animate({
            scrollTop: $('.'+classname).offset().top - 300
        }, 500);
        $('.'+classname).html('<p align="center" class="red-800">Please check your internet connection and try again</p>');
        setTimeout(function(){
            $('.'+classname).empty();
        }, 5000);
      },
      success : function (response) {
        if(response.status == 1){
          if(response.child_field.length > 0){
            var CHILD_HTML = '';
            for(var field of response.child_field) {
              CHILD_HTML += '<div class="col-md-12">';
                switch (field.type){
                  case 'radio-group' :
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){ 
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        CHILD_HTML += '<div class="form-check">\
                          <div class="row">';
                            field.options.forEach(function(option, optionindex ){
                              CHILD_HTML += '<div class="col-md-4">';
                                var requiredval = (field.required == 1) ? "required" : "notrequired";
                                CHILD_HTML += '<label class="radio-inline" >\
                                  <input type="radio" name="field_'+field.field_id+'" value = "'+option.value+'" style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" >'+option.label+'\
                                </label>\
                              </div>';
                            });
                          CHILD_HTML += '</div>\
                        </div>';
                        CHILD_HTML += '<p class="error red-800"></p>\
                      </div>\
                    </div>\
                    <div class="col-md-12">\
                      <div class="row childfields childof'+field.field_id+'">';
                      CHILD_HTML += '</div>\
                    </div>';
                    break;

                  case 'lkp_yesno' :
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){ 
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        CHILD_HTML += '<div class="form-check">\
                          <div class="row">';
                            field.yesno_options.forEach(function(option, optionindex ){
                              CHILD_HTML += '<div class="col-md-4">';
                                var requiredval = (field.required == 1) ? "required" : "notrequired";
                                CHILD_HTML += '<label class="radio-inline" >\
                                  <input type="radio" name="field_'+field.field_id+'" value = "'+option.id+'" style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.id+'" data-required = "'+requiredval+'" >'+option.name+'\
                                </label>\
                              </div>';
                            });
                          CHILD_HTML += '</div>\
                        </div>';
                        CHILD_HTML += '<p class="error red-800"></p>\
                      </div>\
                    </div>\
                    <div class="col-md-12">\
                      <div class="row childfields childof'+field.field_id+'">';
                      CHILD_HTML += '</div>\
                    </div>';
                    break;

                  case 'checkbox-group' :
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){ 
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        CHILD_HTML += '<div class="form-check">\
                          <div class="row">';
                            field.options.forEach(function(option, optionindex ){
                              CHILD_HTML += '<div class="col-md-4">';
                                var radioclass = (field.inline == "true" || field.inline == "TRUE") ? 'radio-inline' : '';
                                var inputradioclass = (field.className != '') ? field.className : '';
                                var requiredval = (field.required == 1) ? "required" : "notrequired";
                                CHILD_HTML += '<label class="'+radioclass+'" >\
                                  <input type="checkbox" name="field_'+field.field_id+'[]"  class="'+inputradioclass+'" value = "'+option.value+'" style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" >'+option.label+'\
                                </label>\
                              </div>';
                            });
                          CHILD_HTML += '</div>\
                        </div>';
                        CHILD_HTML += '<p class="error red-800"></p>\
                      </div>\
                    </div>\
                    <div class="col-md-12">\
                      <div class="row childfields childof'+field.field_id+'">';
                      CHILD_HTML += '</div>\
                    </div>';
                    break;
              
                  case 'number':
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var inputclass = (field.className != '') ? field.className : '';
                        var requiredval = (field.required == 1) ? "required" : "notrequired";

                        switch (field.subtype) {
                          case 'desimal': 
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class=" '+inputclass+' decimal" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'"  >';
                          break;

                          case 'number':
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class=" '+inputclass+' number" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                          break;

                          case 'latitude':
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class=" '+inputclass+' latlong" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                          break;

                          case 'longitude':
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class=" '+inputclass+' latlong" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                          break;
                          
                          default:
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class=" '+inputclass+' numberfield" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                          break;
                        }
                        CHILD_HTML += '<p class="error red-800"></p>\
                        <p class="maxlengtherror red-800"></p>\
                      </div>\
                    </div>';
                    break;

                  case 'text' :
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var inputclass = (field.className != '') ? field.className : '';
                        var requiredval = (field.required == 1) ? "required" : "notrequired";
                        if(field.subtype == 'datetime-local'){
                          CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' datetimepicker5" >';
                        }else{
                          CHILD_HTML += '<input type="'+field.subtype+'" name="field_'+field.field_id+'" class="'+inputclass+'" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                        }
                        CHILD_HTML += '<p class="error red-800"></p>\
                        <p class="maxlengtherror red-800"></p>\
                      </div>\
                    </div>';
                    break;

                  case 'select':
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var requiredval = (field.required == 1) ? "required" : "notrequired";
                        if(field.multiple == 'true' || field.multiple == 'TRUE'){
                          CHILD_HTML += '<select name="field_'+field.field_id+'[]" multiple class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" >';
                        }else{
                          CHILD_HTML += '<select name="field_'+field.field_id+'" class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" >\
                          <option value="">Select an option</option>';
                        }
                        field.options.forEach(function(option, optionindex){                      
                          if(option.selected == "true" || option.selected == "TRUE"){
                            var optionselected = "selected";
                          }else{
                            var optionselected = "";
                          }
                          CHILD_HTML +='<option value = "'+option.value+'" '+optionselected+'>'+option.label+'</option>';
                        });
                        CHILD_HTML += '</select>\
                        <p class="error red-800"></p>\
                      </div>\
                    </div>';
                    break;

                  case 'lkp_technologytype':
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var requiredval = (field.required == 1) ? "required" : "notrequired";
                        if(field.multiple == 'true' || field.multiple == 'TRUE'){
                          CHILD_HTML += '<select name="field_'+field.field_id+'[]" multiple class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" >';
                        }else{
                          CHILD_HTML += '<select name="field_'+field.field_id+'" class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" >\
                          <option value="">Select an option</option>';
                        }
                        field.technologytype_options.forEach(function(option, optionindex){                      
                          CHILD_HTML +='<option value = "'+option.technologytype_id+'">'+option.technologytype_name+'</option>';
                        });
                        CHILD_HTML += '</select>\
                        <p class="error red-800"></p>\
                      </div>\
                    </div>';
                    break;

                  case 'header':
                    CHILD_HTML += '<div class="col-md-12">';
                      switch (field.subtype) {
                        case 'h1': 
                          CHILD_HTML += '<h1 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h1>';
                          break;

                        case 'h2':
                          CHILD_HTML += '<h2 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h2>';
                          break;

                        case 'h3':
                          CHILD_HTML += '<h3 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h3>';
                          break;

                        case 'h4':
                          CHILD_HTML += '<h4 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h4>';
                          break;

                        case 'h5':
                          CHILD_HTML += '<h5 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h5>';
                          break;
                      }
                    CHILD_HTML += '</div>';
                    break;

                  case 'date':
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var inputclass = (field.className != '') ? field.className : '';
                        var requiredval = (field.required == 1) ? "required" : "notrequired";
                        
                        if(field.subtype == 'datetime-local'){
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' datetimepicker5" >';
                        }else{
                            CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' picker" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" autocomplete="off" onkeydown="return false">';
                        }
                        CHILD_HTML += '<p class="error red-800"></p>\
                        <p class="maxlengtherror red-800"></p>\
                      </div>\
                    </div>';
                    break;

                  case 'month':
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var inputclass = (field.className != '') ? field.className : '';
                        var requiredval = (field.required == 1) ? "required" : "notrequired";
                        CHILD_HTML += '<div class="row">\
                          <div class="col-md-6">';
                            if(field.subtype == 'datetime-local'){
                              CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' datetimepicker5" >';
                            }else{
                              CHILD_HTML += '<input type="text" name="field_'+field.field_id+'" class="'+inputclass+' monthpicker" data-subtype = "'+field.subtype+'" data-required = "'+requiredval+'" autocomplete="off" onkeydown="return false">';
                            }
                          CHILD_HTML += '</div>\
                        </div>\
                        <p class="error red-800"></p>\
                        <p class="maxlengtherror red-800"></p>\
                      </div>\
                    </div>';
                    break;

                  case 'textarea' :
                    CHILD_HTML += '<div class="col-md-6">\
                      <div class="form-group">\
                        <label>'+field.label;
                          if(field.required == 1){ 
                            CHILD_HTML += '<font color="red">*</font>';
                          }
                        CHILD_HTML += '</label>';
                        if(field.description != null){
                          CHILD_HTML += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                        }
                        var inputclass = (field.className != '') ? field.className : '';
                        var requiredval = (field.required == 1) ? "required" : "notrequired";
                        CHILD_HTML += '<textarea name="field_'+field.field_id+'" rows="8" class="'+inputclass+'" data-subtype="'+field.subtype+'" data-maxlength = "'+field.maxlength+'" data-required="'+requiredval+'"></textarea>';
                        CHILD_HTML += '<p class="error red-800"></p>\
                        <p class="maxlengtherror red-800"></p>\
                      </div>\
                    </div>';
                    break;
                }
              CHILD_HTML += '</div>';
            };

            $('.'+classname).html(CHILD_HTML);

            //Date picker
            $('.picker').datepicker({
              format: 'yyyy-mm-dd',
              autoclose: true
            });

            //month picker
            $('.monthpicker').datepicker({
              format: 'yyyy-mm',
              autoclose: true,
              viewMode: "months", 
              minViewMode: "months"
            });
          }
        }else{
          $('html,body').animate({
              scrollTop: $('.'+classname).offset().top - 300
          }, 500);
          $('.'+classname).html('<p align="center" class="red-800">'+response.msg+'</p>');
          setTimeout(function(){
              $('.'+classname).empty();
          }, 5000);
        }
      }
    });
  }
</script>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-top: 10px;">
      <div class="row">
        <div class="col-md-12">
          <div class="error_msg"></div>
        </div>
        <div class="col-md-12">
          <div class="required_ajax_message"></div>
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
  				<h4 style="font-weight: bold;">Farmer profiling</h4>
  			</div>

        <div class="col-md-12 mt-10">
          <form id="formdata">
            <div class="content-body card" style="padding: 20px;">  
              <div id="surveyform">         
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Select County</label>
                      <select class="form-control" name="county">
                        <option value="">Select County</option>
                        <?php foreach ($user_county_data as $key => $county) { ?>
                          <option value="<?php echo $county['county_id']; ?>"><?php echo $county['name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Select Subcounty</label>
                      <select class="form-control" name="subcounty">
                        <option value="">Select Subcounty</option>
                        
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Select Ward</label>
                      <select class="form-control" name="ward">
                        <option value="">Select Ward</option>
                        
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <?php $i = 1;
                  $groupfields_count = 0;
                  foreach ($get_surveyfields as $formfieldskey => $value) {
                    $formfield = "field_".$value['field_id'];
                    if($value['parent_id'] == null){
                      switch ($value['type']) {
                        case 'group': ?>
                          <div class="col-md-12 mb-10 mt-10">
                            <div class="panel panel-default" style="border: 1px solid #1e9ff2; margin: 0px; font-weight: bold; margin-bottom: 10px;">
                              <div class="panel-heading">
                                <h4 class="panel-title expand title">
                                  <span class="pull-right panel-collapse-clickable" data-toggle="collapse" data-parent="#panel<?php echo $value['field_id']; ?>" href="#<?php echo $value['field_id']; ?>">
                                    <i class="icon-plus success float-right"></i>
                                  </span>
                                  <?php $groupquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                  <a data-toggle="collapse" data-parent="#panel<?php echo $value['field_id']; ?>" href="#<?php echo $value['field_id']; ?>" style="text-decoration: none;">
                                    <?php echo ($value['field_count'] == 1) ? $textquestion.". ".$value['label'] : $value['label']; ?>
                                  </a>
                                </h4>
                              </div>
                              <div id="<?php echo $value['field_id']; ?>" class="panel-collapse panel-collapse collapse <?php echo ($groupfields_count == 0) ? "show" : ""; ?>">
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-6" style="margin-top: -10px; margin-bottom: -20px;">
                                      <div class="form-group">
                                        <label><?php echo ($value['description'] == NULL || $value['description'] == '') ? "Enter count to repeat the fields" : $value['description']; ?></label>
                                        <input type="text" name="group_<?php echo $value['field_id']; ?>" class="form-control groupcount" data-groupid = "<?php echo $value['field_id']; ?>" data-required="required">
                                        <p class="error red-800"></p>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row group<?php echo $value['field_id']; ?>_div mt-20 groupfields"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php $groupfields_count++;
                          break;

                        //display of text box field
                        case 'scanner': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $textquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $textquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <input type="<?php echo $value['subtype']; ?>" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> hhid_check" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>">
                                  <p class="error red-800"></p>
                                  <p class="maxlengtherror red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;


                        case 'text':
                          if($value['subtype'] != 'tel'){ ?>
                            <div class="col-md-12">
                              <div class="form-group">
                                <?php $textquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                                <label><?php echo ($value['field_count'] == 1) ? $textquestion.". ".$value['label'] : $value['label'];
                                  echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                                </label>
                                <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                                <div class="row">
                                  <div class="col-md-6">
                                    <?php if($value['subtype'] == 'datetime-local'){ ?>
                                        <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> datetimepicker5" >
                                      <?php  }else{ ?>
                                        <input type="<?php echo $value['subtype']; ?>" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>">
                                      <?php  } ?>
                                    <p class="error red-800"></p>
                                    <p class="maxlengtherror red-800"></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php }
                          break;

                        //display date field
                        case 'date': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $datequestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $datequestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> picker" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;

                        //display date field
                        case 'month': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $monthquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $monthquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> monthpicker" onkeydown="return false" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;
                        
                        //display number field
                        case 'number': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $numberquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $numberquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <?php switch ($value['subtype']) {
                                    case 'desimal': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> decimal" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                      <?php break;

                                    case 'number': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> number" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                      <?php break;

                                    case 'latitude': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> latlong" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                      <?php break;

                                    case 'longitude': ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> latlong" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                      <?php break;

                                    case 'phone': ?>
                                      <input type="tel" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> phone" style="width: 600px;" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                      <?php break;
                                    
                                    default: ?>
                                      <input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> numberfield" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required = "<?php echo ($value['required'] == 1) ? 'required' : 'notrequired'; ?>"  >
                                      <?php break;
                                  } ?>
                                  <p class="error red-800"></p>
                                  <p class="maxlengtherror red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php break;

                        //display radio button
                        case 'radio-group': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $radioquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $radioquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; 
                              if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ ?>
                                <div class="form-check">
                                  <div class="row">
                                    <?php foreach ($value['options'] as $key => $option) { ?>
                                      <div class="col-md-4">
                                        <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "radio-inline"; } ?>" >
                                          <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                            $radio_value = "checked"; 
                                          }else{
                                            $radio_value = '';
                                          } ?>
                                          <input type="radio" name="field_<?php echo $value['field_id']; ?>"  class="<?php if($value['className'] != ''){ echo $value['className']; }  ?>" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ echo "checked"; } ?> style="margin-right: 5px;" data-field_id = "<?php echo $value['field_id']; ?>" data-field_value = "<?php echo $option['value']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $radio_value; ?>>
                                          <span><?php echo $option['label'] ?></span>
                                        </label>
                                      </div>
                                    <?php } ?>
                                  </div>
                                </div>
                              <?php }else{ ?>
                                <div class="row">
                                  <?php foreach ($value['options'] as $key => $option) { ?>
                                    <div class="col-md-4">
                                      <div class="form-check">
                                        <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "radio-inline"; } ?>" >
                                          <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                            $radio_value = "checked"; 
                                          }else{
                                            $radio_value = '';
                                          } ?>
                                          <input type="radio" name="field_<?php echo $value['field_id']; ?>"  class="<?php if($value['className'] != ''){ echo $value['className']; }  ?>" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ echo "checked"; } ?> style = "margin-right: 5px;" data-field_id = "<?php echo $value['field_id']; ?>" data-field_value = "<?php echo $option['value']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $radio_value; ?> >
                                          <span><?php echo $option['label'] ?></span>
                                        </label>
                                      </div>
                                    </div>
                                  <?php } ?>
                                </div>
                              <?php } ?> 
                              <p class="error red-800"></p>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                            </div>
                          </div>
                          <?php break;

                        //display checkbox
                        case 'checkbox-group': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $checkboxquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $checkboxquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; 
                              if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ ?>
                                <div class="form-radio row">
                                  <?php foreach ($value['options'] as $key => $option) { ?>
                                    <div class="col-md-4">
                                      <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "checkbox-inline"; } ?>" >
                                        <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                          $checkbox_value = "checked"; 
                                        }else{
                                          $checkbox_value = '';
                                        } ?>
                                        <input type="checkbox" name="field_<?php echo $value['field_id']; ?>[]"  class="<?php if($value['className'] != ''){ echo $value['className']; }  ?>" value = "<?php echo $option['value']; ?>" data-field_id = "<?php echo $value['field_id']; ?>"  <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ echo "checked"; } ?> style = "margin-right: 5px;" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $checkbox_value; ?> >
                                          <span><?php echo $option['label'] ?></span>
                                      </label>
                                    </div>
                                  <?php } ?>
                                </div>
                              <?php }else{
                                foreach ($value['options'] as $key => $option) { ?>
                                  <div class="form-radio row">
                                    <div class="col-md-4">
                                      <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "checkbox-inline"; } ?>" >
                                        <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                          $checkbox_value = "checked"; 
                                        }else{
                                          $checkbox_value = '';
                                        } ?>
                                        <input type="checkbox" name="field_<?php echo $value['field_id']; ?>[]"  class="<?php if($value['className'] != ''){ echo $value['className']; }  ?>" value = "<?php echo $option['value']; ?>" data-field_id = "<?php echo $value['field_id']; ?>"  <?php if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ echo "checked"; } ?> style = "margin-right: 5px;" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" <?php echo $checkbox_value; ?> >
                                        <span><?php echo $option['label'] ?></span>
                                      </label>
                                    </div>
                                  </div>
                                <?php }
                              } ?>
                              <p class="error red-800"></p>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                            </div>
                          </div>
                          <?php break;

                        //display of textarea
                        case 'textarea': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $textareaquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $textareaquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>                        <div class="row">
                                  <div class="col-md-6">
                                    <textarea name="field_<?php echo $value['field_id']; ?>" rows="8" class="<?php echo $value['className']; ?>" data-subtype="<?php echo $value['subtype']; ?>" data-maxlength = "<?php echo $value['maxlength']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>"><?php echo (isset($record_details)) ? $record_details[$formfield]  : ''; ?></textarea>
                                    <p class="error red-800"></p>
                                    <p class="maxlengtherror red-800"></p>
                                  </div>
                              </div>
                            </div>
                                </div>
                          <?php break;

                        //display of select box
                        case 'select': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <?php if($value['multiple'] == 'true' || $value['multiple'] == 'TRUE'){ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>[]" multiple class="form-control" <?php if($value['required'] == 1){ echo "required"; } ?> data-required = "<?php if($value['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" data-field_id = "<?php echo $value['field_id']; ?>" >
                                  <?php  }else{ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>" class="form-control" <?php if($value['required'] == 1){ echo "required"; } ?> data-required = "<?php if($value['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" data-field_id = "<?php echo $value['field_id']; ?>">
                                    <option value="">Select an option</option>
                                  <?php  }
                                  foreach ($value['options'] as $key => $option) {
                                    if($option['selected'] == 'true' || $option['selected'] == 'TRUE'){ 
                                      $select_value = "selected"; 
                                    }else{
                                      $select_value = '';
                                    } ?>
                                    <option value = "<?php echo $option['value']; ?>" <?php echo $select_value; ?> ><?php echo $option['label']; ?></option> <?php
                                  } ?>
                                  </select>
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                            </div>
                          </div>
                          <?php break;

                        //display of select box
                        case 'lkp_education': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <?php if($value['multiple'] == 'true' || $value['multiple'] == 'TRUE'){ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>[]" multiple class="form-control" <?php if($value['required'] == 1){ echo "required"; } ?> data-required = "<?php if($value['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" data-field_id = "<?php echo $value['field_id']; ?>" >
                                  <?php  }else{ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>" class="form-control" <?php if($value['required'] == 1){ echo "required"; } ?> data-required = "<?php if($value['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" data-field_id = "<?php echo $value['field_id']; ?>">
                                    <option value="">Select an option</option>
                                  <?php  }
                                  foreach ($value['education_options'] as $key => $option) { ?>
                                    <option value = "<?php echo $option['education_id']; ?>" ><?php echo $option['education_name']; ?></option> 
                                  <?php } ?>
                                  </select>
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                            </div>
                          </div>
                          <?php break;

                        //display of select box
                        case 'lkp_school': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <?php foreach ($value['school_options'] as $key => $option) { ?>
                                  <div class="col-md-4">
                                    <div class="form-check">
                                      <label class="<?php if($value['inline'] == 'true' || $value['inline'] == 'TRUE'){ echo "radio-inline"; } ?>" >
                                        <input type="radio" name="field_<?php echo $value['field_id']; ?>"  value = "<?php echo $option['school_id']; ?>" style = "margin-right: 5px;" data-field_id = "<?php echo $value['field_id']; ?>" data-field_value = "<?php echo $option['school_id']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
                                        <span><?php echo $option['school_choice'] ?></span>
                                      </label>
                                    </div>
                                  </div>
                                <?php } ?>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                            </div>
                          </div>
                          <?php break;

                        //display of select box
                        case 'lkp_value_chain': ?>
                          <div class="col-md-12">
                            <div class="form-group">
                              <?php $selectquestion = ($value['field_count'] == 1) ? $i++ : $i; ?>
                              <label><?php echo ($value['field_count'] == 1) ? $selectquestion.". ".$value['label'] : $value['label'];
                                echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
                              </label>
                              <?php echo ($value['description'] != NULL) ? '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '.$value["description"].'</p>' : ''; ?>
                              <div class="row">
                                <div class="col-md-6">
                                  <?php if($value['multiple'] == 'true' || $value['multiple'] == 'TRUE'){ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>[]" multiple class="form-control" <?php if($value['required'] == 1){ echo "required"; } ?> data-required = "<?php if($value['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" data-field_id = "<?php echo $value['field_id']; ?>" >
                                  <?php  }else{ ?>
                                    <select name="field_<?php echo $value['field_id']; ?>" class="form-control" <?php if($value['required'] == 1){ echo "required"; } ?> data-required = "<?php if($value['required'] == 1){ echo 'required'; }else{ echo 'notrequired'; } ?>" data-field_id = "<?php echo $value['field_id']; ?>">
                                    <option value="">Select an option</option>
                                  <?php  }
                                  foreach ($value['value_chain_options'] as $key => $option) { ?>
                                    <option value = "<?php echo $option['value_chain_id']; ?>" ><?php echo $option['value_chain_name']; ?></option> 
                                  <?php } ?>
                                  </select>
                                  <p class="error red-800"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="row childfields childof<?php echo $value['field_id']; ?>">
                            </div>
                          </div>
                          <?php break;

                        //display of header
                        case 'header': ?>
                          <div class="col-md-12">
                            <?php switch ($value['subtype']) {
                              case 'h1': ?>
                                <h1 class="title" style="margin-top: 0px; margin-bottom: 20px;     margin-left: 3px;"><?php echo $value['label']; ?></h1>
                              <?php  break;

                              case 'h2': ?>
                                  <h2 class="title" style="margin-top: 0px; margin-bottom: 20px;     margin-left: 3px;"><?php echo $value['label']; ?></h2>
                              <?php  break;

                              case 'h3': ?>
                                  <h3 class="title" style="margin-top: 0px; margin-bottom: 20px;     margin-left: 3px;"><?php echo $value['label']; ?></h3>
                              <?php  break;

                              case 'h4': ?>
                                  <h4 class="title" style="margin-top: 0px; margin-bottom: 20px;     margin-left: 3px;"><?php echo $value['label']; ?></h4>
                              <?php  break;

                              case 'h5': ?>
                                  <h5 class="title" style="margin-top: 0px; margin-bottom: 20px;     margin-left: 3px;"><?php echo $value['label']; ?></h5>
                              <?php  break;
                            } ?>
                          </div>
                          <?php break;
                      }
                    }
                  } ?>

                  <!-- <div class="col-md-12" style="margin-top: 10px;">
                    <div class="form-group">
                      <label>Farmer image</label>
                      <input type="file" name="survey_images" id="surv_images" />
                      <div class="help-block pull-right" id="holder" style="border:1px solid #6cc00c;"></div>
                      <p style="font-size: 10px; font-style: italic; color: gray;">
                        File size must be less than 5MB<br/>
                        Only image file types are allowed
                      </p>
                      <p class="error red-800" id="si_err"></p>
                    </div>
                  </div> -->
                  <div class="col-md-12 text-center hidden">
                    <div class="loading">
                      <img src="<?php echo base_url(); ?>includeout/images/pleasewait.gif" style="width: 200px; height: 40px;">
                    </div>
                  </div> 
                </div>
              </div>
            </div>
            <div class="row"></div>
            <div class="row" style="margin-bottom: 40px;">
              <div class="col-md-12">
                <button name="submit" class="pull-right btn btn-success pull-up" style="margin-left:10px;border-radius:10px;"><i class="fa fa-upload" aria-hidden="true"></i> Submit Data</button>
              </div>
            </div>
          </form> 
        </div>       
  		</div>
    </div>
  </div>
</div>

<script src="<?php echo base_url();?>include/vendors/intlTelInput/build/js/intlTelInput.js"></script>


<script type="text/javascript">
  $(function(){
    $('body').on('keyup', '.groupcount', function(){
      var element = $(this);
      var groupid = element.data('groupid');
      var groupcount = element.val();
      var classname = "group"+groupid+"_div";
      $('.'+classname).html('<img src="<?php echo base_url(); ?>includeout/images/loading.gif" style="width: 130px; height: 35px;">');
      if(groupcount > 0){
        $.ajax({
          url: "<?php echo base_url(); ?>reporting/get_fieldsbygroupid",
          type: "POST",
          dataType: "json",
          data : {
            groupid : groupid,
            groupcount : groupcount,
            survey_id : 1
          },
          success : function(response){
            if(response.status == 0){
              element.val('');
              $('.'+classname).html('<div class="col-md-6">\
                <div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+response.msg+'</div>\
              </div>');
            }else{
              if(response.survey_groupfields.length > 0){
                var GROUP_FIELDS = '';
                for(i=0 ; i < groupcount ; i++){
                  GROUP_FIELDS += '<div class = "col-md-12"><label style="background-color: #ff0000; width: 30px; text-align: center; color: #FFFFFF !important; margin-bottom: 0px;">'+(i+1)+'</label><hr style="margin-top: 0px; border: none; height: 3px; background-color: #8e8ec0;"></div>';
                  var j = 1;
                  for(var field of response.survey_groupfields) {
                    var jval_upper = convertToNumerals(j);
                    var jval = jval_upper.toLowerCase();
                    switch (field.type){
                      case 'radio-group' :
                        GROUP_FIELDS += '<div class="col-md-12">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){ 
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            GROUP_FIELDS += '<div class="form-check">\
                              <div class="row">';
                                field.options.forEach(function(option, optionindex ){
                                  GROUP_FIELDS += '<div class="col-md-4">';
                                    var radioclass = (field.inline == "true" || field.inline == "TRUE") ? "radio-inline" : "notrequired";
                                    var inputradioclass = (field.className != '') ? field.className : '';
                                    var selectedvalue = (option.selected == 'true' || option.selected == 'TRUE') ? "checked" : "";
                                    var requiredval = (field.required == 1) ? "required" : "notrequired";
                                    GROUP_FIELDS += '<label class="'+radioclass+'" >\
                                      <input type="radio" name="field_'+field.field_id+'['+i+']"  class="'+inputradioclass+'" value = "'+option.value+'" '+selectedvalue+' style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" data-fieldtype = "groupfield" data-groupcount = "'+(i+1)+'">'+option.label+'\
                                    </label>\
                                  </div>';
                                });
                              GROUP_FIELDS += '</div>\
                            </div>';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                          </div>\
                        </div>';
                        if(field.child_count != 0){
                          GROUP_FIELDS += '<div class="col-md-12">\
                            <div class="row childfields childof'+field.field_id+'_'+(i+1)+'">';
                            
                            GROUP_FIELDS += '</div>\
                          </div>';
                        }
                        break;

                      case 'lkp_gender' :
                        GROUP_FIELDS += '<div class="col-md-12">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){ 
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            GROUP_FIELDS += '<div class="form-check">\
                              <div class="row">';
                                field.gender_options.forEach(function(option, optionindex ){
                                  GROUP_FIELDS += '<div class="col-md-3">';
                                    var radioclass = (field.inline == "true" || field.inline == "TRUE") ? "radio-inline" : "notrequired";
                                    var requiredval = (field.required == 1) ? "required" : "notrequired";
                                    GROUP_FIELDS += '<label class="'+radioclass+'" >\
                                      <input type="radio" name="field_'+field.field_id+'['+i+']" value = "'+option.id+'"  style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.id+'" data-required = "'+requiredval+'" data-fieldtype = "groupfield" data-groupcount = "'+(i+1)+'">'+option.type+'\
                                    </label>\
                                  </div>';
                                });
                              GROUP_FIELDS += '</div>\
                            </div>';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                          </div>\
                        </div>';
                        if(field.child_count != 0){
                          GROUP_FIELDS += '<div class="col-md-12">\
                            <div class="row childfields childof'+field.field_id+'_'+(i+1)+'">';
                            
                            GROUP_FIELDS += '</div>\
                          </div>';
                        }
                        break;

                      case 'lkp_respondentritn' :
                        GROUP_FIELDS += '<div class="col-md-12">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){ 
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            GROUP_FIELDS += '<div class="form-check">\
                              <div class="row">';
                                field.respondentritn_options.forEach(function(option, optionindex ){
                                  GROUP_FIELDS += '<div class="col-md-3">';
                                    var radioclass = (field.inline == "true" || field.inline == "TRUE") ? "radio-inline" : "notrequired";
                                    var requiredval = (field.required == 1) ? "required" : "notrequired";
                                    GROUP_FIELDS += '<label class="'+radioclass+'" >\
                                      <input type="radio" name="field_'+field.field_id+'['+i+']" value = "'+option.id+'"  style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.id+'" data-required = "'+requiredval+'" data-fieldtype = "groupfield" data-groupcount = "'+(i+1)+'">'+option.relationship+'\
                                    </label>\
                                  </div>';
                                });
                              GROUP_FIELDS += '</div>\
                            </div>';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                          </div>\
                        </div>';
                        if(field.child_count != 0){
                          GROUP_FIELDS += '<div class="col-md-12">\
                            <div class="row childfields childof'+field.field_id+'_'+(i+1)+'">';
                            
                            GROUP_FIELDS += '</div>\
                          </div>';
                        }
                        break;

                      case 'checkbox-group' :
                        GROUP_FIELDS += '<div class="col-md-12">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){ 
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            GROUP_FIELDS += '<div class="form-check">\
                              <div class="row">';
                                field.options.forEach(function(option, optionindex ){
                                  GROUP_FIELDS += '<div class="col-md-4">';
                                    var radioclass = (field.inline == "true" || field.inline == "TRUE") ? 'radio-inline' : '';
                                    var inputradioclass = (field.className != '') ? field.className : '';
                                    var selectedvalue = (option.selected == 'true' || option.selected == 'TRUE') ? "checked" : "";
                                    var requiredval = (field.required == 1) ? "required" : "notrequired";
                                    GROUP_FIELDS += '<label class="'+radioclass+'" >\
                                      <input type="checkbox" name="field_'+field.field_id+'['+i+'][]"  class="'+inputradioclass+'" value = "'+option.value+'" '+selectedvalue+' style="margin-right: 5px;" data-field_id = "'+field.field_id+'" data-field_value = "'+option.value+'" data-required = "'+requiredval+'" data-fieldtype = "groupfield" data-groupcount = "'+(i+1)+'">'+option.label+'\
                                    </label>\
                                  </div>';
                                });
                              GROUP_FIELDS += '</div>\
                            </div>';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                          </div>\
                        </div>';
                        if(field.child_count != 0){
                          GROUP_FIELDS += '<div class="col-md-12">\
                            <div class="row childfields childof'+field.field_id+'_'+(i+1)+'">';
                            GROUP_FIELDS += '</div>\
                          </div>';
                        }
                        break;

                      case 'number':
                        GROUP_FIELDS += '<div class="col-md-4">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            var inputclass = (field.className != '') ? field.className : '';
                            var requiredval = (field.required == 1) ? "required" : "notrequired";

                            switch (field.subtype) {
                              case 'desimal': 
                                  GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class=" '+inputclass+' decimal" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'"  >';
                              break;

                              case 'number':
                                  GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class=" '+inputclass+' number" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                              break;

                              case 'latitude':
                                  GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class=" '+inputclass+' latlong" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                              break;

                              case 'longitude':
                                  GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class=" '+inputclass+' latlong" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                              break;
                              
                              default:
                                  GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class=" '+inputclass+' numberfield" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                              break;
                              }
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                            <p class="maxlengtherror red-800"></p>\
                          </div>\
                        </div>';
                        break;

                      case 'text' :
                        GROUP_FIELDS += '<div class="col-md-4">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            var inputclass = (field.className != '') ? field.className : '';
                            var requiredval = (field.required == 1) ? "required" : "notrequired";
                            GROUP_FIELDS += '<input type="'+field.subtype+'" name="field_'+field.field_id+'[]" class="'+inputclass+'" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" >';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                            <p class="maxlengtherror red-800"></p>\
                          </div>\
                        </div>';
                        break;

                      case 'select':
                        GROUP_FIELDS += '<div class="col-md-4">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            var requiredval = (field.required == 1) ? "required" : "notrequired";
                            if(field.multiple == 'true' || field.multiple == 'TRUE'){
                              GROUP_FIELDS += '<select name="field_'+field.field_id+'['+i+'][]" multiple class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" data-fieldtype = "groupfield" data-groupcount = "'+(i+1)+'">';
                            }else{
                              GROUP_FIELDS += '<select name="field_'+field.field_id+'[]" class="form-control" data-required = "'+requiredval+'" data-field_id = "'+field.field_id+'" data-fieldtype = "groupfield" data-groupcount = "'+(i+1)+'">\
                                  <option value="">Select an option</option>';
                            } 
                            field.options.forEach(function(option, optionindex){
                              var optionselected = (option.selected == "true" || option.selected == "TRUE") ? "selected" : "";
                              GROUP_FIELDS +='<option value = "'+option.value+'" '+optionselected+'>'+option.label+'</option>';
                            });
                            GROUP_FIELDS += '</select>\
                            <p class="error red-800"></p>\
                          </div>\
                        </div>';
                        if(field.child_count != 0){
                          GROUP_FIELDS += '<div class="col-md-12">\
                            <div class="row childfields childof'+field.field_id+'_'+(i+1)+'">';
                            GROUP_FIELDS += '</div>\
                          </div>';
                        }
                        break;

                      case 'header':
                        GROUP_FIELDS += '<div class="col-md-12">';
                          switch (field.subtype) {
                            case 'h1': 
                              GROUP_FIELDS += '<h1 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h1>';
                            break;

                            case 'h2':
                                GROUP_FIELDS += '<h2 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h2>';
                            break;

                            case 'h3':
                                GROUP_FIELDS += '<h3 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h3>';
                            break;

                            case 'h4':
                                GROUP_FIELDS += '<h4 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h4>';
                            break;

                            case 'h5':
                                GROUP_FIELDS += '<h5 class="title" style="margin-top: 0px; margin-bottom: 20px;">'+field.label+'</h5>';
                            break;
                          }
                        GROUP_FIELDS += '</div>';
                        break;

                      case 'date':
                        GROUP_FIELDS += '<div class="col-md-4">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            var inputclass = (field.className != '') ? field.className : '';
                            var requiredval = (field.required == 1) ? "required" : "notrequired";
                            GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class="'+inputclass+' picker" data-subtype = "'+field.subtype+'" data-maxlength ="'+field.maxlength+'" data-required = "'+requiredval+'" autocomplete="off" onkeydown="return false">';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                            <p class="maxlengtherror red-800"></p>\
                          </div>\
                        </div>';
                        break;

                      case 'month':
                        GROUP_FIELDS += '<div class="col-md-4">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            var inputclass = (field.className != '') ? field.className : '';
                            var requiredval = (field.required == 1) ? "required" : "notrequired";
                            GROUP_FIELDS += '<input type="text" name="field_'+field.field_id+'[]" class="'+inputclass+' monthpicker" data-subtype = "'+field.subtype+'" data-required = "'+requiredval+'" autocomplete="off" onkeydown="return false">';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                            <p class="maxlengtherror red-800"></p>\
                          </div>\
                        </div>';
                        break;

                      case 'textarea' :
                        GROUP_FIELDS += '<div class="col-md-12">\
                          <div class="form-group">\
                            <label>('+jval+') '+field.label+''+(field.required == 1 ? "<font color='red'>*</font>" : "")+'</label>';
                            if(field.description != null){
                              GROUP_FIELDS += '<p style="font-size: 10px; font-style: italic; color: gray;">Note: '+field.description+'</p>';
                            }
                            var inputclass = (field.className != '') ? field.className : '';
                            var requiredval = (field.required == 1) ? "required" : "notrequired";
                            GROUP_FIELDS += '<textarea name="field_'+field.field_id+'[]" rows="8" class="'+inputclass+'" data-subtype="'+field.subtype+'" data-maxlength = "'+field.maxlength+'" data-required="'+requiredval+'"></textarea>';
                            GROUP_FIELDS += '<p class="error red-800"></p>\
                            <p class="maxlengtherror red-800"></p>\
                          </div>\
                        </div>';
                        break;                          
                    }
                    j++;
                  };
                }
                $('.'+classname).html(GROUP_FIELDS);

                //Date picker
                $('.picker').datepicker({
                   format: 'yyyy-mm-dd',
                   autoclose: true
                });

                //month picker
                $('.monthpicker').datepicker({
                  format: 'yyyy-mm',
                  autoclose: true,
                  viewMode: "months", 
                  minViewMode: "months"
                });
              }
            }
          }
        });
      }else{
        $('.'+classname).html('<div class="col-md-6">\
          <div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Field value must be greater than 0.</div>\
        </div>');
        $(this).val('');
      }
    });

    $('body').on('blur', '.hhid_check', function(){
      $elem = $(this);
      var hhid = $elem.val();

      if(hhid != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>reporting/hhid_check',
          type: 'POST',
          dataType : 'json',
          data: {
            hhid : hhid
          },
          error: function() {
            $('.required_ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $elem.closest('.form-group').find('.error').html('<p>'+response.msg+'</p>');

              $elem.val('');
            }
          }
        });
      }
    });



    $('body').on('change', 'select[name="county"]', function(){
      $elem = $(this);
      var county_id = $elem.val();

      $.ajax({
        url : '<?php echo base_url(); ?>reporting/get_users_subcounty',
        type: 'POST',
        dataType : 'json',
        data: {
          county_id : county_id
        },
        error: function() {
          $('.required_ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
        },
        success : function(response){
          if(response.status == 0){
            $('.required_ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>');
          }else{
            var HTML_DATA = '<option value="">Select Subcounty</option>';
            response.get_users_subcounty.forEach(function(subcounty, index){
              HTML_DATA += '<option value="'+subcounty.sub_county_id+'">'+subcounty.sub_county_name+'</option>';
            });

            $('select[name="subcounty"]').html(HTML_DATA);
          }
        }
      });
    });

    $('body').on('change', 'select[name="subcounty"]', function(){
      $elem = $(this);
      var subcounty_id = $elem.val();
      var county_id = $('select[name="county"]').val();

      if(subcounty_id != '' && county_id != ''){
        $.ajax({
          url : '<?php echo base_url(); ?>reporting/get_users_ward',
          type: 'POST',
          dataType : 'json',
          data: {
            subcounty_id : subcounty_id,
            county_id : county_id
          },
          error: function() {
            $('.required_ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success : function(response){
            if(response.status == 0){
              $('.required_ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              var HTML_DATA = '<option value="">Select Ward</option>';
              response.get_users_ward.forEach(function(ward, index){
                HTML_DATA += '<option value="'+ward.ward_id+'">'+ward.ward_name+'</option>';
              });

              $('select[name="ward"]').html(HTML_DATA);
            }
          }
        });
      }
    });

    //expand and collapse
    $('body').on('click', '.expand, .hide_child', function(){
      $elem = $(this);
      if($elem.hasClass('expand')){
        $elem.removeClass('expand');
        $elem.addClass('hide_child');
        $elem.parent().next('div').removeClass('collapse');
      } else {
        $elem.removeClass('hide_child');
        $elem.addClass('expand');
        $elem.parent().next('div').addClass('collapse');
      }      
    });   

    //intializing inttel to phone field
    $(".phone").intlTelInput({
      allowExtensions: true,
      autoFormat: true,
      nationalMode: false,
      numberType: "MOBILE",
      onlyCountries: ["KE"],
      utilsScript: "<?php echo base_url() ?>include/vendors/intlTelInput/build/js/utils.js"
    });

    //inline validation for phone number field
    var phonenumbererror = 0;
    // on blur: validate
    $('body').on('blur', '.phone', function(){
      phonenumbererror = 0;
      var telInput = $(this);
      if ($.trim(telInput.val())) {
        if (telInput.intlTelInput("isValidNumber")) {
          $('.error').html('');
          $('.phonenumber').html('<span id="valid-msg" style="color: #00C900;">✓ Valid</span>');
        } else {
          $('.error').html('');
          $('.phonenumber').html('<span id="error-msg">Invalid number</span>');
          phonenumbererror++;
        }
      }
    });

    //Date picker
    $('.picker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

      //month picker
    $('.monthpicker').datepicker({
      format: 'yyyy-mm',
      autoclose: true,
      viewMode: "months", 
      minViewMode: "months"
    });

    //to check the value is number or not
    $('body').on('keyup', '.numberfield', function(){
      $(this).closest('.form-group').find('.error').html('');
      if($(this).val().length > 0){
        if (!/^(\+|-)?(\d*\.?\d*)$/.test(this.value)) { // a non–digit was entered
          $(this).closest('.form-group').find('.error').html('This field contains only numbers and perfect decimals.');
          $(this).val('');
        }else{
          $(this).closest('.form-group').find('.error').empty();
        }
      }
    });

    //to check value is perfect decimal number or not
    $('body').on('keyup', '.decimal', function(){
      $(this).closest('.form-group').find('.error').html('');
      if($(this).val().length > 0){
        if(!/^(\d*\.?\d*)$/.test($(this).val())){
          $(this).closest('.form-group').find('.error').html('Please! Enter only number');
        }else if (!/^[0-9]+(\.\d{2})?$/.test($(this).val())) {
          $(this).closest('.form-group').find('.error').html('Field can contain only proper decimal number.');
        }
      }
    });

    //to check value is perfect decimal number or not
    $('body').on('keyup', '.latlong', function(){
      $(this).closest('.form-group').find('.error').html('');
      if($(this).val().length > 0 && ($(this).val() != 0)){
        if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
          $(this).closest('.form-group').find('.error').empty();
        } else {
          $(this).closest('.form-group').find('.error').html('Please provide a valid number.');
        }
      }
    });

    //to check value is perfect number
    $('body').on('keyup', '.number', function(){
      $(this).closest('.form-group').find('.error').html('');
      if($(this).val().length > 0){
        if (/^\d+$/.test($(this).val())) {
          $(this).closest('.form-group').find('.error').empty();
        } else {
          $(this).val('');
          $(this).closest('.form-group').find('.error').html('Please provide a valid number.');
        }
      }
    });

    //to get child fields on change of radio field 
    $('body').on('change', 'input[type=radio]', function() {
      var field_id = $(this).attr("data-field_id");
      var field_value = $(this).attr("data-field_value");
      var fieldtype = $(this).attr("data-fieldtype");
      var groupcount = $(this).attr("data-groupcount");

      if(fieldtype == 'groupfield' && typeof fieldtype !== 'undefined'){
        var classname = 'childof'+field_id+'_'+groupcount;
      }else{
        var classname = 'childof'+field_id;
      }
      $('.'+classname).html('');
      var calltype = 'onchange';
      getchild_field(field_id, field_value, calltype, classname);
    });

    //to get child fields on change of checkbox field 
    $('body').on('change', 'input[type=checkbox]', function() {
      var field_id = $(this).attr("data-field_id");
      var name = $(this).attr("name");
      var calltype = 'onchange';
      var fieldtype = $(this).attr("data-fieldtype");
      var groupcount = $(this).attr("data-groupcount");

      if(fieldtype == 'groupfield' && typeof fieldtype !== 'undefined'){
        var classname = 'childof'+field_id+'_'+groupcount;
      }else{
        var classname = 'childof'+field_id;
      }
      $('.'+classname).html('');

      var checkedvalues = [];
      $.each($("input[name='"+name+"']:checked"), function(){
        checkedvalues.push($(this).val());
      });

      var field_value = checkedvalues;
      if(field_value != ''){
        getchild_field(field_id, field_value, calltype);
      }
    });

    //to get child fields on change of selectbox field 
    $('body').on('change', 'select', function() {
      var field_id = $(this).attr("data-field_id");
      var name = $(this).attr("name");
      var calltype = 'onchange';
      var fieldtype = $(this).attr("data-fieldtype");
      var groupcount = $(this).attr("data-groupcount");

      if(fieldtype == 'groupfield' && typeof fieldtype !== 'undefined'){
        var classname = 'childof'+field_id+'_'+groupcount;
      }else{
        var classname = 'childof'+field_id;
      }

      $('.'+classname).html('');
      var checkedvalues = [];
      $.each($("option:selected", this) , function(){
        checkedvalues.push($(this).val());
      });
      var field_value = checkedvalues;

      if(field_value != ''){
        getchild_field(field_id, field_value, calltype, classname);
      }
    });

    //form submission
    $('button[name="submit"]').on('click', function (event) {
      event.preventDefault();
      $('button[name="submit"]').prop('disabled', true);
      $('.error').html('');
      $('.maxlengtherror').html('');
      var metacount = 0;
      var surveycount = 0;
      var imageerror = 0;

      $('input[type=text]', '#formdata').each(function() {
        var requiredvalue = $(this).data("required");
        var subtypevalue = $(this).data("subtype");
        var maxvalue = $(this).data("maxlength");

        if(requiredvalue == 'required'){
          if($.trim($(this).val()).length === 0){
            $(this).closest('.form-group').find('.error').html('This field is required');
            surveycount++;
          }
        }

        switch (subtypevalue){
          case 'numberfield':
            if($(this).val().length > 0){
              if (!/^(\+|-)?(\d*\.?\d*)$/.test(this.value)) { // a non–digit was entered
                $(this).closest('.form-group').find('.error').html('This field contains only numbers and perfect decimals.');
                surveycount++;
              }else{
                $(this).closest('.form-group').find('.error').empty();
              }
            }
            break;

          case 'number':
            if($(this).val().length > 0){
              if (/^\d+$/.test($(this).val())) {
                $(this).closest('.form-group').find('.error').empty();
              } else {
                $(this).val('');
                $(this).closest('.form-group').find('.error').html('Please provide a valid number.');
                surveycount++;
              }
            }
            break;

          case 'latitude':
            if($.trim($(this).val()).length > 0  && ($(this).val() != 0)){
              if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
                $(this).closest('.form-group').find('.error').empty();
              } else {
                $(this).closest('.form-group').find('.error').html('Please provide a valid number.');
                surveycount++;
              }
            }
            break;

          case 'longitude':
            if($.trim($(this).val()).length > 0  && ($(this).val() != 0)){
              if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
                $(this).closest('.form-group').find('.error').empty();
              } else {
                $(this).closest('.form-group').find('.error').html('Please provide a valid number.');
                surveycount++;
              }
            }
            break;

          case 'altitude':
            if($.trim($(this).val()).length > 0  && ($(this).val() != 0)){
              if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
                $(this).closest('.form-group').find('.error').empty();
              } else {
                $(this).closest('.form-group').find('.error').html('Please provide a valid number.');
                surveycount++;
              }
            }
            break;

          case 'desimal':
            if($(this).val().length > 0){
              if(!/^(\d*\.?\d*)$/.test($(this).val())){
                $(this).closest('.form-group').find('.error').html('Please! Enter only number');
                surveycount++;
              }else if (!/^[0-9]+(\.\d{2})?$/.test($(this).val())) {
                $(this).closest('.form-group').find('.error').html('Field can contain only proper decimal number.');
                surveycount++;
              }
            }
            break;
        }

        if($(this).val().length > maxvalue){
          $(this).closest('.form-group').find('.maxlengtherror').html('Please! Enter upto '+maxvalue+' character/number');
          surveycount++;
        }
      });

      $('input[type=tel]', '#formdata').each(function() {
        var requiredvalue = $(this).data("required");
        var subtypevalue = $(this).data("subtype");
        var maxvalue = $(this).data("maxlength");

        if(requiredvalue == 'required'){
          if($.trim($(this).val()).length === 0){
            $(this).closest('.form-group').find('.error').html('This field is required');
            surveycount++;
          }
        }

        var telInput = $(this);
        if ($.trim(telInput.val())) {
          if (telInput.intlTelInput("isValidNumber")) {                 
          } else {
            //$('.phonenumber').html('<span id="error-msg" style="color: red;">Invalid number</span>');
            $(this).closest('.form-group').find('.phonenumber').html('');
            $(this).closest('.form-group').find('.error').html('Invalid phone number');
            surveycount++;
          }
        }     

        if($(this).val().length > maxvalue){
          $(this).closest('.form-group').find('.maxlengtherror').html('Please! Enter upto '+maxvalue+' character/number');
          surveycount++;
        }
      });

      $('textarea', '#formdata').each(function() {
        var requiredvalue = $(this).data("required");
        var subtypevalue = $(this).data("subtype");
        var maxvalue = $(this).data("maxlength");

        if(requiredvalue == 'required'){
          if($.trim($(this).val()).length === 0){
            $(this).closest('.form-group').find('.error').html('This field is required');
            surveycount++;
          }
        }

        if($(this).val().length > maxvalue){
          $(this).closest('.form-group').find('.maxlengtherror').html('Please! Enter upto '+maxvalue+' character/number');
          surveycount++;
        }
      });

      $('input[type=radio]', '#formdata').each(function() {
        var requiredvalue = $(this).data("required");
        var subtypevalue = $(this).data("subtype");
        var maxvalue = $(this).data("maxlength");
        if(requiredvalue == 'required'){
          var name = $(this).attr("name");
          if($("input:radio[name='"+name+"']:checked").length == 0){
            $(this).closest('.form-group').find('.error').html('This field is required');
            surveycount++;
          }
        }
      });

      $('select', '#formdata').each(function() {
        var requiredvalue = $(this).data("required");
        var subtypevalue = $(this).data("subtype");
        var maxvalue = $(this).data("maxlength");

        if(requiredvalue == 'required'){
          if($.trim($(this).val()).length == 0){
            $(this).closest('.form-group').find('.error').html('This field is required');
            surveycount++;
          }
        }
      });

      $('input[type=checkbox]', '#formdata').each(function() {
        var requiredvalue = $(this).data("required");
        var subtypevalue = $(this).data("subtype");
        var maxvalue = $(this).data("maxlength");

        if(requiredvalue == 'required'){
          var name = $(this).attr("name");
          if($("input:checkbox[name='"+name+"']:checked").length == 0){
            $(this).closest('.form-group').find('.error').html('This field is required');
            surveycount++;
          }
        }
      });

      if(surveycount == 0){
        var metaForm = new FormData($('#formdata')[0]);
        metaForm.append('surveystatus', 1);
        metaForm.append('form_id', 1);

        $.ajax({
          url: '<?php echo base_url(); ?>reporting/survey_addmore_insert',
          type: 'POST',
          dataType : 'json',
          data: metaForm,
          processData: false,
          contentType: false,
          error: function() {
            $('.loading').parent().addClass('hidden');
            $('.required_ajax_message').html('<div class="alert dark alert-icon alert-danger alert-dismissible" role="alert">\
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
                <span aria-hidden="true">×</span>\
              </button> Please check your internet connection and try again.\
              </div>');
            $('html,body').animate({
              scrollTop: $(".required_ajax_message").offset().top - 300
            }, 500);
            $('button[name="submit"]').prop('disabled', false);
          },
          success: function(response) {
            $('.required_ajax_message').html('');
            if(response.status == 1){
              $('.required_ajax_message').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> '+response['msg']+'</div>');
              $('html,body').animate({
                scrollTop: $(".required_ajax_message").offset().top - 300
              }, 500);
              window.setTimeout(function(){
                window.location.href = "<?php echo base_url(); ?>reporting/farmer_profiling";
              }, 3000);
              $('#surveyform input[type="tel"]').val('');
              $('#surveyform input[type="text"]').val('');
              $('#surveyform input[type="email"]').val('');
              $('#surveyform input[type="file"]').val('');
              $('#surveyform textarea').val('');
              $('.phonenumber').html('');
              $('.groupfields').html('');
              $('#surveyform input[type="checkbox"]').each(function() {
                this.checked = false;
              });
              $('#surveyform input[type="radio"]').each(function() {
                this.checked = false;
              });
              $('#holder').html('');

              $('.childfields').html('');

              $('button[name="submit"]').prop('disabled', false);

              var fields = <?php echo json_encode($get_surveyfields); ?>;

              var SELECT = '';
              fields.forEach(function(field, index) {
                var fieldid = field.field_id;
                var predefinedvalue = "field_";
                var fieldname = predefinedvalue.concat(fieldid);
                switch(field.type){
                  case 'district':
                    if(field.multiple == 'true' || field.multiple == 'TRUE'){ 
                      $('select[name="'+fieldname+'[]"] option').prop("selected", false);
                      field.districts.forEach(function(fieldlabel, index){
                        if(fieldlabel.selected == 'true' || fieldlabel.selected == 'TRUE'){
                          $('select[name="'+fieldname+'[]"] option[value="'+fieldlabel.value+'"]').prop("selected", true);
                        }
                      });
                    }else{
                      $('select[name="'+fieldname+'"] option').prop("selected", false);
                      field.districts.forEach(function(fieldlabel, index){
                        if(fieldlabel.selected == 'true' || fieldlabel.selected == 'TRUE'){
                          $('select[name="'+fieldname+'"] option[value="'+fieldlabel.value+'"]').prop("selected", true);
                        }
                      });
                    }
                    break;
                  
                  case 'select':
                    if(field.multiple == 'true' || field.multiple == 'TRUE'){ 
                      $('select[name="'+fieldname+'[]"] option').prop("selected", false);
                      field.options.forEach(function(fieldlabel, index){
                        if(fieldlabel.selected == 'true' || fieldlabel.selected == 'TRUE'){
                          $('select[name="'+fieldname+'[]"] option[value="'+fieldlabel.value+'"]').prop("selected", true);
                        }
                      });
                    }else{
                      $('select[name="'+fieldname+'"] option').prop("selected", false);
                      field.options.forEach(function(fieldlabel, index){
                        if(fieldlabel.selected == 'true' || fieldlabel.selected == 'TRUE'){
                          $('select[name="'+fieldname+'"] option[value="'+fieldlabel.value+'"]').prop("selected", true);
                        }
                      });
                    }
                    break;
                  
                  case 'radio-group':
                    $('input:radio[name="'+fieldname+'"]').removeAttr('checked');
                    field.options.forEach(function(fieldlabel, index){
                      if(fieldlabel.selected == 'true' || fieldlabel.selected == 'TRUE'){
                        $('input:radio[name="'+fieldname+'"][value="'+fieldlabel.value+'"]').prop('checked', true);
                      }
                    });
                    break;
                  
                  case 'checkbox-group':
                    $('input:checkbox[name="'+fieldname+'"]').removeAttr('checked');
                    field.options.forEach(function(fieldlabel, index){
                      if(fieldlabel.selected == 'true' || fieldlabel.selected == 'TRUE'){
                        $('input:checkbox[name="'+fieldname+'"][value="'+fieldlabel.value+'"]').prop('checked', true);
                      }
                    });
                    break;
                }
              });

              //Call NodeJS
              if(response.nodeData != null)
                callNode(response.nodeData);
            }else{
              $('.required_ajax_message').html('');
              $('.required_ajax_message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+response['msg']+'</div>');
              $('html,body').animate({
                scrollTop: $(".required_ajax_message").offset().top - 300
              }, 500);
              $('button[name="submit"]').prop('disabled', false);
            }
            $('.loading').parent().addClass('hidden');
          }
        });
      }else{
        $('html,body').animate({
          scrollTop: $('.required_ajax_message').offset().top - 200
        }, 1000);
        $('.required_ajax_message').html('<div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please fill all the mandatory fields (marked in red)</div>');
        $('button[name="submit"]').prop('disabled', false);
      }
    });
  });

  function convertToNumerals(num) {
    // Catch decimals
    num = Math.floor(num);  
    var numeralVals = {
      M: 1000,
      D: 500,
      C: 100,
      L: 50,
      X: 10,
      V: 5,
      I: 1
    };
    var numerals = Object.keys(numeralVals); // Keys in an array for easy iteration
    var result = ""; // Final roman numerals

    // For subtractive rules
    var powersOfTen = [];
    for (var exponent = 0; exponent < 6; exponent++) {
      var pow = Math.pow(10, exponent);
      powersOfTen.push(pow);
    }

    var remainder = num;

    while (remainder > 0) {
      for (var i = 0; i < numerals.length; i++) {
        var currentNumeralVal = numeralVals[numerals[i]];
        var mod = remainder % currentNumeralVal;
        var modBack = currentNumeralVal % remainder;
        var divide = remainder / currentNumeralVal;

        if (remainder - currentNumeralVal >= 0) {
          remainder -= currentNumeralVal;
          result += numerals[i];
          break;
        }

        // Subtractive rules
        // Looping from lowest to highest value to get correct subtrahend
        for (var j = (numerals.length - 1); j > i; j--) {
          var minuend = currentNumeralVal;
          var subtrahend = numeralVals[numerals[j]];

          // Only to a numeral (the subtrahend) that is a power of ten (I, X or C).
          // For example, "VL" is not a valid representation of 45 (XLV is correct).
          if (powersOfTen.indexOf(subtrahend) === -1) {
            continue;
          }
          // Only when the subtrahend precedes a minuend no more than ten times larger. 
          // For example, "IL" is not a valid representation of 49 (XLIX is correct).
          if (subtrahend * 10 < minuend) {
            continue;
          }

          var minused = minuend - subtrahend;

          if (remainder - minused >= 0) {
            remainder -= minused;
            result += numerals[j] + numerals[i];
            break;
          }
        }

        // Stop loop early if we have no remainder
        if (remainder === 0) {
          break;
        }
      }
    }
    return result;
  }
</script>