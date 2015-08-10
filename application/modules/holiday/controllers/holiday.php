<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday extends CI_Controller {
	
	    function __construct()
    {
        parent:: __construct();
        $this->load->helper('form');
		$this->load->helper('auth');
		//auth();
        $this->load->library('form_validation');
        $this->load->model('holiday_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
    }

    function index()
    {
        $item['source'] =  site_url('holiday/grid_data');
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
        echo $this->holiday_model->grid_data();
    }
    
    function call_form($id = FALSE)
    {
        if($id)
        {
            $data = (array) $this->holiday_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'date' => '',
                'holiday_desc'=>''
            );
        }
        $this->load->view('form', $data);
    }
    
    function submit_data()
    {
       $this->form_validation->set_rules('date', 'date', 'required');
       $this->form_validation->set_rules('holiday_desc', 'holiday name', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'date' => $this->input->post('date'),
                'holiday_desc' => $this->input->post('holiday_desc')
            );
            $this->holiday_model->save_data($save);
            $json = array('status'=> 1, 'alert'=>'Holiday date has been added');
        }
        echo json_encode($json);
    }
	
	function delete_data($id)
    {
        $this->holiday_model->delete_data($id);
        redirect('holiday');
    }

	
}