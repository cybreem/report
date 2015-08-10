<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Divisions extends CI_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->helper('form');
		$this->load->helper('auth');
		auth();
		$this->load->library('form_validation');
		$this->load->model('division_model');
        if(!$this->session->userdata('logged_in'))
        {
            show_404();
        }
	}

	function index()
	{
		$item['source'] =  site_url('divisions/grid_data');
		$item['chart'] =  site_url('divisions/data_chart');
		$data = array(
			'content'=>$this->load->view('content', $item, TRUE),
			'script'=>$this->load->view('content_js', '', TRUE)
		);
		$this->load->view('template', $data);
	}
	
	function data_chart()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->division_model->get_chart();
	}
	
	function grid_data()
	{
		if(!$this->input->is_ajax_request())
		{
			show_404();
			exit;
		}
		echo $this->division_model->grid_data();
	}
	
	function call_form($id = FALSE)
    {
        if($id)
        {
            $data = (array) $this->division_model->get_data($id);
        }
        else
        {
            $data = array(
                'id' => FALSE,
                'division' => '',
                'leader'=>'',
                'color_label'=>''
            );
        }
		$data['opt_leader'] = $this->division_model->opt_member();
        $this->load->view('form', $data);
    }
	
	function submit_data()
    {
       $this->form_validation->set_rules('division', 'division', 'required');
	   $this->form_validation->set_rules('leader', 'leader', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $json = array('status'=> 0, 'alert'=>validation_errors());
        }
        else
        {
            $save = array(
                'id' => $this->input->post('id'),
                'division' => $this->input->post('division'),
                'color_label' => $this->input->post('label'),
                'leader' => $this->input->post('leader')
            );
            $this->division_model->save_data($save);
            $json = array('status'=> 1, 'alert'=>'Data has been added');
        }
        echo json_encode($json);
    }
	function block_data($id)
	{
		$this->division_model->block_data($id);
		redirect('divisions');
	}

	function unblock_data($id)
	{
		$this->division_model->unblock_data($id);
		redirect('divisions');
	}
	function delete_data($id)
	{
		$this->division_model->delete_data($id);
		redirect('divisions');
	}

}