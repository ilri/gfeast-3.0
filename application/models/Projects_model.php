<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function all_project_1()
	{
		$this->db->distinct()->select('lp.proj_id, lp.proj_name, lp.proj_description, lp.proj_reg_date, lp.status');
		$this->db->from('lkp_projects AS lp');
		if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2) {
			// $this->db->join('rpt_project_partner_user_location AS rppul', 'rppul.lkp_project_id = lp.proj_id');
			// $this->db->where('rppul.user_id', $this->session->userdata('login_id'))->where('rppul.project_user_loc_status', 1);
		}
		$all_projects = $this->db->where('lp.proj_id', 1)->where('lp.status', 1)->get()->result_array();
		foreach ($all_projects as $key => $project) {
			// $this->db->select('id, partner_id')->from('rpt_partner_project')->where('status', 1);
			// $partners = $this->db->where('proj_id', $project['proj_id'])->get();
			// $all_projects[$key]['partners'] = $partners->num_rows();
			$all_projects[$key]['partners'] = 0;

			// $this->db->distinct()->select('user_id')->from('rpt_project_partner_user_location')->where('project_user_loc_status', 1);
			// $users = $this->db->where('lkp_project_id', $project['proj_id'])->get();
			// $all_projects[$key]['users'] = $users->num_rows();
			$all_projects[$key]['users'] = 0;
		}

		return $all_projects;
	}	

	public function all_project()
	{
		$org_projects = $this->all_org_project();
		$ass_projects = $this->all_assigned_project();

		$projs = array();
		foreach ($org_projects as $key => $value) {
			array_push($projs, $value['proj_id']);
		}
		foreach ($ass_projects as $key => $value) {
			if(!in_array($value['proj_id'], $projs)) array_push($org_projects, $value);
		}
		return $org_projects;
	}

	public function all_org_project()
	{
		$this->db->distinct()->select('lp.proj_id, lp.proj_name, lp.proj_description, lp.proj_loc_depth, lp.proj_reg_date, lp.status');
		$this->db->from('lkp_projects AS lp');
		if($this->session->userdata('role') >= 3) {
			$this->db->join('rpt_user_relation AS rur', 'rur.lkp_org_id = lp.proj_org_id');
			$this->db->where('rur.user_id', $this->session->userdata('login_id'))->where('rur.status', 1);
		}
		$all_projects = $this->db->where('lp.status', 1)->get()->result_array();
		// foreach ($all_projects as $key => $project) {
		// 	$this->db->select('id, partner_id')->from('rpt_partner_project')->where('status', 1);
		// 	$partners = $this->db->where('project_id', $project['project_id'])->get();
		// 	$all_projects[$key]['partners'] = $partners->num_rows();

		// 	$this->db->distinct()->select('user_id')->from('rpt_project_partner_user_location')->where('project_user_loc_status', 1);
		// 	$users = $this->db->where('lkp_project_id', $project['project_id'])->get();
		// 	$all_projects[$key]['users'] = $users->num_rows();
		// }

		return $all_projects;
	}

	public function all_assigned_project()
	{
		$this->db->distinct()->select('lp.id, lp.project_name, lp.status');
		$this->db->from('lkp_country_projects AS lp');
		if($this->session->userdata('role') >= 3) {
			$this->db->join('tbl_user_unit_location AS rufl', 'rufl.project_id = lp.id');
			$this->db->where('rufl.user_id', $this->session->userdata('login_id'))->where('rufl.status', 1);
		}
		$all_projects = $this->db->where('lp.status', 1)->get()->result_array();
		// foreach ($all_projects as $key => $project) {
		// 	$this->db->select('id, partner_id')->from('rpt_partner_project')->where('status', 1);
		// 	$partners = $this->db->where('project_id', $project['project_id'])->get();
		// 	$all_projects[$key]['partners'] = $partners->num_rows();

		// 	$this->db->distinct()->select('user_id')->from('rpt_project_partner_user_location')->where('project_user_loc_status', 1);
		// 	$users = $this->db->where('lkp_project_id', $project['project_id'])->get();
		// 	$all_projects[$key]['users'] = $users->num_rows();
		// }

		return $all_projects;
	}

	public function get_project_details($data)
	{
		$this->db->select('proj_id, proj_name, proj_description, proj_reg_date, status');
		$project = $this->db->where('proj_id', $data['project_id'])->where('status', 1)->get('lkp_projects');

		if($project->num_rows() === 0) {
			return false;
		} else {
			return $project->row_array();
		}
	}

	public function get_project_locations($data)
	{
		$this->db->select('proj_id, proj_name, proj_description, proj_reg_date, status');
		$project = $this->db->where('proj_id', $data['project_id'])->where('status', 1)->get('lkp_projects');

		if($project->num_rows() === 0) {
			return false;
		}

		$partIds = array();
		// $this->db->select('id, partner_id')->from('rpt_partner_project')->where('status', 1);
		// $partners = $this->db->where('proj_id', $data['project_id'])->get()->result_array();
		// foreach ($partners as $partner) {
		// 	if(!in_array($partner['partner_id'], $partIds)) {
		// 		array_push($partIds, $partner['partner_id']);
		// 	}
		// }
		if(count($partIds) == 0) $partIds = array(0);

		$this->db->distinct()->select('lc.name AS country, ls.state_name AS state, ld.district_name AS dist, lb.block_name AS block, lv.village_name AS village');
		$this->db->join('lkp_country AS lc', 'lc.country_id = rpl.lkp_country_id');
		$this->db->join('lkp_state AS ls', 'ls.state_id = rpl.lkp_state_id');
		$this->db->join('lkp_district AS ld', 'ld.district_id = rpl.lkp_district_id');
		$this->db->join('lkp_block AS lb', 'lb.block_id = rpl.lkp_block_id');
		$this->db->join('lkp_village AS lv', 'lv.village_id = rpl.lkp_village_id');
		$locations = $this->db->where_in('rpl.partner_id', $partIds)->get('rpt_partner_location AS rpl')->result_array();

		return $locations;
	}

	public function get_project_partners($data)
	{
		$this->db->select('proj_id, proj_name, proj_description, proj_reg_date, status');
		$project = $this->db->where_in('proj_id', $data['project_id'])->where('status', 1)->get('lkp_projects');

		if($project->num_rows() === 0) {
			return false;
		}
		$partners = array();
		// $this->db->distinct()->select('lp.partner_id, lp.partner_name, lp.partner_email, lp.nature_of_business, lp.address, lp.postcode, lp.country, lp.telephone, lp.fax, lp.proj_reg_date, lp.status');
		// $this->db->join('lkp_partners AS lp', 'lp.partner_id = rpp.partner_id');
		// $this->db->where_in('rpp.proj_id', $data['project_id'])->where('rpp.status', 1);
		// $this->db->having('count(distinct rpp.proj_id) = '.count($data['project_id']));
		// $partners = $this->db->group_by('rpp.partner_id')->get('rpt_partner_project AS rpp')->result_array();

		return $partners;
	}

	public function add_project($data)
	{
		$query = $this->db->insert('lkp_projects', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_country_project($data)
	{
		$query = $this->db->insert('lkp_country_projects', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_country_site($data, $regionArray)
	{
		$query = $this->db->insert('lkp_project_site', $data);
		if($query) {

			$siteId = $this->db->insert_id();

			foreach ($regionArray['majorRegionArray'] as $key => $value) {
				$data = array(
					'project_id' => $data['project_id'],
					'site_id' => $siteId,
					'major_region_name' => htmlspecialchars($value, ENT_QUOTES),
					'user_id' => $this->session->userdata('login_id'),
					'datetime' =>  date('Y-m-d H:i:s'),
					'status' => 1,
				);

				$query = $this->db->insert('lkp_major_region', $data);

				if(!$query){
					return false;
				}
			}

			foreach ($regionArray['minorRegionArray'] as $key => $value) {
				$data = array(
					'project_id' => $data['project_id'],
					'site_id' => $siteId,
					'minor_region_name' => htmlspecialchars($value, ENT_QUOTES),
					'user_id' => $this->session->userdata('login_id'),
					'datetime' =>  date('Y-m-d H:i:s'),
					'status' => 1,
				);	

				$query = $this->db->insert('lkp_minor_region', $data);

				if(!$query){
					return false;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function update_country_project($project_id, $data)
	{
		// Specify which project to update using the project ID
		$this->db->where('id', $project_id);
		$query = $this->db->update('lkp_country_projects', $data);
		return $query; // Will return true on success, false on failure
	}

	public function update_country_site($siteId, $data, $regionArray)
	{
		// Specify which project to update using the project ID
		$this->db->where('id', $siteId);
		$query = $this->db->update('lkp_project_site', $data);

		if($query) {
			$deleteQueryData = array(
				'status' => 0,
			);
			$deletemajorRegion = $this->db->where('site_id', $siteId)->update('lkp_major_region', $deleteQueryData);
			foreach ($regionArray['majorRegionArray'] as $key => $value) {
				$data = array(
					'project_id' => $data['project_id'],
					'site_id' => $siteId,
					'major_region_name' => htmlspecialchars($value, ENT_QUOTES),
					'user_id' => $this->session->userdata('login_id'),
					'datetime' =>  date('Y-m-d H:i:s'),
					'status' => 1,
				);

				$query = $this->db->insert('lkp_major_region', $data);

				if(!$query){
					return false;
				}
			}

			$deleteminorRegion = $this->db->where('site_id', $siteId)->update('lkp_minor_region', $deleteQueryData);
			foreach ($regionArray['minorRegionArray'] as $key => $value) {
				$data = array(
					'project_id' => $data['project_id'],
					'site_id' => $siteId,
					'minor_region_name' => htmlspecialchars($value, ENT_QUOTES),
					'user_id' => $this->session->userdata('login_id'),
					'datetime' =>  date('Y-m-d H:i:s'),
					'status' => 1,
				);	

				$query = $this->db->insert('lkp_minor_region', $data);

				if(!$query){
					return false;
				}
			}
		}

		return true; // Will return true on success, false on failure
	}

	public function edit_project($data)
	{
		$query = $this->db->where($data['where'])->update('lkp_projects', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function update_project_status($projectId, $data, $table_name)
	{
		$this->db->where('id', $projectId);
		return $this->db->update($table_name, $data);
	}

	public function get_country_projects()
	{
		$user_id = $this->session->userdata('login_id');
        $this->db->select('user_id')
                ->from('tbl_users')
                ->where('role_id', 6)
                ->where('user_id !=', $user_id);
        $adminUsersResult = $this->db->get()->result_array();
        $adminUsers = array_column($adminUsersResult, 'user_id');

		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where('user_id', $this->session->userdata('login_id'));
		}
		if($this->session->userdata('role') == 6){
            $this->db->group_start()
                    ->where('project_type', 'Public')
                    ->or_where('user_id', $user_id)
                    ->group_end();
			if (!empty($adminUsers)) {
				$this->db->where_not_in('user_id', $adminUsers);
			}
		}
		$query = $this->db->order_by('project_name')->get('lkp_country_projects');
		return $query->result_array(); // Convert result to array
	}

	public function get_world_region()
	{
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_world_region');
		return $query->result_array(); // Convert result to array
	}

	public function get_major_region()
	{
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_major_region');
		return $query->result_array(); // Convert result to array
	}

	public function get_minor_region()
	{
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_minor_region');
		return $query->result_array(); // Convert result to array
	}

	public function get_countries()
	{
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_country');
		return $query->result_array(); // Convert result to array
	}

	public function get_communities_type()
	{
		if($this->session->userdata('role') == 8){
			$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
		}
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_communities_type');
		return $query->result_array(); // Convert result to array
	}

	public function get_currency()
	{
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_currency');
		return $query->result_array(); // Convert result to array
	}

	public function get_states()
	{
		$this->db->select('state_id, state_name');
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_state');
		return $query->result_array(); // Convert result to array
	}

	public function get_districts()
	{
		$this->db->select('district_id, district_name');
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_district');
		return $query->result_array(); // Convert result to array
	}

	public function get_country_sites($project_id = null)
	{
		if($project_id != null){
			$this->db->where('project_id', $project_id);
		}
		$this->db->where('status', 1);
		if($this->session->userdata('role') == 8){
			$this->db->where('user_id', $this->session->userdata('login_id'));
		}
		$query = $this->db->order_by('site_name')->get('lkp_project_site');
		return $query->result_array(); // Convert result to array
	}

	public function get_project_info($project_id)
	{
		$this->db->where('id', $project_id);
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_country_projects');
		return $query->row_array(); // Convert result to array
	}

	public function get_sites_major_region($projectId, $siteId)
	{
		$this->db->where('status', 1);
		$this->db->where('project_id', $projectId);
		$this->db->where('site_id', $siteId);
		return $query = $this->db->get('lkp_major_region')->result_array();
	}


	public function get_sites_minor_region($projectId, $siteId, $majorRegionId)
	{
		$this->db->where('status', 1);
		$this->db->where('project_id', $projectId);
		$this->db->where('site_id', $siteId);
		$this->db->where('major_region_id', $majorRegionId);
		return $query = $this->db->get('lkp_minor_region')->result_array();
	}

	public function add_major_region($data)
	{
		$query = $this->db->insert('lkp_major_region', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_minor_region($data)
	{
		$query = $this->db->insert('lkp_minor_region', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function update_major_region($regionId, $data)
	{
		// Specify which project to update using the project ID
		$this->db->where('id', $regionId);
		$query = $this->db->update('lkp_major_region', $data);
		return $query; // Will return true on success, false on failure
	}

	public function update_minor_region($regionId, $data)
	{
		// Specify which project to update using the project ID
		$this->db->where('id', $regionId);
		$query = $this->db->update('lkp_minor_region', $data);
		return $query; // Will return true on success, false on failure
	}
	
	public function update_region_status($projectId, $data, $table_name)
	{
		$this->db->where('id', $projectId);
		return $this->db->update($table_name, $data);
	}
}