<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewmanager extends CI_Controller {
	
	// function _construct(){
	// 	parent::__construct();
	// 	$this->load->helper('url');
	// 	$this->load->library('session');
	// 	$this->load->library('user_agent');
	// }

	public function index()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('view_manager/view_manager_home');
			$this->load->view('footer');
		}
	}

	public function duplicate_hhids(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			// $result=array();
			$this->db->select('field_1001');
			$this->db->from('rpt_form_1');
			$this->db->where('status',1)->having('COUNT(field_1001) > 1')->group_by('field_1001');
			$hhids = $this->db->get()->result_array();
			
			$duplicate_hhids = array();
			foreach ($hhids as $key => $hhid) {
				array_push($duplicate_hhids, $hhid['field_1001']);			
			}

			if(count($duplicate_hhids) > 0){
				$this->db->select('form.field_1001 as hhid, valuechain.value_chain_name as valuechainid, CONCAT(users.first_name,'.',users.last_name) as name,form.datetime as inserteddate,form.field_1450,form.field_1456,form.field_1002,form.field_1003');
				$this->db->from('rpt_form_1 form');
				$this->db->join('tbl_users users','users.user_id=form.added_by');
				$this->db->join('lkp_value_chain valuechain','valuechain.value_chain_id=form.value_chain_id');
				$this->db->where_in('form.field_1001', $duplicate_hhids)->where('form.status', 1)->order_by('valuechain.value_chain_name, form.field_1001');
				$data = $this->db->get()->result_array();
			}else{
				$data = array();
			}

			$result['overall_data']=$data;
			$result['heading'] = 'Duplicate data by HHIDS';

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
			$this->load->view('view_manager/duplicate_data',$result);
			$this->load->view('footer');
		}		
	}
	public function duplicate_nationalid()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->db->select('field_1003');
			$this->db->from('rpt_form_1');
			$this->db->where('status',1)->where('field_1003!=',null)->where('field_1003!=','')->having('COUNT(field_1003) > 1')->group_by('field_1003');
			$nids = $this->db->get()->result_array();

			$duplicate_nids = array();

			foreach ($nids as $key => $nid) {			
				array_push($duplicate_nids, $nid['field_1003']);
			}

			if(count($duplicate_nids) == 0){
				$duplicate_nids = array(0);
			}
		
			$this->db->select('form.id as recordid,form.field_1003,form.field_1001 as hhid, valuechain.value_chain_name as valuechainid, CONCAT(users.first_name,'.',users.last_name) as name,form.datetime as inserteddate,form.field_1450,form.field_1456,form.field_1002');
			$this->db->from('rpt_form_1 form');
			$this->db->join('tbl_users users','users.user_id=form.added_by');
			$this->db->join('lkp_value_chain valuechain','valuechain.value_chain_id=form.value_chain_id');
			$this->db->where_in('form.field_1003', $duplicate_nids)->where('form.status', 1)->order_by('valuechain.value_chain_name, form.field_1003');
			$data = $this->db->get()->result_array();

			$result['overall_data']=$data;
			$result['heading'] = 'Duplicate data by Nationalid';

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			
			$this->load->view('header', $header_result);
			$this->load->view('view_manager/duplicate_data',$result);
			$this->load->view('footer');
		}
	}

	public function duplicate_mobile()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$this->db->select('field_1002');
			$this->db->from('rpt_form_1');
			$this->db->where('status',1)->where('field_1002 !=',null)->where('field_1002!=','')->having('COUNT(field_1002) > 1')->group_by('field_1002');
			$mids = $this->db->get()->result_array();

			$duplicate_mids = array();

			foreach ($mids as $key => $mid) {			
				array_push($duplicate_mids, $mid['field_1002']);					
			}

			if(count($duplicate_mids) == 0){
				$duplicate_mids = array(0);
			}

			$this->db->select('form.field_1002 as mid,form.field_1001 as hhid, valuechain.value_chain_name as valuechainid, CONCAT(users.first_name,'.',users.last_name) as name,form.datetime as inserteddate,form.field_1450,form.field_1456,form.field_1002,form.field_1003');
			$this->db->from('rpt_form_1 form');
			$this->db->join('tbl_users users','users.user_id=form.added_by');
			$this->db->join('lkp_value_chain valuechain','valuechain.value_chain_id=form.value_chain_id');
			$this->db->where_in('form.field_1002', $duplicate_mids)->where('form.status', 1)->order_by('valuechain.value_chain_name, form.field_1002');
			$data = $this->db->get()->result_array();

			$result['overall_data']=$data;
			$result['heading'] = 'Duplicate data by Mobie number';

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);
			$this->load->view('header', $header_result);
			$this->load->view('view_manager/duplicate_data',$result);
			$this->load->view('footer');
		}
	}

	public function edit_hhid()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{

			$this->db->select('GROUP_CONCAT(lkp_value_chain_id) as valuechain_ids');
	        $this->db->where('user_id', $this->session->userdata('login_id'))->where('value_chain_user_status', 1);
	        $get_user_valuechains = $this->db->get('rpt_value_chain_user')->row_array();

	        $user_valuechains=explode(',',$get_user_valuechains['valuechain_ids']);

	        $this->db->select('value_chain_id, value_chain_name');
	        $this->db->where_in('value_chain_id',$user_valuechains);
	        $valuechains = $this->db->get('lkp_value_chain')->result_array();

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$header_result = array('main_menu' => $main_menu);

			$result = array('valuechains' => $valuechains);

			$this->load->view('header', $header_result);
			$this->load->view('view_manager/edit_hhid', $result);
			$this->load->view('footer');
		}
	}

	public function get_data_toedithhid()
	{
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$valuechain = $_POST['valuechain'];
			$hhid = $_POST['hhid'];

			$this->db->select('form.id, form.field_1002 as mid, form.field_1001 as hhid, valuechain.value_chain_name as valuechainid, CONCAT(users.first_name,'.',users.last_name) as name, form.datetime as inserteddate, form.field_1450, form.field_1456, form.field_1002, form.field_1003');
			$this->db->from('rpt_form_1 form');
			$this->db->join('tbl_users users','users.user_id=form.added_by');
			$this->db->join('lkp_value_chain valuechain','valuechain.value_chain_id=form.value_chain_id');
			$this->db->where('field_1011', $valuechain)->where('form.status', 1);
			$this->db->like('field_1001', $hhid);
			$data = $this->db->get()->result_array();

			$result = array('data' => $data, 'status' => 1);

			echo json_encode($result);
			exit();
		}
	}

	public function update_edited_hhid()
	{
		date_default_timezone_set("UTC");
		if(($this->session->userdata('login_id') == '')) {
			echo json_encode(array(
				'status' => 0,
				'msg' => 'Session Expired! Please login again to continue.'
			));
			exit();
		}else{
			$valuechain = $_POST['valuechain'];
			$hhidvalue = $_POST['hhidvalue'];
			$recordid = $_POST['recordid'];
			$correct_hhid = $_POST['correct_hhid'];


			$this->db->where('field_1001', $correct_hhid);
			$check_hhid = $this->db->get('rpt_form_1')->num_rows();

			$this->db->where('id', $_POST['recordid'])->where('field_1001', $hhidvalue);
			$check_record_exist = $this->db->get('rpt_form_1')->num_rows();

			if($check_hhid == 0 && $check_record_exist > 0){
				$update_array = array(
					'field_1001' => $correct_hhid
				);

				$this->db->where('id', $_POST['recordid']);
				$query = $this->db->update('rpt_form_1', $update_array);

				if($query){


					$insert_log_array = array(
						'editedby' => $this->session->userdata('login_id'),
						'editedfor' => $this->session->userdata('login_id'),
						'table_name' => 'rpt_form_1',
						'table_row_id' => $_POST['recordid'],
						'table_field_name' => 'field_1001',
						'old_value' => $hhidvalue,
						'new_value' => $correct_hhid,
						'edited_reason' => 'Edited by admin by client request',
						'updated_date' => date('Y-m-d H:i:s'),
						'ip_address' => $this->input->ip_address(),
						'log_status' => 1
					);

					$log_query = $this->db->insert('ic_log', $insert_log_array);

					if($log_query){
						echo json_encode(array(
							'status' => 1,
							'msg' => 'Updated successfully.'
						));
						exit();
					}else{
						echo json_encode(array(
							'status' => 0,
							'msg' => 'Something went wrong please try after some time with log.'
						));
						exit();
					}					
				}else{
					echo json_encode(array(
						'status' => 0,
						'msg' => 'Something went wrong please try after some time.'
					));
					exit();
				}
			}else{
				echo json_encode(array(
					'status' => 0,
					'msg' => 'HHID already exist please use other HHID.'
				));
				exit();
			}
		}
	}
}