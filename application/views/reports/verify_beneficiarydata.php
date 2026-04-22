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
	label {
    font-weight: bold;
    color: #800000 !important;
}
</style>


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
						<div class="text-right verify hidden">
							<button type="button" class="btn btn-sm btn-success mr-1" data-status="1">Accept</button>
							<button type="button" class="btn btn-sm btn-danger" data-status="0">Reject</button>
						</div>
						<div class="table-responsive">
							<?php echo form_open('', array('id'=>'verifyForm')); ?>
							<table class="table">
								<thead>
									<tr>
										<?php if($this->session->userdata('role') == 3 || $this->session->userdata('role') == 4) { ?>
										<th><input type="checkbox" class="checkall"></th>
										<?php } ?>
										<th width="50">Proj Mgr Verified</th>
										<th width="50">Agency Mgr Verified</th>
										<th>Sl.no</th>
										<th>Unique Id</th>
										<th>Images</th>
										<?php foreach ($fields as $key => $value) { ?>
											<th><?php echo $value['label']; ?></th>
										<?php } ?>
										<!-- <?php if($check_group_field > 0){ ?>
											<th>Group data</th>
										<?php } ?> -->
										<th>Location</th>
										
									</tr>
								</thead>
								<tbody id="beneficiary_data">
									<?php if(count($survey_data) > 0){
										foreach ($survey_data as $dkey => $data) { ?>
											<tr class="<?php echo $data['data_id'] ?>" data-id="<?php echo $data['id'] ?>">
												<?php $data_array = json_decode($data['form_data'], true); ?>
												<?php if($this->session->userdata('role') == 3
												|| $this->session->userdata('role') == 4) { ?>
												<td>
													<?php if(($this->session->userdata('role') == 3 && (is_null($data['pm_verified']) || strlen($data['pm_verified']) == 0))
													|| ($this->session->userdata('role') == 4 && (is_null($data['am_verified']) || strlen($data['am_verified']) == 0))) { ?>
														<input type="checkbox" name="check[]" value="<?php echo $data['id']; ?>">
													<?php } ?>
												</td>
												<?php } ?>
												<td>
													<?php if(is_null($data['pm_verified']) || strlen($data['pm_verified']) == 0) {
														echo '<span class="badge border-warning warning badge-border">Unverified';
													} else {
														echo $data['pm_verified'] == 0 ? '<span class="badge border-danger danger badge-border">Rejected' : '<span class="badge border-success success badge-border">Accepted';
													} ?></span>
												</td>
												<td>
													<?php if(is_null($data['am_verified']) || strlen($data['am_verified']) == 0) {
														echo '<span class="badge border-warning warning badge-border">Unverified';
													} else {
														echo $data['am_verified'] == 0 ? '<span class="badge border-danger danger badge-border">Rejected' : '<span class="badge border-success success badge-border">Accepted';
													} ?></span>
												</td>
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
													<td>
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
													</td>
												<?php } ?>
												<!-- <?php if($check_group_field > 0){ ?>
													<td><a href="<?php echo base_url(); ?>reports/groupdata_info/<?php echo ($this->uri->segment(2) == 'view_dashboard') ? 1 : $this->uri->segment(3); ?>/<?php echo $data['data_id']; ?>" target="_blank">View data</a></td>
												<?php } ?> -->
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
							<?php echo form_close(); ?>
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

		 intiall_date = "05/10/2020";
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
						HTML += `<tr class="`+data.id+`" data-id="`+data.id+`">`;
						if(role == 3 || role == 4) {
						HTML += `<td>`;
							if((role == 3 && (!data.pm_verified || data.pm_verified.length == 0))
							|| (role == 4 && (!data.am_verified || data.am_verified.length == 0))) {
								HTML += `<input type="checkbox" name="check[]" value="`+data.id+`">`;
							}
						HTML += `</td>`;
						}
						HTML += `<td>`;
							if(!data.pm_verified || data.pm_verified.length == 0) {
								HTML += `<span class="badge border-warning warning badge-border">Unverified`;
							} else {
								HTML += data.pm_verified == 0 ? `<span class="badge border-danger danger badge-border">Rejected` : `<span class="badge border-success success badge-border">Accepted`;
							}
							HTML += `</span>
						</td>
						<td>`;
							if(!data.am_verified || data.am_verified.length == 0) {
								HTML += `<span class="badge border-warning warning badge-border">Unverified`;
							} else {
								HTML += data.am_verified == 0 ? `<span class="badge border-danger danger badge-border">Rejected` : `<span class="badge border-success success badge-border">Accepted`;
							} HTML += `</span>
						</td>`;
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

							switch(field.type){
								case 'lkp_district':
								if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
									HTML += `<td>N/A</td>`;
								}else{
									response.district_list.forEach(function(dist, index){
										if(dist.district_id == jsondata[fieldname]){
											HTML += `<td>`+dist.district_name+`</td>`;
										}
									});
								}
								break;

								case 'lkp_block':
								if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
									HTML += `<td>N/A</td>`;
								}else{
									response.block_list.forEach(function(block, index){
										if(block.block_id == jsondata[fieldname]){
											HTML += `<td>`+block.block_name+`</td>`;
										}
									});
								}
								break;

								case 'lkp_village':
								if(typeof jsondata[fieldname] === 'undefined' || jsondata[fieldname] == null || jsondata[fieldname] == ''){
									HTML += `<td>N/A</td>`;
								}else{
									response.village_list.forEach(function(village, index){
										if(village.village_id == jsondata[fieldname]){
											HTML += `<td>`+village.village_name+`</td>`;
										}
									});
								}
								break;

								default:
								HTML += `<td>`+(typeof jsondata[fieldname] === 'undefined' ? "N/A" : jsondata[fieldname])+`</td>`;
								break;
							}
						});

						// if(response.check_group_field > 0) {
						// 	HTML += `<td class="unwantedCol">
						// 		<a href="<?php echo base_url(); ?>reports/edit_groupdata_info/<?php echo ($this->uri->segment(2) == 'view_dashboard') ? 1 : $this->uri->segment(3); ?>/`+data.data_id+`" target="_blank">
						// 			Edit data
						// 		</a>
						// 	</td>`;
						// }

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

	//Handle checkall on click
	$('body').on('change', '.checkall', function(event) {
		var elem = $(this);

		if(elem.is(":checked")) {
			$('.verify').removeClass('hidden');
			$('body').find('[name="check[]"]').prop('checked', true);
		} else {
			$('.verify').addClass('hidden');
			$('body').find('[name="check[]"]').prop('checked', false);
		}
	});
	//Handle check on change
	$('body').on('change', '[name="check[]"]', function(event) {
		var totalChecked = $('body').find('[name="check[]"]:checked').length;
		if(totalChecked > 0) {
			$('.verify').removeClass('hidden');
		} else {
			$('.verify').addClass('hidden');
		}
	});

	//Handle verify button click
	$('.verify').on('click', 'button', function(event) {
		var elem = $(this),
		status = elem.data('status');
		$('.verify').find('button').prop('disabled', true);

		var formData = new FormData($('#verifyForm')[0]);
		formData.append('status', elem.data('status'));
		$.ajax({
			url: '<?php echo base_url(); ?>reports/verify_beneficiary/',
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
				$('.verify').find('button').prop('disabled', false);
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
						icon: 'error'
					});
					$('.verify').find('button').prop('disabled', false);
				}

				if(data.status == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							$('.verify').find('button').prop('disabled', false);
							
							var verifyText = '',
							role = <?php echo $this->session->userdata('role'); ?>;
							if(status == 1) verifyText = '<span class="badge border-success success badge-border">Accepted</span>';
							if(status == 0) verifyText = '<span class="badge border-danger danger badge-border">Rejected</span>';
							
							$('body').find('[name="check[]"]:checked').each(function(index) {
								if(role == 4) $(this).parent().next().next().html(verifyText);
								if(role == 3) $(this).parent().next().html(verifyText);
								$(this).trigger('click');
								$(this).remove();
							});
						}
					});
				} else if(data.status == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('.verify').find('button').prop('disabled', false);
				}
			}
		});
	});
</script>