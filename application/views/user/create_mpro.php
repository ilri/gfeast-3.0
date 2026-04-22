<style>
  .vertical-layout{
    margin-top: 10px;
  }
  label {
    font-weight: bold;
    color: #800000 !important;
  }
  ul {
    list-style-type: none;
    margin: 3px;
  }
  ul.checktree li:before {
    height: 1em;
    width: 12px;
    border-bottom: 1px dashed;
    content: "";
    display: inline-block;
    top: -0.3em;
  }
  ul.checktree li.blocks {
    margin-right: 50px;
    vertical-align: top;
    display: inline-block;
  }
  ul.checktree li { border-left: 1px dashed; }
  ul.checktree li.blocks:last-child { margin-right: 0; }
</style>
<link rel="stylesheet" href="<?php echo base_url(); ?>include/vendors/checkbox-tree/css/styles.css">

<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 40px;"><!-- Form wizard with number tabs section start -->
      <div class="row">
        <div class="col-12 ajax_message"></div>
        <div class="col-12">
          <h4 class="title">Create User</h4>

          <?php echo form_open('', array('id' => 'userForm', 'autocomplete' => 'off')); ?>
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <h5>Personal Details</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="firstName1">First Name<span style="color:red;">*</span></label>
                      <input type="text" class="form-control" name="first_name">
                      <p class="first_name error" style="color: red;"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lastName1">Last Name<span style="color:red;">*</span></label>
                      <input type="text" class="form-control" name="last_name" >
                      <p class="last_name error" style="color: red;"></p>
                    </div>
                  </div>
               
                  <div class="col-md-6">
                      <div class="form-group">
                        <label for="emailAddress1">Email Address<span style="color:red;">*</span></label>
                        <input type="email" class="form-control" name="email" >
                        <p class="email error" style="color: red;"></p>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="eventName1">Username<span style="color:red;">*</span></label>
                      <input type="text" class="form-control" name="username" >
                      <p class="username error" style="color: red;"></p>
                    </div>
                  </div>                                             
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="eventName1">Password<span style="color:red;">*</span></label>
                        <input type="password" class="form-control" name="password" autocomplete="off">
                        <p class="password error" style="color: red;"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="eventName1">Confirm Password<span style="color:red;">*</span></label>
                        <input type="password" class="form-control" name="cpassword" autocomplete="off">
                        <p class="cpassword error" style="color: red;"></p>
                    </div>
                  </div>
                </div>

                <h5 class="mt-2">Professional Details</h5>
                <?php if($this->session->userdata('role') < 4) { ?>
                <div class="row">
                  <div class="col-md-12"><label for="user_role">Select Type of User</label></div>
                  <div class="form-group col-md-6">
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" name="user_type" id="partnerUser" value="partner" checked>
                      <label class="custom-control-label" for="partnerUser">Partner User</label>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" name="user_type" id="orgUser" value="organization">
                      <label class="custom-control-label" for="orgUser">Organization(ICRISAT) User</label>
                    </div>
                  </div>
                </div>
                <?php } ?>
                
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="user_role">Select Role<span style="color:red;">*</span></label>
                    <select class="form-control" name="user_role">
                      <option value="">-- Select --</option>
                      <?php foreach ($all_roles as $key => $value) { if($value['role_id'] == 4 || $value['role_id'] == 5) { ?>
                      <option value="<?php echo $value['role_id'] ?>"><?php echo $value['role_name']; ?></option>
                      <?php } } ?>
                    </select>
                    <p class="user_role error" style="color: red;"></p>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="user_project">Select Project<span style="color:red;">*</span></label>
                    <select class="form-control" name="user_project[]" multiple>
                      <?php foreach ($projects as $key => $value) { ?>
                      <option value="<?php echo $value['project_id'] ?>"><?php echo $value['project_name']; ?></option>
                      <?php } ?>
                    </select>
                    <p class="user_project error" style="color: red;"></p>
                  </div>
                </div>

                <div class="form-group agency hidden">
                  <label for="user_agency">Select Agency<span style="color:red;">*</span></label>
                  <select class="form-control" name="user_agency">
                    <option value="">-- Select --</option>
                  </select>
                  <p class="user_agency error" style="color: red;"></p>
                </div>

                <div class="form-group locations">
                  <h4 class="text-center">Select An Agency To View Operating Locations</h4>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success pull-right">Add User</button>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>include/vendors/checkbox-tree/js/checktree.js"></script>
