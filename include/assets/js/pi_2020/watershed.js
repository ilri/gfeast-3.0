var script = document.getElementById("wJS"),
	baseURL = script.getAttribute("data-baseurl");

Highcharts.setOptions({
	lang: {
		thousandsSep: ",",
	},
});

class Watershed {
	constructor() {
		this.selectedSankyType = 1;
	}
	init() {
		this.getWatershedData();
	}

	getWatershedData() {
		// const request = { purpose: "get_watershed" };
		const request = indexFilter.getFilteredData();
		request.purpose = "get_watershed";
		const promises = [
			post("pi_2020", request),
			get(baseURL + "/include/assets/js/pi_2020/tabs/watershed_tab.html", true),
		];
		Promise.all(promises)
			.then((response) => {
				if (response?.length) {
					this.watershedData = response[0];
					const resHtml = response[1].replaceAll(
						'src="img/',
						`src="${baseURL}include/assets/img/pi_2020/`
					);
					$(".mpr-tab-contend").html(resHtml);
					setTimeout(() => {
						$(
							`[name="sankey-sdg-radio"][value="${this.selectedSankyType}]`
						).prop("checked", true);
					});
					this.generateData();
					this.generateCharts();
					this.getHTMLActionForSankey();
					// this.staticCharts();
					// this.getHtmlActions();
					this.htmlToggle();
				}
			})
			.catch((err) => console.log(err));
	}

	/*
	 Data is arranged to simplify retional
  */
	generateData() {
		this.tws = clone(this.watershedData.tws);
		this.tws.forEach((d) => {
			d.crp = this.watershedData.tw_crps.filter((e) => e.data_id == d.data_id);
			d.sdg = this.watershedData.tw_sdgs.filter((e) => e.data_id == d.data_id);
		});
	}

	generateCharts() {
		this.getAreaUnderWatershed();
		this.getAreaUnderWatershedRpWise();
		this.getAreaUnderWatershedCprWise();
		this.getScientificPublications();
		this.generateWaterShedSankeyGraph();
		//this.generateCountriesMap();
		this.generateAreaUnderWatershedYearWise();
		//this.generateDirectAreaYearChart();
		this.getHtmlActionForCRPYearComparison();
		this.graphCRPYearwiseArea();
		this.getYearContryInfo();
		this.getHtmlActionForContriesYearComparison();
		this.generateCountrywiseSpatialOutreachMap();
	}
	generateCountrywiseSpatialOutreachMap() {
		let spatialMapData = this.watershedData.tws.map((d) => {
			return {
				name: d.village_name,
				lat: d.latitude,
				lang: d.longitude,
				project_name: d.project_name,
			};
		});

		const villages = spatialMapData;
		const greenIcon = L.icon({
			iconUrl: `https://unpkg.com/leaflet@1.3.1/dist/images/marker-icon.png`,
			shadowUrl:
				"https://unpkg.com/leaflet@1.3.1/dist/images/marker-shadow.png",
		});
		this.map1 = L.map("mpr-mapCountrywisespatialoutreach",{ scrollWheelZoom: false }).setView(
			[14.8043, 77.349],
			3
		);

		L.simpleMapScreenshoter().addTo(this.map1)

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
		for (let i = 0; i < villages.length; i++) {
			let village_name = villages[i]["name"];
			let lat = villages[i]["lat"];
			let lng = villages[i]["lang"];
			let project_name = villages[i]["project_name"];
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
			villages.map(
				(e) =>
					`<tr><td>${e.project_name}</td><td>${e.name}</td><td>${e.lat}</td><td>${e.lang}</td></tr>`
			)
		);
	}

	getAreaUnderWatershed() {
		const maxVal = Math.max(...this.watershedData.mapAreaunderwatershed.data);
		if(this.watershedData.mapAreaunderwatershed.data.reduce((a,b)=> a+b, 0) == 0){
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

		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("mpr-mapAreaunderwatershed", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494", "#7cb5ec"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: this.watershedData.mapAreaunderwatershed.categories,
				title: {
					text: "Direct Area, Indirect Area",
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				// max: 8,
				tickInterval: 2,
				title: {
					text: "Area(Ha)",
					// align: "high",
				},
				labels: {
					overflow: "justify",
				},
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: {
				enabled: false,
			},
			tooltip: {
				enabled: true,
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
					},
					states: {
						inactive: {
							opacity: 1,
						},
						hover: {
							enabled: false,
						},
					},
				},
				column: {
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
					maxPointWidth: 20,
					name: "Area",
					data: this.watershedData.mapAreaunderwatershed.data,
				},
			],
		});

		$("#table-1-tbody").html(`
      <tr>
	  <td>${this.watershedData.mapAreaunderwatershed.categories[0]}</td>
	  <td>${numberWithCommas(this.watershedData.mapAreaunderwatershed.data[0]) == 0 ? "NA" : numberWithCommas(this.watershedData.mapAreaunderwatershed.data[0])}</td>
	  </tr>
      <tr>
	  <td>${this.watershedData.mapAreaunderwatershed.categories[1]}</td>
	  <td>${numberWithCommas(this.watershedData.mapAreaunderwatershed.data[1]) == 0 ? "NA" : numberWithCommas(this.watershedData.mapAreaunderwatershed.data[1])}</td>
	  </tr>
    `);
		$("#table-1-tfoot").html(`
      <tr><td>Total</td><td>${numberWithCommas(
				this.watershedData.mapAreaunderwatershed.data[0] +
					this.watershedData.mapAreaunderwatershed.data[1]
			) == 0 ? "NA" : numberWithCommas(
				this.watershedData.mapAreaunderwatershed.data[0] +
					this.watershedData.mapAreaunderwatershed.data[1]
			)}</td></tr>
    `);
		$("#ws-di-area").html(
			`${numberWithCommas(this.watershedData.mapAreaunderwatershed.data[0]) == 0 ? "NA" : numberWithCommas(this.watershedData.mapAreaunderwatershed.data[0])}`
		);
		$("#ws-indi-area").html(
			`${numberWithCommas(this.watershedData.mapAreaunderwatershed.data[1]) == 0 ? "NA" : numberWithCommas(this.watershedData.mapAreaunderwatershed.data[1])}`
		);

		let noOfWatersheds = [...new Set(this.watershedData.tws.map((d) => d.project_name))].length;
		let noOfCountries = [...new Set(this.watershedData.tws.map((d) => d.country_id))].length
		$("#ws-indi-area-watersheds-count").html(noOfWatersheds == 0 ? "NA" : noOfWatersheds);
		$("#ws-indi-area-countries-count").html(noOfCountries == 0 ? "NA" : noOfCountries);

