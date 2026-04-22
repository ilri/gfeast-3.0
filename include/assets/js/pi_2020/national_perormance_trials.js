var script = document.getElementById("nptJS"),
	baseURL = script.getAttribute("data-baseurl");

class HybridNationalTrails {
	constructor() {}

	init() {
		setTimeout(() => this.getHybridNationalData());
	}

	get HYBRIDID() {
		return 1;
	}

	get GERMPLASMID() {
		return 2;
	}

	get BREEDINGID() {
		return 3;
	}
	get DISAGGREGATION() {
		return 4;
	}

	getHybridNationalData() {
		const request = indexFilter.getFilteredData();
		request.purpose = "get_breeding_germplasm_hybrid";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL +
					"/include/assets/js/pi_2020/tabs/national_performance_trials_tab.html",
				true
			),
		];
		Promise.all(promises)
			.then((response) => {
				if (response?.length) {
					this.hybridNationalTrailsData = response[0];
					const resHtml = response[1].replaceAll(
						'src="img/',
						`src="${baseURL}include/assets/img/pi_2020/`
					);
					$(".mpr-tab-contend").html(resHtml);
					this.arrangeData();
					this.generateCharts();
					this.htmlToggle();
				}
			})
			.catch((err) => console.log(err));
	}

	arrangeData() {
		this.tbghs = clone(hybridNationalTrails.hybridNationalTrailsData.tbghs);
		this.tbghs.forEach((d) => {
			d.crp = hybridNationalTrails.hybridNationalTrailsData.tbgh_crps.filter(
				(e) => d.data_id == e.data_id
			);
			d.nutritional = hybridNationalTrails.hybridNationalTrailsData.tbgh_nutritional.filter(
				(e) => d.data_id == e.data_id
			);
			d.primaries = hybridNationalTrails.hybridNationalTrailsData.tbgh_primaries.filter(
				(e) => d.data_id == e.data_id
			);
			d.secondaries = hybridNationalTrails.hybridNationalTrailsData.tbgh_secondaries.filter(
				(e) => d.data_id == e.data_id
			);
		});
	}
	generateCharts() {
		this.generateEnteredNationalPerformanceChart();
		this.generateCountryNationalPerformanceChart();
		this.generateCropNationalPerformanceChart();
		this.generateCrpWiseNationPerformanceChart();
		// this.generateRPNationPerformanceChart();
		//this.generateNutritionNationPerformanceChart();
		this.generatePSNationPerformanceChart();
		this.generateCountriesMap();
		this.graphYearwiseTrials();
		this.generateSankeyChart();

		this.getHtmlActionForCropYearComparison();
		this.getCropYearComparisonInfo();

		this.getHtmlActionForCRPYearComparison();
		this.getCRPYearComparisonInfo();

		this.getHtmlActionForCountryYearComparison();
		this.getCountryYearComparisonInfo();
	}

	generateEnteredNationalPerformanceChart() {
		$("#totalRecords").html("");
		$("#breadingMaterialCount").html("");
		$("#hybridLineCount").html("");
		$("#germplasmCount").html("");
		$("#disaggregationCount").html("");
		$("#table-1-tbody").html("");

		const tbghs = this.hybridNationalTrailsData.tbghs;
		const chartData = {
			breedingMaterial: {},
			hybridLine: {},
			germplasm: {},
			disaggregation: {},
			count: tbghs.length,
		};
		chartData.breedingMaterial.data = tbghs.filter(
			(d) => d.hybrid_variety_area == this.BREEDINGID
		);
		chartData.breedingMaterial.count = chartData.breedingMaterial.data.length;
		chartData.hybridLine.data = tbghs.filter(
			(d) => d.hybrid_variety_area == this.HYBRIDID
		);
		chartData.hybridLine.count = chartData.hybridLine.data.length;
		chartData.germplasm.data = tbghs.filter(
			(d) => d.hybrid_variety_area == this.GERMPLASMID
		);
		chartData.germplasm.count = chartData.germplasm.data.length;
		chartData.disaggregation.data = tbghs.filter(
			(d) => d.hybrid_variety_area == this.DISAGGREGATION
		);
		chartData.disaggregation.count = chartData.disaggregation.data.length;
		let totalCount =
			chartData.breedingMaterial.count +
			chartData.hybridLine.count +
			chartData.germplasm.count +
		chartData.disaggregation.count;

		chartData.countryData = indexFilter.countries
			.map((data) => {
				const result = {
					countryId: data.country_id,
					countryName: data.country_name,
				};
				result["breedingMaterialData"] = tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.BREEDINGID &&
						d.country_id == data.country_id
				);
				result["breedingMaterialCount"] = result["breedingMaterialData"].length;
				result["hybridLineData"] = tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.HYBRIDID &&
						d.country_id == data.country_id
				);
				result["hybridLineCount"] = result["hybridLineData"].length;
				result["germplasmData"] = tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.GERMPLASMID &&
						d.country_id == data.country_id
				);
				result["germplasmCount"] = result["germplasmData"].length;
				result["disaggregationData"] = tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.DISAGGREGATION &&
						d.country_id == data.country_id
				);
				result["disaggregationCount"] = result["disaggregationData"].length;
				return result;
			})
			.filter(
				(d) =>
					d.germplasmCount ||
					d.hybridLineCount ||
					d.breedingMaterialCount ||
					d.disaggregationCount
			);

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
			colors: ["#d79494", "#7cb5ec", "#FFCE56", "#17A2B8"],
			credits: {
				enabled: false,
			},
			plotOptions: {
				pie: {
					allowPointSelect: false,
					//cursor: 'pointer',
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
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percent:.2f} %) of total<br/>',
			},
			series: [
				{
					name: "Hybrids / Varieties",
					colorByPoint: true,
					data: [
						{
							name: "Breeding Material",
							y: chartData.breedingMaterial.count,
							percent: (chartData.breedingMaterial.count * 100) / totalCount,
						},
						{
							name: "Hybrid Lines",
							y: chartData.hybridLine.count,
							percent: (chartData.hybridLine.count * 100) / totalCount,
						},
						{
							name: "Germplasm",
							y: chartData.germplasm.count,
							percent: (chartData.germplasm.count * 100) / totalCount,
						},
						{
							name: "Disaggregated data is not available for the year",
							y: chartData.disaggregation.count,
							percent: (chartData.disaggregation.count * 100) / totalCount,
						},
					].filter(d=> d.y > 0),
				},
			],
		});

		$("#totalRecords").html(numberWithCommas(chartData.count));
		$("#breadingMaterialCount").html(
			numberWithCommas(chartData.breedingMaterial.count)
		);
		$("#hybridLineCount").html(numberWithCommas(chartData.hybridLine.count));
		$("#germplasmCount").html(numberWithCommas(chartData.germplasm.count));
		// $("#disaggregationCount").html(
		// 	numberWithCommas(chartData.disaggregation.count)
		// );
		if (chartData.disaggregation.count == 0) {
			$("#disaggregationView").css('display', 'none')
		} else {
			$("#disaggregationCount").html(chartData.disaggregation.count);
		}

		let tfData = chartData.disaggregation.count +
		chartData.hybridLine.count +
		chartData.germplasm.count +
		chartData.breedingMaterial.count

		let tableHtml = `
        <tr><td>Breeding Material</td><td>${chartData.breedingMaterial.count == 0 ? "NA" : chartData.breedingMaterial.count}</td></tr>
        <tr><td>Germplasm</td><td>${chartData.germplasm.count == 0 ? "NA" : chartData.germplasm.count}</td></tr>
        <tr><td>Hybrid Lines</td><td>${chartData.hybridLine.count == 0 ? "NA" : chartData.hybridLine.count}</td></tr>
        <tr><td>Disaggregated data is not available for the year</td><td>${chartData.disaggregation.count == 0 ? "NA" : chartData.disaggregation.count}</td></tr>
        <tr><td style="font-weight: 600;">Total</td><td style="font-weight: 600;">${ tfData == 0 ? "NA" : tfData}</td></tr>
    `;
		$("#table-1-tbody").html(tableHtml);

		let hybridLineVCounts = indexFilter.pi2020FilterData.crops
			.map((c) => {
				let vNames = Array.from(
					new Set(
						chartData.hybridLine.data
							.filter((f) => f.crop_id == c.crop_id && f.varieties_id)
							.map((f) => f.varieties_id)
					)
				);
				return vNames.map(
					(vn) => `<tr><td>${c.crop_name}</td><td>${vn}</td></tr>`
				);
			})
			.join("\n");
		$("#tt-hl-list").html(hybridLineVCounts);

		let breedingVCounts = indexFilter.pi2020FilterData.crops
			.map((c) => {
				let vNames = Array.from(
					new Set(
						chartData.breedingMaterial.data
							.filter((f) => f.crop_id == c.crop_id && f.varieties_id)
							.map((f) => f.varieties_id)
					)
				);
				return vNames.map(
					(vn) => `<tr><td>${c.crop_name}</td><td>${vn}</td></tr>`
				);
			})
			.join("\n");
		$("#tt-br-list").html(breedingVCounts);

		let germplasmVCounts = indexFilter.pi2020FilterData.crops
			.map((c) => {
				let vNames = Array.from(
					new Set(
						chartData.germplasm.data
							.filter((f) => f.crop_id == c.crop_id && f.varieties_id)
							.map((f) => f.varieties_id)
					)
				);
				return vNames.map(
					(vn) => `<tr><td>${c.crop_name}</td><td>${vn}</td></tr>`
				);
			})
			.join("\n");
		$("#tt-gp-list").html(germplasmVCounts);
		let disaggregationVCounts = indexFilter.pi2020FilterData.crops
			.map((c) => {
				let vNames = Array.from(
					new Set(
						chartData.disaggregation.data
							.filter((f) => f.crop_id == c.crop_id && f.varieties_id)
							.map((f) => f.varieties_id)
					)
				);
				return vNames.map(
					(vn) => `<tr><td>${c.crop_name}</td><td>${vn}</td></tr>`
				);
			})
			.join("\n");
		$("#tt-disagri-list").html(disaggregationVCounts);
	}

	generateCountryNationalPerformanceChart() {
		const chartData = indexFilter.pi2020FilterData.countries
			.map((data) => {
				const result = { id: data.country_id, name: data.country_name };
				result.breeder = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.BREEDINGID &&
						d.country_id == data.country_id
				);
				result.hybridLine = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.HYBRIDID &&
						d.country_id == data.country_id
				);
				result.germplasm = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.GERMPLASMID &&
						d.country_id == data.country_id
				);
				result.disaggregation = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.DISAGGREGATION &&
						d.country_id == data.country_id
				);

				result.breederCount = result.breeder.length;
				result.hybridLineCount = result.hybridLine.length;
				result.germplasmCount = result.germplasm.length;
				result.disaggregationCount = result.disaggregation.length;

				return result;
			})
			.filter(
				(d) =>
					d.breederCount ||
					d.hybridLineCount ||
					d.germplasmCount ||
					d.disaggregationCount
			);

			const serz = [
				{
					name: "Breeding Material",
					color: "#d79494",
					data: chartData.map((d) => d.breederCount),
					count: chartData.map((d) => d.breederCount).reduce((a,b)=> a+b,0),
				},
				{
					name: "Hybrid Lines",
					color: "#7cb5ec",
					data: chartData.map((d) => d.hybridLineCount),
					count: chartData.map((d) => d.hybridLineCount).reduce((a,b)=> a+b,0),
				},
				{
					name: "Germplasm",
					color: "#FFCE56",
					data: chartData.map((d) => d.germplasmCount),
					count: chartData.map((d) => d.germplasmCount).reduce((a,b)=> a+b,0),
				},
				{
					name: "Disaggregated data is not available for the year",
					color: "#17A2B8",
					data: chartData.map((d) => d.disaggregationCount),
					count: chartData.map((d) => d.disaggregationCount).reduce((a,b)=> a+b,0),
				},
			].filter(d=> d.count > 0)

			// debugger

		Highcharts.chart("mpr-mapComparison", {
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
				categories: chartData.map((d) => d.name),
			},
			yAxis: {
				min: 0,
				title: {
					text: "Number of lines",
				},
			},
			tooltip: {
				pointFormat:
					'<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
				shared: true,
			},
			// plotOptions: {
			// 	column: {
			// 		stacking: "normal",
			// 		dataLabels: { enabled: true, style: { textOutline: false , color:"#000"} },
			// 	},
			// },
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						style: { textOutline: false },
						formatter: function(){
							return (this.y!=0)?this.y:"";
							}

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

			series: serz,
		});


		$("#table-3-tbody").html(
			chartData
				.map((e) => {
					return `<tr>
					<td>${e.name}</td>
					<td>${e.breederCount == 0 ? "NA" : e.breederCount}</td>
					<td>${e.hybridLineCount == 0 ? "NA" : e.hybridLineCount}</td>
					<td>${e.germplasmCount == 0 ? "NA" : e.germplasmCount}</td>
					<td>${e.disaggregationCount == 0 ? "NA" : e.disaggregationCount}</td>
					<td style="font-weight: 600;">${(e.breederCount + e.hybridLineCount + e.germplasmCount + e.disaggregationCount) == 0 ? "NA" : (e.breederCount + e.hybridLineCount + e.germplasmCount + e.disaggregationCount)}</td></tr>`;
				})
				.join("\n")
		);

		let tbHydrid = chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0);
		let tbGerm = chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0);
		let tbBreeder = chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0);
		let tbDisag = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0)

		let tfData = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0)

		let tableFooter = `
        <tr><td>Total</td>
		<td>${tbHydrid == 0 ? "NA" : tbHydrid}</td>
		<td>${tbGerm == 0 ? "NA" : tbGerm}</td>
		<td>${tbBreeder == 0 ? "NA" : tbBreeder}</td>
		<td>${tbDisag == 0 ? "NA" : tbDisag}</td>
		<td>${ tfData == 0 ? "NA" : tfData}</td></tr>
    	`;
		$("#table-3-tfoot").html(tableFooter);
	}

	generateCropNationalPerformanceChart() {
		const chartData = indexFilter.pi2020FilterData.crops
			.map((data) => {
				const result = { id: data.crop_id, name: data.crop_name };
				result.breeder = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.BREEDINGID &&
						d.crop_id == data.crop_id
				);
				result.hybridLine = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.HYBRIDID && d.crop_id == data.crop_id
				);
				result.germplasm = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.GERMPLASMID &&
						d.crop_id == data.crop_id
				);
				result.disaggregation = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.DISAGGREGATION &&
						d.crop_id == data.crop_id
				);

				result.breederCount = result.breeder.length;
				result.hybridLineCount = result.hybridLine.length;
				result.germplasmCount = result.germplasm.length;
				result.disaggregationCount = result.disaggregation.length;

				return result;
			})
			.filter(
				(d) =>
					d.breederCount ||
					d.hybridLineCount ||
					d.germplasmCount ||
					d.disaggregationCount
			);

			const serz = [
				{
					name: "Breeding Material",
					color: "#d79494",
					data: chartData.map((d) => d.breederCount),
					count: chartData.map((d) => d.breederCount).reduce((a,b)=> a+b,0),
				},
				{
					name: "Hybrid Lines",
					color: "#7cb5ec",
					data: chartData.map((d) => d.hybridLineCount),
					count: chartData.map((d) => d.hybridLineCount).reduce((a,b)=> a+b,0),
				},
				{
					name: "Germplasm",
					color: "#FFCE56",
					data: chartData.map((d) => d.germplasmCount),
					count: chartData.map((d) => d.germplasmCount).reduce((a,b)=> a+b,0),
				},
				{
					name: "Disaggregated data is not available for the year",
					color: "#17A2B8",
					data: chartData.map((d) => d.disaggregationCount),
					count: chartData.map((d) => d.disaggregationCount).reduce((a,b)=> a+b,0),
				},
			].filter(d=> d.count > 0)

			debugger
		Highcharts.chart("mpr-mapCropwise_hybrid_entered", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#d79494", "#7cb5ec", "#FFCE56", "#17A2B8"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: chartData.map((d) => d.name),
				title: {
					text: "",
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
					text: "Number of lines",
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
						formatter: function(){
							return (this.y!=0)?this.y:"";
							}

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
			series: serz,
		});

		$("#table-4-tbody").html(
			chartData
				.map((e) => {
					//return `<tr><td>${e.name}</td><td>${e.breederCount}</td><td>${e.hybridLineCount}</td><td>${e.germplasmCount}</td></tr>`;
					return `<tr>
					<td>${e.name}</td>
					<td>${e.breederCount == 0 ? "NA" : e.breederCount}</td>
					<td>${e.hybridLineCount == 0 ? "NA" : e.hybridLineCount}</td>
					<td>${e.germplasmCount == 0 ? "NA" : e.germplasmCount}</td>
					<td>${e.disaggregationCount == 0 ? "NA" : e.disaggregationCount}</td>
					<td style="font-weight: 600;">${(e.breederCount +
						e.hybridLineCount +
						e.germplasmCount +
						e.disaggregationCount) == 0 ? "NA" : (e.breederCount +
							e.hybridLineCount +
							e.germplasmCount +
							e.disaggregationCount)}</td></tr>`;
				})
				.join("\n")
		);

		let tbhybrid = chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0);
		let tbGerm = chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0)
		let tbBreeder = chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0)
		let tbDisag = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0)

		let tfData = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0)

		let tableFooter = `
        <tr><td>Total</td>
		<td>${ tbhybrid == 0 ? "NA" : tbhybrid}</td>
		<td>${ tbGerm == 0 ? "NA" : tbGerm }</td>
		<td>${ tbBreeder == 0 ? "NA" : tbBreeder}</td>
		<td>${ tbDisag == 0 ? "NA" : tbDisag}</td>
		<td>${ tfData == 0 ? "NA" : tfData}</td></tr>
    	`;
		$("#table-4-tfoot").html(tableFooter);
	}

	generateCrpWiseNationPerformanceChart() {
		const chartData = indexFilter.pi2020FilterData.crps
			.map((data) => {
				const result = { id: data.crp_id, name: data.crp_name };
				result.data = this.tbghs.filter((d) =>
					d.crp.some((e) => e.crp_id == data.crp_id)
				);
				result.count = result.data.length;
				return result;
			})
			.filter((d) => d.count > 0);

		Highcharts.chart("mpr-mapCropwise_hybrid_entered_in_national", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#7cb5ec"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: chartData.map((d) => d.name),
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
					text: "Number of lines",
				},
				labels: {
					overflow: "justify",
				},
			},
			legend: {
				enabled: false,
			},
			tooltip: {
				pointFormat: "<b>{point.y}</b>",
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
					name: "",
					data: chartData.map((d) => d.count),
				},
			],
		});

		$("#table-8-tbody").html(
			chartData.map((e) => `<tr><td>${e.name}</td><td>${e.count == 0 ? "NA" : e.count}</td></tr>`)
		);

		let tfData = chartData.map((e) => e.count).reduce((a, b) => a + b , 0)

		$("#table-8-tfoot").html(
			`<tr><td>Total</td><td>${ tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	generateRPNationPerformanceChart() {
		const chartData = indexFilter.pi2020FilterData.reasearchprograms
			.map((data) => {
				const result = { rpId: data.rp_id, rpName: data.rp_name };
				result.count = this.tbghs.filter((d) => d.rp_id == data.rp_id).length;
				return result;
			})
			.filter((d) => d.count > 0);
		const rpTotal = chartData
			.map((d) => d.count)
			.reduce((v1, v2) => v1 + v2, 0);
		chartData.forEach((d) => {
			if (rpTotal) {
				d.avg = (d.count / rpTotal) * 100;
			} else {
				d.avg = 0;
			}
		});
		$("#rp-legends").html("");
		Highcharts.chart("mpr-pieChartType2", {
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
						format: "{point.name}: <b>{point.y}</b> ({point.percentage:.2f} %)",
						style: { textOutline: false },
					},
					showInLegend: false,
				},
			},
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat:
					'<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)<br/>',
			},
			series: [
				{
					name: "Hybrids / Varieties",
					colorByPoint: true,
					data: chartData.map((d) => {
						return { name: d.rpName, y: d.count };
					}),
				},
			],
		});
		const legendsHtml = chartData.map((d) => {
			return `
            <li>
            <div class="mr-3">${Number(d.avg.toFixed(2))}%</div> ${d.rpName}
            </li>
            `;
		});
		$("#rp-legends").html(legendsHtml);
	}

	/* generateNutritionNationPerformanceChart() {

	const chartData = indexFilter.pi2020FilterData.nutrionaltraiss.map(data => {
	  const result = {id: data.nutrionaltraiss_id, name: data.nutrionaltraiss}
	  result.data = this.tbghs.filter(d => d.nutritional.some(e => e.nutritional_id == data.nutrionaltraiss_id));
	  result.count = result.data.length;
	  return result;
	}).filter(d => d.count > 0);

	Highcharts.chart("mpr-mapNutritional", {
	  chart: {
		type: "column"
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
		categories: chartData.map(d => d.name),
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
		},
		labels: {
		  overflow: "justify",
		},
	  },
	  legend: {
		enabled: false,
	  },
	  tooltip: {
		pointFormat: "<b>{point.y}</b>",
	  },
	  plotOptions: {
		series: {
		  dataLabels: {
			enabled: true,
			style: {textOutline: false}
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
		  name: "",
		  data: chartData.map(d => d.count),
		},
	  ],
	});

	$("#table-6-tbody").html(chartData.map(e => `<tr><td>${e.name}</td><td>${e.count}</td></tr>`))
  }*/

	generatePSNationPerformanceChart() {
		const selectedCrop = $("#national-performance-crop-name").data("value");
		const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
		const pChartData = indexFilter.pi2020FilterData.primtraits
			.map((data) => {
				const result = { id: data.primtraits_id, name: data.primtraits };
				result.data = this.tbghs.filter(
					(d) =>
						d.primaries.some((e) => e.primary_id == data.primtraits_id) &&
						search(d)
				);
				result.count = result.data.length;
				return result;
			})
			.filter((d) => d.count > 0);

		const sChartData = indexFilter.pi2020FilterData.secondarytraits
			.map((data) => {
				const result = {
					id: data.secondarytraits_id,
					name: data.secondarytraits,
				};
				result.data = this.tbghs.filter(
					(d) =>
						d.secondaries.some(
							(e) => e.secondary_id == data.secondarytraits_id
						) && search(d)
				);
				result.count = result.data.length;
				return result;
			})
			.filter((d) => d.count > 0);

		const chartData = indexFilter.pi2020FilterData.nutrionaltraiss
			.map((data) => {
				const result = {
					id: data.nutrionaltraiss_id,
					name: data.nutrionaltraiss,
				};
				result.data = this.tbghs.filter(
					(d) =>
						d.nutritional.some(
							(e) => e.nutritional_id == data.nutrionaltraiss_id
						) && search(d)
				);
				result.count = result.data.length;
				return result;
			})
			.filter((d) => d.count > 0 && d.name != null);
			// console.log(chartData);
		Highcharts.chart("mpr-mapTraitwisehybrids", {
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
				categories: pChartData.map((d) => d.name),
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
					text: "Number of lines",
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
					name: "Primary traits ",
					data: pChartData.map((d) => d.count),
				},
			],
		});
		Highcharts.chart("mpr-mapTraitwisehybrids_1", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#7cb5ec"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: sChartData.map((d) => d.name),
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
					text: "Number of lines",
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
					name: "Secondary traits ",
					data: sChartData.map((d) => d.count),
				},
			],
		});
		Highcharts.chart("mpr-mapNutritional", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			colors: ["#FFCE56"],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: chartData.map((d) => d.name),
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				min: 0,
				tickInterval: 2,
				title: {
					text: "Number of lines",
				},
				labels: {
					overflow: "justify",
				},
			},
			tooltip: {
				//pointFormat: "<b>{point.y}</b>",
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
					name: "Nutritional traits ",
					data: chartData.map((d) => d.count),
				},
			],
		});
	}

	generateCountriesMap() {
		// const mapData = indexFilter.countries.map(data => {
		//   const result = {id: data.country_id, name: data.country_name, countryCode: data.country_code};
		//   result.z =  this.tbghs.filter(d => d.country_id == data.country_id).length;
		//   return result;
		// }).filter(d => d.z > 0);

		// Highcharts.mapChart("mpr-mapChart", {
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
		//       name: "Hybrids / Varieties",
		//       joinBy: ["iso-a2", "countryCode"],
		//       data: mapData,
		//       minSize: 4,
		//       maxSize: "12%",
		//       tooltip: {
		//         pointFormat: "{point.name}: {point.z}",
		//       },
		//       dataLabels: {
		//         enabled: true,
		//         style: {textOutline: false}
		//       }
		//     },
		//   ],
		// });

		let chartData = indexFilter.pi2020FilterData.countries
			.map((data) => {
				let result = {
					id: data.country_code,
					name: data.country_name,
					color: "lightblue",
				};
				result.value = this.tbghs.filter(
					(d) => d.country_id == data.country_id
				).length;
				return result;
			})
			.filter((d) => d.value > 0);

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("mpr-mapChart", am4maps.MapChart);
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
			label.fontSize = 16;
			// label.adapter.add("dy", function (dy, target) {
			// 	var circle = target.parent.children.getIndex(0);
			// 	return circle.pixelRadius;
			// });

			chart.exporting.filePrefix = "traits-country";
			exportAmchart('dwn-img-2',chart)
		});

		$("#table-2-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.name}</td><td>${numberWithCommas(e.value) == 0 ? "NA" : numberWithCommas(e.value)}</td></tr>`
			)
		);

		let tfData = chartData.map((e) => e.value).reduce((a, b) => a + b , 0)
		$("#table-2-tfoot").html(
			`<tr><td>Total</td><td>${tfData == 0 ? "NA" : tfData}</td></tr>`
		);
	}

	generateSankeyChart() {
		const chartData = indexFilter.pi2020FilterData.countries
			.map((country) => {
				return indexFilter.pi2020FilterData.crops.map((crop) => {
					const data = this.tbghs.filter(
						(d) =>
							d.country_id == country.country_id && d.crop_id == crop.crop_id
					);
					const cropsData = {
						from: country.country_name,
						to: crop.crop_name,
						value: data.length,
					};
					const primaryData = indexFilter.pi2020FilterData.primtraits
						.map((primary) => {
							const val = clone(data).filter((d) =>
								d.primaries.some((e) => e.primary_id == primary.primtraits_id)
							).length;
							return {
								from: crop.crop_name,
								to: primary.primtraits,
								value: val,
							};
						})
						.filter((d) => d.value > 0);
					primaryData.unshift(cropsData);
					return primaryData;
				});
			})
			.flat()
			.flat()
			.filter((d) => d.value);
		$("#mpr-Hybridsenteredbasedonlocation").css(
			"height",
			chartData.length * 15 + "px"
		);
		am4core.ready(function () {
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			var chart = am4core.create(
				"mpr-Hybridsenteredbasedonlocation",
				am4charts.SankeyDiagram
			);
			chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
			chart.logo.disabled = "true";

			chart.data = chartData;
			// chart.svgContainer.htmlElement.style.height = (chartData.length * 10) + "px";

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";

			// for right-most label to fit
			chart.paddingRight = 500;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.truncate = false;

			// make nodes draggable
			var nodeTemplate = chart.nodes.template;
			nodeTemplate.inert = true;
			nodeTemplate.readerTitle = "Drag me!";
			nodeTemplate.showSystemTooltip = true;
			nodeTemplate.width = 20;

			// make nodes draggable
			var nodeTemplate = chart.nodes.template;
			nodeTemplate.readerTitle = "Click to show/hide or drag to rearrange";
			nodeTemplate.showSystemTooltip = true;
			nodeTemplate.cursorOverStyle = am4core.MouseCursorStyle.pointer;

			chart.exporting.menu = new am4core.ExportMenu();
			chart.exporting.menu.align = "right";
			chart.exporting.menu.verticalAlign = "top";
			chart.exporting.menu.items[0].icon = `${baseURL}include/assets/img/pi_2020/` + "download.svg";
			chart.exporting.filePrefix = "Hybridsenteredbasedonlocation";
		});
	}

	graphYearwiseTrials() {
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				const result = { year: yr.year };
				result.breederCount = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.BREEDINGID && d.year_id == yr.year_id
				).length;
				result.hybridLineCount = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.HYBRIDID && d.year_id == yr.year_id
				).length;
				result.germplasmCount = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.GERMPLASMID && d.year_id == yr.year_id
				).length;
				result.disaggregationCount = this.hybridNationalTrailsData.tbghs.filter(
					(d) =>
						d.hybrid_variety_area == this.DISAGGREGATION &&
						d.year_id == yr.year_id
				).length;
				return result;
			})
			.filter(
				(d) =>
					d.breederCount > 0 ||
					d.hybridLineCount > 0 ||
					d.germplasmCount > 0 ||
					d.disaggregationCount > 0
			);

		Highcharts.chart("nt-yearwise-graph", {
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
					text: "Number of lines",
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
					formatter: function(){
						return (this.y!=0)?this.y:"";
						}
				},
			},

			series: [
				{
					name: "Breeding Material",
					color: "#d79494",
					data: chartData.map((d) => d.breederCount),
				},
				{
					name: "Hybrid Lines",
					color: "#7cb5ec",
					data: chartData.map((d) => d.hybridLineCount),
				},
				{
					name: "Germplasm",
					color: "#FFCE56",
					data: chartData.map((d) => d.germplasmCount),
				},
				{
					name: "Disaggregated data is not available for the year",
					color: "#17A2B8",
					data: chartData.map((d) => d.disaggregationCount),
				},
			],
		});

		$("#table-10-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td>
					<td>${e.breederCount == 0 ? "NA" : e.breederCount}</td>
					<td>${e.hybridLineCount == 0 ? "NA" : e.hybridLineCount}</td>
					<td>${e.germplasmCount == 0 ? "NA" : e.germplasmCount}</td>
					<td>${e.disaggregationCount == 0 ? "NA" : e.disaggregationCount}</td>
					<td style="font-weight: 600;">${numberWithCommas(e.breederCount + e.germplasmCount + e.hybridLineCount + e.disaggregationCount) == 0 ? "NA" : numberWithCommas(e.breederCount + e.germplasmCount + e.hybridLineCount + e.disaggregationCount)}</td></tr>`
			)
		);

		let tbHydrid = chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0)
		let tbGerm = chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0)
		let tbBreeder = chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0)
		let tbDisag = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0)
		let tfData = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0)

		let tableFooter = `
        <tr><td>Total</td>
		<td>${ tbHydrid == 0 ? "NA" : tbHydrid}</td>
		<td>${ tbGerm == 0 ? "NA" : tbGerm}</td>
		<td>${ tbBreeder == 0 ? "NA" : tbBreeder}</td>
		<td>${ tbDisag == 0 ? "NA" : tbDisag}</td>
		<td>${ tfData == 0 ? "NA" : tfData}</td></tr>
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
					.attr("download", `traits-summary.jpeg`);
			});
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "traits-summary.csv",
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
			// 		.attr("download", `traits-country.jpeg`);
			// });
		});
		$("#dwn-csv-2").on("click", function () {
			$("#table-2-main").table2csv({
				file_name: "traits-country.csv",
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
					.attr("download", `traits-country.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "traits-country.csv",
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
					.attr("download", `traits-crop.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "traits-crop.csv",
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
					.attr("download", `traits-primary-secondary.jpeg`);
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
					.attr("download", `traits-nutrition.jpeg`);
			});
		});
		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "traits-nutrition.csv",
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
					.attr("download", `traits-crp.jpeg`);
			});
		});
		$("#dwn-csv-8").on("click", function () {
			$("#table-8-main").table2csv({
				file_name: "traits-crp.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
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
					.attr("download", `traits-year.jpeg`);
			});
		});
		$("#dwn-csv-10").on("click", function () {
			$("#table-10-main").table2csv({
				file_name: "traits-year.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Sankey-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Sankey.svg");
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
					.attr("download", `year-crop.jpeg`);
			});
		});
		$("#dwn-csv-11").on("click", function () {
			$("#table-11-main").table2csv({
				file_name: "year-crop.csv",
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
					.attr("download", `year-crp.jpeg`);
			});
		});
		$("#dwn-csv-12").on("click", function () {
			$("#table-12-main").table2csv({
				file_name: "year-crp.csv",
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
					.attr("download", `year-country.jpeg`);
			});
		});
		$("#dwn-csv-13").on("click", function () {
			$("#table-13-main").table2csv({
				file_name: "year-country.csv",
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

		this.getHtmlActionForNationalPerformanceTraits();
	}

	generteCropList(className) {
		return indexFilter.crops
			.map((d, i) => {
				let all = "";
				if (i == 0) {
					all = `<a class="dropdown-item ${className}" data-value="0" data-label="All Crops"><i class="ft-user"></i> All Crops</a>`;
				}
				return ` ${all}
      <a class="dropdown-item ${className}" data-value="${d.crop_id}" data-label="${d.crop_name}"><i class="ft-user"></i> ${d.crop_name}</a>`;
			})
			.join("\n");
	}

	getHtmlActionForNationalPerformanceTraits() {
		const cropListHtml = this.generteCropList(
			"national-performance-crops-list"
		);
		$("#national-performance-crops").html(cropListHtml);
		$(".national-performance-crops-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#national-performance-crop-name").html(data.label);
			$("#national-performance-crop-name").data("value", data.value);
			this.generatePSNationPerformanceChart();
		});
	}

	getHtmlActionForCropYearComparison() {
		const cropListHtml = this.generteCropList("year-comp-crops-list");
		$("#year-comp-crops").html(cropListHtml);
		$(".year-comp-crops-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crop-name").html(data.label);
			$("#year-comp-crop-name").data("value", data.value);
			this.getCropYearComparisonInfo();
		});
	}

	getCropYearComparisonInfo() {
		const selectedCrop = $("#year-comp-crop-name").data("value");
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				let result = { year: yr.year };
				if (selectedCrop) {
					result.hybridLineCount = this.tbghs.filter(
						(e) =>
							e.crop_id == selectedCrop &&
							e.year_id == yr.year_id &&
							e.hybrid_variety_area == 1
					).length;
					result.germplasmCount = this.tbghs.filter(
						(e) =>
							e.crop_id == selectedCrop &&
							e.year_id == yr.year_id &&
							e.hybrid_variety_area == 2
					).length;
					result.breederCount = this.tbghs.filter(
						(e) =>
							e.crop_id == selectedCrop &&
							e.year_id == yr.year_id &&
							e.hybrid_variety_area == 3
					).length;
					result.disaggregationCount = this.tbghs.filter(
						(e) =>
							e.crop_id == selectedCrop &&
							e.year_id == yr.year_id &&
							e.hybrid_variety_area == 4
					).length;
				} else {
					result.hybridLineCount = this.tbghs.filter(
						(e) => e.year_id == yr.year_id && e.hybrid_variety_area == 1
					).length;
					result.germplasmCount = this.tbghs.filter(
						(e) => e.year_id == yr.year_id && e.hybrid_variety_area == 2
					).length;
					result.breederCount = this.tbghs.filter(
						(e) => e.year_id == yr.year_id && e.hybrid_variety_area == 3
					).length;
					result.disaggregationCount = this.tbghs.filter(
						(e) => e.year_id == yr.year_id && e.hybrid_variety_area == 4
					).length;
				}
				return result;
			})
			.filter(
				(d) =>
					d.hybridLineCount ||
					d.germplasmCount ||
					d.breederCount ||
					d.disaggregationCount
			);

		// Highcharts.chart("nt-year-crop-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of lines released" },
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
		// 	series: [
		// 		{
		// 			name: "Breeding Material",
		// 			color: "#d79494",
		// 			data: chartData.map((e) => e.breederCount),
		// 		},
		// 		{
		// 			name: "Hybrid Lines",
		// 			color: "#7cb5ec",
		// 			data: chartData.map((e) => e.hybridLineCount),
		// 		},
		// 		{
		// 			name: "Germplasm",
		// 			color: "#FFCE56",
		// 			data: chartData.map((e) => e.germplasmCount),
		// 		},
		// 		{
		// 			name: "Disaggregation not available",
		// 			color: "#17A2B8",
		// 			data: chartData.map((e) => e.disaggregationCount),
		// 		},
		// 	],
		// });

		Highcharts.chart("nt-year-crop-graph", {
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
					text: "Number of lines",
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
					name: "Breeding Material",
					color: "#d79494",
					data: chartData.map((e) => e.breederCount),
				},
				{
					name: "Hybrid Lines",
					color: "#7cb5ec",
					data: chartData.map((e) => e.hybridLineCount),
				},
				{
					name: "Germplasm",
					color: "#FFCE56",
					data: chartData.map((e) => e.germplasmCount),
				},
				{
					name: "Disaggregated data is not available for the year",
					color: "#17A2B8",
					data: chartData.map((e) => e.disaggregationCount),
				},
			],
		});

		$("#table-11-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td>
					<td>${e.hybridLineCount == 0 ? "NA" : e.hybridLineCount}</td>
					<td>${e.germplasmCount == 0 ? "NA" : e.germplasmCount}</td>
					<td>${e.breederCount == 0 ? "NA" : e.breederCount}</td>
					<td>${e.disaggregationCount == 0 ? "NA" : e.disaggregationCount}</td>
					<td style="font-weight: 600;">${(e.breederCount +
						e.disaggregationCount +
						e.germplasmCount +
						e.hybridLineCount) == 0 ? "NA" : (e.breederCount +
							e.disaggregationCount +
							e.germplasmCount +
							e.hybridLineCount)}</td></tr>`
			)
		);

		let tbhybrid = chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0);
		let tbGerm = chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0);
		let tbBreed = chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0);
		let tbDisag = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0);
		let tbTotal = chartData.map((e) => e.disaggregationCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.germplasmCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.hybridLineCount).reduce((a, b) => a + b , 0) +
		chartData.map((e) => e.breederCount).reduce((a, b) => a + b , 0)
						

		let tableFooter = `
        <tr><td>Total</td>
		<td>${tbhybrid == 0 ? "NA" : tbhybrid}</td>
		<td>${tbGerm == 0 ? "NA" : tbGerm}</td>
		<td>${tbBreed == 0 ? "NA" : tbBreed}</td>
		<td>${tbDisag == 0 ? "NA" : tbDisag}</td>
		<td>${tbTotal == 0 ? "NA" : tbTotal}</td></tr>
    	`;
		$("#table-11-tfoot").html(tableFooter);
	}

	generateVarietyList(className) {
		return indexFilter.pi2020FilterData.hybrid_varieties
			.map((d, i) => {
				let all = "";
				if (i == 0) {
					all = `<a class="dropdown-item ${className}" data-value="0" data-label="All Varieties"><i class="ft-user"></i> All Varieties</a>`;
				}
				return `${all} <a class="dropdown-item ${className}" data-value="${d.hybrid_variety_id}" data-label="${d.hybrid_variety}"><i class="ft-user"></i> ${d.hybrid_variety}</a>`;
			})
			.join("\n");
	}

	getHtmlActionForCRPYearComparison() {
		const crpListHtml = this.generateVarietyList("year-comp-crp-list");
		$("#year-comp-crps").html(crpListHtml);
		$(".year-comp-crp-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-crp-name").html(data.label);
			$("#year-comp-crp-name").data("value", data.value);
			this.getCRPYearComparisonInfo();
		});
	}

	getCRPYearComparisonInfo() {
		const selectedVariety = $("#year-comp-crp-name").data("value");
		// console.log(selectedVariety);
		const chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			let yearRecords = this.tbghs.filter((e) => e.year_id == yr.year_id);
			let varietyRecords = selectedVariety
				? yearRecords.filter((e) => e.hybrid_variety_area == selectedVariety)
				: yearRecords;
			indexFilter.pi2020FilterData.crps.forEach((crp) => {
				result[crp.crp_name] = varietyRecords.filter((e) =>
					e.crp.map((f) => f.crp_id).includes(crp.crp_id)
				).length;
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


		// Highcharts.chart("nt-year-crp-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of lines released" },
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
			return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name]) };
		})
		
		$("#nt-year-crp-graph").css("height", serz.length * 3 + "em");
		Highcharts.chart("nt-year-crp-graph", {
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
					text: "Number of lines",
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
			`<th>CRP</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-12-tbody").html(
			indexFilter.pi2020FilterData.crps.map((e) => {
				let yValsHtml = chartData.map((f) => `<td>${f[e.crp_name] == 0 ? "NA" : f[e.crp_name]}</td>`);
				let yVals = chartData.map((f) => f[e.crp_name]).reduce((a, b) => a + b , 0);

				return `<tr><td>${e.crp_name}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : yVals}</td></tr>`;
			})
		);
		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b , 0);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-12-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue == 0 ? "NA" : ttvalue}</td></tr>`
		);
	}

	getHtmlActionForCountryYearComparison() {
		const crpListHtml = this.generateVarietyList("year-comp-country-list");
		$("#year-comp-country").html(crpListHtml);
		$(".year-comp-country-list").on("click", (env) => {
			const ele = $(env.target);
			const data = ele.data();
			$("#year-comp-country-name").html(data.label);
			$("#year-comp-country-name").data("value", data.value);
			this.getCountryYearComparisonInfo();
		});
	}

	getCountryYearComparisonInfo() {
		const selectedVariety = $("#year-comp-country-name").data("value");
		// console.log(selectedVariety);
		const chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			let yearRecords = this.tbghs.filter((e) => e.year_id == yr.year_id);
			let varietyRecords = selectedVariety
				? yearRecords.filter((e) => e.hybrid_variety_area == selectedVariety)
				: yearRecords;
			indexFilter.pi2020FilterData.countries.forEach((c) => {
				result[c.country_name] = varietyRecords.filter(
					(e) => e.country_id == c.country_id
				).length;
			});
			return result;
		});

		// Highcharts.chart("nt-year-country-graph", {
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
		// 		title: { text: "Number of lines released" },
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
		// 	series: indexFilter.pi2020FilterData.countries
		// 		.map((e) => {
		// 			return {
		// 				name: e.country_name,
		// 				data: chartData
		// 					.filter((f) => f[e.country_name] > 0)
		// 					.map((f) => f[e.country_name]),
		// 			};
		// 		})
		// 		.filter(
		// 			(e) =>
		// 				!Object.keys(e.data)
		// 					.map((f) => e[f])
		// 					.every((f) => f == 0)
		// 		),
		// });

		Highcharts.chart("nt-year-country-graph", {
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
			yAxis: {
				title: {
					text: "Number of lines",
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
			series: indexFilter.pi2020FilterData.countries
				.map((e) => {
					return {
						name: e.country_name,
						data: chartData
							.filter((f) => f[e.country_name] > 0)
							.map((f) => f[e.country_name]),
					};
				})
				.filter(
					(e) =>
						!Object.keys(e.data)
							.map((f) => e[f])
							.every((f) => f == 0)
				),
		});

		$("#table-13-thead-row").html(
			`<th>Countries</th>` +
				chartData.map((e) => `<th>${e.year}</th>`) +
				`<th>Total</th>`
		);
		$("#table-13-tbody").html(
			indexFilter.pi2020FilterData.countries.map((e) => {
				let yVals = chartData.map((f) => f[e.country_name]);
				if (!yVals.every((e) => e == 0)) {
					let yValsHtml = chartData.map(
						(f) => `<td>${numberWithCommas(f[e.country_name]) == 0 ? "NA" : numberWithCommas(f[e.country_name])}</td>`
					);
					let yVals = chartData
						.map((f) => f[e.country_name])
						.reduce((a, b) => a + b , 0);

					return `<tr><td>${e.country_name}</td>${yValsHtml}<td style="font-weight: 600;">${yVals == 0 ? "NA" : yVals}</td></tr>`;
				}
			})
		);

		let totals = chartData.map((e) => {
			let allKeys = Object.keys(e).filter((e) => e != "year");
			return allKeys.map((f) => e[f]).reduce((a, b) => a + b, 0);
		});

		let ttvalue = totals.map((e) => Number(e)).reduce((a, b) => a + b , 0);
		let totalsHtml = totals.map((e) => `<td>${numberWithCommas(e) == 0 ? "NA" : numberWithCommas(e)}</td>`);
		$("#table-13-tfoot").html(
			`<tr><td>Total</td>${totalsHtml}<td>${ttvalue == 0 ? "NA" : ttvalue}</td></tr>`
		);
	}
}
