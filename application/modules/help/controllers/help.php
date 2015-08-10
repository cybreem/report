<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		auth();
		$this->load->library('form_validation');
		
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
		$item['source'] =  site_url('members/grid_data');
		$data = array(
			'content'=>$this->load->view('help_view', $item, TRUE),
			'script'=>$this->load->view('help_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
	
	

}