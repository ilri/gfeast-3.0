<style>
	/* pagination css start*/
	.dataTables_info{
		display: none;
	}
	.submited_pagination{
		display: flex;
		margin-bottom: 0px;
		justify-content: end;
		align-items: center;
		background-color: #D3E7DD;
	}
	.p1{
		padding: 10px 10px;
	}
	.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border-radius: 8px!important;
	border:3px solid #4d7c3a !important;
}
.modal-content h5 {
    font-size: 20px;
    font-weight: 500;
    color: #000;
}
.close {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    float: right;
    opacity: .5;
    position: relative;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    right: -12px;
    top: -23px;
}
.modal-content h5 {
	font-size: 20px;
	margin-bottom:5px;
}
	.border-right.line {
		background: #000 !important;
		width: 3px;
		height: 23px;
		margin-top: 0px;
	}
	label {
    font-weight: bold;
    color: #800000 !important;
}
.modal {
	background-color: #0000008f;
}
	.s1{
		height: 33px;
    	border-radius: 5px;
    	text-align: center;
	}
	.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
    	width: 100% !important;
	}
	.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
        color: #000;
        background: #e3e7ee !important;
    }
	.btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active, .show > .btn-light.dropdown-toggle {
		color: #2a2e30;
		background-color: #e3e7ee;
	}
	select.form-control:not([size]):not([multiple]), input.form-control {
		background: #e3e7ee;
		color: #000;
	}
	.mt-28px {
		margin-top: 28px;
	}
	.text-danger{
		font-size: 14px;
	}
	/* pagination css end*/
	/*Loader css Added here */
	.imagediv_load {
		position: relative;
	}

	.loaders {
		margin: 0 auto;
		z-index: 10000;

	}

	.loaders img {
		-webkit-animation: rotation 2s infinite linear;
	}

	.loader-height {
		height: 450px;
	}

	.rotate {
		animation: rotation 2s infinite linear;
	}

	@keyframes rotation {
		from {
			transform: rotate(0deg);
		}

		to {
			transform: rotate(360deg);
		}
	}

	
.font-18px h2{
    font-size: 18px!important;
    font-weight: 600;
}
.bootstrap-select .dropdown-toggle .filter-option-inner-inner {
    overflow: hidden;
    font-size: 14px;
}
.font-18px h2 {
    font-size: 18px !important;
    font-weight: 600;
    color: #fff;
}
.card-header.bg-dark {
    background: #464855 !important;
}
/* .card-header.bg-dark {
    background: #959595 !important;
} */
.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
    color: #00000091;
    background: #e3e7ee !important;
}

.downArrow:after {
    content: "\f078";
    position: absolute;
    right: 12px;
    bottom: 17px;
    z-index: 99;
    display: inline-block;
    width: 0px;
    height: 0px;
    margin-left: .255em;
    vertical-align: .255em;
    content: "";
    border-top: .35em solid;
    border-right: .35em solid #692f2f00;
    border-bottom: 0;
    border-left: .35em solid transparent;
}
.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
    color: #00000091;
    background: #e3e7ee !important;
}
.bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: 100%;
	}
	.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
        color: #000;
        background: #e3e7ee !important;
    }
	.btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active, .show > .btn-light.dropdown-toggle {
		color: #2a2e30;
		background-color: #e3e7ee;
	}
	select.form-control:not([size]):not([multiple]), input.form-control {
		background: #e3e7ee;
		color: #000;
	}
	.bootstrap-select > .dropdown-toggle.bs-placeholder, .bootstrap-select > .dropdown-toggle.bs-placeholder:hover, .bootstrap-select > .dropdown-toggle.bs-placeholder:focus, .bootstrap-select > .dropdown-toggle.bs-placeholder:active {
    color: #9e9e9e;
    border: 1px solid #babfc7 !important;
}
</style>


<!-- Add this modal markup to your HTML -->
<div id="approveModal" class="modal">
	<div class="modal-dialog modal-dialog-centered modal-sm">
	<div class="modal-content px-2">
		<div class="d-flex justify-content-between align-items-center pt-1">
			<h5 class="mt-1">Are you sure you want to approve?</h5>
			<span class="close" onclick="closeApproveModal()">&times;</span>
		</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12 text-center">
					<button id="submitApprove" class="mt-2 btn btn-success btn-sm text-center mb-1 px-4">Yes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="rejectModal" class="modal">
	<div class="modal-dialog modal-dialog-centered modal-sm">
	<div class="modal-content px-2">
	<div class="d-flex justify-content-between align-items-center pt-1">
		<h5>Enter reason for rejection</h5>
		<span class="close" onclick="closeModal()">&times;</span>
	</div>

	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="charCountDisplay"></div>
			<textarea id="rejectionReason" rows="3" placeholder="Enter reason for rejection" class="form-control"></textarea>
		</div>
		<div class="col-sm-12 col-md-12 col-lg-12 text-center">
			<button id="submitRejection" class="mt-2 btn btn-success btn-sm text-center mb-1 px-4">Submit</button>
		</div>
		</div>
		</div>
	</div>
</div>

<!-- Add this delete modal-like HTML to your document -->
<div id="deleteModal" class="modal">
	<div class="modal-dialog modal-dialog-centered modal-sm">
	<div class="modal-content px-2">
		<div class="d-flex justify-content-between align-items-center pt-1">
			<h5 class="modal-title">Enter reason for deletion</h5>
			<span class="close" onclick="closeDeleteModal()">&times;</span>
		</div>	
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="charCountDisplay"></div>
			<textarea id="deletionReason" rows="3" placeholder="Enter reason for Deletion" class="form-control"></textarea>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-12 text-center">
				<button id="submitDelete" class="mt-2 btn btn-success btn-sm text-center mb-1 px-4">Delete</button>
			</div>
		</div>
		</div>
	</div>
</div>

<!-- Add this edit modal-like HTML -->
<div id="editModal" class="modal">
	<div class="modal-dialog modal-dialog-centered modal-xl">
	<div class="modal-content px-2" style="width: 100%">
		<div class="d-flex justify-content-between align-items-center">
			<span class="close" onclick="closeEditModal()">&times;</span>
			<h5 class="modal-title pl-3 mb-0">Edit</h5>
		</div>	
		<div id="formContainer" style="height: 60vh; overflow-y: scroll;overflow-x:hidden"></div>
		<div class="mt-4 text-right">
			<button id="submitEdit" class="modal-title mb-2 btn btn-dark">Submit</button>
		</div>
	</div>
	</div>
</div>

