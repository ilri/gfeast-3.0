
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

<div class="app-content content mb-0 pb-0" style="margin-left: 0px;">
	<div class="content-wrapper pb-0 mb-0">
        <div class="content-body mb-0 pb-0">
            <div class="row add-project-form">
                <div class="col-md-12 text-center">
                    <h2><?php echo $projectInfo['project_name']; ?></h2>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <a href="" onclick="window.close()" class="btn btn-sm btn-success pull-right">Back</a>
                    <h4 class="mb-1" style="font-weight: bold;">
                        <span id="formHeading">Add New Site</span>
                    </h4>
                    <div class="card">
                        <div class="card-body">
                            <form id="newSiteForm">
                                <div class="form-group">
                                    <label for="siteName">Site Name<font color="red">*</font>:</label>
                                    <input type="text" id="siteName" name="siteName" class="form-control" value="<?php echo $sites['site_name']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="siteDescription">Site Description:</label>
                                    <textarea id="siteDescription" name="siteDescription" class="form-control"><?php echo $sites['site_description']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="worldRegion">Select World Region<font color="red">*</font>:</label>
                                    <select id="worldRegion" name="worldRegion" class="form-control" required>
                                        <option value="">--Select World Region--</option>
                                        <?php foreach ($world_region as $key => $value) { ?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo ($sites['world_region_id'] == $value['id']) ? 'selected' : ''; ?>><?php echo $value['world_region_name']; ?></option>
                                        <?php } ?>
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
                                        <?php 
                                        $majorRegionCount = count($sites['majorRegion']);
                                        if($majorRegionCount == 0) { ?>
                                            <div class="row addmore addmore_div ">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Major Region</label>
                                                        <input type="text" class="form-control" name="major_region[]" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                    <button type="button" class="btn btn-success btn-sm addmorefields pull-left" style="margin-bottom: 15px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span> Add
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } else {
                                            foreach ($sites['majorRegion'] as $key => $value) { ?>
                                                <div class="row addmore addmore_div ">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Major Region</label>
                                                            <input type="text" class="form-control" name="major_region[]" value="<?php echo $value['major_region_name'] ?>">
                                                        </div>
                                                    </div>
                                                    <?php if($key == 0){ ?>
                                                        <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                            <button type="button" class="btn btn-success btn-sm addmorefields pull-left" style="margin-bottom: 15px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span> Add
                                                            </button>
                                                        </div>
                                                    <?php }else{ ?>
                                                        <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                            <button type="button" class="btn btn-danger btn-sm removeaddmore pull-left" style="margin-top:10px;">
                                                                <span class="glyphicon glyphicon-minus"></span> Remove
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } 
                                        }?>
                                    </div>
                               
                                    <div class="col-md-6 addmoremaindiv">
                                        <?php $minorRegionCount = count($sites['minorRegion']);
                                        if($minorRegionCount == 0) { ?>
                                            <div class="row addmore addmore_div ">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Minor Region</label>
                                                        <input type="text" class="form-control" name="minor_region[]" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                    <button type="button" class="btn btn-success btn-sm addmorefields pull-left" style="margin-bottom: 15px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span> Add
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } else {
                                            foreach ($sites['minorRegion'] as $key => $value) { ?>
                                                <div class="row addmore addmore_div ">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Minor Region</label>
                                                            <input type="text" class="form-control" name="minor_region[]" value="<?php echo $value['minor_region_name'] ?>">
                                                        </div>
                                                    </div>
                                                    <?php if($key == 0){ ?>
                                                        <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                            <button type="button" class="btn btn-success btn-sm addmorefields pull-left" style="margin-bottom: 15px; margin-top:10px;"><span class="glyphicon glyphicon-plus"></span> Add
                                                            </button>
                                                        </div>
                                                    <?php }else{ ?>
                                                        <div class="col-md-4 mt-20 add_remove_button" style="display: none;">
                                                            <button type="button" class="btn btn-danger btn-sm removeaddmore pull-left" style="margin-top:10px;">
                                                                <span class="glyphicon glyphicon-minus"></span> Remove
                                                            </button>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } 
                                        } ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="subRegion">Sub-Region:</label>
                                            <input type="text" id="subRegion" name="subRegion" class="form-control" value="<?php echo $sites['sub_region']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="villageCommunity">Village/Community:</label>
                                            <input type="text" id="villageCommunity" name="villageCommunity" class="form-control" value="<?php echo $sites['village_community']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="communityType">Community Type</label>
                                            <select class="form-control" id="communityType" name="communityType">
                                                <option value="">--Select Community Type--</option>
                                                <?php foreach ($communities_type as $key => $value) { ?>
                                                    <option value="<?php echo $value['id']; ?>" <?php echo ($sites['community_type'] == $value['id']) ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
                                                <?php } ?>
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
                                            <input type="text" id="grazingmetabolisable" value="<?php echo $sites['grazingmetabolisable']; ?>" name="grazingmetabolisable" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="grazingcrude">Grazing Crude Protein Percentage (Default 8%):</label>
                                            <input type="text" id="grazingcrude" value="<?php echo $sites['grazingcrude']; ?>" name="grazingcrude" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="collectedmetabolisable">Collected Fodder Metabolisable Energy MJ/KG (Default 10):</label>
                                            <input type="text" id="collectedmetabolisable" value="<?php echo $sites['collectedmetabolisable']; ?>" name="collectedmetabolisable" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="collectedcrude">Collected Fodder Crude Protein Percentage (Default 8%):</label>
                                            <input type="text" id="collectedcrude" value="<?php echo $sites['collectedcrude']; ?>" name="collectedcrude" class="form-control" >
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
            countries: <?php echo json_encode($countries); ?>,
            currency: <?php echo json_encode($currency); ?>,
            selectedProjectId : <?php echo $this->uri->segment(3); ?>,
            countryVal : <?php echo $sites['country_id']; ?>,
            localCurrency : <?php echo $sites['localCurrency']; ?>
        };
        
        let isEditing = true; // Track whether we are editing a project
        // When world region changes, populate major regions
        $('#worldRegion').change(function() {
            const worldRegionId = $(this).val();
            $('#country').empty().append('<option value="">--Select Country--</option>'); // Clear country dropdown

            const filteredCountries = data.countries.filter(region => region.world_region_id == worldRegionId);
            if (filteredCountries) {
                filteredCountries.forEach(country => {
                    $('#country').append(new Option(country.name, country.country_id));
                });
            }
        });

        $('#worldRegion').trigger('change');

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

        $('#country').val(data.countryVal).change();

        $('#localCurrency').val(data.localCurrency);

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

            const siteId = '<?php echo $this->uri->segment(4); ?>'; // Get site ID from the hidden input

            if (siteId && isEditing) {
                formData.site_id = siteId; // Add site ID to the form data
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