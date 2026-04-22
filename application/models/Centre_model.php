<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centre_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function all_centre()
	{
		$this->db->distinct()->select('lc.centre_id, lc.centre_name, lc.added_datetime, lc.status');
		$this->db->from('lkp_centre AS lc');
		if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2) {
			$this->db->join('rpt_centre_user AS rcu', 'rcu.centre_id = lc.centre_id', 'left');
			$this->db->where('(
				(lc.added_by = '.$this->session->userdata('login_id').'
				AND (rcu.status = 1 OR rcu.status IS NULL))
			OR 
				(rcu.user_id = '.$this->session->userdata('login_id').' AND rcu.status = 1)
			)');
		}
		$all_centre = $this->db->where('lc.status', 1)->get()->result_array();
		foreach ($all_centre as $key => $centre) {
			$this->db->select('id')->from('rpt_centre_partner')->where('centre_id', $centre['centre_id']);
			$all_centre[$key]['partners'] = $this->db->where('status', 1)->get()->num_rows();

			$this->db->select('id')->from('rpt_centre_user')->where('centre_id', $centre['centre_id']);
			$all_centre[$key]['users'] = $this->db->where('status', 1)->get()->num_rows();
		}

		return $all_centre;
	}

	public function all_batch()
	{
		$this->db->distinct()->select('lb.batch_id, lb.centre_id, lb.batch_name, lb.added_datetime, lb.status, lc.centre_name');
		$this->db->from('lkp_batch AS lb');
		$this->db->join('lkp_centre AS lc', 'lc.centre_id = lb.centre_id');
		if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2) {
			$this->db->join('rpt_centre_user AS rcu', 'rcu.centre_id = lb.centre_id', 'left');
			$this->db->where('(
				(lb.added_by = '.$this->session->userdata('login_id').'
				AND (rcu.status = 1 OR rcu.status IS NULL))
			OR (rcu.user_id = '.$this->session->userdata('login_id').' AND rcu.status = 1))');
		}
		$all_batch = $this->db->where('lb.status', 1)->where('lc.status', 1)->get()->result_array();
		foreach ($all_batch as $key => $batch) {
			$this->db->select('id')->from('rpt_centre_partner')->where('centre_id', $batch['centre_id']);
			$all_batch[$key]['partners'] = $this->db->where('status', 1)->get()->num_rows();

			$this->db->select('id')->from('rpt_centre_user')->where('centre_id', $batch['centre_id']);
			$all_batch[$key]['users'] = $this->db->where('status', 1)->get()->num_rows();
		}

		return $all_batch;
	}

	public function get_centre_details($data)
	{
		$this->db->select('centre_id, centre_name, added_datetime, status');
		$this->db->where('centre_id', $data['centre_id'])->where('status', 1);
		$centre = $this->db->get('lkp_centre');

		if($centre->num_rows() === 0) {
			return false;
		}

		$centre = $centre->row_array();
		$this->db->select('country, state, dist')->from('rpt_centre_location');
		$this->db->where('centre_id', $data['centre_id'])->where('status', 1);
		$centre['locations'] = $this->db->get()->result_array();

		$this->load->model('Helper_model');
		$countries = $this->Helper_model->all_countries();
		foreach ($centre['locations'] as $key => $loc) {
			$states = $this->Helper_model->all_states($loc['country']);
			$dists = $this->Helper_model->all_dists($loc['state']);

			$centre['locations'][$key]['countries'] = $countries;
			$centre['locations'][$key]['states'] = $states;
			$centre['locations'][$key]['dists'] = $dists;
		}

		$centre['partners'] = array();
		$this->db->where('status', 1)->where('centre_id', $data['centre_id']);
		$partners_in_centre = $this->db->get('rpt_centre_partner')->result_array();
		foreach ($partners_in_centre as $key => $part) {
			array_push($centre['partners'], $part['partner_id']);
		}
		$centre['users'] = array();
		$this->db->where('status', 1)->where('centre_id', $data['centre_id']);
		$users_in_centre = $this->db->get('rpt_centre_user')->result_array();
		foreach ($users_in_centre as $key => $user) {
			array_push($centre['users'], $user['user_id']);
		}

		$this->load->model('Partners_model');
		$partners = $this->Partners_model->all_partner();

		$this->load->model('User_model');
		$users = $this->User_model->all_users($centre['users']);

		return array(
			'users' => $users,
			'centre' => $centre,
			'partners' => $partners
		);
	}

	public function get_centre_locations($data)
	{
		$this->db->select('centre_id, centre_name, added_datetime, status');
		$this->db->where('centre_id', $data['centre_id'])->where('status', 1);
		$centre = $this->db->get('lkp_centre');

		if($centre->num_rows() === 0) {
			return false;
		}

		$centre = $centre->row_array();
		$this->db->distinct()->select('lc.name AS country, ls.state_name AS state, ld.district_name AS dist');
		$this->db->join('lkp_country AS lc', 'lc.country_id = rcl.country');
		$this->db->join('lkp_state AS ls', 'ls.state_id = rcl.state');
		$this->db->join('lkp_district AS ld', 'ld.district_id = rcl.dist');
		$locations = $this->db->where('rcl.centre_id', $data['centre_id'])->get('rpt_centre_location AS rcl')->result_array();

		return $locations;
	}

	public function add_centre($data)
	{
		$query = $this->db->insert('lkp_centre', $data);
		if($query) {
			$insertId = $this->db->insert_id();
			return array('centre_id' => $insertId);
		} else {
			return false;
		}
	}

	public function edit_centre($data)
	{
		$query = $this->db->where($data['where'])->update('lkp_centre', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_centre_partner($data)
	{
		$query = $this->db->insert('rpt_centre_partner', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function edit_centre_partner($data)
	{
		$query = $this->db->where($data['where'])->update('rpt_centre_partner', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_centre_user($data)
	{
		$query = $this->db->insert('rpt_centre_user', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function edit_centre_user($data)
	{
		$query = $this->db->where($data['where'])->update('rpt_centre_user', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_centre_batch($data)
	{
		$query = $this->db->insert('lkp_batch', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function edit_centre_batch($data)
	{
		$query = $this->db->where($data['where'])->update('lkp_batch', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_location($data)
	{
		$query = $this->db->insert('rpt_centre_location', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function edit_location($data)
	{
		$query = $this->db->where($data['where'])->update('rpt_centre_location', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_location($data)
	{
		$query = $this->db->where($data['where'])->delete('rpt_centre_location');
		if($query) {
			return true;
		} else {
			return false;
		}
	}
}