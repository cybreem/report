<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller {
	 
    function __construct()
    {
        parent:: __construct();
        $this->load->model('auth_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }   

	function index()
	{
		$notification = $this->notification_model->notification();
		$data['notification'] = $notification;
		$this->load->view('template',$data);
	}
}