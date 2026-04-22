<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agency extends CI_Controller {

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
			case 'get_all_assigned':
				$this->get_all_assigned($data);
			break;
			case 'get_all_created':
				$this->get_all_created($data);
			break;
			case 'get_details':
				$this->get_details($data);
			break;
			
			case 'create':
				$this->create($data);
			break;
			
			case 'update':
				$this->update($data);
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

	public function get_all_assigned($data)
	{
		if(!$data) $this->bad_request();

		$this->db->select('la.*');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->join('lkp_agency_user AS lau', 'lau.agency_id = la.agency_id');
			$this->db->where('lau.status', 1)->where('lau.user_id', $data['login_id']);
		}
		$all_agencies = $this->db->where('la.status', 1)->get('lkp_agency AS la')->result_array();
		foreach ($all_agencies as $key => $value) {
			$this->db->select('lu.*');
			$this->db->join('lkp_agency_client AS lac', 'lac.client_id = lu.UNIT_ID');
			$this->db->where('lac.agency_id', $value['agency_id'])->where('lac.status', 1);
			$client = $this->db->get('lkp_unit AS lu')->row_array();
			$all_agencies[$key]['client'] = $client;
		}

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'all_agencies' => $all_agencies
		));
	}
	public function get_all_created($data)
	{
		if(!$data) $this->bad_request();

		$this->db->select('*');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->where('added_by', $data['login_id']);
		}
		$all_agencies = $this->db->where('status', 1)->get('lkp_agency')->result_array();
		foreach ($all_agencies as $key => $value) {
			$this->db->select('lu.*');
			$this->db->join('lkp_agency_client AS lac', 'lac.client_id = lu.UNIT_ID');
			$this->db->where('lac.agency_id', $value['agency_id'])->where('lac.status', 1);
			$client = $this->db->get('lkp_unit AS lu')->row_array();
			$all_agencies[$key]['client'] = $client;
		}

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'all_agencies' => $all_agencies
		));
	}
	public function get_details($data)
	{
		if(!$data) $this->bad_request();
		if(!isset($data['agency_id']) || (strlen($data['agency_id']) == 0)) $this->bad_request();

		$this->db->select('*');
		$this->db->where('agency_id', $data['agency_id']);
		$agency = $this->db->get('lkp_agency')->row_array();

		$this->db->select('lu.*');
		$this->db->join('lkp_agency_client AS lac', 'lac.client_id = lu.UNIT_ID');
		$this->db->where('lac.agency_id', $agency['agency_id'])->where('lac.status', 1);
		$client = $this->db->get('lkp_unit AS lu')->row_array();
		$agency['client'] = $client;

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'agency' => $agency
		));
	}


	public function create($data)
	{
		if(!$data) $this->bad_request();
		
		$error = array(
			'status' => 1
		);

		$name = $data['name'];
		if(empty($name)) {
			$error['name'] = 'Name is mandatory.';
			$error['status'] = 0;
		}
		$address = $data['address'];
		if(strlen($address) > 5000) {
			$error['address'] = 'Address must be within 5000 characters.';
			$error['status'] = 0;
		}
		$poc_name = $data['pocname'];
		if(empty($poc_name)) {
			$error['pocname'] = 'Name of person of contact is mandatory.';
			$error['status'] = 0;
		}
		$poc_email = $data['pocemail'];
		if(empty($poc_email)) {
			$error['pocemail'] = 'Email of person of contact is mandatory.';
			$error['status'] = 0;
		}
		$poc_phone = $data['pocphone'];
		if(empty($poc_phone)) {
			$error['pocphone'] = 'Phone of person of contact is mandatory.';
			$error['status'] = 0;
		}
		$client = $data['client'];
		if(empty($client)) {
			$error['client'] = 'Client is mandatory.';
			$error['status'] = 0;
		}

		if($error['status'] == 0) {
			$error['type'] = 'fields';
			echo json_encode($error);
			exit();
		}

		date_default_timezone_set("UTC");
		$name = htmlspecialchars($data['name'], ENT_QUOTES);
		$phone = htmlspecialchars($data['phone'], ENT_QUOTES);
		$address = htmlspecialchars($data['address'], ENT_QUOTES);
		$poc_name = htmlspecialchars($data['pocname'], ENT_QUOTES);
		$poc_email = htmlspecialchars($data['pocemail'], ENT_QUOTES);
		$poc_phone = htmlspecialchars($data['pocphone'], ENT_QUOTES);
		$client = htmlspecialchars($data['client'], ENT_QUOTES);

		$phone = strlen($phone) > 0 ? $phone : NULL;
		$address = strlen($address) > 0 ? $address : NULL;

		$insert = $this->db->insert('lkp_agency', array(
			'agency_name' => $name,
			'phone' => $phone,
			'address' => $address,
			'poc_name' => $poc_name,
			'poc_email' => $poc_email,
			'poc_phone' => $poc_phone,
			'added_by' => $data['login_id'],
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
			'status' => 1
		));
		if(!$insert){
			$this->jsonify(array(
				'msg'=>'Sorry! Please try after sometime.',
				'status' => 0
			));
		}

		$agency_id = $this->db->insert_id();
		// Set client of agency
		$this->db->insert('lkp_agency_client', array(
			'client_id' => $client,
			'agency_id' => $agency_id,
			'status' => 1
		));

		$this->jsonify(array(
			'msg'=>'Agency added successfully.',
			'status' => 1
		));
	}


	public function update($data)
	{
		if(!$data) $this->bad_request();
		if(!isset($data['agency_id']) || (strlen($data['agency_id']) == 0)) $this->bad_request();
		
		$error = array(
			'status' => 1
		);

		$name = $data['name'];
		if(empty($name)) {
			$error['name'] = 'Name is mandatory.';
			$error['status'] = 0;
		}
		$address = $data['address'];
		if(strlen($address) > 5000) {
			$error['address'] = 'Address must be within 5000 characters.';
			$error['status'] = 0;
		}
		$poc_name = $data['pocname'];
		if(empty($poc_name)) {
			$error['pocname'] = 'Name of person of contact is mandatory.';
			$error['status'] = 0;
		}
		$poc_email = $data['pocemail'];
		if(empty($poc_email)) {
			$error['pocemail'] = 'Email of person of contact is mandatory.';
			$error['status'] = 0;
		}
		$poc_phone = $data['pocphone'];
		if(empty($poc_phone)) {
			$error['pocphone'] = 'Phone of person of contact is mandatory.';
			$error['status'] = 0;
		}
		$client = $data['client'];
		if(empty($client)) {
			$error['client'] = 'Client is mandatory.';
			$error['status'] = 0;
		}

		if($error['status'] == 0) {
			$error['type'] = 'fields';
			echo json_encode($error);
			exit();
		}

		date_default_timezone_set("UTC");
		$name = htmlspecialchars($data['name'], ENT_QUOTES);
		$phone = htmlspecialchars($data['phone'], ENT_QUOTES);
		$address = htmlspecialchars($data['address'], ENT_QUOTES);
		$poc_name = htmlspecialchars($data['pocname'], ENT_QUOTES);
		$poc_email = htmlspecialchars($data['pocemail'], ENT_QUOTES);
		$poc_phone = htmlspecialchars($data['pocphone'], ENT_QUOTES);
		$client = htmlspecialchars($data['client'], ENT_QUOTES);

		$phone = strlen($phone) > 0 ? $phone : NULL;
		$address = strlen($address) > 0 ? $address : NULL;

		$update = $this->db->where('agency_id', $data['agency_id'])->update('lkp_agency', array(
			'agency_name' => $name,
			'phone' => $phone,
			'address' => $address,
			'poc_name' => $poc_name,
			'poc_email' => $poc_email,
			'poc_phone' => $poc_phone
		));
		if(!$update){
			$this->jsonify(array(
				'msg'=>'Sorry! Please try after sometime.',
				'status' => 0
			));
		}

		// Remove previous client
		$this->db->where('agency_id', $data['agency_id'])->delete('lkp_agency_client');
		// Set client of agency
		$this->db->insert('lkp_agency_client', array(
			'client_id' => $client,
			'agency_id' => $data['agency_id'],
			'status' => 1
		));

		$this->jsonify(array(
			'msg'=>'Agency updated successfully.',
			'status' => 1
		));
	}
}