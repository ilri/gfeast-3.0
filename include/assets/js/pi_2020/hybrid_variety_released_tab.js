Highcharts.setOptions({
  lang: {
    thousandsSep: ",",
  },
});

class HybridVarietyReleased {
  constructor() {
    this.breedingGermplasmHybrid = null;
    this.hybridVarietyReleasedData = null;
  }

  init() {
    this.getBreedingGermplasmHybrid();
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

  getBreedingGermplasmHybrid() {
    const request = indexFilter.getFilteredData();
    request.purpose = "get_hybrids_varieties_released";
    const promises = [
      post("pi_2020", Object.assign(request, indexFilter.getFilteredData())),
      get("./tabs/hybrid_variety_released_tab.html", true),
    ];
    Promise.all(promises).then((response) => {
      if (response?.length) {
        this.hybridVarietyReleasedData = response[0];
        $(".mpr-tab-contend").html(response[1]);
        $(`#mpr-programWise,#mpr-pieChartType2`).css("height", "400px");
        this.arrangeData();
        this.generateCharts();
      }
    });
    // .catch((err) => console.log(err));
  }

  arrangeData() {
    this.thvrs = clone(this.hybridVarietyReleasedData.thvrs);
    this.thvrs.forEach((d) => {
      d.crps = clone(this.hybridVarietyReleasedData.thvr_crps).filter(
        (f) => f.data_id == d.data_id
      );
      d.sdgs = clone(this.hybridVarietyReleasedData.thvr_sdgs).filter(
        (f) => f.data_id == d.data_id
      );
      d.spillover = this.hybridVarietyReleasedData.thvr_spillover.filter(
        (f) => f.data_id == d.data_id
      );
      d.nutritional = this.hybridVarietyReleasedData.thvr_nutri.filter(
        (e) => d.data_id == e.data_id
      );
      d.primaries = this.hybridVarietyReleasedData.thvr_primary.filter(
        (e) => d.data_id == e.data_id
      );
      d.secondaries = this.hybridVarietyReleasedData.thvr_secondary.filter(
        (e) => d.data_id == e.data_id
      );
    });
  }

  generateCharts() {
    this.generateHybridVarietyReleaseChart();
    this.generateCropWiseHybridVarietyRelease();
    this.generateSdgsContributionData();
    this.generateResearchWiseChart();
    // this.generateCrpVarietyWiseChart();
    this.generateHybridVarietyByReleaseChart();
    this.generateCrpWiseHybridVarietyRelease();
    this.generateResearchProgramWiseHybridVarietyChart();
    this.generateHybridSdgVarietyByReleaseChart();
    this.generateCountriesMap();

    this.getHtmlActionForCropYearComparison();
    this.getCropYearComparisonInfo();

    this.getHtmlActionForCRPYearComparison();
    this.getCRPYearComparisonInfo();
    this.getHtmlActionForContrysYearComparison();
    this.getContriesYearComparisonInfo();
    this.generateCropWiseYieldOnStation();
    this.generatePSNationPerformanceChart();
    this.getHtmlActionForNationalPerformanceTraits();

    this.generateCropxCRPCharts();
    this.generateCropXSdgXTraitChart();
    this.generateCropXCountryXTraitChart();

    this.graphScientificPublications();
    this.graphBMSData();
    this.generateAgroEcChart();
    this.generateSpillCountriesMap();

    // this.generateNutritionNationPerformanceChart();

    this.htmlToggle();
  }

  generateHybridVarietyReleaseChart() {
    debugger
    $("#totalRecords").html("");
    $("#breadingMaterialCount").html("");
    $("#hybridLineCount").html("");
    $("#germplasmCount").html("");
    $("#disaggregationCount").html("");

    const tbghs = this.thvrs;
    const chartData = {
      breedingMaterial: {},
      hybridLine: {},
      germplasm: {},
      disaggregation: {},
      count: tbghs.length,
    };
    chartData.breedingMaterial.data = tbghs.filter(
      (d) => d.hybrid_varieties_id == this.BREEDINGID
    );
    chartData.breedingMaterial.count = chartData.breedingMaterial.data.length;
    chartData.hybridLine.data = tbghs.filter(
      (d) => d.hybrid_varieties_id == this.HYBRIDID
    );
    chartData.hybridLine.count = chartData.hybridLine.data.length;
    chartData.germplasm.data = tbghs.filter(
      (d) => d.hybrid_varieties_id == this.GERMPLASMID
    );
    chartData.germplasm.count = chartData.germplasm.data.length;
    chartData.disaggregation.data = tbghs.filter(
			(d) => d.hybrid_variety_area == this.DISAGGREGATION
		);
		chartData.disaggregation.count = chartData.disaggregation.data.length;

    chartData.countryData = indexFilter.countries
      .map((data) => {
        const result = {
          countryId: data.country_id,
          countryName: data.country_name,
        };
        result["breedingMaterialData"] = tbghs.filter(
          (d) =>
            d.hybrid_varieties_id == this.BREEDINGID &&
            d.country_id == data.country_id
        );
        result["breedingMaterialCount"] = result["breedingMaterialData"].length;
        result["hybridLineData"] = tbghs.filter(
          (d) =>
            d.hybrid_varieties_id == this.HYBRIDID &&
            d.country_id == data.country_id
        );
        result["hybridLineCount"] = result["hybridLineData"].length;
        result["germplasmData"] = tbghs.filter(
          (d) =>
            d.hybrid_varieties_id == this.GERMPLASMID &&
            d.country_id == data.country_id
        );
        result["germplasmCount"] = result["germplasmData"].length;
        result["disaggregationData"] = tbghs.filter(
					(d) =>
						d.hybrid_varieties_id == this.DISAGGREGATION &&
						d.country_id == data.country_id
				);
				result["disaggregationCount"] = result["disaggregationData"].length;
        return result;
      })
      .filter(
        (d) => d.germplasmCount || d.hybridLineCount || d.breedingMaterialCount || d.disaggregationCount
      );

    chartData.totalCount =
      chartData.breedingMaterial.count +
      chartData.hybridLine.count +
      chartData.germplasm.count+chartData.disaggregation.count;
    Highcharts.chart("graph-one", {
      chart: {
        type: "pie",
      },
      title: {
        text: null,
      },
      subtitle: {
        text: null,
      },
      colors: ["#d79494", "#7cb5ec", "#FFCE56","#6de38e"],
      credits: {
        enabled: false,
      },
      plotOptions: {
        pie: {
          allowPointSelect: false,
          dataLabels: {
            enabled: true,
            format: "{point.name}: {point.y} ({point.percent:.2f} %)",
          },
          showInLegend: true,
        },
      },
      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat:
          '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percent:.2f}%)<br/>',
      },
      series: [
        {
          name: "Hybrids / Varieties",
          colorByPoint: true,
          data: [
            {
              name: "Breeding Material",
              y: chartData.breedingMaterial.count,
              percent:
                (chartData.breedingMaterial.count * 100) / chartData.totalCount,
            },
            {
              name: "Hybrid Lines",
              y: chartData.hybridLine.count,
              percent:
                (chartData.hybridLine.count * 100) / chartData.totalCount,
            },
            {
              name: "Germplasm",
              y: chartData.germplasm.count,
              percent: (chartData.germplasm.count * 100) / chartData.totalCount,
            },
            {
              name: "Disaggregation not available",
              y: chartData.germplasm.count,
              percent: (chartData.disaggregation.count * 100) / chartData.totalCount,
            },
          ],
        },
      ],
    });

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

    this.generateHybridVarietyReleaseTable(chartData);
    this.generateHybridVarietyReleaseCountryWiseTable(chartData);
  }

  generateHybridVarietyReleaseTable(chartData) {
    $("#totalRecords").html(chartData.totalCount);
    // besides the map
    $("#breadingMaterialCount").html(chartData.breedingMaterial.count);
    $("#hybridLineCount").html(chartData.hybridLine.count);
    $("#germplasmCount").html(chartData.germplasm.count);
    $("#disaggregationCount").html(chartData.disaggregation.count);

    let tableHtml = `
        <tr><td>Breeding Material</td><td>${chartData.breedingMaterial.count}</td></tr>
        <tr><td>Germplasm</td><td>${chartData.germplasm.count}</td></tr>
        <tr><td>Hybrid Lines</td><td>${chartData.hybridLine.count}</td><td>${chartData.disaggregation.count}</td></tr>
    `;
    $("#table-1-tbody").html(tableHtml);
    let tfootHtml = `
        <tr><td>Total</td><td>${
          chartData.breedingMaterial.count +
          chartData.germplasm.count +
          chartData.hybridLine.count+
          chartData.disaggregation.count
        }</td></tr>
    `;
    $("#table-1-tfoot").html(tfootHtml);
  }

