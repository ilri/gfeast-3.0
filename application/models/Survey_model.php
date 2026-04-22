<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function all_surveys(){

		// $this->db->distinct();
		// $this->db->select('centre_id');
		// $this->db->where('user_id', $this->session->userdata('login_id'))->where('status', 1);
		// $user_center = $this->db->get('rpt_centre_user')->result_array();

		// $user_center_array = array();
		// foreach ($user_center as $key => $centre) {
		// 	array_push($user_center_array, $centre['centre_id']);
		// }

		// if(count($user_center_array) == 0){
		// 	$user_center_array = array(0);
		// }

		// $this->db->distinct();
		// $this->db->select('lkp_partner_id');
		// $this->db->where('user_id', $this->session->userdata('login_id'));
		// $this->db->where('status', 1);
		// $center_partner = $this->db->get('rpt_centre_partner')->result_array();

		// $center_partner_array = array();
		// foreach ($center_partner as $key => $partner) {
		// 	array_push($center_partner_array, $partner['partner_id']);
		// }

		// if(count($center_partner_array) == 0){
		// 	$center_partner_array = array(0);
		// }

		$this->db->distinct();
		$this->db->select('lkp_project_id');
		$this->db->where('user_id', $this->session->userdata('login_id'));
		$this->db->where('project_user_loc_status', 1);
		$user_project = $this->db->get('rpt_project_partner_user_location')->result_array();

		$project_array = array();
		foreach ($user_project as $key => $project) {
			array_push($project_array, $project['lkp_project_id']);
		}

		if(count($project_array) == 0){
			$project_array = array(0);
		}

		$this->db->distinct();
		$this->db->select('form_id');
		$this->db->where_in('lkp_project_id', $project_array)->where('relation_status', 1);
		$form_ids = $this->db->get('rpt_form_relation')->result_array(); 

		$form_id_array = array();
		foreach ($form_ids as $key => $form) {
			array_push($form_id_array, $form['form_id']);
		}

		if(count($form_id_array) == 0){
			$form_id_array = array(0);
		}

		$this->db->select('form.id, form.title, form.description, form.type, form.pic_min, form.pic_max, form.location, form.datetime, form.dormant, form.status, concat(tbl_users.first_name, " ", tbl_users.last_name) as username,fr.lkp_project_id, proj.project_name');
        $this->db->from('form');
        $this->db->join('rpt_form_relation as fr', 'fr.form_id = form.id');
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        $this->db->join('lkp_projects as proj', 'proj.project_id = fr.lkp_project_id');
        if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2){
            $this->db->group_start();
            $this->db->or_where('fr.lkp_project_id', 1);
            $this->db->or_where('form.added_by', $this->session->userdata('login_id'));
            $this->db->or_where('form.type', 'Beneficiary');
            $this->db->group_end();
        }
        $this->db->where('fr.lkp_project_id', 1);
        $this->db->where('form.status', 1);
        return $all_survey = $this->db->get()->result_array();
	}
	

	public function all_moderation_surveys($proj_id = NULL, $user_id = NULL){
		$this->db->select('form.id, form.title, form.description, form.type, form.pic_min, form.pic_max, form.location, form.datetime, form.dormant, form.status, concat(tbl_users.first_name, " ", tbl_users.last_name) as username, fr.proj_id, proj.proj_name');
		if(is_null($user_id)) {
			if($this->session->userdata('role') >= 3) {
				$this->db->join('rpt_user_form_moderation as fr', 'fr.form_id = form.id');
			} else {
				$this->db->join('rpt_project_form_location as fr', 'fr.form_id = form.id');
			}
		} else {
			$this->db->join('rpt_user_form_moderation as fr', 'fr.form_id = form.id');
		}
		$this->db->join('lkp_projects as proj', 'proj.proj_id = fr.proj_id');
		$this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
		if(!is_null($proj_id)) $this->db->where('fr.proj_id', $proj_id);
		if(!is_null($user_id)) {
			$this->db->where('fr.user_id', $user_id);
		} else {
			if($this->session->userdata('role') >= 3)
			$this->db->where('fr.user_id', $this->session->userdata('login_id'));
		}
		$all_survey = $this->db->where('form.status', 1)->get('form')->result_array();

		foreach ($all_survey as $key => $value) {
            $this->db->select('reg_date_time');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 1);
            $this->db->order_by('reg_date_time', 'DESC');
            $last_updated = $this->db->get('ic_form_data')->row_array();

            $all_survey[$key]['last_updated'] = ($last_updated == NULL) ? 'N/A' : $last_updated['reg_date_time'];

            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 1);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            $upload_count = $this->db->get('ic_form_data')->num_rows();

            $all_survey[$key]['upload_count'] = $upload_count;
        }

        return $all_survey;
	}

	public function survey_delete($survey_id){
		$array = array(
			'status' => 0
		);

		$this->db->where('id', $survey_id);
    	$query = $this->db->update('form', $array);

    	if($query){
    		return true;
    	}else{
    		return false;
    	}
	}

	public function survey_details($survey_id){
		
		$form_details = $this->db->select('id, title, description, type, pic_min, pic_max, location, datetime, dormant, status')->where('id', $survey_id)->where('status', 1)->get('form')->row_array();
		$form_details['projects'] = array();
		$this->db->where('relation_status', 1)->where('form_id', $survey_id);
		$projects_in_survey = $this->db->get('rpt_form_relation')->result_array();
		foreach ($projects_in_survey as $key => $proj) {
			array_push($form_details['projects'], $proj['lkp_project_id']);
		}

		$this->load->model('Projects_model');
		$projects = $this->Projects_model->all_project();

		$this->db->select('*');
		$this->db->where("form_id", $survey_id)->where('status', 1);
		$survey_formfields = $this->db->get('form_field')->result_array();
		
		foreach ($survey_formfields as $key => $field) {
			// Remove ip_address and added_by
			unset($survey_formfields[$key]['added_by']);
			unset($survey_formfields[$key]['ip_address']);
			
			switch ($field['type']) {
				case 'checkbox-group':
				case 'radio-group':
				case 'select':
					$this->db->select('multi_id, label, selected, value');
					$this->db->where("field_id", $field['field_id'])->where('status', 1);
					$options = $this->db->get('form_field_multiple')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;
				
				case 'lkp_partners':
					$this->db->select('IFNULL(GROUP_CONCAT(centre_id), 0) AS centre_ids');
					$this->db->where('user_id', $this->session->userdata('login_id'));
					$this->db->where('status', 1);
					$centres = $this->db->get('rpt_centre_user')->row_array();

					$centres_list = explode(",", $centres['centre_ids']);

					$this->db->select('IFNULL(GROUP_CONCAT(partner_id), 0) AS partner_ids');
					$this->db->where_in('centre_id', $centres_list);
					$this->db->where('status', 1);
					$partner = $this->db->get('rpt_centre_partner')->row_array();

					$partner_list = explode(",", $partner['partner_ids']);

					$this->db->select('partner_id, partner_name');
					$this->db->where('status', 1);
					$this->db->where_in('partner_id', $partner_list);
					$options = $this->db->get('lkp_partners')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_centre':
					$this->db->select('IFNULL(GROUP_CONCAT(centre_id), 0) AS centre_ids');
					$this->db->where('user_id', $this->session->userdata('login_id'));
					$this->db->where('status', 1);
					$centres = $this->db->get('rpt_centre_user')->row_array();

					$centres_list = explode(",", $centres['centre_ids']);

					$this->db->select('centre_id, centre_name');
					$this->db->where('status', 1);
					$this->db->where_in('centre_id', $centres_list);
					$options = $this->db->get('lkp_centre')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_batch':
					$this->db->select('IFNULL(GROUP_CONCAT(centre_id), 0) AS centre_ids');
					$this->db->where('user_id', $this->session->userdata('login_id'));
					$this->db->where('status', 1);
					$centres = $this->db->get('rpt_centre_user')->row_array();

					$centres_list = explode(",", $centres['centre_ids']);

					$this->db->select('batch_id, batch_name');
					$this->db->where('status', 1);
					$this->db->where_in('centre_id', $centres_list);
					$options = $this->db->get('lkp_batch')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_trainee':
					$this->db->select('trainee_id, trainee_name');
					$this->db->where('status', 1);
					$options = $this->db->get('lkp_trainee')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_age':
					$this->db->select('id, age');
					$this->db->where('status', 1);
					$options = $this->db->get('lkp_age')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_state':
					$this->db->select('state_id, state_name');
					$this->db->where('status', 1);
					$options = $this->db->get('lkp_state')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_district':
					$this->db->select('state_id, state_name');
					$this->db->where('status', 1);
					$state = $this->db->get('lkp_state')->row_array();

					$this->db->select('district_id, district_name');
					$this->db->where('status', 1)->where('state_id', $state['state_id']);
					$options = $this->db->get('lkp_district')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_gender':
					$this->db->select('id, type');
					$this->db->where('status', 1);
					$options = $this->db->get('lkp_gender')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;

				case 'lkp_yesno':
					$this->db->select('id, name');
					$this->db->where('status', 1);
					$options = $this->db->get('lkp_yesno')->result_array();

					$survey_formfields[$key]['options'] = $options;
					break;
			}
		}
		
		$result = array(
			'projects' => $projects,
			'form_id' => $survey_id,
			'fields' => $survey_formfields,
			'form_details' => $form_details
		);
		return $result;
	}

	public function edit_formdetails($data){
		
		switch ($data['type']) {
			case 'save_edit_maximages':
				$array = array(
					'pic_max' => $data['images_count']
				);
				
				$this->db->where('id', $data['survey_id']);
				$query = $this->db->update('form', $array);
				break;

			case 'save_edit_title':
				$array = array(
					'title' => $data['survey_title']
				);
				
				$this->db->where('id', $data['survey_id']);
				$query = $this->db->update('form', $array);
				break;

			case 'save_edit_description':
				$array = array(
					'description' => $data['survey_description']
				);
				
				$this->db->where('id', $data['survey_id']);
				$query = $this->db->update('form', $array);
				break;

			case 'save_edit_location':
				$array = array(
					'location' => $data['survey_location']
				);
				
				$this->db->where('id', $data['survey_id']);
				$query = $this->db->update('form', $array);
				break;

			case 'form_field':
				$array = array(
					'label' => $data['field_label']
				);
				
				$this->db->where('field_id', $data['field_id'])->where('form_id', $data['survey_id']);
				$query = $this->db->update('form_field', $array);
				break;
		}		

		if($query){
			return true;
		}else{
			return false;
		}
	}

	public function get_projectid($survey_id){
		$this->db->select('lkp_project_id');
		$this->db->where('form_id', $survey_id)->where('relation_status', 1);
		$project_id = $this->db->get('rpt_form_relation')->row_array();

		return $project_id['lkp_project_id'];
	}

	public function get_beneficiary_details(){
		$this->db->select('data_id, form_data');
		$this->db->where('form_id', 1)->where('data_status', 1);
		$details = $this->db->get('ic_form_data')->result_array();

		$beneficiary_list = array();

		foreach ($details as $key => $value) {
			$data = json_decode($value['form_data'], true);

			$beneficiary_list[$key]['id'] = $value['data_id'];
			$beneficiary_list[$key]['name'] = $data['field_1005']." ".$data['field_1006'];			
		}

		return $beneficiary_list;
	}

	public function get_beneficiary_country($beneficiary_id){
		$this->db->select('data_id, form_data');
		$this->db->where('data_status', 1)->where('data_id', $beneficiary_id);
		$details = $this->db->get('ic_form_data')->row_array();

		$data = json_decode($details['form_data'], true);

		$centre_id = $data['field_1002'];

		$this->db->distinct();
		$this->db->select('country');
		$this->db->where('status', 1)->where('centre_id', $centre_id);
		$country = $this->db->get('rpt_centre_location')->result_array();

		$country_array = array();

		foreach ($country as $key => $value) {
			array_push($country_array, $value['country']);
		}

		if(count($country_array) == 0){
			$country_array = array(0);
		}

		$this->db->select('country_id, name');
		$this->db->where('status', 1);
		$this->db->where_in('country_id', $country_array);
		return $country_details = $this->db->get('lkp_country')->result_array();
	}

	public function get_beneficiary_state($data){
		$this->db->select('data_id, form_data');
		$this->db->where('data_status', 1)->where('data_id', $data['beneficiary_id']);
		$details = $this->db->get('ic_form_data')->row_array();

		$data_array = json_decode($details['form_data'], true);

		$centre_id = $data_array['field_1002'];

		$this->db->distinct();
		$this->db->select('state');
		$this->db->where('status', 1)->where('country', $data['country_id'])->where('centre_id', $centre_id);
		$state = $this->db->get('rpt_centre_location')->result_array();

		$state_array = array();

		foreach ($state as $key => $value) {
			array_push($state_array, $value['state']);
		}

		if(count($state_array) == 0){
			$state_array = array(0);
		}

		$this->db->select('state_id, state_name');
		$this->db->where('status', 1);
		$this->db->where_in('state_id', $state_array)->where('country_id', $data['country_id']);
		return $state_details = $this->db->get('lkp_state')->result_array();
	}

	public function get_beneficiary_district($data){
		$this->db->select('data_id, form_data');
		$this->db->where('data_status', 1)->where('data_id', $data['beneficiary_id']);
		$details = $this->db->get('ic_form_data')->row_array();

		$data_array = json_decode($details['form_data'], true);

		$centre_id = $data_array['field_1002'];

		$this->db->distinct();
		$this->db->select('dist');
		$this->db->where('status', 1)->where('country', $data['country_id'])->where('state', $data['state_id'])->where('centre_id', $centre_id);
		$dist = $this->db->get('rpt_centre_location')->result_array();

		$dist_array = array();

		foreach ($dist as $key => $value) {
			array_push($dist_array, $value['dist']);
		}

		if(count($dist_array) == 0){
			$dist_array = array(0);
		}

		$this->db->select('district_id, district_name');
		$this->db->where('status', 1);
		$this->db->where_in('district_id', $dist_array)->where('country_id', $data['country_id'])->where('state_id', $data['state_id']);
		return $dist_details = $this->db->get('lkp_district')->result_array();
	}

	public function get_district($data){
		$this->db->select('district_id, district_name');
		$this->db->where('status', 1);
		$this->db->where('state_id', $data['state_id']);
		return $dist_details = $this->db->get('lkp_district')->result_array();
	}

	public function add_survey_project($data)
	{
		$query = $this->db->insert('rpt_form_relation', $data);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
	public function edit_survey_project($data)
	{
		$query = $this->db->where($data['where'])->update('rpt_form_relation', $data['set']);
		if($query) {
			return true;
		} else {
			return false;
		}
	}
}