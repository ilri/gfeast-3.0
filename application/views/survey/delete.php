<style type="text/css">
  .vertical-layout{ margin-top: 10px; }
</style>

<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-top: 10px;">
      <div class="row">
        <div class="col-md-12">
          <h4 class="bold">All Survey</h4>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card p-10" style="max-height: 800px;">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Survey Name</th>
                    <th>Survey Type</th>
                    <th>Location</th>
                    <th>Created by</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(count($all_surveys) > 0){
                    foreach ($all_surveys as $key => $survey) { ?>
                      <tr id="survey<?php echo $survey['id']; ?>">
                        <th scope="row"><?php echo ++$key; ?></th>
                        <td><?php echo $survey['title']; ?></td>
                        <td><?php echo $survey['type']; ?></td>
                        <td><?php echo ($survey['location'] == 1) ? "Yes" : "N/A"; ?></td>
                        <td><?php echo $survey['username']; ?></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete_survey" data-surveyid="<?php echo $survey['id']; ?>">Delete</button></td>
                      </tr>
                    <?php }
                  }else{ ?>
                    <tr>
                      <td colspan="6">No data found</td>
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

<script type="text/javascript">
  $(function(){

    // Define global variable ajaxData
    var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

    $('.delete_survey').on('click', function(){
      $elem = $(this);
      var survey_id = $elem.data("surveyid");
      ajaxData['survey_id'] = survey_id;
      
      swal({
        title: "Are you sure?",
        text: "All the projects linked to this survey will be freed. You will not be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      }, function (isConfirm) {
        if (!isConfirm) return;
        $.ajax({
          url: "<?php echo base_url(); ?>survey/delete_survey",
          type: "POST",
          dataType: "json",
          data: ajaxData,
          complete: function(data) {
            var csrfData = JSON.parse(data.responseText);
            ajaxData[csrfData.csrfName] = csrfData.csrfHash;
            if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
              $('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
            }
          },
          error: function () {
            swal("Network Error!", "Could not establish connection to server. Please refresh the page and try again.", "error");
          },
          success: function (response) {
            if(response.status == 1){
              swal("Done!", ""+response.msg+"!", "success");

              $('#survey'+survey_id+'').remove();
            }else{
              swal("Sorry!", ""+response.msg+"!", "error");
            }
          }            
        });
      });
    });
  });
</script>