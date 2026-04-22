<style>
	.vertical-layout{
		margin-top: 10px;
	}
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>
<!-- location Modal -->
<div class="modal fade" id="locationModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Location Details</h4>
				<button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body table-responsive">
				<table class="table">
					<thead>
						<th>#</th>
						<th>World region</th>
						<th>Major region</th>
						<th>Country</th>
						<th>State</th>
						<th>District</th>
						<!-- <th>Block</th> -->
						<!-- <th>Village</th> -->
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 30px;">
			<div class="row">
				<div class="col-12">		    	
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-header">
								<h4 class="card-title">View User</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Sl.no</th>
												<th>First name</th>
												<th>Last name</th>
												<th>Email id</th>
												<th>Username</th>
												<th>Role</th>
												<th>Created</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($users) > 0){
												foreach ($users as $ukey => $user) { ?>
													<tr data-user_id="<?php echo $user['user_id']; ?>">
														<td><?php echo ($ukey+1); ?></td>
														<td><?php echo $user['first_name']; ?></td>
														<td><?php echo $user['last_name']; ?></td>
														<td><?php echo $user['email_id']; ?></td>
														<td><?php echo $user['username']; ?></td>
														<td><?php echo $user['role_name']; ?></td>
														<td><?php echo $user['added_datetime']; ?></td>
														<td>
														<?php if($user['role_id'] > 2) { ?>
															<a href="javascript:void(0);" data-id="<?php echo $user['user_id']; ?>" class="locations"><i class="fa fa-map" aria-hidden="true"></i> View Locations</a>
														<?php } else { ?>
															N/A
														<?php } ?>
														</td>
													</tr>
												<?php }
											}else{ ?>
												<tr>
													<td colspan="7">No users found</td>
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
	</div>
</div>

<!-- Page Script -->
<script type="text/javascript">
	$(function() {
		const data = {
            worldRegions: <?php echo json_encode($world_region); ?>,
            majorRegions: <?php echo json_encode($major_region); ?>,
            countries: <?php echo json_encode($countries); ?>,
            states: <?php echo json_encode($states); ?>,
            districts: <?php echo json_encode($districts); ?>,
        };
		var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
		var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

		// Handle project locations btn click
		$('body').on('click', '.locations', function(event) {
			var elem = $(this);
			$('.error').empty();
			$('#locationModal').modal('show');
			$('#locationModal').find('tbody').html('<tr><td colspan="4">Please Wait... Getting Location Details.</td></tr>');
			var data = {
				user_id: elem.data('id')
			}
			data[csrfName] = csrfHash;
			$.ajax({
				url: '<?php echo base_url(); ?>users/get_user_locations/',
				data: data,
				type: 'POST',
				dataType: 'json',
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
					csrfName = csrfData.csrfName;
					csrfHash = csrfData.csrfHash;
				},
				error: function() {
					$.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error',
						afterHidden: function () {
							$('#locationModal').modal('hide');
						}
					});
				},
				success: function(data) {
					if(data.status == 0) {
						$('#locationModal').modal('hide');
						$.toast({
							heading: 'Error!',
							text: data.msg,
							icon: 'error',
							afterHidden: function () {
								$('#locationModal').modal('hide');
							}
						});
						return false;
					}

					if(data.status == 2) {
						$('#locationModal').modal('hide');
						$.toast({
							heading: 'Info',
							text: data.msg,
							icon: 'info',
							afterHidden: function () {
								$('#locationModal').modal('hide');
							}
						});
						return false;
					}

					var HTML = ``;
					data.assignedLoc.forEach((loc, index) => {
						// Append a row to the HTML string for each location
						HTML += `<tr>
									<td>${index + 1}</td> <!-- Use the index for the row number -->
									<td>${get_world_name(loc.world_region_id)}</td>  <!-- Get the World Region Name -->
									<td>${get_major_region_name(loc.major_region_id)}</td>  <!-- Get Major Region Name -->
									<td>${get_country_name(loc.country_id)}</td>  <!-- Get Country Name -->
									<td>${get_state_name(loc.state_id)}</td>  <!-- Get State Name -->
									<td>${get_district_name(loc.district_id)}</td>  <!-- Get District Name -->
								</tr>`;
					});
					$('#locationModal').find('tbody').html(HTML == '' ? 'No data found' : HTML);
				}
			});
		});
		
		// Function to get World Region Name
		function get_world_name(world_region_id) {
			const name = data.worldRegions.filter(item => item.id == world_region_id);
			return name.length > 0 ? name[0].world_region_name : 'NA'; // Return 'NA' if not found
		}

		// Function to get Major Region Name
		function get_major_region_name(major_region_id) {
			const name = data.majorRegions.filter(item => item.id == major_region_id);
			return name.length > 0 ? name[0].major_region_name : 'NA';
		}

		// Function to get Country Name
		function get_country_name(country_id) {
			const name = data.countries.filter(item => item.country_id == country_id);
			return name.length > 0 ? name[0].name : 'NA';
		}

		// Function to get State Name
		function get_state_name(state_id) {
			const name = data.states.filter(item => item.state_id == state_id);
			return name.length > 0 ? name[0].state_name : 'NA';
		}

		// Function to get District Name
		function get_district_name(district_id) {
			const name = data.districts.filter(item => item.district_id == district_id);
			return name.length > 0 ? name[0].district_name : 'NA';
		}
	});
</script>