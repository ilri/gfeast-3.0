<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
    border-color: #007bff !important;
    background-color: #007bff !important;
}
.highcharts-legend-item.highcharts-column-series.highcharts-color-undefined rect {
    x: 2.5;
    y: 6;
    rx: 5.5;
    ry: 0!important;
    width: 11;
    height: 11;
    
}
</style>

<!-- Page -->
<div class="app-content content" style="margin-left: 0px;">
	<div class="content-wrapper">
		<div class="content-body">

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-12 col-md-10 col-lg-10">
								<div class="row">
								<div class="col-sm-12 col-md-3 col-lg-3">
								<div class="form-group mb-0">
									<label class="">Select country</label><br>
									<select class="selectpicker" multiple data-actions-box="true" title="Select Country">
										<option>India</option>
										<option>Zambia</option>
										<option>Srilanka</option>
										<option>Senegal</option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-3">
								<div class="form-group mb-0">
									<label class="">Select State</label><br>
									<select class="selectpicker" multiple data-actions-box="true" title="Select State">
										<option>Andhra Pradesh</option>
										<option>Assam</option>
										<option>Tegangana</option>
										<option>Tamilnadu</option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-3">
								<div class="form-group mb-0">
									<label class="">Select district </label><br>
									<select class="selectpicker" multiple data-actions-box="true" title="Select district ">
										<option>Ananthapur</option>
										<option>Nellore</option>
										<option>Karnool</option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-3 col-lg-3">
							<div class="form-group mb-0">
									<label class="">Select date </label><br>
									<input type="text" class="form-control daterange_form" name="daterange" value="" />
								</div>
							</div>
								</div>
							</div>
							<div class="col-sm-12.col-md-2 col-lg-2">
								<button class="btn btn-primary w-100 mt-28px">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Land Holding</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Households by landholding category</h5>
                                <div id="landholding" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Range of land size in hectare</h5>
                                <div id="range_of_land_size" style="width:100%;height:400px;"></div>
                            </div>
                         </div>
                    </div>
                </div>
				

               
                
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
                <h3 class="title">Livestock Holding</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Dominant livestock categories by average TLUs per household</h5>
                                <div id="livestock_holding" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Average household livestock holdings by category in TLUs</h5>
                                <div id="average_livestock_holding" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Average household livestock holdings by type of TLUs</h5>
                                <div id="average_household_livestock" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Average livestock holdings (number of heads) per household based on wealth</h5>
                                <div id="average_livestock_holding_wealth" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Average livestock species holdings per household in TLU</h5>
                                <div id="average_livestock_species_holding" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Average livestock holdings per household – dominant species (TLU)</h5>
                                <div id="average_livestock_holding_dominant_species" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
		    </div>
	    </div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Crop cultivation</h3>
				
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Dominant crop types by average hectares cultivated</h5>
                                <div id="crop_type_by_average_hectares" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Crop types by average hectares cultivated</h5>
                                <div id="crop_type_by_average_hectares_cultivated" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Crops grown in the area</h5>
                                <div id="crops_grown_in_the_area" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Average area (ha) per hh of dominanat arable crops</h5>
                                <div id="average_area_per_hh" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>

                </div>

			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Fodder crop</h3>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title">Dominant fodder crops by average hectares cultivated</h5>
                                <div id="fodder_crop_by_average_hectares" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title"> Average hectares cultivated per household by fodder crop type</h5>
                                <div id="average_hectares_cultivated_fodder_crop_type" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title"> Fodder crops grown in the area</h5>
                                <div id="fodder_crops_grown_in_the_area" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="chart-title"> The dominant fodder crops grown in the area </h5>
                                <div id="dominant_fodder_crops_grown_in_the_area" style="width:100%;height:400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
				

				

			</div>
		</div>


		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Purchased feed</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Dominant purchased feed types by kg purchased</h5>
						<div id="purchased_feed_types" style="width:100%;height:400px;"></div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Average kg of feed purchased per household by feed type</h5>
						<div id="average_purchased_feed_types" style="width:100%;height:400px;"></div>
					</div>
				</div>

                <div class="card">
					<div class="card-body">
						<h5 class="chart-title">Available feed resources</h5>
						<div id="available_feed_resources" style="width:100%;height:400px;"></div>
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Animal diet & nutrition</h3>

				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-6">
						<div class="card">
							<div class="card-body">
								<h5 class="chart-title">Dry matter (DM) intake by source</h5>
								<div id="dry_matter_intake_by_source" style="width:100%;height:400px;"></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-6">
						<div class="card">
							<div class="card-body">
								<h5 class="chart-title">Metabolizable energy (ME) intake by source</h5>
								<div id="metabolizable_energy_intake_by_source" style="width:100%;height:400px;"></div>
							</div>
						</div>
					</div>

					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="card">
							<div class="card-body">
								<h5 class="chart-title">Crude protein (CP) intake by source</h5>
								<div id="crude_protein_intake_by_source" style="width:100%;height:400px;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Rainfall & feed availability</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Month-wise rainfall & feed availability</h5>
						<div id="month_wise_rainfall_feed_availability" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Milk & Livestock prices</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Average price of major livestock species in USD by month</h5>
						<div id="milk_and_livestock_price" style="width:100%;height:400px;"></div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Average daily milk yield v/s Average price received per liter (USD)</h5>
						<div id="average_daily_milk_and_livestock_price" style="width:100%;height:400px;"></div>
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Income by activity</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Average household income by activity category</h5>
						<div id="income_by_activity" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender pay equality</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Average daily labor rates by gender (in USD)</h5>
						<div id="gender_pay_equality" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

        <div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Contribution</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Contribution of livelihood activities to household income (as a percnetage)</h5>
						<div id="contribution_of_livelihood" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Key statistics</h3>
				<h5 class="chart-title">Livestock key statistics</h5>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Percentage offtake cattle (%)</h4></div>
							<div><h4 class="text-primary mb-0"><strong>9%</strong></h4></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Percentage offtake sheep and goats (%)</h4></div>
							<div><h4 class="text-primary mb-0"><strong>15%</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Average annual income from milk sales</h4></div>
							<div><h4 class="text-primary mb-0"><strong>0.00	In Rwandan Franc</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Amount spent on purchased feeds</h4></div>
							<div><h4 class="text-primary mb-0"><strong>2,18,100.00	In Rwandan Franc</strong></h4></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Percentage of income from milk sales spent on purchased feeds</h4></div>
							<div><h4 class="text-primary mb-0"><strong>N/A</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Total amount of milk produced per year</h4></div>
							<div><h4 class="text-primary mb-0"><strong>8116.333333	Litres/hh</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Average price received for milk throughout the year</h4></div>
							<div><h4 class="text-primary mb-0"><strong>323.61	In Rwandan Franc</strong></h4></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Average price received for milk throughout the year</h4></div>
							<div><h4 class="text-primary mb-0"><strong>0.26	In US Dollar	</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Total amount of milk retained throughout the year</h4></div>
							<div><h4 class="text-primary mb-0"><strong>1471	Litres/hh</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Percentage of milk sold</h4></div>
							<div><h4 class="text-primary mb-0"><strong>82%</strong></h4></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Average production per female dairy animal per day</h4></div>
							<div><h4 class="text-primary mb-0"><strong>15.77 Litres/cow/d</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Average production per lactating dairy animal per day</h4></div>
							<div><h4 class="text-primary mb-0"><strong>17.65	litres/cow</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">DM amount (kg)  diet per household</h4></div>
							<div><h4 class="text-primary mb-0"><strong>7508.860	kg</strong></h4></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">ME amount (MJ)  diet per household</h4></div>
							<div><h4 class="text-primary mb-0"><strong>79436.319 MJ</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">CP amount  diet (kg) per household</h4></div>
							<div><h4 class="text-primary mb-0"><strong>780.175 kg</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">CP:ME ratio</h4></div>
							<div><h4 class="text-primary mb-0"><strong>9.821 g CP/MJ</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Milk yield per MJ ME</h4></div>
							<div><h4 class="text-primary mb-0"><strong>0.102 litres/MJ</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Total crop area per household</h4></div>
							<div><h4 class="text-primary mb-0"><strong>1.181666667	ha</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Total forage area per household	</h4></div>
							<div><h4 class="text-primary mb-0"><strong>0.169166667	ha</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">CR yield per ha (=total CR DM/total crop area)</h4></div>
							<div><h4 class="text-primary mb-0"><strong>1927.70	kg DM/ha</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Forage yield per ha (=total forage DM/total	</h4></div>
							<div><h4 class="text-primary mb-0"><strong>16000.00	kg DM/ha</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-4 col-lg-4">
				<div class="card mh-100px">
					<div class="card-body">
						<div class="text-center">
							<div><h4 class="font-16px">Forage crop area as percentage of cropped</h4></div>
							<div><h4 class="text-primary mb-0"><strong>13%</strong></h4></div>
						</div>	
					</div>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender land & farm size</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Land ownership by gender</h5>
						<div id="land_ownership_by_gender" style="width:100%;height:400px;"></div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Farm size by gender of household head</h5>
						<div id="farm_size_by_gender_of_householdhead" style="width:100%;height:400px;"></div>
					</div>
				</div>


			</div>
		</div>


		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender livestock & crops</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Dominant livestock categories in TLUs by gender of HH</h5>
						<div id="livestock_categories_by_gender" style="width:100%;height:400px;"></div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Dominant crop types in average hectares cultivated by gender of HH</h5>
						<div id="average_hectares_cultivated_by_gender_of_hh" style="width:100%;height:400px;"></div>
					</div>
				</div>


			</div>
		</div>


		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender Coop membership</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Coop/Farmer organization membership by gender</h5>
						<div id="coop_farmer_organization_gender" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender division of labor</h3>
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Gender division of labor in feed production, harvesting & feeding</h5>
						<div id="gender_division_of_labor_feed_production" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender decision making</h3>

					<div class="card">
						<div class="card-body">
							<h5 class="chart-title">Gendered decision making on livestock</h5>
							<div id="gendered_decision_making_on_livestock" style="width:100%;height:400px;"></div>
						</div>
					</div>

					<div class="card">
						<div class="card-body">
							<h5 class="chart-title">Gendered decision making on sales of livestock & milk</h5>
							<div id="gendered_decision_making_on_sales_of_livestock" style="width:100%;height:400px;"></div>
						</div>
					</div>

					<div class="card">
						<div class="card-body">
							<h5 class="chart-title">Gendered decision making on crop & feeding</h5>
							<div id="gendered_decision_making_on_crop_feeding" style="width:100%;height:400px;"></div>
						</div>
					</div>

					<div class="card">
						<div class="card-body">
							<h5 class="chart-title">Gendered decision making on major sources of HH income</h5>
							<div id="gendered_decision_making_on_major_source" style="width:100%;height:400px;"></div>
						</div>
					</div>

			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h3 class="title">Gender women’s income</h3>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-6">
						<div class="card">
							<div class="card-body">
								<h5 class="chart-title">Major sources of income for women by activity category</h5>
								<div id="major_source_of_income_for_women" style="width:100%;height:400px;"></div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-6">
						<div class="card">
								<div class="card-body">
									<h5 class="chart-title">Relative contribution of major sources of income to HH & women’s income</h5>
									<div id="relative_contribution_of_major_source_of_income" style="width:100%;height:400px;"></div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>

		<!-- <div class="row">
			<div class="col-sm-12 ol-md-12 col-lg-12">
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Livestock holdings per households</h5>
						<div id="livestock_holdings_per_households" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-12 ol-md-12 col-lg-12">
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Livestock holdings</h5>
						<div id="livestock_holdings" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 ol-md-12 col-lg-12">
				<div class="card">
					<div class="card-body">
						<h5 class="chart-title">Crop varieties grown</h5>
						<div id="crop_varieties_grown" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>

		

		<div class="row">
			<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
					<h5 class="chart-title">Sources of energy</h5>
					<div id="sources_of_energy" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6 col-lg-6">
			<div class="card">
					<div class="card-body">
					<h5 class="chart-title">Where is the energy coming from?</h5>
					<div id="the_energy_coming" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-12 col-md-6 col-lg-6">
				<div class="card">
					<div class="card-body">
					<h5 class="chart-title">Sources of protein per hh</h5>
					<div id="sources_of_protein_per_hh" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-6 col-lg-6">
			<div class="card">
					<div class="card-body">
					<h5 class="chart-title">Where is the protein coming from?</h5>
					<div id="the_protein_coming" style="width:100%;height:400px;"></div>
					</div>
				</div>
			</div>
		</div> -->


		

			<!-- <div class="row">
				<div id="recent-sales" class="col-12 col-md-12">
					<div class="card">
						<div class="card-content mt-1">
							<div class="table-responsive">
								<table id="recent-orders" class="table table-hover table-xl mb-0">
									<thead>
										<tr>
											<th class="border-top-0">Project name</th>
											<th class="border-top-0">No.of surveys</th>
											<th class="border-top-0">No.of users</th>
											<th class="border-top-0">No.of uploads</th>
											<th class="border-top-0">Action</th>
										</tr>
									</thead>
									<tbody>
										<td>
											<h1>Coming soon...</h1>
										</td>
										 <?php if(count($project_surveys) > 0){ 
											foreach ($project_surveys as $key => $project) { ?>
												<tr>
													<td>
														<a href="javascript:void(0);" class="projectdetails" data-project_id="<?php echo $project['project_id']; ?>" data-projectname="<?php echo $project['project_name']; ?>" style="font-size: 16px;" >
															<i class="ft-plus-square"></i> <span><?php echo $project['project_name']; ?></span>
														</a>
													</td>
													<td>
														<?php switch ($project['project_id']) {
															case 10:
																echo 8;
															break;

															case 11:
																echo 7;
															break;
															case 20:
																echo 1;
															break;
															case 21:
																echo 2;
															break;

															case 27:
																echo 1;
															break;

															case 29:
																echo 4;
															break;

															case 30:
																echo 4;
															break;
															
															case 31:
																echo 2;
															break;

															case 32:
																echo 2;
															break;

															case 35:
																echo 1;
															break;

															case 36:
																echo 1;
															break;

															case 37:
																echo 1;
															break;

															case 38:
																echo 3;
															break;

															default:
																echo count($project['project_surveys']);
															break;
														} ?>
													</td>
													<td>
														<?php switch ($project['project_id']) {
															case 3:
																echo 2;
															break;
															case 10:
																echo 35;
															break;

															case 11:
																echo 22;
															break;
															case 13:
																echo 11;
															break;
															case 14:
																echo 1;
															break;
															case 15:
																echo 32;
															break;
															case 20:
																echo 5;
															break;
															case 21:
																echo 8;
															break;
															case 22:
																echo 40;
															break;
															case 24:
																echo 16;
															break;
															case 26:
																echo 2;
															break;

															case 27:
																echo 16;
															break;

															case 29:
																echo 5;
															break;

															case 30:
																echo 10;
															break;
															
															case 31:
																echo 9;
															break;

															case 32:
																echo 16;
															break;

															case 35:
																echo 1;
															break;

															case 36:
																echo 5;
															break;

															case 37:
																echo 18;
															break;

															case 38:
																echo 38;
															break;

															default:
																echo $project['project_users'];
															break;
														} ?>
													</td>
													<td>
														<?php switch ($project['project_id']) {
															case 10:
																echo 864;
															break;

															case 11:
																echo 760;
															break;
															case 20:
																echo 193;
															break;
															case 21:
																echo 65;
															break;

															case 22:
																echo 2949;
															break;

															case 24:
																echo 954;
															break;

															case 27:
																echo 425;
															break;

															case 29:
																echo 163189;
															break;

															case 30:
																echo 12;
															break;

															case 31:
																echo 197;
															break;
															
															case 32:
																echo 806;
															break;

															case 35:
																echo 55302;
															break;

															case 36:
																echo 0;
															break;

															case 37:
																echo 647;
															break;

															case 38:
																echo 2622;
															break;

															default:
																echo $project['project_upload'];
															break;
														} ?>
													</td>
													<td>
														<?php switch ($project['project_id']) {
															case 1: ?>
																<a href="<?php echo base_url(); ?>taat_dashboard/main_dashboard/<?php echo $project['project_id']; ?>/2020" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 3: ?>
																<a href="<?php echo base_url(); ?>icrisat_operations/overview_dashboard/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 7: ?>
																<a href="<?php echo base_url(); ?>site_integration/dashboard/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 9: ?>
																<a href="<?php echo base_url(); ?>pi_2020" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 10: ?>
																<a href="https://mpro.icrisat.org/pi_2019/pi2019_dashboards.html" target="_blank" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 11: ?>
																<a href="https://mpro.icrisat.org/pi_2018/performanceindicator2018_dashboard.html" target="_blank" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 13: ?>
																<a href="<?php echo base_url(); ?>ananthasamrudhi/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 16: ?>
																<a href="<?php echo base_url(); ?>eia_fertilizer_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 18: ?>
																<a href="<?php echo base_url(); ?>guava_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 19: ?>
																<a href="<?php echo base_url(); ?>gender_gap_study/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 20: ?>
																<a href="<?php echo base_url(); ?>cgiar_site_integration_details_one/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 21: ?>
																<a href="<?php echo base_url(); ?>cgiar_site_integration_details_two/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															
															case 22: ?>
																<a href="<?php echo base_url(); ?>rainfall_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 24: ?>
																<a href="<?php echo base_url(); ?>ground_truthing_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 26: ?>
																<a href="<?php echo base_url(); ?>odisha_bhoochetana_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 27: ?>
																<a href="<?php echo base_url(); ?>brewery_industries_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 28: ?>
																<a href="<?php echo base_url(); ?>enquete_sur_les_tests_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 29: ?>
																<a href="<?php echo base_url(); ?>misst_seventeen_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 30: ?>
																<a href="<?php echo base_url(); ?>misst_eighteen_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 31: ?>
																<a href="<?php echo base_url(); ?>study_on_understanding_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 32: ?>
																<a href="<?php echo base_url(); ?>tliii_data_collection_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 32: ?>
																<a href="<?php echo base_url(); ?>tliii_data_collection_dashboard/index/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

															case 35: ?>
																<a href="<?php echo base_url(); ?>groundnut/upscaling/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 36: ?>
																<a href="<?php echo base_url(); ?>groundnut/upscaling_wca/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 37: ?>
																<a href="<?php echo base_url(); ?>cropslivestock/impact_assessment/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															case 38: ?>
																<a href="<?php echo base_url(); ?>gldc/sc_performance/<?php echo $project['project_id']; ?>" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;
															default: ?>
																<a href="javascript:void(0);" class="btn btn-sm btn-success round">Project dashboard</a>
																<?php break;

														} ?>
													</td>
												</tr>

												<?php foreach ($project['project_surveys'] as $key => $survey) { ?>
													<tr class="projectsurveys_<?php echo $project['project_id']; ?> hidden">
														<td colspan="2"><?php echo $survey['formname']; ?></td>
														<td><?php //echo $survey['survey_users_count']; 
															switch($survey['formid'])
															{
																case 389:
																	echo 2;
																break;
																case 390:
																	echo 2;
																break;
																case 391:
																	echo 2;
																break;
																case 392:
																	echo 2;
																break;
																case 393:
																	echo 2;
																break;
																case 394:
																	echo 2;
																break;
																case 395:
																	echo 2;
																break;
																case 396:
																	echo 2;
																break;
																case 397:
																	echo 2;
																break;
																case 491:
																	echo 1;
																break;
																case 479:
																	echo 11;
																break;

																case 480:
																	echo 5;
																break;

																case 481:
																	echo 11;
																break;
																case 482:
																	echo 12;
																break;

																case 483:
																	echo 11;
																break;

																case 484:
																	echo 12;
																break;

																case 485:
																	echo 11;
																break;

																case 486:
																	echo 11;
																break;

																case 487:
																	echo 11;
																break;
																case 488:
																	echo 11;
																break;

																case 489:
																	echo 8;
																break;

																case 490:
																	echo 8;
																break;
																case ($survey['formid']>491 && $survey['formid']<499):
																	echo 32;
																break;
																case 502:
																	echo 8;
																break;
																case 503:
																	echo 32;
																break;
																case 504:
																	echo 9;
																break;
																case 506:
																	echo 16;
																break;
																case 508:
																	echo 2;
																break;
																case 512:
																	echo 1;
																break;
																case 514:
																	echo 18;
																break;
																
																case 505:
																	echo 9;
																break;
																case 515:
																	echo 26;
																break;
																case 516:
																	echo 6;
																break;
																case 517:
																	echo 6;
																break;
																default:
																	echo $survey['survey_users_count'];
																break;
															}
														?></td>
														<td><?php 
														
															switch($survey['formid'])
															{
																case 502:
																	echo 19;
																break;
																case 503:
																	echo 2930;
																break;
																case 504:
																	echo 35;
																break;

																case 505:
																	echo 16;
																break;
																case 506:
																	echo 954;
																break;
																case 512:
																	echo 55302;
																break;
																case 514:
																	echo 647;
																break;
																case 515:
																	echo 2620;
																break;
																case 516:
																	echo 1;
																break;
																case 517:
																	echo 1;
																break;

																default:
																	echo $survey['survey_upload_count'];
																break;
															}
															 ?>
															
														</td>
														<td>
															<?php switch ($project['project_id']) {
																case 1: ?>
																	<a href="<?php echo base_url(); ?>taat_dashboard/taat_survey/<?php echo $project['project_id']; ?>/<?php echo $survey['formid']; ?>/2020" class="btn btn-sm btn-success round">View data</a>
																	<?php break;
																
																
																default: 
																	// switch ($survey['type']) {
																	// 	case 'Survey':
																	// 		$url = base_url('dashboard/view_surveydata/'.$project['project_id'].'/'.$survey['formid']);
																	// 		break;

																	// 	case 'Activity':
																	// 		$url = base_url('dashboard/view_activitydata/'.$project['project_id'].'/'.$survey['formid']);
																	// 		break;

																	// 	case 'Registration':
																	// 		$url = base_url('dashboard/view_registrationdata/'.$project['project_id'].'/'.$survey['formid']);
																	// 		break;

																	// 	case 'Visit':
																	// 		$url = base_url('dashboard/view_visitdata/'.$project['project_id'].'/'.$survey['formid']);
																	// 		break;
																		
																	// 	default:
																	// 		# code...
																	// 		break;
																	// } ?>
																	<a href="<?php echo base_url(); ?>dashboard/view_activitydata/<?php echo $project['project_id'].'/'.$survey['formid']; ?>" class="btn btn-sm btn-success round">View data</a>
																	<?php break;
															} ?>
														</td>
													</tr>
												<?php }
											}
										} ?> 
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>
<!-- End Page -->

<script type="text/javascript">
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
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>

<!-- New Graphs -->
<script>
	Highcharts.chart('landholding', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category',
		title: {
            text: '<span><strong>Small Farm: 0 to 0.5 ha, Medium Farm: 0.5 to 1.5 ha, Large Farm: 1.5 ha and Above</strong></span>'
        },
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
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'Landholding',
			color:'#4bacc6',
            data: [
                {
                    name: 'Small',
                    y: 45,
                },
                {
                    name: 'Medium',
                    y: 28,
                },
                {
                    name: 'Large',
                    y: 18,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('range_of_land_size', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
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
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.y}',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        
        colorByPoint: true,
        data: [{
            name: 'Landless',
			color:'#ed7d31',
            y: 0
        }, {
            name: 'Small Farmer (Upto 5)',
			color:'#ffc000',
            y: 5
        }, {
            name: 'Medium Farmer (5 to 10)',
			color:'#70ad47',
            y: 10
        }, {
            name: 'Medium Farmer (Morethan 10)',
			color:'#9e480e',
            y: 15
        }]
    }]
});
</script>

