<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			
			<!-- <div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
							<i class="fa fa-calendar"></i>&nbsp;
							<span></span> <i class="fa fa-caret-down"></i>
						</div>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="division[]" multiple title="Select Division">
							<?php foreach ($divisions as $key => $div) { ?>
							<option value="<?php echo $div['DIV_CODE']; ?>"><?php echo $div['DIV_NAME']; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="circle[]" multiple title="Select Circle"></select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="village[]" multiple title="Select Village"></select>
					</div>
					
					<div class="col-md-3 text-right">
						<button class="btn btn-sm btn-success get_data pull-right">Filter Data</button>
					</div>
				</div>
			</div> -->
			<div class="card-footer">
				<h5>Crop Loss Survey</h5>
			</div>
		</div>

		<!-- Import map view -->
		<?php $this->load->view('reports/map_data.php'); ?>
		<?php $filename='Farmer Registration Data';?>
		<!-- Import tabular view -->
		<?php //$this->load->view('reports/tabular_data.php', array('filename' => 'Farmer Registration Data')); ?>
		<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
		<style type="text/css">
			.dropdown-menu {
				width: auto !important;
			}
		</style>

		<!-- Tabular Data -->
		<div class="card mt-20">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="card p-10">
							
							<div class="exportContainer hidden"></div>
							<div class="table-responsive">
								<table class="table table-bordered table-hover m-0">
									<thead>
										<tr>
											<th>S.No.</th>
											<?php if($filename == 'Plot Registration Data') { ?>
											<th>Plot number</th>
											<?php } ?>
											<th>Images</th>
											<?php foreach ($fields as $key => $value) { ?>
											<th><?php echo $value['label']; ?></th>
											<?php } ?>
											<th>Uploaded By</th>
											<th>Uploaded Datetime</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$i=1;
											foreach($survey_data as $survey){?>
											<tr>
												<td><?php echo $i;?></td>
												<td><?php if(count($survey['images'])>0){?><img style="max-height:70px" src="<?php echo base_url()?>uploads/survey/<?php echo $survey['images'][0]['file_name']?>"><?php }?></td>
												
												<?php foreach ($fields as $key => $value) { ?>
												<td><?php echo (isset($survey['field_'.$value['field_id']]))?$survey['field_'.$value['field_id']]:'N/A'; ?></td>
												<?php } ?>
												<td><?php echo $survey['user_id']?></td>
												<td><?php echo $survey['datetime']?></td>
											</tr>
										<?php $i++; }?>
									</tbody>
								</table>
							</div>

							<div class="loadingText"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.js"></script>
		<script src="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.js"></script>
		<script src="<?php echo base_url(); ?>include/plugins/table_doublescroller/jquery.doubleScroll.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
	</div>
</div>
<!-- /Main content -->

<script type="text/javascript">
	var primary_key = 'farmer_id',
	url = '<?php echo base_url(); ?>reports/registration',
	mapUrl = '<?php echo base_url(); ?>reports/get_map_locations/1';
	$(function() {
		$('.get_data').trigger('click');

		//Call fn to load all locations in map
		loadAllLocationData(null);
	});
	$('.table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
</script>