<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');
		$this->session->keep_flashdata('err');
		$this->session->keep_flashdata('succ');
	}

	public function index()
	{
		show_404();
	}
	
	public function lostpassword()
	{
		$baseurl = base_url();
		date_default_timezone_set("UTC");
		if($this->session->userdata('login_id') != null && $this->session->userdata('login_id') != '') {
			redirect($baseurl);
		}

		//$this->load->view('header');
		$this->load->view('forgotpassword');
		$this->load->view('footer');
	}

	public function resetpassword()
	{
		$error = array('email' => '', 'status' => 0);
		$error['csrfHash'] = $this->security->get_csrf_hash();
		$error['csrfName'] = $this->security->get_csrf_token_name();

		//Email validation
		$emailRex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
		$email = $this->input->post('email');
		if(strlen($email) == 0) {
			$error['email'] = 'Email address is mandatory.';
			$error['status'] = 1;
		} else if(!preg_match($emailRex, $email)) {
			$error['email'] = 'Provided email is not a valid email address.';
			$error['status'] = 1;
		}

		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		$check = $this->db->where('email_id', $this->input->post('email'))->where('status', 1)->get('tbl_users');
		if($check->num_rows() == 0) {
			echo json_encode(array(
				'msg' => 'Sorry!!! The email id is not recognized.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'sentstatus' => 0
			));
			exit();
		}

		$get = $check->row_array();
		if($get['status'] == 0) {
			echo json_encode(array(
				'msg' => 'Sorry!!! The email id is blocked by Admin.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'sentstatus' => 0
			));
			exit();
		}

		$code = $get['user_id'].uniqid();
		$update = $this->db->where('email_id', $this->input->post('email'))->update('tbl_users', array('forgot_pass' => $code));

		// Get mail configuration from db
		$emaildetails = $this->db->where('status', 1)->get('emailconfiguration_details')->row_array();
		
		$this->load->library('phpmailer_lib');
		$mail = $this->phpmailer_lib->load();
		$mail->isSMTP();
		//$mail->SMTPDebug = 2;
		$mail->Host     = 'smtp.office365.com';
		$mail->SMTPAuth = true;
		$mail->Username = $emaildetails['email_id'];
		$mail->Password = $emaildetails['password'];
		$mail->SMTPSecure = 'tls';
		$mail->Port     = 465;
		$mail->setFrom('me-icrisat@cgiar.org', 'ICRISAT M&E');
		$mail->addReplyTo('me-icrisat@cgiar.org', 'ICRISAT M&E');
		$mail->addAddress($this->input->post('email'));
		$mail->Subject = 'Password Reset Mail';
		$mail->isHTML(true);
		$mail->priority = 1;

		$data = array(
			'name'=> $get['first_name'].' '.$get['last_name'],
			'link'=> '<a href = "'.base_url().'password/cpassword/'.$code.'/">Set a New Password</a>'
		);
		$body = $this->load->view('passwordlinkemail.php',$data,TRUE);
		$mail->Body = $body;

		/*// Email body content
		$mailContent = "Dear ".$get['first_name']." ".$get['last_name'].",<br/>Please click on the link below to change your password. If clicking on the link does not work, then copy and paste the link onto your browser's URL.<br/><br/><h4>Activation Link</h4><a href='".$baseurl.'password/cpassword/'.$code."'>".$baseurl.'password/cpassword/'.$code."</a>";
		$mail->Body = $mailContent;*/

		// Send email
		if(!$mail->send()) {
			// $mail->ErrorInfo
			echo json_encode(array(
				'msg' => 'Sorry!!! Mail could not be sent due to SMTP server error.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'sentstatus' => 0
			));
		} else {
			echo json_encode(array(
				'msg' => 'Password Reset Link Sent Successfully to your Email-ID.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'sentstatus' => 1
			));
		}
		exit();
	}

	public function cpassword()
	{
		$baseurl = base_url();
		date_default_timezone_set("UTC");
		if($this->session->userdata('login_id') != null && $this->session->userdata('login_id') != '') {
			redirect($baseurl);
		}
		
		$forgot_pass = $this->uri->segment(3);
		if(!$forgot_pass || empty($forgot_pass)) {
			show_404();
		}

		$check = $this->db->query("SELECT email_id FROM tbl_users WHERE forgot_pass = '".$forgot_pass."'");
		if($check->num_rows() == 0) {
			$this->load->view('linkexpire');
		} else {
			$this->load->view('resetpassword');
			$this->load->view('footer');
		}
	}

	public function changepass()
	{
		if(!$this->input->post('forgot_pass')) {
			echo json_encode(array(
				'msg' => 'Sorry!!! It seems this link has expired. Please click on the link in the mail again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'updatestatus' => 0
			));
			exit();
		}
		
		$forgot_pass = $this->input->post('forgot_pass');
		$checkinfo = $this->db->query("SELECT email_id FROM tbl_users WHERE forgot_pass = '".$forgot_pass."'");
		if($checkinfo->num_rows() === 0) {
			echo json_encode(array(
				'msg' => 'Sorry!!! It seems this link has expired. Please click on the link in the mail again.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'updatestatus' => 0
			));
			exit();
		}
		
		$error = array('password' => '', 'cpassword' => '', 'status' => 0);
		$error['csrfName'] = $this->security->get_csrf_token_name();
		$error['csrfHash'] = $this->security->get_csrf_hash();

		$password = $this->input->post('password');
		if(strlen($password) == 0) {
			$error['password'] = 'Password is mandatory.';
			$error['status'] = 1;
		} else if((strlen($password) < 6) || (strlen($password) > 25)) {
			$error['password'] = 'Password must be between 6 to 25 characters in length.';
			$error['status'] = 1;
		}

		$cpassword = $this->input->post('cpassword');
		if(strlen($cpassword) == 0) {
			$error['cpassword'] = 'Confirm password is mandatory.';
			$error['status'] = 1;
		} else if($password != $cpassword) {
			$error['cpassword'] = 'Confirm password must match with password.';
			$error['status'] = 1;
		}

		if($error['status'] > 0) {
			echo json_encode($error);
			exit();
		}
		
		$get = $checkinfo->row_array();
		$salt = bin2hex(random_bytes(32));
		$saltedPW =  $this->input->post('password') . $salt;
		$hashedPW = hash('sha256', $saltedPW);
		$newdata = array(
			'password' => $hashedPW,
			'salt' => $salt
		);

		$newdata = $this->security->xss_clean($newdata);
		$query = $this->db->where('forgot_pass', $forgot_pass)->update('tbl_users', $newdata);
		$update = $this->db->where('email_id', $get['email_id'])->update('tbl_users', array('forgot_pass' => NULL));
		if($update) {
			echo json_encode(array(
				'msg' => 'Password set successfully. You can now <a href="'.base_url().'">Login</a> using your new password.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'updatestatus' => 1
			));
		} else {
			echo json_encode(array(
				'msg' => 'Sorry!!! Cannot set new password. Please try after sometime.',
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' => $this->security->get_csrf_hash(),
				'updatestatus' => 0
			));
		}
		exit();
	}
	
	public function pmessage()
	{
		$this->load->view('pmessage');
		$this->load->view('footer');
	}
}
