<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Valuechainmanagement_model extends CI_Model {
    
	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

    public function get_value_chain()
    {
        $this->db->where('status', 1);
        $this->db->order_by('value_chain_name');
        return $value_chain_list = $this->db->get('lkp_value_chain')->result_array();
    }

    public function get_user_surveys()
    {
        $this->db->select('lkp_value_chain_id as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->result_array();
        $valuechain_ids_arrays=array();

        foreach($get_user_valuechains as $valuechains){
            $valuechainids=$valuechains['valuechain_ids'];
            array_push($valuechain_ids_arrays,$valuechainids);
        }

        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('f.id, f.title, vc.value_chain_name');
        $this->db->from('rpt_form_relation as fr');
        $this->db->join('form as f', 'f.id = fr.form_id');
        $this->db->join('lkp_value_chain as vc', 'vc.value_chain_id = fr.lkp_value_chain_id');
        $this->db->where_in('lkp_value_chain_id', $valuechain_ids_arrays)->where('relation_status', 1);
        $this->db->order_by('vc.value_chain_name, f.title');
        $form = $this->db->get()->result_array();

        return array('form' => $form, 'value_chain_list' => $value_chain_list);
    }

    public function get_value_chain_locations($value_chain_id)
    {
        $this->db->select('lkp_value_chain_id as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->result_array();
        $valuechain_ids_arrays=array();
        foreach($get_user_valuechains as $valuechains){

            $valuechainids=$valuechains['valuechain_ids'];
            if(!in_array($valuechainids,$valuechain_ids_arrays))
            {
                array_push($valuechain_ids_arrays,$valuechainids);
            }

        }

        // $valuechain_ids_arrays = explode(",", $get_user_valuechains['valuechain_ids']);

        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('vl.*, cou.name, scou.sub_county_name, ward.ward_name, vc.value_chain_name');
        $this->db->from('rpt_value_chain_location as vl');
        $this->db->join('lkp_county as cou', 'cou.county_id = vl.lkp_county_id');
        $this->db->join('lkp_sub_county as scou', 'scou.sub_county_id = vl.lkp_sub_county_id');
        $this->db->join('lkp_ward as ward', 'ward.ward_id = vl.lkp_ward_id');
        $this->db->join('lkp_value_chain as vc', 'vc.value_chain_id = vl.lkp_value_chain_id');
        $this->db->where('vl.lkp_value_chain_id', $value_chain_id);
        if(isset($_POST['value_chain_val']) && ($_POST['value_chain_val'] != 'all')){
            $this->db->where('lkp_value_chain_id', $_POST['value_chain_val']);
        }else{
            $this->db->where_in('lkp_value_chain_id', $valuechain_ids_arrays);
        }
        $this->db->where('cou.status', 1)->where('scou.status', 1)->where('ward.status', 1)->where('loc_status', 1);
        $this->db->order_by('cou.name')->order_by('scou.sub_county_name')->order_by('ward.ward_name');
        $value_chain_location = $this->db->get()->result_array();

        return array('value_chain_list' => $value_chain_list, 'value_chain_location' => $value_chain_location);
    }

    public function get_value_chain_locations_users($value_chain, $user_id)
    {
        if(isset($_POST['value_chain_val'])){
            $value_chain_val = $_POST['value_chain_val'];
        }

        /*$this->db->select('lkp_value_chain_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->result_array();*/

        /*$valuechain_ids_arrays=array();
        foreach($get_user_valuechains as $valuechains){
            $valuechainid = $valuechains['lkp_value_chain_id'];
            if(!in_array($valuechainid,$valuechain_ids_arrays))
            {
                array_push($valuechain_ids_arrays,$valuechainid);
            }
        }
        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('user_id as user_ids');
        $this->db->where_in('lkp_value_chain_id', $valuechain_ids_arrays)->where('value_chain_user_status', 1);
        $user_ids = $this->db->get('rpt_value_chain_user')->result_array();
        $user_ids_array=array();

        foreach($user_ids as $userid){
            $user_ids=$userid['user_ids'];
            if(!in_array($user_ids,$user_ids_array))
            {
                array_push($user_ids_array,$user_ids);
            }
        }*/
        //$user_ids_array = explode(",", $user_ids['user_ids']);

        $this->db->select('vl.*, cou.name, scou.sub_county_name, ward.ward_name, CONCAT(user.first_name, " ", user.last_name) as username, vc.value_chain_name');
        $this->db->from('rpt_value_chain_user_location as vl');
        $this->db->join('lkp_county as cou', 'cou.county_id = vl.lkp_county_id');
        $this->db->join('lkp_sub_county as scou', 'scou.sub_county_id = vl.lkp_sub_county_id');
        $this->db->join('lkp_ward as ward', 'ward.ward_id = vl.lkp_ward_id');
        $this->db->join('tbl_users as user', 'user.user_id = vl.user_id');
        $this->db->join('lkp_value_chain as vc', 'vc.value_chain_id = vl.lkp_value_chain_id');
        $this->db->where('lkp_value_chain_id', $value_chain);
        $this->db->where('vl.user_id', $user_id)->where('value_chain_user_loc_status', 1);
        /*if(!empty($user_id)){
            $this->db->where_in('vl.user_id', $user_id)->where('value_chain_user_loc_status', 1);
        }else{
            $this->db->where_in('vl.user_id', $user_ids_array)->where('value_chain_user_loc_status', 1);
        }*/
        $this->db->where('cou.status', 1)->where('scou.status', 1)->where('ward.status', 1)->where('vl.lkp_value_chain_id', $value_chain);
        $value_chain_location_user = $this->db->get()->result_array();

        return array(/*'value_chain_list' => $value_chain_list,*/ 'value_chain_location_user' => $value_chain_location_user);
    }

    public function get_value_chain_users()
    {
        $this->db->select('lkp_value_chain_id as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->result_array();
        $valuechain_ids_arrays=array();

        foreach($get_user_valuechains as $valuechains)
        {
            $valuechain_id=$valuechains['valuechain_ids'];
            if(!in_array($valuechain_id,$valuechain_ids_arrays))
            {
                array_push($valuechain_ids_arrays,$valuechain_id);
            }
        }
        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('user.first_name, user.last_name, vc.value_chain_name, vc.value_chain_id');
        $this->db->from('rpt_value_chain_user as vcu');
        $this->db->join('lkp_value_chain as vc', 'vcu.lkp_value_chain_id = vc.value_chain_id');
        $this->db->join('tbl_users as user', 'user.user_id = vcu.user_id');
        $this->db->where_in('lkp_value_chain_id', $valuechain_ids_arrays)->where('value_chain_user_status', 1)->order_by('vc.value_chain_name')->order_by('user.first_name');
        $valuechain_users = $this->db->get('')->result_array();

        return array('value_chain_list' => $value_chain_list, 'valuechain_users' => $valuechain_users);
    }
    
    //get_value_chain_users_by_id()
    public function get_value_chain_users_by_id(){
        if(isset($_POST['valuechain_id'])){
            $valuechain_id = $_POST['valuechain_id'];
        }

        $this->db->select('lkp_value_chain_id as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->result_array();
        $valuechain_ids_arrays=array();
        foreach($get_user_valuechains as $valuechains)
        {
            $valuechain=$valuechains['valuechain_ids'];
            if(!in_array($valuechain,$valuechain_ids_arrays)){
                array_push($valuechain_ids_arrays, $valuechain);
            }
        }
        // $valuechain_ids_arrays = explode(",", $get_user_valuechains['valuechain_ids']);

        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('user.first_name, user.last_name,user.user_id, vc.value_chain_name, vc.value_chain_id');
        $this->db->from('rpt_value_chain_user as vcu');
        $this->db->join('lkp_value_chain as vc', 'vcu.lkp_value_chain_id = vc.value_chain_id');
        $this->db->join('tbl_users as user', 'user.user_id = vcu.user_id');
        if(!isset($_POST['valuechain_id']) || $valuechain_id==""){
          $this->db->where_in('vcu.lkp_value_chain_id', $valuechain_ids_arrays);
        }else{
            $this->db->where('vcu.lkp_value_chain_id', $valuechain_id);
            if(isset($_POST['user_id']) && $_POST['user_id'] != ''){
                $this->db->where('user.user_id', $_POST['user_id']);
            }
        }
        $this->db->where('user.status',1);
        $this->db->where('vcu.value_chain_user_status', 1)->order_by('vc.value_chain_name')->order_by('user.first_name');
        $val_chain_users=$this->db->get('')->result_array();

        return array('value_chain_list' => $value_chain_list, 'val_chain_users'=>$val_chain_users, 'status' => 1);
    }

    public function get_surveydetails()
    {
        $this->db->select('lkp_value_chain_id as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->result_array();
        $valuechain_ids_arrays=array();

        foreach($get_user_valuechains as $uservaluechain)
        {
            $user_valuechain=$uservaluechain['valuechain_ids'];
            if(!in_array($user_valuechain,$valuechain_ids_arrays))
            {
                array_push($valuechain_ids_arrays,$user_valuechain);
            }
        }

        // $valuechain_ids_arrays = explode(",", $get_user_valuechains['valuechain_ids']);

        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('GROUP_CONCAT(lkp_value_chain_id) as value_assigned');
        $this->db->where('form_id', $_POST['survey_id']);
        $this->db->where('relation_status', 1);
        $assigned_valuechains = $this->db->get('rpt_form_relation')->row_array();

        $assigned_valuechains_array = explode(",", $assigned_valuechains['value_assigned']);

        return $result = array('value_chain_list' => $value_chain_list, 'assigned_valuechains_array' => $assigned_valuechains_array);

    }

    public function get_allsurvey()
    {      
        $this->db->select('form.*');
        $this->db->from('form');
        /*$this->db->join('rpt_form_relation as fr', 'form.id = fr.form_id');
        $this->db->where('status', 1)->where('fr.type_id', 2)->where('relation_status', 1)->group_by('fr.form_id');*/
        $this->db->where('status', 1);
        return $form_list = $this->db->get()->result_array();
    }

    public function assign_valuechain_survey()
    {
        date_default_timezone_set("UTC");
        $survey_id = $_POST['survey_id'];
        $valuechains = $_POST['valuechains'];

        $data = array(
            'relation_status' => 0
        );
        $this->db->where('form_id', $survey_id);
        $updatequery = $this->db->update('rpt_form_relation', $data);

        if($updatequery){
            foreach ($valuechains as $key => $value) {
                $insert_data = array(
                    'lkp_value_chain_id' => $value,
                    'form_id' => $survey_id,
                    'type_id' => 2,
                    'added_by' => $this->session->userdata('login_id'),
                    'added_datetime' => date('Y-m-d H:i:s'),
                    'ip_address' => $this->input->ip_address(),
                    'relation_status' => 1
                );

                $insert_query = $this->db->insert('rpt_form_relation', $insert_data);

                if(!$insert_query){
                    return false;
                }
            }
        }else{
            return false;
        }

        return true;
    }

    public function get_uservaluechain()
    {
       /* $this->db->select('GROUP_CONCAT(lkp_value_chain_id) as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->row_array();

        $valuechain_ids_arrays = explode(",", $get_user_valuechains['valuechain_ids']);*/

        $this->db->where('status', 1);
        return $value_chain_list = $this->db->get('lkp_value_chain')->result_array();
    }

    public function get_county_list()
    {
        $this->db->where('status', 1);
        return $county_list = $this->db->get('lkp_county')->result_array();
    }

    public function get_subcounty_list()
    {
        $this->db->where('status', 1)->where('county_id', $_POST['county_id']);
        return $subcounty_list = $this->db->get('lkp_sub_county')->result_array();
    }

    public function get_ward_list()
    {
        $this->db->where('status', 1)->where('county_id', $_POST['county_id'])->where('sub_county_id', $_POST['subcounty_id']);
        return $ward_list = $this->db->get('lkp_ward')->result_array();
    }
    public function get_ward_byvaluechain()
    {
        $assignwards=$this->db->select('lkp_ward_id')->from('rpt_value_chain_location')->where('lkp_county_id',$_POST['county_id'])->where('lkp_sub_county_id',$_POST['subcounty_id'])->where('lkp_value_chain_id',$_POST['valuechain_id'])->where('loc_status',1)->get()->result_array();
        $allassignwards=array();
        foreach($assignwards as $wards){
            $ward_id=$wards['lkp_ward_id'];
            if(!in_array($ward_id,$allassignwards))
            {
               array_push($allassignwards, $ward_id);

            }            
        }

        $this->db->where('status', 1)->where('county_id', $_POST['county_id'])->where('sub_county_id', $_POST['subcounty_id']);
        if(count($allassignwards)!=0){
        $this->db->where_not_in('ward_id',$allassignwards);
       }
        return $ward_list = $this->db->get('lkp_ward')->result_array();
    }

    public function assign_valuechain_location()
    {
        date_default_timezone_set("UTC");
        $valuechain_id = $_POST['valuechain_id'];
        $county_id = $_POST['county_id'];
        $subcounty_id = $_POST['subcounty_id'];
        $ward_id = $_POST['ward_id'];

        foreach ($ward_id as $key => $value) {
            $check_record_count = $this->db->where('lkp_value_chain_id', $valuechain_id)->where('lkp_county_id', $county_id)->where('lkp_sub_county_id', $subcounty_id)->where('lkp_ward_id', $value)->where('loc_status', 1)->get('rpt_value_chain_location')->num_rows();

            if($check_record_count == 0){
                $insert_data = array(
                    'lkp_value_chain_id' => $valuechain_id,
                    'lkp_county_id' => $county_id,
                    'lkp_sub_county_id' => $subcounty_id,
                    'lkp_ward_id' => $value,
                    'added_by' => $this->session->userdata('login_id'),
                    'added_datetime' => date('Y-m-d H:i:s'),
                    'ip_address' => $this->input->ip_address(),
                    'loc_status' => 1
                );

                $insert_query = $this->db->insert('rpt_value_chain_location', $insert_data);
                if(!$insert_query){
                    return false;
                }
            }                
        }
        return true;
    }

    public function get_valuechain_userids()
    {
        $valuechain_id = $_POST['valuechain_id'];

        $this->db->select('GROUP_CONCAT(vcusers.user_id) as user_ids');
        $this->db->from('rpt_value_chain_user as vcusers');
        $this->db->where('lkp_value_chain_id', $valuechain_id);
        $this->db->where('value_chain_user_status', 1);
        $user_list = $this->db->get()->row_array();

        return explode(",", $user_list['user_ids']);
    }

    public function get_users_list($get_valuechain_users)
    {   
        $this->db->select('user_id, CONCAT(first_name, " ", last_name) as username');
        $this->db->where('status', 1)->where_not_in('user_id', $get_valuechain_users);
        return $user_list = $this->db->get('tbl_users')->result_array();
    }

    public function get_valuechain_user()
    {
        if(isset($_POST['valuechain_id']) && ($_POST['valuechain_id'])!=""){
        $valuechain_id = $_POST['valuechain_id'];
        }
        else{
        $valuechain_id = $this->uri->segment(3);
        }
        // echo $this->uri->segment(3);die();

        $this->db->select('user.user_id, CONCAT(first_name, " ", last_name) as username');
        $this->db->from('rpt_value_chain_user as vcusers');
        $this->db->join('tbl_users as user', 'user.user_id = vcusers.user_id');
        $this->db->where('lkp_value_chain_id', $valuechain_id);
        $this->db->where('value_chain_user_status', 1);
        $this->db->where('user.status', 1);
        $this->db->order_by('first_name');
        return $user_list = $this->db->get()->result_array();
    }

    public function get_valuechain_county()
    {
        $valuechain_id = $_POST['valuechain_id'];

        $this->db->select('lkp_county_id as county_ids');
        $this->db->where('lkp_value_chain_id', $valuechain_id)->where('loc_status', 1);
        //$this->db->group_by('lkp_county_id');
        $get_countylist = $this->db->get('rpt_value_chain_location')->result_array();
        $get_countylist_array=array();
        foreach($get_countylist as $countylist)
        {
            $countyids=$countylist['county_ids'];
            if(!in_array($countyids,$get_countylist_array))
            {
                array_push($get_countylist_array,$countyids);
            }
        }


        $this->db->where('status', 1)->where_in('county_id', $get_countylist_array);
        $this->db->order_by('name');
        return $county_list = $this->db->get('lkp_county')->result_array();
    }

    public function get_valuechain_subcounties_list()
    {
        $valuechain_id = $_POST['valuechain_id'];

        $this->db->select('lkp_sub_county_id as subcounty_ids');
        $this->db->where('lkp_value_chain_id', $valuechain_id)->where('loc_status', 1)->where('lkp_county_id', $_POST['county_id']);
        //$this->db->group_by('lkp_sub_county_id');
        $get_subcountylist = $this->db->get('rpt_value_chain_location')->result_array();
        $get_subcountylist_array=array();
        foreach($get_subcountylist as $subcountylist){
            $subcounty=$subcountylist['subcounty_ids'];
            if(!in_array($subcounty,$get_subcountylist_array)){
                array_push($get_subcountylist_array,$subcounty);
            }
        }

        // $get_subcountylist_array = explode(",", $get_subcountylist['subcounty_ids']);

        $this->db->where('status', 1)->where_in('sub_county_id', $get_subcountylist_array)->where('county_id', $_POST['county_id']);
        return $subcounty_list = $this->db->get('lkp_sub_county')->result_array();
    }

    public function get_valuechain_wards()
    {
        $valuechain_id = $_POST['valuechain_id'];
        $user_id = $_POST['user_id'];        

        $this->db->select('lkp_ward_id as ward_ids');
        $this->db->where('lkp_value_chain_id', $valuechain_id)->where('loc_status', 1)->where('lkp_county_id', $_POST['county_id'])->where('lkp_sub_county_id', $_POST['subcounty_id']);
        $get_wardlist = $this->db->get('rpt_value_chain_location')->result_array();

        $get_wardlist_array=array();
        foreach($get_wardlist as $wardlist)
        {
            $wards=$wardlist['ward_ids'];
            if(!in_array($wards,$get_wardlist))
            {
                array_push($get_wardlist_array,$wards);
            }
        }
       
        // $get_wardlist_array = explode(",", $get_wardlist['ward_ids']);


        $this->db->select('lkp_ward_id as ward_ids');
        $this->db->where('lkp_value_chain_id', $valuechain_id)->where('lkp_county_id', $_POST['county_id'])->where('lkp_sub_county_id', $_POST['subcounty_id'])->where('value_chain_user_loc_status', 1)->where('user_id', $user_id);
        $get_users_wardlist = $this->db->get('rpt_value_chain_user_location')->result_array();
        
        $get_users_wardlist_array=array();
        foreach($get_users_wardlist as $wardlist)
        {
            $userwards=$wardlist['ward_ids'];
            if(!in_array($userwards,$get_wardlist))
            {
                array_push($get_users_wardlist_array,$userwards);
            }
        }
        

        // $get_users_wardlist_array = explode(",", $get_users_wardlist['ward_ids']);

        $this->db->where('status', 1)->where('county_id', $_POST['county_id'])->where('sub_county_id', $_POST['subcounty_id']);
        if(count($get_users_wardlist_array)!=0)
            {
               $this->db->where_not_in('ward_id', $get_users_wardlist_array);
            }
        $this->db->where_in('ward_id', $get_wardlist_array);
        $this->db->order_by('ward_name');
        return $ward_list = $this->db->get('lkp_ward')->result_array();
    }

    public function assign_user_valuechain_locations()
    {
        date_default_timezone_set("UTC");
        $valuechain_id = $_POST['valuechain_id'];
        $county_id = $_POST['county_id'];
        $subcounty_id = $_POST['subcounty_id'];
        $ward_id = $_POST['ward_id'];
        $user_id = $_POST['user_id'];

        foreach ($ward_id as $key => $value) {
            $check_record_count = $this->db->where('lkp_value_chain_id', $valuechain_id)->where('user_id', $user_id)->where('lkp_county_id', $county_id)->where('lkp_sub_county_id', $subcounty_id)->where('lkp_ward_id', $value)->where('value_chain_user_loc_status', 1)->get('rpt_value_chain_user_location')->num_rows();

            if($check_record_count == 0){
                $insert_data = array(
                    'lkp_value_chain_id' => $valuechain_id,
                    'user_id' => $user_id,
                    'lkp_county_id' => $county_id,
                    'lkp_sub_county_id' => $subcounty_id,
                    'lkp_ward_id' => $value,
                    'added_by' => $this->session->userdata('login_id'),
                    'added_datetime' => date('Y-m-d H:i:s'),
                    'ip_address' => $this->input->ip_address(),
                    'value_chain_user_loc_status' => 1
                );

                $insert_query = $this->db->insert('rpt_value_chain_user_location', $insert_data);

                if(!$insert_query){
                    return false;
                }
            }
        }
        
        return true;
    }

    public function assign_valuechain_user()
    {
        date_default_timezone_set("UTC");
        $valuechain = $_POST['valuechain'];
        $users_list = $_POST['users_list'];

        foreach ($users_list as $key => $value) {
            $check_record_count = $this->db->where('lkp_value_chain_id', $valuechain)->where('user_id', $value)->where('value_chain_user_status', 1)->get('rpt_value_chain_user')->num_rows();

            if($check_record_count == 0){
                $insert_data = array(
                    'lkp_value_chain_id' => $valuechain,
                    'user_id' => $value,
                    'added_by' => $this->session->userdata('login_id'),
                    'added_datetime' => date('Y-m-d H:i:s'),
                    'ip_address' => $this->input->ip_address(),
                    'value_chain_user_status' => 1
                );

                $insert_query = $this->db->insert('rpt_value_chain_user', $insert_data);
                if(!$insert_query){
                    return false;
                }
            }                
        }
        return true;
    }
    public function get_allcounty()
    {
        return $this->db->select('county_id,name')->from('lkp_county')->where('status',1)->get()->result_array();
    }
    public function get_users_value_chain_location()
    {
        $record_id=$_POST['record_id'];
        $user_id=$_POST['user_id'];
        $valuechain_id=$_POST['valuechain_id'];

        $finaldata=array();

        $recorddata=$this->db->select('loc.lkp_county_id as county_id,loc.lkp_sub_county_id as subcounty_id,loc.lkp_ward_id as ward_id,county.name as countyname,sub.sub_county_name as subcountyname,ward.ward_name as wardname')->from('rpt_value_chain_user_location loc')->join('lkp_county county','county.county_id=loc.lkp_county_id')->join('lkp_sub_county sub','sub.sub_county_id=loc.lkp_sub_county_id')->join('lkp_ward ward','ward.ward_id=loc.lkp_ward_id')->where('loc.value_chain_user_loc_id',$record_id)->get()->row_array();
        
        $this->db->select('lkp_ward_id as ward_ids');
        $this->db->where('lkp_value_chain_id', $valuechain_id)->where('lkp_county_id', $recorddata['county_id'])->where('lkp_sub_county_id', $recorddata['subcounty_id'])->where('value_chain_user_loc_status', 1)->where('user_id', $user_id);
        $get_users_wardlist = $this->db->get('rpt_value_chain_user_location')->result_array();
        $get_users_wardlist_array=array();

        foreach($get_users_wardlist as $wardlist)
        {
            $ward=$wardlist['ward_ids'];
            if(!in_array($ward,$get_users_wardlist_array))
            {
                array_push($get_users_wardlist_array,$ward);
            }
        }



        // $get_users_wardlist_array = explode(",", $get_users_wardlist['ward_ids']);

        $this->db->where('status', 1)->where('county_id', $recorddata['county_id'])->where('sub_county_id', $recorddata['subcounty_id'])->where_not_in('ward_id', $get_users_wardlist_array);
        $this->db->order_by('ward_name');
        $ward_list = $this->db->get('lkp_ward')->result_array();

        return array('seldata'=>$recorddata,'unsdata'=>$ward_list);

    }

    public function getvalue_chain_location()
    {
        $record_id=$_POST['record_id'];
        $valuechain_id=$_POST['valuechain_id'];

        $finaldata=array();

        $recorddata=$this->db->select('loc.lkp_county_id as county_id,loc.lkp_sub_county_id as subcounty_id,loc.lkp_ward_id as ward_id,county.name as countyname,sub.sub_county_name as subcountyname,ward.ward_name as wardname')->from('rpt_value_chain_location loc')->join('lkp_county county','county.county_id=loc.lkp_county_id')->join('lkp_sub_county sub','sub.sub_county_id=loc.lkp_sub_county_id')->join('lkp_ward ward','ward.ward_id=loc.lkp_ward_id')->where('loc.value_chain_loc_id',$record_id)->get()->row_array();
        
        $this->db->select('lkp_ward_id as ward_ids');
        $this->db->where('lkp_value_chain_id', $valuechain_id)->where('lkp_county_id', $recorddata['county_id'])->where('lkp_sub_county_id', $recorddata['subcounty_id'])->where('loc_status', 1);
        $get_users_wardlist = $this->db->get('rpt_value_chain_location')->result_array();
        $get_users_wardlist_array=array();
        foreach($get_users_wardlist as $wardlist){
            $ward=$wardlist['ward_ids'];
            if(!in_array($ward, $get_users_wardlist)){
                array_push($get_users_wardlist_array,$ward);
            }
        }

        // $get_users_wardlist_array = explode(",", $get_users_wardlist['ward_ids']);

        $this->db->where('status', 1)->where('county_id', $recorddata['county_id'])->where('sub_county_id', $recorddata['subcounty_id'])->where_not_in('ward_id', $get_users_wardlist_array);
        $this->db->order_by('ward_name');
        $ward_list = $this->db->get('lkp_ward')->result_array();

        return array('seldata'=>$recorddata,'unsdata'=>$ward_list);
    }

}