<script>
	Highcharts.chart('livestock_holding', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: ''
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Landholding',
			color:'#ffd966',
            data: [
                {
                    name: 'Improve Daily Cattele',
                    y: 0.473333333,
                },
                {
                    name: 'Pig',
                    y: 0.417,
                },
                {
                    name: 'Local Daily cattle',
                    y: 0.166666667,
                },
				{
                    name: 'Fattening and Draught Cattle',
                    y: 0.133333333,
                },
                {
                    name: 'Goat',
                    y: 0.093333333,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('average_livestock_holding', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'TLUS'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Average household livestock holdings by category in TLUs',
			color:'#c9f',
            data: [
                {
                    name: 'Improved Dairy Cattle',
                    y: 0.473333333,
                },
                {
                    name: 'Pig',
                    y: 0.417,
                },
                {
                    name: 'Local Dairy Cattle',
                    y: 0.166666667,
                },
				{
                    name: 'Fattening and Draught Cattle',
                    y: 0.133333333,
                },
                {
                    name: 'Goat',
                    y: 0.093333333,
                },
				{
                    name: 'Sheep',
                    y: 0.038333333,
                },
				{
                    name: 'Rabbit-village conditions',
                    y: 0.015666667,
                },
                {
                    name: 'Poultry - Village Conditions',
                    y: 0.005166667,
                },{
                    name: '()',
                    y: 0.003333333,
                },
				{
                    name: 'Poultry - Commercial',
                    y: 0.001333333,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('average_household_livestock', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'TLUS'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Average household livestock holdings by type of TLUs',
			color:'#5b9bd5',
            data: [
                {
                    name: 'Improved dairy cows - lactating',
                    y: 0.433333333,
                },
                {
                    name: 'Sows',
                    y: 0.173333333,
                },
                {
                    name: 'Local Dairy cows - lactating',
                    y: 0.166666667,
                },
				{
                    name: 'Growers',
                    y: 0.151666667,
                },
                {
                    name: 'Goats',
                    y: 0.093333333,
                },
				{
                    name: 'Local Bulls or castrated male cattle (>6mths old - < 2 years)',
                    y: 0.066666667,
                },
				{
                    name: 'Improved Bulls or castrated male cattle (>6mths old - < 2 years)',
                    y: 0.066666667,
                },
                {
                    name: 'Weaners/Piglets',
                    y: 0.052,
                },{
                    name: 'Improved Dairy heifers (>6mths old - < 1st calving)',
                    y: 0.04,
                },
				{
                    name: 'Finishers (porkers/baconers)',
                    y: 	0.04,
                },{
                    name: 'Sheep',
                    y: 0.038333333,
                },{
                    name: 'Rabbit-Backyard conditions',
                    y: 0.015666667,
                },{
                    name: 'Poultry - village conditions',
                    y: 0.005166667,
                },{
                    name: 'Blank',
                    y: 0.003333333,
                },{
                    name: 'Poultry-commerical production',
                    y: 0.001333333,
                }
            ]
        }
    ],
});
</script>

<script>
    Highcharts.chart('average_livestock_holding_wealth', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Improved Dairy calves (<6mths old) – female', 'Improved dairy cows – non-lactating (dry)', 'Improved Dairy heifers (>6mths old - <1st calving)', 'Local Dairy calves (<6mths old) – female', 'Local Dairy calves (<6mths old) – male', 'Local Dairy cows – lactating', 'Local Dairy cows – non-lactating (dry)', 'Local Dairy heifers (>6mths old - <1st calving)', 'Sheep', 'Improved Dairy calves (<6mths old) – male']
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        column: {
            borderRadius: '25%'
        }
    },
    series: [{
        name: 'Below Average',
        color: '#5b9bd5',
        data: [0, 0, 2, 2, 1, 2, 1, 1, 5, 1]
    }, {
        name: 'Average',
        color: '#c9f',
        data: [2, 1, 5, 1, 1, 4, 0, 0, 7, 1]
    }, {
        name: 'Above Average',
        color: '#ffd966',
        data: [8, 10, 6, 2, 5, 7, 3, 2, 10, 4]
    }]
});
</script>

<script>
	Highcharts.chart('average_livestock_species_holding', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'Livestock Species'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Average Tropical Livestock Units (TLU) per Household',
			color:'#5b9bd5',
            data: [
                {
                    name: 'Improved Dairy Cattle',
                    y: 4.50,
                },
                {
                    name: 'Local Dairy Cattle',
                    y: 3.00,
                },
                {
                    name: 'Sheep',
                    y: 0.50,
                },
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('average_livestock_holding_dominant_species', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'Dominant species (TLU)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Average livestock holdings per household – dominant species (TLU)',
			color:'#c9f',
            data: [
                {
                    name: 'Improved Dairy Cattle',
                    y: 4.50,
                },
                {
                    name: 'Local Dairy Cattle',
                    y: 3.00,
                },
                {
                    name: 'Sheep',
                    y: 0.50,
                },
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('crop_type_by_average_hectares', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Potato (Solanum tuberosum)', 'Maize (Zea mays)', 'Common Beans (Phaseolus vulgaris)', 'Coffee (Coffea arabica)', 'Banana (Musa acuminata)']
    },
	yAxis: {
        min: 0,
        title: {
            text: 'Hectares Under Cultivation'
        }
    },
	legend: {
		enabled: false
	},
    credits: {
        enabled: false
    },
    plotOptions: {
        column: {
            borderRadius: ''
        }
    },
    series: [{
        name: 'Sum of average ha per hh',
      color:"#5b9bd5",
        data: [0.540833333, 0.3075, 0.27, 0.224166667, 0.078333333]
    }, {
        name: 'Sum of residue percent sold',
       color:"#ed7d31",
        data: [0, 0.6, 0, 4, 0.5]
    }, {
        name: 'Sum of residue percent fed',
       color:"#a5a5a5",
        data: [3.7, 8.4, 7, 0, 2.5]
    }]
});
</script>

<script>
	Highcharts.chart('crop_type_by_average_hectares_cultivated', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Crop types by average hectares cultivated',
			color:'#ed7d31',
            data: [
                {
                    name: 'Potato (Solanum tuberosum)',
                    y: 0.540833333,
                },
                {
                    name: 'Maize (Zea mays)',
                    y: 0.3075,
                },
                {
                    name: 'Common Beans (Phaseolus vulgaris)',
                    y: 0.27,
                },
				{
                    name: 'Coffee (Coffea arabica)',
                    y: 0.224166667,
                },
                {
                    name: 'Banana (Musa acuminata)',
                    y: 0.0783333333,
                },
				{
                    name: 'Sweet potato (Iopmoea batatas)',
                    y: 0.07,
                },
				{
                    name: 'Cabbage (Brassica oleracea)',
                    y: 0.033333333,
                },
                {
                    name: 'Eucalypt trees (Eucalyptus spp.)',
                    y: 0.0075,
                },{
                    name: 'Carrot (Daucus carota)',
                    y: 0.004166667,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('crops_grown_in_the_area', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'Average Area per Household (hectares)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Crops grown in the area',
			color:'#5b9bd5',
            data: [
                {
                    name: 'Wheat (Triticum aestivum)',
                    y: 1.40,
                },
                {
                    name: 'Rice (Oryza sativa)',
                    y: 0.40,
                },
                {
                    name: 'Common Beans (Phaseolus vulgaris)',
                    y: 0.60,
                },
				{
                    name: 'Maize (Zea mays)',
                    y: 0.40,
                },
                {
                    name: 'Barley (Hordeum vulgare)',
                    y: 0.60,
                },
				{
                    name: 'Carrot (Daucus carota)',
                    y: 0.20,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('average_area_per_hh', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'Average Area per Household (hectares)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Average area (ha) per hh of dominanat arable crops',
			color:'#a5a5a5',
            data: [
                {
                    name: 'Wheat (Triticum aestivum)',
                    y: 1.40,
                },
                {
                    name: 'Rice (Oryza sativa)',
                    y: 0.80,
                },
                {
                    name: 'Common Beans (Phaseolus vulgaris)',
                    y: 0.60,
                },
				{
                    name: 'Maize (Zea mays)',
                    y: 0.60,
                },
                {
                    name: 'Barley (Hordeum vulgare)',
                    y: 0.20,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('fodder_crop_by_average_hectares', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'fodder crops by average hectares cultivated',
			color:'#f4b183',
            data: [
                {
                    name: 'Napier grass (Pennisetum purpureum)',
                    y: 0.129166667,
                },
                {
                    name: 'Leucaena (Leucaena leucocephala)',
                    y: 0.008333333,
                },
                {
                    name: 'Mucuna (Mucuna pruriens)',
                    y: 0.004166667,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('average_hectares_cultivated_fodder_crop_type', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'Average hectares cultivated per household by fodder crop type',
			color:'#ed7d31',
            data: [
                {
                    name: 'Napier grass (Pennisetum purpureum)',
                    y: 0.129166667,
                },
                {
                    name: 'Leucaena (Leucaena leucocephala)',
                    y: 0.008333333,
                },
                {
                    name: 'Mucuna (Mucuna pruriens)',
                    y: 0.004166667,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('fodder_crops_grown_in_the_area', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'Average Area of Crop Grown per Household (hectares)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'Average Area of Crop Grown per Household (hectares)',
			color:'#5b9bd5',
            data: [
                {
                    name: 'Cocksfoot (Dactylis glomerata)',
                    y: 0.50,
                },
                {
                    name: 'Oat (Avena sativa)',
                    y: 0.50,
                },
                {
                    name: 'Berseem (Trifolium alexandrinum)',
                    y: 0.35,
                },
                {
                    name: 'Bermuda grass (Cynodon dactylon)',
                    y: 0.30,
                },
                {
                    name: 'Lablab (Lablab purpureus)',
                    y: 0.15,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('dominant_fodder_crops_grown_in_the_area', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
    },
    yAxis: {
        title: {
            text: 'Average Area of Crop Grown per Household (hectares)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'Average Area of Crop Grown per Household (hectares)',
			color:'#c9f',
            data: [
                {
                    name: 'Cocksfoot (Dactylis glomerata)',
                    y: 0.50,
                },
                {
                    name: 'Oat (Avena sativa)',
                    y: 0.50,
                },
                {
                    name: 'Berseem (Trifolium alexandrinum)',
                    y: 0.35,
                },
                {
                    name: 'Bermuda grass (Cynodon dactylon)',
                    y: 0.30,
                },
                {
                    name: 'Lablab (Lablab purpureus)',
                    y: 0.15,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('purchased_feed_types', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'Dominant purchased feed types by kg purchased',
			color:'#5b9bd5',
            data: [
                {
                    name: " 'Brewer's grain - wet'",
                    y: 1400,
                },
                {
                    name: 'Cabbage (Brassica oleracea)',
                    y: 600,
                },
                {
                    name: 'Maize (Zea mays) - crop resid',
                    y: 300,
                },
				{
                    name: "Commercially Mixed Ration",
                    y: 80,
                },
                {
                    name: 'Maize (Zea mays) - cobs grou',
                    y: 75,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('average_purchased_feed_types', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
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
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b><br/>'
    },
    series: [
        {
            name: 'Average kg of feed purchased per household by feed type',
			color:'#f4b183',
            data: [
                {
                    name: " 'Brewer's grain - wet'",
                    y: 1400,
                },
                {
                    name: 'Cabbage (Brassica oleracea)',
                    y: 600,
                },
                {
                    name: 'Maize (Zea mays) - crop resid',
                    y: 300,
                },
				{
                    name: "Commercially Mixed Ration",
                    y: 80,
                },
                {
                    name: 'Maize (Zea mays) - cobs ground',
                    y: 75,
                },
				{
                    name: 'Rice (Oryza sativa) - bran (with germs)',
                    y: 60,
                },
				{
                    name: 'Maize (Zea mays) - gluten with bran',
                    y: 32.5,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('available_feed_resources', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
         categories: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Availability'
        },
        stackLabels: {
            enabled: false
        }
    },
    credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: false
            }
        }
    },
     series: [
      {
        name: 'Cereal Crop',
		color:'#ffc000',
        type: 'column',
        data: [1.566666667, 0.775, 1.291666667, 1.15, 0.833333333, 0.85, 0.5, 0.65, 0.483333333, 1],
        tooltip: {
            valueSuffix: ' mm'
        }

    },{
        name: 'Concentrates',
        type: 'column',
		color:'#70ad47',
        data: [0.5, 0, 0.483333333, 0.491666667, 0.808333333, 0.333333333, 0.266666667, 0.266666667, 0, 0.683333333],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Grazing',
        type: 'column',
		color:'#9e480e',
        data: [0.2333333, 0.2, 0.3666667, 0.625, 0.6666667, 0.5, 0, 0.6, 0.3833333, 1.1333333 ],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Leguminous',
        type: 'column',
		color:'#9a7504',
        data: [0.1083333, 0.2083333, 0.1666667, 0.1083333, 0, 0.1833333, 0.3333333, 0.25, 0.25, 0.3833333],
        tooltip: {
            valueSuffix: ' mm'
        }

    },  {
        name: 'Green Forage',
        type: 'column',
		color:'#f1975a',
        data: [3, 0.3166667, 1.4, 2.9666667, 3.7083333, 1.7666667, 1.55, 0.5, 1.525, 3.15],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Other',
        type: 'column',
		color:'#43682b',
        data: [0.0916667, 0.1583333, 0.1166667, 0.2666667, 0.2666667, 0.3, 0.15, 0.3, 0.3833333, 0.25],
        tooltip: {
            valueSuffix: ' mm'
        }

    },{
        name: 'Rainfall pattern',
        type: 'column',
		color:'#dc853d',
        data: [
            11.4, 9.5, 14.2, 0.2, 7.0, 12.1, 13.5, 13.6, 8.2,
            2.8, 12.0, 15.5
        ],
    }]
});
</script>

<script>
	Highcharts.chart('dry_matter_intake_by_source', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
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
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.y}',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        
        colorByPoint: true,
        data: [{
            name: 'Collected Fodder',
			color:'#ed7d31',
            y: 3117.42
        }, {
            name: 'Crop Residue',
			color:'#ffc000',
            y: 993.44
        }, {
            name: 'Cultivated Fodder',
			color:'#70ad47',
            y: 2115.81
        }, {
            name: 'Grazing',
			color:'#9e480e',
            y: 111.11
        }, {
            name: 'Purchased Feed',
			color:'#997300',
            y: 1139.67
        }]
    }]
});
</script>

<script>
	Highcharts.chart('metabolizable_energy_intake_by_source', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
        align: 'left'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
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
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.y}',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        
        colorByPoint: true,
        data: [{
            name: 'Collected Fodder',
			color:'#ed7d31',
            y: 31174.20
        }, {
            name: 'Crop Residue',
			color:'#ffc000',
            y: 9058.57
        }, {
            name: 'Cultivated Fodder',
			color:'#70ad47',
            y: 20833.94
        }, {
            name: 'Grazing',
			color:'#9e480e',
            y: 999.98
        }, {
            name: 'Purchased Feed',
			color:'#997300',
            y: 10646.52
        }]
    }]
});
</script>

<script>
	Highcharts.chart('crude_protein_intake_by_source', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
        align: 'left'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
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
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.y}',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        
        colorByPoint: true,
        data: [{
            name: 'Collected Fodder',
			color:'#ed7d31',
            y: 31174.20
        }, {
            name: 'Crop Residue',
			color:'#ffc000',
            y: 9058.57
        }, {
            name: 'Cultivated Fodder',
			color:'#70ad47',
            y: 20833.94
        }, {
            name: 'Grazing',
			color:'#9e480e',
            y: 999.98
        }, {
            name: 'Purchased Feed',
			color:'#997300',
            y: 10646.52
        }]
    }]
});
</script>

<script>
	Highcharts.chart('month_wise_rainfall_feed_availability', {
    chart: {
        zooming: {
            type: 'xy'
        }
    },
    title: {
        text: '',
        align: 'left'
    },
    credits: {
        text: ''
    },
    xAxis: [{
        categories: [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ],
        crosshair: true
    }],
    yAxis: [{ // Primary yAxis
        // labels: {
        //     format: '{value}°C',
        //     style: {
        //         color: Highcharts.getOptions().colors[1]
        //     }
        // },
        title: {
            text: 'Availability in feed (0 - 10) ',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        }
    }, { // Secondary yAxis
        title: {
            text: 'Rainfall (0 - 5)',
            style: {
                color: Highcharts.getOptions().colors[0]
            }
        },
        // labels: {
        //     format: '{value} mm',
        //     style: {
        //         color: Highcharts.getOptions().colors[0]
        //     }
        // },
        opposite: true
    }],
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: false
            }
        }
    },
    series: [
      {
        name: 'Cereal Crop',
		color:'#ffc000',
        type: 'column',
        yAxis: 1,
        data: [1.566666667, 0.775, 1.291666667, 1.15, 0.833333333, 0.85, 0.5, 0.65, 0.483333333, 1],
        tooltip: {
            valueSuffix: ' mm'
        }

    },{
        name: 'Concentrates',
        type: 'column',
		color:'#70ad47',
        yAxis: 1,
        data: [0.5, 0, 0.483333333, 0.491666667, 0.808333333, 0.333333333, 0.266666667, 0.266666667, 0, 0.683333333],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Grazing',
        type: 'column',
		color:'#9e480e',
        yAxis: 1,
        data: [0.2333333, 0.2, 0.3666667, 0.625, 0.6666667, 0.5, 0, 0.6, 0.3833333, 1.1333333 ],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Leguminous',
        type: 'column',
		color:'#9a7504',
        yAxis: 1,
        data: [0.1083333, 0.2083333, 0.1666667, 0.1083333, 0, 0.1833333, 0.3333333, 0.25, 0.25, 0.3833333],
        tooltip: {
            valueSuffix: ' mm'
        }

    },  {
        name: 'Green Forage',
        type: 'column',
		color:'#f1975a',
        yAxis: 1,
        data: [3, 0.3166667, 1.4, 2.9666667, 3.7083333, 1.7666667, 1.55, 0.5, 1.525, 3.15],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Other',
        type: 'column',
		color:'#43682b',
        yAxis: 1,
        data: [0.0916667, 0.1583333, 0.1166667, 0.2666667, 0.2666667, 0.3, 0.15, 0.3, 0.3833333, 0.25],
        tooltip: {
            valueSuffix: ' mm'
        }

    }, {
        name: 'Sum of Rainfall',
        type: 'spline',
		color:'#bed7ee',
        data: [2, 1.375, 4, 3, 0, 1, 0, 1.516666667, 0.625, 3],
        // tooltip: {
        //     valueSuffix: '°C'
        // }
    }
    ]
});
</script>

<script>
	Highcharts.chart('milk_and_livestock_price', {

	title: {
		text: '',
		align: 'left'
	},

	subtitle: {
		text: '',
		align: 'left'
	},

	yAxis: {
		title: {
			text: 'Price Cattle (USD)'
		}
	},

	xAxis: [{
		categories: [
			'January', 'February', 'March', 'April', 'May', 'June',
			'July', 'August', 'September', 'October', 'November', 'December'
		],
		crosshair: true
	}],
	yAxis: [{ 
		title: {
			text: 'Price Cattle (USD)',
			style: {
				color: Highcharts.getOptions().colors[1]
			}
		}
	}, { // Secondary yAxis
		title: {
			text: 'Price Other (USD)',
			style: {
				color: Highcharts.getOptions().colors[0]
			}
		},
		opposite: true
	}],
	tooltip: {
		shared: true
	},

	credits: {
        text: ''
    },
	plotOptions: {
		series: {
			label: {
				connectorAllowed: true
			},
		}
	},

	series: [{
		name: 'Average Price Cattle',
		color:"#87ba64",
		data: [310, 270, 290, 266, 294, 245, 296, 350, 340, 310, 320, 390]
	}, {
		name: 'Average Price Sheep',
		color:"#6289ce",
		data: [28, 32, 32, 32, 32, 28, 26, 36, 32, 28, 28, 40]
	}, {
		name: 'Average Price Goat',
		color:"#ffca29",
		data: [64, 52, 50, 52, 54, 66, 64, 68, 66, 62, 58, 80]
	}],

	});
</script>

<script>
	Highcharts.chart('average_daily_milk_and_livestock_price', {

	title: {
		text: '',
		align: 'left'
	},

	subtitle: {
		text: '',
		align: 'left'
	},

	yAxis: {
		title: {
			text: 'Price Cattle (USD)'
		}
	},

	xAxis: [{
		categories: [
			'January', 'February', 'March', 'April', 'May', 'June',
			'July', 'August', 'September', 'October', 'November', 'December'
		],
		crosshair: true
	}],
	yAxis: [{ 
		title: {
			text: 'Price Cattle (USD)',
			style: {
				color: Highcharts.getOptions().colors[1]
			}
		}
	}, { // Secondary yAxis
		title: {
			text: 'Price Other (USD)',
			style: {
				color: Highcharts.getOptions().colors[0]
			}
		},
		opposite: true
	}],
	tooltip: {
		shared: true
	},
	credits: {
        text: ''
    },
	plotOptions: {
		series: {
			label: {
				connectorAllowed: true
			},
		}
	},

	series: [{
		name: 'Average Yield (L)',
		color:"#77b150",
		data: [7.75, 7.75, 8.25, 8.5, 8.25, 7.25, 5.5, 6.5, 6.5, 6.25, 6.5, 7.25]
	}, {
		name: 'Average Price per Litre (USD)',
		color:"#557fc9",
		data: [0.448, 0.448, 0.448, 0.438, 0.438, 0.45, 0.45, 0.45, 0.448, 0.436, 0.436, 0.436]
	}],

	});
</script>

<script>
	Highcharts.chart('income_by_activity', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
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
			dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        colorByPoint: true,
        data: [{
            name: 'Business',
			color:'#ed7d31',
            y: 0.033333333
        }, {
            name: 'Cropping',
			color:'#ffc000',
            y: 0.474166667
        }, {
            name: 'Labour',
			color:'#70ad47',
            y: 0.0083333333
        }, {
            name: 'Livestock',
			color:'#9e480e',
            y: 0.434166667
        }, {
            name: 'Others',
			color:'#997300',
            y: 0.05
        }]
    }]
});
</script>

<script>
	Highcharts.chart('gender_pay_equality', {
    chart: {
        type: 'column'
    },
    title: {
        align: 'left',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Daily Labour Rate (USD)'
        }

    },
    legend: {
        enabled: false
    },
	credits: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: false,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y:.2f}%</b><br/>'
    },

    series: [
        {
            name: 'Average daily labor rates by gender (in USD)',
            colorByPoint: true,
            data: [
                {
                    name: 'Average Pay Female',
                  color:"#ed7d31",
                    y:0.88,
                },
                {
                    name: 'Average Pay Male',
                  color:"#5b9bd5",
                    y: 1.94,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('contribution_of_livelihood', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
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
			dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        colorByPoint: true,
        data: [{
            name: 'Agriculture',
			color:'#ed7d31',
            y: 40
        }, {
            name: 'Livestock',
			color:'#ffc000',
            y: 27
        }, {
            name: 'Remittance',
			color:'#70ad47',
            y: 16
        }, {
            name: 'Labour',
			color:'#9e480e',
            y: 12
        }, {
            name: 'Others',
			color:'#997300',
            y: 3
        },{
            name: 'Business',
			color:'#5b9bd5',
            y: 2
        }]
    }]
});
</script>


<script>
	Highcharts.chart('land_ownership_by_gender', {
    chart: {
        type: 'column'
    },
    title: {
        align: 'left',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Area (Hectares)'
        }

    },
    legend: {
        enabled: false
    },
	credits: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: false,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y:.2f}%</b><br/>'
    },

    series: [
        {
            name: 'Land ownership by gender',
            colorByPoint: true,
            data: [
                {
                    name: 'Male',
                 	 color:"#ed7d31",
                    y:0,
                },
                {
                    name: 'Female',
                  	color:"#ed7d31",
                    y: 0.066666667,
                },
				{
                    name: 'Joint',
                  	color:"#ed7d31",
                    y: 1.46,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('farm_size_by_gender_of_householdhead', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Small Farm', 'Medium Farm', 'Large Farm']
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        column: {
            borderRadius: '0'
        }
    },
    series: [{
        name: 'Female',
       color:"#ffd966",
        data: [0.26, 0.90, 10.0]
    }, {
        name: 'Male',
       color:"#ed7d31",
        data: [0, 1.17, 0]
    }]
});
</script>

<script>
	Highcharts.chart('livestock_categories_by_gender', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Improved Dairy Cattle', 'Pig', 'Local Dairy Cattle', 'Goat', 'Rabbit-village conditions']
    },
	yAxis: {
        title: {
            text: 'Size of Holding (TLUs)'
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        column: {
            borderRadius: '0'
        }
    },
    series: [{
        name: 'Female',
       color:"#5b9bd5",
        data: [0.9333333, 0.1933333, 0.0788889, 0.1066667, 0 ]
    }, {
        name: 'Male',
       color:"#ed7d31",
        data: [1.3555556, 0.6066667, 0.1788889, 0.0577778, 0.0888889]
    }]
});
</script>

<script>
	Highcharts.chart('average_hectares_cultivated_by_gender_of_hh', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Common Beans (Phaseolus vulgaris)', 'Maize (Zea mays)', 'Sorghum (Sorghum bicolor)', 'Soybean (Glycine max)', 'Banana (Musa acuminata)']
    },
	yAxis: {
        title: {
            text: 'Area (Hectares)'
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        column: {
            borderRadius: '0'
        }
    },
    series: [{
        name: 'Female',
        color:"#5b9bd5",
        data: [1.2666667, 1, 0.0555556, 0.1666667, 0]
    }, {
        name: 'Male',
        color:"#ed7d31",
        data: [0.1633333, 0.2011111, 0.3, 0.0222222, 0.0888889]
    }]
});
</script>

<script>
	Highcharts.chart('coop_farmer_organization_gender', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: ['Male Household Members', 'Female Household Members']
    },
	yAxis: {
        title: {
            text: 'Average number of memberships per HH'
        }
    },
    credits: {
        enabled: false
    },
	legends: {
		enabled: false
	},
    plotOptions: {
        column: {
            borderRadius: '0'
        }
    },
    series: [ {
        color:"#ed7d31",
        data: [0.3333333, 0.5833333]
    }]
});
</script>

<script>
	Highcharts.chart('gender_division_of_labor_feed_production', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
        categories: ['Preparing land for planting forages', 'Planting forages', 'Weeding of forage crops',
		'Harvesting forages/crop residues', 'Processing (milling/chopping) feeds and forages', 'Purchasing of feeds and forages', 'Transportation of feeds and forages',
		'Storage of feeds and forages', 'Mixing feed ingredients', 'Feeding', 'Watering', 'Collection of off-farm forages', 'Cleaning of feeding and watering facilities']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count trophies'
        },
        stackLabels: {
            enabled: false
        }
    },
    // legend: {
    //     align: 'left',
    //     x: 70,
    //     verticalAlign: 'top',
    //     y: 70,
    //     floating: true,
    //     backgroundColor:
    //         Highcharts.defaultOptions.legend.backgroundColor || 'white',
    //     borderColor: '#CCC',
    //     borderWidth: 1,
    //     shadow: false
    // },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
	credits: {
		enabled: false
	},
    plotOptions: {
        column: {
            stacking: 'normal',
            // dataLabels: {
            //     enabled: true
            // }
        }
    },
    series: [{
        name: 'Children and youth',
		color:"#5b9bd5",
        data: [0, 1, 0, 1, 2, 1, 2, 1, 1, 5, 7, 7, 6]
    }, {
        name: 'Men',
		color:"#ef8b48",
        data: [2, 2, 2, 5, 2, 8, 7, 4, 0, 1, 0, 1, 1]
    }, {
        name: 'Women',
		color:"#a5a5a5",
        data: [9, 8, 9, 6, 2, 3, 2, 6, 7, 6, 5, 4, 5]
    },{
        name: 'N/A',
		color:"#ffc000",
        data: [1, 1, 1, 0, 6, 0, 1, 1, 4, 0, 0, 0, 0]
    }]
});
</script>

<script>
	Highcharts.chart('gendered_decision_making_on_livestock', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
        categories: ['Large ruminant livestock e.g. cattle/buffalo', 'Small ruminant livestock e.g. sheep and goats', '	Pigs', 'Poultry']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        },
        stackLabels: {
            enabled: false
        }
    },
   
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
	credits: {
		enabled: false
	},
    plotOptions: {
        column: {
            stacking: 'normal',
            // dataLabels: {
            //     enabled: true
            // }
        }
    },
    series: [{
        name: 'Joint',
		color:"#5b9bd5",
        data: [7, 4, 11, 4]
    }, {
        name: 'Men',
		color:"#ef8b48",
        data: [2, 1, 1, 1]
    }, {
        name: 'Women',
		color:"#a5a5a5",
        data: [0, 0, 0, 3]
    },{
        name: 'N/A',
		color:"#ffc000",
        data: [3, 7, 0, 4]
    }]
});
</script>

