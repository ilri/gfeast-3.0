var script = document.getElementById("poJS"),
	baseURL = script.getAttribute("data-baseurl");

class PublicationInOar {
	constructor() { }
	init() {
		this.getPublicationOarData();
	}

	getPublicationOarData() {
		// const request = { purpose: "get_publication_oar" };
		const request = indexFilter.getFilteredData();
		request.purpose = "get_publication_oar";
		const promises = [
			post("pi_2020", request),
			get(
				baseURL + "/include/assets/js/pi_2020/tabs/publication_oar_tab.html",
				true
			),
		];
		Promise.all(promises).then((response) => {
			if (response?.length) {
				this.publicationOarData = response[0].tpos;
				const resHtml = response[1].replaceAll(
					'src="img/',
					`src="${baseURL}include/assets/img/pi_2020/`
				);
				$(".mpr-tab-contend").html(resHtml);
				// this.arrangeData();
				// this.generateCharts();
				// this.generateCountryWiseChart()
				// this.staticCharts();
				// this.getHtmlActions();
				this.htmlToggle();
				this.getCharts();
				this.getBarChart();
				this.getYearWiseChart();
				this.getYearWiseAuthorsChart();
				// this.getYearwiseCountryChart();
			}
		});
		// .catch((err) => console.log(err));
	}