		let pname_list = Array.from(
			new Set(this.watershedData.tws.map((e) => e.project_name))
		);
		$("#tt-dir-list").html(
			pname_list.map((p) => {
				let directArea = this.tws
					.filter((e) => e.project_name == p)
					.map((e) => parseInt(e.direct_area || 0))
					.reduce((a, b) => a + b, 0);
				let indirectArea = this.tws
					.filter((e) => e.project_name == p)
					.map((e) => parseInt(e.indirect_area || 0))
					.reduce((a, b) => a + b, 0);
				let yrOfEstb = [... new Set(this.tws
					.filter((e) => e.project_name == p)
					.map((e) => e.establishment_year))];

				// console.log(yrOfEstb[0]);

				return `<tr><td>${p}</td><td>${yrOfEstb[0] == null ? "NA" : yrOfEstb}</td><td>${directArea == 0 ? "NA" : directArea}</td><td>${indirectArea == 0 ? "NA" : indirectArea}</td></tr>`;
			})
		);

		$("#tt-indir-list").html(
			pname_list.map((p) => {
				let indirectArea = this.tws
					.filter((e) => e.project_name == p)
					.map((e) => parseInt(e.indirect_area || 0))
					.reduce((a, b) => a + b, 0);
				return `<tr><td>${p}</td><td>${indirectArea}</td></tr>`;
			})
		);

		// let pname_list = Array.from(
		// 	new Set(this.watershedData.tws.map((e) => e.project_name))
		// );
		// // $("#tt-dir-list").html(
		// // 	pname_list.map((p) => {
		// // 		let directArea = this.tws
		// // 			.filter((e) => e.project_name == p)
		// // 			.map((e) => parseInt(e.direct_area || 0))
		// // 			.reduce((a, b) => a + b, 0);
		// // 		return `<tr><td>${p}</td><td>${directArea}</td></tr>`;
		// // 	})
		// // );
		// $('#tt-dir-list').html(
		// 	pname_list.map(p => {
		// 		let directArea = this.tws.filter(e => e.project_name == p).map(e => parseInt(e.direct_area || 0)).reduce((a, b) => a + b, 0)
		// 		let indirectArea = this.tws.filter(e => e.project_name == p).map(e => parseInt(e.indirect_area || 0)).reduce((a, b) => a + b, 0)

