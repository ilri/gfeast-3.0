<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.Default.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.css" />

<script src="<?php echo base_url(); ?>includeout/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/leaflet.markercluster.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_groupedlayer/src/leaflet.groupedlayercontrol.js"></script>

<script src="<?php echo base_url(); ?>includeout/amcharts4/core.js"></script>
<script src="<?php echo base_url(); ?>includeout/amcharts4/charts.js"></script>
<script src="<?php echo base_url(); ?>includeout/amcharts4/themes/animated.js"></script>

<style type="text/css">
	.dropdown-menu {
		width: auto !important;
	}
	.p-10{
		padding: 10px;
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
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<select class="form-control" name="division[]" multiple title="Select Division">
							<?php foreach ($divisions as $key => $div) { ?>
								<option value="<?php echo $div['DIV_CODE']; ?>"><?php echo $div['DIV_NAME']; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-3">
						<select class="form-control" name="circle[]" multiple title="Select Circle"></select>
					</div>

					<div class="col-md-4">
						<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
							<i class="fa fa-calendar"></i>&nbsp;
							<span></span> <i class="fa fa-caret-down"></i>
						</div>				
					</div>
					
					<div class="col-md-2 text-right">
						<button class="btn btn-sm btn-success get_dashboardfilter_data pull-right">Filter Data</button>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 loadingdiv">
						
					</div>
				</div>

				<div class="row mt-20 dashboard_data">
					<div class="col-xl-3 col-lg-3 col-12">
						<div class="card p-10" style="background-color: orange;">				                
							<div class="media d-flex">
								<div class="media-body text-white text-left">
									<h6 class="text-white"><strong>Total Ryots registered</strong></h6>
								</div>
								<div class="align-self-center">
									<h5 class="text-white"><strong id="farmer_registered"><?php echo $farmer_registered; ?></strong></h5>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-12">
						<div class="card p-10" style="background-color: #1ec481;">				                
							<div class="media d-flex">
								<div class="media-body text-white text-left">
									<h6 class="text-white"><strong>Total Plots</strong></h6>
								</div>
								<div class="align-self-center">
									<h5 class="text-white"><strong id="plot_data"><?php echo $total_plot; ?></strong></h5>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-3 col-12">
						<div class="card p-10" style="background-color: #62bcf6;">				                
							<div class="media d-flex">
								<div class="media-body text-white text-left">
									<h6 class="text-white"><strong>Total Area</strong></h6>
								</div>
								<div class="align-self-center">
									<h5 class="text-white"><strong id="total_area"><?php echo $total_area; ?></strong></h5>
								</div>
							</div>
						</div>
					</div>					
					<div class="col-xl-3 col-lg-3 col-12">
						<div class="card p-10" style="background-color: #b585e3;">				                
							<div class="media d-flex">
								<div class="media-body text-white text-left">
									<h6 class="text-white"><strong>Total REs</strong></h6>
								</div>
								<div class="align-self-center">
									<h5 class="text-white"><strong id="total_res"><?php echo $total_res; ?></strong></h5>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12 mt-20">
						<h5>Number of farmers registered by village</h5>
						<div id="villagewise_famers" style="height: 400px; width:100%;"></div>						
					</div>

					<div class="col-md-12">
						<h5>Ryots/ Plots registered</h5>
						<div class="mapdiv">
							<div id="map_element" style="height: 600px; width: 100%;"></div>
						</div>
					</div>
					<!-- <div class="col-md-3">
						<div class="card p-10" style="height: 600px;">
							
						</div>
					</div> -->

					<div class="col-md-12 mt-20">
						<h5>Plots registered vs Plots agreements done by village</h5>
						<div id="plotsregisterd_agrementdone" style="height: 400px; width:100%;"></div>						
					</div> 

					<!-- <div class="col-md-12 mt-20">
						<a id="btnExport" onclick="javascript:xport.toCSV('datatable', 'Plot data');" class="btn btn-sm btn-success float-right">Export Table Data</a>
						<h4>Plots data</h4>
						<div class="table-responsive">
							<table class="table table-bordered table-hover m-0" id="datatable">
								<thead>
									<tr>
										<th>Sl.No.</th>
										<th>Plot number</th>
										<?php foreach ($fields as $key => $value) { ?>
											<th><?php echo $value['label']; ?></th>
										<?php } ?>
										<th>Uploaded By</th>
										<th>Uploaded Datetime</th>
									</tr>
								</thead>
								<tbody id="survey_data">
									<?php if(count($plot_data) > 0){
										foreach ($plot_data as $dkey => $data) { ?>
											<tr data-id="<?php echo $data['data_id']; ?>">
												<td><?php echo $dkey+1; ?></td>
												<td><a href="<?php echo base_url(); ?>dashboard/plot_info/<?php echo $data['plot_id']; ?>" target="_blank" style="color: blue;"><?php echo $data['plot_number']; ?></a></td>
												<?php foreach ($fields as $fkey => $field) {
													$column = "field_".$field['field_id']; ?>
														<?php switch ($field['type']) {
															default:
																if(isset($data[$column])){
																	if($data[$column] == NULL || $data[$column] == ''){
																		echo "<td>N/A</td>";
																	}else{
																		echo "<td>".$data[$column]."</td>";
																	}
																}else{
																	echo "<td>N/A</td>";
																}
																break;
														} ?>
													</td>
												<?php } ?>
												<td><?php echo $data['username']; ?></td>
												<td><?php echo $data['added_date']; ?></td>
											</tr>
										<?php }
									}else{ ?>
										<tr>
											<td colspan="<?php echo count($fields)+3 ?>" style="text-align: left;">No data found</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div> -->
				</div>



				<!-- Import tabular view -->
			</div>
		</div>
	</div>
</div>
<!-- /Main content -->

<script src="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/table_doublescroller/jquery.doubleScroll.js"></script>

<script type="text/javascript">
	var startdate, enddate;

	$(function() {
		$('.table-responsive').doubleScroll({
			resetOnWindowResize:true
		});

		$("[name='division[]'], [name='circle[]']").selectpicker({
			actionsBox: true,
			liveSearch: true
		});

		var start = moment().subtract(1, 'years');
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
				'Last 15 Days': [moment().subtract(14, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);

		cb(start, end);

		$('body').on('change', '[name="division[]"]', function(){
			$elem = $(this);
			var divisions = $elem.val();

			$('select[name="circle[]"]').html('');
			$('select[name="circle[]"]').selectpicker('refresh');

			if(divisions.length > 0){
				$.ajax({
					url : '<?php echo base_url(); ?>users/get_circlebydivision',
					data : {
						division_ids : divisions
					},
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
								heading: 'Network Error!',
								text: response.msg,
								icon: 'error'
							});
						}else{
							var HTML = ``;
							response.get_circlebydivision.forEach(function(circle, index){
								HTML += `<option value="`+circle.CIR_CODE+`">`+circle.CIR_NAME+`</option>`;
							});

							$('select[name="circle[]"]').html(HTML);
							$('select[name="circle[]"]').selectpicker('refresh');
						}
					}
				});
			}
		});

		$('body').on('click', '.get_dashboardfilter_data', function(){

			var division_ids = $('select[name="division[]"]').val();
			var circle_ids = $('select[name="circle[]"]').val();
			$('.loadingdiv').html('<h5>Please wait loading data...</h5>');
			$('.mapdiv').html('<div id="map_element" style="height: 600px; width: 100%;"></div>');
			$.ajax({
				url : '<?php echo base_url(); ?>dashboard',
				type : "POST",
				dataType : "JSON",
				data : {
					division : division_ids,
					circle : circle_ids,
					start_date : formatDate(startdate),
					end_date : formatDate(enddate)
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
						$('#farmer_registered').html(response.farmer_registered);
						$('#total_area').html(response.total_area);
						$('#total_res').html(response.total_res);
						$('#plot_data').html(response.total_plot);

						simple_barchart('villagewise_famers', response.famers_byvillage);

						cluster_columnchart('plotsregisterd_agrementdone', response.plotsregisterd_agrementdone);

						var addressPoints = response.location_array;
						map_content(addressPoints);
						$('.loadingdiv').html('');
					}
				}
			});		
		});


	});

