var script = document.getElementById("lpJS"),
	baseURL = script.getAttribute("data-baseurl");

class LearnerParticipants {
	constructor() { }
	init() {
		this.getLearnerParticipantsData();
	}

	getLearnerParticipantsData() {
		// const request = { purpose: "get_lerner_participant" };
		const request = indexFilter.getFilteredData();
		request.purpose = "get_lerner_participant";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL +
				"/include/assets/js/pi_2020/tabs/learner_participants_tab.html",
				true
			),
		];
		Promise.all(promises).then((response) => {
			if (response?.length) {
				this.learnerParticipantsData = response[0];
				const resHtml = response[1].replaceAll(
					'src="img/',
					`src="${baseURL}include/assets/img/pi_2020/`
				);
				$(".mpr-tab-contend").html(resHtml);
				this.generateCharts();
				this.generateCountryWiseChart();
				this.generateCountriesMap();
				this.handleCountrySelection();
				this.generateYearwiseChart();
				this.generateYearwiseGenderChart();

				// this.generateYearwiseCRPChart();
				this.generateYearwiseCountryChart();
				this.htmlToggle();
			}
		});
		// .catch((err) => console.log(err));
	}

	generateCharts() {
		$("#totalLP").html("");
		$("internsLP").html("");
		$("#scholarsLP").html("");
		$("#fellowsLP").html("");
		const chartData = {
			males: this.learnerParticipantsData.tlps
				.map((d) => parseInt(d.no_of_male))
				.reduce((a, b) => a + b, 0),
			females: this.learnerParticipantsData.tlps
				.map((d) => parseInt(d.no_of_female))
				.reduce((a, b) => a + b, 0),
			learners: this.learnerParticipantsData.tlps
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0),
			interns: this.learnerParticipantsData.tlps
				.filter((d) => d.participants_category == 1)
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0),
			scholars: this.learnerParticipantsData.tlps
				.filter((d) => d.participants_category == 2)
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0),
			fellows: this.learnerParticipantsData.tlps
				.filter((d) => d.participants_category == 3)
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0),
		};

		if(chartData.learners == 0 ){
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

		$("#totalLP").html(chartData.learners == 0 ? "NA" : numberWithCommas(chartData.learners));
		$("#internsLP").html(chartData.interns == 0 ? "NA" : numberWithCommas(chartData.interns));
		$("#scholarsLP").html(chartData.scholars == 0 ? "NA" : numberWithCommas(chartData.scholars));
		$("#fellowsLP").html(chartData.fellows == 0 ? "NA" : numberWithCommas(chartData.fellows));

		Highcharts.chart("mpr-pieChart", {
			chart: {
				type: "pie",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494", "#7cb5ec", "#FFCE56"],
			credits: {
				enabled: false,
			},
			plotOptions: {
				pie: {
					allowPointSelect: false,
					//cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: "{point.name}: {point.y:.1f}%",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>',
			},
			series: [
				{
					name: "Hybrids / Varieties",
					colorByPoint: true,
					data: [
						{
							name: "Interns",
							y: Math.floor((chartData.interns / chartData.learners) * 100),
						},
						{
							name: "Scholars",
							y: Math.floor((chartData.scholars / chartData.learners) * 100),
						},
						{
							name: "Fellows",
							y: Math.floor((chartData.fellows / chartData.learners) * 100),
						},
					],
				},
			],
		});

		Highcharts.chart("mpr-pieChart1", {
			chart: {
				type: "pie",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494", "#7cb5ec", "#FFCE56"],
			credits: {
				enabled: false,
			},
			plotOptions: {
				pie: {
					allowPointSelect: false,
					//cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: "{point.name}: {point.y:.1f}%",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>',
			},
			series: [
				{
					name: "Hybrids / Varieties",
					colorByPoint: true,
					data: [
						{
							name: "Female",
							y: Math.floor((chartData.females / chartData.learners) * 100),
						},
						{
							name: "Male",
							y: Math.floor((chartData.males / chartData.learners) * 100),
						},
					],
				},
			],
		});

		let activeCountryIds = Array.from(
			new Set(this.learnerParticipantsData.tlps.map((e) => e.country_id))
		);
		let activeCountries = indexFilter.pi2020FilterData.countries.filter((e) =>
			activeCountryIds.includes(e.country_id)
		);
		$("#lp-summary-country-options").html(
			`<option value="0">All Countries</option>` +
			activeCountries.map(
				(e) => `<option value="${e.country_id}">${e.country_name}</option>`
			)
		);
		let allCountryRecords = this.learnerParticipantsData.tlps;
		let availableYearIds = Array.from(
			new Set(allCountryRecords.map((e) => e.year_id))
		);
		let yearsIndex = indexFilter.pi2020FilterData.years.filter((e) =>
			availableYearIds.includes(e.year_id)
		);
		let lpy = yearsIndex.map((e) => {
			let result = { year: e.year };
			let curYearRec = allCountryRecords.filter((d) => d.year_id == e.year_id);
			result.learners = curYearRec
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0);
			result.interns = curYearRec
				.filter((d) => d.participants_category == 1)
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0);
			result.scholars = curYearRec
				.filter((d) => d.participants_category == 2)
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0);
			result.fellows = curYearRec
				.filter((d) => d.participants_category == 3)
				.map((d) => parseInt(d.no_of_learners))
				.reduce((a, b) => a + b, 0);
			result.male = curYearRec
				.map((d) => parseInt(d.no_of_male))
				.reduce((a, b) => a + b, 0);
			result.female = curYearRec
				.map((d) => parseInt(d.no_of_female))
				.reduce((a, b) => a + b, 0);
			return result;
		});
		$("#lp-1-tbody").html(
			lpy.map(
				(e) =>
					`<tr><td>${e.year == 0 ? "NA" : numberWithCommas(e.year)}</td>
					<td>${e.learners == 0 ? "NA" : numberWithCommas(e.learners)}</td>
					<td>${e.interns == 0 ? "NA" : numberWithCommas(e.interns)}</td>
					<td>${e.scholars == 0 ? "NA" : numberWithCommas(e.scholars)}</td>
					<td>${e.fellows == 0 ? "NA" : numberWithCommas(e.fellows)}</td>
					<td>${e.male == 0 ? "NA" : numberWithCommas(e.male)}</td>
					<td>${e.female == 0 ? "NA" : numberWithCommas(e.female)}</td
					><td style="font-weight: 600;">${(e.learners + e.interns + e.scholars + e.fellows + e.male + e.female) == 0 ? "NA" : (e.learners + e.interns + e.scholars + e.fellows + e.male + e.female)
					}</td></tr>`
			)
		);

		let tfLearners = lpy.map((e) => e.learners).reduce((a, b) => a + b, 0);
		let tfInterns = lpy.map((e) => e.interns).reduce((a, b) => a + b, 0);
		let tfScholars = lpy.map((e) => e.scholars).reduce((a, b) => a + b, 0);
		let tfFellows = lpy.map((e) => e.fellows).reduce((a, b) => a + b, 0);
		let tfMale = lpy.map((e) => e.male).reduce((a, b) => a + b, 0);
		let tfFemale = lpy.map((e) => e.female).reduce((a, b) => a + b, 0);
		let tfTotal = lpy.map((e) => e.learners).reduce((a, b) => a + b, 0) +
			lpy.map((e) => e.male).reduce((a, b) => a + b, 0) +
			lpy.map((e) => e.female).reduce((a, b) => a + b, 0) +
			lpy.map((e) => e.interns).reduce((a, b) => a + b, 0) +
			lpy.map((e) => e.scholars).reduce((a, b) => a + b, 0) +
			lpy.map((e) => e.fellows).reduce((a, b) => a + b, 0)

		let tableFooter = `
    		<tr><td>Total</td>
			<td>${tfLearners == 0 ? "NA" : numberWithCommas(tfLearners)}</td>
			<td>${tfInterns == 0 ? "NA" : numberWithCommas(tfInterns)}</td>
			<td>${tfScholars == 0 ? "NA" : numberWithCommas(tfScholars)}</td>
			<td>${tfFellows == 0 ? "NA" : numberWithCommas(tfFellows)}</td>
			<td>${tfMale == 0 ? "NA" : numberWithCommas(tfMale)}</td>
			<td>${tfFemale == 0 ? "NA" : numberWithCommas(tfFemale)}</td>
			<td>${tfTotal == 0 ? "NA" : numberWithCommas(tfTotal)}</td></tr>
`;
		$("#lp-1-tfoot").html(tableFooter);
	}

	handleCountrySelection = () => {
		$("#lp-summary-country-options").on("change", (evn) => {
			if (evn.target.value != 0) {
				let selectedCountryRecords = this.learnerParticipantsData.tlps.filter(
					(e) => e.country_id == evn.target.value
				);
				let availableYearIds = Array.from(
					new Set(selectedCountryRecords.map((e) => e.year_id))
				);
				let yearsIndex = indexFilter.pi2020FilterData.years.filter((e) =>
					availableYearIds.includes(e.year_id)
				);
				let lpyc = yearsIndex.map((e) => {
					let result = { year: e.year };
					let curYearRec = selectedCountryRecords.filter(
						(d) => d.year_id == e.year_id
					);
					result.learners = curYearRec
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.interns = curYearRec
						.filter((d) => d.participants_category == 1)
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.scholars = curYearRec
						.filter((d) => d.participants_category == 2)
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.fellows = curYearRec
						.filter((d) => d.participants_category == 3)
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.male = curYearRec
						.map((d) => parseInt(d.no_of_male))
						.reduce((a, b) => a + b, 0);
					result.female = curYearRec
						.map((d) => parseInt(d.no_of_female))
						.reduce((a, b) => a + b, 0);
					return result;
				});
				// $("#lp-1-tbody").html(
				// 	lpyc.map(
				// 		(e) =>
				// 			`<tr><td>${e.year}</td><td>${e.learners}</td><td>${e.interns}</td><td>${e.scholars}</td><td>${e.fellows}</td><td>${e.male}</td><td>${e.female}</td></tr>`
				// 	)
				// );
				$("#lp-1-tbody").html(
					lpyc.map(
						(e) =>
							`<tr><td>${e.year == 0 ? "NA" : numberWithCommas(e.year)}</td>
							<td>${e.learners == 0 ? "NA" : numberWithCommas(e.learners)}</td>
							<td>${e.interns == 0 ? "NA" : numberWithCommas(e.interns)}</td>
							<td>${e.scholars == 0 ? "NA" : numberWithCommas(e.scholars)}</td>
							<td>${e.fellows == 0 ? "NA" : numberWithCommas(e.fellows)}</td>
							<td>${e.male == 0 ? "NA" : numberWithCommas(e.male)}</td>
							<td>${e.female == 0 ? "NA" : numberWithCommas(e.female)}</td>
							</tr>`
					)
				);
			} else {
				let allCountryRecords = this.learnerParticipantsData.tlps;
				let availableYearIds = Array.from(
					new Set(allCountryRecords.map((e) => e.year_id))
				);
				let yearsIndex = indexFilter.pi2020FilterData.years.filter((e) =>
					availableYearIds.includes(e.year_id)
				);
				let lpy = yearsIndex.map((e) => {
					let result = { year: e.year };
					let curYearRec = allCountryRecords.filter(
						(d) => d.year_id == e.year_id
					);
					result.learners = curYearRec
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.interns = curYearRec
						.filter((d) => d.participants_category == 1)
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.scholars = curYearRec
						.filter((d) => d.participants_category == 2)
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.fellows = curYearRec
						.filter((d) => d.participants_category == 3)
						.map((d) => parseInt(d.no_of_learners))
						.reduce((a, b) => a + b, 0);
					result.male = curYearRec
						.map((d) => parseInt(d.no_of_male))
						.reduce((a, b) => a + b, 0);
					result.female = curYearRec
						.map((d) => parseInt(d.no_of_female))
						.reduce((a, b) => a + b, 0);
					return result;
				});
				$("#lp-1-tbody").html(
					lpy.map(
						(e) =>
							// `<tr><td>${e.year}</td><td>${e.learners}</td><td>${e.interns}</td><td>${e.scholars}</td><td>${e.fellows}</td><td>${e.male}</td><td>${e.female}</td></tr>`
							`<tr><td>${e.year == 0 ? "NA" : numberWithCommas(e.year)}</td>
					<td>${e.learners == 0 ? "NA" : numberWithCommas(e.learners)}</td>
					<td>${e.interns == 0 ? "NA" : numberWithCommas(e.interns)}</td>
					<td>${e.scholars == 0 ? "NA" : numberWithCommas(e.scholars)}</td>
					<td>${e.fellows == 0 ? "NA" : numberWithCommas(e.fellows)}</td>
					<td>${e.male == 0 ? "NA" : numberWithCommas(e.male)}</td>
					<td>${e.female == 0 ? "NA" : numberWithCommas(e.female)}</td></tr>`
					)
				);
			}
		});
	};

	generateCountryWiseChart() {
		const chartData = indexFilter.pi2020FilterData.countries
			.map((d) => {
				const result = {
					countryName: d.country_name,
					country_id: d.country_id,
					internsCount: 0,
					scholarsCount: 0,
					fellowsCount: 0,
				};
				result.internsCount = this.learnerParticipantsData.tlps
					.filter(
						(data) =>
							data.participants_category == 1 && data.country_id == d.country_id
					)
					.map((j) => parseInt(j.no_of_learners))
					.reduce((a, b) => a + b, 0);
				result.scholarsCount = this.learnerParticipantsData.tlps
					.filter(
						(data) =>
							data.participants_category == 2 && data.country_id == d.country_id
					)
					.map((j) => parseInt(j.no_of_learners))
					.reduce((a, b) => a + b, 0);
				result.fellowsCount = this.learnerParticipantsData.tlps
					.filter(
						(data) =>
							data.participants_category == 3 && data.country_id == d.country_id
					)
					.map((j) => parseInt(j.no_of_learners))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((i) => i.internsCount || i.scholarsCount || i.fellowsCount);

		Highcharts.chart("mpr-mapResearchprogramwiseunderwatershed", {
			chart: {
				type: "column",
			},
			title: {
				text: "",
			},
			xAxis: {
				//   categories: ['India', 'Phillipines', 'Bangladesh', 'Burundi', 'Erithria', 'Ethiopia', 'Kenya', 'Malawi', 'Somalia']
				categories: chartData.map((d) => d.countryName),
			},
			yAxis: {
				min: 0,
				title: {
					text: "Number of learners",
				},
			},
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
				shared: true,
			},
			plotOptions: {
				column: {
					stacking: "normal",
					dataLabels: { enabled: true, style: { textOutline: false } },
				},
			},

			series: [
				{
					name: "Interns",
					color: "#FFCE56",
					data: chartData.map((d) => d.internsCount),
				},
				{
					name: "Scholars",
					color: "#7cb5ec",
					data: chartData.map((d) => d.scholarsCount),
				},
				{
					name: "Fellows",
					color: "#d79494",
					data: chartData.map((d) => d.fellowsCount),
				},
			],
		});

		$("#table-3-tbody").html(
			chartData.map(
				(e) =>
					`<tr>
					<td>${e.countryName == 0 ? "NA" : numberWithCommas(e.countryName)}</td>
					<td>${e.internsCount == 0 ? "NA" : numberWithCommas(e.internsCount)}</td>
					<td>${e.scholarsCount == 0 ? "NA" : numberWithCommas(e.scholarsCount)}</td>
					<td>${e.fellowsCount == 0 ? "NA" : numberWithCommas(e.fellowsCount)}</td>
					<td style="font-weight: 600;">${(e.fellowsCount + e.scholarsCount + e.internsCount) == 0 ? "NA" : numberWithCommas(e.fellowsCount + e.scholarsCount + e.internsCount)}</td>
					</tr>`
			)
		);

		let tfInterns = chartData.map((e) => e.internsCount).reduce((a, b) => a + b, 0);
		let tfScholars = chartData.map((e) => e.scholarsCount).reduce((a, b) => a + b, 0);
		let tfFellows = chartData.map((e) => e.fellowsCount).reduce((a, b) => a + b, 0);
		let tfTotal = chartData.map((e) => e.internsCount).reduce((a, b) => a + b, 0) + chartData.map((e) => e.scholarsCount).reduce((a, b) => a + b, 0) + chartData.map((e) => e.fellowsCount).reduce((a, b) => a + b, 0)
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${tfInterns == 0 ? "NA" : numberWithCommas(tfInterns)}</td>
			<td>${tfScholars == 0 ? "NA" : numberWithCommas(tfScholars)}</td>
			<td>${tfFellows == 0 ? "NA" : numberWithCommas(tfFellows)}</td>
			<td>${tfTotal == 0 ? "NA" : numberWithCommas(tfTotal)}</td></tr>
`;
		$("#table-3-tfoot").html(tableFooter);
	}

	generateYearwiseChart() {
		const chartData = indexFilter.dataViewYears
			.map((d) => {
				const result = { year: d.year };
				result.internsCount = this.learnerParticipantsData.tlps
					.filter(
						(data) =>
							data.participants_category == 1 && data.year_id == d.year_id
					)
					.map((j) => parseInt(j.no_of_learners))
					.reduce((a, b) => a + b, 0);
				result.scholarsCount = this.learnerParticipantsData.tlps
					.filter(
						(data) =>
							data.participants_category == 2 && data.year_id == d.year_id
					)
					.map((j) => parseInt(j.no_of_learners))
					.reduce((a, b) => a + b, 0);
				result.fellowsCount = this.learnerParticipantsData.tlps
					.filter(
						(data) =>
							data.participants_category == 3 && data.year_id == d.year_id
					)
					.map((j) => parseInt(j.no_of_learners))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((i) => i.internsCount || i.scholarsCount || i.fellowsCount);
		// Highcharts.chart("lp-yearwise-graph", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: "",
		// 	},
		// 	xAxis: {
		// 		//   categories: ['India', 'Phillipines', 'Bangladesh', 'Burundi', 'Erithria', 'Ethiopia', 'Kenya', 'Malawi', 'Somalia']
		// 		categories: chartData.map((d) => d.year),
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: "Number of participants",
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
		// 			dataLabels: { enabled: true, style: { textOutline: false } },
		// 		},
		// 	},

		// 	series: [
		// 		{
		// 			name: "Interns",
		// 			color: "#FFCE56",
		// 			data: chartData.map((d) => d.internsCount),
		// 		},
		// 		{
		// 			name: "Scholars",
		// 			color: "#7cb5ec",
		// 			data: chartData.map((d) => d.scholarsCount),
		// 		},
		// 		{
		// 			name: "Fellows",
		// 			color: "#d79494",
		// 			data: chartData.map((d) => d.fellowsCount),
		// 		},
		// 	],
		// });

		Highcharts.chart("lp-yearwise-graph", {
			chart: {
				type: "area",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			xAxis: { categories: chartData.map((d) => d.year) },
			yAxis: {
				title: {
					text: "Number of learners",
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
			series: [
				{
					name: "Interns",
					color: "#FFCE56",
					data: chartData.map((d) => d.internsCount),
				},
				{
					name: "Scholars",
					color: "#7cb5ec",
					data: chartData.map((d) => d.scholarsCount),
				},
				{
					name: "Fellows",
					color: "#d79494",
					data: chartData.map((d) => d.fellowsCount),
				},
			],
		});
		$("#table-4-tbody").html(
			chartData.map(
				(e) =>
					`<tr>
					<td>${e.year}</td>
					<td>${e.internsCount == 0 ? "NA" : numberWithCommas(e.internsCount)}</td>
					<td>${e.scholarsCount == 0 ? "NA" : numberWithCommas(e.scholarsCount)}</td>
					<td>${e.fellowsCount == 0 ? "NA" : numberWithCommas(e.fellowsCount)}</td>
					<td style="font-weight: 600;">${(e.fellowsCount + e.scholarsCount + e.internsCount) == 0 ? "NA" : numberWithCommas(e.fellowsCount + e.scholarsCount + e.internsCount)}</td></tr>`
			)
		);

		let tfInterns = chartData.map((e) => e.internsCount).reduce((a, b) => a + b, 0);
		let tfScholars = chartData.map((e) => e.scholarsCount).reduce((a, b) => a + b, 0);
		let tfFellows = chartData.map((e) => e.fellowsCount).reduce((a, b) => a + b, 0);
		let tfTotal = chartData.map((e) => e.internsCount).reduce((a, b) => a + b, 0) +
			chartData.map((e) => e.scholarsCount).reduce((a, b) => a + b, 0) +
			chartData.map((e) => e.fellowsCount).reduce((a, b) => a + b, 0)

		let tableFooter = `
    		<tr><td>Total</td>
			<td>${tfInterns == 0 ? "NA" : numberWithCommas(tfInterns)}</td>
			<td>${tfScholars == 0 ? "NA" : numberWithCommas(tfScholars)}</td>
			<td>${tfFellows == 0 ? "NA" : numberWithCommas(tfFellows)}</td>
			<td>${tfTotal == 0 ? "NA" : numberWithCommas(tfTotal)}</td></tr>
`;
		$("#table-4-tfoot").html(tableFooter);
	}

	generateYearwiseGenderChart() {
		const chartData = indexFilter.dataViewYears
			.map((d) => {
				const result = { year: d.year };
				result.maleCount = this.learnerParticipantsData.tlps
					.filter((e) => e.year_id == d.year_id)
					.map((e) => parseInt(e.no_of_male || 0))
					.reduce((a, b) => a + b, 0);
				result.femaleCount = this.learnerParticipantsData.tlps
					.filter((e) => e.year_id == d.year_id)
					.map((e) => parseInt(e.no_of_female || 0))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.maleCount || d.femaleCount);
		Highcharts.chart("lp-yearwise-gender-graph", {
			chart: { type: "column" },
			title: { text: "" },
			xAxis: { categories: chartData.map((e) => e.year) },
			yAxis: {
				min: 0,
				title: { text: "Number of learners" },
			},
			credits: { enabled: false },
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)<br/>',
				shared: true,
			},
			plotOptions: {
				column: {
					stacking: "normal",
					dataLabels: { enabled: true, style: { textOutline: false } },
				},
			},
			series: [
				{
					name: "Female",
					color: "#7cb5ec",
					data: chartData.map((e) => e.femaleCount),
				},
				{
					name: "Male",
					color: "#d79494",
					data: chartData.map((e) => e.maleCount),
				},
			],
		});

		$("#table-5-tbody").html(
			chartData.map(
				(e) =>
					`<tr>
					<td>${e.year}</td>
					<td>${e.maleCount == 0 ? "NA" : numberWithCommas(e.maleCount)}</td>
					<td>${e.femaleCount == 0 ? "NA" : numberWithCommas(e.femaleCount)}</td>
					<td style="font-weight: 600;">${(e.maleCount + e.femaleCount) == 0 ? "NA" : numberWithCommas(e.maleCount + e.femaleCount)}</td></tr>`
			)
		);

		let tfMale = chartData.map((e) => e.maleCount).reduce((a, b) => a + b, 0);
		let tfFemale = chartData.map((e) => e.femaleCount).reduce((a, b) => a + b, 0);
		let tfTotal = chartData.map((e) => e.maleCount).reduce((a, b) => a + b, 0) + chartData.map((e) => e.femaleCount).reduce((a, b) => a + b, 0)
		let tableFooter = `
    		<tr><td>Total</td>
			<td>${tfMale == 0 ? "NA" : numberWithCommas(tfMale)}</td>
			<td>${tfFemale == 0 ? "NA" : numberWithCommas(tfFemale)}</td>
			<td>${tfTotal == 0 ? "NA" : numberWithCommas(tfTotal)}</td></tr>
`;
		$("#table-5-tfoot").html(tableFooter);
	}

	generateYearwiseCRPChart() {
		const chartData = indexFilter.dataViewYears.map((y) => {
			const result = { year: y.year };
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				result[c.crp_name] = this.learnerParticipantsData.tlps
					.filter((e) => e.crp_id == c.crp_id && e.year_id == y.year_id)
					.map((e) => parseInt(e.no_of_learners || 0))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		});
		console.log(chartData);
	}

	generateYearwiseCountryChart() {
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				result[c.country_name] = this.learnerParticipantsData.tlps
					.filter(
						(e) => e.country_id == c.country_id && e.year_id == yr.year_id
					)
					.map((e) => parseInt(e.no_of_learners || 0))
					.reduce((a, b) => a + b, 0);
			});
			return result;
		}).filter(d => {
			const allCountries = Object.keys(d).filter(e => e != 'year');
			return allCountries.some(e => d[e]);
		});

		// Highcharts.chart("lp-yearwise-country-graph", {
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


		const cat = chartData.map((e) => e.year)

		const serz = indexFilter.pi2020FilterData.countries
			.map((c) => {
				let result = {
					name: c.country_name,
					data: chartData
						// .filter((e) => e[c.country_name] > 0)
						.map((e) => e[c.country_name] ? Number(e[c.country_name].toFixed(2)) : null),
				};
				return result;
			}).filter(e => (e.data).some(d => d))

		Highcharts.chart("lp-yearwise-country-graph", {
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
				categories: chartData
					// .filter(
					// 	(e) =>
					// 		!Object.keys(e)
					// 			.filter((e) => e != "year")
					// 			.map((f) => e[f])
					// 			.every((f) => f == 0)
					// )
					.map((e) => e.year),
			},
			yAxis: {
				title: {
					text: "Number of learners",
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

		$("#table-7-thead-row").html(
			`<th>Countries</th>` +
			chartData.map((e) => `<th>${e.year}</th>`) +
			`<th>Total</th>`
		);
		$("#table-7-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${numberWithCommas(f[e.country_name]) == 0 ? "NA" : numberWithCommas(f[e.country_name])}</td>`
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
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-7-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue == 0 ? "NA" : numberWithCommas(ttvalue)}</td></tr>`
		);
	}

	generateCountriesMap() {
		// const mapData = indexFilter.countries.map(data => {
		//     const result = { id: data.country_id, name: data.country_name, countryCode: data.country_code };
		//     result.z = this.learnerParticipantsData.tlps.filter(d => d.country_id == data.country_id).map(d => parseInt(d.no_of_male)+parseInt(d.no_of_female)).reduce((a, b) => a+b, 0);
		//     return result;
		// }).filter(d => d.z > 0);
		// Highcharts.mapChart("mpr-learnerparticipants", {
		//     chart: {
		//         borderWidth: 0,
		//         map: "custom/world",
		//     },
		//     title: {
		//         text: null,
		//     },
		//     subtitle: {
		//         text: null,
		//     },
		//     credits: {
		//         enabled: false,
		//     },
		//     legend: {
		//         enabled: false,
		//     },
		//     mapNavigation: {
		//         enabled: true,
		//         buttonOptions: {
		//             verticalAlign: "bottom",
		//         },
		//     },
		//     series: [
		//         {
		//             name: "Countries",
		//             color: "#4dabf5",
		//             enableMouseTracking: false,
		//         },
		//         {
		//             type: "mapbubble",
		//             name: "Hybrids / Varieties",
		//             joinBy: ["iso-a2", "countryCode"],
		//             data: mapData,
		//             minSize: 4,
		//             maxSize: "12%",
		//             tooltip: {
		//                 pointFormat: "{point.name}: {point.z}",
		//             },
		//             dataLabels: {
		//                 enabled: true,
		//                 style: {textOutline: false}
		//             }
		//         },
		//     ],
		// });

		let chartData = indexFilter.pi2020FilterData.countries
			.map((data) => {
				let result = {
					id: data.country_code,
					name: data.country_name,
					color: "lightblue",
				};
				result.value = this.learnerParticipantsData.tlps
					.filter((d) => d.country_id == data.country_id)
					.map((d) => parseInt(d.no_of_male) + parseInt(d.no_of_female))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.value > 0);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("mpr-learnerparticipants", am4maps.MapChart);
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
			label.fontSize = 14;
			// label.adapter.add("dy", function (dy, target) {
			// 	var circle = target.parent.children.getIndex(0);
			// 	return circle.pixelRadius;
			// });

			chart.exporting.filePrefix = "lp-country";
			exportAmchart('dwn-img-2',chart)
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
					.attr("download", `lp-summary.jpeg`);
			});
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "lp-summary.csv",
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
			// html2canvas(document.getElementById("graph-2")).then((canvas) => {
			// 	let dataSrc = canvas.toDataURL("image/png");
			// 	dataSrc = dataSrc.replace("data:image/png;base64,", "");
			// 	$("#dwn-img-2")
			// 		.attr(
			// 			"href",
			// 			"data:application/octet-stream;base64," + encodeURI(dataSrc)
			// 		)
			// 		.attr("target", "_blank")
			// 		.attr("download", `lp-country.jpeg`);
			// });
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
					.attr("download", `lp-country.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "lp-country.csv",
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
					.attr("download", `lp-year.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "lp-year.csv",
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
					.attr("download", `lp-year.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "lp-year.csv",
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
					.attr("download", `lp-year.jpeg`);
			});
		});
		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "lp-year.csv",
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
				dataSrc = dataSrc.replace("data:image/png;base74,", "");
				$("#dwn-img-7")
					.attr(
						"href",
						"data:application/octet-stream;base74," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `lp-year.jpeg`);
			});
		});
		$("#dwn-csv-7").on("click", function () {
			$("#table-7-main").table2csv({
				file_name: "lp-year.csv",
				header_body_space: 0,
			});
		});
	}
}
