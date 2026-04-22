<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.css" />

<script src="<?php echo base_url(); ?>includeout/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/leaflet.markercluster.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.js"></script>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-12">
					<button class="btn btn-sm btn-success pull-right" onclick="window.top.close();">Back</button>
					<h4 class="title">Farmer details</h4>
				</div>
				<div class="col-xl-12 col-lg-12 col-12 mt-10">
					<div class="card">
						<div class="card-content">
							<div class="card-body">
								<div class="row">
									<?php $data_array = json_decode($farmer_details['form_data'], true); ?>
									<div class="col-sm-3">
										<?php if($farmer_image != NULL){ ?>
											<img class="img-align" src="<?php echo base_url(); ?>uploads/survey/<?php echo $farmer_image['file_name']; ?>" style="height: 280px; width: 280px;">
										<?php } ?>
									</div>
									<div class="col-sm-4">
										<h4><strong>Farmer ID: <?php echo (isset($data_array['field_1670'])) ? $data_array['field_1670'] : 'N/A'; ?></strong></h4>
										<h5><span class="font-weight-500">Farmer Name: </span> <?php echo (isset($data_array['field_1673'])) ? $data_array['field_1673'] : 'N/A'; ?> <?php echo (isset($data_array['field_1674'])) ? $data_array['field_1674'] : 'N/A'; ?></h5>
										<h5><span class="font-weight-500">District Name: </span> 
											<?php if(isset($data_array['field_1667'])){
												if($data_array['field_1667'] == NULL || $data_array['field_1667'] == ''){
													echo "N/A";
												}else{
													foreach ($district_list as $key => $dist) {
														if($data_array['field_1667'] == $dist['district_id']){
															echo $dist['district_name'];
														}
													}
												}
											}else{
												echo "N/A";
											} ?>
										</h5>
										<h5><span class="font-weight-500">Block Name: </span> 
											<?php if(isset($data_array['field_1668'])){
												if($data_array['field_1668'] == NULL || $data_array['field_1668'] == ''){
													echo "N/A";
												}else{
													foreach ($block_list as $key => $block) {
														if($data_array['field_1668'] == $block['block_id']){
															echo $block['block_name'];
														}
													}
												}
											}else{
												echo "N/A";
											} ?>
										</h5>
										<h5><span class="font-weight-500">Village Name: </span>
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
										</h5>
										<h5><span class="font-weight-500">Year Of Birth: </span> <?php echo (isset($data_array['field_1676'])) ? $data_array['field_1676'] : 'N/A'; ?></h5>
										<h5><span class="font-weight-500">Primary Mobile Number: </span><?php echo (isset($data_array['field_1677'])) ? $data_array['field_1677'] : 'N/A'; ?></h5>
										<h5><span class="font-weight-500">Primary Occupation: </span> <?php echo (isset($data_array['field_1681'])) ? $data_array['field_1681'] : 'N/A'; ?></h5>
									
										<h5><span class="font-weight-500">Total number of family members: </span><?php echo (isset($data_array['field_1695'])) ? $data_array['field_1695'] : 'N/A'; ?> </h5>
										<h5><span class="font-weight-500">Total male members: </span> <?php echo (isset($data_array['field_1696'])) ? $data_array['field_1696'] : 'N/A'; ?></h5>
										<h5><span class="font-weight-500">Total female members: </span> <?php echo (isset($data_array['field_1698'])) ? $data_array['field_1698'] : 'N/A'; ?></h5>
									</div>

									<div class="col-sm-5">
										<div id="map_element" style="height: 250px; width: 100%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php foreach ($group_info as $key => $group) { ?>
					<div class="col-xl-12 col-lg-12 col-12">
						<h5 class="title"><?php echo $group['group_label']; ?></h5>
						<div class="card p-10">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>Sl.no</th>
											<?php foreach ($group['group_fields'] as $key => $field) { ?>
												<th><?php echo $field['label']; ?></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php if(count($group['group_data']) > 0){
											foreach ($group['group_data'] as $dkey => $data) { 
												$data_array = json_decode($data['formgroup_data'], true); ?>
												<tr>
													<td><?php echo $dkey+1; ?></td>
													<?php foreach ($group['group_fields'] as $fkey => $field) {
														$column = "field_".$field['field_id']; ?>
														<td>
															<?php switch ($field['type']) {
																case 'lkp_crop_types':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_types as $key => $ctype) {
																				if($data_array[$column] == $ctype['type_id']){
																					echo $ctype['type_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crops':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crops as $key => $crop) {
																				if($data_array[$column] == $crop['crop_id']){
																					echo $crop['crop_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crop_intervention':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_intervention as $key => $crop_int) {
																				if($data_array[$column] == $crop_int['intervention_id']){
																					echo $crop_int['intervention_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crop_inputname':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_inputname as $key => $crop_input) {
																				if($data_array[$column] == $crop_input['inputname_id']){
																					echo $crop_input['inputname_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crop_varieties':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_varieties as $key => $variety) {
																				if($data_array[$column] == $variety['variety_id']){
																					echo $variety['variety_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;
																
																default:
																	if(isset($data_array[$column])){
																		if($data_array[$column] == 'null' || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			if(is_array($data_array[$column])) {
																				echo $data_array[$column][0];
																			} else {
																				echo $data_array[$column];
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;
															} ?>
														</td>
													<?php } ?>
												</tr>
											<?php }
										}else{ ?>
											<tr>
												<td colspan="<?php echo count($group['group_fields'])+1; ?>">No data found</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>	
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var addressPoints = <?php echo json_encode($location_data); ?>;
	map_content(addressPoints);

	function map_content(addressPoints){
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
			center: addressPoints[0] ? [addressPoints[0][0], addressPoints[0][1]] : [0, 0],
			layers: [leafletLayer],
			zoom: 9
		});
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
			var marker = L.marker(new L.LatLng(a[0], a[1]), {
				title: title
			});
			marker.bindPopup(title);
			markers.addLayer(marker);
		}
		map.addLayer(markers);
	}
</script>