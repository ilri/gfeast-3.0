<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function get_rolelist($data = NULL)
	{
		$default_roles = array(1, 2);

		$this->db->select('role_id, role_name, role_description, added_date, status');
		if(!is_null($data)) { $this->db->where($data); }
		$this->db->where('status', 1)->where_not_in('role_id', $default_roles);
		return $role_list = $this->db->get('tbl_role')->result_array();
	}

	public function get_capabilitylist()
	{
		$this->db->select('role_id, role_name, role_description, added_date, status');
		$role_list = $this->db->where('status', 1)->get('tbl_role')->result_array();

		$capabilitylist = $this->db->select('module_id, module_key, module_name, module_description, slno, added_datetime, status')->where('status', 1)->get('tbl_module')->result_array(); 

		foreach ($capabilitylist as $key => $module) {
			$permissions = $this->db->select('permission_id, module_id, module_key, name, description, slno, added_datetime, status')->where('module_id', $module['module_id'])->where('status', 1)->order_by('slno')->get('tbl_permissions')->result_array();

			foreach ($permissions as $pkey => $permission) {
				foreach ($role_list as $rkey => $role) {
					$check_permission = $this->db->where('role_id', $role['role_id'])->where('module_id', $module['module_id'])->where('permission_id', $permission['permission_id'])->where('status', 1)->get('tbl_role_permissions')->num_rows();
					if($check_permission == 0){
						$permissions[$pkey][$role['role_name']] = 0;
					}else{
						$permissions[$pkey][$role['role_name']] = 1;
					}
				}
			}
			$capabilitylist[$key]['permissions'] = $permissions;
		}
		return $capabilitylist;
	}

	public function all_roles(){
		// $default_roles = array(1, 2);

		$this->db->select('role_id, role_name');
		$this->db->where('role_id >', $this->session->userdata('role'));
		$this->db->where('role_id !=', 1)->where('status', 1);
		return $role_list = $this->db->get('tbl_role')->result_array();
	}
	
	public function all_roles_api($data){
		// $default_roles = array(1, 2);

		$this->db->select('role_id, role_name, can_add');
		$this->db->where('role_id >=', $data['login_role']);
		$this->db->where('role_id !=', 1)->where('status', 1);
		return $role_list = $this->db->get('tbl_role')->result_array();
	}

	public function check_emaiid(){
		$email = $_POST['email'];
		return $check_emaiid = $this->db->where('email_id', $email)->get('tbl_users')->row_array();
	}
	public function check_emaiid_api($data){
		$email = $data['email'];
		return $check_emaiid = $this->db->where('email_id', $email)->get('tbl_users')->row_array();
	}

	public function check_username(){
		$username = $_POST['username'];
		return $check_username = $this->db->where('username', $username)->get('tbl_users')->row_array();
	}
	public function check_username_api($data){
		$username = $data['username'];
		return $check_username = $this->db->where('username', $username)->get('tbl_users')->row_array();
	}

	public function insert_user(){
		date_default_timezone_set("UTC");
		$user_role = $_POST['user_role'];
		$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
		$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
		$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
		// $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];

		$nsalt = bin2hex(random_bytes(32));
		$nsaltedPW = $password.$nsalt;
		$nhashedPW = hash('sha256', $nsaltedPW);

		$insert_data = array(
			'username' => $username,
			'email_id' => $email,
			'password' => $nhashedPW,
			'salt' => $nsalt,
			'first_name' => $first_name,
			'last_name' => $last_name,
			// 'mobile_number' => $phone,
			'role_id' => $user_role,
			'added_by' => $this->session->userdata('login_id'),
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
			'status' => 1
		);
		$insert_data = $this->security->xss_clean($insert_data);
		$query = $this->db->insert('tbl_users', $insert_data);
		if($query) {
			$user = $this->db->insert_id();
			$insertimage = array(
				'user_id' => $user,
				'image' => 'default.png',
				'original_image' => 'default.png',
				'ip_address' => $this->input->ip_address(),
				'regdate' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			$insert = $this->db->insert('tbl_images', $insertimage);
			
			return true;
		} else {
			return false;
		}
	}
	
	public function insert_user_api($data){
		date_default_timezone_set("UTC");
		$user_role = $data['user_role'];
		$first_name = htmlspecialchars($data['first_name'], ENT_QUOTES);
		$last_name = htmlspecialchars($data['last_name'], ENT_QUOTES);
		$email = htmlspecialchars($data['email'], ENT_QUOTES);
		$username = htmlspecialchars($data['username'], ENT_QUOTES);
		$phone = htmlspecialchars($data['phone'], ENT_QUOTES);
		$designation = htmlspecialchars($data['designation'], ENT_QUOTES);
		$password = $data['password'];
		$cpassword = $data['cpassword'];

		$nsalt = bin2hex(random_bytes(32));
		$nsaltedPW = $password.$nsalt;
		$nhashedPW = hash('sha256', $nsaltedPW);

		$insert_data = array(
			'username' => $username,
			'email_id' => $email,
			'password' => $nhashedPW,
			'salt' => $nsalt,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'phone' => $phone,
			'designation' => $designation,
			'role_id' => $user_role,
			'added_by' => $data['login_id'],
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
			'status' => 1
		);
		$insert_data = $this->security->xss_clean($insert_data);
		$query = $this->db->insert('tbl_users', $insert_data);
		if($query) {
			$user = $this->db->insert_id();
			$insertimage = array(
				'user_id' => $user,
				'image' => 'default.png',
				'original_image' => 'default.png',
				'ip_address' => $this->input->ip_address(),
				'regdate' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			$insert = $this->db->insert('tbl_images', $insertimage);
			
			return array('user_id' => $user);
		} else {
			return false;
		}
	}

	//Get all users
	public function all_users($userIds = array()){
		if(count($userIds) === 0) $userIds = array(0);
		$this->db->distinct()->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, users.status, role.role_id, role.role_name, users.added_datetime')->from('tbl_users as users');
		$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
		if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 6) {
			$this->db->where('users.added_by', $this->session->userdata('login_id'));
		}
		if($this->session->userdata('role') == 6) {
			$this->db->where('users.role_id', 8);
		}
		$this->db->where('role.status', 1);
		$users = $this->db->or_where_in('users.user_id', $userIds)->get()->result_array();

		return $users;
	}

	public function all_users_api($data){
		if(!isset($data['userIds']) || (count($data['userIds']) === 0)) $userIds = array(0);
		$this->db->distinct()->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, role.role_id, role.role_name, users.added_datetime')->from('tbl_users as users');
		$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->where('users.added_by', $data['login_id']);
		}
		$this->db->where('users.status', 1)->where('role.status', 1);
		$users = $this->db->or_where_in('users.user_id', $userIds)->get()->result_array();

		return $users;
	}
	//Get all users without status check
	public function all_users_without_status($userIds = array()){
		if(count($userIds) === 0) $userIds = array(0);
		$this->db->distinct()->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, users.status, role.role_id, role.role_name, users.added_datetime')->from('tbl_users as users');
		$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
		if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2) {
			$this->db->where('users.added_by', $this->session->userdata('login_id'));
		}
		$this->db->where('role.status', 1);
		$users = $this->db->or_where_in('users.user_id', $userIds)->get()->result_array();

		return $users;
	}
	public function all_users_without_status_api($data){
		if(!isset($data['userIds']) || (count($data['userIds']) === 0)) $userIds = array(0);
		$this->db->distinct()->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, users.status, role.role_id, role.role_name, users.added_datetime')->from('tbl_users as users');
		$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->where('users.added_by', $data['login_id']);
		}
		$this->db->where('role.status', 1);
		$users = $this->db->or_where_in('users.user_id', $userIds)->get()->result_array();

		return $users;
	}

	//Get user details
	public function get_user_details($user_id){
		$this->db->select('users.user_id, users.username, users.email_id, users.first_name, users.last_name, role.role_id, role.role_name');
		$this->db->from('tbl_users as users');
		$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
		$this->db->where('users.user_id', $user_id);
		$this->db->where('role.status', 1);
		$user_details = $this->db->get()->row_array();
		
		return $user_details;
	}

	//Get user locations
	public function get_user_locations($user_id){
		$this->db->distinct()->select('lc.name AS country, ls.state_name AS state, ld.district_name AS dist, lb.block_name AS block, lv.village_id, lv.village_name AS village');
		$this->db->join('lkp_country AS lc', 'lc.country_id = rppul.lkp_country_id');
		$this->db->join('lkp_state AS ls', 'ls.state_id = rppul.lkp_state_id');
		$this->db->join('lkp_district AS ld', 'ld.district_id = rppul.lkp_district_id');
		$this->db->join('lkp_block AS lb', 'lb.block_id = rppul.lkp_block_id');
		$this->db->join('lkp_village AS lv', 'lv.village_id = rppul.lkp_village_id');
		$this->db->where('rppul.user_id', $user_id)->where('rppul.project_user_loc_status', 1);
		$locations = $this->db->get('rpt_project_partner_user_location AS rppul')->result_array();

		return $locations;
	}

	public function update_permissions()
	{
		date_default_timezone_set("UTC");
		$assigingstatus = $_POST['assigingstatus'];
		$roleid = $_POST['roleid'];
		$moduleid = $_POST['moduleid'];
		$permissionid = $_POST['permissionid'];

		if($assigingstatus == 0){
			$update_data = array(
				'status' => 0
			);

			$this->db->where('role_id', $roleid)->where('module_id', $moduleid)->where('permission_id', $permissionid);
			$updatequery = $this->db->update('tbl_role_permissions', $update_data);

			if(!$updatequery){
				return false;
			}else{
				return true;
			}
		}else{
			$insert_data = array(
				'role_id' => $roleid,
				'module_id' => $moduleid,
				'permission_id' => $permissionid,
				'added_by' => $this->session->userdata('login_id'),
				'added_date' => date('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address(),
				'status' => 1
			);
			$insert_data = $this->security->xss_clean($insert_data);
			$insert_query = $this->db->insert('tbl_role_permissions', $insert_data);

			if(!$insert_query){
				return false;
			}else{
				return true;
			}
		}
	}

	public function insert_role()
	{
		date_default_timezone_set("UTC");
		$copy_role = $_POST['copy_role'];
		$role_name = htmlspecialchars($_POST['role_name'], ENT_QUOTES);
		$role_description = htmlspecialchars($_POST['role_description'], ENT_QUOTES);

		$insert_data = array(
			'role_name' => $role_name,
			'role_description' => $role_description,
			'added_by' => $this->session->userdata('login_id'),
			'added_date' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
			'status' => 1
		);
		$insert_data = $this->security->xss_clean($insert_data);

		$query = $this->db->insert('tbl_role', $insert_data);

		if($query){
			$formlast_insertid = $this->db->insert_id();

			$get_permission_byrole = $this->db->where('role_id', $copy_role)->where('status', 1)->get('tbl_role_permissions')->result_array();

			foreach ($get_permission_byrole as $key => $value) {
				$permission_data = array(
					'role_id' => $formlast_insertid,
					'module_id' => $value['module_id'],
					'permission_id' => $value['permission_id'],
					'added_by' => $this->session->userdata('login_id'),
					'added_date' => date('Y-m-d H:i:s'),
					'ip_address' => $this->input->ip_address(),
					'status' => 1
				);

				$permissions_query = $this->db->insert('tbl_role_permissions', $permission_data);

				if(!$permissions_query){
					return false;
				}
			}
		}else{
			return false;
		}

		return true;
	}

	public function get_user_checkin_checkout_data($data)
	{
		$this->db->select('inout.*, user.first_name, user.last_name');
		$this->db->from('tbl_checkin_checkout as inout');
		$this->db->join('tbl_users as user', 'inout.user_id = user.user_id');
		if(isset($data['user_ids']) && count($data['user_ids']) > 0){
			$this->db->where_in('inout.user_id', $data['user_ids']);
		}
		if(isset($data['date']) && $data['date'] != ''){
			$this->db->where_in('DATE(inout.date_time)', $data['date']);
		}
		$this->db->where('inout_status', 1);
		$location_info = $this->db->get()->result_array();
		
		$location_data = array();
		foreach ($location_info as $key => $location) {
			if($location['type'] == 1){
				$type = "Check in";
			}else{
				$type = "Check out";
			}

			$data = "<h6>User: ".$location['first_name']." ".$location['last_name']."</h6><h6> Check in/Check out: ".$type."</h6>";

			array_push($location_data, array($location['latitude'], $location['longitude'], $data));
		}

		return $location_data;
	}

	public function insert_userregistration(){
		date_default_timezone_set("UTC");
		$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES);
		$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES);
		$email = htmlspecialchars($_POST['email'], ENT_QUOTES);
		$username = htmlspecialchars($_POST['username'], ENT_QUOTES);
		// $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];

		$nsalt = bin2hex(random_bytes(32));
		$nsaltedPW = $password.$nsalt;
		$nhashedPW = hash('sha256', $nsaltedPW);

		$insert_data = array(
			'username' => $username,
			'email_id' => $email,
			'password' => $nhashedPW,
			'salt' => $nsalt,
			'first_name' => $first_name,
			'last_name' => $last_name,
			// 'mobile_number' => $phone,
			'role_id' => 8,
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
			'status' => 2
		);
		$insert_data = $this->security->xss_clean($insert_data);
		$query = $this->db->insert('tbl_users', $insert_data);
		if($query) {
			$user_id = $this->db->insert_id();
			$insertimage = array(
				'user_id' => $user_id,
				'image' => 'default.png',
				'original_image' => 'default.png',
				'ip_address' => $this->input->ip_address(),
				'regdate' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			$this->db->insert('tbl_images', $insertimage);

			return $user_id; // return user_id for activation link
		} else {
			return false;
		}
	}

}