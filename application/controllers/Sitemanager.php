<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemanager extends CI_Controller {

	public function index()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$data=array();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Sitemanager_model');
			$result['counties']=$this->Sitemanager_model->get_counties($data);

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/allcounties',$result);
			$this->load->view('footer');

		}


	}
	public function get_subcounty()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$county_id=$this->uri->segment(3);
			$data['county_id']=$county_id;
			$cdata=array();

			$this->load->model('Sitemanager_model');
			$result['subcounties']=$this->Sitemanager_model->get_subcounty($data);

			$result['counties']=$this->Sitemanager_model->get_counties($cdata);

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/allsubcounties',$result);
			$this->load->view('footer');

		}

	}
	public function get_ward()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$sub_county_id=$this->uri->segment(3);
			$data['sub_county_id']=$sub_county_id;
			$cdata=array();

			$this->load->model('Sitemanager_model');
			$result['wards1']=$this->Sitemanager_model->get_ward($data);

			$result['counties']=$this->Sitemanager_model->get_counties($cdata);

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/allwards',$result);
			$this->load->view('footer');	
		}

	}
	public function add_county()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			
			$result=array();

			$this->load->model('Dynamicmenu_model');

			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/add_county',$result);
			$this->load->view('footer');
		}

	}
	public function addcounty()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('county_name'),
				'lat'=>$this->input->post('latitude'),
				'lng'=>$this->input->post('longitude'),
				'status'=>1);

			if(!empty($this->input->post('county_id')))
			{
				$data['county_id']=$this->input->post('county_id');
			}

			$this->load->model('Sitemanager_model');

			$rs=$this->Sitemanager_model->save_county($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	public function add_subcounty()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			
			$data=array();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$this->load->view('header', $header_result);

			$this->load->model('Sitemanager_model');
			$result['counties']=$this->Sitemanager_model->get_counties($data);

			$this->load->view('sitemanager/add_subcounty',$result);
			$this->load->view('footer');
		}
	}
	public function addsubcounty()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('county_id'=>$this->input->post('county_id'),
				'sub_county_name'=>$this->input->post('subcounty_name'),
				'lat'=>$this->input->post('latitude'),
				'lng'=>$this->input->post('longitude'),
				'status'=>1);

			if(!empty($this->input->post('subcounty_id')))
			{
				$data['sub_county_id']=$this->input->post('subcounty_id');
			}

			$this->load->model('Sitemanager_model');

			$rs=$this->Sitemanager_model->save_subcounty($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
		}
	}
	public function add_ward()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{

			$data=array();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);

			$this->load->model('Sitemanager_model');
			$result['counties']=$this->Sitemanager_model->get_counties($data);
				// $result['subcounties']=$this->Sitemanager_model->get_subcounties();
			$this->load->view('sitemanager/add_ward',$result);
			$this->load->view('footer');

		}
	}
	public function get_subcountyby_county()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('county_id'=>$this->input->post('county_id'));

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$this->load->view('header', $header_result);

			$this->load->model('Sitemanager_model');
			$subcounties=$this->Sitemanager_model->getsubcounty_by_county($data);

			echo json_encode(array('subcounty'=>$subcounties,'status'=>1));exit();

		}
	}
	public function addward()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{

			$data=array('county_id'=>$this->input->post('county_id'),
				'sub_county_id'=>$this->input->post('subcounty_id'),
				'ward_name'=>$this->input->post('ward_name'),
				'lat'=>$this->input->post('latitude'),
				'lng'=>$this->input->post('longitude'),
				'status'=>1);

			if(!empty($this->input->post('ward_id')))
			{
				$data['ward_id']=$this->input->post('ward_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_ward($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
		}
	}
	public function county_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{
			$data=array('county_id'=>$this->input->post('county_id'),
				'county_status'=>$this->input->post('county_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->county_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}

	}
	public function subcounty_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{
			$data=array('subcounty_id'=>$this->input->post('subcounty_id'),
				'subcounty_status'=>$this->input->post('subcounty_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->subcounty_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}

	}
	public function ward_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{
			$data=array('ward_id'=>$this->input->post('ward_id'),
				'status'=>$this->input->post('status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->ward_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}

	}
	public function county_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			if(!empty($this->uri->segment(3))){

				$county_id=$this->uri->segment(3);
				$data=array('county_id'=>$county_id);

				$this->load->model('Sitemanager_model');
				$result['county']=$this->Sitemanager_model->get_counties($data);

				$this->load->model('Dynamicmenu_model');
				$main_menu = $this->Dynamicmenu_model->menu_details();

				$header_result = array('main_menu' => $main_menu);

				$this->load->view('header', $header_result);
				$this->load->view('sitemanager/add_county',$result);
				$this->load->view('footer');
			}
			else
			{
				show_404();
			}

		}
	}
	public function subcounty_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			if(!empty($this->uri->segment(3))){

				$subcounty_id=$this->uri->segment(3);
				$data['subcounty_id']=$subcounty_id;

				$this->load->model('Sitemanager_model');

				$result['subcounty']=$this->Sitemanager_model->get_subcounty($data);
				$result['counties']=$this->Sitemanager_model->get_counties($data);

				$this->load->model('Dynamicmenu_model');
				$main_menu = $this->Dynamicmenu_model->menu_details();

				$header_result = array('main_menu' => $main_menu);

				$this->load->view('header', $header_result);
				$this->load->view('sitemanager/add_subcounty',$result);
				$this->load->view('footer');
			}
			else
			{
				show_404();
			}

		}

	}
	public function ward_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			if(!empty($this->uri->segment(3))){

				$ward_id=$this->uri->segment(3);
				$data['ward_id']=$ward_id;

				$this->load->model('Sitemanager_model');
				$result['subcounty']=$this->Sitemanager_model->get_subcounty($data);
				$result['counties']=$this->Sitemanager_model->get_counties($data);
				$result['wards']=$this->Sitemanager_model->get_ward($data);

				$this->load->model('Dynamicmenu_model');
				$main_menu = $this->Dynamicmenu_model->menu_details();

				$header_result = array('main_menu' => $main_menu);

				$this->load->view('header', $header_result);
				$this->load->view('sitemanager/add_ward',$result);
				$this->load->view('footer');
			}
			else
			{
				show_404();
			}

		}
	}
	public function debt_type()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else{

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['debts']=$this->Sitemanager_model->get_debt_type();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/debt_type',$result);
			$this->load->view('footer');

		}

	}
	public function dtcfarmertype()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['dtcfarmertypes']=$this->Sitemanager_model->get_dtcfarmer_type();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/dtcfarmer_type',$result);
			$this->load->view('footer');
		}
	}

	public function get_education()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['education']=$this->Sitemanager_model->get_education();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/education',$result);
			$this->load->view('footer');
		}
	}
	

		//education edit
	public function education_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('education_id'=>$this->input->post('education_id'));

			$this->load->model('Sitemanager_model');
			$education=$this->Sitemanager_model->get_educations($data);

			if(!empty($education)){
				echo json_encode(array('education'=>$education,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
		//education status update
	public function education_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('education_id'=>$this->input->post('education_id'),
				'education_status'=>$this->input->post('education_status'));

			$this->load->model('Sitemanager_model');

			$cstatus=$this->Sitemanager_model->education_delete($data);
			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//save edit for education
	public function addeducation()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('education_name'=>$this->input->post('education_name'),
				'education_status'=>1,
				'added_by'=>$this->session->userdata('login_id'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
					);

			if(!empty($this->input->post('education_id')))
			{
				$data['education_id']=$this->input->post('education_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_education($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
		}
	}
	
	//financingaccessed_type view
	public function get_financingaccessed_type()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['financingaccessed_type']=$this->Sitemanager_model->get_financingaccessed_type();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/financingaccessed_type',$result);
			$this->load->view('footer');
		}
	}

	public function get_training_specifics()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else{

			$data=array();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['types']=$this->Sitemanager_model->get_training_type($data);

			$result['specifics']=$this->Sitemanager_model->get_training_specifics($data);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/training_specifics',$result);
			$this->load->view('footer');
		}
	}
	public function get_training_type()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else{
			$data=array();
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();
			$header_result = array('main_menu' => $main_menu);
			$this->load->model('Sitemanager_model');
			$result['types']=$this->Sitemanager_model->get_training_type($data);
			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/training_type',$result);
			$this->load->view('footer');
		}
	}
	public function valuechain()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else{

			$data=array();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['valuechains']=$this->Sitemanager_model->get_valuechain($data);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/valuechain',$result);
			$this->load->view('footer');
		}
	}
	public function add_valuechain()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array(
				'value_chain_name'=>$this->input->post('valuechain_name'),
				'value_chain_description'=>$this->input->post('valuechain_description'),
				'added_by'=>$this->session->userdata('login_id'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
				'status'=>1
			);

			if(!empty($this->input->post('valuechain_id'))){
				$data['value_chain_id']=$this->input->post('valuechain_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->insert_valuechain($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else{
				echo json_encode(array('status'=>1));exit();
			}
		}

	}
	public function valuechain_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('value_chain_id'=>$this->input->post('valuechain_id'));

			$this->load->model('Sitemanager_model');
			$valuechain=$this->Sitemanager_model->get_valuechain($data);

			if(!empty($valuechain)){
				echo json_encode(array('valuechain'=>$valuechain,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	public function valuechain_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('status'=>$this->input->post('valuechain_status'),
				'value_chain_id'=>$this->input->post('valuechain_id'));

			$this->load->model('Sitemanager_model');
			$status=$this->Sitemanager_model->delete_valuechain($data);

			echo json_encode(array('cstatus'=>$status));exit();
			
		}

	}
	public function addtrainingtype()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array(
				'name'=>$this->input->post('trainingtype_name'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
				'status'=>1);

			if(!empty($this->input->post('trainingtype_id')))
			{
				$data['id']=$this->input->post('trainingtype_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->add_trainingtype($data);

			if(!$rs)
			{
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
		}
	}
	public function edit_trainingtype()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('trainingtype_id'=>$this->input->post('trainingtype_id'));

			$this->load->model('Sitemanager_model');
			$types=$this->Sitemanager_model->get_training_type($data);

			echo json_encode(array('types'=>$types,'status'=>1));exit();
		}

	}
	public function addtraining_specific()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('trainingtype_id'=>$this->input->post('type_id'),
				'name'=>$this->input->post('specific_name'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
				'status'=>1);

			if(!empty($this->input->post('specific_id')))
			{
				$data['id']=$this->input->post('specific_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->insert_training_specific($data);

			if(!$rs)
			{
				echo json_encode(array('status'=>0));exit();
			}
			else{
				echo json_encode(array('status'=>1));exit();
			}

		}

	}
	public function edittraining_specific()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('specific_id'=>$this->input->post('specific_id'));

			$this->load->model('Sitemanager_model');
			$specifics=$this->Sitemanager_model->get_training_specifics($data);

			$types=$this->Sitemanager_model->get_training_type($data);

			echo json_encode(array('specifics'=>$specifics,'status'=>1,'trainingtypes'=>$types));exit();
		}

	}
	public function specific_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('status'=>$this->input->post('specific_status'),
				'specific_id'=>$this->input->post('specific_id'));

			$this->load->model('Sitemanager_model');
			$status=$this->Sitemanager_model->delete_specific($data);

			echo json_encode(array('cstatus'=>$status));exit();
			
		}

	}
	//respondentritn view
	public function get_respondentritn()
	{
		if($this->session->userdata('login_id') == ''){
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['respondentritn']=$this->Sitemanager_model->get_respondentritn();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/respondentritn',$result);
			$this->load->view('footer');
		}
	}
	//respondentritn status update
	public function get_specificsby_type()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data['type_id']=$this->input->post('type_id');

			$this->load->model('Sitemanager_model');
			$specifics=$this->Sitemanager_model->get_specificsby_type($data);

			echo json_encode(array('specifics'=>$specifics,'status'=>1));exit();
		}




	}
	public function respondentritn_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('id'),
				'respondentritn_status'=>$this->input->post('respondentritn_status'));

			$this->load->model('Sitemanager_model');

			$cstatus=$this->Sitemanager_model->respondentritn_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//respondentritn edit
	public function respondentritn_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$respondentritn=$this->Sitemanager_model->get_respondentritns($data);

			if(!empty($respondentritn)){
				echo json_encode(array('respondentritn'=>$respondentritn,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	//save edit for respondentritn
	public function addrespondentritn()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('relationship'=>$this->input->post('relationship'),
				'status'=>1,
			);

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_respondentritn($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}




	//market view
	public function get_market()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['market']=$this->Sitemanager_model->get_market();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/market',$result);
			$this->load->view('footer');
		}
	}
	//market status update
	public function market_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{
			$data=array('id'=>$this->input->post('id'),
				'market_status'=>$this->input->post('market_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->market_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}

	}
	//market edit
	public function market_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$market=$this->Sitemanager_model->get_markets($data);

			if(!empty($market)){
				echo json_encode(array('market'=>$market,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}

	//technologypractice view
	public function get_technologypractice()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['technologypractice']=$this->Sitemanager_model->get_technologypractice();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/technologypractice',$result);
			$this->load->view('footer');
		}
	}
	//technologypractice status update
	public function technologypractice_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('id'),
				'technologypractice_status'=>$this->input->post('technologypractice_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->technologypractice_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//technologypractice edit
	public function technologypractice_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$technologypractice=$this->Sitemanager_model->get_technologypractices($data);

			if(!empty($technologypractice)){
				echo json_encode(array('technologypractice'=>$technologypractice,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	//save edit for technologypractice
	public function addtechnologypractice()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('name'),
				'status'=>1,
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address()
			);

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_technologypractice($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}

	//technologytype view
	public function get_technologytype()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['technologytype']=$this->Sitemanager_model->get_technologytype();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/technologytype',$result);
			$this->load->view('footer');
		}
	}
	//technologytype status update
	public function technologytype_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('technologytype_id'=>$this->input->post('technologytype_id'),
				'status'=>$this->input->post('status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->technologytype_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	// //technologytype edit
	// public function technologytype_edit()
	// {
	// 	if($this->session->userdata('login_id') == '') {
	// 		echo json_encode(array(
	// 			'status' => 0,
	// 			'msg' => 'Session Expired! Please login again to continue.'
	// 		));
	// 		exit();
	// 	}
	// 	else{
	// 		if(!empty($this->uri->segment(3))){
	// 			$technologytype_id=$this->uri->segment(3);
	// 			$data=array('technologytype_id'=>$technologytype_id);
	// 			$this->load->model('Sitemanager_model');
	// 			$result['technologytype']=$this->Sitemanager_model->get_technologytypes($data);
	// 			$this->load->model('Dynamicmenu_model');
	// 			$main_menu = $this->Dynamicmenu_model->menu_details();
	// 			$header_result = array('main_menu' => $main_menu);
	// 			$this->load->view('header', $header_result);
	// 			$this->load->view('sitemanager/add_technologytype',$result);
	// 			$this->load->view('footer');
	// 		}
	// 		else
	// 		{
	// 			show_404();
	// 		}

	// 	}
	// }
	//save edit for technologytype
	public function addtechnologytype()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('technologytype_name'=>$this->input->post('technologytype_name'),
				'status'=>1,
				'added_by'=>$this->session->userdata('login_id'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
			);

			if(!empty($this->input->post('technologytype_id')))
			{
				$data['technologytype_id']=$this->input->post('technologytype_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_technologytype($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	//trainingpartners view
	public function get_trainingpartners()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['trainingpartners']=$this->Sitemanager_model->get_trainingpartners();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/trainingpartners',$result);
			$this->load->view('footer');
		}
	}
	//trainingpartners status update
	public function trainingpartners_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('id'),
				'status'=>$this->input->post('status'),
			);

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->trainingpartners_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//save trainingpartners
	public function addtrainingpartners()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('name'),
				'status'=>1,
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
			);

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_trainingpartners($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	//trainingpartners edit
	public function trainingpartners_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$trainingpartners=$this->Sitemanager_model->get_trainingpartnerss($data);

			if(!empty($trainingpartners)){
				echo json_encode(array('trainingpartners'=>$trainingpartners,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	//vc_actor_type view
	public function get_vc_actor_type()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['vc_actor_type']=$this->Sitemanager_model->get_vc_actor_type();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/vc_actor_type',$result);
			$this->load->view('footer');
		}
	}
	//vc_actor_type status update
	public function vc_actor_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{
			$data=array('vc_actor_id'=>$this->input->post('vc_actor_id'),
				'status'=>$this->input->post('status'),
			);

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->vc_actor_type_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}

	}
	//gender view
	public function get_gender()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['gender']=$this->Sitemanager_model->get_gender();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/gender',$result);
			$this->load->view('footer');
		}
	}
	//gender status update
	public function gender_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('id'),
				'gender_status'=>$this->input->post('gender_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->gender_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//yesno view
	public function get_yesno()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['yesno']=$this->Sitemanager_model->get_yesno();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/yesno',$result);
			$this->load->view('footer');
		}
	}
	//yesno status update
	public function yesno_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('id'),
				'yesno_status'=>$this->input->post('yesno_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->yesno_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//yesno edit
	public function yesno_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$yesno=$this->Sitemanager_model->get_yesnos($data);

			if(!empty($yesno)){
				echo json_encode(array('yesno'=>$yesno,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}

	//save edit for yesno
	public function addyesno()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('name'),
				'status'=>1,
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address());

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_yesno($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}

	//gender edit
	public function gender_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$gender=$this->Sitemanager_model->get_genders($data);

			if(!empty($gender)){
				echo json_encode(array('gender'=>$gender,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	//save edit for gender
	public function addgender()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('type'=>$this->input->post('type'),
				'status'=>1);

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_gender($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}

	//financingaccessed_type edit
	public function financingaccessed_type_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('id'));

			$this->load->model('Sitemanager_model');
			$financingaccessed_type=$this->Sitemanager_model->get_financingaccessed_types($data);

			if(!empty($financingaccessed_type)){
				echo json_encode(array('financingaccessed_type'=>$financingaccessed_type,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	//save edit for financingaccessed_type
	public function addfinancingaccessed_type()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('name'),
				'status'=>1,
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address());

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_financingaccessed_type($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	//eventtype edit
	public function eventtype_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			if(!empty($this->uri->segment(3))){

				$eventtype_id=$this->uri->segment(3);
				$data=array('eventtype_id'=>$eventtype_id);

				$this->load->model('Sitemanager_model');
				$result['types']=$this->Sitemanager_model->get_training_type($data);

				$result['specifics']=$this->Sitemanager_model->get_training_specifics($data);

				$result['eventtype']=$this->Sitemanager_model->get_eventtypes($data);

				$this->load->model('Dynamicmenu_model');
				$main_menu = $this->Dynamicmenu_model->menu_details();

				$header_result = array('main_menu' => $main_menu);

				$this->load->view('header', $header_result);
				$this->load->view('sitemanager/add_eventtype',$result);
				$this->load->view('footer');
			}
			else
			{
				show_404();
			}

		}
	}
	//save edit for eventtype
	public function addeventtype()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('eventtype_name'),
						'trainingtype_id'=>$this->input->post('trainingtype_id'),
						'trainingspecifics_id'=>$this->input->post('trainingspecifics_id'),
						'added_datetime'=>date('Y-m-d H:i:s'),
						'ip_address'=>$this->input->ip_address(),
						'status'=>1);

			if(!empty($this->input->post('eventtype_id')))
			{
				$data['id']=$this->input->post('eventtype_id');
			}

			$this->load->model('Sitemanager_model');

			$rs=$this->Sitemanager_model->save_eventtype($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	//save edit for market
	public function addmarket()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('name'),
				'status'=>1,
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address());

			if(!empty($this->input->post('id')))
			{
				$data['id']=$this->input->post('id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_market($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	//school view
	public function get_school()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->model('Sitemanager_model');
			$result['school']=$this->Sitemanager_model->get_school();

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/school',$result);
			$this->load->view('footer');
		}
	}
	//school status update
	public function school_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('school_id'=>$this->input->post('school_id'),
				'school_status'=>$this->input->post('school_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->school_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//school edit
	public function school_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('school_id'=>$this->input->post('school_id'));

			$this->load->model('Sitemanager_model');
			$school=$this->Sitemanager_model->get_schools($data);

			if(!empty($school)){
				echo json_encode(array('school'=>$school,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}
	//save edit for school
	public function addschool()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('school_choice'=>$this->input->post('school_choice'),
				'choice_status'=>1,
				'added_by'=>$this->session->userdata('login_id'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
			);

			if(!empty($this->input->post('school_id')))
			{
				$data['school_id']=$this->input->post('school_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_school($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}
	//eventtype view
	public function get_eventtype()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}
		else
		{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$eventtype_id=$this->uri->segment(3);
			$data['eventtype_id']=$eventtype_id;

			$this->load->model('Sitemanager_model');
			$result['types']=$this->Sitemanager_model->get_training_type($data);

			$result['specifics']=$this->Sitemanager_model->get_training_specifics($data);

			$result['eventtype']=$this->Sitemanager_model->get_eventtype($data);

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('sitemanager/eventtype',$result);
			$this->load->view('footer');

		}

	}
	//eventtype status update
	public function eventtype_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('id'),
				'eventtype_status'=>$this->input->post('eventtype_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->eventtype_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}
	//financingaccessed_type status update
	public function financingaccessed_type_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{
			$data=array('id'=>$this->input->post('id'),
				'financingaccessed_type_status'=>$this->input->post('financingaccessed_type_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->financingaccessed_type_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}

	}
	//edit technologytype
	public function technologytype_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('technologytype_id'=>$this->input->post('technologytype_id'));

			$this->load->model('Sitemanager_model');
			$technologytype=$this->Sitemanager_model->get_technologytypes($data);

			if(!empty($technologytype)){
				echo json_encode(array('technologytype'=>$technologytype,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}

		//save vc_actor_type
	public function addvc_actor()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('vc_actor_type'=>$this->input->post('vc_actor_name'),
				'status'=>1,
				'added_by'=>$this->session->userdata('login_id'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
			);

			if(!empty($this->input->post('vc_actor_id')))
			{
				$data['vc_actor_id']=$this->input->post('vc_actor_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_vc_actor_type($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
			

		}
	}

		//edit vc_actor_edit
	public function vc_actor_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('vc_actor_id'=>$this->input->post('vc_actor_id'));

			$this->load->model('Sitemanager_model');
			$vc_actor_type=$this->Sitemanager_model->get_vc_actor_types($data);

			if(!empty($vc_actor_type)){
				echo json_encode(array('vc_actor_type'=>$vc_actor_type,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}

		//debttype  status update
	public function debttype_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('debttype_id'),
				'status'=>$this->input->post('debttype_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->debttype_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}

		//save edit for debttype
	public function adddebttype()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('name'=>$this->input->post('debttype_name'),
				'status'=>1,
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
					);

			if(!empty($this->input->post('debttype_id')))
			{
				$data['id']=$this->input->post('debttype_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_debttype($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
		}
	}

	//debttype edit
	public function debttype_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{

			$data=array('id'=>$this->input->post('debttype_id'));

			$this->load->model('Sitemanager_model');
			$debttype=$this->Sitemanager_model->get_debttypes($data);

			if(!empty($debttype)){
				echo json_encode(array('debttype'=>$debttype,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}

			//dtcfarmer  status update
	public function dtcfarmer_delete()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else
		{	
			$data=array('id'=>$this->input->post('dtcfarmer_id'),
				'status'=>$this->input->post('dtcfarmer_status'));

			$this->load->model('Sitemanager_model');
			$cstatus=$this->Sitemanager_model->dtcfarmer_delete($data);

			echo json_encode(array('status'=>1,'cstatus'=>$cstatus));exit();

		}
	}

		//save edit for dtcfarmer
	public function adddtcfarmer()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{

			$data=array('type'=>$this->input->post('dtcfarmer_name'),
				'status'=>1,
				'added_by'=>$this->session->userdata('login_id'),
				'added_datetime'=>date('Y-m-d H:i:s'),
				'ip_address'=>$this->input->ip_address(),
					);

			if(!empty($this->input->post('dtcfarmer_id')))
			{
				$data['id']=$this->input->post('dtcfarmer_id');
			}

			$this->load->model('Sitemanager_model');
			$rs=$this->Sitemanager_model->save_dtcfarmer($data);

			if(!$rs){
				echo json_encode(array('status'=>0));exit();
			}
			else
			{
				echo json_encode(array('status'=>1));exit();
			}
		}
	}

	//dtcfarmer edit
	public function dtcfarmer_edit()
	{
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		else{
			$data=array('id'=>$this->input->post('dtcfarmer_id'));

			$this->load->model('Sitemanager_model');
			$dtcfarmer=$this->Sitemanager_model->get_dtcfarmers($data);

			if(!empty($dtcfarmer)){
				echo json_encode(array('dtcfarmer'=>$dtcfarmer,'status'=>1));exit();
			}
			else
			{
				echo json_encode(array('msg'=>"Unable to get editable data",'status'=>0));exit();
			}
			
		}
	}

}
?>