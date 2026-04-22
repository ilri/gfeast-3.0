var script = document.getElementById("cbaJS"),
	baseURL = script.getAttribute("data-baseurl");

class CapacityBuilding {
	constructor() {
		this.selectedCourseTab = 1;
		this.selectedCRPTab = 1;
		this.selectedCRPTrainingTab = 1;
		this.selectedCRPYearTab = 1;
		this.selectedRPTrainingTab = 1;

		this.selectedCountryYearTab = 1;
	}

	init() {
		this.getCapacityBuildingData();
	}

	getCapacityBuildingData() {
		const request = indexFilter.getFilteredData();
		request.purpose = "get_participants_reached";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL +
					"/include/assets/js/pi_2020/tabs/capacity_building_activity_tab.html",
				true
			),
		];
		// const promises = [post("pi_2020", {"purpose": "get_participants_reached"}), get("./tabs/capacity_building_activity_tab.html", true)];
		Promise.all(promises)
			.then((response) => {
				if (response?.length) {
					this.capacityBuildingData = response[0];
					const resHtml = response[1].replaceAll(
						'src="img/',
						`src="${baseURL}include/assets/img/pi_2020/`
					);
					$(".mpr-tab-contend").html(resHtml);

					this.arrangeData();

					this.summaryCounts(this.capacityBuildingData.tprs);

					this.getCourseChart();
					this.getHTMLactionForCourseTab();

					this.graphEvents(this.capacityBuildingData.tprs);
					this.graphParticipants(this.capacityBuildingData.tprs);

					this.graphCountryTrainings(this.capacityBuildingData.tprs);

					this.graphParticipantKeyCategories(
						this.capacityBuildingData.tprs,
						this.capacityBuildingData.tpr_keys
					);

					this.getCRPTrainingChart();
					this.getHTMLactionForCRPTrainingTab();
					this.getCRPYearChart();
					this.getHTMLactionForCRPYearTab();

					this.getCountryYearChart();
					this.getHTMLactionForCountryYearTab();

					this.getRPTrainingChart();
					this.getHTMLactionForRPTrainingTab();

					this.graphEventSDG();

					this.getCRPSDGChart();
					this.getHTMLactionForCRPSDGTab();
					this.graphCountryWiseParticipants(this.capacityBuildingData.tprs);
					this.graphYearwiseParticipants(this.capacityBuildingData.tprs);
					this.htmlToggle();
				}
			})
			.catch((err) => console.log(err));
	}

	summaryCounts(events) {
		$("#cb-event-count").html(events.length == 0 ? "NA" : events.length);
		let cbParticipatesCount = numberWithCommas(events.map((e) => parseInt(e.no_of_male) + parseInt(e.no_of_female)).reduce((a, b) => a + b, 0))
		if(cbParticipatesCount == 0){
			// window.alert('Data not available for the selected year, Please reselect your options.');
			// location.reload();

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
		$("#cb-participant-count").html(cbParticipatesCount == 0 ? "NA" : cbParticipatesCount);
	}

	graphEvents(events) {
		let categoryIndex = indexFilter.pi2020FilterData.course_trainings.map(
			(catElement) => {
				let categoryCount = events.filter(
					(e) => e.course_training_category == catElement.course_id
				).length;
				return {
					name: catElement.course_training_name,
					y: categoryCount,
					percent: ((categoryCount * 100) / events.length).toFixed(2),
					allValues: categoryCount
				};
			}
		).filter(d=> d.percent != 0);
		
		this.verticalBarChart(
			categoryIndex,
			"name",
			"y",
			"mpr-pieChart",
			"Course Training"
		);
	}

	graphParticipants(events) {
		$("#cb-course-participant-graph").show();
		let maleParticipants = events
			.map((e) => parseInt(e.no_of_male))
			.reduce((a, b) => a + b, 0);
		let femaleParticipants = events
			.map((e) => parseInt(e.no_of_female))
			.reduce((a, b) => a + b, 0);
		let genderWiseParticipants = [
			{
				name: "Male",
				y: maleParticipants,
				percent: (
					(maleParticipants * 100) /
					(maleParticipants + femaleParticipants)
				).toFixed(2),
			},
			{
				name: "Female",
				y: femaleParticipants,
				percent: (
					(femaleParticipants * 100) /
					(maleParticipants + femaleParticipants)
				).toFixed(2),
			},
		];
		this.pieChartWithColors(
			genderWiseParticipants,
			"mpr-pieChart1",
			"Participants",
			["#d79494", "#7cb5ec"]
		);
	}

	graphCountryTrainings(events) {
		let countryIndex = indexFilter.pi2020FilterData.countries
			.map((country) => {
				let country_count = events.filter(
					(e) => e.country_id == country.country_id
				).length;
				return {
					country: country.country_name,
					count: country_count,
					allValues: country_count,
				};
			})
			.filter((e) => e.count > 0);
		this.verticalBarChart(
			countryIndex,
			"country",
			"count",
			"mpr-mapCountrywiseNumberofTraining",
			"Number of events"
		);
		$("#table-3-tbody").html(
			countryIndex.map(
				(e) => `<tr><td>${e.country}</td><td>${e.count == 0 ? "NA" : numberWithCommas(e.count)}</td></tr>`
			)
		);

		let tfData = countryIndex.map((e) => e.count).reduce((a, b) => a + b, 0)
		$("#table-3-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphEventTrainings(events) {
		$("#cb-course-event-graph").show();
		let categoryIndex = indexFilter.pi2020FilterData.course_trainings
			.map((catElement) => {
				let categoryCount = events.filter(
					(e) => e.course_training_category == catElement.course_id
				).length;
				return {
					course: catElement.course_training_name,
					count: categoryCount,
					allValues: categoryCount,
				};
			})
			.filter((d) => d.count > 0);
		this.verticalBarChart(
			categoryIndex,
			"course",
			"count",
			"cb-course-event-graph",
			"Number of events"
		);
		$("#table-4-thead-row").html(
			`<th>Course name</th><th>Number of events</th>`
		);
		$("#table-4-tbody").html(
			categoryIndex.map(
				(e) => `<tr><td>${e.course}</td><td>${e.count == 0 ? "NA" : numberWithCommas(e.count)}</td></tr>`
			)
		);

		let tfData = categoryIndex.map((e) => e.count).reduce((a, b) => a + b, 0)
		$("#table-4-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphParticipantTrainings(events) {
		$("#cb-course-participant-graph").show();
		let categoryIndex = indexFilter.pi2020FilterData.course_trainings
			.map((catElement) => {
				let maleCount = events
					.filter((e) => e.course_training_category == catElement.course_id)
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleCount = events
					.filter((e) => e.course_training_category == catElement.course_id)
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				return {
					cat: catElement.course_training_name,
					male_count: maleCount,
					female_count: femaleCount,
				};
			})
			.filter((d) => d.male_count > 0 || d.female_count > 0);
		this.verticalStackedBarChartGender(
			categoryIndex,
			"cb-course-participant-graph",
			"Number of participants"
		);
		$("#table-4-thead-row").html(
			`<th>Course name</th><th>Male participants</th><th>Female participants</th><th>Total</th>`
		);
		$("#table-4-tbody").html(
			categoryIndex.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.male_count) == 0 ? "NA" : numberWithCommas(e.male_count)}</td>
					<td>${numberWithCommas(e.female_count) == 0 ? "NA" : numberWithCommas(e.female_count)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.male_count + e.female_count) == 0 ? "NA" : numberWithCommas(e.male_count + e.female_count)}</td></tr>`
			)
		);

		let tfMaleCount = numberWithCommas(categoryIndex.map((e) => e.male_count).reduce((a, b) => a + b, 0));
		let tffemaleCount = numberWithCommas(categoryIndex.map((e) => e.female_count).reduce((a, b) => a + b, 0));
		let tfmalefemaleTotal = numberWithCommas(categoryIndex.map((e) => e.male_count).reduce((a, b) => a + b, 0) +
				categoryIndex.map((e) => e.female_count).reduce((a, b) => a + b, 0))
		let tableFooter = `
    		<tr><td>Total</td><td>${tfMaleCount == 0 ? "NA" : numberWithCommas(tfMaleCount)}</td>
			<td>${tffemaleCount == 0 ? "NA" : numberWithCommas(tffemaleCount)}</td>
			<td>${tfmalefemaleTotal == 0 ? "NA" : numberWithCommas(tfmalefemaleTotal)}</td></tr>
		`;
		$("#table-4-tfoot").html(tableFooter);
	}

	getCourseChart() {
		$("#cb-course-event-graph").hide();
		$("#cb-course-participant-graph").hide();
		if (this.selectedCourseTab == 1) {
			this.graphEventTrainings(this.capacityBuildingData.tprs);
		} else if (this.selectedCourseTab == 2) {
			this.graphParticipantTrainings(this.capacityBuildingData.tprs);
		}
	}

	getHTMLactionForCourseTab() {
		$('input[name="cb-course-tab"]').on("change", () => {
			this.selectedCourseTab = $('input[name="cb-course-tab"]:checked').val();
			this.getCourseChart();
		});
	}

	graphParticipantKeyCategories(events, keys) {

		let participantDataRecords = indexFilter.pi2020FilterData.keycategories.map(
			(pElement) => {
				let keyDataRecords = keys
					.filter((e) => e.key_category_id == pElement.keycategory_id)
					.map((e) => e.data_id);
				let maleRecords = events
					.filter((e) => keyDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleRecords = events
					.filter((e) => keyDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				return {
					cat: pElement.keycategory,
					male_count: maleRecords,
					female_count: femaleRecords,
				};
			}
		);
		participantDataRecords = participantDataRecords.filter(
			(e) => e.male_count && e.female_count
		);
		this.verticalStackedBarChartGender(
			participantDataRecords,
			"mpr-mapKeyCategorywisefarmer",
			"Number of participants"
		);
		$("#table-5-tbody").html(
			participantDataRecords.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.male_count) == 0 ? "NA" : numberWithCommas(e.male_count)}</td>
					<td>${numberWithCommas(e.female_count) == 0 ? "NA" : numberWithCommas(e.female_count)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.male_count + e.female_count) == 0 ? "NA" : numberWithCommas(e.male_count + e.female_count)}</td></tr>`
			)
		);

		let tfMaleCount = participantDataRecords.map((e) => e.male_count).reduce((a, b) => a + b, 0);
		let tffemaleCount = participantDataRecords.map((e) => e.female_count).reduce((a, b) => a + b, 0);
		let tfmalefemaleTotal = numberWithCommas(participantDataRecords.map((e) => e.male_count).reduce((a, b) => a + b, 0) +
		participantDataRecords.map((e) => e.female_count).reduce((a, b) => a + b, 0))
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${ tfMaleCount == 0 ? "NA" : numberWithCommas(tfMaleCount)}</td>
			<td>${ tffemaleCount == 0 ? "NA" : numberWithCommas(tffemaleCount)}</td>
			<td>${ tfmalefemaleTotal == 0 ? "NA" : tfmalefemaleTotal}</td></tr>
