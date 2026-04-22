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

        $olm_number = 0;
        $householdmale_count = 0;
        $householdfemale_count = 0;

        $member_cooperative = 0;

      
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
        $farmer_registrations_data = $this->db->get('ic_form_data')->result_array();
        foreach ($farmer_registrations_data as $key => $value) {

            $data_array = json_decode($value['form_data'], true); 
            if(isset($data_array['field_1671']) && $data_array['field_1671'] != NULL){
                $olm_number++;
            }

            if(isset($data_array['field_1756']) && $data_array['field_1756'] == 1){
                $member_cooperative++;
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
        }

        $poultry_details = array();
        $this->db->select('sum(chickens) as chickens, sum(ducks) as ducks, sum(geese) as geese, sum(turkeys) as turkeys, sum(others) as others');
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $this->db->where('status', 1);
        $poultry_data = $this->db->get('dashboard_poultry_details')->row_array();
        $poultry_details['Chickens'] = $poultry_data['chickens'];
        $poultry_details['Ducks'] = $poultry_data['ducks'];
        $poultry_details['Geese'] = $poultry_data['geese'];
        $poultry_details['Turkeys'] = $poultry_data['turkeys'];
        $poultry_details['Others'] = $poultry_data['others'];
        

        $livestock_details = array();
        $this->db->select('sum(buffalo) as buffalo, sum(cows) as cows, sum(bulls_or_oxen) as bulls_or_oxen, sum(goat) as goat, sum(sheep) as sheep, sum(pig) as pig, sum(others) as others');
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $this->db->where('status', 1);
        $livestock_data = $this->db->get('dashboard_livestock_details')->row_array();
        $livestock_details['Buffalo'] = $livestock_data['buffalo'];
        $livestock_details['Cows'] = $livestock_data['cows'];
        $livestock_details['Bulls/Oxen'] = $livestock_data['bulls_or_oxen'];
        $livestock_details['Goat'] = $livestock_data['goat'];
        $livestock_details['Sheep'] = $livestock_data['sheep'];
        $livestock_details['Pig'] = $livestock_data['pig'];
        $livestock_details['Others'] = $livestock_data['others'];

        $this->db->select('sum(homestead) as homestead, sum(block_plantation) as block_plantation, sum(bund_plantation) as bund_plantation');
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $this->db->where('status', 1);
        $coconut_plantation_data = $this->db->get('dashboard_coconutplantation_details')->row_array();
        $nature_coconut_plantation_graph = array();

        array_push($nature_coconut_plantation_graph, array(
            'name' => 'Homestead',
            'count' => $coconut_plantation_data['homestead']
        ));

        array_push($nature_coconut_plantation_graph, array(
            'name' => 'Block plantation',
            'count' => $coconut_plantation_data['block_plantation']
        ));

        array_push($nature_coconut_plantation_graph, array(
            'name' => 'Bund plantation',
            'count' => $coconut_plantation_data['bund_plantation']
        ));

        $this->db->select('sum(green_gram) as green_gram, sum(black_gram) as black_gram, sum(vegetables) as vegetables, sum(others) as others, sum(paddy) as paddy, sum(sunflower) as sunflower, sum(chickpea) as chickpea, sum(groundnut) as groundnut, sum(onion) as onion, sum(pea) as pea, sum(not_cultivated) as not_cultivated, sum(dont_remember) as dont_remember');
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $this->db->where('status', 1);
        $rabicrop_data = $this->db->get('dashboard_rabicrop_details')->row_array();
        $rabicrop_details_graph = array();

        array_push($rabicrop_details_graph, array(
            'name' => 'Green gram',
            'count' => $rabicrop_data['green_gram']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Black gram',
            'count' => $rabicrop_data['black_gram']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Vegetables',
            'count' => $rabicrop_data['vegetables']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Others',
            'count' => $rabicrop_data['others']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Paddy',
            'count' => $rabicrop_data['paddy']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Sun flower',
            'count' => $rabicrop_data['sunflower']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Chick pea',
            'count' => $rabicrop_data['chickpea']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Groundnut',
            'count' => $rabicrop_data['groundnut']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Onion',
            'count' => $rabicrop_data['onion']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Pea',
            'count' => $rabicrop_data['pea']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Not Cultivated',
            'count' => $rabicrop_data['not_cultivated']
        ));
        array_push($rabicrop_details_graph, array(
            'name' => 'Dont Remember',
            'count' => $rabicrop_data['dont_remember']
        ));

        $this->db->select('sum(paddy) as paddy, sum(pearl_millet) as pearl_millet, sum(maize) as maize, sum(chillies) as chillies, sum(peas) as peas, sum(french_beans) as french_beans, sum(others) as others, sum(groundnut) as groundnut, sum(not_cultivated) as not_cultivated, sum(sweet_potato) as sweet_potato, sum(pigeon_pea) as pigeon_pea, sum(onion) as onion, sum(finger_millet) as finger_millet, sum(vegetables) as vegetables, sum(dont_remember) as dont_remember');
        if(isset($data['district_ids']) && count($data['district_ids']) > 0){
            $this->db->where_in('district_id', $data['district_ids']);
        }
        if(isset($data['block_ids']) && count($data['block_ids']) > 0){
            $this->db->where_in('block_id', $data['block_ids']);
        }
        if(isset($data['village_ids']) && count($data['village_ids']) > 0){
            $this->db->where_in('village_id', $data['village_ids']);
        }
        $this->db->where('status', 1);
        $kharifcrop_data = $this->db->get('dashboard_kharifcrop_details')->row_array();
        $kharifcrop_details_graph = array();

        array_push($kharifcrop_details_graph, array(
            'name' => 'Paddy',
            'count' => $kharifcrop_data['paddy']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Pearl millet',
            'count' => $kharifcrop_data['pearl_millet']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Maize',
            'count' => $kharifcrop_data['maize']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Chillies',
            'count' => $kharifcrop_data['chillies']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Peas',
            'count' => $kharifcrop_data['peas']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'French bean',
            'count' => $kharifcrop_data['french_beans']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Others',
            'count' => $kharifcrop_data['others']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Groundnut',
            'count' => $kharifcrop_data['groundnut']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Not Cultivated',
            'count' => $kharifcrop_data['not_cultivated']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Sweet potato',
            'count' => $kharifcrop_data['sweet_potato']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Pigeon pea',
            'count' => $kharifcrop_data['pigeon_pea']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Onion',
            'count' => $kharifcrop_data['onion']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Finger millet',
            'count' => $kharifcrop_data['finger_millet']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Vegetables',
            'count' => $kharifcrop_data['vegetables']
        ));
        array_push($kharifcrop_details_graph, array(
            'name' => 'Dont Remember',
            'count' => $kharifcrop_data['dont_remember']
        ));

        $result = array('farmer_registrations' => $farmer_registrations, 'soil_samples' => $soil_samples, 'activity_uploads' => $activity_uploads, 'visit_uploads' => $visit_uploads, 'survey_uploads' => $survey_uploads, 'activity_survey_details' => $activity_survey_details, 'datauploaded_villageslist' => $datauploaded_villageslist, 'olm_number' => $olm_number, 'householdmale_count' => $householdmale_count, 'householdfemale_count' => $householdfemale_count, 'poultry_details' => $poultry_details, 'livestock_details' => $livestock_details, 'nature_coconut_plantation_graph' => $nature_coconut_plantation_graph, 'rabicrop_details_graph' => $rabicrop_details_graph, 'kharifcrop_details_graph' => $kharifcrop_details_graph, 'member_cooperative' => $member_cooperative);

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
