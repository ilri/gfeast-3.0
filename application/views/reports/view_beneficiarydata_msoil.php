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
				<div class="col-md-12">
					<a href="<?php echo base_url(); ?>reports/beneficiary" class="btn btn-success btn-sm pull-right">Back</a>
					<h4 style="font-weight: bold;"><?php echo $form_details['title']; ?></h4>
				</div>

				<div class="col-md-12 mt-10">
					<div class="card p-10" style="max-height: 800px;">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Sl.no</th>
										<th>Unique Id</th>
										<?php foreach ($fields as $key => $value) { ?>
											<th><?php echo $value['label']; ?></th>
										<?php } ?>
										<th>Location</th>
										<th>Images</th>
									</tr>
								</thead>
								<tbody>
									<?php if(count($survey_data) > 0){
										foreach ($survey_data as $dkey => $data) { ?>
											<tr>
												<?php $data_array = json_decode($data['form_data'], true); ?>
												<td><?php echo $dkey+1; ?></td>
												<td><?php echo $data['data_id']; ?></td>
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
																	}else{
																		if($field['subtype'] == 'encrypt'){
																			echo base64_decode($data_array[$column], true);
																		}else{
																			echo $data_array[$column];
																		}
																	}
																}else{
																	echo "N/A";
																}
																break;
														} ?>
													</td>
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
												<td>
													<?php if(count($data['images']) > 0){
														foreach ($data['images'] as $key => $img) { ?>
															<img src="<?php echo base_url(); ?>uploads/survey/<?php echo $img['file_name']; ?>" style="height: 80px; width: 80px;">
														<?php }
													}else{
														echo "No images found";
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
			</div>
		</div>
	</div>
</div>