		// 		return `<tr><td>${p}</td><td>${directArea}</td><td>${indirectArea}</td></tr>`;
		// 	})
		// )
		// $("#tt-indir-list").html(
		// 	pname_list.map((p) => {
		// 		let indirectArea = this.tws
		// 			.filter((e) => e.project_name == p)
		// 			.map((e) => parseInt(e.indirect_area || 0))
		// 			.reduce((a, b) => a + b, 0);
		// 		return `<tr><td>${p}</td><td>${indirectArea}</td></tr>`;
		// 	})
		// );
	}

	getAreaUnderWatershedRpWise() {
		let allValues = this.watershedData.mapResearchprogramwiseunderwatershed.series
			.map((e) => e.data)
			.flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [];
		// const breakarray = [
		// 	{
		// 		from: (maxVal * 5) / 100,
		// 		to: (maxVal * 95) / 100,
		// 	},
		// ];
		// Highcharts.chart("mpr-mapResearchprogramwiseunderwatershed", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: "",
		// 	},
		// 	xAxis: {
		// 		categories: this.watershedData.mapResearchprogramwiseunderwatershed
		// 			.categories,
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: "Area (ha)",
		// 		},
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
		// 			dataLabels: {
		// 				enabled: true,
		// 				style: { textOutline: false },
		// 			},
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
		// 	series: this.watershedData.mapResearchprogramwiseunderwatershed.series,
		// });

		Highcharts.chart("mpr-mapResearchprogramwiseunderwatershed", {
			chart: { type: "column" },
			title: { text: "" },
			xAxis: {
				categories: this.watershedData.mapResearchprogramwiseunderwatershed
					.categories,
			},
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
				// {
				// 	name: "Direct",
				// 	color: "#7cb5ec",
				// 	data: chartData.map((e) => e.direct_area),
				// },
				{
					name: "Direct Area",
					color: "#7cb5ec",
					// data: this.watershedData.mapCRPwiseareaunderwatershedmanagement.series[0].data,
					data: this.watershedData.mapResearchprogramwiseunderwatershed
						.series[0].data,
				},
			],
		});
		Highcharts.chart("mpr-mapResearchprogramwiseunderwatershed-indirect", {
			chart: { type: "column" },
			title: { text: "" },
			xAxis: {
				categories: this.watershedData.mapResearchprogramwiseunderwatershed
					.categories,
			},
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
					name: "Indirect Area",
					color: "#d79494",
					// data: this.watershedData.mapCRPwiseareaunderwatershedmanagement.series[0].data,
					data: this.watershedData.mapResearchprogramwiseunderwatershed
						.series[1].data,
				},
			],
		});

		let rpWiseArea = this.watershedData.mapResearchprogramwiseunderwatershed.categories.map(
			(e, index) => {
				return {
					crp: e,
					direct_area: this.watershedData.mapResearchprogramwiseunderwatershed
						.series[0].data[index],
					indirect_area: this.watershedData.mapResearchprogramwiseunderwatershed
						.series[1].data[index],
				};
			}
		);
		$("#table-5-tbody").html(
			rpWiseArea.map(
				(e) =>
					`<tr>
					<td>${e.crp}</td>
					<td>${numberWithCommas(e.direct_area) == 0 ? "NA" : numberWithCommas(e.direct_area)}</td>
					<td>${numberWithCommas(e.indirect_area) == 0 ? "NA" : numberWithCommas(e.indirect_area)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.direct_area + e.indirect_area) == 0 ? "NA" : numberWithCommas(e.direct_area + e.indirect_area)}</td>
					</tr>`
			)
		);

		let tfDir = numberWithCommas(rpWiseArea.map((e) => e.direct_area).reduce((a, b) => a + b, 0));
		let tfIndir = numberWithCommas(rpWiseArea.map((e) => e.indirect_area).reduce((a, b) => a + b, 0));
		let tfTotal = numberWithCommas(rpWiseArea.map((e) => e.direct_area).reduce((a, b) => a + b, 0) + rpWiseArea.map((e) => e.indirect_area).reduce((a, b) => a + b, 0))
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${ tfDir == 0 ? "NA" : tfDir}</td>
			<td>${ tfIndir == 0 ? "NA" : tfIndir}</td>
			<td>${tfTotal == 0 ? "NA" : tfTotal}</td></tr>
		`;
		$("#table-5-tfoot").html(tableFooter);
	}

	getAreaUnderWatershedCprWise() {
		let allValues = this.watershedData.mapCRPwiseareaunderwatershedmanagement.series
			.map((e) => e.data)
			.flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [];
		// const breakarray = [
		// 	{
		// 		from: (maxVal * 5) / 100,
		// 		to: (maxVal * 95) / 100,
		// 	},
		// ];

		// Highcharts.chart("mpr-mapCRPwiseareaunderwatershedmanagement", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: null,
		// 	},
		// 	subtitle: {
		// 		text: null,
		// 	},
		// 	colors: ["#d79494", "#7cb5ec"],
		// 	credits: {
		// 		enabled: false,
		// 	},
		// 	legend: {
		// 		y: 10,
		// 	},
		// 	xAxis: {
		// 		categories: this.watershedData.mapCRPwiseareaunderwatershedmanagement.categories,
		// 		title: {
		// 			text: null,
		// 		},
		// 	},
		// 	yAxis: {
		// 		//opposite: true,
		// 		min: 0,
		// 		// max: 8,
		// 		tickInterval: 2,
		// 		title: {
		// 			text: "",
		// 			align: "high",
		// 		},
		// 		labels: {
		// 			overflow: "justify",
		// 		},
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	legend: {
		// 		enabled: false,
		// 	},
		// 	tooltip: {
		// 		enabled: true,
		// 	},
		// 	plotOptions: {
		// 		series: {
		// 			dataLabels: {
		// 				enabled: true,
		// 				style: { textOutline: false },
		// 			},
		// 			states: {
		// 				inactive: {
		// 					opacity: 1,
		// 				},
		// 				hover: {
		// 					enabled: false,
		// 				},
		// 			},
		// 		},
		// 		column: {
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
		// 	series: [
		// 		{
		// 			name: "Direct",
		// 			color: "#d79494",
		// 			data: this.watershedData.mapCRPwiseareaunderwatershedmanagement.series[0].data,
		// 		}
		// 	],
		// });
		Highcharts.chart("mpr-mapCRPwiseareaunderwatershedmanagement", {
			// chart: { type: "column" },
			// title: { text: "" },
			// xAxis: {
			// 	categories: this.watershedData.mapCRPwiseareaunderwatershedmanagement
			// 		.categories,
			// },
			// yAxis: {
			// 	min: 0,
			// 	title: { text: "Area (ha)" },
			// 	breaks: breakarray,
			// 	events: {
			// 		pointBreak: pointBreakColumn,
			// 	},
			// },
			// credits: { enabled: false },
			// tooltip: {
			// 	pointFormat:
			// 		'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %) <br/>',
			// 	shared: true,
			// },
			// plotOptions: {
			// 	column: {
			// 		stacking: "normal",
			// 		dataLabels: { enabled: true, style: { textOutline: false } },
			// 		point: {
			// 			events: {
			// 				mouseOver: function () {
			// 					const chart = this,
			// 						yAxis = chart.series.yAxis;
			// 					yAxis.update({
			// 						breaks: [],
			// 					});
			// 				},
			// 				mouseOut: function () {
			// 					const chart = this,
			// 						yAxis = chart.series.yAxis;
			// 					yAxis.update({
			// 						breaks: breakarray,
			// 					});
			// 				},
			// 			},
			// 		},
			// 	},
			// },
			chart: {
				type: "column",
			  },
			  title: {
				text: null,
			  },
			  subtitle: {
				text: null,
			  },
			  colors: ["#d79494", "#7cb5ec"],
			  credits: {
				enabled: false,
			  },
			  legend: {
				y: 0,
			  },
			  xAxis: {
				categories: this.watershedData.mapCRPwiseareaunderwatershedmanagement
				.categories,
				title: {
				  text: null,
				},
			  },
			  yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
				  text: "Yield on-station and yield on-farm (kg/ha)",
				  align: "high",
				},
				labels: {
				  overflow: "justify",
				},
			  },
			  tooltip: {
				enabled: true,
			  },
			  plotOptions: {
				series: {
				  dataLabels: {
					enabled: true,
					style: { textOutline: false },
				  },
				  states: {
					inactive: {
					  opacity: 1,
					},
					hover: {
					  enabled: false,
					},
				  },
				},
			  },
			series: [
				{
				  maxPointWidth: 20,
				  name: "Direct Area ",
				  data: this.watershedData.mapCRPwiseareaunderwatershedmanagement
				  .series[0].data,
				},
				{
				  maxPointWidth: 20,
				  name: "Indirect Area",
				  data: this.watershedData.mapCRPwiseareaunderwatershedmanagement
				  .series[1].data,
				},
			  ],

		});

		// Highcharts.chart("mpr-mapCRPwiseareaunderwatershedmanagement-indirect", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: null,
		// 	},
		// 	subtitle: {
		// 		text: null,
		// 	},
		// 	colors: ["#d79494", "#7cb5ec"],
		// 	credits: {
		// 		enabled: false,
		// 	},
		// 	legend: {
		// 		y: 10,
		// 		//enabled: true,
		// 	},
		// 	xAxis: {
		// 		categories: this.watershedData.mapCRPwiseareaunderwatershedmanagement.categories,
		// 		title: {
		// 			text: null,
		// 		},
		// 	},
		// 	yAxis: {
		// 		//opposite: true,
		// 		min: 0,
		// 		// max: 8,
		// 		tickInterval: 2,
		// 		title: {
		// 			text: "",
		// 			align: "high",
		// 		},
		// 		labels: {
		// 			overflow: "justify",
		// 		},
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	legend: {
		// 		enabled: false,
		// 	},
		// 	tooltip: {
		// 		enabled: true,
		// 	},
		// 	plotOptions: {
		// 		series: {
		// 			dataLabels: {
		// 				enabled: true,
		// 				style: { textOutline: false },
		// 			},
		// 			states: {
		// 				inactive: {
		// 					opacity: 1,
		// 				},
		// 				hover: {
		// 					enabled: false,
		// 				},
		// 			},
		// 		},
		// 		column: {
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
		// 	series: [
		// 		{
		// 			name: "Indirect",
		// 			color: "#d79494",
		// 			data: this.watershedData.mapCRPwiseareaunderwatershedmanagement.series[1].data,
		// 		},
		// 	],
		// });

		// Highcharts.chart("mpr-mapCRPwiseareaunderwatershedmanagement-indirect", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: {
		// 		categories: this.watershedData.mapCRPwiseareaunderwatershedmanagement
		// 			.categories,
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
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %) <br/>',
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
		// 	series: [
		// 		{
		// 			name: "Indirect",
		// 			color: "#d79494",
		// 			data: this.watershedData.mapCRPwiseareaunderwatershedmanagement
		// 				.series[1].data,
		// 		},
		// 	],
		// });

		let crpWiseArea = this.watershedData.mapCRPwiseareaunderwatershedmanagement.categories.map(
			(e, index) => {
				return {
					crp: e,
					direct_area: this.watershedData.mapCRPwiseareaunderwatershedmanagement
						.series[0].data[index],
					indirect_area: this.watershedData
						.mapCRPwiseareaunderwatershedmanagement.series[1].data[index],
				};
			}
		);
		$("#table-4-tbody").html(
			crpWiseArea.map(
				(e) =>
					`<tr><td>${e.crp}</td>
					<td>${numberWithCommas(e.direct_area) == 0 ? "NA" : numberWithCommas(e.direct_area)}</td>
					<td>${numberWithCommas(e.indirect_area) == 0 ? "NA" : numberWithCommas(e.indirect_area)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.indirect_area + e.direct_area) == 0 ? "NA" : numberWithCommas(e.indirect_area + e.direct_area)}</td></tr>`
			)
		);
		let tdDir = numberWithCommas(crpWiseArea.map((e) => e.direct_area).reduce((a, b) => a + b, 0));
		let tdIndir = numberWithCommas(crpWiseArea.map((e) => e.indirect_area).reduce((a, b) => a + b, 0))
		let tdTotal = numberWithCommas(crpWiseArea.map((e) => e.direct_area).reduce((a, b) => a + b, 0) + crpWiseArea.map((e) => e.indirect_area).reduce((a, b) => a + b, 0));
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${ tdDir == 0 ? "NA" : tdDir}</td>
			<td>${ tdIndir == 0 ? "NA" : tdIndir}</td>
			<td>${ tdTotal == 0 ? "NA" : tdTotal}</td></tr>
		`;
		$("#table-4-tfoot").html(tableFooter);
	}

	getScientificPublications() {
		let totalSP = this.watershedData.mapScientificpublication
			.map((e) => e.y)
			.reduce((a, b) => a + b, 0);
		this.watershedData.mapScientificpublication.forEach(
			(e) => (e.percent = (e.y * 100) / totalSP)
		);
		Highcharts.chart("mpr-pieChartType3", {
			chart: {
				type: "pie",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494", "#7cb5ec", "#000000"],
			credits: {
				enabled: false,
			},
			legend: {
				enabled: true,
			},
			plotOptions: {
				pie: {
					allowPointSelect: false,
					dataLabels: {
						enabled: true,
						format: "{point.name}: {point.y} ({point.percent:.2f} %)",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percent:.2f} %)<br/>',
			},
			series: [
				{
					name: "Scientific Publications",
					colorByPoint: true,
					data: this.watershedData.mapScientificpublication,
				},
			],
		});

		// $("#table-6-tbody").html(
		// 	this.watershedData.mapScientificpublication.map(
		// 		(e) => `<tr><td>${e.name}</td><td>${e.y}</td></tr>`
		// 	)
		// );
		let total_scientifics = this.watershedData.mapScientificpublication
			.map((e) => e.y)
			.reduce((a, b) => a + b, 0);

		$("#table-6-total-count").html(total_scientifics == 0 ? "NA" : total_scientifics);
		$("#table-6-tbody").html(
			this.watershedData.mapScientificpublication.map(
				(e) =>
					`<tr><td>${e.name}</td><td>${e.y == 0 ? "NA" : e.y}</td><td>${Number(((e.y / total_scientifics) * 100).toFixed(2)) == 0 ? "NA" : ((e.y / total_scientifics) * 100).toFixed(2)} %</td></tr>`
			)
		);
	}

	/*
  generate sankey graph to watershed relation among country, CRP and Research Program
  */
	generateWaterShedSankeyGraph() {
		let chartData = [];
		if (this.selectedSankyType == 1) {
			chartData = indexFilter.countries
				.map((data) => {
					return indexFilter.sdgs.map((sdg) => {
						const result = {
							from: data.country_name,
							to: sdg.sdg_name,
							value: 0,
							width: 10,
						};
						result.value = this.tws.filter(
							(d) =>
								d.country_id == data.country_id &&
								d.sdg.some((e) => e.sdg_id == sdg.sdg_id)
						).length;
						return result;
					});
				})
				.flat()
				.filter((d) => d.value > 0);
		} else if (this.selectedSankyType == 2) {
			chartData = indexFilter.crps
				.map((data) => {
					return indexFilter.sdgs.map((sdg) => {
						const result = {
							from: data.crp_name,
							to: sdg.sdg_name,
							value: 0,
							width: 10,
						};
						result.value = this.tws.filter(
							(d) =>
								d.crp.some((e) => e.crp_id == data.crp_id) &&
								d.sdg.some((e) => e.sdg_id == sdg.sdg_id)
						).length;
						return result;
					});
				})
				.flat()
				.filter((d) => d.value > 0);
		} else if (this.selectedSankyType == 3) {
			chartData = indexFilter.reasearchPrograms
				.map((data) => {
					return indexFilter.sdgs.map((sdg) => {
						const result = {
							from: data.rp_name,
							to: sdg.sdg_name,
							value: 0,
							width: 10,
						};
						result.value = this.tws.filter(
							(d) =>
								d.rp_id == data.rp_id &&
								d.sdg.some((e) => e.sdg_id == sdg.sdg_id)
						).length;
						return result;
					});
				})
				.flat()
				.filter((d) => d.value > 0);
		}
		// MPR Flow Chart
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"mpr-watershedinterventionmaped",
				am4charts.SankeyDiagram
			);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.data = chartData;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";

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
			chart.exporting.filePrefix = "watershedinterventionmaped";
		});
	}

	generateCountriesMap() {
		// const mapData = indexFilter.countries.map(data => {
		//   const result = {id: data.country_id, name: data.country_name, countryCode: data.country_code};
		//   result.z =  this.tws.filter(d => d.country_id == data.country_id).map(e => parseInt(e.direct_area)+parseInt(e.indirect_area || 0)).reduce((a, b) => a+b, 0);
		//   return result;
		// }).filter(d => d.z > 0);
		// Highcharts.mapChart("mpr-mapAreaunderwatershedmanagementcountries", {
		//   chart: {
		//     borderWidth: 0,
		//     map: "custom/world",
		//   },
		//   title: {
		//     text: null,
		//   },
		//   subtitle: {
		//     text: null,
		//   },
		//   credits: {
		//     enabled: false,
		//   },
		//   legend: {
		//     enabled: false,
		//   },
		//   mapNavigation: {
		//     enabled: true,
		//     buttonOptions: {
		//       verticalAlign: "bottom",
		//     },
		//   },
		//   series: [
		//     {
		//       name: "Countries",
		//       color: "#4dabf5",
		//       enableMouseTracking: false,
		//     },
		//     {
		//       type: "mapbubble",
		//       name: "Area",
		//       joinBy: ["iso-a2", "countryCode"],
		//       data: mapData,
		//       minSize: 4,
		//       maxSize: "12%",
		//       tooltip: {
		//         pointFormat: "{point.name}: {point.z} ha",
		//       },
		//       dataLabels: {
		//         enabled: true,
		//         style: {textOutline: false}
		//       }
		//     },
		//   ],
		// });

		const mapData = indexFilter.countries
			.map((data) => {
				let result = { id: data.country_code, name: data.country_name };
				result.value = this.tws
					.filter((d) => d.country_id == data.country_id)
					.map((e) => parseInt(e.direct_area) + parseInt(e.indirect_area || 0))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.value > 0);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"mpr-mapAreaunderwatershedmanagementcountries",
				am4maps.MapChart
			);
			mapData.forEach((d, i) => (d.color = chart.colors.getIndex(i)));
			chart.geodata = am4geodata_worldIndiaLow;
			chart.projection = new am4maps.projections.Miller();
			chart.logo.disabled = "true";
			chart.numberFormatter.numberFormat = "#,###.##";

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
			label.padding(-10, 0, 0, 0);
			label.fontSize = 10;
			label.adapter.add("dy", function (dy, target) {
				var circle = target.parent.children.getIndex(0);
				return circle.pixelRadius;
			});
		});

		$("#table-2-tbody").html(
			mapData.map(
				(e) =>
					`<tr><td>${e.name}</td><td>${numberWithCommas(e.value) == 0 ? "NA" : numberWithCommas(e.value)}</td></tr>`
			)
		);

		let tfTotal = numberWithCommas(mapData.map((e) => e.value).reduce((a, b) => a + b, 0))
		$("#table-2-tfoot").html(
			`<tr><td>Total</td><td>${tfTotal == 0 ? "NA" : tfTotal}</td></tr>`
		);
	}

	generateAreaUnderWatershedYearWise() {
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				return {
					year: yr.year,
					direct_area: this.tws
						.filter((d) => d.year_id == yr.year_id)
						.map((d) => parseInt(d.direct_area))
						.reduce((a, b) => a + b, 0),
					indirect_area: this.tws
						.filter((d) => d.year_id == yr.year_id)
						.map((d) => parseInt(d.indirect_area || 0))
						.reduce((a, b) => a + b, 0),
				};
			})
			.filter((d) => d.direct_area || d.indirect_area);

		let allValues = chartData
			.map((e) => [e.direct_area, e.indirect_area])
			.flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("ws-year-graph", {
			chart: { type: "column" },
			title: { text: "" },
			xAxis: { categories: chartData.map((e) => e.year) },
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
					name: "Direct",
					color: "#7cb5ec",
					data: chartData.map((e) => e.direct_area),
				},
				// {
				// 	name: "Indirect",
				// 	color: "#d79494",
				// 	data: chartData.map((e) => e.indirect_area),
				// },
			],
		});
		Highcharts.chart("ws-year-graph-indirect", {
			chart: { type: "column" },
			title: { text: "" },
			xAxis: { categories: chartData.filter(d=> d.indirect_area > 0 ).map((e) => e.year) },
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
				// {
				// 	name: "Direct",
				// 	color: "#7cb5ec",
				// 	data: chartData.map((e) => e.direct_area),
				// },
				{
					name: "Indirect",
					color: "#d79494",
					data: chartData.filter(d=> d.indirect_area > 0 ).map((e) => e.indirect_area),
				},
			],
		});
		$("#table-7-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td>
					<td>${numberWithCommas(e.direct_area) == 0 ? "NA" : numberWithCommas(e.direct_area)}</td>
					<td>${numberWithCommas(e.indirect_area) == 0 ? "NA" : numberWithCommas(e.indirect_area)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.direct_area + e.indirect_area) == 0 ? "NA" : numberWithCommas(e.direct_area + e.indirect_area)}</td></tr>`
			)
		);

		let tfdir = numberWithCommas(chartData.map((e) => e.direct_area).reduce((a, b) => a + b, 0));
		let tfIndir = numberWithCommas(chartData.map((e) => e.indirect_area).reduce((a, b) => a + b, 0));
		let tfTotal = numberWithCommas(chartData.map((e) => e.direct_area).reduce((a, b) => a + b, 0) +
		chartData.map((e) => e.indirect_area).reduce((a, b) => a + b, 0));

		let tableFooter = `
    		<tr><td>Total</td>
			<td>${tfdir == 0 ? "NA" : tfdir}</td>
			<td>${tfIndir == 0 ? "NA" : tfIndir}</td>
			<td>${tfTotal == 0 ? "NA" : tfTotal}</td></tr>
		`;
		$("#table-7-tfoot").html(tableFooter);
	}
	getHtmlActionForCRPYearComparison() {
		const crpListHtml = `
			<a class="dropdown-item year-comp-crp-list" data-value="0" data-label="All Types"><i class=""></i> All Types</a>
			<a class="dropdown-item year-comp-crp-list" data-value="1" data-label="Direct Area"><i class=""></i> Direct Area</a>
			<a class="dropdown-item year-comp-crp-list" data-value="2" data-label="Indirect Area"><i class=""></i> Indirect Area</a>
		`;
		$("#year-comp-crps").html(crpListHtml);
		$("#year-comp-crps-pie").html(crpListHtml);

		$(".year-comp-crp-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crp-name").html(data.label);
			$("#year-comp-crp-name").data("value", data.value);
			this.graphCRPYearwiseArea();
		});
	}

	// graphCRPYearwiseArea() {
	// 	let selectedLandType = $("#year-comp-crp-name").data("value");
	// 	let chartData = indexFilter.dataViewYears.map((yr) => {
	// 		let result = { year: yr.year };

	// 		indexFilter.pi2020FilterData.crps.forEach((c) => {
	// 			let crpDataRecords = this.watershedData.tw_crps
	// 				.filter((e) => e.crp_id == c.crp_id)
	// 				.map((e) => e.data_id);
	// 			let matchedRecords = this.watershedData.tws.filter(
	// 				(e) => crpDataRecords.includes(e.data_id) && e.year_id == yr.year_id
	// 			);
	// 			result[c.crp_name] = matchedRecords
	// 				.map(
	// 					(e) =>
	// 						parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
	// 				)
	// 				.reduce((a, b) => a + b, 0);
	// 			switch (true) {
	// 				case selectedLandType == 0:
	// 					result[c.crp_name] = matchedRecords
	// 						.map(
	// 							(e) =>
	// 								parseFloat(e.direct_area || 0) +
	// 								parseFloat(e.indirect_area || 0)
	// 						)
	// 						.reduce((a, b) => a + b, 0);
	// 					break;
	// 				case selectedLandType == 1:
	// 					result[c.crp_name] = matchedRecords
	// 						.map((e) => parseFloat(e.direct_area || 0))
	// 						.reduce((a, b) => a + b, 0);
	// 					break;
	// 				case selectedLandType == 2:
	// 					result[c.crp_name] = matchedRecords
	// 						.map((e) => parseFloat(e.indirect_area || 0))
	// 						.reduce((a, b) => a + b, 0);
	// 					break;
	// 			}
	// 		});
	// 		return result;
	// 	});

	// 	let allValues = chartData
	// 		.map((e) =>
	// 			Object.keys(e)
	// 				.filter((e) => e != "year")
	// 				.map((f) => e[f])
	// 		)
	// 		.flat();
	// 	const maxVal = Math.max(...allValues);
	// 	const breakarray = [
	// 		{
	// 			from: (maxVal * 5) / 100,
	// 			to: (maxVal * 95) / 100,
	// 		},
	// 	];

	// 	const sortedData = indexFilter.pi2020FilterData.crps.map((e) => {
	// 		return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name]) };
	// 	})

	// 	Highcharts.chart("wm-year-crp-graph", {
	// 		chart: { type: "column" },
	// 		title: { text: "" },
	// 		xAxis: { categories: chartData.map((e) => e.year) },
	// 		yAxis: {
	// 			min: 0,
	// 			title: { text: "Area (ha)" },
	// 			breaks: breakarray,
	// 			events: {
	// 				pointBreak: pointBreakColumn,
	// 			},
	// 		},
	// 		credits: { enabled: false },
	// 		tooltip: {
	// 			pointFormat:
	// 				'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %) <br/>',
	// 			shared: true,
	// 		},
	// 		plotOptions: {
	// 			column: {
	// 				// stacking: "normal",
	// 				stacking: "percent",
	// 				dataLabels: {
	// 					enabled: true,
	// 					format: "{point.y:.2f}",
	// 					style: { textOutline: false },
	// 				},
	// 				point: {
	// 					events: {
	// 						mouseOver: function () {
	// 							const chart = this,
	// 								yAxis = chart.series.yAxis;
	// 							yAxis.update({
	// 								breaks: [],
	// 							});
	// 						},
	// 						mouseOut: function () {
	// 							const chart = this,
	// 								yAxis = chart.series.yAxis;
	// 							yAxis.update({
	// 								breaks: breakarray,
	// 							});
	// 						},
	// 					},
	// 				},
	// 			},
	// 		},

	// 		series: indexFilter.pi2020FilterData.crps.map((e) => {
	// 			return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name]) };
	// 		}),
	// 	});

	// 	$("#table-8-thead-row").html(
	// 		`<th>CRP</th>` + chartData.map((e) => `<th>${e.year}</th>`)
	// 	);
	// 	$("#table-8-tbody").html(
	// 		indexFilter.pi2020FilterData.crps.map((e) => {
	// 			let yVals = chartData.map(
	// 				(f) => `<td>${numberWithCommas(f[e.crp_name].toFixed(2))}</td>`
	// 			);
	// 			return `<tr><td>${e.crp_name}</td>${yVals}</tr>`;
	// 		})
	// 	);
	// }

	//contries year wise comparison

	graphCRPYearwiseArea() {
		let selectedLandType = $("#year-comp-crp-name").data("value");
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };

			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = this.watershedData.tw_crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				let matchedRecords = this.watershedData.tws.filter(
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
		});
		let chartData_direct = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			let areaType = 1;
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = this.watershedData.tw_crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				let matchedRecords = this.watershedData.tws.filter(
					(e) => crpDataRecords.includes(e.data_id) && e.year_id == yr.year_id
				);
				result[c.crp_name] = matchedRecords
					.map(
						(e) =>
							parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
					)
					.reduce((a, b) => a + b, 0);
				switch (true) {
					case areaType == 0:
						result[c.crp_name] = matchedRecords
							.map(
								(e) =>
									parseFloat(e.direct_area || 0) +
									parseFloat(e.indirect_area || 0)
							)
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 1:
						result[c.crp_name] = matchedRecords
							.map((e) => parseFloat(e.direct_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 2:
						result[c.crp_name] = matchedRecords
							.map((e) => parseFloat(e.indirect_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
				}
			});
			return result;
		});
		let chartData_indirect = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			let areaType = 2;
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = this.watershedData.tw_crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				let matchedRecords = this.watershedData.tws.filter(
					(e) => crpDataRecords.includes(e.data_id) && e.year_id == yr.year_id
				);
				result[c.crp_name] = matchedRecords
					.map(
						(e) =>
							parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
					)
					.reduce((a, b) => a + b, 0);
				switch (true) {
					case areaType == 0:
						result[c.crp_name] = matchedRecords
							.map(
								(e) =>
									parseFloat(e.direct_area || 0) +
									parseFloat(e.indirect_area || 0)
							)
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 1:
						result[c.crp_name] = matchedRecords
							.map((e) => parseFloat(e.direct_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 2:
						result[c.crp_name] = matchedRecords
							.map((e) => parseFloat(e.indirect_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
				}
			});
			return result;
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

		// Highcharts.chart("wm-year-crp-graph", {
		// 	chart: { type: 'column' },
		// 	title: { text: '' },
		// 	// xAxis: {categories: chartData.map(e => e.year)},
		// 	xAxis: { categories: chartData.filter(e => !Object.keys(e).filter(e => e != "year").map(f => e[f]).every(f => f == 0)).map(e => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: 'Area (ha)' },
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	credits: { enabled: false, },
		// 	tooltip: {
		// 		pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %) <br/>',
		// 		shared: true
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			// stacking: 'normal',
		// 			stacking: 'percent',
		// 			dataLabels: { enabled: true, format: '{point.y:.2f}', style: { textOutline: false } },
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
		// 		}
		// 	},
		// 	// series: indexFilter.pi2020FilterData.crps.map(e => {
		// 	// return {'name': e.crp_name, 'data': chartData.map(f => f[e.crp_name])}
		// 	// })
		// 	series: indexFilter.pi2020FilterData.crps.map(c => {
		// 		let result = { 'name': c.crp_name, 'data': chartData.filter(e => e[c.crp_name] > 0).map(e => e[c.crp_name]) };
		// 		return result;
		// 	}).filter(e => !Object.keys(e.data).map(f => e[f]).every(f => f == 0))
		// });

		// Highcharts.chart("wm-year-crp-pieChartType3", {
		// 	chart: {
		// 		type: "pie",
		// 	},
		// 	title: {
		// 		text: null,
		// 	},
		// 	subtitle: {
		// 		text: null,
		// 	},
		// 	colors: ["#d79494", "#7cb5ec", "#000000"],
		// 	credits: {
		// 		enabled: false,
		// 	},
		// 	legend: {
		// 		enabled: true,
		// 	},
		// 	plotOptions: {
		// 		pie: {
		// 			allowPointSelect: false,
		// 			dataLabels: {
		// 				enabled: true,
		// 				format: "{point.name}: {point.y} ({point.percent:.2f} %)",
		// 				style: { textOutline: false },
		// 			},
		// 			showInLegend: true,
		// 		},
		// 	},
		// 	tooltip: {
		// 		headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
		// 		pointFormat:
		// 			'<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percent:.2f} %)<br/>',
		// 	},
		// 	series: [
		// 		{
		// 			name: ["Scientific Publications","asd",'sdf'],
		// 			colorByPoint: true,
		// 			data: [23,323,12],
		// 		},
		// 	]
		// });

		Highcharts.chart("wm-year-crp-graph-dir", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: "Direct area",
				verticalAlign: "bottom",
			},
			xAxis: {
				categories: chartData_direct
					.filter(
						(e) =>
							!Object.keys(e)
								.filter((e) => e != "year")
								.map((f) => e[f])
								.every((f) => f == 0)
					)
					.map((e) => e.year),
			},
			yAxis: {
				title: {
					text: "Area (ha)",
				},
				// labels: {
				// 	formatter: function () {
				// 		return this.value / 1000;
				// 	}
				// }
			},
			tooltip: {
				split: true,
				valueSuffix: null,
			},
			plotOptions: {
				// area: {
				// 	stacking: 'normal',
				// 	lineColor: '#666666',
				// 	lineWidth: 1,
				// 	marker: {
				// 		lineWidth: 1,
				// 		lineColor: '#666666'
				// 	}
				// }
				area: {
					fillOpacity: 0.5,
					// pointStart: 1940,
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
			series: indexFilter.pi2020FilterData.crps
				.map((c) => {
					let result = {
						name: c.crp_name,
						data: chartData_direct
							.filter((e) => e[c.crp_name] > 0)
							.map((e) => Number(e[c.crp_name].toFixed(2))),
					};
					return result;
				})
				.filter(
					(e) =>
						!Object.keys(e.data)
							.map((f) => e[f])
							.every((f) => f == 0)
				),
		});
		Highcharts.chart("wm-year-crp-graph-indir", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: "Indirect area",
				verticalAlign: "bottom",
			},
			xAxis: {
				categories: chartData_indirect
					.filter(
						(e) =>
							!Object.keys(e)
								.filter((e) => e != "year")
								.map((f) => e[f])
								.every((f) => f == 0)
					)
					.map((e) => e.year),
			},
			yAxis: {
				title: {
					text: "Area (ha)",
				},
				// labels: {
				// 	formatter: function () {
				// 		return this.value / 1000;
				// 	}
				// }
			},
			tooltip: {
				split: true,
				valueSuffix: null,
			},
			plotOptions: {
				// area: {
				// 	stacking: 'normal',
				// 	lineColor: '#666666',
				// 	lineWidth: 1,
				// 	marker: {
				// 		lineWidth: 1,
				// 		lineColor: '#666666'
				// 	}
				// }
				area: {
					fillOpacity: 0.5,
					// pointStart: 1940,
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
			series: indexFilter.pi2020FilterData.crps
				.map((c) => {
					let result = {
						name: c.crp_name,
						data: chartData_indirect
							.filter((e) => e[c.crp_name] > 0)
							.map((e) => Number(e[c.crp_name].toFixed(2))),
					};
					return result;
				})
				.filter(
					(e) =>
						!Object.keys(e.data)
							.map((f) => e[f])
							.every((f) => f == 0)
				),
		});

		$("#table-8-thead-row").html(
			`<th>CRP</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-8-tbody").html(
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
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-8-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue) == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	getHtmlActionForContriesYearComparison() {
		const crpListHtml = `
			<a class="dropdown-item year-contry-list" data-value="0" data-label="All Types"><i class=""></i> All Types</a>
			<a class="dropdown-item year-contry-list" data-value="1" data-label="Direct Area"><i class=""></i> Direct Area</a>
			<a class="dropdown-item year-contry-list" data-value="2" data-label="Indirect Area"><i class=""></i> Indirect Area</a>
		`;
		$("#year-comp-contry").html(crpListHtml);
		$(".year-contry-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-contries").html(data.label);
			$("#year-comp-contries").data("value", data.value);
			this.getYearContryInfo();
		});
	}
	getYearContryInfo() {
		let selectedLandType = $("#year-comp-contries").data("value");
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				let matchedRecords = this.watershedData.tws.filter(
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
		});

		let chartData_direct = indexFilter.dataViewYears.map((yr) => {
			let areaType = 1;
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				let matchedRecords = this.watershedData.tws.filter(
					(e) => e.country_id == c.country_id && e.year_id == yr.year_id
				);
				result[c.country_name] = matchedRecords
					.map(
						(e) =>
							parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
					)
					.reduce((a, b) => a + b, 0);
				switch (true) {
					case areaType == 0:
						result[c.country_name] = matchedRecords
							.map(
								(e) =>
									parseFloat(e.direct_area || 0) +
									parseFloat(e.indirect_area || 0)
							)
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 1:
						result[c.country_name] = matchedRecords
							.map((e) => parseFloat(e.direct_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 2:
						result[c.country_name] = matchedRecords
							.map((e) => parseFloat(e.indirect_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
				}
			});
			return result;
		});

		let chartData_indirect = indexFilter.dataViewYears.map((yr) => {
			let areaType = 2;
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				let matchedRecords = this.watershedData.tws.filter(
					(e) => e.country_id == c.country_id && e.year_id == yr.year_id
				);
				result[c.country_name] = matchedRecords
					.map(
						(e) =>
							parseFloat(e.direct_area || 0) + parseFloat(e.indirect_area || 0)
					)
					.reduce((a, b) => a + b, 0);
				switch (true) {
					case areaType == 0:
						result[c.country_name] = matchedRecords
							.map(
								(e) =>
									parseFloat(e.direct_area || 0) +
									parseFloat(e.indirect_area || 0)
							)
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 1:
						result[c.country_name] = matchedRecords
							.map((e) => parseFloat(e.direct_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
					case areaType == 2:
						result[c.country_name] = matchedRecords
							.map((e) => parseFloat(e.indirect_area || 0))
							.reduce((a, b) => a + b, 0);
						break;
				}
			});
			return result;
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

		// Highcharts.chart("contry-year-wise-graphs", {
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
		// 			// stacking: "normal",
		// 			stacking: "percent",
		// 			dataLabels: {
		// 				enabled: true,
		// 				format: "{point.y:.2f}",
		// 				style: { textOutline: false },
		// 			},
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
		// Highcharts.chart("contry-year-wise-graphs-dir", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: {
		// 		categories: chartData_direct
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
		// 			// stacking: "normal",
		// 			stacking: "percent",
		// 			dataLabels: {
		// 				enabled: true,
		// 				format: "{point.y:.2f}",
		// 				style: { textOutline: false },
		// 			},
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
		// 				data: chartData_direct
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

		Highcharts.chart("contry-year-wise-graphs-dir", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: "Direct area",
				verticalAlign: "bottom",
			},
			// xAxis: {
			// 	categories: ['2017','2018','2019','2020'],
			// 	tickmarkPlacement: 'on',
			// 	title: {
			// 		enabled: false
			// 	}
			// },
			// xAxis: { categories: chartData.filter(e => !Object.keys(e).filter(e => e != "year").map(f => e[f]).every(f => f == 0)).map(e => e.year) },
			xAxis: {
				categories: chartData_direct
					.filter(
						(e) =>
							!Object.keys(e)
								.filter((e) => e != "year")
								.map((f) => e[f])
								.every((f) => f == 0)
					)
					.map((e) => e.year),
				tickmarkPlacement: "on",
			},
			yAxis: {
				title: {
					text: "Area (ha)",
				},
				// labels: {
				// 	formatter: function () {
				// 		return this.value / 1000;
				// 	}
				// }
			},
			tooltip: {
				split: true,
				valueSuffix: null,
			},
			plotOptions: {
				// area: {
				// 	stacking: 'normal',
				// 	lineColor: '#666666',
				// 	lineWidth: 1,
				// 	opacity:0.9,
				// 	marker: {
				// 		lineWidth: 1,
				// 		lineColor: '#666666'
				// 	}
				// }
				area: {
					fillOpacity: 0.5,
					// pointStart: 1940,
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
			series: indexFilter.pi2020FilterData.countries
				.map((c) => {
					let result = {
						name: c.country_name,
						data: chartData_direct
							.filter((e) => e[c.country_name] > 0)
							.map((e) => Number(e[c.country_name].toFixed(2))),
					};
					return result;
				})
				.filter(
					(e) =>
						!Object.keys(e.data)
							.map((f) => e[f])
							.every((f) => f == 0)
				),
		});

		Highcharts.chart("contry-year-wise-graphs-indir", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: "Indirect area",
				verticalAlign: "bottom",
			},
			// xAxis: {
			// 	categories: ['2017','2018','2019','2020'],
			// 	tickmarkPlacement: 'on',
			// 	title: {
			// 		enabled: false
			// 	}
			// },
			// xAxis: { categories: chartData.filter(e => !Object.keys(e).filter(e => e != "year").map(f => e[f]).every(f => f == 0)).map(e => e.year) },
			xAxis: {
				categories: chartData_indirect
					.filter(
						(e) =>
							!Object.keys(e)
								.filter((e) => e != "year")
								.map((f) => e[f])
								.every((f) => f == 0)
					)
					.map((e) => e.year),
			},
			yAxis: {
				title: {
					text: "Area (ha)",
				},
				// labels: {
				// 	formatter: function () {
				// 		return this.value / 1000;
				// 	}
				// }
			},
			tooltip: {
				split: true,
				valueSuffix: null,
			},
			plotOptions: {
				// area: {
				// 	stacking: 'normal',
				// 	lineColor: '#666666',
				// 	lineWidth: 1,
				// 	marker: {
				// 		lineWidth: 1,
				// 		lineColor: '#666666'
				// 	}
				// }
				area: {
					fillOpacity: 0.5,
					// pointStart: 1940,
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
			series: indexFilter.pi2020FilterData.countries
				.map((c) => {
					let result = {
						name: c.country_name,
						data: chartData_indirect
							.filter((e) => e[c.country_name] > 0)
							.map((e) => Number(e[c.country_name].toFixed(2))),
					};
					return result;
				})
				.filter(
					(e) =>
						!Object.keys(e.data)
							.map((f) => e[f])
							.every((f) => f == 0)
				),
		});
		// Highcharts.chart("contry-year-wise-graphs-indir", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: {
		// 		categories: chartData_indirect
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
		// 			// stacking: "normal",
		// 			stacking: "percent",
		// 			dataLabels: {
		// 				enabled: true,
		// 				format: "{point.y:.2f}",
		// 				style: { textOutline: false },
		// 			},
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
		// 				data: chartData_indirect
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

		// $("#table-9-thead-row").html(
		// 	`<th>Countries</th>` + chartData.map((e) => `<th>${e.year}</th>`)
		// );

		// console.log(chartData_direct);

		$("#table-9-thead-row").html(
			`<th>Countries</th>` +
				chartData_indirect.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-9-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${numberWithCommas(f[e.country_name].toFixed(2)) == 0 ? "NA" : numberWithCommas(f[e.country_name].toFixed(2))}</td>`
					);
					let yValss = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b, 0)
						.toFixed(2);

					return `<tr><td>${e.country_name}</td>${yValsHtml}<td style="font-weight: 600;">${yValss == 0 ? "NA" : yValss}</td></tr>`;
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
		$("#table-9-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue) == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
		//direct and indirect separate tables commented
		// $("#table-9-tbody-dir").html(
		// 	indexFilter.pi2020FilterData.countries.map((e) => {
		// 		let yVals = chartData_direct.map((f) => f[e.country_name]);
		// 		if (!yVals.every((e) => e == 0)) {
		// 			let yValsHtml = chartData_direct.map(
		// 				(f) => `<td>${numberWithCommas(f[e.country_name].toFixed(2))}</td>`
		// 			);
		// 			return `<tr><td>${e.country_name}</td>${yValsHtml}</tr>`;
		// 		}
		// 	})
		// );
		// $("#table-9-tbody-indir").html(
		// 	indexFilter.pi2020FilterData.countries.map((e) => {
		// 		let yVals = chartData_indirect.map((f) => f[e.country_name]);
		// 		if (!yVals.every((e) => e == 0)) {
		// 			let yValsHtml = chartData_indirect.map(
		// 				(f) => `<td>${numberWithCommas(f[e.country_name].toFixed(2))}</td>`
		// 			);
		// 			return `<tr><td>${e.country_name}</td>${yValsHtml}</tr>`;
		// 		}
		// 	})
		// );
	}
	//contries year wise comparison end

	/*
  Html action for sankey radio buttons
  */
	getHTMLActionForSankey() {
		$('[name="sankey-sdg-radio"]').on("change", (env) => {
			this.selectedSankyType = env.target.value;
			this.generateWaterShedSankeyGraph();
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
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
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
			html2canvas(document.getElementById("graph-1")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-1")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `watershed-area.jpeg`);
			});
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "watershed-area.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-01").on("click", function () {
			$("#table-01-main").table2csv({
				file_name: "watershed-area.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Map-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Map.svg");
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
			html2canvas(document.getElementById("graph-2")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-2")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `watershed-country.jpeg`);
			});
		});
		$("#dwn-csv-2").on("click", function () {
			$("#table-2-main").table2csv({
				file_name: "watershed-country.csv",
				header_body_space: 0,
			});
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					.attr("download", `watershed-crp.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "watershed-crp.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
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
					.attr("download", `watershed-rp.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "watershed-rp.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
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
					.attr("download", `watershed-publication.jpeg`);
			});
		});
		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "watershed-publication.csv",
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
					.attr("download", `watershed-year.jpeg`);
			});
		});
		$("#dwn-csv-7").on("click", function () {
			$("#table-7-main").table2csv({
				file_name: "watershed-year.csv",
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
					.attr("download", `watershed-year.jpeg`);
			});
		});
		$("#dwn-csv-8").on("click", function () {
			$("#table-8-main").table2csv({
				file_name: "watershed-year.csv",
				header_body_space: 0,
			});
		});

		//graph-9
		const graphTab9 = $("#graph-btn-9");
		const tableTab9 = $("#table-btn-9");
		const downloadTab9 = $("#download-btn-9>img");

		const graph9 = $("#graph-9");
		const table9 = $("#table-9");

		graphTab9.on("click", () => {
			graphTab9.addClass("active");
			tableTab9.removeClass("active");
			graph9.show();
			table9.hide();
			graphTab9
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab9
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab9.on("click", () => {
			tableTab9.addClass("active");
			graphTab9.removeClass("active");
			table9.show();
			graph9.hide();
			graphTab9
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab9
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab9.on("click", () => {
			if (
				downloadTab9.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab9.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab9.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab9.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-9")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-9")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `watershed-contry-year.jpeg`);
			});
		});
		$("#dwn-csv-9").on("click", function () {
			$("#table-9-main").table2csv({
				file_name: "watershed-contry-year.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
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
					.attr("download", `watershed-contry-year.jpeg`);
			});
		});
		$("#dwn-csv-10").on("click", function () {
			$("#table-10-main").table2csv({
				file_name: "watershed-contry-year.csv",
				header_body_space: 0,
			});
		});
	}
}
