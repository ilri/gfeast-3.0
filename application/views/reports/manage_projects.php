
<head>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<style>
    .btn-lg, .btn-group-lg > .btn {
    font-size: 16px;
    line-height: 1.25;
    border-radius: 4px;
    background: rgb(221, 51, 51);
    color: #fff;
    padding: 7px 12px;
}
.min_scroll {
    overflow-y: scroll;
    height: 485px;
}
.table thead {
    position: sticky;
    top: -3px;
}
.sweet-alert .sa-icon.sa-success::before {
    border-radius: 120px 0 0 120px;
    top: -19px;
    left: -33px;
    transform: rotate(-45deg);
    transform-origin: 60px 60px;
}
</style>
<?php
    function get_country_name($countryId, $countries) {
        foreach ($countries as $country) {
            if ($country['country_id'] == $countryId) {
                return $country['name'];
            }
        }
        return 'NA'; // Default return value if not found
    }
?>
<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper mb-0 pb-0">
        <div class="content-body mb-0 pb-0">
            <div class="row main-content mb-0 pb-0">
                <div class="col-md-12">
                    <button id="addProjectBtn" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Project</button>
                    <h4 style="font-weight: bold;">Manage Projects</h4>
                </div>
                <div class="col-md-12">
                    <h4 class="bold"></h4>
                    <div class="card mb-0">
                        <div class="card-header pb-0 d-flex justify-content-end">

                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="table-responsive min_scroll">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project Name</th>
                                                <!-- <th>Country</th> -->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($projects) > 0){
                                                foreach ($projects as $key => $survey) { ?>
                                                    <tr>
                                                        <td><?php echo ++$key; ?></td>
                                                        <td><?php echo $survey['project_name']; ?></td>
                                                        <!-- <td><?php //echo get_country_name($survey['country_id'], $countries); ?></td> --> <!-- Pass countries to the function -->
                                                        <td style="width:300px;">
                                                            <a href="<?php echo base_url(); ?>projects/manage_sites/<?php echo $survey['id']; ?>" class="btn btn-warning btn-sm ">Manage Site</a>
                                                            <div class="btn btn-success btn-sm edit-project-btn" id="editProject_<?php echo $survey['id']; ?>">Edit</div>
                                                            <?php if ($survey['status'] == 1): ?>
                                                                <div class="btn btn-danger btn-sm change-status-btn" data-status="active" data-id="<?php echo $survey['id']; ?>">Delete</div>
                                                            <?php elseif ($survey['status'] == 0): ?>
                                                                <div class="btn btn-light btn-sm change-status-btn" data-status="inactive" data-id="<?php echo $survey['id']; ?>" style="background-color: lightcoral; color: white;">Inactive</div>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            }else{ ?>
                                                <tr>
                                                    <td colspan="8">No data found</td>
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
            <div class="row add-project-form" style="display: none;">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <button class="cursor btn btn-warning btn-sm pull-right" id="backButton"><i class="fa fa-angle-double-left"></i> Back</button> 
                    <h4 class="mb-1" style="font-weight: bold;">
                        <span id="formHeading">Add New Project</span>
                    </h4>
                    <div class="card">
                        <div class="card-body">
                            <form id="newProjectForm">
                                <div class="form-group">
                                    <label for="projectName">Title<font color="red">*</font>:</label>
                                    <input type="text" name="projectName" class="form-control" id="projectName" required>
                                </div>

                                <div class="form-group">
                                    <label for="projectDescription">Description:</label>
                                    <textarea id="projectDescription" name="projectDescription" class="form-control"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="projectStartDate">Start Date<font color="red">*</font>:</label>
                                            <input type="text" name="projectStartDate" class="form-control datepicker" id="projectStartDate" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="projectEndDate">End Date:</label>
                                            <input type="text" name="projectEndDate" class="form-control enddatepicker" autocomplete="off" id="projectEndDate">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <label>Keep data public or private <font color="red">*</font>: <a href="#" data-toggle="tooltip" class="pull-right" title="All data from the selected project will be made public. It is not possible to selectively make some data from a project public and other data private. Also, once data has been made public, it cannot be made private again. You may tick the 'Keep my data private for 1 year' box if you do not wish to share your data immediately with the community. However, be advised that all data uploaded to the site will become available to the community after one year. You can read more about ILRI's open data policy on the FEAST website" style="margin-left: 10px;"><i class="fa fa-info-circle" style="font-size:15px"></i></a></label>
                                        <div class="form-check">
                                            <div class="row">															 
                                                <div class="col-6">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="project_type" value="Public"> Public
                                                    </label>
                                                </div>
                                                
                                                <div class="col-6">                               
                                                    <label class="radio-inline">
                                                        <input type="radio" name="project_type" value="Private"> Private (for 1 year)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="projectOrganization">Partner Organization(s):</label>
                                    <textarea id="projectOrganization" name="projectOrganization" class="form-control"></textarea>
                                </div>

                                <input type="hidden" id="projectId" name="projectId" value="">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip();   

        $('.datepicker').datepicker({
            format: 'mm-dd-yyyy',
            autoclose: true,
        });

        $('#projectStartDate').on('change', function(){
            var startDate = new Date(this.value);
            $('.enddatepicker').datepicker({
                format: 'mm-dd-yyyy',
                startDate: startDate,
                autoclose: true
            });
            /*$('.enddatepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
            });*/
        });

        const data = {
            projects: <?php echo json_encode($projects); ?>,
            worldRegions: <?php echo json_encode($world_region); ?>,
            majorRegions: <?php echo json_encode($major_region); ?>,
            countries: <?php echo json_encode($countries); ?>
        };
        let isEditing = false; // Track whether we are editing a project
        // Populate world regions on page load
        data.worldRegions.forEach(region => {
            $('#worldRegion').append(new Option(region.world_region_name, region.id));
        });

        // When world region changes, populate major regions
        $('#worldRegion').change(function() {
            const worldRegionId = $(this).val();
            $('#majorRegion').empty().append('<option value="">--Select Major Region--</option>');
            $('#country').empty().append('<option value="">--Select Country--</option>'); // Clear country dropdown

            const filteredMajorRegions = data.majorRegions.filter(region => region.world_region_id == worldRegionId);
            if (filteredMajorRegions) {
                filteredMajorRegions.forEach(majorRegion => {
                    $('#majorRegion').append(new Option(majorRegion.major_region_name, majorRegion.id));
                });
            }
        });

        // When major region changes, populate countries
        $('#majorRegion').change(function() {
            const worldRegionId = $('#worldRegion').val();
            const majorRegionId = $(this).val();
            $('#country').empty().append('<option value="">--Select Country--</option>');

            const filteredCountries = data.countries.filter(region => region.world_region_id == worldRegionId && region.major_region_id == majorRegionId);

            if (filteredCountries) {
                filteredCountries.forEach(country => {
                    $('#country').append(new Option(country.name, country.country_id));
                });
            }
        });

        // Show form and hide main content when Add Project is clicked
        $('#addProjectBtn').click(function() {
            $('#worldRegion').val('').change(); // Clear and trigger change event
            $('#majorRegion').val('').change(); // Clear and trigger change event
            $('#country').val(''); // Clear country field
            $('#projectName').val(''); // Clear site name textarea
            $('#projectId').val(''); 
            isEditing = false; // Set to add mode
            $('#formHeading').text('Add New Project'); // Update heading
            $('.main-content').hide();
            $('.add-project-form').show();
        });

        $('#backButton').click(function() {
            $('.add-project-form').hide();
            $('.main-content').show();
        });

        $('.edit-project-btn').click(function() {
            isEditing = true; // Set to edit mode
            $('#formHeading').text('Edit Project'); // Update heading
            $('.add-project-form').show();
            $('.main-content').hide();

            const projectId = $(this).attr('id').split('_')[1];
            const filteredProject = data.projects.filter(project => project.id == projectId && project.status == 1);
            if (filteredProject.length > 0) {
                $('#projectName').val(filteredProject[0]['project_name']); // Set the project name in the textarea
                $('#projectDescription').val(filteredProject[0]['projectDescription']); // Set the project name in the textarea
                $('#projectStartDate').val(filteredProject[0]['projectStartDate']); // Trigger change after setting value
                $('#projectEndDate').val(filteredProject[0]['projectEndDate']).change(); // Trigger change after setting value
                $('#projectOrganization').val(filteredProject[0]['projectOrganization']); // Set country value directly if needed
                $("input[name=project_type][value=" + filteredProject[0]['project_type'] + "]").attr('checked', 'checked');
                
                $('#projectId').val(projectId); // Set the project ID for editing
            } else {
                console.warn('No project found with ID:', projectId);
            }
        });

        $('#newProjectForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            const formData = {
                project_name: $('#projectName').val(),
                projectDescription: $('#projectDescription').val(),
                projectStartDate: $('#projectStartDate').val(),                
                projectEndDate: $('#projectEndDate').val(),
                project_type: $('input[name="project_type"]:checked').val(),
                projectOrganization: $('#projectOrganization').val(),
                type: 'projects',
            };

            const projectId = $('#projectId').val(); // Get project ID from the hidden input

            if (projectId && isEditing) {
                formData.project_id = projectId; // Add project ID to the form data
            }

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>projects/add_edit_project', // Use the same endpoint
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    if(response.insertstatus == 1) {
                        Swal.fire({
                            title: "Done!",
                            text: 'Project ' + (isEditing ? 'edited' : 'added') + ' successfully!',
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            setTimeout(() => {
                                window.location.reload();
                            }, 500); // Add a slight delay
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.msg,
                            icon: "error",
                            confirmButtonText: "OK"
                        }).then(() => {
                            setTimeout(() => {
                                window.location.reload();
                            }, 500); // Add a slight delay
                        });
                    }

                    // $('.add-project-form').hide();
                    // $('.main-content').show();
                    // Optionally, reload the project list or update the DOM as needed
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: 'Failed to ' + (isEditing ? 'edit' : 'add') + ' project. Please try again.',
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        });

        $('.change-status-btn').on('click', function() {
            const currentStatus = $(this).data('status');
            const projectId = $(this).data('id');
            const newStatus = (currentStatus === 'active') ? 'inactive' : 'active';

            const confirmationMessage = `Are you sure you want to make this project ${newStatus}?`;

            Swal.fire({
                title: "Confirm Action",
                text: confirmationMessage,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, keep it"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url(); ?>projects/change_project_status',
                        data: {
                            id: projectId,
                            status: newStatus,
                            type: 'projects',
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Done!",
                                text: 'Project deleted successfully!',
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.reload(); // Reload the page after the alert is closed
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Status Code:', xhr.status);
                            console.error('Error details:', xhr.responseText);
                            Swal.fire("Error!", 'An error occurred while changing the project status. Please try again.', "error");
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "The project status remains unchanged.", "info");
                }
            });
        });

    });
</script>