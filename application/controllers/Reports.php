<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}
	
	public function index(){
		/*$this->load->model('Employee_m', 'm');
		$data['posts'] = $this->m->getEmployee();*/
	    $this->load->view('product_admin/index');
	    $this->load->view('product_admin/side_nav');
	    $this->load->view('product_admin/header');
	    $this->load->view('product_admin/footer');	
	}


	public function get_data_details()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$data_id = $this->input->post('data_id');
		$survey_id = $this->input->post('survey_id');

		// Get Survey Village
		$this->db->select('survey.*, vill.village_name')->from('survey'.$survey_id.' AS survey');
		$this->db->join('lkp_village AS vill', 'vill.village_id = survey.field_10765');
		$this->db->where('survey.data_id', $data_id)->where('survey.status', 1);
		$survey_data = $this->db->order_by('survey.id', 'DESC')->get()->row_array();

		// Get Survey Images
		$this->db->select('*')->from('ic_data_file');
		$this->db->where('status', 1)->where('form_id', $survey_id);
		$this->db->where('data_id', $data_id)->where('file_type', 'image');
		$survey_data['images'] = $this->db->get()->result_array();

		// Return Data
		echo json_encode(array(
			'status' => 1,
			'survey_data' => $survey_data
		));
		exit();
	}


	public function view_registration()
	{
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$survey_id = $this->uri->segment(3);
		$selected_season = 'kharif-2023';
		$season = $this->uri->segment(3);
		if(isset($season) && strlen($season) > 0) {
			switch ($season) {
				case 'rabi':
					$selected_season = 'rabi';
					$start_date = "2022-03-01";
					$end_date = "2022-07-31";
				break;

				case 'kharif':
					$selected_season = 'kharif';
					$start_date = "2022-08-01";
					$end_date = "2023-07-24";
				break;

				case 'kharif-2023':
					$selected_season = 'kharif-2023';
					$start_date = "2023-07-25";
				break;
				
				default:
					$selected_season = 'kharif-2023';
					$start_date = "2023-07-25";
				break;
			}
		} else {
			$selected_season = 'kharif-2023';
			$start_date = "2023-07-25";
		}
		
		$result = array();
		$form_details = $this->db->select('id, title, description, type, pic_min, pic_max, location, datetime, dormant, status')->where('id', $survey_id)->where('status', 1)->get('form')->row_array();
		$result['form_details'] = $form_details;

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->get_country_projects();
		$sites = $this->Projects_model->get_country_sites();
		$this->db->select('lkp_country.*, lkp_project_site.id as site_id');
		$this->db->from('lkp_country');
		$this->db->join('lkp_project_site', 'lkp_country.country_id = lkp_project_site.country_id');
		$countries = $this->db->where('lkp_country.status', 1)->where('lkp_project_site.status', 1)->get()->result_array();
		// $this->Projects_model->get_countries();
		$major_region = $this->Projects_model->get_major_region();
		$minor_region = $this->Projects_model->get_minor_region();

		$result['projects'] = $projects;
		$result['sites'] = $sites;
		$result['countries'] = $countries;		
		$result['major_region'] = $major_region;
		$result['minor_region'] = $minor_region;

		$this->load->model('Reports_model');
		$result['country_list'] = $this->db->where('status', 1)->get('lkp_country')->result_array();        
		$result['state_list'] = $this->Reports_model->state_list();
		$result['district_list'] = $this->Reports_model->district_list();
		$result['block_list'] = $this->Reports_model->block_list();
		$result['village_list'] = $this->Reports_model->village_list();
		
		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['majorRegion_list'] = $this->db->get('lkp_major_region')->result_array();

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['minorRegion_list'] = $this->db->get('lkp_minor_region')->result_array();

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['communities_type_list'] = $this->db->get('lkp_communities_type')->result_array();

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['season_list'] = $this->db->get('lkp_add_season')->result_array();

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['units_list'] = $this->db->get('lkp_units')->result_array();

		$this->db->where('status', 1);
		$result['gender_list'] = $this->db->get('lkp_gender')->result_array();

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['income_activities_list'] = $this->db->get('lkp_income_activities')->result_array();

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$result['currency_list'] = $this->db->get('lkp_currency')->result_array();

		$result['world_region'] = $this->Projects_model->get_world_region();

		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();
		$header_result = array('main_menu' => $main_menu);


		$this->load->view('header', $header_result);
		$this->load->view('reports/view_registration', $result);
		$this->load->view('footer');
	}
	

	public function registration(){
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}
			
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->all_assigned_project();

		// $this->load->model('Survey_model');
		$this->load->model('Reports_model');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$pid = $this->input->post('project');
		} else {
			$pid = !empty($projects) ? $projects[0]['id'] : null;
		}
		$all_registration = $this->Reports_model->all_registration($pid);
		$ids = array();
		foreach ($all_registration as $key => $value) {
			array_push($ids, $value['id']);
		}

		$result = array('all_registration' => $all_registration, 'projects' => $projects);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			echo json_encode($result);
			exit();
		}

		$header_result = array('main_menu' => $main_menu);
		$result = $this->security->xss_clean($result);
		$this->load->view('header', $header_result);
		$this->load->view('reports/registration', $result);
		$this->load->view('footer');
	}
		
	public function get_village_survey()
	{
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}
		
		$survey_id = $this->uri->segment(3);		
		$type = $this->input->post('type');
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $this->input->post('worldRegionIds');
		$countryIds = $this->input->post('countryIds');
		if(!isset($countryIds) || count($countryIds) == 0 ){
			$this->db->distinct()->select('GROUP_CONCAT(country_id) as countries');
			$this->db->where('status', 1);
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			$getCountryList = $this->db->get('lkp_country')->row_array();
			$countryIds = explode(",", $getCountryList['countries']);
		}
		$projectIds = $this->input->post('projectIds');
		if(!isset($projectIds) || count($projectIds) == 0) {
			$this->db->distinct()->select('GROUP_CONCAT(project_id) as projects');
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('country_id', $countryIds);
			}
			$this->db->where('status', 1);
			$projectsList = $this->db->get('lkp_project_site')->row_array();

			if ($this->session->userdata('role') == 6) {
				$this->db->select('user_id')
						->from('tbl_users')
						->where('role_id', 6)
						->where('user_id !=', $user_id);
				$adminUsersResult = $this->db->get()->result_array();
				$adminUsers = array_column($adminUsersResult, 'user_id');

				// Main query for projects
				$this->db->select('GROUP_CONCAT(DISTINCT sites.project_id) as projects')
						->from('lkp_project_site as sites')
						->join('lkp_country_projects as projects', 'sites.project_id = projects.id')
						->where('sites.status', 1)
						->where('projects.status', 1);

				// Apply optional region and country filters
				if (!empty($worldRegionIds)) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if (!empty($countryIds)) {
					$this->db->where_in('sites.country_id', $countryIds);
				}

				// Apply project type and user_id conditions, excluding other admins
				$this->db->group_start()
						->where('projects.project_type', 'Public')
						->or_where('projects.user_id', $user_id)
						->group_end();
				if (!empty($adminUsers)) {
					$this->db->where_not_in('projects.user_id', $adminUsers);
				}

				$projectsList = $this->db->get()->row_array();
			}

			if($this->session->userdata('role') == 8) {
				$this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
				$this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.country_id', $countryIds);
				}
				$this->db->where('sites.status', 1)->where('projects.status', 1)->where('projects.user_id', $this->session->userdata('login_id'));
				$projectsList = $this->db->get('lkp_project_site as sites')->row_array();
			}
			$projectIds = explode(",", $projectsList['projects']);
		}

		$siteIds = $this->input->post('siteIds');
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		$page_no =  1;
		$record_per_page = 100;
		if($this->input->post('pagination')){
			$pagination = $this->input->post('pagination');
			$page_no = $pagination['pageNo'] != null ? $pagination['pageNo'] : 1;
			$record_per_page = $pagination['recordperpage'] != null ? $pagination['recordperpage'] : 100;
		}
		$is_pagination = $this->input->post('pagination') != null;
		$data = array(
			'survey_id' => $survey_id,
			"page_no" => $page_no,
			"record_per_page" => $record_per_page,
			"is_pagination" => $this->input->post('pagination') != null
		);

		ini_set('memory_limit', '-1');

		$this->load->model('Reports_model');
		$result = $this->Reports_model->survey_details($survey_id);

		
		// Get Survey Data
		$this->db->select('survey.*, tu.first_name, tu.last_name');
		if($type == 'approve' || $type == 'reject'){
			$this->db->select('CONCAT(tu_verify.first_name," ",tu_verify.last_name) as verified_full_name');
		}
		$this->db->from('survey'.$survey_id.' AS survey');
		$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
		if($type == 'approve' || $type == 'reject'){
			$this->db->join('tbl_users AS tu_verify', 'tu_verify.user_id = survey.verified_id');
		}
		if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
			if($survey_id == 4) {
				$this->db->where_in('survey.fgd_project_id', $projectIds);
			}else{
				$this->db->where_in('survey.project_id', $projectIds);
			}			
		}
		if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
			if($survey_id == 4) {
				$this->db->where_in('survey.fgd_site_id', $siteIds);
			}else{
				$this->db->where_in('survey.site_id', $siteIds);
			}
		}
		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('survey.country_id', $countryIds);
		}
		if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('survey.datetime >=', $start_date.' 00:00:00');
		}
		if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('survey.datetime <=', $end_date.' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('survey.user_id', $user_id);
		}
		$this->db->where_not_in('survey.status', [0, 4]);	
		if($type == 'approve'){
			$this->db->where('survey.verified', 1);
		} else if($type == 'reject'){
			$this->db->where('survey.verified', 0);
		} else {
			$this->db->where('survey.verified', null);
		}
		if($is_pagination){
            $this->db->limit($record_per_page,($record_per_page*$page_no)-($record_per_page));
        }
		$survey_data = $this->db->order_by('survey.id', 'DESC')->get()->result_array();
		
		$result['total_records'] = count($survey_data);

		foreach ($survey_data as $key => $value) {
			// Convert Upload Time to IST
			/* $date = new DateTime($survey_data[$key]['datetime'], new DateTimeZone('UTC'));
			$date->setTimezone(new DateTimeZone('Asia/Kolkata'));
			$survey_data[$key]['datetime'] = $date->format('Y-m-d H:i:s'); */

			$this->db->select('data_id, file_name, file_lat, file_long, field_id');
			$this->db->where('data_id', $value['data_id']);
			$this->db->where('form_id', $survey_id);
			$this->db->where('file_type', 'image')->where('status', 1);
			$images = $this->db->get('ic_data_file')->result_array();
			
			$lat1 = $long1 = $file1 = $lat2 = $long2 = $file2 = $lat3 = $long3 = $file3 = $lat4 = $long4 = $file4 = "N/A";
			
			$fields  = $result['fields'];
			foreach ($fields as $fkey => $field){
				if($field['type']=="file"){
					$image_field_id= 'field_'.$field['field_id'];					
			
					$this->db->select('data_id, file_name, file_lat, file_long, field_id');
					$this->db->where('data_id', $value['data_id']);
					$this->db->where('form_id', $survey_id);
					$this->db->where('file_type', 'image')->where('status', 1);
					$images = $this->db->get('ic_data_file')->result_array();
					if(!empty($images)){
						$images_array = array();
						foreach ($images as $ikey => $img){
							if($field['field_id'] == $img['field_id']){
								array_push($images_array,$img['file_name']);
							}
						}
						$survey_data[$key][$image_field_id]=$images_array;
					}
				}
			}			
		}
		$result['survey_data'] = $survey_data;
		$result['lkp_village'] = array();	
		$result['status'] = 1;
		echo json_encode($result);
		exit();
		
	}

	public function get_village_survey_export()
	{
		$baseurl = base_url();
		if (($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}

		$survey_id = $this->uri->segment(3);
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $this->input->post('worldRegionIds');
		$countryIds = $this->input->post('countryIds');
		if (!isset($countryIds) || count($countryIds) == 0) {
			$this->db->distinct()->select('GROUP_CONCAT(country_id) as countries');
			$this->db->where('status', 1);
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			$getCountryList = $this->db->get('lkp_country')->row_array();
			$countryIds = explode(",", $getCountryList['countries']);
		}
		$projectIds = $this->input->post('projectIds');
		if (!isset($projectIds) || count($projectIds) == 0) {
			$this->db->distinct()->select('GROUP_CONCAT(project_id) as projects');
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('country_id', $countryIds);
			}
			$this->db->where('status', 1);
			$projectsList = $this->db->get('lkp_project_site')->row_array();

			if ($this->session->userdata('role') == 6) {
				$this->db->select('user_id')
						->from('tbl_users')
						->where('role_id', 6)
						->where('user_id !=', $user_id);
				$adminUsersResult = $this->db->get()->result_array();
				$adminUsers = array_column($adminUsersResult, 'user_id');

				$this->db->select('GROUP_CONCAT(DISTINCT sites.project_id) as projects')
						->from('lkp_project_site as sites')
						->join('lkp_country_projects as projects', 'sites.project_id = projects.id')
						->where('sites.status', 1)
						->where('projects.status', 1);

				if (!empty($worldRegionIds)) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if (!empty($countryIds)) {
					$this->db->where_in('sites.country_id', $countryIds);
				}

				$this->db->group_start()
						->where('projects.project_type', 'Public')
						->or_where('projects.user_id', $user_id)
						->group_end();
				if (!empty($adminUsers)) {
					$this->db->where_not_in('projects.user_id', $adminUsers);
				}

				$projectsList = $this->db->get()->row_array();
			}

			if ($this->session->userdata('role') == 8) {
				$this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
				$this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.country_id', $countryIds);
				}
				$this->db->where('sites.status', 1)->where('projects.status', 1)->where('projects.user_id', $this->session->userdata('login_id'));
				$projectsList = $this->db->get('lkp_project_site as sites')->row_array();
			}
			$projectIds = explode(",", $projectsList['projects']);
		}
		$siteIds = $this->input->post('siteIds');
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		ini_set('memory_limit', '-1');
		$this->load->model('Reports_model');
		$result = $this->Reports_model->survey_details($survey_id);

		$result['country_list'] = $this->db->where('status', 1)->get('lkp_country')->result_array();
		$result['state_list'] = $this->Reports_model->state_list();
		$result['district_list'] = $this->Reports_model->district_list();
		$result['block_list'] = $this->Reports_model->block_list();
		$result['village_list'] = $this->Reports_model->village_list();
		$result['majorRegion_list'] = $this->db->where('status', 1)->get('lkp_major_region')->result_array();
		$result['minorRegion_list'] = $this->db->where('status', 1)->get('lkp_minor_region')->result_array();
		$result['communities_type_list'] = $this->db->where('status', 1)->get('lkp_communities_type')->result_array();
		$result['season'] = $this->db->where('status', 1)->get('lkp_add_season')->result_array();
		$result['units'] = $this->db->where('status', 1)->get('lkp_units')->result_array();
		$result['gender'] = $this->db->where('status', 1)->get('lkp_gender')->result_array();
		$result['income_activities'] = $this->db->where('status', 1)->get('lkp_income_activities')->result_array();
		$result['currency'] = $this->db->where('status', 1)->get('lkp_currency')->result_array();
		$result['lkp_fodder_type'] = $this->db->where('status', 1)->get('lkp_fodder_type')->result_array();
		$result['lkp_feed_type'] = $this->db->where('status', 1)->get('lkp_feed_type')->result_array();
		$result['lkp_livestock_sales'] = $this->db->where('status', 1)->get('lkp_livestock_sales')->result_array();
		$result['lkp_crop'] = $this->db->where('status', 1)->get('lkp_crop')->result_array();
		$result['lkp_animal_type'] = $this->db->where('status', 1)->get('lkp_animal_type')->result_array();
		$result['lkp_livestock'] = $this->db->where('status', 1)->get('lkp_livestock')->result_array();

		$this->db->select('survey.*, tu.first_name, tu.last_name');
		$this->db->from('survey' . $survey_id . ' AS survey');
		$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('survey.country_id', $countryIds);
		}
		if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
			if ($survey_id == 4) {
				$this->db->where_in('survey.fgd_project_id', $projectIds);
			} else {
				$this->db->where_in('survey.project_id', $projectIds);
			}
		}
		if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
			if ($survey_id == 4) {
				$this->db->where_in('survey.fgd_site_id', $siteIds);
			} else {
				$this->db->where_in('survey.site_id', $siteIds);
			}
		}
		if (isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
		}
		if (isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('survey.user_id', $user_id);
		}
		$this->db->where_not_in('survey.status', [0, 4]);
		$survey_data = $this->db->order_by('survey.id', 'DESC')->get()->result_array();

		foreach ($survey_data as $key => $value) {
			$date = new DateTime($survey_data[$key]['datetime'], new DateTimeZone('UTC'));
			$date->setTimezone(new DateTimeZone('Asia/Kolkata'));
			$survey_data[$key]['datetime'] = $date->format('Y-m-d H:i:s');

			$this->db->select('data_id, file_name, file_lat, file_long, field_id');
			$this->db->where('data_id', $value['data_id']);
			$this->db->where('form_id', $survey_id);
			$this->db->where('file_type', 'image')->where('status', 1);
			$images = $this->db->get('ic_data_file')->result_array();

			$lat1 = "N/A";
			$long1 = "N/A";
			$file1 = "N/A";
			$lat2 = "N/A";
			$long2 = "N/A";
			$file2 = "N/A";
			$lat3 = "N/A";
			$long3 = "N/A";
			$file3 = "N/A";
			$lat4 = "N/A";
			$long4 = "N/A";
			$file4 = "N/A";

			$fields = $result['fields'];
			foreach ($fields as $fkey => $field) {
				if ($field['type'] == "file") {
					$image_field_id = 'field_' . $field['field_id'];
					$this->db->select('data_id, file_name, file_lat, file_long, field_id');
					$this->db->where('data_id', $value['data_id']);
					$this->db->where('form_id', $survey_id);
					$this->db->where('file_type', 'image')->where('status', 1);
					$images = $this->db->get('ic_data_file')->result_array();
					if (!empty($images)) {
						$images_array = array();
						foreach ($images as $ikey => $img) {
							if ($field['field_id'] == $img['field_id']) {
								array_push($images_array, $img['file_name']);
							}
						}
						$survey_data[$key][$image_field_id] = $images_array;
					}
				}
			}
		}
		$result['survey_data'] = $survey_data;

		$getheaderfields = $this->db->select('field_id, label, child_id')->where('type', 'tab')->where('status', 1)->where('form_id', $survey_id)->order_by('slno')->get('form_field')->result_array();
		$headerInfo = array();
		$mergeFieldCount = 2;
		foreach ($getheaderfields as $hkey => $header) {
			$headerInfo[$hkey]['name'] = $header['label'];
			$fieldCount = 0;
			if ($header['child_id'] != NULL) {
				$fieldCount = count(explode(",", $header['child_id']));
			}
			$headerInfo[$hkey]['fieldcount'] = $fieldCount;

			$start = $mergeFieldCount;
			$end = $start + $fieldCount;

			$headerInfo[$hkey]['mergestart'] = $start;
			$headerInfo[$hkey]['mergeend'] = $end - 1;

			$mergeFieldCount = $end;
		}
		$result['headerInfo'] = $headerInfo;
		$result['lkp_village'] = array();

		$data_ids = !empty($survey_data) ? array_column($survey_data, 'data_id') : [];
		$getGroupData = array(
			'survey_id' => $survey_id,
			'data_id' => $data_ids,
		);

		$this->load->model('Reports_model');
		$result['group_info'] = $this->Reports_model->group_info($getGroupData);

		$result['status'] = 1;
		echo json_encode($result);
		exit();
	}

	public function get_dashboard_data(){
		$user_id = $this->session->userdata('login_id');
		$worldRegionIds = $this->input->post('worldRegionIds') ?? null;
		$countryIds = $this->input->post('countryIds') ?? null;
		if(!isset($countryIds) || count($countryIds) == 0 ){
			$this->db->distinct()->select('GROUP_CONCAT(country_id) as countries');
			$this->db->where('status', 1);
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			$getCountryList = $this->db->get('lkp_country')->row_array();
			$countryIds = explode(",", $getCountryList['countries']);
		}
		$projectIds = $this->input->post('projectIds') ?? null;
		if(!isset($projectIds) || count($projectIds) == 0) {
			$this->db->distinct()->select('GROUP_CONCAT(project_id) as projects');
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('country_id', $countryIds);
			}
			$this->db->where('status', 1);
			$projectsList = $this->db->get('lkp_project_site')->row_array();

			if ($this->session->userdata('role') == 6) {
				$this->db->select('user_id')
						->from('tbl_users')
						->where('role_id', 6)
						->where('user_id !=', $user_id);
				$adminUsersResult = $this->db->get()->result_array();
				$adminUsers = array_column($adminUsersResult, 'user_id');

				// Main query for projects
				$this->db->select('GROUP_CONCAT(DISTINCT sites.project_id) as projects')
						->from('lkp_project_site as sites')
						->join('lkp_country_projects as projects', 'sites.project_id = projects.id')
						->where('sites.status', 1)
						->where('projects.status', 1);

				// Apply optional region and country filters
				if (!empty($worldRegionIds)) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if (!empty($countryIds)) {
					$this->db->where_in('sites.country_id', $countryIds);
				}

				// Apply project type and user_id conditions, excluding other admins
				$this->db->group_start()
						->where('projects.project_type', 'Public')
						->or_where('projects.user_id', $user_id)
						->group_end();
				if (!empty($adminUsers)) {
					$this->db->where_not_in('projects.user_id', $adminUsers);
				}

				$projectsList = $this->db->get()->row_array();
			}

			if($this->session->userdata('role') == 8) {
				$this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
				$this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.country_id', $countryIds);
				}
				$this->db->where('sites.status', 1)->where('projects.status', 1)->where('projects.user_id', $this->session->userdata('login_id'));
				$projectsList = $this->db->get('lkp_project_site as sites')->row_array();
			}
			$projectIds = explode(",", $projectsList['projects']);
		}
		$siteIds = $this->input->post('siteIds') ?? null;

		$params = [
			'worldRegionIds' => $worldRegionIds,
			'countryIds' => $countryIds,
			'projectIds' => $projectIds,
			'siteIds' => $siteIds,
			'dateRange' => $postData['dateRange'] ?? null
		];
		// Landholding
			// households
				// %percentage of households-small farm (field_699)
				// %percentage of households-medium farm (field_956)
				// %percentage of households-large farm (field_957)
			// range of farms
				// farm size-landless (field_694)
				// farm size-small (field_695)
				// farm size-medium (field_696)
				// farm size-large (field_697)		
		$result['landholding'] = $this->get_landholds($params);		
		$result['range_farms'] = $this->get_range_farms($params);

		// Crop cultivation
			// crop types cultivated in ha
				// crop type (field_759)
				// cultivated area (field_761)
				// units (field_762)
			//crop grown in area
				//annual yield (field_909)
		$result['crop_cultivated_area'] = $this->getCropCultivationData('field_759', 'field_761', 'field_762', $params, 2);

		// Fooder crop cultivation
			// crop types cultivated in ha
				// crop type (field_779)
				// cultivated area (field_780)
				// units (field_781)
		$result['fodder_crop_cultivated_area'] = $this->getCropCultivationData('field_779', 'field_780', 'field_781', $params, 3);	
		
		// purchased feed
			// Average kg of feed purchased per household by feed type
				// feed purchases (field_782)
				// quantity purchased (field_783)
			// Available feed resources
				// monthly feed availability (field_845)
				// overall feed availability (field_846)
				// monthly diet composition (field_847)
				// availability - <selected monthly diet compostion> (field_849 to field_854)
		$result['avg_feed_purchased'] = $this->avg_feed_purchased($params);	
		$result['feed_availability'] = $this->feed_availability($params);	

		// Animal diet and nutrition
			// intake_by_source
				// contribution to animals diet (field_773) ; this is a check box and value can be "Purchased feed&#44;Grazing&#44;Collected fodder" or "Grazing" likewise.
				// % of contribution of animal's diet - <selected contribution to animals diet> (field_774 to field_778)
		$result['intake_by_source'] = $this->intake_by_source($params);	

		// Income by activity
			// Average household income by activity category
				// what are the four main sources of household income (field_746); use form_multiple,count each source and find the percentage of each 
		$result['income_by_activity'] = $this->income_by_activity($params);	
			
		// Contribution
			// Contribution of livelihood activities to household income (as a percnetage)
				// what are the four main sources of household income (field_746); 
				// Contribution to household income (%) - <selected source of household income> (field_748 and field_962 to field_977)
		$result['contibution_household_income'] = $this->contibution_household_income($params);	

		// Milk and livestock prices
			// Average price of major livestock species in USD by month
			// Average daily milk yield v/s Average price received per liter (USD)
		$result['avg_livestock_price'] = $this->avg_livestock_price($params);	
		$result['avg_daily_milk_price'] = $this->avg_daily_milk_price($params);	

		$result['dominant_livestock_categories'] = $this->dominant_livestock_categories($params);	
		$result['average_household_livestock_holdings_category'] = $this->average_household_livestock_holdings_category($params);	
		$result['average_household_livestock_holdings_type'] = $this->average_household_livestock_holdings_type($params);	

		$result['gender_pay_equality'] = $this->gender_pay_equality($params);	
		
    	$result['intake_values'] = $this->get_intake_values($params);
		
		echo json_encode($result);
		exit();
	}

	public function get_landholds($request){
		
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}		
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');	

		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
			$this->db->where_in('project_id', $projectIds);
		}	
		if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
			$this->db->where_in('site_id', $siteIds);
		}	
		if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('datetime >=', $start_date.' 00:00:00');
		}
		if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('datetime <=', $end_date.' 23:59:59');
		}		
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$query = $this->db->get('survey1');
		$total = $query->num_rows();

		$this->db->select([
			'SUM(field_699) AS small',
			'SUM(field_956) AS medium',
			'SUM(field_957) AS large'
		]);
		
		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
			$this->db->where_in('project_id', $projectIds);
		}	
		if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
			$this->db->where_in('site_id', $siteIds);
		}	
		if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('datetime >=', $start_date.' 00:00:00');
		}
		if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('datetime <=', $end_date.' 23:59:59');
		}		
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$query = $this->db->get('survey1');
		$result1 = $query->row_array();

		$small = (int)$result1['small'];
		$medium = (int)$result1['medium'];
		$large = (int)$result1['large'];
		
		// Calculate percentages
		$percentageSmall = ($total > 0 && $small > 0) ? floor(($small/$total)) : 0;
		$percentageMedium = ($total > 0 && $medium > 0) ? floor(($medium/$total)) : 0;
		$percentageLarge = ($total > 0 &&  $large > 0) ? floor(($large/$total)) : 0;
	
		// Assign the counts to the result array
		$landholds = [
			$percentageSmall,
			$percentageMedium,
			$percentageLarge
		];

		return $landholds;
	}

	public function get_range_farms($request){
		
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}		
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
			$this->db->where_in('project_id', $projectIds);
		}		
		if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
			$this->db->where_in('site_id', $siteIds);
		}												
		if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('datetime >=', $start_date.' 00:00:00');
		}
		if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('datetime <=', $end_date.' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$query = $this->db->get('survey1');
		$total = $query->num_rows();

		$this->db->select([
			'SUM(field_694) AS landless',
			'SUM(field_695) AS small',
			'SUM(field_696) AS medium',
			'SUM(field_697) AS large'
		]);
		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
			$this->db->where_in('project_id', $projectIds);
		}		
		if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
			$this->db->where_in('site_id', $siteIds);
		}												
		if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('datetime >=', $start_date.' 00:00:00');
		}
		if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('datetime <=', $end_date.' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$query = $this->db->get('survey1');
		$result2 = $query->row_array();
		
		$landless = (int)$result2['landless'];
		$small = (int)$result2['small'];
		$medium = (int)$result2['medium'];
		$large = (int)$result2['large'];
		
		// Calculate percentages
		$percent_landless = $total > 0 ? floor(($landless / $total) * 100) : 0;
		$percent_small = $total > 0 ? floor(($small / $total) * 100) : 0;
		$percent_medium = $total > 0 ? floor(($medium / $total) * 100) : 0;
		$percent_large = $total > 0 ? floor(($large / $total) * 100) : 0;
		
		$range_farms = [
			$percent_landless/2.471,
			$percent_small/2.471,
			$percent_medium/2.471,
			$percent_large/2.471
		];

		return $range_farms;
	}

	public function getCropCultivationData($crop_type_field, $cultivated_area, $units, $request, $round_it) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Count total survey4 records before join
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$totalRecords = $this->db->count_all_results(); // Get count of survey4 records

		if ($totalRecords == 0) {
			return ['all' => [], 'top5' => []]; // Return empty arrays if no survey4 records found
		}

		// Query to get crop types, cultivated area, and units from survey4
		$this->db->select('groupdata.groupfield_id, groupdata.data');
		$this->db->from('survey4');
		$this->db->join('survey4_groupdata as groupdata', 'survey4.data_id = groupdata.data_id');
		if (!empty($countryIds)) {
			$this->db->where_in('survey4.country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('survey4.fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('survey4.fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('survey4.datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('survey4.datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('survey4.user_id', $user_id);
		}
		$this->db->where_in('survey4.status', [1, 2]);
		if ($crop_type_field === 'field_759') {
			$this->db->where('groupdata.groupfield_id', 760);
		} elseif ($crop_type_field === 'field_779') {
			$this->db->where('groupdata.groupfield_id', 766);
		}
		$query = $this->db->get();
		$results = $query->result_array();

		$crop_data = [];

		foreach ($results as $row) {
			$data_array = json_decode($row['data'], true);
			if ($row['groupfield_id'] == 760) {
				if (empty($data_array['field_759']) || empty($data_array['field_761']) || empty($data_array['field_762'])) {
					continue;
				}
				$crop_type = $data_array['field_759'];
				$cultivated_area = (float)$data_array['field_761'];
				$units = trim($data_array['field_762']);
			} elseif ($row['groupfield_id'] == 766) {
				if (empty($data_array['field_779']) || empty($data_array['field_780']) || empty($data_array['field_781'])) {
					continue;
				}
				$crop_type = $data_array['field_779'];
				$cultivated_area = (float)$data_array['field_780'];
				$units = trim($data_array['field_781']);
			} else {
				continue;
			}

			// Convert acres to hectares if units == 2
			if (strtolower($units) == '2') {
				$cultivated_area = $this->acresToHectares($cultivated_area);
			}

			if (!isset($crop_data[$crop_type])) {
				$crop_data[$crop_type] = [
					'total_hectares' => 0,
					'count' => 0,
					'crop_name' => null
				];
			}

			// Fetch crop or fodder name
			if ($crop_type_field === 'field_759') {
				$name = $this->db->select('crop_name')
								->where('id', $crop_type)
								->get('lkp_crop')
								->row_array()['crop_name'] ?? 'Unknown';
				$crop_data[$crop_type]['crop_name'] = $name;
			} elseif ($crop_type_field === 'field_779') {
				$name = $this->db->select('fodder_type')
								->where('fodder_type_id', $crop_type)
								->get('lkp_fodder_type')
								->row_array()['fodder_type'] ?? 'Unknown';
				$crop_data[$crop_type]['crop_name'] = $name;
			}

			$crop_data[$crop_type]['total_hectares'] += $cultivated_area;
			$crop_data[$crop_type]['count']++;
		}

		$chart_data = [];
		foreach ($crop_data as $crop_type => $data) {
			$average_hectares = $totalRecords > 0 ? $data['total_hectares'] / $totalRecords : 0; // Divide by total survey4 records
			if ($average_hectares > 0) { // Only include crop types with average hectares > 0
				$chart_data[] = [
					'name' => $data['crop_name'] ?? $crop_type,
					'y' => round($average_hectares,  $round_it)
				];
			}
		}

		// Sort by 'y' in descending order
		usort($chart_data, function($a, $b) {
			return $b['y'] <=> $a['y'];
		});

		// Prepare top 5 and all data
		$all_data = $chart_data;
		$top5_data = array_slice($chart_data, 0, 5);

		return [
			'all' => $all_data,
			'top5' => $top5_data
		];
	}

	public function avg_feed_purchased($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Fetch active feed types
		$feeds = $this->db->select('feed_type_id, feed_type')
						->where('status', 1)
						->get('lkp_feed_type')
						->result_array();
		if (empty($feeds)) {
			return ['all' => [], 'top5' => []]; // Return empty arrays if no feed types are found
		}

		// Count total survey4 records before join
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$totalRecords = $this->db->count_all_results(); // Get count of survey4 records

		if ($totalRecords == 0) {
			return ['all' => [], 'top5' => []]; // Return empty arrays if no survey4 records found
		}

		$chart_data = [];
		foreach ($feeds as $feed) {
			$this->db->select('groupdata.data');
			$this->db->from('survey4 as survey');
			$this->db->join('survey4_groupdata as groupdata', 'survey.data_id = groupdata.data_id');
			if (!empty($countryIds)) {
				$this->db->where_in('survey.country_id', $countryIds);
			}
			if (!empty($projectIds)) {
				$this->db->where_in('survey.fgd_project_id', $projectIds);
			}
			if (!empty($siteIds)) {
				$this->db->where_in('survey.fgd_site_id', $siteIds);
			}
			if (!empty($start_date)) {
				$this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
			}
			if (!empty($end_date)) {
				$this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
			}
			if ($role_id == 8) {
				$this->db->where('survey.user_id', $user_id);
			}
			$this->db->where('groupdata.groupfield_id', 1114);
			$this->db->where_in('survey.status', [1, 2]);
			$this->db->like('groupdata.data', '"field_782":"' . $feed['feed_type_id'] . '"');
			$result = $this->db->get()->result_array();

			$sum = 0;
			foreach ($result as $res) {
				$jsondata = json_decode($res['data'], true);
				if (isset($jsondata['field_783']) && is_numeric($jsondata['field_783']) && 
					isset($jsondata['field_786']) && is_numeric($jsondata['field_786'])) {
					$sum += (float)($jsondata['field_786'] * $jsondata['field_783']);
				}
			}

			$avg = 0;
			if ($totalRecords > 0 && $sum > 0) {
				$avg = $sum / $totalRecords; // Calculate average using (field_786 * field_783) / totalRecords
			}

			if ($avg > 0) {
				$chart_data[] = [
					'name' => $feed['feed_type'],
					'y' => round($avg, 2) // Rounded to 2 decimal places for display
				];
			}
		}

		// Sort by 'y' in descending order
		usort($chart_data, function($a, $b) {
			return $b['y'] <=> $a['y'];
		});

		// Prepare top 5 and all data
		$all_data = $chart_data;
		$top5_data = array_slice($chart_data, 0, 5);

		return [
			'all' => $all_data,
			'top5' => $top5_data
		];
	}

	public function dominant_livestock_categories($request) {
        $baseurl = base_url();
        if (empty($this->session->userdata('login_id'))) {
            redirect($baseurl);
        }

        $user_id = $this->session->userdata('login_id');
        $role_id = $this->session->userdata('role');

        $countryIds = $request['countryIds'] ?? null;
        $projectIds = $request['projectIds'] ?? null;
        $siteIds = $request['siteIds'] ?? null;
        $start_date = $this->input->post('startDate');
        $end_date = $this->input->post('endDate');

        // Remove duplicates from input arrays to optimize query
        $countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
        $projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
        $siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

        // Fetch all active livestock categories
        $livestocks = $this->db->select('id, name')
                            ->where('status', 1)
                            ->get('lkp_livestock')
                            ->result_array();
        if (empty($livestocks)) {
            return []; // Return empty array if no livestock categories found
        }

        // Initialize data calculation array for TLUs
        $dataCalculation = [];
        foreach ($livestocks as $source) {
            $dataCalculation[$source['id']] = [
                'tlu_sum' => 0,
                'count' => 0
            ];
        }

        // Count total survey4 records before join
        $this->db->from('survey4');
        if (!empty($countryIds)) {
            $this->db->where_in('country_id', $countryIds);
        }
        if (!empty($projectIds)) {
            $this->db->where_in('fgd_project_id', $projectIds);
        }
        if (!empty($siteIds)) {
            $this->db->where_in('fgd_site_id', $siteIds);
        }
        if (!empty($start_date)) {
            $this->db->where('datetime >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('datetime <=', $end_date . ' 23:59:59');
        }
        if ($role_id == 8) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where_in('status', [1, 2]);
        $totalRecords = $this->db->count_all_results(); // Get count of survey4 records

        if ($totalRecords == 0) {
            return array_map(function($source) {
                return [
                    'name' => $source['name'],
                    'y' => 0
                ];
            }, $livestocks); // Return zeroed data if no records found
        }

        // Query to get livestock category data and fields for TLU calculation from survey4_groupdata
        $this->db->select('groupdata.data');
        $this->db->from('survey4 as survey');
        $this->db->join('survey4_groupdata as groupdata', 'survey.data_id = groupdata.data_id');
        if (!empty($countryIds)) {
            $this->db->where_in('survey.country_id', $countryIds);
        }
        if (!empty($projectIds)) {
            $this->db->where_in('survey.fgd_project_id', $projectIds);
        }
        if (!empty($siteIds)) {
            $this->db->where_in('survey.fgd_site_id', $siteIds);
        }
        if (!empty($start_date)) {
            $this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
        }
        if ($role_id == 8) {
            $this->db->where('survey.user_id', $user_id);
        }
        $this->db->where('groupdata.groupfield_id', 742);
        $this->db->where_in('survey.status', [1, 2]);
        $result = $this->db->get()->result_array();

        // Process results, calculating TLUs and aggregating by category
        foreach ($result as $res) {
            $jsondata = json_decode($res['data'], true);
            if (empty($jsondata['field_750']) || empty($jsondata['field_755']) || empty($jsondata['field_757'])) {
                continue; // Skip if required fields are missing
            }

            // Handle field_750 as comma-separated or array
            $categories = is_array($jsondata['field_750']) 
                ? $jsondata['field_750'] 
                : explode(',', $jsondata['field_750']);
            
            // Calculate TLU for the record: TLU = (field_757 * field_755) / 250
            $field_757 = (float) $jsondata['field_757'];
            $field_755 = (float) $jsondata['field_755'];
            $tlu = ($field_757 * $field_755) / 250;

            // Calculate average TLU per survey4 record: avg_tlu = TLU / totalRecords
            $avg_tlu = $tlu / $totalRecords;

            // Distribute avg_tlu across categories and track count
            foreach ($categories as $category_id) {
                $category_id = trim($category_id);
                if (isset($dataCalculation[$category_id])) {
                    $dataCalculation[$category_id]['tlu_sum'] += $avg_tlu;
                    $dataCalculation[$category_id]['count']++;
                }
            }
        }

        // Build chart data for Highcharts with summed average TLUs
        $chart_data = [];
        foreach ($livestocks as $source) {
            $sum_tlu = ($dataCalculation[$source['id']]['count'] > 0) 
                ? round($dataCalculation[$source['id']]['tlu_sum'], 2) // Sum of avg TLUs per category
                : 0;
            $chart_data[] = [
                'name' => $source['name'],
                'y' => $sum_tlu
            ];
        }

        // Sort by 'y' in descending order
        usort($chart_data, function($a, $b) {
            return $b['y'] <=> $a['y'];
        });

        // Return top 5 entries
        return array_slice($chart_data, 0, 5);
    }

	public function average_household_livestock_holdings_category($request) {
        $baseurl = base_url();
        if (empty($this->session->userdata('login_id'))) {
            redirect($baseurl);
        }

        $user_id = $this->session->userdata('login_id');
        $role_id = $this->session->userdata('role');

        $countryIds = $request['countryIds'] ?? null;
        $projectIds = $request['projectIds'] ?? null;
        $siteIds = $request['siteIds'] ?? null;
        $start_date = $this->input->post('startDate');
        $end_date = $this->input->post('endDate');

        // Remove duplicates from input arrays to optimize query
        $countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
        $projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
        $siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

        // Fetch all active livestock categories
        $livestocks = $this->db->select('id, name')
                            ->where('status', 1)
                            ->get('lkp_livestock')
                            ->result_array();
        if (empty($livestocks)) {
            return []; // Return empty array if no livestock categories found
        }

        // Initialize data calculation array for TLUs
        $dataCalculation = [];
        foreach ($livestocks as $source) {
            $dataCalculation[$source['id']] = [
                'tlu_sum' => 0,
                'count' => 0
            ];
        }

        // Count total survey4 records before join
        $this->db->from('survey4');
        if (!empty($countryIds)) {
            $this->db->where_in('country_id', $countryIds);
        }
        if (!empty($projectIds)) {
            $this->db->where_in('fgd_project_id', $projectIds);
        }
        if (!empty($siteIds)) {
            $this->db->where_in('fgd_site_id', $siteIds);
        }
        if (!empty($start_date)) {
            $this->db->where('datetime >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('datetime <=', $end_date . ' 23:59:59');
        }
        if ($role_id == 8) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where_in('status', [1, 2]);
        $totalRecords = $this->db->count_all_results(); // Get count of survey4 records

        if ($totalRecords == 0) {
            return array_map(function($source) {
                return [
                    'name' => $source['name'],
                    'y' => 0
                ];
            }, $livestocks); // Return zeroed data if no records found
        }

        // Query to get livestock category data and fields for TLU calculation from survey4_groupdata
        $this->db->select('groupdata.data');
        $this->db->from('survey4 as survey');
        $this->db->join('survey4_groupdata as groupdata', 'survey.data_id = groupdata.data_id');
        if (!empty($countryIds)) {
            $this->db->where_in('survey.country_id', $countryIds);
        }
        if (!empty($projectIds)) {
            $this->db->where_in('survey.fgd_project_id', $projectIds);
        }
        if (!empty($siteIds)) {
            $this->db->where_in('survey.fgd_site_id', $siteIds);
        }
        if (!empty($start_date)) {
            $this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
        }
        if ($role_id == 8) {
            $this->db->where('survey.user_id', $user_id);
        }
        $this->db->where('groupdata.groupfield_id', 742);
        $this->db->where_in('survey.status', [1, 2]);
        $result = $this->db->get()->result_array();

        // Process results, calculating TLUs and aggregating by category
        foreach ($result as $res) {
            $jsondata = json_decode($res['data'], true);
            if (empty($jsondata['field_750']) || empty($jsondata['field_755']) || empty($jsondata['field_757'])) {
                continue; // Skip if required fields are missing
            }

            // Handle field_750 as comma-separated or array
            $categories = is_array($jsondata['field_750']) 
                ? $jsondata['field_750'] 
                : explode(',', $jsondata['field_750']);
            
            // Calculate TLU for the record: TLU = (field_757 * field_755) / 250
            $field_757 = (float) $jsondata['field_757'];
            $field_755 = (float) $jsondata['field_755'];
            $tlu = ($field_757 * $field_755) / 250;

            // Calculate average TLU per survey4 record: avg_tlu = TLU / totalRecords
            $avg_tlu = $tlu / $totalRecords;

            // Distribute avg_tlu across categories and track count
            foreach ($categories as $category_id) {
                $category_id = trim($category_id);
                if (isset($dataCalculation[$category_id])) {
                    $dataCalculation[$category_id]['tlu_sum'] += $avg_tlu;
                    $dataCalculation[$category_id]['count']++;
                }
            }
        }

        // Build chart data for Highcharts with summed average TLUs, include only categories with y > 0
        $chart_data = [];
        foreach ($livestocks as $source) {
            $sum_tlu = ($dataCalculation[$source['id']]['count'] > 0) 
                ? round($dataCalculation[$source['id']]['tlu_sum'], 2) // Sum of avg TLUs per category
                : 0;
            if ($sum_tlu > 0) { // Only include categories with sum_tlu > 0
                $chart_data[] = [
                    'name' => $source['name'],
                    'y' => $sum_tlu
                ];
            }
        }

        // Sort by 'y' in descending order
        usort($chart_data, function($a, $b) {
            return $b['y'] <=> $a['y'];
        });

        return $chart_data;
    }

	public function average_household_livestock_holdings_type($request) {
        $baseurl = base_url();
        if (empty($this->session->userdata('login_id'))) {
            redirect($baseurl);
        }

        $user_id = $this->session->userdata('login_id');
        $role_id = $this->session->userdata('role');

        $countryIds = $request['countryIds'] ?? null;
        $projectIds = $request['projectIds'] ?? null;
        $siteIds = $request['siteIds'] ?? null;
        $start_date = $this->input->post('startDate');
        $end_date = $this->input->post('endDate');

        // Remove duplicates from input arrays to optimize query
        $countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
        $projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
        $siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

        // Fetch all active livestock categories
        $livestocks = $this->db->select('id, name')
                            ->where('status', 1)
                            ->get('lkp_animal_type')
                            ->result_array();
        if (empty($livestocks)) {
            return []; // Return empty array if no livestock categories found
        }

        // Initialize data calculation array for TLUs
        $dataCalculation = [];
        foreach ($livestocks as $source) {
            $dataCalculation[$source['id']] = [
                'tlu_sum' => 0,
                'count' => 0
            ];
        }

        // Count total survey4 records before join
        $this->db->from('survey4');
        if (!empty($countryIds)) {
            $this->db->where_in('country_id', $countryIds);
        }
        if (!empty($projectIds)) {
            $this->db->where_in('fgd_project_id', $projectIds);
        }
        if (!empty($siteIds)) {
            $this->db->where_in('fgd_site_id', $siteIds);
        }
        if (!empty($start_date)) {
            $this->db->where('datetime >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('datetime <=', $end_date . ' 23:59:59');
        }
        if ($role_id == 8) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where_in('status', [1, 2]);
        $totalRecords = $this->db->count_all_results(); // Get count of survey4 records

        if ($totalRecords == 0) {
            return array_map(function($source) {
                return [
                    'name' => $source['name'],
                    'y' => 0
                ];
            }, $livestocks); // Return zeroed data if no records found
        }

        // Query to get livestock category data and fields for TLU calculation from survey4_groupdata
        $this->db->select('groupdata.data');
        $this->db->from('survey4 as survey');
        $this->db->join('survey4_groupdata as groupdata', 'survey.data_id = groupdata.data_id');
        if (!empty($countryIds)) {
            $this->db->where_in('survey.country_id', $countryIds);
        }
        if (!empty($projectIds)) {
            $this->db->where_in('survey.fgd_project_id', $projectIds);
        }
        if (!empty($siteIds)) {
            $this->db->where_in('survey.fgd_site_id', $siteIds);
        }
        if (!empty($start_date)) {
            $this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
        }
        if ($role_id == 8) {
            $this->db->where('survey.user_id', $user_id);
        }
        $this->db->where('groupdata.groupfield_id', 742);
        $this->db->where_in('survey.status', [1, 2]);
        $result = $this->db->get()->result_array();

        // Process results, calculating TLUs and aggregating by category
        foreach ($result as $res) {
            $jsondata = json_decode($res['data'], true);
            if (empty($jsondata['field_886']) || empty($jsondata['field_755']) || empty($jsondata['field_757'])) {
                continue; // Skip if required fields are missing
            }

            // Handle field_886 as comma-separated or array
            $categories = is_array($jsondata['field_886']) 
                ? $jsondata['field_886'] 
                : explode(',', $jsondata['field_886']);
            
            // Calculate TLU for the record: TLU = (field_757 * field_755) / 250
            $field_757 = (float) $jsondata['field_757'];
            $field_755 = (float) $jsondata['field_755'];
            $tlu = ($field_757 * $field_755) / 250;

            // Calculate average TLU per survey4 record: avg_tlu = TLU / totalRecords
            $avg_tlu = $tlu / $totalRecords;

            // Distribute avg_tlu across categories and track count
            foreach ($categories as $category_id) {
                $category_id = trim($category_id);
                if (isset($dataCalculation[$category_id])) {
                    $dataCalculation[$category_id]['tlu_sum'] += $avg_tlu;
                    $dataCalculation[$category_id]['count']++;
                }
            }
        }

        // Build chart data for Highcharts with summed average TLUs, include only categories with y > 0
        $chart_data = [];
        foreach ($livestocks as $source) {
            $sum_tlu = ($dataCalculation[$source['id']]['count'] > 0) 
                ? round($dataCalculation[$source['id']]['tlu_sum'], 2) // Sum of avg TLUs per category
                : 0;
            if ($sum_tlu > 0) { // Only include categories with sum_tlu > 0
                $chart_data[] = [
                    'name' => $source['name'],
                    'y' => $sum_tlu
                ];
            }
        }

        // Sort by 'y' in descending order
        usort($chart_data, function($a, $b) {
            return $b['y'] <=> $a['y'];
        });

        return $chart_data;
	}

	public function gender_pay_equality($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('end_date');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Fetch currency conversion rates
		$getCurrencyData = $this->db->where('status', 1)->get('lkp_currency')->result_array();
		$currencyInfo = [];
		foreach ($getCurrencyData as $value) {
			$currencyInfo[$value['id']] = (float)($value['current_exchange_rate'] ?? 1);
		}

		// Build query for survey1 to get data_ids
		$this->db->select('data_id, field_651 as currency_id');
		$this->db->from('survey1');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$survey1_records = $this->db->get()->result_array();

		if (empty($survey1_records)) {
			return ['female_avg' => 0, 'male_avg' => 0]; // Return zeros if no survey1 records found
		}

		// Extract data_ids
		$data_ids = array_column($survey1_records, 'data_id');
		$currency_map = array_column($survey1_records, 'currency_id', 'data_id'); // Map data_id to currency_id

		// Query survey1_groupdata for all matching records
		$this->db->select('data, data_id');
		$this->db->from('survey1_groupdata');
		$this->db->where_in('data_id', $data_ids);
		$result = $this->db->get()->result_array();

		$female_sum = 0;
		$male_sum = 0;
		$record_count = 0;

		foreach ($result as $res) {
			$jsondata = json_decode($res['data'], true);
			$data_id = $res['data_id'];
			$currency_id = $currency_map[$data_id] ?? null;
			$conversion_rate = isset($currencyInfo[$currency_id]) ? $currencyInfo[$currency_id] : 1;

			// Check for valid numeric values
			$female_value = isset($jsondata['field_871']) && is_numeric($jsondata['field_871']) ? (float)$jsondata['field_871'] : 0;
			$male_value = isset($jsondata['field_872']) && is_numeric($jsondata['field_872']) ? (float)$jsondata['field_872'] : 0;

			// Apply currency conversion
			$female_sum += $female_value * $conversion_rate;
			$male_sum += $male_value * $conversion_rate;
			$record_count++;
		}

		// Calculate overall averages
		$female_avg = $record_count > 0 ? $female_sum / $record_count : 0;
		$male_avg = $record_count > 0 ? $male_sum / $record_count : 0;

		return [
			'female_avg' => round($female_avg, 5),
			'male_avg' => round($male_avg, 5)
		];
	}
	
	public function get_intake_values($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Fetch survey4 records with relevant fields
		$this->db->select('data_id, fgd_site_id, field_774 AS collected_fodder_pct, field_775 AS grazing_pct');
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$survey4_records = $this->db->get()->result_array();

		if (empty($survey4_records)) {
			return [
				'dry_matter_intake' => [
					['name' => 'Crop Residue', 'y' => 0],
					['name' => 'Cultivated Fodder', 'y' => 0],
					['name' => 'Purchased Feed', 'y' => 0],
					['name' => 'Collected Fodder', 'y' => 0],
					['name' => 'Grazing', 'y' => 0]
				],
				'metabolisable_energy_intake' => [
					['name' => 'Crop Residue', 'y' => 0],
					['name' => 'Cultivated Fodder', 'y' => 0],
					['name' => 'Purchased Feed', 'y' => 0],
					['name' => 'Collected Fodder', 'y' => 0],
					['name' => 'Grazing', 'y' => 0]
				],
				'crude_protein_intake' => [
					['name' => 'Crop Residue', 'y' => 0],
					['name' => 'Cultivated Fodder', 'y' => 0],
					['name' => 'Purchased Feed', 'y' => 0],
					['name' => 'Collected Fodder', 'y' => 0],
					['name' => 'Grazing', 'y' => 0]
				]
			];
		}

		$data_ids = array_column($survey4_records, 'data_id');
		$survey4_map = []; // Map data_id to survey4 fields
		foreach ($survey4_records as $record) {
			$survey4_map[$record['data_id']] = [
				'collected_fodder_pct' => $record['collected_fodder_pct'] ? (float)$record['collected_fodder_pct'] : 0,
				'grazing_pct' => $record['grazing_pct'] ? (float)$record['grazing_pct'] : 0,
				'fgd_site_id' => $record['fgd_site_id']
			];
		}

		// Fetch survey4_groupdata for Cultivated Fodder (766), Crop Residue (760), and Purchased Feed (1114)
		$this->db->select('data, data_id, groupfield_id');
		$this->db->from('survey4_groupdata');
		$this->db->where_in('data_id', $data_ids);
		$this->db->where_in('groupfield_id', [766, 760, 1114]); // Include all relevant groupfield_ids
		$this->db->where('status', 1);
		$groupdata_records = $this->db->get()->result_array();

		if (empty($groupdata_records)) {
			return [
				'dry_matter_intake' => [
					['name' => 'Crop Residue', 'y' => 0],
					['name' => 'Cultivated Fodder', 'y' => 0],
					['name' => 'Purchased Feed', 'y' => 0],
					['name' => 'Collected Fodder', 'y' => 0],
					['name' => 'Grazing', 'y' => 0]
				],
				'metabolisable_energy_intake' => [
					['name' => 'Crop Residue', 'y' => 0],
					['name' => 'Cultivated Fodder', 'y' => 0],
					['name' => 'Purchased Feed', 'y' => 0],
					['name' => 'Collected Fodder', 'y' => 0],
					['name' => 'Grazing', 'y' => 0]
				],
				'crude_protein_intake' => [
					['name' => 'Crop Residue', 'y' => 0],
					['name' => 'Cultivated Fodder', 'y' => 0],
					['name' => 'Purchased Feed', 'y' => 0],
					['name' => 'Collected Fodder', 'y' => 0],
					['name' => 'Grazing', 'y' => 0]
				]
			];
		}

		// Initialize sums and counts
		$cultivated_dm_sum = 0;
		$cultivated_me_sum = 0;
		$cultivated_cp_sum = 0;
		$crop_residue_dm_sum = 0;
		$crop_residue_me_sum = 0;
		$crop_residue_cp_sum = 0;
		$purchased_feed_dm_sum = 0;
		$purchased_feed_me_sum = 0;
		$purchased_feed_cp_sum = 0;
		$cultivated_count = 0;
		$crop_residue_count = 0;
		$purchased_feed_count = 0;
		$collected_fodder_dm_sum = 0;
		$collected_fodder_me_sum = 0;
		$collected_fodder_cp_sum = 0;
		$grazing_dm_sum = 0;
		$grazing_me_sum = 0;
		$grazing_cp_sum = 0;
		$collected_fodder_count = 0;
		$grazing_count = 0;

		// Pre-calculate DM sums for each data_id
		$dm_sums = [];
		foreach ($groupdata_records as $record) {
			$jsondata = json_decode($record['data'], true);
			$data_id = $record['data_id'];
			$groupfield_id = $record['groupfield_id'];

			if (!isset($dm_sums[$data_id])) {
				$dm_sums[$data_id] = 0;
			}

			if ($groupfield_id == 766 && !empty($jsondata) && isset($jsondata['field_779']) && isset($jsondata['field_780']) && isset($jsondata['field_781'])) {
				// Cultivated Fodder
				$fodder_type_id = $jsondata['field_779'];
				$cultivated_area = (float)$jsondata['field_780'];
				$unit_id = $jsondata['field_781'];

				// Fetch unit conversion from lkp_units
				$this->db->select('unit_name, equivalent');
				$this->db->from('lkp_units');
				$this->db->where('unit_id', $unit_id);
				$this->db->where('status', 1);
				$unit = $this->db->get()->row_array();

				$area_in_hectares = $cultivated_area;
				if ($unit && !empty($unit['equivalent'])) {
					$equivalent = (float)$unit['equivalent'];
					$area_in_hectares = $cultivated_area * $equivalent;
				} elseif ($unit && $unit['unit_name'] == 'Hectare (ha)') {
					$area_in_hectares = $cultivated_area;
				}

				// Fetch fodder type details
				$this->db->select('kg_dry_matter, metabolisable_energy, crude_protein_content');
				$this->db->from('lkp_fodder_type');
				$this->db->where('fodder_type_id', $fodder_type_id);
				$this->db->where('status', 1);
				$fodder = $this->db->get()->row_array();

				if ($fodder) {
					$dm = $fodder['kg_dry_matter'] ? (float)$fodder['kg_dry_matter'] : 0;
					$me = $fodder['metabolisable_energy'] ? (float)$fodder['metabolisable_energy'] : 0;
					$cp = $fodder['crude_protein_content'] ? (float)$fodder['crude_protein_content'] : 0;

					$cultivated_dm_value = $area_in_hectares * $dm;
					$cultivated_dm_value = max(0, $cultivated_dm_value); // Ensure non-negative DM
					$cultivated_me_value = $cultivated_dm_value * $me;
					$cultivated_cp_value = ($cp * $cultivated_dm_value) / 100;

					$cultivated_dm_sum += $cultivated_dm_value;
					$cultivated_me_sum += $cultivated_me_value;
					$cultivated_cp_sum += $cultivated_cp_value;
					$dm_sums[$data_id] += $cultivated_dm_value;
					$cultivated_count++; // Increment count even if DM is 0
				}
			} elseif ($groupfield_id == 760 && !empty($jsondata) && isset($jsondata['field_759']) && isset($jsondata['field_761']) && isset($jsondata['field_762']) && isset($jsondata['field_909']) && isset($jsondata['field_767'])) {
				// Crop Residue
				$crop_id = $jsondata['field_759'];
				$cultivated_area = (float)$jsondata['field_761'];
				$unit_id = $jsondata['field_762'];
				$annual_yield = (float)$jsondata['field_909'];
				$fed_to_animals = (float)$jsondata['field_767'];

				// Fetch unit conversion from lkp_units
				$this->db->select('unit_name, equivalent');
				$this->db->from('lkp_units');
				$this->db->where('unit_id', $unit_id);
				$this->db->where('status', 1);
				$unit = $this->db->get()->row_array();

				$area_in_hectares = $cultivated_area;
				if ($unit && !empty($unit['equivalent'])) {
					$equivalent = (float)$unit['equivalent'];
					$area_in_hectares = $cultivated_area * $equivalent;
				} elseif ($unit && $unit['unit_name'] == 'Hectare (ha)') {
					$area_in_hectares = $cultivated_area;
				}

				// Fetch crop details from lkp_crop
				$this->db->select('harvest_index, dry_matter_content, metabolisable_energy, crude_protein_content');
				$this->db->from('lkp_crop');
				$this->db->where('id', $crop_id);
				$this->db->where('status', 1);
				$crop = $this->db->get()->row_array();

				if ($crop) {
					$harvest_index = $crop['harvest_index'] ? (float)$crop['harvest_index'] : 0;
					$dm_content = $crop['dry_matter_content'] ? (float)$crop['dry_matter_content'] : 0;
					$me_content = $crop['metabolisable_energy'] ? (float)$crop['metabolisable_energy'] : 0;
					$cp_content = $crop['crude_protein_content'] ? (float)$crop['crude_protein_content'] : 0;

					// Calculations for Crop Residue
					$residue_to_grain_ratio = $harvest_index > 0 ? (1 - $harvest_index) / $harvest_index : 0;
					$crop_residue_produce = $residue_to_grain_ratio * $annual_yield;
					$fed_percentage = $fed_to_animals / 100;
					$amount_fed_to_livestock = $crop_residue_produce * $fed_percentage;
					$dm = $dm_content / 100;
					$crop_residue_dm_fed = $amount_fed_to_livestock * $dm;
					$crop_residue_dm_fed = max(0, $crop_residue_dm_fed); // Ensure non-negative DM
					$crop_residue_me_fed = $crop_residue_dm_fed * $me_content;
					$crop_residue_cp_fed = $crop_residue_dm_fed * ($cp_content / 100);

					$crop_residue_dm_sum += $crop_residue_dm_fed;
					$crop_residue_me_sum += $crop_residue_me_fed;
					$crop_residue_cp_sum += $crop_residue_cp_fed;
					$dm_sums[$data_id] += $crop_residue_dm_fed;
					$crop_residue_count++; // Increment count even if DM is 0
				}
			} elseif ($groupfield_id == 1114 && !empty($jsondata) && isset($jsondata['field_782']) && isset($jsondata['field_783']) && isset($jsondata['field_786'])) {
				// Purchased Feed
				$feed_id = $jsondata['field_782'];
				$quantity_purchased = (float)$jsondata['field_783'];
				$times_purchased = (float)$jsondata['field_786'];

				// Fetch feed type details from lkp_feed_type
				$this->db->select('dry_matter_content, metabolisable_energy, crude_protein_content');
				$this->db->from('lkp_feed_type');
				$this->db->where('feed_type_id', $feed_id);
				$this->db->where('status', 1);
				$feed = $this->db->get()->row_array();

				if ($feed) {
					$dm_content = $feed['dry_matter_content'] ? (float)$feed['dry_matter_content'] : 0;
					$me_content = $feed['metabolisable_energy'] ? (float)$feed['metabolisable_energy'] : 0;
					$cp_content = $feed['crude_protein_content'] ? (float)$feed['crude_protein_content'] : 0;

					// Calculations for Purchased Feed
					$annual_purchase_feed = $quantity_purchased * $times_purchased;
					$dm = $dm_content / 100;
					$dm_purchased_feed = $annual_purchase_feed * $dm;
					$dm_purchased_feed = max(0, $dm_purchased_feed); // Ensure non-negative DM
					$me_purchased_feed = $dm_purchased_feed * $me_content;
					$cp_purchased_feed = $dm_purchased_feed * ($cp_content / 100);

					$purchased_feed_dm_sum += $dm_purchased_feed;
					$purchased_feed_me_sum += $me_purchased_feed;
					$purchased_feed_cp_sum += $cp_purchased_feed;
					$dm_sums[$data_id] += $dm_purchased_feed;
					$purchased_feed_count++; // Increment count even if DM is 0
				}
			}
		}

		// Process Collected Fodder and Grazing
		foreach ($groupdata_records as $record) {
			$jsondata = json_decode($record['data'], true);
			$data_id = $record['data_id'];
			if (isset($survey4_map[$data_id])) {
				$survey_data = $survey4_map[$data_id];
				$collected_fodder_pct = $survey_data['collected_fodder_pct'];
				$grazing_pct = $survey_data['grazing_pct'];
				$fgd_site_id = $survey_data['fgd_site_id'];

				// Fetch site data from lkp_project_site
				$this->db->select('collectedmetabolisable, collectedcrude, grazingmetabolisable, grazingcrude');
				$this->db->from('lkp_project_site');
				$this->db->where('id', $fgd_site_id);
				$this->db->where('status', 1);
				$site_data = $this->db->get()->row_array();

				$total_other_pct = 100 - $grazing_pct - $collected_fodder_pct;
				$base_dm = isset($dm_sums[$data_id]) ? $dm_sums[$data_id] : 0;

				// Collected Fodder
				if ($site_data) {
					$collected_fodder_dm = $base_dm > 0 ? ($base_dm / $total_other_pct) * $collected_fodder_pct : 0;
					$collected_fodder_dm = max(0, $collected_fodder_dm); // Ensure non-negative DM
					$collected_me_site = $site_data['collectedmetabolisable'] ? (float)$site_data['collectedmetabolisable'] : 0;
					$collected_cp_site = $site_data['collectedcrude'] ? (float)$site_data['collectedcrude'] / 100 : 0;

					$collected_fodder_me_fed = $collected_fodder_dm * $collected_me_site;
					$collected_fodder_cp_fed = $collected_fodder_dm * $collected_cp_site;

					$collected_fodder_dm_sum += $collected_fodder_dm;
					$collected_fodder_me_sum += $collected_fodder_me_fed;
					$collected_fodder_cp_sum += $collected_fodder_cp_fed;
					$collected_fodder_count++;
				}

				// Grazing
				if ($site_data) {
					$grazing_dm = $base_dm > 0 ? ($base_dm / $total_other_pct) * $grazing_pct : 0;
					$grazing_dm = max(0, $grazing_dm); // Ensure non-negative DM
					$grazing_me_site = $site_data['grazingmetabolisable'] ? (float)$site_data['grazingmetabolisable'] : 0;
					$grazing_cp_site = $site_data['grazingcrude'] ? (float)$site_data['grazingcrude'] / 100 : 0;

					$grazing_me_fed = $grazing_dm * $grazing_me_site;
					$grazing_cp_fed = $grazing_dm * $grazing_cp_site;

					$grazing_dm_sum += $grazing_dm;
					$grazing_me_sum += $grazing_me_fed;
					$grazing_cp_sum += $grazing_cp_fed;
					$grazing_count++;
				}
			}
		}

		// Calculate averages
		$cultivated_dm_avg = $cultivated_count > 0 ? $cultivated_dm_sum / $cultivated_count : 0;
		$cultivated_me_avg = $cultivated_count > 0 ? $cultivated_me_sum / $cultivated_count : 0;
		$cultivated_cp_avg = $cultivated_count > 0 ? $cultivated_cp_sum / $cultivated_count : 0;
		$crop_residue_dm_avg = $crop_residue_count > 0 ? $crop_residue_dm_sum / $crop_residue_count : 0;
		$crop_residue_me_avg = $crop_residue_count > 0 ? $crop_residue_me_sum / $crop_residue_count : 0;
		$crop_residue_cp_avg = $crop_residue_count > 0 ? $crop_residue_cp_sum / $crop_residue_count : 0;
		$purchased_feed_dm_avg = $purchased_feed_count > 0 ? $purchased_feed_dm_sum / $purchased_feed_count : 0;
		$purchased_feed_me_avg = $purchased_feed_count > 0 ? $purchased_feed_me_sum / $purchased_feed_count : 0;
		$purchased_feed_cp_avg = $purchased_feed_count > 0 ? $purchased_feed_cp_sum / $purchased_feed_count : 0;
		$collected_fodder_dm_avg = $collected_fodder_count > 0 ? $collected_fodder_dm_sum / $collected_fodder_count : 0;
		$collected_fodder_me_avg = $collected_fodder_count > 0 ? $collected_fodder_me_sum / $collected_fodder_count : 0;
		$collected_fodder_cp_avg = $collected_fodder_count > 0 ? $collected_fodder_cp_sum / $collected_fodder_count : 0;
		$grazing_dm_avg = $grazing_count > 0 ? $grazing_dm_sum / $grazing_count : 0;
		$grazing_me_avg = $grazing_count > 0 ? $grazing_me_sum / $grazing_count : 0;
		$grazing_cp_avg = $grazing_count > 0 ? $grazing_cp_sum / $grazing_count : 0;

		return [
			'dry_matter_intake' => [
				['name' => 'Crop Residue', 'y' => round($crop_residue_dm_avg, 2)],
				['name' => 'Cultivated Fodder', 'y' => round($cultivated_dm_avg, 2)],
				['name' => 'Purchased Feed', 'y' => round($purchased_feed_dm_avg, 2)],
				['name' => 'Collected Fodder', 'y' => round($collected_fodder_dm_avg, 2)],
				['name' => 'Grazing', 'y' => round($grazing_dm_avg, 2)]
			],
			'metabolisable_energy_intake' => [
				['name' => 'Crop Residue', 'y' => round($crop_residue_me_avg, 2)],
				['name' => 'Cultivated Fodder', 'y' => round($cultivated_me_avg, 2)],
				['name' => 'Purchased Feed', 'y' => round($purchased_feed_me_avg, 2)],
				['name' => 'Collected Fodder', 'y' => round($collected_fodder_me_avg, 2)],
				['name' => 'Grazing', 'y' => round($grazing_me_avg, 2)]
			],
			'crude_protein_intake' => [
				['name' => 'Crop Residue', 'y' => round($crop_residue_cp_avg, 2)],
				['name' => 'Cultivated Fodder', 'y' => round($cultivated_cp_avg, 2)],
				['name' => 'Purchased Feed', 'y' => round($purchased_feed_cp_avg, 2)],
				['name' => 'Collected Fodder', 'y' => round($collected_fodder_cp_avg, 2)],
				['name' => 'Grazing', 'y' => round($grazing_cp_avg, 2)]
			]
		];
	}

	function feed_availability($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Initialize data arrays
		$months = [];
		$feed_types = [
			'Cereal Crop' => [],
			'Concentrates' => [],
			'Leguminous' => [],
			'Grazing' => [],
			'Green Forage' => [],
			'Other' => [],
		];
		$overall_availability = [];
		$avg_rainfall = [];

		$monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

		// Define field mappings for each month
		$month_fields = [
			'January' => [
				'overall' => 'field_846',
				'types' => ['field_849', 'field_852', 'field_850', 'field_851', 'field_853', 'field_854'],
				'rainfall' => 'field_707'
			],
			'February' => [
				'overall' => 'field_1275',
				'types' => ['field_1278', 'field_1284', 'field_1280', 'field_1282', 'field_1286', 'field_1289'],
				'rainfall' => 'field_875'
			],
			'March' => [
				'overall' => 'field_1291',
				'types' => ['field_1294', 'field_1300', 'field_1296', 'field_1298', 'field_1302', 'field_1305'],
				'rainfall' => 'field_876'
			],
			'April' => [
				'overall' => 'field_1307',
				'types' => ['field_1310', 'field_1316', 'field_1312', 'field_1314', 'field_1318', 'field_1321'],
				'rainfall' => 'field_877'
			],
			'May' => [
				'overall' => 'field_1323',
				'types' => ['field_1326', 'field_1332', 'field_1328', 'field_1330', 'field_1334', 'field_1337'],
				'rainfall' => 'field_878'
			],
			'June' => [
				'overall' => 'field_1339',
				'types' => ['field_1342', 'field_1348', 'field_1344', 'field_1346', 'field_1350', 'field_1353'],
				'rainfall' => 'field_879'
			],
			'July' => [
				'overall' => 'field_1355',
				'types' => ['field_1358', 'field_1364', 'field_1360', 'field_1362', 'field_1366', 'field_1369'],
				'rainfall' => 'field_880'
			],
			'August' => [
				'overall' => 'field_1371',
				'types' => ['field_1374', 'field_1380', 'field_1376', 'field_1378', 'field_1382', 'field_1385'],
				'rainfall' => 'field_881'
			],
			'September' => [
				'overall' => 'field_1387',
				'types' => ['field_1390', 'field_1396', 'field_1392', 'field_1394', 'field_1398', 'field_1401'],
				'rainfall' => 'field_882'
			],
			'October' => [
				'overall' => 'field_1403',
				'types' => ['field_1406', 'field_1412', 'field_1408', 'field_1410', 'field_1414', 'field_1417'],
				'rainfall' => 'field_883'
			],
			'November' => [
				'overall' => 'field_1419',
				'types' => ['field_1422', 'field_1428', 'field_1424', 'field_1426', 'field_1430', 'field_1433'],
				'rainfall' => 'field_884'
			],
			'December' => [
				'overall' => 'field_1435',
				'types' => ['field_1438', 'field_1444', 'field_1440', 'field_1442', 'field_1446', 'field_1449'],
				'rainfall' => 'field_885'
			]
		];

		// Count total survey4 records
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$totalRecords = $this->db->count_all_results();

		if ($totalRecords == 0) {
			return [
				'months' => [],
				'feed_types' => [
					'Cereal Crop' => [],
					'Concentrates' => [],
					'Leguminous' => [],
					'Grazing' => [],
					'Green Forage' => [],
					'Other' => []
				],
				'overall_availability' => [],
				'avg_rainfall' => []
			];
		}

		foreach ($monthsList as $mon) {
			// Query to get feed availability data
			$select_fields = [
				$month_fields[$mon]['overall'] . ' AS overall_feed_availability'
			];
			for ($i = 0; $i < 6; $i++) {
				$select_fields[] = $month_fields[$mon]['types'][$i] . ' AS feed_type_' . ($i + 1);
			}
			$this->db->select($select_fields);
			$this->db->from('survey4');
			if (!empty($countryIds)) {
				$this->db->where_in('country_id', $countryIds);
			}
			if (!empty($projectIds)) {
				$this->db->where_in('fgd_project_id', $projectIds);
			}
			if (!empty($siteIds)) {
				$this->db->where_in('fgd_site_id', $siteIds);
			}
			if (!empty($start_date)) {
				$this->db->where('datetime >=', $start_date . ' 00:00:00');
			}
			if (!empty($end_date)) {
				$this->db->where('datetime <=', $end_date . ' 23:59:59');
			}
			if ($role_id == 8) {
				$this->db->where('user_id', $user_id);
			}
			$this->db->where_in('status', [1, 2]);
			$this->db->like('field_845', $mon);
			$results = $this->db->get()->result_array();

			// Initialize sums for feed types and overall availability
			$feed_sums = array_fill(1, 6, 0);
			$overall_sum = 0;
			$record_count = count($results);

			// Calculate sums for each record
			foreach ($results as $row) {
				$overall = (float)($row['overall_feed_availability'] ?? 0);
				for ($i = 1; $i <= 6; $i++) {
					$feed_value = (float)($row['feed_type_' . $i] ?? 0);
					$feed_sums[$i] += ($feed_value / 10) * $overall;
				}
				$overall_sum += $overall;
			}

			// Calculate averages
			$feed_types_avg = [];
			for ($i = 1; $i <= 6; $i++) {
				$feed_types_avg[$i] = ($totalRecords > 0 && $record_count > 0) ? round($feed_sums[$i] / $totalRecords, 2) : 0;
			}
			$overall_avg = ($totalRecords > 0 && $record_count > 0) ? round($overall_sum / $totalRecords, 2) : 0;

			// Fetch average rainfall from survey1
			$rainfall_field = $month_fields[$mon]['rainfall'];
			$this->db->select("AVG($rainfall_field) AS avg_rainfall");
			$this->db->from('survey1 s1');
			$this->db->join('survey4 s4', 's1.fgd_id = s4.fgd_id');
			if (!empty($countryIds)) {
				$this->db->where_in('s4.country_id', $countryIds);
			}
			if (!empty($projectIds)) {
				$this->db->where_in('s4.fgd_project_id', $projectIds);
			}
			if (!empty($siteIds)) {
				$this->db->where_in('s4.fgd_site_id', $siteIds);
			}
			if (!empty($start_date)) {
				$this->db->where('s4.datetime >=', $start_date . ' 00:00:00');
			}
			if (!empty($end_date)) {
				$this->db->where('s4.datetime <=', $end_date . ' 23:59:59');
			}
			if ($role_id == 8) {
				$this->db->where('s4.user_id', $user_id);
			}
			$this->db->where_in('s4.status', [1, 2]);
			$this->db->like('s4.field_845', $mon);
			$rainfall_result = $this->db->get()->row_array();
			$avg_rainfall_value = !empty($rainfall_result['avg_rainfall']) ? round((float)$rainfall_result['avg_rainfall'], 2) : 0;

			// Store results
			$months[] = $mon;
			$feed_types['Cereal Crop'][] = $feed_types_avg[1];
			$feed_types['Concentrates'][] = $feed_types_avg[2];
			$feed_types['Leguminous'][] = $feed_types_avg[3];
			$feed_types['Grazing'][] = $feed_types_avg[4];
			$feed_types['Green Forage'][] = $feed_types_avg[5];
			$feed_types['Other'][] = $feed_types_avg[6];
			$overall_availability[] = $overall_avg;
			$avg_rainfall[] = $avg_rainfall_value;
		}

		// Format the data for Highcharts
		$chart_data = [
			'months' => $months,
			'feed_types' => $feed_types,
			'overall_availability' => $overall_availability,
			'avg_rainfall' => $avg_rainfall
		];

		return $chart_data;
	}

	public function intake_by_source($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Query to get diet source data and percentages
		$this->db->select([
			'survey4.field_773 AS diet_source', // Diet source
			'survey4.field_774 AS collected_fodder', // % of collected fodder
			'survey4.field_775 AS grazing', // % of grazing
			'survey4.field_776 AS cultivated_fodder', // % of cultivated fodder
			'survey4.field_777 AS crop_residue', // % of crop residue
			'survey4.field_778 AS purchased_feed' // % of purchased feed
		]);
		if (!empty($countryIds)) {
			$this->db->where_in('survey4.country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('survey4.fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('survey4.fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('survey4.datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('survey4.datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('survey4.user_id', $user_id);
		}
		$this->db->where_in('survey4.status', [1, 2]);
		$this->db->from('survey4');
		$results = $this->db->get()->result_array();

		// Get total record count for averaging
		$totalRecords = count($results);
		if ($totalRecords == 0) {
			return [0, 0, 0, 0, 0]; // Return zeros if no records found
		}

		// Initialize sums for each diet source
		$sums = [
			'Collected Fodder' => 0,
			'Grazing' => 0,
			'Cultivated Fodder' => 0,
			'Crop Residue' => 0,
			'Purchased Feed' => 0
		];

		// Aggregate percentages based on diet_source
		foreach ($results as $row) {
			// Split diet_source (field_773) as it may contain multiple values
			$diet_sources = explode(',', $row['diet_source']); // Assuming field_773 is a comma-separated string
			foreach ($diet_sources as $source) {
				$source = trim($source);
				switch ($source) {
					case 'Collected fodder':
						if (is_numeric($row['collected_fodder'])) {
							$sums['Collected Fodder'] += (float)$row['collected_fodder'];
						}
						break;
					case 'Grazing':
						if (is_numeric($row['grazing'])) {
							$sums['Grazing'] += (float)$row['grazing'];
						}
						break;
					case 'Cultivated fodder':
						if (is_numeric($row['cultivated_fodder'])) {
							$sums['Cultivated Fodder'] += (float)$row['cultivated_fodder'];
						}
						break;
					case 'Crop residue':
						if (is_numeric($row['crop_residue'])) {
							$sums['Crop Residue'] += (float)$row['crop_residue'];
						}
						break;
					case 'Purchased feed':
						if (is_numeric($row['purchased_feed'])) {
							$sums['Purchased Feed'] += (float)$row['purchased_feed'];
						}
						break;
				}
			}
		}

		// Calculate average percentages
		$percentages = [
			'Collected Fodder' => $sums['Collected Fodder'] > 0 ? round($sums['Collected Fodder'] / $totalRecords, 2) : 0,
			'Grazing' => $sums['Grazing'] > 0 ? round($sums['Grazing'] / $totalRecords, 2) : 0,
			'Cultivated Fodder' => $sums['Cultivated Fodder'] > 0 ? round($sums['Cultivated Fodder'] / $totalRecords, 2) : 0,
			'Crop Residue' => $sums['Crop Residue'] > 0 ? round($sums['Crop Residue'] / $totalRecords, 2) : 0,
			'Purchased Feed' => $sums['Purchased Feed'] > 0 ? round($sums['Purchased Feed'] / $totalRecords, 2) : 0
		];

		// Convert to Highcharts format
		$pie_data = [
			$percentages['Collected Fodder'],
			$percentages['Grazing'],
			$percentages['Cultivated Fodder'],
			$percentages['Crop Residue'],
			$percentages['Purchased Feed']
		];

		return $pie_data;
	}

	public function income_by_activity($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Fetch all unique income categories with their names
		$categories = $this->db->select('lc.category_id, lc.category_name')
							->from('lkp_category as lc')
							->join('lkp_income_activities as lia', 'lc.category_id = lia.category_id', 'inner')
							->where('lc.status', 1)
							->where('lia.status', 1)
							->group_by('lc.category_id')
							->get()
							->result_array();
		if (empty($categories)) {
			return []; // Return empty array if no categories found
		}

		// Define a light color palette
		$color_palette = [
			"#87ba64", // Light green
			"#6289ce", // Light blue
			"#ffca29", // Light yellow
			"#ffc75f", // Light orange
			"#66c2a5", // Soft teal
			"#ffd166", // Light golden
			"#9ad3bc", // Light mint
			"#f7b267", // Soft peach
			"#a9d08e", // Light olive
			"#f1e189", // Light lemon
			"#f4a261", // Soft coral
			"#56c1ab", // Soft aquamarine
			"#81c7d4", // Light turquoise
			"#8fd19e", // Soft green
		];

		// Initialize data calculation array for summed averages
		$dataCalculation = [];
		foreach ($categories as $category) {
			$dataCalculation[$category['category_id']] = [
				'sum_avg' => 0,
				'count' => 0,
				'category_name' => $category['category_name']
			];
		}

		// Count total survey4 records before join
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$totalRecords = $this->db->count_all_results(); // Get count of survey4 records

		if ($totalRecords == 0) {
			return []; // Return empty array if no survey4 records found
		}

		// Query to get income activity data from survey4_groupdata
		$this->db->select('groupdata.data');
		$this->db->from('survey4 as survey');
		$this->db->join('survey4_groupdata as groupdata', 'survey.data_id = groupdata.data_id');
		if (!empty($countryIds)) {
			$this->db->where_in('survey.country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('survey.fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('survey.fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('survey.user_id', $user_id);
		}
		$this->db->where('groupdata.groupfield_id', 745);
		$this->db->where_in('survey.status', [1, 2]);
		$result = $this->db->get()->result_array();

		// Process results, calculating (field_748 / 100) / totalRecords and aggregating by category
		foreach ($result as $res) {
			$jsondata = json_decode($res['data'], true);
			if (empty($jsondata['field_746']) || empty($jsondata['field_748']) || !is_numeric($jsondata['field_748'])) {
				continue;
			}

			// Handle field_746 as comma-separated or array
			$income_sources = is_array($jsondata['field_746']) 
				? $jsondata['field_746'] 
				: explode(',', $jsondata['field_746']);
			
			// Calculate average contribution: (field_748 / 100) / totalRecords
			$field_748 = (float) $jsondata['field_748'];
			$avg_contribution = ($field_748 / 100) / $totalRecords;

			// Map income activities to their categories
			foreach ($income_sources as $source_id) {
				$source_id = trim($source_id);
				// Fetch category_id for the income activity
				$category = $this->db->select('category_id')
									->where('id', $source_id)
									->where('status', 1)
									->get('lkp_income_activities')
									->row_array();
				if ($category && isset($dataCalculation[$category['category_id']])) {
					$dataCalculation[$category['category_id']]['sum_avg'] += $avg_contribution;
					$dataCalculation[$category['category_id']]['count']++;
				}
			}
		}

		// Build series data for Highcharts with summed averages
		$series_data = [];
		foreach ($categories as $index => $category) {
			$sum_avg = ($dataCalculation[$category['category_id']]['count'] > 0) 
				? round($dataCalculation[$category['category_id']]['sum_avg'], 2) 
				: 0;
			if ($sum_avg > 0) { // Only include categories with sum_avg > 0
				$series_data[] = [
					'name' => $dataCalculation[$category['category_id']]['category_name'],
					'y' => $sum_avg,
					'color' => $color_palette[$index % count($color_palette)]
				];
			}
		}

		// Sort by 'y' in descending order
		usort($series_data, function($a, $b) {
			return $b['y'] <=> $a['y'];
		});

		return $series_data;
	}
	
	// Define the generate_random_color() function if it doesn't exist
	function generate_random_color() {
		return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
	}	

	function contibution_household_income($request) {		
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');
		// Define the fields and labels
		$fields = [
			'Cash crops' => 'field_748',
			// 'Charcoal making' => 'field_962',
			'Dairying' => 'field_963',
			// 'Draft animals' => 'field_964',
			// 'Fattening - cattle' => 'field_965',
			// 'Fattening - sheep and goats' => 'field_966',
			// 'Food crops' => 'field_967',
			// 'Handicrafts' => 'field_968',
			// 'Laboring/service' => 'field_969',
			// 'Off-farm business' => 'field_970',
			// 'Pigs' => 'field_971',
			// 'Poultry (eggs)' => 'field_972',
			// 'Poultry (meat)' => 'field_973',
			// 'Priest' => 'field_974',
			// 'Remittances' => 'field_975',
			// 'Timber' => 'field_976',
			// 'Other' => 'field_977'
		];
		
		// Define a light color palette with 20 unique light colors
		$color_palette = [
			"#87ba64", // Light green
			"#6289ce", // Light blue
			"#ffca29", // Light yellow
			"#ffc75f", // Light orange
			"#66c2a5", // Soft teal
			"#ffd166", // Light golden
			"#9ad3bc", // Light mint
			"#f7b267", // Soft peach
			"#a9d08e", // Light olive
			"#f1e189", // Light lemon
			"#f4a261", // Soft coral
			"#56c1ab", // Soft aquamarine
			"#81c7d4", // Light turquoise
			"#8fd19e", // Soft green
			"#c4e17f", // Light lime green
			"#b5d6e5", // Soft sky blue
			"#e6e7a9", // Light pastel yellow
			"#b1e5d4", // Light seafoam
			"#f7d794", // Light sand
			"#c7e59a", // Soft pale green
		];

		// Initialize the total count and income by source array
		$total_count = 0;
		$income_by_source = [];
	
		// Loop through each field and calculate the count
		foreach ($fields as $label => $field) {
			$this->db->where("{$field} IS NOT NULL", NULL, FALSE);
			if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
				$this->db->where_in('country_id', $countryIds);
			}
			if (isset($projectIds) && !is_null($projectIds) && (count($projectIds) > 0)) {
				$this->db->where_in('fgd_project_id', $projectIds);
			}	
			if (isset($siteIds) && !is_null($siteIds) && (count($siteIds) > 0)) {
				$this->db->where_in('fgd_site_id', $siteIds);
			}												
			if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
				$this->db->where('datetime >=', $start_date.' 00:00:00');
			}
			if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
				$this->db->where('datetime <=', $end_date.' 23:59:59');
			}
			if ($role_id == 8) {
				$this->db->where('user_id', $user_id);
			}
			$this->db->where_in('status', [1, 2]);
			$count = $this->db->count_all_results('survey4');
			
			$income_by_source[$label] = $count;
			$total_count += $count;
		}
	
		// Prepare data for Highcharts
		$series_data = [];
		$index = 0;
		foreach ($income_by_source as $label => $count) {
			$percentage = ($total_count > 0) ? round(($count / $total_count) * 100, 2) : 0;
			$series_data[] = [
				'name' => $label,
				'y' => $percentage,  // Percentage contribution
				'color' => isset($color_palette[$index]) ? $color_palette[$index] : $color_palette[$index % count($color_palette)] // Assign a unique color from the palette
			];
			$index++;
		}
	
		// Return series data for Highcharts
		return $series_data;
	}

	public function avg_livestock_price($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Define the fields for each month
		$fields = [
			'January' => ['field_1004', 'field_805', 'field_910', 'field_911'],
			'February' => ['field_1007', 'field_913', 'field_914', 'field_915'],
			'March' => ['field_1010', 'field_917', 'field_918', 'field_919'],
			'April' => ['field_1013', 'field_921', 'field_922', 'field_923'],
			'May' => ['field_1016', 'field_925', 'field_926', 'field_927'],
			'June' => ['field_1019', 'field_929', 'field_930', 'field_931'],
			'July' => ['field_1022', 'field_933', 'field_934', 'field_935'],
			'August' => ['field_1025', 'field_937', 'field_938', 'field_939'],
			'September' => ['field_1028', 'field_941', 'field_942', 'field_943'],
			'October' => ['field_1031', 'field_945', 'field_946', 'field_947'],
			'November' => ['field_1034', 'field_949', 'field_950', 'field_951'],
			'December' => ['field_1037', 'field_953', 'field_954', 'field_955']
		];

		// Initialize the result array
		$result = [
			'Cattle' => array_fill(0, 12, 0),
			'Sheep' => array_fill(0, 12, 0),
			'Goat' => array_fill(0, 12, 0)
		];

		// Fetch currency conversion rates
		$getCurrencyData = $this->db->where('status', 1)->get('lkp_currency')->result_array();
		$currencyInfo = [];
		foreach ($getCurrencyData as $value) {
			$currencyInfo[$value['id']] = (float)($value['current_exchange_rate'] ?? 1);
		}

		// Count total survey4 records
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$totalRecords = $this->db->count_all_results();

		if ($totalRecords == 0) {
			return $result;
		}

		// Loop through each month
		$index = 0;
		foreach ($fields as $month => $fieldsArray) {
			// Build select query for currency and livestock prices
			$select_fields = [
				$fieldsArray[0] . ' AS currency_id', // Currency lookup ID
				$fieldsArray[1] . ' AS cattle_price',
				$fieldsArray[2] . ' AS sheep_price',
				$fieldsArray[3] . ' AS goat_price'
			];
			$this->db->select($select_fields);
			$this->db->from('survey4');
			if (!empty($countryIds)) {
				$this->db->where_in('country_id', $countryIds);
			}
			if (!empty($projectIds)) {
				$this->db->where_in('fgd_project_id', $projectIds);
			}
			if (!empty($siteIds)) {
				$this->db->where_in('fgd_site_id', $siteIds);
			}
			if (!empty($start_date)) {
				$this->db->where('datetime >=', $start_date . ' 00:00:00');
			}
			if (!empty($end_date)) {
				$this->db->where('datetime <=', $end_date . ' 23:59:59');
			}
			if ($role_id == 8) {
				$this->db->where('user_id', $user_id);
			}
			$this->db->where_in('status', [1, 2]);
			$resultArray = $this->db->get()->result_array();

			// Initialize sums for each livestock type
			$sums = [
				'Cattle' => 0,
				'Sheep' => 0,
				'Goat' => 0
			];
			$record_count = count($resultArray);

			// Process each record
			foreach ($resultArray as $row) {
				$currency_id = $row['currency_id'] ?? null;
				$conversion_rate = isset($currencyInfo[$currency_id]) ? $currencyInfo[$currency_id] : 1;

				// Convert each price to USD
				if (is_numeric($row['cattle_price'])) {
					$sums['Cattle'] += ((float)$row['cattle_price'] * $conversion_rate) / $totalRecords;
				}
				if (is_numeric($row['sheep_price'])) {
					$sums['Sheep'] += ((float)$row['sheep_price'] * $conversion_rate) / $totalRecords;
				}
				if (is_numeric($row['goat_price'])) {
					$sums['Goat'] += ((float)$row['goat_price'] * $conversion_rate) / $totalRecords;
				}
			}

			// Store averages in result
			$result['Cattle'][$index] = ($record_count > 0) ? round($sums['Cattle'], 2) : 0;
			$result['Sheep'][$index] = ($record_count > 0) ? round($sums['Sheep'], 2) : 0;
			$result['Goat'][$index] = ($record_count > 0) ? round($sums['Goat'], 2) : 0;

			$index++;
		}

		return $result;
	}
	
	public function avg_daily_milk_price($request) {
		$baseurl = base_url();
		if (empty($this->session->userdata('login_id'))) {
			redirect($baseurl);
		}

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		// Input parameters
		$worldRegionIds = $request['worldRegionIds'] ?? null;
		$majorRegionIds = $request['majorRegionIds'] ?? null;
		$countryIds = $request['countryIds'] ?? null;
		$stateIds = $request['stateIds'] ?? null;
		$districtIds = $request['districtIds'] ?? null;
		$projectIds = $request['projectIds'] ?? null;
		$siteIds = $request['siteIds'] ?? null;
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		// Remove duplicates from input arrays to optimize query
		$countryIds = !empty($countryIds) ? array_unique($countryIds) : null;
		$projectIds = !empty($projectIds) ? array_unique($projectIds) : null;
		$siteIds = !empty($siteIds) ? array_unique($siteIds) : null;

		// Define the fields for each month
		$fields = [
			'January' => ['field_1037', 'field_808', 'field_810'],
			'February' => ['field_1037', 'field_811', 'field_813'],
			'March' => ['field_1037', 'field_814', 'field_816'],
			'April' => ['field_1037', 'field_817', 'field_819'],
			'May' => ['field_1037', 'field_820', 'field_822'],
			'June' => ['field_1037', 'field_823', 'field_825'],
			'July' => ['field_1037', 'field_826', 'field_828'],
			'August' => ['field_1037', 'field_829', 'field_831'],
			'September' => ['field_1037', 'field_832', 'field_834'],
			'October' => ['field_1037', 'field_835', 'field_837'],
			'November' => ['field_1037', 'field_838', 'field_840'],
			'December' => ['field_1037', 'field_841', 'field_843']
		];

		// Initialize the result array
		$result = [
			'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			'milk' => array_fill(0, 12, 0),
			'price' => array_fill(0, 12, 0),
		];

		// Get currency data
		$getCurrencyData = $this->db->where('status', 1)->get('lkp_currency')->result_array();
		$currencyInfo = [];
		foreach ($getCurrencyData as $value) {
			$currencyInfo[$value['id']] = (float)($value['current_exchange_rate'] ?? 1);
		}

		// Count total survey4 records
		$this->db->from('survey4');
		if (!empty($countryIds)) {
			$this->db->where_in('country_id', $countryIds);
		}
		if (!empty($projectIds)) {
			$this->db->where_in('fgd_project_id', $projectIds);
		}
		if (!empty($siteIds)) {
			$this->db->where_in('fgd_site_id', $siteIds);
		}
		if (!empty($start_date)) {
			$this->db->where('datetime >=', $start_date . ' 00:00:00');
		}
		if (!empty($end_date)) {
			$this->db->where('datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where_in('status', [1, 2]);
		$totalRecords = $this->db->count_all_results();

		if ($totalRecords == 0) {
			return $result;
		}

		// Loop through each month
		$index = 0;
		foreach ($fields as $month => $fieldsArray) {
			// Build select query for currency, milk yield, and price
			$select_fields = [
				$fieldsArray[0] . ' AS currency_id', // Currency lookup ID
				$fieldsArray[1] . ' AS milk_yield',
				$fieldsArray[2] . ' AS milk_price'
			];
			$this->db->select($select_fields);
			$this->db->from('survey4');
			if (!empty($countryIds)) {
				$this->db->where_in('country_id', $countryIds);
			}
			if (!empty($projectIds)) {
				$this->db->where_in('fgd_project_id', $projectIds);
			}
			if (!empty($siteIds)) {
				$this->db->where_in('fgd_site_id', $siteIds);
			}
			if (!empty($start_date)) {
				$this->db->where('datetime >=', $start_date . ' 00:00:00');
			}
			if (!empty($end_date)) {
				$this->db->where('datetime <=', $end_date . ' 23:59:59');
			}
			if ($role_id == 8) {
				$this->db->where('user_id', $user_id);
			}
			$this->db->where_in('status', [1, 2]);
			$resultArray = $this->db->get()->result_array();

			// Initialize sums for milk yield and price
			$sums = [
				'milk' => 0,
				'price' => 0
			];
			$record_count = count($resultArray);

			// Process each record
			foreach ($resultArray as $row) {
				$currency_id = $row['currency_id'] ?? null;
				$conversion_rate = isset($currencyInfo[$currency_id]) ? $currencyInfo[$currency_id] : 1;

				// Convert milk yield and price to USD
				if (is_numeric($row['milk_yield'])) {
					$sums['milk'] += ((float)$row['milk_yield']) / $totalRecords;
				}
				if (is_numeric($row['milk_price'])) {
					$sums['price'] += ((float)$row['milk_price'] * $conversion_rate) / $totalRecords;
				}
			}

			// Store averages in result
			$result['milk'][$index] = ($record_count > 0) ? round($sums['milk'], 2) : 0;
			$result['price'][$index] = ($record_count > 0) ? round($sums['price'], 2) : 0;

			$index++;
		}

		return $result;
	}
	
	function acresToHectares($acres) {
		return $acres * 0.404686;
	}
	
	public function mappoints()
	{
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// 	echo json_encode(array(
			// 		'status' => 0,
			// 		'msg' => 'Session Expired! Please login again to continue.'
			// 	));
			// 	exit();
			// } else {
			// 	redirect($baseurl);
			// }
			redirect($baseurl);
		}
		
		$survey_id = 432;
		$selected_season = 'kharif-2023';
		$season = $this->uri->segment(3);
		if(isset($season) && strlen($season) > 0) {
			switch ($season) {
				case 'rabi':
					$selected_season = 'rabi';
					$start_date = "2022-03-01";
					$end_date = "2022-07-31";
				break;

				case 'kharif':
					$selected_season = 'kharif';
					$start_date = "2022-08-01";
					$end_date = "2023-07-24";
				break;

				case 'kharif-2023':
					$selected_season = 'kharif-2023';
					$start_date = "2023-07-25";
				break;
				
				default:
					$selected_season = 'kharif-2023';
					$start_date = "2023-07-25";
				break;
			}
		} else {
			$selected_season = 'kharif-2023';
			$start_date = "2023-07-25";
		}

		ini_set('memory_limit', '-1');

		$this->load->model('Reports_model');
		$result = $this->Reports_model->survey_details($survey_id);

		// Get survey locations as group
		$this->db->select("data_id, GROUP_CONCAT(
			DISTINCT CONCAT(file_lat,',',file_long)
			SEPARATOR ';'
		) AS lat_lng")->from('ic_data_file');
		$this->db->where('form_id', $survey_id)->where('status', 1);
		if(isset($start_date) && !is_null($start_date) && (strlen($start_date) > 0)) {
			$this->db->where('created_date >=', $start_date.' 00:00:00');
		}
		if(isset($end_date) && !is_null($end_date) && (strlen($end_date) > 0)) {
			$this->db->where('created_date <=', $end_date.' 23:59:59');
		}
		$this->db->group_by('data_id');
		$location = $this->db->get()->result_array();
		foreach ($location as $key => $loc) {
			$location[$key]['survey_id'] = $survey_id;
			$location[$key]['locations'] = explode(';', $loc['lat_lng']);
		}
		$result['survey_locations'] = $location;

		

        $result['season'] = $selected_season;
        $this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('reports/view_map', $result);
		$this->load->view('footer');
	}

	public function registration_file_details() {
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		if(!$this->input->post('data_id') || $this->input->post('data_id') == ''
		|| !$this->input->post('field_id') || $this->input->post('field_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Unable to get data. Please refrersh the page and try again.'
			));
			exit();
		}
		
		$survey_id = 432;
		$data_id = $this->input->post('data_id');
		$field_id = $this->input->post('field_id');
		
		$this->db->select('file_name, file_lat, file_long');
		$this->db->where('field_id', $field_id);
		$this->db->where('data_id', $data_id)->where('form_id', $survey_id);
		$this->db->where('file_type', 'image')->where('status', 1);
		$images = $this->db->get('ic_data_file')->result_array();

		if(count($images) > 0) {
			echo json_encode(array(
				'status' => 1,
				'data' => $images[0]
			));
		} else {
			echo json_encode(array(
				'status' => 1,
				'data' => array()
			));
		}
		exit();
	}
	public function registration_ajax()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$survey_id = 432;
		ini_set('memory_limit', '-1');

		// Get Survey Data
		$this->db->select('survey.*, block.block_name, dist.district_name, vill.village_name, tu.first_name, tu.last_name');
		$this->db->from('survey'.$survey_id.' AS survey');
		$this->db->join('lkp_district AS dist', 'dist.district_id = survey.field_10772');
		$this->db->join('lkp_block AS block', 'block.block_id = survey.field_10773');
		$this->db->join('lkp_village AS vill', 'vill.village_id = survey.field_10765');
		$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
		if(isset($_POST['start_date']) && strlen($_POST['start_date']) > 0) {
			$this->db->where('DATE(survey.datetime) >=', $_POST['start_date'].' 00:00:00');
		} else if(isset($_POST['end_date']) && strlen($_POST['end_date']) > 0) {
			$this->db->where('DATE(survey.datetime) <=', $_POST['end_date'].' 23:59:59');
		}
		$this->db->where('survey.status', 1);
		$survey_data = $this->db->order_by('survey.id', 'DESC')->get()->result_array();
		foreach ($survey_data as $key => $value) {
			// Replace district, block and village id with name
			$survey_data[$key]['field_10772'] = $value['district_name'];
			$survey_data[$key]['field_10773'] = $value['block_name'];
			$survey_data[$key]['field_10765'] = $value['village_name'];

			// Convert Upload Time to IST
			$date = new DateTime($survey_data[$key]['datetime'], new DateTimeZone('UTC'));
			$date->setTimezone(new DateTimeZone('Asia/Kolkata'));
			$survey_data[$key]['datetime'] = $date->format('Y-m-d H:i:s');

			$this->db->select('field_id, file_name, file_lat, file_long');
			$this->db->where('data_id', $value['data_id'])->where('status', 1);
			$this->db->where('form_id', $survey_id)->where('file_type', 'image');
			// $survey_data[$key]['images'] = $this->db->get('ic_data_file')->result_array();
			$images = $this->db->get('ic_data_file')->result_array();
			// Assign Images to field Ids
			foreach ($images as $ikey => $img) {
				$survey_data[$key]['field_'.$img['field_id']] = $img['file_name'];
				$survey_data[$key]['field_'.$img['field_id'].'_lat'] = $img['file_lat'];
				$survey_data[$key]['field_'.$img['field_id'].'_lng'] = $img['file_long'];
			}
		}


		// Get survey locations as group
		$this->db->select("data_id, GROUP_CONCAT(
			DISTINCT CONCAT(file_lat,',',file_long)
			SEPARATOR ';'
		) AS lat_lng")->from('ic_data_file');
		$this->db->where('form_id', $survey_id)->where('status', 1);
		if(isset($_POST['start_date']) && strlen($_POST['start_date']) > 0) {
			$this->db->where('DATE(created_date) >=', $_POST['start_date'].' 00:00:00');
		} else if(isset($_POST['end_date']) && strlen($_POST['end_date']) > 0) {
			$this->db->where('DATE(created_date) <=', $_POST['end_date'].' 23:59:59');
		}
		$this->db->group_by('data_id');
		$location = $this->db->get()->result_array();
		foreach ($location as $key => $loc) {
			$location[$key]['survey_id'] = $survey_id;
			$location[$key]['locations'] = explode(';', $loc['lat_lng']);
		}

		echo json_encode(array(
			'status' => 1,
			'survey_data' => $survey_data,
			'survey_locations' => $location
		));
		exit();
	}

	public function plot()
	{
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}
		
		$survey_id = 2;

		$this->load->model('Reports_model');
		$result = $this->Reports_model->survey_details($survey_id);
		$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);
		$this->load->model('Helper_model');
		// $result['divisions'] = $this->Helper_model->all_divisions();
		// echo '<pre>';print_r($result);exit;
		$lookup_tables = array(
			'lkp_circle', 'lkp_division', 'lkp_gender', 'lkp_title', 'lkp_village'
		);
		$result['survey_data'] = $this->Reports_model->registration_data2($survey_id);
		
		// echo '<pre>';print_r($result['survey_data']);exit;
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('reports/view_registration2', $result);
		$this->load->view('footer');
	}

	public function agreement()
	{
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}
		
		$survey_id = 3;

		$this->load->model('Reports_model');
		$result = $this->Reports_model->survey_details($survey_id);
		$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);
		$this->load->model('Helper_model');
		// $result['divisions'] = $this->Helper_model->all_divisions();
		// echo '<pre>';print_r($result);exit;
		$lookup_tables = array(
			'lkp_circle', 'lkp_division', 'lkp_gender', 'lkp_title', 'lkp_village'
		);
		$result['survey_data'] = $this->Reports_model->registration_data3($survey_id);
		
		// echo '<pre>';print_r($result['survey_data']);exit;
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('reports/view_registration3', $result);
		$this->load->view('footer');
	}

	public function get_map_locations()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$survey_id = $this->uri->segment(3);
		// $this->load->model('Reports_model');
		// $result['survey_locations'] = $this->Reports_model->survey_location($survey_id);

		// Get survey locations as group
		$this->db->select("data_id, GROUP_CONCAT(
			DISTINCT CONCAT(file_lat,',',file_long)
			SEPARATOR ';'
		) AS lat_lng")->from('ic_data_file');
        $this->db->where('form_id', $survey_id)->where('status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(created_date) >=', $_POST['start_date']);
            $this->db->where('DATE(created_date) <=', $_POST['end_date']);
        }
        $this->db->group_by('data_id');
        $location = $this->db->order_by('id', 'DESC')->get()->result_array();

        foreach ($location as $key => $loc) {
        	$location[$key]['locations'] = explode(';', $loc['lat_lng']);
        }

		$result['status'] = 1;
		echo json_encode($result);
		exit();
	}

	public function survey(){
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}
			
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->all_assigned_project();

		$this->load->model('Survey_model');
		$this->load->model('Reports_model');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$pid = $this->input->post('project');
		} else {
			$pid = $projects[0]['proj_id'];
		}
		$all_surveys = $this->Reports_model->all_surveys($pid);
		// echo print_r($all_surveys);
		// exit();
		$ids = array();
		foreach ($all_surveys as $key => $value) {
			array_push($ids, $value['id']);
		}
		$moderate = $this->Survey_model->all_moderation_surveys($pid);
		foreach ($moderate as $key => $value) {
			if($value['type'] == 'Survey') {
				if(!in_array($value['id'], $ids)) array_push($all_surveys, $value);
			}
		}

		$result = array('all_surveys' => $all_surveys, 'projects' => $projects);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			echo json_encode($result);
			exit();
		}

		$header_result = array('main_menu' => $main_menu);
		$result = $this->security->xss_clean($result);
		$this->load->view('header', $header_result);
		$this->load->view('reports/survey', $result);
		$this->load->view('footer');
	}

	public function view_surveydata(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);

			if($survey_id == '' || $survey_id == NULL){
				show_404();
			}

			$this->load->model('Reports_model');
			$result = $this->Reports_model->survey_details($survey_id);

			$result['survey_data'] = $this->Reports_model->survey_data($survey_id);
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['user_list'] = $this->Reports_model->user_list();
			$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);
			$result['crop_types'] = $this->Reports_model->lkp_crop_types();
			$result['crops'] = $this->Reports_model->lkp_crops();
			$result['crop_intervention'] = $this->Reports_model->lkp_crop_intervention();
			$result['crop_inputname'] = $this->Reports_model->lkp_crop_inputname();
			$result['crop_varieties'] = $this->Reports_model->lkp_crop_varieties();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('reports/view_surveydata', $result);
			$this->load->view('footer');
		}
	}

	public function view_activitydata()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);
			$village_id = $this->uri->segment(4);

			if($survey_id == '' || $survey_id == NULL || $village_id == '' || $village_id == NULL){
				show_404();
			}

			$this->load->model('Reports_model');
			$result = $this->Reports_model->survey_details($survey_id);

			$result['survey_data'] = $this->Reports_model->activity_data($survey_id, $village_id);
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['user_list'] = $this->Reports_model->user_list();
			$result['survey_locations'] = $this->Reports_model->activity_location($survey_id, $village_id);
			$result['crop_types'] = $this->Reports_model->lkp_crop_types();
			$result['crops'] = $this->Reports_model->lkp_crops();
			$result['crop_intervention'] = $this->Reports_model->lkp_crop_intervention();
			$result['crop_inputname'] = $this->Reports_model->lkp_crop_inputname();
			$result['crop_varieties'] = $this->Reports_model->lkp_crop_varieties();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('reports/view_activitydata', $result);
			$this->load->view('footer');
		}
	}

	public function view_surveydata_filter()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$survey_id = $this->uri->segment(3);

			if($survey_id == '' || $survey_id == NULL){
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Some error occured! Please refresh and try again.'
				));
				exit();
			}

			$this->load->model('Reports_model');
			$result = $this->Reports_model->survey_details($survey_id);

			$result['survey_data'] = $this->Reports_model->survey_data($survey_id);

			$result['partners_list'] = $this->Reports_model->partners_list();
			$result['age_list'] = $this->Reports_model->age_list();
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);

			$result['status'] = 1;
			echo json_encode($result);
			exit();
		}
	}

	public function get_survey_locations()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$survey_id = $this->uri->segment(3);
		$this->load->model('Reports_model');
		$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);

		$result['status'] = 1;
		echo json_encode($result);
		exit();
	}

	public function get_activity_locations()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$survey_id = $this->uri->segment(3);
		$village_id = $this->uri->segment(3);

		$this->load->model('Reports_model');
		$result['survey_locations'] = $this->Reports_model->activity_location($survey_id, $village_id);

		$result['status'] = 1;
		echo json_encode($result);
		exit();
	}

	public function beneficiary(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Reports_model');
			$all_beneficiary = $this->Reports_model->all_beneficiary();

			$result = array('all_beneficiary' => $all_beneficiary);

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('reports/beneficiary', $result);
			$this->load->view('footer');
		}
	}
	
	public function view_beneficiarydata(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);

			if($survey_id == '' || $survey_id == NULL){
				show_404();
			}

			$this->load->model('Reports_model');
			$result = $this->Reports_model->survey_details($survey_id);

			$result['survey_data'] = $this->Reports_model->survey_data($survey_id);

			$result['partners_list'] = $this->Reports_model->partners_list();
			$result['age_list'] = $this->Reports_model->age_list();
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list_with_survey_data($survey_id);
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['user_list'] = $this->Reports_model->user_list();
			$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);
			$result['date_wise_data'] = $this->Reports_model->date_wise_data($survey_id);

			$subsidies_list_graph = array();
			$subsidieslist = array();
			$this->db->select('value')->where('form_id', 1)->where('field_id', 1752)->where('status', 1);
			$subsidies_list = $this->db->get('form_field_multiple')->result_array();			
			foreach ($subsidies_list as $key => $value) {
				$subsidies_list_graph[$key]['name'] = $value['value'];
				$subsidies_list_graph[$key]['count'] = 0;

				$subsidieslist[$value['value']] = array();
			}

			$blocklist = array();

			$this->db->select('form_data');
			$this->db->where('form_id', 1);
			$this->db->where('data_status', 1);
			$formdata = $this->db->get('ic_form_data')->result_array();

			$agriculture = 0;
			$agroprocessingindustry = 0;
			$animalhusbandry = 0;
			$nonfarmlabor = 0;
			$fishing = 0;
			$plantationcrops = 0;
			$pension = 0;
			$service = 0;
			$business = 0;
			$handicraft = 0;
			foreach ($formdata as $key => $value) {
				$jsondata = json_decode($value['form_data'], true);

				if(isset($jsondata['field_1682'])){
					$agriculture = $agriculture + $jsondata['field_1682'];
				}
				if(isset($jsondata['field_1683'])){
					$agroprocessingindustry = $agroprocessingindustry + $jsondata['field_1683'];
				}
				if(isset($jsondata['field_1684'])){
					$animalhusbandry = $animalhusbandry + $jsondata['field_1684'];
				}
				if(isset($jsondata['field_1685'])){
					$nonfarmlabor = $nonfarmlabor + $jsondata['field_1685'];
				}
				if(isset($jsondata['field_1686'])){
					$fishing = $fishing + $jsondata['field_1686'];
				}
				if(isset($jsondata['field_1687'])){
					$plantationcrops = $plantationcrops + $jsondata['field_1687'];
				}
				if(isset($jsondata['field_1688'])){
					$pension = $pension + $jsondata['field_1688'];
				}
				if(isset($jsondata['field_1689'])){
					$service = $service + $jsondata['field_1689'];
				}
				if(isset($jsondata['field_1690'])){
					$business = $business + $jsondata['field_1690'];
				}
				if(isset($jsondata['field_1691'])){
					$handicraft = $handicraft + $jsondata['field_1691'];
				}

				if(isset($jsondata['field_1752'])){
					$subsidies_data = explode("&#44;", $jsondata['field_1752']);

					foreach ($subsidies_data as $sub) {
						array_push($subsidieslist[$sub], 1);
					}
				}

				if(isset($jsondata['field_1668'])){
					if(!in_array($jsondata['field_1668'], $blocklist)){
						array_push($blocklist, $jsondata['field_1668']);
					}
				}			
			}

			$blocklist_data = array();

			foreach ($blocklist as $key => $value) {
				$blocklist_data[$value] = array();
			}

			foreach ($formdata as $key => $value) {
				$jsondata = json_decode($value['form_data'], true);

				if (isset($jsondata['field_1668'])) {
					array_push($blocklist_data[$jsondata['field_1668']], 1);
				}
			}

			$blocklist_graphdata = array();
			$i = 0;
			foreach ($blocklist_data as $key => $value) {
				$blockname = $this->db->select('block_name')->where('block_id', $key)->get('lkp_block')->row_array();

				$blocklist_graphdata[$i]['name'] = $blockname['block_name'];
				$blocklist_graphdata[$i]['count'] = count($value);

				$i++;
			}

			$occupation_list_graph = array();
			array_push($occupation_list_graph, array('name' => 'Agriculture', 'count' => $agriculture));
			array_push($occupation_list_graph, array('name' => 'Agro-processing industry', 'count' => $agroprocessingindustry));
			array_push($occupation_list_graph, array('name' => 'Animal husbandry', 'count' => $animalhusbandry));
			array_push($occupation_list_graph, array('name' => 'Non-farm labor', 'count' => $nonfarmlabor));
			array_push($occupation_list_graph, array('name' => 'Fishing', 'count' => $fishing));
			array_push($occupation_list_graph, array('name' => 'Plantation crops', 'count' => $plantationcrops));
			array_push($occupation_list_graph, array('name' => 'Pension', 'count' => $pension));
			array_push($occupation_list_graph, array('name' => 'Service', 'count' => $service));
			array_push($occupation_list_graph, array('name' => 'Business', 'count' => $business));
			array_push($occupation_list_graph, array('name' => 'Handicraft', 'count' => $handicraft));

			foreach ($subsidies_list_graph as $key => $val) {
				$subsidies_list_graph[$key]['count'] = count($subsidieslist[$val['name']]);
			}

			$result['occupation_list_graph'] = $occupation_list_graph;
			$result['subsidies_list_graph'] = $subsidies_list_graph;
			$result['blocklist_graphdata'] = $blocklist_graphdata;

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('reports/view_beneficiarydata', $result);
			$this->load->view('footer');
		}
	}

	public function district_data()
	{
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}

		$district_id = $this->uri->segment(3);
		if($district_id == '' || $district_id == NULL){
			show_404();
		}

		$survey_id = $this->uri->segment(4);
		if($survey_id == '' || $survey_id == NULL){
			show_404();
		}

		$this->load->model('Reports_model');
		$result = $this->Reports_model->survey_details($survey_id);
		
		$districts = $this->Reports_model->district_list($district_id);
		$result['district'] = $districts[0];
		
		$result['survey_data'] = $this->Reports_model->survey_data($survey_id, $district_id);
		$result['partners_list'] = $this->Reports_model->partners_list();
		$result['age_list'] = $this->Reports_model->age_list();
		$result['state_list'] = $this->Reports_model->state_list();
		$result['district_list'] = $this->Reports_model->district_list();
		$result['block_list'] = $this->Reports_model->block_list();
		$result['village_list'] = $this->Reports_model->village_list_with_survey_data($survey_id, $district_id);
		$result['user_list'] = $this->Reports_model->user_list($district_id);
		$result['survey_locations'] = $this->Reports_model->survey_location($survey_id, $district_id);

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$result['status'] = 1;
			echo json_encode($result);
			exit();
		}

		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$header_result = array('main_menu' => $main_menu);
		$result = $this->security->xss_clean($result);
		$this->load->view('header', $header_result);
		$this->load->view('reports/view_district_data', $result);
		$this->load->view('footer');
	}

	public function edit_beneficiarydata(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);

			if($survey_id == '' || $survey_id == NULL){
				show_404();
			}

			$this->load->model('Reports_model');
			$result = $this->Reports_model->survey_details($survey_id);

			$result['survey_data'] = $this->Reports_model->survey_data($survey_id);

			$result['partners_list'] = $this->Reports_model->partners_list();
			// $result['centre_list'] = $this->Reports_model->centre_list();
			// $result['batch_list'] = $this->Reports_model->batch_list();
			// $result['trainee_list'] = $this->Reports_model->trainee_list();
			$result['age_list'] = $this->Reports_model->age_list();
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['user_list'] = $this->Reports_model->user_list();
			$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('reports/edit_beneficiarydata', $result);
			$this->load->view('footer');
		}
	}

	public function verify_beneficiarydata(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);

			if($survey_id == '' || $survey_id == NULL){
				show_404();
			}

			$this->load->model('Reports_model');
			$result = $this->Reports_model->survey_details($survey_id);

			$result['survey_data'] = $this->Reports_model->survey_data($survey_id);

			$result['partners_list'] = $this->Reports_model->partners_list();
			// $result['centre_list'] = $this->Reports_model->centre_list();
			// $result['batch_list'] = $this->Reports_model->batch_list();
			// $result['trainee_list'] = $this->Reports_model->trainee_list();
			$result['age_list'] = $this->Reports_model->age_list();
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['user_list'] = $this->Reports_model->user_list();
			$result['survey_locations'] = $this->Reports_model->survey_location($survey_id);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('reports/verify_beneficiarydata', $result);
			$this->load->view('footer');
		}
	}

	public function groupdata_info(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);
			$data_id = $this->uri->segment(4);

			$data = array(
				'survey_id' => $survey_id,
				'data_id' => $data_id
			);

			$this->load->model('Reports_model');
			$check_record = $this->Reports_model->check_record($data);

			if($check_record == 0){
				show_404();
			}

			$group_info = $this->Reports_model->group_info($data);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$result = array('group_info' => $group_info);

			$result['crop_types'] = $this->Reports_model->lkp_crop_types();
			$result['crops'] = $this->Reports_model->lkp_crops();
			$result['crop_intervention'] = $this->Reports_model->lkp_crop_intervention();
			$result['crop_inputname'] = $this->Reports_model->lkp_crop_inputname();
			$result['crop_varieties'] = $this->Reports_model->lkp_crop_varieties();

			$this->load->view('header', $header_result);
			$this->load->view('reports/groupdata_info', $result);
			$this->load->view('footer');
		}
	}

	public function edit_groupdata_info(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);
			$data_id = $this->uri->segment(4);
			$groupfield_id = $this->uri->segment(5);

			$data = array(
				'survey_id' => $survey_id,
				'data_id' => $data_id,
				'groupfield_id' => $groupfield_id,
			);

			$this->load->model('Reports_model');
			$check_record = $this->Reports_model->check_record($data);

			if($check_record == 0){
				show_404();
			}

			$group_info = $this->Reports_model->group_info($data);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$result = array('group_info' => $group_info);
			$result['lkp_fodder_type'] = $this->db->where('status', 1)->get('lkp_fodder_type')->result_array();
			$result['lkp_feed_type'] = $this->db->where('status', 1)->get('lkp_feed_type')->result_array();
			$result['lkp_livestock_sales'] = $this->db->where('status', 1)->get('lkp_livestock_sales')->result_array();
			$result['lkp_gender'] = $this->db->where('status', 1)->get('lkp_gender')->result_array();
			$result['lkp_crop'] = $this->db->where('status', 1)->get('lkp_crop')->result_array();
			$result['lkp_income_activities'] = $this->db->where('status', 1)->get('lkp_income_activities')->result_array();
			$result['lkp_animal_type'] = $this->db->where('status', 1)->get('lkp_animal_type')->result_array();
			$result['lkp_livestock'] = $this->db->where('status', 1)->get('lkp_livestock')->result_array();
			$result['lkp_units'] = $this->db->where('status', 1)->get('lkp_units')->result_array();


			$this->load->view('header', $header_result);
			$this->load->view('reports/edit_groupdata_info', $result);
			$this->load->view('footer');
		}
	}

	public function get_details_for_edit()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if(!$this->input->post('id')
		|| !$this->input->post('field_id')) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}

		$this->load->model('Reports_model');
		$result['survey_data'] = $this->Reports_model->survey_data_details($this->input->post('id'));
		$result['field_details'] = $this->Reports_model->field_details($this->input->post('field_id'));

		$result['status'] = 1;
		echo json_encode($result);
		exit();
	}

	public function get_group_details_for_edit()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if(!$this->input->post('group_id')
		|| !$this->input->post('field_id')) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}

		$this->load->model('Reports_model');
		$result['group_data'] = $this->Reports_model->group_info_details($this->input->post('group_id'));
		$result['field_details'] = $this->Reports_model->field_details($this->input->post('field_id'));

		$result['status'] = 1;
		echo json_encode($result);
		exit();
	}

	public function edit_beneficiary()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if(!$this->input->post('id')
		|| !$this->input->post('field')) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}

		$this->load->model('Reports_model');
		$survey_data = $this->Reports_model->survey_data_details($this->input->post('id'));
		$field_details = $this->Reports_model->field_details($this->input->post('field'));
		if(!$survey_data || !$field_details) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}

		$newKey = 'field_'.$field_details['field_id'];
		$newValue = $this->input->post($newKey);
		if(is_array($newValue)) {
			$newValue = implode('&#44;', $newValue);
		}

		//Convert string data to array
		$form_data = (array)json_decode($survey_data['form_data']);
		$log = array();
		
		//If newValue is not empty Modify form_data accordingly
		if(strlen($newValue) > 0) {
			$form_data[$newKey] = $newValue;
			$log['new_value'] = json_encode(array($newKey => $newValue));
		}
		//Check if element exist in form_data array
		if(in_array(array($newKey => $newValue), $form_data)) {
			//If newValue is empty
			if(strlen($newValue) == 0) {
				unset($form_data[$newKey]);
				$log['new_value'] = json_encode($form_data);
			}
		}

		date_default_timezone_set('UTC');
		$currentDateTime = date('Y-m-d H:i:s');

		//Check if log has new value then prepare complete log
		if(isset($log['new_value'])) {
			$log['editedby'] = $this->session->userdata('login_id');
			$log['editedfor'] = $survey_data['user_id'];
			$log['table_name'] = 'ic_form_data';
			$log['table_row_id'] = $survey_data['id'];
			$log['table_field_name'] = 'form_data';
			$log['old_value'] = $survey_data['form_data'];
			$log['edited_reason'] = $this->input->post('reason');
			$log['updated_date'] = $currentDateTime;
			$log['ip_address'] = $this->input->ip_address();
			$log['log_status'] = 1;

			//Update ic_form_data
			$this->db->where('id', $survey_data['id'])->update('ic_form_data', array(
				'form_data' => json_encode($form_data)
			));

			//Insert log
			$this->db->insert('ic_log', $log);
		}

		$result['status'] = 1;
		$result['msg'] = 'Data updated successfully.';
		$result['field_value'] = strlen($newValue) == 0 ? 'N/A' : $newValue;
		echo json_encode($result);
		exit();
	}

	public function edit_groupdata()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if(!$this->input->post('group')
		|| !$this->input->post('field')) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}

		$this->load->model('Reports_model');
		$group_data = $this->Reports_model->group_info_details($this->input->post('group'));
		$field_details = $this->Reports_model->field_details($this->input->post('field'));
		if(!$group_data || !$field_details) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}

		$newKey = 'field_'.$field_details['field_id'];
		$newValue = $this->input->post($newKey);
		if(is_array($newValue)) {
			$newValue = implode(',', $newValue);
		}

		//Convert string data to array
		$form_data = (array)json_decode($group_data['formgroup_data']);
		$log = array();

		//Cehck if newValue is empty or not
		//Modify form_data accordingly
		if(strlen($newValue) > 0) {
			$form_data[$newKey] = $newValue;
			$log['new_value'] = json_encode(array($newKey => $newValue));
		} else if(strlen($newValue) == 0) {
			$form_data[$newKey] = NULL;
			$log['new_value'] = json_encode(array($newKey => NULL));
		}

		date_default_timezone_set('UTC');
		$currentDateTime = date('Y-m-d H:i:s');

		//Check if log has new value then prepare complete log
		if(isset($log['new_value'])) {
			$log['editedby'] = $this->session->userdata('login_id');
			$log['editedfor'] = $group_data['user_id'];
			$log['table_name'] = 'ic_form_group_data';
			$log['table_row_id'] = $group_data['group_id'];
			$log['table_field_name'] = 'formgroup_data';
			$log['old_value'] = $group_data['formgroup_data'];
			$log['edited_reason'] = $this->input->post('reason');
			$log['updated_date'] = $currentDateTime;
			$log['ip_address'] = $this->input->ip_address();
			$log['log_status'] = 1;

			//Update ic_form_group_data
			$this->db->where('group_id', $group_data['group_id'])->update('ic_form_group_data', array(
				'formgroup_data' => json_encode($form_data)
			));

			//Insert log
			$this->db->insert('ic_log', $log);
		}

		$result['status'] = 1;
		$result['msg'] = 'Data updated successfully.';
		$result['field_value'] = strlen($newValue) == 0 ? 'N/A' : $newValue;
		echo json_encode($result);
		exit();
	}

	public function verify_beneficiary()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if($this->session->userdata('role') != 3 && $this->session->userdata('role') != 4) {
			$result['status'] = 0;
			$result['msg'] = 'You are not authorized to use this feature.';
			echo json_encode($result);
			exit();
		}

		$status = $this->input->post('status');
		if(!isset($status) || strlen($status) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again 1.';
			echo json_encode($result);
			exit();
		}

		$ids = $this->input->post('check');
		if(!$ids || count($ids) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again 2.';
			echo json_encode($result);
			exit();
		}

		foreach ($ids as $id) {
			date_default_timezone_set('UTC');
			$currentDateTime = date('Y-m-d H:i:s');

			if($this->session->userdata('role') == 3) {
				$verification = array(
					'pm_verified' => $this->input->post('status'),
					'pm_verified_id' => $this->session->userdata('login_id'),
					'pm_verified_date' => $currentDateTime
				);
			} else if($this->session->userdata('role') == 4) {
				$verification = array(
					'am_verified' => $this->input->post('status'),
					'am_verified_id' => $this->session->userdata('login_id'),
					'am_verified_date' => $currentDateTime
				);
			}
			
			//Prepare verification data
			$this->db->where('id', $id)->update('ic_form_data', $verification);
		}

		$result['status'] = 1;
		$result['msg'] = 'Data verified successfully.';
		echo json_encode($result);
		exit();
	}

	public function delete_formdata()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if($this->session->userdata('role') != 1) {
			$result['status'] = 0;
			$result['msg'] = 'You are not authorized to use this feature.';
			echo json_encode($result);
			exit();
		}

		$status = $this->input->post('status');
		if(!isset($status) || strlen($status) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again 1.';
			echo json_encode($result);
			exit();
		}

		$ids = $this->input->post('check');
		if(!$ids || count($ids) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again 2.';
			echo json_encode($result);
			exit();
		}

		foreach ($ids as $id) {
			//Get data_id
			$data = $this->db->where('id', $id)->get('ic_form_data')->row_array();
			if($status == 'delete') {
				//Delete from ic_form_data table
				$this->db->where('id', $id)->update('ic_form_data', array('data_status' => 0));
				//Delete from ic_data_file table
				$this->db->where('data_id', $data['data_id'])->update('ic_data_file', array('status' => 0));
				//Delete from ic_data_location table
				$this->db->where('data_id', $data['data_id'])->update('ic_data_location', array('status' => 0));
				//Delete from ic_form_group_data table
				$this->db->where('data_id', $data['data_id'])->update('ic_form_group_data', array('data_status' => 0));
			} else if($status == 'erase') {
				//Delete from ic_form_data table
				$this->db->where('id', $id)->delete('ic_form_data');
				//Delete from ic_data_file table
				$this->db->where('data_id', $data['data_id'])->delete('ic_data_file');
				//Delete from ic_data_location table
				$this->db->where('data_id', $data['data_id'])->delete('ic_data_location');
				//Delete from ic_form_group_data table
				$this->db->where('data_id', $data['data_id'])->delete('ic_form_group_data');
			}
		}

		$result['status'] = 1;
		$result['msg'] = 'Data deleted successfully.';
		echo json_encode($result);
		exit();
	}
	public function delete_formgroupdata()
	{
		$baseurl = base_url();
		$result = array(
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}

		if($this->session->userdata('role') != 1) {
			$result['status'] = 0;
			$result['msg'] = 'You are not authorized to use this feature.';
			echo json_encode($result);
			exit();
		}

		$status = $this->input->post('status');
		if(!isset($status) || strlen($status) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again 1.';
			echo json_encode($result);
			exit();
		}

		$ids = $this->input->post('check');
		if(!$ids || count($ids) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again 2.';
			echo json_encode($result);
			exit();
		}

		foreach ($ids as $id) {
			if($status == 'delete') {
				//Delete from ic_form_group_data table
				$this->db->where('group_id', $id)->update('ic_form_group_data', array('data_status' => 0));
			} else if($status == 'erase') {
				//Delete from ic_form_group_data table
				$this->db->where('group_id', $id)->delete('ic_form_group_data');
			}
		}

		$result['status'] = 1;
		$result['msg'] = 'Data deleted successfully.';
		echo json_encode($result);
		exit();
	}

	public function coconutplantation_info(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = $this->uri->segment(3);
			$data_id = $this->uri->segment(4);

			$data = array(
				'survey_id' => $survey_id,
				'data_id' => $data_id
			);

			$this->load->model('Reports_model');
			$check_record = $this->Reports_model->check_record($data);

			if($check_record == 0){
				show_404();
			}

			$coconutplantation_info = $this->Reports_model->coconutplantation_info($data);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$result = array('coconutplantation_info' => $coconutplantation_info);

			$this->load->view('header', $header_result);
			$this->load->view('reports/coconutplantation_info', $result);
			$this->load->view('footer');
		}
	}
	

	public function activity(){
		// echo '<pre>';print_r($this->session->userdata);exit;
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}
		
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->all_assigned_project();
		// echo '<pre>';print_r($projects);exit;
		$this->load->model('Survey_model');
		$this->load->model('Reports_model');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$pid = $this->input->post('project');
		} else {
			$pid = $projects[0]['proj_id'];
		}
		$all_activity = $this->Reports_model->all_activity($pid);
		
		$ids = array();
		foreach ($all_activity as $key => $value) {
			array_push($ids, $value['id']);
		}
		$moderate = $this->Survey_model->all_moderation_surveys($pid);
		foreach ($moderate as $key => $value) {
			if($value['type'] == 'Activity') {
				if(!in_array($value['id'], $ids)) array_push($all_activity, $value);
			}
		}

		$result = array('all_activity' => $all_activity, 'projects' => $projects);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			echo json_encode($result);
			exit();
		}

		$header_result = array('main_menu' => $main_menu);
		$result = $this->security->xss_clean($result);
		$this->load->view('header', $header_result);
		$this->load->view('reports/activity', $result);
		$this->load->view('footer');
	}

	public function visits(){
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}
		
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->all_assigned_project();

		$this->load->model('Survey_model');
		$this->load->model('Reports_model');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$pid = $this->input->post('project');
		} else {
			$pid = $projects[0]['proj_id'];
		}
		$all_visits = $this->Reports_model->all_visits($pid);
		$ids = array();
		foreach ($all_visits as $key => $value) {
			array_push($ids, $value['id']);
		}
		$moderate = $this->Survey_model->all_moderation_surveys($pid);
		foreach ($moderate as $key => $value) {
			if($value['type'] == 'Visit') {
				if(!in_array($value['id'], $ids)) array_push($all_visits, $value);
			}
		}

		$result = array('all_visits' => $all_visits, 'projects' => $projects);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			echo json_encode($result);
			exit();
		}

		$header_result = array('main_menu' => $main_menu);
		$result = $this->security->xss_clean($result);
		$this->load->view('header', $header_result);
		$this->load->view('reports/visits', $result);
		$this->load->view('footer');
	}
	
	public function validate_record_from_surveys()
	{
		// verfied value = 0 -> reject
		// verfied value = 1 -> approve
		// verfied value = 2 -> delete
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}
		
		$survey_id = $this->uri->segment(3);
		
		$post_data = $this->input->post();
		$type = $post_data['type'];
		$selectedIds = $post_data['selectedIds'];

		$verified_id = $this->session->userdata('login_id');
		$verified_date = date('Y-m-d H:i:s');
		$verified_reason = $post_data['reason'];
		
		$status = 1;

		if($type == 'delete'){
			$verified = 2;
			$status = 4;
		} else if($type == 'approve'){
			$verified = 1;
			$status = 2;			
		} else if($type == 'reject'){
			$verified = 0;	
			$status = 3;			
		}		
			
		foreach ($selectedIds as $selectedId) {
			$this->db->where('id', $selectedId);
			$this->db->set('status', $status);
			$this->db->set('verified', $verified);
			$this->db->set('verified_id', $verified_id);
			$this->db->set('verified_date', $verified_date); 
			$this->db->set('verified_reason', $verified_reason);
			$this->db->update('survey' . $survey_id);
		}

		echo json_encode("success");		
		exit();
		
	}
	
	public function edit_survey()
	{
		$baseurl = base_url();
		if (!$this->session->userdata('login_id')) {
			redirect($baseurl);
		}

		$survey_id = $this->uri->segment(3);

		$post_data = $this->input->post();
		
		if (!empty($post_data['formData']) && !empty($post_data['selectedId'])) {
			$formData = $post_data['formData'];
			$selectedId = $post_data['selectedId'];

			$ae_zone_id = $post_data['aezId'];
			$region_id = $post_data['regionId'];
			$department_id = $post_data['deptId'];
			$commune_id = $post_data['communeId'];
			// $village_id = $post_data['villageId'];

			if (is_array($formData)) {
				foreach ($formData as $key => $value) {
					$this->db->set($key, $value);
				}
				
				$this->db->set('ae_zone_id', $ae_zone_id);
				$this->db->set('region_id', $region_id);
				$this->db->set('department_id', $department_id); 
				$this->db->set('commune_id', $commune_id);
				// $this->db->set('village_id', $village_id);

				$this->db->where('id', $selectedId);
				$this->db->update('survey' . $survey_id);

				echo json_encode("success");
				exit();
			} else {
				echo json_encode("error: formData is not an array");
				exit();
			}
		} else {
			echo json_encode("error: formData or selectedId is empty");
			exit();
		}
	}
	
	public function edit_survey_field()
	{
		$baseurl = base_url();
		if (!$this->session->userdata('login_id')) {
			redirect($baseurl);
		}

		$role_id = $this->session->userdata('role');

		if (!in_array($role_id, [1, 7])) {
			redirect($baseurl);
		}

		$survey_id = $this->uri->segment(3);

		$post_data = $this->input->post();
		
		if (!empty($post_data['recordId']) && !empty($post_data['field'])) {
			$recordId = $post_data['recordId'];
			$field = $post_data['field'];
			$field_value = $post_data['field_value'];						
				
			$this->db->set($field, $field_value);
			if (isset($post_data['project_id']) && $post_data['project_id'] && !empty($post_data['project_id'])) {
				$this->db->set('project_id', $post_data['project_id']);
			}			
			if (isset($post_data['site_id']) && $post_data['site_id'] && !empty($post_data['site_id'])) {
				$this->db->set('site_id', $post_data['site_id']);
			}
			$this->db->where('id', $recordId);
			$this->db->update('survey' . $survey_id);

			echo json_encode("success");
			exit();
		} else {
			echo json_encode("error: recordId or field is empty");
			exit();
		}
	}

	public function get_records_count(){
		
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}	
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

		$survey_id = $this->uri->segment(3);
		$worldRegionIds = $this->input->post('worldRegionIds');
		$countryIds = $this->input->post('countryIds');
		if(!isset($countryIds) || count($countryIds) == 0 ){
			$this->db->distinct()->select('GROUP_CONCAT(country_id) as countries');
			$this->db->where('status', 1);
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			$getCountryList = $this->db->get('lkp_country')->row_array();
			$countryIds = explode(",", $getCountryList['countries']);
		}
		$projectIds = $this->input->post('projectIds');		

		if(!isset($projectIds) || count($projectIds) == 0) {
			$this->db->distinct()->select('GROUP_CONCAT(project_id) as projects');
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('world_region_id', $worldRegionIds);
			}
			if ($worldRegionIds && count($worldRegionIds) > 0) {
				$this->db->where_in('country_id', $countryIds);
			}
			$this->db->where('status', 1);
			$projectsList = $this->db->get('lkp_project_site')->row_array();

			if ($this->session->userdata('role') == 6) {
				$this->db->select('user_id')
						->from('tbl_users')
						->where('role_id', 6)
						->where('user_id !=', $user_id);
				$adminUsersResult = $this->db->get()->result_array();
				$adminUsers = array_column($adminUsersResult, 'user_id');

				// Main query for projects
				$this->db->select('GROUP_CONCAT(DISTINCT sites.project_id) as projects')
						->from('lkp_project_site as sites')
						->join('lkp_country_projects as projects', 'sites.project_id = projects.id')
						->where('sites.status', 1)
						->where('projects.status', 1);

				// Apply optional region and country filters
				if (!empty($worldRegionIds)) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if (!empty($countryIds)) {
					$this->db->where_in('sites.country_id', $countryIds);
				}

				// Apply project type and user_id conditions, excluding other admins
				$this->db->group_start()
						->where('projects.project_type', 'Public')
						->or_where('projects.user_id', $user_id)
						->group_end();
				if (!empty($adminUsers)) {
					$this->db->where_not_in('projects.user_id', $adminUsers);
				}

				$projectsList = $this->db->get()->row_array();
			}

			if($this->session->userdata('role') == 8) {
				$this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
				$this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.world_region_id', $worldRegionIds);
				}
				if ($worldRegionIds && count($worldRegionIds) > 0) {
					$this->db->where_in('sites.country_id', $countryIds);
				}
				$this->db->where('sites.status', 1)->where('projects.status', 1)->where('projects.user_id', $this->session->userdata('login_id'));
				$projectsList = $this->db->get('lkp_project_site as sites')->row_array();
			}
			$projectIds = explode(",", $projectsList['projects']);
		}

		$siteIds = $this->input->post('siteIds');
		$start_date = $this->input->post('startDate');
		$end_date = $this->input->post('endDate');

		$result['total_count'] 		= $this->apply_filters($survey_id, $projectIds, $siteIds, $countryIds, $start_date, $end_date);
		$result['pending_count'] 	= $this->apply_filters($survey_id, $projectIds, $siteIds, $countryIds, $start_date, $end_date, null);  // Pending (verified = null)
		$result['approved_count'] 	= $this->apply_filters($survey_id, $projectIds, $siteIds, $countryIds, $start_date, $end_date, 1);     // Approved (verified = 1)
		$result['rejected_count'] 	= $this->apply_filters($survey_id, $projectIds, $siteIds, $countryIds, $start_date, $end_date, 0);     // Rejected (verified = 0)

		echo json_encode($result);
		exit();
	}	

	public function apply_filters($survey_id, $projectIds, $siteIds, $countryIds, $start_date, $end_date, $verified = 'total') {

		$role_id = $this->session->userdata('role');
		$user_id = $this->session->userdata('login_id');
		
		$this->db->from('survey' . $survey_id . ' AS survey')->where_not_in('survey.status', [0, 4]);
		if ($projectIds && count($projectIds) > 0) {
			if($survey_id == 4) {
				$this->db->where_in('survey.fgd_project_id', $projectIds);
			}else{
				$this->db->where_in('survey.project_id', $projectIds);
			}
		}
		if ($siteIds && count($siteIds) > 0) {
			if($survey_id == 4) {
				$this->db->where_in('survey.fgd_site_id', $siteIds);
			}else{
				$this->db->where_in('survey.site_id', $siteIds);
			}
		}
		if (isset($countryIds) && !is_null($countryIds) && (count($countryIds) > 0)) {
			$this->db->where_in('survey.country_id', $countryIds);
		}
		if ($start_date) {
			$this->db->where('survey.datetime >=', $start_date . ' 00:00:00');
		}
		if ($end_date) {
			$this->db->where('survey.datetime <=', $end_date . ' 23:59:59');
		}
		if ($role_id == 8) {
			$this->db->where('survey.user_id', $user_id);
		}
		if ($verified !== 'total') {
			$this->db->where('survey.verified', $verified);
		}	
		return $this->db->get()->num_rows();
	}
	
	/*filters*/
	public function getCountryList() {
		$this->db->where('status', 1);
		$this->db->where_in('world_region_id', $this->input->post('world_region_id'));
		$getCountryList = $this->db->get('lkp_country')->result_array();

		$result = array('countryList' => $getCountryList, 'status' => 1);

		echo json_encode($result);
		exit;
	}

	public function getProjectsList() {
		$user_id = $this->session->userdata('login_id');
		$this->db->distinct()->select('GROUP_CONCAT(project_id) as projects');
		$this->db->where_in('world_region_id', $this->input->post('world_region_id'));
		$this->db->where_in('country_id', $this->input->post('country_id'));
		$this->db->where('status', 1);
		$projectsList = $this->db->get('lkp_project_site')->row_array();

		if($this->session->userdata('role') == 6) {
			$this->db->select('user_id')
					->from('tbl_users')
					->where('role_id', 6)
					->where('user_id !=', $user_id);
			$adminUsersResult = $this->db->get()->result_array();
			$adminUsers = array_column($adminUsersResult, 'user_id');

			$this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
			$this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
			$this->db->where_in('sites.world_region_id', $this->input->post('world_region_id'));
			$this->db->where_in('sites.country_id', $this->input->post('country_id'));
			$this->db->where('sites.status', 1)
						->where('projects.status', 1)
						->group_start()
							->where('projects.project_type', 'Public')
							->or_where('projects.user_id', $user_id)
						->group_end();
			if (!empty($adminUsers)) {
				$this->db->where_not_in('projects.user_id', $adminUsers);
			}
			$projectsList = $this->db->get('lkp_project_site as sites')->row_array();
		}

		if($this->session->userdata('role') == 8) {
			$this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
			$this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
			$this->db->where_in('sites.world_region_id', $this->input->post('world_region_id'));
			$this->db->where_in('sites.country_id', $this->input->post('country_id'));
			$this->db->where('sites.status', 1)->where('projects.status', 1)->where('projects.user_id', $this->session->userdata('login_id'));
			$projectsList = $this->db->get('lkp_project_site as sites')->row_array();
		}			

		$projectIds = explode(",", $projectsList['projects']);

		$this->db->where_in('id', explode(",", $projectsList['projects']));
		$getProjectList = $this->db->where('status', 1)->get('lkp_country_projects')->result_array();

		$result = array('projectInfo' => $getProjectList, 'status' => 1);

		echo json_encode($result);
		exit;
	}

	public function getSitesList() {
		$sitesList = $this->db->where_in('project_id', $this->input->post('project_id'))->where('status', 1)->get('lkp_project_site')->result_array();

		$result = array('sitesList' => $sitesList, 'status' => 1);

		echo json_encode($result);
		exit;
	}

}