<!-- Main content -->
<div class="main-content">
	
	<div class="p-4">
	<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="card">
                    <div class="card-header bg-dark p-1 cursor" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                       <div class="d-flex justify-content-between align-items-center">
                        <div class="cursor font-18px"> <h2 class="mb-0">Apply Filters - <?php echo $form_details['title']; ?></h2></div>
                        <div class="cursor"><img src="<?php echo base_url(); ?>includeout/images/chevron.png" style="height:24px;"></div>
                       </div>
                    </div>
					<div class="card-body collapse p-1"  id="collapseExample">
                        <div class="row">
							<div class="col-sm-12 col-md-3 col-lg-3">
								<div class="form-group">
									<label for="worldRegion">Select World Region</label>
									<select id="selectWorldRegion" name="selectWorldRegion" class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select World Region">
										<?php foreach ($world_region as $key => $wr) { ?>
											<option value="<?php echo $wr['id']; ?>"><?php echo $wr['world_region_name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

							 <div class="col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group mb-1">
									<label class="">Select Country</label><br>
									<select class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select Country" id="selectCountry" name="selectCountry">
									</select>
                                </div>
                            </div>

                        	<div class="col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group mb-0">
									<label class="">Select Project </label><br>
									<select class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select Project" id="selectProject" name="selectProject">
																			
									</select>
								</div>
                            </div>

                            <div class="col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group mb-0">
									<label class="">Select Site </label><br>
									<select class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select Site" id="selectSite" name="selectSite">
									</select>
								</div>
                            </div>
                            
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group mb-0">
									<label class="">Select date </label><br>
									<input type="text" class="form-control daterange_form" name="daterange" value="" autocomplete="off" />
								</div>
                            </div>

                            <div class="col-sm-12 col-md-2 col-lg-2">
                                <button class="btn btn-primary w-100 mt-28px" id="filter_submit">Submit</button>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>

		<!-- Import map view -->
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.0.4/pako.min.js"></script>

		<!-- Tabular Data -->
		<div class="card mt-20">			
			<div class="card-header d-flex justify-content-end">
				<button type="button" class="btn btn-sm btn-primary"  id="export_sub" onclick="exportXcel()" style="margin: 0 20px 0 0">Export data</button>
				<button type="button" class="btn btn-sm btn-primary" id="export_zlib" onclick="exportZlib()" >Export .zlib Data</button>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="d-flex justify-content-between align-items-center px-2">
						<div>
							<ul class="nav nav-tabs allTabs" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<button class="nav-link active cursor" id="submitted-tab" data-toggle="tab" data-target="#submitted" 
									type="button" role="tab" aria-controls="submitted" aria-selected="true">Submitted</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link cursor" id="approved-tab" data-toggle="tab" data-target="#approved" 
									type="button" role="tab" aria-controls="approved" aria-selected="false">Approved</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link cursor" id="rejected-tab" data-toggle="tab" data-target="#rejected" 
									type="button" role="tab" aria-controls="rejected" aria-selected="false">Rejected</button>
								</li>
							</ul>
						</div>
						<div class="d-flex justify-content-between align-items-center">
							<div id="validation" class="text-right mr-3 d-none">
								<button type="button" class="btn btn-sm btn-success verify ml-2" data-status="1" id="approve">Approve</button>
								<button type="button" class="btn btn-sm btn-danger verify ml-2" data-status="0" id="reject">Reject</button>
								<button type="button" class="btn btn-sm btn-danger delete ml-2" data-status="delete" id="delete">Delete</button>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12">
									<div class="d-flex align-items-center px-2">
										<div class="upload-text" id="total_surveys">
											<h4 class="mb-0">Total uploads : 0</h4>
										</div>
										<div class="border-right line mx-1"></div>
										<div class="pending-text" id="pending_surveys">
											<h4 class="mb-0">Pending : 0</h4>
										</div>
										<div class="border-right line mx-1"></div>
										<div class="approved-text" id="approved_surveys">
											<h4 class="mb-0">Approved : 0</h4>
										</div>
										<div class="border-right line mx-1"></div>
										<div class="reject-text" id="rejected_surveys">
											<h4 class="mb-0">Rejected : 0</h4>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="card p-10 border-0 boxshadow-none">
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
									<div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12">
										<div class="exportContainer hidden"></div>
										<div class="table-responsive" style="height: 530px;overflow-y: scroll;">
										<table class="table table-bordered table-hover m-0 tbl_submitted">
											<thead id="submited_head"></thead>
											<tbody id="submited_body">
										</table>
									</div>
										<div class="submited_pagination" id="submited_pagination"></div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">									
									<div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12">
										<div class="exportContainer hidden"></div>
										<div class="table-responsive" style="height: 530px;overflow-y: scroll;">
										<table class="table table-bordered table-hover m-0 tbl_submitted">
											<thead id="approved_head"></thead>
											<tbody id="approved_body">
										</table>
									</div>
										<div class="submited_pagination" id="approved_pagination"></div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
									<div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12">
										<div class="exportContainer hidden"></div>
										<div class="table-responsive" style="height: 530px;overflow-y: scroll;">
										<table class="table table-bordered table-hover m-0 tbl_submitted">
											<thead id="rejected_head"></thead>
											<tbody id="rejected_body">
										</table>
									</div>
										<div class="submited_pagination" id="rejected_pagination"></div>
										</div>
									</div>
								</div>
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
		<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
		<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
		<!-- <script src="<?php echo base_url(); ?>include/js/bootstrap.min.js" ></script> -->

	</div>
</div>
<!-- /Main content -->
<div class="modal" id="ImgModal" style="padding-right: 0px;padding-top: 400px;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" id="closeImage">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div id="img_element" style="height: auto; width: 100%;text-align:center"></div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>include/js/pagination.js"></script>
<script src="<?php echo base_url(); ?>include/js/xlsx.full.min.js"></script>

<script>
	$(function() {
		$('input[name="daterange"]').daterangepicker({
			opens: 'left',
			locale: {
				format: 'YYYY-MM-DD',
				cancelLabel: 'Clear'
			}
		}, function(start, end, label) {
			console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});
		$('input[name="daterange"]').val('');

		$('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
			$('input[name="daterange"]').val('');
		});
	});

	$('#closeImage').on('click', function(){
		$("#ImgModal").modal("hide"); 
	});
</script>

<script type="text/javascript">
	
	let submittedIds = [];
	let approvedIds = [];
	let rejectedIds = [];
	let edited_survey_id = 0;
	let fields = [];

	let submitedData = [];
	let edit_survey_data = [];
	let lookUpData = {};
	lookUpData = {
        sites: <?php echo json_encode($sites); ?>,
        projects: <?php echo json_encode($projects); ?>,
        countries: <?php echo json_encode($countries); ?>,
        majorRegions: <?php echo json_encode($major_region); ?>,
        minorRegion: <?php echo json_encode($minor_region); ?>
    };
    lookUpData.country_list 	= <?php echo json_encode($country_list); ?>;
	lookUpData.state_list 		= <?php echo json_encode($state_list); ?>;
	lookUpData.district_list 	= <?php echo json_encode($district_list); ?>;
	lookUpData.majorRegion_list = <?php echo json_encode($majorRegion_list); ?>;
	lookUpData.minorRegion_list = <?php echo json_encode($minorRegion_list); ?>;
	lookUpData.communities_type_list = <?php echo json_encode($communities_type_list); ?>;
	lookUpData.season_list 		= <?php echo json_encode($season_list); ?>;
	lookUpData.units_list 		= <?php echo json_encode($units_list); ?>;
	lookUpData.gender_list 		= <?php echo json_encode($gender_list); ?>;
	lookUpData.income_activities_list = <?php echo json_encode($income_activities_list); ?>;
	lookUpData.currency_list 	= <?php echo json_encode($currency_list); ?>;

	var surveyName = '<?php echo $form_details['title']; ?>';

	$(function() {
        $('.selectpicker').selectpicker({
            liveSearch: true,  // Enables live search
            actionsBox: true,  // Adds 'Select All' and 'Deselect All' checkboxes
        });

		$('#selectWorldRegion').change(function() {			
			$('#selectCountry').empty();
			$('#selectCountry').selectpicker('refresh');
			$('#selectProject').empty();
			$('#selectProject').selectpicker('refresh');
			$('#selectSite').empty();
			$('#selectSite').selectpicker('refresh');
			$.ajax({
				url: "<?php echo base_url(); ?>reports/getCountryList",
				data: {
					world_region_id : $(this).val()
				},
				type: "POST",
				dataType: "JSON",
				error: function() {
					$.toast({
						heading: 'Error!',
						text: 'Something went wrong!',
						icon: 'error'
					});
				},
				success: function(response) {
					if (response.status == 1) {
						var OPTIONS = '';
						response.countryList.forEach(function(country, index){
                            OPTIONS += '<option value="'+country.country_id+'">'+country.name+'</option>';
                        });

						$('#selectCountry').html(OPTIONS);
						$('#selectCountry').selectpicker('refresh');
					}
				}
			});
		});

		$('#selectCountry').change(function() {
			$('#selectProject').empty();
			$('#selectProject').selectpicker('refresh');
			$('#selectSite').empty();
			$('#selectSite').selectpicker('refresh');
			$.ajax({
				url: "<?php echo base_url(); ?>reports/getProjectsList",
				data: {
					world_region_id : $('#selectWorldRegion').val(),
					country_id : $(this).val()
				},
				type: "POST",
				dataType: "JSON",
				error: function() {
					$.toast({
						heading: 'Error!',
						text: 'Something went wrong!',
						icon: 'error'
					});
				},
				success: function(response) {
					if (response.status == 1) {
						var OPTIONS = '';
						response.projectInfo.forEach(function(project, index){
                            OPTIONS += '<option value="'+project.id+'">'+project.project_name+'</option>';
                        });

						$('#selectProject').html(OPTIONS);
						$('#selectProject').selectpicker('refresh');
					}
				}
			});
		});

		$('#selectProject').change(function() {
			$('#selectSite').empty();
			$('#selectSite').selectpicker('refresh');
			$.ajax({
				url: "<?php echo base_url(); ?>reports/getSitesList",
				data: {
					world_region_id : $('#selectWorldRegion').val(),
					country_id : $('#selectCountry').val(),
					project_id : $(this).val()
				},
				type: "POST",
				dataType: "JSON",
				error: function() {
					$.toast({
						heading: 'Error!',
						text: 'Something went wrong!',
						icon: 'error'
					});
				},
				success: function(response) {
					if (response.status == 1) {
						var OPTIONS = '';
						response.sitesList.forEach(function(site, index){
                            OPTIONS += '<option value="'+site.id+'">'+site.site_name+'</option>';
                        });

						$('#selectSite').html(OPTIONS);
						$('#selectSite').selectpicker('refresh');
					}
				}
			});
		});
		call_apis();

        let isEditing = false; // Track whether we are editing a project

		$('#filter_submit').click(function(event) {
			event.preventDefault();  // Prevent the default form submit behavior
			call_apis();
		});
	});

	function openImgPopup(env){
		var img = env.target.dataset.imgUrl;
		$('#ImgModal').modal('show');
		setTimeout(() => {
			$('#img_element').html('<img src="'+img+'" class="img-fit" />');
		}, 500)
	}

	// Load data on season change
	/* $('body').on('change', '[name="season"]', function(event) {
		call_apis();
	}); */

	function call_apis(){
		get_records_count();
		generate_survey_table(1, 50, 'submited_head', 'submited_body', 'submit', lookUpData)
		generate_survey_table(1, 50, 'approved_head', 'approved_body', 'approve', lookUpData)
		generate_survey_table(1, 50, 'rejected_head', 'rejected_body', 'reject', lookUpData)
	}

	function get_record_edit(rowId){
		// return;
		edited_survey_id = rowId
		let row_data;
		$('#editModal').fadeIn();
		// debugger;
		for(let i = 0; i < edit_survey_data.length; i++){
			if(edit_survey_data[i].id ==  rowId){
				row_data = edit_survey_data[i];
				// Call the function and append the generated HTML to the container using jQuery
				$('#formContainer').html(generateEditableForm(row_data));
				break;
			}
		}
	}

	// Function to generate HTML from the object using jQuery
	function generateEditableForm(data) {
		var form = $('<form id="edited_form" class="p-3">');

		for (var key in data) {
			if (data.hasOwnProperty(key) && key.indexOf('field_') === 0) {
				let number_key = number_extractor(key)
				let req_obj = {}
				for(let i = 0; i < fields.length; i++){
					if(fields[i].field_id ==  number_key){
						req_obj = fields[i];
						break;
					}
				}
				if (req_obj.label ) {
					var label = $('<label>').attr('for', key).text(req_obj.label);
					var input;

					if (req_obj.label && req_obj.type == "file") {
						var label = $('<label class="col-md-12 col-lg-12">').attr('for', key).text(req_obj.label);

						// Create a container div to hold existing images
						var imageContainer = $('<div>').addClass('form-group d-flex');

						for (var i = 0; i < (data?.[key] || []).length; i++) {
							var fileName = data[key][i];
							var imageTag = $('<img>').attr({ src: '<?php echo base_url(); ?>uploads/survey/' + fileName, alt: fileName, class: 'image-preview img-thumbnail' });
							// var imageTag = $('<img>').attr({ src: 'http://44.231.57.147/ancar/uploads/survey/' + fileName, alt: fileName, class: 'image-preview img-thumbnail' });
							imageContainer.append($('<div>').append(imageTag));
						}

						form.append($('<div>').addClass('form-group row').append(label, imageContainer));

						// Add "Choose File" input for additional file uploads
						// var fileInput = $('<input>').attr({ type: 'file', class: 'form-control col-sm-12 col-md-9 pr-4', id: key, name: key, multiple: true });
						// form.append($('<div>').addClass('form-group row').append(fileInput));
					} 
					else if(req_obj.label && req_obj.type == "select"){
					} 
					else if(req_obj.label && req_obj.type == "date"){
						let dateValue;

						for(let i = 0; i < edit_survey_data.length; i++){
							if(edit_survey_data[i].id == edited_survey_id){
								dateValue = edit_survey_data[i]['field_' + req_obj.field_id];
								break;
							}
						}

						var input = $('<input>').attr({
							type: 'date',
							class: 'form-control col-sm-12 col-md-9 pr-4',
							id: key,
							name: key,
							value: dateValue,
						});

						var label = $('<label class="col-sm-12 col-md-3">').attr('for', key).text(req_obj.label);
						if (req_obj.required == 1) {
							input.attr('required', 'required');
							label.append('<span class="required-star">*</span>');
						}
						form.append($('<div>').addClass('form-group row').append(label, input));
					} 
					else if(req_obj.label && req_obj.type == "lkp_country"){
						var select = $('<select>').attr({ class: 'form-control col-sm-12 col-md-9 pr-4 ae_zone_id', id: key, name: key });
						
						ae_zone_list.forEach(function(option) {
							var optionElement = $('<option>').attr('value', option.id).text(option.ae_zone);
							
							if (data[key] == option.id) {
								optionElement.attr('selected', 'selected');
							}
							
							select.append(optionElement);
						});
						
						var label = $('<label class="col-sm-12 col-md-3">').attr('for', key).text(req_obj.label);
						if (req_obj.required == 1) {
							select.attr('required', 'required');
							label.append('<span class="required-star">*</span>');
						}
						form.append($('<div>').addClass('form-group row').append(label, select));
					} 
					else if(req_obj.label && req_obj.type == "lkp_state"){
						var select = $('<select>').attr({ class: 'form-control col-sm-12 col-md-9 pr-4 region_id', id: key, name: key });
						
						region_list.forEach(function(option) {
							var optionElement = $('<option>').attr('value', option.id).text(option.region);
							
							if (data[key] == option.id) {
								optionElement.attr('selected', 'selected');
							}
							
							select.append(optionElement);
						});
						
						var label = $('<label class="col-sm-12 col-md-3">').attr('for', key).text(req_obj.label);
						if (req_obj.required == 1) {
							select.attr('required', 'required');
							label.append('<span class="required-star">*</span>');
						}
						form.append($('<div>').addClass('form-group row').append(label, select));
					} 
					else if(req_obj.label && req_obj.type == "lkp_district"){
						var select = $('<select>').attr({ class: 'form-control col-sm-12 col-md-9 pr-4 department_id', id: key, name: key });
						
						department_list.forEach(function(option) {
							var optionElement = $('<option>').attr('value', option.id).text(option.department);
							
							if (data[key] == option.id) {
								optionElement.attr('selected', 'selected');
							}
							
							select.append(optionElement);
						});
						
						var label = $('<label class="col-sm-12 col-md-3">').attr('for', key).text(req_obj.label);
						if (req_obj.required == 1) {
							select.attr('required', 'required');
							label.append('<span class="required-star">*</span>');
						}
						form.append($('<div>').addClass('form-group row').append(label, select));
					} 
					else if(req_obj.label && req_obj.type == "lkp_commune"){
						var select = $('<select>').attr({ class: 'form-control col-sm-12 col-md-9 pr-4 commune_id', id: key, name: key });
						
						commune_list.forEach(function(option) {
							var optionElement = $('<option>').attr('value', option.id).text(option.commune);
							
							if (data[key] == option.id) {
								optionElement.attr('selected', 'selected');
							}
							
							select.append(optionElement);
						});
						
						var label = $('<label class="col-sm-12 col-md-3">').attr('for', key).text(req_obj.label);
						if (req_obj.required == 1) {
							select.attr('required', 'required');
							label.append('<span class="required-star">*</span>');
						}
						form.append($('<div>').addClass('form-group row').append(label, select));
					} 
					else if(req_obj.label && req_obj.type == "lkp_project_program"){
					}
					else if(req_obj.label && req_obj.type == "radio-group"){
					}					
					else {
						if( req_obj.label.toLowerCase() != 'other specify'){
							var label = $('<label class="col-sm-12 col-md-3">').attr('for', key).text(req_obj.label);
							var input = $('<input>').attr({ type: 'text', class: 'form-control col-sm-12 col-md-9 pr-4', id: key, name: key, value: data[key] });
							if (req_obj.required == 1) {
								input.attr('required', 'required');
								label.append('<span class="required-star">*</span>');
							}
							form.append($('<div>').addClass('form-group row').append(label, input));
						}
					}

				}

			}
		}

		return form;
	}

	function validateForm() {
		var form = $('#edited_form');
		// Check if all required fields are filled
		var isValid = true;
		form.find('[required]').each(function() {
			if (!$(this).val()) {
				isValid = false;
				return false; // Exit the loop if any required field is empty
			}
		});

		if (!isValid) {
			alert('Please fill in all required fields.');
		}

		return isValid;
	}

	function number_extractor(inputString){
		var match = inputString.match(/\d+/);
		if (match) {
			var extractedNumber = parseInt(match[0], 10);
			return extractedNumber;
		} else {
			console.log('No number found in the string.');
		}

	}
	
	$(document).ready(function () {
		$("#back_button").click(function () {
			window.close();
		});
	});

	$(document).ready(function () {
		$('#reject').click(function () {
			openModal();
		});

		$('.close, .modal').click(function () {
			closeModal();
		});

		// Prevent modal from closing when clicking inside the modal content
		$('.modal-content').click(function (event) {
			event.stopPropagation();
		});

		var rejectionReason = $('#rejectionReason');
		var submitRejection = $('#submitRejection');
		var charCountDisplay = $('.charCountDisplay');

		// Initially disable the button
		submitRejection.prop('disabled', true);

		rejectionReason.on('input', function () {
			var charCount = rejectionReason.val().length;

			charCountDisplay.text(charCount + '/260');

			// Enable the button only if there is some content in the textarea
			if (charCount > 0 && charCount <= 260) {
				submitRejection.prop('disabled', false);
			} else {
				submitRejection.prop('disabled', true);
			}
		});

		submitRejection.on('click', function () {
			var reason = rejectionReason.val();
			perform_validate_action(reason, 'reject');
			closeModal();
		});
	});			

	function openModal() {
		$('#rejectModal').fadeIn();		
		var count = $('#rejectionReason').val().length;		
		if (count > 0 && count <= 260) {
			$('#submitRejection').prop('disabled', false);
		} else {
			$('#submitRejection').prop('disabled', true);
		}
	}

	function closeModal() {
		$('#rejectModal').fadeOut();
		$('#rejectionReason').val('');
		$('.charCountDisplay').text('');
	}

	$(document).ready(function () {

		var deletionReason = $('#deletionReason');
		var submitDelete = $('#submitDelete');
		var charCountDisplay = $('.charCountDisplay');

		// Initially disable the button
		submitDelete.prop('disabled', true);

		deletionReason.on('input', function () {
			var charCount1 = deletionReason.val().length;

			charCountDisplay.text(charCount1 + '/260');

			// Enable the button only if there is some content in the textarea
			if (charCount1 > 0 && charCount1 <= 260) {
				submitDelete.prop('disabled', false);
			} else {
				submitDelete.prop('disabled', true);
			}
		});

		submitDelete.on('click', function () {
			var reason = deletionReason.val();
			perform_validate_action(reason, 'delete');
			closeDeleteModal();
		});
		
		$('#delete').click(function () {
			openDeleteModal();
		});

		$('.close, .modal').click(function () {
			closeDeleteModal();
		});

		// Prevent modal from closing when clicking inside the modal content
		$('.modal-content').click(function (event) {
			event.stopPropagation();
		});

	});				

	function openDeleteModal() {
		$('#deleteModal').fadeIn();		
		var count = $('#deletionReason').val().length;		
		if (count > 0 && count <= 260) {
			$('#submitDelete').prop('disabled', false);
		} else {
			$('#submitDelete').prop('disabled', true);
		}
	}

	function closeDeleteModal() {
		$('#deleteModal').fadeOut();
		$('#deletionReason').val('');
		$('.charCountDisplay').text('');
	}	

	$(document).ready(function () {

		var submitEdit = $('#submitEdit');
		
		submitEdit.on('click', function () {
			if (validateForm()) {
				// Serialize the form data as an array and convert it to an object
				let formData = $('#edited_form').serializeArray().reduce(function (obj, item) {
									obj[item.name] = item.value;
									return obj;
								}, {});
				let post_data = {
					formData: formData,
					selectedId: edited_survey_id,
					aezId: $('.ae_zone_id').val(),
					regionId: $('.region_id').val(),
					deptId: $('.department_id').val(),
					communeId: $('.commune_id').val(),
					// villageId: $('.village_id').val(),
				}

				$.ajax({
					url: "<?php echo base_url(); ?>reports/edit_survey/<?php echo $this->uri->segment(3); ?>",
					data: post_data,
					type: "POST",
					dataType: "JSON",
					success: function (response) {
						call_apis()
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});

				closeEditModal();
			}
		});


		$('.close, .modal').click(function () {
			closeEditModal();
		});

		// Prevent modal from closing when clicking inside the modal content
		$('.modal-content').click(function (event) {
			event.stopPropagation();
		});

	});				

	function openEditModal() {
		$('#editModal').fadeIn();
	}

	function closeEditModal() {
		$('#editModal').fadeOut();
	}

	function perform_validate_action(reason, type) {
		
		// Check which tab is currently active
		var activeTabId = $('#myTab .nav-item .active').attr('id');
		let selectedIds = [];

		if(activeTabId == "submitted-tab"){
			selectedIds = submittedIds;
		} else if(activeTabId == "approved-tab"){
			selectedIds = approvedIds;
		}else if(activeTabId == "rejected-tab"){
			selectedIds = rejectedIds;
		}

		var query_data = {
			selectedIds: selectedIds,
			reason: reason,
			type: type,
		};

		$.ajax({
			url: "<?php echo base_url(); ?>reports/validate_record_from_surveys/<?php echo $this->uri->segment(3); ?>",
			data: query_data,
			type: "POST",
			dataType: "JSON",
			success: function (response) {
				call_apis();
				submittedIds = [];
				approvedIds = [];
				rejectedIds = [];
				$('#validation').toggleClass('d-none')
			}
		});
	}

	$(document).ready(function () {
		$('#approve').click(function () {
			openApproveModal();
		});

		$('.close, .modal').click(function () {
			closeApproveModal();
		});

		// Prevent modal from closing when clicking inside the modal content
		$('.modal-content').click(function (event) {
			event.stopPropagation();
		});

		$('#submitApprove').click(function () {
			perform_validate_action('', 'approve');
			closeApproveModal();
		});
	});

	function openApproveModal() {
		$('#approveModal').fadeIn();
	}

	function closeApproveModal() {
		$('#approveModal').fadeOut();
	}

	/* pagination start*/
	const onsubmitedPagination = (event) => { 
		// get_village_survey(+event.currentPage,+event.recordsPerPage);		
		generate_survey_table(+event.currentPage,+event.recordsPerPage, 'submited_head', 'submited_body', 'submit', lookUpData)
  	}

	const onapprovedPagination = (event) => { 
		generate_survey_table(+event.currentPage,+event.recordsPerPage, 'approved_head', 'approved_body', 'approve', lookUpData)
	}

	const onrejectedPagination = (event) => { 
		generate_survey_table(+event.currentPage,+event.recordsPerPage, 'rejected_head', 'rejected_body', 'reject', lookUpData)
	}
	const submited_pagination = new Pagination('#submited_pagination',onsubmitedPagination);
	const approved_pagination = new Pagination('#approved_pagination',onapprovedPagination);
	const rejected_pagination = new Pagination('#rejected_pagination',onrejectedPagination);

	$('#submitted-tab').on('click', function(){
		$('#validation').addClass('d-none');		
		$('#approve').removeClass('d-none');
		$('#reject').removeClass('d-none');
		generate_survey_table(1, 50, 'submited_head', 'submited_body', 'submit', lookUpData)
	})

	$('#approved-tab').on('click', function(){
		$('#validation').addClass('d-none');
		$('#approve').addClass('d-none');
		$('#reject').removeClass('d-none');
		generate_survey_table(1, 50, 'approved_head', 'approved_body', 'approve', lookUpData)
	})

	$('#rejected-tab').on('click', function(){
		$('#validation').addClass('d-none');
		$('#approve').removeClass('d-none');
		$('#reject').addClass('d-none');
		generate_survey_table(1, 50, 'rejected_head', 'rejected_body', 'reject', lookUpData)
	})

	// function get_village_survey(pageNo =1, recordperpage = 100){
	function generate_survey_table(pageNo = 1, recordperpage = 100, head_id, body_id, table_type, lookUpData){
		// var season = $('select[name="season"]').val();
		
		var dateRange = $('input[name="daterange"]').val(); // This will give you a string like '2024-11-01 - 2024-11-21'
		let start_date;
		let end_date;
		if (dateRange) {
			var dates = dateRange.split(' - '); // Split by the separator ' - '
			start_date = dates[0]; // First date (start)
			end_date = dates[1];   // Second date (end)
		}

		var query_data = {
			// season: season,
			pagination:{pageNo,recordperpage},
			type: table_type,
			worldRegionIds : $('#selectWorldRegion').val(),
			countryIds: $('#selectCountry').val(),
			projectIds: $('#selectProject').val(),
			siteIds: $('#selectSite').val(),
			startDate: start_date,
			endDate: end_date,
		};

		var imageLoader = `<div class="loaders">
				<div class="d-flex flex-column align-items-center justify-content-center loader-height" >
					<img class="map_icon" src="<?php echo base_url(); ?>include/img/Loader_new.svg" alt="loader">
					<p class="text-color"><strong> Loading...</strong></p>
				</div>
			</div>`;
		$(`#${body_id}`).html(imageLoader);
		
		$.ajax({
			url: "<?php echo base_url(); ?>reports/get_village_survey/<?php echo $this->uri->segment(3); ?>",
			data: query_data,
			type: "POST",
			dataType: "JSON",
			error: function() {
				$(`#${body_id}`).html('<h4 class="text-danger">No data found</h4>');
			},
			success: function(response) {
				if (response.status == 0) {
					$.toast({
						heading: 'Error!',
						text: response.msg,
						icon: 'error'
					});
					$(`#${body_id}`).html('<h4 class="text-danger">No data found</h4>');
					return false;
				}
				var role = response.user_role;
				fields = response.fields;
				submitedData = response.survey_data;
				
				if(table_type == 'submit'){
					edit_survey_data = response.survey_data;
				}
				submitedDatafile = response.survey_data;
				
				var td_count = 0;
				var tableHead = `<tr style="position: sticky;top: -1px;background: #fff;">`;
				
				if (<?php echo ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 6) ? 'true' : 'false'; ?>) {
					if(table_type == "submit"){
						tableHead += `<th id="${table_type}_all" class="text-left tcolor" nowrap><input id="submitAll" type="checkbox" class="checkall_sub"></th>`;
					} else if(table_type == "approve"){
						tableHead += `<th id="${table_type}_all" class="text-left tcolor" nowrap><input id="approveAll" type="checkbox" class="checkall_sub"></th>`;

					} else if(table_type == "reject"){
						tableHead += `<th id="${table_type}_all" class="text-left tcolor" nowrap><input id="rejectAll" type="checkbox" class="checkall_sub"></th>`;
					}
				}

				tableHead += `<th>S.No.</th>`;
				
				for (const key in fields) {
					const label = fields[key]['label'];
					const type = fields[key]['type'];
					if(label != "Declaration" && label != "General"){
						td_count++;
						if (type == 'kml') {
							if(survey_id == 5){
								tableHead += `<th>`+label+`</th>`;
								tableHead += `<th>KML Details</th>`;
							}else{
								tableHead += `<th>`+label+`</th>`;	
							}
						}else{
							tableHead += `<th>`+label+`</th>`;
						}
					}
				}
				tableHead += `<th>Verified by</th><th>Uploaded by</th><th>Uploaded date & time (GMT)</th>`;

				$(`#${head_id}`).html(tableHead);

				$(`#${body_id}`).html("");
				
				if(submitedData.length > 0){
					var loginuserId = <?php echo $this->session->userdata('login_id'); ?>;
					var loginuserRole = <?php echo $this->session->userdata('role'); ?>;
					var tableBody ="";
					var count = (pageNo*recordperpage-recordperpage+1);
					for (let k = 0; k < submitedData.length; k++) {
						var data_id = submitedData[k]['data_id'];
						tableBody = `<tr class="`+submitedData[k]['id']+` text-left" data-id="`+submitedData[k]['id']+`">`;
						if (<?php echo ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 6) ? 'true' : 'false'; ?>) {
							tableBody += `<td class="text-center"><input id="`+submitedData[k]['id']+`" type="checkbox" name="check_sub[]" class="${table_type}Checkbox" value="`+submitedData[k]['id']+`"`;
								tableBody += `</td>`
						}
						tableBody += `<td>`+ count++ +`</td>`;
						
						for (let key = 0; key < fields.length; key++) {
							const label = fields[key]['label'];
							const field = 'field_'+fields[key]['field_id'];
							const type = fields[key]['type'];
							// const subType = fields[key]['sub_type'];
							
							if(label != "Declaration" && label != "General"){
								if (type == 'file') {
									tableBody += `<td>`;
										if(submitedData[k][field] == null || submitedData[k][field] == 'N/A'){
											tableBody += `N/A`;
										}else{
											
											let image_count = 0;
											for (let ikey = 0; ikey < submitedData[k][field].length; ikey++) {
												image_count++;
												tableBody += `<a class="img_link text-primary" data-img-url="<?php echo base_url(); ?>uploads/survey/`+ submitedData[k][field][ikey] +`" onClick="openImgPopup(event);" href="javascript:void(0);">View Image`+image_count+`,<br/></a>`;
											}
										}
									tableBody += `</td>`;
								}else if (type == 'group') {
									// tableBody += `<td><a class="text-primary" target="_blank" href="<?php echo base_url(); ?>reports/groupData/<?php echo $this->uri->segment(3)?>/`+ fields[key]['field_id']+`/`+ submitedData[k]['data_id'] +`">View Data</a></td>`;
									tableBody += `<td><a class="text-primary" target="_blank" href="<?php echo base_url(); ?>reports/edit_groupdata_info/<?php echo $this->uri->segment(3); ?>/`+submitedData[k]['data_id']+`/`+fields[key]['field_id']+`">Show Group Data</a></td>`;
								}else if (type == 'lkp_country') {									
									tableBody += `<td>`;
									if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
											tableBody += `N/A`;
									}else{
										for (dkey in lookUpData.country_list){	
											if(lookUpData.country_list[dkey]['country_id'] == submitedData[k][field]){
												tableBody += lookUpData.country_list[dkey]['name'];
												break;
											}
										}
									}
									tableBody += `</td>`;
								}else if (type == 'lkp_major_region') {									
									tableBody += `<td>`;
									if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
											tableBody += `N/A`;
									}else{
										for (dkey in lookUpData.majorRegion_list){	
											if(lookUpData.majorRegion_list[dkey]['id'] == submitedData[k][field]){
												tableBody += lookUpData.majorRegion_list[dkey]['major_region_name'];
												break;
											}
										}
									}
									tableBody += `</td>`;
								}else if (type == 'lkp_minor_region') {									
									tableBody += `<td>`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.minorRegion_list){	
												if(lookUpData.minorRegion_list[dkey]['id'] == submitedData[k][field]){
													tableBody += lookUpData.minorRegion_list[dkey]['minor_region_name'];
													break;
												}
											}
										}
									tableBody += `</td>`;
								}else if (type == 'lkp_communities_type') {									
									tableBody += `<td class="cell_edit ${type}" id="${field}">`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.communities_type_list){	
												if(lookUpData.communities_type_list[dkey]['id'] == submitedData[k][field]){
													tableBody += lookUpData.communities_type_list[dkey]['name'];
													break;
												}
											}
										}
										tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
									tableBody += `</td>`;
								}else if (type == 'lkp_add_season') {
									tableBody += `<td class="cell_edit ${type}" id="${field}">`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.season_list){	
												if(lookUpData.season_list[dkey]['data_id'] == submitedData[k][field]){
													tableBody += lookUpData.season_list[dkey]['season_name'];
													break;
												}
											}
										}
										tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
									tableBody += `</td>`;
								}else if (type == 'lkp_units') {
									tableBody += `<td class="cell_edit ${type}" id="${field}">`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.units_list){	
												if(lookUpData.units_list[dkey]['unit_id'] == submitedData[k][field]){
													tableBody += lookUpData.units_list[dkey]['unit_name'];
													break;
												}
											}
										}
										tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
									tableBody += `</td>`;
								}else if (type == 'lkp_gender') {
									tableBody += `<td class="cell_edit ${type}" id="${field}">`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.gender_list){	
												if(lookUpData.gender_list[dkey]['gender_id'] == submitedData[k][field]){
													tableBody += lookUpData.gender_list[dkey]['gender_des'];
													break;
												}
											}
										}
										tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
									tableBody += `</td>`;
								}else if (type == 'lkp_income_activities') {
									tableBody += `<td class="cell_edit ${type}" id="${field}">`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.income_activities_list){	
												if(lookUpData.income_activities_list[dkey]['id'] == submitedData[k][field]){
													tableBody += lookUpData.income_activities_list[dkey]['name'];
													break;
												}
											}
										}
										tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
									tableBody += `</td>`;
								}else if (type == 'lkp_currency') {
									tableBody += `<td id="${field}">`;
										if(submitedData[k][field] == 'N/A' || submitedData[k][field] == null || submitedData[k][field] == ''){
												tableBody += `N/A`;
										}else{
											for (dkey in lookUpData.currency_list){	
												if(lookUpData.currency_list[dkey]['id'] == submitedData[k][field]){
													tableBody += lookUpData.currency_list[dkey]['name'];
													break;
												}
											}
										}
										// tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
									tableBody += `</td>`;
								}else{
									if (table_type == 'submit' &&  (loginuserRole == 1 || loginuserRole == 6|| loginuserId == submitedData[k]['user_id'])) {
										if(type == 'text' || type == 'number' || type == 'select' || type == 'textarea') {
											tableBody += `<td class="cell_edit ${type}" id="${field}">`;
												tableBody += submitedData[k][field] == null ? `N/A` : submitedData[k][field];
												tableBody += '<span class="ml-2 fa fa-edit text-primary"></span>';
											tableBody += `</td>`;
										} else {
											tableBody += `<td>`;
												tableBody += submitedData[k][field] == null ? `N/A` : submitedData[k][field];
											tableBody += `</td>`;
										}
									} else{
										tableBody += `<td>`;
											tableBody += submitedData[k][field] == null ? `N/A` : submitedData[k][field];
										tableBody += `</td>`;
									}
								}

							}
						}
						tableBody += `<td>`;
								tableBody += table_type == 'submit' && submitedData[k]['verified_full_name'] == null ? 'Pending' : submitedData[k]['verified_full_name'];
						tableBody += `</td>`;
						tableBody += `<td>`;
								tableBody += submitedData[k]['first_name'] + ' ' + submitedData[k]['last_name'];
						tableBody += `</td>`;
						tableBody += `<td>`;
								tableBody += get_senegal_date(submitedData[k]['datetime']);
						tableBody += `</td>`;
						tableBody += `</tr>`;

						
						$(`#${body_id}`).append(tableBody);
						
					}
		
					$('.' + table_type + 'Checkbox').change(function () {
						var checkboxId = $(this).attr('id');

						if ($(this).hasClass('submitCheckbox')) {
							if ($(this).prop('checked')) {
								submittedIds.push(checkboxId);
							} else {
								submittedIds = submittedIds.filter(id => id !== checkboxId);
							}

							$('#validation').toggleClass('d-none', submittedIds.length === 0);
						} else if ($(this).hasClass('approveCheckbox')) {
							if ($(this).prop('checked')) {
								approvedIds.push(checkboxId);
							} else {
								approvedIds = approvedIds.filter(id => id !== checkboxId);
							}

							$('#validation').toggleClass('d-none', approvedIds.length === 0);
						} else if ($(this).hasClass('rejectCheckbox')) {
							if ($(this).prop('checked')) {
								rejectedIds.push(checkboxId);
							} else {
								rejectedIds = rejectedIds.filter(id => id !== checkboxId);
							}

							$('#validation').toggleClass('d-none', rejectedIds.length === 0);
						}
					});					
					
					$('#submitAll').change(function () {
						var selectAllChecked = $(this).prop('checked');

						$(`#${body_id} .${table_type}Checkbox`).prop('checked', selectAllChecked);

						if (selectAllChecked) {
							submittedIds = $(`#${body_id} .${table_type}Checkbox`).map(function () {
								return this.id;
							}).get();
						} else {
							submittedIds = [];
						}

						$('#validation').toggleClass('d-none', submittedIds.length === 0);
						
					});
					
					$('#approveAll').change(function () {
						var selectAllChecked = $(this).prop('checked');

						$(`#${body_id} .${table_type}Checkbox`).prop('checked', selectAllChecked);

						if (selectAllChecked) {
							approvedIds = $(`#${body_id} .${table_type}Checkbox`).map(function () {
								return this.id;
							}).get();
						} else {
							approvedIds = [];
						}

						$('#validation').toggleClass('d-none', approvedIds.length === 0);
						
					});
					
					$('#rejectAll').change(function () {
						var selectAllChecked = $(this).prop('checked');

						$(`#${body_id} .${table_type}Checkbox`).prop('checked', selectAllChecked);

						if (selectAllChecked) {
							rejectedIds = $(`#${body_id} .${table_type}Checkbox`).map(function () {
								return this.id;
							}).get();
						} else {
							rejectedIds = [];
						}

						$('#validation').toggleClass('d-none', rejectedIds.length === 0);
						
					});

					$("#submited_body").on("click", ".cell_edit", function() {

						if ($(event.target).is("input, select, textarea")) {
							return;
						}					

						// Check if editing is in progress for this cell
						if ($(this).data("isEditing")) {
							return;
						}

						// Set the editing flag to true for this cell
						$(this).data("isEditing", true);

						// Get the current content of the cell
						const cellContent = $(this).text().trim();

						// Get the id attribute of the clicked cell
						const cellId = $(this).attr("id");

						// Get the row id from the closest "tr" element's data-id attribute
						const rowId = $(this).closest("tr").data("id");
						let row_data;
						const cellType = $(this).hasClass("text") ? "text" :
										$(this).hasClass("number") ? "number" :
										$(this).hasClass("textarea") ? "textarea" :
										$(this).hasClass("checkbox-group") ? "checkbox" :										
										$(this).hasClass("time") ? "date-time" :
										$(this).hasClass("date") ? "date" :
										$(this).hasClass("lkp_communities_type") ? "lkp_communities_type" :
										$(this).hasClass("lkp_add_season") ? "lkp_add_season" :
										$(this).hasClass("lkp_units") ? "lkp_units" :
										$(this).hasClass("lkp_gender") ? "lkp_gender" :
										$(this).hasClass("lkp_income_activities") ? "lkp_income_activities" :
										$(this).hasClass("lkp_currency") ? "lkp_currency" : "select";


						let inputField;
						let survey_index;
						let mandatory;

						let lkp_project = false;
						let lkp_site = false;
						if ($(this).hasClass("lkp_project")){
							lkp_project = true;
						}
						if ($(this).hasClass("lkp_site")){
							lkp_site = true;
						}

						for(let i = 0; i < edit_survey_data.length; i++){
							if(edit_survey_data[i].id ==  rowId){
								row_data = edit_survey_data[i];
								survey_index = i;
								inputField = inlineEditRecord(cellType, cellId, cellContent, row_data, lkp_project, lkp_site, lookUpData);
								mandatory = mandatory_field(cellId);
								break;
							}
						}

						// Save the original content in the data-original-content attribute
						$(this).data("original-content", cellContent);

						// Create buttons for submit and cancel
						const submitButton = $("<button class='btn btn-success btn-sm mr-0 ml-2'>").text("Submit").click((event) => {
												const element = $(event.target);
												submitCellEdit(rowId, cellId, element, survey_index, mandatory);
											});
						const cancelButton = $("<button class='btn btn-outline-secondary btn-sm ml-0 mr-2'>").text("Cancel").click(cancelCellEdit);

						// Create a div to hold the input field and buttons
						const actionDiv = $("<div class='d-flex justify-content-between align-items-center'>").append(cancelButton, submitButton);
						const editDiv = $("<div>").append(inputField, actionDiv);

						// Replace the content of the cell with the edit div
						$(this).empty().append(editDiv);

						// Focus on the input field for better user experience
						inputField.focus();
					});
				}else{
					$(`#${body_id}`).html('<tr><td class="nodata" colspan="55"><h5 class="text-danger">No data found</h5></td></tr>');
				}

				const curentPage = pageNo;
				const totalRecordsPerPage = recordperpage;
				const totalRecords= response.total_records;
				const currentRecords = submitedData.length;
				if(table_type == 'submit'){
					submited_pagination.refreshPagination(Number(curentPage || 1),totalRecords,currentRecords, Number(totalRecordsPerPage || 100))
				} else if(table_type == 'approve'){	
					approved_pagination.refreshPagination(Number(curentPage || 1),totalRecords,currentRecords, Number(totalRecordsPerPage || 100))
				} else if(table_type == 'reject'){	
					rejected_pagination.refreshPagination(Number(curentPage || 1),totalRecords,currentRecords, Number(totalRecordsPerPage || 100))
				}
			}
		});
	}
	

	// Function to handle the submit button click
	function submitCellEdit(rowId, cellId, element, survey_index, mandatory) {
		// Find the closest ".cell_edit" ancestor
		const cellEditElement = $(element).closest(".cell_edit");

		// Get the edited value from the input field
		let editedValue;
		let updatedValue;

		// Check if the input is of type select
		if (cellEditElement.find("select").length > 0) {
			editedValue = cellEditElement.find("select").val();
			updatedValue = cellEditElement.find("option:selected").text();
		} else if (cellEditElement.find("input[type='checkbox']").length > 0) {
			editedValue = cellEditElement.find("input[type='checkbox']:checked").map(function() {
				return this.value;
			}).get().join(',');

		} else {
			// For other input types like text, date, number
			updatedValue = editedValue = cellEditElement.find("input").val();
		}

		  // Check if the field is mandatory and the value is empty
		if (mandatory && (editedValue === undefined || editedValue.trim() === '' || editedValue == 'N/A')) {
			alert('This is a mandatory field. Please provide a value.');
			return; // Stop further processing
		}

		let post_data = {
			recordId: rowId,
			field: cellId,
			field_value: editedValue,
		};
		
		if (cellId == 'field_1191') {
			const matchedProject = lookUpData.projects.find(item => item.project_name === editedValue);
			if (matchedProject) {
				post_data['project_id'] = matchedProject.id;
			} else {
				post_data['project_id'] = null; // or handle accordingly
			}
		}
		
		if (cellId == 'field_1192') {
			const matchedProject = lookUpData.sites.find(item => item.site_name === editedValue);
			if (matchedProject) {
				post_data['site_id'] = matchedProject.id;
			} else {
				post_data['site_id'] = null; // or handle accordingly
			}
		}

		$.ajax({
			url: "<?php echo base_url(); ?>reports/edit_survey_field/<?php echo $this->uri->segment(3); ?>",
			data: post_data,
			type: "POST",
			dataType: "JSON",
			success: function(response) {
				edit_survey_data[survey_index][cellId] = editedValue;
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
			}
		});

		// Update the content of the cell with the edited value
		cellEditElement.empty().text(updatedValue);
		cellEditElement.append('<span class="ml-2 fa fa-edit text-primary"></span>');

		// Set the editing flag back to false for this cell
		cellEditElement.data("isEditing", false);
	}

	// Function to handle the cancel button click
	function cancelCellEdit() {
		// Find the closest ".cell_edit" ancestor
		const cellEditElement = $(this).closest(".cell_edit");

		// Get the original content from the data-original-content attribute
		const originalContent = cellEditElement.data("original-content");

		// Restore the original content of the cell
		cellEditElement.empty().text(originalContent);
		cellEditElement.append('<span class="ml-2 fa fa-edit text-primary"></span>')

		// Set the editing flag back to false for this cell
		cellEditElement.data("isEditing", false);
	}

	function mandatory_field(cellId){
		
		let mandatory;
		let number_key = number_extractor(cellId)	
		

		for(let i = 0; i < fields.length; i++){
			if(fields[i].field_id ==  number_key){
				req_obj = fields[i];
				mandatory = fields[i]['required'] == 1 ? true : false
				break;
			}
		}
		return mandatory;
	}

	function inlineEditRecord(cellType, cellId, cellContent, data, lkp_project, lkp_site, lookUpData) {
		let inputField;
		let number_key = number_extractor(cellId)		
		
		let req_obj = {}

		for(let i = 0; i < fields.length; i++){
			if(fields[i].field_id ==  number_key){
				req_obj = fields[i];
				break;
			}
		}
		
		if (req_obj.label && (!lkp_project && !lkp_site)) {
			console.log(cellType);

			switch (cellType) {

				case 'lkp_communities_type':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(lookUpData.communities_type_list, function(index, dropdowninfo) {
						console.log(dropdowninfo);
						var option = $("<option>", { value: dropdowninfo.id, text: dropdowninfo.name });
						if (dropdowninfo.id == data[cellId]) {
							option.prop("selected", true);
							isAnyOptionSelected = true;
						}
						inputField.append(option);
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;

				case 'lkp_add_season':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(lookUpData.season_list, function(index, dropdowninfo) {
						if(dropdowninfo.fgd_ID == data['fgd_ID']){
							var option = $("<option>", { value: dropdowninfo.data_id, text: dropdowninfo.season_name });
							if (dropdowninfo.data_id == data[cellId]) {
								option.prop("selected", true);
								isAnyOptionSelected = true;
							}
							inputField.append(option);
						}
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;

				case 'lkp_units':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(lookUpData.units_list, function(index, dropdowninfo) {
						var option = $("<option>", { value: dropdowninfo.unit_id, text: dropdowninfo.unit_name });
						if (dropdowninfo.unit_id == data[cellId]) {
							option.prop("selected", true);
							isAnyOptionSelected = true;
						}
						inputField.append(option);
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;

				case 'lkp_gender':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(lookUpData.gender_list, function(index, dropdowninfo) {
						var option = $("<option>", { value: dropdowninfo.gender_id, text: dropdowninfo.gender_des });
						if (dropdowninfo.gender_id == data[cellId]) {
							option.prop("selected", true);
							isAnyOptionSelected = true;
						}
						inputField.append(option);
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;

				case 'lkp_income_activities':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(lookUpData.income_activities_list, function(index, dropdowninfo) {
						var option = $("<option>", { value: dropdowninfo.id, text: dropdowninfo.name });
						if (dropdowninfo.id == data[cellId]) {
							option.prop("selected", true);
							isAnyOptionSelected = true;
						}
						inputField.append(option);
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;

				case 'lkp_currency':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(lookUpData.currency_list, function(index, dropdowninfo) {
						var option = $("<option>", { value: dropdowninfo.id, text: dropdowninfo.name });
						if (dropdowninfo.id == data[cellId]) {
							option.prop("selected", true);
							isAnyOptionSelected = true;
						}
						inputField.append(option);
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;


				case 'select':
					inputField = $("<select class='form-control'>");

					var isAnyOptionSelected = false;

					$.each(req_obj.multi_labels, function(index, label) {
						var option = $("<option>", { value: label, text: label });
						if (label == data[cellId]) {
							option.prop("selected", true);
							isAnyOptionSelected = true;
						}
						inputField.append(option);
					});
					if (!isAnyOptionSelected) {
						inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
					}
					break;

				case 'checkbox':
					if (req_obj.multi_labels && req_obj.multi_labels.length > 0) {
						inputField = $("<div class='checkbox-group border' style='height: 200px;overflow-y: scroll;width: 220px;overflow-x: hidden;border: 1px solid #7cc79d;padding: 10px;border-radius: 4px;margin-bottom: 10px;'>");

						$.each(req_obj.multi_labels, function (index, label) {
							var checkbox = $("<input>", { type: 'checkbox', value: label, id: 'checkbox_' + index, name: 'checkbox_group' });

							if (data[cellId] && data[cellId].includes(label)) {
								checkbox.prop("checked", true);
							}
							var labelElement = $("<label>", { for: 'checkbox_' + index, text: label, class:'pl-1' });
							var checkboxContainer = $("<div>").append(checkbox).append(labelElement);

							inputField.append(checkboxContainer);
						});
					}
					break;

				case 'date':
					inputField = $("<input class='form-control'>").attr("type", "date").val(cellContent);
					break;

				case 'date-time':
					// Ensure the time format is "HH:mm"
					let formattedTime = cellContent;
					if (formattedTime) {
						// Add a leading zero if minutes are a single digit
						let parts = formattedTime.split(':');
						if (parts.length === 2 && parts[1].length === 1) {
							formattedTime = `${parts[0]}:0${parts[1]}`;
						}
					}

					// Set the formatted time value to the input field
					inputField = $("<input class='form-control'>").attr("type", "time").val(formattedTime);
					break;

				default:
					// Default to text input for other types
					inputField = $("<input class='form-control'>").attr("type", "text").val(cellContent);
					break;
			}

		} else if (lkp_project) {
			
			inputField = $("<select class='form-control'>");
			var countryIds = $('#country').val();
            const filteredProjects = lookUpData.projects.filter(project => project.country_id == data.country_id);

			var isAnyOptionSelected = false;

			$.each(filteredProjects, function(index, project) {
				var option = $("<option>", { value: project.project_name, text: project.project_name });

				// Check if the current project matches the selected value in the data
				if (project.id == data.project_id) {
					option.prop("selected", true);
					isAnyOptionSelected = true;
				}

				// Append the option to the input field
				inputField.append(option);
			});

			if (!isAnyOptionSelected) {
				inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
			}

		} else {
			
			inputField = $("<select class='form-control'>");
			var countryIds = $('#country').val();

            const filteredSites = lookUpData.sites.filter(site => site.project_id == data.project_id);
			var isAnyOptionSelected = false;

			$.each(filteredSites, function(index, site) {
				var option = $("<option>", { value: site.site_name, text: site.site_name });

				// Check if the current site matches the selected value in the data
				if (site.id == data.site_id) {
					option.prop("selected", true);
					isAnyOptionSelected = true;
				}

				// Append the option to the input field
				inputField.append(option);
			});

			if (!isAnyOptionSelected) {
				inputField.prepend($("<option>", { value: '', text: '--select--', selected: true }));
			}
		}
		return inputField;
	}

	function get_records_count() {
		var dateRange = $('input[name="daterange"]').val(); // This will give you a string like '2024-11-01 - 2024-11-21'
		let start_date;
		let end_date;
		if (dateRange) {
			var dates = dateRange.split(' - '); // Split by the separator ' - '
			start_date = dates[0]; // First date (start)
			end_date = dates[1];   // Second date (end)
		}

		$.ajax({
			url: "<?php echo base_url(); ?>reports/get_records_count/<?php echo $this->uri->segment(3); ?>",  // Target URL
			type: "POST",  // Change to POST
			dataType: "JSON",  // Expect a JSON response
			data: {
				worldRegionIds : $('#selectWorldRegion').val(),
				countryIds: $('#selectCountry').val(),
				projectIds: $('#selectProject').val(),
				siteIds: $('#selectSite').val(),
				startDate: start_date,
				endDate: end_date
			},
			success: function (response) {
				// Update your HTML with the received data
				$('#total_surveys').html('<h4 class="mb-0 upload-text">Total uploads: ' + response.total_count + '</h4>');
				$('#pending_surveys').html('<h4 class="mb-0 pending-text">Pending: ' + response.pending_count + '</h4>');
				$('#approved_surveys').html('<h4 class="mb-0 approved-text">Approved: ' + response.approved_count + '</h4>');
				$('#rejected_surveys').html('<h4 class="mb-0 reject-text">Rejected: ' + response.rejected_count + '</h4>');
			},
			error: function (xhr, status, error) {
				// Handle any errors in the request
				console.error(xhr.responseText);
			}
		});
	}

	function get_senegal_date(inputDate) {
		const inputDateTimeString = inputDate;

		// Convert string to Date object (assuming the input is in a known format)
		const inputDateTime = new Date(inputDateTimeString);

		// Convert to Senegal time zone (West Africa Time - WAT)
		const options = { timeZone: "Africa/Dakar" };
		const senegalDateTimeString = inputDateTime.toLocaleString("en-US", options);

		// Format the date in "YYYY-MM-DD, hh:mm:ss A" format manually
		const year = inputDateTime.getFullYear();
		const month = String(inputDateTime.getMonth() + 1).padStart(2, '0');
		const day = String(inputDateTime.getDate()).padStart(2, '0');
		const hours = String(inputDateTime.getHours()).padStart(2, '0');
		const minutes = String(inputDateTime.getMinutes()).padStart(2, '0');
		const seconds = String(inputDateTime.getSeconds()).padStart(2, '0');
		const meridiem = (hours >= 12) ? 'PM' : 'AM';

		const formattedSenegalDate = `${year}-${month}-${day}, ${hours}:${minutes}:${seconds} ${meridiem}`;

		return formattedSenegalDate;
	}

	function exportToXcel(name,data, totalGroupInfo, mergeInfo){
		const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(data);
		XLSX.utils.book_append_sheet(wb, ws, name);
		// Define merged cells
		ws['!merges'] = mergeInfo;
		for (const key in totalGroupInfo) {
			const wsg = XLSX.utils.aoa_to_sheet(totalGroupInfo[key]['groupData']);
			XLSX.utils.book_append_sheet(wb, wsg, totalGroupInfo[key]['groupName']);
		}        
        XLSX.writeFile(wb, name+'.xlsx');
	}
	
	function exportXcel() {
		$("#export_sub").prop('disabled', true);
        $("#export_sub").html("Please wait ...");
		
		var dateRange = $('input[name="daterange"]').val(); // This will give you a string like '2024-11-01 - 2024-11-21'
		let start_date;
		let end_date;
		if (dateRange) {
			var dates = dateRange.split(' - '); // Split by the separator ' - '
			start_date = dates[0]; // First date (start)
			end_date = dates[1];   // Second date (end)
		}

		var query_data = {
			// season: season,
			pagination:null,
			worldRegionIds: $('#worldRegion').val(),
			countryIds: $('#country').val(),
			stateIds: $('#selectState').val(),
			districtIds: $('#selectDistrict').val(),
			projectIds: $('#selectProject').val(),
			siteIds: $('#selectSite').val(),
			startDate: start_date,
			endDate: end_date,
		};
		
		$.ajax({
			url: "<?php echo base_url(); ?>reports/get_village_survey_export/<?php echo $this->uri->segment(3); ?>",
			data: query_data,
			type: "POST",
			dataType: "JSON",
			error: function() {
				$('#submited_body').html('<h4 class="text-center">No Data Found</h4>');
			},
			success: function(response) {
				if (response.status == 0) {
					$.toast({
						heading: 'Error!',
						text: response.msg,
						icon: 'error'
					});
					$('#submited_body').html('<h4 class="text-center">No data Found</h4>');
					return false;
				}
				// var role = response.user_role;
				var fields = response.fields;
				var submitedData = response.survey_data;
				var lkp_country = response.country_list;
				var lkp_state = response.state_list;
				var lkp_district = response.district_list;
				var lkp_major_regions = response.majorRegion_list;
				var lkp_minor_region = response.minorRegion_list;
				var lkp_communities_type = response.communities_type_list;
				var lkp_add_season = response.season;
				var lkp_units = response.units;
				var gender = response.gender;
				var income_activities = response.income_activities;
				var lkp_currency = response.currency;
				var crop_list = response.crop_list;
				var lkp_fodder_type = response.lkp_fodder_type;
				var lkp_feed_type = response.lkp_feed_type;
				var lkp_livestock_sales = response.lkp_livestock_sales;
				var lkp_crop = response.lkp_crop;
				var lkp_animal_type = response.lkp_animal_type;
				var lkp_livestock = response.lkp_livestock;
				var headerInfo = response.headerInfo;
		
				const lkpData = {};
				
				const countries = {};
				for (let sid = 0; sid < lkp_country.length; sid++) {
					const element = lkp_country[sid];
					countries[element.country_id] = element.name;
				}
				const states = {};
				for (let sid = 0; sid < lkp_state.length; sid++) {
					const element = lkp_state[sid];
					states[element.state_id] = element.state_name;
				}
				const districts = {};
				for (let sid = 0; sid < lkp_district.length; sid++) {
					const element = lkp_district[sid];
					districts[element.district_id] = element.district_name;
				}
				const majorRegion_list = {};
				for (let sid = 0; sid < lkp_major_regions.length; sid++) {
					const element = lkp_major_regions[sid];
					majorRegion_list[element.id] = element.major_region_name;
				}
				const minorRegion_list = {};
				for (let sid = 0; sid < lkp_minor_region.length; sid++) {
					const element = lkp_minor_region[sid];
					minorRegion_list[element.id] = element.minor_region_name;
				}
				const communities_type_list = {};
				for (let sid = 0; sid < lkp_communities_type.length; sid++) {
					const element = lkp_communities_type[sid];
					communities_type_list[element.id] = element.name;
				}
				const units = {};
				for (let sid = 0; sid < lkp_units.length; sid++) {
					const element = lkp_units[sid];
					units[element.unit_id] = element.unit_name;
				}
				const season = {};
				for (let sid = 0; sid < lkp_add_season.length; sid++) {
					const element = lkp_add_season[sid];
					season[element.data_id] = element.season_name;
				}
				const currency = {};
				for (let sid = 0; sid < lkp_currency.length; sid++) {
					const element = lkp_currency[sid];
					currency[element.id] = element.name;
				}
				const fodder_type = {};
				for (let sid = 0; sid < lkp_fodder_type.length; sid++) {
					const element = lkp_fodder_type[sid];
					fodder_type[element.fodder_type_id] = element.fodder_type;
				}
				const feed_type = {};
				for (let sid = 0; sid < lkp_feed_type.length; sid++) {
					const element = lkp_feed_type[sid];
					feed_type[element.feed_type_id] = element.feed_type;
				}
				const livestock_sales = {};
				for (let sid = 0; sid < lkp_livestock_sales.length; sid++) {
					const element = lkp_livestock_sales[sid];
					livestock_sales[element.id] = element.name;
				}
				const crops = {};
				for (let sid = 0; sid < lkp_crop.length; sid++) {
					const element = lkp_crop[sid];
					crops[element.id] = element.crop_name;
				}
				const animal_type = {};
				for (let sid = 0; sid < lkp_animal_type.length; sid++) {
					const element = lkp_animal_type[sid];
					animal_type[element.id] = element.name;
				}
				const livestock = {};
				for (let sid = 0; sid < lkp_livestock.length; sid++) {
					const element = lkp_livestock[sid];
					livestock[element.id] = element.name;
				}
				const incomeActivities = {};
				for (let sid = 0; sid < income_activities.length; sid++) {
					const element = income_activities[sid];
					incomeActivities[element.id] = element.name;
				}				
				const genders = {};
				for (let sid = 0; sid < gender.length; sid++) {
					const element = gender[sid];
					genders[element.gender_id] = element.gender_des;
				}

				let xcelData = [];
				let xcelHeader = [];
				let tableHeaderFields = [];

				let xcelHeaderInfo = ['',''];
				var mergeInfo = [];
				var countValStart = 2;
				var countValEnd = '';
				for (const key in headerInfo) {
					const headerLabel = headerInfo[key]['name'];
					for (let index = 0; index < headerInfo[key]['fieldcount']; index++) {
						if(index == 0){
							xcelHeaderInfo.push(headerLabel);
						} else {
							xcelHeaderInfo.push('');
						}
					}

					mergeInfo.push({ s: { r: 0, c: headerInfo[key]['mergestart'] }, e: { r: 0, c: headerInfo[key]['mergeend'] } });

					/* if(key == 0) {
						countValEnd = countValStart + headerInfo[key]['fieldcount'];
						console.log(countValStart);
						console.log(countValEnd);
						mergeInfo.push({ s: { r: 0, c: countValStart }, e: { r: 0, c: countValEnd-2 } });
					} */ /* else {
						countValStart = countValEnd;
						countValEnd += headerInfo[key]['fieldcount'];
						mergeInfo.push({ s: { r: 0, c: countValStart+1 }, e: { r: 0, c: countValEnd -2 } });
					} */

					/* countValStart += headerInfo[key]['fieldcount']+1;
					countValEnd = countValStart + headerInfo[key]['fieldcount'];				

					if(key == 0) {						
						mergeInfo.push({ s: { r: 0, c: 2 }, e: { r: 0, c: headerInfo[key]['fieldcount'] } });
					} else {
						mergeInfo.push({ s: { r: 0, c: countValStart }, e: { r: 0, c: 18 } });
					} */
				}
				var tableHead = `<tr style="position: sticky;top: -1px;background: #fff;">`;
				
				tableHead += `<th>S.No.</th>`;
				
				xcelHeader.push("S.No.")
				xcelHeader.push("Data ID")
				tableHeaderFields.push('sno')
				tableHeaderFields.push('data_id')
				for (const key in fields) {
					const label = fields[key]['label'];
					const type = fields[key]['type'];

					if(type?.startsWith('lkp_') ){
						switch (type) {
							case 'lkp_country':
								lkpData['field_'+fields[key]['field_id']] = countries;
								break;
							case 'lkp_state':
								lkpData['field_'+fields[key]['field_id']] = states;
								break;
							case 'lkp_district':
								lkpData['field_'+fields[key]['field_id']] = districts;
								break;
							case 'lkp_major_region':
								lkpData['field_'+fields[key]['field_id']] = majorRegion_list;
								break;
							case 'lkp_minor_region':
								lkpData['field_'+fields[key]['field_id']] = minorRegion_list;
								break;
							case 'lkp_communities_type':
								lkpData['field_'+fields[key]['field_id']] = communities_type_list;
								break;
							case 'lkp_units':
								lkpData['field_'+fields[key]['field_id']] = units;
								break;
							case 'lkp_add_season':
								lkpData['field_'+fields[key]['field_id']] = season;
								break;
							case 'lkp_currency':
								lkpData['field_'+fields[key]['field_id']] = currency;
								break;
						
							default:
								break;
						}
					}
					
					if(label != "Declaration" && label != "General"){
						if (type == 'kml') {
							if(survey_id == 5){
								tableHead += `<th>`+label+`</th>`;
								xcelHeader.push(label)
								tableHeaderFields.push('field_'+fields[key]['field_id']);
							}
						}else if(type == 'file'){
							xcelHeader.push(label+' lat', label+' long')
							tableHeaderFields.push('field_'+fields[key]['field_id']+'_lat', 'field_'+fields[key]['field_id']+'_long');
						}else{
							tableHead += `<th>`+label+`</th>`;
							xcelHeader.push(label)
							tableHeaderFields.push('field_'+fields[key]['field_id']);
						}
					}
				}
				tableHead += `<th>Verified by</th><th>Uploaded by</th><th>Uploaded date & time (GMT)</th>`;
				xcelHeader.push(...['Verified by', 'Uploaded by','Uploaded date & time (GMT)']);
				tableHeaderFields.push(...['verified_full_name', 'first_name','datetime']);				
					
				tableHead += `<th>verified_status</th>`;
				xcelHeader.push('verified_status')
				tableHeaderFields.push(...['verified']);

				if(submitedData.length > 0){
					const xcelBody = [];
					var tableBody ="";
					
					for (let i=0; i<submitedData.length; i++){
						const elemnt = submitedData[i];
						const row = [];
						elemnt.sno = i+1;

						for (let k = 0; k < tableHeaderFields.length; k++) {
							const key = tableHeaderFields[k];
							if(lkpData[key]){
								row.push(lkpData[key][elemnt[key]] || "N/A");
							}else{
								if(key == 'verified'){
									row.push(elemnt[key] == 1 ? 'Approved' : elemnt[key] == 0 ? 'Rejected' : 'Pending');
								} else{
									if(key == "datetime"){
										row.push(get_senegal_date(elemnt[key]) || 'N/A')
									} else{
										// Check if elemnt[key] is a string before using replace
										if (typeof elemnt[key] === 'string') {
											row.push((elemnt[key] || 'N/A').replace(/&#44;/g, ','));
										} else {
											row.push(elemnt[key] || 'N/A');
										}
									}
								}
							}
						}
						xcelBody.push(row);
					}
					xcelData.push(xcelHeaderInfo)
					xcelData.push(xcelHeader)
					xcelData.push(...xcelBody)

					// groupData start
					var groupInfo = response.group_info;
					let totalgroupxcelHeader = [];
					let totalgroupxcelBody = [];
					let totalGroupInfo = [];
					for (const key in groupInfo) {
						// var groupName = groupInfo[key]['group_label'];
						let groupxcelHeader = [];
						let groupxcelBody = [];
						let individualgroupdata = [];

						groupxcelHeader.push("S.No.");
						groupxcelHeader.push("Data Id");
						for (const gfkey in groupInfo[key]['group_fields']) {
							const groupFieldLabel = groupInfo[key]['group_fields'][gfkey]['label'];
							groupxcelHeader.push(groupFieldLabel);
						}

						var groupData = groupInfo[key]['group_data'];
						if(groupData.length > 0){
							for (let k = 0; k < groupData.length; k++) {
								const elemnt = groupData[k];
								const row = [];
								row.push(k+1);
								row.push(elemnt['data_id']);
								const parsedObject = JSON.parse(elemnt['data']);

								for (const gdfkey in groupInfo[key]['group_fields']) {
									const grouptype = groupInfo[key]['group_fields'][gdfkey]['type'];

									if(grouptype?.startsWith('lkp_') ){
										if(grouptype == 'lkp_fodder_type'){
											row.push(fodder_type[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);
										} else if(grouptype == 'lkp_feed_type'){
											row.push(feed_type[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);
										} else if(grouptype == 'lkp_livestock_sales'){
											row.push(livestock_sales[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);
										} else if(grouptype == 'lkp_gender'){
											row.push(genders[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);																						
										} else if(grouptype == 'lkp_crop'){
											row.push(crops[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);																						
										} else if(grouptype == 'lkp_income_activities'){
											row.push(incomeActivities[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);																						
										} else if(grouptype == 'lkp_animal_type'){
											row.push(animal_type[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);																						
										} else if(grouptype == 'lkp_livestock'){
											row.push(livestock[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);																						
										} else if(grouptype == 'lkp_units'){
											row.push(units[parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]]);																						
										}
									} else {
										row.push(parsedObject['field_'+groupInfo[key]['group_fields'][gdfkey]['field_id']]);
									}
								}

								groupxcelBody.push(row);
							}
						}

						individualgroupdata.push(groupxcelHeader);
						individualgroupdata.push(...groupxcelBody);

						totalGroupInfo[key] = {};
						totalGroupInfo[key]['groupData'] = individualgroupdata;
						totalGroupInfo[key]['groupName'] = groupInfo[key]['group_label'];
					}

					exportToXcel(surveyName, xcelData, totalGroupInfo, mergeInfo);
					$("#export_sub").prop('disabled', false);
                	$("#export_sub").html("Export data");
				}else{
					$("#export_sub").prop('disabled', false);
					$("#export_sub").html("Export data");
				}
			}
		});		
	}

	function exportZlib() {
		$("#export_zlib").prop('disabled', true);
		$("#export_zlib").html("Please wait ...");
		
		var dateRange = $('input[name="daterange"]').val(); // This will give you a string like '2024-11-01 - 2024-11-21'
		let start_date;
		let end_date;
		if (dateRange) {
			var dates = dateRange.split(' - '); // Split by the separator ' - '
			start_date = dates[0]; // First date (start)
			end_date = dates[1];   // Second date (end)
		}

		var query_data = {
			// season: season,
			pagination: null,
			worldRegionIds: $('#worldRegion').val(),
			countryIds: $('#country').val(),
			stateIds: $('#selectState').val(),
			districtIds: $('#selectDistrict').val(),
			projectIds: $('#selectProject').val(),
			siteIds: $('#selectSite').val(),
			startDate: start_date,
			endDate: end_date,
		};

		$.ajax({
			url: "<?php echo base_url(); ?>reports/get_village_survey_export/<?php echo $this->uri->segment(3); ?>",
			data: query_data,
			type: "POST",
			dataType: "JSON",
			error: function() {
				$('#submited_body').html('<h4 class="text-center">No Data Found</h4>');
				$("#export_zlib").prop('disabled', false);
				$("#export_zlib").html("Export .zlib Data");
			},
			success: function(response) {
				if (response.status == 0) {
					$.toast({
						heading: 'Error!',
						text: response.msg,
						icon: 'error'
					});
					$('#submited_body').html('<h4 class="text-center">No data Found</h4>');
					$("#export_zlib").prop('disabled', false);
					$("#export_zlib").html("Export .zlib Data");
					return false;
				}

				var submitedData = response.survey_data;

				if (submitedData.length > 0) {
					// Convert data to CSV format
					var csvContent = "data:text/csv;charset=utf-8," 
						+ submitedData.map(e => Object.values(e).join(",")).join("\n");

					// Compress CSV content
					var compressedData = pako.gzip(csvContent);

					// Create a Blob and trigger download
					var blob = new Blob([compressedData], { type: 'application/x-gzip' });
					var url = window.URL.createObjectURL(blob);
					var a = document.createElement('a');
					a.href = url;
					a.download = 'data.zlib';
					document.body.appendChild(a);
					a.click();
					document.body.removeChild(a);

					$("#export_zlib").prop('disabled', false);
					$("#export_zlib").html("Export .zlib Data");
				} else {
					$("#export_zlib").prop('disabled', false);
					$("#export_zlib").html("Export .zlib Data");
				}
			}
		});
	}

	// Load file details on click
	$('body').on('click', '.get_file_details', function(event) {
		var elem = $(this);
		var td = elem.parent();
		var data_id = elem.data('dataid');
		var field_id = elem.data('field');

		// Get Details of file for the field
		$.ajax({
			url : '<?php echo base_url(); ?>reports/registration_file_details',
			data : { data_id: data_id, field_id : field_id },
			type : "POST",
			dataType : "JSON",
			error:function(){
				$.toast({
					heading: 'Network Error!',
					text: 'Could not establish connection to server. Please refresh the page and try again.',
					icon: 'error'
				});
			},
			success:function(response){
				if(response.status == 0) {
					$.toast({
						heading: 'Error!',
						text: response.msg,
						icon: 'error'
					});
				} else {
					if(response.data.length === 0) {
						td.html('N/A');
					} else {
						let HTML = '';

						HTML = '<a target="_blank" href="<?php echo base_url(); ?>uploads/survey/' + response.data.file_name +'" style="color:#63C2DE; font-weight:bold;">View Image</a><br/><br/>';
						HTML += response.data.file_lat + ', ' + response.data.file_long;
						td.html(HTML);
					}
				}
			}
		})
	});
</script>