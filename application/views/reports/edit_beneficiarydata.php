<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet/css/leaflet.css" />

<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/MarkerCluster.Default.css" />

<script src="<?php echo base_url(); ?>includeout/leaflet/js/leaflet.js"></script>
<script src="<?php echo base_url(); ?>includeout/leaflet_markercluster/dist/leaflet.markercluster.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<style type="text/css">
	td, th{
		text-align: center;
	}
	td .form-group{
		margin-bottom: 10px;
	}
</style>

<!-- Edit Data Modal -->
<div class="modal fade" id="reasonModal" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Provide a reason for the edit</h4>
			</div>
			
			<?php echo form_open('', array('id'=>'reasonForm')); ?>
			<div class="modal-body">
				<div class="form-group">
					<label for="reason">Reason</label> <span class="text-danger">*</span>
					<textarea id="reason" placeholder="Provide some reason..." class="form-control" name="reason" rows="3" style="resize:vertical;"></textarea>
					<span class="reason error text-danger"></span>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success">Save Data</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body">
			<div class="row">
				<div class="col-md-4">
					<h4 style="font-weight: bold;">Beneficiary Data</h4>
				</div>
				<div class="col-md-2">
					<select class="form-control" name="district[]" multiple title="Select district">
						<?php foreach ($district_list as $key => $district) { ?>
							<option value="<?php echo $district['district_id']; ?>"><?php echo $district['district_name']; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-2">
					<select class="form-control" name="users[]" multiple title="Select user">
						<?php foreach ($user_list as $key => $user) { ?>
							<option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3">
					<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
						<i class="fa fa-calendar"></i>&nbsp;
						<span></span> <i class="fa fa-caret-down"></i>
					</div>
				</div>
				
				<div class="col-md-1">
					<button class="btn btn-sm btn-success get_data pull-right">Filter Data</button>
				</div>
			</div>
			
			<div class="row mt-30">
				<div class="col-md-12 mt-10">
					<div class="card p-10">
						<div class="text-right">
							<button class="btn btn-sm btn-success saveAll hidden" data-toggle="modal" data-target="#reasonModal">Save All Data</button>
						</div>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Sl.no</th>
										<th>Unique Id</th>
										<th>Images</th>
										<?php foreach ($fields as $key => $value) { ?>
											<th><?php echo $value['label']; ?></th>
										<?php } ?>
										<?php if($check_group_field > 0){ ?>
											<th>Group data</th>
										<?php } ?>
										<th>Location</th>
										
									</tr>
								</thead>
								<tbody id="beneficiary_data">
									<?php if(count($survey_data) > 0){
										foreach ($survey_data as $dkey => $data) { ?>
											<tr class="<?php echo $data['data_id'] ?>" data-id="<?php echo $data['id'] ?>">
												<?php $data_array = json_decode($data['form_data'], true); ?>
												<td><?php echo $dkey+1; ?></td>
												<td><?php echo $data['beneficiary_id']; ?></td>
												<td>
													<?php if(count($data['images']) > 0){
														foreach ($data['images'] as $key => $img) { ?>
															<img src="<?php echo base_url(); ?>uploads/survey/<?php echo $img['file_name']; ?>" style="height: 100px; width: 100px; margin-bottom: 10px;">
														<?php }
													}else{
														echo "No images found";
													} ?>
												</td>
												<?php foreach ($fields as $fkey => $field) {
													$column = "field_".$field['field_id']; ?>
													<td class="<?php echo $field['field_id']; ?>">
														<div data-field='<?php echo $field['field_id']; ?>' data-id='<?php echo $data['id']; ?>'>
															<?php if($field['type'] == 'text' || $field['type'] == 'textarea'
															|| $field['type'] == 'number' || $field['type'] == 'scanner'
															|| $field['type'] == 'lkp_gender' || $field['type'] == 'select'
															|| $field['type'] == 'radio-group' || $field['type'] == 'checkbox-group') {
																echo "<a href='javascript:void(0)' title='Edit Data' class='pl-1 float-right edit'>
																	<i class='fa fa-edit' style='line-height:1.5;'></i>
																</a>";
															} ?>
															<span class="field_value">
															<?php switch ($field['type']) {

																case 'lkp_partners':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($partners_list as $key => $partners) {
																			if($partners['partner_id'] == $data_array[$column]){
																				echo $partners['partner_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_centre':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($centre_list as $key => $centre) {
																			if($centre['centre_id'] == $data_array[$column]){
																				echo $centre['centre_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_batch':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($batch_list as $key => $batch) {
																			if($batch['batch_id'] == $data_array[$column]){
																				echo $batch['batch_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_trainee':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($trainee_list as $key => $trainee) {
																			if($trainee['trainee_id'] == $data_array[$column]){
																				echo $trainee['trainee_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_age':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($age_list as $key => $age) {
																			if($age['id'] == $data_array[$column]){
																				echo $age['age'];
																			}
																		}
																	}
																	break;

																case 'lkp_state':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($state_list as $key => $state) {
																			if($state['state_id'] == $data_array[$column]){
																				echo $state['state_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_district':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($district_list as $key => $district) {
																			if($district['district_id'] == $data_array[$column]){
																				echo $district['district_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_block':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($block_list as $key => $block) {
																			if($block['block_id'] == $data_array[$column]){
																				echo $block['block_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_village':
																	if($data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		foreach ($village_list as $key => $village) {
																			if($village['village_id'] == $data_array[$column]){
																				echo $village['village_name'];
																			}
																		}
																	}
																	break;

																case 'lkp_yesno':
																	if(!isset($data_array[$column]) || $data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		if($data_array[$column] == 1){
																			echo "Yes";
																		}else{
																			echo "No";
																		}
																	}
																	break;

																case 'lkp_gender':
																	if(!isset($data_array[$column]) || $data_array[$column] == NULL || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		if($data_array[$column] == 1){
																			echo "Male";
																		}else{
																			echo "Female";
																		}
																	}
																	break;
																
																default:
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		} else {
																			echo $data_array[$column];
																		}
																	} else {
																		echo "N/A";
																	}
																	break;
															} ?>
															</span>
														</div>
													</td>
												<?php } ?>
												<?php if($check_group_field > 0){ ?>
													<td><a href="<?php echo base_url(); ?>reports/edit_groupdata_info/<?php echo ($this->uri->segment(2) == 'view_dashboard') ? 1 : $this->uri->segment(3); ?>/<?php echo $data['data_id']; ?>" target="_blank">Edit data</a></td>
												<?php } ?>
												<td>
													<?php if($data['location'] == NULL){
														echo "N/A";
													}else{
														echo ($data['location']['lat'] == NULL) ? "N/A" : $data['location']['lat'];
														echo ", ";
														echo ($data['location']['lng'] == NULL) ? "N/A" : $data['location']['lng'];
													} ?>
												</td>
												
											</tr>
										<?php }
									}else{ ?>
										<tr>
											<td colspan="<?php echo count($fields)+3 ?>">No data found</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col-md-12 mt-10" style="margin-bottom:60px;">
					<button type="button" class="btn btn-success pull-right" id="loadm">Load More Data</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>includeout/table_doublescroller/jquery.doubleScroll.js"></script>
<script src="<?php echo base_url(); ?>includeout/bootstrap-select/bootstrap-select.js"></script>

<script type="text/javascript">
	var startdate, enddate;
	
	$(function(){
		$('.table-responsive').doubleScroll({
			resetOnWindowResize:true
		});

		$("[name='district[]'], [name='users[]']").selectpicker({
			actionsBox: true,
			liveSearch: true
		});

		var intiall_date = "05/10/2020";
		var start = moment(intiall_date);
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

		$('.get_data').on('click', function(){
			var district = $('select[name="district[]"]').val();
			var user_ids = $('select[name="users[]"]').val();
			var start_date = startdate;
			var end_date = enddate;

			var query_data = {
				district : district,
				user_ids : user_ids,
				start_date : formatDate(start_date),
				end_date : formatDate(end_date)
			};

			get_data(query_data);
		});

		$('#loadm').on('click', function(){
			var district = $('select[name="district[]"]').val();
			var user_ids = $('select[name="users[]"]').val();
			var start_date = startdate;
			var end_date = enddate;
			var last_id = $('#beneficiary_data tr:last').data('id');

			var query_data = {
				district : district,
				user_ids : user_ids,
				start_date : formatDate(start_date),
				end_date : formatDate(end_date),
				last_id : last_id
			};

			get_data(query_data);
		});
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
		var loadingText = '<tr class="loading"><td colspan="20">Please Wait... Getting Data...</td></tr>';
		if($('#beneficiary_data').html().length === 0) {
			$('#beneficiary_data').html(loadingText);
		} else {
			$('#beneficiary_data').append(loadingText);
		}
		$('#loadm').removeClass('hidden');
		$('#loadm, .get_data').html('Please Wait...');
		$('#loadm, .get_data').prop('disabled', true);
		var role = <?php echo $this->session->userdata('role'); ?>;
		
		$.ajax({
			url : "<?php echo base_url(); ?>/dashboard/view_dashboard_filter",
			data : query_data,
			type : "POST",
			dataType : "JSON",
			error:function(){
				$('#loadm').html('Load More Data');
				$('.get_data').html('Filter Data');
				$('#loadm, .get_data').prop('disabled', false);
				$('#beneficiary_data').find('.loading').remove();
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success:function(response){
				$('#loadm').html('Load More Data');
				$('.get_data').html('Filter Data');
				$('#loadm, .get_data').prop('disabled', false);
				$('#beneficiary_data').find('.loading').remove();
				if(response.status == 0){

				}else{
					var HTML = ``;
					if(typeof query_data.last_id == 'undefined'){
						var i = 1;
					}else{
						var i = $('#beneficiary_data tr:last').index() + 1;
					}
					if(response.survey_data.length == 0) {
						$('#loadm').addClass('hidden');
						$.toast({
							heading: 'End of Data!',
							text: 'No more data found.',
							icon: 'info'
						});
						return false;
					}

					response.survey_data.forEach(function(data, index){
						HTML += `<tr data-id="`+data.id+`">`;
						HTML += `<td>`+i+`</td>
						<td>`+(data.beneficiary_id == null || data.beneficiary_id == '' ? "N/A" : data.beneficiary_id)+`</td>
						<td>`;
						if(data.images.length > 0){
							data.images.forEach(function(img, ind){
								HTML += `<img src="<?php echo base_url(); ?>uploads/survey/`+img.file_name+`" style="height: 100px; width: 100px; margin-bottom: 10px;">`;
							});
						}else{
							HTML += `No images found`;
						}
						HTML += `</td>`;

						var jsondata = jQuery.parseJSON(data.form_data);
						response.fields.forEach(function(field, index){
							var fieldname = "field_"+field.field_id;

							HTML += `<td class="`+field.field_id+`">
							<div data-field='`+field.field_id+`' data-id='`+data.id+`'>`;
								if(field.type == 'text' || field.type == 'textarea'
								|| field.type == 'number' || field.type == 'scanner'
								|| field.type == 'lkp_gender' || field.type == 'select'
								|| field.type == 'radio-group' || field.type == 'checkbox-group') {
									HTML += `<a href='javascript:void(0)' title='Edit Data' class='pl-1 float-right edit'>
										<i class='fa fa-edit' style='line-height:1.5;'></i>
									</a>`;
								}
								HTML += `<span class="field_value">`;
								switch(field.type){
									case 'lkp_district':
									if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
										HTML += `N/A`;
									}else{
										response.district_list.forEach(function(dist, index){
											if(dist.district_id == jsondata[fieldname]){
												HTML += dist.district_name;
											}
										});
									}
									break;

									case 'lkp_block':
									if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
										HTML += `N/A`;
									}else{
										response.block_list.forEach(function(block, index){
											if(block.block_id == jsondata[fieldname]){
												HTML += block.block_name;
											}
										});
									}
									break;

									case 'lkp_village':
									if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
										HTML += `N/A`;
									}else{
										response.village_list.forEach(function(village, index){
											if(village.village_id == jsondata[fieldname]){
												HTML += village.village_name;
											}
										});
									}
									break;

									default:
									HTML += (typeof jsondata[fieldname] === 'undefined' ? "N/A" : jsondata[fieldname]);
									break;
								}
								HTML += `</span>
							</div></td>`;
						});

						if(response.check_group_field > 0) {
							HTML += `<td class="unwantedCol">
								<a href="<?php echo base_url(); ?>reports/edit_groupdata_info/<?php echo ($this->uri->segment(2) == 'view_dashboard') ? 1 : $this->uri->segment(3); ?>/`+data.data_id+`" target="_blank">
									Edit data
								</a>
							</td>`;
						}

						if(data.location == null){
							HTML += `<td>N/A</td>`;
						}else{
							var lat = (data.location.lat == null ? "N/A" : data.location.lat);
							var lng = (data.location.lng == null ? "N/A" : data.location.lng);

							HTML += `<td>`+lat+` `+lng+`</td>`;
						}
						HTML += `</tr>`;

						i++;
					});

					if(typeof query_data.last_id == 'undefined'){
						$('#beneficiary_data').html(HTML);
					}else{
						$('#beneficiary_data').append(HTML);
					}
				}
			}
		});
	}

	$('body').tooltip({
		selector: '[data-toggle="tooltip"]'
	});

	// Define global variable ajaxData
	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

	//Handle edit click of every column
	$('body').on('click', '.edit', function(event) {
		var elem = $(this),
		div = elem.parent();
		
		div.addClass('hidden');

		//Call function to create form
		div.after('<form class="editForm" class="text-left" style="min-width:200px;">\
			<img src="<?php echo base_url(); ?>include/app-assets/images/measure_loader.svg" alt="Loading Data... Please Wait..." height="40" width="40">\
			<h6 class="text-center">Please Wait...</h6>\
		</form>');

		//Show/Hide save all button
		showHideSaveAll();
		//Call function to fill the form
		fillEditForm(div);
	}).on('click', '.cancelEdit', function(event) {
		var elem = $(this),
		form = elem.closest('form');
		
		form.prev().find('.field_value').html(form.data('field_value'));
		form.prev().removeClass('hidden');
		form.remove();

		//Show/Hide save all button
		showHideSaveAll();
	});

	//Reset reason form
	$('#reasonModal').on('shown.bs.modal', function () {
		$('#reasonForm')[0].reset();
	});

	//Hamdle reasonForm submit
	$('body').on('submit', '#reasonForm', function(event) {
		event.preventDefault();
		var elem = $(this),
		reason = elem.find('[name="reason"]').val();
		elem.find('.error').empty();

		if(reason.length === 0) {
			elem.find('.error.reason').html('Reason for editing data is mandatory.');
			return false;
		}

		$('body').find('.editForm').each(function(index) {
			var individualReason = $(this).find('[name="reason"]');
			if(individualReason.val().length === 0) individualReason.val(reason);
		});
		$('#reasonModal').modal('hide');
		$('.saveAll').prop('disabled', true);
		$('body').find('.editForm').trigger('submit');
	});

	//Handle edit click of every column
	$('body').on('submit', '.editForm', function(event) {
		event.preventDefault();
		var elem = $(this),
		id = elem.data('id'),
		field = elem.data('field');
		elem.find('.error').empty();

		//Validate fields
		var error = 0;
		if(elem.data('required') == 1) {
			switch(elem.data('type')) {
				case 'text':
				case 'number':
				case 'select':
				case 'scanner':
				case 'textarea':
					if(elem.find('.field_'+field).val().length === 0) {
						elem.find('.error.field_'+field).html('Field is mandatory.');
						error++;
					}
				break;

				case 'lkp_gender':
				case 'radio-group':
				case 'checkbox-group':
					if(elem.find('.field_'+field+':checked').length == 0) {
						elem.find('.error.field_'+field).html('Selection is mandatory.');
						error++;
					}
				break;
			}
		}
		if(elem.find('[name="reason"]').val().length === 0) {
			elem.find('.error.reason').html('Field is mandatory.');
			error++;
		}
		if(error > 0) return false;

		var formData = new FormData(elem[0]);
		formData.append('id', id);
		formData.append('field', field);

		elem.find('button').prop('disabled', true);
		elem.find('button[type="submit"]').html('Please wait...');
		$.ajax({
			url: '<?php echo base_url(); ?>reports/edit_beneficiary/',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			complete: function(data) {
				var csrfData = JSON.parse(data.responseText);
				ajaxData[csrfData.csrfName] = csrfData.csrfHash;
				if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
					$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
				}
			},
			error: function() {
				elem.find('button').prop('disabled', false);
				elem.find('button[type="submit"]').html('Save');
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success: function(data) {
				var data = JSON.parse(data);

				// If session error exists
				if(data.session_err == 1) {
					$.toast({
						heading: 'Session Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							elem.find('button').prop('disabled', false);
							elem.find('button[type="submit"]').html('Save');
						}
					});
				}

				if(data.status == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							elem.find('button').prop('disabled', false);
							elem.find('button[type="submit"]').html('Save');
							
							elem.data('field_value', data.field_value);
							elem.find('.cancelEdit').trigger('click');
						}
					});
				} else if(data.status == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							elem.find('button').prop('disabled', false);
							elem.find('button[type="submit"]').html('Save');
						}
					});
				}
			}
		});
	});

	//Function to show/hide save all button
	function showHideSaveAll() {
		if($('.table').find('.editForm').length > 1) {
			$('.saveAll').prop('disabled', false);
			$('.saveAll').removeClass('hidden');
		} else {
			$('.saveAll').addClass('hidden');
		}
	}

	//Function to fill form
	function fillEditForm(elem) {
		form = elem.next('form');
		
		//AJAX to get submitted data
		ajaxData['id'] = elem.data('id');
		ajaxData['field_id'] = elem.data('field');
		$.ajax({
			url: '<?php echo base_url(); ?>reports/get_details_for_edit/',
			data: ajaxData,
			type: 'POST',
			dataType: 'json',
			complete: function(data) {
				var csrfData = JSON.parse(data.responseText);
				ajaxData[csrfData.csrfName] = csrfData.csrfHash;
				if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
					$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
				}
			},
			error: function() {
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error',
					afterHidden: function () {
						form.remove();
						elem.removeClass('hidden');
					}
				});
			},
			success: function(data) {
				// If session error exists
				if(data.session_err == 1) {
					$.toast({
						heading: 'Session Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							form.remove();
							elem.removeClass('hidden');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							form.remove();
							elem.removeClass('hidden');
						}
					});
					return false;
				}

				form.data('id', elem.data('id'));
				form.data('type', data.field_details.type);
				form.data('field', elem.data('field'));
				if(data.field_details.required == 1) {
					form.data('required', 1);
				} else {
					form.data('required', 0);
				}
				
				var surveyData = JSON.parse(data.survey_data.form_data),
				fieldValue = surveyData['field_'+data.field_details.field_id] ? surveyData['field_'+data.field_details.field_id] : '';
				
				if(fieldValue.length === 0) form.data('field_value', 'N/A');
				else form.data('field_value', fieldValue);
				
				var formHTML = '<div class="form-group">\
					<label>'+data.field_details.label+'</label>';
					if(data.field_details.required == 1) {
						formHTML += '<font color="red">*</font>';
					}
					if(data.field_details.description) {
						formHTML += '<i data-toggle="tooltip" data-title="'+data.field_details.description+'" class="fa fa-question-circle ml-1" aria-hidden="true"></i>';
					}
					switch(data.field_details.type) {
						case 'text':
						case 'number':
						case 'scanner':
							formHTML += '<input type="'+data.field_details.subtype+'" name="field_'+data.field_details.field_id+'" class="'+data.field_details.className+' field_'+data.field_details.field_id+' input-sm" value="'+fieldValue+'">';
						break;

						case 'textarea':
							formHTML += '<textarea name="field_'+data.field_details.field_id+'" class="'+data.field_details.className+' field_'+data.field_details.field_id+' input-sm" ></textarea>';
						break;

						case 'select':
							var fieldValueArray = fieldValue.split('&#44;');
							if(data.field_details.multiple == 'true') {
							formHTML += '<select name="field_'+data.field_details.field_id+'[]" multiple class="form-control field_'+data.field_details.field_id+' input-sm">';
							} else {
							formHTML += '<select name="field_'+data.field_details.field_id+'" class="form-control field_'+data.field_details.field_id+' input-sm">';
							}
							data.field_details.options.forEach(function(option, index) {
								var selected = fieldValueArray.includes(option['value']) ? 'selected' : '';
								formHTML += '<option value="'+option['value']+'" '+selected+'>'+option['label']+'</option>';
							});
							formHTML += '</select>';
						break;

						case 'lkp_gender':
						case 'radio-group':
							var fieldValueArray = fieldValue.split('&#44;');
							data.field_details.options.forEach(function(option, index) {
								var checked = fieldValueArray.includes(option['value']) ? 'checked' : '';
								formHTML += '<div class="custom-control custom-radio">\
									<input type="radio" class="custom-control-input field_'+data.field_details.field_id+'" name="field_'+data.field_details.field_id+'" id="'+data.field_details.field_id+'_'+option['value']+'" value="'+option['value']+'" '+checked+'>\
									<label class="custom-control-label" for="'+data.field_details.field_id+'_'+option['value']+'">'+option['label']+'</label>\
								</div>';
							});
						break;

						case 'checkbox-group':
							var fieldValueArray = fieldValue.split('&#44;');
							data.field_details.options.forEach(function(option, index) {
								var checked = fieldValueArray.includes(option['value']) ? 'checked' : '';
								formHTML += '<div class="custom-control custom-checkbox">\
									<input type="checkbox" class="custom-control-input field_'+data.field_details.field_id+'" name="field_'+data.field_details.field_id+'[]" id="'+data.field_details.field_id+'_'+option['value']+'" value="'+option['value']+'" '+checked+'>\
									<label class="custom-control-label" for="'+data.field_details.field_id+'_'+option['value']+'">'+option['label']+'</label>\
								</div>';
							});
						break;
					}
					formHTML += '<p class="error red-800 m-0 field_'+data.field_details.field_id+'"></p>\
				</div>\
				<div class="form-group">\
					<label>Reason for edit</label><font color="red">*</font>\
					<textarea name="reason" class="form-control input-sm" ></textarea>\
					<p class="error red-800 m-0 reason"></p>\
				</div>\
				<div class="mt-10">\
					<button type="submit" class="btn btn-sm btn-success">Save</button>\
					<button type="button" class="btn btn-sm btn-danger cancelEdit pull-right">Cancel</button>\
				</div>';
				
				form.html(formHTML);
				form.addClass('text-left');
			}
		});
	}
</script>