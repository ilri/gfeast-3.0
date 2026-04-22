<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,300' rel='stylesheet' type='text/css'>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
  
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  
<script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>

<style type="text/css">
	#map {
		width: 100%;
		height: 90vh;
	}
</style>

<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			<div class="card-header">
				<h3>KML View</h3>
			</div>
			<div class="card-body">
				<div id='map'></div>
			</div>
		</div>
	</div>
</div>

<script>
	var map = L.map('map', {
		center: [13.33, 79.73],
		zoom: 5
	});
	
	// Get all KML
	var kmls = <?php echo json_encode($kmls); ?>;
	for (var i = 0; i < kmls.length; i++) {
		var kml = kmls[i];

		// Plot KML
		omnivore.kml('<?php echo base_url(); ?>uploads/survey/'+kml['file_name']).addTo(map).bindPopup('Measured Area: '+kml['measured_area']+'.');
	}
	
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		maxZoom: 19,
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);
</script>