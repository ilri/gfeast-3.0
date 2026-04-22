<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('uniqueid_lib');		
	}

	public function index(){
		show_404();	
	}

	public function check_childfields(){
		$baseurl = base_url();
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array('status' => 0, 'msg' => 'Your session has expired please login and try again'));
			exit();
		}

		$field_id = $_POST['field_id'];
		$field_value = $_POST['field_value'];
		$survey_id = $_POST['survey_id'];

		$table = "survey".$survey_id;

		if(is_array($field_value)){
			$child_string = '';

			foreach ($field_value as $key => $value) {
				$this->db->where('form_id', $survey_id)->where('parent_id', $field_id)->like('parent_value', $value)->where('status', 1)->order_by('slno');
				$get_child_list = $this->db->get('form_field')->result_array();

				if(count($get_child_list) > 0){

					foreach ($get_child_list as $dkey => $d_field) {
						$option_vals = explode("&#44;", $d_field['parent_value']);

						if(!in_array($value, $option_vals)){
							unset($get_child_list[$dkey]);
						}

						if(in_array($value, $option_vals)){
							if($child_string != ''){
								$child_string .= ",".$d_field['field_id'];
							}else{
								$child_string .= $d_field['field_id'];
							}
						}
					}
				}
			}

			$child_string_array = explode(",", $child_string);

			$this->db->where('form_id', $survey_id)->where_in('field_id', $child_string_array);
			$this->db->where('status', 1)->order_by('slno');
			$get_child_list_array = $this->db->get('form_field')->result_array();

			foreach ($get_child_list_array as $key => $field) {
				if($field['type'] == 'select' || $field['type'] == 'radio-group' || $field['type'] == 'checkbox-group') {
					$get_child_list_array[$key]['options'] = $this->db->where('field_id', $field['field_id'])->where('status', 1)->order_by('order_by')->get('form_field_multiple')->result_array();
				}

				$childfield = "field_".$field['field_id'];
				if(isset($_POST['record_id']) && ($_POST['record_id'] != '')){
					$get_child_field_value = $this->db->select($childfield)->where('id', $_POST['record_id'])->get($table)->row_array();

					$get_child_list_array[$key]['value'] = $get_child_field_value[$childfield];
				}else{
					$get_child_list_array[$key]['value'] = "";
				}

				$this->db->select('field_id');
				$this->db->where('parent_id', $field['field_id'])->where('status', 1)->order_by('slno');
				$get_child_list_array[$key]['check_child_fields'] = $this->db->get('form_field')->num_rows();
			}

			if(count($get_child_list_array) > 0){
				$get_child_list_array = array_values($get_child_list_array);
			}else{
				$get_child_list_array = array();
			}

			$result = array('status' => 1, 'child_field' => $get_child_list_array);
		}else{
			$this->db->where('parent_id', $field_id)->where('form_id', $survey_id)->like('parent_value', $field_value)->where('status', 1)->order_by('slno');
			$get_child_fields = $this->db->get('form_field')->result_array();
			foreach ($get_child_fields as $key => $field) {
				if($field['type'] == 'select' || $field['type'] == 'radio-group' || $field['type'] == 'checkbox-group') {
					$this->db->where('field_id', $field['field_id'])->where('status', 1)->order_by('order_by');
					$get_child_fields[$key]['options'] = $this->db->get('form_field_multiple')->result_array();
				}

				$childfield = "field_".$field['field_id'];

				if(isset($_POST['record_id']) && ($_POST['record_id'] != '')){
					$this->db->select($childfield)->where('id', $_POST['record_id']);
					$get_child_field_value = $this->db->get($table)->row_array();					

					$get_child_fields[$key]['value'] = $get_child_field_value[$childfield];
				}else{
					$get_child_fields[$key]['value'] = "";
				}
				

				$this->db->select('field_id');
				$this->db->where('parent_id', $field['field_id'])->where('status', 1)->order_by('slno');
				$get_child_fields[$key]['check_child_fields'] = $this->db->get('form_field')->num_rows();
				
				$option_vals = explode("&#44;", $field['parent_value']);

				if(!in_array($field_value, $option_vals)){
					unset($get_child_fields[$key]);
				}				
			}
			$get_child_fields = array_values($get_child_fields);

			$result = array('status' => 1, 'child_field' => $get_child_fields);
		}

		echo json_encode($result);
		exit();	
	}

	public function ryot_regitration(){
		$baseurl = base_url();
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array('status' => 0, 'msg' => 'Your session has expired please login and try again'));
			exit();
		}

		$get_village_info = $this->db->select('*')->where('VILLAGE_ID', $this->input->post('field_1012'))->get('lkp_village')->row_array();
		
		$form_fields = $this->db->where('form_id', $this->input->post('survey_id'))->where('status', 1)->get('form_field')->result_array();

		$time = time();
		$datetime = date('Y-m-d H:i:s');

		$insert_array = array();
		$insert_array['data_id'] = $time.'-'.$this->session->userdata('login_id');
		$insert_array['farmer_number'] = $this->uniqueid_lib->shuffle();
		$insert_array['sap_farmer_number'] = NULL;
		$insert_array['division_id'] = $get_village_info['DIV_CODE'];
		$insert_array['circle_id'] = $get_village_info['CIR_CODE'];
		$insert_array['village_id'] = $get_village_info['VILLAGE_CODE'];
		foreach ($form_fields as $key => $value) {
			$field_name = "field_".$value['field_id'];
			if(is_array($this->input->post($field_name))){
				$insert_array[$field_name] = implode("&#44;", $this->input->post($field_name));
			}else{
				$insert_array[$field_name] = $this->input->post($field_name);
			}			
		}
		$insert_array['company_code'] = NULL;
		$insert_array['account_group'] = NULL;
		$insert_array['reconciliation_account'] = NULL;
		$insert_array['purchasing_organization'] = NULL;
		$insert_array['added_by'] =  $this->session->userdata('login_id');
		$insert_array['added_date'] = date('Y-m-d H:i:s');
		$insert_array['ip_address'] = $this->input->ip_address();
		$insert_array['farmer_status'] = 1;

		$query = $this->db->insert('tbl_farmers', $insert_array);

		if($query){
			if(isset($_FILES['survey_images'])) {
				foreach ($_FILES['survey_images']['name'] as $key => $si) {
					if($_FILES['survey_images']['size'][$key] > 0) {
						//Upload Image
						$file = uniqid().$key.$this->session->userdata('login_id').'.jpg';
						$file_size = $_FILES['survey_images']['size'][$key];

						if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
						$imgurl = UPLOAD_DIR . $file;

						$this->load->model('Compress_model');
						$filename = $this->Compress_model->compress_image_file($_FILES["survey_images"]["tmp_name"][$key], $imgurl, $_FILES['survey_images']['size'][$key]);

						if($filename) {
							$surv_image_data = array(
								'file_id' => $time.$key.'-'.$this->session->userdata('login_id'),
								'data_id' => $insert_array['data_id'],
								'form_id' => $this->input->post('survey_id'),
								'user_id' => $this->session->userdata('login_id'),
								'file_name' => $file,
								'file_type' => 'image',
								'created_date' => $datetime,
								'ip_address' => $this->input->ip_address(),
								'status' => 1
							);

							if(isset($_POST['survey_type']) && $_POST['survey_type'] == 'survey'){
								$surv_image_data['division_id'] = $get_village_info['DIV_CODE'];
								$surv_image_data['circle_id'] = $get_village_info['CIR_CODE'];
								$surv_image_data['village_id'] = $get_village_info['VILLAGE_CODE'];
							}
							$surv_image_data = $this->security->xss_clean($surv_image_data);
							$this->db->insert('ic_data_file', $surv_image_data);
						}
					}
				}
			}

			$ajax_message = 'Data submitted successfully. You can now add more data.';

			$result = array(
				'status' => 1,
				'msg' => $ajax_message,
			);

			echo json_encode($result);
			exit();
		}else{
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Sorry! Something went wrong, please try after some time'
			));
			exit();
		}
	}

	public function plot_regitsration(){
		$baseurl = base_url();
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array('status' => 0, 'msg' => 'Your session has expired please login and try again'));
			exit();
		}

		$get_village_info = $this->db->select('*')->where('VILLAGE_ID', $this->input->post('field_1033'))->get('lkp_village')->row_array();
		
		$form_fields = $this->db->where('form_id', $this->input->post('survey_id'))->where('status', 1)->get('form_field')->result_array();

		$get_farmer_number = $this->db->select('farmer_number')->where('data_id', $this->input->post('search_info'))->where('farmer_status', 1)->get('tbl_farmers')->row_array();

		if($get_farmer_number == NULL){
			echo json_encode(array('status' => 0, 'msg' => 'Some thing went wrong please refresh the page and try again.'));
			exit();
		}

		$time = time();
		$datetime = date('Y-m-d H:i:s');

		$insert_array = array();
		$insert_array['data_id'] = $time.'-'.$this->session->userdata('login_id');
		$insert_array['farmer_data_id'] = NULL;
		$insert_array['farmer_number'] = $get_farmer_number['farmer_number'];
		$insert_array['plot_number'] = $this->uniqueid_lib->shuffle_10();
		$insert_array['sap_plot_number'] = NULL;
		$insert_array['division_id'] = $get_village_info['DIV_CODE'];
		$insert_array['circle_id'] = $get_village_info['CIR_CODE'];
		$insert_array['village_id'] = $get_village_info['VILLAGE_CODE'];
		foreach ($form_fields as $key => $value) {
			$field_name = "field_".$value['field_id'];
			if(is_array($this->input->post($field_name))){
				$insert_array[$field_name] = implode("&#44;", $this->input->post($field_name));
			}else{
				$insert_array[$field_name] = $this->input->post($field_name);
			}			
		}
		$insert_array['plant'] = NULL;
		$insert_array['purchasing_group'] = NULL;
		$insert_array['added_by'] =  $this->session->userdata('login_id');
		$insert_array['added_date'] = date('Y-m-d H:i:s');
		$insert_array['ip_address'] = $this->input->ip_address();
		$insert_array['plot_status'] = 1;

		$query = $this->db->insert('tbl_plot', $insert_array);

		if($query){
			if(isset($_FILES['survey_images'])) {
				foreach ($_FILES['survey_images']['name'] as $key => $si) {
					if($_FILES['survey_images']['size'][$key] > 0) {
						//Upload Image
						$file = uniqid().$key.$this->session->userdata('login_id').'.jpg';
						$file_size = $_FILES['survey_images']['size'][$key];

						if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
						$imgurl = UPLOAD_DIR . $file;

						$this->load->model('Compress_model');
						$filename = $this->Compress_model->compress_image_file($_FILES["survey_images"]["tmp_name"][$key], $imgurl, $_FILES['survey_images']['size'][$key]);

						if($filename) {
							$surv_image_data = array(
								'file_id' => $time.$key.'-'.$this->session->userdata('login_id'),
								'data_id' => $insert_array['data_id'],
								'form_id' => $this->input->post('survey_id'),
								'user_id' => $this->session->userdata('login_id'),
								'file_name' => $file,
								'file_type' => 'image',
								'created_date' => $datetime,
								'ip_address' => $this->input->ip_address(),
								'status' => 1
							);

							if(isset($_POST['survey_type']) && $_POST['survey_type'] == 'survey'){
								$surv_image_data['division_id'] = $get_village_info['DIV_CODE'];
								$surv_image_data['circle_id'] = $get_village_info['CIR_CODE'];
								$surv_image_data['village_id'] = $get_village_info['VILLAGE_CODE'];
							}
							$surv_image_data = $this->security->xss_clean($surv_image_data);
							$this->db->insert('ic_data_file', $surv_image_data);
						}
					}
				}
			}

			$ajax_message = 'Data submitted successfully. You can now add more data.';

			$result = array(
				'status' => 1,
				'msg' => $ajax_message,
			);

			echo json_encode($result);
			exit();
		}else{
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Sorry! Something went wrong, please try after some time'
			));
			exit();
		}
	}

	public function plot_agreement(){
		$baseurl = base_url();
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array('status' => 0, 'msg' => 'Your session has expired please login and try again'));
		}
	
		$form_fields = $this->db->where('form_id', $this->input->post('survey_id'))->where('status', 1)->get('form_field')->result_array();

		$time = time();
		$datetime = date('Y-m-d H:i:s');

		$insert_array = array();
		$insert_array['agreement_data_id'] = $time.'-'.$this->session->userdata('login_id');
		$insert_array['plot_data_id'] = NULL;
		$insert_array['farmer_number'] = $this->uniqueid_lib->shuffle();
		foreach ($form_fields as $key => $value) {
			$field_name = "field_".$value['field_id'];
			if(is_array($this->input->post($field_name))){
				$insert_array[$field_name] = implode("&#44;", $this->input->post($field_name));
			}else{
				$insert_array[$field_name] = $this->input->post($field_name);
			}			
		}
		$insert_array['added_by'] =  $this->session->userdata('login_id');
		$insert_array['added_date'] = date('Y-m-d H:i:s');
		$insert_array['ip_address'] = $this->input->ip_address();
		$insert_array['farmer_status'] = 1;

		$query = $this->db->insert('tbl_agreement', $insert_array);

		if($query){
			if(isset($_FILES['survey_images'])) {
				foreach ($_FILES['survey_images']['name'] as $key => $si) {
					if($_FILES['survey_images']['size'][$key] > 0) {
						//Upload Image
						$file = uniqid().$key.$this->session->userdata('login_id').'.jpg';
						$file_size = $_FILES['survey_images']['size'][$key];

						if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
						$imgurl = UPLOAD_DIR . $file;

						$this->load->model('Compress_model');
						$filename = $this->Compress_model->compress_image_file($_FILES["survey_images"]["tmp_name"][$key], $imgurl, $_FILES['survey_images']['size'][$key]);

						if($filename) {
							$surv_image_data = array(
								'file_id' => $time.$key.'-'.$this->session->userdata('login_id'),
								'data_id' => $insert_array['agreement_data_id'],
								'form_id' => $this->input->post('survey_id'),
								'user_id' => $this->session->userdata('login_id'),
								'file_name' => $file,
								'file_type' => 'image',
								'created_date' => $datetime,
								'ip_address' => $this->input->ip_address(),
								'status' => 1
							);
							$surv_image_data = $this->security->xss_clean($surv_image_data);
							$this->db->insert('ic_data_file', $surv_image_data);
						}
					}
				}
			}

			$ajax_message = 'Data submitted successfully. You can now add more data.';

			$result = array(
				'status' => 1,
				'msg' => $ajax_message,
			);

			echo json_encode($result);
			exit();
		}else{
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Sorry! Something went wrong, please try after some time'
			));
			exit();
		}
	}

	public function plot_kml(){
		$baseurl = base_url();
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array('status' => 0, 'msg' => 'Your session has expired please login and try again'));
		}

		$get_village_info = $this->db->select('*')->where('VILLAGE_ID', $this->input->post('field_1033'))->get('lkp_village')->row_array();

		
		$form_fields = $this->db->where('form_id', $this->input->post('survey_id'))->where('status', 1)->get('form_field')->result_array();

		$time = time();
		$datetime = date('Y-m-d H:i:s');

		$file = uniqid().$this->session->userdata('login_id').'.kml';
		$file_size = $_FILES['survey_images']['size'][0];

		if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
		$imgurl = UPLOAD_DIR . $file;

		$this->load->model('Compress_model');
		$filename = $this->Compress_model->compress_image_file($_FILES["survey_images"]["tmp_name"][0], $imgurl, $_FILES['survey_images']['size'][0]);

		$insert_array = array();
		$insert_array['data_id'] = $time.'-'.$this->session->userdata('login_id');
		$insert_array['plot_data_id'] = NULL;
		$insert_array['file_name'] =  $file;
		$insert_array['added_by'] =  $this->session->userdata('login_id');
		$insert_array['added_date'] = date('Y-m-d H:i:s');
		$insert_array['ip_address'] = $this->input->ip_address();
		$insert_array['farmer_status'] = 1;

		$query = $this->db->insert('tbl_agreement', $insert_array);

		if($query){
			$ajax_message = 'Data submitted successfully. You can now add more data.';

			$result = array(
				'status' => 1,
				'msg' => $ajax_message,
			);

			echo json_encode($result);
			exit();
		}else{
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Sorry! Something went wrong, please try after some time'
			));
			exit();
		}
	}

	public function get_ryotinfo($value=''){
		if($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}

		$this->db->select('data_id as id, CONCAT(IFNULL(field_1000, "NA"), " ", IFNULL(field_1001, "NA"), " - ", field_1004) as text');
		$this->db->from('tbl_farmers');
		if(isset($_POST['searchTerm'])){
			switch ($this->input->post('searchby')) {
				case 'Phone Number':
					$this->db->like('field_1005', $this->input->post('searchTerm'));
					break;

				case 'Aadhar Number':
					$this->db->like('field_1006', $this->input->post('searchTerm'));
					break;

				case 'Ryot code':
					$this->db->like('farmer_number', $this->input->post('searchTerm'));
					break;

				case 'First Name':
					$this->db->like('field_1000', $this->input->post('searchTerm'));
					break;

				case 'Last Name':
					$this->db->like('field_1001', $this->input->post('searchTerm'));
					break;
			}
		}
		$this->db->where('farmer_status', 1);
		$search_result = $this->db->get()->result_array();

		echo json_encode($search_result);
		exit();
	}
}