	getCharts() {
		$("#totalPublishings").html("");
		const chartData = {
			totalPublished: this.publicationOarData
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			journalArticles: this.publicationOarData
				.filter((d) => d.category == 1)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			isiThomsonReutersWeb: this.publicationOarData
				.filter((d) => d.category == 2)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			peerReviewed: this.publicationOarData
				.filter((d) => d.category == 3)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			books: this.publicationOarData
				.filter((d) => d.category == 4)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			bookChapters: this.publicationOarData
				.filter((d) => d.category == 5)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			monographs: this.publicationOarData
				.filter((d) => d.category == 6)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			conferenceProceedings: this.publicationOarData
				.filter((d) => d.category == 7)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			policyBriefs: this.publicationOarData
				.filter((d) => d.category == 8)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			intlNewsletters: this.publicationOarData
				.filter((d) => d.category == 9)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			socioEconomic: this.publicationOarData
				.filter((d) => d.category == 10)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			reports: this.publicationOarData
				.filter((d) => d.category == 11)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			papersDocumentsPublished: this.publicationOarData
				.filter((d) => d.category == 12)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			isiThomsonReuters: this.publicationOarData
				.filter((d) => d.category == 13)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			booksAndJournal: this.publicationOarData
				.filter((d) => d.category == 14)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			conferencePapers: this.publicationOarData
				.filter((d) => d.category == 15)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			journalArticlesSAT: this.publicationOarData
				.filter((d) => d.category == 16)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			technicalManual: this.publicationOarData
				.filter((d) => d.category == 17)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			internationalNewsletters: this.publicationOarData
				.filter((d) => d.category == 18)
				.map((d) => parseInt(d.papers_published))
				.reduce((a, b) => a + b, 0),
			newslettersPolicyBriefsPosters: this.publicationOarData
				.filter((d) => d.category == 19)
				.map((d) => parseInt(d.papers_published))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
		};

		if(chartData.totalPublished == 0){
			window.alert('Data not available for the selected year, Please reselect your options.');
			location.reload();
			return
		}
		$("#totalPublishings").html(numberWithCommas(chartData.totalPublished == 0 ? "NA" : numberWithCommas(chartData.totalPublished)));

		Highcharts.chart("mpr-pieChart", {
			chart: {
				type: "column",
			},
			title: {
				text: null,
			},
			subtitle: {
				text: null,
			},
			// colors: ['#d79494', '#7cb5ec', '#FFCE56'],
			credits: {
				enabled: false,
			},
			legend: {
				y: 10,
			},
			xAxis: {
				categories: [
					"Journal Articles (ISI & Non-ISI)",
					"Listed by ISI/Thomson Reuters (Web of Science Listed)",
					"Articles in Peer reviewed journals",
					"Books",
					"Book chapters",
					"Monographs",
					"Conference Proceedings",
					"Policy Briefs",
					"Published in International Newsletters",
					"Socio Economic Discussion Papers",
					"Reports",
					"Papers/Documents published",
					"Journals listed by ISI/THOMSON REUTERS",
					"Books and Journal volumes",
					"Conference papers/ proceedings",
					"Journal articles in SAT eJournal",
					"Technical manual",
					"Newsletters, Policy Briefs, Posters, etc.",
				],
				title: {
					text: null,
				},
			},
			yAxis: {
				//opposite: true,
				// min: 0,
				// max: 8,
				// tickInterval: 2,
				title: {
					text: "Publications Published",
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
					name: "Journal Articles (ISI & Non-ISI)",
					// color: '#182C3C',
					data: [chartData.journalArticles],
				},
				{
					maxPointWidth: 20,
					name: "Listed by ISI/Thomson Reuters (Web of Science Listed)",
					// color: '#2C5370',
					data: [chartData.isiThomsonReutersWeb],
				},
				{
					maxPointWidth: 20,
					name: "Articles in Peer reviewed journals",
					// color: '#1590EF',
					data: [chartData.peerReviewed],
				},
				{
					maxPointWidth: 20,
					name: "Books",
					// color: '#1590EF',
					data: [chartData.books],
				},
				{
					maxPointWidth: 20,
					name: "Book chapters",
					// color: '#1590EF',
					data: [chartData.bookChapters],
				},
				{
					maxPointWidth: 20,
					name: "Monographs",
					// color: '#1590EF',
					data: [chartData.monographs],
				},
				{
					maxPointWidth: 20,
					name: "Conference Proceedings",
					// color: '#1590EF',
					data: [chartData.conferenceProceedings],
				},
				{
					maxPointWidth: 20,
					name: "Policy Briefs",
					// color: '#1590EF',
					data: [chartData.policyBriefs],
				},
				{
					maxPointWidth: 20,
					name: "Published in International Newsletters",
					// color: '#1590EF',
					data: [chartData.intlNewsletters],
				},
				{
					maxPointWidth: 20,
					name: "Socio Economic Discussion Papers",
					// color: '#1590EF',
					data: [chartData.socioEconomic],
				},
				{
					maxPointWidth: 20,
					name: "Reports",
					// color: '#1590EF',
					data: [chartData.reports],
				},
				{
					maxPointWidth: 20,
					name: "Papers/Documents published",
					// color: '#1590EF',
					data: [chartData.papersDocumentsPublished],
				},
				{
					maxPointWidth: 20,
					name: "Journals listed by ISI/THOMSON REUTERS",
					// color: '#1590EF',
					data: [chartData.isiThomsonReuters],
				},
				{
					maxPointWidth: 20,
					name: "Books and Journal volumes",
					// color: '#1590EF',
					data: [chartData.booksAndJournal],
				},
				{
					maxPointWidth: 20,
					name: "Conference papers/ proceedings",
					// color: '#1590EF',
					data: [chartData.conferencePapers],
				},
				{
					maxPointWidth: 20,
					name: "Journal articles in SAT eJournal",
					// color: '#1590EF',
					data: [chartData.journalArticlesSAT],
				},
				{
					maxPointWidth: 20,
					name: "Technical manual",
					// color: '#1590EF',
					data: [chartData.technicalManual],
				},
				{
					maxPointWidth: 20,
					name: "Newsletters, Policy Briefs, Posters, etc.",
					// color: '#1590EF',
					data: [chartData.newslettersPolicyBriefsPosters],
				},
			],
		});

		$("#table-1-tbody").html(`
			<tr><td>Journal Articles (ISI & Non-ISI)</td>
			<td>${chartData.journalArticles == 0 ? "NA" : numberWithCommas(chartData.journalArticles)}</td></tr>
			<tr><td>Listed by ISI/Thomson Reuters (Web of Science Listed)</td>
			<td>${chartData.isiThomsonReutersWeb == 0 ? "NA" : numberWithCommas(chartData.isiThomsonReutersWeb)}</td></tr>
			<tr><td>Articles in Peer reviewed journals</td>
			<td>${chartData.peerReviewed == 0 ? "NA" : numberWithCommas(chartData.peerReviewed)}</td>
			</tr>
			<tr><td>Books</td><td>${chartData.books == 0 ? "NA" : numberWithCommas(chartData.books)}</td>
			</tr>
			<tr><td>Book chapters</td><td>${chartData.bookChapters == 0 ? "NA" : numberWithCommas(chartData.bookChapters)}</td></tr>
			<tr><td>Monographs</td><td>${chartData.monographs == 0 ? "NA" : numberWithCommas(chartData.monographs)}</td></tr>
			<tr><td>Conference Proceedings</td><td>${chartData.conferenceProceedings == 0 ? "NA" : numberWithCommas(chartData.conferenceProceedings)}</td></tr>
			<tr><td>Policy Briefs</td><td>${chartData.policyBriefs == 0 ? "NA" : numberWithCommas(chartData.policyBriefs)}</td></tr>
			<tr><td>Published in International Newsletters</td><td>${chartData.intlNewsletters == 0 ? "NA" : numberWithCommas(chartData.intlNewsletters)}</td></tr>
			<tr><td>Socio Economic Discussion Papers</td><td>${chartData.socioEconomic == 0 ? "NA" : numberWithCommas(chartData.socioEconomic)}</td></tr>
			<tr><td>Reports</td><td>${chartData.reports == 0 ? "NA" : numberWithCommas(chartData.reports)}</td></tr>
			<tr><td>Papers/Documents published</td><td>${chartData.papersDocumentsPublished == 0 ? "NA" : numberWithCommas(chartData.papersDocumentsPublished)}</td></tr>
			<tr><td>Journals listed by ISI/THOMSON REUTERS</td><td>${chartData.isiThomsonReuters == 0 ? "NA" : numberWithCommas(chartData.isiThomsonReuters)}</td></tr>
			<tr><td>Books and Journal volumes</td><td>${chartData.isiThomsonReuters == 0 ? "NA" : numberWithCommas(chartData.booksAndJournal)}</td></tr>
			<tr><td>Conference papers/ proceedings</td><td>${chartData.conferencePapers == 0 ? "NA" : numberWithCommas(chartData.conferencePapers)}</td></tr>
			<tr><td>Journal articles in SAT eJournal</td><td>${chartData.journalArticlesSAT == 0 ? "NA" : numberWithCommas(chartData.journalArticlesSAT)}</td></tr>
			<tr><td>Technical manual</td><td>${chartData.technicalManual == 0 ? "NA" : numberWithCommas(chartData.technicalManual)}</td></tr>
			<tr><td>Newsletters, Policy Briefs, Posters, etc.</td><td>${chartData.newslettersPolicyBriefsPosters == 0 ? "NA" : numberWithCommas(chartData.newslettersPolicyBriefsPosters)}</td></tr>
		`);
		$("#table-1-tfoot").html(`<tr><td>Total</td><td>${(chartData.newslettersPolicyBriefsPosters + chartData.technicalManual + chartData.journalArticlesSAT + chartData.conferencePapers + chartData.booksAndJournal + chartData.isiThomsonReuters + chartData.socioEconomic + chartData.intlNewsletters + chartData.journalArticles + chartData.isiThomsonReutersWeb + chartData.peerReviewed + chartData.bookChapters + chartData.conferenceProceedings + chartData.policyBriefs) == 0 ? "NA" : numberWithCommas(
			chartData.newslettersPolicyBriefsPosters + chartData.technicalManual + chartData.journalArticlesSAT + chartData.conferencePapers + chartData.booksAndJournal + chartData.isiThomsonReuters + chartData.socioEconomic + chartData.intlNewsletters + chartData.journalArticles + chartData.isiThomsonReutersWeb + chartData.peerReviewed + chartData.bookChapters + chartData.conferenceProceedings + chartData.policyBriefs
		)}</td></tr>`)
	}

