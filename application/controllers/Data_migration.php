<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_migration extends CI_Controller {
	
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

	//data migration for form id 1
	public function olm_beneficarydata()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$block_field = "field_1668";
			$village_field = "field_1669";

			$this->db->select('*')->where('form_id', 1)->where('data_status', 1)->order_by('id', 'DESC');
			$olm_beneficarydata = $this->db->get('ic_form_data')->result_array();

			$count = 0;
			foreach ($olm_beneficarydata as $key => $value) {
				$data_array = json_decode($value['form_data'], true);

				$new_blockval = (isset($data_array[$block_field])) ? $data_array[$block_field] : NULL;
				$new_villageval = (isset($data_array[$village_field])) ? $data_array[$village_field] : NULL;

				$update_array = array(
					'block_id' => $new_blockval,
					'village_id' => $new_villageval
				);

				if($new_blockval != NULL && $new_villageval != NULL){
					$this->db->where('data_id', $value['data_id']);
					$query = $this->db->update('ic_form_data', $update_array);

					if(!$query){
						echo "Some thing wrong for".$value['data_id'];
					}else{
						echo "Successfully updated";
					}
				}
			}			
			die();
		}
	}

	/*//data migration for form id 14
	public function survey_form14()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$block_field = "field_1668";
			$village_field = "field_1669";

			$this->db->select('*')->where('form_id', 14)->where('data_status', 1)->order_by('id', 'DESC');
			$olm_beneficarydata = $this->db->get('ic_form_data')->result_array();

			$count = 0;
			foreach ($olm_beneficarydata as $key => $value) {
				$data_array = json_decode($value['form_data'], true);

				$new_blockval = (isset($data_array[$block_field])) ? $data_array[$block_field] : NULL;
				$new_villageval = (isset($data_array[$village_field])) ? $data_array[$village_field] : NULL;

				$update_array = array(
					'block_id' => $new_blockval,
					'village_id' => $new_villageval
				);

				if($new_blockval != NULL && $new_villageval != NULL){
					$this->db->where('data_id', $value['data_id']);
					$query = $this->db->update('ic_form_data', $update_array);

					if(!$query){
						echo "Some thing wrong for".$value['data_id'];
					}else{
						echo "Successfully updated";
					}
				}
			}			
			die();
		}
	}*/

	//data migration for form id 13
	public function survey_form13()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$block_field = "field_1647";
			$village_field = "field_1648";

			$this->db->select('*')->where('form_id', 13)->where('data_status', 1)->order_by('id', 'DESC');
			$olm_beneficarydata = $this->db->get('ic_form_data')->result_array();

			$count = 0;
			foreach ($olm_beneficarydata as $key => $value) {
				$data_array = json_decode($value['form_data'], true);

				$new_blockval = (isset($data_array[$block_field])) ? $data_array[$block_field] : NULL;
				$new_villageval = (isset($data_array[$village_field])) ? $data_array[$village_field] : NULL;

				$update_array = array(
					'block_id' => $new_blockval,
					'village_id' => $new_villageval
				);

				if($new_blockval != NULL && $new_villageval != NULL){
					$this->db->where('data_id', $value['data_id']);
					$query = $this->db->update('ic_form_data', $update_array);

					if(!$query){
						echo "Some thing wrong for".$value['data_id'];
					}else{
						echo "Successfully updated";
					}
				}
			}			
			die();
		}
	}

	/*//data migration for form id 16
	public function survey_form16()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$block_field = "field_1668";
			$village_field = "field_1669";

			$this->db->select('*')->where('form_id', 16)->where('data_status', 1)->order_by('id', 'DESC');
			$olm_beneficarydata = $this->db->get('ic_form_data')->result_array();

			$count = 0;
			foreach ($olm_beneficarydata as $key => $value) {
				$data_array = json_decode($value['form_data'], true);

				$new_blockval = (isset($data_array[$block_field])) ? $data_array[$block_field] : NULL;
				$new_villageval = (isset($data_array[$village_field])) ? $data_array[$village_field] : NULL;

				$update_array = array(
					'block_id' => $new_blockval,
					'village_id' => $new_villageval
				);

				if($new_blockval != NULL && $new_villageval != NULL){
					$this->db->where('data_id', $value['data_id']);
					$query = $this->db->update('ic_form_data', $update_array);

					if(!$query){
						echo "Some thing wrong for".$value['data_id'];
					}else{
						echo "Successfully updated";
					}
				}
			}			
			die();
		}
	}*/

	//data migration for form id 19
	public function survey_form19()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$block_field = "field_2877";
			$village_field = "field_2878";

			$this->db->select('*')->where('form_id', 19)->where('data_status', 1)->order_by('id', 'DESC');
			$olm_beneficarydata = $this->db->get('ic_form_data')->result_array();

			$count = 0;
			foreach ($olm_beneficarydata as $key => $value) {
				$data_array = json_decode($value['form_data'], true);

				$new_blockval = (isset($data_array[$block_field])) ? $data_array[$block_field] : NULL;
				$new_villageval = (isset($data_array[$village_field])) ? $data_array[$village_field] : NULL;

				$update_array = array(
					'block_id' => $new_blockval,
					'village_id' => $new_villageval
				);

				if($new_blockval != NULL && $new_villageval != NULL){
					$this->db->where('data_id', $value['data_id']);
					$query = $this->db->update('ic_form_data', $update_array);

					if(!$query){
						echo "Some thing wrong for".$value['data_id'];
					}else{
						echo "Successfully updated";
					}
				}
			}			
			die();
		}
	}

	//data migration for form id 22
	public function survey_form22()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$block_field = "field_2963";
			$village_field = "field_2964";

			$this->db->select('*')->where('form_id', 22)->where('data_status', 1)->order_by('id', 'DESC');
			$olm_beneficarydata = $this->db->get('ic_form_data')->result_array();

			$count = 0;
			foreach ($olm_beneficarydata as $key => $value) {
				$data_array = json_decode($value['form_data'], true);

				$new_blockval = (isset($data_array[$block_field])) ? $data_array[$block_field] : NULL;
				$new_villageval = (isset($data_array[$village_field])) ? $data_array[$village_field] : NULL;

				$update_array = array(
					'block_id' => $new_blockval,
					'village_id' => $new_villageval
				);

				if($new_blockval != NULL && $new_villageval != NULL){
					$this->db->where('data_id', $value['data_id']);
					$query = $this->db->update('ic_form_data', $update_array);

					if(!$query){
						echo "Some thing wrong for".$value['data_id'];
					}else{
						echo "Successfully updated";
					}
				}
			}			
			die();
		}
	}	
}