<script src="<?php echo base_url(); ?>includeout/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript">
  // Define global variable ajaxData
  var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
  
  $(function() {
    $("[name='user_project[]']").selectpicker({
      actionsBox: true
    });
    
    //Check total projects and auto select if 1
    var projects = <?php echo json_encode($projects); ?>;
    if(projects.length === 1)
    $('[name="user_project[]"]').val(projects[0].project_id).trigger('change');
  });

  // Handle user_role and user_type on change to show agency & locations
  $('body').on('change', '[name="user_role"]', function(event) {
    var user_role = $(this).val(),
    user_type = $('[name="user_type"]:checked').val();
    if($('[name="user_type"]').length === 0) user_type = 'partner';

    if(user_type == 'organization') {
      $('.agency').addClass('hidden');
    } else if(user_type == 'partner') {
      console.log(user_role.length === 0 || user_role == 3);
      if(user_role.length === 0 || user_role == 3) $('.agency').addClass('hidden');
      else $('.agency').removeClass('hidden');
    }
  }).on('change', '[name="user_type"]', function(event) {
    var elem = $(this),
    roles = <?php echo json_encode($all_roles); ?>,
    projects = <?php echo json_encode($projects); ?>;

    var roleHTML = '<option value="">-- Select --</option>';
    for(role of roles) { if(elem.val() == 'organization') {
      if(role.role_id == 3 || role.role_id == 5) {
        roleHTML += '<option value="'+role.role_id+'">'+role.role_name+'</option>';
      }
    } else if(elem.val() == 'partner') {
      if(role.role_id == 4 || role.role_id == 5) {
        roleHTML += '<option value="'+role.role_id+'">'+role.role_name+'</option>';
      }
    }}

    var projHTML = '';
    for(project of projects) {
      projHTML += '<option value="'+project.project_id+'">'+project.project_name+'</option>';
    }

    $('[name="user_role"]').html(roleHTML).val('').trigger('change');
    $('[name="user_project[]"]').html(projHTML);
    if(projects.length === 1) {$('.selectpicker').selectpicker('render');
      $('[name="user_project[]"]').val(projects[0].project_id);
    } else {
      $('[name="user_project[]"]').val(null);
    }
    $('[name="user_project[]"]').selectpicker('render');
    $('[name="user_project[]"]').trigger('change');
  });

  // Handle user_project on change to get agency
  $('[name="user_project[]"]').on('change', function(event) {
    var elem = $(this),
    user_type = $('[name="user_type"]:checked').val(),
    partnerHTML = '<option value="">-- Select --</option>';
    $('[name="user_agency"]').html(partnerHTML).val('').trigger('change');
    if($('[name="user_type"]').length === 0) user_type = 'partner';
    if(elem.val().length === 0) return false;
    
    $('button').prop('disabled', true);
    $('button[type="submit"]').html('Getting Agencies...');
    ajaxData['project_id'] = elem.val();
    $.ajax({
      url: '<?php echo base_url(); ?>projects/project_partners',
      type: 'POST',
      dataType : 'json',
      data: ajaxData,
      complete: function(data) {
        var csrfData = JSON.parse(data.responseText);
        ajaxData[csrfData.csrfName] = csrfData.csrfHash;
        if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
          $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
        }
      },
      error: function() {
        $('.ajax_message').html('<div class="alert alert-danger">Could not establish connection to server. Please refresh the page and try again.</div>');
        $('button').prop('disabled', false);
        $('button[type="submit"]').html('Add User');
      },
      success: function(response){
        $('button').prop('disabled', false);
        $('button[type="submit"]').html('Add User');
        if(response.status == 0 || response.status == 2){
          $('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>').delay(3000).fadeOut();
          $('html,body').animate({
            scrollTop: $(".ajax_message").offset().top - 300
          }, 500);
          return false;
        }
        
        var partners = [];
        for(partner of response.partners) {
          if(user_type == 'organization') {
            if(partner.status == 2) partners.push(partner);
          } else if(user_type == 'partner') {
            if(partner.status == 1) partners.push(partner);
          }
        }
        for(partner of partners) {
          partnerHTML += '<option value="'+partner.partner_id+'">'+partner.partner_name+'</option>'
        }
        $('[name="user_agency"]').html(partnerHTML);

        if(partners.length === 1)
        $('[name="user_agency"]').val(partners[0].partner_id).trigger('change');
      }
    });
  });

  // Handle user_agency on change to get agency locations
  $('[name="user_agency"]').on('change', function(event) {
    var elem = $(this),
    user_type = $('[name="user_type"]:checked').val();
    if($('[name="user_type"]').length === 0) user_type = 'partner';
    
    if(elem.val().length === 0) {
      if(user_type == 'partner') {
        $('.locations').html('<h4 class="text-center">Select An Agency To View Operating Locations</h4>');
      } else {
        $('.locations').html('<h4 class="text-center">Select A Project To View Operating Locations</h4>');
      }
      return false;
    }
    
    $('button').prop('disabled', true);
    $('button[type="submit"]').html('Getting Agency Locations...');
    ajaxData['user_type'] = user_type;
    ajaxData['partner_id'] = elem.val();
    $.ajax({
      url: '<?php echo base_url(); ?>partners/partner_locations',
      type: 'POST',
      dataType : 'json',
      data: ajaxData,
      complete: function(data) {
        var csrfData = JSON.parse(data.responseText);
        ajaxData[csrfData.csrfName] = csrfData.csrfHash;
        if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
          $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
        }
      },
      error: function() {
        $('.ajax_message').html('<div class="alert alert-danger">Could not establish connection to server. Please refresh the page and try again.</div>');
        $('button').prop('disabled', false);
        $('button[type="submit"]').html('Add User');
      },
      success: function(response){
        $('button').prop('disabled', false);
        $('button[type="submit"]').html('Add User');
        if(response.status == 0){
          $('.ajax_message').html('<div class="alert alert-danger">'+response.msg+'</div>').delay(3000).fadeOut();
          $('html,body').animate({
            scrollTop: $(".ajax_message").offset().top - 300
          }, 500);
          return false;
        }
        
        var locationHTML = `<label>Tag Locations To User<span style="color:red;">*</span></label>`;
        for(country of response.countries) {
          locationHTML += `<ul class="checktree">
            <li>
              <input id="country_${country.id}" type="checkbox" />
              <label for="country_${country.id}">${country.country}</label>
              <ul>`;
              for(state of country.states) {
                locationHTML += `<li>
                  <input id="state_${state.id}" type="checkbox" />
                  <label for="state_${state.id}">${state.state}</label>
                  <ul>`;
                  for(district of state.districts) {
                    locationHTML += `<li>
                      <input id="district_${district.id}" type="checkbox" />
                      <label for="district_${district.id}">${district.district}</label>
                      <ul>`;
                      for(block of district.blocks) {
                        locationHTML += `<li class="blocks">
                          <input id="block_${block.id}" type="checkbox" />
                          <label for="block_${block.id}">${block.block}</label>
                          <ul>`;
                          for(village of block.villages) {
                            locationHTML += `<li>
                              <input id="village_${village.id}" type="checkbox" name="villages[]" value="${village.id}" />
                              <label for="village_${village.id}">${village.village}</label>
                            </li>`;
                          }
                          locationHTML += `</ul>
                        </li>`;
                      }
                      locationHTML += `</ul>
                    </li>`;
                  }
                  locationHTML += `</ul>
                </li>`;
              }
              locationHTML += `</ul>
            </li>
          </ul>
          <p class="villages error" style="color: red;"></p>`;
        }
        $('.locations').html(locationHTML);
        $('ul.checktree').checktree();
      }
    });
  });

  // Handle user form submit
  $('#userForm').on('submit', function(event) {
    event.preventDefault();
    var elem = $(this);
    $('.error').empty();
    $('button').prop('disabled', true);
    $('button[type="submit"]').html('Please wait...');

    var form = $(this)
    formData = new FormData($(this)[0]);
    $.ajax({
      url: '<?php echo base_url(); ?>users/insert_user/',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      complete: function(data) {
        var csrfData = JSON.parse(data.responseText);
        ajaxData[csrfData.csrfName] = csrfData.csrfHash;
        if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
          $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
        }
      },
      error: function() {
        $('button').prop('disabled', false);
        $('button[type="submit"]').html('Add User');
        $.toast({
          heading: 'Network Error!',
          text: 'Could not establish connection to server. Please refresh the page and try again.',
          icon: 'error'
        });
      },
      success: function(data) {
        var data = JSON.parse(data);

        // If session error exists
        if(data.session_err == 1) {
          $.toast({
            heading: 'Session Error!',
            text: data.msg,
            icon: 'error'
          });

          $('button').prop('disabled', false);
          $('button[type="submit"]').html('Add User');
        }

        // If validation error exists
        if(data.status > 0) {
          for(var key in data) {
            var errorContainer = form.find(`.${key}.error`);
            if(errorContainer.length !== 0) {
              errorContainer.html(data[key]);
            }
          }
          $('button').prop('disabled', false);
          $('button[type="submit"]').html('Add User');
        }

        if(data.insertstatus == 1) {
          // If update completed
          $.toast({
            heading: 'Success!',
            text: data.msg,
            icon: 'success',
            afterHidden: function () {
              window.location.href = '<?php echo base_url(); ?>users/create';
            }
          });
        } else if(data.insertstatus == 0) {
          $.toast({
            heading: 'Error!',
            text: data.msg,
            icon: 'error'
          });
          $('button').prop('disabled', false);
          $('button[type="submit"]').html('Add User');
        }
      }
    });
  });
</script>
