<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization extends CI_Controller {
	
	function _construct(){
		parent::_construct();
		$this->load->helper('url');
	}
	public function index(){
		/*$this->load->model('Employee_m', 'm');
		$data['posts'] = $this->m->getEmployee();*/
	    $this->load->view('product_admin/index');
	    $this->load->view('product_admin/side_nav');
	    $this->load->view('product_admin/header');
	    $this->load->view('product_admin/footer');	
	}
		public function organization_data()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();
			$data=array('user_id'=>$this->session->userdata('login_id'));

			$this->load->model('Reports_model');
			// $get_farmer_data = $this->Reports_model->get_farmer_data();

			// $get_farmer_data['farmer_data_location'] = $this->Reports_model->get_farmer_datalocation();
			
			$header_result = array('main_menu' => $main_menu);
			$result['valuechains']=$this->Reports_model->get_uservalluechain($data);

			$this->load->view('header', $header_result);
			$this->load->view('reports/organization_data', $result);
			$this->load->view('footer');
		}
	}
		public  function get_organizationdata()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			    $this->db->select('Group_concat(form_id) as formids');
        		$this->db->where('lkp_value_chain_id',$this->input->post('valuechain_id'))->where('type_id',3)->where('relation_status',1);
        		$formids = $this->db->get('rpt_form_relation')->row_array();

       			 $formid_arrays=explode(',',$formids['formids']);

        	if(count(array_filter($formid_arrays)) >0 ) {

				$data=array('valuechain_id'=>$this->input->post('valuechain_id'),
					'user_id'=>$this->session->userdata('login_id'),
				);

				$this->load->model('Reports_model');
				$this->load->model('Organization_model');
				if (isset($_POST['change_type']) &&  ($_POST['change_type'] == 'valuechain')) {
					$details=$this->Reports_model->getcounty_databy_valuechainsurvey($data);
				}

				if (isset($_POST['change_type']) &&  ($_POST['change_type'] == 'county')) {
					$data['county_id']=$this->input->post('county_id');
					$details=$this->Reports_model->getsubcounty_databy_valuechainsurvey($data);
				}

				if (isset($_POST['change_type']) &&  ($_POST['change_type'] == 'subcounty')) {
					$data['subcounty_id']=$this->input->post('subcounty_id');
					$data['county_id']=$this->input->post('county_id');
					$details=$this->Reports_model->getward_databy_valuechainsurvey($data);
				}

				if (isset($_POST['change_type']) &&  ($_POST['change_type'] == 'ward')) {
					$details=array();
				}
				$profilecounts=$this->Organization_model->get_farmer_profile_countbyvaluechain();

				$get_farmer_data = $this->Organization_model->get_farmer_data();

				$result = array('details' => $details, 'get_farmer_data' => $get_farmer_data,'profilecounts'=>$profilecounts);

				if(isset($_POST['call_type']) && $_POST['call_type'] == 'filter'){
					$result['get_farmer_data_location'] = $this->Organization_model->get_farmer_datalocation();
				}
			}
			else{
				$result['status']=0;
				$result['msg']="There is no data for selected Valuechain";
			}
			
			echo json_encode($result);
			exit();
		}
	}
	public function view_organization_reports(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Organization_model');
			$get_usersurvey_byvaluechain = $this->Organization_model->get_usersurvey_byvaluechain();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('reports/view_reports', $get_usersurvey_byvaluechain);
			$this->load->view('footer');
		}


	}
	public function view_survey_data(){

	 if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$value_chain_id = $this->uri->segment(3);
			$form_id = $this->uri->segment(4);

			if($value_chain_id == '' || $value_chain_id == NULL || $form_id == '' || $form_id == NULL){
				show_404();
			}

			$this->load->model('Reporting_model');
			$form_name = $this->Reporting_model->get_form_name($form_id);

			$this->load->model('Dynamicmenu_model');
			$this->load->model('Organization_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Reports_model');
			$data=array('valuechain_id'=>$value_chain_id,
						'user_id'=>$this->session->userdata('login_id'));
			// $counties=$this->Reports_model->getcounty_databy_valuechainsurvey($data);
			$form_fields = $this->Reports_model->get_form_fields($form_id);
			$surveydetails = $this->Organization_model->get_surveydetails($form_id, $value_chain_id);

			$title=$this->db->select('title')->from('form')->where('id',$form_id)->get()->row_array();


			$survey_locations = $this->Organization_model->get_survey_locations($surveydetails, $form_id, $value_chain_id);
			
			$form_check_group_fields_count = $this->db->where('type', 'group')->where('form_id', $form_id)->where('status', 1)->get('form_field')->num_rows();
			
			$header_result = array('main_menu' => $main_menu);

			$result = array('form_name' => $form_name, 'form_fields' => $form_fields, 'surveydetails' => $surveydetails, 'form_check_group_fields_count' => $form_check_group_fields_count, 'survey_locations' => $survey_locations,'title'=>$title);

			$this->load->view('header', $header_result);
			$this->load->view('reports/view_organization_data', $result);
			$this->load->view('footer');
		}
	}
}
