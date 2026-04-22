<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style type="text/css">
    .projimages { width: 150px; height: auto; position: absolute; right: 0; top: 0; }
    .item { width: 29%; margin: 10px; float: left; }
    .chart-title {
        font-family: 'Open Sans', sans-serif;
        font-weight: 600;
        line-height: 1.2;
        margin-bottom: .5rem;
        color: #464855;
        font-size: 18px;
        margin-bottom:15px;
    }
    .font-16px {
        font-size: 16px;
    }
    .mh-100px {
        min-height: 110px;
    }
    .cursor {
        cursor: pointer;
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
    .mt-28px {
        margin-top: 28px;
    }
    .dropdown .dropdown-menu .dropdown-item {
        width: 100%;
        padding: 5px 20px;
    }
    .btn-primary {
        color: #fff;
        border-color: #800000 !important;
        background-color: #800000 !important;
    }
    label {
        font-weight: 600;
        color: #800000 !important;
    }
    .highcharts-legend-item.highcharts-column-series.highcharts-color-undefined rect {
        x: 2.5;
        y: 6;
        rx: 5.5;
        ry: 0!important;
        width: 11;
        height: 11;
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
    ul.scrollSide li  {
        padding: 0px;
        color: #ccc;
        background-color: #333;
        margin-bottom: 1px;
        border-left: 10px solid transparent;
        list-style-type: none;
        padding-left: 0px;
        margin-left:0px
    }
    ul.scrollSide li a {
        padding: 15px;
        color: #000;
        background-color: #fff;
        margin-bottom: 1px;
        border-left: 10px solid transparent;
        list-style-type: none;
        font-size: 14px;
        padding-left: 0px;
        font-weight: 500;
        margin-left: -8px;
        border-bottom: 1px solid #e3bfbf63 !important;
    }
    ul.scrollSide li a.active {
        border-color: #4f7c3d;
    }
    .leftFixedCard {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        box-shadow: 0 1px 15px 1px rgba(62, 57, 107, .07);
        height: 100vh;
        background-color: #fff;
        overflow-y: auto;
    }
    .scrollSide {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .scrollSide li a {
        display: block;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        transition: background 0.3s, color 0.3s;
    }
    .scrollSide li a.active {
        background-color: #f7f7f7;
        color: #000;
        border-radius: 0px;
        padding-left: 10px;
        border-bottom: 1px solid #e3bfbf63 !important;
    }
    .contents {
        margin-left: 0;
        padding: 20px;
    }
    section {
        margin-bottom: 20px;
        padding: 0px;
        min-height: auto;
        display: none; /* Hide all sections initially */
    }
    .mt-71px {
        margin-top: 71px;
    }
    .rightFloat {
        float: right;
        margin-left: 260px;
        width: 100%;
    }
    .no-data-message {
        text-align: center;
        font-size: 24px;
        color: #666;
        margin-top: 80px;
        display: block;
    }

    /* Style for Highcharts data table to add spacing between columns */
    .highcharts-data-table table {
        border-collapse: collapse; /* Use collapse to remove spacing between cells */
        width: 100%;
        max-width: 800px; /* Optional: Limit table width for better readability */
        margin: 10px 0 10px 60px; /* Keep your margin settings */
        table-layout: auto; /* Let table adjust column widths based on content */
    }

    .highcharts-data-table th,
    .highcharts-data-table td {
        padding: 2px 5px; /* Minimal padding for readability, adjust if needed */
        text-align: left; /* Align text for readability */
        border: 1px solid #ddd; /* Keep borders for clarity */
        white-space: nowrap; /* Prevent text wrapping to ensure tight fit */
    }

    .highcharts-data-table th {
        background-color: #f5f5f5; /* Light background for headers */
        font-weight: bold;
    }

    .highcharts-data-table td.highcharts-number {
        text-align: left; /* Keep your alignment for numbers */
    }
</style>

<!-- Page -->
<div class="app-content content" style="margin-left: 0px;">
    <div class="content-wrapper p-2">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card leftFixedCard mt-71px">
                    <div class="card-body p-0">
                        <ul class="scrollSide">
                            <li><a class="active" href="#landholdings">Landholding</a></li>
                            <li><a href="#livestockholdings">Livestock Holding</a></li>
                            <li><a href="#cropcultivations">Crop Cultivation</a></li>
                            <li><a href="#foddercrops">Fodder Crop Cultivation</a></li>
                            <li><a href="#purchasedfeeds">Purchased Feed</a></li>
                            <li><a href="#animaldiets">Animal Diet & Nutrition</a></li>
                            <li><a href="#rainfallFeedAvailability">Rainfall & Feed Availability</a></li>
                            <li><a href="#milkandlivestockprices">Milk and Livestock Prices</a></li>
                            <li><a href="#incomebyactivitys">Income by Activity</a></li>
                            <li><a href="#genderpayequality">Gender Pay Equality</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="rightFloat">
                <div class="content-body contents py-0 px-0">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header bg-dark p-1 cursor" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="true" aria-controls="collapseExample">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="cursor font-18px"> <h2 class="mb-0">Apply Filters</h2></div>
                                        <div class="cursor"><img src="<?php echo base_url(); ?>includeout/images/chevron.png" style="height:24px;"></div>
                                    </div>
                                </div>
                                <div class="card-body collapse show p-1" id="collapseExample">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label for="worldRegion">Select World Region *</label>
                                                <select id="selectWorldRegion" name="selectWorldRegion" class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select World Region" required>
                                                    <?php foreach ($world_region as $key => $wr) { ?>
                                                        <option value="<?php echo $wr['id']; ?>"><?php echo $wr['world_region_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group mb-1">
                                                <label class="">Select Country *</label><br>
                                                <select class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select Country" id="selectCountry" name="selectCountry" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group mb-0">
                                                <label class="">Select Project *</label><br>
                                                <select class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select Project" id="selectProject" name="selectProject" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group mb-0">
                                                <label class="">Select Site</label><br>
                                                <select class="selectpicker downArrow" multiple data-actions-box="true" data-live-search="true" title="Select Site" id="selectSite" name="selectSite">
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-4 col-lg-4">
                                            <div class="form-group mb-0">
                                                <label class="">Select date</label><br>
                                                <input type="text" class="form-control daterange_form" name="daterange" value="" autocomplete="off" />
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-2 col-lg-2">
                                            <button class="btn btn-primary w-100 mt-28px" id="filter_submit" disabled>Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="max-width: 100%">
                        <div class="col-md-12">
                            <button class="btn btn-primary pull-right" id="download" style="display: none;">Generate Report</button>
                        </div>
                    </div>

                    <div class="no-data-message">Please select mandatory filters (*) to view the data</div>

                    <section id="landholdings"> 
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Landholding</h3>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Focus Group Discussion survey based on households and Farm/landholding size"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="households_by_landholding_category" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="livestockholdings">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Livestock Holding</h3>
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on livestock Holding"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="dominant_livestock_categories_by_average_tlus_per_household" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on livestock Holding"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_household_livestock_holdings_by_category_in_tropical_livestock_units" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on livestock Holding"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_household_livestock_holdings_by_type_in_tropical_livestock_units" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="cropcultivations">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Crop Cultivation</h3>                
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Crops and hectors cultivated"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="crop_types_by_average_hectares_cultivated" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Crops grown in the area"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="crops_grown_in_the_area" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="foddercrops">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Fodder Crop Cultivation</h3>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder Type, Fodder Crop Type, Cultivated Area (in hectares), Units, and Crops cultivated."><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="average_hectares_cultivated_per_household_by_fodder_crop_type" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder Type, Fodder Crop Type, Cultivated Area (in hectares), Units, and Crops cultivated."><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="average_hectares_cultivated_per_household_by_fodder_crop_type_all" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="purchasedfeeds">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Purchased Feed</h3>
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder Type, Feed Purchases, and Quantity Purchased."><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_kg_of_feed_purchased_per_household_by_feed_type" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder Type, Feed Purchases, and Quantity Purchased."><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_kg_of_feed_purchased_per_household_by_feed_type_all" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="animaldiets">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Animal Diet & Nutrition</h3>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder and diet"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="dry_matter_intake" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder and diet"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="metabolisable_energy_intake" style="width: 100%; height: 400px;"></div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Fodder and diet"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="crude_protein_intake" style="width: 100%; height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="rainfallFeedAvailability">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Rainfall & Feed Availability</h3>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Feed Availability, including Monthly and Overall Feed Availability, Monthly Diet Composition, Cereal Residues, Legume Residues, Grazing, Concentrates, Green Forage, and Other Sources."><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                                <div id="available_feed_resources" style="width:100%;height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="milkandlivestockprices">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Milk & Livestock Prices</h3>
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on livestock Sales"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_price_of_major_livestock_species_in_usd_by_month" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Milk"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_daily_milk_yield_vs_average_price_received_per_liter" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="incomebyactivitys">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Income by Activity</h3>
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Popertry/Income"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="contribution_of_livelihood_activities_to_household_income" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="genderpayequality">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <h3 class="title">Gender Pay Equality</h3>
                                <div class="card">
                                    <div class="card-body">
                                        <a href="#" data-toggle="tooltip" class="pull-right" title="The data shown in the graph is derived from Individual Farmer Interview survey based on Popertry/Income"><i class="fa fa-info-circle" style="font-size:24px"></i></a>
                                        <div id="average_daily_labour_rates_by_gender" style="width: 100%; height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                </div>
            </div>
        </div>        
    </div>
    <div id="pdf-wrapper" style="margin-left: 100px; justify-content: center; align-items: center;"></div>
</div>
<!-- End Page -->

<script src="<?php echo base_url(); ?>include/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>include/plugins/table_doublescroller/jquery.doubleScroll.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sections = document.querySelectorAll("section");
        const navLinks = document.querySelectorAll(".scrollSide a");
        const headerHeight = 70; // Adjust based on your header height
        const extraMargin = 20; // Adjusted for better visibility
        const sidebar = document.querySelector(".leftFixedCard");

        // Function to reset margins and active states
        const resetStyles = () => {
            sections.forEach((section) => {
                section.style.marginTop = "0";
            });
            navLinks.forEach((link) => {
                link.classList.remove("active");
                link.removeAttribute("aria-current");
            });
        };

        // IntersectionObserver to highlight navigation links
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && entry.target.style.display !== "none") {
                        const targetId = entry.target.id;
                        resetStyles();
                        navLinks.forEach((link) => {
                            if (link.getAttribute("href") === `#${targetId}`) {
                                link.classList.add("active");
                                link.setAttribute("aria-current", "page");
                            }
                        });
                        entry.target.style.marginTop = `${headerHeight + extraMargin}px`;
                    }
                });
            },
            {
                root: null, // Use viewport as root
                rootMargin: `-${headerHeight}px 0px -30% 0px`, // Adjust for header and better detection
                threshold: [0.1, 0.5, 0.9], // Multiple thresholds for smoother transitions
            }
        );

        // Observe sections that are visible
        const observeSections = () => {
            sections.forEach((section) => {
                if (section.style.display !== "none") {
                    observer.observe(section);
                }
            });
        };

        // Initial observation
        observeSections();

        // Re-observe sections when filters are applied (sections become visible)
        const filterSubmit = document.getElementById("filter_submit");
        filterSubmit.addEventListener("click", () => {
            // Wait for sections to become visible
            setTimeout(observeSections, 100);
        });

        // Handle navigation link clicks
        navLinks.forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                const targetId = link.getAttribute("href").slice(1);
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    // Ensure section is visible
                    targetSection.style.display = "block";

                    // Calculate scroll position
                    const sectionTop = targetSection.getBoundingClientRect().top + window.pageYOffset - headerHeight - extraMargin;

                    // Smooth scroll to section
                    window.scrollTo({
                        top: sectionTop,
                        behavior: "smooth",
                    });

                    // Update active state
                    resetStyles();
                    link.classList.add("active");
                    link.setAttribute("aria-current", "page");
                    targetSection.style.marginTop = `${headerHeight + extraMargin}px`;

                    // Ensure section is observed
                    observer.observe(targetSection);
                }
            });
        });

        // Handle initial active section
        const hash = window.location.hash;
        if (hash) {
            const targetSection = document.querySelector(hash);
            if (targetSection) {
                targetSection.style.display = "block";
                const sectionTop = targetSection.getBoundingClientRect().top + window.pageYOffset - headerHeight - extraMargin;
                window.scrollTo({
                    top: sectionTop,
                    behavior: "smooth",
                });
                resetStyles();
                targetSection.style.marginTop = `${headerHeight + extraMargin}px`;
                navLinks.forEach((link) => {
                    if (link.getAttribute("href") === hash) {
                        link.classList.add("active");
                        link.setAttribute("aria-current", "page");
                    }
                });
            }
        }
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

    function ensureChartVisible(chartId) {
        const chartContainer = document.getElementById(chartId);
        if (chartContainer) {
            chartContainer.style.display = 'block';
        }
    }

    $('#download').on('click', function() {
        console.log('Starting PDF generation...');
        const today = new Date();
        const dateStr = `${today.getFullYear()}${String(today.getMonth() + 1).padStart(2, '0')}${String(today.getDate()).padStart(2, '0')}_${String(today.getHours()).padStart(2, '0')}${String(today.getMinutes()).padStart(2, '0')}`;
        const name = `Generated Report_${dateStr}`;

        $(this).prop('disabled', true);
        $(this).html('<span class="spinner-border spinner-border-sm" role="status"></span> Generating...');

        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        const pageWidth = 297;
        const marginLeft = 20;
        const marginRight = 20;
        const marginTop = 30;
        const maxChartWidth = pageWidth - marginLeft - marginRight;
        const chartHeight = 110;

        const logoImg = new Image();

        function captureChart(chartId, callback) {
            console.log(`Capturing chart: ${chartId}`);
            ensureChartVisible(chartId);
            let chart = null;
            Highcharts.charts.forEach(c => {
                if (c && c.renderTo && c.renderTo.id === chartId) {
                    chart = c;
                }
            });

            if (!chart) {
                console.warn(`Chart with ID ${chartId} not found.`);
                callback(null);
                return;
            }

            try {
                const svg = chart.getSVG({
                    chart: { width: 1300, height: 500 },
                    exporting: { scale: 2 }
                });
                console.log(`SVG length for ${chartId}: ${svg.length}`);
                const base64Svg = btoa(unescape(encodeURIComponent(svg)));
                const img = new Image();
                img.src = 'data:image/svg+xml;base64,' + base64Svg;

                img.onload = function() {
                    console.log(`SVG loaded for ${chartId}`);
                    const canvas = document.createElement('canvas');
                    canvas.width = 1300;
                    canvas.height = 500;
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = '#fff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, 1300, 500);
                    const imageData = canvas.toDataURL('image/jpeg', 0.98);
                    console.log(`Image data generated for ${chartId}: ${imageData.substring(0, 50)}...`);
                    callback(imageData);
                };

                img.onerror = function() {
                    console.warn(`Failed to load SVG for ${chartId}. Falling back to html2canvas...`);
                    const chartContainer = document.getElementById(chartId);
                    if (!chartContainer) {
                        console.error(`Chart container ${chartId} not found for html2canvas.`);
                        callback(null);
                        return;
                    }
                    html2canvas(chartContainer, {
                        scale: 2,
                        width: 1300,
                        height: 500,
                        backgroundColor: '#fff',
                        logging: true
                    }).then(canvas => {
                        const imageData = canvas.toDataURL('image/jpeg', 0.98);
                        console.log(`html2canvas image data for ${chartId}: ${imageData.substring(0, 50)}...`);
                        callback(imageData);
                    }).catch(err => {
                        console.error(`html2canvas failed for ${chartId}:`, err);
                        callback(null);
                    });
                };
            } catch (err) {
                console.error(`Error capturing chart ${chartId}:`, err);
                callback(null);
            }
        }

        let chartIndex = 0;
        let pageCount = 1;
        let failedCharts = [];

        function processNextChart() {
            if (chartIndex >= chartIds.length) {
                console.log('All charts processed, finalizing PDF...');
                try {
                    if (chartIndex > 0 && logoImg.complete && logoImg.naturalWidth !== 0) {
                        const imgWidth = 50;
                        const imgHeight = (logoImg.height * imgWidth) / logoImg.width;
                        pdf.addImage(logoImg, 'PNG', 20, 20, imgWidth, imgHeight);
                        console.log('Logo added to PDF.');
                    } else {
                        console.warn('Logo not loaded or already added, skipping...');
                    }
                } catch (err) {
                    console.error('Error adding logo:', err);
                }
                finalizePdf();
                return;
            }

            const chartId = chartIds[chartIndex];
            captureChart(chartId, function(imageData) {
                if (imageData) {
                    try {
                        if (chartIndex > 0) {
                            pdf.addPage();
                        }
                        if (chartIndex === 0 && logoImg.complete && logoImg.naturalWidth !== 0) {
                            const imgWidth = 50;
                            const imgHeight = (logoImg.height * imgWidth) / logoImg.width;
                            pdf.addImage(logoImg, 'PNG', 20, 20, imgWidth, imgHeight);
                            console.log('Logo added to first chart page.');
                        }
                        pdf.setFontSize(14);
                        pdf.setTextColor(70, 72, 85);
                        pdf.setFontSize(12);
                        pdf.setTextColor(102, 102, 102);
                        pdf.addImage(imageData, 'JPEG', marginLeft, marginTop + 20, maxChartWidth, chartHeight);
                        pdf.setFontSize(10);
                        pdf.setTextColor(100);
                        pdf.text(`Page ${pageCount} of ${chartIds.length}`, 257, 200);
                        console.log(`Added chart ${chartId} to PDF on page ${pageCount}`);
                        pageCount++;
                    } catch (err) {
                        console.error(`Error adding chart ${chartId} to PDF:`, err);
                        failedCharts.push(chartId);
                    }
                } else {
                    failedCharts.push(chartId);
                }
                chartIndex++;
                processNextChart();
            });
        }

        function finalizePdf() {
            try {
                console.log('Saving PDF...');
                pdf.save(`${name}.pdf`);
                console.log('PDF save initiated.');
            } catch (err) {
                console.error('Error saving PDF:', err);
                Swal.fire('Error!', 'Failed to save PDF. Please try again.', 'error');
            } finally {
                $('#download').prop('disabled', false).html('Generate Report');
                if (failedCharts.length > 0) {
                    Swal.fire('Warning', `Some charts could not be included: ${failedCharts.join(', ')}`, 'warning');
                }
            }
        }

        chartIds.forEach(chartId => ensureChartVisible(chartId));
        processNextChart();
    });

    $('.projectdetails').on('click', function(){
        $elem = $(this);
        var project_id = $elem.data('project_id');
        var projectname = $elem.data('projectname');

        if($('.projectsurveys_'+project_id+'').hasClass('hidden')){
            $('.projectsurveys_'+project_id+'').removeClass('hidden');
            $elem.html('<i class="ft-minus-square"></i> '+projectname+'');
        }else{
            $('.projectsurveys_'+project_id+'').addClass('hidden');
            $elem.html('<i class="ft-plus-square"></i>'+projectname+'');
        }        
    });
