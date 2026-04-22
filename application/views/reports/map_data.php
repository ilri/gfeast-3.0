<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/leaflet_markercluster/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.css" />

<script src="<?php echo base_url(); ?>include/plugins/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/leaflet_markercluster/dist/leaflet.markercluster.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.js"></script>

<style type="text/css">
	.filter {
		cursor: pointer;
	}
	#dateWiseUpload {
		width: 100%;
		height: 500px;
	}
	.maplabel {
		background-color: rgba(255,255,255, 0.7);
		border-radius: 5px;
		font-weight: 500;
		padding: 2px;
	}
	/* loading dots */
	.loading {
		padding-right: 30px;
	}
	.loading:after {
		content: ' .';
		line-height: 0;
		font-size: 50px;
		position: absolute;
		animation: dots 1s steps(5, end) infinite;
	}
	@keyframes dots {
		0%, 20% {
			color: rgba(0,0,0,0);
			text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0);
		}
		40% {
			color: #000;
			text-shadow: .25em 0 0 rgba(0,0,0,0), .5em 0 0 rgba(0,0,0,0);
		}
		60% {
			text-shadow: .25em 0 0 #000, .5em 0 0 rgba(0,0,0,0);
		}
		80%, 100% {
			text-shadow: .25em 0 0 #000, .5em 0 0 #000;
		}
	}
</style>

<!-- Map Data -->
<div class="card mt-20">
	<div class="card-body">
		<div class="row">
			<div class="col-md-9 col-sm-7 mapdiv">
				<div id="map_element" style="height: 600px; width: 100%;"></div>
			</div>
			<div class="col-md-3 col-sm-5 detailsdiv" style="overflow:auto;">
				<h4 class="text-center">
					Click on an area to view details.
				</h4>
			</div>
		</div>
	</div>
</div>

<!-- Page Script -->
<script type="text/javascript">
	function loadAllLocationData(lastId){
		var query_data = {
			// division : $("[name='division[]']").val(),
			// circle : $("[name='circle[]']").val(),
			// village : $("[name='village[]']").val(),
			// start_date : $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD'),
			// end_date : $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD'),
			last_id: lastId
		};
		
		$.ajax({
			url : mapUrl,
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
				if(lastId) loadMarkers(addressPoints);
				else {
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
				}
			}
		});
	}

	function loadMarkers(addressPoints, firstLoad = false){
		// for (var i = 0; i < addressPoints.length; i++) {
		// 	var a = addressPoints[i];
		// 	var title = a[2];
		// 	var marker = L.marker(new L.LatLng(a[0], a[1]), {
		// 		title: title
		// 	});
		// 	marker.bindPopup(title);
		// 	if(firstLoad) {
		// 		mapIndividualMarkers.push(marker);
		// 		map.addLayer(marker);
		// 	}
		// 	markers.addLayer(marker);
		// }

		// if(addressPoints.length > 0) {
		// 	loadAllLocationData(addressPoints[(addressPoints.length-1)][3]);
		// } else {
		// 	for(var i = 0; i < mapIndividualMarkers.length; i++){
		// 		map.removeLayer(mapIndividualMarkers[i]);
		// 	}
		// 	map.addLayer(markers);
		// 	if(info) info.remove(map);
		// }

		for (var i = 0; i < addressPoints.length; i++) {
			var a = addressPoints[i];

			var polygonPoints = [];
			for (var j = 0; j < a.locations.length; j++) {
				var loc = a.locations[j].split(', ');
				polygonPoints.push(loc);
			}
			var poly = L.polygon(polygonPoints, { data_id : a.data_id, survey_id : a.survey_id }).addTo(map);
			poly.on('click', function (event) {
				var survey_id = event.target.options.survey_id,
				data_id = event.target.options.data_id;

				// Get Details of the Polygon
				$.ajax({
					url : '<?php echo base_url(); ?>reports/get_data_details',
					data : { data_id : data_id, survey_id : survey_id },
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

						// Show Details
						// var detailsDiv = $('.detailsdiv');
						var HTML = `<h6>Village Name : ${response.survey_data.village_name}</h6>
						<h6 style="margin-top:30px;">Crop Type : ${response.survey_data.field_10766}</h6>`;
						if(response.survey_data.field_10766 == 'Single crop') {
							HTML += `<h6>Crop Name : ${response.survey_data.field_10767}</h6>`
						} else if(response.survey_data.field_10766 == 'Mixed crop') {
							HTML += `<h6>Crop Name : ${response.survey_data.field_10771}</h6>`
						} else {
							HTML += `<h6>Crop Name : ${response.survey_data.field_10768}</h6>`
						}
						HTML += `<h6 style="margin-top:30px;">Means of Irrigation : ${response.survey_data.field_10769}</h6>
						<hr style="margin:30px 0;"/>
						<h5>Images</h5>`;
						for (var i = 0; i < response.survey_data.images.length; i++) {
							var image = response.survey_data.images[i];
							HTML += `<div class="text-center" style="margin-bottom:30px;">
								<img style="max-height:70px;" src="<?php echo base_url()?>uploads/survey/${image.file_name}">
								<label class="d-block">Lattitude: ${image.file_lat}</label>
								<label class="d-block">Longitude: ${image.file_long}</label>
							</div>`;
						}
						$('.detailsdiv').html(HTML);
						// console.log(response.survey_data);
					}
				});
			});
		}
	}

	var mapIndividualMarkers = [];

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
		center: [17.387140, 78.491684],
		layers: [leafletLayer],
		zoom: 5
	});

	var baseLayers = {
		"Street": leafletLayer,
		"Satellite": googleSatelliteLayer
	};
	// Use the custom grouped layer control, not "L.control.layers"
	L.control.groupedLayers(baseLayers).addTo(map);

	var addressPoints = <?php echo json_encode($survey_locations); ?>;
	loadMarkers(addressPoints);

	// var info = L.control();
	// info.onAdd = function (map) {
	// 	this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
	// 	this.update();
	// 	return this._div;
	// };
	// // method that we will use to update the control based on feature properties passed
	// info.update = function (props) {
	// 	this._div.innerHTML = '<h4 class="maplabel">Partial data loaded. <span class="loading">Please wait</span> while we load all the data points.</h4>';
	// };
	// info.addTo(map);
</script>