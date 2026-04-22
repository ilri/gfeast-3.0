class DataIndicators {
  constructor() {
		$("#table-download").hide();
	this.onFilterDataChange();
	this.onNationPerformanceChange();
    this.getData();
		this.downloadTable();
  }

  getData() {
    $("#pageTitle").html("");
		$("#table-download").hide();
    $("#form-tableHead").html("");
    $("#form-tableBody").html("");
	$('#raido-content').hide();
    const filterData = this.getFilterData();
    if (Array.isArray(filterData)) {
		$('#raido-content').show();
		$(`[name="n-performance"][value="${filterData[0]}"]`).prop('checked', true);
		$(`[name="n-performance"]`).trigger('change');
    } else {
     this.getApiData(filterData)
    }
  }

	downloadTable() {
		$("#table-download").on("click", () => {
			$("#table-1>div>div>table").table2csv({
			  file_name: $("#pageTitle").html() + '.csv',
			  header_body_space: 0,
			});
		})
	}

  getApiData(filterData) {
	post(`pi_2020/get_activitydata/9/${filterData}`, {}, true).then(
        (response) => {
          if (response?.status == 1) {
            this.responseData = response;
            this.setDataToHtml();
          }
        }
      );
  }

  setDataToHtml() {
    this.formDetail = this.responseData.form_details;
    if (this.formDetail) {
      $("#pageTitle").html(this.formDetail.title);
			$("#table-download").show();
    }
    if (this.responseData.fields) {
      this.fields = this.responseData.fields;
      this.setFieldsData();
    }
    if (this.responseData.survey_data) {
      this.rowData = this.responseData.survey_data.map((d) =>
        JSON.parse(d.form_data)
      );
      this.setRowData();
    }
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
	$('.table-responsive').doubleScroll({resetOnWindowResize: true});
  }

  setRowData() {
    const tableBody = this.rowData
      .map((data) => {
        const rows = this.fields
          .map((field) => {
            const cellVal = (data?.["field_" + field.field_id] ?? '')?.trim()	;
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
	$('[name="years-filter"]').on('change', () => {
		this.getData();
	})
  }
  onNationPerformanceChange() {
	$(`[name="n-performance"]`).on('change', () => {
		const val = $('[name="n-performance"]:checked').val();
		this.getApiData(val);
	})
  }
}
const di = new DataIndicators();