  generateHybridVarietyReleaseCountryWiseTable(chartData) {
    if (chartData.countryData) {
      // breedingMaterialCount
      let tableHtml = chartData.countryData
        .map((d) => {
          return `
                <tr>
                    <td>${d.countryName}</td>
                    <td>${d.breedingMaterialCount}</td>
                    <td>${d.hybridLineCount}</td>
                    <td>${d.germplasmCount}</td>
                    <td>${d.disaggregationCount}</td>
                </tr>
                `;
        })
        .join("\n");

      $("#table-2-tbody").html(tableHtml);
    }
  }

  generateCropWiseHybridVarietyRelease() {
    const chartData = indexFilter.crops
      .map((data) => {
        const result = { cropId: data.crop_id, cropName: data.crop_name };
        result["breedingMaterialData"] = this.thvrs.filter(
          (d) =>
            d.hybrid_varieties_id == this.BREEDINGID &&
            d.crop_id == data.crop_id
        );
        result["breedingMaterialCount"] = result["breedingMaterialData"].length;
        result["hybridLineData"] = this.thvrs.filter(
          (d) =>
            d.hybrid_varieties_id == this.HYBRIDID && d.crop_id == data.crop_id
        );
        result["hybridLineCount"] = result["hybridLineData"].length;
        result["germplasmData"] = this.thvrs.filter(
          (d) =>
            d.hybrid_varieties_id == this.GERMPLASMID &&
            d.crop_id == data.crop_id
        );
        result["germplasmCount"] = result["germplasmData"].length;
        return result;
      })
      .filter(
        (d) => d.germplasmCount || d.hybridLineCount || d.breedingMaterialCount
      );

    Highcharts.chart("graph-3", {
      chart: {
        type: "column",
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
        y: 0,
      },
      xAxis: {
        categories: chartData.map((d) => d.cropName),
        title: {
          text: null,
        },
      },
      yAxis: {
        //opposite: true,
        min: 0,
        tickInterval: 2,
        title: {
          text: "Hybrids/Varieties released (number)",
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
          name: "Breeding Material ",
          data: chartData.map((d) => d.breedingMaterialCount),
        },
        {
          maxPointWidth: 20,
          name: "Hybrid Lines",
          data: chartData.map((d) => d.hybridLineCount),
        },
        {
          maxPointWidth: 20,
          name: "Germplasm",
          data: chartData.map((d) => d.germplasmCount),
        },
      ],
    });

    this.generateCropWiseHybridVarietyReleaseTable(chartData);
  }

  generateCropWiseHybridVarietyReleaseTable(chartData) {
    let tableHtml = chartData.map((e) => {
      if (e.breedingMaterialCount || e.hybridLineCount || e.germplasmCount)
        return `<tr><td>${e.cropName}</td><td>${e.breedingMaterialCount}</td><td>${e.hybridLineCount}</td><td>${e.germplasmCount}</td></tr>`;
    });
    $("#table-3-tbody").html(tableHtml);
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
  generatePSNationPerformanceChart() {
    const selectedCrop = $("#national-performance-crop-name").data("value");
    const search = (env) => !selectedCrop || selectedCrop == env.crop_id;
    const pChartData = indexFilter.pi2020FilterData.primtraits
      .map((data) => {
        const result = { id: data.primtraits_id, name: data.primtraits };
        result.data = this.thvrs.filter(
          (d) =>
            d.primaries.some((e) => e.primarytrait_id == data.primtraits_id) &&
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
        result.data = this.thvrs.filter(
          (d) =>
            d.secondaries.some(
              (e) => e.secondarytrait_id == data.secondarytraits_id
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
        result.data = this.thvrs.filter(
          (d) =>
            d.nutritional.some(
              (e) => e.nutritionaltrait_id == data.nutrionaltraiss_id
            ) && search(d)
        );
        result.count = result.data.length;
        return result;
      })
      .filter((d) => d.count > 0);

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
      colors: ["#d79494", "#7cb5ec", "#FFCE56"],
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
          text: "Number of lines entered",
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
          name: "Primary",
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
          text: "Number of lines entered",
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
          name: "Secondary",
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
          text: "Number of lines entered",
        },
        labels: {
          overflow: "justify",
        },
      },
      tooltip: {
        pointFormat: "<b>{point.y}</b>",
        enabled: true,
      },
      plotOptions: {
        series: {
          dataLabels: {
            enabled: true,
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
          name: "Nutritional",
          data: chartData.map((d) => d.count),
        },
      ],
    });
  }
  generateNutritionNationPerformanceChart() {
    const chartData = indexFilter.pi2020FilterData.nutrionaltraiss
      .map((data) => {
        const result = {
          id: data.nutrionaltraiss_id,
          name: data.nutrionaltraiss,
        };
        result.data = this.thvrs.filter((d) =>
          d.nutritional.some(
            (e) => e.nutritionaltrait_id == data.nutrionaltraiss_id
          )
        );
        result.count = result.data.length;
        return result;
      })
      .filter((d) => d.count > 0);

    Highcharts.chart("", {
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
          text: "Number of lines entered",
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
          name: "Nutritional",
          data: chartData.map((d) => d.count),
        },
      ],
    });

