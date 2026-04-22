<style type="text/css">
  
  .vertical-layout{
    margin-top: 10px;
   }

</style>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
  	<div class="content-body" style="margin-top: 10px;">
       <div class="row" >
          <div class="col-md-12" style="margin-bottom: 30px; margin-top: -30px;">
            <img src="<?php echo base_url(); ?>includeout/images/banner.jpg" style="width: 100%;">
          </div>
        </div>
  		<div class="row">
        <div class="col-md-12 mt-10">
          <a href="" class=" btn btn-success round float-md-right" style="margin-right: 15px;">
            <i class="ft-plus"></i> Value Chain                
          </a>
          <h4 class="bold">Manage Value chain</h4>
          <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
        </div>
        <div class="col-md-12 mt-10">
          <div class="card">
            <div class="card-header">
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
            </div>
            <div class="card-content collapse show">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Value chain</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($get_value_chain) > 0){
                        foreach ($get_value_chain as $key => $value) { ?>
                          <tr>
                            <th scope="row"><?php echo $key+1; ?></th>
                            <td><?php echo $value['value_chain_name']; ?></td>
                            <td>
                              <a href="javascript:void(0);" class="btn btn-success btn-sm"><i class="ft-edit"></i> Value chain</a>
                              <a href="<?php echo base_url(); ?>value_chain_manangement/manage_value_chain_location/<?php echo $value['value_chain_id']; ?>" class="btn btn-success btn-sm">Manage Location</a>
                            </td>
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