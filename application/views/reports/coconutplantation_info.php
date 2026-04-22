<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body">
			<div class="row">
				<div class="col-md-12">
					<a href="" onclick="window.close()" class="btn btn-sm btn-success pull-right">Back</a>
				</div>
				<div class="col-md-12 mt-10">
					<div class="card p-10">
						<div class="table-responsive">
							<table class="table tblexportData">
								<thead>
									<tr>
										<th>Image</th>
										<th>Latitude</th>
										<th>Longitude</th>
										<th>Address</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($coconutplantation_info['images'] as $key => $img) { ?>
										<tr>
											<td><img src="<?php echo base_url(); ?>uploads/survey/<?php echo $img['file_name']; ?>"></td>
											<td><?php echo $coconutplantation_info['location'][$key]['lat']; ?></td>
											<td><?php echo $coconutplantation_info['location'][$key]['lng']; ?></td>
											<td><?php echo $coconutplantation_info['location'][$key]['address']; ?></td>
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