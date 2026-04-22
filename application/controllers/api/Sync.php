<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sync extends CI_Controller {

	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Content-Type: Application/json");
		header("Accept: application/json");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}

		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('user_agent');
	}

	//Load Methods According to Client Request
	public function index()
	{
		$data = (array)json_decode(file_get_contents("php://input"));
		if(!isset($data['purpose'])) {
			$this->logout();
		}
		
		switch ($data['purpose']) {
			case 'download':
				$this->download($data);
				break;

			case 'upload':
				$this->upload($data);
				break;

			default:
				$this->logout();
				break;
		}
	}

	// Download Server Data to Local
	public function download($data)
	{
		if(!$data) $this->logout();
		$limit = !empty($data['limit']) ? (array)$data['limit'] : array();

		if(!isset($data['user_id']) || !isset($data['unit_id'])
		|| strlen($data['user_id']) == 0 || strlen($data['unit_id']) == 0) {
			$this->jsonify(array(
				'status' => 0,
				'msg' => 'Bad Request! Could not verify user.'
			));
			exit();
		}

		// Get circles assigned to user
		// $this->db->distinct()->select('CIR_CODE');
		// $this->db->where('UNIT_ID', $data['unit_id'])->where('user_id', $data['user_id']);
		// $assignedLoc = $this->db->where('status', 1)->get('tbl_user_unit_location')->result_array();
		// $circles = array();
		// foreach ($assignedLoc as $key => $value) {
		// 	if(!in_array($value['CIR_CODE'], $circles)) array_push($circles, $value['CIR_CODE']);
		// }

		ini_set('memory_limit', '-1');

		$tables = array(
			'form' => 'id',
			'form_field' => 'field_id',
			'form_field_multiple' => 'multi_id',
			'ic_form_data' => 'id',
			'ic_form_group_data' => 'id',
			'ic_data_file' => 'id',
			'lkp_title' => 'title_id',
			'lkp_house_bank' => 'house_bank_id',
			'tbl_kmlfile' => 'kml_id',
			'tbl_kmlimage' => 'img_id',
			'query' => 'id',
			'lkp_gender' => 'GENDER_ID',
			'lkp_crop_type' => 'CROP_TYPE_ID',
			'lkp_soil_type' => 'SOIL_TYPE_ID',
			'lkp_variety' => 'VARIETY_ID',
			'lkp_ifsc' => 'IFSC_ID',

			//new tables added for munichre
			'lkp_state' => 'state_id',
			'lkp_country' => 'country_id',
			'lkp_feed_type' => 'feed_type_id',
			'lkp_fodder_type' => 'fodder_type_id',
			'lkp_district' => 'district_id',
			'lkp_tehsil' => 'tehsil_id',
			'lkp_block' => 'block_id',
			'lkp_grampanchayat' => 'grampanchayat_id',
			'lkp_village' => 'village_id',
			'lkp_crop' => 'id',
			'lkp_insurancecompany' => 'icompany_id',
			'lkp_currency' => 'id',
			'lkp_communities_type' => 'id',
			'survey1' => 'id',
			'survey2' => 'id',
			'survey2_groupdata' => 'id',
			'survey3' => 'id',
			'survey4' => 'id',
			'survey4_groupdata' => 'id',
			'survey6' => 'id',
			'survey432' => 'id',
			'lkp_country_projects' => 'id',
			'lkp_project_site' => 'id',
			'lkp_major_region' => 'id',
			'lkp_world_region' => 'id',
			'lkp_units' => 'unit_id',
			'survey1_groupdata' => 'id',
			'lkp_add_season' => 'id',
			'lkp_species' => 'id',
			'lkp_livestock' => 'id',
			'lkp_animal_type' => 'id',
			'lkp_minor_region' => 'id',
			'lkp_category' => 'category_id',
			'lkp_livestock_sales' => 'id',
			'lkp_income_activities' => 'id'
		);
		$structure = $records = array();
		
		//Get all Table Stucture and Data for Mobile Sync
		foreach($tables as $table => $pk) {
			// Get table structure
			// if(count($limit) === 0) {
				$fields = $this->db->field_data($table);
				foreach($fields as $key => $field) {
					if($fields[$key]->name == $pk) {
						if($table == 'ic_form_data' || $table == 'ic_form_group_data'
						|| $table == 'ic_data_location' || $table == 'ic_data_file'
						|| $table == 'tbl_farmers' || $table == 'tbl_farmer_bank_details'
						|| $table == 'tbl_farmer_other_details' || $table == 'tbl_farm_details'
						|| $table == 'tbl_plot' || $table == 'tbl_agreement'
						|| $table == 'tbl_kmlfile' || $table == 'tbl_kmlimage') {
							$fields[$key] = $fields[$key]->name.' INTEGER NULL DEFAULT NULL';
						} else {
							if($fields[$key]->type == 'int') {
								$fields[$key] = $fields[$key]->name.' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL';
							} else {
								$fields[$key] = $fields[$key]->name.' TEXT PRIMARY KEY NOT NULL';
							}
						}
					} else {
						if($fields[$key]->type == 'int') {
							$fields[$key] = $fields[$key]->name.' INTEGER NULL DEFAULT NULL';
						} else {
							$fields[$key] = $fields[$key]->name.' TEXT NULL DEFAULT NULL';
						}
					}
				}
				$structure[$table] = '('.implode(', ',$fields).')';
			// }

			$userAssignedCurrency = $this->db->select('GROUP_CONCAT(localCurrency) as currency_info')->where('user_id', $data['user_id'])->get('lkp_project_site')->row_array();
			$userAssignedcommunitiestype = $this->db->select('GROUP_CONCAT(community_type) as communitytype')->where('user_id', $data['user_id'])->get('lkp_project_site')->row_array();
			$userAssignedCountry = $this->db->select('GROUP_CONCAT(country_id) as country_info')->where('user_id', $data['user_id'])->get('lkp_project_site')->row_array();
			
			if($table != 'query') {
				// Get table data
				$this->db->distinct()->select('table.*')->from($table.' AS table');
				if($table == 'lkp_country') {
					// $this->db->join('tbl_user_unit_location AS tuul', 'tuul.country_id = table.country_id');
					// $this->db->where('status', 1)->where('user_id', $data['user_id']);
					$this->db->where_in('country_id', explode(",", $userAssignedCountry['country_info']));
				}else if($table == 'lkp_state') {
					$this->db->join('tbl_user_unit_location AS tuul', 'tuul.state_id = table.state_id');
					$this->db->where('tuul.status', 1)->where('tuul.user_id', $data['user_id']);
				} else if($table == 'lkp_district') {
					$this->db->join('tbl_user_unit_location AS tuul', 'tuul.district_id = table.district_id');
					$this->db->where('tuul.status', 1)->where('tuul.user_id', $data['user_id']);
				}else if($table == 'lkp_country_projects') {
					$this->db->where('table.user_id', $data['user_id']);
					$this->db->group_by('table.id');
				}else if($table == 'lkp_project_site') {
					$this->db->where('table.user_id', $data['user_id']);
					$this->db->group_by('table.id');
				}else if($table == 'survey1') {
					$this->db->where('user_id', $data['user_id']);
				}else if($table == 'survey4') {
					$this->db->where('user_id', $data['user_id']);
				}else if($table == 'lkp_currency'){
					$this->db->where_in('id', explode(",", $userAssignedCurrency['currency_info']));
				}else if($table == 'lkp_communities_type'){
					$this->db->where_in('id', explode(",", $userAssignedcommunitiestype['communitytype']));
				}else if($table == 'lkp_major_region' || $table == 'lkp_minor_region'){
					$this->db->where('user_id', $data['user_id']);
				}else if($table == 'lkp_animal_type' || $table == 'lkp_crop' || $table == 'lkp_fodder_type' || $table == 'lkp_feed_type' || $table == 'lkp_livestock' || $table == 'lkp_species' || $table == 'lkp_units' || $table == 'lkp_livestock_sales' || $table == 'lkp_income_activities' || $table == 'lkp_category'){
					$this->db->where("(user_id IS NULL OR user_id = '".$data['user_id']."')");
				}
				$this->db->order_by('table.'.$pk, "ASC");
				$query = $this->db->get();
				$records[$table] = $query->result();
				
				$query->free_result();
				$query = null;
			}
		}

		$this->jsonify(array(
			'status' => 1,
			'structure' => $structure,
			'records' => $records
		));
	}

	// Upload Local Data to Server
	public function upload($data)
	{
		date_default_timezone_set("UTC");
		$queries = $data['queries'];
		foreach ($queries as $key => $query) {
			$data = (array)json_decode($query->data);
			switch ($query->type) {
				case 'query':
					switch ($query->action) {
						case 'insert':
							//Insert into survey table
							$this->db->insert($data['tablename'], $data['data']);
						break;

						case 'update':

							/*$condition = (array)$data['data'][0]->where;
							$updateValue = (array)$data['data'][0]->set;*/

							if(isset($data['data_id'])) {
								$condition_array = array(
									'data_id' => $data['data_id']
								);
							} else if(isset($data['group_id'])) {
								$condition_array = array(
									'group_id' => $data['group_id']
								);
							} else if(isset($data['file_id'])) {
								$condition_array = array(
									'file_id' => $data['file_id']
								);
							}
							$updateValue = $data['data'];							

							foreach ($updateValue as $key => $value) {
								$get_current_field_val = $this->db->select($key)->where($condition_array)->get($data['tablename'])->row_array();

								$old_value = $get_current_field_val[$key];
								$new_value = $value;

								$insert_log_array = array(
									'editedby' => $query->creator,
									'editedfor' => $query->creator,
									'table_name' => $data['tablename'],
									'table_field_name' => $key,
									'old_value' => $old_value,
									'new_value' => $new_value,
									'edited_reason' => "Mobile edited",
									'updated_date' => date('Y-m-d H:i:s'),
									'ip_address' => $this->input->ip_address(),
									'log_status' => 1
								);
								if(isset($data['data_id'])) {
									$insert_log_array['table_row_id'] = $data['data_id'];
								} else if(isset($data['file_id'])) {
									$insert_log_array['table_row_id'] = $data['file_id'];
								}

								$insert_log = $this->db->insert('ic_log', $insert_log_array);
							}

							//Update required table
							$this->db->where($condition_array)->update($data['tablename'], $updateValue);
						break;

						case 'delete':
							$condition = (array)$data['data'][0]->where;
							
							//Delete data from required table
							$this->db->where($condition)->delete($data['tablename']);
						break;

						default:
							$this->db->query($query->data);
							break;
					}

					//Insert into query table
					$this->db->insert('query', array(
						'type' => $query->type,
						'action' => $query->action,
						'time' => $query->time,
						'creator' => $query->creator,
						'platform' => 'mobile',
						'data' => $query->data
					));
				break;

				case 'image':
					if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
					$images = $data['image'];

					foreach ($images as $key => $image) {
						$mimeType = explode(';', $image);
						switch ($mimeType[0]) {
							case 'data:image/*':
								$crop = str_replace('data:image/*;charset=utf-8;base64,', '', $image);
								break;

							case 'data:image/jpeg':
								$crop = str_replace('data:image/jpeg;base64,', '', $image);
								break;

							case 'data:image/png':
								$crop = str_replace('data:image/png;base64,', '', $image);
								break;

							default:
								$crop = $image;
								break;
						}
						$crop = str_replace(' ', '+', $crop);
						$cropdata = base64_decode($crop);
						$file = uniqid() . $key . $data['userid'] . '.jpg';
						$url = UPLOAD_DIR . $file;

						file_put_contents(UPLOAD_DIR . $file, $cropdata);
						
						$insert = $this->db->insert('rpt_formdata_image', array(
							'form_id' => $data['formid'],
							'survey_id' => $data['surveyId'],
							'image' => $file
						));
					}
				break;

				case 'surveyimage':
					switch ($query->action) {
						case 'insert':
							//Insert into survey table
							$this->db->insert($data['table_name'], $data['data']);
						break;
					}
					//Call uploadfile function to save file in server
					// $this->uploadfile($query);
				break;
			}
		}

		$this->jsonify(array(
			'msg' => 'Successfully synced LocalDB with remoteDB.',
			'status' => 1
		));
	}

	// Upload Local File to Server
	private function uploadfile($query)
	{
		date_default_timezone_set("UTC");
		if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
		
		//Convert object data to array
		$data = (array)json_decode($query->data);
		$tablename = $data['table_name'];
		$syncdata = (array)$data['data'];
		$extension = substr($syncdata['file_name'], strrpos($syncdata['file_name'], '.') + 1);

		$base64 = $data['base64'];
		$base64 = str_replace(' ', '+', $base64);
		$cropdata = base64_decode($base64);
		file_put_contents(UPLOAD_DIR . $syncdata['file_name'], $cropdata);
		
		$insert = $this->db->insert($tablename, $syncdata);
		if($insert) return true;
		else return false;
	}
	public function upload_file()
	{
		$config['upload_path']   = './uploads/survey/';
		$config['allowed_types'] = 'gif|jpg|png|exe|xls|doc|docx|xlsx|rar|zip|avi|mp4';
		$config['max_size']      = '131072'; 
		$config['remove_spaces'] = TRUE; //it will remove all spaces
		$config['encrypt_name']  = TRUE; //it wil encrypte the original file name
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$error = array('error' => $this->upload->display_errors());
			$this->jsonify(array(
				'status' => 0,
				'msg' => $error
			));
			exit();
		} else {
			$data = $this->upload->data();
			$this->jsonify(array(
				'status' => 1,
				'msg' => $data['file_name']
			));
			exit();
		}
	}
	public function upload_file_new()
	{
		// var_dump($_FILES); die();
		date_default_timezone_set("UTC");
		if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/survey/');
		$uploadPath = UPLOAD_DIR . $_FILES['file']['name'];

		// Save file in uploads/survey folder
		move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
		$this->jsonify(array(
			'msg' => 'Successfully synced Local files with server.',
			'status' => 1
		));
	}
	
	//return json data
	public function jsonify($data)
	{
		print_r(json_encode($data));
		exit();
	}

	//logout ++++++++ session
	public function logout()
	{
		$this->jsonify(array(
			'logout' => true
		));
	}
}