<style type="text/css">
	.dropdown-menu {
		width: auto !important;
	}
	.p-10{
		padding: 10px;
	}
</style>

<div class="main-content">
	<div class="p-4">
		<div class="card p-10 mt-20">
			<div class="row">
				<div class="col-md-3 ml-0">
					<div style="width: 100%; height: 430px;" id="map"></div>
					<?php if($plot_databyid['kml']) { ?>
					<a class="text-primary" href="<?php echo base_url(); ?>/uploads/survey/<?php echo $plot_databyid['kml']['file_name']; ?>" download>Download KML</a>
					<?php } ?>
				</div>
				<div class="col-md-9">
					<button class="float-right btn btn-success btn-sm" onclick="window.top.close();">Back</button>
					<h5><?php echo $plot_databyid['plot_number']; ?></h5>
					<div class="table table-responsive" style="max-height: 400px;">
						<table class="table table-bordered table-hover m-0" >
							<tbody>
								<tr>
									<th class="text-center" colspan="2" style="background-color: #FAF47E; color: black;">
										<b>PLOT DETAILS</b>
									</th>
								</tr>
								<?php foreach ($fields as $key => $field) { 
									$column = "field_".$field['field_id']; ?>
									<tr>
										<td style="width: 50%;"><?php echo $field['label']; ?></td>
										<td><?php echo $plot_databyid[$column]; ?></td>
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

<script>
	var map;
	var src = '<?php echo base_url(); ?>/uploads/survey/<?php echo $plot_databyid['kml']['file_name']; ?>';
	// var src = 'https://developers.google.com/maps/documentation/javascript/examples/kml/westcampus.kml';

	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: new google.maps.LatLng(-19.257753, 146.823688),
			zoom: 2,
			mapTypeId: 'terrain'
		});

		var kmlLayer = new google.maps.KmlLayer(src, {
			suppressInfoWindows: true,
			preserveViewport: false,
			map: map
		});
		kmlLayer.addListener('click', function(event) {
			var content = event.featureData.infoWindowHtml;
			var testimonial = document.getElementById('capture');
			testimonial.innerHTML = content;
		});
	}
</script>
<script defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVt2nTvTF_Z-p-Wyyul2Bsm9fzz8XHD-U&callback=initMap">
</script>