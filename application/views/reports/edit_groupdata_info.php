<style type="text/css">
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
				<div class="col-md-12">
					<a href="" onclick="window.close()" class="btn btn-sm btn-success pull-right">Back</a>
				</div>

				<?php foreach ($group_info as $key => $group) { ?>
					<div class="col-md-12 mt-10">
						<h4 class="title">
							<button class="btn btn-sm btn-success saveAll hidden pull-right" data-toggle="modal" data-target="#reasonModal">Save All Data</button>
							<?php echo $group['group_label']; ?>
						</h4>
						<div class="card p-10">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>Sl.no</th>
											<?php foreach ($group['group_fields'] as $key => $field) { ?>
												<th><?php echo $field['label']; ?></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php if(count($group['group_data']) > 0){
											foreach ($group['group_data'] as $dkey => $data) { 
												$data_array = json_decode($data['data'], true); ?>
												<tr>
													<td><?php echo $dkey+1; ?></td>
													<?php foreach ($group['group_fields'] as $fkey => $field) { ?>
														<td>
															<div data-field='<?php echo $field['field_id']; ?>' data-group='<?php echo $data['group_id']; ?>'>
																<?php if($field['type'] == 'text' || $field['type'] == 'textarea'
																|| $field['type'] == 'number' || $field['type'] == 'scanner'
																|| $field['type'] == 'select' || $field['type'] == 'radio-group'
																|| $field['type'] == 'checkbox-group') {
																	/*echo "<a href='javascript:void(0)' title='Edit Data' class='pl-1 float-right edit'>
																		<i class='fa fa-edit' style='line-height:1.5;'></i>
																	</a>";*/
																} ?>
																
																<span class="field_value">
																<?php $column = "field_".$field['field_id'];
																if(isset($data_array[$column])){
																	if($data_array[$column] == 'null' || $data_array[$column] == ''){
																		echo "N/A";
																	}else{
																		if(is_array($data_array[$column])) {
																			echo $data_array[$column][0];
																		} else {
																			if($field['type'] == 'lkp_fodder_type'){
																				foreach($lkp_fodder_type as $lkpkey => $lookup) {
																					if($lookup['fodder_type_id'] == $data_array[$column]){
																						echo $lookup['fodder_type'];
																					}																						
																				}
																			} elseif($field['type'] == 'lkp_feed_type'){
																				foreach($lkp_feed_type as $lkpkey => $lookup) {
																					if($lookup['feed_type_id'] == $data_array[$column]){
																						echo $lookup['feed_type'];
																					}
																				}
																			} elseif($field['type'] == 'lkp_livestock_sales'){
																				foreach($lkp_livestock_sales as $lkpkey => $lookup) {
																					if($lookup['id'] == $data_array[$column]){
																						echo $lookup['name'];
																					}
																				}
																			} elseif($field['type'] == 'lkp_gender'){
																				foreach($lkp_gender as $lkpkey => $lookup) {
																					if($lookup['gender_id'] == $data_array[$column]){
																						echo $lookup['gender_des'];
																					}
																				}																						
																			} elseif($field['type'] == 'lkp_crop'){
																				foreach($lkp_crop as $lkpkey => $lookup) {
																					if($lookup['id'] == $data_array[$column]){
																						echo $lookup['crop_name'];
																					}
																				}																						
																			} elseif($field['type'] == 'lkp_income_activities'){
																				foreach($lkp_income_activities as $lkpkey => $lookup) {
																					if($lookup['id'] == $data_array[$column]){
																						echo $lookup['name'];
																					}
																				}																						
																			} elseif($field['type'] == 'lkp_animal_type'){
																				foreach($lkp_animal_type as $lkpkey => $lookup) {
																					if($lookup['id'] == $data_array[$column]){
																						echo $lookup['name'];
																					}
																				}																						
																			} elseif($field['type'] == 'lkp_livestock'){
																				foreach($lkp_livestock as $lkpkey => $lookup) {
																					if($lookup['id'] == $data_array[$column]){
																						echo $lookup['name'];
																					}
																				}																						
																			} elseif($field['type'] == 'lkp_units'){
																				foreach($lkp_units as $lkpkey => $lookup) {
																					if($lookup['unit_id'] == $data_array[$column]){
																						echo $lookup['unit_name'];
																					}
																				}																						
																			} else {
																				echo $data_array[$column];
																			}
																		}
																	}
																}else{
																	echo "N/A";
																} ?>
																</span>
															</div>
														</td>
													<?php } ?>
												</tr>
											<?php }
										}else{ ?>
											<tr>
												<td colspan="<?php echo count($group['group_fields'])+1; ?>">No data found</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>					
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
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
		showHideSaveAll(div.closest('.table'));
		//Call function to fill the form
		fillEditForm(div);
	}).on('click', '.cancelEdit', function(event) {
		var elem = $(this),
		form = elem.closest('form');

		//Show/Hide save all button
		showHideSaveAll(form.closest('.table'), 'remove');

		form.prev().find('.field_value').html(form.data('field_value'));
		form.prev().removeClass('hidden');
		form.remove();
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

		var activeSaveAll = $('body').find('.saveAll.active');
		activeSaveAll.parent().next().find('.editForm').each(function(index) {
			var individualReason = $(this).find('[name="reason"]');
			if(individualReason.val().length === 0) individualReason.val(reason);
		});
		$('#reasonModal').modal('hide');
		$('.saveAll.active').prop('disabled', true);
		activeSaveAll.parent().next().find('.editForm').trigger('submit');
	});

	//Handle edit click of every column
	$('body').on('submit', '.editForm', function(event) {
		event.preventDefault();
		var elem = $(this),
		group = elem.data('group'),
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
		formData.append('group', group);
		formData.append('field', field);

		elem.find('button').prop('disabled', true);
		elem.find('button[type="submit"]').html('Please wait...');
		$.ajax({
			url: '<?php echo base_url(); ?>reports/edit_groupdata/',
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
	function showHideSaveAll(table, remove) {
		var totalForms = table.find('.editForm').length;
		if(remove) totalForms = totalForms - 1;

		table.closest('.card').parent().find('.saveAll').removeClass('active');
		if(totalForms > 1) {
			table.closest('.card').parent().find('.saveAll').prop('disabled', false);
			table.closest('.card').parent().find('.saveAll').removeClass('hidden');
		} else {
			table.closest('.card').parent().find('.saveAll').addClass('hidden');
		}
	}

	//Function to fill form
	function fillEditForm(elem) {
		form = elem.next('form');
		
		//AJAX to get submitted data
		ajaxData['group_id'] = elem.data('group');
		ajaxData['field_id'] = elem.data('field');
		$.ajax({
			url: '<?php echo base_url(); ?>reports/get_group_details_for_edit/',
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

				form.data('type', data.field_details.type);
				form.data('group', elem.data('group'));
				form.data('field', elem.data('field'));
				if(data.field_details.required == 1) {
					form.data('required', 1);
				} else {
					form.data('required', 0);
				}
				
				var groupData = JSON.parse(data.group_data.formgroup_data),
				fieldValue = groupData['field_'+data.field_details.field_id] == 'null' ? '' : groupData['field_'+data.field_details.field_id];
				
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
							var fieldValueArray = fieldValue.split(',');
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
						
						case 'radio-group':
							var fieldValueArray = fieldValue.split(',');
							data.field_details.options.forEach(function(option, index) {
								var checked = fieldValueArray.includes(option['value']) ? 'checked' : '';
								formHTML += '<div class="custom-control custom-radio">\
									<input type="radio" class="custom-control-input field_'+data.field_details.field_id+'" name="field_'+data.field_details.field_id+'" id="'+data.field_details.field_id+'_'+option['value']+'" value="'+option['value']+'" '+checked+'>\
									<label class="custom-control-label" for="'+data.field_details.field_id+'_'+option['value']+'">'+option['label']+'</label>\
								</div>';
							});
						break;

						case 'checkbox-group':
							var fieldValueArray = fieldValue.split(',');
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