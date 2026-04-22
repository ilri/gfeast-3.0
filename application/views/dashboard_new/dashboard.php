<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.css" />

<script src="<?php echo base_url(); ?>includeout/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/leaflet.markercluster.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.js"></script>

<link href="<?php echo base_url(); ?>includeout/fselect/fSelect.css" rel="stylesheet">

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body">
			<div class="row">
				<div class="col-xl-12 col-lg-12">
					<div class="card p-10">						
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>District</label> <br>
									<select class="district form-control" name="district[]" multiple="multiple" title="Select district">
										<?php foreach ($district_list as $key => $dist) { ?>
											<option value="<?php echo $dist['district_id']; ?>"><?php echo $dist['district_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">									
									<label>Block Name</label> <br>
									<select class="block form-control" name="block[]" multiple="multiple" title="Select block">
										
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Village Name</label><br>
									<select class="village form-control" name="village[]" multiple="multiple" title="Select village">
										
									</select>
								</div>
							</div>

							<div class="col-sm-2">
								<button type="button" class="btn btn-sm btn-success mt-30 get_filterdata">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
					<div class="card p-10 get_location_info" data-locationtype="olmfarmers_registration" data-surveyid="1" style="cursor: pointer;">
						<div class="media d-flex">
							<div class="align-self-center">
								<img src="<?php echo base_url(); ?>includeout/images/former.png" style="height:70px;">
							</div>
							<div class="media-body text-white text-right">
								<h4 class="text-warning"><strong>Farmers Registered</strong></h4>
								<h4 class=""><strong id="farmer_registrations_val"><?php echo $farmer_registrations; ?></strong></h4>
							</div>
						</div>
					</div>

					<div class="card p-10 get_location_info" data-locationtype="msoil_registration" data-surveyid="13" style="cursor: pointer;">
						<div class="media d-flex">
							<div class="align-self-center">
								<img src="<?php echo base_url(); ?>includeout/images/soil.png" style="height:50px;">
							</div>
							<div class="media-body text-white text-right">
								<h4 class="text-warning"><strong>Soil Samples</strong></h4>
								<h4 class=""><strong id="soil_samples_val"><?php echo $soil_samples; ?></strong></h4>
							</div>
						</div>
					</div>

					<div class="card p-10 get_location_info" data-locationtype="activities" style="cursor: pointer;">
						<div class="media d-flex">
							<div class="align-self-center">
								<img src="<?php echo base_url(); ?>includeout/images/activity.png" style="height:40px;">
							</div>
							<div class="media-body text-white text-right">
								<h4 class="text-warning"><strong>Activities</strong></h4>
								<h4 class=""><strong id="activity_uploads_val"><?php echo $activity_uploads; ?></strong></h4>
							</div>
						</div>
					</div>

					<div class="card p-10 get_location_info" data-locationtype="visits" style="cursor: pointer;">
						<div class="media d-flex">
							<div class="align-self-center">
								<img src="<?php echo base_url(); ?>includeout/images/visit.png" style="height:40px;">
							</div>
							<div class="media-body text-white text-right">
								<h4 class="text-warning"><strong>Visits</strong></h4>
								<h4 class=""><strong id="visit_uploads_val"><?php echo $visit_uploads; ?></strong></h4>
							</div>
						</div>
					</div>
					<div class="card p-10 get_location_info" data-locationtype="surveys" style="cursor: pointer;">
						<div class="media d-flex">
							<div class="align-self-center">
								<img src="<?php echo base_url(); ?>includeout/images/survey.png" style="height:40px;">
							</div>
							<div class="media-body text-white text-right">
								<h4 class="text-warning"><strong>Surveys</strong></h4>
								<h4 class=""><strong id="survey_uploads_val"><?php echo $survey_uploads; ?></strong></h4>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
					<div class="card p-10 mapdiv">
						<div id="map_element" style="height: 490px; width: 100%;"></div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12" id="general_information">
					<div class="row">
						<div class="col-md-4">
							<div class="card p-10">				            
				            	<h4 class="text-center"><strong>Nature of coconut plantation</strong></h4>
				            	<div id="nature_coconut_plantation" style="height: 400px; width: 100%;"></div>
				            </div>
				        </div>

				        <div class="col-md-4">
				        	<div class="card p-10">				            
				            	<h4 class="text-center"><strong>Crops grown during Rabi (2019-2020)</strong></h4>
				            	<div id="rabicrop_details_graph" style="height: 400px; width: 100%;"></div>
				            </div>
				        </div>

				        <div class="col-md-4">
				        	<div class="card p-10">				            
				            	<h4 class="text-center"><strong>Crops grown during Kharif (2019-2020)</strong></h4>
				            	<div id="kharifcrop_details_graph" style="height: 400px; width: 100%;"></div>
				            </div>
				        </div>

						<div class="col-md-4">
							<div class="card p-10">
								<div class="row">
									<div class="col-md-12">
										<button id="btnExport" onclick="javascript:xport.toCSV('livestock_details_datatable', 'Livestock details');" class="btn btn-success btn-sm pull-right"> <i class="ft-download"></i></button>
									</div>
									<div class="col-md-12">
										<div class="table-responsive">
						                	<table class="table table-bordered" id="livestock_details_datatable">
						                		<thead>
						                			<tr style="background: #cdc4c4;">
						                				<th>Livestock type</th>
						                				<th>Total number</th>
						                			</tr>
						                		</thead>
						                		<tbody>
						                			<?php foreach ($livestock_details as $key => $livestock) { ?>
						                				<tr>
						                					<td style="padding: 5px;"><strong><?php switch ($key) {
						                						case 'Buffalo': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/buffalo.png">
						                							<?php break;

						                						case 'Cows': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/cow.png">
						                							<?php break;

						                						case 'Bulls/Oxen': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/bull.png">
						                							<?php break;

						                						case 'Goat': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/goat.png">
						                							<?php break;

						                						case 'Sheep': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/sheep.png">
						                							<?php break;

						                						case 'Pig': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/pig.png">
						                							<?php break;

						                						case 'Others': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/livestock.png">
						                							<?php break;
						                						
						                						default:
						                							
						                							break;
						                					} ?><?php echo $key; ?></strong></td>
						                					<td style="padding: 5px; text-align: center;"><?php echo $livestock; ?></td>
						                				</tr>
						                			<?php } ?>
						                		</tbody>
						                	</table>
						                </div>
						            </div>
						        </div>
				            </div>
				        </div>

				        <div class="col-md-4">
				        	<div class="card p-10" style="height: 380px;">
				        		<div class="row">
									<div class="col-md-12">
										<button id="btnExport" onclick="javascript:xport.toCSV('poultry_details_datatable', 'Poultry details');" class="btn btn-success btn-sm pull-right"> <i class="ft-download"></i></button>
									</div>
									<div class="col-md-12">
										<div class="table-responsive">
						                	<table class="table table-bordered" id="poultry_details_datatable">
						                		<thead>
						                			<tr style="background: #cdc4c4;">
						                				<th>Poultry type</th>
						                				<th>Total number</th>
						                			</tr>
						                		</thead>
						                		<tbody>
						                			<?php foreach ($poultry_details as $key => $poultry) { ?>
						                				<tr>
						                					<td style="padding: 5px;"><strong><?php switch ($key) {
						                						case 'Chickens': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/hen.png">
						                							<?php break;

						                						case 'Ducks': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/duck.png">
						                							<?php break;

						                						case 'Geese': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/geese.png">
						                							<?php break;

						                						case 'Turkeys': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/turkeys.png">
						                							<?php break;

						                						case 'Others': ?>
						                							<img height="28" src="<?php echo base_url(); ?>includeout/images/livestock.png">
						                							<?php break;
						                						
						                						default:
						                							
						                							break;
						                					} ?><?php echo $key; ?></strong></td>
						                					<td style="padding: 5px; text-align: center;"><?php echo $poultry; ?></td>
						                				</tr>
						                			<?php } ?>
						                		</tbody>
						                	</table>
						                </div>
						            </div>
						        </div>
				            </div>
				        </div>

				        <div class="col-md-4">
				        	<div class="card p-10" style="height: 380px;">
								<h4 class="card-title text-center mb-2"><strong>Number of OLM households sampled/covered and availing OLM benefits:</strong><span class="text-bold-600 h3"> <?php echo $olm_number; ?></span></h4>

								<h4 class="card-title text-center mb-2"><strong>No of household head is member of Cooperative/ producer companies:</strong><span class="text-bold-600 h3"> <?php echo $member_cooperative; ?></span></h4>
							
								<h4 class="card-title text-center mb-1 mt-20"><strong>Households head disaggregation by gender:</strong></h4>
								<div class="row">
									<div class="col-md-6 col-12 text-center">
										<img src="<?php echo base_url(); ?>includeout/images/man-user.png" height="50">
										<h3 class="text-black text-bold-600 pt-1"><?php echo $householdmale_count; ?></h3>
										<p class=" lighten-2 mb-0"><strong>Male</strong></p>
									</div>

									<div class="col-md-6 col-12 text-center">
										<img src="<?php echo base_url(); ?>includeout/images/woman.png" height="50">
										<h3 class="text-black text-bold-600 pt-1"><?php echo $householdfemale_count; ?></h3>
										<p class=" lighten-2 mb-0"><strong>Female</strong></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
    		    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
    		        <div class="card p-10">
		                <h4 class="card-title"><strong> Activities</strong></h4>
		                <div class="table-responsive" id="activity_info">
		                	<table class="table table-bordered">
		                		<thead>
		                			<tr style="background: #cdc4c4;">
		                				<th>Village name</th>
		                				<?php if(count($activity_survey_details) > 0){
		                					foreach ($activity_survey_details as $key => $act) { ?>
		                						<th><?php echo $act['activity_name']; ?></th>
		                					<?php }
		                				} ?>
		                			</tr>
		                		</thead>
		                		<tbody>
		                			<?php if(count($datauploaded_villageslist) > 0){
                						foreach ($datauploaded_villageslist as $key => $value) { ?>
                							<tr>
                								<td><?php echo $value['village_name']; ?></td>
	                							<?php foreach ($activity_survey_details as $key => $act) { 
	                								if($act[$value['village_name']] > 0){ ?>
	                									<td><a href="<?php echo base_url(); ?>reports/view_activitydata/<?php echo $act['activity_id'] ?>/<?php echo $value['village_id']; ?>" target="_blank"><?php echo $act[$value['village_name']]; ?></a></td>
	                								<?php }else{ ?>
	                									<td><?php echo $act[$value['village_name']]; ?></td>
	                								<?php } ?>
	                							<?php } ?>
                							</tr>
                						<?php }
                					}else{ ?>
                						<tr>
                							<td colspan="<?php echo count($activity_survey_details)+1; ?>">No activities found</td>
                						</tr>
                					<?php } ?>
		                		</tbody>
		                	</table>
		                </div>
    		        </div>
    		    </div>
           	</div>

           	<div class="row">
    		    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
    		        <div class="card p-10">
		                <h4 class="card-title"><strong>Farmer details </strong></h4>
		                <div class="table-responsive">
		                	<table class="table table-bordered" id="beneficiary_data">
		                		<thead>
		                			<tr style="background: #cdc4c4;">
		                				<th>Farmer Id</th>
		                				<th>Farmer Name</th>
		                				<th>Name Of Village </th>
		                			</tr>
		                		</thead>
		                		<tbody id="beneficiary_data_info">
		                			<?php if(count($farmer_data) > 0){ 
		                				foreach ($farmer_data as $key => $value) { 
		                					$data_array = json_decode($value['form_data'], true); 
		                					if($data_array['field_2031'] == 1){ ?>
			                				 	<tr data-id="<?php echo $value['id']; ?>">
					                				<td><a class="text-primary font-weight-600" href="<?php echo base_url(); ?>dashboard_new/farmerdetails/<?php echo $value['data_id']; ?>" target="_blank"><?php echo (isset($data_array['field_1670'])) ? $data_array['field_1670'] : 'N/A'; ?></a></td>
					                				<td><?php echo (isset($data_array['field_1673'])) ? $data_array['field_1673'] : 'N/A'; ?> <?php echo (isset($data_array['field_1674'])) ? $data_array['field_1674'] : 'N/A'; ?></td>
					                				<td>
					                					<?php if(isset($data_array['field_1669'])){
															if($data_array['field_1669'] == NULL || $data_array['field_1669'] == ''){
																echo "N/A";
															}else{
																foreach ($village_list as $key => $village) {
																	if($data_array['field_1669'] == $village['village_id']){
																		echo $village['village_name'];
																	}
																}
															}
														}else{
															echo "N/A";
														} ?>
													</td>
					                			</tr>
		                					<?php }
		                				}
			                		}else{ ?>
		                				<tr>
		                					<td colspan="4">No data found</td>
		                				</tr>
		                			<?php } ?>			                			
		                		</tbody>
		                	</table>
		                </div>
		            </div>
		            <button class="btn btn-sm btn-success pull-right" id="loadmore" style="margin-top: -20px;">View more</button>
    		    </div>
    		</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>include/vendors/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript">
	$(function() {
	    $("[name='district[]'], [name='block[]'], [name='village[]']").selectpicker({
		   	actionsBox: true,
		   	liveSearch: true
		});

		$('select[name="district[]"]').change(function(){
			$('select[name="block[]"]').html('');
			$('select[name="village[]"]').html('');
			$('.block').selectpicker('refresh');
			$('.village').selectpicker('refresh');

			var district_id = $('select[name="district[]"]').val();

			$.ajax({
	      		url : '<?php echo base_url(); ?>dashboard_new/get_blockslist',
	      		type: 'POST',
	      		dataType : 'json',
	      		data : {
	      			district_id : district_id
	      		},
	      		error : function(){
	      			
	      		},
	      		success : function(response){
	      			if(response.status == 1){
	      				var options="";							
						response.block_list.forEach(function(block, index){
							options+='<option value="'+block.block_id+'">'+block.block_name+'</option>';
						});
						$('select[name="block[]"]').html(options);
						$('.block').selectpicker('refresh');
					}else{
						$('select[name="block[]"]').html('');
						$('.block').selectpicker('refresh');
					}
	      		}
	      	});
		});

		$('select[name="block[]"]').change(function(){
			$('select[name="village[]"]').html('');			
			$('.village').selectpicker('refresh');

			var block_id = $('select[name="block[]"]').val();

			$.ajax({
	      		url : '<?php echo base_url(); ?>dashboard_new/get_villagelist',
	      		type: 'POST',
	      		dataType : 'json',
	      		data : {
	      			block_id : block_id
	      		},
	      		error : function(){
	      			
	      		},
	      		success : function(response){
	      			if(response.status == 1){
	      				var options="";							
						response.village_list.forEach(function(village, index){
							options+='<option value="'+village.village_id+'">'+village.village_name+'</option>';
						});						
						$('select[name="village[]"]').html(options);
						$('.village').selectpicker('refresh');
					}else{
						$('select[name="village[]"]').html('');
						$('.village').selectpicker('refresh');
					}
	      		}
	      	});
		});

		$('body').on('click', '.get_location_info', function(){
			$elem = $(this);
			var district_ids = $('select[name="district[]"]').val();
			var block_ids = $('select[name="block[]"]').val();
			var village_ids = $('select[name="village[]"]').val();

			var locationtype = $elem.data('locationtype');
			var surveyid = $elem.data('surveyid');

			var query_data = {
        		district_ids : district_ids,
        		block_ids : block_ids,
        		village_ids : village_ids,
        		locationtype : locationtype,
        		surveyid : surveyid
        	};

        	$.ajax({
	            url : "<?php echo base_url(); ?>/dashboard_new/get_location_info",
	            data : query_data,
	            type : "POST",
	            dataType : "JSON",
	            error:function(){
	                $.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error'
					});
	            },
	            success:function(response){
	            	if(response.status == 1){
	            		$('.mapdiv').html('<div id="map_element" style="height: 490px; width: 100%;"></div>');

	            		var addressPoints = response.location_data;
						map_content(addressPoints);
	            	}
	            }
	        });
		});

		$('body').on('click', '.get_filterdata', function(){
			$('#farmer_registrations_val').html('Please wait...');
    		$('#soil_samples_val').html('Please wait...');
    		$('#activity_uploads_val').html('Please wait...');
    		$('#visit_uploads_val').html('Please wait...');
    		$('#survey_uploads_val').html('Please wait...');
    		$('#activity_info').html('Please wait...');
    		$('#general_information').html('');

    		var loadingText = '<tr class="loading"><td colspan="20">Please Wait... Getting Data...</td></tr>';
			$('#beneficiary_data_info').html(loadingText);

			var district_ids = $('select[name="district[]"]').val();
			var block_ids = $('select[name="block[]"]').val();
			var village_ids = $('select[name="village[]"]').val();

			var query_data = {
        		district_ids : district_ids,
        		block_ids : block_ids,
        		village_ids : village_ids
        	};

			$.ajax({
	            url : "<?php echo base_url(); ?>/dashboard_new/get_filterdata",
	            data : query_data,
	            type : "POST",
	            dataType : "JSON",
	            error:function(){	            	
	            	$('#beneficiary_data').find('.loading').remove();
	                $.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error'
					});
	            },
	            success:function(response){
	            	if(response.status == 1){
	            		$('#farmer_registrations_val').html(response.farmer_registrations);
	            		$('#soil_samples_val').html(response.soil_samples);
	            		$('#activity_uploads_val').html(response.activity_uploads);
	            		$('#visit_uploads_val').html(response.visit_uploads);
	            		$('#survey_uploads_val').html(response.survey_uploads);

	            		var HTML_ACTIVITY = ``;

	            		HTML_ACTIVITY += `<table class="table table-bordered">
	                		<thead>
	                			<tr style="background: #cdc4c4;">
	                				<th>Village name</th>`;
	                				if(response.activity_survey_details.length > 0){
	                					response.activity_survey_details.forEach(function(act, index){
	                						HTML_ACTIVITY += `<th>`+act.activity_name+`</th>`;
	                					});
	                				}
	                			HTML_ACTIVITY += `</tr>
	                		</thead>
	                		<tbody>`;
	                			if(response.datauploaded_villageslist.length > 0){
	                				response.datauploaded_villageslist.forEach(function(value, index){
	                					HTML_ACTIVITY += `<tr>
	                						<td>`+value.village_name+`</td>`;
	                						if(response.activity_survey_details.length > 0){
	                							response.activity_survey_details.forEach(function(act, index){
	                								if(act[value.village_name] > 0){
	                									HTML_ACTIVITY += `<td><a href="<?php echo base_url(); ?>reports/view_activitydata/`+act.activity_id+`/`+value.village_id+`" target="_blank">`+act[value.village_name]+`</a></td>`;
	                								}else{
	                									HTML_ACTIVITY += `<td>`+act[value.village_name]+`</td>`;
	                								}
	                								
	                							});
	                						}
	                					HTML_ACTIVITY += `</tr>`;
	                				});
	                			}else{
	                				HTML_ACTIVITY += `<tr>
		                				<td colspan="2">No activities found</td>
		                			</tr>`;
	                			}
	                		HTML_ACTIVITY += `</tbody>
	                	</table>`;

	                	$('#activity_info').html(HTML_ACTIVITY);

	                	var HTML = ``;

	                	response.farmer_data.forEach(function(data, index){
	            			var jsondata = jQuery.parseJSON(data.form_data);

	            			HTML += `<tr data-id="`+data.id+`">
                				<td><a class="text-primary font-weight-600" href="<?php echo base_url(); ?>dashboard_new/farmerdetails/<?php echo $value['data_id']; ?>" target="_blank">`+(typeof jsondata['field_1670'] === 'undefined' ? "N/A" : jsondata['field_1670'])+`</a></td>
                				<td>`+(typeof jsondata['field_1673'] === 'undefined' ? "N/A" : jsondata['field_1673'])+` `+(typeof jsondata['field_1674'] === 'undefined' ? "N/A" : jsondata['field_1674'])+`</td>`;
                				var village_val = '';
                				if(typeof jsondata['field_1669'] !== 'undefined'){
									if(jsondata['field_1669'] == null || jsondata['field_1669'] == ''){
										village_val = "N/A";
									}else{
										response.village_list.forEach(function(village, index){
											if(jsondata['field_1669'] == village.village_id){
												village_val = village.village_name;
											}
										}); 
									}
								}else{
									village_val = "N/A";
								}
                				HTML += `<td>`+village_val+`</td>
                			</tr>`;
	            		});

	            		$('#beneficiary_data_info').html(HTML);

	            		var HTML_GENERAL = `<div class="row">
	            			<div class="col-md-4">
				            	<div class="card p-10">
				            		<h4 class="text-center"><strong>Nature of coconut plantation</strong></h4>
				            		<div id="nature_coconut_plantation" style="height: 400px; width: 100%;"></div>
				            	</div>
				            </div>

				            <div class="col-md-4">
				            	<div class="card p-10">
					            	<h4 class="text-center"><strong>Crops grown during Rabi (2019-2020)</strong></h4>
					            	<div id="rabicrop_details_graph" style="height: 400px; width: 100%;"></div>
					            </div>
				            </div>

				            <div class="col-md-4">
				            	<div class="card p-10">
				            		<h4 class="text-center"><strong>Crops grown during Kharif (2019-2020)</strong></h4>
				            		<div id="kharifcrop_details_graph" style="height: 400px; width: 100%;"></div>
				            	</div>
				            </div>

							<div class="col-md-4">
								<div class="card p-10" style="height: 380px;">
									<div class="row">
										<div class="col-md-12">
											<button id="btnExport" onclick="javascript:xport.toCSV('livestock_details_datatable', 'Livestock details');" class="btn btn-success btn-sm pull-right"> <i class="ft-download"></i></button>
										</div>
										<div class="col-md-12">
											<div class="table-responsive">
							                	<table class="table table-bordered" id="livestock_details_datatable">
							                		<thead>
							                			<tr style="background: #cdc4c4;">
							                				<th>Livestock type</th>
							                				<th>Total number</th>
							                			</tr>
							                		</thead>
							                		<tbody>`;
							                			$.each( response.livestock_details, function( key, livestock ) {
							                				HTML_GENERAL += `<tr>
							                					<td style="padding: 5px;"><strong>`;
							                						switch(key){
							                							case 'Buffalo':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/buffalo.png">`+key;
								                							break;

								                						case 'Cows':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/cow.png">`+key;
								                							break;

								                						case 'Bulls/Oxen':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/bull.png">`+key;
								                							break;

								                						case 'Goat':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/goat.png">`+key;
								                							break;

								                						case 'Sheep':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/sheep.png">`+key;
								                							break;

								                						case 'Pig':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/pig.png">`+key;
								                							break;

								                						case 'Others':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/livestock.png">`+key;
								                							break;

								                						default:
							                								HTML_GENERAL += `<p>`+key+`<p>`;	
							                								break;
								                					}
								                					HTML_GENERAL += `</strong>
								                				</td>
							                					<td style="padding: 5px; text-align: center;">`+livestock+`</td>
							                				</tr>`;
							                			});
							                		HTML_GENERAL += `</tbody>
							                	</table>
							                </div>
							            </div>
							        </div>
					            </div>
				            </div>

				            <div class="col-md-4">
				            	<div class="card p-10" style="height: 380px;">
				            		<div class="row">
										<div class="col-md-12">
											<button id="btnExport" onclick="javascript:xport.toCSV('poultry_details_datatable', 'Livestock details');" class="btn btn-success btn-sm pull-right"> <i class="ft-download"></i></button>
										</div>
										<div class="col-md-12">
											<div class="table-responsive">
							                	<table class="table table-bordered" id="poultry_details_datatable">
							                		<thead>
							                			<tr style="background: #cdc4c4;">
							                				<th>Poultry type</th>
							                				<th>Total number</th>
							                			</tr>
							                		</thead>
							                		<tbody>`;
							                			$.each( response.poultry_details, function( key, poultry ) {
							                				HTML_GENERAL += `<tr>
							                					<td style="padding: 5px;"><strong>`;
								                					switch (key) {
								                						case 'Chickens':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/hen.png">`+key;
								                							break;

								                						case 'Ducks':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/duck.png">`+key;
								                							break;

								                						case 'Geese':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/geese.png">`+key;
								                							break;

								                						case 'Turkeys':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/turkeys.png">`+key;
								                							break;

								                						case 'Others':
								                							HTML_GENERAL += `<img height="28" src="<?php echo base_url(); ?>includeout/images/livestock.png">`+key;
								                							break;
								                						
								                						default:
								                							HTML_GENERAL += `<p>`+key+`<p>`;
								                							break;
								                					} HTML_GENERAL += `</strong>
								                				</td>
							                					<td style="padding: 5px; text-align: center;">`+poultry+`</td>
							                				</tr>`;
							                			});
							                		HTML_GENERAL += `</tbody>
							                	</table>
							                </div>
							            </div>
							        </div>
					            </div>
				            </div>
				            
				            <div class="col-md-4">
				            	<div class="card p-10" style="height: 380px;">
									<h4 class="card-title text-center mb-2"><strong>Number of OLM households sampled/covered and availing OLM benefits:</strong><span class="text-bold-600 h3"> `+response.olm_number+`</span></h4>

									<h4 class="card-title text-center mb-2"><strong>No of household head is member of Cooperative/ producer companies:</strong><span class="text-bold-600 h3">`+response.member_cooperative+`</span></h4>
								
									<h4 class="card-title text-center mb-1 mt-20"><strong>Households head disaggregation by gender:</strong></h4>
									<div class="row">
										<div class="col-md-6 col-12 text-center">
											<img src="<?php echo base_url(); ?>includeout/images/man-user.png" height="50">
											<h3 class="text-black text-bold-600 pt-1">`+response.householdmale_count+`</h3>
											<p class=" lighten-2 mb-0"><strong>Male</strong></p>
										</div>

										<div class="col-md-6 col-12 text-center">
											<img src="<?php echo base_url(); ?>includeout/images/woman.png" height="50">
											<h3 class="text-black text-bold-600 pt-1">`+response.householdfemale_count+`</h3>
											<p class=" lighten-2 mb-0"><strong>Female</strong></p>
										</div>
									</div>
								</div>
							</div>
				        </div>`;

				        $('#general_information').html(HTML_GENERAL);

				        piechart('nature_coconut_plantation', response.nature_coconut_plantation_graph);
    					monthsimple_barchart('rabicrop_details_graph', response.rabicrop_details_graph, 'Number of farmers');
    					monthsimple_barchart('kharifcrop_details_graph', response.kharifcrop_details_graph, 'Number of farmers');

	            		$('.mapdiv').html('<label>Uploads From Ground</label>\
							<div id="map_element" style="height: 500px; width: 100%;"></div>');

	            		var addressPoints = response.location_data;
						map_content(addressPoints);
	            	}
	            }
	        });
		});

		$('body').on('click', '#loadmore', function(){
			var district_ids = $('select[name="district[]"]').val();
			var block_ids = $('select[name="block[]"]').val();
			var village_ids = $('select[name="village[]"]').val();
        	var last_id = $('#beneficiary_data tr:last').data('id');

        	var query_data = {
        		district_ids : district_ids,
        		block_ids : block_ids,
        		village_ids : village_ids,
        		last_id : last_id
        	};

        	get_farmerdata(query_data);
		});

		function get_farmerdata(query_data){
			var loadingText = '<tr class="loading"><td colspan="20">Please Wait... Getting Data...</td></tr>';
			if($('#beneficiary_data').html().length === 0) {
				$('#beneficiary_data').html(loadingText);
			} else {
				$('#beneficiary_data').append(loadingText);
			}

			$.ajax({
	            url : "<?php echo base_url(); ?>/dashboard_new/loadmore_beneficarydata",
	            data : query_data,
	            type : "POST",
	            dataType : "JSON",
	            error:function(){	            	
	            	$('#beneficiary_data').find('.loading').remove();
	                $.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error'
					});
	            },
	            success:function(response){
	            	$('#beneficiary_data').find('.loading').remove();
	            	if(response.status == 0){

	            	}else{
	            		var HTML = ``;
	            		
	            		if(response.farmer_data.length == 0) {
	            			$('#loadmore').addClass('hidden');
		            		$.toast({
								heading: 'End of Data!',
								text: 'No more data found.',
								icon: 'info'
							});
	            			return false;
	            		}

	            		response.farmer_data.forEach(function(data, index){
	            			var jsondata = jQuery.parseJSON(data.form_data);

	            			HTML += `<tr data-id="`+data.id+`">
                				<td><a class="text-primary font-weight-600" href="<?php echo base_url(); ?>dashboard_new/farmerdetails/<?php echo $value['data_id']; ?>" target="_blank">`+(typeof jsondata['field_1670'] === 'undefined' ? "N/A" : jsondata['field_1670'])+`</a></td>
                				<td>`+(typeof jsondata['field_1673'] === 'undefined' ? "N/A" : jsondata['field_1673'])+` `+(typeof jsondata['field_1674'] === 'undefined' ? "N/A" : jsondata['field_1674'])+`</td>`;
                				var village_val = '';
                				if(typeof jsondata['field_1669'] !== 'undefined'){
									if(jsondata['field_1669'] == null || jsondata['field_1669'] == ''){
										village_val = "N/A";
									}else{
										response.village_list.forEach(function(village, index){
											if(jsondata['field_1669'] == village.village_id){
												village_val = village.village_name;
											}
										}); 
									}
								}else{
									village_val = "N/A";
								}
                				HTML += `<td>`+village_val+`</td>
                			</tr>`;
	            		});

	            		$('#beneficiary_data').append(HTML);
	            	}
	            }
	        });
		}
	});
		
	var addressPoints = <?php echo json_encode($location_data); ?>;
	map_content(addressPoints);

	function map_content(addressPoints){
		var mapIndividualMarkers = [];
		var LeafIcon = L.Icon.extend({
			options: {
				// shadowUrl: '<?php echo base_url(); ?>uploads/leaflet/pin1.png',
				iconSize:     [14, 14], // size of the icon
				// shadowSize:   [0, 0], // size of the shadow
				iconAnchor:   [7, 7], // point of the icon which will correspond to marker's location
				// shadowAnchor: [7, 7],  // the same for the shadow
				popupAnchor:  [7, 7] // point from which the popup should open relative to the iconAnchor
			}
		});
		var Khordha = new LeafIcon({iconUrl: '<?php echo base_url(); ?>uploads/leaflet/pin1.png'}),
		Puri = new LeafIcon({iconUrl: '<?php echo base_url(); ?>uploads/leaflet/pin2.png'}),
		Koraput = new LeafIcon({iconUrl: '<?php echo base_url(); ?>uploads/leaflet/pin3.png'}),
		Nabarangpur = new LeafIcon({iconUrl: '<?php echo base_url(); ?>uploads/leaflet/pin4.png'}),
		Gajapati = new LeafIcon({iconUrl: '<?php echo base_url(); ?>uploads/leaflet/pin5.png'}),
		Rayagada = new LeafIcon({iconUrl: '<?php echo base_url(); ?>uploads/leaflet/pin6.png'});
		
		var leafletLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
			maxNativeZoom: 19,
			maxZoom: 27
		}),
		googleSatelliteLayer = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
			subdomains:['mt0','mt1','mt2','mt3'],
			maxNativeZoom: 19,
			maxZoom: 27
		});
		
		var map = L.map('map_element', {
			layers: [leafletLayer]
		}).setView([19, 82], 7);
		// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		// 	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
		// }).addTo(map);

		var baseLayers = {
			"Street": leafletLayer,
			"Satellite": googleSatelliteLayer
		};
		// Use the custom grouped layer control, not "L.control.layers"
		L.control.groupedLayers(baseLayers).addTo(map);

		var markers = L.markerClusterGroup();
		for (var i = 0; i < addressPoints.length; i++) {
			var a = addressPoints[i];
			var title = a[2];
			var icon = null;
			switch(a[3]) {
				case 'Khordha':
				icon = Khordha;
				break;

				case 'Puri':
				icon = Puri;
				break;

				case 'Koraput':
				icon = Koraput;
				break;

				case 'Nabarangpur':
				icon = Nabarangpur;
				break;

				case 'Gajapati':
				icon = Gajapati;
				break;

				case 'Rayagada':
				icon = Rayagada;
				break;
			};
			var marker = L.marker(new L.LatLng(a[0], a[1]), {
				title: title,
				icon: icon
			});
			marker.bindPopup(title);
			markers.addLayer(marker);
		}
		map.addLayer(markers);
	}
