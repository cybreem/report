<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	 
    function __construct()
    {
        parent:: __construct();
        $this->load->model('auth_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }   

	function index()
	{
		if(!$this->session->userdata('logged_in'))
        {
            $this->load->view('login_page');    
        }
        else
        {
            redirect('dashboard');
        }
	}
    
    function login()
    {
        $this->form_validation->set_error_delimiters('<p class="text-danger text-center">', '</p>');
        $this->form_validation->set_rules('nip', 'NIP', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'trim|required|md5');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('alert', validation_errors());
            redirect('auth');
        }
        else
        {
            $user_log = $this->auth_model->check_detail();
            
            if(!$user_log)
            {
                $this->session->set_flashdata('alert', 'Oops! Incorect NIP or password');
                redirect('auth');
            }
            else
            {
                $this->session->set_userdata($user_log);
                $this->session->set_flashdata('alert', 'Welcome, You have successfuly logged in!');
                redirect('dashboard');
            }
        }
    }
    
    function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->set_flashdata('alert', 'Thanks, You have successfuly logged out!');
        redirect('auth');
    }
}