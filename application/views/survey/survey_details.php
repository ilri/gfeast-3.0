<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 40px;">
			<div class="row">
				<div class="col-md-12">
					<h4 class="title">Survey details</h4>
				</div>
			</div>
			<div class="card p-10">
				<div class="row">
					<div class="col-md-12">
	              		<label class="bt">Survey Title<font color="red">*</font></label>
	              		<span>
	              			<button class="btn btn-sm btn-success pull-right edit_title" style="cursor: pointer; color: #FFFFFF;"><i class="fa fa-edit"></i></button>
	              		</span>
	              		<input type="text" name="title" class="form-control" value="<?php echo $form_details['title']; ?>" disabled placeholder="Survey title" style="margin-top: 0px;">
	              		<p class="error red-800 title_error"></p>
	            	</div>
	            	<div class="col-md-12">
	              		<label class="bt">Survey description<font color="red">*</font></label>
	              		<span>
	              			<button class="btn btn-sm btn-success pull-right edit_description" style="cursor: pointer; color: #FFFFFF;"><i class="fa fa-edit"></i></button>
	              		</span>
	              		<textarea class="form-control" name="subject" placeholder="Survey Description" style="resize: none;" disabled><?php echo $form_details['description']; ?></textarea>	              		
	              		<p class="error red-800 description_error"></p>
	            	</div>
	            	<div class="col-md-12">
              			<label class="bt">Enable location</label>
              			<span>
              				<button class="btn btn-sm btn-success pull-right edit_location" style="cursor: pointer; color: #FFFFFF;"><i class="fa fa-edit"></i></button>
              			</span><br>
              			<input type="checkbox" disabled name="checkbox" <?php echo ($form_details['location'] == 1) ? "checked" : ""; ?> id="agree" value="1"><label for="agree"> Please select the checkbox to enable the location while submitting the survey</label>
              			<p class="term_checkbox_error red-800"></p>
            		</div>
            		<div class="col-md-12">
              			<label>Maximum number of images allowed</label>
              			<span>
              				<button class="btn btn-sm btn-success pull-right edit_maximages" style="cursor: pointer; color: #FFFFFF;"><i class="fa fa-edit"></i></button>
              			</span>
              			<select class="form-control" name="images_count" disabled>
							<option value="">Select images count</option>
							<option value="1" <?php echo ($form_details['pic_max'] == 1) ? "selected" : ""; ?>>1</option>
							<option value="2" <?php echo ($form_details['pic_max'] == 2) ? "selected" : ""; ?>>2</option>
							<option value="3" <?php echo ($form_details['pic_max'] == 3) ? "selected" : ""; ?>>3</option>
							<option value="4" <?php echo ($form_details['pic_max'] == 4) ? "selected" : ""; ?>>4</option>
							<option value="5" <?php echo ($form_details['pic_max'] == 5) ? "selected" : ""; ?>>5</option>
              			</select>
              			<p class="error red-800 imagescount_error"></p>
            		</div>

            		
            	</div>
            </div>

            <div class="card p-10">
				<div class="row">
					<div class="col-md-12">
            			<h4 class="title">Survey fields</h4>
            		</div>
            		<?php foreach ($fields as $key => $value) { ?>
            			<div class="col-sm-12">
            				<div class="form-group">
            					<label>Label</label>
            					<span>
	              					<p class="pull-right edit_field" data-fieldid = "<?php echo $value['field_id']; ?>" style="cursor: pointer; color: blue;" data-field_label="<?php echo $value['label']; ?>">
	              						<i class="fa fa-edit"></i>
	              					</p>
	              				</span>
            					<input type="text" name="field_label" class="form-control" value="<?php echo $value['label']; ?>" disabled placeholder="Survey title">
            					<p class="error red-800"></p>
            				</div>
            			</div>
            		<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){

		var survey_id = "<?php echo $this->uri->segment(3); ?>";

		$('body').on('click', '.edit_field', function(){
			$elem = $(this);

			var field_id = $elem.data("fieldid");
			var field_label = $elem.data('field_label');
			$elem.closest('.form-group').find('input[name="field_label"]').prop("disabled", false);

			$elem.closest('span').html('<button type="button" class="btn btn-sm btn-success save_edit_field pull-right" data-fieldid = "'+field_id+'">Save</button>\
				<button type="button" class="btn btn-sm btn-default cancel pull-right" style="margin-right:10px;" data-type="field" data-fieldid="'+field_id+'" data-field_label="'+field_label+'">cancel</button>');
		});

		// Define global variable ajaxData
    	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

		$('body').on('click', '.save_edit_field', function(){
			$elem = $(this);

			var field_id = $elem.data("fieldid");

			var field_label = $elem.closest('.form-group').find('input[name="field_label"]').val();

			if(field_label != ''){
				ajaxData['field_label'] = field_label;
				ajaxData['survey_id'] = survey_id;
				ajaxData['field_id'] = field_id;
				ajaxData['type'] = 'form_field';
				$.ajax({
					url: "<?php echo base_url(); ?>survey/edit_formdetails",
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
			              	swal({
					            title: "Done!",
					            text: ""+response.msg+"!",
					            type: "success"
					        }, function() {
					        	$elem.closest('.form-group').find('input[name="field_label"]').val(field_label);
					        	$elem.closest('.form-group').find('input[name="field_label"]').prop("disabled", true);
					        	$elem.closest('span').html('<p class="pull-right edit_field" data-fieldid = "'+field_id+'" style="cursor: pointer; color: blue;" data-field_label="'+field_label+'"><i class="fa fa-edit"></i></p>');
					            // location.reload(true);
					        });
			            }else{
			              	swal("Sorry!", ""+response.msg+"!", "error");
			            }
					}
				});
			}else{
				$elem.closest('.form-group').find('.error').html('This field is required');
			}
		});


		$('body').on('click', '.edit_title', function(){

			$('input[name="title"]').prop("disabled", false);

			$(this).closest('span').html('<button type="button" class="btn btn-sm btn-success save_edit_title pull-right">Save</button>\
				<button type="button" class="btn btn-sm btn-default cancel pull-right" style="margin-right:10px;">cancel</button>');
		});

		$('body').on('click', '.save_edit_title', function(){
			var survey_title = $('input[name="title"]').val();

			$('.title_error').html('')

			if(survey_title == ''){
				$('.title_error').html('<p>This field is mandatory.</p>')
			}else{
				ajaxData['survey_title'] = survey_title;
				ajaxData['survey_id'] = survey_id;
				ajaxData['type'] = 'save_edit_title';
				$.ajax({
					url: "<?php echo base_url(); ?>survey/edit_formdetails",
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
						swal("Something went wrong!", "Please try again", "error");
					},
					success : function(response){
						if(response.status == 1){
			              	swal({
					            title: "Done!",
					            text: ""+response.msg+"!",
					            type: "success"
					        }, function() {
					            location.reload(true);
					        });
			            }else{
			              	swal("Sorry!", ""+response.msg+"!", "error");
			            }
					}
				});
			}
		});
		

		$('body').on('click', '.edit_description', function(){

			$('textarea[name="subject"]').prop("disabled", false);

			$(this).closest('span').html('<button type="button" class="btn btn-sm btn-success save_edit_description pull-right">Save</button>\
				<button type="button" class="btn btn-sm btn-default cancel pull-right" style="margin-right:10px;">cancel</button>');
		});

		$('body').on('click', '.save_edit_description', function(){
			var survey_description = $('textarea[name="subject"]').val();

			$('.description_error').html('')

			if(survey_description == ''){
				$('.description_error').html('<p>This field is mandatory.</p>')
			}else{
				ajaxData['survey_description'] = survey_description;
				ajaxData['survey_id'] = survey_id;
				ajaxData['type'] = 'save_edit_description';
				$.ajax({
					url: "<?php echo base_url(); ?>survey/edit_formdetails",
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
						swal("Something went wrong!", "Please try again", "error");
					},
					success : function(response){
						if(response.status == 1){
			              	swal({
					            title: "Done!",
					            text: ""+response.msg+"!",
					            type: "success"
					        }, function() {
					            location.reload(true);
					        });
			            }else{
			              	swal("Sorry!", ""+response.msg+"!", "error");
			            }
					}
				});
			}
		});

		

		$('body').on('click', '.edit_location', function(){

			$('input[name="checkbox"]').prop("disabled", false);

			$(this).closest('span').html('<button type="button" class="btn btn-sm btn-success save_edit_location pull-right">Save</button>\
				<button type="button" class="btn btn-sm btn-default cancel pull-right" style="margin-right:10px;">cancel</button>');
		});

		$('body').on('click', '.save_edit_location', function(){
			ajaxData['survey_location'] = $('input[name="checkbox"]:checked').val();
			ajaxData['survey_id'] = survey_id;
			ajaxData['type'] = 'save_edit_location';
			$.ajax({
				url: "<?php echo base_url(); ?>survey/edit_formdetails",
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
					swal("Something went wrong!", "Please try again", "error");
				},
				success : function(response){
					if(response.status == 1){
		              	swal({
				            title: "Done!",
				            text: ""+response.msg+"!",
				            type: "success"
				        }, function() {
				            location.reload(true);
				        });
		            }else{
		              	swal("Sorry!", ""+response.msg+"!", "error");
		            }
				}
			});
		});

		

		$('body').on('click', '.edit_maximages', function(){

			$('select[name="images_count"]').prop("disabled", false);

			$(this).closest('span').html('<button type="button" class="btn btn-sm btn-success save_edit_maximages pull-right">Save</button>\
				<button type="button" class="btn btn-sm btn-default cancel pull-right" style="margin-right:10px;">cancel</button>');
		});	

		$('body').on('click', '.save_edit_maximages', function(){

			var images_count = $('select[name="images_count"]').val();

			$('.imagescount_error').html('')

			if(images_count == ''){
				$('.imagescount_error').html('<p>This field is mandatory.</p>')
			}else{
				ajaxData['images_count'] = images_count;
				ajaxData['survey_id'] = survey_id;
				ajaxData['type'] = 'save_edit_maximages';
				$.ajax({
					url: "<?php echo base_url(); ?>survey/edit_formdetails",
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
						swal("Something went wrong!", "Please try again", "error");
					},
					success : function(response){
						if(response.status == 1){
			              	swal({
					            title: "Done!",
					            text: ""+response.msg+"!",
					            type: "success"
					        }, function() {
					            location.reload(true);
					        });
			            }else{
			              	swal("Sorry!", ""+response.msg+"!", "error");
			            }
					}
				});
			}
		});

		$('body').on('click', '.cancel', function(){
			$elem = $(this);
			if($elem.data('type') == 'field'){
				var field_id = $elem.data("fieldid");
				var field_label = $elem.data('field_label');
				$elem.closest('.form-group').find('input[name="field_label"]').val(field_label);
				$elem.closest('.form-group').find('input[name="field_label"]').prop("disabled", true);
				$elem.closest('span').html('<p class="pull-right edit_field" data-fieldid = "'+field_id+'" style="cursor: pointer; color: blue;" data-field_label="'+field_label+'"><i class="fa fa-edit"></i></p>');
			} else {
				location.reload(true);
			}
			
		});
	});
</script>