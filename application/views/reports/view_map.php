<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			<div class="card-footer">
				<h5>Individual Farmer Interviews (IFI) Map View</h5>

				<div class="form-group mt-20">
					<h6>Select Season</h6>
					<select class="form-control" title="Season" name="season">
						<option value="rabi" <?php if($season == 'rabi') echo 'selected'; ?>>Rabi - 2022</option>
						<option value="kharif" <?php if($season == 'kharif') echo 'selected'; ?>>Kharif - 2022</option>
						<option value="kharif-2023" <?php if($season == 'kharif-2023') echo 'selected'; ?>>Kharif - 2023</option>
					</select>
				</div>
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
	mapUrl = '<?php echo base_url(); ?>reports/get_map_locations/431';
	$('body').on('change', '[name="season"]', function(event) {
		var elem = $(this);
		window.location.href = "<?php echo base_url() ?>reports/mappoints/"+elem.val();
	});
</script>