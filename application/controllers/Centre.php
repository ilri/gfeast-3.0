<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centre extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->helper('url');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		$session_allowed = $this->Auth_model->match_account_activity();
		if(!$session_allowed) redirect($baseurl.'auth/logout');
	}
	
	public function index()
	{
		show_404();	
	}

	public function all_centre()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		$this->load->model('Centre_model');
		$all_centre = $this->Centre_model->all_centre();
		echo json_encode(array(
			'status' => 1,
			'all_centre' => $all_centre,
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
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

			$this->load->model('Centre_model');
			$all_centre = $this->Centre_model->all_centre();

			$this->load->model('Helper_model');
			$all_countries = $this->Helper_model->all_countries();

			$header_result = array('main_menu' => $main_menu);

			$result = array(
				'all_centre' => $all_centre,
				'all_countries' => $all_countries
			);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/create', $result);
			$this->load->view('footer');
		}
	}
	public function add_centre()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		$error = array('centre_name' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['centre_name'])) {
			$error['centre_name'] = 'Centre name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['centre_name']) > 250) {
			$error['centre_name'] = 'Centre name should not cross 250 characters in length.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'centre_name' => htmlspecialchars($this->input->post('centre_name'), ENT_QUOTES),
			'added_by' => $this->session->userdata('login_id'),
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address()
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$insertquery = $this->Centre_model->add_centre($data);
		if(!$insertquery) {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'insertstatus' => 0
			));
			exit();
		}

		$countries = $this->input->post('country');
		foreach ($countries as $key => $country) {
			$state = $this->input->post('state')[$key];
			$dist = $this->input->post('dist')[$key];

			$data = array(
				'centre_id' => $insertquery['centre_id'],
				'country' => $country,
				'state' => $state,
				'dist' => $dist
			);
			$this->Centre_model->add_location($data);
		}
		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'msg' => 'Centre Added Successfully',
			'insertstatus' => 1
		));
		exit();
	}

	public function view($value='')
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Centre_model');
			$all_centre = $this->Centre_model->all_centre();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_centre' => $all_centre);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/view', $result);
			$this->load->view('footer');
		}
	}
	public function centre_details()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		if(!$this->input->post('centre_id')) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$centre_id = $this->input->post('centre_id');
		if(strlen($centre_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'centre_id' => $centre_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$details = $this->Centre_model->get_centre_details($data);
		if($details) {
			echo json_encode(array(
				'status' => 1,
				'users' => $details['users'],
				'details' => $details['centre'],
				'partners' => $details['partners'],
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		} else {
			echo json_encode(array(
				'status' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}
	}
	public function centre_locations()
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

		if(!$this->input->post('centre_id')) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$centre_id = $this->input->post('centre_id');
		if(strlen($centre_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'centre_id' => $centre_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$locations = $this->Centre_model->get_centre_locations($data);
		if(count($locations) === 0) {
			echo json_encode(array(
				'status' => 2,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'No location has been assigned to this centre.'
			));
			exit();
		}
		if(!$locations) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.'
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

	public function edit()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Centre_model');
			$all_centre = $this->Centre_model->all_centre();

			$this->load->model('Helper_model');
			$all_countries = $this->Helper_model->all_countries();

			$header_result = array('main_menu' => $main_menu);

			$result = array(
				'all_centre' => $all_centre,
				'all_countries' => $all_countries
			);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/edit', $result);
			$this->load->view('footer');
		}
	}
	public function edit_centre()
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

		if(!$this->input->post('centre_id')) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$centre_id = $this->input->post('centre_id');
		if(strlen($centre_id) == 0) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'centre_id' => $centre_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$details = $this->Centre_model->get_centre_details($data);
		if(!$details) {
			echo json_encode(array(
				'updatestatus' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		$error = array('centre_name' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['centre_name'])) {
			$error['centre_name'] = 'Centre name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['centre_name']) > 250) {
			$error['centre_name'] = 'Centre name should not cross 250 characters in length.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'centre_id' => $this->input->post('centre_id')
			),
			'set' => array(
				'centre_name' => htmlspecialchars($this->input->post('centre_name'), ENT_QUOTES)
			)
		);
		$data = $this->security->xss_clean($data);
		$updatequery = $this->Centre_model->edit_centre($data);
		if(!$updatequery) {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'updatestatus' => 0
			));
			exit();
		}

		$data = array(
			'where' => array(
				'centre_id' => $this->input->post('centre_id')
			)
		);
		$data = $this->security->xss_clean($data);
		$this->Centre_model->delete_location($data);

		$countries = $this->input->post('country');
		foreach ($countries as $key => $country) {
			$state = $this->input->post('state')[$key];
			$dist = $this->input->post('dist')[$key];

			$data = array(
				'centre_id' => $this->input->post('centre_id'),
				'country' => $country,
				'state' => $state,
				'dist' => $dist
			);
			$data = $this->security->xss_clean($data);
			$this->Centre_model->add_location($data);
		}
		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'msg' => 'Centre Updated Successfully',
			'updatestatus' => 1
		));
		exit();
	}

	public function delete()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Centre_model');
			$all_centre = $this->Centre_model->all_centre();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_centre' => $all_centre);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/delete', $result);
			$this->load->view('footer');
		}
	}
	public function delete_centre()
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

		if(!$this->input->post('centre_id')) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$centre_id = $this->input->post('centre_id');
		if(strlen($centre_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'centre_id' => $centre_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$details = $this->Centre_model->get_centre_details($data);
		if(!$details) {
			echo json_encode(array(
				'status' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'centre_id' => $this->input->post('centre_id')
			),
			'set' => array(
				'status' => 0
			)
		);
		$data = $this->security->xss_clean($data);
		$deletequery = $this->Centre_model->edit_centre($data);
		if($deletequery) {
			$this->Centre_model->edit_centre_partner($data);
			$this->Centre_model->edit_centre_batch($data);
			$data = array(
				'where' => array(
					'centre_id' => $this->input->post('centre_id')
				)
			);
			$data = $this->security->xss_clean($data);
			$this->Centre_model->delete_location($data);
			
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Centre Deleted Successfully',
				'status' => 1
			));
			exit();
		} else {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'status' => 0
			));
			exit();
		}
	}

	public function partner_combo()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Centre_model');
			$all_centre = $this->Centre_model->all_centre();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_centre' => $all_centre);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/partner_combo', $result);
			$this->load->view('footer');
		}
	}
	public function manage_partner_combo()
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

		if(!$this->input->post('centre_id')) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$centre_id = $this->input->post('centre_id');
		if(strlen($centre_id) == 0) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'centre_id' => $centre_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$details = $this->Centre_model->get_centre_details($data);
		if(!$details) {
			echo json_encode(array(
				'updatestatus' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		$error = array('partners' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['partners'])) {
			$error['partners'] = 'Partner selection is mandatory.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'centre_id' => $this->input->post('centre_id')
			),
			'set' => array(
				'status' => 0
			)
		);
		$data = $this->security->xss_clean($data);
		$this->Centre_model->edit_centre_partner($data);

		foreach ($this->input->post('partners') as $key => $partner) {
			$data = array(
				'centre_id' => $this->input->post('centre_id'),
				'partner_id' => $partner,
				'added_by' => $this->session->userdata('login_id'),
				'added_datetime' => date('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address()
			);
			$data = $this->security->xss_clean($data);
			$this->Centre_model->add_centre_partner($data);
		}
		echo json_encode(array(
			'msg' => 'Centre And Partner Combination Updated Successfully',
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'updatestatus' => 1
		));
		exit();
	}

	public function user()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Centre_model');
			$all_centre = $this->Centre_model->all_centre();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_centre' => $all_centre);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/user', $result);
			$this->load->view('footer');
		}
	}
	public function manage_user()
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

		if(!$this->input->post('centre_id')) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$centre_id = $this->input->post('centre_id');
		if(strlen($centre_id) == 0) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'centre_id' => $centre_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$details = $this->Centre_model->get_centre_details($data);
		if(!$details) {
			echo json_encode(array(
				'updatestatus' => 0,
				'msg'=>'Sorry! Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}

		$error = array('users' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['users'])) {
			$error['users'] = 'User selection is mandatory.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'centre_id' => $this->input->post('centre_id')
			),
			'set' => array(
				'status' => 0
			)
		);
		$data = $this->security->xss_clean($data);
		$this->Centre_model->edit_centre_user($data);

		foreach ($this->input->post('users') as $key => $user) {
			$data = array(
				'centre_id' => $this->input->post('centre_id'),
				'user_id' => $user,
				'added_by' => $this->session->userdata('login_id'),
				'added_datetime' => date('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address()
			);
			$data = $this->security->xss_clean($data);
			$this->Centre_model->add_centre_user($data);
		}
		echo json_encode(array(
			'msg' => 'Centre And User Combination Updated Successfully',
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'updatestatus' => 1
		));
		exit();
	}

	public function batch()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Centre_model');
			$all_batch = $this->Centre_model->all_batch();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_batch' => $all_batch);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('centre/batch', $result);
			$this->load->view('footer');
		}
	}
	public function add_batch()
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

		$error = array('batch_name' => '', 'centre' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['batch_name'])) {
			$error['batch_name'] = 'Batch name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['batch_name']) > 20) {
			$error['batch_name'] = 'Batch name should not cross 20 characters in length.';
			$error['status'] = 1;
		}

		if(empty($_POST['centre'])) {
			$error['centre'] = 'Centre selection is mandatory.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}

		date_default_timezone_set('UTC');
		$data = array(
			'batch_name' => htmlspecialchars($this->input->post('batch_name'), ENT_QUOTES),
			'centre_id' => $this->input->post('centre'),
			'added_by' => $this->session->userdata('login_id'),
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address()
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Centre_model');
		$this->Centre_model->add_centre_batch($data);
		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'msg' => 'Batch Created Successfully',
			'insertstatus' => 1
		));
		exit();
	}
}