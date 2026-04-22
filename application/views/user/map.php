<link rel="stylesheet" href="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.css">
<style type="text/css">
	.dropdown-menu {
		width: auto !important;
	}
</style>
<style>
	label {
    font-weight: bold;
    color: #800000 !important;
  }
</style>
<!-- Main content -->
<div class="main-content">
	<div class="p-4">
		<div class="card">
			<div class="card-header">
				<h3>Map User's Location and Sites</h3>
			</div>
			<div class="card-body">
				<?php echo form_open('', array('id' => 'mapUserForm', 'autocomplete' => 'off')); ?>
				<div class="row">
					<div class="form-group col-6">
						<label for="user">User</label>
						<select class="form-control" name="user" title="-- Search / Select --">
							<?php foreach ($users as $key => $user) { if($user['role_id'] >= 3) { ?>
							<option value="<?php echo $user['user_id']; ?>">
								<?php echo $user['first_name'].' '.$user['last_name'].' ('.$user['username'].')'; ?>
							</option>
							<?php } } ?>
						</select>
					</div>

					<div class="form-group col-6">
						<label for="worldRegion">World region</label>
						<select id="worldRegion" class="form-control" name="worldRegion" title="-- Search / Select --">
							<option value="">--Select World Region--</option>
							<?php foreach ($world_region as $key => $region) { ?>
							<option value="<?php echo $region['id']; ?>">
								<?php echo $region['world_region_name']; ?>
							</option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group col-6">
						<label for="majorRegion">Select Major Region:</label>
						<select id="majorRegion" name="majorRegion" class="form-control" required>
							<option value="">--Select Major Region--</option>
						</select>
					</div>

					<div class="form-group col-6">
						<label for="country">Select Country:</label>
						<select id="country" name="country" class="form-control" required>
							<option value="">--Select Country--</option>
						</select>
					</div>

					<!-- <div class="form-group col-6 acc_group"></div>
					<div class="form-group col-6 house_bank"></div> -->

					<div class="form-group col-12 locations"></div>

					<div class="form-group col-12 sitesProjects"></div>

					<div class="col-12 text-right">
						<h5 class="text-danger form error float-left title mt-1"></h5>
						<button type="submit" class="btn btn-success">Map User</button>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>include/plugins/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript">
	
		const data = {
            sites: <?php echo json_encode($sites); ?>,
            projects: <?php echo json_encode($projects); ?>,
            worldRegions: <?php echo json_encode($world_region); ?>,
            majorRegions: <?php echo json_encode($major_region); ?>,
            countries: <?php echo json_encode($countries); ?>
        };

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

        // When major region changes, populate countries
        $('#country').change(function() {
            const worldRegionId = $('#worldRegion').val();
            const majorRegionId = $('#majorRegion').val();
            const countryId = $(this).val();
            $('#selectProject').empty().append('<option value="">--Select Project--</option>');

            const filteredProjects = data.projects.filter(region => region.world_region_id == worldRegionId && region.major_region_id == majorRegionId && region.country_id == countryId);

            // if (filteredProjects) {
            //     filteredProjects.forEach(project => {
            //         $('#selectProject').append(new Option(project.project_name, project.id));
            //     });
            // }
        });

		let states = []
		let projects = []
		let sites = []
		let selectedSites = []
		
		$(function(){
			$("[name='agency'], [name='user']").selectpicker({
				actionsBox: true,
				liveSearch: true
			});
		});

		// Define global variable ajaxData
		var ajaxData = { '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' };

		// Handle checkbox tree view
		$(document).on('click', '.tree label', function(e) {
			$(this).next('ul').fadeToggle();
			e.stopPropagation();
		});

		$(document).on('change', '.tree input[type=checkbox]', function(e) {
			$(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
			$(this).parentsUntil('.tree').children("input[type='checkbox']").prop('checked', this.checked);
			e.stopPropagation();
		});
		
		$(document).on('click', '.project_tree label', function(e) {
			$(this).next('ul').fadeToggle();
			e.stopPropagation();
		});

		$(document).on('change', '.project_tree input[type=checkbox]', function(e) {
			$(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
			$(this).parentsUntil('.project_tree').children("input[type='checkbox']").prop('checked', this.checked);
			e.stopPropagation();
		});

		// Handle selecting/unselecting all sites when a project checkbox is clicked
		$(document).on('change', '.project_tree input[name="project[]"]', function (e) {
			const isChecked = $(this).prop('checked');
			const projectId = $(this).val();

			// Select or unselect all sites under this project
			$(this).closest('li').find('ul:first input[name="site[]"]').prop('checked', isChecked);

			e.stopPropagation();  // Prevent bubbling
		});
		
		$(document).on('change', '.tree input[type=checkbox]', function(e) {
			// Prevent the event from bubbling up
			e.stopPropagation();

			// Get the checkbox that was clicked
			var clickedCheckbox = $(this);
			
			// Check if it's a 'state' checkbox (top-level checkbox in the tree)
			if (clickedCheckbox.attr('name') === 'state[]') {
				console.log('State checkbox changed: ', clickedCheckbox.val());
			}

			// Now, gather the selected states (checkboxes that are checked)
			var selectedStates = [];
			
			// Iterate through all 'state' checkboxes and get those that are checked
			$('.tree input[name="state[]"]:checked').each(function() {
				selectedStates.push($(this).val());
				// let countryIds = states
				// 	.filter(state => selectedStates.includes(state.state_id))
				// 	.map(state => state.country_id);
				// let filteredProjects = projects.filter(project => countryIds.includes(project.country_id));
				// let filteredSites = sites.filter(site => countryIds.includes(site.country_id));
				// initSitesProjects(filteredProjects, filteredSites, selectedSites)
			});
		});

		// Initialize Sites and Projects
		function initSitesProjects(projects, sites, selectedSites) {
			let locationHTML = ``;
			selectedSites = selectedSites.length === 0 ? null : selectedSites;

			// Loop through projects to generate the HTML with checkboxes
			for (var project of projects) {
				let checked = '';
				const selected_project = selectedSites && selectedSites.filter(item => item.project_id == project.id);
				if (selected_project && selected_project.length > 0 && selected_project[0].project_id == project.id) {
					checked = 'checked';
				}

				locationHTML += `
				<li class="col-12">
					<input type="checkbox" name="project[]" value="${project.id}" ${checked}>
					<label>${project.project_name}</label>
				`;

				// Now handle the sites for the selected project
				const project_sites = sites.filter(item => item.project_id == project.id);
				let did = 0;

				// Loop through sites
				for (var site of project_sites) {
					if (did === 0) locationHTML += `<ul>`;  // Add an <ul> before listing sites

					checked = '';
					if (selected_project && selected_project[0]?.sites && selected_project[0].sites.includes(String(site.id))) {
						checked = 'checked';
					}

					locationHTML += `
						<li>
							<input type="checkbox" name="site[]" value="${site.id}" ${checked}>
							<label>${site.site_name}</label>
						</li>
					`;

					did++;
					if (did === project_sites.length) locationHTML += `</ul>`;  // Close the <ul> for sites
				}

				locationHTML += `</li>`;
			}

			// Inject the generated HTML for projects and sites into the DOM
			$('.sitesProjects').html(`
				<label>Modify Sites Assignment - <a href="javascript:void(0)" class="allSites text-success">Assign All Sites</a></label>
				<ul class="project_tree row">${locationHTML}</ul>
				<h6 class="text-danger location error title mt-1"></h6>
			`);

			// Show total direct children for each project
			$('.sitesProjects').find('label').each(function (index) {
				var label = $(this),
					total = label.closest('li').find('ul:first > li').length;
				if (total > 0) label.append(' <span class="total">(' + total + ')</span>');
			});
		}

		// Handle selecting/unselecting all sites when a project checkbox is clicked
		$(document).on('change', '.project_tree input[name="project[]"]', function (e) {
			const isChecked = $(this).prop('checked');
			const projectId = $(this).val();

			// Select or unselect all sites under this project
			$(this).closest('li').find('ul:first input[name="site[]"]').prop('checked', isChecked);

			e.stopPropagation();  // Prevent bubbling
		});

		//Handle user and country change
		$('body').on('change', '[name="user"]', function(event) {
			var elem = $(this);
			getLocations(elem.val(), $('[name="country"]').val());
		}).on('change', '[name="country"]', function(event) {
			var elem = $(this);
			getLocations($('[name="user"]').val(), elem.val());
		});

		function getLocations(user, country) {
			selectedSites = []
			$('.acc_group, .house_bank').empty();
			$('.locations').html(`<h6 class="text-info text-center">Select both User and country to continue mapping</h6>`);
			$('.sitesProjects').html(``);
			if(user.length === 0 || country.length === 0) return false;
			
			$('.locations').html('<h6 class="text-info text-center">Getting Locations... Please Wait...</h6>');
			
			let filteredProjects = data.projects.filter(project => country == project.country_id);
			let filteredSites = data.sites.filter(site => country == site.country_id);
			//send ajax request to get location
			ajaxData['country_id'] = country;
			ajaxData['user_id'] = user;
			$.ajax({
				url: '<?php echo base_url(); ?>Users/get_user_locations',
				type: 'POST',
				dataType : 'json',
				data: ajaxData,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function() {
					$.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error'
					});
					$('.locations').html('<h6 class="text-info text-center"> Please change either User or country to get details.</h6>');
				},
				success: function(response) {
					// // Show account group as radio button
					// var HTML = ``;
					// for(var accgrp of response.allAccGrp) {
					// 	var checked = (response.selectedAccGrp && (response.selectedAccGrp.account_group_id == accgrp.account_group_id)) ? 'checked' : '';
					// 	HTML += `<div class="form-group">
					// 		<input type="radio" name="accgrp" id="accgrp-${accgrp.account_group_id}" value="${accgrp.account_group_id}" ${checked}>
					// 		<label for="accgrp-${accgrp.account_group_id}">${accgrp.account_group_code} - ${accgrp.account_group_name} - ${accgrp.reconciliation_account} - ${accgrp.purchasing_organization}</label>
					// 	</div>`;
					// }
					// $('.acc_group').html(`<label>Select User Account Group</label>
					// ${HTML}
					// <h6 class="text-danger accgrp error title mt-1"></h6>`);

					// // Show house bank as radio button
					// HTML = ``;
					// for(var hsebnk of response.allHseBank) {
					// 	var checked = (response.selectedHseBank && (response.selectedHseBank.house_bank_id == hsebnk.house_bank_id)) ? 'checked' : '';
					// 	HTML += `<div class="form-group">
					// 		<input type="radio" name="hsebnk" id="hsebnk-${hsebnk.house_bank_id}" value="${hsebnk.house_bank_id}" ${checked}>
					// 		<label for="hsebnk-${hsebnk.house_bank_id}">${hsebnk.name}</label>
					// 	</div>`;
					// }
					// $('.house_bank').html(`<label>Select User House Bank</label>
					// ${HTML}
					// <h6 class="text-danger hsebnk error title mt-1"></h6>`);

					if(response.status == 0) {
						$.toast({
							heading: 'Error!',
							text: response.msg,
							icon: 'error'
						});
						$('.locations').html('<h6 class="text-info text-center">Please change either User or country to get details.</h6>');
						return false;
					}
					
					states = response.states;
					projects = response.projects;
					sites = response.sites;
					response.selectedProjSites.forEach(item => {
						let project = selectedSites.find(p => p.project_id === item.project_id);
						if (project) {
							project.sites.push(item.site_id);
						} else {
							selectedSites.push({
								project_id: item.project_id,
								sites: [item.site_id]
							});
						}
					});
					initLocation(response.states, response.selectedLoc);
					initSitesProjects(filteredProjects, filteredSites, selectedSites);
				}
			});
		}

		//Initialize location tree
		function initLocation(states, selectedLoc) {
			var states = states;
			selectedLoc = JSON.parse(selectedLoc);
			selectedLoc = selectedLoc.length === 0 ? null : selectedLoc;

			locationHTML = ``;
			for (var state of states) {
				checked = '';
				if(selectedLoc && selectedLoc.states && selectedLoc.states.includes(state.state_id)) checked = 'checked';
				
				locationHTML += `<li class="col-lg-3 col-md-4 col-sm-6">
				<input type="checkbox" name="state[]" value="${state.state_id}" ${checked}>
				<label>${state.state_name}</label>`;
				var did = 0;
				for (var district of state.districts) {
					if(did === 0) locationHTML += `<ul>`;

					checked = '';
					if(selectedLoc && selectedLoc.districts && selectedLoc.districts.includes(district.district_id)) checked = 'checked';
					
					locationHTML += `<li>
					<input type="checkbox" name="district[]" value="${district.district_id}" ${checked}>
					<label>${district.district_name}</label>`;
					// var tid = 0;
					// for (var tehsil of district.tehsils) {
					// 	if(tid === 0) locationHTML += `<ul>`;

					// 	checked = '';
					// 	if(selectedLoc && selectedLoc.tehsils && selectedLoc.tehsils.includes(tehsil.tehsil_id)) checked = 'checked';

					// 	locationHTML += `<li>
					// 	<input type="checkbox" name="tehsil[]" value="${tehsil.tehsil_id}" ${checked}>
					// 	<label>${tehsil.tehsil_name}</label>`;
						// var bid = 0;
						// for (var block of tehsil.blocks) {
						// 	if(bid === 0) locationHTML += `<ul>`;

						// 	checked = '';
						// 	if(selectedLoc && selectedLoc.blocks && selectedLoc.blocks.includes(block.block_id)) checked = 'checked';

						// 	locationHTML += `<li>
						// 	<input type="checkbox" name="block[]" value="${block.block_id}" ${checked}>
						// 	<label>${block.block_name}</label>`;
							// var gid = 0;
							// for (var gp of block.gps) {
							// 	if(gid === 0) locationHTML += `<ul>`;

							// 	checked = '';
							// 	if(selectedLoc && selectedLoc.gps && selectedLoc.gps.includes(gp.grampanchayat_id)) checked = 'checked';

							// 	locationHTML += `<li>
							// 	<input type="checkbox" name="grampanchayat[]" value="${gp.grampanchayat_id}" ${checked}>
							// 	<label>${gp.grampanchayat_name}</label>`;
							// 	var vid = 0;
							// 	for (var village of gp.villages) {
							// 		if(vid === 0) locationHTML += `<ul>`;

							// 		checked = '';
							// 		if(selectedLoc && selectedLoc.villages && selectedLoc.villages.includes(village.village_id)) checked = 'checked';

							// 		locationHTML += `<li>
							// 		<input type="checkbox" name="village[]" value="${village.village_id}" ${checked}>
							// 		<label>${village.village_name}</label>`;
							// 		locationHTML += `</li>`;
							// 		vid++;

							// 		if(vid === gp.villages.length) locationHTML += `</ul>`;
							// 	}
							// 	locationHTML += `</li>`;
							// 	gid++;

							// 	if(gid === block.gps.length) locationHTML += `</ul>`;
							// }
						// 	locationHTML += `</li>`;
						// 	bid++;

						// 	if(bid === tehsil.blocks.length) locationHTML += `</ul>`;
						// }
					// 	locationHTML += `</li>`;
					// 	tid++;

					// 	if(tid === district.tehsils.length) locationHTML += `</ul>`;
					// }
					locationHTML += `</li>`;
					did++;

					if(did === state.districts.length) locationHTML += `</ul>`;
				}
				locationHTML += `</li>`;
			}
			$('.locations').html(`<label>Modify Location Assignment - <a href="javascript:void(0)" class="allLoc text-success">Assign All Locations</a></label>
			<ul class="tree row">${locationHTML}</ul>
			<h6 class="text-danger location error title mt-1"></h6>`);

			// Show total direct children
			$('.locations').find('label').each(function(index) {
				var label = $(this),
				total = label.closest('li').find('ul:first > li').length;
				if(total > 0) label.append(' <span class="total">('+total+')</span>');
			});
		}

		// Assign all locations
		$('body').on('click', '.allLoc', function(event) {
			var elem = $(this);
			elem.closest('.locations').find('ul.tree > li').find('input[type=checkbox]').prop('checked', true).trigger("change");
		});

		$('body').on('click', '.allSites', function(event) {
			var elem = $(this);
			elem.closest('.sitesProjects').find('ul.project_tree > li').find('input[type=checkbox]').prop('checked', true).trigger("change");
		});

		//Handle user mapping form submit
		$('body').on('submit', '#mapUserForm', function(event) {
			event.preventDefault();
			var form = $(this);
			form.find('.error').empty();
			form.find('button').prop('disabled', true);
			form.find('button[type="submit"]').html('Please wait...');

			var formData = new FormData($(this)[0]);
			let postData = {
				user: $('[name="user"]').val(),
                world_region_id: $('#worldRegion').val(),
                major_region_id: $('#majorRegion').val(),
                country_id: $('#country').val(),
				states: [],
				projects: [],
			}
			

			// First loop: Handle selected states and their associated districts
			$('input[name="state[]"]:checked').each(function() {
				let stateId = $(this).val();  // Get the selected state ID

				// Find the districts associated with the selected state (inside the <ul>)
				let selectedDistricts = [];
				$(this).closest('li').find('input[name="district[]"]:checked').each(function() {
					selectedDistricts.push($(this).val());  // Add selected district IDs to the array
				});

				// If any districts are selected, add the state and its selected districts to postData
				if (selectedDistricts.length > 0) {
					postData.states.push({
						stateId: stateId,  // The state ID
						districts: selectedDistricts  // The selected districts under this state
					});
				}
			});

			// Second loop: Handle districts that are selected independently
			$('input[name="district[]"]:checked').each(function() {
				let districtId = $(this).val();  // Get the selected district ID

				// Find the closest parent state for this district
				let associatedStateId = $(this).closest('li').closest('ul').closest('li').find('input[name="state[]"]').val();

				// Check if the district is already added to a state
				let existingState = postData.states.find(state => state.stateId === associatedStateId);

				// If the state doesn't exist in postData, create a new entry
				if (!existingState) {
					postData.states.push({
						stateId: associatedStateId,  // The associated state ID
						districts: [districtId]  // Only the selected district ID
					});
				} else {
					// If the state already exists, just add the district to the existing state's districts
					if (!existingState.districts.includes(districtId)) {
						existingState.districts.push(districtId);
					}
				}
			});

			// First loop: Handle selected projects and their associated sites
			$('input[name="project[]"]:checked').each(function() {
				let projectId = $(this).val();  // Get the selected project ID

				// Find the associated sites under the current project
				let selectedSites = [];
				$(this).closest('li').find('input[name="site[]"]:checked').each(function() {
					selectedSites.push($(this).val());  // Add the selected site ID to the array
				});

				// If any sites are selected, add the project and its selected sites to postData
				if (selectedSites.length > 0) {
					postData.projects.push({
						projectId: projectId,   // The project ID
						sites: selectedSites    // The selected site IDs under that project
					});
				}
			});

			// Second loop: Handle sites that are selected independently
			$('input[name="site[]"]:checked').each(function() {
				let siteId = $(this).val();  // Get the selected site ID

				// Find the closest parent project for this site
				let associatedProjectId = $(this).closest('li').closest('ul').closest('li').find('input[name="project[]"]').val();

				// Check if the site is already added to a project
				let existingProject = postData.projects.find(project => project.projectId === associatedProjectId);

				// If the project doesn't exist in postData, create a new entry
				if (!existingProject) {
					postData.projects.push({
						projectId: associatedProjectId,  // The associated project ID
						sites: [siteId]  // Only the selected site ID
					});
				} else {
					// If the project already exists, just add the site to the existing project's sites
					if (!existingProject.sites.includes(siteId)) {
						existingProject.sites.push(siteId);
					}
				}
			});

			$.ajax({
				url: '<?php echo base_url(); ?>users/update_user_mapping/',
				type: 'POST',
				data: JSON.stringify(postData),
				processData: false,
				contentType: false,
				complete: function(data) {
					var csrfData = JSON.parse(data.responseText);
					ajaxData[csrfData.csrfName] = csrfData.csrfHash;
					if(csrfData.csrfName && $('input[name="' + csrfData.csrfName + '"]').length > 0) {
						$('input[name="' + csrfData.csrfName + '"]').val(csrfData.csrfHash);
					}
				},
				error: function() {
					form.find('button').prop('disabled', false);
					form.find('button[type="submit"]').html('Map User');
					$.toast({
						heading: 'Network Error!',
						text: 'Could not establish connection to server. Please refresh the page and try again.',
						icon: 'error'
					});
				},
				success: function(data) {
					var data = JSON.parse(data);

					// If session error exists
					if(data.session_err == 1) {
						$.toast({
							heading: 'Session Error!',
							text: data.msg,
							icon: 'error'
						});

						form.find('button').prop('disabled', false);
						form.find('button[type="submit"]').html('Map User');
					}

					// If validation error exists
					if(data.status > 0) {
						for(var key in data) {
							var errorContainer = form.find(`.${key}.error`);
							if(errorContainer.length !== 0) {
								errorContainer.html(data[key]);
							}
						}
						form.find('button').prop('disabled', false);
						form.find('button[type="submit"]').html('Map User');
					}

					if(data.updatestatus == 1) {
						// If mapping completed
						$.toast({
							heading: 'Success!',
							text: data.msg,
							icon: 'success',
							afterHidden: function () {
								window.location.href = '<?php echo base_url(); ?>users/map';
							}
						});
					} else if(data.updatestatus == 0) {
						$.toast({
							heading: 'Error!',
							text: data.msg,
							icon: 'error'
						});
						form.find('button').prop('disabled', false);
						form.find('button[type="submit"]').html('Map User');
					}
				}
			});
		});
</script>