<script>
	Highcharts.chart('gendered_decision_making_on_sales_of_livestock', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
        categories: ['Large ruminant livestock e.g. cattle/buffalo', 'Small ruminant livestock e.g. sheep and goats', '	Pigs', 'Poultry', 'Milk']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        },
        stackLabels: {
            enabled: false
        }
    },
   
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
	credits: {
		enabled: false
	},
    plotOptions: {
        column: {
            stacking: 'normal',
            // dataLabels: {
            //     enabled: true
            // }
        }
    },
    series: [{
        name: 'Joint',
		color:"#5b9bd5",
        data: [8, 3, 11, 4, 5]
    }, {
        name: 'Men',
		color:"#ef8b48",
        data: [1, 1, 0, 0, 1]
    }, {
        name: 'Women',
		color:"#a5a5a5",
        data: [0, 0, 0, 4, 0]
    },{
        name: 'N/A',
		color:"#ffc000",
        data: [3, 8, 1, 3, 7]
    }]
});
</script>

<script>
	Highcharts.chart('gendered_decision_making_on_crop_feeding', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
        categories: ['Who decides on how to use crop residue?', 'Who decides on what crops to grow?', 'Who decides on what fodder type and where to grow?', 'Who decides or purchases feed?']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        },
        stackLabels: {
            enabled: false
        }
    },
   
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
	credits: {
		enabled: false
	},
    plotOptions: {
        column: {
            stacking: 'normal',
            // dataLabels: {
            //     enabled: true
            // }
        }
    },
    series: [{
        name: 'Joint',
		color:"#5b9bd5",
        data: [9, 8, 8, 9]
    }, {
        name: 'Men',
		color:"#ef8b48",
        data: [0, 1, 1, 2]
    }, {
        name: 'Women',
		color:"#a5a5a5",
        data: [3, 3, 2, 1]
    },{
        name: 'N/A',
		color:"#ffc000",
        data: [0, 0, 1, 1]
    }]
});
</script>

