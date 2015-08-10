<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		auth();
		$this->load->library('form_validation');
		$this->load->model('user_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
		$item['source'] =  site_url('users/grid_data');
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
	
	function grid_data()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->user_model->grid_data();
	}
	
	function call_form($id = FALSE)
    {
        if($id != 0)
        {
            $data = (array) $this->user_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'nip' => '',
                'name' => '',
                'email' => '',
                'id_division'=>'',
                'id_user_level'=>''
            );
		}
		$data['opt_division'] = $this->user_model->opt_division();
		$data['opt_level'] = $this->user_model->opt_level();
		$this->load->view('form01', $data);
    }
	
	function call_form_upload($id = FALSE)
    {
        $this->load->view('form02');
    }
	
	function submit_data()
    {
       $this->form_validation->set_rules('nip', 'NIP', 'required|numeric');
	   $this->form_validation->set_rules('name', 'name', 'required');
	   $this->form_validation->set_rules('email', 'email', 'required|valid_email');
	   $this->form_validation->set_rules('division', 'division', 'required');
	   $this->form_validation->set_rules('level', 'level', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'nip' => $this->input->post('nip'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'id_division' => $this->input->post('division'),
                'id_user_level' => $this->input->post('level')
            );
			$json = array('status'=> 1, 'alert'=>'Data has been added');
            $this->user_model->save_data($save);            
        }
        echo json_encode($json);
    }

    function xx()
    {
        $name = "Cumi";
        $jj = $name * 20;
        echo $jj."<br>";
    }

    function block_user($id)
	{
		$this->user_model->block_user($id);
		redirect('members');
	}
	
    function unblock_user($id)
	{
		$this->user_model->unblock_user($id);
		redirect('members');
	}

    function showList() {
        $this->load->library('controllerlist'); // Load the library
        print_r($this->controllerlist->getControllers());
    }
	
}