var script = document.getElementById("smJS"),
	baseURL = script.getAttribute("data-baseurl");

class Grants {
	constructor() { }
	init() {
		this.getGrantsData();
	}

	getGrantsData() {
		const request = indexFilter.getFilteredData();
		request.purpose = "get_active_projects";
		const promises = [
			post("pi_2020", request),
			get(baseURL + "/include/assets/js/pi_2020/tabs/grant_tab.html", true),
		];

		Promise.all(promises)
			.then((response) => {
				if (response?.length) {
					this.grantData = response[0];
					const resHtml = response[1].replaceAll(
						'src="img/',
						`src="${baseURL}include/assets/img/pi_2020/`
					);
					$(".mpr-tab-contend").html(resHtml);
					this.arrangeData();
					setTimeout(() => {
						$("#sdg_framework_chart_container").hide();
						$("#table-1,#table-2,#table-3,#table-4").hide();
						this.generateChart();
					});
				}
			})
			.catch((err) => console.log(err));
	}

	arrangeData() {
		this.tapsData = clone(this.grantData.taps).map((d) => {
			d.tap_benef_countries = this.grantData.tap_benef_countries.filter(
				(e) => e.data_id == d.data_id
			);
			d.tap_impl_countries = this.grantData.tap_impl_countries.filter(
				(e) => e.data_id == d.data_id
			);
			d.tap_sdgs = this.grantData.tap_sdgs.filter(
				(e) => e.data_id == d.data_id
			);
			return d;
		});
	}

	generateChart() {
		// this.generateCountActiveProjchart();
		// this.generateSourceFundContriSdgchart();
		// this.generateCountryXSdgchart();
		// this.generateCountryXDonorchart();
		// this.generateFoundContriSdgchart();
		// this.generateNturFundrCntrbtnSdgchart();
		this.generateFundingSourceChart();
		this.generateDrilldown();
		this.generatePrjtCrpsSdgPiechart();
		this.generatePrjtCrpsSdgColumnchart();
		this.generateActiveProjectTable();
		// this.generateSdgProjTable();
		this.generateCtrySdgHeatChart();
		this.generateActions();
	}

	generateActiveProjectTable() {
		const project_name = this.tapsData
			.filter((d) => d.donor_status == 1)
			.map((c) => {
				return `<tr><td>${c.proj_title}</td></tr>`;
			});

		if (project_name.length == 0) {
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

		$("#active_project_table").html(project_name);
		$("#total_projects").html(this.tapsData.length);
		$("#total_active_projects").html(project_name.length);
	}
	generateFundingSourceChart() {
		const chartData = indexFilter.pi2020FilterData.funding_source
			.map((f) => {
				const name = f.fs;
				const y = this.tapsData.filter(
					(d) => d.funding_source == f.fs_id && d.donor_status == 1
				).length;
				return { name, y };
			})
			.filter((d) => d.y > 0);
		const tableData = chartData.map((d) => {
			const result = `
			<td>${d.name}</td>
			<td>${d.y}</td>
			`;
			return `<tr>${result}</tr>`;
		});
		const tot = chartData.map((d) => d.y).reduce((v1, v2) => v1 + v2, 0);
		tableData.push(`<tr>
						<th>Total</th>
						<th>${tot}</th>
		</tr>`);
		$("#table-1>div>table>tbody").html(tableData);
		Highcharts.chart("windows_pie", {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: "pie",
			},
			title: {
				text: "",
			},
			colors: [
				"#d79494",
				"#7cb5ec",
				"#FFCE56",
				"#4E789A",
				"#8bbc21",
				"#910000",
			],
			tooltip: {
				pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>",
			},
			accessibility: {
				point: {
					valueSuffix: "%",
				},
			},
			plotOptions: {
				pie: {
					allowPointSelect: false,
					cursor: "pointer",
					dataLabels: {
						enabled: false,
					},
					showInLegend: true,
				},
			},
			series: [
				{
					name: "Totals of Windows",
					colorByPoint: true,
					data: chartData,
				},
			],
		});
	}

