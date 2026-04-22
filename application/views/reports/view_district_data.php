<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.css" />

<script src="<?php echo base_url(); ?>includeout/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/leaflet.markercluster.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.js"></script>

<style type="text/css">
	td, th{
		text-align: center;
	}
	.filter {
		cursor: pointer;
	}
</style>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 50px;">
			<div class="row">
				<div class="col-md-6">
					<h4 style="font-weight: bold;">
						Beneficiary Data in <?php echo $district['district_name']; ?>
					</h4>
				</div>
				
				<div class="col-md-2">
					<select class="form-control" name="users[]" multiple title="Select user">
						<?php foreach ($user_list as $key => $user) { ?>
							<option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3">
					<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
						<i class="fa fa-calendar"></i>&nbsp;
						<span></span> <i class="fa fa-caret-down"></i>
					</div>
				</div>

				<div class="col-md-1">
					<button class="btn btn-sm btn-success get_data pull-right">Filter Data</button>
				</div>
			</div>

			<div class="row">
				<div class="col-md-5 mt-10">
					<div class="table-responsive" style="height: 600px; overflow-y: auto;">
						<table class="table table-bordered tblexportData beneficiarydata">
							<thead>
								<tr>
									<th>Village Name</th>
									<th>Total Samples</th>
									<!-- <th>Target</th> -->
								</tr>
							</thead>
							<tbody>
								<?php foreach ($village_list as $key => $village) { ?>
									<tr>
										<td><?php echo $village['village_name']; ?></td>
										<td><?php echo $village['total']; ?></td>
										<!-- <td>N/A</td> -->
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-7 mapdiv mt-10">
					<div id="map_element" style="height: 600px; width: 100%;"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 mt-30">
					<div class="card p-10 mb-0">
						<div class="text-right">
							<button onclick="exportToExcel('tblexportData', 'beneficiary-data-<?php echo $district['district_name']; ?>')" class="btn btn-sm btn-success mr-1">
								Export Beneficiary Data
							</button>
							<a href="<?php echo base_url(); ?>uploads/data/<?php echo $form_details['title'].'-'.$district['district_name']; ?>.xlsx" download class="btn btn-sm btn-success">
								Export Full Data
							</a>
						</div>
						<div class="exportContainer hidden"></div>
						<div class="table-responsive">
							<table class="table tblexportData beneficiarydata">
								<thead>
									<tr>
										<th>Sl.no</th>
										<th>Unique Id</th>
										<th>Images</th>
										<?php foreach ($fields as $key => $value) { ?>
											<th><?php
											echo $value['label'];
											switch ($value['field_id']) {
												case '2858':
												echo ' (0-15 cm)';
												break;
												case '2862':
												echo ' (0-30 cm)';
												break;
												case '2864':
												echo ' (30-60 cm)';
												break;
											}
											?></th>
										<?php } ?>
										<?php if($check_group_field > 0){ ?>
											<th>Group data</th>
										<?php } ?>
										<th>Partner Name</th>
										<th>Submitted By</th>
										<th>Submitted Datetime</th>
										<th>Location</th>										
									</tr>
								</thead>
								<tbody id="beneficiary_data">
									<?php if(count($survey_data) > 0){
										foreach ($survey_data as $dkey => $data) { ?>
											<tr data-id="<?php echo $data['id']; ?>">
												<?php $data_array = json_decode($data['form_data'], true); ?>
												<td><?php echo $dkey+1; ?></td>
												<td><?php echo $data['beneficiary_id']; ?></td>
												<td>
													<?php if(count($data['images']) > 0){
														foreach ($data['images'] as $key => $img) { ?>
															<img src="<?php echo base_url(); ?>uploads/survey/<?php echo $img['file_name']; ?>" style="height: 100px; width: 100px; margin-bottom: 10px;">
														<?php }
													}else{
														echo "No images found";
													} ?>
												</td>
												<?php foreach ($fields as $fkey => $field) {
													$column = "field_".$field['field_id']; ?>
													<td>
														<?php switch ($field['type']) {
															case 'lkp_state':
															if($data_array[$column] == NULL || $data_array[$column] == ''){
																echo "N/A";
															}else{
																foreach ($state_list as $key => $state) {
																	if($state['state_id'] == $data_array[$column]){
																		echo $state['state_name'];
																	}
																}
															}
															break;

															case 'lkp_district':
															if($data_array[$column] == NULL || $data_array[$column] == ''){
																echo "N/A";
															}else{
																foreach ($district_list as $key => $district) {
																	if($district['district_id'] == $data_array[$column]){
																		echo $district['district_name'];
																	}
																}
															}
															break;

															case 'lkp_block':
															if($data_array[$column] == NULL || $data_array[$column] == ''){
																echo "N/A";
															}else{
																foreach ($block_list as $key => $block) {
																	if($block['block_id'] == $data_array[$column]){
																		echo $block['block_name'];
																	}
																}
															}
															break;

															case 'lkp_village':
															if($data_array[$column] == NULL || $data_array[$column] == ''){
																echo "N/A";
															}else{
																foreach ($village_list as $key => $village) {
																	if($village['village_id'] == $data_array[$column]){
																		echo $village['village_name'];
																	}
																}
															}
															break;

															case 'lkp_yesno':
															if(!isset($data_array[$column]) || $data_array[$column] == NULL || $data_array[$column] == ''){
																echo "N/A";
															}else{
																if($data_array[$column] == 1){
																	echo "Yes";
																}else{
																	echo "No";
																}
															}
															break;

															case 'lkp_gender':
															if(!isset($data_array[$column]) || $data_array[$column] == NULL || $data_array[$column] == ''){
																echo "N/A";
															}else{
																if($data_array[$column] == 1){
																	echo "Male";
																}else{
																	echo "Female";
																}
															}
															break;
															
															default:
															if(isset($data_array[$column])){
																if($data_array[$column] == NULL || $data_array[$column] == ''){
																	echo "N/A";
																}else{
																	echo $data_array[$column];
																}

															}else{
																echo "N/A";
															}
															break;
														} ?>
													</td>
												<?php } ?>
												<?php if($check_group_field > 0){ ?>
													<td><a href="<?php echo base_url(); ?>reports/groupdata_info/<?php echo ($this->uri->segment(2) == 'view_dashboard') ? 1 : $this->uri->segment(3); ?>/<?php echo $data['data_id']; ?>" target="_blank">View data</a></td>
												<?php } ?>
												<td><?php echo ($data['partner_name'] && strlen($data['partner_name']) > 0) ? $data['partner_name'] : 'N/A'; ?></td>
												<td><?php echo $data['username']; ?></td>
												<td><?php echo $data['reg_date_time']; ?></td>
												<td>
													<?php if($data['location'] == NULL){
														echo "N/A";
													}else{
														echo ($data['location']['lat'] == NULL) ? "N/A" : $data['location']['lat'];
														echo ", ";
														echo ($data['location']['lng'] == NULL) ? "N/A" : $data['location']['lng'];
													} ?>
												</td>
											</tr>
										<?php }
									}else{ ?>
										<tr>
											<td colspan="<?php echo count($fields)+3 ?>">No data found</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col-md-12 mt-10">
					<button type="button" class="btn btn-success pull-right" id="loadm">Load More Data</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>includeout/table_doublescroller/jquery.doubleScroll.js"></script>
<script src="<?php echo base_url(); ?>includeout/bootstrap-select/bootstrap-select.js"></script>

<script type="text/javascript">
	var startdate, enddate;
	
	$(function(){
		$('.table-responsive').doubleScroll();

		$("[name='users[]']").selectpicker({
			actionsBox: true,
			liveSearch: true
		});

		var intiall_date = "05/10/2020";
		var start = moment(intiall_date);
		var end = moment();

		function cb(start, end) {
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			startdate = start;
			enddate = end;
		}

		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);

		cb(start, end);

		$('.get_data').on('click', function(){
			var user_ids = $('select[name="users[]"]').val();
			var start_date = startdate;
			var end_date = enddate;

			var query_data = {
				user_ids : user_ids,
				start_date : formatDate(start_date),
				end_date : formatDate(end_date)
			};

			get_data(query_data);
		});

		$('#loadm').on('click', function(){
			var user_ids = $('select[name="users[]"]').val();
			var start_date = startdate;
			var end_date = enddate;
			var last_id = $('#beneficiary_data tr:last').data('id');

			var query_data = {
				user_ids : user_ids,
				start_date : formatDate(start_date),
				end_date : formatDate(end_date),
				last_id : last_id
			};

			get_data(query_data);
		});

		//Call fn to load markers in the map
		loadMarkers(<?php echo json_encode($survey_locations); ?>, true);
	});

	function formatDate(date) {
		var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;

		return [year, month, day].join('-');
	}

	function get_data(query_data){
		var loadingText = '<tr class="loading"><td colspan="20">Please Wait... Getting Data...</td></tr>';
		if($('#beneficiary_data').html().length === 0) {
			$('#beneficiary_data').html(loadingText);
		} else {
			$('#beneficiary_data').append(loadingText);
		}
		$('#loadm').removeClass('hidden');
		$('#loadm, .get_data').html('Please Wait...');
		$('#loadm, .get_data').prop('disabled', true);
		
		$.ajax({
			url : "<?php echo base_url(); ?>/reports/district_data/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>",
			data : query_data,
			type : "POST",
			dataType : "JSON",
			error:function(){
				$('#loadm').html('Load More Data');
				$('.get_data').html('Filter Data');
				$('#loadm, .get_data').prop('disabled', false);
				$('#beneficiary_data').find('.loading').remove();
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success:function(response){
				$('#loadm').html('Load More Data');
				$('.get_data').html('Filter Data');
				$('#loadm, .get_data').prop('disabled', false);
				$('#beneficiary_data').find('.loading').remove();
				if(response.status == 0){

				}else{
					var HTML = ``;
					if(typeof query_data.last_id == 'undefined'){
						var i = 1;
					}else{
						var i = $('#beneficiary_data tr:last').index() + 1;
					}
					if(response.survey_data.length == 0) {
						$('#loadm').addClass('hidden');
						$.toast({
							heading: 'End of Data!',
							text: 'No more data found.',
							icon: 'info'
						});
						return false;
					}

					response.survey_data.forEach(function(data, index){
						HTML += `<tr data-id="`+data.id+`">`;
						HTML += `<td>`+i+`</td>
						<td>`+(data.beneficiary_id == null || data.beneficiary_id == '' ? "N/A" : data.beneficiary_id)+`</td>
						<td>`;
						if(data.images.length > 0){
							data.images.forEach(function(img, ind){
								HTML += `<img src="<?php echo base_url(); ?>uploads/survey/`+img.file_name+`" style="height: 100px; width: 100px; margin-bottom: 10px;">`;
							});
						}else{
							HTML += `No images found`;
						}
						HTML += `</td>`;

						var jsondata = jQuery.parseJSON(data.form_data);
						response.fields.forEach(function(field, index){
							var fieldname = "field_"+field.field_id;

							switch(field.type){
								case 'lkp_district':
								if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
									HTML += `<td>N/A</td>`;
								}else{
									response.district_list.forEach(function(dist, index){
										if(dist.district_id == jsondata[fieldname]){
											HTML += `<td>`+dist.district_name+`</td>`;
										}
									});
								}
								break;

								case 'lkp_block':
								if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
									HTML += `<td>N/A</td>`;
								}else{
									response.block_list.forEach(function(block, index){
										if(block.block_id == jsondata[fieldname]){
											HTML += `<td>`+block.block_name+`</td>`;
										}
									});
								}
								break;

								case 'lkp_village':
								if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
									HTML += `<td>N/A</td>`;
								}else{
									response.village_list.forEach(function(village, index){
										if(village.village_id == jsondata[fieldname]){
											HTML += `<td>`+village.village_name+`</td>`;
										}
									});
								}
								break;

								default:
								HTML += `<td>`+(typeof jsondata[fieldname] === 'undefined' ? "N/A" : jsondata[fieldname])+`</td>`;
								break;
							}
						});

						if(response.check_group_field > 0) {
							HTML += `<td class="unwantedCol">
								<a href="<?php echo base_url(); ?>reports/groupdata_info/<?php echo ($this->uri->segment(2) == 'view_dashboard') ? 1 : $this->uri->segment(3); ?>/`+data.data_id+`" target="_blank">
									View data
								</a>
							</td>`;
						}

						HTML += `<td>`+(typeof data.partner_name === 'undefined' ? "N/A" : data.partner_name)+`</td>`;
						HTML += `<td>`+data.username+`</td>
						<td>`+data.reg_date_time+`</td>`;
						if(data.location == null){
							HTML += `<td>N/A</td>`;
						}else{
							var lat = (data.location.lat == null ? "N/A" : data.location.lat);
							var lng = (data.location.lng == null ? "N/A" : data.location.lng);

							HTML += `<td>`+lat+` `+lng+`</td>`;
						}
						HTML += `</tr>`;

						i++;
					});

					if(typeof query_data.last_id == 'undefined'){
						$('#beneficiary_data').html(HTML);
						addressPoints = response.survey_locations;
						// $('.mapdiv').html('<div id="map_element" style="height: 600px; width: 100%;"></div>');
						// map_content(addressPoints);
						map.removeLayer(markers);
	            		markers = L.markerClusterGroup({
							// disableClusteringAtZoom: 11
						});
						info.onAdd = function (map) {
							this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
							this.update();
							return this._div;
						};
						// method that we will use to update the control based on feature properties passed
						info.update = function (props) {
							this._div.innerHTML = '<h4 class="maplabel">Partial data loaded. <span class="loading">Please wait</span> while we load all the data points.</h4>';
						};
						info.addTo(map);
	            		loadMarkers(addressPoints, true);
					}else{
						$('#beneficiary_data').append(HTML);
					}
				}
			}
		});
	}

	function loadAllLocationData(lastId){
		var district = <?php echo $this->uri->segment(3); ?>;
		var user_ids = $('select[name="users[]"]').val();
		var start_date = startdate;
		var end_date = enddate;
		
		var query_data = {
			district : district,
			user_ids : user_ids,
			start_date : formatDate(start_date),
			end_date : formatDate(end_date),
			last_id: lastId
		};
		
		$.ajax({
            url : "<?php echo base_url(); ?>/dashboard/get_survey_locations",
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
            	if(response.status == 0){
            		$.toast({
						heading: 'Error!',
						text: response.msg,
						icon: 'error'
					});
					return false;
            	}

            	addressPoints = response.survey_locations;
        		loadMarkers(addressPoints);
            }
		});
	}

	function loadMarkers(addressPoints, firstLoad = false){
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
			if(firstLoad) {
				mapIndividualMarkers.push(marker);
				map.addLayer(marker);
			}
			markers.addLayer(marker);
		}

		if(addressPoints.length > 0) {
			loadAllLocationData(addressPoints[(addressPoints.length-1)][4]);
		} else {
			for(var i = 0; i < mapIndividualMarkers.length; i++){
				map.removeLayer(mapIndividualMarkers[i]);
			}
			map.addLayer(markers);
			if(info) info.remove(map);
		}
	}

	var addressPoints = <?php echo json_encode($survey_locations); ?>;
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
	
	var markers = L.markerClusterGroup({
		// disableClusteringAtZoom: 11
	});

	var map = L.map('map_element', {
		center: addressPoints[0] ? [addressPoints[0][0], addressPoints[0][1]] : [0, 0],
		layers: [leafletLayer],
		zoom: 8
	});

	var baseLayers = {
		"Street": leafletLayer,
		"Satellite": googleSatelliteLayer
	};
	// Use the custom grouped layer control, not "L.control.layers"
	L.control.groupedLayers(baseLayers).addTo(map);

	var info = L.control();
	info.onAdd = function (map) {
		this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
		this.update();
		return this._div;
	};
	// method that we will use to update the control based on feature properties passed
	info.update = function (props) {
		this._div.innerHTML = '<h4 class="maplabel">Partial data loaded. <span class="loading">Please wait</span> while we load all the data points.</h4>';
	};
	info.addTo(map);

	function exportToExcel(tableID, filename = ''){
		var downloadurl;
		var dataFileType = 'application/vnd.ms-excel';
		
		//Clone table to new div
		//Keep the classname as id
		//Remove unnecessary columns
		var clone = $('.'+tableID).clone();
		clone.attr('id', tableID);
		clone.removeClass(tableID);
		clone.find('.unwantedCol').remove();
		$('.exportContainer').html(clone);

		var tableSelect = document.getElementById(tableID);
		var tableHTMLData = tableSelect.outerHTML.replace(/ /g, '%20');
		filename = filename?filename+'.xls':'export_excel_data.xls';

		// Create download link element
		downloadurl = document.createElement("a");

		document.body.appendChild(downloadurl);

		if(navigator.msSaveOrOpenBlob){
			var blob = new Blob(['\ufeff', tableHTMLData], {
				type: dataFileType
			});
			navigator.msSaveOrOpenBlob( blob, filename);
		}else{
			// Create a link to the file
			downloadurl.href = 'data:' + dataFileType + ', ' + tableHTMLData;

			// Setting the file name
			downloadurl.download = filename;

			//triggering the function
			downloadurl.click();
		}
	}
</script>