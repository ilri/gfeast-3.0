<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.css">
<style type="text/css">
	.dropdown-menu {
		width: auto !important;
	}
</style>

<!-- Tabular Data -->
<div class="card mt-20">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<div class="card p-10">
					<div class="text-right">
						<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="exportToExcel('table', '<?php echo $filename; ?>')">
							Export Table Data
						</a>
					</div>
					
					<div class="exportContainer hidden"></div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover m-0">
							<thead>
								<tr>
									<th>Sl.No.</th>
									<?php if($filename == 'Plot Registration Data') { ?>
									<th>Plot number</th>
									<?php } ?>
									<th>Images</th>
									<?php foreach ($fields as $key => $value) { ?>
									<th><?php echo $value['label']; ?></th>
									<?php } ?>
									<th>Uploaded By</th>
									<th>Uploaded Datetime</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<div class="loadingText"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/table_doublescroller/jquery.doubleScroll.js"></script>
<!-- Page Script -->
<script type="text/javascript">
	var startdate, enddate;
	
	$(function(){
		$('.table-responsive').doubleScroll({
			resetOnWindowResize:true
		});

		$("[name='division[]'], [name='circle[]'], [name='village[]']").selectpicker({
			actionsBox: true,
			liveSearch: true
		});

		var start = moment().subtract(1, 'years');
		var end = moment();

		function cb(start, end) {
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			startdate = start;
			enddate = end;
		}

		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);

		cb(start, end);
	});

	$('body').on('change', '[name="division[]"]', function(event){
		var elem = $(this);
		$("[name='circle[]']").val(null).trigger('change');

		if(elem.val().length === 0) return false;

		$('.get_data').html('Please Wait...');
		$('.get_data').prop('disabled', true);
		$.ajax({
			url : '<?php echo base_url(); ?>helper/all_circles',
			data : {division: elem.val()},
			type : "POST",
			dataType : "JSON",
			error:function() {
				$('.get_data').html('Filter Data');
				$('.get_data').prop('disabled', false);
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success:function(response) {
				$('.get_data').html('Filter Data');
				$('.get_data').prop('disabled', false);

				var HTML = ``;
				for(var circle of response.circles) {
					HTML += `<option value="${circle.CIR_CODE}">${circle.CIR_NAME}</option>`
				}
				$("[name='circle[]']").html(HTML).selectpicker('refresh');
			}
		});
	}).on('change', '[name="circle[]"]', function(event){
		var elem = $(this);
		$("[name='village[]']").val(null).trigger('change');

		if(elem.val().length === 0) return false;

		$('.get_data').html('Please Wait...');
		$('.get_data').prop('disabled', true);
		$.ajax({
			url : '<?php echo base_url(); ?>helper/all_villages',
			data : {circle: elem.val()},
			type : "POST",
			dataType : "JSON",
			error:function() {
				$('.get_data').html('Filter Data');
				$('.get_data').prop('disabled', false);
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success:function(response) {
				$('.get_data').html('Filter Data');
				$('.get_data').prop('disabled', false);

				var HTML = ``;
				for(var village of response.villages) {
					HTML += `<option value="${village.VILLAGE_CODE}">${village.VNAME}</option>`
				}
				$("[name='village[]']").html(HTML).selectpicker('refresh');
			}
		});
	});

	$('body').on('click', '.get_data', function(){
		$('.table').find('tbody').empty();
		
		var start_date = startdate;
		var end_date = enddate;

		var query_data = {
			division : $("[name='division[]']").val(),
			circle : $("[name='circle[]']").val(),
			village : $("[name='village[]']").val(),
			start_date : formatDate(start_date),
			end_date : formatDate(end_date)
		};

		loadingData = false;
		get_data(query_data);
	});

	var position = $(window).scrollTop(),
	loadingData = false;
	
	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if(scroll > position) {
			var start_date = startdate;
			var end_date = enddate;
			var last_id = $('.table tr:last').data('id');

			var query_data = {
				division : $("[name='division[]']").val(),
				circle : $("[name='circle[]']").val(),
				village : $("[name='village[]']").val(),
				start_date : formatDate(start_date),
				end_date : formatDate(end_date),
				last_id : last_id
			};

			get_data(query_data);
		}
		position = scroll;
	});

	function formatDate(date) {
		var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;

		return [year, month, day].join('-');
	}

	function get_data(query_data){
		// Check if already loading data
		if(loadingData) return false;
		// alert(url);
		// Continue otherwise		
		loadingData = true;
		$('.get_data').html('Please Wait...');
		$('.get_data').prop('disabled', true);
		var role = <?php echo $this->session->userdata('role'); ?>;
		$('.loadingText').html(`<h6 class="text-info text-center m-1">Please Wait... Getting Data...</h6>`);
		
		$.ajax({
			url : url,
			data : query_data,
			type : "POST",
			dataType : "JSON",
			error:function(){
				loadingData = false;
				$('.loadingText').empty();
				$('.get_data').html('Filter Data');
				$('.get_data').prop('disabled', false);
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success:function(response){
				loadingData = false;
				$('.loadingText').empty();
				$('.get_data').html('Filter Data');
				$('.get_data').prop('disabled', false);
				if(response.status > 0) {
					var HTML = ``;
					if(typeof query_data.last_id == 'undefined'){
						var i = 1;
					}else{
						var i = $('.table tr:last').index() + 2;
					}
					if(response.survey_data.length == 0) return false;

					response.survey_data.forEach(function(data, index){
						HTML += `<tr data-id="`+data[primary_key]+`">`;
						HTML += `<td>`+i+`</td>`;
						if(primary_key == 'plot_id') {
						HTML += `<td><a href="<?php echo base_url(); ?>dashboard/plot_info/`+data.plot_id+`" target="_blank" style="color: blue;">`+data.plot_number+`</a></td>`;
						}
						HTML += `<td>`;
						if(data.images.length > 0){
							data.images.forEach(function(img, ind){
								HTML += `<img src="<?php echo base_url(); ?>/uploads/survey/`+img.file_name+`" style="height: 100px; width: 100px; margin-bottom: 10px;">`;
							});
						}else{
							HTML += `No image uploaded`;
						}
						HTML += `</td>`;
						
						response.fields.forEach(function(field, index){
							var fieldname = "field_"+field.field_id;

							HTML += `<td class="`+field.field_id+`">
							<div data-field='`+field.field_id+`' data-id='`+data.id+`'>`;
								// if(field.type == 'text' || field.type == 'textarea'
								// || field.type == 'number' || field.type == 'scanner'
								// || field.type == 'lkp_gender' || field.type == 'select'
								// || field.type == 'radio-group' || field.type == 'checkbox-group') {
								// 	HTML += `<a href='javascript:void(0)' title='Edit Data' class='pl-1 float-right edit'>
								// 		<i class='fa fa-edit' style='line-height:1.5;'></i>
								// 	</a>`;
								// }
								HTML += `<span class="field_value">`;
								switch(field.type){
									case 'lkp_gender':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_gender.forEach(function(value, index){
											if(value.GENDER_CODE == data[fieldname]){
												HTML += value.GENDER_DESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_title':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_title.forEach(function(value, index){
											if(value.title_id == data[fieldname]){
												HTML += value.title_name;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_circle':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_circle.forEach(function(value, index){
											if(value.CIR_CODE == data[fieldname]){
												HTML += value.CIR_NAME;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_division':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_division.forEach(function(value, index){
											if(value.DIV_CODE == data[fieldname]){
												HTML += value.DIV_NAME;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_village':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_village.forEach(function(value, index){
											if(value.VILLAGE_CODE == data[fieldname]){
												HTML += value.VNAME;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_category':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_category.forEach(function(value, index){
											if(value.CATEG == data[fieldname]){
												HTML += value.Category_Name;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_crop_type':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_crop_type.forEach(function(value, index){
											if(value.PLANT_TYPE == data[fieldname]){
												HTML += value.PLANTDESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_crushing_season':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_crushing_season.forEach(function(value, index){
											if(value.ZYEAR_ID == data[fieldname]){
												HTML += value.ZYEAR;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_irrigation_source':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_irrigation_source.forEach(function(value, index){
											if(value.IRR_SOURCE == data[fieldname]){
												HTML += value.IRRSDESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_irrogation_method':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_irrogation_method.forEach(function(value, index){
											if(value.IRR_METHOD == data[fieldname]){
												HTML += value.IRRDESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_maturity_type':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_maturity_type.forEach(function(value, index){
											if(value.MATURITY_ID == data[fieldname]){
												HTML += value.MATURITY;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_ownership':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_ownership.forEach(function(value, index){
											if(value.OWNERSHIP == data[fieldname]){
												HTML += value.Description;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_plantation_method':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_plantation_method.forEach(function(value, index){
											if(value.PLA_METHOD == data[fieldname]){
												HTML += value.PLADESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_planting_season':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_planting_season.forEach(function(value, index){
											if(value.PLANTSEA_ID == data[fieldname]){
												HTML += value.PLANTSEA;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_plot_type':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_plot_type.forEach(function(value, index){
											if(value.PLOT_TYPE == data[fieldname]){
												HTML += value.PLOTDESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_soil_type':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_soil_type.forEach(function(value, index){
											if(value.SOIL_TYPE_CODE == data[fieldname]){
												HTML += value.SOIL_TYPE_DESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_spacing_code':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_spacing_code.forEach(function(value, index){
											if(value.SPACE_CODE == data[fieldname]){
												HTML += value.SPACE_NAME;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									case 'lkp_variety':
									if(typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == ''){
										HTML += `N/A`;
									}else{
										var match = false;
										response.lkp_variety.forEach(function(value, index){
											if(value.VARIETY == data[fieldname]){
												HTML += value.VARDESC;
												match = true;
											}
										});
										if(!match) HTML += `N/A`;
									}
									break;

									default:
									HTML += (typeof data[fieldname] === 'undefined' || data[fieldname] == null || data[fieldname] == '') ? "N/A" : data[fieldname];
									break;
								}
								HTML += `</span>
							</div></td>`;
						});

						HTML += `<td>`+data.username+`</td>
						<td>`+data.added_date+`</td>
						</tr>`;

						i++;
					});

					if(typeof query_data.last_id == 'undefined'){
						$('.table tbody').html(HTML);
					}else{
						$('.table tbody').append(HTML);
					}
					$('.table-responsive').doubleScroll({
						resetOnWindowResize:true
					});
				}
			}
		});
	}

	function exportToExcel(tableID, filename = ''){
		var downloadurl;
		var dataFileType = 'application/vnd.ms-excel';
		
		//Clone table to new div
		//Keep the classname as id
		//Remove unnecessary columns
		var clone = $('.'+tableID).clone();
		clone.attr('id', tableID);
		clone.removeClass(tableID);
		clone.find('.unwantedCol').remove();
		$('.exportContainer').html(clone);

		var tableSelect = document.getElementById(tableID);
		var tableHTMLData = tableSelect.outerHTML.replace(/ /g, '%20');
		filename = filename?filename+'.xls':'export_excel_data.xls';

		// Create download link element
		downloadurl = document.createElement("a");

		document.body.appendChild(downloadurl);

		if(navigator.msSaveOrOpenBlob){
			var blob = new Blob(['\ufeff', tableHTMLData], {
				type: dataFileType
			});
			navigator.msSaveOrOpenBlob( blob, filename);
		}else{
			// Create a link to the file
			downloadurl.href = 'data:' + dataFileType + ', ' + tableHTMLData;

			// Setting the file name
			downloadurl.download = filename;

			//triggering the function
			downloadurl.click();
		}
	}
</script>