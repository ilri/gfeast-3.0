class IndexFilter {
  constructor() {
    this.pi2020FilterData = null;

    this.getLookupsData();
  }

  getTabData() {
    const request =  indexFilter.getFilteredData();
    request.purpose = "get_tab_counters";
    post("pi_2020", request).then(response => {
      if (response) {
        this.tabsData = response;
        this.tabsData.forEach((d, i) => {
          $(`[data-tabnum="${i}"]`).html(Number(d.toFixed(2)));
        });
        if (selectedTab) {
          selectedTab.trigger('click');
        }
      }
    }).catch(err => console.log(err));
  }

  getLookupsData() {
    const request = { purpose: "get_lookups" };
    post("pi_2020", request)
      .then((response) => {
        if (response) {
          this.pi2020FilterData = response;
          this.generateCuntriesFilter();
          this.getYearsFilter();
          this.generateCropsFilter();
          this.generateSDGFilter();
          this.generateCRPSFilter();
          this.generateReasearchProgramsFilter();
          this.setSearchAction();
          this.getTabData();
        }
      })
      .catch((err) => console.log(err));
  }

  getBreedingGermplasmHybrid() {
    const request = { purpose: "get_breeding_germplasm_hybrid" };
    post("pi_2020", request)
      .then((response) => {
        this.breedingGermplasmHybrid = response;
        this.generateChart();
      })
      .catch((err) => console.log(err));
  }

  generateCuntriesFilter() {
    if (this.pi2020FilterData.countries) {
      this.countries = clone(this.pi2020FilterData.countries);
      this.countryCodes = new Map();
      this.countryName = new Map();
      this.countries.forEach(d => {
        this.countryCodes.set(d.country_id, d.country_code);
        this.countryName.set(d.country_id, d.country_name);
      });
      const countryHTML = this.countries
        .map((country, index) => {
          const selectAll =
            index != 0
              ? ""
              : `
            <li>
                <label for="country_0" class="msCheck-label"> Select All
                    <input type="checkbox" class="all-filter-country" id="country_0" data-label="All Countries" data-value="0" checked="checked">
                    <span class="msCheck-checkmark"></span>
                </label>
            </li>
        `;
          const htmlData = `
                ${selectAll}
                <li>
                    <label for="country_${country.country_id}" class="msCheck-label"> ${country.country_name}
                        <input type="checkbox" class="filter-country" id="country_${country.country_id}" data-label="${country.country_name}" data-value="${country.country_id}" checked="checked">
                        <span class="msCheck-checkmark"></span>
                    </label>
                </li>
                `;
          return htmlData;
        })
        .join("\n");
      $("#country-filter-options").html(countryHTML);
      const onCountryChange = (env) => {
        const countryData = $.map($(".filter-country"), (ele) => {
          const eleData = clone($(ele).data());
          eleData["checked"] = ele.checked;
          return eleData;
        });
        const isAllCountry = countryData.every((d) => d.checked);
        $("#country_0").prop("checked", isAllCountry);
        this.setSelectedLabel('.filter-country', '#countries-selected-label', 'Select Countries');
      };
      const onAllCountryChange = (env) => {
        const isAllCountry = env.target.checked;
        $(".filter-country").prop("checked", isAllCountry);
        this.setSelectedLabel('.filter-country', '#countries-selected-label', 'Select Countries');
      };
      $(".filter-country").on("change", onCountryChange);
      $("#country_0").on("change", onAllCountryChange);
    }
  }

  getYearsFilter() {
    if (this.pi2020FilterData.years) {
      this.years = clone(this.pi2020FilterData.years)
      .sort((v1, v2) => parseInt(v1.sl_no) - parseInt(v2.sl_no));

      const carouselYearListHTML = this.years
        .map((year) => {
          const htmlData = `
            <div class="item" data-label="${year.year}" data-value="${year.year_id}">
                <div class="mpYear-item">
                    <h4>${year.year}</h4>
                </div>
            </div>`;
          return htmlData;
        })
        .join("\n");
      $("#msSingle_year, #msRange_year1, #msRange_year").html(
        carouselYearListHTML
      );

      const yearOptionHTML = this.years
        .map((year, index) => {
          const selectAll =
            index != 0
              ? ""
              : `
            <li>
                <label for="year_0" class="msCheck-label"> Select All
                    <input type="checkbox" class="all-filter-year" id="year_0" data-label="All Years" data-value="0" checked="checked">
                    <span class="msCheck-checkmark"></span>
                </label>
            </li>
        `;
          const htmlData = `
                    ${selectAll}
                    <li>
                        <label for="year_${year.year_id}" class="msCheck-label"> ${year.year}
                            <input type="checkbox" class="filter-year" id="year_${year.year_id}" data-label="${year.year}" data-value="${year.year_id}" checked="checked">
                            <span class="msCheck-checkmark"></span>
                        </label>
                    </li>
                    `;
          return htmlData;
        })
        .join("\n");
      $("#year-filter-options").html(yearOptionHTML);

      const onChange = (env) => {
        const data = $.map($(".filter-year"), (ele) => {
          const eleData = clone($(ele).data());
          eleData["checked"] = ele.checked;
          return eleData;
        });
        const isAll = data.every((d) => d.checked);
        $("#year_0").prop("checked", isAll);
      };
      const onAllChange = (env) => {
        const isAll = env.target.checked;
        $(".filter-year").prop("checked", isAll);
      };
      $(".filter-year").on("change", onChange);
      $("#year_0").on("change", onAllChange);

      // setTimeout(() => {
        $("#msSingle_year, #msRange_year1, #msRange_year").owlCarousel({
          nav: true,
          dots: false,
          loop: true,
          items: 1,
          startPosition: this.years.length-1
        });
      // });
    }
  }

  generateCropsFilter() {
    if (this.pi2020FilterData.crops) {
      this.crops = clone(this.pi2020FilterData.crops);
      const cropsHTML = this.crops
        .map((crop, index) => {
          const selectAll =
            index != 0
              ? ""
              : `
            <li>
                <label for="crop_0" class="msCheck-label"> Select All
                    <input type="checkbox" class="all-filter-crop" id="crop_0" data-label="All Crops" data-value="0" checked="checked">
                    <span class="msCheck-checkmark"></span>
                </label>
            </li>
        `;
          const htmlData = `
                ${selectAll}
                <li>
                    <label for="crop_${crop.crop_id}" class="msCheck-label"> ${crop.crop_name}
                        <input type="checkbox" class="filter-crop" id="crop_${crop.crop_id}" data-label="${crop.crop_name}" data-value="${crop.crop_id}" checked="checked">
                        <span class="msCheck-checkmark"></span>
                    </label>
                </li>
                `;
          return htmlData;
        })
        .join("\n");
      $("#crop-filter-options").html(cropsHTML);

      const onChange = (env) => {
        const data = $.map($(".filter-crop"), (ele) => {
          const eleData = clone($(ele).data());
          eleData["checked"] = ele.checked;
          return eleData;
        });
        const isAll = data.every((d) => d.checked);
        $("#crop_0").prop("checked", isAll);
        this.setSelectedLabel('.filter-crop', '#crops-selected-label', 'Select Crops');
      };
      const onAllChange = (env) => {
        const isAll = env.target.checked;
        $(".filter-crop").prop("checked", isAll);
        this.setSelectedLabel('.filter-crop', '#crops-selected-label', 'Select Crops');
      };
      $(".filter-crop").on("change", onChange);
      $("#crop_0").on("change", onAllChange);
    }
  }

  generateSDGFilter() {
    if (this.pi2020FilterData.crops) {
      this.sdgs = clone(this.pi2020FilterData.sdgs);
      const sdgsHTML = this.sdgs
        .map((sdg, index) => {
          const selectAll =
            index != 0
              ? ""
              : `
        <li>
            <label for="sdg_0" class="msCheck-label"> Select All
                <input type="checkbox" class="all-filter-sdg" id="sdg_0" data-label="All SDGS" data-value="0" checked="checked">
                <span class="msCheck-checkmark"></span>
            </label>
        </li>
    `;
          const htmlData = `
                ${selectAll}
                <li>
                    <label for="sdg_${sdg.sdg_id}" class="msCheck-label"> ${sdg.sdg_name}
                        <input type="checkbox" class="filter-sdg" id="sdg_${sdg.sdg_id}" data-label="${sdg.sdg_name}" data-value="${sdg.sdg_id}" checked="checked">
                        <span class="msCheck-checkmark"></span>
                    </label>
                </li>
                `;
          return htmlData;
        })
        .join("\n");
      $("#sdg-filter-options").html(sdgsHTML);

      const onChange = (env) => {
        const data = $.map($(".filter-sdg"), (ele) => {
          const eleData = clone($(ele).data());
          eleData["checked"] = ele.checked;
          this.setSelectedLabel('.filter-sdg', '#sdg-selected-label', 'Select SDGs');
          return eleData;
        });
        const isAll = data.every((d) => d.checked);
        $("#sdg_0").prop("checked", isAll);
        this.setSelectedLabel('.filter-sdg', '#sdg-selected-label', 'Select SDGs');
      };
      const onAllChange = (env) => {
        const isAll = env.target.checked;
        $(".filter-sdg").prop("checked", isAll);
      };
      $(".filter-sdg").on("change", onChange);
      $("#sdg_0").on("change", onAllChange);
    }
  }

  generateCRPSFilter() {
    if (this.pi2020FilterData.crops) {
      this.crps = clone(this.pi2020FilterData.crps);
      const crpsHTML = this.crps
        .map((crp, index) => {
          const selectAll =
            index != 0
              ? ""
              : `
            <li>
                <label for="crp_0" class="msCheck-label"> Select All
                    <input type="checkbox" class="all-filter-crp" id="crp_0" data-label="All CROPS" data-value="0" checked="checked">
                    <span class="msCheck-checkmark"></span>
                </label>
            </li>
        `;
          const htmlData = `
                ${selectAll}
                <li>
                    <label for="crp_${crp.crp_id}" class="msCheck-label"> ${crp.crp_name}
                        <input type="checkbox" class="filter-crp" id="crp_${crp.crp_id}" data-label="${crp.crp_name}" data-value="${crp.crp_id}" checked="checked">
                        <span class="msCheck-checkmark"></span>
                    </label>
                </li>
                `;
          return htmlData;
        })
        .join("\n");
      $("#crp-filter-options").html(crpsHTML);

      const onChange = (env) => {
        const data = $.map($(".filter-crp"), (ele) => {
          const eleData = clone($(ele).data());
          eleData["checked"] = ele.checked;
          this.setSelectedLabel('.filter-crp', '#crp-selected-label', 'Select CRPs');
          return eleData;
        });
        const isAll = data.every((d) => d.checked);
        this.setSelectedLabel('.filter-crp', '#crp-selected-label', 'Select CRPs');
        $("#crp_0").prop("checked", isAll);
      };
      const onAllChange = (env) => {
        const isAll = env.target.checked;
        $(".filter-crp").prop("checked", isAll);
      };
      $(".filter-crp").on("change", onChange);
      $("#crp_0").on("change", onAllChange);
    }
  }

  generateReasearchProgramsFilter() {
    if (this.pi2020FilterData.crops) {
      this.reasearchPrograms = clone(this.pi2020FilterData.reasearchprograms);
      const reasearchProgramHTML = this.reasearchPrograms
        .map((reasearchProgram, index) => {
          const selectAll =
            index != 0
              ? ""
              : `
            <li>
                <label for="reasearchPrograms_0" class="msCheck-label"> Select All
                    <input type="checkbox" class="all-filter-country" id="reasearchPrograms_0" data-label="All Reasearch Programs" data-value="0" checked="checked">
                    <span class="msCheck-checkmark"></span>
                </label>
            </li>
        `;
          const htmlData = `
                ${selectAll}
                <li>
                    <label for="reasearchProgram_${reasearchProgram.rp_id}" class="msCheck-label"> ${reasearchProgram.rp_name}
                        <input type="checkbox" class="filter-reasearchProgram" id="reasearchProgram_${reasearchProgram.rp_id}" data-label="${reasearchProgram.rp_name}" data-value="${reasearchProgram.rp_id}" checked="checked">
                        <span class="msCheck-checkmark"></span>
                    </label>
                </li>
                `;
          return htmlData;
        })
        .join("\n");
      $("#reasearchProgramHTML-filter-options").html(reasearchProgramHTML);

      const onChange = (env) => {
        const data = $.map($(".filter-reasearchProgram"), (ele) => {
          const eleData = clone($(ele).data());
          eleData["checked"] = ele.checked;
          return eleData;
        });
        const isAll = data.every((d) => d.checked);
        $("#reasearchPrograms_0").prop("checked", isAll);
        this.setSelectedLabel('.filter-reasearchProgram', '#resarch-selected-label', 'Select Reasearch Program');
      };
      const onAllChange = (env) => {
        const isAll = env.target.checked;
        $(".filter-reasearchProgram").prop("checked", isAll);
        this.setSelectedLabel('.filter-reasearchProgram', '#resarch-selected-label', 'Select Reasearch Program');
      };
      $(".filter-reasearchProgram").on("change", onChange);
      $("#reasearchPrograms_0").on("change", onAllChange);
    }
  }

  getFilteredData() {
     const result = {};
     result.countries = $.map($('.filter-country:checked'), (ele) => $(ele).data().value);
     result.crops = $.map($('.filter-crop:checked'), (ele) => $(ele).data().value);
     result.sdgs = $.map($('.filter-sdg:checked'), (ele) => $(ele).data().value);
     result.crps = $.map($('.filter-crp:checked'), (ele) => $(ele).data().value);
     result.rps = $.map($('.filter-reasearchProgram:checked'), (ele) => $(ele).data().value);

     const yearType = $('[name="years-filter"]:checked').prop('id');

     if (yearType == 'msSingle') {
        result.years = [$('#msSingle_year').find(".active").find('.item').data()?.value];
     } else if (yearType == 'msMultiple') {
        result.years = $.map($('.filter-year:checked'), (ele) => $(ele).data().value);
     } else if (yearType == 'msRange') {
        const beginYear = Number($('#msRange_year1').find(".active").find('.item').data()?.label);
        const endYear = Number($('#msRange_year').find(".active").find('.item').data()?.label);
        if (beginYear && endYear) {
            if (beginYear == endYear) {
                result.years = [];
            } else if (beginYear < endYear) {
                const years = [];
                for(let i = beginYear; i <= endYear; i++) {
                    years.push(i);
                }
                result.years = this.years.filter(d => years.includes(d.year)).map(d => d.year_id);
            }
        }
     }
     return result;

  }

  setSelectedLabel(inSelector, outSelector, emptyText) {
      const allData = $.map($(inSelector), (ele) => {
         const result =  $(ele).data();
         result.checked = ele.checked;
         return result;
        });
      const isAll = allData.every(d => d.checked);
      if (isAll) {
        $(outSelector).html('All Selected');
      } else {
        const someSelected = allData.some(d => d.checked);
        if (someSelected) {
            const selectedOnes = allData.filter(d => d.checked);
            if (selectedOnes.length == 1) {
                $(outSelector).html(selectedOnes[0].label);
            } else {
                $(outSelector).html(`${selectedOnes[0].label}, + ${selectedOnes.length - 1}`);

            }
        } else {
            $(outSelector).html(emptyText);
        }
      }
  }

  setSearchAction() {
    $('#filter-search-btn').on('click', (env) => {
      this.getTabData();
    })
  }

  get dataViewYears () {
    $('.yearWiseChart').hide();
    $('.non-yearwisecharts').show();
    if ($('[name="years-filter"]:checked').prop("id") == 'msMultiple') {
      const dataView = $('[name="data-radio"]:checked').val();
      if (dataView == 'comparison') {
      const filteredData = this.getFilteredData();
      $('.yearWiseChart').show();
      $('.non-yearwisecharts').hide();
      return this.years.filter(d => filteredData.years.includes(parseInt(d.year_id)));
      } 
    }
    return this.years;
  }

}
