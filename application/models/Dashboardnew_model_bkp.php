<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardnew_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();  
    }

    public function dashboard($data)
    {
        $this->db->select('data_id');
        $this->db->where('form_id', 1)->where('data_status', 1);
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $farmer_registrations = $this->db->get('ic_form_data')->num_rows();

        $this->db->select('data_id');
        $this->db->where('form_id', 13)->where('data_status', 1);
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $soil_samples = $this->db->get('ic_form_data')->num_rows();

        $this->db->select('GROUP_CONCAT(id) as form_ids');
        $this->db->where('type', 'Activity')->where('status', 1);
        $activity_formids = $this->db->get('form')->row_array();

        $activity_survey_details = array();

        if($activity_formids == NULL){
            $activity_uploads = 0;
        }else{
            $activity_formids_array = explode(",", $activity_formids['form_ids']);

            $this->db->select('data_id');
            $this->db->where_in('form_id', $activity_formids_array)->where('data_status', 1);
            if(isset($data['district_ids']) && count($data['district_ids']) > 0){
                $this->db->where_in('district_id', $data['district_ids']);
            }
            if(isset($data['block_ids']) && count($data['block_ids']) > 0){
                $this->db->where_in('block_id', $data['block_ids']);
            }
            if(isset($data['village_ids']) && count($data['village_ids']) > 0){
                $this->db->where_in('village_id', $data['village_ids']);
            }
            $activity_uploads = $this->db->get('ic_form_data')->num_rows();

            foreach ($activity_formids_array as $key => $act) {
                $activity_survey_details[$key]['activity_id'] = $act;

                $activity_name = $this->db->select('title')->where('id', $act)->get('form')->row_array();
                $activity_survey_details[$key]['activity_name'] = $activity_name['title'];

                $this->db->distinct();
                $this->db->select('village_id');
                $this->db->where('data_status', 1)->where('village_id IS NOT NULL');
                if(isset($data['district_ids']) && count($data['district_ids']) > 0){
                    $this->db->where_in('district_id', $data['district_ids']);
                }
                if(isset($data['block_ids']) && count($data['block_ids']) > 0){
                    $this->db->where_in('block_id', $data['block_ids']);
                }
                if(isset($data['village_ids']) && count($data['village_ids']) > 0){
                    $this->db->where_in('village_id', $data['village_ids']);
                }
                $unique_villages = $this->db->get('ic_form_data')->result_array();
                
                $datauploaded_villageslist = array();
                foreach ($unique_villages as $dkey => $dist) {
                    $this->db->select('village_id, village_name')->where('village_status', 1)->where('village_id', $dist['village_id']);
                    $village_details = $this->db->get('lkp_village')->row_array();
                    if($village_details != NULL){
                        $datauploaded_villageslist[$dkey]['village_id'] = $village_details['village_id'];
                        $datauploaded_villageslist[$dkey]['village_name'] = $village_details['village_name'];
                    }
                }

                foreach ($datauploaded_villageslist as $value) {
                    $this->db->select('data_id');
                    $this->db->where('form_id', $act)->where('data_status', 1)->where('village_id', $value['village_id']);
                    $activity_survey_details[$key][$value['village_name']] = $this->db->get('ic_form_data')->num_rows();
                }
            }
        }

        $this->db->select('GROUP_CONCAT(id) as form_ids');
        $this->db->where('type', 'Visit')->where('status', 1);
        $visit_formids = $this->db->get('form')->row_array();

        if($visit_formids == NULL){
            $visit_uploads = 0;
        }else{
            $visits_formids_array = explode(",", $visit_formids['form_ids']);

            $this->db->select('data_id');
            $this->db->where_in('form_id', $visits_formids_array)->where('data_status', 1);
            if(isset($data['district_ids']) && count($data['district_ids']) > 0){
                $this->db->where_in('district_id', $data['district_ids']);
            }
            if(isset($data['block_ids']) && count($data['block_ids']) > 0){
                $this->db->where_in('block_id', $data['block_ids']);
            }
            if(isset($data['village_ids']) && count($data['village_ids']) > 0){
                $this->db->where_in('village_id', $data['village_ids']);
            }
            $visit_uploads = $this->db->get('ic_form_data')->num_rows();
        }

        $this->db->select('GROUP_CONCAT(id) as form_ids');
        $this->db->where('type', 'Survey')->where('status', 1)->where('id !=', 13);
        $survey_formids = $this->db->get('form')->row_array();

        if($survey_formids == NULL){
            $survey_uploads = 0;
        }else{
            $survey_formids_array = explode(",", $survey_formids['form_ids']);

            $this->db->select('data_id');
            $this->db->where_in('form_id', $survey_formids_array)->where('data_status', 1);
            $survey_uploads = $this->db->get('ic_form_data')->num_rows();
        }

        $livestock_list = $this->db->select('value')->where('field_id', 1745)->where('status', 1)->get('form_field_multiple')->result_array();
        $livestock_details = array();
        foreach ($livestock_list as $key => $livestock) {
            $livestock_details[$livestock['value']] = 0;
        }

        $poultry_list = $this->db->select('value')->where('field_id', 1749)->where('status', 1)->get('form_field_multiple')->result_array();
        $poultry_details = array();
        foreach ($poultry_list as $key => $livestock) {
            $poultry_details[$livestock['value']] = 0;
        }

        $kharifcrop_list = $this->db->select('value')->where('field_id', 1717)->where('status', 1)->get('form_field_multiple')->result_array();
        $kharifcrop_details = array();
        foreach ($kharifcrop_list as $key => $kharifcrop) {
            $kharifcrop_details[$kharifcrop['value']] = 0;
        }

        $rabicrop_list = $this->db->select('value')->where('field_id', 1719)->where('status', 1)->get('form_field_multiple')->result_array();
        $rabicrop_details = array();
        foreach ($rabicrop_list as $key => $rabicrop) {
            $rabicrop_details[$rabicrop['value']] = 0;
        }

        $naturecoconut_list = $this->db->select('value')->where('field_id', 1726)->where('status', 1)->get('form_field_multiple')->result_array();
        $naturecoconut_details = array();
        foreach ($naturecoconut_list as $key => $coconut) {
            $naturecoconut_details[$coconut['value']] = 0;
        }

        $olm_number = 0;
        $householdmale_count = 0;
        $householdfemale_count = 0;

      
        $this->db->select('*');
        $this->db->where('form_id', 1)->where('data_status', 1);
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $this->db->limit(100);
        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();
        foreach ($farmer_registrations_data as $key => $value) {

            $data_array = json_decode($value['form_data'], true); 
            if(isset($data_array['field_1671']) && $data_array['field_1671'] != NULL){
                $olm_number++;
            }

            if(isset($data_array['field_1675']) && $data_array['field_1675'] != NULL){
                switch ($data_array['field_1675']) {
                    case 'Male':
                        $householdmale_count++;
                        break;

                    case 'Female':
                        $householdfemale_count++;
                        break;
                }
            }

            $check_landholding_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1702)->get('ic_form_group_data');
            if($check_landholding_details->num_rows() > 0){
                $landholdinggroupdata = $check_landholding_details->result_array();

                foreach ($landholdinggroupdata as $key => $gd_landholding) {
                    $landholding_groupdata_array = json_decode($gd_landholding['formgroup_data'], true);
                   
                    if($landholding_groupdata_array['field_1717'] != NULL && $landholding_groupdata_array['field_1717'] != ''){
                        $karif_new_data = json_decode($landholding_groupdata_array['field_1717'], true);
                        if($karif_new_data != NULL && count($karif_new_data) > 0){
                            foreach ($karif_new_data as $key => $karifcrop) {
                                if(isset($kharifcrop_details[$karifcrop])){
                                    $kharifcrop_details[$karifcrop]++;;
                                }
                            }
                        }
                        
                    }

                    if($landholding_groupdata_array['field_1719'] != NULL && $landholding_groupdata_array['field_1719'] != ''){
                        $rabi_new_data = json_decode($landholding_groupdata_array['field_1719'], true);
                        if($rabi_new_data != NULL && count($rabi_new_data) > 0){
                            foreach ($rabi_new_data as $key => $rabicrop) {
                                if(isset($rabicrop_details[$rabicrop])){
                                    $rabicrop_details[$rabicrop]++;
                                }
                            }
                        }
                    }                    
                }
            }

            $check_plantationlandparcel_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1721)->get('ic_form_group_data');
            if($check_plantationlandparcel_details->num_rows() > 0){
                $plantationlandparcel_groupdata = $check_plantationlandparcel_details->result_array();

                foreach ($plantationlandparcel_groupdata as $key => $gd_landparcel) {
                    $plantationlandparcel_groupdata_array = json_decode($gd_landparcel['formgroup_data'], true);
                   
                    if($plantationlandparcel_groupdata_array['field_1726'] != NULL && $plantationlandparcel_groupdata_array['field_1726'] != ''){
                        $cocnut_new_data = json_decode($plantationlandparcel_groupdata_array['field_1726'], true);
                        if($cocnut_new_data != NULL && count($cocnut_new_data) > 0){
                            foreach ($cocnut_new_data as $key => $coc_data) {
                                if(isset($naturecoconut_details[$coc_data])){
                                    $naturecoconut_details[$coc_data]++;
                                }
                            }
                        }                        
                    }
                }
            }

            $check_livestock_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1744)->get('ic_form_group_data');
            if($check_livestock_details->num_rows() > 0){
                $groupdata = $check_livestock_details->result_array();

                foreach ($groupdata as $key => $gd) {
                    $groupdata_array = json_decode($gd['formgroup_data'], true); 

                    if($groupdata_array['field_1745'] == 'Buffalo'){
                        $livestock_details['Buffalo']++;
                    }

                    if($groupdata_array['field_1745'] == 'Cows'){
                        $livestock_details['Cows']++;
                    }

                    if($groupdata_array['field_1745'] == 'Bulls/Oxen'){
                        $livestock_details['Bulls/Oxen']++;
                    }

                    if($groupdata_array['field_1745'] == 'Goat'){
                        $livestock_details['Goat']++;
                    }

                    if($groupdata_array['field_1745'] == 'Sheep'){
                        $livestock_details['Sheep']++;
                    }

                    if($groupdata_array['field_1745'] == 'Pig'){
                        $livestock_details['Pig']++;
                    }

                    if($groupdata_array['field_1745'] == 'Others'){
                        $livestock_details['Others']++;
                    }
                }
            }

            $check_poultry_details = $this->db->where('data_id', $value['data_id'])->where('data_status', 1)->where('groupfield_id', 1748)->get('ic_form_group_data');
            if($check_poultry_details->num_rows() > 0){
                $groupdata = $check_poultry_details->result_array();

                foreach ($groupdata as $key => $gd) {
                    $groupdata_array = json_decode($gd['formgroup_data'], true); 

                    if($groupdata_array['field_1749'] == 'Chickens'){
                        $poultry_details['Chickens']++;
                    }

                    if($groupdata_array['field_1749'] == 'Ducks'){
                        $poultry_details['Ducks']++;
                    }

                    if($groupdata_array['field_1749'] == 'Geese'){
                        $poultry_details['Geese']++;
                    }

                    if($groupdata_array['field_1749'] == 'Turkeys'){
                        $poultry_details['Turkeys']++;
                    }

                    if($groupdata_array['field_1749'] == 'Others'){
                        $poultry_details['Others']++;
                    }
                }
            }
        }

        $nature_coconut_plantation_graph = array();

        foreach ($naturecoconut_details as $key => $value) {
            array_push($nature_coconut_plantation_graph, array('name' => $key, 'count' => $value));
        }

        $rabicrop_details_graph = array();
        foreach ($rabicrop_details as $key => $rabi) {
            array_push($rabicrop_details_graph, array('name' => $key, 'count' => $rabi));
        }

        $kharifcrop_details_graph = array();
        foreach ($kharifcrop_details as $key => $kharif) {
            array_push($kharifcrop_details_graph, array('name' => $key, 'count' => $kharif));            
        }
        
        $result = array('farmer_registrations' => $farmer_registrations, 'soil_samples' => $soil_samples, 'activity_uploads' => $activity_uploads, 'visit_uploads' => $visit_uploads, 'survey_uploads' => $survey_uploads, 'activity_survey_details' => $activity_survey_details, 'datauploaded_villageslist' => $datauploaded_villageslist, 'olm_number' => $olm_number, 'householdmale_count' => $householdmale_count, 'householdfemale_count' => $householdfemale_count, 'poultry_details' => $poultry_details, 'livestock_details' => $livestock_details, 'nature_coconut_plantation_graph' => $nature_coconut_plantation_graph, 'rabicrop_details_graph' => $rabicrop_details_graph, 'kharifcrop_details_graph' => $kharifcrop_details_graph);

        return $result;
    }

    public function location_data($data)
    {

        if(isset($data['locationtype']) && $data['locationtype'] == 'activities'){
            $this->db->select('GROUP_CONCAT(id) as form_ids');
            $this->db->where('type', 'Activity')->where('status', 1);
            $activity_formids = $this->db->get('form')->row_array();

            if($activity_formids == NULL){
                $activity_formids_array = array(0);
            }else{
                $activity_formids_array = explode(",", $activity_formids['form_ids']);
            }
        }

        if(isset($data['locationtype']) && $data['locationtype'] == 'visits'){
            $this->db->select('GROUP_CONCAT(id) as form_ids');
            $this->db->where('type', 'Visit')->where('status', 1);
            $visit_formids = $this->db->get('form')->row_array();

            if($visit_formids == NULL){
                $visits_formids_array = array(0);
            }else{
                $visits_formids_array = explode(",", $visit_formids['form_ids']);
            }
        }

        if(isset($data['locationtype']) && $data['locationtype'] == 'surveys'){
            $this->db->select('GROUP_CONCAT(id) as form_ids');
            $this->db->where('type', 'Survey')->where('status', 1)->where('id !=', 13);
            $survey_formids = $this->db->get('form')->row_array();

            if($survey_formids == NULL){
                $survey_formids_array = array(0);
            }else{
                $survey_formids_array = explode(",", $survey_formids['form_ids']);
            }
        }


        $this->db->select('loc.lat, loc.lng, loc.address, f.title, concat(tu.first_name, " ", tu.last_name) as username, data.form_data, data.form_id, data.reg_date_time, ld.district_name');
        $this->db->from('ic_data_location as loc');
        $this->db->join('form as f', 'f.id = loc.form_id');
        $this->db->join('lkp_district AS ld', 'ld.district_id = loc.district_id');
        $this->db->join('ic_form_data as data', 'data.data_id = loc.data_id');
        $this->db->join('tbl_users AS tu', 'tu.user_id = data.user_id');
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('data.district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('data.block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('data.village_id', $data['village_ids']);
        }
        if(isset($data['locationtype']) && $data['locationtype'] != ''){
            switch ($data['locationtype']) {
                case 'olmfarmers_registration':
                case 'msoil_registration':
                    $this->db->where('data.form_id', $data['surveyid']);
                    break;

                case 'activities':
                    $this->db->where_in('data.form_id', $activity_formids_array);
                    break;

                case 'visits':
                    $this->db->where_in('data.form_id', $visits_formids_array);
                    break;

                case 'surveys':
                    $this->db->where_in('data.form_id', $survey_formids_array);
                    break;
                
                default:
                    $this->db->where('data.form_id IS NULL');
                    break;
            }
            
        }
        $this->db->where('loc.status', 1)->where('data.data_status', 1);
        $survey_locations = $this->db->get()->result_array();

        $location_data = array();
        foreach ($survey_locations as $key => $location) {

            switch ($location['form_id']) {
                case 1:
                    $data_array = json_decode($location['form_data'], true);

                    $household_headname = (isset($data_array['field_1673']) ? $data_array['field_1673'] : 'N/A')." ".(isset($data_array['field_1674']) ? $data_array['field_1674'] : 'N/A');

                    $beneficiary_id = (isset($data_array['field_1670'])) ? $data_array['field_1670'] : "N/A";
                    
                    $data = "<h5 class='title'>".$location['title']."</h5><h5>Household headname : ". $household_headname."</h5><h5>Beneficiary id: ". $beneficiary_id."</h5><h5>Submitted by : ".$location['username']."</h5><h5>Submitted date : ".$location['reg_date_time']."</h5><h5>Address : ".$location['address']."</h5>";
                    break;

                case 13:
                    $data_array = json_decode($location['form_data'], true);

                    $soil_array = array();

                    if(isset($data_array['field_2858']) && $data_array['field_2858'] != NULL){
                        array_push($soil_array, $data_array['field_2858']);
                    }

                    if(isset($data_array['field_2862']) && $data_array['field_2862'] != NULL){
                        array_push($soil_array, $data_array['field_2862']);
                    }

                    if(isset($data_array['field_2864']) && $data_array['field_2864'] != NULL){
                        array_push($soil_array, $data_array['field_2864']);
                    }

                    $address = ($location['address'] == '' || $location['address'] == NULL) ? "N/A" : $location['address'];

                    $uploaded_by = $location['username'];

                    $uploaded_date = $location['reg_date_time'];

                    $imgData = '';

                    $date = new DateTime($uploaded_date, new DateTimeZone('UTC'));
                    $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                    $uploaded_date = $date->format('Y-m-d H:i:s');

                    $data = $imgData ."<h5 class='title'>".$location['title']."</h5><h5>Soil id : ".implode(", ", $soil_array)."</h5><h5>Submitted by : ".$location['username']."</h5><h5>Submitted date : ".$uploaded_date."</h5>";
                    break;
                
                default:
                    $data = "<h5 class='title'>".$location['title']."</h5><h5>Submitted by : ".$location['username']."</h5>";
                    break;
            }

            array_push($location_data, array($location['lat'], $location['lng'], $data, $location['district_name']));
        }

        return $location_data;
    }

    public function district_list(){
        //district list
        $this->db->distinct();
        $this->db->select('lkp_district_id');
        $this->db->where('project_user_loc_status', 1)->where('lkp_district_id IS NOT NULL');
        $unique_districts = $this->db->get('rpt_project_partner_user_location')->result_array();
        
        $district_list = array();
        foreach ($unique_districts as $key => $dist) {
            $district_details = $this->db->select('district_id, district_name')->where('status', 1)->where('district_id', $dist['lkp_district_id'])->get('lkp_district')->row_array();
            if($district_details != NULL){
                $district_list[$key]['district_id'] = $district_details['district_id'];
                $district_list[$key]['district_name'] = $district_details['district_name'];
            }
        }

        return $district_list;
    }

    public function get_blockslist($district_id){
        //block list
        $this->db->distinct();
        $this->db->select('lkp_block_id');
        $this->db->where('project_user_loc_status', 1)->where_in('lkp_district_id', $district_id)->where('lkp_block_id IS NOT NULL');
        $unique_blocks = $this->db->get('rpt_project_partner_user_location')->result_array();
        
        $block_list = array();
        foreach ($unique_blocks as $key => $dist) {
            $block_details = $this->db->select('block_id, block_name')->where('block_status', 1)->where('block_id', $dist['lkp_block_id'])->get('lkp_block')->row_array();
            if($block_details != NULL){
                $block_list[$key]['block_id'] = $block_details['block_id'];
                $block_list[$key]['block_name'] = $block_details['block_name'];
            }
        }

        return $block_list;
    }

    public function get_villagelist($block_id){
        //block list
        $this->db->distinct();
        $this->db->select('lkp_village_id');
        $this->db->where('project_user_loc_status', 1)->where_in('lkp_block_id', $block_id)->where('lkp_village_id IS NOT NULL');
        $unique_villages = $this->db->get('rpt_project_partner_user_location')->result_array();
        
        $village_list = array();
        foreach ($unique_villages as $key => $dist) {
            $block_details = $this->db->select('village_id, village_name')->where('village_status', 1)->where('village_id', $dist['lkp_village_id'])->get('lkp_village')->row_array();
            if($block_details != NULL){
                $village_list[$key]['village_id'] = $block_details['village_id'];
                $village_list[$key]['village_name'] = $block_details['village_name'];
            }
        }

        return $village_list;
    }    

    public function farmer_data($data){
        $this->db->select('*');
        $this->db->where('form_id', 1)->where('data_status', 1);
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        if(isset($data['last_id'])){
            $this->db->where('id <', $_POST['last_id']);
        }
        $this->db->limit(100);
        $this->db->order_by('id', 'DESC');
        return $farmer_data = $this->db->get('ic_form_data')->result_array();
    }
}
