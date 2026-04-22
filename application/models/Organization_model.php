<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organization_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

        public function get_farmer_data()
    {
  

       
        $limitstart=0;
        $this->db->select('GROUP_CONCAT(lkp_value_chain_id) as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->row_array();

        $this->db->distinct();
        $this->db->select('lkp_county_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_county_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $this->db->distinct();
        $this->db->select('lkp_sub_county_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_subcounty_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $this->db->distinct();
        $this->db->select('lkp_ward_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_ward_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $cidlist = array();
        $sidlist = array();
        $widlist = array();

        foreach ($get_user_county_locs as $key => $value) {
            if(!in_array($value['lkp_county_id'], $cidlist)){
                array_push($cidlist, $value['lkp_county_id']);
            }
        }

        foreach ($get_user_subcounty_locs as $key => $value) {
            if(!in_array($value['lkp_sub_county_id'], $sidlist)){
                array_push($sidlist, $value['lkp_sub_county_id']);
            }
        }

        foreach ($get_user_ward_locs as $key => $value) {
            if(!in_array($value['lkp_ward_id'], $widlist)){
                array_push($widlist, $value['lkp_ward_id']);
            }
        }

        $valuechain_ids_arrays = explode(",", $get_user_valuechains['valuechain_ids']);

        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $check_group_field = $this->db->where('type', 'group')->where('form_id', 1)->where('status', 1)->get('form_field')->num_rows();

        if($check_group_field > 0){
            $form_group_id = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where('type', 'group')->where('form_id',40)->where('status', 1)->get('form_field')->row_array();

            $form_group_id_array = explode(",", $form_group_id['field_ids']);

            $group_field = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where_in('parent_id', $form_group_id_array)->where('status', 1)->where('form_id',40)->get('form_field')->row_array();

            $group_fields_array = explode(",", $group_field['field_ids']);
        }else{
            $group_fields_array = array(0);
        }

        $form_field = $this->db->select('field_id, label, name, type, multiple, required, parent_id, maxlength, subtype')->where('form_id', 40)->where('type !=', 'header')->where('type !=', 'collapse')->where('type !=', 'group')->where('status', 1)->where_not_in('field_id', $group_fields_array)->order_by('slno')->get('form_field')->result_array();

        $this->db->select('sur.*, CONCAT(u.first_name, " ", u.last_name) as username');
        $this->db->select('county.name as county_name, subcounty.sub_county_name, ward.ward_name, value_chain.value_chain_name');
        $this->db->from('rpt_form_40 as sur');
        $this->db->join('lkp_county as county', 'sur.county = county.county_id', 'left');
        $this->db->join('lkp_sub_county as subcounty', 'sur.subcounty = subcounty.sub_county_id', 'left');
        $this->db->join('lkp_ward as ward', 'sur.ward = ward.ward_id', 'left');
        $this->db->join('lkp_value_chain as value_chain', 'sur.value_chain_id = value_chain.value_chain_id', 'left');
        // $this->db->join('lkp_school as school', 'sur.field_1009 = school.school_id', 'left');
        // $this->db->join('lkp_education as education', 'sur.field_1010 = education.education_id', 'left');
        // $this->db->join('lkp_cooperativename as cooperative', 'sur.field_1018 = cooperative.id', 'left');
        // $this->db->join('lkp_groupname as groupname', 'sur.field_1013 = groupname.id', 'left');
        // $this->db->join('lkp_learningfarm as learningfarm', 'sur.field_1014 = learningfarm.id', 'left');
        // $this->db->join('lkp_technologytype as technologytype', 'sur.field_1040 = technologytype.technologytype_id', 'left');
        // $this->db->join('lkp_dtcfarmertype as dtcfarmertype', 'sur.field_1223 = dtcfarmertype.id', 'left');
        $this->db->join('tbl_users as u', 'sur.added_by = u.user_id');
        if(isset($_POST['valuechain_id'])){
            $this->db->where('sur.value_chain_id', $_POST['valuechain_id']);
        }        
        if(isset($_POST['county_id']) && ($_POST['county_id'] != "")){
            $this->db->where('sur.county', $this->input->post('county_id'));
        }else{
            $this->db->where_in('sur.county', $cidlist);
        }
        if(isset($_POST['subcounty_id']) && ($_POST['subcounty_id'] != "") ){
            $this->db->where('sur.subcounty', $this->input->post('subcounty_id'));
        }else{
            $this->db->where_in('sur.subcounty', $sidlist);
        }
        if(isset($_POST['ward_id']) && ($_POST['ward_id'] != "")){
            $this->db->where('sur.ward', $this->input->post('ward_id'));
        }else{        
            $this->db->where_in('sur.ward', $widlist);
        }
        $this->db->where('sur.status', 1)->order_by('datetime', 'DESC');
        if(isset($_POST['last_id'])){
            $this->db->limit(500, $_POST['last_id']);
        }else{
            $this->db->limit(500, $limitstart);
        }
        $survey_details=$this->db->get()->result_array();

     
        // foreach ($survey_details as $key => $survey) {
        //     $survey_details[$key]['members_data'] = $this->db->where('survey_recordid', $survey['id'])->where('status', 1)->get('rpt_form_40_groupdata')->result_array();
        // }
       

        return  array('status' => 1, 'form_field' => $form_field, 'survey_details' => $survey_details, 'value_chain_list' => $value_chain_list);

    }
     public function get_farmer_profile_countbyvaluechain(){

        $result=array();

        $valuechain_id=$this->input->post('valuechain_id');
        $county_id=$this->input->post('county_id');
        $subcounty_id=$this->input->post('subcounty_id');
        $ward_id=$this->input->post('ward_id');


        $this->db->distinct();
        $this->db->select('lkp_county_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_county_locs = $this->db->get('rpt_value_chain_user_location')->result_array();


        $this->db->distinct();
        $this->db->select('lkp_sub_county_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_subcounty_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $this->db->distinct();
        $this->db->select('lkp_ward_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_ward_locs = $this->db->get('rpt_value_chain_user_location')->result_array();


        $cidlist = array();
        $sidlist = array();
        $widlist = array();

        foreach ($get_user_county_locs as $key => $value) {
            if(!in_array($value['lkp_county_id'], $cidlist)){
                array_push($cidlist, $value['lkp_county_id']);
            }
        }

        foreach ($get_user_subcounty_locs as $key => $value) {
            if(!in_array($value['lkp_sub_county_id'], $sidlist)){
                array_push($sidlist, $value['lkp_sub_county_id']);
            }
        }

        foreach ($get_user_ward_locs as $key => $value) {
            if(!in_array($value['lkp_ward_id'], $widlist)){
                array_push($widlist, $value['lkp_ward_id']);
            }
        }


        if($valuechain_id!="" && $county_id==""){
         
           $countys=$this->db->select('county')->where('value_chain_id',$valuechain_id)->where('status',1)->group_by('county')->get('rpt_form_40')->result_array();

           foreach ($countys as $key => $county) {

                    $countyname=$this->db->select('name')->where('county_id',$county['county'])->where_in('county_id',$cidlist)->get('lkp_county')->row_array();

                    $count=$this->db->where('value_chain_id',$valuechain_id)->where('county',$county['county'])->where_in('county',$cidlist)->where('status',1)->get('rpt_form_40')->num_rows();
                    $result[$key]['name']=$countyname['name'];
                    $result[$key]['count']=$count;
                     
            }
        }
        if($valuechain_id!="" && $county_id!="" && $subcounty_id==""){
         
           $subcountys=$this->db->select('subcounty')->where('value_chain_id',$valuechain_id)->where('county',$county_id)->where('status',1)->group_by('subcounty')->get('rpt_form_40')->result_array();
           
           foreach ($subcountys as $key => $subcounty) {

                    $subcountyname=$this->db->select('sub_county_name')->where('sub_county_id',$subcounty['subcounty'])->get('lkp_sub_county')->row_array();

                    $count=$this->db->where('value_chain_id',$valuechain_id)->where('county',$county_id)->where('subcounty',$subcounty['subcounty'])->where('status',1)->get('rpt_form_40')->num_rows();
                    $result[$key]['name']=$subcountyname['sub_county_name'];
                    $result[$key]['count']=$count;
                     
            }
        }
        if($valuechain_id!="" && $county_id!="" && $subcounty_id!=""){
         
           $wards=$this->db->select('ward')->where('value_chain_id',$valuechain_id)->where('county',$county_id)->where('subcounty',$subcounty_id)->where('status',1)->group_by('ward')->get('rpt_form_40')->result_array();
           
           foreach ($wards as $key => $ward) {

                    $wardname=$this->db->select('ward_name')->where('ward_id',$ward['ward'])->get('lkp_ward')->row_array();

                    $count=$this->db->where('value_chain_id',$valuechain_id)->where('county',$county_id)->where('subcounty',$subcounty_id)->where('ward',$ward['ward'])->where('status',1)->get('rpt_form_40')->num_rows();
                    $result[$key]['name']=$wardname['ward_name'];
                    $result[$key]['count']=$count;
                     
            }
        }

           if($valuechain_id!="" && $county_id!="" && $subcounty_id!="" && $ward_id){
         
                    $wardname=$this->db->select('ward_name')->where('ward_id',$ward_id)->get('lkp_ward')->row_array();

                    $count=$this->db->where('value_chain_id',$valuechain_id)->where('county',$county_id)->where('subcounty',$subcounty_id)->where('ward',$ward_id)->where('status',1)->get('rpt_form_40')->num_rows();
                    $result[$key]['name']=$wardname['ward_name'];
                    $result[$key]['count']=$count;
                     
        }

        return $result;



    }
    public function get_farmer_datalocation(){
        $this->db->distinct();
        $this->db->select('lkp_county_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_county_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $this->db->distinct();
        $this->db->select('lkp_sub_county_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_subcounty_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $this->db->distinct();
        $this->db->select('lkp_ward_id');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('lkp_value_chain_id', $_POST['valuechain_id']);
        }
        $get_user_ward_locs = $this->db->get('rpt_value_chain_user_location')->result_array();

        $cidlist = array();
        $sidlist = array();
        $widlist = array();

        foreach ($get_user_county_locs as $key => $value) {
            if(!in_array($value['lkp_county_id'], $cidlist)){
                array_push($cidlist, $value['lkp_county_id']);
            }
        }

        foreach ($get_user_subcounty_locs as $key => $value) {
            if(!in_array($value['lkp_sub_county_id'], $sidlist)){
                array_push($sidlist, $value['lkp_sub_county_id']);
            }
        }

        foreach ($get_user_ward_locs as $key => $value) {
            if(!in_array($value['lkp_ward_id'], $widlist)){
                array_push($widlist, $value['lkp_ward_id']);
            }
        }

        $this->db->select('loc.lat, loc.lng, loc.address, vc.value_chain_name');
        $this->db->from('rpt_formdata_location as loc');
        $this->db->join('rpt_form_40 as sur', 'sur.id = loc.survey_id');
        $this->db->join('lkp_value_chain as vc', 'vc.value_chain_id = sur.value_chain_id');
        $this->db->where('form_id', 40)->where('sur.status', 1)->where('loc.status', 1);
        if(isset($_POST['valuechain_id'])){
            $this->db->where('sur.value_chain_id', $_POST['valuechain_id']);
        }        
        if(isset($_POST['county_id']) && ($_POST['county_id'] != "")){
            $this->db->where('sur.county', $this->input->post('county_id'));
        }else{
            $this->db->where_in('sur.county', $cidlist);
        }
        if(isset($_POST['subcounty_id']) && ($_POST['subcounty_id'] != "") ){
            $this->db->where('sur.subcounty', $this->input->post('subcounty_id'));
        }else{
            $this->db->where_in('sur.subcounty', $sidlist);
        }
        if(isset($_POST['ward_id']) && ($_POST['ward_id'] != "")){
            $this->db->where('sur.ward', $this->input->post('ward_id'));
        }else{        
            $this->db->where_in('sur.ward', $widlist);
        }
        $location = $this->db->get()->result_array();

        $survey_locations = array();
        foreach ($location as $key => $value) {
            if($value['lat'] != NULL || $value['lng'] != NULL){
                $address = ($value['address'] == '' || $value['address'] == NULL) ? "N/A" : $value['address'];
                $data = "<h5 class='title'>".$value['value_chain_name']."</h5><h5>Address : ".$address."</h5>";
                array_push($survey_locations, array($value['lat'], $value['lng'], $data) );
            }
        }
        return $survey_locations;
    }
        public function get_usersurvey_byvaluechain()
    {
        $this->db->select('GROUP_CONCAT(lkp_value_chain_id) as valuechain_ids');
        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->row_array();

        $valuechain_ids_arrays = explode(",", $get_user_valuechains['valuechain_ids']);

        $this->db->where_in('value_chain_id', $valuechain_ids_arrays)->where('status', 1);
        $value_chain_list = $this->db->get('lkp_value_chain')->result_array();

        $this->db->select('f.id, f.title, vc.value_chain_name, vc.value_chain_id');
        $this->db->from('rpt_form_relation as fr');
        $this->db->join('form as f', 'f.id = fr.form_id');
        $this->db->join('lkp_value_chain as vc', 'vc.value_chain_id = fr.lkp_value_chain_id');
        $this->db->where('f.status',1)->where('type_id', 4);
        if($this->uri->segment(3)!=NULL && $this->uri->segment(3)!=""){
            $this->db->where('lkp_value_chain_id', $this->uri->segment(3));
        }else{
            $this->db->where_in('lkp_value_chain_id', $valuechain_ids_arrays);
        }
        $this->db->where('relation_status', 1);
        $this->db->order_by('vc.value_chain_name, f.title');
        $form = $this->db->get()->result_array();

        foreach ($form as $key => $value) {

            $this->db->select('lkp_county_id');
            $this->db->from('rpt_value_chain_user_location');
            $this->db->where('user_id', $this->session->userdata('login_id'))->where('lkp_value_chain_id',$value['value_chain_id']);
            $counties = $this->db->group_by('lkp_county_id')->get()->result_array();

            if(count($counties)>0){
                $users_valuechain_county=array();
                foreach ($counties as $ckey => $county) {
                    array_push($users_valuechain_county,$county['lkp_county_id']);
                }
            }
            else{
                $users_valuechain_county=array(0);
            }


            $this->db->select('lkp_sub_county_id');
            $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
            $this->db->where('lkp_value_chain_id',$value['value_chain_id']);
            $subcounties= $this->db->get('rpt_value_chain_user_location')->result_array();

            if(count($subcounties)>0){
                $users_valuechain_subcounty=array();
                foreach ($subcounties as $skey => $subcounty) {
                    array_push($users_valuechain_subcounty,$subcounty['lkp_sub_county_id']);
                }
            }
            else{
                $users_valuechain_subcounty=array(0);
            }

            $this->db->select('lkp_ward_id');
            $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_loc_status', 1);
            $this->db->where('lkp_value_chain_id',$value['value_chain_id']);
            $wards= $this->db->get('rpt_value_chain_user_location')->result_array();

            if(count($wards)>0){
                $users_valuechain_wards=array();
                foreach ($wards as $wkey => $ward) {
                    array_push($users_valuechain_wards,$ward['lkp_ward_id']);
                }
            }
            else{
                $users_valuechain_wards=array(0);
            }



            $table = "rpt_form_".$value['id'];

            $this->db->where('status', 1);
            $form[$key]['upload_count'] = $this->db->get($table)->num_rows();
        }

        return array('form' => $form, 'value_chain_list' => $value_chain_list);
    }
    public function get_surveydetails($form_id,$value_chain_id)
    {
        $tablename = "rpt_form_".$form_id;
        $userid=$this->session->userdata('login_id');
        $county_id=$this->input->post('county_id');
        $subcounty_id=$this->input->post('subcounty_id'); 
        $ward_id=$this->input->post('ward_id');
        $limitstart=0;
        $this->db->select('sur.*, CONCAT(u.first_name, " ", u.last_name) as username');
        $this->db->select('county.name as county_name, subcounty.sub_county_name, ward.ward_name, value_chain.value_chain_name');
        $this->db->from(''.$tablename.' as sur');
        $this->db->join('lkp_county as county', 'sur.county = county.county_id', 'left');
        $this->db->join('lkp_sub_county as subcounty', 'sur.subcounty = subcounty.sub_county_id', 'left');
        $this->db->join('lkp_ward as ward', 'sur.ward = ward.ward_id', 'left');
        $this->db->join('lkp_value_chain as value_chain', 'sur.valuechain_id = value_chain.value_chain_id', 'left');
        $this->db->join('tbl_users as u', 'sur.added_by = u.user_id');
        $this->db->where('sur.status', 1)->order_by('datetime', 'DESC');
        if(isset($_POST['last_id'])){
            $lastid=$_POST['last_id'];
            $this->db->limit(500,$lastid);
        }else{
            $this->db->limit(500,$limitstart);
        }
        $survey_details =$this->db->get()->result_array();
        foreach ($survey_details as $key => $survey) {
            $this->db->select('image')->where('survey_id', $survey['id'])->where('form_id', $form_id)->limit(1);;
            $survey_details[$key]['images'] = $this->db->get('rpt_formdata_image')->result_array();

            $check_group_field = $this->db->where('type', 'group')->where('form_id', $form_id)->where('status', 1)->get('form_field')->num_rows();
            if($check_group_field > 0){
                $grouptable = "rpt_form_".$form_id."_groupdata";

                $survey_details[$key]['members_data'] = $this->db->where('survey_recordid', $survey['id'])->where('status', 1)->get($grouptable)->result_array();
            }
        }
        //print_r($survey_details);die();
        return $survey_details;
    }
     public function get_survey_locations($survey_details, $form_id, $value_chain_id)
    {
        $survey_locations = array();
        $table_name = "rpt_form_".$form_id;

        $userid = $this->session->userdata('login_id');

        $this->db->from('rpt_formdata_location as loc');
        $this->db->join($table_name.' as sur', 'sur.id = loc.survey_id');
        $this->db->where('form_id', $form_id)->where('sur.status', 1)->where('loc.status', 1);
        $location = $this->db->get()->result_array();
        
        if(count($location) > 0){
            foreach ($location as $key => $loc) {
                if($loc['lat'] != NULL || $loc['lng'] != NULL){
                    $this->db->where('id', $form_id)->where('status', 1);
                    $form_name = $this->db->get('form')->row_array();

                    $address = ($loc['address'] == '' || $loc['address'] == NULL) ? "N/A" : $loc['address'];
                    $data = "<h5 class='title'>".$form_name['title']."</h5><h5>Address : ".$address."</h5>";
                    array_push($survey_locations, array($loc['lat'],$loc['lng'],$data) );
                }
            }            
        }
        return $survey_locations;
    }      
}