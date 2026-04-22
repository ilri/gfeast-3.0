<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_management extends CI_Controller {
	
	function _construct(){
		parent::_construct();
		$this->load->helper('url');
	}

	//View users list
	public function view_users(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Usermanagement_model');
			$user_list = $this->Usermanagement_model->get_userslist();

			$roles_list = $this->Usermanagement_model->get_rolelist();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$result = array('user_list' => $user_list, 'roles_list' => $roles_list);

			$header_result = array('main_menu' => $main_menu);

		    $this->load->view('header', $header_result);
		    $this->load->view('user_management/users_list', $result);
		    $this->load->view('footer');
		}
	}

	//add user based on role
	public function add_new_user()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Usermanagement_model');
			$roles_list = $this->Usermanagement_model->get_rolelist();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$result = array('roles_list' => $roles_list);

			$header_result = array('main_menu' => $main_menu);

		    $this->load->view('header', $header_result);
		    $this->load->view('user_management/add_new_user', $result);
		    $this->load->view('footer');
		}
	}

	public function insert_user()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}else{
			$this->load->model('Usermanagement_model');
			$check_emaiid = $this->Usermanagement_model->check_emaiid();

			$this->load->model('Usermanagement_model');
			$check_username = $this->Usermanagement_model->check_username();

			if(count($check_emaiid) > 0 && $check_emaiid['status'] == 0){
				echo json_encode(array('msg'=>'Email id already exists but blocked by some other user please contact admin.', 'status' => 0));
	            exit();
			}else{
				if(count($check_emaiid) > 0){
					echo json_encode(array('msg'=>'Email id already exists', 'status' => 0));
	            	exit();
				}
			}

			if(count($check_username) > 0 && $check_username['status'] == 0){
				echo json_encode(array('msg'=>'Username already exists but blocked by some other user please contact admin.', 'status' => 0));
	            exit();
			}else{
				if(count($check_username) > 0){
					echo json_encode(array('msg'=>'Username already exists', 'status' => 0));
	            	exit();
				}
			}
			
			$this->load->model('Usermanagement_model');
			$insert_user = $this->Usermanagement_model->insert_user();
			if(!$insert_user){
				echo json_encode(array('msg'=>'Sorry! Please try after sometime.','status' => 0));
	            exit();
			}else{
				echo json_encode(array('msg'=>'User added successfully.','status' => 1));
	            exit();
			}
		}
	}

	public function roles_capabilities()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Usermanagement_model');
			$roles_list = $this->Usermanagement_model->get_rolelist();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$capability_list = $this->Usermanagement_model->get_capabilitylist();

			$result = array('roles_list' => $roles_list, 'capability_list' => $capability_list);

			$header_result = array('main_menu' => $main_menu);

		    $this->load->view('header', $header_result);
		    $this->load->view('user_management/roles_capabilities', $result);
		    $this->load->view('footer');
		}
	}

	public function add_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Usermanagement_model');
			$roles_list = $this->Usermanagement_model->get_rolelist();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$result = array('roles_list' => $roles_list);

			$header_result = array('main_menu' => $main_menu);

		    $this->load->view('header', $header_result);
		    $this->load->view('user_management/add_role', $result);
		    $this->load->view('footer');
		}
	}

	public function insert_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}else{
			$this->load->model('Usermanagement_model');
			$insert_role = $this->Usermanagement_model->insert_role();
			
			if(!$insert_role){
				echo json_encode(array('msg'=>'Sorry! Please try after sometime.','status' => 0));
	            exit();
			}else{
				echo json_encode(array('msg'=>'Role added successfully.','status' => 1));
	            exit();
			}
		}
	}

	public function update_permissions()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}else{

			$this->load->model('Usermanagement_model');
			$update_permissions = $this->Usermanagement_model->update_permissions();
			
			if(!$update_permissions){
				echo json_encode(array('msg'=>'Sorry! Please try after sometime.','status' => 0));
	            exit();
			}else{
				echo json_encode(array('msg'=>'Permission updated successfully.','status' => 1));
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
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			//get all roles
			$this->db->select();
			$this->db->from('tbl_role');
			$this->db->where('added_by', $this->session->userdata('login_id'));
			$this->db->where('status', 1);
			$roles = $this->db->get()->result_array();
	
			$result = array('roles' => $roles);			
			$header_result = array('main_menu' => $main_menu);

		    $this->load->view('header', $header_result);
		    $this->load->view('user_management/edit_role', $result);
		    $this->load->view('footer');
		}
	}

	public function delete_role()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			if(isset($_POST['role_id'])){
				//check this role is assigned to someone or not
				$this->db->select();
				$this->db->from('tbl_users');
				$this->db->where('role_id', $_POST['role_id']);
				$this->db->where('status',1);
				$check = $this->db->get()->num_rows();
				
				if($check == 0)
				{
					$delete_role = $this->db->where('role_id', $_POST['role_id'])->update('tbl_role', array(
					'status' => 0));
					if($delete_role){
						$result = array(
							'status' => 1,
							'msg' => 'Role Deleted successfully.'
						);
					} else {
						$result = array(
							'status' => 0,
							'msg' => 'Something went wrong, please try again later'
						);
					}
				} else {
					$result = array(
						'status' => 2,
						'msg' => 'This role could not be deleted, role is assigned to some user !'
					);
				}
				echo json_encode($result);
				exit();
			} else {
				$this->load->model('Dynamicmenu_model');
				$main_menu = $this->Dynamicmenu_model->menu_details();

				//get all roles
				$this->db->select();
				$this->db->from('tbl_role');
				$this->db->where('added_by', $this->session->userdata('login_id'));
				$this->db->where('status', 1);
				$roles = $this->db->get()->result_array();
		
				$result = array('roles' => $roles);
				$header_result = array('main_menu' => $main_menu);

			    $this->load->view('header', $header_result);
			    $this->load->view('user_management/delete_role', $result);
			    $this->load->view('footer');
			}	
		}
	}

	public function update_role(){
		
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}else{
			//declare vriables
			$role_id = $this->input->post('role_id');
			$role_name = $this->input->post('role_name');
			$role_description = $this->input->post('role_description');

			//declare role data array
			$role_data = array(
				'role_name' => $role_name,
				'role_description' => $role_description
			);

			//update role name and description
			$update_role = $this->db->where('role_id', $role_id)->update('tbl_role', $role_data);
			//check role updated or not
			if($update_role){
				$result = array(
					'status' => 1,
					'msg' => 'Role updated successfully.'
				);
			} else {
				$result = array(
					'status' => 0,
					'msg' => 'Something went wrong, please try again later'
				);
			}
			echo json_encode($result);
			exit();
		}
	}

	public function edit_users()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			//get all users
			$this->db->select();
			$this->db->from('tbl_users as users');
			$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
			$this->db->where('users.status', 1);
			$this->db->where('users.added_by', $this->session->userdata('login_id'));
			$this->db->order_by('first_name');
			$all_users = $this->db->get()->result_array();

			$result = array(
				'all_users' => $all_users
			);
			$header_result = array('main_menu' => $main_menu);

		    $this->load->view('header', $header_result);
		    $this->load->view('user_management/edit_users', $result);
		    $this->load->view('footer');
		}
	}

	public function delete_users()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			if(isset($_POST['user_id'])){
				$delete_user = $this->db->where('user_id', $_POST['user_id'])->update('tbl_users', array(
					'status' => 0));
				if($delete_user){
					$result = array(
						'status' => 1,
						'msg' => 'User Deleted successfully.'
					);
				} else {
					$result = array(
						'status' => 0,
						'msg' => 'Something went wrong, please try again later'
					);
				}
				echo json_encode($result);
				exit();
			} else {
				$this->load->model('Dynamicmenu_model');
				$main_menu = $this->Dynamicmenu_model->menu_details();
				//get all users
				$this->db->select();
				$this->db->from('tbl_users as users');
				$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
				$this->db->where('users.status', 1);
				$this->db->where('users.added_by', $this->session->userdata('login_id'));
				$all_users = $this->db->get()->result_array();

				$result = array(
					'all_users' => $all_users
				);
				$header_result = array('main_menu' => $main_menu);

			    $this->load->view('header', $header_result);
			    $this->load->view('user_management/delete_users', $result);
			    $this->load->view('footer');
			}
		}
	}

	public function get_user_data()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}else{
			//get all roles
			$this->db->select();
			$this->db->from('tbl_role');
			$this->db->where('status', 1);
			$all_roles = $this->db->get()->result_array();

			$userId = $this->input->post('user_id');
			//get user details from id
			$this->db->select('users.first_name, users.last_name, users.username, users.email_id, users.user_id, role.role_name, role.role_id, users.added_datetime');
			$this->db->from('tbl_users as users');
			$this->db->join('tbl_role as role', 'role.role_id = users.role_id');
			$this->db->where('users.status', 1);
			$this->db->where('users.user_id', $userId);
			$this->db->where('users.added_by', $this->session->userdata('login_id'));
			$userData = $this->db->get()->row_array();

			$result = array(
				'all_roles' => $all_roles,
				'userData' => $userData,
				'status' => 1
			);

			echo json_encode($result);
			exit(); 
		}
	}


	//update users details
	public function update_user()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'msg' => 'Your session has expired. Please login and try again',
				'status' => 0
			));
			exit();
		}else{
			//users data to update
			$user_data = array(
				'role_id' => $_POST['role'],
				'first_name' => $_POST['first_name'],
				'last_name' => $_POST['last_name'],
				'email_id' => $_POST['email'],
				'username' => $_POST['username']
			);

			//update user query
			$update_user = $this->db->where('user_id', $_POST['user_id'])->update('tbl_users', $user_data);
			if($update_user){
				$result = array(
					'status' => 1,
					'msg' => 'User updated successfully.'
				);
			} else {
				$result = array(
					'status' => 0,
					'msg' => 'Something went wrong, please try again later'
				);
			}
			echo json_encode($result);
			exit();
		}
	}
}