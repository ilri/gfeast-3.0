<style>
 .vertical-layout{
    margin-top: 10px;
   }
</style>


<div class="app-content content" style="margin-left: 0px; margin-bottom: 50px;">
  <div class="content-wrapper">
    <div class="content-body">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>includeout/formbuilder/css/meta_form_builder.css">
    <div class="row">
      <div class="col-md-12">
        <h4 class="title">Create Survey</h4>
      </div>
      
      <div class="col-md-12" id="message"></div>
      
      <div class="col-md-12 mt-10">
        <div class="card p-10">          
          <?php echo form_open(); ?>
            <div class="col-md-12">
              <label class="bt">Survey Title<font color="red">*</font></label>
              <input type="text" name="title" class="form-control" placeholder="Survey title" style="margin-top: 0px;">
            </div>
            <div class="col-md-12 mt-10">
              <label class="bt">Survey description<font color="red">*</font></label>
              <textarea class="form-control" name="subject" placeholder="Survey Description" style="resize: none;"></textarea>
            </div>

            <div class="col-md-12 mt-10">
              <label class="bt">Enable location</label><br>
              <input type="checkbox" name="checkbox" id="agree" /><label for="agree"> Please select the checkbox to enable the location while submitting the survey</label>
              <p class="term_checkbox_error red-800"></p>
            </div>

            <div class="col-md-12 mt-10 hidden">
              <label class="bt">Type<font color="red">*</font></label>
              <div class="row">
                <div class="col-md-2">
                  <input type="radio" name="survey_type" value="Beneficiary" id="beneficiary"><label for="beneficiary"> Beneficary</label>
                </div>

                <div class="col-md-2">
                  <input type="radio" name="survey_type" value="Survey" id="survey" checked><label for="survey"> Survey</label>
                </div>
              </div>
             
              <div class="surveytype_error"></div>
            </div>

            <div class="col-md-12 mt-10">
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
          <?php echo form_close(); ?>
          <div class="col-md-12">
            <div>
              <div id="stage1" class="build-wrap" style="border: 1px solid gray; border-radius: 5px;"></div>
              <?php echo form_open('', array('class' => 'render-wrap')); ?>
              <?php echo form_close(); ?>
              <button id="save" type="button" class="btn btn-success pull-right btn-md mt-30"><i class="ft-plus"></i> Add Survey</button>
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
            'email': 'Email',
            'encrypt': 'Encrypt'
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
            'phone': 'Phone',
            'number': 'Number',
            'desimal': 'Decimal',
            'encrypt': 'Encrypt'
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

    // Define global variable ajaxData
    var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

    var formBuilder = $('.build-wrap').formBuilder(fbOptions);
    var fbPromise = formBuilder.promise;
    fbPromise.then(function(fb) {
      var apiBtns = {
        showData: fb.actions.showData,
        clearFields: fb.actions.clearFields,
        getData: function() {
          ajaxData['formdata'] = fb.actions.getData('json', true);
          ajaxData['title'] = $('input[name="title"]').val();
          ajaxData['subject'] = $('textarea[name="subject"]').val();
          ajaxData['location_val'] = $('[name="checkbox"]:checked').length;
          ajaxData['images_count'] = $('select[name="images_count"]').val();
          ajaxData['survey_type'] = $('input[name="survey_type"]:checked').val();
          
          $.ajax({
            url: '<?php echo base_url(); ?>survey/drag_data',
            type: 'POST',
            dataType: 'json',
            data: ajaxData,
            complete: function(data) {
              var csrfData = JSON.parse(data.responseText);
              ajaxData[csrfData.csrfName] = csrfData.csrfHash;
              if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
                $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
              }
            },
            error: function() {
              $('#message').html('<h4 class="red-800">Could not establish connection to server. Please refresh the page and try again.</h4>');
              $('#save').removeAttr('disabled', 'disabled');
            },
            success: function(data) {
              $('#save').removeAttr('disabled', 'disabled');
              if(data.msg.length > 0){
                formBuilder.actions.clearFields();
                $('input[name="title"]').val('');
                $('textarea[name="subject"]').val('');
                $('[name="checkbox"]').prop('checked',false);
                $('[name="survey_type"]').prop('checked',false);
                $('select[name="images_count"]').val('');
                $('#message').html(data.msg);
                $('html,body').animate({
                  scrollTop: $("#message").offset().top - 300
                }, 500);
                setTimeout(function(){
                  $('.message').empty();
                }, 5000);
              }

              //Call NodeJS
              if(data.nodeData != null) {
                callNode(data.nodeData);
              }
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
        var title = $.trim($('input[name="title"]').val());
        var subject = $.trim($('textarea[name="subject"]').val());
        var survey_type = $('input[name="survey_type"]:checked').val();

        if(title == ''){
          $('input[name="title"]').after('<span class="error red-800">Title is mandatory</span>');
        }
        
        if(subject == ''){
          $('textarea[name="subject"]').after('<span class="error red-800">Survey description is mandatory</span>');
        }
        
        if(length == 0){
          $('#stage1').after('<span class="error red-800">Select atleast one field</span>');
        }else{
          if(requiredstatus == 0){
            $("#stage1").after('<h5 class="error red-800">Please select atleast one field as required.</h5>');
          }
        }

        if(typeof survey_type === 'undefined'){
          $('.surveytype_error').html('<span class="error red-800">This field is mandatory</span>');
        }
        
        if(title != '' && subject != '' && length != 0 && labelerror == 0 && requiredstatus != 0 && typeof survey_type !== 'undefined'){
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
  });
</script>