	generateDrilldown() {
		const countryList = [
			...new Set(
				this.tapsData
					.map((d) => d.tap_impl_countries.map((d) => d.country_id))
					.flat()
			),
		];


		const chartData = indexFilter.pi2020FilterData.countries.filter(d => countryList.includes(d.country_id)).map(country => {
			const result = { name: country.country_name };
			result.children = this.tapsData.filter(d => d.donor_status == 1 && d.tap_impl_countries.some((e) => e.country_id == country.country_id)).map(d => {
				return { name: d.proj_title, count: 1 }
			});
			result.children = Array.from(new Set(result.children.map(e => e.name))).map(e => {
				return { name: e, count: result.children.filter(f => f.name == e).length };
			});
			result.count = result.children.length
			return result;
		});

		am4core.ready(function () {
			// Themes begin
			am4core.useTheme(am4themes_animated);
			am4core.useTheme(am4themes_kelly);
			// Themes end

			// create chart
			var chart = am4core.create("drilldownChart", am4charts.TreeMap);
			chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect

			// chart.colors.list = [
			// 	am4core.color("#F3C300"),
			// 	am4core.color("#875692"),
			// 	am4core.color("#F38400"),
			// 	am4core.color("#A1CAF1"),
			// 	am4core.color("#BE0032"),
			// 	am4core.color("#C2B280"),
			// 	am4core.color("#848482"),
			// 	am4core.color("#008856"),
			// 	am4core.color("#E68FAC"),
			// 	am4core.color("#0067A5"),
			// 	am4core.color("#F99379"),
			// 	am4core.color("#604E97"),
			// 	am4core.color("#F6A600"),
			// 	am4core.color("#B3446C"),
			// 	am4core.color("#DCD300"),
			// 	am4core.color("#882D17"),
			// 	am4core.color("#8DB600"),
			// 	am4core.color("#654522"),
			// 	am4core.color("#E25822"),
			// 	//  am4core.color("#2B3D26"),
			// 	am4core.color("#F2F3F4"),
			// 	// am4core.color("#222222"),
			// ];

			chart.colors.list = [
				am4core.color("#3a1302"),
				am4core.color("#601205"),
				am4core.color("#8a2b0d"),
				am4core.color("#c75e24"),
				am4core.color("#c79f59"),
				am4core.color("#a4956a"),
				am4core.color("#868569"),
				am4core.color("#756f61"),
				am4core.color("#586160"),
				am4core.color("#617983")
			]

			chart.logo.disabled = "true";
			// only one level visible initially
			chart.maxLevels = 1;
			// define data fields
			chart.dataFields.value = "count";
			chart.dataFields.name = "name";
			chart.dataFields.children = "children";
			chart.homeText = "Country-wise projects";

			// enable navigation
			chart.navigationBar = new am4charts.NavigationBar();

			// level 0 series template
			var level0SeriesTemplate = chart.seriesTemplates.create("0");
			level0SeriesTemplate.strokeWidth = 2;

			// by default only current level series bullets are visible, but as we need brand bullets to be visible all the time, we modify it's hidden state
			level0SeriesTemplate.bulletsContainer.hiddenState.properties.opacity = 1;
			level0SeriesTemplate.bulletsContainer.hiddenState.properties.visible = true;
			level0SeriesTemplate.columns.template.tooltipText = "{name} ({count})";
			// create hover state
			var columnTemplate = level0SeriesTemplate.columns.template;
			var hoverState = columnTemplate.states.create("hover");


			// darken
			hoverState.adapter.add("fill", function (fill, target) {
				if (fill instanceof am4core.Color) {
					return am4core.color(am4core.colors.brighten(fill.rgb, -0.2));
				}
				return fill;
			});

			// add logo
			var image = columnTemplate.createChild(am4core.Image);
			image.opacity = 0.15;
			image.align = "center";
			image.valign = "middle";
			image.width = am4core.percent(80);
			image.height = am4core.percent(80);

			var level0SeriesTemplate = chart.seriesTemplates.create("0");
			level0SeriesTemplate.columns.template.fillOpacity = 1;

			var bullet1 = level0SeriesTemplate.bullets.push(
				new am4charts.LabelBullet()
			);
			bullet1.locationX = 0.5;
			bullet1.locationY = 0.5;
			bullet1.label.text = "{name} ({count})";
			bullet1.label.fontSize = "18px";
			bullet1.label.fill = am4core.color("#fff");

			var image = columnTemplate.createChild(am4core.Image);
			image.opacity = 0.15;
			image.align = "center";
			image.valign = "middle";
			image.width = am4core.percent(80);
			image.height = am4core.percent(80);

			// add adapter for href to load correct image
			// image.adapter.add("href", function (href, target) {
			//   var dataItem = target.parent.dataItem;
			//   if (dataItem) {
			// 	return "https://www.amcharts.com/lib/images/logos/" + dataItem.treeMapDataItem.name.toLowerCase() + ".png";
			//   }
			// });

			image.adapter.add("href", (href, target) => {
				let d = target.dataItem._dataContext;
				if (d._dataContext.name) {
					// const conutryCode = d._dataContext.name.toLowerCase();
					const conutryCode = d._dataContext.name;
					return `${baseURL}include/assets/img/pi_2020/flags_rounded/flag-of-${conutryCode}.png`;
				}
				return href;
			});

			// level1 series template
			var level1SeriesTemplate = chart.seriesTemplates.create("1");
			level1SeriesTemplate.columns.template.fillOpacity = 0;
			level1SeriesTemplate.columns.template.tooltipText =
				"{parent.name} - {name}";

			var bullet1 = level1SeriesTemplate.bullets.push(
				new am4charts.LabelBullet()
			);
			bullet1.locationX = 0.5;
			bullet1.locationY = 0.6;
			bullet1.label.text = "{name}";
			// bullet1.label.text = "{name}";
			bullet1.label.fill = am4core.color("#fff");

			// level2 series template
			// var level2SeriesTemplate = chart.seriesTemplates.create("2");
			// level2SeriesTemplate.columns.template.fillOpacity = 0;

			// var bullet2 = level2SeriesTemplate.bullets.push(new am4charts.LabelBullet());
			// bullet2.locationX = 0.5;
			// bullet2.locationY = 0.5;
			// bullet2.label.text = "{name}";
			// bullet2.label.fill = am4core.color("#ff0000");

			chart.data = chartData;
			//   chart.data = processData(cData);
			chart.exporting.filePrefix = "Projects corresponding to countries";
			exportAmchart("box_chart_sdg_framework_download", chart);
		});
	}

