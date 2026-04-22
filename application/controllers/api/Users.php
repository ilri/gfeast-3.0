<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Controller {

	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Content-Type: Application/json");
		header("Accept: application/json");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}

		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('user_agent');
	}

	//Load Methods According to Client Request
	public function index()
	{
		$data = (array)json_decode(file_get_contents("php://input"));
		if(!isset($data)) {
			if(!isset($data['login_id']) || (strlen($data['login_id']) === 0)) {
				$this->jsonify(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
			} else if(!isset($data['purpose']) || (strlen($data['purpose']) === 0))  $this->bad_request();
		}

		// Load the method according to purpose
		switch ($data['purpose']) {
			case 'set_profile_image':
				$this->set_profile_image($data);
			break;
			case 'set_client_image':
				$this->set_profile_image($data);
			break;
			
			case 'get_roles':
				$this->get_roles($data);
			break;
			case 'get_agencies':
				$this->get_agencies($data);
			break;
			
			case 'create_user':
				$this->create_user($data);
			break;

			case 'get_users_witout_status':
				$this->get_users_witout_status($data);
			break;
			case 'get_user_details':
				$this->get_user_details($data);
			break;
			case 'update_user_details':
				$this->update_user_details($data);
			break;
			case 'change_user_status':
				$this->change_user_status($data);
			break;
			case 'reset_user_password':
				$this->reset_user_password($data);
			break;

			case 'get_mapping_details':
				$this->get_mapping_details($data);
			break;
			case 'get_user_locations':
				$this->get_user_locations($data);
			break;
			case 'update_user_mapping':
				$this->update_user_mapping($data);
			break;

			default:
				$this->bad_request();
			break;
		}
	}

	public function bad_request()
	{
		$this->jsonify(array(
			'status' => 0,
			'msg' => 'Bad Request...'
		));
	}
	public function jsonify($data)
	{
		echo(json_encode($data));
		exit();
	}

	// Upload and Set Profile Image
	private function set_profile_image($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['base64'])) $this->bad_request();
		
		date_default_timezone_set("UTC");
		if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/user/');

		$ext = '.jpg';
		$image = $data['base64'];
		$mimeType = explode(';', $image);
		switch ($mimeType[0]) {
			case 'data:image/*':
				$crop = str_replace('data:image/*;charset=utf-8;base64,', '', $image);
				break;

			case 'data:image/jpeg':
				$crop = str_replace('data:image/jpeg;base64,', '', $image);
				$ext = '.jpeg';
				break;

			case 'data:image/png':
				$crop = str_replace('data:image/png;base64,', '', $image);
				$ext = '.png';
				break;

			default:
				$crop = $image;
				break;
		}
		$crop = str_replace(' ', '+', $crop);
		$cropdata = base64_decode($crop);
		$file = uniqid() . $data['login_id'] . $ext;
		$url = UPLOAD_DIR . $file;

		// Upload image to serrver folder
		file_put_contents(UPLOAD_DIR . $file, $cropdata);

		// Clear all previous images of user
		$this->db->where('user_id', $data['login_id'])->update('tbl_images', array(
			'status' => 0
		));
		
		// Insert new record
		$insert = $this->db->insert('tbl_images', array(
			'user_id' => $data['login_id'],
			'image' => $file,
			'original_image' => $file,
			'ip_address' => $this->input->ip_address(),
			'regdate' => date('Y-m-d H:i:s'),
			'status' => 1
		));

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'filename' => $file
		));
	}
	// Upload and Set Client Image
	private function set_client_image($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['base64']) || !isset($data['client_id'])) $this->bad_request();
		
		date_default_timezone_set("UTC");
		if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/client/');

		$ext = '.jpg';
		$image = $data['base64'];
		$mimeType = explode(';', $image);
		switch ($mimeType[0]) {
			case 'data:image/*':
				$crop = str_replace('data:image/*;charset=utf-8;base64,', '', $image);
				break;

			case 'data:image/jpeg':
				$crop = str_replace('data:image/jpeg;base64,', '', $image);
				$ext = '.jpeg';
				break;

			case 'data:image/png':
				$crop = str_replace('data:image/png;base64,', '', $image);
				$ext = '.png';
				break;

			default:
				$crop = $image;
				break;
		}
		$crop = str_replace(' ', '+', $crop);
		$cropdata = base64_decode($crop);
		$file = uniqid() . $ext;
		$url = UPLOAD_DIR . $file;

		// Upload image to serrver folder
		file_put_contents(UPLOAD_DIR . $file, $cropdata);

		// update images of client
		$this->db->where('UNIT_ID', $data['client_id'])->update('lkp_unit', array(
			'image' => $file
		));

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'filename' => $file
		));
	}

	public function get_roles($data)
	{
		if(!$data) $this->bad_request();

		$this->load->model('User_model');
		$all_roles = $this->User_model->all_roles_api($data);

		foreach ($all_roles as $key => $role) {
			if(!is_null($role['can_add'])) {
				$roles = explode(',', $role['can_add']);
				
				$this->db->select('role_id, role_name');
				$this->db->where_in('role_id', $roles)->where('status', 1);
				$all_roles[$key]['can_add'] = $this->db->get('tbl_role')->result_array();
			} else {
				$all_roles[$key]['can_add'] = [];
			}
		}

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'all_roles' => $all_roles
		));
	}
	public function get_agencies($data)
	{
		if(!$data) $this->bad_request();

		$this->db->select('la.agency_id, la.agency_name, lac.client_id');
		$this->db->join('lkp_agency_client AS lac', 'lac.agency_id = la.agency_id');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->join('lkp_agency_user AS lau', 'lau.agency_id = la.agency_id');
			$this->db->where('lau.status', 1)->where('lau.user_id', $data['login_id']);
		}
		$all_agencies = $this->db->where('la.status', 1)->get('lkp_agency AS la')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'all_agencies' => $all_agencies
		));
	}
	
	public function create_user($data)
	{
		if(!$data) $this->bad_request();
		
		$this->load->model('User_model');
		$error = array(
			'status' => 1
		);

		$fname = $data['first_name'];
		if(empty($fname)) {
			$error['first_name'] = 'First name is mandatory.';
			$error['status'] = 0;
		}

		$lname = $data['last_name'];
		if(empty($lname)) {
			$error['last_name'] = 'Last name is mandatory.';
			$error['status'] = 0;
		}

		$role = $data['user_role'];
		if(empty($role)) {
			$error['user_role'] = 'Role selection is mandatory.';
			$error['status'] = 0;
		}

		$email = $data['email'];
		if(empty($email)) {
			$error['email'] = 'Email is mandatory.';
			$error['status'] = 0;
		} else {
			$check_emaiid = $this->User_model->check_emaiid_api($data);

			if($check_emaiid) {
				if($check_emaiid['status'] == 0) {
					$error['email'] = 'Email has been blocked. Please contact admin for more details.';
					$error['status'] = 0;
				} else {
					$error['email'] = 'Email already exists.';
					$error['status'] = 0;
				}
			}
		}

		$username = $data['username'];
		if(empty($username)) {
			$error['username'] = 'Username is mandatory.';
			$error['status'] = 0;
		} else {
			$check_username = $this->User_model->check_username_api($data);

			if($check_username) {
				if($check_username['status'] == 0) {
					$error['username'] = 'Username has been blocked. Please contact admin for more details.';
					$error['status'] = 0;
				} else {
					$error['username'] = 'Username already exists.';
					$error['status'] = 0;
				}
			}
		}

		$password = $data['password'];
		if(empty($password)) {
			$error['password'] = 'Password is mandatory.';
			$error['status'] = 0;
		}

		$cpassword = $data['cpassword'];
		if(empty($cpassword)) {
			$error['cpassword'] = 'Confirm password is mandatory.';
			$error['status'] = 0;
		}

		if(!empty($password) && !empty($cpassword) && $password != $cpassword) {
			$error['password'] = 'Both password and confirm password should be same.';
			$error['cpassword'] = 'Both password and confirm password should be same.';
			$error['status'] = 0;
		}

		$client = $data['client'];
		if(empty($role)) {
			$error['client'] = 'Client selection is mandatory.';
			$error['status'] = 0;
		}

		if($error['status'] == 0) {
			$error['type'] = 'fields';
			echo json_encode($error);
			exit();
		}

		$insert_user = $this->User_model->insert_user_api($data);
		if(!$insert_user){
			$this->jsonify(array(
				'msg'=>'Sorry! Please try after sometime.',
				'status' => 0
			));
		}

		$agency = (isset($data['agency']) && !is_null($data['agency'])) ? $data['agency'] : NULL;
		// Set agency and client of user
		$this->db->insert('lkp_agency_user', array(
			'user_id' => $insert_user['user_id'],
			'client_id' => $client,
			'agency_id' => $agency,
			'status' => 1
		));

		$this->jsonify(array(
			'msg'=>'User added successfully.',
			'status' => 1
		));
	}

	public function get_users_witout_status($data)
	{
		if(!$data) $this->bad_request();

		$this->load->model('User_model');
		$all_users = $this->User_model->all_users_without_status_api($data);

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'users' => $all_users
		));
	}
	public function get_user_details($data)
	{
		if(!$data) $this->bad_request();
		
		// Get User details
		$this->db->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, users.phone, users.designation, role.role_id, role.role_name');
		$this->db->from('tbl_users as users');
		$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
		$this->db->where('users.user_id', $data['user_id']);
		$this->db->where('role.status', 1);
		$user_details = $this->db->get()->row_array();

		// Get client and agency details
		$user_details['agency'] = $this->db->where('user_id', $data['user_id'])->where('status', 1)->get('lkp_agency_user')->row_array();
		if(is_null($user_details['agency']) || is_null($user_details['agency']['agency_id'])) $user_details['role_type'] = 1;
		else $user_details['role_type'] = 2;
		
		// Return data
		$this->jsonify(array(
			'status' => 1,
			'user_details' => $user_details
		));
	}
	public function update_user_details($data)
	{
		if(!$data) $this->bad_request();
		
		$userData = array(
			'first_name' => htmlspecialchars($data['fname'], ENT_QUOTES),
			'last_name' => htmlspecialchars($data['lname'], ENT_QUOTES),
			'email_id' => htmlspecialchars($data['email'], ENT_QUOTES),
			'username' => htmlspecialchars($data['username'], ENT_QUOTES),
			'role_id' => htmlspecialchars($data['role'], ENT_QUOTES),
			'phone' => htmlspecialchars($data['phone'], ENT_QUOTES),
			'designation' => htmlspecialchars($data['designation'], ENT_QUOTES),
		);
		$userData = $this->security->xss_clean($userData);
		
		$update_user = $this->db->where('user_id', $data['user_id'])->update('tbl_users', $userData);
		if($update_user){
			// Clear all previous agency of user
			$this->db->where('user_id', $data['user_id'])->delete('lkp_agency_user');
			// Set new agency and client of user
			$agency = (isset($data['agency_id']) && !is_null($data['agency_id'])) ? $data['agency_id'] : NULL;
			$this->db->insert('lkp_agency_user', array(
				'user_id' => $data['user_id'],
				'client_id' => $data['client_id'],
				'agency_id' => $data['agency_id'],
				'status' => 1
			));
			
			$result = array(
				'msg' => 'User Updated successfully !', 
				'status' => 1
			);
		} else {
			$result = array(
				'msg' => 'Something went wrong. Please try again later',
				'status' => 0
			);
		}
		
		// Return data
		$this->jsonify($result);
	}
	public function change_user_status($data)
	{
		if(!$data) $this->bad_request();
		
		$update_user = $this->db->where('user_id', $data['user_id'])->update('tbl_users', array('status' => $data['status']));
		if($update_user){
			$result = array(
				'msg' => 'User Updated Successfully!',
				'status'=> 1
			);
		} else {
			$result = array(
				'msg' => 'Something went wrong. Please try again later !',
				'status'=> 0
			);
		}
		
		// Return data
		$this->jsonify($result);
	}
	public function reset_user_password($data)
	{
		if(!$data) $this->bad_request();

		$password = 'Mpro@123';
		$salt = bin2hex(random_bytes(32));
		$saltedPW = $password.$salt;
		$hashedPW = hash('sha256', $saltedPW);
		$reset_user_password = $this->db->where('user_id', $data['user_id'])->update('tbl_users', array(
			'password' => $hashedPW,
			'salt' => $salt
		));

		// Return data
		$this->jsonify(array(
			'msg' => 'User Password Updated Successfully!',
			'status'=> 1
		));
	}

	public function get_mapping_details($data)
	{
		if(!$data) $this->bad_request();
		if(!isset($data['type'])) $this->bad_request();

		$result = array('status' => 1);
		switch ($data['type']) {
			case 'client':
				// Get all clients
				$this->load->model('Helper_model');
				$all_units = $this->Helper_model->all_units();

				$result['all_units'] = $all_units;
			break;

			case 'agency':
				if(!isset($data['client_id']) || (strlen($data['client_id']) === 0)) $this->bad_request();
				
				$client_id = $data['client_id'];
				// Get all agencies according to client
				$this->db->select('la.*')->from('lkp_agency AS la');
				$this->db->join('lkp_agency_client AS lac', 'lac.agency_id = la.agency_id');
				$this->db->where('la.status', 1)->where('lac.status', 1)->where('lac.client_id', $client_id);
				$all_agencies = $this->db->get()->result_array();

				$result['all_agencies'] = $all_agencies;
			break;

			case 'user':
				if(!isset($data['client_id']) || (strlen($data['client_id']) === 0)) $this->bad_request();
				if(!isset($data['agency_id']) || (strlen($data['agency_id']) === 0)) $this->bad_request();
				
				$agency_id = $data['agency_id'];
				// Get all users according to agency
				$this->db->distinct()->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, users.phone, role.role_id, role.role_name');
				
				$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
				$this->db->join('lkp_agency_user AS lau', 'lau.user_id = users.user_id', 'left');
				
				if($data['login_role'] != 1 && $data['login_role'] != 2) {
					$this->db->where('users.added_by', $this->session->userdata('login_id'));
				}
				$this->db->where('users.status', 1)->where('role.status', 1);
				$this->db->where('lau.client_id', $data['client_id']);
				if($agency_id != 0) {
					$this->db->where('lau.status', 1)->where('lau.agency_id', $agency_id);
				} else {
					$this->db->where('lau.agency_id IS NULL');
				}
				$all_users = $this->db->get('tbl_users as users')->result_array();

				$result['all_users'] = $all_users;
			break;
			
			default:
				$this->bad_request();
			break;
		}

		// Return data
		$this->jsonify($result);
	}
	public function get_user_locations($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['client']) || !isset($data['agency']) || !isset($data['user_id'])) $this->bad_request();

		$client = $data['client'];
		$agency = $data['agency'];
		$user_id = $data['user_id'];
		
		if(strlen($client) == 0 || strlen($user_id) == 0) $this->bad_request();
		if(strlen($agency) == 0 || $agency == 0) $agency = NULL;

		$result = array('status' => 1);
		ini_set('memory_limit', '-1');

		// Get all locations
		$this->load->model('Helper_model');
		$states = $this->Helper_model->all_client_states($client);
		// $districts = $this->Helper_model->all_districts();
		// $tehsils = $this->Helper_model->all_tehsils();
		// $blocks = $this->Helper_model->all_blocks();
		// $gps = $this->Helper_model->all_gps();
		// $villages = $this->Helper_model->all_villages();
		foreach ($states as $skey => $state) {
			$districts = $this->Helper_model->all_client_districts($state['state_id'], $client);
			
			foreach ($districts as $dkey => $dist) {
				$tehsils = $this->Helper_model->all_tehsils($dist['district_id']);

				foreach ($tehsils as $tkey => $tehsil) {
					$blocks = $this->Helper_model->all_blocks($tehsil['tehsil_id']);

					// foreach ($blocks as $bkey => $block) {
					// 	$gps = $this->Helper_model->all_gps($block['block_id']);

					// 	foreach ($gps as $gkey => $gp) {
					// 		$villages = $this->Helper_model->all_villages($gp['grampanchayat_id']);
							
					// 		$gps[$gkey]['villages'] = $villages;
					// 	}
						
					// 	$blocks[$bkey]['gps'] = $gps;
					// }
					
					$tehsils[$tkey]['blocks'] = $blocks;
				}
				
				$districts[$dkey]['tehsils'] = $tehsils;
			}

			$states[$skey]['districts'] = $districts;
		}
		$result['states'] = $states;
		
		// Get locations assigned to user
		$this->db->select('state_id, district_id, tehsil_id, block_id, grampanchayat_id, village_id');
		if(!is_null($agency)) {
			$this->db->where('agency_id', $agency);
		} else {
			$this->db->where('agency_id IS NULL');
		}
		$this->db->where('UNIT_ID', $client)->where('user_id', $user_id);
		$assignedLoc = $this->db->where('status', 1)->get('tbl_user_unit_location');
		if($assignedLoc->num_rows() === 0) { $selectedLoc = array(); }
		else { $selectedLoc = array(
			'states' => array(),
			'districts' => array(),
			'tehsils' => array(),
			'blocks' => array(),
			'gps' => array(),
			'villages' => array()
		); }
		$assignedLoc = $assignedLoc->result_array();
		foreach ($assignedLoc as $key => $loc) {
			if(!in_array($loc['state_id'], $selectedLoc['states'])) array_push($selectedLoc['states'], $loc['state_id']);
			if(!in_array($loc['district_id'], $selectedLoc['districts'])) array_push($selectedLoc['districts'], $loc['district_id']);
			if(!in_array($loc['tehsil_id'], $selectedLoc['tehsils'])) array_push($selectedLoc['tehsils'], $loc['tehsil_id']);
			if(!in_array($loc['block_id'], $selectedLoc['blocks'])) array_push($selectedLoc['blocks'], $loc['block_id']);
			if(!in_array($loc['grampanchayat_id'], $selectedLoc['gps'])) array_push($selectedLoc['gps'], $loc['grampanchayat_id']);
			if(!in_array($loc['village_id'], $selectedLoc['villages'])) array_push($selectedLoc['villages'], $loc['village_id']);
		}
		$result['selectedLoc'] = json_encode($selectedLoc);

		// Return data
		$this->jsonify($result);
	}
	public function update_user_mapping($data){
		date_default_timezone_set("UTC");
		$result = array();

		$user_id = $data['user'];
		$client_id = $data['client'];
		$agency_id = $data['agency'];
		
		if(strlen($user_id) == 0 || strlen($client_id) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Invalid request. Please refresh the page and try again.';

			$this->jsonify($result);
		}
		if(strlen($agency_id) == 0 || $agency_id == 0) $agency_id = NULL;

		$blocks = $data['block'];
		if(!$blocks || count($blocks) == 0) {
			$result['status'] = 0;
			$result['msg'] = 'Select atleast one location to map agency to user.';
		}

		if(isset($result['status']) && ($result['status'] == 0)) {
			$this->jsonify($result);
		}

		// Clear previous maaping
		// Location
		$this->db->where('user_id', $user_id);
		$this->db->delete('tbl_user_unit_location');

		// Get all villages inside the block
		$villages = $this->db->where_in('block_id', $blocks)->where('village_status', 1)->get('lkp_village')->result_array();
		foreach ($villages as $vkey => $village) {
			// Insert new location mappings
			$this->db->insert('tbl_user_unit_location', array(
				'user_id' => $user_id,
				'UNIT_ID' => $client_id,
				'agency_id' => $agency_id,
				'country_id' => $village['country_id'],
				'state_id' => $village['state_id'],
				'district_id' => $village['district_id'],
				'tehsil_id' => $village['tehsil_id'],
				'block_id' => $village['block_id'],
				'grampanchayat_id' => $village['grampanchayat_id'],
				'village_id' => $village['village_id'],
				'added_by' => $this->session->userdata('login_id'),
				'added_datetime' => date('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address()
			));
		}

		$result['status'] = 1;
		$result['msg'] = 'User mapping comleted successfully.';

		// Return data
		$this->jsonify($result);
	}
}