<script>
	Highcharts.chart('gendered_decision_making_on_crop_feeding', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
        categories: ['Who decides on how to use crop residue?', 'Who decides on what crops to grow?', 'Who decides on what fodder type and where to grow?', 'Who decides or purchases feed?']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        },
        stackLabels: {
            enabled: false
        }
    },
   
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
	credits: {
		enabled: false
	},
    plotOptions: {
        column: {
            stacking: 'normal',
            // dataLabels: {
            //     enabled: true
            // }
        }
    },
    series: [{
        name: 'Joint',
		color:"#5b9bd5",
        data: [9, 8, 8, 9]
    }, {
        name: 'Men',
		color:"#ef8b48",
        data: [0, 1, 1, 2]
    }, {
        name: 'Women',
		color:"#a5a5a5",
        data: [3, 3, 2, 1]
    },{
        name: 'N/A',
		color:"#ffc000",
        data: [0, 0, 1, 1]
    }]
});
</script>

<script>
	Highcharts.chart('gendered_decision_making_on_major_source', {
    chart: {
        type: 'column'
    },
    title: {
        text: '',
        align: 'left'
    },
    xAxis: {
        categories: ['Cash Crops', 'Dairying', 'Fattening Animals - Cattle', 'Labouring/Service', 
		'Off-Farm Business', 'Poultry - Eggs', 'Poultry - Meat', 'Fattening Animals - Sheep and Goats','Food Crops', 
		'Gilt (sow)-breeding females', 'Monthly salary', 'Gilt (sow)- breeding females']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        },
        stackLabels: {
            enabled: false
        }
    },
   
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
	credits: {
		enabled: false
	},
    plotOptions: {
        column: {
            stacking: 'normal',
            // dataLabels: {
            //     enabled: true
            // }
        }
    },
    series: [{
        name: 'Joint',
		color:"#5b9bd5",
        data: [5, 4, 3, 1, 2, 5, 1, 1, 6, 0, 0, 8]
    }, {
        name: 'Men',
		color:"#ef8b48",
        data: [0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 1, 0]
    }, {
        name: 'Women',
		color:"#a5a5a5",
        data: [0, 0, 0, 0, 1, 1, 0, 1, 1, 0, 0, 0]
    }]
});
</script>

