<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		$this->load->model('Helper_model');
		$this->load->model('Reports_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Projects_model');
		$this->load->model('Dynamicmenu_model');

		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}

	public function index(){
		if(($this->session->userdata('login_id') == '')) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				echo json_encode(array(
					'status' => 0,
					'msg' => 'Session Expired! Please login again to continue.'
				));
				exit();
			} else {
				redirect($baseurl);
			}
		}

		$result = array();

		$survey_id = 2;
		$result = $this->Reports_model->survey_details($survey_id);
		// $result['divisions'] = $this->Helper_model->all_divisions();

		$result['farmer_registered'] = $this->Dashboard_model->farmer_registered();
		$result['total_area'] = $this->Dashboard_model->total_area();

		$result['total_res'] = $this->Dashboard_model->total_res();
		$result['total_plot'] = $this->Dashboard_model->total_plot();
		$result['location_array'] = $this->Dashboard_model->location_data();
		$result['famers_byvillage'] = $this->Dashboard_model->famers_byvillage();
		$result['plotsregisterd_agrementdone'] = $this->Dashboard_model->plotsregisterd_agrementdone();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$result['status'] = 1;
			echo json_encode($result);
			exit();
		}else{
			$this->load->view('header');
			$this->load->view('sidebar');
			$this->load->view('dashboard/dashboard', $result);
			$this->load->view('footer');
		}
	}

	public function plot_info()
	{
		if(($this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}

		$plot_id = $this->uri->segment(3);

		if($plot_id == '') show_404();

		
		$survey_id = 2;
		$result = $this->Reports_model->survey_details($survey_id);
		$result['plot_databyid'] = $this->Dashboard_model->plot_databyid($plot_id);	

		if(!$result['plot_databyid']) show_404();


		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('dashboard/plot_info', $result);
		$this->load->view('footer');
	}	

	//dashboard
	public function view_dashboard(){
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			
			$user_id = $this->session->userdata('login_id');
			$role_id = $this->session->userdata('role');

			$projects_list = $this->Dashboard_model->projects_list();

			if(count(array_filter($projects_list)) >0){
				$project_surveys = $this->Dashboard_model->project_surveys($projects_list);
			}else{
				$project_surveys = array();
			}
			
			$result = array();

			$result['project_surveys'] = $project_surveys;
		
			// if ($role_id == 1) {
			// 	$world_region = $this->Projects_model->get_world_region();
			// 	$major_region = $this->Projects_model->get_major_region();
			// 	// $countries = $this->Projects_model->get_countries();
			// 	$this->db->select('lkp_country.*, lkp_project_site.id as site_id');
			// 	$this->db->from('lkp_country');
			// 	$this->db->join('lkp_project_site', 'lkp_country.country_id = lkp_project_site.country_id');
			// 	$countries = $this->db->where('lkp_country.status', 1)->where('lkp_project_site.status', 1)->get()->result_array();
			// 	$projects = $this->Projects_model->get_country_projects();
			// 	$sites = $this->Projects_model->get_country_sites();
		
			// 	$result['world_region'] = $world_region;
			// 	$result['major_region'] = $major_region;
			// 	$result['countries'] = $countries;
			// 	$result['projects'] = $projects;
			// 	$result['sites'] = $sites;
		
			// 	$result['state_list'] = $this->Helper_model->all_states();
			// 	$result['district_list'] = $this->Helper_model->all_districts();

			// } else {
			// 	$assigned_locations = $this->db->select(array('user_id', 'world_region_id', 'major_region_id', 'country_id', 'state_id', 'district_id'))->where('user_id', $user_id)->from('tbl_user_unit_location')->get()->result_array();
			// 	$world_region = array_unique(array_column($assigned_locations, 'world_region_id'));
			// 	$major_region = array_unique(array_column($assigned_locations, 'major_region_id'));
			// 	// $countries = array_unique(array_column($assigned_locations, 'country_id'));
			// 	$this->db->select('lkp_country.*, lkp_project_site.id as site_id');
			// 	$this->db->from('lkp_country');
			// 	$this->db->join('lkp_project_site', 'lkp_country.country_id = lkp_project_site.country_id');
			// 	$countries = $this->db->where('lkp_country.status', 1)->where('lkp_project_site.status', 1)->get()->result_array();
			// 	$states = array_unique(array_column($assigned_locations, 'state_id'));
			// 	$districts = array_unique(array_column($assigned_locations, 'district_id'));
			// 	/* $projects = $this->Projects_model->get_country_projects(!empty($countries) ? $countries : null);
			// 	$sites = $this->Projects_model->get_country_sites(!empty($countries) ? $countries : null); */
			// 	$projects = $this->Projects_model->get_country_projects();
			// 	$sites = $this->Projects_model->get_country_sites();
		
			// 	$result['world_region'] = $world_region;
			// 	$result['major_region'] = $major_region;
			// 	$result['countries'] = $countries;
			// 	$result['state_list'] = $states;
			// 	$result['district_list'] = $districts;
			// 	$result['projects'] = $projects;
			// 	$result['sites'] = $sites;

			// }

			// starts
			$projects = $this->Projects_model->get_country_projects();
			$sites = $this->Projects_model->get_country_sites();
			$this->db->select('lkp_country.*, lkp_project_site.id as site_id');
			$this->db->from('lkp_country');
			$this->db->join('lkp_project_site', 'lkp_country.country_id = lkp_project_site.country_id');
			$countries = $this->db->where('lkp_country.status', 1)->where('lkp_project_site.status', 1)->get()->result_array();
			$major_region = $this->Projects_model->get_major_region();
			$minor_region = $this->Projects_model->get_minor_region();
			$result['projects'] = $projects;
			$result['sites'] = $sites;
			$result['countries'] = $countries;		
			$result['major_region'] = $major_region;
			$result['minor_region'] = $minor_region;
			$result['country_list'] = $this->db->where('status', 1)->get('lkp_country')->result_array();        
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			$result['world_region'] = $this->Projects_model->get_world_region();
			// ends

			$main_menu = $this->Dynamicmenu_model->menu_details();
			$header_result = array('main_menu' => $main_menu);
			// echo print_r($result);exit;

			$this->load->view('header', $header_result);
			$this->load->view('dashboard/bulletin_view');
			$this->load->view('dashboard/view_dashboard', $result);
			$this->load->view('footer');
		}
	}
}