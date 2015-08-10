<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_error extends CI_Controller {
	 
    function index()
	{
		$this->load->view('error_404.php');
	}

}