var addressPoints = <?php echo json_encode($location_array); ?>;
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
	}).setView([19, 82], 5);

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

	simple_barchart('villagewise_famers', <?php echo json_encode($famers_byvillage); ?>);

	function simple_barchart(divid, data){
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
			categoryAxis.renderer.minGridDistance = 30;
			categoryAxis.renderer.labels.template.horizontalCenter = "right";
			categoryAxis.renderer.labels.template.verticalCenter = "middle";
			categoryAxis.renderer.labels.template.rotation = 270;
			categoryAxis.tooltip.disabled = true;
			categoryAxis.renderer.minHeight = 110;

			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.minWidth = 50;

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

			// Cursor
			chart.cursor = new am4charts.XYCursor();
		}); // end am4core.ready()
	}

	cluster_columnchart('plotsregisterd_agrementdone', <?php echo json_encode($plotsregisterd_agrementdone); ?>);

	function cluster_columnchart(div_id, data) {
		am4core.ready(function() {
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			var chart = am4core.create(div_id, am4charts.XYChart)
			chart.colors.step = 2;

			chart.legend = new am4charts.Legend()
			chart.legend.position = 'top'
			chart.legend.paddingBottom = 20
			chart.legend.labels.template.maxWidth = 95

			var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
			xAxis.dataFields.category = 'name'
			xAxis.renderer.cellStartLocation = 0.1
			xAxis.renderer.cellEndLocation = 0.9
			xAxis.renderer.grid.template.location = 0;

			var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
			yAxis.min = 0;

			function createSeries(value, name) {
				var series = chart.series.push(new am4charts.ColumnSeries())
				series.dataFields.valueY = value
				series.dataFields.categoryX = 'name'
				series.name = name

				series.events.on("hidden", arrangeColumns);
				series.events.on("shown", arrangeColumns);

				var bullet = series.bullets.push(new am4charts.LabelBullet())
				bullet.interactionsEnabled = false
				bullet.dy = 30;
				bullet.label.text = '{valueY}'
				bullet.label.fill = am4core.color('#ffffff')

				return series;
			}

			chart.data = data

			createSeries('plot_registered', 'Plot registered');
			createSeries('plot_aggrement', 'Plot agreement');

			function arrangeColumns() {

				var series = chart.series.getIndex(0);

				var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
				if (series.dataItems.length > 1) {
					var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
					var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
					var delta = ((x1 - x0) / chart.series.length) * w;
					if (am4core.isNumber(delta)) {
						var middle = chart.series.length / 2;

						var newIndex = 0;
						chart.series.each(function(series) {
							if (!series.isHidden && !series.isHiding) {
								series.dummyData = newIndex;
								newIndex++;
							}
							else {
								series.dummyData = chart.series.indexOf(series);
							}
						})
						var visibleCount = newIndex;
						var newMiddle = visibleCount / 2;

						chart.series.each(function(series) {
							var trueIndex = chart.series.indexOf(series);
							var newIndex = series.dummyData;

							var dx = (newIndex - trueIndex + middle - newMiddle) * delta

							series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
							series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
						})
					}
				}
			}
		}); // end am4core.ready()
	}

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