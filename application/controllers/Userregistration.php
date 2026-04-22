<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userregistration extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		$this->load->model('User_model');
		$this->load->model('Helper_model');
		$this->load->library('email');
	}

	public function index(){
		show_404();
	}

	public function create(){		
		$result = array();

		$this->load->view('commonheader');
		$this->load->view('userregistration');
		$this->load->view('footer');
	}

	public function insert_user(){
		$baseurl = base_url();

		$error = array(
			'status' => 0,
			'csrfHash' => $this->security->get_csrf_hash(),
			'csrfName' => $this->security->get_csrf_token_name()
		);

		$fname = $this->input->post('first_name');
		if(empty($fname)) {
			$error['first_name'] = 'First name is mandatory.';
			$error['status'] = 1;
		}

		$lname = $this->input->post('last_name');
		if(empty($lname)) {
			$error['last_name'] = 'Last name is mandatory.';
			$error['status'] = 1;
		}

		$email = $this->input->post('email');
		if(empty($email)) {
			$error['email'] = 'Email is mandatory.';
			$error['status'] = 1;
		} else {
			$check_emaiid = $this->User_model->check_emaiid();

			if($check_emaiid) {
				if($check_emaiid['status'] == 0) {
					$error['email'] = 'Email has been blocked. Please contact admin for more details.';
					$error['status'] = 1;
				} else {
					$error['email'] = 'Email already exists.';
					$error['status'] = 1;
				}
			}
		}

		$username = $this->input->post('username');
		if(empty($username)) {
			$error['username'] = 'Username is mandatory.';
			$error['status'] = 1;
		} else {
			$check_username = $this->User_model->check_username();

			if($check_username) {
				if($check_username['status'] == 0) {
					$error['username'] = 'Username has been blocked. Please contact admin for more details.';
					$error['status'] = 1;
				} else {
					$error['username'] = 'Username already exists.';
					$error['status'] = 1;
				}
			}
		}

		// $phone = $this->input->post('phone');
		// if(!empty($phone) && !preg_match('/^\+?[0-9]{7,15}$/', preg_replace('/\s+/', '', $phone))) {
		// 	$error['phone'] = 'Please enter a valid phone number.';
		// 	$error['status'] = 1;
		// }

		$password = $this->input->post('password');
		if(empty($password)) {
			$error['password'] = 'Password is mandatory.';
			$error['status'] = 1;
		} else if((strlen($password) < 6) || (strlen($password) > 25)) {
			$error['password'] = 'Password must be between 6 to 25 characters in length.';
			$error['status'] = 1;
		}

		$cpassword = $this->input->post('cpassword');
		if(empty($cpassword)) {
			$error['cpassword'] = 'Confirm password is mandatory.';
			$error['status'] = 1;
		}

		if(!empty($password) && !empty($cpassword) && $password != $cpassword) {
			$error['password'] = 'Both password and confirm password should be same.';
			$error['cpassword'] = 'Both password and confirm password should be same.';
			$error['status'] = 1;
		}

		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}

		$user_id = $this->User_model->insert_userregistration();
		if(!$user_id){
			echo json_encode(array(
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'msg'=>'Sorry! Please try after sometime.',
				'insertstatus' => 0
			));
			exit();
		}else{
			$this->send_activation_email($email, $fname, $lname, $username, $user_id);
			echo json_encode(array(
				'csrfName'     => $this->security->get_csrf_token_name(),
				'csrfHash'     => $this->security->get_csrf_hash(),
				'msg'          => 'User added successfully. Please check your email to activate your account.',
				'insertstatus' => 1
			));
			exit();
		}
	}

	public function forgotpassword(){		
		$result = array();

		$this->load->view('commonheader');
		$this->load->view('forgotpassword');
		$this->load->view('footer');
	}

	public function verify_email() {
		$email = $this->input->post('email');

		$response = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
		);

		if (empty($email)) {
			$response['status'] = 'error';
			$response['msg']    = 'Email is mandatory.';
			echo json_encode($response);
			exit();
		}

		$user = $this->db->where('email_id', $email)->where('status !=', 0)->get('tbl_users')->row_array();

		if (!$user) {
			$response['status'] = 'error';
			$response['msg']    = 'No account found with this email address.';
			echo json_encode($response);
			exit();
		}

		// Generate unique reset token and store in DB
		$code = $user['user_id'] . uniqid();
		$this->db->where('email_id', $email)->update('tbl_users', array('forgot_pass' => $code));

		// Send reset email using CI email library (same as send_activation_email)
		$emailSent = $this->send_reset_email($email, $user['first_name'], $user['last_name'], $code);

		if (!$emailSent) {
			$response['status'] = 'error';
			$response['msg']    = 'Unable to send email. Please try again later.';
			echo json_encode($response);
			exit();
		}

		$response['status'] = 'success';
		$response['msg']    = 'Password reset link has been sent to your email address.';
		echo json_encode($response);
		exit();
	}

	/* reset_password() removed - password reset is now handled via email link
	   through Password::cpassword() and Password::changepass() */

	public function activate($user_id) {
		if (empty($user_id) || !is_numeric($user_id)) {
			show_404();
		}

		$user = $this->db->where('user_id', $user_id)->where('status', 2)->get('tbl_users')->row_array();

		if (!$user) {
			// Already activated or doesn't exist
			$this->load->view('commonheader');
			$this->load->view('activation_result', array(
				'success' => false,
				'message' => 'This activation link is invalid or your account is already active.'
			));
			$this->load->view('footer');
			return;
		}

		$this->db->where('user_id', $user_id)->update('tbl_users', array('status' => 1));

		$this->load->view('commonheader');
		$this->load->view('activation_result', array(
			'success' => true,
			'message' => 'Your account has been activated successfully! You can now login.'
		));
		$this->load->view('footer');
	}

	private function send_reset_email($email, $fname, $lname, $code) {
		ob_start();
		try {
			$this->config->load('email');
			$email_config = array(
				'protocol'    => $this->config->item('protocol'),
				'smtp_host'   => $this->config->item('smtp_host'),
				'smtp_port'   => $this->config->item('smtp_port'),
				'smtp_user'   => $this->config->item('smtp_user'),
				'smtp_pass'   => $this->config->item('smtp_pass'),
				'smtp_crypto' => $this->config->item('smtp_crypto'),
				'mailtype'    => $this->config->item('mailtype'),
				'charset'     => $this->config->item('charset'),
				'newline'     => $this->config->item('newline'),
			);
			$this->email->initialize($email_config);

			$full_name  = $fname . ' ' . $lname;
			$site_name  = 'G-Feast';
			$reset_link = base_url('password/cpassword/' . $code);

			$message = '
			<html>
			<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; padding: 20px;">
				<h2 style="color: #4CAF50;">Password Reset Request</h2>
				<p>Hi <strong>' . $full_name . '</strong>,</p>
				<p>We received a request to reset your password for your ' . $site_name . ' account.</p>
				<p>Please click the button below to set a new password:</p>
				<p style="text-align:center; margin: 30px 0;">
					<a href="' . $reset_link . '" style="background-color:#4CAF50; color:#fff; padding:12px 30px; text-decoration:none; border-radius:5px; font-size:16px;">
						Set New Password
					</a>
				</p>
				<p>Or copy and paste this link in your browser:<br>
					<a href="' . $reset_link . '">' . $reset_link . '</a>
				</p>
				<p>If you didn\'t request this, you can safely ignore this email. Your password will not be changed.</p>
				<br>
				<p>Thank you,<br><strong>' . $site_name . ' Team</strong></p>
			</body>
			</html>';

			$this->email->from($this->config->item('smtp_user'), $site_name);
			$this->email->to($email);
			$this->email->subject('Password Reset - ' . $site_name);
			$this->email->message($message);

			if (!$this->email->send()) {
				log_message('error', 'Password reset email failed: ' . $this->email->print_debugger());
				ob_end_clean();
				return false;
			}
		} catch (Exception $e) {
			log_message('error', 'Password reset email exception: ' . $e->getMessage());
			ob_end_clean();
			return false;
		}
		ob_end_clean();
		return true;
	}

	private function send_activation_email($email, $fname, $lname, $username, $user_id) {
		ob_start();
		try {
			$this->config->load('email');
			$email_config = array(
				'protocol'    => $this->config->item('protocol'),
				'smtp_host'   => $this->config->item('smtp_host'),
				'smtp_port'   => $this->config->item('smtp_port'),
				'smtp_user'   => $this->config->item('smtp_user'),
				'smtp_pass'   => $this->config->item('smtp_pass'),
				'smtp_crypto' => $this->config->item('smtp_crypto'),
				'mailtype'    => $this->config->item('mailtype'),
				'charset'     => $this->config->item('charset'),
				'newline'     => $this->config->item('newline'),
			);
			$this->email->initialize($email_config);

			$full_name       = $fname . ' ' . $lname;
			$site_name       = 'G-Feast';
			$activation_link = base_url('userregistration/activate/' . $user_id);

			$message = '
			<html>
			<body style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; padding: 20px;">
				<h2 style="color: #4CAF50;">Welcome to ' . $site_name . '!</h2>
				<p>Hi <strong>' . $full_name . '</strong>,</p>
				<p>Your account has been successfully created with the username: <strong>' . $username . '</strong></p>
				<p>Please click the button below to activate your account:</p>
				<p style="text-align:center; margin: 30px 0;">
					<a href="' . $activation_link . '" style="background-color:#4CAF50; color:#fff; padding:12px 30px; text-decoration:none; border-radius:5px; font-size:16px;">
						Activate My Account
					</a>
				</p>
				<p>Or copy and paste this link in your browser:<br>
					<a href="' . $activation_link . '">' . $activation_link . '</a>
				</p>
				<p>If you have any questions, please contact our support team.</p>
				<br>
				<p>Thank you,<br><strong>' . $site_name . ' Team</strong></p>
			</body>
			</html>';

			$this->email->from($this->config->item('smtp_user'), $site_name);
			$this->email->to($email);
			$this->email->subject('Activate Your ' . $site_name . ' Account');
			$this->email->message($message);

			if (!$this->email->send()) {
				log_message('error', 'Activation email failed: ' . $this->email->print_debugger());
			}
		} catch (Exception $e) {
			log_message('error', 'Activation email exception: ' . $e->getMessage());
		}
		ob_end_clean();
	}
}