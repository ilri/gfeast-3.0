<div class="main-content">
  <div class="p-4">
    <h5 style="font-weight: bold;">Upload survey data</h5>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Survey name</th>
                    <th>Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(count($form) > 0){
                    foreach ($form as $key => $value) { ?>
                      <tr>
                        <th scope="row"><?php echo $key+1; ?></th>
                        <td><?php echo $value['title']; ?></td>
                        <td><?php echo $value['type']; ?></td>
                        <td><a href="<?php echo base_url(); ?>reporting/upload_data/<?php echo $value['id']; ?>" class="btn btn-sm btn-success">Upload data</a></td>
                      </tr>
                    <?php }
                  }else{ ?>
                    <tr>
                      <td colspan="5">No surveys have been assigned.</td>
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