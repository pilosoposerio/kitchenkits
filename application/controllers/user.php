<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct(){
		parent:: __construct();
		$this->load->model('User_model');
	}

	public function index(){
		$this->load->view('login');	
	}

	public function login(){
		$con = mysqli_connect("localhost","root","","kitchen_kits");
		$user = mysqli_real_escape_string($con, $_POST['username']);
		$pass = mysqli_real_escape_string($con, sha1($_POST['password']));
		$userdata = $this->User_model->login_check($user, $pass);
		if(isset($userdata)){
			if ($userdata[0]->status == 'A') {
				$_SESSION = array(
					'id' => $userdata[0]->id,
					'user' => $userdata[0]->username,
					'pass' => $userdata[0]->password,
					'utype' => $userdata[0]->user_type_id,
					'logged_in' => TRUE
				);
				$this->User_model->logged_in($_SESSION['id']);
				switch ($userdata[0]->user_type_id) {
					case '1':
						redirect('admin_dashboard');
						break;
					case '2':
						$managerdata = $this->User_model->get_manager($_SESSION['id']);
						$new = array(
							'name' => $managerdata[0]->name
						);
						$this->session->set_userdata($new);
						redirect('branch');
						break;
					case '3':
						$customerdata = $this->User_model->get_customer($_SESSION['id']);
						$new = array(
							'fname' => $customerdata[0]->first_name,
							'lname' => $customerdata[0]->last_name
						);
						$this->session->set_userdata($new);
						redirect();
						break;
				}
			}
			else{
				$this->session->set_flashdata('error_msg','Your Account Has Been Disabled!');
				redirect('login');
			}
		}
		else{
			$this->session->set_flashdata('error_msg','Invalid Username or Password, Try again!');
			redirect('login');
		}
		mysqli_close($con);
	}

	public function logout(){
		$this->User_model->logged_out($_SESSION['id']);
		session_destroy();
		redirect('login');
	}

	public function register_view(){
		$this->load->view('register');
	}

	public function register(){
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]',
			array(
		 		'is_unique' => 'Username already taken'
			));
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]',
			array(
		 		'matches' => 'Passwords do not match'
			));
		$this->form_validation->set_rules('haddress', 'Password', 'required');
		if ($this->form_validation->run() == TRUE){
			$user_type_id = $_GET['id'];
	    	$userdata = array(
				'username' => str_replace("'","’",$_POST['username']),
				'password' => str_replace("'","’",sha1($_POST['password'])),
				'status' => 'A',
				'user_type_id' => $user_type_id
			);
			$this->User_model->add_customer_account($userdata);
			$user_id = $this->db->insert_id();
			$code = $this->User_model->get_code(3);
			$this->User_model->update_counter($code[0]->ct_count+1,3);
			$customerdata = array(
				'user_id' => $user_id,
				'code' => $code[0]->ct_code.(sprintf('%05d', $code[0]->ct_count+1)),
				'first_name' => str_replace("'","’",$_POST['fname']),
				'last_name' => str_replace("'","’",$_POST['lname']),
				'email_address' => str_replace("'","’",$_POST['emailaddr']),
				'home_address' => str_replace("'","’",$_POST['haddress'])
			);
			$this->User_model->add_customer($customerdata);
			$_SESSION = array(
				'id' => $user_id,
				'user' => str_replace("'","’",$_POST['username']),
				'pass' => str_replace("'","’",sha1($_POST['password'])),
				'utype' => $user_type_id,
				'logged_in' => TRUE
			);
			$this->User_model->logged_in($user_id);
			redirect();
	    }
	    else{
			$this->load->view('register');
	    }
	}
}
