<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helper_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function all_units()
	{
		return $all_units = $this->db->where('UNIT_STATUS', 1)->get('lkp_unit')->result_array();
	}

	// public function all_divisions($UNIT = NULL)
	// {
	// 	$this->db->where('ld.DIVISION_STATUS', 1);
	// 	if(!is_null($UNIT)) {
	// 		$this->db->join('lkp_unit AS lu', 'lu.plant_code = ld.WERKS');
	// 		$this->db->where('lu.UNIT_ID', $UNIT);
	// 	}
	// 	return $all_divisions = $this->db->get('lkp_division AS ld')->result_array();
	// }

	// public function all_circles($DIV_CODE = NULL)
	// {
	// 	if(!is_null($DIV_CODE)) $this->db->where_in('DIV_CODE', $DIV_CODE);
	// 	return $all_circles = $this->db->where('CIRCLE_STATUS', 1)->get('lkp_circle')->result_array();
	// }





	public function all_agencies()
	{
		return $all_agencies = $this->db->where('UNIT_STATUS', 1)->get('lkp_unit')->result_array();
	}

	public function all_states($country = NULL)
	{
		if(!is_array($country) && !is_null($country)) $country = explode(',', $country);
		if(!is_null($country)) $this->db->where_in('country_id', $country);
		return $all_states = $this->db->where('status', 1)->get('lkp_state')->result_array();
	}
	public function all_client_states($client)
	{
		$this->db->distinct()->select('state.state_id, state.state_name');
		$this->db->join('tbl_client_location AS tcl', 'tcl.state_id = state.state_id');
		$this->db->where('tcl.status', 1)->where('tcl.client_id', $client);
		return $all_states = $this->db->get('lkp_state AS state')->result_array();
	}

	public function all_districts($state = NULL)
	{
		if(!is_array($state) && !is_null($state)) $state = explode(',', $state);
		if(!is_null($state)) $this->db->where_in('state_id', $state);
		return $all_districts = $this->db->where('status', 1)->get('lkp_district')->result_array();
	}
	public function all_client_districts($state = NULL, $client)
	{
		if(!is_array($state) && !is_null($state)) $state = explode(',', $state);

		$this->db->distinct()->select('dist.district_id, dist.district_name')->from('tbl_client_location AS tcl');
		$this->db->join('lkp_district AS dist', 'dist.district_id = tcl.district_id');
		$this->db->where('tcl.status', 1)->where('tcl.client_id', $client);
		if(!is_null($state)) $this->db->where_in('dist.state_id', $state);
		return $all_districts = $this->db->get()->result_array();
	}

	public function all_tehsils($district = NULL)
	{
		if(!is_array($district) && !is_null($district)) $district = explode(',', $district);
		if(!is_null($district)) $this->db->where_in('district_id', $district);
		return $all_tehsils = $this->db->where('tehsil_status', 1)->get('lkp_tehsil')->result_array();
	}

	public function all_blocks($tehsil = NULL)
	{
		if(!is_array($tehsil) && !is_null($tehsil)) $tehsil = explode(',', $tehsil);
		if(!is_null($tehsil)) $this->db->where_in('tehsil_id', $tehsil);
		return $all_blocks = $this->db->where('block_status', 1)->get('lkp_block')->result_array();
	}

	public function all_gps($block = NULL)
	{
		if(!is_array($block) && !is_null($block)) $block = explode(',', $block);
		if(!is_null($block)) $this->db->where_in('block_id', $block);
		return $all_gps = $this->db->where('grampanchayat_status', 1)->get('lkp_grampanchayat')->result_array();
	}

	public function all_villages($gp = NULL)
	{
		if(!is_array($gp) && !is_null($gp)) $gp = explode(',', $gp);
		if(!is_null($gp)) $this->db->where_in('grampanchayat_id', $gp);
		return $all_villages = $this->db->where('village_status', 1)->get('lkp_village')->result_array();
	}
}