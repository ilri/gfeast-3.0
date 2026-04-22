var script = document.getElementById("smJS"),
	baseURL = script.getAttribute("data-baseurl");

class SoilManagement {
	constructor() {
		this.selectedSDGTab = 1;
		this.selectedSoilTypeTab = 1;
	}

	init() {
		this.getSoilManagementData();
	}

	getSoilManagementData() {
		const request = indexFilter.getFilteredData();
		request.purpose = "get_soil_management";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL +
					"/include/assets/js/pi_2020/tabs/area_under_soil_management_tab.html",
				true
			),
		];
		// const promises = [post("pi_2020", {"purpose": "get_soil_management"}) , get("./tabs/area_under_soil_management_tab.html", true)];
		Promise.all(promises)
			.then((response) => {
				if (response?.length) {
					this.soilManagementData = response[0];
					const resHtml = response[1].replaceAll(
						'src="img/',
						`src="${baseURL}include/assets/img/pi_2020/`
					);
					$(".mpr-tab-contend").html(resHtml);

					this.arrangeData();
					this.summaryCounts();
					this.getSDGGraph();
					this.getSoilTypeGraph();
					this.getHTMLactionForSDGTab();
					this.getHTMLactionForSoilTypeTab();

					this.graphCRPwiseArea();
					this.graphRPwiseArea();
					this.graphSP();
					this.graphCountryWiseArea();
					this.graphYearwiseArea();
					this.htmlToggle();

					this.getHtmlActionForCRPYearComparison();
					this.graphCRPYearwiseArea();

					this.getHtmlActionForContriesYear();
					this.getYearsContryInfo();
					this.generateCountrywiseSpatialOutreachMap();
				}
			})
			.catch((err) => console.log(err));
	}

	arrangeData() {
		this.tsms = clone(this.soilManagementData.tsms);
		this.tsms.forEach((d) => {
			d.crps = clone(this.soilManagementData.tsm_crps).filter(
				(e) => e.data_id == d.data_id
			);
			d.sdgs = clone(this.soilManagementData.tsm_sdgs).filter(
				(e) => e.data_id == d.data_id
			);
			d.soilType = clone(this.soilManagementData.tsm_soil_types).filter(
				(e) => e.data_id == d.data_id
			);
		});
	}

	// summaryCounts() {
	// 	$("#sm-direct-area").html(
	// 		numberWithCommas(
	// 			this.tsms
	// 				.map((e) => parseInt(e.direct_area))
	// 				.filter((e) => e)
	// 				.reduce((a, b) => a + b, 0)
	// 		)
	// 	);
	// 	$("#table-0-tbody").html(`
	// 		<tr><td>Direct area</td><td>${numberWithCommas(
	// 			this.tsms
	// 				.map((e) => parseInt(e.direct_area))
	// 				.filter((e) => e)
	// 				.reduce((a, b) => a + b, 0)
	// 		)}</td></tr>
	// 		<tr><td>Indirect area</td><td>${numberWithCommas(
	// 			this.tsms
	// 				.map((e) => parseInt(e.indirect_area))
	// 				.filter((e) => e)
	// 				.reduce((a, b) => a + b, 0)
	// 		)}</td></tr>
	// 	`);

	// 	let pname_list = Array.from(
	// 		new Set(this.soilManagementData.tsms.map((e) => e.project_name))
	// 	);
	// 	$("#ss-dir-list").html(
	// 		pname_list.map((p) => {
	// 			let directArea = this.tsms
	// 				.filter((e) => e.project_name == p)
	// 				.map((e) => parseInt(e.direct_area || 0))
	// 				.reduce((a, b) => a + b, 0);
	// 			return `<tr><td>${p}</td><td>${directArea}</td></tr>`;
	// 		})
	// 	);
	// }
	generateCountrywiseSpatialOutreachMap() {
		let spatialMapData_soil = this.soilManagementData.tsms.map((d) => {
			return {
				name: d.village_name,
				lat: d.latitude,
				lang: d.longitude,
				project_name: d.project_name,
			};
		});

		// const villages = spatialMapData
		const greenIcon = L.icon({
			iconUrl: `https://unpkg.com/leaflet@1.3.1/dist/images/marker-icon.png`,
			shadowUrl:
				"https://unpkg.com/leaflet@1.3.1/dist/images/marker-shadow.png",
		});
		this.map1 = L.map("mpr-mapCountrywisespatialoutreach_soilmgmt",{ scrollWheelZoom: false }).setView(
			[14.8043, 77.349],
			3
		);
		L.simpleMapScreenshoter().addTo(this.map1)

		// L.geoJson(india_geo, {
		// style: style_states1,
		// onEachFeature: function (feature, layer) {
		// layer.bindPopup('<h3>State: '+feature.properties.ST_NM+'</h3>');
		// }
		// }).addTo(this.map1);

		function getZoneColor(e) {
			return e > 5
				? "#78A0D2"
				: e > 4
				? "#78A0D2"
				: e > 3
				? "#78A0D2"
				: e > 2
				? "#78A0D2"
				: e > 1
				? "#78A0D2"
				: e > 0
				? "#78A0D2"
				: "#78A0D2";
		}

		function style_states1(feature) {
			return {
				fillColor: getZoneColor(feature.properties.Zone),
				weight: 0.1,
				opacity: 0.3,
				color: "grey",
				dashArray: "0",
				fillOpacity: 0.3,
			};
		}
		// Add States Layer
		L.geoJson(india_geo, {
			style: style_states1,
			onEachFeature: function (feature, layer) {
				layer.bindPopup("<h3>State: " + feature.properties.ST_NM + "</h3>");
			},
		}).addTo(this.map1);

		L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
			maxZoom: 18,
			attribution:
				'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ',
			// +
			// '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			// 'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			id: "mapbox/streets-v11",
		}).addTo(this.map1);
		let markers = L.markerClusterGroup();
		for (let i = 0; i < spatialMapData_soil.length; i++) {
			let village_name = spatialMapData_soil[i]["name"];
			let lat = spatialMapData_soil[i]["lat"];
			let lng = spatialMapData_soil[i]["lang"];
			let project_name = spatialMapData_soil[i]["project_name"];
			try {
				let marker = L.marker(new L.LatLng(lat, lng), {
					name: village_name,
					icon: greenIcon,
				});
				const html = `
			<div class="row">
			<div class="col-sm-12">
			<h5 style="font-weight: 500; font-size: 1.14rem;"> <b>Project Name</b>: ${project_name}</h5>
			<h5 style="font-weight: 500; font-size: 1.14rem;"><b>Village Name</b>: ${village_name}</h5>
			<h5 style="font-weight: 500; font-size: 1.14rem;"><b>Location</b>: Lat:${lat} Lng: ${lng}</h5>
			</div>
			</div>
			`;
				// marker /.on('click', onMapClick);
				marker.bindPopup(html);
				markers.addLayer(marker);
			} catch (e) {
				console.log(e);
			}

			/ adding click event /;
		}
		this.map1.addLayer(markers);
		let popup = L.popup();
		// $(".leaflet-marker-icon").attr(
		// "src",
		// "./assets/images/map_pointer/greenmarker.png"
		// );
		// }
		// });
		// }
		$("#table-10-tbody").html(
			spatialMapData_soil.map(
				(e) =>
					`<tr><td>${e.project_name}</td><td>${e.name}</td><td>${e.lat}</td><td>${e.lang}</td></tr>`
			)
		);
	}

	summaryCounts() {
		let dirAreaCount = numberWithCommas(this.tsms.map((e) => parseInt(e.direct_area)).filter((e) => e).reduce((a, b) => a + b, 0))
		let dirCountryCount = [...new Set(this.tsms.map((d) => d.country_id))].length;
		let dirProjectsCount = [...new Set(this.tsms.map((d) => d.project_name))].length

		if(dirAreaCount == 0 ){
			// window.alert('Data not available for the selected year, Please reselect your options.')
			// location.reload()

			$("#alert").html( `<div class="modal" id="myModal">
			<div class="modal-dialog">
			  <div class="modal-content">
			  
				<!-- Modal Header -->
				<div class="modal-header">
				  <h4 class="modal-title">Alert</h4>
				  <button type="button" class="close" data-dismiss="modal" onclick="location.reload()">&times;</button>
				</div>
				
				<!-- Modal body -->
				<div class="modal-body text-center">
				  Data not available for the selected year, Please reselect your options after page reload.
				</div>
				
				<!-- Modal footer -->
				<div class="modal-footer justify-content-center">
				  <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="location.reload()">Close</button>
				</div>
				
			  </div>
			</div>
		  </div>`);

			$('#btnTrigger').click();
			return
		}
		$("#sm-direct-area").html(dirAreaCount == 0 ? "NA" : dirAreaCount+' ha');
		$("#sm-direct-countries_count").html(dirCountryCount == 0 ? "NA" : dirCountryCount);
		$("#sm-direct-projects_count").html(dirProjectsCount == 0 ? "NA" : dirProjectsCount);
		$("#table-0-tbody").html(`
		<tr><td>Direct area</td><td>${numberWithCommas(
			this.tsms
				.map((e) => parseInt(e.direct_area))
				.filter((e) => e)
				.reduce((a, b) => a + b, 0)
		)}</td></tr>
		<tr><td>Number of projects</td><td>${numberWithCommas(
			[...new Set(this.tsms.map((d) => d.project_name))].length
		)}</td></tr><tr><td>Number of countries</td><td>${numberWithCommas(
			[...new Set(this.tsms.map((d) => d.country_id))].length
		)}</td></tr>
		`);

		let pname_list = Array.from(
			new Set(this.soilManagementData.tsms.map((e) => e.project_name))
		);
		$("#ss-dir-list").html(
			pname_list.map((p) => {
				let directArea = this.tsms
					.filter((e) => e.project_name == p)
					.map((e) => parseInt(e.direct_area || 0))
					.reduce((a, b) => a + b, 0);
				return `<tr><td>${p}</td><td>${directArea == 0 ? "NA" : numberWithCommas(directArea)}</td></tr>`;
			})
		);
	}

	graphCountrySDG() {
		$("#sm-sdg-country").show();
		let chartData = indexFilter.pi2020FilterData.countries
			.map((country) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tsms.filter(
						(d) =>
							d.country_id == country.country_id &&
							d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return { from: country.country_name, to: sdg.sdg_name, value: val };
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		$("#sm-sdg-country").css("height", "600px");
		this.sankeyChart(chartData, "sm-sdg-country");
	}

	graphCRPSDG() {
		$("#sm-sdg-crp").show();
		let chartData = indexFilter.pi2020FilterData.crps
			.map((crp) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tsms.filter(
						(d) =>
							d.crps.some((e) => e.crp_id == crp.crp_id) &&
							d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return { from: crp.crp_name, to: sdg.sdg_name, value: val };
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		$("#sm-sdg-crp").css("height", "600px");
		this.sankeyChart(chartData, "sm-sdg-crp");
	}

	graphRPSDG() {
		$("#sm-sdg-rp").show();
		let chartData = indexFilter.pi2020FilterData.reasearchprograms
			.map((rp) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tsms.filter(
						(d) =>
							d.rp_id == rp.rp_id && d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return { from: rp.rp_name, to: sdg.sdg_name, value: val };
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		$("#sm-sdg-rp").css("height", "600px");
		this.sankeyChart(chartData, "sm-sdg-rp");
	}

	graphCountrySoilType() {
		$("#sm-soil-type-country").show();
		let chartData = indexFilter.pi2020FilterData.countries
			.map((country) => {
				return indexFilter.pi2020FilterData.soil_types.map((soilType) => {
					const val = this.tsms.filter(
						(d) =>
							d.country_id == country.country_id &&
							d.soilType.some((e) => e.soil_type_id == soilType.soil_type_id)
					).length;
					return {
						from: country.country_name,
						to: soilType.soil_type_name,
						value: val,
					};
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		const height = chartData.length * 30 <= 600 ? 600 : chartData.length * 30;
		$("#sm-soil-type-country").css("height", `${height}px`);
		this.sankeyChart(chartData, "sm-soil-type-country");
	}

	graphCRPSoilType() {
		$("#sm-soil-type-crp").show();
		let chartData = indexFilter.pi2020FilterData.crps
			.map((crp) => {
				return indexFilter.pi2020FilterData.soil_types.map((soilType) => {
					const val = this.tsms.filter(
						(d) =>
							d.crps.some((e) => e.crp_id == crp.crp_id) &&
							d.soilType.some((e) => e.soil_type_id == soilType.soil_type_id)
					).length;
					return {
						from: crp.crp_name,
						to: soilType.soil_type_name,
						value: val,
					};
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		const height = chartData.length * 30 <= 600 ? 600 : chartData.length * 30;
		$("#sm-soil-type-crp").css("height", `${height}px`);
		this.sankeyChart(chartData, "sm-soil-type-crp");
	}

	graphRPSoilType() {
		$("#sm-soil-type-rp").show();
		let chartData = indexFilter.pi2020FilterData.reasearchprograms
			.map((rp) => {
				return indexFilter.pi2020FilterData.soil_types.map((soilType) => {
					const val = this.tsms.filter(
						(d) =>
							d.rp_id == rp.rp_id &&
							d.soilType.some((e) => e.soil_type_id == soilType.soil_type_id)
					).length;
					return { from: rp.rp_name, to: soilType.soil_type_name, value: val };
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		const height = chartData.length * 30 <= 600 ? 600 : chartData.length * 30;
		$("#sm-soil-type-rp").css("height", `${height}px`);
		this.sankeyChart(chartData, "sm-soil-type-rp");
	}

	getSDGGraph() {
		$("#sm-sdg-country").hide();
		$("#sm-sdg-crp").hide();
		$("#sm-sdg-rp").hide();
		if (this.selectedSDGTab == 1) {
			this.graphCountrySDG();
		} else if (this.selectedSDGTab == 2) {
			this.graphCRPSDG();
		} else if (this.selectedSDGTab == 3) {
			this.graphRPSDG();
		}
	}

	getSoilTypeGraph() {
		$("#sm-soil-type-country").hide();
		$("#sm-soil-type-crp").hide();
		$("#sm-soil-type-rp").hide();
		if (this.selectedSoilTypeTab == 1) {
			this.graphCountrySoilType();
		} else if (this.selectedSoilTypeTab == 2) {
			this.graphCRPSoilType();
		} else if (this.selectedSoilTypeTab == 3) {
			this.graphRPSoilType();
		}
	}

	getHTMLactionForSDGTab() {
		$('input[name="sm-sdg-tab"]').on("change", () => {
			this.selectedSDGTab = $('input[name="sm-sdg-tab"]:checked').val();
			this.getSDGGraph();
		});
	}

	getHTMLactionForSoilTypeTab() {
		$('input[name="sm-soilType-tab"]').on("change", () => {
			this.selectedSoilTypeTab = $(
				'input[name="sm-soilType-tab"]:checked'
			).val();
			this.getSoilTypeGraph();
		});
	}

	sankeyChart(dataObj, container) {
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(container, am4charts.SankeyDiagram);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";
			chart.data = dataObj;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";
			chart.dataFields.color = "nodeColor";

			chart.paddingRight = 500;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.truncate = false;

			var nodeTemplate = chart.nodes.template;
			nodeTemplate.inert = true;
			nodeTemplate.readerTitle = "Drag me!";
			nodeTemplate.showSystemTooltip = true;
			nodeTemplate.width = 20;

			nodeTemplate.propertyFields.width = "width";

			var nodeTemplate = chart.nodes.template;
			nodeTemplate.readerTitle = "Click to show/hide or drag to rearrange";
			nodeTemplate.showSystemTooltip = true;
			nodeTemplate.cursorOverStyle = am4core.MouseCursorStyle.pointer;

			chart.exporting.menu = new am4core.ExportMenu();
			chart.exporting.menu.align = "right";
			chart.exporting.menu.verticalAlign = "top";
			chart.exporting.menu.items[0].icon = `${baseURL}include/assets/img/pi_2020/` + "download.svg";
			chart.exporting.filePrefix = "soil_management";
		});
	}

	graphCRPwiseArea() {
		let chartData = indexFilter.pi2020FilterData.crps
			.map((crp) => {
				let crpDataRecords = this.tsms
					.filter((e) => e.crps.map((f) => f.crp_id).includes(crp.crp_id))
					.map((e) => e.data_id);
				let directArea = this.tsms
					.filter((e) => crpDataRecords.includes(e.data_id) && e.direct_area)
					.map((e) => parseInt(e.direct_area))
					.reduce((a, b) => a + b, 0);
				let indirectArea = this.tsms
					.filter((e) => crpDataRecords.includes(e.data_id) && e.indirect_area)
					.map((e) => parseInt(e.indirect_area))
					.reduce((a, b) => a + b, 0);
				return {
					cat: crp.crp_name,
					direct_area: directArea,
					indirect_area: indirectArea,
				};
			})
			.filter((d) => d.direct_area > 0 || d.indirect_area > 0);
		this.verticalStackedBarChartArea(chartData, "sm-crp-area-graph");
		$("#table-3-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.direct_area) == 0 ? "NA" : numberWithCommas(e.direct_area)}</td></tr>`
				//<td>${numberWithCommas(e.indirect_area)}</td>
			)
		);

		let tfData = numberWithCommas(chartData.map((e) => e.direct_area).reduce((a, b) => a + b, 0))
		$("#table-3-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	graphRPwiseArea() {
		let chartData = indexFilter.pi2020FilterData.reasearchprograms
			.map((rp) => {
				let directArea = this.tsms
					.filter((e) => e.rp_id == rp.rp_id && e.direct_area)
					.map((e) => parseInt(e.direct_area))
					.reduce((a, b) => a + b, 0);
				let indirectArea = this.tsms
					.filter((e) => e.rp_id == rp.rp_id && e.indirect_area)
					.map((e) => parseInt(e.indirect_area))
					.reduce((a, b) => a + b, 0);
				return {
					cat: rp.rp_name,
					direct_area: directArea,
					indirect_area: indirectArea,
				};
			})
			.filter((d) => d.direct_area > 0 || d.indirect_area > 0);
		this.verticalStackedBarChartArea(chartData, "sm-rp-area-graph");
		$("#table-4-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.direct_area) == 0 ? "NA" : numberWithCommas(e.direct_area)}</td></tr>`
				//<td>${numberWithCommas(e.indirect_area)}</td>
			)
		);

		let tfData = numberWithCommas(chartData.map((e) => e.direct_area).reduce((a, b) => a + b, 0))
		$("#table-4-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	graphYearwiseArea() {
		let chartData = indexFilter.dataViewYears
			.map((year) => {
				let directArea = this.tsms
					.filter((e) => e.year_id == year.year_id && e.direct_area)
					.map((e) => parseInt(e.direct_area))
					.reduce((a, b) => a + b, 0);
				let indirectArea = this.tsms
					.filter((e) => e.year_id == year.year_id && e.indirect_area)
					.map((e) => parseInt(e.indirect_area))
					.reduce((a, b) => a + b, 0);
				return {
					cat: year.year,
					direct_area: directArea,
					indirect_area: indirectArea,
				};
			})
			.filter((d) => d.direct_area > 0 || d.indirect_area > 0);
		chartData.sort((a, b) => (parseInt(a.cat) > parseInt(b.cat) ? 0 : -1));
		// this.verticalStackedBarChartArea(chartData, "sm-yearwise-graph");

		Highcharts.chart("sm-yearwise-graph", {
			chart: { type: "column" },
			title: { text: null },
			xAxis: { categories: chartData.map((e) => e.cat) },
			yAxis: {
				min: 0,
				title: { text: "Area (ha)" },
				// breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			credits: { enabled: false },
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
				shared: true,
			},
			plotOptions: {
				column: {
					stacking: "normal",
					dataLabels: { enabled: true, style: { textOutline: false } },
					point: {
						events: {
							mouseOver: function () {
								const chart = this,
									yAxis = chart.series.yAxis;
								// yAxis.update({
								// 	breaks: [],
								// });
							},
							mouseOut: function () {
								const chart = this,
									yAxis = chart.series.yAxis;
								// yAxis.update({
								// 	breaks: breakarray,
								// });
							},
						},
					},
				},
			},
			series: [
				{
					name: "Direct",
					color: "#7cb5ec",
					data: chartData.map((e) => e.direct_area),
				},
				// {
				// 	name: "Indirect",
				// 	color: "#d79494",
				// 	data: dataObj.map((e) => e.indirect_area),
				// },
			],
		});

		$("#table-6-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.direct_area) == 0 ? "NA" : numberWithCommas(e.direct_area)}</td></tr>`

				//<td>${numberWithCommas(e.indirect_area)}</td><td>${numberWithCommas(
				//		e.direct_area + e.indirect_area
				//	)}</td>
			)
		);

		let tfData = numberWithCommas(chartData.map((e) => e.direct_area).reduce((a, b) => a + b, 0));

		$("#table-6-tfoot").html(
		`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	verticalStackedBarChartArea(dataObj, container) {
		let allValues = dataObj.map((e) => [e.direct_area, e.indirect_area]).flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart(container, {
			chart: { type: "column" },
			title: { text: null },
			xAxis: { categories: dataObj.map((e) => e.cat) },
			yAxis: {
				min: 0,
				title: { text: "Area (ha)" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			credits: { enabled: false },
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %) <br/>',
				shared: true,
			},
			plotOptions: {
				column: {
					stacking: "normal",
					dataLabels: { enabled: true, style: { textOutline: false } },
					point: {
						events: {
							mouseOver: function () {
								const chart = this,
									yAxis = chart.series.yAxis;
								yAxis.update({
									breaks: [],
								});
							},
							mouseOut: function () {
								const chart = this,
									yAxis = chart.series.yAxis;
								yAxis.update({
									breaks: breakarray,
								});
							},
						},
					},
				},
			},
			series: [
				{
					name: "Direct area",
					color: "#7cb5ec",
					data: dataObj.map((e) => e.direct_area),
				},
			],
			// {
			// 	name: "Indirect",
			// 	color: "#d79494",
			// 	data: dataObj.map((e) => e.indirect_area),
		});
	}

	graphSP() {
		let chartData = indexFilter.pi2020FilterData.scientific_publications.map(
			(sp) => {
				const val = this.tsms.filter(
					(e) => e.scientific_publications == sp.sp_id
				).length;
				return {
					name: sp.scientific_publications,
					y: val,
					percent: ((val * 100) / this.tsms.length).toFixed(2),
				};
			}
		);
		this.pieChartWithColors(
			chartData,
			"sm-sp-graph",
			"Scientific Publication",
			["#d79494", "#7cb5ec", "#ffce56"]
		);
		$("#table-5-tbody").html(
			chartData.map((e) => `<tr><td>${e.name}</td><td>${e.y == 0 ? "NA" : e.y}</td></tr>`)
		);

		let tfData = numberWithCommas(chartData.map((e) => e.y).reduce((a, b) => a + b, 0))
		$("#table-5-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	pieChartWithColors(dataObj, container, seriesName, colorList) {
		Highcharts.chart(container, {
			chart: { type: "pie" },
			title: { text: null },
			subtitle: { text: null },
			credits: { enabled: false },
			legend: { enabled: true },
			colors: colorList,
			plotOptions: {
				pie: {
					allowPointSelect: false,
					dataLabels: {
						enabled: true,
						format:
							"{point.name}</span>: <b>{point.y} ({point.percent} %)</b> ",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y} ({point.percent} %)</b> <br/>',
			},
			series: [
				{
					name: seriesName,
					colorByPoint: true,
					data: dataObj,
				},
			],
		});
	}

	graphCountryWiseArea() {
		// let chartData = indexFilter.pi2020FilterData.countries.map(country => {
		// 	let directArea = this.tsms.filter(e => e.country_id == country.country_id && e.direct_area).map(e => parseInt(e.direct_area)).reduce((a, b) => a+b, 0);
		// 	let indirectArea = this.tsms.filter(e => e.country_id == country.country_id && e.indirect_area).map(e => parseInt(e.indirect_area)).reduce((a, b) => a+b, 0);
		// 	return {'id': country.country_id, 'name': country.country_name, 'countryCode': country.country_code, 'z': directArea+indirectArea, 'zd': directArea, 'zi': indirectArea};
		// }).filter(d => d.z > 0);
		// this.worldMap(chartData, "sm-country-map", "countryCode", "ha", "Area");
		let mapData = indexFilter.pi2020FilterData.countries
			.map((data) => {
				let result = { id: data.country_code, name: data.country_name };
				let directArea = this.tsms
					.filter((e) => e.country_id == data.country_id && e.direct_area)
					.map((e) => parseInt(e.direct_area))
					.reduce((a, b) => a + b, 0);
				let indirectArea = this.tsms
					.filter((e) => e.country_id == data.country_id && e.indirect_area)
					.map((e) => parseInt(e.indirect_area))
					.reduce((a, b) => a + b, 0);
				result.zd = directArea;
				result.zi = indirectArea;
				result.value = directArea + indirectArea;
				return result;
			})
			.filter((d) => d.value > 0);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("sm-country-map", am4maps.MapChart);
			mapData.forEach((d, i) => (d.color = chart.colors.getIndex(i)));
			chart.geodata = am4geodata_worldIndiaLow;
			chart.projection = new am4maps.projections.Miller();
			chart.logo.disabled = "true";
			chart.numberFormatter.numberFormat = "#,###.##";

			chart.maxZoomLevel = 1;

			var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
			polygonSeries.exclude = ["AQ"];
			polygonSeries.useGeodata = true;
			polygonSeries.nonScalingStroke = true;
			polygonSeries.strokeWidth = 0.5;
			polygonSeries.calculateVisualCenter = true;

			var imageSeries = chart.series.push(new am4maps.MapImageSeries());
			imageSeries.data = mapData;
			imageSeries.dataFields.value = "value";

			var imageTemplate = imageSeries.mapImages.template;
			imageTemplate.nonScaling = true;

			var imageTemplate = imageSeries.mapImages.template;
			imageTemplate.verticalCenter = "middle";
			imageTemplate.horizontalCenter = "middle";
			imageTemplate.propertyFields.latitude = "lat";
			imageTemplate.propertyFields.longitude = "long";
			imageTemplate.tooltipText = "{name}:[bold]{value}[/]";

			var circle = imageTemplate.createChild(am4core.Circle);
			circle.fillOpacity = 0.7;
			circle.propertyFields.fill = "color";
			circle.tooltipText = "{name}: [bold]{value}[/]";

			imageSeries.heatRules.push({
				target: circle,
				property: "radius",
				min: 10,
				max: 40,
				dataField: "value",
			});

			imageTemplate.adapter.add("latitude", function (latitude, target) {
				var polygon = polygonSeries.getPolygonById(
					target.dataItem.dataContext.id
				);
				if (polygon) {
					return polygon.visualLatitude;
				}
				return latitude;
			});

			imageTemplate.adapter.add("longitude", function (longitude, target) {
				var polygon = polygonSeries.getPolygonById(
					target.dataItem.dataContext.id
				);
				if (polygon) {
					return polygon.visualLongitude;
				}
				return longitude;
			});

			var label = imageTemplate.createChild(am4core.Label);
			label.text = "{value}";
			label.horizontalCenter = "middle";
			label.verticalCenter = "middle";
			// label.padding(-10, 0, 0, 0);
			label.fontSize = 12;
			// label.adapter.add("dy", function (dy, target) {
			// 	var circle = target.parent.children.getIndex(0);
			// 	return circle.pixelRadius;
			// });

			chart.exporting.filePrefix = "soil-mgmt-country";
			exportAmchart('dwn-img-1',chart)			
		});
		$("#table-1-tbody").html(
			mapData.map(
				(e) => `<tr><td>${e.name}</td><td>${numberWithCommas(e.zd) == 0 ? "NA" : numberWithCommas(e.zd)}</td></tr>`
			)
			//<td>${numberWithCommas(e.zi)}</td>
		);

		let tfData = numberWithCommas(mapData.map((e) => e.zd).reduce((a, b) => a + b, 0))
		$("#table-1-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	worldMap(dataObj, container, identifier, units, seriesName) {
		Highcharts.mapChart(container, {
			chart: { borderWidth: 0, map: "custom/world" },
			title: { text: null },
			subtitle: { text: null },
			credits: { enabled: false },
			legend: { enabled: false },
			mapNavigation: {
				enabled: true,
				buttonOptions: {
					verticalAlign: "bottom",
				},
			},
			series: [
				{
					name: "Countries",
					color: "#4dabf5",
					enableMouseTracking: false,
				},
				{
					type: "mapbubble",
					name: seriesName,
					joinBy: ["iso-a2", identifier],
					data: dataObj,
					minSize: 4,
					maxSize: "12%",
					tooltip: {
						pointFormat: `{point.name}: {point.z} ${units ? units : ""}`,
					},
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
					},
				},
			],
		});
	}

	htmlToggle() {
		//graph-1
		const graphTab1 = $("#graph-btn-1");
		const tableTab1 = $("#table-btn-1");
		const downloadTab1 = $("#download-btn-1>img");

		const graph1 = $("#graph-1");
		const table1 = $("#table-1");

		graphTab1.on("click", () => {
			graphTab1.addClass("active");
			tableTab1.removeClass("active");
			graph1.show();
			table1.hide();
			graphTab1
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Map-selected.svg"
				);
			tableTab1
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab1.on("click", () => {
			tableTab1.addClass("active");
			graphTab1.removeClass("active");
			table1.show();
			graph1.hide();
			graphTab1
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Map.svg");
			tableTab1
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});

		downloadTab1.on("click", () => {
			if (
				downloadTab1.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab1.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab1.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab1.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			// html2canvas(document.getElementById("graph-1")).then((canvas) => {
			// 	let dataSrc = canvas.toDataURL("image/png");
			// 	dataSrc = dataSrc.replace("data:image/png;base64,", "");
			// 	$("#dwn-img-1")
			// 		.attr(
			// 			"href",
			// 			"data:application/octet-stream;base64," + encodeURI(dataSrc)
			// 		)
			// 		.attr("target", "_blank")
			// 		.attr("download", `soil-mgmt-country.jpeg`);
			// });
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "soil-mgmt-country.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-0").on("click", function () {
			$("#table-0-main").table2csv({
				file_name: "soil-mgmt-summary.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-00").on("click", function () {
			$("#table-00-main").table2csv({
				file_name: "soil-mgmt-summary.csv",
				header_body_space: 0,
			});
		});

		//graph-2
		const graphTab2 = $("#graph-btn-2");
		const tableTab2 = $("#table-btn-2");
		const downloadTab2 = $("#download-btn-2>img");

		const graph2 = $("#graph-2");
		const table2 = $("#table-2");

		graphTab2.on("click", () => {
			graphTab2.addClass("active");
			tableTab2.removeClass("active");
			graph2.show();
			table2.hide();
			graphTab2
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
				);
			tableTab2
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab2.on("click", () => {
			tableTab2.addClass("active");
			graphTab2.removeClass("active");
			table2.show();
			graph2.hide();
			graphTab2
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
			tableTab2
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});

		downloadTab2.on("click", () => {
			if (
				downloadTab2.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab2.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab2.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab2.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
		});

		//graph-3
		const graphTab3 = $("#graph-btn-3");
		const tableTab3 = $("#table-btn-3");
		const downloadTab3 = $("#download-btn-3>img");

		const graph3 = $("#graph-3");
		const table3 = $("#table-3");

		graphTab3.on("click", () => {
			graphTab3.addClass("active");
			tableTab3.removeClass("active");
			graph3.show();
			table3.hide();
			graphTab3
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab3
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab3.on("click", () => {
			tableTab3.addClass("active");
			graphTab3.removeClass("active");
			table3.show();
			graph3.hide();
			graphTab3
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab3
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab3.on("click", () => {
			if (
				downloadTab3.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab3.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab3.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab3.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-3")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-3")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `soil-mgmt-crp.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "soil-mgmt-crp.csv",
				header_body_space: 0,
			});
		});

		//graph-4
		const graphTab4 = $("#graph-btn-4");
		const tableTab4 = $("#table-btn-4");
		const downloadTab4 = $("#download-btn-4>img");

		const graph4 = $("#graph-4");
		const table4 = $("#table-4");

		graphTab4.on("click", () => {
			graphTab4.addClass("active");
			tableTab4.removeClass("active");
			graph4.show();
			table4.hide();
			graphTab4
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab4
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab4.on("click", () => {
			tableTab4.addClass("active");
			graphTab4.removeClass("active");
			table4.show();
			graph4.hide();
			graphTab4
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab4
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab4.on("click", () => {
			if (
				downloadTab4.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab4.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab4.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab4.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-4")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-4")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `soil-mgmt-rp.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "soil-mgmt-rp.csv",
				header_body_space: 0,
			});
		});

		//graph-5
		const graphTab5 = $("#graph-btn-5");
		const tableTab5 = $("#table-btn-5");
		const downloadTab5 = $("#download-btn-5>img");

		const graph5 = $("#graph-5");
		const table5 = $("#table-5");

		graphTab5.on("click", () => {
			graphTab5.addClass("active");
			tableTab5.removeClass("active");
			graph5.show();
			table5.hide();
			graphTab5
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
				);
			tableTab5
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab5.on("click", () => {
			tableTab5.addClass("active");
			graphTab5.removeClass("active");
			table5.show();
			graph5.hide();
			graphTab5
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
			tableTab5
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab5.on("click", () => {
			if (
				downloadTab5.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab5.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab5.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab5.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-5")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-5")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `soil-mgmt-publication.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "soil-mgmt-publication.csv",
				header_body_space: 0,
			});
		});

		//graph-6
		const graphTab6 = $("#graph-btn-6");
		const tableTab6 = $("#table-btn-6");
		const downloadTab6 = $("#download-btn-6>img");

		const graph6 = $("#graph-6");
		const table6 = $("#table-6");

		graphTab6.on("click", () => {
			graphTab6.addClass("active");
			tableTab6.removeClass("active");
			graph6.show();
			table6.hide();
			graphTab6
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab6
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab6.on("click", () => {
			tableTab6.addClass("active");
			graphTab6.removeClass("active");
			table6.show();
			graph6.hide();
			graphTab6
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab6
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab6.on("click", () => {
			if (
				downloadTab6.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab6.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab6.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab6.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-6")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-6")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `soil-mgmt-year.jpeg`);
			});
		});
		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "soil-mgmt-year.csv",
				header_body_space: 0,
			});
		});

		//graph-7
		const graphTab7 = $("#graph-btn-7");
		const tableTab7 = $("#table-btn-7");
		const downloadTab7 = $("#download-btn-7>img");

		const graph7 = $("#graph-7");
		const table7 = $("#table-7");

		graphTab7.on("click", () => {
			graphTab7.addClass("active");
			tableTab7.removeClass("active");
			graph7.show();
			table7.hide();
			graphTab7
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab7
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab7.on("click", () => {
			tableTab7.addClass("active");
			graphTab7.removeClass("active");
			table7.show();
			graph7.hide();
			graphTab7
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab7
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});

		downloadTab7.on("click", () => {
			if (
				downloadTab7.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab7.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab7.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab7.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-7")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-7")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `sm-crp-year.jpeg`);
			});
		});
		$("#dwn-csv-7").on("click", function () {
			$("#table-7-main").table2csv({
				file_name: "sm-crp-year.csv",
				header_body_space: 0,
			});
		});

		//graph-8
		const graphTab8 = $("#graph-btn-8");
		const tableTab8 = $("#table-btn-8");
		const downloadTab8 = $("#download-btn-8>img");

		const graph8 = $("#graph-8");
		const table8 = $("#table-8");

		graphTab8.on("click", () => {
			graphTab8.addClass("active");
			tableTab8.removeClass("active");
			graph8.show();
			table8.hide();
			graphTab8
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab8
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab8.on("click", () => {
			tableTab8.addClass("active");
			graphTab8.removeClass("active");
			table8.show();
			graph8.hide();
			graphTab8
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab8
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});

		downloadTab8.on("click", () => {
			if (
				downloadTab8.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab8.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab8.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab8.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-8")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-8")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `sm-contry-year.jpeg`);
			});
		});
		$("#dwn-csv-8").on("click", function () {
			$("#table-8-main").table2csv({
				file_name: "sm-contry-year.csv",
				header_body_space: 0,
			});
		});

		//graph-10
		const graphTab10 = $("#graph-btn-10");
		const tableTab10 = $("#table-btn-10");
		const downloadTab10 = $("#download-btn-10>img");

		const graph10 = $("#graph-10");
		const table10 = $("#table-10");

		graphTab10.on("click", () => {
			graphTab10.addClass("active");
			tableTab10.removeClass("active");
			graph10.show();
			table10.hide();
			graphTab10
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Map-selected.svg"
				);
			tableTab10
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab10.on("click", () => {
			tableTab10.addClass("active");
			graphTab10.removeClass("active");
			table10.show();
			graph10.hide();
			graphTab10
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Map.svg");
			tableTab10
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});

		downloadTab10.on("click", () => {
			if (
				downloadTab10.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab10.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab10.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab10.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-10")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-10")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `sm-contry-year.jpeg`);
			});
		});
		$("#dwn-csv-10").on("click", function () {
			$("#table-10-main").table2csv({
				file_name: "sm-contry-year.csv",
				header_body_space: 0,
			});
		});
	}

	getHtmlActionForCRPYearComparison() {
		const crpListHtml = `
			<a class="dropdown-item year-comp-crp-list" data-value="0" data-label="All Types">All Types</a>
			<a class="dropdown-item year-comp-crp-list" data-value="1" data-label="Direct Area">Direct Area</a>
			<a class="dropdown-item year-comp-crp-list" data-value="2" data-label="Indirect Area">Indirect Area</a>
		`;
		$("#year-comp-crps").html(crpListHtml);
		$(".year-comp-crp-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crp-name").html(data.label);
			$("#year-comp-crp-name").data("value", data.value);
			this.graphCRPYearwiseArea();
		});
	}

	graphCRPYearwiseArea() {
		let selectedLandType = $("#year-comp-crp-name").data("value");
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };

			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = this.soilManagementData.tsm_crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				let matchedRecords = this.soilManagementData.tsms.filter(
					(e) => crpDataRecords.includes(e.data_id) && e.year_id == yr.year_id
				);
				result[c.crp_name] = matchedRecords
					.map(
						(e) =>
							parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
					)
					.reduce((a, b) => a + b, 0);
				switch (true) {
					case selectedLandType == 0:
						result[c.crp_name] = matchedRecords
							.map(
								(e) =>
									parseFloat(e.direct_area || 0) +
									parseFloat(e.indirect_area || 0)
							)
							.reduce((a, b) => a + b, 0);
						break;
					case selectedLandType == 1:
						result[c.crp_name] = matchedRecords
							.map((e) => parseFloat(e.direct_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
					case selectedLandType == 2:
						result[c.crp_name] = matchedRecords
							.map((e) => parseFloat(e.indirect_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
				}
			});
			return result;
		}).filter(d=> d[" CGIAR Research Program on Water, Land and Ecosystems "] |
		d["Not mapped"] |
		d["CGIAR Research Program on Grain Legumes and Dryland Cereals"] |
		d["CGIAR Research Program on Livestock"] |
		d["CGIAR Research Program on Climate Change, Agriculture and Food Security "] |
		d["CGIAR Research Program on Agriculture for Nutrition and Health"] |
		d["CGIAR Research Program on Policies, Institutions, and Markets "] |
		d["Dryland Cereals (Ph 1)"] |
		d["Grain Legumes (Ph 1)"] |
		d["Dryland Systems (Ph 1)"])


		// console.log(chartData);

		let allValues = chartData
			.map((e) =>
				Object.keys(e)
					.filter((e) => e != "year")
					.map((f) => e[f])
			)
			.flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		// Highcharts.chart("sm-year-crp-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Area (ha)" },
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	credits: { enabled: false },
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %) <br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
		// 			dataLabels: { enabled: true, style: { textOutline: false } },
		// 			point: {
		// 				events: {
		// 					mouseOver: function () {
		// 						const chart = this,
		// 							yAxis = chart.series.yAxis;
		// 						yAxis.update({
		// 							breaks: [],
		// 						});
		// 					},
		// 					mouseOut: function () {
		// 						const chart = this,
		// 							yAxis = chart.series.yAxis;
		// 						yAxis.update({
		// 							breaks: breakarray,
		// 						});
		// 					},
		// 				},
		// 			},
		// 		},
		// 	},
		// 	series: indexFilter.pi2020FilterData.crps.map((e) => {
		// 		return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name]) };
		// 	}),
		// });

		let serz = indexFilter.pi2020FilterData.crps.map((e) => {
			return {
				name: e.crp_name,
				data: chartData.map((f) =>
					f[e.crp_name] ? Number(f[e.crp_name].toFixed(2)) : 0
				),
			};
		})


		$("#sm-year-crp-graph").css("height", serz.length * 4 + "em");

		Highcharts.chart("sm-year-crp-graph", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			xAxis: { categories: chartData.map((e) => e.year) },
			yAxis: {
				title: {
					text: "Area (ha)",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
			},
			plotOptions: {
				area: {
					fillOpacity: 0.5,
					marker: {
						enabled: false,
						symbol: "circle",
						radius: 2,
						states: {
							hover: {
								enabled: true,
							},
						},
					},
				},
			},
			// .map((e) => e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : null),
			series: serz
		});

		$("#table-7-thead-row").html(
			`<th>CRP</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-7-tbody").html(
			indexFilter.pi2020FilterData.crps.map((e) => {
				let yValsHtml = chartData.map(
					(f) => `<td>${numberWithCommas(f[e.crp_name].toFixed(2)) == 0 ? "NA" : numberWithCommas(f[e.crp_name].toFixed(2))}</td>`
				);
				let yVals = chartData
					.map((f) => f[e.crp_name])
					.reduce((a, b) => a + b, 0)
					.toFixed(2);

				return `<tr><td>${
					e.crp_name
				}</td>${yValsHtml}<td style="font-weight: 600;">${numberWithCommas(yVals) == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys
				.map((f) => e[f])
				.reduce((a, b) => a + b, 0)
				.toFixed(2);
		});
		let ttvalue = totals
			.map((e) => Number(e))
			.reduce((a, b) => a + b, 0)
			.toFixed(2);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-7-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue) == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	//contries year wise comparison
	getHtmlActionForContriesYear() {
		const crpListHtml = `
			<a class="dropdown-item year-contrys-list" data-value="0" data-label="All Types"> All Types</a>
			<a class="dropdown-item year-contrys-list" data-value="1" data-label="Direct Area"> Direct Area</a>
			<a class="dropdown-item year-contrys-list" data-value="2" data-label="Indirect Area">Indirect Area</a>
		`;
		$("#year-contry").html(crpListHtml);
		$(".year-contrys-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-contry-name").html(data.label);
			$("#year-contry-name").data("value", data.value);
			this.getYearsContryInfo();
		});
	}
	getYearsContryInfo() {
		let selectedLandType = $("#year-contry-name").data("value");
		//console.log(selectedLandType);
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				let matchedRecords = this.soilManagementData.tsms.filter(
					(e) => e.country_id == c.country_id && e.year_id == yr.year_id
				);
				result[c.country_name] = matchedRecords
					.map(
						(e) =>
							parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
					)
					.reduce((a, b) => a + b, 0);
				switch (true) {
					case selectedLandType == 0:
						result[c.country_name] = matchedRecords
							.map(
								(e) =>
									parseFloat(e.direct_area || 0) +
									parseFloat(e.indirect_area || 0)
							)
							.reduce((a, b) => a + b, 0);
						break;
					case selectedLandType == 1:
						result[c.country_name] = matchedRecords
							.map((e) => parseFloat(e.direct_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
					case selectedLandType == 2:
						result[c.country_name] = matchedRecords
							.map((e) => parseFloat(e.indirect_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
				}
			});
			return result;
		}).filter(d=> {
            const allCountries = Object.keys(d).filter(e=> e != 'year');
            return allCountries.some(e => d[e]);
        });

		let allValues = chartData
			.map((e) =>
				Object.keys(e)
					.filter((e) => e != "year")
					.map((f) => e[f])
			)
			.flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		// Highcharts.chart("sm-year-contry-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: {
		// 		categories: chartData
		// 			.filter(
		// 				(e) =>
		// 					!Object.keys(e)
		// 						.filter((e) => e != "year")
		// 						.map((f) => e[f])
		// 						.every((f) => f == 0)
		// 			)
		// 			.map((e) => e.year),
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Area (ha)" },
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	credits: { enabled: false },
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %) <br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
		// 			dataLabels: { enabled: true, style: { textOutline: false } },
		// 			format: "{point.y:.2f}",
		// 			point: {
		// 				events: {
		// 					mouseOver: function () {
		// 						const chart = this,
		// 							yAxis = chart.series.yAxis;
		// 						yAxis.update({
		// 							breaks: [],
		// 						});
		// 					},
		// 					mouseOut: function () {
		// 						const chart = this,
		// 							yAxis = chart.series.yAxis;
		// 						yAxis.update({
		// 							breaks: breakarray,
		// 						});
		// 					},
		// 				},
		// 			},
		// 		},
		// 	},
		// 	series: indexFilter.pi2020FilterData.countries
		// 		.map((c) => {
		// 			let result = {
		// 				name: c.country_name,
		// 				data: chartData
		// 					.filter((e) => e[c.country_name] > 0)
		// 					.map((e) => e[c.country_name]),
		// 			};
		// 			return result;
		// 		})
		// 		.filter(
		// 			(e) =>
		// 				!Object.keys(e.data)
		// 					.map((f) => e[f])
		// 					.every((f) => f == 0)
		// 		),
		// });

		// console.log(chartData);
		const cat = chartData.map((e) => e.year);

		const serz = indexFilter.pi2020FilterData.countries
			.map((c) => {
				let result = {
					name: c.country_name,
					data: chartData
						// .filter((e) => e[c.country_name] > 0)
						.map((e) =>
							e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : 0
						),
				};
				return result;
			})
			.filter((e) => e.data.some((d) => d));
		// .filter(
		// 	(e) =>
		// 		!Object.keys(e.data)
		// 			.map((f) => e[f])
		// 			.every((f) => f == 0)
		// )

		// console.log(cat);
		// console.log(serz);

		Highcharts.chart("sm-year-contry-graph", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			xAxis: {
				categories: cat,
			},
			yAxis: {
				title: {
					text: "Area (ha)",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
			},
			plotOptions: {
				area: {
					fillOpacity: 0.5,
					marker: {
						enabled: false,
						symbol: "circle",
						radius: 2,
						states: {
							hover: {
								enabled: true,
							},
						},
					},
				},
			},
			series: serz,
		});

		$("#table-8-thead-row").html(
			`<th>Countries</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-8-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${numberWithCommas(f[e.country_name].toFixed(2)) == 0 ? "NA" : numberWithCommas(f[e.country_name].toFixed(2))}</td>`
					);
					let yVals = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b, 0)
						.toFixed(2);

					return `<tr><td>${
						e.country_name
					}</td>${yValsHtml}<td style="font-weight: 600;">${numberWithCommas(yVals) == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
				}
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys
				.map((f) => e[f])
				.reduce((a, b) => a + b, 0)
				.toFixed(2);
		});

		let ttvalue = totals
			.map((e) => Number(e))
			.reduce((a, b) => a + b, 0)
			.toFixed(2);
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-8-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue) == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}
	//contries year wise comparison end
}
