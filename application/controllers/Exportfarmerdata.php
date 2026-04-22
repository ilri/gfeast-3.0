<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportfarmerdata extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('user_agent');
    }

    public function exportfarmer_data(){
        require(APPPATH .'third_party/PHPExcel.php');
        require(APPPATH .'third_party/PHPExcel/Writer/Excel2007.php');

        $objPHPExcel = new PHPExcel();
        $limitstartid=0;
            
        $limit = 500;    
        $county_id=$this->uri->segment('5');
        $valuechain_id=$this->uri->segment('3');
        $form_id=$this->uri->segment('4');
        $subcounty_id=$this->uri->segment('6');
        $ward_id=$this->uri->segment('7');
        $userid=$this->session->userdata('login_id');
        $tablename = "rpt_form_".$form_id;

        $file_id = $this->uri->segment('8');

        $rec_id=($file_id-1)*500;
        $table="rpt_form_".$form_id;
        /*condition for export file number is not 1*/
        if($rec_id !=0){
            $this->db->select('farmer_id');
            $this->db->where('ward',$ward_id);
            $this->db->where('field_1011', $valuechain_id)->where('status',1);
            $this->db->order_by('farmer_id','desc')->limit($rec_id);
            $recids = $this->db->get($table)->result_array();

            /*For finding the array last key*/
            $key = end($recids);
            /*Based on last key finding the array Value*/
            $limitstartid = $key['farmer_id'];            
        }

        $data = array('county_id' => $county_id,
            'valuechain_id' => $valuechain_id,
            'form_id' => $form_id,
            'subcounty_id' => $subcounty_id,
            'ward_id' => $ward_id,
            'userid' => $userid,
            'limit' => $limit,/*how many records to download from limitstart id*/
            'limitstartid' => $limitstartid/*From where to start*/
        );
      
       
       $this->load->model('Exportsurvey_model');
       $surveydetails=$this->Exportsurvey_model->exportfarmer_data($data);


       $check_group_field = $this->db->where('type', 'group')->where('form_id', 1)->where('status', 1)->get('form_field')->num_rows();

        if($check_group_field > 0){
            $form_group_id = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where('type', 'group')->where('form_id', 1)->where('status', 1)->get('form_field')->row_array();

            $form_group_id_array = explode(",", $form_group_id['field_ids']);

            $group_field = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where_in('parent_id', $form_group_id_array)->where('status', 1)->where('form_id', 1)->get('form_field')->row_array();

            $group_fields_array = explode(",", $group_field['field_ids']);

            $group_data = $this->db->select('field_id,label')->where_in('parent_id', $form_group_id_array)->where('status', 1)->where('form_id', 1)->order_by('slno')->get('form_field')->result_array();
             $group_field_data = $this->db->select('field_id')->where_in('parent_id', $form_group_id_array)->where('status', 1)->where('form_id', 1)->order_by('slno')->get('form_field')->result_array();
        }else{
            $group_fields_array = array(0);
        }

        $this->db->select('field_id, label, name, type, multiple, required, parent_id, maxlength, subtype');
        $this->db->where('form_id', 1)->where('type !=', 'header')->where('type !=', 'collapse')->where('type !=', 'group')->where('status', 1)->where_not_in('field_id', $group_fields_array)->order_by('slno');
        $form_field = $this->db->get('form_field')->result_array();

        $this->db->select('field_id,label');
        $this->db->where('parent_id', 1218)->where('status', 1)->where('form_id', 1)->where('type!=','header')->order_by('slno');
        $child_group_data = $this->db->get('form_field')->result_array();
       
        $this->db->select('field_id,label');
        $this->db->where('parent_id', 1004)->where('status', 1)->where('form_id', 1)->order_by('slno');
        $member_group_data = $this->db->get('form_field')->result_array();


        foreach ($surveydetails as $key => $survey) {

            $surveyrecord_id = $survey['id'];

            $this->db->select('groupdata.*, gender.type as member_gender,CONCAT(user.first_name, " ", user.last_name) as user_name');
            $this->db->from('rpt_form_1_groupdata as groupdata');
            $this->db->join('lkp_gender as gender', 'groupdata.field_1220 = gender.id', 'left');
            $this->db->join('tbl_users as user', 'groupdata.updated_by = user.user_id','left');
            $this->db->where('groupdata.survey_recordid', $surveyrecord_id)->where('groupdata.status', 1)->where('groupdata.group_field_id', 1218);
            $surveydetails[$key]['child_data'] = $this->db->get()->result_array();


            $this->db->select('groupdata.*, gender.type as member_gender, respondentritn.relationship, age.age,CONCAT(user.first_name, " ", user.last_name) as user_name');
            $this->db->from('rpt_form_1_groupdata as groupdata');
            $this->db->join('lkp_gender as gender', 'groupdata.field_1006 = gender.id', 'left');
            $this->db->join('lkp_age as age', 'groupdata.field_1448 = age.id', 'left');
            $this->db->join('lkp_respondentritn as respondentritn', 'groupdata.field_1008 = respondentritn.id', 'left');
            $this->db->join('tbl_users as user', 'groupdata.updated_by = user.user_id','left');
            $this->db->where('groupdata.survey_recordid', $surveyrecord_id)->where('groupdata.status', 1)->where('groupdata.group_field_id', 1004);
            $surveydetails[$key]['members_data'] = $this->db->get()->result_array();

        }

      
        $form_field_array = array();    
        foreach($form_field as $formfield){
            $field = "field_".$formfield['field_id'];
            $form_field_array[$field] = $formfield['label'];
        }        

        $objPHPExcel->getProperties()->setCreator("");
        $objPHPExcel->getProperties()->setLastModifiedBy("");
        $objPHPExcel->getProperties()->setTitle("");
        $objPHPExcel->getProperties()->setSubject("");
        $objPHPExcel->getProperties()->setDescription("");

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'County' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'Subcounty' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1,'Ward' );
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1,$form_field_array['field_1000']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1,$form_field_array['field_1001']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1,$form_field_array['field_1450']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1,$form_field_array['field_1456']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1,$form_field_array['field_1451']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1,$form_field_array['field_1452']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1,$form_field_array['field_1009']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1,$form_field_array['field_1010']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1,$form_field_array['field_1002']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1,$form_field_array['field_1003']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1,$form_field_array['field_1011']);
        if($valuechain_id==1){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1,$form_field_array['field_1228']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1,$form_field_array['field_1018']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1,$form_field_array['field_1020']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1,$form_field_array['field_1021']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1,$form_field_array['field_1017']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1,$form_field_array['field_1040']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1,$form_field_array['field_1041']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 1,$form_field_array['field_1224']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, 1,$form_field_array['field_1226']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, 1,$form_field_array['field_1042']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, 1,$form_field_array['field_1227']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, 1,$form_field_array['field_1225']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, 1,$form_field_array['field_1043']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, 1,$form_field_array['field_1044']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, 1,$form_field_array['field_1045']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, 1,$form_field_array['field_1046']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, 1,$form_field_array['field_1047']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, 1,'Submitted by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, 1,'Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(33, 1,'Updated by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, 1,'Updated Date');           
        }
        if($valuechain_id == 2){ 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1,$form_field_array['field_1223']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1,$form_field_array['field_1015']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1,$form_field_array['field_1016']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1,$form_field_array['field_1229']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1,'Submitted by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1,'Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1,'Updated by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 1,'Updated Date');
        }
        if($valuechain_id == 3){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1,$form_field_array['field_1025']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1,$form_field_array['field_1023']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1,$form_field_array['field_1024']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1,'Submitted by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1,'Date');
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1,'Updated by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1,'Updated Date');
        }
        if($valuechain_id == 4){ 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1,$form_field_array['field_1012']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1,$form_field_array['field_1453']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1,$form_field_array['field_1460']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1,$form_field_array['field_1013']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1,$form_field_array['field_1014']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1,'Submitted by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1,'Date');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 1,'Updated by');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, 1,'Updated Date');
        }

        $row=2;

        foreach($surveydetails as $survey){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $survey['county_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $survey['sub_county_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $survey['ward_name']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, (($survey['field_1000'] ==  null || $survey['field_1000'] == '')  ? " N/A": $survey['field_1000']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, (($survey['field_1001'] ==  null || $survey['field_1001'] == '') ? " N/A": $survey['field_1001']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, (($survey['field_1450'] ==  null || $survey['field_1450'] == '')? " N/A": $survey['field_1450']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, (($survey['field_1456'] ==  null || $survey['field_1456'] == '') ? " N/A": $survey['field_1456']));
            if($survey['field_1451'] == '' || $survey['field_1451'] == null){
                $data= "N/A";
            }else{
                $data=($survey['field_1451'] == 1 ? 'Male' : 'Female');
            }
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row,$data);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, ($survey['field_1452'] ==  null ? " N/A": $survey['field_1452']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, (($survey['field_1009'] ==  null || $survey['field_1009'] ==  '') ? " N/A": $survey['school_choice']));
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, (($survey['field_1010'] == null || $survey['field_1010'] == '')? "N/A" : $survey['education_name']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, (($survey['field_1002'] ==  null || $survey['field_1002'] ==  '')? " N/A": $survey['field_1002']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, (($survey['field_1003'] ==  null || $survey['field_1003'] == '') ? " N/A": $survey['field_1003']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, (($survey['field_1011'] ==  null || $survey['field_1011']=='') ? " N/A": $survey['value_chain_name']));
            if($valuechain_id == 1){
                if($survey['field_1228']== '' || $survey['field_1228'] ==  null ){
                    $data== "N/A";
                }else{
                    $data=($survey['field_1228'] == 1 ? 'Yes' : 'No');
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row,$data);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row,(($survey['field_1018']== null || $survey['field_1018']== '')? 'N/A' : $survey['cooperativename']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row,(($survey['field_1020'] == null || $survey['field_1020'] == '') ? 'N/A' : $survey['field_1020']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,(($survey['field_1021'] == null || $survey['field_1021'] == '') ? 'N/A' : $survey['field_1021']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,(($survey['field_1017'] == null || $survey['field_1017'] == '') ? 'N/A' : $survey['field_1017']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row,(($survey['field_1040'] == null || $survey['field_1040'] == '') ? 'N/A' : $survey['technologytype_name']));
                if($survey['field_1041'] == '' || $survey['field_1041'] == null ){
                    $data="N/A";
                }else{
                    $data=  ($survey['field_1041'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row,$data);
                         
                if($survey['field_1224'] == '' || $survey['field_1224']==null){
                    $data= "N/A";
                }else{
                    $data=($survey['field_1224'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row,$data);
                if($survey['field_1226'] == '' || $survey['field_1226'] ==  null ){
                    $data="N/A";
                }else{
                    $data=($survey['field_1226'] == 1 ? 'Yes' : 'No') ;
                } 
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row,$data);
                          
                if($survey['field_1042'] == '' || $survey['field_1042'] ==  null ){
                    $data="N/A";
                }else{
                    $data=($survey['field_1042'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row,$data);
                         
                if($survey['field_1227'] == '' || $survey['field_1227'] ==  null ){
                    $data="N/A";
                }else{
                  $data=($survey['field_1227'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24, $row,$data);
                          
                if($survey['field_1225'] == '' || $survey['field_1225'] ==  null ){
                    $msg="N/A";
                }else{
                    $data=($survey['field_1225'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25, $row,$data);
                if($survey['field_1043'] == '' || $survey['field_1043'] == null ){
                    $data="N/A";
                }else{
                    $data=($survey['field_1043'] == 1  ? 'Yes' : 'No');
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26, $row,$data);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27, $row,(($survey['field_1044'] == null || $survey['field_1044'] == '') ? "N/A" : $survey['field_1044']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28, $row,(($survey['field_1045'] == null || $survey['field_1045'] == '') ? "N/A" : $survey['field_1045']));
                if($survey['field_1046'] == '' || $survey['field_1046'] ==  null ){
                    $data="N/A";
                }else{
                    $data=($survey['field_1046'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29, $row,$data);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30, $row,(($survey['field_1047'] == null || $survey['field_1047'] == '') ? "N/A" : $survey['field_1047']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31, $row,$survey['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(32, $row,$survey['datetime']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(33, $row,(($survey['user_name']==null||$survey['user_name']=="")?"N/A":$survey['user_name']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34, $row,(($survey['updated_date']==null ||$survey['updated_date']=="")?"N/A":$survey['updated_date']));
            }
            if($valuechain_id == 2){
                if($survey['dtcfarmertype'] == '' || $survey['dtcfarmertype']==  null ){
                    $data="N/A";
                }else{
                    $data=$survey['dtcfarmertype'];
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row,$data);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row,(($survey['field_1015'] == null || $survey['field_1015'] == '') ? 'N/A' : $survey['field_1015']));
                if($survey['field_1016'] == '' || $survey['field_1016'] == null ){
                    $data="N/A";
                }else{
                    $data=($survey['field_1016'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row,$data);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,(($survey['field_1229'] == null || $survey['field_1229'] == '') ? 'N/A' : $survey['field_1229']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,$survey['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row,$survey['datetime']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row,(($survey['user_name']==null||$survey['user_name']=="")?"N/A":$survey['user_name']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row,(($survey['updated_date']==null ||$survey['updated_date']=="")?"N/A":$survey['updated_date']));
            }
            if($valuechain_id == 3){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row,(($survey['field_1025'] == null || $survey['field_1025'] == '') ? 'N/A' : $survey['field_1025']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row,(($survey['field_1023'] == null || $survey['field_1023'] == '') ? 'N/A' : $survey['field_1023']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row,(($survey['field_1024'] == null || $survey['field_1024'] == '') ? 'N/A' : $survey['field_1024']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,$survey['username']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,$survey['datetime']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row,(($survey['user_name']==null||$survey['user_name']=="")?"N/A":$survey['user_name']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row,(($survey['updated_date']==null ||$survey['updated_date']=="")?"N/A":$survey['updated_date']));
            }
            if($valuechain_id == 4){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row,(($survey['field_1012'] == null || $survey['field_1012'] == '') ? 'N/A' : $survey['field_1012']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row,(($survey['field_1453'] == null || $survey['field_1453'] == '') ? 'N/A' : $survey['field_1453']));
            
                if($survey['field_1460'] == '' || $survey['field_1460'] == null ){
                    $data="N/A";
                }else{
                    $data=($survey['field_1460'] == 1 ? 'Yes' : 'No') ;
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row,$data);         
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,(($survey['field_1013'] == null || $survey['field_1013'] == '') ? 'N/A' : $survey['groupname']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,(($survey['field_1014'] == null || $survey['field_1014'] == '') ? 'N/A' : $survey['learningfarmname']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row,(($survey['username']==null || $survey['username']=="")? "N/A" :$survey['username']) );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row,(($survey['datetime']==null || $survey['datetime']=="")? "N/A" :$survey['datetime']) );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row,(($survey['user_name']==null||$survey['user_name']=="")?"N/A":$survey['user_name']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row,(($survey['updated_date']==null ||$survey['updated_date']=="")?"N/A":$survey['updated_date']));
            }            

            if(count($member_group_data) > 0){
                if($valuechain_id==1){
                    $head_metacol=35;
                }
                if($valuechain_id==2){
                    $head_metacol=22;
                }
                if($valuechain_id==3){
                    $head_metacol=21;
                }
                if($valuechain_id==4){
                    $head_metacol=23;
                }
                $member_colarray=array();
                foreach ($member_group_data as $key =>$group) {
                    array_push($member_colarray,$head_metacol);
                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($head_metacol).$row;
                    $objPHPExcel->getActiveSheet()->getStyle($columnLetter)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($head_metacol, $row, $group['label']);
                    $head_metacol++;
                }
                //$row++;
                if(count($survey['members_data']) > 0){
                    foreach ($survey['members_data'] as $fkey => $groupdata) {
                        $row++;
                        foreach ($member_group_data as $key =>$group) {
                            $field = "field_".$group['field_id'];  
                            if($field=='field_1006'){
                                $value=$groupdata['member_gender'];
                            }else if($field=='field_1008'){
                                $value=$groupdata['relationship'];
                            }
                            else{                   
                                $value = ($groupdata[$field] == null || $groupdata[$field] == "") ? "N/A" : $groupdata[$field];
                            }
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($member_colarray[$key], $row, $value);
                        }                        
                    }
                }else{
                    $row++;
                    foreach ($member_group_data as $key =>$group) {
                        $field = "field_".$group['field_id'];                     
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($member_colarray[$key], $row, "N/A");
                    } 
                }
            }
            //$row++;
            $head_metacol++;
            
            if(count( $child_group_data) > 0){
                $child_colarray=array();
                foreach ($child_group_data as $key =>$group) {
                    array_push($child_colarray,$head_metacol);
                    $columnLetter = PHPExcel_Cell::stringFromColumnIndex($head_metacol).$row;
                    $objPHPExcel->getActiveSheet()->getStyle($columnLetter)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($head_metacol, $row, $group['label']);
                    $head_metacol++;
                }
                //$row++;
                if(count($survey['child_data']) > 0){
                    foreach ($survey['child_data'] as $fkey => $group_data) {
                        $row++;
                        foreach ($child_group_data as $key =>$group) {
                            $field = "field_".$group['field_id'];
                            if($field=='field_1220'){
                                $value=$group_data['member_gender'];
                            }else{                     
                                $value = ($group_data[$field] == null || $group_data[$field] == "") ? "N/A" : $group_data[$field];
                            }
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($child_colarray[$key], $row, $value);
                        }                        
                    }
                }else{
                    $row++;
                    foreach ($child_group_data as $key =>$group) {
                        $field = "field_".$group['field_id'];                     
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($child_colarray[$key], $row, "N/A");
                    } 
                }
            }  
            $row++;
        }
       
        $title=$this->db->select('title')->from('form')->where('id', $form_id)->get()->row_array();
        if($county_id != ''){
            $countyname=$this->db->where('county_id',$county_id)->get('lkp_county')->row_array();
            $filename= "".$title['title'].'-'.$countyname['name'].".xlsx";
        }else{
            $filename= "".$title['title'].".xlsx";
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('survey');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

/*Based on records count displaying file buttons*/
    public function getfarmercount(){

        $limitstart=0;

        $county_id = $this->input->post('county_id');
        $valuechain_id = $this->input->post('valuechain_id');
        $form_id = $this->input->post('survey_id');
        $subcounty_id = $this->input->post('subcounty_id');
        $ward_id = $this->input->post('ward_id');
        $userid = $this->session->userdata('login_id');
        $text="";


        $data = array('county_id' => $county_id,
            'valuechain_id' => $valuechain_id,
            'form_id' => $form_id,
            'subcounty_id' => $subcounty_id,
            'ward_id' => $ward_id,
            'userid' => $userid,
            'limitstart' => $limitstart
        );
        $this->load->model('Exportsurvey_model');
        $surveydetails=$this->Exportsurvey_model->getfarmer_count($data);

        $total_count = count($surveydetails);
        $last_page = ceil($total_count/500);

         $text.='<label>List of files to download</label>
        <select class="pull-right file_Export form-control" style="margin-top:5px;">
            <option value="">Select file</option>';
         for ($i = 1; $i <= $last_page; $i++) {
                $text .='<option value="'.$i.'">File'.$i.'</option>';
            }
        $text.='</select>';    
            
        echo json_encode(array('text'=>$text,'status'=>1));die();



    }
}
?>