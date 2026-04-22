<style type="text/css">
  .vertical-layout{ margin-top: 10px; }
</style>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-top: 10px;">
  		<div class="row">
  			<div class="col-md-12">
  				<h4 class="bold">All Templates</h4>
  			</div>
        <div class="col-md-12 mt-10">
          <div class="card">
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Template Name</th>
                        <th>Template Type</th>
                        <!-- <th>Location</th> -->
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
                            <!-- <td><?php echo ($survey['location'] == 1) ? "Yes" : "N/A"; ?></td> -->
                            <td><?php echo $survey['username']; ?></td>
                            <td>
                              <a href="<?php echo base_url(); ?>survey/view_survey/<?php echo $survey['id']; ?>" class="btn btn-success btn-sm">Upload Data</a>
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
	</div>
</div>