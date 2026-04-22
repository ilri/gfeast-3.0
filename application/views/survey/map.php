<link href="<?php echo base_url() ?>include/vendors/select2/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.vertical-layout{ margin-top: 10px; }
	.select2-container .select2-search--inline .select2-search__field { margin-top: 0; width: auto !important; }
	.select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice { background-color: #D4D9F8 !important; }
</style>

<!-- Map Survey Project Modal -->
<div class="modal fade" id="mapSurvey" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Map survey to projects</h4>
			</div>
			
			<?php echo form_open('', array('id' => 'surveyForm')); ?>
				<div class="modal-body">
					<div class="form-group">
						<label for="survey_name">Survey Name</label>
						<div class="form-control" id="survey_name"></div>
					</div>

					<div class="form-group">
						<label for="projects">Select Projects</label>
						<select name="projects" id="projects"></select>
						<span class="projects error text-danger"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-info">Map Survey</button>
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-top: 10px;">
			<div class="row">
				<div class="col-md-12">
					<h4 class="bold">All Survey</h4>
				</div>
				<div class="col-md-12 mt-10">
					<div class="card p-10" style="max-height: 800px;">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th>Survey Name</th>
										<th>Assigned Project</th>
										<th>Location</th>
										<th>Created by</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $surveyCount = 0; if(count($all_surveys) > 0) {
										foreach ($all_surveys as $key => $survey) { if($survey['type'] == 'Survey') {
										$surveyCount++; ?>
										<tr id="survey<?php echo $survey['id']; ?>">
											<th scope="row"><?php echo ++$key; ?></th>
											<td><?php echo $survey['title']; ?></td>
											<td><?php echo ($survey['projects'] > 0) ? $survey['project']['project_name'] : 'N/A'; ?></td>
											<td><?php echo ($survey['location'] == 1) ? "Yes" : "N/A"; ?></td>
											<td><?php echo $survey['username']; ?></td>
											<td><?php if($survey['projects'] == 0) { ?>
												<button type="button" class="btn btn-primary btn-sm map" data-surveyid="<?php echo $survey['id']; ?>">Map Projects</button>
											<?php } else { ?>
												N/A
											<?php } ?></td>
										</tr>
										<?php } }
									} if($surveyCount == 0) { ?>
										<tr>
											<td colspan="6">No data found</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Select2 -->
<script src="<?php echo base_url() ?>include/vendors/select2/select2.full.min.js"></script>

<!-- Page Script -->
<script type="text/javascript">
	$(function() {
		$('#projects').select2({
			placeholder: 'Select Projects...'
		});
	});

	// Define global variable ajaxData
    var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

	// Handle survey map btn click
	$('body').on('click', '.map', function(event) {
		var elem = $(this);
		$('.error').empty();
		$('#surveyForm')[0].reset();
		$('#mapSurvey').modal('show');
		$('#mapSurvey').find('#projects').val(null).empty();
		$('#mapSurvey').find('button').prop('disabled', true);
		$('#mapSurvey').find('button[type="submit"]').html('Getting Survey Details...');

		ajaxData['survey_id'] = elem.data('surveyid');
		$.ajax({
			url: '<?php echo base_url(); ?>survey/get_survey_details/',
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
						$('#mapSurvey').modal('hide');
						$('#mapSurvey').find('button').prop('disabled', false);
						$('#mapSurvey').find('button[type="submit"]').html('Map Survey');
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
							$('#mapSurvey').modal('hide');
							$('#mapSurvey').find('button').prop('disabled', false);
							$('#mapSurvey').find('button[type="submit"]').html('Map Survey');
						}
					});
					return false;
				}
				
				if(data.status == 0) {
					$('#mapSurvey').modal('hide');
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error',
						afterHidden: function () {
							$('#mapSurvey').modal('hide');
							$('#mapSurvey').find('button').prop('disabled', false);
							$('#mapSurvey').find('button[type="submit"]').html('Map Survey');
						}
					});
					return false;
				}
				
				$('#mapSurvey').find('button').prop('disabled', false);
				$('#mapSurvey').find('button[type="submit"]').html('Map Survey');
				
				$('#surveyForm').data('survey_id', elem.data('surveyid'));
				$('#mapSurvey').find('#survey_name').html(data.form_details.title);

				var HTML = ``;
				for(var project of data.projects) {
					HTML += `<option value="${project.project_id}">${project.project_name}</option>`;
				}
				$('#mapSurvey').find('#projects').html(HTML).val(null);
				if(data.form_details.projects.length > 0) {
					$('#mapSurvey').find('#projects').val(data.form_details.projects);
				}
			}
		});
	});

	// Handle survey form submit
	$('#surveyForm').on('submit', function(event) {
		event.preventDefault();
		var elem = $(this);
		$('.error').empty();
		$('button').prop('disabled', true);
		$('button[type="submit"]').html('Please wait...');

		var form = $(this),
		formData = new FormData($(this)[0]);
		formData.append('survey_id', elem.data('survey_id'));
		$.ajax({
			url: '<?php echo base_url(); ?>survey/map_project/',
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
				$('button').prop('disabled', false);
				$('button[type="submit"]').html('Map Survey');
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

					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Map Survey');
				}
				
				// If validation error exists
				if(data.status > 0) {
					for(var key in data) {
						var errorContainer = form.find(`.${key}.error`);
						if(errorContainer.length !== 0) {
							errorContainer.html(data[key]);
						}
					}
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Map Survey');
				}
				
				if(data.updatestatus == 1) {
					// If update completed
					$.toast({
						heading: 'Success!',
						text: data.msg,
						icon: 'success',
						afterHidden: function () {
							window.location.href = '<?php echo base_url(); ?>survey/map';
						}
					});
				} else if(data.updatestatus == 0) {
					$.toast({
						heading: 'Error!',
						text: data.msg,
						icon: 'error'
					});
					$('button').prop('disabled', false);
					$('button[type="submit"]').html('Map Survey');
				}
			}
		});
	});
</script>