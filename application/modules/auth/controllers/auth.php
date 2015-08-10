<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	 
    function __construct()
    {
        parent:: __construct();
        $this->load->model('auth_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
		$this->load->helper('cookie');
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
                $this->session->set_flashdata('alert', 'Welcome '.$this->session->userdata('name').'!');
                redirect('dashboard');
            }
        }
    }
	
    function loginRecord(){
		$this->load->model('daily_attendance/daily_attendance_model','dam');
		$this->load->model('user_request/user_request_model','urm');
		date_default_timezone_set("Asia/Jakarta");
		$timeFirst = date('H:i:s',$this->input->post('tm'));
		$date = date('Y-m-d',$this->input->post('tm'));
		
		$timeCompare = ($this->input->post('tm') - strtotime('08:00:00'));
		$check = $this->db->get_where('daily_attendance', array('id_user'=>$this->session->userdata('id'), 'date'=>$date));
		if($check->num_rows()>0){
			$json = array('status'=> 1, 'alert'=>'You have login, Thanks!');
			echo json_encode($json);
			exit();
		}
		$data['id_user'] = $this->session->userdata('id');
		$data['login_time'] = $timeFirst;
		$data['late'] = $timeCompare;
		$data['date'] = $date;
		$record = $this->dam->set_daily_attendance($data);
		if($record){
			
			$time = new Datetime();
			$post = array();
			
			/*=======================
			Start Attendance
			=======================*/
			$att = array();
			
			$att['date'] = $date;
			$att['id_user'] = $this->session->userdata('id');
			
			
			if($this->input->post('tm') > strtotime('08:00:00')){
				
				/*=======================
				Set Request
				=======================*/
				
				$post['id_user'] = $this->session->userdata('id');
				$post['start_time'] = strtotime('08:00:00');
				$post['end_time'] = $this->input->post('tm');
				$post['reason'] = $this->input->post('reason');
				if($this->input->post('tm') < strtotime('12:00:00')){
					$post['status'] = 4;
				}
				else if( $this->input->post('tm') >= strtotime('12:00:00')){
					$post['status'] = 8;
				}
				$id_request = $this->urm->createRequest($post,false);
				
				/*=======================
				End Request
				=======================*/
				$att['id_status'] = $post['status'];
				$att['id_request'] = $id_request;
			}else{
				$att['id_status'] = 3;
			}
			$attendance = $this->dam->set_attendance($att);
			/*=======================
			End Attendance
			=======================*/
			if($attendance){
				$json = array('status'=> 1, 'alert'=>'Success Record your login time');
			}else{
				$json = array('status'=> 0, 'alert'=>'Fail to record your login time');
			}
		}
		else{
			$json = array('status'=> 0, 'alert'=>'Fail to login');
		}
		echo json_encode($json);
		exit();
	}
	function checkout(){
		$a = $this->auth_model->logout($this->session->all_userdata());
		echo json_encode($a);
		// return true;
	}
    function logout()
    {
        $this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
        $this->session->set_flashdata('alert', 'Thanks, You have successfuly logged out!');
        redirect('auth');
    }
}