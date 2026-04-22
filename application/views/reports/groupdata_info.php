<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body">
			<div class="row">
				<div class="col-md-12">
					<a href="" onclick="window.close()" class="btn btn-sm btn-success pull-right">Back</a>
				</div>

				<?php foreach ($group_info as $key => $group) { ?>
					<div class="col-md-12 mt-10">
						<h4 class="title"><?php echo $group['group_label']; ?></h4>
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
												$data_array = json_decode($data['formgroup_data'], true); ?>
												<tr>
													<td><?php echo $dkey+1; ?></td>
													<?php foreach ($group['group_fields'] as $fkey => $field) {
														$column = "field_".$field['field_id']; ?>
														<td>
															<?php switch ($field['type']) {
																case 'lkp_crop_types':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_types as $key => $ctype) {
																				if($data_array[$column] == $ctype['type_id']){
																					echo $ctype['type_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crops':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crops as $key => $crop) {
																				if($data_array[$column] == $crop['crop_id']){
																					echo $crop['crop_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crop_intervention':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_intervention as $key => $crop_int) {
																				if($data_array[$column] == $crop_int['intervention_id']){
																					echo $crop_int['intervention_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crop_inputname':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_inputname as $key => $crop_input) {
																				if($data_array[$column] == $crop_input['inputname_id']){
																					echo $crop_input['inputname_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;

																case 'lkp_crop_varieties':
																	if(isset($data_array[$column])){
																		if($data_array[$column] == NULL || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			foreach ($crop_varieties as $key => $variety) {
																				if($data_array[$column] == $variety['variety_id']){
																					echo $variety['variety_name'];
																				}
																			}
																		}
																	}else{
																		echo "N/A";
																	}
																	break;
																
																default:
																	if(isset($data_array[$column])){
																		if($data_array[$column] == 'null' || $data_array[$column] == ''){
																			echo "N/A";
																		}else{
																			if(is_array($data_array[$column])) {
																				echo $data_array[$column][0];
																			} else {
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