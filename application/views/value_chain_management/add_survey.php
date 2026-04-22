<style>
 .vertical-layout{
    margin-top: 10px;
   }
</style>


<div class="app-content content" style="margin-left: 0px; margin-bottom: 50px;">
  <div class="content-wrapper">
    <div class="content-body">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>includeout/formbuilder/css/meta_form_builder.css">
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
        <h4 class="title">Create activity</h4>
      </div>
      <div class="col-md-12 mt-10">
        <div class="">
          <div class="card" style="padding-bottom: 10px;">
            <p id="message"></p>
            <form class="form-group" style="margin-bottom: 50px;">
              <div class="col-md-12">
                <label class="bt">Survey Title<font color="red">*</font></label>
                <input type="text" name="title" class="form-control" placeholder="Survey title" style="margin-top: 0px;">
              </div>
              <div class="col-md-12 mt-10">
                <label class="bt">Survey description<font color="red">*</font></label>
                <textarea class="form-control" name="subject" placeholder="Survey Description" style="resize: none;"></textarea>
                <!-- <input type="text" name="subject" class="form-control" placeholder="Subject" style="margin-top: 0px;"> -->
              </div>

              <div class="col-md-12 mt-10">
                <label class="bt">Enable location</label><br>
                <input type="checkbox" name="checkbox" id="agree" /><label for="agree"> Please select the checkbox to enable the location while submitting the survey</label>
                <p class="term_checkbox_error red-800"></p>
              </div>

              <div class="col-md-12">
                <label>Maximum number of images allowed</label>
                <select class="form-control" name="images_count">
                  <option value="">Select images count</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
            </form>
            <div class="col-md-12">
              <div>
                <div id="stage1" class="build-wrap" style="border: 1px solid gray; border-radius: 5px;"></div>
                <form class="render-wrap"></form>
                <?php if(base_url() == 'http://52.40.207.123/icrisatproduction/'){ ?>
                  <button id="save" type="button" class="btn btn-success pull-right btn-md mt-10" disabled><i class="ft-plus"></i> Add Survey</button>
                <?php }else{ ?>
                  <button id="save" type="button" class="btn btn-success pull-right btn-md mt-10" disabled><i class="ft-plus"></i> Add Survey</button>
                <?php } ?>
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
  $(function() {
    var actionButtons = [{
      id: 'smile',
      className: 'btn btn-success',
      label: '😁',
      type: 'button',
      events: {
        click: function() {
          alert('😁😁😁 !SMILE! 😁😁😁');
        }
      }
    }];

    var typeUserDisabledAttrs = {
      autocomplete: ['access']
    };

    var typeUserAttrs = {
      text: {
        className: {
          label: 'Class',
          options: {
            'red form-control': 'Red',
            'green form-control': 'Green',
            'blue form-control': 'Blue'
          },
          style: 'border: 1px solid red'
        }
      },

      //textarea
      text: {
        maxlength: {
          label: 'Max Length',
          options: {
            '1': '1',
            '10': '10',
            '100': '100',
            '250': '250'
          }
        },
        subType: {
          label: 'Type',
          options: {
            'text': 'Text',
            'email': 'Email'
          }
        }
      },

      //number
      number: {
          maxlength: {
          label: 'Max Length',
          options: {
            '15': '15',
            '20': '20',
            '25': '25',
            '30': '30'
          }
        },
        subType: {
          label: 'Type',
          options: {
            'desimal': 'Decimal',
            'number': 'Number',
            'phone': 'Phone'
          }
        }
      },

      //textarea
      textarea: {
          maxlength: {
          label: 'Max Length',
          options: {
            '1': '1',
            '10': '10',
            '100': '100',
            '500': '500',
            '1000': '1000'
          }
        }
      }
    };

    // test disabledAttrs
    var fbOptions = {
      subtypes: {
        text: ['datetime-local']
      },
      onSave: function(e, formData) {
        toggleEdit();
        $('.render-wrap').formRender({
          formData: formData,
          templates: templates
        });
        window.sessionStorage.setItem('formData', JSON.stringify(formData));
      },
      stickyControls: {
        enable: true
      },
      sortableControls: true,
      //fields: fields,
      //templates: templates,
      //inputSets: inputSets,
      typeUserDisabledAttrs: typeUserDisabledAttrs,
      typeUserAttrs: typeUserAttrs,
      disableInjectedStyle: false,
      //actionButtons: actionButtons,
      showActionButtons: false,
      disableFields: ['autocomplete','hidden','paragraph','file','button','header'],
      disabledAttrs: ['class', 'value', 'placeholder', 'rows', 'access', 'min', 'max', 'step', 'other', 'name', 'subtype', 'maxlength', 'toggle']
      // controlPosition: 'left'
      //disabledAttrs
    };

    var formBuilder = $('.build-wrap').formBuilder(fbOptions);
    var fbPromise = formBuilder.promise;
    fbPromise.then(function(fb) {
      var apiBtns = {
        showData: fb.actions.showData,
        clearFields: fb.actions.clearFields,
        getData: function() {
          $.ajax({
            url: '<?php echo base_url(); ?>ic_admin/drag_data',
            type: 'POST',
            dataType: 'json',
            data: {
              meta_data: meta_data,
              formdata: fb.actions.getData('json', true),
              title: $('input[name="title"]').val(),
              subject: $('textarea[name="subject"]').val(),
              location_val : $('[name="checkbox"]:checked').length,
              images_count : $('select[name="images_count"]').val()
            },
            error: function() {
              $('#message').html('<h4 class="red-800">There seems to be some issue from our side. Please refesh the page and try again.</h4>');
              $('#save').removeAttr('disabled', 'disabled');
            },
            success: function(data) {
              $('#save').removeAttr('disabled', 'disabled');
              if(data.msg.length > 0){
                formBuilder.actions.clearFields();
                $('input[name="title"]').val('');
                $('textarea[name="subject"]').val('');
                $('#meta_data_parent').html('');
                $('[name="checkbox"]').prop('checked',false);
                $('select[name="images_count"]').val('');
                /*$('[name="sdg[]"]').html('');
                $('[name="sdg[]"]').selectpicker("refresh");*/
                $('#message').html(data.msg);
                $('html,body').animate({
                  scrollTop: $("#message").offset().top - 300
                }, 500);
                setTimeout(function(){
                  $('.message').empty();
                }, 5000);
              }

              //Call NodeJS
              if(data.nodeData != null)
              callNode(data.nodeData);
            }
          });
        }
      };


      document.getElementById('save')
      .addEventListener('click', function(e) {
        $('#save').attr('disabled','disabled');
        $('.error').remove();
        $('#message').html('');
        var formfeild = fb.actions.getData('json', true); 
        var obj = JSON.parse(formfeild);
        var labelerror = 0;
        var requiredstatus = 0;
        
        $.each(obj, function(key,value) {
          if(typeof value.label === "undefined"){
            $('#stage1').after('<span class="error red-800">Please enter label to '+value.type+' field.<br></span>');
            labelerror++; 
          }
          if(typeof value.required !== "undefined"){
            requiredstatus++;
          }
        });
        
        var length = Object.keys(obj).length;        
        var title = $('input[name="title"]').val();
        var subject = $('textarea[name="subject"]').val();
        /*var sdg_ids = $("[name ='sdg[]']").val();*/
        if(title == ''){
          $('input[name="title"]').after('<span class="error red-800">Title is mandatory</span>');
        }else{
          /*if(!title.match(/^[a-zA-Z ]*$/)){
            $('input[name="title"]').after('<span class="error" style="color:red;">Contain only Alphabets and spaces</span>');
          }*/
        }
        if(subject == ''){
          $('textarea[name="subject"]').after('<span class="error red-800">Survey description is mandatory</span>');
        }else{
         /* if(!subject.match(/^[a-zA-Z ]*$/)){
            $('input[name="subject"]').after('<span class="error" style="color:red;">Contain only Alphabets and spaces</span>');
          }*/
        }
        if(length == 0){
          $('#stage1').after('<span class="error red-800">Select atleast one field</span>');
        }else{
          if(requiredstatus == 0){
            $("#stage1").after('<h5 class="error red-800">Please select atleast one field as required.</h5>');
          }
        }
        
        if(title != '' && subject != '' && length != 0 && labelerror == 0 && requiredstatus != 0){
          apiBtns['getData']();
        }else{
          $('#save').removeAttr('disabled','disabled');
        }
      });
    });
  });
</script>
<script src="<?php echo base_url();?>includeout/formbuilder/js/vendor.js"></script>
<script src="<?php echo base_url();?>includeout/formbuilder/js/form-builder.min.js"></script>
<script src="<?php echo base_url();?>includeout/formbuilder/js/form-render.min.js"></script>  
<script type="text/javascript">
  $(function(){
    //Date picker
    $('body').on('focus',"#date", function(){
      $(this).datepicker();
    });

    //initialize selectpicker
    if($("[name='sdg[]']").length > 0) {
      $("[name='sdg[]']").selectpicker({
        actionsBox: true,
        liveSearch: true
      });
    }

    //sdg Change
    $('body').on('change', "[name ='sdg[]']", function(event) {
      //Call AJAX to load data
      var sdg_ids = $(this).val();
      if(sdg_ids == null || sdg_ids.length === 0) {
        $("#sdg_error").html('<h5 class="red-800 mb-0">Please Select Atleast One State.</h5>');
      }else{
        $("#sdg_error").empty();
      }
    });      
  });
</script>