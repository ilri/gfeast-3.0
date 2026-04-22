<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		ob_start();
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->library('user_agent');
	}

	public function index()
	{
		$this->logout();
	}

	// login
	public function login()
	{
		date_default_timezone_set("UTC");
		$error = array('email' => '', 'password' => '', 'form' => '', 'status' => 1);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();
		$baseurl = base_url();
		if(($this->session->userdata('login_id') != null && $this->session->userdata('login_id') != '')) {
			$error['form'] = 'It seems you are already logged in. Please refresh the page to continue to your dashboard.';
			$error['status'] = 0;

			echo json_encode($error);
			exit();
		}

		if(empty($_POST['email'])) {
			$error['email'] = 'Email or Username is required';
			$error['status'] = 0;
		}

		if(empty($_POST['password'])) {
			$error['password'] = 'Password is required';
			$error['status'] = 0;
		}

		if($error['status'] == 0) {
			echo json_encode($error);
			exit();
		}

		$data = array(
			'uuid' => $this->input->post('uuid') ? $this->input->post('uuid') : false,
			'logintype' => $this->input->post('logintype') ? 'ldap' : 'simple',
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password'),
		);
		$this->load->model('Auth_model');
		$logindata = $this->Auth_model->login($data);

		// Define allowed roles
		$allowedRoles = [1, 6, 8];

		if($logindata) {
			if(isset($logindata['msg'])) {
				echo json_encode(array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'form' => $logindata['msg'],
					'status' => 0
				));
			} 
			else if (!in_array($logindata['role'], $allowedRoles)) {
				// Prepare the response data
				$response = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'form' => 'You are not authorized to access this page. Please reach out to the administrator.',
					'status' => 0
				);
	
				// Send JSON response and exit
				echo json_encode($response);
				exit();
			}
			else {
				$newdata = array(
					'login_id' => $logindata['id'],
					'name' => $logindata['name'],
					'role' => $logindata['role'],
					'image' => $logindata['image'],
					'login_time' => date('H:i:s'),
					'main_menu_array' => $logindata['main_menu_array']
				);

				// if(count($logindata['main_menu_array']) === 0) {
				// 	$redirect = $baseurl.'nopermission';
				// } else {
				// 	$redirect = $baseurl.$logindata['landing_page'];
				// }
				// $redirect = $baseurl.'users/track';
				$redirect = $baseurl.'dashboard/view_dashboard';
				
				$newdata['email'] = $logindata['email'];
				$this->session->set_userdata($newdata);

				// Create account activity array
				$accActivity = array(
					'user_id' => $logindata['id'],
					'browser' => $this->agent->browser(),
					'version' => $this->agent->version(),
					'platform' => $this->agent->platform()
				);
				$accActivity = $this->security->xss_clean($accActivity);
				// $stored = $this->Auth_model->store_account_activity($accActivity, $this->input->cookie('ci_sessions', true));

				// if(!$stored) {
				// 	// Clear session if user agent data is not stored
				// 	$this->load->driver('cache');
				// 	$data = array(
				// 		'login_id' => '', 'name' => '', 'role' => '',
				// 		'image' => '', 'login_time' => '', 'main_menu_array' => ''
				// 	);
				// 	$this->session->unset_userdata($data);
				// 	$this->session->sess_destroy();
				// 	$this->cache->clean();
				// 	ob_clean();
					
				// 	echo json_encode(array(
				// 		'csrfName' => $this->security->get_csrf_token_name(),
				// 		'csrfHash' => $this->security->get_csrf_hash(),
				// 		'form' => 'Error while logging in. Please refresh the page and try again.',
				// 		'status' => 0
				// 	));
				// 	exit();
				// }
				
				echo json_encode(array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
					'redirect' => $redirect,
					'status' => 1
				));
			}
			exit();
		} else {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'form' => 'Invalid login credentials. Please contact admin to get access.',
				'status' => 0
			));
			exit();
		}
	}

	public function logout()
	{
		$baseurl = base_url();
		$this->load->driver('cache');
		$page = $this->uri->segment(3);
		//Clear Session Before Starting a New One
		$data = array(
			'login_id' => '',
			'name' => '',
			'role' => '',
			'image' => '',
			'login_time' => '',
			'main_menu_array' => ''
		);
		$this->session->unset_userdata($data);
		$this->session->sess_destroy();
		$this->cache->clean();
		ob_clean();
		redirect($baseurl.$page);
	}
}
