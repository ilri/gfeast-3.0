<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 30px;">
			<?php echo form_open('', array('id' => 'formdata')); ?>
				<div class="row">
					<div class="col-md-12">
						<a href="<?php echo base_url(); ?>survey/upload" class="btn btn-success btn-sm pull-right">Back</a>
						<h4 class="title"><?php echo $form_details['title']; ?></h4>
					</div>

					<div class="col-md-12 mt-10">					
						<div class="card p-10">
							<div class="form-group">
								<label>Select country<font color="red">*</font></label>
								<select name="country_id" class="form-control" data-required="required">
									<option value="">Select country</option>
									<?php foreach ($get_beneficiary_country as $key => $country) { ?>
										<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
									<?php } ?>
								</select>
								<p class="error red-800"></p>
							</div>

							<div class="form-group">
								<label>Select state<font color="red">*</font></label>
								<select name="state_id" class="form-control" data-required="required">
									<option value="">Select state</option>
								</select>
								<p class="error red-800"></p>
							</div>

							<div class="form-group">
								<label>Select district<font color="red">*</font></label>
								<select name="district_id" class="form-control" data-required="required">
									<option value="">Select district</option>
								</select>
								<p class="error red-800"></p>
							</div>

							<?php foreach ($fields as $key => $value) {
								switch ($value['type'] ) {
									case 'text':
									case 'tel': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php }

											if($value['subtype'] == 'datetime-local'){ ?>
												<input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> datetimepicker5" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
											<?php }else{ ?>
												<input type="<?php echo $value['subtype']; ?>" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>"  data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
											<?php } ?>
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'date': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?> 

											<input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?> datepicker" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" onkeydown="return false">
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'number': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<input type="text" name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" data-subtype="<?php echo $value['subtype']; ?>">
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'radio-group': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php }

											if($value['inline'] == 'true'){ ?>
												<div class="form-check">
													<?php foreach ($value['options'] as $key => $option) { ?>                                
														<label class="<?php if($value['inline'] == 'true'){ echo "radio-inline"; } ?>" >
															<input type="radio" name="field_<?php echo $value['field_id']; ?>" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?> data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ><?php echo $option['label'] ?>
														</label>
													<?php } ?>
												</div>
											<?php }else{
												foreach ($value['options'] as $key => $option) { ?>
													<div class="form-check">
														<label class="<?php if($value['inline'] == 'true'){ echo "radio-inline"; } ?>" >
															<input type="radio" name="field_<?php echo $value['field_id']; ?>"  value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?> data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ><?php echo $option['label'] ?>
														</label>
													</div>
												<?php }
											} ?> 
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_gender': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<div class="form-check">
												<div class="row">
													<?php foreach ($value['options'] as $key => $option) { ?>   
														<div class="col-sm-2">
															<label class="<?php if($value['inline'] == 'true'){ echo "radio-inline"; } ?>" >
																<input type="radio" name="field_<?php echo $value['field_id']; ?>" value = "<?php echo $option['id']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ><?php echo $option['type'] ?>
															</label>
														</div>
													<?php } ?>
												</div>
											</div>
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_yesno': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<div class="form-check">
												<div class="row">
													<?php foreach ($value['options'] as $key => $option) { ?>
														<div class="col-sm-2">                               
															<label class="<?php if($value['inline'] == 'true'){ echo "radio-inline"; } ?>" >
																<input type="radio" name="field_<?php echo $value['field_id']; ?>" value = "<?php echo $option['id']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ><?php echo $option['name'] ?>
															</label>
														</div>
													<?php } ?>
												</div>
											</div>
											<p class="error red-800"></p>
										</div>
										<?php break;


									case 'checkbox-group': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php }

											if($value['inline'] == 'true'){ ?>
												<div class="form-radio">
													<?php foreach ($value['options'] as $key => $option) { ?>
														<label class="<?php if($value['inline'] == 'true'){ echo "checkbox-inline"; } ?>" >
															<input type="checkbox" name="field_<?php echo $value['field_id']; ?>[]" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?> data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ><?php echo $option['label'] ?>
														</label>
													<?php } ?>
												</div>
											<?php }else{
												foreach ($value['options'] as $key => $option) { ?>
													<div class="form-radio">
														<label class="<?php if($value['inline'] == 'true'){ echo "checkbox-inline"; } ?>" >
															<input type="checkbox" name="field_<?php echo $value['field_id']; ?>[]" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?> data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ><?php echo $option['label'] ?>
														</label>
													</div>
												<?php }
											} ?>
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'textarea': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<textarea name="field_<?php echo $value['field_id']; ?>" class="<?php echo $value['className']; ?>" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" ></textarea>
											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'select': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php }

											if($value['multiple'] == 'true'){ ?>
												<select name="field_<?php echo $value['field_id']; ?>[]" multiple class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
											<?php }else{ ?>
												<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" >
													<option value="">Select option</option>
											<?php }

												foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "selected"; } ?> ><?php echo $option['label']; ?></option> <?php
												} ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_partners': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>" >
												<option value="">Select partner</option>

												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['partner_id']; ?>"><?php echo $option['partner_name']; ?></option>
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_centre': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>
											
											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
												<option value="">Select center</option>
											
												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['centre_id']; ?>"><?php echo $option['centre_name']; ?></option>
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_age': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>
										
											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
												<option value="">Select age</option>
												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['id']; ?>"><?php echo $option['age']; ?></option> 
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;								

									case 'lkp_batch': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>
											
											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
												<option value="">Select batch</option>
												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['batch_id']; ?>"><?php echo $option['batch_name']; ?></option>
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_trainee': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>
											
											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
												<option value="">Select batch</option>
												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['trainee_id']; ?>"><?php echo $option['trainee_name']; ?></option>
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_state': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
												<option value="">Select state</option>
												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['state_id']; ?>"><?php echo $option['state_name']; ?></option> 
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;

									case 'lkp_district': ?>
										<div class="form-group">
											<label><?php echo $value['label']; 
												echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
											</label>

											<?php if($value['description'] != NULL){ ?>
												<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
											<?php } ?>

											<select name="field_<?php echo $value['field_id']; ?>" class="form-control" data-required="<?php echo ($value['required'] == 1) ? 'required' : ''; ?>">
												<option value="">Select district</option>
												<?php foreach ($value['options'] as $key => $option) { ?>
													<option value = "<?php echo $option['district_id']; ?>"><?php echo $option['district_name']; ?></option> 
												<?php } ?>
											</select>

											<p class="error red-800"></p>
										</div>
										<?php break;
								}
							} ?>

							<div class="form-group">
								<label class="english">Upload relevant images (if available) <?php echo ($form_details['pic_min'] != NULL) ? '<font color="red">*</font>' : ''; ?></label>
								<label class="french hidden">(Télécharger les images appropriées) <?php echo ($form_details['pic_min'] != NULL) ? '<font color="red">*</font>' : ''; ?></label>
								<input type="file" multiple name="survey_images[]" id="surv_images" />
								<div class="help-block pull-right" id="holder" style="border:1px solid #6cc00c;"></div>
								<p style="font-size: 10px; font-style: italic; color: gray;">
									File size must be less than 5MB<br/>
									Only image file types are allowed
								</p>
								<p class="red-800"  id="si_err"></p>
							</div>
						</div>
					
					</div>

					<div class="col-md-12">
						<button name="submit" type="button" class="pull-right btn btn-success pull-up" style="margin-left:10px;border-radius:10px;"><i class="fa fa-upload" aria-hidden="true"></i> Submit Data</button>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(function(){

		var imageerror = 0;

		$(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
      		autoClose:true
		});

		var beneficiary_id = '<?php echo $beneficiary_id; ?>';

		//to check value is perfect decimal number or not
		$('body').on('keyup', '.decimal', function(){
			$(this).closest('.form-group').find('.error').html('');
			if($(this).val().length > 0){
				if(!/^(\d*\.?\d*)$/.test($(this).val())){
					$(this).closest('.form-group').find('.error').html('Please! Enter only number');
	            }else if (!/^[0-9]+(\.\d{1,2})?$/.test($(this).val())) {
	            	$(this).closest('.form-group').find('.error').html('Field can contain only proper decimal number.');
	            }
	        }
		});
		
		//to check value is perfect number
		$('body').on('keyup', '.number', function(){
			$(this).closest('.form-group').find('.error').html('');
			if($(this).val().length > 0){
				if (/^\d+$/.test($(this).val())) {
					$(this).closest('.form-group').find('.error').empty();
				} else {
					$(this).val('');
					$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
				}
			}
		});

		// Define global variable ajaxData
    	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

		/* get country based on beneficiary selected*/
		$('.get_beneficiary_info').on('click', function(){
			var beneficiary_id = $('input[name="beneficiary_id"]').val();

			ajaxData['beneficiary_id'] = beneficiary_id;
			$.ajax({
				url: "<?php echo base_url(); ?>survey/get_beneficiary_country",
				type: "POST",
				dataType: "json",
				data: ajaxData,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function () {
					swal("Network Error!", "Could not establish connection to server. Please refresh the page and try again.", "error");
				},
				success : function(response){
					if(response.status == 1){
						var html = '<option value="">Select country</option>';

						response.get_beneficiary_country.forEach(function(country, index){
							html += '<option value="'+country.country_id+'">'+country.name+'</option>';
						});

						$('select[name="country_id"]').html(html);
					}else{
						swal("Sorry!", ""+response.msg+"!", "error");
					}
				}
			});
		});

		/* get state based on beneficiary selected*/
		$('select[name="country_id"]').on('change', function(){
			var country_id = $(this).val();

			ajaxData['beneficiary_id'] = beneficiary_id;
			ajaxData['country_id'] = country_id;
			$.ajax({
				url: "<?php echo base_url(); ?>survey/get_beneficiary_state",
				type: "POST",
				dataType: "json",
				data: ajaxData,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function () {
					swal("Network Error!", "Could not establish connection to server. Please refresh the page and try again.", "error");
				},
				success : function(response){
					if(response.status == 1){
						var html = '<option value="">Select state</option>';

						response.get_beneficiary_state.forEach(function(state, index){
							html += '<option value="'+state.state_id+'">'+state.state_name+'</option>';
						});

						$('select[name="state_id"]').html(html);
					}else{
						swal("Sorry!", ""+response.msg+"!", "error");
					}
				}
			});
		});

		/* get district based on beneficiary selected*/
		$('select[name="state_id"]').on('change', function(){
			var state_id = $(this).val();
			var country_id = $('select[name="country_id"]').val();

			ajaxData['beneficiary_id'] = beneficiary_id;
			ajaxData['country_id'] = country_id;
			ajaxData['state_id'] = state_id;
			$.ajax({
				url: "<?php echo base_url(); ?>survey/get_beneficiary_district",
				type: "POST",
				dataType: "json",
				data: ajaxData,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function () {
					swal("Network Error!", "Could not establish connection to server. Please refresh the page and try again.", "error");
				},
				success : function(response){
					if(response.status == 1){
						var html = '<option value="">Select district</option>';

						response.get_beneficiary_district.forEach(function(dist, index){
							html += '<option value="'+dist.district_id+'">'+dist.district_name+'</option>';
						});

						$('select[name="district_id"]').html(html);
					}else{
						swal("Sorry!", ""+response.msg+"!", "error");
					}
				}
			});
		});


		<?php if($form_details['pic_max'] != NULL){ ?>
			var maxpics = <?php echo $form_details['pic_max']; ?>;
		<?php }else{ ?>
			var maxpics = 5;
		<?php } ?>

		$("#surv_images").change(function () {
			$('#si_err').html('');
			var elem = $(this);
			var images_count = maxpics+1;

			imageerror = 0;
			
			if((elem[0].files.length < images_count)){
				if (typeof (FileReader) != "undefined") {
					var dvPreview = $("#holder");
					dvPreview.html("");
					var regex = /\.(gif|png|jpg|jpeg)$/i;
					$(elem[0].files).each(function () {
						var file = $(this);

						if (!regex.test(file[0].name.toLowerCase())) {
							//$("input[name='survey_images[]']").val("");
							$('#si_err').html('Only image file types are allowed.');
							dvPreview.html("");
							imageerror++;
							//return false;
						} else if (file[0].size > 5242880) {
							//$("input[name='survey_images[]']").val("");
							$('#si_err').html('File size must be less than 5MB.');
							dvPreview.html("");
							imageerror++;
							//return false;
						} else {
							$('#si_err').empty();
						}
						var reader = new FileReader();
						reader.onload = function (e) {
							var IMG  = $("<img src='"+e.target.result+"' style='height:100px; width:100px; padding:5px;'>");
							dvPreview.append(IMG);
						}
						reader.readAsDataURL(file[0]);
					});
				} else {
					alert("This browser does not support HTML5 FileReader.");
				}
			}else{
				var dvPreview = $("#holder");
				dvPreview.html("");
				$("input[name='survey_images[]']").val("");
				$('#si_err').html('Maximum files to be choose is '+maxpics+'');
			}
		});


		$('button[name="submit"]').on('click', function (event) {
			$elem = $(this);

			$('.error').html('');

			$elem.prop('disabled', true);

			var surveycount = 0;      		

      		$('input[type=file]', '#formdata').each(function() {
      			var fieldtype = $(this).data("fieldtype");
      			var fieldsubtype = $(this).data("fieldsubtype");
      			var requiredvalue = $(this).data("required");

      			if(fieldsubtype == 'document'){
      				if(fieldtype == 'uploadfile' && typeof fieldtype !== 'undefined'){
      					if(requiredvalue == 'required'){
      						if($.trim($(this).val()).length === 0){
      							$(this).closest('.form-group').find('.error').html('This field is required');
      							surveycount++;
      						}
      					}

      					if($(this).val() != ''){
      						var fileUpload = $(this)[0].files[0];
      						var fileTypes = ['pdf'];
      						var extension = fileUpload.name.split('.').pop().toLowerCase();
      						var error = [];

      						if(fileTypes.indexOf(extension) == '-1') {
      							error.push('Please upload a valid pdf file.');
      							surveycount++;
      						}
      						if(fileUpload.size > 5242880) {
      							error.push('Upload file size should be less than 5MB');
      							surveycount++;
      						}
      						$(this).closest('.form-group').find('.error').html(error.join('<br/>'));
      					}
      				}
      			}
      		});

      		$('input[type=text]', '#formdata').each(function() {
      			var requiredvalue = $(this).data("required");
      			var subtypevalue = $(this).data("subtype");
      			var maxvalue = $(this).data("maxlength");
      			if(requiredvalue == 'required'){
      				if($.trim($(this).val()).length === 0){
      					$(this).closest('.form-group').find('.error').html('This field is required');
      					surveycount++;
      				}
      			}
      			if(subtypevalue == 'number' || subtypevalue == 'phone' || subtypevalue == 'numberfield' || subtypevalue == 'desimal' || subtypevalue == 'latitude' || subtypevalue == 'longitude'){
      				switch (subtypevalue){
      					case 'numberfield':
	      					if($.trim($(this).val()).length > 0){
				                if (!/^(\+|-)?(\d*\.?\d*)$/.test(this.value)) { // a non–digit was entered
				                	$(this).closest('.form-group').find('.error').html('This field contains only numbers and perfect decimals.');
				                	surveycount++;
				                }else{
				                	$(this).closest('.form-group').find('.error').empty();
				                }
				            }
	            			break;

	            		case 'phone':
			            case 'number':
				            if($.trim($(this).val()).length > 0){
				            	if (/^\d+$/.test($(this).val())) {
				            		$(this).closest('.form-group').find('.error').empty();
				            	} else {
				            		$(this).val('');
				            		$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
				            		surveycount++;
				            	}
				            }
				            break;

			            case 'latitude':
				            if($.trim($(this).val()).length > 0){
				            	if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
				            		$(this).closest('.form-group').find('.error').empty();
				            	} else {
				            		$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
				            		surveycount++;
				            	}
				            }
				            break;

			            case 'longitude':
				            if($.trim($(this).val()).length > 0){
				            	if (/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/.test($(this).val())) {
				            		$(this).closest('.form-group').find('.error').empty();
				            	} else {
				            		$(this).closest('.form-group').find('.error').html('Please provide a valid number.');
				            		surveycount++;
				            	}
				            }
				            break;

			            case 'desimal':
				            if($.trim($(this).val()).length > 0){
				            	if(!/^(\d*\.?\d*)$/.test($(this).val())){
				            		$(this).closest('.form-group').find('.error').html('Please! Enter only number');
				            		surveycount++;
				            	}else if (!/^[0-9]+(\.\d{1,2})?$/.test($(this).val())) {
				            		$(this).closest('.form-group').find('.error').html('Field can contain only proper decimal number.');
				            		surveycount++;
				            	}
				            }
				            break;
        			}
    			}

			    if(subtypevalue == 'email' && $(this).val().length > 0){
			    	if( !isValidEmailAddress( $(this).val())) { 
			    		$(this).closest('.form-group').find('.error').html('Invalid email id');
			    		surveycount++; 
			    	}
			    }

			    if($.trim($(this).val()).length > maxvalue){
			    	$(this).closest('.form-group').find('.maxlengtherror').html('Please! Enter upto '+maxvalue+' character/number');
			    	surveycount++;
			    }
			});

			$('textarea', '#formdata').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				var maxvalue = $(this).data("maxlength");

				if(requiredvalue == 'required'){
					if($.trim($(this).val()).length === 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
				if($.trim($(this).val()).length > maxvalue){
					$(this).closest('.form-group').find('.maxlengtherror').html('Please! Enter upto '+maxvalue+' character/number');
					surveycount++;
				}
			});

			$('input[type=radio]', '#formdata').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				if(requiredvalue == 'required'){
					var name = $(this).attr("name");
					if($("input:radio[name="+name+"]:checked").length == 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
			});

			$('select', '#formdata').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");

				if(requiredvalue == 'required'){
					if($.trim($(this).val()).length == 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
			});

			$('input[type=checkbox]', '#formdata').each(function() {
				var requiredvalue = $(this).data("required");
				var subtypevalue = $(this).data("subtype");
				if(requiredvalue == 'required'){
					var name = $(this).attr("name");
					if($("input:checkbox[name='"+name+"']:checked").length == 0){
						$(this).closest('.form-group').find('.error').html('This field is required');
						surveycount++;
					}
				}
			});

			var images_count = $("#surv_images")[0].files.length
			if (images_count > maxpics){
				$("input[type='file']").closest('.form-group').find('.error').html('Maximum files to be choose is '+maxpics+'');
				surveycount++;
			}

			if(surveycount == 0 && imageerror == 0){
				var formdata = new FormData($('#formdata')[0]);
				formdata.append('survey_id', <?php echo $this->uri->segment(3); ?>);
				formdata.append('survey_type', 'survey');
				formdata.append('project_id', '<?php echo $project_id; ?>');
				formdata.append('beneficiary_id', beneficiary_id);
				$.ajax({
					url: '<?php echo base_url(); ?>survey/survey_insert',
					type: 'POST',
					dataType : 'json',
					data: formdata,
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
						swal({
				            title: "Network Error!",
				            text: "Could not establish connection to server. Please refresh the page and try again.",
				            type: "error"
				        }, function() {
				            $('html,body').animate({
			            		scrollTop: $(".content-body").offset().top - 300
			        		}, 500);

			        		$elem.prop('disabled', false);
				        });				        
					},
					success : function(response){
						if(response.status == 1){
							swal({
					            title: "Success!",
					            text: ""+response.msg+"!",
					            type: "success"
					        }, function() {
					            $('html,body').animate({
				            		scrollTop: $(".content-body").offset().top - 300
				        		}, 500);

					            $('#formdata input[type="tel"]').val('');
								$('#formdata input[type="text"]').val('');
								$('#formdata input[type="email"]').val('');
								$('#formdata select').val('');
								$('#formdata input[type="file"]').val('');
								$('#formdata textarea').val('');
								$('#formdata input[type="checkbox"]').each(function() {
									this.checked = false;
								});
								
								$('#formdata input[type="radio"]').each(function() {
									this.checked = false;
								});
								$('#holder').html('');

								$elem.prop('disabled', false);

								window.location.href = "<?php echo base_url(); ?>survey/upload";
					        });

					        
						}else{
							swal({
					            title: "Error!",
					            text: ""+response.msg+"!",
					            type: "error"
					        }, function() {
					            $('html,body').animate({
				            		scrollTop: $(".content-body").offset().top - 300
				        		}, 500);

				        		$elem.prop('disabled', false);
					        });

					        
						}
					}
				});
			}else{
        		swal({
		            title: "Warning!",
		            text: "Please clear all the errors!",
		            type: "error"
		        }, function() {
		            $('html,body').animate({
	            		scrollTop: $(".content-body").offset().top - 300
	        		}, 500);
		        });

		        $elem.prop('disabled', false);
			}
		});
	});
</script>