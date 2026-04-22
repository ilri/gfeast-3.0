<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

    /*public function all_surveys(){
        $this->db->select('form.*, concat(first_name, " ", last_name) as username');
        $this->db->from('form');
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        $this->db->where('form.status', 1)->where('type', 'Survey');
        $all_survey = $this->db->get()->result_array();

        return $all_survey;
    }*/

    public function all_surveys_1(){

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
            $this->db->group_end();            
        }
        //$this->db->where('form.type', 'Survey');
        $this->db->group_start();
        $this->db->where('form.type', 'Survey');
        $this->db->or_where('form.type', 'Activity');
        $this->db->group_end();
        $this->db->where('fr.lkp_project_id', 1);
        $this->db->where('form.status', 1);
        return $all_survey = $this->db->get()->result_array();
    }    

    public function all_surveys($proj_id = NULL){
        $project_array = array($proj_id);
        if(is_null($proj_id)) {
            $this->db->distinct();
            $this->db->select('proj_id');
            $this->db->where('user_id', $this->session->userdata('login_id'));
            $this->db->where('status', 1);
            $user_project = $this->db->get('rpt_user_form_location')->result_array();

            $project_array = array();
            foreach ($user_project as $key => $project) {
                array_push($project_array, $project['proj_id']);
            }

            if(count($project_array) == 0){
                $project_array = array(0);
            }
        }

        $this->db->select('form.id, form.title, form.description, form.type, form.pic_min, form.pic_max, form.location, form.datetime, form.dormant, form.status, concat(tbl_users.first_name, " ", tbl_users.last_name) as username,fr.proj_id, proj.proj_name');
        $this->db->from('form');
        if($this->session->userdata('role') >= 3) {
            $this->db->join('rpt_user_form_location as fr', 'fr.form_id = form.id');
        } else {
            $this->db->join('rpt_project_form_location as fr', 'fr.form_id = form.id');
        }
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        $this->db->join('lkp_projects as proj', 'proj.proj_id = fr.proj_id');
        if($this->session->userdata('role') >= 3) {
            $this->db->where('fr.user_id', $this->session->userdata('login_id'));
        }
        $this->db->where_in('fr.proj_id', $project_array);
        // if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2){
        //     $this->db->group_start();
        //     $this->db->where_in('fr.proj_id', $project_array);
        //     $this->db->or_where('form.added_by', $this->session->userdata('login_id'));
        //     $this->db->group_end();
        //     //$this->db->where('fr.added_by', $this->session->userdata('login_id'));            
        // }
        $this->db->where('form.type', 'Survey');        
        $this->db->where('form.status', 1)->where('form.id !=', 1);
        $all_survey = $this->db->get()->result_array();

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
            $submitted_count = $this->db->get('ic_form_data')->num_rows();

            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 2);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            $saved_count = $this->db->get('ic_form_data')->num_rows();

            $all_survey[$key]['submitted_count'] = $submitted_count;
            $all_survey[$key]['saved_count'] = $saved_count;
            $all_survey[$key]['upload_count'] = intval($submitted_count) + intval($saved_count);
        }

        return $all_survey;
    }
    

    public function all_registration($proj_id = NULL){
        $project_array = array($proj_id);
        if(is_null($proj_id)) {
            $this->db->distinct();
            $this->db->select('project_id');
            $this->db->where('user_id', $this->session->userdata('login_id'));
            $this->db->where('status', 1);
            $user_project = $this->db->get('tbl_user_projects_sites')->result_array();

            $project_array = array();
            foreach ($user_project as $key => $project) {
                array_push($project_array, $project['project_id']);
            }

            if(count($project_array) == 0){
                $project_array = array(0);
            }
        }

        $this->db->select('form.id, form.title, form.description, form.type, form.pic_min, form.pic_max, form.location, form.datetime, form.dormant, form.status, concat(tbl_users.first_name, " ", tbl_users.last_name) as username');
        $this->db->from('form');
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        
        $this->db->where('form.type', 'Registration');
        $this->db->where('form.status', 1);
        $all_registration = $this->db->get()->result_array();

		$user_id = $this->session->userdata('login_id');
		$role_id = $this->session->userdata('role');

        $this->db->distinct()->select('GROUP_CONCAT(country_id) as countries');
        $this->db->where('status', 1);
        $getCountryList = $this->db->get('lkp_country')->row_array();
        $countryIds = explode(",", $getCountryList['countries']);
        
        $this->db->select('user_id')
                ->from('tbl_users')
                ->where('role_id', 6)
                ->where('user_id !=', $user_id);
        $adminUsersResult = $this->db->get()->result_array();
        $adminUsers = array_column($adminUsersResult, 'user_id');

        $this->db->distinct()->select('GROUP_CONCAT(sites.project_id) as projects');
        $this->db->join('lkp_country_projects as projects', 'sites.project_id = projects.id');
        $this->db->where_in('sites.country_id', $countryIds);
        $this->db->where('sites.status', 1)->where('projects.status', 1);
        if ($role_id == 6) {
            $this->db->group_start()
                    ->where('projects.project_type', 'Public')
                    ->or_where('projects.user_id', $user_id)
                    ->group_end();
			if (!empty($adminUsers)) {
				$this->db->where_not_in('projects.user_id', $adminUsers);
			}
        } else {
            $this->db->where('projects.project_type', 'Public');
        }
        $projectsList = $this->db->get('lkp_project_site as sites')->row_array();

        $projectIds = explode(",", $projectsList['projects']);

        foreach ($all_registration as $key => $value) {
            $submitted_count = 0;

            // Check if title contains 'FGD' or 'IFI'
            if (strpos($value['title'], 'FGD') !== false) {
                // For survey1 table
                $this->db->from('survey1');
                if ($role_id == 8) {
                    $this->db->where('user_id', $user_id);
                }
                if ($role_id == 6) {
                    $this->db->where_in('project_id', $projectIds);
                }
                $submitted_count = $this->db->where_not_in('status', [0, 4])->count_all_results();
            } else if (strpos($value['title'], 'IFI') !== false) {
                // For survey4 table
                $this->db->from('survey4');
                if ($role_id == 8) {
                    $this->db->where('user_id', $user_id);
                }
                if ($role_id == 6) {
                    $this->db->where_in('fgd_project_id', $projectIds);
                }
                $submitted_count = $this->db->where_not_in('status', [0, 4])->count_all_results();
            }
            
            $all_registration[$key]['submitted_count'] = $submitted_count;
        }

        return $all_registration;
    }

    public function all_activity($proj_id = NULL){
        $project_array = array($proj_id);
        if(is_null($proj_id)) {
            $this->db->distinct();
            $this->db->select('proj_id');
            $this->db->where('user_id', $this->session->userdata('login_id'));
            $this->db->where('status', 1);
            $user_project = $this->db->get('rpt_user_form_location')->result_array();

            $project_array = array();
            foreach ($user_project as $key => $project) {
                array_push($project_array, $project['proj_id']);
            }

            if(count($project_array) == 0){
                $project_array = array(0);
            }
        }

        $this->db->select('form.id, form.title, form.description, form.type, form.pic_min, form.pic_max, form.location, form.datetime, form.dormant, form.status, concat(tbl_users.first_name, " ", tbl_users.last_name) as username,fr.proj_id, proj.proj_name');
        $this->db->from('form');
        if($this->session->userdata('role') >= 3) {
            $this->db->join('rpt_user_form_location as fr', 'fr.form_id = form.id');
        } else {
            $this->db->join('rpt_project_form_location as fr', 'fr.form_id = form.id');
        }
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        $this->db->join('lkp_projects as proj', 'proj.proj_id = fr.proj_id');
        if($this->session->userdata('role') >= 3) {
            $this->db->where('fr.user_id', $this->session->userdata('login_id'));
        }
        $this->db->where_in('fr.proj_id', $project_array);
        // if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2){
        //     $this->db->group_start();
        //     $this->db->where_in('fr.proj_id', $project_array);
        //     $this->db->or_where('form.added_by', $this->session->userdata('login_id'));
        //     $this->db->group_end();
        //     //$this->db->where('fr.added_by', $this->session->userdata('login_id'));            
        // }
        $user=$this->session->userdata;
        $this->db->where('form.type', 'Activity');
        $this->db->where('form.status', 1)->where('form.id !=', 1);
        // $this->db->order_by('form.sl_no');
        $all_activity = $this->db->get()->result_array();
        $user_id=$this->session->userdata['login_id'];
        foreach ($all_activity as $key => $value) {
            $this->db->select('reg_date_time');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 1);
            $this->db->order_by('reg_date_time', 'DESC');
            $last_updated = $this->db->get('ic_form_data')->row_array();

            $all_activity[$key]['last_updated'] = ($last_updated == NULL) ? 'N/A' : $last_updated['reg_date_time'];

            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 1);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            if($user['role']==4)
            {
                $submitted_count = $this->db->where('user_id', $user_id)->get('ic_form_data')->num_rows();
            }
            else
            {
                $submitted_count = $this->db->get('ic_form_data')->num_rows();
            }
            

            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 2);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            if($user['role']==3)
            {
                $saved_count = $this->db->where('user_id', $user_id)->get('ic_form_data')->num_rows();
            }
            else
            {
                $saved_count = $this->db->get('ic_form_data')->num_rows();
            }
            // added by sagar for nothning to report column dispaly
            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 0)->where('nothingto_report', 1);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            if($user['role']==3)
            {
                $nothingto_report_count = $this->db->where('user_id', $user_id)->get('ic_form_data')->num_rows();
            }
            else
            {
                $nothingto_report_count = $this->db->get('ic_form_data')->num_rows();
            }
            

            $all_activity[$key]['submitted_count'] = $submitted_count;
            $all_activity[$key]['saved_count'] = $saved_count;
            $all_activity[$key]['nothingto_report_count'] = $nothingto_report_count;
            $all_activity[$key]['upload_count'] = intval($submitted_count) + intval($saved_count);
        }

        return $all_activity;
    }

    public function all_visits($proj_id = NULL){
        $project_array = array($proj_id);
        if(is_null($proj_id)) {
            $this->db->distinct();
            $this->db->select('proj_id');
            $this->db->where('user_id', $this->session->userdata('login_id'));
            $this->db->where('status', 1);
            $user_project = $this->db->get('rpt_user_form_location')->result_array();

            $project_array = array();
            foreach ($user_project as $key => $project) {
                array_push($project_array, $project['proj_id']);
            }

            if(count($project_array) == 0){
                $project_array = array(0);
            }
        }

        $this->db->select('form.id, form.title, form.description, form.type, form.pic_min, form.pic_max, form.location, form.datetime, form.dormant, form.status, concat(tbl_users.first_name, " ", tbl_users.last_name) as username,fr.proj_id, proj.proj_name');
        $this->db->from('form');
        if($this->session->userdata('role') >= 3) {
            $this->db->join('rpt_user_form_location as fr', 'fr.form_id = form.id');
        } else {
            $this->db->join('rpt_project_form_location as fr', 'fr.form_id = form.id');
        }
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        $this->db->join('lkp_projects as proj', 'proj.proj_id = fr.proj_id');
        if($this->session->userdata('role') >= 3) {
            $this->db->where('fr.user_id', $this->session->userdata('login_id'));
        }
        $this->db->where_in('fr.proj_id', $project_array);
        // if($this->session->userdata('role') != 1 && $this->session->userdata('role') != 2){
        //     $this->db->group_start();
        //     $this->db->where_in('fr.proj_id', $project_array);
        //     $this->db->or_where('form.added_by', $this->session->userdata('login_id'));
        //     $this->db->group_end();
        //     //$this->db->where('fr.added_by', $this->session->userdata('login_id'));            
        // }
        $this->db->where('form.type', 'Visit');
        $this->db->where('form.status', 1)->where('form.id !=', 1);
        $all_visits = $this->db->get()->result_array();

        foreach ($all_visits as $key => $value) {
            $this->db->select('reg_date_time');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 1);
            $this->db->order_by('reg_date_time', 'DESC');
            $last_updated = $this->db->get('ic_form_data')->row_array();

            $all_visits[$key]['last_updated'] = ($last_updated == NULL) ? 'N/A' : $last_updated['reg_date_time'];

            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 1);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            $submitted_count = $this->db->get('ic_form_data')->num_rows();

            $this->db->select('data_id');
            $this->db->where('form_id', $value['id'])->where('project_id', $value['proj_id'])->where('data_status', 2);
            if($this->session->userdata('role') > 3) {
                $this->db->where('user_id', $this->session->userdata('login_id'));
            }
            $saved_count = $this->db->get('ic_form_data')->num_rows();

            $all_visits[$key]['submitted_count'] = $submitted_count;
            $all_visits[$key]['saved_count'] = $saved_count;
            $all_visits[$key]['upload_count'] = intval($submitted_count) + intval($saved_count);
        }

        return $all_visits;
    }

    public function all_beneficiary(){
        $this->db->select('form.*, concat(first_name, " ", last_name) as username');
        $this->db->from('form');
        $this->db->join('tbl_users', 'tbl_users.user_id = form.added_by');
        $this->db->where('form.status', 1)->where('type', 'Beneficiary');
        //$this->db->where('form.added_by', $this->session->userdata('login_id'));
        $all_beneficiary = $this->db->get()->result_array();

        return $all_beneficiary;
    }

    public function date_wise_data($survey_id){
        $districts = $this->district_list();
        $startDate = '2020-06-03';

        // Declare an empty array for total upload
        $totalUploads = array();
          
        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');
      
        $endDate = new DateTime();
      
        $period = new DatePeriod(new DateTime($startDate), $interval, $endDate);
      
        // Use loop to store date into array
        foreach($period as $date) {
            $today = $date->format('Y-m-d');

            $this->db->select('ifd.id');
            $this->db->where('ifd.form_id', $survey_id);
            $this->db->where('DATE(ifd.reg_date_time) >=', $today.' 00:00');
            $this->db->where('DATE(ifd.reg_date_time) <=', $today.' 23:59');
            $total = $this->db->where('ifd.data_status', 1)->get('ic_form_data AS ifd')->num_rows();

            //Create current date upload array
            $upload = array(
                'date' => $today,
                'upload' => $total
            );

            //Get district wise record
            foreach($districts as $dist) {
                $this->db->select('ifd.id');
                $this->db->where('ifd.form_id', $survey_id);
                $this->db->where('ifd.district_id', $dist['district_id']);
                $this->db->where('DATE(ifd.reg_date_time) >=', $today.' 00:00');
                $this->db->where('DATE(ifd.reg_date_time) <=', $today.' 23:59');
                $total = $this->db->where('ifd.data_status', 1)->get('ic_form_data AS ifd')->num_rows();

                $upload['upload'.$dist['district_id']] = $total;
            }

            //Push to totalUploads Array
            array_push($totalUploads, $upload);
        }

        return array(
            'uploads' => $totalUploads,
            'districts' => $districts
        );
    }
    public function registration_data($survey_id)
    {
        $survey=$this->survey_details($survey_id);
        $fields=$survey['fields'];
        // echo '<pre>';print_r($fields);exit;
        $this->db->select('survey1.*, lkp_state.state_name as field_501, lkp_district.district_name as field_502, lkp_tehsil.tehsil_name as field_643, lkp_block.block_name as field_503, lkp_grampanchayat.grampanchayat_name as field_644, lkp_village.village_name as field_504, CONCAT(tbl_users.first_name," ", tbl_users.last_name) as user_id');
        $this->db->join('lkp_state', 'lkp_state.state_id=survey1.field_501');
        $this->db->join('lkp_district', 'lkp_district.district_id=survey1.field_502');
        $this->db->join('lkp_tehsil', 'lkp_tehsil.tehsil_id=survey1.field_643');
        $this->db->join('lkp_block', 'lkp_block.block_id=survey1.field_503');
        $this->db->join('lkp_grampanchayat','lkp_grampanchayat.grampanchayat_id=survey1.field_644');
        $this->db->join('lkp_village','lkp_village.village_id=survey1.field_504');
        $this->db->join('tbl_users','tbl_users.user_id=survey1.user_id');
        $data= $this->db->get('survey1')->result_array();
        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', 1);
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();
            
            // $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
            // $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            // $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
        }
        return $data;

    }
    public function registration_data2($survey_id)
    {
        $survey=$this->survey_details($survey_id);
        $fields=$survey['fields'];
        // echo '<pre>';print_r($fields);exit;
        $this->db->select('survey2.*, lkp_state.state_name as field_528, lkp_district.district_name as field_529, lkp_tehsil.tehsil_name as field_530, lkp_block.block_name as field_531, lkp_grampanchayat.grampanchayat_name as field_533, lkp_village.village_name as field_534 , CONCAT(tbl_users.first_name," ", tbl_users.last_name) as user_id');
        $this->db->join('lkp_state', 'lkp_state.state_id=survey2.field_528');
        $this->db->join('lkp_district', 'lkp_district.district_id=survey2.field_529');
        $this->db->join('lkp_tehsil', 'lkp_tehsil.tehsil_id=survey2.field_530');
        $this->db->join('lkp_block', 'lkp_block.block_id=survey2.field_531');
        $this->db->join('lkp_grampanchayat','lkp_grampanchayat.grampanchayat_id=survey2.field_533');
        $this->db->join('lkp_village','lkp_village.village_id=survey2.field_534');
        $this->db->join('tbl_users','tbl_users.user_id=survey2.user_id');
        $data= $this->db->get('survey2')->result_array();
        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', 2);
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();
            
            // $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
            // $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            // $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
        }
        return $data;

    }
    public function registration_data3($survey_id)
    {
        $survey=$this->survey_details($survey_id);
        $fields=$survey['fields'];
        // echo '<pre>';print_r($fields);exit;
        $this->db->select('survey3.*, lkp_state.state_name as field_585, lkp_district.district_name as field_586, lkp_tehsil.tehsil_name as field_587, lkp_block.block_name as field_588, lkp_grampanchayat.grampanchayat_name as field_591, lkp_village.village_name as field_592 , CONCAT(tbl_users.first_name," ", tbl_users.last_name) as user_id');
        $this->db->join('lkp_state', 'lkp_state.state_id=survey3.field_585');
        $this->db->join('lkp_district', 'lkp_district.district_id=survey3.field_586');
        $this->db->join('lkp_tehsil', 'lkp_tehsil.tehsil_id=survey3.field_587');
        $this->db->join('lkp_block', 'lkp_block.block_id=survey3.field_588');
        $this->db->join('lkp_grampanchayat','lkp_grampanchayat.grampanchayat_id=survey3.field_591');
        $this->db->join('lkp_village','lkp_village.village_id=survey3.field_592');
        $this->db->join('tbl_users','tbl_users.user_id=survey3.user_id');
        $data= $this->db->get('survey3')->result_array();
        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', 3);
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();
            
            // $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
            // $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            // $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
        }
        return $data;

    }
    // public function registration_data(){
    //     $this->db->select('tblf.*, concat(tu.first_name, " ", tu.last_name) as username');
    //     $this->db->join('tbl_users AS tu', 'tu.user_id = tblf.added_by');
    //     $this->db->where('tblf.farmer_status', 1);
    //     if(isset($_POST['start_date']) && isset($_POST['end_date'])){
    //         $this->db->where('DATE(tblf.added_date) >=', $_POST['start_date']);
    //         $this->db->where('DATE(tblf.added_date) <=', $_POST['end_date']);
    //     }
    //     if(isset($_POST['division']) && !is_null($_POST['division'])){
    //         if(isset($_POST['division'])) $division = $this->input->post('division');
    //         $this->db->where_in('tblf.division_id', $division);
    //     }
    //     if(isset($_POST['circle']) && !is_null($_POST['circle'])){
    //         if(isset($_POST['circle'])) $circle = $this->input->post('circle');
    //         $this->db->where_in('tblf.circle_id', $circle);
    //     }
    //     if(isset($_POST['village']) && !is_null($_POST['village'])){
    //         if(isset($_POST['village'])) $village = $this->input->post('village');
    //         $this->db->where_in('tblf.village_id', $village);
    //     }
    //     if(isset($_POST['last_id'])){
    //         $this->db->where('tblf.farmer_id <', $_POST['last_id']);
    //         $this->db->limit(20);
    //     } else {
    //         $this->db->limit(50);
    //     }
    //     $data = $this->db->order_by('tblf.farmer_id', 'DESC')->get('tbl_farmers AS tblf')->result_array();
    //     // echo $this->db->last_query();exit;

    //     foreach ($data as $key => $value) {
    //         $this->db->select('file_name');
    //         $this->db->where('data_id', $value['data_id'])->where('status', 1);
    //         $this->db->where('form_id', 1);
    //         $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();
            
    //         $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
    //         $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
    //         $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
    //     }

    //     return $data;
    // }

    public function plot_data(){
        $this->db->select('tblp.*, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('tbl_users AS tu', 'tu.user_id = tblp.added_by');
        $this->db->where('tblp.plot_status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(tblp.added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(tblp.added_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('tblp.division_id', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('tblp.circle_id', $circle);
        }
        if(isset($_POST['village']) && !is_null($_POST['village'])){
            if(isset($_POST['village'])) $village = $this->input->post('village');
            $this->db->where_in('tblp.village_id', $village);
        }
        if(isset($_POST['last_id'])){
            $this->db->where('tblp.plot_id <', $_POST['last_id']);
        }
        if(isset($_POST['last_id'])){
            $this->db->where('tblp.plot_id <', $_POST['last_id']);
            $this->db->limit(20);
        } else {
            $this->db->limit(50);
        }
        $data = $this->db->order_by('tblp.plot_id', 'DESC')->get('tbl_plot AS tblp')->result_array();

        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', 2);
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();

            $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
        }

        return $data;
    }

    public function agreement_data(){
        $this->db->select('tbla.*, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('tbl_users AS tu', 'tu.user_id = tbla.added_by');
        $this->db->join('tbl_plot AS tp', 'tp.data_id = tbla.plot_data_id');
        $this->db->where('tbla.agreement_status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(tbla.added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(tbla.added_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('tp.division_id', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('tp.circle_id', $circle);
        }
        if(isset($_POST['village']) && !is_null($_POST['village'])){
            if(isset($_POST['village'])) $village = $this->input->post('village');
            $this->db->where_in('tp.village_id', $village);
        }
        if(isset($_POST['last_id'])){
            $this->db->where('tbla.agreement_id <', $_POST['last_id']);
            $this->db->limit(20);
        } else {
            $this->db->limit(50);
        }
        $data = $this->db->order_by('tbla.agreement_id', 'DESC')->get('tbl_agreement AS tbla')->result_array();

        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['agreement_data_id'])->where('status', 1);
            $this->db->where('form_id', 3);
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();

            $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
        }

        return $data;
    }

    public function survey_data($survey_id, $district_id = NULL){
        $this->db->select('ifd.*, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('tbl_users AS tu', 'tu.user_id = ifd.user_id');
        $this->db->where('ifd.form_id', $survey_id)->where('ifd.data_status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(ifd.reg_date_time) >=', $_POST['start_date']);
            $this->db->where('DATE(ifd.reg_date_time) <=', $_POST['end_date']);
        }
        if(isset($_POST['district']) || !is_null($district_id)){
            if(isset($_POST['district'])) $district_id = $this->input->post('district');
            $this->db->where_in('ifd.district_id', $district_id);
        }
        if(isset($_POST['user_ids'])){
            $this->db->where_in('ifd.user_id', $_POST['user_ids']);
        }
        if(isset($_POST['last_id'])){
            $this->db->where('ifd.id <', $_POST['last_id']);
        }
        $data = $this->db->limit(100)->order_by('ifd.id', 'DESC')->get('ic_form_data AS ifd')->result_array();

        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', $survey_id);
            if($survey_id == 22){
                $this->db->limit(1);
            }
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();

            $this->db->select('*');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', $survey_id);
            $data[$key]['location'] = $this->db->get('ic_data_location')->row_array();

            $this->db->distinct()->select('lp.partner_name');
            $this->db->join('rpt_project_partner_user_location AS rppul', 'rppul.lkp_partner_id = lp.partner_id');
            $this->db->where('rppul.user_id', $value['user_id'])->where('rppul.project_user_loc_status', 1);
            $partner = $this->db->where_in('rppul.lkp_project_id', 1)->get('lkp_partners AS lp')->row_array();
            $data[$key]['partner_name'] = $partner['partner_name'];

            $date = new DateTime($value['reg_date_time'], new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $data[$key]['reg_date_time'] = $date->format('Y-m-d H:i:s');
        }

        return $data;
    }

    public function activity_data($survey_id, $village_id){
        $this->db->select('ifd.*, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('tbl_users AS tu', 'tu.user_id = ifd.user_id');
        $this->db->where('ifd.form_id', $survey_id)->where('ifd.data_status', 1);
        $this->db->where('ifd.village_id', $village_id);
        if(isset($_POST['last_id'])){
            $this->db->where('ifd.id <', $_POST['last_id']);
        }
        $data = $this->db->limit(100)->order_by('ifd.id', 'DESC')->get('ic_form_data AS ifd')->result_array();

        foreach ($data as $key => $value) {
            $this->db->select('file_name');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', $survey_id);
            if($survey_id == 22){
                $this->db->limit(1);
            }
            $data[$key]['images'] = $this->db->get('ic_data_file')->result_array();

            $this->db->select('*');
            $this->db->where('data_id', $value['data_id'])->where('status', 1);
            $this->db->where('form_id', $survey_id);
            $data[$key]['location'] = $this->db->get('ic_data_location')->row_array();

            $this->db->distinct()->select('lp.partner_name');
            $this->db->join('rpt_project_partner_user_location AS rppul', 'rppul.lkp_partner_id = lp.partner_id');
            $this->db->where('rppul.user_id', $value['user_id'])->where('rppul.project_user_loc_status', 1);
            $partner = $this->db->where_in('rppul.lkp_project_id', 1)->get('lkp_partners AS lp')->row_array();
            $data[$key]['partner_name'] = $partner['partner_name'];

            $date = new DateTime($value['reg_date_time'], new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $data[$key]['reg_date_time'] = $date->format('Y-m-d H:i:s');
        }

        return $data;
    }

    public function district_list_with_survey_data($survey_id){
        date_default_timezone_set("Asia/Kolkata");
        $districts = $this->district_list();
        $startDate = date('Y-m-d').' 00:00';
        $endDate = date('Y-m-d').' 23:59';

        foreach ($districts as $key => $district) {
            $this->db->select('ifd.id');
            $this->db->where('ifd.district_id', $district['district_id']);
            $this->db->where('ifd.form_id', $survey_id)->where('ifd.data_status', 1);
            $districts[$key]['total'] = $this->db->get('ic_form_data AS ifd')->num_rows();

            $this->db->select('ifd.id')->from('ic_form_data AS ifd');
            $this->db->where('ifd.form_id', $survey_id)->where('ifd.data_status', 1);
            $this->db->where('ifd.district_id', $district['district_id']);
            $this->db->where('DATE(ifd.reg_date_time) >=', $startDate);
            $this->db->where('DATE(ifd.reg_date_time) <=', $endDate);
            $districts[$key]['total_today'] = $this->db->get()->num_rows();

            switch ($district['district_name']) {
                case 'Khordha':
                $districts[$key]['icon'] = 'pin1.png';
                break;

                case 'Puri':
                $districts[$key]['icon'] = 'pin2.png';
                break;

                case 'Koraput':
                $districts[$key]['icon'] = 'pin3.png';
                break;

                case 'Nabarangpur':
                $districts[$key]['icon'] = 'pin4.png';
                break;

                case 'Gajapati':
                $districts[$key]['icon'] = 'pin5.png';
                break;

                case 'Rayagada':
                $districts[$key]['icon'] = 'pin6.png';
                break;
            }
        }
        
        return $districts;
    }

    public function village_list_with_survey_data($survey_id, $district_id){
        $villages = $this->village_list();

        $this->db->select('ifd.id, ifd.form_data');
        $this->db->where('ifd.district_id', $district_id);
        $this->db->where('ifd.form_id', $survey_id)->where('ifd.data_status', 1);
        $district_total = $this->db->get('ic_form_data AS ifd')->result_array();

        //Get village field id
        $this->db->where('type', 'lkp_village')->where('form_id', $survey_id);
        $field = $this->db->where('status', 1)->get('form_field')->row_array();
        $village_field = 'field_'.$field['field_id'];

        foreach ($villages as $key => $village) {
            $totalUpload = 0;
            foreach ($district_total as $value) {
                $form_data = (array)json_decode($value['form_data']);
                if(isset($form_data[$village_field]) && $form_data[$village_field] == $village['village_id']) $totalUpload++;
            }
            $villages[$key]['total'] = $totalUpload;
        }
        
        return $villages;
    }

    public function survey_details($survey_id){
        
        $form_details = $this->db->where('id', $survey_id)->where('status', 1)->get('form')->row_array();

        $this->db->where('type', 'group')->where('form_id', $survey_id)->where('status', 1);
        $check_group_field = $this->db->get('form_field')->num_rows();

        if($check_group_field > 0){
            $this->db->select('GROUP_CONCAT(field_id) as field_ids');
            $this->db->where('type', 'group')->where('form_id', $survey_id)->where('status', 1);
            $form_group_id = $this->db->get('form_field')->row_array();

            $form_group_id_array = explode(",", $form_group_id['field_ids']);

            $form_group_id_array_list = array();
            foreach ($form_group_id_array as $key => $value) {
                $this->db->select('child_id');
                $this->db->where('form_id', $survey_id)->where('field_id', $value);
                $this->db->where('status', 1);
                $group_childid = $this->db->get('form_field')->row_array();

                $child_id_array = explode(",", $group_childid['child_id']);

                foreach ($child_id_array as $key => $child) {
                    array_push($form_group_id_array_list, $child);
                }
            }
        }else{
            $form_group_id_array_list = array(0);
        }

        $this->db->select('*');
        $this->db->where("form_id", $survey_id)->where('status', 1);
        $this->db->where_not_in('field_id', $form_group_id_array_list);
        /*$this->db->where('type !=', 'group')->where('type !=', 'header');*/
        $this->db->where('type !=', 'header')->where('type !=', 'tab');
        $survey_formfields = $this->db->order_by('slno')->get('form_field')->result_array();
        
        foreach ($survey_formfields as &$formfield) {
            $field_id = $formfield['field_id'];
        
            // Fetch 'label' values from 'form_field_multiple' for the current 'field_id'
            $labels = $this->db->select('label')->where('field_id', $field_id)->get('form_field_multiple')->result_array();
        
            // Create a new key in the current element and assign the 'label' values
            $formfield['multi_labels'] = array_column($labels, 'label');
        }
        
        $result = array('fields' => $survey_formfields, 'form_details' => $form_details, 'form_id'=>$survey_id, 'check_group_field' => $check_group_field);

        return $result;
    }

    public function field_details($field_id){
        $this->db->select('*');
        $this->db->where('field_id', $field_id);
        $data = $this->db->where('status', 1)->get('form_field')->row_array();

        switch ($data['type']) {
            case 'checkbox-group':
            case 'radio-group':
            case 'select':
                $this->db->select('multi_id, label, value');
                $this->db->where("field_id", $data['field_id'])->where('status', 1);
                $options = $this->db->get('form_field_multiple')->result_array();

                $data['options'] = $options;
                break;
            
            case 'lkp_gender':
                $this->db->select('id, type');
                $this->db->where('status', 1);
                $options = $this->db->get('lkp_gender')->result_array();

                $data['options'] = $options;
                break;

            case 'lkp_district':
            case 'lkp_block':
            case 'lkp_village':
                $this->db->select('district_id, district_name');
                $this->db->where('status', 1);
                $districts = $this->db->get('lkp_district')->result_array();

                $this->db->select('block_id, block_name');
                $this->db->where('block_status', 1);
                $blocks = $this->db->get('lkp_block')->result_array();
                
                $this->db->select('village_id, village_name');
                $this->db->where('village_status', 1);
                $villages = $this->db->get('lkp_village')->result_array();

                $data['districts'] = $districts;
                $data['blocks'] = $blocks;
                $data['villages'] = $villages;
                break;
        }

        return $data;
    }

    public function survey_data_details($id){
        $this->db->select('*');
        $this->db->where('id', $id)->where('data_status', 1);
        $data = $this->db->get('ic_form_data')->row_array();

        return $data;
    }

    public function partners_list(){
        $this->db->select('partner_id, partner_name');
        $this->db->where('status', 1);
        return $partners = $this->db->get('lkp_partners')->result_array();
    }

    public function centre_list(){
        $this->db->select('centre_id, centre_name');
        $this->db->where('status', 1);
        return $centre = $this->db->get('lkp_centre')->result_array();
    }

    public function batch_list(){
        $this->db->select('batch_id, batch_name');
        $this->db->where('status', 1);
        return $batch = $this->db->get('lkp_batch')->result_array();
    }

    public function trainee_list(){
        $this->db->select('trainee_id, trainee_name');
        $this->db->where('status', 1);
        return $trainee = $this->db->get('lkp_trainee')->result_array();
    }

    public function age_list(){
        $this->db->select('id, age');
        $this->db->where('status', 1);
        return $age = $this->db->get('lkp_age')->result_array();
    }

    public function state_list(){
        $this->db->select('state_id, state_name');
        $this->db->where('status', 1);
        return $state = $this->db->get('lkp_state')->result_array();
    }

    public function district_list($district_id = NULL){
        $this->db->select('district_id, district_name');
        $this->db->where('status', 1);
        return $state = $this->db->get('lkp_district')->result_array();
    }

    public function block_list(){
        $this->db->select('block_id, block_name');
        $this->db->where('block_status', 1);
        return $block = $this->db->get('lkp_block')->result_array();
    }

    public function village_list(){
        $this->db->select('village_id, village_name');
        $this->db->where('village_status', 1);
        return $village = $this->db->get('lkp_village')->result_array();
    }

    public function user_list($district_id = NULL){
        $this->db->distinct()->select('tu.user_id, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('rpt_project_partner_user_location AS rppul', 'rppul.user_id = tu.user_id');
        $this->db->where('rppul.lkp_project_id', 1)->where('rppul.project_user_loc_status', 1);
        if(!is_null($district_id)) {
        $this->db->where('rppul.lkp_district_id', $district_id);
        }
        return $users = $this->db->where('tu.status', 1)->get('tbl_users AS tu')->result_array();
    }


    public function check_record($data){
        $table = "survey".$data['survey_id'];
        $this->db->select('data_id');
        $this->db->where('data_id', $data['data_id']);
        return $record_status = $this->db->get($table)->num_rows();
    }


    public function group_info($data)
    {
        $table = "survey" . $data['survey_id'] . "_groupdata";
        
        if (isset($data['groupfield_id']) && !empty($data['groupfield_id'])) {
            $form_group_id_array = [$data['groupfield_id']];
        } else {
            $this->db->select('GROUP_CONCAT(field_id) as field_ids');
            $this->db->where('type', 'group')->where('form_id', $data['survey_id'])->where('status', 1);
            $form_group_id = $this->db->get('form_field')->row_array();
            $form_group_id_array = !empty($form_group_id['field_ids']) ? explode(",", $form_group_id['field_ids']) : [];
        }

        $group_fields = array();

        // Convert data_id to array if it's a single value
        $data_ids = isset($data['data_id']) && !empty($data['data_id']) 
                    ? (is_array($data['data_id']) ? $data['data_id'] : [$data['data_id']]) 
                    : [];

        foreach ($form_group_id_array as $key => $value) {
            $group_fields[$key]['group_fieldid'] = $value;

            $group_label = $this->db->select('label')->where('field_id', $value)->get('form_field')->row_array();
            $group_fields[$key]['group_label'] = $group_label['label'] ?? '';

            $this->db->select('child_id');
            $this->db->where('form_id', $data['survey_id'])->where('field_id', $value);
            $this->db->where('status', 1);
            $group_childid = $this->db->get('form_field')->row_array();

            $child_id_array = !empty($group_childid['child_id']) ? explode(",", $group_childid['child_id']) : [];

            $this->db->select('field_id, type, label, slno');
            $this->db->where('form_id', $data['survey_id'])->where('type !=', 'header')->where_in('field_id', $child_id_array);
            $this->db->where('status', 1);
            $this->db->order_by('slno');
            $group_fields[$key]['group_fields'] = $this->db->get('form_field')->result_array();

            if (!empty($data_ids)) {
                $this->db->select('*');
                $this->db->where('groupfield_id', $value);
                $this->db->where_in('data_id', $data_ids);
                $this->db->where('status', 1);
                $group_fields[$key]['group_data'] = $this->db->get($table)->result_array();
            } else {
                $this->db->select('*');
                $this->db->join('survey' . $data['survey_id'] . ' AS survey', 'survey.data_id = group.data_id');
                $this->db->where('group.groupfield_id', $value)->where('group.status', 1);
                $group_fields[$key]['group_data'] = $this->db->get($table . ' AS group')->result_array();
            }
        }

        return $group_fields;
    }

    public function group_info_details($group){
        $this->db->select('*');
        $this->db->where('group_id', $group)->where('data_status', 1);
        $group_data = $this->db->get('ic_form_group_data')->row_array();

        return $group_data;
    }

    public function survey_location($survey_id){
        // $form_details = $this->db->where('id', $survey_id)->where('status', 1)->get('form')->row_array();

        $this->db->select('loc.id, loc.lat, loc.lng, loc.address, loc.created_date, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->from('ic_data_location as loc');
        $this->db->join('tbl_users AS tu', 'tu.user_id = loc.user_id');
        switch ($survey_id) {
            case 1:
                $this->db->join('tbl_farmers AS tblf', 'tblf.data_id = loc.data_id');
                if(isset($_POST['division']) && !is_null($_POST['division'])){
                    if(isset($_POST['division'])) $division = $this->input->post('division');
                    $this->db->where_in('tblf.division_id', $division);
                }
                if(isset($_POST['circle']) && !is_null($_POST['circle'])){
                    if(isset($_POST['circle'])) $circle = $this->input->post('circle');
                    $this->db->where_in('tblf.circle_id', $circle);
                }
                if(isset($_POST['village']) && !is_null($_POST['village'])){
                    if(isset($_POST['village'])) $village = $this->input->post('village');
                    $this->db->where_in('tblf.village_id', $village);
                }
            break;

            case 2:
                $this->db->join('tbl_plot AS tblp', 'tblp.data_id = loc.data_id');
                if(isset($_POST['division']) && !is_null($_POST['division'])){
                    if(isset($_POST['division'])) $division = $this->input->post('division');
                    $this->db->where_in('tblp.division_id', $division);
                }
                if(isset($_POST['circle']) && !is_null($_POST['circle'])){
                    if(isset($_POST['circle'])) $circle = $this->input->post('circle');
                    $this->db->where_in('tblp.circle_id', $circle);
                }
                if(isset($_POST['village']) && !is_null($_POST['village'])){
                    if(isset($_POST['village'])) $village = $this->input->post('village');
                    $this->db->where_in('tblp.village_id', $village);
                }
            break;

            case 3:
                $this->db->join('tbl_agreement AS tbla', 'tbla.agreement_data_id = loc.data_id');
                $this->db->join('tbl_plot AS tblp', 'tblp.data_id = tbla.plot_data_id');
                if(isset($_POST['division']) && !is_null($_POST['division'])){
                    if(isset($_POST['division'])) $division = $this->input->post('division');
                    $this->db->where_in('tblp.division_id', $division);
                }
                if(isset($_POST['circle']) && !is_null($_POST['circle'])){
                    if(isset($_POST['circle'])) $circle = $this->input->post('circle');
                    $this->db->where_in('tblp.circle_id', $circle);
                }
                if(isset($_POST['village']) && !is_null($_POST['village'])){
                    if(isset($_POST['village'])) $village = $this->input->post('village');
                    $this->db->where_in('tblp.village_id', $village);
                }
            break;
        }
        $this->db->where('loc.form_id', $survey_id)->where('loc.status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(loc.created_date) >=', $_POST['start_date']);
            $this->db->where('DATE(loc.created_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['last_id']) && strlen($_POST['last_id']) > 0){
            $this->db->where('loc.id <', $_POST['last_id']);
        } else {
            $this->db->limit(300);
        }
        $location = $this->db->order_by('loc.id', 'DESC')->get()->result_array();

        $location_data = array();
        foreach ($location as $key => $value) {
            if($value['lat'] != NULL && $value['lng'] != NULL && $value['lat'] != 0 && $value['lng'] != 0 ) {
                $address = ($value['address'] == '' || $value['address'] == NULL) ? "N/A" : $value['address'];

                $uploaded_by = $value['username'];
                $uploaded_date = $value['created_date'];
                $date = new DateTime($uploaded_date, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $uploaded_date = $date->format('Y-m-d H:i:s');

                $data = "<h5>Submitted by : ".$uploaded_by."</h5><h5>Submitted date : ".$uploaded_date."</h5><h5>Address : ".$address."</h5>";

                array_push($location_data, array($value['lat'], $value['lng'], $data, $value['id']) );
            }
        }

        return $location_data;
    }

    public function activity_location($survey_id, $village_id){
        $form_details = $this->db->where('id', $survey_id)->where('status', 1)->get('form')->row_array();

        $this->db->select('loc.lat, loc.lng, loc.address, data.id, data.form_data, data.reg_date_time, data.data_id, concat(tu.first_name, " ", tu.last_name) as username, ld.district_name');
        $this->db->from('ic_data_location as loc');
        $this->db->join('lkp_district AS ld', 'ld.district_id = loc.district_id');
        $this->db->join('ic_form_data as data', 'data.data_id = loc.data_id');
        $this->db->join('tbl_users AS tu', 'tu.user_id = data.user_id');
        $this->db->where('loc.form_id', $survey_id)->where('data.form_id', $survey_id)->where('data.village_id', $village_id);
        $this->db->where('loc.status', 1)->where('data.data_status', 1);
        if(isset($_POST['last_id'])){
            $this->db->where('data.id <', $_POST['last_id']);
        } else {
            $this->db->limit(300);
        }
        $location = $this->db->order_by('data.id', 'DESC')->get()->result_array();

        $location_data = array();
        foreach ($location as $key => $value) {
            if($value['lat'] != NULL && $value['lng'] != NULL && $value['lat'] != 0 && $value['lng'] != 0 ) {
                $address = ($value['address'] == '' || $value['address'] == NULL) ? "N/A" : $value['address'];

                $uploaded_by = $value['username'];
                $uploaded_date = $value['reg_date_time'];
                $date = new DateTime($uploaded_date, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                $uploaded_date = $date->format('Y-m-d H:i:s');

                if($survey_id == 1) {
                    $data_array = json_decode($value['form_data'], true);

                    $household_headname = (isset($data_array['field_1673']) ? $data_array['field_1673'] : 'N/A')." ".(isset($data_array['field_1674']) ? $data_array['field_1674'] : 'N/A');

                    $beneficiary_id = (isset($data_array['field_1670'])) ? $data_array['field_1670'] : "N/A";
                    
                    $data = "<h5 class='title'>".$form_details['title']."</h5><h5>Household headname : ". $household_headname."</h5><h5>Beneficiary id: ". $beneficiary_id."</h5><h5>Submitted by : ".$uploaded_by."</h5><h5>Submitted date : ".$uploaded_date."</h5><h5>Address : ".$address."</h5>";
                } else if($survey_id == 19) {
                    $data_array = json_decode($value['form_data'], true);

                    $farmer_name = isset($data_array['field_2870']) ? $data_array['field_2870'] : '';
                    $farmer_name .= isset($data_array['field_2871']) ? " ".$data_array['field_2871'] : '';
                    $farmer_name = strlen($farmer_name) === 0 ? 'N/A' : $farmer_name;

                    $soil_id = (isset($data_array['field_2874'])) ? $data_array['field_2874'] : "N/A";

                    $data = "<h5 class='title'>".$form_details['title']."</h5><h5>Farmer Name : ". $farmer_name."</h5><h5>Soil ID: ". $soil_id."</h5><h5>Submitted by : ".$uploaded_by."</h5><h5>Submitted date : ".$uploaded_date."</h5>";
                } else {
                    $data = "<h5 class='title'>".$form_details['title']."</h5><h5>Submitted by : ".$uploaded_by."</h5><h5>Submitted date : ".$uploaded_date."</h5><h5>Address : ".$address."</h5>";
                }

                array_push($location_data, array($value['lat'], $value['lng'], $data, $value['district_name'], $value['id']) );
            }
        }

        return $location_data;
    }

     public function get_form_fields($form_id)
    {
        $check_group_field = $this->db->where('type', 'group')->where('form_id', $form_id)->where('status', 1)->get('form_field')->num_rows();

        if($check_group_field > 0){
            $this->db->select('GROUP_CONCAT(field_id) as field_ids');
            $this->db->where('type', 'group')->where('form_id', $form_id)->where('status', 1);
            $form_group_id = $this->db->get('form_field')->row_array();

            $form_group_id_array = explode(",", $form_group_id['field_ids']);

            $group_fields_array = array();
            foreach ($form_group_id_array as $key => $value) {
                $this->db->select('child_id');
                $this->db->where('form_id', $form_id)->where('field_id', $value);
                $this->db->where('status', 1);
                $group_childid = $this->db->get('form_field')->row_array();

                $child_id_array = explode(",", $group_childid['child_id']);

                foreach ($child_id_array as $key => $child) {
                    array_push($group_fields_array, $child);
                }
            }
        }else{
            $group_fields_array = array(0);
        }

        $this->db->select('field_id, label, name, type, multiple, required, parent_id, maxlength, subtype');
        $this->db->where('form_id', $form_id)->where('type !=', 'header')->where('type !=', 'collapse')->where('type !=', 'group');
        $this->db->where('status', 1)->where_not_in('field_id', $group_fields_array)->order_by('slno');
        
        return $form_field = $this->db->get('form_field')->result_array();
    }

    public function lkp_crop_types(){
        $this->db->select('type_id, type_name');
        $this->db->where('status', 1);
        return $age = $this->db->get('lkp_crop_types')->result_array();
    }

    public function lkp_crops(){
        $this->db->select('crop_id, crop_name');
        $this->db->where('status', 1);
        return $age = $this->db->get('lkp_crops')->result_array();
    }

    public function lkp_crop_intervention(){
        $this->db->select('intervention_id, intervention_name');
        $this->db->where('status', 1);
        return $age = $this->db->get('lkp_crop_intervention')->result_array();
    }

    public function lkp_crop_inputname(){
        $this->db->select('inputname_id, inputname_name');
        $this->db->where('status', 1);
        return $age = $this->db->get('lkp_crop_inputname')->result_array();
    }

    public function lkp_crop_varieties(){
        $this->db->select('variety_id, variety_name');
        $this->db->where('status', 1);
        return $age = $this->db->get('lkp_crop_varieties')->result_array();
    }

    public function coconutplantation_info($data){
        $result = array();

        $this->db->select('file_name');
        $this->db->where('data_id', $data['data_id'])->where('status', 1);
        $this->db->where('form_id', $data['survey_id']);
        $this->db->order_by('created_date', 'DESC');
        $result['images'] = $this->db->get('ic_data_file')->result_array();

        $this->db->select('*');
        $this->db->where('data_id', $data['data_id'])->where('status', 1);
        $this->db->where('form_id', $data['survey_id']);
        $this->db->order_by('created_date', 'DESC');
        $result['location'] = $this->db->get('ic_data_location')->result_array();

        return $result;
    }

    public function get_lookup_data($table) {
        return $lookup_data = $this->db->get($table)->result_array();
    }
    
	public function get_country_projects($countries)
	{
		$this->db->where_in('country_id', $countries);
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_country_projects');
		return $query->result_array(); // Convert result to array
	}

	public function get_country_sites($countries)
	{
		$this->db->where_in('country_id', $countries);
		$this->db->where('status', 1);
		$query = $this->db->get('lkp_project_site');
		return $query->result_array(); // Convert result to array
	}

}
