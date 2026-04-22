
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends CI_Controller {

	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
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

	//Load Login Form
	public function index()
	{
		$this->logout();
	}
	//Login Code
	// login
	public function login()
	{
		$data = (array)json_decode(file_get_contents("php://input"));
		if(isset($data['purpose']) && $data['purpose'] == 'login') {
			date_default_timezone_set("UTC");
			$user = array(
				'email_id' => $data['email_id']
			);

			if(isset($data['logintype'])) {
				switch ($data['logintype']) {
					case 'simple':
						$checkuser = $this->db->where("(username = '".$data['email_id']."' OR email_id = '".$data['email_id']."' OR mobile_number = '".$data['email_id']."')")->get('tbl_users');
						if($checkuser->num_rows() === 0) {
							$this->jsonify(array(
								'msg' => 'Invalid Credentials.',
								'status' => 0
							));
							exit();
						}
					break;

					case 'ldap':
						$username = explode('@', $data['email_id']);
						$username = $username[0];
						$email = $data['email_id'];

						//Start LDAP login process
						$ldapport = 636;
						$ldaphostA = "ldaps://AZCGNEROOT2.CGIARAD.ORG";
						$ldaphostB = "ldaps://AZCGCCROOT2.CGIARAD.ORG";

						// Connecting to LDAP
						$ldapconn = ldap_connect($ldaphostB, $ldapport);
						if(!$ldapconn) {
						    return false;
						}

						// configure ldap params
						ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
						ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
						ldap_set_option($ldapconn, LDAP_OPT_NETWORK_TIMEOUT, 10);

						// binding to ldap server
						$ldapbind = ldap_bind($ldapconn, $email, $data['pass']);
						if(!$ldapbind) {
							$this->jsonify(array(
								'msg' => 'Invalid AD Credentials.',
								'status' => 0
							));
							exit();
						}

						$checkuser = $this->db->where("email_id", $email)->get('tbl_users');
						if($checkuser->num_rows() === 0) {
							$this->jsonify(array(
								'msg' => 'Invalid Login. Please contact s.nagaraji@cgiar.org to get access.',
								'status' => 0
							));
							exit();
						}
					break;
					
					default:
						$checkuser = $this->db->where("(username = '".$data['email_id']."' OR email_id = '".$data['email_id']."' OR mobile_number = '".$data['email_id']."')")->get('tbl_users');
						if($checkuser->num_rows() === 0) {
							$this->jsonify(array(
								'msg' => 'Invalid Credentials.',
								'status' => 0
							));
							exit();
						}
					break;
				}
			} else {
				$checkuser = $this->db->where("(username = '".$data['email_id']."' OR email_id = '".$data['email_id']."' OR mobile_number = '".$data['email_id']."')")->get('tbl_users');
				if($checkuser->num_rows() === 0) {
					$this->jsonify(array(
						'msg' => 'Invalid Credentials.',
						'status' => 0
					));
					exit();
				}
			}

			$getData = $checkuser->row_array();
			$password = $data['pass'];
			$salt = $getData['salt'];
			$saltedPW =  $password . $salt;
			$hashedPW = hash('sha256', $saltedPW);
			if(isset($data['logintype']) && $data['logintype'] == 'ldap') {
				$newData = array(
					'email_id' => $getData['email_id'],
					'status' => 1
				);
			} else {
				$newData = array(
					'email_id' => $getData['email_id'],
					'password' => $hashedPW,
					'status' => 1
				);
			}

			$query = $this->db->where($newData)->get('tbl_users');
			if($query->num_rows() > 0){
				// Get user avatar
				$getImage = $this->db->where('user_id', $getData['user_id'])->where('status', 1)->get('tbl_images')->row_array();

				// // Get user account group
				// $this->db->select('lagm.*')->from('lkp_account_group_master AS lagm');
				// $this->db->join('tbl_user_account_group AS tuag', 'tuag.account_group_id = lagm.account_group_id');
				// $getAccGrp = $this->db->where('tuag.user_id', $getData['user_id'])->where('tuag.status', 1)->get()->row_array();

				// // Get user house bank
				// $this->db->select('lhb.*')->from('lkp_house_bank AS lhb');
				// $this->db->join('tbl_user_account_group AS tuag', 'tuag.house_bank_id = lhb.house_bank_id');
				// $getHseBnk = $this->db->where('tuag.user_id', $getData['user_id'])->where('tuag.status', 1)->get()->row_array();

				// Get user client
				$this->db->select('lau.client_id')->from('lkp_agency_user AS lau');
				$this->db->where('lau.user_id', $getData['user_id'])->where('lau.status', 1);
				$unit = $this->db->get()->row_array();

				if($getData['role_id'] == 1 || $getData['role_id'] == 2) $unit = NULL;

				$newdata = array(
					'user_id'=>$getData['user_id'],
					'first_name'=>$getData['first_name'],
					'last_name'=>$getData['last_name'],
					'email_id'=>$getData['email_id'],
					'mobile_number'=>$getData['mobile_number'],
					'user_role'=>$getData['role_id'],
					'image'=>$getImage['image'],
					'unit_id'=>(is_null($unit) ? NULL : $unit['client_id'])
					// 'account_group'=>$getAccGrp,
					// 'house_bank'=>$getHseBnk
				);
				$this->jsonify(array(
					'user' => $newdata,
					'status' => 1
				));
				exit();
			}else {
				$this->jsonify(array(
					'msg' => 'Invalid Credentials.',
					'status' => 0
				));
				exit();
			}
		}
		else {
			$this->jsonify(array(
				'msg' => 'Oops! Some Error Occured!!!',
				'status' => 0
			));
			exit();
		}
	}

	//forgot password using email
	public function forgotpass()
	{
		$data = (array)json_decode(file_get_contents("php://input"));
		if(isset($data['purpose']) && $data['purpose'] == 'forgotpassword') {
			if(isset($data['email_id'])) {
				$baseurl = base_url();
				$check = $this->db->where('email_id', $data['email_id'])->get('tbl_users');
				if($check->num_rows() == 0) {
		        	$this->jsonify(array(
						'status' => 0,
						'msg' => 'Enter email id is not registered. please contact admin'
					));
					exit();
				}
				else{
					$get = $check->row_array();
					if($get['status'] == 0) {
						$this->jsonify(array(
							'status' => 0,
							'msg' => 'Enter email id is block. please contact admin'
						));
						exit();
					}
					$alpha   = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		            $numeric = str_shuffle('0123456789');
		            $code = substr($alpha, 0, 4) . substr($numeric, 0, 2);
		            $code = str_shuffle($code);
					$code = $code.uniqid();
					$update = $this->db->where('email_id', $data['email_id'])->update('tbl_users', array('forgot_pass' => $code));
					/*$config = Array(
						'protocol' => 'smtp',
					    'smtp_host' => 'smtp.office365.com',
					    'smtp_user' => 'me-icrisat@cgiar.org',
					    'smtp_pass' => '$Admin#2021',
					    'smtp_crypto' => 'tls',    
					    'newline' => "\r\n",
					    'smtp_port' => 587,
					    'charset' => 'utf-8',
					    'starttls' => TRUE,
						'mailtype' => 'html'
					);
					$this->load->library('email', $config);
					$this->email->set_crlf( "\r\n" );
					$this->email->set_newline("\r\n");
					$this->email->from('me-icrisat@cgiar.org','ICRISAT M&E');
					$this->email->to($data['email_id']);
					$this->email->subject('Password Mail');
					$this->email->set_mailtype("html");*/
					$this->load->library('phpmailer_lib');
			        $mail = $this->phpmailer_lib->load();
			        
			        // SMTP configuration
			        $mail->isSMTP();
			        $mail->Host     = 'smtp.office365.com';
			        $mail->SMTPAuth = true;
			        $mail->Username = 'me-icrisat@cgiar.org';
			        $mail->Password = '$Admin#2021';
			        $mail->SMTPSecure = 'tls';
			        $mail->Port     = 587;
			        
			        $mail->setFrom('me-icrisat@cgiar.org', 'Mbook');
			        $mail->addReplyTo('noreply@cgiar.org', 'Mbook');
			        
			        // Add a recipient
			        $mail->addAddress($data['email_id']);
			        
			        // Email subject
			        $mail->Subject = 'Password Reset Mail';
			        
			        // Set email format to HTML
			        $mail->isHTML(true);
					$data = array(
						'name'=> $get['first_name'].' '.$get['last_name'],
						'link'=> '<a href = "'.base_url().'password/cpassword/'.$code.'/">Set a New Password</a>'
					);
					$body = $this->load->view('passwordlinkemail.php',$data,TRUE);
					$mail->Body = $body;
					if(!$mail->send()){
						$this->jsonify(array(
							'status' => 1,
							'msg' => $mail->ErrorInfo
						));
						exit();
			        }else{
			            $this->jsonify(array(
							'status' => 1,
							'msg' => 'Password Reset Link Sent Successfully to your registered email id.'
						));
						exit();
			        }
				}
			}
			else{
				$this->jsonify(array(
					'status' => 0,
					'msg' => 'Email id is required'
				));
				exit();
			}
		}
	}

	//reset password using forgot key
	public function resetpass()
	{
		$data = (array)json_decode(file_get_contents("php://input"));
		if(isset($data['purpose']) && $data['purpose'] == 'changepassword') {
			if(isset($data['forgot_pass'])) {
				$baseurl = base_url();
				$check = $this->db->where('forgot_pass', $data['forgot_pass'])->get('tbl_users');
				if($check->num_rows() == 0) {
					$this->jsonify(array(
						'status' => 0,
						'msg' => 'Link is not working.'
					));
					exit();
				}
				else{
					$get = $check->row_array();
					if($get['status'] == 0) {
						$this->jsonify(array(
							'status' => 0,
							'msg' => 'Enter email id is block. please contact admin'
						));
						exit();
					}

					$user_id = $get['user_id'];
					$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
					$saltedPW = $data['new_password'] . $salt;
					$hashedPW = hash('sha256', $saltedPW);
					$newdata = array(
						'password'=>$hashedPW,
						'salt'=>$salt
					);
					$query = $this->db->where('user_id', $user_id)->update('tbl_users', $newdata);
					$update = $this->db->where('user_id', $user_id)->update('tbl_users', array('forgot_pass' => NULL));
					if($update)
					{
						$this->jsonify(array(
							'status' => 1,
							'msg' => 'Password reset successfully.'
						));
						exit();
					}
					else
					{
						$this->jsonify(array(
							'status' => 0,
							'msg' => 'Something is wrong!.'
						));
						exit();
					}
				}
			}

			else{
				$this->jsonify(array(
					'status' => 0,
					'msg' => 'something went wrong.'
				));
				exit();
			}
		}

		else{
			$this->jsonify(array(
				'status' => 0,
				'msg' => 'Purpose not received.'
			));
			exit();
		}
	}

	// update profile image
	public function profileimage()
	{	
		$data = (array)json_decode(file_get_contents("php://input"));
		if(isset($data['purpose']) && $data['purpose'] == 'profileimage') {
			date_default_timezone_set("UTC");
			if(!defined('UPLOAD_DIR')) define('UPLOAD_DIR', 'uploads/user/');
			$crop = $data['img'];
			$crop = str_replace('data:image/jpeg;base64,', '', $crop);
			$crop = str_replace(' ', '+', $crop);
			$cropdata = base64_decode($crop);
			$file = uniqid() . '.jpg';
			$url = UPLOAD_DIR . $file;
			
			//file_put_contents(UPLOAD_DIR . $file, $cropdata);
			$this->load->model('Compress_model');
			$filename = $this->Compress_model->compress_image_mobile($cropdata, $url, 90);

			$imgData = array(
				'user_id' => $data['id'],
				'image' => $file,
				'original_image' => $file,
				'ip_address' => $this->input->ip_address(),
				'regdate' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			$this->db->where('user_id', $data['id'])->update('tbl_images', array('status' => 0));
			$insert = $this->db->insert('tbl_images', $imgData);
			
			$this->jsonify(array(
				'img' => $file,
				'status' => 1
			));
			exit();
		}else{
			$this->jsonify(array(
				'status' => 0,
				'msg' => 'Purpose not received.'
			));
			exit();
		}
	}

	// update profile password
	public function changepassword()
	{	
		$data = (array)json_decode(file_get_contents("php://input"));
		if(isset($data['purpose']) && $data['purpose'] == 'changepassword') {
			$pass = (array)$data['password'];
			$checkuser = $this->db->select('salt')->where("user_id", $data['id'])->get('tbl_users');
			if($checkuser->num_rows() === 0) {
				$this->jsonify(array(
					'msg' => 'Sorry! Cannot change password. Please try again later.',
					'status' => 0
				));
				exit();
			}

			$getData = $checkuser->row_array();
			$password = $pass['old'];
			$salt = $getData['salt'];
			$saltedPW =  $password . $salt;
			$hashedPW = hash('sha256', $saltedPW);
			$newData = array(
			    'password' => $hashedPW,
			    'user_id' => $data['id']
			);
			$query = $this->db->where($newData)->get('tbl_users');

			if($query->num_rows() == 0) {
				$this->jsonify(array(
					'msg' => 'Current password mismatch.',
					'status' => 0
				));
				exit();
			}

			if($pass['old'] == $pass['new']){
				$this->jsonify(array(
					'msg' => 'The New Password must differ from Current Password.',
					'status' => 0
				));
				exit();
			}

			$salt = bin2hex(random_bytes(32));
			$saltedPW =  $pass['new'] . $salt;
			$hashedPW = hash('sha256', $saltedPW);
			$newdata = array(
				'password'=>$hashedPW,
				'salt'=>$salt
			);

			$update = $this->db->where('user_id', $data['id'])->update('tbl_users', $newdata);
			$this->jsonify(array(
				'msg' => 'Your password has been changed. Please login again to continue.',
				'status' => 1
			));
			exit();
		}else{
			$this->jsonify(array(
				'status' => 0,
				'msg' => 'Purpose not received.'
			));
			exit();
		}
	}

	//logout ++++++++ session
	public function logout()
	{
	$data = (array)json_decode(file_get_contents("php://input"));
		if(isset($data['purpose']) && $data['purpose'] == 'logout') {
			$this->jsonify(array(
				'status' => 1
			));
			exit();
		}
	}

	public function jsonify($data)
	{
		print_r(json_encode($data));
	}
}