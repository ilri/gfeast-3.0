<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body">
  		<div class="row">
  			<div class="col-md-12">
  				<!-- <h4 style="font-weight: bold;">All Registration</h4> -->
  			</div>
        <div class="col-md-12">
          <h4 class="bold"></h4>
          <div class="card">
            <!-- <div class="card-header pb-0">
              <label>Select Project</label>
              <select class="form-control" name="project">
                <option value = "1" selected>Example project</option>
                <?php foreach ($projects as $key => $proj) { ?>
                <option value="<?php echo $proj['proj_id']; ?>"><?php echo $proj['proj_name']; ?></option>
                <?php } ?>
              </select>
            </div> -->
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <!-- <th rowspan="2">#</th> -->
                        <th rowspan="2">Data forms</th>
                        <!-- <th rowspan="2">Project Name</th> -->
                        <!-- <th rowspan="2">Last uploaded date</th> -->
                        <!-- <th colspan="2">Total Uploads</th> -->
                        <!-- <th rowspan="2">Created by</th> -->
                        <th rowspan="2">Submitted record count</th>
                        <!-- <th>Saved</th> -->
                        <th rowspan="2">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($all_registration) > 0){
                        foreach ($all_registration as $key => $survey) { ?>
                          <tr>
                            <!-- <td><?php echo ++$key; ?></td> -->
                            <td><?php echo $survey['title']; ?></td>
                            <!-- <td><?php echo $survey['description']; ?></td> -->
                            <!-- <td><?php echo $survey['last_updated']; ?></td> -->
                            <td><?php echo $survey['submitted_count']; ?></td>
                            <!-- <td><?php echo $survey['saved_count']; ?></td> -->
                            <td>
                              <a href="<?php echo base_url(); ?>reports/view_registration/<?php echo $survey['id']; ?>" target="_blank" class="btn btn-success btn-sm">View data</a>
                            </td>
                          </tr>
                        <?php }
                      }else{ ?>
                        <tr>
                          <td colspan="8">No data found</td>
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
  $(function() {
    var project = '<?php echo $this->uri->segment(3); ?>';
    if(project.length === 0) project = '<?php echo $projects[0]['proj_id']; ?>';
    $('[name="project"]').val(project).trigger('change');
  });
  
  // Define global variable ajaxData
  var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

  // Handle project change event
  $('body').on('change', '[name="project"]', function(event) {
    var elem = $(this);
    
    //AJAX to get data
    ajaxData['project'] = elem.val();
    $.ajax({
      url: '<?php echo base_url(); ?>reports/registration/',
      data: ajaxData,
      type: 'POST',
      dataType: 'json',
      complete: function(data) {
        var csrfData = JSON.parse(data.responseText);
        ajaxData[csrfData.csrfName] = csrfData.csrfHash;
        if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
          $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
        }
      },
      error: function() {
        $.toast({
          heading: 'Network Error!',
          text: 'Could not establish connection to server. Please refresh the page and try again.',
          icon: 'error',
          afterHidden: function () {
            form.remove();
            elem.removeClass('hidden');
          }
        });
      },
      success: function(data) {
        if(data.status == 0) {
          $.toast({
            heading: 'Error!',
            text: data.msg,
            icon: 'error'
          });
          return false;
        }

        var HTML = ``;
        for(var key in data.all_registration) {
          var survey = data.all_registration[key];
          HTML += `<tr>
            <td>${parseInt(key)+1}</td>
            <td>${survey.title}</td>
            <td>${survey.proj_name}</td>
            <td>${survey.last_updated}</td>
            <td>${survey.submitted_count}</td>
            <td>${survey.saved_count}</td>
            <td>
              <a href="<?php echo base_url(); ?>reports/view_activitydata/${survey.proj_id}/${survey.id}" target="_blank" class="btn btn-success btn-sm">View data</a>
            </td>
          </tr>`;
        }
        if(data.all_registration.length === 0) {
          HTML += `<tr><td colspan="8">No data found</td></tr>`;
        }
        $('.table').find('tbody').html(HTML);
      }
    });
  });
</script>