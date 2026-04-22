<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			<div class="card-header">
				<h3>Plot Registration Data</h3>
			</div>
			<div class="card-body">
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
			</div>
		</div>

		<!-- Import map view -->
		<?php $this->load->view('reports/map_data.php'); ?>

		<!-- Import tabular view -->
		<?php $this->load->view('reports/tabular_data.php', array('filename' => 'Plot Registration Data')); ?>
	</div>
</div>
<!-- /Main content -->

<script type="text/javascript">
	var primary_key = 'plot_id',
	url = '<?php echo base_url(); ?>reports/plot',
	mapUrl = '<?php echo base_url(); ?>reports/get_map_locations/2';
	$(function() {
		$('.get_data').trigger('click');

		//Call fn to load all locations in map
		loadAllLocationData(null);
	});
</script>