	getBarChart() {
		const chartData = {
			// icrisat_authors: this.publicationOarData.map(d => parseInt(d.ICRISAT_authors)).reduce((a, b) => a + b, 0),
			icrisat_authors: this.publicationOarData
				.map((d) => parseInt(d.ICRISAT_authors))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			nars: this.publicationOarData
				.map((d) => parseInt(d.nars))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			womens: this.publicationOarData
				.map((d) => parseInt(d.women))
				.filter((d) => !Number.isNaN(d))
				.reduce((a, b) => a + b, 0),
			// total_authors: this.publicationOarData.map(d => parseInt(d.total_authors)).filter(d=> !Number.isNaN(d)).reduce((a,b)=> a+b),
		};

		const total_authors =
			chartData.icrisat_authors + chartData.nars + chartData.womens;

		$("#total_authors").html(numberWithCommas(total_authors == 0 ? "NA" : numberWithCommas(total_authors)));
		$("#icrisat_authors").html(numberWithCommas(chartData.icrisat_authors == 0 ? "NA" : numberWithCommas(((chartData.icrisat_authors/total_authors)*100).toFixed(1))));
		$("#nars").html(numberWithCommas(chartData.nars == 0 ? "NA" : numberWithCommas(((chartData.nars/total_authors)*100).toFixed(1))));
		$("#womens").html(numberWithCommas(chartData.womens == 0 ? "NA" : numberWithCommas(((chartData.womens/total_authors)*100).toFixed(1))));

		$("#table-2-tbody").html(`
			<tr><td>ICRISAT authors</td>
			<td>${chartData.icrisat_authors == 0 ? "NA" : numberWithCommas(chartData.icrisat_authors)}</td></tr>
			<tr><td>NARS</td><td>${chartData.nars == 0 ? "NA" : numberWithCommas(chartData.nars)}</td></tr>
			<tr><td>Women</td><td>${chartData.womens == 0 ? "NA" : numberWithCommas(chartData.womens)}</td></tr>
		`);
		$("#table-2-tfoot").html(`<tr><td>Total</td><td>${(chartData.icrisat_authors + chartData.nars + chartData.womens) == 0 ? "NA" : numberWithCommas(
			chartData.icrisat_authors + chartData.nars + chartData.womens
		)}</td></tr>`)

		Highcharts.chart("mpr-pieChart1", {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: "pie",
			},
			credits: {
				enabled: false,
			},
			title: {
				text: null,
			},
			colors: ["#d79494", "#7cb5ec", "#FFCE56"],
			tooltip: {
				pointFormat: "<b>{point.percentage:.1f}%</b>",
			},
			accessibility: {
				point: {
					valueSuffix: "%",
				},
			},
			legend: {
				enabled: true,
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: "pointer",
					dataLabels: {
						enabled: true,
						format: "<b>{point.name}</b>: {point.percentage:.1f} %",
						// format: "<b>{point.name}</b>",
						style: { textOutline: false },
					},
					showInLegend: true,
				},
			},
			series: [
				{
					name: "Brands",
					colorByPoint: true,
					data: [
						{
							name: "ICRISAT authors",
							y: chartData.icrisat_authors,
						},
						{
							name: "NARS",
							y: chartData.nars,
						},
						{
							name: "Women",
							y: chartData.womens,
						},
					],
				},
			],
		});

		// Crop-wise yield on-station & on-farm
		// Highcharts.chart('mpr-pieChart12', {
		// 	chart: {
		// 		type: 'pie',
		// 	},
		// 	title: {
		// 		text: null
		// 	},
		// 	subtitle: {
		// 		text: null
		// 	},
		// 	colors: ['#d79494', '#7cb5ec', '#FFCE56'],
		// 	credits: {
		// 		enabled: false
		// 	},
		// 	legend: {
		// 		y: 10,
		// 	},
		// 	xAxis: {
		// 		categories: [''],
		// 		title: {
		// 			text: null
		// 		}
		// 	},
		// 	yAxis: {
		// 		//opposite: true,
		// 		// min: 0,
		// 		// max: 8,
		// 		// tickInterval: 10,
		// 		title: {
		// 			text: 'Number of authors',
		// 		},
		// 		labels: {
		// 			overflow: 'justify'
		// 		}
		// 	},
		// 	tooltip: {
		// 		enabled: false
		// 	},
		// 	plotOptions: {
		// 		series: {
		// 			dataLabels: {
		// 				enabled: true
		// 			},
		// 			states: {
		// 				inactive: {
		// 					opacity: 1
		// 				},
		// 				hover: {
		// 					enabled: false,
		// 				}
		// 			}
		// 		}
		// 	},
		// 	// series: [{
		// 	// 	maxPointWidth: 40,
		// 	// 	name: "ICRISAT authors",
		// 	// 	color: '#182C3C',
		// 	// 	data: [chartData.icrisat_authors]
		// 	// }, {
		// 	// 	maxPointWidth: 40,
		// 	// 	name: "NARS",
		// 	// 	color: '#2C5370',
		// 	// 	data: [chartData.nars]
		// 	// }, {
		// 	// 	maxPointWidth: 40,
		// 	// 	name: "Women",
		// 	// 	color: '#1590EF',
		// 	// 	data: [chartData.womens]
		// 	// }]
		// 	series: [{
		// 		name: 'Brands',
		// 		colorByPoint: true,
		// 		data: [
		// 			{
		// 				name: "ICRISAT authors",
		// 				y: chartData.icrisat_authors
		// 			},
		// 			{
		// 				name: "NARS",
		// 				y: chartData.nars
		// 			},
		// 			{
		// 				name: "Women",
		// 				y: chartData.womens
		// 			},
		// 		]
		// 	}]
		// });
	}

	getYearWiseChart() {
		const chartData = indexFilter.dataViewYears
			.map((yr) => {
				return {
					year: yr.year,
					publications: this.publicationOarData
						.filter((e) => e.year_id == yr.year_id && e.papers_published)
						.map((e) => parseInt(e.papers_published))
						.reduce((a, b) => a + b, 0),
				};
			})
			.filter((d) => d.publications > 0);

		$("#table-4-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td><td>${e.publications == 0 ? "NA" : numberWithCommas(e.publications)}</td></tr>`
			)
		);
		let tfData = chartData.map((e) => e.publications).reduce((a, b) => a + b)
		$("#table-4-tfoot").html(`<tr><td>Total</td><td>${tfData == 0 ? "NA" : numberWithCommas(tfData)}</td></tr>`
		);

		Highcharts.chart("pub-yearwise-graph", {
			chart: { type: "column" },
			title: { text: null },
			subtitle: { text: null },
			colors: ["#d79494"],
			credits: { enabled: false },
			legend: { y: 10 },
			xAxis: {
				categories: chartData.map((e) => e.year),
				title: { text: null },
			},
			yAxis: {
				// title: { text: "Papers Published" },
				title: { text: "Publications Published" },
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
					data: chartData.map((e) => e.publications),
				},
			],
		});
	}

	getYearWiseAuthorsChart() {
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			result.icrisat_authors = this.publicationOarData
				.filter((e) => e.year_id == yr.year_id)
				.map((e) => parseInt(e.ICRISAT_authors || 0))
				.reduce((a, b) => a + b, 0);
			result.nars = this.publicationOarData
				.filter((e) => e.year_id == yr.year_id)
				.map((e) => parseInt(e.nars || 0))
				.reduce((a, b) => a + b, 0);
			result.women = this.publicationOarData
				.filter((e) => e.year_id == yr.year_id)
				.map((e) => parseInt(e.women || 0))
				.reduce((a, b) => a + b, 0);
			return result;
		});

		// Highcharts.chart("pub-auth-yearwise-graph", {
		// 	chart: { type: "column" },
		// 	title: { text: "" },
		// 	xAxis: { categories: chartData.map((e) => e.year) },
		// 	yAxis: {
		// 		min: 0,
		// 		title: { text: "Number of authors" },
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
		// 			dataLabels: {
		// 				enabled: true,
		// 				format: "{point.y}",
		// 				style: { textOutline: false },
		// 			},
		// 		},
		// 	},
		// 	series: [
		// 		{
		// 			name: "ICRISAT authors",
		// 			color: "#7cb5ec",
		// 			data: chartData.map((e) => e.icrisat_authors),
		// 		},
		// 		{
		// 			name: "NARS",
		// 			color: "#d79494",
		// 			data: chartData.map((e) => e.nars),
		// 		},
		// 		{
		// 			name: "Women",
		// 			color: "#FFCE56",
		// 			data: chartData.map((e) => e.women),
		// 		},
		// 	],
		// });

		Highcharts.chart("pub-auth-yearwise-graph", {
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
					text: "Number of authors",
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
					name: "ICRISAT authors",
					color: "#7cb5ec",
					data: chartData.map((e) => e.icrisat_authors),
				},
				{
					name: "NARS",
					color: "#d79494",
					data: chartData.map((e) => e.nars),
				},
				{
					name: "Women",
					color: "#FFCE56",
					data: chartData.map((e) => e.women),
				},
			],
		});

		$("#table-5-tbody").html(
			chartData.map(
				(e) =>
					`<tr><td>${e.year}</td>
					<td>${e.icrisat_authors == 0 ? "NA" : numberWithCommas(e.icrisat_authors)}</td>
					<td>${e.nars == 0 ? "NA" : numberWithCommas(e.nars)}</td>
					<td>${e.women == 0 ? "NA" : numberWithCommas(e.women)}</td>
					<td style="font-weight: 600;">${(e.icrisat_authors + e.nars + e.women) == 0 ? "NA" : numberWithCommas(e.icrisat_authors + e.nars + e.women)}</td></tr>`
			)
		);

		let tfIcrisat = chartData.map((e) => e.icrisat_authors).reduce((a, b) => a + b);
		let tfNars = chartData.map((e) => e.nars).reduce((a, b) => a + b);
		let tfWomen = chartData.map((e) => e.women).reduce((a, b) => a + b);
		let tfTotal = ((chartData.map((e) => e.icrisat_authors).reduce((a, b) => a + b) + (chartData.map((e) => e.nars).reduce((a, b) => a + b)) + (chartData.map((e) => e.women).reduce((a, b) => a + b))));

		let tableFooter = `
    <tr><td>Total</td>
	<td>${tfIcrisat == 0 ? "NA" : numberWithCommas(tfIcrisat)}</td>
	<td>${tfNars == 0 ? "NA" : numberWithCommas(tfNars)}</td>
	<td>${tfWomen == 0 ? "NA" : numberWithCommas(tfWomen)}</td>
	<td>${tfTotal == 0 ? "NA" : numberWithCommas(tfTotal)}</td></tr>
`;
		$("#table-5-tfoot").html(tableFooter);
	}

	getYearwiseCountryChart() {
		let chartData = indexFilter.dataViewYears.map((yr) => {
			let result = { year: yr.year };
			indexFilter.pi2020FilterData.countries.map((c) => { });
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
					.attr("download", `oar-publication.jpeg`);
			});
		});
		$("#dwn-csv-1").on("click", function () {
			$("#table-1-main").table2csv({
				file_name: "oar-publication.csv",
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
					`${baseURL}include/assets/img/pi_2020/` + "Pie-selected.svg"
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
				.prop("src", `${baseURL}include/assets/img/pi_2020/` + "Pie.svg");
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
					.attr("download", `oar-distribution.jpeg`);
			});
		});
		$("#dwn-csv-2").on("click", function () {
			$("#table-2-main").table2csv({
				file_name: "oar-distribution.csv",
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
					.attr("download", `oar-year.jpeg`);
			});
		});
		$("#dwn-csv-4").on("click", function () {
			$("#table-4-main").table2csv({
				file_name: "oar-year.csv",
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
					.attr("download", `oar-year.jpeg`);
			});
		});
		$("#dwn-csv-5").on("click", function () {
			$("#table-5-main").table2csv({
				file_name: "oar-year.csv",
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
					.attr("download", `oar-year.jpeg`);
			});
		});
		$("#dwn-csv-6").on("click", function () {
			$("#table-6-main").table2csv({
				file_name: "oar-year.csv",
				header_body_space: 0,
			});
		});
	}
}