</script>

<script type="text/javascript">
	piechart('nature_coconut_plantation', <?php echo json_encode($nature_coconut_plantation_graph); ?>);

    function piechart(divid, data){
        am4core.ready(function() {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create(divid, am4charts.PieChart3D);
            chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
            chart.paddingRight = am4core.percent(10);

            chart.legend = new am4charts.Legend();

            chart.data = data;

            var series = chart.series.push(new am4charts.PieSeries3D());
            series.dataFields.value = "count";
            series.dataFields.category = "name";

            chart.exporting.menu = new am4core.ExportMenu();
            chart.exporting.filePrefix = "OLM_download";

            series.ticks.template.disabled = true;
			series.alignLabels = false;
			series.labels.template.text = "{value.percent.formatNumber('#.0')}%";
			series.labels.template.radius = am4core.percent(-40);
			series.labels.template.fill = am4core.color("white");

			series.labels.template.relativeRotation = 90;
        });
    }

    monthsimple_barchart('rabicrop_details_graph', <?php echo json_encode($rabicrop_details_graph); ?>, 'Number of farmers');
    monthsimple_barchart('kharifcrop_details_graph', <?php echo json_encode($kharifcrop_details_graph); ?>, 'Number of farmers');
    
    function monthsimple_barchart(divid, data, yaxis_units){
        am4core.ready(function() {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create(divid, am4charts.XYChart);
            chart.scrollbarX = new am4core.Scrollbar();

            // Add data
            chart.data = data;

            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 20;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;
            valueAxis.min = 0;

            valueAxis.title.text = yaxis_units;
            valueAxis.title.fontWeight = 800;

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.sequencedInterpolation = true;
            series.dataFields.valueY = "count";
            series.dataFields.categoryX = "name";
            series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            series.columns.template.strokeWidth = 0;

            series.tooltip.pointerOrientation = "vertical";

            series.columns.template.column.cornerRadiusTopLeft = 10;
            series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.fillOpacity = 0.8;

            // on hover, make corner radiuses bigger
            var hoverState = series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;

            series.columns.template.adapter.add("fill", function(fill, target) {
              return chart.colors.getIndex(target.dataItem.index);
            });

            var valueLabel = series.bullets.push(new am4charts.LabelBullet());
            valueLabel.label.text = "{valueY}";
            valueLabel.label.truncate = false;
            valueLabel.label.hideOversized = false;
            valueLabel.label.dy = -9;
            valueLabel.label.fontSize = 10;

            // Cursor
            chart.cursor = new am4charts.XYCursor();
            chart.exporting.menu = new am4core.ExportMenu();
            chart.exporting.filePrefix = "OLM_download";
        }); // end am4core.ready()   
    }