<script>
	Highcharts.chart('major_source_of_income_for_women', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
        align: 'left'
    },
    tooltip: {
        pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
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
			dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        //enableMouseTracking: false,
        colorByPoint: true,
        data: [{
            name: 'Business',
			color:'#5b9bd5',
            y: 5.83
        }, {
            name: 'Cropping',
			color:'#ed7d31',
            y: 35.67
        }, {
            name: 'Labour',
			color:'#a8a6a6',
            y: 0.83
        }, {
            name: 'Livestock',
			color:'#ffc000',
            y: 57.67
        }]
    }]
});
</script>

<script>
	Highcharts.chart('relative_contribution_of_major_source_of_income', {
    chart: {
        type: 'bar'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: [
            '% of Household Incom', "% of Women's Income"]
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        reversed: false
    },
	credits: {
        enabled: false
    },
	tooltip: {
        valueSuffix: '%'
    },
    plotOptions: {
        series: {
            stacking: 'normal',
            dataLabels: {
                enabled: false,
            }
        }
    },
    series: [{
        name: 'Cash Crops',
		color:"#5b9bd5",
        data: [12.08, 11.25]
    }, {
        name: 'Dairying',
		color:"#ed7d31",
        data: [3.92, 5.00]
    }, {
        name: 'Fattening Animals - Cattle',
		color:"#a5a5a5",
        data: [10.42, 5.42]
    },{
        name: 'Food Crops',
		color:"#656460",
        data: [21.92, 24.42]
    },{
        name: 'Labouring/Service',
		color:"#ffc000",
        data: [0.83, 0.83]
    },{
        name: 'Off-Farm Business',
		color:"#5a7cb1",
        data: [13.33, 5.83]
    },{
        name: 'Poultry - Eggs',
		color:"#70ad47",
        data: [14.00, 16.50]
    },{
        name: 'Poultry - Meat',
		color:"#255e91",
        data: [0.17, 0.17]
    },{
        name: 'Fattening Animals - Sheep and Goats',
		color:"#806859",
        data: [1.00, 5.42]
    },{
        name: 'Gilt (sow)-breeding females',
		color:"#636363",
        data: [16.50, 25.17]
    },{
        name: 'Gilt (sow)- breeding females',
		color:"#9a7504",
        data: [3.33, 0.00]
    },{
        name: 'Monthly salary',
		color:"#264478",
        data: [2.50, 0.00]
    }]
});

