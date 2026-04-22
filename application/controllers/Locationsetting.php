<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Locationsetting extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Common_model');
		$this->load->model('Dynamicmenu_model');
	}

	public function index()
	{
		if (($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		} else {
			$main_menu = $this->Dynamicmenu_model->menu_details();
			$header_result = array('main_menu' => $main_menu);

			// getting countries data 
			$table = 'lkp_country';
			$cols = array('*');
			$where = array('status' => 1);
			$countries = $this->Common_model->select_all($table, $cols, $where);

			// getting states data 
			$table1 = 'lkp_state s';
			$table2 = 'lkp_country c';
			$match_id1 = 's.country_id = c.country_id';
			$join = 'inner';
			$cols = array('s.*', 'c.name');
			$where = array('s.status' => 1);
			$states = $this->Common_model->join_tables($cols, $table1, $table2, $match_id1, $join, $where);
			// getting districts data 
			$table1 = 'lkp_country c';
			$table2 = 'lkp_state s';
			$table3 = 'lkp_district d';

			$match_id1 = 'c.country_id = s.country_id';
			$match_id2 = 's.state_id = d.state_id';
			$join = 'left';
			$cols = array("c.name", "s.state_name", "d.*");
			$where = array('d.status' => 1);
			$districts = $this->Common_model->select_join_three_tbl($cols, $table1, $table2, $table3, $match_id1, $match_id2, $join, $where);
			$data['countries'] = $countries;
			$data['states'] = $states;
			$data['districts'] = $districts;
			$this->load->view('header', $header_result);
			$this->load->view('setting/location', $data);
			$this->load->view('footer');
		}
	}

	public function getCountry()
	{
		$country_id = $this->input->post('country_id');
		$table = 'lkp_country';
		$cols = array("*");
		$where = array('country_id' => $country_id, 'status' => 1);
		$country_data = $this->Common_model->select_single($table, $cols, $where);
		echo  json_encode($country_data);
		exit();
	}
	public function getState()
	{
		$state_id = $this->input->post('state_id');
		$table1 = 'lkp_state s';
		$table2 = 'lkp_country c';
		$match_id1 = 's.country_id = c.country_id';
		$join = 'inner';
		$cols = array('s.*', 'c.name');
		$where = array('s.state_id' => $state_id, 's.status' => 1);
		$state_data = $this->Common_model->join_tables($cols, $table1, $table2, $match_id1, $join, $where);
		echo  json_encode($state_data);
		exit();
	}
	public function getDistrict()
	{
		$district_id = $this->input->post('district_id');
		$table1 = 'lkp_country c';
		$table2 = 'lkp_state s';
		$table3 = 'lkp_district d';
		$match_id1 = 'c.country_id = s.country_id';
		$match_id2 = 's.state_id = d.state_id';
		$join = 'inner';
		$cols = array("c.name", "s.state_name", "d.*");
		$where = array('d.district_id' => $district_id, 'd.status' => 1);
		$district_data = $this->Common_model->select_join_three_tbl($cols, $table1, $table2, $table3, $match_id1, $match_id2, $join, $where);
		echo  json_encode($district_data);
		exit();
	}
	public function deleteCountry()
	{
		$country_id = $this->input->post('country_id');
		$table = 'lkp_country';
		$data = array('status' => 0);
		$colsReturn = array();
		$where = array('country_id' => $country_id);
		$response_country = $this->Common_model->update($table, $data, $colsReturn, $where);
		$table = 'lkp_state';
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		$table = 'lkp_district';
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		if ($response_country) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	public function deleteState()
	{
		$state_id = $this->input->post('state_id');
		$table = 'lkp_state';
		$where = array('state_id' => $state_id);
		$data = array('status' => 0);
		$colsReturn = array();
		$response_state = $this->Common_model->update($table, $data, $colsReturn, $where);
		$table = 'lkp_district';
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		if ($response_state) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	public function deleteDistrict()
	{
		$district_id = $this->input->post('district_id');
		$table = 'lkp_district';
		$where = array('district_id' => $district_id);
		$data = array('status' => 0);
		$colsReturn = array();
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	public function filterState()
	{
		$country_id = $this->input->post('country_id');
		$table1 = 'lkp_state s';
		$table2 = 'lkp_country c';
		$match_id1 = 's.country_id = c.country_id';
		$join = 'inner';
		$cols = array('s.*', 'c.name');
		if ($country_id != "") {
			$where = array('s.country_id' => $country_id, 's.status' => 1);
		} else {
			$where = array('s.status' => 1);
		}
		$state_data = $this->Common_model->join_tables($cols, $table1, $table2, $match_id1, $join, $where);
		if ($state_data) {
			$data = $state_data;
		} else {
			$data = array('status' => 0);
		}
		echo  json_encode($data);
		exit();
	}
	public function filterDistrict()
	{
		$state_id = $this->input->post('state_id');
		$country_id = $this->input->post('country_id');
		$table1 = 'lkp_country c';
		$table2 = 'lkp_state s';
		$table3 = 'lkp_district d';
		$match_id1 = 'c.country_id = s.country_id';
		$match_id2 = 's.state_id = d.state_id';
		$join = 'inner';
		$cols = array("c.name", "s.state_name", "d.*");
		if ($state_id == "" && $country_id == "") {
			$where = array('d.status' => 1);
		} else if ($state_id != "") {
			$where = array('d.state_id' => $state_id, 'd.status' => 1);
		} else {
			$where = array('d.status' => 1, 'c.country_id' => $country_id);
		}
		$district_data = $this->Common_model->select_join_three_tbl($cols, $table1, $table2, $table3, $match_id1, $match_id2, $join, $where);
		if ($district_data) {
			$data = $district_data;
		} else {
			$data = array('status' => 0);
		}
		echo  json_encode($data);
		exit();
	}
	// add country 
	public function addCountry()
	{
		$name = $this->input->post('name');
		$code = $this->input->post('code');
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$data = array('name' => ucfirst($name), 'code' => strtoupper($code), 'lat' => $lat, 'lng' => $lng);
		$table = 'lkp_country';
		$where = "country_id";
		$cols = array();
		$response = $this->Common_model->insert($table, $data, $where, $cols);
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	// update country 
	public function updateCountry()
	{
		$country_id = $this->input->post('country_id');
		$name = $this->input->post('name');
		$code = $this->input->post('code');
		$data = array('name' => ucfirst($name), 'code' => strtoupper($code));
		$table = 'lkp_country';
		$colsReturn = array();
		$where = array('country_id' => $country_id);
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	// update state 
	public function updateState()
	{
		$state_id = $this->input->post('state_id');
		$state_name = $this->input->post('state_name');
		$data = array('state_name' => ucfirst($state_name));
		$table = 'lkp_state';
		$colsReturn = array();
		$where = array('state_id' => $state_id);
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	// update state 
	public function addState()
	{
		$country_id = $this->input->post('country_id');
		$state_name = $this->input->post('state_name');
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$data = array('state_name' => $state_name);
		$data = array('country_id' => $country_id, 'state_name' => ucfirst($state_name), 'lat' => $lat, 'lng' => $lng, 'status' => 1);
		$table = 'lkp_state';
		$cols = array();
		$where = 'state_id';
		$response = $this->Common_model->insert($table, $data, $where, $cols);;
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	// update district 
	public function addDistrict()
	{
		$country_id = $this->input->post('country_id');
		$state_id = $this->input->post('state_id');
		$district_name = $this->input->post('district_name');
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');
		$data = array('country_id' => $country_id, 'state_id' => $state_id, 'district_name' => ucfirst($district_name), 'lat' => $lat, 'lng' => $lng, 'status' => 1);
		$table = 'lkp_district';
		$cols = array();
		$where = 'district_id';
		$response = $this->Common_model->insert($table, $data, $cols, $where);
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	// update district 
	public function updateDistrict()
	{
		$district_id = $this->input->post('district_id');
		$district_name = $this->input->post('district_name');
		$data = array('district_name' => ucfirst($district_name));
		$table = 'lkp_district';
		$colsReturn = array();
		$where = array('district_id' => $district_id);
		$response = $this->Common_model->update($table, $data, $colsReturn, $where);
		if ($response) {
			$res = array('status' => 1);
		} else {
			$res = array('status' => 0);
		}
		echo json_encode($res);
		exit();
	}
	/* check duplication */
	public function checkCountry()
	{
		$name = $this->input->post('name');
		$code = $this->input->post('code');
		$table = 'lkp_country';
		$where = array('name' => ucfirst($name));
		$response_name = $this->Common_model->checkDuplicate($table, $where);
		$where = array('code' => strtoupper($code));
		$response_code = $this->Common_model->checkDuplicate($table, $where);
		$res = array();
		if ($response_name) {
			$res['status_name'] = 1;
		} else {
			$res['status_name'] = 0;
		}
		if ($response_code) {
			$res['status_code'] = 1;
		} else {
			$res['status_code'] = 0;
		}
		echo  json_encode($res);
		exit();
	}
	public function checkState()
	{
		$country_id = $this->input->post('country_id');
		$state_name = $this->input->post('state_name');
		$table = 'lkp_state';
		$where = array('country_id' => $country_id, 'state_name' => ucfirst($state_name));
		$response = $this->Common_model->checkDuplicate($table, $where);
		if ($response) {
			$res = array("status" => 1);
		} else {
			$res = array("status" => 0);
		}
		echo  json_encode($res);
		exit();
	}
	public function checkDistrict()
	{
		$country_id = $this->input->post('country_id');
		$state_id = $this->input->post('state_id');
		$district_name = $this->input->post('district_name');
		$table = 'lkp_district';
		$where = array('country_id' => $country_id, 'state_id' => $state_id, 'district_name' => ucfirst($district_name));
		$response = $this->Common_model->checkDuplicate($table, $where);
		if ($response) {
			$res = array("status" => 1);
		} else {
			$res = array("status" => 0);
		}
		echo  json_encode($res);
		exit();
	}
}
