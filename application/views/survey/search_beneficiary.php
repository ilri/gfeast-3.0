<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-top: 10px;">

			<div class="row">
				<div class="col-md-12">
					<h4 class="bold">Search beneficiary</h4>
				</div>

				<div class="col-md-12 mt-10">
					<?php echo form_open(base_url().'survey/upload_surveydata/'.$this->uri->segment(3), array('id' => 'beneficiary_form', 'method' => 'post')); ?>
						<div class="card p-10">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label>Beneficiary id</label>
										<input type="text" name="beneficiary_id" class="form-control">
										<p class="error red-800"></p>
									</div>
								</div>

								<div class="col-md-4">
									<button type="button" class="btn btn-success btn-sm mt-30 check_beneficiary">Go</button>
								</div>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		// Define global variable ajaxData
    	var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };
		
		/* get country based on beneficiary selected*/
		$('.check_beneficiary').on('click', function(){

			$('.error').html('');

			var beneficiary_id = $('input[name="beneficiary_id"]').val();

			if(beneficiary_id != ''){
				ajaxData['beneficiary_id'] = beneficiary_id;
				$.ajax({
					url: "<?php echo base_url(); ?>survey/check_beneficiary",
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
					success : function(response) {
						if(response.status == 1) {
							setTimeout(function() {
								$('#beneficiary_form').submit();
							}, 100);
						} else {
							swal("Sorry!", ""+response.msg+"!", "error");
						}
					}
				});
			}else{
				$('.error').html('Enter beneficiary unique id.');
			}
		});		
	});
</script>