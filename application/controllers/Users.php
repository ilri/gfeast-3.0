<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		$this->load->model('User_model');
		$this->load->model('Helper_model');
		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}

	public function index(){
		show_404();
	}

	public function create(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}		
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');
		
		if ($role_id == 8) {
			$baseurl = base_url();
			redirect($baseurl);
		}

		// $this->load->model('Dynamicmenu_model');
		// $main_menu = $this->Dynamicmenu_model->menu_details();
		// $main_menu = $this->security->xss_clean($main_menu);
		// $header_result = array('main_menu' => $main_menu);
		
		$result = array();
		
		$all_roles = $this->User_model->all_roles();
		$result['all_roles'] = $this->security->xss_clean($all_roles);
		
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$header_result = array('main_menu' => $main_menu);

		$this->load->view('header', $header_result);
		// $this->load->view('header');
		// $this->load->view('sidebar');
		$this->load->view('user/create', $result);
		$this->load->view('footer');
	}

	public function insert_user(){
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

		$error = array(
			'status' => 0,
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		$fname = $this->input->post('first_name');
		if(empty($fname)) {
			$error['first_name'] = 'First name is mandatory.';
			$error['status'] = 1;
		}

		$lname = $this->input->post('last_name');
		if(empty($lname)) {
			$error['last_name'] = 'Last name is mandatory.';
			$error['status'] = 1;
		}

		$role = $this->input->post('user_role');
		if(empty($role)) {
			$error['user_role'] = 'Role selection is mandatory.';
			$error['status'] = 1;
		}

		$email = $this->input->post('email');
		if(empty($email)) {
			$error['email'] = 'Email is mandatory.';
			$error['status'] = 1;
		} else {
			$check_emaiid = $this->User_model->check_emaiid();

			if($check_emaiid) {
				if($check_emaiid['status'] == 0) {
					$error['email'] = 'Email has been blocked. Please contact admin for more details.';
					$error['status'] = 1;
				} else {
					$error['email'] = 'Email already exists.';
					$error['status'] = 1;
				}
			}
		}

		$username = $this->input->post('username');
		if(empty($username)) {
			$error['username'] = 'Username is mandatory.';
			$error['status'] = 1;
		} else {
			$check_username = $this->User_model->check_username();

			if($check_username) {
				if($check_username['status'] == 0) {
					$error['username'] = 'Username has been blocked. Please contact admin for more details.';
					$error['status'] = 1;
				} else {
					$error['username'] = 'Username already exists.';
					$error['status'] = 1;
				}
			}
		}

		// $phone = $this->input->post('phone');
		// if(!empty($phone) && !preg_match('/^\+?[0-9]{7,15}$/', preg_replace('/\s+/', '', $phone))) {
		// 	$error['phone'] = 'Please enter a valid phone number.';
		// 	$error['status'] = 1;
		// }

		$password = $this->input->post('password');
		if(empty($password)) {
			$error['password'] = 'Password is mandatory.';
			$error['status'] = 1;
		}

		$cpassword = $this->input->post('cpassword');
		if(empty($cpassword)) {
			$error['cpassword'] = 'Confirm password is mandatory.';
			$error['status'] = 1;
		}

		if(!empty($password) && !empty($cpassword) && $password != $cpassword) {
			$error['password'] = 'Both password and confirm password should be same.';
			$error['cpassword'] = 'Both password and confirm password should be same.';
			$error['status'] = 1;
		}

		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}

		$insert_user = $this->User_model->insert_user();
		if(!$insert_user){
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'insertstatus' => 0
			));
			exit();
		}else{
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'User added successfully.',
				'insertstatus' => 1
			));
			exit();
		}
	}

	public function manage(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}		
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');
		
		if ($role_id == 8) {
			$baseurl = base_url();
			redirect($baseurl);
		}

		// $this->load->model('Dynamicmenu_model');
		// $main_menu = $this->Dynamicmenu_model->menu_details();
		// $main_menu = $this->security->xss_clean($main_menu);
		// $header_result = array('main_menu' => $main_menu);
		
		$result = array();
		
		$this->load->model('User_model');

		if ($role_id == 7) {
			$assigned_locations = $this->db->select(array('user_id', 'world_region_id', 'major_region_id', 'country_id', 'state_id', 'district_id'))->where('user_id', $user_id)->from('tbl_user_unit_location')->get()->result_array();
			$districts = array_unique(array_column($assigned_locations, 'district_id'));
			$user_ids = [];
			if (count($districts) > 0) {
				$assigned_locations_1 = $this->db->select(array('user_id'))->where_in('district_id', $districts)->from('tbl_user_unit_location')->get()->result_array();
				$user_ids = array_unique(array_column($assigned_locations_1, 'user_id'));
			}
			$user_ids = array_diff($user_ids, array($user_id));
			$all_users = $this->User_model->all_users_without_status($user_ids);
		} else {
			$all_users = $this->User_model->all_users_without_status();
		}

		$result['users'] = $this->security->xss_clean($all_users);

		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('user/manage', $result);
		$this->load->view('footer');
	}

	public function map(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}		
					
		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');
		
		if ($role_id == 8) {
			$baseurl = base_url();
			redirect($baseurl);
		}

		// $this->load->model('Dynamicmenu_model');
		// $main_menu = $this->Dynamicmenu_model->menu_details();
		// $main_menu = $this->security->xss_clean($main_menu);
		// $header_result = array('main_menu' => $main_menu);
		
		$result = array();
		
		$this->load->model('User_model');
		$assigned_locations = [];		

		if ($role_id == 7) {
			$assigned_locations = $this->db->select(array('user_id', 'world_region_id', 'major_region_id', 'country_id', 'state_id', 'district_id'))->where('user_id', $user_id)->from('tbl_user_unit_location')->get()->result_array();
			$districts = array_unique(array_column($assigned_locations, 'district_id'));
			$user_ids = [];
			if (count($districts) > 0) {
				$assigned_locations_1 = $this->db->select(array('user_id'))->where_in('district_id', $districts)->from('tbl_user_unit_location')->get()->result_array();
				$user_ids = array_unique(array_column($assigned_locations_1, 'user_id'));
			}
			$user_ids = array_diff($user_ids, array($user_id));
			$all_users = $this->User_model->all_users($user_ids);
		} else {
			$all_users = $this->User_model->all_users();
		}

		$result['users'] = $this->security->xss_clean($all_users);

		$this->load->model('Helper_model');
		$all_units = $this->Helper_model->all_units();
		$result['units'] = $this->security->xss_clean($all_units);
		
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$header_result = array('main_menu' => $main_menu);

		$this->load->model('Projects_model');
		if ($role_id == 7) {
			$worlds_ = array_unique(array_column($assigned_locations, 'world_region_id'));
			if (!empty($worlds_)) {
				$world_region = $this->db->where_in('id', $worlds_)
										->where('status', 1)
										->get('lkp_world_region')
										->result_array();
			} else {
				// Handle the case when no world regions are assigned
				$world_region = [];
			}
		} else {
			$world_region = $this->Projects_model->get_world_region();
		}
		$major_region = $this->Projects_model->get_major_region();
		$countries = $this->Projects_model->get_countries();
		$projects = $this->Projects_model->get_country_projects();
		$sites = $this->Projects_model->get_country_sites();

		$header_result = array('main_menu' => $main_menu);

		$world_region = $this->security->xss_clean($world_region);
		$major_region = $this->security->xss_clean($major_region);
		$countries = $this->security->xss_clean($countries);
		$projects = $this->security->xss_clean($projects);
		$sites = $this->security->xss_clean($sites);
		$result['world_region'] = $world_region;
		$result['major_region'] = $major_region;
		$result['countries'] = $countries;
		$result['projects'] = $projects;
		$result['sites'] = $sites;

		$this->load->view('header', $header_result);
		// $this->load->view('header');
		// $this->load->view('sidebar');
		$this->load->view('user/map', $result);
		$this->load->view('footer');
	}
	public function get_user_locations(){
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

		if(!$this->input->post('user_id')) { // !$this->input->post('agency') || 
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';

			echo json_encode($result);
			exit();
		}

		// $agency = $this->input->post('agency');
		$user_id = $this->input->post('user_id');
		if(strlen($user_id) == 0) { // strlen($agency) == 0 || 
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';

			echo json_encode($result);
			exit();
		}

		$result['status'] = 1;
		ini_set('memory_limit', '-1');

		// Get all locations
		$this->load->model('Helper_model');
		$country_id = $this->input->post('country_id');
		$states = $this->Helper_model->all_states($country_id);
		// $districts = $this->Helper_model->all_districts();
		// $tehsils = $this->Helper_model->all_tehsils();
		// $blocks = $this->Helper_model->all_blocks();
		// $gps = $this->Helper_model->all_gps();
		// $villages = $this->Helper_model->all_villages();
		foreach ($states as $skey => $state) {
			$districts = $this->Helper_model->all_districts($state['state_id']);
			
			// foreach ($districts as $dkey => $dist) {
			// 	$tehsils = $this->Helper_model->all_tehsils($dist['district_id']);

				// foreach ($tehsils as $tkey => $tehsil) {
				// 	$blocks = $this->Helper_model->all_blocks($tehsil['district_id']);

					// foreach ($blocks as $bkey => $block) {
					// 	$gps = $this->Helper_model->all_gps($block['block_id']);

					// 	foreach ($gps as $gkey => $gp) {
					// 		$villages = $this->Helper_model->all_villages($gp['grampanchayat_id']);
							
					// 		$gps[$gkey]['villages'] = $villages;
					// 	}
						
					// 	$blocks[$bkey]['gps'] = $gps;
					// }
					
				// 	$tehsils[$tkey]['blocks'] = $blocks;
				// }
				
			// 	$districts[$dkey]['tehsils'] = $tehsils;
			// }

			$states[$skey]['districts'] = $districts;
		}
		$result['states'] = $states;
		
		// Get locations assigned to user
		$this->db->select('world_region_id, major_region_id, country_id, state_id, district_id'); // , block_id, village_id
		$this->db->where('user_id', $user_id); // ->where('UNIT_ID', $agency)
		$assignedLoc = $this->db->where('status', 1)->get('tbl_user_unit_location');
		if($assignedLoc->num_rows() === 0) { $selectedLoc = array(); }
		else { $selectedLoc = array(
			'states' => array(),
			'districts' => array(),
			'tehsils' => array(),
			// 'blocks' => array(),
			'gps' => array(),
			'villages' => array()
		); }
		$assignedLoc = $assignedLoc->result_array();
		foreach ($assignedLoc as $key => $loc) {
			if(!in_array($loc['state_id'], $selectedLoc['states'])) array_push($selectedLoc['states'], $loc['state_id']);
			if(!in_array($loc['district_id'], $selectedLoc['districts'])) array_push($selectedLoc['districts'], $loc['district_id']);
			// if(!in_array($loc['block_id'], $selectedLoc['blocks'])) array_push($selectedLoc['blocks'], $loc['block_id']);
			// if(!in_array($loc['village_id'], $selectedLoc['villages'])) array_push($selectedLoc['villages'], $loc['village_id']);
		}
		$result['selectedLoc'] = json_encode($selectedLoc);
		
		$this->db->select('project_id, site_id');
		$this->db->where('user_id', $user_id);
		$selectedProjSites = $this->db->where('status', 1)->get('tbl_user_projects_sites')->result_array();
		// i want like this 
		// $selectedProjSites = [
		// 	{
		// 		projectId: $selectedProjSites[i].project_id,
		// 		sites: $selectedProjSites(item=>item.project_id === projectId)
		// 	}...sooo on
		// ]
		$result['selectedProjSites'] = $selectedProjSites;
		$result['assignedLoc'] = $assignedLoc;

		echo json_encode($result);
		exit();
	}

	public function update_user_mapping() {
		date_default_timezone_set("UTC");
		$result = array(
			'status' => 0,
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);
	
		// Check for session validity
		if($this->session->userdata('login_id') == '') {
			$result['session_err'] = 1;
			$result['msg'] = 'Session Expired! Please login again to continue.';
			echo json_encode($result);
			exit();
		}
	
		// Retrieve and decode JSON input
		$inputData = json_decode(file_get_contents("php://input"), true);
	
		// Retrieve and validate inputs
		$user_id = $inputData['user'] ?? '';
		if(strlen($user_id) == 0) {
			$result['status'] = 1;
			$result['form'] = 'Invalid request. Please refresh the page and try again.';
			echo json_encode($result);
			exit();
		}
	
		// Additional data extraction
		$world_region_id = $inputData['world_region_id'] ?? null;
		$major_region_id = $inputData['major_region_id'] ?? null;
		$country_id = $inputData['country_id'] ?? null;
		$states = $inputData['states'] ?? [];
		$projects = $inputData['projects'] ?? [];
	
		// Clear previous mappings for the user
		$this->db->where('user_id', $user_id);
		$this->db->delete('tbl_user_unit_location');
		$this->db->where('user_id', $user_id);
		$this->db->delete('tbl_user_projects_sites');
	
		// Insert new location mappings
		foreach ($states as $state) {
			foreach ($state['districts'] as $district) {
				$this->db->insert('tbl_user_unit_location', array(
					'user_id' => $user_id,
					'world_region_id' => $world_region_id,
					'major_region_id' => $major_region_id,
					'country_id' => $country_id,
					'state_id' => $state['stateId'],
					'district_id' => $district,
					'added_by' => $this->session->userdata('login_id'),
					'added_datetime' => date('Y-m-d H:i:s'),
					'ip_address' => $this->input->ip_address()
				));
			}
		}
	
		// Insert new project mappings
		foreach ($projects as $project) {
			foreach ($project['sites'] as $site) {
				$this->db->insert('tbl_user_projects_sites', array(
					'user_id' => $user_id,
					'country_id' => $country_id,
					'project_id' => $project['projectId'],
					'site_id' => $site,
					'added_by' => $this->session->userdata('login_id'),
					'added_datetime' => date('Y-m-d H:i:s'),
					'ip_address' => $this->input->ip_address()
				));
			}
		}
	
		$result['updatestatus'] = 1;
		$result['msg'] = 'User mapping completed successfully.';
	
		echo json_encode($result);
		exit();
	}

	public function view()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			if ($role_id == 8) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			//get all user
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');

			if ($role_id == 7) {
				$assigned_locations = $this->db->select(array('user_id', 'world_region_id', 'major_region_id', 'country_id', 'state_id', 'district_id'))->where('user_id', $user_id)->from('tbl_user_unit_location')->get()->result_array();
				$districts = array_unique(array_column($assigned_locations, 'district_id'));
				$user_ids = [];
				if (count($districts) > 0) {
					$assigned_locations_1 = $this->db->select(array('user_id'))->where_in('district_id', $districts)->from('tbl_user_unit_location')->get()->result_array();
					$user_ids = array_unique(array_column($assigned_locations_1, 'user_id'));
				}
				$user_ids = array_diff($user_ids, array($user_id));
				$users = $this->User_model->all_users($user_ids);
			} else {
				$users = $this->User_model->all_users();
			}

			$header_result = array('main_menu' => $main_menu);

			$result = array('users' => $users);

			$this->load->model('Projects_model');
			$world_region = $this->Projects_model->get_world_region();
			$major_region = $this->Projects_model->get_major_region();
			$countries = $this->Projects_model->get_countries();
			$states = $this->Projects_model->get_states();
			$districts = $this->Projects_model->get_districts();
			
			$result['world_region'] = $world_region;
			$result['major_region'] = $major_region;
			$result['countries'] = $countries;
			$result['states'] = $states;
			$result['districts'] = $districts;

			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('user/view', $result);
			$this->load->view('footer');
		}
	}
	//get user details
	public function get_user_details()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}
		
		$result = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
		);
		$result = $this->security->xss_clean($result);
		
		
		$user_details = $this->User_model->get_user_details($_POST['user_id']);
		$result['user_details'] = $user_details;
		// $result['user_details'] = $this->security->xss_clean($user_details);
		echo json_encode($result);
		exit();
	}
		

	public function edit(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			
			if ($role_id == 8) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$result = array();
			//get all user
			
			$users = $this->User_model->all_users();
			$result['users'] = $this->security->xss_clean($users);
			
			$all_roles = $this->User_model->all_roles();
			$result['all_roles'] = $this->security->xss_clean($all_roles);

			// $this->load->model('Projects_model');
			// $projects = $this->Projects_model->all_project();
			// $result['projects'] = $this->security->xss_clean($projects);

			$header_result = array('main_menu' => $main_menu);
			$this->load->view('header', $header_result);
			$this->load->view('user/edit', $result);
			$this->load->view('footer');
		}
	}
	//update user details
	public function update_user_details()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}else{
			$data = array(
				'first_name' => htmlspecialchars($_POST['fname'], ENT_QUOTES),
				'last_name' => htmlspecialchars($_POST['lname'], ENT_QUOTES),
				'email_id' => htmlspecialchars($_POST['email'], ENT_QUOTES),
				'username' => htmlspecialchars($_POST['username'], ENT_QUOTES),
				'role_id' => htmlspecialchars($_POST['role'], ENT_QUOTES),
			);

			$check_emaiid = $this->db->where('email_id', $data['email_id'])->where('user_id !=', $_POST['user_id'])->get('tbl_users')->num_rows();
			$check_username = $this->db->where('username', $data['username'])->where('user_id !=', $_POST['user_id'])->get('tbl_users')->num_rows();

			if($check_emaiid == 1 || $check_username == 1){
				$result = array(
					'msg' => 'Either email or username already exist.',
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'status' => 0
				);
			} else {
				$data = $this->security->xss_clean($data);
				$update_user = $this->db->where('user_id', $_POST['user_id'])->update('tbl_users', $data);
				if($update_user){
					$result = array(
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash(),
						'msg' => 'User Updated successfully !', 
						'status' => 1
					);
				} else {
					$result = array(
						'msg' => 'Something went wrong. Please try again later',
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash(),
						'status' => 0
					);
				}
			}
			$result = $this->security->xss_clean($result);
			echo json_encode($result);
			exit();
		}
	}
	//update user details professional
	public function update_user_details_professional()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		$error = array(
			'status' => 0,
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		$role = $this->input->post('user_role');
		if(empty($role)) {
			$error['user_role'] = 'Role selection is mandatory.';
			$error['status'] = 1;
		}

		$projects = $this->input->post('user_project');
		if(!$projects || count($projects) == 0) {
			$error['user_project'] = 'Project selection is mandatory.';
			$error['status'] = 1;
		}

		$partner = $this->input->post('user_agency');
		if(empty($partner)) {
			$error['user_agency'] = 'Agency selection is mandatory.';
			$error['status'] = 1;
		}

		$villages = $this->input->post('villages');
		if(!$villages || count($villages) == 0) {
			$error['villages'] = 'Location selection is mandatory.';
			$error['status'] = 1;
		}

		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}

		date_default_timezone_set("UTC");
		$data = array(
			'role_id' => $_POST['user_role']
		);
		$data = $this->security->xss_clean($data);
		$update_user = $this->db->where('user_id', $_POST['user_id'])->update('tbl_users', $data);
		if($update_user){
			// Delete all the old mappings
			$this->db->where('user_id', $_POST['user_id'])->where('lkp_project_id', 1)->delete('rpt_project_partner_user_location');
			// Insert new mappings
			foreach ($projects as $key => $project) {
				foreach ($villages as $key => $village) {
					$location = $this->db->where('village_id', $village)->get('lkp_village')->row_array();
					$mapuser = array(
						'lkp_project_id' => htmlspecialchars($project, ENT_QUOTES),
						'lkp_partner_id' => htmlspecialchars($this->input->post('user_agency'), ENT_QUOTES),
						'user_id' => $_POST['user_id'],
						'lkp_country_id' => $location['country_id'],
						'lkp_state_id' => $location['state_id'],
						'lkp_district_id' => $location['dist_id'],
						'lkp_block_id' => $location['block_id'],
						'lkp_village_id' => $location['village_id'],
						'added_by' => $this->session->userdata('login_id'),
						'added_datetime' => date('Y-m-d H:i:s'),
						'ip_address' => $this->input->ip_address(),
						'project_user_loc_status' => 1
					);
					$insert = $this->db->insert('rpt_project_partner_user_location', $mapuser);
				}
			}
			
			$result = array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'User Details Updated successfully !', 
				'updatestatus' => 1
			);
		} else {
			$result = array(
				'msg' => 'Something went wrong. Please try again later',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'updatestatus' => 0
			);
		}

		echo json_encode($result);
		exit();
	}

	public function delete(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			
			if ($role_id == 8) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			//get all user
			if ($role_id == 7) {
				$assigned_locations = $this->db->select(array('user_id', 'world_region_id', 'major_region_id', 'country_id', 'state_id', 'district_id'))->where('user_id', $user_id)->from('tbl_user_unit_location')->get()->result_array();
				$districts = array_unique(array_column($assigned_locations, 'district_id'));
				$user_ids = [];
				if (count($districts) > 0) {
					$assigned_locations_1 = $this->db->select(array('user_id'))->where_in('district_id', $districts)->from('tbl_user_unit_location')->get()->result_array();
					$user_ids = array_unique(array_column($assigned_locations_1, 'user_id'));
				}
				$user_ids = array_diff($user_ids, array($user_id));
				$users = $this->User_model->all_users($user_ids);
			} else {
				$users = $this->User_model->all_users();
			}
			$header_result = array('main_menu' => $main_menu);

			$result = array('users' => $users);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('user/delete', $result);
			$this->load->view('footer');
		}
	}
	//delete User
	public function delete_user()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}else{
			$delete_user = $this->db->where('user_id', $_POST['user_id'])->update('tbl_users', array('status' => $_POST['status']));
			if($delete_user){
				$result = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg' => 'User Updated Successfully!',
					'status'=> 1
				);
			} else {
				$result = array(
					'msg' => 'Something went wrong. Please try again later !',
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'status'=> 0
				);
			}
			echo json_encode($result);
			exit();
		}
	}

	//reset user password
	public function reset_user_password()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}

		$password = 'Mpro@123';
		$salt = bin2hex(random_bytes(32));
		$saltedPW = $password.$salt;
		$hashedPW = hash('sha256', $saltedPW);
		$reset_user_password = $this->db->where('user_id', $_POST['user_id'])->update('tbl_users', array(
			'password' => $hashedPW,
			'salt' => $salt
		));

		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'msg' => 'User Password Updated Successfully!',
			'status'=> 1
		));
		exit();
	}

	public function role_capabilities()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			
			if ($role_id != 1) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			
			$roles_list = $this->User_model->get_rolelist();
			$capability_list = $this->User_model->get_capabilitylist();

			$result = array('roles_list' => $roles_list, 'capability_list' => $capability_list);

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('user/roles_capabilities', $result);
			$this->load->view('footer');
		}
	}
	public function update_permissions()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}else{
			
			$update_permissions = $this->User_model->update_permissions();
			
			if(!$update_permissions){
				echo json_encode(array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg'=>'Sorry! Please try after sometime.',
					'status' => 0
				));
				exit();
			}else{
				echo json_encode(array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg'=>'Permission updated successfully.',
					'status' => 1
				));
				exit();
			}
		}
	}

	public function add_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			
			if ($role_id != 1) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			
			$roles_list = $this->User_model->get_rolelist();

			$result = array('roles_list' => $roles_list);

			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('user/add_role', $result);
			$this->load->view('footer');
		}
	}
	public function insert_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}else{
			
			$insert_role = $this->User_model->insert_role();
			
			if(!$insert_role){
				echo json_encode(array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg'=>'Sorry! Please try after sometime.',
					'status' => 0
				));
				exit();
			}else{
				echo json_encode(array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg'=>'Role added successfully.',
					'status' => 1
				));
				exit();
			}
		}
	}

	public function edit_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			
			if ($role_id != 1) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			//get all roles
			
			$roles_list = $this->User_model->get_rolelist();

			$result = array('roles' => $roles_list);			
			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('user/edit_role', $result);
			$this->load->view('footer');
		}
	}
	public function update_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}else{
			//declare vriables
			$role_id = $this->input->post('role_id');
			$role_name = htmlspecialchars($this->input->post('role_name'), ENT_QUOTES);
			$role_description = htmlspecialchars($this->input->post('role_description'), ENT_QUOTES);

			//declare role data array
			$role_data = array(
				'role_name' => $role_name,
				'role_description' => $role_description
			);
			$role_data = $this->security->xss_clean($role_data);
			//update role name and description
			$update_role = $this->db->where('role_id', $role_id)->update('tbl_role', $role_data);
			//check role updated or not
			if($update_role){
				$result = array(
					'status' => 1,
					'msg' => 'Role updated successfully.',
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			} else {
				$result = array(
					'status' => 0,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg' => 'Something went wrong, please try again later',
				);
			}
			echo json_encode($result);
			exit();
		}
	}

	public function delete_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{		
					
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');
			
			if ($role_id != 1) {
				$baseurl = base_url();
				redirect($baseurl);
			}
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			//get all roles
			
			$roles_list = $this->User_model->get_rolelist();
	
			$result = array('roles' => $roles_list);
			$header_result = array('main_menu' => $main_menu);
			$result = $this->security->xss_clean($result);
		    $this->load->view('header', $header_result);
		    $this->load->view('user/delete_role', $result);
		    $this->load->view('footer');
		}
	}
	public function remove_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'status' => 0
			));
			exit();
		}else{
			//check this role is assigned to someone or not
			$this->db->select();
			$this->db->from('tbl_users');
			$this->db->where('role_id', $_POST['role_id']);
			$this->db->where('status',1);
			$check = $this->db->get()->num_rows();
			
			if($check == 0) {
				$delete_role = $this->db->where('role_id', $_POST['role_id'])->update('tbl_role', array(
				'status' => 0));
				if($delete_role){
					$result = array(
						'status' => 1,
						'msg' => 'Role Deleted successfully.',
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash(),
					);
				} else {
					$result = array(
						'status' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash(),
						'msg' => 'Something went wrong, please try again later'
					);
				}
			} else {
				$result = array(
					'status' => 2,
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'msg' => 'This role could not be deleted, role is assigned to some user !'
				);
			}
			echo json_encode($result);
			exit();
		}
	}

	//date august 11 2020, Niranjan

	public function track(){
		redirect('dashboard/view_dashboard');

		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}

		$result = array();
		
		$result['all_users'] = $this->User_model->all_users();
		
		$this->load->model('Dynamicmenu_model');
		$main_menu = $this->Dynamicmenu_model->menu_details();

		$header_result = array('main_menu' => $main_menu);

		$this->load->view('header', $header_result);
		// $this->load->view('header');
		// $this->load->view('sidebar');
		$this->load->view('user/track', $result);
		$this->load->view('footer');
	}

	public function get_circlebydivision(){
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}

		$division_ids = $this->input->post('division_ids');

		
		$get_circlebydivision = $this->Helper_model->all_circles($division_ids);

		$result['get_circlebydivision'] = $get_circlebydivision;
		$result['status'] = 1;

		echo json_encode($result);
		exit();
	}

	public function get_user_checkin_checkout_data(){
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}

		$user_ids = $this->input->post('user_ids');
		$date = $this->input->post('date');

		$data = array(
			'user_ids' => $user_ids,
			'date' => $date
		);

		
		$get_user_checkin_checkout_data = $this->User_model->get_user_checkin_checkout_data($data);

		$result['get_user_checkin_checkout_data'] = $get_user_checkin_checkout_data;
		$result['status'] = 1;

		echo json_encode($result);
		exit();
	}
}