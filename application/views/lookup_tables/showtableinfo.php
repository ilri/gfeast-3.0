
<head>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<style>
.sweet-alert .sa-icon.sa-success::before {
    border-radius: 120px 0 0 120px;
    top: -19px;
    left: -33px;
    transform: rotate(-45deg);
    transform-origin: 60px 60px;
}
	.vertical-layout{
		margin-top: 10px;
	}
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 30px;">
			<div class="row">
				<div class="col-md-12">
					<button id="addData" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> <?php echo $addLabelName; ?></button>
				</div>
				
				<div class="col-md-12 addDataDiv mt-10" style="display: none;">
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<form id="lookupdata">
									<?php
									$tablePrimaryId = '';
									switch ($this->uri->segment(3)) {
										case 'lkp_animal_type': ?>
											<div class="row lkp_animal_type">
												<div class="col-3">
													<label>Select Livestock Category*</label>
													<select name="livestock_id" class="form-control" id="livestock_id">
														<option value="">Select Livestock</option>
														<?php foreach ($dropdowns['livestockArray'] as $key => $value) { ?>
															<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="col-3">
													<label>Animal type*</label>
													<input type="text" name="animal_type_name" id="animal_type_name" class="form-control">
												</div>

												<!-- <div class="col-3">
													<label>Description*</label>
													<input type="text" name="description" id="description" class="form-control">
												</div> -->

												<div class="col-3">
													<label>Lactating</label>
													<div class="form-check">
		                                    			<div class="row">															 
															<div class="col-6">
																<label class="radio-inline">
																	<input type="radio" name="lactating" value="Yes"> Yes
																</label>
															</div>
															
															<div class="col-6">                               
																<label class="radio-inline">
																	<input type="radio" name="lactating" value="No"> No
																</label>
															</div>
														</div>
													</div>
												</div>

												<div class="col-3">
													<label>Dairy</label>
													<div class="form-check">
		                                    			<div class="row">															 
															<div class="col-6">
																<label class="radio-inline">
																	<input type="radio" name="dairy" value="Yes"> Yes
																</label>
															</div>
															
															<div class="col-6">                               
																<label class="radio-inline">
																	<input type="radio" name="dairy" value="No"> No
																</label>
															</div>
														</div>
													</div>
												</div>

												<div class="col-3">
													<label>Minimum Weight*</label>
													<input type="number" name="min_wt" id="min_wt" class="form-control">
												</div>

												<div class="col-3">
													<label>Maximum Weight*</label>
													<input type="number" name="max_wt" id="max_wt" class="form-control">
												</div>

												<div class="col-12">
													<p>Note: Don't define the minimum and maximum weight too strictly – leave some margin at either end of the range.</p>
												</div>
											</div>											
											<?php 
											$tablePrimaryId = 'id';
											break;

										case 'lkp_communities_type': ?>
											<div class="row lkp_communities_type">
												<div class="col-4">
													<label>Name*</label>
													<input type="text" name="communities_type_name" class="form-control" id="communities_type_name">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'id';
											break;

										case 'lkp_crop': ?>
											<div class="row lkp_crop">
												<div class="col-4">
													<label>Name*</label>
													<input type="text" name="crop_name" class="form-control" id="crop_name">
												</div>

												<div class="col-4">
													<label>Harvest Index*</label>
													<input type="text" name="harvest_index" class="form-control" id="harvest_index">
												</div>

												<div class="col-4">
													<label>Dry Matter Content (%)*</label>
													<input type="text" name="dry_matter_content" class="form-control" id="dry_matter_content">
												</div>

												<div class="col-4">
													<label>Metabolisable Energy (MJ/kgDM)*</label>
													<input type="text" name="metabolisable_energy" class="form-control" id="metabolisable_energy">
												</div>

												<div class="col-4">
													<label>Crude Protein Content (%)*</label>
													<input type="text" name="crude_protein_content" class="form-control" id="crude_protein_content">
												</div>

												<div class="col-4">
													<label>Provide reference /citation for your source of information</label>
													<input type="text" name="ref_source_info" class="form-control" id="ref_source_info">
												</div>
											</div>
											<?php
											$tablePrimaryId = 'id';
											break;

										case 'lkp_currency': ?>
											<div class="row lkp_currency">
												<div class="col-4">
													<label>Select Country</label>
													<select name="currencyId" class="form-control" id="currencyId">
														<option value="">Select Currency</option>
														<?php foreach ($getdata as $key => $value) { ?>
															<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
														<?php } ?>
													</select>
												</div>

												<div class="col-4">
													<label>Default Value in USD</label>
													<input type="text" name="default_value_in_USD" class="form-control" id="default_value_in_USD">
												</div>
											
												<div class="col-4">
													<label>Current Value in USD</label>
													<input type="text" name="current_exchange_rate" class="form-control" id="current_exchange_rate">
												</div>
											</div>
											<?php
											$tablePrimaryId = 'id';
											break;


										case 'lkp_fodder_type': ?>
											<div class="row lkp_fodder_type">
												<div class="col-4">
													<label>Fodder Type*</label>
													<input type="text" name="fodder_type" class="form-control" id="fodder_type">
												</div>

												<div class="col-4">
													<label>kg Dry Matter per Hectare per Year*</label>
													<input type="text" name="kg_dry_matter" class="form-control" id="kg_dry_matter">
												</div>

												<div class="col-4">
													<label>Metabolisable Energy (MJ/kgDM)*</label>
													<input type="text" name="metabolisable_energy" class="form-control" id="metabolisable_energy">
												</div>

												<div class="col-4">
													<label>Crude Protein Content (%)*</label>
													<input type="text" name="crude_protein_content" class="form-control" id="crude_protein_content">
												</div>

												<div class="col-4">
													<label>Provide a reference / citation for your source of information</label>
													<input type="text" name="reference" class="form-control" id="reference">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'fodder_type_id';
											break;

										case 'lkp_feed_type': ?>
											<div class="row lkp_feed_type">
												<div class="col-4">
													<label>Feed Type*</label>
													<input type="text" name="feed_type" class="form-control" id="feed_type">
												</div>

												<div class="col-4">
													<label>Dry Matter per Hectare per Year*</label>
													<input type="text" name="dry_matter_content" class="form-control" id="dry_matter_content">
												</div>

												<div class="col-4">
													<label>Metabolisable Energy (MJ/kgDM)*</label>
													<input type="text" name="metabolisable_energy" class="form-control" id="metabolisable_energy">
												</div>

												<div class="col-4">
													<label>Crude Protein Content (%)*</label>
													<input type="text" name="crude_protein_content" class="form-control" id="crude_protein_content">
												</div>

												<div class="col-4">
													<label>Provide a reference / citation for your source of information</label>
													<input type="text" name="reference" class="form-control" id="reference">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'feed_type_id';
											break;

										case 'lkp_livestock': ?>
											<div class="row lkp_livestock">
												<div class="col-4">
													<label>Name*</label>
													<input type="text" name="livestock_name" class="form-control" id="livestock_name">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'id';
											break;

										case 'lkp_species': ?>
											<div class="row lkp_species">
												<div class="col-4">
													<label>Name*</label>
													<input type="text" name="species_name" class="form-control" id="species_name">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'id';
											break;

										case 'lkp_units': ?>
											<div class="row lkp_units">
												<div class="col-4">
													<label>Unit Name*</label>
													<input type="text" name="unit_name" class="form-control" id="unit_name">
												</div>

												<div class="col-4">
													<label>Unit Description</label>
													<input type="text" name="unit_description" class="form-control" id="unit_description">
												</div>

												<div class="col-4">
													<label>Unit Type*</label>
													<select name="unit_type" class="form-control" id="unit_type">
														<option value="area">Area</option>
														<option value="weight">Weight</option>
													</select>
												</div>

												<div class="col-4">
													<label>Equivalent in ha/kg*</label>
													<input type="text" name="equivalent" class="form-control" id="equivalent">
												</div>

												<div class="col-4">
													<label>Standard / Local  <a href="#" data-toggle="tooltip" class="pull-right" title='Choose "Standard" if there is an official, universally accepted definition for the unit. Choose "Local" if the definition changes from place to place.' style="margin-left: 10px;"><i class="fa fa-info-circle" style="font-size:15px"></i></a></label>
													<select name="standard_local" class="form-control" id="standard_local">
														<option value="Standard">Standard</option>
														<option value="Local">Local</option>
													</select>
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'unit_id';
											break;

										case 'lkp_category': ?>
											<div class="row lkp_species">
												<div class="col-4">
													<label>Category Name*</label>
													<input type="text" name="category_name" class="form-control" id="category_name">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'category_id';
											break;

										case 'lkp_livestock_sales': ?>
											<div class="row lkp_species">
												<div class="col-4">
													<label>Livestock Sales Name*</label>
													<input type="text" name="livestock_sale_name" class="form-control" id="livestock_sale_name">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'id';
											break;

										case 'lkp_income_activities': ?>
											<div class="row lkp_species">
												<div class="col-4">
													<label>Select Category*</label>
													<select name="incomeactivity_category" class="form-control" id="incomeactivity_category">
														<option value="">Select Category</option>
														<?php foreach ($dropdowns['categoryArray'] as $key => $value) { ?>
															<option value="<?php echo $value['category_id']; ?>"><?php echo $value['category_Name']; ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="col-4">
													<label>Name*</label>
													<input type="text" name="incomeactivity_name" class="form-control" id="incomeactivity_name">
												</div>
											</div>
											<?php 
											$tablePrimaryId = 'id';
											break;
									} ?>
									<button name="submit" class="pull-right btn btn-success mt-10 mb-10"><i class="fa fa-upload" aria-hidden="true"></i> Submit Data</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row main-content mb-0 mt-10 pb-0">
                <!-- <div class="col-md-12">
                    <button id="addDataBtn" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Data</button>
                </div> -->
                <div class="col-md-12">	    	
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Sl.no</th>
												<?php foreach ($columnname as $key => $cname) { ?>
													<th><?php echo $cname; ?></th>	
												<?php } ?>
												<?php if($this->uri->segment(3) != 'lkp_currency'){ ?>
													<th>Action</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											<?php if(count($getdata) > 0){
												$i = 1;
												foreach ($getdata as $dkey => $data) { ?>
													<tr id="updatedata_<?php echo $data[$tablePrimaryId]; ?>">
														<td><?php echo $i; ?></td>
														<?php foreach ($columnname as $ckey => $cvalue) { 
															if($this->uri->segment(3) == 'lkp_currency' && $ckey == 'world_region_id') { ?>
																<td><?php echo $data['world_region_name']; ?></td>
															<?php } elseif($this->uri->segment(3) == 'lkp_currency' && $ckey == 'country_id'){ ?>
																<td><?php echo $data['name']; ?></td>
															<?php } elseif($this->uri->segment(3) == 'lkp_income_activities' && $ckey == 'category_id'){ ?>
																<td><?php echo $data['category_Name']; ?></td>
															<?php }elseif($this->uri->segment(3) == 'lkp_animal_type' && $ckey == 'livestock_id'){ ?>
																<td><?php echo $data['livestockname']; ?></td>
															<?php } else {
																if($data['user_id'] != ''){ ?>
																	<td><input type='text' class="form-control" name="<?php echo $ckey; ?>" value="<?php echo $data[$ckey]; ?>" readonly></td>
																<?php } else { ?>
																	<td><?php echo $data[$ckey]; ?></td>
																<?php }
															}
														}
														if($this->uri->segment(3) != 'lkp_currency'){ ?>
															<td style="width: 170px;">
																<?php if($data['user_id'] != ''){ ?>
																	<div class="btn btn-success btn-sm editData" data-tablePrimaryId = "<?php echo $tablePrimaryId; ?>" data-recordid = "<?php echo $data[$tablePrimaryId]; ?>" data-status="active">Edit</div>
																	<div class="btn btn-success btn-sm updatedata hidden" data-tablePrimaryId = "<?php echo $tablePrimaryId; ?>" data-recordid = "<?php echo $data[$tablePrimaryId]; ?>" data-status="active" >Update</div>
																	<div class="btn btn-danger btn-sm change-status-btn" data-tablePrimaryId = "<?php echo $tablePrimaryId; ?>" data-recordid = "<?php echo $data[$tablePrimaryId]; ?>" data-status="active">Delete</div>
																<?php } ?>
															</td>
														<?php } ?>
													</tr>
													<?php $i++; 
												}
											}else{ ?>
												<tr>
													<td colspan="7">No data found</td>
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
  	$(function(){

		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});

  		$('#addData').on('click', function(){
  			$('.addDataDiv').show();
  		});

  		$('.editData').on('click', function(){
  			const recordid = $(this).data('recordid');
            var idname = "updatedata_"+recordid;

            $('#'+idname).find('input').each(function () {
			    $(this).removeAttr('readonly');
			});

			$(this).html('Update');
  			$(this).addClass('hidden');
  			$(this).closest('td').find('.updatedata').removeClass('hidden');
  		});

  		$('#currencyId').on('change', function(){
  			var currencyId = $(this).val();
  			$.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>lookup_tables/getCurrencyInfo',
                data: {
                	currencyId : currencyId
                },
                dataType: "json",
                success: function(response) {
                 	$('#default_value_in_USD').val(response.getCurrencyData.default_value_in_USD);
                 	$('#current_exchange_rate').val(response.getCurrencyData.current_exchange_rate);
                }
            });
  		});

  		$('body').on('click', '.updatedata', function(){
  			const tablePrimaryId = $(this).data('tableprimaryid');
            const recordid = $(this).data('recordid');
            var tablename = '<?php echo $this->uri->segment(3); ?>';
            var idname = "updatedata_"+recordid;

            var form = $('#'+idname);

            var select = form.find('select'),
	        input = form.find('input'),
	        requestString = '{';
		    for (var i = 0; i < select.length; i++) {
		        requestString += '"' + $(select[i]).attr('name') + '": "' +$(select[i]).val() + '",';
		    }
		    if (select.length > 0) {
		        requestString = requestString.substring(0, requestString.length - 1);
		    }
		    for (var i = 0; i < input.length; i++) {
		        if ($(input[i]).attr('type') !== 'checkbox') {
		            requestString += '"' + $(input[i]).attr('name') + '":"' + $(input[i]).val() + '",';
		        } else {
		            if ($(input[i]).attr('checked')) {
		                requestString += '"' + $(input[i]).attr('name') +'":"' + $(input[i]).val() +'",';
		            }
		        }
		    }
		    if (input.length > 0) {
		        requestString = requestString.substring(0, requestString.length - 1);
		    }
		    requestString += '}';

		    var formData = $.parseJSON(requestString);
			console.log(formData);
		    formData.tablePrimaryId = tablePrimaryId;
		    formData.recordid = recordid;
		    formData.tablename = tablename;
		    var errorCount = 0;
		    var msg = ''
		    if(formData.tablename == 'lkp_animal_type') {
				if(parseInt(formData.max_wt) < parseInt(formData.min_wt)){
					msg = 'Max value should be greater than min value.';
					errorCount++;
				}

				if(formData.lactating != 'Yes' && formData.lactating != 'No'){
					msg = 'Lactating value can be either Yes or No';
					errorCount++;
				}

				if(formData.dairy != 'Yes' && formData.dairy != 'No'){
					msg = 'Dairy value can be either Yes or No';
					errorCount++;
				}
            }

            if(errorCount == 0) {
			    $.ajax({
	                type: 'POST',
	                url: '<?php echo base_url(); ?>lookup_tables/editrecord',
	                data: formData,
	                dataType: "json",
	                success: function(response) {
	                    Swal.fire({
	                        title: "Done!",
	                        text: response.msg,
	                        icon: "success",
	                        confirmButtonText: "OK"
	                    }).then(() => {
	                        window.location.reload(); // Reload the page after the alert is closed
	                    });
	                },
	                error: function(xhr, status, error) {
	                    console.error('Status Code:', xhr.status);
	                    console.error('Error details:', xhr.responseText);
	                    Swal.fire("Error!", 'An error occurred while changing the project status. Please try again.', "error");
	                }
	            });
	        }else {
	        	Swal.fire({
                    title: "Error!",
                    text: msg,
                    icon: "error",
                    confirmButtonText: "OK"
                });
	        }
  		});

  		var className = '<?php echo $this->uri->segment(3); ?>';

  		$('button[name="submit"]').on('click', function (event) {
      		event.preventDefault();
      		$('button[name="submit"]').prop('disabled', true);
	  		var tablename = '<?php echo $this->uri->segment(3); ?>';
	  		var errorCount = 0;
	  		var msg = 'Please fill data in all the field before clicking on submit.';
	  		switch(tablename){
	  			case 'lkp_animal_type':
	  				var formData = {
	  					livestock_id : $('#livestock_id').val(),
		                animal_type_name: $('#animal_type_name').val(),
						lactating : $('input[name="lactating"]:checked').val(),
						dairy : $('input[name="dairy"]:checked').val(),
		                min_wt: $('#min_wt').val(),
		                max_wt: $('#max_wt').val(),
		                type: tablename
		            };
		            if(formData.livestock_id == '' || formData.animal_type_name == '' || formData.min_wt == '' || formData.max_wt == '') {
		            	errorCount++;
		            }

		            if(parseInt(formData.max_wt) < parseInt(formData.min_wt)) {
		            	errorCount++;
		            	msg = 'Max value should be greater than min value.';
		            }
	  				break;

	  			case 'lkp_communities_type':
	  				var formData = {
		                communities_type_name: $('#communities_type_name').val(),
		                type: tablename
		            };

		            if(formData.communities_type_name == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_crop':
	  				var formData = {
		                crop_name: $('#crop_name').val(),
						harvest_index: $('#harvest_index').val(),
						dry_matter_content: $('#dry_matter_content').val(),
						metabolisable_energy: $('#metabolisable_energy').val(),
						crude_protein_content: $('#crude_protein_content').val(),
						ref_source_info: $('#ref_source_info').val(),
		                type: tablename
		            };

		            if(formData.crop_name == '' || formData.harvest_index == '' || formData.dry_matter_content == '' || formData.metabolisable_energy == '' || formData.crude_protein_content == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_currency':
	  				var formData = {
		                currencyId: $('#currencyId').val(),
		                default_value_in_USD: $('#default_value_in_USD').val(),
		                current_exchange_rate: $('#current_exchange_rate').val(),
		                type: tablename
		            };

		            if(formData.currencyId == '' || formData.default_value_in_USD == '' || formData.default_value_in_USD == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_fodder_type':
	  				var formData = {
		                fodder_type: $('#fodder_type').val(),
						kg_dry_matter: $('#kg_dry_matter').val(),
						metabolisable_energy: $('#metabolisable_energy').val(),
						crude_protein_content: $('#crude_protein_content').val(),
						reference: $('#reference').val(),
		                type: tablename
		            };

		            if(formData.fodder_type == '' || formData.kg_dry_matter == '' || formData.metabolisable_energy == '' || formData.crude_protein_content == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_feed_type':
	  				var formData = {
		                feed_type: $('#feed_type').val(),
						dry_matter_content: $('#dry_matter_content').val(),
						metabolisable_energy: $('#metabolisable_energy').val(),
						crude_protein_content: $('#crude_protein_content').val(),
						reference: $('#reference').val(),
		                type: tablename
		            };

		            if(formData.feed_type == '' || formData.dry_matter_content == '' || formData.metabolisable_energy == '' || formData.crude_protein_content == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_livestock':
	  				var formData = {
		                livestock_name: $('#livestock_name').val(),
		                type: tablename
		            };

		            if(formData.livestock_name == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_species':
	  				var formData = {
		                species_name: $('#species_name').val(),
		                type: tablename
		            };

		            if(formData.species_name == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_units':
	  				var formData = {
		                unit_name: $('#unit_name').val(),
		                unit_description: $('#unit_description').val(),
		                unit_type: $('#unit_type').val(),
						equivalent: $('#equivalent').val(),
						standard_local: $('#standard_local').val(),
		                type: tablename
		            };

		            if(formData.unit_name == '' ||  formData.unit_type == '' || formData.equivalent == '' || formData.standard_local == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_category':
	  				var formData = {
	  					category_name: $('#category_name').val(),
	  					type: tablename
	  				};

	  				if(formData.category_name == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_livestock_sales':
	  				var formData = {
	  					livestock_sale_name: $('#livestock_sale_name').val(),
	  					type: tablename
	  				};

	  				if(formData.livestock_sale_name == '') {
		            	errorCount++;
		            }
	  				break;

	  			case 'lkp_income_activities':
	  				var formData = {
	  					incomeactivity_category: $('#incomeactivity_category').val(),
	  					incomeactivity_name: $('#incomeactivity_name').val(),
	  					type: tablename
	  				};

	  				if(formData.incomeactivity_category == '' || formData.incomeactivity_name == '') {
		            	errorCount++;
		            }
	  				break;
	  		}

	  		if(errorCount == 0) {
		  		// Send AJAX request
	            $.ajax({
	                type: 'POST',
	                url: '<?php echo base_url(); ?>lookup_tables/addData', // Use the same endpoint
	                data: formData,
	                dataType: "JSON",
	                success: function(response) {
	                	if(response.insertstatus == 1) {
		                	Swal.fire({
		                        title: "Done!",
		                        text: 'Data added successfully!',
		                        icon: "success",
		                        confirmButtonText: "OK"
		                    }).then(() => {
		                        setTimeout(() => {
		                            window.location.reload();
		                        }, 500); // Add a slight delay
		                    });
		                } else {
		                	Swal.fire({
		                        title: "Failed!",
		                        text: response.msg,
		                        icon: "error",
		                        confirmButtonText: "OK"
		                    }).then(() => {
		                        setTimeout(() => {
		                            window.location.reload();
		                        }, 500); // Add a slight delay
		                    });
		                }
	                },
	                error: function(xhr, status, error) {
	                    Swal.fire({
	                        title: "Error!",
	                        text: 'Failed to add data. Please try again.',
	                        icon: "error",
	                        confirmButtonText: "OK"
	                    });
	                }
	            });
	        } else {
	        	$('button[name="submit"]').prop('disabled', false);
	        	Swal.fire({
                    title: "Error!",
                    text: msg,
                    icon: "error",
                    confirmButtonText: "OK"
                });	
	        }
	  	});


		$('.change-status-btn').on('click', function() {
            const tablePrimaryId = $(this).data('tableprimaryid');
            const recordid = $(this).data('recordid');

            const confirmationMessage = `Are you sure you want to Delete?`;

            Swal.fire({
                title: "Confirm Action",
                text: confirmationMessage,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, keep it"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url(); ?>lookup_tables/deleterecord',
                        data: {
                            tablePrimaryId: tablePrimaryId,
                            recordid: recordid,
                            tablename : className,
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Done!",
                                text: 'Deleted successfully!',
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.reload(); // Reload the page after the alert is closed
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Status Code:', xhr.status);
                            console.error('Error details:', xhr.responseText);
                            Swal.fire("Error!", 'An error occurred while changing the project status. Please try again.', "error");
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "The project status remains unchanged.", "info");
                }
            });
        });
  	});
</script>