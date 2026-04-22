class Gallery {
  constructor() {
    this.onFilterDataChange();
    this.onNationPerformanceChange();
    this.getData();
    $("#noImageFound").hide();
    this.fieldsMap = new Map();
    this.fieldsMap.set("370", ["field_11612", "field_11613", "field_11615"]);
    this.fieldsMap.set("372", ["field_9230", "field_9231", "field_9232", "field_9233"]);
    this.fieldsMap.set("373", ["field_9143", "field_9145"]);
    this.fieldsMap.set("374", ["field_9166", "field_9168"]);
    this.fieldsMap.set("375", ["field_9187", "field_9190"]);
    this.fieldsMap.set("376", ["field_9208", "field_9224"]);
    this.fieldsMap.set("462", ["field_11713", "field_11729"]);
    this.fieldsMap.set("463", ["field_11732", "field_11734"]);
    this.fieldsMap.set("464", ["field_11751", "field_11758"]);
  }

  getData() {
    $("#pageTitle").html("");
    $("#form-tableHead").html("");
    $("#form-tableBody").html("");
    $("#raido-content").hide();
    const filterData = this.getFilterData();
    if (Array.isArray(filterData)) {
      $("#raido-content").show();
      $(`[name="n-performance"][value="${filterData[0]}"]`).prop(
        "checked",
        true
      );
      $(`[name="n-performance"]`).trigger("change");
    } else {
      this.getApiData(filterData);
    }
  }

  getApiData(filterData) {
    $("#noImageFound").hide();
    $("#galist").html("");
    this.apiFilter = filterData;
    post(`pi_2020/get_imagedata/9/${filterData}`, {}, true).then((response) => {
      if (response) {
        this.responseData = response;
        this.setDataToHtml();
      }
    });
  }

  setDataToHtml() {
    this.data = this.responseData.data;
    if (this.data?.length) {
      const galImgs = this.data
        .map((d) => {
          if (d.form_data) {
            d.form_data = JSON.parse(d.form_data);
          }
          debugger;
          if (this.fieldsMap.get(this.apiFilter)) {
            const fields = this.fieldsMap.get(this.apiFilter);
            const contents = fields.map((e) => d.form_data[e]).join(", ");
            return `<li  data-content="${contents}" tabindex="0" data-src="${imgBaseURI}${d.image}" style="background:url('${imgBaseURI}${d.image}') center center;">
        <img src="${imgBaseURI}${d.image}" alt="${contents}" style="width: 100%; height: 200px" />
    </li>`;
          }

          return `<li tabindex="0" data-src="${imgBaseURI}${d.image}" style="background:url('${imgBaseURI}${d.image}') center center;">
        <img src="${imgBaseURI}${d.image}" style="width: 100%; height: 200px" />
    </li>`;
        })
        .join("\n");
      $("#galist").html(`
        <ul id="lightgallery">
        ${galImgs}
        </ul>
        `);

      setTimeout(() => {
        $("#lightgallery").lightGallery({
          pager: true,
        });
        $("#lightgallery li").popover({
          trigger: "hover",
          container: "body",
          placement: "bottom",
        });
      }, 100);
    } else {
      $("#noImageFound").show();
    }
    //   if (this.responseData.fields) {
    //     this.fields = this.responseData.fields;
    //     this.setFieldsData();
    //   }
    //   if (this.responseData.survey_data) {
    //     this.rowData = this.responseData.survey_data.map((d) =>
    //       JSON.parse(d.form_data)
    //     );
    //     this.setRowData();
    //   }
  }

  setFieldsData() {
    const ths = this.fields
      .map((d) => {
        return `<th nowrap>${d.label}</th>`;
      })
      .join("\n");
    const tableHead = `
          <tr class="bg-table-1">
              ${ths}
          </tr>
          `;
    $("#form-tableHead").html(tableHead);
    $(".table-responsive").doubleScroll({ resetOnWindowResize: true });
  }

  setRowData() {
    const tableBody = this.rowData
      .map((data) => {
        const rows = this.fields
          .map((field) => {
            const cellVal = (data["field_" + field.field_id] ?? "")?.trim();
            return `<td nowrap>${cellVal}</td>`;
          })
          .join("\n");
        return `<tr>
          ${rows}
          </tr>`;
      })
      .join("\n");
    $("#form-tableBody").html(tableBody);
  }

  getFilterData() {
    const ele = $('[name="years-filter"]:checked');
    if (ele.data("isarray")) {
      return ele.val().split(",");
    } else {
      return ele.val();
    }
  }

  onFilterDataChange() {
    $('[name="years-filter"]').on("change", () => {
      this.getData();
    });
  }
  onNationPerformanceChange() {
    $(`[name="n-performance"]`).on("change", () => {
      const val = $('[name="n-performance"]:checked').val();
      this.getApiData(val);
    });
  }
}
const gallery = new Gallery();
