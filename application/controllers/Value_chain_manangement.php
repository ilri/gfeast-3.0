<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Value_chain_manangement extends CI_Controller {
	
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

	public function add_survey()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('value_chain_management/add_survey');
			$this->load->view('footer');
		}
	}

	public function manage_survey()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$user_surveys = $this->Valuechainmanagement_model->get_user_surveys();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('value_chain_management/manage_survey', $user_surveys);
			$this->load->view('footer');
		}	
	}

	public function manage_value_chain($value='')
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$get_value_chain = $this->Valuechainmanagement_model->get_value_chain();

			$header_result = array('main_menu' => $main_menu);

			$result = array('get_value_chain' => $get_value_chain);

			$this->load->view('header', $header_result);
			$this->load->view('value_chain_management/manage_value_chain', $result);
			$this->load->view('footer');
		}
	}
	
	public function manage_value_chain_location()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			if($this->uri->segment(3) == '' || $this->uri->segment(3) == NULL){
				show_404();
			}

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$get_value_chain_locations = $this->Valuechainmanagement_model->get_value_chain_locations($this->uri->segment(3));

			$header_result = array('main_menu' => $main_menu);

			$result = array('get_value_chain_locations' => $get_value_chain_locations);

			$this->load->view('header', $header_result);
			$this->load->view('value_chain_management/manage_value_chain_location', $result);
			$this->load->view('footer');
		}
	}

	public function manage_surveys_byvaluechain()
	{
		$baseurl = base_url();
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'msg' => 'Your session has ended. Please login again and try again.',
				'status' => 0
			));
			exit();
		}else{
			$this->load->model('Valuechainmanagement_model');
			$get_value_chain_locations = $this->Valuechainmanagement_model->get_value_chain_locations($_POST['value_chain_val']);

			$get_value_chain_locations['status'] = 1;

			echo json_encode($get_value_chain_locations);
			exit();
		}
	}

	public function manage_users_value_chain_location()
	{
		if($this->session->userdata('login_id') == '') {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			if($this->uri->segment(3) == '' || $this->uri->segment(3) == NULL || $this->uri->segment(4) == '' || $this->uri->segment(4) == NULL){
				show_404();
			}

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$get_value_chain_locations_users = $this->Valuechainmanagement_model->get_value_chain_locations_users($this->uri->segment(3),$this->uri->segment(4));

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('value_chain_management/manage_users_value_chain_location', $get_value_chain_locations_users);
			$this->load->view('footer');
		}
	}

	public function manage_users()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$get_valuechain_users = $this->Valuechainmanagement_model->get_value_chain_users_by_id();
			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('value_chain_management/manage_user_value_chain', $get_valuechain_users);
			$this->load->view('footer');
		}
	}

	public function get_allsurvey()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_allsurvey = $this->Valuechainmanagement_model->get_allsurvey();

		$result = array('status' => 1, 'form_list' => $get_allsurvey);

		echo json_encode($result);
		die();
	}

	public function get_surveydetails()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_surveydetails = $this->Valuechainmanagement_model->get_surveydetails();

		$get_surveydetails['status'] = 1;

		echo json_encode($get_surveydetails);
		die();
	}

	public function assign_valuechain_survey()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$assign_valuechain_survey = $this->Valuechainmanagement_model->assign_valuechain_survey();

		if($assign_valuechain_survey){
			$result = array('status' => 1, 'msg' => 'Assigning done successfully');
		}else{
			$result = array('status' => 0, 'msg' => 'Some thing went wrong please try after some time');
		}

		echo json_encode($result);
		die();
	}

	public function get_uservaluechain()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_uservaluechain = $this->Valuechainmanagement_model->get_uservaluechain();

		$get_county_list = $this->Valuechainmanagement_model->get_county_list();

		$result = array('status' => 1, 'get_uservaluechain' => $get_uservaluechain, 'get_county_list' => $get_county_list);

		echo json_encode($result);
		die();
	}

	public function get_valuechain_users()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Valuechainmanagement_model');
		$get_valuechain_users = $this->Valuechainmanagement_model->get_value_chain_users_by_id();
		echo json_encode($get_valuechain_users);
		exit();
	}

	public function get_valuechain_users_toassign()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_valuechain_users = $this->Valuechainmanagement_model->get_valuechain_userids();

		$users_list = $this->Valuechainmanagement_model->get_users_list($get_valuechain_users);

		$result = array('status' => 1, 'get_valuechain_users' => $get_valuechain_users, 'users_list' => $users_list);

		echo json_encode($result);
		exit();
	}

	//get_userchain_by_id
	public function get_userchain_by_id(){
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Valuechainmanagement_model');
		$userbyid = $this->Valuechainmanagement_model->get_userbyid();
		$result=array('status'=>1,'result'=>$userbyid);
		echo json_encode($result);
		die();
	}
	//get_valuechain_allusers
	public function get_valuechain_allusers(){
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Valuechainmanagement_model');
		$get_valuechain_users = $this->Valuechainmanagement_model->get_valuechain_userids();
		$users_list = $this->Valuechainmanagement_model->get_users_list($get_valuechain_users);
		$valuechainname =$this->Valuechainmanagement_model->get_valuechain_name();
		$result = array('status' => 1, 'get_valuechain_users' => $get_valuechain_users, 'users_list' => $users_list, 'val_chain_name'=>$valuechainname);
		echo json_encode($result);
		die();
	}

	public function get_subcounties()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_subcounty_list = $this->Valuechainmanagement_model->get_subcounty_list();

		$result = array('status' => 1, 'get_subcounty_list' => $get_subcounty_list);

		echo json_encode($result);
		die();
	}

	public function get_ward()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_ward_list = $this->Valuechainmanagement_model->get_ward_list();

		$result = array('status' => 1, 'get_ward_list' => $get_ward_list);

		echo json_encode($result);
		die();
	}
	public function get_ward_byvaluechain()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_ward_list = $this->Valuechainmanagement_model->get_ward_byvaluechain();

		$result = array('status' => 1, 'get_ward_list' => $get_ward_list);

		echo json_encode($result);
		die();
	}

	public function assign_valuechain_location()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$assign_valuechain_location = $this->Valuechainmanagement_model->assign_valuechain_location();

		if($assign_valuechain_location){
			$result = array('status' => 1, 'msg' => 'Assigning done successfully');
		}else{
			$result = array('status' => 0, 'msg' => 'Some thing went wrong please try after some time');
		}

		echo json_encode($result);
		die();
	}

	public function get_valuechain_user_county()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_valuechain_user = $this->Valuechainmanagement_model->get_valuechain_user();

		$get_valuechain_county = $this->Valuechainmanagement_model->get_valuechain_county();

		$result = array('status' => 1, 'get_valuechain_user' => $get_valuechain_user, 'get_valuechain_county' => $get_valuechain_county);

		echo json_encode($result);
		die();
	}

	public function get_valuechain_subcounties()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_valuechain_subcounties_list = $this->Valuechainmanagement_model->get_valuechain_subcounties_list();

		$result = array('status' => 1, 'get_valuechain_subcounties_list' => $get_valuechain_subcounties_list);

		echo json_encode($result);
		die();
	}

	public function get_valuechain_wards()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$get_valuechain_wards = $this->Valuechainmanagement_model->get_valuechain_wards();

		$result = array('status' => 1, 'get_valuechain_wards' => $get_valuechain_wards);

		echo json_encode($result);
		die();
	}

	public function assign_user_valuechain_locations()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$assign_user_valuechain_locations = $this->Valuechainmanagement_model->assign_user_valuechain_locations();

		if($assign_user_valuechain_locations){
			$result = array('status' => 1, 'msg' => 'Assigning done successfully');
		}else{
			$result = array('status' => 0, 'msg' => 'Some thing went wrong please try after some time');
		}

		echo json_encode($result);
		die();
	}

	public function assign_valuechain_user()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->load->model('Valuechainmanagement_model');
		$assign_valuechain_user = $this->Valuechainmanagement_model->assign_valuechain_user();

		if($assign_valuechain_user){
			$result = array('status' => 1, 'msg' => 'Assigning done successfully');
		}else{
			$result = array('status' => 0, 'msg' => 'Some thing went wrong please try after some time');
		}

		echo json_encode($result);
		die();
	}

	public function assign_valuechain_userlocation()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$result['user_valuechain']=$this->Valuechainmanagement_model->get_user_surveys();
			// $get_value_chain_locations_users = $this->Valuechainmanagement_model->get_value_chain_locations_users($this->uri->segment(3),$this->uri->segment(4));

			$header_result = array('main_menu' => $main_menu);

		$this->load->view('header', $header_result);
		$this->load->view('value_chain_management/assign_valuechain_userlocation',$result);
		$this->load->view('footer');


	}

	public function assign_valuechain_loc()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->load->model('Valuechainmanagement_model');
			$result['user_valuechain']=$this->Valuechainmanagement_model->get_user_surveys();
			$result['counties']=$this->Valuechainmanagement_model->get_allcounty();
			// $get_value_chain_locations_users = $this->Valuechainmanagement_model->get_value_chain_locations_users($this->uri->segment(3),$this->uri->segment(4));

			$header_result = array('main_menu' => $main_menu);

		$this->load->view('header', $header_result);
		$this->load->view('value_chain_management/assign_valuechain_loc',$result);
		$this->load->view('footer');
	}

	public function assign_value_chain_user()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();
			$header_result = array('main_menu' => $main_menu);

		$this->load->model('Valuechainmanagement_model');
		$result['get_uservaluechain'] = $this->Valuechainmanagement_model->get_uservaluechain();

		$result['get_county_list'] = $this->Valuechainmanagement_model->get_county_list();

		$this->load->view('header', $header_result);
		$this->load->view('value_chain_management/assign_value_chain_user',$result);
		$this->load->view('footer');
		

	}
	public function delete_users_value_chain_location()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$value_chain_user_loc_id=$_POST['record_id'];
		$reason=$_POST['reason'];
		$rs=$this->db->where('value_chain_user_loc_id',$value_chain_user_loc_id)->set('value_chain_user_loc_status',0)->update('rpt_value_chain_user_location');
		if($rs)
		{
			$result=array('editedby'=>$this->session->userdata('login_id'),
                'editedfor'=>'',
                'table_name'=>'rpt_value_chain_user_location',
                'table_row_id'=>$value_chain_user_loc_id,
                'table_field_name'=>'status',
                'old_value'=>1,
                'new_value'=>0,
                'edited_reason'=>$reason,
                'updated_date'=>date('Y-m-d H:i:s'),
                'ip_address'=>$this->input->ip_address(),
                'log_status'=>1
            );
            $res=$this->db->insert('ic_log',$result);
            if($res){
			echo json_encode(array('status'=>1,'msg'=>'Location deleted successfully'));exit();
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'Unable to delete Location'));exit();
		}

	}
	public function delete_value_chain_location()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$value_chain_loc_id=$_POST['record_id'];
		$reason=$_POST['reason'];
		$rs=$this->db->where('value_chain_loc_id',$value_chain_loc_id)->set('loc_status',0)->update('rpt_value_chain_location');
		if($rs)
		{
			$result=array('editedby'=>$this->session->userdata('login_id'),
                'editedfor'=>'',
                'table_name'=>'rpt_value_chain_location',
                'table_row_id'=>$value_chain_loc_id,
                'table_field_name'=>'status',
                'old_value'=>1,
                'new_value'=>0,
                'edited_reason'=>$reason,
                'updated_date'=>date('Y-m-d H:i:s'),
                'ip_address'=>$this->input->ip_address(),
                'log_status'=>1
            );
            $res=$this->db->insert('ic_log',$result);
            if($res){
			echo json_encode(array('status'=>1,'msg'=>'Location deleted successfully'));exit();
			}
		}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'Unable to delete Location'));exit();
		}
	}

	public function get_users_value_chain_location()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Valuechainmanagement_model');
		$userdata=$this->Valuechainmanagement_model->get_users_value_chain_location();
		echo json_encode(array('userdata'=>$userdata,'status'=>1));exit();

	}
	public function updatevaluechain_details()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
	    
		}
		$user_id=$this->session->userdata('login_id');
		$recordid = $_POST['recordid'];
		$county_id = $_POST['county'];
		$subcounty_id = $_POST['subcounty'];
		$ward_id = $_POST['ward'];

		$rs=$this->db->set('lkp_county_id',$county_id)->set('lkp_sub_county_id',$subcounty_id)->set('lkp_ward_id',$ward_id)->where('value_chain_user_loc_id',$recordid)->update('rpt_value_chain_user_location');

		if($rs)
		{
			$recorddata=$this->db->select('county.name as countyname,sub.sub_county_name as subcountyname,ward.ward_name as wardname')->from('rpt_value_chain_user_location loc')->join('lkp_county county','county.county_id=loc.lkp_county_id')->join('lkp_sub_county sub','sub.sub_county_id=loc.lkp_sub_county_id')->join('lkp_ward ward','ward.ward_id=loc.lkp_ward_id')->where('loc.value_chain_user_loc_id',$recordid)->get()->row_array();

			echo json_encode(array('status'=>1,'msg'=>"Location updated successfully",'countyname'=>$recorddata['countyname'],'subcountyname'=>$recorddata['subcountyname'],'wardname'=>$recorddata['wardname']));exit();
		}
	}

	public function getvalue_chain_location()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$this->load->model('Valuechainmanagement_model');
		$userdata=$this->Valuechainmanagement_model->getvalue_chain_location();
		echo json_encode(array('userdata'=>$userdata,'status'=>1));exit();

	}
	public function updatevaluechain_loc()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
	    
		}
		$recordid = $_POST['recordid'];
		$county_id = $_POST['county'];
		$subcounty_id = $_POST['subcounty'];
		$ward_id = $_POST['ward'];

		$rs=$this->db->set('lkp_county_id',$county_id)->set('lkp_sub_county_id',$subcounty_id)->set('lkp_ward_id',$ward_id)->where('value_chain_loc_id',$recordid)->update('rpt_value_chain_location');

		if($rs)
		{

			$recorddata=$this->db->select('county.name as countyname,sub.sub_county_name as subcountyname,ward.ward_name as wardname')->from('rpt_value_chain_location loc')->join('lkp_county county','county.county_id=loc.lkp_county_id')->join('lkp_sub_county sub','sub.sub_county_id=loc.lkp_sub_county_id')->join('lkp_ward ward','ward.ward_id=loc.lkp_ward_id')->where('loc.value_chain_loc_id',$recordid)->get()->row_array();

			echo json_encode(array('status'=>1,'msg'=>"Location updated successfully",'countyname'=>$recorddata['countyname'],'subcountyname'=>$recorddata['subcountyname'],'wardname'=>$recorddata['wardname']));exit();
		}
	}
	public function alldelete_users_value_chain_location()
	{
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$recordids=$_POST['record_ids'];
		$reason=$_POST['reason'];

	    $value_chain_user_loc_ids=explode(',',$recordids);

		foreach($value_chain_user_loc_ids as $value_chain_user_loc_id){
		$rs=$this->db->where('value_chain_user_loc_id',$value_chain_user_loc_id)->set('value_chain_user_loc_status',0)->update('rpt_value_chain_user_location');
			if($rs)
			{
				$result=array('editedby'=>$this->session->userdata('login_id'),
                'editedfor'=>'',
                'table_name'=>'rpt_value_chain_user_location',
                'table_row_id'=>$value_chain_user_loc_id,
                'table_field_name'=>'status',
                'old_value'=>1,
                'new_value'=>0,
                'edited_reason'=>$reason,
                'updated_date'=>date('Y-m-d H:i:s'),
                'ip_address'=>$this->input->ip_address(),
                'log_status'=>1
            	);
            	$res=$this->db->insert('ic_log',$result);
          
			}
		}
		  if($res){
			echo json_encode(array('status'=>1,'msg'=>'Record deleted successfully'));exit();
			}
		else
		{
			echo json_encode(array('status'=>0,'msg'=>'Unable to delete record'));exit();
		}

	}
	public function check_valuechain_location(){
		$baseurl = base_url();
		
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}
		$value_chain_loc_id=$_POST['value_chain_loc_id'];
		$info=$this->db->select('*')->from('rpt_value_chain_location')->where('value_chain_loc_id',$value_chain_loc_id)->where('loc_status',1)->get()->row_array();

		$check_record_count= $this->db->where('lkp_value_chain_id', $info['lkp_value_chain_id'])->where('lkp_county_id',$info['lkp_county_id'])->where('lkp_sub_county_id', $info['lkp_sub_county_id'])->where('lkp_ward_id', $info['lkp_ward_id'])->where('value_chain_user_loc_status', 1)->get('rpt_value_chain_user_location')->num_rows();
	
		
		 echo json_encode(array('status'=>1,'count'=>$check_record_count));exit();
		
	}
}