<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporting_model extends CI_Model {
    
	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

    public function get_survey(){
        $this->db->select('*');
        $this->db->from('form');
        $this->db->where('status', 1)->where('type', 'Registration');
        $form = $this->db->get()->result_array();

        return array('form' => $form);
    }

    public function get_survey_details($form_id){
        $form_details = $this->db->where('id', $form_id)->get('form')->row_array();

        $this->db->where('form_id', $form_id)->where('parent_id', NULL)->where('parent_value', NULL)->where('status', 1);
        $this->db->order_by('slno');
        $survey_formfields = $this->db->get('form_field')->result_array();

        foreach ($survey_formfields as $key => $field) {
            if($field['type'] == 'select' || $field['type'] == 'radio-group' || $field['type'] == 'checkbox-group') {
                $this->db->where('form_id', $form_id)->where('field_id', $field['field_id'])->where('status', 1)->order_by('label');
                $survey_formfields[$key]['options'] = $this->db->get('form_field_multiple')->result_array();
            }

            if($field['type'] == 'lkp_title'){
                $this->db->where('title_status', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_title')->result_array();
            }

            if($field['type'] == 'lkp_gender'){
                $this->db->where('GENDER_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_gender')->result_array();
            }

            if($field['type'] == 'lkp_village'){
                $this->db->where('VILLAGE_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_village')->result_array();
            }

            if($field['type'] == 'lkp_ifsc'){
                $this->db->where('IFSC_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_ifsc')->result_array();
            }

            if($field['type'] == 'lkp_planting_season'){
                $this->db->where('PLANTSEA_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_planting_season')->result_array();
            }

            if($field['type'] == 'lkp_crushing_season'){
                $this->db->where('ZYEAR_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_crushing_season')->result_array();
            }

            if($field['type'] == 'lkp_category'){
                $this->db->where('CATEGORY_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_category')->result_array();
            }

            if($field['type'] == 'lkp_variety'){
                $this->db->where('VARIETY_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_variety')->result_array();
            }

            if($field['type'] == 'lkp_plot_type'){
                $this->db->where('PLOT_TYPE_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_plot_type')->result_array();
            }

            if($field['type'] == 'lkp_irrigation_source'){
                $this->db->where('IRR_SOURCE_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_irrigation_source')->result_array();
            }

            if($field['type'] == 'lkp_spacing_code'){
                $this->db->where('SPACE_CODE_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_spacing_code')->result_array();
            }

            if($field['type'] == 'lkp_crop_type'){
                $this->db->where('CROP_TYPE_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_crop_type')->result_array();
            }
            
            if($field['type'] == 'lkp_irrogation_method'){
                $this->db->where('IRR_METHOD_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_irrogation_method')->result_array();
            }
            
            if($field['type'] == 'lkp_soil_type'){
                $this->db->where('SOIL_TYPE_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_soil_type')->result_array();
            }

            if($field['type'] == 'lkp_plantation_method'){
                $this->db->where('PLANTATION_STATUS', 1);
                $survey_formfields[$key]['options'] = $this->db->get('lkp_plantation_method')->result_array();
            }
        }

        $this->db->where('COMPANY_STATUS', 1);
        $lkp_company = $this->db->get('lkp_company')->result_array();

        $this->db->where('UNIT_STATUS', 1);
        $lkp_unit = $this->db->get('lkp_unit')->result_array();

        $this->db->where('account_group_status', 1);
        $lkp_account_group_master = $this->db->get('lkp_account_group_master')->result_array();

        $result = array('survey_formfields' => $survey_formfields, 'form_details' => $form_details, 'lkp_company' => $lkp_company, 'lkp_unit' => $lkp_unit, 'lkp_account_group_master' => $lkp_account_group_master);

        return  $result;
    }
}
