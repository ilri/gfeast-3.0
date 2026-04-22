<style>
  ::-webkit-scrollbar {
    width: 8px;
    height: 0px;
  }
</style>
<link href="<?php echo base_url() ?>include/vendors/select2/select2.min.css" rel="stylesheet">
<div class="app-content content" style="margin-left:0px;">
  <div class="content-wrapper">
    <div class="content-body" style="margin-bottom: 40px;">
      <div class="col-md-12 ">
        <div class="card">
          <!-- Example Tabs -->
          <div class="example-wrap">
            <div class="nav-tabs-horizontal navtab" data-plugin="tabs">
              <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link active show" data-toggle="tab" href="#Country" aria-controls="Country" role="tab" aria-selected="true"><b>Country Settings</b></a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#State" aria-controls="State" role="tab" aria-selected="false"><b>State Settings</b></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#District" aria-controls="District" role="tab" aria-selected="false"><b>District Settings</b></a></li>
              </ul>
              <div class="tab-content pt-20">
                <div class="tab-pane active show" id="Country" role="tabpanel">
                  <div class="card-content collapse show">
                    <div class="card-body">
                      <div class="table-responsive">
                        <button type="button" class="btn btn-info float-right mb-20" id="" title="Add Country" data-target="#countryModelAdd" data-toggle="modal">Add Country</button>
                        <table class="table">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Country Name </th>
                              <th>Country Code </th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            if ($countries) {
                              $i = 0;
                              foreach ($countries as $country) {
                                $i++;
                            ?>
                                <tr>
                                  <td><?php echo $i ?></td>
                                  <td><?php echo $country->name ?></td>
                                  <td><?php echo $country->code ?></td>
                                  <td>
                                    <button type="button" class="btn btn-info btnViewCountry" id="<?php echo $country->country_id ?>" data-target="#countryModel" data-toggle="modal">View</button>
                                    <button type="button" class="btn btn-success btnEditCountry" id="<?php echo $country->country_id ?>" data-target="#countryModelEdit" data-toggle="modal">Edit</button>
                                    <button type="button" class="btn btn-danger btnDeleteCountry" id="<?php echo $country->country_id ?>">Delete</button>
                                  </td>
                                </tr>
                              <?php
                              }
                            } else { ?>
                              <tr>
                                <td colspan="3">
                                  <h3 class="text-warning text-center">Countries not found.</h3>
                                </td>
                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="State" role="tabpanel">
                  <div class="card-body">

                    <div class="table-responsive">
                      <!-- select 2  -->
                      <div class="form-group row stateSelect2">
                        <label class="col-md-2 form-control-label"> <span class="text-danger"> </span></label>
                        <div class="col-md-6">
                          <select class="form-control select2_select" id="countryFil" name="countryFil" data-plugin="select2">
                            <option value="">All Country</option>
                            <?php
                            foreach ($countries as $country) {
                            ?>
                              <option value="<?php echo $country->country_id ?>"><?php echo ucfirst($country->name) ?></option>
                            <?php
                            }
                            ?>
                          </select>
                          <small class="text-danger" id="countryFilError"></small>
                        </div>
                        <div class="col-md-4">
                          <button type="button" class="btn btn-info float-right" id="btnStateModalOpen" title="Add State" data-target="#stateModelAdd" data-toggle="modal">Add State</i></button>
                        </div>
                      </div>
                      <!-- /select 2  -->
                      <table class="table" id="statetbl">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Country </th>
                            <th>State </th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($states) {
                            $i = 0;
                            foreach ($states as $state) {
                              $i++;
                          ?>
                              <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $state->name ?></td>
                                <td><?php echo $state->state_name ?></td>
                                <td>
                                  <button type="button" class="btn btn-info btnViewState" id="<?php echo $state->state_id ?>" data-target="#stateModel" data-toggle="modal">View</button>
                                  <button type="button" class="btn btn-success btnEditState" id="<?php echo $state->state_id ?>" data-target="#stateModelEdit" data-toggle="modal">Edit</button>
                                  <button type="button" class="btn btn-danger btnDeleteState" id="<?php echo $state->state_id ?>">Delete</button>
                                </td>
                              </tr>
                            <?php
                            }
                          } else {
                            ?>
                            <tr>
                              <td colspan="4">
                                <h3 class="text-warning text-center">States not foun.</h3>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="District" role="tabpanel">
                  <div class="card-body">
                    <!-- select 2  -->
                    <div class="form-group row districtSelect2 ">
                      <div class="col-md-3"></div>
                      <div class="col-md-3">
                        <select class="form-control select2_select" id="selCountry" name="" data-plugin="select2">
                          <option value="">All Country</option>
                          <?php
                          foreach ($countries as $country) {
                          ?>
                            <option value="<?php echo $country->country_id ?>"><?php echo ucfirst($country->name) ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <select class="form-control select2_select" id="selState" name="" data-plugin="select2">
                          <option>All State</option>
                          <?php
                          foreach ($states as $state) {
                          ?>
                            <option value="<?php echo $state->state_id ?>"><?php echo ucfirst($state->state_name) ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <button type="button" class="btn btn-info float-right" id="btnDistrictModalOpen" title="Add State" data-target="#districtModelAdd" data-toggle="modal">Add Disrtict</button>
                      </div>
                    </div>
                    <!-- /select 2  -->
                    <div class="table-responsive">
                      <table class="table" id="districttbl">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Country </th>
                            <th>State </th>
                            <th>District </th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if ($districts) {
                            $i = 0;
                            foreach ($districts as $district) {
                              $i++;
                          ?>
                              <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $district->name ?></td>
                                <td><?php echo $district->state_name ?></td>
                                <td><?php echo $district->district_name ?></td>
                                <td>
                                  <button type="button" class="btn btn-info btnViewDistrict" id="<?php echo $district->district_id ?>" data-target="#districtModel" data-toggle="modal">View</button>
                                  <button type="button" class="btn btn-success btnEditDistrict" id="<?php echo  $district->district_id ?>" data-target="#districtModelEdit" data-toggle="modal">Edit</button>
                                  <button type="button" class="btn btn-danger btnDeleteDistrict" id="<?php echo $district->district_id ?>">Delete</button>
                                </td>
                              </tr>
                            <?php
                            }
                          } else {
                            ?>
                            <tr>
                              <td colspan="5">
                                <h3 class="text-warning text-center">Districts not found.</h3>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Example Tabs -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- country modal  -->
<div class="modal fade" id="countryModel" aria-labelledby="countryModelLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Countr Informations.</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <span id="countryModalBody"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!--edit country  -->
<div class="modal fade" id="countryModelEdit" aria-labelledby="countryModelEditLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Country</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <span id="btnEditCountry"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success float-right " data-placement="top" title="Edit" id="btnUpdateCountry">Save</button>
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!-- /edi country  -->
<!-- add country  -->
<div class="modal fade" id="countryModelAdd" aria-labelledby="countryModelAddLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Country</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Country Name <span class="text-danger">* </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="country_name" id="country_name" aria-describedby="helpId" placeholder="Country Name" autocomplete="off" value="">
                <small class="text-danger" id="country_name_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Country Code <span class="text-danger">* </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="country_code" id="country_code" aria-describedby="helpId" placeholder="Country Code" value="">
                <small class="text-danger" id="country_code_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Latitude <span class="text-danger"> </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="lat" id="lat" aria-describedby="helpId" placeholder="Latitude" value="">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Langtitude: <span class="text-danger"> </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="lng" id="lng" aria-describedby="helpId" placeholder="Langtitude" value="">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success float-right " data-placement="top" title="Add" id="btnAddCountry">Save</button>
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!-- /add country  -->
<!-- /country modal  -->
<!-- state modal  -->
<div class="modal fade" id="stateModel" aria-labelledby="stateModelLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">State Informations.</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <span id="stateModalBody"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger float-right btnCloseState btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!--edit state  -->
<div class="modal fade" id="stateModelEdit" aria-labelledby="stateModelEditLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update State Name</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <span id="btnEditstate"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success float-right " data-placement="top" title="Edit" id="btnUpdateState">Save</button>
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!--add state  -->
<div class="modal fade" id="stateModelAdd" aria-labelledby="stateModelAddLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add State</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Country<span class="text-danger">* </span></label>
              <div class="col-md-9">
                <select class="form-control select2_select" id="cName" name="" data-plugin="select2">
                  <option value="">Select Country</option>
                  <?php
                  foreach ($countries as $country) {
                  ?>
                    <option value="<?php echo $country->country_id ?>"><?php echo ucfirst($country->name) ?></option>
                  <?php
                  }
                  ?>
                </select>
                <small class="text-danger" id="cName_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> State Name <span class="text-danger">* </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="" id="state_name" aria-describedby="helpId" placeholder="State Name" value="" autocomplete="off">
                <small class="text-danger" id="state_name_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Latitude <span class="text-danger"> </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="lat" id="state_lat" aria-describedby="helpId" placeholder="Latitude" value="" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Langtitude: <span class="text-danger"> </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="lng" id="state_lng" aria-describedby="helpId" placeholder="Langtitude" value="" autocomplete="off">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success float-right " data-placement="top" title="Edit" id="btnAddState">Save</button>
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!-- /add state  -->
<!-- /state modal  -->
<!-- district modal  -->
<div class="modal fade" id="districtModel" aria-labelledby="districtModelLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width: 1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">District Informations.</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <span id="districtModalBody"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger float-right btnCloseDistrict btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close "><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!--edit district  -->
<div class="modal fade" id="districtModelEdit" aria-labelledby="districtModelEditLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update District Name</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <span id="districtEditModel"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success float-right " data-placement="top" title="Edit" id="btnUpdateDistrict">Save</button>
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!-- /edi district  -->
<!--add district  -->
<div class="modal fade" id="districtModelAdd" aria-labelledby="districtModelAddLabel" role="dialog" tabindex="-1" aria-hidden="true" style="display: none; width:100%;">
  <div class="modal-dialog modal-xl" style="width:1024px; margin:auto">
    <form class=" modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add District</h4>
      </div>
      <div class="modal-body" id="">
        <div class="row">
          <div class="col-md-12 ">
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Country<span class="text-danger">* </span></label>
              <div class="col-md-9">
                <select class="form-control select2_select" id="selCName" name="" data-plugin="select2">
                  <option value="">Select Country</option>
                  <?php
                  foreach ($countries as $country) {
                  ?>
                    <option value="<?php echo $country->country_id ?>"><?php echo ucfirst($country->name) ?></option>
                  <?php
                  }
                  ?>
                </select>
                <small class="text-danger" id="selCName_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> State<span class="text-danger">* </span></label>
              <div class="col-md-9">
                <select class="form-control select2_select" id="selStateAdd" name="" data-plugin="select2">
                  <option value="">Select state</option>
                </select>
                <small class="text-danger" id="selStateAdd_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> District Name <span class="text-danger">* </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="district_name" id="district_name" aria-describedby="helpId" placeholder="District Name" value="" autocomplete="off">
                <small class="text-danger" id="district_name_error"></small>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Latitude <span class="text-danger"> </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="lat" id="district_lat" aria-describedby="helpId" placeholder="Latitude" value="" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 form-control-label"> Langtitude: <span class="text-danger"> </span></label>
              <div class="col-md-9">
                <input type="text" class="form-control" name="lng" id="district_lng" aria-describedby="helpId" placeholder="Langtitude" value="" autocomplete="off">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success float-right " data-placement="top" title="Edit" id="btnAddDistrict">Save</button>
        <button type="button" class="btn btn-danger float-right btnClose" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-remove"></i></button>
      </div>
  </div>
  </form>
</div>
<!-- /add district  -->
<!-- /district modal  -->
</link>
<script src="<?php echo base_url() ?>include/vendors/select2/select2.min.js"></script>
<script>
  $(document).ready(function() {
    // select 2 select
    $('.select2_select').select2();
    /* showing and hidding the select2 based on modal shown and hidden */
    $('#districtModel, #districtModelEdit, #districtModelAdd').on('show.bs.modal', function(e) {
      $('.districtSelect2').hide();
    })
    $('#districtModel,#districtModelEdit,#districtModelAdd').on('hide.bs.modal', function(e) {
      $('.districtSelect2').show();
    })
    $('#stateModel, #stateModelEdit, #stateModelAdd').on('show.bs.modal', function(e) {
      $('.stateSelect2').hide();
    })
    $('#stateModel, #stateModelEdit, #stateModelAdd').on('hide.bs.modal', function(e) {
      $('.stateSelect2').show();
    })
    // view country
    $('body').on('click', '.btnViewCountry', function(e) {
      e.preventDefault();
      let country_id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/getCountry') ?>",
        data: {
          country_id: country_id
        },
        dataType: "JSON",
        success: function(response) {
          console.log(response);
          var country_modal_body = ``;
          country_modal_body += `
            <table class="table table-bordered">
              <tbody>
              <tr>
              <td>Name</td>
                <td>` + response.name + `</td>
              </tr>
              <tr>
              <td>Code</td>
                <td>` + response.code + `</td>
              </tr>
              </tbody>
            </table>
         `;
          $("#countryModalBody").html(country_modal_body);
        }
      });
    });
    // view state
    $('body').on('click', '.btnViewState', function(e) {
      e.preventDefault();
      let state_id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/getState') ?>",
        data: {
          state_id: state_id
        },
        dataType: "JSON",
        success: function(response) {
          var response = response[0];
          var state_modal_body = ``;
          state_modal_body += `
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tbody>
                <tr>
                <td>Country </td>
                  <td>` + response.name + `</td>
                </tr>
                <tr>
                <td>State</td>
                  <td>` + response.state_name + `</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
         `;
          $("#stateModalBody").html(state_modal_body);
        }
      });
    });
    // view district
    $('body').on('click', '.btnViewDistrict', function(e) {
      e.preventDefault();
      let district_id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/getDistrict') ?>",
        data: {
          district_id: district_id
        },
        dataType: "JSON",
        success: function(response) {
          var response = response[0];
          var district_modal_body = ``;
          district_modal_body += `
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tbody>
                <tr>
                <td>Country </td>
                  <td>` + response.name + `</td>
                </tr>
                <tr>
                <td>State</td>
                  <td>` + response.state_name + `</td>
                </tr>
                <tr>
                <td>State</td>
                  <td>` + response.district_name + `</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
         `;
          $("#districtModalBody").html(district_modal_body);
        }
      });
    });
    // setting country status to 0
    $('body').on('click', '.btnDeleteCountry', function() {
      let country_id = $(this).attr('id');
      if (confirm("Are you sure to delete?")) {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/deleteCountry') ?>",
          data: {
            country_id: country_id
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 1) {
              alert("Deleted successfully.");
              var url = "<?php echo base_url('Locationsetting/') ?>";
              window.location.href = url;
            } else if (response.status == 0) {
              alert("Does not deleted.");
            }
          }
        });
      }
    });
    // setting state status to 0
    $('body').on('click', '.btnDeleteState', function() {
      let state_id = $(this).attr('id');
      if (confirm("Are you sure to delete?")) {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/deleteState') ?>",
          data: {
            state_id: state_id
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 1) {
              alert("Deleted successfully.");
              var url = "<?php echo base_url('Locationsetting/') ?>";
              window.location.href = url;
            } else if (response.status == 0) {
              alert("Does not deleted.");
            }
          }
        });
      }
    });
    // setting district status to 0
    $('body').on('click', '.btnDeleteDistrict', function() {
      let district_id = $(this).attr('id');
      if (confirm("Are you sure to delete?")) {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/deleteDistrict') ?>",
          data: {
            district_id: district_id
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 1) {
              var url = "<?php echo base_url('Locationsetting/') ?>";
              window.location.href = url;
              alert("Deleted successfully.");
            } else if (response.status == 0) {
              alert("Does not deleted.");
            }
          }
        });
      }
    });

    // state filtering 
    $('body').on('change', '#countryFil', function(e) {
      e.preventDefault();
      let country_id = $(this).val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/filterState') ?>",
        data: {
          country_id: country_id
        },
        dataType: "JSON",
        success: function(response) {
          var state_content_data = `<table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Country </th>
                <th>State </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>`;
          var tbl_content = ``;
          if (response.status !== 0) {
            var i = 0;
            response.forEach(el => {
              i++;
              tbl_content += `
                <tr>
                  <td>` + i + `</td>
                  <td>` + el.name + `</td>
                  <td>` + el.state_name + `</td>
                  <td>
                    <button type="button" class="btn btn-info btnViewState" id="` + el.state_id + `" data-target="#stateModel" data-toggle="modal">View</button>
                    <button type="button" class="btn btn-success btnEditState" id="` + el.state_id + `" data-target="#stateModelEdit" data-toggle="modal">Edit</button>
                    <button type="button" class="btn btn-danger btnDeleteState" id="` + el.state_id + `">Delete</button>
                  </td>
                </tr>
                `;
            });
          } else {
            tbl_content += `
              <tr>
              <td colspan="4">
                      <h3 class="text-warning text-center">State not found.</h3>
                    </td>
              </tr>
              `;
          }
          state_content_data += tbl_content;
          state_content_data += `</tbody> </table > `;
          $("#statetbl").html(state_content_data);
        }
      });
    });
    // fetch state on country selction changed
    $('body').on('change', '#selCountry', function(e) {
      e.preventDefault();
      let country_id = $(this).val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/filterState') ?>",
        data: {
          country_id: country_id
        },
        dataType: "JSON",
        success: function(response) {
          var select_content = `<select class="form-control select2_select" id="selState" name="" data-plugin="select2">`;
          if (response.status !== 0) {
            select_content += ` 
               <option value="">All State</option>
                `;
            response.forEach(el => {
              select_content += `
            <option value="` + el.state_id + `">` + el.state_name + `</option>
            `;
            });
            select_content += `</select>`;
            $("#selState").html(select_content).val('').trigger('change');
          } else {
            select_content += `
          <option value="0">State not found.</option>`;
            $("#selState").html(select_content).val(0).trigger('change');
          }
        }
      });
    });
    // filtering district based on country and sate
    $('body').on('change', '#selState', function(e) {
      e.preventDefault();
      let state_id = $(this).val();
      let country_id = $("#selCountry").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/filterDistrict') ?>",
        data: {
          state_id: state_id,
          country_id: country_id
        },
        dataType: "JSON",
        success: function(response) {
          var district_content_data = `<table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Country </th>
                <th>State </th>
                <th>District </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>`;
          var tbl_content = ``;
          if (response.status !== 0) {
            var i = 0;
            response.forEach(el => {
              i++;
              tbl_content += `
                <tr>
                  <td>` + i + `</td>
                  <td>` + el.name + `</td>
                  <td>` + el.state_name + `</td>
                  <td>` + el.district_name + `</td>
                  <td>
                  <button type="button" class="btn btn-info btnViewDistrict" id="` + el.district_id + `" data-target="#districtModel" data-toggle="modal">View</button>
                    <button type="button" class="btn btn-success btnEditDistrict" id="` + el.district_id + `" data-target="#districtModelEdit" data-toggle="modal">Edit</button>
                    <button type="button" class="btn btn-danger btnDeleteDistrict" id="` + el.district_id + `">Delete</button>
                  </td>
                </tr>
                `;
            });
          } else {
            tbl_content += `
            <tr>
              <td colspan="5">
                <h3 class="text-warning text-center">Districts not found.</h3>
              </td>
              </tr>
              `;
          }
          district_content_data += tbl_content;
          district_content_data += `</tbody> </table > `;
          $("#districttbl").html(district_content_data);
        }
      });
    });
    //Edit country 
    $('body').on('click', '.btnEditCountry', function(e) {
      e.preventDefault();
      let country_id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/getCountry') ?>",
        data: {
          country_id: country_id
        },
        dataType: "JSON",
        success: function(response) {
          var country_modal_body = ``;
          country_modal_body += `
          <div class="form-group row">
            <label class="col-md-2 form-control-label"> Country Name <span class="text-danger">* </span></label>
            <div class="col-md-9">
            <input type="text"
            class="form-control" name="countryName" id="countryName" aria-describedby="helpId" placeholder="Country Name" value="` + response.name + `">
            <input type="hidden" id="countryId" value="` + response.country_id + `">
            <small class="text-danger" id="countryName_error"></small>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-2 form-control-label"> Country Code <span class="text-danger">*</span></label>
            <div class="col-md-9">
            <input type="text"
            class="form-control" name="countryCode" id="countryCode" aria-describedby="helpId" placeholder="Country Code" value="` + response.code + `">
            <small class="text-danger" id="countryCode_error"></small>
            </div>
          </div>
         `;
          $("#btnEditCountry").html(country_modal_body);
        }
      });
    });
    // Update the country data
    $('body').on('click', '#btnUpdateCountry', function(e) {
      e.preventDefault();
      let country_id = $("#countryId").val();
      let name = $("#countryName").val();
      let code = $("#countryCode").val();
      if (name == "") {
        $("#countryName_error").text("Name is required");
      } else if (code == "") {
        $("#countryCode_error").text("Code is required");
      } else {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/updateCountry') ?>",
          data: {
            country_id: country_id,
            name: name,
            code: code
          },
          dataType: "JSON",
          success: function(response) {
            var url = "<?php echo base_url('Locationsetting/') ?>";
            window.location.href = url;
            if (response.status == 1) {
              alert("Record Update Successfully.");
              $("#countryModelEdit").modal('toggle');
              window.location.href = url;
            } else if (response.status == 0) {
              alert("Record Does Not Update.");
            }
          }
        });
      }
    });
    // add the country data
    $('body').on('click', '#btnAddCountry', function(e) {
      e.preventDefault();
      let name = $("#country_name").val();
      let code = $("#country_code").val();
      let lat = $("#lat").val();
      let lng = $("#lng").val();
      if (name == "") {
        $("#country_name_error").text("Name is required");
      } else if (code == "") {
        $("#country_code_error").text("Code is required");
      } else {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/checkCountry') ?>",
          data: {
            name: name,
            code: code
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status_name == 0) {
              if (response.status_code == 0) {
                $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('Locationsetting/addCountry') ?>",
                  data: {
                    name: name,
                    code: code,
                    lat: lat,
                    lng: lng
                  },
                  dataType: "JSON",
                  success: function(response) {
                    var url = "<?php echo base_url('Locationsetting/') ?>";
                    window.location.href = url;
                    if (response.status == 1) {
                      alert("Country Added Successfully.");
                      $("#countryModelAdd").modal('toggle');
                      window.location.href = url;
                    } else if (response.status == 0) {
                      alert("Country Does Not Added.");
                    }
                  }
                });
              } else {
                alert("Country Code already exist.")
              }
            } else {
              alert("Country Name already exist.")
            }
          }
        });
      }
    });
    // fetch state on country selction changed
    $('body').on('change', '#selCName', function(e) {
      e.preventDefault();
      let country_id = $(this).val();
      if (country_id !== "") {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/filterState') ?>",
          data: {
            country_id: country_id
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status !== 0) {
              var tbl_content = ` <select class="form-control select2_select" id="selStateAdd" name="" data-plugin="select2">
          <option value="">Select State</option>
          `;
              if (response.status !== 0) {
                response.forEach(el => {
                  tbl_content += `
            <option value="` + el.state_id + `">` + el.state_name + `</option>
            `;
                });
                $("#selStateAdd").html(tbl_content);
              }
            } else {
              tbl_content = `<select class="form-control select2_select" id="selStateAdd" name="" data-plugin="select2">
          <option value="">State not found.</option></select>`;
              $("#selStateAdd").html(tbl_content);
            }
          }
        });
      } else {
        $("#selStateAdd").html(`<select class="form-control select2_select" id="selStateAdd" name="" data-plugin="select2">
          <option value="">Select state</option></select>`);
      }
    });
    // removing error message on change 
    $("#cName").change(function(e) {
      $("#cName_error").text('');
    });
    // add the state data
    $('body').on('click', '#btnAddState', function(e) {
      e.preventDefault();
      let country_id = $("#cName").val();
      let state_name = $("#state_name").val();
      let lat = $("#state_lat").val();
      let lng = $("#state_lng").val();
      if (country_id == "") {
        $("#cName_error").text('Country is requiered.');
      } else if (state_name == "") {
        $("#state_name_error").text('State Name is requiered.');
      } else {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/checkState') ?>",
          data: {
            country_id: country_id,
            state_name: state_name
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 0) {
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('Locationsetting/addState') ?>",
                data: {
                  country_id: country_id,
                  state_name: state_name,
                  lat: lat,
                  lng: lng
                },
                dataType: "JSON",
                success: function(response) {
                  var url = "<?php echo base_url('Locationsetting/') ?>";
                  window.location.href = url;
                  if (response.status == 1) {
                    alert("State Added Successfully.");
                    $("#stateModelAdd").modal('toggle');
                    window.location.href = url;
                  } else if (response.status == 0) {
                    alert("State Does Not Added.");
                  }
                }
              });
            } else {
              alert("State Name for this country already exist.")
            }
          }
        });
      }
    });
    // removing error message on change 
    $("#selCName").change(function(e) {
      $("#selCName_error").text('');
    });
    $("#selStateAdd").change(function(e) {
      $("#selStateAdd_error").text('');
    });
    // add the district data
    $('body').on('click', '#btnAddDistrict', function(e) {
      e.preventDefault();
      let country_id = $("#selCName").val();
      let state_id = $("#selStateAdd").val();
      let district_name = $("#district_name").val();
      let lat = $("#district_lat").val();
      let lng = $("#district_lng").val();
      if (country_id == "") {
        $("#selCName_error").text("Country is required")
      } else if (state_id == "") {
        $("#selStateAdd_error").text("State is required")
      } else if (district_name == "") {
        $("#district_name_error").text("District Name is required")
      } else {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/checkDistrict') ?>",
          data: {
            country_id: country_id,
            state_id: state_id,
            district_name: district_name
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 0) {
              $.ajax({
                type: "POST",
                url: "<?php echo base_url('Locationsetting/addDistrict') ?>",
                data: {
                  country_id: country_id,
                  state_id: state_id,
                  district_name: district_name,
                  lat: lat,
                  lng: lng
                },
                dataType: "JSON",
                success: function(response) {
                  var url = "<?php echo base_url('Locationsetting/') ?>";
                  window.location.href = url;
                  if (response.status == 1) {
                    alert("District Added Successfully.");
                    $("#stateModelAdd").modal('toggle');
                    window.location.href = url;
                  } else if (response.status == 0) {
                    alert("State Does Not Added.");
                  }
                }
              });
            } else {
              alert("District Name for this state of country already exist.")
            }
          }
        });
      }
    });
    //Edit state 
    $('body').on('click', '.btnEditState', function(e) {
      e.preventDefault();
      let state_id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/getState') ?>",
        data: {
          state_id: state_id
        },
        dataType: "JSON",
        success: function(response) {
          response = response[0];
          var state_modal_body = ``;
          state_modal_body += `
          <div class="form-group row stateSelect2">
            <label class="col-md-2 form-control-label"> State Name <span class="text-danger">* </span></label>
            <div class="col-md-9">
            <input type="text"
            class="form-control" name="stateName" id="stateName" aria-describedby="helpId" placeholder="State Name" value="` + response.state_name + `">
            <input type="hidden" id="stateId" value="` + response.state_id + `">
              <small class="text-danger" id="stateNameError"></small>
            </div>
          </div>
         `;
          $("#btnEditstate").html(state_modal_body);
        }
      });
    });
    // Update the state data
    $('body').on('click', '#btnUpdateState', function(e) {
      e.preventDefault();
      let state_id = $("#stateId").val();
      let state_name = $("#stateName").val();
      if (state_name == "") {
        $("#stateNameError").text("State Name is required.")
      } else {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/updateState') ?>",
          data: {
            state_id: state_id,
            state_name: state_name
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 1) {
              alert("Record Update Successfully.");
              $("#stateModelEdit").modal('toggle');
              var url = "<?php echo base_url('Locationsetting/') ?>";
              window.location.href = url;
            } else if (response.status == 0) {
              alert("Record Does Not Update.");
            }
          }
        });
      };
    });
    //Edit district 
    $('body').on('click', '.btnEditDistrict', function(e) {
      e.preventDefault();
      let district_id = $(this).attr('id');
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('Locationsetting/getDistrict') ?>",
        data: {
          district_id: district_id
        },
        dataType: "JSON",
        success: function(response) {
          response = response[0];
          var district_modal_body = ``;
          district_modal_body += `
          <div class="form-group row">
            <label class="col-md-2 form-control-label"> district Name <span class="text-danger">* </span></label>
            <div class="col-md-9">
            <input type="text"
            class="form-control" name="districtName" id="districtName" aria-describedby="helpId" placeholder="District Name" value="` + response.district_name + `">
            <input type="hidden" id="districtId" value="` + response.district_id + `">
              <small class="text-danger" id="districtNameError"></small>
            </div>
          </div>
         `;
          $("#districtEditModel").html(district_modal_body);
        }
      });
    });
    // Update the district data
    $('body').on('click', '#btnUpdateDistrict', function(e) {
      e.preventDefault();
      let district_id = $("#districtId").val();
      let district_name = $("#districtName").val();
      if (district_name == "") {
        $("#districtNameError").text("District Name is required.")
      } else {
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('Locationsetting/updateDistrict') ?>",
          data: {
            district_id: district_id,
            district_name: district_name
          },
          dataType: "JSON",
          success: function(response) {
            if (response.status == 1) {
              alert("Record Update Successfully.");
              $("#districtModelEdit").modal('toggle');
              var url = "<?php echo base_url('Locationsetting/') ?>";
              window.location.href = url;
            } else if (response.status == 0) {
              alert("Record Does Not Update.");
            }
          }
        });
      }
    });

  });
</script>