`;
		$("#table-5-tfoot").html(tableFooter);
	}

	graphEventCRP(events, crps) {
		$("#cb-crp-training-event-graph").show();
		let crpEvents = indexFilter.pi2020FilterData.crps
			.map((crpElement) => {
				let crpDataRecords = crps
					.filter((e) => e.crp_id == crpElement.crp_id)
					.map((e) => e.data_id);
				let eventCrps = events.filter((e) => crpDataRecords.includes(e.data_id))
					.length;
				return {
					crp_name: crpElement.crp_name,
					count: eventCrps,
					allValues: eventCrps,
				};
			})
			.filter((d) => d.count > 0);
		this.verticalBarChart(
			crpEvents,
			"crp_name",
			"count",
			"cb-crp-training-event-graph",
			"Number of events"
		);
		$("#table-8-thead-row").html(`<th>CRP</th><th>Number of events</th>`);
		$("#table-8-tbody").html(
			crpEvents.map((e) => `<tr><td>${e.crp_name}</td><td>${e.count == 0 ? "NA" : numberWithCommas(e.count)}</td></tr>`)
		);

		let tfData = crpEvents.map((e) => e.count).reduce((a, b) => a + b, 0)
		$("#table-8-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphParticipantCRP(events, crps) {
		$("#cb-crp-training-participant-graph").show();
		let participantDataRecords = indexFilter.pi2020FilterData.crps
			.map((pElement) => {
				let crpDataRecords = crps
					.filter((e) => e.crp_id == pElement.crp_id)
					.map((e) => e.data_id);
				let maleRecords = events
					.filter((e) => crpDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleRecords = events
					.filter((e) => crpDataRecords.includes(e.data_id))
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				return {
					cat: pElement.crp_name,
					male_count: maleRecords,
					female_count: femaleRecords,
				};
			})
			.filter((e) => e.male_count > 0 || e.female_count > 0);
		this.verticalStackedBarChartGender(
			participantDataRecords,
			"cb-crp-training-participant-graph",
			"Number of participants"
		);
		$("#table-8-thead-row").html(
			`<th>CRP</th><th>Male participants</th><th>Female participants</th><th>Total</th>`
		);
		$("#table-8-tbody").html(
			participantDataRecords.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.male_count) == 0 ? "NA" : numberWithCommas(e.male_count)}</td>
					<td>${numberWithCommas(e.female_count) == 0 ? "NA" : numberWithCommas(e.female_count)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.male_count + e.female_count) == 0 ? "NA" : numberWithCommas(e.male_count + e.female_count)}</td></tr>`
			)
		);

		let tfMaleCount = numberWithCommas(participantDataRecords.map((e) => e.male_count).reduce((a, b) => a + b, 0));
		let tffemaleCount = numberWithCommas(participantDataRecords.map((e) => e.female_count).reduce((a, b) => a + b, 0));
		let tfmalefemaleTotal = numberWithCommas(participantDataRecords.map((e) => e.male_count).reduce((a, b) => a + b, 0) +
		participantDataRecords.map((e) => e.female_count).reduce((a, b) => a + b, 0));

		let tableFooter = `
    		<tr><td>Total</td>
			<td>${ tfMaleCount == 0 ? "NA" : numberWithCommas(tfMaleCount)}</td>
			<td>${ tffemaleCount == 0 ? "NA" : numberWithCommas(tffemaleCount)}</td>
			<td>${ tfmalefemaleTotal == 0 ? "NA" : numberWithCommas(tfmalefemaleTotal)}</td></tr>
`;
		$("#table-8-tfoot").html(tableFooter);
	}

	getCRPTrainingChart() {
		$("#cb-crp-training-event-graph").hide();
		$("#cb-crp-training-participant-graph").hide();
		if (this.selectedCRPTrainingTab == 1) {
			this.graphEventCRP(
				this.capacityBuildingData.tprs,
				this.capacityBuildingData.tpr_crps
			);
		} else if (this.selectedCRPTrainingTab == 2) {
			this.graphParticipantCRP(
				this.capacityBuildingData.tprs,
				this.capacityBuildingData.tpr_crps
			);
		}
	}

	getHTMLactionForCRPTrainingTab() {
		$('input[name="cb-crp-training-tab"]').on("change", () => {
			this.selectedCRPTrainingTab = $(
				'input[name="cb-crp-training-tab"]:checked'
			).val();
			this.getCRPTrainingChart();
		});
	}

	graphEventCRPYear(events, crps) {
		$("#cb-crp-training-event-yr-graph").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				result[c.crp_name] = events.filter(
					(e) => crpDataRecords.includes(e.data_id) && e.year_id == y.year_id
				).length;
			});
			return result;
		}).filter(d=> {
            const allCrps = Object.keys(d).filter(e=> e != 'year');
            return allCrps.some(e => d[e]);
        });

		console.log(chartData);

		// Highcharts.chart("cb-crp-training-event-yr-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of events" },
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
			return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name] ? Number(f[e.crp_name].toFixed(2)) : 0) };
		})

		$("#cb-crp-training-event-yr-graph").css("height", serz.length * 4 + "em");

		Highcharts.chart("cb-crp-training-event-yr-graph", {
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
					text: "Number of events",
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
			series: serz
		});

		$("#table-11-thead-row").html(
			`<th>CRP</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-11-tbody").html(
			indexFilter.pi2020FilterData.crps.map((e) => {
				let yValsHtml = chartData.map(
					(f) => `<td>${f[e.crp_name] == 0 ? "NA" : numberWithCommas(f[e.crp_name])}</td>`
				);
				let yVals = chartData.map((f) => f[e.crp_name]).reduce((a, b) => a + b, 0);

				return `<tr><td>${e.crp_name}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b, 0);
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e)}</td>`);
		$("#table-11-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue}</td></tr>`
		);
	}

	graphParticipantCRPYear(events, crps) {
		$("#cb-crp-training-participant-yr-graph").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				let maleCount = events
					.filter(
						(e) => crpDataRecords.includes(e.data_id) && e.year_id == y.year_id
					)
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleCount = events
					.filter(
						(e) => crpDataRecords.includes(e.data_id) && e.year_id == y.year_id
					)
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				result[c.crp_name] = maleCount + femaleCount;
			});
			return result;
		}).filter(d=> {
            const crps = Object.keys(d).filter(e=> e != 'year');
            return crps.some(e => d[e]);
        });


		// Highcharts.chart("cb-crp-training-participant-yr-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of participants" },
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
			return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name] ? Number(f[e.crp_name].toFixed(2)) : 0) };
		})

		$("#cb-crp-training-participant-yr-graph").css("height", serz.length * 4 + "em");

		Highcharts.chart("cb-crp-training-participant-yr-graph", {
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
					text: "Number of participants",
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
			series: serz
		});

		$("#table-11-thead-row").html(
			`<th>CRP</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-11-tbody").html(
			indexFilter.pi2020FilterData.crps.map((e) => {
				let yValsHtml = chartData.map(
					(f) => `<td>${f[e.crp_name] == 0 ? "NA" : numberWithCommas(f[e.crp_name])}</td>`
				);
				let yVals = chartData.map((f) => f[e.crp_name]).reduce((a, b) => a + b, 0);

				return `<tr><td>${
					e.crp_name
				}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b, 0);
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${e == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-11-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	getCRPYearChart() {
		$("#cb-crp-training-event-yr-graph").hide();
		$("#cb-crp-training-participant-yr-graph").hide();
		if (this.selectedCRPYearTab == 1) {
			this.graphEventCRPYear(
				this.capacityBuildingData.tprs,
				this.capacityBuildingData.tpr_crps
			);
		} else if (this.selectedCRPYearTab == 2) {
			this.graphParticipantCRPYear(
				this.capacityBuildingData.tprs,
				this.capacityBuildingData.tpr_crps
			);
		}
	}

	getHTMLactionForCRPYearTab() {
		$('input[name="cb-crp-training-yr-tab"]').on("change", () => {
			this.selectedCRPYearTab = $(
				'input[name="cb-crp-training-yr-tab"]:checked'
			).val();
			this.getCRPYearChart();
		});
	}

	graphEventCountryYear(events) {
		$("#cb-country-training-event-yr-graph").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				result[c.country_name] = events.filter(
					(e) => e.year_id == y.year_id && e.country_id == c.country_id
				).length;
			});
			return result;
		}).filter(d=> {
            const allCountries = Object.keys(d).filter(e=> e != 'year');
            return allCountries.some(e => d[e]);
        });

		console.log(chartData);
		// Highcharts.chart("cb-country-training-event-yr-graph", {
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
		// 		title: { text: "Number of events" },
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

		let serz = indexFilter.pi2020FilterData.countries
		.map((c) => {
			let result = {
				name: c.country_name,
				data: chartData.map((e) => e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : 0),
			};
			return result;
		})

		$("#cb-country-training-event-yr-graph").css("height", serz.length * 5 +"px")

		Highcharts.chart("cb-country-training-event-yr-graph", {
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
				categories: chartData.map((e) => e.year),
			},
			yAxis: {
				title: {
					text: "Number of participants",
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
			series: serz
		});
		$("#table-12-thead-row").html(
			`<th>Countries</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-12-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${f[e.country_name] == 0 ? "NA" : numberWithCommas(f[e.country_name])}</td>`
					);
					let yVals = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b, 0);

					return `<tr><td>${e.country_name}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
				}
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b, 0);
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${e== 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-12-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	graphParticipantCountryYear(events) {
		$("#cb-country-training-participant-yr-graph").show();
		let chartData = indexFilter.dataViewYears.map((y) => {
			let result = { year: y.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				let maleCount = events
					.filter((e) => e.country_id == c.country_id && e.year_id == y.year_id)
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleCount = events
					.filter((e) => e.country_id == c.country_id && e.year_id == y.year_id)
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				result[c.country_name] = maleCount + femaleCount;
			});
			return result;
		}).filter(d=> {
            const allCountries = Object.keys(d).filter(e=> e != 'year');
            return allCountries.some(e => d[e]);
        });

		// Highcharts.chart("cb-country-training-participant-yr-graph", {
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
		// 		title: { text: "Number of participants" },
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

		let serz =indexFilter.pi2020FilterData.countries
		.map((c) => {
			let result = {
				name: c.country_name,
				data: chartData.map((e) => e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : 0),
			};
			return result;
		})

		$("#cb-country-training-participant-yr-graph").css("height", serz.length * 5 + "px")


		Highcharts.chart("cb-country-training-participant-yr-graph", {
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
				categories: chartData.map((e) => e.year),
			},
			yAxis: {
				title: {
					text: "Number of participants",
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
			series: serz
		});
		$("#table-12-thead-row").html(
			`<th>Countries</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-12-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${f[e.country_name] == 0 ? "NA" : numberWithCommas(f[e.country_name])}</td>`
					);
					let yVals = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b, 0);

					return `<tr><td>${
						e.country_name
					}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : numberWithCommas(yVals)}</td></tr>`;
				}
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b, 0);
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${e == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-12-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${numberWithCommas(ttvalue == 0 ? "NA" : numberWithCommas(ttvalue))}</td></tr>`
		);
	}

	getCountryYearChart() {
		$("#cb-country-training-event-yr-graph").hide();
		$("#cb-country-training-participant-yr-graph").hide();
		if (this.selectedCountryYearTab == 1) {
			this.graphEventCountryYear(this.capacityBuildingData.tprs);
		} else if (this.selectedCountryYearTab == 2) {
			this.graphParticipantCountryYear(this.capacityBuildingData.tprs);
		}
	}

	getHTMLactionForCountryYearTab() {
		$('input[name="cb-country-training-yr-tab"]').on("change", () => {
			this.selectedCountryYearTab = $(
				'input[name="cb-country-training-yr-tab"]:checked'
			).val();
			this.getCountryYearChart();
		});
	}

	graphEventRP(events) {
		$("#cb-rp-training-event-graph").show();
		let categoryIndex = indexFilter.pi2020FilterData.reasearchprograms
			.map((catElement) => {
				let categoryCount = events.filter((e) => e.rp_id == catElement.rp_id)
					.length;
				return {
					rp_name: catElement.rp_name,
					count: categoryCount,
					allValues: categoryCount,
				};
			})
			.filter((d) => d.count > 0);
		this.verticalBarChart(
			categoryIndex,
			"rp_name",
			"count",
			"cb-rp-training-event-graph",
			"Number of events"
		);
		$("#table-9-thead-row").html(
			`<th>Research Program</th><th>Number of events</th>`
		);
		$("#table-9-tbody").html(
			categoryIndex.map(
				(e) => `<tr><td>${e.rp_name}</td><td>${e.count == 0 ? "NA" : numberWithCommas(e.count)}</td></tr>`
			)
		);

		let tfData = categoryIndex.map((e) => e.count).reduce((a, b) => a + b, 0)
		$("#table-9-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);
	}

	graphParticipantRP(events) {
		$("#cb-rp-training-participant-graph").show();
		let categoryIndex = indexFilter.pi2020FilterData.reasearchprograms
			.map((catElement) => {
				let maleCount = events
					.filter((e) => e.rp_id == catElement.rp_id)
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleCount = events
					.filter((e) => e.rp_id == catElement.rp_id)
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				return {
					cat: catElement.rp_name,
					male_count: maleCount,
					female_count: femaleCount,
				};
			})
			.filter((e) => e.male_count > 0 || e.female_count > 0);
		this.verticalStackedBarChartGender(
			categoryIndex,
			"cb-rp-training-participant-graph",
			"Number of participants"
		);
		$("#table-9-thead-row").html(
			`<th>Research Program</th><th>Male participants</th><th>Female participants</th><th>Total</th>`
		);
		$("#table-9-tbody").html(
			categoryIndex.map(
				(e) =>
					`<tr><td>${e.cat}</td><td>${numberWithCommas(e.male_count) == 0 ? "NA" : numberWithCommas(e.male_count)}</td>
					<td>${numberWithCommas(e.female_count) == 0 ? "NA" : numberWithCommas(e.female_count)}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.male_count + e.female_count) == 0 ? "NA" : numberWithCommas(e.male_count + e.female_count)}</td></tr>`
			)
		);

		let tfMaleCount = numberWithCommas(categoryIndex.map((e) => e.male_count).reduce((a, b) => a + b, 0));
		let tffemaleCount = numberWithCommas(categoryIndex.map((e) => e.female_count).reduce((a, b) => a + b, 0));
		let tfmalefemaleTotal = numberWithCommas(categoryIndex.map((e) => e.male_count).reduce((a, b) => a + b, 0) +
		categoryIndex.map((e) => e.female_count).reduce((a, b) => a + b, 0))
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${ tfMaleCount == 0 ? "NA" : numberWithCommas(tfMaleCount)}</td>
			<td>${ tffemaleCount ==0 ? "NA" : numberWithCommas(tffemaleCount)}</td>
			<td>${ tfmalefemaleTotal == 0 ? "NA" : numberWithCommas(tfmalefemaleTotal)}</td></tr>
		`;
		$("#table-9-tfoot").html(tableFooter);
	}

	getRPTrainingChart() {
		$("#cb-rp-training-event-graph").hide();
		$("#cb-rp-training-participant-graph").hide();
		if (this.selectedRPTrainingTab == 1) {
			this.graphEventRP(this.capacityBuildingData.tprs);
		} else if (this.selectedRPTrainingTab == 2) {
			this.graphParticipantRP(this.capacityBuildingData.tprs);
		}
	}

	getHTMLactionForRPTrainingTab() {
		$('input[name="cb-rp-training-tab"]').on("change", () => {
			this.selectedRPTrainingTab = $(
				'input[name="cb-rp-training-tab"]:checked'
			).val();
			this.getRPTrainingChart();
		});
	}

	arrangeData() {
		this.tprs = clone(this.capacityBuildingData.tprs);
		this.tprs.forEach((d) => {
			d.crps = clone(this.capacityBuildingData.tpr_crps).filter(
				(f) => f.data_id == d.data_id
			);
			d.sdgs = clone(this.capacityBuildingData.tpr_sdgs).filter(
				(f) => f.data_id == d.data_id
			);
		});
	}

	graphEventSDG() {
		let chartData = indexFilter.pi2020FilterData.course_trainings
			.map((course) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tprs.filter(
						(d) =>
							d.course_training_category == course.course_id &&
							d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return {
						from: course.course_training_name,
						to: sdg.sdg_name,
						value: val,
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		// console.log(chartData);
		$("#mpr-Typeofeventscontributing").css("height", "600px");
		this.sankeyChart(chartData, "mpr-Typeofeventscontributing");
	}

	graphCRPSDG() {
		$("#cb-crp-event-graph").show();
		let chartData = indexFilter.pi2020FilterData.crps
			.map((crp) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const val = this.tprs.filter(
						(d) =>
							d.crps.some((e) => e.crp_id == crp.crp_id) &&
							d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return {
						from: crp.crp_name,
						to: sdg.sdg_name,
						value: val,
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value > 0);
		// console.log(chartData);
		$("#cb-crp-event-graph").css("height", "600px");
		this.sankeyChart(chartData, "cb-crp-event-graph");
	}

	graphParticipantsSDG() {
		$("#cb-crp-participant-graph").show();
		// let maleSDG = indexFilter.pi2020FilterData.sdgs
		// 	.map((sdg) => {
		// 		const val = this.capacityBuildingData.tpr_keys
		// 			.filter((d) => d.sdgs.some((e) => e.sdg_id == sdg.sdg_id))
		// 			.map((e) => parseInt(e.no_of_male))
		// 			.reduce((a, b) => a + b, 0);
		// 		return { from: "Male", to: sdg.sdg_name, value: val, width: 10 };
		// 	})
		// 	.flat()
		// 	.filter((d) => d.value > 0);
		// let femaleSDG = indexFilter.pi2020FilterData.sdgs
		// 	.map((sdg) => {
		// 		const val = this.tprs
		// 			.filter((d) => d.sdgs.some((e) => e.sdg_id == sdg.sdg_id))
		// 			.map((e) => parseInt(e.no_of_female))
		// 			.reduce((a, b) => a + b, 0);
		// 		return { from: "Female", to: sdg.sdg_name, value: val, width: 10 };
		// 	})
		// 	.flat()
		// 	.filter((d) => d.value > 0);
		// let chartData = [...maleSDG, ...femaleSDG];
		let chartData = indexFilter.pi2020FilterData.sdgs
			.map((sdg) => {
				indexFilter.pi2020FilterData.keycategories.map(
					(pElement) => {
				// return { from: name, to: sdg.sdg_name, value: val, width: 10 };
				return { from: pElement.keycategory, to: sdg.sdg_name};
			})
			// .flat()

		// 	let chartData = indexFilter.pi2020FilterData.keycategories.map(
		// 		(pElement) => {
		// 			let keyDataRecords = this.capacityBuildingData.tpr_keys
		// 				.filter((e) => e.key_category_id == pElement.keycategory_id)
		// 				.map((e) => e.data_id);
		// return  pElement.keycategory

		
	})

		// 		let keyDataRecords = keys



			debugger
		// let participantDataRecords = indexFilter.pi2020FilterData.keycategories.map(
		// 	(pElement) => {
		// 		let keyDataRecords = keys
		// 			.filter((e) => e.key_category_id == pElement.keycategory_id)
		// 			.map((e) => e.data_id);
		// 		let maleRecords = events
		// 			.filter((e) => keyDataRecords.includes(e.data_id))
		// 			.map((e) => parseInt(e.no_of_male))
		// 			.reduce((a, b) => a + b, 0);
		// 		let femaleRecords = events
		// 			.filter((e) => keyDataRecords.includes(e.data_id))
		// 			.map((e) => parseInt(e.no_of_female))
		// 			.reduce((a, b) => a + b, 0);
		// 		return {
		// 			cat: pElement.keycategory,
		// 			male_count: maleRecords,
		// 			female_count: femaleRecords,
		// 		};
		// 	}
		// );

		// let chartData2 = indexFilter.pi2020FilterData.course_trainings
		// 	.map((course) => {
		// 		return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
		// 			const val = this.tprs.filter(
		// 				(d) =>
		// 					d.course_training_category == course.course_id &&
		// 					d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
		// 			).length;
		// 			return {
		// 				from: course.course_training_name,
		// 				to: sdg.sdg_name,
		// 				value: val,
		// 				width: 10,
		// 			};
		// 		});
		// 	})
		// 	.flat()
		// 	.filter((d) => d.value > 0);

		// 	debugger

		const sankeyData =indexFilter.pi2020FilterData.keycategories
		.map((course) => {
			return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
				const val = this.capacityBuildingData.tpr_keys.filter(
					(d) =>
						d.key_category_id == course.keycategory_id
				).length;
				return {
					from: course.keycategory,
					to: sdg.sdg_name,
					value: val,
				};
			});
		})
		.flat()
		.filter((d) => d.value > 0);

		$("#cb-crp-participant-graph").css("height", "600px");
		this.sankeyChart(sankeyData, "cb-crp-participant-graph");
	}

	getCRPSDGChart() {
		$("#cb-crp-event-graph").hide();
		$("#cb-crp-participant-graph").hide();
		if (this.selectedCRPTab == 1) {
			this.graphCRPSDG();
		} else if (this.selectedCRPTab == 2) {
			this.graphParticipantsSDG();
		}
	}

	getHTMLactionForCRPSDGTab() {
		$('input[name="cb-crp-tab"]').on("change", () => {
			this.selectedCRPTab = $('input[name="cb-crp-tab"]:checked').val();
			this.getCRPSDGChart();
		});
	}

	pieChart(dataObj, container, seriesName) {
		Highcharts.chart(container, {
			chart: { type: "pie" },
			title: { text: null },
			subtitle: { text: null },
			credits: { enabled: false },
			legend: { enabled: false },
			plotOptions: {
				pie: {
					allowPointSelect: false,
					dataLabels: {
						enabled: true,
						format:
							"{point.name}</span>: <b>{point.y} ({point.percent:.2f} %)</b>",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y} ({point.percent:.2f} %)</b> <br/>',
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

	pieChartWithColors(dataObj, container, seriesName, colorList) {
		Highcharts.chart(container, {
			chart: { type: "pie" },
			title: { text: null },
			subtitle: { text: null },
			credits: { enabled: false },
			legend: { enabled: false },
			colors: colorList,
			plotOptions: {
				pie: {
					allowPointSelect: false,
					dataLabels: {
						enabled: true,
						format:
							"{point.name}</span>: <b>{point.y} ({point.percent:.2f} %)</b>",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y} ({point.percent:.2f} %)</b> <br/>',
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

	verticalBarChart(dataObj, categories, categoryValues, container, yTitle) {
		// debugger
		let allValues = dataObj.map((e) => [e.allValues]).flat();
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
			subtitle: { text: null },
			colors: ["#d79494"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: dataObj.map((e) => e[categories]),
				title: { text: null },
			},
			// yAxis: {
			// 	title: { text: yTitle },
			// 	labels: { overflow: "justify" },
			// },
			yAxis: {
				min: 0,
				title: { text: yTitle },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y}</b>" },
			// plotOptions: {
			// 	series: {
			// 		dataLabels: { enabled: true, style: { textOutline: false } },
			// 		states: {
			// 			inactive: { opacity: 1 },
			// 			hover: { enabled: false },
			// 		},
			// 	},
			// },
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
					maxPointWidth: 30,
					name: "",
					data: dataObj.map((e) => e[categoryValues]),
				},
			],
		});
	}

	verticalStackedBarChartGender(dataObj, container, yTitle) {
		let allValues = dataObj.map((e) => [e.male_count,e.female_count]).flat();
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart(container, {
			chart: { type: "column" },
			title: { text: "" },
			xAxis: { categories: dataObj.map((e) => e.cat) },
			// yAxis: {
			// 	min: 0,
			// 	title: { text: yTitle },
			// 	stackLabels: {
			// 		enabled: true,
			// 		style: {
			// 			fontWeight: 'bold',
			// 			color: ( // theme
			// 				Highcharts.defaultOptions.title.style &&
			// 				Highcharts.defaultOptions.title.style.color
			// 			) || 'gray'
			// 		}
			// 	}
			// },
			yAxis: {
				min: 0,
				title: { text: yTitle },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
				stackLabels: {
					enabled: true,
					style: {
						fontWeight: 'bold',
						color: ( // theme
							Highcharts.defaultOptions.title.style &&
							Highcharts.defaultOptions.title.style.color
						) || 'gray'
					}
				}
			},
			credits: { enabled: false },
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
				shared: true,
			},
			// plotOptions: {
			// 	column: {
			// 		stacking: "normal",
			// 		dataLabels: { enabled: false, style: { textOutline: false } },
			// 	},
			// },

			plotOptions: {
				column: {
					stacking: "normal",
					dataLabels: { enabled: false, style: { textOutline: false } },
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
					name: "Female",
					color: "#7cb5ec",
					data: dataObj.map((e) => e.female_count),
				},
				{
					name: "Male",
					color: "#d79494",
					data: dataObj.map((e) => e.male_count),
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
			chart.exporting.filePrefix = "Events";
		});
	}

	graphCountryWiseParticipants(events) {
		// let chartData = indexFilter.pi2020FilterData.countries.map(country => {
		// 	let maleCount = events.filter(e => e.country_id == country.country_id).map(e => parseInt(e.no_of_male)).reduce((a, b) => a+b, 0);
		// 	let femaleCount = events.filter(e => e.country_id == country.country_id).map(e => parseInt(e.no_of_female)).reduce((a, b) => a+b, 0);
		// 	return {'id': country.country_id, 'name': country.country_name, 'countryCode': country.country_code, 'z': maleCount+femaleCount};
		// }).filter(d => d.z > 0);
		// this.worldMap(chartData, "cb-country-map", "countryCode", "", "Participants");

		let chartData = indexFilter.pi2020FilterData.countries
			.map((data) => {
				let result = {
					id: data.country_code,
					name: data.country_name,
					color: "lightblue",
				};
				let maleCount = events
					.filter((e) => e.country_id == data.country_id)
					.map((e) => parseInt(e.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleCount = events
					.filter((e) => e.country_id == data.country_id)
					.map((e) => parseInt(e.no_of_female))
					.reduce((a, b) => a + b, 0);
				result.value = maleCount + femaleCount;
				return result;
			})
			.filter((d) => d.value > 0);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("cb-country-map", am4maps.MapChart);
			chartData.forEach((d, i) => (d.color = chart.colors.getIndex(i)));
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
			imageSeries.data = chartData;
			imageSeries.dataFields.value = "value";

			// var imageTemplate = imageSeries.mapImages.template;
			// imageTemplate.nonScaling = true;


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

			chart.exporting.filePrefix = "cb-country";
			exportAmchart('dwn-img-2',chart)
		});

		$("#table-2-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.name}</td><td>${numberWithCommas(e.value) == 0 ? "NA" : numberWithCommas(e.value)}</td></tr>`
			)
		);

		let tfData = chartData.map((e) => e.value).reduce((a, b) => a + b, 0)
		$("#table-2-tfoot").html(
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

	graphYearwiseParticipants(events) {
		let chartData = indexFilter.dataViewYears
			.map((e) => {
				let maleCount = events
					.filter((d) => d.year_id == e.year_id)
					.map((d) => parseInt(d.no_of_male))
					.reduce((a, b) => a + b, 0);
				let femaleCount = events
					.filter((d) => d.year_id == e.year_id)
					.map((d) => parseInt(d.no_of_female))
					.reduce((a, b) => a + b, 0);
				return {
					cat: e.year,
					male_count: maleCount,
					female_count: femaleCount,
				};
			})
			.filter((d) => d.male_count > 0 || d.female_count > 0);
		chartData.sort((a, b) => (parseInt(a.cat) > parseInt(b.cat) ? 0 : -1));
		this.verticalStackedBarChartGender(
			chartData,
			"cb-yearwise-graph",
			"Number of participants"
		);
		$("#table-10-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.cat}</td>
					<td>${e.male_count == 0 ? "NA" : numberWithCommas(e.male_count)}</td>
					<td>${e.female_count == 0 ? "NA" : numberWithCommas(e.female_count)}</td>
					<td style="font-weight: 600;">${(e.male_count + e.female_count) == 0 ? "NA" : numberWithCommas(e.male_count + e.female_count)}</td></tr>`
			)
		);

		let tfMaleCount = numberWithCommas(chartData.map((e) => e.male_count).reduce((a, b) => a + b, 0));
		let tffemaleCount = numberWithCommas(chartData.map((e) => e.female_count).reduce((a, b) => a + b, 0));
		let tfmalefemaleTotal = numberWithCommas(chartData.map((e) => e.male_count).reduce((a, b) => a + b, 0) +
		chartData.map((e) => e.female_count).reduce((a, b) => a + b, 0))
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${tfMaleCount == 0 ? "NA" : tfMaleCount}</td>
			<td>${tffemaleCount == 0 ? "NA" : tffemaleCount}</td>
			<td>${tfmalefemaleTotal == 0 ? "NA" : tfmalefemaleTotal}</td></tr>
		`;
		$("#table-10-tfoot").html(tableFooter);
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
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
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
					.attr("download", `cb-summary.jpeg`);
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
			// html2canvas(document.getElementById("graph-2")).then((canvas) => {
			// 	let dataSrc = canvas.toDataURL("image/png");
			// 	dataSrc = dataSrc.replace("data:image/png;base64,", "");
			// 	$("#dwn-img-2")
			// 		.attr(
			// 			"href",
			// 			"data:application/octet-stream;base64," + encodeURI(dataSrc)
			// 		)
			// 		.attr("target", "_blank")
			// 		.attr("download", `cb-country.jpeg`);
			// });
		});
		$("#dwn-csv-2").on("click", function () {
			$("#table-2-main").table2csv({
				file_name: "cb-country.csv",
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
					.attr("download", `cb-country.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "cb-country.csv",
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
					.attr("download", `cb-course.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "cb-course.csv",
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
					.attr("download", `cb-keycategory.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "cb-keycategory.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					.attr("download", `cb-crp.jpeg`);
			});
		});
		$("#dwn-csv-8").on("click", function () {
			$("#table-8-main").table2csv({
				file_name: "cb-crp.csv",
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
					.attr("download", `cb-rp.jpeg`);
			});
		});
		$("#dwn-csv-9").on("click", function () {
			$("#table-9-main").table2csv({
				file_name: "cb-rp.csv",
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
					.attr("download", `cb-year.jpeg`);
			});
		});
		$("#dwn-csv-10").on("click", function () {
			$("#table-10-main").table2csv({
				file_name: "cb-year.csv",
				header_body_space: 0,
			});
		});

		//graph-11
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
					.attr("download", `cb-crp-year.jpeg`);
			});
		});
		$("#dwn-csv-11").on("click", function () {
			$("#table-11-main").table2csv({
				file_name: "cb-crp-year.csv",
				header_body_space: 0,
			});
		});

		//graph-12
		const graphTab12 = $("#graph-btn-12");
		const tableTab12 = $("#table-btn-12");
		const downloadTab12 = $("#download-btn-12>img");

		const graph12 = $("#graph-12");
		const table12 = $("#table-12");

		graphTab12.on("click", () => {
			graphTab12.addClass("active");
			tableTab12.removeClass("active");
			graph12.show();
			table12.hide();
			graphTab12
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab12
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab12.on("click", () => {
			tableTab12.addClass("active");
			graphTab12.removeClass("active");
			table12.show();
			graph12.hide();
			graphTab12
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab12
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});

		downloadTab12.on("click", () => {
			if (
				downloadTab12.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab12.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab12.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab12.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-12")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-12")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `cb-country-year.jpeg`);
			});
		});
		$("#dwn-csv-12").on("click", function () {
			$("#table-12-main").table2csv({
				file_name: "cb-country-year.csv",
				header_body_space: 0,
			});
		});

		//graph-13
		const graphTab13 = $("#graph-btn-13");
		const tableTab13 = $("#table-btn-13");
		const downloadTab13 = $("#download-btn-13>img");

		const graph13 = $("#graph-13");
		const table13 = $("#table-13");

		graphTab13.on("click", () => {
			graphTab13.addClass("active");
			tableTab13.removeClass("active");
			graph13.show();
			table13.hide();
			graphTab13
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab13
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab13.on("click", () => {
			tableTab13.addClass("active");
			graphTab13.removeClass("active");
			table13.show();
			graph13.hide();
			graphTab13
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab13
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab13.on("click", () => {
			if (
				downloadTab13.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab13.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab13.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab13.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
		});

		//graph-14
		const graphTab14 = $("#graph-btn-14");
		const tableTab14 = $("#table-btn-14");
		const downloadTab14 = $("#download-btn-14>img");

		const graph14 = $("#graph-14");
		const table14 = $("#table-14");

		graphTab14.on("click", () => {
			graphTab14.addClass("active");
			tableTab14.removeClass("active");
			graph14.show();
			table14.hide();
		});

		tableTab14.on("click", () => {
			tableTab14.addClass("active");
			graphTab14.removeClass("active");
			table14.show();
			graph14.hide();
		});
		downloadTab14.on("click", () => {
			if (
				downloadTab14.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab14.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab14.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab14.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
		});
	}
}

// Toggling portions
