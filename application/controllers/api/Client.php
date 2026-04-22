<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Client extends CI_Controller {

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

			case 'location':
				$this->location($data);
			break;

			case 'map_client_location':
				$this->map_client_location($data);
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

		$this->db->distinct()->select('lu.*');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->join('tbl_user_unit_location AS tuul', 'tuul.UNIT_ID = lu.UNIT_ID');
			$this->db->where('tuul.status', 1)->where('tuul.user_id', $data['login_id']);
		}
		$all_clients = $this->db->where('lu.UNIT_STATUS', 1)->get('lkp_unit AS lu')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'all_clients' => $all_clients
		));
	}
	public function get_all_created($data)
	{
		if(!$data) $this->bad_request();

		$this->db->select('*');
		if($data['login_role'] != 1 && $data['login_role'] != 2) {
			$this->db->where('added_by', $data['login_id']);
		}
		$all_clients = $this->db->where('UNIT_STATUS', 1)->get('lkp_unit')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'all_clients' => $all_clients
		));
	}
	public function get_details($data)
	{
		if(!$data) $this->bad_request();
		if(!isset($data['client_id']) || (strlen($data['client_id']) == 0)) $this->bad_request();

		$this->db->select('*');
		$this->db->where('UNIT_ID', $data['client_id']);
		$client = $this->db->get('lkp_unit')->row_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'client' => $client
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

		$phone = strlen($phone) > 0 ? $phone : NULL;
		$address = strlen($address) > 0 ? $address : NULL;
		
		$insert = $this->db->insert('lkp_unit', array(
			'UNIT_NAME' => $name,
			'phone' => $phone,
			'address' => $address,
			'poc_name' => $poc_name,
			'poc_email' => $poc_email,
			'poc_phone' => $poc_phone,
			'added_by' => $data['login_id'],
			'added_datetime' => date('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
			'UNIT_STATUS' => 1
		));
		if(!$insert){
			$this->jsonify(array(
				'msg'=>'Sorry! Please try after sometime.',
				'status' => 0
			));
		}

		$client_id = $this->db->insert_id();
		if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/client/');
		$ext = '.jpg';
		$image = $data['image'];
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
		$this->db->where('UNIT_ID', $client_id)->update('lkp_unit', array(
			'image' => $file
		));

		$this->jsonify(array(
			'msg'=>'Client added successfully.',
			'status' => 1
		));
	}

	public function update($data)
	{
		if(!$data) $this->bad_request();
		if(!isset($data['client_id']) || (strlen($data['client_id']) == 0)) $this->bad_request();
		
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

		$phone = strlen($phone) > 0 ? $phone : NULL;
		$address = strlen($address) > 0 ? $address : NULL;
		
		$update = $this->db->where('UNIT_ID', $data['client_id'])->update('lkp_unit', array(
			'UNIT_NAME' => $name,
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

		$this->jsonify(array(
			'msg'=>'Client updated successfully.',
			'status' => 1
		));
	}

	public function location($data){
		// $this->db->select('state.state_id, state.state_name');
		// $this->db->from('tbl_client_location as location');
		// $this->db->join('lkp_state as state', 'state.state_id = location.state_id');
		// $this->db->where('location.client_id', $data['client_id']);
		// $this->db->where('location.status', 1);
		// $selectedstate = $this->db->get()->result_array();
		// $exitingstates = array();
		// foreach ($selectedstate as $skey => $location) {
		// 	array_push($exitingstates, $location['state_id']);
		// }
		// $this->db->select('state_id, state_name');
		// $this->db->from('lkp_state');
		// if(count($exitingstates)){
		// 	$this->db->where_not_in('state_id', $exitingstates);
		// }
		// $this->db->where('status', 1);
		// $unselectedstate = $this->db->get()->result_array();


        // Get all states
        $this->db->select('state_id, state_name')->from('lkp_state');
        $allState = $this->db->where('status', 1)->get()->result_array();
        foreach ($allState as $key => $state) {
        	// Get all dists in state
			$this->db->select('district_id, district_name')->from('lkp_district');
			$this->db->where('state_id', $state['state_id'])->where('status', 1);
        	$allDist = $this->db->get()->result_array();

        	$allState[$key]['districts'] = $allDist;
		}

		// Get all selected states
		$this->db->select('state_id, district_id')->from('tbl_client_location');
        $this->db->where('client_id', $data['client_id'])->where('status', 1);
        $selectedLoc = $this->db->get()->result_array();
        $states = $districts = [];
        foreach ($selectedLoc as $key => $loc) {
        	// Push selected state ids
        	if(!in_array($loc['state_id'], $states)) array_push($states, $loc['state_id']);
        	// Push selected district ids
        	if(!in_array($loc['district_id'], $districts)) array_push($districts, $loc['district_id']);
        }

        $this->jsonify(array(
			'status' => 1,
			'all_state' => $allState,
			'selected' => array(
				'states' => $states,
				'districts' => $districts
			)
		));
		exit();
	}

	public function map_client_location($data){
		if(!$data) $this->bad_request();
		if(!isset($data['client_id']) || (strlen($data['client_id']) == 0)) $this->bad_request();

		$client_id = $data['client_id'];
		$deletelocation = $this->db->where('client_id', $client_id)->delete('tbl_client_location');
		foreach ($data['states'] as $key => $state) {
			$client_location = array(
				'client_id' => $client_id,
				'state_id' => $state->state_id,
				'district_id' => $state->district_id,
				'added_by' => $data['login_id']
			);
			$module_query = $this->db->insert('tbl_client_location', $client_location);
		}
		$this->jsonify(array(
			'msg' => 'Location added successfully.',
			'status' => 1
		));
		exit();

	}
}