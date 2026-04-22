<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();  
    }

    public function farmer_registered(){
        $this->db->select('data_id');        
        $this->db->where('farmer_status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(added_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('division_id', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('circle_id', $circle);
        }
        $farmer_registered = $this->db->get('tbl_farmers')->num_rows();

        return $farmer_registered;
    }

    public function total_plot(){
        $this->db->select('tblp.*, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('tbl_users AS tu', 'tu.user_id = tblp.added_by');
        $this->db->where('tblp.plot_status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(tblp.added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(tblp.added_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('tblp.field_1031', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('tblp.field_1032', $circle);
        }
        $data = $this->db->order_by('tblp.plot_id', 'DESC')->get('tbl_plot AS tblp');

        // foreach ($data as $key => $value) {
        //     $date = new DateTime($value['added_date'], new DateTimeZone('UTC'));
        //     $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        //     $data[$key]['added_date'] = $date->format('Y-m-d H:i:s');
        // }

        return $data->num_rows();
    } 

    public function plot_databyid($plot_id){
        $this->db->select('tblp.*, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->join('tbl_users AS tu', 'tu.user_id = tblp.added_by');
        $this->db->where('tblp.plot_status', 1);
        $this->db->where('plot_id', $plot_id);
        $data = $this->db->get('tbl_plot AS tblp')->row_array();

        if($data == NULL){
            return false;
        }

        $date = new DateTime($data['added_date'], new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $data['added_date'] = $date->format('Y-m-d H:i:s');

        $kml = $this->db->where('plot_data_id', $data['data_id'])->where('kml_status', 1)->get('tbl_kmlfile');
        if($kml->num_rows() > 0) $data['kml'] = $kml->row_array();
        else $data['kml'] = NULL;

        return $data;
    } 

    public function famers_byvillage()
    {
        // Disable strict mode
        $this->db->query('SET SESSION sql_mode = ""');
        
        $this->db->distinct();
        $this->db->select('lv.VNAME AS name, COUNT(tf.data_id) AS count');
        $this->db->join('lkp_village AS lv', 'lv.VILLAGE_CODE = tf.village_id');
        $this->db->where('tf.farmer_status', 1)->where('lv.VILLAGE_STATUS', 1);
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('tf.division_id', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('tf.circle_id', $circle);
        }
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(tf.added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(tf.added_date) <=', $_POST['end_date']);
        }
        $this->db->group_by('tf.village_id')->order_by('lv.VNAME', 'ASC');
        $distinct_village_list = $this->db->get('tbl_farmers AS tf')->result_array();

        return $distinct_village_list;
    }

    public function location_data()
    {
        $baseurl = base_url();

        $this->db->select('loc.data_id, loc.form_id, loc.lat, loc.lng, loc.address, loc.created_date, f.title, concat(tu.first_name, " ", tu.last_name) as username');
        $this->db->from('ic_data_location as loc');
        $this->db->join('tbl_users AS tu', 'tu.user_id = loc.user_id');
        $this->db->join('form as f', 'f.id = loc.form_id');
        $this->db->where('loc.status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(loc.created_date) >=', $_POST['start_date']);
            $this->db->where('DATE(loc.created_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('loc.division_id', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('loc.circle_id', $circle);
        }
        $location_data = $this->db->get()->result_array();
        
        
        $location_array = array();
        foreach ($location_data as $key => $location) {

            $date = new DateTime($location['created_date'], new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
            $new_date = $date->format('Y-m-d H:i:s');

            $this->db->select('file_name');
            $this->db->where('file_type', 'image')->where('form_id', $location['form_id'])->where('data_id', $location['data_id']);
            $images = $this->db->get('ic_data_file')->result_array();

            if(count($images) > 0){
                $imgData = '';
                foreach ($images as $ikey => $img) {
                    $imgData .= '<img src="'.$baseurl.'uploads/survey/'.$img['file_name'].'" style="width:120px;height:120px;margin-left:5px;">';
                }
            }else{
                $imgData = '';
            }

            switch ($location['form_id']) {
                case 1:
                    $get_data = $this->db->select('farmer_number, field_1000, field_1001')->where('data_id', $location['data_id'])->get('tbl_farmers')->row_array();
                    if($get_data == NULL){
                       $farmer_number = "N/A";
                       $farmer_name = "N/A";
                    }else{
                        $farmer_number = $get_data['farmer_number'];
                        $farmer_name = $get_data['field_1000']." ".$get_data['field_1001'];
                    }               
                    $data = $imgData."<h6>".$location['title']."</h6><h6>Uploaded by: ".$location['username']."</h6><h6> Uploaded date: ".$new_date."</h6><h6>Farmer name: ".$farmer_name."</h6><h6>Farmer number: ".$farmer_number."</h6>";
                    break;

                case 2:
                    $get_data = $this->db->select('plot_number')->where('data_id', $location['data_id'])->get('tbl_plot')->row_array();
                    if($get_data == NULL){
                       $plot_number = "N/A";
                    }else{
                        $plot_number = $get_data['plot_number'];
                    }
                    $data = $imgData."<h6>".$location['title']."</h6><h6>Uploaded by: ".$location['username']."</h6><h6> Uploaded date: ".$new_date."</h6><h6>Plot number: ".$plot_number."</h6>";
                    break;
                
                default:
                    $data = $imgData."<h6>".$location['title']."</h6><h6>Uploaded by: ".$location['username']."</h6><h6> Uploaded date: ".$new_date."</h6>";
                    break;
            }

            

            array_push($location_array, array($location['lat'], $location['lng'], $data));
        }

        return $location_array;
    }

    public function total_res()
    {   
        $this->db->distinct();
        $this->db->select('unit.user_id');
        $this->db->from('tbl_users as user');
        $this->db->join('tbl_user_unit_location as unit', 'unit.user_id = user.user_id');
        $this->db->where('user.status', 1)->where('unit.status', 1)->where('role_id', 5);
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('DIV_CODE', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('CIR_CODE', $circle);
        }
        $total_res = $this->db->get()->num_rows();

        return $total_res;
    }

    public function total_area(){        
        $this->db->select('sum(field_1037) as area');        
        $this->db->where('plot_status', 1);
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(added_date) <=', $_POST['end_date']);
        }
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('field_1031', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('field_1032', $circle);
        }
        if(isset($_POST['village']) && !is_null($_POST['village'])){
            if(isset($_POST['village'])) $village = $this->input->post('village');
            $this->db->where_in('field_1033', $village);
        }
        $total_area = $this->db->get('tbl_plot')->row_array();

        return round($total_area['area'], 2);
    }

    public function plotsregisterd_agrementdone()
    {
        // Disable strict mode
        $this->db->query('SET SESSION sql_mode = ""');
        
        $this->db->distinct();
        $this->db->select('village_id');
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('field_1031', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('field_1032', $circle);
        }
        $this->db->where('plot_status', 1);
        $distinct_village_list_plotsregistered = $this->db->get('tbl_plot')->result_array();

        $this->db->distinct();
        $this->db->select('lv.VNAME, tp.village_id, COUNT(tp.data_id) AS plot_registered');
        $this->db->join('lkp_village AS lv', 'lv.VILLAGE_CODE = tp.village_id');
        if(isset($_POST['division']) && !is_null($_POST['division'])){
            if(isset($_POST['division'])) $division = $this->input->post('division');
            $this->db->where_in('tp.field_1031', $division);
        }
        if(isset($_POST['circle']) && !is_null($_POST['circle'])){
            if(isset($_POST['circle'])) $circle = $this->input->post('circle');
            $this->db->where_in('tp.field_1032', $circle);
        }
        if(isset($_POST['start_date']) && isset($_POST['end_date'])){
            $this->db->where('DATE(tp.added_date) >=', $_POST['start_date']);
            $this->db->where('DATE(tp.added_date) <=', $_POST['end_date']);
        }
        $this->db->where('tp.plot_status', 1)->group_by('tp.village_id')->order_by('lv.VNAME', 'ASC');
        $distinct_village_list_plotsregistered = $this->db->get('tbl_plot AS tp')->result_array();

        $plot_info = array();
        foreach ($distinct_village_list_plotsregistered as $key => $value) {
            $plot_info[$key]['name'] = $value['VNAME'];
            $plot_info[$key]['plot_registered'] = $value['plot_registered'];

            $this->db->select('agg.agreement_data_id')->from('tbl_plot as reg');
            $this->db->join('tbl_agreement as agg', 'reg.data_id = agg.plot_data_id');
            $this->db->where('reg.plot_status', 1)->where('agg.agreement_status', 1);
            if(isset($_POST['start_date']) && isset($_POST['end_date'])){
                $this->db->where('DATE(agg.added_date) >=', $_POST['start_date']);
                $this->db->where('DATE(agg.added_date) <=', $_POST['end_date']);
            }
            $this->db->where('reg.village_id', $value['village_id']);
            $plot_aggrement = $this->db->get()->num_rows();
            $plot_info[$key]['plot_aggrement'] = $plot_aggrement;
        }

        return $plot_info;
    }    

    //getting user assigned projects
    public function projects_list()
    {

        $this->db->distinct()->select('lp.proj_id, lp.proj_name, lp.proj_description, lp.proj_loc_depth, lp.proj_reg_date, lp.status');
        $this->db->from('lkp_projects AS lp');
        if($this->session->userdata('role') >= 3) {
            $this->db->join('rpt_user_form_location AS rufl', 'rufl.proj_id = lp.proj_id', 'left');
            $this->db->where('rufl.user_id', $this->session->userdata('login_id'))->where('rufl.status', 1);
            $this->db->or_where_in('lp.proj_id', array(9,10));
        }
        $users_projectslist = $this->db->where('lp.status', 1)->get()->result_array();
        
        $users_projectslist_array = array();
        foreach ($users_projectslist as $key => $value) {
            array_push($users_projectslist_array, $value['proj_id']);
        }

        if(count($users_projectslist_array) == 0){
            $users_projectslist_array = array(0);
        }

        return $users_projectslist_array;
    }

    //getting surveys of all the projects
    public function project_surveys($projects_list){
        $project_array = array(); 
        foreach ($projects_list as $key => $project) {
            $project_upload = 0;

            $this->db->select('proj_name');
            $this->db->where('proj_id', $project)->where('status', 1);
            $project_name = $this->db->get('lkp_projects')->row_array();

            $this->db->distinct();
            $this->db->select('fl.form_id, f.title, f.datetime');
            $this->db->from('rpt_project_form_location as fl');
            $this->db->join('form as f', 'f.id = fl.form_id');
            $this->db->where('fl.proj_id', $project)->where('fl.status', 1);
            $this->db->where('f.status', 1);
            $form_ids = $this->db->get()->result_array();
            // echo $this->db->last_query();exit;

            $form_ids_array = array();
            foreach ($form_ids as $key => $form) {
                array_push($form_ids_array, $form['form_id']);
            }

            $form_list = array();
            foreach ($form_ids_array as $key => $formid) {
                $this->db->select('title, type');
                $this->db->where('id', $formid)->where('status', 1);
                $formname = $this->db->get('form')->row_array();

                $this->db->select('data_id');
                $this->db->where('form_id', $formid)->where('project_id', $project)->where('data_status', 1);
                $survey_upload_count = $this->db->get('ic_form_data')->num_rows();
                /*echo $this->db->last_query();
                var_dump($survey_upload_count);*/

                if($survey_upload_count > 0){
                    $project_upload = $project_upload + $survey_upload_count;
                }

                $this->db->distinct();
                $this->db->select('user_id');
                $this->db->where('form_id', $formid)->where('proj_id', $project)->where('status', 1);
                $survey_users_count = $this->db->get('rpt_user_form_location')->num_rows();

                $form_details = array(
                    'formid' => $formid,
                    'type' => $formname['type'],
                    'formname' => $formname['title'],
                    'survey_upload_count' => $survey_upload_count,
                    'survey_users_count' => $survey_users_count
                );

                array_push($form_list, $form_details);           
            }

            $this->db->distinct();
            $this->db->select('user_id');
            $this->db->where('proj_id', $project)->where('status', 1);
            $projects_users = $this->db->get('rpt_user_form_location')->result_array();

            $projects_user_list = array();
            foreach ($projects_users as $key => $value) {
                array_push($projects_user_list, $value['user_id']);
            }

            $project_data = array(
                'project_id' => $project,
                'project_name' => $project_name['proj_name'],
                'project_users' => count($projects_user_list),
                'project_upload' => $project_upload,
                'project_surveys' => $form_list
            );

            array_push($project_array, $project_data);
        }
        
        return $project_array;
    }
}
