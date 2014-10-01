<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('m_users');
    }
	function index()
	{
		$this->load->view('users');
	}

	function login()
	{
		$username = $this->input->post('username');
		$upass = $this->input->post('password');

		$hashed_password = $this->hash_pass($upass);

		$user = $this->m_users->get_user_details($username, $hashed_password);
		// print_r($user);die;

		if($user)
		{
			$usertype = $user[0]['user_type'];
			$username = $user[0]['username'];

			if ($usertype == 'student') {
				$student = $this->m_users->getUser('students', $username);
				// print_r($student);die;
				if($student)
				{
					$data['username'] = $student[0]['id'];
					$data['firstname'] = $student[0]['firstname'];
					$data['lastname'] = $student[0]['lastname'];
					$data['email'] = $student[0]['student_email'];
					$data['logged_in'] = TRUE;
					$data['user_type'] = $usertype;
					$this->session->set_userdata($data);

					$this->m_users->register_session();
					redirect(base_url() .'student');
				}
				else
				{
					redirect(base_url(). 'home/error');
				}
			}
			else if ($usertype == 'administrator') {
				$admin = $this->m_users->getUser('administrator', $username);
				// print_r($admin);die;
				if($admin)
				{
					$data['username'] = $admin[0]['id'];
					$data['firstname'] = $admin[0]['f_name'];
					$data['lastname'] = $admin[0]['l_name'];
					$data['email'] = $admin[0]['email'];
					$data['logged_in'] = TRUE;
					$data['user_type'] = $usertype;
					$this->session->set_userdata($data);
	
					$this->m_users->register_session();
					redirect(base_url() .'admin');
				}
				else
				{
					redirect(base_url(). 'home/error');
				}
			}
		}
		else
		{
			redirect(base_url(). 'home/error');
		}
	}
}