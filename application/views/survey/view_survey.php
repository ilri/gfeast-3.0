<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 30px;">
			<div class="row">
				
				<div class="col-md-12">
					<a href="<?php echo base_url(); ?>survey/view" class="btn btn-success btn-sm pull-right">Back</a>
					<h4 class="title"><?php echo $form_details['title']; ?></h4>
				</div>

				<div class="col-md-12 mt-10">
					<div class="card p-10">
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
											<input type="text" name="<?php echo $value['name']; ?>" class="<?php echo $value['className']; ?> datetimepicker5" >
										<?php }else{ ?>
											<input type="<?php echo $value['subtype']; ?>" name="<?php echo $value['name']; ?>" class="<?php echo $value['className']; ?>"  >
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

										<input type="text" name="<?php echo $value['name']; ?>" class="<?php echo $value['className']; ?> datepicker"  >
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

										<input type="text" name="<?php echo $value['name']; ?>" class="<?php echo $value['className']; ?>"  >
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
														<input type="radio" name="<?php echo $value['name']; ?>" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?> ><?php echo $option['label'] ?>
													</label>
												<?php } ?>
											</div>
										<?php }else{
											foreach ($value['options'] as $key => $option) { ?>
												<div class="form-check">
													<label class="<?php if($value['inline'] == 'true'){ echo "radio-inline"; } ?>" >
														<input type="radio" name="<?php echo $value['name']; ?>"  value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?>><?php echo $option['label'] ?>
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
															<input type="radio" name="<?php echo $value['name']; ?>" value = "<?php echo $option['id']; ?>" ><?php echo $option['type'] ?>
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
															<input type="radio" name="<?php echo $value['name']; ?>" value = "<?php echo $option['id']; ?>" ><?php echo $option['name'] ?>
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
														<input type="checkbox" name="<?php echo $value['name']; ?>[]" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?> ><?php echo $option['label'] ?>
													</label>
												<?php } ?>
											</div>
										<?php }else{
											foreach ($value['options'] as $key => $option) { ?>
												<div class="form-radio">
													<label class="<?php if($value['inline'] == 'true'){ echo "checkbox-inline"; } ?>" >
														<input type="checkbox" name="<?php echo $value['name']; ?>[]" value = "<?php echo $option['value']; ?>" <?php if($option['selected'] == 'true'){ echo "checked"; } ?>  ><?php echo $option['label'] ?>
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

										<textarea name="<?php echo $value['name']; ?>" class="<?php echo $value['className']; ?>" ></textarea>
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
											<select name="<?php echo $value['name']; ?>[]" multiple class="form-control" >
										<?php }else{ ?>
											<select name="<?php echo $value['name']; ?>" class="form-control" ><?php
										}

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
										<?php }

										if($value['multiple'] == 'true'){ ?>
											<select name="<?php echo $value['name']; ?>[]" multiple class="form-control" >
										<?php }else{ ?>
											<select name="<?php echo $value['name']; ?>" class="form-control" >
												<option value="">Select partner</option>
										<?php }

										foreach ($value['options'] as $key => $option) { ?>
											<option value = "<?php echo $option['partner_id']; ?>"><?php echo $option['partner_name']; ?></option> <?php
										} ?>
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
										<?php }

										if($value['multiple'] == 'true'){ ?>
											<select name="<?php echo $value['name']; ?>[]" multiple class="form-control" >
										<?php }else{ ?>
											<select name="<?php echo $value['name']; ?>" class="form-control" >
												<option value="">Select center</option>
										<?php }

										foreach ($value['options'] as $key => $option) { ?>
											<option value = "<?php echo $option['centre_id']; ?>"><?php echo $option['centre_name']; ?></option> <?php
										} ?>
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
									
										<select name="<?php echo $value['name']; ?>" class="form-control" >
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
										<?php }

										if($value['multiple'] == 'true'){ ?>
											<select name="<?php echo $value['name']; ?>[]" multiple class="form-control" >
										<?php }else{ ?>
											<select name="<?php echo $value['name']; ?>" class="form-control" >
												<option value="">Select batch</option>
										<?php }

										foreach ($value['options'] as $key => $option) { ?>
											<option value = "<?php echo $option['batch_id']; ?>"><?php echo $option['batch_name']; ?></option> <?php
										} ?>
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
										<?php }

										if($value['multiple'] == 'true'){ ?>
											<select name="<?php echo $value['name']; ?>[]" multiple class="form-control" >
										<?php }else{ ?>
											<select name="<?php echo $value['name']; ?>" class="form-control" >
												<option value="">Select batch</option>
										<?php }

										foreach ($value['options'] as $key => $option) { ?>
											<option value = "<?php echo $option['trainee_id']; ?>"><?php echo $option['trainee_name']; ?></option> <?php
										} ?>
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

										<select name="<?php echo $value['name']; ?>" class="form-control" >
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

										<select name="<?php echo $value['name']; ?>" class="form-control" >
											<option value="">Select district</option>
											<?php foreach ($value['options'] as $key => $option) { ?>
												<option value = "<?php echo $option['district_id']; ?>"><?php echo $option['district_name']; ?></option> 
											<?php } ?>
										</select>

										<p class="error red-800"></p>
									</div>
									<?php break;

								case 'file': ?>
									<div class="form-group">
										<label><?php echo $value['label']; 
											echo ($value['required'] == 1) ? '<font color="red">*</font>' : ''; ?>
										</label>

										<?php if($value['description'] != NULL){ ?>
											<i data-toggle="tooltip" data-title="<?php echo $value['description']; ?>" class="fa fa-question-circle" aria-hidden="true"></i>
										<?php } ?>

										<input type="file" name="<?php echo $value['name']; ?>[]" class="<?php echo $value['className']; ?> <?php echo $value['subtype']; ?>" data-maxfile = "<?php echo $value['maxlength']; ?>" multiple >
										<p class="error red-800"></p>

										<?php echo ($value['subtype'] == 'image') ? '<p class="imageerror red-800"></p>' : ''; ?>
										<?php echo ($value['subtype'] == 'document') ? '<p class="documenterror red-800"></p>' : ''; ?>
										<?php echo ($value['subtype'] == 'excel') ? '<p class="excelerror red-800"></p>' : ''; ?>

									</div>
									<?php break;
								
								default:
									# code...
									break;
							}
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){

		$(".datepicker").datepicker({
			format: 'yyyy-mm-dd',
      		autoClose:true
		});

	});
</script>