</script>

<script type="text/javascript">
  var xport = {
    _fallbacktoCSV: true,  
    toXLS: function(tableId, filename) {   
    this._filename = (typeof filename == 'undefined') ? tableId : filename;

    //var ieVersion = this._getMsieVersion();
    //Fallback to CSV for IE & Edge
    if ((this._getMsieVersion() || this._isFirefox()) && this._fallbacktoCSV) {
      return this.toCSV(tableId);
    } else if (this._getMsieVersion() || this._isFirefox()) {
      alert("Not supported browser");
    }

    //Other Browser can download xls
    var htmltable = document.getElementById(tableId);
    var html = htmltable.outerHTML;

    this._downloadAnchor("data:application/vnd.ms-excel" + encodeURIComponent(html), 'xls'); 
    },
    toCSV: function(tableId, filename) {
    this._filename = (typeof filename === 'undefined') ? tableId : filename;
    // Generate our CSV string from out HTML Table
    var csv = this._tableToCSV(document.getElementById(tableId));
    // Create a CSV Blob
    var blob = new Blob([csv], { type: "text/csv" });

    // Determine which approach to take for the download
    if (navigator.msSaveOrOpenBlob) {
      // Works for Internet Explorer and Microsoft Edge
      navigator.msSaveOrOpenBlob(blob, this._filename + ".csv");
    } else {      
      this._downloadAnchor(URL.createObjectURL(blob), 'csv');      
    }
    },
    _getMsieVersion: function() {
    var ua = window.navigator.userAgent;

    var msie = ua.indexOf("MSIE ");
    if (msie > 0) {
      // IE 10 or older => return version number
      return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
    }

    var trident = ua.indexOf("Trident/");
    if (trident > 0) {
      // IE 11 => return version number
      var rv = ua.indexOf("rv:");
      return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
    }

    var edge = ua.indexOf("Edge/");
    if (edge > 0) {
      // Edge (IE 12+) => return version number
      return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
    }

    // other browser
    return false;
    },
    _isFirefox: function(){
    if (navigator.userAgent.indexOf("Firefox") > 0) {
      return 1;
    }

    return 0;
    },
    _downloadAnchor: function(content, ext) {
      var anchor = document.createElement("a");
      anchor.style = "display:none !important";
      anchor.id = "downloadanchor";
      document.body.appendChild(anchor);

      // If the [download] attribute is supported, try to use it
      
      if ("download" in anchor) {
        anchor.download = this._filename + "." + ext;
      }
      anchor.href = content;
      anchor.click();
      anchor.remove();
    },
    _tableToCSV: function(table) {
    // We'll be co-opting `slice` to create arrays
    var slice = Array.prototype.slice;

    return slice
      .call(table.rows)
      .map(function(row) {
        return slice
          .call(row.cells)
          .map(function(cell) {
            return '"t"'.replace("t", cell.textContent);
          })
          .join(",");
      })
      .join("\r\n");
    }
  };
</script>