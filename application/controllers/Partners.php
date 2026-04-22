<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->helper('url');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}
	
	public function index()
	{
		show_404();
	}

	public function all_partners()
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

		$this->load->model('Partners_model');
		$all_partners = $this->Partners_model->all_partner();
		echo json_encode(array(
			'status' => 1,
			'all_partners' => $all_partners,
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

			$this->load->model('Partners_model');
			$all_partners = $this->Partners_model->all_partner();

			$this->load->model('Helper_model');
			$all_countries = $this->Helper_model->all_countries();

			$header_result = array('main_menu' => $main_menu);

			$result = array(
				'all_partners' => $all_partners,
				'all_countries' => $all_countries
			);

			$result = $this->security->xss_clean($result);

			$this->load->view('header', $header_result);
			$this->load->view('partners/create', $result);
			$this->load->view('footer');
		}
	}
	public function add_partner()
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

		$error = array('partner_name' => '', 'partner_email' => '', 'partner_address' => '', 'partner_phone' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['partner_name'])) {
			$error['partner_name'] = 'Partner name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['partner_name']) > 250) {
			$error['partner_name'] = 'Partner name should not cross 250 characters in length.';
			$error['status'] = 1;
		}

		//Email regex
		$emailRex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
		if(empty($_POST['partner_email'])) {
			$error['partner_email'] = 'Partner email is mandatory.';
			$error['status'] = 1;
		} else if(!preg_match($emailRex, $_POST['partner_email'])) {
			$error['partner_email'] = 'Partner email is invalid.';
			$error['status'] = 1;
		}

		if(!empty($_POST['partner_address']) && strlen($_POST['partner_address']) > 5000) {
			$error['partner_address'] = 'Address should not exceed 5000 characters in length.';
			$error['status'] = 1;
		}

		if(!empty($_POST['partner_phone']) && strlen($_POST['partner_phone']) > 0) {
			if(!ctype_digit($_POST['partner_phone'])) {
				$error['partner_phone'] = 'Phone number must contain only numbers.';
				$error['status'] = 1;
			} else if(strlen($_POST['partner_phone']) != 10) {
				$error['partner_phone'] = 'Phone number must contain 10 digits.';
				$error['status'] = 1;
			}
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'partner_name' => htmlspecialchars($this->input->post('partner_name'), ENT_QUOTES),
			'partner_email' => htmlspecialchars($this->input->post('partner_email'), ENT_QUOTES),
			'nature_of_business' => $this->input->post('partner_business') != '' ? htmlspecialchars($this->input->post('partner_business'), ENT_QUOTES) : NULL,
			'address' => $this->input->post('partner_address') != '' ? htmlspecialchars($this->input->post('partner_address'), ENT_QUOTES) : NULL,
			'postcode' => $this->input->post('partner_zip') != '' ? htmlspecialchars($this->input->post('partner_zip'), ENT_QUOTES) : NULL,
			'country' => $this->input->post('partner_country') != '' ? htmlspecialchars($this->input->post('partner_country'), ENT_QUOTES) : NULL,
			'telephone' => $this->input->post('partner_phone') != '' ? htmlspecialchars($this->input->post('partner_phone'), ENT_QUOTES) : NULL,
			'fax' => $this->input->post('partner_fax') != '' ? htmlspecialchars($this->input->post('partner_fax'), ENT_QUOTES) : NULL,
			'added_by' => $this->session->userdata('login_id'),
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address()
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Partners_model');
		$insertquery = $this->Partners_model->add_partner($data);
		if($insertquery) {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Partner Added Successfully',
				'insertstatus' => 1
			));
			exit();
		} else {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'insertstatus' => 0
			));
			exit();
		}
	}

	public function view($value='')
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Partners_model');
			$all_partners = $this->Partners_model->all_partner();

			$this->load->model('Helper_model');
			$all_countries = $this->Helper_model->all_countries();

			$header_result = array('main_menu' => $main_menu);

			$result = array(
				'all_partners' => $all_partners,
				'all_countries' => $all_countries
			);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('partners/view', $result);
			$this->load->view('footer');
		}
	}
	public function partner_details()
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

		if(!$this->input->post('partner_id')) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$partner_id = $this->input->post('partner_id');
		if(strlen($partner_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'partner_id' => $partner_id
		);
		$this->load->model('Partners_model');
		$details = $this->Partners_model->get_partner_details($data);
		$details = $this->security->xss_clean($details);
		if($details) {
			echo json_encode(array(
				'status' => 1,
				'details' => $details['partner'],
				'projects' => $details['projects'],
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		} else {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.'
			));
			exit();
		}
	}
	public function partner_locations()
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

		if(!$this->input->post('partner_id')) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$partner_id = $this->input->post('partner_id');
		if(strlen($partner_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'partner_id' => $partner_id
		);
		$this->load->model('Partners_model');
		$locations = $this->Partners_model->get_partner_locations($data);
		if(count($locations) === 0) {
			echo json_encode(array(
				'status' => 2,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'No location has been assigned to this partner.'
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

		$countries = $this->Partners_model->get_partner_locations_nested($data);
		echo json_encode(array(
			'status' => 1,
			'locations' => $locations,
			'countries' => $countries,
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash()
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

			$this->load->model('Partners_model');
			$all_partners = $this->Partners_model->all_partner();

			$this->load->model('Helper_model');
			$all_countries = $this->Helper_model->all_countries();

			$header_result = array('main_menu' => $main_menu);

			$result = array(
				'all_partners' => $all_partners,
				'all_countries' => $all_countries
			);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('partners/edit', $result);
			$this->load->view('footer');
		}
	}
	public function edit_partner()
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

		if(!$this->input->post('partner_id')) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$partner_id = $this->input->post('partner_id');
		if(strlen($partner_id) == 0) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'partner_id' => $partner_id
		);
		$data = $this->security->xss_clean($data);
		$this->load->model('Partners_model');
		$details = $this->Partners_model->get_partner_details($data);
		if(!$details) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.'
			));
			exit();
		}

		$error = array('partner_name' => '', 'partner_email' => '', 'partner_address' => '', 'partner_phone' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['partner_name'])) {
			$error['partner_name'] = 'Partner name is mandatory.';
			$error['status'] = 1;
		}
		else if(strlen($_POST['partner_name']) > 250) {
			$error['partner_name'] = 'Partner name should not cross 250 characters in length.';
			$error['status'] = 1;
		}

		//Email regex
		$emailRex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
		if(empty($_POST['partner_email'])) {
			$error['partner_email'] = 'Partner email is mandatory.';
			$error['status'] = 1;
		} else if(!preg_match($emailRex, $_POST['partner_email'])) {
			$error['partner_email'] = 'Partner email is invalid.';
			$error['status'] = 1;
		}

		if(!empty($_POST['partner_address']) && strlen($_POST['partner_address']) > 5000) {
			$error['partner_address'] = 'Address should not exceed 5000 characters in length.';
			$error['status'] = 1;
		}

		if(!empty($_POST['partner_phone']) && strlen($_POST['partner_phone']) > 0) {
			if(!ctype_digit($_POST['partner_phone'])) {
				$error['partner_phone'] = 'Phone number must contain only numbers.';
				$error['status'] = 1;
			} else if(strlen($_POST['partner_phone']) != 10) {
				$error['partner_phone'] = 'Phone number must contain 10 digits.';
				$error['status'] = 1;
			}
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}

		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'partner_id' => $this->input->post('partner_id')
			),
			'set' => array(
				'partner_name' => htmlspecialchars($this->input->post('partner_name'), ENT_QUOTES),
				'partner_email' => htmlspecialchars($this->input->post('partner_email'), ENT_QUOTES),
				'nature_of_business' => $this->input->post('partner_business') != '' ? htmlspecialchars($this->input->post('partner_business'), ENT_QUOTES) : NULL,
				'address' => $this->input->post('partner_address') != '' ? htmlspecialchars($this->input->post('partner_address'), ENT_QUOTES) : NULL,
				'postcode' => $this->input->post('partner_zip') != '' ? htmlspecialchars($this->input->post('partner_zip'), ENT_QUOTES) : NULL,
				'country' => $this->input->post('partner_country') != '' ? $this->input->post('partner_country') : NULL,
				'telephone' => $this->input->post('partner_phone') != '' ? htmlspecialchars($this->input->post('partner_phone'), ENT_QUOTES) : NULL,
				'fax' => $this->input->post('partner_fax') != '' ? htmlspecialchars($this->input->post('partner_fax'), ENT_QUOTES) : NULL
			)
		);
		$data = $this->security->xss_clean($data);
		$updatequery = $this->Partners_model->edit_partner($data);
		if($updatequery) {
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Partner Updated Successfully',
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
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Partners_model');
			$all_partners = $this->Partners_model->all_partner();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_partners' => $all_partners);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('partners/delete', $result);
			$this->load->view('footer');
		}
	}
	public function delete_partner()
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

		if(!$this->input->post('partner_id')) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$partner_id = $this->input->post('partner_id');
		if(strlen($partner_id) == 0) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'partner_id' => $partner_id
		);
		$this->load->model('Partners_model');
		$details = $this->Partners_model->get_partner_details($data);
		if(!$details) {
			echo json_encode(array(
				'status' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.'
			));
			exit();
		}

		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'partner_id' => $this->input->post('partner_id')
			),
			'set' => array(
				'status' => 0
			)
		);
		$deletequery = $this->Partners_model->edit_partner($data);
		if($deletequery) {
			$this->Partners_model->edit_partner_project($data);
			$this->load->model('Centre_model');
			$this->Centre_model->edit_centre_partner($data);
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Partner Deleted Successfully',
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

	public function project_combo()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Partners_model');
			$all_partners = $this->Partners_model->all_partner();

			$header_result = array('main_menu' => $main_menu);

			$result = array('all_partners' => $all_partners);
			$result = $this->security->xss_clean($result);
			$this->load->view('header', $header_result);
			$this->load->view('partners/project_combo', $result);
			$this->load->view('footer');
		}
	}
	public function manage_project_combo()
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

		if(!$this->input->post('partner_id')) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		$partner_id = $this->input->post('partner_id');
		if(strlen($partner_id) == 0) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg' => 'Invalid request. Please refresh the page and try again.'
			));
			exit();
		}
		
		$data = array(
			'partner_id' => $partner_id
		);
		$this->load->model('Partners_model');
		$details = $this->Partners_model->get_partner_details($data);
		if(!$details) {
			echo json_encode(array(
				'updatestatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.'
			));
			exit();
		}

		$error = array('projects' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		if(empty($_POST['projects'])) {
			$error['projects'] = 'Project selection is mandatory.';
			$error['status'] = 1;
		}
		
		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		date_default_timezone_set('UTC');
		$data = array(
			'where' => array(
				'partner_id' => $this->input->post('partner_id')
			),
			'set' => array(
				'status' => 0
			)
		);
		$this->Partners_model->edit_partner_project($data);

		foreach ($this->input->post('projects') as $key => $project) {
			$data = array(
				'partner_id' => $this->input->post('partner_id'),
				'project_id' => $project,
				'added_by' => $this->session->userdata('login_id'),
				'added_datetime' => date('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address()
			);
			$data = $this->security->xss_clean($data);
			$this->Partners_model->add_partner_project($data);
		}
		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'msg' => 'Partner And Project Combination Updated Successfully',
			'updatestatus' => 1
		));
		exit();
	}
}