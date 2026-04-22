<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('form_validation');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}
	
	public function index()
	{
		show_404();	
	}

	public function all_projets()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Projects_model');
		$all_projets = $this->Projects_model->all_project();
		echo json_encode(array(
			'status' => 1,
			'all_projets' => $all_projets,
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
		));
		exit();
	}
	
	public function create()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Projects_model');
			$all_projets = $this->Projects_model->all_project();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_projets' => $all_projets);

			$this->load->view('header', $header_result);
			$this->load->view('projects/create', $result);
			$this->load->view('footer');
		}
	}
	public function add_project()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		$error = array('project_name' => '', 'project_description' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['project_name'])) {
			$error['project_name'] = 'Project name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['project_name']) > 250) {
			$error['project_name'] = 'Project name should not exceed 250 characters in length.';
			$error['status'] = 1;
		}

		if(empty($_POST['description'])) {
			$error['project_description'] = 'Project description is mandatory.';
			$error['status'] = 1;
		} else if(strlen($_POST['description']) > 10000) {
			$error['project_description'] = 'Project description should not exceed 10000 characters in length.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'project_name' => htmlspecialchars($this->input->post('project_name'), ENT_QUOTES),
			'project_description' => htmlspecialchars($this->input->post('description'), ENT_QUOTES),
			'added_by' => $this->session->userdata('login_id'),
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address()
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Projects_model');
		$insertquery = $this->Projects_model->add_project($data);
		if($insertquery) {
			echo json_encode(
				array(
					'msg' => 'Project Added Successfully',
					'insertstatus' => 1,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				)
			);
			exit();
		} else {
			echo json_encode(
				array(
					'msg'=>'Sorry! Please try after sometime.',
					'insertstatus' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				)
			);
			exit();
		}
	}

	public function add_edit_project()
	{
		$baseurl = base_url();

		if ($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		$type = $this->input->post('type');

		if ($type == 'projects') {
			// Collect project data
			$data = array(
				'project_name' => $this->input->post('project_name'),
				'projectDescription' => $this->input->post('projectDescription'),
				'projectStartDate' => $this->input->post('projectStartDate'),
				'projectEndDate' => $this->input->post('projectEndDate'),
				'projectOrganization' => $this->input->post('projectOrganization'),
				'project_type' => $this->input->post('project_type'),
				'user_id' => $this->session->userdata('login_id'),
				'status' => 1,
			);
		} else {
			// Collect project data
			$data = array(
				'site_name' => $this->input->post('site_name'),
				'site_description' => $this->input->post('site_description'),
				'world_region_id' => $this->input->post('world_region_id'),
				'country_id' => $this->input->post('country_id'),
				'localCurrency' => $this->input->post('localCurrency'),
				'sub_region' => $this->input->post('sub_region'),
				'village_community' => $this->input->post('village_community'),
				'community_type' => $this->input->post('community_type'),
				'grazingmetabolisable' => $this->input->post('grazingmetabolisable'),
				'grazingcrude' => $this->input->post('grazingcrude'),
				'collectedmetabolisable' => $this->input->post('collectedmetabolisable'),
				'collectedcrude' => $this->input->post('collectedcrude'),
				'project_id' => $this->input->post('selectedProjectId'),
				'user_id' => $this->session->userdata('login_id'),
				'status' => 1,
			);

			$regionArray = array(
				'majorRegionArray' => $this->input->post('majorRegionArray'),
				'minorRegionArray' => $this->input->post('minorRegionArray')
			);
		}

		// Clean data
		$data = $this->security->xss_clean($data);

		// Check if we are editing a project
		if ($type == 'projects') {
			$project_id = $this->input->post('project_id'); // Assume this is sent in the POST request
		} else {
			$project_id = $this->input->post('site_id'); // Assume this is sent in the POST request
		}

		$this->load->model('Projects_model');

		if ($project_id) {
			// Edit project
			if ($type == 'projects') {

				$newProjectType = $this->input->post('project_type');
				$oldProjectType = $this->db->select('project_type')->where('id', $project_id)->get('lkp_country_projects')->row_array();

				if ($newProjectType != $oldProjectType['project_type']) {
					if($oldProjectType['project_type'] == 'Public' && $newProjectType == 'Private' ) {
						echo json_encode(array(
							'msg' => 'Public project cant be made Private.',
							'insertstatus' => 0,
							'csrfName' => $this->security->get_csrf_token_name(),
							'csrfHash' => $this->security->get_csrf_hash()
						));
						exit();
					}
				}

				$updatequery = $this->Projects_model->update_country_project($project_id, $data);
				$insertionType = 'Project';
			} else {
				$updatequery = $this->Projects_model->update_country_site($project_id, $data, $regionArray);
				$insertionType = 'Site';
			}

			if ($updatequery) {
				echo json_encode(array(
					'msg' => $insertionType.' Updated Successfully',
					'insertstatus' => 1,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			} else {
				echo json_encode(array(
					'msg' => 'Failed to update '.$updatequery,
					'insertstatus' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			}
		} else {
			// Add new project
			if ($type == 'projects') {
				$insertquery = $this->Projects_model->add_country_project($data);
				$insertionType = 'Project';
			} else {
				$insertquery = $this->Projects_model->add_country_site($data, $regionArray);
				$insertionType = 'Site';
			}
			
			if ($insertquery) {
				echo json_encode(array(
					'msg' => $insertionType.' Added Successfully',
					'insertstatus' => 1,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			} else {
				echo json_encode(array(
					'msg' => 'Failed to create '.$updatequery,
					'insertstatus' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			}
		}
	}

	public function change_project_status(){
		// Check for session and CSRF token...
		$projectId = $this->input->post('id');
		$newStatus = $this->input->post('status') === 'active' ? 1 : 0;
		$type = $this->input->post('type');

		$data = array(
			'status' => $newStatus,
		);

		$table_name = $type == 'projects' ? 'lkp_country_projects' : 'lkp_project_site';

		$this->load->model('Projects_model');
		$updateQuery = $this->Projects_model->update_project_status($projectId, $data, $table_name);

		if ($updateQuery) {
			echo json_encode(array('msg' => 'Project status updated successfully', 'status' => 1));
		} else {
			echo json_encode(array('msg' => 'Failed to update project status', 'status' => 0));
		}
		exit(); // Ensure to exit after sending the response
	}

	public function view($value='')
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Projects_model');
			$all_projets = $this->Projects_model->all_project();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_projets' => $all_projets);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('projects/view', $result);
			$this->load->view('footer');
		}
	}
	public function project_details()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		if(!$this->input->post('project_id')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		$project_id = $this->input->post('project_id');
		if(strlen($project_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		
		$data = array(
			'project_id' => $project_id
		);
		$this->load->model('Projects_model');
		$details = $this->Projects_model->get_project_details($data);
		if($details) {
			echo json_encode(array(
				'status' => 1,
				'details' => $details,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		} else {
			echo json_encode(array(
				'status' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
	}
	public function project_locations()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		if(!$this->input->post('project_id')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		$project_id = $this->input->post('project_id');
		if(strlen($project_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		
		$data = array(
			'project_id' => $project_id
		);
		$this->load->model('Projects_model');
		$locations = $this->Projects_model->get_project_locations($data);
		if(count($locations) === 0) {
			echo json_encode(array(
				'status' => 2,
				'msg'=>'No location has been assigned to this project.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		if(!$locations) {
			echo json_encode(array(
				'status' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		echo json_encode(array(
			'status' => 1,
			'locations' => $locations,
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
		));
		exit();
	}
	public function project_partners()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		if(!$this->input->post('project_id')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		$project_id = $this->input->post('project_id');
		if(is_array($project_id)) {
			if(count($project_id) == 0) {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Invalid request. Please refresh the page and try again.',
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				));
				exit();
			}
			$data = array(
				'project_id' => $project_id
			);
		} else {
			if(strlen($project_id) == 0) {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Invalid request. Please refresh the page and try again.',
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				));
				exit();
			}
			$data = array(
				'project_id' => array($project_id)
			);
		}
		
		$this->load->model('Projects_model');
		$partners = $this->Projects_model->get_project_partners($data);
		if(count($partners) === 0) {
			echo json_encode(array(
				'status' => 2,
				'msg'=>'No partner has been assigned to this project.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		if(!$partners) {
			echo json_encode(array(
				'status' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		echo json_encode(array(
			'status' => 1,
			'partners' => $partners,
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
		));
		exit();
	}

	public function edit()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Projects_model');
			$all_projets = $this->Projects_model->all_project();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_projets' => $all_projets);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('projects/edit', $result);
			$this->load->view('footer');
		}
	}
	public function edit_project()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		if(!$this->input->post('project_id')) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$project_id = $this->input->post('project_id');
		if(strlen($project_id) == 0) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'project_id' => $project_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Projects_model');
		$details = $this->Projects_model->get_project_details($data);
		if(!$details) {
			echo json_encode(array(
				'updatestatus' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		$error = array('project_name' => '', 'project_description' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['project_name'])) {
			$error['project_name'] = 'Project name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['project_name']) > 250) {
			$error['project_name'] = 'Project name should not exceed 250 characters in length.';
			$error['status'] = 1;
		}

		if(empty($_POST['description'])) {
			$error['project_description'] = 'Project description is mandatory.';
			$error['status'] = 1;
		} else if(strlen($_POST['description']) > 10000) {
			$error['project_description'] = 'Project description should not exceed 10000 characters in length.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'project_id' => $this->input->post('project_id')
			),
			'set' => array(
				'project_name' => htmlspecialchars($this->input->post('project_name'), ENT_QUOTES),
				'project_description' => htmlspecialchars($this->input->post('description'), ENT_QUOTES)
			)
		);
		$data = $this->security->xss_clean($data);
		$updatequery = $this->Projects_model->edit_project($data);
		if($updatequery) {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Project Updated Successfully',
				'updatestatus' => 1
			));
			exit();
		} else {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'updatestatus' => 0
			));
			exit();
		}
	}

	public function delete()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Projects_model');
			$all_projets = $this->Projects_model->all_project();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_projets' => $all_projets);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('projects/delete', $result);
			$this->load->view('footer');
		}
	}
	public function delete_project()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		if(!$this->input->post('project_id')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		$project_id = $this->input->post('project_id');
		if(strlen($project_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Invalid request. Please refresh the page and try again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}
		
		$data = array(
			'project_id' => $project_id
		);
		$this->load->model('Projects_model');
		$details = $this->Projects_model->get_project_details($data);
		if(!$details) {
			echo json_encode(array(
				'status' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'project_id' => $this->input->post('project_id')
			),
			'set' => array(
				'status' => 0
			)
		);
		$deletequery = $this->Projects_model->edit_project($data);
		if($deletequery) {
			$this->load->model('Partners_model');
			$this->Partners_model->edit_partner_project($data);

			$data = array(
				'where' => array(
					'lkp_project_id' => $this->input->post('project_id')
				),
				'set' => array(
					'relation_status' => 0
				)
			);
			$this->load->model('Survey_model');
			$this->Survey_model->edit_survey_project($data);
			
			echo json_encode(
				array(
					'msg' => 'Project Deleted Successfully',
					'status' => 1,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				)
			);
			exit();
		} else {
			echo json_encode(
				array(
					'msg'=>'Sorry! Please try after sometime.',
					'status' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				)
			);
			exit();
		}
	}

	public function manage_projects(){
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
		$world_region = $this->Projects_model->get_world_region();
		$major_region = $this->Projects_model->get_major_region();
		$countries = $this->Projects_model->get_countries();
		$projects = $this->Projects_model->get_country_projects();

		$header_result = array('main_menu' => $main_menu);
		$world_region = $this->security->xss_clean($world_region);
		$major_region = $this->security->xss_clean($major_region);
		$countries = $this->security->xss_clean($countries);
		$projects = $this->security->xss_clean($projects);
		
		$result = array('world_region' => $world_region, 'major_region' => $major_region, 'countries' => $countries, 'projects' => $projects);
		
		$this->load->view('header', $header_result);
		$this->load->view('reports/manage_projects', $result);
		$this->load->view('footer');
	}

	public function manage_sites(){
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

		$project_id = $this->uri->segment(3);
			
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$this->load->model('Projects_model');
		$world_region = $this->Projects_model->get_world_region();
		$major_region = $this->Projects_model->get_major_region();
		$countries = $this->Projects_model->get_countries();
		$communities_type = $this->Projects_model->get_communities_type();
		$currency = $this->Projects_model->get_currency();
		$projects = $this->Projects_model->get_country_projects();
		$sites = $this->Projects_model->get_country_sites($project_id);
		foreach ($sites as $key => $value) {
			$sites[$key]['majorRegion']  = $this->db->where('status', 1)->where('site_id', $value['id'])->get('lkp_major_region')->result_array();
			$sites[$key]['minorRegion']  = $this->db->where('status', 1)->where('site_id', $value['id'])->get('lkp_minor_region')->result_array();	
		}
		
		$projectInfo = $this->Projects_model->get_project_info($project_id);

		$header_result = array('main_menu' => $main_menu);
		$world_region = $this->security->xss_clean($world_region);
		$major_region = $this->security->xss_clean($major_region);
		$countries = $this->security->xss_clean($countries);
		$projects = $this->security->xss_clean($projects);
		$sites = $this->security->xss_clean($sites);
		
		$result = array('world_region' => $world_region, 'major_region' => $major_region, 'countries' => $countries, 'communities_type' => $communities_type, 'currency' => $currency, 'projects' => $projects, 'sites' => $sites, 'projectInfo' => $projectInfo);
		$this->load->view('header', $header_result);
		$this->load->view('reports/manage_sites', $result);
		$this->load->view('footer');
	}

	public function manage_sites_edit(){
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

		$project_id = $this->uri->segment(3);
		$site_id = $this->uri->segment(4);

		if($project_id == '' || $site_id == ''){
			show_404();	
		}
			
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$this->load->model('Projects_model');
		$world_region = $this->Projects_model->get_world_region();
		$major_region = $this->Projects_model->get_major_region();
		$countries = $this->Projects_model->get_countries();
		$communities_type = $this->Projects_model->get_communities_type();
		$currency = $this->Projects_model->get_currency();
		$projects = $this->Projects_model->get_country_projects();
		//$sites = $this->Projects_model->get_country_sites($project_id);
		$checksite = $this->db->where('id', $site_id)->where('project_id', $project_id)->where('status', 1)->get('lkp_project_site')->num_rows();
		if($checksite == 0){
			show_404();
		}
		$sites = $this->db->where('id', $site_id)->where('project_id', $project_id)->where('status', 1)->get('lkp_project_site')->row_array();
		$sites['majorRegion']  = $this->db->where('status', 1)->where('site_id', $sites['id'])->get('lkp_major_region')->result_array();
		$sites['minorRegion']  = $this->db->where('status', 1)->where('site_id', $sites['id'])->get('lkp_minor_region')->result_array();	
		
		$projectInfo = $this->Projects_model->get_project_info($project_id);

		$header_result = array('main_menu' => $main_menu);
		$world_region = $this->security->xss_clean($world_region);
		$major_region = $this->security->xss_clean($major_region);
		$countries = $this->security->xss_clean($countries);
		$projects = $this->security->xss_clean($projects);
		$sites = $this->security->xss_clean($sites);
		
		$result = array('world_region' => $world_region, 'major_region' => $major_region, 'countries' => $countries, 'communities_type' => $communities_type, 'currency' => $currency, 'projects' => $projects, 'sites' => $sites, 'projectInfo' => $projectInfo);
		$this->load->view('header', $header_result);
		$this->load->view('reports/manage_sites_edit', $result);
		$this->load->view('footer');
	}

	public function manage_site_major_region()
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
		$this->load->model('Projects_model');
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();
		$header_result = array('main_menu' => $main_menu);

		$projectId = $this->uri->segment(3);
		$siteId = $this->uri->segment(4);

		$sites_major_region = $this->Projects_model->get_sites_major_region($projectId, $siteId);

		$result = array('sites_major_region' => $sites_major_region);

		$this->load->view('header', $header_result);
		$this->load->view('reports/manage_site_major_region', $result);
		$this->load->view('footer');
	}

	public function add_edit_region()
	{
		$baseurl = base_url();

		if ($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		$type = $this->input->post('type');

		if ($type == 'major_region') {
			// Collect Major Region data
			$data = array(
				'project_id' => $this->input->post('projectId'),
				'site_id' => $this->input->post('siteId'),
				'major_region_name' => htmlspecialchars($this->input->post('major_region_name'), ENT_QUOTES),
				'user_id' => $this->session->userdata('login_id'),
				'datetime' =>  date('Y-m-d H:i:s'),
				'status' => 1,
			);
		} else {
			// Collect Minor Region data
			$data = array(
				'major_region_id' => $this->input->post('majorregionId'),
				'project_id' => $this->input->post('projectId'),
				'site_id' => $this->input->post('siteId'),
				'minor_region_name' => htmlspecialchars($this->input->post('minor_region_name'), ENT_QUOTES),
				'user_id' => $this->session->userdata('login_id'),
				'datetime' => date('Y-m-d H:i:s'),
				'status' => 1,
			);
		}

		// Clean data
		$data = $this->security->xss_clean($data);

		// Check if we are editing a project
		if ($type == 'major_region') {
			$regionId = $this->input->post('major_region_nameId'); // Assume this is sent in the POST request
		} else {
			$regionId = $this->input->post('minor_region_nameId'); // Assume this is sent in the POST request
		}

		$this->load->model('Projects_model');

		if ($regionId) {
			// Edit Region
			if ($type == 'major_region') {
				$updatequery = $this->Projects_model->update_major_region($regionId, $data);
			} else {
				$updatequery = $this->Projects_model->update_minor_region($regionId, $data);
			}

			if ($updatequery) {
				echo json_encode(array(
					'msg' => 'Region Updated Successfully',
					'insertstatus' => 1,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			} else {
				echo json_encode(array(
					'msg' => 'Sorry! Please try after sometime.',
					'insertstatus' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			}
		} else {
			// Add new project
			if ($type == 'major_region') {
				$insertquery = $this->Projects_model->add_major_region($data);
			} else {
				$insertquery = $this->Projects_model->add_minor_region($data);
			}
			
			if ($insertquery) {
				echo json_encode(array(
					'msg' => 'Region Added Successfully',
					'insertstatus' => 1,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			} else {
				echo json_encode(array(
					'msg' => 'Sorry! Please try after sometime.',
					'insertstatus' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash()
				));
				exit();
			}
		}
	}

	public function manage_site_minor_region()
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
		$this->load->model('Projects_model');
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();
		$header_result = array('main_menu' => $main_menu);

		$projectId = $this->uri->segment(3);
		$siteId = $this->uri->segment(4);
		$majorRegionId = $this->uri->segment(5);

		$sites_minor_region = $this->Projects_model->get_sites_minor_region($projectId, $siteId, $majorRegionId);

		$result = array('sites_minor_region' => $sites_minor_region);

		$this->load->view('header', $header_result);
		$this->load->view('reports/manage_site_minor_region', $result);
		$this->load->view('footer');
	}

	public function change_region_status()
	{
		// Check for session and CSRF token...
		$projectId = $this->input->post('id');
		$newStatus = $this->input->post('status') === 'active' ? 1 : 0;
		$type = $this->input->post('type');

		$data = array(
			'status' => $newStatus,
		);

		$this->load->model('Projects_model');

		$table_name = $type == 'major_region' ? 'lkp_major_region' : 'lkp_minor_region';

		$this->load->model('Projects_model');
		$updateQuery = $this->Projects_model->update_region_status($projectId, $data, $table_name);

		if ($updateQuery) {
			echo json_encode(array('msg' => 'Region status updated successfully', 'status' => 1));
		} else {
			echo json_encode(array('msg' => 'Failed to update region status', 'status' => 0));
		}
		exit(); // Ensure to exit after sending the response
	}
}