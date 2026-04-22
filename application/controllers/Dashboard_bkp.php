<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function _construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');
	}

	public function index()
	{
		show_404();
	}

	//admin dashboard
	public function view_dashboard()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$data=array('user_id'=>$this->session->userdata('login_id'));

			$result = array();
			$this->load->model('Dashboard_model');
			$result['survey_count'] = $this->Dashboard_model->dashboard_data();
			
			$this->load->model('Dashboard_model');
			$result['counts'] = $this->Dashboard_model->get_farmer_profile_count();
			$result['surveyupload_counts'] = $this->Dashboard_model->get_survey_uploads_count();		

			$this->load->model('Reports_model');
			$valuechains=$this->Reports_model->get_uservalluechain($data);
			
			$valuechain_ids=array();

			foreach ($valuechains  as $key => $valuechain) {
				if(!in_array($valuechain['value_chain_id'], $valuechain_ids)){
					array_push($valuechain_ids, $valuechain['value_chain_id']);
				}
			}		

	        $this->db->distinct();
	        $this->db->select('lkp_ward_id');
	        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
	        $this->db->where_in('lkp_value_chain_id',$valuechain_ids);
	        $get_user_ward_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

	        $widlist = array();      

	        foreach ($get_user_ward_locs as $key => $value) {
	            if(!in_array($value['lkp_ward_id'], $widlist)){
	                array_push($widlist, $value['lkp_ward_id']);
	            }
	        }

			$this->db->select('loc.lat, loc.lng, loc.address, vc.value_chain_name, CONCAT(sur.field_1450, " ", sur.field_1456) as householdhead_name, sur.field_1001 as householdid');
	        $this->db->from('rpt_formdata_location as loc');
	        $this->db->join('rpt_form_1 as sur', 'sur.id = loc.survey_id');
	        $this->db->join('lkp_value_chain as vc', 'vc.value_chain_id = sur.field_1011');
	        $this->db->where('form_id', 1)->where('sur.status', 1)->where('loc.status', 1);
	        $this->db->where_in('sur.field_1011',$valuechain_ids);
	     	$this->db->where_in('sur.ward', $widlist);
	        $location=$this->db->get()->result_array();
	      
	        $result['survey_locations'] = array();
	        foreach ($location as $key => $value) {
	            if($value['lat'] != NULL && $value['lng'] != NULL && $value['lat'] != 0 && $value['lng'] != 0 ){
	                $address = ($value['address'] == '' || $value['address'] == NULL) ? "N/A" : $value['address'];
	                
	                $householdhead_name = ($value['householdhead_name'] == '' || $value['householdhead_name'] == NULL) ? "N/A" : $value['householdhead_name'];
	                
	                $data = "<h5 class='title'>Valuechain : ".$value['value_chain_name']."</h5><h5>Household headname : ". $householdhead_name."</h5><h5>HHID: ". $value['householdid']."</h5><h5>Address : ".$address."</h5>";
	                array_push( $result['survey_locations'], array($value['lat'], $value['lng'], $data) );
	            }
	        }

	        		$result['dairy_head_count_45']=0;
					$result['dairy_head_count_40']=0;
					$result['dairy_head_count_29']=0;
					$result['dairy_head_count_17']=0;
					$result['dairy_head_male']=0;
					$result['dairy_head_female']=0;
					$result['dairy_member_male']=0;
					$result['dairy_member_female']=0;
					$result['dairy_member_count_45']=0;
					$result['dairy_member_count_40']=0;
					$result['dairy_member_count_29']=0;
					$result['dairy_member_count_17']=0;
	        	
	        		$result['dtc_head_count_45']=0;
					$result['dtc_head_count_40']=0;
					$result['dtc_head_count_29']=0;
					$result['dtc_head_count_17']=0;
					$result['dtc_member_count_45']=0;
					$result['dtc_member_count_40']=0;
					$result['dtc_member_count_29']=0;
					$result['dtc_member_count_17']=0;
					$result['dtc_head_male']=0;
					$result['dtc_head_female']=0;
					$result['dtc_member_male']=0;
					$result['dtc_member_female']=0;
	        	
	        		$result['livestock_head_count_45']=0;
					$result['livestock_head_count_40']=0;
					$result['livestock_head_count_29']=0;
					$result['livestock_head_count_17']=0;
					$result['livestock_member_count_45']=0;
					$result['livestock_member_count_40']=0;
					$result['livestock_member_count_29']=0;
					$result['livestock_member_count_17']=0;
					$result['livestock_head_male']=0;
					$result['livestock_head_female']=0;
					$result['livestock_member_male']=0;
					$result['livestock_member_female']=0;
	        	
	        		$result['potato_head_count_45']=0;
					$result['potato_head_count_40']=0;
					$result['potato_head_count_29']=0;
					$result['potato_head_count_17']=0;
					$result['potato_member_count_45']=0;
					$result['potato_member_count_40']=0;
					$result['potato_member_count_29']=0;
					$result['potato_member_count_17']=0;
					$result['potato_head_male']=0;
					$result['potato_head_female']=0;
					$result['potato_member_male']=0;
					$result['potato_member_female']=0;

	        if(count($valuechain)>0){
	        foreach ($valuechains as $key => $valuechain) {
	        	
	        	$head_count_45=0;
	        	$head_count_40=0;
	        	$head_count_29=0;
	        	$head_count_17=0;

	        	$member_count_45=0;
	        	$member_count_40=0;
	        	$member_count_29=0;
	        	$member_count_17=0;

	        	$head_female=0;
	        	$head_male=0;
	        	$member_male=0;
	        	$member_female=0;

	        	$valuechainid=$valuechain['value_chain_id'];
	        	$valuechainname=$valuechain['value_chain_name'];
				$this->db->select('group.field_1452 as field_1452');
	        	$this->db->from('rpt_form_1_groupdata as group');
	        	$this->db->join('rpt_form_1','rpt_form_1.id=group.survey_recordid');
	        	$this->db->where('group_field_id=',NULL);
	        	$this->db->where('rpt_form_1.field_1011',$valuechainid);
	        	$this->db->where('group.status',1);
	        	$head_dob=$this->db->get()->result_array();

	        	$this->db->select('group.field_1007 as field_1007');
	        	$this->db->from('rpt_form_1_groupdata as group');
	        	$this->db->join('rpt_form_1','rpt_form_1.id=group.survey_recordid');
	        	$this->db->where('group_field_id=',1004);
	        	$this->db->where('rpt_form_1.field_1011',$valuechainid);
	        	$this->db->where('group.status',1);
	        	$member_dob=$this->db->get()->result_array();

	        	$this->db->select('group.field_1451 as field_1451');
	        	$this->db->from('rpt_form_1_groupdata as group');
	        	$this->db->join('rpt_form_1','rpt_form_1.id=group.survey_recordid');
	        	$this->db->where('rpt_form_1.field_1011',$valuechainid);
	        	$this->db->where('group.group_field_id=',NULL);
	        	$this->db->where('group.field_1451!=',NULL);
	        	$this->db->where('rpt_form_1.status',1);
	        	$this->db->where('group.status',1);
	        	$head_gender=$this->db->get()->result_array();

	        	
	        	$this->db->select('group.field_1006 as field_1006');
	        	$this->db->from('rpt_form_1_groupdata as group');
	        	$this->db->join('rpt_form_1','rpt_form_1.id=group.survey_recordid');
	        	$this->db->where('rpt_form_1.field_1011',$valuechainid);
	        	$this->db->where('group.group_field_id=',1004);
	        	$this->db->where('group.field_1006=',2);
	        	$this->db->where('rpt_form_1.status',1);
	        	$this->db->where('group.status',1);
	        	$member_gender_female=$this->db->get()->result_array();


	        	$this->db->select('group.field_1006 as field_1006');
	        	$this->db->from('rpt_form_1_groupdata as group');
	        	$this->db->join('rpt_form_1','rpt_form_1.id=group.survey_recordid');
	        	$this->db->where('rpt_form_1.field_1011',$valuechainid);
	        	$this->db->where('group.group_field_id=',1004);
	        	$this->db->where('group.field_1006=',1);
	        	$this->db->where('rpt_form_1.status',1);
	        	$this->db->where('group.status',1);
	        	$member_gender_male=$this->db->get()->result_array();
	        	

		        	foreach ($head_dob as $fkey => $hdob) {
					$birth_year=$hdob['field_1452'];
					//var_dump(gettype($birth_year)); die();
					$present_age=((int)date('Y'))-((int)$birth_year);

							switch ($present_age) {
								case ($present_age>45):
									$head_count_45=$head_count_45+1;
									break;
								case ($present_age<=40 && $present_age>=30):
									$head_count_40=$head_count_40+1;
									break;	
								case ($present_age<=29 && $present_age>=18):
									$head_count_29=$head_count_29+1;
									break;
								case ($present_age<=17 && $present_age>=15):
									$head_count_17=$head_count_17+1;
									break;	
								default:
									break;
							}
		        	}
		        	$headcount[$valuechainname]=array('head_count_45'=>$head_count_45,
		        							'head_count_40'=>$head_count_40,
		        							'head_count_29'=>$head_count_29,
		        							'head_count_17'=>$head_count_17);

		        foreach ($member_dob as $hkey => $mdob) {

	        	$mem_birth_year=$mdob['field_1007'];
	        	// var_dump(gettype(date('Y')));
	        	// var_dump($mem_birth_year); die();
				$mem_present_age=((int)date('Y'))-((int)$mem_birth_year);

						switch ($mem_present_age) {
							case ($mem_present_age>45):
								$member_count_45=$member_count_45+1;
								break;
							case ($mem_present_age<=40 && $mem_present_age>=30):
								$member_count_40=$member_count_40+1;
								break;	
							case ($mem_present_age<=29 && $mem_present_age>=18):
								$member_count_29=$member_count_29+1;
								break;
							case ($mem_present_age<=17 && $mem_present_age>=15):
								$member_count_17=$member_count_17+1;
								break;	
							default:
								break;
						}
	        	
	        	}

	        	$membercount[$valuechainname]=array('member_count_45'=>$member_count_45,
		        							'member_count_40'=>$member_count_40,
		        							'member_count_29'=>$member_count_29,
		        							'member_count_17'=>$member_count_17);

	        	foreach ($head_gender as $jkey => $gender) {

	        			$head_gender=$gender['field_1451'];

	        			switch($head_gender){

	        				case ($head_gender==1):
	        					$head_male=$head_male+1;
	        				case ($head_gender==2):
	        					$head_female=$head_female+1;
	        				
	        			}
	        	
	        	}
	        	$headgender[$valuechainname]=array('head_male'=>$head_male,
	    										'head_female'=>$head_female);


	        	foreach ($member_gender_male as $lkey => $mgender) {

	        			$mem_gender=$mgender['field_1006'];
	        			$member_male=$member_male+1;
	        	}
	        	foreach ($member_gender_female as $lkey => $fgender) {

	        		$mem_fgender=$fgender['field_1006'];
					$member_female=$member_female+1;
	        	}
	        	$membergender[$valuechainname]=array('member_male'=>$member_male,
	    										'member_female'=>$member_female);

	        	if($valuechainname=='Dairy'){
	        		$result['dairy_head_count_45']=$headcount['Dairy']['head_count_45'];
					$result['dairy_head_count_40']=$headcount['Dairy']['head_count_40'];
					$result['dairy_head_count_29']=$headcount['Dairy']['head_count_29'];
					$result['dairy_head_count_17']=$headcount['Dairy']['head_count_17'];
					$result['dairy_head_male']=$headgender['Dairy']['head_male'];
					$result['dairy_head_female']=$headgender['Dairy']['head_female'];
					$result['dairy_member_male']=$membergender['Dairy']['member_male'];
					$result['dairy_member_female']=$membergender['Dairy']['member_female'];
					$result['dairy_member_count_45']=$membercount['Dairy']['member_count_45'];
					$result['dairy_member_count_40']=$membercount['Dairy']['member_count_40'];
					$result['dairy_member_count_29']=$membercount['Dairy']['member_count_29'];
					$result['dairy_member_count_17']=$membercount['Dairy']['member_count_17'];
	        	}if($valuechainname=='DTC'){
	        		$result['dtc_head_count_45']=$headcount['DTC']['head_count_45'];
					$result['dtc_head_count_40']=$headcount['DTC']['head_count_40'];
					$result['dtc_head_count_29']=$headcount['DTC']['head_count_29'];
					$result['dtc_head_count_17']=$headcount['DTC']['head_count_17'];
					$result['dtc_member_count_45']=$membercount['DTC']['member_count_45'];
					$result['dtc_member_count_40']=$membercount['DTC']['member_count_40'];
					$result['dtc_member_count_29']=$membercount['DTC']['member_count_29'];
					$result['dtc_member_count_17']=$membercount['DTC']['member_count_17'];
					$result['dtc_head_male']=$headgender['DTC']['head_male'];
					$result['dtc_head_female']=$headgender['DTC']['head_female'];
					$result['dtc_member_male']=$membergender['DTC']['member_male'];
					$result['dtc_member_female']=$membergender['DTC']['member_female'];
	        	}if($valuechainname=='Livestock'){
	        		$result['livestock_head_count_45']=$headcount['Livestock']['head_count_45'];
					$result['livestock_head_count_40']=$headcount['Livestock']['head_count_40'];
					$result['livestock_head_count_29']=$headcount['Livestock']['head_count_29'];
					$result['livestock_head_count_17']=$headcount['Livestock']['head_count_17'];
					$result['livestock_member_count_45']=$membercount['Livestock']['member_count_45'];
					$result['livestock_member_count_40']=$membercount['Livestock']['member_count_40'];
					$result['livestock_member_count_29']=$membercount['Livestock']['member_count_29'];
					$result['livestock_member_count_17']=$membercount['Livestock']['member_count_17'];
					$result['livestock_head_male']=$headgender['Livestock']['head_male'];
					$result['livestock_head_female']=$headgender['Livestock']['head_female'];
					$result['livestock_member_male']=$membergender['Livestock']['member_male'];
					$result['livestock_member_female']=$membergender['Livestock']['member_female'];
	        	}if($valuechainname=='Potato'){
	        		$result['potato_head_count_45']=$headcount['Potato']['head_count_45'];
					$result['potato_head_count_40']=$headcount['Potato']['head_count_40'];
					$result['potato_head_count_29']=$headcount['Potato']['head_count_29'];
					$result['potato_head_count_17']=$headcount['Potato']['head_count_17'];
					$result['potato_member_count_45']=$membercount['Potato']['member_count_45'];
					$result['potato_member_count_40']=$membercount['Potato']['member_count_40'];
					$result['potato_member_count_29']=$membercount['Potato']['member_count_29'];
					$result['potato_member_count_17']=$membercount['Potato']['member_count_17'];
					$result['potato_head_male']=$headgender['Potato']['head_male'];
					$result['potato_head_female']=$headgender['Potato']['head_female'];
					$result['potato_member_male']=$membergender['Potato']['member_male'];
					$result['potato_member_female']=$membergender['Potato']['member_female'];
	        	}

			}
		}	


			//var_dump($result);die();
			
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$this->load->view('header', $header_result);
			$this->load->view('dashboard/view_dashboard', $result);
			$this->load->view('footer');
		}
	}
	public function powerbi_dashboard(){

		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$result=array();
			// $this->load->model('Dynamicmenu_model');
			// $main_menu = $this->Dynamicmenu_model->menu_details();
			// $header_result = array('main_menu' => $main_menu);
			// $this->load->view('header', $header_result);
			$this->load->view('dashboard/powerbi_dashboard', $result);
			// $this->load->view('footer');
		}	

	}
	public function get_data_bycounty()
	{
		$baseurl = base_url();
		date_default_timezone_set("UTC");		
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$this->load->model('Dashboard_model');
			$result = $this->Dashboard_model->get_data_bycounty();		

			echo json_encode($result);
			exit();
		}
	}

	public function value_chain_data(){
		$baseurl = base_url();
		date_default_timezone_set("UTC");		
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		} else {
			$value_chain_id = $this->uri->segment(3);
			if($value_chain_id == ''){
				show_404();
			}

			$this->load->model('Dashboard_model');
			$survey_locations = $this->Dashboard_model->get_locationsbyvaluechain($value_chain_id);

			$form_list = $this->Dashboard_model->get_surveysbyvaluechain($value_chain_id);

			$surveydata = $this->Dashboard_model->get_surveydata($form_list);			

			$result = array('survey_locations' => $survey_locations, 'surveydata' => $surveydata);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('dashboard/value_chain_data', $result);
			$this->load->view('footer');
		}
	}

	public function value_chain_data_bycounty(){
		$baseurl = base_url();
		date_default_timezone_set("UTC");		
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		} else {
			$value_chain_id = $this->uri->segment(4);
			$county = $this->uri->segment(3);

			if($value_chain_id == '' || $county == ''){
				show_404();
			}

			$this->load->model('Dashboard_model');
			$survey_locations = $this->Dashboard_model->value_chain_data_bycounty($value_chain_id, $county);

			$form_list = $this->Dashboard_model->get_surveysbyvaluechain($value_chain_id);

			$surveydata = $this->Dashboard_model->get_surveydata_bycounty($form_list, $county);			

			$result = array('survey_locations' => $survey_locations, 'surveydata' => $surveydata);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();
			
			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('dashboard/value_chain_data', $result);
			$this->load->view('footer');
		}
	}

	public function livestock_marketdashboard()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$valuechain_id=3;
			$this->load->model('Dashboard_model');			
			$result = $this->Dashboard_model->get_animal_data();

			$result['animal_aggregated_data'] = $this->Dashboard_model->get_animal_aggregated_data();

			$this->load->model('Reports_model');
			$result['surveydetails'] = $this->Dashboard_model->get_surveydetails(37,$valuechain_id );
			$result['form_fields'] = $this->Reports_model->get_form_fields(37);
			
			$this->db->select('field_id');
			$this->db->where('type', 'group')->where('form_id', 37)->where('status', 1);
			$result['form_check_group_fields_count'] = $this->db->get('form_field')->num_rows();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();		

			$header_result = array('main_menu' => $main_menu);
			
			$this->load->view('header', $header_result);
			$this->load->view('dashboard/livestock_marketdashboard', $result);
			$this->load->view('footer');
		}
	}

	public function getanimal_data_byfilter(){
		$baseurl = base_url();
		date_default_timezone_set("UTC");		
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$this->load->model('Dashboard_model');
			$this->load->model('Reports_model');
			
			$result=$this->Dashboard_model->get_animal_data();
			$result['surveydetails'] = $this->Dashboard_model->get_surveydetails(37,3 );
			$result['form_fields'] = $this->Reports_model->get_form_fields(37);
			
			echo json_encode($result);
			exit();
		}
	}
}