<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportdata extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');

        $this->load->model('Reports_model');
	}

    public function exportsurvey_district() {
        $form_id = $this->uri->segment(3);
        $district_list = $this->Reports_model->district_list();
        foreach ($district_list as $key => $dist) {
            $this->exportsurvey($form_id, $dist['district_id']);
        }
    }

    public function exportsurvey($form = NULL, $district = NULL){

		require(APPPATH .'third_party/PHPExcel.php');
		require(APPPATH .'third_party/PHPExcel/Writer/Excel2007.php');

		$objPHPExcel = new PHPExcel();
		
        if(is_null($form)) $form_id = $this->uri->segment(3);
        $form_id = $form;

        $table="rpt_form_".$form_id;

        $data = array(
            'form_id' => $form_id,
            'district' => $district
        );

        $title = $this->db->select('title')->from('form')->where('id', $form_id)->get()->row_array();
        if(!is_null($district)) {
            $dist_details = $this->db->select('district_name')->where('district_id', $district)->get('lkp_district')->row_array();

            $filename = $title['title']."-".$dist_details['district_name'].".xlsx";
        } else {
            $filename = $title['title'].".xlsx";
        }

        $this->load->model('Reports_model');

        $state_list = $this->Reports_model->state_list();
        $district_list = $this->Reports_model->district_list();
        $block_list = $this->Reports_model->block_list();
        $village_list = $this->Reports_model->village_list();
        $crop_types = $this->Reports_model->lkp_crop_types();
        $crops = $this->Reports_model->lkp_crops();
        $crop_intervention = $this->Reports_model->lkp_crop_intervention();
        $crop_inputname = $this->Reports_model->lkp_crop_inputname();
        $crop_varieties = $this->Reports_model->lkp_crop_varieties();

        $this->load->model('Exportsurvey_model');
        $surveydetails=$this->Exportsurvey_model->exportsurvey_data($data);

        
        $form_fields = $this->Reports_model->get_form_fields($form_id);

        $objPHPExcel->getProperties()->setCreator("");
    	$objPHPExcel->getProperties()->setLastModifiedBy("");
    	$objPHPExcel->getProperties()->setTitle("");
    	$objPHPExcel->getProperties()->setSubject("");
    	$objPHPExcel->getProperties()->setDescription("");

    	$objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Data Id');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'Submitted by');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1,'Date');    
        $metacol = 3; //columnnumber

        foreach ($form_fields as $key => $field){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, 1, $field['label']);
            $metacol++;
        }

        $row=2;
        if(count($surveydetails) > 0){
            foreach ($surveydetails as $key => $details) {
                $check_group_field = $this->db->where('type', 'group')->where('form_id', $form_id)->where('status', 1)->get('form_field')->num_rows();

                if($check_group_field > 0){
                    $grouptable = "ic_form_group_data";
                    $this->db->where('data_id', $details['data_id'])->where('form_id', $form_id)->where('data_status', 1);
                    $survey_details['group_data'] = $this->db->get($grouptable)->result_array();

                    $group_data = array();
                    $form_group_id = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where('type', 'group')->where('form_id', $form_id)->where('status', 1)->get('form_field')->row_array();

                    $form_group_id_array = explode(",", $form_group_id['field_ids']);

                    foreach ($form_group_id_array as $key => $value) {
                        $formgroup_field[$key]['group_id'] = $value;
                        $grouplabel = $this->db->select('label')->where('field_id', $value)->where('status', 1)->where('form_id', $form_id)->get('form_field')->row_array();
                        $formgroup_field[$key]['group_label'] = $grouplabel['label'];

                        $get_childfieldids = $this->db->select('child_id')->where('field_id', $value)->get('form_field')->row_array();

                        $get_childfieldids_array = explode(",", $get_childfieldids['child_id']);

                        $this->db->select('field_id, label, name, type, multiple, required, parent_id, maxlength, subtype');
                        $this->db->where_in('field_id', $get_childfieldids_array)->where('status', 1)->where('form_id', $form_id);
                        $formgroup_field[$key]['group_fields'] = $this->db->get('form_field')->result_array();

                        $group_data[$value] = $this->db->where('groupfield_id', $value)->where('data_id', $details['data_id'])->where('data_status', 1)->get($grouptable)->result_array();
                    }

                    $formgroup_field=$formgroup_field;
                    $group_data=$group_data;
                }

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, (($details['data_id']==null || $details['data_id']=="")? "N/A": $details['data_id']) );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, (($details['username']==null || $details['username']=="")? "N/A": $details['username']) );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row,(($details['reg_date_time']==null || $details['reg_date_time']=="")? "N/A": $details['reg_date_time']) );
        		$data_metacol=3;

                $jsondata = json_decode($details['form_data'], true);

                foreach ($form_fields as $key => $formfield) {
                    $news = 'field_'.$formfield['field_id'];

                    switch ($formfield['type']) {
                        case 'lkp_crop_types':
                            if(isset($jsondata[$column])){
                                if($jsondata[$column] == NULL || $jsondata[$column] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($crop_types as $key => $ctype) {
                                        if($jsondata[$column] == $ctype['type_id']){
                                            $data = $ctype['type_name'];
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_crops':
                            if(isset($jsondata[$column])){
                                if($jsondata[$column] == NULL || $jsondata[$column] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($crops as $key => $crop) {
                                        if($jsondata[$column] == $crop['crop_id']){
                                            $data = $crop['crop_name'];
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_crop_intervention':
                            if(isset($jsondata[$column])){
                                if($jsondata[$column] == NULL || $jsondata[$column] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($crop_intervention as $key => $crop_int) {
                                        if($jsondata[$column] == $crop_int['intervention_id']){
                                            $data = $crop_int['intervention_name'];
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_crop_inputname':
                            if(isset($jsondata[$column])){
                                if($jsondata[$column] == NULL || $jsondata[$column] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($crop_inputname as $key => $crop_input) {
                                        if($jsondata[$column] == $crop_input['inputname_id']){
                                            $data = $crop_input['inputname_name'];
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_crop_varieties':
                            if(isset($jsondata[$column])){
                                if($jsondata[$column] == NULL || $jsondata[$column] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($crop_varieties as $key => $variety) {
                                        if($jsondata[$column] == $variety['variety_id']){
                                            $data = $variety['variety_name'];
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_state':
                            if(isset($jsondata[$news])){
                                if($jsondata[$news] == NULL || $jsondata[$news] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($state_list as $key => $state) {
                                        if($jsondata[$news] == $state['state_id']){
                                            $data =  $state['state_name'];

                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_district':
                            if(isset($jsondata[$news])){
                                if($jsondata[$news] == NULL || $jsondata[$news] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($district_list as $key => $district) {
                                        if($jsondata[$news] == $district['district_id']){
                                            $data = $district['district_name'];

                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_block':
                            if(isset($jsondata[$news])){
                                if($jsondata[$news] == NULL || $jsondata[$news] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($block_list as $key => $block) {
                                        if($jsondata[$news] == $block['block_id']){
                                            $data = $block['block_name'];

                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        case 'lkp_village':
                            if(isset($jsondata[$news])){
                                if($jsondata[$news] == NULL || $jsondata[$news] == ''){
                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                                }else{
                                    foreach ($village_list as $key => $village) {
                                        if($jsondata[$news] == $village['village_id']){
                                            $data = $village['village_name'];

                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                                        }
                                    }
                                }
                            }else{
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, "N/A");
                            }
                            break;

                        default:
                            if(isset($jsondata[$news])){
                                $data=$jsondata[$news];                                                           
                            }else{
                                $data= "N/A";
                            }

                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol, $row, $data);
                            break;
                    }
                    $data_metacol++;
                }
                if(isset($formgroup_field) && count($formgroup_field) > 0){
                    foreach ($formgroup_field as $key => $group) {
                        $colarray=array();
                        $data_metacol++;
                    
                        foreach ($group['group_fields'] as $key => $groupfield) {
                           
                            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($data_metacol).$row;

                            $objPHPExcel->getActiveSheet()->getStyle($columnLetter)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($data_metacol,$row,$groupfield['label']);
                            array_push($colarray,$data_metacol);
                            $data_metacol++;
                        }
                        $row++;

                        if(count($group_data[$group['group_id']]) > 0){
                            foreach ($group_data[$group['group_id']] as $key => $groupdata) {
                                $json_groupdata = json_decode($groupdata['formgroup_data'], true);
                                foreach ($group['group_fields'] as $fieldkey => $groupfield) {
                                    $field = "field_".$groupfield['field_id'];

                                    switch ($groupfield['type']) {
                                        case 'lkp_crop_types':
                                            if(isset($json_groupdata[$field])){
                                                if($json_groupdata[$field] == NULL || $json_groupdata[$field] == ''){
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                                }else{
                                                    foreach ($crop_types as $key => $ctype) {
                                                        if($json_groupdata[$field] == $ctype['type_id']){
                                                            $gdata = $ctype['type_name'];

                                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, $gdata);
                                                        }
                                                    }
                                                }
                                            }else{
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                            }
                                            break;

                                        case 'lkp_crops':
                                            if(isset($json_groupdata[$field])){
                                                if($json_groupdata[$field] == NULL || $json_groupdata[$field] == ''){
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                                }else{
                                                    foreach ($crops as $key => $crop) {
                                                        if($json_groupdata[$field] == $crop['crop_id']){
                                                            $gdata = $crop['crop_name'];

                                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, $gdata);
                                                        }
                                                    }
                                                }
                                            }else{
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                            }
                                            break;

                                        case 'lkp_crop_intervention':
                                            if(isset($json_groupdata[$field])){
                                                if($json_groupdata[$field] == NULL || $json_groupdata[$field] == ''){
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                                }else{
                                                    foreach ($crop_intervention as $key => $crop_int) {
                                                        if($json_groupdata[$field] == $crop_int['intervention_id']){
                                                            $gdata = $crop_int['intervention_name'];

                                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, $gdata);
                                                        }
                                                    }
                                                }
                                            }else{
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                            }
                                            break;

                                        case 'lkp_crop_inputname':
                                            if(isset($json_groupdata[$field])){
                                                if($json_groupdata[$field] == NULL || $json_groupdata[$field] == ''){
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                                }else{
                                                    foreach ($crop_inputname as $key => $crop_input) {
                                                        if($json_groupdata[$field] == $crop_input['inputname_id']){
                                                            $gdata = $crop_input['inputname_name'];

                                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, $gdata);
                                                        }
                                                    }
                                                }
                                            }else{
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                            }
                                            break;

                                        case 'lkp_crop_varieties':
                                            if(isset($json_groupdata[$field])){
                                                if($json_groupdata[$field] == NULL || $json_groupdata[$field] == ''){
                                                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                                }else{
                                                    foreach ($crop_varieties as $key => $variety) {
                                                        if($json_groupdata[$field] == $variety['variety_id']){
                                                            $gdata = $variety['variety_name'];

                                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, $gdata);
                                                        }
                                                    }
                                                }
                                            }else{
                                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                                            }
                                            break;

                                        default:
                                            if(isset($json_groupdata[$field])){
                                                $gdata = $json_groupdata[$field];                                                
                                            }else{
                                                $gdata= "N/A";
                                            }
                                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, $gdata);
                                            break;
                                    }
                                }
                                $row++; 
                            }
                        }else{
                            foreach ($group['group_fields'] as $fieldkey => $groupfield) {
                                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colarray[$fieldkey],$row, "N/A");
                            }
                            $row++;
                        }
                    }
                }
               
                $row++;
            } 
        }

        $objPHPExcel->getActiveSheet()->setTitle('survey');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //$objWriter->save('php://output');
        $objWriter->save(getcwd()."/uploads/data/".$filename);
    }


    public function beneficary_dataexport(){
        require(APPPATH .'third_party/PHPExcel.php');
        require(APPPATH .'third_party/PHPExcel/Writer/Excel2007.php');

        $survey_id=$this->uri->segment(3);
        
        $form_data = $this->db->select('id ,title')->where('id',$survey_id)->get('form')->row_array();
        
        //get survey fields
        $this->db->select('field_id, label, type, status');
        $this->db->where('form_id',$form_data['id']);
        $this->db->order_by('slno');
        $form_fields = $this->db->get('form_field')->result_array();
        
        //get options of all the fields
        $this->db->select('form_field.label as field_label, form_field_multiple.label as multiple_label, form_field.field_id, form_field.type, form_field.label as field_label, form_field_multiple.status as status, multi_id'); 
        $this->db->from('form_field_multiple');
        $this->db->join('form_field', 'form_field.field_id = form_field_multiple.field_id' );
        $this->db->where('form_field.form_id', $form_data['id']);
        $form_multiples = $this->db->get()->result_array();                 

        //get list of all lookup tables
        $this->db->distinct()->select('type');
        $this->db->where('form_id',$form_data['id'])->like('type','lkp_');
        $get_unique_lkp_tables = $this->db->get('form_field')->result_array();

        $get_unique_lkp_tables_array = array();
        foreach($get_unique_lkp_tables as $table){
            array_push($get_unique_lkp_tables_array, $table['type']);
        }

        $objPHPExcel = new PHPExcel();
        
        $objPHPExcel->getProperties()->setCreator("Measure");
        $objPHPExcel->getProperties()->setLastModifiedBy("");
        $objPHPExcel->getProperties()->setTitle("Survey data - OLM");
        $objPHPExcel->getProperties()->setSubject("");
        
        //print of survey fields
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'Field Id');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'Field Type');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1,'Label'); 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1,'Status'); 
        $metacol = 2; //columnnumber

        foreach ($form_fields as $key => $field){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $metacol, 'field_'.$field['field_id']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $metacol, $field['type']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $metacol, $field['label']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $metacol, $field['status']);
            $metacol++;
        }
        $objPHPExcel->getActiveSheet()->setTitle("form_fields"); //formfields

        $i = 1;

        //print of the options related to survey 
        if(count($form_multiples)){
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($i);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Field Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Label'); 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Type');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Option Id');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Type Label');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Status');
            $metacol = 2; //columnnumber
            foreach($form_multiples as $multiples){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $metacol, $multiples['field_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $metacol, $multiples['field_label']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $metacol, $multiples['type']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $metacol, $multiples['multi_id']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $metacol, $multiples['multiple_label']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $metacol, $multiples['status']);
                $metacol++;
            }
            $objPHPExcel->getActiveSheet()->setTitle("form_field_multiple");
        }

        //print of data related to all lookup tables
        if(count($get_unique_lkp_tables_array)){
            foreach($get_unique_lkp_tables_array as $tbl){
                $i++;

                $lkp_table_columns = $this->db->list_fields($tbl);
               
                $objPHPExcel->createSheet();
                $objPHPExcel->setActiveSheetIndex($i);
                $column_col = 0;
                foreach ($lkp_table_columns as $key => $field){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column_col, 1, $field);
                    $column_col++;
                }

                $lkp_table = $this->db->select('*')->get($tbl)->result_array();

                $row=2;         
                foreach ($lkp_table as $key => $data) {
                    $metacol = 0;

                    foreach ($lkp_table_columns as $key => $column) {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, $row, $data[$column]);
                        $metacol++;
                    }
                    $row++;
                }
                $objPHPExcel->getActiveSheet()->setTitle($tbl); //$g[type]
            }
        }

        $table_name = "ic_form_data";
        $i++;
        //to check survey contains any group field
        $this->db->select('field_id');
        $this->db->where('type', 'group')->where('form_id', $survey_id)->where('status', 1);
        $check_group_field = $this->db->get('form_field')->num_rows();

        //printing of the data related to survey table
        $surveytable_columns = $this->db->list_fields($table_name);

        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($i);

        $form_fields = $this->Reports_model->get_form_fields($survey_id);

        $metacol = 0;
        foreach ($surveytable_columns as $key => $field){
            if($field == 'form_data'){
                foreach ($form_fields as $key => $field){
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, 1, $field['label']);
                    $metacol++;
                }
            }else{
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, 1, $field);
                $metacol++;
            }
        }

        $this->db->select('*');
        $this->db->where('form_id', $survey_id)->where('project_id', 1)->where('data_status', 1);
        $this->db->order_by('id', 'DESC');
        $surveydata = $this->db->get($table_name)->result_array();

        $row=2;         
        foreach ($surveydata as $key => $data) {
            $metacol = 0;

            foreach ($surveytable_columns as $key => $column) {

                if($column == 'form_data'){
                    $jsondata = json_decode($data['form_data'], true);

                    foreach ($form_fields as $key => $field){
                        $field_var = "field_".$field['field_id'];

                        $val = isset($jsondata[$field_var]) ? $jsondata[$field_var] : "N/A";

                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, $row, $val);
                        $metacol++;
                    }
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, $row, $data[$column]);
                    $metacol++;
                }                   
            }
            $row++;
        }

        $objPHPExcel->getActiveSheet()->setTitle($table_name);

        //group data
        $this->db->select('*');
        $this->db->where('type', 'group')->where('form_id', $survey_id)->where('status', 1);
        $group_fieldinfo = $this->db->get('form_field')->result_array();

        foreach ($group_fieldinfo as $key => $value) {            

            $group_tablename = "ic_form_group_data";
            $i++;
            $surveytable_group_columns = $this->db->list_fields($group_tablename);

            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($i);

            $child_array = explode(",", $value['child_id']);

            $metacol = 0;
            foreach ($surveytable_group_columns as $key => $field){
                if($field == 'formgroup_data'){
                    foreach ($child_array as $key => $fieldid) {
                        $getlablename = $this->db->select('label')->where('field_id', $fieldid)->get('form_field')->row_array();
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, 1, $getlablename['label']);
                        $metacol++;
                    }
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, 1, $field);
                    $metacol++;
                }
            }

            $this->db->select('*');
            $this->db->where('form_id', $survey_id)->where('groupfield_id', $value['field_id'])->where('data_status', 1);
            $this->db->order_by('reg_date_time', 'DESC');
            $surveygroupdata = $this->db->get($group_tablename)->result_array();

            $row=2;         
            foreach ($surveygroupdata as $key => $groupdata) {
                $metacol = 0;

                foreach ($surveytable_group_columns as $key => $column) {

                    if($column == 'formgroup_data'){
                        $group_jsondata = json_decode($groupdata['formgroup_data'], true);

                        foreach ($child_array as $key => $fieldid){
                            $field_var = "field_".$fieldid;

                            $val = isset($group_jsondata[$field_var]) ? $group_jsondata[$field_var] : "N/A";

                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, $row, $val);
                            $metacol++;
                        }
                    }else{
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($metacol, $row, $groupdata[$column]);
                        $metacol++;
                    }                   
                }
                $row++;
            }

            $objPHPExcel->getActiveSheet()->setTitle($value['label']);
        }
        
        $filename = "beneficary_dataexport.xlsx"; 
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(getcwd()."/uploads/data/".$filename);
        //$objWriter->save('php://output');
    }

} ?>