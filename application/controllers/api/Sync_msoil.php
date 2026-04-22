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
		$this->load->library('PHP_AES_Cipher');
	}

	//Load Methods According to Client Request
	public function index()
	{
		$data = (array)json_decode(file_get_contents("php://input"));
		if(!isset($data['purpose'])) {
			$this->logout();
		}

		// Receive and check both uuid and userid combination
		if(!$data['uuid'] || $data['uuid'] == '') {
			$this->jsonify(array(
				'status' => 0,
				'msg' => 'Unauthorized access detected. You are not authorized to access this method. Please logout and re-login.'
			));
			exit();
		}
		
		$uuid = $data['uuid'];
		$this->db->where('uuid', $uuid)->where('user_id', $data['user_id']);
		$authorized = $this->db->get('tbl_user_uuid')->num_rows();
		if($authorized == 0) {
			$this->jsonify(array(
				'status' => 0,
				'msg' => 'Unauthorized access detected. You are not authorized to access this method. Please logout and re-login.'
			));
			exit();
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
		$limit = !empty($data['limit']) ? (array)$data['limit'] : $data['limit'];
		$globaldata = array();

    	// Select global record
		$tables = array('form', 'form_field', 'form_field_multiple', 'lkp_age', 'lkp_batch', 'lkp_block', 'lkp_caste', 'lkp_centre', 'lkp_country', 'lkp_district', 'lkp_gender', 'lkp_groupname', 'lkp_partners', 'lkp_projects', 'lkp_state', 'lkp_trainee', 'lkp_village', 'lkp_yesno', 'tbl_module', 'tbl_permissions', 'rpt_partner_project', 'rpt_value_chain_location', 'rpt_value_chain_user', 'rpt_value_chain_user_location', 'rpt_centre_location', 'rpt_centre_partner', 'rpt_centre_user', 'tbl_role_permissions', 'tbl_role', 'rpt_form_relation');

		$primary_keys_list = array(
			'form' => 'id',
			'form_field' => 'field_id',
			'form_field_multiple' => 'multi_id',
			'lkp_age' => 'id',
			'lkp_batch' => 'batch_id',
			'lkp_block' => 'block_id',
			'lkp_caste' => 'caste_id',
			'lkp_centre' => 'centre_id',			
			'lkp_country' => 'country_id',
			'lkp_district' => 'district_id',
			'lkp_gender' => 'id',
			'lkp_groupname' => 'id',
			'lkp_partners' => 'partner_id',
			'lkp_projects' => 'project_id',
			'lkp_state' => 'state_id',
			'lkp_trainee' => 'trainee_id',
			'lkp_village' => 'village_id',
			'lkp_yesno' => 'id',
			'tbl_module' => 'module_id',
			'tbl_permissions' => 'permission_id',
			'rpt_partner_project' => 'id',
			'rpt_value_chain_location' => 'value_chain_loc_id',
			'rpt_value_chain_user' => 'value_chain_user_id',
			'rpt_value_chain_user_location' => 'value_chain_user_loc_id',
			'rpt_centre_location' => 'id',
			'rpt_centre_partner' => 'id',
			'rpt_centre_user' => 'id',
			'tbl_role_permissions' => 'role_permission_id',
			'tbl_role' => 'role_id',
			'rpt_form_relation' => 'relation_id'
		);
		
		foreach ($tables as $key => $table) {
			$this->db->select('*')->from($table);
			$this->db->order_by($primary_keys_list[$table], "asc");
			if(empty($limit)) {  
				$this->db->limit(1000);
			} else {
				$this->db->where(''.$primary_keys_list[$table].' >', $limit[$table]);
				$this->db->limit(2000);
			}
			$globaldata[$table] = $this->db->get()->result();
		}

		$surveyJson = $surveyTables = array();

		$surveyTables = array('ic_form_data');

		foreach ($surveyTables as $key => $table) {			
			if(!empty($limit)) {
				if($limit[$table] == ""){
					$autoincrementid = 0;
				}else{
					$this->db->select('id')->where('data_id', $limit[$table]);
					$get_autoincrementid = $this->db->get($table)->row_array();

					$autoincrementid = $get_autoincrementid['id'];
				}
			}

			$this->db->select('*');
			$this->db->from(''.$table.'');
			$this->db->where('data_status !=', 0);
			$this->db->order_by('id', "asc");
			$this->db->where('form_id', 1);
			if(empty($limit)) {
				$this->db->limit(1000);
			} else {
				$this->db->where('id >', $autoincrementid);
				$this->db->limit(1000);
			}
			$surveyJson[$table] = $this->db->get()->result();
		}
		$globaldata = (object)$globaldata;

		$surveyJson = (object)$surveyJson;



		$encodedString = json_encode(array(
			'status' => 1,
			'data' => $globaldata,
			'surveyJson' => $surveyJson
		));
		/*echo base64_encode($encodedString);*/
		$iv = 'Z2Rzv7FpLAKR03HQ'; #Same as in JAVA
		$key = 'qPox5Fh3ABI3P1Xw'; #Same as in JAVA

		$encryptedData = PHP_AES_Cipher::encrypt($key, $iv, $encodedString);

		echo $encryptedData;

		/*$decryptedPayload = PHP_AES_Cipher::decrypt($key, $encrypted);

		echo "Decrypted Payload: $decryptedPayload <br><br>";*/
		exit();
		// $this->jsonify(array(
		// 	'status' => 1,
		// 	'data' => $globaldata,
		// 	'surveyJson' => $surveyJson
		// ));
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
							$condition = (array)$data['data'][0]->where;
							$updateValue = (array)$data['data'][0]->set;

							foreach ($updateValue as $key => $value) {
								$get_current_field_val = $this->db->select($key)->where($condition)->get($data['tablename'])->row_array();

								$old_value = $get_current_field_val[$key];
								$new_value = $value;


								$insert_log_array = array(
									'editedby' => $query->creator,
									'editedfor' => $query->creator,
									'table_name' => $data['tablename'],
									'table_row_id' => $condition['id'],
									'table_field_name' => $key,
									'old_value' => $old_value,
									'new_value' => $new_value,
									'edited_reason' => "Mobile edited",
									'updated_date' => date('Y-m-d H:i:s'),
									'ip_address' => $this->input->ip_address(),
									'log_status' => 1
								);

								$insert_log = $this->db->insert('ic_log', $insert_log_array);
							}

							//Update required table
							$this->db->where($condition)->update($data['tablename'], $updateValue);

							$update_data = array(
								'updated_by' => $query->creator,
								'updated_date' => date('Y-m-d H:i:s')
							);

							$this->db->select('updated_by, updated_date')->where($condition);
							$get_update_col_info = $this->db->get($data['tablename'])->row_array();

							$old_updateby_value = $get_update_col_info['updated_by'];
							$old_updatedate_value = $get_update_col_info['updated_date'];

							$insert_updated_by_log_array = array(
								'editedby' => $query->creator,
								'editedfor' => $query->creator,
								'table_name' => $data['tablename'],
								'table_row_id' => $condition['id'],
								'table_field_name' => 'updated_by',
								'old_value' => $old_updateby_value,
								'new_value' => $query->creator,
								'edited_reason' => "Mobile edited",
								'updated_date' => date('Y-m-d H:i:s'),
								'ip_address' => $this->input->ip_address(),
								'log_status' => 1
							);
							$insert_updated_by_log = $this->db->insert('ic_log', $insert_updated_by_log_array);

							$insert_updated_date_log_array = array(
								'editedby' => $query->creator,
								'editedfor' => $query->creator,
								'table_name' => $data['tablename'],
								'table_row_id' => $condition['id'],
								'table_field_name' => 'updated_date',
								'old_value' => $old_updatedate_value,
								'new_value' => date('Y-m-d H:i:s'),
								'edited_reason' => "Mobile edited",
								'updated_date' => date('Y-m-d H:i:s'),
								'ip_address' => $this->input->ip_address(),
								'log_status' => 1
							);
							$insert_updated_date_log = $this->db->insert('ic_log', $insert_updated_date_log_array);

							$insert_updateinfo =  $this->db->where($condition)->update($data['tablename'], $update_data);

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
					//Call uploadfile function to save file in server
					$this->uploadfile($query);
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