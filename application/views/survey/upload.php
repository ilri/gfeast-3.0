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
                        <td>
                          <?php if($survey['type'] == 'Beneficiary'){ ?>
                            <a href="<?php echo base_url(); ?>survey/upload_beneficiarydata/<?php echo $survey['id']; ?>" class="btn btn-success btn-sm">Upload data</a>
                          <?php }else{ 
                            if($survey['projects'] == 1){ ?>
                              <a href="<?php echo base_url(); ?>survey/search_beneficiary/<?php echo $survey['id']; ?>" class="btn btn-success btn-sm">Upload data</a>
                            <?php }else{
                              echo "No project has been assign.";
                            } ?>
                          <?php } ?>
                        </td>
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