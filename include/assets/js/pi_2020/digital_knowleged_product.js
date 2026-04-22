var script = document.getElementById("dkpJS"),
	baseURL = script.getAttribute("data-baseurl");

class DigitalInitatives {
	constructor() {
		this.selectedDiSdgTab = 1;
		this.selectedFarmersTab = 1;
	}

	init() {
		this.getDigitalInitiativesData();
	}

	getDigitalInitiativesData() {
		const request = indexFilter.getFilteredData();
		request.purpose = "get_digital_initiative";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL +
				"/include/assets/js/pi_2020/tabs/digital initiatives_tab.html",
				true
			),
		];
		// const promises = [post("pi_2020", {"purpose": "get_digital_initiative"}), get("./tabs/digital initiatives_tab.html", true)];
		Promise.all(promises)
			.then((response) => {
				if (response?.length) {
					this.digitalInitativesData = response[0];
					const resHtml = response[1].replaceAll(
						'src="img/',
						`src="${baseURL}include/assets/img/pi_2020/`
					);
					$(".mpr-tab-contend").html(resHtml);

					this.arrangeData();

					this.summaryCounts(this.digitalInitativesData.tdkpis);
					this.graphCRPWiseFarmers(
						this.digitalInitativesData.tdkpis,
						this.digitalInitativesData.tdkpi_crps
					);
					this.graphRPWiseFarmers(this.digitalInitativesData.tdkpis);
					this.graphScientificPublications(this.digitalInitativesData.tdkpis);

					this.getFarmersChart();
					this.getHTMLactionForFarmersTab();
					this.getFarmersCharts();
					this.getHTMLactionForFarmersTabs();

					this.graphGeoscopeFarmers(this.digitalInitativesData.tdkpis);

					this.getDiSdgChart();
					this.getHTMLactionForSDGtab();
					// this.graphCountryWiseFarmers(
					// 	this.digitalInitativesData.tdkpis,
					// 	this.digitalInitativesData.tdkpi_countries
					// );

					//this.graphYearwiseFarmers(this.digitalInitativesData.tdkpis);
					this.graphCRPYearwiseFarmers(
						this.digitalInitativesData.tdkpis,
						this.digitalInitativesData.tdkpi_crps
					);
					this.graphCountryYearwiseFarmers(
						this.digitalInitativesData.tdkpis,
						this.digitalInitativesData.tdkpi_countries
					);
					this.htmlToggle();
				}
			})
			.catch((err) => console.log(err));
	}

	// summaryCounts(events) {
	// 	let totalEvents = events.length ? events.length : 0;
	// 	let farmersReached = events.length
	// 		? numberWithCommas(
	// 				events
	// 					.map((e) => parseInt(e.farmers_reached))
	// 					.reduce((a, b) => a + b, 0)
	// 		  )
	// 		: 0;
	// 	$("#di-event-count").html(totalEvents);
	// 	$("#di-farmer-count").html(farmersReached);
	// 	$("#table-0-main").html(
	// 		`<tbody><tr><td>Total Digital Initiatives</td><td>${totalEvents}</td></tr><tr><td>Farmers Reached</td><td>${farmersReached}</td></tr></tbody>`
	// 	);
	// }
	summaryCounts(events) {
		let totalEvents = events.length ? events.length : 0;

		if (totalEvents == 0) {
			// window.alert('Data not available for the selected year, Please reselect your options.');
			// location.reload();

			$("#alert").html(`<div class="modal" id="myModal">
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
		let farmersReached = events.length
			? numberWithCommas(
				events
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0)
			)
			: 0;

		const dataIds = this.digitalInitativesData.tdkpis.map(d => d.data_id);
		let conutriesCount = new Set(this.digitalInitativesData.tdkpi_countries.filter(c => dataIds.includes(c.data_id)).map(d => d.country_id)).size
		// let conutriesCount = [
		// 	...new Set(
		// 		this.digitalInitativesData.tdkpi_countries.map((d) => d.country_id)
		// 	),
		// ].length;



		$("#di-event-count").html(totalEvents == 0 ? "NA" : totalEvents);
		$("#di-farmer-count").html(farmersReached == 0 ? "NA" : farmersReached);
		$("#di-countries-count").html(conutriesCount == 0 ? "NA" : conutriesCount);
		$("#table-0-main").html(
			`<tbody><tr><td>Total Digital Initiatives</td><td>${totalEvents == 0 ? "NA" : totalEvents}</td></tr><tr><td>Farmers Reached</td><td>${farmersReached == 0 ? "NA" : farmersReached}</td></tr><tr><td>Number of countries</td><td>${conutriesCount == 0 ? "NA" : conutriesCount}</td></tr></tbody>`
		);

		let diTableCount = this.digitalInitativesData.tdkpis;
		// $('#ss-dir-list').html(
		// diTableCount.map(p => {
		// let directArea = this.tsms.filter(e => e.project_name == p).map(e => parseInt(e.direct_area || 0)).reduce((a, b) => a+b, 0)
		// return `<tr><td>${p}</td><td>${directArea}</td></tr>`;
		// })
		// )


		$("#digital-initiative-tbl").html(
			diTableCount.map(
				(e) =>
					`<tr><td>${e.digital_agriculture_initiative}</td><td>${e.farmers_reached == 0 ? "NA" : numberWithCommas(e.farmers_reached)}</td></tr>`
			)
		);
		let ttcount = diTableCount
			.map((f) => Number(f.farmers_reached))
			.reduce((a, b) => a + b, 0);
		//console.log(ttcount);
		$("#digital-initiative-tblfoot").html(
			`<tr><td>Total</td><td>${numberWithCommas(ttcount) == 0 ? "NA" : numberWithCommas(ttcount)}</td></tr>`
		);
	}

	graphCRPWiseFarmers(events, crps) {
		let crpFarmers = indexFilter.pi2020FilterData.crps
			.map((crpElement) => {
				let crpDataRecords = crps
					.filter((e) => e.crp_id == crpElement.crp_id)
					.map((e) => e.data_id);
				let eventFarmers = events
					.filter((e) => crpDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return {
					crp_name: crpElement.crp_name,
					farmers_reached: eventFarmers,
				};
			})
			.filter((d) => d.farmers_reached > 0);
		this.verticalBarChart(
			crpFarmers,
			"crp_name",
			"farmers_reached",
			"mpr-mapCrpwisefarmerreached"
		);
		$("#table-4-tbody").html(
			crpFarmers.map(
				(e) =>
					`<tr><td>${e.crp_name}</td><td>${numberWithCommas(e.farmers_reached) == 0 ? "NA" : numberWithCommas(e.farmers_reached)}</td></tr>`
			)
		);

		let tfData = crpFarmers.map((e) => e.farmers_reached).reduce((a, b) => a + b, 0)
		$("#table-4-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphRPWiseFarmers(events) {
		let rpFarmers = indexFilter.pi2020FilterData.reasearchprograms
			.map((rpElement) => {
				let eventFarmers = events
					.filter((e) => e.rp_id == rpElement.rp_id)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return {
					researchprogram: rpElement.rp_name,
					farmers_reached: eventFarmers,
				};
			})
			.filter((d) => d.farmers_reached > 0);
		this.verticalBarChart(
			rpFarmers,
			"researchprogram",
			"farmers_reached",
			"mpr-mapResearchprogramwisefarmerreached"
		);
		$("#table-5-tbody").html(
			rpFarmers.map(
				(e) =>
					`<tr><td>${e.researchprogram}</td><td>${numberWithCommas(e.farmers_reached) == 0 ? "NA" : numberWithCommas(e.farmers_reached)}</td></tr>`
			)
		);
		let tfData = rpFarmers.map((e) => e.farmers_reached).reduce((a, b) => a + b, 0);
		$("#table-5-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphScientificPublications(events) {
		let spEvents = indexFilter.pi2020FilterData.scientific_publications.map(
			(spElement) => {
				let spEventTypes = events.filter(
					(e) => e.scientific_publications == spElement.sp_id
				).length;
				return {
					name: spElement.scientific_publications,
					y: spEventTypes,
					percent: ((spEventTypes * 100) / events.length).toFixed(2),
				};
			}
		);
		// console.log(spEvents);
		this.pieChart(spEvents, "mpr-pieChartType4", "Publication", [
			"#d79494",
			"#7cb5ec",
			"#ffce56",
		]);
		$("#table-7-tbody").html(
			spEvents.map((e) => `<tr><td>${e.name}</td><td>${e.y == 0 ? "NA" : numberWithCommas(e.y)}</td></tr>`)
		);

		let tfData = spEvents.map((e) => e.y).reduce((a, b) => a + b, 0)
		$("#table-7-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphCRPYearwiseFarmers(events, crps) {
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				result[c.crp_name] = events
					.filter(
						(e) => crpDataRecords.includes(e.data_id) && e.year_id == y.year_id
					)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		}).filter(d => d[" CGIAR Research Program on Water, Land and Ecosystems "] |
			d["Not mapped"] |
			d["CGIAR Research Program on Grain Legumes and Dryland Cereals"] |
			d["CGIAR Research Program on Livestock"] |
			d["CGIAR Research Program on Climate Change, Agriculture and Food Security "] |
			d["CGIAR Research Program on Agriculture for Nutrition and Health"] |
			d["CGIAR Research Program on Policies, Institutions, and Markets "] |
			d["Dryland Cereals (Ph 1)"] |
			d["Grain Legumes (Ph 1)"] |
			d["Dryland Systems (Ph 1)"])

		// Highcharts.chart("di-crp-year-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of farmers" },
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

		$("#di-crp-year-graph").css("height", serz.length * 4 + "em");

		Highcharts.chart("di-crp-year-graph", {
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
					text: "Number of lines released",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
				formatter: function () {
					// debugger
					if (this?.points?.length > 0) {
						debugger
						return chartData.filter(d => d.year == this.x).map(d => {
							const result = [];
							Object.keys(d).filter(k => d[k] > 0).forEach(k => result.push(`<div class="d-flex">
												<div>${k.toUpperCase()} : </div>
												<div><b>${d[k]}</b></div>
												</div>`))
							return result.join("<br>")
						}).join("<br>")
						// return this.points.filter(d=> d.y).map(d=> d.y).join('<br>')

					}
					return ""
				}
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
			series: serz
		});

		$("#table-9-thead-row").html(
			`<th>CRP</th>` +
			chartData.map((e) => `<th>${e.year}</th>`) +
			`<th>Total</th>`
		);
		$("#table-9-tbody").html(
			indexFilter.pi2020FilterData.crps.map((e) => {
				let yValsHtml = chartData.map(
					(f) => `<td>${numberWithCommas(f[e.crp_name]) == 0 ? "NA" : numberWithCommas(f[e.crp_name])}</td>`
				);
				let yVals = chartData.map((f) => f[e.crp_name]).reduce((a, b) => a + b, 0);

				return `<tr><td>${e.crp_name
					}</td>${yValsHtml}<td style="font-weight: 600;">${numberWithCommas(yVals) == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});
		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b, 0);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-9-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue) == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	graphCountryYearwiseFarmers(events, eventCountries) {
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				let countryDataRecords = eventCountries
					.filter((e) => e.country_id == c.country_id)
					.map((e) => e.data_id);
				result[c.country_name] = events
					.filter(
						(e) =>
							e.year_id == y.year_id && countryDataRecords.includes(e.data_id)
					)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		}).filter(d => {
			const allCountries = Object.keys(d).filter(e => e != 'year');
			return allCountries.some(e => d[e]);
		});

		// Highcharts.chart("di-country-year-graph", {
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
		// 		title: { text: "Number of farmers" },
		// 	},
		// 	credits: { enabled: false },
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)<br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
		// 			dataLabels: { enabled: true, style: { textOutline: false } },
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

		const cat = chartData.map((e) => e.year);

		const serz = indexFilter.pi2020FilterData.countries
			.map((c) => {
				let result = {
					name: c.country_name,
					data: chartData
						// .filter((e) => e[c.country_name] > 0)
						.map((e) =>
							e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : ""
						),
				};
				return result;
			})
			.filter((e) => e.data.some((d) => d));

		// console.log(chartData);
		// console.log(cat);
		// console.log(serz);


		$("#di-country-year-graph").css("height", serz.length * 5 + "px");

		Highcharts.chart("di-country-year-graph", {
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
					text: "Number of farmers",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
				formatter: function () {
					// debugger
					if (this?.points?.length > 0) {
						debugger
						return chartData.filter(d => d.year == this.x).map(d => {
							const result = [];
							Object.keys(d).filter(k => d[k] > 0).forEach(k => result.push(`<div class="d-flex">
							<div>${k.toUpperCase()} : </div>
							<div><b>${d[k]}</b></div>
							</div>`))
							return result.join("<br>")
						}).join("<br>")
						// return this.points.filter(d=> d.y).map(d=> d.y).join('<br>')

					}
					return ""
				}

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
			// series: indexFilter.pi2020FilterData.countries
			// 	.map((c) => {
			// 		let result = {
			// 			name: c.country_name,
			// 			data: chartData
			// 				// .filter((e) => e[c.country_name] > 0)
			// 				.map((e) => e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : null),
			// 		};
			// 		return result;
			// 	}).filter(d=> (d.data).some(d=>d))

			series: serz,
			// .filter(
			// 	(e) =>
			// 		!Object.keys(e.data)
			// 			.map((f) => e[f])
			// 			.every((f) => f == 0)
			// ),
		});

		$("#table-10-thead-row").html(
			`<th>Countries</th>` +
			chartData.map((e) => `<th>${e.year}</th>`) +
			`<th>Total</th>`
		);
		$("#table-10-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${numberWithCommas(f[e.country_name]) == 0 ? "NA" : numberWithCommas(f[e.country_name])}</td>`
					);
					let yVals = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b, 0);

					return `<tr><td>${e.country_name}</td>${yValsHtml}<td style="font-weight: 600;">${numberWithCommas(yVals) == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
				}
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});
		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b, 0);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-10-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue) == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	graphICTWiseFarmers(events, icts) {
		$("#di-ict-farmer-graph").show();
		let ictFarmers = indexFilter.pi2020FilterData.ict_medias.map(
			(ictElement) => {
				let ictDataRecords = icts
					.filter((e) => e.ict_medium_id == ictElement.ICT_media_id)
					.map((e) => e.data_id);
				let eventFarmers = events
					.filter((e) => ictDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return {
					ict_name: ictElement.ICT_media,
					farmers_reached: eventFarmers,
				};
			}
		);
		// console.log(ictFarmers);
		this.verticalBarChart(
			ictFarmers,
			"ict_name",
			"farmers_reached",
			"di-ict-farmer-graph",
			"ICT medium",
			"#d79494"
		);
		$("#table-3-thead-row").html(`<th>ICT Medium</th><th>Farmers Reached</th>`);
		$("#table-3-tbody").html(
			ictFarmers.map(
				(e) =>
					`<tr><td>${e.ict_name}</td><td>${numberWithCommas(e.farmers_reached) == 0 ? "NA" : numberWithCommas(e.farmers_reached)}</td></tr>`
			)
		);

		let tfData = ictFarmers.map((e) => e.farmers_reached).reduce((a, b) => a + b, 0);

		$("#table-3-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);


	}

	graphInterventionFarmers(events, ints) {
		$("#di-int-farmer-graph").show();
		let intFarmers = indexFilter.pi2020FilterData.intervention_types.map(
			(intElement) => {
				let intDataRecords = ints
					.filter((e) => e.intervention_type == intElement.intervention_type_id)
					.map((e) => e.data_id);
				let eventFarmers = events
					.filter((e) => intDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return {
					int_name: intElement.intervention_type,
					farmers_reached: eventFarmers,
				};
			}
		);
		this.verticalBarChart(
			intFarmers,
			"int_name",
			"farmers_reached",
			"di-int-farmer-graph",
			"Intervention type",
			"#7cb5ec"
		);
		$("#table-3-thead-row").html(
			`<th>Intervention Type</th><th>Farmers Reached</th>`
		);
		$("#table-3-tbody").html(
			intFarmers.map(
				(e) =>
					`<tr><td>${e.int_name}</td><td>${numberWithCommas(e.farmers_reached) == 0 ? "NA" : numberWithCommas(e.farmers_reached)}</td></tr>`
			)
		);
	}

	graphInnovationWiseFarmers(events) {
		$("#di-inn-farmer-graph").show();
		let innFarmers = indexFilter.pi2020FilterData.innovation_types.map(
			(inn) => {
				let farmerCounts = events
					.filter((e) => e.innovation_type == inn.innovation_type_id)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return { inn_name: inn.innovation_type, farmers_reached: farmerCounts };
			}
		);
		this.verticalBarChart(
			innFarmers,
			"inn_name",
			"farmers_reached",
			"di-inn-farmer-graph",
			"Innovation type",
			"#FFCE56"
		);
		$("#table-3-thead-row").html(
			`<th>Innovation Type</th><th>Farmers Reached</th>`
		);
		$("#table-3-tbody").html(
			innFarmers.map(
				(e) =>
					`<tr><td>${e.inn_name}</td><td>${numberWithCommas(e.farmers_reached) == 0 ? "NA" : numberWithCommas(e.farmers_reached)}</td></tr>`
			)
		);
	}

	getFarmersChart() {
		// $("#di-ict-farmer-graph").hide();
		// $("#di-int-farmer-graph").hide();
		// $("#di-inn-farmer-graph").hide();
		// if (this.selectedFarmersTab == 1) {
		this.graphICTWiseFarmers(
			this.digitalInitativesData.tdkpis,
			this.digitalInitativesData.tdkpi_icts
		);
		// } else if (this.selectedFarmersTab == 2) {
		this.graphInterventionFarmers(
			this.digitalInitativesData.tdkpis,
			this.digitalInitativesData.tdkpi_interventions
		);
		// } else if (this.selectedFarmersTab == 3) {
		this.graphInnovationWiseFarmers(this.digitalInitativesData.tdkpis);
		// }
	}
	//year wise graph for stacked
	getFarmersCharts() {
		$("#di-yearwise-graph0").hide();
		$("#di-yearwise-graph1").hide();
		$("#di-yearwise-graph2").hide();
		if (this.selectedFarmersTab == 1) {
			this.graphYearwiseFarmers1(
				this.digitalInitativesData.tdkpis,
				this.digitalInitativesData.tdkpi_icts
			);
		} else if (this.selectedFarmersTab == 2) {
			this.graphYearwiseFarmers2(
				this.digitalInitativesData.tdkpis,
				this.digitalInitativesData.tdkpi_interventions
			);
		} else if (this.selectedFarmersTab == 3) {
			this.graphYearwiseFarmers3(this.digitalInitativesData.tdkpis);
		}
	}
	graphYearwiseFarmers1(events, icts) {
		$("#di-yearwise-graph0").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.ict_medias.forEach((ictElement) => {
				let ictDataRecords = icts
					.filter((e) => e.ict_medium_id == ictElement.ICT_media_id)
					.map((e) => e.data_id);
				result[ictElement.ICT_media] = events
					.filter(
						(e) => ictDataRecords.includes(e.data_id) && e.year_id == y.year_id
					)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		}).filter(d => d["SMS"] |
			d["Mobile App"] |
			d["Web Platform"] |
			d["Webinars"] |
			d["IVRS"] |
			d["Whatsapp"])




		// console.log(chartData);

		// Highcharts.chart("di-yearwise-graph0", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of farmers" },
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
		// 		},
		// 	},
		// 	series: indexFilter.pi2020FilterData.ict_medias.map((e) => {
		// 		return {
		// 			name: e.ICT_media,
		// 			data: chartData.map((f) => f[e.ICT_media]),
		// 		};
		// 	}),
		// });

		Highcharts.chart("di-yearwise-graph0", {
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
					text: "Number of farmers",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
				formatter: function () {
					// debugger
					if (this?.points?.length > 0) {
						debugger
						return chartData.filter(d => d.year == this.x).map(d => {
							const result = [];
							Object.keys(d).filter(k => d[k] > 0).forEach(k => result.push(`<div class="d-flex">
												<div>${k.toUpperCase()} : </div>
												<div><b>${d[k]}</b></div>
												</div>`))
							return result.join("<br>")
						}).join("<br>")
						// return this.points.filter(d=> d.y).map(d=> d.y).join('<br>')

					}
					return ""
				}
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
			// Number(e[c.country_name].toFixed(2)) : null)
			series: indexFilter.pi2020FilterData.ict_medias.map((e) => {
				return {
					name: e.ICT_media,
					data: chartData.map((f) =>
						f[e.ICT_media] ? Number(f[e.ICT_media].toFixed(2)) : 0
					),
				};
			}),
		});

		$("#table-11-thead-row").html(
			`<th>ICT Name</th>` + chartData.map((e) => `<th>${e.year}</th>`) + `<th>Total</th>`
		);
		$("#table-11-tbody").html(
			indexFilter.pi2020FilterData.ict_medias.map((e) => {
				let yVals = chartData.map(
					(f) => `<td>${numberWithCommas(f[e.ICT_media]) == 0 ? "NA" : numberWithCommas(f[e.ICT_media])}</td>`
				);
				let yValsTotal = chartData.map(d => d[e.ICT_media]).reduce((a, b) => a + b, 0)
				return `<tr><td>${e.ICT_media}</td>${yVals}<td style="font-weight: 600;">${yValsTotal == 0 ? "NA" : numberWithCommas(yValsTotal)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let tfData = totals.map((e) => e).reduce((a, b) => a + b, 0)
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-11-tfoot").html(`<tr><td>Total</td>${totalsHtml}<td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`);
	}

	graphYearwiseFarmers2(events, ints) {
		$("#di-yearwise-graph1").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.intervention_types.forEach((intElement) => {
				let intDataRecords = ints
					.filter((e) => e.intervention_type == intElement.intervention_type_id)
					.map((e) => e.data_id);
				result[intElement.intervention_type] = events
					.filter(
						(e) => intDataRecords.includes(e.data_id) && e.year_id == y.year_id
					)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		}).filter(d => d["Advisory"] |
			d["Decision support"] |
			d["Research"] |
			d["Information access"] |
			d["Educational"] |
			d["Transactional"])


		// Highcharts.chart("di-yearwise-graph1", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of farmers" },
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
		// 		},
		// 	},
		// 	series: indexFilter.pi2020FilterData.intervention_types.map((e) => {
		// 		return {
		// 			name: e.intervention_type,
		// 			data: chartData.map((f) => f[e.intervention_type]),
		// 		};
		// 	}),
		// });

		Highcharts.chart("di-yearwise-graph1", {
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
					text: "Number of farmers",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
				formatter: function () {
					// debugger
					if (this?.points?.length > 0) {
						debugger
						return chartData.filter(d => d.year == this.x).map(d => {
							const result = [];
							Object.keys(d).filter(k => d[k] > 0).forEach(k => result.push(`<div class="d-flex">
												<div>${k.toUpperCase()} : </div>
												<div><b>${d[k]}</b></div>
												</div>`))
							return result.join("<br>")
						}).join("<br>")
						// return this.points.filter(d=> d.y).map(d=> d.y).join('<br>')

					}
					return ""
				}
			},
			plotOptions: {
				area: {
					fillOpacity: 0.5,
					marker: {
						fillOpacity: 0.5,
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
			series: indexFilter.pi2020FilterData.intervention_types.map((e) => {
				return {
					name: e.intervention_type,
					data: chartData.map((f) =>
						f[e.intervention_type]
							? Number(f[e.intervention_type].toFixed(2))
							: 0
					),
				};
			}),
		});

		$("#table-11-thead-row").html(
			`<th>ICT Name</th>` + chartData.map((e) => `<th>${e.year}</th>`) + `<th>Total</th>`
		);

		$("#table-11-tbody").html(
			indexFilter.pi2020FilterData.intervention_types.map((e) => {
				let yVals = chartData.map(
					(f) => `<td>${f[e.intervention_type] == 0 ? "NA" : numberWithCommas(f[e.intervention_type])}</td>`
				);
				let yValsTotal = chartData.map((f) => f[e.intervention_type]).reduce((a, b) => a + b, 0);
				return `<tr><td>${e.intervention_type}</td>${yVals}<td style="font-weight: 600;">${Number(yValsTotal) == 0 ? "NA" : numberWithCommas(yValsTotal)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e)}</td>`);
		let tfData = totals.map((e) => e).reduce((a, b) => a + b, 0)
		$("#table-11-tfoot").html(`<tr><td>Total</td>${totalsHtml}<td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`);
	}

	graphYearwiseFarmers3(events) {
		$("#di-yearwise-graph2").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.innovation_types.forEach((inn) => {
				result[inn.innovation_type] = events
					.filter(
						(e) =>
							e.innovation_type == inn.innovation_type_id &&
							e.year_id == y.year_id
					)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		}).filter(d => d["Production systems and Management practices"] |
			d["Research and Communication"] |
			d["Genetic (variety and breeds)"] |
			d["Social Science"] |
			d["Methodologies and Tools"] |
			d["Other"])




		// Highcharts.chart("di-yearwise-graph2", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of farmers" },
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
		// 		},
		// 	},
		// 	series: indexFilter.pi2020FilterData.innovation_types.map((e) => {
		// 		return {
		// 			name: e.innovation_type,
		// 			data: chartData.map((f) =>
		// 				f[e.innovation_type]
		// 					? Number(f[e.innovation_type].toFixed(2))
		// 					: 0
		// 			),
		// 		};
		// 	}),
		// });

		Highcharts.chart("di-yearwise-graph2", {
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
					text: "Number of farmers",
				},
			},
			tooltip: {
				split: true,
				valueSuffix: null,
				formatter: function () {
					// debugger
					if (this?.points?.length > 0) {
						debugger
						return chartData.filter(d => d.year == this.x).map(d => {
							const result = [];
							Object.keys(d).filter(k => d[k] > 0).forEach(k => result.push(`<div class="d-flex">
												<div>${k.toUpperCase()} : </div>
												<div><b>${d[k]}</b></div>
												</div>`))
							return result.join("<br>")
						}).join("<br>")
						// return this.points.filter(d=> d.y).map(d=> d.y).join('<br>')

					}
					return ""
				}
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
			series: indexFilter.pi2020FilterData.innovation_types.map((e) => {
				return {
					name: e.innovation_type,
					data: chartData.map((f) =>
						f[e.innovation_type]
							? Number(f[e.innovation_type].toFixed(2))
							: 0
					),
				};
			}),
		});

		$("#table-11-thead-row").html(
			`<th>ICT Name</th>` + chartData.map((e) => `<th>${e.year}</th>`) + `<th>Total</th>`
		);
		$("#table-11-tbody").html(
			indexFilter.pi2020FilterData.innovation_types.map((e) => {
				let yVals = chartData.map(
					(f) => `<td>${f[e.innovation_type] == 0 ? "NA" : numberWithCommas(f[e.innovation_type])}</td>`
				);
				let tdTotal = chartData.map(d => d[e.innovation_type]).reduce((a, b) => a + b, 0)
				return `<tr><td>${e.innovation_type}</td>${yVals}<td style="font-weight: 600;">${tdTotal == 0 ? "NA" : numberWithCommas(tdTotal)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});
		let tdTotal = totals.map(e => e).reduce((a, b) => a + b, 0)
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e)}</td>`);
		$("#table-11-tfoot").html(`<tr><td>Total</td>${totalsHtml}<td>${tdTotal == 0 ? "NA" : numberWithCommas(tdTotal)}</td></tr>`);
	}

	//   graphYearwiseFarmers(events){
	// 	let chartData = indexFilter.dataViewYears.map(e => {
	// 		let result = {"year": e.year}
	// 		result.farmers_reached = events.filter(d => d.year_id ==e.year_id).map(d => parseInt(d.farmers_reached)).reduce((a, b) => a+b, 0);
	// 		return result;
	// 	}).filter(d => d.farmers_reached > 0);
	// 	chartData.sort((a, b) => parseInt(a.year) > parseInt(b.year) ? 0 : -1);
	// 	this.verticalBarChart(chartData, "year", "farmers_reached", "di-yearwise-graph");
	// 	$("#table-8-tbody").html(chartData.map(e => `<tr><td>${e.year}</td><td>${numberWithCommas(e.farmers_reached)}</td></tr>`));
	//  }

	graphGeoscopeFarmers(events) {
		let totalFarmers = events
			.map((e) => parseInt(e.farmers_reached))
			.reduce((a, b) => a + b, 0);
		let geoFarmers = indexFilter.pi2020FilterData.geograhic_scopes.map(
			(geoElement) => {
				let geoSum = events
					.filter((e) => e.geo_grahic_scope == geoElement.geograhic_scope_id)
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return {
					name: geoElement.geograhic_scope,
					y: geoSum,
					percent: ((geoSum * 100) / totalFarmers).toFixed(2),
				};
			}
		);
		// console.log(geoFarmers);
		this.pieChart(geoFarmers, "mpr-pieChartType3", "Geographic Scope", [
			"#D79494",
			"#C09CAA",
			"#ffce56",
			"#93ADD6",
			"#7CB5EC",
		]);
		$("#table-6-tbody").html(
			geoFarmers.map(
				(e) => `<tr><td>${e.name}</td><td>${numberWithCommas(e.y) == 0 ? "NA" : numberWithCommas(e.y)}</td></tr>`
			)
		);

		let tfData = geoFarmers.map((e) => e.y).reduce((a, b) => a + b, 0)
		$("#table-6-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	arrangeData() {
		this.tdkpis = clone(this.digitalInitativesData.tdkpis);
		this.tdkpis.forEach((d) => {
			d.icts = clone(this.digitalInitativesData.tdkpi_icts).filter(
				(f) => f.data_id == d.data_id
			);
			d.sdgs = clone(this.digitalInitativesData.tdkpi_sdgs).filter(
				(f) => f.data_id == d.data_id
			);
			d.ints = clone(this.digitalInitativesData.tdkpi_interventions).filter(
				(f) => f.data_id == d.data_id
			);
		});
	}

	graphIctSdg() {
		$("#di-ict-sdg-graph").show();
		let chartData = indexFilter.pi2020FilterData.ict_medias
			.map((ict) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tdkpis.filter(
						(d) =>
							d.icts.some((e) => e.ict_medium_id == ict.ICT_media_id) &&
							d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return {
						from: ict.ICT_media,
						to: sdg.sdg_name,
						value: val,
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		$("#di-ict-sdg-graph").css("height", "600px");
		this.sankeyChart(chartData, "di-ict-sdg-graph");
	}

	graphInterventionSdg() {
		$("#di-int-sdg-graph").show();
		let chartData = indexFilter.pi2020FilterData.intervention_types
			.map((inn) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tdkpis.filter(
						(d) =>
							d.ints.some(
								(e) => e.intervention_type == inn.intervention_type_id
							) && d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return {
						from: inn.intervention_type,
						to: sdg.sdg_name,
						value: val,
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		$("#di-int-sdg-graph").css("height", "600px");
		this.sankeyChart(chartData, "di-int-sdg-graph");
	}

	getDiSdgChart() {
		$("#di-ict-sdg-graph").hide();
		$("#di-int-sdg-graph").hide();
		if (this.selectedDiSdgTab == 1) {
			this.graphIctSdg();
		} else if (this.selectedDiSdgTab == 2) {
			this.graphInterventionSdg();
		}
	}

	verticalBarChart(
		dataObj,
		categories,
		categoryValues,
		container,
		subTitle = "",
		color = "#7cb5ec"
	) {
		Highcharts.chart(container, {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: subTitle, verticalAlign: "bottom" },
			colors: [color],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: dataObj.map((e) => e[categories]),
				title: { text: null },
			},
			yAxis: {
				title: { text: "Number of Farmers" },
				labels: { overflow: "justify" },
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y}</b>" },
			plotOptions: {
				series: {
					dataLabels: { enabled: true, style: { textOutline: false } },
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
					},
				},
			},
			series: [
				{
					maxPointWidth: 30,
					name: "",
					data: dataObj.map((e) => e[categoryValues]),
				},
			],
		});
	}

	pieChart(dataObj, container, seriesName, colorList) {
		const chartData = dataObj.filter(d => d.percent != 0)
		Highcharts.chart(container, {
			chart: { type: "pie" },
			title: { text: null },
			subtitle: { text: null },
			credits: { enabled: false },
			colors: colorList,
			plotOptions: {
				pie: {
					allowPointSelect: false,
					dataLabels: {
						enabled: true,
						format: "{point.name}</span>: <b>{point.y} ({point.percent} %)</b>",
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
					data: chartData,
				},
			],
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
			chart.exporting.filePrefix = "di-ict-sdg-graph";
		});
	}

	// toggle graph panels
	getHTMLactionForSDGtab() {
		$('input[name="di-sdg-tab"]').on("change", () => {
			this.selectedDiSdgTab = $('input[name="di-sdg-tab"]:checked').val();
			this.getDiSdgChart();
		});
	}

	getHTMLactionForFarmersTab() {
		$('input[name="di-farmer"]').on("change", () => {
			this.selectedFarmersTab = $('input[name="di-farmer"]:checked').val();
			this.getFarmersChart();
		});
	}

	getHTMLactionForFarmersTabs() {
		$('input[name="di-farmers"]').on("change", () => {
			this.selectedFarmersTab = $('input[name="di-farmers"]:checked').val();
			this.getFarmersCharts();
		});
	}

	graphCountryWiseFarmers(events, eventCountries) {
		// let chartData = indexFilter.pi2020FilterData.countries.map(country => {
		//   let countryDataRecords = eventCountries.filter(e => e.country_id == country.country_id).map(e => e.data_id);
		//   let farmerCounts = events.filter(e => countryDataRecords.includes(e.data_id)).map(e => parseInt(e.farmers_reached)).reduce((a, b) => a+b, 0);
		//   return {'id': country.country_id, 'name': country.country_name, 'countryCode': country.country_code, 'z': farmerCounts};
		// }).filter(d => d.z > 0);
		// this.worldMap(chartData, "di-country-map", "countryCode", "", "Farmers Reached")

		let chartData = indexFilter.pi2020FilterData.countries
			.map((data) => {
				let result = { id: data.country_code, name: data.country_name };
				let countryDataRecords = eventCountries
					.filter((e) => e.country_id == data.country_id)
					.map((e) => e.data_id);
				result.value = events
					.filter((e) => countryDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.farmers_reached))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.value > 0);

		// am4core.ready(function () {
		// 	am4core.useTheme(am4themes_animated);
		// 	var chart = am4core.create("di-country-map", am4maps.MapChart);
		// 	chartData.forEach((d, i) => (d.color = chart.colors.getIndex(i)));
		// 	chart.geodata = am4geodata_worldIndiaLow;
		// 	chart.projection = new am4maps.projections.Miller();
		// 	chart.logo.disabled = "true";
		// 	chart.numberFormatter.numberFormat = "#,###.##";

		// 	var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
		// 	polygonSeries.exclude = ["AQ"];
		// 	polygonSeries.useGeodata = true;
		// 	polygonSeries.nonScalingStroke = true;
		// 	polygonSeries.strokeWidth = 0.5;
		// 	polygonSeries.calculateVisualCenter = true;

		// 	var imageSeries = chart.series.push(new am4maps.MapImageSeries());
		// 	imageSeries.data = chartData;
		// 	imageSeries.dataFields.value = "value";

		// 	var imageTemplate = imageSeries.mapImages.template;
		// 	imageTemplate.nonScaling = true;

		// 	// var circle = imageTemplate.createChild(am4core.Circle);
		// 	// circle.fillOpacity = 0.7;
		// 	// circle.propertyFields.fill = "color";
		// 	// circle.tooltipText = "{name}: [bold]{value}[/]";

		// 	var circle = imageTemplate.createChild(am4core.Circle);
		// 	circle.fillOpacity = 0.7;
		// 	// circle.fill = am4core.color("#a791b4");
		// 	circle.verticalCenter = "middle";
		// 	circle.horizontalCenter = "middle";
		// 	circle.propertyFields.fill = "color";
		// 	circle.tooltipText = "{name}: [bold]{value}[/]";

		// 	imageSeries.heatRules.push({
		// 		target: circle,
		// 		property: "radius",
		// 		min: 10,
		// 		max: 40,
		// 		dataField: "value",
		// 	});

		// 	imageTemplate.adapter.add("latitude", function (latitude, target) {
		// 		var polygon = polygonSeries.getPolygonById(
		// 			target.dataItem.dataContext.id
		// 		);
		// 		if (polygon) {
		// 			return polygon.visualLatitude;
		// 		}
		// 		return latitude;
		// 	});

		// 	imageTemplate.adapter.add("longitude", function (longitude, target) {
		// 		var polygon = polygonSeries.getPolygonById(
		// 			target.dataItem.dataContext.id
		// 		);
		// 		if (polygon) {
		// 			return polygon.visualLongitude;
		// 		}
		// 		return longitude;
		// 	});

		// 	chart.maxZoomLevel = 1;

		// 	var label = imageTemplate.createChild(am4core.Label);
		// 	label.text = "{value}";
		// 	// label.horizontalCenter = "middle";
		// 	// label.verticalCenter = "middle";
		// 	label.verticalCenter = "middle";
		// 	label.horizontalCenter = "middle";
		// 	label.nonScaling = true;
		// 	// label.padding(-10, 0, 0, 0);
		// 	label.fontSize = 18;
		// });

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("di-country-map", am4maps.MapChart);
			chartData.forEach((d, i) => (d.color = chart.colors.getIndex(i)));
			chart.geodata = am4geodata_worldIndiaLow;
			chart.projection = new am4maps.projections.Miller();
			chart.logo.disabled = "true";
			chart.numberFormatter.numberFormat = "#,###.##";



			// var home = chart.chartContainer.createChild(am4core.Button);
			// home.label.text = "Home";
			// home.align = "right";
			// home.events.on("hit", function (ev) {
			// 	chart.goHome();
			// });




			// Add zoom control
			// chart.zoomControl = new am4maps.ZoomControl();
			chart.maxZoomLevel = 1;


			var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
			polygonSeries.exclude = ["AQ"];
			polygonSeries.useGeodata = true;
			polygonSeries.nonScalingStroke = true;
			polygonSeries.strokeWidth = 0.5;
			polygonSeries.calculateVisualCenter = true;

			var imageSeries = chart.series.push(new am4maps.MapImageSeries());
			imageSeries.data = chartData;
			imageSeries.dataFields.value = "value";

			var imageTemplate = imageSeries.mapImages.template;
			imageTemplate.nonScaling = true;

			var circle = imageTemplate.createChild(am4core.Circle);
			circle.fillOpacity = 0.7;
			// circle.fill = am4core.color("#a791b4");
			circle.verticalCenter = "middle";
			circle.horizontalCenter = "middle";
			circle.propertyFields.fill = "color";
			circle.tooltipText = "{name}: [bold]{value}[/]";

			var template = imageSeries.mapImages.template;
			template.verticalCenter = "middle";
			template.horizontalCenter = "middle";
			template.propertyFields.latitude = "lat";
			template.propertyFields.longitude = "long";
			template.tooltipText = "{name}:[bold]{value}[/]";

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
			label.verticalCenter = "middle";
			label.horizontalCenter = "middle";
			label.nonScaling = true;
			// label.padding(-10, -10, 0, 0);
			label.fontSize = 12;

		});

		$("#table-1-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.name}</td><td>${numberWithCommas(e.value) == 0 ? "NA" : numberWithCommas(e.value)}</td></tr>`
			)
		);

		let tfData = chartData.map((e) => e.value).reduce((a, b) => a + b, 0);

		$("#table-1-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
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
			html2canvas(document.getElementById("graph-1")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-1")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `di-country.jpeg`);
			});
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "di-country.csv",
				header_body_space: 0,
			});
		});

		$("#dwn-csv-0").on("click", function () {
			$("#table-0-main").table2csv({
				file_name: "di-summary.csv",
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
					.attr("download", `di-farmer.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "di-farmer.csv",
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
					.attr("download", `di-crp.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "di-crp.csv",
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
					.attr("download", `di-rp.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "di-rp.csv",
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
					.attr("download", `di-outreach.jpeg`);
			});
		});
		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "di-outreach.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
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
					.attr("download", `di-publication.jpeg`);
			});
		});
		$("#dwn-csv-7").on("click", function () {
			$("#table-7-main").table2csv({
				file_name: "di-publication.csv",
				header_body_space: 0,
			});
		});

		// graph-8
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
					.attr("download", `di-year.jpeg`);
			});
		});
		$("#dwn-csv-8").on("click", function () {
			$("#table-8-main").table2csv({
				file_name: "di-year.csv",
				header_body_space: 0,
			});
		});

		// graph-9
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
					.attr("download", `di-crp-year.jpeg`);
			});
		});
		$("#dwn-csv-9").on("click", function () {
			$("#table-9-main").table2csv({
				file_name: "di-crp-year.csv",
				header_body_space: 0,
			});
		});

		// graph-10
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
					.attr("download", `di-country-year.jpeg`);
			});
		});
		$("#dwn-csv-10").on("click", function () {
			$("#table-10-main").table2csv({
				file_name: "di-country-year.csv",
				header_body_space: 0,
			});
		});

		// graph-11
		const graphTab11 = $("#graph-btn-11");
		const tableTab11 = $("#table-btn-11");
		const downloadTab11 = $("#download-btn-11>img");

		const graph11 = $("#graph-11");
		const table11 = $("#table-11");

		graphTab11.on("click", () => {
			graphTab11.addClass("active");
			tableTab11.removeClass("active");
			graph11.show();
			table11.hide();
			graphTab11
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab11
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab11.on("click", () => {
			tableTab11.addClass("active");
			graphTab11.removeClass("active");
			table11.show();
			graph11.hide();
			graphTab11
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab11
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab11.on("click", () => {
			if (
				downloadTab11.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab11.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab11.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab11.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-11")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-11")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `di-year.jpeg`);
			});
		});
		$("#dwn-csv-11").on("click", function () {
			$("#table-11-main").table2csv({
				file_name: "di-year.csv",
				header_body_space: 0,
			});
		});
	}
}
