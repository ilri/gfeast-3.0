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

<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body" style="margin-bottom: 30px;">
			<div class="row">
				<div class="col-12">		    	
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Sl.no</th>
												<th>Table Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(count($listoftables) > 0){
												$i = 1;
												foreach ($listoftables as $key => $listoftables) { ?>
													<tr>
														<td><?php echo $i; ?></td>
														<td><?php echo $listoftables; ?></td>
														<td><a href="<?php echo base_url(); ?>lookup_tables/showtableinfo/<?php echo $key; ?>">View List</a></td>
													</tr>
													<?php $i++;
												}
											}else{ ?>
												<tr>
													<td colspan="7">No data found</td>
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