    $("#table-19-tbody").html(
      chartData.map((e) => `<tr><td>${e.name}</td><td>${e.count}</td></tr>`)
    );
  }

  generateCropWiseYieldOnStation() {
    const chartData = indexFilter.crops
      .map((data) => {
        const result = { cropId: data.crop_id, cropName: data.crop_name };
        result["yieldOnStation"] = this.thvrs
          .filter((d) => d.crop_id == data.crop_id)
          .map((e) => parseFloat(e.yield_on_station_kg_ha || 0))
          .reduce((a, b) => a + b, 0);
        result["yieldOnFarm"] = this.thvrs
          .filter((d) => d.crop_id == data.crop_id)
          .map((f) => parseFloat(f.yield_on_farm_kg_ha || 0))
          .reduce((m, n) => m + n, 0);
        return result;
      })
      .filter((c) => c.yieldOnStation || c.yieldOnFarm);

    // console.log(chartData);
    Highcharts.chart("yeild_on_crops", {
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
        categories: chartData.map((d) => d.cropName),
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
          name: "Yield On Station ",
          data: chartData.map((d) => d.yieldOnStation),
        },
        {
          maxPointWidth: 20,
          name: "Yield On Farm",
          data: chartData.map((d) => d.yieldOnFarm),
        },
      ],
    });

    this.generateCropWiseyeildonTable(chartData);
  }

  generateCropWiseyeildonTable(chartData) {
    let tableHtml = chartData.map((e) => {
      if (e.yieldOnStation || e.yieldOnFarm)
        return `<tr><td>${e.cropName}</td><td>${e.yieldOnStation}</td><td>${e.yieldOnFarm}</td></tr>`;
    });
    $("#table-17-tbody").html(tableHtml);
  }

  generateSdgsContributionData() {
    const chartData = indexFilter.sdgs
      .map((data) => {
        const bData = this.thvrs.filter(
          (d) =>
            d.sdgs.some((sdg) => sdg.sdg_id == data.sdg_id) &&
            d.hybrid_varieties_id == this.BREEDINGID
        ).length;
        const hData = this.thvrs.filter(
          (d) =>
            d.sdgs.some((sdg) => sdg.sdg_id == data.sdg_id) &&
            d.hybrid_varieties_id == this.HYBRIDID
        ).length;
        const gData = this.thvrs.filter(
          (d) =>
            d.sdgs.some((sdg) => sdg.sdg_id == data.sdg_id) &&
            d.hybrid_varieties_id == this.GERMPLASMID
        ).length;
        return [
          { from: "Breeding Material", to: data.sdg_name, value: bData },
          { from: "Hybrid Lines", to: data.sdg_name, value: hData },
          { from: "Germplasm", to: data.sdg_name, value: gData },
        ];
      })
      .flat()
      .filter((d) => d.value > 0);
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
      // chart.nodes.template.nameLabel.label.wrap = true;

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
    });
  }

  generateResearchWiseChart() {
    const chartData = indexFilter.reasearchPrograms
      .map((data) => {
        const result = { rpId: data.rp_id, rpName: data.rp_name };
        result.count = this.thvrs.filter((d) => d.rp_id == data.rp_id).length;
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
          cursor: "pointer",
          dataLabels: {
            enabled: true,
            format:
              "{point.name}</span>: {point.count}<b>{point.y:.2f}%</b> of total",
            style: { textOutline: false },
          },
          showInLegend: false,
        },
      },
      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat:
          '<span style="color:{point.color}">{point.name}</span>: {point.count}<b>{point.y:.2f}%</b> of total<br/>',
      },
      series: [
        {
          name: "Hybrids / Varieties",
          colorByPoint: true,
          data: chartData.map((d) => {
            return { name: d.rpName, y: d.avg };
          }),
        },
      ],
    });
    // const legendsHtml = chartData.map((d) => {
    //   return `
    //         <li>
    //         <div class="mr-3">${Number(d.avg.toFixed(2))}%</div> ${d.rpName}
    //         </li>
    //         `;
    // });
    // $("#rp-legends").html(legendsHtml);

    this.generateResearchWiseTable(chartData);
  }

  generateResearchWiseTable(chartData) {
    let tableHtml = chartData.map(
      (e) => `<tr><td>${e.rpName}</td><td>${e.count}</td><td>${e.avg}</td></tr>`
    );
    $("#table-6-tbody").html(tableHtml);
  }

  generateCrpVarietyWiseChart() {
    const chartData = indexFilter.crps
      .map((data) => {
        const result = { crpName: data.crp_name, crpId: data.crp_id, count: 0 };
        result.data = this.thvrs.filter((d) =>
          d.crps.some((e) => e.crp_id == data.crp_id)
        );
        result.count = result.data.length;
        return result;
      })
      .filter((d) => d.count > 0);
    Highcharts.chart("mpr-basic-singlecolumnChart", {
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
        enabled: false,
      },
      xAxis: {
        categories: chartData.map((d) => d.crpName),
        title: {
          text: null,
        },
      },
      yAxis: {
        min: 0,
        tickInterval: 5,
        title: {
          text: "Number of varieties released",
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
          name: "",
          data: chartData.map((d) => d.count),
        },
      ],
    });

    this.generateCrpVarietyWiseTable(chartData);
  }

  generateCrpVarietyWiseTable(chartData) {
    let tableHtml = chartData.map(
      (e) => `<tr><td>${e.crpName}</td><td>${e.data.length}</td></tr>`
    );
    $("#table-5-tbody").html(tableHtml);
  }

  generateHybridVarietyByReleaseChart() {
    const chartData = indexFilter.dataViewYears.map((data) => {
      const result = { yearId: data.year_id, year: data.year };
      result.breederData = this.thvrs.filter(
        (d) =>
          d.year_id == data.year_id && d.hybrid_varieties_id == this.BREEDINGID
      );
      result.breederCount = result.breederData.length;
      result.hybridData = this.thvrs.filter(
        (d) =>
          d.year_id == data.year_id && d.hybrid_varieties_id == this.HYBRIDID
      );
      result.hybridCount = result.hybridData.length;
      result.germplasmData = this.thvrs.filter(
        (d) =>
          d.year_id == data.year_id && d.hybrid_varieties_id == this.GERMPLASMID
      );
      result.germplasmCount = result.germplasmData.length;
      return result;
    });

    Highcharts.chart("mpr-stackedChart", {
      chart: {
        type: "column",
      },
      title: {
        text: null,
      },
      credits: {
        enabled: false,
      },
      colors: ["#d79494", "#7cb5ec", "#FFCE56"],
      xAxis: {
        categories: chartData.map((d) => d.year),
      },
      yAxis: {
        min: 0,
        tickInterval: 2,
        title: {
          text: "Number of hybrids/varieties released",
        },
        stackLabels: {
          enabled: true,
          style: {
            fontWeight: "bold",
            color:
              (Highcharts.defaultOptions.title.style &&
                Highcharts.defaultOptions.title.style.color) ||
              "gray",
          },
        },
      },
      tooltip: {
        headerFormat: "<b>{point.x}</b><br/>",
        pointFormat: "{series.name}: {point.y}<br/>Total: {point.stackTotal}",
      },
      plotOptions: {
        column: {
          stacking: "normal",
          dataLabels: {
            enabled: true,
            style: { textOutline: false },
          },
        },
      },
      series: [
        {
          maxPointWidth: 40,
          name: "Breeding Material",
          data: chartData.map((d) => d.breederCount),
        },
        {
          maxPointWidth: 40,
          name: "Hybrid Lines",
          data: chartData.map((d) => d.hybridCount),
        },
        {
          maxPointWidth: 40,
          name: "Germplasm",
          data: chartData.map((d) => d.germplasmCount),
        },
      ],
    });

    this.generateHybridVarietyByReleaseTable(chartData);
  }

  generateHybridVarietyByReleaseTable(chartData) {
    let tableHtml = chartData.map((e) => {
      if (e.breederCount || e.germplasmCount || e.hybridCount) {
        return `<tr><td>${e.year}</td><td>${e.breederCount}</td><td>${
          e.hybridCount
        }</td><td>${e.germplasmCount}</td><td>${
          e.breederCount + e.hybridCount + e.germplasmCount
        }</td></tr>`;
      }
    });
    $("#table-9-tbody").html(tableHtml);
  }

  generateCrpWiseHybridVarietyRelease() {
    const chartData = indexFilter.crps
      .map((data) => {
        const result = { crpId: data.crp_id, crpName: data.crp_name };
        result.breederData = this.thvrs.filter(
          (d) =>
            d.crps.some((e) => e.crp_id == data.crp_id) &&
            d.hybrid_varieties_id == this.BREEDINGID
        );
        result.breederCount = result.breederData.length;
        result.hybridData = this.thvrs.filter(
          (d) =>
            d.crps.some((e) => e.crp_id == data.crp_id) &&
            d.hybrid_varieties_id == this.HYBRIDID
        );
        result.hybridCount = result.hybridData.length;
        result.germplasmData = this.thvrs.filter(
          (d) =>
            d.crps.some((e) => e.crp_id == data.crp_id) &&
            d.hybrid_varieties_id == this.GERMPLASMID
        );
        result.germplasmCount = result.hybridData.length;
        return result;
      })
      .filter((d) => d.breederCount || d.hybridCount || d.germplasmCount);
    Highcharts.chart("mpr-wiseHybrids", {
      chart: {
        type: "column",
      },
      title: {
        text: null,
      },
      credits: {
        enabled: false,
      },
      colors: ["#d79494", "#7cb5ec", "#FFCE56"],
      xAxis: {
        categories: chartData.map((d) => d.crpName),
      },
      yAxis: {
        min: 0,
        tickInterval: 2,
        title: {
          text: "Number of hybrids/varieties released",
        },
        stackLabels: {
          enabled: true,
          style: {
            fontWeight: "bold",
            color:
              (Highcharts.defaultOptions.title.style &&
                Highcharts.defaultOptions.title.style.color) ||
              "gray",
          },
        },
      },
      tooltip: {
        headerFormat: "<b>{point.x}</b><br/>",
        pointFormat: "{series.name}: {point.y}<br/>Total: {point.stackTotal}",
      },
      plotOptions: {
        column: {
          stacking: "normal",
          dataLabels: {
            enabled: true,
            style: { textOutline: false },
          },
        },
      },
      series: [
        {
          maxPointWidth: 40,
          name: "Breeding Material",
          data: chartData.map((d) => d.breederCount),
        },
        {
          maxPointWidth: 40,
          name: "Hybrid Lines",
          data: chartData.map((d) => d.hybridCount),
        },
        {
          maxPointWidth: 40,
          name: "Germplasm",
          data: chartData.map((d) => d.germplasmCount),
        },
      ],
    });

    this.generateCrpWiseHybridVarietyTable(chartData);
  }

  generateCrpWiseHybridVarietyTable(chartData) {
    let tableHtml = chartData.map((e) => {
      if (e.breederCount || e.germplasmCount || e.hybridCount) {
        return `<tr><td>${e.crpName}</td><td>${e.breederCount}</td><td>${e.hybridCount}</td><td>${e.germplasmCount}</td></tr>`;
      }
    });
    $("#table-12-tbody").html(tableHtml);
  }

  generateResearchProgramWiseHybridVarietyChart() {
    const chartData = indexFilter.reasearchPrograms
      .map((data) => {
        const result = { rpId: data.rp_id, rpName: data.rp_name };
        result.breederData = this.thvrs.filter(
          (d) =>
            d.rp_id == data.rp_id && d.hybrid_varieties_id == this.BREEDINGID
        );
        result.breederCount = result.breederData.length;
        result.hybridData = this.thvrs.filter(
          (d) => d.rp_id == data.rp_id && d.hybrid_varieties_id == this.HYBRIDID
        );
        result.hybridCount = result.hybridData.length;
        result.germplasmData = this.thvrs.filter(
          (d) =>
            d.rp_id == data.rp_id && d.hybrid_varieties_id == this.GERMPLASMID
        );
        result.germplasmCount = result.hybridData.length;
        return result;
      })
      .filter((d) => d.breederCount || d.hybridCount || d.germplasmCount);

    Highcharts.chart("mpr-programWise", {
      chart: {
        type: "column",
      },
      title: {
        text: null,
      },
      credits: {
        enabled: false,
      },
      colors: ["#d79494", "#7cb5ec", "#FFCE56"],
      xAxis: {
        categories: chartData.map((d) => d.rpName),
      },
      yAxis: {
        min: 0,
        tickInterval: 2,
        title: {
          text: "Number of hybrids/varieties released",
        },
        stackLabels: {
          enabled: true,
          style: {
            fontWeight: "bold",
            color:
              (Highcharts.defaultOptions.title.style &&
                Highcharts.defaultOptions.title.style.color) ||
              "gray",
          },
        },
      },
      tooltip: {
        headerFormat: "<b>{point.x}</b><br/>",
        pointFormat: "{series.name}: {point.y}<br/>Total: {point.stackTotal}",
      },
      plotOptions: {
        column: {
          stacking: "normal",
          dataLabels: {
            enabled: true,
            style: { textOutline: false },
          },
        },
      },
      series: [
        {
          maxPointWidth: 40,
          name: "Breeding Material",
          data: chartData.map((d) => d.breederCount),
        },
        {
          maxPointWidth: 40,
          name: "Hybrid Lines",
          data: chartData.map((d) => d.hybridCount),
        },
        {
          maxPointWidth: 40,
          name: "Germplasm",
          data: chartData.map((d) => d.germplasmCount),
        },
      ],
    });

    this.generateResearchProgramWiseHybridVarietyTable(chartData);
  }

  generateResearchProgramWiseHybridVarietyTable(chartData) {
    let tableHtml = chartData.map((e) => {
      if (e.breederCount || e.germplasmCount || e.hybridCount) {
        return `<tr><td>${e.rpName}</td><td>${e.breederCount}</td><td>${e.hybridCount}</td><td>${e.germplasmCount}</td></tr>`;
      }
    });
    $("#table-13-tbody").html(tableHtml);
  }

  generateHybridSdgVarietyByReleaseChart() {
    const chartData = indexFilter.dataViewYears
      .map((year, iYear) => {
        const breederData = this.thvrs.filter(
          (d) =>
            d.hybrid_varieties_id == this.BREEDINGID &&
            d.year_id == year.year_id
        );
        const hybridData = this.thvrs.filter(
          (d) =>
            d.hybrid_varieties_id == this.HYBRIDID && d.year_id == year.year_id
        );
        const germplasmData = this.thvrs.filter(
          (d) =>
            d.hybrid_varieties_id == this.GERMPLASMID &&
            d.year_id == year.year_id
        );
        return indexFilter.sdgs.map((sdg, isdg) => {
          const breederStg = breederData.filter((d) =>
            d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
          );
          const hybridrStg = hybridData.filter((d) =>
            d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
          );
          const germplasmStg = germplasmData.filter((d) =>
            d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
          );

          return [
            {
              from: year.year,
              to: "Breeding Material",
              value: breederData.length,
              id: `${iYear}0`,
            },
            {
              from: year.year,
              to: "Hybrid Lines",
              value: hybridData.length,
              id: `${iYear}1`,
            },
            {
              from: year.year,
              to: "Germplasm",
              value: germplasmData.length,
              id: `${iYear}2`,
            },
            {
              from: "Breeding Material",
              to: sdg.sdg_name,
              value: breederStg.length,
              id: `${iYear}0-${isdg}`,
            },
            {
              from: "Hybrid Lines",
              to: sdg.sdg_name,
              value: hybridrStg.length,
              id: `${iYear}1-${isdg}`,
            },
            {
              from: "Germplasm",
              to: sdg.sdg_name,
              value: germplasmStg.length,
              id: `${iYear}2-${isdg}`,
            },
          ];
        });
      })
      .flat()
      .flat()
      .filter((d) => d.value > 0);

    am4core.ready(function () {
      // am4core.useTheme(am4themes_animated);
      // var chart = am4core.create(
      //   "mpr-flowdiaChartType2",
      //   am4charts.SankeyDiagram
      // );
      // chart.hiddenState.properties.opacity = 0;

      // chart.data = chartData;
      // chart.logo.disabled = true;

      // let hoverState = chart.links.template.states.create("hover");
      // hoverState.properties.fillOpacity = 0.6;

      // chart.dataFields.fromName = "from";
      // chart.dataFields.toName = "to";
      // chart.dataFields.value = "value";
      // chart.dataFields.color = "nodeColor";

      // chart.links.template.propertyFields.id = "id";
      // chart.links.template.colorMode = "solid";
      // chart.links.template.fill = new am4core.InterfaceColorSet().getFor(
      //   "alternativeBackground"
      // );
      // chart.links.template.fillOpacity = 0.1;
      // chart.links.template.tooltipText = "";

      // // highlight all links with the same id beginning
      // chart.links.template.events.on("over", function (event) {
      //   let link = event.target;
      //   let id = link.id.split("-")[0];

      //   chart.links.each(function (link) {
      //     if (link.id.indexOf(id) != -1) {
      //       link.isHover = true;
      //     }
      //   });
      // });

      // chart.links.template.events.on("out", function (event) {
      //   chart.links.each(function (link) {
      //     link.isHover = false;
      //   });
      // });

      // // for right-most label to fit
      // chart.paddingRight = 500;
      // chart.paddingLeft = 10;
      // chart.paddingTop = 10;
      // chart.paddingBottom = 50;
      // chart.nodes.template.nameLabel.label.truncate = false;

      // // make nodes draggable
      // var nodeTemplate = chart.nodes.template;
      // nodeTemplate.inert = true;
      // nodeTemplate.readerTitle = "Drag me!";
      // nodeTemplate.showSystemTooltip = true;
      // nodeTemplate.width = 20;

      // // make nodes draggable
      // var nodeTemplate = chart.nodes.template;
      // nodeTemplate.readerTitle = "Click to show/hide or drag to rearrange";
      // nodeTemplate.showSystemTooltip = true;
      // nodeTemplate.cursorOverStyle = am4core.MouseCursorStyle.pointer;

      am4core.useTheme(am4themes_animated);
      var chart = am4core.create(
        "mpr-flowdiaChartType2",
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
    });
  }
  generateCropxCRPCharts() {
    let chartData = indexFilter.pi2020FilterData.crops
      .map((crop) => {
        let cropData = this.thvrs.filter((d) => d.crop_id == crop.crop_id);
        return indexFilter.pi2020FilterData.crps.map((crp) => {
          const cropCrp = cropData.filter((d) =>
            d.crps.some((e) => e.crp_id == crp.crp_id)
          );
          let l1 = {
            from: crop.crop_name,
            to: crp.crp_name,
            value: cropCrp.length,
            id: `crop${crop.crop_id}-crp${crp.crp_id}`,
          };
          let l2 = indexFilter.pi2020FilterData.nutrionaltraiss.map((nu) => {
            const crpNutritional = this.thvrs.filter((e) =>
              e.crps.map((f) => f.crp_id).includes(crp.crp_id)
            );
            const crpNutritionalVals = crpNutritional.filter((e) =>
              e.nutritional
                .map((f) => f.nutritionaltrait_id)
                .includes(nu.nutrionaltraiss_id)
            );
            return {
              from: crp.crp_name,
              to: nu.nutrionaltraiss,
              value: crpNutritionalVals.length,
              id: `crp${crp.crp_id}-nu${nu.nutrionaltraiss_id}`,
            };
          });

          return [l1].concat(...l2);
        });
      })
      .flat()
      .flat()
      .filter((d) => d.value > 0)
      .filter(
        (item, index, self) =>
          index ===
          self.findIndex(
            (t) =>
              t.from === item.from &&
              t.to === item.to &&
              t.value === item.value &&
              t.id === item.id
          )
      );

    am4core.ready(function () {
      am4core.useTheme(am4themes_animated);
      var chart = am4core.create(
        "mpr-flowdiaChartType5",
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

      chart.paddingRight = 100;
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
    });
  }

  generateCropXSdgXTraitChart() {
    let chartData = indexFilter.pi2020FilterData.crops
      .map((crop) => {
        let cropData = this.thvrs.filter((d) => d.crop_id == crop.crop_id);
        return indexFilter.pi2020FilterData.sdgs.map((sdg) => {
          const cropSdg = cropData.filter((d) =>
            d.sdgs.some((e) => e.sdg_id == sdg.sdg_id)
          );
          let l1 = {
            from: crop.crop_name,
            to: sdg.sdg_name,
            value: cropSdg.length,
            id: `crop${crop.crop_id}-sdg${sdg.sdg_id}`,
          };

          let l2 = indexFilter.pi2020FilterData.nutrionaltraiss.map((nu) => {
            const sdgNutritional = this.thvrs.filter((e) =>
              e.sdgs.map((f) => f.sdg_id).includes(sdg.sdg_id)
            );
            const sdgNutritionalVals = sdgNutritional.filter((e) =>
              e.nutritional
                .map((f) => f.nutritionaltrait_id)
                .includes(nu.nutrionaltraiss_id)
            );
            return {
              from: sdg.sdg_name,
              to: nu.nutrionaltraiss,
              value: sdgNutritionalVals.length,
              id: `sdg${sdg.sdg_id}-nu${nu.nutrionaltraiss_id}`,
            };
          });
          return [l1].concat(...l2);
        });
      })
      .flat()
      .flat()
      .filter((d) => d.value > 0)
      .filter(
        (item, index, self) =>
          index ===
          self.findIndex(
            (t) =>
              t.from === item.from &&
              t.to === item.to &&
              t.value === item.value &&
              t.id === item.id
          )
      );

    am4core.ready(function () {
      am4core.useTheme(am4themes_animated);
      var chart = am4core.create(
        "mpr-flowdiaChartType6",
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

      chart.paddingRight = 100;
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
    });
  }

  generateCropXCountryXTraitChart() {
    let chartData = indexFilter.pi2020FilterData.nutrionaltraiss
      .map((nu) => {
        let nuData = this.thvrs.filter((d) =>
          d.nutritional
            .map((e) => e.nutritionaltrait_id)
            .includes(nu.nutrionaltraiss_id)
        );
        return indexFilter.pi2020FilterData.countries.map((country) => {
          const nuCountry = nuData.filter(
            (d) => d.country_id == country.country_id
          );
          let l1 = {
            from: nu.nutrionaltraiss,
            to: country.country_name,
            value: nuCountry.length,
            id: `nu${nu.nutrionaltraiss_id}-country${country.country_id}`,
          };
          let l2 = indexFilter.pi2020FilterData.crops.map((crop) => {
            const countryNutritional = this.thvrs.filter(
              (e) => e.crop_id == crop.crop_id
            );
            const countryNutritionalVals = countryNutritional.filter(
              (e) => e.country_id == country.country_id
            );
            return {
              from: country.country_name,
              to: crop.crop_name,
              value: countryNutritionalVals.length,
              id: `country${country.country_id}-crop${crop.crop_id}`,
            };
          });
          return [l1].concat(...l2);
        });
      })
      .flat()
      .flat()
      .filter((d) => d.value > 0)
      .filter(
        (item, index, self) =>
          index ===
          self.findIndex(
            (t) =>
              t.from === item.from &&
              t.to === item.to &&
              t.value === item.value &&
              t.id === item.id
          )
      );

    am4core.ready(function () {
      am4core.useTheme(am4themes_animated);
      var chart = am4core.create(
        "mpr-flowdiaChartType7",
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

      chart.paddingRight = 100;
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
    });
  }
  generateAgroEcChart() {
    let chartData = indexFilter.pi2020FilterData.agro_ecologies.map((agr) => {
      let result = { name: agr.agro_ecologies };
      result.y = this.thvrs.filter(
        (d) => d["agro-ecology"] == agr.agro_ecologies_id
      ).length;
      return result;
    });
    console.log(chartData);
    Highcharts.chart("agro-ec-graph", {
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
          text: "Number of varieties released",
        },
        labels: {
          overflow: "justify",
        },
      },
      tooltip: {
        pointFormat: "<b>{point.y}</b>",
        //enabled: true,
      },
      plotOptions: {
        series: {
          dataLabels: {
            enabled: true,
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
          name: "Agro Ecology Data",
          data: chartData.map((d) => d.y),
        },
      ],
    });
    $("#table-26-tbody").html(
      chartData.map((e) => `<tr><td>${e.name}</td><td>${e.y}</td></tr>`)
    );
  }
  generateSpillCountriesMap() {
    var targetSVG =
      "M9,0C4.029,0,0,4.029,0,9s4.029,9,9,9s9-4.029,9-9S13.971,0,9,0z M9,15.93 c-3.83,0-6.93-3.1-6.93-6.93S5.17,2.07,9,2.07s6.93,3.1,6.93,6.93S12.83,15.93,9,15.93 M12.5,9c0,1.933-1.567,3.5-3.5,3.5S5.5,10.933,5.5,9S7.067,5.5,9,5.5 S12.5,7.067,12.5,9z";
    const mapData = this.thvrs
      .map((d) => {
        const vals = d.spillover
          .filter((e) => e.spillovercountry_id != d.country_id)
          .map((e) => {
            return {
              multiGeoLine: [
                [
                  getLatLong(indexFilter.countryCodes.get(d.country_id)),
                  getLatLong(
                    indexFilter.countryCodes.get(e.spillovercountry_id)
                  ),
                ],
              ],
            };
          });
        if (!vals?.length) {
          return null;
        } else {
          return vals;
        }
      })
      .filter((d) => d)
      .flat();

      const imageSeriesData = Array.from(new Set(this.thvrs.map(d => {
        const result = [d.country_id];
        result.push(...d.spillover.map(e => e.spillovercountry_id));
        return result;
      }).flat())).map(d => {
        const result = [];
      const currentCountry = getLatLong(
        indexFilter.countryCodes.get(d)
      );
      currentCountry.id = indexFilter.countryName.get(d);
      currentCountry.title = indexFilter.countryName.get(d);
      currentCountry.svgPath = targetSVG;
      currentCountry.scale = 0.5;
      return currentCountry;
      })
    console.log(imageSeriesData);
    am4core.ready(function () {
      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Define marker path

      // Create map instance
      var chart = am4core.create("spill_map", am4maps.MapChart);
      var interfaceColors = new am4core.InterfaceColorSet();

      // Set map definition
      // chart.geodata = am4geodata_worldLow;
      chart.geodata = am4geodata_worldIndiaLow;

      // Set projection
      chart.projection = new am4maps.projections.Mercator();
      chart.logo.disabled = "true";

      // Add zoom control
      chart.zoomControl = new am4maps.ZoomControl();

      chart.maxZoomLevel = 1;
      // Set initial zoom
      // chart.homeZoomLevel = 3;
      // chart.homeGeoPoint = {
      //   latitude: 17,
      //   longitude: 78,
      // };

      // Create map polygon series
      var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
      polygonSeries.exclude = ["AQ"];
      polygonSeries.useGeodata = true;
      polygonSeries.mapPolygons.template.nonScalingStroke = true;

      // Add images
      var imageSeries = chart.series.push(new am4maps.MapImageSeries());
      var imageTemplate = imageSeries.mapImages.template;
      imageTemplate.tooltipText = "{title}";
      imageTemplate.nonScaling = true;

      var marker = imageTemplate.createChild(am4core.Sprite);
      marker.path = targetSVG;
      marker.horizontalCenter = "middle";
      marker.verticalCenter = "middle";
      marker.scale = 0.7;
      marker.fill = interfaceColors.getFor("alternativeBackground");
      imageTemplate.propertyFields.latitude = "latitude";
      imageTemplate.propertyFields.longitude = "longitude";
      imageSeries.data = imageSeriesData;
      // Add lines
      var lineSeries = chart.series.push(new am4maps.MapLineSeries());
      lineSeries.dataFields.multiGeoLine = "multiGeoLine";

      var lineTemplate = lineSeries.mapLines.template;
      lineTemplate.nonScalingStroke = true;
      lineTemplate.arrow.nonScaling = true;
      lineTemplate.arrow.width = 4;
      lineTemplate.arrow.height = 6;
      lineTemplate.stroke = interfaceColors.getFor("alternativeBackground");
      lineTemplate.fill = interfaceColors.getFor("alternativeBackground");
      lineTemplate.line.strokeOpacity = 0.4;
      lineSeries.data = mapData;
    }); // end am4core.ready()
  }

  generateCountriesMap() {
    // const mapData = indexFilter.countries.map(data => {
    //   const result = {id: data.country_id, name: data.country_name, countryCode: data.country_code};
    //   result.z =  this.thvrs.filter(d => d.country_id == data.country_id).length;
    //   result.y =  JSON.stringify(indexFilter.pi2020FilterData.hybrid_varieties.map(hv => {
    //       let hvVal = this.thvrs.filter(e => e.hybrid_varieties_id == hv.hybrid_variety_id).length;
    //       return {[hv.hybrid_variety] : hvVal}
    //   }));
    //   return result;
    // }).filter(d => d.z > 0);

    // Highcharts.mapChart("graph-2", {
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
    //         formatter: "{point.name}: {point.z}",
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
        let result = {
          id: data.country_code,
          name: data.country_name,
          color: "lightblue",
        };
        result.value = this.thvrs.filter(
          (d) => d.country_id == data.country_id
        ).length;
        result.y = JSON.stringify(
          indexFilter.pi2020FilterData.hybrid_varieties.map((hv) => {
            let hvVal = this.thvrs.filter(
              (e) => e.hybrid_varieties_id == hv.hybrid_variety_id
            ).length;
            return { [hv.hybrid_variety]: hvVal };
          })
        );
        return result;
      })
      .filter((d) => d.value > 0);

    am4core.ready(function () {
      am4core.useTheme(am4themes_animated);
      var chart = am4core.create("graph-2", am4maps.MapChart);
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
      // circle.fill = am4core.color("#a791b4");
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
          result.hybridLineCount = this.thvrs.filter(
            (e) =>
              e.crop_id == selectedCrop &&
              e.year_id == yr.year_id &&
              e.hybrid_varieties_id == 1
          ).length;
          result.germplasmCount = this.thvrs.filter(
            (e) =>
              e.crop_id == selectedCrop &&
              e.year_id == yr.year_id &&
              e.hybrid_varieties_id == 2
          ).length;
          result.breederCount = this.thvrs.filter(
            (e) =>
              e.crop_id == selectedCrop &&
              e.year_id == yr.year_id &&
              e.hybrid_varieties_id == 3
          ).length;
        } else {
          result.hybridLineCount = this.thvrs.filter(
            (e) => e.year_id == yr.year_id && e.hybrid_varieties_id == 1
          ).length;
          result.germplasmCount = this.thvrs.filter(
            (e) => e.year_id == yr.year_id && e.hybrid_varieties_id == 2
          ).length;
          result.breederCount = this.thvrs.filter(
            (e) => e.year_id == yr.year_id && e.hybrid_varieties_id == 3
          ).length;
        }
        return result;
      })
      .filter((d) => d.hybridLineCount || d.germplasmCount || d.breederCount);
    Highcharts.chart("hv-year-crop-graph", {
      chart: { type: "column" },
      title: { text: "" },
      xAxis: { categories: chartData.map((e) => e.year) },
      yAxis: {
        min: 0,
        title: { text: "Number of varieties released" },
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
      ],
    });
    $("#table-14-tbody").html(
      chartData.map(
        (e) =>
          `<tr><td>${e.year}</td><td>${e.hybridLineCount}</td><td>${e.germplasmCount}</td><td>${e.breederCount}</td></tr>`
      )
    );
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
    // console.log(this.thvrs);
    const chartData = indexFilter.dataViewYears.map((yr) => {
      let result = { year: yr.year };
      let yearRecords = this.thvrs.filter((e) => e.year_id == yr.year_id);
      let varietyRecords = selectedVariety
        ? yearRecords.filter((e) => e.hybrid_varieties_id == selectedVariety)
        : yearRecords;
      indexFilter.pi2020FilterData.crps.forEach((crp) => {
        result[crp.crp_name] = varietyRecords.filter((e) =>
          e.crps.map((f) => f.crp_id).includes(crp.crp_id)
        ).length;
      });
      return result;
    });

    Highcharts.chart("hv-year-crp-graph", {
      chart: { type: "column" },
      title: { text: "" },
      xAxis: { categories: chartData.map((e) => e.year) },
      yAxis: {
        min: 0,
        title: { text: "Number of varieties released" },
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
        },
      },
      series: indexFilter.pi2020FilterData.crps.map((e) => {
        return { name: e.crp_name, data: chartData.map((f) => f[e.crp_name]) };
      }),
    });

    $("#table-15-thead-row").html(
      `<th>CRP</th>` + chartData.map((e) => `<th>${e.year}</th>`)
    );
    $("#table-15-tbody").html(
      indexFilter.pi2020FilterData.crps.map((e) => {
        let yVals = chartData.map((f) => `<td>${f[e.crp_name]}</td>`);
        return `<tr><td>${e.crp_name}</td>${yVals}</tr>`;
      })
    );
  }

  getHtmlActionForContrysYearComparison() {
    const crpListHtml = this.generateVarietyList("year-comp-cont-list");
    $("#year-comp-contrys").html(crpListHtml);
    $(".year-comp-cont-list").on("click", (env) => {
      const ele = $(env.target);
      const data = ele.data();
      $("#year-comp-contry-name").html(data.label);
      $("#year-comp-contry-name").data("value", data.value);
      this.getContriesYearComparisonInfo();
    });
  }

  getContriesYearComparisonInfo() {
    const selectedVariety = $("#year-comp-contry-name").data("value");
    const chartData = indexFilter.dataViewYears.map((yr) => {
      let result = { year: yr.year };
      // let varietyRecords = selectedVariety ? yearRecords.filter(e => e.hybrid_varieties_id == selectedVariety) : yearRecords;
      indexFilter.pi2020FilterData.countries.forEach((con) => {
        if (selectedVariety) {
          result[con.country_name] = this.thvrs
            .filter(
              (e) =>
                e.year_id == yr.year_id &&
                e.country_id == con.country_id &&
                e.hybrid_varieties_id == selectedVariety
            )
            .map((r) => r.data_id).length;
        } else {
          result[con.country_name] = this.thvrs
            .filter(
              (e) => e.year_id == yr.year_id && e.country_id == con.country_id
            )
            .map((r) => r.data_id).length;
        }
        // result[con.country_name] = this.thvrs.filter(e => e.year_id == yr.year_id && e.country_id == con.country_id && e.hybrid_varieties_id == selectedVariety).map(r=>r.data_id).length;
        // result[con.country_name] = varietyRecords.filter(e => e.thvrs.map(f => f.country_id).includes(con.country_name)).length;
      });
      return result;
    });

    Highcharts.chart("hv-year-contry-graph", {
      chart: { type: "column" },
      title: { text: "" },
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
        min: 0,
        title: { text: "Number of varieties released" },
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
        },
      },
      series: indexFilter.pi2020FilterData.countries
        .map((c) => {
          let result = {
            name: c.country_name,
            data: chartData
              .filter((e) => e[c.country_name] > 0)
              .map((e) => e[c.country_name]),
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

    $("#table-16-thead-row").html(
      `<th>Countries</th>` + chartData.map((e) => `<th>${e.year}</th>`)
    );
    $("#table-16-tbody").html(
      indexFilter.pi2020FilterData.countries.map((e) => {
        let yVals = chartData.map((f) => f[e.country_name]);
        if (!yVals.every((e) => e == 0)) {
          let yValsHtml = chartData.map(
            (f) => `<td>${numberWithCommas(f[e.country_name])}</td>`
          );
          return `<tr><td>${e.country_name}</td>${yValsHtml}</tr>`;
        }
      })
    );
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
      graphTab1.find("img").prop("src", "img/Pie-selected.svg");
      tableTab1.find("img").prop("src", "img/Table.svg");
    });

    tableTab1.on("click", () => {
      tableTab1.addClass("active");
      graphTab1.removeClass("active");
      table1.show();
      graph1.hide();
      graphTab1.find("img").prop("src", "img/Pie.svg");
      tableTab1.find("img").prop("src", "img/Table-selected.svg");
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
          .attr("download", `hybrid-varieties.jpeg`);
      });
    });
    $("#dwn-csv-1").on("click", function () {
      $("#table-1-main").table2csv({
        file_name: "hybrid-varieties.csv",
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
      graphTab2.find("img").prop("src", "img/Map-selected.svg");
      tableTab2.find("img").prop("src", "img/Table.svg");
    });

    tableTab2.on("click", () => {
      tableTab2.addClass("active");
      graphTab2.removeClass("active");
      table2.show();
      graph2.hide();
      graphTab2.find("img").prop("src", "img/Map.svg");
      tableTab2.find("img").prop("src", "img/Table-selected.svg");
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
          .attr("download", `hybrid-varieties-countries.jpeg`);
      });
    });
    $("#dwn-csv-2").on("click", function () {
      $("#table-2-main").table2csv({
        file_name: "hybrid-varieties-countries.csv",
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
      graphTab3.find("img").prop("src", "img/Bar-selected.svg");
      tableTab3.find("img").prop("src", "img/Table.svg");
    });

    tableTab3.on("click", () => {
      tableTab3.addClass("active");
      graphTab3.removeClass("active");
      table3.show();
      graph3.hide();
      graphTab3.find("img").prop("src", "img/Bar.svg");
      tableTab3.find("img").prop("src", "img/Table-selected.svg");
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
          .attr("download", `hybrid-varieties-crops.jpeg`);
      });
    });
    $("#dwn-csv-3").on("click", function () {
      $("#table-3-main").table2csv({
        file_name: "hybrid-varieties-countries.csv",
        header_body_space: 0,
      });
    });

    // graph-4
    const downloadTab4 = $("#download-btn-4>img");
    downloadTab4.on("click", () => {
      html2canvas(document.getElementById("graph-4")).then((canvas) => {
        let dataSrc = canvas.toDataURL("image/png");
        dataSrc = dataSrc.replace("data:image/png;base64,", "");
        $("#dwn-img-4")
          .attr(
            "href",
            "data:application/octet-stream;base64," + encodeURI(dataSrc)
          )
          .attr("target", "_blank")
          .attr("download", `hybrid-varieties-sdgs.jpeg`);
      }); // change to highcharts?
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
      graphTab5.find("img").prop("src", "img/Bar-selected.svg");
      tableTab5.find("img").prop("src", "img/Table.svg");
    });

    tableTab5.on("click", () => {
      tableTab5.addClass("active");
      graphTab5.removeClass("active");
      table5.show();
      graph5.hide();
      graphTab5.find("img").prop("src", "img/Bar.svg");
      tableTab5.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab5.on("click", () => {
      if (downloadTab5.attr("src") == "img/download.svg") {
        downloadTab5.prop("src", "img/Download-selected.svg");
      } else if (downloadTab5.attr("src") == "img/Download-selected.svg") {
        downloadTab5.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-crps.jpeg`);
      });
    });
    $("#dwn-csv-5").on("click", function () {
      $("#table-5-main").table2csv({
        file_name: "hybrid-varieties-crps.csv",
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
      graphTab6.find("img").prop("src", "img/Pie-selected.svg");
      tableTab6.find("img").prop("src", "img/Table.svg");
    });

    tableTab6.on("click", () => {
      tableTab6.addClass("active");
      graphTab6.removeClass("active");
      table6.show();
      graph6.hide();
      graphTab6.find("img").prop("src", "img/Pie.svg");
      tableTab6.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab6.on("click", () => {
      if (downloadTab6.attr("src") == "img/download.svg") {
        downloadTab6.prop("src", "img/Download-selected.svg");
      } else if (downloadTab6.attr("src") == "img/Download-selected.svg") {
        downloadTab6.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-rps.jpeg`);
      });
    });
    $("#dwn-csv-6").on("click", function () {
      $("#table-6-main").table2csv({
        file_name: "hybrid-varieties-rps.csv",
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
      graphTab9.find("img").prop("src", "img/Bar-selected.svg");
      tableTab9.find("img").prop("src", "img/Table.svg");
    });

    tableTab9.on("click", () => {
      tableTab9.addClass("active");
      graphTab9.removeClass("active");
      table9.show();
      graph9.hide();
      graphTab9.find("img").prop("src", "img/Bar.svg");
      tableTab9.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab9.on("click", () => {
      if (downloadTab9.attr("src") == "img/download.svg") {
        downloadTab9.prop("src", "img/Download-selected.svg");
      } else if (downloadTab9.attr("src") == "img/Download-selected.svg") {
        downloadTab9.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-yr.jpeg`);
      });
    });
    $("#dwn-csv-9").on("click", function () {
      $("#table-9-main").table2csv({
        file_name: "hybrid-varieties-yr.csv",
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
      graphTab12.find("img").prop("src", "img/Bar-selected.svg");
      tableTab12.find("img").prop("src", "img/Table.svg");
    });

    tableTab12.on("click", () => {
      tableTab12.addClass("active");
      graphTab12.removeClass("active");
      table12.show();
      graph12.hide();
      graphTab12.find("img").prop("src", "img/Bar.svg");
      tableTab12.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab12.on("click", () => {
      if (downloadTab12.attr("src") == "img/download.svg") {
        downloadTab12.prop("src", "img/Download-selected.svg");
      } else if (downloadTab12.attr("src") == "img/Download-selected.svg") {
        downloadTab12.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-crp.jpeg`);
      });
    });
    $("#dwn-csv-12").on("click", function () {
      $("#table-12-main").table2csv({
        file_name: "hybrid-varieties-crp.csv",
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
      graphTab13.find("img").prop("src", "img/Bar-selected.svg");
      tableTab13.find("img").prop("src", "img/Table.svg");
    });

    tableTab13.on("click", () => {
      tableTab13.addClass("active");
      graphTab13.removeClass("active");
      table13.show();
      graph13.hide();
      graphTab13.find("img").prop("src", "img/Bar.svg");
      tableTab13.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab13.on("click", () => {
      if (downloadTab13.attr("src") == "img/download.svg") {
        downloadTab13.prop("src", "img/Download-selected.svg");
      } else if (downloadTab13.attr("src") == "img/Download-selected.svg") {
        downloadTab13.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-rp.jpeg`);
      });
    });
    $("#dwn-csv-13").on("click", function () {
      $("#table-13-main").table2csv({
        file_name: "hybrid-varieties-rp.csv",
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
      graphTab14.find("img").prop("src", "img/Bar-selected.svg");
      tableTab14.find("img").prop("src", "img/Table.svg");
    });

    tableTab14.on("click", () => {
      tableTab14.addClass("active");
      graphTab14.removeClass("active");
      table14.show();
      graph14.hide();
      graphTab14.find("img").prop("src", "img/Bar.svg");
      tableTab14.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab14.on("click", () => {
      if (downloadTab14.attr("src") == "img/download.svg") {
        downloadTab14.prop("src", "img/Download-selected.svg");
      } else if (downloadTab14.attr("src") == "img/Download-selected.svg") {
        downloadTab14.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-year.jpeg`);
      });
    });
    $("#dwn-csv-14").on("click", function () {
      $("#table-14-main").table2csv({
        file_name: "hybrid-varieties-year.csv",
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
      graphTab15.find("img").prop("src", "img/Bar-selected.svg");
      tableTab15.find("img").prop("src", "img/Table.svg");
    });

    tableTab15.on("click", () => {
      tableTab15.addClass("active");
      graphTab15.removeClass("active");
      table15.show();
      graph15.hide();
      graphTab15.find("img").prop("src", "img/Bar.svg");
      tableTab15.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab15.on("click", () => {
      if (downloadTab15.attr("src") == "img/download.svg") {
        downloadTab15.prop("src", "img/Download-selected.svg");
      } else if (downloadTab15.attr("src") == "img/Download-selected.svg") {
        downloadTab15.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-crp.jpeg`);
      });
    });
    $("#dwn-csv-15").on("click", function () {
      $("#table-15-main").table2csv({
        file_name: "hybrid-varieties-crp.csv",
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
      graphTab16.find("img").prop("src", "img/Bar-selected.svg");
      tableTab16.find("img").prop("src", "img/Table.svg");
    });

    tableTab16.on("click", () => {
      tableTab16.addClass("active");
      graphTab16.removeClass("active");
      table16.show();
      graph16.hide();
      graphTab16.find("img").prop("src", "img/Bar.svg");
      tableTab16.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab16.on("click", () => {
      if (downloadTab16.attr("src") == "img/download.svg") {
        downloadTab16.prop("src", "img/Download-selected.svg");
      } else if (downloadTab16.attr("src") == "img/Download-selected.svg") {
        downloadTab16.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-contries.jpeg`);
      });
    });
    $("#dwn-csv-16").on("click", function () {
      $("#table-16-main").table2csv({
        file_name: "hybrid-varieties-contries.csv",
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
      graphTab17.find("img").prop("src", "img/Bar-selected.svg");
      tableTab17.find("img").prop("src", "img/Table.svg");
    });

    tableTab17.on("click", () => {
      tableTab17.addClass("active");
      graphTab17.removeClass("active");
      table17.show();
      graph17.hide();
      graphTab17.find("img").prop("src", "img/Bar.svg");
      tableTab17.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab17.on("click", () => {
      if (downloadTab17.attr("src") == "img/download.svg") {
        downloadTab17.prop("src", "img/Download-selected.svg");
      } else if (downloadTab17.attr("src") == "img/Download-selected.svg") {
        downloadTab17.prop("src", "img/download.svg");
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
          .attr("download", `hybrid-varieties-crops.jpeg`);
      });
    });
    $("#dwn-csv-17").on("click", function () {
      $("#table-17-main").table2csv({
        file_name: "hybrid-varieties-countries.csv",
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
      graphTab19.find("img").prop("src", "img/Bar-selected.svg");
      tableTab19.find("img").prop("src", "img/Table.svg");
    });

    tableTab19.on("click", () => {
      tableTab19.addClass("active");
      graphTab19.removeClass("active");
      table19.show();
      graph19.hide();
      graphTab19.find("img").prop("src", "img/Bar.svg");
      tableTab19.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab19.on("click", () => {
      if (downloadTab19.attr("src") == "img/download.svg") {
        downloadTab19.prop("src", "img/Download-selected.svg");
      } else if (downloadTab19.attr("src") == "img/Download-selected.svg") {
        downloadTab19.prop("src", "img/download.svg");
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
          .attr("download", `nutritional_trait.jpeg`);
      });
    });
    $("#dwn-csv-19").on("click", function () {
      $("#table-19-main").table2csv({
        file_name: "nutritional_trait.csv",
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
      graphTab20.find("img").prop("src", "img/Bar-selected.svg");
      tableTab20.find("img").prop("src", "img/Table.svg");
    });

    tableTab20.on("click", () => {
      tableTab20.addClass("active");
      graphTab20.removeClass("active");
      table20.show();
      graph20.hide();
      graphTab20.find("img").prop("src", "img/Bar.svg");
      tableTab20.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab20.on("click", () => {
      if (downloadTab20.attr("src") == "img/download.svg") {
        downloadTab20.prop("src", "img/Download-selected.svg");
      } else if (downloadTab20.attr("src") == "img/Download-selected.svg") {
        downloadTab20.prop("src", "img/download.svg");
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
          .attr("download", `nutritional_trait.jpeg`);
      });
    });
    $("#dwn-csv-20").on("click", function () {
      $("#table-20-main").table2csv({
        file_name: "nutritional_trait.csv",
        header_body_space: 0,
      });
    });

    //graph-24
    const graphTab24 = $("#graph-btn-24");
    const tableTab24 = $("#table-btn-24");
    const downloadTab24 = $("#download-btn-24>img");

    const graph24 = $("#graph-24");
    const table24 = $("#table-24");

    graphTab24.on("click", () => {
      graphTab24.addClass("active");
      tableTab24.removeClass("active");
      graph24.show();
      table24.hide();
      graphTab24.find("img").prop("src", "img/Pie-selected.svg");
      tableTab24.find("img").prop("src", "img/Table.svg");
    });

    tableTab24.on("click", () => {
      tableTab24.addClass("active");
      graphTab24.removeClass("active");
      table24.show();
      graph24.hide();
      graphTab24.find("img").prop("src", "img/Pie.svg");
      tableTab24.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab24.on("click", () => {
      if (downloadTab24.attr("src") == "img/download.svg") {
        downloadTab24.prop("src", "img/Download-selected.svg");
      } else if (downloadTab24.attr("src") == "img/Download-selected.svg") {
        downloadTab24.prop("src", "img/download.svg");
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
          .attr("download", `hv-publications.jpeg`);
      });
    });
    $("#dwn-csv-24").on("click", function () {
      $("#table-24-main").table2csv({
        file_name: "hv-publications.csv",
        header_body_space: 0,
      });
    });

    //graph-25
    const graphTab25 = $("#graph-btn-25");
    const tableTab25 = $("#table-btn-25");
    const downloadTab25 = $("#download-btn-25>img");

    const graph25 = $("#graph-25");
    const table25 = $("#table-25");

    graphTab25.on("click", () => {
      graphTab25.addClass("active");
      tableTab25.removeClass("active");
      graph25.show();
      table25.hide();
      graphTab25.find("img").prop("src", "img/Pie-selected.svg");
      tableTab25.find("img").prop("src", "img/Table.svg");
    });

    tableTab25.on("click", () => {
      tableTab25.addClass("active");
      graphTab25.removeClass("active");
      table25.show();
      graph25.hide();
      graphTab25.find("img").prop("src", "img/Pie.svg");
      tableTab25.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab25.on("click", () => {
      if (downloadTab25.attr("src") == "img/download.svg") {
        downloadTab25.prop("src", "img/Download-selected.svg");
      } else if (downloadTab25.attr("src") == "img/Download-selected.svg") {
        downloadTab25.prop("src", "img/download.svg");
      }
      html2canvas(document.getElementById("graph-25")).then((canvas) => {
        let dataSrc = canvas.toDataURL("image/png");
        dataSrc = dataSrc.replace("data:image/png;base64,", "");
        $("#dwn-img-25")
          .attr(
            "href",
            "data:application/octet-stream;base64," + encodeURI(dataSrc)
          )
          .attr("target", "_blank")
          .attr("download", `hv-bms.jpeg`);
      });
    });
    $("#dwn-csv-25").on("click", function () {
      $("#table-25-main").table2csv({
        file_name: "hv-bms.csv",
        header_body_space: 0,
      });
    });

    //graph-26
    const graphTab26 = $("#graph-btn-26");
    const tableTab26 = $("#table-btn-26");
    const downloadTab26 = $("#download-btn-26>img");

    const graph26 = $("#graph-26");
    const table26 = $("#table-26");

    graphTab26.on("click", () => {
      graphTab26.addClass("active");
      tableTab26.removeClass("active");
      graph26.show();
      table26.hide();
      graphTab26.find("img").prop("src", "img/Bar-selected.svg");
      tableTab26.find("img").prop("src", "img/Table.svg");
    });

    tableTab26.on("click", () => {
      tableTab26.addClass("active");
      graphTab26.removeClass("active");
      table26.show();
      graph26.hide();
      graphTab26.find("img").prop("src", "img/Bar.svg");
      tableTab26.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab26.on("click", () => {
      if (downloadTab26.attr("src") == "img/download.svg") {
        downloadTab26.prop("src", "img/Download-selected.svg");
      } else if (downloadTab26.attr("src") == "img/Download-selected.svg") {
        downloadTab26.prop("src", "img/download.svg");
      }
      html2canvas(document.getElementById("graph-26")).then((canvas) => {
        let dataSrc = canvas.toDataURL("image/png");
        dataSrc = dataSrc.replace("data:image/png;base64,", "");
        $("#dwn-img-26")
          .attr(
            "href",
            "data:application/octet-stream;base64," + encodeURI(dataSrc)
          )
          .attr("target", "_blank")
          .attr("download", `hv-agro.jpeg`);
      });
    });
    $("#dwn-csv-26").on("click", function () {
      $("#table-26-main").table2csv({
        file_name: "hv-agro.csv",
        header_body_space: 0,
      });
    });

    //graph-27
    const graphTab27 = $("#graph-btn-27");
    const tableTab27 = $("#table-btn-27");
    const downloadTab27 = $("#download-btn-27>img");

    const graph27 = $("#graph-27");
    const table27 = $("#table-27");

    graphTab27.on("click", () => {
      graphTab27.addClass("active");
      tableTab27.removeClass("active");
      graph27.show();
      table27.hide();
      graphTab27.find("img").prop("src", "img/Map-selected.svg");
      tableTab27.find("img").prop("src", "img/Table.svg");
    });

    tableTab27.on("click", () => {
      tableTab27.addClass("active");
      graphTab27.removeClass("active");
      table27.show();
      graph27.hide();
      graphTab27.find("img").prop("src", "img/Map.svg");
      tableTab27.find("img").prop("src", "img/Table-selected.svg");
    });

    downloadTab27.on("click", () => {
      if (downloadTab27.attr("src") == "img/download.svg") {
        downloadTab27.prop("src", "img/Download-selected.svg");
      } else if (downloadTab27.attr("src") == "img/Download-selected.svg") {
        downloadTab27.prop("src", "img/download.svg");
      }
      html2canvas(document.getElementById("graph-27")).then((canvas) => {
        let dataSrc = canvas.toDataURL("image/png");
        dataSrc = dataSrc.replace("data:image/png;base64,", "");
        $("#dwn-img-27")
          .attr(
            "href",
            "data:application/octet-stream;base64," + encodeURI(dataSrc)
          )
          .attr("target", "_blank")
          .attr("download", `hv-agro.jpeg`);
      });
    });
    $("#dwn-csv-27").on("click", function () {
      $("#table-27-main").table2csv({
        file_name: "hv-agro.csv",
        header_body_space: 0,
      });
    });
  }

  graphScientificPublications() {
    let chartData = indexFilter.pi2020FilterData.scientific_publications.map(
      (s) => {
        let result = { name: s.scientific_publications };
        result.y = this.thvrs.filter(
          (d) => d.scientific_publications == s.sp_id
        ).length;
        return result;
      }
    );

    Highcharts.chart("hv-sp-piegraph", {
      chart: { type: "pie" },
      title: { text: null },
      subtitle: { text: null },
      credits: { enabled: false },
      colors: ["#d79494", "#7cb5ec", "#ffce56"],
      plotOptions: {
        pie: {
          allowPointSelect: false,
          dataLabels: {
            enabled: true,
            format:
              "{point.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)",
            style: { textOutline: false },
          },
          showInLegend: true,
          // ({point.percentage:.2f} %)
        },
      },
      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat:
          '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)<br/>',
      },
      series: [
        {
          name: "Publication",
          colorByPoint: true,
          data: chartData,
        },
      ],
    });

    $("#table-24-tbody").html(
      chartData.map((e) => `<tr><td>${e.name}</td><td>${e.y}</td></tr>`)
    );
  }

  graphBMSData() {
    let chartData = indexFilter.pi2020FilterData.bms.map((b) => {
      let result = { name: b.bms };
      result.y = this.thvrs.filter((d) => d.bms == b.bms_id).length;
      return result;
    });

    Highcharts.chart("hv-bms-piegraph", {
      chart: { type: "pie" },
      title: { text: null },
      subtitle: { text: null },
      credits: { enabled: false },
      colors: ["#d79494", "#7cb5ec", "#ffce56"],
      plotOptions: {
        pie: {
          allowPointSelect: false,
          dataLabels: {
            enabled: true,
            format:
              "{point.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)",
            style: { textOutline: false },
          },
          showInLegend: true,
          // ({point.percentage:.2f} %)
        },
      },
      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat:
          '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> ({point.percentage:.2f} %)<br/>',
      },
      series: [
        {
          name: "Availibility in BMS",
          colorByPoint: true,
          data: chartData,
        },
      ],
    });

    $("#table-25-tbody").html(
      chartData.map((e) => `<tr><td>${e.name}</td><td>${e.y}</td></tr>`)
    );
  }
}
