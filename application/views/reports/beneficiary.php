<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body">
  		<div class="row">
  			<div class="col-md-12">
  				<h4 style="font-weight: bold;">All Beneficiaries</h4>
  			</div>
        <div class="col-md-12">
          <h4 class="bold"></h4>
          <div class="card">
            <div class="card-header"></div>
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Beneficiary Name</th>
                        <!-- <th>Survey Type</th> -->
                        <th>Location</th>
                        <th>Created by</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($all_beneficiary) > 0){
                        foreach ($all_beneficiary as $key => $survey) { ?>
                          <tr>
                            <th scope="row"><?php echo ++$key; ?></th>
                            <td><?php echo $survey['title']; ?></td>
                            <!-- <td><?php echo $survey['type']; ?></td> -->
                            <td><?php echo ($survey['location'] == 1) ? "Yes" : "N/A"; ?></td>
                            <td><?php echo $survey['username']; ?></td>
                            <td>
                              <a href="<?php echo base_url(); ?>reports/view_beneficiarydata/<?php echo $survey['id']; ?>" class="btn btn-success btn-sm" target="_blank">View Data</a>
                              <a href="<?php echo base_url(); ?>reports/edit_beneficiarydata/<?php echo $survey['id']; ?>" class="btn btn-primary btn-sm ml-1" target="_blank">Edit Data</a>
                              <a href="<?php echo base_url(); ?>reports/verify_beneficiarydata/<?php echo $survey['id']; ?>" class="btn btn-info btn-sm ml-1" target="_blank">Verify Data</a>
                              <a href="<?php echo base_url(); ?>uploads/data/beneficary_dataexport.xlsx" class="btn btn-success btn-sm ml-1" download>Download Excel</a>
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