</script>
<!-- End New Graphs -->

<script>
	Highcharts.chart('livestock_holdings_per_households', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
		title: {
            text: 'Category of Livestock'
        },
        categories: ['Dairy cows - lactating', 'Dairy cows - non lactating', ' Improved dairy cows - lactating', 
		'Draught cattle - working', 'Draught cattle - non working', 'Fattening cattle', 'Sheep', 'Goats', 'Pigs', 'Poultry', 'Camels', 'Horse', 'Donkeys', 'Buffalo']
    },
	yAxis: {
        min: 0,
        title: {
            text: 'Average number of individuals per household'
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        column: {
            borderRadius: '0%'
        }
    },
    series: [{
        name: 'Below average land holding',
		color: '#4f81bd',
        data: [5, 3, 4, 7, 2, 5, 3, 4, 7, 2, 5, 3, 4, 7]
    }, {
		name: 'Average land holding',
		color: '#c0504d',
        data: [2, 2, 3, 2, 1, 2, 2, 3, 2, 1, 2, 2, 3, 2]
    }, {
        name: 'Above average land holding',
		color: '#9bbb59',
        data: [3, 4, 4, 2, 5, 3, 4, 4, 2, 5, 3, 4, 4, 2]
    },{
        name: 'Average holding/HH',
		color: '#8064a2',
        data: [2, 2, 3, 2, 1, 2, 2, 3, 2, 1, 2, 2, 3, 2]
    }]
});
</script>

