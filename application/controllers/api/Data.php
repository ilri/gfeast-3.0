<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data extends CI_Controller {

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
		if(!isset($data) || !isset($data['purpose']) || (strlen($data['purpose']) === 0))  $this->bad_request();

		// Load the method according to purpose
		switch ($data['purpose']) {
			case 'get_lookups':
				$this->get_lookups($data);
			break;

			case 'get_tbl_pmfby':
				$this->get_tbl_pmfby($data);
			break;

			case 'get_kmls':
				$this->get_kmls($data);
			break;
			case 'get_kml_details':
				$this->get_kml_details($data);
			break;

			case 'get_dashboard':
				$this->get_dashboard($data);
			break;

			case 'get_locations':
				$this->get_locations($data);
			break;

			case 'get_surveyfields':
				$this->get_surveyfields($data);
			break;

			case 'get_surveys':
				$this->get_surveys($data);
			break;

			case 'get_surveydata':
				$this->get_surveydata($data);
			break;
			case 'get_surveydata_details':
				$this->get_surveydata_details($data);
			break;

			case 'get_files':
				$this->get_files($data);
			break;

			case 'get_checkin_checkout':
				$this->get_checkin_checkout($data);
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

	public function get_lookups($data)
	{
		if(!$data) $this->bad_request();

		$this->db->distinct()->select('ls.*')->from('lkp_state AS ls');
		if(isset($data['client_id']) && strlen($data['client_id']) > 0) {
			$this->db->join('tbl_client_location AS tcl', 'tcl.state_id = ls.state_id');
			$this->db->where('tcl.client_id', $data['client_id'])->where('tcl.status', 1);
		}
		$states = $this->db->where('ls.status', 1)->get()->result_array();
		// $districts = $this->db->where('status', 1)->get('lkp_district')->result_array();
		// $blocks = $this->db->where('block_status', 1)->get('lkp_block')->result_array();
		// $tehsils = $this->db->where('tehsil_status', 1)->get('lkp_tehsil')->result_array();
		// $grampanchayats = $this->db->where('grampanchayat_status', 1)->get('lkp_grampanchayat')->result_array();
		// $villages = $this->db->where('village_status', 1)->get('lkp_village')->result_array();

		$crops = $this->db->where('status', 1)->get('lkp_crop')->result_array();
		$ifscs = $this->db->where('IFSC_STATUS', 1)->get('lkp_ifsc')->result_array();
		$clients = $this->db->where('UNIT_STATUS', 1)->get('lkp_unit')->result_array();
		$soil_types = $this->db->where('SOIL_TYPE_STATUS', 1)->get('lkp_soil_type')->result_array();

		// Get all users
		$this->db->select('user_id, email_id, first_name, last_name');
		$this->db->where('role_id', 8)->where('status', 1);
		$users = $this->db->get('tbl_users')->result_array();

		// Return data
		$this->jsonify(array(
			'users' => $users,
			'states' => $states,
			'crops' => $crops,
			'ifscs' => $ifscs,
			'clients' => $clients,
			'soil_types' => $soil_types
		));
	}

	public function get_tbl_pmfby($data)
	{
		if(!$data) $this->bad_request();

		$tableData = $this->db->get('tbl_pmfby')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'tbl_pmfby' => $tableData
		));
	}

	public function get_kmls($data)
	{
		if(!$data) $this->bad_request();

		$this->db->select('tk.kml_id, tk.file_name, tk.measured_area');
		$this->db->where('tk.kml_status', 1);
		if(isset($data['states']) && count($data['states']) > 0) {
			$this->db->where_in('tk.state_id', $data['states']);
		}
		if(isset($data['districts']) && count($data['districts']) > 0) {
			$this->db->where_in('tk.district_id', $data['districts']);
		}
		if(isset($data['client_id']) && strlen($data['client_id']) > 0) {
			$this->db->where('tk.client_id', 1000);
		}
		$all_kmls = $this->db->get('tbl_kmlfile AS tk')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'kmls' => $all_kmls
		));
	}
	public function get_kml_details($data)
	{
		if(!$data) $this->bad_request();
		if(!$data['kml_id'] || strlen($data['kml_id']) == 0) $this->bad_request();

		// KML Id
		$kml_id = $data['kml_id'];

		// Get kml details
		$kml = $this->db->where('kml_id', $kml_id)->where('kml_status', 1)->get('tbl_kmlfile')->row_array();
		$data_id = $kml['plot_data_id'];

		// Search plot_data_id in survey 1, 2 and 3
		$sid = 0;
		$check1 = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey1');
		$check2 = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey2');
		$check3 = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey3');
		$check4 = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey4');
		$check6 = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey6');
		$check7 = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey7');
		if($check1->num_rows() > 0) {
			$sid = 1;
		} else if($check2->num_rows() > 0) {
			$sid = 2;
		} else if($check3->num_rows() > 0) {
			$sid = 3;
		} else if($check4->num_rows() > 0) {
			$sid = 4;
		} else if($check6->num_rows() > 0) {
			$sid = 6;
		} else if($check7->num_rows() > 0) {
			$sid = 7;
		}

		if($sid == 0) {
			$this->bad_request();
		}

		// Get survey details
		$survey = $this->db->where('id', $sid)->where('status', 1)->get('form')->row_array();
		// Get survey fields according to survey_id
		$fields = $this->db->where('form_id', $sid)->where('status', 1)->get('form_field')->result_array();
		// Get survey data according to survey_id and data_id
		$surveydata = $this->db->where('data_id', $data_id)->where('status', 1)->get('survey'.$sid)->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'survey' => $survey,
			'fields' => $fields,
			'surveydata' => $surveydata
		));
	}

	public function get_dashboard($data)
	{
		if(!$data) $this->bad_request();
		if(!isset($data['states']) || count($data['states']) == 0) $this->bad_request();
		// if(!$data['districts'] || count($data['districts']) == 0) $this->bad_request();

		$user_ids = array();
		// Get Users Assigned to Given Location
		$this->db->where('status', 1);
		$this->db->where_in('state_id', $data['states']);
		if(isset($data['districts']) && (count($data['districts']) > 0)) {
			$this->db->where_in('district_id', $data['districts']);
		}
		if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
			$this->db->where_in('UNIT_ID', $data['client_id']);
		}
		$users = $this->db->get('tbl_user_unit_location')->result_array();
		foreach ($users as $key => $user) {
			if(!in_array($user['user_id'], $user_ids)) array_push($user_ids, $user['user_id']);
		}
		if(count($user_ids) == 0) $user_ids = array(0);

		// Get Check-in and Check-out data of above users
		$this->db->where('inout_status', 1);
		$this->db->where_in('user_id', $user_ids);
		if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
			$this->db->where_in('client_id', $data['client_id']);
		}
		if((isset($data['start_date']) && strlen($data['start_date']) > 0)
		&& (isset($data['end_date']) && strlen($data['end_date']) > 0)) {
			$this->db->where('date_time >=', $data['start_date'].' 00:00:00');
			$this->db->where('date_time <=', $data['end_date'].' 23:59:59');
		}
		$check_in_out = $this->db->get('tbl_checkin_checkout')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'check_in_out' => $check_in_out
		));
	}

	public function get_locations($data)
	{

		if(!$data) $this->bad_request();
		if(!$data['location_type'] || strlen($data['location_type']) == 0) $this->bad_request();

		$table = $data['location_type'];
		$this->db->distinct()->select('location.*');
		switch ($table) {
			case 'lkp_district':
				if(isset($data['client_id']) && strlen($data['client_id']) > 0) {
					$this->db->join('tbl_client_location AS tcl', 'tcl.district_id = location.district_id');
					$this->db->where('tcl.client_id', $data['client_id'])->where('tcl.status', 1);
				}
				$this->db->where('location.status', 1);
				if(isset($data['location_id']) && strlen($data['location_id']) > 0) {
					$this->db->where('location.state_id', $data['location_id']);
				}
			break;

			case 'lkp_tehsil':
				$this->db->where('location.tehsil_status', 1);
				if(isset($data['location_id']) && strlen($data['location_id']) > 0) {
					$this->db->where('location.district_id', $data['location_id']);
				}
			break;

			case 'lkp_block':
				$this->db->where('location.block_status', 1);
				if(isset($data['location_id']) && strlen($data['location_id']) > 0) {
					$this->db->where('location.tehsil_id', $data['location_id']);
				}
			break;

			case 'lkp_grampanchayat':
				$this->db->where('location.grampanchayat_status', 1);
				if(isset($data['location_id']) && strlen($data['location_id']) > 0) {
					$this->db->where('location.block_id', $data['location_id']);
				}
			break;

			case 'lkp_village':
				$this->db->where('location.village_status', 1);
				if(isset($data['location_id']) && strlen($data['location_id']) > 0) {
					$this->db->where('location.grampanchayat_id', $data['location_id']);
				}
			break;
			
			default:
				$this->bad_request();
			break;
		}
		$location = $this->db->get($table.' AS location')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			$table => $location
		));
	}

	public function get_surveys($data)
	{
		if(!$data) $this->bad_request();

		// Get all surveys
		$this->db->where('status', 1)->where('parent_form IS NULL');
		$surveys = $this->db->order_by('sl_no', 'ASC')->get('form')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'surveys' => $surveys
		));
	}

	public function get_surveyfields($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['survey_id']) || (strlen($data['survey_id']) === 0)) $this->bad_request();

		// Survey Id
		$sid = $data['survey_id'];

		// Get all fields of survey
		$fields = $this->db->where('form_id', $sid)->where('status', 1)->get('form_field')->result_array();

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'fields' => $fields
		));
	}

	public function get_surveydata($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['survey_id']) || (strlen($data['survey_id']) === 0)) $this->bad_request();

		// Survey Id
		$sid = $data['survey_id'];
		// Table name where data is stored
		$survey_table = 'survey'.$sid;

		$crop_field = NULL;
		$state_field = NULL;
		$district_field = NULL;
		// Get all fields of survey
		$fields = $this->db->where('form_id', $sid)->where('status', 1)->get('form_field')->result_array();
		foreach ($fields as $key => $field) {
			if($field['type'] == 'lkp_crop') $crop_field = 'field_'.$field['field_id'];
			if($field['type'] == 'lkp_state') $state_field = 'field_'.$field['field_id'];
			if($field['type'] == 'lkp_district') $district_field = 'field_'.$field['field_id'];
		}

		// "agency_id": ["2", "3"]

		// Get all survey data
		$this->db->distinct();
		$this->db->select('survey.*, lu.UNIT_NAME AS client_name, tu.first_name, tu.last_name, idl.lat, idl.lng');
		$this->db->join('lkp_unit AS lu', 'lu.UNIT_ID = survey.client_id');
		$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
		$this->db->join('ic_data_location AS idl', 'idl.data_id = survey.data_id', 'left');
		$this->db->where('survey.status', 1)->where('idl.status', 1)->where('lu.UNIT_STATUS', 1);
		if(isset($data['user_id']) && (count($data['user_id']) > 0)) {
			$this->db->where_in('survey.user_id', $data['user_id']);
		}
		if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
			$this->db->where_in('survey.client_id', $data['client_id']);
		}
		if($crop_field && isset($data['crop_id']) && (count($data['crop_id']) > 0)) {
			$this->db->where_in('survey.'.$crop_field, $data['crop_id']);
		}
		if($state_field && isset($data['states']) && (count($data['states']) > 0)) {
			$this->db->where_in('survey.'.$state_field, $data['states']);
		}
		if($district_field && isset($data['districts']) && (count($data['districts']) > 0)) {
			$this->db->where_in('survey.'.$district_field, $data['districts']);
		}
		if(isset($data['start_date']) && strlen($data['start_date']) > 0) {
			$this->db->where('survey.datetime >=', $data['start_date'].' 00:00:00');
		}
		if(isset($data['end_date']) && strlen($data['end_date']) > 0) {
			$this->db->where('survey.datetime <=', $data['end_date'].' 23:59:59');
		}
		if(isset($data['pagination'])) {
			$page_no = intval($data['pagination']->page_no);
			$records_per_page = intval($data['pagination']->records_per_page);

			$start = $page_no == 1 ? 0 : ($records_per_page * ($page_no - 1));
			$limit = $records_per_page;

			$this->db->limit($limit, $start);
		}
		$surveydata = $this->db->order_by('id', 'DESC')->get($survey_table.' AS survey')->result_array();

		// Get all survey data count
		$this->db->distinct();
		$this->db->select('survey.*');
		$this->db->join('lkp_unit AS lu', 'lu.UNIT_ID = survey.client_id');
		$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
		$this->db->where('survey.status', 1)->where('lu.UNIT_STATUS', 1);
		if(isset($data['user_id']) && (count($data['user_id']) > 0)) {
			$this->db->where_in('survey.user_id', $data['user_id']);
		}
		if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
			$this->db->where_in('survey.client_id', $data['client_id']);
		}
		if($crop_field && isset($data['crop_id']) && (count($data['crop_id']) > 0)) {
			$this->db->where_in('survey.'.$crop_field, $data['crop_id']);
		}
		if($state_field && isset($data['states']) && (count($data['states']) > 0)) {
			$this->db->where_in('survey.'.$state_field, $data['states']);
		}
		if($district_field && isset($data['districts']) && (count($data['districts']) > 0)) {
			$this->db->where_in('survey.'.$district_field, $data['districts']);
		}
		if(isset($data['start_date']) && strlen($data['start_date']) > 0) {
			$this->db->where('survey.datetime >=', $data['start_date'].' 00:00:00');
		}
		if(isset($data['end_date']) && strlen($data['end_date']) > 0) {
			$this->db->where('survey.datetime <=', $data['end_date'].' 23:59:59');
		}
		$surveydatacount = $this->db->get($survey_table.' AS survey')->num_rows();
		
		// Get all location data
		$this->db->where('form_id', $sid)->where('status', 1);
		if(isset($data['user_id']) && (count($data['user_id']) > 0)) {
			$this->db->where_in('user_id', $data['user_id']);
		}
		if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
			$this->db->where_in('client_id', $data['client_id']);
		}
		if(isset($data['start_date']) && strlen($data['start_date']) > 0) {
			$this->db->where('created_date >=', $data['start_date'].' 00:00:00');
		}
		if(isset($data['end_date']) && strlen($data['end_date']) > 0) {
			$this->db->where('created_date <=', $data['end_date'].' 23:59:59');
		}
		$locationdata = $this->db->get('ic_data_location')->result_array();
		
		// Return data
		$this->jsonify(array(
			'status' => 1,
			'surveydata' => $surveydata,
			'locationdata' => $locationdata,
			'surveydatacount' => $surveydatacount
		));
	}
	public function get_surveydata_details($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['data_id']) || (strlen($data['data_id']) === 0)) $this->bad_request();

		if(!isset($data['survey_id']) || (strlen($data['survey_id']) === 0)) $this->bad_request();

		// Survey Id
		$sid = $data['survey_id'];
		// Survey Data Id
		$data_id = $data['data_id'];
		// Table name where data is stored
		$survey_table = 'survey'.$sid;

		// Get survey data
		$this->db->select('survey.*, lu.UNIT_NAME AS client_name, tu.first_name, tu.last_name, idl.lat, idl.lng');
		$this->db->join('lkp_unit AS lu', 'lu.UNIT_ID = survey.client_id');
		$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
		$this->db->join('ic_data_location AS idl', 'idl.data_id = survey.data_id', 'left');
		$this->db->where('idl.status', 1)->where('lu.UNIT_STATUS', 1);
		$this->db->where('survey.data_id', $data_id)->where('survey.status', 1);
		if(isset($data['date']) && strlen($data['date']) > 0) {
			$this->db->where('survey.datetime >=', $data['date'].' 00:00:00');
			$this->db->where('survey.datetime <=', $data['date'].' 23:59:59');
		}
		$surveydata = $this->db->get($survey_table.' AS survey')->row_array();
		
		// Return data
		$this->jsonify(array(
			'status' => 1,
			'surveydata' => $surveydata
		));
	}

	public function get_files($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['type']) || (strlen($data['type']) === 0)) $this->bad_request();
		if(!isset($data['data_id']) || (strlen($data['data_id']) === 0)) $this->bad_request();
		if(!isset($data['survey_id']) || (strlen($data['survey_id']) === 0)) $this->bad_request();

		// Type
		$type = $data['type'];
		// Survey Id
		$sid = $data['survey_id'];
		// Data Id
		$dataid = $data['data_id'];

		switch ($type) {
			case 'kml':
				$this->db->select('kml_id, file_name, measured_area');
				$this->db->where('plot_data_id', $dataid)->where('kml_status', 1);
				$files = $this->db->get('tbl_kmlfile')->result_array();
			break;

			case 'image':
				if(!isset($data['field_id']) || (strlen($data['field_id']) === 0)) $this->bad_request();
				
				$this->db->where('form_id', $sid)->where('field_id', $data['field_id']);
				$this->db->where('data_id', $dataid)->where('status', 1);
				$files = $this->db->get('ic_data_file')->result_array();
			break;
			
			default:
				$this->bad_request();
			break;
		}

		// Return data
		$this->jsonify(array(
			'status' => 1,
			'files' => $files
		));
	}

	public function get_checkin_checkout($data)
	{
		if(!$data) $this->bad_request();

		if(!isset($data['user_id'])) $this->bad_request();

		// Get all checkin_checkout data
		$this->db->where('inout_status', 1);
		if(isset($data['user_id']) && count($data['user_id']) > 0) {
			$this->db->where_in('user_id', $data['user_id']);
		}
		if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
			$this->db->where_in('client_id', $data['client_id']);
		}
		if((isset($data['start_date']) && strlen($data['start_date']) > 0)
		&& (isset($data['end_date']) && strlen($data['end_date']) > 0)) {
			$this->db->where('date_time >=', $data['start_date'].' 00:00:00');
			$this->db->where('date_time <=', $data['end_date'].' 23:59:59');
		}
		$check_in_out = $this->db->get('tbl_checkin_checkout')->result_array();


		// Get survey data
		if(!isset($data['survey_id'])) $this->bad_request();

		// Survey Id
		$sids = $data['survey_id'];
		// Survey Return Data
		$surveys = array();
		foreach ($sids as $key => $sid) {
			// Table name where data is stored
			$survey_table = 'survey'.$sid;

			// Get survey name
			$survey = $this->db->where('id', $sid)->get('form')->row_array();

			// Get survey data count
			$this->db->distinct();
			$this->db->select('survey.id');
			$this->db->join('lkp_unit AS lu', 'lu.UNIT_ID = survey.client_id');
			$this->db->join('tbl_users AS tu', 'tu.user_id = survey.user_id');
			$this->db->where('survey.status', 1)->where('lu.UNIT_STATUS', 1);
			if(isset($data['user_id']) && (count($data['user_id']) > 0)) {
				$this->db->where_in('survey.user_id', $data['user_id']);
			}
			if(isset($data['client_id']) && (count($data['client_id']) > 0)) {
				$this->db->where_in('survey.client_id', $data['client_id']);
			}
			if(isset($data['state_id']) && (count($data['state_id']) > 0)) {
				$this->db->where_in('survey.state_id', $data['state_id']);
			}
			if(isset($data['district_id']) && (count($data['district_id']) > 0)) {
				$this->db->where_in('survey.dist_id', $data['district_id']);
			}
			if((isset($data['start_date']) && strlen($data['start_date']) > 0)
			&& (isset($data['end_date']) && strlen($data['end_date']) > 0)) {
				$this->db->where('survey.datetime >=', $data['start_date'].' 00:00:00');
				$this->db->where('survey.datetime <=', $data['end_date'].' 23:59:59');
			}
			$surveydata = $this->db->get($survey_table.' AS survey')->num_rows();

			// Push all to $surveys
			array_push($surveys, array(
				'id' => $sid,
				'name' => $survey['title'],
				'count' => $surveydata
			));
		}
		
		// Return data
		$this->jsonify(array(
			'status' => 1,
			'surveys' => $surveys,
			'check_in_out' => $check_in_out
		));
	}
}