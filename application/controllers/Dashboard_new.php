<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_new extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		$this->load->model('Dashboardnew_model');
		$this->load->model('Reports_model');
		$this->load->model('Dynamicmenu_model');		
	}

	public function index()
	{
		show_404();
	}

	public function dashboard()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$data = array();
			$result = $this->Dashboardnew_model->dashboard($data);

			$result['location_data'] = $this->Dashboardnew_model->location_data($data);
			$result['farmer_data'] = $this->Dashboardnew_model->farmer_data($data);
			$result['district_list'] = $this->Dashboardnew_model->district_list();

			//all villages list
			$result['village_list'] = $this->Reports_model->village_list();

			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('dashboard_new/dashboard', $result);
			$this->load->view('footer');
		}
	}

	public function get_filterdata()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$district_ids = $this->input->post('district_ids');
			$block_ids = $this->input->post('block_ids');
			$village_ids = $this->input->post('village_ids');

			$data = array(
				'district_ids' => $district_ids,
				'block_ids' => $block_ids,
				'village_ids' => $village_ids
			);

			$result = $this->Dashboardnew_model->dashboard($data);
			$result['location_data'] = $this->Dashboardnew_model->location_data($data);	
			$result['farmer_data'] = $this->Dashboardnew_model->farmer_data($data);

			//all villages list
			$result['village_list'] = $this->Reports_model->village_list();
			
			$result['status'] = 1;

			echo json_encode($result);
			exit();
		}
	}

	public function get_location_info()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$district_ids = $this->input->post('district_ids');
			$block_ids = $this->input->post('block_ids');
			$village_ids = $this->input->post('village_ids');
			$locationtype = $this->input->post('locationtype');
			$surveyid = $this->input->post('surveyid');

			$data = array(
				'district_ids' => $district_ids,
				'block_ids' => $block_ids,
				'village_ids' => $village_ids,
				'locationtype' => $locationtype,
				'surveyid' => $surveyid
			);

			$result['location_data'] = $this->Dashboardnew_model->location_data($data);			
			$result['status'] = 1;

			echo json_encode($result);
			exit();
		}
	}

	public function loadmore_beneficarydata()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$district_ids = $this->input->post('district_ids');
			$block_ids = $this->input->post('block_ids');
			$village_ids = $this->input->post('village_ids');
			$last_id = $this->input->post('last_id');

			$data = array(
				'district_ids' => $district_ids,
				'block_ids' => $block_ids,
				'village_ids' => $village_ids,
				'last_id' => $last_id
			);

			$result['farmer_data'] = $this->Dashboardnew_model->farmer_data($data);

			//all villages list
			$result['village_list'] = $this->Reports_model->village_list();
			$result['status'] = 1;

			echo json_encode($result);
			exit();

		}
	}

	public function farmerdetails(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$survey_id = 1;
			$data_id = $this->uri->segment(3);

			$data = array(
				'survey_id' => $survey_id,
				'data_id' => $data_id
			);

			$check_record = $this->Reports_model->check_record($data);

			if($check_record == 0){
				show_404();
			}

			$this->db->select('*');
			$this->db->where('form_id', 1)->where('data_status', 1)->where('data_id', $data_id);
			$farmer_details = $this->db->get('ic_form_data')->row_array();

			$farmer_image = $this->db->select('file_name')->where('status', 1)->where('data_id', $data_id)->get('ic_data_file')->row_array();

			$group_info = $this->Reports_model->group_info($data);

			$this->db->select('loc.lat, loc.lng, loc.address, f.title, concat(tu.first_name, " ", tu.last_name) as username, data.form_data');
			$this->db->from('ic_data_location as loc');
			$this->db->join('form as f', 'f.id = loc.form_id');
			$this->db->join('ic_form_data as data', 'data.data_id = loc.data_id');
			$this->db->join('tbl_users AS tu', 'tu.user_id = data.user_id');
			$this->db->where('data.data_id', $data_id)->where('data.data_status', 1)->where('loc.status', 1);
			$survey_locations = $this->db->get()->result_array();
			$location_data = array();
			foreach ($survey_locations as $key => $location) {

				$data_array = json_decode($location['form_data'], true);

				$household_headname = (isset($data_array['field_1673']) ? $data_array['field_1673'] : 'N/A')." ".(isset($data_array['field_1674']) ? $data_array['field_1674'] : 'N/A');
            
	            $data = "<h5 class='title'>".$location['title']."</h5><h5>Household headname : ". $household_headname."</h5><h5>Submitted by : ".$location['username']."</h5>";

	            array_push($location_data, array($location['lat'], $location['lng'], $data));
	        }

			$result = array('group_info' => $group_info, 'farmer_details' => $farmer_details, 'farmer_image' => $farmer_image, 'location_data' => $location_data);
			$result['state_list'] = $this->Reports_model->state_list();
			$result['district_list'] = $this->Reports_model->district_list();
			$result['block_list'] = $this->Reports_model->block_list();
			$result['village_list'] = $this->Reports_model->village_list();
			
			$main_menu = $this->Dynamicmenu_model->menu_details();
			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('dashboard_new/farmerdetails', $result);
			$this->load->view('footer');
		}
	}

	public function get_blockslist()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$district_id = $this->input->post('district_id');

			$result['block_list'] = $this->Dashboardnew_model->get_blockslist($district_id);
			$result['status'] = 1;

			echo json_encode($result);
			exit();
		}
	}

	public function get_villagelist()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$block_id = $this->input->post('block_id');

			$result['village_list'] = $this->Dashboardnew_model->get_villagelist($block_id);
			$result['status'] = 1;

			echo json_encode($result);
			exit();
		}
	}

	//start crone job function

	//updating of dashboard_coconutplantation_details
	public function dashboard_coconutplantation_details()
	{
		$this->db->distinct();
		$this->db->select('village_id');
		$this->db->where('data_status', 1)->where('form_id', 1);
		$distinct_villages = $this->db->get('ic_form_data')->result_array();

		foreach ($distinct_villages as $key => $village) {
			$naturecoconut_details = array();

			$naturecoconut_details['Homestead'] = 0;
			$naturecoconut_details['Block plantation'] = 0;
			$naturecoconut_details['Bund plantation'] = 0;

			$this->db->select('data_id, country_id, state_id, district_id, block_id, village_id');
	        $this->db->where('form_id', 1)->where('data_status', 1);
            $this->db->where('village_id', $village['village_id']);
	        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();

	        foreach ($farmer_registrations_data as $key => $value) {
	        	$check_plantationlandparcel_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1721)->get('ic_form_group_data');
	            if($check_plantationlandparcel_details->num_rows() > 0){
	                $plantationlandparcel_groupdata = $check_plantationlandparcel_details->result_array();

	                foreach ($plantationlandparcel_groupdata as $key => $gd_landparcel) {
	                    $plantationlandparcel_groupdata_array = json_decode($gd_landparcel['formgroup_data'], true);
	                   
	                    if($plantationlandparcel_groupdata_array['field_1726'] != NULL && $plantationlandparcel_groupdata_array['field_1726'] != ''){
	                        $cocnut_new_data = json_decode($plantationlandparcel_groupdata_array['field_1726'], true);
	                        if($cocnut_new_data != NULL && count($cocnut_new_data) > 0){
	                            foreach ($cocnut_new_data as $key => $coc_data) {
	                                if(isset($naturecoconut_details[$coc_data])){
	                                    $naturecoconut_details[$coc_data]++;
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	        }

	        $location_info = $this->db->select('*')->where('village_id', $village['village_id'])->get('lkp_village')->row_array();

	        $check_villagerecord = $this->db->where('village_id', $village['village_id'])->where('status', 1)->get('dashboard_coconutplantation_details')->num_rows();

	        if($check_villagerecord > 0){
	        	$array_data = array(
		        	'homestead' => $naturecoconut_details['Homestead'],
		        	'block_plantation' => $naturecoconut_details['Block plantation'],
		        	'bund_plantation' => $naturecoconut_details['Bund plantation'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

	        	$this->db->where('village_id', $village['village_id']);
		        $query = $this->db->update('dashboard_coconutplantation_details', $array_data);
	        }else{
	        	$array_data = array(
		        	'country_id' => $location_info['country_id'],
		        	'state_id' => $location_info['state_id'],
		        	'district_id' => $location_info['dist_id'],
		        	'block_id' => $location_info['block_id'],
		        	'village_id' => $location_info['village_id'],
		        	'homestead' => $naturecoconut_details['Homestead'],
		        	'block_plantation' => $naturecoconut_details['Block plantation'],
		        	'bund_plantation' => $naturecoconut_details['Bund plantation'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

		        $query = $this->db->insert('dashboard_coconutplantation_details', $array_data);
	        }
		}
		exit();
	}

	//updating of dashboard_rabicrop_details
	public function dashboard_rabicrop_details()
	{
		$this->db->distinct();
		$this->db->select('village_id');
		$this->db->where('data_status', 1)->where('form_id', 1);
		$distinct_villages = $this->db->get('ic_form_data')->result_array();

		foreach ($distinct_villages as $key => $village) {
			$rabicrop_details['Green gram'] = 0;
	        $rabicrop_details['Black gram'] = 0;
	        $rabicrop_details['Vegetables'] = 0;
	        $rabicrop_details['Others'] = 0;
	        $rabicrop_details['Paddy'] = 0;
	        $rabicrop_details['Sun flower'] = 0;
	        $rabicrop_details['Chick pea'] = 0;
	        $rabicrop_details['Groundnut'] = 0;
	        $rabicrop_details['Onion'] = 0;
	        $rabicrop_details['Pea'] = 0;
	        $rabicrop_details['Not Cultivated'] = 0;
	        $rabicrop_details['Dont Remember'] = 0;

	        $this->db->select('data_id, country_id, state_id, district_id, block_id, village_id');
	        $this->db->where('form_id', 1)->where('data_status', 1);
            $this->db->where('village_id', $village['village_id']);
	        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();

	        foreach ($farmer_registrations_data as $key => $value) {
	        	$check_landholding_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1702)->get('ic_form_group_data');
	            if($check_landholding_details->num_rows() > 0){
	                $landholdinggroupdata = $check_landholding_details->result_array();

	                foreach ($landholdinggroupdata as $key => $gd_landholding) {
	                    $landholding_groupdata_array = json_decode($gd_landholding['formgroup_data'], true);

	                    if($landholding_groupdata_array['field_1719'] != NULL && $landholding_groupdata_array['field_1719'] != ''){
	                        $rabi_new_data = json_decode($landholding_groupdata_array['field_1719'], true);
	                        if($rabi_new_data != NULL && count($rabi_new_data) > 0){
	                            foreach ($rabi_new_data as $key => $rabicrop) {
	                                if(isset($rabicrop_details[$rabicrop])){
	                                    $rabicrop_details[$rabicrop]++;
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	        }

	        $location_info = $this->db->select('*')->where('village_id', $village['village_id'])->get('lkp_village')->row_array();

	        $check_villagerecord = $this->db->where('village_id', $village['village_id'])->where('status', 1)->get('dashboard_rabicrop_details')->num_rows();

	        if($check_villagerecord > 0){
	        	$array_data = array(
		        	'green_gram' => $rabicrop_details['Green gram'],
		        	'black_gram' => $rabicrop_details['Black gram'],
		        	'vegetables' => $rabicrop_details['Vegetables'],
		        	'others' => $rabicrop_details['Others'],
		        	'paddy' => $rabicrop_details['Paddy'],
		        	'sunflower' => $rabicrop_details['Sun flower'],
		        	'chickpea' => $rabicrop_details['Chick pea'],
		        	'groundnut' => $rabicrop_details['Groundnut'],
		        	'onion' => $rabicrop_details['Onion'],
		        	'pea' => $rabicrop_details['Pea'],
		        	'not_cultivated' => $rabicrop_details['Not Cultivated'],
		        	'dont_remember' => $rabicrop_details['Dont Remember'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

	        	$this->db->where('village_id', $village['village_id']);
		        $query = $this->db->update('dashboard_rabicrop_details', $array_data);
	        }else{
	        	$array_data = array(
		        	'country_id' => $location_info['country_id'],
		        	'state_id' => $location_info['state_id'],
		        	'district_id' => $location_info['dist_id'],
		        	'block_id' => $location_info['block_id'],
		        	'village_id' => $location_info['village_id'],
		        	'green_gram' => $rabicrop_details['Green gram'],
		        	'black_gram' => $rabicrop_details['Black gram'],
		        	'vegetables' => $rabicrop_details['Vegetables'],
		        	'others' => $rabicrop_details['Others'],
		        	'paddy' => $rabicrop_details['Paddy'],
		        	'sunflower' => $rabicrop_details['Sun flower'],
		        	'chickpea' => $rabicrop_details['Chick pea'],
		        	'groundnut' => $rabicrop_details['Groundnut'],
		        	'onion' => $rabicrop_details['Onion'],
		        	'pea' => $rabicrop_details['Pea'],
		        	'not_cultivated' => $rabicrop_details['Not Cultivated'],
		        	'dont_remember' => $rabicrop_details['Dont Remember'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

		        $query = $this->db->insert('dashboard_rabicrop_details', $array_data);
	        }
		}
	}

	//updating of dashboard_kharifcrop_details table
	public function dashboard_kharifcrop_details()
	{
		$this->db->distinct();
		$this->db->select('village_id');
		$this->db->where('data_status', 1)->where('form_id', 1);
		$distinct_villages = $this->db->get('ic_form_data')->result_array();

		foreach ($distinct_villages as $key => $village) {
			$kharifcrop_details['Paddy'] = 0;
	        $kharifcrop_details['Pearl millet'] = 0;
	        $kharifcrop_details['Maize'] = 0;
	        $kharifcrop_details['Chillies'] = 0;
	        $kharifcrop_details['Peas'] = 0;
	        $kharifcrop_details['French bean'] = 0;
	        $kharifcrop_details['Others'] = 0;
	        $kharifcrop_details['Groundnut'] = 0;
	        $kharifcrop_details['Not Cultivated'] = 0;
	        $kharifcrop_details['Sweet potato'] = 0;
	        $kharifcrop_details['Pigeon pea'] = 0;
	        $kharifcrop_details['Onion'] = 0;
	        $kharifcrop_details['Finger millet'] = 0;
	        $kharifcrop_details['Vegetables'] = 0;
	        $kharifcrop_details['Dont Remember'] = 0;

	        $this->db->select('data_id, country_id, state_id, district_id, block_id, village_id');
	        $this->db->where('form_id', 1)->where('data_status', 1);
            $this->db->where('village_id', $village['village_id']);
	        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();

	        foreach ($farmer_registrations_data as $key => $value) {
	        	$check_landholding_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1702)->get('ic_form_group_data');
	            if($check_landholding_details->num_rows() > 0){
	                $landholdinggroupdata = $check_landholding_details->result_array();

	                foreach ($landholdinggroupdata as $key => $gd_landholding) {
	                    $landholding_groupdata_array = json_decode($gd_landholding['formgroup_data'], true);
	                   
	                    if($landholding_groupdata_array['field_1717'] != NULL && $landholding_groupdata_array['field_1717'] != ''){
	                        $karif_new_data = json_decode($landholding_groupdata_array['field_1717'], true);
	                        if($karif_new_data != NULL && count($karif_new_data) > 0){
	                            foreach ($karif_new_data as $key => $karifcrop) {
	                                if(isset($kharifcrop_details[$karifcrop])){
	                                    $kharifcrop_details[$karifcrop]++;;
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	        }

	        $location_info = $this->db->select('*')->where('village_id', $village['village_id'])->get('lkp_village')->row_array();

	        $check_villagerecord = $this->db->where('village_id', $village['village_id'])->where('status', 1)->get('dashboard_kharifcrop_details')->num_rows();

	        if($check_villagerecord > 0){
	        	$array_data = array(
		        	'paddy' => $kharifcrop_details['Paddy'],
		        	'pearl_millet' => $kharifcrop_details['Pearl millet'],
		        	'maize' => $kharifcrop_details['Maize'],
		        	'chillies' => $kharifcrop_details['Chillies'],
		        	'peas' => $kharifcrop_details['Peas'],
		        	'french_beans' => $kharifcrop_details['French bean'],
		        	'others' => $kharifcrop_details['Others'],
		        	'groundnut' => $kharifcrop_details['Groundnut'],
		        	'not_cultivated' => $kharifcrop_details['Not Cultivated'],
		        	'sweet_potato' => $kharifcrop_details['Sweet potato'],
		        	'pigeon_pea' => $kharifcrop_details['Pigeon pea'],
		        	'onion' => $kharifcrop_details['Onion'],
		        	'finger_millet' => $kharifcrop_details['Finger millet'],
		        	'vegetables' => $kharifcrop_details['Vegetables'],
		        	'dont_remember' => $kharifcrop_details['Dont Remember'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

	        	$this->db->where('village_id', $village['village_id']);
		        $query = $this->db->update('dashboard_kharifcrop_details', $array_data);
	        }else{
	        	$array_data = array(
		        	'country_id' => $location_info['country_id'],
		        	'state_id' => $location_info['state_id'],
		        	'district_id' => $location_info['dist_id'],
		        	'block_id' => $location_info['block_id'],
		        	'village_id' => $location_info['village_id'],
		        	'paddy' => $kharifcrop_details['Paddy'],
		        	'pearl_millet' => $kharifcrop_details['Pearl millet'],
		        	'maize' => $kharifcrop_details['Maize'],
		        	'chillies' => $kharifcrop_details['Chillies'],
		        	'peas' => $kharifcrop_details['Peas'],
		        	'french_beans' => $kharifcrop_details['French bean'],
		        	'others' => $kharifcrop_details['Others'],
		        	'groundnut' => $kharifcrop_details['Groundnut'],
		        	'not_cultivated' => $kharifcrop_details['Not Cultivated'],
		        	'sweet_potato' => $kharifcrop_details['Sweet potato'],
		        	'pigeon_pea' => $kharifcrop_details['Pigeon pea'],
		        	'onion' => $kharifcrop_details['Onion'],
		        	'finger_millet' => $kharifcrop_details['Finger millet'],
		        	'vegetables' => $kharifcrop_details['Vegetables'],
		        	'dont_remember' => $kharifcrop_details['Dont Remember'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

		        $query = $this->db->insert('dashboard_kharifcrop_details', $array_data);
	        }
	    }        
	}

	//updating of dashboard_livestock_details
	public function dashboard_livestock_details()
	{
		$this->db->distinct();
		$this->db->select('village_id');
		$this->db->where('data_status', 1)->where('form_id', 1);
		$distinct_villages = $this->db->get('ic_form_data')->result_array();

		foreach ($distinct_villages as $key => $village) {
			$livestock_details['Buffalo'] = 0;
			$livestock_details['Cows'] = 0;
			$livestock_details['Bulls/Oxen'] = 0;
			$livestock_details['Goat'] = 0;
			$livestock_details['Sheep'] = 0;
			$livestock_details['Pig'] = 0;
			$livestock_details['Others'] = 0;

	        $this->db->select('data_id, country_id, state_id, district_id, block_id, village_id');
	        $this->db->where('form_id', 1)->where('data_status', 1);
            $this->db->where('village_id', $village['village_id']);
	        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();

	        foreach ($farmer_registrations_data as $key => $value) {
	        	$check_livestock_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1744)->get('ic_form_group_data');
	            if($check_livestock_details->num_rows() > 0){
	                $groupdata = $check_livestock_details->result_array();

	                foreach ($groupdata as $key => $gd) {
	                    $groupdata_array = json_decode($gd['formgroup_data'], true); 

	                    if($groupdata_array['field_1745'] == 'Buffalo'){
	                        $livestock_details['Buffalo']++;
	                    }

	                    if($groupdata_array['field_1745'] == 'Cows'){
	                        $livestock_details['Cows']++;
	                    }

	                    if($groupdata_array['field_1745'] == 'Bulls/Oxen'){
	                        $livestock_details['Bulls/Oxen']++;
	                    }

	                    if($groupdata_array['field_1745'] == 'Goat'){
	                        $livestock_details['Goat']++;
	                    }

	                    if($groupdata_array['field_1745'] == 'Sheep'){
	                        $livestock_details['Sheep']++;
	                    }

	                    if($groupdata_array['field_1745'] == 'Pig'){
	                        $livestock_details['Pig']++;
	                    }

	                    if($groupdata_array['field_1745'] == 'Others'){
	                        $livestock_details['Others']++;
	                    }
	                }
	            }
	        }

	        $location_info = $this->db->select('*')->where('village_id', $village['village_id'])->get('lkp_village')->row_array();

	        $check_villagerecord = $this->db->where('village_id', $village['village_id'])->where('status', 1)->get('dashboard_livestock_details')->num_rows();

	        if($check_villagerecord > 0){
	        	$array_data = array(
		        	'buffalo' => $livestock_details['Buffalo'],
		        	'cows' => $livestock_details['Cows'],
		        	'bulls_or_oxen' => $livestock_details['Bulls/Oxen'],
		        	'goat' => $livestock_details['Goat'],
		        	'sheep' => $livestock_details['Sheep'],
		        	'pig' => $livestock_details['Pig'],
		        	'others' => $livestock_details['Others'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

	        	$this->db->where('village_id', $village['village_id']);
		        $query = $this->db->update('dashboard_livestock_details', $array_data);
	        }else{
	        	$array_data = array(
		        	'country_id' => $location_info['country_id'],
		        	'state_id' => $location_info['state_id'],
		        	'district_id' => $location_info['dist_id'],
		        	'block_id' => $location_info['block_id'],
		        	'village_id' => $location_info['village_id'],
		        	'buffalo' => $livestock_details['Buffalo'],
		        	'cows' => $livestock_details['Cows'],
		        	'bulls_or_oxen' => $livestock_details['Bulls/Oxen'],
		        	'goat' => $livestock_details['Goat'],
		        	'sheep' => $livestock_details['Sheep'],
		        	'pig' => $livestock_details['Pig'],
		        	'others' => $livestock_details['Others'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

		        $query = $this->db->insert('dashboard_livestock_details', $array_data);
	        }
	    }

	}

	//updating of dashboard_poultry_details
    public function dashboard_poultry_details()
    {
    	$this->db->distinct();
		$this->db->select('village_id');
		$this->db->where('data_status', 1)->where('form_id', 1);
		$distinct_villages = $this->db->get('ic_form_data')->result_array();

		foreach ($distinct_villages as $key => $village) {
			$poultry_details['Chickens'] = 0;
			$poultry_details['Ducks'] = 0;
			$poultry_details['Geese'] = 0;
			$poultry_details['Turkeys'] = 0;
			$poultry_details['Others'] = 0;

	        $this->db->select('data_id, country_id, state_id, district_id, block_id, village_id');
	        $this->db->where('form_id', 1)->where('data_status', 1);
            $this->db->where('village_id', $village['village_id']);
	        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();

	        foreach ($farmer_registrations_data as $key => $value) {
	        	$check_poultry_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1748)->get('ic_form_group_data');
	            if($check_poultry_details->num_rows() > 0){
	                $groupdata = $check_poultry_details->result_array();

	                foreach ($groupdata as $key => $gd) {
	                    $groupdata_array = json_decode($gd['formgroup_data'], true); 

	                    if($groupdata_array['field_1749'] == 'Chickens'){
	                        $poultry_details['Chickens']++;
	                    }

	                    if($groupdata_array['field_1749'] == 'Ducks'){
	                        $poultry_details['Ducks']++;
	                    }

	                    if($groupdata_array['field_1749'] == 'Geese'){
	                        $poultry_details['Geese']++;
	                    }

	                    if($groupdata_array['field_1749'] == 'Turkeys'){
	                        $poultry_details['Turkeys']++;
	                    }

	                    if($groupdata_array['field_1749'] == 'Others'){
	                        $poultry_details['Others']++;
	                    }
	                }
	            }
	        }

	        $location_info = $this->db->select('*')->where('village_id', $village['village_id'])->get('lkp_village')->row_array();

	        $check_villagerecord = $this->db->where('village_id', $village['village_id'])->where('status', 1)->get('dashboard_poultry_details')->num_rows();

	        if($check_villagerecord > 0){
	        	$array_data = array(
		        	'chickens' => $poultry_details['Chickens'],
		        	'ducks' => $poultry_details['Ducks'],
		        	'geese' => $poultry_details['Geese'],
		        	'turkeys' => $poultry_details['Turkeys'],
		        	'others' => $poultry_details['Others'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

	        	$this->db->where('village_id', $village['village_id']);
		        $query = $this->db->update('dashboard_poultry_details', $array_data);
	        }else{
	        	$array_data = array(
		        	'country_id' => $location_info['country_id'],
		        	'state_id' => $location_info['state_id'],
		        	'district_id' => $location_info['dist_id'],
		        	'block_id' => $location_info['block_id'],
		        	'village_id' => $location_info['village_id'],
		        	'chickens' => $poultry_details['Chickens'],
		        	'ducks' => $poultry_details['Ducks'],
		        	'geese' => $poultry_details['Geese'],
		        	'turkeys' => $poultry_details['Turkeys'],
		        	'others' => $poultry_details['Others'],
		        	'updated_date' => date('Y-m-d H:i:s')
		        );

		        $query = $this->db->insert('dashboard_poultry_details', $array_data);
	        }
	    }
    }
    //end crone job function
}