<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-top: 10px;">
      <div class="row">
        <div class="col-md-12 mb-10">
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
          <h4 style="font-weight: bold;">House hold head deatils</h4>
        </div>

        <div class="col-md-12 ajax_message"></div>

        <div class="col-md-12">
          <div class="card p-10">
            <?php echo form_open('reporting/survey_details/'.$this->uri->segment(3).'/'.$this->uri->segment(4)); ?>
              <div class="row">
                <div class="col-md-4">
                  <label>HHID number</label>
                  <input type="text" class="form-control" name="hhid_number">
                  <p></p>
                </div>

                <div class="col-md-8">
                  <?php echo ($check_hhid_field == 0) ? '<button type="submit" data-buttontype="skip_data" class="btn btn-default submit_data mt-20">Skip</button>' : ''; ?>
                </div>

                <div class="col-md-12 members_list"></div>

                <div class="col-md-12 submit_button">
                  
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function(){
    $('body').on('blur', 'input[name="hhid_number"]', function(){
      $('.submit_button').html('');
      $('.members_list').html('');
      
      $elem = $(this);

      var hhid_no = $elem.val();

      if(hhid_no != ''){
        $.ajax({
          url: '<?php echo base_url(); ?>reporting/get_hhid_details',
          type: 'POST',
          dataType : 'json',
          data: {
            hhid_no : hhid_no,
          },
          error: function() {
            $('.ajax_message').html('<div class="alert alert-danger">Please check your internet connection and try again.</div>');
          },
          success: function(response){
            if(response.status == 0){
              $('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>');
            }else{
              var HTML_DATA = '';              
              HTML_DATA += '<div class="form-group">\
                <label>Select Household Member</label>\
                <div class="row">';
                  response.hhid_members.forEach(function(member, index){
                    HTML_DATA += '<div class="col-md-4">\
                      <div class="form-check">\
                        <label class="radio-inline">\
                          <input type="radio" name="household_member" value="'+member.id+'" style="margin-right: 5px;" data-field_id="1009" data-field_value="1" data-required="required">\
                          <span>'+member.name+'</span>\
                        </label>\
                      </div>\
                    </div>';
                  });
                HTML_DATA += '</div>\
              </div>';

              $('.members_list').html(HTML_DATA);

              $('.submit_button').html('<button type="submit" data-buttontype="get_data" class="btn btn-success float-md-right submit_data" style="margin-left:10px;">Next</button>');
            }
          }
        });
      }
    });

    $('body').on('click', '.submit_data', function(){
      $elem = $(this);
      var buttontype = $elem.data('buttontype');
      if(buttontype == 'get_data'){
        

        var hhid_number = $('input[name="hhid_number"]').val();
        var household_member = $('input[name="household_member"]:checked').val();

        if(hhid_number == ''){
          return false;
        }

        if(typeof household_member   == 'undefined' || household_member == ''){
          return false;
        }
      }
    });
  });
</script>