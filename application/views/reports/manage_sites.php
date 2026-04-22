
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
    function get_project_name($projectId, $projects) {
        foreach ($projects as $project) {
            if ($project['id'] == $projectId) {
                return $project['project_name'];
            }
        }
        return 'NA'; // Default return value if not found
    }
    function get_country_name($countryId, $countries) {
        foreach ($countries as $country) {
            if ($country['country_id'] == $countryId) {
                return $country['name'];
            }
        }
        return 'NA'; // Default return value if not found
    }
?>
<div class="app-content content mb-0 pb-0" style="margin-left: 0px;">
	<div class="content-wrapper pb-0 mb-0">
        <div class="content-body mb-0 pb-0">
            <div class="row main-content mb-0 pb-0">
                <div class="col-md-12">
                    <button id="addProjectBtn" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Site</button>
                    <h4 style="font-weight: bold;">Manage Sites</h4>
                    <a class="cursor btn btn-warning btn-sm" href="<?php echo base_url(); ?>projects/manage_projects/">Back to Projects</a>
                </div>
                <div class="col-md-12">
                    <h4 class="bold"></h4>
                    <div class="card mb-0">
                        <div class="card-header">
                            <h2><?php echo $projectInfo['project_name']; ?></h2>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="table-responsive min_scroll">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Site Name</th>
                                                <th>Project Name</th>
                                                <th>Country</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($sites) > 0){
                                                foreach ($sites as $key => $survey) { ?>
                                                    <tr>
                                                        <td><?php echo ++$key; ?></td>
                                                        <td><?php echo $survey['site_name']; ?></td>
                                                        <td><?php echo get_project_name($survey['project_id'], $projects); ?></td> <!-- Pass projects to the function -->
                                                        <td><?php echo get_country_name($survey['country_id'], $countries); ?></td> <!-- Pass countries to the function -->
                                                        <td style="width:400px;">
                                                           <!--  <a href="<?php echo base_url(); ?>projects/manage_site_major_region/<?php echo $this->uri->segment(3); ?>/<?php echo $survey['id']; ?>" class="btn btn-warning btn-sm ">Manage Major Region</a> -->
                                                            <!-- <div class="btn btn-success btn-sm edit-project-btn" id="editProject_<?php echo $survey['id']; ?>">Edit</div> -->
                                                            <a href="<?php echo base_url(); ?>projects/manage_sites_edit/<?php echo $this->uri->segment(3); ?>/<?php echo $survey['id']; ?>" class="btn btn-success btn-sm" target="_blank">Edit</a>
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
                <div class="col-md-12 text-center">
                    <h2><?php echo $projectInfo['project_name']; ?></h2>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <button class="cursor btn btn-warning btn-sm pull-right" id="backButton"><i class="fa fa-angle-double-left"></i> Back</button>
                    <h4 class="mb-1" style="font-weight: bold;">
                        <span id="formHeading">Add New Site</span>
                    </h4>
                    <div class="card">
                        <div class="card-body">
                            <form id="newSiteForm">
                                <div class="form-group">
                                    <label for="siteName">Site Name<font color="red">*</font>:</label>
                                    <input type="text" id="siteName" name="siteName" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="siteDescription">Site Description:</label>
                                    <textarea id="siteDescription" name="siteDescription" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="worldRegion">Select World Region<font color="red">*</font>:</label>
                                    <select id="worldRegion" name="worldRegion" class="form-control" required>
                                        <option value="">--Select World Region--</option>
                                        <!-- Add options dynamically or hardcoded as needed -->
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="country">Select Country<font color="red">*</font>:</label>
                                    <select id="country" name="country" class="form-control" required>
                                        <option value="">--Select Country--</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="localCurrency">Local Currency<font color="red">*</font>:</label>
                                    <select class="form-control" id="localCurrency" name="localCurrency" required>
                                        <option value="">--Select Local Currency--</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 addmoremaindiv">
                                        <div class="row addmore addmore_div ">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Major Region</label>
                                                    <input type="text" class="form-control" name="major_region[]">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                <button type="button" class="btn btn-success btn-sm addmorefields pull-left" style="margin-bottom: 15px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                               
                                    <div class="col-md-6 addmoremaindiv">
                                        <div class="row addmore addmore_div ">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Minor Region</label>
                                                    <input type="text" class="form-control" name="minor_region[]">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                <button type="button" class="btn btn-success btn-sm addmorefields pull-left" style="margin-bottom: 15px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="subRegion">Sub-Region:</label>
                                            <input type="text" id="subRegion" name="subRegion" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="villageCommunity">Village/Community:</label>
                                            <input type="text" id="villageCommunity" name="villageCommunity" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="communityType">Community Type</label>
                                            <select class="form-control" id="communityType" name="communityType">
                                                <option value="">--Select Community Type--</option>
                                            </select>
                                            <!-- <a href="<?php echo base_url(); ?>lookup_tables/showtableinfo/lkp_communities_type" target="_blank"><p class="pull-right">Add Community Type</p></a> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Local Fodder Resource Data</h3>
                                        <hr style="border-top: 4px solid rgba(0, 0, 0, 0.1);">
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="grazingmetabolisable">Grazing Metabolisable Energy MJ/KG (Default 9):</label>
                                            <input type="text" id="grazingmetabolisable" value="9" name="grazingmetabolisable" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="grazingcrude">Grazing Crude Protein Percentage (Default 8%):</label>
                                            <input type="text" id="grazingcrude" value="8" name="grazingcrude" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="collectedmetabolisable">Collected Fodder Metabolisable Energy MJ/KG (Default 10):</label>
                                            <input type="text" id="collectedmetabolisable" value="10" name="collectedmetabolisable" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="collectedcrude">Collected Fodder Crude Protein Percentage (Default 8%):</label>
                                            <input type="text" id="collectedcrude" value="8" name="collectedcrude" class="form-control" >
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="siteId" name="siteId" value="">
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
        const data = {
            sites: <?php echo json_encode($sites); ?>,
            projects: <?php echo json_encode($projects); ?>,
            worldRegions: <?php echo json_encode($world_region); ?>,
            /*majorRegions: <?php echo json_encode($major_region); ?>,*/
            countries: <?php echo json_encode($countries); ?>,
            communities_type: <?php echo json_encode($communities_type); ?>,
            currency: <?php echo json_encode($currency); ?>,
            selectedProjectId : <?php echo $projectInfo['id']; ?>
        };
        
        let isEditing = false; // Track whether we are editing a project
        
        // Populate world regions on page load
        data.worldRegions.forEach(region => {
            $('#worldRegion').append(new Option(region.world_region_name, region.id));
        });

        data.projects.forEach(project => {
            $('#selectProject').append(new Option(project.project_name, project.id));
        });

        data.communities_type.forEach(communitiestype => {
            $('#communityType').append(new Option(communitiestype.name, communitiestype.id));
        });

        // When world region changes, populate major regions
        $('#worldRegion').change(function() {
            const worldRegionId = $(this).val();
            /*$('#majorRegion').empty().append('<option value="">--Select Major Region--</option>');*/
            $('#country').empty().append('<option value="">--Select Country--</option>'); // Clear country dropdown

            const filteredCountries = data.countries.filter(region => region.world_region_id == worldRegionId);
            if (filteredCountries) {
                filteredCountries.forEach(country => {
                    $('#country').append(new Option(country.name, country.country_id));
                });
            }
        });

        // When major region changes, populate countries
        $('#country').change(function() {
            const worldRegionId = $('#worldRegion').val();
            const majorRegionId = $('#majorRegion').val();
            const countryId = $(this).val();
            $('#selectProject').empty().append('<option value="">--Select Project--</option>');
            $('#localCurrency').empty().append('<option value="">--Select Local Currency--</option>');

            const filteredcurrency = data.currency.filter(region => region.world_region_id == worldRegionId && region.country_id == countryId);

            if (filteredcurrency) {
                filteredcurrency.forEach(currencyVal => {
                    $('#localCurrency').append(new Option(currencyVal.name, currencyVal.id));
                });
            }
        });

        // Show form and hide main content when Add Project is clicked
        $('#addProjectBtn').click(function() {
            $('#worldRegion').val('').change(); // Clear and trigger change event
            $('#majorRegion').val('').change(); // Clear and trigger change event
            $('#country').val(''); // Clear country field
            $('#selectProject').val(''); // Clear project selection
            $('#siteName').val(''); // Clear site name textarea
            $('#siteId').val(''); 
            isEditing = false; // Set to add mode
            $('#formHeading').text('Add New Site'); // Update heading
            $('.main-content').hide();
            $('.add-project-form').show();
        });

        $('#backButton').click(function() {
            $('.add-project-form').hide();
            $('.main-content').show();
        });

        $('.edit-project-btn').click(function() {
            isEditing = true; // Set to edit mode
            $('#formHeading').text('Edit Site'); // Update heading
            $('.add-project-form').show();
            $('.main-content').hide();

            const projectId = $(this).attr('id').split('_')[1];
            const filteredProject = data.sites.filter(project => project.id == projectId && project.status == 1);

            if (filteredProject.length > 0) {
                $('#siteName').val(filteredProject[0]['site_name']); // Set the project name in the textarea
                $('#siteDescription').val(filteredProject[0]['site_description']); // Set the project name in the textarea
                $('#worldRegion').val(filteredProject[0]['world_region_id']).change(); // Trigger change after setting value
                $('#country').val(filteredProject[0]['country_id']).change(); // Set country value directly if needed
                $('#localCurrency').val(filteredProject[0]['localCurrency']);
                $('#subRegion').val(filteredProject[0]['sub_region']);
                $('#villageCommunity').val(filteredProject[0]['village_community']);
                $('#communityType').val(filteredProject[0]['community_type']);
                $('#grazingmetabolisable').val(filteredProject[0]['grazingmetabolisable']);
                $('#grazingcrude').val(filteredProject[0]['grazingcrude']);
                $('#collectedmetabolisable').val(filteredProject[0]['collectedmetabolisable']);
                $('#collectedcrude').val(filteredProject[0]['collectedcrude']);                
                $('#siteId').val(projectId); // Set the project ID for editing
            } else {
                console.warn('No project found with ID:', projectId);
            }
        });

        $('#newSiteForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var majorRegionArray = [];
            var minorRegionArray = [];

            $('input[name="major_region[]"]').map(function () {
                majorRegionArray.push(this.value);
                //return this.value; // $(this).val()
            }).get();

            $('input[name="minor_region[]"]').map(function () {
                minorRegionArray.push(this.value);
            }).get();

            // Collect form data
            const formData = {
                site_name: $('#siteName').val(),
                site_description: $('#siteDescription').val(),
                world_region_id: $('#worldRegion').val(),
                country_id: $('#country').val(),
                localCurrency: $('#localCurrency').val(),
                sub_region : $('#subRegion').val(),
                village_community : $('#villageCommunity').val(),
                community_type : $('#communityType').val(),
                grazingmetabolisable: $('#grazingmetabolisable').val(),
                grazingcrude: $('#grazingcrude').val(),
                collectedmetabolisable: $('#collectedmetabolisable').val(),
                collectedcrude: $('#collectedcrude').val(),
                selectedProjectId : data.selectedProjectId,
                majorRegionArray :  majorRegionArray,
                minorRegionArray :  minorRegionArray,
                type: 'sites',
            };

            const projectId = $('#siteId').val(); // Get project ID from the hidden input

            if (projectId && isEditing) {
                formData.site_id = projectId; // Add project ID to the form data
            }

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>projects/add_edit_project', // Use the same endpoint
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: "Done!",
                        text: 'Site ' + (isEditing ? 'edited' : 'added') + ' successfully!',
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500); // Add a slight delay
                    });

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
                            type: 'sites',
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

    $('body')
    .on('click', '.addmorefields', function() {
        $('.error').html('');
        $elem = $(this);
        
        $elem.closest('.addmoremaindiv').find('.removeaddmore').parent().html('');
        var $template = $elem.closest('.addmoremaindiv').find('.addmore_div'),
            $clone = $template
            .clone()
            .removeClass('addmore_div')
            .addClass('dulicate_addmore_div');

        $clone.find('input[type="text"]').val('');

        $clone.find('.addmorefields').parent().html('<button type="button" class="btn btn-danger btn-sm removeaddmore pull-left" style="margin-top:10px;">\
            <span class="glyphicon glyphicon-minus"></span> Remove\
        </button>');
        $(this).closest('.addmoremaindiv').append($clone);       
    });
</script>