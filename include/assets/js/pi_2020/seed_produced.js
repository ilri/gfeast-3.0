var script = document.getElementById("spJS"),
	baseURL = script.getAttribute("data-baseurl");

Highcharts.setOptions({
	lang: {
		thousandsSep: ",",
	},
});

class SeedProduced {
	constructor() { }
	init() {
		this.getSeedProducedData();
	}

	getSeedProducedData() {
		const request = indexFilter.getFilteredData();
		request.purpose = "get_seed_produced";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL + "/include/assets/js/pi_2020/tabs/seeds_produced_tab.html",
				true
			),
		];
		Promise.all(promises).then((response) => {
			if (response?.length) {
				this.seedProducedData = response[0];
				const resHtml = response[1].replaceAll(
					'src="img/',
					`src="${baseURL}include/assets/img/pi_2020/`
				);
				$(".mpr-tab-contend").html(resHtml);
				this.arrangeData();
				this.generateCharts();
				this.staticCharts();
				this.getHtmlActions();
				this.htmlToggle();
			}
		});
		// .catch((err) => console.log(err));
	}

	arrangeData() {
		this.breaderData = this.seedProducedData.tsp_breeders.map((data) => {
			const result = clone(data);
			result.tsp = this.seedProducedData.tsps.filter(
				(d) => d.data_id == data.data_id
			);
			result.tspSdgs = this.seedProducedData.tsp_sdgs.filter(
				(d) => d.data_id == data.data_id
			);
			result.crps = this.seedProducedData.tsp_crps.filter(
				(d) => d.data_id == data.data_id
			);
			return result;
		});
		this.certifiedData = this.seedProducedData.tsp_certifieds.map((data) => {
			const result = clone(data);
			result.tsp = this.seedProducedData.tsps.filter(
				(d) => d.data_id == data.data_id
			);
			result.tspSdgs = this.seedProducedData.tsp_sdgs.filter(
				(d) => d.data_id == data.data_id
			);
			result.crps = this.seedProducedData.tsp_crps.filter(
				(d) => d.data_id == data.data_id
			);
			return result;
		});
		this.foundationData = this.seedProducedData.tsp_foundations.map((data) => {
			const result = clone(data);
			result.tsp = this.seedProducedData.tsps.filter(
				(d) => d.data_id == data.data_id
			);
			result.tspSdgs = this.seedProducedData.tsp_sdgs.filter(
				(d) => d.data_id == data.data_id
			);
			result.crps = this.seedProducedData.tsp_crps.filter(
				(d) => d.data_id == data.data_id
			);
			return result;
		});

		this.qcdcData = this.seedProducedData.tsp_qdcs.map((data) => {
			const result = clone(data);
			result.tsp = this.seedProducedData.tsps.filter(
				(d) => d.data_id == data.data_id
			);
			result.tspSdgs = this.seedProducedData.tsp_sdgs.filter(
				(d) => d.data_id == data.data_id
			);
			result.crps = this.seedProducedData.tsp_crps.filter(
				(d) => d.data_id == data.data_id
			);
			return result;
		});

		this.tspsData = this.seedProducedData.tsps.map((data) => {
			const result = clone(data);
			result.tsp = this.seedProducedData.tsps.filter(
				(d) => d.data_id == data.data_id
			);
			result.tspSdgs = this.seedProducedData.tsp_sdgs.filter(
				(d) => d.data_id == data.data_id
			);
			result.crps = this.seedProducedData.tsp_crps.filter(
				(d) => d.data_id == data.data_id
			);
			result.unit = result["unit_breeder_seed"] || result["unit_foundation_seed"] || result["unit_certifiedseed"] ||result["unit_QDS"]
			result.all = result["quantity_breeder_seed"] || result["quantity_foundation_seed"] || result["quantity_certified_seed"] ||result["quantity_QDS"]
			result.key_producer_categories = [...new Set([
				...this.seedProducedData.tsp_breeders.filter(d=> d.data_id == data.data_id).map(e=> e.key_producer_category),
				...this.seedProducedData.tsp_certifieds.filter(d=> d.data_id == data.data_id).map(e=> e.key_producer_category),
				...this.seedProducedData.tsp_foundations.filter(d=> d.data_id == data.data_id).map(e=> e.key_producer_category),
				...this.seedProducedData.tsp_qdcs.filter(d=> d.data_id == data.data_id).map(e=> e.key_producer_category),
			])]

			return result;
		});
	}

	generateCharts() {
		this.getSeedProducedContainerInfo();
		this.getCountryComparationInfo();

		this.getYearComparisonInfo();
		this.getYearComparisonInfoBR();
		this.getYearComparisonInfoFN();
		this.getYearComparisonInfoCF();
		this.getYearComparisonInfoQD();

		this.getCountryCropWiseSeedProducedInfo();
		this.getProducerWiseSeedInfo();
		this.getCropWiseSeedProducedInfo();
		this.getResearchWiseSeedProducedInfo();
		this.getCropAndCountryWiseSeedInfo();
		this.getCropWiseSdgsContribution();
		this.generateCrpWiseCropInfo();
		this.getScientificPublications();
		this.generateCountriesMap();
		// this.generateSeedProduceBreederChart();
		//this.generateSeedProduceBreederStackedChart();
		this.getYearCrpsInfo();
		this.getYearContriesInfo();
	}
	getSeedProducedContainerInfo() {
		const selectedCrop = $("#seed-production-crop-name").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const getTonsData = (key, unit) => {
			return this.seedProducedData.tsps
				.filter(search)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const filteredData = indexFilter.getFilteredData();
		const getTonsDataList = (key, unit) => {
			return indexFilter.years.map((year) => {
				const val = this.seedProducedData.tsp_full
					.filter((d) => search(d) && d.year_id == year.year_id)
					.map((d) => {
						if (d[unit]?.toUpperCase() == "KG") {
							return parseFloat(d[key] || 0) / 1000;
						} else {
							return parseFloat(d[key] || 0);
						}
					})
					.filter((d) => !isNaN(d))
					.reduce((v1, v2) => v1 + v2, 0);
				return { year: year.year, value: val };
			});
		};
		const chartData = {
			breader: Number(getTonsData("quantity_breeder_seed", "unit_breeder_seed").toFixed(2)),
			foundation: Number(getTonsData(
				"quantity_foundation_seed",
				"unit_foundation_seed"
			).toFixed(2)),
			qcdc: Number(getTonsData("quantity_QDS", "unit_QDS").toFixed(2)),
			certified: Number(getTonsData("quantity_certified_seed", "unit_certifiedseed").toFixed(2))
		};
		const allValues = [
			chartData.breader,
			chartData.foundation,
			,
			chartData.qcdc,
			chartData.certified,
		].flat();

		const maxVal = Math.max(...allValues);

		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];
		const mprChart = Highcharts.chart("mpr-mapTraitwisehybrids", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494", "#f9d533"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: ["Breeder Seeds", "Foundation Seeds", "Certified Seeds", "Quality Declared Seeds/<br>Truthfully Labelled Seeds"],
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
					text: "",
					align: "high",
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
			tooltip: {},
			enabled: true,
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						format: "{point.y:.2f}",
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
					name: "Primary",
					data: [
						{ y: chartData.breader, color: "#d79494" },
						{ y: chartData.foundation, color: "#7cb5ec" },
						{ y: chartData.certified, color: "#FFCC66" },
						{ y: chartData.qcdc, color: "#6de38e" },
					],
				},
			],
		});
		const getPercentage = (val) => {
			const total =
				chartData.certified +
				chartData.foundation +
				chartData.qcdc +
				chartData.breader;
			if (!total) return 0;
			return Number(((val / total) * 100).toFixed(2));
		};

		const fixedVal = (val) => Number(val.toFixed(2));
		const htmlData = `

    <div class="row">
    <div class="col-md-3">
      <span class="seed_text_count">Breeder Seeds</span>
    </div>
    <div class="col-md-3">
      <div class="seed_text_count">${numberWithCommas(
			fixedVal(chartData.breader)
		)}</div>
    </div>
    <div class="col-md-6">
      <div id="breeder-sparkLine" style="height:50px;"></div>
    </div>
  </div>
  
  <div class="row mt-4">
    <div class="col-md-3">
      <span class="seed_text_count">Foundation Seeds</span>
    </div>
    <div class="col-md-3">
      <div class="seed_text_count">${numberWithCommas(
			fixedVal(chartData.foundation)
		)}</div>
    </div>
    <div class="col-md-6">
      <div id="foundation-sparkLine" style="height:50px;"></div>
    </div>
  </div>
  
  
  <div class="row mt-4">
    <div class="col-md-3">
      <span class="seed_text_count">Certified Seeds</span>
    </div>
    <div class="col-md-3">
      <div class="seed_text_count">${numberWithCommas(
			fixedVal(chartData.certified)
		)}</div>
        </div>
      <div class="col-md-6">
        <div id="certified-sparkLine" style="height:50px;"></div>
      </div>
    </div>
  
  </div>
  <div class="row mt-4">
    <div class="col-md-3">
      <span class="seed_text_count">Quality Declared Seeds/Truthfully Labelled Seeds</span>
    </div>
    <div class="col-md-3">
      <div class="seed_text_count">${numberWithCommas(
			fixedVal(chartData.qcdc)
		)}</div>
    </div>
    <div class="col-md-6">
      <div id="qcdc-sparkLine" style="height:50px;"></div>
    </div>
  </div>
    `;
		$("#cat-num").html(htmlData);
		$("#table-11-tbody").html(`
      <tr><td>Breeder Seeds</td><td>${numberWithCommas(
			chartData.breader.toFixed(2)
		) == 0 ? "NA" : chartData.breader.toFixed(2)}</td></tr>
      <tr><td>Foundation Seeds</td><td>${numberWithCommas(
			chartData.foundation.toFixed(2)
		)== 0 ? "NA" : chartData.foundation.toFixed(2)}</td></tr>
      <tr><td>Quality Declared Seeds/Truthfully Labelled Seeds</td><td>${numberWithCommas(
			chartData.qcdc.toFixed(2)
		)== 0 ? "NA" : chartData.qcdc.toFixed(2)}</td></tr>
      <tr><td>Certified Seeds</td><td>${numberWithCommas(
			chartData.certified.toFixed(2)
		)== 0 ? "NA" : chartData.certified.toFixed(2)}</td></tr>
    `);
		$("#table-11-tfoot").html(`
    <tr><td>Total</td><td>${Number(
			(
				chartData.breader +
				chartData.foundation +
				chartData.qcdc +
				chartData.certified
			).toFixed(2)
		)}</td></tr>
  `);

		const sparklineData = {
			breader: getTonsDataList("quantity_breeder_seed", "unit_breeder_seed"),
			foundation: getTonsDataList(
				"quantity_foundation_seed",
				"unit_foundation_seed"
			),
			qcdc: getTonsDataList("quantity_QDS", "unit_QDS"),
			certified: getTonsDataList(
				"quantity_certified_seed",
				"unit_certifiedseed"
			),
		};
		setTimeout(() => {
			this.initSparkLine("breeder-sparkLine", sparklineData.breader, "#d79494");
			this.initSparkLine(
				"foundation-sparkLine",
				sparklineData.foundation,
				"#7cb5ec"
			);
			this.initSparkLine(
				"certified-sparkLine",
				sparklineData.certified,
				"#FFCC66"
			);
			this.initSparkLine("qcdc-sparkLine", sparklineData.qcdc, "#6de38e");
		});
	}

	getCountryComparationInfo() {
		const selectedCrop = $("#country-comp-crop-name").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const getTonsData = (key, countryId, unit) => {
			return this.seedProducedData.tsps
				.filter((d) => search(d) && d.country_id === countryId)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const chartData = indexFilter.countries
			.map((data) => {
				const result = {
					countryId: data.country_id,
					countryName: data.country_name,
				};
				result.breaderCount = getTonsData(
					"quantity_breeder_seed",
					data.country_id,
					"unit_breeder_seed"
				);
				result.foundationCount = getTonsData(
					"quantity_foundation_seed",
					data.country_id,
					"unit_foundation_seed"
				);
				result.qcdcCount = getTonsData(
					"quantity_QDS",
					data.country_id,
					"unit_QDS"
				);
				result.certifiedCount = getTonsData(
					"quantity_certified_seed",
					data.country_id,
					"unit_certifiedseed"
				);
				return result;
			})
			.filter(
				(d) =>
					d.breaderCount || d.foundationCount || d.qcdcCount || d.certifiedCount
			);

		const allValues = chartData.map((d) =>
			Math.max(d.breaderCount, d.certifiedCount, d.foundationCount, d.qcdcCount)
		);
		const maxVal = Math.max(...allValues);

		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		// Highcharts.chart("mpr-mapComparisonofseedsproduced", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: "",
		// 	},
		// 	credits: {
		// 		enabled: false,
		// 	},
		// 	xAxis: {
		// 		categories: chartData.map((d) => d.countryName),
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: "Seeds Produced (Tons)",
		// 		},
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.0f}%)<br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
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

		// 	series: [
		// 		{
		// 			name: "Certified",
		// 			color: "#17A2B8",
		// 			data: chartData.map((d) => d.certifiedCount),
		// 		},
		// 		{
		// 			name: "Foundation",
		// 			color: "#FFCE56",
		// 			data: chartData.map((d) => d.foundationCount),
		// 		},
		// 		{
		// 			name: "QDS (TLS)",
		// 			color: "#7cb5ec",
		// 			data: chartData.map((d) => d.qcdcCount),
		// 		},
		// 		{
		// 			name: "Breeder",
		// 			color: "#d79494",
		// 			data: chartData.map((d) => d.breaderCount),
		// 		},
		// 	],
		// });

		Highcharts.chart("mpr-mapComparisonofseedsproduced-br", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#d79494"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				title: { text: "Country" },
				categories: chartData
					.filter((d) => d.breaderCount > 0)
					.map((d) => d.countryName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Breeder",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.breaderCount > 0)
						.map((d) => d.breaderCount),
				},
			],
		});
		Highcharts.chart("mpr-mapComparisonofseedsproduced-fn", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#7cb5ec"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				title: { text: "Country" },
				categories: chartData
					.filter((d) => d.foundationCount > 0)
					.map((d) => d.countryName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				// breaks: breakarray,
				// events: {
				// 	pointBreak: pointBreakColumn,
				// },
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
									breaks: [],
								});
							},
						},
					},
				},
			},
			series: [
				{
					name: "Foundation",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.foundationCount > 0)
						.map((d) => d.foundationCount),
				},
			],
		});
		Highcharts.chart("mpr-mapComparisonofseedsproduced-cr", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#FFCC66"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				title: { text: "Country" },
				categories: chartData
					.filter((d) => d.certifiedCount > 0)
					.map((d) => d.countryName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Certified",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.certifiedCount > 0)
						.map((d) => d.certifiedCount),
				},
			],
		});
		Highcharts.chart("mpr-mapComparisonofseedsproduced-qds", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#6de38e"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				title: { text: "Country" },
				categories: chartData
					.filter((d) => d.qcdcCount > 0)
					.map((d) => d.countryName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Foundation",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.qcdcCount > 0)
						.map((d) => d.qcdcCount),
				},
			],
		});

		// $("#table-2-tbody").html(
		// 	chartData
		// 		.map((e) => {
		// 			return `<tr>
		//     <td>${e.countryName}</td>
		//     <td>${numberWithCommas(e.breaderCount.toFixed(2))}</td>
		//     <td>${numberWithCommas(e.foundationCount.toFixed(2))}</td>
		//     <td>${numberWithCommas(e.qcdcCount.toFixed(2))}</td>
		//     <td>${numberWithCommas(e.certifiedCount.toFixed(2))}</td>
		//     <td style="font-weight: 600;">${numberWithCommas(
		// 				(
		// 					e.certifiedCount +
		// 					e.qcdcCount +
		// 					e.foundationCount +
		// 					e.breaderCount
		// 				).toFixed(2)
		// 			)}</td>
		//   </tr>`;
		// 		})
		// 		.join(`\n`)
		// );
		$("#table-2-tbody").html(
			chartData.filter(d=> d.breaderCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.countryName}</td>
            <td>${numberWithCommas(e.breaderCount.toFixed(2))}</td>
          </tr>`;
				})
				.join(`\n`)
		);
		$("#table-22-tbody").html(
			chartData.filter(d=> d.foundationCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.countryName}</td>
            <td>${numberWithCommas(e.foundationCount.toFixed(2))}</td>
          </tr>`;
				})
				.join(`\n`)
		);
		$("#table-23-tbody").html(
			chartData.filter(d=> d.certifiedCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.countryName}</td>
            <td>${numberWithCommas(e.certifiedCount.toFixed(2))}</td>
          </tr>`;
				})
				.join(`\n`)
		);
		$("#table-24-tbody").html(
			chartData.filter(d=> d.qcdcCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.countryName}</td>
            <td>${numberWithCommas(e.qcdcCount.toFixed(2))}</td>
          </tr>`;
				})
				.join(`\n`)
		);

		let tableFooter = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.breaderCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