	generateCountActiveProjchart() {
		const chartData = indexFilter.pi2020FilterData.countries
			.map((f) => {
				const name = f.country_name;
				const y = this.tapsData.filter(
					(d) =>
						d.tap_impl_countries.some((e) => e.country_id == f.country_id) &&
						d.donor_status == 1
				).length;
				return { name, y };
			})
			.filter((d) => d.y > 0);
		const tableData = chartData.map((d) => {
			const result = `
				<td>${d.name}</td>
				<td>${d.y}</td>
				`;
			return `<tr>${result}</tr>`;
		});
		const tot = chartData.map((d) => d.y).reduce((v1, v2) => v1 + v2, 0);
		tableData.push(`<tr>
						<th>Total</th>
						<th>${tot}</th>
		</tr>`);
		$("#table-4>div>table>tbody").html(tableData);
		Highcharts.chart("Active_Projects_across_Countries", {
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
					text: "<span><b>Number of Projects</b></span>",
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
						enabled: false,
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
					maxPointWidth: 30,
					name: "",
					data: chartData.map((d) => d.y),
				},
			],
		});
	}

	generateSourceFundContriSdgchart() {
		const chartData = indexFilter.pi2020FilterData.sdgs
			.map((sdg) => {
				return indexFilter.pi2020FilterData.funding_source.map((fund) => {
					const result = {
						from: fund.fs,
						to: sdg.sdg_name,
						value: 0,
						width: 10,
					};
					result.value = this.tapsData.filter(
						(d) =>
						(d.funding_source =
							fund.fs_id && d.tap_sdgs.some((e) => e.sdg_id == sdg.sdg_id))
					).length;
					return result;
				});
			})
			.flat()
			.filter((d) => d.value);
		if (chartData.length > 10) {
			$("#Source_of_funding_contributing_to_SDGs").css(
				"height",
				`${chartData.length * 20}px`
			);
		}
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"Source_of_funding_contributing_to_SDGs",
				am4charts.SankeyDiagram
			);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.paddingRight = 450;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.truncate = false;

			chart.data = chartData;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";
			chart.dataFields.color = "nodeColor";

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

			chart.exporting.filePrefix = "Source of funding contributing to SDGs";
			exportAmchart(
				"Source_of_funding_contributing_to_SDGs_screenshot_download",
				chart
			);
		});
	}

	generateCountryXSdgchart() {
		const chartData = indexFilter.pi2020FilterData.countries
			.map((country) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const result = {
						from: country.country_name,
						to: sdg.sdg_name,
						value: 0,
						width: 10,
					};
					result.value = this.tapsData.filter(
						(d) =>
							d.tap_impl_countries.some(
								(e) => e.country_id == country.country_id
							) && d.tap_sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return result;
				});
			})
			.flat()
			.filter((d) => d.value);
		if (chartData.length > 10) {
			$("#Country_sdg").css("height", `${chartData.length * 20}px`);
		}
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("Country_sdg", am4charts.SankeyDiagram);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.paddingRight = 450;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.truncate = false;

			chart.data = chartData;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";
			chart.dataFields.color = "nodeColor";

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
			chart.exporting.filePrefix = "Country projects corresponding to SDGs";
			exportAmchart("Country_sdg_screenshot_download", chart);
		});
	}

	generateCountryXDonorchart() {
		const chartData = indexFilter.pi2020FilterData.countries
			.map((country) => {
				return indexFilter.pi2020FilterData.funder_name.map((fund) => {
					const result = {
						from: country.country_name,
						to: fund.fund_name,
						value: 0,
						width: 10,
					};
					result.value = this.tapsData.filter(
						(d) =>
							d.tap_impl_countries.some(
								(e) => e.country_id == country.country_id
							) && d.funder_name == fund.fund_name_id
					).length;
					return result;
				});
			})
			.flat()
			.filter((d) => d.value);

		if (chartData.length > 10) {
			$("#Country_donor").css("height", `${chartData.length * 20}px`);
		}

		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("Country_donor", am4charts.SankeyDiagram);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.paddingRight = 450;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.truncate = false;

			chart.data = chartData;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";
			chart.dataFields.color = "nodeColor";

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

			chart.exporting.filePrefix = "Country projects corresponding to Donors";
			exportAmchart("Country_donor_screenshot_download", chart);
		});
	}

	generateFoundContriSdgchart() {
		const chartData = indexFilter.pi2020FilterData.funder_name
			.map((fund) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const result = {
						from: fund.fund_name,
						to: sdg.sdg_name,
						value: 0,
						width: 10,
					};
					result.value = this.tapsData.filter(
						(d) =>
							d.funder_name == fund.fund_name_id &&
							d.tap_sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return result;
				});
			})
			.flat()
			.filter((d) => d.value);
		if (chartData.length > 10) {
			$("#Funders_contribution_to_SDGs").css(
				"height",
				`${chartData.length * 100}px`
			);
		}
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"Funders_contribution_to_SDGs",
				am4charts.SankeyDiagram
			);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.paddingRight = 200;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.wrap = true;
			chart.nodes.template.nameLabel.label.truncate = false;

			chart.data = chartData;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";
			chart.dataFields.color = "nodeColor";

			var nodeTemplate = chart.nodes.template;
			nodeTemplate.inert = true;
			nodeTemplate.readerTitle = "Drag me!";
			nodeTemplate.showSystemTooltip = true;
			nodeTemplate.width = 20;

			nodeTemplate.propertyFields.width = "width";

			var nodeTemplate = chart.nodes.template;
			nodeTemplate.readerTitle = "{from} -> {to}";
			nodeTemplate.showSystemTooltip = true;
			nodeTemplate.cursorOverStyle = am4core.MouseCursorStyle.pointer;

			chart.exporting.filePrefix = "Funders contribution to SDGs";
			exportAmchart("Funders_contribution_to_SDGs_screenshot_download", chart);
		});
	}

	generateNturFundrCntrbtnSdgchart() {
		const chartData = indexFilter.pi2020FilterData.funder_nature
			.map((fund) => {
				return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
					const result = {
						from: fund.fn,
						to: sdg.sdg_name,
						value: 0,
						width: 10,
					};
					result.value = this.tapsData.filter(
						(d) =>
							d.funder_nature == fund.fn_id &&
							d.tap_sdgs.some((e) => e.sdg_id == sdg.sdg_id)
					).length;
					return result;
				});
			})
			.flat()
			.filter((d) => d.value);
		if (chartData.length > 10) {
			$("#Nature_of_Funders_contribution_to_SDGs").css(
				"height",
				`${chartData.length * 20}px`
			);
		}
		am4core.ready(function () {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create(
				"Nature_of_Funders_contribution_to_SDGs",
				am4charts.SankeyDiagram
			);
			chart.hiddenState.properties.opacity = 0;
			chart.logo.disabled = "true";

			chart.paddingRight = 450;
			chart.paddingLeft = 10;
			chart.paddingTop = 10;
			chart.paddingBottom = 50;
			chart.nodes.template.nameLabel.label.truncate = false;

			chart.data = chartData;

			let hoverState = chart.links.template.states.create("hover");
			hoverState.properties.fillOpacity = 0.6;

			chart.dataFields.fromName = "from";
			chart.dataFields.toName = "to";
			chart.dataFields.value = "value";
			chart.dataFields.color = "nodeColor";

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
			chart.exporting.filePrefix = "Nature of Funders contribution to SDGs";
			exportAmchart(
				"Nature_of_Funders_contribution_to_SDGs_screenshot_download",
				chart
			);
		});
	}

	generatePrjtCrpsSdgPiechart() {
		const chartData = indexFilter.pi2020FilterData.sdgs
			.map((f) => {
				const name = f.sdg_name;
				const y = this.tapsData.filter(
					(d) =>
						d.tap_sdgs.some((e) => e.sdg_id == f.sdg_id) && d.donor_status == 1
				).length;
				return { name, y };
			})
			.filter((d) => d.y > 0);
		const tableData = chartData.map((d) => {
			const result = `
				<td>${d.name}</td>
				<td>${d.y}</td>
				`;
			return `<tr>${result}</tr>`;
		});
		const tot = chartData.map((d) => d.y).reduce((v1, v2) => v1 + v2, 0);
		tableData.push(`<tr>
						<th>Total</th>
						<th>${tot}</th>
		</tr>`);
		$("#table-3>div>table>tbody").html(tableData);
		Highcharts.chart("corresponding_sdgs_pie", {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: "pie",
			},
			title: {
				text: "",
			},
			colors: [
				"#ec1b2e",
				"#d3a02a",
				"#289c49",
				"#c51f33",
				"#ef402b",
				"#00aed9",
				"#fdb815",
				"#901937",
				"#f36d24",
				"#e01485",
				"#fa9d26",
				"#cf8e2a",
				"#487740",
				"#017dbb",
				"#3db049",
				"#04558c",
				"#183668",
			],
			tooltip: {
				pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>",
			},
			accessibility: {
				point: {
					valueSuffix: "%",
				},
			},
			plotOptions: {
				column: {
					colorByPoint: true,
				},
				pie: {
					allowPointSelect: false,
					cursor: "pointer",
					dataLabels: {
						enabled: false,
					},
					showInLegend: true,
				},
			},
			series: [
				{
					name: "SDGs",
					colorByPoint: true,
					data: chartData,
				},
			],
		});
	}

	generatePrjtCrpsSdgColumnchart() {
		const chartData = indexFilter.pi2020FilterData.sdgs
			.map((f) => {
				const name = f.sdg_name;
				const y = this.tapsData.filter(
					(d) =>
						d.tap_sdgs.some((e) => e.sdg_id == f.sdg_id) && d.donor_status == 1
				).length;
				return { name, y };
			})
			.filter((d) => d.y > 0);
		const maxVal = Math.max(...chartData.map(d => d.y));
		const breakarray = [
			{
				from: (maxVal * 5) / 100,
				to: (maxVal * 95) / 100,
			},
		];
		const tableData = chartData.map((d) => {
			const result = `
				<td>${d.name}</td>
				<td>${d.y}</td>
				`;
			return `<tr>${result}</tr>`;
		});
		const tot = chartData.map((d) => d.y).reduce((v1, v2) => v1 + v2, 0);
		tableData.push(`<tr>
						<th>Total</th>
						<th>${tot}</th>
		</tr>`);
		$("#table-2>div>table>tbody").html(tableData);
		Highcharts.chart("corresponding_sdgs_bar", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			colors: [
				"#ec1b2e",
				"#d3a02a",
				"#289c49",
				"#c51f33",
				"#ef402b",
				"#00aed9",
				"#fdb815",
				"#901937",
				"#f36d24",
				"#e01485",
				"#fa9d26",
				"#cf8e2a",
				"#487740",
				"#017dbb",
				"#3db049",
				"#04558c",
				"#183668",
			],
			subtitle: {
				text: null,
			},

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
					text: "<span><b>Number of Projects</b></span>",
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
				pointFormat: "<b>{point.y}</b>",
			},
			plotOptions: {
				column: {
					colorByPoint: true,
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
				series: {
					dataLabels: {
						enabled: false,
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
					maxPointWidth: 30,
					name: "",
					data: chartData.map((d) => d.y),
				},
			],
		});
	}

	generateSdgProjTable() {
		const hsdgIds = $.map($("[data-head_sdgid]"), (e) =>
			$(e).data("head_sdgid")
		);
		const tableData = this.tapsData
			.filter((d) => d.donor_status == 1)
			.map((d) => {
				let result = `<td>
			<div class="text-left" style="width: 200px;">${d.proj_title}</div>
			</td>\n`;

				result += hsdgIds
					.map((sdg) => {
						const isAvaliable = d.tap_sdgs.some((e) => e.sdg_id == sdg)
							? `<img src="${baseURL}include/assets/img/pi_2020/check.png">`
							: "-";
						return `<td>${isAvaliable}</td>`;
					})
					.join("\n");

				return `<tr>${result}</tr>`;
			})
			.join("\n");

		$("#sdg_proj_tbody").html(tableData);
	}

	generateActions() {
		const imgUrl = `${baseURL}include/assets/img/pi_2020/`;
		const pie = "Pie.svg";
		const pieSelected = "Pie-selected.svg";
		const bar = "Bar.svg";
		const barSelected = "Bar-selected.svg";
		const table = "Table.svg";
		const tableSelected = "Table-selected.svg";

		const [graph1, graph2, graph3, graph4] = [
			$("#graph-1"),
			$("#graph-2"),
			$("#graph-3"),
			$("#graph-4"),
		];
		const [table1, table2, table3, table4] = [
			$("#table-1"),
			$("#table-2"),
			$("#table-3"),
			$("#table-4"),
		];

		const grphBtn1 = $("#graph-btn-1");
		const tblBtn1 = $("#table-btn-1");
		const downloadTab1 = $("#download-btn-1>img");

		const grphBtn2 = $("#graph-btn-2");
		const tblBtn2 = $("#table-btn-2");
		const downloadTab2 = $("#download-btn-2>img");

		const grphBtn3 = $("#graph-btn-3");
		const tblBtn3 = $("#table-btn-3");
		const downloadTab3 = $("#download-btn-3>img");

		const grphBtn4 = $("#graph-btn-4");
		const tblBtn4 = $("#table-btn-4");
		const downloadTab4 = $("#download-btn-4>img");

		//single table
		$("#dwn-csv-0").on("click", function () {
			$("#table-0-main").table2csv({
				file_name: "grant.csv",
				header_body_space: 0,
			});
		});

		// Buttons Actions for First Chart
		grphBtn1.on("click", () => {
			grphBtn1.addClass("active");
			tblBtn1.removeClass("active");
			grphBtn1.find("img").prop("src", imgUrl + pieSelected);
			tblBtn1.find("img").prop("src", imgUrl + table);
			graph1.show();
			table1.hide();
		});

		tblBtn1.on("click", () => {
			tblBtn1.addClass("active");
			grphBtn1.removeClass("active");
			tblBtn1.find("img").prop("src", imgUrl + tableSelected);
			grphBtn1.find("img").prop("src", imgUrl + pie);
			table1.show();
			graph1.hide();
		});
		downloadTab1.on("click", () => {
			if (downloadTab1.attr("src") == "img/download.svg") {
				downloadTab1.prop("src", "img/Download-selected.svg");
			} else if (downloadTab1.attr("src") == "img/Download-selected.svg") {
				downloadTab1.prop("src", "img/download.svg");
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
					.attr("download", `grant.jpeg`);
			});
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "grant.csv",
				header_body_space: 0,
			});
		});

		// Buttons Actions for Second Chart
		grphBtn2.on("click", () => {
			grphBtn2.addClass("active");
			tblBtn2.removeClass("active");
			grphBtn2.find("img").prop("src", imgUrl + barSelected);
			tblBtn2.find("img").prop("src", imgUrl + table);
			graph2.show();
			table2.hide();
		});

		tblBtn2.on("click", () => {
			tblBtn2.addClass("active");
			grphBtn2.removeClass("active");
			tblBtn2.find("img").prop("src", imgUrl + tableSelected);
			grphBtn2.find("img").prop("src", imgUrl + bar);
			table2.show();
			graph2.hide();
		});
		downloadTab2.on("click", () => {
			if (downloadTab2.attr("src") == "img/download.svg") {
				downloadTab2.prop("src", "img/Download-selected.svg");
			} else if (downloadTab2.attr("src") == "img/Download-selected.svg") {
				downloadTab2.prop("src", "img/download.svg");
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
					.attr("download", `grant.jpeg`);
			});
		});
		$("#dwn-csv-2").on("click", function () {
			$("#table-2-main").table2csv({
				file_name: "grant.csv",
				header_body_space: 0,
			});
		});

		// Buttons Actions for Second Chart
		grphBtn3.on("click", () => {
			grphBtn3.addClass("active");
			tblBtn3.removeClass("active");
			grphBtn3.find("img").prop("src", imgUrl + pieSelected);
			tblBtn3.find("img").prop("src", imgUrl + table);
			graph3.show();
			table3.hide();
		});

		tblBtn3.on("click", () => {
			tblBtn3.addClass("active");
			grphBtn3.removeClass("active");
			tblBtn3.find("img").prop("src", imgUrl + tableSelected);
			grphBtn3.find("img").prop("src", imgUrl + pie);
			table3.show();
			graph3.hide();
		});
		downloadTab3.on("click", () => {
			if (downloadTab3.attr("src") == "img/download.svg") {
				downloadTab3.prop("src", "img/Download-selected.svg");
			} else if (downloadTab3.attr("src") == "img/Download-selected.svg") {
				downloadTab3.prop("src", "img/download.svg");
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
					.attr("download", `grant.jpeg`);
			});
		});
		$("#dwn-csv-3").on("click", function () {
			$("#table-3-main").table2csv({
				file_name: "grant.csv",
				header_body_space: 0,
			});
		});

		// Buttons Actions for Second Chart
		grphBtn4.on("click", () => {
			grphBtn4.addClass("active");
			tblBtn4.removeClass("active");
			grphBtn4.find("img").prop("src", imgUrl + barSelected);
			tblBtn4.find("img").prop("src", imgUrl + table);
			graph4.show();
			table4.hide();
		});

		tblBtn4.on("click", () => {
			tblBtn4.addClass("active");
			grphBtn4.removeClass("active");
			tblBtn4.find("img").prop("src", imgUrl + tableSelected);
			grphBtn4.find("img").prop("src", imgUrl + bar);
			table4.show();
			graph4.hide();
		});
		downloadTab4.on("click", () => {
			if (downloadTab4.attr("src") == "img/download.svg") {
				downloadTab4.prop("src", "img/Download-selected.svg");
			} else if (downloadTab4.attr("src") == "img/Download-selected.svg") {
				downloadTab4.prop("src", "img/download.svg");
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
					.attr("download", `grant.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "grant.csv",
				header_body_space: 0,
			});
		});
	}

	generateCtrySdgHeatChart() {
		if (navigator.userAgent.indexOf("Safari") >= 0 && navigator.userAgent.indexOf("Chrome") < 0) {
			return
		}
		$("#sdg_framework_chart_container").show();

		const colors = [
			"#f1f1f1", // 0
			"#fecd6e", // 1 to 2
			"#2ca2fc", // 2 to 5
			"#8770d6", // 5 to 8
			"#ff6e83", // 8 to 14
			"#54b4fb", // 14 to 23
			"#29A829", // > 23
			"#d9dce1" // Total
		]
		const sdgs = indexFilter.pi2020FilterData.sdgs
			.map((d) => {
				d.seq = Number(d.sdg_name.match(/[0-9]/g).join(""));
				return d;
			})
			.sort((a, b) => a.seq - b.seq);
		const countryIds = [
			...new Set(
				this.tapsData
					.map((d) => d.tap_impl_countries)
					.flat()
					.map((d) => d.country_id)
			),
		];
		const tempData = indexFilter.pi2020FilterData.countries
			.filter((c) => countryIds.includes(c.country_id))
			.map((country, i) => {
				return sdgs.map((sdg) => {
					const projects = this.tapsData.filter(
						(d) =>
							d.tap_sdgs.some((s) => s.sdg_id == sdg.sdg_id) &&
							d.tap_impl_countries.some(
								(c) => c.country_id == country.country_id
							) && d.donor_status == 1
					);
					const result = {
						y: country.country_name,
						x: sdg.sdg_id,
						value: projects.length,
						sdg_id: sdg.sdg_id,
						sdg_name: sdg.sdg_name,
						country_id: country.country_id,
						country_code: country.country_code,
						country_name: country.country_name,
					};
					if (i == 0) {
						result.bullet = `${baseURL}include/assets/img/pi_2020/sdgimages/E_WEB_${sdg.seq
							.toString()
							.padStart(2, 0)}.png`;
					}
					if (result.value == 0) {
						result.color = colors[0];
						result.status = "complicated";
					} else if (result.value >= 1 && result.value < 2) {
						result.color = colors[1];
						result.status = "bad";
					} else if (result.value >= 2 && result.value < 5) {
						result.color = colors[2];
						result.status = "satisfatory";
					} else if (result.value >= 5 && result.value < 8) {
						result.color = colors[3];
						result.status = "good";
					} else if (result.value >= 8 && result.value < 14) {
						result.color = colors[4];
						result.status = "veryok";
					} else if (result.value >= 14 && result.value < 23) {
						result.color = colors[5];
						result.status = "verygood";
					} else {
						result.color = colors[6];
						result.status = "excellent";
					}
					return result;
				});
			})
			.flat();
		const chartData = [...new Set(tempData.map(d => d.sdg_id))].map(d => {
			const allData = tempData.filter(e => e.sdg_id == d);
			const count = allData.map(e => e.value).reduce((a, b) => a + b, 0);
			if (count) {
				return allData
			} else {
				return []
			}
		}).flat();
		const totData = Array.from(new Set(chartData.map((d) => d.y))).map((d) => {
			const data = chartData.find((e) => e.y == d);
			const tot = new Set(this.tapsData.filter((d) =>
				d.tap_impl_countries.some((c) => c.country_id == data.country_id) && d.donor_status == 1
			).map(d => d.proj_title)).size;
			return {
				y: d,
				x: "Total",
				value: tot,
				sdg_id: "NA",
				sdg_name: "NA",
				country_id: data?.country_id,
				country_code: data?.country_code,
				country_name: data?.country_name,
				color: colors[7],
			};
		});
		chartData.push(...totData);

		am4core.ready(function () {
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			var chart = am4core.create("sdg_framework", am4charts.XYChart);
			chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
			chart.logo.disabled = "true";

			chart.maskBullets = false;
			chart.paddingTop = 50;

			var xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());

			xAxis.dataFields.category = "x";
			yAxis.dataFields.category = "y";

			xAxis.renderer.grid.template.disabled = true;
			xAxis.renderer.minGridDistance = 35;

			yAxis.renderer.grid.template.disabled = true;
			yAxis.renderer.inversed = true;
			yAxis.renderer.minGridDistance = 35;
			yAxis.renderer.labels.template.fontSize = 12;
			yAxis.renderer.labels.template.adapter.add(
				"text",
				function (text, target) {
					const data = target.dataItem._dataContext;
					return `\n\n${data?.country_code}`;
				}
			);
			xAxis.renderer.labels.template.fontSize = 0;
			var image1 = new am4core.Image();
			image1.horizontalCenter = "bottom";
			image1.width = 40;
			image1.height = 40;
			image1.verticalCenter = "bottom";
			image1.dx = -40;
			image1.dy = 10;
			image1.adapter.add("href", (href, target) => {
				let d = target.dataItem._dataContext;
				if (d?.country_code) {
					const conutryCode = d?.country_code.toLowerCase();
					return `${baseURL}include/assets/img/pi_2020/flags/${conutryCode}.svg`;
				}
				return href;
			});
			yAxis.dataItems.template.bullet = image1;
			yAxis.dataItems.template.bullet.tooltipText = "{y}";

			var series = chart.series.push(new am4charts.ColumnSeries());
			series.dataFields.categoryX = "x";
			series.dataFields.categoryY = "y";
			// series.dataFields.value = "value";
			series.sequencedInterpolation = false;
			series.defaultState.transitionDuration = 3000;

			series.columns.template.events.on(
				"hit",
				function (ev) {
					const data = ev.target.dataItem._dataContext;
					const projects = grant.tapsData
						.filter(
							(d) =>
								d.tap_sdgs.some(
									(s) => s.sdg_id == data.sdg_id || data.sdg_id == "NA"
								) &&
								d.tap_impl_countries.some(
									(c) => c.country_id == data.country_id
								)
						)
						.map((d) => {
							return `<tr>
						<td colspan="2" class="text-left">${d.proj_title}</td>
					</tr>`;
						})
						.join("\n");
					const html = `<div class="row align-items-top">
				<div class="col-lg-12">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead> 
							<tr>
								<th class="text-left">Country</th>
								<td class="text-left">${data.country_name}</td>
							</tr>
							<tr>
								<th class="text-left">SDG</th>
								<td class="text-left">${data.sdg_name}</td>
							</tr>
							<tr>
								<th colspan="2">Projects</th>
							</tr>
							</thead>
							<tbody>
							${projects}
							</tbody>
							`;

					$("#heat-map-content").html(html);
				},
				this
			);

			// Set up column appearance
			var column = series.columns.template;
			column.strokeWidth = 2;
			column.strokeOpacity = 1;
			column.stroke = am4core.color("#ffffff");
			column.tooltipText =
				"{y}: {value} {value.workingValue.formatNumber('#.')}";
			column.width = am4core.percent(100);
			column.height = am4core.percent(100);
			column.column.cornerRadius(0, 0, 0, 0);
			column.propertyFields.fill = "color";

			// Set up bullet appearance
			var bullet1 = series.bullets.push(new am4charts.CircleBullet());
			bullet1.circle.propertyFields.radius = "none";
			bullet1.circle.fill = am4core.color("{color}");
			bullet1.circle.strokeWidth = 0;
			bullet1.circle.fillOpacity = 0;
			bullet1.interactionsEnabled = false;

			var bullet2 = series.bullets.push(new am4charts.LabelBullet());
			bullet2.label.text = "{value}";
			bullet2.label.fill = am4core.color("#000");
			bullet2.zIndex = 1;
			bullet2.fontSize = 12;
			bullet2.interactionsEnabled = false;

			chart.data = chartData;

			// var baseWidth = Math.min(
			// 	chart.plotContainer.maxWidth,
			// 	chart.plotContainer.maxHeight
			// );
			// var maxRadius = baseWidth / Math.sqrt(chart.data.length) / 2 - 2; // 2 is jast a margin
			// series.heatRules.push({
			// 	min: 10,
			// 	max: maxRadius,
			// 	property: "radius",
			// 	target: bullet1.circle,
			// });

			// chart.plotContainer.events.on("maxsizechanged", function () {
			// 	var side = Math.min(
			// 		chart.plotContainer.maxWidth,
			// 		chart.plotContainer.maxHeight
			// 	);
			// 	bullet1.circle.clones.each(function (clone) {
			// 		clone.scale = side / baseWidth;
			// 	});
			// });

			// Do not crop bullets
			chart.maskBullets = false;
			chart.paddingTop = 85;

			// Add bullets
			var bullet = series.bullets.push(new am4charts.Bullet());
			var image = bullet.createChild(am4core.Image);
			image.horizontalCenter = "middle";
			image.verticalCenter = "bottom";
			image.width = 100;
			image.height = 80;

			image.dy = -35;
			image.y = am4core.percent(100);
			image.propertyFields.href = "bullet";
			//image.tooltipText = series.columns.template.tooltipText;
			image.propertyFields.fill = "color";
			image.filters.push(new am4core.DropShadowFilter());

			chart.exporting.filePrefix =
				"Projects corresponding to countries and SDGs";
			exportAmchart("heat_sdg_framework_download", chart);
		}); // end am4core.ready()
	}
}