</script>

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

        $('.selectpicker').selectpicker({
            liveSearch: true,
            actionsBox: true,
            selectedTextFormat: 'count > 3', // Show count for >3 selections
            width: '100%' // Force dropdown to parent width
        });

        $('#selectWorldRegion').change(function() {
            $('#selectCountry').empty();
            $('#selectCountry').selectpicker('refresh');
            $('#selectProject').empty();
            $('#selectProject').selectpicker('refresh');
            $('#selectSite').empty();
            $('#selectSite').selectpicker('refresh');
            $('#filter_submit').prop('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>reports/getCountryList",
                data: {
                    world_region_id: $(this).val()
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
            $('#filter_submit').prop('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>reports/getProjectsList",
                data: {
                    world_region_id: $('#selectWorldRegion').val(),
                    country_id: $(this).val()
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
                        if ($('#selectProject').val() && $('#selectProject').val().length > 0) {
                            $('#filter_submit').prop('disabled', false);
                        }
                    }
                }
            });
        });

        $('#selectProject').change(function() {
            $('#selectSite').empty();
            $('#selectSite').selectpicker('refresh');
            if ($(this).val() && $(this).val().length > 0) {
                $('#filter_submit').prop('disabled', false);
            } else {
                $('#filter_submit').prop('disabled', true);
            }
            $.ajax({
                url: "<?php echo base_url(); ?>reports/getSitesList",
                data: {
                    world_region_id: $('#selectWorldRegion').val(),
                    country_id: $('#selectCountry').val(),
                    project_id: $(this).val()
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

        $('#filter_submit').click(function(event) {
            event.preventDefault();
            const worldRegion = $('#selectWorldRegion').val();
            const country = $('#selectCountry').val();
            const project = $('#selectProject').val();

            if (!worldRegion || worldRegion.length === 0) {
                $.toast({
                    heading: 'Error!',
                    text: 'Please select at least one World Region.',
                    icon: 'error'
                });
                return;
            }
            if (!country || country.length === 0) {
                $.toast({
                    heading: 'Error!',
                    text: 'Please select at least one Country.',
                    icon: 'error'
                });
                return;
            }
            if (!project || project.length === 0) {
                $.toast({
                    heading: 'Error!',
                    text: 'Please select at least one Project.',
                    icon: 'error'
                });
                return;
            }

            $('.no-data-message').hide();
            $('section').show();
            $('#download').show();

            call_apis();
        });
    });
</script>

<script>
    function call_apis() {
        $("#download").prop('disabled', true);
        var dateRange = $('input[name="daterange"]').val();
        let start_date;
        let end_date;
        if (dateRange) {
            var dates = dateRange.split(' - ');
            start_date = dates[0];
            end_date = dates[1];
        }

        var query_data = {
            worldRegionIds: $('#selectWorldRegion').val(),
            countryIds: $('#selectCountry').val(),
            projectIds: $('#selectProject').val(),
            siteIds: $('#selectSite').val(),
            startDate: start_date,
            endDate: end_date,
        };
        $.ajax({
            url: "<?php echo base_url(); ?>reports/get_dashboard_data",
            data: query_data,
            type: "POST",
            dataType: "JSON",
            error: function() {
                $.toast({
                    heading: 'Error!',
                    text: 'Failed to fetch data. Please try again.',
                    icon: 'error'
                });
                $('.no-data-message').show();
                $('section').hide();
                $('#download').hide();
            },
            success: function(response) {
                land_holding_chart(response.landholding);
                crop_cultivated_area_chart(response.crop_cultivated_area.top5);
                crops_grown_in_the_area_chart(response.crop_cultivated_area.all);
                fodder_crop_cultivated_area_chart(response.fodder_crop_cultivated_area.top5);
                fodder_crop_cultivated_area_chart_all(response.fodder_crop_cultivated_area.all);
                avg_feed_purchased_top5(response.avg_feed_purchased.top5);
                avg_feed_purchased_all(response.avg_feed_purchased.all);
                feed_availability(response.feed_availability);
                income_by_activity(response.income_by_activity);
                avg_livestock_price(response.avg_livestock_price);
                avg_daily_milk_price(response.avg_daily_milk_price);
                dominant_livestock_categories(response.dominant_livestock_categories);
                average_household_livestock_holdings_category(response.average_household_livestock_holdings_category);
                average_household_livestock_holdings_type(response.average_household_livestock_holdings_type);
            
                const genderData = response.gender_pay_equality;
                const bar_data = [
                    { name: 'Average Pay Female', y: genderData.female_avg },
                    { name: 'Average Pay Male', y: genderData.male_avg }
                ];
                gender_pay_equality(bar_data);

                dry_matter_intake(response.intake_values.dry_matter_intake);
                metabolisable_energy_intake(response.intake_values.metabolisable_energy_intake);
                crude_protein_intake(response.intake_values.crude_protein_intake);
                $("#download").prop('disabled', false);
            }
        });
    }
    
    // Add global Highcharts configuration to hide chart title in data table
    Highcharts.setOptions({
        exporting: {
            showTable: true,
            tableCaption: false // Disable the chart title as table caption
        }
    });

    // Landholding Chart
    function land_holding_chart(series_data) {
        Highcharts.chart('households_by_landholding_category', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: 'Households by landholding category'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Percentage'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1, // Add spacing between individual columns
                    groupPadding: 0.2 // Add spacing between groups
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Landholding',
                    color: '#4bacc6',
                    data: [
                        { name: 'Small', y: +series_data[0] },
                        { name: 'Medium', y: +series_data[1] },
                        { name: 'Large', y: +series_data[2] }
                    ]
                }
            ],
            exporting: {
                filename: 'households_by_landholding_category'
            }
        });
    }

    // Crop Cultivation Charts
    function crop_cultivated_area_chart(bar_data) {
        bar_data = bar_data.filter(item => item.y > 0);
        Highcharts.chart('crop_types_by_average_hectares_cultivated', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Dominant Crop Types by Average Hectares Cultivated (Up to 5)'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Hectares Under Cultivation'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b> <br/>'
            },
            series: [
                {
                    name: 'Dominant Crop Types by Average Hectares Cultivated (Up to 5)',
                    color: '#ed7d31',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'crop_types_by_average_hectares_cultivated'
            }
        });
    }

    function crops_grown_in_the_area_chart(bar_data) {
        Highcharts.chart('crops_grown_in_the_area', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Crop Types by Average Hectares Cultivated'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Hectares Under Cultivation'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b> <br/>'
            },
            series: [
                {
                    name: 'Crop Types by Average Hectares Cultivated',
                    color: '#5b9bd5',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'crops_grown_in_the_area'
            }
        });
    }

    // Fodder Crop Cultivation Charts
    function fodder_crop_cultivated_area_chart(bar_data) {
        bar_data = bar_data.filter(item => item.y > 0);
        Highcharts.chart('average_hectares_cultivated_per_household_by_fodder_crop_type', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Dominant Fodder Crops by Average Hectares Cultivated (Up to 5)'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Hectares Under Cultivation'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Dominant Fodder Crops by Average Hectares Cultivated (Up to 5)',
                    color: '#ed7d31',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_hectares_cultivated_per_household_by_fodder_crop_type'
            }
        });
    }

    function fodder_crop_cultivated_area_chart_all(bar_data) {
        Highcharts.chart('average_hectares_cultivated_per_household_by_fodder_crop_type_all', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Average Hectares Cultivated per Household by Fodder Crop Type'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Hectares Under Cultivation'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Average Hectares Cultivated per Household by Fodder Crop Type',
                    color: '#ed7d31',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_hectares_cultivated_per_household_by_fodder_crop_type_all'
            }
        });
    }

    // Purchased Feed Charts
    function avg_feed_purchased_top5(bar_data) {
        bar_data = bar_data.filter(item => item.y > 0);
        Highcharts.chart('average_kg_of_feed_purchased_per_household_by_feed_type', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Dominant Purchased Feed Types by kg Purchased (Up to 5)'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Kg purchased per Year'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Dominant Purchased Feed Types by kg Purchased (Up to 5)',
                    color: '#f4b183',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_kg_of_feed_purchased_per_household_by_feed_type'
            }
        });
    }

    function avg_feed_purchased_all(bar_data) {
        Highcharts.chart('average_kg_of_feed_purchased_per_household_by_feed_type_all', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Average kg of Feed Purchased per Household by Feed Type'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'Kg purchased per Year'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Average kg of Feed Purchased per Household by Feed Type',
                    color: '#f4b183',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_kg_of_feed_purchased_per_household_by_feed_type_all'
            }
        });
    }

    // Feed Availability Chart
    function feed_availability(chart_data) {
        Highcharts.chart('available_feed_resources', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Available Feed Resource',
                align: 'left'
            },
            xAxis: {
                categories: chart_data['months'],
                title: {
                    text: 'Months'
                }
            },
            yAxis: [{
                title: {
                    text: 'Availability of Feed (0 - 10)',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                stackLabels: {
                    enabled: false
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min: 0,
                max: 10
            }, {
                title: {
                    text: 'Rainfall (0-5)',
                    style: {
                        color: '#a8d8ea'
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#a8d8ea'
                    }
                },
                opposite: true,
                min: 0,
                max: 5
            }],
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false
                    },
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            series: [
                {
                    name: 'Cereal Residues',
                    color: '#a3d9a5',
                    data: chart_data['feed_types']['Cereal Crop'],
                    yAxis: 0
                },
                {
                    name: 'Concentrates',
                    color: '#9fc6e7',
                    data: chart_data['feed_types']['Concentrates'],
                    yAxis: 0
                },
                {
                    name: 'Grazing',
                    color: '#f5b8c3',
                    data: chart_data['feed_types']['Grazing'],
                    yAxis: 0
                },
                {
                    name: 'Legume Residues',
                    color: '#f6e57b',
                    data: chart_data['feed_types']['Leguminous'],
                    yAxis: 0
                },
                {
                    name: 'Green Forage',
                    color: '#d6e6a8',
                    data: chart_data['feed_types']['Green Forage'],
                    yAxis: 0
                },
                {
                    name: 'Other',
                    color: '#f7d7d7',
                    data: chart_data['feed_types']['Other'],
                    yAxis: 0
                },
                {
                    name: 'Rainfall Pattern',
                    color: '#a8d8ea',
                    data: chart_data['avg_rainfall'],
                    yAxis: 1,
                    type: 'line'
                }
            ],
            exporting: {
                filename: 'available_feed_resources'
            }
        });
    }

    // Income by Activity (Pie Chart)
    function income_by_activity(pie_data) {
        Highcharts.chart('contribution_of_livelihood_activities_to_household_income', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Average Household Income by Activity Category',
                align: 'left'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    borderWidth: 2,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
                        distance: 20
                    }
                }
            },
            series: [{
                name: 'Income Source',
                colorByPoint: true,
                data: pie_data
            }],
            exporting: {
                filename: 'contribution_of_livelihood_activities_to_household_income'
            }
        });
    }

    // Average Livestock Price Chart
    function avg_livestock_price(bar_data) {
        // Preprocess data to convert zeros to null
        const processedData = {
            Cattle: bar_data['Cattle'].map(value => (value === 0 ? null : value)),
            Sheep: bar_data['Sheep'].map(value => (value === 0 ? null : value)),
            Goat: bar_data['Goat'].map(value => (value === 0 ? null : value))
        };

        Highcharts.chart('average_price_of_major_livestock_species_in_usd_by_month', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Average price of major livestock species in USD by month',
                align: 'left'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ],
                crosshair: true,
                title: {
                    text: 'Months'
                }
            },
            yAxis: [{
                title: {
                    text: 'Cattle Price (USD)',
                    style: {
                        color: '#a3d8a5' // Green for Cattle
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#a3d8a5'
                    }
                },
                min: 0
            }, {
                title: {
                    text: 'Sheep/Goat Price (USD)',
                    style: {
                        color: '#8fc1e3' // Blue for Sheep/Goat
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#8fc1e3'
                    }
                },
                opposite: true,
                min: 0
            }],
            tooltip: {
                shared: true,
                valueDecimals: 2
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Average Price Cattle',
                color: '#a3d8a5', // Green for Cattle
                data: processedData['Cattle'],
                yAxis: 0,
                marker: {
                    symbol: 'circle'
                }
            }, {
                name: 'Average Price Sheep',
                color: '#8fc1e3', // Blue for Sheep
                data: processedData['Sheep'],
                yAxis: 1,
                marker: {
                    symbol: 'square'
                }
            }, {
                name: 'Average Price Goat',
                color: '#f7b267', // Orange for Goat
                data: processedData['Goat'],
                yAxis: 1,
                marker: {
                    symbol: 'triangle'
                }
            }],
            exporting: {
                filename: 'average_price_of_major_livestock_species_in_usd_by_month'
            }
        });
    }

    // Average Daily Milk Price Chart
    function avg_daily_milk_price(bar_data) {
        Highcharts.chart('average_daily_milk_yield_vs_average_price_received_per_liter', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Average Daily Milk Yield vs Average Price Received per Liter (USD)',
                align: 'left'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: bar_data['months'],
                title: {
                    text: 'Months'
                },
                crosshair: true
            },
            yAxis: [{
                title: {
                    text: 'Yield (L)',
                    style: {
                        color: '#8fc1e3'
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#8fc1e3'
                    }
                },
                tickInterval: 2,
                min: 0
            }, {
                title: {
                    text: 'Price (USD)',
                    style: {
                        color: '#a3d8a5'
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#a3d8a5'
                    }
                },
                tickInterval: 0.1,
                opposite: true,
                min: 0
            }],
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },
            series: [{
                name: 'Average Yield (L)',
                color: '#8fc1e3',
                data: bar_data['milk'],
                yAxis: 0
            }, {
                name: 'Average Price per Litre (USD)',
                color: '#a3d8a5',
                data: bar_data['price'],
                yAxis: 1
            }],
            exporting: {
                filename: 'average_daily_milk_yield_vs_average_price_received_per_liter'
            }
        });
    }

    // Dominant Livestock Categories Chart
    function dominant_livestock_categories(bar_data) {
        bar_data = bar_data.filter(item => item.y > 0);
        Highcharts.chart('dominant_livestock_categories_by_average_tlus_per_household', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Dominant Livestock Categories by Average TLUs per Household (Top 5)'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'TLUs'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Dominant Livestock Categories by Average TLUs per Household (Top 5)',
                    color: '#ed7d31',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'dominant_livestock_categories_by_average_tlus_per_household'
            }
        });
    }

    // Average Household Livestock Holdings by Category
    function average_household_livestock_holdings_category(bar_data) {
        Highcharts.chart('average_household_livestock_holdings_by_category_in_tropical_livestock_units', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Average Household Livestock Holdings by Category in Tropical Livestock Units (TLUs)'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'TLUs'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Average Household Livestock Holdings by Category in Tropical Livestock Units (TLUs)',
                    color: '#ed7d31',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_household_livestock_holdings_by_category_in_tropical_livestock_units'
            }
        });
    }

    // Average Household Livestock Holdings by Type
    function average_household_livestock_holdings_type(bar_data) {
        Highcharts.chart('average_household_livestock_holdings_by_type_in_tropical_livestock_units', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Average Household Livestock Holdings by Type in Tropical Livestock Units (TLUs)'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                }
            },
            yAxis: {
                title: {
                    text: 'TLUs'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}</b><br/>'
            },
            series: [
                {
                    name: 'Average Household Livestock Holdings by Type in Tropical Livestock Units (TLUs)',
                    color: '#ed7d31',
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_household_livestock_holdings_by_type_in_tropical_livestock_units'
            }
        });
    }

    function gender_pay_equality(bar_data) {
        console.log(bar_data)
        Highcharts.chart('average_daily_labour_rates_by_gender', {
            chart: {
                type: 'column'
            },
            title: {
                align: '',
                text: 'Average Daily Labour Rates by Gender'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                title: {
                    text: ''
                },
                categories: ['Average Pay Female', 'Average Pay Male']
            },
            yAxis: {
                title: {
                    text: 'Daily Labour Rate (USD)'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0
                },
                column: {
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }
            },
            credits: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.5f}</b> USD<br/>'
            },
            series: [
                {
                    name: 'Average Daily Labour Rates by Gender',
                    colorByPoint: true,
                    colors: ['#99cc66', '#6699cc'], // Green for Female, Blue for Male
                    data: bar_data
                }
            ],
            exporting: {
                filename: 'average_daily_labour_rates_by_gender'
            }
        });
    }

    function dry_matter_intake(data) {
        Highcharts.chart('dry_matter_intake', {
            chart: {
                type: 'pie'
            },
            title: {
                align: 'center',
                text: 'Dry Matter Intake by Source'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            tooltip: {
                pointFormat: '<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Intake',
                colorByPoint: true,
                colors: ['#f4a261', '#e76f51', '#2a9d8f', '#264653', '#e9c46a'],
                data: data || [] // Use provided data or empty array
            }],
            credits: {
                enabled: false
            },
            exporting: {
                filename: 'dry_matter_intake'
            }
        });
    }

    function metabolisable_energy_intake(data) {
        Highcharts.chart('metabolisable_energy_intake', {
            chart: {
                type: 'pie'
            },
            title: {
                align: 'center',
                text: 'Metabolisable Energy Intake by Source'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            tooltip: {
                pointFormat: '<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Intake',
                colorByPoint: true,
                colors: ['#f4a261', '#e76f51', '#2a9d8f', '#264653', '#e9c46a'],
                data: data || [] // Use provided data or empty array
            }],
            credits: {
                enabled: false
            },
            exporting: {
                filename: 'metabolisable_energy_intake'
            }
        });
    }

    function crude_protein_intake(data) {
        Highcharts.chart('crude_protein_intake', {
            chart: {
                type: 'pie'
            },
            title: {
                align: 'center',
                text: 'Crude Protein Intake by Source'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            tooltip: {
                pointFormat: '<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Intake',
                colorByPoint: true,
                colors: ['#f4a261', '#e76f51', '#2a9d8f', '#264653', '#e9c46a'],
                data: data || [] // Use provided data or empty array
            }],
            credits: {
                enabled: false
            },
            exporting: {
                filename: 'crude_protein_intake'
            }
        });
    }
</script>