`;
		let tableFooter22 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.foundationCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
`;
		let tableFooter23 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.certifiedCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
`;
		let tableFooter24 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.qcdcCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
`;



		$("#table-2-tfoot").html(tableFooter);
		$("#table-22-tfoot").html(tableFooter22);
		$("#table-23-tfoot").html(tableFooter23);
		$("#table-24-tfoot").html(tableFooter24);
	}

	getYearComparisonInfo() {
		const selectedCrop = $("#year-comp-crop-name").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const getTonsData = (key, unit, yearId) => {
			return this.seedProducedData.tsps
				.filter((d) => search(d) && d.year_id == yearId)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const chartData = indexFilter.dataViewYears
			.map((data) => {
				const result = { year: data.year };
				result.breaderCount = getTonsData(
					"quantity_breeder_seed",
					"unit_breeder_seed",
					data.year_id
				);
				result.foundationCount = getTonsData(
					"quantity_foundation_seed",
					"unit_foundation_seed",
					data.year_id
				);
				result.qcdcCount = getTonsData(
					"quantity_QDS",
					"unit_QDS",
					data.year_id
				);
				result.certifiedCount = getTonsData(
					"quantity_certified_seed",
					"unit_certifiedseed",
					data.year_id
				);
				return result;
			})
			.filter(
				(d) =>
					d.breaderCount || d.foundationCount || d.qcdcCount || d.certifiedCount
			);

		const allValues = chartData.map((d) =>
			Math.max(d.breaderCount, d.certifiedCount, d.foundationCount, d.qcdcCount)
		);
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		// Highcharts.chart("sp-year-crop-graph", {
		//   chart: {
		//     type: "column",
		//   },
		//   title: {
		//     text: "",
		//   },
		//   credits: {
		//     enabled: false,
		//   },
		//   xAxis: {
		//     categories: chartData.map((d) => d.year),
		//   },
		//   yAxis: {
		//     min: 0,
		//     title: {
		//       text: "Seeds Produced (Tons)",
		//     },
		//     breaks: breakarray,
		//     events: {
		//       pointBreak: pointBreakColumn,
		//     },
		//   },
		//   tooltip: {
		//     pointFormat:
		//       '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.0f}%)<br/>',
		//     shared: true,
		//   },
		//   plotOptions: {
		//     column: {
		//       stacking: "normal",
		//       dataLabels: {
		//         enabled: true,
		//         format: "{point.y:.2f}",
		//         style: { textOutline: false },
		//       },
		//       point: {
		//         events: {
		//           mouseOver: function () {
		//             const chart = this,
		//               yAxis = chart.series.yAxis;
		//             yAxis.update({
		//               breaks: [],
		//             });
		//           },
		//           mouseOut: function () {
		//             const chart = this,
		//               yAxis = chart.series.yAxis;
		//             yAxis.update({
		//               breaks: breakarray,
		//             });
		//           },
		//         },
		//       },
		//     },
		//   },

		//   series: [
		//     {
		//       name: "Certified",
		//       color: "#17A2B8",
		//       data: chartData.map((d) => d.certifiedCount),
		//     },
		//     {
		//       name: "Foundation",
		//       color: "#FFCE56",
		//       data: chartData.map((d) => d.foundationCount),
		//     },
		//     {
		//       name: "QDS (TLS)",
		//       color: "#7cb5ec",
		//       data: chartData.map((d) => d.qcdcCount),
		//     },
		//     {
		//       name: "Breeder",
		//       color: "#d79494",
		//       data: chartData.map((d) => d.breaderCount),
		//     },
		//   ],
		// });

		Highcharts.chart("sp-year-crop-graph", {
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
				categories: chartData.map((d) => d.year),
			},
			// xAxis: chartData.map((d) => d.year),
			yAxis: {
				title: {
					text: "Seed Production (Tons)",
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
					name: "Certified",
					color: "#17A2B8",
					data: chartData.map((d) => Number(d.certifiedCount.toFixed(2))),
				},
				{
					name: "Foundation",
					color: "#FFCE56",
					data: chartData.map((d) => Number(d.foundationCount.toFixed(2))),
				},
				{
					name: "Quality Declared Seeds/Truthfully Labelled Seeds",
					color: "#7cb5ec",
					data: chartData.map((d) => Number(d.qcdcCount.toFixed(2))),
				},
				{
					name: "Breeder",
					color: "#d79494",
					data: chartData.map((d) => Number(d.breaderCount.toFixed(2))),
				},
			],
		});

		$("#table-13-tbody").html(
			chartData
				.map((e) => {
					return `<tr>
            <td>${e.year}</td>
            <td>${numberWithCommas(e.breaderCount.toFixed(2))}</td>
            <td>${numberWithCommas(e.foundationCount.toFixed(2))}</td>
            <td>${numberWithCommas(e.qcdcCount.toFixed(2))}</td>
            <td>${numberWithCommas(e.certifiedCount.toFixed(2))}</td>
            <td style="font-weight: 600;">${numberWithCommas(
						(
							e.certifiedCount +
							e.qcdcCount +
							e.foundationCount +
							e.breaderCount
						).toFixed(2)
					)}</td>
          </tr>`;
				})
				.join(`\n`)
		);

		let tableFooter = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.breaderCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td><td>${chartData
					.map((e) => e.foundationCount)
					.reduce((a, b) => a + b , 0)
					.toFixed(2)}</td><td>${chartData
						.map((e) => e.qcdcCount)
						.reduce((a, b) => a + b , 0)
						.toFixed(2)}</td><td>${chartData
							.map((e) => e.certifiedCount)
							.reduce((a, b) => a + b , 0)
							.toFixed(2)}</td><td>${(
								chartData.map((e) => e.certifiedCount).reduce((a, b) => a + b , 0) +
								chartData.map((e) => e.qcdcCount).reduce((a, b) => a + b , 0) +
								chartData.map((e) => e.foundationCount).reduce((a, b) => a + b , 0) +
								chartData.map((e) => e.breaderCount).reduce((a, b) => a + b , 0)
							).toFixed(2)}</td></tr>
`;
		$("#table-13-tfoot").html(tableFooter);
	}
	//crp year wise comparison
	getYearCrpsInfo() {
		const key = this.getSearchCatKey("#year-crp-quantity_type");
		const unit = this.getSearchCatUnit("#year-crp-quantity_type");
		//console.log(key);
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.crps.forEach((c) => {
				let crpDataRecords = this.seedProducedData.tsp_crps
					.filter((e) => e.crp_id == c.crp_id)
					.map((e) => e.data_id);
				let spOption = {
					quantity_breeder_seed: this.seedProducedData.tsp_breeders,
					quantity_foundation_seed: this.seedProducedData.tsp_foundations,
					quantity_QDS: this.seedProducedData.tsp_qdcs,
					quantity_certified_seed: this.seedProducedData.tsp_certifieds,
				};
				let keyDatarecords = spOption[key].map((e) => e.data_id);
				let matchedRecords = this.seedProducedData.tsps.filter(
					(e) =>
						crpDataRecords.includes(e.data_id) &&
						keyDatarecords.includes(e.data_id) &&
						e.year_id == yr.year_id
				);
				result[c.crp_name] = matchedRecords
					.map((d) => {
						if (d[unit]?.toUpperCase() == "KG") {
							return parseFloat(d[key] || 0) / 1000;
						} else {
							return parseFloat(d[key] || 0);
						}
					})
					.filter((e) => !isNaN(e))
					.reduce((a, b) => a + b, 0);
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

		// Highcharts.chart("crp-year-wise-graph", {
		//   chart: { type: "column" },
		//   title: { text: "" },
		//   xAxis: { categories: chartData.map((e) => e.year) },
		//   yAxis: {
		//     min: 0,
		//     title: { text: "Seeds Produced (Tons)" },
		//     breaks: breakarray,
		//     events: {
		//       pointBreak: pointBreakColumn,
		//     },

		//   },
		//   credits: { enabled: false },
		//   tooltip: {
		//     pointFormat:
		//       '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %) <br/>',
		//     shared: true,
		//   },
		//   plotOptions: {
		//     column: {
		//       stacking: "normal",
		//       dataLabels: {
		//         enabled: true,
		//         format: "{point.y:.2f}",
		//         style: { textOutline: false },
		//       },
		//       point: {
		//         events: {
		//           mouseOver: function () {
		//             const chart = this,
		//               yAxis = chart.series.yAxis;
		//             yAxis.update({
		//               breaks: [],
		//             });
		//           },
		//           mouseOut: function () {
		//             const chart = this,
		//               yAxis = chart.series.yAxis;
		//             yAxis.update({
		//               breaks: breakarray,
		//             });
		//           },
		//         },
		//       },
		//     },
		//   },
		//   series: indexFilter.pi2020FilterData.crps.map((e) => {
		//     return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name]) };
		//   }),
		// });

		Highcharts.chart("crp-year-wise-graph", {
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
			// xAxis: chartData.map((d) => d.year),
			yAxis: {
				title: {
					text: "Seed Production (Tons)",
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
			series: indexFilter.pi2020FilterData.crps.map((e) => {
				return {
					name: e.crp_name,
					data: chartData.map((f) =>
						f[e.crp_name] ? Number(f[e.crp_name].toFixed(2)) : 0
					),
				};
			}),
		});

		$("#table-14-thead-row").html(
			`<th>CRP</th>` +
			chartData.map((e) => `<th>${e.year}</th>`) +
			`<th>Total</th>`
		);
		$("#table-14-tbody").html(
			indexFilter.pi2020FilterData.crps.map((e) => {
				let yValsHtml = chartData.map(
					(f) => `<td>${numberWithCommas(f[e.crp_name].toFixed(2)) ==0 ? "NA" : numberWithCommas(f[e.crp_name].toFixed(2))}</td>`
				);
				let yVals = chartData
					.map((f) => f[e.crp_name])
					.reduce((a, b) => a + b , 0)
					.toFixed(2);

				return `<tr><td>${e.crp_name}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : yVals}</td></tr>`;
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
			.reduce((a, b) => a + b , 0)
			.toFixed(2);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e)}</td>`);
		$("#table-14-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue}</td></tr>`
		);
	}
	//crp year wise comparison end

	getCountryCropWiseSeedProducedInfo() {
		const getTonsData = (key, cropId, unit) => {
			return this.seedProducedData.tsps
				.filter((d) => d.crop_id == cropId)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};
		const chartData = indexFilter.crops
			.map((data) => {
				const result = { cropId: data.crop_id, cropName: data.crop_name };
				result.breaderCount = Number(getTonsData(
					"quantity_breeder_seed",
					data.crop_id,
					"unit_breeder_seed"
				).toFixed(2));
				result.foundationCount = Number(getTonsData(
					"quantity_foundation_seed",
					data.crop_id,
					"unit_foundation_seed"
				).toFixed(2));
				result.qcdcCount = Number(getTonsData(
					"quantity_QDS",
					data.crop_id,
					"unit_QDS"
				).toFixed(2));
				result.certifiedCount = Number(getTonsData(
					"quantity_certified_seed",
					data.crop_id,
					"unit_certifiedseed"
				).toFixed(2));
				return result;
			})
			.filter(
				(d) =>
					d.breaderCount || d.foundationCount || d.qcdcCount || d.certifiedCount
			);

		const allValus = chartData.map((d) =>
			Math.max(d.breaderCount, d.foundationCount, d.qcdcCount, d.certifiedCount)
		);
		const maxVal = Math.max(...allValus);

		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("mpr-mapYieldonStation", {
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
				categories: chartData.map((d) => d.cropName),
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				// min: 0,
				// tickInterval: 2,
				title: {
					text: "Seed Production (Tons)",
				},
				labels: {
					overflow: "justify",
				},
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			tooltip: {
				enabled: true,
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						format: "{point.y:.2f}",
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
					name: "Certified",
					color: "#182C3C",
					data: chartData.map((d) => d.certifiedCount),
				},
				{
					name: "Foundation",
					color: "#2C5370",
					data: chartData.map((d) => d.foundationCount),
				},
				{
					name: "Quality Declared Seeds/Truthfully Labelled Seeds",
					color: "#1590EF",
					data: chartData.map((d) => d.qcdcCount),
				},
				{
					name: "Breeder",
					color: "#A8D9FF",
					data: chartData.map((d) => d.breaderCount),
				},
			],
		});

		$("#table-3-tbody").html(
			chartData
				.map((e) => {
					return `<tr>
            <td>${e.cropName}</td>
            <td>${numberWithCommas(e.breaderCount.toFixed(2)) == 0 ? "NA" : numberWithCommas(e.breaderCount.toFixed(2))}</td>
            <td>${numberWithCommas(e.foundationCount.toFixed(2)) == 0 ? "NA" : numberWithCommas(e.foundationCount.toFixed(2))}</td>
            <td>${numberWithCommas(e.qcdcCount.toFixed(2)) == 0 ? "NA" : numberWithCommas(e.qcdcCount.toFixed(2))}</td>
            <td>${numberWithCommas(e.certifiedCount.toFixed(2)) == 0 ? "NA" : numberWithCommas(e.certifiedCount.toFixed(2))}</td> 
            <td style="font-weight: 600;">${numberWithCommas(
						(
							e.certifiedCount +
							e.qcdcCount +
							e.foundationCount +
							e.breaderCount
						).toFixed(2)
					)}</td> 
          </tr>`;
				})
				.join(`\n`)
		);

		let tdTotal = (chartData.map((e) => e.certifiedCount).reduce((a, b) => a + b , 0) + chartData.map((e) => e.qcdcCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.foundationCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.breaderCount).reduce((a, b) => a + b , 0)).toFixed(2)

		let tableFooter = `
    <tr><td>Total</td>
	<td>${chartData.map((e) => e.breaderCount).reduce((a, b) => a + b , 0).toFixed(2)}</td>
	<td>${chartData.map((e) => e.foundationCount).reduce((a, b) => a + b , 0).toFixed(2)}</td>
	<td>${chartData.map((e) => e.qcdcCount).reduce((a, b) => a + b , 0).toFixed(2)}</td>
	<td>${chartData.map((e) => e.certifiedCount).reduce((a, b) => a + b , 0).toFixed(2)}</td>
	<td>${tdTotal == 0 ? "NA" : tdTotal}</td></tr>
`;
		$("#table-3-tfoot").html(tableFooter);
	}

	getProducerWiseSeedInfo() {
		const key = this.getSearchCatKey("#producers-cat-name");
		const unit = this.getSearchCatUnit("#producers-cat-name");
		const generateChartValue = (keycategory_id) => {
			let spOption = {
				quantity_breeder_seed:this.getSearchCatDatabyVal(1),
				quantity_foundation_seed: this.getSearchCatDatabyVal(2),
				quantity_QDS: this.getSearchCatDatabyVal(4),
				quantity_certified_seed: this.getSearchCatDatabyVal(3),
				all: this.getSearchCatDatabyVal(0),
			};
			

			let keyDatarecords = spOption[key]
				.filter((e) => e.key_producer_categories.includes(keycategory_id))
				.map((e) => e.data_id);
			return this.tspsData
				.filter((d) => keyDatarecords.includes(d.data_id))
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};



		// generateChartValue();
		const chartData = indexFilter.pi2020FilterData.kpcs
			.map((data) => {
				const result = {
					categoryId: data.kpc_id,
					categoryName: data.key_producer_category,
				};
				result.count = generateChartValue(data.kpc_id);
				return result;
			})
			.filter((d) => d.count)
			.sort((v1, v2) => v1.categoryName.localeCompare(v2.categoryName));
		// const chartData = indexFilter.pi2020FilterData.keycategories
		// 	.map((data) => {
		// 		const result = {
		// 			categoryId: data.keycategory_id,
		// 			categoryName: data.keycategory,
		// 		};
		// 		result.count = generateChartValue(data.keycategory_id);
		// 		return result;
		// 	})
		// 	.filter((d) => d.count)
		// 	.sort((v1, v2) => v1.categoryName.localeCompare(v2.categoryName));


		const maxVal = Math.max(...chartData.map((d) => d.count));

		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		// Highcharts.chart("mpr-mapProducercategorywise", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: null,
		// 	},
		// 	subtitle: {
		// 		text: null,
		// 	},
		// 	colors: ["#d79494"],
		// 	credits: {
		// 		enabled: false,
		// 	},
		// 	legend: {
		// 		y: 10,
		// 	},
		// 	xAxis: {
		// 		categories: chartData.map((d) => d.categoryName),
		// 		title: {
		// 			text: null,
		// 		},
		// 	},
		// 	yAxis: {
		// 		//opposite: true,

		// 		title: {
		// 			text: "Seeds produced (Tons)",
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
		// 		pointFormat: "<b>{point.y:.2f}</b>",
		// 	},
		// 	plotOptions: {
		// 		series: {
		// 			dataLabels: {
		// 				enabled: true,
		// 				format: "{point.y:.2f}",
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
		// 			data: chartData.map((d) => d.count),
		// 		},
		// 	],
		// });


		Highcharts.chart('mpr-mapProducercategorywise', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: null
			},
			colors: ["#d79494", "#7cb5ec", "#FFCE56","#94D794","#FF56DB"],
			tooltip: {
				pointFormat: '<b>{point.percentage:.1f}%</b>'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			// plotOptions: {
			// 	pie: {
			// 		allowPointSelect: true,
			// 		cursor: 'pointer',
			// 		dataLabels: {
			// 			enabled: true,
			// 			format: '<b>{point.name}</b>: {point.percentage:.1f} %'
			// 		}
			// 	}
			// },
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: "pointer",
					dataLabels: {
						enabled: true,
						// format: "<b>{point.name}</b>: {point.percentage:.1f} %",
						format: "<b>{point.name} ({point.y})</b>",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			series: [{
				// name: 'Brands',
				colorByPoint: true,
				data: chartData.map(d=> {
					const result = {}
					result.name = d.categoryName,
					result.y = Number((d.count).toFixed(2))
					return result}),
	
			}]
		});

		let tfTotal = chartData.map((e) => e.count).reduce((a, b) => a + b , 0).toFixed(2)

		$("#table-5-tbody").html(
			chartData
				.map(
					(e) =>
						`<tr><td>${e.categoryName}</td><td>${numberWithCommas(e.count.toFixed(2)) == 0 ? "NA" : numberWithCommas(e.count.toFixed(2))}</td></tr>`
				)
				.join(`\n`)
		);
		$("#table-5-tfoot").html(
			`<tr><td>Total</td><td>${tfTotal == 0 ? "NA" : tfTotal}</td></tr>`
		);
	}

	getCropWiseSeedProducedInfo() {
		const generateChartValue = (crpId, key, unit) => {
			let crpDataRecords = this.seedProducedData.tsp_crps
				.filter((e) => e.crp_id == crpId)
				.map((e) => e.data_id);
			return this.seedProducedData.tsps
				.filter((e) => crpDataRecords.includes(e.data_id))
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const chartData = indexFilter.crps
			.map((data) => {
				const result = { crpId: data.crp_id, crpName: data.crp_name };
				result.breaderCount = generateChartValue(
					data.crp_id,
					"quantity_breeder_seed",
					"unit_breeder_seed"
				);
				result.foundationCount = generateChartValue(
					data.crp_id,
					"quantity_foundation_seed",
					"unit_foundation_seed"
				);
				result.qcdcCount = generateChartValue(
					data.crp_id,
					"quantity_QDS",
					"unit_QDS"
				);
				result.certifiedCount = generateChartValue(
					data.crp_id,
					"quantity_certified_seed",
					"unit_certifiedseed"
				);
				return result;
			})
			.filter(
				(d) =>
					d.breaderCount || d.foundationCount || d.qcdcCount || d.certifiedCount
			);

		const allVal = chartData.map((d) =>
			Math.max(d.breaderCount, d.foundationCount, d.qcdcCount, d.certifiedCount)
		);
		const maxVal = Math.max(...allVal);

		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		// Highcharts.chart("mpr-mapCrpwiseseedproduced", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: "",
		// 	},
		// 	xAxis: {
		// 		categories: chartData.map((d) => d.crpName),
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: "Seeds produced (Tons)",
		// 		},
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.0f}%)<br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
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

		// 	series: [
		// 		{
		// 			name: "Certified",
		// 			color: "#17A2B8",
		// 			data: chartData.map((d) => d.certifiedCount),
		// 		},
		// 		{
		// 			name: "Foundation",
		// 			color: "#FFCE56",
		// 			data: chartData.map((d) => d.foundationCount),
		// 		},
		// 		{
		// 			name: "Quality Declared Seeds/Truthfully Labelled Seeds",
		// 			color: "#7cb5ec",
		// 			data: chartData.map((d) => d.qcdcCount),
		// 		},
		// 		{
		// 			name: "Breeder",
		// 			color: "#d79494",
		// 			data: chartData.map((d) => d.breaderCount),
		// 		},
		// 	],
		// });

		Highcharts.chart("mpr-mapCrpwiseseedproduced-br", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#d79494"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.breaderCount > 0)
					.map((d) => d.crpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Breeder",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.breaderCount > 0)
						.map((d) => d.breaderCount),
				},
			],
		});
		Highcharts.chart("mpr-mapCrpwiseseedproduced-fn", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#7cb5ec"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.foundationCount > 0)
					.map((d) => d.crpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				// breaks: breakarray,
				// events: {
				// 	pointBreak: pointBreakColumn,
				// },
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
									breaks: [],
								});
							},
						},
					},
				},
			},
			series: [
				{
					name: "Foundation",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.foundationCount > 0)
						.map((d) => d.foundationCount),
				},
			],
		});
		Highcharts.chart("mpr-mapCrpwiseseedproduced-cr", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#FFCC66"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.certifiedCount > 0)
					.map((d) => d.crpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Foundation",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.certifiedCount > 0)
						.map((d) => d.certifiedCount),
				},
			],
		});
		Highcharts.chart("mpr-mapCrpwiseseedproduced-qds", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#6de38e"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.qcdcCount > 0)
					.map((d) => d.crpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Foundation",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.qcdcCount > 0)
						.map((d) => d.qcdcCount),
				},
			],
		});

		$("#table-6-tbody").html(
			chartData.filter(d=> d.breaderCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.crpName}</td>
            <td>${numberWithCommas(e.breaderCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);
		$("#table-62-tbody").html(
			chartData.filter(d=> d.foundationCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.crpName}</td>
            <td>${numberWithCommas(e.foundationCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);
		$("#table-63-tbody").html(
			chartData.filter(d=> d.certifiedCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.crpName}</td>
            <td>${numberWithCommas(e.certifiedCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);
		
		$("#table-64-tbody").html(
			chartData.filter(d=> d.qcdcCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.crpName}</td>
            <td>${numberWithCommas(e.qcdcCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);

		let tableFooter = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.breaderCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;
		let tableFooter2 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.foundationCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;
		let tableFooter3 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.certifiedCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;
		let tableFooter4 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.qcdcCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;



		$("#table-6-tfoot").html(tableFooter);
		$("#table-62-tfoot").html(tableFooter2);
		$("#table-63-tfoot").html(tableFooter3);
		$("#table-64-tfoot").html(tableFooter4);
	}

	getResearchWiseSeedProducedInfo() {
		const generateChartValue = (rpId, key, unit) => {
			return this.seedProducedData.tsps
				.filter((e) => e.rp_id == rpId)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const chartData = indexFilter.reasearchPrograms
			.map((data) => {
				const result = { rpId: data.rp_id, rpName: data.rp_name };
				result.breaderCount = generateChartValue(
					data.rp_id,
					"quantity_breeder_seed",
					"unit_breeder_seed"
				);
				result.foundationCount = generateChartValue(
					data.rp_id,
					"quantity_foundation_seed",
					"unit_foundation_seed"
				);
				result.qcdcCount = generateChartValue(
					data.rp_id,
					"quantity_QDS",
					"unit_QDS"
				);
				result.certifiedCount = generateChartValue(
					data.rp_id,
					"quantity_certified_seed",
					"unit_certifiedseed"
				);
				return result;
			})
			.filter(
				(d) =>
					d.breaderCount || d.foundationCount || d.qcdcCount || d.certifiedCount
			);
		const allVal = chartData.map((d) =>
			Math.max(d.breaderCount, d.foundationCount, d.qcdcCount, d.certifiedCount)
		);
		const maxVal = Math.max(...allVal);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];
		// Highcharts.chart("mpr-mapResearchprogramwise", {
		// 	chart: {
		// 		type: "column",
		// 	},
		// 	title: {
		// 		text: "",
		// 	},
		// 	xAxis: {
		// 		categories: chartData.map((d) => d.rpName),
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: "Seeds Produced (Tons)",
		// 		},
		// 		breaks: breakarray,
		// 		events: {
		// 			pointBreak: pointBreakColumn,
		// 		},
		// 	},
		// 	tooltip: {
		// 		pointFormat:
		// 			'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.0f}%)<br/>',
		// 		shared: true,
		// 	},
		// 	plotOptions: {
		// 		column: {
		// 			stacking: "normal",
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

		// 	series: [
		// 		{
		// 			name: "Certified",
		// 			color: "#17A2B8",
		// 			data: chartData.map((d) => d.certifiedCount),
		// 		},
		// 		{
		// 			name: "Foundation",
		// 			color: "#FFCE56",
		// 			data: chartData.map((d) => d.foundationCount),
		// 		},
		// 		{
		// 			name: "Quality Declared Seeds/Truthfully Labelled Seeds",
		// 			color: "#7cb5ec",
		// 			data: chartData.map((d) => d.qcdcCount),
		// 		},
		// 		{
		// 			name: "Breeder",
		// 			color: "#d79494",
		// 			data: chartData.map((d) => d.breaderCount),
		// 		},
		// 	],
		// });

		Highcharts.chart("mpr-mapResearchprogramwise-br", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#d79494"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.breaderCount > 0)
					.map((d) => d.rpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Breeder",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.breaderCount > 0)
						.map((d) => d.breaderCount),
				},
			],
		});
		Highcharts.chart("mpr-mapResearchprogramwise-fn", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#7cb5ec"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.foundationCount > 0)
					.map((d) => d.rpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				// breaks: breakarray,
				// events: {
				// 	pointBreak: pointBreakColumn,
				// },
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
									breaks: [],
								});
							},
						},
					},
				},
			},
			series: [
				{
					name: "Foundation",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.foundationCount > 0)
						.map((d) => d.foundationCount),
				},
			],
		});
		Highcharts.chart("mpr-mapResearchprogramwise-cr", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#FFCC66"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.certifiedCount > 0)
					.map((d) => d.rpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Certified",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.certifiedCount > 0)
						.map((d) => d.certifiedCount),
				},
			],
		});
		Highcharts.chart("mpr-mapResearchprogramwise-qds", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#6de38e"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData
					.filter((d) => d.qcdcCount > 0)
					.map((d) => d.rpName),
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					name: "Quality Declared Seeds/Truthfully Labelled Seeds",
					maxPointWidth: 30,
					data: chartData
						.filter((d) => d.qcdcCount > 0)
						.map((d) => d.qcdcCount),
				},
			],
		});

		$("#table-7-tbody").html(
			chartData.filter(d=> d.breaderCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.rpName}</td>
            <td>${numberWithCommas(e.breaderCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);
		$("#table-72-tbody").html(
			chartData.filter(d=> d.foundationCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.rpName}</td>
            <td>${numberWithCommas(e.foundationCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);
		$("#table-73-tbody").html(
			chartData.filter(d=> d.certifiedCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.rpName}</td>
            <td>${numberWithCommas(e.certifiedCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);
		$("#table-74-tbody").html(
			chartData.filter(d=> d.qcdcCount > 0)
				.map((e) => {
					return `<tr>
            <td>${e.rpName}</td>
            <td>${numberWithCommas(e.qcdcCount.toFixed(2))}</td>
          </tr>`;
				})
				.join("\n")
		);

		let tableFooter = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.breaderCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;
		let tableFooter2 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.foundationCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;
		let tableFooter3 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.certifiedCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;
		let tableFooter4 = `
    <tr><td>Total</td><td>${chartData
				.map((e) => e.qcdcCount)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>
    `;


		$("#table-7-tfoot").html(tableFooter);
		$("#table-72-tfoot").html(tableFooter2);
		$("#table-73-tfoot").html(tableFooter3);
		$("#table-74-tfoot").html(tableFooter4);
	}

	getCropAndCountryWiseSeedInfo() {
		// #country-corp-sanky-cat-name
		const key = this.getSearchCatKey("#country-corp-sanky-cat-name");
		const unit = this.getSearchCatUnit("#country-corp-sanky-cat-name");

		const generateSankeyValue = (country_id, crop_id) => {
			return this.seedProducedData.tsps
				.filter((d) => d.crop_id == crop_id && d.country_id == country_id)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};
		const chartData = indexFilter.crops
			.map((crop) => {
				return indexFilter.countries.map((country) => {
					return {
						from: crop.crop_name,
						to: country.country_name,
						value: generateSankeyValue(country.country_id, crop.crop_id),
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"mpr-mapCountryCropwise",
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
			chart.dataFields.color = "nodeColor";

			chart.paddingRight = 300;
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
			chart.exporting.filePrefix = "mapCountryCropwise";

		});
	}

	getCropWiseSdgsContribution() {
		const chartData = indexFilter.crops
			.map((crop) => {
				return indexFilter.sdgs.map((sdg) => {
					const data = this.getSearchCatData(
						"#crop-sdg-contribution-cat-name"
					).filter(
						(d) =>
							d.tsp.some((e) => e.crop_id == crop.crop_id) &&
							d.tspSdgs.some((e) => e.sdg_id == sdg.sdg_id)
					);
					return {
						from: crop.crop_name,
						to: sdg.sdg_name,
						value: data.length,
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value);
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("mpr-flowdiaChart", am4charts.SankeyDiagram);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.data = chartData;

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
			chart.exporting.filePrefix = "flowdiaChart";
		});
	}

	generateCrpWiseCropInfo() {
		const chartData = indexFilter.crops
			.map((crop) => {
				return indexFilter.crps.map((crp) => {
					const data = this.getSearchCatData("#crop-prod-cat-name").filter(
						(d) =>
							d.tsp.some((e) => e.crop_id == crop.crop_id) &&
							d.crps.some((e) => e.crp_id == crp.crp_id)
					);
					return {
						from: crop.crop_name,
						to: crp.crp_name,
						value: data.length,
						width: 10,
					};
				});
			})
			.flat()
			.filter((d) => d.value);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"mpr-ContributiontoCRPs",
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
			chart.exporting.filePrefix = "ContributiontoCRPs";
		});
	}

	getScientificPublications() {
		// const scientificData = this.getSearchCatData(
		//   "#scientific-publicatin-cat-name"
		// );
		// const chartData = { y: 0, n: 0, up: 0 };
		// chartData.y = scientificData.filter((d) =>
		//   d.tsp.some((e) => e.scientific_publications == 1)
		// ).length;
		// chartData.n = scientificData.filter((d) =>
		//   d.tsp.some((e) => e.scientific_publications == 2)
		// ).length;
		// chartData.up = scientificData.filter((d) =>
		//   d.tsp.some((e) => e.scientific_publications == 3)
		// ).length;

		const key = this.getSearchCatKey("#scientific-publicatin-cat-name");
		const generateChartValue = (key) => {
			let spOption = {
				quantity_breeder_seed: this.seedProducedData.tsp_breeders,
				quantity_foundation_seed: this.seedProducedData.tsp_foundations,
				quantity_QDS: this.seedProducedData.tsp_qdcs,
				quantity_certified_seed: this.seedProducedData.tsp_certifieds,
			};

			let spDataRecords = spOption[key].map((e) => e.data_id);
			return indexFilter.pi2020FilterData.scientific_publications.map((e) => {
				return {
					name: e.scientific_publications,
					y: this.seedProducedData.tsps.filter(
						(f) =>
							f.scientific_publications &&
							f.scientific_publications == e.sp_id &&
							spDataRecords.includes(f.data_id)
					).length,
				};
			});
		};
		let chartData = generateChartValue(key);
		//console.log(chartData)
		// scientific_publications

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
			colors: ["#d79494", "#7cb5ec", "#FFCE56"],
			credits: {
				enabled: false,
			},
			legend: {
				enabled: true,
			},
			plotOptions: {
				pie: {
					// allowPointSelect: false,
					//cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: "{point.name}: {point.percentage:.2f} % ({point.y})  ",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %)<br/>',
			},
			series: [
				{
					name: "Scientific Publications",
					colorByPoint: true,
					data: chartData,
				},
			],
		});

		$("#table-10-tbody").html(
			chartData.map(
				(e) => `<tr><td>${e.name}</td><td>${numberWithCommas(e.y) == 0 ? "NA" : numberWithCommas(e.y)}</td></tr>`
			)
		);

		let tfData = chartData.map((e) => e.y).reduce((a, b) => a + b , 0).toFixed(2)
		$("#table-10-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	generateCountriesMap() {
		const key = this.getSearchCatKey("#map-cat-name");
		const unit = this.getSearchCatUnit("#map-cat-name");
		const generateMapValue = (country_id) => {
			return this.seedProducedData.tsps
				.filter((d) => d.country_id == country_id)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const mapData = indexFilter.countries
			.map((data) => {
				let result = {
					id: data.country_code,
					name: data.country_name,
					color: "lightblue",
				};
				result.value = generateMapValue(data.country_id);
				return result;
			})
			.filter((d) => d.value > 0);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"mpr-mapSeedsproducedacrosscountries",
				am4maps.MapChart
			);
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
			label.fontSize = 14;
			// label.adapter.add("dy", function (dy, target) {
			// 	var circle = target.parent.children.getIndex(0);
			// 	return circle.pixelRadius;
			// });

			chart.exporting.filePrefix = "seeds-produced-countries";
			exportAmchart('dwn-img-1',chart)
		});

		$("#table-1-tbody").html(
			mapData.filter(d=> d.value > 0).map(
				(e) =>
					`<tr><td>${e.name}</td><td>${numberWithCommas(
						e.value.toFixed(2)
					)}</td></tr>`
			)
		);
		$("#table-1-tfoot").html(
			`<tr><td>Total</td><td>${mapData
				.map((e) => e.value)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>`
		);
	}
	//contries year wise comparison
	getYearContriesInfo() {
		const key = this.getSearchCatKey("#year-countries-quantity-type");
		//console.log(key);
		const unit = this.getSearchCatUnit("#year-countries-quantity-type");
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				//	let crpDataRecords = this.seedProducedData.tsp_crps.filter(e => e.crp_id == c.crp_id).map(e => e.data_id);
				let spOption = {
					quantity_breeder_seed: this.seedProducedData.tsp_breeders,
					quantity_foundation_seed: this.seedProducedData.tsp_foundations,
					quantity_QDS: this.seedProducedData.tsp_qdcs,
					quantity_certified_seed: this.seedProducedData.tsp_certifieds,
				};
				let keyDatarecords = spOption[key].map((e) => e.data_id);
				let matchedRecords = this.seedProducedData.tsps.filter(
					(e) =>
						e.country_id == c.country_id &&
						keyDatarecords.includes(e.data_id) &&
						e.year_id == yr.year_id
				);
				result[c.country_name] = matchedRecords
					.map((d) => {
						if (d[unit]?.toUpperCase() == "KG") {
							return parseFloat(d[key] || 0) / 1000;
						} else {
							return parseFloat(d[key] || 0);
						}
					})
					.filter((e) => !isNaN(e))
					.reduce((a, b) => a + b, 0);
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

		// Highcharts.chart("contry-year-wise-graph", {
		//   chart: { type: "column" },
		//   title: { text: "" },
		//   xAxis: {
		//     categories: chartData
		//       .filter(
		//         (e) =>
		//           !Object.keys(e)
		//             .filter((e) => e != "year")
		//             .map((f) => e[f])
		//             .every((f) => f == 0)
		//       )
		//       .map((e) => e.year),
		//   },
		//   yAxis: {
		//     min: 0,
		//     title: { text: "Seeds Produced (Tons)" },
		//     breaks: breakarray,
		//     events: {
		//       pointBreak: pointBreakColumn,
		//     },
		//   },
		//   credits: { enabled: false },
		//   tooltip: {
		//     pointFormat:
		//       '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.2f} %) <br/>',
		//     shared: true,
		//   },
		//   plotOptions: {
		//     column: {
		//       stacking: "normal",
		//       dataLabels: {
		//         enabled: true,
		//         format: "{point.y:.2f}",
		//         style: { textOutline: false },
		//       },
		//       point: {
		//         events: {
		//           mouseOver: function () {
		//             const chart = this,
		//               yAxis = chart.series.yAxis;
		//             yAxis.update({
		//               breaks: [],
		//             });
		//           },
		//           mouseOut: function () {
		//             const chart = this,
		//               yAxis = chart.series.yAxis;
		//             yAxis.update({
		//               breaks: breakarray,
		//             });
		//           },
		//         },
		//       },
		//     },
		//   },
		//   series: indexFilter.pi2020FilterData.countries
		//     .map((c) => {
		//       let result = {
		//         name: c.country_name,
		//         data: chartData
		//           .filter((e) => e[c.country_name] > 0)
		//           .map((e) => e[c.country_name]),
		//       };
		//       return result;
		//     })
		//     .filter(
		//       (e) =>
		//         !Object.keys(e.data)
		//           .map((f) => e[f])
		//           .every((f) => f == 0)
		//     ),
		// });

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

			$("#contry-year-wise-graph").css("height", serz.length * 3 + "em");

		Highcharts.chart("contry-year-wise-graph", {
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
					.filter(
						(e) =>
							!Object.keys(e)
								.filter((e) => e != "year")
								.map((f) => e[f])
								.every((f) => f == 0)
					)
					.map((e) => e.year),
			},
			// xAxis: chartData.map((d) => d.year),
			yAxis: {
				title: {
					text: "Seed Production (Tons)",
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

		$("#table-16-thead-row").html(
			`<th>Country</th>` +
			chartData.map((e) => `<th>${e.year}</th>`) +
			`<th>Total</th>`
		);
		$("#table-16-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${numberWithCommas(f[e.country_name].toFixed(2)) == 0 ? "NA" : numberWithCommas(f[e.country_name].toFixed(2))}</td>`
					);
					let yVals = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b , 0)
						.toFixed(2);

					return `<tr><td>${e.country_name}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : yVals}</td></tr>`;
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
			.reduce((a, b) => a + b , 0)
			.toFixed(2);
		//console.log(ttvalue);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e)}</td>`);
		$("#table-16-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue == 0 ? "NA" : numberWithCommas(ttvalue) }</td></tr>`
		);
	}
	//contries year wise comparison end

	generateSeedProduceBreederChart() {
		const key = this.getSearchCatKey("#year-cat-name");
		const generateChartValue = (year_id) => {
			let spOption = {
				quantity_breeder_seed: this.seedProducedData.tsp_breeders,
				quantity_foundation_seed: this.seedProducedData.tsp_foundations,
				quantity_QDS: this.seedProducedData.tsp_qdcs,
				quantity_certified_seed: this.seedProducedData.tsp_certifieds,
			};

			//let keyDatarecords = spOption[key].filter(e => e.key_producer_category == year_id).map(e => e.data_id);
			let keyDatarecords = spOption[key].map((e) => e.data_id);
			return (
				this.seedProducedData.tsps
					.filter(
						(d) => keyDatarecords.includes(d.data_id) && d.year_id == year_id
					)
					.map((d) => parseFloat(d[key] || 0))
					.filter((d) => !isNaN(d))
					.reduce((v1, v2) => v1 + v2, 0) / 1000
			);
		};

		generateChartValue();
		const chartData = indexFilter.years
			.map((data) => {
				const result = {
					categoryId: data.year_id,
					categoryName: data.year,
				};
				result.count = generateChartValue(data.year_id);
				return result;
			})
			.filter((d) => d.count)
			.sort((v1, v2) => v1.categoryName.localeCompare(v2.categoryName));
		// console.log(chartData);

		const maxVal = Math.max(...chartData.map((e) => e.count));
		const breakarray = [
			{
				from: (maxVal * 1) / 100,
				to: (maxVal * 99) / 100,
			},
		];

		Highcharts.chart("mpr-yearSeedsproduced", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: chartData.map((d) => d.categoryName),
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,

				title: {
					text: "Seed Production (Tons)",
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
				pointFormat: "<b>{point.y:2f}</b>",
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						format: "{point.y:.2f}",
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
					name: "",
					data: chartData.map((d) => d.count),
				},
			],
		});

		$("#table-12-tbody").html(
			chartData
				.map(
					(e) =>
						`<tr><td>${e.categoryName}</td><td>${numberWithCommas(
							e.count.toFixed(2)
						)}</td></tr>`
				)
				.join(`\n`)
		);
	}

	generateSeedProduceBreederStackedChart() {
		const getTonsData = (key, unit, yearId) => {
			return this.seedProducedData.tsps
				.filter((d) => d.year_id == yearId)
				.map((d) => {
					if (d[unit]?.toUpperCase() == "KG") {
						return parseFloat(d[key] || 0) / 1000;
					} else {
						return parseFloat(d[key] || 0);
					}
				})
				.filter((d) => !isNaN(d))
				.reduce((v1, v2) => v1 + v2, 0);
		};

		const chartData = indexFilter.dataViewYears
			.map((data) => {
				const result = { year: data.year };
				result.breaderCount = getTonsData(
					"quantity_breeder_seed",
					"unit_breeder_seed",
					data.year_id
				);
				result.foundationCount = getTonsData(
					"quantity_foundation_seed",
					"unit_foundation_seed",
					data.year_id
				);
				result.qcdcCount = getTonsData(
					"quantity_QDS",
					"unit_QDS",
					data.year_id
				);
				result.certifiedCount = getTonsData(
					"quantity_certified_seed",
					"unit_certifiedseed",
					data.year_id
				);
				return result;
			})
			.filter(
				(d) =>
					d.breaderCount || d.foundationCount || d.qcdcCount || d.certifiedCount
			);

		const allValues = chartData.map((d) =>
			Math.max(d.breaderCount, d.certifiedCount, d.foundationCount, d.qcdcCount)
		);
		const maxVal = Math.max(...allValues);
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("mpr-yearSeedsproduced-stacked", {
			chart: {
				type: "column",
			},
			title: {
				text: "",
			},
			credits: {
				enabled: false,
			},
			xAxis: {
				categories: chartData.map((d) => d.year),
			},
			yAxis: {
				min: 0,
				title: {
					text: "Seed Production (Tons)",
				},
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.2f}</b> ({point.percentage:.0f}%)<br/>',
				shared: true,
			},
			plotOptions: {
				column: {
					stacking: "normal",
					dataLabels: {
						enabled: true,
						format: "{point.y:.2f}",
						style: { textOutline: false },
					},
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
					name: "Certified",
					color: "#17A2B8",
					data: chartData.map((d) => d.certifiedCount),
				},
				{
					name: "Foundation",
					color: "#FFCE56",
					data: chartData.map((d) => d.foundationCount),
				},
				{
					name: "Quality Declared Seeds/Truthfully Labelled Seeds",
					color: "#7cb5ec",
					data: chartData.map((d) => d.qcdcCount),
				},
				{
					name: "Breeder",
					color: "#d79494",
					data: chartData.map((d) => d.breaderCount),
				},
			],
		});

		const setDecimal = (p) => {
			if (p) {
				return Number(p.toFixed(2));
			} else {
				return "NA";
			}
		};

		$("#table-15-tbody").html(
			chartData
				.map((e) => {
					return `<tr><td>${e.year}</td><td>${setDecimal(
						e.breaderCount
					)}</td><td>${setDecimal(e.foundationCount)}</td><td>${setDecimal(
						e.qcdcCount
					)}</td><td>${setDecimal(e.certifiedCount)}</td></tr>`;
				})
				.join(`\n`)
		);
	}

	//generateCrpWiseHybridVarietyByReleaseChart end

	getHtmlActions() {
		this.getHtmlActionForSeedProduced();
		this.getHtmlActionForCountryComparation();

		this.getHtmlActionForYearComparison();
		this.getHtmlActionForYearComparisonBR();
		this.getHtmlActionForYearComparisonFN();
		this.getHtmlActionForYearComparisonCF();
		this.getHtmlActionForYearComparisonQD();

		this.getHtmlActionForProducerWiseSeed();
		this.getHtmlActionForCropAndCountryWiseSeedInfo();
		this.getHtmlActionForCropWiseSdgsContribution();
		this.getHtmlActionForCrpWiseCrop();
		this.getHtmlActionForScientificPublications();
		this.getHtmlActionForMap();
		this.getHtmlActionForBreed();
		this.getHtmlActionForBreedList();
		this.getHtmlActionForBreederOptionList();
	}

	getSearchCatData(selector) {
		//  const val =  $('#producer-cat-name').data('value');
		const val = $(selector).data("value");
		let ids = [];

		if (val == 1) {
			ids = this.seedProducedData.tsp_breeders.map(d => d.data_id)
			// return this.breaderData;
		} else if (val == 2) {
			ids = this.seedProducedData.tsp_foundations.map(d => d.data_id)
			// return this.foundationData;
		} else if (val == 3) {
			ids = this.seedProducedData.tsp_certifieds.map(d => d.data_id)
			// return this.certifiedData;
		} else if (val == 4) {
			ids = this.seedProducedData.tsp_qdcs.map(d => d.data_id)
			// return this.qcdcData;
		} else {
			return this.tspsData;
		} 
		return this.tspsData.filter(d => ids.includes(d.data_id));
	}

	getSearchCatDatabyVal(val) {
		//  const val =  $('#producer-cat-name').data('value');
		// const val = $(selector).data("value");
		let ids = [];

		if (val == 1) {
			ids = this.seedProducedData.tsp_breeders.map(d => d.data_id)
			// return this.breaderData;
		} else if (val == 2) {
			ids = this.seedProducedData.tsp_foundations.map(d => d.data_id)
			// return this.foundationData;
		} else if (val == 3) {
			ids = this.seedProducedData.tsp_certifieds.map(d => d.data_id)
			// return this.certifiedData;
		} else if (val == 4) {
			ids = this.seedProducedData.tsp_qdcs.map(d => d.data_id)
			// return this.qcdcData;
		} else {
			return this.tspsData;
		} 
		return this.tspsData.filter(d => ids.includes(d.data_id));
	}

	getSearchCatKey(selector) {
		const val = $(selector).data("value");
		if (val == 1) {
			return "quantity_breeder_seed";
		} else if (val == 3) {
			return "quantity_foundation_seed";
		} else if (val == 2) {
			return "quantity_certified_seed";
		} else if (val == 4) {
			return "quantity_QDS";
		} else {
			return "all";
		}
	}

	getSearchCatUnit(selector) {
		const val = $(selector).data("value");
		if (val == 1) {
			return "unit_breeder_seed";
		} else if (val == 3) {
			return "unit_foundation_seed";
		} else if (val == 2) {
			return "unit_certifiedseed";
		} else if (val == 4) {
			return "unit_QDS";
		} else {
			return "unit";
		}
	}

	generteCropList(className) {
		return indexFilter.crops
			.map((d, i) => {
				let all = "";
				if (i == 0) {
					all = `<a class="dropdown-item ${className}" data-value="0" data-label="All Crops"><i class=""></i> All Crops</a>`;
				}
				return ` ${all}
      <a class="dropdown-item ${className}" data-value="${d.crop_id}" data-label="${d.crop_name}"><i class=""></i> ${d.crop_name}</a>`;
			})
			.join("\n");
	}

	generateSeedOptionList(className, isAll) {
		let result = `
		<a class="dropdown-item ${className}" data-value="1" data-label="Breeder Seed"><i class=""></i> Breeder Seed</a>
		<a class="dropdown-item ${className}" data-value="2" data-label="Certified"><i class=""></i> Certified</a>
		<a class="dropdown-item ${className}" data-value="3" data-label="Foundation"><i class=""></i> Foundation</a>
		<a class="dropdown-item ${className}" data-value="4" data-label="Quality Declared Seeds/Truthfully Labelled Seeds"><i class=""></i> Quality Declared Seeds/Truthfully Labelled Seeds</a>
		`
		if(isAll){
			result = `<a class="dropdown-item ${className}" data-value="0" data-label="All"><i class=""></i> All</a>
			${result}
			`
		}
		return result;
	}

	getHtmlActionForSeedProduced() {
		const cropListHtml = this.generteCropList("seed-production-crops-list");
		$("#seed-production-crops").html(cropListHtml);
		$(".seed-production-crops-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#seed-production-crop-name").html(data.label);
			$("#seed-production-crop-name").data("value", data.value);
			this.getSeedProducedContainerInfo();
		});
	}

	getHtmlActionForCountryComparation() {
		const cropListHtml = this.generteCropList("country-comp-crops-list");
		$("#country-comp-crops").html(cropListHtml);
		$(".country-comp-crops-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#country-comp-crop-name").html(data.label);
			$("#country-comp-crop-name").data("value", data.value);
			this.getCountryComparationInfo();
		});
	}

	getHtmlActionForYearComparison() {
		const cropListHtml = this.generteCropList("year-comp-crops-list");
		$("#year-comp-crops").html(cropListHtml);
		$(".year-comp-crops-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crop-name").html(data.label);
			$("#year-comp-crop-name").data("value", data.value);
			this.getYearComparisonInfo();
		});
	}

	getHtmlActionForProducerWiseSeed() {
		const catListHtml = this.generateSeedOptionList(
			"producers-cat-options-list", true
		);
		$("#producers-cat-options").html(catListHtml);
		$(".producers-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#producers-cat-name").html(data.label);
			$("#producers-cat-name").data("value", data.value);
			this.getProducerWiseSeedInfo();
		});
	}

	getHtmlActionForCropAndCountryWiseSeedInfo() {
		const catListHtml = this.generateSeedOptionList(
			"country-corp-sanky-cat-options-list"
		);
		$("#country-corp-sanky-cat-options").html(catListHtml);
		$(".country-corp-sanky-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#country-corp-sanky-cat-name").html(data.label);
			$("#country-corp-sanky-cat-name").data("value", data.value);
			this.getCropAndCountryWiseSeedInfo();
		});
	}

	getHtmlActionForCropWiseSdgsContribution() {
		const catListHtml = this.generateSeedOptionList(
			"crop-sdg-contribution-cat-options-list"
		);
		$("#crop-sdg-contribution-cat-options").html(catListHtml);
		$(".crop-sdg-contribution-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#crop-sdg-contribution-cat-name").html(data.label);
			$("#crop-sdg-contribution-cat-name").data("value", data.value);
			this.getCropWiseSdgsContribution();
		});
	}

	getHtmlActionForCrpWiseCrop() {
		const catListHtml = this.generateSeedOptionList(
			"crop-prod-cat-options-list"
		);
		$("#crop-prod-cat-options").html(catListHtml);
		$(".crop-prod-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#crop-prod-cat-name").html(data.label);
			$("#crop-prod-cat-name").data("value", data.value);
			this.generateCrpWiseCropInfo();
		});
	}

	getHtmlActionForScientificPublications() {
		const catListHtml = this.generateSeedOptionList(
			"scientific-publicatin-cat-options-list"
		);
		$("#scientific-publicatin-cat-options").html(catListHtml);
		$(".scientific-publicatin-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#scientific-publicatin-cat-name").html(data.label);
			$("#scientific-publicatin-cat-name").data("value", data.value);
			this.getScientificPublications();
		});
	}

	getHtmlActionForMap() {
		const catListHtml = this.generateSeedOptionList(
			"producer-cat-options-list"
		);
		$("#producer-cat-options").html(catListHtml);
		$(".producer-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#map-cat-name").html(data.label);
			$("#map-cat-name").data("value", data.value);
			this.generateCountriesMap();
		});
	}

	getHtmlActionForBreed() {
		const catListHtml = this.generateSeedOptionList(
			"producer-cat-options-list"
		);
		$("#producer-cats-options").html(catListHtml);
		$(".producer-cat-options-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-cat-name").html(data.label);
			$("#year-cat-name").data("value", data.value);
			// function commented for this graph can not display
			//this.generateSeedProduceBreederChart();
		});
	}

	getHtmlActionForBreedList() {
		const catListHtml = this.generateSeedOptionList("crp-option-list");
		$("#year-crp-list").html(catListHtml);
		$(".crp-option-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-crp-quantity_type").html(data.label);
			$("#year-crp-quantity_type").data("value", data.value);
			this.getYearCrpsInfo();
		});
	}

	getHtmlActionForBreederOptionList() {
		const catListHtml = this.generateSeedOptionList("contries-option-list");
		$("#year-countries-list").html(catListHtml);
		$(".contries-option-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-countries-quantity-type").html(data.label);
			$("#year-countries-quantity-type").data("value", data.value);
			this.getYearContriesInfo();
		});
	}

	staticCharts() {
		// MPR Map
		// Highcharts.getJSON(
		//   "https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/world-population.json",
		//   function (data) {
		//     Highcharts.mapChart("mpr-mapSeedsproducedacrosscountries", {
		//       chart: {
		//         borderWidth: 0,
		//         map: "custom/world",
		//       },
		//       title: {
		//         text: null,
		//       },
		//       subtitle: {
		//         text: null,
		//       },
		//       credits: {
		//         enabled: false,
		//       },
		//       legend: {
		//         enabled: false,
		//       },
		//       mapNavigation: {
		//         enabled: true,
		//         buttonOptions: {
		//           verticalAlign: "bottom",
		//         },
		//       },
		//       series: [
		//         {
		//           name: "Countries",
		//           color: "#4dabf5",
		//           enableMouseTracking: false,
		//         },
		//         {
		//           type: "mapbubble",
		//           name: "Hybrids / Varieties",
		//           joinBy: ["iso-a3", "code3"],
		//           data: data,
		//           minSize: 4,
		//           maxSize: "12%",
		//           tooltip: {
		//             pointFormat: "{point.properties.hc-a2}: {point.z} thousands",
		//           },
		//         },
		//       ],
		//     });
		//   }
		// );
		// MPR Flow Chart
		// MPR Pie Chart Type 2
		// MPR Flow Chart
		//mpr-MapChamparision mapResearchprogramwise
		// MPR Flow Chart
		// Crop-wise yield on-station & on-farm
		// Trait-wise hybrids/varieties released
		// Trait-wise hybrids/varieties released
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
			// 		.attr("download", `seeds-produced-countries.jpeg`);
			// });
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "seeds-produced-countries.csv",
				header_body_space: 0,
			});
		});

		//graph-2
		const graphTab2 = $("#graph-btn-2");
		const graphTab22 = $("#graph-btn-22");
		const graphTab23 = $("#graph-btn-23");
		const graphTab24 = $("#graph-btn-24");

		const tableTab2 = $("#table-btn-2");
		const tableTab22 = $("#table-btn-22");
		const tableTab23 = $("#table-btn-23");
		const tableTab24 = $("#table-btn-24");

		const downloadTab2 = $("#download-btn-2>img");
		const downloadTab22 = $("#download-btn-22>img");
		const downloadTab23 = $("#download-btn-23>img");
		const downloadTab24 = $("#download-btn-24>img");

		const graph2 = $("#graph-2");
		const graph22 = $("#graph-22");
		const graph23 = $("#graph-23");
		const graph24 = $("#graph-24");

		const table2 = $("#table-2");
		const table22 = $("#table-22");
		const table23 = $("#table-23");
		const table24 = $("#table-24");

		graphTab2.on("click", () => {
			graphTab2.addClass("active");
			tableTab2.removeClass("active");
			graph2.show();
			table2.hide();
			graphTab2
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab2
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab22.on("click", () => {
			graphTab22.addClass("active");
			tableTab22.removeClass("active");
			graph22.show();
			table22.hide();
			graphTab22
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab22
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab23.on("click", () => {
			graphTab23.addClass("active");
			tableTab23.removeClass("active");
			graph23.show();
			table23.hide();
			graphTab23
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab23
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab24.on("click", () => {
			graphTab24.addClass("active");
			tableTab24.removeClass("active");
			graph24.show();
			table24.hide();
			graphTab24
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab24
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab2
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab22.on("click", () => {
			tableTab22.addClass("active");
			graphTab22.removeClass("active");
			table22.show();
			graph22.hide();
			graphTab22
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab22
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab23.on("click", () => {
			tableTab23.addClass("active");
			graphTab23.removeClass("active");
			table23.show();
			graph23.hide();
			graphTab23
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab23
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab24.on("click", () => {
			tableTab24.addClass("active");
			graphTab24.removeClass("active");
			table24.show();
			graph24.hide();
			graphTab24
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab24
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
					.attr("download", `seeds-produced-countries.jpeg`);
			});
		});
		downloadTab22.on("click", () => {
			if (
				downloadTab22.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab22.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab22.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab22.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-22")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-22")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-countries.jpeg`);
			});
		});
		downloadTab23.on("click", () => {
			if (
				downloadTab23.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab23.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab23.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab23.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-23")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-23")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-countries.jpeg`);
			});
		});
		downloadTab24.on("click", () => {
			if (
				downloadTab24.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab24.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab24.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab24.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-24")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-24")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-countries.jpeg`);
			});
		});


		$("#dwn-csv-2").on("click", function () {
			$("#table-2-main").table2csv({
				file_name: "seeds-produced-countries.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-22").on("click", function () {
			$("#table-22-main").table2csv({
				file_name: "seeds-produced-countries.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-23").on("click", function () {
			$("#table-23-main").table2csv({
				file_name: "seeds-produced-countries.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-24").on("click", function () {
			$("#table-24-main").table2csv({
				file_name: "seeds-produced-countries.csv",
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
					.attr("download", `seeds-produced-crops.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "seeds-produced-crops.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					.attr("download", `seeds-produced-producers.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "seeds-produced-producers.csv",
				header_body_space: 0,
			});
		});

		//graph-6
		const graphTab6 = $("#graph-btn-6");
		const graphTab62 = $("#graph-btn-62");
		const graphTab63 = $("#graph-btn-63");
		const graphTab64 = $("#graph-btn-64");

		const tableTab6 = $("#table-btn-6");
		const tableTab62 = $("#table-btn-62");
		const tableTab63 = $("#table-btn-63");
		const tableTab64 = $("#table-btn-64");

		const downloadTab6 = $("#download-btn-6>img");
		const downloadTab62 = $("#download-btn-62>img");
		const downloadTab63 = $("#download-btn-63>img");
		const downloadTab64 = $("#download-btn-64>img");

		const graph6 = $("#graph-6");
		const graph62 = $("#graph-62");
		const graph63 = $("#graph-63");
		const graph64 = $("#graph-64");

		const table6 = $("#table-6");
		const table62 = $("#table-62");
		const table63 = $("#table-63");
		const table64 = $("#table-64");

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
		graphTab62.on("click", () => {
			graphTab62.addClass("active");
			tableTab62.removeClass("active");
			graph62.show();
			table62.hide();
			graphTab62
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab62
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab63.on("click", () => {
			graphTab63.addClass("active");
			tableTab63.removeClass("active");
			graph63.show();
			table63.hide();
			graphTab63
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab63
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab64.on("click", () => {
			graphTab64.addClass("active");
			tableTab64.removeClass("active");
			graph64.show();
			table64.hide();
			graphTab64
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab64
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
		tableTab62.on("click", () => {
			tableTab62.addClass("active");
			graphTab62.removeClass("active");
			table62.show();
			graph62.hide();
			graphTab62
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab62
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab63.on("click", () => {
			tableTab63.addClass("active");
			graphTab63.removeClass("active");
			table63.show();
			graph63.hide();
			graphTab63
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab63
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab64.on("click", () => {
			tableTab64.addClass("active");
			graphTab64.removeClass("active");
			table64.show();
			graph64.hide();
			graphTab64
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab64
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
					.attr("download", `seeds-produced-crp.jpeg`);
			});
		});
		downloadTab62.on("click", () => {
			if (
				downloadTab62.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab62.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab62.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab62.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-62")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-62")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-crp.jpeg`);
			});
		});
		downloadTab63.on("click", () => {
			if (
				downloadTab63.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab63.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab63.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab63.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-63")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-63")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-crp.jpeg`);
			});
		});
		downloadTab64.on("click", () => {
			if (
				downloadTab64.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab64.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab64.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab64.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-64")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-64")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-crp.jpeg`);
			});
		});

		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "seeds-produced-crp.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-62").on("click", function () {
			$("#table-62-main").table2csv({
				file_name: "seeds-produced-crp.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-63").on("click", function () {
			$("#table-63-main").table2csv({
				file_name: "seeds-produced-crp.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-64").on("click", function () {
			$("#table-64-main").table2csv({
				file_name: "seeds-produced-crp.csv",
				header_body_space: 0,
			});
		});

		//graph-7
		const graphTab7 = $("#graph-btn-7");
		const graphTab72 = $("#graph-btn-72");
		const graphTab73 = $("#graph-btn-73");
		const graphTab74 = $("#graph-btn-74");

		const tableTab7 = $("#table-btn-7");
		const tableTab72 = $("#table-btn-72");
		const tableTab73 = $("#table-btn-73");
		const tableTab74 = $("#table-btn-74");

		const downloadTab7 = $("#download-btn-7>img");
		const downloadTab72 = $("#download-btn-72>img");
		const downloadTab73 = $("#download-btn-73>img");
		const downloadTab74 = $("#download-btn-74>img");

		const graph7 = $("#graph-7");
		const graph72 = $("#graph-72");
		const graph73 = $("#graph-73");
		const graph74 = $("#graph-74");

		const table7 = $("#table-7");
		const table72 = $("#table-72");
		const table73 = $("#table-73");
		const table74 = $("#table-74");

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
		graphTab72.on("click", () => {
			graphTab72.addClass("active");
			tableTab72.removeClass("active");
			graph72.show();
			table72.hide();
			graphTab72
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab72
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab73.on("click", () => {
			graphTab73.addClass("active");
			tableTab73.removeClass("active");
			graph73.show();
			table73.hide();
			graphTab73
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab73
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});
		graphTab74.on("click", () => {
			graphTab74.addClass("active");
			tableTab74.removeClass("active");
			graph74.show();
			table74.hide();
			graphTab74
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab74
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
		tableTab72.on("click", () => {
			tableTab72.addClass("active");
			graphTab72.removeClass("active");
			table72.show();
			graph72.hide();
			graphTab72
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab72
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab73.on("click", () => {
			tableTab73.addClass("active");
			graphTab73.removeClass("active");
			table73.show();
			graph73.hide();
			graphTab73
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab73
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		tableTab74.on("click", () => {
			tableTab74.addClass("active");
			graphTab74.removeClass("active");
			table74.show();
			graph74.hide();
			graphTab74
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab74
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
					.attr("download", `seeds-produced-rp.jpeg`);
			});
		});
		downloadTab72.on("click", () => {
			if (
				downloadTab72.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab72.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab72.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab72.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-72")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-72")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-rp.jpeg`);
			});
		});
		downloadTab73.on("click", () => {
			if (
				downloadTab73.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab73.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab73.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab73.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-73")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-73")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-rp.jpeg`);
			});
		});
		downloadTab74.on("click", () => {
			if (
				downloadTab74.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab74.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab74.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab74.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-74")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-74")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced-rp.jpeg`);
			});
		});

		$("#dwn-csv-7").on("click", function () {
			$("#table-7-main").table2csv({
				file_name: "seeds-produced-rp.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-72").on("click", function () {
			$("#table-72-main").table2csv({
				file_name: "seeds-produced-rp.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-73").on("click", function () {
			$("#table-73-main").table2csv({
				file_name: "seeds-produced-rp.csv",
				header_body_space: 0,
			});
		});
		$("#dwn-csv-74").on("click", function () {
			$("#table-74-main").table2csv({
				file_name: "seeds-produced-rp.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
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
					.attr("download", `seeds-produced-publication.jpeg`);
			});
		});
		$("#dwn-csv-10").on("click", function () {
			$("#table-10-main").table2csv({
				file_name: "seeds-produced-publication.csv",
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
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-11").on("click", function () {
			$("#table-11-main").table2csv({
				file_name: "seeds-produced.csv",
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
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-12").on("click", function () {
			$("#table-12-main").table2csv({
				file_name: "seeds-produced.csv",
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
			html2canvas(document.getElementById("graph-13")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-13")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-13").on("click", function () {
			$("#table-13-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
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
			graphTab14
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab14
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab14.on("click", () => {
			tableTab14.addClass("active");
			graphTab14.removeClass("active");
			table14.show();
			graph14.hide();
			graphTab14
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab14
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
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
			html2canvas(document.getElementById("graph-14")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-14")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-14").on("click", function () {
			$("#table-14-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});

		//graph-15
		const graphTab15 = $("#graph-btn-15");
		const tableTab15 = $("#table-btn-15");
		const downloadTab15 = $("#download-btn-15>img");

		const graph15 = $("#graph-15");
		const table15 = $("#table-15");

		graphTab15.on("click", () => {
			graphTab15.addClass("active");
			tableTab15.removeClass("active");
			graph15.show();
			table15.hide();
			graphTab15
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab15
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab15.on("click", () => {
			tableTab15.addClass("active");
			graphTab15.removeClass("active");
			table15.show();
			graph15.hide();
			graphTab15
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab15
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab15.on("click", () => {
			if (
				downloadTab15.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab15.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab15.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab15.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-15")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-15")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-15").on("click", function () {
			$("#table-15-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});

		//graph-16
		const graphTab16 = $("#graph-btn-16");
		const tableTab16 = $("#table-btn-16");
		const downloadTab16 = $("#download-btn-16>img");

		const graph16 = $("#graph-16");
		const table16 = $("#table-16");

		graphTab16.on("click", () => {
			graphTab16.addClass("active");
			tableTab16.removeClass("active");
			graph16.show();
			table16.hide();
			graphTab16
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab16
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab16.on("click", () => {
			tableTab16.addClass("active");
			graphTab16.removeClass("active");
			table16.show();
			graph16.hide();
			graphTab16
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab16
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab16.on("click", () => {
			if (
				downloadTab16.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab16.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab16.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab16.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-16")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-16")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-16").on("click", function () {
			$("#table-16-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});

		//graph-17
		const graphTab17 = $("#graph-btn-17");
		const tableTab17 = $("#table-btn-17");
		const downloadTab17 = $("#download-btn-17>img");

		const graph17 = $("#graph-17");
		const table17 = $("#table-17");

		graphTab17.on("click", () => {
			graphTab17.addClass("active");
			tableTab17.removeClass("active");
			graph17.show();
			table17.hide();
			graphTab17
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab17
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab17.on("click", () => {
			tableTab17.addClass("active");
			graphTab17.removeClass("active");
			table17.show();
			graph17.hide();
			graphTab17
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab17
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab17.on("click", () => {
			if (
				downloadTab17.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab17.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab17.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab17.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-17")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-17")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-17").on("click", function () {
			$("#table-17-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});

		//graph-18
		const graphTab18 = $("#graph-btn-18");
		const tableTab18 = $("#table-btn-18");
		const downloadTab18 = $("#download-btn-18>img");

		const graph18 = $("#graph-18");
		const table18 = $("#table-18");

		graphTab18.on("click", () => {
			graphTab18.addClass("active");
			tableTab18.removeClass("active");
			graph18.show();
			table18.hide();
			graphTab18
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab18
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab18.on("click", () => {
			tableTab18.addClass("active");
			graphTab18.removeClass("active");
			table18.show();
			graph18.hide();
			graphTab18
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab18
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab18.on("click", () => {
			if (
				downloadTab18.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab18.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab18.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab18.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-18")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-18")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-18").on("click", function () {
			$("#table-18-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});

		//graph-19
		const graphTab19 = $("#graph-btn-19");
		const tableTab19 = $("#table-btn-19");
		const downloadTab19 = $("#download-btn-19>img");

		const graph19 = $("#graph-19");
		const table19 = $("#table-19");

		graphTab19.on("click", () => {
			graphTab19.addClass("active");
			tableTab19.removeClass("active");
			graph19.show();
			table19.hide();
			graphTab19
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab19
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab19.on("click", () => {
			tableTab19.addClass("active");
			graphTab19.removeClass("active");
			table19.show();
			graph19.hide();
			graphTab19
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab19
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab19.on("click", () => {
			if (
				downloadTab19.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab19.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab19.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab19.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-19")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-19")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-19").on("click", function () {
			$("#table-19-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});

		//graph-20
		const graphTab20 = $("#graph-btn-20");
		const tableTab20 = $("#table-btn-20");
		const downloadTab20 = $("#download-btn-20>img");

		const graph20 = $("#graph-20");
		const table20 = $("#table-20");

		graphTab20.on("click", () => {
			graphTab20.addClass("active");
			tableTab20.removeClass("active");
			graph20.show();
			table20.hide();
			graphTab20
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Bar-selected.svg"
				);
			tableTab20
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Table.svg");
		});

		tableTab20.on("click", () => {
			tableTab20.addClass("active");
			graphTab20.removeClass("active");
			table20.show();
			graph20.hide();
			graphTab20
				.find("img")
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Bar.svg");
			tableTab20
				.find("img")
				.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Table-selected.svg"
				);
		});
		downloadTab20.on("click", () => {
			if (
				downloadTab20.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "download.svg"
			) {
				downloadTab20.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
				);
			} else if (
				downloadTab20.attr("src") ==
				`${baseURL}include/assets/img/pi_2020/` + "Download-selected.svg"
			) {
				downloadTab20.prop(
					"src",
					`${baseURL}include/assets/img/pi_2020/` + "download.svg"
				);
			}
			html2canvas(document.getElementById("graph-20")).then((canvas) => {
				let dataSrc = canvas.toDataURL("image/png");
				dataSrc = dataSrc.replace("data:image/png;base64,", "");
				$("#dwn-img-20")
					.attr(
						"href",
						"data:application/octet-stream;base64," + encodeURI(dataSrc)
					)
					.attr("target", "_blank")
					.attr("download", `seeds-produced.jpeg`);
			});
		});
		$("#dwn-csv-20").on("click", function () {
			$("#table-20-main").table2csv({
				file_name: "seeds-produced.csv",
				header_body_space: 0,
			});
		});
	}

	initSparkLine(selector, data, color) {
		const years = data.map((d) => Number(d.year));
		const chart = Highcharts.SparkLine(selector, {
			series: [
				{
					data: data.map((d) => Number(Number(d.value || 0).toFixed(2))),
					color: color,
				},
			],
			tooltip: {
				formatter: () => {
					const currentData = data[chart.hoverPoint.index];
					return `<span>${currentData.year
						} <br/> <b> ${currentData.value.toFixed(2)} </b></span>`;
				},
			},
			chart: {},
		});
	}

	getHtmlActionForYearComparisonBR() {
		const cropListHtml = this.generteCropList("year-comp-crops-list-br");
		$("#year-comp-crops-br").html(cropListHtml);
		$(".year-comp-crops-list-br").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crop-name-br").html(data.label);
			$("#year-comp-crop-name-br").data("value", data.value);
			this.getYearComparisonInfoBR();
		});
	}

	getYearComparisonInfoBR() {
		const selectedCrop = $("#year-comp-crop-name-br").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				let result = { year: yr.year };
				result.quantity = this.seedProducedData.tsps
					.filter((d) => search(d) && d.year_id == yr.year_id)
					.map((d) => {
						if (d.unit_breeder_seed?.toUpperCase() == "KG") {
							return parseFloat(d.quantity_breeder_seed || 0) / 1000;
						} else {
							return parseFloat(d.quantity_breeder_seed || 0);
						}
					})
					.filter((d) => !isNaN(d))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.quantity > 0);

		const maxVal = Math.max(...chartData.map((e) => e.quantity));
		const breakarray = [
			{
				from: (maxVal * 1) / 100,
				to: (maxVal * 99) / 100,
			},
		];

		Highcharts.chart("sp-year-crop-graph-br", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#d79494"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData.map((e) => e["year"]),
				title: { text: null },
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					maxPointWidth: 30,
					data: chartData.map((e) => e["quantity"]),
				},
			],
		});

		$("#table-17-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td><td>${numberWithCommas(
						e.quantity.toFixed(2)
					)}</td></tr>`
			)
		);
		$("#table-17-tfoot").html(
			`<tr><td>Total</td><td>${chartData
				.map((e) => e.quantity)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>`
		);
	}

	getHtmlActionForYearComparisonFN() {
		const cropListHtml = this.generteCropList("year-comp-crops-list-fn");
		$("#year-comp-crops-fn").html(cropListHtml);
		$(".year-comp-crops-list-fn").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crop-name-fn").html(data.label);
			$("#year-comp-crop-name-fn").data("value", data.value);
			this.getYearComparisonInfoFN();
		});
	}

	getYearComparisonInfoFN() {
		const selectedCrop = $("#year-comp-crop-name-fn").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				let result = { year: yr.year };
				result.quantity = this.seedProducedData.tsps
					.filter((d) => search(d) && d.year_id == yr.year_id)
					.map((d) => {
						if (d.unit_foundation_seed?.toUpperCase() == "KG") {
							return parseFloat(d.quantity_foundation_seed || 0) / 1000;
						} else {
							return parseFloat(d.quantity_foundation_seed || 0);
						}
					})
					.filter((d) => !isNaN(d))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.quantity > 0);

		const maxVal = Math.max(...chartData.map((e) => e.quantity));
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("sp-year-crop-graph-fn", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#FFCE56"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData.map((e) => e["year"]),
				title: { text: null },
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					maxPointWidth: 30,
					data: chartData.map((e) => e["quantity"]),
				},
			],
		});

		$("#table-18-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td><td>${numberWithCommas(
						e.quantity.toFixed(2)
					)}</td></tr>`
			)
		);
		$("#table-18-tfoot").html(
			`<tr><td>Total</td><td>${chartData
				.map((e) => e.quantity)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>`
		);
	}

	getHtmlActionForYearComparisonCF() {
		const cropListHtml = this.generteCropList("year-comp-crops-list-cf");
		$("#year-comp-crops-cf").html(cropListHtml);
		$(".year-comp-crops-list-cf").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crop-name-cf").html(data.label);
			$("#year-comp-crop-name-cf").data("value", data.value);
			this.getYearComparisonInfoCF();
		});
	}

	getYearComparisonInfoCF() {
		const selectedCrop = $("#year-comp-crop-name-cf").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				let result = { year: yr.year };
				result.quantity = this.seedProducedData.tsps
					.filter((d) => search(d) && d.year_id == yr.year_id)
					.map((d) => {
						if (d.unit_certifiedseed?.toUpperCase() == "KG") {
							return parseFloat(d.quantity_certified_seed || 0) / 1000;
						} else {
							return parseFloat(d.quantity_certified_seed || 0);
						}
					})
					.filter((d) => !isNaN(d))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.quantity > 0);

		const maxVal = Math.max(...chartData.map((e) => e.quantity));
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("sp-year-crop-graph-cf", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#17A2B8"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData.map((e) => e["year"]),
				title: { text: null },
			},
			yAxis: {
				title: { text: "Seed Production (Tons)" },
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					maxPointWidth: 30,
					data: chartData.map((e) => e["quantity"]),
				},
			],
		});

		$("#table-19-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td><td>${numberWithCommas(
						e.quantity.toFixed(2)
					)}</td></tr>`
			)
		);
		$("#table-19-tfoot").html(
			`<tr><td>Total</td><td>${chartData
				.map((e) => e.quantity)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>`
		);
	}

	getHtmlActionForYearComparisonQD() {
		const cropListHtml = this.generteCropList("year-comp-crops-list-qd");
		$("#year-comp-crops-qd").html(cropListHtml);
		$(".year-comp-crops-list-qd").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crop-name-qd").html(data.label);
			$("#year-comp-crop-name-qd").data("value", data.value);
			this.getYearComparisonInfoQD();
		});
	}

	getYearComparisonInfoQD() {
		const selectedCrop = $("#year-comp-crop-name-qd").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				let result = { year: yr.year };
				result.quantity = this.seedProducedData.tsps
					.filter((d) => search(d) && d.year_id == yr.year_id)
					.map((d) => {
						if (d.unit_QDS?.toUpperCase() == "KG") {
							return parseFloat(d.quantity_QDS || 0) / 1000;
						} else {
							return parseFloat(d.quantity_QDS || 0);
						}
					})
					.filter((d) => !isNaN(d))
					.reduce((a, b) => a + b, 0);
				return result;
			})
			.filter((d) => d.quantity > 0);

		const maxVal = Math.max(...chartData.map((e) => e.quantity));
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];

		Highcharts.chart("sp-year-crop-graph-qd", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#7cb5ec"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData.map((e) => e["year"]),
				title: { text: null },
			},
			yAxis: {
				title: { text: "Seed Production (Tons)"},
				labels: { overflow: "justify" },
				breaks: breakarray,
				events: {
					pointBreak: pointBreakColumn,
				},
			},
			legend: { enabled: false },
			tooltip: { pointFormat: "<b>{point.y:.2f}</b>" },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						format: "{point.y:.2f}",
					},
					states: {
						inactive: { opacity: 1 },
						hover: { enabled: false },
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
					maxPointWidth: 30,
					data: chartData.map((e) => e["quantity"]),
				},
			],
		});

		$("#table-20-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td><td>${numberWithCommas(
						e.quantity.toFixed(2)
					)}</td></tr>`
			)
		);
		$("#table-20-tfoot").html(
			`<tr><td>Total</td><td>${chartData
				.map((e) => e.quantity)
				.reduce((a, b) => a + b , 0)
				.toFixed(2)}</td></tr>`
		);
	}
}
