<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function all_partner()
	{
		$this->db->distinct()->select('lp.partner_id, lp.partner_name, lp.partner_email, lp.nature_of_business, lp.address, lp.postcode, lp.country, lp.telephone, lp.fax, lp.added_datetime, lp.status');
		$this->db->from('lkp_partners AS lp');
		if($this->session->userdata('role') > 3) {
			$this->db->join('rpt_project_partner_user_location AS rppul', 'rppul.lkp_partner_id = lp.partner_id');
			$this->db->where('rppul.user_id', $this->session->userdata('login_id'))->where('rppul.project_user_loc_status', 1);
		}
		$all_partners = $this->db->where('lp.status', 1)->get()->result_array();
		foreach ($all_partners as $key => $partner) {
			$this->db->select('id')->from('rpt_partner_project')->where('partner_id', $partner['partner_id']);
			$all_partners[$key]['projects'] = $this->db->where('status', 1)->get()->num_rows();
		}

		return $all_partners;
	}

	public function get_partner_details($data)
	{
		$this->db->select('partner_id, partner_name, partner_email, nature_of_business, address, postcode, country, telephone, fax, added_datetime, status');
		$partner = $this->db->where('partner_id', $data['partner_id'])->where('status', 1)->get('lkp_partners');

		if($partner->num_rows() === 0) {
			return false;
		}

		$partner = $partner->row_array();
		$partner['projects'] = array();
		$this->db->where('status', 1)->where('partner_id', $data['partner_id']);
		$projects_in_partner = $this->db->get('rpt_partner_project')->result_array();
		foreach ($projects_in_partner as $key => $proj) {
			array_push($partner['projects'], $proj['project_id']);
		}

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->all_project();

		return array(
			'partner' => $partner,
			'projects' => $projects
		);
	}

	public function get_partner_locations($data)
	{
		$this->db->select('partner_id, partner_name, partner_email, nature_of_business, address, postcode, country, telephone, fax, added_datetime, status');
		$partner = $this->db->where('partner_id', $data['partner_id'])->where('status !=', 0)->get('lkp_partners');

		if($partner->num_rows() === 0) {
			return false;
		}
		$partner = $partner->row_array();

		if($partner['status'] == 1) {
			$this->db->distinct()->select('lc.name AS country, ls.state_name AS state, ld.district_name AS dist, lb.block_name AS block, lv.village_name AS village');
			$this->db->join('lkp_country AS lc', 'lc.country_id = rpl.lkp_country_id');
			$this->db->join('lkp_state AS ls', 'ls.state_id = rpl.lkp_state_id');
			$this->db->join('lkp_district AS ld', 'ld.district_id = rpl.lkp_district_id');
			$this->db->join('lkp_block AS lb', 'lb.block_id = rpl.lkp_block_id');
			$this->db->join('lkp_village AS lv', 'lv.village_id = rpl.lkp_village_id');
			$locations = $this->db->where('rpl.partner_id', $partner['partner_id'])->get('rpt_partner_location AS rpl')->result_array();
		} else {
			$this->db->distinct()->select('lc.name AS country, ls.state_name AS state, ld.district_name AS dist, lb.block_name AS block, lv.village_name AS village');
			$this->db->join('lkp_country AS lc', 'lc.country_id = lv.country_id');
			$this->db->join('lkp_state AS ls', 'ls.state_id = lv.state_id');
			$this->db->join('lkp_district AS ld', 'ld.district_id = lv.dist_id');
			$this->db->join('lkp_block AS lb', 'lb.block_id = lv.block_id');
			$locations = $this->db->where('lv.village_status', 1)->get('lkp_village AS lv')->result_array();
		}

		return $locations;
	}
	public function get_partner_locations_nested($data)
	{
		$this->db->select('partner_id, partner_name, partner_email, nature_of_business, address, postcode, country, telephone, fax, added_datetime, status');
		$partner = $this->db->where('partner_id', $data['partner_id'])->where('status !=', 0)->get('lkp_partners');

		if($partner->num_rows() === 0) {
			return false;
		}
		$partner = $partner->row_array();

		if($partner['status'] == 1) {
			$this->db->distinct()->select('lc.country_id AS id, lc.name AS country');
			$this->db->join('lkp_country AS lc', 'lc.country_id = rpl.lkp_country_id');
			$this->db->where('rpl.partner_id', $partner['partner_id']);
			$countries = $this->db->get('rpt_partner_location AS rpl')->result_array();
			
			foreach ($countries as $ckey => $country) {
				$this->db->distinct()->select('ls.state_id AS id, ls.state_name AS state');
				$this->db->join('lkp_state AS ls', 'ls.state_id = rpl.lkp_state_id');
				$this->db->where('rpl.partner_id', $partner['partner_id'])->where('rpl.lkp_country_id', $country['id']);
				$states = $this->db->get('rpt_partner_location AS rpl')->result_array();

				foreach ($states as $skey => $state) {
					$this->db->distinct()->select('ld.district_id AS id, ld.district_name AS district');
					$this->db->join('lkp_district AS ld', 'ld.district_id = rpl.lkp_district_id');
					$this->db->where('rpl.partner_id', $partner['partner_id'])->where('rpl.lkp_state_id', $state['id']);
					$districts = $this->db->get('rpt_partner_location AS rpl')->result_array();

					foreach ($districts as $dkey => $district) {
						$this->db->distinct()->select('lb.block_id AS id, lb.block_name AS block');
						$this->db->join('lkp_block AS lb', 'lb.block_id = rpl.lkp_block_id');
						$this->db->where('rpl.partner_id', $partner['partner_id'])->where('rpl.lkp_district_id', $district['id']);
						$blocks = $this->db->get('rpt_partner_location AS rpl')->result_array();

						foreach ($blocks as $bkey => $block) {
							$this->db->distinct()->select('lv.village_id AS id, lv.village_name AS village');
							$this->db->join('lkp_village AS lv', 'lv.village_id = rpl.lkp_village_id');
							$this->db->where('rpl.partner_id', $partner['partner_id'])->where('rpl.lkp_block_id', $block['id']);
							$villages = $this->db->get('rpt_partner_location AS rpl')->result_array();

							$blocks[$bkey]['villages'] = $villages;
						}
						$districts[$dkey]['blocks'] = $blocks;
					}
					$states[$skey]['districts'] = $districts;
				}
				$countries[$ckey]['states'] = $states;
			}
		} else {
			$this->db->distinct()->select('lc.country_id AS id, lc.name AS country');
			$countries = $this->db->where('lc.status', 1)->get('lkp_country AS lc')->result_array();
			
			foreach ($countries as $ckey => $country) {
				$this->db->distinct()->select('ls.state_id AS id, ls.state_name AS state');
				$this->db->where('ls.status', 1)->where('ls.country_id', $country['id']);
				$states = $this->db->get('lkp_state AS ls')->result_array();

				foreach ($states as $skey => $state) {
					$this->db->distinct()->select('ld.district_id AS id, ld.district_name AS district');
					$this->db->where('ld.status', 1)->where('ld.state_id', $state['id']);
					$districts = $this->db->get('lkp_district AS ld')->result_array();

					foreach ($districts as $dkey => $district) {
						$this->db->distinct()->select('lb.block_id AS id, lb.block_name AS block');
						$this->db->where('lb.block_status', 1)->where('lb.dist_id', $district['id']);
						$blocks = $this->db->get('lkp_block AS lb')->result_array();

						foreach ($blocks as $bkey => $block) {
							$this->db->distinct()->select('lv.village_id AS id, lv.village_name AS village');
							$this->db->where('lv.village_status', 1)->where('lv.block_id', $block['id']);
							$villages = $this->db->get('lkp_village AS lv')->result_array();

							$blocks[$bkey]['villages'] = $villages;
						}
						$districts[$dkey]['blocks'] = $blocks;
					}
					$states[$skey]['districts'] = $districts;
				}
				$countries[$ckey]['states'] = $states;
			}
		}

		return $countries;
	}

	public function add_partner($data)
	{
		$query = $this->db->insert('lkp_partners', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function edit_partner($data)
	{
		$query = $this->db->where($data['where'])->update('lkp_partners', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}

	public function add_partner_project($data)
	{
		$query = $this->db->insert('rpt_partner_project', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function edit_partner_project($data)
	{
		$query = $this->db->where($data['where'])->update('rpt_partner_project', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
}