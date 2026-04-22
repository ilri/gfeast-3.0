<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.css" />

<script src="<?php echo base_url(); ?>includeout/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/leaflet.markercluster.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.js"></script>

<style type="text/css">
	.dropdown-menu {
		width: auto !important;
	}
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>
<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			<div class="card-header">
				<h3>Track user</h3>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<select class="form-control" name="users[]" multiple title="Select User">
							<?php foreach ($all_users as $key => $user) { ?>
								<option value="<?php echo $user['user_id']; ?>"><?php echo $user['first_name']." ".$user['last_name']; ?></option>
							<?php } ?>
						</select>
					</div>
					
					<div class="col-md-3">
						<input type="text" readonly name="datepicker" id="datepicker" class="form-control">				
					</div>
					
					<div class="col-md-3 text-right">
						<button class="btn btn-sm btn-success get_user_checkin_checkout_data pull-right">Filter Data</button>
					</div>
				</div>

				<div class="row mt-20">
					<div class="col-md-12">
						<div class="card p-10 mapdiv">
							
						</div>
					</div>
				</div>

				<!-- Import tabular view -->
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/table_doublescroller/jquery.doubleScroll.js"></script>

<script type="text/javascript">
	$(function() {
		$('.table-responsive').doubleScroll({
			resetOnWindowResize:true
		});

		$("[name='users[]']").selectpicker({
			actionsBox: true,
			liveSearch: true
		});

		$('#datepicker').daterangepicker({
			singleDatePicker: true,
			showDropdowns: true,
			minYear: 2001,
			locale:{
				format:'YYYY-MM-DD',
			},
			maxYear: parseInt(moment().format('YYYY'),10)
		});

		$('body').on('click', '.get_user_checkin_checkout_data', function(){

			var user_ids = $('select[name="users[]"]').val();
			var date = $('input[name="datepicker"]').val();
			$('.mapdiv').html('<div id="map_element" style="height: 500px; width: 100%;"></div>');

			if(user_ids.length > 0 && date != ''){
				$.ajax({
					url : '<?php echo base_url(); ?>users/get_user_checkin_checkout_data',
					type : "POST",
					dataType : "JSON",
					data : {
						user_ids : user_ids,
						date : date
					},
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
								heading: 'Network Error!',
								text: response.msg,
								icon: 'error'
							});
						}else{
							$('.mapdiv').html('<h5>User Check in/Check out locations</h5><div id="map_element" style="height: 500px; width: 100%;"></div>');

		            		var addressPoints = response.get_user_checkin_checkout_data;
							map_content(addressPoints);
						}
					}
				});
			}			
		})
	});

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
		var icon = new LeafIcon({iconUrl: '<?php echo base_url(); ?>include/plugins/leaflet/icons/pin1.png'});
		
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

		var baseLayers = {
			"Street": leafletLayer,
			"Satellite": googleSatelliteLayer
		};
		// Use the custom grouped layer control, not "L.control.layers"
		L.control.groupedLayers(baseLayers).addTo(map);

		// var markers = L.markerClusterGroup();
		for (var i = 0; i < addressPoints.length; i++) {
			var a = addressPoints[i];
			var title = a[2];
			var marker = L.marker(new L.LatLng(a[0], a[1]), {
				title: title,
				icon: icon
			});
			marker.bindPopup(title);
			map.addLayer(marker);
			// markers.addLayer(marker);
		}
		// map.addLayer(markers);
	}
</script>