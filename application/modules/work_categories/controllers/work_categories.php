<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_categories extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		//auth();
		$this->load->library('form_validation');
		$this->load->model('work_category_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
		$item['source'] =  site_url('work_categories/grid_data');
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
		echo $this->work_category_model->grid_data();
	}
	
	function call_form($id = FALSE)
    {
        if($id)
        {
            $data = (array) $this->work_category_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'category' => ''
            );
        }
        $this->load->view('form', $data);
    }
	
	function submit_data()
    {
       $this->form_validation->set_rules('category', 'category', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'category' => $this->input->post('category')
            );
            $this->work_category_model->save_data($save);
            $json = array('status'=> 1, 'alert'=>'Success! Data has been edited');
        }
        echo json_encode($json);
    }
	
	function block_category($id)
	{
		$this->work_category_model->block_category($id);
		redirect('work_categories');
	}
	
    function unblock_category($id)
	{
		$this->work_category_model->unblock_category($id);
		redirect('work_categories');
	}

	function delete_data($id)
	{
		$this->work_category_model->delete_data($id);
		redirect('work_categories');
	}

}