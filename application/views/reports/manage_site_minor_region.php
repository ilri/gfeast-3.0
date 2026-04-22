
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
                    <button id="addminorregionBtn" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Minor Region</button>
                    <h4 style="font-weight: bold;">Manage Minor region</h4>
                    <a class="cursor btn-sm btn btn-warning" href="<?php echo base_url(); ?>projects/manage_site_major_region/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>">Back to Major Region</a>
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
                                                <th>Minor Region Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($sites_minor_region) > 0){
                                                foreach ($sites_minor_region as $key => $mregion) { ?>
                                                    <tr>
                                                        <td><?php echo ++$key; ?></td>
                                                        <td><?php echo $mregion['minor_region_name']; ?></td>
                                                        <td style="width:300px;">
                                                            <div class="btn btn-success btn-sm edit-minorregion-btn" id="editminorregion_<?php echo $mregion['id']; ?>">Edit</div>
                                                            <?php if ($mregion['status'] == 1): ?>
                                                                <div class="btn btn-danger btn-sm change-status-btn" data-status="active" data-id="<?php echo $mregion['id']; ?>">Delete</div>
                                                            <?php elseif ($mregion['status'] == 0): ?>
                                                                <div class="btn btn-light btn-sm change-status-btn" data-status="inactive" data-id="<?php echo $mregion['id']; ?>" style="background-color: lightcoral; color: white;">Inactive</div>
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
            <div class="row add-minorregion-form" style="display: none;">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <button class="cursor btn btn-warning btn-sm pull-right" id="backButton"><i class="fa fa-angle-double-left"></i> Back</button> 
                    <h4 class="mb-1" style="font-weight: bold;">
                        <span id="formHeading">Add New Minor Region</span>
                    </h4>
                    <div class="card">
                        <div class="card-body">
                            <form id="newminorregionForm">
                                <div class="form-group">
                                    <label for="minor_region_name">Minor Region Name:</label>
                                    <input type="text" name="minor_region_name" class="form-control" id="minor_region_name" required>
                                </div>

                                <input type="hidden" id="minor_region_nameId" name="minor_region_nameId" value="">
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
            sites_minor_regions: <?php echo json_encode($sites_minor_region); ?>,
            projectId : <?php echo $this->uri->segment(3); ?>,
            siteId : <?php echo $this->uri->segment(4); ?>,
            majorregionId : <?php echo $this->uri->segment(5); ?>
        };

        let isEditing = false; // Track whether we are editing a project

        // Show form and hide main content when Add Project is clicked
        $('#addminorregionBtn').click(function() {
            $('#minor_region_name').val('');
            isEditing = false; // Set to add mode
            $('#formHeading').text('Add New Minor Region'); // Update heading
            $('.main-content').hide();
            $('.add-minorregion-form').show();
        });

        $('#backButton').click(function() {
            $('.add-minorregion-form').hide();
            $('.main-content').show();
        });

        $('.edit-minorregion-btn').click(function() {
            isEditing = true; // Set to edit mode
            $('#formHeading').text('Edit Minor Region'); // Update heading
            $('.add-minorregion-form').show();
            $('.main-content').hide();

            const sites_minor_regionId = $(this).attr('id').split('_')[1];
            const filteredsitesminorregion = data.sites_minor_regions.filter(sitesminorregion => sitesminorregion.id == sites_minor_regionId && sitesminorregion.status == 1);
            if (filteredsitesminorregion.length > 0) {
                $('#minor_region_name').val(filteredsitesminorregion[0]['minor_region_name']); // Set the minorregion name in the textarea                
                $('#minor_region_nameId').val(sites_minor_regionId); // Set the minorregion ID for editing
            } else {
                console.warn('No Minor Region found with ID:', sites_minor_regionId);
            }
        });

        $('#newminorregionForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Collect form data
            const formData = {
                minor_region_name: $('#minor_region_name').val(),
                projectId : data.projectId,
                siteId : data.siteId,
                majorregionId : data.majorregionId,
                type: 'minor_region'
            };

            const minor_region_nameId = $('#minor_region_nameId').val(); // Get minorregion ID from the hidden input

            if (minor_region_nameId && isEditing) {
                formData.minor_region_nameId = minor_region_nameId; // Add minorregion ID to the form data
            }

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url(); ?>projects/add_edit_region', // Use the same endpoint
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: "Done!",
                        text: 'Minor Region ' + (isEditing ? 'edited' : 'added') + ' successfully!',
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 500); // Add a slight delay
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Error!",
                        text: 'Failed to ' + (isEditing ? 'edit' : 'add') + ' Minor Region. Please try again.',
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        });

        $('.change-status-btn').on('click', function() {
            const currentStatus = $(this).data('status');
            const minor_region_nameId = $(this).data('id');
            const newStatus = (currentStatus === 'active') ? 'inactive' : 'active';

            const confirmationMessage = `Are you sure you want to make this Minor Region ${newStatus}?`;

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
                        url: '<?php echo base_url(); ?>projects/change_region_status',
                        data: {
                            id: minor_region_nameId,
                            status: newStatus,
                            type: 'minor_region',
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Done!",
                                text: 'Minor Region deleted successfully!',
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.reload(); // Reload the page after the alert is closed
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Status Code:', xhr.status);
                            console.error('Error details:', xhr.responseText);
                            Swal.fire("Error!", 'An error occurred while changing the Minor Region status. Please try again.', "error");
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "The Minor Region status remains unchanged.", "info");
                }
            });
        });

    });
</script>