<script>
	Highcharts.chart('livestock_holdings', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category',
		title: {
            text: 'Category of Livestock'
        },
    },
    yAxis: {
        title: {
            text: 'Average household livestock holdings in Tropical livestock units (TLUs)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Livestock holdings',
			color:'#4bacc6',
            data: [
                {
                    name: 'Dairy cows - lactating',
                    y: 63.06,
                },
                {
                    name: 'Dairy cows - non lactating',
                    y: 19.84,
                },
                {
                    name: 'Improved dairy cows - lactating',
                    y: 4.18,
                },
                {
                    name: 'Draught cattle - working',
                    y: 4.12,
                },
                {
                    name: 'Draught cattle - non working',
                    y: 2.33,
                },
                {
                    name: 'Fattening cattle',
                    y: 0.45,
                },
                {
                    name: 'Sheep',
                    y: 1.582,
                },
				{
                    name: 'Goats',
                    y: 15.06,
                },
                {
                    name: 'Pigs',
                    y: 19.84,
                },
                {
                    name: 'Poultry',
                    y: 4.18,
                },
                {
                    name: 'Camels',
                    y: 4.12,
                },
				{
                    name: 'Horse',
                    y: 2.33,
                },
                {
                    name: 'Donkeys',
                    y: 0.45,
                },
                {
                    name: 'Buffalo',
                    y: 1.582,
                },
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('crop_varieties_grown', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category',
		title: {
            text: 'Major crops grown'
        },
    },
    yAxis: {
        title: {
            text: 'Area of land utilised for production (average hectares per household)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Crop varieties grown',
			color:'#c0504d',
            data: [
                {
                    name: 'Maize',
                    y: 63.06,
                },
                {
                    name: 'Sorghum ',
                    y: 19.84,
                },
				{
                    name: '0',
                    y: 19.84,
                },
                {
                    name: 'O',
                    y: 4.18,
                },
                {
                    name: '0',
                    y: 4.12,
                },
                {
                    name: 'Potato ',
                    y: 4.18,
                },
                {
                    name: 'Napier grass',
                    y: 4.12,
                },
                {
                    name: 'Spinach',
                    y: 2.33,
                },
                {
                    name: 'Kale',
                    y: 0.45,
                },
                {
                    name: 'Carrots',
                    y: 1.582,
                },
				{
                    name: 'Cabbage',
                    y: 15.06,
                },
            ]
        }
    ],
});
</script>



<script>
	Highcharts.chart('sources_of_energy', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category',
		// title: {
        //     text: 'Category of Livestock'
        // },
    },
    yAxis: {
        title: {
            text: 'Me (MJ per hh)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Livestock holdings',
			color:'#4bacc6',
            data: [
                {
                    name: 'Energy 1',
                    y: 63.06,
                },
                {
                    name: 'Energy 2',
                    y: 19.84,
                },
                {
                    name: 'Energy 3',
                    y: 4.18,
                },
                {
                    name: 'Energy 4',
                    y: 4.12,
                },
                {
                    name: 'Energy 5',
                    y: 2.33,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('the_energy_coming', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
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
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.percentage}%',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        enableMouseTracking: false,
        
        colorByPoint: true,
		data: [{
            name: 'Energy 1',
			color:'#4299b0',
            y: 21.3
        }, {
            name: 'Energy 2',
			color:'#71588f',
            y: 18.7
        }, {
            name: 'Energy 3',
			color:'#89a64e',
            y: 20.2
        }, {
            name: 'Energy 4',
			color:'#aa4643',
            y: 14.2
        }, {
            name: 'Energy 5',
			color:'#4572a8',
            y: 25.6
        }]
    }]
});
</script>

<script>
	Highcharts.chart('sources_of_protein_per_hh', {
    chart: {
        type: 'column'
    },
    title: {
        align: '',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category',
		// title: {
        //     text: 'Category of Livestock'
        // },
    },
    yAxis: {
        title: {
            text: 'Me (MJ per hh)'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            // dataLabels: {
            //     enabled: true,
            //     format: '{point.y:.1f}%'
            // }
        }
    },
	credits: {
        enabled: false
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y}</b> <br/>'
    },
    series: [
        {
            name: 'Sources of protein per hh',
			color:'#c0504d',
            data: [
                {
                    name: 'Protein 1',
                    y: 63.06,
                },
                {
                    name: 'Protein 2',
                    y: 19.84,
                },
                {
                    name: 'Protein 3',
                    y: 4.18,
                },
                {
                    name: 'Protein 4',
                    y: 4.12,
                },
                {
                    name: 'Protein 5',
                    y: 2.33,
                }
            ]
        }
    ],
});
</script>

<script>
	Highcharts.chart('the_protein_coming', {
    chart: {
        type: 'pie'
    },
    title: {
        text: '',
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
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b><br>{point.percentage}%',
                distance: 20
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        enableMouseTracking: false,
        
        colorByPoint: true,
        data: [{
            name: 'Protein 1',
			color:'#4299b0',
            y: 21.3
        }, {
            name: 'Protein 2',
			color:'#71588f',
            y: 18.7
        }, {
            name: 'Protein 3',
			color:'#89a64e',
            y: 20.2
        }, {
            name: 'Protein 4',
			color:'#aa4643',
            y: 14.2
        }, {
            name: 'Protein 5',
			color:'#4572a8',
            y: 25.6
        }]
    }]
});
</script>
