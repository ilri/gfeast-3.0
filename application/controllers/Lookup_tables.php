<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lookup_tables extends CI_Controller {
	
	function _construct(){
		parent::_construct();
		$this->load->helper('url');
	}

	public function manage_lookup_tables() {
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$listoftables = array(
				'lkp_animal_type' => 'Animal Type',
				'lkp_communities_type' => 'Community Type',
				'lkp_crop' => 'Crop',
				'lkp_currency' => 'Currency', 
				'lkp_fodder_type' => 'Fodder Type',
				'lkp_feed_type' => 'Feed Type',
				'lkp_livestock' => 'Livestock',
				'lkp_species' => 'Species',				
				'lkp_units' => 'Units',
				'lkp_category' => 'Category',
				'lkp_livestock_sales' => 'Livestock Sales',
				'lkp_income_activities' => 'Income Activity'
			);

			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$result = array('listoftables' => $listoftables);

			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
		    $this->load->view('lookup_tables/manage_lookup_tables', $result);
		    $this->load->view('footer');
		}
	}

	public function showtableinfo()
	{
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{

			$listoftables = array(
				'lkp_animal_type', 'lkp_communities_type', 'lkp_crop', 'lkp_currency', 'lkp_fodder_type', 'lkp_feed_type', 'lkp_livestock', 'lkp_species', 'lkp_units', 'lkp_category', 'lkp_livestock_sales', 'lkp_income_activities'
			);

			$tablename = $this->uri->segment(3);

			if(!in_array($tablename, $listoftables)){
				$baseurl = base_url();
				redirect($baseurl);
			}

			$dropdowns = array();

			switch ($tablename) {
				case 'lkp_animal_type':
					$columnname = array(
						'livestock_id' => 'Livestock Category',
						'name' => 'Animal type',
						'lactating' => 'Lactating',
						'dairy' => 'Dairy',
						'min_wt' => 'Weight Minimum (kg)',
						'max_wt' => 'Weight Maximum (kg)'
					);

					$addLabelName = 'Add Animal Type';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$livestockArray = $this->db->where('status', 1)->get('lkp_livestock')->result_array();

					$this->db->select('main.*, sub.name as livestockname');
					$this->db->join('lkp_livestock as sub', 'sub.id = main.livestock_id');
					$this->db->where('main.status', 1);
					if($this->session->userdata('role') == 8){
						$this->db->where("(main.user_id IS NULL OR main.user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->get($tablename.' as main')->result_array();

					$dropdowns['livestockArray'] = $livestockArray;
					break;

				case 'lkp_communities_type':
					$columnname = array(
						'name' => 'Name'
					);

					$addLabelName = 'Add Community Type';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_crop':
					$columnname = array(
						'crop_name' => 'Crop Name',
						'harvest_index' => 'Harvest Index',
						'dry_matter_content' => 'Dry Matter Content (%)',
						'metabolisable_energy' => 'Metabolisable Energy (MJ/kgDM)',
						'crude_protein_content' => 'Crude Protein Content (%)',
						'ref_source_info' => 'Provide reference /citation for your source of information'
					);

					$addLabelName = 'Add Crop';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_currency':
					$columnname = array(
						'world_region_id' => 'World Region',
						'country_id' => 'Country',
						'currency'=> 'Currency',
						// 'name' => 'Name',
						'default_value_in_USD' => 'Default value in USD',
						'current_exchange_rate' => 'Current exchange rate'
					);

					$addLabelName = 'Update Exchange Rate';

					$this->db->select('main.*, wr.world_region_name, cou.name');
					$this->db->join('lkp_world_region as wr', 'wr.id = main.world_region_id');
					$this->db->join('lkp_country as cou', 'cou.country_id = main.country_id');
					$this->db->where('main.status', 1);
					if($this->session->userdata('role') == 8){
						$this->db->where("(main.user_id IS NULL OR main.user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->get($tablename.' as main')->result_array();
					break;

				case 'lkp_feed_type':
					$columnname = array(
						'feed_type' => 'Feed Type',
						'dry_matter_content' => 'Dry Matter per Hectare per Year',
						'metabolisable_energy' => 'Metabolisable Energy (MJ/kgDM)',
						'crude_protein_content' => 'Crude Protein Content (%)',
						'reference' => 'Provide a reference / citation for your source of information'
					);

					$addLabelName = 'Add Feed Type';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_fodder_type':
					$columnname = array(
						'fodder_type' => 'Fooder Type',
						'kg_dry_matter' => 'kg Dry Matter per Hectare per Year',
						'metabolisable_energy' => 'Metabolisable Energy (MJ/kgDM)',
						'crude_protein_content' => 'Crude Protein Content (%)',
						'reference' => 'Provide a reference / citation for your source of information'
					);

					$addLabelName = 'Add Fodder Type';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_livestock':
					$columnname = array(
						'name' => 'Name'
					);

					$addLabelName = 'Add Livestock';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_species':
					$columnname = array(
						'name' => 'Name'
					);

					$addLabelName = 'Add Species';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_units':
					$columnname = array(
						'unit_name' => 'Unit Name',
						'unit_description' => 'Description',
						'unit_type' => 'Unit Type',
						'equivalent' => 'Equivalent in ha/kg',
						'standard_local' => 'Standard / Local'
					);

					$addLabelName = 'Add Unit';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_category':
					$columnname = array(
						'category_Name' => 'Category Name'
					);

					$addLabelName = 'Add Category';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_livestock_sales':
					$columnname = array(
						'name' => 'Livestock Name'
					);

					$addLabelName = 'Add Livestock Sale';

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->where('status', 1)->get($tablename)->result_array();
					break;

				case 'lkp_income_activities':
					$columnname = array(
						'category_id' => 'Category Name',
						'name' => 'Name'
					);

					$addLabelName = 'Add Income Activity';

					$this->db->select('main.*, sub.category_Name');
					$this->db->join('lkp_category as sub', 'sub.category_id = main.category_id');
					$this->db->where('main.status', 1);
					if($this->session->userdata('role') == 8){
						$this->db->where("(main.user_id IS NULL OR main.user_id = '".$this->session->userdata('login_id')."')");
					}
					$getdata = $this->db->order_by('sub.category_Name')->get($tablename.' as main')->result_array();

					if($this->session->userdata('role') == 8){
						$this->db->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')");
					}
					$categoryArray = $this->db->where('status', 1)->get('lkp_category')->result_array();

					$dropdowns['categoryArray'] = $categoryArray;
					break;
				
				default:
					$columnname = array();

					$addLabelName = 'Add';
					$getdata = array();
					break;
			}

			$result = array('columnname' => $columnname, 'getdata' => $getdata, 'addLabelName' => $addLabelName, 'dropdowns' => $dropdowns);


			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();
			$header_result = array('main_menu' => $main_menu);

			$this->load->view('header', $header_result);
		    $this->load->view('lookup_tables/showtableinfo', $result);
		    $this->load->view('footer');	
		}
	}

	public function addData()
	{
		$baseurl = base_url();

		if ($this->session->userdata('login_id') == '') {
			echo json_encode(array(
				'session_err' => 1,
				'msg' => 'Session Expired! Please login again to continue.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
			));
			exit();
		}

		$type = $this->input->post('type');

		switch ($type) {
			case 'lkp_animal_type':
				$inserData = array(
					'livestock_id' => $this->input->post('livestock_id'),
					'name' => $this->input->post('animal_type_name'),
					'lactating' => $this->input->post('lactating'),
					'dairy' => $this->input->post('dairy'),
					'min_wt' => $this->input->post('min_wt'),
					'max_wt' => $this->input->post('max_wt'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('name', $this->input->post('animal_type_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Animal type'.$this->input->post('animal_type_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_communities_type':
  				$inserData = array(
					'name' => $this->input->post('communities_type_name'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('name', $this->input->post('communities_type_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Community type '.$this->input->post('communities_type_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_crop':
  				$inserData = array(
					'crop_name' => $this->input->post('crop_name'),
					'harvest_index' => $this->input->post('harvest_index'),
					'dry_matter_content' => $this->input->post('dry_matter_content'),
					'metabolisable_energy' => $this->input->post('metabolisable_energy'),
					'crude_protein_content' => $this->input->post('crude_protein_content'),
					'ref_source_info' => $this->input->post('ref_source_info'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('crop_name', $this->input->post('crop_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Crop '.$this->input->post('crop_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_currency':
  				$updateData = array(
					'default_value_in_USD' => $this->input->post('default_value_in_USD'),
					'current_exchange_rate' => $this->input->post('current_exchange_rate')
				);
  				break;

  			case 'lkp_fodder_type':
  				$inserData = array(
					'fodder_type' => $this->input->post('fodder_type'),
					'kg_dry_matter' => $this->input->post('kg_dry_matter'),
					'metabolisable_energy' => $this->input->post('metabolisable_energy'),
					'crude_protein_content' => $this->input->post('crude_protein_content'),
					'reference' => $this->input->post('reference'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('fodder_type', $this->input->post('fodder_type'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Fodder type '.$this->input->post('fodder_type').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_feed_type':
  				$inserData = array(
					'feed_type' => $this->input->post('feed_type'),
					'dry_matter_content' => $this->input->post('dry_matter_content'),
					'metabolisable_energy' => $this->input->post('metabolisable_energy'),
					'crude_protein_content' => $this->input->post('crude_protein_content'),
					'reference' => $this->input->post('reference'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('feed_type', $this->input->post('feed_type'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Feed type '.$this->input->post('feed_type').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_livestock':
  				$inserData = array(
					'name' => $this->input->post('livestock_name'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('name', $this->input->post('livestock_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Livestock '.$this->input->post('livestock_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_species':
  				$inserData = array(
					'name' => $this->input->post('species_name'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('name', $this->input->post('species_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Species '.$this->input->post('species_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_units':
  				$inserData = array(
					'unit_name' => $this->input->post('unit_name'),
					'unit_description' => $this->input->post('unit_description'),
					'unit_type' => $this->input->post('unit_type'),
					'equivalent' => $this->input->post('equivalent'),
					'standard_local' => $this->input->post('standard_local'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('unit_name', $this->input->post('unit_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Species '.$this->input->post('unit_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_category':
  				$inserData = array(
					'category_Name' => $this->input->post('category_name'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('category_Name', $this->input->post('category_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Category '.$this->input->post('unit_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_livestock_sales':
  				$inserData = array(
					'name' => $this->input->post('livestock_sale_name'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('name', $this->input->post('livestock_sale_name'))
				->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
				->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Category '.$this->input->post('unit_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;

  			case 'lkp_income_activities':
  				$inserData = array(
					'category_id' => $this->input->post('incomeactivity_category'),
					'name' => $this->input->post('incomeactivity_name'),
					'user_id' => $this->session->userdata('login_id')
				);

				$checkdata = $this->db->where('category_id', $this->input->post('incomeactivity_category'))
					->where('name', $this->input->post('incomeactivity_name'))
					->where("(user_id IS NULL OR user_id = '".$this->session->userdata('login_id')."')")
					->where('status', 1)->get($type)->num_rows();

				if($checkdata > 0) {
					echo json_encode(array(
						'msg' => 'Category '.$this->input->post('unit_name').' is already available.',
						'insertstatus' => 0,
						'csrfName' => $this->security->get_csrf_token_name(),
						'csrfHash' => $this->security->get_csrf_hash()
					));
					exit();
				}
  				break;
		}

		if($type == 'lkp_currency') {
			$insertquery = $this->db->where('id', $this->input->post('currencyId'))->update($type, $updateData);
			$message = 'Data updated successfully';
		} else {
			$insertquery = $this->db->insert($type, $inserData);
			$message = 'Data Added successfully';
		}

		if ($insertquery) {
			echo json_encode(array(
				'msg' => $message,
				'insertstatus' => 1,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		} else {
			echo json_encode(array(
				'msg' => 'Sorry! Please try after sometime.',
				'insertstatus' => 0,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash()
			));
			exit();
		}
	}

	public function deleterecord()
	{
		// Check for session and CSRF token...
		$tablePrimaryId = $this->input->post('tablePrimaryId');
		$recordid = $this->input->post('recordid');
		$tablename = $this->input->post('tablename');

		$data = array(
			'status' => 0,
		);

		$this->db->where($tablePrimaryId, $recordid);
		$updateQuery = $this->db->update($tablename, $data);

		if ($updateQuery) {
			echo json_encode(array('msg' => 'Deleted successfully', 'status' => 1));
		} else {
			echo json_encode(array('msg' => 'Failed to update status', 'status' => 0));
		}
		exit(); // Ensure to exit after sending the response
	}

	public function editrecord()
	{
		// Check for session and CSRF token...
		$tablePrimaryId = $this->input->post('tablePrimaryId');
		$recordid = $this->input->post('recordid');
		$tablename = $this->input->post('tablename');

		$data = array();

		foreach ($_POST as $key => $value) {
			if($key != 'tablePrimaryId' && $key != 'recordid' && $key != 'tablename') {
				$data[$key] = $value;
			}
		}

		$this->db->where($tablePrimaryId, $recordid);
		$updateQuery = $this->db->update($tablename, $data);

		if ($updateQuery) {
			echo json_encode(array('msg' => 'Edited successfully', 'status' => 1));
		} else {
			echo json_encode(array('msg' => 'Failed to update data', 'status' => 0));
		}
		exit();
	}

	public function getCurrencyInfo()
	{
		$currencyId = $this->input->post('currencyId');

		$getCurrencyData = $this->db->where('id', $currencyId)->get('lkp_currency')->row_array();

		echo json_encode(array('getCurrencyData' => $getCurrencyData, 'status' => 1));
		exit();
	}
}