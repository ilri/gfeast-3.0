<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportsurvey_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function exportsurvey_data($data){
		$form_id = $data['form_id'];
        $district = $data['district'];

		
		$tablename = "ic_form_data";

        $this->db->select('sur.*, CONCAT(u.first_name, " ", u.last_name) as username');
        $this->db->from(''.$tablename.' as sur');
        $this->db->join('tbl_users as u', 'sur.user_id = u.user_id');
        if(!is_null($district)) {
            $this->db->where_in('sur.district_id', $district);
        }
        $this->db->where('sur.data_status', 1)->where('sur.form_id', $form_id);
       	$surveydetails = $this->db->order_by('sur.reg_date_time', 'DESC')->get()->result_array();

        $check_group_fields = $this->db->select('field_id')->where('type', 'group')->where('form_id',  $form_id)->where('status', 1)->get('form_field')->num_rows();
        if($check_group_fields > 0){
            $get_group_id = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where('type', 'group')->where('form_id',  $form_id)->where('status', 1)->get('form_field')->row_array();
            $get_group_id_array = explode(",", $get_group_id['field_ids']);

            $get_group_fields = $this->db->select('GROUP_CONCAT(field_id) as field_ids')->where_in('parent_id', $get_group_id_array)->where('status', 1)->where('form_id', $form_id)->get('form_field')->row_array();

            $get_group_fields_array = explode(",", $get_group_fields['field_ids']);
            $form_fields = $this->db->select('label,type,field_id')->where_not_in('field_id', $get_group_fields_array)->where('status', 1)->where('form_id', $form_id)->get('form_field')->result_array();
        }
        return $surveydetails;
	}
}