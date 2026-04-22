<div class="app-content content" style="margin-left: 0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 30px;">
      <div class="row">
        <div class="col-md-12">
          <h4 style="font-weight: bold;">Save survey data</h4>
        </div>

        <!-- <div class="col-md-12 mt-10">
          <div class="card p-10">
            <div class="content-body">
              <div class="row">
                <div class="col-md-12">
                  <label class="bold">Select Value Chain</label>
                  <select class="form-control" name="value_chain">
                    <option value="">All</option>
                    <?php foreach ($value_chain_list as $key => $value) { ?>
                      <option value="<?php echo $value['value_chain_id']; ?>"><?php echo $value['value_chain_name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <div class="col-md-12 mt-10">
          <h4 class="bold"></h4>
          <div class="card">
            <div class="card-header">
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <!-- <div class="heading-elements">
                <ul class="list-inline mb-0">
                  <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                  <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                  <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  <li><a data-action="close"><i class="ft-x"></i></a></li>
                </ul>
              </div> -->
            </div>
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Value chain</th>
                        <th>Survey name</th>                        
                        <th>Upload count</th>
                        <!-- <th>Action</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($form) > 0){
                        foreach ($form as $key => $value) { ?>
                          <tr>
                            <th scope="row"><?php echo $key+1; ?></th>
                            <td><?php echo $value['value_chain_name']; ?></td>
                            <td><?php echo $value['title']; ?></td>                            
                            <td style="text-align: center;"><?php echo $value['upload_count']; ?></td>
                            <!-- <td><a href="javascript:void(0);">Upload survey</a></td> -->
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
  </div>
</div>